<?php

namespace LegacyProductAttributes\Form;

use LegacyProductAttributes\LegacyProductAttributes;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\ProductQuery;
use Thelia\TaxEngine\Calculator;
use Thelia\TaxEngine\TaxEngine;
use Thelia\Tools\NumberFormat;

/**
 * Form for legacy product attribute values configuration update.
 */
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
                    'label' => Translator::getInstance()->trans('Product'),
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
                    'label' => Translator::getInstance()->trans('Currency'),
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

        if ($product->getTemplate() === null) {
            return;
        }

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
            'price_delta_with_tax' => [],
        ];

        /** @var TaxEngine $taxEngine */
        $taxEngine = $this->container->get('thelia.taxEngine');
        $taxCalculator = (new Calculator())->load($product, $taxEngine->getDeliveryCountry());

        /** @var AttributeAv $productAttributeAv */
        foreach ($productAttributeAvs as $productAttributeAv) {
            $legacyProductAttributeValuePrice = LegacyProductAttributeValuePriceQuery::create()
                ->findPk([
                    $product->getId(),
                    $productAttributeAv->getId(),
                    $currencyId
                ]);

            $priceDelta = 0;
            $priceDeltaWithTax = 0;
            if (null !== $legacyProductAttributeValuePrice) {
                $priceDelta = $legacyProductAttributeValuePrice->getDelta();
                $priceDeltaWithTax = $taxCalculator->getTaxedPrice($legacyProductAttributeValuePrice->getDelta());
            }

            $numberFormatter = NumberFormat::getInstance($this->getRequest());

            $formData['price_delta'][$productAttributeAv->getId()]
                = $numberFormatter->formatStandardNumber($priceDelta);
            $formData['price_delta_with_tax'][$productAttributeAv->getId()]
                = $numberFormatter->formatStandardNumber($priceDeltaWithTax);
        }

        $this->formBuilder
            ->add(
                'legacy_product_attribute_value_price_delta',
                'collection',
                [
                    'label' => Translator::getInstance()->trans(
                        'Price difference excluding taxes',
                        [],
                        LegacyProductAttributes::MESSAGE_DOMAIN_BO
                    ),
                    'type' => 'number',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'data' => $formData['price_delta'],
                ]
            )
            ->add(
                'legacy_product_attribute_value_price_delta_with_tax',
                'collection',
                [
                    'label' => Translator::getInstance()->trans(
                        'Price difference including taxes',
                        [],
                        LegacyProductAttributes::MESSAGE_DOMAIN_BO
                    ),
                    'type' => 'number',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'data' => $formData['price_delta_with_tax'],
                ]
            );
    }
}
