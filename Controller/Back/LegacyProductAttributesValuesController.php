<?php

namespace LegacyProductAttributes\Back\Controller;

use LegacyProductAttributes\Model\LegacyProductAttributeValue;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePrice;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Form\Exception\FormValidationException;

class LegacyProductAttributesValuesController extends BaseAdminController
{
    public function updateAction()
    {
        $baseForm = $this->createForm('legacy_product_attributes_form_legacy_product_attributes_values');

        try {
            $form = $this->validateForm($baseForm, "POST");

            $this->doUpdate($form->getData());

            return $this->generateSuccessRedirect($baseForm);
        } catch (FormValidationException $e) {
            throw $e;
        }
    }

    protected function doUpdate(array $formData)
    {
        foreach ($formData['legacy_product_attribute_value_price_delta'] as $attributeAvId => $priceDelta) {
            if ($priceDelta == 0) {
                continue;
            }

            $this->createOrUpdateLegacyProductAttributeValue(
                $formData['product_id'],
                $attributeAvId,
                $formData['currency_id'],
                $priceDelta
            );
        }
    }

    protected function createOrUpdateLegacyProductAttributeValue(
        $productId,
        $attributeAvId,
        $currencyId,
        $priceDelta = null,
        $quantity = null,
        $active = null
    ) {
        if ($priceDelta === null && $quantity === null && $priceDelta === null) {
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

        if ($quantity !== null) {
            $legacyProductAttributeValue->setQuantity($quantity);
        }

        if ($active !== null) {
            $legacyProductAttributeValue->setActive($active);
        }

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
