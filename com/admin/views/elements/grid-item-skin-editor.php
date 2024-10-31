<?php

if( !defined( 'ABSPATH') ) exit();

//force the js file to be included
wp_enqueue_script('adamlabsgallery-item-editor-script', plugins_url('../../assets/js/grid-editor.js', __FILE__ ), array('jquery'), AdamLabsGallery::VERSION );

$base = new AdamLabsGallery_Base();
$item_elements = new AdamLabsGallery_Item_Element();
$meta = new AdamLabsGallery_Meta();
$meta_link = new AdamLabsGallery_Meta_Linking();
$fonts = new AdamLabs_Fonts();

//check if id exists and get data from database if so.
$skin = false;
$skin_id = false;

$isCreate = $base->getGetVar('create', 'true');

$title = __('Create New Item Template', ADAMLABS_GALLERY_TEXTDOMAIN);
$save = __('Save Item Template', ADAMLABS_GALLERY_TEXTDOMAIN);

if(intval($isCreate) > 0){ //currently editing
	$skin = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skin_by_id(intval($isCreate));
	if(!empty($skin)){
		$title = __('Change Item Template', ADAMLABS_GALLERY_TEXTDOMAIN);
		$save = __('Change Item Template', ADAMLABS_GALLERY_TEXTDOMAIN);
		$skin_id = intval($isCreate);
	}
}

$elements = $item_elements->getElementsForJavascript();
$style_attributes = $item_elements->get_existing_elements(true);
$all_attributes = $item_elements->get_existing_elements();
$element_type = $item_elements->getElementsForDropdown();

$fonts_full = $fonts->get_all_fonts();

$meta_keys = $meta->get_all_meta_handle();

$meta_link_keys = $meta_link->get_all_link_meta_handle();
$meta_keys = array_merge($meta_keys, $meta_link_keys);

$transitions_cover = $base->get_hover_animations();
$transitions_media = $base->get_media_animations();

/* 2.1.6 - for the new home-image option */
$transitions_hover = array_slice($transitions_cover, 0, count($transitions_cover), true);
if(isset($transitions_hover['turn'])) unset($transitions_hover['turn']);
if(isset($transitions_hover['covergrowup'])) unset($transitions_hover['covergrowup']);

/* 2.2.4.2 */
$transitions_elements = array_slice($transitions_cover, 0, count($transitions_cover), true);
if(isset($transitions_elements['rotatescale'])) unset($transitions_elements['rotatescale']);
if(isset($transitions_elements['covergrowup'])) unset($transitions_elements['covergrowup']);

if(!isset($skin['params'])) $skin['params'] = array(); //fallback if skin does not exist
if(!isset($skin['layers'])) $skin['layers'] = array(); //fallback if skin does not exist

?>

