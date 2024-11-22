<?php

class CPA_Settings {
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cpa_nonce'])) {
            if (wp_verify_nonce($_POST['cpa_nonce'], 'cpa_save_settings')) {
                update_option('cpa_api_endpoint', sanitize_text_field($_POST['cpa_api_endpoint']));
                update_option('cpa_consumer_key', sanitize_text_field($_POST['cpa_consumer_key']));
                update_option('cpa_consumer_secret', sanitize_text_field($_POST['cpa_consumer_secret']));
                echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
            }
        }

        $api_endpoint = get_option('cpa_api_endpoint', '');
        $consumer_key = get_option('cpa_consumer_key', '');
        $consumer_secret = get_option('cpa_consumer_secret', '');

        echo '<div class="wrap"><h1>API Configuration</h1>';
        echo '<form method="post">';
        wp_nonce_field('cpa_save_settings', 'cpa_nonce');
        echo '<table class="form-table">
                <tr><th><label for="cpa_api_endpoint">API Endpoint URL</label></th>
                    <td><input type="text" id="cpa_api_endpoint" name="cpa_api_endpoint" value="' . esc_attr($api_endpoint) . '" class="regular-text"></td></tr>
                <tr><th><label for="cpa_consumer_key">Consumer Key</label></th>
                    <td><input type="text" id="cpa_consumer_key" name="cpa_consumer_key" value="' . esc_attr($consumer_key) . '" class="regular-text"></td></tr>
                <tr><th><label for="cpa_consumer_secret">Consumer Secret</label></th>
                    <td><input type="text" id="cpa_consumer_secret" name="cpa_consumer_secret" value="' . esc_attr($consumer_secret) . '" class="regular-text"></td></tr>
              </table>';
        submit_button();
        echo '</form></div>';
    }
}
