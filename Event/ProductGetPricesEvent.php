<?php

namespace LegacyProductAttributes\Event;

use Thelia\Core\Event\ActionEvent;
use Thelia\Model\Tools\ProductPriceTools;

/**
 * Event object to be dispatched for a LegacyProductAttributesEvents::PRODUCT_GET_PRICES event.
 */
class ProductGetPricesEvent extends ActionEvent
{
    /** @var int */
    protected $productId;
    /** @var int */
    protected $quantity;
    /** @var int */
    protected $currencyId;
    /** @var ProductPriceTools */
    protected $basePrices;
    /** @var array */
    protected $legacyProductAttributes;

    /** @var ProductPriceTools */
    protected $prices;

    public function __construct($productId, $quantity = 1)
    {
        $this->productId = $productId;
        
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return ProductGetPricesEvent
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @param int $currencyId
     * @return ProductGetPricesEvent
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    /**
     * @return ProductPriceTools
     */
    public function getBasePrices()
    {
        return $this->basePrices;
    }

    /**
     * @param ProductPriceTools $basePrices
     * @return ProductGetPricesEvent
     */
    public function setBasePrices($basePrices)
    {
        $this->basePrices = $basePrices;

        return $this;
    }

    /**
     * @return array
     */
    public function getLegacyProductAttributes()
    {
        return $this->legacyProductAttributes;
    }

    /**
     * @param array $legacyProductAttributes
     * @return ProductGetPricesEvent
     */
    public function setLegacyProductAttributes($legacyProductAttributes)
    {
        $this->legacyProductAttributes = $legacyProductAttributes;

        return $this;
    }

    /**
     * @return ProductPriceTools
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param ProductPriceTools $prices
     * @return ProductGetPricesEvent
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }
}
