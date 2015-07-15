<?php

namespace LegacyProductAttributes\EventListeners;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductGetPricesEvent;
use LegacyProductAttributes\Form\CartAddFormExtension;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombination;
use LegacyProductAttributes\Model\LegacyCartItemAttributeCombinationQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Action\Cart;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Attribute;
use Thelia\Model\Cart as CartModel;
use Thelia\Model\CartItem;
use Thelia\Model\CartItemQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\Model\Tools\ProductPriceTools;

class CartAction extends Cart
{
    protected $legacyProductAttributes = [];

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::CART_ADDITEM => ['addItem', 192],
        ];
    }

    public function addItem(CartEvent $event)
    {
        $this->getLegacyProductAttributes($event);

        parent::addItem($event);

        $event->setNewness(false);
        $event->setAppend(false);
    }

    protected function getLegacyProductAttributes(CartEvent $event)
    {
        $product = ProductQuery::create()->findPk($event->getProduct());
        $productAttributes = $product->getTemplate()->getAttributes();

        $this->legacyProductAttributes = [];

        /** @var Attribute $productAttribute */
        foreach ($productAttributes as $productAttribute) {
            $legacyProductAttributeFieldKey =
                CartAddFormExtension::LEGACY_PRODUCT_ATTRIBUTE_FIELD_PREFIX . $productAttribute->getId();

            if ($event->$legacyProductAttributeFieldKey !== null) {
                $this->legacyProductAttributes[$productAttribute->getId()] = $event->$legacyProductAttributeFieldKey;
            }
        }
    }

    protected function findItem($cartId, $productId, $productSaleElementsId)
    {
        if (empty($this->legacyProductAttributes)) {
            return parent::findItem($cartId, $productId, $productSaleElementsId);
        }

        $query = CartItemQuery::create();

        $query
            ->filterByCartId($cartId)
            ->filterByProductId($productId)
            ->filterByProductSaleElementsId($productSaleElementsId);

        /** @var CartItem $cartItem */
        foreach ($query->find() as $cartItem) {
            $legacyCartItemAttributeCombinations = LegacyCartItemAttributeCombinationQuery::create()
                ->findByCartItemId($cartItem->getId());

            $cartItemLegacyProductAttributes = [];
            /** @var LegacyCartItemAttributeCombination $legacyCartItemAttributeCombination */
            foreach ($legacyCartItemAttributeCombinations as $legacyCartItemAttributeCombination) {
                $cartItemLegacyProductAttributes[$legacyCartItemAttributeCombination->getAttributeId()]
                    = $legacyCartItemAttributeCombination->getAttributeAvId();
            }

            if ($cartItemLegacyProductAttributes == $this->legacyProductAttributes) {
                return $cartItem;
            }
        }

        return null;
    }

    protected function doAddItem(
        EventDispatcherInterface $dispatcher,
        CartModel $cart,
        $productId,
        ProductSaleElements $productSaleElements,
        $quantity,
        ProductPriceTools $productPrices
    ) {
        // get the adjusted price
        $productGetPricesEvent = (new ProductGetPricesEvent($productId))
            ->setCurrencyId($cart->getCurrencyId())
            ->setBasePrices($productPrices)
            ->setLegacyProductAttributes($this->legacyProductAttributes);

        $dispatcher->dispatch(LegacyProductAttributesEvents::PRODUCT_GET_PRICES, $productGetPricesEvent);
        if (null !== $productGetPricesEvent->getPrices()) {
            $productPrices = $productGetPricesEvent->getPrices();
        }

        $cartItem = parent::doAddItem($dispatcher, $cart, $productId, $productSaleElements, $quantity, $productPrices);

        foreach ($this->legacyProductAttributes as $attributeId => $attributeAvId) {
            (new LegacyCartItemAttributeCombination())
                // cannot use setCartItem directly, as the inverse relation in CartItem does not exists
                ->setCartItemId($cartItem->getId())
                ->setAttributeId($attributeId)
                ->setAttributeAvId($attributeAvId)
                ->save();
        }

        return $cartItem;
    }
}
