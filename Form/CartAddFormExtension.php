<?php

namespace LegacyProductAttributes\Form;

use LegacyProductAttributes\LegacyProductAttributes;
use LegacyProductAttributes\Model\Map\LegacyProductAttributeValueTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Translation\Translator;
use Thelia\Model\Attribute;
use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\AttributeQuery;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Map\AttributeAvTableMap;
use Thelia\Model\ProductQuery;

/**
 * Extension to the cart item add form.
 */
class CartAddFormExtension implements EventSubscriberInterface
{
    /**
     * Prefix for the legacy product attribute fields.
     */
    const LEGACY_PRODUCT_ATTRIBUTE_FIELD_PREFIX = 'legacy_product_attribute_';

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::FORM_AFTER_BUILD . '.thelia_cart_add' => ['cartFormAfterBuild', 128],
        ];
    }

    /**
     * Add fields for attribute values selection in our own way (since the product on has its default PSE, it has
     * no attributes as far as Thelia is concerned, but we want it to have all of its template's attributes).
     *
     * @param TheliaFormEvent $event
     */
    public function cartFormAfterBuild(TheliaFormEvent $event)
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        // Should we check available stock ?
        $checkAvailableStock = ConfigQuery::checkAvailableStock();

        $sessionLocale = null;
        /** @var Session $session */
        $session = $request->getSession();
        if ($session !== null) {
            $sessionLang = $session->getLang();
            if ($sessionLang !== null) {
                $sessionLocale = $sessionLang->getLocale();
            }
        }

        $product = ProductQuery::create()->findPk($request->getProductId());
        if ($product === null || $product->getTemplate() === null) {
            return;
        }

        $productAttributes = AttributeQuery::create()
            ->filterByTemplate($product->getTemplate())
            ->find();

        /** @var Attribute $productAttribute */
        foreach ($productAttributes as $productAttribute) {
            $attributeValuesQuery = AttributeAvQuery::create()
                ->addJoin(AttributeAvTableMap::ID, LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID)
                    ->add(LegacyProductAttributeValueTableMap::VISIBLE, true)
                    ->add(LegacyProductAttributeValueTableMap::PRODUCT_ID, $product->getId())
             ;

            // Hide items without stock
            if ($checkAvailableStock) {
                $attributeValuesQuery
                    ->add(LegacyProductAttributeValueTableMap::STOCK, 0, Criteria::GREATER_THAN);
            }

            $attributeValues = $attributeValuesQuery
                ->orderByPosition()
                ->findByAttributeId($productAttribute->getId());

            $choices = [];

            if (true === $withOptions = $attributeValues->count() > 0) {
                /** @var AttributeAv $attributeValue */
                foreach ($attributeValues as $attributeValue) {
                    if ($sessionLocale !== null) {
                        $attributeValue->setLocale($sessionLocale);
                    }

                    $choices[$attributeValue->getId()] = $attributeValue->getTitle();
                }
            } else {
                $choices[0] = Translator::getInstance()->trans("None available", [], LegacyProductAttributes::MESSAGE_DOMAIN);
            }

            $event->getForm()->getFormBuilder()
                ->add(
                    static::LEGACY_PRODUCT_ATTRIBUTE_FIELD_PREFIX . $productAttribute->getId(),
                    'choice',
                    [
                        'choices' => $choices,
                        'required' => true,
                        'disabled' => ! $withOptions
                    ]
                );
        }
    }
}
