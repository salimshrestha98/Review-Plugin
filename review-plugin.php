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
    wp_enqueue_style( 'rp-bootstrap-style', $plugin_url . "assets/bootstrap/css/bootstrap.min.css" );
    wp_enqueue_style( 'rp-style', $plugin_url . "assets/css/style.css" );
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

//  Function to save user and user review
function rp_form_handler() {
    parse_str($_POST['data'], $formData);
    $err_messages = [];
    $response_obj = [];

    if ( isset( $formData['rp-nonce'] ) && wp_verify_nonce( $formData['rp-nonce'], 'rp-nonce' ) ) {
        $user_fname = sanitize_text_field( $formData['rp-fname'] );
        $user_lname = sanitize_text_field( $formData['rp-lname'] );
        $user_email = sanitize_email( $formData['rp-email'] );
        $user_password = sanitize_text_field( $formData['rp-pass'] );
        $user_review_text = sanitize_textarea_field( $formData['rp-review-text'] );
        $user_rating = sanitize_text_field( $formData['rp-rating'] );

        $user_username = apply_filters( 'rp_after_form_receive', $user_email );

        if ( ! username_exists( $user_username ) && ! email_exists( $user_email ) ) {
            //  Create new user
            $uid = wp_create_user( $user_username, $user_password, $user_email );

            error_log( "User registered with uid ".$uid );

            update_user_meta( $uid, 'first_name', $user_fname);
            update_user_meta( $uid, 'last_name', $user_lname);
            update_user_meta( $uid, 'review_content', $user_review_text );
            update_user_meta( $uid, 'review_rating', $user_rating);

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

add_action( 'wp_ajax_rp_stars_filter', 'rp_get_reviews' );
add_action( 'wp_ajax_nopriv_rp_stars_filter', 'rp_get_reviews' );

//  Function to fetch reviews according to ajax query
function rp_get_reviews() {
    if ( isset( $_POST['starsCount']) && $_POST['starsCount'] != 0 ) {
        $starsFilter = sanitize_text_field( $_POST['starsCount'] );
        $starsComp = "=";
    } else {
        $starsFilter = 5;
        $starsComp = "<=";
    }

    // error_log( print_r( $_POST, true) );

    $args = array(
                    'meta_query' => array(
                        array(
                            'key' => 'review_rating',
                            'value' => $starsFilter,
                            'compare' => $starsComp
                        )
                    )
                );

    if ( isset( $_POST['order'] ) ) {
        $args['order'] = $_POST['order'];
        $args['orderby'] = 'user_registered';
    }

    if ( isset( $_POST['offset'] ) ) {
        $args['offset'] = 6*$_POST['offset'];
    }

    $args['number'] = 6;
    
    error_log( print_r( $args, true) );

    $user_query = new WP_User_Query( $args );

    $users = $user_query->get_results();

    $response = [];

    foreach( $users as $user ) {
        $userObj = [];

        $userObj['fullName'] = get_user_meta( $user->ID, 'first_name', true ) . ' ' . get_user_meta( $user->ID, 'last_name', true );

        $userObj['rating'] = get_user_meta( $user->ID, 'review_rating', true );
        $userObj['reviewText'] = get_user_meta( $user->ID, 'review_content', true );
        $response[] = $userObj;
    }

    $total_reviews_count = $user_query->get_total();  // Get Total Number of Reviews

    $responseArray = array('reviews' => $response, 
                            'totalReviews' => $total_reviews_count,
                            'currentPage' => $_POST['offset']
                        );

    error_log( print_r( $responseArray, true));
    wp_send_json( $responseArray );
    
    exit;
}