/************************************************************************************
 * jquery.adamlabs.adamlabsgallery.js - jQuery Plugin for adamlabsgallery Portfolio Slider
 * @version: 2.3 (12.07.2018)
 * @requires jQuery v1.7 or later
 * @author AdamLabs
************************************************************************************/

var AdminEssentials = new function(){

	var t = this;

	var miGalleryAnimmatrix = null;

	var ajaxLoaderClass = null;
	var ajaxLoaderTempHTML = null;

	var all_pages  = [];
	var mapping = {};

	var espprevrevapi,skinpreviewselector, skinpreviewselector2;
	var adamlabsgallery_codemirror_navigation_css = null;
	var adamlabsgallery_codemirror_navigation_css_default_skin = [];
	var adamlabsgallery_codemirror_api_js = null;
	var adamlabsgallery_codemirror_ajax_css = null;

	/*******************************
	 * META BOX PART
	 *******************************/

	var arr_meta_keys = []
	var init_skins = {};
	var init_elements = {};
	var init_styles = {};
	var init_custom = {};
	
	// 2.2.5
	var adamlabsgalleryPreviewTimer;


	/**
	 * set init skins object (from db)
	 */
	t.setInitSkinsJson = function(json_skins){
		init_skins = jQuery.parseJSON(json_skins);
	}

	/**
	 * set init elements object (from post)
	 */
	t.setInitElementsJson = function(json_elements){
		init_elements = jQuery.parseJSON(json_elements);
	}

	/**
	 * set init elements object (from post)
	 */
	t.setInitStylingJson = function(json_styles){
		init_styles = jQuery.parseJSON(json_styles);
		
		// 2.2.6
		// cover animations can't be used for regular elements
		var len = init_styles.length;
		for(var i = 0; i < len; i++) {
		
			if(init_styles[i].hasOwnProperty('name')) {
			
				if(init_styles[i].name.hasOwnProperty('handle')) {
				
					if(init_styles[i].name.handle === 'transition') {
					
						if(init_styles[i].hasOwnProperty('values')) {
						
							delete init_styles[i].values['circlezoom'];
							delete init_styles[i].values['collapsehorizontal'];
							delete init_styles[i].values['collapsevertical'];
							delete init_styles[i].values['linediagonal'];
							delete init_styles[i].values['linevertical'];
							delete init_styles[i].values['linehorizontal'];
							delete init_styles[i].values['spiralzoom'];
						
						}
					
					}
				
				}
			
			}
		
		}
		
	}

	/**
	 * set init custom elements
	 */
	t.setInitCustomJson = function(json_custom){
		init_custom = jQuery.parseJSON(json_custom);
	}


	/**
	 * set init meta keys
	 */
	t.setInitMetaKeysJson = function(json_meta){
		arr_meta_keys = jQuery.parseJSON(json_meta);
		/*json_meta_keys = jQuery.parseJSON(json_meta);
		for (meta in json_meta_keys){
			arr_meta_keys.push(meta);
		}*/
	}

	t.initMetaBox = function(mode){
		
		if(mode === 'post') {
		
			jQuery('#adamlabsgallery-featured-grid').off('.adamlabsgallerymeta').on('change.adamlabsgallerymeta', function(){
				if(jQuery(this).val()!="")
					jQuery("#revslider_featured_slider_id").val("");
					jQuery(".featured-slider-main-metas").hide();
					jQuery(".featured-slider-slide").css("height","40px").css("background-color","transparent");
			});

			jQuery("#revslider_featured_slider_id").off('.adamlabsgallerymeta').on('change.adamlabsgallerymeta', function(){
				jQuery('#adamlabsgallery-featured-grid').val("");
			});
			
		}
		
		jQuery('#adamlabsgallery-add-custom-meta-field-' + mode).off('.adamlabsgallerymeta').on('click.adamlabsgallerymeta', function(){
			t.add_meta_element(mode);
		});
		
		jQuery('body').off('.adamlabsgallerymeta').on('click.adamlabsgallerymeta', '.adamlabsgallery-remove-custom-meta-field', function(){
			jQuery(this).parent().remove();
		});

		jQuery('body').on('change.adamlabsgallerymeta', '.adamlabsgallery-custom-meta-skin', function(){
			var sel = jQuery(this).val();
			var item_sel = jQuery(this).parent().children('.adamlabsgallery-custom-meta-element');
			var elements = '';

			for(var key in init_skins[sel].layers){
				elements += '<option value="'+init_skins[sel].layers[key]+'">adamlabsgallery-'+init_skins[sel].handle+'-element-'+init_skins[sel].layers[key]+'</option>';
			}

			//add other elements here
			elements += '<option value="layout">'+adamlabsgallery_lang.layout_settings+'</option>';

			item_sel.html(elements);
		});


		jQuery('body').on('change.adamlabsgallerymeta', '.adamlabsgallery-custom-meta-element', function(){
			var sel = jQuery(this).val();
			var settings = '';

			if(sel == 'layout'){ //put layout in select
				for(var key in init_styles){
					if(init_styles[key]['container'] == 'layout'){
						settings += '<option value="'+init_styles[key].name.handle+'">'+init_styles[key].name.text+'</option>';
						if(typeof init_styles[key].hover !== 'undefined' && init_styles[key].hover == 'true')
							settings += '<option value="'+init_styles[key].name.handle+'-hover">'+init_styles[key].name.text+':hover</option>';
					}
				}
			}else{ //insert style / anim things in select
				for(var key in init_styles){
					if(init_styles[key]['container'] == 'style' || init_styles[key]['container'] == 'anim'){
						settings += '<option value="'+init_styles[key].name.handle+'">'+init_styles[key].name.text+'</option>';
						if(typeof init_styles[key].hover !== 'undefined' && init_styles[key].hover == 'true')
							settings += '<option value="'+init_styles[key].name.handle+'-hover">'+init_styles[key].name.text+':hover</option>';
					}
				}
			}

			jQuery(this).siblings('.adamlabsgallery-custom-meta-setting').html(settings).change();
		});	

		jQuery('body').on('change.adamlabsgallerymeta', '.adamlabsgallery-custom-meta-setting', function(){
			
			/* 2.1.6 */
			var $this = jQuery(this);
			
			var sett = $this.val();
			var hover = false;
			if(sett.indexOf('-hover') >= 0){
				sett = sett.replace('-hover', '');
				hover = true;
			}

			for(var key in init_styles){
				if(init_styles[key].name.handle == sett){
					
					var sb_data = $this.siblings('.adamlabsgallery-custom-meta-style');
					sb_data = sb_data.length ? sb_data.data('eltype') : $this.siblings().find('.adamlabsgallery-custom-meta-style').data('eltype');
					
					switch(init_styles[key].type){
						case 'color':
							
							var cur_val = $this.siblings('.adamlabsgallery-custom-meta-style');
							cur_val = cur_val.length ? cur_val.val() : $this.siblings().find('.adamlabsgallery-custom-meta-style').val();
							$this.siblings('.adamlabsgallery-custom-meta-style, .wp-picker-container, .rev-colorpickerspan').remove();
							
							var cpMode = ' data-mode="' + init_styles[key].cpmode + '"';
							$this.parent().append('<input class="adamlabsgallery-custom-meta-style" data-eltype="color" type="text" name="adamlabsgallery-custom-meta-style[]" value="'+init_styles[key]['default']+'"'+cpMode+'>');
							
							if(typeof RevColor === 'undefined') {
								$this.siblings('.adamlabsgallery-custom-meta-style').wpColorPicker({color:true});
								if(sb_data == 'color') $this.siblings('.adamlabsgallery-custom-meta-style').wpColorPicker('color', cur_val);
							}
							else {
								if(sb_data == 'color') {
									$this.siblings('.adamlabsgallery-custom-meta-style').val(cur_val).AdamLabsColorPicker({wrapper:'<span class="rev-colorpickerspan"></span>'});
								}
								else {
									$this.siblings('.adamlabsgallery-custom-meta-style').AdamLabsColorPicker({wrapper:'<span class="rev-colorpickerspan"></span>'});
								}
							}
							
						break;
						case 'select':
							var cur_val = $this.siblings('.adamlabsgallery-custom-meta-style option:selected').val();
							$this.siblings('.adamlabsgallery-custom-meta-style, .wp-picker-container, .rev-colorpickerspan').remove();
							$this.parent().append('<select class="adamlabsgallery-custom-meta-style" data-eltype="select" name="adamlabsgallery-custom-meta-style[]"></select>');

							for(var opt in init_styles[key]['values']){
								$this.siblings('.adamlabsgallery-custom-meta-style').append('<option value="'+opt+'">'+init_styles[key]['values'][opt]+'</option>');
							}

							$this.siblings('.adamlabsgallery-custom-meta-style option[value="'+init_styles[key]['default']+'"]').attr('selected', 'selected');

							if(sb_data == 'select')
								$this.siblings('.adamlabsgallery-custom-meta-style option[value="'+cur_val+'"]').attr('selected', 'selected');
						break;
						case 'number':
							var cur_val = $this.siblings('.adamlabsgallery-custom-meta-style').val();
							$this.siblings('.adamlabsgallery-custom-meta-style, .wp-picker-container, .rev-colorpickerspan').remove();
							$this.parent().append('<input class="adamlabsgallery-custom-meta-style" type="number" data-eltype="number" name="adamlabsgallery-custom-meta-style[]" value="'+init_styles[key]['default']+'">');

							if(sb_data == 'number')
								$this.siblings('.adamlabsgallery-custom-meta-style').val(cur_val);
						break;
						case 'text':
						default:
							var cur_val = $this.siblings('.adamlabsgallery-custom-meta-style').val();
							$this.siblings('.adamlabsgallery-custom-meta-style, .wp-picker-container, .rev-colorpickerspan').remove();
							$this.parent().append('<input class="adamlabsgallery-custom-meta-style" type="text" data-eltype="text" name="adamlabsgallery-custom-meta-style[]" value="'+init_styles[key]['default']+'">');

							if(sb_data == 'text')
								$this.siblings('.adamlabsgallery-custom-meta-style').val(cur_val);
						break;
					}
					break;
				}
			}

		});

		for(var key in init_elements){
			t.add_meta_element(mode, init_elements[key]);
		}
		
		if(mode === 'post') {
		
			jQuery('#adamlabsgallery-create-wp-gallery .ess-customgridwrap').off('.adamlabsgallerymeta').on('click.adamlabsgallerymeta', function() {

				jQuery('#insert-media-button').click();
				jQuery('.media-menu').children().eq(1).click();
				jQuery('.ui-dialog[aria-describedby="adamlabsgallery-tiny-mce-dialog"] .ui-dialog-titlebar-close').click();

				var bodies = jQuery('body').one('click.adamlabsgallerymeta', '.media-modal-backdrop, .media-modal-close', function() {
					bodies.removeClass('adamlabsgallery-wp-gallery');
				});

				jQuery('body').addClass('adamlabsgallery-wp-gallery');

			});

			jQuery('#insert-media-button').off('.adamlabsgallerymeta').on('click.adamlabsgallerymeta', function() {
			   jQuery('body').removeClass('adamlabsgallery-wp-gallery');
			});
			
			if(jQuery('body').hasClass('post-type-attachment')) {
				jQuery('#adamlabsgallery-meta-box').remove();
			}
			
		}

	}


	t.initCustomMeta = function(){

		jQuery('#adamlabsgallery-meta-add').click(function(){
			jQuery('#custom-meta-dialog-wrap').dialog({
				modal:true,
				draggable:true,
				resizable:false,
				width:340,
				height:560,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons: [ { text: adamlabsgallery_lang.add_meta, click: function() {
					var data = {};

					data.handle = t.sanitize_input(jQuery('input[name="adamlabsgallery-custom-meta-handle"]').val());
					data.name = jQuery('input[name="adamlabsgallery-custom-meta-name"]').val();
					data['default'] = jQuery('input[name="adamlabsgallery-custom-meta-default"]').val();
					data.type = jQuery('select[name="adamlabsgallery-custom-meta-type"] option:selected').val();
					data['sort-type'] = jQuery('select[name="adamlabsgallery-custom-meta-sort-type"] option:selected').val();
					data.sel = false;

					jQuery('input[name="adamlabsgallery-custom-meta-handle"]').val(data.handle);

					if(data.type == 'select' || data.type == 'multi-select')
						data.sel = jQuery('textarea[name="adamlabsgallery-custom-meta-select"]').val();

					if(data.handle.length < 3 || data.name.length < 3){
						alert(adamlabsgallery_lang.handle_and_name_at_least_3);
						return false;
					}

					AdminEssentials.ajaxRequest("add_custom_meta", data, '#adamlabsgallery-meta-add',function(response){});

				} } ],
			});
		});


		jQuery('body').on('click', '.adamlabsgallery-meta-edit', function(){
			if(confirm(adamlabsgallery_lang.really_change_meta_effects)){
				var data = {};
				var el = jQuery(this);
				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-meta-handle[]"]').val();
				data.name = el.closest('.inside').find('input[name="adamlabsgallery-meta-name[]"]').val();
				data['default'] = el.closest('.inside').find('input[name="adamlabsgallery-meta-default[]"]').val();
				data.sel = el.closest('.inside').find('textarea[name="adamlabsgallery-meta-select[]"]').val();

				AdminEssentials.ajaxRequest("edit_custom_meta", data, '#adamlabsgallery-meta-add, .adamlabsgallery-meta-edit, .adamlabsgallery-meta-delete',function(response){});
			}

		});

		jQuery('body').on('click', '.adamlabsgallery-meta-delete', function(){
			if(confirm(adamlabsgallery_lang.really_delete_meta)){
				var data = {};
				var el = jQuery(this);

				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-meta-handle[]"]').val();

				AdminEssentials.ajaxRequest("remove_custom_meta", data, '#adamlabsgallery-meta-add, .adamlabsgallery-meta-edit, .adamlabsgallery-meta-delete',function(response){
					if(response.success == true){
						el.closest('.postbox.adamlabsgallery-postbox').remove();
					}
				});
			}
		});


		jQuery('select[name="adamlabsgallery-custom-meta-type"]').change(function(){
			if(jQuery(this).val() == 'select' || jQuery(this).val() == 'multi-select'){
				jQuery('#adamlabsgallery-custom-meta-select-wrap').show();
			}else{
				jQuery('#adamlabsgallery-custom-meta-select-wrap').hide();
			}
		});



		jQuery('#adamlabsgallery-link-meta-add').click(function(){
			jQuery('#link-meta-dialog-wrap').dialog({
				modal:true,
				draggable:true,
				resizable:false,
				width:320,
				height:380,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons: [ { text: adamlabsgallery_lang.add_meta, click: function() {
					var data = {};

					data.handle = t.sanitize_input(jQuery('input[name="adamlabsgallery-link-meta-handle"]').val());
					data.name = jQuery('input[name="adamlabsgallery-link-meta-name"]').val();
					data.original = t.sanitize_input(jQuery('input[name="adamlabsgallery-link-meta-original"]').val());
					data['sort-type'] = jQuery('select[name="adamlabsgallery-link-meta-sort-type"] option:selected').val();

					jQuery('input[name="adamlabsgallery-link-meta-handle"]').val(data.handle);
					jQuery('input[name="adamlabsgallery-link-meta-original"]').val(data.original);

					if(data.handle.length < 3 || data.name.length < 3 || data.original.length < 3){
						alert(adamlabsgallery_lang.handle_and_name_at_least_3);
						return false;
					}

					AdminEssentials.ajaxRequest("add_link_meta", data, '#adamlabsgallery-link-meta-add',function(response){});

				} } ],
			});
		});


		jQuery('body').on('click', '.adamlabsgallery-link-meta-edit', function(){
			if(confirm(adamlabsgallery_lang.really_change_meta_effects)){
				var data = {};
				var el = jQuery(this);
				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-link-meta-handle[]"]').val();
				data.name = el.closest('.inside').find('input[name="adamlabsgallery-link-meta-name[]"]').val();
				data.original = el.closest('.inside').find('input[name="adamlabsgallery-link-meta-original[]"]').val();

				AdminEssentials.ajaxRequest("edit_link_meta", data, '#adamlabsgallery-link-meta-add, .adamlabsgallery-link-meta-edit, .adamlabsgallery-link-meta-delete',function(response){});
			}

		});


		jQuery('body').on('click', '.adamlabsgallery-link-meta-delete', function(){
			if(confirm(adamlabsgallery_lang.really_delete_meta)){
				var data = {};
				var el = jQuery(this);

				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-link-meta-handle[]"]').val();

				AdminEssentials.ajaxRequest("remove_link_meta", data, '#adamlabsgallery-link-meta-add, .adamlabsgallery-link-meta-edit, .adamlabsgallery-link-meta-delete',function(response){
					if(response.success == true){
						el.closest('.postbox.adamlabsgallery-postbox').remove();
					}
				});
			}
		});

		jQuery('#adamlabsgallery-grid-custom-meta-wrapper').tabs();

		jQuery('.adamlabsgallery-custom-meta-info-box').click(function() {
			if (jQuery(this).hasClass("show")) {
				jQuery(this).find('.adamlabsgallery-custom-meta-toggle-visible').slideUp(200);
				jQuery(this).removeClass("show");
			} else {
				jQuery(this).find('.adamlabsgallery-custom-meta-toggle-visible').slideDown(200);
				jQuery(this).addClass("show");

			}
		});

	}


	t.add_meta_element = function(mode, entry){
		
		var skins = '';
		for(var key in init_skins){
			skins += '<option value="'+key+'">'+init_skins[key].name+'</option>';
		}
		
		mode = jQuery('#adamlabsgallery-advanced-param-' + mode).append('<div class="adamlabsgallery-custom-meta-setting-wrap">'+
												'<a class="button-primary adamlabsgallery-remove-custom-meta-field" href="javascript:void(0);">-</a>'+
												'<select class="adamlabsgallery-custom-meta-skin" name="adamlabsgallery-custom-meta-skin[]">'+skins+'</select>'+
												'<select class="adamlabsgallery-custom-meta-element" name="adamlabsgallery-custom-meta-element[]"></select>'+
												'<select class="adamlabsgallery-custom-meta-setting" name="adamlabsgallery-custom-meta-setting[]"></select>'+
											'</div>');
		
		/* 2.1.6 - convert color/opacity */
		if(typeof entry !== undefined && typeof entry !== 'undefined') {
			
			if(typeof adamlabsgallery_skin_color_values !== 'undefined' && typeof RevColor !== 'undefined' && entry.setting === 'cover-bg-color') {
				
				var converted;
				for(var prop in init_elements) {
					
					if(!init_elements.hasOwnProperty(prop)) continue;
					if(init_elements[prop].setting === 'cover-bg-opacity') {
							
						var color = entry['style'];
						if(color) {	
							var opacity = init_elements[prop].style;
							if(!isNaN(opacity)) {
								entry['style'] = RevColor.convert(color, opacity);
								converted = true;
							}
						}
						break;
					}	
				}
				if(!converted) {
					
					if(adamlabsgallery_skin_color_values.hasOwnProperty(entry.skin)) {
						
						var color = adamlabsgallery_skin_color_values[entry.skin],
							orig = entry['style'];
						
						if(color && orig) {
							
							color = RevColor.process(color);
							orig = RevColor.process(orig);
							
							if(orig[1].search(/hex|rgb/) !== -1 && color[1].search(/hex|rgb/) !== -1) {	
								
								if(color[1] === 'hex') color[0] = RevColor.processRgba(color[0]);
								var rgbValues = RevColor.rgbValues(color[0], 4);
								entry['style'] = RevColor.convert(entry['style'], rgbValues[3]);
							}
						}
					}
				}
			}
			
			mode.find('.adamlabsgallery-custom-meta-skin').last().find('option[value="'+entry['skin']+'"]').attr("selected","selected");
			mode.find('.adamlabsgallery-custom-meta-skin').last().change();

			//before inserting the value, create the field depending on what is selected
			mode.find('.adamlabsgallery-custom-meta-element').last().find('option[value="'+entry['element']+'"]').attr("selected","selected");
			mode.find('.adamlabsgallery-custom-meta-element').last().change();

			mode.find('.adamlabsgallery-custom-meta-setting').last().find('option[value="'+entry['setting']+'"]').attr("selected","selected");
			mode.find('.adamlabsgallery-custom-meta-setting').last().change();

			
			//check if skin, element and setting still exists
			if(	mode.find('.adamlabsgallery-custom-meta-skin option[value="'+entry['skin']+'"]').last().length == 0 ||
				mode.find('.adamlabsgallery-custom-meta-element option[value="'+entry['element']+'"]').last().length == 0 ||
				mode.find('.adamlabsgallery-custom-meta-setting option[value="'+entry['setting']+'"]').last().length == 0 ){
					mode.find('.adamlabsgallery-custom-meta-setting-wrap').last().remove();
					return false;
				}


			//check what the element is now, and add the value to it
			var sb = mode.find('.adamlabsgallery-custom-meta-style').last();
			var sb_data = sb.data('eltype');

			switch(sb_data){
				case 'color':
					if(typeof RevColor === 'undefined') {
						sb.wpColorPicker('color', entry['style']);
					}
					else {
						sb.val(entry['style']).AdamLabsColorPicker('refresh');
					}
				break;
				case 'select':
					sb.find('option[value="'+entry['style']+'"]').attr('selected', 'selected');
				break;
				case 'number':
				case 'text':
				default:
					sb.val(entry['style']);
				break;
			}

		}else{
			mode.find('.adamlabsgallery-custom-meta-skin').last().change();
			mode.find('.adamlabsgallery-custom-meta-element').last().change();
			mode.find('.adamlabsgallery-custom-meta-setting').last().change();
		}

	}

	/**
	 * handles widget areas page
	 * since 1.0.6
	 */

	t.initWidgetAreas = function(){
		jQuery('#adamlabsgallery-widget-area-add').click(function(){
			jQuery('#widget-areas-dialog-wrap').dialog({
				modal:true,
				draggable:true,
				resizable:false,
				width:300,
				height:340,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons: [ { text: adamlabsgallery_lang.add_widget_area, click: function() {
					var data = {};

					data.handle = t.sanitize_input(jQuery('input[name="adamlabsgallery-widget-area-handle"]').val());
					data.name = jQuery('input[name="adamlabsgallery-widget-area-name"]').val();

					jQuery('input[name="adamlabsgallery-widget-area-handle"]').val(data.handle);

					if(data.handle.length < 3 || data.name.length < 3){
						alert(adamlabsgallery_lang.handle_and_name_at_least_3);
						return false;
					}

					AdminEssentials.ajaxRequest("add_widget_area", data, '.ui-button',function(response){});

				} } ],
			});
		});


		jQuery('body').on('click', '.adamlabsgallery-widget-area-edit', function(){
			if(confirm(adamlabsgallery_lang.really_change_widget_area_name)){
				var data = {};
				var el = jQuery(this);
				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-widget-area-handle[]"]').val();
				data.name = el.closest('.inside').find('input[name="adamlabsgallery-widget-area-name[]"]').val();

				AdminEssentials.ajaxRequest("edit_widget_area", data, '#adamlabsgallery-widget-area-add, .adamlabsgallery-widget-area-edit, .adamlabsgallery-widget-area-delete',function(response){});
			}

		});


		jQuery('body').on('click', '.adamlabsgallery-widget-area-delete', function(){
			if(confirm(adamlabsgallery_lang.really_delete_widget_area)){
				var data = {};
				var el = jQuery(this);

				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-widget-area-handle[]"]').val();

				AdminEssentials.ajaxRequest("remove_widget_area", data, '#adamlabsgallery-widget-area-add, .adamlabsgallery-widget-area-edit, .adamlabsgallery-widget-area-delete',function(response){
					if(response.success == true){
						el.closest('.postbox.adamlabsgallery-postbox').remove();
					}
				});
			}
		});

	}

	t.initGoogleFonts = function(){

		jQuery('#adamlabsgallery-font-add').click(function(){
			jQuery('#font-dialog-wrap').dialog({
				modal:true,
				draggable:true,
				resizable:false,
				width:470,
				height:320,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons: [ { text: adamlabsgallery_lang.add_font, click: function() {
					var data = {};

					data.handle = t.sanitize_input(jQuery('input[name="adamlabsgallery-font-handle"]').val());
					data['url'] = jQuery('input[name="adamlabsgallery-font-url"]').val();

					if(data.handle.length < 3 || data.url.length < 3){
						alert(adamlabsgallery_lang.handle_and_name_at_least_3);
						return false;
					}

					AdminEssentials.ajaxRequest("add_google_fonts", data, '#adamlabsgallery-font-add',function(response){});

				} } ],
			});
		});


		jQuery('body').on('click', '.adamlabsgallery-font-edit', function(){
			if(confirm(adamlabsgallery_lang.really_change_font_effects)){
				var data = {};
				var el = jQuery(this);
				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-font-handle[]"]').val();
				data['url'] = el.closest('.inside').find('input[name="adamlabsgallery-font-url[]"]').val();

				AdminEssentials.ajaxRequest("edit_google_fonts", data, '#adamlabsgallery-font-add, .adamlabsgallery-font-edit, .adamlabsgallery-font-delete',function(response){});
			}

		});


		jQuery('body').on('click', '.adamlabsgallery-font-delete', function(){
			if(confirm(adamlabsgallery_lang.really_delete_meta)){
				var data = {};
				var el = jQuery(this);

				data.handle = el.closest('.inside').find('input[name="adamlabsgallery-font-handle[]"]').val();

				AdminEssentials.ajaxRequest("remove_google_fonts", data, '#adamlabsgallery-font-add, .adamlabsgallery-font-edit, .adamlabsgallery-font-delete',function(response){
					if(response.success == true){
						el.closest('.postbox.adamlabsgallery-postbox').remove();
					}
				});
			}
		});

	}

	/**
	 * Init Search Settings
	 * @since: 2.0
	 */
	t.initSearchSettings = function(){

		jQuery('#adamlabsgallery-btn-save-settings').click(function(){
			var data = {};

			data.global = t.getFormParams('adamlabsgallery-search-global-settings');
			data.shortcode = t.getFormParams('adamlabsgallery-search-shortcode-settings');
			data.settings = {}
			data.settings['search-enable'] = jQuery('input[name="search-enable"]:checked').val();

			AdminEssentials.ajaxRequest("save_search_settings", data, '#adamlabsgallery-btn-save-settings',function(response){});
		});

		jQuery('body').on('click', '.adamlabsgallery-btn-remove-setting', function(){
			jQuery(this).closest('.postbox').remove();
		});

		jQuery('input[name="search-enable"]').click(function(){
			if(jQuery(this).val() == 'on')
				jQuery('#adamlabsgallery-search-global-settings').show();
			else
				jQuery('#adamlabsgallery-search-global-settings').hide();

		});
		jQuery('input[name="search-enable"]:checked').click();


		jQuery('#adamlabsgallery-btn-add-global-setting').click(function(){
			t.append_global_setting( {} );
			t.initAccordion();
		});

		jQuery('#adamlabsgallery-btn-add-shortcode-setting').click(function(){
			t.append_shortcode_setting( {} );
			t.initAccordion();
		});


		jQuery('body').on('keyup', 'input[name="search-class[]"]', function(){
			jQuery(this).closest('.postbox').find('.search-title').text(jQuery(this).val());
		});


		jQuery('#adamlabsgallery-grid-search-wrapper').tabs();

		jQuery('.adamlabsgallery-search-settings-info-box').click(function() {
			if (jQuery(this).hasClass("show")) {
				jQuery(this).find('.adamlabsgallery-search-settings-toggle-visible').slideUp(200);
				jQuery(this).removeClass("show");
			} else {
				jQuery(this).find('.adamlabsgallery-search-settings-toggle-visible').slideDown(200);
				jQuery(this).addClass("show");
			}
		});


		t.append_global_setting = function(data){
			var content = global_settings_template(data);
			jQuery('.adamlabsgallery-global-search-wrap').append(content);
		}

		t.append_shortcode_setting = function(data){
			var content = shortcode_settings_template(data);
			jQuery('.adamlabsgallery-shortcode-search-wrap').append(content);
		}

		var global_settings_template = wp.template( "adamlabsgallery-global-settings-wrap" );
		var shortcode_settings_template = wp.template( "adamlabsgallery-shortcode-settings-wrap" );
		var data = global_settings;

		if(typeof(data.global) !== 'undefined' && typeof(data.global['search-class']) !== 'undefined'){
			for(var i = 0; i<data.global['search-class'].length;i++){
				var init_data = {};
				for(var key in data.global){
					init_data[key] = data.global[key][i];
				}

				t.append_global_setting(init_data);
			}
		}

		if(typeof(data.shortcode) !== 'undefined' && typeof(data.shortcode['sc-grid-id']) !== 'undefined'){
			for(var i = 0; i<data.shortcode['sc-grid-id'].length;i++){
				var init_data = {};
				for(var key in data.shortcode){
					init_data[key] = data.shortcode[key][i];
				}

				t.append_shortcode_setting(init_data);
			}
		}



		jQuery('body').on('change', '#adamlabsgallery-shortcode-search-wrap select, #adamlabsgallery-shortcode-search-wrap input', function(){
			jQuery(this).closest('.postbox').find('input[name="sc-shortcode[]"]').val('[adamlabsgallery_search handle="'+jQuery(this).closest('.postbox').find('input[name="sc-handle[]"]').val()+'"]');
		});
		jQuery('#adamlabsgallery-shortcode-search-wrap select, #adamlabsgallery-shortcode-search-wrap input').each(function(){
			jQuery(this).find('option:selected').change();
		});

		jQuery('input[name="sc-shortcode[]"]').click(function(){
			this.select();
		});
	}


	/*******************************
	 *	- SHOW INFO AND HIDE INFO -
	 *******************************/

	t.showInfo = function(obj) {

		if(typeof(adamlabsgallerygs) === 'undefined') return true;

		var info = '<i class="adamlabsgallery-icon-info"></i>';
		if (obj.type=="warning") info = '<i class="adamlabsgallery-icon-cancel"></i>';
		if (obj.type=="success") info = '<i class="adamlabsgallery-icon-ok"></i>';

		obj.showdelay = obj.showdelay != undefined ? obj.showdelay : 0;
		obj.hidedelay = obj.hidedelay != undefined ? obj.hidedelay : 0;

		// CHECK IF THE TOOLBOX WRAPPER EXIST ALREADY
		if (jQuery('#adamlabsgallery-toolbox-wrapper').length==0) jQuery('#adamlabsgallery-wrap').append('<div id="adamlabsgallery-toolbox-wrapper"></div>');

		// ADD NEW INFO BOX
		jQuery('#adamlabsgallery-toolbox-wrapper').append('<div class="adamlabsgallery-toolbox newadded">'+info+obj.content+'</div>');
		var nt = jQuery('#adamlabsgallery-toolbox-wrapper').find('.adamlabsgallery-toolbox.newadded');
		nt.removeClass('newadded');


		// ANIMATE THE INFO BOX
		adamlabsgallerygs.TweenLite.fromTo(nt,0.5,{y:-50,autoAlpha:0,transformOrigin:"50% 50%", transformPerspective:900, rotationX:-90},{autoAlpha:1,y:0,rotationX:0,ease:adamlabsgallerygs.Back.easeOut,delay:obj.showdelay});

		if (obj.hideon != "event") {
			nt.click(function() {
				adamlabsgallerygs.TweenLite.to(nt,0.3,{x:200,ease:adamlabsgallerygs.Power3.easeInOut,autoAlpha:0,onComplete:function() {nt.remove()}});
			})

			if (obj.hidedelay !=0 && obj.hideon!="click")
				adamlabsgallerygs.TweenLite.to(nt,0.3,{x:200,ease:adamlabsgallerygs.Power3.easeInOut,autoAlpha:0,delay:obj.hidedelay + obj.showdelay, onComplete:function() {nt.remove()}});
		} else  {
			jQuery('#adamlabsgallery-toolbox-wrapper').on(obj.event,function() {
				adamlabsgallerygs.TweenLite.to(nt,0.3,{x:200,ease:adamlabsgallerygs.Power3.easeInOut,autoAlpha:0,onComplete:function() {nt.remove()}});
			});
		}
	}


	/**
	 * escape html, turn html to a string
	 */
	t.htmlspecialchars = function(string){
		  return string
		      .replace(/&/g, "&amp;")
		      .replace(/</g, "&lt;")
		      .replace(/>/g, "&gt;")
		      .replace(/"/g, "&quot;")
		      .replace(/'/g, "&#039;");
	}

	/**
	 * turn string value ("true", "false") to string
	 */
	t.strToBool = function(str){

		if(str == undefined)
			return(false);

		if(typeof(str) != "string")
			return(false);

		str = str.toLowerCase();

		var bool = (str == "true")?true:false;
		return(bool);
	}

	/**
	 * strip html tags
	 */
	t.stripTags = function(input, allowed) {
	    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	}


	/**
	 * Strip slashes
	 * since 1.0.2
	 */
	t.stripslashes = function(str) {
	//       discuss at: http://phpjs.org/functions/stripslashes/
	//      original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	//      improved by: Ates Goral (http://magnetiq.com)
	//      improved by: marrtins
	//      improved by: rezna
	//         fixed by: Mick@el
	//      bugfixed by: Onno Marsman
	//      bugfixed by: Brett Zamir (http://brett-zamir.me)
	//         input by: Rick Waldron
	//         input by: Brant Messenger (http://www.brantmessenger.com/)
	// reimplemented by: Brett Zamir (http://brett-zamir.me)
	//        example 1: stripslashes('Kevin\'s code');
	//        returns 1: "Kevin's code"
	//        example 2: stripslashes('Kevin\\\'s code');
	//        returns 2: "Kevin\'s code"

	return (str + '')
		.replace(/\\(.?)/g, function(s, n1) {
		  switch (n1) {
			case '\\':
			  return '\\';
			case '0':
			  return '\u0000';
			case '':
			  return '';
			default:
			  return n1;
		  }
		});
	}


    /**
	 * change hex to rgb
	 */
    t.hex_to_rgba = function(hex, transparency, format){
        if(typeof transparency !== 'undefined'){
            transparency = (transparency > 0) ? transparency / 100 : 0;
        }else{
            transparency = 1;
        }

        // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });

        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

        if(typeof format !== 'undefined'){
            if(result){
                return 'rgba('+parseInt(result[1], 16)+', '+parseInt(result[2], 16)+', '+parseInt(result[3], 16)+', '+transparency+')';
            }else{
                return null;
            }
        }else{
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16),
                a: transparency
            } : null;
        }
    }


	t.upload_image_img = function(id){
		
		//To Avoid Search Issue in WP Media		
		jQuery(document).off('focusin');

		var custom_uploader;

		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: adamlabsgallery_lang.choose_image,
			button: {
				text: adamlabsgallery_lang.choose_image
			},
			multiple: false,
			open: function() {
				console.log("Open");
			},
			close: function() {
				console.log("Close");
			}
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();

			jQuery('#'+id).val(attachment.id);
			jQuery('#'+id+'-img').attr('src', attachment.url);
			jQuery('#'+id+'-img').show();
			
			if(id == 'adamlabsgallery-custom-image'){
				jQuery('.adamlabsgallery-elset-row').find('input[name="title"]').val(attachment.title);
				jQuery('.adamlabsgallery-elset-row').find('input[name="excerpt"]').val(attachment.description);
				jQuery('.adamlabsgallery-elset-row').find('input[name="content"]').val(attachment.description);
			}
			
			//custom_uploader.close();
		});

		//Open the uploader dialog
		custom_uploader.open();
		//jQuery(".ui-dialog").hide();
	}

	t.upload_image_bg = function(id){
		var custom_uploader;

		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: adamlabsgallery_lang.choose_image,
			button: {
				text: adamlabsgallery_lang.choose_image
			},
			multiple: false
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();

			jQuery('#'+id).val(attachment.id);
			jQuery('#'+id+'-wrapper').css('background', 'url('+attachment.url+')');
			//custom_uploader.close();
		});

		//Open the uploader dialog
		custom_uploader.open();
	}

	t.add_custom_grid_multiple_images = function(id){
		var custom_uploader;

		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: adamlabsgallery_lang.choose_images,
			button: {
				text: adamlabsgallery_lang.choose_images
			},
			multiple: true
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {

			custom_uploader.state().get('selection').each(function(sel_image){
				var curimg = sel_image.toJSON();
				
				if(typeof curimg !== 'undefined' && typeof curimg.id !== 'undefined' && parseInt(curimg.id) > 0){
					t.reset_custom_dialog();
					t.remove_hightlighting();
					
					jQuery('#adamlabsgallery-custom-image').val(curimg.id);
					
					jQuery('.adamlabsgallery-elset-row').find('input[name="title"]').val(curimg.title);
					jQuery('.adamlabsgallery-elset-row').find('input[name="excerpt"]').val(curimg.description);
					jQuery('.adamlabsgallery-elset-row').find('input[name="content"]').val(curimg.description);
					
					var new_data = JSON.stringify(t.getFormParams('edit-custom-element-form')); //get set data
					
					jQuery('#adamlabsgallery-preview-wrapping-wrapper').prepend('<input id="adamlabsgallery-new-temp-layer" class="adamlabsgallery-new-temp-layer" name="layers[]" type="hidden" values="" />');
					jQuery('#adamlabsgallery-new-temp-layer').val(new_data);
				}
			});

			t.changePreviewGrid(true);

		});

		//Open the uploader dialog
		custom_uploader.open();
	}


	t.getFormParams = function(formID, ignore_empty){
		var obj = new Object();
		var form = document.getElementById(formID);
		var name,value,type,flagUpdate;

		//enabling all form items connected to mx
		var len = form.elements.length;
		for(var i=0; i<len; i++){
			var element = form.elements[i];

			name = element.name;
			value = element.value;

			type = element.type;
			if(jQuery(element).hasClass("wp-editor-area"))
				type = "editor";

			flagUpdate = true;

			switch(type){
				case "checkbox":
					if(form.elements[i].className.indexOf('adamlabsgallery-get-val') !== -1){
						if(form.elements[i].checked){
							value = form.elements[i].value;
						}else{
							continue;
						}
					}else{
						value = form.elements[i].checked;
					}
				break;
				case "radio":
					if(form.elements[i].className.indexOf('adamlabsgallery-get-val') !== -1){
						if(form.elements[i].checked){
							value = form.elements[i].value;
						}else{
							continue;
						}
					}else{
						if(form.elements[i].checked == false)
							flagUpdate = false;
					}
				break;
				case "editor":
				
					/* 2.1.6 */
					if(typeof(tinyMCE) !== 'undefined' && tinyMCE.get(name) != null){
						try {
							value = tinyMCE.get(name).getContent();
						}
						catch(e) {
							value = jQuery('textarea[name="' + name + '"]').val();
						}
					}
				break;
				case "select-multiple":
					value = jQuery(element).val();
					if(value)
						value = value.toString();
				break;
			}

			if(flagUpdate == true && name != undefined){
				if(typeof ignore_empty !== 'undefined'){ //remove empty values from string that first needs to be json to obj
					if(value != ''){
						try{
							var json_array  = jQuery.parseJSON(value);
						}catch(e){
							try{
								value = t.stripslashes(value);
								var json_array = jQuery.parseJSON(value);
							}catch(e){
								continue; //invalid json
							}
						}

						if(typeof json_array == 'object'){

							for(var key in json_array){
								if(json_array[key] == '') delete(json_array[key]);
							}

							value = JSON.stringify(json_array);
						}
					}else{
						continue;
					}
				}
				if(name.indexOf('[]') > -1){
					name = name.replace('[]', '');
					if(typeof obj[name] !== 'object') obj[name] = [];

					obj[name][Object.keys(obj[name]).length] = value;
				}else{
					obj[name] = value;
				}
			}
		}
		return(obj);
	}

	/**
	 * init accordion
	 */
	t.initAccordion = function(){

		jQuery(".postbox-arrow").each(function(i) {

			jQuery(this).closest('h3').unbind('click');

			jQuery(this).closest('h3').click(function(){
				var handle = jQuery(this);

				//open
				if(!handle.hasClass("box-closed")){
					handle.closest('.postbox').find('.inside').slideUp("fast");
					handle.addClass("box-closed");

				}else{	//close
					jQuery('.postbox-arrow').each(function() {
						var handle = jQuery(this).closest('h3');
						handle.closest('.postbox').find('.inside').slideUp("fast");
						handle.addClass("box-closed");
					})
					handle.closest('.postbox').find('.inside').slideDown("fast");
					handle.removeClass("box-closed");

				}
			});

		});

	}

	/**
	 * init slider
	 */
	t.initSlider = function(){
		jQuery(function() {
			var sliders = [
				{name: 'rows', min: 1, max: 99},
				{name: 'rows-mobile', min: 1, max: 99},
				{name: 'grid-animation-speed', min: 0, max: 5000, step: 100},
				{name: 'grid-animation-delay', min: 1, max: 30},
				{name: 'grid-start-animation-speed', min: 0, max: 5000, step: 100},
				{name: 'grid-start-animation-delay', min: 1, max: 30},
				{name: 'hover-animation-speed', min: 0, max: 9000, step: 100},
				{name: 'hover-animation-delay', min: 1, max: 30, step: 5},
				{name: 'load-more-amount', min: 1, max: 20},
				{name: 'load-more-start', min: 1, max: 20}
				];


			for(key in sliders){
				var curval = jQuery('input[name="'+sliders[key].name+'"]').val();
				var curstep = 1;
				if(sliders[key].step != undefined) curstep = sliders[key].step;
				var slidr = jQuery('#slider-'+sliders[key].name);
				slidr.slider({
					value: curval,
					min: sliders[key].min,
					max: sliders[key].max,
					step: curstep,
					slide: function(event, ui){
						jQuery('input[name="'+jQuery(this).attr('id').replace('slider-', '')+'"]').val(ui.value);
						jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));
					},
					create: function() {
						slidr.data('sliderinited', true);
					}

				});
				var neww = parseInt(jQuery('#slider-'+sliders[key].name+' .ui-slider-handle').css('left'),0);
				jQuery('#slider-'+sliders[key].name).prepend('<span class="adamlabsgallery-pre-slider"></span>');
				jQuery('#slider-'+sliders[key].name+' .adamlabsgallery-pre-slider').css({width:neww + "%"});

			}

			for(var i=1; i<=7; i++){
				var columns = jQuery('#columns-'+i).val();
				jQuery('#slider-columns-'+i).slider({
					value: columns,
					min: 1,
					max: 15,
					slide: function(event, ui){
						jQuery('#columns-'+jQuery(this).data('num')).val(ui.value);
						jQuery("body").trigger("adamlabsgalleryslide",jQuery(this));

					}
				});
				jQuery('#slider-columns-'+i).prepend('<span class="adamlabsgallery-pre-slider"></span>');

				var neww = parseInt(jQuery('#slider-columns-'+i+' .ui-slider-handle').css('left'),0);
				jQuery('#slider-columns-'+i+' .adamlabsgallery-pre-slider').css({width:neww});
			}


			// RESCALE SLIDER SELECTED PART
			jQuery('body').on('adamlabsgalleryslide',function(event,$obj) {
				var obj = jQuery($obj);
				setTimeout(function() {
					var neww = parseInt(obj.find('.ui-slider-handle').css('left'),0);
					obj.find('.adamlabsgallery-pre-slider').css({width:neww});
				},10);

			});
			
			// 2.2.5
			jQuery('#grid-start-animation').on('change', function() {
				
				var val,
					delayMethod,
					speedMethod = 'show',
					isNone = this.value === 'none',
					revealMethod = this.value !== 'none' && this.value !== 'reveal' ? 'show' : 'hide';
					
				if(this.value !== 'reveal') {
					
					val = 'off';
					delayMethod = this.value !== 'none' ? 'show' : 'hide';
					if(this.value === 'none') speedMethod = 'hide';
					
				}
				else {
					
					val = 'on';
					delayMethod = 'hide';
					
				}

				jQuery('#start-animation-speed-wrap')[speedMethod]();
				jQuery('#start-animation-delay-wrap')[delayMethod]();
				jQuery('#start-animation-viewport-wrap')[revealMethod]();
				$('#hide-markup-before-load').val(val);
				
			}).change();
			
			// 2.2.5
			jQuery('.start-anime-viewport').on('change', function() {
				
				var method = this.checked && this.value === 'on' ? 'show' : 'hide';
				jQuery('#start-animation-viewport-buffer')[method]();
				
			});
			
			// 2.2.5
			jQuery('#grid-animation-select').on('change', function() {
				
				var method = this.value !== 'rotatefall' ? 'show' : 'hide';
				jQuery('#animation-delay-type-wrap')[method]();
				
			}).change();
			
			// 2.2.5
			jQuery('.adamlabsgallery-lb-source-list').on('change', function() {
				
				if(this.value === 'post-content' && this.checked) {
					
					jQuery('#lbo-list .adamlabsgallery-media-source-order').not('#lbo-post-content').addClass('notavailable');
					jQuery('#adamlabsgallery-post-content-options').show();
					
				}
				else {
					
					jQuery('#lbo-list .adamlabsgallery-media-source-order').removeClass('notavailable');
					jQuery('#adamlabsgallery-post-content-options').hide();
					
				}
				
			});
			
			jQuery('.start-anime-viewport:checked').change();
			jQuery('.adamlabsgallery-lb-source-list[value="post-content"]').change();
			
			jQuery(document).on('keydown', function(event) {

				 if (event.ctrlKey || event.metaKey) {
			        switch (String.fromCharCode(event.which).toLowerCase()) {
			        	case 's':
			            	var saver = jQuery('#adamlabsgallery-btn-save-grid');
							if(saver.length) {
								event.preventDefault();
								saver.click();
							}
			            break;
			         }
			    }
			});
			
			var waitaminute = jQuery('#waitaminute');
			if(waitaminute.parent().attr('id') !== 'wpbody-content') {
				waitaminute.appendTo(jQuery('#wpbody-content'));
			}
			
			jQuery('body').on('click', '.ui-widget-overlay', function() {
				
				jQuery('.ui-dialog[aria-describedby="edit-custom-element-dialog-wrap"], .ui-dialog[aria-describedby="post-meta-dialog-wrap"]').find('.ui-dialog-titlebar-close').click();
				
			});
			
			// 2.2.6
			jQuery('.pagination-touchswipe').on('change', function() {
				
				if(this.checked) jQuery('#pagination-touchswipe-settings').show();
				else jQuery('#pagination-touchswipe-settings').hide();
				
			});
			
			// 2.2.6
			jQuery('.filtervisible').on('change', function() {
				
				if(!this.checked) return;
				var method = this.value === 'on' ? 'show' : 'hide';
				jQuery(this).closest('.adamlabsgallery-filter-options-wrap').find('.adamlabsgallery-filter-visible')[method]();
				
			});
			
			// 2.2.6
			jQuery('.enable-mobile-rows').on('change', function() {
				
				if(!this.checked) return;
				var method = this.value === 'on' ? 'show' : 'hide';
				jQuery('#rows-mobile-wrap')[method]();
				
			});
			
			// 2.3
			jQuery('body').on('click', '.adamlabsgallery-alert', function() {
				
				jQuery(this).removeClass('adamlabsgallery-alert');
				
			});

		});
	}

	t.recalcSlidersPos = function() {

			var sliders = [
				{name: 'rows', min: 1, max: 99},
				{name: 'grid-animation-speed', min: 0, max: 9000, step: 100},
				{name: 'grid-animation-delay', min: 0, max: 100},
				{name: 'hover-animation-speed', min: 0, max: 9000, step: 100},
				{name: 'hover-animation-delay', min: 1, max: 30},
				{name: 'load-more-amount', min: 1, max: 20},
				{name: 'load-more-start', min: 1, max: 20}
				];


			for(key in sliders){
				var curval = jQuery('input[name="'+sliders[key].name+'"]').val();
				var curstep = 1;
				if(sliders[key].step != undefined) curstep = sliders[key].step;
				var neww = parseInt(jQuery('#slider-'+sliders[key].name+' .ui-slider-handle').css('left'),0);
				jQuery('#slider-'+sliders[key].name+' .adamlabsgallery-pre-slider').css({width:neww});

			}

			for(var i=1; i<=7; i++){
				var columns = jQuery('#columns-'+i).val();
				var neww = parseInt(jQuery('#slider-columns-'+i+' .ui-slider-handle').css('left'),0);
				jQuery('#slider-columns-'+i+' .adamlabsgallery-pre-slider').css({width:neww});
			}


			// RESCALE SLIDER SELECTED PART
			jQuery('body').on('adamlabsgalleryslide',function(event,$obj) {
				var obj = jQuery($obj);
				setTimeout(function() {
					var neww = parseInt(obj.find('.ui-slider-handle').css('left'),0);
					obj.find('.adamlabsgallery-pre-slider').css({width:neww});
				},10);

			});

	}


	t.initAutocomplete = function(){

		for(var i = 0; i < pages.length; ++i) {
			all_pages.push(pages[i].label);
			mapping[pages[i].label] = pages[i].value;
		}

		jQuery("#pages").autocomplete({
			minLength: 1,
			source: all_pages,
			select: function(event, ui) {

				t.insertSelectedPage(ui.item.value);

				return false;
			}
		});

		jQuery('body').on('click', '.del-page-entry', function(){
			var rem_id = jQuery(this).parent().data('id');
			jQuery('select[name="selected_pages"] option[value="' + rem_id + '"]').attr('selected', false);

			jQuery(this).parent().remove();
		});
	}

	t.insertSelectedPage = function(page_value){
		var last_key = 0;
		var add_id = mapping[page_value];

		if(jQuery('select[name="selected_pages"] option[value="' + add_id + '"]').is(':selected')){ //already inserted
			jQuery('#pages').val('');
			return false;
		}

		jQuery('#pages-wrap').append('<div data-id="'+mapping[page_value]+'">'+page_value+' <i class="adamlabsgallery-icon-trash del-page-entry"></i></div>');

		var sortedDivs = jQuery("#pages-wrap").children().toArray().sort(
			function sorter(a, b) {
				return (jQuery(a).text() > jQuery(b).text()) ? 1 : 0;
			}
		);

		jQuery.each(sortedDivs, function (index, value) {
			jQuery("#pages-wrap").append(value);
		});

		jQuery('select[name="selected_pages"] option[value="' + mapping[page_value] + '"]').attr('selected', true);

		jQuery('#pages').val('');
	}

	/***********************
	* Create Grid Start
	***********************/

	/**
	 * update shortcode from alias value.
	 */
	t.updateShortcode = function(){
		var alias = jQuery("input[name='handle']").val();
		var shortcode = '[adamlabsgallery alias="'+alias+'"]';
		if(alias == ''){
            shortcode = '-- '+adamlabsgallery_lang.aj_wrong_alias+' -- ';
		}

		jQuery('input[name="shortcode"]').val(shortcode);

		jQuery('input[name="ajax-container-shortcode"]').val(shortcode.replace('[adamlabsgallery', '[adamlabsgallery_ajax_target'));

		jQuery('.filter-shortcode-filter').each(function(){
			jQuery(this).val(shortcode.replace('[adamlabsgallery', '[adamlabsgallery_nav id="'+jQuery(this).data('num')+'" '));
		});

	}

	t.checkEvenMasonryInput = function() {
		//if ((jQuery('input[name="layout"]:checked').val()== "even" || jQuery('input[name="layout"]:checked').val()== "cobbles") && jQuery("input[name='layout-sizing']:checked").val() != 'fullscreen') {
		if (jQuery("input[name='layout-sizing']:checked").val() != 'fullscreen') {
			jQuery('#adamlabsgallery-items-ratio-wrap').show();
			if (jQuery('input[name="layout"]:checked').val()== "even")
				jQuery('#adamlabsgallery-content-push-wrap').show();
			else
				jQuery('#adamlabsgallery-content-push-wrap').hide();

		} else {
			jQuery('#adamlabsgallery-content-push-wrap').hide();
			jQuery('#adamlabsgallery-items-ratio-wrap').hide();
		}

		if(jQuery('input[name="layout"]:checked').val() == 'cobbles'){
			jQuery('#adamlabsgallery-cobbles-options').show();
		}else{
			jQuery('#adamlabsgallery-cobbles-options').hide();
		}

		if(jQuery('input[name="layout"]:checked').val() == 'masonry'){
			jQuery('#adamlabsgallery-masonry-options').show();
		}else{
			jQuery('#adamlabsgallery-masonry-options').hide();
		}
		
		if(jQuery('input[name="layout"]:checked').val() == 'masonry' && jQuery('input[name="auto-ratio"]').attr('checked') == 'checked'){
			jQuery('#adamlabsgallery-ratio-wrapper').hide();
		}else{
			jQuery('#adamlabsgallery-ratio-wrapper').show();
		}
		
	}

	t.initCreateGrid = function(doAction){
		
		jQuery("input[name='layout-sizing']").change(function(){
			
			if(jQuery("input[name='layout-sizing']:checked").val() == 'fullscreen') {
				jQuery('#adamlabsgallery-fullscreen-container-wrap').show();
				jQuery('#adamlabsgallery-even-masonry-wrap').hide();
				jQuery('input[name="layout"][value="even"]').click();
				jQuery('input[name="rows-unlimited"][value="off"]').click();
				
				var gStart = jQuery('#grid-start-animation');
				if(gStart.val() === 'reveal') {
					gStart.val('scale').change();
					var aDelay = jQuery('#slider-grid-start-animation-delay');
					if(aDelay.length) {
						if(aDelay.data('sliderinited')) {
							aDelay.slider('value', 1);
							aDelay.find('.adamlabsgallery-pre-slider').width(0);
							jQuery('#grid-start-animation-delay').val('1');
						}
						else {
							var adtimer = setInterval(function() {
								if(aDelay.data('sliderinited')) {
									clearInterval(adtimer);
									aDelay.slider('value', 1);
									aDelay.find('.adamlabsgallery-pre-slider').width(0);
									jQuery('#grid-start-animation-delay').val('1');
								}
							}, 250);
						}
					}
				}
				gStart.find('option[value="reveal"]').prop('disabled', true).hide();
				jQuery('#adamlabsgallery-pagination-wrap').hide();
				t.checkEvenMasonryInput();
			} else {
				jQuery('#adamlabsgallery-fullscreen-container-wrap').hide();
				jQuery('#adamlabsgallery-even-masonry-wrap').show();
				t.checkEvenMasonryInput();
				jQuery('#adamlabsgallery-pagination-wrap').show();
				jQuery('#grid-start-animation').find('option[value="reveal"]').prop('disabled', false).show();
			}
		});
		
		jQuery('input[name="auto-ratio"]').click(function(){
			
			if(jQuery(this).attr('checked') == 'checked'){
				jQuery('#adamlabsgallery-ratio-wrapper').hide();
			}else{
				jQuery('#adamlabsgallery-ratio-wrapper').show();
			}
		});

		jQuery('input[name="layout"]').change(function() {
			t.checkEvenMasonryInput();
		});

		jQuery("input[name='layout-sizing']").change();

		//update shortcode
		jQuery("input[name='handle']").change(function(){
			t.updateShortcode();
		});

		jQuery("input[name='handle']").keyup(function(){
			t.updateShortcode();
		});

		//select shortcode text onclick.
		jQuery("input[name='shortcode']").focus(function(){
			this.select();
		});

		//select shortcode text onclick.
		jQuery('body').on('focus', ".filter-shortcode-filter", function(){
			this.select();
		});

		jQuery("input[name='shortcode']").click(function(){
			this.select();
		});

		t.updateShortcode();

		jQuery('input[name="ajax-container-position"]').click(function(){
			if(jQuery(this).val() == 'shortcode')
				jQuery('#adamlabsgallery-ajax-shortcode-wrapper').show();
			else
				jQuery('#adamlabsgallery-ajax-shortcode-wrapper').hide();
		});
		jQuery('input[name="ajax-container-position"]:checked').click();


		jQuery('#adamlabsgallery-btn-save-grid').click(function(){
			t.removeRedHighlighting();

			var errors = 0;

			var data = {
				name: jQuery.trim(jQuery('input[name="name"]').val()),
				handle: jQuery.trim(jQuery('input[name="handle"]').val()), //is alias
				postparams: t.getFormParams('adamlabsgallery-form-create-posts'),
				params: t.getFormParams('adamlabsgallery-form-create-settings')
			};

			data.postparams['youtube-default-image'] = jQuery("#adamlabsgallery-youtube-default-image").val();

			data.params['css-id'] = jQuery('#adamlabsgallery-id-value').val();

			data.params['navigation-layout'] = t.get_navigation_layout();
			data.params['custom-javascript'] = adamlabsgallery_codemirror_api_js.getValue();
			data.params['ajax-container-css'] = adamlabsgallery_codemirror_ajax_css.getValue();
			
			data.params['custom-filter'] = adamlabsgallery_filter_handles_selected;

			delete data['postparams']['search_pages']; //unused
			delete data['params']['do-not-save']; //unused
			delete data['params']['ajax-container-shortcode']; //unused

			if(jQuery('input[name="source-type"]:checked').val() == 'custom'){
				var custom_layers = t.getFormParams('adamlabsgallery-custom-elements-form-wrap');
				data.layers = (typeof custom_layers['layers'] !== 'undefined') ? custom_layers['layers'] : [];

				if(typeof custom_layers['layers'] === 'undefined'){
					errors++;
					t.showErrorMessage(adamlabsgallery_lang.add_at_least_one_element);
				}
			}

			if(data.name.length < 2 || data.name.length > 255){
				t.addRedHighlighting('input[name="name"]');
				errors++;
				jQuery('#adamlabsgallery-naming-tab').click();
			}
			if(data.handle.length < 2 || data.handle.length > 255){
				t.addRedHighlighting('input[name="handle"]');
				errors++;
				jQuery('#adamlabsgallery-naming-tab').click();
			}

			if(errors == 0){ //do update
				//add slider id to the data
				if(doAction == 'update_grid'){
					data.id = jQuery('input[name="adamlabsgallery-id"]').val();
				}

				//start update/insert process
				t.ajaxRequest("update_create_grid", data, '.save-wrap-settings');
			}
		});

		jQuery('#adamlabsgallery-btn-delete-grid').click(function(){
			var delete_id = jQuery('input[name="adamlabsgallery-id"]').val();

			var data = { id: delete_id }

			if(confirm(adamlabsgallery_lang.delete_grid)){
				t.ajaxRequest("delete_grid", data, '.save-wrap-settings');
			}
		});

		t.build_filter_tab = function(filter_sel, wrap, filter_name, nr){

			var filter = []; //save who is checked
			jQuery('.'+filter_sel+':checked').each(function(){
				filter.push(jQuery(this).val());
			});

			var filter_all = []; //save the order
			jQuery('.'+filter_sel).each(function(){
				filter_all.push(jQuery(this).val());
			});
			
			//push custom wanted also in here
			
			
			var cur_selected = jQuery('select[name="post_category"]').val(); //currently selected categories and tags

			if(cur_selected == null) cur_selected = [];

			//add available metas here
			/*
			if(typeof(adamlabsgallery_meta_handles) !== 'undefined'){
				for(var key in adamlabsgallery_meta_handles){
					cur_selected.push(key);
				}
			}
			*/
			
			//add available metas here
			if(typeof(adamlabsgallery_filter_handles_selected) !== 'undefined'){
				for(var key in adamlabsgallery_filter_handles_selected){
					if(jQuery.inArray(key, cur_selected) === -1){
						cur_selected.push(key);
					}else{
						delete(adamlabsgallery_filter_handles_selected[key]);
					}
				}
			}

			jQuery('.'+wrap).html('');

			for(var fa_key in filter_all){
				if(cur_selected !== null && cur_selected.indexOf(filter_all[fa_key]) > -1){ //still exists add it
					var opt_html = '';
					var opt_name = (filter_all[fa_key].indexOf('meta-') > -1) ? adamlabsgallery_meta_handles[filter_all[fa_key]]+' '+adamlabsgallery_lang.meta_val : jQuery('select[name="post_category"] option[value="'+filter_all[fa_key]+'"]').html();
					
					/* 2.1.5 */
					if(opt_name == undefined) continue;
					
					opt_html = '<div class="adamlabsgallery-media-source-order button-primary"><span style="float:left">'+opt_name+'</span><input class="adamlabsgallery-get-val adamlabsgallery-filter-input '+filter_sel+'" type="checkbox" name="'+filter_name+'" data-origname="filter-selected-#NR[]" value="'+filter_all[fa_key]+'" /><div style="clear:both"></div></div>';
					jQuery('.'+wrap).append(opt_html);

					//now check if it should be selected
					if(filter !== null && filter.indexOf(filter_all[fa_key]) > -1) jQuery('.'+filter_sel+'[value="'+filter_all[fa_key]+'"]').attr('checked', 'checked');

					//remove element from the cur_selected array so that we have in the end only elements that need to be added to the list. (also need to be checked because they are new)
					delete(cur_selected[cur_selected.indexOf(filter_all[fa_key])]);
				}
			}

			if(cur_selected !== null){
				
				for(var key in cur_selected){
					var opt_html = '';
					var opt_name = (cur_selected[key].indexOf('meta-') > -1) ? adamlabsgallery_meta_handles[cur_selected[key]]+' '+adamlabsgallery_lang.meta_val : jQuery('select[name="post_category"] option[value="'+cur_selected[key]+'"]').html();
					
					/* 2.1.5 */
					if(opt_name == undefined) continue;
					
					opt_html = '<div class="adamlabsgallery-media-source-order button-primary"><span style="float:left">'+opt_name+'</span><input class="adamlabsgallery-get-val adamlabsgallery-filter-input '+filter_sel+'" type="checkbox" name="'+filter_name+'" data-origname="filter-selected-#NR[]" value="'+cur_selected[key]+'" /><div style="clear:both"></div></div>';
					jQuery('.'+wrap).append(opt_html);

					if(!filter_startup){
						jQuery('.'+filter_sel+'[value="'+cur_selected[key]+'"]').attr('checked', 'checked');
					}
				}
			}

			//check if all exist, if not add the missing ones
			if(typeof(nr) !== 'undefined' && jQuery('.'+wrap).length > 0){
				if(jQuery('.adamlabsgallery-navigation-cons-filter-'+nr).length === 0){
					//add filter button for dropdown
					jQuery('.adamlabsgallery-navigation-cons-wrapper').append('<div data-navtype="filter-'+nr+'" class="adamlabsgallery-navigation-cons-filter-'+nr+' adamlabsgallery-nav-cons-filter adamlabsgallery-navigation-cons"><i class="adamlabsgallery-icon-megaphone"></i>'+adamlabsgallery_lang.filter+' '+nr+'</div>');
				}
			}
		}

		adamlabsgallery_postTypesWithCats = jQuery.parseJSON(adamlabsgallery_jsonTaxWithCats);

		jQuery('select[name="post_types"]').change(function(){
			var arrTypes = jQuery(this).val();
			var is_page_active = false;

			jQuery('#set-pages-wrap').hide();
			jQuery('#adamlabsgallery-post-cat-wrap').hide();

			//replace the categories in multi select
			jQuery('select[name="post_category"]').empty();
			jQuery(arrTypes).each(function(index,postType){
				var objCats = adamlabsgallery_postTypesWithCats[postType];
				if(postType == 'page') jQuery('#set-pages-wrap').show();
				if(postType != 'page') jQuery('#adamlabsgallery-post-cat-wrap').show();

				var flagFirst = true;

				for(catIndex in objCats){
					var catTitle = objCats[catIndex];
					//add option to cats select
					var opt = new Option(catTitle, catIndex);

					if(catIndex.indexOf("option_disabled") == 0)
						jQuery(opt).prop("disabled","disabled");
					else{
						//select first option:
						if(flagFirst == true){
							jQuery(opt).prop("selected","selected");
							flagFirst = false;
						}
					}

					jQuery('select[name="post_category"]').append(opt);

				}
			});
			
			/* 2.1.5 */
			// populate "custom-filter-select" list every time a post-type is added/removed
			adamlabsgallery_filter_handles = [];
			jQuery('select[name="post_category"] option').each(function(){
				adamlabsgallery_filter_handles[this.value] = jQuery(this).text();
			});
			
			/* 2.1.5 */
			// update filter selection lists on a post-type change
			jQuery('select[name="post_category"]').change();

		});
		
		/* 2.1.5 */
		// prevent category list from showing as empty if no post types are selected
		if(!jQuery('select[name="post_category"] option').length) {
			
			jQuery('select[name="post_types"]').change();
			jQuery('select[name="post_category"] option:selected').prop('selected', false);
			
		}

		jQuery('input[name="filter-meta-key"]').autocomplete({
			source: arr_meta_keys,
			minLength:0
		});

		//open the list on right button
		jQuery('.filter-meta-selector').click(function(event){
			event.stopPropagation();
			if(jQuery('input[name="filter-meta-key"]').data('is_open') == true){
				jQuery('input[name="filter-meta-key"]').autocomplete('close');
			}
			else {  //else open autocomplete
				//jQuery('input[name="filter-meta-key"]').autocomplete('search', '').data('ui-autocomplete');
				jQuery('input[name="filter-meta-key"]').autocomplete({
					source: arr_meta_keys,
					minLength:0
				});
			}
		});

		// SHOW / HIDE WARNING ABOUT SMALL CACHE
		function checkSmallCache() {
			jQuery('.cachenumbercheck').each(function() {
				var me = jQuery(this),
					inp = me.find('input'),
					lab = me.find('.showonsmallcache');
				if (inp.val()<3600) 
					lab.show()
				else
					lab.hide();
			})			
		}

		checkSmallCache();
		jQuery('.cachenumbercheck input').change(checkSmallCache);

		// SHOW/HIDE FILTERS
		jQuery('body').on('mouseenter','.inst-filter-griditem',function() {
			adamlabsgallerygs.TweenLite.to(jQuery(this).find('.inst-filter-griditem-img'),0.5,{autoAlpha:0});
		})
		jQuery('body').on('mouseleave','.inst-filter-griditem',function() {
			adamlabsgallerygs.TweenLite.to(jQuery(this).find('.inst-filter-griditem-img'),0.5,{autoAlpha:1});
		});

		jQuery('body').on('click','.inst-filter-griditem',function() {
			var a = jQuery(this);
			jQuery('#media-filter-type option:selected').removeAttr('selected');
			jQuery('#media-filter-type option[value="'+a.data("type")+'"]').attr('selected','selected');
			jQuery('.inst-filter-griditem.selected').removeClass("selected")
			a.addClass("selected");
		});

		// SHOW / HIDE AVAILALE MEDIA ELEMENTS IN LIGHTBOX, AJAX AND MEDIA SOURCE
		function qCheckAC(el) {
			
			return (!el.hasClass("notavailable") && el.find('input').is(":checked"))
		}
		function checkAvailablePosters() {
			
			jQuery('#pso-list').find('.adamlabsgallery-media-source-order').each(function() {
				jQuery(this).addClass("notavailable");
			});
			
			jQuery('.default-posters').each(function(){
				jQuery(this).addClass("notavailable");
			});

			var gt = jQuery('input[name="source-type"]:checked').val(),
				any = false,
				/*obj = {	fei:{a:false, b:"#pso-featured-image"},
						alt:{a:false, b:"#pso-alternate-image"},
						fci:{a:false, b:"#pso-content-image"},
						ydi:{a:false, b:"#pso-default-youtube-image"},
						vdi:{a:false, b:"#pso-default-vimeo-image"},
						hdi:{a:false, b:"#pso-default-html-image"},						
						yth:{a:false, b:"#pso-youtube-image"},
						vth:{a:false, b:"#pso-vimeo-image"},
						ni:{a:false, b:"#pso-no-image"}};*/

				obj = {	fei:{a:false, b:"#pso-featured-image"},
						alt:{a:false, b:"#pso-alternate-image"},
						fci:{a:false, b:"#pso-content-image"},
						ydi:{a:false, b:"#pso-default-youtube-image,#adamlabsgallery-youtube-default-poster"},
						vdi:{a:false, b:"#pso-default-vimeo-image,#adamlabsgallery-vimeo-default-poster"},
						hdi:{a:false, b:"#pso-default-html-image,#adamlabsgallery-html5-default-poster"},
						yth:{a:false, b:"#pso-youtube-image"},
						vth:{a:false, b:"#pso-vimeo-image"},
						ni:{a:false, b:"#pso-no-image"}};
				
			if  (qCheckAC(jQuery('#imso-youtube')) || qCheckAC(jQuery('#imso-content-youtube'))) {
				obj.fei.a=true;
				obj.alt.a=true;
				obj.fci.a=true;
				obj.ni.a=true;
				obj.ydi.a=true;
				obj.yth.a=true;				
			}

			if  (qCheckAC(jQuery('#imso-vimeo')) || qCheckAC(jQuery('#imso-content-vimeo'))) {
				obj.fei.a=true;
				obj.alt.a=true;
				obj.fci.a=true;
				obj.ni.a=true;
				obj.vdi.a=true;
				obj.vth.a=true;				
			}

			if  (qCheckAC(jQuery('#imso-html5')) || qCheckAC(jQuery('#imso-content-html5'))) {
				obj.fei.a=true;
				obj.alt.a=true;
				obj.fci.a=true;
				obj.ni.a=true;
				obj.hdi.a=true;
				obj.vth.a=true;				
			}
			
			if  (qCheckAC(jQuery('#imso-wistia')) || qCheckAC(jQuery('#imso-soundcloud'))) {
				obj.fei.a=true;
				obj.alt.a=true;
				obj.fci.a=true;
				obj.ni.a=true;
			}						
			
			jQuery.each(obj,function(i,el){
				if (el.a)
					jQuery(el.b).removeClass("notavailable");
			})
		}

		// SHOW/HIDE THE AVAILABLE MEDIA SOUERCES ON DEMAND
		function checkAvailableMedias() {
			var gt = jQuery('input[name="source-type"]:checked').val(),
				st = jQuery('input[name="stream-source-type"]:checked').val();
			if (gt!=="post") {
				jQuery('#imso-list,#lbo-list, #ajo-list').find('.adamlabsgallery-media-source-order').each(function() {
					jQuery(this).addClass("notavailable");
				});				
			}
			jQuery(".adamlabsgallery-navigation-cons-search-input,.adamlabsgallery-navigation-cons-filter,.adamlabsgallery-navigation-cons-filter-input,.filter_groups").css('display','inline');
			jQuery('.search_settings').show();
			switch (gt) {
				case "post":
					jQuery('#imso-list, #lbo-list, #ajo-list').find('.adamlabsgallery-media-source-order').each(function() {
						jQuery(this).removeClass("notavailable");
					});
					
				break;
				case "custom":
					jQuery('#imso-list, #lbo-list, #ajo-list').find('.adamlabsgallery-media-source-order').each(function(i) {
						var id = this.id.indexOf("imso-content-",0);
						
						if (id!=-1)
							jQuery(this).addClass("notavailable");
						else
							jQuery(this).removeClass("notavailable");

					});
					jQuery('#ajo-revslider').addClass("notavailable");
					jQuery('#ajo-content-image').addClass("notavailable");
				break;
				case "stream":
					jQuery('#imso-'+st+', #lbo-'+st+', #ajo-'+st).removeClass("notavailable");
					switch (st) {
						case "instagram":
							jQuery('#imso-featured-image, #lbo-featured-image, #ajo-featured-image').removeClass("notavailable");
							jQuery('#imso-html5,#lbo-html5').removeClass("notavailable");
						break;
						case "twitter":
							jQuery('#imso-featured-image,#lbo-featured-image,#ajo-featured-image').removeClass("notavailable");							
						break;
						case "flickr":
							jQuery('#imso-featured-image,#lbo-featured-image,#ajo-featured-image').removeClass("notavailable");							
						break;
						case "behance":
							jQuery('#imso-featured-image,#lbo-featured-image,#ajo-featured-image').removeClass("notavailable");
						break;
						case "facebook":
							jQuery('#imso-featured-image,#lbo-featured-image,#ajo-featured-image').removeClass("notavailable");
							jQuery('#imso-html5,#lbo-html5,#ajo-html5').removeClass("notavailable");
							jQuery('#imso-youtube,#lbo-youtube,#ajo-youtube').removeClass("notavailable");
						break;
					}		
					jQuery(".adamlabsgallery-navigation-cons-search-input,.adamlabsgallery-navigation-cons-filter,.adamlabsgallery-navigation-cons-filter-input,.filter_groups,.search_settings").hide();
				break;
				case "rml":
				case "nextgen":
					jQuery('#imso-featured-image, #lbo-featured-image, #ajo-featured-image').removeClass("notavailable");
					jQuery(".adamlabsgallery-navigation-cons-search-input,.adamlabsgallery-navigation-cons-filter,.adamlabsgallery-navigation-cons-filter-input,.filter_groups,.search_settings").hide();
				break;
			}	

			jQuery("#lbo-list div.notavailable input, #lbo-btn-list div.notavailable input").each(function(){
				jQuery(this).prop( "checked", false );
			});

			checkAvailablePosters();			
		}
		
		/* 2.1.6.2 */
		jQuery('.grid-item-anime-select').on('change', function() {
			
			var $this = jQuery(this),
				container = $this.closest('p');
			
			container.find('.grid-item-anime-option').hide();
			container.find('.grid-item-anime-wrap-' + $this.val()).show();
			
		}).change();
		
		/* 2.2 */
		jQuery('.lightbox-post-content-img').on('change', function() {
			
			var action = jQuery('.lightbox-post-content-img:checked').val() === 'on' ? 'addClass' : 'removeClass';
			jQuery(this).closest('.adamlabsgallery-creative-settings')[action]('show-featured-img-settings');
			
		}).change();


		jQuery('#adamlabsgallery-source-choose-wrapper input').change(checkAvailableMedias);
		jQuery('#imso-list input').change(checkAvailablePosters);


		/**
		 * function that populates the three filter selectboxes
		 **/
		jQuery('select[name="post_category"]').change(function(){

			t.build_filter_tab('adamlabsgallery-filter-selected', 'adamlabsgallery-filter-selected-order-wrap', 'filter-selected[]');

			//do all also for the other elements
			if(adamlabsgallery_filter_counter > 1 || typeof(jQuery('input[name="filter-all-text-1"]')) !== 'undefined'){
				for(var i = 1; i <= adamlabsgallery_filter_counter; i++){

					t.build_filter_tab('adamlabsgallery-filter-selected-'+i, 'adamlabsgallery-filter-selected-order-wrap-'+i, 'filter-selected-'+i+'[]', i);
					jQuery('.adamlabsgallery-filter-selected-order-wrap-'+i).closest('.adamlabsgallery-filter-options-wrap').find('.adamlabsgallery-remove-filter-tab').show();

				}
			}
			filter_startup = false;
		});
		jQuery('select[name="post_category"]').change(); //to propagate filter dropdowns

		jQuery('select[name="filter2-type"]').change(function(){
			if(jQuery(this).val() == 'custom')
				jQuery('#adamlabsgallery-filter2-sel-wrap').show();
			else
				jQuery('#adamlabsgallery-filter2-sel-wrap').hide();
		});


		jQuery('select[name="filter3-type"]').change(function(){
			if(jQuery(this).val() == 'custom')
				jQuery('#adamlabsgallery-filter3-sel-wrap').show();
			else
				jQuery('#adamlabsgallery-filter3-sel-wrap').hide();
		});


		//show/hide page selector depending on what is selected at start
		var sel = jQuery('select[name="post_types"]').val();
		jQuery('#set-pages-wrap').hide();
		jQuery('#adamlabsgallery-post-cat-wrap').hide();
		jQuery(sel).each(function(index,postType){
			if(postType == 'page') jQuery('#set-pages-wrap').show();
			if(postType != 'page') jQuery('#adamlabsgallery-post-cat-wrap').show();

		});


		jQuery('input[name="layout"]').click(function(){
			if(jQuery(this).val() == 'even')
				jQuery('#adamlabsgallery-layout-even-ratio').show();
			else
				jQuery('#adamlabsgallery-layout-even-ratio').hide();
		});
		
		if(typeof RevColor === 'undefined') {
			jQuery('#main-background-color').wpColorPicker({
				change:function() {
					setTimeout(function() {
						jQuery('#adamlabsgallery-live-preview-wrap').css({backgroundColor:jQuery('#main-background-color').val()});
					},50);
				}
			});
			jQuery('#spinner_color').wpColorPicker({
				change:function() {
					setTimeout(function() {
						t.spinnerColorChange();
					},50);
				}
			});

			jQuery('#lazy-load-color').wpColorPicker();
		}
		else {
			jQuery('#main-background-color').AdamLabsColorPicker({
				change: function() {
					var clr = jQuery('#main-background-color');
					jQuery('#adamlabsgallery-live-preview-wrap').css('background', clr.attr('data-color') || clr.val());
				},
				wrapper:'<span class="rev-colorpickerspan"></span>'  
			});
			jQuery('#spinner_color').AdamLabsColorPicker({
				change:function() {
					t.spinnerColorChange();
				},
				wrapper:'<span class="rev-colorpickerspan"></span>'  
			});
			jQuery('#lazy-load-color').AdamLabsColorPicker({wrapper:'<span class="rev-colorpickerspan"></span>'});
		}

		
		
		if(typeof RevColor === 'undefined') {
			jQuery('#adamlabsgallery-live-preview-wrap').css('background', jQuery('#main-background-color').val());
		}
		else {
			jQuery('#adamlabsgallery-live-preview-wrap').css('background', RevColor.process(jQuery('#main-background-color').val())[0]);
		}

		jQuery('input[name="rows-unlimited"]').change(function(){
			if(jQuery(this).val() == 'off'){
				jQuery('.load-more-wrap').hide();
				jQuery('.rows-num-wrap').show();
			}else{
				jQuery('.load-more-wrap').show();
				jQuery('.rows-num-wrap').hide();
			}
		});

		jQuery('select[name="load-more"]').change(function(){
			if(jQuery('input[name="rows-unlimited"]:checked').val() == 'on'){
				if(jQuery(this).val() == 'scroll'){
					jQuery('.load-more-hide-wrap').show();
				}else{
					jQuery('.load-more-hide-wrap').hide();
				}
			}else{
				jQuery('.load-more-hide-wrap').hide();
			}
		});

		jQuery('input[name="columns-advanced"]').change(function(){
			if(jQuery(this).val() == 'on') {
				jQuery('.columns-width').show();
				jQuery('.columns-height').show();
				jQuery('.columns-sliding').hide();
				for (var i=0;i<8;i++) {
					jQuery('#slider-columns-'+i).addClass("shortform");
				}
			} else {
				jQuery('.columns-width').hide();
				jQuery('.columns-height').hide();
				jQuery('.columns-sliding').show();
				for (var i=0;i<8;i++) {
					jQuery('#slider-columns-'+i).removeClass("shortform");
				}

			}

			t.calc_advanced_rows(jQuery(this).val());
		});

		if(jQuery('input[name="columns-advanced"]:checked').val() == 'on'){
			jQuery('.columns-width').show();
			jQuery('.columns-height').show();
			jQuery('.columns-sliding').hide();
		}

		t.calc_advanced_rows(jQuery('input[name="columns-advanced"]:checked').val());


		jQuery('body').on('click', '#adamlabsgallery-add-column-advanced', function(){
			var len = jQuery('.columns-adv-head').length;

			if(len == 9) return true;

			var col = [];

			if(len == 0){
				col[0] = jQuery('#columns-1').val();
				col[1] = jQuery('#columns-2').val();
				col[2] = jQuery('#columns-3').val();
				col[3] = jQuery('#columns-4').val();
				col[4] = jQuery('#columns-5').val();
				col[5] = jQuery('#columns-6').val();
				col[6] = jQuery('#columns-7').val();
			}else{
				var c = len - 1;

				jQuery('input[name="columns-advanced-rows-'+c+'[]"]').each(function(e){
					col[e] = jQuery(this).val();
				});
			}

			jQuery('#adamlabsgallery-col-00').append('<td class="columns-adv-'+len+' columns-adv-rows columns-adv-head" style="text-align: center;position:relative;"></td>');
			jQuery('#adamlabsgallery-col-1').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[0]+'" /></td>');
			jQuery('#adamlabsgallery-col-2').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[1]+'" /></td>');
			jQuery('#adamlabsgallery-col-3').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[2]+'" /></td>');
			jQuery('#adamlabsgallery-col-4').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[3]+'" /></td>');
			jQuery('#adamlabsgallery-col-5').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[4]+'" /></td>');
			jQuery('#adamlabsgallery-col-6').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[5]+'" /></td>');
			jQuery('#adamlabsgallery-col-7').append('<td class="columns-adv-'+len+' columns-adv-rows" style="position:relative;"><input class="input-settings-small" type="text" name="columns-advanced-rows-'+len+'[]" value="'+col[6]+'" /></td>');

			t.calc_advanced_rows(jQuery('input[name="columns-advanced"]:checked').val());

		});

		jQuery('body').on('click', '#adamlabsgallery-remove-column-advanced', function(){
			var len = jQuery('.columns-adv-head').length;

			if(len == 0) return true;

			len--;

			jQuery('.columns-adv-'+len).remove();

			t.calc_advanced_rows(jQuery('input[name="columns-advanced"]:checked').val());

		});

		t.getPagesDialog();

		jQuery('#navigation-skin-select').change(function(){
			/*if(jQuery('#navigation-skin-select option:selected').hasClass('custom-skin')){
			}else{
				jQuery('#adamlabsgallery-edit-navigation-skin').hide();
			}*/

			//jQuery('#adamlabsgallery-edit-navigation-skin').show();
		});


		/**
		 * Change new Navigation Skin
		 */
		jQuery('#adamlabsgallery-edit-navigation-skin').click(function(){
			var skin_handle = jQuery('#navigation-skin-select option:selected').val();
			t.open_navigation_skin_dialog(skin_handle);
		});


		/**
		 * Delete selected Navigation Skin
		 */
		jQuery('#adamlabsgallery-delete-navigation-skin').click(function(){
			if(confirm(adamlabsgallery_lang.deleting_nav_skin_message)){
				var skin_handle = jQuery('#navigation-skin-select option:selected').val();
				var data = {skin: skin_handle};

				AdminEssentials.ajaxRequest("delete_navigation_skin_css", data, '#adamlabsgallery-edit-navigation-skin,#adamlabsgallery-create-navigation-skin,#adamlabsgallery-delete-navigation-skin',function(response){
					if(response.success == true){

						jQuery('#navigation-styling-css-wrapper').html(response.css);
						jQuery('#navigation-skin-select').html(response.select);

						jQuery('#navigation-skin-select option:first').attr("selected","selected");

						t.changePreviewGrid();

						adamlabsgallery_codemirror_navigation_css_default_skin = jQuery.extend({}, response.default_skins);

						jQuery('#navigation-skin-css-edit-dialog-wrap').dialog('close');
					}
				});
			}
		});



		/**
		 * Create new Navigation Skin
		 */
		jQuery('#adamlabsgallery-create-navigation-skin').click(function(){
			var nav_skin_name = prompt(adamlabsgallery_lang.please_enter_unique_skin_name);
            if(nav_skin_name == null) return false;

            if(nav_skin_name.length < 2){
			    alert(adamlabsgallery_lang.skin_name_too_short);
                return false;
            }

			var nav_skin_name_sanitize = t.sanitize_input(nav_skin_name);
			for(var key in adamlabsgallery_codemirror_navigation_css_default_skin){
				if(adamlabsgallery_codemirror_navigation_css_default_skin[key]['handle'] == nav_skin_name_sanitize){
					alert(adamlabsgallery_lang.skin_name_already_registered);
					return false;
				}
			}

			t.open_navigation_skin_dialog(nav_skin_name);
		});



		t.open_navigation_skin_dialog = function(skin_handle){
			var exist = false;

			for(var key in adamlabsgallery_codemirror_navigation_css_default_skin){
				if(adamlabsgallery_codemirror_navigation_css_default_skin[key]['handle'] == skin_handle){
					adamlabsgallery_codemirror_navigation_css.setValue(adamlabsgallery_codemirror_navigation_css_default_skin[key]['css']);
					exist = adamlabsgallery_codemirror_navigation_css_default_skin[key]['id'];
					break;
				}
			}

			if(exist == false){ //not found, use first entry for referal, we create a new skin now
				for(var key in adamlabsgallery_codemirror_navigation_css_default_skin){
					var san_skin = t.sanitize_input(skin_handle);
					var han_skin = adamlabsgallery_codemirror_navigation_css_default_skin[key]['handle'];

					nav_css = adamlabsgallery_codemirror_navigation_css_default_skin[key]['css'];
					nav_css = nav_css.split('.'+han_skin).join('.'+san_skin);

					adamlabsgallery_codemirror_navigation_css.setValue(nav_css);
					break;
				}
			}


			jQuery("#navigation-skin-css-edit-dialog-wrap").dialog({
				modal:true,
				draggable:true,
				resizable:false,
				width:632,
				height:565,
				closeOnEscape:true,
				buttons: [ { text: adamlabsgallery_lang.create_nav_skin+': '+skin_handle, click: function() {
					var data = {
						skin_css: adamlabsgallery_codemirror_navigation_css.getValue()
					};

					if(exist !== false){ //change existing skin
						data.sid = exist;
					}else{ //create skin
						data.name = skin_handle;
					}

					AdminEssentials.ajaxRequest("update_create_navigation_skin_css", data, '.ui-button',function(response){

						if(response.success == true){
							if(exist !== false)
								var do_select = jQuery('#navigation-skin-select option:selected').val();
							else
								var do_select = t.sanitize_input(skin_handle);

							jQuery('#navigation-styling-css-wrapper').html(response.css);
							jQuery('#navigation-skin-select').html(response.select);

							jQuery('#navigation-skin-select option[value="'+do_select+'"]').attr("selected","selected");

							t.changePreviewGrid();

							adamlabsgallery_codemirror_navigation_css_default_skin = jQuery.extend({}, response.default_skins);

							jQuery('#navigation-skin-css-edit-dialog-wrap').dialog('close');
						}
					});

				} } ],
				dialogClass:'wp-dialog',
				open: function(){
					//jQuery('#adamlabsgallery-nav-skins-select').prependTo('.ui-dialog-buttonpane');
				},
				close: function(){
					//jQuery('#adamlabsgallery-nav-skins-select').prependTo('.ui-dialog-buttonpane');
				}
			});

			adamlabsgallery_codemirror_navigation_css.refresh();
		}

		adamlabsgallery_codemirror_navigation_css = CodeMirror.fromTextArea(document.getElementById("adamlabsgallery-navigation-skin-css-editor"), {
			lineNumbers: true
		});

		adamlabsgallery_codemirror_navigation_css.setSize(632, 482);
		
		jQuery('.adamlabsgallery-navigation-drop-inner, .adamlabsgallery-navigation-cons-wrapper').sortable({
			
			connectWith: ".adamlabsgallery-navigation-drop-inner, .adamlabsgallery-navigation-cons-wrapper",
			revert: true,
			over: function(event, ui) {
				/*if(!ui.item.hasClass('adamlabsgallery-navigation-cons-right') && !ui.item.hasClass('adamlabsgallery-navigation-cons-left')){
					var elid = ui.item.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id');
					if(elid != 'adamlabsgallery-navigations-sort-left' && elid != 'adamlabsgallery-navigations-sort-right')
						return false;
				}*/

				jQuery(this).addClass("adamlabsgallery-navigation-drop-inner-hovered");
			},
			stop: function(event, ui){

				/*if(!ui.item.hasClass('adamlabsgallery-navigation-cons-right') && !ui.item.hasClass('adamlabsgallery-navigation-cons-left')){
					var elid = ui.item.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id');
					if(elid != 'adamlabsgallery-navigations-sort-left' && elid != 'adamlabsgallery-navigations-sort-right')
						return false;
				}*/

				jQuery(this).removeClass("adamlabsgallery-navigation-drop-inner-hovered");
			},
			receive: function(event,ui) {
				if(ui.item.closest('#adamlabsgallery-navigations-sort-external').length == 1){
					//add fields if not already existing
					if(ui.item.find('.adamlabsgallery-filter-sc').length == 0){

						var item_skin = jQuery('#navigation-skin-select').clone().wrap('<div></div>');
						item_skin.attr('id', '');
						item_skin.attr('name', 'navigation-special-skin[]');

						var new_item = '<div class="adamlabsgallery-filter-sc"><input class="filter-shortcode-filter" type="text" readonly="readonly" data-num="'+ui.item.data('navtype')+'" /><input type="text" name="navigation-special-class[]" value="" />';
						new_item += item_skin.parent().html();
						new_item += '</div>';

						ui.item.append(new_item);
						t.updateShortcode();
					}else{ //already existing

					}
				}else{
					if(ui.item.closest('.adamlabsgallery-navigation-default-wrap').length == 1){
						jQuery('.adamlabsgallery-stay-last-element').appendTo('.adamlabsgallery-navigation-default-wrap');
					}

					//remove fields if they exist
					if(ui.item.find('.adamlabsgallery-filter-sc').length == 1){
						ui.item.find('.adamlabsgallery-filter-sc').remove();
					}
				}

			},
			out: function(event,ui) {

				jQuery(this).removeClass("adamlabsgallery-navigation-drop-inner-hovered");
			}
		});

		jQuery('.adamlabsgallery-media-source-order-wrap .adamlabsgallery-media-source-order').adamlabsortable({

		});


		/**
		 * set options for posts
		 */

		jQuery('body').on('click', '.adamlabsgallery-btn-activate-post-item', function(){
			var cur_post_id = jQuery(this).attr('id').replace('adamlabsgallery-act-post-item-', '');
			var cur_grid_id = jQuery('input[name="adamlabsgallery-id"]').val();

			var data = { post_id: cur_post_id, grid_id: cur_grid_id };

			AdminEssentials.ajaxRequest("trigger_post_meta_visibility", data, '.adamlabsgallery-btn-activate-post-item',function(response){
				if(typeof(response.success != 'undefined') && response.success == true){
					if(jQuery('#adamlabsgallery-act-post-item-'+cur_post_id).children().hasClass('adamlabsgallery-icon-eye')) {
						jQuery('#adamlabsgallery-act-post-item-'+cur_post_id).children().removeClass('adamlabsgallery-icon-eye').addClass('adamlabsgallery-icon-eye-off');
						jQuery('#adamlabsgallery-act-post-item-'+cur_post_id).removeClass("revblue").addClass("revred");
					} else {
						jQuery('#adamlabsgallery-act-post-item-'+cur_post_id).children().removeClass('adamlabsgallery-icon-eye-off').addClass('adamlabsgallery-icon-eye');
						jQuery('#adamlabsgallery-act-post-item-'+cur_post_id).removeClass("revred").addClass("revblue");
					}
				}
			});

		});


		jQuery('body').on('click', '.adamlabsgallery-btn-edit-post-item', function(){
			var cur_post_id = jQuery(this).attr('id').replace('adamlabsgallery-edit-post-item-', '');
			var cur_grid_id = jQuery('input[name="adamlabsgallery-id"]').val();
			var data = { post_id: cur_post_id, grid_id: cur_grid_id };

			AdminEssentials.ajaxRequest("get_post_meta_html_for_editor", data, '.adamlabsgallery-btn-edit-post-item',function(response){
				if(typeof(response.success != 'undefined') && response.success == true){

					jQuery('#adamlabsgallery-meta-box').html(response.data.html);

					document.getElementById('adamlabsgallery-form-post-meta-settings').reset();

					jQuery('#post-meta-dialog-wrap').dialog({
						modal:true,
						draggable:true,
						resizable:false,
						width:850,
						height:600,
						closeOnEscape:true,
						dialogClass:'wp-dialog',
						buttons: [ { text: adamlabsgallery_lang.save_post_meta, click: function() {

							var data = {
								metas: t.getFormParams('adamlabsgallery-form-post-meta-settings')
							};

							data.metas['grid_id'] = jQuery('input[name="adamlabsgallery-id"]').val();

							AdminEssentials.ajaxRequest("update_post_meta_through_editor", data, '.ui-button',function(response){
								t.changePreviewGrid(true);
								document.getElementById('adamlabsgallery-form-post-meta-settings').reset();
								jQuery('#post-meta-dialog-wrap').dialog('close');
							});

						} } ],
					});
					
				}
			});
		});


		/**
		 * Custom Create Grid Switch
		 */

		var do_change = false;

		jQuery('input[name="source-type"]').change(function(){
			var set = jQuery(this).val();
			
			/* 2.2.5 */
			var method = set === 'post' || set === 'custom' ? 'removeClass' : 'addClass';
			jQuery('body')[method]('hide-adamlabsgallery-item-settings');
			
			jQuery('body').removeClass('hide-custom-options');
			
			switch(set){
				case 'post':
					jQuery('#post-pages-wrap').show();
					jQuery('#set-pages-wrap').show();
					jQuery('.filter-only-for-post').show();
					jQuery('.available-filters-in-group').show();
					jQuery('#aditional-pages-wrap').show();
					jQuery('#custom-sorting-wrap').hide();
					//jQuery('#adamlabsgallery-external-drag-wrap').show();
					jQuery('#custom-element-add-elements-wrapper').hide();
					jQuery('#all-stream-wrap').hide();
					jQuery('#media-source-order-wrap').show();
					jQuery('#media-source-sizes').show();
					jQuery('#all-nextgen-wrap').hide();
					jQuery('#all-rml-wrap').hide();
					break;
				case 'custom':
					jQuery('#post-pages-wrap').hide();
					jQuery('#set-pages-wrap').hide();
					jQuery('.filter-only-for-post').hide();
					jQuery('.available-filters-in-group').hide();
					jQuery('#aditional-pages-wrap').hide();
					jQuery('#custom-sorting-wrap').show();
					//jQuery('#adamlabsgallery-external-drag-wrap').hide();
					jQuery('#custom-element-add-elements-wrapper').show();
					jQuery('#media-source-order-wrap').show();
					jQuery('#media-source-sizes').show();
					//move all elements back to start
					/*jQuery('#adamlabsgallery-navigations-sort-external .adamlabsgallery-navigation-drop-inner div').each(function(){
						jQuery(this).appendTo('.adamlabsgallery-navigation-cons-wrapper');
						jQuery('.adamlabsgallery-filter-sc').remove();
					});*/
					jQuery('#all-stream-wrap').hide();
					jQuery('#all-nextgen-wrap').hide();
					jQuery('#all-rml-wrap').hide();
					break;
				case 'stream':
					jQuery('#post-pages-wrap').hide();
					jQuery('#set-pages-wrap').hide();
					jQuery('.filter-only-for-post').hide();
					jQuery('.available-filters-in-group').hide();
					jQuery('#aditional-pages-wrap').hide();
					jQuery('#custom-sorting-wrap').hide();
					jQuery('#custom-element-add-elements-wrapper').hide();
					//jQuery('#media-source-order-wrap').hide();
					jQuery('#all-stream-wrap').show();
					jQuery('#media-source-sizes').hide();
					jQuery('input[name="stream-source-type"]:checked').change();
					jQuery('#all-nextgen-wrap').hide();
					jQuery('#all-rml-wrap').hide();
					jQuery('body').addClass('hide-custom-options');
					break;
				case 'nextgen':
					jQuery('#post-pages-wrap').hide();
					jQuery('#set-pages-wrap').hide();
					jQuery('.filter-only-for-post').hide();
					jQuery('.available-filters-in-group').hide();
					jQuery('#aditional-pages-wrap').hide();
					jQuery('#custom-sorting-wrap').hide();
					jQuery('#custom-element-add-elements-wrapper').hide();
					jQuery('#media-source-order-wrap').show();
					jQuery('#all-stream-wrap').hide();
					jQuery('#media-source-sizes').hide();
					jQuery('#select-grids-wrap').hide();
					jQuery('#all-nextgen-wrap').show();
					jQuery('input[name="nextgen-source-type"]:checked').change();
					jQuery('#media-source-filter').show();
					jQuery('#media-source-default-templates').show();
					jQuery('body').addClass('hide-custom-options');
					break;
				case 'rml':
					jQuery('#post-pages-wrap').hide();
					jQuery('#set-pages-wrap').hide();
					jQuery('.filter-only-for-post').hide();
					jQuery('.available-filters-in-group').hide();
					jQuery('#aditional-pages-wrap').hide();
					jQuery('#custom-sorting-wrap').hide();
					jQuery('#custom-element-add-elements-wrapper').hide();
					jQuery('#media-source-order-wrap').show();
					jQuery('#all-stream-wrap').hide();
					jQuery('#all-nextgen-wrap').hide();
					jQuery('#media-source-sizes').hide();
					jQuery('#select-grids-wrap').hide();
					jQuery('#all-rml-wrap').show();
					jQuery('#media-source-filter').show();
					jQuery('#media-source-default-templates').show();
					jQuery('body').addClass('hide-custom-options');
					break;
			}
			
			// 2.2.6
			if(set !== 'custom') jQuery('.adamlabsgallery-blankitem-hideable').hide();
			else jQuery('.adamlabsgallery-blankitem-hideable').show();

			if(do_change == true) //do not preview on load
				t.changePreviewGrid(true);
			else
				do_change = true;

		});
		jQuery('input[name="source-type"]:checked').change();
		
		/**
		 * Show/Hide Stream Sources
		 * @since 1.1.0
		 */
		jQuery('input[name="stream-source-type"]').change(function(){

			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;
			
			var set = jQuery(this).val();

			jQuery( "[id$='-external-stream-wrap']" ).hide();
			jQuery( "#"+set+"-external-stream-wrap" ).show();

			jQuery("#adamlabsgallery-source-youtube-message,#adamlabsgallery-source-vimeo-message").hide();

			switch(set){
				case 'vimeo':
					jQuery('input[name="vimeo-type-source"]:checked').change();
					jQuery("#adamlabsgallery-source-vimeo-message").show();
				break;
				case 'youtube':
					jQuery('input[name="youtube-type-source"]:checked').change();
					jQuery("#adamlabsgallery-source-youtube-message").show();
				break;
				case 'facebook':
					jQuery('input[name="facebook-type-source"]:checked').change();
				break;
				case 'flickr':
					jQuery('input[name="flickr-type"]:checked').change();
				break;
				case 'behance':
					jQuery('input[name="behance-type"]:checked').click();
				break;
				
			}
			
			/* 2.1.5 */
			var media = ['imso', 'lbo', 'ajo']; // "value!=" can't be chained
			for(var i = 0; i < 3; i++) {	
				/* hidden source types need to be unchecked for vimeo/youtube streams */
				if(set.search(/youtube|vimeo/) !== -1) {
					jQuery('#' + media[i] + '-list input[value!="' + set + '"]').prop('checked', false); 
					jQuery('#' + media[i] + '-list input[value="' + set + '"]').prop('checked', true); 
				}
				/* turn featured-image on by default for other streams */
				else {
					jQuery('#' + media[i] + '-list input[value="featured-image"]').prop('checked', true); 
				}
			}
/*
			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}
*/
		});
		jQuery('input[name="stream-source-type"]:checked').change();

		// Vimeo Source
		jQuery('input[name="vimeo-type-source"]').change(function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='vimeo') return false;
			var set = jQuery(this).val();
			
			jQuery( ".adamlabsgallery-external-source-vimeo" ).hide();
			jQuery( "#adamlabsgallery-external-source-vimeo-"+set+"-wrap" ).show();

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}

		});
		jQuery('input[name="vimeo-type-source"]:checked').change();

		// YouTube Source
		jQuery('input[name="youtube-type-source"]').change(function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='youtube') return false;
			var set = jQuery(this).val();
			
			if(set=="playlist"){
				jQuery("#adamlabsgallery-external-source-youtube-playlist-wrap").show();
				var data = { api: jQuery('#youtube-api').val() , id: jQuery('#youtube-channel-id').val(), playlist: jQuery('#youtube-playlist').val() };
				AdminEssentials.ajaxRequest("get_youtube_playlists", data, '#youtube-playlist-select',function(response){
					jQuery('#youtube-playlist-select').html(response.data.html).show();
					jQuery('#youtube-playlist-select').val(jQuery('input[name=youtube-playlist]').val());
				});
			}
			else {
				jQuery("#adamlabsgallery-external-source-youtube-playlist-wrap").hide();
			}

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}

		});
		jQuery('input[name="youtube-type-source"]:checked').change();

		jQuery('#youtube-playlist-select').change(function(){
			jQuery('input[name=youtube-playlist]').val(jQuery('#youtube-playlist-select').val());	
		});

		// YouTube Channel ID
		jQuery('input[name="youtube-channel-id"]').change(function(){
			if( jQuery('input[name="youtube-type-source"]:checked').val() == "playlist" ){
				jQuery('input[name="youtube-type-source"]:checked').change();
			}
		});

		// Flickr Source
		jQuery('input[name="flickr-type"]').change(function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='flickr') return false;
			var set = jQuery(this).val();

			jQuery("#adamlabsgallery-external-source-flickr-sources div").hide();
			switch(set){
				case 'publicphotos':
					jQuery('#adamlabsgallery-external-source-flickr-publicphotos-url-wrap').show();
				break;
				case 'photosets':
					var data = { key: jQuery('input[name=flickr-api-key').val() , count: jQuery('input[name=flickr-count').val() , url: jQuery('input[name=flickr-user-url').val() , set: jQuery('input[name=flickr-photoset]').val() };
					AdminEssentials.ajaxRequest("get_flickr_photosets", data, 'select[name=flickr-photoset-select]',function(response){
						jQuery('select[name=flickr-photoset-select]').html(response.data.html).show();
						jQuery('input[name=flickr-photoset').val(jQuery('select[name=flickr-photoset-select]').val());
					});
					jQuery('#adamlabsgallery-external-source-flickr-photosets-wrap').show();
				break;
				case 'gallery':
					jQuery('#adamlabsgallery-external-source-flickr-gallery-url-wrap').show();
				break;
				case 'group':
					jQuery('#adamlabsgallery-external-source-flickr-group-url-wrap').show();
				break;
				default:
				break;
			}

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}

		});
		jQuery('input[name="flickr-type"]:checked').change();

		jQuery('select[name=flickr-photoset-select]').change(function(){
			jQuery('input[name=flickr-photoset]').val(jQuery('select[name=flickr-photoset-select]').val());	
		});

		// Facebook Source
		jQuery('input[name="facebook-type-source"]').change(function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='facebook') return false;
			var set = jQuery(this).val();

			if(set=="album"){
				var data = { url: jQuery("input[name=facebook-page-url]").val(), album: jQuery("input[name=facebook-album]").val(), api_key: jQuery("input[name=facebook-app-id]").val(), api_secret: jQuery("input[name=facebook-app-secret]").val()};
				AdminEssentials.ajaxRequest("get_facebook_photosets", data, 'select[name=facebook-album-select]',function(response){
					jQuery('select[name=facebook-album-select]').html(response.data.html).show();
					jQuery('input[name=facebook-album').val(jQuery('select[name=facebook-album-select]').val());
				});
				jQuery("#adamlabsgallery-external-source-facebook-album-wrap").show();
			}
			else{
				jQuery("#adamlabsgallery-external-source-facebook-album-wrap").hide();
			}

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}

		});
		jQuery('input[name="facebook-type-source"]:checked').change();

		jQuery('select[name=facebook-album-select]').change(function(){
			jQuery('input[name=facebook-album]').val(jQuery('select[name=facebook-album-select]').val());	
		});

		jQuery('input[name="facebook-page-url"]').change(function(){
			jQuery('input[name="facebook-type-source"]:checked').change();
		});		

		// Instagram Source User
		jQuery('input.instagram-type-source').click(function(){
			t.checkInstagramSourceType();
		});
		t.checkInstagramSourceType();
		
		// Behance Source
		jQuery('input[name="behance-type"]').click(function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='behance') return false;
			var set = jQuery(this).val();

			if(set=="project"){
				var data = { userid: jQuery("#behance-user-id").val(), project: jQuery("input[name=behance-project]").val(), api: jQuery("input[name=behance-api]").val()};
				AdminEssentials.ajaxRequest("get_behance_projects", data, 'select[name=behance-project]',function(response){
					jQuery('select[name=behance-project-select]').html(response.data.html).show();
					jQuery('input[name=behance-project').val(jQuery('select[name=behance-project-select]').val());
				});
				jQuery("#adamlabsgallery-external-source-behance-projects-images-wrap").hide();
				jQuery("#adamlabsgallery-external-source-behance-project-wrap,#adamlabsgallery-external-source-behance-project-images-wrap").show();
			}
			else{
				jQuery("#adamlabsgallery-external-source-behance-project-wrap,#adamlabsgallery-external-source-behance-project-images-wrap").hide();
				jQuery("#adamlabsgallery-external-source-behance-projects-images-wrap").show();
				
			}

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}

		});

		jQuery('input[name="behance-type"]:checked').click();

		jQuery('select[name=behance-project-select]').change(function(){
			jQuery('input[name=behance-project]').val(jQuery('select[name=behance-project-select]').val());	
		});

		jQuery('input[name="nextgen-source-type"]').change(function(){
			jQuery('body').addClass('hide-custom-options');
			if(jQuery('input[name="source-type"]:checked').val()!='nextgen') return false;
			
			var set = jQuery(this).val();

			jQuery(".nextgen-source").hide();
			jQuery("#adamlabsgallery-nextgen-"+set+"-wrap").show();

		});

		//Nextgen
		jQuery('input[name="nextgen-source-type"]:checked').change();

		jQuery('.adamlabsgallery-clear-cache').click(function(){
			t.clearStreamCache(jQuery(this));
		});

		/**
		 * Show/Hide filter options depending on setting
		 * @since 1.1.0
		 */
		jQuery('input[name="filter-listing"]').change(function(){
			var set = jQuery(this).val();
			if(set == 'list'){
				jQuery('.filter-only-if-dropdown').hide();
			}else{
				jQuery('.filter-only-if-dropdown').show();
			}
		});
		jQuery('input[name="filter-listing"]:checked').change();

		jQuery('input[name="poster-source-order[]"]').change(function(){
			$this = jQuery(this);
			var values = new Array();
			jQuery.each(jQuery("input[name='poster-source-order[]']:checked"), function() {
			  values.push(jQuery(this).val());
			});
			
			values.indexOf("default-youtube-image")>-1 ? jQuery("#adamlabsgallery-youtube-default-poster").show() : jQuery("#adamlabsgallery-youtube-default-poster").hide();
			values.indexOf("default-vimeo-image")>-1 ? jQuery("#adamlabsgallery-vimeo-default-poster").show() : jQuery("#adamlabsgallery-vimeo-default-poster").hide();
			values.indexOf("default-html-image")>-1 ? jQuery("#adamlabsgallery-html5-default-poster").show() : jQuery("#adamlabsgallery-html5-default-poster").hide();
		});
		jQuery('input[name="poster-source-order[]"]').change();

		/**
		 * Reset the custom Elements
		 */
		t.reset_custom_fields = function(){
			for(var key in init_custom){
				switch(init_custom[key]['type']){
					case 'input':
						var set_val = (typeof(init_custom[key]['default']) !== 'undefined') ? init_custom[key]['default'] : '';
						jQuery('input[name="'+init_custom[key]['name']+'"]').val(set_val);
						break;
					case 'select':
					case 'multi-select':
						var set_val = (typeof(init_custom[key]['default']) !== 'undefined') ? init_custom[key]['default'] : jQuery('select[name="'+init_custom[key]['name']+'"] option:first-child').attr('selected', 'selected');
						jQuery('select[name="'+init_custom[key]['name']+'"] option[value="'+set_val+'"]').attr('selected', 'selected');
						break;
					case 'textarea':
						var set_val = (typeof(init_custom[key]['default']) !== 'undefined') ? init_custom[key]['default'] : '';
						jQuery('textarea[name="'+init_custom[key]['name']+'"]').val(set_val);
						break;
					case 'image':
						jQuery('input[name="'+init_custom[key]['name']+'"]').val('');
						jQuery('#'+init_custom[key]['name']+'-img').attr('src', '');
						jQuery('#'+init_custom[key]['name']+'-img').hide();
						break;
				}
			}

		}

		/**
		 * remove highlight class from elements
		 */
		t.remove_hightlighting = function(){
			jQuery('div').removeClass('adamlabsgallery-elset-row-highlight');
		}


		jQuery('body').on('click', '.adamlabsgallery-btn-duplicate-custom-element', function(){
			var data = jQuery(this).closest('li').find('input[name="layers[]"]').val(); //get set data

			jQuery('#adamlabsgallery-preview-wrapping-wrapper').prepend('<input id="adamlabsgallery-new-temp-layer" class="adamlabsgallery-new-temp-layer" name="layers[]" type="hidden" values="" />');
			jQuery('#adamlabsgallery-new-temp-layer').val(data);

			t.changePreviewGrid(true);
		});


		jQuery('body').on('click', '.adamlabsgallery-btn-edit-custom-element', function(){
			var li = jQuery(this).closest('li');
			var data = li.find('input[name="layers[]"]').val(); //get set data
			data = jQuery.parseJSON(data);
			
			if(typeof(data['custom-type']) === 'undefined') data['custom-type'] = 'image';
			var cur_type = !li.hasClass('adamlabsgalleryblankskin-wrapper') ? data['custom-type'] : 'blank';
			
			jQuery('body').removeClass('adamlabsgallery-custom-dialog-blank');
			t.open_custom_element_dialog(cur_type, data, jQuery(this).closest('li').find('input[name="layers[]"]'));
			
		});


		jQuery('body').on('click', '.adamlabsgallery-open-edit-dialog', function(){
			var cur_type = jQuery(this).attr('id').replace('adamlabsgallery-add-new-custom-', '').replace('-top', '');
			jQuery('body').removeClass('adamlabsgallery-custom-dialog-blank');
			
			if(cur_type == 'image'){
				jQuery('#custom-element-image-dialog-wrap').dialog({
					modal:true,
					draggable:true,
					resizable:false,
					width:300,
					height:200,
					closeOnEscape:true,
					dialogClass:'wp-dialog',
					buttons: [
					{ text: adamlabsgallery_lang.single, click: function() {
						t.open_custom_element_dialog(cur_type, false);
						jQuery(this).dialog('close');
					} },
					{ text: adamlabsgallery_lang.bulk, click: function() {
						t.add_custom_grid_multiple_images();
						jQuery(this).dialog('close');
					} }]
				});
			}else{
				t.open_custom_element_dialog(cur_type, false);
			}
		});



		t.open_custom_element_dialog = function(cur_type, cur_data, input_obj){

			t.reset_custom_dialog();
			t.remove_hightlighting();

			jQuery('.adamlabsgallery-item-skin-elements').hide(); //hide all specific elements first
			jQuery('.adamlabsgallery-item-skin-media-title').show(); //hide all specific elements first

			var editor_text = adamlabsgallery_lang.add_element;
			var editor_save_text = adamlabsgallery_lang.add_element;

			jQuery('input[name="custom-type"]').val(cur_type); //write the type into the box

			switch(cur_type){
				case 'youtube':
					jQuery('#adamlabsgallery-item-skin-elements-media-youtube').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-image').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-ratio').show();
					break;
				case 'vimeo':
					jQuery('#adamlabsgallery-item-skin-elements-media-vimeo').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-image').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-ratio').show();
					break;
				case 'soundcloud':
					jQuery('#adamlabsgallery-item-skin-elements-media-sound').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-image').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-ratio').show();
					break;
				case 'html5':
					jQuery('#adamlabsgallery-item-skin-elements-media-html5').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-image').show();
					jQuery('#adamlabsgallery-item-skin-elements-media-ratio').show();
					break;
				case 'image':
					jQuery('#adamlabsgallery-item-skin-elements-media-image').show();
					break;
				case 'text':
					jQuery('.adamlabsgallery-item-skin-media-title').hide();
					break;
				case 'blank':
					jQuery('body').addClass('adamlabsgallery-custom-dialog-blank');
					break;
			}

			//set values from current settings
			var cur_ele = jQuery('#adamlabsgallery-template-wrapper .adamlabsgallery-data-handler').data('exists');

			for(var key in cur_ele){
				jQuery('#edit-custom-element-form input[name="'+key+'"]').val(cur_ele[key]);
				jQuery('#edit-custom-element-form input[name="'+key+'"]').closest('div').addClass('adamlabsgallery-elset-row-highlight');
			}

			//set data into fields if we have some presets
			if(cur_data !== false){ //edit mode
				var editor_text = adamlabsgallery_lang.edit_element;
				var editor_save_text = adamlabsgallery_lang.save_changes;
				t.set_custom_dialog_fields(cur_data);
			}
			
			var hhh;
			if(cur_type !== 'blank') {
				hhh = 600;
				jQuery('select[name="use-skin"]').val('-1');
			}
			else {
				hhh = 300;
				var ss = jQuery('select[name="use-skin"]'),
					val;
					
				ss.children('option').each(function() {
					
					var $this = jQuery(this);
					if(jQuery.trim($this.text().replace(' ', '').toLowerCase()) === 'adamlabsgalleryblankskin') {
						
						val = $this.val();
						return false;
						
					}
					
				});

				ss.val(val);
			}

			jQuery('#edit-custom-element-dialog-wrap').dialog({
				modal:true,
				draggable:true,
				resizable:false,
				title: editor_text,
				width:850,
				height:hhh,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons: [
				{ text: adamlabsgallery_lang.close, click: function() {
					jQuery(this).dialog('close');
					t.showWaitAMinute({fadeOut:300});
				} },
				{ text: editor_save_text, click: function() {
					
					var data = t.getFormParams('edit-custom-element-form');
					console.log(data);
					if(data.hasOwnProperty('adamlabsgallery-custom-meta-skin')) {
						
						data.adamlabsgallery_settings_custom_meta_skin = data['adamlabsgallery-custom-meta-skin'];
						delete data['adamlabsgallery-custom-meta-skin'];
						
						if(data.hasOwnProperty('adamlabsgallery-custom-meta-element')) {
						
							data.adamlabsgallery_settings_custom_meta_element = data['adamlabsgallery-custom-meta-element'];
							delete data['adamlabsgallery-custom-meta-element'];
							
						}
						if(data.hasOwnProperty('adamlabsgallery-custom-meta-setting')) {
							
							data.adamlabsgallery_settings_custom_meta_setting = data['adamlabsgallery-custom-meta-setting'];
							delete data['adamlabsgallery-custom-meta-setting'];
							
						}
						if(data.hasOwnProperty('adamlabsgallery-custom-meta-style')) {
							
							data.adamlabsgallery_settings_custom_meta_style = data['adamlabsgallery-custom-meta-style'];
							delete data['adamlabsgallery-custom-meta-style'];
							
						}
						
						if(!data.post_id) data.post_id = 'adamlabsgallery-item-' + parseInt(Math.random() * 10000, 10);
						
					}
					
					//write input field in the front of elements, then refresh the preview
					var new_data = JSON.stringify(data); //get set data

					if(cur_data === false){ //we create a new entry

						jQuery('#adamlabsgallery-preview-wrapping-wrapper').prepend('<input id="adamlabsgallery-new-temp-layer" class="adamlabsgallery-new-temp-layer" name="layers[]" type="hidden" values="" />');
						jQuery('#adamlabsgallery-new-temp-layer').val(new_data);

					}else{ //we update an existing entry
						//set new_data into the right input field
						input_obj.val(new_data);
					}

					jQuery(this).dialog('close');
					t.changePreviewGrid(true);

				} }

				],
				create: function() {
		            jQuery(this).closest('div.ui-dialog')
		                   .find('.ui-dialog-titlebar-close')
		                   .click(function(e) {
		                      t.showWaitAMinute({fadeOut:300});
		                       e.preventDefault();
		                   });
		        }
			});
			
			jQuery('.adamlabsgallery-advanced-param').empty();
			var metas = [],
				len;
			
			if(cur_data.adamlabsgallery_settings_custom_meta_skin) {
				
				len = cur_data.adamlabsgallery_settings_custom_meta_skin.length;
				for(var i = 0; i < len; i++) {
						
					metas[i] = {};
					metas[i].skin = cur_data.adamlabsgallery_settings_custom_meta_skin[i];
					metas[i].element = cur_data.adamlabsgallery_settings_custom_meta_element[i];
					metas[i].setting = cur_data.adamlabsgallery_settings_custom_meta_setting[i];
					metas[i].style = cur_data.adamlabsgallery_settings_custom_meta_style[i];
				
				}
			
			}
			
			init_elements = metas;
			t.initMetaBox('custom');
			
		}


		jQuery('body').on('click', '.adamlabsgallery-btn-move-before-custom-element, .adamlabsgallery-btn-move-after-custom-element, .adamlabsgallery-btn-switch-custom-element', function(){

			var new_position,
				jt = jQuery(this),
				jtli = jt.closest('li');

			if(jt.hasClass('adamlabsgallery-btn-move-before-custom-element')){
				new_position = jtli.index();
				jtli.insertBefore(jtli.parent().find('>li:nth-child('+new_position+')'));
			}

			else

			if(jt.hasClass('adamlabsgallery-btn-move-after-custom-element')){
				new_position = jtli.index()+2;
				if (new_position >= jtli.parent().find('>li').length) new_position=jtli.parent().find('>li').length-1;
				jtli.insertAfter(jtli.parent().find('>li:nth-child('+new_position+')'));
			}

			else

			if(jt.hasClass('adamlabsgallery-btn-switch-custom-element')){
				new_position = parseInt(prompt(adamlabsgallery_lang.enter_position,1));
				if (new_position >=0 && new_position<99999) {
					if (new_position >= jtli.parent().find('>li').length) new_position=jtli.parent().find('>li').length-1;
					jtli.insertAfter(jtli.parent().find('>li:nth-child('+new_position+')'));
				}
			}
			t.resetCustomItemValues();
			t.changePreviewGrid();

		});

		t.resetCustomItemValues = function() {
			if(jQuery('input[name="source-type"]:checked').val() == 'custom'){
				jQuery('#adamlabsgallery-preview-skinlevel').find('ul >li').each(function() {
					var li = jQuery(this);
					li.find('.adamlabsgallery-order-nr').remove();
					if (!li.hasClass("adamlabsgallery-addnewitem-wrapper"))
						li.append('<div class="adamlabsgallery-order-nr">'+(li.index()+1)+'</div>');
				});
			}
		};



		t.reset_custom_dialog = function(){

			t.reset_custom_fields();

			jQuery('#adamlabsgallery-custom-image-img').attr('src', '');
			jQuery('#adamlabsgallery-custom-image-img').hide();

			document.getElementById('edit-custom-element-form').reset();

		};

		t.set_custom_dialog_fields = function(cur_data){
			
			var set_form = document.getElementById('edit-custom-element-form');
			for(var key in cur_data){
				var els = document.getElementsByName(key);
				
				if(typeof els[0] !== 'undefined'){
					switch(els[0].tagName){
						case 'INPUT':
						
							jQuery('input[name="'+key+'"]').val(cur_data[key]);

							//check if we are an image
							var is_img = jQuery('#adamlabsgallery-'+key+'-img');
							if(!is_img.length) is_img = jQuery('#'+key+'-cm-img');
							
							if(is_img.length === 0) //custom meta images
								var is_img = jQuery('#'+key+'-img');
							
							//show the img tag and set the right source
							if(is_img.length > 0){
								
								if(parseInt(cur_data[key]) > 0){
									var data = {img_id: cur_data[key]};
									
									t.ajaxRequest('get_image_by_id', data, '', function(response, img_obj){
										if(typeof(response.success != 'undefined') && response.success == true){
											img_obj.attr('src', response.url);
											img_obj.show();
										}
									}, is_img);
								}
							}
						break;
						case 'SELECT':
							jQuery('select[name="'+key+'"] option[value="'+cur_data[key]+'"]').attr('selected', 'selected');
						break;
						
					}
				}
			}
		}


		/**
		 * Remove Custom Element from list
		 */
		jQuery('body').on('click', '.adamlabsgallery-btn-delete-custom-element', function(){
			if(confirm(adamlabsgallery_lang.remove_this_element)){
				jQuery(this).closest('li').remove();

				jQuery('#adamlabsgallery-preview-grid').adamlabsgalleryappend(); // Add the new Element to Grid Logic

				t.resetCustomItemValues();
				t.changePreviewGrid();
			}
		});


		/**
		SET RATIO VISIBILTY BASED ON LAOYUT
		**/
		t.checkEvenMasonryInput();

		/**
		CHECK BOX FOR SKIN SELECTION
		**/
		t.skinSelectorFakes();

		/**
		Set The Layout Dependencies of Lightbox
		**/
		t.lightboxLayoutDependencies();
		t.lightboxLayoutEvents();

		/**
		CHANGE ON ITEM ADD HOVER THE TITLES IN ELEMENTS
		**/
		t.changeAddElementTitles();


		/**
		NAVIGATION SETTINGS
		**/
		jQuery('input[name="nagivation-type"]').change(function(){
			jQuery('#es-ng-layout-wrapper').hide();
			jQuery('#es-ng-external-wrapper').hide();
			jQuery('#es-ng-widget-wrapper').hide();

			switch(jQuery(this).val()){
				case 'internal':
					jQuery('#es-ng-layout-wrapper').show();
				break;
				case 'external':
					jQuery('#es-ng-external-wrapper').show();
				break;
				case 'widget':
					jQuery('#es-ng-widget-wrapper').show();
				break;
			}
		});
		jQuery('input[name="nagivation-type"]:checked').change();


		adamlabsgallery_codemirror_api_js = CodeMirror.fromTextArea(document.getElementById("adamlabsgallery-api-custom-javascript"), {
			lineNumbers: true,
			mode: "text/javascript"
		});

		adamlabsgallery_codemirror_api_js.setSize(500, 250);

		adamlabsgallery_codemirror_ajax_css = CodeMirror.fromTextArea(document.getElementById("adamlabsgallery-ajax-custom-css"), {
			lineNumbers: true,
			mode: "text/css"
		});

		adamlabsgallery_codemirror_ajax_css.setSize(500, 250);


		jQuery('.adamlabsgallery-api-inputs').click(function(){
			jQuery(this).select().focus();
		});

		jQuery('.adamlabsgallery-default-image-add,.adamlabsgallery-youtube-default-image-add,.adamlabsgallery-vimeo-default-image-add,.adamlabsgallery-html-default-image-add').click(function(e) {
			e.preventDefault();
			AdminEssentials.upload_image_img(jQuery(this).data('setto'));

			return false;
		});

		jQuery('.adamlabsgallery-default-image-clear,.adamlabsgallery-youtube-default-image-clear,.adamlabsgallery-vimeo-default-image-clear,.adamlabsgallery-html-default-image-clear').click(function(e) {
			e.preventDefault();
			var setto = jQuery(this).data('setto');
			jQuery('#'+setto).val('');
			jQuery('#'+setto+'-img').attr("src","");
			jQuery('#'+setto+'-img').hide();
			return false;
		});

		
		jQuery('.adamlabsgallery-clear-taxonomies').click(function(){
			jQuery('select[name="post_category"]').val([]).change();
		});
		
		
		/*******************
		 * More Filter Functions
		 *******************/
		jQuery('body').on('click', '.adamlabsgallery-filter-add-custom-filter', function(){
			//open list with all filters plus the custom filters that were added
			jQuery('#adamlabsgallery-filter-select-box').html('');
			var sel_filters = jQuery('select[name="post_category"]').val();
			
			var sBox = jQuery('#adamlabsgallery-filter-select-box')
			for(var key in adamlabsgallery_filter_handles){
				var opt = new Option(adamlabsgallery_filter_handles[key], key);
				
				if(key.indexOf("option_disabled") == 0 || jQuery.inArray(key, sel_filters) !== -1){
					jQuery(opt).prop("disabled","disabled");
				}
				
				
				if(adamlabsgallery_filter_handles_selected[key] !== undefined){
					jQuery(opt).attr("selected","selected");
				}
				
				sBox.append(opt);
			}
			
			// 2.2.5
			sBox.append(jQuery('<option disabled>---- Custom Metas ----</option>'));
			for(key in adamlabsgallery_meta_handles) {
				
				sBox.append(jQuery('<option value="' + key + '">' + adamlabsgallery_meta_handles[key] + '</option>'));
				
			}
			
			jQuery('#filter-select-dialog-wrap').dialog({
				modal:true,
				resizable:false,
				draggable: true,
				width:600,
				height:350,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons:[
					{ text: adamlabsgallery_lang.save_changes, click: function(){
							adamlabsgallery_filter_handles_selected = {};
							jQuery('#adamlabsgallery-filter-select-box option:selected').each(function(){
								adamlabsgallery_filter_handles_selected[jQuery(this).val()] = jQuery(this).text();
							});
							
							//update the list
							jQuery('select[name="post_category"]').change();
							
							jQuery(this).dialog("close");
						}
					},
					{ text: adamlabsgallery_lang.close, click: function(){
							jQuery(this).dialog("close");
						}
					}
				],
				create:function () {
					jQuery(this).closest(".ui-dialog")
						.find(".ui-dialog-buttonpane") // the first button
						.addClass("save-wrap");
				},
			});
		});
		
		jQuery('.adamlabsgallery-add-filter-box').click(function(){
			adamlabsgallery_filter_counter++;

			var filter_html = jQuery('.adamlabsgallery-original-filter-options-wrap').clone();
			filter_html.removeClass('adamlabsgallery-original-filter-options-wrap');

			filter_html.find('[data-origname]').each(function(){
				jQuery(this).attr('name', jQuery(this).data('origname').replace('#NR', adamlabsgallery_filter_counter));
			});


			filter_html.find('.filter-shortcode-filter').data('num', adamlabsgallery_filter_counter);
			filter_html.find('.filter-shortcode-filter').attr('data-num', adamlabsgallery_filter_counter);
			filter_html.find('.filter-header-id').text(adamlabsgallery_filter_counter);
			filter_html.find('.adamlabsgallery-remove-filter-tab').show();
			filter_html.find('.adamlabsgallery-filter-selected-order-wrap').removeClass('adamlabsgallery-filter-selected-order-wrap').addClass('adamlabsgallery-filter-selected-order-wrap-'+adamlabsgallery_filter_counter);

			filter_html.find('.adamlabsgallery-filter-selected').each(function(){
				jQuery(this).removeClass('adamlabsgallery-filter-selected').addClass('adamlabsgallery-filter-selected-'+adamlabsgallery_filter_counter);
			});

			filter_html.appendTo('.adamlabsgallery-original-filter-options-holder');

			jQuery('.adamlabsgallery-media-source-order-wrap .adamlabsgallery-media-source-order').adamlabsortable({});
			
			/* 2.1.5 */
			var wrap = jQuery('.adamlabsgallery-navigation-cons-wrapper'),
				lastEl = wrap.children('.adamlabsgallery-stay-last-element'),
				newFilter = '<div data-navtype="filter-'+adamlabsgallery_filter_counter+'" class="adamlabsgallery-navigation-cons-filter-'+adamlabsgallery_filter_counter+' adamlabsgallery-nav-cons-filter adamlabsgallery-navigation-cons"><i class="adamlabsgallery-icon-megaphone"></i>'+adamlabsgallery_lang.filter+' '+adamlabsgallery_filter_counter+'</div>';
			
			if(lastEl.length) jQuery(newFilter).insertBefore(lastEl);
			else wrap.append(newFilter);
			
			t.updateShortcode();

		});

		jQuery('body').on('click', '.adamlabsgallery-remove-filter-tab', function(){
			if(confirm(adamlabsgallery_lang.deleting_this_cant_be_undone)){
				var curnum = jQuery(this).siblings('input').attr('name').replace('filter-all-text-', '');
				jQuery('.adamlabsgallery-navigation-cons-filter-'+curnum).remove();
				jQuery(this).closest('.adamlabsgallery-filter-options-wrap').remove();
			}
		});

		jQuery('input[name="filter-arrows"]').change(function(){
			if(jQuery(this).val() == 'multi'){
				jQuery('.adamlabsgallery-filter-logic').show();
				jQuery('#convert_mobile_filters').hide();
			}else{
				jQuery('.adamlabsgallery-filter-logic').hide();
				jQuery('#convert_mobile_filters').show();
			}
		});
		jQuery('input[name="filter-arrows"]:checked').change();


		jQuery('input[name="ajax-close-button"]').change(function(){
			if(jQuery(this).val() == 'on' || jQuery('input[name="ajax-nav-button"]:checked').val() == 'on'){
				if(jQuery(this).val() == 'on'){
					jQuery('.adamlabsgallery-close-button-settings-wrap').show();
				}
				jQuery('.adamlabsgallery-close-nav-button-settings-wrap').show();

			}else{
				jQuery('.adamlabsgallery-close-button-settings-wrap').hide();
				jQuery('.adamlabsgallery-close-nav-button-settings-wrap').hide();
			}
		});
		jQuery('input[name="ajax-close-button"]:checked').change();

		jQuery('input[name="ajax-nav-button"]').change(function(){
			if(jQuery(this).val() == 'on' || jQuery('input[name="ajax-close-button"]:checked').val() == 'on'){
				jQuery('.adamlabsgallery-close-nav-button-settings-wrap').show();
			}else{
				jQuery('.adamlabsgallery-close-nav-button-settings-wrap').hide();
			}
		});
		jQuery('input[name="ajax-nav-button"]:checked').change();


		jQuery('select[name="lightbox-mode"]').change(function(){
			if(jQuery(this).val() == 'content' || jQuery(this).val() == 'content-gallery' || jQuery(this).val() == 'woocommerce-gallery'){
				jQuery('.lightbox-mode-addition-wrapper').show();
			}else{
				jQuery('.lightbox-mode-addition-wrapper').hide();
			}
		});
		jQuery('select[name="lightbox-mode"] option:selected').change();


		jQuery('.adamlabsgallery-add-new-cobbles-pattern').click(function(){
			var cob_sort_count = jQuery('.cob-sort-order').length + 1;
			var cobbles_container = '<div class="adamlabsgallery-cobbles-drop-wrap"><span class="cob-sort-order">'+ cob_sort_count +'.</span><select name="cobbles-pattern[]"><option value="1x1">1:1</option><option value="1x2">1:2</option><option value="1x3">1:3</option><option value="2x1">2:1</option><option value="2x2">2:2</option><option value="2x3">2:3</option><option value="3x1">3:1</option><option value="3x2">3:2</option><option value="3x3">3:3</option></select><a class="button-primary revred adamlabsgallery-delete-cobbles" href="javascript:void(0);"><i class="adamlabsgallery-icon-trash"></i></a></div>';
			jQuery('.adamlabsgallery-cobbles-pattern-wrap').append(cobbles_container);
		});

		jQuery('input[name="use-cobbles-pattern"]').change(function(){
			if(jQuery(this).val() == 'on'){
				jQuery('.adamlabsgallery-cobbles-pattern-wrap').show();
				jQuery('.adamlabsgallery-add-new-cobbles-pattern').show();
				jQuery('.adamlabsgallery-refresh-cobbles-pattern').show();
			}else{
				jQuery('.adamlabsgallery-cobbles-pattern-wrap').hide();
				jQuery('.adamlabsgallery-add-new-cobbles-pattern').hide();
				jQuery('.adamlabsgallery-refresh-cobbles-pattern').hide();
			}
		});


		jQuery('body').on('click', '.adamlabsgallery-delete-cobbles', function(){
			jQuery(this).closest('.adamlabsgallery-cobbles-drop-wrap').remove();
		});

		jQuery('.adamlabsgallery-cobbles-pattern-wrap').sortable({
			stop: function(event, ui){
				jQuery('.cob-sort-order').each(function(e){
					e = e + 1;
					jQuery(this).text(e+'.');
				});
			}
		});
		
		/* 2.1.6 */
		document.getElementById('lightbox-post-content-img-position').addEventListener('change', function() {
			
			var display = this.value === 'left' || this.value === 'right' ? 'block' : 'none';
			document.getElementById('lightbox-post-content-img-width').style.display = display;
			
		});
		
		/* 2.1.6.2 */
		jQuery('.pagination-autoplay').on('change', function() {
	
			var display = this.value === 'on' ? 'block' : 'none';
			document.getElementById('pagination-autoplay-speed').style.display = display;
			
		});
		
	}


	t.checkInstagramSourceType = function(){
			if(jQuery('input[name="source-type"]:checked').val()!='stream') return false;		
			if(jQuery('input[name="stream-source-type"]:checked').val()!='instagram') return false;

			jQuery('input.instagram-type-source').each(function(){
				$this = jQuery(this);
				if($this.is(':checked')){
					jQuery(".instagram_"+$this.data("source")).show();
				}
				else{
					jQuery(".instagram_"+$this.data("source")).hide();
				}
			});

			try{
				if(do_change == true) //do not preview on load
					t.changePreviewGrid(true);
				else
					do_change = true;
			}
			catch(e){}
	}

	t.initImportExport = function(){
		jQuery('input[name="export-grids"]').click(function(){
			t.switchCheckInputFields('export-grids', jQuery(this).is(':checked'));
		});
		jQuery('input[name="export-skins"]').click(function(){
			t.switchCheckInputFields('export-skins', jQuery(this).is(':checked'));
		});
		jQuery('input[name="export-elements"]').click(function(){
			t.switchCheckInputFields('export-elements', jQuery(this).is(':checked'));
		});
		jQuery('input[name="export-custom-meta"]').click(function(){
			t.switchCheckInputFields('export-custom-meta', jQuery(this).is(':checked'));
		});
		jQuery('input[name="export-navigation-skins"]').click(function(){
			t.switchCheckInputFields('export-navigation-skins', jQuery(this).is(':checked'));
		});
		jQuery('input[name="export-adamlabs-fonts"]').click(function(){
			t.switchCheckInputFields('export-adamlabs-fonts', jQuery(this).is(':checked'));
		});

		jQuery('input[name="import-grids"]').click(function(){
			t.switchCheckInputFields('import-grids', jQuery(this).is(':checked'));
		});
		jQuery('input[name="import-skins"]').click(function(){
			t.switchCheckInputFields('import-skins', jQuery(this).is(':checked'));
		});
		jQuery('input[name="import-elements"]').click(function(){
			t.switchCheckInputFields('import-elements', jQuery(this).is(':checked'));
		});
		jQuery('input[name="import-custom-meta"]').click(function(){
			t.switchCheckInputFields('import-custom-meta', jQuery(this).is(':checked'));
		});
		jQuery('input[name="import-navigation-skins"]').click(function(){
			t.switchCheckInputFields('import-navigation-skins', jQuery(this).is(':checked'));
		});
		jQuery('input[name="import-adamlabs-fonts"]').click(function(){
			t.switchCheckInputFields('import-adamlabs-fonts', jQuery(this).is(':checked'));
		});




		t.switchCheckInputFields = function(name, check){
			jQuery('input[name="'+name+'-id[]"]').each(function(){
				jQuery(this).attr('checked', check);
			});
			jQuery('input[name="'+name+'-handle[]"]').each(function(){
				jQuery(this).attr('checked', check);
			});
		}

		jQuery('#adamlabsgallery-import-data').click(function(){
			var import_data = t.getFormParams('adamlabsgallery-grid-import-form');

			t.ajaxRequest("import_data", {imports: import_data}, '#adamlabsgallery-import-data',function(response){

			});
		});

		jQuery('#adamlabsgallery-grid-export-import-wrapper .adamlabsgallery-li-intern-wrap').click(function(ev){
			var ec = jQuery(this).find('.adamlabsgallery-expand-collapse');

			if (ec.length>0 && jQuery(ev.target).hasClass("adamlabsgallery-li-intern-wrap") || jQuery(ev.target).hasClass("adamlabsgallery-expand-collapse") || jQuery(ev.target).hasClass("adamlabsgallery-icon-folder-open") || jQuery(ev.target).hasClass("adamlabsgallery-icon-folder")) {
				var li = ec.closest("li");
				if (ec.hasClass("closed")) {
					ec.removeClass("closed")
					li.find('ul').first().slideDown(200);
				} else {
					ec.addClass("closed")
					li.find('ul').first().slideUp(200);
				}
			}
		})

		jQuery('#adamlabsgallery-grid-export-import-wrapper ul li ul').each(function() {
			var ul = jQuery(this),
				lilen = ul.find('>li').length;
			ul.parent().find('.adamlabsgallery-amount-of-lis').html("("+lilen+")");

		})
		// PREPARING CHECKED AND NOT CHECKED STATUS
		jQuery('#adamlabsgallery-grid-export-import-wrapper').find('.adamlabsgallery-inputchecked').each(function() {
			var ch = jQuery(this);
			ch.click(function() {
				var inp = ch.siblings('input');
				inp.click();
				t.checkImportExportInputs();
				return false;
			});
		})

		t.checkImportExportInputs();

		/**
		 * Import Demo Posts
		 */
		jQuery('#adamlabsgallery-import-demo-posts').click(function(){
			if(confirm(adamlabsgallery_lang.import_demo_post_heavy_loading)){
				t.ajaxRequest("import_default_post_data", '', '#adamlabsgallery-import-demo-posts, #adamlabsgallery-import-demo-posts-210, #adamlabsgallery-read-file-import, #adamlabsgallery-export-selected-settings',function(response){

				});
			}
		});
		
		/**
		 * Import Demo Grids added at 2.1.0
		 */
		jQuery('#adamlabsgallery-import-demo-posts-210').click(function(){
			if(confirm(adamlabsgallery_lang.import_demo_grids_210)){
				t.ajaxRequest("import_default_grid_data_210", '', '#adamlabsgallery-import-demo-posts, #adamlabsgallery-import-demo-posts-210, #adamlabsgallery-read-file-import, #adamlabsgallery-export-selected-settings',function(response){

				});
			}
		});

	}


	t.checkImportExportInputs = function() {
		jQuery('#adamlabsgallery-grid-export-import-wrapper').find('.adamlabsgallery-inputchecked').each(function() {
			var ch = jQuery(this);
			ch.removeClass("adamlabsgallery-partlychecked");

			var inp = ch.siblings('input');

			if (inp.attr('checked')== "checked") {
				ch.addClass("checked")
				if (ch.closest('.adamlabsgallery-ie-sub-ul').length>0) {
					var chli = ch.closest('.adamlabsgallery-ie-sub-ul').closest('li').find('.adamlabsgallery-inputchecked').first();
					var notch = ch.closest('.adamlabsgallery-ie-sub-ul').find("input:checkbox:not(:checked)").length;
					if (!chli.hasClass("checked") && notch>0) chli.addClass("adamlabsgallery-partlychecked");
				}
			} else {
				ch.removeClass("checked");
				if (ch.closest('.adamlabsgallery-ie-sub-ul').length>0) {
					var chli = ch.closest('.adamlabsgallery-ie-sub-ul').closest('li').find('.adamlabsgallery-inputchecked').first();
					if (chli.hasClass("checked")) chli.addClass("adamlabsgallery-partlychecked");
				}

			}


		});
	}

	/**
	CHANGE ITEM TITLES IN ADD ELEMENT
	**/
