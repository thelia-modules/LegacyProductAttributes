<?php

namespace LegacyProductAttributes\Model;

use LegacyProductAttributes\Model\Base\LegacyProductAttributeValueQuery as BaseLegacyProductAttributeValueQuery;
use LegacyProductAttributes\Model\Map\LegacyProductAttributeValuePriceTableMap;
use LegacyProductAttributes\Model\Map\LegacyProductAttributeValueTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;

class LegacyProductAttributeValueQuery extends BaseLegacyProductAttributeValueQuery
{
    public function joinPriceWithCurrency($currencyId)
    {
        $this
            ->addJoinObject(
                new Join(
                    LegacyProductAttributeValueTableMap::PRODUCT_ID,
                    LegacyProductAttributeValuePriceTableMap::PRODUCT_ID,
                    Criteria::INNER_JOIN
                ),
                'price_for_currency'
            )
            ->addJoinCondition(
                'price_for_currency',
                LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID
                . Criteria::EQUAL
                . LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID
            )
            ->addJoinCondition(
                'price_for_currency',
                LegacyProductAttributeValuePriceTableMap::CURRENCY_ID . Criteria::EQUAL . $currencyId
            )
        ;

        return $this;
    }
}
