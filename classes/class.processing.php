<?php


if ( !class_exists( 'Inbound_Attachments_Processing' )) {

	class Inbound_Attachments_Processing {

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

	new Inbound_Attachments_Processing;
}