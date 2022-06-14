<?php

/**
 * Plugin Name: Review Plugin
 * Author: Salim Shrestha
 * Author URI: https://salim.com.np
 * Description: A plugin to collect and display user ratings
 * Text Domain: review-plugin
 */

function rp_register_scripts() {
    wp_register_script( 'rp-main', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array(), '1.0.0', true );
}

//  Localize ajaxParameters
function rp_localize() {
    $ajaxLocal = [
        'ajaxPostUrl' => esc_attr( admin_url( 'admin-ajax.php' ))
    ];

    wp_localize_script( 'rp-main', 'ajaxVars', $ajaxLocal );
}

add_action( 'init', 'rp_register_scripts' );
add_action( 'init', 'rp_localize');

//  Add Stylesheets
function rp_add_stylesheets() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'rp-style', $plugin_url . "assets/bootstrap/css/bootstrap.min.css" );
}

//  Add Scripts
function rp_add_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_script( 'rp-main' );
}

add_action( 'wp_enqueue_scripts', 'rp_add_stylesheets' );
add_action( 'wp_enqueue_scripts', 'rp_add_scripts' );


// Add shortcode to display form in page
add_shortcode( 'rp-user-form', 'rp_user_form_main' );

function rp_user_form_main( $atts = [], $content = null ) {
    
    //  Adding User Form Template
    include_once 'includes/rp-user-form.php';
}

add_action( 'wp_ajax_rp_form_response', 'rp_form_handler' );
add_action( 'wp_ajax_nopriv_rp_form_response', 'rp_form_handler' );

function rp_form_handler() {
    parse_str($_POST['data'], $formData);
    $err_messages = [];
    $response_obj = [];

    if ( isset( $formData['rp-nonce'] ) && wp_verify_nonce( $formData['rp-nonce'], 'rp-nonce' ) ) {
        $user_fname = sanitize_text_field( $formData['rp-fname'] );
        $user_lname = sanitize_text_field( $formData['rp-lname'] );
        $user_email = sanitize_email( $formData['rp-email'] );
        $user_password = sanitize_text_field( $formData['rp-pass'] );
        $user_review_title = sanitize_text_field( $formData['rp-review-title'] );
        $user_review_text = sanitize_textarea_field( $formData['rp-review-text'] );
        $user_rating = sanitize_text_field( $formData['rp-rating'] );

        $user_username = apply_filters( 'rp_after_form_receive', $user_email );

        if ( ! username_exists( $user_username ) && ! email_exists( $user_email ) ) {
            //  Create new user
            $uid = wp_create_user( $user_username, $user_password, $user_email );

            add_user_meta( $uid, 'first_name', $user_fname);
            add_user_meta( $uid, 'last_name', $user_lname);
            add_user_meta( $uid, 'review_content', $user_review_text );
            add_user_meta( $uid, 'review_rating', $user_rating);

            //  Leave an action hook for after registration actions
            do_action( 'rp_after_user_registration', $uid );

            $response_obj['status'] = 'success';
            $response_obj['messages'][] = 'New User Created Successfully.';
            $response_obj['messages'][] = 'User Review Saved Successfully.';
        } else {
            $err_messages[] = "Username or Email already exists.";
        }
    } else {
        $err_messages[] = "Invalid Request. Try again with proper browser.";
    }
    if ( count( $err_messages ) ) {
        $response_obj['status'] = 'failed';
        $response_obj['errorMsgs'] = $err_messages;
    }

    wp_send_json( $response_obj );
    exit;

}

add_action( 'rp_after_user_registration', 'rp_send_registration_mail', 10, 1 );

function rp_send_registration_mail( $uid ) {
    $user = get_userdata( $uid );
    $email = $user->user_email;
    $username = $user->user_nicename;
    
    //  Send registration email
    $mail_recipient = "salim.shrestha@themegrill.com";
    $mail_subject = "Thank You for the review";
    $mail_body = "
        Dear $username,
            We have received your review successfully. Furthermore, your subscriber account has also been set up. The account details are as follows:
            Email: $email,
            Username: $username
            
        Thank You!";

        wp_mail( [ $mail_recipient ], $mail_subject, $mail_body );
        error_log($mail_body);
}

add_filter( 'rp_after_form_receive', 'rp_extract_username', 10, 1 );

//  Extract Username from Email
function rp_extract_username( $email ) {
    $email_parts = explode( '@', $email );
    $username = $email_parts[0];

    return $username;
}

add_shortcode( 'rp-testimonials', 'rp_testimonials_view' );

function rp_testimonials_view() {
    include_once "includes/rp-testimonials-html.php";
};