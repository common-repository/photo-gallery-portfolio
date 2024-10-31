<?php

if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Item_Element {

    
    /**
     * Return all Item Elements
     */
    public static function get_adamlabsgallery_item_elements(){
        global $wpdb;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
        
        $item_elements = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        
		return apply_filters('adamlabsgallery_get_adamlabsgallery_item_elements', $item_elements);
    }
    
    
    /**
	 * Get Item Element by ID from Database
	 */
	public static function get_adamlabsgallery_item_element_by_id($id = 0){
		global $wpdb;
		
		$id = intval($id);
		if($id == 0) return false;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
		
		$element = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
		
		if(!empty($element)){
			$element['settings'] = @json_decode($element['params'], true);
		}
		
		return apply_filters('adamlabsgallery_get_adamlabsgallery_item_element_by_id', $element, $id);
	}
    
    
    /**
	 * Get Item Element by handle from Database
	 */
	public static function check_existence_by_handle($handle){
		global $wpdb;
		
		if(trim($handle) == '') return __('Chosen name is too short', ADAMLABS_GALLERY_TEXTDOMAIN);
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
		
		$element = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $handle), ARRAY_A);
		
		$return = false;
		
		if(!empty($element)){
			$return = true;
		}
		
		return apply_filters('adamlabsgallery_check_existence_by_handle', $return, $handle);
	}
    
    
    /**
	 * Update Item Element by ID from Database
	 */
    public static function update_create_adamlabsgallery_item_element($data){
        global $wpdb;
        
        $table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
        
        if(!isset($data['name']) || empty($data['name'])) return __('Name not received', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        $element = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s", $data['name']), ARRAY_A);
        
        if(!empty($element)){
            $success = self::update_adamlabsgallery_item_element(apply_filters('adamlabsgallery_update_create_adamlabsgallery_item_element', $data, 'update'));
        }else{
            $success = self::insert_adamlabsgallery_item_element(apply_filters('adamlabsgallery_update_create_adamlabsgallery_item_element', $data, 'insert'));
        }
        
        return $success;
    }
    
    
    /**
	 * Update Item Element by ID from Database
	 */
	public static function update_adamlabsgallery_item_element($data){
		global $wpdb;
		
        $table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
        
        if(empty($data['settings'])) return __('Element Item has no attributes', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        //check if element is default element (these are not deletable)
        $default = self::getDefaultElementsArray();
        
        $is_default = false;
        foreach($default as $handle => $settings){
            if($handle == sanitize_title($data['name'])){
                $is_default = true;
                break;
            }
        }
        
        $data['settings'] = self::clean_settings_from_elements($data['settings']);
        
        if($is_default) return __('Choosen name is reserved for default Item Elements. Please choose a different name', ADAMLABS_GALLERY_TEXTDOMAIN);
        
		$data = apply_filters('adamlabsgallery_update_adamlabsgallery_item_element', $data);
        
		$response = $wpdb->update($table_name,
                                    array(
                                        'settings' => json_encode($data['settings'])
                                        ), array('handle' => sanitize_title($data['name'])));
                                    
        if($response === false) return __('Element Item could not be changed', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        return true;
	}
    
    
    /**
	 * Insert Item Element by ID from Database
	 */
	public static function insert_adamlabsgallery_item_element($data){
		global $wpdb;
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
		
        if(empty($data['settings'])) return __('Element Item has no attributes', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        //check if element is default element (these are not deletable)
        $default = self::getDefaultElementsArray();
        
        $is_default = false;
        foreach($default as $handle => $settings){
            if($handle == sanitize_title($data['name'])){
                $is_default = true;
                break;
            }
        }
        
        if($is_default) return __('Choosen name is reserved for default Item Elements. Please choose a different name', ADAMLABS_GALLERY_TEXTDOMAIN);
            
        $data['settings'] = self::clean_settings_from_elements($data['settings']);
        
		$data = apply_filters('adamlabsgallery_insert_adamlabsgallery_item_element', $data);
		
		$response = $wpdb->insert($table_name, array('name' => $data['name'], 'handle' => sanitize_title($data['name']), 'settings' => json_encode($data['settings'])));
		
		if($response === false) return false;
		
        return true;
	}
    
    
    /**
	 * Delete Item Element by handle from Database
	 */
    public static function delete_element_by_handle($data){
        global $wpdb;
		
		$data = apply_filters('adamlabsgallery_delete_element_by_handle', $data);
		
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_ELEMENTS;
        
        if(empty($data['handle'])) return __('Element Item does not exist', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        //check if element is default element (these are not deletable)
        $default = self::getDefaultElementsArray();
        
        $is_default = false;
        foreach($default as $handle => $settings){
            if($handle == $data['handle']){
                $is_default = true;
                break;
            }
        }
        
        if($is_default) return __('Default Item Elements can\'t be deleted', ADAMLABS_GALLERY_TEXTDOMAIN);
            
        $response = $wpdb->delete($table_name, array('handle' => $data['handle']));
		if($response === false) return __('Element Item could not be deleted', ADAMLABS_GALLERY_TEXTDOMAIN);
        
        return true;
    }
    
    
    /**
	 * Clean the element- from the settings
	 */
    public static function clean_settings_from_elements($settings){
        if(empty($settings)) return $settings;
        if(!is_array($settings)) return str_replace('element-', '', $settings);
        
        $clean_setting = array();
        
        foreach($settings as $key => $value){
            $clean_setting[str_replace('element-', '', $key)] = $value;
        }
        
        return apply_filters('adamlabsgallery_clean_settings_from_elements', $clean_setting, $settings);
    }
    
    /**
	 * Get Array of Text Elements
	 */
	public static function getTextElementsArray(){
		global $wpdb;
		
		$custom = array();
		
        $elements = self::get_adamlabsgallery_item_elements();
        
		if(!empty($elements)){
			foreach($elements as $element){
				$custom[$element['handle']] = array('id' => $element['id'], 'name' => $element['name'], 'settings' => json_decode($element['settings'], true));
			}
		}
		
		AdamLabsGallery_Base::stripslashes_deep($custom);
		
		return apply_filters('adamlabsgallery_getTextElementsArray', $custom, $elements);
	}
    
    
	/**
	 * Get Array of Special Elements
	 */
	public static function getSpecialElementsArray(){
        
		$default = array(
            'adamlabsgallery-line-break' => array(
                'id' => '-1',
                'name' => 'adamlabsgallery-line-break',
                'display' => '<i class="adamlabsgallery-icon-level-down"></i><span>'.__('LINEBREAK ELEMENT', ADAMLABS_GALLERY_TEXTDOMAIN).'</span>',
                'settings' => array(
                    'background-color' => '#FFFFFF',
                    'bg-alpha' => '20',
                    'clear' => 'both',
                    'border-width' => '0',
                    'color' => 'transparent',
                    'display' => 'block',
                    'font-size' => '10',
                    'font-style' => 'italic',
                    'font-weight' => '700',
                    'line-height' => '5',
                    'margin' => array('0', '0', '0', '0'),
                    'padding' => array('0', '0', '0', '0'),
                    'text-align' => 'center',
                    'transition' => 'none',
                    'text-transform' => 'uppercase',
					'letter-spacing' => 'normal',
                    'source' => 'text',
                    'source-text' => __('LINE-BREAK', ADAMLABS_GALLERY_TEXTDOMAIN),
                    'special' => 'true',
					'special-type' => 'line-break'
                )
            )
		);
		
		return apply_filters('adamlabsgallery_getSpecialElementsArray', $default);
	}
	
	
	/**
	 * Get Array of Additional Elements
	 */
	public static function getAdditionalElementsArray(){
        
		$default = array(
			'adamlabsgallery-blank-element' => array(
                'id' => '-2',
                'name' => 'adamlabsgallery-blank-element',
                'display' => '<i class="adamlabsgallery-icon-doc"></i><span>'.__('Blank HTML', ADAMLABS_GALLERY_TEXTDOMAIN).'</span>',
                'settings' => array(
                    'background-color' => 'transparent',
                    'source-text-style-disable' => 'true',
                    'bg-alpha' => '20',
                    'clear' => 'both',
                    'border-width' => '0',
                    'color' => '#000000',
                    'display' => 'block',
                    'font-size' => '13',
                    'font-weight' => '400',
                    'line-height' => '15',
                    'margin' => array('0', '0', '0', '0'),
                    'padding' => array('0', '0', '0', '0'),
                    'text-align' => 'center',
                    'transition' => 'none',
                    'source' => 'text',
                    'source-text' => __('Blank HTML', ADAMLABS_GALLERY_TEXTDOMAIN),
					'special' => 'true',
					'special-type' => 'blank-element'
                )
            )
		);
		
		return apply_filters('adamlabsgallery_getAdditionalElementsArray', $default);
	}
	
	
	/**
	 * Get Array of Post Elements
	 */
	public static function getPostElementsArray(){
		
		$post = array(
			'title' => array('name' => __('Title', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'cat_list' => array('name' => __('Cat. List', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'tag_list' => array('name' => __('Tag List', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'excerpt' => array('name' => __('Excerpt', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'meta' => array('name' => __('Meta', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'num_comments' => array('name' => __('Num. Comments', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date' => array('name' => __('Date', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_day' => array('name' => __('Date Day', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_month' => array('name' => __('Date Month', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_month_abbr' => array('name' => __('Date Month Abbr.', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_year' => array('name' => __('Date Year', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_year_abbr' => array('name' => __('Date Year Abbr.', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'date_modified' => array('name' => __('Date Modified', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_name' => array('name' => __('Author Name', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_name' => array('name' => __('Author Name', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_profile' => array('name' => __('Author Website', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_posts' => array('name' => __('Author Posts Page', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_avatar_32' => array('name' => __('Author Avatar 32px', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_avatar_64' => array('name' => __('Author Avatar 64px', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_avatar_96' => array('name' => __('Author Avatar 96px', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'author_avatar_512' => array('name' => __('Author Avatar 512px', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'post_id' => array('name' => __('Post ID', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'post_url' => array('name' => __('Post URL', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'content' => array('name' => __('Post Content', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'alternate-image' => array('name' => __('Alt. Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'image'),
			'alias' => array('name' => __('Alias', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'taxonomy' => array('name' => __('Taxonomy List', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'caption' => array('name' => __('Caption', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'likespost' => array('name' => __('Likes (Posts)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'likes' => array('name' => __('Likes (Facebook,Twitter,YouTube,Vimeo,Instagram)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'likes_short' => array('name' => __('Likes Short (Facebook,Twitter,YouTube,Vimeo,Instagram)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'dislikes' => array('name' => __('Dislikes (YouTube)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'dislikes_short' => array('name' => __('Dislikes Short (YouTube)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'favorites' => array('name' => __('Favorites (YouTube, flickr)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'favorites_short' => array('name' => __('Favorites Short (YouTube, flickr)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'retweets' => array('name' => __('Retweets (Twitter)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'retweets_short' => array('name' => __('Retweets Short (Twitter)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'views'	=> array('name' => __('Views (flickr,YouTube, Vimeo)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'views_short'	=> array('name' => __('Views Short (flickr,YouTube, Vimeo)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'itemCount' => array('name' => __('Playlist Item Count (YouTube)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'channel_title' => array('name' => __('Channel Title (YouTube)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'duration' => array('name' => __('Duration (Vimeo)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'iframe' => array('name' => __('iFrame (url)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'text'),
			'revslider' => array('name' => __('Slider Revolution', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'revslider'),
			'adamlabsgallery' => array('name' => __('Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'adamlabsgallery'),
			'wistia' => array('name' => __('Wistia Video (ID)', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'wistia')
		);
		
		$post = apply_filters('adamlabsgallery_post_meta_handle', $post); //stays for backwards compatibility
		$post = apply_filters('adamlabsgallery_getPostElementsArray', $post);
		
		return $post;
	}
	
	
	/**
	 * Get Array of Event Elements
	 */
	public static function getEventElementsArray(){
		
		$event = array(
			'event_start_date' => array('name' => __('Event Start Date', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_end_date' => array('name' => __('Event End Date', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_start_time' => array('name' => __('Event Start Time', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_end_time' => array('name' => __('Event End Time', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_event_id' => array('name' => __('Event Event ID', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_name' => array('name' => __('Event Location Name', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_slug' => array('name' => __('Event Location Slug', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_address' => array('name' => __('Event Location Address', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_town' => array('name' => __('Event Location Town', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_state' => array('name' => __('Event Location State', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_postcode' => array('name' => __('Event Location Postcode', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_region' => array('name' => __('Event Location Region', ADAMLABS_GALLERY_TEXTDOMAIN)),
			'event_location_country' => array('name' => __('Event Location Country', ADAMLABS_GALLERY_TEXTDOMAIN))
		);
		
		return apply_filters('adamlabsgallery_getEventElementsArray', $event);
	}
	
	
	/**
	 * Get Array of Default Elements
	 */
	public static function getDefaultElementsArray(){
		
        $default = array();
		
		include('assets/default-item-elements.php');
		
		$default = apply_filters('adamlabsgallery_add_default_item_elements', $default); //stays for backwards compatibility
		$default = apply_filters('adamlabsgallery_getDefaultElementsArray', $default);
		
		return $default;
	}
	
	
	/**
	 * Get Array of Elements
	 */
	public static function prepareElementsForEditor($elements, $set_loaded = false){
		$html = '';
		$load_class = '';
		
        if($set_loaded == true)
			$load_class = ' adamlabsgallery-newli';
		
		foreach($elements as $handle => $element){
            $styles = '';
            $filter_type = 'text';
            $data_id = 1;
            if(isset($element['settings']) && !empty($element['settings'])){
                //$styles = self::get_css_from_settings($element['settings']);
                
                if($element['settings']['source'] == 'icon'){
                    $text = '<i class="'.$element['settings']['source-icon'].'"></i>';
                }elseif($element['settings']['source'] == 'text'){
                    $text = $element['settings']['source-text'];
                }else{
                    $text = $element['name'];
                }
                
                if($element['settings']['source'] == 'icon') $filter_type = 'icon';
                
                $data_id = $element['id'];
                
            }else{
                $text = $element['name'];
            }
            
            $sort_title = strip_tags($text);
            if(trim($sort_title) == ''){
                $sort_title = 'unsorted';
            }else{
                $sort_title = strtolower(substr($sort_title, 0, 1));
            }
            
			
			
            if(isset($element['default']) && $element['default'] == 'true') $filter_type.= ' filter-default';
            
            $html.= '<li class="filterall filter-'.$filter_type.$load_class.'" data-title="'.$sort_title.'" data-date="'.$data_id.'">'."\n";
            $html.= '   <div class="adamlabsgallery-entry-content">';
            $html.= '       <div class="adamlabsgallery-elements-format-wrapper"><div class="skin-dz-elements" data-handle="'.$handle.'"'.$styles.'>';
            $html.= $text;
			$html.= '       </div></div>'."\n";
            $html.= '   </div>'."\n";
            $html.= '</li>'."\n";
			
		}
		
		return apply_filters('adamlabsgallery_prepareElementsForEditor', $html, $elements, $set_loaded);
	}
	
	/**
	 * Get Array of Special Elements
	 */
	public static function prepareSpecialElementsForEditor(){
		$html = '';
        
        $elements = self::getSpecialElementsArray();
        
		foreach($elements as $handle => $element){
            $styles = '';
            
            if(isset($element['settings']) && !empty($element['settings'])){
                //$styles = self::get_css_from_settings($element['settings']);
                
                $text = $element['display'];
                
            }else{
                $text = $element['name'];
            }
            
            
            $html.= '<div class="skin-dz-elements adamlabsgallery-special-element" data-handle="'.$handle.'"'.$styles.'>';
            $html.= $text;
			$html.= '</div>'."\n";
			
		}
		
		return apply_filters('adamlabsgallery_prepareSpecialElementsForEditor', $html, $elements);
	}
	
	
	/**
	 * Get Array of Additional Elements
	 */
	public static function prepareAdditionalElementsForEditor(){
		$html = '';
        
        $elements = self::getAdditionalElementsArray();
        
		foreach($elements as $handle => $element){
            $styles = '';
            
            if(isset($element['settings']) && !empty($element['settings'])){
                //$styles = self::get_css_from_settings($element['settings']);
                
                $text = $element['display'];
				
            }else{
				$text = $element['name'];
			}
            
            
            $html.= '<div style="margin-left: 15px;" class="skin-dz-elements adamlabsgallery-special-blank-element adamlabsgallery-additional-element" data-handle="'.$handle.'"'.$styles.'>';
            $html.= $text;
			$html.= '</div>'."\n";
			
		}
		
		return apply_filters('adamlabsgallery_prepareAdditionalElementsForEditor', $html, $elements);
	}
	
	
	/**
	 * Get Array of Default Elements
	 */
	public static function prepareDefaultElementsForEditor(){
		$elements = self::getDefaultElementsArray();
		$elements = apply_filters('adamlabsgallery_prepareDefaultElementsForEditor', $elements);
		
		return self::prepareElementsForEditor($elements, true);
	}
	
	/**
	 * Get Array of Post Elements
	 */
	public static function prepareTextElementsForEditor(){
		$elements = self::getTextElementsArray();
		$elements = apply_filters('adamlabsgallery_prepareTextElementsForEditor', $elements);
		
		return self::prepareElementsForEditor($elements, true);
	}
	
	
	/**
	 * Get Array of Elements
	 */
	public static function getElementsForJavascript(){
		$default = self::getDefaultElementsArray();
		$text = self::getTextElementsArray();
		$special = self::getSpecialElementsArray();
		$additional = self::getAdditionalElementsArray();
		
		$all = array_merge($default, $text, $special, $additional);
		
		return apply_filters('adamlabsgallery_getElementsForJavascript', $all);
	}
	
	/**
	 * Get Array of Elements
	 */
	public static function getElementsForDropdown(){
		
		$post = self::getPostElementsArray();
		//$event = self::getEventElementsArray();
		
		$all['post'] = $post;
		//$all['event'] = $event;
		
		if(AdamLabsGallery_Woocommerce::is_woo_exists()){
			$woocommerce = array();
			$tmp_wc = AdamLabsGallery_Woocommerce::get_meta_array();
			
			foreach($tmp_wc as $handle => $name){
				$woocommerce[$handle]['name'] = $name;
			}
			
			$all['woocommerce'] = $woocommerce;
		}
		
		return apply_filters('adamlabsgallery_getElementsForDropdown', $all);
	}
    
    /**
	 * create css from settings
	 */
    /*public static function get_css_from_settings($settings){
        $existing = self::get_existing_elements(true);
        
        $styles = ' style="';
        
        foreach($settings as $setting => $value){
            $style = str_replace('element-', '', $setting);
            if(isset($existing[$style])){
                if($existing[$style]['value'] == 'int') $value = intval($value);
                
                if($value != '') $styles .= $style.': '.$value.$existing[$style]['unit'].'; ';
                
            }
        }
        
        $styles .= '" ';
        
        return $styles;
    }*/
    
    /**
	 * create css from settings
	 */
    public static function get_existing_elements($only_styles = false){
		
        $styles = array(
                'font-size'         => array('value' => 'int',
                                             'type' => 'text-slider',
                                             'values' => array('min' =>'6', 'max' =>'120', 'step' =>'1', 'default' =>'12'),
                                             'style' => 'idle',
                                             'unit' => 'px'),
                                             
                'line-height'       => array('value' => 'int',
                                             'type' => 'text-slider',
                                             'values' => array('min' =>'7', 'max' =>'150', 'step' =>'1', 'default' =>'14'),
                                             'style' => 'idle',
                                             'unit' => 'px'),
                                             
                'color'             => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#000'),
                                             'style' => 'idle',
                                             'unit' => ''),
                                             
                'font-family'       => array('value' => 'string',
                                             'values' => array('default' =>''),
                                             'style' => 'idle',
                                             'type' => 'text',
                                             'unit' => ''),
                                             
                'font-weight'       => array('value' => 'string',
                                             'values' => array('default' =>'400'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'text-decoration'  => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'font-style'        => array('value' => 'string',
                                             'values' => array('default' =>false),
                                             'style' => 'idle',
                                             'type' => 'checkbox',
                                             'unit' => ''),
                
                'text-transform'    => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
											 
				'letter-spacing'    => array('value' => 'string',
                                             'values' => array('default' =>'normal'),
                                             'style' => 'idle',
                                             'type' => 'text',
                                             'unit' => ''),
                
                'display'           => array('value' => 'string',
                                             'values' => array('default' =>'inline-block'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'float'             => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
            
                'text-align'        => array('value' => 'string',
                                             'values' => array('default' =>'center'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'clear'             => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'margin'            => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'idle',
                                             'unit' => 'px'),
                                             
                'padding'           => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'idle',
                                             'unit' => 'px'),
                                             
                'border'            => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'idle',
                                             'unit' => 'px'),      
                                             
                'border-radius'     => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'idle',
                                             'unit' => array('px', 'percentage')),
                                             
                'border-color'      => array('value' => 'string',
                                             'values' => array('default' =>'transparent'),
                                             'style' => 'idle',
                                             'type' => 'colorpicker',
                                             'unit' => ''),
                                             
                'border-style'      => array('value' => 'string',
                                             'values' => array('default' =>'solid'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),                            
                                             
                'background-color'  => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#FFF'),
                                             'style' => 'idle',
                                             'unit' => ''),
                                             
                'bg-alpha'          => array('value' => 'string',
                                             'values' => array('min' =>'0', 'max' =>'100', 'step' =>'1', 'default' =>'100'),
                                             'style' => 'false',
                                             'type' => 'text-slider',
                                             'unit' => ''),
                                             
                /*'background-size'   => array('value' => 'string',
                                             'values' => array('default' =>'cover'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'background-repeat'  => array('value' => 'string',
                                             'values' => array('default' =>'no-repeat'),
                                             'style' => 'idle',
                                             'type' => 'select',
                                             'unit' => ''),
                 */                            
                'shadow-color'       => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#000'),
                                             'style' => 'false',
                                             'unit' => ''),   
                                             
                'shadow-alpha'       => array('value' => 'string',
                                             'values' => array('min' =>'0', 'max' =>'100', 'step' =>'1', 'default' =>'100'),
                                             'style' => 'false',
                                             'type' => 'text-slider',
                                             'unit' => ''),
                                             
                'box-shadow'         => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'idle',
                                             'unit' => 'px'),
                                             
                'position'         	=> array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' => 'relative'),
                                             'style' => 'idle',
                                             'unit' => ''),
                                             
                'top-bottom'	=> array('value' => 'int',
                                             'type' => 'text',
                                             'values' => array('default' => '0'),
                                             'style' => 'false',
                                             'unit' => 'px'),
                                             //'unit' => array('px', 'percentage')),
											 
                'left-right'	=> array('value' => 'int',
                                             'type' => 'text',
                                             'values' => array('default' => '0'),
                                             'style' => 'false',
                                             'unit' => 'px')
                                             
            );
			
        $styles = apply_filters('adamlabsgallery_get_existing_elements_styles', $styles, $only_styles);
		
        $hover_styles = array(
                'font-size-hover'         => array('value' => 'int',
                                             'type' => 'text-slider',
                                             'values' => array('min' =>'6', 'max' =>'120', 'step' =>'1', 'default' =>'12'),
                                             'style' => 'hover',
                                             'unit' => 'px'),
                                             
                'line-height-hover'       => array('value' => 'int',
                                             'type' => 'text-slider',
                                             'values' => array('min' =>'7', 'max' =>'150', 'step' =>'1', 'default' =>'14'),
                                             'style' => 'hover',
                                             'unit' => 'px'),
                                             
                'color-hover'             => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#000'),
                                             'style' => 'hover',
                                             'unit' => ''),
                                             
                'font-family-hover'       => array('value' => 'string',
                                             'values' => array('default' =>''),
                                             'style' => 'hover',
                                             'type' => 'text',
                                             'unit' => ''),
                                             
                'font-weight-hover'       => array('value' => 'string',
                                             'values' => array('default' =>'400'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'text-decoration-hover'  => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'font-style-hover'        => array('value' => 'string',
                                             'values' => array('default' =>false),
                                             'style' => 'hover',
                                             'type' => 'checkbox',
                                             'unit' => ''),
                
                'text-transform-hover'    => array('value' => 'string',
                                             'values' => array('default' =>'none'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),
											 
				'letter-spacing-hover'    => array('value' => 'string',
                                             'values' => array('default' =>'normal'),
                                             'style' => 'hover',
                                             'type' => 'text',
                                             'unit' => ''),
                                             
                'border-hover'            => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'hover',
                                             'unit' => 'px'),      
                                             
                'border-radius-hover'     => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'hover',
                                             'unit' => array('px', 'percentage')),
                                             
                'border-color-hover'      => array('value' => 'string',
                                             'values' => array('default' =>'transparent'),
                                             'style' => 'hover',
                                             'type' => 'colorpicker',
                                             'unit' => ''),
                                             
                'border-style-hover'      => array('value' => 'string',
                                             'values' => array('default' =>'solid'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),                            
                                             
                'background-color-hover'  => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#FFF'),
                                             'style' => 'hover',
                                             'unit' => ''),
                                             
                'bg-alpha-hover'          => array('value' => 'string',
                                             'values' => array('min' =>'0', 'max' =>'100', 'step' =>'1', 'default' =>'100'),
                                             'style' => 'false',
                                             'type' => 'text-slider',
                                             'unit' => ''),
                                             
                /*'background-size-hover'   => array('value' => 'string',
                                             'values' => array('default' =>'cover'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),
                                             
                'background-repeat-hover'  => array('value' => 'string',
                                             'values' => array('default' =>'no-repeat'),
                                             'style' => 'hover',
                                             'type' => 'select',
                                             'unit' => ''),
                 */                            
                'shadow-color-hover'       => array('value' => 'string',
                                             'type' => 'colorpicker',
                                             'values' => array('default' =>'#000'),
                                             'style' => 'false',
                                             'unit' => ''),   
                                             
                'shadow-alpha-hover'       => array('value' => 'string',
                                             'values' => array('min' =>'0', 'max' =>'100', 'step' =>'1', 'default' =>'100'),
                                             'style' => 'false',
                                             'type' => 'text-slider',
                                             'unit' => ''),
                                             
                'box-shadow-hover'         => array('value' => 'int',
                                             'type' => 'multi-text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'hover',
                                             'unit' => 'px')
            );
			
        $hover_styles = apply_filters('adamlabsgallery_get_existing_elements_hover_styles', $hover_styles, $only_styles);
		
        $other = array();
            
        if(!$only_styles){
            $other = array(
                'source'            => array('value' => 'string', 
                                             'type' => 'select',
                                             'values' => array('default' =>'post'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'transition'        => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'fade'),
                                             'style' => 'attribute',
                                             'unit' => ''),
                
                'source-separate'	=> array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>','),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
				'source-catmax'	    => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'-1'),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
				'always-visible-desktop' => array('value' => 'string',
                                             'type' => 'checkbox',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
											 
				'always-visible-mobile' => array('value' => 'string',
                                             'type' => 'checkbox',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'source-function'	=> array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'link'),
                                             'style' => 'attribute',
                                             'unit' => ''),
                
                'limit-type'        	=> array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'none'),
                                             'style' => 'attribute',
                                             'unit' => ''),
                
                'limit-num'        	=> array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'10'),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
				'min-height'        => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
				'max-height'        => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'none'),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
                /*'split'       		=> array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'full'),
                                             'style' => 'attribute',
                                             'unit' => ''), */
                
                'transition-type'   => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'delay'             => array('value' => 'string',
                                             'type' => 'text-slider',
                                             'values' => array('min' =>'0', 'max' =>'60', 'step' =>'1', 'default' =>'10'),
                                             'style' => 'attribute',
                                             'unit' => ''),
											 
				'duration'             => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'default'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'link-type'             => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'none'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'hideunder'         => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'hideunderheight'         => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>'0'),
                                             'style' => 'false',
                                             'unit' => ''),
											 
                'hidetype'    	     => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'visibility'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'hide-on-video'		=> array('value' => 'string',
                                             'type' => 'select', //was checkbock before with values 'false', 'true'
                                             'values' => array('default' => false),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'show-on-lightbox-video'=> array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' => false),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'enable-hover' => array('value' => 'string',
                                             'type' => 'checkbox',
                                             'values' => array('default' =>false),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'attribute' => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'class' => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'rel' => array('value' => 'string',
                                             'type' => 'text',
                                             'values' => array('default' =>''),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'tag-type' => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'div'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'force-important' => array('value' => 'string',
                                             'type' => 'checkbox',
                                             'values' => array('default' =>true),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'align' => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'t_l'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'link-target' => array('value' => 'string',
                                             'type' => 'select',
                                             'values' => array('default' =>'_self'),
                                             'style' => 'false',
                                             'unit' => ''),
                
                'source-text-style-disable' => array('value' => 'string',
                                             'type' => 'checkbox',
                                             'values' => array('default' =>false),
                                             'style' => 'false',
                                             'unit' => '')
            );
			
			if(AdamLabsGallery_Woocommerce::is_woo_exists()){
				$other['show-on-sale']		= array('value' => 'string',
																'type' => 'checkbox',
																'values' => array('default' => false),
																'style' => 'false',
																'unit' => '');
				$other['show-if-featured']	= array('value' => 'string',
																'type' => 'checkbox',
																'values' => array('default' => false),
																'style' => 'false',
																'unit' => '');
			}
			
			$other = apply_filters('adamlabsgallery_get_existing_elements_other', $other, $only_styles);
        }
        
        $styles = array_merge($styles, $other, $hover_styles);
        
        return apply_filters('adamlabsgallery_get_existing_elements', $styles, $only_styles);
    }
	
	
	/**
	 * get list of allowed styles on tags
	 */
    public static function get_allowed_styles_for_tags(){
		
		return apply_filters('adamlabsgallery_get_allowed_styles_for_tags',
			array(
				'font-size',
                'line-height',
                'color',
                'font-family',
                'font-weight',
                'text-decoration',
                'font-style',
                'text-transform',
				'letter-spacing',
                'background-color'
			)
		);
		
	}
	
	
	/**
	 * get list of allowed styles on tags
	 */
    public static function get_allowed_styles_for_cat_tag(){
		
		return apply_filters('adamlabsgallery_get_allowed_styles_for_cat_tag',
			array(
				'font-size',
                'line-height',
                'color',
                'font-family',
                'font-weight',
                'text-decoration',
                'font-style',
                'text-transform',
				'letter-spacing',
			)
		);
		
	}
	
	
	/**
	 * get list of allowed styles on wrap
	 */
    public static function get_allowed_styles_for_wrap(){
		
		return apply_filters('adamlabsgallery_get_allowed_styles_for_wrap',
			array(
				'display',
				'clear',
                'position',
                'text-align',
                'margin',
                'float',
                'left',
                'top',
                'right',
                'bottom'
			)
		);
		
	}
	
	
	/**
	 * get list of allowed styles on wrap
	 */
    public static function get_wait_until_output_styles(){
		
		return apply_filters('adamlabsgallery_get_wait_until_output_styles',
			array(
				'border-style' => array(
						'wait' => array('border', 'border-color', 'border-style', 'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width'),
						'not-if' => 'none'
					),
				'border-style-hover' => array(
						'wait' => array('border-hover', 'border-color-hover', 'border-style-hover', 'border-top-width-hover', 'border-right-width-hover', 'border-bottom-width-hover', 'border-left-width-hover'),
						'not-if' => 'none'
					),
				'box-shadow' => array(
						'wait' => array('box-shadow'),
						'not-if' => array('0px 0px 0px 0px', '0)')
					),
				'-moz-box-shadow' => array(
						'wait' => array('-moz-box-shadow'),
						'not-if' => array('0px 0px 0px 0px', '0)')
					),
				'-webkit-box-shadow' => array(
						'wait' => array('-webkit-box-shadow'),
						'not-if' => array('0px 0px 0px 0px', '0)')
					),
				'text-decoration' => array(
						'wait' => array('text-decoration'),
						'not-if' => 'none'
					),
				'text-transform' => array(
						'wait' => array('text-transform'),
						'not-if' => 'none'
					),
				'letter-spacing' => array(
						'wait' => array('letter-spacing'),
						'not-if' => 'normal'
					),
				'font-family' => array(
						'wait' => array('font-family'),
						'not-if' => ''
					)
			)
		);
		
	}
	
	
	/**
	 * get list of allowed things on meta
	 */
    public function get_allowed_meta(){
		$base = new AdamLabsGallery_Base();
		
		$transitions_media = $base->get_hover_animations(true); //true will get with in/out
		
		return apply_filters('adamlabsgallery_get_allowed_meta',
			array(
				array(
					'name' => array('handle' => 'color', 'text' => __('Font Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'style',
					'hover' => 'true',
					'cpmode' => 'single'
				),
				array(
					'name' => array('handle' => 'font-style', 'text' => __('Font Style', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'select',
					'default' => 'normal',
					'values' => array('normal'=>__('Normal', ADAMLABS_GALLERY_TEXTDOMAIN),'italic'=>__('Italic', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'text-decoration', 'text' => __('Text Decoration', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'select',
					'default' => 'none',
					'values' => array('none'=>__('None', ADAMLABS_GALLERY_TEXTDOMAIN),'underline'=>__('Underline', ADAMLABS_GALLERY_TEXTDOMAIN),'overline'=>__('Overline', ADAMLABS_GALLERY_TEXTDOMAIN),'line-through'=>__('Line Through', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'text-transform', 'text' => __('Text Transform', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'select',
					'default' => 'none',
					'values' => array('none'=>__('None', ADAMLABS_GALLERY_TEXTDOMAIN),'capitalize'=>__('Capitalize', ADAMLABS_GALLERY_TEXTDOMAIN),'uppercase'=>__('Uppercase', ADAMLABS_GALLERY_TEXTDOMAIN),'lowercase'=>__('Lowercase', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'letter-spacing', 'text' => __('Letter Spacing', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'text',
					'default' => 'normal',
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'border-color', 'text' => __('Border Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'style',
					'hover' => 'true',
					'cpmode' => 'single'
				),
				array(
					'name' => array('handle' => 'border-style', 'text' => __('Border Style', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'select',
					'default' => 'none',
					'values' => array('none'=>__('None', ADAMLABS_GALLERY_TEXTDOMAIN),'solid'=>__('solid', ADAMLABS_GALLERY_TEXTDOMAIN),'dotted'=>__('dotted', ADAMLABS_GALLERY_TEXTDOMAIN),'dashed'=>__('dashed', ADAMLABS_GALLERY_TEXTDOMAIN),'double'=>__('double', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'background', 'text' => __('Background Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'style',
					'hover' => 'true',
					'cpmode' => 'full'
				),
				array(
					'name' => array('handle' => 'box-shadow', 'text' => __('Box Shadow', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'text',
					'default' => '0px 0px 0px 0px #000000',
					'container' => 'style',
					'hover' => 'true'
				),
				array(
					'name' => array('handle' => 'transition', 'text' => __('Transition', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'select',
					'default' => 'fade',
					'values' => $transitions_media,
					'container' => 'anim'
				),
				array(
					'name' => array('handle' => 'transition-delay', 'text' => __('Transition Delay', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'number',
					'default' => '0',
					'values' => array('0', '60', '1'),
					'container' => 'anim'
				),
				array(
					'name' => array('handle' => 'cover-bg-color', 'text' => __('Cover BG Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'layout',
					'cpmode' => 'full'
				),
				/*
				array(
					'name' => array('handle' => 'cover-bg-opacity', 'text' => __('Cover BG Opacity', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'number',
					'default' => '100',
					'container' => 'layout'
				),
				*/
				array(
					'name' => array('handle' => 'item-bg-color', 'text' => __('Item BG Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'layout',
					'cpmode' => 'full'
				),
				array(
					'name' => array('handle' => 'content-bg-color', 'text' => __('Content BG Color', ADAMLABS_GALLERY_TEXTDOMAIN)),
					'type' => 'color',
					'default' => '#FFFFFF',
					'container' => 'layout',
					'cpmode' => 'full'
				)
				
			)
		);
		
	}
}