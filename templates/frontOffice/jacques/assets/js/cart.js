(function($) {
    var $inserts = $('.legacy-product-attributes-cart-item-attribute-combination');
    var i = 0;

    $('.table-cart').find('tbody').find('tr').each(function() {
        $(this).find('.product-options').find('dl').append($($inserts.get(i)).html());
        ++i;
    });
})(jQuery);