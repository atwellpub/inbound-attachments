<?php


if ( !class_exists( 'Inbound_Attachments_Fields' )) {

	class Inbound_Attachments_Fields {

		/**
		*	initiates class
		*/
		public function __construct() {		
			
			/* Define hooks and filters */
			self::load_hooks();
			
		}
		
		/**
		*	Loads hooks and filters selectively
		*/
		public static function load_hooks() {
			add_filter( 'inboundnow_forms_settings' , array( __CLASS__ , 'extend_field_type_dropdown' ) );
		}
		
		/**
		*  Extends field type dataset for Inbound Forms
		*/
		public static function extend_field_type_dropdown( $config ) {
			$config['forms']['child']['options']['field_type']['options']['attachments'] = __("Attachments Uploader", "inbound-pro");
			return $config;
		}
	}

	new Inbound_Attachments_Fields;
}