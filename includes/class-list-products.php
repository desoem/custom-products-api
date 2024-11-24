<?php
class Custom_Products_API_List_Products {
    public static function render_page() {
        ?>
        <div class="wrap">
            <h1>List Products</h1>
            <input type="text" id="product-search" placeholder="Search products..." />
            <div id="product-list">
                <p>Loading products...</p>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                function fetchProducts(page = 1, search = '') {
                    $('#product-list').html('<p>Loading products...</p>');
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'fetch_products',
                            page: page,
                            search: search
                        },
                        success: function (response) {
                            $('#product-list').html(response);
                        },
                        error: function () {
                            $('#product-list').html('<p>Error fetching products.</p>');
                        }
                    });
                }

                fetchProducts(); // Initial fetch

                // Pagination click handler
                $(document).on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    fetchProducts(page, $('#product-search').val());
                });

                // Search input handler
                $('#product-search').on('input', function () {
                    fetchProducts(1, $(this).val());
                });
            });
        </script>
        <?php
    }
}

add_action('wp_ajax_fetch_products', function () {
    $options = get_option('custom_products_api_settings', array());
    $endpoint = $options['api_endpoint'] ?? '';
    $consumer_key = $options['consumer_key'] ?? '';
    $consumer_secret = $options['consumer_secret'] ?? '';
    $enable_pagination = $options['enable_pagination'] ?? false;

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $per_page = 10; // Products per page
    $offset = ($page - 1) * $per_page;

    if ($endpoint && $consumer_key && $consumer_secret) {
        // Ensure the endpoint URL ends with '/products' if needed
        if (substr($endpoint, -9) !== '/products') {
            $endpoint = rtrim($endpoint, '/') . '/products';
        }

        // Query parameters for pagination and search
        $query_params = [
            'page' => $page,
            'per_page' => $per_page,
        ];

        if (!empty($search)) {
            $query_params['search'] = $search;
        }

        $response = wp_remote_get($endpoint . '?' . http_build_query($query_params), array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode("$consumer_key:$consumer_secret"),
            ),
        ));

        if (is_wp_error($response)) {
            echo '<p>Error: ' . $response->get_error_message() . '</p>';
        } else {
            $products = json_decode(wp_remote_retrieve_body($response), true);
            $total_pages = wp_remote_retrieve_header($response, 'x-wp-totalpages');

            if (is_array($products)) {
                echo '<table class="wp-list-table widefat fixed striped" aria-label="Product List">';
                echo '<thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr></thead>';
                echo '<tbody>';
                foreach ($products as $product) {
                    echo '<tr>';
                    echo '<td>' . esc_html($product['id']) . '</td>';
                    echo '<td>' . esc_html($product['name']) . '</td>';
                    echo '<td>' . esc_html($product['price']) . '</td>';
                    echo '<td>' . esc_html($product['stock_status']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';

                // Render pagination if enabled
                if ($enable_pagination && $total_pages > 1) {
                    echo '<div class="pagination" style="text-align: center; margin-top: 20px;">';
                    if ($page > 1) {
                        echo '<a href="#" data-page="' . ($page - 1) . '" class="prev" style="margin-right: 10px;">&laquo; Previous</a>';
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = $i === $page ? 'style="font-weight: bold;"' : '';
                        echo '<a href="#" data-page="' . $i . '" ' . $active_class . ' style="margin: 0 5px;">' . $i . '</a>';
                    }
                    if ($page < $total_pages) {
                        echo '<a href="#" data-page="' . ($page + 1) . '" class="next" style="margin-left: 10px;">Next &raquo;</a>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>No products found.</p>';
            }
        }
    }

    wp_die();
});
