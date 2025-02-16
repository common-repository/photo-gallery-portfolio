<?php
/**
 * Portfolio Gallery.
 */

if( !defined( 'ABSPATH') ) exit();

$adamlabsgallery_c_sort_direction = 'ASC';
$adamlabsgallery_c_sort_handle = 'title';
$adamlabsgallery_grid_serial = 0;
$adamlabsgallery_is_inited = false;

class AdamLabsGallery {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     */
    const VERSION = '1.1.0';
    const TABLE_GRID = 'adamlabsgallery_grids';
    const TABLE_ITEM_SKIN = 'adamlabsgallery_item_skins';
    const TABLE_ITEM_ELEMENTS = 'adamlabsgallery_item_elements';
    const TABLE_NAVIGATION_SKINS = 'adamlabsgallery_navigation_skins';

    private $grid_api_name = null;
    private $grid_div_name = null;
    private $grid_id = 0; //set to 0 at beginning for quick grids @since 2.0.2
    private $grid_name = null;
    private $grid_handle = null;
    private $grid_params = array();
    private $grid_postparams = array();
    private $grid_layers = array();
    private $grid_settings = array();
    private $grid_last_mod = '';
    private $grid_inline_js = '';

    public $custom_settings = null;
    public $custom_layers = null;
    public $custom_images = null;
    public $custom_posts = null;
    public $custom_special = null;

    //other changings
    private $filter_by_ids = array();
    private $load_more_post_array = array();

    /**
     * Unique identifier for the plugin.
     * The variable name is used as the text domain when internationalizing strings of text.
     */
    protected $plugin_slug = 'adamlabsgallery';


    /**
     * Instance of this class.
     */
    protected static $instance = null;


    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     */
    public function __construct() {
        global $adamlabsgallery_is_inited;

        if(!$adamlabsgallery_is_inited){

            $adamlabsgallery_is_inited = true;

            // Load plugin text domain
            add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

            $add_cpt = apply_filters('adamlabsgallery_set_cpt', get_option('adamlabsgallery_enable_custom_post_type', 'false'));

            if($add_cpt == 'true' || $add_cpt === true)
                add_action( 'init', array( $this, 'register_custom_post_type' ) );

            // Load public-facing style sheet and JavaScript.
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_action('wp_ajax_adamlabsgallery_front_request_ajax', array($this, 'on_front_ajax_action'));
            add_action('wp_ajax_nopriv_adamlabsgallery_front_request_ajax', array($this, 'on_front_ajax_action')); //for not logged in users

            // Post Like
            add_action('wp_ajax_nopriv_adamlabsgallery_post_like', array($this,'adamlabsgallery_post_like'));
            add_action('wp_ajax_adamlabsgallery_post_like', array($this,'adamlabsgallery_post_like'));

            //Gallery
            $gallery = get_option('adamlabsgallery_overwrite_gallery','');
            if( !empty($gallery) && $gallery != "off"  ){
                add_action('init', array($this, 'remove_wp_gallery'));
                add_action('init', array($this,'add_adamlabsgallery_gallery'));
            }
            add_filter('post_gallery', array($this,'use_adamlabsgallery_gallery'), 10, 2);

            //Woo Add to Cart Updater
            add_filter('woocommerce_add_to_cart_fragments', array('AdamLabsGallery_Woocommerce','woocommerce_header_add_to_cart_fragment'));

            // 2.2 lightbox post content
            add_filter('adamlabsgallery_lightbox_post_content', array($this, 'on_lightbox_post_content'), 10, 2);

        }
    }


    /**
     * Return the plugin slug.
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }


    /**
     * Return an instance of this class.
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasnt been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

        load_plugin_textdomain( $domain, FALSE, dirname(dirname(plugin_basename( __FILE__ ))) . '/languages/' );
        //load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

        do_action('adamlabsgallery_load_plugin_textdomain', $domain);
    }


    /**
     * Register and enqueue public-facing style sheet.
     */
    public function enqueue_styles() {

        $use_cache = (get_option('adamlabsgallery_use_cache', 'false') == 'true') ? true : false;
        wp_register_style($this->plugin_slug . '-plugin-settings', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/css/settings.css', array(), self::VERSION);
        wp_enqueue_style( $this->plugin_slug .'-plugin-settings' );

        $font = new AdamLabs_Fonts();
        $font->register_fonts();
        $font->register_icon_fonts("public");

        wp_register_style('adamlabsboxextcss', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/css/jquery.adamlabsgallerybox.min.css', array(), self::VERSION);

        // Enqueue Lightbox Style/Script
        if($use_cache){
            wp_enqueue_style('adamlabsboxextcss');
        }

        do_action('adamlabsgallery_enqueue_styles', $use_cache, self::VERSION);

    }


    /**
     * Register and enqueues public-facing JavaScript files.
     */
    public function enqueue_scripts() {
        $use_cache = (get_option('adamlabsgallery_use_cache', 'false') == 'true') ? true : false;
        $js_to_footer = (get_option('adamlabsgallery_js_to_footer', 'false') == 'true') ? true : false;
        $enable_log = (get_option('adamlabsgallery_enable_log', 'false') == 'true') ? true : false;

        wp_enqueue_script( 'jquery' );
        //wp_register_script( 'adamlabsboxext', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/js/lightbox.js', array('jquery'), self::VERSION, $js_to_footer);
        $waitfor = array( 'jquery' );

        if(get_option('adamlabsgallery_use_lightbox') !== 'disabled') {
            wp_register_script( 'adamlabsboxext', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/js/jquery.adamlabsgallerybox.min.js', array('jquery'), self::VERSION, $js_to_footer);
            $waitfor[] = 'adamlabsboxext';
        }

        if($enable_log) wp_enqueue_script( 'enable-logs', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/js/jquery.adamlabs.enablelog.js', $waitfor, self::VERSION, $js_to_footer );

        wp_register_script( 'adamlabs-tools', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/js/jquery.adamlabs.tools.min.js', $waitfor, self::VERSION, $js_to_footer );
        wp_register_script( $this->plugin_slug . '-adamlabsgallery-script', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/js/jquery.adamlabs.adamlabsgallery.min.js', array( 'jquery', 'adamlabs-tools' ), self::VERSION, $js_to_footer );

        do_action('adamlabsgallery_enqueue_scripts', $use_cache, self::VERSION, $js_to_footer);
    }


    /**
     * Register Shortcode
     */
    public static function register_shortcode($args, $mid_content=null){
        //$dbg = new AdamLabsGalleryMemoryUsageInformation();
        //$dbg->setStart();
        //$dbg->setMemoryUsage('Before ShortCode');

        $args = apply_filters('adamlabsgallery_register_shortcode_pre', $args);

        $caching = get_option('adamlabsgallery_use_cache', 'false');
        $use_cache = $caching == 'true' ? true : false;

        // Enqueue Scripts
        wp_enqueue_script( 'adamlabs-tools' );
        wp_enqueue_script( 'adamlabsgallery-adamlabsgallery-script' );
        wp_localize_script('adamlabsgallery-adamlabsgallery-script', 'adamlabsgallery_ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('adamlabsgallery-ajax-nonce')
        ));

        // Enqueue Lightbox Style/Script
        if($use_cache){
            wp_enqueue_script( 'adamlabsboxext' );
        }

        $grid = new AdamLabsGallery;
        extract(shortcode_atts(array('alias' => '', 'settings' => '', 'layers' => '', 'images' => '', 'posts' => '', 'special' => ''), $args, 'adamlabsgallery'));
        $eg_alias = ($alias != '') ? $alias : implode(' ', $args);

        if($settings !== '') $grid->custom_settings = json_decode(str_replace(array('({', '})', "'"), array('[', ']', '"'), $settings) ,true);
        if($layers !== '') $grid->custom_layers = json_decode(str_replace(array('({', '})', "'"), array('[', ']', '"'), $layers),true);
        if($images !== '') $grid->custom_images = explode(',', $images);
        if($posts !== '') $grid->custom_posts = explode(',', $posts);
        if($special !== '') $grid->custom_special = $special;

        if($settings !== '' || $layers !== '' || $images !== '' || $posts !== '' || $special !== ''){ //disable caching if one of this is set
            $caching = 'false';
        }

        $grid->check_for_shortcodes($mid_content); //check for example on gallery shortcode and do stuff

        if($eg_alias == '')
            $eg_alias = implode(' ', $args);

        $content = false;
        $grid_id = self::get_id_by_alias($eg_alias);

        if($grid_id == '0'){ //grid is created by custom settings. Check if layers and settings are set
            ob_start();
            $grid->output_adamlabsgallery_by_settings();
            $content = ob_get_contents();
            ob_clean();
            ob_end_clean();
        }else{

            if($caching == 'true'){ //check if we use total caching
                //add wpml transient
                $lang_code = '';
                if(AdamLabsGallery_Wpml::is_wpml_exists()){
                    $lang_code = AdamLabsGallery_Wpml::get_current_lang_code();
                }

                $content = get_transient( 'adamlabsgallery_trans_full_grid_'.$grid_id.$lang_code );
            }

            if($content == false){
                ob_start();
                $grid->output_adamlabsgallery_by_alias($eg_alias);
                $content = ob_get_contents();
                ob_clean();
                ob_end_clean();

                if($caching == 'true'){
                    set_transient( 'adamlabsgallery_trans_full_grid_'.$grid_id.$lang_code, $content, 60*60*24*7 );
                }
            }

        }

        $output_protection = get_option('adamlabsgallery_output_protection', 'none');

        //$dbg->setMemoryUsage('After ShortCode');
        //$dbg->setEnd();
        //$dbg->printMemoryUsageInformation();

        //handle output types
        switch($output_protection){
            case 'compress':
                $content = str_replace("\n", '', $content);
                $content = str_replace("\r", '', $content);
                return($content);
                break;
            case 'echo':
                echo $content;		//bypass the filters
                break;
            default: //normal output
                return($content);
                break;
        }

    }


    /**
     * Register Shortcode For Ajax Content
     * @since: 1.5.0
     */
    public static function register_shortcode_ajax_target($args, $mid_content=null){
        $args = apply_filters('adamlabsgallery_register_shortcode_ajax_target_pre', $args);

        extract(shortcode_atts(array('alias' => ''), $args, 'adamlabsgallery_ajax_target'));

        if($alias == '') return false; //no alias found

        $output_protection = get_option('adamlabsgallery_output_protection', 'none');

        $content = '';

        $grid = new AdamLabsGallery;

        $grid_id = self::get_id_by_alias($alias);
        if($grid_id > 0){

            $grid->init_by_id($grid_id);
            //check if shortcode is allowed

            $is_sc_allowed = $grid->get_param_by_handle('ajax-container-position');
            if($is_sc_allowed != 'shortcode') return false;

            $content = $grid->output_ajax_container();

        }

        //handle output types
        switch($output_protection){
            case 'compress':
                $content = str_replace("\n", '', $content);
                $content = str_replace("\r", '', $content);
                return($content);
                break;
            case 'echo':
                echo $content;		//bypass the filters
                break;
            default: //normal output
                return($content);
                break;
        }

    }


    /**
     * Register Shortcode For Filter
     * @since: 1.5.0
     */
    public static function register_shortcode_filter($args, $mid_content=null){
        $args = apply_filters('adamlabsgallery_register_shortcode_filter_pre', $args);

        extract(shortcode_atts(array('alias' => '', 'id' => ''), $args, 'adamlabsgallery_nav'));

        if($alias == '') return false; //no alias found
        if($id == '') return false; //no alias found
        $base = new AdamLabsGallery_Base();
        $meta_c = new AdamLabsGallery_Meta();
        $meta_link_c = new AdamLabsGallery_Meta_Linking();

        $output_protection = get_option('adamlabsgallery_output_protection', 'none');

        $content = '';

        ob_start();

        $grid = new AdamLabsGallery;

        $grid_id = self::get_id_by_alias($alias);

        if($grid_id > 0){
            $navigation_c = new AdamLabsGallery_Navigation($grid_id);

            $grid->init_by_id($grid_id);

            $layout = $grid->get_param_by_handle('navigation-layout', array());
            $navig_special_class = $grid->get_param_by_handle('navigation-special-class', array()); //has all classes in an ordered list
            $navig_special_skin = $grid->get_param_by_handle('navigation-special-skin', array()); //has all classes in an ordered list

            $special_class = '';
            $special_skin = '';

            if($id == 'sort') $id = 'sorting';

            //Check if selected element is in external list and also get the key to use it to get class
            if(isset($layout[$id]) && isset($layout[$id]['external'])){
                $special_class = @$navig_special_class[$layout[$id]['external']];
                $special_skin = @$navig_special_skin[$layout[$id]['external']];
            }else{ //its not in external set so break since its only allowed to use each element one time
                return false;
            }

            $navigation_c->set_special_class($special_class);
            $navigation_c->set_special_class($special_skin);
            $navigation_c->set_special_class('adamlabsgallery-fgc-'.$grid_id);

            $filter = false;
            switch($id){
                case 'sorting':
                    $order_by_start = $grid->get_param_by_handle('sorting-order-by-start', 'none');
                    $sort_by_text = $grid->get_param_by_handle('sort-by-text', __('Sort By ', ADAMLABS_GALLERY_TEXTDOMAIN));
                    $order_by = explode(',', $grid->get_param_by_handle('sorting-order-by', 'date'));
                    if(!is_array($order_by)) $order_by = array($order_by);
                    //set order of filter
                    $navigation_c->set_orders_text($sort_by_text);
                    $navigation_c->set_orders_start($order_by_start);
                    $navigation_c->set_orders($order_by);

                    /* 2.1.6 */
                    echo $navigation_c->output_sorting();
                    break;
                case 'cart':
                    /* 2.1.6 */
                    echo $navigation_c->output_cart();
                    break;
                case 'left':
                    /* 2.1.6 */
                    echo $navigation_c->output_navigation_left();
                    break;
                case 'right':
                    /* 2.1.6 */
                    echo $navigation_c->output_navigation_right();
                    break;
                case 'pagination':
                    /* 2.1.6 */
                    echo $navigation_c->output_pagination();
                    break;
                case 'search-input':
                    $search_text = $grid->get_param_by_handle('search-text', __('Search...', ADAMLABS_GALLERY_TEXTDOMAIN));
                    $navigation_c->set_search_text($search_text);

                    /* 2.1.6 */
                    echo $navigation_c->output_search_input();
                    break;
                case 'filter':
                    $id = 1;
                    $filter = true;
                    break;
                default:
                    //check for filter
                    if(strpos($id, 'filter-') !== false){
                        $id = intval(str_replace('filter-', '', $id));
                        $filter = true;
                    }else{
                        return false;
                    }
                    break;
            }

            /*****
             * Complex Filter Part
             *****/
            $found_filter = array();

            if($filter === true){
                switch($grid->get_postparam_by_handle('source-type')){
                    case 'custom':

                        if(!empty($grid->grid_layers) && count($grid->grid_layers) > 0){
                            foreach($grid->grid_layers as $key => $entry){

                                $filters = array();

                                if(!empty($entry['custom-filter'])){
                                    $cats = explode(',', $entry['custom-filter']);
                                    if(!is_array($cats)) $cats = (array)$cats;
                                    foreach($cats as $category){
                                        $filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
                                    }
                                }

                                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                            }
                        }
                        break;
                    case 'post':
                        $start_sortby = $grid->get_param_by_handle('sorting-order-by-start', 'none');
                        $start_sortby_type = $grid->get_param_by_handle('sorting-order-type', 'ASC');
                        $post_category = $grid->get_postparam_by_handle('post_category');
                        $post_types = $grid->get_postparam_by_handle('post_types');
                        $page_ids = explode(',',  $grid->get_postparam_by_handle('selected_pages', '-1'));

                        $cat_relation = $grid->get_postparam_by_handle('category-relation',  'OR');

                        $max_entries = $grid->get_maximum_entries($grid);

                        $additional_query = $grid->get_postparam_by_handle('additional-query', '');
                        if($additional_query !== '')
                            $additional_query = wp_parse_args($additional_query);

                        $cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category);

                        $posts = AdamLabsGallery_Base::getPostsByCategory($grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, $max_entries, $additional_query, true, $cat_relation);

                        $nav_filters = array();

                        $taxes = array('post_tag');
                        if(!empty($cat_tax['tax']))
                            $taxes = explode(',', $cat_tax['tax']);

                        if(!empty($cat_tax['cats'])){
                            $cats = explode(',', $cat_tax['cats']);

                            foreach($cats as $key => $cid){
                                if(AdamLabsGallery_Wpml::is_wpml_exists() && isset($sitepress)){
                                    $new_id = icl_object_id($cid, 'category', true, $sitepress->get_default_language());
                                    $cat = get_category($new_id);
                                }else{
                                    $cat = get_category($cid);
                                }
                                if(is_object($cat)){
                                    $nav_filters[$cid] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug), 'parent' => $cat->category_parent);
                                }

                                foreach($taxes as $custom_tax){
                                    $term = get_term_by('id', $cid, $custom_tax);
                                    if(is_object($term)) $nav_filters[$cid] = array('name' => $term->name, 'slug' => sanitize_key($term->slug), 'parent' => $term->parent);
                                }
                            }

                            if(!empty($filters_meta)){
                                $nav_filters = $filters_meta + $nav_filters;
                            }
                            asort($nav_filters);
                        }

                        if($id == 1){
                            $filterall_visible = $grid->get_param_by_handle('filter-all-visible');
                            $all_text = $grid->get_param_by_handle('filter-all-text');
                            $listing_type = $grid->get_param_by_handle('filter-listing', 'list');
                            $listing_text = $grid->get_param_by_handle('filter-dropdown-text');
                            $show_count = $grid->get_param_by_handle('filter-counter', 'off');
                            $selected = $grid->get_param_by_handle('filter-selected', array());
                        }else{
                            $filterall_visible = $grid->get_param_by_handle('filter-all-visible-'.$id);
                            $all_text = $grid->get_param_by_handle('filter-all-text-'.$id);
                            $listing_type = $grid->get_param_by_handle('filter-listing-'.$id, 'list');
                            $listing_text = $grid->get_param_by_handle('filter-dropdown-text-'.$id);
                            $show_count = $grid->get_param_by_handle('filter-counter-'.$id, 'off');
                            $selected = $grid->get_param_by_handle('filter-selected-'.$id, array());
                        }
                        $filter_allow = $grid->get_param_by_handle('filter-arrows', 'single');
                        $filter_start = $grid->get_param_by_handle('filter-start', '');
                        $filter_grouping = $grid->get_param_by_handle('filter-grouping', 'false');

                        //check the selected and change metas to correct fields
                        $filters_arr['filter-grouping'] = $filter_grouping;
                        $filters_arr['filter-listing'] = $listing_type;
                        $filters_arr['filter-selected'] = $selected;

                        if(!empty($filters_arr['filter-selected'])){
                            if(!empty($posts) && count($posts) > 0){
                                foreach($filters_arr['filter-selected'] as $fk => $filter){
                                    if(strpos($filter, 'meta-') === 0){
                                        unset($filters_arr['filter-selected'][$fk]); //delete entry

                                        foreach($posts as $key => $post){
                                            $fil = str_replace('meta-', '', $filter);
                                            $post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'adamlabsgallery-'.$fil);
                                            $arr = json_decode($post_filter_meta, true);
                                            $cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
                                            //$cur_filter = explode(',', $post_filter_meta);
                                            $add_filter = array();
                                            if(!empty($cur_filter)){
                                                foreach($cur_filter as $k => $v){
                                                    if(trim($v) !== ''){
                                                        $add_filter[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
                                                        if(!empty($filters_arr['filter-selected'])){
                                                            $filter_found = false;
                                                            foreach($filters_arr['filter-selected'] as $fcheck){
                                                                if($fcheck == sanitize_key($v)){
                                                                    $filter_found = true;
                                                                    break;
                                                                }
                                                            }
                                                            if(!$filter_found){
                                                                $filters_arr['filter-selected'][] = sanitize_key($v); //add found meta
                                                            }
                                                        }else{
                                                            $filters_arr['filter-selected'][] = sanitize_key($v); //add found meta
                                                        }
                                                    }
                                                }
                                                if(!empty($add_filter)) $navigation_c->set_filter($add_filter);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if($all_text == '' || $listing_type == '' || $listing_text == '' || empty($filters_arr['filter-selected'])) return false;

                        $navigation_c->set_filter_settings('filter', $filters_arr);
                        $navigation_c->set_filter_text($all_text);
                        $navigation_c->set_filterall_visible($filterall_visible);
                        $navigation_c->set_dropdown_text($listing_text);
                        $navigation_c->set_show_count($show_count);
                        $navigation_c->set_filter_type($filter_allow);
                        $navigation_c->set_filter_start_select($filter_start);

                        if(!empty($posts) && count($posts) > 0){
                            foreach($posts as $key => $post){

                                //check if post should be visible or if its invisible on current grid settings
                                $is_visible = $grid->check_if_visible($post['ID'], $grid_id);
                                if($is_visible == false) continue; // continue if invisible

                                $filters = array();

                                //$categories = get_the_category($post['ID']);
                                $categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
                                //$tags = wp_get_post_terms($post['ID']);
                                $tags = get_the_tags($post['ID']);

                                if(!empty($categories)){
                                    foreach($categories as $key => $category){
                                        $filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug), 'parent' => $category->parent);
                                    }
                                }

                                if(!empty($tags)){
                                    foreach($tags as $key => $taxonomie){
                                        $filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug), 'parent' => '0');
                                    }
                                }

                                $filter_meta_selected = $grid->get_param_by_handle('filter-selected', array());
                                if(!empty($filter_meta_selected)){
                                    foreach($filter_meta_selected as $filter){
                                        if(strpos($filter, 'meta-') === 0){
                                            $fil = str_replace('meta-', '', $filter);
                                            $post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'adamlabsgallery-'.$fil);
                                            $arr = json_decode($post_filter_meta, true);
                                            $cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
                                            //$cur_filter = explode(',', $post_filter_meta);
                                            if(!empty($cur_filter)){
                                                foreach($cur_filter as $k => $v){
                                                    if(trim($v) !== '')
                                                        $filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
                                                }
                                            }
                                        }
                                    }
                                }

                                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                            }
                        }

                        $remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
                        if(!empty($remove_filter)){
                            foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
                                unset($found_filter[$key]);
                            }
                        }
                        break;
                }

                $navigation_c->set_filter($found_filter); //set filters $nav_filters $found_filter

                echo $navigation_c->output_filter_unwrapped();

            }

        }

        $content = ob_get_contents();
        ob_clean();
        ob_end_clean();

        //handle output types
        switch($output_protection){
            case 'compress':
                $content = str_replace("\n", '', $content);
                $content = str_replace("\r", '', $content);
                return($content);
                break;
            case 'echo':
                echo $content;		//bypass the filters
                break;
            default: //normal output
                return($content);
                break;
        }
    }


    /**
     * We check the content for gallery shortcode.
     * If existing, create Grid based on the images
     **/
    public function check_for_shortcodes($mid_content){
        $mid_content = apply_filters('adamlabsgallery_check_for_shortcodes', $mid_content);

        $base = new AdamLabsGallery_Base();

        $img = $base->get_all_gallery_images($mid_content);

        $this->custom_images = (empty($img)) ? null : $img;

    }


    public static function fix_shortcodes($content){
        $content = apply_filters('adamlabsgallery_fix_shortcodes_pre', $content);

        $columns = array("adamlabsgallery");
        $block = join("|",$columns);

        // opening tag
        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

        // closing tag
        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/","[/$2]",$rep);

        return apply_filters('adamlabsgallery_fix_shortcodes_post', $rep);
    }


    /**
     * Register Custom Post Type & Taxonomy
     */
    public function register_custom_post_type() {
        $postType = apply_filters('AdamLabsGallery_custom_post_type', 'AdamLabsGallery');
        $taxonomy = apply_filters('AdamLabsGallery_category', 'adamlabsgallery_category');

        $taxArgs = array();
        $taxArgs["hierarchical"] = true;
        $taxArgs["label"] = __("Categories", ADAMLABS_GALLERY_TEXTDOMAIN);
        $taxArgs["singular_label"] = __("Category", ADAMLABS_GALLERY_TEXTDOMAIN);
        $taxArgs["rewrite"] = true;
        $taxArgs["public"] = true;
        $taxArgs["show_admin_column"] = true;

        $postArgs = array();
        $postArgs["label"] = __("AdamLabs Gallery Posts", ADAMLABS_GALLERY_TEXTDOMAIN);
        $postArgs["singular_label"] = __("AdamLabs Gallery Post", ADAMLABS_GALLERY_TEXTDOMAIN);
        $postArgs["public"] = true;
        $postArgs["capability_type"] = "post";
        $postArgs["hierarchical"] = false;
        $postArgs["show_ui"] = true;
        $postArgs["show_in_menu"] = true;
        $postArgs["supports"] = array('title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt');
        $postArgs["show_in_admin_bar"] = false;
        $postArgs["taxonomies"] = array($taxonomy, 'post_tag');

        $postArgs["rewrite"] = array("slug"=>$postType,"with_front"=>true);

        $d = apply_filters('adamlabsgallery_register_custom_post_type', array('postArgs' => $postArgs, 'taxArgs' => $taxArgs));
        $postArgs = $d['postArgs'];
        $taxArgs = $d['taxArgs'];

        register_taxonomy($taxonomy,array($postType),$taxArgs);
        register_post_type($postType,$postArgs);

    }

    /**
     * Activate licensed version of the plugin
     */
    public static function activate_license()
    {
        update_option('adamlabsgallery_valid', 'true');
    }

    /**
     * Deactivate licensed version of the plugin
     */
    public static function deactivate_license()
    {
        delete_option('adamlabsgallery_valid');
    }

    /**
     * Create/Update Database Tables
     */
    public static function create_tables($networkwide = false){
        global $wpdb;

        if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site

            // $old_blog = $wpdb->blogid;

            // Get all blog ids and create tables
            $blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

            foreach($blogids as $blog_id){
                switch_to_blog($blog_id);
                self::_create_tables();

                restore_current_blog();
            }

            // switch_to_blog($old_blog); //go back to correct blog

        }else{  //no multisite, do normal installation

            self::_create_tables();

        }

    }


    /**
     * Create Tables, edited for multisite
     */
    public static function _create_tables(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        //Create/Update Grids Database
        $grid_ver = get_option("adamlabsgallery_grids_version", '0.99');

        if(version_compare($grid_ver, '1', '<')){

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $table_name = $wpdb->prefix . self::TABLE_GRID;
            $sql = "CREATE TABLE $table_name (
				  id mediumint(6) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(191) NOT NULL,
				  handle VARCHAR(191) NOT NULL,
				  postparams TEXT NOT NULL,
				  params TEXT NOT NULL,
				  layers MEDIUMTEXT NOT NULL,
				  settings TEXT NULL,
				  last_modified DATETIME,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  ) $charset_collate;";

            dbDelta($sql);

            $table_name = $wpdb->prefix . self::TABLE_ITEM_SKIN;
            $sql = "CREATE TABLE $table_name (
				  id mediumint(6) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(191) NOT NULL,
				  handle VARCHAR(191) NOT NULL,
				  params TEXT NOT NULL,
				  layers MEDIUMTEXT NOT NULL,
				  settings TEXT,
				  UNIQUE KEY id (id),
				  UNIQUE (name),
				  UNIQUE (handle)
				  ) $charset_collate;";

            dbDelta($sql);

            $table_name = $wpdb->prefix . self::TABLE_ITEM_ELEMENTS;
            $sql = "CREATE TABLE $table_name (
				  id mediumint(6) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(191) NOT NULL,
				  handle VARCHAR(191) NOT NULL,
				  settings MEDIUMTEXT NOT NULL,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  ) $charset_collate;";

