<?php

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Model\LegacyCartItemAttributeCombination;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Attribute;
use Thelia\Model\AttributeAv;
use Thelia\Model\OrderProductAttributeCombination;
use Thelia\Model\OrderProductQuery;
use Thelia\Tools\I18n;

/**
 * Listener for order related events.
 */
class OrderAction implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_PRODUCT_AFTER_CREATE => ['createOrderProductAttributeCombinations', 128],
        ];
    }

    /**
     * Save the attribute combinations for the order from our cart item attribute combinations.
     *
     * @param OrderEvent $event
     *
     * @throws PropelException
     */
    public function createOrderProductAttributeCombinations(OrderEvent $event)
    {
        $legacyCartItemAttributeCombinations = LegacyCartItemAttributeCombinationQuery::create()
            ->findByCartItemId($event->getCartItemId());

        // works with Thelia 2.2
        if (method_exists($event, 'getId')) {
            $orderProductId = $event->getId();
        } else {
            // Thelia 2.1 however does not provides the order product id in the event

            // Since the order contains potentially identical (for Thelia) cart items that are only differentiated
            // by the cart item attribute combinations that we are storing ourselves, we cannot use information
            // such as PSE id to cross reference the cart item we are given to the order product that was created from
            // it (as far as I can tell).

            // So we will ASSUME that the order product with the higher id is the one created from this cart item.
            // This is PROBABLY TRUE on a basic Thelia install with no modules messing with the cart and orders in a way
            // that create additional order products, BUT NOT IN GENERAL !
            // This also assumes that ids are generated incrementally, which is NOT GUARANTEED (but true for MySQL
            // with default settings).

            // The creation date was previously used but is even less reliable.

            // FIXME: THIS IS NOT A SANE WAY TO DO THIS

            $orderProductId = OrderProductQuery::create()
                ->orderById(Criteria::DESC)
                ->findOne()
                ->getId();
        }

        $lang = $this->request->getSession()->getLang();

        /** @var LegacyCartItemAttributeCombination $legacyCartItemAttributeCombination */
        foreach ($legacyCartItemAttributeCombinations as $legacyCartItemAttributeCombination) {
            /** @var Attribute $attribute */
            $attribute = I18n::forceI18nRetrieving(
                $lang->getLocale(),
                'Attribute',
                $legacyCartItemAttributeCombination->getAttributeId()
            );

            /** @var AttributeAv $attributeAv */
            $attributeAv = I18n::forceI18nRetrieving(
                $lang->getLocale(),
                'AttributeAv',
                $legacyCartItemAttributeCombination->getAttributeAvId()
            );

            (new OrderProductAttributeCombination())
                ->setOrderProductId($orderProductId)
                ->setAttributeTitle($attribute->getTitle())
                ->setAttributeChapo($attribute->getChapo())
                ->setAttributeDescription($attribute->getDescription())
                ->setAttributePostscriptum($attribute->getPostscriptum())
                ->setAttributeAvTitle($attributeAv->getTitle())
                ->setAttributeAvChapo($attributeAv->getChapo())
                ->setAttributeAvDescription($attributeAv->getDescription())
                ->setAttributeAvPostscriptum($attributeAv->getPostscriptum())
                ->save();
        }
    }
}
