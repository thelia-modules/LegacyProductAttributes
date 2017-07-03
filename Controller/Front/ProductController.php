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

/**
 * Front-office actions related to products.
 */
class ProductController extends BaseFrontController
{
    /**
     * Get the price and, if in sale, promo price for a product, adjusted with the selected attribute values.
     * Prices are with taxes and formatted for display.
     *
     * @return JsonResponse
     */
    public function getPricesAction()
    {
        $baseForm = $this->createForm('thelia.cart.add');
    
        // make the quantity field a dummy field so that we can ignore it
        // (we want to be able to update prices on out-of-stock products),
        // while still validating the rest of the form
        $baseForm->getForm()->remove('quantity');
        $baseForm->getForm()->add('quantity', 'number'); //, ['mapped' => false]);

        try {
            $form = $this->validateForm($baseForm, 'POST');
            $product = ProductQuery::create()->findPk($form->get('product')->getData());
            $quantity = $form->get('quantity')->getData();

            $productGetPricesEvent = (new ProductGetPricesEvent($product->getId(), $quantity))
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
                'untaxed_price' => $moneyFormat->format(
                    $prices->getPrice(),
                    null,
                    null,
                    null,
                    $this->getSession()->getCurrency()->getSymbol()
                )
            ];

            if ($product->getDefaultSaleElements()->getPromo()) {
                $response['promo_price'] = $moneyFormat->format(
                    $taxCalculator->getTaxedPrice($prices->getPromoPrice()),
                    null,
                    null,
                    null,
                    $this->getSession()->getCurrency()->getSymbol()
                );
                
                $response['untaxed_promo_price'] = $moneyFormat->format(
                    $prices->getPromoPrice(),
                    null,
                    null,
                    null,
                    $this->getSession()->getCurrency()->getSymbol()
                );
                
            }
            
            return new JsonResponse($response);
        } catch (FormValidationException $e) {
            return JsonResponse::createError($e->getMessage(), 400);
        }
    }

    /**
     * Get the selected product attribute values from the card add form.
     *
     * @param Form $form Cart add form.
     *
     * @return array A map of attribute ids => selected attribute value id.
     */
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
