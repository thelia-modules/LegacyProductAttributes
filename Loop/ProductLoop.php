<?php

namespace LegacyProductAttributes\Loop;

use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Product as BaseProductLoop;
use Thelia\Model\ProductQuery;

/**
 * Replacement for the Thelia product loop.
 * Replace the PSE_COUNT by a virtual count based on our legacy attributes.
 */
class ProductLoop extends BaseProductLoop
{
    public function parseResults(LoopResult $loopResult)
    {
        $loopResult = parent::parseResults($loopResult);

        /** @var LoopResultRow $loopResultRow */
        foreach ($loopResult as $loopResultRow) {
            // do nothing if no count is present (simple mode)
            if ($loopResultRow->get('PSE_COUNT') === null) {
                continue;
            }
            //  do nothing if the count is more than one (we don't use legacy attributes for this product)
            if ($loopResultRow->get('PSE_COUNT') > 1) {
                continue;
            }

            $product = ProductQuery::create()->findPk($loopResultRow->get('ID'));

            // do nothing if the product has a PSE that is not the default one (i.e. with an attribute combination)
            if ($product->getDefaultSaleElements()->countAttributeCombinations() > 0) {
                continue;
            }
            // nothing to do if the product has no template (and thus no attributes)
            if ($product->getTemplate() === null) {
                continue;
            }

            $virtualPseCount = 1;
            foreach ($product->getTemplate()->getAttributes() as $attribute) {
                $virtualPseCount *= $attribute->countAttributeAvs();
            }

            $loopResultRow->set('PSE_COUNT', $virtualPseCount);
        }

        return $loopResult;
    }
}
