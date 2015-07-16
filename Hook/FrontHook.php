<?php

namespace LegacyProductAttributes\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Front-office hooks.
 */
class FrontHook extends BaseHook
{
    /**
     * Insert the product details form extensions, and the javascript to insert it.
     *
     * @param HookRenderEvent $event
     */
    public function onProductJavascriptInitialization(HookRenderEvent $event)
    {
        $event->add('<script type="text/html" id="form-product-details-legacy-product-attributes">');
        $event->add($this->render('product-form-product-details.html'));
        $event->add('</script>');

        // this javascript file is rendered because it contains smarty content
        $event->add('<script type="text/javascript">');
        $event->add($this->render('assets/js/product.js'));
        $event->add('</script>');
    }

    /**
     * Insert the cart view extensions, and the javascript to insert them.
     *
     * @param HookRenderEvent $event
     */
    public function onCartJavascriptInitialization(HookRenderEvent $event)
    {
        $event->add($this->render('cart-product-options.html'));

        $event->add($this->addJS('assets/js/cart.js'));
    }
}
