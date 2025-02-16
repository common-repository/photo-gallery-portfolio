/************************************************************************************
 * jquery.adamlabs.adamlabsgallery.js - jQuery Plugin for adamlabsgallery Portfolio Slider
 * @version: 2.2.5 (12.07.2018)
 * @requires jQuery v1.7 or later
 * @author AdamLabs
************************************************************************************/

var GridEditorEssentials = new function(){
	var t = this;

	var selected_layer = null;
	var init_layers = {};
	var init_elements = {};
	var layers = [];
	var all_attributes = {};
	var essapi;
	var adamlabsgallery_codemirror_global_css = null;
	var save_needed = false;
	var arr_font_types = [];
	var arr_init_font_types = [];
	var arr_meta_keys = [];

	t.initDraggable = function() {
		jQuery( ".adamlabsgallery-draggable" ).draggable({
			handle:".dragme"});
	}

	/**
	 * set init layers object (from db from skin item)
	 */
	t.setInitLayersJson = function(json_layers){
		
		init_layers = jQuery.parseJSON(json_layers);

		for(var key in init_layers) {
			
			// 2.2.6
			// line break detected
			if(typeof init_layers[key] === 'string') continue;
			
			if(typeof init_layers[key]['settings'] == 'undefined') init_layers[key]['settings'] = {};
			
			// 2.2.6
			var settings = init_layers[key]['settings'];
			if(!settings.hasOwnProperty('min-height')) init_layers[key]['settings']['min-height'] = 0;
			if(!settings.hasOwnProperty('max-height')) init_layers[key]['settings']['max-height'] = 'none';
			if(!settings.hasOwnProperty('duration')) init_layers[key]['settings']['duration'] = 'default';
			if(!settings.hasOwnProperty('letter-spacing')) init_layers[key]['settings']['letter-spacing'] = 'normal';
			if(!settings.hasOwnProperty('letter-spacing-hover')) init_layers[key]['settings']['letter-spacing-hover'] = 'normal';
			
		}
		
	}

	/**
	 * set init elements object (from db from predefined elements)
	 */
	t.setInitElementsJson = function(json_elements){
		
		init_elements = jQuery.parseJSON(json_elements);
		
		for(var key in init_elements){ //strip slashes from element settings
			if(typeof init_elements[key]['settings']['font-family'] !== 'undefined')
				init_elements[key]['settings']['font-family'] = AdminEssentials.stripslashes(init_elements[key]['settings']['font-family']);
		}
	}

	/**
	 * set init font
	 */
	t.setInitFontsJson = function(fonts_elements){
		arr_init_font_types = jQuery.parseJSON(fonts_elements);
	}

	/**
	 * set init elements object (from db from predefined elements)
	 */
	t.refreshInitElements = function(obj_elements){
		
		init_elements = obj_elements;
		for(var key in init_elements){ //strip slashes from element settings
			if(typeof init_elements[key]['settings']['font-family'] !== 'undefined')
				init_elements[key]['settings']['font-family'] = AdminEssentials.stripslashes(init_elements[key]['settings']['font-family']);
		}
	}

	/**
	 * set init meta keys
	 */
	t.setInitMetaKeysJson = function(json_meta){
		arr_meta_keys = jQuery.parseJSON(json_meta);
		// console.log(arr_meta_keys);
	}

	/**
	 * set init style elements object
	 */
	t.setInitAllAttributesJson = function(json_elements){
		
		all_attributes = jQuery.parseJSON(json_elements);
	}

	t.update_layers = function(){ //get all layers
	
		if(selected_layer == null) return(false);

		//check if it has an ID
		var layer_id = selected_layer.data('id');
		if(layer_id != undefined) //update layer sorting
			t.update_layer_sorting();
		else //create new layer
			t.add_layer();

		t.redraw_container_height();

	}


	/**
	 * add new layer and set default options
	 */
	t.add_layer = function(){
		
		var start = +new Date();

		if(selected_layer == null) return(false);

		var next_id = t.get_latest_id() + 1;
		jQuery('input[name="adamlabsgallery-item-skin-element-last-id"]').val(next_id);

		selected_layer.data('id', next_id);
		selected_layer.attr('data-id', next_id);


		var new_layer = new Object;
		var default_handle_name = selected_layer.data('handle');
		new_layer.id = next_id.toString();
		new_layer.handle = selected_layer.data('handle');
		new_layer.settings = jQuery.extend({},init_elements[selected_layer.data('handle')]['settings']);

		layers.push(new_layer);

		t.update_layer_sorting();

		t.set_default_values();


		t.select_layer(next_id.toString());
		t.setting_has_changed();

		//t.select_layer(next_id.toString()); //select again for right source and other settings

		t.propagate_element_selector();

		//change the handle after setting the informations to a new handle
		layers[layers.length-1].handle = adamlabsgallery_lang.new_element_sanitize+'-'+next_id;

		selected_layer.data('handle', adamlabsgallery_lang.new_element_sanitize+'-'+next_id);
		selected_layer.attr('data-handle',selected_layer.data('handle'));

		selected_layer.data('orighandle', default_handle_name);
		selected_layer.attr('orighandle', default_handle_name);
	}


	/**
	 * update layer sorting by editor sorting
	 */
	t.update_layer_sorting = function(){
		var stl = jQuery('#skin-dz-tl').sortable('toArray', {attribute: 'data-id'});
		var sbr = jQuery('#skin-dz-br').sortable('toArray', {attribute: 'data-id'});
		var sc = jQuery('#skin-dz-c').sortable('toArray', {attribute: 'data-id'});
		var sm = jQuery('#skin-dz-m').sortable('toArray', {attribute: 'data-id'});

		t.update_sorting(stl, 'tl');
		t.update_sorting(sbr, 'br');
		t.update_sorting(sc, 'c');
		t.update_sorting(sm, 'm');
	}


	/**
	 * update order and container of elements
	 */
	t.update_sorting = function(sort_array, cnt){
		var sort = 0;
		for(var i in sort_array){
			for(var key in layers){
				if(sort_array[i] == layers[key].id){
					layers[key].order = sort;
					layers[key].container = cnt;
					sort++;
					break;
				}
			}
		}
	}


	/**
	 * set default values of item
	 */
	t.set_default_values = function(id){
		
		for(var handle in all_attributes){
			switch(all_attributes[handle]['type']){
				case 'colorpicker':
					
					/* 2.1.6 */
					if(typeof RevColor === 'undefined') {
						jQuery('#element-'+handle).closest('.wp-picker-container').find('a').first().css('background-color', all_attributes[handle]['values']['default']);
						jQuery('input[name="element-'+handle+'"]').val(all_attributes[handle]['values']['default']);	
					}
					else {
						jQuery('input[name="element-'+handle+'"]').val(all_attributes[handle]['values']['default']).AdamLabsColorPicker();
					}
					
				break;
				case 'select':
					jQuery('select[name="element-'+handle+'"] option[value="'+all_attributes[handle]['values']['default']+'"]').attr("selected","selected");

					//special cases
					if(handle == 'source'){
						jQuery('select[name="element-source-post"] option[value="title"]').attr("selected","selected");
						jQuery('select[name="element-source-event"] option[value="event_start_date"]').attr("selected","selected");
						jQuery('select[name="element-source-woocommerce"] option[value="wc_regular_price_from"]').attr("selected","selected");
						jQuery('input[name="element-source-icon"]').val('');
						jQuery('#adamlabsgallery-preview-icon').html('');
						jQuery('input[name="element-source-text-style-disable"]').attr('checked', false);
						jQuery('textarea[name="element-source-text"]').val('');
						jQuery('input[name="element-source-meta"]').val('');

						jQuery('select[name="element-source-taxonomy"] option[value="post_tag"]').attr("selected","selected");

						jQuery('select[name="element-source-event"]').hide().siblings('.select_fake').hide();
						jQuery('select[name="element-source-woocommerce"]').hide().siblings('.select_fake').hide();
						jQuery('#adamlabsgallery-source-icon-wrap').hide();
						jQuery('#adamlabsgallery-source-text-style-disable-wrap').hide();
						jQuery('#adamlabsgallery-source-text-wrap').hide();
						jQuery('#adamlabsgallery-source-limit-wrap').hide();
						jQuery('.adamlabsgallery-cat-tag-settings').hide();
						jQuery('#adamlabsgallery-source-functonality-wrap').hide().find('.select_fake').hide();
						jQuery('#adamlabsgallery-source-meta-wrap').hide();

					}else if(handle == 'link-type'){
						jQuery('input[name="element-url-link"]').val('');
						jQuery('input[name="element-javascript-link"]').val('');
						jQuery('#adamlabsgallery-element-post-url-wrap').hide();
						jQuery('#adamlabsgallery-element-post-meta-wrap').hide();
						jQuery('#adamlabsgallery-element-post-javascript-wrap').hide();
					}else if(handle == 'background-size'){
						jQuery('input[name="element-background-size-x"]').val('100');
						jQuery('input[name="element-background-size-y"]').val('100');
						jQuery('#background-size-percent-wrap').css('display', 'none');
					}else if(handle == 'align'){
						jQuery('select[name="element-absolute-unit"] option[value="px"]').attr("selected","selected");
						jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.top);
						jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.left);
					}
				break;
				case 'checkbox':
					jQuery('input[name="element-'+handle+'"]').attr('checked', all_attributes[handle]['values']['default']);
				break;
				case 'multi-text':
					jQuery('input[name="element-'+handle+'[]"]').each(function(){
						jQuery(this).val(all_attributes[handle]['values']['default']);
					});
				break;
				default:
					jQuery('input[name="element-'+handle+'"]').val(all_attributes[handle]['values']['default']);
				break;

			}

			//check the unit of elements
			if(jQuery('select[name="element-'+handle+'-unit"]').length > 0){
				jQuery('select[name="element-'+handle+'-unit"] option[value="px"]').attr("selected","selected");
			}
		}

		//Load values of item element if selected
		if(id !== undefined){
			if(typeof layers[id] === 'object' && layers[id]['handle'] !== 'undefined'){
				var ie_settings = layers[id]['settings'];//init_elements[layers[id]['handle']]['settings'];
				for(var handle in all_attributes){
					if(typeof ie_settings[handle] === 'undefined') continue;

					switch(all_attributes[handle]['type']){
						case 'colorpicker':
							if(handle == 'bg-alpha') continue;
							
							/* 2.1.6 */
							if(typeof RevColor === 'undefined') {
								jQuery('#element-'+handle).closest('.wp-picker-container').find('a').first().css('background-color', ie_settings[handle]);
								jQuery('input[name="element-'+handle+'"]').val(ie_settings[handle]);
							}
							else {
								jQuery('input[name="element-'+handle+'"]').val(ie_settings[handle]).AdamLabsColorPicker();
							}
							
						break;
						case 'select':
							jQuery('select[name="element-'+handle+'"] option[value="'+ie_settings[handle]+'"]').attr("selected","selected");

							//special case
							if(handle == 'source'){
								var cur_type = '';

								if(ie_settings[handle] == 'post'){
									if(typeof ie_settings['source-post'] !== 'undefined'){
										jQuery('select[name="element-source-post"] option[value="'+ie_settings['source-post']+'"]').attr("selected","selected");

										if(ie_settings['source-post'] == 'cat_list' || ie_settings['source-post'] == 'tag_list' || ie_settings['source-post'] == 'taxonomy'){
											jQuery('.adamlabsgallery-cat-tag-settings').show();
											jQuery('#adamlabsgallery-source-limit-wrap').hide();
											jQuery('#adamlabsgallery-source-functonality-wrap').show().find('.select_fake').show();
										}

										if(ie_settings['source-post'] == 'meta'){
											jQuery('#adamlabsgallery-source-meta-wrap').show();
											if(typeof ie_settings['source-meta'] !== 'undefined')
												jQuery('input[name="element-source-meta"]').val(ie_settings['source-meta']);
										}

									}
								}else if(ie_settings[handle] == 'event'){
									if(typeof ie_settings['source-event'] !== 'undefined')
									jQuery('select[name="element-source-event"] option[value="'+ie_settings['source-event']+'"]').attr("selected","selected");
								}else if(ie_settings[handle] == 'woocommerce'){
									if(typeof ie_settings['source-woocommerce'] !== 'undefined'){
										jQuery('select[name="element-source-woocommerce"] option[value="'+ie_settings['source-woocommerce']+'"]').attr("selected","selected");

										if(ie_settings['source-woocommerce'] == 'wc_categories'){
											jQuery('.adamlabsgallery-cat-tag-settings').show();
											jQuery('#adamlabsgallery-source-limit-wrap').show();
											jQuery('#adamlabsgallery-source-functonality-wrap').show().find('.select_fake').show();
										}
									}
								}else if(ie_settings[handle] == 'icon'){
									if(typeof ie_settings['source-icon'] !== 'undefined'){
										jQuery('input[name="element-source-icon"]').val(ie_settings['source-icon']);
										jQuery('#adamlabsgallery-preview-icon').html('<i class="'+ie_settings['source-icon']+'"></i>');
									}
								}else if(ie_settings[handle] == 'text'){
									if(typeof ie_settings['source-text'] !== 'undefined')
										jQuery('textarea[name="element-source-text"]').val(ie_settings['source-text']);
								}

							}else if(handle == 'link-type'){
								if(typeof ie_settings['link-type-url'] !== 'undefined') jQuery('input[name="element-url-link"]').val(ie_settings['link-type-url']);
								if(typeof ie_settings['link-type-meta'] !== 'undefined') jQuery('input[name="element-meta-link"]').val(ie_settings['link-type-meta']);
								if(typeof ie_settings['link-type-javascript'] !== 'undefined') jQuery('input[name="element-javascript-link"]').val(ie_settings['link-type-javascript']);
								if(typeof ie_settings['link-type-sharefacebook'] !== 'undefined') {
									jQuery('select[name="element-facebook-sharing-link"]').val(ie_settings['link-type-sharefacebook']);
									jQuery('input[name="element-facebook-link-url"]').val(ie_settings['element-facebook-link-url']);
									//if(ie_settings['link-type-sharefacebook']=="custom") jQuery(".adamlabsgallery-element-facebook_link_custom").show();
									//else  jQuery(".adamlabsgallery-element-facebook_link_custom").hide();
								}
								if(typeof ie_settings['link-type-sharegplus'] !== 'undefined') {
									jQuery('select[name="element-gplus-sharing-link"]').val(ie_settings['link-type-sharegplus']);
									jQuery('input[name="element-gplus-link-url"]').val(ie_settings['element-gplus-link-url']);
								}
								if(typeof ie_settings['link-type-sharepinterest'] !== 'undefined') {
									jQuery('select[name="element-pinterest-sharing-link"]').val(ie_settings['link-type-sharepinterest']);
									jQuery('input[name="element-pinterest-link-url"]').val(ie_settings['element-pinterest-link-url']);
									jQuery('textarea[name="element-pinterest-description"]').val(ie_settings['link-type-sharepinterest-description']);
								}
								if(typeof ie_settings['link-type-sharetwitter'] !== 'undefined') {
									jQuery('select[name="element-twitter-sharing-link"]').val(ie_settings['link-type-sharetwitter']);
									jQuery('input[name="element-twitter-link-url"]').val(ie_settings['element-twitter-link-url']);
									jQuery('input[name="element-twitter-text-before"]').val(ie_settings['link-type-sharetwitter-text-before']);
									jQuery('input[name="element-twitter-text-after"]').val(ie_settings['link-type-sharetwitter-text-after']);
								}
							}else if(handle == 'text-align' || handle == 'float'){ //set to default depending on display setting
								if(jQuery('input[name="element-display"]').val() == 'block'){
									if(handle == 'float') jQuery('select[name="element-'+handle+'"] option[value="none"]').attr("selected","selected");
								}else{
									if(handle == 'text-align') jQuery('select[name="element-'+handle+'"] option[value="center"]').attr("selected","selected");
								}
							}else if(handle == 'background-size'){
								if(typeof ie_settings['background-size-x'] !== 'undefined') jQuery('input[name="element-background-size-x"]').val(ie_settings['background-size-x']);
								if(typeof ie_settings['background-size-y'] !== 'undefined') jQuery('input[name="element-background-size-y"]').val(ie_settings['background-size-y']);

								if(ie_settings['background-size'] == '%'){
									jQuery('#background-size-percent-wrap').css('display', 'inline-block');
								}
							}else if(handle == 'align'){
								if(typeof ie_settings['absolute-unit'] !== 'undefined') jQuery('select[name="element-absolute-unit"] option[value="'+ie_settings['absolute-unit']+'"]').attr("selected","selected");

								if(typeof ie_settings['align'] !== 'undefined'){
									switch(ie_settings['align']){
										case 't_l':
											jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.top);
											jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.left);
											break;
										case 't_r':
											jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.top);
											jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.right);
											break;
										case 'b_l':
											jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.bottom);
											jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.left);
											break;
										case 'b_r':
											jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.bottom);
											jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.right);
											break;
									}
								}
							}
						break;
						case 'checkbox':
							if(ie_settings[handle] == jQuery('input[name="element-'+handle+'"]').val()){
								jQuery('input[name="element-'+handle+'"]').attr('checked', true);
							}else{
								jQuery('input[name="element-'+handle+'"]').attr('checked', false);
							}
						break;
						case 'multi-text':
							var at_key = 0;
							jQuery('input[name="element-'+handle+'[]"]').each(function(){
								jQuery(this).val(ie_settings[handle][at_key]);
								at_key++;
							});
						break;
						default:
							jQuery('input[name="element-'+handle+'"]').val(ie_settings[handle]);
						break;

					}

					//check the unit of elements
					if(jQuery('select[name="element-'+handle+'-unit"]').length > 0){
						jQuery('select[name="element-'+handle+'-unit"] option[value="'+ie_settings[handle+'-unit']+'"]').attr("selected","selected");
					}
				}
			}
		}


		// Social Sharing
		jQuery('select[name="element-facebook-sharing-link"]').change(function(){
			if(jQuery('select[name="element-facebook-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-facebook_link_custom").show();
			else jQuery(".adamlabsgallery-element-facebook_link_custom").hide();
		});
		jQuery('select[name="element-gplus-sharing-link"]').change(function(){
			if(jQuery('select[name="element-gplus-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-gplus_link_custom").show();
			else jQuery(".adamlabsgallery-element-gplus_link_custom").hide();
		});
		jQuery('select[name="element-pinterest-sharing-link"]').change(function(){
			if(jQuery('select[name="element-pinterest-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-pinterest_link_custom").show();
			else jQuery(".adamlabsgallery-element-pinterest_link_custom").hide();
		});
		jQuery('select[name="element-twitter-sharing-link"]').change(function(){
			if(jQuery('select[name="element-twitter-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-twitter_link_custom").show();
			else jQuery(".adamlabsgallery-element-twitter_link_custom").hide();
		});

	}



	/**
	 * reset & show/hide elements based on positon:absolute or position:relative
	 */
	t.reset_style_by_position = function(){
		
		var pos = jQuery('select[name="element-position"] option:selected').val();

		if(pos == 'absolute'){
			jQuery('#adamlabsgallery-show-on-absolute').css('display', 'block');
			jQuery('#adamlabsgallery-show-on-relative').css('display', 'none');
			if(!selected_layer.hasClass('ui-draggable')){
				jQuery(selected_layer).appendTo('#skin-dz-wrapper');
				t.set_absolute_positioning();
			}
		}else{
			var layer_id = selected_layer.data('id');
			for(var key in layers){
				if(layers[key].id == layer_id){
					//if(jQuery('#skin-dz-'+layers[key].container).length){}
					if(selected_layer.hasClass('ui-draggable')){
						selected_layer.appendTo('#skin-dz-'+layers[key].container);
						selected_layer.removeClass('ui-draggable');
						selected_layer.css('top', 'auto');
						selected_layer.css('right', 'auto');
						selected_layer.css('bottom', 'auto');
						selected_layer.css('left', 'auto');

						selected_layer.draggable('destroy');

						//resort items
						t.update_layer_sorting();
					}
					break;
				}
			}
			jQuery('#adamlabsgallery-show-on-absolute').css('display', 'none');
			jQuery('#adamlabsgallery-show-on-relative').css('display', 'block');
		}
	}


	/**
	 * get setting values from input fields
	 */
	t.get_setting_values = function(){

		if(selected_layer == null) return(false);

		var has_changed = false;
		var layer_id = selected_layer.data('id');

		t.reset_style_by_position();

		for(var key in layers){
			if(layers[key].id == layer_id){
				for(var handle in all_attributes){

					var temp_val = layers[key]['settings'][handle];

					if(all_attributes[handle]['type'] == 'checkbox'){
						layers[key]['settings'][handle] = (jQuery('input[name="element-'+handle+'"]').is(':checked')) ? jQuery('input[name="element-'+handle+'"]').val() : '';
					}else if(all_attributes[handle]['type'] == 'select'){
						layers[key]['settings'][handle] = jQuery('select[name="element-'+handle+'"] option:selected').val();

						//special case
						if(handle == 'source'){

							delete(layers[key]['settings']['source-event']);
							delete(layers[key]['settings']['source-woocommerce']);
							delete(layers[key]['settings']['source-icon']);
							delete(layers[key]['settings']['source-text']);
							delete(layers[key]['settings']['source-post']);
							delete(layers[key]['settings']['source-meta']);

							switch(layers[key]['settings'][handle]){
								case 'event':
									layers[key]['settings']['source-event'] = jQuery('select[name="element-source-event"] option:selected').val();
								break;
								case 'woocommerce':
									layers[key]['settings']['source-woocommerce'] = jQuery('select[name="element-source-woocommerce"] option:selected').val();
								break;
								case 'icon':
									layers[key]['settings']['source-icon'] = jQuery('input[name="element-source-icon"]').val();
								break;
								case 'text':
									layers[key]['settings']['source-text'] = jQuery('textarea[name="element-source-text"]').val();
								break;
								case 'post':
								default:
									layers[key]['settings']['source-taxonomy'] = jQuery('select[name="element-source-taxonomy"] option:selected').val();
									layers[key]['settings']['source-post'] = jQuery('select[name="element-source-post"] option:selected').val();
									if(layers[key]['settings']['source-post'] == 'meta')
										layers[key]['settings']['source-meta'] = jQuery('input[name="element-source-meta"]').val();

							}

						}else if(handle == 'link-type'){
							delete(layers[key]['settings']['link-type-url']);
							delete(layers[key]['settings']['link-type-meta']);
							delete(layers[key]['settings']['link-type-javascript']);
							delete(layers[key]['settings']['link-type-sharefacebook']);
							delete(layers[key]['settings']['link-type-sharegplus']);
							delete(layers[key]['settings']['link-type-sharepinterest']);
							delete(layers[key]['settings']['link-type-sharetwitter']);

							switch(layers[key]['settings'][handle]){
								case 'post'://do nothing
								case 'lightbox'://do nothing
								case 'embedded_media'://do nothing
								break;
								case 'url':
									layers[key]['settings']['link-type-url'] = jQuery('input[name="element-url-link"]').val();
								break;
								case 'meta':
									layers[key]['settings']['link-type-meta'] = jQuery('input[name="element-meta-link"]').val();
								break;
								case 'javascript':
									layers[key]['settings']['link-type-javascript'] = jQuery('input[name="element-javascript-link"]').val();
									break;
								case 'sharefacebook':
									layers[key]['settings']['link-type-sharefacebook'] = jQuery('select[name="element-facebook-sharing-link"]').val();
									layers[key]['settings']['link-type-sharefacebook-custom-url'] = jQuery('input[name="element-facebook-link-url"]').val();
								break;
								case 'sharegplus':
									layers[key]['settings']['link-type-sharegplus'] = jQuery('select[name="element-gplus-sharing-link"]').val();
									layers[key]['settings']['link-type-sharegplus-custom-url'] = jQuery('input[name="element-gplus-link-url"]').val();
								break;
								case 'sharepinterest':
									layers[key]['settings']['link-type-sharepinterest'] = jQuery('select[name="element-pinterest-sharing-link"]').val();
									layers[key]['settings']['link-type-sharepinterest-custom-url'] = jQuery('input[name="element-pinterest-link-url"]').val();
									layers[key]['settings']['link-type-sharepinterest-description'] = jQuery('textarea[name="element-pinterest-description"]').val();
								break;
								case 'sharetwitter':
									layers[key]['settings']['link-type-sharetwitter'] = jQuery('select[name="element-twitter-sharing-link"]').val();
									layers[key]['settings']['link-type-sharetwitter-custom-url'] = jQuery('input[name="element-twitter-link-url"]').val();
									layers[key]['settings']['link-type-sharetwitter-text-before'] = jQuery('input[name="element-twitter-text-before"]').val();
									layers[key]['settings']['link-type-sharetwitter-text-after'] = jQuery('input[name="element-twitter-text-after"]').val();
								break;
							}

						}else if(handle == 'background-size'){
							delete(layers[key]['settings']['background-size-x']);
							delete(layers[key]['settings']['background-size-y']);

							if(layers[key]['settings'][handle] == '%'){
								layers[key]['settings']['background-size-x'] = jQuery('input[name="element-background-size-x"]').val();
								layers[key]['settings']['background-size-y'] = jQuery('input[name="element-background-size-y"]').val();
							}
						}else if(handle == 'align'){
							layers[key]['settings']['absolute-unit'] = jQuery('select[name="element-absolute-unit"] option:selected').val();
						}


					}else if(all_attributes[handle]['type'] == 'multi-text'){
						var multi_val = {};
						var multi_key = 0;
						jQuery('input[name="element-'+handle+'[]"]').each(function(){
							multi_val[multi_key] = jQuery(this).val();
							multi_key++;
						});
						layers[key]['settings'][handle] = multi_val;
					}else{
						layers[key]['settings'][handle] = jQuery('input[name="element-'+handle+'"]').val();
					}


					//check the unit of elements
					if(jQuery('select[name="element-'+handle+'-unit"]').length > 0){
						layers[key]['settings'][handle+'-unit'] = jQuery('select[name="element-'+handle+'-unit"] option:selected').val();
					}

					if(temp_val !== layers[key]['settings'][handle] && temp_val !== undefined){
						if(all_attributes[handle]['type'] == 'multi-text'){
							var mstring = '';
							var mtstring = '';
							var mfirst = true;
							var mtfirst = true;
							for(var mkey in layers[key]['settings'][handle]){
								if(!mfirst) mstring+=',';
								mstring+=layers[key]['settings'][handle][mkey];
								mfirst = false;
							}

							for(var mkey in temp_val){
								if(!mtfirst) mtstring+=',';
								mtstring+=temp_val[mkey];
								mtfirst = false;
							}

							if(mtstring != mstring){
								save_needed = true;
							}
						}else{
							save_needed = true;
						}
					}
					
					/* 2.1.6 */
					if(handle == 'transition'){
						// t.set_transition_type_visibility(layers[key]['settings'][handle]);
						t.check_no_transition(layers[key]['settings'][handle]);
					}

				}

				break;
			}
		}
	}


	/**
	 * set setting values from input fields
	 */
	t.set_setting_values = function(){
		
		if(selected_layer == null) return(false);

		var layer_id = selected_layer.data('id');

		for(var key in layers){

			if(layers[key].id == layer_id){
				for(var handle in all_attributes){
					
					if(typeof(layers[key]['settings']) == 'undefined') continue;
					if(typeof(layers[key]['settings'][handle]) == 'undefined') continue;

					if(all_attributes[handle]['type'] == 'checkbox'){
						if(layers[key]['settings'][handle] == jQuery('input[name="element-'+handle+'"]').val() || layers[key]['settings'][handle] == 'true' && jQuery('input[name="element-'+handle+'"]').val() == 'on'){
							jQuery('input[name="element-'+handle+'"]').attr('checked', true);
						}else{
							jQuery('input[name="element-'+handle+'"]').attr('checked', false);
						}
					}else if(all_attributes[handle]['type'] == 'colorpicker'){
						
						/* 2.1.6 */
						if(typeof RevColor === 'undefined') {
							jQuery('input[name="element-'+handle+'"]').val(layers[key]['settings'][handle]);
							jQuery('#element-'+handle).closest('.wp-picker-container').find('a').first().css('background-color', jQuery('input[name="element-'+handle+'"]').val());
						}
						else {
							jQuery('input[name="element-'+handle+'"]').val(layers[key]['settings'][handle]).AdamLabsColorPicker();
						}
						
						
					}else if(all_attributes[handle]['type'] == 'slider' || all_attributes[handle]['type'] == 'text-slider'){
						jQuery('input[name="element-'+handle+'"]').val(layers[key]['settings'][handle]);
						jQuery('#element-'+handle).slider({value:jQuery('input[name="element-'+handle+'"]').val()});
						jQuery("body").trigger("adamlabsgalleryslide",jQuery('#element-'+handle));
					}else if(all_attributes[handle]['type'] == 'select'){
						jQuery('select[name="element-'+handle+'"] option[value="'+layers[key]['settings'][handle]+'"]').attr("selected","selected");

						//special case
						if(handle == 'source'){
							jQuery('.elements-select-wrap').hide();

							switch(layers[key]['settings'][handle]){
								case 'event':
									jQuery('#adamlabsgallery-source-limit-wrap').show();

									jQuery('select[name="element-source-event"]').show().siblings('.select_fake').show();
									jQuery('select[name="element-source-event"] option[value="'+layers[key]['settings']['source-event']+'"]').attr("selected","selected");
								break;
								case 'woocommerce':
									jQuery('#adamlabsgallery-source-limit-wrap').show();
									jQuery('select[name="element-source-woocommerce"]').show().siblings('.select_fake').show();
									jQuery('select[name="element-source-woocommerce"] option[value="'+layers[key]['settings']['source-woocommerce']+'"]').attr("selected","selected");
								break;
								case 'icon':
									jQuery('#adamlabsgallery-source-icon-wrap').show();
									jQuery('input[name="element-source-icon"]').val(layers[key]['settings']['source-icon']);
									jQuery('#adamlabsgallery-preview-icon').html('<i class="'+layers[key]['settings']['source-icon']+'"></i>');
								break;
								case 'text':
									if(typeof layers[key]['settings']['special'] !== undefined && layers[key]['settings']['special'] == 'true' && typeof layers[key]['settings']['special-type'] !== undefined && layers[key]['settings']['special-type'] == 'blank-element'){
										jQuery('#adamlabsgallery-source-text-style-disable-wrap').hide();
									}else{
										jQuery('#adamlabsgallery-source-text-style-disable-wrap').show();
									}
									jQuery('#adamlabsgallery-source-text-wrap').show();
									jQuery('textarea[name="element-source-text"]').val(layers[key]['settings']['source-text']);
								break;
								case 'post':
								default:
									jQuery('#adamlabsgallery-source-limit-wrap').show();
									jQuery('select[name="element-source-post"]').show().siblings('.select_fake').show();
									if('source-taxonomy' in layers[key]['settings'])
										jQuery('select[name="element-source-taxonomy"] option[value="'+layers[key]['settings']['source-taxonomy']+'"]').attr("selected","selected");
									jQuery('select[name="element-source-post"] option[value="'+layers[key]['settings']['source-post']+'"]').attr("selected","selected");
									if(layers[key]['settings']['source-post'] == 'meta')
										jQuery('input[name="element-source-meta"]').val(layers[key]['settings']['source-meta']);


							}

						}else if(handle == 'link-type'){
							jQuery('#adamlabsgallery-element-post-url-wrap').hide();
							jQuery('#adamlabsgallery-element-post-meta-wrap').hide();
							jQuery('#adamlabsgallery-element-post-javascript-wrap').hide();
							jQuery('#adamlabsgallery-element-link-details-wrap').show();
							jQuery('#adamlabsgallery-element-facebook-wrap').hide();
							jQuery('#adamlabsgallery-element-gplus-wrap').hide();
							jQuery('#adamlabsgallery-element-pinterest-wrap').hide();
							jQuery('#adamlabsgallery-element-twitter-wrap').hide();

							switch(layers[key]['settings'][handle]){
								case 'post':
								case 'lightbox':
								case 'embedded_media':
								break;
								case 'url':
									jQuery('#adamlabsgallery-element-post-url-wrap').show();
									jQuery('input[name="element-url-link"]').val(layers[key]['settings']['link-type-url']);
								break;
								case 'meta':
									jQuery('#adamlabsgallery-element-post-meta-wrap').show();
									jQuery('input[name="element-meta-link"]').val(layers[key]['settings']['link-type-meta']);
								break;
								case 'javascript':
									jQuery('#adamlabsgallery-element-post-javascript-wrap').show();
									jQuery('input[name="element-javascript-link"]').val(layers[key]['settings']['link-type-javascript']);
								break;
								case 'sharefacebook':
									jQuery('#adamlabsgallery-element-link-details-wrap').hide();
									jQuery('#adamlabsgallery-element-facebook-wrap').show();
									jQuery('select[name="element-facebook-sharing-link"]').val(layers[key]['settings']['link-type-sharefacebook']);
									jQuery('input[name="element-facebook-link-url"]').val(layers[key]['settings']['link-type-sharefacebook-custom-url']);
									if(jQuery('select[name="element-facebook-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-facebook_link_custom").show();
									else  jQuery(".adamlabsgallery-element-facebook_link_custom").hide();
								break;
								case 'sharegplus':
									jQuery('#adamlabsgallery-element-link-details-wrap').hide();
									jQuery('#adamlabsgallery-element-gplus-wrap').show();
									jQuery('select[name="element-gplus-sharing-link"]').val(layers[key]['settings']['link-type-sharegplus']);
									jQuery('input[name="element-gplus-link-url"]').val(layers[key]['settings']['link-type-sharegplus-custom-url']);
									if(jQuery('select[name="element-gplus-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-gplus_link_custom").show();
									else  jQuery(".adamlabsgallery-element-gplus_link_custom").hide();
								break;
								case 'sharepinterest':
									jQuery('#adamlabsgallery-element-link-details-wrap').hide();
									jQuery('#adamlabsgallery-element-pinterest-wrap').show();
									jQuery('select[name="element-pinterest-sharing-link"]').val(layers[key]['settings']['link-type-sharepinterest']);
									jQuery('input[name="element-pinterest-link-url"]').val(layers[key]['settings']['link-type-sharepinterest-custom-url']);
									jQuery('textarea[name="element-pinterest-description"]').val(layers[key]['settings']['link-type-sharepinterest-description']);
									if(jQuery('select[name="element-pinterest-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-pinterest_link_custom").show();
									else  jQuery(".adamlabsgallery-element-pinterest_link_custom").hide();
								break;
								case 'sharetwitter':
									jQuery('#adamlabsgallery-element-link-details-wrap').hide();
									jQuery('#adamlabsgallery-element-twitter-wrap').show();
									jQuery('select[name="element-twitter-sharing-link"]').val(layers[key]['settings']['link-type-sharetwitter']);
									jQuery('input[name="element-twitter-link-url"]').val(layers[key]['settings']['link-type-sharetwitter-custom-url']);
									jQuery('input[name="element-twitter-text-before"]').val(layers[key]['settings']['link-type-sharetwitter-text-before']);
									jQuery('input[name="element-twitter-text-after"]').val(layers[key]['settings']['link-type-sharetwitter-text-after']);
									if(jQuery('select[name="element-twitter-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-twitter_link_custom").show();
									else  jQuery(".adamlabsgallery-element-twitter_link_custom").hide();
								break;
							}
						}else if(handle == 'display'){
							jQuery('#element-text-align-wrap').hide();
							jQuery('#element-float-wrap').hide();

							switch(layers[key]['settings'][handle]){
								case 'block':
									jQuery('#element-text-align-wrap').show();
									break;
								case 'inline-block':
									jQuery('#element-float-wrap').show();
								break;
							}
						}else if(handle == 'background-size'){

							if(layers[key][handle] == '%'){
								jQuery('#background-size-percent-wrap').css('display', 'inline-block');

								jQuery('input[name="element-background-size-x"]').val(layers[key]['settings']['background-size-x']);
								jQuery('input[name="element-background-size-y"]').val(layers[key]['settings']['background-size-y']);
							}
						}else if(handle == 'align'){
							jQuery('select[name="element-absolute-unit"] option[value="'+layers[key]['settings']['absolute-unit']+'"]').attr('selected', 'selected');
						}
					}else if(all_attributes[handle]['type'] == 'multi-text'){
						var multi_key = 0;
						jQuery('input[name="element-'+handle+'[]"]').each(function(){
							jQuery(this).val(layers[key]['settings'][handle][multi_key]);
							multi_key++;
						});
					}else{
						jQuery('input[name="element-'+handle+'"]').val(layers[key]['settings'][handle]);
					}

					//check the unit of elements
					if(jQuery('select[name="element-'+handle+'-unit"]').length > 0){
						jQuery('select[name="element-'+handle+'-unit"] option[value="'+layers[key]['settings'][handle+'-unit']+'"]').attr("selected","selected");
					}
				}
				break;
			}
		}
	}




	/**
	 * set setting styles from object
	 */
	t.set_setting_styles = function(){
		
		if(selected_layer == null) return(false);
		
		var layer_id = selected_layer.data('id');
		
		for(var key in layers){
		   if(layers[key].id == layer_id){
				
				//t.set_element_styles(selected_layer, layers[key]['settings']);
				t.set_inline_styles(selected_layer, layers[key]['settings']);
				found = true;
				break;
		   }
	   }
	}


	/**
	 * Set css inline styles element
	 */
	t.set_inline_styles = function(on_layer, settings, demo_element){
		
		var isw = jQuery('#adamlabsgallery-inline-style-wrapper');

		if(on_layer.data('handle') == 'adamlabsgallery-line-break' || on_layer.data('handle') == 'adamlabsgallery-blank-element') return true;
		
		var idle = [];
		var hover = [];

		//check if we have selected to not set any styles here
		if(settings['source'] == 'text' && typeof(settings['source-text-style-disable']) !== 'undefined' && settings['source-text-style-disable'] == 'on'){
			//do not write anything
		}else{

		
			var do_important = '';
			var do_hover = false;

			if(settings['force-important'] == 'true') do_important = ' !important';
			if(settings['enable-hover'] == 'on') do_hover = true;

			for(var handle in all_attributes){
				if(demo_element !== undefined){
					if(handle == 'margin') continue;
					if(handle == 'position') continue;
				}
				var style_type = all_attributes[handle]['style'];

				if(style_type == 'hover'){ //look for spacings, use same as idle
					switch(handle){
						case 'display':
						case 'text-align':
						case 'float':
						case 'clear':
						case 'margin':
						case 'padding':
							break;
						default:
							if(all_attributes[handle]['style'] != style_type) continue;
					}
				}

				if(all_attributes[handle]['style'] != 'idle' && all_attributes[handle]['style'] != 'hover') continue; //only styles idle and hover

				if(!do_hover && all_attributes[handle]['style'] == 'hover') continue;

				if(typeof(settings[handle]) !== 'undefined'){

					var set_handle = (style_type == 'idle') ? handle : handle.replace('-hover', '');

					if(all_attributes[handle]['type'] == 'multi-text'){

						if(typeof settings[handle+'-unit'] == 'undefined') settings[handle+'-unit'] = 'px';
						var set_unit = settings[handle+'-unit'];

						if(set_handle == 'border-radius'){ //check if this works
							if(style_type == 'idle'){
								idle.push('border-top-left-radius: '+settings[handle][0]+set_unit+do_important+';');
								idle.push('border-top-right-radius: '+settings[handle][1]+set_unit+do_important+';');
								idle.push('border-bottom-right-radius: '+settings[handle][2]+set_unit+do_important+';');
								idle.push('border-bottom-left-radius: '+settings[handle][3]+set_unit+do_important+';');
							}else{
								hover.push('border-top-left-radius: '+settings[handle][0]+set_unit+do_important+';');
								hover.push('border-top-right-radius: '+settings[handle][1]+set_unit+do_important+';');
								hover.push('border-bottom-right-radius: '+settings[handle][2]+set_unit+do_important+';');
								hover.push('border-bottom-left-radius: '+settings[handle][3]+set_unit+do_important+';');
							}
						}else if(set_handle == 'border'){ //check if this works
							if(style_type == 'idle'){
								idle.push('border-top: '+settings[handle][0]+set_unit+do_important+';');
								idle.push('border-right: '+settings[handle][1]+set_unit+do_important+';');
								idle.push('border-bottom: '+settings[handle][2]+set_unit+do_important+';');
								idle.push('border-left: '+settings[handle][3]+set_unit+do_important+';');
							}else{
								hover.push('border-top: '+settings[handle][0]+set_unit+do_important+';');
								hover.push('border-right: '+settings[handle][1]+set_unit+do_important+';');
								hover.push('border-bottom: '+settings[handle][2]+set_unit+do_important+';');
								hover.push('border-left: '+settings[handle][3]+set_unit+do_important+';');
							}
						}else if(set_handle == 'box-shadow' || set_handle == 'background-color'){
							var multi_string = '';
							for(var mkey in settings[handle]){
								multi_string += settings[handle][mkey]+set_unit+' ';
							}

							//get box shadow color
							var shadow_color = (style_type == 'idle') ? settings['shadow-color'] : settings['shadow-color-hover'];

							/* 2.1.6 */
							/*
							//get box shadow transaprency
							var shadow_transparency = (style_type == 'idle') ? settings['shadow-alpha'] : settings['shadow-alpha-hover'];
							shadow_color = AdminEssentials.hex_to_rgba(shadow_color, shadow_transparency, true);
							*/
							
							multi_string += ' '+shadow_color;

							if(style_type == 'idle'){
								idle.push('-moz-'+set_handle+': '+multi_string+do_important+';');
								idle.push('-webkit-'+set_handle+': '+multi_string+do_important+';');
								idle.push(set_handle+': '+multi_string+do_important+';');
							}else{
								hover.push('-moz-'+set_handle+': '+multi_string+do_important+';');
								hover.push('-webkit-'+set_handle+': '+multi_string+do_important+';');
								hover.push(set_handle+': '+multi_string+do_important+';');
							}
						}else{
							var multi_string = '';
							for(var mkey in settings[handle]){
								multi_string += settings[handle][mkey]+set_unit+' ';
							}

							if(style_type == 'idle'){
								idle.push(set_handle+': '+multi_string+do_important+';');
							}else{
								hover.push(set_handle+': '+multi_string+do_important+';');
							}
						}
					}else{
						if(set_handle == 'background-color'){
							
							/* 2.1.6 */
							/*
							//get bg color transaprency
							var bg_color_transparency = (style_type == 'idle') ? settings['bg-alpha'] : settings['bg-alpha-hover'];
							var bg_color_rgba = AdminEssentials.hex_to_rgba(settings[handle], bg_color_transparency, true); // we only need rgba in backend
							*/
							
							var bg_color_rgba = settings[handle];
							if(typeof RevColor !== 'undefined') bg_color_rgba = RevColor.get(bg_color_rgba);
							
							if(style_type == 'idle'){
								idle.push('background: '+bg_color_rgba+do_important+';');
							}else{
								hover.push('background: '+bg_color_rgba+do_important+';');
							}
						}else{
							if(set_handle == 'border'){
								if(style_type == 'idle'){
									idle.push('border-style: solid'+do_important+';');
								}else{
									hover.push('border-style: solid'+do_important+';');
								}
							}
							if(set_handle == 'font-style' && settings[handle] == 'true') settings[handle] = 'italic'+do_important+';';

							var set_unit = all_attributes[handle]['unit'];

							if(set_unit == undefined) set_unit = '';

							if(settings[handle] == '') continue;

							if(style_type == 'idle'){
								if(set_handle == 'display') //force important on display setting
									idle.push(set_handle+': '+settings[handle]+set_unit+' !important;');
								else
									idle.push(set_handle+': '+settings[handle]+set_unit+do_important+';');

								if(set_handle == 'position' && settings[handle] == 'absolute'){
									idle.push('height: auto'+do_important+';');
									idle.push('width: auto'+do_important+';');
									idle.push('white-space: nowrap'+do_important+';');


									var lr = settings['left-right'] == "NaN" ? 0 : settings['left-right'];
									var tb = settings['top-bottom'] == "NaN" ? 0 : settings['top-bottom'];

									switch(settings['align']){
										case 't_l':
											idle.push('top: '+tb+settings['absolute-unit']+';');
											idle.push('left: '+lr+settings['absolute-unit']+';');
											idle.push('bottom:auto;');
											idle.push('right:auto;');
											break;
										case 't_r':
											idle.push('top: '+tb+settings['absolute-unit']+';');
											idle.push('right: '+lr+settings['absolute-unit']+';');
											idle.push('bottom:auto;');
											idle.push('left:auto;');

											break;
										case 'b_l':
											idle.push('bottom: '+tb+settings['absolute-unit']+';');
											idle.push('left: '+lr+settings['absolute-unit']+';');
											idle.push('top:auto;');
											idle.push('right:auto;');

											break;
										case 'b_r':
											idle.push('bottom: '+tb+settings['absolute-unit']+';');
											idle.push('right: '+lr+settings['absolute-unit']+';');
											idle.push('top:auto;');
											idle.push('left:auto;');
											break;
									}

								}

							}else{
								hover.push(set_handle+': '+settings[handle]+set_unit+do_important+';');
							}
						}
					}
				}

			}
		}
		
		var html = '<style type="text/css" id="adamlabsgallery-element-style-'+on_layer.data('handle')+'">'+"\n";

		if(idle.length > 0){
			html += '.skin-dz-elements[data-handle="'+on_layer.data('handle')+'"] {'+"\n";
			for(var ikey in idle){
				html+= idle[ikey]+"\n";
			}
			html += '}'+"\n";
		}

		if(hover.length > 0){
			html += '.skin-dz-elements[data-handle="'+on_layer.data('handle')+'"]:hover {'+"\n";
			for(var hkey in hover){
				html+= hover[hkey]+"\n";
			}
			html += '}'+"\n";
		}

		html += '</style>'+"\n";

		//remove styles tag of element if exists
		if(jQuery('#adamlabsgallery-element-style-'+on_layer.data('handle')).length > 0) jQuery('#adamlabsgallery-element-style-'+on_layer.data('handle')).remove();

		isw.append(html);

	}


	/**
	 * Set css inline styles element
	 */
	t.set_global_inline_styles = function(){

		var isw = jQuery('#adamlabsgallery-inline-style-wrapper');

		if(jQuery('#adamlabsgallery-global-inline-styles').length > 0) jQuery('#adamlabsgallery-global-inline-styles').remove();

		html = '<style type="text/css" id="adamlabsgallery-global-inline-styles">'+"\n";
		html += adamlabsgallery_codemirror_global_css.getValue();
		html += '</style>'+"\n";

		save_needed = true;

		isw.append(html);
	}


	/**
	 * Set Settings like delay and animation type
	 */
	t.set_setting_attributes = function(){
		
		if(selected_layer == null) return(false);

		var layer_id = selected_layer.data('id');

		//if(typeof(layers[layer_id]) === 'undefined') return false;

		for(var key in layers){
			if(layers[key]['id'] == layer_id){ //element == layers[key]['handle'] &&

				for(var handle in all_attributes){
					if(all_attributes[handle]['style'] !== 'attribute') continue;

					if(handle == 'delay'){
						var delay = layers[key]['settings'][handle];

						if(delay > 0) delay = delay / 100;
						selected_layer.data('delay', delay);
					}
					else if(handle == 'duration') {
							
						var duration = layers[key]['settings'][handle];
						selected_layer.data('duration', duration);	
							
					}else if(handle == 'transition'){
						var classes = selected_layer.attr('class');
						var class_arr = classes.split(' ');

						//reset classes
						for(var class_key in class_arr){
							if(class_arr[class_key].indexOf('adamlabsgallery-') !== -1) selected_layer.removeClass(class_arr[class_key]);
						}

						if(typeof layers[key]['settings']['transition-type'] === 'undefined') layers[key]['settings']['transition-type'] = '';
						//add transition
						selected_layer.addClass('adamlabsgallery-'+layers[key]['settings'][handle]+layers[key]['settings']['transition-type']);
					}/*else if(handle == 'split'){
						var split_on = layers[key]['settings'][handle];
						//console.log(split_on);

						selected_layer.data('split', split_on);
						selected_layer.attr('data-split', split_on);
					}*/
				}
			}
		}
	}

	/**
	 * Set the caption text of the element
	 */
	t.set_setting_caption = function(){
		if(selected_layer == null) return(false);

		var layer_id = selected_layer.data('id');

		var caption = '';

		for(var key in layers){
			if(layers[key]['id'] == layer_id){
				//check if we are special blank html element. If yes, leave empty in backend
				if(typeof layers[key]['settings']['special'] !== undefined && layers[key]['settings']['special'] == 'true'){
					if(typeof layers[key]['settings']['special-type'] !== undefined && layers[key]['settings']['special-type'] == 'blank-element'){
						selected_layer.html('&nbsp;');
						return true;
					}
				}
				switch(layers[key]['settings']['source']){
					case 'icon':
						if(typeof layers[key]['settings']['source-icon'] !== 'undefined')
							var caption = '<i class="'+layers[key]['settings']['source-icon']+'"></i>';
					break;
					case 'text':
					   if(typeof layers[key]['settings']['source-text'] !== 'undefined')
							var caption = layers[key]['settings']['source-text'];
					break;
					case 'event':
						if(typeof layers[key]['settings']['source-event'] !== 'undefined')
							var caption = layers[key]['settings']['source-event'];
					break;
					case 'woocommerce':
						if(typeof layers[key]['settings']['source-woocommerce'] !== 'undefined')
							var caption = layers[key]['settings']['source-woocommerce'];
					break;
					case 'post':
					default:
						if(typeof layers[key]['settings']['source-post'] !== 'undefined')
							var caption = layers[key]['settings']['source-post'];

				}

				if(layers[key]['settings']['source'] == 'post' || layers[key]['settings']['source'] == 'event' || layers[key]['settings']['source'] == 'woocommerce'){
					if(caption == ''){
						caption = 'title';
						layers[key]['settings']['source'] = 'post';
					}

					switch(caption){ //set shorts for woocommerce
						case 'wc_full_price':
							caption = adamlabsgallery_lang.full_price;
							break;
						case 'wc_price':
							caption = adamlabsgallery_lang.regular_price;
							break;
						case 'wc_price_no_cur':
							caption = adamlabsgallery_lang.regular_price_no_cur;
							break;
						default:
							caption = jQuery('select[name="element-source-'+layers[key]['settings']['source']+'"] option[value="'+caption+'"]').text();
						break;
					}
				}
				selected_layer.html(caption);

				break;
			}
		}
	}

	/**
	 * propagate styles into all layers (when layers get filled at start)
	 */
	t.propagate_layer_styles = function(handle, layer_id){
		
		if(typeof init_elements[handle] == 'undefined') return false;

		for(var key in init_elements[handle]['settings']){
			layers[layer_id]['settings'][key] = init_elements[handle]['settings'][key];
		}

	}

	/**
	 * propagate elements into selectbox
	 */
	t.propagate_element_selector = function(){
		jQuery('#element-settings-current-name').html('');

		for(var lkey in layers){ //add layers
			var ele_text = '';

			switch(layers[lkey]['settings']['source']){
				case 'icon':
					ele_text = layers[lkey]['settings']['source-icon'];
					break;
				case 'post':
					ele_text = jQuery('select[name="element-source-post"] option[value="'+layers[lkey]['settings']['source-post']+'"]').text();
					break;
				case 'event':
					ele_text = jQuery('select[name="element-source-event"] option[value="'+layers[lkey]['settings']['source-event']+'"]').text();
					break;
				case 'woocommerce':
					ele_text = jQuery('select[name="element-source-woocommerce"] option[value="'+layers[lkey]['settings']['source-woocommerce']+'"]').text();
					break;
				case 'text':
					ele_text = layers[lkey]['settings']['source-text'].replace(/(<([^>]+)>)/ig,"");
					break;
			}

			jQuery('#element-settings-current-name').append(jQuery('<option>', {
				value: layers[lkey].id,
				text: ele_text
			}));
		}

		jQuery('#element-settings-current-name option').each(function(){
			jQuery(this).removeAttr('selected');
		});

		if(selected_layer !== null){
			jQuery('#element-settings-current-name option[value="'+selected_layer.data('id')+'"]').attr('selected', 'selected');
			jQuery('#element-settings-current-name').parent().show();

			var nostyle = (jQuery('select[name="element-source"] option:selected').val() == 'text' && jQuery('input[name="element-source-text-style-disable"]').attr('checked') == 'checked') ? '-nostyle' : '';
			jQuery('.adamlabsgallery-element-class-setter').text(adamlabsgallery_lang.class_name+' .adamlabsgallery-'+jQuery('#adamlabsgallery-item-skin-slug').text()+nostyle+'-element-'+selected_layer.data('id'));

		}else{
			jQuery('.adamlabsgallery-element-class-setter').text('');
		}

		var hide_tool = true;
		for(var key in layers){
			if(layers[key] != undefined){
				hide_tool = false;
				break;
			}
		}

		if(hide_tool) jQuery('#element-settings-current-name').parent().hide();

		AdminEssentials.presetSelects();
	}

	/**
	 * propagate styles into all default layers (when layers get filled at start)
	 */
	t.propagate_default_element_styles = function(){

		for(var handles in init_elements){

			if(typeof init_elements[handles]['settings'] === 'undefined') continue;

			var layer = jQuery('.mainul .skin-dz-elements[data-handle="' + handles + '"]');

			if(!jQuery.isEmptyObject(layer)){
				layer.each(function(){
					t.set_inline_styles(jQuery(this), init_elements[handles]['settings'], true);
				});
			}
		}

		essapi.adamlabsgalleryreinit();

		t.set_default_elements_draggable();

	}

	/**
	 * select layer by id
	 */
	t.select_layer = function(id){
		
		//remove highlight from all elements
		jQuery('#adamlabsgallery-dz-hover-wrap .skin-dz-elements').removeClass('selected');
		
		jQuery('.skin-dz-elements').each(function(index) {
			var jt = jQuery(this);
			if (jt!=undefined && jt.data('id')!=undefined){
				if (jt.data('id').toString() === id.toString()){
					selected_layer = jt;
					return true;
				}
			}

		});
		
		/* 2.1.6 - hide these options for masonry content */
		var alwaysVisible = document.getElementById('always-visible-options');
		if(alwaysVisible) {
			var display = !selected_layer.closest('#skin-dz-m-wrap').length ? 'block' : 'none';
			alwaysVisible.style.display = display;
		}

		selected_layer.addClass('selected');

		t.set_default_values(id);

		t.set_setting_values();

		t.show_toolbar();

		t.select_element_editor_name();

		jQuery('select[name="element-source"]').change();

	}


	/**
	 * delete layer by id
	 */
	t.delete_layer = function(layer_obj){
		jQuery('#adamlabsgallery-dz-hover-wrap .skin-dz-elements').removeClass('selected');

		if(typeof(layer_obj) !== 'object')
			layer_obj = jQuery('.skin-dz-elements[data-id="'+layer_obj+'"]');

		var	id = layer_obj.data('id');

		layer_obj.remove();

		for(var key in layers){
			if(layers[key]['id'] == id){
				delete layers[key];
				break;
			}
		}

		selected_layer = null;

		var select_new_layer = -1;

		//select next layer
		if(layers.length > 0){
			for(var key in layers){
				if(typeof layers[key]['id'] != undefined){
					t.select_layer(layers[key]['id']);
					break;
				}
			}
		}

		if(selected_layer == null) t.show_empty_toolbar();

		t.change_bg_color();
		t.propagate_element_selector();
		t.redraw_container_height();
		AdminEssentials.adjustDropHeights();
	}

	/**
	 * shows the toolbar, move it to correct position
	 */
	t.show_toolbar = function(){
		if(selected_layer == null) return(false);

		var found = false;
		
		jQuery('#settings-dz-elements-wrapper').show();
		jQuery('.adamlabsgallery-hide-on-special').show();
		jQuery('#element-save-as-button').show();
		
		for(var key in layers){
			if(layers[key].handle == selected_layer.data('handle')){
				if(typeof layers[key]['settings']['special'] !== undefined && layers[key]['settings']['special'] == 'true'){
					//check if we have to hide everything or if we just have to show the HTML box
					if(typeof layers[key]['settings']['special-type'] !== undefined){
						switch(layers[key]['settings']['special-type']){
							case 'blank-element':
								jQuery('.adamlabsgallery-hide-on-blank-element').hide();
								jQuery('#element-save-as-button').hide();
								jQuery('.adamlabsgallery-source-li>a').click();
							break;
							case 'line-break':
							default:
								jQuery('#settings-dz-elements-wrapper').hide();
								jQuery('#element-save-as-button').hide();
							break;
						}
					}
				}else{
					jQuery('#settings-dz-elements-wrapper').show();
					jQuery('.adamlabsgallery-hide-on-special').show();
					jQuery('#element-save-as-button').show();
				}
				t.hide_empty_toolbar();
				found = true;
				break;
			}
		}

		if(!found) t.show_empty_toolbar();
	}

	/**
	 * hide the toolbar options
	 */
	t.show_empty_toolbar = function(){
		jQuery('#element-setting-wrap-alternative').css('display', 'block');
		jQuery('#element-setting-wrap-top').css('display', 'none');

	}

	/**
	 * show the toolbar options
	 */
	t.hide_empty_toolbar = function(){
		jQuery('#element-setting-wrap-alternative').css('display', 'none');
		jQuery('#element-setting-wrap-top').css('display', 'block');
	}

	t.select_element_editor_name = function(){
		if(selected_layer == null) return(false);

		var layer_id = selected_layer.data('id');

		jQuery('#element-settings-current-name option').each(function(){
			jQuery(this).removeAttr('selected');
		});

		jQuery('#element-settings-current-name option[value="'+layer_id+'"]').attr('selected', 'selected');

		AdminEssentials.presetSelects();
	}

	/**
	 * return latest id of layers
	 */
	t.get_latest_id = function(){
		var last_id = -1;

		for(var key in layers)
			if(parseInt(layers[key].id) > last_id) last_id = parseInt(layers[key].id);

		if(parseInt(jQuery('input[name="adamlabsgallery-item-skin-element-last-id"]').val()) > parseInt(last_id)) last_id = jQuery('input[name="adamlabsgallery-item-skin-element-last-id"]').val();

		return parseInt(last_id);
	}


	/**
	 * output the settings
	 */
	t.output_setting_data = function(){
		var output_layers = [];

		//return false;
		for(var key in layers){
			if(layers[key] == "") continue;
			if(layers[key].order == undefined) layers[key].order = '0';
			output_layers[key] = {};

			//Filter styles out of layer
			output_layers[key]['id'] = layers[key]['id'];
			output_layers[key]['order'] = layers[key]['order'];
			//output_layers[key]['handle'] = layers[key]['handle'];
			output_layers[key]['container'] = layers[key]['container'];
			output_layers[key]['settings'] = layers[key]['settings'];
		}

		return output_layers;
	}


	/**
	 * creates elements by given data + put them into the preview box
	 */
	t.create_elements_by_data = function(given_data){
		
		//var current_id = 0;
		var empty_element = 0;
		var temp_object = [];

		if(typeof given_data == 'undefined') given_data = init_layers;
		if(given_data == null) return false;

		for(var key in given_data){
			if(given_data[key] == "") continue;
			if(given_data[key].order == undefined) given_data[key].order = '0';
			if(given_data[key].container == undefined) given_data[key].container = 'c';
			temp_object[key] = jQuery.extend({}, given_data[key]);
		}

		if(!jQuery.isEmptyObject(temp_object)){
			temp_object.sort(t.sortByOrder);
		}

		for(var key in temp_object){ //create all layers and put them into the corresponding boxes
			var found = false;
			for(var element in init_elements){
				if(element == temp_object[key].handle){
					found = element;
					break;
				}
			}

			if(found !== false){ //found
				//var data_id = temp_object[key].id;
				var data_handle = temp_object[key].handle;
				var data_name = init_elements[found].name;

			}else{ //not found, create element as own element

				var data_handle = adamlabsgallery_lang.new_element_sanitize+'-'+empty_element;
				var data_name = adamlabsgallery_lang.new_element+' '+empty_element;

				//change to new object
				for(var el_key in init_layers){
					if(init_layers[el_key].id == temp_object[key].id && init_layers[el_key].handle == temp_object[key].handle){
						//init_layers[el_key].id = current_id.toString();
						init_layers[el_key].handle = data_handle;
					}
				}

				//temp_object[key].id = current_id.toString();
				temp_object[key].handle = data_handle;

				empty_element++;
			}

			//var data_id = current_id.toString();
			var data_id = temp_object[key].id;
			//current_id++;

			var special_class = '';

			if(typeof temp_object[key]['settings']['special'] !== undefined && temp_object[key]['settings']['special'] == 'true'){
				if(typeof temp_object[key]['settings']['special-type'] !== undefined){
					switch(temp_object[key]['settings']['special-type']){
						case 'blank-element':
							special_class =' adamlabsgallery-special-blank-element';
						break;
						case 'line-break':
						default:
							special_class =' adamlabsgallery-special-element';
						break;
					}
				}else{
					special_class =' adamlabsgallery-special-element';
				}
			}
			
			var html = '<div class="skin-dz-elements'+special_class+'" data-id="'+data_id+'" data-handle="'+data_handle+'">'+data_name+'</div>';

			jQuery('#skin-dz-'+temp_object[key].container).append(html);

		}

		layers = temp_object;

		for(var lkey in layers){
			//t.propagate_layer_styles(layers[lkey]['handle'], lkey); //insert styles into object

			selected_layer = jQuery('.skin-dz-elements[data-id="'+layers[lkey]['id']+'"]');

			t.set_setting_styles();
			t.set_setting_caption();
			t.set_setting_attributes();

			if(typeof layers[lkey]['settings']['position'] !== 'undefined'){
				if(layers[lkey]['settings']['position'] == 'absolute'){
					jQuery(selected_layer).appendTo('#skin-dz-wrapper');
					t.set_absolute_positioning();
				}
			}
		}

		t.propagate_element_selector(); //propagate selectbox in element editor

		selected_layer = null;

		t.change_bg_color();

		t.selet_first_layer();

		t.redraw_container_height();
	}


	t.selet_first_layer = function(){
		
		var is_selected = false;

		for(var key in layers){
			if(layers[key] == undefined) continue;

			t.select_layer(layers[key]['id']);
			is_selected = true;
			break;
		}

		if(!is_selected) t.show_empty_toolbar();

	}


	/**
	 * add/adjust animation classes
	 */
	t.add_animation_classes = function(){
		var c = jQuery('#skin-dz-c-wrap .adamlabsgallery-element-cover');
		var tl = jQuery('#skin-dz-tl-wrap .adamlabsgallery-element-cover');
		var br = jQuery('#skin-dz-br-wrap .adamlabsgallery-element-cover');

		c.removeClass().addClass('adamlabsgallery-element-cover');
		tl.removeClass().addClass('adamlabsgallery-element-cover');
		br.removeClass().addClass('adamlabsgallery-element-cover');

		c.removeAttr('data-delay');
		tl.removeAttr('data-delay');
		br.removeAttr('data-delay');
		c.data('delay', null);
		tl.data('delay', null);
		br.data('delay', null);
		
		c.removeAttr('data-duration');
		tl.removeAttr('data-duration');
		br.removeAttr('data-duration');
		c.data('duration', null);
		tl.data('duration', null);
		br.data('duration', null);

		jQuery('#skin-dz-wrapper').removeClass().addClass('adamlabsgallery-'+jQuery('select[name="cover-group-animation"] option:selected').val());
		jQuery('#skin-dz-media-bg').removeClass().addClass('adamlabsgallery-'+jQuery('select[name="media-animation"] option:selected').val());

		jQuery('#skin-dz-wrapper').attr('data-delay', parseInt(jQuery('input[name="cover-group-animation-delay"]').val()) / 100);
		jQuery('#skin-dz-wrapper').attr('data-duration', jQuery('input[name="cover-group-animation-duration"]').val());
		
		jQuery('#skin-dz-media-bg').attr('data-delay', parseInt(jQuery('input[name="media-animation-delay"]').val()) / 100);
		jQuery('#skin-dz-media-bg').attr('data-duration', jQuery('input[name="media-animation-duration"]').val());

		jQuery('#skin-dz-wrapper').data('delay', parseInt(jQuery('input[name="cover-group-animation-delay"]').val()) / 100);
		jQuery('#skin-dz-media-bg').data('delay', parseInt(jQuery('input[name="media-animation-delay"]').val()) / 100);
		
		jQuery('#skin-dz-wrapper').data('duration', jQuery('input[name="cover-group-animation-duration"]').val());
		jQuery('#skin-dz-media-bg').data('duration', jQuery('input[name="media-animation-duration"]').val());

		if(jQuery('select[name="cover-type"] option:selected').val() == 'full'){
			c.addClass('adamlabsgallery-'+jQuery('select[name="cover-animation-center"] option:selected').val()+jQuery('select[name="cover-animation-center-type"] option:selected').val());

			c.attr('data-delay', parseInt(jQuery('input[name="cover-animation-delay-center"]').val()) / 100);
			c.data('delay', parseInt(jQuery('input[name="cover-animation-delay-center"]').val()) / 100);
			
			c.attr('data-duration', jQuery('input[name="cover-animation-duration-center"]').val());
			c.data('duration', jQuery('input[name="cover-animation-duration-center"]').val());
			
		}else{
			tl.addClass('adamlabsgallery-'+jQuery('select[name="cover-animation-top"] option:selected').val()+jQuery('select[name="cover-animation-top-type"] option:selected').val());
			c.addClass('adamlabsgallery-'+jQuery('select[name="cover-animation-center"] option:selected').val()+jQuery('select[name="cover-animation-center-type"] option:selected').val());
			br.addClass('adamlabsgallery-'+jQuery('select[name="cover-animation-bottom"] option:selected').val()+jQuery('select[name="cover-animation-bottom-type"] option:selected').val());

			tl.attr('data-delay', parseInt(jQuery('input[name="cover-animation-delay-top"]').val()) / 100);
			c.attr('data-delay', parseInt(jQuery('input[name="cover-animation-delay-center"]').val()) / 100);
			br.attr('data-delay', parseInt(jQuery('input[name="cover-animation-delay-bottom"]').val()) / 100);

			tl.data('delay', parseInt(jQuery('input[name="cover-animation-delay-top"]').val()) / 100);
			c.data('delay', parseInt(jQuery('input[name="cover-animation-delay-center"]').val()) / 100);
			br.data('delay', parseInt(jQuery('input[name="cover-animation-delay-bottom"]').val()) / 100);
			
			tl.attr('data-duration', jQuery('input[name="cover-animation-duration-top"]').val());
			c.attr('data-duration', jQuery('input[name="cover-animation-duration-center"]').val());
			br.attr('data-duration', jQuery('input[name="cover-animation-duration-bottom"]').val());

			tl.data('duration', jQuery('input[name="cover-animation-duration-top"]').val());
			c.data('duration', jQuery('input[name="cover-animation-duration-center"]').val());
			br.data('duration', jQuery('input[name="cover-animation-duration-bottom"]').val());
		}
	}


	/**
	 * refresh the predefined elements in navigation
	 */
	t.refresh_predefined_elements = function(remove_handle){
		if(typeof remove_handle == 'undefined'){
			AdminEssentials.ajaxRequest("get_predefined_elements", {}, '',function(response){
				t.refreshInitElements(response['data']['elements']);

				t.refresh_item_layout_elements(response['data']['html']);

				t.propagate_default_element_styles();

				t.propagate_element_selector();
			});
		}else{ //manual refresh
			for(var handle in init_elements){
				if(remove_handle == handle){
					delete(init_elements['handle']);
					break;
				}
			}

			essapi.adamlabsgalleryredraw();
		}
	};


	/**
	 * refresh the elements in item layout
	 */
	t.refresh_item_layout_elements = function(elements_html){
		//delete all elements
		jQuery('.mainul').html('');
		jQuery('.mainul').html(elements_html);
	};


	/**
	 * check where the content should be (if masonry is selected)
	 */
	t.check_content_position = function(){
		var sc = jQuery('select[name="show-content"] option:selected').val();

		jQuery('#skin-dz-m-wrap').show();

		if(sc == 'none'){
			jQuery('#skin-dz-m-wrap').hide();
		}else if(sc == 'bottom'){
			jQuery('#skin-dz-m-wrap').appendTo(jQuery('#adamlabsgallery-dz-hover-wrap'));
		}else if(sc == 'top'){
			jQuery('#skin-dz-m-wrap').prependTo(jQuery('#adamlabsgallery-dz-hover-wrap'));
		}
	}


	/**
	 * element dragging for absolute positioned elements
	 */
	t.set_absolute_positioning = function(){
		jQuery("#skin-dz-wrapper>.skin-dz-elements").draggable({
			containment: '.adamlabsgallery-editor-inside-wrapper',
			drag: function()  {
				jQuery(this).css({'bottom':'auto','right':'auto'});
			}
		});
		jQuery("#skin-dz-wrapper>.skin-dz-elements").on('dragstop', function(event, ui){
			if(selected_layer.data('id') !== jQuery(this).data('id')){
				t.select_layer(jQuery(this).data('id'));
			}
			t.calculate_absolute_positioning(jQuery(this));
		});
	}


	/**
	 * element dragging for absolute positioned elements
	 */
	t.calculate_absolute_positioning = function(cont){
		var tlr = jQuery('input[name="element-top-bottom"]');
		var blr = jQuery('input[name="element-left-right"]');
		var unit = jQuery('select[name="element-absolute-unit"] option:selected').val();

		var ww = jQuery('#adamlabsgallery-dz-padding-wrapper').width();
		var wh = jQuery('#adamlabsgallery-dz-padding-wrapper').height();

		var align = jQuery('select[name="element-align"] option:selected').val();
		var par = jQuery('#skin-dz-media-bg');
		var parw = par.width();
		var parh = par.height();

		var top = cont.position().top,
			left = cont.position().left,
			right =  parw - cont.position().left - cont.outerWidth(true),
			bottom = parh - cont.position().top - cont.outerHeight(true);

		if (unit=="%") {
			top = (top / parh) * 100;
			left = (left / parw) * 100;
			right = (right / parw) * 100;
			bottom = (bottom / parh) * 100;
		}
		switch(align){
			case 't_l':
				tlr.val(top);
				blr.val(left);
				break;
			case 't_r':
				tlr.val(top);
				blr.val(right);
				break;
			case 'b_l':
				tlr.val(bottom);
				blr.val(left);
				break;
			case 'b_r':
				tlr.val(bottom);
				blr.val(right);
				break;
		}
		t.setting_has_changed();
	}


	/**
	 * enable draggable of default elements
	 */
	t.set_default_elements_draggable = function(){
		jQuery("#skin-dz-tl, #skin-dz-br, #skin-dz-c, #skin-dz-m, .skin-dz-elements, .adamlabsgallery-special-element, .adamlabsgallery-additional-element").disableSelection();

		jQuery(".mainul .skin-dz-elements, .adamlabsgallery-special .adamlabsgallery-special-element, .adamlabsgallery-additional-element").draggable({
			connectToSortable: "#skin-dz-tl,#skin-dz-br,#skin-dz-c,#skin-dz-m",
			helper: "clone",
			revert: false,
			appendTo:"#adamlabsgallery-wrap",
			drag: function(){
				AdminEssentials.whileDropOrSort('.skin-dz-elements.ui-draggable-dragging');
			},
			stop: function(){
				AdminEssentials.atDropStop();
			}
		});

		jQuery(".adamlabsgallery-trashdropzone").droppable({
			accept: ".mainul .skin-dz-elements",
			hoverClass: "adamlabsgallery-trashdropzone-hover",
			drop: function(event, ui){
				if(confirm(adamlabsgallery_lang.really_delete_element_permanently)){
					var data = {
						handle: ui.draggable.data('handle')
					};

					AdminEssentials.ajaxRequest("delete_predefined_elements", data, '.adamlabsgallery-trashdropzone',function(response){
						jQuery(ui.draggable).closest('li').remove();
						t.refresh_predefined_elements(ui.draggable.data('handle'));
					});
				}
			}
		});

		t.set_absolute_positioning();

		jQuery(".drop-to-stylechange").droppable({
			accept: ".mainul .skin-dz-elements",
			hoverClass: "adamlabsgallery-trashdropzone-hover",
			drop: function(event, ui){
				if(selected_layer == null) return false;

				var new_settings = {};
				var search_handle = ui.draggable.data('handle');
				for(var handle in init_elements){
					if(handle == search_handle){
						new_settings = jQuery.extend({}, init_elements[handle]['settings']);
						break;
					}
				}

				var lid = selected_layer.data('id');

				if(!jQuery.isEmptyObject(new_settings)){
					for(var key in layers){
						if(layers[key]['id'] ==lid){

							for(var handle in all_attributes){
								if(all_attributes[handle]['style'] != 'idle') continue; // && all_attributes[handle]['style'] != 'hover'

								if(typeof new_settings[handle] != 'undefined'){
									layers[key]['settings'][handle] = new_settings[handle];

									if(all_attributes[handle]['type'] == 'multi-text'){
										if(typeof new_settings[handle+'-unit'] == 'undefined') new_settings[handle+'-unit'] = 'px';

										layers[key]['settings'][handle+'-unit'] = new_settings[handle+'-unit'];
									}
									
									/* 2.1.6 */
									/*
									if(handle == 'background-color' && typeof new_settings['bg-alpha'] != 'undefined'){
										layers[key]['settings']['bg-alpha'] = new_settings['bg-alpha'];
									}else if(handle == 'background-color' && typeof new_settings['shadow-alpha'] != 'undefined'){
										layers[key]['settings']['shadow-alpha'] = new_settings['shadow-alpha'];
									}
									*/
								}
							}

							//recheck layer & call reprint of it
							t.select_layer(lid);
							t.setting_has_changed();

							break;
						}
					}
				}
			}
		});
	}


	/**
	 * redraw container height
	 */
	t.redraw_container_height = function(){
		var eecc = jQuery('#adamlabsgallery-element-centerme-c');
		var eecw = jQuery('#skin-dz-wrapper');

		eecc.css({top:Math.round((eecw.height() - eecc.height())/2)+"px"});
	}


	/**
	 * redraw container width
	 */
	t.redraw_container_width = function(){
		var con = jQuery('#adamlabsgallery-dz-hover-wrap');

		var wl = parseInt(con.css('paddingLeft'), 0);
		var wr = parseInt(con.css('paddingRight'), 0);
		var bl = parseInt(con.css('borderLeftWidth'), 0);
		var br = parseInt(con.css('borderRightWidth'), 0);

		var new_width = 400 - wl - wr - bl - br;

		con.css('width', new_width);

		jQuery('#skin-dz-wrapper, #skin-dz-video-wrapper, #skin-dz-music-wrapper, #skin-dz-media-bg-wrapper').css('width', new_width);

	}


	/**
	 * change the background color of elements
	 */
	t.change_bg_color = function(){

		var bgColor = jQuery('#container-background-color');
		var full_color = bgColor.attr('data-color') || bgColor.val();
		var bg_size = jQuery('select[name="cover-background-size"] option:selected').val();
		var bg_repeat = jQuery('select[name="cover-background-repeat"] option:selected').val();
		
		/* 2.1.6 colorpicker conversion */
		// var transparency = jQuery('input[name="element-container-background-color-opacity"]').val();
		// var full_color = AdminEssentials.hex_to_rgba(bg_color, transparency, true);

		var background_image = (jQuery('input[name="cover-background-image"]').val() != '0') ? jQuery('input[name="cover-background-image-url"]').val() : '';

		jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-color', 'transparent');
		jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background-color', 'transparent');
		jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background-color', 'transparent');
		jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-image', '');
		jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background-image', '');
		jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background-image', '');

		if(full_color == null) full_color = 'transparent';

		if(jQuery('select[name="cover-type"] option:selected').val() == 'full'){
			jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background', full_color);
			if(background_image !== '')
				jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-image', 'url('+background_image+')');

			jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-size', bg_size);
			jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-repeat', bg_repeat);
		}else{
			if(jQuery.trim(jQuery('#skin-dz-c').html())){
				jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background', full_color);
				if(background_image !== '')
					jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-image', 'url('+background_image+')');

				jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-size', bg_size);
				jQuery('.adamlabsgallery-cc .adamlabsgallery-element-cover').css('background-repeat', bg_repeat);
			}

			if(jQuery.trim(jQuery('#skin-dz-tl').html())){
				jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background', full_color);
				if(background_image !== '')
					jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background-image', 'url('+background_image+')');

				jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background-size', bg_size);
				jQuery('.adamlabsgallery-tc .adamlabsgallery-element-cover').css('background-repeat', bg_repeat);
			}

			if(jQuery.trim(jQuery('#skin-dz-br').html())){
				
				if(background_image !== '') {
					jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background-image', 'url('+background_image+')');
					jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background-size', bg_size);
					jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background-repeat', bg_repeat);
				}
				else {
					jQuery('.adamlabsgallery-bc .adamlabsgallery-element-cover').css('background', full_color);
				}

				
			}

		}

		jQuery('#adamlabsgallery-elements-container-grid ul').css('background', full_color);

		jQuery('.adamlabsgallery-pa-coverring').css('background', full_color);
		//jQuery('.adamlabsgallery-special .skin-dz-elements').css('background-color', full_color);
	}


	/**
	 * reset hover style to idle style
	 */
	jQuery('.drop-to-stylereset').click(function(){
		if(selected_layer == null) return false;

		var layer_id = selected_layer.data('id');

		for(var key in layers){
			if(layers[key].id == layer_id){ //element == layers[key].handle &&
				for(var handle in all_attributes){
					var copy_handle = handle.replace('-hover', '');
					if(all_attributes[handle]['style'] != 'hover') continue;

					if(all_attributes[handle]['type'] == 'checkbox'){
						layers[key]['settings'][handle] = layers[key]['settings'][copy_handle];
					}else if(all_attributes[handle]['type'] == 'select'){
						layers[key]['settings'][handle] = layers[key]['settings'][copy_handle];

						//special case
						if(handle == 'background-size'){
							delete(layers[key]['settings']['background-size-x-hover']);
							delete(layers[key]['settings']['background-size-y-hover']);

							if(layers[key]['settings'][copy_handle] == '%'){
								layers[key]['settings']['background-size-x-hover'] = layers[key]['settings']['element-background-size-x'];
								layers[key]['settings']['background-size-y-hover'] = layers[key]['settings']['element-background-size-y'];
							}
						}
					}else if(all_attributes[handle]['type'] == 'multi-text'){
						layers[key]['settings'][handle] = layers[key]['settings'][copy_handle];
					}else{
						layers[key]['settings'][handle] = layers[key]['settings'][copy_handle];
					}
					
					/* 2.1.6 */
					/*
					if(copy_handle == 'background-color') layers[key]['settings']['bg-alpha-hover'] = layers[key]['settings']['bg-alpha'];
					if(copy_handle == 'shadow-color') layers[key]['settings']['shadow-alpha-hover'] = layers[key]['settings']['shadow-alpha'];
					*/
					
					//check the unit of elements
					if(typeof layers[key]['settings'][copy_handle+'-unit'] !== 'undefined'){
						layers[key]['settings'][copy_handle+'-unit-hover'] = layers[key]['settings'][copy_handle+'-unit'];
					}
				}

				//redraw settings
				t.set_default_values(layer_id);
				t.set_setting_values();
				t.setting_has_changed();

				AdminEssentials.presetSelects();

				break;
			}
		}
	});

	/**
	 * init editor and activate functionality
	 */
	t.initGridEditor = function(doAction){

		/**
		 * warn the user if changes are made and not saved yet
		 */
		window.onbeforeunload = function (e) {
			if(save_needed){
				var message = adamlabsgallery_lang.leave_not_saved,
				e = e || window.event;
				// For IE and Firefox
				if (e) {
					e.returnValue = message;
				}

				// For Safari
				return message;
			}
		};

		jQuery('input[name="element-font-family"]').autocomplete({
			source: arr_font_types,
			minLength:0
		});

		jQuery('input[name="element-font-family-hover"]').autocomplete({
			source: arr_font_types,
			minLength:0
		});

		jQuery('input[name="element-source-meta"]').autocomplete({
			source: arr_meta_keys,
			minLength:0
		});

		jQuery('.ar-meta-field').autocomplete({
			source: arr_meta_keys,
			minLength:0
		});

		jQuery('input[name="element-meta-link"]').autocomplete({
			source: arr_meta_keys,
			minLength:0
		});

		jQuery('input[name="link-meta-link"]').autocomplete({
			source: arr_meta_keys,
			minLength:0
		});

		//handle autocomplete close
		jQuery('input[name="element-font-family"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="element-font-family"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="element-font-family"]').bind('autocompletechange', function() {
			t.setting_has_changed();
		});
		//handle autocomplete close
		jQuery('input[name="element-font-family-hover"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="element-font-family-hover"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="element-font-family-hover"]').bind('autocompletechange', function() {
			t.setting_has_changed();
		});

		//handle autocomplete close
		jQuery('.ar-meta-field').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('.ar-meta-field').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="element-source-meta"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="element-source-meta"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="element-source-meta"]').bind('autocompletechange', function() {
			t.setting_has_changed();
		});


		//handle autocomplete close
		jQuery('input[name="element-meta-link"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="element-meta-link"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="element-meta-link"]').bind('autocompletechange', function() {
			t.setting_has_changed();
		});

		jQuery('input[name="link-meta-link"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="link-meta-link"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		jQuery('input[name="link-meta-link"]').bind('autocompletechange', function() {
			t.setting_has_changed();
		});

		//open the list on right button
		jQuery('#button-open-font-family').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="element-font-family"]').data('is_open') == true)
				jQuery('input[name="element-font-family"]').autocomplete('close');
			else   //else open autocomplete
				jQuery('input[name="element-font-family"]').autocomplete('search', '').data('ui-autocomplete');
		});

		//open the list on right button
		jQuery('#button-open-font-family-hover').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="element-font-family-hover"]').data('is_open') == true)
				jQuery('input[name="element-font-family-hover"]').autocomplete('close');
			else   //else open autocomplete
				jQuery('input[name="element-font-family-hover"]').autocomplete('search', '').data('ui-autocomplete');
		});


		//open the list on right button
		jQuery('#button-open-meta-key').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="element-source-meta"]').data('is_open') == true)
				jQuery('input[name="element-source-meta"]').autocomplete('close');
			else   //else open autocomplete
				jQuery('input[name="element-source-meta"]').autocomplete('search', '').data('ui-autocomplete');
		});

		//open the list on right button
		jQuery('#button-open-link-meta-key').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="element-meta-link"]').data('is_open') == true)
				jQuery('input[name="element-meta-link"]').autocomplete('close');
			else   //else open autocomplete
				jQuery('input[name="element-meta-link"]').autocomplete('search', '').data('ui-autocomplete');
		});

		//open the list on right button
		jQuery('.ar-open-meta').click(function(event){
			if(jQuery(this).attr('disabled') != 'disabled'){
				event.stopPropagation();
				if(jQuery(this).closest('tr').find('.ar-meta-field').data('is_open') == true)
					jQuery(this).closest('tr').find('.ar-meta-field').autocomplete('close');
				else   //else open autocomplete
					jQuery(this).closest('tr').find('.ar-meta-field').autocomplete('search', '').data('ui-autocomplete');

				var elem = jQuery('.ui-autocomplete');
				for(var key in elem){
					if(elem.hasOwnProperty(key) && typeof elem[key] === 'object' && 'style' in elem[key]) {
						elem[key].style.setProperty('z-index', '999999', 'important');
					}
				}
			}
		});


		//open the list on right button
		jQuery('#button-open-link-link-meta-key').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="link-meta-link"]').data('is_open') == true)
				jQuery('input[name="link-meta-link"]').autocomplete('close');
			else   //else open autocomplete
				jQuery('input[name="link-meta-link"]').autocomplete('search', '').data('ui-autocomplete');
		});


		jQuery('body').click(function(){
			jQuery('input[name="element-font-family"]').autocomplete("close");
			jQuery('input[name="element-font-family-hover"]').autocomplete("close");
			jQuery('input[name="element-source-meta"]').autocomplete("close");
			jQuery('input[name="element-meta-link"]').autocomplete("close");
			jQuery('input[name="link-meta-link"]').autocomplete("close");
			jQuery('.ar-meta-field').autocomplete("close");
		});

		jQuery('#dialog-adamlabsgallery-fakeicon-in').click(function() {
			jQuery('#adamlabsgallery-fontello-icons-dialog-wrap').dialog("close");
			var cl = jQuery('#dialog-adamlabsgallery-fakeicon-in i').attr('class');
			jQuery('input[name="element-source-icon"]').val(cl);
			jQuery('#adamlabsgallery-preview-icon').html('<i class="'+cl+'"></i>');
		});

		jQuery('.adamlabsgallery-icon-chooser').on("mouseenter",function() {
			var e = jQuery(this);
			var df = jQuery('#dialog-adamlabsgallery-fakeicon-in');
			df.data('lastovered',e);
			var cl = e.attr("class").replace("adamlabsgallery-icon-chooser ","");
			df.html('<i class="'+cl+'"></i>');

			adamlabsgallerygs.TweenLite.fromTo(df,0.3,
								  { top:e.position().top-8,left:e.position().left-10,scale:0,transformPerspective:600,transformOrigin:"50% 50%",autoAlpha:0},
								  { scale:1,ease:adamlabsgallerygs.Power3.easeout,autoAlpha:1});
		});

		jQuery('#dialog-adamlabsgallery-fakeicon-in').on('mouseleave',function() {
			var df = jQuery('#dialog-adamlabsgallery-fakeicon-in');
		   	adamlabsgallerygs.TweenLite.fromTo(df.data('lastovered'),0.2,
		   					{ scale:1.3,transformPerspective:600,transformOrigin:"50% 50%"},
		   					{ scale:1,ease:adamlabsgallerygs.Power3.easeout});
		});

		jQuery('.adamlabsgallery-add-meta-to-textarea').click(function(){
			jQuery('#meta-dialog-wrap').dialog("close");
			var cl = jQuery(this).find('td:first-child').text();
			jQuery('textarea[name="element-source-text"]').val(jQuery('textarea[name="element-source-text"]').val()+cl);
		});

		jQuery('#show-fontello-dialog').click(function(){
			jQuery('#adamlabsgallery-fontello-icons-dialog-wrap').css('overflow','auto').dialog({
				modal:true,
				draggable:false,
				resizable:false,
				width:700,
				height:580,
				title:adamlabsgallery_lang.fontello_icons,
				closeOnEscape:true,
				dialogClass:'wp-dialog'
			});
		});


		jQuery('#adamlabsgallery-show-meta-keys-dialog').click(function(){
			jQuery('#meta-dialog-wrap').dialog({
				modal:true,
				draggable:false,
				resizable:false,
				width:632,
				height:565,
				closeOnEscape:true,
				dialogClass:'wp-dialog'
			});
		});


		jQuery('#adamlabsgallery-advanced-rules-edit').click(function(){
			if(selected_layer == null) return(false);

			jQuery('#advanced-rules-dialog-wrap').dialog({
				modal:true,
				draggable:false,
				resizable:false,
				width:690,
				height:565,
				closeOnEscape:false,
				buttons: [ { text: adamlabsgallery_lang.save_rules, click: function() {
					var rules = AdminEssentials.getFormParams('ar-form-wrap');

					var layer_id = selected_layer.data('id');
					for(var key in layers){
						if(layers[key].id == layer_id){
							layers[key]['settings']['adv-rules'] = rules;
							break;
						}
					}

					jQuery(this).dialog('close');
				} },
				{ text: adamlabsgallery_lang.reset_fields, click: function() {
					if(confirm(adamlabsgallery_lang.really_reset_fields)){
						document.getElementById('ar-form-wrap').reset();
						jQuery('.ar-type-field').each(function(){
							jQuery(this).change();
						});
						jQuery('.ar-operator-field').each(function(){
							jQuery(this).change();
						});
					}
				} },
				{ text: adamlabsgallery_lang.discard_changes, click: function() {
					if(confirm(adamlabsgallery_lang.really_discard_changes)){
						jQuery(this).dialog('close');
					}
				} } ],
				dialogClass:'wp-dialog'
			});

			//insert open part here. First clear all fields and then fill them if anything exists
			document.getElementById('ar-form-wrap').reset();
			jQuery('.ar-type-field').each(function(){
				jQuery(this).change();
			});
			jQuery('.ar-operator-field').each(function(){
				jQuery(this).change();
			});

			var layer_id = selected_layer.data('id');

			jQuery('.ar-show-field[value="show"]').attr('checked', 'checked');

			for(var key in layers){
				if(layers[key].id == layer_id){
					if(typeof layers[key]['settings']['adv-rules'] !== 'undefined'){
						//set the input fields corresponding to the values of adv-rules
						for(var rule in layers[key]['settings']['adv-rules']){
							if(rule == 'ar-show'){
								jQuery('.'+rule+'-field[value="'+layers[key]['settings']['adv-rules'][rule]+'"]').attr('checked', 'checked');
							}else{
								var nr = 0;
								jQuery('.'+rule+'-field').each(function(){
									jQuery(this).val(layers[key]['settings']['adv-rules'][rule][nr]);
									nr++;
								});
							}
						}
					}
					break;
				}
			}

			jQuery('.ar-type-field').each(function(){
				jQuery(this).change();
			});
			jQuery('.ar-operator-field').each(function(){
				jQuery(this).change();
			});

		});


		jQuery('body').on('change', '.ar-type-field', function(){
			if(jQuery(this).val() == 'meta'){
				jQuery(this).closest('tr').find('.ar-meta-field').attr('disabled', false);
				jQuery(this).closest('tr').find('.ar-open-meta').attr('disabled', false);
				jQuery(this).closest('tr').find('.ar-value-field').attr('disabled', false);
				jQuery(this).closest('tr').find('.ar-opt-meta').each(function(){
					jQuery(this).attr('disabled', false);
				});
			}else{
				jQuery(this).closest('tr').find('.ar-meta-field').attr('disabled', 'disabled');
				jQuery(this).closest('tr').find('.ar-open-meta').attr('disabled', 'disabled');
				jQuery(this).closest('tr').find('.ar-value-field').attr('disabled', 'disabled');
				jQuery(this).closest('tr').find('.ar-opt-meta').each(function(){
					jQuery(this).attr('disabled', 'disabled');
				});

				var cur_val = jQuery(this).closest('tr').find('.ar-operator-field option:selected').val();
				if(cur_val !== 'isset' && cur_val !== 'empty'){
					jQuery(this).closest('tr').find('.ar-operator-field option[value="isset"]').attr('selected', 'selected');
				}
			}


			//enable/disable the && || selects
			var cur_id = jQuery(this).attr('id');
			if(jQuery(this).val() == 'off'){
				jQuery('#'+cur_id+'-logic').attr('disabled', 'disabled');
			}else{
				jQuery('#'+cur_id+'-logic').attr('disabled', false);
			}
		});


		jQuery('body').on('change', '.ar-operator-field', function(){
			if(jQuery(this).val() == 'between'){
				jQuery(this).closest('tr').find('input[name="ar-value-2[]"]').attr('disabled', false);
			}else{
				jQuery(this).closest('tr').find('input[name="ar-value-2[]"]').attr('disabled', 'disabled');
			}
		});


		/**
		 * Initialize global css editor
		 */
		jQuery('#adamlabsgallery-global-css-dialog').click(function(){
			jQuery('#global-css-edit-dialog-wrap').dialog({
				modal:true,
				draggable:false,
				resizable:false,
				width:632,
				height:565,
				closeOnEscape:true,
				buttons: [ { text: adamlabsgallery_lang.apply_changes, click: function() {
					var data = {
						global_css: adamlabsgallery_codemirror_global_css.getValue()
					};

					AdminEssentials.ajaxRequest("update_custom_css", data, '.ui-button',function(response){
						t.set_global_inline_styles();
						jQuery('#global-css-edit-dialog-wrap').dialog('close');
					});

				} } ],
				dialogClass:'wp-dialog'
			});

			adamlabsgallery_codemirror_global_css.refresh();
		});


		adamlabsgallery_codemirror_global_css = CodeMirror.fromTextArea(document.getElementById("adamlabsgallery-global-css-editor"), {
			lineNumbers: true
		});

		adamlabsgallery_codemirror_global_css.setSize(632, 482);

		t.change_font_list = function(){

			var web_fonts = [];
			//Serif Fonts
			web_fonts.push('Georgia, serif');
			web_fonts.push('"Palatino Linotype", "Book Antiqua", Palatino, serif');
			web_fonts.push('"Times New Roman", Times, serif');
			//Sans-Serif Fonts
			web_fonts.push('Arial, Helvetica, sans-serif');
			web_fonts.push('"Arial Black", Gadget, sans-serif');
			web_fonts.push('"Comic Sans MS", cursive, sans-serif');
			web_fonts.push('Impact, Charcoal, sans-serif');
			web_fonts.push('"Lucida Sans Unicode", "Lucida Grande", sans-serif');
			web_fonts.push('Tahoma, Geneva, sans-serif');
			web_fonts.push('"Trebuchet MS", Helvetica, sans-serif');
			web_fonts.push('Verdana, Geneva, sans-serif');
			//Monospace Fonts
			web_fonts.push('"Courier New", Courier, monospace');
			web_fonts.push('"Lucida Console", Monaco, monospace');

			jQuery('.adamlabsgallery-google-font-link').remove();
			
			/* 2.1.5 */
			if(arr_init_font_types && arr_init_font_types.length > 0){
				for(var key in arr_init_font_types){
					var font = arr_init_font_types[key]['url'];
					
					if(typeof(font) !== 'undefined'){
						font = font.split('+').join(' ');
						font = font.split(':');
						web_fonts.push('"'+font[0]+'"');
					}
				}
			}

			arr_font_types = web_fonts;

			jQuery('input[name="element-font-family"]').autocomplete('option','source', arr_font_types);
			jQuery('input[name="element-font-family-hover"]').autocomplete('option','source', arr_font_types);
		}

		t.change_font_list();

		jQuery('input[name="choose-layout"]').change(function(){
			if(jQuery(this).val() == 'even'){
				jQuery('#adamlabsgallery-show-ratio').show();
				//jQuery('#adamlabsgallery-show-content').hide();
				jQuery('#skin-dz-m-wrap').hide();

				jQuery('select[name="show-content"] option').each(function(){
					if(jQuery(this).val() == 'bottom') jQuery(this).text(adamlabsgallery_lang.bottom_on_hover);
					if(jQuery(this).val() == 'top') jQuery(this).text(adamlabsgallery_lang.top_on_hover);
					if(jQuery(this).val() == 'none') jQuery(this).text(adamlabsgallery_lang.hidden);
				});

			}else{
				jQuery('#adamlabsgallery-show-ratio').hide();
				//jQuery('#adamlabsgallery-show-content').show();
				jQuery('#skin-dz-m-wrap').show();

				jQuery('select[name="show-content"] option').each(function(){
					if(jQuery(this).val() == 'bottom') jQuery(this).text(adamlabsgallery_lang.bottom);
					if(jQuery(this).val() == 'top') jQuery(this).text(adamlabsgallery_lang.top);
					if(jQuery(this).val() == 'none') jQuery(this).text(adamlabsgallery_lang.hide);
				});
			}

			jQuery('select[name="show-content"]').parent().find('.select_fake>span').text(jQuery('select[name="show-content"] option:selected').text());

			t.check_content_position();

			t.resize_item_skin_preview();

			//AdminEssentials.adamlabsgallery3dtakeCare(0);

		});

		jQuery('select[name="show-content"]').change(function(){
			t.check_content_position();
		});

		jQuery('select[name="show-content"] option:selected').change();


		jQuery('select[name="content-align"]').change(function(){
			jQuery('#skin-dz-m').css('textAlign', jQuery(this).val());
		});

		jQuery('select[name="content-align"] option:selected').change();


		jQuery('input[name="choose-layout"]:checked').change();

		jQuery('#element-settings-current-name').change(function(){
			var id = jQuery(this).val();

			//jQuery('#adamlabsgallery-styling-idle-hover-tab .adamlabsgallery-submenu li:first-child').click();

			t.select_layer(id);
		});

		jQuery('select[name="cover-animation-top"], select[name="cover-animation-bottom"], select[name="cover-animation-center"], select[name="cover-group-animation"], select[name="media-animation"], select[name="cover-animation-top-type"], select[name="cover-animation-bottom-type"], select[name="cover-animation-center-type"]').change(function(){
			t.add_animation_classes();
		});

		jQuery('select[name="cover-type"]').change(function(){
			if(jQuery(this).val() == 'full'){
				jQuery('#adamlabsgallery-cover-animation-top').css('display', 'none');
				jQuery('#adamlabsgallery-cover-animation-bottom').css('display', 'none');
				jQuery('#adamlabsgallery-cover-animation-center-hide').css('display', 'none');

				jQuery('#adamlabsgallery-dz-hover-wrap').addClass('adamlabsgallery-full-layout');

				jQuery('#skin-dz-c-wrap .adamlabsgallery-element-cover').prependTo('#skin-dz-c-wrap');
			}else{
				jQuery('#adamlabsgallery-cover-animation-top').css('display', 'block');
				jQuery('#adamlabsgallery-cover-animation-bottom').css('display', 'block');
				jQuery('#adamlabsgallery-cover-animation-center-hide').css('display', 'block');

				jQuery('#adamlabsgallery-dz-hover-wrap').removeClass('adamlabsgallery-full-layout');

				jQuery('#skin-dz-c-wrap .adamlabsgallery-element-cover').prependTo('#adamlabsgallery-element-centerme-c');
			}

			t.add_animation_classes();
			t.change_bg_color();
		});

		jQuery('select[name="cover-type"] option:selected').change();

		//t.check_tblr_fields();
		t.add_animation_classes();
		
		/* 2.1.6 */
		jQuery('select[name="element-transition"]').change(function() {
			
			t.check_no_transition(this.options[this.selectedIndex].value);
			
		});
		
		t.check_no_transition = function(cur_val) {

			var display = cur_val !== 'none' ? 'show' : 'hide';
			jQuery('.adamlabsgallery-hideable-no-transition')[display]();
			
		}
		
		/* 2.1.6 */
		/*
		jQuery('select[name="element-transition-type"]').change(function() {
			t.set_transition_type_visibility(jQuery('select[name="element-transition-type"] option:selected').val());
		});

		t.set_transition_type_visibility = function(cur_val){
			switch(cur_val){
				case 'always':
				    jQuery('#adamlabsgallery-element-transition-drop').hide();
				    jQuery('#groupanimwarning').show();
				break;
				case 'in':
				case 'out':
				default:
				    jQuery('#adamlabsgallery-element-transition-drop').show();
				    jQuery('#groupanimwarning').hide();
				break;
			}
		}
		*/

		jQuery('select[name="element-align"]').change(function(){
			switch(jQuery('select[name="element-align"] option:selected').val()){
				case 't_l':
					jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.top);
					jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.left);
					break;
				case 't_r':
					jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.top);
					jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.right);
					break;
				case 'b_l':
					jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.bottom);
					jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.left);
					break;
				case 'b_r':
					jQuery('#adamlabsgallery-t_b_align').text(adamlabsgallery_lang.bottom);
					jQuery('#adamlabsgallery-l_r_align').text(adamlabsgallery_lang.right);
					break;
			}

			t.calculate_absolute_positioning(selected_layer);
		});

		jQuery('select[name="element-absolute-unit"]').change(function(){
			t.calculate_absolute_positioning(selected_layer);
		});

		//main drag & drop
		jQuery("#skin-dz-tl, #skin-dz-br, #skin-dz-c, #skin-dz-m").sortable({
			connectWith: "#skin-dz-tl,#skin-dz-br,#skin-dz-c,#skin-dz-m",
			revert: true,
			tolerance:"pointer",
			distance: 15,
			grid:[2,2],
			placeholder: "adamlabsgallery-state-highlight",
			stack:"#adamlabsgallery-dz-hover-wrap",
			cancel: ".dropzonetext, .adamlabsgallery-element-cover",
			opacity:0.8,
			zIndex:1000,
			sort: function(){
			   		AdminEssentials.whileDropOrSort('.skin-dz-elements.ui-sortable-helper');
					t.redraw_container_height();
					AdminEssentials.atDropStop(1);
				},
			update: function(event, ui) {

			},
			beforeStop: function(event,ui) {

			},
			stop: function(event, ui){
				AdminEssentials.atDropStop();
				//jQuery('#adamlabsgallery-styling-idle-hover-tab .adamlabsgallery-submenu li:first-child').click();
				if(ui.item.data('id') !== undefined){
					t.select_layer(ui.item.data('id'));
				}else{
					selected_layer = ui.item;
				}

				selected_layer.removeClass('ui-draggable');

				t.update_layers();
				t.change_bg_color();
				t.redraw_container_height();

				t.setting_has_changed();
			}
		});

		jQuery('body').on("click",'#layertotop',function() {
			var el = jQuery('.skin-dz-elements.selected');
			if (!el.prev().hasClass("adamlabsgallery-element-cover"))
				el.insertBefore(el.prev());
			t.update_layers();
			t.change_bg_color();
			t.redraw_container_height();
			t.setting_has_changed();

		});

		jQuery('body').on("click",'#layertobottom',function() {
			var el = jQuery('.skin-dz-elements.selected');
			el.insertAfter(el.next());
			t.update_layers();
			t.change_bg_color();
			t.redraw_container_height();
			t.setting_has_changed();

		});


		t.set_default_elements_draggable();

		jQuery("body").on('click', '#adamlabsgallery-dz-hover-wrap .skin-dz-elements', function(){
			if(selected_layer != null){
				if(selected_layer.data('id') == jQuery(this).data('id')) return true;

				//trigger setting has changed to set values one last time before the element change
				t.setting_has_changed();
			}

			//jQuery('#adamlabsgallery-styling-idle-hover-tab .adamlabsgallery-submenu li:first-child').click();
			t.select_layer(jQuery(this).data('id'));
		});

		jQuery('#settings-dz-elements-wrapper').tabs();
		var chtimer;

		var options1 = {
			  color:true,
			  change:function(event,ui) {
			  	  clearTimeout(chtimer);
				  chtimer = setTimeout(function() {
				  	t.setting_has_changed();
				  },10);
			  },
			  wrapper:'<span class="rev-colorpickerspan"></span>'  
		}
		
		/* 2.1.6 options modified to be compatible with both */
		var clrPicker = typeof RevColor !== 'undefined' ? 'AdamLabsColorPicker' : 'wpColorPicker';
		
		jQuery('#element-background-color')[clrPicker](options1);
		jQuery('#element-color')[clrPicker](options1);
		jQuery('#element-border-color')[clrPicker](options1);
		jQuery('#element-shadow-color')[clrPicker](options1);
		jQuery('#element-background-color-hover')[clrPicker](options1);
		jQuery('#element-color-hover')[clrPicker](options1);
		jQuery('#element-border-color-hover')[clrPicker](options1);
		jQuery('#element-shadow-color-hover')[clrPicker](options1);

		var options2 = {
			  color:true,
			  change:function(event,ui) {
			  	  clearTimeout(chtimer);
				  chtimer = setTimeout(function() {
				  	t.container_setting_has_changed();
				  },10);
			  },
			  wrapper:'<span class="rev-colorpickerspan"></span>'  
		}
		
		var options3 = jQuery.extend(true, {}, options2);
		options3.wrapper = '<span class="rev-colorpickerspan cover-animation-color-wrap"></span>';

		jQuery('#full-border-color')[clrPicker](options2);
		jQuery('#full-bg-color')[clrPicker](options2);
		jQuery('#content-border-color')[clrPicker](options2);
		jQuery('#content-shadow-color')[clrPicker](options2);
		jQuery('#content-bg-color')[clrPicker](options2);
		jQuery('.cover-animation-color')[clrPicker](options3);

		jQuery('#container-background-color')[clrPicker]({
			color:true,
			change: function(event, ui) {
				if(typeof RevColor === 'undefined') jQuery(this).val(ui.color.toString());
				t.change_bg_color();
			},
			wrapper:'<span class="rev-colorpickerspan"></span>'  
		});

		jQuery('#container-background-color, input[name="container-background-color-into"]').change(function(){
			t.change_bg_color();
		});

		jQuery('#container-background-color').change();

		t.init_slider_elements();

		/**
		 * Setting of inserted element has been changed
		 */
		t.setting_has_changed = function(){

			t.get_setting_values();
			t.set_setting_styles();
			t.set_setting_caption();
			t.set_setting_attributes();
			t.change_bg_color();
			t.propagate_element_selector();
			t.redraw_container_height();
			AdminEssentials.atDropStop();

		}


		/**
		 * Setting of container has changed
		 */
		t.container_setting_has_changed = function(){
			t.set_full_styles();
			t.set_content_styles();

			t.redraw_container_height();
			t.redraw_container_width();
			AdminEssentials.atDropStop();
			t.set_shadow_on_container();
			t.change_bg_color();
		}

		/**
		 * Set settings of the full container
		 */
		t.set_full_styles = function(){
			var me = jQuery('#adamlabsgallery-dz-hover-wrap');
			var border = [];
			var radius = [];
			var padding = '';

			jQuery('input[name="full-border[]"]').each(function(){
			   border.push(parseInt(jQuery(this).val()));
			});

			jQuery('input[name="full-border-radius[]"]').each(function(){
			   radius.push(parseInt(jQuery(this).val()));
			});

			jQuery('input[name="full-padding[]"]').each(function(){
			   padding += jQuery(this).val()+'px ';
			});
			
			me.css('borderTopWidth', border[0]+'px');
			me.css('borderRightWidth', border[1]+'px');
			me.css('borderBottomWidth', border[2]+'px');
			me.css('borderLeftWidth', border[3]+'px');
			
			var borderType = jQuery('select[name="full-border-radius-type"] option:selected').val();
			me.css('borderTopLeftRadius', radius[0]+borderType);
			me.css('borderTopRightRadius', radius[1]+borderType);
			me.css('borderBottomRightRadius', radius[2]+borderType);
			me.css('borderBottomLeftRadius', radius[3]+borderType);

			me.css('padding', padding);
			
			/* 2.1.6 */
			var fullBgColor = jQuery('#full-bg-color'),
				fullBorderColor = jQuery('#full-border-color');
				
			me.css('background', fullBgColor.attr('data-color') || fullBgColor.val());
			me.css('borderColor', fullBorderColor.attr('data-color') || fullBorderColor.val());
			me.css('borderStyle', jQuery('select[name="full-border-style"] option:selected').val());
			
		}

		/**
		 * Set shadow depending on settings
		 */
		t.set_shadow_on_container = function(){
			var set_on = jQuery('select[name="all-shadow-used"] option:selected').val();
			var cb = jQuery('#adamlabsgallery-dz-hover-wrap'); //both
			var cc = jQuery('#skin-dz-m-wrap'); //content
			var cm = jQuery('#skin-dz-wrapper'); //media
			var cv = jQuery('.adamlabsgallery-element-cover'); // cover
			var shadow = '';

			//reset on all elements
			cb.css('-moz-box-shadow', '').css('-webkit-box-shadow', '').css('box-shadow', '');
			cc.css('-moz-box-shadow', '').css('-webkit-box-shadow', '').css('box-shadow', '');
			cm.css('-moz-box-shadow', '').css('-webkit-box-shadow', '').css('box-shadow', '');
			cv.css('-moz-box-shadow', '').css('-webkit-box-shadow', '').css('box-shadow', '');
			
			if(set_on == 'none') return true;
			var coverType = jQuery('select[name="cover-type"]').val();
			
			if(coverType === 'full') cv = cv.filter(function() {return jQuery(this).parent().attr('id') === 'skin-dz-c-wrap';});
			if(set_on === 'cover' || jQuery('#content-shadow-inset:checked').length) shadow += 'inset ';
			
			jQuery('input[name="content-box-shadow[]"]').each(function(){
			   shadow += jQuery(this).val()+'px ';
			});
			
			var shadow_color = jQuery('#content-shadow-color');
			shadow_color = shadow_color.attr('data-color') || shadow_color.val();
			
			/* 2.1.6 */
			/*
			var shadow_transparency = jQuery('input[name="content-shadow-alpha"]').val();
			shadow_color = AdminEssentials.hex_to_rgba(shadow_color, shadow_transparency, true);
			*/
			
			shadow += shadow_color;

			if(set_on == 'both'){
				cb.css('-moz-box-shadow', shadow).css('-webkit-box-shadow', shadow).css('box-shadow', shadow);
			}else if(set_on == 'content'){
				cc.css('-moz-box-shadow', shadow).css('-webkit-box-shadow', shadow).css('box-shadow', shadow);
			}else if(set_on == 'media'){
				cm.css('-moz-box-shadow', shadow).css('-webkit-box-shadow', shadow).css('box-shadow', shadow);
			}else if(set_on == 'cover'){
				cv.css('-moz-box-shadow', shadow).css('-webkit-box-shadow', shadow).css('box-shadow', shadow);
			}
		}

		/**
		 * Set settings of content container
		 */
		t.set_content_styles = function(){
			var me = jQuery('#skin-dz-m');
			var border = [];
			var radius = [];
			var padding = '';

			jQuery('input[name="content-border[]"]').each(function(){
			   border.push(parseInt(jQuery(this).val()));
			});

			jQuery('input[name="content-border-radius[]"]').each(function(){
			   radius.push(parseInt(jQuery(this).val()));
			});

			jQuery('input[name="content-padding[]"]').each(function(){
			   padding += jQuery(this).val()+'px ';
			});

			me.css('borderTopWidth', border[0]+'px');
			me.css('borderRightWidth', border[1]+'px');
			me.css('borderBottomWidth', border[2]+'px');
			me.css('borderLeftWidth', border[3]+'px');
			
			var borderType = jQuery('select[name="content-border-radius-type"] option:selected').val();		
			me.css('borderTopLeftRadius', radius[0]+borderType);
			me.css('borderTopRightRadius', radius[1]+borderType);
			me.css('borderBottomRightRadius', radius[2]+borderType);
			me.css('borderBottomLeftRadius', radius[3]+borderType);
			me.css('padding', padding);
			
			/* 2.1.6 */
			var contentBgColor = jQuery('#content-bg-color'),
				contentBorderColor = jQuery('#content-border-color');
			
			me.css('background', contentBgColor.attr('data-color') || contentBgColor.val());
			me.css('borderColor', contentBorderColor.attr('data-color') || contentBorderColor.val());
			me.css('borderStyle', jQuery('select[name="content-border-style"] option:selected').val());
			
		}

		jQuery('body').on('click', '#element-delete-button', function(){
			if(selected_layer == null) return(false);
			
			if(confirm(adamlabsgallery_lang.delete_this_element)){
				t.delete_layer(selected_layer);
			}
		});


		jQuery('select[name="element-source"]').change(function(){

			jQuery('.elements-select-wrap').hide().siblings('.select_fake').hide();

			jQuery('.adamlabsgallery-cat-tag-settings').hide();
			jQuery('#adamlabsgallery-source-functonality-wrap').hide().find('.select_fake').hide();
			jQuery('#adamlabsgallery-source-meta-wrap').hide();
			jQuery("#adamlabsgallery-source-taxonomy-wrap").hide();

			switch(jQuery(this).val()){
				case 'event':
					jQuery('#adamlabsgallery-source-element-drops').show();
					jQuery('select[name="element-source-event"]').show().siblings('.select_fake').show();
					jQuery('#adamlabsgallery-source-limit-wrap').show();
				break;
				case 'woocommerce':
					jQuery('#adamlabsgallery-source-element-drops').show();
					jQuery('select[name="element-source-woocommerce"]').show().siblings('.select_fake').show()
					jQuery('#adamlabsgallery-source-limit-wrap').show();
					jQuery('select[name="element-source-woocommerce"]').change();
				break;
				case 'icon':
					 jQuery('#adamlabsgallery-source-element-drops').hide();
					jQuery('#adamlabsgallery-source-icon-wrap').show();
				break;
				case 'text':
					jQuery('#adamlabsgallery-source-element-drops').hide();
					
					var lfound = false;
					for(var key in layers){
						if(layers[key].id == selected_layer.data('id')){
							lfound = true;
							var sellayer = layers[key];
							if(typeof sellayer !== undefined && sellayer !== null && typeof sellayer['settings']['special'] !== undefined && sellayer['settings']['special'] == 'true' && typeof sellayer['settings']['special-type'] !== undefined && sellayer['settings']['special-type'] == 'blank-element'){
								jQuery('#adamlabsgallery-source-text-style-disable-wrap').hide();
							}else{
								jQuery('#adamlabsgallery-source-text-style-disable-wrap').show();
							}
						}
					}
					
					if(lfound === false){
						jQuery('#adamlabsgallery-source-text-style-disable-wrap').show();
					}
					jQuery('#adamlabsgallery-source-text-wrap').show();
				break;
				case 'post':
				default:
					jQuery('#adamlabsgallery-source-element-drops').show();
					
					var val = jQuery('select[name="element-source-post"]').val();
					if(val.search(/cat_list|tag_list/) === -1) {
						jQuery('#adamlabsgallery-source-limit-wrap').show();
					}
					else {
						jQuery('#adamlabsgallery-source-limit-wrap').hide();
					}

					if(val == "taxonomy" ){
						jQuery("#adamlabsgallery-source-taxonomy-wrap").show();
					}
					
					jQuery('#adamlabsgallery-source-functonality-wrap').show();
					jQuery('select[name="element-source-post"]').show().siblings('.select_fake').show();
					jQuery('select[name="element-source-function"]').show().siblings('.select_fake').show();
					jQuery('select[name="element-source-post"]').change();

			}
		});

		jQuery('select[name="element-source-post"]').change(function(){
			var source_val = jQuery(this).val();
			if(source_val == 'cat_list' || source_val == 'tag_list' || source_val == 'taxonomy'){
				jQuery('.adamlabsgallery-cat-tag-settings').show();
				jQuery('#adamlabsgallery-source-limit-wrap').hide();
				jQuery('#adamlabsgallery-source-functonality-wrap').show().find('.select_fake').show();
			}else{
				jQuery('.adamlabsgallery-cat-tag-settings').hide();
				jQuery('#adamlabsgallery-source-limit-wrap').show();
				jQuery('#adamlabsgallery-source-functonality-wrap').hide().find('.select_fake').hide();
			}
			if(source_val == 'meta'){
				jQuery('#adamlabsgallery-source-meta-wrap').show();
			}
			else{
				jQuery('#adamlabsgallery-source-meta-wrap').hide();
			}

			if(source_val == 'taxonomy'){
				jQuery("#adamlabsgallery-source-taxonomy-wrap").show();
			}
			else {
				jQuery("#adamlabsgallery-source-taxonomy-wrap").hide();
			}
		});

		jQuery('select[name="element-source-woocommerce"]').change(function(){
			var source_val = jQuery(this).val();
			if(source_val == 'wc_categories'){
				jQuery('.adamlabsgallery-cat-tag-settings').show();
				jQuery('#adamlabsgallery-source-functonality-wrap').show().find('.select_fake').show();
			}else{
				jQuery('.adamlabsgallery-cat-tag-settings').hide();
				jQuery('#adamlabsgallery-source-functonality-wrap').hide().find('.select_fake').hide();
			}

		});

		jQuery('select[name="element-link-type"]').change(function(){
			jQuery('#adamlabsgallery-element-post-url-wrap').hide();
			jQuery('#adamlabsgallery-element-post-meta-wrap').hide();
			jQuery('#adamlabsgallery-element-post-javascript-wrap').hide();
			jQuery('#adamlabsgallery-element-link-details-wrap').show();
			jQuery('#adamlabsgallery-element-facebook-wrap').hide();
			jQuery('#adamlabsgallery-element-gplus-wrap').hide();
			jQuery('#adamlabsgallery-element-pinterest-wrap').hide();
			jQuery('#adamlabsgallery-element-twitter-wrap').hide();

			switch(jQuery(this).val()){
				case 'post':
				case 'lightbox':
				case 'embedded_media':
				break;
				case 'url':
					jQuery('#adamlabsgallery-element-post-url-wrap').show();
				break;
				case 'meta':
					jQuery('#adamlabsgallery-element-post-meta-wrap').show();
				break;
				case 'javascript':
					jQuery('#adamlabsgallery-element-post-javascript-wrap').show();
					break;
				case 'sharefacebook':
					jQuery('#adamlabsgallery-element-link-details-wrap').hide();
					jQuery('#adamlabsgallery-element-facebook-wrap').show();
					if(jQuery('select[name="element-facebook-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-facebook_link_custom").show();
					else jQuery(".adamlabsgallery-element-facebook_link_custom").hide();
					break;
				case 'sharegplus':
					jQuery('#adamlabsgallery-element-link-details-wrap').hide();
					jQuery('#adamlabsgallery-element-gplus-wrap').show();
					if(jQuery('select[name="element-gplus-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-gplus_link_custom").show();
					else jQuery(".adamlabsgallery-element-gplus_link_custom").hide();
					break;
				case 'sharepinterest':
					jQuery('#adamlabsgallery-element-link-details-wrap').hide();
					jQuery('#adamlabsgallery-element-pinterest-wrap').show();
					if(jQuery('select[name="element-pinterest-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-pinterest_link_custom").show();
					else jQuery(".adamlabsgallery-element-pinterest_link_custom").hide();
					break;
				case 'sharetwitter':
					jQuery('#adamlabsgallery-element-link-details-wrap').hide();
					jQuery('#adamlabsgallery-element-twitter-wrap').show();
					if(jQuery('select[name="element-twitter-sharing-link"]').val()=="custom") jQuery(".adamlabsgallery-element-twitter_link_custom").show();
					else jQuery(".adamlabsgallery-element-twitter_link_custom").hide();
					break;
				default:
					break;

			}
		});


		jQuery('select[name="link-link-type"]').change(function(){
			jQuery('#adamlabsgallery-link-post-url-wrap').hide();
			jQuery('#adamlabsgallery-link-post-meta-wrap').hide();
			jQuery('#adamlabsgallery-link-post-javascript-wrap').hide();

			switch(jQuery(this).val()){
				case 'post':
				case 'lightbox':
				case 'embedded_media':
				break;
				case 'url':
					jQuery('#adamlabsgallery-link-post-url-wrap').show();
				break;
				case 'meta':
					jQuery('#adamlabsgallery-link-post-meta-wrap').show();
				break;
				case 'javascript':
					jQuery('#adamlabsgallery-link-post-javascript-wrap').show();
					break;
				default:
					break;

			}
		});

		jQuery('select[name="link-link-type"] option:selected').change();

		jQuery('select[name="link-set-to"]').change(function(){
			if(jQuery(this).val() !== 'none')
				jQuery('.add-link-to-wrapper').show();
			else
				jQuery('.add-link-to-wrapper').hide();

		});
		jQuery('select[name="link-set-to"] option:selected').change();

		jQuery('select[name="element-background-size"]').change(function(){
			jQuery('#background-size-percent-wrap').hide();

			if(jQuery(this).val() == '%'){
				jQuery('#background-size-percent-wrap').css('display', 'inline-block');
			}

		});

		jQuery('select[name="element-background-size"] option:selected').click();

		jQuery('input[name="choose-layout"]:checked').change();

		jQuery('select[name="element-display"]').change(function(){
			jQuery('#element-text-align-wrap').hide();
			jQuery('#element-float-wrap').hide();
			switch(jQuery(this).val()){
				case 'block':
					jQuery('#element-text-align-wrap').show();
					jQuery('#element-float-wrap option[value="none"]').attr("selected","selected");
					break;
				case 'inline-block':
					jQuery('#element-float-wrap').show();
					jQuery('#element-text-align-wrap option[value="center"]').attr("selected","selected");
				break;
			}
		});

		jQuery('input[name="element-display"]:checked').change();

		jQuery('#adamlabsgallery-styling-idle-hover-tab .adamlabsgallery-submenu li').click(function(){
			var eg_idle = jQuery('#adamlabsgallery-style-idle');
			var eg_hover = jQuery('#adamlabsgallery-style-hover');
			var to_show = jQuery(this).data('toshow');

			eg_idle.hide();
			eg_hover.hide();

			jQuery('#'+to_show).show();

			jQuery('#'+to_show+' .adamlabsgallery-small-vertical-menu li:first-child').click();

			jQuery('#adamlabsgallery-styling-idle-hover-tab .adamlabsgallery-submenu li').removeClass('selected-submenu-setting');

			jQuery(this).addClass('selected-submenu-setting');

		});


		jQuery('#adamlabsgallery-lc-spaces .adamlabsgallery-submenu li').click(function(){
			var eg_full = jQuery('#adamlabsgallery-style-full');
			var eg_content = jQuery('#adamlabsgallery-style-content');
			var to_show = jQuery(this).data('toshow');

			eg_full.hide();
			eg_content.hide();

			jQuery('#'+to_show).show();

			jQuery('#adamlabsgallery-lc-spaces .adamlabsgallery-submenu li').removeClass('selected-submenu-setting');

			jQuery(this).addClass('selected-submenu-setting');

		});


		jQuery('#adamlabsgallery-btn-save-grid-editor, #adamlabsgallery-global-change').click(function(){

			var item_name = jQuery('input[name="item-skin-name"]').val();
			//var item_name = prompt(adamlabsgallery_lang.please_enter_unique_item_name);
			if(item_name.length < 2){
				alert(adamlabsgallery_lang.item_name_too_short);
				return false;
			}

			var my_layers = t.output_setting_data();

			my_layers = JSON.stringify(my_layers);

			var data = {
				name: jQuery.trim(item_name),
				params: AdminEssentials.getFormParams('adamlabsgallery-form-item-skin-layout-settings'),
				layers: my_layers
			};

			if(doAction == 'update_item_skin'){
				data.id = jQuery('input[name="adamlabsgallery-item-skin-id"]').val();
			}

			AdminEssentials.ajaxRequest("update_create_item_skin", data, '#adamlabsgallery-btn-save-grid-editor, #adamlabsgallery-global-change');

			save_needed = false;
		});


		jQuery('input[name="item-skin-name"]').keyup(function(){
			jQuery('#adamlabsgallery-item-skin-slug').text(AdminEssentials.sanitize_input(jQuery(this).val()));
			if(selected_layer !== null){
				var nostyle = (jQuery('select[name="element-source"] option:selected').val() == 'text' && jQuery('input[name="element-source-text-style-disable"]').attr('checked') == 'checked') ? '-nostyle' : '';
				jQuery('.adamlabsgallery-element-class-setter').text(adamlabsgallery_lang.class_name+' .adamlabsgallery-'+jQuery('#adamlabsgallery-item-skin-slug').text()+nostyle+'-element-'+selected_layer.data('id'));
			}
		});

		jQuery('input[name="item-skin-name"]').keyup();

		jQuery('#element-save-as-button').click(function(){
			var use_name = (jQuery('.skin-dz-elements.selected').data('orighandle') !== undefined) ? jQuery('.skin-dz-elements.selected').data('orighandle') : jQuery('.skin-dz-elements.selected').data('handle');
			var item_name = prompt(adamlabsgallery_lang.please_enter_unique_element_name, use_name);
			if(item_name == null) return false;

			if(item_name.length < 2){
				alert(adamlabsgallery_lang.item_name_too_short);
				return false;
			}

			AdminEssentials.ajaxRequest("check_item_element_existence", {name: jQuery.trim(item_name)}, '.save-wrap-settings, #dz-delete',function(response){
				var do_query = false;

				if(typeof response.data['existence'] !== undefined){

					if(response.data['existence'] == 'true'){
						if(confirm(adamlabsgallery_lang.element_name_exists_do_overwrite)){
							do_query = true;
						}
					}else if(response.data['existence'] == 'false'){
						do_query = true;
					}else{
						AdminEssentials.showErrorMessage(response.data['existence'], '');
					}
				}

				if(do_query){
					var data = {
						settings: AdminEssentials.getFormParams('adamlabsgallery-item-element-settings-wrap'),
						name: jQuery.trim(item_name)
					};

					AdminEssentials.ajaxRequest("update_create_item_element", data, '.save-wrap-settings, #dz-delete',function(response){
						t.refresh_predefined_elements();
						AdminEssentials.showWaitAMinute({fadeOut:300});
					});
				}else{
					AdminEssentials.showInfoMessage(adamlabsgallery_lang.element_was_not_changed);
					AdminEssentials.showWaitAMinute({fadeOut:300});
				}
			});
		});

		t.resize_item_skin_preview();

		var basicEntries = [{ width:400,amount:3},
							{ width:480,amount:4},
							{ width:620,amount:5},
							{ width:768,amount:5},
							{ width:992,amount:6},
							{ width:1200,amount:7},
							{ width:1400,amount:7}];
		
		essapi = jQuery('#adamlabsgallery-elements-container-grid').adamlabsgallery({

				layout:"even",
				row:3,
				column:5,
				space:5,
				aspectratio:"4:3",
				startAnimation:"none",
				startAnimationSpeed: 0,
				startAnimationDelay: 0,
				pageAnimation:"fade",
				overflowoffset:20,
				animSpeed:500,
				animDelay:"on",
				delayBasic:1,
				responsiveEntries: basicEntries
		});

		jQuery('#adamlabsgallery-preview-item-skin').click(function(){
			AdminEssentials.playElementAnimation();
		});

		jQuery('#adamlabsgallery-preview-stop-item-skin').click(function(){
			AdminEssentials.stopElementAnimation();
		});

		//AdminEssentials.adamlabsgallery3dtakeCare();

		t.propagate_default_element_styles();

		AdminEssentials.atDropStop();

		AdminEssentials.callOnChange('#settings-dz-elements-wrapper', t.setting_has_changed, 0);
		AdminEssentials.callOnChange('.adamlabsgallery-lc-menu-wrapper', t.container_setting_has_changed, 0);

		t.container_setting_has_changed();

		AdminEssentials.callOnChange('#adamlabsgallery-curname-event-wrap');

		AdminEssentials.presetSelects();

		jQuery('#cover-background-image-wrap').click(function(event){
			event.preventDefault();

			// Media Library params
			var frame = wp.media({
				title: adamlabsgallery_lang.choose_image,
				multiple: false,
				library: {type: 'image'},
				button: {text: adamlabsgallery_lang.choose_image}
			});

			frame.on('select',function(){
				var objSettings = frame.state().get('selection').first().toJSON();

				var selection = frame.state().get('selection');
				var arrImages = [];

				t.select_cover_image(objSettings.url,objSettings.id);
			});

			//open
			frame.open();
		});

		jQuery('#remove-cover-background-image-wrap').click(function(){
			jQuery('input[name="cover-background-image"]').val('0');
			jQuery('input[name="cover-background-image-url"]').val('');

			jQuery('#cover-background-image-wrap').css('background-image', '');

			t.change_bg_color();
		});


		t.select_cover_image = function(image_url,image_id){
			jQuery('input[name="cover-background-image"]').val(image_id);
			jQuery('input[name="cover-background-image-url"]').val(image_url);

			jQuery('#cover-background-image-wrap').css('background-image', 'url('+image_url+')');

			t.change_bg_color();
		}
		
		/* 2.1.6 */
		jQuery('#element-hover-image').on('change', function() {
			
			var display = this.checked ? 'block' : 'none';
				hov = document.getElementById('adamlabsgallery-hover-img-animation');
				
			if(hov) hov.style.display = display;
			
		});
		
		jQuery(document).on('keydown', function(event) {
			 if (event.ctrlKey || event.metaKey) {
				switch (String.fromCharCode(event.which).toLowerCase()) {
					case 's':
						var saver = jQuery('#adamlabsgallery-global-change');
						if(saver.length) {
							event.preventDefault();
							saver.click();
						}
					break;
				 }
			}
		});
		
		/* 2.2.6 */
		jQuery('.cover-animation-select').change(function() {
			
			var level = this.name.replace('cover-animation-', ''),
				method = this.value.search(/line|spiral|circle/) === -1 ? 'addClass' : 'removeClass';
			
			jQuery('#cover-animation-color-' + level).closest('.cover-animation-color-wrap')[method]('cover-animation-color-hide');
			
		}).change();
		
		/* 2.2.6 */
		jQuery('#cover-type').change(function() {
			
			jQuery('#all-shadow-used').change();
			
		});
		
		/* 2.2.6 */
		jQuery('#all-shadow-used').change(function() {
			
			var isCover = this.value === 'cover',
				method = !isCover ? 'removeClass' : jQuery('#cover-type').val() === 'full' ? 'removeClass' : 'addClass';
				
			jQuery('#content-box-shadow-hover')[method]('adamlabsgallery-hide-option');
			
			method = !isCover ? 'removeClass' : 'addClass';
			jQuery('#content-box-shadow-inset')[method]('adamlabsgallery-hide-option');
			
			
		}).change();
		
		/* 2.2.6 */
		function onLetterChange() {
			
			letterSpacings.off('.letterspacing');
			var val = this.value.replace('px', '');
			
			if(!isNaN(val)) {
				
				this.value = val + 'px';
				jQuery(this).change();
				
			}
			else {
				
				if(val !== 'normal' && val !== 'inherit' && val !== 'initial') {
				
					this.value = 'normal';
					jQuery(this).change();
				
				}
			
			}
			
			letterSpacings.on('change.letterspacing', onLetterChange);
			
		}
		
		/* 2.2.6 */
		var letterSpacings = jQuery('.letter-spacing').on('change.letterspacing', onLetterChange);
		
		/* 2.2.6 */
		jQuery('#media-animation').change(function() {
			
			var method = this.value.search('blur') === -1 ? 'addClass' : 'removeClass';
			jQuery('#media-animation-blur')[method]('adamlabsgallery-hide-option');
			
		}).change();
		
	}

	/*
	 * Sort layers by order function
	 */
	t.sortByOrder = function(a, b){
		var a = parseInt(a.order.toLowerCase());
		var b = parseInt(b.order.toLowerCase());
		return ((a < b) ? -1 : ((a > b) ? 1 : 0));
	}

	/*
	 * Resize the preview
	 */
	t.resize_item_skin_preview = function(){
		var cur_width = jQuery('#skin-dz-wrapper').width();

		if(jQuery('input[name="choose-layout"]:checked').val() == 'even'){
			var x_ratio = jQuery('input[name="element-x-ratio"]').val();
			var y_ratio = jQuery('input[name="element-y-ratio"]').val();
		}else{
			var x_ratio = 1;
			var y_ratio = 1;
		}
		jQuery('#skin-dz-wrapper').css('height', cur_width / x_ratio * y_ratio);
		jQuery('#skin-dz-media-bg').css('height', cur_width / x_ratio * y_ratio);
		jQuery('#skin-dz-media-bg-wrapper').css('height', cur_width / x_ratio * y_ratio);

	}

	/*
	 * initiate slider elements from editor
	 */
	t.init_slider_elements = function(){
		for(var handle in all_attributes){
			switch(all_attributes[handle]['type']){
				case 'text-slider':
				case 'slider':
					if(handle == 'shadow') continue;
					if(parseInt(all_attributes[handle]['values']['default']));
					jQuery('#element-'+handle).slider({
						value: (isNaN(parseInt(all_attributes[handle]['values']['default']))) ? 0 : parseInt(all_attributes[handle]['values']['default']),
						min: parseInt(all_attributes[handle]['values']['min']),
						max: parseInt(all_attributes[handle]['values']['max']),
						step: parseInt(all_attributes[handle]['values']['step']),
						slide: function(event, ui){
							jQuery('input[name="'+jQuery(this).attr('id')+'"]').val(ui.value);
							jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
						}

					});
					var neww = parseInt(jQuery('#element-'+handle+' .ui-slider-handle').css('left'),0);
					jQuery('#element-'+handle).prepend('<span class="adamlabsgallery-pre-slider"></span>');
					jQuery('#element-'+handle+' .adamlabsgallery-pre-slider').css({width:neww});
				break;
			}
		}


		var sliders = [
			{name: 'x-ratio', value: -1, min: 1, max: 16},
			{name: 'y-ratio', value: -1, min: 1, max: 16}
			/* 2.1.6 */
			/* {name: 'container-background-color-opacity', value: 70, min: 0, max: 100} */
			];

		for(var key in sliders){
			var curstep = 1;
			if(sliders[key].step != undefined) curstep = sliders[key].step;
			if(sliders[key].value == -1) sliders[key].value = jQuery('input[name="element-'+sliders[key].name+'"]').val();

			jQuery('#element-'+sliders[key].name).slider({
				value: sliders[key].value,
				min: sliders[key].min,
				max: sliders[key].max,
				step: curstep,
				slide: function(event, ui){
					jQuery('input[name="'+jQuery(this).attr('id')+'"]').val(ui.value);
					jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
					if(jQuery(this).attr('id') == 'element-x-ratio' || jQuery(this).attr('id') == 'element-y-ratio'){
						t.resize_item_skin_preview();
						//AdminEssentials.adamlabsgallery3dtakeCare(0);
					}
					/* 2.1.6 */
					/*
					if(jQuery(this).attr('id') == 'element-container-background-color-opacity'){
						t.change_bg_color();
					}
					*/
				}

			});
			var neww = parseInt(jQuery('#element-'+sliders[key].name+' .ui-slider-handle').css('left'),0);
			jQuery('#element-'+sliders[key].name).prepend('<span class="adamlabsgallery-pre-slider"></span>');
			jQuery('#element-'+sliders[key].name+' .adamlabsgallery-pre-slider').css({width:neww});
		}

		// RESCALE SLIDER SELECTED PART
		jQuery('body').on('adamlabsgalleryslide',function(event,$obj) {
			var obj = jQuery($obj);
			setTimeout(function() {
				var neww = parseInt(obj.find('.ui-slider-handle').css('left'),0);
				obj.find('.adamlabsgallery-pre-slider').css({width:neww});

			},10);
		});

		/**
		 * Special Slider
		 */
		jQuery('#element-item-skin-width-check').slider({
			value: 400,
			min: 100,
			max: 1170,
			step: 1,
			slide: function(event, ui){
				jQuery('#currently-at-pixel').text(ui.value+'px');
				jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
				jQuery('#skin-dz-wrapper').css('width', ui.value);
				jQuery('#skin-dz-media-bg').css('width', ui.value);
				t.resize_item_skin_preview();
			}
		});
		var neww = parseInt(jQuery('#element-item-skin-width-check .ui-slider-handle').css('left'),0);
		jQuery('#element-item-skin-width-check').prepend('<span class="adamlabsgallery-pre-slider"></span>');
		jQuery('#element-item-skin-width-check .adamlabsgallery-pre-slider').css({width:neww});

		jQuery('#content-shadow-alpha').slider({
			value: jQuery('input[name="content-shadow-alpha"]').val(),
			min: 0,
			max: 100,
			step: 1,
			slide: function(event, ui){
				jQuery('input[name="content-shadow-alpha"]').val(ui.value);
				jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
			}
		});
		var neww = parseInt(jQuery('#content-shadow-alpha .ui-slider-handle').css('left'),0);
		jQuery('#content-shadow-alpha').prepend('<span class="adamlabsgallery-pre-slider"></span>');
		jQuery('#content-shadow-alpha .adamlabsgallery-pre-slider').css({width:neww});


		var sliders = [/*
			{name: 'cover-animation-delay-top', value: 0, min: 0, max: 60},
			{name: 'cover-animation-delay-center', value: 0, min: 0, max: 60},
			{name: 'cover-animation-delay-bottom', value: 0, min: 0, max: 60},
			{name: 'cover-group-animation-delay', value: 0, min: 0, max: 60},
			{name: 'media-animation-delay', value: 0, min: 0, max: 60},
			{name: 'hover-image-animation-delay', value: 0, min: 0, max: 60}
			
		*/];

		for(var key in sliders){
			sliders[key].value = jQuery('input[name="'+sliders[key].name+'"]').val();

			jQuery('#'+sliders[key]['name']).slider({
			value: sliders[key]['value'],
			min: sliders[key]['min'],
			max: sliders[key]['max'],
			step: 1,
			slide: function(event, ui){
					jQuery('input[name="'+jQuery(this).attr('id')+'"]').val(ui.value);
					jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
					t.add_animation_classes();
				}
			});
			var neww = parseInt(jQuery('#'+sliders[key].name+' .ui-slider-handle').css('left'),0);
			jQuery('#'+sliders[key].name).prepend('<span class="adamlabsgallery-pre-slider"></span>');
			jQuery('#'+sliders[key].name+' .adamlabsgallery-pre-slider').css({width:neww});
		}

		t.reinitSliderPosition();

		jQuery('#settings-dz-elements-wrapper>ul>li>a, #layer-settings-header').click(function() {

		   setTimeout(function() {
		   	t.reinitSliderPosition();
		   },20);
		});

	}

	t.reinitSliderPosition = function() {
		//ONE TIME PREPARE SLIDER
		jQuery('.adamlabsgallery-pre-slider').each(function() {

			var eps = jQuery(this);

			var neww = parseInt(eps.parent().find('.ui-slider-handle').css('left'),0);
			if (neww!=NaN && neww!=undefined)
				eps.css({width:neww});

		})
	}


	/**
	 * Called on overview page of item skins
	 */
	t.initOverviewItemSkin = function(){

		jQuery('.adamlabsgallery-btn-delete-item-skin').click(function(){
			var delete_id = jQuery(this).attr('id').replace('adamlabsgallery-delete-', '');

			var data = { id: delete_id }

			if(confirm(adamlabsgallery_lang.delete_item_skin)){
				AdminEssentials.ajaxRequest('delete_item_skin', data, '.btn-wrap-item-skin-overview-'+delete_id, function(response){
					if(typeof(response.success != 'undefined') && response.success == true){
						jQuery('#adamlabsgallery-delete-'+delete_id).closest('li').remove();
					}
				});
			}
		});

		jQuery('.adamlabsgallery-btn-duplicate-item-skin').click(function(){
			var duplicate_id = jQuery(this).attr('id').replace('adamlabsgallery-duplicate-', '');

			var data = { id: duplicate_id }

			AdminEssentials.ajaxRequest('duplicate_item_skin', data, '');

		});

		jQuery('.adamlabsgallery-btn-star-item-skin').click(function(){
			var star_id = jQuery(this).attr('id').replace('adamlabsgallery-star-', '');

			var data = { id: star_id }

			AdminEssentials.ajaxRequest('star_item_skin', data, '#waitaminute', function(response){
				var es = jQuery('#adamlabsgallery-star-'+star_id).children('i');

				if(es.hasClass('adamlabsgallery-icon-star-empty')){
					es.removeClass('adamlabsgallery-icon-star-empty').addClass('adamlabsgallery-icon-star');
					es.closest('li').addClass('filter-favorite');
				}else{
					es.removeClass('adamlabsgallery-icon-star').addClass('adamlabsgallery-icon-star-empty');
					es.closest('li').removeClass('filter-favorite');
				}

			});

		});

	}

}
