<?php
/*
Plugin Name: Inbound Attachments
Plugin URI: http://www.inboundnow.com/
Description: Extends Inbound Forms with an attachments field type.
Version: 1.0.1
Author: Inbound Now
Author URI: http://www.inboundnow.com/
*
*/



if ( !class_exists( 'Inbound_Attachments' )) {

	class Inbound_Attachments {

		/**
		*	initiates class
		*/
		public function __construct() {

			global $wpdb;
		
			/* Define constants */
			self::define_constants();
			
			/* Define hooks and filters */
			self::load_hooks();
			
			/* load files */
			self::load_files();
		}
		
		/**
		*	Loads hooks and filters selectively
		*/
		public static function load_hooks() {
			/* Setup Automatic Updating & Licensing */
			add_action('admin_init', array( __CLASS__ , 'license_setup') );
		}
		
		
		/**
		*	Defines constants
		*/
		public static function define_constants() {
			define('INBOUND_ATTACHMENTS_CURRENT_VERSION', '0.0.1' ); 
			define('INBOUND_ATTACHMENTS_LABEL' , 'Inbound Attachments' ); 
			define('INBOUND_ATTACHMENTS_SLUG' , plugin_basename( dirname(__FILE__) ) ); 
			define('INBOUND_ATTACHMENTS_FILE' ,	__FILE__ ); 
			define('INBOUND_ATTACHMENTS_REMOTE_ITEM_NAME' , 'inbound-attaxchments' ); 
			define('INBOUND_ATTACHMENTS_URLPATH', plugins_url( '/', __FILE__ ) ); 
			define('INBOUND_ATTACHMENTS_PATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' ); 
		}
		
		/**
		* Setups Software Update API 
		*/
		public static function license_setup() {
			
			/* ignore these hooks if inbound pro is active */
			if (defined('INBOUND_PRO_CURRENT_VERSION')) {
				return $global_settings;
			}
	
			/*PREPARE THIS EXTENSION FOR LICESNING*/
			if ( class_exists( 'Inbound_License' ) ) {
				$license = new Inbound_License( INBOUND_ATTACHMENTS_FILE , INBOUND_ATTACHMENTS_LABEL , INBOUND_ATTACHMENTS_SLUG , INBOUND_ATTACHMENTS_CURRENT_VERSION	, INBOUND_ATTACHMENTS_REMOTE_ITEM_NAME ) ;
			}
		}
		
		/**
		*  Loads PHP files
		*/
		public static function load_files() {
			
			if ( is_admin() ) {		
				
				/* extend lead profile */
				include_once INBOUND_ATTACHMENTS_PATH . 'classes/class.lead-profiles.php';
				
				/* adds settings to global settings */
				include_once INBOUND_ATTACHMENTS_PATH . 'classes/class.settings.php';
				
			}
			
			/* extend inbound form fields */
			include_once INBOUND_ATTACHMENTS_PATH . 'classes/class.inbound-fields.php';
			
			/* processes form submissions */
			include_once INBOUND_ATTACHMENTS_PATH . 'classes/class.processing.php';
				
		}
		
	}

	/** 
	*	Load Inbound_Attachments class in init
	*/
	function Load_Inbound_Attachments() {
		$Inbound_Attachments = new Inbound_Attachments();
	}
	add_action( 'init' , 'Load_Inbound_Attachments' );
}