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
			add_filter( 'inbound_form_custom_field' , array( __CLASS__ , 'inbound_form_custom_field' ), 10, 3 );
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
		public static function inbound_form_custom_field( $form, $field, $form_id) {
			global $attachments_loaded;
			
			/* only render field if 'attachments' type is selected. */ 
			if ( !$field || $field['type'] != 'attachments'){
				return;
			}

			/* Mark loaded */
			$attachments_loaded = true;
			
			/* get attachment settings */
			$settings = Inbound_Attachments_Settings::get_settings();
			
			$label = (isset($field['label'])) ? $field['label'] : '';
			$clean_label = preg_replace("/[^A-Za-z0-9 ]/", '', trim($label));
			$formatted_label = strtolower(str_replace(array(' ','_'),'-',$clean_label));

			$field_input_class = (isset($field['field_input_class'])) ? $field['field_input_class'] : '';
			$required = (isset($field['required'])) ? $field['required'] : '0';
			$req = ($required === '1') ? 'required' : '';
			$exclude_tracking = (isset($field['exclude_tracking'])) ? $field['exclude_tracking'] : '0';
			$et_output = ($exclude_tracking === '1') ? ' data-ignore-form-field="true"' : '';
			$req_label = ($required === '1') ? '<span class="inbound-required">*</span>' : '';
			$map_field = (isset($field['map_to'])) ? $field['map_to'] : '';
			if ($map_field != "") {
				$field_name = $map_field;
			} else {
				//$label = self::santize_inputs($label);
				$field_name = strtolower(str_replace(array(' ','_'),'-',$label));
			}
			$input_type = 'file';
			$fill_value = (isset($field['default'])) ? $field['default'] : '';
			
			$form .='<div class="inbound-attachment-container">
						<div class="row fileupload-buttonbar">
							<div class="col-lg-12">
								<!-- The fileinput-button span is used to style the file input field as button -->
								<span class="btn btn-success fileinput-button">
									<i class="glyphicon glyphicon-plus"></i>
									<span>'. __( 'Add files...' , 'inbound-pro' ) .'</span>
									<input type="'.$input_type .'" class="inbound-input '.$formatted_label . ' ' .$field_input_class.'" name="'.$field_name.'[]" id="'.$field_name.'" value="'.$fill_value.'" '.$et_output.' '.$req.' multiple/>
									<input type="hidden" name="inbound_attachment_files"  id="inbound_attachment_files" value="" />
								</span>
								<!--
								<button type="submit" class="btn btn-primary start">
									<i class="glyphicon glyphicon-upload"></i>
									<span>Start upload</span>
								</button>
								<button type="reset" class="btn btn-warning cancel">
									<i class="glyphicon glyphicon-ban-circle"></i>
									<span>Cancel upload</span>
								</button>
								<button type="button" class="btn btn-danger delete">
									<i class="glyphicon glyphicon-trash"></i>
									<span>Delete</span>
								</button>
								<!--<input type="checkbox" class="toggle">-->
								<!-- The global file processing state -->
								<span class="fileupload-process"></span>
							</div>
							<!-- The global progress state -->
							<div class="col-lg-5 fileupload-progress fade">
								<!-- The global progress bar -->
								<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
									<div class="progress-bar progress-bar-success" style="width:0%;"></div>
								</div>
								<!-- The extended global progress state -->
								<div class="progress-extended">&nbsp;</div>
							</div>
						</div>
						<!-- The table listing the files available for upload/download -->
						<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
						<br>
					</div>
					<!-- The blueimp Gallery widget -->
					<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
						<div class="slides"></div>
						<h3 class="title"></h3>
						<a class="prev">‹</a>
						<a class="next">›</a>
						<a class="close">×</a>
						<a class="play-pause"></a>
						<ol class="indicator"></ol>
					</div>
					<!-- The template to display files available for upload -->
					<script id="template-upload" type="text/x-tmpl">
					{% for (var i=0, file; file=o.files[i]; i++) { %}
						<tr class="template-upload fade">
							<td>
								<span class="preview"></span>
							</td>
							<td>
								<p class="name">{%=file.name%}</p>
								<strong class="error text-danger"></strong>
							</td>
							<td>
								<p class="size">Processing...</p>
								<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
							</td>
							<td>
								{% if (!i && !o.options.autoUpload) { %}
									<button class="btn btn-primary start" disabled>
										<i class="glyphicon glyphicon-upload"></i>
										<span>Start</span>
									</button>
								{% } %}
								{% if (!i) { %}
									<button class="btn btn-warning cancel">
										<i class="glyphicon glyphicon-ban-circle"></i>
										<span>Cancel</span>
									</button>
								{% } %}
							</td>
						</tr>
					{% } %}
					</script>
					<!-- The template to display files available for download -->
					<script id="template-download" type="text/x-tmpl">
					{% for (var i=0, file; file=o.files[i]; i++) { %}
						<tr class="template-download fade">
							<td>
								<span class="preview">
									{% if (file.thumbnailUrl) { %}
										<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
									{% } %}
								</span>
							</td>ssss
							<td>
								<p class="name">									
									<span>{%=file.name%}</span>
								</p>
								{% if (file.error) { %}
									<div><span class="label label-danger">'.__( 'Error' , 'inbound-pro' ) .'</span> {%=file.error%}</div>
								{% } %}
							</td>
							<td>
								<span class="size">{%=o.formatFileSize(file.size)%}</span>
							</td>
							<td>
								{% if (file.deleteUrl) { %}
									<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields="{"withCredentials":true}"{% } %}>
										<i class="glyphicon glyphicon-trash"></i>
										<span>'.__( 'Delete' , 'inbound-pro' ).'</span>
									</button>
									<!--<input type="checkbox" name="delete" value="1" class="toggle">-->
								{% } else { %}
									<button class="btn btn-warning cancel">
										<i class="glyphicon glyphicon-ban-circle"></i>
										<span>'.__( 'Cancel' , 'inbound-pro' ) .'</span>
									</button>
								{% } %}
							</td>
						</tr>
					{% } %}
					</script>
					<script type="text/javascript">jQuery(function ($) {
					   "use strict";
						jQuery("#'.$form_id.'").attr("enctype", "multipart/form-data");
						jQuery("#'.$form_id.'").fileupload({
							url: inbound_attachment_file_upload,
							acceptFileTypes: '.$settings['filetypes_regex'].',
							maxFileSize: '.$settings['max_size_limit'].',
							autoUpload:true
						});
						
						jQuery("#'.$form_id.'").bind("fileuploaddone", function (e, data) {
							$.each(data.result.files, function (index, file) {
								var hidden_val = jQuery( "#inbound_attachment_files" ).val();
								hidden_val += file.name + "|" ;
								jQuery( "#inbound_attachment_files" ).val(hidden_val);
							});
						});
					});
					</script>';
			
			
			return $form;
		}
	}
	

	new Inbound_Attachments_Fields;
}