(function($) {
    var $insert = $('#form-product-details-legacy-product-attributes');

    if ($insert.length) {
        $('#form-product-details')
            .find('fieldset:eq(-0)')
            .before($insert.html());
    }
})(jQuery);