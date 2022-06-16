<?php

if ( ! class_exists( 'RP_Ajax' ) ) :
    /**
     * Main Ajax Class
     */
    final class RP_Ajax {
        public function __construct() {

        }

        /**
         * Function to save user and user review
         */
        public function save_user_review() {
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

        /**
         * Send an email to the registered user
         */
        public function send_registration_mail( $uid ) {
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
        }

        /**
         * Extract Username from Email
         */
        public function extract_username( $email ) {
            $email_parts = explode( '@', $email );
            $username = $email_parts[0];

            return $username;
        }

        /**
         * Get Reviews according to Ajax Query
         */
        public function get_reviews() {
            if ( isset( $_POST['starsCount']) && $_POST['starsCount'] != 0 ) {
                $starsFilter = sanitize_text_field( $_POST['starsCount'] );
                $starsComp = "=";
            } else {
                $starsFilter = 5;
                $starsComp = "<=";
            }
        
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
                                
            wp_send_json( $responseArray );
            
            exit;
        }
    }
endif;

$rp_ajax = new RP_Ajax;

?>