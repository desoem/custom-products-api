<?php
class Custom_Products_API_Settings {
    public static function render_page() {
        // Save settings on form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('custom_products_api_settings')) {
            update_option('custom_products_api_settings', [
                'api_endpoint' => sanitize_text_field($_POST['api_endpoint']),
                'consumer_key' => sanitize_text_field($_POST['consumer_key']),
                'consumer_secret' => sanitize_text_field($_POST['consumer_secret']),
                'enable_pagination' => isset($_POST['enable_pagination']) ? 1 : 0,
            ]);
            echo '<div class="updated"><p>Settings saved.</p></div>';
        }

        // Get plugin settings
        $options = get_option('custom_products_api_settings', []);

        // Ensure the default API endpoint is set if not already saved
        if (empty($options['api_endpoint'])) {
            $options['api_endpoint'] = 'https://stagingdeveloper.site/wp-json/wc/v3/products';
            update_option('custom_products_api_settings', $options);
        }
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
                                value="<?php echo esc_attr($options['api_endpoint'] ?? ''); ?>" 
                                required
                            >
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
                                value="<?php echo esc_attr($options['consumer_key'] ?? ''); ?>" 
                                required
                            >
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
                                value="<?php echo esc_attr($options['consumer_secret'] ?? ''); ?>" 
                                required
                            >
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
                                <?php checked($options['enable_pagination'] ?? 0, 1); ?>
                            >
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
