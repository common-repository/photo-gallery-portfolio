<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 */
 
if( !defined( 'ABSPATH') ) exit();

$grid = false;

$base = new AdamLabsGallery_Base();
$nav_skin = new AdamLabsGallery_Navigation();
$wa = new AdamLabsGallery_Widget_Areas();
$meta = new AdamLabsGallery_Meta();

$isCreate = $base->getGetVar('create', 'true');

$title = __('Create New Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN);
$save = __('Save Grid', ADAMLABS_GALLERY_TEXTDOMAIN);

$layers = false;
$showAddNewButton = false;

if(intval($isCreate) > 0){ //currently editing
	$grid = AdamLabsGallery::get_adamlabsgallery_by_id(intval($isCreate));
	if(!empty($grid)){
		$title = __('Editing', ADAMLABS_GALLERY_TEXTDOMAIN);
		$showAddNewButton = true;

		$layers = $grid['layers'];
	}
}

$postTypesWithCats = $base->getPostTypesWithCatsForClient();
$jsonTaxWithCats = $base->jsonEncodeForClientSide($postTypesWithCats);

$base = new AdamLabsGallery_Base();

$pages = get_pages(array('sort_column' => 'post_name'));

$post_elements = $base->getPostTypesAssoc();

$postTypes = $base->getVar($grid['postparams'], 'post_category', 'post');
$categories = $base->setCategoryByPostTypes($postTypes, $postTypesWithCats);

$selected_pages = explode(',', $base->getVar($grid['postparams'], 'selected_pages', '-1', 's'));

$columns = $base->getVar($grid['params'], 'columns', '');
$columns = $base->set_basic_colums($columns);

$mascontent_height = $base->getVar($grid['params'], 'mascontent-height', '');
$mascontent_height = $base->set_basic_mascontent_height($mascontent_height);


$columns_width = $base->getVar($grid['params'], 'columns-width', '');
$columns_width = $base->set_basic_colums_width($columns_width);

$columns_height = $base->getVar($grid['params'], 'columns-height', '');
$columns_height = $base->set_basic_colums_height($columns_height);

$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-0', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-1', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-2', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-3', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-4', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-5', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-6', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-7', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-8', '');
$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-9', '');

$nav_skin_choosen = $base->getVar($grid['params'], 'navigation-skin', 'minimal-light');
$navigation_skins = $nav_skin->get_adamlabsgallery_navigation_skins();
$navigation_skin_css = $base->jsonEncodeForClientSide($navigation_skins);

$entry_skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
$entry_skin_choosen = $base->getVar($grid['params'], 'entry-skin', '0');

$grid_animations = $base->get_grid_animations();
$start_animations = $base->get_start_animations();
$grid_item_animations = $base->get_grid_item_animations();
$hover_animations = $base->get_hover_animations();
$grid_animation_choosen = $base->getVar($grid['params'], 'grid-animation', 'fade');
$grid_start_animation_choosen = $base->getVar($grid['params'], 'grid-start-animation', 'reveal');
$grid_item_animation_choosen = $base->getVar($grid['params'], 'grid-item-animation', 'none');
$grid_item_animation_other = $base->getVar($grid['params'], 'grid-item-animation-other', 'none');
$hover_animation_choosen = $base->getVar($grid['params'], 'hover-animation', 'fade');

if(intval($isCreate) > 0) //currently editing, so default can be empty
	$media_source_order = $base->getVar($grid['postparams'], 'media-source-order', '');
else
	$media_source_order = $base->getVar($grid['postparams'], 'media-source-order', array('featured-image'));

$media_source_list = $base->get_media_source_order();

$custom_elements = $base->get_custom_elements_for_javascript();

$all_image_sizes = $base->get_all_image_sizes(); 
$all_media_filters = $base->get_all_media_filters(); 

$meta_keys = $meta->get_all_meta_handle();

// INIT POSTER IMAGE SOURCE ORDERS
if(intval($isCreate) > 0){ //currently editing, so default can be empty
	$poster_source_order = $base->getVar($grid['params'], 'poster-source-order', '');
	if($poster_source_order == ''){ //since 2.1.0
		$poster_source_order = $base->getVar($grid['postparams'], 'poster-source-order', '');
	}
}else{
	$poster_source_order = $base->getVar($grid['postparams'], 'poster-source-order', array('featured-image'));
}

$poster_source_list = $base->get_poster_source_order();

?>

<!--
LEFT SETTINGS
-->
<h2 class="topheader"><?php echo $title; ?></h2>

<?php if($showAddNewButton === true): ?>

<div>
    <a class="button-primary" href="<?php echo admin_url('admin.php?page=adamlabsgallery&view=grid-create&create=true'); ?>"><?php _e('Create New Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
</div>

<?php endif; ?>

<div style="width:100%;height:20px"></div>

<h2 class="adamlabs-section-title adamlabs-section-title-no-pad">Main Info</h2>

<?php require __DIR__ . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'general-info.php'; ?>

<div class="grid-form-wrap">

    <?php require __DIR__ . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'add-item.php'; ?>

    <div class="adamlabsgallery-form-preview-wrap-transparent">
        <h2 class="adamlabs-section-title"><?php _e('Editor / Preview', ADAMLABS_GALLERY_TEXTDOMAIN); ?></h2><!--div id="build_thumbnail" class="button-primary">Create Thumbnail</div-->
        <form id="adamlabsgallery-custom-elements-form-wrap">
            <div id="adamlabsgallery-live-preview-wrap">
                <?php
                wp_enqueue_script($this->plugin_slug . '-adamlabsgallery-script', ADAMLABS_GALLERY_PLUGIN_URL.'com/public/assets/js/jquery.adamlabs.adamlabsgallery.min.js', array('jquery'), AdamLabsGallery::VERSION );

                AdamLabsGallery_Global_Css::output_global_css_styles_wrapped();
                ?>
                <div id="adamlabsgallery-preview-wrapping-wrapper">
                    <?php
                    if($base->getVar($grid['postparams'], 'source-type', 'post') == 'custom'){
                        $layers = @$grid['layers']; //no stripslashes used here

                        if(!empty($layers)){
                            foreach($layers as $layer){
                                ?>
                                <input class="adamlabsgallery-remove-on-reload" type="hidden" name="layers[]" value="<?php echo htmlentities($layer); ?>" />
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>

    <div class="grid-form-wrap-right">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'source.php'; ?>

        <div id="adamlabsgallery-layout-composition" class="postbox adamlabsgallery-postbox">
            <h3>
                <span><?php _e('Layout', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                <div class="postbox-arrow"></div>
            </h3>
            <div class="inside" style="padding:0px !important;margin:0px !important;height:100%;position:relative;background:#e1e1e1">

                <!--
                MENU
                -->
                <div id="adamlabsgallery-create-settings-menu">
                    <ul>
                        <li style="width:150px; background:#E1e1e1;position:absolute;height:100%;top:0px;left:0px;box-sizing:border-box;
				-moz-box-sizing:border-box;
				-webkit-box-sizing:border-box;
				"></li>
                        <li class="selected-adamlabsgallery-setting" data-toshow="adamlabsgallery-settings-skins-settings"><i class="adamlabsgallery-icon-droplet"></i><span><?php _e('Templates', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-grid-settings"><i class="adamlabsgallery-icon-menu"></i><span><?php _e('Grid Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red !important"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-filterandco-settings"><i class="adamlabsgallery-icon-shuffle"></i><span><?php _e('Nav-Filter-Sort', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red !important"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-animations-settings"><i class="adamlabsgallery-icon-tools"></i><span><?php _e('Animations', ADAMLABS_GALLERY_TEXTDOMAIN); ?><?php if($GLOBALS['adamlabsgallery_validated'] === 'false'): ?><span style="color:red !important"> (<?php _e('Pro', ADAMLABS_GALLERY_TEXTDOMAIN); ?>)</span><?php endif; ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-lightbox-settings"><i class="adamlabsgallery-icon-search"></i><span><?php _e('Lightbox', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-ajax-settings"><i class="adamlabsgallery-icon-ccw-1"></i><span><?php _e('Ajax', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-spinner-settings"><i class="adamlabsgallery-icon-back-in-time"></i><span><?php _e('Spinner', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-api-settings"><i class="adamlabsgallery-icon-magic"></i><span><?php _e('API/JavaScript', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <li class="" data-toshow="adamlabsgallery-settings-cookie-settings"><i class="adamlabsgallery-icon-eye"></i><span><?php _e('Cookies', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></li>
                        <div class="clear"></div>
                    </ul>
                </div>

                <!--
                SOURCE
                -->

                <?php
                require_once('elements/grid-settings.php');
                ?>
            </div>
        </div>
    </div>
</div>

<div class="save-wrap-settings" style="">
    <!--	<div style="width:150px; background:#E1e1e1;position:absolute;height:100%;top:0px;left:0px;"></div>-->
    <div class="sws-toolbar-button"><a class="button-primary" href="javascript:void(0);" id="adamlabsgallery-btn-save-grid"><i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;margin-right:10px !important;margin-left:2px !important;"></i><?php echo $save; ?></a></div>
    <div class="sws-toolbar-button"><a class="button-primary adamlabsgallery-refresh-preview-button"><i class="adamlabsgallery-icon-arrows-ccw"></i><?php _e('Refresh Preview', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
    <div class="sws-toolbar-button"><a class="button-primary" href="<?php echo self::getViewUrl(AdamLabsGallery_Admin::VIEW_OVERVIEW); ?>"><i class="adamlabsgallery-icon-cancel"></i><?php _e('Close', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div>
    <!-- <div class="sws-toolbar-button"><a class="button-primary revpurple" id="createthumbnail" href="#"><i class="adamlabsgallery-icon-picture-1"></i><?php _e('Create Thumb', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a></div> -->
    <div class="sws-toolbar-button"><?php if($grid !== false){ ?>
            <a class="button-primary revred" href="javascript:void(0);" id="adamlabsgallery-btn-delete-grid"><i class="adamlabsgallery-icon-trash"></i><?php _e('Delete Grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
        <?php } ?></div>
</div>
<script>
    jQuery('document').ready(function() {
        adamlabsgallerygs.TweenLite.fromTo(jQuery('.save-wrap-settings'),0.5,{autoAlpha:0,x:40},{autoAlpha:1,x:0,ease:adamlabsgallerygs.Power3.easeInOut,delay:3});
        jQuery.each(jQuery('.sws-toolbar-button'),function(ind,elem) {
            adamlabsgallerygs.TweenLite.fromTo(elem,0.5,{x:40},{x:0,ease:adamlabsgallerygs.Power3.easeInOut,delay:0.5+(ind*0.3)});
        })

        jQuery('.sws-toolbar-button').hover(function() {
                adamlabsgallerygs.TweenLite.to(jQuery(this),0.3,{x:-110,ease:adamlabsgallerygs.Power3.easeInOut});
            },
            function() {
                adamlabsgallerygs.TweenLite.to(jQuery(this),0.3,{x:0,ease:adamlabsgallerygs.Power3.easeInOut});
            })
        /*
        jQuery('#createthumbnail').click(function() {
            AdminEssentials.buildThumbnail();
        });
        */
    });
</script>

<div class="clear"></div>


<?php
AdamLabsGallery_Dialogs::post_meta_dialog(); //to change post meta informations
AdamLabsGallery_Dialogs::edit_custom_element_dialog(); //to change post meta informations
AdamLabsGallery_Dialogs::custom_element_image_dialog(); //to change post meta informations

?>
<script type="text/javascript">
	var adamlabsgallery_jsonTaxWithCats = <?php echo $jsonTaxWithCats; ?>;
	var pages = [
		<?php
		if(!empty($pages)){
			$first = true;
			foreach($pages as $page){
				echo (!$first) ? ",\n" : "\n";
				echo '{ value: '.$page->ID.', label: "'.str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')" }';
				$first = false;
			}
		}
		?>
	];


	jQuery(document).ready(function(){
		
		AdminEssentials.setInitMetaKeysJson(<?php echo $base->jsonEncodeForClientSide($meta_keys); ?>);
		
		AdminEssentials.initCreateGrid(<?php echo ($grid !== false) ? '"update_grid"' : ''; ?>);

		AdminEssentials.set_default_nav_skin(<?php echo $navigation_skin_css; ?>);

		AdminEssentials.initAccordion('adamlabsgallery-create-settings-general-tab');

		AdminEssentials.initSlider();

		AdminEssentials.initAutocomplete();

		AdminEssentials.initTabSizes();

		AdminEssentials.set_navigation_layout();
		
		setTimeout(function() {
			AdminEssentials.createPreviewGrid();
		},500);


		AdminEssentials.initSpinnerAdmin();
		
		AdminEssentials.setInitCustomJson(<?php echo $base->jsonEncodeForClientSide($custom_elements); ?>);
		
	});
</script>

<?php

echo '<div id="navigation-styling-css-wrapper">'."\n";
$skins = AdamLabsGallery_Navigation::output_navigation_skins();
echo $skins;
echo '</div>';

?>

<div id="adamlabsgallery-template-wrapper" style="display: none;">

</div>
