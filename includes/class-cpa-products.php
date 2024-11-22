<?php

class CPA_Products {
    private static function get_api_credentials() {
        return [
            'endpoint' => get_option('cpa_api_endpoint'),
            'key' => get_option('cpa_consumer_key'),
            'secret' => get_option('cpa_consumer_secret'),
        ];
    }

    private static function call_api($method, $url, $body = []) {
        $credentials = self::get_api_credentials();

        if (!$credentials['endpoint'] || !$credentials['key'] || !$credentials['secret']) {
            return new WP_Error('missing_credentials', 'API credentials are missing.');
        }

        $args = [
            'method'  => $method,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($credentials['key'] . ':' . $credentials['secret']),
                'Content-Type'  => 'application/json',
            ],
            'body'    => $method === 'POST' ? wp_json_encode($body) : null,
        ];

        $response = wp_remote_request($credentials['endpoint'] . $url, $args);

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public static function render_list_products() {
        echo '<div class="wrap"><h1>List Products</h1>';
        $products = self::call_api('GET', '/products');
        if (is_wp_error($products)) {
            echo '<div class="notice notice-error"><p>' . esc_html($products->get_error_message()) . '</p></div>';
            return;
        }

        echo '<table class="widefat"><thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock Status</th></tr></thead><tbody>';
        foreach ($products as $product) {
            echo '<tr><td>' . esc_html($product['id']) . '</td>
                    <td>' . esc_html($product['name']) . '</td>
                    <td>' . esc_html($product['price']) . '</td>
                    <td>' . esc_html($product['stock_status']) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }

    public static function render_create_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cpa_nonce'])) {
            if (wp_verify_nonce($_POST['cpa_nonce'], 'cpa_create_product')) {
                $new_product = [
                    'name'        => sanitize_text_field($_POST['name']),
                    'description' => sanitize_textarea_field($_POST['description']),
                    'price'       => sanitize_text_field($_POST['price']),
                    'stock_quantity' => intval($_POST['stock_quantity']),
                ];
                $response = self::call_api('POST', '/products', $new_product);

                if (isset($response['id'])) {
                    echo '<div class="notice notice-success"><p>Product created successfully!</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>Error creating product.</p></div>';
                }
            }
        }

        echo '<div class="wrap"><h1>Create Product</h1>';
        echo '<form method="post">';
        wp_nonce_field('cpa_create_product', 'cpa_nonce');
        echo '<table class="form-table">
                <tr><th><label for="name">Product Name</label></th>
                    <td><input type="text" id="name" name="name" class="regular-text"></td></tr>
                <tr><th><label for="description">Description</label></th>
                    <td><textarea id="description" name="description" class="large-text"></textarea></td></tr>
                <tr><th><label for="price">Price</label></th>
                    <td><input type="text" id="price" name="price" class="regular-text"></td></tr>
                <tr><th><label for="stock_quantity">Stock Quantity</label></th>
                    <td><input type="number" id="stock_quantity" name="stock_quantity" class="regular-text"></td></tr>
              </table>';
        submit_button('Create Product');
        echo '</form></div>';
    }
}
