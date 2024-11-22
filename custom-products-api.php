<?php
/**
 * Plugin Name: Custom Products API
 * Description: A WordPress plugin to integrate with a remote WooCommerce API to manage products. Yorprofirm WP plugin.
 * Version: 1.0
 * Author: Ridwan Sumantri
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Autoload required files
require_once plugin_dir_path(__FILE__) . 'includes/class-cpa-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cpa-products.php';

// Initialize the plugin
class CustomProductsAPI {
    public function __construct() {
        add_action('admin_menu', [$this, 'register_admin_menu']);
    }

    public function register_admin_menu() {
        add_menu_page(
            'Custom Products API',
            'Custom Products API',
            'manage_options',
            'custom-products-api',
            null,
            'dashicons-products'
        );

        add_submenu_page(
            'custom-products-api',
            'List Products',
            'List Products',
            'manage_options',
            'cpa-list-products',
            ['CPA_Products', 'render_list_products']
        );

        add_submenu_page(
            'custom-products-api',
            'Create Product',
            'Create Product',
            'manage_options',
            'cpa-create-product',
            ['CPA_Products', 'render_create_product']
        );

        add_submenu_page(
            'custom-products-api',
            'Configuration',
            'Configuration',
            'manage_options',
            'cpa-settings',
            ['CPA_Settings', 'render_settings_page']
        );
    }
}

// Start the plugin
new CustomProductsAPI();
