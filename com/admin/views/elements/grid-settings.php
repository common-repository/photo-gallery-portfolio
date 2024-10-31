<?php
 
if( !defined( 'ABSPATH') ) exit();

//needs: $base (instance of AdamLabsGallery_Base), $grid (Instance of AdamLabsGallery::get_adamlabsgallery_by_id() || false [creation of new])
if(!isset($base)) $base = new AdamLabsGallery_Base();
$adamlabsgallery_meta = new AdamLabsGallery_Meta();



// INIT LIGHTBOX SOURCE ORDERS
if(intval($isCreate) > 0) {//currently editing, so default can be empty
	$lb_source_order = $base->getVar($grid['params'], 'lb-source-order', '');
	$lb_button_order = $base->getVar($grid['params'], 'lb-button-order', array());
}
else {
	$lb_source_order = $base->getVar($grid['params'], 'lb-source-order', array('featured-image'));
	$lb_button_order = $base->getVar($grid['params'], 'lb-button-order', array('share', 'thumbs', 'close'));
}

$lb_source_list = $base->get_lb_source_order();
$lb_button_list = $base->get_lb_button_order();

// INIT AJAX SOURCE ORDERS
if(intval($isCreate) > 0) //currently editing, so default can be empty
	$aj_source_order = $base->getVar($grid['params'], 'aj-source-order', '');
else
	$aj_source_order = $base->getVar($grid['params'], 'aj-source-order', array('post-content'));

$aj_source_list = $base->get_aj_source_order();

