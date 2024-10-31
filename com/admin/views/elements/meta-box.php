<?php
/**
 * Represents the view for the metabox in post / pages
 */

if( !defined( 'ABSPATH') ) exit();

if(!isset($post)) return false; //not called as it should be

$base = new AdamLabsGallery_Base();
$item_skin = new AdamLabsGallery_Item_Skin();
$item_elements = new AdamLabsGallery_Item_Element();
$meta = new AdamLabsGallery_Meta();

$values = get_post_custom($post->ID);

$adamlabsgallery_sources_html5_mp4 = isset($values['adamlabsgallery_sources_html5_mp4']) ? esc_attr($values['adamlabsgallery_sources_html5_mp4'][0]) : "";
$adamlabsgallery_sources_html5_ogv = isset($values['adamlabsgallery_sources_html5_ogv']) ? esc_attr($values['adamlabsgallery_sources_html5_ogv'][0]) : "";
$adamlabsgallery_sources_html5_webm = isset($values['adamlabsgallery_sources_html5_webm']) ? esc_attr($values['adamlabsgallery_sources_html5_webm'][0]) : "";
$adamlabsgallery_vimeo_ratio = isset($values['adamlabsgallery_vimeo_ratio']) ? esc_attr($values['adamlabsgallery_vimeo_ratio'][0]) : "1";
$adamlabsgallery_youtube_ratio = isset($values['adamlabsgallery_youtube_ratio']) ? esc_attr($values['adamlabsgallery_youtube_ratio'][0]) : "1";
$adamlabsgallery_wistia_ratio = isset($values['adamlabsgallery_wistia_ratio']) ? esc_attr($values['adamlabsgallery_wistia_ratio'][0]) : "1";
$adamlabsgallery_html5_ratio = isset($values['adamlabsgallery_html5_ratio']) ? esc_attr($values['adamlabsgallery_html5_ratio'][0]) : "1";
$adamlabsgallery_soundcloud_ratio = isset($values['adamlabsgallery_soundcloud_ratio']) ? esc_attr($values['adamlabsgallery_soundcloud_ratio'][0]) : "1";
$adamlabsgallery_sources_youtube = isset($values['adamlabsgallery_sources_youtube']) ? esc_attr($values['adamlabsgallery_sources_youtube'][0]) : "";
$adamlabsgallery_sources_wistia = isset($values['adamlabsgallery_sources_wistia']) ? esc_attr($values['adamlabsgallery_sources_wistia'][0]) : "";
$adamlabsgallery_sources_vimeo = isset($values['adamlabsgallery_sources_vimeo']) ? esc_attr($values['adamlabsgallery_sources_vimeo'][0]) : "";
$adamlabsgallery_sources_image = isset($values['adamlabsgallery_sources_image']) ? esc_attr($values['adamlabsgallery_sources_image'][0]) : "";
$adamlabsgallery_sources_iframe = isset($values['adamlabsgallery_sources_iframe']) ? esc_attr($values['adamlabsgallery_sources_iframe'][0]) : "";
$adamlabsgallery_sources_soundcloud = isset($values['adamlabsgallery_sources_soundcloud']) ? esc_attr($values['adamlabsgallery_sources_soundcloud'][0]) : "";
$adamlabsgallery_sources_adamlabsgallery = isset($values['adamlabsgallery_sources_adamlabsgallery']) ? esc_attr($values['adamlabsgallery_sources_adamlabsgallery'][0]) : "";

$adamlabsgallery_featured_grid = isset($values['adamlabsgallery_featured_grid']) ? esc_attr($values['adamlabsgallery_featured_grid'][0]) : "";

$adamlabsgallery_image_fit = isset($values['adamlabsgallery_image_fit']) ? esc_attr($values['adamlabsgallery_image_fit'][0]) : "";
$adamlabsgallery_image_align_h = isset($values['adamlabsgallery_image_align_h']) ? esc_attr($values['adamlabsgallery_image_align_h'][0]) : "";
$adamlabsgallery_image_align_v = isset($values['adamlabsgallery_image_align_v']) ? esc_attr($values['adamlabsgallery_image_align_v'][0]) : "";
$adamlabsgallery_image_repeat = isset($values['adamlabsgallery_image_repeat']) ? esc_attr($values['adamlabsgallery_image_repeat'][0]) : "";

$adamlabsgallery_sources_image_url = '';
if(intval($adamlabsgallery_sources_image) > 0){
	//get URL to Image
	$img = wp_get_attachment_image_src($adamlabsgallery_sources_image, 'full');
	if($img !== false){
		$adamlabsgallery_sources_image_url = $img[0];
	}else{
		$adamlabsgallery_sources_image = '';
	}
}

