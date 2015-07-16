<?php

namespace LegacyProductAttributes\Event;

/**
 * Module events.
 */
class LegacyProductAttributesEvents
{
    /**
     * Get prices for a product, adjusted with the selected attributes.
     */
    const PRODUCT_GET_PRICES = 'action.legacy_product_attributes.product.get_prices';
}
