<?php

namespace LegacyProductAttributes\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;

class BackHook extends BaseHook
{
    public function onProductTab(HookRenderBlockEvent $event)
    {
        $event->add([
            'id' => 'legacy-product-attributes',
            'title' => 'Legacy attributes',
            'content' => $this->render('product-edit-tab-legacy-product-attributes.html'),
        ]);
    }
}
