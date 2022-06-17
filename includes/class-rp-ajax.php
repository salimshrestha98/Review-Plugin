<?php

if ( ! class_exists( 'RP_Ajax' ) ) :
	/**
	 * Main Ajax Class
	 */
	final class RP_Ajax {
		/**
		 * Constructor for RP_Ajax Class
		 * Hook into actions and filters
		 */
		public function __construct() {
			add_action( 'wp_ajax_rp_form_response', array( $this, 'save_user_review' ) );
			add_action( 'wp_ajax_nopriv_rp_form_response', array( $this, 'save_user_review' ) );
			add_action( 'rp_after_user_registration', array( $this, 'send_registration_mail' ), 10, 1 );
			add_filter( 'rp_after_form_receive', array( $this, 'extract_username' ), 10, 1 );
			add_action( 'wp_ajax_rp_stars_filter', array( $this, 'get_reviews' ) );
			add_action( 'wp_ajax_nopriv_rp_stars_filter', array( $this, 'get_reviews' ) );
		}

		/**
		 * Function to save user and user review
		 */
		public function save_user_review() {
			if ( isset( $form_data['rp-nonce'] ) && wp_verify_nonce( $form_data['rp-nonce'], 'rp-nonce' ) ) {
				parse_str( $_POST['data'], $form_data );
				$err_messages = array();
				$response_obj = array();

				$user_fname       = sanitize_text_field( $form_data['rp-fname'] );
				$user_lname       = sanitize_text_field( $form_data['rp-lname'] );
				$user_email       = sanitize_email( $form_data['rp-email'] );
				$user_password    = sanitize_text_field( $form_data['rp-pass'] );
				$user_review_text = sanitize_textarea_field( $form_data['rp-review-text'] );
				$user_rating      = sanitize_text_field( $form_data['rp-rating'] );

				$user_username = apply_filters( 'rp_after_form_receive', $user_email );

				if ( ! username_exists( $user_username ) && ! email_exists( $user_email ) ) {
					//  Create new user
					$uid = wp_create_user( $user_username, $user_password, $user_email );

					update_user_meta( $uid, 'first_name', $user_fname );
					update_user_meta( $uid, 'last_name', $user_lname );
					update_user_meta( $uid, 'review_content', $user_review_text );
					update_user_meta( $uid, 'review_rating', $user_rating );

					//  Leave an action hook for after registration actions
					do_action( 'rp_after_user_registration', $uid );

					$response_obj['status']     = esc_html__( 'success', RP_TEXT_DOMAIN );
					$response_obj['messages'][] = esc_html__( 'New User Created Successfully.', RP_TEXT_DOMAIN );
					$response_obj['messages'][] = esc_html__( 'User Review Saved Successfully.', RP_TEXT_DOMAIN );
				} else {
					$err_messages[] = esc_html__( 'Username or Email already exists.', RP_TEXT_DOMAIN );
				}
			} else {
				$err_messages[] = esc_html__( 'Invalid Request. Try again with proper browser.', RP_TEXT_DOMAIN );
			}
			if ( count( $err_messages ) ) {
				$response_obj['status']    = esc_html__( 'failed', RP_TEXT_DOMAIN );
				$response_obj['errorMsgs'] = $err_messages;
			}

			wp_send_json( $response_obj );
			exit;

		}

		/**
		 * Send an email to the registered user
		 */
		public function send_registration_mail( $uid ) {
			$user     = get_userdata( $uid );
			$email    = $user->user_email;
			$username = $user->user_nicename;

			//  Send registration email
			$mail_recipient = 'salim.shrestha@themegrill.com';
			$mail_subject   = 'Thank You for the review';
			$mail_body      = "
                Dear $username,
                    We have received your review successfully. Furthermore, your subscriber account has also been set up. The account details are as follows:
                    Email: $email,
                    Username: $username
                    
                Thank You!";

				wp_mail( array( $mail_recipient ), $mail_subject, $mail_body );
		}

		/**
		 * Extract Username from Email
		 */
		public function extract_username( $email ) {
			$email_parts = explode( '@', $email );
			$username    = $email_parts[0];

			return $username;
		}

		/**
		 * Get Reviews according to Ajax Query
		 */
		public function get_reviews() {
			if ( isset( $_POST['rp-nonce'] ) && wp_verify_nonce( $_POST['rp-nonce'], 'rp-nonce' ) ) {
				if ( isset( $_POST['starsCount'] ) && ( 0 !== $_POST['starsCount'] ) ) {
					$stars_filter = sanitize_text_field( $_POST['starsCount'] );
					$stars_comp   = '=';
				} else {
					$stars_filter = 5;
					$stars_comp   = '<=';
				}

				$args = array(
					'meta_query' => array(
						array(
							'key'     => 'review_rating',
							'value'   => $stars_filter,
							'compare' => $stars_comp,
						),
					),
				);

				if ( isset( $_POST['order'] ) ) {
					$args['order']   = $_POST['order'];
					$args['orderby'] = 'user_registered';
				}

				if ( isset( $_POST['offset'] ) ) {
					$args['offset'] = 6 * $_POST['offset'];
				}

				$args['number'] = 6;

				$user_query = new WP_User_Query( $args );

				$users = $user_query->get_results();

				$response = array();

				foreach ( $users as $user ) {
					$user_obj = array();

					$user_obj['fullName']   = get_user_meta( $user->ID, 'first_name', true ) . ' ' . get_user_meta( $user->ID, 'last_name', true );
					$user_obj['rating']     = get_user_meta( $user->ID, 'review_rating', true );
					$user_obj['reviewText'] = get_user_meta( $user->ID, 'review_content', true );
					$response[]             = $user_obj;
				}

				$total_reviews_count = $user_query->get_total();  // Get Total Number of Reviews

				$response_array = array(
					'reviews'      => $response,
					'totalReviews' => $total_reviews_count,
					'currentPage'  => $_POST['offset'],
				);

				wp_send_json( $response_array );

				exit;
			}
		}
	}
endif;

$rp_ajax = new RP_Ajax();

