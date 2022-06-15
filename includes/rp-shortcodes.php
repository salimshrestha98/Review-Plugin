<?php

if ( ! class_exists( 'UR_Shortcode' ) ) :
    /**
     * Main Shortcode class
     */
    final class UR_Shortcode {
        public function __construct() {
            // Add shortcode to display form in page
            add_shortcode( 'rp-user-form', array( $this, 'user_form_main' ) );

            //  Add shortcode to display user testimonials grid
            add_shortcode( 'rp-testimonials', array( $this, 'testimonials_view' ) );
        }

        public function user_form_main( $atts = [], $content = null ) {
    
            //  Adding User Form Template
            include_once RP_ABSPATH . 'templates/rp-user-form-html.php';
        }

        public function testimonials_view() {
            include_once RP_ABSPATH . 'templates/rp-testimonials-html.php';
        }
    };

endif;

$shortcode = new UR_Shortcode;

?>