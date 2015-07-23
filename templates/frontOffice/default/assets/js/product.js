(function ($) {
    function initFormProductDetails() {
        var $formProductDetails = $('#form-product-details');

        var productId = $formProductDetails.find('input[name="product_id"]').val();
        var $insert = $('.form-product-details-legacy-product-attributes[data-product="'+productId+'"]');

        if ($insert.length) {
            $formProductDetails
                .find('fieldset:eq(-0)')
                .before($insert.html());

            $('#pse-options').hide();
            $('#pse-name').hide();
        }


        var $psePrice = $('#pse-price');
        var $psePriceOld = $('#pse-price-old');

        setLegacyProductAttributesPrices($formProductDetails, $psePrice, $psePriceOld);
        $formProductDetails.on('change', '.pse-option', function () {
            setLegacyProductAttributesPrices($formProductDetails, $psePrice, $psePriceOld);
        });
    }

    initFormProductDetails();

    $('.product-quickview').each(function () {
        var productUrl = $(this).attr('href');

        $(document).ajaxSuccess(function (event, xhr, settings) {
            if (settings.url != productUrl) {
                return;
            }

            initFormProductDetails();
        });
    });

    function setLegacyProductAttributesPrices($formProductDetails, $promo, $old) {
        $
            .ajax({
                type: 'POST',
                url: '{url path="/admin/module/LegacyProductAttributes/product/get_prices"}',
                data: $formProductDetails.serialize()
            })
            .done(function (data) {
                if (typeof data.promo_price != 'undefined') {
                    $promo.html(data.promo_price);
                    $old.html(data.price);
                } else {
                    $promo.html(data.price);
                }
            })
            .fail(function () {
                $promo.html('-');
                $old.html('-');
            });
    }

    $(document).ajaxSuccess(function (event, xhr, settings) {
        // Thelia 2.2 defines addCartMessageUrl, Thelia 2.1 hardcodes it
        if (typeof addCartMessageUrl == 'undefined') {
            addCartMessageUrl = 'ajax/addCartMessage';
        }

        if (settings.url.split(/[?#]/)[0] != addCartMessageUrl) {
            return;
        }

        var $bootbox = $('.bootbox');
        var $formProductDetails = $('#form-product-details');

        if ($formProductDetails.length > 0) {
            setLegacyProductAttributesPrices(
                $formProductDetails,
                $bootbox.find('.special-price').find('.price'),
                $bootbox.find('.old-price').find('.price')
            );
        }
    });
})(jQuery);