<?php
/**
 * Plugin Name:       Photo Gallery Portfolio
 * Plugin URI:        https://pluginjungle.com/downloads/photo-portfolio-gallery/
 * Description:       Add professional touch to your website with Photo Portfolio gallery! A powerful mixture of multiple plugins.
 * Version:           1.1.0
 * Author:            Portfolio Gallery Team
 * Author URI:        http://adamlabs.net
 * Text Domain:       adamlabs_gallery * Domain Path:       /languages
 */
 
 
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if(class_exists('AdamLabs_Gallery')) {
	die('ERROR: It looks like you have more than one instance of AdamLabs Gallery installed. Please remove additional instances for this plugin to work again.');
}

define( 'ADAMLABS_GALLERY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'ADAMLABS_GALLERY_PLUGIN_URL', str_replace('index.php','',plugins_url( 'index.php', __FILE__ )));
//used to determinate if already done for cart button on this skin
define( 'ADAMLABS_GALLERY_TEXTDOMAIN', 'adamlabsgallery');

$wc_is_localized = false; //used to determinate if already done for cart button on this skin


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

 /* 2.1.6 */
require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-colorpicker.php');
 
require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-base.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/class-adamlabsgallery.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-global-css.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-navigation.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-grids-widget.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-item-skin.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-item-element.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-wpml.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-woocommerce.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-meta.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-fonts.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-search.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-aq-resizer.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-jackbox.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-social-gallery.php');

require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-external-sources.php');
require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/core/class-wordpress-update-fix.php');

$adamlabsgallery_rsl			= (isset($_GET['adamlabsgallery_refresh'])) ? true : false;

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array('AdamLabsGallery', 'deactivate_license' ));
register_activation_hook( __FILE__, array('AdamLabsGallery', 'create_tables' ));
register_activation_hook( __FILE__, array('AdamLabsGallery_Item_Skin', 'propagate_default_item_skins' ));
register_activation_hook( __FILE__, array('AdamLabsGallery_Navigation', 'propagate_default_navigation_skins' ));
register_activation_hook( __FILE__, array('AdamLabsGallery_Global_Css', 'propagate_default_global_css' ));
register_activation_hook( __FILE__, array('AdamLabs_Fonts', 'propagate_default_fonts' ));
register_activation_hook( __FILE__, array('AdamLabsGallery', 'activation_hooks' ));
register_activation_hook( __FILE__, array('AdamLabsGallery', 'propagate_default_grids' ));

//register_deactivation_hook( __FILE__, array('AdamLabsGallery', 'deactivate' ));

add_action('plugins_loaded', array('AdamLabsGallery', 'get_instance'));

add_filter('the_content', array('AdamLabsGallery', 'fix_shortcodes'));

add_shortcode('adamlabsgallery', array('AdamLabsGallery', 'register_shortcode'));
add_shortcode('adamlabsgallery_ajax_target', array('AdamLabsGallery', 'register_shortcode_ajax_target'));
add_shortcode('adamlabsgallery_nav', array('AdamLabsGallery', 'register_shortcode_filter'));
add_shortcode('adamlabsgallery_search', array('AdamLabsGallery_Search', 'register_shortcode_search'));

add_action('widgets_init', array('AdamLabsGallery', 'register_custom_sidebars'));
add_action('widgets_init', array('AdamLabsGallery', 'register_custom_widget'));

// Featured Grid
add_filter( 'post_thumbnail_html', array('AdamLabsGallery','post_thumbnail_replace'), 20, 5);

/*----------------------------------------------------------------------------*
 * FrontEnd Special Functionality
 *----------------------------------------------------------------------------*/
if(!is_admin()){
	/**
	 * initialize grid search
	 */
	$adamlabsgallery_search = new AdamLabsGallery_Search();
	
	/**
	 * load VC components in FrontEnd Editor of VC
	 */
	add_action( 'vc_before_init', 'AdamLabsGalleryCheckVc' );
	function AdamLabsGalleryCheckVc() {
		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			require_once( ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/adamlabsgallery-admin.class.php' );
			AdamLabsGallery_Admin::add_to_VC();
		}
	}
}


/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
if(is_admin()){ // && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX )

	add_action('plugins_loaded', array('AdamLabsGallery', 'create_tables' ));
	
	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/adamlabsgallery-admin.class.php');

	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/dialogs.class.php');
	
	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/import.class.php');
	
	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/export.class.php');
	
	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/import-post.class.php');
	
	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/plugin-update.class.php');
	

	// require_once(ADAMLABS_GALLERY_PLUGIN_PATH . 'admin/includes/addon-admin.class.php');

	require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/library.class.php');
	
	add_action('plugins_loaded', array( 'AdamLabsGallery_Admin', 'do_update_checks' )); //add update checks
	
	add_action('plugins_loaded', array( 'AdamLabsGallery_Admin', 'get_instance' ));
	
	add_action('plugins_loaded', array( 'AdamLabsGallery_Admin', 'visual_composer_include' )); //VC functionality
	//add_action('init', array('AdamLabsGallery_Admin', 'visual_composer_include')); //VC functionality
	
}


/*add_action('shutdown', 'adamlabsgallery_debug');

function adamlabsgallery_debug(){
	global $wpdb;
	echo "<pre>";
	print_r($wpdb->queries);
	echo "</pre>";
}
*/
//debug memory usage
//require_once(ADAMLABS_GALLERY_PLUGIN_PATH . '/com/admin/includes/debug.class.php');
