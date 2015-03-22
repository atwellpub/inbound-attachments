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
			/* Add new field type to dropdown */
			add_filter( 'inboundnow_forms_settings' , array( __CLASS__ , 'extend_field_type_dropdown' ) );
			
			/* Add handler to process field type */
			add_action( 'inbound_form_custom_field' , array( __CLASS__ , 'render_attachment_uploader') );
		}
		
		/**
		*  Extends field type dataset for Inbound Forms
		*/
		public static function extend_field_type_dropdown( $config ) {
			$config['forms']['child']['options']['field_type']['options']['attachments'] = __("Attachments Uploader", "inbound-pro");
			return $config;
		}
		
		
		/**
		*  Listens for attachment uploader field type and renders it 
		*/
		public static function render_attachment_uploader( $field ) {
			/* only render field if 'attachments' type is selected. */ 
			
			/* here are the values passed by the field that can be used to determine what kind of custom field this is */
			print_r($field);exit;
		}
	}
	

	new Inbound_Attachments_Fields;
}