$adamlabsgallery_settings_custom_meta_skin = isset($values['adamlabsgallery_settings_custom_meta_skin']) ? unserialize($values['adamlabsgallery_settings_custom_meta_skin'][0]) : "";
$adamlabsgallery_settings_custom_meta_element = isset($values['adamlabsgallery_settings_custom_meta_element']) ? unserialize($values['adamlabsgallery_settings_custom_meta_element'][0]) : "";
$adamlabsgallery_settings_custom_meta_setting = isset($values['adamlabsgallery_settings_custom_meta_setting']) ? unserialize($values['adamlabsgallery_settings_custom_meta_setting'][0]) : "";
$adamlabsgallery_settings_custom_meta_style = isset($values['adamlabsgallery_settings_custom_meta_style']) ? unserialize($values['adamlabsgallery_settings_custom_meta_style'][0]) : "";

//if(!isset($disable_advanced) || $disable_advanced == false){
	$adamlabsgallery_meta = array();
	
	if(!empty($adamlabsgallery_settings_custom_meta_skin)){
		foreach($adamlabsgallery_settings_custom_meta_skin as $key => $val){
			$adamlabsgallery_meta[$key]['skin'] = @$val;
			$adamlabsgallery_meta[$key]['element'] = @$adamlabsgallery_settings_custom_meta_element[$key];
			$adamlabsgallery_meta[$key]['setting'] = @$adamlabsgallery_settings_custom_meta_setting[$key];
			$adamlabsgallery_meta[$key]['style'] = @$adamlabsgallery_settings_custom_meta_style[$key];
		}
	}
	
	$advanced = array();
	
	$adamlabsgallery_skins = $item_skin->get_adamlabsgallery_item_skins();
	
	foreach($adamlabsgallery_skins as $skin){
		if(!empty($skin['layers'])){
			$advanced[$skin['id']]['name'] = $skin['name'];
			$advanced[$skin['id']]['handle'] = $skin['handle'];
			foreach($skin['layers'] as $layer){
				if(empty($layer)) continue; //some layers may be NULL...
				
				//check if special, ignore special elements
				$settings = $layer['settings'];
				if(!empty($settings) && isset($settings['special']) && $settings['special'] == 'true') continue;
				
				/* 2.1.6 */
				if(isset($layer['id'])) $advanced[$skin['id']]['layers'][] = $layer['id'];
			}
		}
	}

	$eg_elements = $item_elements->get_allowed_meta();
	
//}

$custom_meta = $meta->get_all_meta(false);

if(isset($disable_advanced) && $disable_advanced == true){ //only show if we are in preview mode
	?>
	<form id="adamlabsgallery-form-post-meta-settings">
		<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
	<?php
}

wp_nonce_field('adamlabsgallery_meta_box_nonce', 'adamlabsgallery_meta_box_nonce');

?>

<style type="text/css">	
	/******************************
		-	META BOX STYLING	-
	********************************/
	#adamlabsgallery-meta-box input			{	background:#f1f1f1; box-shadow: none; -webkit-box-shadow: none; }
	#adamlabsgallery-meta-box .adamlabsgallery-mb-label 	{	min-width:130px; margin-right:20px; display:inline-block;}
	#adamlabsgallery-custommeta-options .adamlabsgallery-mb-label	{	min-width: 150px;}
	#adamlabsgallery-custommeta-options input[type="text"]	{	min-width:220px;}
	#adamlabsgallery-meta-box h2				{	font-size:18px;background:#f1f1f1; margin-left:-12px;margin-right:-12px; padding:5px 10px; margin-bottom:30px; line-height:29px;}
	
	#adamlabsgallery-meta-box .adamlabsgallery-remove-custom-meta-field	{	padding: 0px 12px; }
	
	#adamlabsgallery-meta-box .adamlabsgallery-custom-meta-style,
	#adamlabsgallery-meta-box .wp-picker-container		 	  {	line-height: 20px;vertical-align: middle; }
	
	#adamlabsgallery-meta-box .wp-picker-container .wp-color-result	{	margin:0px;}
	#adamlabsgallery-meta-box .adamlabsgallery-custom-meta-setting-wrap {	line-height: 45px}
	
	#adamlabsgallery-meta-box .adamlabsgallery-cs-row			{	height:45px;}
