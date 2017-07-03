<?php

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\UpdateStockEvent;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombination;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use LegacyProductAttributes\Model\LegacyOrderProductAttributeCombination;
use LegacyProductAttributes\Model\LegacyOrderProductAttributeCombinationQuery;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Core\Event\Payment\ManageStockOnCreationEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Exception\TheliaProcessException;
use Thelia\Model\Attribute;
use Thelia\Model\AttributeAv;
use Thelia\Model\CartItemQuery;
use Thelia\Model\ConfigQuery;
use Thelia\Model\ModuleQuery;
use Thelia\Model\Order;
use Thelia\Model\OrderProductAttributeCombination;
use Thelia\Model\OrderStatusQuery;
use Thelia\Module\PaymentModuleInterface;
use Thelia\Tools\I18n;

/**
 * Listener for order related events.
 *
 * Ensure legacy attributes stock management.
 */
class OrderAction implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var int
     */
    protected $allowNegativeStock;

    /**
     * OrderAction constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        $this->allowNegativeStock = intval(ConfigQuery::read('allow_negative_stock', 0));
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_PRODUCT_AFTER_CREATE => ['createOrderProductAttributeCombinations', 128],
            TheliaEvents::ORDER_UPDATE_STATUS => ['updateStatus', 130],
            TheliaEvents::ORDER_AFTER_CREATE => ['orderCreated', 128],
        ];
    }

    /**
     * Save the attribute combinations for the order from our cart item attribute combinations.
     *
     * @param OrderProductEvent $event
     *
     * @throws PropelException
     */
    public function createOrderProductAttributeCombinations(OrderProductEvent $event)
    {
        $legacyCartItemAttributeCombinations = LegacyCartItemAttributeCombinationQuery::create()
            ->findByCartItemId($event->getCartItemId());

        $locale = $this->requestStack->getCurrentRequest()->getSession()->getLang()->getLocale();

//echo "$legacyCartItemAttributeCombinations=".$$legacyCartItemAttributeCombinations.count();
// exit();

        /** @var LegacyCartItemAttributeCombination $legacyCartItemAttributeCombination */
        foreach ($legacyCartItemAttributeCombinations as $legacyCartItemAttributeCombination) {
            /** @var Attribute $attribute */
            $attribute = I18n::forceI18nRetrieving(
                $locale,
                'Attribute',
                $legacyCartItemAttributeCombination->getAttributeId()
            );

            /** @var AttributeAv $attributeAv */
            $attributeAv = I18n::forceI18nRetrieving(
                $locale,
                'AttributeAv',
                $legacyCartItemAttributeCombination->getAttributeAvId()
            );

            $orderProductAttributeCombination = new OrderProductAttributeCombination();

            $orderProductAttributeCombination
                ->setOrderProductId($event->getId())
                ->setAttributeTitle($attribute->getTitle())
                ->setAttributeChapo($attribute->getChapo())
                ->setAttributeDescription($attribute->getDescription())
                ->setAttributePostscriptum($attribute->getPostscriptum())
                ->setAttributeAvTitle($attributeAv->getTitle())
                ->setAttributeAvChapo($attributeAv->getChapo())
                ->setAttributeAvDescription($attributeAv->getDescription())
                ->setAttributeAvPostscriptum($attributeAv->getPostscriptum())
                ->save();

            // Save combination legacy information to be able to manage stock later.
            $cartItem = CartItemQuery::create()->findPk($event->getCartItemId());

            (new LegacyOrderProductAttributeCombination())
                ->setOrderProductId($event->getId())
                ->setProductId($cartItem->getProductId())
                ->setQuantity($cartItem->getQuantity())
                ->setAttributeAvId($legacyCartItemAttributeCombination->getAttributeAvId())
                ->save();
        }
    }

    /**
     * Get ordrered items out of stock if we have to do this at order creation.
     *
     * @param OrderEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function orderCreated(OrderEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $order = $event->getOrder();

        if ($this->decreaseStockAtOrderCreation($order, $dispatcher)) {
            $this->manageLegacyStock($order, -1, $dispatcher);
        }
    }

    /**
     * Manage legacy stock when order status changes
     *
     * @param OrderEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function updateStatus(OrderEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $order = $event->getOrder();
        $newStatus = $event->getStatus();

        $decreaseStockAtOrderCreation = $this->decreaseStockAtOrderCreation($order, $dispatcher);

        $canceledStatus = OrderStatusQuery::getCancelledStatus()->getId();
        $paidStatus = OrderStatusQuery::getPaidStatus()->getId();

        if ($newStatus == $canceledStatus) {
            // Order is canceled, get items back in stock
            $this->manageLegacyStock($order, +1, $dispatcher);
        } elseif (! $decreaseStockAtOrderCreation && $newStatus == $paidStatus && $order->isNotPaid() && $order->getVersion() == 1) {
            // Order is paid, get items out of stock
            $this->manageLegacyStock($order, -1, $dispatcher);
        }
    }

    /**
     * Check if we have to decrease stock at order creation or at order payment.
     *
     * @param Order $order
     * @param EventDispatcherInterface $dispatcher
     * @return bool
     */
    protected function decreaseStockAtOrderCreation(Order $order, EventDispatcherInterface $dispatcher)
    {
        $paymentModule = ModuleQuery::create()->findPk($order->getPaymentModuleId());

        /** @var PaymentModuleInterface $paymentModuleInstance */
        $paymentModuleInstance = $paymentModule->createInstance();

        $event = new ManageStockOnCreationEvent($paymentModuleInstance);

        $dispatcher->dispatch(
            TheliaEvents::getModuleEvent(
                TheliaEvents::MODULE_PAYMENT_MANAGE_STOCK,
                $paymentModuleInstance->getCode()
            )
        );

        return
            (null !== $event->getManageStock())
                ? $event->getManageStock()
                : $paymentModuleInstance->manageStockOnCreation();
    }

    /**
     * Increase or decrease legacy attributes stock for a given order.
     *
     * @param Order $order
     * @param int $delta +1 or -1
     */
    protected function manageLegacyStock(Order $order, $delta, EventDispatcherInterface $dispatcher)
    {
        $legacyOrderProductAttributeCombinations = LegacyOrderProductAttributeCombinationQuery::create()
            ->useOrderProductQuery()
                ->filterByOrderId($order->getId())
            ->endUse()
            ->find()
        ;

        /** @var LegacyOrderProductAttributeCombination $legacyOrderProductAttributeCombination */
        foreach ($legacyOrderProductAttributeCombinations as $legacyOrderProductAttributeCombination) {
            if (null !== $legacyProductAttributeValue = LegacyProductAttributeValueQuery::create()
                ->findPk([
                    $legacyOrderProductAttributeCombination->getProductId(),
                    $legacyOrderProductAttributeCombination->getAttributeAvId(),
                ])) {
                // If we're about to decrease stock, check that we have enough items
                if ($delta < 0 && ConfigQuery::checkAvailableStock()) {
                    if ($legacyProductAttributeValue->getStock() < $legacyOrderProductAttributeCombination->getQuantity()) {
                        /** @var AttributeAv $attributeAv */
                        $attributeAv = I18n::forceI18nRetrieving(
                            $this->requestStack->getCurrentRequest()->getSession()->getLang()->getLocale(),
                            'AttributeAv',
                            $legacyOrderProductAttributeCombination->getAttributeAvId()
                        );

                        throw new TheliaProcessException(
                            $attributeAv->getTitle() . " : Not enough stock 3"
                        );
                    }
                }

                $newStock = $legacyProductAttributeValue->getStock()
                    + ($delta * $legacyOrderProductAttributeCombination->getQuantity())
                ;

                if ($newStock < 0 && ! $this->allowNegativeStock) {
                    $newStock = 0;
                }

                $legacyProductAttributeValue
                    ->setStock($newStock)
                    ->save();

                // Update product total stock
                $dispatcher->dispatch(
                    LegacyProductAttributesEvents::UPDATE_PRODUCT_STOCK,
                    new UpdateStockEvent($legacyOrderProductAttributeCombination->getProductId())
                );
            }
        }
    }
}
