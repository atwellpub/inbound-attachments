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


			?>
			<style>

				#attachment-history {
					margin-left: -30px;
					margin-top: 10px;
				}

				#attachment-history, .lead-timeline {
					position: relative
				}

				#attachment-history:before, .lead-timeline:before, .lead-timeline .lead-event-text:before {
					content: "";
					position: absolute;
					top: 0;
					left: 65px;
				}

				#attachment-history:before {
					width: 3px;
					left: 105px;
					bottom: 0px;
					background: rgba(0, 0, 0, 0.1);
				}

				#attachment-history ol {
					padding-left: 20px;
				}

				#attachment-history .recent-conversion-item {
					margin-left: 0px;
					padding-left: 0px;
				}

				#attachment-history .recent-conversion-item {
					border-bottom: none;
				}
			</style>
			<div class="lead-profile-section" id="wpleads_lead_tab_attachments" >
			<div id="activity-data-display">
				<div id="attachment-history">
					<?php
					$wpleads_attachments =  (file_exists($upload_dir)) ? scandir($upload_dir) : array() ;

					$records = get_post_meta( $lead_id , 'wpleads_attachments' , true);
					$records = json_decode($records,true);
					$records = (!is_array($records)) ? array() : $records;


					$wpleads_attachments = array_merge( $records , $wpleads_attachments );

					if($wpleads_attachments){
						$i = 0;
						foreach($wpleads_attachments as $filename){
							if ( $filename == '.' || $filename == '..' || $filename == 'index.php'|| $filename == 'thumbnail' ) {
								continue;
							}
							$file = $upload_dir.$filename;

							if(!file_exists($file) && !strstr( $filename ,'http' )){
								continue;
							}

							if (!strstr('http')) {
								$filetype = wp_check_filetype($filename);
								$file_url =  $upload_url_thumbs.$filename;
								$download_link = $upload_url.$filename;
							} else {
								$download_link = $filename;
							}

							if( preg_match( '/^image/', $filetype['type'] ) && file_exists($upload_dir.'thumbnail/'.$filename) ){
								?>
								<div class="lead-timeline recent-conversion-item page-view-item page">
									<img src="<?php echo $file_url; ?>" class="lead-timeline-img" download style='max-width:50px;margin-top:10px;'/>
									<div class="lead-timeline-body">
										<div class="lead-event-text">
											<p>
											<span class="lead-helper-text"><?php echo rand(0,100);; ?>
												<a href="<?php echo $download_link; ?>" title="<?php _e( 'Click to download attachment' , 'inbound-pro' ); ?>" download><p><span class="lead-item-num" style="display: none;"></span><span class="lead-helper-text"><?php echo $filename; ?></span></p></a>
											</span>
											</p>
										</div>
									</div>
								</div>
								<?php
							} else {

								?>
								<div class="lead-timeline recent-conversion-item page-view-item page ">
									<a href="<?php echo $download_link; ?>" class="lead-timeline-img" download> </a>
									<div class="lead-timeline-body">
										<div class="lead-event-text">
											<p>
												<span class="lead-helper-text">
													<a href="<?php echo $download_link; ?>" title="<?php _e( 'Click to download attachment' , 'inbound-pro' ); ?>" download><?php echo $filename; ?></a>
												</span>
											</p>
										</div>
									</div>

								</div>
								<?php
							}
							$i++;
						}
					} else {
						echo '<div class="attachments-message" style="margin-left:50px;">'. __('No attachments found.' , 'inbound-pro' ) .'</div>';
					}
					?>
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