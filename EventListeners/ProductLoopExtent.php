<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 08/02/2017 17:46
 */

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductCheckEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Loop\LoopExtendsParseResultsEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Model\ProductQuery;

class ProductLoopExtent implements EventSubscriberInterface
{
    /** @var  EventDispatcherInterface */
    protected $dispatcher;
    
    /**
     * ProductLoopExtent constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_PARSE_RESULTS, 'product') => ['productParseResults', 128],
        ];
    }
    
    public function productParseResults(LoopExtendsParseResultsEvent $event)
    {
        $loopResult = $event->getLoopResult();
    
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