$all_metas = $adamlabsgallery_meta->get_all_meta();
?>
	<!-- SETTINGS -->
	<form id="adamlabsgallery-form-create-settings">
		<!--
		GRID SETTINGS
		-->
		<div id="adamlabsgallery-settings-grid-settings" class="adamlabsgallery-settings-container <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>adamlabsgallery-pro-disabled<?php endif; ?>">
			<div class="">

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="navigation-container" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose layout type of the grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Layout", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="layout-sizing" class="firstinput" value="boxed"<?php checked($base->getVar($grid['params'], 'layout-sizing', 'fullwidth'), 'boxed'); ?>><span style="margin-right:25px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Grid always stays within the wrapping container', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Boxed", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="layout-sizing" value="fullwidth" <?php checked($base->getVar($grid['params'], 'layout-sizing', 'fullwidth'), 'fullwidth'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Force Fullwidth. Grid will fill complete width of the window', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Fullwidth", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="layout-sizing" value="fullscreen" <?php checked($base->getVar($grid['params'], 'layout-sizing', 'fullwidth'), 'fullscreen'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Fullscreen Layout. !! Hides not needed options !! Grid Width = Window Width, Grid Height = Window Height - Offset Containers.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Fullscreen", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p id="adamlabsgallery-fullscreen-container-wrap" style="display: none;">
							<label for="fullscreen-offset-container"><?php _e('Offset Container', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input class="firstinput" type="text" name="fullscreen-offset-container" value="<?php echo $base->getVar($grid['params'], 'fullscreen-offset-container', ''); ?>" />
						</p>
						<p id="adamlabsgallery-even-masonry-wrap">
							<label for="layout" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select Grid Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="adamlabsgallery-grid-layout-wrapper">
								<input type="radio" name="layout" value="even" class="firstinput" <?php checked($base->getVar($grid['params'], 'layout', 'even'), 'even'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Even - Each item has same height. Width and height are item ratio dependent', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Even', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="layout" value="masonry" <?php checked($base->getVar($grid['params'], 'layout', 'even'), 'masonry'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Individual item height depends on media height and content height', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Masonry', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="layout" value="cobbles" <?php checked($base->getVar($grid['params'], 'layout', 'even'), 'cobbles'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Even Grid with Width / Height Multiplications', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Cobbles', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</span>
						</p>
						<p id="adamlabsgallery-content-push-wrap">
							<label for="columns" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Content Push', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Push', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="content-push" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'content-push', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Content will push the website down on Even Grids with content in the Masonry Content area for the last row', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="content-push" value="off" <?php checked($base->getVar($grid['params'], 'content-push', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Content will overflow elements on Even Grids with content in the Masonry Content area for the last row', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p id="adamlabsgallery-items-ratio-wrap">
							<label for="x-ratio" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Media width/height ratio, Width ratio of Media:Height ratio of Media', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Media Ratio X:Y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="adamlabsgallery-ratio-wrapper" style="margin-right: 10px;" <?php echo ($base->getVar($grid['params'], 'auto-ratio', 'true') === 'true' && $base->getVar($grid['params'], 'layout', 'even') === 'masonry') ? 'style="display: none;"' : ''; ?>>
								<input class="input-settings-small firstinput" type="text" name="x-ratio" value="<?php echo $base->getVar($grid['params'], 'x-ratio', '4', 'i'); ?>" />&nbsp;:&nbsp;<input class="input-settings-small firstinput" type="text" name="y-ratio" value="<?php echo $base->getVar($grid['params'], 'y-ratio', '3', 'i'); ?>" />
							</span>
							<span id="adamlabsgallery-masonry-options">
								<input type="checkbox" name="auto-ratio" <?php checked($base->getVar($grid['params'], 'auto-ratio', 'true'), 'true'); ?> /> <?php _e('Auto', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</span>
						</p>
						<p>
							<label for="rtl" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Right To Left option. This will change the direction of the Grid Items from right to left instead of left to right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('RTL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="rtl" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'rtl', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Grid Items will be sorted and ordered from right to left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="rtl" value="off" <?php checked($base->getVar($grid['params'], 'rtl', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Grid Items will be sorted and ordered from left to right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings" id="adamlabsgallery-cobbles-options">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Cobbles', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="padding-bottom:15px;">
						<p>
							<label for="use-cobbles-pattern" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use cobbles pattern and overwrite the cobbles that is set sepcifically in the entries', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Use Cobbles Pattern', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="use-cobbles-pattern" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'use-cobbles-pattern', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('User cobbles pattern', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="use-cobbles-pattern" value="off" <?php checked($base->getVar($grid['params'], 'use-cobbles-pattern', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('User specific set cobbles setting from entries', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<div class="adamlabsgallery-cobbles-pattern-wrap" style="margin-bottom:10px; <?php echo ($base->getVar($grid['params'], 'use-cobbles-pattern', 'off') == 'off') ? ' display: none;' : ''; ?>">
							<?php
							$cobbles_pattern = $base->getVar($grid['params'], 'cobbles-pattern', array());
							if(!empty($cobbles_pattern)){
								$cob_sort_count = 0;
								foreach($cobbles_pattern as $pattern){
									$cob_sort_count++;
									?>
									<div class="adamlabsgallery-cobbles-drop-wrap">
										<span class="cob-sort-order"><?php echo $cob_sort_count; ?>.</span>
										<select name="cobbles-pattern[]">
											<option <?php selected($pattern, '1x1'); ?> value="1x1">1:1</option>
											<option <?php selected($pattern, '1x2'); ?> value="1x2">1:2</option>
											<option <?php selected($pattern, '1x3'); ?> value="1x3">1:3</option>
											<option <?php selected($pattern, '1x4'); ?> value="1x4">1:4</option>
											<option <?php selected($pattern, '2x1'); ?> value="2x1">2:1</option>
											<option <?php selected($pattern, '2x2'); ?> value="2x2">2:2</option>
											<option <?php selected($pattern, '2x3'); ?> value="2x3">2:3</option>
											<option <?php selected($pattern, '2x4'); ?> value="2x4">2:4</option>
											<option <?php selected($pattern, '3x1'); ?> value="3x1">3:1</option>
											<option <?php selected($pattern, '3x2'); ?> value="3x2">3:2</option>
											<option <?php selected($pattern, '3x3'); ?> value="3x3">3:3</option>
											<option <?php selected($pattern, '3x4'); ?> value="3x4">3:4</option>
											<option <?php selected($pattern, '4x1'); ?> value="4x1">4:1</option>
											<option <?php selected($pattern, '4x2'); ?> value="4x2">4:2</option>
											<option <?php selected($pattern, '4x3'); ?> value="4x3">4:3</option>
											<option <?php selected($pattern, '4x4'); ?> value="4x4">4:4</option>
										</select><a class="button-primary revred adamlabsgallery-delete-cobbles" href="javascript:void(0);"><i class="adamlabsgallery-icon-trash"></i></a>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div style="clear: both;"></div>
						<a <?php echo ($base->getVar($grid['params'], 'use-cobbles-pattern', 'off') == 'off') ? ' style="display: none;"' : ''; ?> class="button-primary revgreen adamlabsgallery-add-new-cobbles-pattern adamlabsgallery-tooltip-wrap" title="<?php _e('Add your custom cobbles pattern here', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" href="javascript:void(0);"><i class="adamlabsgallery-icon-plus"></i><?php _e("Cobbles Pattern", ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						<a <?php echo ($base->getVar($grid['params'], 'use-cobbles-pattern', 'off') == 'off') ? ' style="display: none;"' : ''; ?> class="adamlabsgallery-refresh-preview-button adamlabsgallery-refresh-cobbles-pattern button-primary" style="display: inline-block;"><i class="adamlabsgallery-icon-arrows-ccw"></i><?php _e('Refresh Preview', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Columns', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<table id="grid-columns-table" style="position:relative">
							<tr id="adamlabsgallery-col-0">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Display normal settings or get advanced', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label for="columns"><?php _e('Setting Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td>
									<input type="radio" name="columns-advanced" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'columns-advanced', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Advanced min heights, columns, custom media queries and custom columns (in rows pattern)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Advanced', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									<input type="radio" name="columns-advanced" value="off" <?php checked($base->getVar($grid['params'], 'columns-advanced', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Simple columns. Each row with same column, default media query levels.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Simple', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								</td>
								<td></td>
							</tr>
							
							<tr id="adamlabsgallery-col-00" style="height: 20px;" class="columns-height columns-width">
								<td></td>
								<td style="vertical-align:top !important"><span style="display:inline-block; vertical-align:top;width:100px;"><?php _e('Min Height of<br>Grid at Start', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span style="display:inline-block; vertical-align:top; margin-left: 19px;width:100px;"><?php _e('Breakpoint at<br>Grid Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span style="display:inline-block; vertical-align:top; margin-left: 19px;width:100px;"><?php _e('Min Masonry<br>Content Height', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></td>
								<?php
								$ca_steps = 0;
								if(!empty($columns_advanced[0])){
									foreach($columns_advanced[0] as $col)
										if(!empty($col)) $ca_steps = count($columns_advanced) + 1;
								}
								?>
								<td class="columns-adv-first" style="text-align: center;position:relative;">
									<?php _e('Rows:', ADAMLABS_GALLERY_TEXTDOMAIN); ?><br><?php
									if($ca_steps > 0) {
										echo 1; echo ',';
										echo 1 + 1 * $ca_steps; echo ',';
										echo 1 + 2 * $ca_steps;
									}else{
										?>
										<div style="position: absolute;top: 11px;white-space: nowrap;left: 100%;">
											<a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-add-column-advanced">+</a>
										</div>
										<?php
									}
									?>
								</td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows columns-adv-head" style="text-align: center;position:relative;">
											<?php _e('Rows:', ADAMLABS_GALLERY_TEXTDOMAIN); ?><br><?php
											$at = $adv_key + 2;
											echo $at; echo ',';
											echo $at + 1 * $ca_steps; echo ',';
											echo $at + 2 * $ca_steps;
											if($ca_steps == $adv_key + 1){
												?>
												<div style="position: absolute;top: 11px;white-space: nowrap;left: 100%;">
													<a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-add-column-advanced">+</a>
													<a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-remove-column-advanced">-</a>
												</div>
												<?php
											}
											?>
										</td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-1">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for large desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Desktop Large', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on large desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for large desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px; width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[0]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[0]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[0]; ?>">
									<span id="slider-columns-1" data-num="1" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on large desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-1" name="columns[]" value="<?php echo $columns[0]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on large desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[0]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-2">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for medium sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Desktop Medium', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on medium sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for medium sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[1]; ?>">
									<input class="input-settings-small columns-width firstinput" style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[1]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[1]; ?>">
									<span id="slider-columns-2" data-num="2" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on medium sized desktops', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-2" name="columns[]" value="<?php echo $columns[1]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on medium sized desktops', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small"  type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[1]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-3">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for small sized desktops', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Desktop Small', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on small sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for small sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[2]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[2]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[2]; ?>">
									<span id="slider-columns-3" data-num="3" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on small sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-3" name="columns[]" value="<?php echo $columns[2]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Amount of items in the rows shown above on small sized desktop screens', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[2]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-4">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for tablets in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Tablet Landscape', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on tablets in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for tablets in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[3]; ?>">
									<input class="input-settings-small columns-width firstinput" style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[3]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[3]; ?>">
									<span id="slider-columns-4" data-num="4" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on tablets in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-4" name="columns[]" value="<?php echo $columns[3]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Amount of items in the rows shown above on tablet in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[3]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-5">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for tablets in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Tablet', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap"  title="<?php _e('Start height for Grid on tablets in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for tablets in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[4]; ?>">
									<input class="input-settings-small columns-width firstinput" style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[4]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[4]; ?>">
									<span id="slider-columns-5" data-num="5" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on tablets', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-5" name="columns[]" value="<?php echo $columns[4]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in the rows shown above on tablets', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small"type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[4]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-6">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for mobiles in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Mobile Landscape', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on mobiles in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for mobiles in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[5]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="columns-width[]" value="<?php echo $columns_width[5]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[5]; ?>">
									<span id="slider-columns-6" data-num="6" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in the rows for mobiles in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-6" name="columns[]" value="<?php echo $columns[5]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in the rows shown above for mobiles in landscape view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[5]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
							<tr id="adamlabsgallery-col-7">
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items per Row (+ Min. Window Width (advanced)) for mobiles in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><label><?php _e('Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Start height for Grid on mobiles in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Min. browser width for mobiles in portrait view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<input class="input-settings-small columns-height firstinput" style="margin-right:15px;width:100px" type="text" name="columns-height[]" value="<?php echo $columns_height[6]; ?>">
									<input class="input-settings-small columns-width firstinput" style="margin-right:15px;width:100px" type="text"  name="columns-width[]" value="<?php echo $columns_width[6]; ?>">
									<input class="input-settings-small columns-width firstinput"  style="margin-right:15px;width:100px" type="text" name="mascontent-height[]" value="<?php echo $mascontent_height[6]; ?>">
									<span id="slider-columns-7" data-num="7" class="slider-settings columns-sliding"></span>
								</td>
								<td class="adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in rows on mobiles', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" id="columns-7" name="columns[]" value="<?php echo $columns[6]; ?>" /></td>
								<?php
								if(!empty($columns_advanced)){
									foreach($columns_advanced as $adv_key => $adv){
										if(empty($adv)) continue;
										?>
										<td class="columns-adv-<?php echo $adv_key; ?> columns-adv-rows adamlabsgallery-tooltip-wrap" title="<?php _e('Number of items in the rows shown above on mobiles', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><input class="input-settings-small" type="text" name="columns-advanced-rows-<?php echo $adv_key; ?>[]" value="<?php echo $adv[6]; ?>" /></td>
										<?php
									}
								}
								?>
							</tr>
						</table>
					</div>
				</div>

				<div class="divider1"></div>
				
				<div class="adamlabsgallery-creative-settings adamlabsgallery-blankitem-hideable">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Blank Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="sorting-order-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Hide Blank Items at a certain break-point', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hide Blank Items At', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<?php $blank_breakpoint = $base->getVar($grid['params'], 'blank-item-breakpoint', 'desktop-medium'); ?>
							<select class="adamlabsgallery-tooltip-wrap" name="blank-item-breakpoint" title="<?php _e('Hide Blank Items at a certain break-point', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
								<option value="1"<?php selected($blank_breakpoint, '1'); ?>><?php _e('Desktop Medium', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="2"<?php selected($blank_breakpoint, '2'); ?>><?php _e('Desktop Small', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="3"<?php selected($blank_breakpoint, '3'); ?>><?php _e('Tablet Landscape', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="4"<?php selected($blank_breakpoint, '4'); ?>><?php _e('Tablet', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="5"<?php selected($blank_breakpoint, '5'); ?>><?php _e('Mobile Landscape', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="6"<?php selected($blank_breakpoint, '6'); ?>><?php _e('Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="none"<?php selected($blank_breakpoint, 'none'); ?>><?php _e('Always Show Blank Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							</select>
						</p>
					</div>
					
				</div>
				
				<div class="divider1 adamlabsgallery-blankitem-hideable"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>

					<div class="adamlabsgallery-cs-tbc">
						<p id="adamlabsgallery-pagination-wrap">
							<label for="rows-unlimited"><?php _e('Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="rows-unlimited" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'rows-unlimited', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Pagination deactivated. Load More Option is available.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Disable (Load More Available)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="rows-unlimited" value="off" <?php checked($base->getVar($grid['params'], 'rows-unlimited', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Pagination Activated. Load More Option is disabled. Dont Forget to add The Navigation Module "Pagination" to your Grid !', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<div class="rows-num-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'on') ? ' style="display: none;"' : ''; ?>>
							<p>
								<label for="rows" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Amount of Rows shown (max) when Pagination Activated.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Max Visible Rows', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span id="slider-rows" class="slider-settings"></span>
								<input class="input-settings-small" type="text" name="rows" value="<?php echo $base->getVar($grid['params'], 'rows', '3', 'i'); ?>" />
							</p>
							<p>
								<label for="enable-rows-mobile" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set a custom rows amount for mobile devices', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Max Rows Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" class="firstinput enable-mobile-rows" name="enable-rows-mobile" value="on" <?php checked($base->getVar($grid['params'], 'enable-rows-mobile', 'off'), 'on'); ?>> 
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Enable custom rows amount for mobile devices', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" class="enable-mobile-rows" name="enable-rows-mobile" value="off" <?php checked($base->getVar($grid['params'], 'enable-rows-mobile', 'off'), 'off'); ?>> 
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Disable custom rows amount for mobile devices', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Disable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
							<?php $enable_mobile_rows = $base->getVar($grid['params'], 'enable-rows-mobile', 'off') === 'off' ? 'none' : 'block'; ?>
							<p id="rows-mobile-wrap" style="display: <?php echo $enable_mobile_rows; ?>">
								<label for="rows-mobile" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set a custom rows amount for mobile devices.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Max Visible Rows Mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span id="slider-rows-mobile" class="slider-settings"></span>
								<input class="input-settings-small" type="text" name="rows-mobile" value="<?php echo $base->getVar($grid['params'], 'rows-mobile', '3', 'i'); ?>" />
							</p>
							<p>
								<label for="pagination-autoplay" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Enable/Disable Autoplay for Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Autoplay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" class="pagination-autoplay firstinput" name="pagination-autoplay" value="on" <?php checked($base->getVar($grid['params'], 'pagination-autoplay', 'off'), 'on'); ?>> 
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Enable Autoplay for Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" class="pagination-autoplay" name="pagination-autoplay" value="off" <?php checked($base->getVar($grid['params'], 'pagination-autoplay', 'off'), 'off'); ?>> 
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Disable Autoplay for Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Disable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								
								<div id="pagination-autoplay-speed"<?php echo ($base->getVar($grid['params'], 'pagination-autoplay', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
									<p>
										<label for="pagination-autoplay-speed" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Timing in milliseconds for the Pagination autoplay', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Timing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small firstinput" type="text" name="pagination-autoplay-speed" value="<?php echo $base->getVar($grid['params'], 'pagination-autoplay-speed', '5000', 'i'); ?>" /> ms
									</p>
								</div>
								
								<p>
									<label for="pagination-touchswipe" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Allow pagination swipe on mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Touch Swipe', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" class="pagination-touchswipe firstinput" name="pagination-touchswipe" value="on" <?php checked($base->getVar($grid['params'], 'pagination-touchswipe', 'off'), 'on'); ?>> 
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Enable TouchSwipe for Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									<input type="radio" class="pagination-touchswipe" name="pagination-touchswipe" value="off" <?php checked($base->getVar($grid['params'], 'pagination-touchswipe', 'off'), 'off'); ?>> 
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Disable TouchSwipe for Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Disable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								</p>
								
								<div id="pagination-touchswipe-settings"<?php echo ($base->getVar($grid['params'], 'pagination-touchswipe', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
									
									<p>
										<label for="pagination-dragvertical" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Allows the page to be scrolled vertically', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Allow Vertical Dragging', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input type="radio" class="pagination-dragvertical firstinput" name="pagination-dragvertical" value="on" <?php checked($base->getVar($grid['params'], 'pagination-dragvertical', 'on'), 'on'); ?>> 
										<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Allow Vertical Dragging', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										<input type="radio" class="pagination-dragvertical" name="pagination-dragvertical" value="off" <?php checked($base->getVar($grid['params'], 'pagination-dragvertical', 'on'), 'off'); ?>> 
										<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Prevent Vertical Dragging', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Disable', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</p>
									
									<p>
										<label for="pagination-swipebuffer" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Amount the finger moves before a swipe is honored', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Swipe Threshold', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small firstinput" type="text" name="pagination-swipebuffer" value="<?php echo $base->getVar($grid['params'], 'pagination-swipebuffer', '30', 'i'); ?>" /> px
									</p>
									
								</div>
								
							</p>
						</div>
					</div>
				</div>

				<div class="divider1"></div>
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Smart Loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p class="load-more-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
							<label for="load-more" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Load More type', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Load More', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select name="load-more" >
								<option value="none"<?php selected($base->getVar($grid['params'], 'load-more', 'none'), 'none'); ?>><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="button"<?php selected($base->getVar($grid['params'], 'load-more', 'none'), 'button'); ?>><?php _e('More Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="scroll"<?php selected($base->getVar($grid['params'], 'load-more', 'none'), 'scroll'); ?>><?php _e('Infinite Scroll', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							</select>
						</p>
						<p class="load-more-wrap load-more-hide-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off' || $base->getVar($grid['params'], 'load-more', 'none') !== 'scroll') ? ' style="display: none;"' : ''; ?>>
							<label for="load-more-hide"><?php _e('Hide Load More Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="load-more-hide" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'load-more-hide', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="load-more-hide" value="off" <?php checked($base->getVar($grid['params'], 'load-more-hide', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
						<p class="load-more-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the Load More text here', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" for="load-more-text" ><?php _e('Load More Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="load-more-text" value="<?php echo $base->getVar($grid['params'], 'load-more-text', __('Load More', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>" />
						</p>
						<p class="load-more-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
							<label for="load-more-show-number"><?php _e('Item No. Remaining', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="load-more-show-number" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'load-more-show-number', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="load-more-show-number" value="off" <?php checked($base->getVar($grid['params'], 'load-more-show-number', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
						<p class="load-more-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
							<label  class="adamlabsgallery-tooltip-wrap" title="<?php _e('Display how many items at start?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" for="load-more-start" ><?php _e('Item No. at Start', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="slider-load-more-start" class="slider-settings"></span>
							<input class="input-settings-small" type="text" name="load-more-start" value="<?php echo $base->getVar($grid['params'], 'load-more-start', '3', 'i'); ?>" />
						</p>
						<p class="load-more-wrap"<?php echo ($base->getVar($grid['params'], 'rows-unlimited', 'off') == 'off') ? ' style="display: none;"' : ''; ?>>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Display how many items after loading?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" for="load-more-amount"><?php _e('Item No. Added', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="slider-load-more-amount" class="slider-settings"></span>
							<input class="input-settings-small" type="text" name="load-more-amount" value="<?php echo $base->getVar($grid['params'], 'load-more-amount', '3', 'i'); ?>" />
						</p>
						<p>
							<label for="lazy-loading"><?php _e('Lazy Load', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="lazy-loading" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lazy-loading', 'off'), 'on'); ?>> <span class="firstinput adamlabsgallery-tooltip-wrap" title="<?php _e('Enable Lazy Load of Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="lazy-loading" value="off" <?php checked($base->getVar($grid['params'], 'lazy-loading', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Disable Lazy Loading (All Item except the - Load more items -  on first page will be preloaded once)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label for="lazy-loading"><?php _e('Lazy Load Blurred Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="lazy-loading-blur" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lazy-loading-blur', 'on'), 'on'); ?>> <span class="firstinput adamlabsgallery-tooltip-wrap" title="<?php _e('Enable Lazy Load Blurred Images, that will be shown before the selected image is loaded', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="lazy-loading-blur" value="off" <?php checked($base->getVar($grid['params'], 'lazy-loading-blur', 'on'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Disabled Lazy Load Blurred Images, that will be shown before the selected image is loaded', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p class="lazy-load-wrap">
							<label for="lazy-loading" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Background color of media during the lazy loading progress', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Lazy Load Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input name="lazy-load-color" type="text" id="lazy-load-color" value="<?php echo $base->getVar($grid['params'], 'lazy-load-color', '#FFFFFF'); ?>">
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Spacings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label class="adamlabsgallery-tooltip-wrap" for="spacings" title="<?php _e('Spaces between items vertical and horizontal', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Item Spacing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input class="input-settings-small firstinput" type="text" name="spacings" value="<?php echo $base->getVar($grid['params'], 'spacings', '0', 'i'); ?>" /> px
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Paddings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="min-width:620px">
						<p>
							<?php
							$grid_padding = $base->getVar($grid['params'], 'grid-padding', '0');
							if(!is_array($grid_padding)) $grid_padding = array('0', '0', '0', '0');
							?>
							<label for="grid-padding"><?php _e('Whole Grid Padding', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Top of the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Top:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><input class="input-settings-small" type="text" style="margin-right:10px" name="grid-padding[]" value="<?php echo @$grid_padding[0]; ?>" />
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Right of the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Right:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><input class="input-settings-small" type="text" style="margin-right:10px" name="grid-padding[]" value="<?php echo @$grid_padding[1]; ?>" />
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Bottom of the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Bottom:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><input class="input-settings-small" type="text" style="margin-right:10px" name="grid-padding[]" value="<?php echo @$grid_padding[2]; ?>" />
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Left of the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Left:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><input class="input-settings-small" type="text" style="margin-right:10px" name="grid-padding[]" value="<?php echo @$grid_padding[3]; ?>" />
						</p>
					</div>
				</div>

			</div>
		</div>

		<!--
		SKIN SETTINGS
		-->
		<div id="adamlabsgallery-settings-skins-settings" class="adamlabsgallery-settings-container active-esc">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Background', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="main-background-color" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Background Color of the Grid. Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Main Background Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input name="main-background-color" type="text" id="main-background-color" value="<?php echo $base->getVar($grid['params'], 'main-background-color', 'transparent'); ?>">
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Navigation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="navigation-skin" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select the skin/color of the Navigation', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Choose Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select id="navigation-skin-select" name="navigation-skin" style="margin-right:10px" >
								<?php
								foreach($navigation_skins as $skin){
									?>
									<option value="<?php echo $skin['handle']; ?>"<?php selected($nav_skin_choosen, $skin['handle']); ?>><?php echo $skin['name']; ?></option>
									<?php
								}
								?>
							</select>
							<a id="adamlabsgallery-edit-navigation-skin" class="button-primary adamlabsgallery-tooltip-wrap" title="<?php _e('Edit the selected Navigation Skin Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" href="javascript:void(0);"><?php _e('Edit Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a id="adamlabsgallery-create-navigation-skin" class="button-primary adamlabsgallery-tooltip-wrap" title="<?php _e('Create a new Navigation Skin Style', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"href="javascript:void(0);"><?php _e('Create Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a id="adamlabsgallery-delete-navigation-skin" class="button-primary adamlabsgallery-tooltip-wrap" title="<?php _e('Delete the selected Navigation Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"href="javascript:void(0);"><?php _e('Delete Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Item Template', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc adamlabsgallery-photoshop-bg">
						<div id="adamlabsgallery-selected-skins-wrapper">
							<div id="adamlabsgallery-selected-skins-default">
								<?php
								$skins_c = new AdamLabsGallery_Item_Skin();
								$navigation_c = new AdamLabsGallery_Navigation();
								$grid_c = new AdamLabsGallery();

								$grid_skin_sel['id'] = 'even';
								$grid_skin_sel['name'] = __('Skin Selector', ADAMLABS_GALLERY_TEXTDOMAIN);
								$grid_skin_sel['handle'] = 'skin-selector';
								$grid_skin_sel['postparams'] = array();
								$grid_skin_sel['layers'] = array();
								$grid_skin_sel['params'] = array('navigation-skin' => ''); //leave empty, we use no skin

								$skins_html = '';
								$skins_css = '';
								$filters = array();

								$skins = $skins_c->get_adamlabsgallery_item_skins();

								$demo_img = array();
								for($i=1; $i<=18; $i++){
									$demo_img[] = 'demo_template_1.jpg';
								}

								if(!empty($skins) && is_array($skins)){
									$src = array();

									$do_only_first = false;

									if($entry_skin_choosen == '0') $do_only_first = true; //only add the selected on the first element if we create a new grid, so we select the firs skin

									foreach($skins as $skin){

										// 2.2.6
										if(is_array($skin) && array_key_exists('handle', $skin) && $skin['handle'] === 'adamlabsgalleryblankskin') continue;

										if(empty($src)) $src = $demo_img;

										$item_skin = new AdamLabsGallery_Item_Skin();
										$item_skin->init_by_data($skin);

										//set filters
										$item_skin->set_skin_choose_filter();

										//set demo image
										$img_key = array_rand($src);
										$item_skin->set_image($src[$img_key]);
										unset($src[$img_key]);

										$item_filter = $item_skin->get_filter_array();

										$filters = array_merge($item_filter, $filters);

										//add skin specific css
										$item_skin->register_skin_css();

										ob_start();
										if($do_only_first){
											$item_skin->output_item_skin('skinchoose', '-1'); //-1 = will do select
											$do_only_first = false;
										}else{
											$item_skin->output_item_skin('skinchoose', $entry_skin_choosen);
										}

										$skins_html.= ob_get_contents();
										ob_clean();
										ob_end_clean();

										ob_start();
										$item_skin->generate_element_css('skinchoose');
										$skins_css.= ob_get_contents();
										ob_clean();
										ob_end_clean();
									}
								}

								$grid_c->init_by_data($grid_skin_sel);

								echo '<div id="adamlabsgallery-grid-'.$handle.'-1-wrapper">';

								$grid_c->output_wrapper_pre();

								$filters = array_map("unserialize", array_unique(array_map("serialize", $filters))); //filter to unique elements

								$navigation_c->set_filter($filters);
								$navigation_c->set_style('padding', '10px 0 0 0');

								echo '<div style="text-align: center;">';
								echo $navigation_c->output_filter('skinchoose');
								echo $navigation_c->output_pagination();
								echo '</div>';

								$grid_c->output_grid_pre();

								//output elements
								echo $skins_html;

								$grid_c->output_grid_post();

								$grid_c->output_wrapper_post();

								echo '</div>';

								echo $skins_css;
							?>
							</div>
							<script type="text/javascript">

								jQuery('#adamlabsgallery-grid-even-1').adamlabsgallery({
									layout:"masonry",
									forceFullWidth:"off",
									row:3,
									space:20,
									responsiveEntries: [
														{ width:1400,amount:3},
														{ width:1170,amount:3},
														{ width:1024,amount:3},
														{ width:960,amount:3},
														{ width:778,amount:2},
														{ width:640,amount:2},
														{ width:480,amount:2}
														],
									pageAnimation:"scale",
									startAnimation:"none",
									startAnimationSpeed: 0,
									startAnimationDelay: 0,
									animSpeed:800,
									animDelay:"on",
									delayBasic:0.4,
									aspectratio:"4:3",
									rowItemMultiplier : "",
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--
		ANIMATION SETTINGS
		-->
		<div id="adamlabsgallery-settings-animations-settings" class="adamlabsgallery-settings-container <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>adamlabsgallery-pro-disabled<?php endif; ?>">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Start Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="grid-start-animation" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select the Animation for the Start Effect', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Animation Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select id="grid-start-animation" name="grid-start-animation" style="width: 152px">
								<?php
								foreach($start_animations as $handle => $name){
									?>
									<option value="<?php echo $handle; ?>"<?php selected($grid_start_animation_choosen, $handle); ?>><?php echo $name; ?></option>
									<?php
								}
								?>
							</select>
							<input type="hidden" id="hide-markup-before-load" name="hide-markup-before-load" value="<?php echo $base->getVar($grid['params'], 'hide-markup-before-load', 'off'); ?>"> 
							
						</p>
						<p id="start-animation-speed-wrap">
							<label for="grid-start-animation-speed" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Speed (per item)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Transition Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="slider-grid-start-animation-speed" class="slider-settings"></span>
							<input class="input-settings-small" type="text" id="grid-start-animation-speed" name="grid-start-animation-speed" value="<?php echo $base->getVar($grid['params'], 'grid-start-animation-speed', '1000', 'i'); ?>" readonly="true" /> ms
						</p>
						<div id="start-animation-delay-wrap">
							<p>
								<label for="grid-start-animation-delay" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Create staggered animations by adding a delay value', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Transition Delay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span id="slider-grid-start-animation-delay" class="slider-settings"></span>
								<input class="input-settings-small" type="text" id="grid-start-animation-delay" name="grid-start-animation-delay" value="<?php echo $base->getVar($grid['params'], 'grid-start-animation-delay', '100', 'i'); ?>" readonly="true" />
							</p>
							
							<p>
								<label for="grid-start-animation-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate columns, rows or items individually', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Apply Delay to', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								
								<?php $start_animation_type = $base->getVar($grid['params'], 'grid-start-animation-type', 'item'); ?>
								<input type="radio" name="grid-start-animation-type" value="item" class="firstinput" <?php checked($start_animation_type, 'item'); ?>>
								<span class="adamlabsgallery-tooltip-wrap tooltipstered">Items</span>
								
								<input type="radio" name="grid-start-animation-type" value="col" <?php checked($start_animation_type, 'col'); ?>>
								<span class="adamlabsgallery-tooltip-wrap tooltipstered">Columns</span>
								
								<input type="radio" name="grid-start-animation-type" value="row" <?php checked($start_animation_type, 'row'); ?>>
								<span class="adamlabsgallery-tooltip-wrap tooltipstered">Rows</span>
								
							</p>
							
						</div>
						<div id="start-animation-viewport-wrap">
						
							<p>
								<label for="grid-start-animation-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate when the grid is scrolled into view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Wait for viewport', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								
								<?php $start_anime_viewport = $base->getVar($grid['params'], 'start-anime-in-viewport', 'off'); ?>
								<input type="radio" name="start-anime-in-viewport" value="on" class="firstinput start-anime-viewport" <?php checked($start_anime_viewport, 'on'); ?>>
								<span class="adamlabsgallery-tooltip-wrap tooltipstered">On</span>
								
								<input type="radio" name="start-anime-in-viewport" value="off" class="start-anime-viewport" <?php checked($start_anime_viewport, 'off'); ?>>
								<span class="adamlabsgallery-tooltip-wrap tooltipstered">Off</span>
								
							</p>
							
							<p id="start-animation-viewport-buffer">
								<label for="start-anime-viewport-buffer" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Wait for grid to be (x)% in view', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Viewport buffer', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input style="display: inline-block; vertical-align: middle; margin-left: 0" id="start-anime-viewport-buffer" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="start-anime-viewport-buffer" value="<?php echo $base->getVar($grid['params'], 'start-anime-viewport-buffer', '20'); ?>" title="<?php _e('Zoom Out Percentage (0-100)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</p>
						
						</div>
						
					</div>
				</div>
				
				<div class="divider1"></div>
				
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Filter/Pagination Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
					
						<p>
							<label for="grid-animation" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select the Animation for the Filter Page Change Effects', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Animation Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select id="grid-animation-select" name="grid-animation" style="width: 152px">
								<?php
								foreach($grid_animations as $handle => $name){
									?>
									<option value="<?php echo $handle; ?>"<?php selected($grid_animation_choosen, $handle); ?>><?php echo $name; ?></option>
									<?php
								}
								?>
							</select>
							
						</p>
						<p>
							<label for="grid-animation-speed" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Filter Animation Speed (per item)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Transition Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="slider-grid-animation-speed" class="slider-settings"></span>
							<input class="input-settings-small" type="text" name="grid-animation-speed" value="<?php echo $base->getVar($grid['params'], 'grid-animation-speed', '1000', 'i'); ?>" readonly="true" /> ms
						</p>
						<p>
							<label for="grid-animation-delay" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Create staggered animations by adding a delay value', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Transition Delay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<span id="slider-grid-animation-delay" class="slider-settings"></span>
							<input class="input-settings-small" type="text" name="grid-animation-delay" value="<?php echo $base->getVar($grid['params'], 'grid-animation-delay', '1', 'i'); ?>" readonly="true" />
						</p>
						
						<p id="animation-delay-type-wrap">
							<label for="grid-animation-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate columns, rows or items individually', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Apply Delay to', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							
							<?php $animation_type = $base->getVar($grid['params'], 'grid-animation-type', 'item'); ?>
							<input type="radio" name="grid-animation-type" value="item" class="firstinput" <?php checked($animation_type, 'item'); ?>>
							<span class="adamlabsgallery-tooltip-wrap tooltipstered">Items</span>
							
							<input type="radio" name="grid-animation-type" value="col" <?php checked($animation_type, 'col'); ?>>
							<span class="adamlabsgallery-tooltip-wrap tooltipstered">Columns</span>
							
							<input type="radio" name="grid-animation-type" value="row" <?php checked($animation_type, 'row'); ?>>
							<span class="adamlabsgallery-tooltip-wrap tooltipstered">Rows</span>
							
						</p>
					
					</div>
					
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Hover Animations', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="grid-item-animation" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate the entire Grid Item on Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Item Hover Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select id="grid-item-animation" class="grid-item-anime-select" name="grid-item-animation" >
								<?php
								foreach($grid_item_animations as $handle => $name){
									?>
									<option value="<?php echo $handle; ?>"<?php selected($grid_item_animation_choosen, $handle); ?>><?php echo $name; ?></option>
									<?php
								}
								?>
							</select>
							<span class="grid-item-anime-wrap-zoomin grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-zoomin" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-zoomin" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-zoomin', '125'); ?>" title="<?php _e('Zoom In Percentage (100-200)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-zoomout grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-zoomout" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-zoomout" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-zoomout', '75'); ?>" title="<?php _e('Zoom Out Percentage (0-100)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-fade grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-fade" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-fade" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-fade', '75'); ?>" title="<?php _e('Fade Percentage (0-100)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-blur grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-blur" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-blur" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-blur', '5'); ?>" title="<?php _e('Blur Amount (0-20)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> px
							</span>
							<span class="grid-item-anime-wrap-rotate grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-rotate" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-rotate" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-rotate', '30'); ?>" title="<?php _e('Blur Amount (0-360)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> deg
							</span>
							<span class="grid-item-anime-wrap-shift grid-item-anime-option">
								<select name="grid-item-animation-shift" style="margin-left: 15px">
									<?php
									$grid_item_anime_shift = $base->getVar($grid['params'], 'grid-item-animation-shift', 'up');
									?>
									<option value="up"<?php selected($grid_item_anime_shift, 'up'); ?>><?php _e('Up', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="down"<?php selected($grid_item_anime_shift, 'down'); ?>><?php _e('Down', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="left"<?php selected($grid_item_anime_shift, 'left'); ?>><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="right"<?php selected($grid_item_anime_shift, 'right'); ?>><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
								<input style="display: inline-block; vertical-align: middle" id="grid-item-animation-shift-amount" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-animation-shift-amount" value="<?php echo $base->getVar($grid['params'], 'grid-item-animation-shift-amount', '10'); ?>" title="<?php _e('Shift Amount in pixels', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> px
							</span>
						</p>
						<p>
							<label for="grid-item-animation-other" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animate other Grid Items on Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Item Other Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select id="grid-item-animation-other" class="grid-item-anime-select" name="grid-item-animation-other" >
								<?php
								foreach($grid_item_animations as $handle => $name){
									?>
									<option value="<?php echo $handle; ?>"<?php selected($grid_item_animation_other, $handle); ?>><?php echo $name; ?></option>
									<?php
								}
								?>
							</select>
							<span class="grid-item-anime-wrap-zoomin grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-zoomin" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-zoomin" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-zoomin', '125'); ?>" title="<?php _e('Zoom In Percentage (100-200)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-zoomout grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-zoomout" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-zoomout" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-zoomout', '75'); ?>" title="<?php _e('Zoom Out Percentage (0-100)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-fade grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-fade" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-fade" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-fade', '75'); ?>" title="<?php _e('Fade Percentage (0-100)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> %
							</span>
							<span class="grid-item-anime-wrap-blur grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-blur" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-blur" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-blur', '5'); ?>" title="<?php _e('Blur Amount (0-20)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> px
							</span>
							<span class="grid-item-anime-wrap-rotate grid-item-anime-option">
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-rotate" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-rotate" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-rotate', '30'); ?>" title="<?php _e('Blur Amount (0-360)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> deg
							</span>
							<span class="grid-item-anime-wrap-shift grid-item-anime-option">
								<select name="grid-item-other-shift" style="margin-left: 15px">
									<?php
									$grid_item_other_shift = $base->getVar($grid['params'], 'grid-item-other-shift', 'up');
									?>
									<option value="up"<?php selected($grid_item_other_shift, 'up'); ?>><?php _e('Up', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="down"<?php selected($grid_item_other_shift, 'down'); ?>><?php _e('Down', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="left"<?php selected($grid_item_other_shift, 'left'); ?>><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="right"<?php selected($grid_item_other_shift, 'right'); ?>><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
								<input style="display: inline-block; vertical-align: middle" id="grid-item-other-shift-amount" class="input-settings-small adamlabsgallery-tooltip-wrap" type="text" name="grid-item-other-shift-amount" value="<?php echo $base->getVar($grid['params'], 'grid-item-other-shift-amount', '10'); ?>" title="<?php _e('Shift Amount in pixels', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" /> px
							</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!--
		NAVIGATION SETTINGS
		-->
		<div id="adamlabsgallery-settings-filterandco-settings" class="adamlabsgallery-settings-container <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>adamlabsgallery-pro-disabled<?php endif; ?>">
			<div class="">
				<?php
				/*
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Navigation Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="min-width:710px">
						<p>
							<label for="navigation-container"><?php _e("Type", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="nagivation-type" <?php checked($base->getVar($grid['params'], 'nagivation-type', 'internal'), 'internal'); ?> class="firstinput adamlabsgallery-tooltip-wrap" title="<?php _e('Decide in Grid Settings how the navigation will look like', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="internal"><?php _e('Internal', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="nagivation-type" <?php checked($base->getVar($grid['params'], 'nagivation-type', 'internal'), 'external'); ?> class="adamlabsgallery-tooltip-wrap" title="<?php _e('User the API to generate your navigation to your likings', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="external"><?php _e('External', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="nagivation-type" <?php checked($base->getVar($grid['params'], 'nagivation-type', 'internal'), 'widget'); ?> class="adamlabsgallery-tooltip-wrap" title="<?php _e('Create/Choose a widget area for the navigation', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" value="widget"><?php _e('Widget Area', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
					</div>
					<div class="clear"></div>
				</div>
				*/ ?>

				<div id="es-ng-layout-wrapper">
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Navigation Positions', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>
						<!-- 2.1.5 added overflow: auto -->
						<div class="adamlabsgallery-cs-tbc" style="min-width:710px; overflow: auto">
							<?php
							$layout = $base->getVar($grid['params'], 'navigation-layout', array());
							$navig_special_class = $base->getVar($grid['params'], 'navigation-special-class', array());
							$navig_special_skin = $base->getVar($grid['params'], 'navigation-special-skin', array());
							?>
							<script type="text/javascript">
								var adamlabsgallery_nav_special_class = <?php echo json_encode($navig_special_class); ?>;
								var adamlabsgallery_nav_special_skin = <?php echo json_encode($navig_special_skin); ?>;
							</script>
							<div>
								<div class="adamlabsgallery-navigation-cons-outter">
									<div class="adamlabsgallery-navigation-cons-title"><?php _e('Available Modules:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
									<div class="adamlabsgallery-navigation-cons-wrapper adamlabsgallery-tooltip-wrap adamlabsgallery-navigation-default-wrap" title="<?php _e('Drag and Drop Navigation Modules into the Available Drop Zones', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
										<div data-navtype="left" class="adamlabsgallery-navigation-cons-left adamlabsgallery-navigation-cons"<?php echo (isset($layout['left']) && $layout['left'] !== '') ? ' data-putin="'.current(array_keys($layout['left'])).'" data-sort="'.$layout['left'][current(array_keys($layout['left']))].'"' : ''; ?>><i class="adamlabsgallery-icon-left-open"></i></div>
										<div data-navtype="right" class="adamlabsgallery-navigation-cons-right adamlabsgallery-navigation-cons"<?php echo (isset($layout['right']) && $layout['right'] !== '') ? ' data-putin="'.current(array_keys($layout['right'])).'" data-sort="'.$layout['right'][current(array_keys($layout['right']))].'"' : ''; ?>><i class="adamlabsgallery-icon-right-open"></i></div>
										<div data-navtype="pagination" class="adamlabsgallery-navigation-cons-pagination adamlabsgallery-navigation-cons"<?php echo (isset($layout['pagination']) && $layout['pagination'] !== '') ? ' data-putin="'.current(array_keys($layout['pagination'])).'" data-sort="'.$layout['pagination'][current(array_keys($layout['pagination']))].'"' : ''; ?>><i class="adamlabsgallery-icon-doc-inv"></i><?php _e("Pagination", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										<div data-navtype="filter" class="adamlabsgallery-navigation-cons-filter adamlabsgallery-navigation-cons"<?php echo (isset($layout['filter']) && $layout['filter'] !== '') ? ' data-putin="'.current(array_keys($layout['filter'])).'" data-sort="'.$layout['filter'][current(array_keys($layout['filter']))].'"' : ''; ?>><i class="adamlabsgallery-icon-megaphone"></i><?php _e("Filter 1", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										<?php
										if(AdamLabsGallery_Woocommerce::is_woo_exists()){
											?>
											<div data-navtype="cart" class="adamlabsgallery-navigation-cons-cart adamlabsgallery-navigation-cons"<?php echo (isset($layout['cart']) && $layout['cart'] !== '') ? ' data-putin="'.current(array_keys($layout['cart'])).'" data-sort="'.$layout['cart'][current(array_keys($layout['cart']))].'"' : ''; ?>><i class="adamlabsgallery-icon-basket"></i><?php _e("Cart", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											<?php
										}

										//add extra filters
										if(!empty($layout)){
											foreach($layout as $key => $val){
												if(strpos($key, 'filter-') !== false){
													$nr = str_replace('filter-', '', $key);
													?>
													<div data-navtype="filter-<?php echo $nr; ?>" class="adamlabsgallery-navigation-cons-filter-<?php echo $nr; ?> adamlabsgallery-nav-cons-filter adamlabsgallery-navigation-cons"<?php echo ' data-putin="'.current(array_keys($layout[$key])).'" data-sort="'.$layout[$key][current(array_keys($layout[$key]))].'"'; ?>><i class="adamlabsgallery-icon-megaphone"></i><?php _e("Filter", ADAMLABS_GALLERY_TEXTDOMAIN); echo ' '.$nr; ?></div>
													<?php
												}
											}
										}
										?>
										<div data-navtype="sort" class="adamlabsgallery-navigation-cons-sort adamlabsgallery-navigation-cons"<?php echo (isset($layout['sorting']) && $layout['sorting'] !== '') ? ' data-putin="'.current(array_keys($layout['sorting'])).'" data-sort="'.$layout['sorting'][current(array_keys($layout['sorting']))].'"' : ''; ?>><i class="adamlabsgallery-icon-sort-name-up"></i><?php _e("Sort", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										<div data-navtype="search-input" class="adamlabsgallery-navigation-cons-search-input adamlabsgallery-navigation-cons"<?php echo (isset($layout['search-input']) && $layout['search-input'] !== '') ? ' data-putin="'.current(array_keys($layout['search-input'])).'" data-sort="'.$layout['search-input'][current(array_keys($layout['search-input']))].'"' : ''; ?>><i class="adamlabsgallery-icon-search"></i><?php _e("Search", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>

										<div class="adamlabsgallery-stay-last-element" style="clear:both"></div>
									</div>
								</div>

								<div id="adamlabsgallery-navigations-drag-wrap" style="float:left;">
									<div class="adamlabsgallery-navigation-cons-title"><?php _e('Controls inside Grid:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
									<div id="adamlabsgallery-navigations-sort-top-1" class="adamlabsgallery-navigation-drop-wrapper adamlabsgallery-tooltip-wrap" title="<?php _e('Move the Navigation Modules to define the Order of Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('DROPZONE - TOP - 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
									<div id="adamlabsgallery-navigations-sort-top-2" class="adamlabsgallery-navigation-drop-wrapper adamlabsgallery-tooltip-wrap" title="<?php _e('Move the Navigation Modules to define the Order of Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('DROPZONE - TOP - 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
									<div id="adamlabsgallery-navigations-items-bg" >
										<div class="adamlabsgallery-navconstrctor-pi1"></div>
										<div class="adamlabsgallery-navconstrctor-pi2"></div>
										<div class="adamlabsgallery-navconstrctor-pi3"></div>
										<div class="adamlabsgallery-navconstrctor-pi4"></div>
										<div class="adamlabsgallery-navconstrctor-pi5"></div>
										<div class="adamlabsgallery-navconstrctor-pi6"></div>
										<div id="adamlabsgallery-navigations-sort-left" class="adamlabsgallery-navigation-drop-wrapper"><?php _e('DROPZONE <br> LEFT', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
										<div id="adamlabsgallery-navigations-sort-right" class="adamlabsgallery-navigation-drop-wrapper"><?php _e('DROPZONE <br> RIGHT', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
									</div>
									<div id="adamlabsgallery-navigations-sort-bottom-1" class="adamlabsgallery-navigation-drop-wrapper adamlabsgallery-tooltip-wrap" title="<?php _e('Move the Navigation Modules to define the Order of Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('DROPZONE - BOTTOM - 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
									<div id="adamlabsgallery-navigations-sort-bottom-2" class="adamlabsgallery-navigation-drop-wrapper adamlabsgallery-tooltip-wrap" title="<?php _e('Move the Navigation Modules to define the Order of Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('DROPZONE - BOTTOM - 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div></div>
								</div>
								<div id="adamlabsgallery-external-drag-wrap">
									<div class="adamlabsgallery-navigation-cons-title"><?php _e('Controls anywhere on Page (through ShortCode):', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
									<div id="adamlabsgallery-navigation-external-description">
											<div style="width:132px" class="adamlabsgallery-ext-nav-desc"><?php _e('Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											<div style="width:164px" class="adamlabsgallery-ext-nav-desc"><?php _e('ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											<div style="width:164px" class="adamlabsgallery-ext-nav-desc"><?php _e('Additional Class', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
											<div style="width:164px"class="adamlabsgallery-ext-nav-desc"><?php _e('Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
										</div>
									<div id="adamlabsgallery-navigations-sort-external" class="adamlabsgallery-navigation-drop-wrapper" style="width: 600px; height:316px;">

										<?php _e('DROPZONE - EXTERNAL', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div class="adamlabsgallery-navigation-drop-inner"></div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
							<div style="width:100%;height:25px;clear:both"></div>
						</div>
					</div>

					<div class="divider1"></div>

					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Grid Internal Controls Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>
						<div class="adamlabsgallery-cs-tbc">

							<!--  DROPZONE 1 ALIGN -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Top 1", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="top-1-align" value="left" class="firstinput" <?php checked($base->getVar($grid['params'], 'top-1-align', 'center'), 'left'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" ><?php _e("Left", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="top-1-align" value="center" <?php checked($base->getVar($grid['params'], 'top-1-align', 'center'), 'center'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" ><?php _e("Center", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="top-1-align" value="right" <?php checked($base->getVar($grid['params'], 'top-1-align', 'center'), 'right'); ?>><span style="margin-right:25px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Right", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space under the Zone', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="top-1-margin-bottom" value="<?php echo $base->getVar($grid['params'], 'top-1-margin-bottom', '0', 'i'); ?>"> px

							</p>
							<!--  DROPZONE 2 ALIGN -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Top 2", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="top-2-align" value="left" class="firstinput" <?php checked($base->getVar($grid['params'], 'top-2-align', 'center'), 'left'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Left", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="top-2-align" value="center" <?php checked($base->getVar($grid['params'], 'top-2-align', 'center'), 'center'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Center", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="top-2-align" value="right" <?php checked($base->getVar($grid['params'], 'top-2-align', 'center'), 'right'); ?>><span style="margin-right:25px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Right", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space under the Zone', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="top-2-margin-bottom" value="<?php echo $base->getVar($grid['params'], 'top-2-margin-bottom', '0', 'i'); ?>"> px
							</p>
							<!--  DROPZONE 3 ALIGN -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Bottom 1", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="bottom-1-align" value="left" class="firstinput" <?php checked($base->getVar($grid['params'], 'bottom-1-align', 'center'), 'left'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Left", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="bottom-1-align" value="center" <?php checked($base->getVar($grid['params'], 'bottom-1-align', 'center'), 'center'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Center", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="bottom-1-align" value="right" <?php checked($base->getVar($grid['params'], 'bottom-1-align', 'center'), 'right'); ?>><span style="margin-right:25px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Right", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space above the Zone', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="bottom-1-margin-top" value="<?php echo $base->getVar($grid['params'], 'bottom-1-margin-top', '0', 'i'); ?>"> px
							</p>

							<!--  DROPZONE 4 ALIGN -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Bottom 2", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="bottom-2-align" value="left" class="firstinput" <?php checked($base->getVar($grid['params'], 'bottom-2-align', 'center'), 'left'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Left", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="bottom-2-align" value="center" <?php checked($base->getVar($grid['params'], 'bottom-2-align', 'center'), 'center'); ?>><span class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Center", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="bottom-2-align" value="right" <?php checked($base->getVar($grid['params'], 'bottom-2-align', 'center'), 'right'); ?>><span style="margin-right:25px" class="adamlabsgallery-tooltip-wrap" title="<?php _e('All Buttons in this Zone Align to the Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Right", ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space above the Zone', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="bottom-2-margin-top" value="<?php echo $base->getVar($grid['params'], 'bottom-2-margin-top', '0', 'i'); ?>"> px
							</p>

							<!--  DROPZONE LEFT  -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Left", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space Horizontal the Zone (negative / positive values)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" ><?php _e('Margin Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="left-margin-left" value="<?php echo $base->getVar($grid['params'], 'left-margin-left', '0', 'i'); ?>"> px
							</p>

							<!--  DROPZONE RIGHT -->
							<p>
								<label for="navigation-container"><?php _e("Dropzone Right", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Space Horizontal the Zone (negative / positive values)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Margin Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input class="input-settings-small firstinput" type="text" name="right-margin-right" value="<?php echo $base->getVar($grid['params'], 'right-margin-right', '0', 'i'); ?>"> px
							</p>

						</div>
					</div>
					<div class="divider1"></div>
				</div>

				<!--div id="es-ng-external-wrapper">
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Navigation API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>
						<div class="adamlabsgallery-cs-tbc" style="min-width:710px">
							<pre>

							</pre>
						</div>
					</div>
				</div>
				<div id="es-ng-widget-wrapper">
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Widget Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>
						<div class="adamlabsgallery-cs-tbc" style="min-width:710px">
							<p><?php _e('Please add the Portfolio Gallery Navigation Widgets in the Widget Area you want to use.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>

							<?php
							//for later usage
							//$wa->get_all_registered_sidebars();
							?>
						</div>
					</div>
				</div---->

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Module Spaces', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<!--  MODULE SPACINGS -->
						<p>
							<label for="navigation-container" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Spaces horizontal between the Navigation Modules', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e("Module Spacing", ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input class="input-settings-small firstinput" type="text" name="module-spacings" value="<?php echo $base->getVar($grid['params'], 'module-spacings', '5', 'i'); ?>"> px
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Pagination Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="pagination-numbers"><?php _e('Page Number Option', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="pagination-numbers" value="smart" class="firstinput" <?php checked($base->getVar($grid['params'], 'pagination-numbers', 'smart'), 'smart'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Will show pagination like: 1 2 ... 5 6', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Smart', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="pagination-numbers" value="full" <?php checked($base->getVar($grid['params'], 'pagination-numbers', 'smart'), 'full'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Will show full pagination like: 1 2 3 4 5 6', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label for="pagination-scroll"><?php _e('Scroll To Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="pagination-scroll" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'pagination-scroll', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Scroll to top if pagination is clicked', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="pagination-scroll" value="off" <?php checked($base->getVar($grid['params'], 'pagination-scroll', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do nothing if pagination is clicked', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label for="pagination-scroll-offset" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define an offset for the scrolling position', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Scroll To Offset', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input class="input-settings-small firstinput" type="text" name="pagination-scroll-offset" value="<?php echo $base->getVar($grid['params'], 'pagination-scroll-offset', '0', 'i'); ?>"> px
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings filter_groups">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Filter Groups', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="padding-bottom: 10px;">
						<p>
							<label for="filter-arrows"><?php _e('Filter Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="filter-arrows" value="single" class="firstinput" <?php checked($base->getVar($grid['params'], 'filter-arrows', 'single'), 'single'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Filter is based on 1 Selected Filter in same time.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Single', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="filter-arrows" value="multi" <?php checked($base->getVar($grid['params'], 'filter-arrows', 'single'), 'multi'); ?>> <span  class="adamlabsgallery-tooltip-wrap" title="<?php _e('Filter is based on 1 or more Filters in same time.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Multiple', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p class="adamlabsgallery-filter-logic" style="display: none;">
							<label for="filter-logic"><?php _e('Filter Logic', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="filter-logic" value="and" class="firstinput" <?php checked($base->getVar($grid['params'], 'filter-logic', 'or'), 'and'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Shows all elements that meet ONE OR MORE of the selected filters', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('AND', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="filter-logic" value="or" <?php checked($base->getVar($grid['params'], 'filter-logic', 'or'), 'or'); ?>> <span  class="adamlabsgallery-tooltip-wrap" title="<?php _e('Shows all elements that meet ALL of the selected filters', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('OR', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p class="adamlabsgallery-filter-start">
							<label for="filter-start" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Grid starts with this filter(filters comma separated) active. Take slug from below or leave empty to disable.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Start with Filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="filter-start" value="<?php echo $base->getVar($grid['params'], 'filter-start', ''); ?>" class="firstinput">
						</p>
						<p>
							<label for="filter-deep-link" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Deep Link to select filter by adding # plus the slug to the loading URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Deep Linking', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="filter-deep-link" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'filter-deep-link', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Deep Linking with #slug possible', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="filter-deep-link" value="off" <?php checked($base->getVar($grid['params'], 'filter-deep-link', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('No Deep Linking with #slug', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						
						<p>
							<label for="filter-show-on"><?php _e('Dropdown Elements on', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="filter-show-on" value="click" class="firstinput" <?php checked($base->getVar($grid['params'], 'filter-show-on', 'hover'), 'click'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Filter in Dropdown will be shown on click', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Click', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="filter-show-on" value="hover" <?php checked($base->getVar($grid['params'], 'filter-show-on', 'hover'), 'hover'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Filter in Dropdown will be shown on hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						
						<p id="convert_mobile_filters">
							<label for="convert-mobile-filters"><?php _e('Mobile Filter Conversion', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="convert-mobile-filters" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'convert-mobile-filters', 'off'), 'on'); ?>> 
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose to convert "Inline" filter layouts to "Dropdown" on mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="convert-mobile-filters" value="off" <?php checked($base->getVar($grid['params'], 'convert-mobile-filters', 'off'), 'off'); ?>> 
							<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose to convert "Inline" filter layouts to "Dropdown" on mobile', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						
						<!--<div style="float: left; width: 170px;">
							<p><?php _e('Filter All Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
							<p><?php _e('Layout Option', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
							<p class="filter-only-if-dropdown"><?php _e('Dropdown Start Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
							<div class="filter-only-for-post" style="margin-bottom: 15px;">
								<p><?php _e('Use Filter Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
							</div>
						</div>-->
						<div class="adamlabsgallery-original-filter-options-holder">

							<div class="adamlabsgallery-original-filter-options-wrap adamlabsgallery-filter-options-wrap">
								<div class="adamlabsgallery-filter-header-block"><i class="adamlabsgallery-icon-megaphone"></i><?php _e('Filter -', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <span class="filter-header-id">1</span></div>
								
								<?php $filterallon = $base->getVar($grid['params'], 'filter-all-visible', 'on'); ?>
								<p class="adamlabsgallery-filter-label"><?php _e('Show/Hide Filter "All" Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
								<p class="adamlabsgallery-filter-option-field">
									<input type="radio" name="filter-all-visible" data-origname="filter-all-visible-#NR" value="on" class="firstinput filtervisible" style="margin-left: 5px" <?php checked($filterallon, 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Filter All button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									<input type="radio" name="filter-all-visible" data-origname="filter-all-visible-#NR" value="off" class="filtervisible" <?php checked($filterallon, 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Hide the Filter All button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								</p>
								
								<?php $filtervisible = $base->getVar($grid['params'], 'filter-all-visible', 'on') === 'on' ? 'block' : 'none'; ?>
								<div class="adamlabsgallery-filter-visible">
									<p class="adamlabsgallery-filter-label"><?php _e('Filter "All" Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<p class="adamlabsgallery-filter-option-field adamlabsgallery-tooltip-wrap" title="<?php _e('Visible Title for the ALL Filter Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
										<input type="text" name="filter-all-text" data-origname="filter-all-text-#NR" value="<?php echo $base->getVar($grid['params'], 'filter-all-text', __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>" class="firstinput">
										<span class="adamlabsgallery-remove-filter-tab" style="display: none;"><i class="adamlabsgallery-icon-cancel"></i></span>
									</p>
								</div>
								
								<p class="adamlabsgallery-filter-label"><?php _e('Layout Option', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
								<p class="adamlabsgallery-filter-option-field">
									<?php
									$filter_listing = $base->getVar($grid['params'], 'filter-listing', 'list');
									?>
									<select class="firstinput" name="filter-listing" data-origname="filter-listing-#NR">
										<option value="list" <?php selected($filter_listing, 'list'); ?>><?php _e('In Line', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="dropdown" <?php selected($filter_listing, 'dropdown'); ?>><?php _e('Dropdown', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
								<p class="adamlabsgallery-filter-label"><?php _e('Dropdown Start Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
								<p class="filter-only-if-dropdown adamlabsgallery-filter-option-field adamlabsgallery-tooltip-wrap"  title="<?php _e('Default Text on the Filter Dropdown List.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<?php
									$filter_dropdown_text = $base->getVar($grid['params'], 'filter-dropdown-text', __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
									?>
									<input class="firstinput" type="text" data-origname="filter-dropdown-text-#NR" name="filter-dropdown-text" value="<?php echo $filter_dropdown_text; ?>" />
								</p>
								<p class="adamlabsgallery-filter-label"><?php _e('Show Number of Elements', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
								<p class="adamlabsgallery-filter-option-field">
									<?php
									$f_counter = $base->getVar($grid['params'], 'filter-counter', 'off');
									?>
									<select class="firstinput" name="filter-counter" data-origname="filter-counter-#NR">
										<option value="on" <?php selected($f_counter, 'on'); ?>><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="off" <?php selected($f_counter, 'off'); ?>><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
								<p class="adamlabsgallery-filter-label available-filters-in-group"><?php _e('Available Filters in Group', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
								<div class="filter-only-for-post" style="margin-top: 10px">
									<?php
									$filter_selected = $base->getVar($grid['params'], 'filter-selected', '');
									$filter_startup = false;
									?>
									<div class="adamlabsgallery-media-source-order-wrap adamlabsgallery-filter-selected-order-wrap">
										<?php
										if(!empty($filter_selected)){

											if(!isset($grid['params']['filter-selected'])){ //we are either a new Grid or old Grid that had not this option (since 1.1.0)

												if($grid !== false){ //set the values
													$use_cat = @$categories;
												}else{
													$use_cat = @$postTypesWithCats['post'];
												}

												if(!empty($use_cat)){
													foreach($use_cat as $handle => $cat){
														if(strpos($handle, 'option_disabled_') !== false) continue;
														?>
														<div class="adamlabsgallery-media-source-order button-primary">
															<span style="float:left"><?php echo $cat; ?></span>
															<input class="adamlabsgallery-get-val adamlabsgallery-filter-input adamlabsgallery-filter-selected" type="checkbox" name="filter-selected[]" data-origname="filter-selected-#NR[]" checked="checked" value="<?php echo $handle; ?>" />
															<div style="clear:both"></div>
														</div>
														<?php
													}
												}
												$filter_startup = false;
											}else{
												foreach($filter_selected as $fs){
													?>
													<div class="adamlabsgallery-media-source-order button-primary">
														<span style="float:left"><?php echo $fs; ?></span>
														<input class="adamlabsgallery-get-val adamlabsgallery-filter-input adamlabsgallery-filter-selected" type="checkbox" name="filter-selected[]" data-origname="filter-selected-#NR[]" checked="checked" value="<?php echo $fs; ?>" />
														<div style="clear:both"></div>
													</div>
													<?php
												}
												$filter_startup = true;
											}

										}else{
											$filter_startup = false;
										}
										?>
									</div>
								</div>
								<div class="adamlabsgallery-filter-option-field adamlabsgallery-filter-option-top-m filter-only-for-post">
									<a class="adamlabsgallery-filter-add-custom-filter" href="javascript:void(0);"><i class="adamlabsgallery-icon-plus"></i></a>
								</div>
							</div>
							<?php

							$filter_counter = 1;
							//check if we have more than one filter area
							if(isset($grid['params']) && !empty($grid['params'])){
								foreach($grid['params'] as $key => $params){
									if(strpos($key, 'filter-selected-') !== false){
										$n = str_replace('filter-selected-', '', $key);
										adamlabsgallery_filter_tab_function($n, $grid['params']);
										if($filter_counter < $n) $filter_counter = $n;
									}
								}
								//if($filter_counter > 1) $filter_counter++;
							}

							function adamlabsgallery_filter_tab_function($id, $params){
								global $grid;
								global $categories;
								global $postTypesWithCats;

								$base = new AdamLabsGallery_Base();
								?>
								<div class="adamlabsgallery-filter-options-wrap" style="display:inline-block">
									<div class="adamlabsgallery-filter-header-block"><i class="adamlabsgallery-icon-megaphone"></i><?php _e('Filter -', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <span class="filter-header-id"><?php echo $id;?></span></div>
									
									<?php $filterallon = $base->getVar($params, 'filter-all-visible-'.$id, 'on'); ?>
									<p class="adamlabsgallery-filter-label"><?php _e('Show/Hide Filter "All" Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<p class="adamlabsgallery-filter-option-field">
										<input type="radio" name="filter-all-visible-<?php echo $id; ?>" data-origname="filter-all-visible-#NR" value="on" class="firstinput filtervisible" style="margin-left: 5px" <?php checked($filterallon, 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show the Filter All button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										<input type="radio" name="filter-all-visible-<?php echo $id; ?>" data-origname="filter-all-visible-#NR" value="off" class="filtervisible" <?php checked($filterallon, 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Hide the Filter All button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</p>
									
									<?php $filtervisible = $base->getVar($params, 'filter-all-visible-'.$id, 'on') === 'on' ? 'block' : 'none'; ?>
									<div class="adamlabsgallery-filter-visible" style="display: <?php echo $filtervisible; ?>">
										<p class="adamlabsgallery-filter-label"><?php _e('Filter "All" Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
										<p class="adamlabsgallery-filter-option-field adamlabsgallery-tooltip-wrap" title="<?php _e('Visible Title for the ALL Filter Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
											<input type="text" name="filter-all-text-<?php echo $id; ?>" data-origname="filter-all-text-#NR" value="<?php echo $base->getVar($params, 'filter-all-text-'.$id, __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>" class="firstinput">
											<span class="adamlabsgallery-remove-filter-tab" style="display: none;"><i class="adamlabsgallery-icon-cancel"></i></span>
										</p>
									</div>
									
									<p class="adamlabsgallery-filter-label"><?php _e('Layout Option', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<p class="adamlabsgallery-filter-option-field">
										<?php
										$filter_listing = $base->getVar($params, 'filter-listing-'.$id, 'list');
										?>
										<select class="firstinput" name="filter-listing-<?php echo $id; ?>" data-origname="filter-listing-#NR">
											<option value="list" <?php selected($filter_listing, 'list'); ?>><?php _e('In Line', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="dropdown" <?php selected($filter_listing, 'dropdown'); ?>><?php _e('Dropdown', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</p>
									<p class="adamlabsgallery-filter-label"><?php _e('Dropdown Start Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<p class="filter-only-if-dropdown adamlabsgallery-filter-option-field adamlabsgallery-tooltip-wrap" title="<?php _e('Default Text on the Filter Dropdown List.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
										<?php
										$filter_dropdown_text = $base->getVar($params, 'filter-dropdown-text-'.$id, __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
										?>
										<input class="firstinput" type="text" data-origname="filter-dropdown-text-#NR" name="filter-dropdown-text-<?php echo $id; ?>" value="<?php echo $filter_dropdown_text; ?>" />
									</p>
									<p class="adamlabsgallery-filter-label"><?php _e('Show Number of Elements', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<p class="adamlabsgallery-filter-option-field">
										<?php
										$f_counter = $base->getVar($params, 'filter-counter-'.$id, 'off');
										?>
										<select class="firstinput" name="filter-counter-<?php echo $id; ?>" data-origname="filter-counter-#NR">
											<option value="on" <?php selected($f_counter, 'on'); ?>><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="off" <?php selected($f_counter, 'off'); ?>><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</p>
									<p class="adamlabsgallery-filter-label available-filters-in-group"><?php _e('Available Filters in Group', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
									<div class="filter-only-for-post" style="margin-top: 10px">
										<?php
										$filter_selected = $base->getVar($params, 'filter-selected-'.$id, '');
										?>
										<div class="adamlabsgallery-media-source-order-wrap adamlabsgallery-filter-selected-order-wrap-<?php echo $id; ?>">
											<?php
											if(!empty($filter_selected)){

												if(!isset($params['filter-selected-'.$id])){ //we are either a new Grid or old Grid that had not this option (since 1.1.0)

													if($grid !== false){ //set the values
														$use_cat = @$categories;
													}else{
														$use_cat = @$postTypesWithCats['post'];
													}

													if(!empty($use_cat)){
														foreach($use_cat as $handle => $cat){
															if(strpos($handle, 'option_disabled_') !== false) continue;
															?>
															<div class="adamlabsgallery-media-source-order button-primary">
																<span style="float:left"><?php echo $cat; ?></span>
																<input class="adamlabsgallery-get-val adamlabsgallery-filter-input adamlabsgallery-filter-selected-<?php echo $id; ?>" type="checkbox" name="filter-selected-<?php echo $id; ?>[]" data-origname="filter-selected-#NR[]" checked="checked" value="<?php echo $handle; ?>" />
																<div style="clear:both"></div>
															</div>
															<?php
														}
													}

												}else{
													foreach($filter_selected as $fs){
														?>
														<div class="adamlabsgallery-media-source-order button-primary">
															<span style="float:left"><?php echo $fs; ?></span>
															<input class="adamlabsgallery-get-val adamlabsgallery-filter-input adamlabsgallery-filter-selected-<?php echo $id; ?>" type="checkbox" name="filter-selected-<?php echo $id; ?>[]" data-origname="filter-selected-#NR[]" checked="checked" value="<?php echo $fs; ?>" />
															<div style="clear:both"></div>
														</div>
														<?php
													}
												}

											}
											?>
										</div>
									</div>
									<div class="adamlabsgallery-filter-option-field adamlabsgallery-filter-option-top-m filter-only-for-post">
										<a class="adamlabsgallery-filter-add-custom-filter" href="javascript:void(0);"><i class="adamlabsgallery-icon-plus"></i></a>
									</div>
								</div>
								<?php
							}

							?>
						</div>
						<div class="adamlabsgallery-add-filter-box"><i class="adamlabsgallery-icon-plus"></i></div>
						<script type="text/javascript">
							var filter_startup = <?php echo ($filter_startup) ? 'true' : 'false'; ?>;
							var adamlabsgallery_meta_handles = {};
							var adamlabsgallery_filter_handles = {};
							var adamlabsgallery_filter_handles_selected = {};
							var adamlabsgallery_custom_filter_handles = {};
							<?php
							$f_meta = $adamlabsgallery_meta->get_all_meta(false);

							if(!empty($f_meta) && is_array($f_meta)){
								foreach($f_meta as $fmeta){
									// 2.2.5
									// if($fmeta['type'] == 'multi-select'){
										?>adamlabsgallery_meta_handles['meta-<?php echo $fmeta['handle']; ?>'] = '<?php echo $fmeta['name']; ?>';
										<?php
									// }
								}
							}
							?>
							
							<?php
							$custom_filter = $base->getVar($grid['params'], 'custom-filter', array());
							
							if(!empty($custom_filter) && is_array($custom_filter)){
								foreach($custom_filter as $chandle => $cfilter){
									?>adamlabsgallery_filter_handles_selected['<?php echo $chandle; ?>'] = '<?php echo $cfilter; ?>';
									<?php
								}
							}
							?>
							
							var adamlabsgallery_filter_counter = <?php echo $filter_counter; ?>;
							
							//fill up custom filter dialog with entries
							jQuery('select[name="post_category"] option').each(function(){
								adamlabsgallery_filter_handles[jQuery(this).val()] = jQuery(this).text();
							});
							
							//adamlabsgallery_filter_handles['option_disabled_999'] = adamlabsgallery_lang.custom_filter;
							
						</script>
						<div style="clear: both;"></div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Sorting', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="sort-by-text" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Visible Sort By text on the sort dropdown.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Sort By Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="sort-by-text" value="<?php echo $base->getVar($grid['params'], 'sort-by-text', __('Sort By ', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>" class="firstinput">
						</p>
						<p>
							<label for="sorting-order-by" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select Sorting Definitions (multiple available)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Available Sortings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<?php $order_by = explode(',', $base->getVar($grid['params'], 'sorting-order-by', 'date')); ?>
							<select name="sorting-order-by" multiple="true" size="9" >
								<?php
								if(AdamLabsGallery_Woocommerce::is_woo_exists()){
									$wc_sorts = AdamLabsGallery_Woocommerce::get_arr_sort_by();
									if(!empty($wc_sorts)){
										foreach($wc_sorts as $wc_handle => $wc_name){
											?>
											<option value="<?php echo $wc_handle; ?>"<?php selected(in_array($wc_handle, $order_by), true); ?><?php if(strpos($wc_handle, 'opt_disabled_') !== false) echo ' disabled="disabled"'; ?>><?php echo $wc_name; ?></option>
											<?php
										}
									}
								}
								?>
								<option value="date"<?php selected(in_array('date', $order_by), true); ?>><?php _e('Date', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="title"<?php selected(in_array('title', $order_by), true); ?>><?php _e('Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="excerpt"<?php selected(in_array('excerpt', $order_by), true); ?>><?php _e('Excerpt', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="id"<?php selected(in_array('id', $order_by), true); ?>><?php _e('ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="slug"<?php selected(in_array('slug', $order_by), true); ?>><?php _e('Slug', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="author"<?php selected(in_array('author', $order_by), true); ?>><?php _e('Author', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="last-modified"<?php selected(in_array('last-modified', $order_by), true); ?>><?php _e('Last modified', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="number-of-comments"<?php selected(in_array('number-of-comments', $order_by), true); ?>><?php _e('Number of comments', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="views"<?php selected(in_array('views', $order_by), true); ?>><?php _e('Views', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="likespost"<?php selected(in_array('likespost', $order_by), true); ?>><?php _e('Post Likes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="likes"<?php selected(in_array('likes', $order_by), true); ?>><?php _e('Likes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="dislikes"<?php selected(in_array('dislikes', $order_by), true); ?>><?php _e('Dislikes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="retweets"<?php selected(in_array('retweets', $order_by), true); ?>><?php _e('Retweets', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="favorites"<?php selected(in_array('favorites', $order_by), true); ?>><?php _e('Favorites', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="duration"<?php selected(in_array('duration', $order_by), true); ?>><?php _e('Duration', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="itemCount"<?php selected(in_array('itemCount', $order_by), true); ?>><?php _e('Item Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<?php
								if(!empty($all_metas)){
									?>
									<option value="opt_disabled_99" disabled="disabled"><?php _e('---- Custom Metas ----', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<?php
									foreach($all_metas as $c_meta){
										$type = ($c_meta['m_type'] == 'link') ? 'adamlabsgalleryl-' : 'adamlabsgallery-';
										?>
										<option value="<?php echo $type.$c_meta['handle']; ?>"<?php selected(in_array($type.$c_meta['handle'], $order_by), true); ?>><?php echo $c_meta['name'];
										echo ($c_meta['m_type'] == 'link') ? ' (' .__('Link', ADAMLABS_GALLERY_TEXTDOMAIN).')' : ''; ?></option>
										<?php
									}
								}
								?>
							</select>
						</p>
						<p>
							<label for="sorting-order-by-start" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Sorting at Loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Start Sorting By', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<?php $order_by_start = $base->getVar($grid['params'], 'sorting-order-by-start', 'none'); ?>
							<select name="sorting-order-by-start" >
								<option value="none"<?php selected('none' == $order_by_start, true); ?>><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<?php
								if(AdamLabsGallery_Woocommerce::is_woo_exists()){
									$wc_sorts = AdamLabsGallery_Woocommerce::get_arr_sort_by();
									if(!empty($wc_sorts)){
										foreach($wc_sorts as $wc_handle => $wc_name){
											?>
											<option value="<?php echo $wc_handle; ?>"<?php selected(in_array($wc_handle, $order_by), true); ?><?php if(strpos($wc_handle, 'opt_disabled_') !== false) echo ' disabled="disabled"'; ?>><?php echo $wc_name; ?></option>
											<?php
										}
									}
								}
								?>
								<option value="date"<?php selected('date' == $order_by_start, true); ?>><?php _e('Date', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="title"<?php selected('title' == $order_by_start, true); ?>><?php _e('Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="ID"<?php selected('ID' == $order_by_start, true); ?>><?php _e('ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="name"<?php selected('name' == $order_by_start, true); ?>><?php _e('Slug', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="author"<?php selected('author' == $order_by_start, true); ?>><?php _e('Author', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="modified"<?php selected('modified' == $order_by_start, true); ?>><?php _e('Last modified', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="comment_count"<?php selected('comment_count' == $order_by_start, true); ?>><?php _e('Number of comments', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="rand"<?php selected('rand' == $order_by_start, true); ?>><?php _e('Random', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="menu_order"<?php selected('menu_order' == $order_by_start, true); ?>><?php _e('Menu Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<!--option value="meta_num_"<?php selected('meta_num_' == $order_by_start, true); ?>><?php _e('Meta Numeric', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="meta_"<?php selected('meta_' == $order_by_start, true); ?>><?php _e('Meta String', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option-->
								<option value="views"<?php selected('views' == $order_by_start, true); ?>><?php _e('Views', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="likespost"<?php selected('likespost' == $order_by_start, true); ?>><?php _e('Post Likes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="likes"<?php selected('likes' == $order_by_start, true); ?>><?php _e('Likes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="dislikes"<?php selected('dislikes' == $order_by_start, true); ?>><?php _e('Dislikes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="retweets"<?php selected('retweets' == $order_by_start, true); ?>><?php _e('Retweets', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="favorites"<?php selected('favorites' == $order_by_start, true); ?>><?php _e('Favorites', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="duration"<?php selected('duration' == $order_by_start, true); ?>><?php _e('Duration', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="itemCount"<?php selected('itemCount' == $order_by_start, true); ?>><?php _e('Item Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<?php
								if(!empty($all_metas)){
									?>
									<option value="opt_disabled_99" disabled="disabled"><?php _e('---- Custom Metas ----', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<?php
									foreach($all_metas as $c_meta){
										$type = ($c_meta['m_type'] == 'link') ? 'adamlabsgalleryl-' : 'adamlabsgallery-';
										?>
										<option value="<?php echo $type.$c_meta['handle']; ?>"<?php selected($type.$c_meta['handle'] == $order_by_start, true); ?>><?php echo $c_meta['name'];
										echo ($c_meta['m_type'] == 'link') ? ' (' .__('Link', ADAMLABS_GALLERY_TEXTDOMAIN).')' : ''; ?></option>
										<?php
									}
								}
								?>
							</select>
						</p>
						<p class="adamlabsgallery-sorting-order-meta-wrap" style="display: none;">
							<label for="sorting-order-by-start-meta" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set meta handle here that will be used as start sorting', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Start Sorting By Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="sorting-order-by-start-meta" value="<?php echo $base->getVar($grid['params'], 'sorting-order-by-start-meta', ''); ?>" class="firstinput"> <a class="button-secondary sort-meta-selector" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
						</p>
						<p>
							<label for="sorting-order-type" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Sorting Order at Loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Sorting Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<?php $order_by_type = $base->getVar($grid['params'], 'sorting-order-type', 'ASC'); ?>
							<select name="sorting-order-type" >
								<option value="DESC"<?php selected('DESC' == $order_by_type, true); ?>><?php _e('Descending', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="ASC"<?php selected('ASC' == $order_by_type, true); ?>><?php _e('Ascending', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							</select>
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings search_settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Search Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="search-text" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Placeholder text of input field', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Search Default Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="search-text"  value="<?php echo $base->getVar($grid['params'], 'search-text', __('Search...', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>" class="firstinput">
						</p>
					</div>
				</div>

			</div>
		</div>


	
		<!--
		LIGHTBOX SETTINGS
		-->
		<div id="adamlabsgallery-settings-lightbox-settings" class="adamlabsgallery-settings-container">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Shown Media Orders', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
						<div  style="float:left">
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default order of Shown Content Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Set Source Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
						</div>
						<div style="float:left">
							<div id="lbo-list" class="adamlabsgallery-media-source-order-wrap">
								<?php
								if(!empty($lb_source_order)){
									foreach($lb_source_order as $lb_handle){
										if(!isset($lb_source_list[$lb_handle])) continue;
										?>
										<div id="lbo-<?php echo $lb_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $lb_source_list[$lb_handle]['type']; ?>"></i>
											<span style="float:left"><?php echo $lb_source_list[$lb_handle]['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val adamlabsgallery-lb-source-list" type="checkbox" name="lb-source-order[]" checked="checked" value="<?php echo $lb_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
										unset($lb_source_list[$lb_handle]);
									}
								}

								if(!empty($lb_source_list)){
									foreach($lb_source_list as $lb_handle => $lb_set){
										?>
										<div id="lbo-<?php echo $lb_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $lb_set['type']; ?>"></i>
											<span style="float:left"><?php echo $lb_set['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val adamlabsgallery-lb-source-list" type="checkbox" name="lb-source-order[]" value="<?php echo $lb_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
									}
								}
								?>
							</div>

							<p>
							<?php _e('First Ordered Poster Source will be loaded as default. If source not exist, next available Poster source in order will be taken', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>

			<div class="divider1"></div>
			
			<?php
			$use_lightbox = get_option('adamlabsgallery_use_lightbox', 'false');
			if($use_lightbox == 'jackbox' && !AdamLabsGallery_Jackbox::jb_exists()){
				$use_lightbox = 'false';
				update_option('adamlabsgallery_use_lightbox', 'false');
			}
			if($use_lightbox == 'sg' && !AdamLabsGallery_Social_Gallery::sg_exists()){
				$use_lightbox = 'false';
				update_option('adamlabsgallery_use_lightbox', 'false');
			}
			?>
			<div class="adamlabsgallery-hide-if-social-gallery-is-enabled" <?php echo ($use_lightbox == 'sg') ? ' style="display: none;"' : ''; ?>>
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Lightbox Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<?php
							$lighbox_mode = $base->getVar($grid['params'], 'lightbox-mode', 'single');
							?>
							<label for="lightbox-mode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose the Lightbox Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Gallery Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<select name="lightbox-mode" >
								<option value="single"<?php selected($lighbox_mode, 'single'); ?>><?php _e('Single Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="all"<?php selected($lighbox_mode, 'all'); ?>><?php _e('All Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="filterall"<?php selected($lighbox_mode, 'filterall'); ?>><?php _e('Filter based all Pages', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="filterpage"<?php selected($lighbox_mode, 'filterpage'); ?>><?php _e('Filter based current Page', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="content"<?php selected($lighbox_mode, 'content'); ?>><?php _e('Content based', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="content-gallery"<?php selected($lighbox_mode, 'content-gallery'); ?>><?php _e('Content Gallery based', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<?php
									if(AdamLabsGallery_Woocommerce::is_woo_exists()){
									?>
									<option value="woocommerce-gallery"<?php selected($lighbox_mode, 'woocommerce-gallery'); ?>><?php _e('WooCommerce Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<?php
									}
								?>
							</select>
						</p>
						<p class="lightbox-mode-addition-wrapper"<?php echo ($lighbox_mode == 'content' || $lighbox_mode == 'content-gallery' || $lighbox_mode == 'woocommerce-gallery') ? '' : ' style="display: none;"'; ?>>
							<label for="lightbox-exclude-media"><?php _e('Exclude Original Media', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="lightbox-exclude-media" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-exclude-media', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Exclude original media from Source Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="lightbox-exclude-media" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-exclude-media', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Include original media from Source Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label><?php _e('Group Name', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<?php $lightbox_deep_link = $base->getVar($grid['params'], 'lightbox-deep-link', 'group'); ?>
							<input class="firstinput" type="text" name="lightbox-deep-link" value="<?php echo $lightbox_deep_link; ?>" />
						</p>
						
					</div>
					
				</div>
				<div class="divider1"></div>

				<div class="adamlabsgallery-hide-if-jackbox-is-enabled" <?php echo ($use_lightbox == 'jackbox') ? ' style="display: none;"' : ''; ?>>
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Title / Spacings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>

						<div class="adamlabsgallery-cs-tbc">
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show Item Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-title" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-title', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Display Item Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="lightbox-title" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-title', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not display Item Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
							<p>
								<?php
								$lbox_padding = $base->getVar($grid['params'], 'lbox-padding', '0');
								if(!is_array($lbox_padding)) $lbox_padding = array('0', '0', '0', '0');
								?>
								<label for="lbox-padding"><?php _e('Item Margin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Top of the LightBox', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Top</span>  <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-padding[]" value="<?php echo @$lbox_padding[0]; ?>" />
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Right of the LightBox', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Right</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-padding[]" value="<?php echo @$lbox_padding[1]; ?>" />
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Bottom of the LightBox', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Bottom</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-padding[]" value="<?php echo @$lbox_padding[2]; ?>" />
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Left of the LightBox', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Left</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-padding[]" value="<?php echo @$lbox_padding[3]; ?>" />
							</p>
						</div>
					</div>

					<div class="divider1"></div>

					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Effects', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>

						<div class="adamlabsgallery-cs-tbc">
							<p>
								<label for="" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?>,<?php _e('Animation Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Open / Close Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<select name="lightbox-effect-open-close" style="width: 120px; margin-right: 20px">
									<option value="false"<?php selected($base->getVar($grid['params'], 'lightbox-effect-open-close', 'fade'), 'false'); ?>><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="fade"<?php selected($base->getVar($grid['params'], 'lightbox-effect-open-close', 'fade'), 'fade'); ?>><?php _e('Fade', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="zoom-in-out"<?php selected($base->getVar($grid['params'], 'lightbox-effect-open-close', 'fade'), 'zoom-in-out'); ?>><?php _e('Zoom In Out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
								
								<?php 
									$lightbox_effect_open_close_speed = $base->getVar($grid['params'], 'lightbox-effect-open-close-speed', '500');
									if(!is_numeric($lightbox_effect_open_close_speed)) $lightbox_effect_open_close_speed = '500';
								?>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Animation Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Speed</span> <input class="input-settings-small" type="text" name="lightbox-effect-open-close-speed" value="<?php echo $lightbox_effect_open_close_speed; ?>" /> ms
							</p>

							<p>
								<label for="" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Transition Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?>,<?php _e('Transition Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Next / Prev Animation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<select name="lightbox-effect-next-prev" style="width: 120px; margin-right: 20px">
									<option value="none"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'false'); ?>><?php _e('None', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="fade"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'fade'); ?>><?php _e('Fade', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="slide"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'slide'); ?>><?php _e('Slide', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="circular"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'circular'); ?>><?php _e('Circular', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="tube"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'tube'); ?>><?php _e('Tube', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="zoom-in-out"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'zoom-in-out'); ?>><?php _e('Zoom In Out', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="rotate"<?php selected($base->getVar($grid['params'], 'lightbox-effect-next-prev', 'fade'), 'rotate'); ?>><?php _e('Rotate', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
								<?php 
									$lightbox_effect_next_prev_speed = $base->getVar($grid['params'], 'lightbox-effect-next-prev-speed', '500');
									if(!is_numeric($lightbox_effect_next_prev_speed)) $lightbox_effect_next_prev_speed = '500';
								?>
								<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Transition Speed', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Speed</span> <input class="input-settings-small" type="text" name="lightbox-effect-next-prev-speed" value="<?php echo $lightbox_effect_next_prev_speed; ?>" /> ms

							</p>
						</div>
					</div>

					<div class="divider1"></div>	

					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('AutoPlay', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>

						<div class="adamlabsgallery-cs-tbc">
							<p>
								<label><?php _e('AutoPlay Mode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-autoplay" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-autoplay', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('AutoPlay Elements in Lightbox.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="lightbox-autoplay" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-autoplay', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Dont AutoPlay Elements in LightBox.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
							<p>
								<span style="display:inline-block; width:170px"><?php _e('AutoPlay Speed:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="text" name="lbox-playspeed" style="width:50px; margin-right:15px;" value="<?php echo $base->getVar($grid['params'], 'lbox-playspeed', '3000'); ?>">								
							</p>

						</div>
					</div>
					
					<div class="divider1"></div>
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Slideshow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>

						<div class="adamlabsgallery-cs-tbc">
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show Navigation Arrows.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Navigation Arrows', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-arrows" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-arrows', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-arrows" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-arrows', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label><?php _e('Loop Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-loop" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-loop', 'on'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Loop items after last is shown.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="lightbox-loop" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-loop', 'on'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not loop items.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
							<p>
								<label><?php _e('Item Numbers', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-numbers" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-numbers', 'on'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show numbers such as 1-8, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="lightbox-numbers" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-numbers', 'on'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not display numbers', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
							<p>
								<label><?php _e('Mouse Wheel', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-mousewheel" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-mousewheel', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Enable mouse wheel to change items when lightbox is open', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<input type="radio" name="lightbox-mousewheel" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-mousewheel', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not use mouse wheel', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</p>
						</div>
					</div>
					
					<!-- begin buttons -->
					<div class="divider1"></div>
					<div class="adamlabsgallery-creative-settings">
						<div class="adamlabsgallery-cs-tbc-left">
							<h3 class="box-closed"><span><?php _e('Toolbar Buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
						</div>
						
						<!--
						<div class="adamlabsgallery-cs-tbc">
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-slideshow', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Play/Pause', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-slideshow" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-slideshow', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-slideshow" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-slideshow', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-fullscreen', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Fullscreen', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-fullscreen" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-fullscreen', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-fullscreen" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-fullscreen', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-thumbs', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Thumbnails', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-thumbs" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-thumbs', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-thumbs" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-thumbs', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-share', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Social Share', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-share" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-share', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-share" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-share', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-download', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Download Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-download" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-download', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-download" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-download', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-zoom', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Zoom Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-zoom" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-zoom', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-zoom" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-zoom', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-close', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Close Lightbox', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-close" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-close', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-close" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-close', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-left-arrow', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Left Arrow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-left-arrow" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-left-arrow', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-left-arrow" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-left-arrow', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('lightbox-right-arrow', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Right Arrow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="lightbox-right-arrow" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-right-arrow', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="lightbox-right-arrow" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-right-arrow', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
						</div>
						-->
						
						<div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
						<div style="float:left">
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose which buttons to display and set their order', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Set Button Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
						</div>
						<div style="float:left">
							<div id="lbo-btn-list" class="adamlabsgallery-media-source-order-wrap" style="width: 332px; margin-bottom: 15px">
								<?php
								if(!empty($lb_button_order)){
									foreach($lb_button_order as $lb_handle){
										if(!isset($lb_button_list[$lb_handle])) continue;
										?>
										<div id="lbo-<?php echo $lb_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $lb_button_list[$lb_handle]['type']; ?>"></i>
											<span style="float:left"><?php echo $lb_button_list[$lb_handle]['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="lb-button-order[]" checked="checked" value="<?php echo $lb_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
										unset($lb_button_list[$lb_handle]);
									}
								}

								if(!empty($lb_button_list)){
									foreach($lb_button_list as $lb_handle => $lb_set){
										?>
										<div id="lbo-button-<?php echo $lb_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $lb_set['type']; ?>"></i>
											<span style="float:left"><?php echo $lb_set['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="lb-button-order[]" value="<?php echo $lb_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
									}
								}
								?>
							</div>
							
							<!--
							<p>
							<?php _e('First Ordered Poster Source will be loaded as default. If source not exist, next available Poster source in order will be taken', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							-->
							<div style="clear:both"></div>
						</div>
						</div>
					</div>
					<!-- end buttons -->
					
					<div id="adamlabsgallery-post-content-options">
					
						<div class="divider1"></div>
						
						<!-- 2.1.6 -->
						<div class="adamlabsgallery-creative-settings">
							<div class="adamlabsgallery-cs-tbc-left">
								<h3 class="box-closed"><span><?php _e('Post Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
							</div>

							<div class="adamlabsgallery-cs-tbc">
								
								<p>
									<label for="lightbox-post-content-min-width" class="adamlabsgallery-tooltip-wrap" title="<?php _e('percentage or pixel based', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Min Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input class="input-settings-small firstinput" type="text" name="lightbox-post-content-min-width" value="<?php echo $base->getVar($grid['params'], 'lightbox-post-content-min-width', '75'); ?>" />
									<input type="radio" name="lightbox-post-content-min-perc" value="on" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-min-perc', 'on'), 'on'); ?>> <?php _e('%', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="lightbox-post-content-min-perc" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-min-perc', 'on'), 'off'); ?>> <?php _e('px', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								
								<p>
									<label for="lightbox-post-content-max-width" class="adamlabsgallery-tooltip-wrap" title="<?php _e('percentage or pixel based', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Max Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input class="input-settings-small firstinput" type="text" name="lightbox-post-content-max-width" value="<?php echo $base->getVar($grid['params'], 'lightbox-post-content-max-width', '75'); ?>" />
									<input type="radio" name="lightbox-post-content-max-perc" value="on" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-max-perc', 'on'), 'on'); ?>> <?php _e('%', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="lightbox-post-content-max-perc" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-max-perc', 'on'), 'off'); ?>> <?php _e('px', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								
								<p>
									<label for="lightbox-post-content-overflow" class="adamlabsgallery-tooltip-wrap" title="<?php _e('allow content scrolling', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Overflow', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" name="lightbox-post-content-overflow" class="firstinput" value="on" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-overflow', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="lightbox-post-content-overflow" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-overflow', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								
								<p>
									<?php
									$lbox_post_img_padding = $base->getVar($grid['params'], 'lbox-content_padding', '0');
									if(!is_array($lbox_post_img_padding)) $lbox_post_img_padding = array('0', '0', '0', '0');
									?>
									<label><?php _e('Post Content Padding', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Top (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Top</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-content_padding[]" value="<?php echo @$lbox_post_img_padding[0]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Right (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Right</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-content_padding[]" value="<?php echo @$lbox_post_img_padding[1]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Bottom (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Bottom</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-content_padding[]" value="<?php echo @$lbox_post_img_padding[2]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Padding Left (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Left</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lbox-content_padding[]" value="<?php echo @$lbox_post_img_padding[3]; ?>" />
								</p>
							
								<p>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Show spinner preloader on item while content loads', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show Preloader', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" name="lightbox-post-spinner" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-post-spinner', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="lightbox-post-spinner" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-spinner', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								<p>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Include Featured Image from Post', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Include Featured Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" class="lightbox-post-content-img firstinput" name="lightbox-post-content-img" value="on" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-img', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" class="lightbox-post-content-img" name="lightbox-post-content-img" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-img', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								<p class="featured-img-hideable">
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Featured Image position in relation to the content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Featured Image Position', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select id="lightbox-post-content-img-position" name="lightbox-post-content-img-position">
										<option value="top"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-img-position', 'top'), 'top'); ?>><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="right"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-img-position', 'top'), 'right'); ?>><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="bottom"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-img-position', 'top'), 'bottom'); ?>><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="left"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-img-position', 'top'), 'left'); ?>><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
								<?php
									$lbMaxWidthDisplay = $base->getVar($grid['params'], 'lightbox-post-content-img-position', 'top');
									$lbMaxWidthDisplay = $lbMaxWidthDisplay === 'left' || $lbMaxWidthDisplay === 'right' ? 'block' : 'none';
								?>
								<div class="featured-img-hideable">
									<p id="lightbox-post-content-img-width" style="display: <?php echo $lbMaxWidthDisplay; ?>">
										<label for="lightbox-post-content-img-width" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Percentage based on Lightbox Default Width above', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Featured Image Max Width', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
										<input class="input-settings-small firstinput" type="text" name="lightbox-post-content-img-width" value="<?php echo $base->getVar($grid['params'], 'lightbox-post-content-img-width', '50'); ?>" /> %
									</p>
								</div>
								<p class="featured-img-hideable">
									<?php
									$lbox_post_img_margin = $base->getVar($grid['params'], 'lightbox-post-content-img-margin', '0');
									if(!is_array($lbox_post_img_margin)) $lbox_post_img_margin = array('0', '0', '0', '0');
									?>
									<label for="lightbox-post-content-img-margin"><?php _e('Featured Image Margin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Margin Top of the Featured Image (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Top</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lightbox-post-content-img-margin[]" value="<?php echo @$lbox_post_img_margin[0]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Margin Right of the Featured Image (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Right</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lightbox-post-content-img-margin[]" value="<?php echo @$lbox_post_img_margin[1]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Margin Bottom of the Featured Image (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Bottom</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lightbox-post-content-img-margin[]" value="<?php echo @$lbox_post_img_margin[2]; ?>" />
									<span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Margin Left of the Featured Image (px)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">Left</span> <input class="input-settings-small firstinput" type="text" style="margin-right:10px" name="lightbox-post-content-img-margin[]" value="<?php echo @$lbox_post_img_margin[3]; ?>" />
								</p>
								<p>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Include Post Title Before Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Prepend Post Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" name="lightbox-post-content-title" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-title', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="lightbox-post-content-title" value="off" <?php checked($base->getVar($grid['params'], 'lightbox-post-content-title', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								<p>
									<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('The tag for the Post Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Post Title HTML Tag', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select name="lightbox-post-content-title-tag">
										<option value="h1"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-title-tag', 'h2'), 'h1'); ?>><?php _e('h1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="h2"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-title-tag', 'h2'), 'h2'); ?>><?php _e('h2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="h3"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-title-tag', 'h2'), 'h3'); ?>><?php _e('h3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="h4"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-title-tag', 'h2'), 'h4'); ?>><?php _e('h4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="p"<?php selected($base->getVar($grid['params'], 'lightbox-post-content-title-tag', 'h2'), 'p'); ?>><?php _e('p', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="adamlabsgallery-hide-if-lightbox-is-enabled" <?php echo ($use_lightbox == 'jackbox' || $use_lightbox == 'sg') ? '' : ' style="display: none;"'; ?>>
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('External LightBox', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<?php if($use_lightbox == 'jackbox'){ ?>
								<?php _e('JackBox is enabled in the Portfolio Gallery Global Settings. JackBox specific settings can be changed ', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <a href="<?php echo get_admin_url() . 'options-general.php?page=jackbox_admin'; ?>" target="_blank"><?php _e('here', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<?php } ?>
							<?php if($use_lightbox == 'sg'){ ?>
								<?php _e('Social Gallery is enabled in the Portfolio Gallery Global Settings. Social Gallery specific settings can be changed ', ADAMLABS_GALLERY_TEXTDOMAIN); ?> <a href="<?php echo get_admin_url() . 'admin.php?page=sgp-plugin-settings'; ?>" target="_blank"><?php _e('here', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<?php } ?>
							
						</p>
					</div>
				</div>
			</div>
		</div>

		<!--
		AJAX SETTINGS
		-->
		<div id="adamlabsgallery-settings-ajax-settings" class="adamlabsgallery-settings-container">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Shown Ajax Orders', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
						<div  style="float:left">
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default order of Shown Content at ajax loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Set Source Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
						</div>
						<div style="float:left">
							<div id="ajo-list" class="adamlabsgallery-media-source-order-wrap">
								<?php
								if(!empty($aj_source_order)){
									foreach($aj_source_order as $aj_handle){
										if(!isset($aj_source_list[$aj_handle])) continue;
										?>
										<div id="ajo-<?php echo $aj_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $aj_source_list[$aj_handle]['type']; ?>"></i>
											<span style="float:left"><?php echo $aj_source_list[$aj_handle]['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="aj-source-order[]" checked="checked" value="<?php echo $aj_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
										unset($aj_source_list[$aj_handle]);
									}
								}

								if(!empty($aj_source_list)){
									foreach($aj_source_list as $aj_handle => $aj_set){
										?>
										<div id="ajo-<?php echo $aj_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
											<i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $aj_set['type']; ?>"></i>
											<span style="float:left"><?php echo $aj_set['name']; ?></span>
											<input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="aj-source-order[]" value="<?php echo $aj_handle; ?>" />
											<div style="clear:both"></div>
										</div>
										<?php
									}
								}
								?>
							</div>

							<p>
							<?php _e('First Ordered Source will be loaded as default. If source not exist, next available source in order will be taken', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Ajax Container', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define ID of the container (without #)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Insert a valid CSS ID here', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Container ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="ajax-container-id" value="<?php echo $base->getVar($grid['params'], 'ajax-container-id', 'adamlabsgallery-ajax-container-'); ?>" class="firstinput">
						</p>

						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the position of the ajax content container', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Container Position', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="ajax-container-position" value="top" class="firstinput" <?php checked($base->getVar($grid['params'], 'ajax-container-position', 'top'), 'top'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Visible above the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="ajax-container-position" value="bottom" <?php checked($base->getVar($grid['params'], 'ajax-container-position', 'top'), 'bottom'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Visible under the Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="ajax-container-position" value="shortcode" <?php checked($base->getVar($grid['params'], 'ajax-container-position', 'top'), 'shortcode'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Insert somewhere as ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('As ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p id="adamlabsgallery-ajax-shortcode-wrapper">
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Use this ShortCode somewhere on the page to insert the ajax content container', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Container ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" readonly="readonly" value="" name="ajax-container-shortcode" style="width: 400px;">
						</p>

						<!--p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define if the content should be sliding', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Content Sliding', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="ajax-content-sliding" value="on" class="firstinput adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-content-sliding', 'on'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="ajax-content-sliding" value="off" class="adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-content-sliding', 'on'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p-->
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define if browser should scroll to content after it is loaded via ajax', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Scroll on load', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="ajax-scroll-onload" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'ajax-scroll-onload', 'on'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Scroll to content.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="ajax-scroll-onload" value="off" <?php checked($base->getVar($grid['params'], 'ajax-scroll-onload', 'on'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not scroll to content.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define offset of scrolling in px (-500 - 500)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Scroll Offset', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="ajax-scrollto-offset" value="<?php echo $base->getVar($grid['params'], 'ajax-scrollto-offset', '0'); ?>" class="firstinput">
						</p>

					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Ajax Navigation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the content container should have a close button', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show Close Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="ajax-close-button" value="on" class="firstinput adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-close-button', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="ajax-close-button" value="off" class="adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-close-button', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
						<div class="adamlabsgallery-close-button-settings-wrap">
							<p class="adamlabsgallery-button-text-wrap">
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the button text here', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Close Button Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="text" name="ajax-button-text" class="firstinput" value="<?php echo $base->getVar($grid['params'], 'ajax-button-text', __('Close', ADAMLABS_GALLERY_TEXTDOMAIN)); ?>">
							</p>
						</div>

						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the content container should have navigation buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show Navigation Button', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="ajax-nav-button" value="on" class="firstinput adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-nav-button', 'off'), 'on'); ?>> <?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<input type="radio" name="ajax-nav-button" value="off" <?php checked($base->getVar($grid['params'], 'ajax-nav-button', 'off'), 'off'); ?>> <?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>

						<div class="adamlabsgallery-close-nav-button-settings-wrap">
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the Skin of the buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Button Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<select name="ajax-button-skin" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the Skin of the buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
									<option value="light"<?php selected($base->getVar($grid['params'], 'ajax-button-skin', 'light'), 'light'); ?>><?php _e('Light', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="dark"<?php selected($base->getVar($grid['params'], 'ajax-button-skin', 'light'), 'dark'); ?>><?php _e('Dark', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Switch between button or text', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Button Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="ajax-button-type" value="type1" class="firstinput adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-button-type', 'type1'), 'type1'); ?>> <?php _e('Type 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="ajax-button-type" value="type2" <?php checked($base->getVar($grid['params'], 'ajax-button-type', 'type1'), 'type2'); ?>> <?php _e('Type 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define if the button should be visible inside of the ajax container or outside of it', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Button Container Pos.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="ajax-button-inner" value="true" class="firstinput adamlabsgallery-tooltip-wrap" <?php checked($base->getVar($grid['params'], 'ajax-button-inner', 'false'), 'true'); ?>> <?php _e('Inner', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="ajax-button-inner" value="false" <?php checked($base->getVar($grid['params'], 'ajax-button-inner', 'false'), 'false'); ?>> <?php _e('Outer', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the horizontal positioning of the buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Horizontal Pos.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="ajax-button-h-pos" value="l" class="firstinput" <?php checked($base->getVar($grid['params'], 'ajax-button-h-pos', 'r'), 'l'); ?>> <?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="ajax-button-h-pos" value="c" <?php checked($base->getVar($grid['params'], 'ajax-button-h-pos', 'r'), 'c'); ?>> <?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="ajax-button-h-pos" value="r" <?php checked($base->getVar($grid['params'], 'ajax-button-h-pos', 'r'), 'r'); ?>> <?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
							<p>
								<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define the vertical positioning of the buttons', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Vertical Pos.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="radio" name="ajax-button-v-pos" value="t" class="firstinput" <?php checked($base->getVar($grid['params'], 'ajax-button-v-pos', 't'), 't'); ?>> <?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								<input type="radio" name="ajax-button-v-pos" value="b" <?php checked($base->getVar($grid['params'], 'ajax-button-v-pos', 't'), 'b'); ?>> <?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Prepend Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<div style="margin: 15px 0;">
							<div style="padding-left: 0px;">
								<?php
								$settings = array('textarea_name' => 'ajax-container-pre');
								wp_editor($base->getVar($grid['params'], 'ajax-container-pre', ''), 'ajax-container-pre', $settings);
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Append Content', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<div style="margin: 15px 0;">
							<div style="padding-left: 0px;">
								<?php
								$settings = array('textarea_name' => 'ajax-container-post');
								wp_editor($base->getVar($grid['params'], 'ajax-container-post', ''), 'ajax-container-post', $settings);
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Ajax Container Custom CSS', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<div style="margin: 15px 0;" class="adamlabsgallery-codemirror-border">
							<div style="padding-left: 0px;">
								<textarea name="ajax-container-css" id="adamlabsgallery-ajax-custom-css"><?php echo stripslashes($base->getVar($grid['params'], 'ajax-container-css', '')); ?></textarea>
							</div>
							<p style="font-size: 12px; color: #999;"><?php _e('Please only add styles directly here without any class/id declaration.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
						</div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Advanced', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define a JavaScript callback here. This will be called every time when Content is loaded ! You can also define arguments by using callbackname(arg1, arg2, ...., adamlabsgallery99)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('JavaScript Callback', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="ajax-callback" value="<?php echo stripslashes($base->getVar($grid['params'], 'ajax-callback', '')); ?>" class="firstinput">
						</p>
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Append Portfolio Gallery argument to the callback to the end', ADAMLABS_GALLERY_TEXTDOMAIN); ?>, <?php _e('Append return argument from Portfolio Gallery with object containing posttype, postsource and ajaxcontainterid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Append Argument', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="checkbox" name="ajax-callback-arg" value="on" <?php checked($base->getVar($grid['params'], 'ajax-callback-arg', 'on'), 'on'); ?>>
						</p>
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define a CSS URL to load when First time Ajax Container has beed created. ', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Extend CSS URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="ajax-css-url" value="<?php echo $base->getVar($grid['params'], 'ajax-css-url', ''); ?>" class="firstinput">
						</p>
						<p>
							<label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Define a JavaScript File URL to load which is run 1 time at first Ajax Content loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Extend JavaScript URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="ajax-js-url" value="<?php echo $base->getVar($grid['params'], 'ajax-js-url', ''); ?>" class="firstinput">
						</p>
						<div style="clear:both"></div>
					</div>
				</div>


			</div>
		</div>


		<!--
		SPINNER SETTINGS
		-->
		<div id="adamlabsgallery-settings-spinner-settings" class="adamlabsgallery-settings-container">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Spinner Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<div style="width:100%;height:15px"></div>
							<div id="use_spinner_row">
								<p>
									<label for="cart-arrows" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose Loading Spinner', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Choose Spinner', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<?php
									$use_spinner = $base->getVar($grid['params'], 'use-spinner', '0');
									?>
									<select id="use_spinner" name="use-spinner">
										<option value="-1"<?php selected($use_spinner, '-1'); ?>><?php _e('off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="0"<?php selected($use_spinner, '0'); ?>>0</option>
										<option value="1"<?php selected($use_spinner, '1'); ?>>1</option>
										<option value="2"<?php selected($use_spinner, '2'); ?>>2</option>
										<option value="3"<?php selected($use_spinner, '3'); ?>>3</option>
										<option value="4"<?php selected($use_spinner, '4'); ?>>4</option>
										<option value="5"<?php selected($use_spinner, '5'); ?>>5</option>
									</select>
								</p>
							</div>
							<div id="spinner_color_row">
								<p>
									<label for="cart-arrows" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Sorting at Loading', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Choose Spinner Color', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="text" class="inputColorPicker" id="spinner_color" name="spinner-color" value="<?php echo $base->getVar($grid['params'], 'spinner-color', '#FFFFFF'); ?>" />
								</p>
							</div>
						<div style="width:100%;height:15px"></div>
					</div>
				</div>
			</div>
		</div>

		<!--
		API / CUSTOM JAVASCRIPT SETTINGS
		-->
		<div id="adamlabsgallery-settings-api-settings" class="adamlabsgallery-settings-container">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Custom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc adamlabsgallery-codemirror-border">
						<div style="margin: 15px 0;">
							<label for="main-background-color"><?php _e('Custom JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<div style="padding-left: 170px;">
								<!--jQuery(document).ready(function() {<br>-->
								<textarea name="custom-javascript" id="adamlabsgallery-api-custom-javascript"><?php echo stripslashes($base->getVar($grid['params'], 'custom-javascript', '')); ?></textarea>
								<!--<br>});-->
							</div>
						</div>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<?php
							if($grid !== false){
								?>
								<label for="api-methods"><?php _e('API Methods', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<div style="padding-left: 170px;">
									<div><label><?php _e('Redraw Grid:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input class="adamlabsgallery-api-inputs" type="text" name="do-not-save" value="adamlabsgalleryapi<?php echo $grid['id']; ?>.adamlabsgalleryredraw();" readonly="true" /></div>
									<div style="clear: both;"></div>
									<div><label><?php _e('Quick Redraw Grid:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input class="adamlabsgallery-api-inputs" type="text" name="do-not-save" value="adamlabsgalleryapi<?php echo $grid['id']; ?>.adamlabsgalleryquickdraw();" readonly="true" /></div>
								</div>
								<?php
							}else{
								?>
								<p>
								<?php _e('API Methods will be available after this Grid is saved for the first time.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								<?php
							}
							?>
						</p>
					</div>
				</div>

				<div class="divider1"></div>

				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3><span><?php _e('Code Examples', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label><?php _e('Visual Composer Tab fix:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<div style="margin-left: 170px;">
<pre><code>jQuery('body').on('click', '.wpb_tabs_nav a', function() {
	setTimeout(function(){
		jQuery(window).trigger('resize');
	}, 500); //change 500 to your needs
});</code></pre>
							</div>
						</p>
						<p>
							<label><?php _e('Lightbox Custom Options:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<div style="margin-left: 170px;">
<pre><code>
// http://fancyapps.com/fancybox/3/docs/
lightboxOptions.hideScrollbar = true;
lightboxOptions.hash = false;
</code></pre>
							</div>
						</p>
					</div>
				</div>

			</div>
		</div>
		
		<!--
		COOKIE SETTINGS
		-->
		
		<div id="adamlabsgallery-settings-cookie-settings" class="adamlabsgallery-settings-container">
			<div class="">
				<div class="adamlabsgallery-creative-settings">
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Timing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="filter-arrows" class="adamlabsgallery-tooltip-wrap" title="<?php _e('The amount of time before the cookies expire (in minutes).', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" ><?php _e('Save for', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="cookie-save-time" class="input-settings-small firstinput" value="<?php echo intval($base->getVar($grid['params'], 'cookie-save-time', '30')); ?>"> <?php _e('Minutes', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
						<div style="width:100%;height:15px"></div>
					</div>
				</div>
				
				<div class="adamlabsgallery-creative-settings">
					<div class="divider1"></div>
					
					<div class="adamlabsgallery-cs-tbc-left">
						<h3 class="box-closed"><span><?php _e('Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="adamlabsgallery-cs-tbc">
						<p>
							<label for="filter-arrows" ><?php _e('Search', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="cookie-save-search" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'cookie-save-search', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Remember user\'s last search.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="cookie-save-search" value="off" <?php checked($base->getVar($grid['params'], 'cookie-save-search', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not apply cookie for search.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label for="filter-arrows" ><?php _e('Filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="cookie-save-filter" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'cookie-save-filter', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Remember Grid\'s last filter state.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="cookie-save-filter" value="off" <?php checked($base->getVar($grid['params'], 'cookie-save-filter', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not apply cookie for filter.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p>
							<label for="filter-arrows" ><?php _e('Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="radio" name="cookie-save-pagination" value="on" class="firstinput" <?php checked($base->getVar($grid['params'], 'cookie-save-pagination', 'off'), 'on'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Remember Grid\'s last pagination state.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('On', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							<input type="radio" name="cookie-save-pagination" value="off" <?php checked($base->getVar($grid['params'], 'cookie-save-pagination', 'off'), 'off'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Do not apply cookie for pagination.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
						</p>
						<p class="adamlabsgallery-note">
						<?php _e('<b>Special Note:</b> <a href="//www.cookielaw.org/the-cookie-law/" target="_blank">EU Law</a> requires that a notification be shown to the user when cookies are being used.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</p>
						<div style="width:100%;height:15px"></div>
					</div>
				</div>
			</div>
		</div>

	</form>
	<?php
	AdamLabsGallery_Dialogs::pages_select_dialog();
	AdamLabsGallery_Dialogs::navigation_skin_css_edit_dialog();
	AdamLabsGallery_Dialogs::filter_select_dialog();
	?>