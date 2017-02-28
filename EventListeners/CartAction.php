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

/**
 * Listener for cart related events.
 */
class CartAction extends Cart
{
    /**
     * Selected attributes values for this request.
     * @var array A map of attribute ids => selected attribute value id.
     */
    protected $legacyProductAttributes = [];

    public static function getSubscribedEvents()
    {
        return [
            // must run before the Thelia cart action
            TheliaEvents::CART_ADDITEM => ['addItem', 192],
            TheliaEvents::CART_FINDITEM => ['findCartItem', 192],
        ];
    }

    /**
     * Manage adding an item to the cart when our legacy product attributes are used.
     *
     * @param CartEvent $event
     */
    public function addItem(CartEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $this->getLegacyProductAttributes($event);

        // call the parent method, but using our redefined sub-methods
        parent::addItem($event, $eventName, $dispatcher);

        // prevent the parent event from adding the item
        $event->setNewness(false);
        $event->setAppend(false);
    }

    /**
     * Get the selected legacy product attributes.
     *
     * @param CartEvent $event
     */
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
    
    /**
     * Find a specific record in CartItem table using the current CartEvent
     *
     * @param CartEvent $event the cart event
     */
    public function findCartItem(CartEvent $event)
    {
        // Do not try to find a cartItem if one exists in the event,
        // as previous event handlers may have put it in th event.
        if (null === $event->getCartItem()) {
            // Do something if legacy attributes are defined
            if (!empty($this->legacyProductAttributes)) {
                $query = CartItemQuery::create();
        
                $query
                    ->filterByCartId($event->getCart()->getId())
                    ->filterByProductId($event->getProduct())
                    ->filterByProductSaleElementsId($event->getProductSaleElementsId());
        
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
                        $event->setCartItem($cartItem);
                        break;
                    }
                }
                
                // Prevent the Cart Action to find something else in the cart
                $event->stopPropagation();
            }
        }
    }

    /**
     * @inheritdoc
     *
     * Adjust the item price depending on the selected attributes.
     * Save the attribute combinations for the item.
     */
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
