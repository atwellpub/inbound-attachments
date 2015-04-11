<?php


if ( !class_exists( 'Inbound_Attachments_Enqueue' )) {

	class Inbound_Attachments_Enqueue {

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
			add_filter( 'wp_enqueue_scripts' , array( __CLASS__ , 'enqueue_files' ), 1 );
			
			/* Add handler to process field type */
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'enqueue_files') );
		}
		
		/**
		*  Enqueue JS & CSS files for front end and backend
		*/
		public static function enqueue_files( $config ) {
			$inbound_attachment_file_upload = 'var inbound_attachment_file_upload = "'. admin_url( 'admin-ajax.php' ).'/?action=inbound_attachment_file_upload";';
			$ajax_url = 'var ajaxurl = "'. admin_url( 'admin-ajax.php' ).'";';
			echo "<script type='text/javascript'>\n";
			echo "/* <![CDATA[ */\n";
			echo $inbound_attachment_file_upload;
			echo $ajax_url;
			echo "\n/* ]]> */\n";
			echo "</script>\n";
			
			wp_enqueue_style( 'inbound-attachment-bootstrap-min', '//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css' );
			wp_enqueue_style( 'inbound-attachment-style', INBOUND_ATTACHMENTS_URLPATH.'assets/css/style.css' );
			wp_enqueue_style( 'inbound-attachment-blueimp-gallery', '//blueimp.github.io/Gallery/css/blueimp-gallery.min.css' );
			wp_enqueue_style( 'inbound-attachment-jquery-fileupload', INBOUND_ATTACHMENTS_URLPATH.'assets/css/jquery.fileupload.css' );
			wp_enqueue_style( 'inbound-attachment-fileupload-ui', INBOUND_ATTACHMENTS_URLPATH.'assets/css/jquery.fileupload-ui.css' );
			
			
			wp_enqueue_script( 'inbound-attachment-ui-widget', includes_url() . 'js/jquery/ui/widget.min.js', array( 'jquery' ), true );
			
			wp_enqueue_script('inbound-attachment-jquery-tmpl', '//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js', array('jquery') , true );
			
			wp_enqueue_script('inbound-attachment-jquery-load-image', '//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-canvas-to-blob', '//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-bootstrap-min', '//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-blueimp-gallery', '//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-iframe-transport', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.iframe-transport.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-fileupload', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-fileupload-process', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-process.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-fileupload-image', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-image.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-fileupload-audio', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-audio.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-jquery-fileupload-video', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-video.js', array('jquery') , true );
			
			wp_enqueue_script('inbound-attachment-jquery-fileupload-validate', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-validate.js', array('jquery') , true );
			wp_enqueue_script('inbound-attachment-fileupload-ui', INBOUND_ATTACHMENTS_URLPATH.'assets/js/jquery.fileupload-ui.js', array('jquery') , true );
		}
		
		
	}
	

	new Inbound_Attachments_Enqueue;
}