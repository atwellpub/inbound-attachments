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
		
			/*  Add settings to inbound pro  */
			add_filter('inbound_settings/extend', array( __CLASS__  , 'define_pro_settings' ) );
			
			/* add setting tab for attachments in Landing Page Plugin*/
			add_filter('lp_define_global_settings', array( __CLASS__ , 'inbound_attachment_add_global_settings') );
			
			/* add setting tab for attachments in Lead Plugin*/
			add_filter('wpleads_define_global_settings', array( __CLASS__ , 'inbound_attachment_add_global_settings') );
			
			/* add setting tab for attachments in CTA Plugin*/
			add_filter('wp_cta_define_global_settings', array( __CLASS__ , 'inbound_attachment_add_global_settings') );

		}
		
		/**
		*  Add to core settings
		*/
		public static function inbound_attachment_add_global_settings($global_settings) {
			/* ignore these hooks if inbound pro is active */
			if (defined('INBOUND_PRO_CURRENT_VERSION')) {
				return $global_settings;
			}
			
			switch (current_filter() ) 	{
				case "lp_define_global_settings":		
					$tab_slug = 'lp-extensions';
					break;
				case "wpleads_define_global_settings":		
					$tab_slug = 'wpleads-extensions';
					break;
				case "wp_cta_define_global_settings":		
					$tab_slug = 'wp-cta-extensions';
					break;
			}
		
			$global_settings[$tab_slug]['settings'][] = 
				array(
					'id'  => 'inboundnow_attachment_filetypes_allowed',
					'option_name'  => 'inboundnow_attachment_filetypes_allowed',
					'label' => __('Attachment Filetypes Allowed', 'inbound-pro'),
					'description' => __('Enter the list of allowed filetypes separated by commas like: gif, png, jpg, pdf', 'inbound-pro'),
					'type'  => 'text', 
					'default'  => 'gif,jpeg,png,jpg,pdf,zip'
			);
					
			$global_settings[$tab_slug]['settings'][] = 
				array(
					'id'  => 'inboundnow_attachment_max_size_limit',
					'option_name'  => 'inboundnow_attachment_max_size_limit',
					'label' => __('Attachment Max Size Limit', 'inbound-pro'),
					'description' => __("Attachment Max Size Limit.", 'inbound-pro'),
					'type'  => 'text', 
					'default'  => 10000000
			);
			
			return $global_settings;
		}
		
		
		/**
		*  Adds pro admin settings
		*/
		public static function define_pro_settings( $settings ) {
			$settings['inbound-pro-settings'][] = array(
				'group_name' => INBOUND_ATTACHMENTS_SLUG ,
				'keywords' => __('attachments,forms,inbound forms,uploads,leads' , 'inbound-pro'),
				'fields' => array (
					array(
						'id'  => 'header_hubspot',
						'type'  => 'header',
						'default'  => __('Attachments', 'inbound-pro' ),
						'options' => null
					),
					array(
						'id'  => 'filetypes_allowed',
						'type'  => 'text',
						'label' => __('Attachment Filetypes Allowed', 'inbound-pro'),
						'description' => __('Enter the list of allowed filetypes separated by commas like: gif, png, jpg, pdf', 'inbound-pro'),
						'default'  => 'gif,jpeg,png,jpg,pdf,zip',
						'options' => null
					),
					array(
						'id'  => 'max_size_limit',
						'label' => __('Attachment Max Size Limit', 'inbound-pro'),
						'description' => __("Attachment Max Size Limit.", 'inbound-pro'),
						'type'  => 'text', 
						'default'  => 10000000
					)
				)

			);


			return $settings;

		}

		/**
		*  Get keys
		*/
		public static function get_settings() {
			$attachment_settings = array();
			
			if (!defined('INBOUND_PRO_CURRENT_VERSION')) {
				$attachment_settings['filetypes_allowed'] = get_option('inboundnow_attachment_filetypes_allowed' , 'gif,jpeg,png,jpg,pdf,zip');				
				$attachment_settings['max_size_limit'] = get_option('inboundnow_attachment_max_size_limit' , '10000000');				
			} else {
			
				$settings = Inbound_Options_API::get_option( 'inbound-pro' , 'settings' , array() );
				$attachment_settings['filetypes_allowed'] =  ( $settings[ INBOUND_ATTACHMENTS_SLUG ][ 'filetypes_allowed' ] ) ? $settings[ INBOUND_ATTACHMENTS_SLUG ][ 'filetypes_allowed' ] : 'gif,jpeg,png,jpg,pdf,zip';				
				$attachment_settings['max_size_limit'] =  ( $settings[ INBOUND_ATTACHMENTS_SLUG ][ 'max_size_limit' ] ) ? $settings[ INBOUND_ATTACHMENTS_SLUG ][ 'max_size_limit' ] : '10000000';				
				
			}
			
			$attachment_settings['filetypes_array'] = explode( ',', $attachment_settings['filetypes_allowed'] );
			$attachment_settings['filetypes'] = implode('|', $attachment_settings['filetypes_array'] );
			$attachment_settings['filetypes_regex'] = '/(\.|\/)('.$attachment_settings['filetypes'].')$/i';
			
			//error_log( print_r( $attachment_settings, true) );
			return $attachment_settings;
		}
		
	}

	new Inbound_Attachments_Settings;
}