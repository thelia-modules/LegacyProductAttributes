<?php

namespace LegacyProductAttributes\Event;

use Thelia\Core\Event\ActionEvent;

class ProductCheckEvent extends ActionEvent
{
    /** @var int */
    protected $productId;

    /** @var bool */
    protected $result;

    public function __construct($productId)
    {
        $this->productId = $productId;
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
     * @return ProductCheckEvent
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param boolean $result
     * @return ProductCheckEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
