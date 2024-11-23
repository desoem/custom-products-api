<?php
class Custom_Products_API_List_Products {
    public static function render_page() {
        echo '<div class="wrap">';
        echo '<h1>List Products</h1>';
        echo '<div id="product-list">';
        echo '<p>Loading products...</p>';
        echo '</div>';
        echo '</div>';

        ?>
        <script>
            jQuery(document).ready(function ($) {
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'fetch_products'
                    },
                    success: function (response) {
                        $('#product-list').html(response);
                    },
                    error: function () {
                        $('#product-list').html('<p>Error fetching products.</p>');
                    }
                });
            });
        </script>
        <?php
    }
}

add_action( 'wp_ajax_fetch_products', function () {
    $options = get_option( 'custom_products_api_settings', array() );
    $endpoint = $options['api_endpoint'] ?? '';
    $consumer_key = $options['consumer_key'] ?? '';
    $consumer_secret = $options['consumer_secret'] ?? '';

    if ( $endpoint && $consumer_key && $consumer_secret ) {
        $response = wp_remote_get( "$endpoint/products", array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( "$consumer_key:$consumer_secret" )
            )
        ));

        if ( is_wp_error( $response ) ) {
            echo '<p>Error: ' . $response->get_error_message() . '</p>';
        } else {
            $products = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( is_array( $products ) ) {
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr></thead>';
                echo '<tbody>';
                foreach ( $products as $product ) {
                    echo '<tr>';
                    echo '<td>' . esc_html( $product['id'] ) . '</td>';
                    echo '<td>' . esc_html( $product['name'] ) . '</td>';
                    echo '<td>' . esc_html( $product['price'] ) . '</td>';
                    echo '<td>' . esc_html( $product['stock_status'] ) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No products found.</p>';
            }
        }
    }
    wp_die();
});
