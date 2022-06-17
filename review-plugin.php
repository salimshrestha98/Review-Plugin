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
		}

		/**
		 * Define constants if not defined
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Define Plugin Constants
		 */
		private function define_constants() {
			$this->define( 'RP_PLUGIN_FILE', __FILE__ );
			$this->define( 'RP_VERSION', '1.0.0' );
			$this->define( 'RP_ABSPATH', dirname( __FILE__ ) . '/' );
			$this->define( 'RP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'RP_AJAX_POST_PATH', admin_url( 'admin-ajax.php' ) );
			$this->define( 'RP_TEXT_DOMAIN', 'review-plugin' );
		}

		/**
		 * Include essential class files
		 */
		private function includes() {
			include_once RP_ABSPATH . 'includes/class-rp-shortcodes.php';
			include_once RP_ABSPATH . 'includes/class-rp-ajax.php';
			include_once RP_ABSPATH . 'includes/class-rp-enqueue.php';
		}
	}
}

$start = new ReviewPlugin();
