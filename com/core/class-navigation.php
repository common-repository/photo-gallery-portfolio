<?php

if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Navigation {
	
    private $grid_id = 0;
    private $filter = array();
    private $filter_show = true;
    private $filter_type = 'singlefilters';
    private $filter_start_select = array();
    private $sorting = array();
    private $sorting_start = 'none';
	
    private $layout = array('top-1' => array(), 'top-2' => array(), 'left' => array(), 'right' => array(), 'bottom-1' => array(), 'bottom-2' => array());
    private $specific_styles = array(
									'top-1' => array('margin-bottom' => 0, 'text-align' => 'center'),
									'top-2' => array('margin-bottom' => 0, 'text-align' => 'center'),
									'left' => array('margin-left' => 0),
									'right' => array('margin-right' => 0),
									'bottom-1' => array('margin-top' => 0, 'text-align' => 'center'),
									'bottom-2' => array('margin-top' => 0, 'text-align' => 'center'));
    private $filter_settings = array('filter' => array('filter-grouping' => 'false', 'filter-listing' => 'list', 'filter-selected' => array()));
    
    private $styles = array();
	
    private $filter_all_text = array();
	private $filterall_visible = array();
    private $filter_dropdown_text = array();
    private $filter_show_count = array();
    private $spacing = false;
    private $sort_by_text;
    private $search_text;
    private $special_class = '';
	
	private $search_found = false;
	private $filter_added = false;
    
    
	public function __construct($grid_id = 0) {
		
		$this->grid_id = $grid_id;
		$this->filter_all_text['filter'] = __('Filter - All', ADAMLABS_GALLERY_TEXTDOMAIN);
		$this->filterall_visible['filter'] = 'on';
		$this->filter_dropdown_text['filter'] = __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN);
		$this->filter_show_count['filter'] = 'off';
		$this->sort_by_text = __('Sort By ', ADAMLABS_GALLERY_TEXTDOMAIN);
		$this->search_text = __('Search...', ADAMLABS_GALLERY_TEXTDOMAIN);
		
		self::get_adamlabsgallery_navigation_skins();
	}
    
    
    /**
     * Return all Navigation skins
     */
    public static function get_adamlabsgallery_navigation_skins(){
        global $wpdb;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
        $navigation_skins = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
		
		if($navigation_skins == false){ //empty, insert defaults again
			self::propagate_default_navigation_skins();
			$navigation_skins = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
		}
		
		return apply_filters('adamlabsgallery_get_navigation_skins', $navigation_skins);
    }
    
    
    /**
	 * Get Navigation Skin by ID from Database
	 */
	public static function get_adamlabsgallery_navigation_skin_by_id($id = 0){
		global $wpdb;
		
		$id = intval($id);
		if($id == 0) return false;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
		
		$skin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
		
		
		if(!empty($skin)){
			$skin['css'] = AdamLabsGallery_Base::stripslashes_deep($skin['css']);
		}
		
		return apply_filters('adamlabsgallery_get_navigation_skin_by_id', $skin, $id);
	}
    
    
    /**
	 * Get Navigation Skin by ID from Database
	 */
	public static function get_adamlabsgallery_navigation_skin_by_handle($handle = ''){
		global $wpdb;
		
		if($handle == '') return false;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
		
		$skin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $handle), ARRAY_A);
		
		if(!empty($skin)){
			$skin['css'] = AdamLabsGallery_Base::stripslashes_deep($skin['css']);
		}
		
		return apply_filters('adamlabsgallery_get_navigation_skin_by_handle', $skin, $handle);
	}
	
	
	/**
	 * All default Item Skins
	 */
	public static function get_default_navigation_skins(){
		
		$default = array();
		
		include('assets/default-navigation-skins.php');
		
		$default = apply_filters('adamlabsgallery_add_default_nav_skins', $default);
		
		return $default;
	}
	
	
	public static function propagate_default_navigation_skins($networkwide = false){
		$skins = self::get_default_navigation_skins();
		
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
			global $wpdb;
			
			// 2.2.5
			// $old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
				switch_to_blog($blog_id);
				self::insert_default_navigation_skins($skins);
				restore_current_blog();
            }
			
			// 2.2.5
            // switch_to_blog($old_blog); //go back to correct blog
			
		}else{
		
			self::insert_default_navigation_skins($skins);
			
		}
		
	}
	
	/**
	 * Insert Default Skin if they are not already installed
	 */
	public static function insert_default_navigation_skins($data){
		global $wpdb;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
		
		$data = apply_filters('adamlabsgallery_insert_detault_navigation_skins', $data);
		
        if(!empty($data)){
			foreach($data as $skin){
        
				//create
				$check = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s ", $skin['handle']), ARRAY_A);
				
				//check if exists, if no, create
				if(!empty($check)) continue;
				
				//insert if function did not return yet
				$response = $wpdb->insert($table_name, array('name' => $skin['name'], 'handle' => $skin['handle'], 'css' => $skin['css']));
            
			}
		}
		
	}
	
	
	/**
	 * Update / Save Navigation Skins
	 */
    public static function update_create_navigation_skin_css($data){
        global $wpdb;
        
		$data = apply_filters('adamlabsgallery_update_create_navigation_skin_css', $data);
		
        $table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
        if(isset($data['name'])){ //create new skin
            if(strlen($data['name']) < 2) return __('Invalid name. Name has to be at least 2 characters long.', ADAMLABS_GALLERY_TEXTDOMAIN);
            if(strlen(sanitize_title($data['name'])) < 2) return __('Invalid name. Name has to be at least 2 characters long.', ADAMLABS_GALLERY_TEXTDOMAIN);
            
        }else{
			if(isset($data['sid'])){
				if(intval($data['sid']) == 0) return __('Invalid Navigation Skin. Wrong ID given.', ADAMLABS_GALLERY_TEXTDOMAIN);
			}else{
				return __('Invalid Navigation Skin. Wrong ID given.', ADAMLABS_GALLERY_TEXTDOMAIN);
			}
        }
        
        
		
        if(!isset($data['skin_css']) || empty($data['skin_css'])) return __('No CSS found.', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        if(isset($data['sid']) && intval($data['sid']) > 0){ //update
			//check if entry with id exists, because this is unique
			$skin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id != %s ", $data['sid']), ARRAY_A);
			
			//check if exists, if yes, update
			if(!empty($skin)){
				$response = $wpdb->update($table_name,
											array(
												'css' => stripslashes($data['skin_css'])
												), array('id' => $data['sid']));
											
				if($response === false) return __('Navigation skin could not be changed.', ADAMLABS_GALLERY_TEXTDOMAIN);
				
				return true;
			}
		}else{
            
            //create
            $skin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s ", sanitize_title($data['name'])), ARRAY_A);
            
            //check if exists, if no, create
            if(!empty($skin)){
                return __('Navigation skin with chosen name already exist. Please use a different name.', ADAMLABS_GALLERY_TEXTDOMAIN);
            }
            
            //insert if function did not return yet
            $response = $wpdb->insert($table_name, array('name' => $data['name'], 'handle' => sanitize_title($data['name']), 'css' => stripslashes($data['skin_css'])));
            
            if($response === false) return false;
            
            return true;
        }
    }
	
	
	/**
	 * Update / Save Navigation Skins
	 */
    public static function delete_navigation_skin_css($data){
        global $wpdb;
        
		$data = apply_filters('adamlabsgallery_delete_navigation_skin_css', $data);
		
        $table_name = $wpdb->prefix . AdamLabsGallery::TABLE_NAVIGATION_SKINS;
        
		if(!isset($data['skin'])){
			return __('Invalid Navigation Skin. Wrong ID given.', ADAMLABS_GALLERY_TEXTDOMAIN);
		}
        
		//check if entry with id exists, because this is unique
		$response = $wpdb->delete($table_name, array('handle' => $data['skin']));
		
		if($response === false) return __('Navigation Skin not be deleted', ADAMLABS_GALLERY_TEXTDOMAIN);
		
		return true;
    }
	
	
	/**
	 * Set all Layout Elements
	 */
	public function set_layout($layout){
		
		$layout = apply_filters('adamlabsgallery_set_layout', $layout);
		
		if(!empty($layout)){
			foreach($layout as $type => $position){
				if(!empty($position)){
					$pos = current(array_keys($position));
					
					$this->layout[$pos][$layout[$type][$pos]] = $type;
				}
			}
			
			foreach($this->layout as $key => $val)
				ksort($this->layout[$key]);
			
		}
	}
	
	
	/**
	 * Set specific styles
	 */
	public function set_specific_styles($styles){
		
		$styles = apply_filters('adamlabsgallery_set_specific_styles', $styles);
		
		$this->specific_styles = $styles;
		
	}
	
	
	/**
	 * Set special class to wrapper
	 */
	public function set_special_class($classes){
		
		$classes = apply_filters('adamlabsgallery_set_special_class', $classes);
		
		$this->special_class .= ' '.$classes;
		
	}
	
	
	/**
	 * Set filter added to true/false
	 */
	public function set_filter_added($setto){
		
		$this->filter_added = apply_filters('adamlabsgallery_set_filter_added', $setto);
		
	}
	
	
	/**
	 * Set Filter All Text
	 */
	public function set_filter_text($text, $key = ''){
		
		$a = apply_filters('adamlabsgallery_set_filter_text', array('text' => $text, 'key' => $key));
		
		$this->filter_all_text['filter'.$a['key']] = __($a['text'], ADAMLABS_GALLERY_TEXTDOMAIN);
		
	}
	
	/**
	 * Set Filter All Visiblity
	 */
	public function set_filterall_visible($visible, $key = ''){

		$a = apply_filters('adamlabsgallery_set_filterall_visible', array('visible' => $visible, 'key' => $key));
		
		$this->filterall_visible['filter'.$a['key']] = __($a['visible'], ADAMLABS_GALLERY_TEXTDOMAIN);
		
	}
	
	
	/**
	 * Set Sort By Text
	 */
	public function set_orders_text($text){
		
		$text = apply_filters('adamlabsgallery_set_orders_text', $text);
		
		$this->sort_by_text = $text;
		
	}
	
	
	/**
	 * Set Filter Dropdown Text
	 */
	public function set_dropdown_text($text, $key = ''){
		
		$a = apply_filters('adamlabsgallery_set_dropdown_text', array('text' => $text, 'key' => $key));
		
		$text = apply_filters('adamlabsgallery_set_dropdown_text', $text, $key);
		
		$this->filter_dropdown_text['filter'.$a['key']] = $a['text'];
		
	}
	
	
	/**
	 * Set Filter Option To Show Count
	 */
	public function set_show_count($opt, $key = ''){
		
		$a = apply_filters('adamlabsgallery_set_show_count', array('opt' => $opt, 'key' => $key));
		
		$this->filter_show_count['filter'.$a['key']] = $a['opt'];
		
	}
	
	
	/**
	 * Set specific filter settings
	 */
	public function set_filter_settings($key, $settings){
		
		$a = apply_filters('adamlabsgallery_set_filter_settings', array('settings' => $settings, 'key' => $key));
		
		$this->filter_settings[$a['key']] = $a['settings'];
		
	}
	
	
	/**
	 * Output Container
	 */
	public function output_layout($layout_container, $spacing = 0){
		$fclass = ($this->filter_type != false) ? ' adamlabsgallery-'.$this->filter_type : '';
		
		do_action('adamlabsgallery_output_layout_pre', $layout_container, $this->layout);
		
		$l = '';
		
		if(!empty($this->layout[$layout_container])){
			$l = '<article class="adamlabsgallery-filters'.$fclass;
			if($layout_container == 'left') $l .= ' adamlabsgallery-navbutton-solo-left';
			if($layout_container == 'right') $l .= ' adamlabsgallery-navbutton-solo-right';
			
			$l .= '"';
			$l .= ' style="';
			if(!empty($this->styles)){
				foreach($this->styles as $style => $value){
					$l .= $style.': '.$value.'; ';
				}
			}
			
			if(!empty($this->specific_styles[$layout_container])){
				foreach($this->specific_styles[$layout_container] as $style => $value){
					$l .= $style.': '.$value.'; ';
				}
			}
			
			$l .= '"';
			$l .= '>';
			
			$num = count($this->layout[$layout_container]) - 1;
			
			$real_spacing = ($spacing !== 0) ? $spacing / 2 : 0;
			foreach($this->layout[$layout_container] as $key => $what){
				$important = ($what == 'right' || $what == 'left') ? ' !important' : ''; //set important if we are the arrows, because they have already !important in settings.css
				$this->spacing = ' style="margin-left: '.$real_spacing.'px'.$important.'; margin-right: '.$real_spacing.'px'.$important.';';
				if($what == 'right' || $what == 'left') $this->spacing .= ' display: none;'; //hide navigation buttons left & right at start
				$this->spacing .= '"';
				/*if($num >= 1 && $key != $num && $key > 0){
					$this->spacing = ' style="margin-left: '.$real_spacing.'px'.$important.'; margin-right: '.$real_spacing.'px'.$important.';';
					if($what == 'right' || $what == 'left') $this->spacing .= ' display: none;'; //hide navigation buttons left & right at start
					$this->spacing .= '"';
				}else{
					if($key == 0){
						$this->spacing = ' style="margin-right: '.$real_spacing.'px'.$important.';';
						if($what == 'right' || $what == 'left') $this->spacing .= ' display: none;'; //hide navigation buttons left & right at start
						$this->spacing .= '"';
					}elseif($key == $num){
						$this->spacing = ' style="margin-left: '.$real_spacing.'px'.$important.';';
						if($what == 'right' || $what == 'left') $this->spacing .= ' display: none;'; //hide navigation buttons left & right at start
						$this->spacing .= '"';
					}else{
						$this->spacing = false;
					}
				}*/
					
				
				switch($what){
					case 'sorting':
						$l .= self::output_sorting();
					break;
					case 'cart':
						$l .= self::output_cart();
					break;
					case 'left':
						$l .= self::output_navigation_left();
					break;
					case 'right':
						$l .= self::output_navigation_right();
					break;
					case 'pagination':
						$l .= self::output_pagination();
					break;
					case 'filter':
						$l .= self::output_filter_unwrapped();
					break;
					case 'search-input':
						$l .= self::output_search_input();
					break;
					default:
						//check if its one of the filter fields
						if(strpos($what, 'filter-') !== false){
							$cur_id = intval(str_replace('filter-', '', $what));
							$l .= self::output_filter_unwrapped(false, '-'.$cur_id);
						}else{
							do_action('adamlabsgallery_output_layout_'.$what);
						}
					break;
					
				}
			}
			
			$l .= '</article>';
			$l .= '<div class="adamlabsgallery-clear-no-height"></div>';
			
			echo apply_filters('adamlabsgallery_output_layout', $l);
		}
		
		do_action('adamlabsgallery_output_layout_post', $layout_container, $this->layout);
	}
	
	
    public function set_filter($data){
       if(!empty($data)){
			$this->filter = $data + $this->filter; //merges the array and preserves the key
			
			asort($this->filter);
			
			$this->filter = apply_filters('adamlabsgallery_set_filter', $this->filter);
		}
		
    }
	
	
    public function set_orders($data){
        $this->sorting = $data + $this->sorting; //merges the array and preserves the key
        arsort($this->sorting);
		$this->sorting = apply_filters('adamlabsgallery_set_orders', $this->sorting);
    }
	
	
    public function set_orders_start($start_by){

		$this->sorting_start = apply_filters('adamlabsgallery_set_orders_start', $start_by);

    }
    
    
    public function set_filter_type($type){
        if($type == 'single'){
            $this->filter_type = 'singlefilters';
        }elseif($type == 'multi'){
			$this->filter_type = 'multiplefilters';
        }else{
			$this->filter_type = false;
		}
		
		$this->filter_type = apply_filters('adamlabsgallery_set_filter_type', $this->filter_type);
    }
    
	
    /**
	 * Set filter that are selected at start
	 **/
    public function set_filter_start_select($select){
		$this->filter_start_select = apply_filters('adamlabsgallery_set_filter_start_select', explode(',', $select));
		foreach($this->filter_start_select as $key => $val){
			if(trim($val) == '') unset($this->filter_start_select[$key]);
		}
    }
    
    
    public function set_style($name, $value){
        $a = apply_filters('adamlabsgallery_set_style', array('name' => $name, 'value' => $value));
        $this->styles[$a['name']] = $a['value'];
    }
    
    
    public function output_filter($demo = false){
        if(!$this->filter_show || $this->filter_type == false) return true;
        
        //$f = '<!-- THE FILTERING, SORTING AND WOOCOMMERCE BUTTONS -->';
		$f = '<article class="adamlabsgallery-filters adamlabsgallery-'.$this->filter_type.' '.$this->special_class.'"';
		
		if(!empty($this->styles)){
			$f .= ' style="';
			foreach($this->styles as $style => $value){
				$f .= $style.': '.$value.'; ';
			}
			$f .= '"';
		}
		
		$f .= '>'; //<!-- USE adamlabsgallery-multiplefilters FOR MIXED FILTERING, AND adamlabsgallery-singlefilters FOR SINGLE FILTERING -->
        //$f .= '<!-- THE FILTER BUTTONS -->';
        $f .= '<div class="adamlabsgallery-filter-wrapper">';
		
		// 2.2.5
		$f .= '<div class="adamlabsgallery-mobile-filter-button" style="display: none"><span>'.$this->filter_dropdown_text['filter'].'</span><i class="adamlabsgallery-icon-down-open"></i></div>';
		
		$sel = (!empty($this->filter_start_select)) ? '' : ' selected';
		
		/* 2.2.6 hide Filter-All button if text is empty */
		$all_filter_text = $this->filter_all_text['filter'];
		$hideallfilter = $this->filterall_visible['filter'] === 'on' ? '' : ' style="display: none"';
        $f .= '<div class="adamlabsgallery-filterbutton'.$sel.' adamlabsgallery-allfilter" data-filter="filterall" data-fid="-1"'.$hideallfilter.'><span>'.$all_filter_text.'</span></div>';
		
		if($demo === 'skinchoose'){
            $f .= '<div class="adamlabsgallery-filterbutton" data-filter="filter-selectedskin"><span>'.__('Selected Skin', ADAMLABS_GALLERY_TEXTDOMAIN).'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
        }
        if($demo !== false){
            $f .= '<div class="adamlabsgallery-filterbutton" data-filter="filter-favorite"><span>'.__('Favorites', ADAMLABS_GALLERY_TEXTDOMAIN).'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
        }
		
        if(!empty($this->filter)){
            foreach($this->filter as $filter_id => $filter){
                $filter_text = ($demo !== false) ? self::translate_demo_filter($filter['slug']) : AdamLabsGallery_Wpml::strip_category_additions($filter['name']);
				$sel = (in_array(sanitize_key($filter['slug']), $this->filter_start_select)) ? ' selected' : '';
                $f .= '<div class="adamlabsgallery-filterbutton'.$sel.'" data-fid="'.$filter_id.'" data-filter="filter-'.sanitize_key($filter['slug']).'"><span>'.$filter_text.'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
            }
        }
        $f .= '</div>';
		
        //echo self::output_sorting();
        //echo self::output_cart();
        
        $f .= '<div class="clear"></div>';

        $f .= '</article>'; //<!-- END OF FILTERING, SORTING AND  CART BUTTONS -->';
		
        $f .= '<div class="clear adamlabsgallery-filter-clear"></div>';
        
		$this->set_filter_added(true);
		
		return apply_filters('adamlabsgallery_output_filter', $f, $demo);
    }
    
	
	public function output_filter_unwrapped($demo = false, $type = ''){ //$type -> names what settings we need to check for: filter, filter2, filter3
        global $sitepress;
		
		$grouping = (isset($this->filter_settings['filter'.$type]['filter-grouping'])) ? $this->filter_settings['filter'.$type]['filter-grouping'] : 'false';
		$listing = (isset($this->filter_settings['filter'.$type]['filter-listing'])) ? $this->filter_settings['filter'.$type]['filter-listing'] : 'list';
		$amount = (isset($this->filter_show_count['filter'.$type]) && $this->filter_show_count['filter'.$type] == 'on') ? ' adamlabsgallery-show-amount' : '';
		$do_show = @$this->filter_settings['filter'.$type]['filter-selected'];
		
		
		$dropdown = '';
		switch($listing){
			case 'dropdown': //use dropdown
				$dropdown = ' dropdownstyle';
				break;
			case 'mobiledropdownstyle': //use dropdown only on mobile
				$dropdown = ' mobiledropdownstyle';
				break;
		}
		
        //$f = '<!-- THE FILTERING, SORTING AND WOOCOMMERCE BUTTONS -->';
		$f = '<!-- THE FILTER BUTTONS -->';
        $f .= '<div class="adamlabsgallery-filter-wrapper'.$dropdown.$amount.' '.$this->special_class.'"';
		if($this->spacing !== false) $f .= $this->spacing;
		$f .= '>';
		
		$filtertext = __('Filter Categories', ADAMLABS_GALLERY_TEXTDOMAIN);
		if(array_key_exists('filter'.$type, $this->filter_dropdown_text)) {
			$filtertext = $this->filter_dropdown_text['filter'.$type];
		}
		
		if($listing == 'dropdown'){
			// 2.2.5
			$f .= '<div class="adamlabsgallery-selected-filterbutton adamlabsgallery-mobile-filter-button"><span>'.$filtertext.'</span><i class="adamlabsgallery-icon-down-open"></i></div>';
			
			$f .= '<div class="adamlabsgallery-dropdown-wrapper">';
		}
		else {
			// 2.2.5
			$f .= '<div class="adamlabsgallery-mobile-filter-button"><span>'.$filtertext.'</span><i class="adamlabsgallery-icon-down-open"></i></div>';
		}
		
		$sel = (!empty($this->filter_start_select)) ? '' : ' selected';
		
		/* 2.2.6 hide Filter-All button if text is empty */
		$all_filter_text = @$this->filter_all_text['filter'.$type];
		$hideallfilter = @$this->filterall_visible['filter'.$type] === 'on' ? '' : ' style="display: none"';
		
		$f .= '<div class="adamlabsgallery-filterbutton'.$sel.' adamlabsgallery-allfilter" data-filter="filterall" data-fid="-1"'.$hideallfilter.'><span>'.$all_filter_text.'</span></div>';
		
        if($demo){
            $f .= '<div class="adamlabsgallery-filterbutton" data-filter="filter-favorite"><span>'.__('Favorites', ADAMLABS_GALLERY_TEXTDOMAIN).'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
        }
		
		if(!empty($do_show) && is_array($do_show)){ //we are a post based grid
			foreach($do_show as $string_id){
				$fraw = explode('_', $string_id);
				
				if(count($fraw) > 1){
					$f_id = array_pop($fraw);
				}else{
					$f_id = $fraw[0];
				}
				
				if(AdamLabsGallery_Wpml::is_wpml_exists() && isset($sitepress)){
					$t_id = @icl_object_id($f_id, implode('_', $fraw), true, ICL_LANGUAGE_CODE);
					if(!empty($t_id)) $f_id = $t_id;
				}
				if(isset($this->filter[$f_id])){

					$filter_text = ($demo) ? self::translate_demo_filter($this->filter[$f_id]['slug']) : AdamLabsGallery_Wpml::strip_category_additions($this->filter[$f_id]['name']);
					
					// 2.2.5
					// sanitize multi-select custom meta
					if(strpos($filter_text, "['") !== false && strpos($filter_text, "']") !== false) {

						$filter_text = preg_replace("/\[\'|\'\]/", '', $filter_text);
						$filter_text = preg_replace("/\'\,\'/", ' & ', $filter_text);
	
					}
					
					$sel = (in_array(sanitize_key($this->filter[$f_id]['slug']), $this->filter_start_select)) ? ' selected' : '';
					$parent_id = (isset($this->filter[$f_id]['parent']) && intval($this->filter[$f_id]['parent']) > 0) ? $this->filter[$f_id]['parent'] : 0;
					
					$parent = ($parent_id > 0) ? ' data-pid="'.$parent_id.'"' : '';
					$f .= '<div class="adamlabsgallery-filterbutton'.$sel.'" data-fid="'.$f_id.'"'.$parent.' data-filter="filter-'.sanitize_key($this->filter[$f_id]['slug']).'"><span>'.$filter_text.'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
				}
			}
		}else{
			if(!empty($this->filter)){
				foreach($this->filter as $filter_id => $filter){
					
					$filter_text = ($demo) ? self::translate_demo_filter($filter['slug']) : AdamLabsGallery_Wpml::strip_category_additions($filter['name']);
					
					// 2.2.5
					// sanitize multi-select custom meta
					if(strpos($filter_text, "['") !== false && strpos($filter_text, "']") !== false) {

						$filter_text = preg_replace("/\[\'|\'\]/", '', $filter_text);
						$filter_text = preg_replace("/\'\,\'/", ' & ', $filter_text);
	
					}
					
					$sel = (in_array(sanitize_key($filter['slug']), $this->filter_start_select)) ? ' selected' : '';
					$parent_id = (isset($filter['parent']) && intval($filter['parent']) > 0) ? $filter['parent'] : 0;
					
					$parent = ($parent_id > 0) ? ' data-pid="'.$parent_id.'"' : '';
					$f .= '<div class="adamlabsgallery-filterbutton'.$sel.'" data-fid="'.$filter_id.'"'.$parent.' data-filter="filter-'.sanitize_key($filter['slug']).'"><span>'.$filter_text.'</span><span class="adamlabsgallery-filter-checked"><i class="adamlabsgallery-icon-ok-1"></i></span></div>';
				}
			}
        }
		
		if($listing == 'dropdown'){
			$f .= '</div>';
		}
		$f .= '<div class="adamlabsgallery-clearfix"></div>';
        $f .= '</div>';
		
		$this->set_filter_added(true);
        
		return apply_filters('adamlabsgallery_output_filter_unwrapped', $f, $demo, $type);
    }
	
	
    public function output_sorting(){
		$s = '';
		if(!empty($this->sorting)){
			
			//$s .= '<!-- THE SORTING BUTTON -->';
			$s .= '<div class="adamlabsgallery-sortbutton-wrapper '.$this->special_class.'"';
			if($this->spacing !== false) $s .= $this->spacing;
			$s .= '>';
			$s .= '<div class="adamlabsgallery-sortbutton"><span>'.$this->sort_by_text.'</span><span class="sortby_data">'.$this->set_sorting_text($this->sorting_start).'</span>';
			$s .= '<select class="adamlabsgallery-sorting-select">';
			foreach($this->sorting as $sort){
				$s .= '<option value="'.$this->set_sorting_value($sort).'" '.selected( $this->sorting_start, $this->set_sorting_value($sort), false ).'>'.$this->set_sorting_text($sort).'</option>';
			}
			$s .= '</select>';
			$s .= '</div><div class="adamlabsgallery-sortbutton-order adamlabsgallery-icon-down-open adamlabs-asc"></div>';
			$s .= '</div>'; //<!-- END OF SORTING BUTTON -->';
		}
        
		return apply_filters('adamlabsgallery_output_sorting', $s);
    }
	
	
	public function set_sorting_text($san_text){
		if(strpos($san_text, 'adamlabsgallery-') === 0){
			$meta = new AdamLabsGallery_Meta();
			$m = $meta->get_all_meta(false);
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgallery-'.$me['handle'] == $san_text) return apply_filters('adamlabsgallery_set_sorting_text', $me['name'], $san_text);
				}
			}
		}elseif(strpos($san_text, 'adamlabsgalleryl-') === 0){
			$meta = new AdamLabsGallery_Meta_Linking();
			$m = $meta->get_all_link_meta(false);
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgalleryl-'.$me['handle'] == $san_text) return apply_filters('adamlabsgallery_set_sorting_text', $me['name'], $san_text);
				}
			}
		}else{
			$orig = $san_text;
			switch($san_text){
				case 'date':
					$san_text = __('Date', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'title':
					$san_text = __('Title', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'excerpt':
					$san_text = __('Excerpt', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'id':
					$san_text = __('ID', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'slug':
					$san_text = __('Slug', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'author':
					$san_text = __('Author', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'last-modified':
					$san_text = __('Last Modified', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'number-of-comments':
					$san_text = __('Comments', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta_num_total_sales':
					$san_text = __('Total Sales', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta_num__regular_price':
					$san_text = __('Regular Price', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta_num__sale_price':
					$san_text = __('Sale Price', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta__featured':
					$san_text = __('Featured', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta__sku':
					$san_text = __('SKU', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				case 'meta_num_stock':
					$san_text = __('In Stock', ADAMLABS_GALLERY_TEXTDOMAIN);
				break;
				default:
					$san_text = ucfirst($san_text);
				break;
			}
		}
		
		return apply_filters('adamlabsgallery_set_sorting_text', $san_text, $orig);
	}
	
	
	public function set_sorting_value($san_handle){
		$orig = $san_handle;
		switch($orig){
			case 'meta_num_total_sales':
				$san_handle = 'total-sales';
			break;
			case 'meta_num__regular_price':
				$san_handle = 'regular-price';
			break;
			case 'meta_num__sale_price':
				$san_handle = 'sale-price';
			break;
			case 'meta__featured':
				$san_handle = 'featured';
			break;
			case 'meta__sku':
				$san_handle = 'sku';
			break;
			case 'meta_num_stock':
				$san_handle = 'in-stock';
			break;
		}
		return apply_filters('adamlabsgallery_set_sorting_value', $san_handle, $orig);
	}
	
	
    public function output_cart(){

		if(!AdamLabsGallery_Woocommerce::is_woo_exists()) return true;
		if( !function_exists( 'wc_cart_totals_subtotal_html' ) ) return true;
		ob_start();
		//$c .= '<!-- THE CART BUTTON -->';
		echo '<div class="adamlabsgallery-cartbutton-wrapper '.$this->special_class.'"';
		if($this->spacing !== false) echo $this->spacing;
		echo '>';
		
		echo '<div class="adamlabsgallery-cartbutton">';
		echo '<a href="'.wc_get_cart_url().'">';
		echo '<i class="adamlabsgallery-icon-basket"></i><span class="ess-cart-content">';
		echo WC()->cart->get_cart_contents_count();
		echo __(' items - ', ADAMLABS_GALLERY_TEXTDOMAIN);
		echo wc_cart_totals_subtotal_html();
		echo '</span>';
		echo '</a>';
		echo '</div>';//<a href="#"><div class="adamlabsgallery-sortbutton-order adamlabsgallery-icon-down-open adamlabs-asc"></div></a>
		echo '</div>'; //<!-- END OF CART BUTTON -->';
		
		$c = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		return apply_filters('adamlabsgallery_output_cart', $c);
		
    }
    
    
    public function output_navigation(){
		//$n = '<!-- THE NAVIGATION BUTTONS -->';
        $n = '<article class="navigationbuttons '.$this->special_class.'">';
		$n .= self::output_navigation_left();
		$n .= self::output_navigation_right();
        $n .= '</article>'; //<!-- END OF THE NAVIGATION BUTTONS -->';
		
		return apply_filters('adamlabsgallery_output_navigation', $n);
    }
	
	
	public function output_navigation_left(){
        $n = '<div class="adamlabsgallery-navigationbutton adamlabsgallery-left '.$this->special_class.'" ';
		if($this->spacing !== false) $n .= $this->spacing;
		$n .= '><i class="adamlabsgallery-icon-left-open"></i></div>';
		
		return apply_filters('adamlabsgallery_output_navigation_left', $n);
	}
	
	
	public function output_navigation_right(){
        $n = '<div class="adamlabsgallery-navigationbutton adamlabsgallery-right '.$this->special_class.'" ';
		if($this->spacing !== false) $n .= $this->spacing;
		$n .= '><i class="adamlabsgallery-icon-right-open"></i></div>';
		
		return apply_filters('adamlabsgallery_output_navigation_right', $n);
	}
    
	
    public function output_pagination($backend = false){
        //$p = '<!-- THE PAGINATION CONTAINER. PAGE BUTTONS WILL BE ADDED ON DEMAND AUTOMATICALLY !! -->';
        $p = '<div class="adamlabsgallery-pagination '.$this->special_class.'"';
		if($this->spacing !== false) $p .= $this->spacing;
		$p .= '></div>'; //<!-- END OF THE PAGINATION -->';
		
		return apply_filters('adamlabsgallery_output_pagination', $p, $backend);
    }
    
    
    public function output_navigation_skin($handle){
		$base = new AdamLabsGallery_Base();
        $css = self::get_adamlabsgallery_navigation_skin_by_handle($handle);
		
		$n = '';
		
		if($css !== false){
			$n = '<style type="text/css">';
			$n .= $base->compress_css($css['css']);
			$n .= '</style>'."\n";
		}
		
		return apply_filters('adamlabsgallery_output_navigation_skin', $n, $handle);
    }
    
    
    public static function output_navigation_skins(){
		$base = new AdamLabsGallery_Base();
        $skins = self::get_adamlabsgallery_navigation_skins();
		
		$css = '';
		
		if(!empty($skins)){
			foreach($skins as $skin){
				$css .= '<style class="navigation-skin-css-'.$skin['id'].'" type="text/css">';
				$css .= $base->compress_css($skin['css']);
				$css .= '</style>'."\n";
			}
		}
		
		return apply_filters('adamlabsgallery_output_navigation_skins', $css);
    }
    
    
    private function translate_demo_filter($name){
        
        $post = AdamLabsGallery_Item_Element::getPostElementsArray();
        $event = AdamLabsGallery_Item_Element::getEventElementsArray();
		$woocommerce = array();
		if(AdamLabsGallery_Woocommerce::is_woo_exists()){
			$tmp_wc = AdamLabsGallery_Woocommerce::get_meta_array();
			
			foreach($tmp_wc as $handle => $wc_name){
				$woocommerce[$handle]['name'] = $wc_name;
			}
		}
		
        if(array_key_exists($name, $post)) return $post[$name]['name'];
        if(array_key_exists($name, $event)) return $event[$name]['name'];
        if(array_key_exists($name, $woocommerce)) return $woocommerce[$name]['name'];
        
        return apply_filters('adamlabsgallery_translate_demo_filter', ucwords($name), $name);
    }
	
	
	/**
	 * Change the Search Text
	 */
	public function set_search_text($text){
		$this->search_text = apply_filters('adamlabsgallery_set_search_text', $text);
	}
	
	
    /**
	 * Output the Search Input Field
	 */
	public function output_search_input(){
		$s = '<div class="adamlabsgallery-filter-wrapper adamlabsgallery-search-wrapper'.$this->special_class.'"';
		if($this->spacing !== false) $s .= $this->spacing;
		$s .= '>';
		$s .= '<input name="adamlabsgallery-search-input-'.$this->grid_id.'" class="adamlabsgallery-search-input" type="text" value="" placeholder="'.$this->search_text.'">';
		$s .= '<span class="adamlabsgallery-search-submit"><i class="adamlabsgallery-icon-search"></i></span>';
		$s .= '<span class="adamlabsgallery-search-clean"><i class="adamlabsgallery-icon-cancel"></i></span>';
		$s .= '</div>';
		
		$this->search_found = true; //trigger this to add
		
		return apply_filters('adamlabsgallery_output_search_input', $s);
	}
	
	
	/**
	 * Output "Filter All" markup if search is existing in any container
	 */
	public function check_for_search(){
		
		$s = '';
		
		if($this->filter_added === false && $this->search_found === true){
			
			//$s .= '<!-- NEEDED FOR SEARCH -->';
			$s .= '<div class="adamlabsgallery-filter-wrapper" style="display:none;">';
			$s .= '<article class="adamlabsgallery-filters adamlabsgallery-'.$this->filter_type.' '.$this->special_class.'">';
			$s .= '<div class="adamlabsgallery-filterbutton selected adamlabsgallery-allfilter" data-filter="filterall" data-fid="-1" ><span>'.$this->filter_all_text['filter'].'</span></div>';
			$s .= '</article>';
			$s .= '</div>';
			
		}
		
		return apply_filters('adamlabsgallery_check_for_search', $s, $this->filter_added, $this->search_found);
		
	}
	
}