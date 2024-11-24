<?php
class Custom_Products_API_Settings {
    public static function render_page() {
        // Save settings when the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('custom_products_api_settings')) {
            update_option('custom_products_api_settings', [
                'api_endpoint' => sanitize_text_field($_POST['api_endpoint']),
                'consumer_key' => sanitize_text_field($_POST['consumer_key']),
                'consumer_secret' => sanitize_text_field($_POST['consumer_secret']),
                'enable_pagination' => isset($_POST['enable_pagination']) ? 1 : 0,
            ]);
            echo '<div class="updated"><p>Settings saved.</p></div>';
        }

        // Retrieve saved options
        $options = get_option('custom_products_api_settings', []);
        $api_endpoint = $options['api_endpoint'] ?? '';
        $consumer_key = $options['consumer_key'] ?? '';
        $consumer_secret = $options['consumer_secret'] ?? '';
        $enable_pagination = $options['enable_pagination'] ?? 0;

        ?>
        <div class="wrap">
            <h1>Configuration</h1>
            <form method="post">
                <?php wp_nonce_field('custom_products_api_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="api_endpoint">API Endpoint URL</label></th>
                        <td>
                            <input 
                                name="api_endpoint" 
                                id="api_endpoint" 
                                type="text" 
                                class="regular-text" 
                                value="<?php echo esc_attr($api_endpoint); ?>" 
                                placeholder="https://example.com/wp-json/wc/v3/" 
                                required
                            >
                            <p class="description">Enter the base API URL, such as <code>https://example.com/wp-json/wc/v3/</code>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="consumer_key">Consumer Key</label></th>
                        <td>
                            <input 
                                name="consumer_key" 
                                id="consumer_key" 
                                type="text" 
                                class="regular-text" 
                                value="<?php echo esc_attr($consumer_key); ?>" 
                                placeholder="ck_xxxxxxxxxxxxxxxxxxxxxxxx" 
                                required
                            >
                            <p class="description">Your WooCommerce REST API consumer key.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="consumer_secret">Consumer Secret</label></th>
                        <td>
                            <input 
                                name="consumer_secret" 
                                id="consumer_secret" 
                                type="text" 
                                class="regular-text" 
                                value="<?php echo esc_attr($consumer_secret); ?>" 
                                placeholder="cs_xxxxxxxxxxxxxxxxxxxxxxxx" 
                                required
                            >
                            <p class="description">Your WooCommerce REST API consumer secret.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="enable_pagination">Enable Pagination</label></th>
                        <td>
                            <input 
                                name="enable_pagination" 
                                id="enable_pagination" 
                                type="checkbox" 
                                value="1" 
                                <?php checked($enable_pagination, 1); ?>
                            >
                            <p class="description">Enable pagination for product lists.</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" class="button-primary">Save Changes</button>
                </p>
            </form>
        </div>
        <?php
    }
}

// Add the settings page to the WordPress admin menu
add_action('admin_menu', function () {
    add_menu_page(
        'Custom Products API Settings',
        'API Settings',
        'manage_options',
        'custom-products-api-settings',
        ['Custom_Products_API_Settings', 'render_page'],
        'dashicons-admin-generic',
        80
    );
});
