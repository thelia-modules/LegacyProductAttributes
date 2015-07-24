<?php

namespace LegacyProductAttributes\Hook;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductCheckEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Front-office hooks.
 */
class FrontHook extends BaseHook
{
    /**
     * Insert the product javascript.
     *
     * @param HookRenderEvent $event
     */
    public function onCategoryJavascriptInitialization(HookRenderEvent $event)
    {
        // this javascript file is rendered because it contains smarty content
        $event->add('<script type="text/javascript">');
        $event->add($this->render('assets/js/product.js'));
        $event->add('</script>');
    }

    /**
     * Insert the product details form extensions, and the javascript to insert it.
     *
     * @param HookRenderEvent $event
     */
    public function onProductJavascriptInitialization(HookRenderEvent $event)
    {
        $productCheckEvent = new ProductCheckEvent($this->getRequest()->getProductId());
        $this->dispatcher->dispatch(
            LegacyProductAttributesEvents::PRODUCT_CHECK_LEGACY_ATTRIBUTES_APPLY,
            $productCheckEvent
        );
        if (!$productCheckEvent->getResult()) {
            return;
        }

        $event->add($this->render('product-form-product-details.html'));

        // this javascript file is rendered because it contains smarty content
        $event->add('<script type="text/javascript">');
        $event->add($this->render('assets/js/product.js'));
        $event->add('</script>');
    }

    /**
     * Insert the product details form extensions.
     *
     * @param HookRenderEvent $event
     */
    public function onSingleproductBottom(HookRenderEvent $event)
    {
        $productId = $event->getArgument('product');

        $productCheckEvent = new ProductCheckEvent($productId);
        $this->dispatcher->dispatch(
            LegacyProductAttributesEvents::PRODUCT_CHECK_LEGACY_ATTRIBUTES_APPLY,
            $productCheckEvent
        );
        if (!$productCheckEvent->getResult()) {
            return;
        }

        // add the product id to the request so that the form extension can add the required fields
        $this->getRequest()->query->set('product_id', $productId);

        $event->add($this->render(
            'product-form-product-details.html',
            [
                'product_id' => $productId,
            ]
        ));

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

    /**
     * Insert the cart view extensions, and the javascript to insert them.
     *
     * @param HookRenderEvent $event
     */
    public function onOrderInvoiceJavascriptInitialization(HookRenderEvent $event)
    {
        $event->add($this->render('cart-product-options.html'));
        $event->add($this->addJS('assets/js/cart.js'));
    }
}
