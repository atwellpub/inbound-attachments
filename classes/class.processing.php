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
			
			/* Add Lead Reference To File Uploaded */
			add_action( 'inbound_store_lead_post' , array( __CLASS__ , 'reference_leads_and_move_files' ) , 5 , 1);
			
			add_filter( 'inbound_lead_notification_attachments' , array( __CLASS__ , 'inbound_attachment_email_attachment_notification' ) );
			
			
		}
		
		/**
		*  Builds an attachment reference and moves files into lead id folder
		*/
		public static function reference_leads_and_move_files( $lead ){
;
			$raw_params = ( isset( $_POST['raw_params'] ) ) ? $_POST['raw_params']  : false;
			parse_str($raw_params);
			
			if (!isset($inbound_attachment_files) || !$inbound_attachment_files) {
				return;
			}

			$wp_upload_dir = wp_upload_dir();
			$upload_url = $wp_upload_dir['baseurl'].'/leads/attachments/'.$lead['id'].'/';


			$upload_dir = self::get_attachments_directory();
			$lead_dir = self::get_attachments_directory( $lead['id'] );

			$uploaded_files = explode('|', $inbound_attachment_files);
			$moved_files =  array();

			foreach($uploaded_files as $name){

				if (!$name){
					continue;
				}

				/* create lead folder if does not exist */
				if ( !file_exists( $lead_dir ) ) {
					mkdir( $lead_dir , 0755, true);
					$file = fopen( $lead_dir . 'index.php',"w");

					mkdir( $lead_dir . 'thumbnail' , 0755, true);
					$file = fopen( $lead_dir . 'thumbnail/index.php',"w");

					fclose($file);
				}

				/* move file to lead folder */
				copy( $upload_dir.$name , $lead_dir.$name);

				/* delete original upload */
				unlink(  $upload_dir.$name );

				/* if thumbnail exists move that too */
				if ( file_exists( $upload_dir.'thumbnail/'.$name ) ) {
					/* move thumbnail to lead directory */
					copy( $upload_dir.'thumbnail/'.$name , $lead_dir.'thumbnail/'.$name);
					/* delete original thumbnail */
					unlink( $upload_dir.'thumbnail/'.$name );
				}



				/* prepare array for leads record */
				if(file_exists($lead_dir.$name)){
					$moved_files[] = $upload_url.$name;
				}

			}

			/* update lead meta for record sake */
			$records = get_post_meta( $lead['id'] , 'wpleads_attachments' , true);
			$records = json_decode($records,true);
			$records = (!is_array($records)) ? array() : $records;
			//error_log(print_r($moved_files,true));

			$records = array_merge( $records , $moved_files );
			//error_log(print_r($records,true));
			update_post_meta( $lead['id'] , 'wpleads_attachments' , json_encode($records) );
		}
		
		/**
		*  Includes attachment in email notification
		*/
		public static function inbound_attachment_email_attachment_notification( $attachments ){
			
			if (!class_exists('Inbound_API') || !isset($_POST['wpleads_email_address']) || !isset( $_POST['inbound_attachment_files'] ) ) {
				return $attachments;
			}
			
			$lead_id = Inbound_API::leads_get_id_from_email($_POST['wpleads_email_address']);			
			$lead_dir = self::get_attachments_directory( $lead_id );
			
			$uploaded_files = explode('|', $_POST['inbound_attachment_files']);
			$files =  array();

			foreach($uploaded_files as $name){
				if (!$name){
					continue;
				}
			
				if(file_exists($lead_dir.$name)){
					$files[] = $lead_dir.$name;
				}
			}

			return $attachments = $files;
			
		}
	
		/**
		*  Gets path to attachments upload directory
		*  @param INT $lead_id 
		*/
		public static function get_attachments_directory( $lead_id = null ) {
			$wp_upload_dir = wp_upload_dir();
			$wp_upload_dir_path = $wp_upload_dir['basedir'];
			$upload_dir = $wp_upload_dir_path.'/leads/attachments/';
			
			if ($lead_id) {
				$upload_dir = $upload_dir . $lead_id . '/';
			}
			
			return $upload_dir;
		}
	}

	new Inbound_Attachments_Processing;
}