            dbDelta($sql);

            $table_name = $wpdb->prefix . self::TABLE_NAVIGATION_SKINS;
            $sql = "CREATE TABLE $table_name (
				  id mediumint(6) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(191) NOT NULL,
				  handle VARCHAR(191) NOT NULL,
				  css MEDIUMTEXT NOT NULL,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  ) $charset_collate;";

            dbDelta($sql);

            update_option('adamlabsgallery_grids_version', '1');

            $grid_ver = '1';
        }

        //Change database on certain release? No Problem, use the following:

//		if(version_compare($grid_ver, '1.03', '<')){
//
//			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//
//			$table_name = ...;
//			$sql = "...";
//
//			dbDelta($sql);
//
//			update_option('adamlabsgallery_grids_version', '...');
//
//			$grid_ver = '...';
//		}

        do_action('adamlabsgallery__create_tables', $grid_ver);

    }


    /**
     * Register Custom Sidebars, created in Grids
     * @since 1.0.6
     */
    public static function register_custom_sidebars(){

        // Register custom Sidebars
        $sidebars = apply_filters('adamlabsgallery_register_custom_sidebars', get_option('adamlabsgallery-widget-areas', false));

        if(is_array($sidebars) && !empty($sidebars)){
            foreach($sidebars as $handle => $name){
                register_sidebar(
                    array (
                        'name'          => $name,
                        'id'            => 'adamlabsgallery-'.$handle,
                        'before_widget' => '',
                        'after_widget'  => ''
                    )
                );
            }
        }
    }

    /**
     * Register the Custom Widget for Portfolio Gallery
     **/
    public static function register_custom_widget(){
        register_widget( 'AdamLabsGallery_Widget' );
    }

    /**
     * Get all Grids in Database
     */
    public static function get_adamlabsgallery_grids($order = false){
        global $wpdb;

        $order_fav = false;
        $additional = '';
        if($order !== false && !empty($order)){
            $ordertype = key($order);
            $orderby = reset($order);
            if($ordertype != 'favorite'){
                $additional .= ' ORDER BY '.$ordertype.' '.$orderby;
            }else{
                $order_fav = true;
            }
        }

        $table_name = $wpdb->prefix . self::TABLE_GRID;
        $grids = $wpdb->get_results("SELECT * FROM $table_name".$additional);

        //check if we order by favorites here
        if($order_fav === true){
            $temp = array();
            $temp_not = array();
            foreach($grids as $grid){
                $settings = json_decode($grid->settings, true);
                if(!isset($settings['favorite']) || $settings['favorite'] == 'false'){
                    $temp_not[] = $grid;
                }else{
                    $temp[] = $grid;
                }
            }
            $grids = array();

            $grids = ($orderby == 'ASC') ? array_merge($temp, $temp_not) : array_merge($temp_not, $temp);
        }

        return apply_filters('adamlabsgallery_get_adamlabsgallery_grids', $grids);
    }


    /**
     * Get Grid by ID from Database
     */
    public static function get_adamlabsgallery_by_id($id = 0){
        global $wpdb;

        $id = intval($id);
        if($id == 0) return false;

        $table_name = $wpdb->prefix . self::TABLE_GRID;

        $grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

        if(!empty($grid)){
            $grid['postparams'] = @json_decode($grid['postparams'], true);
            $grid['params'] = @json_decode($grid['params'], true);
            $grid['layers'] = @json_decode($grid['layers'], true);
            $grid['settings'] = @json_decode($grid['settings'], true);
            $grid['last_modified'] = @$grid['last_modified'];
        }

        return apply_filters('adamlabsgallery_get_adamlabsgallery_by_id', $grid, $id);
    }


    /**
     * get array of id -> title
     */
    public static function get_grids_short($exceptID = null){
        $arrGrids = self::get_adamlabsgallery_grids();

        $arrShort = array();
        foreach($arrGrids as $grid){
            $id = $grid->id;
            $title = $grid->name;

            //filter by except
            if(!empty($exceptID) && $exceptID == $id)
                continue;

            $arrShort[$id] = $title;
        }

        return apply_filters('adamlabsgallery_get_grids_short', $arrShort, $exceptID);
    }


    /**
     * get array of id -> handle
     * @since 1.0.6
     */
    public static function get_grids_short_widgets($exceptID = null){
        $arrGrids = self::get_adamlabsgallery_grids();

        $arrShort = array();

        foreach($arrGrids as $grid){

            //filter by except
            if(!empty($exceptID) && $exceptID == $grid->id)
                continue;

            $arrShort[$grid->id] = $grid->handle;
        }

        return apply_filters('adamlabsgallery_get_grids_short_widgets', $arrShort, $exceptID);
    }


    /**
     * get array of id -> title
     */
    public static function get_grids_short_vc($exceptID = null){
        $arrGrids = self::get_adamlabsgallery_grids();

        $arrShort = array();

        foreach($arrGrids as $grid){
            $alias = $grid->handle;
            $title = $grid->name;

            //filter by except
            if(!empty($exceptID) && $exceptID == $grid->id)
                continue;

            $arrShort[$title] = $alias;
        }

        return apply_filters('adamlabsgallery_get_grids_short_vc', $arrShort, $exceptID);
    }


    /**
     * Get Choosen Item Skin
     */
    public function get_choosen_item_skin(){

        $base = new AdamLabsGallery_Base();

        return apply_filters('adamlabsgallery_get_choosen_item_skin', $base->getVar($this->grid_params, 'entry-skin', 0, 'i'));

    }


    /**
     * Get Certain Parameter
     */
    public function get_param_by_handle($handle, $default = ''){
        $d = apply_filters('adamlabsgallery_get_param_by_handle', array('handle' => $handle, 'default' => $default));
        $handle = $d['handle'];
        $default = $d['default'];

        $base = new AdamLabsGallery_Base();

        return $base->getVar($this->grid_params, $handle, $default);

    }


    /**
     * Get Certain Post Parameter
     */
    public function get_postparam_by_handle($handle, $default = ''){
        $d = apply_filters('adamlabsgallery_get_postparam_by_handle', array('handle' => $handle, 'default' => $default));
        $handle = $d['handle'];
        $default = $d['default'];

        $base = new AdamLabsGallery_Base();

        return $base->getVar($this->grid_postparams, $handle, $default);

    }


    /**
     * Update Certain Parameter by Handle
     */
    public function set_param_by_handle($handle, $param){
        $this->grid_params[$handle] = $param;
    }


    /**
     * Update Certain Post Parameter by Handle
     */
    public function set_postparam_by_handle($handle, $param){
        $this->grid_postparams[$handle] = $param;
    }


    /**
     * Update Certain Post Parameter by Handle
     */
    public function save_params(){
        global $wpdb;

        $table_name = $wpdb->prefix . AdamLabsGallery::TABLE_GRID;

        $wpdb->update($table_name,
            array(
                'postparams' => json_encode($this->grid_postparams),
                'params' => json_encode($this->grid_params)
            ),
            array('id' => $this->grid_id)
        );

    }


    /**
     * Output Portfolio Gallery in Page by alias
     */
    public function output_adamlabsgallery_by_alias($eg_alias){
        global $wpdb;

        $eg_alias = apply_filters('adamlabsgallery_output_adamlabsgallery_by_alias', $eg_alias);

        $table_name = $wpdb->prefix . self::TABLE_GRID;

        $grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $eg_alias), ARRAY_A);

        if(!empty($grid)){
            $this->output_adamlabsgallery($grid['id']);
        }else{
            return false;
        }

    }


    /**
     * Output Portfolio Gallery in Page by Custom Settings and Layers
     */
    public function output_adamlabsgallery_by_settings(){

        do_action('adamlabsgallery_output_adamlabsgallery_by_settings', $this);

        if($this->custom_special !== null){
            if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
                $this->apply_custom_settings(true);

            $this->apply_all_media_types();

            $this->output_by_posts();
        }else{
            if($this->custom_settings == null || $this->custom_layers == null){ return false; }else{
                $this->output_adamlabsgallery_custom();
            }
        }

    }


    /**
     * Get Portfolio Gallery ID by alias
     */
    public static function get_id_by_alias($eg_alias){
        global $wpdb;

        $eg_alias = apply_filters('adamlabsgallery_get_id_by_alias', $eg_alias);

        $table_name = $wpdb->prefix . self::TABLE_GRID;

        $grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $eg_alias), ARRAY_A);

        if(!empty($grid)){
            return $grid['id'];
        }else{
            return '0';
        }

    }


    /**
     * Get Portfolio Gallery alias by ID
     */
    public static function get_alias_by_id($eg_id){
        global $wpdb;

        $eg_id = apply_filters('adamlabsgallery_get_alias_by_id', $eg_id);

        $table_name = $wpdb->prefix . self::TABLE_GRID;

        $grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $eg_id), ARRAY_A);

        if(!empty($grid)){
            return $grid['handle'];
        }else{
            return '';
        }

    }


    /**
     * get all post values / layer values at custom grid
     */
    public function get_layer_values(){

        return apply_filters('adamlabsgallery_get_layer_values', $this->grid_layers);

    }


    /**
     * Init essential data by id
     */
    public function init_by_id($grid_id){
        global $wpdb;

        $grid_id = apply_filters('adamlabsgallery_init_by_id_pre', $grid_id);

        $table_name = $wpdb->prefix . self::TABLE_GRID;

        $grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $grid_id), ARRAY_A);

        if(empty($grid)) return false;

        $this->grid_id = @$grid['id'];
        $this->grid_name = @$grid['name'];
        $this->grid_handle = @$grid['handle'];
        $this->grid_postparams = @json_decode($grid['postparams'], true);
        $this->grid_params = @json_decode($grid['params'], true);
        $this->grid_settings = @json_decode($grid['settings'], true);
        $this->grid_last_mod = @$grid['last_modified'];

        if(!empty($grid['layers'])){
            $orig_layers = $grid['layers'];
            $grid['layers'] = @json_decode(stripslashes($orig_layers), true);
            if(empty($grid['layers']) || !is_array($grid['layers'])) $grid['layers'] = @json_decode($orig_layers, true);

            if(!empty($grid['layers'])){
                foreach($grid['layers'] as $key => $layer){
                    $orig_layers_cur = $grid['layers'][$key];
                    $grid['layers'][$key] = @json_decode($orig_layers_cur, true);
                    if(empty($grid['layers'][$key]) || !is_array($grid['layers'][$key])) $grid['layers'][$key] = @json_decode(stripslashes($orig_layers_cur), true);
                }
            }
        }
        $this->grid_layers = @$grid['layers'];

        do_action('adamlabsgallery_init_by_id_post', $this, $grid);

        return true;
    }


    /**
     * Init essential data by given data
     */
    public function init_by_data($grid_data){

        $grid_data = apply_filters('adamlabsgallery_init_by_data', $grid_data);

        $this->grid_id = @$grid_data['id'];
        $this->grid_name = @$grid_data['name'];
        $this->grid_handle = @$grid_data['handle'];
        $this->grid_postparams = @$grid_data['postparams'];
        $this->grid_params = @$grid_data['params'];
        $this->grid_settings = @$grid_data['settings'];
        $this->grid_last_mod = @$grid_data['last_modified'];


        $temp_layer = array();

        if(!empty($grid_data['layers'])){
            foreach($grid_data['layers'] as $key => $layer){
                $temp_layer = @json_decode(stripslashes($grid_data['layers'][$key]), true);
                if(!empty($temp_layer)){
                    $grid_data['layers'][$key] = $temp_layer;
                }else{
                    $temp_layer = @json_decode($grid_data['layers'][$key], true);
                    if(!empty($temp_layer)){
                        $grid_data['layers'][$key] = $temp_layer;
                    }
                }
            }
        }
        $this->grid_layers = @$grid_data['layers'];

        return true;
    }


    /**
     * Init essential data by id
     */
    public function set_loading_ids($ids){

        $this->filter_by_ids = apply_filters('adamlabsgallery_set_loading_ids', $ids);

    }


    /**
     * Check if Grid is a Post
     */
    public function is_custom_grid(){

        do_action('adamlabsgallery_is_custom_grid');

        if(isset($this->grid_postparams['source-type']) && $this->grid_postparams['source-type'] == 'custom')
            return true;
        else
            return false;

    }



    /**
     * Check if Grid is a Stream
     */
    public function is_stream_grid(){

        do_action('adamlabsgallery_is_stream_grid');

        if(isset($this->grid_postparams['source-type'])){
            switch($this->grid_postparams['source-type']){
                case 'stream':
                case 'twitter':
                case 'facebook':
                case 'flickr':
                case 'instagram':
                case 'youtube':
                case 'behance':
                case 'nextgen':
                case 'rml':
                case 'vimeo':
                    return true;
            }
        }
        return false;
    }


    /**
     * Output Portfolio Gallery in Page
     */
    public function output_adamlabsgallery($grid_id, $data = array(), $grid_preview = false, $by_id = false){

        try{

            do_action('adamlabsgallery_output_adamlabsgallery', $grid_id, $data, $grid_preview, $by_id);

            if($grid_preview){
                $data['id'] = $grid_id;
                if($by_id == false){
                    $init = $this->init_by_data($data);
                }else{
                    $init = $this->init_by_id($grid_id);
                }
                if(!$init) return false; //be silent
            }else{
                $init = $this->init_by_id($grid_id);
                if(!$init) return false; //be silent
                AdamLabsGallery_Global_Css::output_global_css_styles_wrapped();
            }

            if($this->custom_posts !== null) //custom post IDs are added, so we change to post
                $this->grid_postparams['source-type'] = 'post';

            if($this->custom_images !== null) //custom images are added, so we change to gallery
                $this->grid_postparams['source-type'] = 'gallery';

            if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
                $this->apply_custom_settings();

            if($this->custom_layers !== null){ //custom layers got added. Overwrite Grid Layers
                $this->apply_custom_layers(true);
                $this->grid_postparams['source-type'] = 'custom';
            }

            $this->set_api_names(); //set correct names for javascript and div id
            switch($this->grid_postparams['source-type']){
                case 'post':
                case 'woocommerce':
                    $this->output_by_posts($grid_preview);
                    break;
                case 'custom':
                    $this->output_by_custom($grid_preview);
                    break;
                case 'gallery':
                    $this->output_by_gallery($grid_preview);
                    break;
                case 'stream':
                case 'twitter':
                case 'facebook':
                case 'flickr':
                case 'instagram':
                case 'youtube':
                case 'behance':
                case 'nextgen':
                case 'rml':
                case 'vimeo':
                    // $this->output_by_stream(false); //false, as we do not have any options to be changed
                    $this->output_by_stream($grid_preview);
                    break;
            }

        }catch(Exception $e){
            $message = $e->getMessage();
            echo $message;
        }
    }


    /**
     * set correct names for javascript and div id
     */
    public function set_api_names(){
        $ess_api = '';
        $ess_div = '';
        if($this->grid_id != null){
            $ess_api = $this->grid_id;
            $ess_div = $this->grid_id;
        }

        if($this->custom_special !== null){
            switch($this->custom_special){
                case 'related':
                case 'popular':
                case 'latest':
                    $ess_api .= '_'.$this->custom_special;
                    $ess_div .= '-'.$this->custom_special;
                    break;
            }
        }
        if($this->custom_posts !== null){
            $ess_api .= '_custom_post';
            $ess_div .= '-custom_post';
        }
        if($this->custom_settings !== null){
            $ess_api .= '_custom';
            $ess_div .= '-custom';
        }
        if($this->custom_layers !== null){
            $ess_api .= '_layers';
            $ess_div .= '-layers';
        }
        if($this->custom_images !== null){
            $ess_api .= '_img';
            $ess_div .= '-img';
        }

        $this->grid_api_name = $ess_api;
        $this->grid_div_name = $ess_div;

        do_action('adamlabsgallery_set_api_names', $this);

    }


    /**
     * Output Portfolio Gallery in Page with Custom Layer and Settings
     */
    public function output_adamlabsgallery_custom($grid_preview = false){
        try{

            do_action('adamlabsgallery_output_adamlabsgallery_custom', $this, $grid_preview);

            AdamLabsGallery_Global_Css::output_global_css_styles_wrapped();

            if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
                $this->apply_custom_settings(true);

            if($this->custom_layers !== null) //custom settings got added. Overwrite Grid Settings and element settings
                $this->apply_custom_layers(true);

            $this->apply_all_media_types();

            return $this->output_by_custom($grid_preview);

        }catch(Exception $e){
            $message = $e->getMessage();
            echo $message;
        }
    }


    /**
     * Apply all media types for custom grids that have not much settings
     */
    public function apply_all_media_types(){
        /**
         * Add settings that need to be set
         * - use all media sources, sorting does not matter since we only set one thing in each entry
         * - use all poster sources for videos, sorting does not matter since we only set one thing in each entry
         * - use all lightbox sources, sorting does not matter since we only set one thing in each entry
         */
        $media_orders = AdamLabsGallery_Base::get_media_source_order();
        foreach($media_orders as $handle => $vals){
            if($handle == 'featured-image' || $handle == 'alternate-image') continue;
            $this->grid_postparams['media-source-order'][] = $handle;
        }
        $this->grid_postparams['media-source-order'][] = 'featured-image'; //set this as the last entry
        $this->grid_postparams['media-source-order'][] = 'alternate-image'; //set this as the last entry

        $poster_orders = AdamLabsGallery_Base::get_poster_source_order();
        if(!empty($poster_orders)){
            foreach($poster_orders as $handle => $vals){
                $this->grid_params['poster-source-order'][] = $handle;
            }
        }

        $lb_orders = AdamLabsGallery_Base::get_lb_source_order();
        foreach($lb_orders as $handle => $vals){
            $this->grid_params['lb-source-order'][] = $handle;
        }

        $lb_buttons = AdamLabsGallery_Base::get_lb_button_order();
        foreach($lb_buttons as $handle => $vals){
            $this->grid_params['lb-button-order'][] = $handle;
        }

        do_action('adamlabsgallery_apply_all_media_types', $this);
    }


    /**
     * Apply Custom Settings to the Grid, so users can change everything in the settings they want to
     * This allows to modify grid_params and grid_post_params
     */
    private function apply_custom_settings($has_handle = false){

        if(empty($this->custom_settings) || !is_array($this->custom_settings)) return false;

        $base = new AdamLabsGallery_Base();

        $translate_variables = array('grid-layout' => 'layout');

        foreach($this->custom_settings as $handle => $new_setting){

            if(isset($translate_variables[$handle])){
                $handle = $translate_variables[$handle];
            }

            if($has_handle){ //p- is in front of postparameters

                if(strpos($handle, 'p-') === 0)
                    $this->grid_postparams[substr($handle, 2)] = $new_setting;
                else
                    $this->grid_params[$handle] = $new_setting;

            }else{

                if(isset($this->grid_params[$handle])){
                    $this->grid_params[$handle] = $new_setting;
                }elseif(isset($this->grid_postparams[$handle])){
                    $this->grid_postparams[$handle] = $new_setting;
                }else{
                    $this->grid_params[$handle] = $new_setting;
                }

            }
        }

        if(isset($this->grid_params['columns'])){ //change columns
            $columns = $base->set_basic_colums_custom($this->grid_params['columns']);
            $this->grid_params['columns'] = $columns;
        }

        if(isset($this->grid_params['rows-unlimited']) && $this->grid_params['rows-unlimited'] == 'off'){ //add pagination
            $this->grid_params['navigation-layout']['pagination']['bottom-1'] = '0';
            $this->grid_params['bottom-1-margin-top'] = '10';
        }

        do_action('adamlabsgallery_apply_custom_settings', $this);

        return true;

    }


    /**
     * Apply Custom Layers to the Grid
     */
    private function apply_custom_layers(){

        $this->grid_layers = array();
        if(!empty($this->custom_layers) && is_array($this->custom_layers)){
            $add_poster_img = array();
            foreach($this->custom_layers as $handle => $val_arr){
                if(!empty($val_arr) && is_array($val_arr)){
                    //$custom_poster = false;
                    foreach($val_arr as $id => $value){
                        //if($handle == 'custom-poster') $custom_poster = array($id, $value);
                        if($handle == 'custom-poster'){
                            $add_poster_img[$id] = $value;
                            continue;
                        }
                        $this->grid_layers[$id][$handle] = $value;
                    }
                }
            }

            if(!empty($add_poster_img)){
                foreach($add_poster_img as $id => $value){
                    $this->grid_layers[$id]['custom-image'] = $value;
                }
            }
        }

        do_action('adamlabsgallery_apply_custom_layers', $this);

    }


    /**
     * Output by Specific Stream
     */
    public function output_by_specific_stream(){

        ob_start();
        $this->output_by_stream(false, true, $this->filter_by_ids);
        $stream_html = ob_get_contents();
        ob_clean();
        ob_end_clean();

        return apply_filters('adamlabsgallery_output_by_specific_stream', $stream_html, $this);
    }


    /**
     * Output by Stream
     */
    public function output_by_stream($grid_preview = false, $only_elements = false, $specific_ids = array()){

        do_action('adamlabsgallery_output_by_stream_pre', $grid_preview, $only_elements, $specific_ids);

        $this->grid_layers = array();

        $base = new AdamLabsGallery_Base();

        if( in_array( $base->getVar($this->grid_postparams, 'source-type'), array("nextgen","rml") ) ){
            if( $base->getVar($this->grid_postparams, 'source-type') == "nextgen" ){
                $nextgen = new AdamLabsGallery_Nextgen();
                switch ($base->getVar($this->grid_postparams, 'nextgen-source-type','album')) {
                    case 'album':
                        $images = $nextgen->get_album_images($base->getVar($this->grid_postparams, 'nextgen-album',''));
                        break;
                    case 'gallery':
                        $images = $nextgen->get_gallery_images(array($base->getVar($this->grid_postparams, 'nextgen-gallery','')));
                        break;
                    case 'tags':
                        $images = $nextgen->get_tags_images($base->getVar($this->grid_postparams, 'nextgen-tags',''));
                        break;
                }

                $nextgen_images_avail_sizes = array('thumb','original');

                if(is_array($images)){
                    foreach ($images as $image) {
                        $image['custom-image-url-full'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'nextgen-full-size'),$nextgen_images_avail_sizes);
                        $image['custom-preload-image-url'] = $image['custom-image-url'][$base->getVar($this->grid_postparams, 'nextgen-thumb-size','thumb')][0];
                        $image['custom-image-url'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'nextgen-thumb-size'),$nextgen_images_avail_sizes);
                        $this->grid_layers[] = $image; //preg_replace("/[^0-9]/","",$image['id'])
                    }
                }
            }
            else {
                $rml = new AdamLabsGallery_Rml();
                $images = $rml->get_images($base->getVar($this->grid_postparams, 'rml-source-type'));

                if(is_array($images)){
                    foreach ($images as $image) {
                        $image['custom-image-url-full'] = $image['custom-image-url'][$base->getVar($this->grid_postparams, 'rml-full-size','original')];
                        $image['custom-preload-image-url'] = $image['custom-image-url']['thumbnail'];
                        $image['custom-image-url'] = $image['custom-image-url'][$base->getVar($this->grid_postparams, 'rml-thumb-size','original')];
                        $this->grid_layers[] = $image; //preg_replace("/[^0-9]/","",$image['id'])
                    }
                }
            }
        }
        else{
            switch ($base->getVar($this->grid_postparams, 'stream-source-type')) {
                case 'twitter':
                    $twitter = new AdamLabsGallery_Twitter($base->getVar($this->grid_postparams, 'twitter-consumer-key'),$base->getVar($this->grid_postparams, 'twitter-consumer-secret'),$base->getVar($this->grid_postparams, 'twitter-access-token'),$base->getVar($this->grid_postparams, 'twitter-access-secret'),$base->getVar($this->grid_postparams, 'twitter-transient-sec',86400));
                    $tweets = $twitter->get_public_photos($base->getVar($this->grid_postparams, 'twitter-user-id'),$base->getVar($this->grid_postparams, 'twitter-include-retweets'),$base->getVar($this->grid_postparams, 'twitter-exclude-replies'),$base->getVar($this->grid_postparams, 'twitter-count'),$base->getVar($this->grid_postparams, 'twitter-image-only'));

                    if(is_array($tweets)){
                        foreach ($tweets as $tweet) {
                            if( empty($tweet['custom-image-url-full'][0]) ) {
                                $default_image_id = $base->getVar($this->grid_postparams, 'default-image');
                                $default_image_size = 'full';
                                if(!empty($default_image_id)){
                                    $image =  wp_get_attachment_image_src($default_image_id,$default_image_size);
                                    $tweet['custom-image-url-full']= $image;
                                }
                            }
                            if( empty($tweet['custom-image-url'][0]) ) {
                                $default_image_id = $base->getVar($this->grid_postparams, 'default-image');
                                $default_image_size = 'full';
                                if(!empty($default_image_id)){
                                    $image =  wp_get_attachment_image_src($default_image_id,$default_image_size);
                                    $tweet['custom-image-url']= $image;
                                }
                            }
                            $this->grid_layers[] = $tweet; //preg_replace("/[^0-9]/","",$tweet['id'])
                        }
                    }
                    break;
                case 'instagram':
                    $instagram = new AdamLabsGallery_Instagram($base->getVar($this->grid_postparams, 'instagram-transient-sec',86400));

                    $public_photos = array();

                    if($base->getVar($this->grid_postparams, 'instagram-thumb-size') == 'Original Resolution' || $base->getVar($this->grid_postparams, 'instagram-full-size') == 'Original Resolution')
                        $orig_image = true;
                    else
                        $orig_image = false;

                    if( $base->getVar($this->grid_postparams, 'instagram-type-source-tags') == "true" ) {
                        $tag_photos = $instagram->get_tags_photos($base->getVar($this->grid_postparams, 'instagram-tags'),$base->getVar($this->grid_postparams, 'instagram-count'),$orig_image );
                        if(is_array($tag_photos))
                            $public_photos = array_merge($public_photos , $tag_photos);
                    }
                    if($base->getVar($this->grid_postparams, 'instagram-type-source-places') == "true") {
                        $place_photos = $instagram->get_places_photos($base->getVar($this->grid_postparams, 'instagram-places'),$base->getVar($this->grid_postparams, 'instagram-count'),$orig_image );
                        if(is_array($place_photos))
                            $public_photos = array_merge($public_photos , $place_photos);
                    }
                    $instagram_user_id = $base->getVar($this->grid_postparams, 'instagram-user-id');
                    if($base->getVar($this->grid_postparams, 'instagram-type-source-users') == "true" || ( $base->getVar($this->grid_postparams, 'instagram-type-source-tags') != "true" && $base->getVar($this->grid_postparams, 'instagram-type-source-places') != "true" && $base->getVar($this->grid_postparams, 'instagram-type-source-users') != "true" &&  !empty($instagram_user_id)  ) ) {
                        $user_photos = $instagram->get_users_photos($base->getVar($this->grid_postparams, 'instagram-user-id'),$base->getVar($this->grid_postparams, 'instagram-count'),$orig_image );
                        if(is_array($user_photos))
                            $public_photos = array_merge($public_photos , $user_photos );
                    }



                    //Filter out duplicates
                    $_public_photos = array();
                    foreach ($public_photos as $v) {
                        if (isset($_public_photos[$v['id']])) {
                            // found duplicate
                            continue;
                        }
                        // remember unique item
                        $_public_photos[$v['id']] = $v;
                    }
                    // if you need a zero-based array, otheriwse work with $_public_photos
                    $public_photos = array_values($_public_photos);

                    $instagram_images_avail_sizes = array('Thumbnail','Low Resolution','Standard Resolution','Original Resolution');

                    if(is_array($public_photos)){
                        foreach ($public_photos as $photo) {
                            $photo['custom-image-url-full'] = $this->find_biggest_photo($photo['custom-image-url'],$base->getVar($this->grid_postparams, 'instagram-full-size'),$instagram_images_avail_sizes);
                            $photo['custom-preload-image-url'] = $photo['custom-image-url']['Thumbnail'][0];
                            $photo['custom-image-url'] = $this->find_biggest_photo($photo['custom-image-url'],$base->getVar($this->grid_postparams, 'instagram-thumb-size'),$instagram_images_avail_sizes);

                            if($photo['custom-type'] == 'html5'){
                                $photo['html5']['mp4'] = $photo['custom-html5-mp4'];
                            }

                            $this->grid_layers[] = $photo; //preg_replace("/[^0-9]/","",$photo['id'])
                        }
                    }
                    break;
                case 'vimeo':
                    $vimeo = new AdamLabsGallery_Vimeo($base->getVar($this->grid_postparams, 'vimeo-transient-sec',86400));
                    $vimeo_type = $base->getVar($this->grid_postparams, 'vimeo-type-source');

                    switch ($vimeo_type) {
                        case 'user':
                            $videos = $vimeo->get_vimeo_videos($vimeo_type,$base->getVar($this->grid_postparams, 'vimeo-username'),$base->getVar($this->grid_postparams, 'vimeo-count','50'));
                            break;
                        case 'channel':
                            $videos = $vimeo->get_vimeo_videos($vimeo_type,$base->getVar($this->grid_postparams, 'vimeo-channelname'),$base->getVar($this->grid_postparams, 'vimeo-count','50'));
                            break;
                        case 'group':
                            $videos = $vimeo->get_vimeo_videos($vimeo_type,$base->getVar($this->grid_postparams, 'vimeo-groupname'),$base->getVar($this->grid_postparams, 'vimeo-count','50'));
                            break;
                        case 'album':
                            $videos = $vimeo->get_vimeo_videos($vimeo_type,$base->getVar($this->grid_postparams, 'vimeo-albumid'),$base->getVar($this->grid_postparams, 'vimeo-count','50'));
                            break;
                        default:
                            break;

                    }

                    $vimeo_images_avail_sizes = array('thumbnail_small','thumbnail_medium','thumbnail_large');

                    if(is_array($videos)){
                        foreach ($videos as $video) {
                            $video['custom-preload-image-url'] = $video['custom-image-url']['thumbnail_small'][0];
                            $video['custom-image-url'] = $this->find_biggest_photo($video['custom-image-url'],$base->getVar($this->grid_postparams, 'vimeo-thumb-size','thumbnail_medium'),$vimeo_images_avail_sizes);
                            $this->grid_layers[] = $video; //preg_replace("/[^0-9]/","",$video['id'])
                        }
                    }
                    break;
                case 'youtube':
                    $channel_id = $base->getVar($this->grid_postparams, 'youtube-channel-id');
                    $youtube = new AdamLabsGallery_Youtube($base->getVar($this->grid_postparams, 'youtube-api'),$channel_id,$base->getVar($this->grid_postparams, 'youtube-transient-sec',0));

                    switch ($base->getVar($this->grid_postparams, 'youtube-type-source')) {
                        case 'playlist':
                            $videos = $youtube->show_playlist_videos($base->getVar($this->grid_postparams, 'youtube-playlist'),$base->getVar($this->grid_postparams, 'youtube-count'));
                            break;
                        case 'playlist_overview':
                            $videos = $youtube->show_playlist_overview($base->getVar($this->grid_postparams, 'youtube-count'));
                            break;
                        default:
                            $videos = $youtube->show_channel_videos($base->getVar($this->grid_postparams, 'youtube-count'));
                            break;
                    }

                    $youtube_images_avail_sizes = array('default','medium','high','standard','maxres');

                    if(is_array($videos)){
                        foreach ($videos as $video) {
                            $video['custom-preload-image-url'] = $video['custom-image-url']['default'][0];
                            $video['custom-image-url-full'] = $this->find_biggest_photo($video['custom-image-url'],$base->getVar($this->grid_postparams, 'youtube-full-size'),$youtube_images_avail_sizes);
                            $video['custom-image-url'] = $this->find_biggest_photo($video['custom-image-url'],$base->getVar($this->grid_postparams, 'youtube-thumb-size'),$youtube_images_avail_sizes);

                            if(strpos($video['custom-image-url-full'][0], 'no_thumbnail') > 0) {
                                $default_image_id = $base->getVar($this->grid_postparams, 'default-image');
                                //$default_image_size = $base->getVar($this->grid_postparams, 'image-source-type');
                                $default_image_size = 'full';
                                if(!empty($default_image_id)){
                                    $image =  wp_get_attachment_image_src($default_image_id,$default_image_size);
                                    $video['custom-image-url-full']= $image;
                                }
                            }
                            if(strpos($video['custom-image-url'][0], 'no_thumbnail') > 0) {
                                $default_image_id = $base->getVar($this->grid_postparams, 'default-image');

                                $default_image_size = $base->getVar($this->grid_postparams, 'image-source-type', 'full');

                                /* 2.1.6 */
                                if(wp_is_mobile()) {
                                    $default_image_size = $base->getVar($this->grid_postparams, 'image-source-type-mobile', $default_image_size);
                                }

                                if(!empty($default_image_id)){
                                    $image =  wp_get_attachment_image_src($default_image_id,$default_image_size);
                                    $video['custom-image-url']= $image;
                                }
                            }

                            $this->grid_layers[] = $video; //preg_replace("/[^0-9]/","",$video['id'])
                        }
                    }
                    break;
                case 'facebook':
                    $facebook = new AdamLabsGallery_Facebook($base->getVar($this->grid_postparams, 'facebook-transient-sec',86400));
                    if($base->getVar($this->grid_postparams, 'facebook-type-source') == "album"){
                        $photo_set_photos = $facebook->get_photo_set_photos($base->getVar($this->grid_postparams, 'facebook-album'),$base->getVar($this->grid_postparams, 'facebook-count',10),$base->getVar($this->grid_postparams, 'facebook-app-id'),$base->getVar($this->grid_postparams, 'facebook-app-secret'));
                    }
                    else{
                        $user_id = $facebook->get_user_from_url($base->getVar($this->grid_postparams, 'facebook-page-url'));
                        $photo_set_photos = $facebook->get_photo_feed($user_id,$base->getVar($this->grid_postparams, 'facebook-app-id'),$base->getVar($this->grid_postparams, 'facebook-app-secret'),$base->getVar($this->grid_postparams, 'facebook-count',10));
                    }

                    $facebook_images_avail_sizes = array("thumbnail","normal");

                    if(is_array($photo_set_photos)){
                        $default_image_id = $base->getVar($this->grid_postparams, 'default-image');
                        $default_image_size = 'full';
                        $image =  wp_get_attachment_image_src($default_image_id,$default_image_size);

                        foreach ($photo_set_photos as $photo) {
                            $photo['custom-preload-image-url'] = isset($photo['custom-image-url']['thumbnail'][0]) ? $photo['custom-image-url']['thumbnail'][0] : "";
                            $photo['custom-image-url-full'] = isset($photo['custom-image-url']['normal']) ? $photo['custom-image-url']['normal'] : "";
                            $photo['custom-image-url'] = isset($photo['custom-image-url']['normal']) ? $photo['custom-image-url']['normal'] : "";

                            if( !empty($default_image_id) && empty($photo['custom-image-url']) ){
                                $photo['custom-preload-image-url'] = $image;
                                $photo['custom-image-url-full'] = $image;
                                $photo['custom-image-url'] = $image;
                            }

                            $this->grid_layers[] = $photo;
                        }
                    }
                    break;
                case 'flickr':
                    $flickr = new AdamLabsGallery_Flickr($base->getVar($this->grid_postparams, 'flickr-api-key'),$base->getVar($this->grid_postparams, 'flickr-transient-sec',86400));

                    switch($base->getVar($this->grid_postparams, 'flickr-type')){
                        case 'publicphotos':
                            $user_id = $flickr->get_user_from_url($base->getVar($this->grid_postparams, 'flickr-user-url'));
                            $flickr_photos = $flickr->get_public_photos($user_id,$base->getVar($this->grid_postparams, 'flickr-count'));
                            break;
                        case 'gallery':
                            $gallery_id = $flickr->get_gallery_from_url($base->getVar($this->grid_postparams, 'flickr-gallery-url'));
                            $flickr_photos = $flickr->get_gallery_photos($gallery_id,$base->getVar($this->grid_postparams, 'flickr-count'));
                            break;
                        case 'group':
                            $group_id = $flickr->get_group_from_url($base->getVar($this->grid_postparams, 'flickr-group-url'));
                            $flickr_photos = $flickr->get_group_photos($group_id,$base->getVar($this->grid_postparams, 'flickr-count'));
                            break;
                        case 'photosets':
                            $flickr_photos = $flickr->get_photo_set_photos($base->getVar($this->grid_postparams, 'flickr-photoset'),$base->getVar($this->grid_postparams, 'flickr-count'));
                            break;
                    }

                    $flickr_images_avail_sizes = array('Square','Thumbnail','Large Square','Small','Small 320','Medium','Medium 640','Medium 800','Large','Original');

                    if(is_array($flickr_photos)){
                        foreach ($flickr_photos as $photo) {
                            $photo['custom-preload-image-url'] = $photo['custom-image-url']['Square'][0];
                            $photo['custom-image-url-full'] = $this->find_biggest_photo($photo['custom-image-url'],$base->getVar($this->grid_postparams, 'flickr-full-size'),$flickr_images_avail_sizes);
                            $photo['custom-image-url'] = $this->find_biggest_photo($photo['custom-image-url'],$base->getVar($this->grid_postparams, 'flickr-thumb-size'),$flickr_images_avail_sizes);
                            $this->grid_layers[] = $photo; //preg_replace("/[^0-9]/","",$photo['id'])
                        }
                    }
                    break;
                case 'behance':
                    $behance = new AdamLabsGallery_Behance($base->getVar($this->grid_postparams, 'behance-api'),$base->getVar($this->grid_postparams, 'behance-user-id'),$base->getVar($this->grid_postparams, 'behance-transient-sec',0));

                    if( $base->getVar($this->grid_postparams, 'behance-type','projects')=='projects' ){
                        $images = $behance->get_behance_projects( $base->getVar($this->grid_postparams, 'behance-count',12) );
                    }
                    else {
                        $images = $behance->get_behance_project_images($base->getVar($this->grid_postparams, 'behance-project',''), $base->getVar($this->grid_postparams, 'behance-count',100) );
                    }

                    $behance_project_images_avail_sizes = array('disp','max_86400','max_1240','original');
                    $behance_images_avail_sizes = array('115','202','230','404','original');


                    if(is_array($images)){
                        foreach ($images as $image) {
                            if($base->getVar($this->grid_postparams, 'behance-type','projects')!='projects'){

                                $image['custom-image-url-full'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'behance-project-full-size'),$behance_project_images_avail_sizes);
                                $image['custom-image-url'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'behance-project-thumb-size'),$behance_project_images_avail_sizes);
                            }
                            else{

                                $image['custom-image-url-full'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'behance-projects-full-size'),$behance_images_avail_sizes);
                                $image['custom-image-url'] = $this->find_biggest_photo($image['custom-image-url'],$base->getVar($this->grid_postparams, 'behance-projects-thumb-size'),$behance_images_avail_sizes);
                            }

                            $this->grid_layers[] = $image; //preg_replace("/[^0-9]/","",$image['id'])
                        }
                    }
                    break;
            } // end switch
        } // end else


        if(!empty($specific_ids)){ //remove all that we do not have in this array
            foreach($this->grid_layers as $key => $layer){
                if(!in_array($key, $specific_ids)) unset($this->grid_layers[$key]);
            }
        }

        do_action('adamlabsgallery_output_by_stream_post', $this, $grid_preview, $only_elements);

        $do_load_more = (!empty($specific_ids)) ? true : false;

        return $this->output_by_custom($grid_preview, $only_elements, $do_load_more);
    }


    public function find_biggest_photo($image_urls, $wanted_size, $avail_sizes){
        $d = apply_filters('adamlabsgallery_find_biggest_photo', array('image_urls' => $image_urls, 'wanted_size' => $wanted_size, 'avail_sizes' => $avail_sizes));

        $image_urls = $d['image_urls'];
        $wanted_size = $d['wanted_size'];
        $avail_sizes = $d['avail_sizes'];

        if(isset($image_urls[$wanted_size]) && !$this->isEmpty($image_urls[$wanted_size])) return $image_urls[$wanted_size];
        $wanted_size_pos = array_search($wanted_size, $avail_sizes);
        for ($i=$wanted_size_pos; $i < 7; $i++) {
            if(isset($avail_sizes[$i]) && !$this->isEmpty($image_urls[$avail_sizes[$i]]))
                return $image_urls[$avail_sizes[$i]];
        }
        for ($i=$wanted_size_pos; $i >= 0 ; $i--) {
            if(!$this->isEmpty($image_urls[$avail_sizes[$i]])) return $image_urls[$avail_sizes[$i]];
        }
    }


    public function isEmpty($stringOrArray) {

        $stringOrArray = apply_filters('adamlabsgallery_isEmpty', $stringOrArray);

        if(is_array($stringOrArray)) {
            foreach($stringOrArray as $value) {
                if(!$this->isEmpty($value)) {
                    return false;
                }
            }
            return true;
        }

        return !strlen($stringOrArray);  // this properly checks on empty string ('')
    }


    /**
     * Output by gallery
     * Remove all custom elements, add image elements
     */
    public function output_by_gallery($grid_preview = false, $only_elements = false, $from_ajax = false){

        $this->grid_layers = array();

        if(!empty($this->custom_images)){
            foreach($this->custom_images as $image_id){
                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $title = get_the_title($image_id);
                $excerpt = get_post_field('post_excerpt', $image_id);
                $this->grid_layers[$image_id] = array(
                    'custom-image' => $image_id,
                    'excerpt' => $excerpt,
                    'caption' => $excerpt,
                    'title' => $title
                );

            }
        }

        do_action('adamlabsgallery_output_by_gallery', $this, $grid_preview, $only_elements);

        return $this->output_by_custom($grid_preview, $only_elements, false, $from_ajax);

    }


    /**
     * Output by custom grid
     */
    public function output_by_custom($grid_preview = false, $only_elements = false, $set_load_more = false, $from_ajax = false){

        $post_limit = 99999;

        do_action('adamlabsgallery_output_by_custom_pre', $this, $grid_preview, $only_elements);

        $base = new AdamLabsGallery_Base();
        $navigation_c = new AdamLabsGallery_Navigation($this->grid_id);
        $item_skin = new AdamLabsGallery_Item_Skin();
        $item_skin->grid_id = $this->grid_id;
        $item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));

        $item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
        $item_skin->set_default_youtube_image_by_id($base->getVar($this->grid_params, 'youtube-default-image', 0, 'i'));
        $item_skin->set_default_vimeo_image_by_id($base->getVar($this->grid_params, 'vimeo-default-image', 0, 'i'));
        $item_skin->set_default_html_image_by_id($base->getVar($this->grid_params, 'html5-default-image', 0, 'i'));

        // 2.1.6.2
        $item_skin->set_grid_item_animation($base, $this->grid_params);

        if($set_load_more)
            $item_skin->set_load_more();

        $m = new AdamLabsGallery_Meta();

        $skins_html = '';
        $skins_css = '';
        $filters = array();

        $rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
        $load_more = $base->getVar($this->grid_params, 'load-more', 'none');
        $load_more_start = $base->getVar($this->grid_params, 'load-more-start', 3, 'i');

        if($rows_unlimited == 'on' && $load_more !== 'none' && $grid_preview == false){ //grid_preview means disable load more in preview
            $post_limit = $load_more_start;
        }

        $nav_filters = array();

        $nav_layout = $base->getVar($this->grid_params, 'navigation-layout', array());
        $nav_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
        $hover_animation = $base->getVar($this->grid_params, 'hover-animation', 'fade');
        $filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_start = $base->getVar($this->grid_params, 'filter-start', '');
        $filterall_visible = $base->getVar($this->grid_params, 'filter-all-visible', 'on');
        $filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN));
        $filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
        $show_count = $base->getVar($this->grid_params, 'filter-counter', 'off');
        $search_text = $base->getVar($this->grid_params, 'search-text', __('Search...', ADAMLABS_GALLERY_TEXTDOMAIN));

        $filter_grouping = $base->getVar($this->grid_params, 'filter-grouping', 'false');
        $listing_type = $base->getVar($this->grid_params, 'filter-listing', 'list');
        //$selected = $base->getVar($this->grid_params, 'filter-selected', array());
        $filters_arr['filter-grouping'] = $filter_grouping;
        $filters_arr['filter-listing'] = $listing_type;
        $filters_arr['filter-selected'] = array(); //always give empty array (metas ect. may still be checked if Grid was a post based grid before.

        $navigation_c->set_filter_settings('filter', $filters_arr);

        $nav_type = $base->getVar($this->grid_params, 'nagivation-type', 'internal');
        $do_nav = ($nav_type == 'internal') ? true : false;

        $order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
        if(!is_array($order_by)) $order_by = array($order_by);
        $order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
        $order_by_dir = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');

        $sort_by_text = $base->getVar($this->grid_params, 'sort-by-text', __('Sort By ', ADAMLABS_GALLERY_TEXTDOMAIN));

        $module_spacings = $base->getVar($this->grid_params, 'module-spacings', '5');

        $top_1_align = $base->getVar($this->grid_params, 'top-1-align', 'center');
        $top_2_align = $base->getVar($this->grid_params, 'top-2-align', 'center');
        $bottom_1_align = $base->getVar($this->grid_params, 'bottom-1-align', 'center');
        $bottom_2_align = $base->getVar($this->grid_params, 'bottom-2-align', 'center');

        $top_1_margin = $base->getVar($this->grid_params, 'top-1-margin-bottom', 0, 'i');
        $top_2_margin = $base->getVar($this->grid_params, 'top-2-margin-bottom', 0, 'i');
        $bottom_1_margin = $base->getVar($this->grid_params, 'bottom-1-margin-top', 0, 'i');
        $bottom_2_margin = $base->getVar($this->grid_params, 'bottom-2-margin-top', 0, 'i');

        $left_margin = $base->getVar($this->grid_params, 'left-margin-left', 0, 'i');
        $right_margin = $base->getVar($this->grid_params, 'right-margin-right', 0, 'i');

        $nav_styles['top-1'] = array('margin-bottom' => $top_1_margin.'px', 'text-align' => $top_1_align);
        $nav_styles['top-2'] = array('margin-bottom' => $top_2_margin.'px', 'text-align' => $top_2_align);
        $nav_styles['left'] = array('margin-left' => $left_margin.'px');
        $nav_styles['right'] = array('margin-right' => $right_margin.'px');
        $nav_styles['bottom-1'] = array('margin-top' => $bottom_1_margin.'px', 'text-align' => $bottom_1_align);
        $nav_styles['bottom-2'] = array('margin-top' => $bottom_2_margin.'px', 'text-align' => $bottom_2_align);

        if($do_nav){ //only do if internal is selected
            $navigation_c->set_special_class('adamlabsgallery-fgc-'.$this->grid_id);
            $navigation_c->set_dropdown_text($filter_dropdown_text);
            $navigation_c->set_show_count($show_count);
            $navigation_c->set_filterall_visible($filterall_visible);
            $navigation_c->set_filter_text($filter_all_text);
            $navigation_c->set_specific_styles($nav_styles);
            $navigation_c->set_search_text($search_text);
            $navigation_c->set_layout($nav_layout); //set the layout

            $navigation_c->set_orders($order_by); //set order of filter
            $navigation_c->set_orders_text($sort_by_text);
            $navigation_c->set_orders_start($order_by_start); //set order of filter
        }
        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));

        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        if($lazy_load == 'on'){
            $item_skin->set_lazy_load(true);
            $lazy_load_blur = $base->getVar($this->grid_params, 'lazy-loading-blur', 'on');
            if($lazy_load_blur == 'on')
                $item_skin->set_lazy_load_blur(true);
        }

        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
        $item_skin->set_default_media_source_order($default_media_source_order);

        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
        $item_skin->set_default_lightbox_source_order($default_lightbox_source_order);

        /* 2.2 */
        $item_skin->set_fancybox_three_options($base->getVar($this->grid_params, 'lightbox-title', 'off'));

        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
        $item_skin->set_default_ajax_source_order($default_aj_source_order);

        $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');

        /* 2.2 */
        $default_lightbox_button_order = $base->getVar($this->grid_params, 'lb-button-order', array('share', 'thumbs', 'close'));

        /* 2.1.6 */
        if(wp_is_mobile()) {
            $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type-mobile', $post_media_source_type);
        }

        $default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
        if($default_video_poster_order == '')
            $default_video_poster_order = $base->getVar($this->grid_postparams, 'poster-source-order', '');

        $item_skin->set_default_video_poster_order($default_video_poster_order);

        $layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');

        $ajax_container_position = $base->getVar($this->grid_params, 'ajax-container-position', 'top');

        if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
            $item_skin->set_poster_cropping(true);
        }

        $skins_css = '';
        $skins_html = '';

        $found_filter = array();
        $i = 1;
        $this->order_by_custom($order_by_start, $order_by_dir);

        if($base->getVar($this->grid_postparams, 'source-type') == "stream" && $base->getVar($this->grid_postparams, 'stream-source-type') == "instagram" ){//&& $base->getVar($this->grid_postparams, 'instagram-type-source')=="mixed")
            if($order_by_start=="none"){
                $this->order_by_custom("date", "asc");
            }
            $this->grid_layers = array_slice($this->grid_layers,0,$base->getVar($this->grid_postparams, 'instagram-count'));
        }

        if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
            foreach($this->grid_layers as $key => $entry){

                $post_media_source_data = $base->get_custom_media_source_data($entry, $post_media_source_type);
                $post_video_ratios = $m->get_custom_video_ratios($entry);
                $filters = array();

                if(is_array($order_by) && !empty($order_by)){
                    $sort = $this->prepare_sorting_array_by_custom($entry, $order_by);
                    $item_skin->set_sorting($sort);
                }
                if(!empty($entry['custom-filter'])){
                    $cats = explode(',', $entry['custom-filter']);
                    if(!is_array($cats)) $cats = (array)$cats;
                    foreach($cats as $category){
                        $filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
                    }
                }

                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                //switch to different skin
                $use_item_skin_id = $base->getVar($entry, 'use-skin', '-1');
                if(intval($use_item_skin_id) === 0){
                    $use_item_skin_id = -1;
                }
                $item_skin->switch_item_skin($use_item_skin_id);
                $item_skin->register_layer_css();
                $item_skin->register_skin_css();

                if($i > $post_limit){
                    $this->load_more_post_array[$key] = $filters; //set for load more, only on elements that will not be loaded from beginning
                    continue; //Load only selected numbers of items at start (for load more)
                }
                $i++;

                $item_skin->set_filter($filters);
                $item_skin->set_media_sources($post_media_source_data);
                $item_skin->set_media_sources_type($post_media_source_type);
                $item_skin->set_video_ratios($post_video_ratios);
                $item_skin->set_layer_values($entry);

                /* 2.1.5 */
                $item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));

                // 2.1.6.2
                $item_skin->set_grid_item_animation($base, $this->grid_params);

                // 2.2.6
                $item_skin->set_post_values($entry);

                ob_start();
                $item_skin->output_item_skin($grid_preview);
                $skins_html.= ob_get_contents();
                ob_clean();
                ob_end_clean();

                // 2.2.6
                //if($only_elements == false && $grid_preview == false){
                $id = (isset($entry['post_id'])) ? $entry['post_id'] : '';
                if(!empty($id)) {
                    ob_start();
                    $item_skin->output_element_css_by_meta($id);
                    $skins_css.= ob_get_contents();
                    ob_clean();
                    ob_end_clean();
                }
                //}

            }
        }


        if($grid_preview !== false && $only_elements == false){ //add the add more box at the end
            ob_start();
            $item_skin->output_add_more();
            $skins_html.= ob_get_contents();
            ob_clean();
            ob_end_clean();
        }

        if($do_nav){ //only do if internal is selected
            $navigation_c->set_filter($found_filter); //set filters $nav_filters $found_filter
            $navigation_c->set_filter_type($filter_allow);
            $navigation_c->set_filter_start_select($filter_start);
        }

        if($only_elements == false){
            ob_start();
            $item_skin->generate_element_css($grid_preview);
            $skins_css.= ob_get_contents();
            ob_clean();
            ob_end_clean();


            if($do_nav){ //only do if internal is selected
                $navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
                echo $navigation_c->output_navigation_skin($navigation_skin);
            }

            echo $skins_css;

            if($item_skin->ajax_loading == true && $ajax_container_position == 'top'){
                echo $this->output_ajax_container();
            }

            $this->output_wrapper_pre($grid_preview);

            if($do_nav){ //only do if internal is selected
                $navigation_c->output_layout('top-1', $module_spacings);
                $navigation_c->output_layout('top-2', $module_spacings);
            }

            $this->output_grid_pre();
        }

        if(!$from_ajax) {
            echo $skins_html;
        }
        else {
            return $skins_html;
        }

        if($only_elements == false){
            $this->output_grid_post();

            if($do_nav){ //only do if internal is selected
                $navigation_c->output_layout('bottom-1', $module_spacings);
                $navigation_c->output_layout('bottom-2', $module_spacings);
                $navigation_c->output_layout('left');
                $navigation_c->output_layout('right');

                //check if search was added. If yes, we also need to add the "Filter All" filter if not existing
                echo $navigation_c->check_for_search();
            }

            $this->output_wrapper_post();

            if($item_skin->ajax_loading == true && $ajax_container_position == 'bottom'){
                echo $this->output_ajax_container();
            }

            $load_lightbox = $item_skin->do_lightbox_loading();

            if($grid_preview === false){
                $this->output_grid_javascript($load_lightbox);
            }elseif($grid_preview !== 'preview' && $grid_preview !== 'custom'){
                $this->output_grid_javascript($load_lightbox, true);
            }

            do_action('adamlabsgallery_output_by_custom_post', $this, $grid_preview, $only_elements);
        }
    }


    /**
     * Output by posts
     */
    public function output_by_posts($grid_preview = false){

        global $sitepress;

        do_action('adamlabsgallery_output_by_posts_pre', $this, $grid_preview);

        $post_limit = 99999;

        $base = new AdamLabsGallery_Base();
        $navigation_c = new AdamLabsGallery_Navigation($this->grid_id);
        $meta_c = new AdamLabsGallery_Meta();
        $meta_link_c = new AdamLabsGallery_Meta_Linking();
        $item_skin = new AdamLabsGallery_Item_Skin();
        $item_skin->grid_id = $this->grid_id;
        $item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));

        $item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
        $item_skin->set_default_youtube_image_by_id($base->getVar($this->grid_params, 'youtube-default-image', 0, 'i'));
        $item_skin->set_default_vimeo_image_by_id($base->getVar($this->grid_params, 'vimeo-default-image', 0, 'i'));
        $item_skin->set_default_html_image_by_id($base->getVar($this->grid_params, 'html-default-image', 0, 'i'));

        // 2.1.6.2
        $item_skin->set_grid_item_animation($base, $this->grid_params);

        $m = new AdamLabsGallery_Meta();

        $skins_html = '';
        $skins_css = '';
        $filters = array();

        $rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
        $load_more = $base->getVar($this->grid_params, 'load-more', 'none');
        $load_more_start = $base->getVar($this->grid_params, 'load-more-start', 3, 'i');

        if($rows_unlimited == 'on' && $load_more !== 'none' && $grid_preview == false){ //grid_preview means disable load more in preview
            $post_limit = $load_more_start;
        }

        $start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');

        $start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');

        $post_category = $base->getVar($this->grid_postparams, 'post_category');
        $post_types = $base->getVar($this->grid_postparams, 'post_types');
        $page_ids = explode(',', $base->getVar($this->grid_postparams, 'selected_pages', '-1'));
        $cat_relation = $base->getVar($this->grid_postparams, 'category-relation', 'OR');

        $max_entries = $this->get_maximum_entries($this);

        $additional_query = $base->getVar($this->grid_postparams, 'additional-query', '');
        if($additional_query !== '')
            $additional_query = wp_parse_args($additional_query);


        $cat_tax = array('cats' => '', 'tax' => '');

        if($this->custom_posts !== null){ //output by specific set posts

            $posts = AdamLabsGallery_Base::get_posts_by_ids($this->custom_posts, $start_sortby, $start_sortby_type);

            $cat_tax_obj = AdamLabsGallery_Base::get_categories_by_posts($posts);

            if(!empty($cat_tax_obj)){
                $cat_tax['cats'] = AdamLabsGallery_Base::translate_categories_to_string($cat_tax_obj);
            }
            //$cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category); //get cats by posts

        }elseif($this->custom_special !== null){ //output by some special rule

            $max_entries = intval($base->getVar($this->grid_params, 'max-entries', '20'));
            if($max_entries == 0) $max_entries = 20;

            switch($this->custom_special){
                case 'related':
                    $posts = AdamLabsGallery_Base::get_related_posts($max_entries);
                    break;
                case 'popular':
                    $posts = AdamLabsGallery_Base::get_popular_posts($max_entries);
                    break;
                case 'latest':
                default:
                    $posts = AdamLabsGallery_Base::get_latest_posts($max_entries);
                    break;
            }

            $cat_tax_obj = AdamLabsGallery_Base::get_categories_by_posts($posts);

            if(!empty($cat_tax_obj)){
                $cat_tax['cats'] = AdamLabsGallery_Base::translate_categories_to_string($cat_tax_obj);
            }

            //$cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category);  //get cats by posts

        }else{ //output with the grid settings from an existing grid

            $cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category);

            $posts = AdamLabsGallery_Base::getPostsByCategory($this->grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, $max_entries, $additional_query, true, $cat_relation);

        }

        $nav_layout = $base->getVar($this->grid_params, 'navigation-layout', array());
        $nav_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
        $hover_animation = $base->getVar($this->grid_params, 'hover-animation', 'fade');
        $filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_start = $base->getVar($this->grid_params, 'filter-start', '');

        $nav_type = $base->getVar($this->grid_params, 'nagivation-type', 'internal');
        $do_nav = ($nav_type == 'internal') ? true : false;

        $order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));

        if(!is_array($order_by)) $order_by = array($order_by);
        $order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
        if(strpos($order_by_start, 'adamlabsgallery-') === 0 || strpos($order_by_start, 'adamlabsgalleryl-') === 0){ //add meta at the end for meta sorting
            //if essential Meta, replace to meta name. Else -> replace - and _ with space, set each word uppercase
            $metas = $m->get_all_meta();
            $f = false;
            if(!empty($metas)){
                foreach($metas as $meta){
                    if('adamlabsgallery-'.$meta['handle'] == $order_by_start || 'adamlabsgalleryl-'.$meta['handle'] == $order_by_start){
                        $f = true;
                        $order_by_start = $meta['name'];
                        break;
                    }
                }
            }

            if($f === false){
                $order_by_start = ucwords(str_replace(array('-', '_'), array(' ', ' '), $order_by_start));
            }
        }

        $sort_by_text = $base->getVar($this->grid_params, 'sort-by-text', __('Sort By ', ADAMLABS_GALLERY_TEXTDOMAIN));
        $search_text = $base->getVar($this->grid_params, 'search-text', __('Search...', ADAMLABS_GALLERY_TEXTDOMAIN));

        $module_spacings = $base->getVar($this->grid_params, 'module-spacings', '5');

        $top_1_align = $base->getVar($this->grid_params, 'top-1-align', 'center');
        $top_2_align = $base->getVar($this->grid_params, 'top-2-align', 'center');
        $bottom_1_align = $base->getVar($this->grid_params, 'bottom-1-align', 'center');
        $bottom_2_align = $base->getVar($this->grid_params, 'bottom-2-align', 'center');

        $top_1_margin = $base->getVar($this->grid_params, 'top-1-margin-bottom', 0, 'i');
        $top_2_margin = $base->getVar($this->grid_params, 'top-2-margin-bottom', 0, 'i');
        $bottom_1_margin = $base->getVar($this->grid_params, 'bottom-1-margin-top', 0, 'i');
        $bottom_2_margin = $base->getVar($this->grid_params, 'bottom-2-margin-top', 0, 'i');

        $left_margin = $base->getVar($this->grid_params, 'left-margin-left', 0, 'i');
        $right_margin = $base->getVar($this->grid_params, 'right-margin-right', 0, 'i');

        $nav_styles['top-1'] = array('margin-bottom' => $top_1_margin.'px', 'text-align' => $top_1_align);
        $nav_styles['top-2'] = array('margin-bottom' => $top_2_margin.'px', 'text-align' => $top_2_align);
        $nav_styles['left'] = array('margin-left' => $left_margin.'px');
        $nav_styles['right'] = array('margin-right' => $right_margin.'px');
        $nav_styles['bottom-1'] = array('margin-top' => $bottom_1_margin.'px', 'text-align' => $bottom_1_align);
        $nav_styles['bottom-2'] = array('margin-top' => $bottom_2_margin.'px', 'text-align' => $bottom_2_align);

        $ajax_container_position = $base->getVar($this->grid_params, 'ajax-container-position', 'top');

        if($do_nav){ //only do if internal is selected
            $navigation_c->set_special_class('adamlabsgallery-fgc-'.$this->grid_id);

            $filters_meta = array();
            $filters_extra = array();

            foreach($this->grid_params as $gkey => $gparam){

                if(strpos($gkey, 'filter-selected') === false) continue;

                $fil_id = intval(str_replace('filter-selected-', '', $gkey));
                $fil_id = ($fil_id == 0) ? '' : '-'.$fil_id;
                $filters_arr = array();

                $filters_arr['filter'.$fil_id]['filter-grouping'] = $base->getVar($this->grid_params, 'filter-grouping'.$fil_id, 'false');
                $filters_arr['filter'.$fil_id]['filter-listing'] = $base->getVar($this->grid_params, 'filter-listing'.$fil_id, 'list');
                $filters_arr['filter'.$fil_id]['filter-selected'] = $base->getVar($this->grid_params, 'filter-selected'.$fil_id, array());

                $filterall_visible = $base->getVar($this->grid_params, 'filter-all-visible'.$fil_id, 'on');
                $filter_all_text = $base->getVar($this->grid_params, 'filter-all-text'.$fil_id, __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN));
                $filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text'.$fil_id, __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
                $show_count = $base->getVar($this->grid_params, 'filter-counter'.$fil_id, 'off');

                if(!empty($filters_arr['filter'.$fil_id]['filter-selected'])){
                    if(!empty($posts) && count($posts) > 0){
                        foreach($filters_arr['filter'.$fil_id]['filter-selected'] as $fk => $filter){
                            if(strpos($filter, 'meta-') === 0){
                                unset($filters_arr['filter'.$fil_id]['filter-selected'][$fk]); //delete entry

                                foreach($posts as $key => $post){
                                    $fil = str_replace('meta-', '', $filter);
                                    $post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'adamlabsgallery-'.$fil);
                                    if($post_filter_meta == ''){ //check if we are linking
                                        $post_filter_meta = $meta_link_c->get_link_meta_value_by_handle($post['ID'], 'adamlabsgalleryl-'.$fil);
                                    }
                                    $arr = json_decode($post_filter_meta, true);
                                    $cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
                                    //$cur_filter = explode(',', $post_filter_meta);
                                    $add_filter = array();
                                    if(!empty($cur_filter)){
                                        foreach($cur_filter as $k => $v){
                                            if(trim($v) !== ''){
                                                $add_filter[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
                                                if(!empty($filters_arr['filter'.$fil_id]['filter-selected'])){
                                                    $filter_found = false;
                                                    foreach($filters_arr['filter'.$fil_id]['filter-selected'] as $fcheck){
                                                        if($fcheck == sanitize_key($v)){
                                                            $filter_found = true;
                                                            break;
                                                        }
                                                    }
                                                    if(!$filter_found){
                                                        $filters_arr['filter'.$fil_id]['filter-selected'][] = sanitize_key($v); //add found meta
                                                    }
                                                }else{
                                                    $filters_arr['filter'.$fil_id]['filter-selected'][] = sanitize_key($v); //add found meta
                                                }
                                            }
                                        }
                                        $filters_meta = $filters_meta + $add_filter;

                                        if(!empty($add_filter)) $navigation_c->set_filter($add_filter);
                                    }
                                }
                            }
                        }
                    }
                    $filters_extra = $filters_arr['filter'.$fil_id]['filter-selected'] + $filters_extra;
                }

                $navigation_c->set_filter_settings('filter'.$fil_id, $filters_arr['filter'.$fil_id]);

                $navigation_c->set_filter_text($filter_all_text, $fil_id);
                $navigation_c->set_filterall_visible($filterall_visible, $fil_id);
                $navigation_c->set_dropdown_text($filter_dropdown_text, $fil_id);
                $navigation_c->set_show_count($show_count, $fil_id);
            }


            $navigation_c->set_filter_type($filter_allow);
            $navigation_c->set_filter_start_select($filter_start);
            $navigation_c->set_specific_styles($nav_styles);

            $navigation_c->set_layout($nav_layout); //set the layout

            $navigation_c->set_orders($order_by); //set order of filter
            $navigation_c->set_orders_text($sort_by_text); //set order of filter
            $navigation_c->set_orders_start($order_by_start); //set order of filter
            $navigation_c->set_search_text($search_text);
        }

        $nav_filters = array();

        $taxes = array('post_tag');
        if(!empty($cat_tax['tax']))
            $taxes = explode(',', $cat_tax['tax']);

        if(!empty($cat_tax['cats'])){
            $cats = explode(',', $cat_tax['cats']);

            foreach($cats as $key => $id){
                if(AdamLabsGallery_Wpml::is_wpml_exists() && isset($sitepress)){
                    $new_id = icl_object_id($id, 'category', true, $sitepress->get_default_language());
                    $cat = get_category($new_id);
                }else{
                    $cat = get_category($id);
                }
                if(is_object($cat)){
                    $nav_filters[$id] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug), 'parent' => $cat->category_parent);
                }

                foreach($taxes as $custom_tax){
                    $term = get_term_by('id', $id, $custom_tax);
                    if(is_object($term)) $nav_filters[$id] = array('name' => $term->name, 'slug' => sanitize_key($term->slug), 'parent' => $term->parent);
                }
            }

            if(!empty($filters_meta)){
                $nav_filters = $filters_meta + $nav_filters;
            }
            if(!empty($add_filter)){
                $nav_filters = $nav_filters + $add_filter;
            }
            asort($nav_filters);
        }

        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));

        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        if($lazy_load == 'on'){
            $item_skin->set_lazy_load(true);
            $lazy_load_blur = $base->getVar($this->grid_params, 'lazy-loading-blur', 'on');
            if($lazy_load_blur == 'on')
                $item_skin->set_lazy_load_blur(true);
        }

        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
        $item_skin->set_default_media_source_order($default_media_source_order);

        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
        $item_skin->set_default_lightbox_source_order($default_lightbox_source_order);

        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
        $item_skin->set_default_ajax_source_order($default_aj_source_order);

        $lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
        $lightbox_include_media = $base->getVar($this->grid_params, 'lightbox-exclude-media', 'off');

        /* 2.2 */
        $item_skin->set_fancybox_three_options($base->getVar($this->grid_params, 'lightbox-title', 'off'));

        $default_lightbox_button_order = $base->getVar($this->grid_params, 'lb-button-order', array('share', 'thumbs', 'close'));

        $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');

        /* 2.1.6 */
        if(wp_is_mobile()) {
            $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type-mobile', $post_media_source_type);
        }

        $default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
        if($default_video_poster_order == '')
            $default_video_poster_order = $base->getVar($this->grid_postparams, 'poster-source-order', '');

        $item_skin->set_default_video_poster_order($default_video_poster_order);

        $layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');

        if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
            $item_skin->set_poster_cropping(true);
        }

        $found_filter = array();
        $i = 1;

        /*if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
			$item_skin->set_lightbox_rel('ess-'.$this->grid_id);
		}
		*/
        if(!empty($posts) && count($posts) > 0){
            foreach($posts as $key => $post){
                if($grid_preview == false){
                    //check if post should be visible or if its invisible on current grid settings
                    $is_visible = $this->check_if_visible($post['ID'], $this->grid_id);
                    if($is_visible == false) continue; // continue if invisible
                }

                if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
                    //$item_skin->set_lightbox_rel('ess-'.$this->grid_id);
                    $item_skin->set_lightbox_rel('ess-'.$post['ID']);
                }

                $post_media_source_data = $base->get_post_media_source_data($post['ID'], $post_media_source_type);
                $post_video_ratios = $m->get_post_video_ratios($post['ID']);
                $filters = array();

                //$categories = get_the_category($post['ID']);
                $categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
                //$tags = wp_get_post_terms($post['ID']);
                $tags = get_the_tags($post['ID']);

                if(!empty($categories)){
                    foreach($categories as $key => $category){
                        $filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug), 'parent' => $category->parent);
                    }
                }

                if(!empty($tags)){
                    foreach($tags as $key => $taxonomie){
                        $filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug), 'parent' => '0');
                    }
                }

                foreach($this->grid_params as $gp_handle => $gp_values){
                    if(strpos($gp_handle, 'filter-selected') !== 0) continue;

                    $filter_meta_selected = $base->getVar($this->grid_params, $gp_handle, array());

                    if(!empty($filter_meta_selected)){
                        foreach($filter_meta_selected as $filter){
                            if(strpos($filter, 'meta-') === 0){
                                $fil = str_replace('meta-', '', $filter);
                                $post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'adamlabsgallery-'.$fil);
                                if($post_filter_meta == ''){ //check if we are linking
                                    $post_filter_meta = $meta_link_c->get_link_meta_value_by_handle($post['ID'], 'adamlabsgalleryl-'.$fil, 'asd');
                                }

                                $arr = json_decode($post_filter_meta, true);
                                $cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
                                //$cur_filter = explode(',', $post_filter_meta);
                                if(!empty($cur_filter)){
                                    foreach($cur_filter as $k => $v){
                                        if(trim($v) !== '')
                                            $filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
                                    }
                                }
                            }
                        }
                    }
                }

                if(is_array($order_by) && !empty($order_by)){
                    $sort = $this->prepare_sorting_array_by_post($post, $order_by);
                    $item_skin->set_sorting($sort);

                }

                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                //switch to different skin
                $use_item_skin_id = json_decode(get_post_meta($post['ID'], 'eg_use_skin', true), true);
                if($use_item_skin_id !== false && isset($use_item_skin_id[$this->grid_id]['use-skin'])){
                    $use_item_skin_id = $use_item_skin_id[$this->grid_id]['use-skin'];
                }else{
                    $use_item_skin_id = -1;
                }

                $use_item_skin_id = apply_filters('adamlabsgallery_modify_post_item_skin', $use_item_skin_id, $post, $this->grid_id);

                $item_skin->switch_item_skin($use_item_skin_id);
                $item_skin->register_layer_css();
                $item_skin->register_skin_css();


                if($i > $post_limit){
                    $this->load_more_post_array[$post['ID']] = $filters; //set for load more, only on elements that will not be loaded from beginning
                    continue; //Load only selected numbers of items at start (for load more)
                }
                $i++;

                if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
                    switch($lightbox_mode){
                        case 'content':
                            $lb_add_images = $base->get_all_content_images($post['ID']);
                            break;
                        case 'content-gallery':
                            $lb_add_images = $base->get_all_gallery_images($post['post_content'], true);
                            break;
                        case 'woocommerce-gallery':
                            $lb_add_images = array();
                            if(AdamLabsGallery_Woocommerce::is_woo_exists()){
                                $lb_add_images = AdamLabsGallery_Woocommerce::get_image_attachements($post['ID'], true);
                            }
                            break;
                    }

                    $item_skin->set_lightbox_addition(array('items' => $lb_add_images, 'base' => $lightbox_include_media));

                }

                $item_skin->set_filter($filters);
                $item_skin->set_media_sources($post_media_source_data);
                $item_skin->set_media_sources_type($post_media_source_type);
                $item_skin->set_video_ratios($post_video_ratios);
                $item_skin->set_post_values($post);

                ob_start();
                $item_skin->output_item_skin($grid_preview);
                $skins_html.= ob_get_contents();
                ob_clean();
                ob_end_clean();

                // 2.2.6
                //if($grid_preview == false){
                ob_start();
                $item_skin->output_element_css_by_meta($post['ID']);
                $skins_css.= ob_get_contents();
                ob_clean();
                ob_end_clean();
                //}
            }
        }else{
            return false;
        }

        if(!empty($filters_extra)){
            foreach($filters_extra as $f_extra){
                $f_extra = explode('_', $f_extra);
                if(is_array($f_extra) && !empty($f_extra)){
                    $cid = end($f_extra);
                    if(AdamLabsGallery_Wpml::is_wpml_exists() && isset($sitepress)){
                        $new_id = icl_object_id($cid, 'category', true, $sitepress->get_default_language());
                        $ncat = get_category($new_id);
                        if(!is_wp_error($ncat) && !is_null($ncat)){
                            $found_filter[$ncat->term_id] = array('name' => $ncat->name, 'slug' => $ncat->slug, 'parent' => $ncat->{'parent'});
                            $nav_filters[$ncat->term_id] = array('name' => $ncat->name, 'slug' => $ncat->slug, 'parent' => $ncat->{'parent'});
                        }
                    }else{
                        /* 2.1.5 */
                        $ncat = get_category($cid);
                        if(empty($ncat)) $ncat = get_tag($cid);
                        if(!is_wp_error($ncat) && !empty($ncat)){
                            $found_filter[$ncat->term_id] = array('name' => $ncat->name, 'slug' => $ncat->slug, 'parent' => $ncat->{'parent'});
                            $nav_filters[$ncat->term_id] = array('name' => $ncat->name, 'slug' => $ncat->slug, 'parent' => $ncat->{'parent'});
                        }
                    }
                }
            }
        }

        $remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
        if(!empty($remove_filter)){
            foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
                unset($nav_filters[$key]);
            }
        }

        if($do_nav){ //only do if internal is selected
            $navigation_c->set_filter($nav_filters); //set filters $nav_filters $found_filter
            $navigation_c->set_filter_type($filter_allow);
            $navigation_c->set_filter_start_select($filter_start);
        }

        ob_start();
        $item_skin->generate_element_css();
        $skins_css.= ob_get_contents();
        ob_clean();
        ob_end_clean();

        if($do_nav){ //only do if internal is selected
            $found_skin = array();
            $navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
            $navigation_special_skin = $base->getVar($this->grid_params, 'navigation-special-skin', array());
            ob_start();
            echo $navigation_c->output_navigation_skin($navigation_skin);
            $found_skin[$navigation_skin] = true;

            if(!empty($navigation_special_skin)){
                foreach($navigation_special_skin as $spec_skin){
                    if(!isset($found_skin[$spec_skin])){
                        echo $navigation_c->output_navigation_skin($spec_skin);
                        $found_skin[$spec_skin] = true;
                    }
                }
            }
            $nav_css = ob_get_contents();
            ob_clean();
            ob_end_clean();

            echo $nav_css;
        }

        echo $skins_css;

        if($item_skin->ajax_loading == true && $ajax_container_position == 'top'){
            echo $this->output_ajax_container();
        }

        $this->output_wrapper_pre($grid_preview);
        if($do_nav){ //only do if internal is selected
            $navigation_c->output_layout('top-1', $module_spacings);
            $navigation_c->output_layout('top-2', $module_spacings);
        }

        $this->output_grid_pre();

        echo $skins_html;

        $this->output_grid_post();
        if($do_nav){ //only do if internal is selected
            $navigation_c->output_layout('bottom-1', $module_spacings);
            $navigation_c->output_layout('bottom-2', $module_spacings);
            $navigation_c->output_layout('left');
            $navigation_c->output_layout('right');

            //check if search was added. If yes, we also need to add the "Filter All" filter if not existing
            echo $navigation_c->check_for_search();
        }

        $this->output_wrapper_post();

        if($item_skin->ajax_loading == true && $ajax_container_position == 'bottom'){
            echo $this->output_ajax_container();
        }

        $load_lightbox = $item_skin->do_lightbox_loading();

        if($grid_preview === false){
            $this->output_grid_javascript($load_lightbox);
        }elseif($grid_preview !== 'preview'){
            $this->output_grid_javascript($load_lightbox, true);
        }

        do_action('adamlabsgallery_output_by_posts_post', $this, $grid_preview);
    }


    /**
     * Output by specific posts for load more
     */
    public function output_by_specific_posts(){
        do_action('adamlabsgallery_output_by_specific_posts_pre', $this);

        $base = new AdamLabsGallery_Base();
        $item_skin = new AdamLabsGallery_Item_Skin();
        $item_skin->grid_id = $this->grid_id;
        $item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));
        $meta_c = new AdamLabsGallery_Meta();
        $meta_link_c = new AdamLabsGallery_Meta_Linking();

        $item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));

        // 2.1.6.2
        $item_skin->set_grid_item_animation($base, $this->grid_params);

        $m = new AdamLabsGallery_Meta();

        $start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');

        $start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');

        if(!empty($this->filter_by_ids)){
            $posts = AdamLabsGallery_Base::get_posts_by_ids($this->filter_by_ids, $start_sortby, $start_sortby_type);
        }else{
            return false;
        }

        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));
        $order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
        if(!is_array($order_by)) $order_by = array($order_by);


        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        if($lazy_load == 'on'){
            $item_skin->set_lazy_load(true);
            $lazy_load_blur = $base->getVar($this->grid_params, 'lazy-loading-blur', 'on');
            if($lazy_load_blur == 'on')
                $item_skin->set_lazy_load_blur(true);
        }

        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
        $item_skin->set_default_media_source_order($default_media_source_order);

        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
        $item_skin->set_default_lightbox_source_order($default_lightbox_source_order);

        $lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
        $lightbox_include_media = $base->getVar($this->grid_params, 'lightbox-exclude-media', 'off');

        /* 2.2 */
        $item_skin->set_fancybox_three_options($base->getVar($this->grid_params, 'lightbox-title', 'off'));

        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
        $item_skin->set_default_ajax_source_order($default_aj_source_order);

        $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');

        /* 2.1.6 */
        if(wp_is_mobile()) {
            $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type-mobile', $post_media_source_type);
        }

        $default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
        if($default_video_poster_order == '')
            $default_video_poster_order = $base->getVar($this->grid_postparams, 'poster-source-order', '');

        $item_skin->set_default_video_poster_order($default_video_poster_order);

        $layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');

        if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
            $item_skin->set_poster_cropping(true);
        }

        $skins_html = '';

        if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
            $item_skin->set_lightbox_rel('ess-'.$this->grid_id);
        }

        if(!empty($posts) && count($posts) > 0){
            foreach($posts as $key => $post){
                //check if post should be visible or if its invisible on current grid settings
                $is_visible = $this->check_if_visible($post['ID'], $this->grid_id);

                if($is_visible == false) continue; // continue if invisible

                $post_media_source_data = $base->get_post_media_source_data($post['ID'], $post_media_source_type);
                $post_video_ratios = $m->get_post_video_ratios($post['ID']);

                $filters = array();

                //$categories = get_the_category($post['ID']);
                $categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
                //$tags = wp_get_post_terms($post['ID']);
                $tags = get_the_tags($post['ID']);

                if(!empty($categories)){
                    foreach($categories as $key => $category){
                        $filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug));
                    }
                }

                if(!empty($tags)){
                    foreach($tags as $key => $taxonomie){
                        $filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug));
                    }
                }

                foreach($this->grid_params as $gp_handle => $gp_values){
                    if(strpos($gp_handle, 'filter-selected') !== 0) continue;

                    $filter_meta_selected = $base->getVar($this->grid_params, $gp_handle, array());

                    if(!empty($filter_meta_selected)){
                        foreach($filter_meta_selected as $filter){
                            if(strpos($filter, 'meta-') === 0){
                                $fil = str_replace('meta-', '', $filter);
                                $post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'adamlabsgallery-'.$fil);
                                if($post_filter_meta == ''){ //check if we are linking
                                    $post_filter_meta = $meta_link_c->get_link_meta_value_by_handle($post['ID'], 'adamlabsgalleryl-'.$fil, 'asd');
                                }

                                $arr = json_decode($post_filter_meta, true);
                                $cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
                                //$cur_filter = explode(',', $post_filter_meta);
                                if(!empty($cur_filter)){
                                    foreach($cur_filter as $k => $v){
                                        if(trim($v) !== '')
                                            $filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
                                    }
                                }
                            }
                        }
                    }
                }

                if(is_array($order_by) && !empty($order_by)){
                    $sort = $this->prepare_sorting_array_by_post($post, $order_by);
                    $item_skin->set_sorting($sort);
                }

                if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
                    switch($lightbox_mode){
                        case 'content':
                            $lb_add_images = $base->get_all_content_images($post['ID']);
                            break;
                        case 'content-gallery':
                            $lb_add_images = $base->get_all_gallery_images($post['post_content'], true);
                            break;
                        case 'woocommerce-gallery':
                            $lb_add_images = array();
                            if(AdamLabsGallery_Woocommerce::is_woo_exists()){
                                $lb_add_images = AdamLabsGallery_Woocommerce::get_image_attachements($post['ID'], true);
                            }
                            break;
                    }

                    $item_skin->set_lightbox_addition(array('items' => $lb_add_images, 'base' => $lightbox_include_media));

                }

                $item_skin->set_filter($filters);
                $item_skin->set_media_sources($post_media_source_data);
                $item_skin->set_media_sources_type($post_media_source_type);
                $item_skin->set_video_ratios($post_video_ratios);
                $item_skin->set_post_values($post);
                $item_skin->set_load_more();

                //switch to different skin
                $use_item_skin_id = json_decode(get_post_meta($post['ID'], 'eg_use_skin', true), true);
                if($use_item_skin_id !== false && isset($use_item_skin_id[$this->grid_id]['use-skin'])){
                    $use_item_skin_id = $use_item_skin_id[$this->grid_id]['use-skin'];
                }else{
                    $use_item_skin_id = -1;
                }

                $use_item_skin_id = apply_filters('adamlabsgallery_modify_post_item_skin', $use_item_skin_id, $post, $this->grid_id);

                $item_skin->switch_item_skin($use_item_skin_id);
                $item_skin->register_layer_css();
                $item_skin->register_skin_css();

                ob_start();
                $item_skin->output_item_skin();
                $skins_html.= ob_get_contents();
                ob_clean();
                ob_end_clean();

            }
        }else{
            $skins_html = false;
        }

        do_action('adamlabsgallery_output_by_specific_posts_post', $this, $skins_html);

        return apply_filters('adamlabsgallery_output_by_specific_posts_return', $skins_html, $this);

    }


    /**
     * Output by specific ids for load more custom grid
     */
    public function output_by_specific_ids($gal = false){

        do_action('adamlabsgallery_output_by_specific_ids_pre', $this);

        $base = new AdamLabsGallery_Base();
        $item_skin = new AdamLabsGallery_Item_Skin();
        $item_skin->grid_id = $this->grid_id;
        $item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));

        $item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));

        // 2.1.6.2
        $item_skin->set_grid_item_animation($base, $this->grid_params);

        $m = new AdamLabsGallery_Meta();

        $filters = array();

        $order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
        if(!is_array($order_by)) $order_by = array($order_by);

        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));

        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        if($lazy_load == 'on'){
            $item_skin->set_lazy_load(true);
            $lazy_load_blur = $base->getVar($this->grid_params, 'lazy-loading-blur', 'on');
            if($lazy_load_blur == 'on')
                $item_skin->set_lazy_load_blur(true);
        }

        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
        $item_skin->set_default_media_source_order($default_media_source_order);

        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
        $item_skin->set_default_lightbox_source_order($default_lightbox_source_order);

        /* 2.2 */
        $item_skin->set_fancybox_three_options($base->getVar($this->grid_params, 'lightbox-title', 'off'));

        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
        $item_skin->set_default_ajax_source_order($default_aj_source_order);

        $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');

        /* 2.1.6 */
        if(wp_is_mobile()) {
            $post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type-mobile', $post_media_source_type);
        }

        $default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
        if($default_video_poster_order == '')
            $default_video_poster_order = $base->getVar($this->grid_postparams, 'poster-source-order', '');

        $item_skin->set_default_video_poster_order($default_video_poster_order);

        $layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');

        if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
            $item_skin->set_poster_cropping(true);
        }

        $skins_html = '';

        $found_filter = array();

        $order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
        $order_by_dir = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');

        $this->order_by_custom($order_by_start, $order_by_dir);

        if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
            foreach($this->grid_layers as $key => $entry){

                if(!in_array($key, $this->filter_by_ids)) continue;

                $post_media_source_data = $base->get_custom_media_source_data($entry, $post_media_source_type);
                $post_video_ratios = $m->get_custom_video_ratios($entry);
                $filters = array();

                if(is_array($order_by) && !empty($order_by)){
                    //$sort = $this->prepare_sorting_array_by_post($post, $order_by);
                    //$item_skin->set_sorting($sort);
                }
                if(!empty($entry['custom-filter'])){
                    $cats = explode(',', $entry['custom-filter']);
                    if(!is_array($cats)) $cats = (array)$cats;
                    foreach($cats as $category){
                        $filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
                    }
                }

                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                $item_skin->set_filter($filters);
                $item_skin->set_media_sources($post_media_source_data);
                $item_skin->set_media_sources_type($post_media_source_type);
                $item_skin->set_video_ratios($post_video_ratios);
                $item_skin->set_layer_values($entry);
                $item_skin->set_load_more();

                //switch to different skin
                $use_item_skin_id = $base->getVar($entry, 'use-skin', '-1');
                if(intval($use_item_skin_id) === 0){
                    $use_item_skin_id = -1;
                }
                $item_skin->switch_item_skin($use_item_skin_id);
                $item_skin->register_layer_css();
                $item_skin->register_skin_css();

                // 2.2.6
                $item_skin->set_post_values($entry);

                ob_start();
                $item_skin->output_item_skin();
                $skins_html.= ob_get_contents();
                ob_clean();
                ob_end_clean();

                // 2.2.6
                $id = (isset($entry['post_id'])) ? $entry['post_id'] : '';
                if(!empty($id)) {
                    ob_start();
                    $item_skin->output_element_css_by_meta($id);
                    $skins_html.= ob_get_contents();
                    ob_clean();
                    ob_end_clean();
                }

            }
        }else{
            $skins_html = false;
        }

        do_action('adamlabsgallery_output_by_specific_ids_post', $this, $skins_html);

        return apply_filters('adamlabsgallery_output_by_specific_ids_return', $skins_html, $this);

    }


    public function prepare_sorting_array_by_post($post, $order_by){
        $d = apply_filters('adamlabsgallery_prepare_sorting_array_by_post_pre', array('post' => $post, 'order_by' => $order_by));
        $post = $d['post'];
        $order_by = $d['order_by'];

        $base = new AdamLabsGallery_Base();
        $link_meta = new AdamLabsGallery_Meta_Linking();
        $meta = new AdamLabsGallery_Meta();

        $m = $meta->get_all_meta(false);
        $lm = $link_meta->get_all_link_meta(false);

        $sorts = array();
        foreach($order_by as $order){
            switch($order){
                case 'date':
                    $sorts['date'] = strtotime($base->getVar($post, 'post_date'));
                    break;
                case 'title':
                    $sorts['title'] = substr($base->getVar($post, 'post_title', ''), 0, 10);
                    break;
                case 'excerpt':
                    $sorts['excerpt'] = substr(strip_tags($base->getVar($post, 'post_excerpt', '')), 0, 10);
                    break;
                case 'id':
                    $sorts['id'] = $base->getVar($post, 'ID');
                    break;
                case 'slug':
                    $sorts['slug'] = $base->getVar($post, 'post_name');
                    break;
                case 'author':
                    $authorID = $base->getVar($post, 'post_author');
                    $sorts['author'] = get_the_author_meta('display_name', $authorID);
                    break;
                case 'last-modified':
                    $sorts['last-modified'] = strtotime($base->getVar($post, 'post_modified'));
                    break;
                case 'number-of-comments':
                    $sorts['number-of-comments'] = $base->getVar($post, 'comment_count');
                    break;
                case 'likespost':
                    $post_id = $base->getVar($post, 'ID');
                    $like_count = get_post_meta($post_id, "adamlabsgallery_votes_count", 0 );
                    $sorts['likespost'] = isset( $like_count[0] ) ? intval( $like_count[0] ) : 0;
                    break;
                case 'random':
                    $sorts['random'] = rand(0,9999);
                    break;
                default: //check if meta. If yes, add meta values
                    if(strpos($order, 'adamlabsgallery-') === 0){
                        if(!empty($m)){
                            foreach($m as $me){
                                if('adamlabsgallery-'.$me['handle'] == $order){
                                    $sorts[$order] = $meta->get_meta_value_by_handle($post['ID'],$order);
                                    break;
                                }
                            }
                        }
                    }elseif(strpos($order, 'adamlabsgalleryl-') === 0){
                        if(!empty($lm)){
                            foreach($lm as $me){
                                if('adamlabsgalleryl-'.$me['handle'] == $order){
                                    $sorts[$order] = $link_meta->get_link_meta_value_by_handle($post['ID'],$order);
                                    break;
                                }
                            }
                        }
                    }
                    break;
            }
        }

        //add woocommerce sortings
        if(AdamLabsGallery_Woocommerce::is_woo_exists()){
            $is_30 = AdamLabsGallery_Woocommerce::version_check('3.0');
            $product = ($is_30) ? wc_get_product($post['ID']) : get_product($post['ID']);

            if(!empty($product)){
                foreach($order_by as $order){
                    switch($order){
                        case 'meta_num_total_sales':
                            $sorts['total-sales'] = get_post_meta($post['ID'],$order,true);
                            break;
                        case 'meta_num__regular_price':
                            $sorts['regular-price'] = $product->get_price();
                            break;
                        //case 'meta_num__sale_price':
                        //	$sorts['sale-price'] = $product->get_sale_price();
                        //break;
                        case 'meta__featured':
                            $sorts['featured'] = ($product->is_featured()) ? '1' : '0';
                            break;
                        case 'meta__sku':
                            $sorts['sku'] = $product->get_sku();
                            break;
                        case 'meta_num_stock':
                            $sorts['in-stock'] = $product->get_stock_quantity();
                            break;
                    }
                }
            }
        }

        return apply_filters('adamlabsgallery_prepare_sorting_array_by_post_post', $sorts, $post, $order_by);
    }


    public function prepare_sorting_array_by_custom($post, $order_by){
        $d = apply_filters('adamlabsgallery_prepare_sorting_array_by_custom_pre', array('post' => $post, 'order_by' => $order_by));
        $post = $d['post'];
        $order_by = $d['order_by'];

        $base = new AdamLabsGallery_Base();
        $link_meta = new AdamLabsGallery_Meta_Linking();
        $meta = new AdamLabsGallery_Meta();

        $m = $meta->get_all_meta(false);
        $lm = $link_meta->get_all_link_meta(false);

        $sorts = array();
        foreach($order_by as $order){
            switch($order){
                case 'date':
                    $sorts['date'] = strtotime($base->getVar($post, 'date'));
                    break;
                case 'title':
                    $sorts['title'] = substr($base->getVar($post, 'title', ''), 0, 10);
                    break;
                case 'excerpt':
                    $sorts['excerpt'] = substr(strip_tags($base->getVar($post, 'excerpt', '')), 0, 10);
                    break;
                case 'id':
                    $sorts['id'] = $base->getVar($post, 'post_id');
                    break;
                case 'slug':
                    $sorts['slug'] = $base->getVar($post, 'alias');
                    break;
                case 'author':
                    $sorts['author'] = $base->getVar($post, 'author_name');
                    break;
                case 'last-modified':
                    $sorts['last-modified'] = strtotime($base->getVar($post, 'date_modified'));
                    break;
                case 'number-of-comments':
                    $sorts['number-of-comments'] = $base->getVar($post, 'num_comments');
                    break;
                case 'random':
                    $sorts['random'] = rand(0,9999);
                    break;
                case 'views':
                    $sorts['views'] = $base->getVar($post, 'views');
                    break;
                case 'likespost':
                    $post_id = $base->getVar($post, 'ID');
                    $like_count = get_post_meta($post_id, "adamlabsgallery_votes_count", 0 );
                    $sorts['likespost'] = isset($like_count[0]) ? $like_count[0] : 0;
                    break;
                case 'likes':
                    $sorts['likes'] = $base->getVar($post, 'likes');
                    break;
                case 'dislikes':
                    $sorts['dislikes'] = $base->getVar($post, 'dislikes');
                    break;
                case 'retweets':
                    $sorts['retweets'] = $base->getVar($post, 'retweets');
                    break;
                case 'favorites':
                    $sorts['favorites'] = $base->getVar($post, 'favorites');
                    break;
                case 'itemCount':
                    $sorts['itemCount'] = $base->getVar($post, 'itemCount');
                    break;
                case 'duration':
                    $sorts['duration'] = $base->getVar($post, 'duration');
                    break;
                default: //check if meta. If yes, add meta values
                    if(strpos($order, 'adamlabsgallery-') === 0 || strpos($order, 'adamlabsgalleryl-') === 0){
                        $sorts[$order] = $base->getVar($post, $order);
                    }
                    break;
            }
        }

        return apply_filters('adamlabsgallery_prepare_sorting_array_by_custom_post', $sorts, $post, $order_by);
    }

    public function prepare_sorting_array_by_stream($post, $order_by){
        $d = apply_filters('adamlabsgallery_prepare_sorting_array_by_stream_pre', array('post' => $post, 'order_by' => $order_by));
        $post = $d['post'];
        $order_by = $d['order_by'];

        $base = new AdamLabsGallery_Base();
        $link_meta = new AdamLabsGallery_Meta_Linking();
        $meta = new AdamLabsGallery_Meta();

        $m = $meta->get_all_meta(false);
        $lm = $link_meta->get_all_link_meta(false);

        $sorts = array();
        foreach($order_by as $order){
            switch($order){
                case 'date':
                    $sorts['date'] = strtotime($base->getVar($post, 'date'));
                    break;
                case 'title':
                    $sorts['title'] = substr($base->getVar($post, 'title', ''), 0, 10);
                    break;
                case 'excerpt':
                    $sorts['excerpt'] = substr(strip_tags($base->getVar($post, 'excerpt', '')), 0, 10);
                    break;
                case 'id':
                    $sorts['id'] = $base->getVar($post, 'post_id');
                    break;
                case 'slug':
                    $sorts['slug'] = $base->getVar($post, 'alias');
                    break;
                case 'author':
                    $sorts['author'] = $base->getVar($post, 'author_name');
                    break;
                case 'last-modified':
                    $sorts['last-modified'] = strtotime($base->getVar($post, 'date_modified'));
                    break;
                case 'number-of-comments':
                    $sorts['number-of-comments'] = $base->getVar($post, 'num_comments');
                    break;
                case 'random':
                    $sorts['random'] = rand(0,9999);
                    break;
                case 'likespost':
                    $post_id = $base->getVar($post, 'ID');
                    $like_count = get_post_meta($post_id, "adamlabsgallery_votes_count", 0 );
                    $sorts['likespost'] = isset($like_count[0]) ? $like_count[0] : 0;
                    break;
                case 'views':
                    $sorts['views'] = $base->getVar($post, 'views');
                    break;
                default: //check if meta. If yes, add meta values
                    if(strpos($order, 'adamlabsgallery-') === 0 || strpos($order, 'adamlabsgalleryl-') === 0){
                        $sorts[$order] = $base->getVar($post, $order);
                    }
                    break;
            }
        }

        return apply_filters('adamlabsgallery_prepare_sorting_array_by_stream_post', $sorts, $post, $order_by);
    }


    public function output_wrapper_pre($grid_preview = false){

        global $adamlabsgallery_grid_serial;

        $base = new AdamLabsGallery_Base();


        $adamlabsgallery_grid_serial++;

        if($this->grid_div_name === null) $this->grid_div_name = $this->grid_id;

        $grid_id = ($grid_preview !== false) ? 'adamlabsgallery-preview-grid' : 'adamlabsgallery-grid-'.$this->grid_div_name.'-'.$adamlabsgallery_grid_serial;
        $grid_id_wrap = $grid_id . '-wrap';
        $article_id = ($grid_preview !== false) ? ' adamlabsgallery-preview-skinlevel' : '';

        $hide_markup_before_load = $base->getVar($this->grid_params, 'hide-markup-before-load', 'off');
        $background_color = $base->getVar($this->grid_params, 'main-background-color', 'transparent');
        $navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
        $paddings = $base->getVar($this->grid_params, 'grid-padding', 0);
        $css_id = $base->getVar($this->grid_params, 'css-id', '');
        $source_type = $base->getVar($this->grid_postparams, 'source-type', 'post');

        /* 2.1.6 */
        if(class_exists('AdamLabsColorpicker')) {

            $background_col = AdamLabsColorpicker::process($background_color, false);
            if(!empty($background_col) && is_array($background_col)) {
                $background_color = $background_col[0];
                if(empty($background_color)) $background_color = '#FFFFFF';
            }

        }

        $pad_style = '';

        if(is_array($paddings) && !empty($paddings)){
            $pad_style = 'padding: ';
            foreach($paddings as $size){
                $pad_style .= $size.'px ';
            }
            $pad_style .= ';';

            $pad_style .= ' box-sizing:border-box;';
            $pad_style .= ' -moz-box-sizing:border-box;';
            $pad_style .= ' -webkit-box-sizing:border-box;';
        }

        $div_style = ' style="';
        $div_style.= 'background: '.$background_color.';';
        $div_style.= $pad_style;
        if($hide_markup_before_load == 'on')
            $div_style.= ' display:none';

        $div_style.= '"';

        if($css_id == '') $css_id = $grid_id_wrap;

        $do_fix_height = $this->add_start_height_css($css_id);

        $this->remove_load_more_button($css_id);

        $fix_height_class = ($do_fix_height) ? ' adamlabsgallery-startheight' : '';

        $n = '<!-- THE ADAMLABS_GALLERY GRID '. self::VERSION .' '.strtoupper($source_type).' -->'."\n\n";

        //$n .= '<!-- GRID WRAPPER FOR CONTAINER SIZING - HERE YOU CAN SET THE CONTAINER SIZE AND CONTAINER SKIN -->'."\n";
        $n .= '<article class="myportfolio-container '.$navigation_skin.$fix_height_class.' source_type_'.$source_type.'" id="'.$css_id.$article_id.'">'."\n\n"; //fullwidthcontainer-with-padding

        //$n .= '    <!-- THE GRID ITSELF WITH FILTERS, PAGINATION, SORTING ETC -->'."\n";
        $n .= '    <div id="'.$grid_id.'" class="adamlabsgallery-grid"'.$div_style.'>'."\n";

        echo apply_filters('adamlabsgallery_output_wrapper_pre', $n, $grid_preview);


    }


    public function output_wrapper_post(){

        $n  = '    </div>'."\n\n"; //<!-- END OF THE GRID -->'."\n\n";
        $n .= '</article>'."\n";
        //$n .= '<!-- END OF THE GRID WRAPPER -->'."\n\n";
        $n .= '<div class="clear"></div>'."\n";

        echo apply_filters('adamlabsgallery_output_wrapper_post', $n);

    }


    public function output_grid_pre(){

        //$n  = '<!-- ############################ -->'."\n";
        //$n .= '<!-- THE GRID ITSELF WITH ENTRIES -->'."\n";
        //$n .= '<!-- ############################ -->'."\n";
        $n = '<ul>'."\n";

        echo apply_filters('adamlabsgallery_output_grid_pre', $n);

    }


    public function output_grid_post(){

        $n  = '</ul>'."\n";
        //$n .= '<!-- ############################ -->'."\n";
        //$n .= '<!--      END OF THE GRID         -->'."\n";
        //$n .= '<!-- ############################ -->'."\n";

        echo apply_filters('adamlabsgallery_output_grid_post', $n);

    }


    public function output_grid_javascript($load_lightbox = false, $is_demo = false){

        global $adamlabsgallery_grid_serial;

        $base = new AdamLabsGallery_Base();

        $hide_markup_before_load = $base->getVar($this->grid_params, 'hide-markup-before-load', 'off');

        $layout = $base->getVar($this->grid_params, 'layout','even');
        $force_full_width = $base->getVar($this->grid_params, 'force_full_width', 'off');

        $content_push = $base->getVar($this->grid_params, 'content-push', 'off');

        $rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
        $load_more_type = $base->getVar($this->grid_params, 'load-more', 'on');
        $rows = $base->getVar($this->grid_params, 'rows', 4, 'i');

        if(wp_is_mobile()) {

            $mobile_rows = $base->getVar($this->grid_params, 'enable-rows-mobile', 'off') === 'on';
            if($mobile_rows) $rows = $base->getVar($this->grid_params, 'rows-mobile', 3, 'i');

        }

        $columns = $base->getVar($this->grid_params, 'columns', '');
        $columns = $base->set_basic_colums($columns);

        $columns_advanced = $base->getVar($this->grid_params, 'columns-advanced', 'off');
        if($columns_advanced == 'on'){
            $columns_width = $base->getVar($this->grid_params, 'columns-width', '');
            if($layout == 'masonry'){
                $masonry_content_height = $base->getVar($this->grid_params, 'mascontent-height', '');
            }else{
                $masonry_content_height = array(); //get defaults
            }
        }else{
            $columns_width = array(); //get defaults
            $masonry_content_height = array(); //get defaults
        }

        $columns_width = $base->set_basic_colums_width($columns_width);
        $masonry_content_height = $base->set_basic_masonry_content_height($masonry_content_height);

        // 2.2.6
        $hide_blankitems_at = $base->getVar($this->grid_params, 'blank-item-breakpoint', '1');

        $space = $base->getVar($this->grid_params, 'spacings', 0, 'i');
        $page_animation = $base->getVar($this->grid_params, 'grid-animation', 'scale');

        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
        $layout_offset_container = $base->getVar($this->grid_params, 'fullscreen-offset-container', '');

        // 2.2.5
        $start_animation = $base->getVar($this->grid_params, 'grid-start-animation', 'reveal');
        $start_animation_speed = $base->getVar($this->grid_params, 'grid-start-animation-speed', 1000, 'i');
        $start_animation_delay = $base->getVar($this->grid_params, 'grid-start-animation-delay', 100, 'i');
        $start_animation_type = $base->getVar($this->grid_params, 'grid-start-animation-type', 'item');
        $animation_type = $base->getVar($this->grid_params, 'grid-animation-type', 'item');

        // 2.2.5
        $in_viewport = $base->getVar($this->grid_params, 'start-anime-in-viewport', 'off') === 'off' ? 'true' : 'false';
        $viewport_buffer = $base->getVar($this->grid_params, 'start-anime-viewport-buffer', 20, 'i');
        $viewport_buffer = intval($viewport_buffer);
        $viewport_buffer = max($viewport_buffer, 0);
        $viewport_buffer = min($viewport_buffer, 80);

        if($start_animation === 'reveal' || $start_animation === 'none') $in_viewport = 'true';
        if($start_animation === 'reveal') {
            if($layout_sizing !== 'fullscreen') {
                $start_animation = 'none';
                $hide_markup_before_load = 'on';
            }
            else {
                $start_animation = 'scale';
                $start_animation_delay = 0;
                $hide_markup_before_load = 'off';
            }

        }

        // 2.2.6
        if($rows_unlimited === 'off') {

            $touchswipe = $base->getVar($this->grid_params, 'pagination-touchswipe', 'off');
            $dragvertical = $base->getVar($this->grid_params, 'pagination-dragvertical', 'on');
            $swipebuffer = $base->getVar($this->grid_params, 'pagination-swipebuffer', 30, 'i');

        }
        else {

            $touchswipe = 'off';
            $dragvertical = 'off';
            $swipebuffer = 30;

        }

        $anim_speed = $base->getVar($this->grid_params, 'grid-animation-speed', 800, 'i');
        $delay_basic = $base->getVar($this->grid_params, 'grid-animation-delay', 1, 'i');
        $delay_hover = $base->getVar($this->grid_params, 'hover-animation-delay', 1, 'i');
        $filter_type = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_logic = $base->getVar($this->grid_params, 'filter-logic', 'or');
        $filter_show_on = $base->getVar($this->grid_params, 'filter-show-on', 'hover');

        $lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
        $lightbox_mode = ($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery') ? 'contentgroup' : $lightbox_mode;

        /* 2.2 */
        $lb_button_order = $base->getVar($this->grid_params, 'lb-button-order', array('share', 'thumbs', 'close'));
        $lb_post_max_width = $base->getVar($this->grid_params, 'lightbox-post-content-max-width', '75');
        $lb_post_max_perc = $base->getVar($this->grid_params, 'lightbox-post-content-max-perc', 'on') == 'on' ? '%' : 'px';
        $lb_post_max_width = intval($lb_post_max_width) . $lb_post_max_perc;

        $lb_post_min_width = $base->getVar($this->grid_params, 'lightbox-post-content-min-width', '75');
        $lb_post_min_perc = $base->getVar($this->grid_params, 'lightbox-post-content-min-perc', 'on') == 'on' ? '%' : 'px';
        $lb_post_min_width = intval($lb_post_min_width) . $lb_post_min_perc;

        $no_filter_match_message = get_option('adamlabsgallery_no_filter_match_message', 'No Items for the Selected Filter');

        /* 2.1.6 for lightbox post content addition */
        $lb_post_spinner = $base->getVar($this->grid_params, 'lightbox-post-spinner', 'off');
        $lb_featured_img = $base->getVar($this->grid_params, 'lightbox-post-content-img', 'off');
        $lb_featured_pos = $base->getVar($this->grid_params, 'lightbox-post-content-img-position', 'top');
        $lb_featured_width = $base->getVar($this->grid_params, 'lightbox-post-content-img-width', '100');
        $lb_featured_margin = $base->getVar($this->grid_params, 'lightbox-post-content-img-margin', array('0', '0', '0', '0'));
        $lb_post_title = $base->getVar($this->grid_params, 'lightbox-post-content-title', 'off');
        $lb_post_title_tag = $base->getVar($this->grid_params, 'lightbox-post-content-title-tag', 'h2');

        // 2.2 Deeplinking
        $filter_deep_linking = $base->getVar($this->grid_params, 'filter-deep-link', 'off');

        // 2.2.5 Mobile Filter Conversion
        $single_filters = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_mobile_conversion = $single_filters === 'single' && wp_is_mobile() ? $base->getVar($this->grid_params, 'convert-mobile-filters', 'off') : false;
        $filter_mobile_conversion = $filter_mobile_conversion === 'on' ? 'true' : 'false';

        if(!is_array($lb_featured_margin) || count($lb_featured_margin) !== 4) $lb_featured_margin = array('0', '0', '0', '0');
        $lb_featured_margin = implode('|', $lb_featured_margin);

        $aspect_ratio_x = $base->getVar($this->grid_params, 'x-ratio', 4, 'i');
        $aspect_ratio_y = $base->getVar($this->grid_params, 'y-ratio', 3, 'i');
        $auto_ratio = $base->getVar($this->grid_params, 'auto-ratio', 'true');

        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        $lazy_load_color = $base->getVar($this->grid_params, 'lazy-load-color', '#FFFFFF');

        $spinner = $base->getVar($this->grid_params, 'use-spinner', '0');
        $spinner_color = $base->getVar($this->grid_params, 'spinner-color', '#FFFFFF');

        /* 2.1.6 */
        if(class_exists('AdamLabsColorpicker')) {

            $spinner_col = AdamLabsColorpicker::process($spinner_color, false);
            $lazy_load_col = AdamLabsColorpicker::process($lazy_load_color, false);

            if(!empty($spinner_col) && is_array($spinner_col)) {
                $spinner_color = $spinner_col[0];
                if(empty($spinner_color)) $spinner_color = '#FFFFFF';
            }
            if(!empty($lazy_load_col) && is_array($lazy_load_col)) {
                $lazy_load_color = $lazy_load_col[0];
                if(empty($lazy_load_color)) $lazy_load_color = '#FFFFFF';
            }

        }

        $lightbox_effect_open_close = $base->getVar($this->grid_params, 'lightbox-effect-open-close', 'fade');
        if($lightbox_effect_open_close != 'false') $lightbox_effect_open_close = '"' . $lightbox_effect_open_close . '"';

        $lightbox_effect_open_close_speed = $base->getVar($this->grid_params, 'lightbox-effect-open-close-speed', '500');
        if(!is_numeric($lightbox_effect_open_close_speed)) $lightbox_effect_open_close_speed = '500';

        $lightbox_effect_next_prev = $base->getVar($this->grid_params, 'lightbox-effect-next-prev', 'fade');
        if($lightbox_effect_next_prev != 'false') $lightbox_effect_next_prev = '"' . $lightbox_effect_next_prev . '"';

        $lightbox_effect_next_prev_speed = $base->getVar($this->grid_params, 'lightbox-effect-next-prev-speed', '366');
        if(!is_numeric($lightbox_effect_next_prev_speed)) $lightbox_effect_next_prev_speed = '366';

        $lightbox_deep_link = $base->getVar($this->grid_params, 'lightbox-deep-link', 'group');
        if(empty($lightbox_deep_link)) $lightbox_deep_link = 'group';

        $lightbox_mousewheel = $base->getVar($this->grid_params, 'lightbox-mousewheel', 'off') == 'on' ? '"auto"' : 'false';
        $lightbox_arrows = $base->getVar($this->grid_params, 'lightbox-arrows', 'off') == 'on' ? 'true' : 'false';

        $lbox_autoplay = $base->getVar($this->grid_params, 'lightbox-autoplay', 'off') == 'on' ? 'true' : 'false';
        $lbox_playspeed = $base->getVar($this->grid_params, 'lbox-playspeed', '3000');
        // $lbox_preload = $base->getVar($this->grid_params, 'lbox-preload', '3');

        $lbox_padding = $base->getVar($this->grid_params, 'lbox-padding', array('0','0','0','0'));
        $lbox_numbers = $base->getVar($this->grid_params, 'lightbox-numbers', 'on') === 'on' ? 'true' : 'false';
        $lbox_loop = $base->getVar($this->grid_params, 'lightbox-loop', 'on') === 'on' ? 'true' : 'false';

        $lbox_margin = $base->getVar($this->grid_params, 'lbox-padding', array('0','0','0','0'));
        if(!is_array($lbox_margin) || count($lbox_margin) !== 4) $lbox_margin = array('0', '0', '0', '0');
        $lbox_margin = implode('|', $lbox_margin);

        $lbox_inpadding = $base->getVar($this->grid_params, 'lbox-content_padding', array('0','0','0','0'));
        if(!is_array($lbox_inpadding) || count($lbox_inpadding) !== 4) $lbox_inpadding = array('0', '0', '0', '0');
        $lbox_inpadding = implode('|', $lbox_inpadding);

        $lbox_overflow = $base->getVar($this->grid_params, 'lightbox-post-content-overflow', 'on') == 'on' ? 'auto' : 'hidden';

        $rtl = $base->getVar($this->grid_params, 'rtl', 'off');

        $media_filter_type = $base->getVar($this->grid_postparams, 'media-filter-type', 'none');

        $wait_for_fonts = get_option('adamlabsgallery_wait_for_fonts', 'true');

        $pagination_numbers = $base->getVar($this->grid_params, 'pagination-numbers', 'smart');
        $pagination_scroll = $base->getVar($this->grid_params, 'pagination-scroll', 'off');
        $pagination_scroll_offset = $base->getVar($this->grid_params, 'pagination-scroll-offset', '0', 'i');

        if($base->getVar($this->grid_params, 'rows-unlimited', 'on') == 'off') {
            $pagination_autoplay = $base->getVar($this->grid_params, 'pagination-autoplay', 'off');
            $pagination_autoplay_delay = $base->getVar($this->grid_params, 'pagination-autoplay-speed', '5000', 'i');
        }
        else {
            $pagination_autoplay = 'off';
            $pagination_autoplay_delay = 5000;
        }

        $ajax_callback = $base->getVar($this->grid_params, 'ajax-callback', '');
        $ajax_css_url = $base->getVar($this->grid_params, 'ajax-css-url', '');
        $ajax_js_url = $base->getVar($this->grid_params, 'ajax-js-url', '');
        $ajax_scroll_onload = $base->getVar($this->grid_params, 'ajax-scroll-onload', 'on');
        $ajax_callback_argument = $base->getVar($this->grid_params, 'ajax-callback-arg', 'on');
        $ajax_content_id = $base->getVar($this->grid_params, 'ajax-container-id', '');
        $ajax_scrollto_offset = $base->getVar($this->grid_params, 'ajax-scrollto-offset', '0');
        $ajax_close_button = $base->getVar($this->grid_params, 'ajax-close-button', 'off');
        $ajax_button_nav = $base->getVar($this->grid_params, 'ajax-nav-button', 'off');
        $ajax_content_sliding = $base->getVar($this->grid_params, 'ajax-content-sliding', 'on');
        $ajax_button_type = $base->getVar($this->grid_params, 'ajax-button-type', 'button');
        if($ajax_button_type == 'type2'){
            $ajax_button_text = $base->getVar($this->grid_params, 'ajax-button-text', __('Close', ADAMLABS_GALLERY_TEXTDOMAIN));
        }
        $ajax_button_skin = $base->getVar($this->grid_params, 'ajax-button-skin', 'light');
        $ajax_button_inner = $base->getVar($this->grid_params, 'ajax-button-inner', 'false');
        $ajax_button_h_pos = $base->getVar($this->grid_params, 'ajax-button-h-pos', 'r');
        $ajax_button_v_pos = $base->getVar($this->grid_params, 'ajax-button-v-pos', 't');

        $cobbles_pattern = $base->getVar($this->grid_params, 'cobbles-pattern', array());
        $use_cobbles_pattern = $base->getVar($this->grid_params, 'use-cobbles-pattern', 'off');

        $cookie_time = intval($base->getVar($this->grid_params, 'cookie-save-time', '30'));
        $cookie_search = $base->getVar($this->grid_params, 'cookie-save-search', 'off');
        $cookie_filter = $base->getVar($this->grid_params, 'cookie-save-filter', 'off');
        $cookie_pagination = $base->getVar($this->grid_params, 'cookie-save-pagination', 'off');

        $js_to_footer = (get_option('adamlabsgallery_js_to_footer', 'false') == 'true') ? true : false;

        //add inline style into the footer
        if($js_to_footer && $is_demo == false){
            ob_start();
        }

        echo '<script type="text/javascript">'."\n";

        if($hide_markup_before_load == 'off') {
            echo 'function adamlabsgallerybfc(winw,resultoption) {'."\n";
            echo '	var lasttop = winw,'."\n";
            echo '	lastbottom = 0,'."\n";
            echo '	smallest =9999,'."\n";
            echo '	largest = 0,'."\n";
            echo '	samount = 0,'."\n";
            echo '	lamount = 0,'."\n";
            echo '	lastamount = 0,'."\n";
            echo '	resultid = 0,'."\n";
            echo '	resultidb = 0,'."\n";
            echo '	responsiveEntries = ['."\n";
            echo '						{ width:'.$columns_width['0'].',amount:'.$columns['0'].',mmheight:'.$masonry_content_height['0'].'},'."\n";
            echo '						{ width:'.$columns_width['1'].',amount:'.$columns['1'].',mmheight:'.$masonry_content_height['1'].'},'."\n";
            echo '						{ width:'.$columns_width['2'].',amount:'.$columns['2'].',mmheight:'.$masonry_content_height['2'].'},'."\n";
            echo '						{ width:'.$columns_width['3'].',amount:'.$columns['3'].',mmheight:'.$masonry_content_height['3'].'},'."\n";
            echo '						{ width:'.$columns_width['4'].',amount:'.$columns['4'].',mmheight:'.$masonry_content_height['4'].'},'."\n";
            echo '						{ width:'.$columns_width['5'].',amount:'.$columns['5'].',mmheight:'.$masonry_content_height['5'].'},'."\n";
            echo '						{ width:'.$columns_width['6'].',amount:'.$columns['6'].',mmheight:'.$masonry_content_height['6'].'}'."\n";
            echo '						];'."\n";
            echo '	if (responsiveEntries!=undefined && responsiveEntries.length>0)'."\n";
            echo '		jQuery.each(responsiveEntries, function(index,obj) {'."\n";
            echo '			var curw = obj.width != undefined ? obj.width : 0,'."\n";
            echo '				cura = obj.amount != undefined ? obj.amount : 0;'."\n";
            echo '			if (smallest>curw) {'."\n";
            echo '				smallest = curw;'."\n";
            echo '				samount = cura;'."\n";
            echo '				resultidb = index;'."\n";
            echo '			}'."\n";
            echo '			if (largest<curw) {'."\n";
            echo '				largest = curw;'."\n";
            echo '				lamount = cura;'."\n";
            echo '			}'."\n";
            echo '			if (curw>lastbottom && curw<=lasttop) {'."\n";
            echo '				lastbottom = curw;'."\n";
            echo '				lastamount = cura;'."\n";
            echo '				resultid = index;'."\n";
            echo '			}'."\n";
            echo '		});'."\n";
            echo '		if (smallest>winw) {'."\n";
            echo '			lastamount = samount;'."\n";
            echo '			resultid = resultidb;'."\n";
            echo '		}'."\n";
            echo '		var obj = new Object;'."\n";
            echo '		obj.index = resultid;'."\n";
            echo '		obj.column = lastamount;'."\n";
            echo '		if (resultoption=="id")'."\n";
            echo '			return obj;'."\n";
            echo '		else'."\n";
            echo '			return lastamount;'."\n";
            echo '	}'."\n";
            // echo 'if ("'.$layout.'"=="even") {'."\n";
            echo '	var coh=0,'."\n";
            echo '		container = jQuery("#adamlabsgallery-grid-'.$this->grid_div_name.'-'.$adamlabsgallery_grid_serial.'");'."\n";
            if($layout_sizing == 'fullscreen'){
                echo 'coh = jQuery(window).height();'."\n";

                if($layout_offset_container !== ''){
                    echo 'try{'."\n";
                    echo '	var offcontainers = "'.$layout_offset_container.'".split(",");'."\n";
                    echo '	jQuery.each(offcontainers,function(index,searchedcont) {'."\n";
                    echo '		coh = coh - jQuery(searchedcont).outerHeight(true);'."\n";
                    echo '	})'."\n";
                    echo '} catch(e) {}'."\n";
                }
            } else {
                echo '	var	cwidth = "' . $layout_sizing . '" == "boxed" ? container.width() : jQuery(window).width(),'."\n";
                echo '		ar = "'.$aspect_ratio_x.':'.$aspect_ratio_y.'",'."\n";
                echo '		gbfc = adamlabsgallerybfc(cwidth,"id"),'."\n";
                if($rows_unlimited == 'on'){
                    $load_more_start = $base->getVar($this->grid_params, 'load-more-start', 3, 'i');
                    echo '	row = Math.ceil(' . $load_more_start . ' / gbfc.column);'."\n";
                } else {
                    echo '	row = '.$rows.';'."\n";
                }
                echo 'ar = ar.split(":");'."\n";
                echo 'var aratio=parseInt(ar[0],0) / parseInt(ar[1],0);'."\n";
                echo 'coh = cwidth / aratio;'."\n";
                echo 'coh = coh/gbfc.column*row;'."\n";
            }
            echo '	var ul = container.find("ul").first();'."\n";
            echo '	ul.css({display:"block",height:coh+"px"});'."\n";
            // echo '}'."\n";
        }

        echo 'var adamlabsgalleryapi'.$this->grid_api_name.';'."\n";
        echo 'jQuery(document).ready(function() {'."\n";
        echo '	adamlabsgalleryapi'.$this->grid_api_name.' = jQuery("#adamlabsgallery-grid-'.$this->grid_div_name.'-'.$adamlabsgallery_grid_serial.'").adamlabsgallery({'."\n";

        do_action('adamlabsgallery_output_grid_javascript_options', $this);

        echo '        gridID:'.$this->grid_id.','."\n";
        echo '        layout:"'.$layout.'",'."\n";

        if($rtl == 'on') echo '        rtl:"on",'."\n";

        echo '        forceFullWidth:"'.$force_full_width.'",'."\n";
        echo '        lazyLoad:"'.$lazy_load.'",'."\n";
        if($lazy_load == 'on')
            echo '        lazyLoadColor:"'.$lazy_load_color.'",'."\n";

        if($rows_unlimited == 'on'){
            $load_more		  = $base->getVar($this->grid_params, 'load-more', 'button');
            $load_more_amount = $base->getVar($this->grid_params, 'load-more-amount', 3, 'i');
            $load_more_show_number = $base->getVar($this->grid_params, 'load-more-show-number', 'on');

            if($load_more !== 'none'){
                $load_more_text = $base->getVar($this->grid_params, 'load-more-text', __('Load More', ADAMLABS_GALLERY_TEXTDOMAIN));
                echo '        gridID:"'.$this->grid_id.'",'."\n";
                echo '        loadMoreType:"'.$load_more.'",'."\n";
                echo '        loadMoreAmount:'.$load_more_amount.','."\n";
                echo '        loadMoreTxt:"'.$load_more_text.'",'."\n";
                echo '        loadMoreNr:"'.$load_more_show_number.'",'."\n";
                echo '        loadMoreEndTxt:"'.__('No More Items for the Selected Filter', ADAMLABS_GALLERY_TEXTDOMAIN).'",'."\n";
                echo '        loadMoreItems:';
                $this->output_load_more_list();
                echo ','."\n";

                /* 2.1.5 */
                if(!empty($this->custom_images)) {
                    echo '        customGallery: true,'."\n";
                }

            }
            echo '        row:9999,'."\n";
        }else{
            echo '        row:'.$rows.','."\n";
        }
        $token = wp_create_nonce('AdamLabsGallery_Front');
        echo '		apiName: "adamlabsgalleryapi'.$this->grid_api_name.'",'."\n";
        echo '        loadMoreAjaxToken:"'.$token.'",'."\n";
        echo '        loadMoreAjaxUrl:"'.admin_url('admin-ajax.php').'",'."\n";
        echo '        loadMoreAjaxAction:"adamlabsgallery_front_request_ajax",'."\n";

        echo '        ajaxContentTarget:"'.$ajax_content_id.'",'."\n";
        echo '        ajaxScrollToOffset:"'.$ajax_scrollto_offset.'",'."\n";
        echo '        ajaxCloseButton:"'.$ajax_close_button.'",'."\n";
        echo '        ajaxContentSliding:"'.$ajax_content_sliding.'",'."\n";
        if($ajax_callback !== '') echo '        ajaxCallback:"'.stripslashes($ajax_callback).'",'."\n";
        if($ajax_css_url !== '') echo '        ajaxCssUrl:"'.$ajax_css_url.'",'."\n";
        if($ajax_js_url !== '') echo '        ajaxJsUrl:"'.$ajax_js_url.'",'."\n";
        if($ajax_scroll_onload !== 'off') echo  '        ajaxScrollToOnLoad:"on",'."\n";

        if($ajax_callback_argument === 'on' || $ajax_callback_argument == 'true') echo  '        ajaxCallbackArgument:"on",'."\n";
        else  echo  '        ajaxCallbackArgument:"off",'."\n";

        echo '        ajaxNavButton:"'.$ajax_button_nav.'",'."\n";
        echo '        ajaxCloseType:"'.$ajax_button_type.'",'."\n";
        if($ajax_button_type == 'type2'){
            echo '        ajaxCloseTxt:"'.$ajax_button_text.'",'."\n";
        }
        echo '        ajaxCloseInner:"'.$ajax_button_inner.'",'."\n";
        echo '        ajaxCloseStyle:"'.$ajax_button_skin.'",'."\n";

        $ajax_button_h_pos = $base->getVar($this->grid_params, 'ajax-button-h-pos', 'r');
        $ajax_button_v_pos = $base->getVar($this->grid_params, 'ajax-button-v-pos', 't');
        if($ajax_button_h_pos == 'c'){
            echo '        ajaxClosePosition:"'.$ajax_button_v_pos.'",'."\n";
        }else{
            echo '        ajaxClosePosition:"'.$ajax_button_v_pos.$ajax_button_h_pos.'",'."\n";
        }

        echo '        space:'.$space.','."\n";
        echo '        pageAnimation:"'.$page_animation.'",'."\n";

        // 2.2.5
        echo '        startAnimation: "' . $start_animation . '",'."\n";
        echo '        startAnimationSpeed: ' . $start_animation_speed . ','."\n";
        echo '        startAnimationDelay: ' . $start_animation_delay . ','."\n";
        echo '        startAnimationType: "' . $start_animation_type . '",'."\n";
        echo '        animationType: "' . $animation_type . '",'."\n";

        if($pagination_numbers == 'full')
            echo '        smartPagination:"off",'."\n";

        echo '        paginationScrollToTop:"'.$pagination_scroll.'",'."\n";
        if($pagination_scroll == 'on'){
            echo '        paginationScrollToOffset:'.$pagination_scroll_offset.','."\n";
        }

        echo '        paginationAutoplay:"'.$pagination_autoplay.'",'."\n";
        if($pagination_autoplay == 'on'){
            echo '        paginationAutoplayDelay:'.$pagination_autoplay_delay.','."\n";
        }

        echo '        spinner:"spinner'.$spinner.'",'."\n";
        if($media_filter_type != 'none') echo '        mediaFilter:"'.esc_attr($media_filter_type).'",'."\n";

        if($spinner != '0' && $spinner != '5')
            echo '        spinnerColor:"'.$spinner_color.'",'."\n";

        if($layout_sizing == 'fullwidth'){
            echo '        forceFullWidth:"on",'."\n";
        }elseif($layout_sizing == 'fullscreen'){
            echo '        forceFullScreen:"on",'."\n";
            if($layout_offset_container !== ''){
                echo '        fullScreenOffsetContainer:"'.$layout_offset_container.'",'."\n";
            }
        }

        if($layout == 'even')
            echo '        evenGridMasonrySkinPusher:"'.$content_push.'",'."\n";

        echo '        lightBoxMode:"'.$lightbox_mode.'",'."\n";

        /* 2.2 */
        echo '		lightboxHash:"'.$lightbox_deep_link.'",'."\n";
        echo '		lightboxPostMinWid:"'.$lb_post_max_width.'",'."\n";
        echo '		lightboxPostMaxWid:"'.$lb_post_min_width.'",'."\n";

        /* 2.1.6 */
        echo '        lightboxSpinner:"'.$lb_post_spinner.'",'."\n";
        echo '        lightBoxFeaturedImg:"'.$lb_featured_img.'",'."\n";
        if($lb_featured_img === 'on') {
            echo '        lightBoxFeaturedPos:"'.$lb_featured_pos.'",'."\n";
            echo '        lightBoxFeaturedWidth:"'.$lb_featured_width.'",'."\n";
            echo '        lightBoxFeaturedMargin:"'.$lb_featured_margin.'",'."\n";
        }
        echo '        lightBoxPostTitle:"'.$lb_post_title.'",'."\n";
        echo '        lightBoxPostTitleTag:"'.$lb_post_title_tag.'",'."\n";
        echo '		lightboxMargin : "'.$lbox_margin . '",'."\n";
        echo '		lbContentPadding : "'.$lbox_inpadding . '",'."\n";
        echo '		lbContentOverflow : "'.$lbox_overflow . '",'."\n";

        if(!empty($cobbles_pattern) && $layout == 'cobbles' && $use_cobbles_pattern == 'on'){
            echo '        cobblesPattern:"'.implode(',', $cobbles_pattern).'",'."\n";
        }
        echo '        animSpeed:'.$anim_speed.','."\n";
        echo '        delayBasic:'.$delay_basic.','."\n";
        echo '        mainhoverdelay:'.$delay_hover.','."\n";

        echo '        filterType:"'.$filter_type.'",'."\n";

        if($filter_type == 'multi'){
            echo '        filterLogic:"'.$filter_logic.'",'."\n";
        }
        echo '        showDropFilter:"'.$filter_show_on.'",'."\n";

        echo '        filterGroupClass:"adamlabsgallery-fgc-'.$this->grid_id.'",'."\n";

        // 2.2
        echo '        filterNoMatch:"'.$no_filter_match_message.'",'."\n";
        echo '        filterDeepLink:"'.$filter_deep_linking.'",'."\n";

        // 2.2.5
        echo '        hideMarkups: "' . $hide_markup_before_load . '",' . "\n";
        echo '        inViewport: ' . $in_viewport . ',' . "\n";
        echo '        viewportBuffer: ' . $viewport_buffer . ',' . "\n";
        echo '        youtubeNoCookie:"'.get_option('adamlabsgallery_enable_youtube_nocookie', 'false').'",'."\n";
        echo '        convertFilterMobile:' . $filter_mobile_conversion . ',' . "\n";

        // 2.2.6
        echo '        paginationSwipe: "' . $touchswipe . '",' . "\n";
        echo '        paginationDragVer: "' . $dragvertical . '",' . "\n";
        echo '        pageSwipeThrottle: ' . $swipebuffer . ',' . "\n";


        if($wait_for_fonts === 'true'){
            $tf_fonts = new AdamLabs_Fonts();
            $fonts = $tf_fonts->get_all_fonts();
            if(!empty($fonts)){
                $first = true;
                $font_string = '[';
                foreach($fonts as $font){
                    if($first === false) $font_string.= ',';
                    $font_string.= "'".esc_attr($font['url'])."'";
                    $first = false;
                }
                $font_string.= ']';
                echo '        googleFonts:'.$font_string.','."\n";
            }
        }

        if($cookie_search === 'on' || $cookie_filter === 'on' || $cookie_pagination === 'on'){
            echo '        cookies: {'."\n";
            if($cookie_search == 'on') echo '                search:"'.$cookie_search.'",'."\n";
            if($cookie_filter == 'on') echo '                filter:"'.$cookie_filter.'",'."\n";
            if($cookie_pagination == 'on') echo '                pagination:"'.$cookie_pagination.'",'."\n";
            echo '                timetosave:"'.$cookie_time.'"'."\n";
            echo '        },'."\n";
        }

        if($layout != 'masonry' || $layout == 'masonry' && $auto_ratio != 'true'){
            echo '        aspectratio:"'.$aspect_ratio_x.':'.$aspect_ratio_y.'",'."\n";
        }

        // 2.2.6
        echo '        hideBlankItemsAt: "' . $hide_blankitems_at . '",' . "\n";

        echo '        responsiveEntries: ['."\n";
        echo '						{ width:'.$columns_width['0'].',amount:'.$columns['0'].',mmheight:'.$masonry_content_height['0'].'},'."\n";
        echo '						{ width:'.$columns_width['1'].',amount:'.$columns['1'].',mmheight:'.$masonry_content_height['1'].'},'."\n";
        echo '						{ width:'.$columns_width['2'].',amount:'.$columns['2'].',mmheight:'.$masonry_content_height['2'].'},'."\n";
        echo '						{ width:'.$columns_width['3'].',amount:'.$columns['3'].',mmheight:'.$masonry_content_height['3'].'},'."\n";
        echo '						{ width:'.$columns_width['4'].',amount:'.$columns['4'].',mmheight:'.$masonry_content_height['4'].'},'."\n";
        echo '						{ width:'.$columns_width['5'].',amount:'.$columns['5'].',mmheight:'.$masonry_content_height['5'].'},'."\n";
        echo '						{ width:'.$columns_width['6'].',amount:'.$columns['6'].',mmheight:'.$masonry_content_height['6'].'}'."\n";
        echo '						]';

        if($columns_advanced == 'on')
            $this->output_ratio_list();

        echo "\n";

        echo '	});'."\n\n";

        /* 2.2 */
        /* lightbox options written first, then custom JS from grid can override them if desired */
        echo '	var arrows = ' . $lightbox_arrows . ','."\n";
        echo '        lightboxOptions = {'."\n";

        echo '		margin : ['.$lbox_padding[0].','.$lbox_padding[1].','.$lbox_padding[2].','.$lbox_padding[3].'],'."\n";
        echo '		buttons : ["'.implode($lb_button_order, '","').'"],'."\n";
        echo '		infobar : '.$lbox_numbers.','."\n";
        echo '		loop : '.$lbox_loop.','."\n";
        echo '		slideShow : {"autoStart": ' . $lbox_autoplay . ', "speed": ' . $lbox_playspeed . '},'."\n";

        echo '		animationEffect : '.$lightbox_effect_open_close.','."\n";
        echo '		animationDuration : '.$lightbox_effect_open_close_speed.','."\n";

        echo '		beforeShow: function(a, c) {'."\n";
        echo '          if(!arrows) {'."\n";
        echo '              jQuery("body").addClass("adamlabsgallerybox-hidearrows");'."\n";
        echo '          }'."\n";
        echo '			var i = 0,'."\n";
        echo '				multiple = false;'."\n";
        echo '			a = a.slides;'."\n";
        echo '			for(var b in a) {'."\n";
        echo '				i++;'."\n";
        echo '				if(i > 1) {'."\n";
        echo '					multiple = true;'."\n";
        echo '					break;'."\n";
        echo '				}'."\n";
        echo '			}'."\n";
        echo '			if(!multiple) jQuery("body").addClass("adamlabsgallerybox-single");'."\n";
        echo '			if(c.type === "image") jQuery(".adamlabsgallerybox-button--zoom").show();'."\n";
        echo '		},'."\n";

        echo '		beforeLoad: function(a, b) {'."\n";
        echo '			jQuery("body").removeClass("adamlabsgallery-four-by-three");'."\n";
        echo '			if(b.opts.$orig.data("ratio") === "4:3") jQuery("body").addClass("adamlabsgallery-four-by-three");'."\n";
        echo '		},'."\n";

        echo '		afterLoad: function() {jQuery(window).trigger("resize.adamlabsgallerylb");},'."\n";
        echo '		afterClose : function() {jQuery("body").removeClass("adamlabsgallerybox-hidearrows adamlabsgallerybox-single");},'."\n";

        echo '		transitionEffect : '.$lightbox_effect_next_prev.','."\n";
        echo '		transitionDuration : '.$lightbox_effect_next_prev_speed.','."\n";

        echo '		hash : "'.$lightbox_deep_link.'",'."\n";
        echo '		arrows : '.$lightbox_arrows.','."\n";
        echo '		wheel : '.$lightbox_mousewheel.','."\n";

        echo '	};'."\n\n";

        echo '	jQuery("#adamlabsgallery-grid-'.$this->grid_div_name.'-'.$adamlabsgallery_grid_serial.'").data("lightboxsettings", lightboxOptions);'."\n\n";

        //output custom javascript if any is set
        $custom_javascript = stripslashes($base->getVar($this->grid_params, 'custom-javascript', ''));
        if($custom_javascript !== ''){
            echo $custom_javascript;
        }

        do_action('adamlabsgallery_output_grid_javascript_custom', $this);
        echo "\n";

        //check if lightbox is active
        $opt = get_option('adamlabsgallery_use_lightbox', 'false');
        if($load_lightbox && !AdamLabsGallery_Jackbox::is_active() && !AdamLabsGallery_Social_Gallery::is_active() && $opt !== 'disabled') {
            echo '	try{'."\n";
            echo '	    jQuery("#adamlabsgallery-grid-'.$this->grid_div_name.'-'.$adamlabsgallery_grid_serial.' .adamlabsgallerybox").adamlabsgallerybox(lightboxOptions);'."\n";
            echo '    } catch (e) {}'."\n"."\n";
        }

        echo '});'."\n";
        echo '</script>'."\n";

        if($js_to_footer && $is_demo == false){
            $js_content = ob_get_contents();
            ob_clean();
            ob_end_clean();

            $this->grid_inline_js = $js_content;

            add_action('wp_footer', array($this, 'add_inline_js'));
        }

    }


    /**
     * Output the Load More list of posts
     */
    public function output_load_more_list(){

        if(!empty($this->load_more_post_array)){
            $wrap_first = true;
            echo '[';

            foreach($this->load_more_post_array as $id => $filter){
                echo (!$wrap_first) ? ','."\n" : "\n";

                echo '				['.$id.', [-1, ';

                if(!empty($filter)){
                    $slug_first = true;
                    foreach($filter as $slug_id => $slug){
                        echo (!$slug_first) ? ', ' : '';

                        if(intval($slug_id == 0)) $slug_id = "'".$slug_id."'";
                        echo $slug_id;

                        $slug_first = false;
                    }
                }

                echo ']]';

                $wrap_first = false;
            }

            echo ']';
        }else{
            echo '[]';
        }
    }


    /**
     * Output the custom row sizes if its set
     */
    public function output_ratio_list(){
        $base = new AdamLabsGallery_Base;

        $columns = $base->getVar($this->grid_params, 'columns', ''); //this is the first line
        $columns = $base->set_basic_colums($columns);

        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-0', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-1', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-2', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-3', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-4', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-5', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-6', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-7', '');
        $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-8', '');
        //$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-9', '');

        $found_rows = 0;
        foreach($columns_advanced as $adv_key => $adv){
            if(empty($adv)) continue;
            $found_rows++;
        }

        if($found_rows > 0){
            echo ','."\n";
            echo '		rowItemMultiplier: ['."\n";

            echo '						[';
            echo $columns[0].',';
            echo $columns[1].',';
            echo $columns[2].',';
            echo $columns[3].',';
            echo $columns[4].',';
            echo $columns[5].',';
            echo $columns[6];
            echo ']';

            foreach($columns_advanced as $adv_key => $adv){
                if(empty($adv)) continue;

                echo ','."\n";
                echo '						[';

                $entry_first = true;
                foreach($adv as $val){
                    echo (!$entry_first) ? ',' : '';
                    echo $val;
                    $entry_first = false;
                }

                echo ']';
            }

            echo "\n".'						]';
        }
    }


    /**
     * check if post is visible in grid
     */
    public function check_if_visible($post_id, $grid_id){
        $pr_visibility = json_decode(get_post_meta($post_id, 'eg_visibility', true), true);

        $is_visible = true;

        if(!empty($pr_visibility) && is_array($pr_visibility)){ //check if element is visible in grid
            foreach($pr_visibility as $pr_grid => $pr_setting){
                if($pr_grid == $grid_id){
                    if($pr_setting == false)
                        $is_visible = false;
                    else
                        $is_visible = true;
                    break;
                }
            }
        }

        return apply_filters('adamlabsgallery_check_if_visible', $is_visible, $post_id, $grid_id);
    }


    /**
     * Output Filter from current Grid (used for Widgets)
     */
    public function output_grid_filter(){

        do_action('adamlabsgallery_output_grid_filter_pre', $this);

        switch($this->grid_postparams['source-type']){
            case 'post':
                $this->output_filter_by_posts();
                break;
            case 'custom':
                $this->output_filter_by_custom();
                break;
            case 'streams':
                break;
        }

        do_action('adamlabsgallery_output_grid_filter_post', $this);

    }


    /**
     * Output Sorting from current Grid (used for Widgets)
     */
    public function output_grid_sorting(){

        do_action('adamlabsgallery_output_grid_sorting_pre', $this);

        switch($this->grid_postparams['source-type']){
            case 'post':
                $this->output_sorting_by_posts();
                break;
            case 'custom':
                $this->output_sorting_by_custom();
                break;
            case 'streams':
                break;
        }

        do_action('adamlabsgallery_output_grid_sorting_post', $this);

    }


    /**
     * Output Sorting from post based
     */
    public function output_sorting_by_posts(){
        do_action('adamlabsgallery_output_sorting_by_posts_pre', $this);

        $this->output_sorting_by_all_types();

        do_action('adamlabsgallery_output_sorting_by_posts_post', $this);
    }


    /**
     * Output Sorting from custom grid
     */
    public function output_sorting_by_custom(){
        do_action('adamlabsgallery_output_sorting_by_custom_pre', $this);

        $this->output_sorting_by_all_types();

        do_action('adamlabsgallery_output_sorting_by_custom_post', $this);
    }


    /**
     * Output Sorting from custom grid
     */
    public function output_sorting_by_all_types(){
        do_action('adamlabsgallery_output_sorting_by_all_types', $this);

        $base = new AdamLabsGallery_Base();
        $nav = new AdamLabsGallery_Navigation();
        $m = new AdamLabsGallery_Meta();

        $order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
        if(!is_array($order_by)) $order_by = array($order_by);

        $order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
        if(strpos($order_by_start, 'adamlabsgallery-') === 0 || strpos($order_by_start, 'adamlabsgalleryl-') === 0){ //add meta at the end for meta sorting
            //if essential Meta, replace to meta name. Else -> replace - and _ with space, set each word uppercase
            $metas = $m->get_all_meta();
            $f = false;
            if(!empty($metas)){
                foreach($metas as $meta){
                    if('adamlabsgallery-'.$meta['handle'] == $order_by_start || 'adamlabsgalleryl-'.$meta['handle'] == $order_by_start){
                        $f = true;
                        $order_by_start = $meta['name'];
                        break;
                    }
                }
            }

            if($f === false){
                $order_by_start = ucwords(str_replace(array('-', '_'), array(' ', ' '), $order_by_start));
            }
        }

        $nav->set_orders($order_by); //set order of filter
        $nav->set_orders_start($order_by_start); //set order of filter

        echo $nav->output_sorting();
    }


    /**
     * Output Filter from post based
     */
    public function output_filter_by_posts(){
        do_action('adamlabsgallery_output_filter_by_posts', $this);

        $base = new AdamLabsGallery_Base();
        $nav = new AdamLabsGallery_Navigation();

        $filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_start = $base->getVar($this->grid_params,'filter-start', '');
        $filterall_visible = $base->getVar($this->grid_params, 'filter-all-visible', 'on');
        $filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN));
        $filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
        $show_count = $base->getVar($this->grid_params, 'filter-counter', 'off');

        $nav->set_filter_text($filter_all_text);
        $nav->set_filterall_visible($filterall_visible);
        $nav->set_dropdown_text($filter_dropdown_text);
        $nav->set_show_count($show_count);

        $start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
        $start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');

        $post_category = $base->getVar($this->grid_postparams, 'post_category');
        $post_types = $base->getVar($this->grid_postparams, 'post_types');
        $page_ids = explode(',', $base->getVar($this->grid_postparams, 'selected_pages', '-1'));
        $cat_relation = $base->getVar($this->grid_postparams, 'category-relation', 'OR');

        $additional_query = $base->getVar($this->grid_postparams, 'additional-query', '');
        if($additional_query !== '')
            $additional_query = wp_parse_args($additional_query);

        $cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category);

        $posts = AdamLabsGallery_Base::getPostsByCategory($this->grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, -1, $additional_query, true, $cat_relation);

        $nav_filters = array();

        $taxes = array('post_tag');
        if(!empty($cat_tax['tax']))
            $taxes = explode(',', $cat_tax['tax']);

        if(!empty($cat_tax['cats'])){
            $cats = explode(',', $cat_tax['cats']);

            foreach($cats as $key => $id){
                $cat = get_category($id);
                if(is_object($cat))	$nav_filters[$id] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug));

                foreach($taxes as $custom_tax){
                    $term = get_term_by('id', $id, $custom_tax);
                    if(is_object($term)) $nav_filters[$id] = array('name' => $term->name, 'slug' => sanitize_key($term->slug));
                }
            }

            asort($nav_filters);
        }


        $found_filter = array();
        if(!empty($posts) && count($posts) > 0){
            foreach($posts as $key => $post){
                //check if post should be visible or if its invisible on current grid settings
                $is_visible = $this->check_if_visible($post['ID'], $this->grid_id);
                if($is_visible == false) continue; // continue if invisible

                $filters = array();

                //$categories = get_the_category($post['ID']);
                $categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
                //$tags = wp_get_post_terms($post['ID']);
                $tags = get_the_tags($post['ID']);

                if(!empty($categories)){
                    foreach($categories as $key => $category){
                        $filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug));
                    }
                }

                if(!empty($tags)){
                    foreach($tags as $key => $taxonomie){
                        $filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug));
                    }
                }

                $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
            }
        }

        $remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
        if(!empty($remove_filter)){
            foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
                unset($nav_filters[$key]);
            }
        }

        $nav->set_filter($nav_filters); //set filters $nav_filters $found_filter
        $nav->set_filter_type($filter_allow);
        $nav->set_filter_start_select($filter_start);

        echo $nav->output_filter();

    }


    /**
     * Output Filter from custom grid
     */
    public function output_filter_by_custom(){
        do_action('adamlabsgallery_output_filter_by_custom', $this);

        $base = new AdamLabsGallery_Base();
        $nav = new AdamLabsGallery_Navigation();

        $filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_start = $base->getVar($this->grid_params, 'filter-start', '');
        $filterall_visible = $base->getVar($this->grid_params, 'filter-all-visible', 'on');
        $filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN));
        $filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN));
        $show_count = $base->getVar($this->grid_params, 'filter-counter', 'off');

        $nav->set_dropdown_text($filter_dropdown_text);
        $nav->set_show_count($show_count);

        $nav->set_filter_text($filter_all_text);
        $nav->set_filterall_visible($filterall_visible);

        $found_filter = array();

        if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
            foreach($this->grid_layers as $key => $entry){
                $filters = array();

                if(!empty($entry['custom-filter'])){
                    $cats = explode(',', $entry['custom-filter']);
                    if(!is_array($cats)) $cats = (array)$cats;
                    foreach($cats as $category){
                        $filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));

                        $found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have

                    }
                }
            }
        }

        $nav->set_filter($found_filter); //set filters $nav_filters $found_filter
        $nav->set_filter_type($filter_allow);
        $nav->set_filter_start_select($filter_start);

        echo $nav->output_filter();

    }

    /**
     * Output Ajax Container
     */
    public function output_ajax_container(){

        $base = new AdamLabsGallery_Base();

        $container_id = $base->getVar($this->grid_params, 'ajax-container-id', '');
        $container_css = $base->getVar($this->grid_params, 'ajax-container-css', '');

        $container_pre = $base->getVar($this->grid_params, 'ajax-container-pre', '');
        $container_post = $base->getVar($this->grid_params, 'ajax-container-post', '');

        $cont = '';
        $cont .= '<div class="adamlabsgallery-ajax-target-container-wrapper" id="'.$container_id.'">'."\n";
        //$cont .= '	<!-- CONTAINER FOR PREFIX -->'."\n";
        $cont .= '	<div class="adamlabsgallery-ajax-target-prefix-wrapper">'."\n";
        $cont .= html_entity_decode($container_pre);
        $cont .= '	</div>'."\n";
        //$cont .= '	<!-- CONTAINER FOR CONTENT TO LOAD -->'."\n";
        $cont .= '	<div class="adamlabsgallery-ajax-target"></div>'."\n";
        //$cont .= '	<!-- CONTAINER FOR SUFFIX -->'."\n";
        $cont .= '	<div class="adamlabsgallery-ajax-target-sufffix-wrapper">'."\n";
        $cont .= html_entity_decode($container_post);
        $cont .= '	</div>'."\n";
        $cont .= '</div>'."\n";

        if($container_css !== '' && $container_id !== ''){
            //$cont .= '<!-- CONTAINER CSS -->'."\n";
            $cont .= '<style type="text/css">'."\n";
            $cont .= '#'.$container_id.' {'."\n";
            $cont .= $container_css;
            $cont .= '}'."\n";
            $cont .= '</style>';
        }

        $cont = do_shortcode($cont);
        return apply_filters('adamlabsgallery_output_ajax_container', $cont, $this);
    }


    /**
     * Output Inline JS
     */
    public function add_inline_js(){

        echo apply_filters('adamlabsgallery_add_inline_js', $this->grid_inline_js);

    }


    /**
     * Check the maximum entries that should be loaded
     */
    public function get_maximum_entries($grid){
        $base = new AdamLabsGallery_Base();

        $max_entries = intval($grid->get_postparam_by_handle('max_entries', '-1'));

        //2.2
        if(is_admin()) $max_entries = intval($grid->get_postparam_by_handle('max_entries_preview', '-1'));

        if($max_entries !== -1) return $max_entries;

        $layout = $grid->get_param_by_handle('navigation-layout', array());

        if(isset($layout['pagination']) || isset($layout['left']) || isset($layout['right'])) return $max_entries;

        $rows_unlimited = $grid->get_param_by_handle('rows-unlimited', 'on');

        $load_more = $grid->get_param_by_handle('load-more', 'none');
        $rows = intval($grid->get_param_by_handle('rows', '3'));

        $columns_advanced = $grid->get_param_by_handle('columns-advanced', 'off');

        $columns = $grid->get_param_by_handle('columns', ''); //this is the first line
        $columns = $base->set_basic_colums($columns);

        $max_column = 0;
        foreach($columns as $column){
            if($max_column < $column) $max_column = $column;
        }

        if($columns_advanced === 'on'){
            $columns_advanced = array();
            $columns_advanced[] = $columns;
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-0', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-1', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-2', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-3', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-4', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-5', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-6', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-7', '');
            $columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-8', '');

            $match = array(0,0,0,0,0,0,0);
            for($i=0;$i<=$rows;$i++){
                foreach($columns_advanced as $col_adv){
                    if(!empty($col_adv)){
                        foreach($col_adv as $key => $val){
                            $match[$key] += $val;
                        }
                        $i++;
                    }
                    if($i>=$rows) break;
                }
            }

            foreach($match as $highest){
                if($max_column < $highest) $max_column = $highest;
            }

        }

        if($rows_unlimited === 'off'){
            if($columns_advanced === 'off'){
                $max_entries = $max_column * $rows;
            }else{
                $max_entries = $max_column;
            }
        }elseif($rows_unlimited === 'on' && $load_more === 'none'){
            //@disabled at 2.0 -> will not work as expeced, all elements should be loaded here
            //$max_entries = $max_column;
        }

        $max_entries_number = apply_filters('adamlabsgallery_get_maximum_entries', $max_entries, $this, $grid);

        return $max_entries_number;
    }


    /**
     * Adds functionality for authors to modify things at activation of plugin
     */
    public static function activation_hooks($networkwide = false){
        //set all starting options
        $options = array();
        $options = apply_filters('adamlabsgallery_mod_activation_option', $options);
        if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
            global $wpdb;

            // 2.2.5
            // $old_blog = $wpdb->blogid;

            // Get all blog ids and create tables
            $blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

            foreach($blogids as $blog_id){

                switch_to_blog($blog_id);

                foreach($options as $opt => $val){
                    update_option('adamlabsgallery_'.$opt, $val);
                }

                // 2.2.5
                restore_current_blog();

            }

            // 2.2.5
            // switch_to_blog($old_blog); //go back to correct blog

        }else{

            foreach($options as $opt => $val){
                update_option('adamlabsgallery_'.$opt, $val);
            }

        }

    }

    /**
     * Adds default Grids at installation process
     */
    public static function propagate_default_grids(){

        $default_grids = array();

        $default_grids = apply_filters('adamlabsgallery_add_default_grids', $default_grids);

        if(!empty($default_grids)){
            $im = new AdamLabsGallery_Import();
            $im->import_grids($default_grids);
        }

    }


    /**
     * Hide Load More button
     */
    public function remove_load_more_button($grid_id_wrap){
        $base = new AdamLabsGallery_Base();

        $css = '';

        if($base->getVar($this->grid_params, 'load-more-hide', 'off') == 'on' && $base->getVar($this->grid_params, 'load-more', 'none') == 'scroll'){
            $css = '<style type="text/css">';
            $css .= '
#'.$grid_id_wrap.' .adamlabsgallery-loadmore { display: none !important; }';
            $css .= '</style>';

        }

        echo apply_filters('adamlabsgallery_remove_load_more_button', $css, $grid_id_wrap);
    }


    /**
     * Adds start height CSS for the Grid, to prevent jumping of Site on loading
     */
    public function add_start_height_css($grid_id_wrap){
        $base = new AdamLabsGallery_Base();

        $columns_advanced = $base->getVar($this->grid_params, 'columns-advanced', 'off');
        if($columns_advanced == 'on'){
            $columns_width = $base->getVar($this->grid_params, 'columns-width', '');
            $columns_height = $base->getVar($this->grid_params, 'columns-height', '');
            $columns_width = $base->set_basic_colums_height($columns_width);
            $columns_height = $base->set_basic_colums_height($columns_height);

            // 2.2.5
            if(!is_array($columns_width)) $columns_width = array(0, 0, 0, 0, 0, 0);
            if(!is_array($columns_height)) $columns_height = array(0, 0, 0, 0, 0, 0);

            $col_height = array_reverse($columns_height); //reverse to start with lowest value
            $col_width = array_reverse($columns_width); //reverse to start with lowest value

            $first = true;

            $css = '<style type="text/css">';
            foreach($col_height as $key => $height){
                if($height > 0){
                    $height = intval($height);
                    $mw = intval($col_width[$key] - 1);
                    if($first){ //first set up without restriction of width
                        $first = false;
                        $css .= '
#'.$grid_id_wrap.'.adamlabsgallery-startheight{ height: '.$height.'px; }';
                    }else{
                        $css .= '
@media only screen and (min-width: '.$mw.'px) {
	#'.$grid_id_wrap.'.adamlabsgallery-startheight{ height: '.$height.'px; }
}';
                    }
                }
            }
            $css .= '</style>';

            echo $css."\n";

            if($css !== '<style type="text/css"></style>') return true;

        }

        return false;
    }


    /**
     * Does the uninstall process, also multisite checks
     */
    public static function uninstall_plugin($networkwide = false){
        // If uninstall not called from WordPress, then exit
        if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
            exit;
        }

        global $wpdb;

        if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
            global $wpdb;

            // 2.2.5
            // $old_blog = $wpdb->blogid;

            // Get all blog ids and create tables
            $blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

            foreach($blogids as $blog_id){

                switch_to_blog($blog_id);
                self::_uninstall_plugin();

                // 2.2.5
                restore_current_blog();

            }

            // 2.2.5
            // switch_to_blog($old_blog); //go back to correct blog

        }else{
            self::_uninstall_plugin();
        }

    }


    /**
     * Does the uninstall process
     */
    public static function _uninstall_plugin(){
        // If uninstall not called from WordPress, then exit
        if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
            exit;
        }

        global $wpdb;

        //Delete Database Tables
        $wpdb->query( "DROP TABLE ". $wpdb->prefix . AdamLabsGallery::TABLE_GRID);
        $wpdb->query( "DROP TABLE ". $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_SKIN);
        $wpdb->query( "DROP TABLE ". $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS);
        $wpdb->query( "DROP TABLE ". $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS);

        //Delete Options
        delete_option('adamlabsgallery_role');
        delete_option('adamlabsgallery_grids_version');
        delete_option('adamlabsgallery_custom_css');

        delete_option('adamlabsgallery_output_protection');
        delete_option('adamlabsgallery_tooltips');
        delete_option('adamlabsgallery_wait_for_fonts');
        delete_option('adamlabsgallery_js_to_footer');
        delete_option('adamlabsgallery_use_cache');
        delete_option('adamlabsgallery_query_type');
        delete_option('adamlabsgallery_enable_log');
        delete_option('adamlabsgallery_use_lightbox');

        delete_option('adamlabsgallery_update-check');
        delete_option('adamlabsgallery_update-check-short');
        delete_option('adamlabsgallery_latest-version');
        delete_option('adamlabsgallery_code');
        delete_option('adamlabsgallery_valid');
        delete_option('adamlabsgallery_valid-notice');

        delete_option('adamlabsgallery-widget-areas');
        delete_option('adamlabsgallery-custom-meta');
        delete_option('adamlabsgallery-custom-link-meta');
        delete_option('adamlabsgallery-search-settings');

        delete_option('adamlabsgallery_custom_css_imported');

        do_action('adamlabsgallery__uninstall_plugin');

    }

    /* format lightbox post content wrapper */
    public static function on_lightbox_post_content($settings, $id) {

        $content = '';
        if(!empty($settings)) {

            $settings = json_decode(stripslashes($settings), true);
            if(empty($settings)) return '';

            $featured = $settings['featured'];
            $titl = $settings['titl'];
            $lbTitle = $settings['lbTitle'];
            $lbTag = $settings['lbTag'];
            $lbImg = $settings['lbImg'];

            $wid = $settings['lbWidth'];
            $lbPos = $settings['lbPos'];

            $minW = $settings['lbMin'];
            $maxW = $settings['lbMax'];

            $margin = $settings['margin'];
            $margin = explode('|', $margin);

            $padding = $settings['padding'];
            $padding = explode('|', $padding);

            $overflow = $settings['overflow'];

            if(!empty($margin) && count($margin) === 4) {
                $margin = $margin[0] . 'px ' . $margin[1] . 'px ' . $margin[2] . 'px ' . $margin[3] . 'px';
            }
            else {
                $margin = '0';
            }

            if(!empty($padding) && count($padding) === 4) {
                $padding = $padding[0] . 'px ' . $padding[1] . 'px ' . $padding[2] . 'px ' . $padding[3] . 'px';
            }
            else {
                $padding = '0';
            }

            $html = '<div class="adamlabsgallery-lightbox-post-content" style="width: ' . $maxW . ';min-width: ' . $minW . '; max-width: ' . $maxW . '; margin: ' . $margin . '">' .
                '<div class="adamlabsgallery-lightbox-post-content-inner" style="padding: ' . $padding . '; overflow: ' . $overflow . '">';

            if(isset($settings['revslider']) && !empty($settings['revslider']) && class_exists('RevSlider')) {

                $slider_id = $settings['revslider'];
                if(is_numeric($slider_id)) {

                    $rev_slider = new RevSlider();
                    if(method_exists($rev_slider, 'getAllSliderForAdminMenu')) {

                        $sliders = $rev_slider->getAllSliderForAdminMenu();
                        if(!empty($sliders) && array_key_exists($slider_id, $sliders)) {

                            $slider = $sliders[$slider_id];
                            if(!empty($slider)) {

                                if(isset($slider['alias']) && !empty($slider['alias'])) {

                                    $slider = $slider['alias'];
                                    $content = do_shortcode('[rev_slider alias="' . $slider . '"][/rev_slider]');
                                    if($content) return $html . $content . '</div></div>';

                                }
                            }
                        }
                    }
                }
            }
            else if(isset($settings['adamlabsgallery']) && !empty($settings['adamlabsgallery'])) {

                $adamlabsgallery_alias = $settings['adamlabsgallery'];
                if(!is_numeric($adamlabsgallery_alias)) {

                    $grids = AdamLabsGallery::get_adamlabsgallery_grids();
                    foreach($grids as $grid) {

                        $alias = $grid -> handle;
                        if($alias === $adamlabsgallery_alias) {

                            $content = do_shortcode('[adamlabsgallery alias="' . $alias . '"][/adamlabsgallery]');
                            if($content) return $html . $content . '</div></div>';
                            break;

                        }

                    }
                }

            }
            else {

                if(isset($settings['ispost']) && !empty($settings['ispost']) && $id > 0) {
                    $raw_content = get_post_field('post_content', $id);
                }
                else {

                    $gridid = isset($settings['gridid']) ? $settings['gridid'] : false;
                    if(is_numeric($gridid)) {

                        $grid = new AdamLabsGallery();
                        $result = $grid->init_by_id($gridid);

                        if($result){

                            $itm = $grid->get_layer_values();
                            if(!empty($itm) && isset($itm[$id])) {

                                $itm = $itm[$id];
                                $raw_content = isset($itm['content']) && !empty($itm['content']) ? $itm['content'] : '';

                            }
                        }
                    }
                }

                if(!is_wp_error($raw_content)) {

                    $content = apply_filters('adamlabsgallery_the_content', $raw_content); //filter apply for qTranslate and other

                    if(method_exists('WPBMap','addAllMappedShortcodes')){
                        WPBMap::addAllMappedShortcodes();
                    }

                    $content = do_shortcode($content);

                }

            }

            if(!empty($titl) && $lbTitle === 'on') {
                if(empty($lbTag)) $lbTag = 'h2';
                $titl = '<' . $lbTag . '>' . stripslashes($titl) . '</' . $lbTag . '>';
            }
            else {
                $titl = '';
            }

            if(!empty($featured) && $lbImg === 'on') {

                $margin = $settings['lbMargin'];
                $margin = explode('|', $margin);

                if(!empty($margin) && count($margin) === 4) {
                    $margin = $margin[0] . 'px ' . $margin[1] . 'px ' . $margin[2] . 'px ' . $margin[3] . 'px';
                }
                else {
                    $margin = '0';
                }

                if(!is_numeric($wid)) $wid = 50;
                $wid = intval($wid);

                $dif = 100 - $wid;
                $dif = 'width: ' . $dif . '%';
                $wid = 'width: ' . $wid . '%';
                $featured = '<img class="adamlabsgallery-post-featured-img" src="' . $featured . '" style="width: 100%; height: auto; padding: ' . $margin . '" />';

                switch($lbPos) {

                    case 'top':
                        $html .= $featured . $titl . $content;
                        break;

                    case 'left':
                        $html .= '<div style="float: left; ' . $wid . '">' . $featured . '</div>';
                        $html .= '<div style="float: left; ' . $dif . '">' . $titl . $content . '</div>';
                        $html .= '<div style="clear: both"></div>';
                        break;

                    case 'right':
                        $html .= '<div style="float: left; ' . $dif . '">' . $titl . $content . '</div>';
                        $html .= '<div style="float: left; ' . $wid . '">' . $featured . '</div>';
                        $html .= '<div style="clear: both"></div>';
                        break;

                    case 'bottom':
                        $html .= $titl . $content . $featured;
                        break;

                }

            }
            else {
                $html .= $titl . $content;
            }

            return $html . '</div></div>';

        }

        return $content;

    }

    /**
     * Handle Ajax Requests
     */
    public static function on_front_ajax_action(){

        $base = new AdamLabsGallery_Base();

        $token = $base->getPostVar("token", false);

        $isVerified = true;

        $error = false;
        if($isVerified){
            $data = $base->getPostVar('data', false);
            //client_action: load_more_items

            $action = !isset($_GET['client_action']) ? $base->getPostVar('client_action', false) : $_GET['client_action'];

            switch($action){
                case 'load_more_items':
                    $gridid = $base->getPostVar('gridid', 0, 'i');
                    if(!empty($data) && $gridid > 0){
                        $grid = new AdamLabsGallery();

                        $result = $grid->init_by_id($gridid);
                        if(!$result){
                            $error = __('Grid not found', ADAMLABS_GALLERY_TEXTDOMAIN);
                        }else{
                            $grid->set_loading_ids($data); //set to only load choosen items
                            $html = false;

                            //check if we are custom grid
                            if( $grid->is_custom_grid() ){
                                $html = $grid->output_by_specific_ids();
                            }elseif($grid->is_stream_grid()){
                                $html = $grid->output_by_specific_stream();
                            }else{
                                $html = $grid->output_by_specific_posts();
                            }

                            /* 2.1.5 */
                            if(!empty($html)){
                                self::ajaxResponseData($html);
                            }else{
                                /* 2.1.5 */
                                $customGallery = $base->getPostVar('customgallery', false);
                                if(!empty($customGallery)) {
                                    $grid->custom_images = $data;
                                    $html = $grid->output_by_gallery(false, true, true);
                                }
                                if(!empty($html)){
                                    self::ajaxResponseData($html);
                                }
                                else {
                                    $error = __('Items Not Found', ADAMLABS_GALLERY_TEXTDOMAIN);
                                }
                            }
                        }
                    }else{
                        $error = __('No Data Received', ADAMLABS_GALLERY_TEXTDOMAIN);
                    }
                    break;
                case 'load_more_content':
                    $postid = $base->getPostVar('postid', 0, 'i');
                    if($postid > 0){
                        $raw_content = get_post_field('post_content', $postid);
                        if(!is_wp_error($raw_content)){
                            $content = apply_filters('adamlabsgallery_the_content', $raw_content); //filter apply for qTranslate and other

                            if(method_exists('WPBMap','addAllMappedShortcodes')){
                                WPBMap::addAllMappedShortcodes();
                            }
                            $content = do_shortcode($content);

                            self::ajaxResponseData($content);
                        }
                    }
                    $error = __('Post Not Found', ADAMLABS_GALLERY_TEXTDOMAIN);
                    break;
                case 'load_post_content':

                    $postid = isset($_GET['postid']) ? $_GET['postid'] : 0;
                    if(is_numeric($postid)) {
                        $settings = isset($_GET['settings']) ? $_GET['settings'] : false;
                        echo apply_filters('adamlabsgallery_lightbox_post_content', $settings, $postid); // lightbox post content
                        die();
                    }

                    $error = __('Post Not Found', ADAMLABS_GALLERY_TEXTDOMAIN);
                    break;
                case 'get_search_results':
                    $search_string = $base->getVar($data, 'search', '');
                    $search_skin = $base->getVar($data, 'skin', 0, 'i');
                    if($search_string !== '' && $search_skin > 0){
                        $search = new AdamLabsGallery_Search();

                        $return = $search->output_search_result($search_string, $search_skin);

                        self::ajaxResponseData($return);
                    }
                    $error = __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
                    break;
                case 'get_grid_search_ids':
                    $search_string = $base->getVar($data, 'search', '');
                    $grid_id = $base->getVar($data, 'id', 0, 'i');
                    if($search_string !== '' && $grid_id > 0){

                        $return = AdamLabsGallery_Search::output_search_result_ids($search_string, $grid_id);
                        if(!is_array($return)){
                            $error = $return;
                        }else{
                            self::ajaxResponseSuccess('', $return);
                        }
                    }
                    $error = __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
                    break;
            }

            $error = apply_filters('adamlabsgallery_on_front_ajax_action', $error, $data);
        }else{
            $error = true;
        }

        if($error !== false){
            $showError = __('Loading Error', ADAMLABS_GALLERY_TEXTDOMAIN);
            if($error !== true)
                $showError = $error;

            self::ajaxResponseError($showError, false);
        }
        exit();
    }


    /**
     * echo json ajax response
     */
    public static function ajaxResponse($success,$message,$arrData = null){

        $response = array();
        $response["success"] = $success;
        $response["message"] = $message;

        if(!empty($arrData)){

            if(gettype($arrData) == "string" || gettype($arrData) == "boolean")
                $arrData = array("data"=>$arrData);

            $response = array_merge($response,$arrData);
        }

        $json = json_encode($response);

        echo $json;
        exit();
    }


    /**
     * echo json ajax response, without message, only data
     */
    public static function ajaxResponseData($arrData){
        if(gettype($arrData) == "string")
            $arrData = array("data"=>$arrData);

        self::ajaxResponse(true,"",$arrData);
    }


    /**
     * echo json ajax response
     */
    public static function ajaxResponseError($message,$arrData = null){

        self::ajaxResponse(false,$message,$arrData,true);
    }


    /**
     * echo ajax success response
     */
    public static function ajaxResponseSuccess($message,$arrData = null){

        self::ajaxResponse(true,$message,$arrData,true);

    }


    /**
     * echo ajax success response
     */
    public static function ajaxResponseSuccessRedirect($message,$url){
        $arrData = array("is_redirect"=>true,"redirect_url"=>$url);

        self::ajaxResponse(true,$message,$arrData,true);
    }

    /**
     * Removes original WP Gallery Shortcode
     */
    public function remove_wp_gallery(){
        remove_shortcode('gallery', 'gallery_shortcode');
    }

    /**
     * Adds AdamLabsGallery instead of Gallery Shortcode
     */
    public function add_adamlabsgallery_gallery(){
        add_shortcode('gallery', array($this,'adamlabsgallery_addon_gallery'),10,2);
    }

    /**
     * Returns AdamLabsGallery for WP Gallery Shortcode Filter
     */
    public function use_adamlabsgallery_gallery($attr, $instance){

        if(!empty($instance['adamlabsgallery_gal'])) return $this->adamlabsgallery_addon_gallery($instance,$instance);
    }

    /**
     * Shortcode to wrap around the original gallery shortcode
     */
    public function adamlabsgallery_addon_gallery($output, $attr){
        //exits if other RevSlider functionality captures the gallery functionality
        if( isset($output["revslider_function"]) ) return false;

        // Columns and Grid Defaults
        $columns = isset($output['columns']) ? $output['columns'] : 3;
        $grid = isset($output['adamlabsgallery_gal']) ? $output['adamlabsgallery_gal'] : 'nogrid';
        $grid = isset($output['adamlabsgallery_gal']) ? $output['adamlabsgallery_gal'] : get_option('adamlabsgallery_overwrite_gallery','');
        $grid = isset($output['adamlabsgallery_custom_setting']) && $output['adamlabsgallery_custom_setting']=='on' ? 'nogrid' : $grid;
        // Random Order
        if( isset($output['orderby']) &&  $output['orderby'] == "rand" ){
            $ids = explode(",", $output['ids']);
            shuffle($ids);
            $output['ids'] = implode(",", $ids);
        }

        // Parse for Attributes
        $return = array();
        foreach($output as $attr_key => $attr_value){
            if(!in_array($attr_key, array("order_by","include") ))
                $return[] = $attr_key.'="'.$attr_value.'"';
        }
        $return = implode(" ", $return);

        if( !empty($grid) ){
            if($grid=="nogrid"){

                // Defaults for Param
                $entryskin = !empty($output['entryskin']) ? $output['entryskin'] : 1;
                $layoutsizing = !empty($output['layoutsizing']) ? $output['layoutsizing'] : 'boxed';
                $gridlayout = !empty($output['gridlayout']) ? $output['gridlayout'] : 'even';
                $spacings = !empty($output['spacings']) ? $output['spacings'] : 0;
                $rowsunlimited = !empty($output['rowsunlimited']) ? $output['rowsunlimited'] : 'off';
                $rows = !empty($output['rows']) ? $output['rows'] : 3;
                $gridanimation = !empty($output['gridanimation']) ? $output['gridanimation'] : 'fade';
                $usespinner = !empty($output['usespinner']) ? $output['usespinner'] : 0;

                //echo '[adamlabsgallery  settings=\'{"entry-skin":"'.$entryskin.'","layout-sizing":"'.$layoutsizing.'","grid-layout":"'.$gridlayout.'","spacings":"'.$spacings.'","rows-unlimited":"'.$rowsunlimited.'","columns":"'.$columns.'","rows":"'.$rows.'","grid-animation":"'.$gridanimation.'","use-spinner":"'.$usespinner.'"}\' alias="portfolio1"][gallery '.$return.'][/adamlabsgallery]';
                return do_shortcode('[adamlabsgallery  settings=\'{"entry-skin":"'.$entryskin.'","layout-sizing":"'.$layoutsizing.'","grid-layout":"'.$gridlayout.'","spacings":"'.$spacings.'","rows-unlimited":"'.$rowsunlimited.'","columns":"'.$columns.'","rows":"'.$rows.'","grid-animation":"'.$gridanimation.'","use-spinner":"'.$usespinner.'"}\' alias="'.get_option('adamlabsgallery_overwrite_gallery').'"][gallery '.$return.'][/adamlabsgallery]');
            }
            else{
                return do_shortcode('[adamlabsgallery  settings=\'{"columns":"'.$columns.'"}\' alias="'.$grid.'"][gallery '.$return.'][/adamlabsgallery]');
            }
        }
        else return false;
    }

    public static function custom_sorter_int($x, $y){
        global $adamlabsgallery_c_sort_direction, $adamlabsgallery_c_sort_handle;

        if(!isset($x[$adamlabsgallery_c_sort_handle])) $x[$adamlabsgallery_c_sort_handle] = 0;
        if(!isset($y[$adamlabsgallery_c_sort_handle])) $y[$adamlabsgallery_c_sort_handle] = 0;
        if(in_array($adamlabsgallery_c_sort_handle, array('date_modified','date','modified'))){
            $x[$adamlabsgallery_c_sort_handle] = strtotime($x[$adamlabsgallery_c_sort_handle]);
            $y[$adamlabsgallery_c_sort_handle] = strtotime($y[$adamlabsgallery_c_sort_handle]);
        } elseif ($adamlabsgallery_c_sort_handle == 'duration') {
            $x[$adamlabsgallery_c_sort_handle] = AdamLabsGallery::time_to_seconds($x[$adamlabsgallery_c_sort_handle]);
            $y[$adamlabsgallery_c_sort_handle] = AdamLabsGallery::time_to_seconds($y[$adamlabsgallery_c_sort_handle]);
        }


        if($adamlabsgallery_c_sort_direction == 'ASC'){
            return $x[$adamlabsgallery_c_sort_handle] - $y[$adamlabsgallery_c_sort_handle];
        }else{
            return $y[$adamlabsgallery_c_sort_handle] - $x[$adamlabsgallery_c_sort_handle];
        }
    }

    public static function time_to_seconds($time_string){
        $timeArr = array_reverse(explode(":", $time_string));
        $seconds = 0;
        foreach ($timeArr as $key => $value)
        {
            if ($key > 2) break;
            $seconds += pow(60, $key) * $value;
        }
        return $seconds;
    }

    public static function custom_sorter($x, $y){
        global $adamlabsgallery_c_sort_direction, $adamlabsgallery_c_sort_handle;

        if(!isset($x[$adamlabsgallery_c_sort_handle])) $x[$adamlabsgallery_c_sort_handle] = '';
        if(!isset($y[$adamlabsgallery_c_sort_handle])) $y[$adamlabsgallery_c_sort_handle] = '';

        if($adamlabsgallery_c_sort_direction == 'ASC'){
            return strcasecmp($x[$adamlabsgallery_c_sort_handle], $y[$adamlabsgallery_c_sort_handle]);
        }else{
            return strcasecmp($y[$adamlabsgallery_c_sort_handle], $x[$adamlabsgallery_c_sort_handle]);
        }
    }

    public function set_custom_sorter($handle, $direction){
        global $adamlabsgallery_c_sort_direction, $adamlabsgallery_c_sort_handle;

        $adamlabsgallery_c_sort_direction = $direction;
        $adamlabsgallery_c_sort_handle = $handle;
    }


    public function order_by_custom($order_by_start, $order_by_dir){
        $base = new AdamLabsGallery_Base();

        if(!empty($order_by_start) && !empty($this->grid_layers)){
            if(is_array($order_by_start)){
                $order_by_start = $order_by_start[0];
            }

            switch($order_by_start){
                case 'rand':
                    $this->grid_layers = $base->shuffle_assoc($this->grid_layers);
                    break;
                case 'title':
                case 'post_url':
                case 'excerpt':
                case 'meta':
                case 'alias':
                case 'name':
                case 'content':
                case 'author_name':
                case 'author':
                case 'cat_list':
                case 'tag_list':
                    if($order_by_start == 'name') $order_by_start = 'alias';
                    if($order_by_start == 'author') $order_by_start = 'author_name';
                    //check if values are existing and if not, add them to the layers
                    $this->set_custom_sorter($order_by_start, $order_by_dir);
                    usort($this->grid_layers, array('AdamLabsGallery', 'custom_sorter'));
                    break;
                case 'post_id':
                case 'ID':
                case 'num_comments':
                case 'comment_count':
                case 'date':
                case 'modified':
                case 'date_modified':
                case 'views':
                case 'likes':
                case 'dislikes':
                case 'retweets':
                case 'favorites':
                case 'itemCount':
                case 'duration':
                    if($order_by_start == 'comment_count') $order_by_start = 'num_comments';
                    if($order_by_start == 'modified') $order_by_start = 'date_modified';
                    if($order_by_start == 'ID') $order_by_start = 'post_id';

                    $this->set_custom_sorter($order_by_start, $order_by_dir);
                    usort($this->grid_layers, array('AdamLabsGallery', 'custom_sorter_int'));
                    break;
            }
        }
    }

    /**
     * Ajax Call to save Post Like
     */
    public function adamlabsgallery_post_like()
    {
        // Check for nonce security
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'adamlabsgallery-ajax-nonce' ) )
            die ( 'Busted!');

        if(isset($_POST['post_like'])){
            // Retrieve user IP address
            $ip = $_SERVER['REMOTE_ADDR'];
            $post_id = $_POST['post_id'];

            // Get voters'IPs for the current post
            $meta_IP = get_post_meta($post_id, "adamlabsgallery_voted_IP");
            $voted_IP = $meta_IP[0];

            if(!is_array($voted_IP))
                $voted_IP = array();

            // Get votes count for the current post
            $meta_count = get_post_meta($post_id, "adamlabsgallery_votes_count", true);

            // Use has already voted ?
            if(!$this->hasAlreadyVoted($post_id))
            {
                $voted_IP[$ip] = time();

                // Save IP and increase votes count
                update_post_meta($post_id, "adamlabsgallery_voted_IP", $voted_IP);
                update_post_meta($post_id, "adamlabsgallery_votes_count", ++$meta_count);

                // Display count (ie jQuery return value)
                echo $meta_count;
            }
            else
                _e("already",ADAMLABS_GALLERY_TEXTDOMAIN);
        }
        exit;
    }

    /**
     * Check if Post was already voted for
     */
    public function hasAlreadyVoted($post_id)
    {
        $timebeforerevote = get_option('adamlabsgallery_post_like_ip_lockout', '');
        if(empty($timebeforerevote) || $timebeforerevote === 0) return false;

        // Retrieve post votes IPs
        $meta_IP = get_post_meta($post_id, "adamlabsgallery_voted_IP");
        $voted_IP = $meta_IP[0];

        if(!is_array($voted_IP))
            $voted_IP = array();

        // Retrieve current user IP
        $ip = $_SERVER['REMOTE_ADDR'];

        // If user has already voted
        if(in_array($ip, array_keys($voted_IP)))
        {
            $time = $voted_IP[$ip];
            $now = time();

            // Compare between current time and vote time
            if(round(($now - $time) / 60) > $timebeforerevote)
                return false;

            return true;
        }

        return false;
    }


    public static function post_thumbnail_replace($html, $post_id, $post_thumbnail_id, $size, $attr){
        $post_grid_id = get_post_meta( $post_id, 'adamlabsgallery_featured_grid', true );
        if(!empty($post_grid_id))
            $html = do_shortcode('[adamlabsgallery alias="'.$post_grid_id.'"]');
        return $html;
    }


}