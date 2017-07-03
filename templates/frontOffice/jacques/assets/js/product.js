(function ($) {
    // deactivate the Thelia PSE manager (kinda...)
    PSE_COUNT = 1;

    function initFormProductDetails() {
        var $formProductDetails = $('#form-product-details');
        
        if ($formProductDetails.length > 0) {
            var productId = $formProductDetails.find('input[name="product_id"]').val();
            var $insert = $('.form-product-details-legacy-product-attributes[data-product="' + productId + '"]');
    
            if ($insert.length) {
                $('#pse-options')
                    .before($insert.html())
                    .hide();
            }
    
            var $psePrice = $('#pse-price');
            var $pseUntaxedPrice = $('#pse-untaxed-price');
    
            var $psePriceOld = $('#pse-price-old');
            var $pseUntaxedPriceOld = $('#pse-untaxed-price-old');
    
            setProductPrices($formProductDetails, $psePrice, $pseUntaxedPrice, $psePriceOld, $pseUntaxedPriceOld);
            setProductPseName();
    
            // Compatibilité avec bootsrap-select
            var className = ".pse-option.bootstrap-select";
            if ($(className).length == 0) {
                className = ".pse-option";
            }
    
            $formProductDetails.on('change', '.pse-option', function () {
                setProductPrices($formProductDetails, $psePrice, $pseUntaxedPrice, $psePriceOld, $pseUntaxedPriceOld);
                setProductPseName();
            });
        }
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

    function setProductPrices($formProductDetails, $promo, $promo_untaxed, $old, $old_untaxed) {
        $
            .ajax({
                type: 'POST',
                url: '{url path="/module/LegacyProductAttributes/product/get_prices"}',
                data: $formProductDetails.serialize()
            })
            .done(function (data) {
                if (typeof data.promo_price != 'undefined') {
                    $promo.html(data.promo_price);
                    $promo_untaxed.html(data.promo_untaxed_price);
                    
                    $old.html(data.price);
                    $old_untaxed.html(data.untaxed_price);
                } else {
                    $promo.html(data.price);
                    $promo_untaxed.html(data.untaxed_price);
                    $old.html('');
                    $old_untaxed.html('');
                }
            })
            .fail(function () {
                $promo.html('-');
                $old.html('-');
                $promo_untaxed.html('-');
                $old_untaxed.html('-');
            });
    }

    function setProductPseName() {
        var name = ' - ';

        var firstAttribute = true;
        $('.legacy-product-attribute').each(function () {
            if (!firstAttribute) {
                name += ', ';
            } else {
                firstAttribute = false;
            }

            name += $(this).find('option:selected').html();
        });

        $('#pse-name').html(name);
    }

    function setBootboxProductOptions() {
        var content = '';

        $('.legacy-product-attribute').each(function () {
            var attributeName = $('label[for="'+$(this).attr('id')+'"]').html();
            var attributeValue = $(this).find('option:selected').html();

            content += '<p>' + attributeName + ' : ' + attributeValue + '</p>';
        });

        $('.bootbox').find('table:eq(0)').find('tr:eq(1)').find('td:eq(1)').find('h2').after(content);
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
            setProductPrices(
                $formProductDetails,
                $bootbox.find('.special-price').find('.price'),
                $bootbox.find('.special-untexed-price').find('.price'),
                $bootbox.find('.old-price').find('.price'),
                $bootbox.find('.old-untaxed-price').find('.price')
            );
            setBootboxProductOptions();
        }
    });
})(jQuery);
