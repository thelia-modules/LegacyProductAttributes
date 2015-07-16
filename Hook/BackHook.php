<?php

namespace LegacyProductAttributes\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

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
        $event->add([
            'id' => 'legacy-product-attributes',
            'title' => 'Legacy attributes',
            'content' => $this->render('product-edit-tab-legacy-product-attributes.html'),
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
