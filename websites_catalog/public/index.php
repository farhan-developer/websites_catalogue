<?php

/**
 *
 * Enqueue scripts and styles to be used in the frontend
 *
**/
function wc_enqueue_form_styles_and_scripts() {
    wp_enqueue_style('website-form-styles', WC_PLUGIN_URL . 'public/css/styles.css');

    wp_enqueue_script('custom-script', WC_PLUGIN_URL . '/public/js/save_data_ajax.js', array('jquery'), '1.0', true);
   
    wp_localize_script('custom-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'), // Passing the admin url admin-ajax.php to the script
        'security' => wp_create_nonce('wc_nonce_action'), // Passing the my_nonce_action for the nonce verification to the script
    ));
}
add_action('wp_enqueue_scripts', 'wc_enqueue_form_styles_and_scripts');


/**
 *
 * Shortcode that can be used in the front page of the website to show the form
 *
**/
function wc_form_content() {
    ob_start();
    require_once WC_PLUGIN_PATH . 'public/form_html.php'; //included the form html
    return ob_get_clean();
}
add_shortcode('wc_website_form', 'wc_form_content');
