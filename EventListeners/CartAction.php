<?php

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductGetPricesEvent;
use LegacyProductAttributes\Form\CartAddFormExtension;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombination;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Action\Cart;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\Cart\CartItemDuplicationItem;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Attribute;
use Thelia\Model\Cart as CartModel;
use Thelia\Model\CartItem;
use Thelia\Model\CartItemQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\Model\Tools\ProductPriceTools;

/**
 * Listener for cart related events.
 */
class CartAction extends Cart
{
    /**
     * Selected attributes values for this request.
     * @var array A map of attribute ids => selected attribute value id.
     */
    protected $legacyProductAttributes = [];

    /**
     * Manage adding an item to the cart when our legacy product attributes are used.
     *
     * @param CartEvent $event
     */
    public function addItem(CartEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $this->getLegacyProductAttributes($event);

        // call the parent method, but using our redefined sub-methods
        parent::addItem($event, $eventName, $dispatcher);
    }

    /**
     * Get the selected legacy product attributes.
     *
     * @param CartEvent $event
     */
    protected function getLegacyProductAttributes(CartEvent $event)
    {
        $product = ProductQuery::create()->findPk($event->getProduct());
        $productAttributes = $product->getTemplate()->getAttributes();

        $this->legacyProductAttributes = [];

        /** @var Attribute $productAttribute */
        foreach ($productAttributes as $productAttribute) {
            $legacyProductAttributeFieldKey =
                CartAddFormExtension::LEGACY_PRODUCT_ATTRIBUTE_FIELD_PREFIX . $productAttribute->getId();

            if ($event->$legacyProductAttributeFieldKey !== null) {
                $this->legacyProductAttributes[$productAttribute->getId()] = $event->$legacyProductAttributeFieldKey;
            }
        }
    }

    /**
     * Find a specific record in CartItem table using the current CartEvent
     *
     * @param CartEvent $event the cart event
     */
    public function findCartItem(CartEvent $event)
    {
        // Do not try to find a cartItem if one exists in the event,
        // as previous event handlers may have put it in th event.
        if (null === $event->getCartItem()) {
            // Do something if legacy attributes are defined
            if (!empty($this->legacyProductAttributes)) {
                $query = CartItemQuery::create();

                $query
                    ->filterByCartId($event->getCart()->getId())
                    ->filterByProductId($event->getProduct())
                    ->filterByProductSaleElementsId($event->getProductSaleElementsId());

                /** @var CartItem $cartItem */
                foreach ($query->find() as $cartItem) {
                    $legacyCartItemAttributeCombinations = LegacyCartItemAttributeCombinationQuery::create()
                        ->findByCartItemId($cartItem->getId());

                    $cartItemLegacyProductAttributes = [];
                    /** @var LegacyCartItemAttributeCombination $legacyCartItemAttributeCombination */
                    foreach ($legacyCartItemAttributeCombinations as $legacyCartItemAttributeCombination) {
                        $cartItemLegacyProductAttributes[$legacyCartItemAttributeCombination->getAttributeId()]
                            = $legacyCartItemAttributeCombination->getAttributeAvId();
                    }

                    if ($cartItemLegacyProductAttributes == $this->legacyProductAttributes) {
                        $event->setCartItem($cartItem);
                        break;
                    }
                }
            } else {
                parent::findCartItem($event);
            }
        }
    }

    /**
     * @inheritdoc
     *
     * Adjust the item price depending on the selected attributes.
     * Save the attribute combinations for the item.
     */
    protected function doAddItem(
        EventDispatcherInterface $dispatcher,
        CartModel $cart,
        $productId,
        ProductSaleElements $productSaleElements,
        $quantity,
        ProductPriceTools $productPrices
    ) {
        if (empty($this->legacyProductAttributes)) {
            return parent::doAddItem($dispatcher, $cart, $productId, $productSaleElements, $quantity, $productPrices);
        }

        // get the adjusted price
        $productGetPricesEvent = (new ProductGetPricesEvent($productId))
            ->setCurrencyId($cart->getCurrencyId())
            ->setBasePrices($productPrices)
            ->setLegacyProductAttributes($this->legacyProductAttributes);

        $dispatcher->dispatch(LegacyProductAttributesEvents::PRODUCT_GET_PRICES, $productGetPricesEvent);

        if (null !== $productGetPricesEvent->getPrices()) {
            $productPrices = $productGetPricesEvent->getPrices();
        }

        $cartItem = parent::doAddItem($dispatcher, $cart, $productId, $productSaleElements, $quantity, $productPrices);

        foreach ($this->legacyProductAttributes as $attributeId => $attributeAvId) {
            (new LegacyCartItemAttributeCombination())
                // cannot use setCartItem directly, as the inverse relation in CartItem does not exists
                ->setCartItemId($cartItem->getId())
                ->setAttributeId($attributeId)
                ->setAttributeAvId($attributeAvId)
                ->save();
        }

        return $cartItem;
    }

    /**
     * Manage cart iteml duplication.
     *
     * @param CartItemDuplicationItem $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function cartItemDuplication(CartItemDuplicationItem $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $oldCartItem = $event->getOldItem();
        $newCartItem = $event->getNewItem();

        $legacyAttributeCombinations =
            LegacyCartItemAttributeCombinationQuery::create()->findByCartItemId($oldCartItem->getId());

        $legacyProductAttributes = [];

        /** @var  LegacyCartItemAttributeCombination $legacyAttributeCombination */
        foreach ($legacyAttributeCombinations as $legacyAttributeCombination) {
            // Create CartItemAttributeCombination for the new cart item.
            (new LegacyCartItemAttributeCombination())
                ->setCartItemId($event->getNewItem()->getId())
                ->setAttributeId($legacyAttributeCombination->getAttributeId())
                ->setAttributeAvId($legacyAttributeCombination->getAttributeAvId())
                ->save();

            $legacyProductAttributes[$legacyAttributeCombination->getAttributeId()] = $legacyAttributeCombination->getAttributeAvId();
        }

        // Adjust cart item price
        $productPrices = new ProductPriceTools(
            $newCartItem->getPrice(),
            $newCartItem->getPromoPrice()
        );

        $productGetPricesEvent = (new ProductGetPricesEvent($event->getOldItem()->getProductId()))
            ->setCurrencyId($newCartItem->getCart()->getCurrencyId())
            ->setBasePrices($productPrices)
            ->setLegacyProductAttributes($legacyProductAttributes);

        $dispatcher->dispatch(LegacyProductAttributesEvents::PRODUCT_GET_PRICES, $productGetPricesEvent);

        if (null !== $productGetPricesEvent->getPrices()) {
            $productPrices = $productGetPricesEvent->getPrices();

            $newCartItem
                ->setPrice($productPrices->getPrice())
                ->setPromoPrice($productPrices->getPromoPrice())
                ->save();
            ;
        }
    }

    public static function getSubscribedEvents()
    {
        $events = parent::getSubscribedEvents();

        $events[TheliaEvents::CART_ITEM_DUPLICATE] = ['cartItemDuplication', 128];

        return $events;
    }
}
