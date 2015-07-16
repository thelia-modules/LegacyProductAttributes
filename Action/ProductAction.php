<?php

namespace LegacyProductAttributes\Action;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductGetPricesEvent;
use LegacyProductAttributes\Model\LegacyProductAttributeValue;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\Tools\ProductPriceTools;

/**
 * Module-specific actions on products.
 */
class ProductAction implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            LegacyProductAttributesEvents::PRODUCT_GET_PRICES => ['getPrices', 128],
        ];
    }

    /**
     * Get the untaxed price and, if the product is in sale, promo price of a product.
     *
     * @param ProductGetPricesEvent $event
     */
    public function getPrices(ProductGetPricesEvent $event)
    {
        $product = ProductQuery::create()->findPk($event->getProductId());
        if (null === $product) {
            throw new \InvalidArgumentException('No product given');
        }

        $currency = CurrencyQuery::create()->findPk($event->getCurrencyId());
        if (null === $currency) {
            $currency = CurrencyQuery::create()->findOneByByDefault(true);
        }

        // get the base prices given in the event
        // or, by default, the prices of the product's default PSE
        if (null !== $event->getBasePrices()) {
            $price = $event->getBasePrices()->getPrice();
            $promoPrice = $event->getBasePrices()->getPromoPrice();
        } else {
            $prices = $product->getDefaultSaleElements()->getPricesByCurrency($currency);

            $price = $prices->getPrice();
            $promoPrice = $prices->getPromoPrice();
        }

        // adjust the prices with the configured price delta from legacy attributes
        $legacyProductAttributeValues = LegacyProductAttributeValueQuery::create()
            ->filterByProductId($product->getId())
            ->filterByAttributeAvId(array_values($event->getLegacyProductAttributes()), Criteria::IN)
            ->find();

        /** @var LegacyProductAttributeValue $legacyProductAttributeValue */
        foreach ($legacyProductAttributeValues as $legacyProductAttributeValue) {
            $legacyProductAttributeValuePrice = $legacyProductAttributeValue->getPriceForCurrency(
                $event->getCurrencyId()
            );
            if ($legacyProductAttributeValuePrice === null) {
                continue;
            }

            $price += $legacyProductAttributeValuePrice->getDelta();
            $promoPrice += $legacyProductAttributeValuePrice->getDelta();
        }

        $event->setPrices(new ProductPriceTools($price, $promoPrice));
    }
}
