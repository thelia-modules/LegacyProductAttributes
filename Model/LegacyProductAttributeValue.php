<?php

namespace LegacyProductAttributes\Model;

use LegacyProductAttributes\Model\Base\LegacyProductAttributeValue as BaseLegacyProductAttributeValue;

class LegacyProductAttributeValue extends BaseLegacyProductAttributeValue
{
    /**
     * @param $currencyId
     * @return array|LegacyProductAttributeValuePrice|mixed
     *
     * @todo bake this into the LegacyProductAttributeValueQuery to avoid multiple queries
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
