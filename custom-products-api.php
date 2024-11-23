<?php
/**
 * Plugin Name: Custom Products API
 * Description: Integrates with WooCommerce API to manage products on a remote WooCommerce site.
 * Version: 1.1
 * Author: Ridwan Sumantri
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define constants
define( 'CUSTOM_PRODUCTS_API_DIR', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_PRODUCTS_API_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once CUSTOM_PRODUCTS_API_DIR . 'includes/class-settings.php';
require_once CUSTOM_PRODUCTS_API_DIR . 'includes/class-list-products.php';
require_once CUSTOM_PRODUCTS_API_DIR . 'includes/class-create-product.php';

class Custom_Products_API {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'Custom Products API',
            'Custom Products API',
            'manage_options',
            'custom-products-api',
            array( 'Custom_Products_API_List_Products', 'render_page' ),
            'dashicons-products'
        );

        add_submenu_page(
            'custom-products-api',
            'List Products',
            'List Products',
            'manage_options',
            'custom-products-api',
            array( 'Custom_Products_API_List_Products', 'render_page' )
        );

        add_submenu_page(
            'custom-products-api',
            'Create Product',
            'Create Product',
            'manage_options',
            'custom-products-api-create',
            array( 'Custom_Products_API_Create_Product', 'render_page' )
        );

        add_submenu_page(
            'custom-products-api',
            'Configuration',
            'Configuration',
            'manage_options',
            'custom-products-api-config',
            array( 'Custom_Products_API_Settings', 'render_page' )
        );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'custom-products-api-styles', CUSTOM_PRODUCTS_API_URL . 'assets/styles.css' );
        wp_enqueue_script( 'custom-products-api-scripts', CUSTOM_PRODUCTS_API_URL . 'assets/scripts.js', array( 'jquery' ), null, true );
    }
}

new Custom_Products_API();
