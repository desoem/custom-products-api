<?php
/**
 * Plugin Name: Custom Products API
 * Description: Integrates with WooCommerce API to manage products on a remote WooCommerce site.
 * Version: 1.2
 * Author: Ridwan Sumantri
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include required files.
require_once plugin_dir_path(__FILE__) . 'includes/class-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-list-products.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-create-product.php';

// Register hooks and actions.
class Custom_Products_API {
    public static function init() {
        add_action('admin_menu', [self::class, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_assets']);
    }

    public static function add_admin_menu() {
        add_menu_page('Custom Products API', 'Custom Products API', 'manage_options', 'custom-products-api', [Custom_Products_API_List_Products::class, 'render_page']);
        add_submenu_page('custom-products-api', 'List Products', 'List Products', 'manage_options', 'custom-products-api', [Custom_Products_API_List_Products::class, 'render_page']);
        add_submenu_page('custom-products-api', 'Create Product', 'Create Product', 'manage_options', 'custom-products-api-create', [Custom_Products_API_Create_Product::class, 'render_page']);
        add_submenu_page('custom-products-api', 'Configuration', 'Configuration', 'manage_options', 'custom-products-api-settings', [Custom_Products_API_Settings::class, 'render_page']);
    }

    public static function enqueue_assets($hook) {
        if (strpos($hook, 'custom-products-api') !== false) {
            wp_enqueue_script('custom-products-api-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], '1.0.0', true);
            wp_enqueue_style('custom-products-api-style', plugin_dir_url(__FILE__) . 'assets/style.css', [], '1.0.0');
        }
    }
}

// Initialize the plugin.
Custom_Products_API::init();
