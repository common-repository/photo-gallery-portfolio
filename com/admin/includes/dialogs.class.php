<?php

if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Dialogs {
	
    
	public static function pages_select_dialog(){
		$pages = apply_filters('adamlabsgallery_pages_select_dialog', get_pages(array('sort_column' => 'post_name')));
		?>
		<div id="pages-select-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Choose Pages', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none;">
			<?php echo _e('Choose Pages', ADAMLABS_GALLERY_TEXTDOMAIN); ?>:
			<table>
				<tr>
					<td colspan="2"><input type="checkbox" id="check-uncheck-pages"><?php echo _e('Select All', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
				</tr>
				<?php
				foreach($pages as $page){
					?>
					<tr><td><input type="checkbox" value="<?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?>" name="selected-pages"></td><td><?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?></td></tr>
					<?php
				}
				?>
			</table>
			<?php
			do_action('adamlabsgallery_pages_select_dialog_post', $pages);
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Insert global CSS Dialog
	 */
	public static function global_css_edit_dialog(){
		$global_css = apply_filters('adamlabsgallery_global_css_edit_dialog', AdamLabsGallery_Global_Css::get_global_css_styles());
		?>
		<div id="global-css-edit-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Global Custom CSS', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none;">
			<textarea id="adamlabsgallery-global-css-editor"><?php echo $global_css; ?></textarea>
			<?php
			do_action('adamlabsgallery_global_css_edit_dialog_post', $global_css);
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Insert navigation skin CSS Dialog
	 */
	public static function navigation_skin_css_edit_dialog(){
		?>
		<div id="navigation-skin-css-edit-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Navigation Skin CSS', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none;">
			<textarea id="adamlabsgallery-navigation-skin-css-editor"></textarea>
			<?php
			do_action('adamlabsgallery_navigation_skin_css_edit_dialog_post');
			?>
		</div>
		<?php
	}
    
	
	/**
	 * Fontello Icons
	 */
	public static function fontello_icons_dialog(){
		?>
		<div id="adamlabsgallery-fontello-icons-dialog-wrap" style="width:602px; height:405px; margin-left:15px;overflow:visible;display:none">
			<div class="font_headline">Fontello Icons</div>
			<div id="dialog-adamlabsgallery-fakeicon-in"></div>
			<div id="dialog-adamlabsgallery-fakeicon-out"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-soundcloud"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-music"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-color-adjust"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-mail"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-mail-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-heart"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-heart-empty"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-star"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-star-empty"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-user"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-male"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-female"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-video"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-videocam"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-picture-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-camera"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-camera-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-th-large"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-th"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ok"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ok-circled2"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ok-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-cancel"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-plus"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-plus-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-plus-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-minus"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-minus-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-minus-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-minus-squared-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-info-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-info"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-home"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-link"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-unlink"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-link-ext"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-lock"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-lock-open"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-eye"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-eye-off"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tag"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-thumbs-up"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-thumbs-up-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-download"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-upload"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-reply"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-forward"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-export-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-print"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-gamepad"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-trash"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-doc-text"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-doc-inv"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-folder-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-folder-open"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-folder-open-empty"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-rss"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-rss-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-phone"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-menu"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-cog-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-wrench"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-basket-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-calendar"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-calendar-empty"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-lightbulb"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-resize-full-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-move"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-down-dir"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-up-dir"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-dir"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-dir"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-down-open"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-angle-left"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-angle-right"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-angle-double-left"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-angle-double-right"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-big"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-big"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-up-hand"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ccw-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-shuffle-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-play"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-play-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-stop"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-pause"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-fast-fw"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-desktop"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-laptop"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tablet"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-mobile"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-flight"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-font"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-bold"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-italic"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-text-height"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-text-width"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-align-left"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-align-center"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-align-right"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-search"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-indent-left"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-indent-right"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ajust"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tint"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-chart-bar"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-magic"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-sort"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-sort-alt-up"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-sort-alt-down"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-sort-name-up"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-sort-name-down"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-coffee"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-food"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-medkit"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-puzzle"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-apple"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-facebook"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-gplus"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tumblr"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-twitter-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-twitter"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-vimeo-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-youtube"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-youtube-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-picture"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-check"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-back"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-thumbs-up-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-thumbs-down"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-download-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-upload-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-reply-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-forward-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-export"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-folder"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-rss-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-cog"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tools"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-basket"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-login"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-logout"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-resize-full"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-popup"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-arrow-combo"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open-mini"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open-mini"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open-big"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open-big"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ccw"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-cw"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-arrows-ccw"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-level-down"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-level-up"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-shuffle"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-palette"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-list-add"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-back-in-time"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-monitor"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-paper-plane"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-brush"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-droplet"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-clipboard"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-megaphone"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-key"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-github"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-github-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-flickr"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-flickr-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-vimeo"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-vimeo-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-twitter-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-twitter-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-facebook-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-facebook-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-facebook-squared"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-gplus-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-gplus-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-pinterest"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-pinterest-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tumblr-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-tumblr-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-linkedin"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-linkedin-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-dribbble"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-dribbble-circled"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-picasa"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-ok-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-doc"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open-outline"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-left-open-2"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open-outline"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-right-open-2"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-equalizer"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-layers-alt"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-pencil-1"></div>
			<div class="adamlabsgallery-icon-chooser adamlabsgallery-icon-align-justify"></div>
			<?php
				$enable_fontello = get_option('adamlabsgallery_global_enable_fontello', 'backfront');
				$enable_font_awesome = get_option('adamlabsgallery_global_enable_font_awesome', 'false');
				$enable_pe7 = get_option('adamlabsgallery_global_enable_pe7', 'false');
				if($enable_font_awesome!="false") include(ADAMLABS_GALLERY_PLUGIN_PATH."com/admin/views/skin-font-awesome-list.php");
				if($enable_pe7!="false") include(ADAMLABS_GALLERY_PLUGIN_PATH."com/admin/views/skin-pe-icon-7-stroke-list.php");
			
			do_action('adamlabsgallery_fontello_icons_dialog_post');
			?>
		</div>
        <?php
	}
	
	
	/**
	 * Insert custom meta Dialog
	 */
	public static function custom_meta_dialog(){
		?>
		<div id="custom-meta-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Custom Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-custom-meta-name" value="" /></div>

			<p style="font-weight:600;color:#ddd; margin-top:20px;padding-bottom:5px; border-bottom:1px solid #ddd"><?php _e('HANDLES', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><span style="margin-left:-20px;margin-right:2px;"><strong>adamlabsgallery-</strong></span><input type="text" name="adamlabsgallery-custom-meta-handle" value="" /></div>
			<p style="font-weight:600;color:#ddd; margin-top:20px;padding-bottom:5px; border-bottom:1px solid #ddd"><?php _e('SETTINGS', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Default:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-custom-meta-default" value="" /></div>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-custom-meta-type"><option value="text"><?php _e('Text', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option><option value="multi-select"><?php _e('Multi Select', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option><option value="select"><?php _e('Select', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option><option value="image"><?php _e('Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option></select></div>
			<div id="adamlabsgallery-custom-meta-select-wrap" style="display: none;">
				<?php _e('Comma Seperated List of Elements:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
				<textarea name="adamlabsgallery-custom-meta-select" style="width: 100%;height: 70px;"></textarea>
			</div>
			
			<p style="font-weight:600;color:#ddd; margin-top:20px;padding-bottom:5px; border-bottom:1px solid #ddd"><?php _e('SORTING', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Sort Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-custom-meta-sort-type"><option value="alphabetic"><?php _e('Alphabetic', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option><option value="numeric"><?php _e('Numeric', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option></select></div>
			<?php
			do_action('adamlabsgallery_custom_meta_dialog_post');
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Insert link meta Dialog
	 */
	public static function custom_meta_linking_dialog(){
		?>
		<div id="link-meta-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Meta References', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:15px !important;">
			
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-link-meta-name" value="" /></div>
			<p style="font-weight:600;color:#ddd; margin-top:20px;padding-bottom:5px; border-bottom:1px solid #ddd"><?php _e('HANDLES', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Internal:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><span style="margin-left:-25px;margin-right:2px;"><strong>egl-</strong></span><input type="text" name="adamlabsgallery-link-meta-handle" value="" /></div>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Original:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-link-meta-original" value="" /></div>
			<p style="font-weight:600;color:#ddd; margin-top:20px;padding-bottom:5px; border-bottom:1px solid #ddd"><?php _e('SORTING', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Sort Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-link-meta-sort-type"><option value="alphabetic"><?php _e('Alphabetic', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option><option value="numeric"><?php _e('Numeric', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option></select></div>
			
			<?php
			do_action('adamlabsgallery_custom_meta_linking_dialog_post');
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Insert Widget Areas Dialog
	 */
	public static function widget_areas_dialog(){
		?>
		<div id="widget-areas-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('New Widget Area', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><span style="margin-right:2px;"><strong>adamlabsgallery-</strong></span><input type="text" name="adamlabsgallery-widget-area-handle" value="" /></div>
			<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-widget-area-name" style="margin-left:29px;" value="" /></div>
			<?php
			do_action('adamlabsgallery_widget_areas_dialog_post');
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Insert font Dialog
	 */
	public static function fonts_dialog(){
		?>
		<div id="font-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Add Font', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			
			<div class="adamlabs-googlefont-cus-row-l"><label><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><span style="margin-left:-20px;margin-right:2px;"><strong>adamlabs-</strong></span><input type="text" name="adamlabsgallery-font-handle" value="" /></div>
			<div style="margin-top:0px; padding-left:100px; margin-bottom:20px;">
				<i style="font-size:12px;color:#777; line-height:20px;"><?php _e('Unique WordPress handle (Internal use only)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></i>
			</div>
			<div class="adamlabs-googlefont-cus-row-l"><label><?php _e('Parameter:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-font-url" value="" /></div>
			<div style="padding-left:100px;">
				<i style="font-size:12px;color:#777; line-height:20px;"><?php _e('Copy the Google Font Family from <a href="http://www.google.com/fonts" target="_blank">http://www.google.com/fonts</a><br/>i.e.:<strong>Open+Sans:400,600,700</strong>', ADAMLABS_GALLERY_TEXTDOMAIN); ?></i>
			</div>
			<?php
			do_action('adamlabsgallery_fonts_dialog_post');
			?>
		</div>
		
		
		<?php
	}
	
	
	/**
	 * Meta Dialog
	 */
	public static function meta_dialog(){
	
		$m = new AdamLabsGallery_Meta();
		$item_ele = new AdamLabsGallery_Item_Element();
		
		$post_items = $item_ele->getPostElementsArray();
		$metas = $m->get_all_meta();
		?>
		<div id="meta-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<table>
				<tr class="adamlabsgallery-table-title"><td><?php _e('Meta Handle', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td><td><?php _e('Description', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
			<?php
			if(!empty($post_items)){
				foreach($post_items as $phandle => $pitem){
					echo '<tr class="adamlabsgallery-add-meta-to-textarea"><td>%'.$phandle.'%</td><td>'.$pitem['name'].'</td></tr>';
				}
			}
			
			if(!empty($metas)){
				foreach($metas as $meta){
					if($meta['m_type'] == 'link'){
						echo '<tr class="adamlabsgallery-add-meta-to-textarea"><td>%egl-'.$meta['handle'].'%</td><td>'.$meta['name'].'</td></tr>';
					}else{
						echo '<tr class="adamlabsgallery-add-meta-to-textarea"><td>%adamlabsgallery-'.$meta['handle'].'%</td><td>'.$meta['name'].'</td></tr>';
					}
				}
			}
			
			if(AdamLabsGallery_Woocommerce::is_woo_exists()){
				$metas = AdamLabsGallery_Woocommerce::get_meta_array();
				
				foreach($metas as $meta => $name){
					echo '<tr><td>%'.$meta.'%</td><td>'.$name.'</td></tr>';
				}
				
			}
			
			do_action('adamlabsgallery_meta_dialog_post');
			?>
			</table>
		</div>
		<?php
	}
	
	
	/**
	 * Post Meta Dialog
	 */
	public static function post_meta_dialog(){
		?>
		<div id="post-meta-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Post Meta Editor', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<div id="adamlabsgallery-meta-box">
			
			</div>
			<?php
			do_action('adamlabsgallery_post_meta_dialog_post');
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Custom Element Image Dialog
	 * @since    1.0.1
	 */
	public static function custom_element_image_dialog(){
		?>
		<div id="custom-element-image-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Please Choose', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<?php
			_e('Please choose the art you wish to add the Image(s): Single Image or Bulk Images ?', ADAMLABS_GALLERY_TEXTDOMAIN);
			
			do_action('adamlabsgallery_custom_element_image_dialog_post');
			?>
		</div>
		<?php
	}
	
	
	/**
	 * Advanced Rules Dialog for Item Skin Editor
	 */
	public static function edit_advanced_rules_dialog(){
		$base = new AdamLabsGallery_Base();
		$types = $base->get_media_source_order();
		?>
		<div id="advanced-rules-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Advanced Rules', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<form id="ar-form-wrap">
				<div class="ad-rules-main"><?php _e('Show/Hide if rules are true:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
					<input class="ar-show-field" type="radio" value="show" name="ar-show" checked="checked" /> <?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
					<input class="ar-show-field" type="radio" value="hide" name="ar-show" /> <?php _e('Hide', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
				</div>
				<?php
				$num = 0;
				for($i=0;$i<=2;$i++){
					?>
					<div class="ar-form-table-wrapper">
						<table>
							<tr style="text-align:center">
								<td style="width:150px;"><?php _e('Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
								<td style="width:250px;"><?php _e('Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
								<td style="width:85px;"><?php _e('Operator', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
								<td style="width:105px;"><?php _e('Value', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
								<td style="width:105px;"><?php _e('Value', ADAMLABS_GALLERY_TEXTDOMAIN); ?></td>
							</tr>
							<?php 
							for($g=0;$g<=2;$g++){
								?>
								<tr>
									<td style="text-align:center">
										<select class="ar-type-field" id="ar-field-<?php echo $num - 1; ?>" name="ar-type[]" style="width: 150px;">
											<option value="off"><?php _e('--- Choose ---', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<?php
											if(!empty($types)){
												foreach($types as $handle => $val){
													?>
													<option value="<?php echo $handle; ?>"><?php echo $val['name']; ?></option>
													<?php
												}
											}
											?>
											<option value="meta"><?php _e('Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</td>
									<td>
										<input class="ar-meta-field" style="width: 150px;" name="ar-meta[]" value="" disabled="disabled" /> <a class="button-secondary ar-open-meta" href="javascript:void(0);"><i class="adamlabsgallery-icon-down-open"></i></a>
									</td>
									<td style="text-align:center">
										<select class="ar-operator-field" name="ar-operator[]" style="width: 45px;">
											<option value="isset"><?php _e('isset', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option value="empty"><?php _e('empty', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											<option class="ar-opt-meta" value="lt"><</option>
											<option class="ar-opt-meta" value="gt">></option>
											<option class="ar-opt-meta" value="equal">==</option>
											<option class="ar-opt-meta" value="notequal">!=</option>
											<option class="ar-opt-meta" value="lte"><=</option>
											<option class="ar-opt-meta" value="gte">>=</option>
											<option class="ar-opt-meta" value="between"><?php _e('between', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										</select>
									</td>
									<td>
										<input class="ar-value-field" style="width: 100px;" name="ar-value[]" value="" />
									</td>
									<td>
										<input style="width: 100px;" name="ar-value-2[]" value="" disabled="disabled" />
									</td>
									
								</tr>
								<?php
								if($g !== 2){
									?>
									<tr>
										<td colspan="5" style="text-align:center;">
											<select class="ar-logic-field" id="ar-field-<?php echo $num; ?>-logic" name="ar-logic[]">
												<option value="and"><?php _e('and', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
												<option value="or"><?php _e('or', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
											</select>
										</td>
									</tr>
									<?php
								}
								$num++;
							}
							?>
						</table>
					</div>
					<?php
					if($i !== 2){
						?>
						<div style="text-align:center;">
							<select  class="ar-logic-glob-field" name="ar-logic-glob[]">
								<option value="and"><?php _e('and', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								<option value="or"><?php _e('or', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							</select>
						</div>
						<?php
					}
				}
				
				do_action('adamlabsgallery_edit_advanced_rules_dialog_post');
				?>
			</form>
		</div>
		<?php
	}
	
	
	/**
	 * Edit Custom Element Dialog
	 */
	public static function edit_custom_element_dialog(){
		$meta = new AdamLabsGallery_Meta();
		$item_elements = new AdamLabsGallery_Item_Element();
		
		?>
		<div id="edit-custom-element-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Element Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:15px 0px;">
			<form id="edit-custom-element-form">
				<input type="hidden" name="custom-type" value="" />
				<div class="adamlabsgallery-elset-title adamlabsgallery-item-skin-media-title" data-collapse="adamlabsgallery-item-skin-elements-media">
					<?php _e('Media:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
					<i class="adamlabsgallery-icon-up-dir"></i>
				</div>
				<div id="adamlabsgallery-item-skin-elements-media">
					<div class="adamlabsgallery-elset-row adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-sound">
						<div class="adamlabsgallery-elset-label"  for="custom-soundcloud"><?php _e('SoundCloud Track ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-soundcloud" type="input" value="" />
					</div>
					<div class="adamlabsgallery-elset-row adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-youtube">
						<div class="adamlabsgallery-elset-label"  for="custom-soundcloud"><?php _e('YouTube ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-youtube" type="input" value="" />
					</div>
					<div class="adamlabsgallery-elset-row adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-vimeo">
						<div class="adamlabsgallery-elset-label"  for="custom-soundcloud"><?php _e('Vimeo ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-vimeo" type="input" value="" />
					</div>
					<div class="adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-html5">
						<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="custom-html5-mp4"><?php _e('MP4', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-html5-mp4" type="input" value="" /></div>
						<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="custom-html5-ogv"><?php _e('OGV', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-html5-ogv" type="input" value="" /></div>
						<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="custom-html5-webm"><?php _e('WEBM', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input name="custom-html5-webm" type="input" value="" /></div>
					</div>
					<div class="adamlabsgallery-elset-row adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-image">
						<div class="adamlabsgallery-elset-label" for="custom-image"><?php _e('Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
						<input type="hidden" value="" id="adamlabsgallery-custom-image" name="custom-image">
						<a id="adamlabsgallery-custom-choose-from-image-library" class="button-primary" href="javascript:void(0);" data-setto="adamlabsgallery-custom-image"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						<a id="adamlabsgallery-custom-clear-from-image-library" class="button-primary adamlabsgallery-custom-remove-custom-meta-field" href="javascript:void(0);"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						
						<div id="custom-image-wrapper" style="width:100%;">
							<img id="adamlabsgallery-custom-image-img" src="" style="max-width:200px; display: none;margin:20px 0px 0px 250px;">
						</div>
					</div>
					<div class="adamlabsgallery-elset-row adamlabsgallery-item-skin-elements" id="adamlabsgallery-item-skin-elements-media-ratio">
						<div class="adamlabsgallery-elset-label"  for="custom-ratio"><?php _e('Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
						<select name="custom-ratio">
							<option value="1"><?php _e('16:9', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="0"><?php _e('4:3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
				</div>
				<div id="adamlabsgallery-custom-item-options">
					
					<?php
					
					echo '<div class="adamlabsgallery-elset-title for-blank" data-collapse="adamlabsgallery-item-skin-elements-settings">';
					_e('Item Settings', ADAMLABS_GALLERY_TEXTDOMAIN);
					echo '<i class="adamlabsgallery-icon-up-dir"></i>';
					echo '</div>';
					echo '<div id="adamlabsgallery-item-skin-elements-settings" class="for-blank">';
					echo '<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="post-link">'.__('Link To:', ADAMLABS_GALLERY_TEXTDOMAIN).':</div><input name="post-link" value="" /></div>';
					
					echo '<div id="adamlabsgallery-custom-for-blank-wrap" class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="custom-filter">'.__('Filter(s) (comma seperated)', ADAMLABS_GALLERY_TEXTDOMAIN).':</div><input name="custom-filter" value="" /></div>';
					?>
					<div class="adamlabsgallery-elset-row for-blank">
						<div class="adamlabsgallery-elset-label" for="cobbles">
							<?php _e('Cobbles Element Size:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="cobbles-size">
							<option value="1:1"><?php _e('width 1, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="1:2"><?php _e('width 1, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="1:3"><?php _e('width 1, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="2:1"><?php _e('width 2, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="2:2"><?php _e('width 2, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="2:3"><?php _e('width 2, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="3:1"><?php _e('width 3, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="3:2"><?php _e('width 3, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="3:3"><?php _e('width 3, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
					<div class="adamlabsgallery-elset-row">
						<?php
						$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins('all', false);
						?>
						<div class="adamlabsgallery-elset-label" for="use-skin">
							<?php _e('Alternate Item Template:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="use-skin">
							<option value="-1"><?php _e('-- Default Skin --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<?php
							if(!empty($skins)){
								foreach($skins as $skin){
									echo '<option value="'.$skin['id'].'">'.$skin['name'].'</option>'."\n";
								}
							}
							?>
						</select>
					</div>
					
					<div class="adamlabsgallery-elset-row" style="margin-bottom: 5px">
						<div class="adamlabsgallery-elset-label">
							<?php _e('Item Template Modifications:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<a class="button-primary adamlabsgallery-add-custom-meta-field" href="javascript:void(0);" id="adamlabsgallery-add-custom-meta-field-custom"><?php _e('Add New Custom Skin Rule', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						<div class="adamlabsgallery-advanced-param" id="adamlabsgallery-advanced-param-custom" style="margin: 20px 0"></div>
					</div>
					
					</div>
					<?php 
					
					$elements = $item_elements->getElementsForDropdown();
					$p_lang = array('post' => __('Item Data', ADAMLABS_GALLERY_TEXTDOMAIN), 'woocommerce' => __('WooCommerce', ADAMLABS_GALLERY_TEXTDOMAIN));
					
					foreach($elements as $type => $element){
						?>
						<div class="adamlabsgallery-elset-title collapse" data-collapse="adamlabsgallery-item-skin-elements-<?php echo $type; ?>">
							<?php echo $p_lang[$type]; ?>
							<i class="adamlabsgallery-icon-down-dir"></i>
						</div>
						<div id="adamlabsgallery-item-skin-elements-<?php echo $type; ?>" style="display: none">
						<?php
						foreach($element as $handle => $itm){

							if(!isset($itm['type'])) $itm['type'] = "empty";
							
							switch($itm['type']) {
									
								case 'image';
								
									echo '<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="'.$handle.'">'.$itm['name'].':</div>';
									echo '<input type="hidden" value="" name="adamlabsgallery-' . $handle . '" id="adamlabsgallery-' . $handle . '-cm" />';
									echo '<a class="button-primary adamlabsgallery-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-' . $handle . '-cm">' . __('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN) . '</a> ';
									echo '<a class="button-primary adamlabsgallery-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-' . $handle . '-cm">' . __('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN) . '</a>';
									echo '<div>';
									echo '<img id="adamlabsgallery-' . $handle . '-cm-img" src="" style="max-width:200px; display: none;margin:20px 0px 0px 250px;">';
									echo '</div>';
									echo '</div>';
								
								break;
								
								case 'revslider';

									if(class_exists('RevSlider')) {
										
										$rev_slider = new RevSlider();
										if(method_exists($rev_slider, 'getAllSliderForAdminMenu')) {
										
											$sliders = $rev_slider->getAllSliderForAdminMenu();
											if(!empty($sliders)) {
												
												echo '<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="'.$handle.'">'.$itm['name'].':</div>';
												echo '<select name="' . $handle . '">';
												echo '<option value="">--- Choose Slider ---</option>';
												
												foreach($sliders as $id => $val) {
													
													if(isset($val['title']) && !empty($val['title'])) {
														echo '<option value="' . $id . '">' . $val['title'] . '</option>';
													}
													
												}
												echo '</select></div>';
				
											}
										}	
									}
								
								break;
								
								case 'adamlabsgallery':
									
									$grids = AdamLabsGallery::get_adamlabsgallery_grids();
									if(!empty($grids)) {
										
										echo '<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="'.$handle.'">'.$itm['name'].':</div>';
										echo '<select name="' . $handle . '">';
										echo '<option value="">--- Choose Grid ---</option>';
									
										foreach($grids as $grid) {				
											echo '<option value="' . $grid->handle . '">' . $grid->name . '</option>';
										}
										
										echo '</select></div>';
									}
								
								break;
								
								default:
								
									echo '<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  for="'.$handle.'">'.$itm['name'].':</div><input name="'.$handle.'" value="" /></div>';
								
							}
							
						}
						
						echo '</div>';
						
					}				

					$custom_meta = $meta->get_all_meta(false);
					if(!empty($custom_meta)){
						echo '<div class="adamlabsgallery-elset-title collapse" data-collapse="adamlabsgallery-item-skin-elements-meta">';
						_e('Custom Meta', ADAMLABS_GALLERY_TEXTDOMAIN);
						echo '<i class="adamlabsgallery-icon-down-dir"></i>';
						echo '</div>';
						
						echo '<div id="adamlabsgallery-item-skin-elements-meta" style="display: none">';
						foreach($custom_meta as $cmeta){
							?>
							<div class="adamlabsgallery-elset-row"><div class="adamlabsgallery-elset-label"  class="adamlabsgallery-mb-label"><?php echo $cmeta['name']; ?>:</div>
								<?php
								switch($cmeta['type']){
									case 'text':
										echo '<input type="text" name="adamlabsgallery-'.$cmeta['handle'].'" value="" />';
									break;
									case 'select':
									case 'multi-select':
										$do_array = ($cmeta['type'] == 'multi-select') ? '[]' : '';
										$el = $meta->prepare_select_by_string($cmeta['select']);
										echo '<select name="adamlabsgallery-'.$cmeta['handle'].$do_array.'"';
										if($cmeta['type'] == 'multi-select') echo ' multiple="multiple" size="5"';
										echo '>';
										if(!empty($el) && is_array($el)){
											if($cmeta['type'] == 'multi-select'){
												echo '<option value="">'.__('---', ADAMLABS_GALLERY_TEXTDOMAIN).'</option>';
											}
											foreach($el as $ele){
												echo '<option value="'.$ele.'">'.$ele.'</option>';
											}
										}
										echo '</select>';
									break;
									case 'image':
										$var_src = '';
										?>
										<input type="hidden" value="" name="adamlabsgallery-<?php echo $cmeta['handle']; ?>" id="adamlabsgallery-<?php echo $cmeta['handle'].'-cm'; ?>" />
										<a class="button-primary adamlabsgallery-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle'].'-cm'; ?>"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
										<a class="button-primary adamlabsgallery-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle'].'-cm'; ?>"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
										<div>
											<img id="adamlabsgallery-<?php echo $cmeta['handle'].'-cm'; ?>-img" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="max-width:200px; display: none;margin:20px 0px 0px 250px;"' : ''; ?>>
										</div>
										<?php
									break;
								}
								?>
							</div>
							<?php
						}
					}
					/*
					else{
						_e('<span class="adamlabsgallery-blank-hide-meta-notice">No metas available yet. Add some through the Custom Meta menu of Portfolio Gallery.</span>', ADAMLABS_GALLERY_TEXTDOMAIN);
						?><div style="clear:both; height:20px"></div><?php 			
					}
					*/
					
					echo '</div>';
					
					echo '<div class="adamlabsgallery-elset-title collapse adamlabsgallery-blank-hideable" data-collapse="adamlabsgallery-item-skin-elements-other">';
					_e('Other', ADAMLABS_GALLERY_TEXTDOMAIN);
					echo '<i class="adamlabsgallery-icon-down-dir"></i>';
					echo '</div>';
					?>
					<div id="adamlabsgallery-item-skin-elements-other" style="display: none">
					<div class="adamlabsgallery-elset-row">
						<div class="adamlabsgallery-elset-label" for="image-fit">
							<?php _e('Image Fit:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="image-fit">
							<option value="-1"><?php _e('-- Default Fit --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="contain"><?php _e('Contain', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="cover"><?php _e('Cover', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
					<div class="adamlabsgallery-elset-row">
						<div class="adamlabsgallery-elset-label" for="image-repeat">
							<?php _e('Image Repeat:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="image-repeat">
							<option value="-1"><?php _e('-- Default Repeat --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="no-repeat"><?php _e('no-repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="repeat"><?php _e('repeat', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="repeat-x"><?php _e('repeat-x', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="repeat-y"><?php _e('repeat-y', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
					<div class="adamlabsgallery-elset-row">
						<div class="adamlabsgallery-elset-label" for="image-align-horizontal">
							<?php _e('Horizontal Align:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="image-align-horizontal">
							<option value="-1"><?php _e('-- Horizontal Align --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="left"><?php _e('Left', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="center"><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="right"><?php _e('Right', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
					<div class="adamlabsgallery-elset-row">
						<div class="adamlabsgallery-elset-label" for="image-align-vertical">
							<?php _e('Vertical Align:', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
						</div>
						<select name="image-align-vertical">
							<option value="-1"><?php _e('-- Vertical Align --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="top"><?php _e('Top', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="center"><?php _e('Center', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<option value="bottom"><?php _e('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
						</select>
					</div>
				</div>
				<?php
				do_action('adamlabsgallery_edit_custom_element_dialog_post');
				?>
			</form>
			<script type="text/javascript">
				
				<?php 
				
					$advanced = array();
					$base = new AdamLabsGallery_Base();
					$item_skin = new AdamLabsGallery_Item_Skin();
					$item_elements = new AdamLabsGallery_Item_Element();
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
					
				?>
				
				AdminEssentials.setInitSkinsJson(<?php echo $base->jsonEncodeForClientSide($advanced); ?>);
				AdminEssentials.setInitStylingJson(<?php echo $base->jsonEncodeForClientSide($eg_elements); ?>);
			
				jQuery('.adamlabsgallery-image-add').click(function(e) {
					e.preventDefault();
					AdminEssentials.upload_image_img(jQuery(this).data('setto'));
					
					return false; 
				});
				
				jQuery('.adamlabsgallery-image-clear').click(function(e) {
					e.preventDefault();
					var setto = jQuery(this).data('setto');
					jQuery('#'+setto).val('');
					jQuery('#'+setto+'-img').attr("src","");
					jQuery('#'+setto+'-img').hide();
					return false; 
				});
				
				jQuery('#adamlabsgallery-custom-choose-from-image-library').click(function(e) {
					e.preventDefault();
					AdminEssentials.upload_image_img(jQuery(this).data('setto'));

					return false; 
				});
				
				jQuery('#adamlabsgallery-custom-clear-from-image-library').click(function(e) {
					e.preventDefault();
					
					jQuery('#adamlabsgallery-custom-image-src').val('');
					jQuery('#adamlabsgallery-custom-image').val('');
					jQuery('#adamlabsgallery-custom-image-img').attr("src","");
					jQuery('#adamlabsgallery-custom-image-img').hide();
					return false; 
				});
				
				jQuery('.adamlabsgallery-elset-title').click(function() {
					
					var $this = jQuery(this);
					if($this.hasClass('collapse')) {
						
						$this.removeClass('collapse').find('i').attr('class', 'adamlabsgallery-icon-up-dir');
						jQuery('#' + $this.attr('data-collapse')).slideDown();
						
					}
					else {
						
						$this.addClass('collapse').find('i').attr('class', 'adamlabsgallery-icon-down-dir');
						jQuery('#' + $this.attr('data-collapse')).slideUp();
						
					}
					
				});
				
				<?php
				do_action('adamlabsgallery_edit_custom_element_dialog_script');
				?>
			</script>
		</div>
		<?php
	}
	
	
	/**
	 * Add tinymce shortcode dialog
	 */
	public static function add_tiny_mce_shortcode_dialog(){
		$base = new AdamLabsGallery_Base();
		$grid_c = new AdamLabsGallery();
		$skins_c = new AdamLabsGallery_Item_Skin();
		
		$grids = AdamLabsGallery::get_grids_short_vc();
		?>
		<div id="adamlabsgallery-tiny-mce-dialog" tabindex="-1" action="" class="adamlabsgallery-dialog-wrap" title="" style="display: none; ">
			<script type="text/javascript">
				var token = '<?php echo wp_create_nonce("AdamLabsGallery_actions"); ?>';
			</script>
			<form id="adamlabsgallery-tiny-mce-settings-form" action="">
			
				<!-- STEP 1 -->
				<div id="adamlabsgallery-tiny-dialog-step-1">
					<div class="ess-top_half">
						<p class="ess-quicktitle"><?php _e('Predefined Grids:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
						<select name="adamlabsgallery-existing-grid">
							<option value="-1"><?php _e('--- Select Grid ---', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
							<?php
							if(!empty($grids)){
								foreach($grids as $title => $alias){
									echo '<option value="'.$alias.'">'.$title.'</option>'."\n";
								}
							}
							?>
						</select>
						<div style="margin-top:20px">
							<a href="javascript:void(0);" class="button-primary" id="adamlabsgallery-add-predefined-grid"><?php _e('Insert Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<!--a href="<?php echo AdamLabsGallery_Base::getViewUrl(AdamLabsGallery_Admin::VIEW_GRID_CREATE, 'create=true'); ?>" target="_blank" class="button-primary" id="adamlabsgallery-create-predefined-grid"><?php _e('Create Full Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a-->
						</div>
					</div>
					<div class="ess-bottom_half">
						<p class="ess-quicktitle"><?php _e('Custom Quick Grids:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
						
						<a href="javascript:void(0);" class="" id="adamlabsgallery-create-wp-gallery">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-format-gallery ess-customgridicon"></div>
								<div class="ess-customonbutton" id="shift8_portfolio_gallery_button"><?php _e('WordPress Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>

						<a href="javascript:void(0);" class="" id="adamlabsgallery-create-custom-grid">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-admin-media ess-customgridicon"></div>
								<div class="ess-customonbutton"><?php _e('Create Custom Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>
						
						<a href="javascript:void(0);" class="" id="adamlabsgallery-edit-custom-grid">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-admin-media ess-customgridicon"></div>
								<div class="ess-customonbutton"><?php _e('Edit Custom Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>
						
						<div class="ess-rowdivider"></div>

						<a href="javascript:void(0);" class="" id="adamlabsgallery-create-popularpost-grid">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-groups ess-customgridicon"></div>
								<div class="ess-customonbutton"><?php _e('Popular Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>


						<a href="javascript:void(0);" class="" id="adamlabsgallery-create-recentpost-grid">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-calendar ess-customgridicon"></div>
								<div class="ess-customonbutton"><?php _e('Recent Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>
						
						<a href="javascript:void(0);" class="" id="adamlabsgallery-create-relatedpost-grid">
							<div class="ess-customgridwrap">
								<div class="dashicons dashicons-tickets ess-customgridicon"></div>
								<div class="ess-customonbutton"><?php _e('Related Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							</div>
						</a>
						
					</div>
					
					<div class="ess-stepnavigator">
						<span class="ess-currentstep"><?php _e('STEP 1 - Select Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
					</div>
				</div>
				
				<!-- STEP 2.5 -->
				<div id="adamlabsgallery-tiny-dialog-step-2-5" style="display: none;">
					<div id="adamlabsgallery-tiny-shortcode-analyze-wrap" class="ess-top_half" style="padding-top:0px;margin-top:0px;padding-bottom:30px;">
						<div class="ess-quicktitle" style="margin-left:25px;"><?php _e('Edit Existing QuickGrid ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
						<p>
							<label><?php _e('Input Quickgrid Code', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
							<input type="text" name="adamlabsgallery-shortcode-analyzer" value="" /> <a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-shortcode-do-analyze"><?php _e('Analyze Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						</p>
						<p style="line-height:20px;font-size:11px;font-style:italic;color:#999;"><?php _e('You can paste your Existing Quick Grid Shortcode here for further editing. Simple copy the full Shortcode of Portfolio Gallery i.e. [adamlabsgallery settings=....][/adamlabsgallery] and paste it here.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
					</div>
					<div style="width:100%;height:30px"></div>
					<div class="ess-stepnavigator">
						<a href="javascript:void(0);" class=""  id="adamlabsgallery-goto-step-1-5">
							<div class="ess-stepbutton-left">
								<div class="dashicons dashicons-arrow-left-alt2"></div>	
								<span class="ess-currentstep"><?php _e('STEP 1 - Select Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</div>
						</a>
					</div>
				</div>
				
				<!-- STEP 2 -->
				<div id="adamlabsgallery-tiny-dialog-step-2" style="display: none;">
					<div id="adamlabsgallery-tiny-settings-wrap">
						<div class="ess-top_half" style="padding:0px 0px 30px;">
							<div class="ess-quicktitle" style="margin-left:25px;"><?php _e('Predefined Grid Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							
							<p style="">
								<label><?php _e('Choose Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<select name="adamlabsgallery-tiny-existing-settings">
									<option value="-1"><?php _e('--- Choose Grid to use Settings from Grid ---', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<?php
									if(!empty($grids)){
										foreach($grids as $title => $alias){
											echo '<option value="'.$alias.'">'.$title.'</option>'."\n";
										}
									}
									?>
								</select>
								<p style="line-height:20px;font-size:11px;font-style:italic;color:#999;"><?php _e('Use the Grid Settings from one of the Existing Portfolio Gallery. This helps to use all Complex settings of a Grid, not just the quick settings listed below.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							</p>
						</div>
						<div class="ess-bottom_half" style="padding:30px 0px 0px;">
							<div class="ess-quicktitle" style="margin-left:25px;"><?php _e('Quick Grid Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
							
							<p class="adamlabsgallery-max-entries" style="display: none; background:#FFF;">
								<label><?php _e('Maximum Entries', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
								<input type="text" name="adamlabsgallery-tiny-max-entries" value="20" />
							</p>
							<div id="adamlabsgallery-tiny-grid-settings-wrap">
		
								<p>
									<label><?php _e('Grid Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select name="adamlabsgallery-tiny-entry-skin">
										<?php
										$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins('all', false);
										
										if(!empty($skins)){
											foreach($skins as $skin){
												echo '<option value="'.$skin['id'].'">'.$skin['name'].'</option>'."\n";
											}
										}
										?>
									</select>
								</p>
								<p>
									<label><?php _e('Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select name="adamlabsgallery-tiny-layout-sizing">
										<option value="boxed"><?php _e('Boxed', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="fullwidth"><?php _e('Fullwidth', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
								<p>
									<label><?php _e('Grid Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select name="adamlabsgallery-tiny-grid-layout">
										<option value="even"><?php _e('Even', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="masonry"><?php _e('Masonry', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="cobbles"><?php _e('Cobbles', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									</select>
								</p>
								<p>
									<label><?php _e('Item Spacing', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="text" name="adamlabsgallery-tiny-spacings" value="0" />
								</p>
								<p>
									<label><?php _e('Pagination', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="radio" style="margin-left:0px !important;" name="adamlabsgallery-tiny-rows-unlimited" value="on" /> <?php _e('Disable', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
									<input type="radio" name="adamlabsgallery-tiny-rows-unlimited" checked="checked" value="off" /> <?php _e('Enable', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
								</p>
								<p>
									<label><?php _e('Columns', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="text" name="adamlabsgallery-tiny-columns" value="5" />
								</p>
								<p>
									<label><?php _e('Max. Visible Rows', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<input type="text" name="adamlabsgallery-tiny-rows" value="3" />
								</p>
								<p>
									<label><?php _e('Start and Filter Animations', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<?php
									$anims = AdamLabsGallery_Base::get_grid_animations();
									?>
									<select class="adamlabsgallery-tooltip-wrap tooltipstered" name="adamlabsgallery-tiny-grid-animation" id="grid-animation-select">
										<?php
										foreach($anims as $value => $name){
											echo '<option value="'.$value.'">'.$name.'</option>'."\n";
										}
										?>
									</select>
								</p>
								<p>
									<label><?php _e('Choose Spinner', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
									<select class="adamlabsgallery-tooltip-wrap tooltipstered" name="adamlabsgallery-tiny-use-spinner" id="use_spinner">
										<option value="-1"><?php _e('off', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
										<option value="0" selected="selected">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</p>
							</div>
						</div>
					</div>
					<!--<a href="javascript:void(0);" class="button-primary"  id="adamlabsgallery-goto-step-3"><?php _e('Add Entries', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>-->
					<div style="width:100%;height:30px"></div>
					<div class="ess-stepnavigator">
						<a href="javascript:void(0);" class=""  id="adamlabsgallery-goto-step-1">
							<div class="ess-stepbutton-left">
								<div class="dashicons dashicons-arrow-left-alt2"></div>	
								<span class="ess-currentstep"><?php _e('STEP 1 - Select Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</div>
						</a>

						<a href="javascript:void(0);" class=""  id="adamlabsgallery-goto-step-3">
							<div class="ess-stepbutton-right">
								<span class="ess-currentstep"><?php _e('STEP 3 - Add Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<div class="dashicons dashicons-arrow-right-alt2"></div>									
							</div>
						</a>

						<a href="javascript:void(0);" class=""  id="adamlabsgallery-add-custom-shortcode-special" style="display: none;">
							<div class="ess-stepbutton-right">
								<span class="ess-currentstep"><?php _e('FINNISH - Generate Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<div class="dashicons dashicons-arrow-right-alt2"></div>									
							</div>
						</a>
					</div>
				</div>
				<?php
				do_action('adamlabsgallery_edit_custom_element_dialog_form');
				?>
			</form>
			<form id="adamlabsgallery-tiny-mce-layers-form" action="">
			
				<!-- STEP 3 -->
				<div id="adamlabsgallery-tiny-dialog-step-3" style="display: none;">
					<div style="padding:30px">
						<div class="adamlabsgallery-mediaselector"><a href="javascript:void(0);" class="adamlabsgallery-add-custom-element" data-type="image"><div class="dashicons dashicons-format-image"></div><?php _e('Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div class="adamlabsgallery-mediaselector"><a href="javascript:void(0);" class="adamlabsgallery-add-custom-element" data-type="html5"><div class="dashicons dashicons-editor-video"></div><?php _e('HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div class="adamlabsgallery-mediaselector"><a href="javascript:void(0);" class="adamlabsgallery-add-custom-element" data-type="vimeo"><div class="dashicons dashicons-format-video"></div><?php _e('Vimeo', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div class="adamlabsgallery-mediaselector"><a href="javascript:void(0);" class="adamlabsgallery-add-custom-element" data-type="youtube"><div class="dashicons dashicons-format-video"></div><?php _e('YouTube', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div class="adamlabsgallery-mediaselector"><a href="javascript:void(0);" class="adamlabsgallery-add-custom-element" data-type="soundcloud"><div class="dashicons dashicons-format-audio"></div><?php _e('SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div class="adamlabsgallery-mediaselector collapseall"><a href="javascript:void(0);"><div class="dashicons dashicons-sort"></div><?php _e('Collapse', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
						<div id="adamlabsgallery-custom-elements-wrap">
							
						</div>
					</div>
					
					<div style="width:100%;height:30px"></div>
					<div class="ess-stepnavigator">
						<a href="javascript:void(0);" class=""  id="adamlabsgallery-goto-step-2">
							<div class="ess-stepbutton-left">
								<div class="dashicons dashicons-arrow-left-alt2"></div>	
								<span class="ess-currentstep"><?php _e('STEP 2 - Grid Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
							</div>
						</a>
						<a href="javascript:void(0);" class="" id="adamlabsgallery-add-custom-shortcode">
							<div class="ess-stepbutton-right">
								<span class="ess-currentstep"><?php _e('FINNISH - Generate Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
								<div class="dashicons dashicons-arrow-right-alt2"></div>									
							</div>
						</a>
					</div>

				</div>
				<?php
				do_action('adamlabsgallery_edit_custom_element_dialog_form_layer');
				?>
			</form>
			<div class="adamlabsgallery-tiny-template-wrap adamlabsgallery-tiny-element" style="display: none;">
				<div class="adamlabsgallery-tiny-collapse-wrapper">
						<div style="width:100%;height:10px;"></div>
						<div class="adamlabsgallery-tiny-custom-wrapper" >
							<!-- POSTER  IMAGE -->
							<div id="adamlabsgallery-tiny-custom-poster-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="ess-quicktitle"><?php _e('Choose Poster Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
								<div class="adamlabsgallery-tiny-img-placeholder"><img src="" class="adamlabsgallery-tiny-preshow-img" style="display: none;" /></div>
								<a href="javascript:void(0);" class="adamlabsgallery-toolbutton adamlabsgallery-select-image" data-setto="adamlabsgallery-tiny-custom-poster[]"><div class="dashicons dashicons-plus"></div></a>
								<input type="hidden" name="adamlabsgallery-tiny-custom-poster[]" data-type="image" value="" />
							</div>
							<!-- SIMPLE IMAGE -->
							<div id="adamlabsgallery-tiny-custom-image-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="ess-quicktitle"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
								<div class="adamlabsgallery-tiny-img-placeholder"><img src="" class="adamlabsgallery-tiny-preshow-img" style="display: none;" /></div>
								<a href="javascript:void(0);" class="adamlabsgallery-toolbutton adamlabsgallery-select-image" data-setto="adamlabsgallery-tiny-custom-image[]"><div class="dashicons dashicons-plus"></div></a>
								<input type="hidden" name="adamlabsgallery-tiny-custom-image[]" data-type="image" value="" />
							</div>
							<!-- VIMEO ID SELECTOR -->
							<div id="adamlabsgallery-tiny-custom-vimeo-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('Vimeo ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </div><input type="text" name="adamlabsgallery-tiny-custom-vimeo[]" value="" />
							</div>
							<!-- YOUTUBE ID SELECTOR -->
							<div id="adamlabsgallery-tiny-custom-youtube-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('YouTube ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </div><input type="text" name="adamlabsgallery-tiny-custom-youtube[]" value="" />
							</div>
							<!-- SOUND CLOUD SELECTOR -->
							<div id="adamlabsgallery-tiny-custom-soundcloud-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="ess-quicktitle"><?php _e('SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('SoundCloud ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div> <input type="text" name="adamlabsgallery-tiny-custom-soundcloud[]" value="" />
							</div>
							<!-- HTML5 SELECTORS -->
							<div id="adamlabsgallery-tiny-custom-html5-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('WEBM URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div> <input type="text" name="adamlabsgallery-tiny-custom-html5-webm[]" value="" />
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('OGV URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div> <input type="text" name="adamlabsgallery-tiny-custom-html5-ogv[]" value="" />
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('MP4 URL', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div> <input type="text" name="adamlabsgallery-tiny-custom-html5-mp4[]" value="" />
							</div>
							<!-- VIDEO RATIO -->
							<div id="adamlabsgallery-tiny-custom-ratio-wrap" class="adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('Video Ratio', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </div>
								<select name="adamlabsgallery-tiny-custom-ratio[]">
									<option value="1" selected>16:9</option>
									<option value="0">4:3</option>
									
								</select>
							</div>
							<!-- COBBLES SETTINGS -->
							<div class="adamlabsgallery-tiny-cobbles-size-wrap adamlabsgallery-tiny-option-wrap" style="display: none;">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('Cobbles Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </div>
								<select name="adamlabsgallery-tiny-cobbles-size[]">
									<option value="1:1"><?php _e('width 1, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="1:2"><?php _e('width 1, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="1:3"><?php _e('width 1, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="2:1"><?php _e('width 2, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="2:2"><?php _e('width 2, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="2:3"><?php _e('width 2, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="3:1"><?php _e('width 3, height 1', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="3:2"><?php _e('width 3, height 2', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<option value="3:3"><?php _e('width 3, height 3', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
								</select>
							</div>
							<!-- CUSTOM SKIN SETTINGS -->
							<div class="adamlabsgallery-tiny-use-skin-wrap adamlabsgallery-tiny-option-wrap">
								<div class="adamlabsgallery-tiny-elset-label"><?php _e('Specific Skin', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </div>
								<?php
								$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins('all', false);
								?>
								<select name="adamlabsgallery-tiny-use-skin[]">
									<option value="-1"><?php _e('-- Default Skin --', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
									<?php
									if(!empty($skins)){
										foreach($skins as $skin){
											echo '<option value="'.$skin['id'].'">'.$skin['name'].'</option>'."\n";
										}
									}
									?>
								</select>
							</div>
							<?php
							do_action('adamlabsgallery_edit_custom_element_dialog_template');
							?>
						</div>
						
						<div class="adamlabsgallery-tiny-custom-wrapper" >
							<?php
							/*$meta = new AdamLabsGallery_Meta();
							$custom_meta = $meta->get_all_meta(false);
							if(!empty($custom_meta)){
								echo '<div class="adamlabsgallery-tiny-elset-title">';
								_e('Layers Content', ADAMLABS_GALLERY_TEXTDOMAIN);
								echo '</div>';
							
								foreach($custom_meta as $cmeta){
									?>
									<div class="adamlabsgallery-tiny-<?php echo $cmeta['handle']; ?>-wrap adamlabsgallery-tiny-elset-row" style="display: none;"><div class="adamlabsgallery-elset-label" class="adamlabsgallery-mb-label"><?php echo $cmeta['name']; ?>:</div>
										<?php
										switch($cmeta['type']){
											case 'text':
												echo '<input type="text" name="adamlabsgallery-tiny-'.$cmeta['handle'].'[]" value="" />';
												break;
											case 'select':
												$el = $meta->prepare_select_by_string($cmeta['select']);
												echo '<select name="adamlabsgallery-tiny-'.$cmeta['handle'].'[]">';
												if(!empty($el) && is_array($el)){
													echo '<option value="">'.__('---', ADAMLABS_GALLERY_TEXTDOMAIN).'</option>';
													foreach($el as $ele){
														
														echo '<option value="'.$ele.'">'.$ele.'</option>';
													}
												}
												echo '</select>';
												break;
											case 'image':
												$var_src = '';
												?>
												<input type="hidden" value="" name="adamlabsgallery-tiny-<?php echo $cmeta['handle']; ?>[]" id="adamlabsgallery-tiny-<?php echo $cmeta['handle']; ?>" />
												<a class="button-primary adamlabsgallery-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle']; ?>"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
												<a class="button-primary adamlabsgallery-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-<?php echo $cmeta['handle']; ?>"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
												<div>
													<img id="adamlabsgallery-tiny-<?php echo $cmeta['handle']; ?>-img" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="max-width:200px; display: none;margin:20px 0px 0px 250px;"' : ''; ?>>
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
							}*/
							
							$elements = AdamLabsGallery_Item_Element::getElementsForDropdown();
							$p_lang = array('post' => __('Post', ADAMLABS_GALLERY_TEXTDOMAIN), 'woocommerce' => __('WooCommerce', ADAMLABS_GALLERY_TEXTDOMAIN));
						
							foreach($elements as $type => $element){
								?>
								<!--<div class="adamlabsgallery-tiny-elset-title">
									<?php echo $p_lang[$type]; ?>
								</div>-->
								<?php
								foreach($element as $handle => $name){
									echo '<div class="adamlabsgallery-tiny-'.$handle.'-wrap adamlabsgallery-tiny-elset-row" style="display: none;"><div class="adamlabsgallery-tiny-elset-label"  for="'.$handle.'">'.$name['name'].':</div><input name="adamlabsgallery-tiny-'.$handle.'[]" value="" /></div>';
								}
							}
							?>
						</div>
						<div style="clear:both"></div>
						<div style="width:100%;height:30px;"></div>						
				</div>
				
				<div class="adamlabsgallery-tiny-custom-pictogram"><div class="dashicons dashicons-format-image"></div></div>
				<div class="adamlabsgallery-toolbutton adamlabsgallery-delete-item">
					<a href="javascript:void(0);" class="adamlabsgallery-tiny-delete-entry"><div class="dashicons dashicons-trash"></div></a>
				</div>
				<div class="adamlabsgallery-toolbutton adamlabsgallery-collapsme-item">
					<a href="javascript:void(0);" class="adamlabsgallery-tiny-collapsme-entry"><div class="dashicons dashicons-sort"></div></a>
				</div>
				<img class="adamlabsgallery-toolbarimg" src="">

			</div>
			<script type="text/javascript">
				<?php
				$skin_layers = array();
				
				$all_skins = $skins_c->get_adamlabsgallery_item_skins();
				
				if(!empty($all_skins)){
					foreach($all_skins as $cskin){
						$custom_layer_elements = array();
						if(isset($cskin['layers'])){
							foreach($cskin['layers'] as $layer){
								if(@isset($layer['settings']['source'])){
							
									switch($layer['settings']['source']){
										case 'post':
											$custom_layer_elements[@$layer['settings']['source-post']] = '';
											break;
										case 'woocommerce':
											$custom_layer_elements[@$layer['settings']['source-woocommerce']] = '';
											break;
									}
									
								}
							}
						}
						$skin_layers[$cskin['id']] = $custom_layer_elements;
					}
				}
				
				?>
				
				var adamlabsgallery_tiny_skin_layers = jQuery.parseJSON(<?php echo $base->jsonEncodeForClientSide($skin_layers); ?>);
				
				
				// KRIKI SCRIPT 
				var adamlabsgalleryCustomCollapser = function(bt,direction) {
					var	cp =  bt.closest('.adamlabsgallery-tiny-element'),
						cpitem = cp.find('.adamlabsgallery-tiny-collapse-wrapper'),
						timg = cp.find('.adamlabsgallery-toolbarimg'),
						pimg = cp.find('.adamlabsgallery-tiny-preshow-img');
						
					if ((direction=="auto" && cpitem.hasClass("collapsed")) || direction=="open") {
					   cpitem.slideDown(200);
					   cpitem.removeClass("collapsed");
					   bt.removeClass("collapsed");
					   timg.removeClass("collapsed");
				   } else {
					   cpitem.slideUp(200);					   
					   cpitem.addClass("collapsed");
					   bt.addClass("collapsed");	
					   timg.addClass("collapsed");
					   jQuery.each(pimg,function(index,pimge) {
							if (jQuery(pimge).attr('src') !=undefined && jQuery(pimge).attr('src').length>0)
									timg.attr('src',jQuery(pimge).attr('src'));						   
					   })
					}
				}
				
				jQuery('body').on('click','.adamlabsgallery-toolbutton.adamlabsgallery-collapsme-item',function() {
					adamlabsgalleryCustomCollapser(jQuery(this),"auto");
				});
				
					
				jQuery('.adamlabsgallery-mediaselector.collapseall').click(function() {
					var ca = jQuery(this);
					if (ca.hasClass("collapsed")) {
						jQuery('.adamlabsgallery-toolbutton.adamlabsgallery-collapsme-item').each(function() {
							adamlabsgalleryCustomCollapser(jQuery(this),"open");
						})
						ca.removeClass("collapsed");
					} else {
						jQuery('.adamlabsgallery-toolbutton.adamlabsgallery-collapsme-item').each(function() {
							adamlabsgalleryCustomCollapser(jQuery(this),"close");
						})
					
						ca.addClass("collapsed");						
					}				
				});
				<?php
				do_action('adamlabsgallery_edit_custom_element_dialog_script');
				?>
			</script>
		</div>
		<?php
	}
	
	
	/**
	 * Filter Dialog Box
	 */
	public static function filter_select_dialog(){
		?>
		<div id="filter-select-dialog-wrap" class="adamlabsgallery-dialog-wrap" title="<?php _e('Select Filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"  style="display: none; padding:20px !important;">
			<select id="adamlabsgallery-filter-select-box" name="custom-filter-select" multiple="true" size="10" style="width: 560px">
				
			</select>
			<?php
			do_action('adamlabsgallery_filter_select_dialog');
			?>
		</div>
		<?php
	}
}
?>