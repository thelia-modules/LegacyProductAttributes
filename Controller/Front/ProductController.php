<?php

namespace LegacyProductAttributes\Controller\Front;

use LegacyProductAttributes\Event\LegacyProductAttributesEvents;
use LegacyProductAttributes\Event\ProductGetPricesEvent;
use LegacyProductAttributes\Form\CartAddFormExtension;
use Symfony\Component\Form\Form;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\ProductQuery;
use Thelia\Model\Tools\ProductPriceTools;
use Thelia\TaxEngine\Calculator;
use Thelia\TaxEngine\TaxEngine;
use Thelia\Tools\MoneyFormat;

class ProductController extends BaseFrontController
{
    public function getPricesAction()
    {
        $baseForm = $this->createForm('thelia.cart.add');

        try {
            $form = $this->validateForm($baseForm, 'POST');

            $product = ProductQuery::create()->findPk($form->get('product')->getData());

            $productGetPricesEvent = (new ProductGetPricesEvent($product->getId()))
                ->setCurrencyId($this->getSession()->getCurrency()->getId())
                ->setLegacyProductAttributes($this->getLegacyProductAttributesInForm($form));

            $this->getDispatcher()->dispatch(LegacyProductAttributesEvents::PRODUCT_GET_PRICES, $productGetPricesEvent);

            if (null !== $productGetPricesEvent->getPrices()) {
                $prices = $productGetPricesEvent->getPrices();
            } else {
                $prices = new ProductPriceTools(0, 0);
            }

            $moneyFormat = MoneyFormat::getInstance($this->getRequest());

            /** @var TaxEngine $taxEngine */
            $taxEngine = $this->getContainer()->get('thelia.taxEngine');

            $taxCountry = $taxEngine->getDeliveryCountry();

            $taxCalculator = (new Calculator())->load($product, $taxCountry);

            $response = [
                'price' => $moneyFormat->format(
                    $taxCalculator->getTaxedPrice($prices->getPrice()),
                    null,
                    null,
                    null,
                    $this->getSession()->getCurrency()->getSymbol()
                ),
            ];

            if ($product->getDefaultSaleElements()->getPromo()) {
                $response['promo_price'] = $moneyFormat->format(
                    $taxCalculator->getTaxedPrice($prices->getPromoPrice()),
                    null,
                    null,
                    null,
                    $this->getSession()->getCurrency()->getSymbol()
                );
            }

            return new JsonResponse($response);
        } catch (FormValidationException $e) {
            throw $e;
        }
    }

    protected function getLegacyProductAttributesInForm(Form $form)
    {
        $product = ProductQuery::create()->findPk($form->get('product')->getData());
        if (null === $product) {
            return [];
        }

        $legacyProductAttributes = [];

        foreach ($product->getTemplate()->getAttributes() as $attribute) {
            $legacyProductAttributeFieldKey
                = CartAddFormExtension::LEGACY_PRODUCT_ATTRIBUTE_FIELD_PREFIX . $attribute->getId();

            if (null !== $form->get($legacyProductAttributeFieldKey)) {
                $legacyProductAttributes[$attribute->getId()]
                    = $form->get($legacyProductAttributeFieldKey)->getData();
            }
        }

        return $legacyProductAttributes;
    }
}
