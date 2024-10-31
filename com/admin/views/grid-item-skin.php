<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 */
 
if( !defined( 'ABSPATH') ) exit();

//force the js file to be included
wp_enqueue_script('adamlabsgallery-item-editor-script', ADAMLABS_GALLERY_PLUGIN_URL.'com/admin/assets/js/grid-editor.js', array('jquery'), AdamLabsGallery::VERSION );
	
?>
<h2 class="topheader"><?php _e('Grid Templates', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h2>

<a class='button-primary' href='<?php echo (($GLOBALS['adamlabsgallery_validated'] === 'true') ? $this->getViewUrl(AdamLabsGallery_Admin::VIEW_ITEM_SKIN_EDITOR, 'create=true'): '#'); ?>'><?php _e('Create New Item Template', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red !important"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></a>
<?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?>
    <a href="https://pluginjungle.com/downloads/photo-portfolio-gallery/" class="button-primary adamlabsgallery-pro-license-button" target="_blank">Get Pro License for only $19.99</a>
<?php endif; ?>

<div id="adamlabsgallery-grid-even-item-skin-wrapper">
	
	<?php
	$skins_c = new AdamLabsGallery_Item_Skin();
	$navigation_c = new AdamLabsGallery_Navigation();
	$grid_c = new AdamLabsGallery();
	
	$grid['id'] = '1';
	$grid['name'] = __('Overview', ADAMLABS_GALLERY_TEXTDOMAIN);
	$grid['handle'] = 'overview';
	$grid['postparams'] = array();
	$grid['layers'] = array();
	$grid['params'] = array('layout' => 'masonry',
							'navigation-skin' => 'backend-flat',
							'filter-arrows' => 'single',
							'navigation-padding' => '0 0 0 0',
							'force_full_width' => 'off',
							'rows-unlimited' => 'off',
							'rows' => 3,
							'columns' => array(4,3,3,2,2,2,1),
							'columns-width' => array(1400,1170,1024,960,778,640,480),
							'spacings' => 15,
							'grid-animation' => 'fade',
							'grid-animation-speed' => 800,
							'grid-animation-delay' => 5,
							'grid-start-animation' => 'reveal',
							'grid-start-animation-speed' => '800',
							'grid-start-animation-delay' => 0,
							'grid-start-animation-type' => 'item',
							'grid-animation-type' => 'item',
							'x-ratio' => 4,
							'y-ratio' => 3,
						   );
	
	$skins_html = '';
	$skins_css = '';
	$filters = array();


	$skins = $skins_c->get_adamlabsgallery_item_skins();
	
	$demo_img = array();
	for($i=1; $i<=4; $i++){
		$demo_img[] = 'demo_template_'.$i.'.jpg';
	}
	
	if(!empty($skins) && is_array($skins)){
		$src = array();
		
		foreach($skins as $skin){
			
			// 2.2.6
			if(is_array($skin) && array_key_exists('handle', $skin) && $skin['handle'] === 'adamlabsgalleryblankskin') continue;
			
			if(empty($src)) $src = $demo_img;
				
			$item_skin = new AdamLabsGallery_Item_Skin();
			$item_skin->init_by_data($skin);
			
			//set filters
			$item_skin->set_demo_filter();
			
			//add skin specific css
			$item_skin->register_skin_css();
			
			//set demo image
			$img_key = array_rand($src);
			$item_skin->set_image($src[$img_key]);
			unset($src[$img_key]);
			
			$item_filter = $item_skin->get_filter_array();
			
			$filters = array_merge($item_filter, $filters);
			
			ob_start();
			$item_skin->output_item_skin('overview');
			$skins_html.= ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			ob_start();
			$item_skin->generate_element_css('overview');
			$skins_css.= ob_get_contents();
			ob_clean();
			ob_end_clean();
		}
	}
	
	$grid_c->init_by_data($grid);
	?>
	<div class="postbox adamlabsgallery-postbox adamlabsgallery-transbackground" >
		<h3><span class="adamlabsgallery-element-setter"><?php _e('Templates', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
		<div class="inside" style="margin:0; padding:0;">
			<?php 
			
			$grid_c->output_wrapper_pre();
			
			$filters = array_map("unserialize", array_unique(array_map("serialize", $filters))); //filter to unique elements
			
			$navigation_c->set_special_class('adamlabsgallery-fgc-'.$grid['id']);
			$navigation_c->set_filter($filters);
			$navigation_c->set_style('padding', $grid['params']['navigation-padding']);
			echo $navigation_c->output_filter(true);
			
			$grid_c->output_grid_pre();

			//output elements
			echo $skins_html;

			$grid_c->output_grid_post();
			echo '<div style="text-align: center;">';
			echo $navigation_c->output_pagination(true);
			echo '</div>';
			
			$grid_c->output_wrapper_post();
			
			?>
		</div>
	</div>
	
	<?php
	$grid_c->output_grid_javascript(false, true);

	echo $skins_css;
	
	AdamLabsGallery_Global_Css::output_global_css_styles_wrapped();

	if(empty($skins)){
		_e('No Item Templates found!', ADAMLABS_GALLERY_TEXTDOMAIN);
	}
	?>
</div>

<script type="text/javascript">
	jQuery(function(){
		GridEditorEssentials.initOverviewItemSkin();
	});
</script>
