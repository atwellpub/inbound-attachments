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
			
		}
		
		
	}

	new Inbound_Attachments_Fields;
}