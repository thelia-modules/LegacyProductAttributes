<?php

namespace LegacyProductAttributes\Controller\Back;

use LegacyProductAttributes\Model\LegacyProductAttributeValue;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePrice;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Form\Exception\FormValidationException;

/**
 * Controller for legacy product attribute values administration.
 */
class LegacyProductAttributesValuesController extends BaseAdminController
{
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
        $priceDelta = null
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
