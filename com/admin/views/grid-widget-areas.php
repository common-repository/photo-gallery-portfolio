<?php
 
if( !defined( 'ABSPATH') ) exit();

?>
	<h2 class="topheader"><?php _e('Custom Widgets', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h2>
	
	<div id="adamlabsgallery-grid-widget-areas-wrapper">
		<?php
		$wa = new AdamLabsGallery_Widget_Areas();
		$sidebars = $wa->get_all_sidebars();
		
		if(is_array($sidebars) && !empty($sidebars)){
			foreach($sidebars as $handle => $name){
				?>
				<div class="postbox adamlabsgallery-postbox" style="width:100%;min-width:500px">
					<h3 class="box-closed"><span style="font-weight:400"><?php _e('Handle:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span><span>adamlabsgallery-<?php echo $handle; ?> </span><div class="postbox-arrow"></div></h3>
					<div class="inside" style="display:none;padding:0px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
						<input type="hidden" name="adamlabsgallery-widget-area-handle[]" value="<?php echo $handle; ?>" />
						<div class="adamlabsgallery-custommeta-row">
							<div class="adamlabsgallery-cus-row-l"><label><?php _e('Name:', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label><input type="text" name="adamlabsgallery-widget-area-name[]" value="<?php echo @$name; ?>"></div>
						</div>
						
						<div class="adamlabsgallery-widget-area-save-wrap-settings">
							<a class="button-primary adamlabsgallery-widget-area-edit" href="javascript:void(0);"><?php _e('Edit', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
							<a class="button-primary adamlabsgallery-widget-area-delete" href="javascript:void(0);"><?php _e('Remove', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	
	<a class="button-primary" id="adamlabsgallery-widget-area-add" href="javascript:void(0);"><?php _e('Add New Widget Area', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
	
	
	<?php AdamLabsGallery_Dialogs::widget_areas_dialog(); ?>
	
	<script type="text/javascript">
		jQuery(function(){
			AdminEssentials.initWidgetAreas();
		});
	</script>