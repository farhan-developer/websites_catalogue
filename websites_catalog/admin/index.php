<?php

/**
 *
 * Create the WEBSITES custom post type
 *
**/
function wc_create_websites_post_type() {
    register_post_type('websites',
        array(
            'labels' => array(
                'name' => __('Websites', 'websites_catalog'), // The plural name for the custom post type.
                'singular_name' => __('Website', 'websites_catalog'), // The singular name for a single item of the custom post type.
            ),
            'public' => true, // Whether the custom post type should be publicly queryable. Would be useful for the wp-json api search query
            'menu_icon' => 'dashicons-admin-site', // The dashicon for the menu icon in the admin menu.
            'has_archive' => false,
            'supports' => array('title'),  // This will only show the title and will remove other default metaboxes except publish metabox.
            'capabilities' => array(
                'create_posts' => false, // Disables the ability to add new post form admin dashboard.
            ),
            'publicly_queryable' => true,
            'map_meta_cap' => true,
            'show_in_rest'=> true, // allow json api
            'rest_base'          => 'website',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        )
    );
}
add_action('init', 'wc_create_websites_post_type');


/**
 *
 * Hide the publish metabox as no one is allowed to edit the websites data
 *
**/
function wc_remove_publish_metabox () {
    remove_meta_box( 'submitdiv', 'websites', 'side' );
}
add_action( 'admin_menu', 'wc_remove_publish_metabox');


/**
 *
 * Callback for the custom metabox which contains a metabox with textarea to show the sourcecode of the url
 *
**/
function wc_source_code_meta_box( $post ) {
    $url = get_post_meta($post->ID, 'url', true);
    $url_response = wp_remote_get($url);
    $source_code = '';

    if (is_array($url_response) && isset($url_response['body'])) {
        $source_code = $url_response['body'];
    }
    ?>
    <label for="website_source_code"><?php _e('Source Code:', 'websites_catalog'); ?></label>
    <input type="hidden" name="website_link" id="website_link" value="<?php esc_attr_e($post->post_content); ?>">
    <textarea id="website_source_code" name="website_source_code" style="width: 100%;" rows="6"><?php esc_html_e($source_code); ?></textarea>
    <div id="source_code_result"></div>
    <?php
}



/**
 *
 * Add custom metabox of the text area
 *
**/
function wc_add_meta_boxes( $post ) {
   global $current_user;
   if($current_user->roles[0] === 'administrator') { // only administrator is allowed to view the sourcecode metabox
       add_meta_box( 'websites_meta_box', __( 'Website Source Code', 'websites_catalog' ), 'wc_source_code_meta_box', 'websites', 'advanced', 'low' );
   }
}
add_action( 'add_meta_boxes', 'wc_add_meta_boxes' );


/**
 *
 * Restrict the Websites posttype ony for administrators and editors
 *
**/
function wc_restrict_for_admins_and_editors($args, $post_type) {
    if ('websites' === $post_type) {
        // As only Administrators and Editors have capability to activate_plugins
        $args['show_in_menu'] = current_user_can('activate_plugins');
    }
    return $args;
}
add_filter('register_post_type_args', 'wc_restrict_for_admins_and_editors', 10, 2);
