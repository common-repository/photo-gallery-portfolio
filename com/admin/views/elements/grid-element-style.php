<?php
 
if( !defined( 'ABSPATH') ) exit();

wp_enqueue_script($this->plugin_slug . '-adamlabsgallery-script', ADAMLABS_GALLERY_PLUGIN_URL.'com/public/assets/js/jquery.adamlabs.adamlabsgallery.min.js', array('jquery'), AdamLabsGallery::VERSION );

wp_enqueue_style($this->plugin_slug .'-admin-settings-styles', ADAMLABS_GALLERY_PLUGIN_URL.'com/public/assets/css/settings.css', array(), AdamLabsGallery::VERSION );
?>

<div id="adamlabsgallery-element-settings-wrap">
	 <form id="">
        <div class="postbox adamlabsgallery-postbox"><h3><span><?php _e('Element Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><div class="postbox-arrow"></div></h3>
            <div class="inside padding-10">
            	<div id="adamlabsgallery-element-settings-tabs">
					 <ul>
						<li><a href="#adamlabsgallery-element-source"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
						<li><a href="#adamlabsgallery-element-style"><?php _e('Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
						<li><a href="#adamlabsgallery-element-animation"><?php _e('Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
					</ul>
					<!-- THE ELEMENT SOURCE SETTING -->
					<div id="adamlabsgallery-element-source">
						<div id="dz-source" data-sort="5">
							 <p>
								<label><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<select name="element-source" style="width:180px">
									<?php
									foreach($element_type as $el_cat => $el_type){
										?>
										<option value="<?php echo $el_cat; ?>"><?php echo ucwords($el_cat); ?></option>
										<?php
									}
									?>
								</select>
							 </p>
							 <p>
								<label><?php _e('Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<?php
								foreach($element_type as $el_cat => $el_type){
									?>
									<select name="element-source-<?php echo $el_cat; ?>" style="width:180px" class="elements-select-wrap">
										<?php
										foreach($el_type as $ty_name => $ty_values){
											?><option value="<?php echo $ty_name; ?>"><?php echo $ty_values['name']; ?></option><?php
										}
										?>
									</select>
									<?php
								}
								?>
							 </p>
							</div>
					</div>
					
					<!-- THE ELEMENT STYLE SETTINGS -->
					<div id="adamlabsgallery-element-style">
						<p id="dz-float" data-sort="10">
					<label><?php _e('Float Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<input class="input-settings-small element-setting firstinput" type="checkbox" name="element-float" />
				</p>
				<p id="dz-font-size" data-sort="20">
					<label><?php _e('Font Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<span id="element-font-size" class="slider-settings"></span>
					<input class="input-settings-small element-setting" type="text" name="element-font-size" value="6" /> px
				</p>
				<p id="dz-background-color" data-sort="30">
					<label><?php _e('Background Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<input class="element-setting" name="element-background-color" type="text" id="element-background-color" value="" data-default-color="#ffffff">
				</p>
				<p id="dz-padding" data-sort="40">
					<label><?php _e('Paddings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<span id="element-padding" class="slider-settings"></span>
					<input class="input-settings-small element-setting" type="text" name="element-padding" value="0" /> px
				</p>
				<p id="dz-margin" data-sort="60">
					<label><?php _e('Margin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<span id="element-margin" class="slider-settings"></span>
					<input class="input-settings-small element-setting" type="text" name="element-margin" value="0" /> px
				</p>
				<p id="dz-border" data-sort="70">
					<label><?php _e('Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<span id="element-border" class="slider-settings"></span>
					<input class="input-settings-small element-setting" type="text" name="element-border" value="0" /> px
				</p>
				<p id="dz-height" data-sort="80">
					<label><?php _e('Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<span id="element-height" class="slider-settings"></span>
					<input class="input-settings-small element-setting" type="text" name="element-height" value="0" /> px
				</p>
				<p id="dz-hideunder" data-sort="90">
					<label><?php _e('Hide Under Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
					<input class="input-settings-small element-setting firstinput" type="text" name="element-hideunder" value="0" /> px
				</p>
				
				<p id="dz-shadow" data-sort="100">
					<label><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				</p>
			
					</div>
					
					<!-- THE ELEMENT ANIMATION SETTINGS -->
					<div id="adamlabsgallery-element-animation">
						<p id="dz-delay" data-sort="50">
							<label><?php _e('Delay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="element-delay" class="slider-settings"></span>
							<input class="input-settings-small element-setting" type="text" name="element-delay" value="0" />
						</p>
						<p id="dz-transition" data-sort="90">
							<label><?php _e('Transition', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select name="element-transition">
								<?php
								foreach($transitions as $handle => $name){
									?>
									<option value="<?php echo $handle; ?>"><?php echo $name; ?></option>
									<?php
								}
								?>
							</select>
						</p>
					</div>
				</div>
				
					<p id="dz-delete" data-sort="9999">
					<a id="element-delete-button" class="button-primary" href="javascript:void(0);"><i class="adamlabsgallery-icon-trash"></i> <?php _e('Delete', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
					<a id="element-save-as-button" class="button-primary" href="javascript:void(0);"><i class="adamlabsgallery-icon-save"></i> <?php _e('Save', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
				<p>
            </div>
        </div>
    </form>
</div>