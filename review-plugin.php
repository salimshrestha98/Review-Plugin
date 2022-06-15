<?php

/**
 * Plugin Name: Review Plugin
 * Author: Salim Shrestha
 * Author URI: https://salim.com.np
 * Description: A plugin to collect and display user ratings transformed using oop
 * Text Domain: review-plugin
 */

 if ( ! class_exists( 'ReviewPlugin' ) ) {
     /**
      * Main ReviewPlugin Class
      * 
      * @class ReviewPlugin
      * @version 1.0.0
      */
      final class ReviewPlugin {
          /**
           * ReviewPlugin Constructor
           */
          public function __construct() {
              $this->define_constants();
              $this->includes();
              $this->init_hooks();
          }

          /**
           * Hook into actions and filters
           */
          private function init_hooks() {
            add_action( 'wp_ajax_rp_form_response', array( 'RP_Ajax', 'save_user_review' ) );
            add_action( 'wp_ajax_nopriv_rp_form_response', array( 'RP_Ajax', 'save_user_review' ) );
            add_action( 'rp_after_user_registration', array( 'RP_Ajax', 'send_registration_mail' ), 10, 1 );
            add_filter( 'rp_after_form_receive', array( 'RP_Ajax', 'extract_username' ), 10, 1 );
            add_action( 'wp_ajax_rp_stars_filter', array( 'RP_Ajax', 'get_reviews' ) );
            add_action( 'wp_ajax_nopriv_rp_stars_filter', array( 'RP_Ajax', 'get_reviews' ) );
          }

          private function define( $name, $value ) {
              if( ! defined( $name ) ) {
                  define( $name, $value );
              }
          }

          /**
           * Define Plugin Constants
           */
          private function define_constants() {
              $this->define( 'RP_PLUGIN_FILE', __FILE__ );
              $this->define( 'RP_ABSPATH', dirname( __FILE__ ) . '/' );
              $this->define( 'RP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
              $this->define( 'RP_AJAX_POST_PATH', admin_url( 'admin-ajax.php' ) );
              $this->define( 'RP_TEXT_DOMAIN', 'review-plugin' );
          }

          /**
           * Includes
           */
          private function includes() {
              include_once RP_ABSPATH . 'includes/rp-shortcodes.php';
              include_once RP_ABSPATH . 'includes/rp-ajax.php';
              include_once RP_ABSPATH . 'includes/rp-enqueue.php';
          }
      }
 }

 $start = new ReviewPlugin;

 ?>