<div id="adamlabsgallery-tool-panel">
    <div id="adamlabsgallery-global-change" class="adamlabsgallery-side-buttons button-primary">
        <i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;margin-right:10px !important;margin-left:2px !important;"></i><?php _e('Save Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
    </div>
	<div id="adamlabsgallery-global-css-dialog" class="button-primary adamlabsgallery-side-buttons">
		<i>&lt;/&gt;</i><?php _e('CSS Editor', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
	</div>

	<!--<a href="<?php /*echo $base->getViewUrl("","",'adamlabsgallery-'.AdamLabsGallery_Admin::VIEW_SUB_ITEM_SKIN_OVERVIEW); */?>" id="adamlabsgallery-global-back-to-overview" class="adamlabsgallery-side-buttons button-primary">
		<i class="adamlabsgallery-icon-th"></i><?php /*_e('Skin Overview', ADAMLABS_GALLERY_TEXTDOMAIN); */?>
	</a>-->
</div>

<div id="skin-editor-wrapper">

	<?php
	if($skin_id !== false){
		?><input type="hidden" value="<?php echo $skin_id; ?>" name="adamlabsgallery-item-skin-id" /><?php
	}
	?>

	<h2 class="topheader"><?php _e('Item Template Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <input type="text" name="item-skin-name" value="<?php echo esc_attr(@$skin['name']); ?>" style="margin-right: 15px;" /> <span style="font-size:12px;font-weight:600;"><?php _e('Class Prefix = ', ADAMLABS_GALLERY_TEXTDOMAIN); ?> .adamlabsgallery-<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Each element in the Skin becomes this CSS Prefix', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" id="adamlabsgallery-item-skin-slug"></span>-</span></h2>

	<div style="width:100%;height:15px"></div>
	<div>

		<div style="float:left; width:670px;margin-right:15px;">
			<!-- START OF SETTINGS ON THE LEFT SIDE  border: 2px solid #27AE60; -->
			<form id="adamlabsgallery-form-item-skin-layout-settings">
				
				<input type="hidden" value="<?php echo $base->getVar($skin['params'], 'adamlabsgallery-item-skin-element-last-id', 0, 'i'); ?>" name="adamlabsgallery-item-skin-element-last-id" />
				<div class="postbox adamlabsgallery-postbox" style=""><h3 style="padding:10px"><span><i style="background-color:#27AE60; padding:3px; margin-right:10px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;color:#fff;" class="adamlabsgallery-icon-menu"></i><?php _e('Layout Composition', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><div class="postbox-arrow"></div></h3>
					<div class="inside" style="padding:0px;margin:0px;height:455px">

						<div class="adamlabsgallery-lc-menu-wrapper" style="height:100%;">
							<div class="adamlabsgallery-lc-vertical-menu" style="height:100%;">
								<ul>
									<li class="selected-lc-setting" data-toshow="adamlabsgallery-lc-layout"><i class="adamlabsgallery-icon-th-large"></i><p><?php _e('Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
									<li data-toshow="adamlabsgallery-lc-cover"><i class="adamlabsgallery-icon-stop"></i><p><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
									<li data-toshow="adamlabsgallery-lc-spaces"><i class="adamlabsgallery-icon-indent-right"></i><p><?php _e('Spaces', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
									<li data-toshow="adamlabsgallery-lc-content-shadow"><i class="adamlabsgallery-icon-picture"></i><p><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
									<li data-toshow="adamlabsgallery-lc-content-animation"><i class="adamlabsgallery-icon-star"></i><p><?php _e('Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
									<li data-toshow="adamlabsgallery-lc-content-link-seo"><i class="adamlabsgallery-icon-link"></i><p><?php _e('Link/SEO', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
								</ul>
							</div>

							<!-- THE LAYOUT SETTINGS -->
							<div id="adamlabsgallery-lc-layout" class="adamlabsgallery-lc-settings-container active-esc">
								<div style="margin-top:15px">
									<label for="choose-preset" class="adamlabsgallery-group-setter"><?php _e('Grid Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" name="choose-layout" value="even" class="firstinput" <?php checked($base->getVar($skin['params'], 'choose-layout', 'even'), 'even'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Each item gets Same Height. Width and Height are Item Ratio dependent.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Even', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									<input type="radio" name="choose-layout" value="masonry" <?php checked($base->getVar($skin['params'], 'choose-layout', 'even'), 'masonry'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items height are depending on Media height and Content height.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Masonry', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								</div>
								<!-- MASONRY SETTINGS-->
								<div id="adamlabsgallery-show-content">
									<div style="margin-top:15px">
										<label style="float:left" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Position of Fixed Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="show-content">
												<option value="bottom" <?php selected($base->getVar($skin['params'], 'show-content', 'none'), 'bottom'); ?>><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="top" <?php selected($base->getVar($skin['params'], 'show-content', 'none'), 'top'); ?>><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="none" <?php selected($base->getVar($skin['params'], 'show-content', 'none'), 'none'); ?>><?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake adamlabsgallery-tooltip-wrap" title="<?php _e('Content Text Align Globaly', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="content-align">
												<option value="left" <?php selected($base->getVar($skin['params'], 'content-align', 'left'), 'left'); ?>><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="center" <?php selected($base->getVar($skin['params'], 'content-align', 'left'), 'center'); ?>><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="right" <?php selected($base->getVar($skin['params'], 'content-align', 'left'), 'right'); ?>><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="clear"></div>
								<div style="margin-top:15px">
									<label style="float: left;" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Media Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Media Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float: left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="image-repeat">
											<option value="no-repeat" <?php selected($base->getVar($skin['params'], 'image-repeat', 'no-repeat'), 'no-repeat'); ?>><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat" <?php selected($base->getVar($skin['params'], 'image-repeat', 'no-repeat'), 'repeat'); ?>><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat-x" <?php selected($base->getVar($skin['params'], 'image-repeat', 'no-repeat'), 'repeat-x'); ?>><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat-y" <?php selected($base->getVar($skin['params'], 'image-repeat', 'no-repeat'), 'repeat-y'); ?>><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
								<div style="margin-top:15px">
									<label style="float: left;" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Media Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Media Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float: left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="image-fit">
											<option value="contain" <?php selected($base->getVar($skin['params'], 'image-fit', 'cover'), 'contain'); ?>><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="cover" <?php selected($base->getVar($skin['params'], 'image-fit', 'cover'), 'cover'); ?>><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
								<div style="margin-top:15px">
									<label style="float: left;" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Media Align horizontal and vertical', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Media Align', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float: left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="image-align-horizontal">
											<option value="left" <?php selected($base->getVar($skin['params'], 'image-align-horizontal', 'center'), 'left'); ?>><?php _e('Hor. Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="center" <?php selected($base->getVar($skin['params'], 'image-align-horizontal', 'center'), 'center'); ?>><?php _e('Hor. Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="right" <?php selected($base->getVar($skin['params'], 'image-align-horizontal', 'center'), 'right'); ?>><?php _e('Hor. Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
									<div class="select_wrapper" style="float: left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="image-align-vertical">
											<option value="top" <?php selected($base->getVar($skin['params'], 'image-align-vertical', 'center'), 'top'); ?>><?php _e('Ver. Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="center" <?php selected($base->getVar($skin['params'], 'image-align-vertical', 'center'), 'center'); ?>><?php _e('Ver. Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="bottom" <?php selected($base->getVar($skin['params'], 'image-align-vertical', 'center'), 'bottom'); ?>><?php _e('Ver. Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
								<!-- EVEN SETTINGS -->
								<div id="adamlabsgallery-show-ratio" >
									<p>
										<label class="adamlabsgallery-group-setter"><?php _e('Ratio X', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<span id="element-x-ratio" class="slider-settings adamlabsgallery-tooltip-wrap" title="<?php _e('Width Ratio of Item.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"></span> <input class="input-settings-small element-setting" type="text" name="element-x-ratio" value="<?php echo $base->getVar($skin['params'], 'element-x-ratio', 4, 'i'); ?>" />
									</p>
									<p>
										<label style="float:left" class="adamlabsgallery-group-setter"><?php _e('Ratio Y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<span id="element-y-ratio" class="slider-settings adamlabsgallery-tooltip-wrap" title="<?php _e('Height Ratio of Item.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"></span> <input class="input-settings-small element-setting" type="text" name="element-y-ratio" value="<?php echo $base->getVar($skin['params'], 'element-y-ratio', 3, 'i'); ?>" />
									</p>
								</div>

								<div class="clear"></div>
								
								<!-- 2.1.6 -->
								<!-- SPLITTED ITEMS -->
								<div style="margin-top:15px">
									<label style="float: left;" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Display Media and Content side-by-side', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Split Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float: left;">
										<div class="select_fake"><span><?php _e('Split Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="splitted-item">
											<option value="none" <?php selected($base->getVar($skin['params'], 'splitted-item', 'none'), 'none'); ?>><?php _e('No Split', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="left" <?php selected($base->getVar($skin['params'], 'splitted-item', 'none'), 'left'); ?>><?php _e('Media Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="right" <?php selected($base->getVar($skin['params'], 'splitted-item', 'none'), 'right'); ?>><?php _e('Media Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>

							</div>
							<!-- THE COVER SETTINGS -->
							<div id="adamlabsgallery-lc-cover" class="adamlabsgallery-lc-settings-container">
								<!-- COVER LAYOUT -->
								<div style="margin-top:15px">
									<label style="float:left; width:180px" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Dynamic Covering Content Type. Show Cover Background on full Media, or only under Cover Contents ?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Cover Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select id="cover-type" name="cover-type">
											<option value="full" <?php selected($base->getVar($skin['params'], 'cover-type', 'full'), 'full'); ?>><?php _e('Full', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="content" <?php selected($base->getVar($skin['params'], 'cover-type', 'full'), 'content'); ?>><?php _e('Content Based', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
								
								<div style="margin-top:15px">
									<label style="float:left; width:180px" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Add a CSS mix-blend-mode filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Blend Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select id="cover-blend-mode" name="cover-blend-mode">
											<option value="normal" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'normal'); ?>><?php _e('Normal', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="multiply" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'multiply'); ?>><?php _e('Multiply', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="screen" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'screen'); ?>><?php _e('Screen', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="overlay" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'overlay'); ?>><?php _e('Overlay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="darken" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'darken'); ?>><?php _e('Darken', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="lighten" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'lighten'); ?>><?php _e('Lighten', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="color-dodge" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'color-dodge'); ?>><?php _e('Color Dodge', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="color-burn" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'color-burn'); ?>><?php _e('Color Burn', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="hard-light" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'hard-light'); ?>><?php _e('Hard Light', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="soft-light" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'soft-light'); ?>><?php _e('Soft Light', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="difference" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'difference'); ?>><?php _e('Difference', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="exclusion" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'exclusion'); ?>><?php _e('Exclusion', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="hue" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'hue'); ?>><?php _e('Hue', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="saturation" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'saturation'); ?>><?php _e('Saturation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="color" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'color'); ?>><?php _e('Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="luminosity" <?php selected($base->getVar($skin['params'], 'cover-blend-mode', 'normal'), 'luminosity'); ?>><?php _e('Luminosity', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
								<div class="clear"></div>
								
								<p>
									<label style="float:left; width:180px" class="adamlabsgallery-cover-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Background Color of Covers', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Background Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input class="element-setting" type="text" name="container-background-color" id="container-background-color" value="<?php echo $base->getVar($skin['params'], 'container-background-color', '#363839', 's'); ?>" />
								</p>
								
								<?php /*
								<p>
									<label style="float:left; width:180px" class="adamlabsgallery-cover-setter"><?php _e('Opacity', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<span id="element-container-background-color-opacity" class="slider-settings adamlabsgallery-tooltip-wrap" title="<?php _e('Cover Background Color opacity', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"></span>
									<input class="input-settings-small element-setting" type="text" name="element-container-background-color-opacity" value="<?php echo $base->getVar($skin['params'], 'element-container-background-color-opacity', '85', 'i'); ?>" />
								</p>
								*/ ?>
								
								<!-- 2.1.6 -->
								<p>
									<label style="float:left; width:180px" class="adamlabsgallery-cover-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Show without a Hover on Desktop', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Always Visible on Desktop', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="checkbox" name="cover-always-visible-desktop" <?php checked($base->getVar($skin['params'], 'cover-always-visible-desktop', ''), 'true'); ?> />
								</p>
								<!-- 2.1.6 -->
								<p>
									<label style="float:left; width:180px" class="adamlabsgallery-cover-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Show without a Tap on Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Always Visible on Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="checkbox" name="cover-always-visible-mobile" <?php checked($base->getVar($skin['params'], 'cover-always-visible-mobile', ''), 'true'); ?> />
								</p>

								<div style="display:none">
									<label style="float:left; width:150px" class="adamlabsgallery-group-setter"><?php _e('Background Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="cover-background-size">
											<option value="cover" <?php selected($base->getVar($skin['params'], 'cover-background-size', 'cover'), 'cover'); ?>><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="contain" <?php selected($base->getVar($skin['params'], 'cover-background-size', 'cover'), 'contain'); ?>><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<!--option value="%" <?php selected($base->getVar($skin['params'], 'cover-background-size', 'cover'), '%'); ?>><?php _e('%', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option-->
											<option value="auto" <?php selected($base->getVar($skin['params'], 'cover-background-size', 'cover'), 'auto'); ?>><?php _e('Normal', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div><div class="clear"></div>
								</div>
								<div style="display:none">
									<label style="float:left; width:150px" class="adamlabsgallery-group-setter"><?php _e('Background Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="cover-background-repeat">
											<option value="no-repeat" <?php selected($base->getVar($skin['params'], 'cover-background-repeat', 'no-repeat'), 'auto'); ?>><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat" <?php selected($base->getVar($skin['params'], 'cover-background-repeat', 'no-repeat'), 'repeat'); ?>><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat-x" <?php selected($base->getVar($skin['params'], 'cover-background-repeat', 'no-repeat'), 'repeat-x'); ?>><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="repeat-y" <?php selected($base->getVar($skin['params'], 'cover-background-repeat', 'no-repeat'), 'repeat-y'); ?>><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div><div class="clear"></div>
								</div>
								<div style="display:none">
									<?php
									$cover_image_url = false;
									$cover_image_id = $base->getVar($skin['params'], 'cover-background-image', '0', 'i');
									if($cover_image_id > 0){
										$cover_image_url = wp_get_attachment_image_src($cover_image_id, 'full');
									}
									?>
									<input type="hidden" value="<?php echo $base->getVar($skin['params'], 'cover-background-image', '0', 'i'); ?>" name="cover-background-image">
									<input type="hidden" value="<?php echo ($cover_image_url !== false) ? $cover_image_url[0] : ''; ?>" name="cover-background-image-url">
									<div id="cover-background-image-wrap"<?php echo ($cover_image_url !== false) ? ' style="background-image: url('.$cover_image_url[0].'); background-size: 100% 100%;"' : ''; ?>><?php _e("Click to<br>Select<br>Image", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
									<i class="adamlabsgallery-icon-trash" id="remove-cover-background-image-wrap"><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></i>
								</div>

							</div>

							<!-- SPACES -->
							<div id="adamlabsgallery-lc-spaces" class="adamlabsgallery-lc-settings-container">
								<ul class="adamlabsgallery-submenu" style="width:100%;margin:-18px 0px 0px -20px;padding:0px;height:50px;vertical-align:bottom">
									<li data-toshow="adamlabsgallery-style-full" class="selected-submenu-setting adamlabsgallery-tooltip-wrap" title="<?php _e('Padding and border of the full item', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float:left; margin-top:10px; padding:7px 10px;"><i class="adamlabsgallery-icon-stop"></i><?php _e('Full Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
									<li data-toshow="adamlabsgallery-style-content" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding and border of the Fixed Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float:left; margin-top:10px; padding:7px 10px;"><i class="adamlabsgallery-icon-doc-text"></i><?php _e('Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
									<div class="clear"></div>
								</ul>
								<div class="clear"></div>
								<!-- FULL STYLING -->
								<div id="adamlabsgallery-style-full">
									<!-- THE PADDING, BORDER AND BG COLOR -->
									<div style="margin-top:15px">
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Background Color of Full Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Item BG Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" name="full-bg-color" type="text" id="full-bg-color" value="<?php echo $base->getVar($skin['params'], 'full-bg-color', '#ffffff'); ?>">
									</div>
									<p>
										<?php
										$padding = $base->getVar($skin['params'], 'full-padding');
										?>
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top,Right,Bottom,Left Padding of Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Item Paddings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="text" name="full-padding[]" value="<?php echo (isset($padding[0])) ? $padding[0] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-padding[]" value="<?php echo (isset($padding[1])) ? $padding[1] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-padding[]" value="<?php echo (isset($padding[2])) ? $padding[2] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-padding[]" value="<?php echo (isset($padding[3])) ? $padding[3] : 0; ?>" /> px
									</p>
									<p>
										<?php
										$border = $base->getVar($skin['params'], 'full-border');
										?>
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top,Right,Bottom,Left Border of Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Item Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="text" name="full-border[]" value="<?php echo (isset($border[0])) ? $border[0] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-border[]" value="<?php echo (isset($border[1])) ? $border[1] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-border[]" value="<?php echo (isset($border[2])) ? $border[2] : 0; ?>" />
										<input class="input-settings-small element-setting" type="text" name="full-border[]" value="<?php echo (isset($border[3])) ? $border[3] : 0; ?>" /> px
									</p>
									<div style="margin-top:10px">
										<?php
										$radius = $base->getVar($skin['params'], 'full-border-radius');
										?>
										<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top Left,Top Right,Bottom Right, Bottom Left Border Radius of Item', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Border Radius', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input style="float: left; margin-left: 3px" class="input-settings-small element-setting firstinput" type="text" name="full-border-radius[]" value="<?php echo (isset($radius[0])) ? $radius[0] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="full-border-radius[]" value="<?php echo (isset($radius[1])) ? $radius[1] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="full-border-radius[]" value="<?php echo (isset($radius[2])) ? $radius[2] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="full-border-radius[]" value="<?php echo (isset($radius[3])) ? $radius[3] : 0; ?>" />
										
										<div class="select_wrapper" style="float:left; width:40px;margin-left:15px; margin-top:1px">
											<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="full-border-radius-type">
												<option value="px" <?php selected($base->getVar($skin['params'], 'full-border-radius-type', 'px'), 'px'); ?>>px</option>
												<option value="%" <?php selected($base->getVar($skin['params'], 'full-border-radius-type', 'px'), '%'); ?>>%</option>
											</select>
										</div><div class="clear"></div>
										
									</div>
									<p>
										<label><?php _e('Border Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting"  name="full-border-color" type="text" id="full-border-color" value="<?php echo $base->getVar($skin['params'], 'full-border-color', 'transparent'); ?>" data-mode="single">
									</p>
									<div style="margin-top:10px">
										<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Border Line Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Border Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="full-border-style">
												<option value="none" <?php selected($base->getVar($skin['params'], 'full-border-style', 'none'), 'none'); ?>><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="solid" <?php selected($base->getVar($skin['params'], 'full-border-style', 'none'), 'solid'); ?>><?php _e('solid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="dotted" <?php selected($base->getVar($skin['params'], 'full-border-style', 'none'), 'dotted'); ?>><?php _e('dotted', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="dashed" <?php selected($base->getVar($skin['params'], 'full-border-style', 'none'), 'dashed'); ?>><?php _e('dashed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="double" <?php selected($base->getVar($skin['params'], 'full-border-style', 'none'), 'double'); ?>><?php _e('double', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div>
										<div class="clear"></div>
									</div>
									<p>
										<label><?php _e('Overflow Hidden', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input type="radio" name="full-overflow-hidden" value="true" class="firstinput" <?php checked($base->getVar($skin['params'], 'full-overflow-hidden', 'false'), 'true'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Hide Overflow (fix border radius issues)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										<input type="radio" name="full-overflow-hidden" value="false" <?php checked($base->getVar($skin['params'], 'full-overflow-hidden', 'false'), 'false'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show Overflowed content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</p>
								</div>
								<div id="adamlabsgallery-style-content" style="display:none">
									<!-- THE PADDING, BORDER AND BG COLOR -->
									<div style="margin-top:15px">
										<label><?php _e('Content BG Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" name="content-bg-color" type="text" id="content-bg-color" value="<?php echo $base->getVar($skin['params'], 'content-bg-color', '#ffffff'); ?>">
									</div>
									<p>
										<?php
										$padding = $base->getVar($skin['params'], 'content-padding');
										?>
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top, Right, Bottom, Left Padding of Fix Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Paddings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="number" name="content-padding[]" value="<?php echo (isset($padding[0])) ? $padding[0] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-padding[]" value="<?php echo (isset($padding[1])) ? $padding[1] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-padding[]" value="<?php echo (isset($padding[2])) ? $padding[2] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-padding[]" value="<?php echo (isset($padding[3])) ? $padding[3] : 0; ?>" /> px
										
									</p>
									<p>
										<?php
										$border = $base->getVar($skin['params'], 'content-border');
										?>
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top, Right, Bottom, Left Padding of Fix Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="number" name="content-border[]" value="<?php echo (isset($border[0])) ? $border[0] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-border[]" value="<?php echo (isset($border[1])) ? $border[1] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-border[]" value="<?php echo (isset($border[2])) ? $border[2] : 0; ?>" />
										<input class="input-settings-small element-setting" type="number" name="content-border[]" value="<?php echo (isset($border[3])) ? $border[3] : 0; ?>" /> px
									</p>
									<div style="margin-top:10px">
										<?php
										$radius = $base->getVar($skin['params'], 'content-border-radius');
										?>
										<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top Left, Top Right, Bottom Right, Bottom Left Border Radius of Fix Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Border Radius', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input style="float: left; margin-left: 3px" class="input-settings-small element-setting firstinput" type="text" name="content-border-radius[]" value="<?php echo (isset($radius[0])) ? $radius[0] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="content-border-radius[]" value="<?php echo (isset($radius[1])) ? $radius[1] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="content-border-radius[]" value="<?php echo (isset($radius[2])) ? $radius[2] : 0; ?>" />
										<input style="float: left" class="input-settings-small element-setting" type="text" name="content-border-radius[]" value="<?php echo (isset($radius[3])) ? $radius[3] : 0; ?>" />
										
										<div class="select_wrapper" style="float:left; width:40px;margin-left:15px; margin-top: 1px">
											<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="content-border-radius-type">
												<option value="px" <?php selected($base->getVar($skin['params'], 'content-border-radius-type', 'px'), 'px'); ?>>px</option>
												<option value="%" <?php selected($base->getVar($skin['params'], 'content-border-radius-type', 'px'), '%'); ?>>%</option>
											</select>
										</div><div class="clear"></div>
										
									</div>
									<p>
										<label><?php _e('Border Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" name="content-border-color" type="text" id="content-border-color" value="<?php echo $base->getVar($skin['params'], 'content-border-color', 'transparent'); ?>" data-mode="single">
									</p>
									<div style="margin-top:10px">
										<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Border Line Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float:left"><?php _e('Border Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="content-border-style">
												<option value="none" <?php selected($base->getVar($skin['params'], 'content-border-style', 'none'), 'none'); ?>><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="solid" <?php selected($base->getVar($skin['params'], 'content-border-style', 'none'), 'solid'); ?>><?php _e('solid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="dotted" <?php selected($base->getVar($skin['params'], 'content-border-style', 'none'), 'dotted'); ?>><?php _e('dotted', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="dashed" <?php selected($base->getVar($skin['params'], 'content-border-style', 'none'), 'dashed'); ?>><?php _e('dashed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="double" <?php selected($base->getVar($skin['params'], 'content-border-style', 'none'), 'double'); ?>><?php _e('double', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>

							<!-- THE CONTENT SHADOW SETTINGS -->
							<div id="adamlabsgallery-lc-content-shadow" class="adamlabsgallery-lc-settings-container ">
								<div style="margin-top:15px">
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Shadow of Element(s)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float:left"><?php _e('Use Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<?php
										$shadow_type = $base->getVar($skin['params'], 'all-shadow-used', 'none');
										?>
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select id="all-shadow-used" name="all-shadow-used">
											<option<?php selected($shadow_type, 'none'); ?> value="none"><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option<?php selected($shadow_type, 'cover'); ?> value="cover"><?php _e('cover (inset)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option<?php selected($shadow_type, 'media'); ?> value="media"><?php _e('media', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option<?php selected($shadow_type, 'content'); ?> value="content"><?php _e('content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option<?php selected($shadow_type, 'both'); ?> value="both"><?php _e('media/content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
									<div class="clear"></div>
								</div>
								
								<p>
									<label><?php _e('Shadow Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input class="element-setting" name="content-shadow-color" type="text" id="content-shadow-color" value="<?php echo $base->getVar($skin['params'], 'content-shadow-color', '#000000'); ?>" data-mode="single">
								</p>
								
								<?php /*
								<p>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Shadow Opacity', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Shadow Alpha', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<span id="content-shadow-alpha" class="slider-settings adamlabsgallery-tooltip-wrap" title="<?php _e('Shadow Opacity', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"></span>
									<input class="input-settings-small element-setting" type="text" name="content-shadow-alpha" value="<?php echo $base->getVar($skin['params'], 'content-shadow-alpha', '100', 'i'); ?>" />
								</p>
								*/ ?>
								<p>
									<?php
									$shadow = $base->getVar($skin['params'], 'content-box-shadow');
									?>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Position of horizontal shadow(Negative values possible)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('blur distance', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('size of shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input class="input-settings-small element-setting firstinput" type="text" name="content-box-shadow[]" value="<?php echo (isset($shadow[0])) ? $shadow[0] : 0; ?>" />
									<input class="input-settings-small element-setting" type="text" name="content-box-shadow[]" value="<?php echo (isset($shadow[1])) ? $shadow[1] : 0; ?>" />
									<input class="input-settings-small element-setting" type="text" name="content-box-shadow[]" value="<?php echo (isset($shadow[2])) ? $shadow[2] : 0; ?>" />
									<input class="input-settings-small element-setting" type="text" name="content-box-shadow[]" value="<?php echo (isset($shadow[3])) ? $shadow[3] : 0; ?>" /> px
								</p>
								
								<p id="content-box-shadow-inset">
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Display the shadow inside the container', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Inset Style</label>
									<input type="checkbox" id="content-shadow-inset" name="content-box-shadow-inset" <?php checked($base->getVar($skin['params'], 'content-box-shadow-inset', 'false'), 'true'); ?>>
								</p>
								
								<p id="content-box-shadow-hover">
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate the Shadow on Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Animate onHover</label>
									<input type="checkbox" name="content-box-shadow-hover" <?php checked($base->getVar($skin['params'], 'content-box-shadow-hover', 'false'), 'true'); ?>>
								</p>

								<p><div style="position:absolute;bottom:10px;text-align:center"><i style="font-size:10px;color:#777"><?php _e('The Container under or over the Media Container.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></i></div></p>
							</div>

							<!-- THE CONTENT ANIMATION SETTINGS -->
							<div id="adamlabsgallery-lc-content-animation" class="adamlabsgallery-lc-settings-container ">
								<!-- COVER ANIMATION -->
								<div  style="margin-top:15px">
									<label style="float:left; width:150px" class="adamlabsgallery-cover-setter"><?php _e('Cover Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div style="float:left">

										<div id="adamlabsgallery-cover-animation-top">
											<span style="padding-right:23px; margin-right:10px;float:left;"><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
											<input class="element-setting cover-animation-color" type="hidden" data-mode="single" name="cover-animation-color-top" id="cover-animation-color-top" value="<?php echo $base->getVar($skin['params'], 'cover-animation-color-top', '#FFFFFF', 's'); ?>" />
											<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" title="<?php _e('Delay before the Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" type="text" name="cover-animation-delay-top" value="<?php echo $base->getVar($skin['params'], 'cover-animation-delay-top', '0', 'i'); ?>" />
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-duration-top" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
													<option value="default" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), 'default'); ?>>default</option>
													<option value="200" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '200'); ?>>200</option>
													<option value="300" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '300'); ?>>300</option>
													<option value="400" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '400'); ?>>400</option>
													<option value="500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '500'); ?>>500</option>
													<option value="750" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '750'); ?>>750</option>
													<option value="1000" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '1000'); ?>>1000</option>
													<option value="1500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-top', 'default'), '1500'); ?>>1500</option>
												</select>
											</div>
											
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-top-type" class=" title="<?php _e('Show or Hide on hover. In = Show on Hover, Out = Hide on hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="width: 60px;">
													<option value="" <?php selected($base->getVar($skin['params'], 'cover-animation-top-type', ''), ''); ?>><?php echo _e('in', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="out" <?php selected($base->getVar($skin['params'], 'cover-animation-top-type', ''), 'out'); ?>><?php echo _e('out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div>
											<div class="select_wrapper" style="float:right;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Effect on Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select class="cover-animation-select" name="cover-animation-top">
													<?php
													foreach($transitions_cover as $handle => $name){
														?>
														<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'cover-animation-top', 'fade'), $handle); ?>><?php echo $name; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
										<div style="clear:both"></div>
										<div id="adamlabsgallery-cover-animation-center">
											<span id="adamlabsgallery-cover-animation-center-hide" style="float:left;"><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
											<input class="element-setting cover-animation-color" type="hidden" data-mode="single" name="cover-animation-color-center" id="cover-animation-color-center" value="<?php echo $base->getVar($skin['params'], 'cover-animation-color-center', '#FFFFFF', 's'); ?>" />
											<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" title="<?php _e('Delay before the Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" type="text" name="cover-animation-delay-center" value="<?php echo $base->getVar($skin['params'], 'cover-animation-delay-center', '0', 'i'); ?>" />
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-duration-center" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
													<option value="default" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), 'default'); ?>>default</option>
													<option value="200" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '200'); ?>>200</option>
													<option value="300" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '300'); ?>>300</option>
													<option value="400" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '400'); ?>>400</option>
													<option value="500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '500'); ?>>500</option>
													<option value="750" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '750'); ?>>750</option>
													<option value="1000" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '1000'); ?>>1000</option>
													<option value="1500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-center', 'default'), '1500'); ?>>1500</option>
												</select>
											</div>
											
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-center-type" class="adamlabsgallery-tooltip-wrap" style="width: 60px;" title="<?php _e('Show or Hide on hover. In = Show on Hover, Out = Hide on hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
													<option value="" <?php selected($base->getVar($skin['params'], 'cover-animation-center-type', ''), ''); ?>><?php echo _e('in', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="out" <?php selected($base->getVar($skin['params'], 'cover-animation-center-type', ''), 'out'); ?>><?php echo _e('out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div>

											<div class="select_wrapper" style="float:right;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Effect on Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select class="cover-animation-select" name="cover-animation-center">
													<?php
													foreach($transitions_cover as $handle => $name){
													?>
													<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'cover-animation-center', 'fade'), $handle); ?>><?php echo $name; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div style="clear:both"></div>
										<div id="adamlabsgallery-cover-animation-bottom">
											<span style="margin-right:10px;float:left"><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
											<input class="element-setting cover-animation-color" type="hidden" data-mode="single" name="cover-animation-color-bottom" id="cover-animation-color-bottom" value="<?php echo $base->getVar($skin['params'], 'cover-animation-color-bottom', '#FFFFFF', 's'); ?>" />
											<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" title="<?php _e('Delay before the Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" type="text" name="cover-animation-delay-bottom" value="<?php echo $base->getVar($skin['params'], 'cover-animation-delay-bottom', '0', 'i'); ?>" />
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-duration-bottom" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
													<option value="default" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), 'default'); ?>>default</option>
													<option value="200" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '200'); ?>>200</option>
													<option value="300" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '300'); ?>>300</option>
													<option value="400" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '400'); ?>>400</option>
													<option value="500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '500'); ?>>500</option>
													<option value="750" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '750'); ?>>750</option>
													<option value="1000" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '1000'); ?>>1000</option>
													<option value="1500" <?php selected($base->getVar($skin['params'], 'cover-animation-duration-bottom', 'default'), '1500'); ?>>1500</option>
												</select>
											</div>
											
											<div class="select_wrapper" style="float:right;">
												<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="cover-animation-bottom-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show or Hide on hover. In = Show on Hover, Out = Hide on hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="width: 60px;">
													<option value="" <?php selected($base->getVar($skin['params'], 'cover-animation-bottom-type', ''), ''); ?>><?php echo _e('in', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="out" <?php selected($base->getVar($skin['params'], 'cover-animation-bottom-type', ''), 'out'); ?>><?php echo _e('out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div>
											<div class="select_wrapper" style="float:right;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Effect on Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select class="cover-animation-select" name="cover-animation-bottom">
													<?php
													foreach($transitions_cover as $handle => $name){
													?>
													<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'cover-animation-bottom', 'fade'), $handle); ?>><?php echo $name; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								
								<!-- GROUP ANIMATION -->
								<div style="margin-top:15px">
									<label style="float:left; width:150px" class="adamlabsgallery-group-setter adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Effect on Cover and on All Cover elements Grouped. This will not replace the Animation but add a global animation extra.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Group Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="cover-group-animation">
											<?php
											foreach($transitions_cover as $handle => $name){
												if(preg_match('/collapse|line|circle|spiral/', $handle)) continue;
											?>
											<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'cover-group-animation', 'none'), $handle); ?>><?php echo $name; ?></option>
											<?php
											}
											?>
										</select>
									</div>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="cover-group-animation-duration" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<option value="default" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), 'default'); ?>>default</option>
											<option value="200" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '200'); ?>>200</option>
											<option value="300" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '300'); ?>>300</option>
											<option value="400" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '400'); ?>>400</option>
											<option value="500" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '500'); ?>>500</option>
											<option value="750" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '750'); ?>>750</option>
											<option value="1000" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '1000'); ?>>1000</option>
											<option value="1500" <?php selected($base->getVar($skin['params'], 'cover-group-animation-duration', 'default'), '1500'); ?>>1500</option>
										</select>
									</div>
									<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" style="float: left" type="text" name="cover-group-animation-delay" title="<?php _e('Delay before the Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($skin['params'], 'cover-group-animation-delay', '0', 'i'); ?>" />
									<div class="clear"></div>
								</div>
								
								<!-- MEDIA ANIMATION -->
								<div style="margin-top:15px">
									<label style="float:left; width:150px;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation of Media on Hover. All Media animation hide, or partly hide the Media on hover.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Media Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select id="media-animation" name="media-animation">
											<?php
											foreach($transitions_media as $handle => $name){
												?>
												<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'media-animation', 'fade'), $handle); ?>><?php echo $name; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div id="media-animation-blur" class="select_wrapper" style="float:left;">
										<div class="select_fake" style="width: 50px"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="media-animation-blur" class="adamlabsgallery-tooltip-wrap" style="width: 70px" title="<?php _e('Blur Amount', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<option value="2">2px</option>
											<option value="3">3px</option>
											<option value="4">4px</option>
											<option value="5" selected>5px</option>
											<option value="10">10px</option>
											<option value="15">15px</option>
											<option value="20">20px</option>
										</select>
									</div>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="media-animation-duration" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<option value="default" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), 'default'); ?>>default</option>
											<option value="200" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '200'); ?>>200</option>
											<option value="300" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '300'); ?>>300</option>
											<option value="400" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '400'); ?>>400</option>
											<option value="500" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '500'); ?>>500</option>
											<option value="750" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '750'); ?>>750</option>
											<option value="1000" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '1000'); ?>>1000</option>
											<option value="1500" <?php selected($base->getVar($skin['params'], 'media-animation-duration', 'default'), '1500'); ?>>1500</option>
										</select>
									</div>
									<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" style="float: left" type="text" name="media-animation-delay" title="<?php _e('Delay before the Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($skin['params'], 'media-animation-delay', '0', 'i'); ?>" />
									<div class="clear"></div>
								</div>
								
								<!-- 2.1.6 -->
								<!-- SHOW ALTERNATIVE IMAGE ON HOVER -->
								<?php 
									$hoverImg = $base->getVar($skin['params'], 'element-hover-image', '');
									$hoverImg = !empty($hoverImg) && $hoverImg !== 'false' ? ' checked' : '';
								?>
								<div style="margin-top:15px; line-height: 25px">
									<label style="float:left; width:150px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the item\'s Alternative Image on mouse hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Alt Image on Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="checkbox" name="element-hover-image" id="element-hover-image" class="element-setting"<?php echo $hoverImg; ?> />	
								</div>
								
								<!-- ALTERNATIVE IMAGE ANIMATION -->
								<?php
									$hoverImgActive = empty($hoverImg) ? 'none' : 'block';
								?>
								<div id="adamlabsgallery-hover-img-animation" style="margin-top:15px; display: <?php echo $hoverImgActive; ?>">
									<label style="float:left; width:150px;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation of Alt Image on Hover.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Alt Image Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="hover-image-animation">
											<?php

											foreach($transitions_hover as $handle => $name){
												?>
												<option value="<?php echo $handle; ?>" <?php selected($base->getVar($skin['params'], 'hover-image-animation', 'fade'), $handle); ?>><?php echo $name; ?></option>
												<?php
											}
											?>
										</select>
									</div>
										
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<select name="hover-image-animation-duration" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<option value="default" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), 'default'); ?>>default</option>
											<option value="200" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '200'); ?>>200</option>
											<option value="300" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '300'); ?>>300</option>
											<option value="400" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '400'); ?>>400</option>
											<option value="500" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '500'); ?>>500</option>
											<option value="750" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '750'); ?>>750</option>
											<option value="1000" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '1000'); ?>>1000</option>
											<option value="1500" <?php selected($base->getVar($skin['params'], 'hover-image-animation-duration', 'default'), '1500'); ?>>1500</option>
										</select>
									</div>
									<input class="input-settings-small element-setting adamlabsgallery-tooltip-wrap input-animation-delay" style="float: left" type="text" name="hover-image-animation-delay" value="<?php echo $base->getVar($skin['params'], 'hover-image-animation-delay', '0', 'i'); ?>" />
									<div class="clear"></div>
								</div>
							</div>

							<!-- GENERAL LINK/SEO SETTINGS -->
							<div id="adamlabsgallery-lc-content-link-seo" class="adamlabsgallery-lc-settings-container">
								<div style="margin-top:15px">
									<label style="float:left; width:150px;" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose where the following link should be appended to.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Add Link To', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<div class="select_wrapper" style="float:left;">
										<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
										<?php $link_set_to = $base->getVar($skin['params'], 'link-set-to', 'none'); ?>
										<select name="link-set-to">
											<option value="none" <?php selected($link_set_to, 'none'); ?>><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="media" <?php selected($link_set_to, 'media'); ?>><?php _e('Media', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="cover" <?php selected($link_set_to, 'cover'); ?>><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</div>
									<div class="clear"></div>
									<div class="add-link-to-wrapper" style="display: none;">
										<div style="margin-top:15px">
											<label style="float:left"><?php _e('Link To', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<?php $link_link_type = $base->getVar($skin['params'], 'link-link-type', 'none'); ?>
												<select name="link-link-type">
													<option <?php selected($link_link_type, 'none'); ?> value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'post'); ?> value="post"><?php _e('Post', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'url'); ?> value="url"><?php _e('URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'meta'); ?> value="meta"><?php _e('Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'javascript'); ?> value="javascript"><?php _e('JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'lightbox'); ?> value="lightbox"><?php _e('Lightbox', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_link_type, 'ajax'); ?> value="ajax"><?php _e('Ajax', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<p id="adamlabsgallery-link-post-url-wrap" style="display: none;">
											<label><?php _e('Link To URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input class="element-setting" type="text" name="link-url-link" value="<?php echo $base->getVar($skin['params'], 'link-url-link', ''); ?>" />
										</p>
										<p id="adamlabsgallery-link-post-meta-wrap" style="display: none;">
											<label><?php _e('Meta Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input class="element-setting" type="text" name="link-meta-link" value="<?php echo $base->getVar($skin['params'], 'link-meta-link', ''); ?>" />
											<a class="button-secondary" id="button-open-link-link-meta-key" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
										</p>
										<p id="adamlabsgallery-link-post-javascript-wrap" style="display: none;">
											<label><?php _e('Link JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input class="element-setting" type="text" name="link-javascript-link" value="<?php echo $base->getVar($skin['params'], 'link-javascript-link', ''); ?>" />
										</p>
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<?php $link_target = $base->getVar($skin['params'], 'link-target', '_self'); ?>
												<select name="link-target">
													<option <?php selected($link_target, 'disabled'); ?> value="disabled"><?php _e('disabled', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_target, '_self'); ?> value="_self"><?php _e('_self', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_target, '_blank'); ?> value="_blank"><?php _e('_blank', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_target, '_parent'); ?> value="_parent"><?php _e('_parent', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option <?php selected($link_target, '_top'); ?> value="_top"><?php _e('_top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</form>

			<!--
			ELEMENT EDITOR
			-->
			<div class="postbox adamlabsgallery-postbox " style="" id="adamlabsgallery-layersettings-box-wrapper">
				<h3 style="padding:12px;" id="layer-settings-header" class="box-closed">
					<span class="adamlabsgallery-element-setter adamlabsgallery-tor-250"><span id="adamlabsgallery-ls-smalllsicon" style="padding:0px"><i style="background-color:#8e44ad; padding:3px; margin-right:10px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;color:#fff;"  class="adamlabsgallery-icon-star"></i></span>
					<?php _e('Layer Settings - ', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
					<div class="postbox-arrow"></div>
					<div class="clear"></div>
				</h3>
				<h3 style="background:none; cursor:default !important; position:absolute;height:23px;width:100%; max-width:370px;left:165px;top:5px;border:none !important; overflow:hidden;" >

					<div class="select_wrapper" style="float:left;margin-left:5px;">
						<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
						<select id="element-settings-current-name"></select>
					</div>
					<span style="font-size:10px;font-weight:600;cursor:text;-webkit-touch-callout: all;-webkit-user-select: all;-khtml-user-select: all;-moz-user-select: all;-ms-user-select: all;user-select: all;" class="adamlabsgallery-element-class-setter"></span>
				</h3>
				<div class="inside" style="padding:0px;margin:0px;height:100%;display:none">
					<div id="element-setting-wrap-top" style="display:none">
						<form id="adamlabsgallery-item-element-settings-wrap">
							<div id="settings-dz-elements-wrapper" class="adamlabsgallery-ul-tabs">
								<ul>
									<li class="selected-el-setting adamlabsgallery-source-li"><a href="#adamlabsgallery-element-source"><i class="adamlabsgallery-icon-folder-open-empty" style="margin-right:5px;"></i><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
									<li class="adamlabsgallery-hide-on-special adamlabsgallery-hide-on-blank-element"><a href="#adamlabsgallery-element-style"><i class="adamlabsgallery-icon-droplet" style="margin-right:5px;"></i><?php _e('Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
									<li><a href="#adamlabsgallery-element-hide"><i class="adamlabsgallery-icon-tablet" style="margin-right:5px;"></i><?php _e('Show/Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
									<li><a href="#adamlabsgallery-element-animation"><i class="adamlabsgallery-icon-gamepad" style="margin-right:5px;"></i><?php _e('Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
									<li class="adamlabsgallery-hide-on-special adamlabsgallery-hide-on-blank-element"><a href="#adamlabsgallery-element-link"><i class="adamlabsgallery-icon-link" style="margin-right:5px;"></i><?php _e('Link/SEO', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
								</ul>
								<!--
								SOURCE
								-->
								<div id="adamlabsgallery-element-source">
									<div id="dz-source">
										<div style="margin-top:10px" class="adamlabsgallery-hide-on-special adamlabsgallery-hide-on-blank-element">
											<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select The Source of this Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-source" style="width:180px">
													<?php
													foreach($element_type as $el_cat => $el_type){
													?>
													<option value="<?php echo $el_cat; ?>"><?php echo ucwords($el_cat); ?></option>
													<?php
													}
													?>
													<option value="icon"><?php _e('Icon', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="text"><?php _e('Text/HTML', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div>
											<div class="clear"></div>
										</div>

										<div style="margin-top:10px">
											<div id="adamlabsgallery-source-element-drops" class="adamlabsgallery-hide-on-special adamlabsgallery-hide-on-blank-element">
												<!-- DROP DOWNS FOR ELEMENTS -->
												<label style="float:left"><?php _e('Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<?php
													foreach($element_type as $el_cat => $el_type){
														?>
														<div class="select_wrapper adamlabsgallery-tooltip-wrap" title="<?php _e('Narrow down your selection', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-source-<?php echo $el_cat; ?>" style="width:180px" class="elements-select-wrap">
																<?php
																foreach($el_type as $ty_name => $ty_values){
																	?><option value="<?php echo $ty_name; ?>"><?php echo $ty_values['name']; ?></option><?php
																}
																?>
															</select>
														</div>
														<?php
													}
													?>
												<div class="clear"></div>

												<!-- CAT & TAG SEPERATOR -->
												<div id="adamlabsgallery-source-seperate-wrap" class="adamlabsgallery-cat-tag-settings" style="margin-top:10px;line-height:25px">
													<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Separator Char in the Listed element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Separate By', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input type="text" value="" name="element-source-separate" class="input-settings-small element-setting firstinput">
													<div class="clear"></div>
												</div>
												
												<!-- CAT & TAG MAX -->
												<div id="adamlabsgallery-source-catmax-wrap" class="adamlabsgallery-cat-tag-settings" style="margin-top:10px;line-height:25px">
													<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Max Categories/Tags to show (use -1 for unlimited)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Max Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input type="text" value="" name="element-source-catmax" class="input-settings-small element-setting firstinput">
													<div class="clear"></div>
												</div>

												<!-- CAT & TAG CHOOSE TYPE -->
												<div id="adamlabsgallery-source-functonality-wrap" style="margin-top:10px;line-height:25px">
													<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Narrow down your selection', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On Click', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-source-function" style="width:180px" class="elements-select-wrap">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="link"><?php _e('Link', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="filter"><?php _e('Trigger Filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div>
													<div class="clear"></div>
												</div>

												<!-- CHOOSE TAX -->
												<div id="adamlabsgallery-source-taxonomy-wrap" class="adamlabsgallery-layer-toolbar-box" style="margin-top:10px;line-height:25px">
													<label style="float: left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose from all Taxonomies available', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Taxonomy', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float: left">
														<div class="select_fake"><span><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-arrow-combo"></i></div>
														<select name="element-source-taxonomy">
															<?php
																$args = array(
																  'public'   => true
																); 
																$taxonomies = get_taxonomies($args,'objects'); 
																foreach ($taxonomies as $taxonomy_name => $taxonomy) {
																	echo '<option value="'.$taxonomy_name.'">'.$taxonomy->labels->name.'</option>';
																}
															?>
														</select>
													</div>
													<div class="clear"></div>
												</div>


												<!-- META TAG -->
												<div id="adamlabsgallery-source-meta-wrap" style="margin-top:10px;line-height:25px">
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('The Handle or ID of Meta Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float: left"><?php _e('Meta Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input type="text" value="" name="element-source-meta" class="input-settings element-setting firstinput">
													<a href="javascript:void(0);" id="button-open-meta-key" class="button-secondary"><i class="adamlabsgallery-icon-down-open"></i></a>
													<div class="clear"></div>
												</div>

												<!-- WORD LIMITATION -->
												<div id="adamlabsgallery-source-limit-wrap" style="margin-top:10px;line-height:25px">
													<label style="float: left"><?php _e('Limit By', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>

													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Text Length Limitation calculated based on...', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>

														<select name="element-limit-type">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="words"><?php _e('Words', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="chars"><?php _e('Characters', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="sentence"><?php _e('End Sentence Words', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div>
													
													<input style="float:left; margin-left:10px;" type="text" value="" name="element-limit-num" class="input-settings-small element-setting firstinput">
													<div class="clear"></div>
													
													<!-- 2.2.6 -->
													<div style="margin-top:10px;line-height:25px">
														<label style="float: left"><?php _e('Min Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<input style="float:left" type="text" value="0" name="element-min-height" class="input-settings-small element-setting firstinput adamlabsgallery-tooltip-wrap" title="<?php _e('Optional CSS min-height (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
														<div class="clear"></div>
													</div>
													
													<div style="margin-top:10px;line-height:25px">
														<label style="float: left"><?php _e('Max Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<input style="float:left" type="text" value="none" name="element-max-height" class="input-settings-small element-setting firstinput adamlabsgallery-tooltip-wrap" title="<?php _e("Optional CSS max-height (px). Enter 'none' for no max-height", ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
														<div class="clear"></div>
													</div>
													
												</div>
												
											</div>

											<!-- ICON SELECTOR -->
											<div id="adamlabsgallery-source-icon-wrap" class="elements-select-wrap" style="margin-left:150px; width:100%;">
												<div id="show-fontello-dialog">
													<div id="adamlabsgallery-preview-icon"></div>
												</div>
												<div style="float:left; margin-left:10px; margin-top:15px;font-size:11px;font-style:italic;color:#777;"><?php _e('Click on the Field to change the Icon', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>

												<input type="hidden" value="" name="element-source-icon" />
											</div>

											<!-- HTML TEXT SOURCE -->
											<div id="adamlabsgallery-source-text-style-disable-wrap" class="elements-select-wrap adamlabsgallery-hide-on-special adamlabsgallery-hide-on-blank-element">
												<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Stylings will not be written', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" style="float: left"><?php _e('Disable Styling', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
												<div style="float: left; margin-top: 5px;"><input type="checkbox"  name="element-source-text-style-disable" value="on" class="input-settings element-setting firstinput"></div>
											</div>

											<div style="clear: both;"></div>

											<div id="adamlabsgallery-source-text-wrap" class="elements-select-wrap" style="margin-left:150px;">
												<textarea name="element-source-text" style="width:350px;height:150px"></textarea>
												<div style="font-size:10px;color:#777"><?php _e('Use %meta-key-handle% to insert meta data. Most can be found ', ADAMLABS_GALLERY_TEXTDOMAIN); ?><a href="javascript:void(0);" id="adamlabsgallery-show-meta-keys-dialog"><?php _e('here', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </a></div>
											</div>

										</div>
									</div>
								</div>

								<!--
								STYLING
								-->
								<div id="adamlabsgallery-element-style" style="position:relative; height: 470px">
									<div id="adamlabsgallery-styling-idle-hover-tab" class="adamlabsgallery-ul-tabs">
										<ul class="adamlabsgallery-submenu">
											<li class="selected-submenu-setting adamlabsgallery-tooltip-wrap" title="<?php _e('Style of Element in Idle State', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" data-toshow="adamlabsgallery-style-idle"><i class="adamlabsgallery-icon-star-empty"></i><?php _e('Idle', ADAMLABS_GALLERY_TEXTDOMAIN); ?></li>
											<li class="adamlabsgallery-tooltip-wrap" title="<?php _e('Style of Element in Hover state (only if Hover Box Checked)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" data-toshow="adamlabsgallery-style-hover"><i class="adamlabsgallery-icon-star"></i><?php _e('Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <input style="margin-left:5px" type="checkbox" name="element-enable-hover" /></li>
											<div class="clear"></div>
										</ul>
										<div class="clear"></div>
										<!-- IDLE STYLING -->
										<div id="adamlabsgallery-style-idle">
											<div class="adamlabsgallery-small-vertical-menu" style="height: 420px">
												<ul>
													<li class="selected-el-setting" data-toshow="adamlabsgallery-el-font"><i class="adamlabsgallery-icon-font" ></i><p><?php _e('Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-pos"><i class="adamlabsgallery-icon-align-left"></i><p><?php _e('Spacing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-border"><i class="adamlabsgallery-icon-minus-squared-alt"></i><p><?php _e('Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-bg"><i class="adamlabsgallery-icon-picture-1"></i><p><?php _e('BG', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-shadow"><i class="adamlabsgallery-icon-picture"></i><p><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
												</ul>
											</div>
											<!--
											FONT
											-->
											<div id="adamlabsgallery-el-font" class="adamlabsgallery-el-settings-container active-esc">
												<p>
													<label><?php _e('Font Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-font-size" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-font-size" value="6" /> px
												</p>
												<p>
													<label><?php _e('Line Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-line-height" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-line-height" value="8" /> px
												</p>
												<p>
													<label><?php _e('Font Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-color" type="text" id="element-color" value="" data-mode="single">
												</p>
												<p>
													<label><?php _e('Font Family', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label
													><input class="element-setting" name="element-font-family" type="text" value=""> <a class="button-secondary" id="button-open-font-family" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Font Weight', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">

														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-font-weight">
															<option value="400"><?php _e('400', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="100"><?php _e('100', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="200"><?php _e('200', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="300"><?php _e('300', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="500"><?php _e('500', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="600"><?php _e('600', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="700"><?php _e('700', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="800"><?php _e('800', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="900"><?php _e('900', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Text Decoration', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-text-decoration">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="underline"><?php _e('Underline', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="overline"><?php _e('Overline', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="line-through"><?php _e('Line Through', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Font Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input type="checkbox" name="element-font-style" value="italic" /> <?php _e('Italic', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Text Transform', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-text-transform">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="capitalize"><?php _e('Capitalize', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="uppercase"><?php _e('Uppercase', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="lowercase"><?php _e('Lowercase', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Letter Spacing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label
													><input type="text" class="letter-spacing" style="width: 65px" name="element-letter-spacing" value="normal">
												</p>
												<div class="drop-to-stylechange adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Element from Available Layers here to overwrite Styling of Current Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Drop for<br>Style<br>Change", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											POSITION
											-->
											<div id="adamlabsgallery-el-pos" class="adamlabsgallery-el-settings-container">
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Position', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-position">
															<option value="relative"><?php _e('Relative', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="absolute"><?php _e('Absolute', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<div id="adamlabsgallery-show-on-absolute">
													<div style="margin-top:10px">
														<label style="float:left"><?php _e('Align', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<div class="select_wrapper" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-align">
																<option value="t_l"><?php _e('Top/Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="t_r"><?php _e('Top/Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="b_l"><?php _e('Bottom/Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="b_r"><?php _e('Bottom/Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															</select>
														</div>
														<div class="select_wrapper" style="float:left; width:40px;margin-left:15px;">
															<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-absolute-unit">
																<option value="px">px</option>
																<option value="%">%</option>
															</select>
														</div><div class="clear"></div>
													</div>
													<div style="margin-top:10px">
														<label style="float:left">&nbsp;</label>
														<span id="adamlabsgallery-t_b_align"><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span> <input class="input-settings-small element-setting" type="text" name="element-top-bottom" value="0" />
														<span id="adamlabsgallery-l_r_align"><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span> <input class="input-settings-small element-setting" type="text" name="element-left-right" value="0" />
														<div class="clear"></div>
													</div>
													<!--div style="margin-top:10px">
														<label style="float:left"><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<input style="float:left" class="input-settings-small element-setting firstinput" type="text" name="element-bottom" value="0" />
														<div class="select_wrapper" style="float:left; width:40px;margin-left:15px;">
															<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-bottom-unit">
																<option value="px">px</option>
																<option value="%">%</option>
															</select>
														</div><div class="clear"></div>
													</div>
													<div style="margin-top:10px">
														<label style="float:left"><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<input style="float:left" class="input-settings-small element-setting firstinput" type="text" name="element-left" value="0" />
														<div class="select_wrapper" style="float:left; width:40px;margin-left:15px;">
															<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-left-unit">
																<option value="px">px</option>
																<option value="%">%</option>
															</select>
														</div><div class="clear"></div>
													</div-->
												</div>
												<div id="adamlabsgallery-show-on-relative">

													<div style="margin-top:10px">
														<label style="float:left"><?php _e('Display', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<div class="select_wrapper" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-display">
																<option value="block"><?php _e('block', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="inline-block"><?php _e('inline-block', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															</select>
														</div><div class="clear"></div>
													</div>

													<div style="margin-top:10px" id="element-text-align-wrap">
														<label style="float:left"><?php _e('Text Align', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<div class="select_wrapper" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-text-align">
																<option value="center"><?php _e('center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="left"><?php _e('left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="right"><?php _e('right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															</select>
														</div><div class="clear"></div>
													</div>

													<div style="margin-top:10px" id="element-float-wrap">
														<label style="float:left"><?php _e('Float Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<div class="select_wrapper" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-float">
																<option value="none"><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="left"><?php _e('left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="right"><?php _e('right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															</select>
														</div><div class="clear"></div>
													</div>

													<div style="margin-top:10px">
														<label style="float:left"><?php _e('Clear', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
														<div class="select_wrapper" style="float:left;">
															<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
															<select name="element-clear">
																<option value="none"><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="left"><?php _e('left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="right"><?php _e('right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
																<option value="both"><?php _e('both', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															</select>
														</div><div class="clear"></div>
													</div>
												</div>
												<p>
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-margin[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-margin[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-margin[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-margin[]" value="0" /> px
												</p>
												<p>
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Paddings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-padding[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-padding[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-padding[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-padding[]" value="0" /> px
												</p>
												<!-- <p>
													<label><?php _e('Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-height" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-height" value="0" /> px
												</p>-->
												<div class="drop-to-stylechange adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Element from Available Layers here to overwrite Styling of Current Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Drop for<br>Style<br>Change", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>
											<!--
											BG
											-->
											<div id="adamlabsgallery-el-bg" class="adamlabsgallery-el-settings-container">
												<p>
													<label><?php _e('Background Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-background-color" type="text" id="element-background-color" value="">
												</p>
												<?php /*
												<p>
													<label><?php _e('Background Alpha', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-bg-alpha" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-bg-alpha" value="100" />
												</p>
												*/ ?>
												<?php /*
												<div>
													<label style="float:left"><?php _e('Background Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-background-size">
															<option value="cover"><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="contain"><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<!--option value="%"><?php _e('%', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option-->
															<option value="normal"><?php _e('Normal', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
													<!--div id="background-size-percent-wrap">
														<input class="input-settings-small element-setting" type="text" name="element-background-size-x" value="100" />
														<input class="input-settings-small element-setting" type="text" name="element-background-size-y" value="100" />
													</div-->
												</div>
												<div>
													<label style="float:left"><?php _e('Background Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-background-repeat">
															<option value="no-repeat"><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat"><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat-x"><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat-y"><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div> */ ?>
												<div class="drop-to-stylechange adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Element from Available Layers here to overwrite Styling of Current Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Drop for<br>Style<br>Change", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											SHADOW
											-->
											<div id="adamlabsgallery-el-shadow" class="adamlabsgallery-el-settings-container">
												<p>
													<label><?php _e('Shadow Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-shadow-color" type="text" id="element-shadow-color" value="" data-mode="single">
												</p>
												<?php /*
												<p>
													<label><?php _e('Shadow Alpha', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-shadow-alpha" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-shadow-alpha" value="100" />
												</p>
												*/ ?>
												<p>
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Position of horizontal shadow(Negative values possible)', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('blur distance', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('size of shadow', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-box-shadow[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow[]" value="0" /> px
												</p>
												<div class="drop-to-stylechange adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Element from Available Layers here to overwrite Styling of Current Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Drop for<br>Style<br>Change", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											BORDER
											-->
											<div id="adamlabsgallery-el-border" class="adamlabsgallery-el-settings-container">
												<p>
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top Border Width', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Right Border Width', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom Border Width', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Left Border Width', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-border[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border[]" value="0" /> px
												</p>
												<div style="margin-top:10px">
													<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top Left Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Top Right Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom Right Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom Left Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Border Radius', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input style="float:left" class="input-settings-small element-setting firstinput" type="text" name="element-border-radius[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius[]" value="0" />
													<div class="select_wrapper" style="float:left; width:40px;margin-left:15px;">
														<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-border-radius-unit">
															<option value="px">px</option>
															<option value="%">%</option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Border Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-border-color" type="text" id="element-border-color" value="" data-mode="single">
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Border Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-border-style">
															<option value="none"><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="solid"><?php _e('solid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="dotted"><?php _e('dotted', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="dashed"><?php _e('dashed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="double"><?php _e('double', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<div class="drop-to-stylechange adamlabsgallery-tooltip-wrap" title="<?php _e('Drop Element from Available Layers here to overwrite Styling of Current Element', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Drop for<br>Style<br>Change", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>
										</div>

										<!-- HOVER STYLING -->
										<div id="adamlabsgallery-style-hover">
											<div class="adamlabsgallery-small-vertical-menu" style="height: 420px">
												<ul>
													<li class="selected-el-setting" data-toshow="adamlabsgallery-el-font-hover"><i class="adamlabsgallery-icon-font" ></i><p><?php _e('Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-border-hover"><i class="adamlabsgallery-icon-minus-squared-alt"></i><p><?php _e('Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-bg-hover"><i class="adamlabsgallery-icon-picture-1"></i><p><?php _e('BG', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
													<li class="" data-toshow="adamlabsgallery-el-shadow-hover"><i class="adamlabsgallery-icon-picture"></i><p><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p></li>
												</ul>
											</div>
											<!--
											FONT ON HONVER
											-->
											<div id="adamlabsgallery-el-font-hover" class="adamlabsgallery-el-settings-container active-esc">
												<p>
													<label><?php _e('Font Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-font-size-hover" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-font-size-hover" value="6" /> px
												</p>
												<p>
													<label><?php _e('Line Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-line-height-hover" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-line-height-hover" value="8" /> px
												</p>
												<p>
													<label><?php _e('Font Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-color-hover" type="text" id="element-color-hover" value="" data-mode="single">
												</p>
												<p>
													<label><?php _e('Font Family', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label
													><input class="element-setting" name="element-font-family-hover" type="text" value=""> <a class="button-secondary" id="button-open-font-family-hover" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Font Weight', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">

														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-font-weight-hover">
															<option value="400"><?php _e('400', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="100"><?php _e('100', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="200"><?php _e('200', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="300"><?php _e('300', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="500"><?php _e('500', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="600"><?php _e('600', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="700"><?php _e('700', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="800"><?php _e('800', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="900"><?php _e('900', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Text Decoration', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-text-decoration-hover">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="underline"><?php _e('Underline', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="overline"><?php _e('Overline', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="line-through"><?php _e('Line Through', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Font Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input type="checkbox" name="element-font-style-hover" value="italic" /> <?php _e('Italic', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Text Transform', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-text-transform-hover">
															<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="capitalize"><?php _e('Capitalize', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="uppercase"><?php _e('Uppercase', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="lowercase"><?php _e('Lowercase', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Letter Spacing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label
													><input type="text" class="letter-spacing" style="width: 65px" name="element-letter-spacing-hover" value="normal">
												</p>
												<div class="revyellow drop-to-stylereset button-primary"><i class="adamlabsgallery-icon-ccw-1"></i><?php _e("Reset from Idle", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											BG ON HOVER
											-->
											<div id="adamlabsgallery-el-bg-hover" class="adamlabsgallery-el-settings-container">
												<p>
													<label><?php _e('Background Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-background-color-hover" type="text" id="element-background-color-hover" value="">
												</p>
												<?php /*
												<p>
													<label><?php _e('Background Alpha', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-bg-alpha-hover" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-bg-alpha-hover" value="100" />
												</p>
												*/ ?>
												<?php /*
												<div>
													<label style="float:left"><?php _e('Background Fit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-background-size-hover">
															<option value="cover"><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="contain"><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<!--option value="%"><?php _e('%', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option-->
															<option value="normal"><?php _e('Normal', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
													<div id="background-size-percent-wrap-hover">
														<input class="input-settings-small element-setting" type="text" name="element-background-size-x-hover" value="100" />
														<input class="input-settings-small element-setting" type="text" name="element-background-size-y-hover" value="100" />
													</div>
												</div>
												<div>
													<label style="float:left"><?php _e('Background Repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-background-repeat-hover">
															<option value="no-repeat"><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat"><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat-x"><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="repeat-y"><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>     */ ?>
												<div class="revyellow drop-to-stylereset button-primary"><i class="adamlabsgallery-icon-ccw-1"></i><?php _e("Reset from Idle", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											SHADOW ON HOVER
											-->
											<div id="adamlabsgallery-el-shadow-hover" class="adamlabsgallery-el-settings-container">
												<p>
													<label><?php _e('Shadow Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-shadow-color-hover" type="text" id="element-shadow-color-hover" value="" data-mode="single">
												</p>
												<?php /*
												<p>
													<label><?php _e('Shadow Alpha', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<span id="element-shadow-alpha-hover" class="slider-settings"></span>
													<input class="input-settings-small element-setting" type="text" name="element-shadow-alpha-hover" value="100" />
												</p>
												*/ ?>
												<p>
													<label class=" adamlabsgallery-tooltip-wrap" title="<?php _e('Position horizontal shadow(Negative values possible)', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('blur distance', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Shadow size', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Shadow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-box-shadow-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-box-shadow-hover[]" value="0" /> px
												</p>
												<div class="revyellow drop-to-stylereset button-primary"><i class="adamlabsgallery-icon-ccw-1"></i><?php _e("Reset from Idle", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

											<!--
											BORDER ON HOVER
											-->
											<div id="adamlabsgallery-el-border-hover" class="adamlabsgallery-el-settings-container">

												<p>
													<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Left Border Width', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Border', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="input-settings-small element-setting firstinput" type="text" name="element-border-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border-hover[]" value="0" />
													<input class="input-settings-small element-setting" type="text" name="element-border-hover[]" value="0" /> px
												</p>
												<div style="margin-top:10px">
													<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Top Left Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Top Right Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom Right Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>, <?php _e('Bottom Left Radius', ADAMLABS_GALLERY_TEXTDOMAIN) ?>"><?php _e('Border Radius', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input style="float:left" class="input-settings-small element-setting firstinput" type="text" name="element-border-radius-hover[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius-hover[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius-hover[]" value="0" />
													<input style="float:left" class="input-settings-small element-setting" type="text" name="element-border-radius-hover[]" value="0" />
													<div class="select_wrapper" style="float:left; width:40px;margin-left:15px;">
														<div class="select_fake" style="width: 40px;overflow: hidden;white-space: nowrap;"><span>px</span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-border-radius-unit-hover">
															<option value="px">px</option>
															<option value="%">%</option>
														</select>
													</div><div class="clear"></div>
												</div>
												<p>
													<label><?php _e('Border Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<input class="element-setting" name="element-border-color-hover" type="text" id="element-border-color-hover" value="" data-mode="single">
												</p>
												<div style="margin-top:10px">
													<label style="float:left"><?php _e('Border Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
													<div class="select_wrapper" style="float:left;">
														<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
														<select name="element-border-style-hover">
															<option value="none"><?php _e('none', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="solid"><?php _e('solid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="dotted"><?php _e('dotted', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="dashed"><?php _e('dashed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
															<option value="double"><?php _e('double', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
														</select>
													</div><div class="clear"></div>
												</div>
												<div class="revyellow drop-to-stylereset button-primary"><i class="adamlabsgallery-icon-ccw-1"></i><?php _e("Reset from Idle", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>

										</div>

									</div>
								</div>

								<!--
								HIDE UNDER
								-->
								<div id="adamlabsgallery-element-hide">
									
									<!-- 2.1.6 -->
									<div id="always-visible-options">
										<p>
											<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Element by default without a Mouse Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show without Hover on Desktop', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input type="checkbox" name="element-always-visible-desktop"  value="true" />
										</p>
										<!-- 2.1.6 -->
										<p>
											<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Element by default without a Screen-Touch/Tap', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show without Tap on Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input type="checkbox" name="element-always-visible-mobile"  value="true" />
										</p>
									</div>
									<p>
										<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Dont Show Element if Item Width is smaller than:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hide Under Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="text" name="element-hideunder" value="0" /> px
									</p>
									<p>
										<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Dont Show Element on mobile if Item height is smaller than:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hide Under Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small element-setting firstinput" type="text" name="element-hideunderheight" value="0" /> px
									</p>
									<div style="margin-top:10px">
										<label style="width:250px; float:left"><?php _e('Hide Under Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-hidetype">
												<option value="visibility"><?php _e('visibility', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="display"><?php _e('display', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div><div class="clear"></div>
									</div>
									<div style="margin-top:10px">
										<label style="width:250px; float:left" title="<?php _e('Show/Hide Element if the Media this Entry gains is a Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('If Media is Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-hide-on-video">
												<option value="false"><?php _e('-- Do Nothing --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="true"><?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="show"><?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div><div class="clear"></div>
									</div>
									<p>
										<label style="width:250px; float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show/Hide Element only if the LightBox is a Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('If LightBox is Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<!--input type="checkbox" name="element-show-on-lightbox-video" value="true" /-->
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-show-on-lightbox-video">
												<option value="false"><?php _e('-- Do Nothing --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="true"><?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="hide"><?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div><div class="clear"></div>
									</p>

									<?php
									if(!AdamLabsGallery_Woocommerce::is_woo_exists()){
										echo '<div style="display: none;">';
									}
									?>
									<p>
										<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Element only if it is on Sale. This is a WooCommerce setting', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show if Product is on Sale', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input type="checkbox" name="element-show-on-sale"  value="true" />
									</p>
									<p>
										<label style="width:250px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Element only if it is featured. This is a WooCommerce setting', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show if Product is featured', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input type="checkbox" name="element-show-if-featured" value="true" />
									</p>
									<?php
									if(!AdamLabsGallery_Woocommerce::is_woo_exists()){
										echo '</div>';
									}
									?>
									<p>
										<a href="javascript:void(0);" id="adamlabsgallery-advanced-rules-edit" class="button-primary revyellow"><?php _e('Advanced Rules', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
									</p>

								</div>

								<!--
								ANIMATION
								-->
								<div id="adamlabsgallery-element-animation">
									<div style="margin-top:10px">
										<label style="float:left"><?php _e('Transition', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div id="adamlabsgallery-element-transition-drop" class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-transition" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select Animation of Element on Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" >
												<?php
												foreach($transitions_elements as $handle => $name){
													if(preg_match('/collapse|line|circle|spiral/', $handle)) continue;
												?>
												<option value="<?php echo $handle; ?>"><?php echo $name; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<div class="select_wrapper adamlabsgallery-hideable-no-transition" style="float:left;width:120px;">
											<div class="select_fake" style="width: 95px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-transition-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Hide or Show element on hover. In = Show, Out = Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" >
												<option value=""><?php _e('in', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="out"><?php _e('out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<!-- 2.1.6 -->
												<!-- <option value="always"><?php _e('always visible', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>-->
											</select>
										</div>
										<div id="groupanimwarning"><?php 
											/* 2.1.6 */ 
											// _e('Dont forget to set the <strong>Group Animation</strong> to "none" !', ADAMLABS_GALLERY_TEXTDOMAIN);
										?></div>
										<div class="clear"></div>
									</div>
									<!--div style="margin-top:10px">
										<label style="float:left"><?php _e('Transition Split', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-split">
												<option value="full"><?php _e('Full', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="line"><?php _e('Line', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="word"><?php _e('Word', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="character"><?php _e('Character', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div>
										<div class="clear"></div>
									</div-->
									
									<div class="adamlabsgallery-hideable-no-transition" style="line-height: 28px; margin: 1em 0">
										<label><?php _e('Duration', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label
										><div class="select_wrapper" style="display: inline-block">
											<div class="select_fake" style="width: 70px;overflow: hidden;white-space: nowrap;"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-duration" class="adamlabsgallery-tooltip-wrap" style="width: 70px;" title="<?php _e('The animation duration (ms)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
												<option value="default">default</option>
												<option value="200">200</option>
												<option value="300">300</option>
												<option value="400">400</option>
												<option value="500">500</option>
												<option value="750">750</option>
												<option value="1000">1000</option>
												<option value="1500">1500</option>
											</select>
										</div>
									</div>
									
									<p class="adamlabsgallery-hideable-no-transition">
										<label><?php _e('Delay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<span id="element-delay" class="slider-settings adamlabsgallery-tooltip-wrap" title="<?php _e('Delay before Element Animation starts', ADAMLABS_GALLERY_TEXTDOMAIN) ?>" ></span>
										<input class="input-settings-small element-setting" type="text" name="element-delay" value="0" />
									</p>
									
								</div>
								<!--
								LINK TO
								-->
								<div id="adamlabsgallery-element-link">
									<div style="margin-top:10px">
										<label style="float:left"><?php _e('Link To', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<div class="select_wrapper" style="float:left;">
											<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
											<select name="element-link-type">
												<option value="none"><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="post"><?php _e('Post', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="url"><?php _e('URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="meta"><?php _e('Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="ajax"><?php _e('Ajax', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="javascript"><?php _e('JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="lightbox"><?php _e('Lightbox', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="embedded_video"><?php _e('Play Embedded Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="sharefacebook"><?php _e('Share on Facebook', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="sharetwitter"><?php _e('Share on Twitter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<!--option value="sharegplus"><?php _e('Share on Google+', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option-->
												<option value="sharepinterest"><?php _e('Share on Pinterest', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="likepost"><?php _e('Like Post', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</div><div class="clear"></div>
									</div>
									<p id="adamlabsgallery-element-post-url-wrap" style="display: none;">
										<label><?php _e('Link To URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" type="text" name="element-url-link" value="" />
									</p>
									<p id="adamlabsgallery-element-post-meta-wrap" style="display: none;">
										<label><?php _e('Meta Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" type="text" name="element-meta-link" value="" />
										<a class="button-secondary" id="button-open-link-meta-key" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
									</p>
									<p id="adamlabsgallery-element-post-javascript-wrap" style="display: none;">
										<label><?php _e('Link JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="element-setting" type="text" name="element-javascript-link" value="" />
									</p>
									<div id="adamlabsgallery-element-link-details-wrap">
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-link-target">
													<option value="disabled"><?php _e('disabled', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="_self"><?php _e('_self', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="_blank"><?php _e('_blank', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="_parent"><?php _e('_parent', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="_top"><?php _e('_top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Use Tag', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-tag-type">
													<option value="div"><?php _e('DIV', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="p"><?php _e('P', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="h2"><?php _e('H2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="h3"><?php _e('H3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="h4"><?php _e('H4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="h5"><?php _e('H5', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="h6"><?php _e('H6', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
									</div>
									<div style="margin-top:10px">
										<label style="float:left"><?php _e('Fix: !important', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input type="checkbox" name="element-force-important" value="true" /> <?php _e('Force !important in styles', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									</div>
									
									<!-- Facebook Fields -->
									<div class="adamlabsgallery-element-facebook-wrap" id="adamlabsgallery-element-facebook-wrap">
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-facebook-sharing-link">
													<option value="site"><?php _e("Parent Site URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="post"><?php _e("Post URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="custom"><?php _e("Custom URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<div class="adamlabsgallery-element-facebook_link_custom">
												<label style="float:left"><?php _e("URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
												<input type="text" style="width:250px;" name="element-facebook-link-url" value="">
											</div><div class="clear"></div>
										</div>
									</div>
									<!-- Gplus Fields -->
									<div class="adamlabsgallery-element-gplus-wrap" id="adamlabsgallery-element-gplus-wrap">
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-gplus-sharing-link">
													<option value="site"><?php _e("Parent Site URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="post"><?php _e("Post URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="custom"><?php _e("Custom URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<div class="adamlabsgallery-element-gplus_link_custom">
												<label style="float:left"><?php _e("URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
												<input type="text" style="width:250px;" name="element-gplus-link-url" value="">
											</div><div class="clear"></div>
										</div>
									</div>
									<!-- Pinterest Fields -->
									<div class="adamlabsgallery-element-pinterest-wrap" id="adamlabsgallery-element-pinterest-wrap">
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-pinterest-sharing-link">
													<option value="site"><?php _e("Parent Site URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="post"><?php _e("Post URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="custom"><?php _e("Custom URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<div class="adamlabsgallery-element-pinterest_link_custom">
												<label style="float:left"><?php _e("URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
												<input type="text" style="width:250px;" name="element-pinterest-link-url" value="">
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Description",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<textarea type="text" style="width:250px;" name="element-pinterest-description" value="" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"></textarea>
											<div class="clear"></div>
										</div>
									</div>
									<!-- Twitter Fields -->
									<div class="adamlabsgallery-element-twitter-wrap" id="adamlabsgallery-element-twitter-wrap">
										<div style="margin-top:10px">
											<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Text before Link",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input type="text" style="width:250px;" name="element-twitter-text-before" value="" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<label style="float:left"><?php _e('Link Target', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<div class="select_wrapper" style="float:left;">
												<div class="select_fake"><span><?php _e('Not uppercased', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><i class="adamlabsgallery-icon-sort"></i></div>
												<select name="element-twitter-sharing-link">
													<option value="site"><?php _e("Parent Site URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="post"><?php _e("Post URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
													<option value="custom"><?php _e("Custom URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												</select>
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<div class="adamlabsgallery-element-twitter_link_custom">
												<label style="float:left"><?php _e("URL",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
												<input type="text" style="width:250px;" name="element-twitter-link-url" value="">
											</div><div class="clear"></div>
										</div>
										<div style="margin-top:10px">
											<label style="float:left" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Text after Link",ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
											<input type="text" style="width:250px;" name="element-twitter-text-after" value="" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use placeholder %title%,%excerpt% for replacement', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
							<p id="dz-delete" class="adamlabsgallery-delete-wrapper">
								<a id="element-save-as-button" class="button-primary" href="javascript:void(0);"><i class="adamlabsgallery-icon-login"></i> <?php _e('Save as Template', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
								<a id="element-delete-button" class="button-primary" href="javascript:void(0);"><i class="adamlabsgallery-icon-trash"></i> <?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							</p>
						</form>
					</div>

					<div id="element-setting-wrap-alternative" style="padding: 10px;">
						<?php _e("Please Drop some Element from the AVAILABLE LAYERS into the ITEM LAYOUT drop zone to be able to edit any Elements here", ADAMLABS_GALLERY_TEXTDOMAIN); ?>
					</div>
				</div>
			</div>
		</div>

		<!--
		THE ITEM LAYOUT
		-->
		<div class="postbox adamlabsgallery-postbox" id="adamlabsgallery-it-layout-wrap" style="display:inline-block;"><h3><span class="adamlabsgallery-element-setter"><i style="background-color:#27AE60; padding:3px; margin-right:10px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;color:#fff;" class="adamlabsgallery-icon-menu"></i><?php _e('Item Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
			<div class="inside padding-10">

				<div style="display:none">
					<?php _e('Show at Width:', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <span id="element-item-skin-width-check" class="slider-settings"></span>
					<span id="currently-at-pixel">400px</span>
				</div>

				<div style="width:100%;height:15px"></div>

				<div style="float:left">
					<a href="javascript:void(0);" class="button-primary" id="adamlabsgallery-preview-item-skin"><i class="adamlabsgallery-icon-play"></i></a>
					<a href="javascript:void(0);" class="button-primary" id="adamlabsgallery-preview-stop-item-skin"><i class="adamlabsgallery-icon-stop"></i></a>
				</div>

				<div style="float:left">
					<a href="javascript:void(0);" class="button-primary" id="make-3d-map"><?php _e('Schematic', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
				</div>

				<div style="margin-left:50px;float:left">
					<a href="javascript:void(0);" class="button-primary" id="layertotop"><i class="adamlabsgallery-icon-up-dir"></i></a>
				</div>

				<div style="float:left">
					<a href="javascript:void(0);" class="button-primary" id="layertobottom"><i class="adamlabsgallery-icon-down-dir"></i></a>
				</div>


				<div style="float:right">
					<a href="javascript:void(0);" class="button-primary" style="margin-right:0px !important" id="drop-1"><?php _e('Hide DropZones', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
				</div>

				<div class="clear"></div>

				<div style="width:100%;height:15px"></div>

				<div style="position:relative">
					<div class="adamlabsgallery-editor-inside-wrapper">
						<div id="adamlabsgallery-dz-padding-wrapper" class="adamlabsgallery-media-cover-wrapper">
							<div id="adamlabsgallery-dz-hover-wrap">
								<!-- MEDIA -->
								<div id="skin-dz-media-bg-wrapper" class="adamlabsgallery-entry-media-wrapper" style="width:100%;height:100%;position:absolute;overflow:hidden">
									<div id="skin-dz-media-bg"></div>
								</div>

								<!-- OVERLAYS -->
								<div id="skin-dz-wrapper">
									<div class="adamlabsgallery-cc eec" id="skin-dz-c-wrap">
										<div class="adamlabsgallery-element-cover"></div>
										<div id="adamlabsgallery-element-centerme-c">
											<div class="dropzonetext adamlabsgallery-drop-2">
												<div class="dropzoneinner"><?php _e('DROP ZONE', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											</div>
											<div id="skin-dz-c"></div>
										</div>
									</div>
									<div class="adamlabsgallery-tc eec" id="skin-dz-tl-wrap">
										<div class="dropzonetext adamlabsgallery-drop-1">
											<div class="dropzoneinner"><?php _e('DROP ZONE', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										</div>
										<div id="skin-dz-tl"><div class="adamlabsgallery-element-cover"></div></div>
									</div>
									<div class="adamlabsgallery-bc eec" id="skin-dz-br-wrap">
										<div class="dropzonetext adamlabsgallery-drop-3">
											<div class="dropzoneinner"><?php _e('DROP ZONE', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										</div>
										<div id="skin-dz-br"><div class="adamlabsgallery-element-cover"></div></div>
									</div>
								</div>

								<!-- CONTENT -->
								<div id="skin-dz-m-wrap" class="adamlabsgallery-entry-content">
									<div class="dropzonetext adamlabsgallery-drop-4">
										<div class="dropzoneinner"><?php _e('DROP ZONE', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
									</div>

									<div id="skin-dz-m"></div>
								</div>

								<div class="clear"></div>
							</div>
						</div>
					</div>

					<!-- 3D MAP -->
					<div id="adamlabsgallery-3dpp" class="adamlabsgallery-3dpp" style="visibility:hidden">
						<div id="adamlabsgallery-3dpp-inner" style="position:relative">
							<div class="adamlabsgallery-3dmc">
								<div class="adamlabsgallery-3d-bg"></div>
								<div class="adamlabsgallery-3d-cover"></div>
								<div class="adamlabsgallery-3d-elements">
									<div class="adamlabsgallery-3d-element"><i style="margin-right:10px; " class="adamlabsgallery-icon-link"></i><i class="adamlabsgallery-icon-search"></i></div>
									<div class="adamlabsgallery-3d-element" style="margin-top:30px; color:#34495e; background:#fff; padding:5px 10px; font-size:12px; display:inline-block">LOREM IPSUM DOLOR</div>
									<div style="width:100%;height:5px"></div>
									<div class="adamlabsgallery-3d-element" style="color:#000; background:#fff; padding:3px 7px; font-size:12px; display:inline-block">sed do ediusmod 09.06.2021</div>
								</div>
							</div>

							<div class="adamlabsgallery-3dcc">
								<div class="adamlabsgallery-3d-ccbg"></div>
								<div class="adamlabsgallery-3d-element 3d-cont" style="font-size:14px; font-weight:600;color:#34495e; background:#fff; padding:3px 7px;">Lorem Ipsum Dolor</div>
								<div class="adamlabsgallery-3d-element 3d-cont" style="font-size:12px; line-height:14px;color:#34495e; background:#fff; padding:3px 7px; font-weight:400;margin-top:5px;">Sit amet, consectetur adipisicing elit, sed ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exerci.</div>
								<div class="adamlabsgallery-3d-element 3d-cont" style="margin-top:10px;font-size:11px; color:#333; font-weight:600;background:#34495e; padding:3px;5px; float:right; color:#fff;font-wieght:600;">LOREM</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<div id="adamlabsgallery-3d-description" style="visibility:hidden">
						<span id="adamlabsgallery-3d-cstep1"><?php _e("Layers", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						<span id="adamlabsgallery-3d-cstep2"><?php _e("Covers", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						<span id="adamlabsgallery-3d-cstep3"><?php _e("Media", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						<span id="adamlabsgallery-3d-cstep4"><?php _e("Content & Layers", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
					</div>
				</div>

				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>


	<!--******************************
	-	THE ELEMENTS GRID	-
	******************************** -->
	<div style="width:100%;height:40px"></div>
	<div class="postbox adamlabsgallery-postbox fullwidthpostbox2 adamlabsgallery-transbg" ><h3><span class="adamlabsgallery-element-setter"><?php _e('Available Layers', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
		<div class="inside" style="margin:0; padding:0;">

			<!-- GRID WRAPPER FOR CONTAINER SIZING   HERE YOU CAN SET THE CONTAINER SIZE AND CONTAINER SKIN-->
			<article id="adamlabsgallery-elements-container-grid-wrap" class="backend-flat myportfolio-container adamlabsgallery-startheight">

				<!-- THE GRID ITSELF WITH FILTERS, PAGINATION,  SORTING ETC... -->
				<div id="adamlabsgallery-elements-container-grid" class="adamlabsgallery-grid" style="text-align:center;">

					<!-- THE FILTERING,  SORTING AND WOOCOMMERCE BUTTONS -->
					<article class="adamlabsgallery-filters adamlabsgallery-singlefilters "> <!-- Use adamlabsgallery-multiplefilters for Mixed Filtering, and adamlabsgallery-singlefilters for Single Filtering -->
						<!-- THE FILTER BUTTONS -->
						<div class="adamlabsgallery-filter-wrapper">
							<div class="adamlabsgallery-filterbutton selected adamlabsgallery-allfilter" data-filter="filterall"><span><?php _e('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></div>
							<div class="adamlabsgallery-filterbutton" data-filter="filter-icon"><span><?php _e('Icons', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>
							<div class="adamlabsgallery-filterbutton" data-filter="filter-text"><span><?php _e('Texts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>
							<div class="adamlabsgallery-filterbutton" data-filter="filter-default"><span><?php _e('Default', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>
						</div>

						<div class="clear"></div>

					</article><!-- END OF FILTERING, SORTING AND  CART BUTTONS -->

					<div class="clear"></div>

					<!-- ############################ -->
					<!-- THE GRID ITSELF WITH ENTRIES -->
					<!-- ############################ -->
					<ul id="" data-kriki="">
						<?php echo $item_elements->prepareDefaultElementsForEditor(); ?>
						<?php echo $item_elements->prepareTextElementsForEditor(); ?>
					</ul>

					<!-- The Pagination Container. Page Buttons will be added on demand Automatically !! -->
					<article style="background: #FFF;z-index: 100;-webkit-backface-visibility: hidden;" class="adamlabsgallery-pagination"></article>
				</div>
				
				<!-- 2.2.5 -->
				<style type="text/css">
				
					#adamlabsgallery-elements-container-grid-wrap.adamlabsgallery-startheight {height: 351px};
				
				</style>

			</article>

			<div class="clear"></div>
			<div class="adamlabsgallery-special">
				<?php echo $item_elements->prepareSpecialElementsForEditor(); ?>

				<?php echo $item_elements->prepareAdditionalElementsForEditor(); ?>

			</div>
			<div style="text-align:center;position: absolute;width: 100%;background:#fff;"><i style="font-size:10px;color:#777"><?php _e('Drag and Drop Elements into the Item Layout - Note You only see here the elements which fits well in your Cover Colors !', ADAMLABS_GALLERY_TEXTDOMAIN); ?></i></div>
			<div class="adamlabsgallery-trashdropzone adamlabsgallery-tooltip-wrap" title="<?php _e('Move ELement Template over to Remove from Available Layers', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><i style="float:left" class="adamlabsgallery-icon-trash"></i><div style="float:left; line-height:11px;"><span style="font-size:10px;"><?php _e('DROP<br>HERE', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></div></div>
		</div>
	</div>

	<div id="adamlabsgallery-inline-style-wrapper"></div>

	<div style="margin-top:20px;" class="save-wrap-settings-skin"><a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-btn-save-grid-editor"><i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;margin-right:10px !important;margin-left:2px !important;"></i><?php echo $save; ?></a></div>

	<?php
	AdamLabsGallery_Dialogs::fontello_icons_dialog();
	AdamLabsGallery_Dialogs::global_css_edit_dialog();
	AdamLabsGallery_Dialogs::meta_dialog();
	AdamLabsGallery_Dialogs::edit_advanced_rules_dialog();
	?>
</div>

<script type="text/javascript">
	jQuery(function(){
		GridEditorEssentials.setInitElementsJson(<?php echo $base->jsonEncodeForClientSide($elements); ?>);

		GridEditorEssentials.setInitFontsJson(<?php echo $base->jsonEncodeForClientSide($fonts_full); ?>);

		GridEditorEssentials.setInitAllAttributesJson(<?php echo $base->jsonEncodeForClientSide($all_attributes); ?>);

		GridEditorEssentials.setInitMetaKeysJson(<?php echo $base->jsonEncodeForClientSide($meta_keys); ?>);

		GridEditorEssentials.initGridEditor(<?php echo ($skin_id !== false) ? '"update_item_skin"' : ''; ?>);

        <?php if(!empty($skin['layers'])){ ?>
            GridEditorEssentials.setInitLayersJson(<?php echo $base->jsonEncodeForClientSide($skin['layers']); ?>);
            GridEditorEssentials.create_elements_by_data();
        <?php } ?>

        GridEditorEssentials.initDraggable();
        AdminEssentials.initSmallMenu();
        AdminEssentials.atDropStop();
        AdminEssentials.adamlabsgallery3dtakeCare();
        AdminEssentials.initSideButtons();

        jQuery('body').on("click",".skin-dz-elements",function() {
        	var ic = jQuery('#adamlabsgallery-ls-smalllsicon');
        	var bw = jQuery('#adamlabsgallery-layersettings-box-wrapper');

//		   adamlabsgallerygs.TweenLite.to(ic,0.5,{scale:1.3,ease:adamlabsgallerygs.Power3.easeOut,delay:1});
//		   adamlabsgallerygs.TweenLite.to(ic,0.5,{scale:1,delay:1.6,ease:adamlabsgallerygs.Power3.easeOut});
		   adamlabsgallerygs.TweenLite.to(bw,0.3,{borderColor:"#8E44A9"});
		   adamlabsgallerygs.TweenLite.to(bw,0.3,{borderColor:"#ccc",delay:0.5});
	       if (jQuery('#layer-settings-header').hasClass("box-closed")) jQuery('#layer-settings-header').click();
        });
	});

</script>
