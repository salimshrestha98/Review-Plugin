<?php

if ( ! defined( 'RP_Enqueue' ) ) :
    /**
     * Main Enqueue Class
     */
    final class RP_Enqueue {
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'add_stylesheets' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
        }

        /**
         * Enqueue Css Files
         */
        public function add_stylesheets() {
            wp_enqueue_style( 'rp-bootstrap-style', RP_PLUGIN_URL . "assets/bootstrap/css/bootstrap.min.css" );
            wp_enqueue_style( 'rp-style', RP_PLUGIN_URL . "assets/css/style.css" );
        }

        /**
         * Enqueue JS Files
         */
        public function add_scripts() {
            wp_enqueue_script( 'rp-main', RP_PLUGIN_URL . 'assets/js/main.js', array('jquery'), '1.0.0', true );

            $ajaxLocal = [
                'ajaxPostUrl' => esc_attr( RP_AJAX_POST_PATH )
            ];

            wp_localize_script( 'rp-main', 'ajaxVars', $ajaxLocal );
        }
    };

endif;

$rp_enqueue = new RP_Enqueue;

?>