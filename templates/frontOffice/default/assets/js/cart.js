(function($) {
    var $cart = $('#cart');

    $cart.find('tbody').find('tr').each(function() {
        var cartItemId = $(this).find('td.qty').find('form').find('input[name="cart_item"]').val();

        if (typeof cartItemId == 'undefined') {
            return;
        }

        var cartItemOptionsContent =
            $('.legacy-product-attributes-cart-item-attribute-combination[data-cart-item="'+cartItemId+'"]')
            .html();

        $(this).find('.product-options').find('dl').append(cartItemOptionsContent);
    });
})(jQuery);