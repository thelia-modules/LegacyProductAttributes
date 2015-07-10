<?php

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Model\LegacyCartItemAttributeCombination;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\OrderProductAttributeCombination;

class OrderAction implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_PRODUCT_AFTER_CREATE => ['createOrderProductAttributeCombinations', 128],
        ];
    }

    public function createOrderProductAttributeCombinations(OrderProductEvent $event)
    {
        $legacyCartItemAttributeCombinations = LegacyCartItemAttributeCombinationQuery::create()
            ->findByCartItemId($event->getCartItemId());

        /** @var LegacyCartItemAttributeCombination $legacyCartItemAttributeCombination */
        foreach ($legacyCartItemAttributeCombinations as $legacyCartItemAttributeCombination) {
            $attribute = $legacyCartItemAttributeCombination->getAttribute();
            $attributeAv = $legacyCartItemAttributeCombination->getAttributeAv();

            (new OrderProductAttributeCombination())
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
        }
    }
}
