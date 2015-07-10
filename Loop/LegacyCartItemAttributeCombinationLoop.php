<?php

namespace LegacyProductAttributes\Loop;

use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\AttributeCombination;
use Thelia\Model\Map\AttributeAvTableMap;
use Thelia\Model\Map\AttributeTableMap;

class LegacyCartItemAttributeCombinationLoop extends AttributeCombination implements PropelSearchLoopInterface
{
    protected $timestampable = false;

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('cart_item')
        );
    }

    public function buildModelCriteria()
    {
        $query = LegacyCartItemAttributeCombinationQuery::create();

        $this->configureI18nProcessing(
            $query,
            ['TITLE', 'CHAPO', 'DESCRIPTION', 'POSTSCRIPTUM'],
            AttributeTableMap::TABLE_NAME,
            'ATTRIBUTE_ID'
        );

        $this->configureI18nProcessing(
            $query,
            ['TITLE', 'CHAPO', 'DESCRIPTION', 'POSTSCRIPTUM'],
            AttributeAvTableMap::TABLE_NAME,
            'ATTRIBUTE_AV_ID'
        );

        $cartItemId = $this->getArgValue('cart_item');
        if ($cartItemId !== null) {
            $query->filterByCartItemId($cartItemId);
        }

        return $query;
    }
}
