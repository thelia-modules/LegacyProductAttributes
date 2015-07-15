<?php

namespace LegacyProductAttributes\Form;

use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;
use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\ProductQuery;

class LegacyProductAttributesValuesForm extends BaseForm
{
    public function getName()
    {
        return 'legacy_product_attributes_form_legacy_product_attributes_values';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'product_id',
                'integer',
                [
                    'label' => 'Product id',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add(
                'currency_id',
                'integer',
                [
                    'label' => 'Currency id',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            );

        $productId = $this->request->get('product_id');
        if ($productId === null) {
            $productId = $this->request->get($this->getName())['product_id'];
        }
        $product = ProductQuery::create()->findPk($productId);

        $currencyId = $this->request->get('edit_currency_id');
        if ($currencyId === null) {
            $defaultCurrency = CurrencyQuery::create()->findOneByByDefault(true);
            if ($defaultCurrency !== null) {
                $currencyId = $defaultCurrency->getId();
            }
        }

        $productAttributeAvs = AttributeAvQuery::create()
            ->useAttributeQuery()
            ->filterByTemplate($product->getTemplate())
            ->endUse()
            ->find();

        $formData = [
            'price_delta' => [],
        ];

        /** @var AttributeAv $productAttributeAv */
        foreach ($productAttributeAvs as $productAttributeAv) {
            $legacyProductAttributeValuePrice = LegacyProductAttributeValuePriceQuery::create()
                ->findPk([
                    $product->getId(),
                    $productAttributeAv->getId(),
                    $currencyId
                ]);

            $formData['price_delta'][$productAttributeAv->getId()] =
                ($legacyProductAttributeValuePrice !== null) ? $legacyProductAttributeValuePrice->getDelta() : 0;
        }

        $this->formBuilder
            ->add(
                'legacy_product_attribute_value_price_delta',
                'collection',
                [
                    'type' => 'number',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'data' => $formData['price_delta'],
                ]
            );
    }
}
