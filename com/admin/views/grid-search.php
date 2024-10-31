<?php
/**
 * Panel to the search options.
 */

if( !defined( 'ABSPATH') ) exit();

$settings = get_option('adamlabsgallery-search-settings', array('settings' => array(), 'global' => array(), 'shortcode' => array()));
$settings = AdamLabsGallery_Base::stripslashes_deep($settings);

$base = new AdamLabsGallery_Base();
$grids = AdamLabsGallery::get_grids_short();

$my_skins = array(
	'light' => __('Light', ADAMLABS_GALLERY_TEXTDOMAIN),
	'dark' => __('Dark', ADAMLABS_GALLERY_TEXTDOMAIN)
);
$my_skins = apply_filters('adamlabsgallery_modify_search_skins', $my_skins);

?>
<h2 class="topheader"><?php _e('Search Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h2>

<div id="adamlabsgallery-grid-search-wrapper">
	<ul class="adamlabsgallery-gridsearch-tabs">
		<li><a href="#adamlabsgallery-search-settings-wrap"><?php _e('Global Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
		<li><a href="#adamlabsgallery-shortcode-search-wrap"><?php _e('ShortCode Search', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
	</ul>
	
	<div id="adamlabsgallery-search-settings-wrap">
		<p>
			<?php $search_enable = $base->getVar(@$settings['settings'], 'search-enable', 'off'); ?>
			<label for="search-enable"><?php _e('Enable Search Globally', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<input type="radio" name="search-enable" value="on" <?php checked($search_enable, 'on'); ?> /> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
			<input type="radio" name="search-enable" value="off" <?php checked($search_enable, 'off'); ?> /> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
		</p>
		<form id="adamlabsgallery-search-global-settings">
			<div class="adamlabsgallery-search-settings-info-box" style="padding:10px 15px; margin-bottom:15px;background:#3498db; color:#fff;">
				<h3 style="color:#fff"><div style="margin-right:15px;" class="dashicons dashicons-info"></div><?php _e('What Are The Search Settings?', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div style="float:right" class="dashicons dashicons-arrow-down-alt2"></div></h3>
				<div class="adamlabsgallery-search-settings-toggle-visible">
					<p><?php _e('With this, you can let any element in your theme use Portfolio Gallery as a Search Result page.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
					<h3 style="color:#fff"><?php _e('Note:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
					<p style="margin-bottom:15px"><?php _e('You can add more than one Setting to have more than one resulting Grid Style depending on the element that opened the search overlay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				</div>
			</div>
			
			<div class="adamlabsgallery-global-search-wrap">
			</div>
			
			<a id="adamlabsgallery-btn-add-global-setting" href="javascript:void(0);" class="button-primary"><i class="adamlabsgallery-icon-plus"></i><?php _e('Add Setting', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
			
		</form>
	</div>
	<div id="adamlabsgallery-shortcode-search-wrap">
		<form id="adamlabsgallery-search-shortcode-settings">
			<div class="adamlabsgallery-search-settings-info-box" style="padding:10px 15px; margin-bottom:15px;background:#3498db; color:#fff;">
				<h3 style="color:#fff"><div style="margin-right:15px;" class="dashicons dashicons-info"></div><?php _e('What Are The Search ShortCode Settings?', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div style="float:right" class="dashicons dashicons-arrow-down-alt2"></div></h3>
				<div class="adamlabsgallery-search-settings-toggle-visible">
					<p><?php _e('With this, you can create a ShortCode with custom HTML markup that can be used anywhere on the website to use the search functionality of Portfolio Gallery.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
					<h3 style="color:#fff"><?php _e('Note:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
					<p><?php _e('- adding HTML will add the onclick event in the first found tag', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
					<p style="margin-bottom:15px"><?php _e('- adding text will wrap an a tag around it that will have the onclick event', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				</div>
			</div>
			
			<div class="adamlabsgallery-shortcode-search-wrap">
			</div>
			
			<a id="adamlabsgallery-btn-add-shortcode-setting" href="javascript:void(0);" class="button-primary"><i class="adamlabsgallery-icon-plus"></i><?php _e('Add ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
		</form>
	</div>
	
	<p>
		<a id="adamlabsgallery-btn-save-settings" href="javascript:void(0);" class="button-primary revgreen"><i class="adamlabsgallery-icon-cog"></i><?php _e('Save Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
	</p>
</div>

<script type="text/javascript">
	var global_settings = <?php echo json_encode($settings); ?>;
	jQuery(function(){
		AdminEssentials.initSearchSettings();
	});
</script>

<script type="text/html" id="tmpl-adamlabsgallery-global-settings-wrap">
	<div class="postbox adamlabsgallery-postbox" style="width:100%;min-width:500px">
		<h3 class="box-closed"><span style="font-weight:400"><?php _e('Selector:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span class="search-title">{{ data['search-class'] }} </span><div class="postbox-arrow"></div></h3>
		<div class="inside" style="display:none;padding:15px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
			<p>
				<label for="search-class"><?php _e('Set by Class/ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<input type="text" name="search-class[]" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Add CSS ID or Class here to trigger search as an onclick event on given elements (can be combined like \'.search, .search2, #search\')', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="{{ data['search-class'] }}"  />
			</p>
			<p>
				
				<label for="search-grid-id"><?php _e('Choose Grid To Use', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<select name="search-grid-id[]">
					<?php
					if(!empty($grids)){
						foreach($grids as $id => $name){
							echo '<option value="'.$id.'" <# if ( \''.$id.'\' == data[\'search-grid-id\'] ) { #>selected="selected"<# } #>>'.$name.'</option>'."\n";
						}
					}
					?>
				</select>
			</p>
			<p>
				<label for="search-style"><?php _e('Overlay Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<select name="search-style[]">
					<?php
					foreach($my_skins as $handle => $name){
						echo '<option value="'.$handle.'" <# if ( \''.$handle.'\' == data[\'search-style\'] ) { #>selected="selected"<# } #>>'.$name.'</option>'."\n";
					}
					?>
				</select>
			</p>
			
			<?php add_action('adamlabsgallery_add_search_global_settings', (object)$settings); ?>
			
			<p>
				<a href="javascript:void(0);" class="button-primary revred adamlabsgallery-btn-remove-setting"><i class="adamlabsgallery-icon-trash"></i><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
			</p>
		</div>
	</div>
</script>


<script type="text/html" id="tmpl-adamlabsgallery-shortcode-settings-wrap">
	<div class="postbox adamlabsgallery-postbox" style="width:100%;min-width:500px">
		<h3 class="box-closed"><span style="font-weight:400"><?php _e('ShortCode:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span class="search-title">{{ data['sc-shortcode'] }} </span><div class="postbox-arrow"></div></h3>
		<div class="inside" style="display:none;padding:15px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
			<p>
				<label for="sc-handle"><?php _e('Handle', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<input type="text" value="{{ data['sc-handle'] }}" name="sc-handle[]" />
			</p>
			<p>
				<label for="sc-grid-id"><?php _e('Choose Grid To Use', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<select name="sc-grid-id[]">
					<?php
					if(!empty($grids)){
						foreach($grids as $id => $name){
							echo '<option value="'.$id.'" <# if ( \''.$id.'\' == data[\'sc-grid-id\'] ) { #>selected="selected"<# } #>>'.$name.'</option>'."\n";
						}
					}
					?>
				</select>
			</p>
			<p>
				<label for="sc-style"><?php _e('Overlay Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<select name="sc-style[]">
					<?php
					foreach($my_skins as $handle => $name){
						echo '<option value="'.$handle.'" <# if ( \''.$handle.'\' == data[\'sc-style\'] ) { #>selected="selected"<# } #>>'.$name.'</option>'."\n";
					}
					?>
				</select>
			</p>
			<p>
				<label for="sc-html"><?php _e('HTML Markup', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<textarea name="sc-html[]">{{ data['sc-html'] }}</textarea>
			</p>
			<p>
				<label for="sc-shortcode"><?php _e('Generated ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
				<input type="text" value="" name="sc-shortcode[]" readonly="readonly" style="width: 400px;" />
			</p>
			
			<?php add_action('adamlabsgallery_add_search_shortcode_settings', (object)$settings); ?>
			
			<p>
				<a href="javascript:void(0);" class="button-primary revred adamlabsgallery-btn-remove-setting"><i class="adamlabsgallery-icon-trash"></i><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
			</p>
		</div>
	</div>
</script>