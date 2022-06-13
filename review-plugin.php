<?php

/**
 * Plugin Name: Review Plugin
 * Author: Salim Shrestha
 * Author URI: https://salim.com.np
 * Description: A plugin to collect and display user ratings
 * Text Domain: review-plugin
 */

//  Add Stylesheets
function rp_add_stylesheets() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . "assets/bootstrap/css/bootstrap.min.css" );
}
add_action( 'wp_enqueue_scripts', 'rp_add_stylesheets' );


// Add shortcode to display form in page
add_shortcode( 'rp-user-form', 'rp_user_form_main' );

function rp_user_form_main( $atts = [], $content = null ) {
    
    //  Adding User Form Template
    include_once 'includes/rp-user-form.php';
}

add_action( 'admin_post_rp_form_response', 'rp_form_handler' );
add_action( 'admin_post_nopriv_rp_form_response', 'rp_form_handler' );

function rp_form_handler() {
    echo "The form has been received.";
    $user_fname = sanitize_text_field ( $_POST['rp-fname'] );
    $user_lname = sanitize_text_field ( $_POST['rp-lname'] );
    $user_email = sanitize_email ( $_POST['rp-email'] );
    $user_password = sanitize_text_field ( $_POST['rp-pass'] );
    $user_review_text = sanitize_text_field ( $_POST['rp-review-text'] );
    $user_rating = sanitize_text_field ( $_POST['rp-rating'] );
    $user_username = strtolower( $user_fname + $user_lname + str( rand(100, 999 ) ) );

    wp_create_user( $user_username, $user_password, $user_email );
}

//  Register Review Post Type

function rp_register_review_post_type() {
        $labels = array(
            'name'          => 'Reviews',
            'singular_name' => 'Review',
            'add_new'       => 'Add New',
            'add_new_item'  => 'Add New Review',
            'edit_item'     => 'Edit Review',
            'new_item'      => 'New Review',
            'view_item'     => 'View Review',
            'view_items'    => 'View Reviews',
            'search_items'  => 'Search Reviews',
            'not_found'     => 'No Reviews found.',
            'archives'      => 'Review Archives',
            'atrributes'    => 'Review Attributes',
            'featured_image'=> 'Review Image',
            'items_list'    => 'Reviews List',
            'item_updated'  => 'Review updated'
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'has_archive'   => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => 'dashicons-feedback',
            'rewrite'       => array( 'slug' => 'reviews' ),
            'supports'      => array( 'title', 'editor' )
    );

    register_post_type( 'review', $args );
}
add_action( 'init', 'rp_register_review_post_type' );