<?php
class Custom_Products_API_Plugin {
    // Render the Create Product Page
    public static function render_create_product_page() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('create_product', 'create_product_nonce')) {
            $options = get_option('custom_products_api_settings', array());
            $endpoint = $options['api_endpoint'] ?? '';
            $consumer_key = $options['consumer_key'] ?? '';
            $consumer_secret = $options['consumer_secret'] ?? '';

            $data = array(
                'name' => sanitize_text_field($_POST['name']),
                'description' => sanitize_textarea_field($_POST['description']),
                'regular_price' => sanitize_text_field($_POST['price']),
                'stock_quantity' => intval($_POST['stock_quantity']),
            );

            $response = wp_remote_post("$endpoint/products", array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode("$consumer_key:$consumer_secret"),
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode($data),
            ));

            if (is_wp_error($response)) {
                echo '<div class="notice notice-error is-dismissible"><p>Error: ' . $response->get_error_message() . '</p></div>';
            } else {
                $status_code = wp_remote_retrieve_response_code($response);
                if ($status_code == 201) {
                    echo '<div class="notice notice-success is-dismissible"><p>Product created successfully.</p></div>';
                } else {
                    $response_body = wp_remote_retrieve_body($response);
                    echo '<div class="notice notice-error is-dismissible"><p>Failed to create product. Response Code: ' . $status_code . '</p>';
                    echo '<p>' . esc_html($response_body) . '</p></div>';
                }
            }
        }

        echo '<div class="wrap">';
        echo '<h1>Create Product</h1>';
        echo '<form method="post" class="custom-create-product-form">';
        wp_nonce_field('create_product', 'create_product_nonce');
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="name">Product Name:</label></th>
                <td><input type="text" id="name" name="name" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="description">Description:</label></th>
                <td><textarea id="description" name="description" rows="5" class="regular-text" required></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="price">Price:</label></th>
                <td><input type="text" id="price" name="price" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="stock_quantity">Stock Quantity:</label></th>
                <td><input type="number" id="stock_quantity" name="stock_quantity" class="regular-text" required></td>
            </tr>
        </table>
        <p><input type="submit" class="button-primary" value="Create Product"></p>
        </form>
        <?php
        echo '</div>';
    }

    // Render the List Products Page
    public static function render_list_products_page() {
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
                            search: search,
                        },
                        success: function (response) {
                            $('#product-list').html(response);
                        },
                        error: function () {
                            $('#product-list').html('<p>Error fetching products.</p>');
                        },
                    });
                }

                fetchProducts();

                $(document).on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    fetchProducts(page, $('#product-search').val());
                });

                $('#product-search').on('input', function () {
                    fetchProducts(1, $(this).val());
                });
            });
        </script>
        <?php
    }

    // AJAX handler for fetching products
    public static function fetch_products() {
        $options = get_option('custom_products_api_settings', array());
        $endpoint = $options['api_endpoint'] ?? '';
        $consumer_key = $options['consumer_key'] ?? '';
        $consumer_secret = $options['consumer_secret'] ?? '';
        $enable_pagination = $options['enable_pagination'] ?? false;

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        if ($endpoint && $consumer_key && $consumer_secret) {
            $query_params = [
                'page' => $page,
                'per_page' => $per_page,
            ];

            if (!empty($search)) {
                $query_params['search'] = $search;
            }

            $response = wp_remote_get("$endpoint/products?" . http_build_query($query_params), array(
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
                    echo '<table class="wp-list-table widefat fixed striped">';
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

                    if ($enable_pagination && $total_pages > 1) {
                        echo '<div class="pagination">';
                        if ($page > 1) {
                            echo '<a href="#" data-page="' . ($page - 1) . '" class="prev">&laquo; Previous</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = $i === $page ? 'class="active"' : '';
                            echo '<a href="#" data-page="' . $i . '" ' . $active . '>' . $i . '</a>';
                        }
                        if ($page < $total_pages) {
                            echo '<a href="#" data-page="' . ($page + 1) . '" class="next">Next &raquo;</a>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products found.</p>';
                }
            }
        }

        wp_die();
    }
}

// Register menu and AJAX handlers
add_action('admin_menu', function () {
    add_menu_page(
        'Custom Products API Settings',
        'Products API',
        'manage_options',
        'custom-products-api',
        [Custom_Products_API_Plugin::class, 'render_create_product_page'],
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'custom-products-api',
        'Create Product',
        'Create Product',
        'manage_options',
        'create-product',
        [Custom_Products_API_Plugin::class, 'render_create_product_page']
    );

    add_submenu_page(
        'custom-products-api',
        'List Products',
        'List Products',
        'manage_options',
        'list-products',
        [Custom_Products_API_Plugin::class, 'render_list_products_page']
    );
});

// Register AJAX action
add_action('wp_ajax_fetch_products', [Custom_Products_API_Plugin::class, 'fetch_products']);
