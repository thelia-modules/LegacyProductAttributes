<?php

namespace LegacyProductAttributes\Loop;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductCheckEvent;
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

            $product = ProductQuery::create()->findPk($loopResultRow->get('ID'));

            //  do nothing if we don't use legacy attributes for this product
            $productCheckEvent = new ProductCheckEvent($product->getId());
            $this->dispatcher->dispatch(
                LegacyProductAttributesEvents::PRODUCT_CHECK_LEGACY_ATTRIBUTES_APPLY,
                $productCheckEvent
            );
            if (!$productCheckEvent->getResult()) {
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
