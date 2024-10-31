<?php
 
if( !defined( 'ABSPATH') ) exit();

//force the js file to be included
//wp_enqueue_script('adamlabsgallery-item-editor-script', ADAMLABS_GALLERY_PLUGIN_URL.'com/admin/assets/js/grid-editor.js', array('jquery'), AdamLabsGallery::VERSION );

$metas = new AdamLabsGallery_Meta();
$meta_links = new AdamLabsGallery_Meta_Linking();
	
?>
<h2 class="topheader"><?php echo esc_html(get_admin_page_title()); ?></h2>

<div id="adamlabsgallery-grid-custom-meta-wrapper">
	<ul class="adamlabsgallery-gridmeta-tabs">
		<li><a href="#adamlabsgallery-custom-meta-wrap"><?php _e('Custom Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
		<li><a href="#adamlabsgallery-meta-links-wrap"><?php _e('Meta References / Aliases', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></li>
	</ul>
	<div id="adamlabsgallery-custom-meta-wrap">
		<div class="adamlabsgallery-custom-meta-info-box" style="padding:10px 15px; margin-bottom:15px;background:#3498db; color:#fff;">
			<h3 style="color:#fff"><div style="margin-right:15px;" class="dashicons dashicons-info"></div><?php _e('What Are Custom Meta Boxes?', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div style="float:right" class="dashicons dashicons-arrow-down-alt2"></div></h3>
			<div class="adamlabsgallery-custom-meta-toggle-visible">
				<p><?php _e('A custom meta (or write) box is incredibly simple in theory. It allows you to add a custom piece of data to a post or page in WordPress.<br>These meta boxes are available in any Posts, Custom Posts, Pages and Custom Items in Grid Editor in the Portfolio Gallery.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				<p><?php _e('Imagine you wish to have a Custom Link to your posts. You can create 1 Meta Box named <i>Custom Link</i>. Now this Meta Box is available in all your posts where you can add your individual value for it.  In the Skin Editor you can refer to this Meta Data to show the individual content of your posts.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				<h3 style="color:#fff"><?php _e('Where can I find the Custom Meta Fields?', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
				<p><?php _e('You can edit the Custom Meta Values in your posts, custom post and  pages within the Portfolio Gallery section, and also in the Portfolio Gallery Editor by clicking on the <strong>Cog Wheel Icon</strong> <span class="dashicons dashicons-admin-generic"></span> of the Item.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				<h3 style="color:#fff"><?php _e('How to add Custom Meta Fields to my Skin?', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
				<p style="margin-bottom:15px"><?php _e('<strong>Edit the Skin</strong> you selected for the Grid(s) and <strong>add or edit</strong> an existing <strong>Layer</strong>. Here you can select under the source tab the <strong>Source Type</strong> to <strong>"POST"</strong> and <strong>Element</strong> to <strong>"META"</strong>. Pick the Custom Meta Key of your choice from the Drop Down list. ', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				
			</div>

		</div>
		<?php
		$custom_metas = $metas->get_all_meta(false);
		
		if(!empty($custom_metas)){
			foreach($custom_metas as $meta){
				if(!isset($meta['sort-type'])) $meta['sort-type'] = 'alphabetic';
				?>
				<div class="postbox adamlabsgallery-postbox" style="width:100%;min-width:500px">
					<h3 class="box-closed"><span style="font-weight:400"><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span>adamlabsgallery-<?php echo $meta['handle']; ?> </span><div class="postbox-arrow"></div></h3>
					<div class="inside" style="display:none;padding:0px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
						<input type="hidden" name="adamlabsgallery-meta-handle[]" value="<?php echo $meta['handle']; ?>" />
						<div class="adamlabsgallery-custommeta-row">
							<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-meta-name[]" value="<?php echo @$meta['name']; ?>"></div>
							<div class="adamlabsgallery-cus-row-l">
								<label><?php _e('Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-meta-type[]" disabled="disabled">
									<option value="<?php echo $meta['type']; ?>"><?php echo ucwords(str_replace('-', ' ', $meta['type'])); ?></option>
								</select>
							</div>
							<div class="adamlabsgallery-cus-row-l">
								<label><?php _e('Sort Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-meta-sort-type[]" disabled="disabled">
									<option value="<?php echo $meta['sort-type']; ?>"><?php echo ucwords(str_replace('-', ' ', $meta['sort-type'])); ?></option>
								</select>
							</div>
							<div class="adamlabsgallery-cus-row-l"><label><?php _e('Default:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-meta-default[]" value="<?php echo @$meta['default']; ?>"></div>
							<div class="adamlabsgallery-cus-row-l">
								<?php if($meta['type'] == 'select' || $meta['type'] == 'multi-select') { ?>
								<label><?php _e('List:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><textarea class="adamlabsgallery-custommeta-textarea" name="adamlabsgallery-meta-select[]"><?php echo @$meta['select']; ?></textarea>
								<?php } ?>
							</div>									
						</div>
						<div class="adamlabsgallery-custommeta-save-wrap-settings">
							<a class="button-primary adamlabsgallery-meta-edit" href="javascript:void(0);"><?php _e('Edit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a class="button-primary adamlabsgallery-meta-delete" href="javascript:void(0);"><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
		
        <a class="button-primary" <?php if($GLOBALS['adamlabsgallery_validated'] === 'true'): ?>id="adamlabsgallery-meta-add"<?php endif; ?> href="javascript:void(0);"><?php _e('Add New Meta', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></a>
        <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>
            <a href="https://pluginjungle.com/downloads/photo-portfolio-gallery/" class="button-primary revred adamlabsgallery-pro-license-button" target="_blank">Get Pro License for only $19.99</a>
        <?php endif; ?>
    </div>
	
	<div id="adamlabsgallery-meta-links-wrap">
		<div class="adamlabsgallery-custom-meta-info-box" style="padding:10px 15px; margin-bottom:15px;background:#3498db; color:#fff;">
			<h3 style="color:#fff"><div style="margin-right:15px;" class="dashicons dashicons-info"></div><?php _e('What Are Meta References / Aliases ?', ADAMLABS_GALLERY_TEXTDOMAIN); ?><div style="float:right" class="dashicons dashicons-arrow-down-alt2"></div></h3>
			<div class="adamlabsgallery-custom-meta-toggle-visible">
				<p><?php _e('To make the selection of different <strong>existing Meta Datas of other plugins and themes</strong> easier within the Portfolio Gallery, we created this Reference Table. <br>Define the Internal name (within Portfolio Gallery) and the original Handle Name of the Meta Key, and all these Meta Keys are available anywhere in Portfolio Gallery from now on.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				<h3 style="color:#fff"><?php _e('Where can I edit the Meta Key References ?', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
				<p><?php _e('You will still need to edit the Value of these Meta Keys in the old place where you edited them before. (Also applies to  WooCommerce, Event Plugins or other third party plugins)    We only reference on these values to deliver the value to the Grid.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				<h3 style="color:#fff"><?php _e('How to add Meta Field References to my Skin?', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h3>
				<p style="margin-bottom:15px"><?php _e('<strong>Edit the Skin</strong> you selected for the Grid(s) and <strong>add or edit</strong> an existing <strong>Layer</strong>. Here you can select under the source tab the <strong>Source Type</strong> to <strong>"POST"</strong> and <strong>Element</strong> to <strong>"META"</strong>. Pick the Custom Meta Key of your choice from the Drop Down list. ', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
				
			</div>

		</div>
		<?php
		$link_metas = $meta_links->get_all_link_meta();
		
		if(!empty($link_metas)){
			foreach($link_metas as $meta){
				if(!isset($meta['sort-type'])) $meta['sort-type'] = 'alphabetic';
				?>
				<div class="postbox adamlabsgallery-postbox" style="width:100%;min-width:500px">
					<h3 class="box-closed"><span style="font-weight:400"><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span>egl-<?php echo $meta['handle']; ?> </span><div class="postbox-arrow"></div></h3>
					<div class="inside" style="display:none;padding:0px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
						<input type="hidden" name="adamlabsgallery-link-meta-handle[]" value="<?php echo $meta['handle']; ?>" />
						<div class="adamlabsgallery-custommeta-row">
							<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-link-meta-name[]" value="<?php echo @$meta['name']; ?>"></div>
							<div class="adamlabsgallery-cus-row-l"><label><?php _e('Original Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-link-meta-original[]" value="<?php echo @$meta['original']; ?>"></div>
							<div class="adamlabsgallery-cus-row-l">
								<label><?php _e('Sort Type:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><select name="adamlabsgallery-link-meta-sort-type[]" disabled="disabled">
									<option value="<?php echo $meta['sort-type']; ?>"><?php echo ucfirst($meta['sort-type']); ?></option>
								</select>
							</div>
						</div>
						<div class="adamlabsgallery-custommeta-save-wrap-settings">
							<a class="button-primary adamlabsgallery-link-meta-edit" href="javascript:void(0);"><?php _e('Edit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a class="button-primary adamlabsgallery-link-meta-delete" href="javascript:void(0);"><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
		<a class="button-primary" <?php if($GLOBALS['adamlabsgallery_validated'] === 'true'): ?>id="adamlabsgallery-link-meta-add"<?php endif; ?> href="javascript:void(0);"><?php _e('Add New Meta Reference', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></a>
        <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>
            <a href="https://pluginjungle.com/downloads/photo-portfolio-gallery/" class="button-primary revred adamlabsgallery-pro-license-button" target="_blank">Get Pro License for only $19.99</a>
        <?php endif; ?>
    </div>
</div>

<?php AdamLabsGallery_Dialogs::custom_meta_dialog(); ?>
<?php AdamLabsGallery_Dialogs::custom_meta_linking_dialog(); ?>

<script type="text/javascript">
	jQuery(function(){
		AdminEssentials.initCustomMeta();
	});
</script>
