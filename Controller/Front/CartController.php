<?php

namespace LegacyProductAttributes\Controller\Front;

use Front\Controller\CartController as BaseCartController;
use Front\Front;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Log\Tlog;

/**
 * Fix form extensions not being loaded for the cart add form for Thelia 2.1.
 */
class CartController extends BaseCartController
{
    public function addItem()
    {
        $request = $this->getRequest();

        $cartAdd = $this->getAddCartForm($request);
        $message = null;

        try {
            $form = $this->validateForm($cartAdd);

            $cartEvent = $this->getCartEvent();

            $cartEvent->bindForm($form);

            $this->getDispatcher()->dispatch(TheliaEvents::CART_ADDITEM, $cartEvent);

            $this->afterModifyCart();


            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->changeViewForAjax();
            } else if (null !== $response = $this->generateSuccessRedirect($cartAdd)) {
                return $response;
            }

        } catch (PropelException $e) {
            Tlog::getInstance()->error(sprintf("Failed to add item to cart with message : %s", $e->getMessage()));
            $message = $this->getTranslator()->trans(
                "Failed to add this article to your cart, please try again",
                [],
                Front::MESSAGE_DOMAIN
            );
        } catch (FormValidationException $e) {
            $message = $e->getMessage();
        }


        if ($message) {
            $cartAdd->setErrorMessage($message);
            $this->getParserContext()->addForm($cartAdd);
        }
    }

    private function getAddCartForm(Request $request)
    {
        if ($request->isMethod("post")) {
            $cartAdd = $this->createForm("thelia.cart.add");
        } else {
            $cartAdd = $this->createForm(
                "thelia.cart.add",
                "form",
                array(),
                array(
                    'csrf_protection'   => false,
                ),
                $this->container
            );
        }

        return $cartAdd;
    }
}
