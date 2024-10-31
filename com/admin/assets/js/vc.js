window.VcEssentialGrid = vc.shortcode_view.extend({
	render: function () {
		jQuery('#adamlabsgallery-create-wp-gallery').hide();
		cur_vc_obj = this;
		var params = this.model.get('params');
		
		if(vc.add_element_block_view.$el.is(':visible')){ //hack to check if we just loaded the page or if we rendered it because of adding a new Portfolio Gallery element
			adamlabsgallery_vc_show_overlay(params);
		}
		return window.VcEssentialGrid.__super__.render.call(this);
	},
	editElement: function() {
		cur_vc_obj = this;
		var params = this.model.get('params');
		
		adamlabsgallery_vc_show_overlay(params);
		return false;
	}
});

if(typeof(window.InlineShortcodeView) !== 'undefined'){
	var show_frontend_overlay = false;
	
	jQuery(window).on('vc_build',function(){
		vc.add_element_block_view.$el.find('[data-element="adamlabsgallery"]').click(function(){ show_frontend_overlay = true; });
	});
	
	window.InlineShortcodeView_adamlabsgallery = window.InlineShortcodeView.extend({
		render: function() {
			
			cur_vc_obj = this;
			var params = this.model.get('params');
			
			if(show_frontend_overlay){
				adamlabsgallery_vc_show_overlay(params);
			}
			
			window.InlineShortcodeView_adamlabsgallery.__super__.render.call(this);
			
			return this;
			
		},
		update: function() {
		
			show_frontend_overlay = false;
			
			// same function as backend changedShortcodeParams
			window.InlineShortcodeView_adamlabsgallery.__super__.update.call(this);
			
			return this;
		},
		edit: function( e ) {
			cur_vc_obj = this;
			var params = this.model.get('params');
			
			adamlabsgallery_vc_show_overlay(params);
			
			return false;
		}
	});
}

function adamlabsgallery_vc_show_overlay(params){

	if ( cur_vc_obj !== false && cur_vc_obj.model.get('cloned') === true){
		//set cloned to false, so that the edit button will work. Then return as this is at the process where the element gets cloned
		cur_vc_obj.model.save('cloned', false);
		
		return; //do not show edit if we cloned
	}
	
	adamlabsgallery_tiny_reset_all();
	
	adamlabsgallery_is_vc = true; //set for the saving that we are visual composer

	jQuery('.wpb-element-edit-modal').hide(); //hide the normal VC window and use own (old vc version)
	jQuery('#vc_properties-panel').hide(); //hide the normal VC window and use own (new vc version)
	
	var adamlabsgallery_vc_variables = {};
	
	adamlabsgallery_vc_variables['alias'] = (typeof(params.alias) !== 'undefined') ? params.alias : '';
	adamlabsgallery_vc_variables['settings'] = (typeof(params.settings) !== 'undefined') ? params.settings.replace(/\'/g, '"') : '';
	adamlabsgallery_vc_variables['layers'] = (typeof(params.layers) !== 'undefined') ? params.layers.replace(/\'/g, '"') : '';
	adamlabsgallery_vc_variables['special'] = (typeof(params.special) !== 'undefined') ? params.special : '';
	
	jQuery('#adamlabsgallery-tiny-dialog-step-1').show();
	jQuery('#adamlabsgallery-tiny-dialog-step-2').hide();
	jQuery('#adamlabsgallery-tiny-dialog-step-3').hide();
	
	jQuery('#adamlabsgallery-tiny-mce-dialog').dialog({
		id       : 'adamlabsgallery-tiny-mce-dialog',
		title	 : adamlabsgallery_lang.shortcode_generator,
		width    : 720,
		height   : 'auto'
	});
	
	if(adamlabsgallery_vc_variables['special'] !== ''){ //special
		
		adamlabsgallery_create_by_predefined = adamlabsgallery_vc_variables['special'];
		
		//special stuff here
		if(adamlabsgallery_vc_variables['alias'] !== ''){
			jQuery('select[name="adamlabsgallery-tiny-existing-settings"] option').each(function(){
				if(jQuery(this).val() == adamlabsgallery_vc_variables['alias']) jQuery(this).attr('selected', true);
			});
			
			if(adamlabsgallery_vc_variables['settings'] !== ''){
				var sett = jQuery.parseJSON(adamlabsgallery_vc_variables['settings']);
				
				if(typeof(sett['max-entries']) !== 'undefined')
					jQuery('input[name="adamlabsgallery-tiny-max-entries"]').val(sett['max-entries']);
			}
		}
		
		jQuery('#adamlabsgallery-goto-step-2').click();
		
	}else if(adamlabsgallery_vc_variables['layers'] != '' && adamlabsgallery_vc_variables['settings'] != '' || adamlabsgallery_vc_variables['layers'] != '' && adamlabsgallery_vc_variables['alias'] != ''){
		
		var ess_shortcode = '[adamlabsgallery ';
		
		if(adamlabsgallery_vc_variables['alias'] !== '')
			ess_shortcode += ' alias="'+adamlabsgallery_vc_variables['alias']+'"';
			
		if(adamlabsgallery_vc_variables['settings'] !== '')
			ess_shortcode += " settings='"+adamlabsgallery_vc_variables['settings']+"'";
			
		if(adamlabsgallery_vc_variables['layers'] !== '')
			ess_shortcode += " layers='"+adamlabsgallery_vc_variables['layers']+"'";
			
		ess_shortcode += '][/adamlabsgallery]';
		
		jQuery('input[name="adamlabsgallery-shortcode-analyzer"]').val(ess_shortcode);
		jQuery('#adamlabsgallery-shortcode-do-analyze').click();
		
	}else if(adamlabsgallery_vc_variables['alias'] !== '' && adamlabsgallery_vc_variables['special'] == ''){ //only grid with alias
		
		jQuery('select[name="adamlabsgallery-existing-grid"] option').each(function(){
			if(jQuery(this).val() == adamlabsgallery_vc_variables['alias']){
				jQuery(this).attr('selected', true);
			}
		});
		
	}else{ /*seems like a new grid  */ }
}