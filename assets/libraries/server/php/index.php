<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
 
add_action('wp_ajax_inbound_attachment_file_upload', 'inbound_attachment_file_upload');
add_action('wp_ajax_nopriv_inbound_attachment_file_upload', 'inbound_attachment_file_upload');

function inbound_attachment_file_upload(){
	require('UploadHandler.php');
	$upload_handler = new UploadHandler();
	die();
}