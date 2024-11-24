function fetchProducts(page = 1, search = '') {
    jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
            action: 'fetch_products',
            page: page,
            search: search
        },
        success: function (response) {
            jQuery('#product-list').html(response);
        },
        error: function () {
            jQuery('#product-list').html('<p>Error fetching products.</p>');
        }
    });
}

jQuery(document).ready(function () {
    fetchProducts();

    jQuery(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const page = jQuery(this).data('page');
        fetchProducts(page, jQuery('#product-search').val());
    });

    jQuery('#product-search').on('input', function () {
        fetchProducts(1, jQuery(this).val());
    });
});
