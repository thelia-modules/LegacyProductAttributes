<?php

namespace LegacyProductAttributes\Controller\Back;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\UpdateStockEvent;
use LegacyProductAttributes\Model\LegacyProductAttributeValue;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePrice;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Thelia\Action\Customer;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\ProductSaleElement\ProductSaleElementDeleteEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\AttributeCombinationQuery;
use Thelia\Model\Currency;
use Thelia\Model\Map\ProductSaleElementsTableMap;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElementsQuery;
use Thelia\Tools\URL;

/**
 * Controller for legacy product attribute values administration.
 */
class LegacyProductAttributesValuesController extends BaseAdminController
{
    public function syncAction()
    {
        $products = ProductQuery::create()->find();
        
        foreach($products as $product) {
            echo "Update ".$product->getId()."<br>";
            
            $this->getDispatcher()->dispatch(
                LegacyProductAttributesEvents::UPDATE_PRODUCT_STOCK,
                new UpdateStockEvent($product->getId())
            );
        }
        
        echo "Sync done";
        
        exit;
    }
    
    public function clearProductCombinationsAction($productId)
    {
        // Select all PSE except the default one
        $pseToDelete = ProductSaleElementsQuery::create()
            ->filterByProductId($productId)
            ->filterByIsDefault(false)
            ->select([ ProductSaleElementsTableMap::ID ])
            ->find()
        ;
        
        // Delete them one by one
        foreach ($pseToDelete->getData() as $pseId) {
            $this->getDispatcher()->dispatch(
                TheliaEvents::PRODUCT_DELETE_PRODUCT_SALE_ELEMENT,
                new ProductSaleElementDeleteEvent($pseId, Currency::getDefaultCurrency()->getId())
            );
        }
        
        // Get the default PSE
        $defaultPse = ProductSaleElementsQuery::create()
            ->filterByProductId($productId)
            ->findOneByIsDefault(true)
        ;
        
        // Delete the related combination, so that the product has no longer any attribute combinations
        AttributeCombinationQuery::create()
            ->filterByProductSaleElements($defaultPse)
            ->delete();
        
        // We're ready !
        return $this->generateRedirect(
            URL::getInstance()->absoluteUrl(
                "/admin/products/update",
                [
                    'product_id' => $productId,
                    'current_tab' => 'legacy-product-attributes'
                ]
            )
        );
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction()
    {
        $baseForm = $this->createForm('legacy_product_attributes_form_legacy_product_attributes_values');

        try {
            $form = $this->validateForm($baseForm, "POST");

            $this->doUpdate($form->getData());

            return $this->generateSuccessRedirect($baseForm);
        } catch (FormValidationException $e) {
            return new Response('', 400);
        }
    }

    /**
     * Create or update the configuration for non-default legacy attribute values.
     *
     * @param array $formData Data from the edition form.
     *
     * @throws PropelException
     */
    protected function doUpdate(array $formData)
    {
        foreach ($formData['legacy_product_attribute_value_price_delta'] as $attributeAvId => $priceDelta) {
            $this->createOrUpdateLegacyProductAttributeValue(
                $formData['product_id'],
                $attributeAvId,
                $formData['currency_id'],
                isset($formData['legacy_product_attribute_value_visible'][$attributeAvId]) ?: false,
                $priceDelta,
                $formData['legacy_product_attribute_value_weight_delta'][$attributeAvId],
                $formData['legacy_product_attribute_value_stock'][$attributeAvId]
            );
        }
    
        $this->getDispatcher()->dispatch(
            LegacyProductAttributesEvents::UPDATE_PRODUCT_STOCK,
            new UpdateStockEvent($formData['product_id'])
        );
    }

    /**
     * Create or update the configuration for a legacy product attribute value.
     *
     * @param int $productId Product id.
     * @param int $attributeAvId Attribute value id.
     * @param int $currencyId Currency id.
     * @param float|null $priceDelta Price difference added (or removed) by the attribute value.
     *
     * @throws PropelException
     */
    protected function createOrUpdateLegacyProductAttributeValue(
        $productId,
        $attributeAvId,
        $currencyId,
        $active = true,
        $priceDelta = null,
        $weightDelta = null,
        $stock = null
    ) {
        if ($priceDelta === null) {
            return;
        }

        $legacyProductAttributeValue = LegacyProductAttributeValueQuery::create()
            ->findPk([
                $productId,
                $attributeAvId,
            ]);
        if ($legacyProductAttributeValue === null) {
            $legacyProductAttributeValue = (new LegacyProductAttributeValue())
                ->setProductId($productId)
                ->setAttributeAvId($attributeAvId);
        }
    
        $legacyProductAttributeValue
            ->setVisible($active)
            ->setWeightDelta($weightDelta)
            ->setStock($stock)
            ;

        $legacyProductAttributeValuePriceDelta = LegacyProductAttributeValuePriceQuery::create()
            ->findPk([
                $productId,
                $attributeAvId,
                $currencyId,
            ]);
        if ($legacyProductAttributeValuePriceDelta === null) {
            $legacyProductAttributeValuePriceDelta = (new LegacyProductAttributeValuePrice())
                ->setProductId($productId)
                ->setAttributeAvId($attributeAvId)
                ->setCurrencyId($currencyId);
        }

        if ($priceDelta !== null) {
            $legacyProductAttributeValuePriceDelta->setDelta($priceDelta);
        }

        Propel::getConnection()->beginTransaction();

        try {
            $legacyProductAttributeValue->save();
            $legacyProductAttributeValuePriceDelta->save();
        } catch (PropelException $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        Propel::getConnection()->commit();
    }
}
