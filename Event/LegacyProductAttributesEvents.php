<?php

namespace LegacyProductAttributes\Event;

/**
 * Module events.
 */
class LegacyProductAttributesEvents
{
    /**
     * Check if a product uses the legacy attributes system.
     */
    const PRODUCT_CHECK_LEGACY_ATTRIBUTES_APPLY
        = 'action.legacy_product_attributes.product.check_legacy_attributes_apply';

    /**
     * Get prices for a product, adjusted with the selected attributes.
     */
    const PRODUCT_GET_PRICES = 'action.legacy_product_attributes.product.get_prices';
}
