<?php

namespace LegacyProductAttributes\Event;

use Thelia\Core\Event\ActionEvent;

class UpdateStockEvent extends ActionEvent
{
    /** @var int */
    protected $productId;

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
}