//	adamlabsgallery-center adamlabsgallery-addnewitem-element-1 adamlabsgallery-rotatescale

	t.changeAddElementTitles = function() {

		jQuery(document.body).on("mouseenter",".adamlabsgallery-addnewitem-element-1",function() {
			var txt = adamlabsgallery_lang.selectyouritem;
			switch (jQuery(this).attr('id')) {
				case "adamlabsgallery-add-new-custom-youtube":
					txt = adamlabsgallery_lang.withyoutube;
				break;
				case "adamlabsgallery-add-new-custom-vimeo":
					txt = adamlabsgallery_lang.withvimeo;
				break;
				case "adamlabsgallery-add-new-custom-html5":
					txt = adamlabsgallery_lang.withthtml5;
				break;
				case "adamlabsgallery-add-new-custom-soundcloud":
					txt = adamlabsgallery_lang.withsoundcloud;
				break;
				case "adamlabsgallery-add-new-custom-image":
					txt = adamlabsgallery_lang.withimage;
				break;
				case "adamlabsgallery-add-new-custom-text":
					txt = adamlabsgallery_lang.withoutmedia;
				break;
				default:
					txt = adamlabsgallery_lang.selectyouritem;
				break;
			}
			jQuery('.adamlabsgallery-bottom.adamlabsgallery-addnewitem-element-2.adamlabsgallery-flipup').html(txt);
		});
		jQuery(document.body).on("mouseleave",".adamlabsgallery-addnewitem-element-1",function() {
			jQuery('.adamlabsgallery-bottom.adamlabsgallery-addnewitem-element-2.adamlabsgallery-flipup').html(adamlabsgallery_lang.selectyouritem);
		});

	}

	/**
	Set The Layout Dependencies of Lightbox
	**/
	t.lightboxLayoutDependencies = function () {
		var tyval = jQuery('select[name="lightbox-type"] option:selected').val();
		if (tyval=="null" || tyval =="over") {
		   jQuery('#adamlabsgallery-lb-title-position').hide();
/*		   jQuery('#adamlabsgallery-lb-twitter').hide();
		   jQuery('#adamlabsgallery-lb-facebook').hide();		   */
		} else {
			jQuery('#adamlabsgallery-lb-title-position').show();
/*			jQuery('#adamlabsgallery-lb-twitter').show();
		   jQuery('#adamlabsgallery-lb-facebook').show();			*/
		}

	}

	t.lightboxLayoutEvents = function() {
		jQuery('select[name="lightbox-type"]').change(function() {
			t.lightboxLayoutDependencies();
		})
	}

	/**
	CHECK BOX FOR SKIN SELECTION
	**/
	t.skinSelectorFakes = function() {
		
		// 2.2.6
		var grid = jQuery('.adamlabsgallery-screenselect-toolbar').closest('.adamlabsgallery-grid').find('.adamlabsgallery-item').css('cursor', 'pointer').click(function() {
			
			var li = jQuery(this);
			jQuery('li.filter-selectedskin').removeClass("filter-selectedskin");
			li.addClass('filter-selectedskin');
			li.find('input[name="entry-skin"]').attr('checked',true).change();

		});
	}

	/**
	 * Returns the navigation layout
	 */
	t.get_navigation_layout = function(){
		var elements = {pagination:{},left:{},right:{},filter:{},filter2:{},filter3:{},cart:{},sorting:{},'search-input':{}}

		var c_pagination = jQuery('.adamlabsgallery-navigation-cons-pagination');
		var c_left = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-left');
		var c_right = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-right');
		var c_filter = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-filter');
		var c_filter2 = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-filter2');
		var c_filter3 = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-filter3');
		var c_cart = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-cart');
		var c_sort = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-sort');
		var c_search_input = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-search-input');

		//var c_navigation = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-navigation');

		if(c_pagination.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			pagination = c_pagination.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_pagination.parent().children('div').index(c_pagination);
			elements.pagination[pagination] = order;
		}

		if(c_left.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			left = c_left.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_left.parent().children('div').index(c_left);
			elements.left[left] = order;
		}

		if(c_right.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			right = c_right.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_right.parent().children('div').index(c_right);
			elements.right[right] = order;
		}

		if(c_filter.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			filter = c_filter.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_filter.parent().children('div').index(c_filter);
			elements.filter[filter] = order;
		}

		if(c_filter2.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			filter2 = c_filter2.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_filter2.parent().children('div').index(c_filter2);
			elements.filter2[filter2] = order;
		}

		if(c_filter3.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			filter3 = c_filter3.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_filter3.parent().children('div').index(c_filter3);
			elements.filter3[filter3] = order;
		}

		if(c_cart.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			cart = c_cart.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_cart.parent().children('div').index(c_cart);
			elements.cart[cart] = order;
		}

		if(c_sort.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			sort = c_sort.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_sort.parent().children('div').index(c_sort);
			elements.sorting[sort] = order;
		}

		if(c_search_input.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id') !== undefined){
			search_input = c_search_input.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
			order = c_search_input.parent().children('div').index(c_search_input);
			elements['search-input'][search_input] = order;
		}

		for(var i = 1;i<= adamlabsgallery_filter_counter; i++){
			var fil = jQuery('.adamlabsgallery-navigation-drop-inner .adamlabsgallery-navigation-cons-filter-'+i);
			if(fil.length > 0){
				sort = fil.closest('.adamlabsgallery-navigation-drop-wrapper').attr('id').replace('adamlabsgallery-navigations-sort-', '');
				order = fil.parent().children('div').index(fil);
				elements['filter-'+i] = {};
				elements['filter-'+i][sort] = order;
			}
		}

		jQuery('.adamlabsgallery-stay-last-element').appendTo('.adamlabsgallery-navigation-default-wrap');

		return elements;
	}


	/**
	 * set the navigation layout
	 */
	t.set_navigation_layout = function(){
		for (var i=0;i<99;i++) {
			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="top-1"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-top-1 .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="top-2"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-top-2 .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="bottom-1"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-bottom-1 .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="bottom-2"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-bottom-2 .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="left"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-left .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="right"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-right .adamlabsgallery-navigation-drop-inner').append(el);

			var el = jQuery('.adamlabsgallery-navigation-cons[data-putin="external"][data-sort="'+i+'"]');
			if (el.length>0) jQuery('#adamlabsgallery-navigations-sort-external .adamlabsgallery-navigation-drop-inner').append(el);

		}

		var i = 0;

		jQuery('#adamlabsgallery-navigations-sort-external .adamlabsgallery-navigation-drop-inner>div').each(function(){
			var cval = '';
			var skin_sel = '';
			if(typeof(adamlabsgallery_nav_special_class[i]) !== 'undefined') cval = adamlabsgallery_nav_special_class[i];
			if(typeof(adamlabsgallery_nav_special_skin[i]) !== 'undefined') skin_sel = adamlabsgallery_nav_special_skin[i];


			var item_skin = jQuery('#navigation-skin-select').clone().wrap('<div></div>');
			item_skin.attr('id', '');
			item_skin.attr('name', 'navigation-special-skin[]');
			item_skin.find('option').attr('selected', false);
			item_skin.find('option[value="'+skin_sel+'"]').attr('selected', 'selected');

			var new_item = '<div class="adamlabsgallery-filter-sc"><input class="filter-shortcode-filter" type="text" readonly="readonly" data-num="'+jQuery(this).data('navtype')+'" /><input type="text" name="navigation-special-class[]" value="'+cval+'" />';
			new_item += item_skin.parent().html();
			new_item += '</div>';

			jQuery(this).append(new_item);
			i++;
		});
		t.updateShortcode();

	}


	t.set_default_nav_skin = function(json_css){
		css = jQuery.parseJSON(json_css)
		adamlabsgallery_codemirror_navigation_css_default_skin = jQuery.extend({}, css);
	}


	t.removeRedHighlighting = function(){
		jQuery('input').removeClass('adamlabsgallery-alert');
	}

	t.addRedHighlighting = function(theSelector){
		jQuery(theSelector).addClass('adamlabsgallery-alert');
	}

	/***********************
	* Overview Grid
	***********************/

	t.initOverviewGrid = function(doAction){

		jQuery('.adamlabsgallery-btn-delete-grid').click(function(){
			var delete_id = jQuery(this).attr('id').replace('adamlabsgallery-delete-', '');

			var data = { id: delete_id }
			if(confirm(adamlabsgallery_lang.delete_grid)){
				t.ajaxRequest("delete_grid", data, '.btn-wrap-overview-'+delete_id);
            }
		});

		jQuery('.adamlabsgallery-btn-duplicate-grid').click(function(){
			var duplicate_id = jQuery(this).attr('id').replace('adamlabsgallery-duplicate-', '');

			var data = { id: duplicate_id }

			t.ajaxRequest("duplicate_grid", data, '.btn-wrap-overview-'+duplicate_id);
		});

		jQuery('.adamlabsgallery-toggle-favorite').click(function(){
			var star_id = jQuery(this).attr('id').replace('adamlabsgallery-star-id-', '');
			
			var data = { id: star_id };
			
			t.ajaxRequest("toggle_grid_favorite", data, '#waitaminute', function(result){
				if(typeof result !== 'undefined' && typeof result['success'] !== 'undefined' && result['success'] === true){
					if(jQuery('#adamlabsgallery-star-id-'+star_id+' i').hasClass('adamlabsgallery-icon-star-empty')){
						jQuery('#adamlabsgallery-star-id-'+star_id+' i').removeClass('adamlabsgallery-icon-star-empty').addClass('adamlabsgallery-icon-star');
					}else{
						jQuery('#adamlabsgallery-star-id-'+star_id+' i').removeClass('adamlabsgallery-icon-star').addClass('adamlabsgallery-icon-star-empty');
					}
				}
			});
		});
		
		// 2.2.6
		var gridList = jQuery('#adamlabsgallery-grid-list').children('tr');
		function gridListSearch() {
		
			var gridSearch,
				keywords;
				
			function buildKeywords() {
				
				var txt = jQuery.trim(jQuery(this).text()).toLowerCase();
				if(txt.search('adamlabsgallery alias') !== -1) txt = txt.replace(/\[adamlabsgallery alias=\"/, '').replace(/\"\]/, '');
				if(txt) keywords += txt + ' ';
				
			}
					
			function onGridSearch() {
				
				var $this = jQuery(this),
					info = $this.children('td');
				
				keywords = '';
				
				info.eq(2).each(buildKeywords);
				info.eq(3).each(buildKeywords);
				info.eq(5).each(buildKeywords);
				info.eq(6).each(buildKeywords);
				
				keywords = keywords.slice(0, -1);
				if(keywords.search(gridSearch) === -1) $this.hide();
				else $this.show();
				
			}
			
			jQuery('#adamlabsgallery-search-grids').on('focus', function() {
				
				gridList = jQuery('#adamlabsgallery-grid-list').children('tr');
				
			}).on('blur', function() {
				
				if(!this.value && gridList && gridList.length) gridList.show();
				
			});
			
			jQuery('#adamlabsgallery-search-grids').on('keyup', function() {
				
				if(!gridList || !gridList.length) return;
				if(!this.value) {
					
					gridList.show();
					return;
					
				}
				
				gridSearch = jQuery.trim(this.value).toLowerCase();
				gridList.each(onGridSearch);
				
			});
			
		}
		
		if(gridList.length) {
			
			gridListSearch();
			jQuery('#adamlabsgallery-search-grids, #ess-pagination-form').addClass('visible');
			
		}
		
	}
	
	
	/***********************
	* Global Settings init
	***********************/

	t.initGlobalSettings = function(doAction){
		jQuery("#adamlabsgallery-delete-cache").click(function(){
			var data = {};

			t.ajaxRequest("delete_full_cache", data, '#adamlabsgallery-delete-cache');
		});
		
		jQuery('#adamlabsgallery-btn-save-global-settings').click(function(){
			var plugin_permissions = jQuery('select[name="plugin_permissions"] option:checked').val();
			var plugin_tooltips = jQuery('select[name="plugin_tooltips"] option:checked').val();
			var output_protection = jQuery('select[name="output_protection"] option:checked').val();
			var wait_for_fonts = jQuery('select[name="wait_for_fonts"] option:checked').val();
			var use_cache = jQuery('select[name="use_cache"] option:checked').val();
			var js_to_footer = jQuery('select[name="js_to_footer"] option:checked').val();
			var overwrite_gallery = jQuery('select[name="overwrite_gallery"] option:checked').val();
			var query_type = jQuery('select[name="query_type"] option:checked').val();
			var enable_log = jQuery('select[name="enable_log"] option:checked').val();
			var enable_media_filter = jQuery('select[name="enable_media_filter"] option:checked').val();
			var use_lightbox = jQuery('select[name="use_lightbox"] option:checked').val();
			var enable_custom_post_type = jQuery('select[name="enable_custom_post_type"] option:checked').val();
			var enable_post_meta = jQuery('select[name="enable_post_meta"] option:checked').val();
			var global_default_img = document.getElementById('global_default_img').value;
			var enable_pe7 = jQuery('select[name="enable_pe7"] option:checked').val();
			var enable_font_awesome = jQuery('select[name="enable_font_awesome"] option:checked').val();
			var enable_fontello = jQuery('select[name="enable_fontello"] option:checked').val();
			var no_filter_match_message = document.getElementById('no_filter_match_message').value;
			var enable_youtube_nocookie = jQuery('select[name="enable_youtube_nocookie"] option:checked').val();

			var data = {
				permission:plugin_permissions,
				tooltips:plugin_tooltips,
				protection:output_protection,
				wait_for_fonts:wait_for_fonts,
				use_cache:use_cache,
				js_to_footer:js_to_footer,
				overwrite_gallery:overwrite_gallery,
				query_type:query_type,
				enable_log:enable_log,
				use_lightbox:use_lightbox,
				enable_custom_post_type:enable_custom_post_type,
				enable_post_meta:enable_post_meta,
				global_default_img: global_default_img,
				enable_fontello : enable_fontello,
				enable_pe7 : enable_pe7,
				enable_font_awesome : enable_font_awesome,
				enable_media_filter : enable_media_filter,
				no_filter_match_message : no_filter_match_message,
				enable_youtube_nocookie : enable_youtube_nocookie
			};

			t.ajaxRequest("update_general_settings", data, '#adamlabsgallery-btn-save-global-settings');
		});
		
		/* 2.1.5 */
		jQuery('.adamlabsgallery-global-add-image').on('click', function(e) {
			
			e.preventDefault();
			AdminEssentials.upload_image_img(jQuery(this).data('setto'));

			return false;
			
		});
		
		/* 2.1.5 */
		jQuery('.adamlabsgallery-global-image-clear').on('click', function(e) {
			
			e.preventDefault();
			var setto = jQuery(this).data('setto');
			jQuery('#'+setto).val('');
			jQuery('#'+setto+'-img').attr("src","").hide();
			return false;
			
		});
		
		/* 2.3 */
		jQuery('select[name="enable_custom_post_type"]').on('change', function() {
			
			var display = this.value === 'true' ? 'inline-block' : 'none';
			jQuery('#adamlabsgallery-import-demo-posts').css('display', display);
			
		}).change();
		
		/**
		 * Import Demo Posts
		 */
		jQuery('#adamlabsgallery-import-demo-posts').click(function(){
			if(confirm(adamlabsgallery_lang.import_demo_post_heavy_loading)){
				t.ajaxRequest("import_default_post_data", '', '#adamlabsgallery-import-demo-posts, #adamlabsgallery-import-demo-posts-210, #adamlabsgallery-read-file-import, #adamlabsgallery-export-selected-settings',function(response){

				});
			}
		});
		
	}
	

	/***********************
	* Dialogs
	***********************/

	t.calc_advanced_rows = function(adv){
		if(adv == 'off'){
			jQuery('.columns-adv-rows').hide();
			jQuery('.columns-adv-first').text('');
		}else{
			jQuery('.columns-adv-rows').show();

			var len = jQuery('.columns-adv-head').length;

			//write text in head of columns
			if(len == 0){
				var t = '1';
				t += ',';
				t += 2 + 1 * len;
				t += ',';
				t += 3 + 2 * len;

				jQuery('.columns-adv-first').html("Rows:<br>"+t);

				var new_html = jQuery('.columns-adv-first').html();
				new_html += '<div style="position: absolute;top: 11px;white-space: nowrap;left: 100%;"><a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-add-column-advanced">+</a></div>';
				jQuery('.columns-adv-first').html(new_html);
			}else{
				var t = '1';
				t += ',';
				t += 2 + 1 * len;
				t += ',';
				t += 3 + 2 * len;

				jQuery('.columns-adv-first').html("Rows:<br>"+t);

				for(var i = 0; i < len; i++){
					var t = i + 2;
					t += ',';
					t += i+3 + 1 * len;
					t += ',';
					t += i+4 + 2 * len;

					jQuery('.columns-adv-'+i+'.columns-adv-head').html("Rows:<br>"+t);

					if(i == len - 1){
						var new_html = jQuery('.columns-adv-'+i+'.columns-adv-head').html();
						if(len == 9)
							new_html += '<div style="position: absolute;top: 11px;white-space: nowrap;left: 100%;"><a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-remove-column-advanced">-</a></div>';
						else
							new_html += '<div style="position: absolute;top: 11px;white-space: nowrap;left: 100%;"><a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-add-column-advanced">+</a> <a class="button-primary revblue" href="javascript:void(0);" id="adamlabsgallery-remove-column-advanced">-</a></div>';

						jQuery('.columns-adv-'+i+'.columns-adv-head').html(new_html);
					}
				}
			}
		}
	}


	t.getPagesDialog = function(){

		jQuery("#button-add-pages").click(function(){
			jQuery("#pages-select-dialog-wrap").dialog({
				modal:true,
				resizable:false,
				draggable:true,
				width:600,
				height:350,
				closeOnEscape:true,
				dialogClass:'wp-dialog',
				buttons:[
					{ text: adamlabsgallery_lang.add_selected, click: function(){
							jQuery('input[name="selected-pages"]').each(function(){
								if(jQuery(this).prop('checked') == true)
									t.insertSelectedPage(jQuery(this).val());
							});

							jQuery(this).dialog("close");
						}
					},
					{ text: adamlabsgallery_lang.close, click: function(){
							jQuery(this).dialog("close");
						}
					}
				],
				create:function () {
					jQuery(this).closest(".ui-dialog")
						.find(".ui-dialog-buttonpane") // the first button
						.addClass("save-wrap");
				},
			});
		});

		jQuery('#check-uncheck-pages').click(function(){
			var do_enable = true;
			if(jQuery(this).attr('checked') != 'checked') do_enable = false;

			jQuery('input[name="selected-pages"]').each(function(){
				jQuery(this).attr('checked', do_enable);
			});
		});

	}




	/***********************
	* Ajax
	***********************/

	t.ajaxRequest = function(action,data,statusElement,successFunction,args){
console.log(action);
		var objData = {
			action:"adamlabsgallery_request_ajax",
			client_action:action,
			token:token,
			data:data
		}

		if(typeof statusElement !== undefined){
			t.setAjaxLoaderElement(statusElement);
			t.showAjaxLoader();
		}

		jQuery.ajax({
			type:"post",
			url:ajaxurl,
			dataType: 'json',
			data:objData,
			success:function(response){
				
				t.hideAjaxLoader();

				if(!response){
					t.showErrorMessage(adamlabsgallery_lang.aj_empty_response);
					return(false);
				}

				if(response == -1){
					t.showErrorMessage(adamlabsgallery_lang.aj_ajax_error);
					return(false);
				}

				if(response == 0){
					t.showErrorMessage(adamlabsgallery_lang.aj_error_not_found+': <b>'+action+'</b>');
					return(false);
				}

				if(response.success == undefined){
					t.showErrorMessage(adamlabsgallery_lang.aj_success_must);
					return(false);
				}

				if(response.success == false){
					t.showErrorMessage(response.message);
					return(false);
				}

				//success actions:

				//run a success event function
				if(typeof successFunction == "function")
					successFunction(response,args);

				if(response.message)
					t.showSuccessMessage(response.message);


				//if everything worked and data is not false, check if redirect
				if(response.is_redirect)
					location.href=response.redirect_url;
				
				if(response.grid_id && !isNaN(response.grid_id)) {
					window.location = document.URL.replace('&create=true', '&create=' + response.grid_id);
					jQuery('.adamlabsgallery-refresh-preview-button').click();
				}

			},
			error:function(jqXHR, textStatus, errorThrown){
				t.hideAjaxLoader();

				if(textStatus == "parsererror")
					console.log(jqXHR.responseText);

				t.showErrorMessage(adamlabsgallery_lang.aj_ajax_error+" " + textStatus);
			}
		});

	}//ajaxrequest

	/**
	 * show error message or call once custom handler function
	 */
	t.showErrorMessage = function(htmlError, hideOn){
        if(typeof hideOn == undefined) hideOn = "click";

        AdminEssentials.showInfo({content:htmlError, type:"warning", showdelay:0, hidedelay:2, hideon:hideOn, event:"" });
	}

	/**
	 * show success message or call once custom handler function
	 */
	t.showSuccessMessage = function(htmlSuccess){
        AdminEssentials.showInfo({content:htmlSuccess, type:"success", showdelay:0, hidedelay:2, hideon:"", event:"" });
	}

	/**
	 * show success message or call once custom handler function
	 */
	t.showInfoMessage = function(htmlInfo){
        AdminEssentials.showInfo({content:htmlInfo, type:"info", showdelay:0, hidedelay:2, hideon:"", event:"" });
	}

	/**
	 * set ajax loader class that will be shown, and hidden on ajax request
	 * this loader will be shown only once, and then need to be sent again.
	 */
	t.setAjaxLoaderElement = function(newClass){
		ajaxLoaderElement = newClass;
	}

	t.showWaitAMinute = function(obj) {
		var wm = jQuery('#waitaminute');		
		// SHOW AND HIDE WITH DELAY
		if (obj.delay!=undefined) {

			adamlabsgallerygs.TweenLite.to(wm,0.3,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut});
			adamlabsgallerygs.TweenLite.set(wm,{display:"block"});
			
			setTimeout(function() {
				adamlabsgallerygs.TweenLite.to(wm,0.3,{autoAlpha:0,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
					adamlabsgallerygs.TweenLite.set(wm,{display:"block"});
				}});  			
			},obj.delay)
		}

		// SHOW IT
		if (obj.fadeIn != undefined) {
			adamlabsgallerygs.TweenLite.to(wm,obj.fadeIn/1000,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut});
			adamlabsgallerygs.TweenLite.set(wm,{display:"block"});
		}

		// HIDE IT
		if (obj.fadeOut != undefined) {

			adamlabsgallerygs.TweenLite.to(wm,obj.fadeOut/1000,{autoAlpha:0,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
					adamlabsgallerygs.TweenLite.set(wm,{display:"block"});
				}});  
		}

		// CHANGE TEXT
		if (obj.text != undefined) {
			switch (obj.text) {
				case "progress1":

				break;
				default:					
					wm.html('<div class="waitaminute-message"><i class="adamlabsgallery-icon-coffee"></i><br>'+obj.text+'</div>');
				break;	
			}
		}
	}


	/**
	 * show loader on ajax actions
	 */
	t.showAjaxLoader = function(){
		/*if(ajaxLoaderElement){
			jQuery(ajaxLoaderElement).hide();
			jQuery(ajaxLoaderElement).parent().append('<div class="ajax-loading-wrap">'+adamlabsgallery_lang.aj_please_wait+'</div>');
		}*/
		t.showWaitAMinute({fadeIn:300,text:adamlabsgallery_lang.aj_please_wait});
	}

	/**
	 * init Side Buttons
	 */
	t.initSideButtons = function(i) {
		adamlabsgallerygs.TweenLite.fromTo(jQuery('#adamlabsgallery-tool-panel'),0.5,{autoAlpha:0,x:40},{autoAlpha:1,x:0,ease:adamlabsgallerygs.Power3.easeInOut,delay:3});

		jQuery.each(jQuery('.adamlabsgallery-side-buttons'),function(ind,elem) {
			adamlabsgallerygs.TweenLite.fromTo(elem,0.5,{x:40},{x:0,ease:adamlabsgallerygs.Power3.easeInOut,delay:4+(ind*0.3)});
		})

		jQuery('.adamlabsgallery-side-buttons').hover(function() {
					adamlabsgallerygs.TweenLite.to(jQuery(this),0.3,{x:-110,ease:adamlabsgallerygs.Power3.easeInOut});
				},
				function() {
					adamlabsgallerygs.TweenLite.to(jQuery(this),0.3,{x:0,ease:adamlabsgallerygs.Power3.easeInOut});
				})

	}

	/**
	 * hide and remove ajax loader. next time has to be set again before "ajaxRequest" function.
	 */
	t.hideAjaxLoader = function(){
		if(ajaxLoaderElement){
			/*jQuery(ajaxLoaderElement).show();

			jQuery(".ajax-loading-wrap").each(function(){
                jQuery(this).remove();
            });*/
            t.showWaitAMinute({fadeOut:300});

			ajaxLoaderElement = null;
		}
	}

	t.initSmallMenu = function() {

		jQuery('.adamlabsgallery-lc-vertical-menu li').each(function(){
			var li = jQuery(this);
			li.click(function() {
				jQuery('.adamlabsgallery-lc-vertical-menu .selected-lc-setting').removeClass('selected-lc-setting');
				li.addClass('selected-lc-setting');

				var aes = jQuery('.adamlabsgallery-lc-menu-wrapper .active-esc')
				var newaes=jQuery('#'+li.data('toshow'));

				adamlabsgallerygs.TweenLite.to(aes,0.1,{autoAlpha:0});
				aes.removeClass("active-esc");

				adamlabsgallerygs.TweenLite.fromTo(newaes,0.3,{autoAlpha:0},{autoAlpha:1,overwrite:"all"});
				newaes.addClass("active-esc");
				setTimeout(t.interResizeSettings,10);
			})
		})


		jQuery('.adamlabsgallery-small-vertical-menu li').each(function(){
			var li = jQuery(this);
			li.click(function() {
				jQuery('.adamlabsgallery-small-vertical-menu .selected-el-setting').removeClass('selected-el-setting');
				li.addClass('selected-el-setting');

				var aes = jQuery('#adamlabsgallery-element-style .active-esc')
				var newaes=jQuery('#'+li.data('toshow'));

				adamlabsgallerygs.TweenLite.to(aes,0.1,{autoAlpha:0});
				aes.removeClass("active-esc");

				adamlabsgallerygs.TweenLite.fromTo(newaes,0.3,{autoAlpha:0},{autoAlpha:1,overwrite:"all"});
				newaes.addClass("active-esc");
				setTimeout(t.interResizeSettings,10);
			})
		})


	}


	t.initTabSizes = function() {
		jQuery('#adamlabsgallery-create-settings-menu li').each(function(){
			var li = jQuery(this);
			li.click(function() {
				jQuery('#adamlabsgallery-create-settings-menu .selected-adamlabsgallery-setting').removeClass('selected-adamlabsgallery-setting');
				li.addClass('selected-adamlabsgallery-setting');

				var aes = jQuery('.active-esc')
				var newaes=jQuery('#'+li.data('toshow'));

				adamlabsgallerygs.TweenLite.to(aes,0.1,{autoAlpha:0});
				aes.removeClass("active-esc");

				adamlabsgallerygs.TweenLite.fromTo(newaes,0.3,{autoAlpha:0},{autoAlpha:1,overwrite:"all"});
				newaes.addClass("active-esc");
				setTimeout(t.interResizeSettings,10);
				t.recalcSlidersPos();

				if(li.data('toshow') === 'adamlabsgallery-settings-skins-settings') {
					
					var skinPreview = jQuery('#adamlabsgallery-grid-even-1');
					if(!skinPreview.data('selectedpositioned')) skinPreview.data('selectedpositioned', true).find('.filter-selectedskin').prependTo(skinPreview.find('.mainul'));
					skinPreview.adamlabsgalleryredraw();
				}

				adamlabsgallery_codemirror_api_js.refresh();
				adamlabsgallery_codemirror_ajax_css.refresh();
			})
		});
		t.interResizeSettings();
		jQuery(window).resize(t.interResizeSettings);

		jQuery('#adamlabsgallery-create-settings-wrap').click(t.interResizeSettings);
	}

	t.interResizeSettings = function () {
		var ecsw = jQuery('#adamlabsgallery-create-settings-wrap');

		ecsw.find('.adamlabsgallery-settings-container').each(function(i) {
			var esc = jQuery(this);
			esc.width(jQuery('#adamlabsgallery-create-settings-wrap').width()-80);
		});

		adamlabsgallerygs.TweenLite.to(ecsw,0.3,{height:ecsw.find('.adamlabsgallery-settings-container.active-esc').outerHeight()+15,overwrite:"all"});

		ecsw.find('.ui-slider').each(function() {
			var uis = jQuery(this);
			var neww = parseInt(uis.find('.ui-slider-handle').css('left'),0);
			uis.find('.adamlabsgallery-pre-slider').css({width:neww});
		});

	}

	/********************************************************
		-	CALL ON CHANGE (SELECTOR AND CALL FUNCTION	-
	********************************************************/
	t.callOnChange = function( selector, call, delay, param1, param2) {

		jQuery(selector).find('input').each(function() {
			var input = jQuery(this);
			input.on("change",function() {
				call(param1,param2);
			});

			if (input.attr('type')=="hidden") {
				input.data('oldval',input.val());

				setInterval(function() {
					if (input.data('oldval')!=input.val()) {
						input.data('oldval',input.val());
						call(param1,param2);
					}
				},200)
			}
		})

		jQuery(selector).find('textarea').each(function() {
			var input = jQuery(this);
			input.data('oldval',input.val());

				setInterval(function() {
					if (input.data('oldval')!=input.val()) {
						input.data('oldval',input.val());
						call(param1,param2);
					}
				},200);
		})

		jQuery(selector).find('.ui-slider-handle').each(function() {
			var han = jQuery(this);

			han.on("mousedown",function() {
				han.data('pos',han.position().left);
				han.addClass("youaredown");
				clearInterval(han.data("timer"));
				han.data('timer',setInterval(function() {

					if (han.data('pos')!=han.position().left) {
						han.data('pos',han.position().left);
						call(param1,param2);
					}
				},delay));
			});

		});

		jQuery('body').on("mouseup",function() {
				jQuery(selector).find('.ui-slider-handle').each(function() {
					var han = jQuery(this);
					clearInterval(han.data("timer"));
				});
		});

		jQuery(selector).find('select').each(function() {
			var input = jQuery(this);
			input.on("change",function() {
				var sf = jQuery(this).parent().find('.select_fake');

				if (sf.length) {
					var cont = jQuery(this).find('option:selected').text()

					sf.find('span').html(cont);
				}
				call(param1,param2);

			});
		});


	}

	/******************************
		-	GET BASIC GRID VALUES	-
	********************************/

	t.getBasicEntries = function() {
		var colwidths = jQuery('input[name="columns-width[]"]');
		var cols = jQuery('input[name="columns[]"]');
		var basicEntries = new Array();

		jQuery.each(cols,function(index) {
			var obj = new Object();
			obj.width = parseInt(colwidths[index].value,0);
			obj.amount = parseInt(cols[index].value,0);
			basicEntries.push(obj);
		});

		return basicEntries;
	}

	t.getMultipleEntries = function() {

	  if (jQuery('input[name="columns-advanced"]:checked').val() == "on") {
			var multipleentries = new Array();
			var cols = jQuery('input[name="columns[]"]');
			var elar = new Array();
			jQuery.each(cols,function(index) {
				elar.push(parseInt(cols[index].value,0));
			});
			multipleentries.push(elar);

			for (var i=0; i<11;i++) {
				var cols = jQuery('input[name="columns-advanced-rows-'+i+'[]"]');
				if (cols!=undefined && cols.length>0) {

					var elar = new Array();
					jQuery.each(cols,function(index) {
						elar.push(parseInt(cols[index].value,0));
					});
					multipleentries.push(elar);
				}
			}
			return multipleentries;
	   }

	   else

	   return [];

	}

	/******************************
		-	CREATE A PREVIEW GRID	-
	********************************/

	t.buildGridPreview = function() {
		var custom_grid = false;
		var data = {
				name: 'gridform',
				handle: 'gridform', //is alias
				postparams: t.getFormParams('adamlabsgallery-form-create-posts'),
				params: t.getFormParams('adamlabsgallery-form-create-settings')
			};

			

		data.params['navigation-layout'] = t.get_navigation_layout();

		data.params['css-id'] = jQuery('#adamlabsgallery-id-value').val();

		data.id = jQuery('input[name="adamlabsgallery-id"]').val();


		if(jQuery('input[name="source-type"]:checked').val() == 'custom')
			custom_grid = true;

		if(custom_grid){
			var custom_layers = t.getFormParams('adamlabsgallery-custom-elements-form-wrap', true); //ignore empty values
			data.layers = (typeof custom_layers['layers'] !== 'undefined') ? custom_layers['layers'] : [];
		}
		/*  //TP: CHUNK
		*/

		jQuery('body').append('<div id="adamlabsgallery-preview-clone" style="display: none;"></div>');
		jQuery('#adamlabsgallery-preview-wrapper').clone(true).appendTo('#adamlabsgallery-preview-clone');

		jQuery('.adamlabsgallery-remove-on-reload').remove(); //remove initial input fields with starting data
		jQuery('.adamlabsgallery-new-temp-layer').remove(); //remove afterwards pushed input field with new entry data

		try{jQuery('#adamlabsgallery-preview-grid').adamlabsgallerykill();} catch(e) { console.log("e:"+e)}
		jQuery('#adamlabsgallery-preview-wrapper').remove();

		jQuery('#adamlabsgallery-preview-wrapping-wrapper').append('<div id="adamlabsgallery-preview-wrapper"></div>');
		/* //TP: CHUNK
		if(custom_grid){
			var chunk = 25;
			var chunk_layers = [];
			var temp_layers = [];
			var layers_html = [];
			if(data.layers.length > chunk){ //split the ajax requests into x / 25 parts

				for (var i = 0; i < data.layers.length; i += chunk) {
					temp_layers = data.layers.slice(i, i + chunk);
					chunk_layers.push(temp_layers);
				}

				chunk_layers.reverse();

				var ajaxReqs = [];

				for(var key in chunk_layers){
					data.layers = chunk_layers[key];

					var objData = {
						action:"adamlabsgallery_request_ajax",
						client_action:"get_preview_html_markup_chunk",
						order_id:key,
						token:token,
						data:data
					}

					ajaxReqs.push(
						jQuery.ajax({
							type:"post",
							url:ajaxurl,
							dataType: 'json',
							data:objData,
							success:function(response){

								if(!response){
									t.showErrorMessage(adamlabsgallery_lang.aj_empty_response);
									return(false);
								}

								if(response == -1){
									t.showErrorMessage(adamlabsgallery_lang.aj_ajax_error);
									return(false);
								}

								if(response == 0){
									t.showErrorMessage(adamlabsgallery_lang.aj_error_not_found+': <b>'+action+'</b>');
									return(false);
								}

								if(response.success == undefined){
									t.showErrorMessage(adamlabsgallery_lang.aj_success_must);
									return(false);
								}

								if(response.success == false){
									t.showErrorMessage(response.message);
									return(false);
								}

								//success actions:

								//run a success event function
								if(typeof successFunction == "function")
									successFunction(response,args);

								if(response.message)
									t.showSuccessMessage(response.message);


								//if everything worked and data is not false, check if redirect
								if(response.is_redirect)
									location.href=response.redirect_url;

								layers_html[response.data.order_id] = response.data.preview;
							},
							error:function(jqXHR, textStatus, errorThrown){

								if(textStatus == "parsererror")
									console.log(jqXHR.responseText);

								t.showErrorMessage(adamlabsgallery_lang.aj_ajax_error+" " + textStatus);
							}
						})
					);
				}

				data.layers = []; //reset to empty layers, to get only the html wrap structure
			}
		} */

		t.ajaxRequest("get_preview_html_markup", data, '#adamlabsgallery-grid-layout-wrapper,#adamlabsgallery-grid-skin-wrapper,.adamlabsgallery-refresh-preview-button,#adamlabsgallery-source-choose-wrapper,.ui-dialog-buttonset',function(response){
			var restore_previous = false;
			
			if(typeof(response.error) != 'undefined'){ //add last state again and say something about it
				alert(response.error);
				alert(adamlabsgallery_lang.script_will_try_to_load_last_working);

				restore_previous = true;

				jQuery('#adamlabsgallery-preview-clone').children().appendTo('#adamlabsgallery-preview-wrapper');

				//jQuery('#adamlabsgallery-preview-wrapper').html(temp_preview);

			}else{
				jQuery('#adamlabsgallery-preview-wrapper').append(response.data.html);
			}
			jQuery('#adamlabsgallery-preview-clone').remove();
			if(jQuery('input[name="source-type"]:checked').val() == 'custom'){
				if(!restore_previous){
					jQuery('#adamlabsgallery-template-wrapper').html(response.data.preview);
					jQuery("#adamlabsgallery-preview-grid ul").sortable({ //start the sorting
						start: function(){
							espprevrevapi.adamlabsgalleryquickdraw();
						},
						stop: function(){
							t.resetCustomItemValues();
							espprevrevapi.adamlabsgalleryquickdraw();
						},
						change: function(){
							t.changePreviewGrid();
						},
						placeholder: false,
						helper: 'original',
						cancel: '.ui-state-disabled'
					});
					jQuery("#adamlabsgallery-preview-grid ul").disableSelection();
				}else{
					//jQuery('#adamlabsgallery-template-wrapper').html(temp_preview);

					jQuery('window').trigger('resize');
					//espprevrevapi.adamlabsgalleryquickdraw();
				}
			}
			
			if(!restore_previous){
				var rim = new Array();
				if (data.params["columns-advanced-rows-0"] != undefined && data.params["columns-advanced"]=="on") {
					rim.push(data.params["columns"]);
					for (var i = 0;i<10;i++) {
						if (data.params["columns-advanced-rows-"+i] != undefined)
							rim.push(data.params["columns-advanced-rows-"+i])
					}
				}

				var rows = jQuery('input[name="rows"]').val();
				var basicEntries = t.getBasicEntries();
				var multipleEntries = t.getMultipleEntries();

				if (jQuery('input[name="rows-unlimited"]:checked').val()=="on") rows=9999;

				/* //TP: CHUNK
				if(custom_grid){
					jQuery.when.apply(jQuery, ajaxReqs).done(function(){

						for(var key in layers_html){
							jQuery('#adamlabsgallery-preview-wrapper ul').prepend(layers_html[key]);
						}

						espprevrevapi = jQuery('#adamlabsgallery-preview-grid').adamlabsgallery({

							layout:jQuery('input[name="layout"]:checked').val(),
							forceFullWidth:"off",

							row:rows,
							space:jQuery('input[name="spacings"]').val(),

							pageAnimation:jQuery('#grid-animation-select').val(),

							animSpeed:jQuery('input[name="grid-animation-speed"]').val(),
							animDelay:"on",
							delayBasic:jQuery('input[name="grid-animation-delay"]').val(),

							aspectratio:jQuery('input[name="x-ratio"]').val()+":"+jQuery('input[name="y-ratio"]').val(),
							rowItemMultiplier : rim,
							responsiveEntries: basicEntries

						});

						t.resetCustomItemValues();
						t.initToolTipser();

						// TWO OBJECT FOR SAVING ALL KIND OF INFORMATIONS OF GRID
						espprevrevapi.settings = new Object();
						espprevrevapi.standards = new Object();

						// SAVE GRID SETTINGS
						espprevrevapi.settings.layout=jQuery('input[name="layout"]:checked').val();
						espprevrevapi.settings.forceFullWidth="off";
						espprevrevapi.settings.row=rows;
						espprevrevapi.settings.space=jQuery('input[name="spacings"]').val();
						espprevrevapi.settings.pageAnimation=jQuery('select [name="grid-animation"]').val();
						espprevrevapi.settings.animSpeed=jQuery('input[name="grid-animation-speed"]').val();
						espprevrevapi.settings.animDelay="on";
						espprevrevapi.settings.delayBasic=jQuery('input[name="grid-animation-delay"]').val();
						espprevrevapi.settings.aspectratio=jQuery('input[name="x-ratio"]').val()+":"+jQuery('input[name="y-ratio"]').val();
						espprevrevapi.settings.responsiveEntries=basicEntries;
						espprevrevapi.settings.rowItemMultiplier=multipleEntries;
						espprevrevapi.settings.filterskin =jQuery('#navigation-skin-select').val();
						espprevrevapi.settings.skin = jQuery('input[name="entry-skin"]:checked').val();

					});
				}else{ */

					var cobbles_pattern = '';
					if(jQuery('input[name="use-cobbles-pattern"]:checked').val() == 'on'){
						jQuery('select[name="cobbles-pattern[]"]').each(function(){
							if(cobbles_pattern !== '') cobbles_pattern += ',';
							cobbles_pattern += jQuery(this).find('option:selected').val();
						});
					}

					var smart_pagination = 'on';
					if(jQuery('input[name="pagination-numbers"]:checked').val() == 'full')
						smart_pagination = 'off';
					
					// 2.2.5
					var startAnimation = jQuery('#grid-start-animation').val();
					if(startAnimation !== 'reveal') jQuery('#adamlabsgallery-preview-grid').find('ul').first().css('height', '1000px');
					else startAnimation = 'none';
					
					espprevrevapi = jQuery('#adamlabsgallery-preview-grid').adamlabsgallery({

						layout:jQuery('input[name="layout"]:checked').val(),
						forceFullWidth:"off",
						
						smartPagination:smart_pagination,
						cobblesPattern:cobbles_pattern,
						row:rows,
						space:jQuery('input[name="spacings"]').val(),

						pageAnimation:jQuery('#grid-animation-select').val(),

						animSpeed:jQuery('input[name="grid-animation-speed"]').val(),
						animDelay:"on",
						delayBasic:jQuery('input[name="grid-animation-delay"]').val(),
						
						startAnimation: startAnimation,
						startAnimationSpeed: jQuery('#grid-start-animation-speed').val(),
						startAnimationDelay: jQuery('#grid-start-animation-delay').val(),
						startAnimationType:jQuery('input[name="grid-start-animation-type"]:checked').val(),
						animationType:jQuery('input[name="grid-animation-type"]:checked').val(),

						aspectratio:jQuery('input[name="x-ratio"]').val()+":"+jQuery('input[name="y-ratio"]').val(),
						rowItemMultiplier : rim,
						responsiveEntries: basicEntries,
						
						lightBoxMode: jQuery('select[name="lightbox-mode"]').val(),
						lightboxHash: jQuery('input[name="lightbox-deep-link"]').val()

					});
					
					var lbPadding = [];
					jQuery('input[name="lbox-padding[]"]').each(function(i) {lbPadding[i] = parseInt(this.value, 0);});
					
					var lbButtons = [];
					jQuery('input[name="lb-button-order[]"]').each(function(i) {if(this.checked) lbButtons[i] = this.value;});
					
					var arrows = jQuery('input[name="lightbox-arrows"]:checked').val() === 'on' ? true : false,
						lightboxOptions = {
						margin : lbPadding,
						buttons : lbButtons,
						infobar : jQuery('input[name="lightbox-numbers"]:checked').val() === 'on' ? true : false,
						loop : false,
						slideShow : {autoStart: false, speed: 3000},
						animationEffect: jQuery('select[name="lightbox-effect-open-close"]').val(),
						animationDuration: jQuery('input[name="lightbox-effect-open-close-speed"]').val(),
						
						beforeShow: function(a, c) {
						  if(!arrows) {
							  jQuery("body").addClass("adamlabsgallerybox-hidearrows");
						  }
							var i = 0,
								multiple = false;
							a = a.slides;
							for(var b in a) {
								i++;
								if(i > 1) {
									multiple = true;
									break;
								}
							}
							if(!multiple) jQuery("body").addClass("adamlabsgallerybox-single");
							if(c.type === "image") jQuery(".adamlabsgallerybox-button--zoom").show();
						},
						beforeLoad: function(a, b) {
							jQuery("body").removeClass("adamlabsgallery-four-by-three");
							if(b.opts.$orig.data("ratio") === "4:3") jQuery("body").addClass("adamlabsgallery-four-by-three");
						},
						afterLoad: function() {jQuery(window).trigger("resize.adamlabsgallerylb");},
						afterClose : function() {jQuery("body").removeClass("adamlabsgallerybox-hidearrows adamlabsgallerybox-single");},
						transitionEffect : jQuery('select[name="lightbox-effect-next-prev-speed"]').val(),
						transitionDuration : jQuery('input[name="lightbox-effect-next-prev-speed"]').val(),
						hash: jQuery('input[name="lightbox-deep-link"]').val(),
						arrows: arrows,
						wheel: jQuery('input[name="lightbox-mousewheel"]:checked').val() === 'on' ? true : false
						
					};
					
					jQuery('#adamlabsgallery-preview-grid').data("lightboxsettings", lightboxOptions);
					
					try{
						jQuery('#adamlabsgallery-preview-grid .adamlabsgallerybox').adamlabsgallerybox(lightboxOptions);
					} catch (e) {}

					t.resetCustomItemValues();
					t.initToolTipser();

					// TWO OBJECT FOR SAVING ALL KIND OF INFORMATIONS OF GRID
					espprevrevapi.settings = new Object();
					espprevrevapi.standards = new Object();

					// SAVE GRID SETTINGS
					espprevrevapi.settings.layout=jQuery('input[name="layout"]:checked').val();
					espprevrevapi.settings.forceFullWidth="off";
					espprevrevapi.settings.row=rows;
					espprevrevapi.settings.smartPagination=smart_pagination;
					espprevrevapi.settings.space=jQuery('input[name="spacings"]').val();
					espprevrevapi.settings.pageAnimation=jQuery('select [name="grid-animation"]').val();
					espprevrevapi.settings.animSpeed=jQuery('input[name="grid-animation-speed"]').val();
					espprevrevapi.settings.animDelay="on";
					
					// 2.2.5
					espprevrevapi.settings.startAnimation = jQuery('#grid-start-animation').val();
					espprevrevapi.settings.startAnimationSpeed = jQuery('#grid-start-animation-speed').val();
					espprevrevapi.settings.startAnimationDelay = jQuery('#grid-start-animation-delay').val();
					espprevrevapi.settings.startAnimationType = jQuery('input[name="grid-start-animation-type"]:checked').val();
					espprevrevapi.settings.animationType = jQuery('input[name="grid-animation-type"]:checked').val();
					espprevrevapi.settings.lightBoxMode = jQuery('select[name="lightbox-mode"]').val();
					espprevrevapi.settings.lightboxHash = jQuery('input[name="lightbox-deep-link"]').val();
						
					espprevrevapi.settings.delayBasic=jQuery('input[name="grid-animation-delay"]').val();
					espprevrevapi.settings.aspectratio=jQuery('input[name="x-ratio"]').val()+":"+jQuery('input[name="y-ratio"]').val();
					espprevrevapi.settings.responsiveEntries=basicEntries;
					espprevrevapi.settings.rowItemMultiplier=multipleEntries;
					espprevrevapi.settings.filterskin =jQuery('#navigation-skin-select').val();
					espprevrevapi.settings.skin = jQuery('input[name="entry-skin"]:checked').val();

				//} //TP: CHUNK
			}
		});

	}


	t.createPreviewGrid = function() {

		t.buildGridPreview();

		// START THE ROUTINE TO CHECK IF ANY INPUT FIELDS HAS BEEN CHANGED !
		t.callOnChange( '#adamlabsgallery-form-create-settings', t.changePreviewGrid, 100);

		jQuery('.adamlabsgallery-refresh-preview-button').click(function() {
			t.changePreviewGrid(true);
		})
		//t.changePreviewGrid(esprevapi);
	}


	/***********************************************
		-	CHANGE GRID BASED ON NEW SETTINGS	-
	***********************************************/
	t.changePreviewGrid = function(rebuild) {

		if( typeof espprevrevapi !== 'undefined'){
			clearTimeout(espprevrevapi.timeout);
			
			var coverColor = jQuery('input[name="main-background-color"]');
			jQuery('#adamlabsgallery-live-preview-wrap').css('background', coverColor.attr('data-color') || RevColor.process(coverColor.val())[0]);

			espprevrevapi.timeout = setTimeout(function() {

				// CHANGE SKIN CLASS
				jQuery('#adamlabsgallery-preview-skinlevel').attr('class','myportfolio-container fullwidthcontainer-with-padding').addClass(jQuery('#navigation-skin-select').val());

				// COLLECT ALL GRID BASED OPTIONS, AND COMPARE THEM
				var basicEntries = t.getBasicEntries();
				var multipleEntries = t.getMultipleEntries();
				var smart_pagination = 'on';
				if(jQuery('input[name="pagination-numbers"]:checked').val() == 'full')
					smart_pagination = 'off';
						
				var settings = new Object();
				settings.layout=jQuery('input[name="layout"]:checked').val();
				settings.forceFullWidth="on";
				settings.row=jQuery('input[name="rows"]').val();
				if (jQuery('input[name="rows-unlimited"]:checked').val()=="on") settings.row=9999;
				settings.space=jQuery('input[name="spacings"]').val();
				settings.smartPagination=smart_pagination;
				settings.pageAnimation=jQuery('#grid-animation-select').val();
				settings.animSpeed=jQuery('input[name="grid-animation-speed"]').val();
				settings.animDelay="on";
				settings.delayBasic=jQuery('input[name="grid-animation-delay"]').val();
				settings.aspectratio=jQuery('input[name="x-ratio"]').val()+":"+jQuery('input[name="y-ratio"]').val();
				settings.responsiveEntries=basicEntries;
				settings.rowItemMultiplier=multipleEntries;
				settings.skin = jQuery('input[name="entry-skin"]:checked').val();
				settings.rtl = jQuery('input[name="rtl"]:checked').val();
				
				// 2.2.5
				settings.startAnimation = jQuery('#grid-start-animation').val();
				settings.startAnimationSpeed = jQuery('#grid-start-animation-speed').val();
				settings.startAnimationDelay = jQuery('#grid-start-animation-delay').val();
				settings.startAnimationType = jQuery('input[name="grid-start-animation-type"]:checked').val();
				settings.animationType = jQuery('input[name="grid-animation-type"]:checked').val();

				var different = false;
				var difkey = new Array();

				// COMPARE VALUES OF GRID SETTINGS
				jQuery.each(settings, function(key,index) {
					if (key!="responsiveEntries" && (settings[key] != espprevrevapi.settings[key])) {
						different = true;
						difkey.push(key);
					}
				})
				// COMPARE RESPONSIVE VALUES
				jQuery.each(settings.responsiveEntries,function(index,obj) {
					if (obj.width != espprevrevapi.settings.responsiveEntries[index].width ||
						obj.amount != espprevrevapi.settings.responsiveEntries[index].amount) {
						different = true;
						difkey.push("responsiveEntries");
					}
				})


				if (settings.skin != espprevrevapi.settings.skin || settings.layout != espprevrevapi.settings.layout || rebuild==true) {
					// SAVE NEW SETTINGS

					jQuery.extend(espprevrevapi.settings,settings);
					t.buildGridPreview();
					different = false;
				} else {

					// SAVE NEW SETTINGS
					jQuery.extend(espprevrevapi.settings,settings);
				}

				// IF DIFFERENT, REDRAW GRID
				if (different)  {

					espprevrevapi.adamlabsgalleryredraw({
						aspectratio:espprevrevapi.settings.aspectratio,
						space:espprevrevapi.settings.space,
						row:espprevrevapi.settings.row,
						pageAnimation:espprevrevapi.settings.pageAnimation,
						smartPagination:espprevrevapi.settings.smartPagination,
						animSpeed:espprevrevapi.settings.animSpeed,
						animDelay:"on",
						rtl:settings.rtl,
						responsiveEntries:espprevrevapi.settings.responsiveEntries,
						delayBasic:espprevrevapi.settings.delayBasic,
						silent:false,
						changedAnim:"pageanim",
						rowItemMultiplier:espprevrevapi.settings.rowItemMultiplier,
						
						/* 2.2.5 */
						startAnimation: espprevrevapi.settings.startAnimation,
						startAnimationSpeed: espprevrevapi.settings.startAnimationSpeed,
						startAnimationDelay: espprevrevapi.settings.startAnimationDelay,
						startAnimationType: espprevrevapi.settings.startAnimationType,
						animationType: espprevrevapi.settings.animationType

					});

					t.resetCustomItemValues();

					setTimeout(function() {
						var btns = jQuery('.adamlabsgallery-navigationbutton.adamlabsgallery-filterbutton.adamlabsgallery-pagination-button ');
						if (difkey[0]=="pageAnimation" || difkey[0] == "animSpeed" || difkey[0] == "delayBasic") {
							if (jQuery(btns[0]).hasClass("selected"))
								try{btns[1].click()} catch(e) { }
						}
						setTimeout(function() {
							if (btns != undefined && btns.length>0) btns[0].click();
						},250);
					 },250);

				}

				t.initToolTipser();

			},100);
		}
	}


	/******************************
		-	REMOVE A PREVIEW GRID	-
	********************************/
	t.removePreviewGrid = function() {

	}


	/******************************************************
		-	ANIMATE ELEMENTS FOR SKIN EDITOR PREVIEW	-
	*******************************************************/

	t.animateElements = function(direction) {

	  // PREPARE THE HOVER ANIMATIONS
	  if (miGalleryAnimmatrix == null)
	     var miGalleryAnimmatrix = [
	  						['.adamlabsgallery-none',				0, {autoAlpha:1,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0, {autoAlpha:1,overwrite:"all"} ],

	    					['.adamlabsgallery-fade',				0.3, {autoAlpha:0,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0.3, {autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
	    					['.adamlabsgallery-fadeout',			0.3, {autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, {autoAlpha:0,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0}, 0.3, {autoAlpha:1,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],

							['.adamlabsgallery-covergrowup',		0.3, {autoAlpha:1,top:"100%",marginTop:-10,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,top:"0%", marginTop:0, ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0.3, {autoAlpha:1,top:"100%",marginTop:-10,bottom:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],



				 			['.adamlabsgallery-flipvertical',		0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,rotationX:180,autoAlpha:0,z:-0.001,transformOrigin:"50% 50%"}, {rotationX:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5,{rotationX:180,autoAlpha:0,scale:1,z:-0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
			 				['.adamlabsgallery-flipverticalout',	0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,rotationX:0,autoAlpha:1,z:0.001,transformOrigin:"50% 50%"},{rotationX:-180,scale:1,autoAlpha:0,z:-150,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.5,{rotationX:0,autoAlpha:1,scale:1,z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

			 				['.adamlabsgallery-fliphorizontal',		0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,rotationY:180,autoAlpha:0,z:-0.001,transformOrigin:"50% 50%"}, {rotationY:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5, {rotationY:180,autoAlpha:0,scale:1,z:-0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
				 			['.adamlabsgallery-fliphorizontalout',	0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,z:0.001,transformOrigin:"50% 50%"}, {rotationY:-180,scale:1,autoAlpha:0,z:-150,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5, {rotationY:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

			 				['.adamlabsgallery-flipup',				0.5, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 100%"}, {scale:1,rotationX:0,autoAlpha:1,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} , 0.3, {scale:0.8,rotationX:90,autoAlpha:0,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],
			 				['.adamlabsgallery-flipupout',			0.5, {rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ,{x:0,y:0,scale:1,rotationZ:0,rotationX:-90,rotationY:0,skewX:0,skewY:0,autoAlpha:1,z:0.001,transformOrigin:"50% 0%"} , 0.3, {rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ],


			 				['.adamlabsgallery-flipdown',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:-90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 0%"},{rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ,0.3, {rotationX:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,autoAlpha:0,overwrite:"all"},true ],
			 				['.adamlabsgallery-flipdownout',		0.5, {scale:1,rotationX:0,autoAlpha:1,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 100%"}, 0.3, {scale:1,rotationX:0,autoAlpha:1,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-flipright',			0.5, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:0,rotationY:90,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"0% 50%"},{scale:1,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{autoAlpha:0,scale:0.8,rotationY:90,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,true],
			 				['.adamlabsgallery-fliprightout',		0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,rotationY:0,autoAlpha:1,transformOrigin:"100% 50%"},{scale:1,rotationY:-90,autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{scale:1,z:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-flipleft',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:-90,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50%"},{rotationY:0,autoAlpha:1,z:0.001,scale:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{autoAlpha:0,rotationY:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],
			 				['.adamlabsgallery-flipleftout',		0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,rotationY:0,autoAlpha:1,transformOrigin:"0% 50%"},{scale:1,rotationY:90,autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{scale:1,z:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-turn',				0.5, {x:50,y:0,scale:0,rotationZ:0,rotationX:0,rotationY:-40,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{scale:1,x:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.3,{scale:0,rotationY:-40,autoAlpha:1,z:0,x:50,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
			 				['.adamlabsgallery-turnout',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{scale:1,rotationY:40,scale:0.6,autoAlpha:0,x:-50,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.3,{scale:1,rotationY:0,z:0,autoAlpha:1,x:0, rotationX:0, rotationZ:0, ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slide',				0.5, {x:-10000,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, y:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:-10000,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideout',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, y:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slideright',			0.5, {xPercent:-50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:-50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-sliderightout',		0.5, {autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-scaleleft',			0.5, {x:0,y:0,scaleX:0,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50%"},{autoAlpha:1,x:0, scaleX:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,z:0,scaleX:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-scaleright',			0.5, {x:0,y:0,scaleX:0,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"0% 50%"},{autoAlpha:1,x:0, scaleX:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,z:0,scaleX:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slideleft',			0.5, {xPercent:50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideleftout',		0.5, {autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-slideup',			0.5, {x:0,yPercent:50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,yPercent:50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideupout',			0.5, {autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,yPercent:-50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slidedown',			0.5, {x:0,yPercent:-50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,yPercent:-50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slidedownout',		0.5, {autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,yPercent:50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slideshortright',	0.5, {x:-30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,x:-30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideshortrightout',	0.5, {autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,x:30, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slideshortleft',		0.5, {x:30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,x:30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideshortleftout',	0.5, {autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:-30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-slideshortup',		0.5, {x:0,y:30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,y:30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideshortupout',	0.5, {autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,y:-30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-slideshortdown',		0.5, {x:0,y:-30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,y:-30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-slideshortdownout',	0.5, {autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,y:30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],


			 				['.adamlabsgallery-skewright',			0.5, {xPercent:-100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,skewX:-60,xPercent:-100,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-skewrightout',		0.5, {autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:-60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-skewleft',			0.5, {xPercent:100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:-60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:100,z:0,skewX:60,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-skewleftout',		0.5, {autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-shifttotop',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-rollleft',			0.5, {xPercent:50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:90,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:50,z:0,rotationZ:90,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-rollleftout',		0.5, {autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:90,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-rollright',			0.5, {xPercent:-50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:-90,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:-50,rotationZ:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-rollrightout',		0.5, {autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:-90,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-falldown',			0.4, {x:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0, yPercent:-100},{autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.4,{yPercent:-100,autoAlpha:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,delay:0.2,overwrite:"all"} ],
			 				['.adamlabsgallery-falldownout',		0.4, {autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0, yPercent:100},0.4,{autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

			 				['.adamlabsgallery-rotatescale',		0.3, {x:0,y:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:80,scale:0.6,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.3,{autoAlpha:0,scale:0.6,z:0,rotationZ:80,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-rotatescaleout',		0.3, {autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0,y:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:80,scale:0.6,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-zoomintocorner',		0.5, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"20% 50%"},{autoAlpha:1,scale:1.2, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:1,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-zoomouttocorner',	0.5, {x:0, y:0,scale:1.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"80% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:1.2,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-zoomtodefault',		0.5, {x:0, y:0,scale:1.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:1.2,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-zoomback',			0.5, {x:0, y:0,scale:0.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:0.2,autoAlpha:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-zoombackout',		0.5, {autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0, y:0,scale:0.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.5,{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-zoomfront',			0.5, {x:0, y:0,scale:1.5,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:1.5,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-zoomfrontout',		0.5, {autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0, y:0,scale:1.5,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.5,{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-flyleft',			0.8, {x:-80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:75,rotationX:10,skewX:0,skewY:0,autoAlpha:0.01,transformOrigin:"30% 10%"},{x:0, y:0, rotationY:0,  z:0.001,rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},0.8,{autoAlpha:0.01,x:-40, y:0,z:300,rotationY:60,rotationX:20,overwrite:"all"}],
			 				['.adamlabsgallery-flyleftout',			0.8, {x:0, y:0, rotationY:0,  z:0.001,rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},{x:-80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:75,rotationX:10,skewX:0,skewY:0,autoAlpha:0.01,transformOrigin:"30% 10%"},0.8,{x:0, y:0, rotationY:0,  z:0.001,rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"}],

			 				['.adamlabsgallery-flyright',			0.8, {scale:1,skewX:0,skewY:0,autoAlpha:0,x:80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:-75,rotationX:10,transformOrigin:"70% 20%"},{x:0, y:0, rotationY:0,  z:0.001,rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},0.8,{autoAlpha:0,x:40, y:-40,z:300,rotationY:-60,rotationX:-40,overwrite:"all"}],
			 				['.adamlabsgallery-flyrightout',		0.8, {x:0, y:0, rotationY:0,  z:0.001,rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},{scale:1,skewX:0,skewY:0,autoAlpha:0,x:80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:-75,rotationX:10,transformOrigin:"70% 20%"},0.8,{x:0, y:0, rotationY:0,  z:0.001,rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"}],

			 				['.adamlabsgallery-mediazoom',			0.3, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1.4, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.3,{autoAlpha:0,x:0, y:0,scale:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
			 				['.adamlabsgallery-zoomandrotate',		0.6, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1.4, x:0, y:0, rotationZ:30,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"},0.4,{x:0, y:0,scale:1,z:0,rotationZ:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],

			 				['.adamlabsgallery-pressback',			0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{rotationY:0,autoAlpha:1,scale:0.8,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{rotationY:0,autoAlpha:1,z:0,scale:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
			 				['.adamlabsgallery-3dturnright',		0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformPerspective:600},{x:-40,y:0,scale:0.8,rotationZ:2,rotationX:5,rotationY:-28,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50% 40%",transformPerspective:600,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{z:0,x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,force3D:"auto",ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true]
			 			   ];




					 	 // ADD A CLASS FOR ANY FURTHER DEVELOPEMENTS
					 	 var item = jQuery('#skin-dz-wrapper');


						 var maxdd = 0;


						 var	 mc = jQuery('.adamlabsgallery-editor-inside-wrapper'),
								 content = jQuery('#skin-dz-m-wrap'),
								 covergroup = jQuery('#skin-dz-wrapper'),
								 media = jQuery('#skin-dz-media-bg');

						 adamlabsgallerygs.TweenLite.set(mc,{transformStyle:"flat"});
						 adamlabsgallerygs.TweenLite.set(content,{transformStyle:"flat"});
						 adamlabsgallerygs.TweenLite.set(covergroup,{transformStyle:"flat"});
						 adamlabsgallerygs.TweenLite.set(media,{transformStyle:"flat"});
						 var nextd =direction;


						 if (direction=="hover") {

							 jQuery.each(miGalleryAnimmatrix,function(index,key) {

								 item.parent().find(key[0]).each(function() {

									  var elem = jQuery(this);
									  var hideunder = elem.data('hideunder');

									  if (elem.hasClass("adamlabsgallery-special-element")){
										//NOTHINNG
									  }

									  else

									  if (item.width()<hideunder && hideunder!=undefined) {

									  	  if (elem.css('display')!="none") {
									  	  	elem.data('display',elem.css('display'));

									  	  }
										  elem.css({display:'none'});
									  } else {
										  var dd = elem.data('delay')!=undefined ? parseFloat(elem.data('delay')) : 0;

										  var animfrom = key[2];
				  						  var animto = key[3];
				  						  animto.delay=dd;
				  						  animto.overwrite="all";
				  						  animfrom.overwrite="all";
				  						  animto.transformStyle="flat";
				  						  animto.force3D = true;
				  						  var splitted = false;
				  						  var elemdelay =0;

				  						  if (elem.attr('id')!="skin-dz-media-bg")
					  						  animto.clearProps="transform";

				  						  if (animfrom.z == undefined) animfrom.z = 0.001;
				  						  if (animto.z == undefined || animto.z==0) animto.z = 0.001;


				  						  if (key[0]==".adamlabsgallery-shifttotop") {
					  						 // animto.y = 0 - item.find('.adamlabsgallery-entry-cover').last().height();
					  						 animto.y = 0 - item.find('#skin-dz-br').last().height();
				  						  }

				  						  if (key[0]==".adamlabsgallery-slide") {
					  						  var dire = Math.round(Math.random()*4+1);
					  						  switch (dire) {
						  						  case 1:
						  						  	animfrom.y = -20-elem.height();
						  						  	animfrom.x = 0;
						  						  break;
						  						  case 2:
						  						  	animfrom.y = 20+elem.height();
						  						  	animfrom.x = 0;
						  						  break;
						  						  case 3:
						  						  	animfrom.x = -20-elem.width();
						  						  	animfrom.y = 0;
						  						  break;
						  						  case 4:
						  						  	animfrom.x = 20+elem.width();
						  						  	animfrom.y = 0;
						  						  break;
						  						  default:
						  						  	animfrom.x = 20+elem.width();
						  						  	animfrom.y = 0;
						  						  break;
					  						  }
					  						  elem.closest('.adamlabsgallery-editor-inside-wrapper').css({overflow:"hidden"});
				  						  }

				  						   if (key[0]==".adamlabsgallery-slideout") {
					  						  var dire = Math.round(Math.random()*4+1);
					  						  switch (dire) {
						  						  case 1:
						  						  	animto.y = -20-elem.height();
						  						  	animto.x = 0;
						  						  break;
						  						  case 2:
						  						  	animto.y = 20+elem.height();
						  						  	animto.x = 0;
						  						  break;
						  						  case 3:
						  						  	animto.x = -20-elem.width();
						  						  	animto.y = 0;
						  						  break;
						  						  case 4:
						  						  	animto.x = 20+elem.width();
						  						  	animto.y = 0;
						  						  break;
						  						  default:
						  						  	animto.x = 20+elem.width();
						  						  	animto.y = 0;
						  						  break;
					  						  }
					  						  elem.closest('.adamlabsgallery-editor-inside-wrapper').css({overflow:"hidden"});
				  						  }

				  						  if (animto.transformPerspective ==undefined)
					  						  animto.transformPerspective = 1000;

					  					  if (animfrom.transformPerspective ==undefined)
					  						  animfrom.transformPerspective = 1000;


				  						  adamlabsgallerygs.TweenLite.killTweensOf(elem,false);

				  						  var animobject = elem;

				  						  adamlabsgallerygs.TweenLite.fromTo(animobject,key[1],animfrom,animto,elemdelay);


										  if (elem.css('display')=="none")
										  	elem.css({display:elem.data('display')});
									    if (dd>maxdd)  maxdd =dd ;
				  					}
								 })
							 })
							 nextd = "leave";
						}

						if (direction=="last") {
							jQuery.each(miGalleryAnimmatrix,function(index,key) {
								 item.parent().find(key[0]).each(function() {
								 	var elem = jQuery(this);

								 	adamlabsgallerygs.TweenLite.killTweensOf(elem,false);
								 	if (elem.data('mySplitText') !=undefined) elem.data('mySplitText').revert();
								 	adamlabsgallerygs.TweenLite.to(item,0.5,{autoAlpha:1,overwrite:"auto",clearProps:"transform"});
								 	adamlabsgallerygs.TweenLite.to(elem,0.5,{skewX:0, skewY:0, rotationX:0, rotationY:0, rotationZ:0, x:0, y:0, z:0,scale:1,clearProps:"transform",autoAlpha:1,overwrite:"all"});
								 	elem.closest('.adamlabsgallery-editor-inside-wrapper').css({overflow:"hidden"});
								 });
							})
						}

						if (direction=="leave") {
									 var maxdelay=0;
									 jQuery.each(miGalleryAnimmatrix,function(index,key) {
										 item.parent().find(key[0]).each(function() {
											  var elem = jQuery(this);
											  var dd = elem.data('delay')!=undefined ? elem.data('delay') : 0;
											  var animto = key[5];
											  if (maxdelay<dd) maxdelay = dd;
					  						  animto.z = 0;
					  						  var elemdelay =0;
					  						  var animobject = elem;
					  						  var splitted = false;

					  						  if (elem.attr('id')=="skin-dz-media-bg")
					  						  	animto.clearProps="transform";

					  						  if (key[0]==".adamlabsgallery-slide") {
						  						  var dire = Math.round(Math.random()*4+1);
						  						  switch (dire) {
							  						  case 1:
							  						  	animto.y = -20-elem.height();
							  						  	animto.x = 0;
							  						  break;
							  						  case 2:
							  						  	animto.y = 20+elem.height();
							  						  	animto.x = 0;
							  						  break;
							  						  case 3:
							  						  	animto.x = -20-elem.width();
							  						  	animto.y = 0;
							  						  break;
							  						  case 4:
							  						  	animto.x = 20+elem.width();
							  						  	animto.y = 0;
							  						  break;
							  						  default:
							  						  	animto.x = 20+elem.width();
							  						  	animto.y = 0;
							  						  break;
						  						  }
						  						  elem.closest('.adamlabsgallery-editor-inside-wrapper').css({overflow:"hidden"});
						  						 }


					  					   if (elem.hasClass("adamlabsgallery-special-element")){
						  					   		//NOTHINNG
						  					  }
						  					  else {
												  adamlabsgallerygs.TweenLite.to(animobject,key[4],animto,elemdelay);
						  					  }




										 })
									 })
									 if (maxdelay==0) maxdelay=0.2;

					 	     maxdd=0;
					 	     nextd="hover";
					 	}



					 	if (direction!="last")
						 	jQuery('#adamlabsgallery-preview-item-skin').data('timer',setTimeout(function() {
							    t.animateElements(nextd);
							},(maxdd*1000)+1500))


	}


	t.playElementAnimation = function() {

		jQuery('#drop-1').removeClass("revgreen").addClass("revred");
		jQuery(".dropzonetext").css({visibility:"hidden"});

		jQuery('#adamlabsgallery-preview-item-skin').css({'display':'none'});
		jQuery('#adamlabsgallery-preview-stop-item-skin').css({'display':'inline-block'});

		jQuery('#skin-dz-wrapper .skin-dz-elements.adamlabsgallery-special-element').css({visibility:"hidden"});

		jQuery('.dropzonetext').each(function() {
				jQuery(this).closest('.eec').addClass("adamlabsgallery-filled-container")
		})

		var but = jQuery('#make-3d-map');

		if (but.hasClass("3don")) {
			t.moveIn3d("off",0);
			but.removeClass("3don");
			but.html("3D");
			setTimeout(function() {
				t.animateElements("hover");
			},600)
		} else {
				t.animateElements("hover");
		}


	};

	t.stopElementAnimation = function(now) {

		jQuery('#drop-1').removeClass("revred").addClass("revgreen");
		jQuery(".dropzonetext").css({visibility:"visible"});


		clearTimeout(jQuery('#adamlabsgallery-preview-item-skin').data('timer'));
		var item = jQuery('#skin-dz-wrapper');

		jQuery('#adamlabsgallery-preview-item-skin').css({'display':'inline-block'});
		jQuery('#adamlabsgallery-preview-stop-item-skin').css({'display':'none'});
		jQuery('#skin-dz-wrapper .skin-dz-elements.adamlabsgallery-special-element').css({visibility:"visible"});



		clearTimeout(jQuery('#adamlabsgallery-preview-item-skin').data('timer'));
		setTimeout(function() {
			clearTimeout(jQuery('#adamlabsgallery-preview-item-skin').data('timer'));
		 	t.animateElements("last");
		},200)


		t.atDropStop();


	}

/******************************************************
	-	SCHEMATIC ANIMATION && DROP ZONE HIDES	-
******************************************************/

	t.adamlabsgallery3dtakeCare = function() {

		var timer;

		jQuery('#drop-1').click(function() {
			var bt = jQuery(this);
			var cl = ".dropzonetext";

			if (bt.hasClass("revgreen")) {
				bt.removeClass("revgreen").addClass("revred");
				jQuery(cl).css({visibility:"hidden"});
			} else {
				bt.removeClass("revred").addClass("revgreen");
				jQuery(cl).css({visibility:"visible"});
			}
		})

		jQuery('#make-3d-map').hover(

			function() {
				clearTimeout(timer)
				timer=setTimeout(t.show3danim,250);


			},

			function() {
					clearTimeout(timer)
					t.hide3danim();
			});
	}

	t.show3danim = function() {
		var orig = jQuery('.adamlabsgallery-editor-inside-wrapper');
				var mc = jQuery('#adamlabsgallery-3dpp');
				var tw = jQuery('#adamlabsgallery-it-layout-wrap');
				var mci = jQuery('#adamlabsgallery-3dpp-inner');

				var bg = jQuery('.adamlabsgallery-3d-bg');
				var cover = jQuery('.adamlabsgallery-3d-cover');
				var elem = jQuery('.adamlabsgallery-3d-element');
				var elems = jQuery('.adamlabsgallery-3d-elements');
				var content = jQuery('.adamlabsgallery-3dcc');
				var bgcc = jQuery('.adamlabsgallery-3d-ccbg');
				var st1 = jQuery('#adamlabsgallery-3d-cstep1');
				var st2 = jQuery('#adamlabsgallery-3d-cstep2');
				var st3 = jQuery('#adamlabsgallery-3d-cstep3');
				var st4 = jQuery('#adamlabsgallery-3d-cstep4');


				adamlabsgallerygs.TweenLite.to(jQuery('#adamlabsgallery-it-layout-wrap'),0.5,{backgroundColor:"#d5d5d5"});
				adamlabsgallerygs.TweenLite.to(orig,0.5,{autoAlpha:0});
				adamlabsgallerygs.TweenLite.to(mc,0.5,{z:-200,y:-30,autoAlpha:1})
				adamlabsgallerygs.TweenLite.to(tw,0.3,{minHeight:600});

				//3d MAP STEP1

				adamlabsgallerygs.TweenLite.set(mci,{z:0,rotationY:0,rotationX:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(jQuery('#adamlabsgallery-3d-description'),{autoAlpha:0, delay:3});
				adamlabsgallerygs.TweenLite.set(elem,{ opacity:1,z:4,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(elems,{opacity:1,z:4,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(cover,{opacity:1, z:3,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(bgcc,{opacity:1, z:2,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(bg,{opacity:1, z:1,overwrite:"all"});

				adamlabsgallerygs.TweenLite.set(mc,{transformStyle:"preserve-3d", transformOrigin:"50% 50% 50%",transformPerspective:1200});
				adamlabsgallerygs.TweenLite.set(jQuery('.adamlabsgallery-3dmc'),{transformStyle:"preserve-3d", transformOrigin:"50% 50% 50%",transformPerspective:1200});
				adamlabsgallerygs.TweenLite.set(mci,{transformStyle:"preserve-3d", transformOrigin:"50% 50% 0%",transformPerspective:1200});
				adamlabsgallerygs.TweenLite.set(elems,{transformStyle:"preserve-3d", transformOrigin:"50% 50% 0%",transformPerspective:1200});
				adamlabsgallerygs.TweenLite.set(content,{transformStyle:"preserve-3d", transformOrigin:"50% 50% 0%",transformPerspective:1200});
				adamlabsgallerygs.TweenLite.to(mci,2,{z:0,rotationY:-45,rotationX:7,delay:0.5,overwrite:"all",ease:adamlabsgallerygs.Power1.easeOut});



				// STEP2
				adamlabsgallerygs.TweenLite.to(elem,1,{z:90,overwrite:"all",delay:1,ease:adamlabsgallerygs.Power1.easeOut});
				adamlabsgallerygs.TweenLite.to(cover,0.7,{z:50,overwrite:"all" ,delay:1,ease:adamlabsgallerygs.Power1.easeOut});
				adamlabsgallerygs.TweenLite.to(bgcc,0.5,{z:22,overwrite:"all",delay:1,ease:adamlabsgallerygs.Power1.easeOut});
				adamlabsgallerygs.TweenLite.to(bg,0.5,{z:20,overwrite:"all",delay:1,ease:adamlabsgallerygs.Power1.easeOut});



				// STEP3
				adamlabsgallerygs.TweenLite.to(jQuery('#adamlabsgallery-3d-description'),0.5,{autoAlpha:1, delay:3});
				adamlabsgallerygs.TweenLite.fromTo(st1,0.5,{autoAlpha:0,y:-10},{autoAlpha:1, y:0,delay:3});
				adamlabsgallerygs.TweenLite.to(elems,0.5,{opacity:1,delay:3});
				adamlabsgallerygs.TweenLite.to(cover,0.5,{opacity:0,delay:3});
				adamlabsgallerygs.TweenLite.to(content,0.5,{opacity:0,delay:3});
				adamlabsgallerygs.TweenLite.to(bg,0.5,{opacity:0,delay:3});

				// STEP4
				adamlabsgallerygs.TweenLite.to(st1,0.5,{autoAlpha:0, delay:4.5});
				adamlabsgallerygs.TweenLite.fromTo(st2,0.5,{autoAlpha:0,y:-10},{autoAlpha:1, y:0,delay:4.5});
				adamlabsgallerygs.TweenLite.to(elems,0.5,{opacity:0,delay:4.5});
				adamlabsgallerygs.TweenLite.to(cover,0.5,{opacity:1,delay:4.5});

				// STEP5
				adamlabsgallerygs.TweenLite.to(st2,0.5,{autoAlpha:0, delay:6});
				adamlabsgallerygs.TweenLite.fromTo(st3,0.5,{autoAlpha:0,y:-10},{autoAlpha:1, y:0,delay:6});
				adamlabsgallerygs.TweenLite.to(cover,0.5,{opacity:0,delay:6});
				adamlabsgallerygs.TweenLite.to(bg,0.5,{opacity:1,delay:6});

				// STEP5
				adamlabsgallerygs.TweenLite.to(st3,0.5,{autoAlpha:0, delay:7.5});
				adamlabsgallerygs.TweenLite.fromTo(st4,0.5,{autoAlpha:0,y:-10},{autoAlpha:1, y:0,delay:7.5});
				adamlabsgallerygs.TweenLite.to(content,0.5,{opacity:1,delay:7.5});
				adamlabsgallerygs.TweenLite.to(bg,0.5,{opacity:0,delay:7.5});

				orig.data('timer',setTimeout(function() {
					t.hide3danim();
				},9000));
	}

	t.hide3danim = function() {
				var orig = jQuery('.adamlabsgallery-editor-inside-wrapper');
				clearTimeout(orig.data('timer'))

				var mc = jQuery('#adamlabsgallery-3dpp');
				var tw = jQuery('#adamlabsgallery-it-layout-wrap');
				var mci = jQuery('#adamlabsgallery-3dpp-inner');

				var bg = jQuery('.adamlabsgallery-3d-bg');
				var cover = jQuery('.adamlabsgallery-3d-cover');
				var elem = jQuery('.adamlabsgallery-3d-element');
				var elems = jQuery('.adamlabsgallery-3d-elements');
				var content = jQuery('.adamlabsgallery-3dcc');
				var bgcc = jQuery('.adamlabsgallery-3d-ccbg');
				var st1 = jQuery('#adamlabsgallery-3d-cstep1');
				var st2 = jQuery('#adamlabsgallery-3d-cstep2');
				var st3 = jQuery('#adamlabsgallery-3d-cstep3');
				var st4 = jQuery('#adamlabsgallery-3d-cstep4');


				adamlabsgallerygs.TweenLite.to(jQuery('#adamlabsgallery-it-layout-wrap'),0.5,{backgroundColor:"#fff"});
				adamlabsgallerygs.TweenLite.to(mc,0.5,{z:0,y:0,autoAlpha:0})
				adamlabsgallerygs.TweenLite.to(orig,0.5,{autoAlpha:1});
				adamlabsgallerygs.TweenLite.to(tw,0.3,{minHeight:0});

				//3d MAP
				adamlabsgallerygs.TweenLite.set(st1,{autoAlpha:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(st2,{autoAlpha:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(st3,{autoAlpha:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.set(st4,{autoAlpha:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(content,0.5,{opacity:1,overwrite:"all"});

				adamlabsgallerygs.TweenLite.to(mci,0.5,{z:0,rotationY:0,rotationX:0,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(jQuery('#adamlabsgallery-3d-description'),0.5,{autoAlpha:0, delay:3});
				adamlabsgallerygs.TweenLite.to(elem,0.5,{ opacity:1,z:4,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(elems,0.5,{opacity:1,z:4,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(cover,0.5,{opacity:1, z:3,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(bgcc,0.5,{opacity:1, z:2,overwrite:"all"});
				adamlabsgallerygs.TweenLite.to(bg,0.5,{opacity:1, z:1,overwrite:"all"});
	}



	/********************************************
		-	SORT AND DRAG DROP LOOK A LIKE	-
	********************************************/

	t.whileDropOrSort = function(selector) {
		 var esh = jQuery('.adamlabsgallery-state-highlight');
         var dd = jQuery(selector);
         esh.html(dd.html());
         esh.css({
         		'borderTop':dd.css('borderTop'),
		 		'borderBottom':dd.css('borderBottom'),
		 		'borderRight':dd.css('borderRight'),
		 		'borderLeft':dd.css('borderLeft'),

         		'paddingTop':dd.css('paddingTop'),
		 		'paddingBottom':dd.css('paddingBottom'),
		 		'paddingLeft':dd.css('paddingLeft'),
		 		'paddingRight':dd.css('paddingRight'),
        		'color':dd.css('color'),
        		'border':dd.css('border'),
        		'marginTop':dd.css('marginTop'),
        		'marginLeft':dd.css('marginLeft'),
        		'marginBottom':dd.css('marginBottom'),
        		'marginRight':dd.css('marginRight'),
        		'fontSize':dd.css('fontSize'),
        		'lineHeight':dd.css('lineHeight')
				})
		t.atDropStop();
	}

	t.adjustDropHeights = function() {
		var tc = jQuery('.adamlabsgallery-tc.eec');
		var bc = jQuery('.adamlabsgallery-bc.eec');
		var cc = jQuery('.adamlabsgallery-bc.eec');

		var tci = jQuery('#skin-dz-tl');
		var bci = jQuery('#skin-dz-br');
		var cci = jQuery('#skin-dz-c');

		var pp = jQuery('#skin-dz-wrapper');
		pph = pp.height();
		var shouldh = ((pph*0.8) - cci.height()) /2;
		if (shouldh > (pph*0.8)/3) shouldh =(pph*0.8)/3



		if (!tc.hasClass("adamlabsgallery-filled-container")) {
			tc.css({minHeight:shouldh+"px"});

		} else {

			tc.css({height:"auto"});
		}

		if (!bc.hasClass("adamlabsgallery-filled-container")) {
			bc.css({minHeight:shouldh+"px"});

		} else {
			bc.css({minHeight:"auto"});
		}
	}

	t.atDropStop = function(limit) {
		if (limit==undefined) limit = 0;

		if (jQuery("input[name='choose-layout']:checked").val() =="even")
		  jQuery('#drop-4').hide();
		else
		  jQuery('#drop-4').show();

		jQuery('.dropzonetext').each(function(i) {
			var eec = jQuery(this).closest('.eec')
			if (eec.length==0) eec = jQuery(this).closest("#skin-dz-m-wrap");

			if (eec.length>0) {
				var amountelems = eec.find('.skin-dz-elements').length;
				var dragelems = eec.find('.skin-dz-elements.ui-sortable-helper').length;


				if (amountelems>limit) {
					eec.addClass("adamlabsgallery-filled-container")
				} else {

					if (amountelems==1 && dragelems!=1)
						eec.addClass("adamlabsgallery-filled-container")
					else
						eec.removeClass("adamlabsgallery-filled-container")
				}
			}
		})
		t.adjustDropHeights();

	}

	t.presetSelects = function() {
		jQuery('#adamlabsgallery-wrap').find('select').each(function() {
			var input = jQuery(this);
			var sf = jQuery(this).parent().find('.select_fake');
			if (sf.length) {
				var cont = input.find('option:selected').text();
				if (cont.length==0) cont = adamlabsgallery_lang.not_selected;
				sf.find('span').html(cont);
			}
		})

	}

	t.initGridLibraryRoutine = function(){
		jQuery('#adamlabsgallery-libary-wrapper').on('showitnow',scrollTA);
		jQuery('body').on('click','.show_more_library_grid',function() {
			jQuery('.adamlabsgallery_group_wrappers').css({zIndex:2});
			var item = jQuery(this).closest('.adamlabsgallery_group_wrappers');
			if (item.length>0) {				
				if (jQuery(window).width() - item.offset().left < item.width()*2.1)
					item.addClass("show_more_to_left")
				else 
					item.removeClass("show_more_to_left");

				item.find('.library_thumb_more').fadeIn(100);
				jQuery('#library_bigoverlay').css("height",'5000px').fadeIn(100);
				item.css({zIndex:150});
			}
		});
		
		jQuery('body').on('click', '#library_bigoverlay',function() {
			jQuery('#library_bigoverlay').fadeOut(100);
			jQuery('.library_thumb_more:visible').fadeOut(100);
		});

		
		jQuery('.library_grid_item, .library_grid_item_import').each(function() {
			var item = jQuery(this),
				gw = item.data('gridwidth'),
				gh = item.data('gridheight'),
				id = item.data('slideid'),
				w = 180;
				
			if (gw==undefined || gw<=0) gw = w;
			if (gh==undefined || gh<=0) gh = w;
			
			var	h = Math.round((w/gw)*gh);
			//item.css({height:h+"px"});
			
			var factor = w/gw;
			
			var htitle = item.closest('.adamlabsgallery_group_wrappers').find('h3');
			if (!htitle.hasClass("modificated")) {
				htitle.html(htitle.html()+" ("+gw+"x"+gh+")").addClass("modificated");
			}			
		});
		
		// CLOSE SLIDE TEMPLATE
		jQuery('#close-template').click(function() {
			jQuery('#adamlabsgallery-libary-wrapper').removeClass("show");
		});		

		// TEMPLATE TAB CHANGE 
		jQuery('body').on("click",'.revolution-templatebutton',function() {
			var btn = jQuery(this);
			jQuery('.adamlabsgallery-library-groups').each(function() { jQuery(this).hide();});
			jQuery("."+btn.data("showgroup")).show();
			jQuery('.revolution-templatebutton').removeClass("selected");
			btn.addClass("selected");
			scrollTA();
			jQuery('.adamlabsgallery-library-groups').perfectScrollbar("update");
		});
		
		setTWHeight();
		jQuery(window).on("resize",setTWHeight);
		jQuery('.adamlabsgallery-library-groups').perfectScrollbar();

		document.addEventListener('ps-scroll-y', function (e) {
			if (jQuery(e.target).closest('.adamlabsgallery-library-groups').length>0) {
				scrollTA();
				jQuery('#library_bigoverlay').css({top:jQuery('.adamlabsgallery-library-groups').scrollTop()});
			}
	    });
		
		jQuery(".input_import_slider").change(function(){
			if(jQuery(this).val() !== ''){
				jQuery('.rs-import-slider-button').show();
			}else{
				jQuery('.rs-import-slider-button').hide();
			}
		});
		
		function setTWHeight() {
			var w = jQuery(window).height(),
				wh = jQuery('#adamlabsgallery_library_header_part').height();
			jQuery('.adamlabsgallery-library-groups').css({height:(w-wh)+"px"});
			jQuery('.adamlabsgallery-library-groups').perfectScrollbar("update");
			scrollTA();
		};

		
		// CLOSE SLIDE TEMPLATE
		jQuery('#adamlabsgallery-close-template').click(function() {
			jQuery('#adamlabsgallery-libary-wrapper').removeClass('show');
		});
		
		
		//LIBRARY ELEMENTS
		jQuery('.adamlabsgallery_library_filter_button').on("click",function() {
			jQuery('#library_bigoverlay').fadeOut(100);
			jQuery('.library_thumb_more:visible').fadeOut(100);
			var btn = jQuery(this),
				sch = btn.data('type');
			jQuery('.adamlabsgallery_library_filter_button').removeClass("selected");
			btn.addClass("selected");
			jQuery('.adamlabsgallery_group_wrappers').hide();
			if (sch=="temp_all") {
				jQuery('.adamlabsgallery_group_wrappers').each(function() {
					var item = jQuery(this);
					item.show();
				});
			} else {				
				jQuery('.'+sch).each(function() {
					var item = jQuery(this);
					if (sch==="template_free") {
						item.hide();
					} else {
						item.show();
					}				
				});
			}
			jQuery('.adamlabsgallery-library-groups').scrollTop(0);
			scrollTA();	
			
		});

		jQuery('body').on("click","span.library_new",function(){
			jQuery("#adamlabsgallery-library-filter-buttons-wrapper .adamlabsgallery_libr_new_udpated").click();
		});
		
		
		
		function scrollTA() {
			var ta = jQuery('#adamlabsgallery-library-grids'),
				st = ta.scrollTop(),
				wh = jQuery(window).height();

			ta.find('.library_item:visible').each(function() {
				var el = jQuery(this),
					rtgt = parseInt(el.closest('#adamlabsgallery-library-grids').offset().top,0);
				
				if (el.data('src')!=undefined && el.data('bgadded')!=1) {
					
					if (jQuery('#adamlabsgallery-libary-wrapper').hasClass("show"))
						if (isElementInViewport(el,st,wh,rtgt)){
							el.css({backgroundImage:'url("'+el.data('src')+'")'});
							el.data('bgadded',1);
						}
				}
			});
		}
		
		
		function isElementInViewport(element,sctop,wh,rtgt) {
			return true;
			var etp = parseInt(element.offset().top,0)-rtgt,
				etpp = parseInt(element.position().top,0),
				inviewport = false;		
			//element.closest('.adamlabsgallery_group_wrappers').find('.template_thumb_title').html("Offset:"+etp+"   Scroll:"+sctop+" POffset:"+rtgt);
			if ((etp>-50) && (etp<wh+50))
				inviewport =  true;
			return inviewport;
		}

	
	}

	/******************************
		-	SANITIZE INPUT	-
	********************************/

	t.sanitize_input = function(raw){
		return raw.toLowerCase().replace(/ /g, '-').replace(/[^-0-9a-z_-]/g,'');
	}

	/**
	Init Slider Spinner Admin View
	**/
	t.initSpinnerAdmin = function() {
		jQuery('#use_spinner_row').parent().prepend('<div id="spinner_preview"></div>');
		var spin = jQuery('#spinner_preview');
		var sel = jQuery('#use_spinner');
		var col = jQuery('#spinner_color');
		var oldcol = col.val();
		t.resetSpin(spin);

		sel.on("change",function() {
			t.resetSpin(spin,true);
		});
		
		/*
		setInterval(function() {
			if (oldcol !=col.val()) {
				t.spinnerColorChange();
				oldocl=col.val();
			}
		},300)
		*/
	}
	/**
	CHANGE SPINNER COLOR ON CALL BACK
	**/
	t.spinnerColorChange = function() {
			
			var spinColor = jQuery('#spinner_color');
			var col = spinColor.attr('data-color') || spinColor.val();
			var prop = 'background';
			
			var sel = jQuery('#use_spinner');
			if (sel.val()==0 || sel.val()==5) {
				col ="#ffffff";
				prop = 'background-color';
			}

			var spin = jQuery('#spinner_preview .adamlabsgallery-loader.adamlabsgallery-demo');
			if (spin.hasClass("spinner0") || spin.hasClass("spinner1") || spin.hasClass("spinner2")) {
				spin.css(prop,col);
			} else {
				spin.find('div').css(prop,col);
			}
	};

	/**
	RESET SPINNER DEMO
	**/
	t.resetSpin = function(spin,remove) {
			var sel = jQuery('#use_spinner');
			spin.find('.adamlabsgallery-loader').remove();
			spin.append('<div class="adamlabsgallery-loader adamlabsgallery-demo">'+
												  		'<div class="dot1"></div>'+
												  	    '<div class="dot2"></div>'+
												  	    '<div class="bounce1"></div>'+
														'<div class="bounce2"></div>'+
														'<div class="bounce3"></div>'+
													 '</div>');
			spin.find('.adamlabsgallery-demo').addClass("spinner"+sel.val());
			if (sel.val()==-1 || sel.val()==0 || sel.val()==5) {
				//jQuery('#spinner_color').val("#ffffff");
				jQuery('#spinner_color_row').css({display:"none"});
			} else {
				jQuery('#spinner_color_row').css({display:"block"});
			}
			t.spinnerColorChange();

	};


	/**
	INITIALISE THE TOOLTIP
	**/
	t.initToolTipser = function() {
		if (es_do_tooltipser)
		jQuery('.adamlabsgallery-tooltip-wrap').not('.tooltipser').tooltipster({
			theme: 'adamlabsgallery-tooltip',
			delay:0,
			ion:"top",
			offsetY:0
		});
	}

	/**
	INITIALISE THE CLEAR STREAM CACHE
	**/
	t.clearStreamCache = function(buttonpressed) {
		current_transient = buttonpressed.parent().find("input");
		current_transient_val = current_transient.val();
		jQuery('input[name=' + buttonpressed.data("clear") + ']').parent().find("input").val("0");
		t.changePreviewGrid(true);
		setTimeout(function(){ jQuery('input[name=' + buttonpressed.data("clear") + ']').val(current_transient_val) }, 500);
	}
}



jQuery.fn.adamlabsortable = function(){
	function disableSelection(sel){
		sel.preventDefault();
	}
    jQuery(this).mousedown(function(e){
		var drag = jQuery(this);
		var posParentTop = drag.parent().offset().top;
		var posParentBottom = posParentTop + drag.parent().height();
		var posOld = drag.offset().top;
		var posOldCorrection = e.pageY - posOld;
        drag.css({'z-index':2});
		var mouseMove = function(e){
			var posNew = e.pageY - posOldCorrection;
			if (posNew < posParentTop){
				drag.offset({'top': posParentTop});
				if (drag.prev().length > 0 ) {
					drag.insertBefore(drag.prev().css({'top':-drag.height()}).animate({'top':0}, 100));
				}
			} else if (posNew + drag.height() > posParentBottom){
				drag.offset({'top': posParentBottom - drag.height()});
				if (drag.next().length > 0 ) {
					drag.insertAfter(drag.next().css({'top':drag.height()}).animate({'top':0}, 100));
                }
			} else {
				drag.offset({'top': posNew});
				if (posOld - posNew > drag.height() - 1){
					drag.insertBefore(drag.prev().css({'top':-drag.height()}).animate({'top':0}, 100));
					drag.css({'top':0});
					posOld = drag.offset().top;
					posNew = e.pageY - posOldCorrection;
					posOldCorrection = e.pageY - posOld;
				} else if (posNew - posOld > drag.height() - 1){
					drag.insertAfter(drag.next().css({'top':drag.height()}).animate({'top':0}, 100));
					drag.css({'top':0});
					posOld = drag.offset().top;
					posNew = e.pageY - posOldCorrection;
					posOldCorrection = e.pageY - posOld;
				}
			}
		};
		var mouseUp = function(){
			jQuery(document).off('mousemove', mouseMove).off('mouseup', mouseUp);
			jQuery(document).off((jQuery.support.selectstart?'selectstart':'mousedown')+'.ui-disableSelection', disableSelection);
            drag.animate({'top':0}, 1, function(){
				drag.css({'z-index':1});
	        });
        };
		jQuery(document).on('mousemove', mouseMove).on('mouseup', mouseUp).on('contextmenu', mouseUp);
		jQuery(document).on((jQuery.support.selectstart?'selectstart':'mousedown')+'.ui-disableSelection', disableSelection);
        jQuery(window).on('blur', mouseUp);
    });
}

jQuery(function() {
	/*! perfect-scrollbar - v0.5.7
	* http://noraesae.github.com/perfect-scrollbar/
	* Copyright (c) 2014 Hyunje Alex Jun; Licensed MIT */
	(function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?e(require("jquery")):e(jQuery)})(function(e){"use strict";function t(e){return"string"==typeof e?parseInt(e,10):~~e}var o={wheelSpeed:1,wheelPropagation:!1,minScrollbarLength:null,maxScrollbarLength:null,useBothWheelAxes:!1,useKeyboard:!0,suppressScrollX:!1,suppressScrollY:!1,scrollXMarginOffset:0,scrollYMarginOffset:0,includePadding:!1},n=0,r=function(){var e=n++;return function(t){var o=".perfect-scrollbar-"+e;return t===void 0?o:t+o}};e.fn.perfectScrollbar=function(n,l){return this.each(function(){function i(e,o){var n=e+o,r=E-W;I=0>n?0:n>r?r:n;var l=t(I*(D-E)/(E-W));S.scrollTop(l)}function a(e,o){var n=e+o,r=x-Y;X=0>n?0:n>r?r:n;var l=t(X*(M-x)/(x-Y));S.scrollLeft(l)}function c(e){return L.minScrollbarLength&&(e=Math.max(e,L.minScrollbarLength)),L.maxScrollbarLength&&(e=Math.min(e,L.maxScrollbarLength)),e}function s(){var e={width:x};e.left=O?S.scrollLeft()+x-M:S.scrollLeft(),B?e.bottom=q-S.scrollTop():e.top=H+S.scrollTop(),A.css(e);var t={top:S.scrollTop(),height:E};z?t.right=O?M-S.scrollLeft()-Q-N.outerWidth():Q-S.scrollLeft():t.left=O?S.scrollLeft()+2*x-M-F-N.outerWidth():F+S.scrollLeft(),_.css(t),K.css({left:X,width:Y-U}),N.css({top:I,height:W-G})}function d(){S.removeClass("ps-active-x"),S.removeClass("ps-active-y"),x=L.includePadding?S.innerWidth():S.width(),E=L.includePadding?S.innerHeight():S.height(),M=S.prop("scrollWidth"),D=S.prop("scrollHeight"),!L.suppressScrollX&&M>x+L.scrollXMarginOffset?(C=!0,Y=c(t(x*x/M)),X=t(S.scrollLeft()*(x-Y)/(M-x))):(C=!1,Y=0,X=0,S.scrollLeft(0)),!L.suppressScrollY&&D>E+L.scrollYMarginOffset?(k=!0,W=c(t(E*E/D)),I=t(S.scrollTop()*(E-W)/(D-E))):(k=!1,W=0,I=0,S.scrollTop(0)),X>=x-Y&&(X=x-Y),I>=E-W&&(I=E-W),s(),C&&S.addClass("ps-active-x"),k&&S.addClass("ps-active-y")}function u(){var t,o,n=!1;K.bind(j("mousedown"),function(e){o=e.pageX,t=K.position().left,A.addClass("in-scrolling"),n=!0,e.stopPropagation(),e.preventDefault()}),e(R).bind(j("mousemove"),function(e){n&&(a(t,e.pageX-o),d(),e.stopPropagation(),e.preventDefault())}),e(R).bind(j("mouseup"),function(){n&&(n=!1,A.removeClass("in-scrolling"))}),t=o=null}function p(){var t,o,n=!1;N.bind(j("mousedown"),function(e){o=e.pageY,t=N.position().top,n=!0,_.addClass("in-scrolling"),e.stopPropagation(),e.preventDefault()}),e(R).bind(j("mousemove"),function(e){n&&(i(t,e.pageY-o),d(),e.stopPropagation(),e.preventDefault())}),e(R).bind(j("mouseup"),function(){n&&(n=!1,_.removeClass("in-scrolling"))}),t=o=null}function f(e,t){var o=S.scrollTop();if(0===e){if(!k)return!1;if(0===o&&t>0||o>=D-E&&0>t)return!L.wheelPropagation}var n=S.scrollLeft();if(0===t){if(!C)return!1;if(0===n&&0>e||n>=M-x&&e>0)return!L.wheelPropagation}return!0}function v(){function e(e){var t=e.originalEvent.deltaX,o=-1*e.originalEvent.deltaY;return(t===void 0||o===void 0)&&(t=-1*e.originalEvent.wheelDeltaX/6,o=e.originalEvent.wheelDeltaY/6),e.originalEvent.deltaMode&&1===e.originalEvent.deltaMode&&(t*=10,o*=10),t!==t&&o!==o&&(t=0,o=e.originalEvent.wheelDelta),[t,o]}function t(t){var n=e(t),r=n[0],l=n[1];o=!1,L.useBothWheelAxes?k&&!C?(l?S.scrollTop(S.scrollTop()-l*L.wheelSpeed):S.scrollTop(S.scrollTop()+r*L.wheelSpeed),o=!0):C&&!k&&(r?S.scrollLeft(S.scrollLeft()+r*L.wheelSpeed):S.scrollLeft(S.scrollLeft()-l*L.wheelSpeed),o=!0):(S.scrollTop(S.scrollTop()-l*L.wheelSpeed),S.scrollLeft(S.scrollLeft()+r*L.wheelSpeed)),d(),o=o||f(r,l),o&&(t.stopPropagation(),t.preventDefault())}var o=!1;window.onwheel!==void 0?S.bind(j("wheel"),t):window.onmousewheel!==void 0&&S.bind(j("mousewheel"),t)}function g(){var t=!1;S.bind(j("mouseenter"),function(){t=!0}),S.bind(j("mouseleave"),function(){t=!1});var o=!1;e(R).bind(j("keydown"),function(n){if((!n.isDefaultPrevented||!n.isDefaultPrevented())&&t){for(var r=document.activeElement?document.activeElement:R.activeElement;r.shadowRoot;)r=r.shadowRoot.activeElement;if(!e(r).is(":input,[contenteditable]")){var l=0,i=0;switch(n.which){case 37:l=-30;break;case 38:i=30;break;case 39:l=30;break;case 40:i=-30;break;case 33:i=90;break;case 32:case 34:i=-90;break;case 35:i=n.ctrlKey?-D:-E;break;case 36:i=n.ctrlKey?S.scrollTop():E;break;default:return}S.scrollTop(S.scrollTop()-i),S.scrollLeft(S.scrollLeft()+l),o=f(l,i),o&&n.preventDefault()}}})}function b(){function e(e){e.stopPropagation()}N.bind(j("click"),e),_.bind(j("click"),function(e){var o=t(W/2),n=e.pageY-_.offset().top-o,r=E-W,l=n/r;0>l?l=0:l>1&&(l=1),S.scrollTop((D-E)*l)}),K.bind(j("click"),e),A.bind(j("click"),function(e){var o=t(Y/2),n=e.pageX-A.offset().left-o,r=x-Y,l=n/r;0>l?l=0:l>1&&(l=1),S.scrollLeft((M-x)*l)})}function h(){function t(){var e=window.getSelection?window.getSelection():document.getSlection?document.getSlection():{rangeCount:0};return 0===e.rangeCount?null:e.getRangeAt(0).commonAncestorContainer}function o(){r||(r=setInterval(function(){return P()?(S.scrollTop(S.scrollTop()+l.top),S.scrollLeft(S.scrollLeft()+l.left),d(),void 0):(clearInterval(r),void 0)},50))}function n(){r&&(clearInterval(r),r=null),A.removeClass("in-scrolling"),_.removeClass("in-scrolling")}var r=null,l={top:0,left:0},i=!1;e(R).bind(j("selectionchange"),function(){e.contains(S[0],t())?i=!0:(i=!1,n())}),e(window).bind(j("mouseup"),function(){i&&(i=!1,n())}),e(window).bind(j("mousemove"),function(e){if(i){var t={x:e.pageX,y:e.pageY},r=S.offset(),a={left:r.left,right:r.left+S.outerWidth(),top:r.top,bottom:r.top+S.outerHeight()};t.x<a.left+3?(l.left=-5,A.addClass("in-scrolling")):t.x>a.right-3?(l.left=5,A.addClass("in-scrolling")):l.left=0,t.y<a.top+3?(l.top=5>a.top+3-t.y?-5:-20,_.addClass("in-scrolling")):t.y>a.bottom-3?(l.top=5>t.y-a.bottom+3?5:20,_.addClass("in-scrolling")):l.top=0,0===l.top&&0===l.left?n():o()}})}function w(t,o){function n(e,t){S.scrollTop(S.scrollTop()-t),S.scrollLeft(S.scrollLeft()-e),d()}function r(){b=!0}function l(){b=!1}function i(e){return e.originalEvent.targetTouches?e.originalEvent.targetTouches[0]:e.originalEvent}function a(e){var t=e.originalEvent;return t.targetTouches&&1===t.targetTouches.length?!0:t.pointerType&&"mouse"!==t.pointerType&&t.pointerType!==t.MSPOINTER_TYPE_MOUSE?!0:!1}function c(e){if(a(e)){h=!0;var t=i(e);p.pageX=t.pageX,p.pageY=t.pageY,f=(new Date).getTime(),null!==g&&clearInterval(g),e.stopPropagation()}}function s(e){if(!b&&h&&a(e)){var t=i(e),o={pageX:t.pageX,pageY:t.pageY},r=o.pageX-p.pageX,l=o.pageY-p.pageY;n(r,l),p=o;var c=(new Date).getTime(),s=c-f;s>0&&(v.x=r/s,v.y=l/s,f=c),e.stopPropagation(),e.preventDefault()}}function u(){!b&&h&&(h=!1,clearInterval(g),g=setInterval(function(){return P()?.01>Math.abs(v.x)&&.01>Math.abs(v.y)?(clearInterval(g),void 0):(n(30*v.x,30*v.y),v.x*=.8,v.y*=.8,void 0):(clearInterval(g),void 0)},10))}var p={},f=0,v={},g=null,b=!1,h=!1;t&&(e(window).bind(j("touchstart"),r),e(window).bind(j("touchend"),l),S.bind(j("touchstart"),c),S.bind(j("touchmove"),s),S.bind(j("touchend"),u)),o&&(window.PointerEvent?(e(window).bind(j("pointerdown"),r),e(window).bind(j("pointerup"),l),S.bind(j("pointerdown"),c),S.bind(j("pointermove"),s),S.bind(j("pointerup"),u)):window.MSPointerEvent&&(e(window).bind(j("MSPointerDown"),r),e(window).bind(j("MSPointerUp"),l),S.bind(j("MSPointerDown"),c),S.bind(j("MSPointerMove"),s),S.bind(j("MSPointerUp"),u)))}function m(){S.bind(j("scroll"),function(){d()})}function T(){S.unbind(j()),e(window).unbind(j()),e(R).unbind(j()),S.data("perfect-scrollbar",null),S.data("perfect-scrollbar-update",null),S.data("perfect-scrollbar-destroy",null),K.remove(),N.remove(),A.remove(),_.remove(),S=A=_=K=N=C=k=x=E=M=D=Y=X=q=B=H=W=I=Q=z=F=O=j=null}function y(){d(),m(),u(),p(),b(),h(),v(),(J||V)&&w(J,V),L.useKeyboard&&g(),S.data("perfect-scrollbar",S),S.data("perfect-scrollbar-update",d),S.data("perfect-scrollbar-destroy",T)}var L=e.extend(!0,{},o),S=e(this),P=function(){return!!S};if("object"==typeof n?e.extend(!0,L,n):l=n,"update"===l)return S.data("perfect-scrollbar-update")&&S.data("perfect-scrollbar-update")(),S;if("destroy"===l)return S.data("perfect-scrollbar-destroy")&&S.data("perfect-scrollbar-destroy")(),S;if(S.data("perfect-scrollbar"))return S.data("perfect-scrollbar");S.addClass("ps-container");var x,E,M,D,C,Y,X,k,W,I,O="rtl"===S.css("direction"),j=r(),R=this.ownerDocument||document,A=e("<div class='ps-scrollbar-x-rail'>").appendTo(S),K=e("<div class='ps-scrollbar-x'>").appendTo(A),q=t(A.css("bottom")),B=q===q,H=B?null:t(A.css("top")),U=t(A.css("borderLeftWidth"))+t(A.css("borderRightWidth")),_=e("<div class='ps-scrollbar-y-rail'>").appendTo(S),N=e("<div class='ps-scrollbar-y'>").appendTo(_),Q=t(_.css("right")),z=Q===Q,F=z?null:t(_.css("left")),G=t(_.css("borderTopWidth"))+t(_.css("borderBottomWidth")),J="ontouchstart"in window||window.DocumentTouch&&document instanceof window.DocumentTouch,V=null!==window.navigator.msMaxTouchPoints;return y(),S})}});
});
