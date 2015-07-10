<?php

namespace LegacyProductAttributes\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
    public function onProductJavascriptInitialization(HookRenderEvent $event)
    {
        $event->add('<script type="text/html" id="form-product-details-legacy-product-attributes">');
        $event->add($this->render('product-form-product-details.html'));
        $event->add('</script>');

        $event->add($this->addJS('assets/js/product.js'));
    }

    public function onCartJavascriptInitialization(HookRenderEvent $event)
    {
        $event->add($this->render('cart-product-options.html'));

        $event->add($this->addJS('assets/js/cart.js'));
    }
}
