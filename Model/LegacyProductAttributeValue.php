<?php

namespace LegacyProductAttributes\Model;

use LegacyProductAttributes\Model\Base\LegacyProductAttributeValue as BaseLegacyProductAttributeValue;

class LegacyProductAttributeValue extends BaseLegacyProductAttributeValue
{
    /**
     * Get the currency-dependant configuration for this legacy product attribute value.
     *
     * @param int $currencyId Currency id.
     *
     * @return null|LegacyProductAttributeValuePrice
     */
    public function getPriceForCurrency($currencyId)
    {
        return LegacyProductAttributeValuePriceQuery::create()
            ->findPk([
                $this->getProductId(),
                $this->getAttributeAvId(),
                $currencyId
            ]);
    }
}
