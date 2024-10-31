//(function() {

	if(typeof(adamlabsgallery_lang) == 'undefined'){
		adamlabsgallery_lang = {};
		
		adamlabsgallery_lang.adamlabsgallery_shortcode_creator = 'Portfolio Gallery Shortcode Creator';
		adamlabsgallery_lang.shortcode_generator = 'Shortcode Generator';
		adamlabsgallery_lang.please_add_at_least_one_layer = 'Please add at least one Layer.';
		adamlabsgallery_lang.choose_image = 'Choose Image';
		
		adamlabsgallery_lang.shortcode_parsing_successfull = 'Shortcode parsing successfull. Items can be found in step 3';
		adamlabsgallery_lang.shortcode_could_not_be_correctly_parsed = 'Shortcode could not be parsed.';
	}

	if(typeof(tinymce) !== 'undefined'){

		tinymce.PluginManager.add('adamlabsgallery_sc_button', function( editor, url ) {
			editor.addButton('adamlabsgallery_sc_button', {
				title: adamlabsgallery_lang.adamlabsgallery_shortcode_creator,
				icon: 'icon dashicons-screenoptions',
				onclick: function() {
					
					//reset all options and settings
					adamlabsgallery_tiny_reset_all();
					jQuery('#adamlabsgallery-create-wp-gallery').show();
					jQuery('#adamlabsgallery-tiny-dialog-step-1').show();
					jQuery('#adamlabsgallery-tiny-dialog-step-2').hide();
					jQuery('#adamlabsgallery-tiny-dialog-step-3').hide();
					
					// 2.1.5 wpDialog not playing nicely with tinymce anymore
					// supressing warning message for now
					var console = window.console;
					window.console = function() {};
					try {
						editor.windowManager.open({
							id       : 'adamlabsgallery-tiny-mce-dialog',
							title	 : adamlabsgallery_lang.shortcode_generator,
							width    : 720,
							height   : 'auto',
							wpDialog : true
						},
						{
							plugin_url : url // Plugin absolute URL
						});
					}
					catch(e){}
					window.console = console;
				}
			});
			
			open_editor = editor;
			
		});
	}
	
	var adamlabsgallery_is_vc = false;
	var open_editor = false;
	var cur_vc_obj = false;
	
	/**
	 * Reset everything do defaults
	 **/
	function adamlabsgallery_tiny_reset_all(){
		adamlabsgallery_is_vc = false;
		
		jQuery('#adamlabsgallery-tiny-mce-settings-form').trigger('reset');
		jQuery('#adamlabsgallery-tiny-grid-settings-wrap').removeClass('notselectable');
		jQuery('#adamlabsgallery-custom-elements-wrap').html(''); //remove all custom build elements
	}
	
	/**
	 * Generate shortcode and add it to content
	 **/
	jQuery('body').on('click', '#adamlabsgallery-add-custom-shortcode, #adamlabsgallery-add-custom-shortcode-special', function(){
		var form = jQuery(this).parents('form');
		if ( ! validateForm( form ) )
			return false;
		
		var adamlabsgallery_params = {};
		
		//remove content from all input fields that are currently hidden
		jQuery('.adamlabsgallery-tiny-elset-row input').each(function(){
			if(jQuery(this).parents(':hidden').length!=0)
				jQuery(this).val('');
		});
		
		//collect all required data and store in content varialble
		
		var content = '[adamlabsgallery ';
		var settings_raw = AdminEssentials.getFormParams('adamlabsgallery-tiny-mce-settings-form');
		var layers_raw = AdminEssentials.getFormParams('adamlabsgallery-tiny-mce-layers-form');
		var settings = {};
		var layers = {};
		
		if(jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option:selected').val() !== '-1'){
			content += ' alias="'+jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option:selected').val()+'"';
			if(adamlabsgallery_is_vc)
				adamlabsgallery_params.alias = jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option:selected').val();
				
			if(typeof(settings_raw['adamlabsgallery-tiny-max-entries']) !== 'undefined' && adamlabsgallery_create_by_predefined !== false){
				settings['max-entries'] = settings_raw['adamlabsgallery-tiny-max-entries'];
				
				settings = JSON.stringify(settings).replace(/\[/g, '({').replace(/\]/g, '})').replace(/\'/g, '');
				content += " settings='"+settings+"'";
				if(adamlabsgallery_is_vc)
					adamlabsgallery_params.settings = settings.replace(/\"/g, "'");
			}
		}else{
		
			for(var key in settings_raw){
				if(key == 'adamlabsgallery-existing-grid') continue;
				if(key == 'adamlabsgallery-tiny-existing-settings') continue;
				if(key == 'adamlabsgallery-shortcode-analyzer') continue;
				if(adamlabsgallery_create_by_predefined == false && key == 'adamlabsgallery-tiny-max-entries') continue; //only take this setting if we are special like popular, recent or related
				
				var new_key = key.replace('adamlabsgallery-tiny-', '');
				settings[new_key] = settings_raw[key];
				
			}
			
			settings = JSON.stringify(settings).replace(/\[/g, '({').replace(/\]/g, '})').replace(/\'/g, '');
			content += " settings='"+settings+"'";
			if(adamlabsgallery_is_vc)
				adamlabsgallery_params.settings = settings.replace(/\"/g, "'");
		}
		
		if(jQuery(this).attr('id') == 'adamlabsgallery-add-custom-shortcode'){
			//remove cobbles settings for layers if type is not cobbles
			if(jQuery('select[name="adamlabsgallery-tiny-grid-layout"] option:selected').val() !== 'cobbles'){
				delete(layers_raw['adamlabsgallery-tiny-cobbles-size']);
			}
			
			for(var key in layers_raw){
				
				var new_key = key.replace('adamlabsgallery-tiny-', '');
				var ignore_setting = false;
				
				if(layers_raw[key] instanceof Array){
					var objSet = {};
					
					ignore_setting = true;
					for(var mkey in layers_raw[key]){
						if(layers_raw[key][mkey] !== ''){
							ignore_setting = false;
							objSet['0'+mkey] = layers_raw[key][mkey];
						}
					}
					layers_raw[key] = objSet;
					
				}
				
				if(ignore_setting == false)
					layers[new_key] = layers_raw[key];
				
			}
		
			if(jQuery.isEmptyObject(layers)){
				alert(adamlabsgallery_lang.please_add_at_least_one_layer);
				return false;
			}
			
			layers = JSON.stringify(layers).replace(/\[/g, '({').replace(/\]/g, '})').replace(/\'/g, '');
			
			content += " layers='"+layers+"'";
			if(adamlabsgallery_is_vc)
				adamlabsgallery_params.layers = layers.replace(/\"/g, "'");
				
		}else{ //add special stuff here
			content += " special='"+adamlabsgallery_create_by_predefined+"'";
			if(adamlabsgallery_is_vc)
				adamlabsgallery_params.special = adamlabsgallery_create_by_predefined;
		}
		
		content += '][/adamlabsgallery]';

		
		if(!adamlabsgallery_is_vc){
			
			tinyMCE.activeEditor.selection.setContent(content);
			if(open_editor !== false){
				open_editor.windowManager.close();
			}
			
		}else{

			jQuery('#adamlabsgallery-tiny-mce-dialog').dialog('close');
			
			cur_vc_obj.model.save('params', adamlabsgallery_params);
		}
		return false;
	});
	
	
	/**
	 * Add shortcode with predefined grid to content
	 **/
	if(!jQuery('#adamlabsgallery-add-predefined-grid').hasClass('adamlabsgallery-clicklistener')){
		jQuery('#adamlabsgallery-add-predefined-grid').addClass('adamlabsgallery-clicklistener');
		
		jQuery('body').on('click', '#adamlabsgallery-add-predefined-grid', function(){
			var form = jQuery(this).parents('form');
			if ( ! validateForm( form ) )
				return false;
			
			var grid_handle = jQuery('select[name="adamlabsgallery-existing-grid"] option:selected').val();
			if(grid_handle !== '-1'){
			
				var content = '[adamlabsgallery alias="'+grid_handle+'"][/adamlabsgallery]';
				if(!adamlabsgallery_is_vc){
				
					tinyMCE.activeEditor.selection.setContent( content );
					if(open_editor !== false)
						open_editor.windowManager.close();
						
				}else{
					
					cur_vc_obj.model.save('params', {'alias':grid_handle});
					
					jQuery('#adamlabsgallery-tiny-mce-dialog').dialog('close');
					
				}
			}
			
			return false;
		});
	
	
		/**
		 * Add custom element and insert all skin fields to it
		 **/
		jQuery('body').on('click', '.adamlabsgallery-add-custom-element', function(){
		
			var cur_type = jQuery(this).data('type');
			
			adamlabsgallery_tiny_add_element(cur_type);
			
		});
	
	}
	/**
	 * Add a new Element
	 */
	function adamlabsgallery_tiny_add_element(cur_type, add_options){
		var new_layer = jQuery('.adamlabsgallery-tiny-template-wrap').clone();
		
		jQuery('#adamlabsgallery-custom-elements-wrap').prepend(new_layer);
		jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap #adamlabsgallery-tiny-custom-'+cur_type+'-wrap').show();
		jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap #adamlabsgallery-tiny-custom-'+cur_type+'-wrap').append('<input type="hidden" name="adamlabsgallery-tiny-custom-type[]" value="'+cur_type+'" />')
		
		switch(cur_type){
			case 'html5':
			case 'vimeo':
			case 'youtube':
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap #adamlabsgallery-tiny-custom-poster-wrap').show();
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap #adamlabsgallery-tiny-custom-ratio-wrap').show();
			break;
			default:
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap #adamlabsgallery-tiny-custom-ratio-wrap').remove();
		}
		
		switch(cur_type) {
			case "html5":
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap').find('.dashicons.dashicons-format-image').removeClass("dashicons-format-image").addClass("dashicons-editor-video");
			break;
			case "vimeo":
			case "youtube":				
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap').find('.dashicons.dashicons-format-image').removeClass("dashicons-format-image").addClass("dashicons-format-video");
			break;
			case "soundcloud":
				jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap').find('.dashicons.dashicons-format-image').removeClass("dashicons-format-image").addClass("dashicons-format-audio");
			break;

		}
		
		if(typeof(add_options) !== 'undefined'){
			for(var key in add_options){
				if(jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap select[name="adamlabsgallery-tiny-'+key+'[]"]').length){
					jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap select[name="adamlabsgallery-tiny-'+key+'[]"] option:first-child').attr('selected', true); //set first element first.
					jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap select[name="adamlabsgallery-tiny-'+key+'[]"] option').each(function(){
						if(jQuery(this).val() == add_options[key]){
							jQuery(this).attr('selected', true);
						}
					});
				}else if(jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap input[name="adamlabsgallery-tiny-'+key+'[]"]').length){
					var my_field = jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap input[name="adamlabsgallery-tiny-'+key+'[]"]')
					if(my_field.attr('type') != 'radio'){
						my_field.val(add_options[key]);
						
						if(my_field.data('type') == 'image'){
							//ajax request
							AdminEssentials.ajaxRequest("get_image_url", {imageid: add_options[key]}, '',function(response){
								if(typeof(response.url) !== 'undefined'){
									jQuery('#adamlabsgallery-custom-elements-wrap input[name="adamlabsgallery-tiny-custom-poster[]"][value="'+response.imageid+'"]').parent().find('img').attr('src', response.url).show();
									jQuery('#adamlabsgallery-custom-elements-wrap input[name="adamlabsgallery-tiny-custom-image[]"][value="'+response.imageid+'"]').parent().find('img').attr('src', response.url).show();
								}
							});
						}
						
					}else if(my_field.attr('type') == 'radio'){
						jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap input[name="adamlabsgallery-tiny-'+key+'[]"][value="'+add_options[key]+'"]').attr('checked', 'checked');
					}
					
				}else if(jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap textarea[name="adamlabsgallery-tiny-'+key+'[]"]').length){
					jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap textarea[name="adamlabsgallery-tiny-'+key+'[]"]').val(add_options[key]);
				}
			}
		}
		
		jQuery('#adamlabsgallery-custom-elements-wrap .adamlabsgallery-tiny-template-wrap').show().removeClass('adamlabsgallery-tiny-template-wrap');
	}
	
	
	/**
	 * Delete custom element
	 **/
	jQuery('body').on('click', '.adamlabsgallery-tiny-delete-entry', function(e){
		jQuery(this).closest('.adamlabsgallery-tiny-element').remove();
	});
	
	
	/**
	 * Go to step 2
	 **/
	var adamlabsgallery_create_by_predefined = false;
	
	jQuery('body').on('click', '#adamlabsgallery-goto-step-2', function(){
		jQuery('#adamlabsgallery-tiny-dialog-step-1').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2-5').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2').show();
		jQuery('#adamlabsgallery-tiny-dialog-step-3').hide();
		if(adamlabsgallery_create_by_predefined == false){
			jQuery('.adamlabsgallery-max-entries').hide();
			jQuery('#adamlabsgallery-tiny-shortcode-analyze-wrap').show();
			jQuery('#adamlabsgallery-goto-step-3').show();
			jQuery('#adamlabsgallery-add-custom-shortcode-special').hide();
		}else{
			jQuery('.adamlabsgallery-max-entries').show();
			jQuery('#adamlabsgallery-tiny-shortcode-analyze-wrap').hide();
			jQuery('#adamlabsgallery-goto-step-3').hide();
			jQuery('#adamlabsgallery-add-custom-shortcode-special').show();
		}
	});
	
	jQuery('body').on('click', '#adamlabsgallery-create-custom-grid', function(){
		adamlabsgallery_create_by_predefined = false;
		jQuery('#adamlabsgallery-goto-step-2').click();
	});
	
	jQuery('body').on('click', '#adamlabsgallery-edit-custom-grid', function(){
		adamlabsgallery_create_by_predefined = false;
		jQuery('#adamlabsgallery-tiny-dialog-step-1').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2-5').show();
		jQuery('#adamlabsgallery-tiny-dialog-step-2').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-3').hide();
	});
	
	jQuery('body').on('click', '#adamlabsgallery-create-popularpost-grid', function(){
		adamlabsgallery_create_by_predefined = 'popular';
		jQuery('#adamlabsgallery-goto-step-2').click();
	});
	
	jQuery('body').on('click', '#adamlabsgallery-create-recentpost-grid', function(){
		adamlabsgallery_create_by_predefined = 'recent';
		jQuery('#adamlabsgallery-goto-step-2').click();
	});
	
	jQuery('body').on('click', '#adamlabsgallery-create-relatedpost-grid', function(){
		adamlabsgallery_create_by_predefined = 'related';
		console.log("jo");
		jQuery('#adamlabsgallery-goto-step-2').click();
	});
	
	
	/**
	 * Go to step 1
	 **/
	jQuery('body').on('click', '#adamlabsgallery-goto-step-1, #adamlabsgallery-goto-step-1-5', function(){
		jQuery('#adamlabsgallery-tiny-dialog-step-1').show();
		jQuery('#adamlabsgallery-tiny-dialog-step-2').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2-5').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-3').hide();
	});
	
	
	/**
	 * Go to step 3
	 **/
	jQuery('body').on('click', '#adamlabsgallery-goto-step-3', function(){
		jQuery('#adamlabsgallery-tiny-dialog-step-1').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-2-5').hide();
		jQuery('#adamlabsgallery-tiny-dialog-step-3').show();
	});
	
	
	jQuery('body').on('click', '.adamlabsgallery-select-image', function(){
		var setto = jQuery(this).data('setto');
		var my_element = jQuery(this);
		
		var img_uploader;

		if (img_uploader) {
			img_uploader.open();
			return;
		}

		//Extend the wp.media object
		img_uploader = wp.media.frames.file_frame = wp.media({
			title: adamlabsgallery_lang.choose_image,
			button: {
				text: adamlabsgallery_lang.choose_image
			},
			multiple: false
		});

		//When a file is selected, grab the URL and set it as the text field's value
		img_uploader.on('select', function() {
			attachment = img_uploader.state().get('selection').first().toJSON();
			
			my_element.siblings('input[name="'+setto+'"]').val(attachment.id);
			my_element.siblings('.adamlabsgallery-tiny-img-placeholder').children('img').attr('src', attachment.url).show();
			//img_uploader.close();
		});

		//Open the uploader dialog
		img_uploader.open();
	});
	
	jQuery( document ).ready(function() {
		jQuery('#adamlabsgallery-custom-elements-wrap').sortable({
			containment: '#adamlabsgallery-custom-elements-wrap'
		});

		//Gutenberg addition 2.3.1
		if( jQuery('body').hasClass('gutenberg-editor-page') || jQuery('body').hasClass('block-editor-page') || jQuery('body').hasClass('wp-editor') ){
			jQuery('body').on('change', 'select[name="adamlabsgallery-existing-grid"]', function(){
				$selected = jQuery('select[name="adamlabsgallery-existing-grid"] option:selected');
				selected_val = $selected.val();
				selected_val = '[adamlabsgallery alias="'+selected_val+'"][/adamlabsgallery]';
				selected_title = $selected.text();
				grid_slug = jQuery('.grid_slug');
				grid_slug.val(selected_val);
				window.adamlabsgallery_react.state.text = selected_val;
				window.adamlabsgallery_react.props.attributes.text = selected_val;
				window.adamlabsgallery_react.state.gridTitle = selected_title;
				window.adamlabsgallery_react.props.attributes.gridTitle = selected_title;
				window.adamlabsgallery_react.forceUpdate();
				jQuery('#adamlabsgallery-tiny-mce-dialog').dialog('close');
			});
		}
	});
	
	jQuery('body').on('change', 'select[name="adamlabsgallery-tiny-entry-skin"]', function(){
		var choosen_skin = jQuery(this).val();
		
		jQuery('.adamlabsgallery-tiny-elset-row').hide(); //hide all fields
		
		if(typeof(adamlabsgallery_tiny_skin_layers[choosen_skin]) !== 'undefined'){
			for(var key in adamlabsgallery_tiny_skin_layers[choosen_skin]){
				jQuery('.adamlabsgallery-tiny-'+key+'-wrap').show();
			}
		}
		
	});
	jQuery('select[name="adamlabsgallery-tiny-entry-skin"]').change();
	
	jQuery('body').on('change', 'select[name="adamlabsgallery-tiny-grid-layout"]', function(){
		var choosen_layout = jQuery(this).val();
		
		if(choosen_layout == 'cobbles'){
			jQuery('.adamlabsgallery-tiny-cobbles-size-wrap').show();
		}else{
			jQuery('.adamlabsgallery-tiny-cobbles-size-wrap').hide();
		}
		
	});
	jQuery('select[name="adamlabsgallery-tiny-grid-layout"]').change();
	
	jQuery('body').on('change', 'select[name="adamlabsgallery-tiny-existing-settings"]', function(){
		var choosen_grid = jQuery(this).val();
		
		if(choosen_grid != '-1'){
			jQuery('#adamlabsgallery-tiny-grid-settings-wrap').addClass('notselectable');
			jQuery('#adamlabsgallery-tiny-grid-settings-wrap').find('input, select, textarea').attr('disabled', 'disabled');
		}else{
			jQuery('#adamlabsgallery-tiny-grid-settings-wrap').removeClass('notselectable');
			jQuery('#adamlabsgallery-tiny-grid-settings-wrap').find('input, select, textarea').attr('disabled', false);
		}
		
	});
	jQuery('select[name="adamlabsgallery-tiny-existing-settings"]').change();
	
	
	/**
	 * Shortcode parser
	 **/
	jQuery('body').on('click', '#adamlabsgallery-shortcode-do-analyze', function(){
		var sc = jQuery('input[name="adamlabsgallery-shortcode-analyzer"]').val();
		
		try{
			var msc = wp.shortcode.next('adamlabsgallery', sc);
			
			if(typeof(msc) !== 'undefined'){
				if(adamlabsgallery_is_vc){
					adamlabsgallery_tiny_reset_all(); //reset all
					adamlabsgallery_is_vc = true;
				}else{
					adamlabsgallery_tiny_reset_all(); //reset all
				}
				
				if(typeof(msc.shortcode.attrs.named.alias) !== 'undefined'){ //either an alias is set
					
					jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option').each(function(){
						if(jQuery(this).val() == msc.shortcode.attrs.named.alias){
							jQuery(this).attr('selected', true);
							jQuery('select[name="adamlabsgallery-tiny-existing-settings"]').change();
						}
					});
					
				}else if(typeof(msc.shortcode.attrs.named.settings) !== 'undefined'){ //or we take the settings if they exist
					jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option:first-child').attr('selected', true); //set first element first.
					jQuery('select[name="adamlabsgallery-tiny-existing-settings"]').change();
					
					var settings = jQuery.parseJSON(msc.shortcode.attrs.named.settings);
					
					for(var key in settings){
						if(jQuery('select[name="adamlabsgallery-tiny-'+key+'"]').length){
							jQuery('select[name="adamlabsgallery-tiny-'+key+'"] option:first-child').attr('selected', true); //set first element first.
							jQuery('select[name="adamlabsgallery-tiny-'+key+'"] option').each(function(){
								if(jQuery(this).val() == settings[key]){
									jQuery(this).attr('selected', true);
								}
							});
						}else if(jQuery('input[name="adamlabsgallery-tiny-'+key+'"]').length){
							
							if(jQuery('input[name="adamlabsgallery-tiny-'+key+'"]').attr('type') == 'text')
								jQuery('input[name="adamlabsgallery-tiny-'+key+'"]').val(settings[key]);
							else if(jQuery('input[name="adamlabsgallery-tiny-'+key+'"]').attr('type') == 'radio')
								jQuery('input[name="adamlabsgallery-tiny-'+key+'"][value="'+settings[key]+'"]').attr('checked', 'checked');
							
						}else if(jQuery('textarea[name="adamlabsgallery-tiny-'+key+'"]').length){
							jQuery('textarea[name="adamlabsgallery-tiny-'+key+'"]').val(settings[key]);
						}
					}
				}
				
				if(typeof(msc.shortcode.attrs.named.layers) !== 'undefined'){ //get the layers
					var layers = jQuery.parseJSON(msc.shortcode.attrs.named.layers);
					
					var new_layer = {};
					//translate layers into object that we can use easy
					for(var key in layers){
						if(!jQuery.isEmptyObject(layers[key])){
							for(var lkey in layers[key]){
								if(typeof(new_layer[lkey]) == 'undefined') new_layer[lkey] = {};
								
								new_layer[lkey][key] = layers[key][lkey];
							}
						}
					}
					
					//order new_layer so DESC, so that we start with the last entry (because the function prepends elements)
					var keys = [];
					var sorted_obj = {};

					for(var key in new_layer){
						if(new_layer.hasOwnProperty(key)){
							keys.push(key);
						}
					}

					// sort keys
					keys.sort();
					keys.reverse(); //reserve order

					// create new array based on Sorted Keys
					jQuery.each(keys, function(i, key){
						sorted_obj[key] = new_layer[key];
					});
					
					for(var key in sorted_obj){
						var cur_type = '';
						if(typeof(sorted_obj[key]['custom-image']) !== 'undefined'){ //add image
							cur_type = 'image';
						}else if(typeof(sorted_obj[key]['custom-youtube']) !== 'undefined'){ //add youtube
							cur_type = 'youtube';
						}else if(typeof(sorted_obj[key]['custom-vimeo']) !== 'undefined'){ //add vimeo
							cur_type = 'vimeo';
						}else if(typeof(sorted_obj[key]['custom-soundcloud']) !== 'undefined'){ //add soundcloud
							cur_type = 'soundcloud';
						}else if(typeof(sorted_obj[key]['custom-html5-mp4']) !== 'undefined' || typeof(sorted_obj[key]['custom-html5-ogv']) !== 'undefined' || typeof(sorted_obj[key]['custom-html5-webm']) !== 'undefined'){ //add html5 video
							cur_type = 'html5';
						}else{ //nothing correct found
							if(typeof(sorted_obj[key]['custom-type']) !== 'undefined'){ //maybe the input type field is set
								cur_type = sorted_obj[key]['custom-type'];
								switch(cur_type){
									case 'image':
									case 'youtube':
									case 'vimeo':
									case 'soundcloud':
									case 'html':
										break;
									default: 
										continue;
								}
							}else{
								continue;
							}
						}
						adamlabsgallery_tiny_add_element(cur_type, sorted_obj[key]);
					}
					
				}
				
				jQuery('select[name="adamlabsgallery-tiny-entry-skin"]').change();
				
				if(!adamlabsgallery_is_vc)
					alert(adamlabsgallery_lang.shortcode_parsing_successfull);
				
				jQuery('#adamlabsgallery-goto-step-2').click();
				
			}else{
				alert(adamlabsgallery_lang.shortcode_could_not_be_correctly_parsed);
			}
			
		}catch(e){
			alert(adamlabsgallery_lang.shortcode_could_not_be_correctly_parsed);
		}
		
	});
//})();