/*	#adamlabsgallery-meta-box .adamlabsgallery-cs-row-min		{	min-height:45px;}		*/
	
	#adamlabsgallery-meta-box hr	{	border-top: 1px solid #f1f1f1;}
	
	#adamlabsgallery-meta-box .adamlabsgallery-notifcation	{	background:#f1f1f1; padding:10px 15px;   font-style: italic; box-sizing:border-box;
										-moz-box-sizing:border-box; line-height:20px; margin-top:10px;
										-webkit-box-sizing:border-box; 
									}
	
	#adamlabsgallery-meta-box h3					{	padding:10px 10px; background:#e74c3c; color:#fff;}
	#adamlabsgallery-meta-box h3 span:before		{	font-family:dashicons;content:"\f180"; font-size:21px; vertical-align: middle; line-height:22px; margin-right:5px;}
	
	#adamlabsgallery-meta-box .handlediv:before	{	padding:11px 10px; color:#fff;}
	
	#adamlabsgallery-custommeta-options .adamlabsgallery-cs-row-min					{	padding:10px 12px; margin:0px -12px; }
	#adamlabsgallery-custommeta-options .adamlabsgallery-cs-row-min:nth-child(odd)	{	background:#f5f5f5; }
	
	#adamlabsgallery-custommeta-options .adamlabsgallery-cs-row-min img {	max-width:100%; margin-top:15px;	}
	#adamlabsgallery-custommeta-options select	{	min-width:223px;}
	/****************************
	* Custom Button Styles
	****************************/
	
	#adamlabsgallery-meta-box .button-primary,
	#button_upload_plugin		{	border:none !important; text-shadow: none !important; border: none !important; outline: none !important;box-shadow: none !important;
											line-height: 26px !important; height: 27px !important; margin:2px 3px 2px 0px!important;color:#fff !important;
											background:transparent !important; vertical-align: middle;
										}
	
	/* 2.1.6 */
	.rev-colorpickerspan {display: inline-block; line-height: 0; vertical-align: middle; margin-left: 1px}
	
	#adamlabsgallery-meta-box .button-primary.button-fixed
								{	height: auto !important;}
	
	.multiple_text_add			{	text-decoration: none !important}
	.egwithhover,
	.egwithhover:link,
	.egwithhover:visited		{	color:#27ae60; font-size:13px; text-decoration: none !important;}
	.egwithhover:hover			{	color:#2ecc71; }

	
	#button_upload_plugin.revgreen,
	#adamlabsgallery-meta-box .revgreen,
	.revgreen								{	background:#27ae60 !important}
	
	#button_upload_plugin.revgreen:hover,
	.revgreen:hover,
	.revgreen.ui-state-active,
	#adamlabsgallery-meta-box .revgreen:hover,
	#adamlabsgallery-meta-box .revgreen.ui-state-active 	{	background:#2ecc71 !important}
	
	#adamlabsgallery-meta-box .revred,
	#adamlabsgallery-meta-box .adamlabsgallery-remove-custom-meta-field,
	#adamlabsgallery-meta-box .revred.button-disabled	{	background: #e74c3c !important}
	#adamlabsgallery-meta-box .adamlabsgallery-remove-custom-meta-field:hover,
	#adamlabsgallery-meta-box .revred:hover				{	background: #c0392b !important}
	
	#adamlabsgallery-meta-box .revyellow,
	#adamlabsgallery-meta-box .revyellow.button-disabled	{	background: #f1c40f !important}
	#adamlabsgallery-meta-box .revyellow:hover			{	background: #f39c12 !important}
	
	.revgray,
	#adamlabsgallery-meta-box .revgray					{	background: #95a5a6 !important}
	.revgray:hover,
	#adamlabsgallery-meta-box .revgray:hover					{	background: #7f8c8d !important}
	
	
	.revcarrot,
	.revcarrot.button-disabled,
	#adamlabsgallery-meta-box .revcarrot,
	#adamlabsgallery-meta-box .revcarrot.button-disabled	{	background: #e67e22 !important}
	.revcarrot:hover,
	#adamlabsgallery-meta-box .revcarrot:hover				{	background: #d35400 !important}
	
	
	
	#button_upload_plugin.revpurple,
	#adamlabsgallery-meta-box .revpurple,
	.revpurple								{	background:#9b59b6 !important}
	
	#button_upload_plugin.revpurple:hover,
	.revpurple:hover,
	.revpurple.ui-state-active,
	#adamlabsgallery-meta-box .revpurple:hover,
	#adamlabsgallery-meta-box .revpurple.ui-state-active 	{	background:#8e44ad !important}
	
	
	#adamlabsgallery-meta-box .iris-picker	{
		position: absolute;
		vertical-align: bottom;
		z-index: 100;
	}
	
	#adamlabsgallery_sources_image-wrapper img{
		max-width: 400px; width:auto;
		max-height: 400px;height:auto;
		
	}
	
	#adamlabsgallery-meta-box  .adamlabsgallery-custom-meta-setting-wrap:first-child	{	margin-top:0px !important; padding-top:0px !important;}
	#adamlabsgallery-meta-box  .adamlabsgallery-custom-meta-setting-wrap:last-child		{	border-bottom:none !important;}
	
	.adamlabsgallery-options-tab				{	display:none;}
	.adamlabsgallery-options-tab.selected	{	display:block;}
	
	.adamlabsgallery-option-tabber			{	display:inline-block; margin:0px 5px 0px 0px;padding:10px 15px; line-height: 18px; background:#d1d1d1; cursor: pointer;}
	.adamlabsgallery-option-tabber.selected 	{	background:#FFF }
	
	.adamlabsgallery-option-tabber-wrapper	{margin: -7px -12px 30px; background: #F1F1F1; padding-top: 10px}
</style>

<ul class="adamlabsgallery-option-tabber-wrapper">
	<?php 
	$selectedtab = "selected";
	if(isset($disable_advanced) && $disable_advanced == true){ //only show if we are in preview mode
		?>
		<li class="adamlabsgallery-option-tabber selected" data-target="#adamlabsgallery-my-cobbles-options"><span style="font-size: 18px;line-height: 18px;margin-right: 10px;" class="dashicons dashicons-align-center"></span><?php _e('Item Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
		<?php
		$selectedtab = "";
	}
	?>
	<li class="adamlabsgallery-option-tabber <?php echo $selectedtab; ?>" data-target="#adamlabsgallery-custommeta-options"><span style="font-size: 18px;line-height: 18px;margin-right: 10px;" class="dashicons dashicons-list-view"></span><?php _e('Custom Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
	<li class="adamlabsgallery-option-tabber" data-target="#adamlabsgallery-source-options"><span style="font-size: 18px;line-height: 18px;margin-right: 10px;" class="dashicons dashicons-admin-media"></span><?php _e('Alternative Sources', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
	<?php
	//if(!isset($disable_advanced) || $disable_advanced == false){
	?>
		<li class="adamlabsgallery-option-tabber" data-target="#adamlabsgallery-skin-options"><span style="font-size: 18px;line-height: 18px;margin-right: 10px;" class="dashicons dashicons-admin-appearance"></span><?php _e('Skin Modifications', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
	<?php
	//}
	?>
	<li class="adamlabsgallery-option-tabber" data-target="#adamlabsgallery-featured-grid-options" style="margin-right: 0"><span style="font-size: 18px;line-height: 18px;margin-right: 10px;" class="dashicons dashicons-screenoptions"></span><?php _e('Featured Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
</ul>
<?php
$selectedtab = "selected";
if(isset($disable_advanced) && $disable_advanced == true){ //only show if we are in preview mode
	$cobbles = '1:1';
	$raw_cobbles = isset($values['eg_cobbles']) ? json_decode($values['eg_cobbles'][0], true) : '';
	if(isset($grid_id) && isset($raw_cobbles[$grid_id]) && isset($raw_cobbles[$grid_id]['cobbles']))
		$cobbles = $raw_cobbles[$grid_id]['cobbles'];
		
	?>
	<div id="adamlabsgallery-my-cobbles-options" class="adamlabsgallery-options-tab <?php echo $selectedtab; ?>">
		<div>
			<div class="adamlabsgallery-cs-row" style="float:left">
				<label class="adamlabsgallery-mb-label"><?php _e('Cobbles Element Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			</div>
			<select name="eg_cobbles_size" id="eg_cobbles_size">
				<option value="1:1"<?php selected($cobbles, '1:1'); ?>><?php _e('width 1, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="1:2"<?php selected($cobbles, '1:2'); ?>><?php _e('width 1, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="1:3"<?php selected($cobbles, '1:3'); ?>><?php _e('width 1, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="1:4"<?php selected($cobbles, '1:4'); ?>><?php _e('width 1, height 4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="2:1"<?php selected($cobbles, '2:1'); ?>><?php _e('width 2, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="2:2"<?php selected($cobbles, '2:2'); ?>><?php _e('width 2, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="2:3"<?php selected($cobbles, '2:3'); ?>><?php _e('width 2, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="2:4"<?php selected($cobbles, '2:4'); ?>><?php _e('width 2, height 4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="3:1"<?php selected($cobbles, '3:1'); ?>><?php _e('width 3, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="3:2"<?php selected($cobbles, '3:2'); ?>><?php _e('width 3, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="3:3"<?php selected($cobbles, '3:3'); ?>><?php _e('width 3, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="3:4"<?php selected($cobbles, '3:4'); ?>><?php _e('width 3, height 4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="4:1"<?php selected($cobbles, '4:1'); ?>><?php _e('width 4, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="4:2"<?php selected($cobbles, '4:2'); ?>><?php _e('width 4, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="4:3"<?php selected($cobbles, '4:3'); ?>><?php _e('width 4, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="4:4"<?php selected($cobbles, '4:4'); ?>><?php _e('width 4, height 4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			</select>
			<div style="clear:both; height: 20px;"></div>
			<?php
			$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins('all', false);
			$use_skin = -1;
			$raw_skin = isset($values['eg_use_skin']) ? json_decode($values['eg_use_skin'][0], true) : '';
			if(isset($grid_id) && isset($raw_skin[$grid_id]) && isset($raw_skin[$grid_id]['use-skin']))
				$use_skin = $raw_skin[$grid_id]['use-skin'];
			?>
			<div class="adamlabsgallery-cs-row" style="float:left">
				<label class="adamlabsgallery-mb-label"><?php _e('Choose Specific Skin:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			</div>
			<select name="eg_use_skin">
				<option value="-1"><?php _e('-- Default Skin --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<?php
				if(!empty($skins)){
					foreach($skins as $skin){
						echo '<option value="'.$skin['id'].'"'.selected($use_skin, $skin['id']).'>'.$skin['name'].'</option>'."\n";
					}
				}
				?>
			</select>
			<div style="clear:both; height: 20px;"></div>
			
			<div class="adamlabsgallery-cs-row" style="float:left">
				<label class="adamlabsgallery-mb-label"><?php _e('Media Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			</div>
			<select name="adamlabsgallery_image_fit">
				<option value="-1"><?php _e('-- Default Fit --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="contain" <?php selected($adamlabsgallery_image_fit, 'contain'); ?>><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="cover" <?php selected($adamlabsgallery_image_fit, 'cover'); ?>><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			</select>
			
			<div style="clear:both; height: 20px;"></div>
			
			<div class="adamlabsgallery-cs-row" style="float:left">
				<label class="adamlabsgallery-mb-label"><?php _e('Media Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			</div>
			<select name="adamlabsgallery_image_repeat">
				<option value="-1"><?php _e('-- Default Repeat --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="no-repeat" <?php selected($adamlabsgallery_image_repeat, 'no-repeat'); ?>><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="repeat" <?php selected($adamlabsgallery_image_repeat, 'repeat'); ?>><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="repeat-x" <?php selected($adamlabsgallery_image_repeat, 'repeat-x'); ?>><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="repeat-y" <?php selected($adamlabsgallery_image_repeat, 'repeat-y'); ?>><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			</select>
			
			<div style="clear:both; height: 20px;"></div>
			<div class="adamlabsgallery-cs-row" style="float:left">
				<label class="adamlabsgallery-mb-label"><?php _e('Media Align', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			</div>
			<select name="adamlabsgallery_image_align_h">
				<option value="-1"><?php _e('-- Horizontal Align --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="left" <?php selected($adamlabsgallery_image_align_h, 'left'); ?>><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="center" <?php selected($adamlabsgallery_image_align_h, 'center'); ?>><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="right" <?php selected($adamlabsgallery_image_align_h, 'right'); ?>><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			</select>
			<select name="adamlabsgallery_image_align_v">
				<option value="-1"><?php _e('-- Vertical Align --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="top" <?php selected($adamlabsgallery_image_align_v, 'top'); ?>><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="center" <?php selected($adamlabsgallery_image_align_v, 'center'); ?>><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
				<option value="bottom" <?php selected($adamlabsgallery_image_align_v, 'bottom'); ?>><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			</select>
			<div style="clear:both; height: 20px;"></div>
		</div>
	</div>
	<?php
	$selectedtab ="";
}
?>

<div id="adamlabsgallery-custommeta-options" class="adamlabsgallery-options-tab <?php echo $selectedtab; ?>">
	<div>
		<?php
		if(!empty($custom_meta)){
			foreach($custom_meta as $cmeta){
				//check if post already has a value set
				$val = isset($values['adamlabsgallery-'.$cmeta['handle']]) ? esc_attr($values['adamlabsgallery-'.$cmeta['handle']][0]) : @$cmeta['default'];
				?>
					<div class="adamlabsgallery-cs-row-min"><label class="adamlabsgallery-mb-label"><?php echo $cmeta['name']; ?>:</label>
					<?php
					switch($cmeta['type']){
						case 'text':
							echo '<input type="text" name="adamlabsgallery-'.$cmeta['handle'].'" value="'.$val.'" />';
							break;
						case 'select':
						case 'multi-select':
							$do_array = ($cmeta['type'] == 'multi-select') ? '[]' : '';
							$el = $meta->prepare_select_by_string($cmeta['select']);
							echo '<select name="adamlabsgallery-'.$cmeta['handle'].$do_array.'"';
							if($cmeta['type'] == 'multi-select') echo ' multiple="multiple" size="5"';
							echo '>';
							if(!empty($el) && is_array($el)){
								if($cmeta['type'] != 'multi-select'){
									echo '<option value="">'.__('---', ADAMLABS_GALLERY_TEXTDOMAIN).'</option>';
								}else{
									$val = json_decode(str_replace('&quot;', '"', $val), true);
								}
								foreach($el as $ele){
									if(is_array($val)){
										$sel = (in_array($ele, $val)) ? ' selected="selected"' : '';
									}else{
										$sel = ($ele == $val) ? ' selected="selected"' : '';
									}
									echo '<option value="'.$ele.'"'.$sel.'>'.$ele.'</option>';
								}
							}
							echo '</select>';
							break;
						case 'image':
							$var_src = '';
							if(intval($val) > 0){
								//get URL to Image
								$img = wp_get_attachment_image_src($val, 'full');
								if($img !== false){
									$var_src = $img[0];
								}else{
									$val = '';
								}
							}else{
								$val = '';
							}
							?>
							<input type="hidden" value="<?php echo $val; ?>" name="adamlabsgallery-<?php echo $cmeta['handle']; ?>" id="adamlabsgallery-<?php echo $cmeta['handle']; ?>" />
							<a class="button-primary adamlabsgallery-cm-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle']; ?>"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a class="button-primary adamlabsgallery-cm-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle']; ?>"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<div>
								<img id="adamlabsgallery-<?php echo $cmeta['handle']; ?>-img" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?>>
							</div>
							<?php
							break;
					}
					?>
					</div>
				<?php
			}
		}else{
			_e('No metas available yet. Add some through the Custom Meta menu of Portfolio Gallery.', ADAMLABS_GALLERY_TEXTDOMAIN);
			?><div style="clear:both; height:20px"></div><?php 			
		}
		?>

		<a href="<?php echo AdamLabsGallery_Admin::getSubViewUrl(AdamLabsGallery_Admin::VIEW_SUB_CUSTOM_META_AJAX); ?>" class="button-primary" style="margin-top:20px !important; margin-bottom:20px !important;" target="_blank"><?php _e('Create New Meta Keys', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
	</div>
</div> <!-- END OF EG OPTION TAB -->

<div id="adamlabsgallery-featured-grid-options" class="adamlabsgallery-options-tab">
	<div class="adamlabsgallery-notifcation">
		<div class="dashicons dashicons-lightbulb" style="float:left;margin-right:10px;"></div>
		<div style="float:left; "><?php _e('The selected grid will be displayed instead of the featured image on the single post and in the blog overviews.<br>If this feature does not work in your theme please check out this <a href="https://www.mi-press.com/revslider-doc/add-on-featured-slider/#theme_not_support">short tutorial</a> to code in manually.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
		<div style="clear:both"></div>
	</div>
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('Select Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>
	<div class="adamlabsgallery-cs-row" style="float:left">
		<label class="adamlabsgallery-mb-label adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the grid to display', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
		<select id="adamlabsgallery-featured-grid" name="adamlabsgallery_featured_grid">
			<option value=""><?php _e("No Featured Portfolio Gallery",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
			<?php 

					$grids = new AdamLabsGallery();
					$arrGrids = $grids->get_adamlabsgallery_grids();
					foreach($arrGrids as $grid){
						echo '<option value="'.$grid->handle.'" '. selected( $adamlabsgallery_featured_grid, $grid->handle, false ) .'>'. $grid->name . '</option>';
					}
				?>
		</select>
	</div>
	<div style="clear:both"></div>
</div> <!-- END OF EG FEATURED TAB -->


<div id="adamlabsgallery-source-options" class="adamlabsgallery-options-tab">
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('HTML5 Video & Audio Source`s', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>
	<p>
		<div class="adamlabsgallery-cs-row" style="float:left"><label class="adamlabsgallery-mb-label"><?php _e('MP4 / Audio', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label> <input type="text" name="adamlabsgallery_sources_html5_mp4" id="adamlabsgallery_sources_html5_mp4" style="margin-right:20px" value="<?php echo $adamlabsgallery_sources_html5_mp4; ?>" /></div>
		<div class="adamlabsgallery-cs-row" style="float:left">
			<label class="adamlabsgallery-mb-label adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Video Ratio:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<select id="adamlabsgallery-html5-ratio" name="adamlabsgallery_html5_ratio">
				<option value="1"<?php selected($adamlabsgallery_html5_ratio, '1'); ?>>16:9</option>
				<option value="0"<?php selected($adamlabsgallery_html5_ratio, '0'); ?>>4:3</option>
			</select>
		</div>
		<div class="adamlabsgallery-cs-row" style="clear: both"><label class="adamlabsgallery-mb-label"><?php _e('OGV', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label> <input type="text" name="adamlabsgallery_sources_html5_ogv" id="adamlabsgallery_sources_html5_ogv" style="margin-right:20px" value="<?php echo $adamlabsgallery_sources_html5_ogv; ?>" /></div>
		<div class="adamlabsgallery-cs-row"><label class="adamlabsgallery-mb-label"><?php _e('WEBM', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label> <input type="text" name="adamlabsgallery_sources_html5_webm" id="adamlabsgallery_sources_html5_webm" style="margin-right:20px" value="<?php echo $adamlabsgallery_sources_html5_webm; ?>" /></div>
		
		<div style="clear:both"></div>
	</p>
		
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('YouTube, Vimeo or Wistia Video Source`s', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>

	<p>
		<div class="adamlabsgallery-cs-row" style="float:left"><label class="adamlabsgallery-mb-label" for="adamlabsgallery_sources_youtube"><?php _e('YouTube ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery_sources_youtube" id="adamlabsgallery_sources_youtube" style="margin-right:20px"  value="<?php echo $adamlabsgallery_sources_youtube; ?>" /></div>
		<div class="adamlabsgallery-cs-row" style="float:left">
			<label class="adamlabsgallery-mb-label"  class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Video Ratio:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<select id="adamlabsgallery-youtube-ratio" name="adamlabsgallery_youtube_ratio">
				<option value="1"<?php selected($adamlabsgallery_youtube_ratio, '1'); ?>>16:9</option>
				<option value="0"<?php selected($adamlabsgallery_youtube_ratio, '0'); ?>>4:3</option>
									
			</select>
		</div>
		<div style="clear:both"></div>		
		<div class="adamlabsgallery-cs-row" style="float:left"><label  class="adamlabsgallery-mb-label" for="adamlabsgallery_sources_vimeo"><?php _e('Vimeo ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery_sources_vimeo" id="adamlabsgallery_sources_vimeo" style="margin-right:20px" value="<?php echo $adamlabsgallery_sources_vimeo; ?>" /></div>
		<div class="adamlabsgallery-cs-row" style="float:left">
			<label class="adamlabsgallery-mb-label adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Video Ratio:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<select id="adamlabsgallery-vimeo-ratio" name="adamlabsgallery_vimeo_ratio">
				
				<option value="1"<?php selected($adamlabsgallery_vimeo_ratio, '1'); ?>>16:9</option>
				<option value="0"<?php selected($adamlabsgallery_vimeo_ratio, '0'); ?>>4:3</option>
			</select>
		</div>
		<div style="clear:both"></div>		
		<div class="adamlabsgallery-cs-row" style="float:left"><label  class="adamlabsgallery-mb-label" for="adamlabsgallery_sources_wistia"><?php _e('Wistia ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery_sources_wistia" id="adamlabsgallery_sources_wistia" style="margin-right:20px" value="<?php echo $adamlabsgallery_sources_wistia; ?>" /></div>
		<div class="adamlabsgallery-cs-row" style="float:left">
			<label class="adamlabsgallery-mb-label adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Video Ratio:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<select id="adamlabsgallery-vimeo-ratio" name="eg_wistia_ratio" >
				
				<option value="1"<?php selected($eg_wistia_ratio, '1'); ?>>16:9</option>
				<option value="0"<?php selected($eg_wistia_ratio, '0'); ?>>4:3</option>
			</select>
		</div>
		<div style="clear:both"></div>		
	</p>
	
	
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('Sound Cloud', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>

	<p>
		<div class="adamlabsgallery-cs-row" style="float:left"><label class="adamlabsgallery-mb-label" for="adamlabsgallery_sources_soundcloud"><?php _e('SoundCloud Track ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery_sources_soundcloud" id="adamlabsgallery_sources_soundcloud" style="margin-right:20px"  value="<?php echo $adamlabsgallery_sources_soundcloud; ?>" /></div>
		<div class="adamlabsgallery-cs-row" style="float:left">
			<label class="adamlabsgallery-mb-label adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the SoundCloud iFrame Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Frame Ratio:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
			<select id="adamlabsgallery-soundcloud-ratio" name="eg_soundcloud_ratio">
				<option value="1"<?php selected($eg_soundcloud_ratio, '1'); ?>>16:9</option>
				<option value="0"<?php selected($eg_soundcloud_ratio, '0'); ?>>4:3</option>
									
			</select>
		</div>
		<div style="clear:both"></div>		
	</p>
		
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('Image Source`s', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>
	<p>
		<label  class="adamlabsgallery-mb-label" for="adamlabsgallery_sources_image"><?php _e('Alt. Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
		<input type="text" name="adamlabsgallery_sources_image" id="adamlabsgallery_sources_image" style="display: none;" value="<?php echo $adamlabsgallery_sources_image; ?>" />
		<a id="adamlabsgallery-choose-from-image-library" class="button-primary" data-setto="adamlabsgallery_sources_image" href="javascript:void(0);"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
		<a id="adamlabsgallery-clear-from-image-library" class="button-primary adamlabsgallery-remove-custom-meta-field" href="javascript:void(0);"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
	</p>
	<div id="adamlabsgallery_sources_image-wrapper">
		<img id="adamlabsgallery_sources_image-img" src="<?php echo $adamlabsgallery_sources_image_url; ?>">
	</div>
	
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('iFrame HTML Markup', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>
	<p>
		<textarea type="text" style="width:100%;background:#f1f1f1;min-height:150px;" name="adamlabsgallery_sources_iframe" id="adamlabsgallery_sources_iframe"><?php echo $adamlabsgallery_sources_iframe; ?></textarea>
	</p>
	
	<p style="margin-top:10px">
		<strong style="font-size:14px"><?php _e('Choose Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></strong>
	</p>
	<p>
		<select id="adamlabsgallery_sources_adamlabsgallery" name="adamlabsgallery_sources_adamlabsgallery">
			
			<option value="">--- Choose Grid ---</option>
			<?php 
							
				$_grids = AdamLabsGallery::get_adamlabsgallery_grids();
				foreach($_grids as $_grid) {
					
					$_alias = $_grid -> handle;
					$_shortcode = '[adamlabsgallery alias="' . $_alias . '"]';
					$_shortcode = str_replace('"', '', $_shortcode)
					
					?><option <?php selected($adamlabsgallery_sources_adamlabsgallery, $_alias); ?> value="<?php echo $_alias; ?>"><?php echo $_shortcode; ?></option>
					
				<?php }
		
			?>
				
		</select>
	</p>
	
	<?php
	do_action('adamlabsgallery_add_meta_options', $values);
	
	/* 2.2.6 */
	//if(!isset($disable_advanced) || $disable_advanced == false){
		?>
		</div><!-- END OF EG OPTION TAB -->
		
		<div id="adamlabsgallery-skin-options" class="adamlabsgallery-options-tab">
		<!--<h2><span style="margin:5px 10px 0px 10px"class="dashicons dashicons-admin-generic"></span><?php _e('Custom Post Based Skin Modifications', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h2>-->
		<div id="adamlabsgallery-advanced-param-wrap">
			<div class="adamlabsgallery-advanced-param" id="adamlabsgallery-advanced-param-post">
				
			</div>
			<a class="button-primary adamlabsgallery-add-custom-meta-field" href="javascript:void(0);" id="adamlabsgallery-add-custom-meta-field-post" style="margin-top:10px !important"><?php _e('Add New Custom Skin Rule', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
			<div class="adamlabsgallery-notifcation">
				<div class="dashicons dashicons-lightbulb" style="float:left;margin-right:10px;"></div>
				<div style="float:left; "><?php _e("For default Skin Settings please use the Portfolio Gallery Skin Editor.<br> Only add Rules here to change the Skin Element Styles only for this Post !<br>Every rule defined here will overwrite the Global Skin settings explicit for this Post in the Grid where the Skin is used. ", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
				<div style="clear:both"></div>
			</div>
			
		</div>
		
		<?php
	//}  
		if(isset($disable_advanced) && $disable_advanced == true){ //only show if we are in preview mode
		?>
		</form>
		<?php
	}
	?>
</div>

<!-- ESG 2.1.6 -->
<?php
	$adamlabsgallery_custom_meta_216 = isset($values['adamlabsgallery_custom_meta_216']) ? esc_attr($values['adamlabsgallery_custom_meta_216'][0]) : 'false';
	if($adamlabsgallery_custom_meta_216 != 'true') { ?>
	<script type="text/javascript">
		var adamlabsgallery_skin_color_values = {
		<?php
			$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins('all', false);
			foreach($skins as $skin) {
				if(isset($skin['params']) && !empty($skin['params']) && is_string($skin['params'])) {
					$params = json_decode($skin['params'], true);
					if(!empty($params) && isset($params['container-background-color']) && !empty($params['container-background-color'])) {
						echo '"' . $skin['id'] . '": "' . $params['container-background-color'] . '",';
					}
				}	
			}
		?>
};
	</script>
	<?php } ?>
<input type="hidden" name="adamlabsgallery_custom_meta_216" value="true" />

<script type="text/javascript">

	jQuery(function(){
	
		jQuery('.adamlabsgallery-option-tabber').click(function() {
			var t = jQuery(this),
				s = jQuery('.adamlabsgallery-option-tabber.selected');
			
			s.removeClass("selected");
			t.addClass("selected");
			jQuery(s.data('target')).fadeOut(0);
			jQuery(t.data('target')).fadeIn(200);
		});
		
		jQuery('#adamlabsgallery-choose-from-image-library').click(function(e) {
			e.preventDefault();
			AdminEssentials.upload_image_img(jQuery(this).data('setto'));
			
			return false; 
		});
		
		jQuery('#adamlabsgallery-clear-from-image-library').click(function(e) {
			e.preventDefault();
			jQuery('#adamlabsgallery_sources_image').val('');
			jQuery('#adamlabsgallery_sources_image-img').attr("src","");
			jQuery('#adamlabsgallery_sources_image-img').hide();
			return false; 
		});
		
		
		jQuery('.adamlabsgallery-cm-image-add').click(function(e) {
			e.preventDefault();
			AdminEssentials.upload_image_img(jQuery(this).data('setto'));
			
			return false; 
		});
		
		jQuery('.adamlabsgallery-cm-image-clear').click(function(e) {
			e.preventDefault();
			var setto = jQuery(this).data('setto');
			jQuery('#'+setto).val('');
			jQuery('#'+setto+'-img').attr("src","");
			jQuery('#'+setto+'-img').hide();
			return false; 
		});
		
		
		<?php
		//if(!isset($disable_advanced) || $disable_advanced == false){
		?>
		
		AdminEssentials.setInitSkinsJson(<?php echo $base->jsonEncodeForClientSide($advanced); ?>);
		AdminEssentials.setInitElementsJson(<?php echo $base->jsonEncodeForClientSide($adamlabsgallery_meta); ?>);
		AdminEssentials.setInitStylingJson(<?php echo $base->jsonEncodeForClientSide($eg_elements); ?>);
		AdminEssentials.initMetaBox('post');
		
		<?php
		//}
		?>
		if(jQuery('#adamlabsgallery_sources_image-img').attr('src') !== '')
			jQuery('#adamlabsgallery_sources_image-img').show();
		else
			jQuery('#adamlabsgallery_sources_image-img').hide();
			
	});
	
	
</script>