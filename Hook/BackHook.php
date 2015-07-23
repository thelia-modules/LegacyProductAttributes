<?php

namespace LegacyProductAttributes\Hook;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductCheckEvent;
use LegacyProductAttributes\LegacyProductAttributes;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;
use Thelia\Model\ProductQuery;

/**
 * Back-office hooks.
 */
class BackHook extends BaseHook
{
    /**
     * Insert the legacy product attributes configuration tab.
     *
     * @param HookRenderBlockEvent $event
     */
    public function onProductTab(HookRenderBlockEvent $event)
    {
        $product = ProductQuery::create()->findPk($event->getArgument('id'));

        $productCheckEvent = new ProductCheckEvent($this->getRequest()->getProductId());
        $this->dispatcher->dispatch(
            LegacyProductAttributesEvents::PRODUCT_CHECK_LEGACY_ATTRIBUTES_APPLY,
            $productCheckEvent
        );

        if (!$productCheckEvent->getResult()) {
            $content = $this->render('product-edit-tab-legacy-product-attributes-does-not-apply.html');
        } elseif ($product->getTemplate() === null) {
            $content = $this->render('product-edit-tab-legacy-product-attributes-no-template.html');
        } else {
            $content = $this->render('product-edit-tab-legacy-product-attributes.html');
        }

        $event->add([
            'id' => 'legacy-product-attributes',
            'title' => Translator::getInstance()->trans(
                'Attributes configuration',
                [],
                LegacyProductAttributes::MESSAGE_DOMAIN_BO
            ),
            'content' => $content,
        ]);
    }

    /**
     * Insert the product edition page javascript.
     *
     * @param HookRenderEvent $event
     */
    public function onProductEditJs(HookRenderEvent $event)
    {
        $event->add($this->addJS('assets/js/product-edit.js'));
    }
}
