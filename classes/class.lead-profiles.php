<?php


if ( !class_exists( 'Inbound_Attachments_Lead_Profile' )) {

	class Inbound_Attachments_Lead_Profile {

		/**
		*	initiates class
		*/
		public function __construct() {		
			
			/* Define hooks and filters */
			self::load_hooks();
			
		}
		
		/**
		*  Loads hooks and filters
		*/
		private function load_hooks() {
			
			/* add nav tabs */
			add_filter('wpl_lead_tabs', array( __CLASS__ , 'create_nav_tabs' ) , 10, 1);
			
			/* add inline css & js rules - maybe should consider enqueuing your css and js instead of printing inline */
			add_action('wp_footer', array( __CLASS__ , 'add_js_css' ) , 10);
			
			/* add nav tab content */
			add_action( 'wpl_print_lead_tab_sections' , array( __CLASS__ , 'add_content_container' ) );
			
		}
		
		/**
		*  	Create New Nav Tabs in WordPress Leads - Lead UI
		*/
		public static function create_nav_tabs( $nav_items ) {
			global $post;
			
			
			/* Add attachments tab */
			$nav_items[] = array(
				'id'=> 'wpleads_lead_tab_attachments',
				'label'=> __( 'Attachments' , 'inbound-pro' )
			);
			
			
			return $nav_items;
		}
		
		/**
		*  Prints container content
		*/
		public static function add_content_container() {
			
			global $post; 
			$lead_id = $post->ID;
			$wp_upload_dir = wp_upload_dir();
			$wp_upload_dir_path = $wp_upload_dir['basedir'];
			$upload_dir = $wp_upload_dir_path.'/leads/attachments/'.$lead_id.'/';
			$upload_url = $wp_upload_dir['baseurl'].'/leads/attachments/'.$lead_id.'/';
			$upload_url_thumbs = $wp_upload_dir['baseurl'].'/leads/attachments/'.$lead_id.'/thumbnail/';
			$wpleads_attachments = maybe_unserialize($wpleads_attachments);
			
			?>
			<div class="lead-profile-section" id="wpleads_lead_tab_attachments" >
				<div id="activity-data-display">
					<div id="all-lead-history">				
					<?php  
					
					$wpleads_attachments = $files1 = scandir($upload_dir);
					if(!empty($wpleads_attachments)){
						
						
						foreach($wpleads_attachments as $filename){
							if ( $filename == '.' || $filename == '..' || $filename == 'index.php'|| $filename == 'thumbnail' ) {
								continue;
							}
							$file = $upload_dir.$filename;
							
							if(!file_exists($file)){
								continue;
							}
							
							$filetype = wp_check_filetype($filename);
							$file_url =  $upload_url_thumbs.$filename; 
							$download_link = $upload_url.$filename; 
							if( preg_match( '/^image/', $filetype['type'] ) && file_exists($upload_dir.'thumbnail/'.$filename) ){
								?>
								<div class="lead-timeline recent-conversion-item page-view-item page cloned-item"> 
									<img src="<?php echo $file_url; ?>" />
									<div class="lead-timeline-body">
										<div class="lead-event-text">
											<a href="<?php echo $download_link; ?>" title="<?php _e( 'Click to download attachment' , 'inbound-pro' ); ?>" download><p><span class="lead-item-num" style="display: none;"></span><span class="lead-helper-text"><?php echo $file; ?></span></p></a>
										</div>
									</div>
								</div>
								<?php 
							} else {
								
								?>
								<div class="lead-timeline recent-conversion-item page-view-item page cloned-item"> 
									<a href="<?php echo $download_link; ?>" class="lead-timeline-img" download> </a>
									<div class="lead-timeline-body">
										<div class="lead-event-text">
											<p>
												<span class="lead-helper-text">
													<a href="<?php echo $download_link; ?>" title="<?php _e( 'Click to download attachment' , 'inbound-pro' ); ?>" download><?php echo $file; ?></a>
												</span>
											</p>
										</div>
									</div>
									
								</div>
								<?php
							}
														
						}
					} else {
						echo '<div>No attachment found.</div>';
					}
					?>
					</div>
				</div>
			</div>
			<?php
		}
		
		/**
		*  Prints inline css and js
		*/
		public static function add_js_css() {
			global $post;
	
			/* bail if not wp-lead screen */
			if ( isset($post) && $post->post_type != 'wp-lead' ) {
				return;
			}
			
			/* add supporting js and css here */
		}
		
	}

	new Inbound_Attachments_Lead_Profile;
}