<?php
class Custom_Products_API_Settings {
    public static function render_page() {
        if ( isset( $_POST['submit'] ) && check_admin_referer( 'save_settings', 'custom_products_api_nonce' ) ) {
            $settings = array(
                'api_endpoint'  => esc_url_raw( $_POST['api_endpoint'] ),
                'consumer_key'  => sanitize_text_field( $_POST['consumer_key'] ),
                'consumer_secret' => sanitize_text_field( $_POST['consumer_secret'] )
            );
            update_option( 'custom_products_api_settings', $settings );
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
        }

        $options = get_option( 'custom_products_api_settings', array() );

        echo '<div class="wrap">';
        echo '<h1>Configuration</h1>';
        echo '<form method="post" class="custom-products-api-config-form">';
        wp_nonce_field( 'save_settings', 'custom_products_api_nonce' );

        echo '<fieldset>';
        echo '<legend>API Settings</legend>';
        echo '<p><label for="api_endpoint">API Endpoint URL:</label><br>';
        echo '<input type="text" id="api_endpoint" name="api_endpoint" value="' . esc_attr( $options['api_endpoint'] ?? '' ) . '" class="regular-text"></p>';
        echo '<p><label for="consumer_key">Consumer Key:</label><br>';
        echo '<input type="text" id="consumer_key" name="consumer_key" value="' . esc_attr( $options['consumer_key'] ?? '' ) . '" class="regular-text"></p>';
        echo '<p><label for="consumer_secret">Consumer Secret:</label><br>';
        echo '<input type="text" id="consumer_secret" name="consumer_secret" value="' . esc_attr( $options['consumer_secret'] ?? '' ) . '" class="regular-text"></p>';
        echo '</fieldset>';

        echo '<p><input type="submit" name="submit" class="button-primary" value="Save Settings"></p>';
        echo '</form>';
        echo '</div>';
    }
}
