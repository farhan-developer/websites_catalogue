<?php
/**
 * Plugin Name: Website Form Plugin
 * Description: A WordPress plugin developed for ROPSTAM with a form to collect visitor's name and website URL then store it in a custom post type WEBSITES.
 * Version: 1.0.0
 * Author: Muhammad Farhan Khan
 * Text Domain: websites_catalog
**/


if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

if ( !class_exists( 'Sites_Catalog' ) ) {
    class Sites_Catalog {    
        public function __construct() {
            $this->wc_define_constants(); // Define constants that will be used in the plugin
            $this->wc_includes(); // include required files for admin and front
            
            add_action('wp_loaded', array( $this, 'sc_load_translations')); // initialize text domain for translation

            add_action('wp_ajax_save_website_data', array( $this, 'sc_save_website_data')); // ajax call back for loggedin users
            add_action('wp_ajax_nopriv_save_website_data', array( $this, 'sc_save_website_data')); // ajax call back for guest users
        }

        public function wc_define_constants() {
            define( 'WC_PLUGIN_PATH', plugin_dir_path(__FILE__) ); // Define a constant with the absolute server file path to the plugin directory.
            define( 'WC_PLUGIN_URL', plugin_dir_url(__FILE__) ); // Define a constant with the URL to the plugin directory.
        }

        public function wc_includes() {
            if (is_admin()) {
                require_once WC_PLUGIN_PATH . 'admin/index.php'; // including admin file
            } else {
                require_once WC_PLUGIN_PATH . 'public/index.php'; // including admin file
            }
        }

        public function sc_load_translations () {
            if ( function_exists( 'sc_load_translations' ) ) {
                sc_load_translations( 'websites_catalog', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            }
        }

        public function sc_save_website_data() {
            check_ajax_referer('wc_nonce_action', 'security'); // Nonce Verification

            $name = sanitize_text_field($_POST['name']);
            $url = esc_url_raw($_POST['url']);

            // Create a new post for the custom posttype Websites
            $post_id = wp_insert_post(array(
                'post_title' => $name, // set name as post title
                'post_type' => 'websites',
                'post_status' => 'publish',
            ));

            if ($post_id) {
                // Update custom fields
                update_post_meta($post_id, 'url', $url); // add url to the post_meta table

                // Send a success response
                echo json_encode(array('status' => 'success', 'message' => __('Website data saved successfully.', 'websites_catalog'), 'post_id' => $post_id));
            } else {
                // Send an error response
                echo json_encode(array('status' => 'error', 'message' => __('Failed to save website data.', 'websites_catalog')));
            }
            die();
        }

    }
    new Sites_Catalog();
}

