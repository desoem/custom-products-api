<?php
class Custom_Products_API_Create_Product {
    public static function render_page() {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer( 'create_product', 'create_product_nonce' ) ) {
            $options = get_option( 'custom_products_api_settings', array() );
            $endpoint = $options['api_endpoint'] ?? '';
            $consumer_key = $options['consumer_key'] ?? '';
            $consumer_secret = $options['consumer_secret'] ?? '';

            $data = array(
                'name' => sanitize_text_field( $_POST['name'] ),
                'description' => sanitize_textarea_field( $_POST['description'] ),
                'regular_price' => sanitize_text_field( $_POST['price'] ),
                'stock_quantity' => intval( $_POST['stock_quantity'] )
            );

            $response = wp_remote_post( "$endpoint/products", array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( "$consumer_key:$consumer_secret" ),
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode( $data )
            ));

            if ( is_wp_error( $response ) ) {
                echo '<div class="notice notice-error is-dismissible"><p>Error: ' . $response->get_error_message() . '</p></div>';
            } else {
                $status_code = wp_remote_retrieve_response_code( $response );
                if ( $status_code == 201 ) {
                    echo '<div class="notice notice-success is-dismissible"><p>Product created successfully.</p></div>';
                } else {
                    $response_body = wp_remote_retrieve_body( $response );
                    echo '<div class="notice notice-error is-dismissible"><p>Failed to create product. Response Code: ' . $status_code . '</p>';
                    echo '<p>' . esc_html( $response_body ) . '</p></div>';
                }
            }
        }

        // Start rendering the form
        echo '<div class="wrap">';
        echo '<h1>Create Product</h1>';
        echo '<form method="post" class="custom-create-product-form">';
        wp_nonce_field( 'create_product', 'create_product_nonce' );
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
}
