<?php


if ( !class_exists( 'Inbound_Attachments_Settings' )) {

	class Inbound_Attachments_Settings {

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

	new Inbound_Attachments_Settings;
}