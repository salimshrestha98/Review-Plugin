
# Review-Plugin

## Description
This is a wordpress plugin to collect user reviews. It also facilitates user registration, review filtering and pagination.

## Plugin Details
  #### Name : Review Plugin
  #### Author : Salim Shrestha
  #### Text Domain : review-plugin
  
## Plugin Constants
  #### RP_PLUGIN_FILE  => Plugin File
  #### RP_ABSPATH  => Plugin Absolute Path
  #### RP_PLUGIN_URL => Plugin Directory Path
  #### RP_AJAX_POST_PATH => Path to admin.ajax file
  #### RP_TEXT_DOMAIN  => Plugin Text Domain

## Plugin Classes
  #### ReviewPlugin => Main Plugin Class
  #### RP_Ajax  => Ajax Class
  #### RP_Shortcodes  => Shortcode Class
  #### RP_Enqueue => Enqueue Class
  
## Shortcodes
  #### [rp-user-form] => Form Template to collect User Review and User Details
  #### [rp-testimonials] => Html template to display user reviews as grid
  
##  Plugin Assets
  #### Bootstrap => Contains bootstrap library
  #### css/style.css  => Main Plugin Stylesheet
  #### js/main.js => Main Plugin Javascript code
  
## File Details
 #### [review-plugin.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/review-plugin.php) => Main Plugin File 
 #### [/includes/rp-ajax.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/includes/rp-ajax.php) => Ajax Class File
 #### [/includes/rp-enqueue.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/includes/rp-enqueue.php) => Enqueue Class File
 #### [/includes/rp-shortcodes.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/includes/rp-shortcodes.php) => Shortcodes Class File
 #### [/templates/rp-user-form-html.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/templates/rp-user-form-html.php) => HTML Template for Registration Form
 #### [/templates/rp-testimonials-html.php](https://github.com/salimshrestha98/Review-Plugin/blob/master/templates/rp-testimonials-html.php) => HTML Template to display Reviews in a grid
