<?php
if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Base {
	
	/**
	 * Get $_GET Parameter
	 */
	public static function getGetVar($key,$default = "",$type=""){
		$val = self::getVar($_GET, $key, $default, $type);
		return apply_filters('adamlabsgallery_getGetVar', $val, $key, $default, $type);
	}


	/**
	 * Get $_POST Parameter
	 */
	public static function getPostVar($key,$default = "",$type=""){
		$val = self::getVar($_POST, $key, $default, $type);
		return apply_filters('adamlabsgallery_getPostVar', $val, $key, $default, $type);
	}


	/**
	 * Get $_POST/$_GET Parameter
	 */
	public static function getVar($arr,$key,$default = "", $type=""){
		$val = $default;
		if(isset($arr[$key]) && !empty($arr[$key])) $val = $arr[$key];

		switch($type){
			case 'i': //int
				$val = intval($val);
			break;
			case 'f': //float
				$val = floatval($val);
			break;
			case 'r': //raw meaning, do nothing
			break;
			default:
				$val = AdamLabsGallery_Base::stripslashes_deep($val);
			break;
		}
		
		// changed so local admin "default img" option can exist separately (i.e. be "removed", etc.)
		/* 
		if($key == "default-image" && empty($val)){
			$val = get_option('adamlabsgallery_global_default_img', '');
		}
		*/
		
		return apply_filters('adamlabsgallery_getVar', $val, $arr, $key, $default, $type);
	}


	/**
	 * Throw exception
	 */
	public static function throw_error($message,$code=null){
		$a = apply_filters('adamlabsgallery_throw_error', array('message' => $message, 'code' => $code));
		
		if(!empty($code))
			throw new Exception($a['message'],$a['code']);
		else
			throw new Exception($a['message']);
	}


	/**
	 * Sort Array by Value order
	 */
	public static function sort_by_order($a,$b){
        if(!isset($a['order']) || !isset($b['order'])) return 0;
		$a = $a['order'];
		$b = $b['order'];
		return (($a < $b) ? -1 : (($a > $b) ? 1 : 0));
	}


    /**
	 * change hex to rgba
	 */
    public static function hex2rgba($hex, $transparency = false) {
        if($transparency !== false){
			$transparency = ($transparency > 0) ? number_format( ( $transparency / 100 ), 2, ".", "" ) : 0;
        }else{
            $transparency = 1;
        }

        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return apply_filters('adamlabsgallery_hex2rgba', 'rgba('.$r.', '.$g.', '.$b.', '.$transparency.')', $hex, $transparency);

    }


	/**
	 * strip slashes recursive
	 */
	public static function stripslashes_deep($value){
		$value = is_array($value) ?
			array_map( array('AdamLabsGallery_Base', 'stripslashes_deep'), $value) :
			stripslashes($value);

		return apply_filters('adamlabsgallery_stripslashes_deep', $value);
	}


	/**
	 * get text intro, limit by number of words
	 */
	public static function get_text_intro($text, $limit, $type = 'words'){

		$intro = $text;

		if($type == 'words'){
			$arrIntro = explode(' ', $text, $limit);

			if (count($arrIntro)>=$limit) {
				array_pop($arrIntro);
				$intro = implode(" ",$arrIntro);
				$intro = trim($intro);
				if(!empty($intro))
					$intro .= '...';
			} else {
				$intro = implode(" ",$arrIntro);
			}
		}elseif($type == 'chars'){
			$text = strip_tags($text);
			$intro = mb_substr($text, 0, $limit, 'utf-8');
			if(strlen($text) > $limit) $intro .= '...';
		}
		elseif($type == 'sentence'){
			$text = strip_tags($text);
			//$intro = mb_substr($text, 0, $limit, 'utf-8');
			//if(strlen($text) > $limit) $intro .= '...';
			$intro = AdamLabsGallery_Base::bac_variable_length_excerpt($text,$limit);
		}

		$intro = preg_replace('`\[[^\]]*\]`','',$intro);

		return apply_filters('adamlabsgallery_get_text_intro', $intro, $text, $limit, $type);
	}

	public static function bac_variable_length_excerpt($text, $length=1, $finish_sentence=1){
	       
	     $tokens = array();
	     $out = '';
	     $word = 0;
	   
	    //Divide the string into tokens; HTML tags, or words, followed by any whitespace.
	    $regex = '/(<[^>]+>|[^<>\s]+)\s*/u';
	    preg_match_all($regex, $text, $tokens);
	    foreach ($tokens[0] as $t){ 
	        //Parse each token
	        if ($word >= $length && !$finish_sentence){ 
	            //Limit reached
	            break;
	        }
	        if ($t[0] != '<'){ 
	            //Token is not a tag. 
	            //Regular expression that checks for the end of the sentence: '.', '?' or '!'
	            $regex1 = '/[\?\.\!]\s*$/uS';
	            if ($word >= $length && $finish_sentence && preg_match($regex1, $t) == 1){ 
	                //Limit reached, continue until ? . or ! occur to reach the end of the sentence.
	                $out .= trim($t);
	                break;
	            }   
	            $word++;
	        }
	        //Append what's left of the token.
	        $out .= $t;     
	    }
	    //Add the excerpt ending as a link.
	    $excerpt_end = '';
	     
	    //Add the excerpt ending as a non-linked ellipsis with brackets.
	    //$excerpt_end = ' [&hellip;]';
	     
	    //Append the excerpt ending to the token. 
	    $out .= $excerpt_end;
	     
	    return trim(force_balance_tags($out)); 
	}


	/**
	 * Get all images sizes + custom added sizes
	 */
	public function get_all_image_sizes(){
		$custom_sizes = array();
		$added_image_sizes = get_intermediate_image_sizes();
		if(!empty($added_image_sizes) && is_array($added_image_sizes)){
			foreach($added_image_sizes as $key => $img_size_handle){
				$custom_sizes[$img_size_handle] = ucwords(str_replace('_', ' ', $img_size_handle));
			}
		}
		$img_orig_sources = array(
			'full' => __('Original Size', ADAMLABS_GALLERY_TEXTDOMAIN),
			'thumbnail' => __('Thumbnail', ADAMLABS_GALLERY_TEXTDOMAIN),
			'medium' => __('Medium', ADAMLABS_GALLERY_TEXTDOMAIN),
			'large' => __('Large', ADAMLABS_GALLERY_TEXTDOMAIN)
		);
		
		return apply_filters('adamlabsgallery_get_all_image_sizes', array_merge($img_orig_sources, $custom_sizes));
	}

	/**
	 * Get all media filtes 
	 */
	public function get_all_media_filters(){
		$custom_sizes = array();
		$added_image_sizes = get_intermediate_image_sizes();
		if(!empty($added_image_sizes) && is_array($added_image_sizes)){
			foreach($added_image_sizes as $key => $img_size_handle){
				$custom_sizes[$img_size_handle] = ucwords(str_replace('_', ' ', $img_size_handle));
			}
		}
		$media_filter_sources = array(
			'none' => __('No Filter', ADAMLABS_GALLERY_TEXTDOMAIN),
			'_1977' => __('1977', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aden' => __('Aden', ADAMLABS_GALLERY_TEXTDOMAIN),
			'brooklyn' => __('Brooklyn', ADAMLABS_GALLERY_TEXTDOMAIN),
			'clarendon' => __('Clarendon', ADAMLABS_GALLERY_TEXTDOMAIN),
			'earlybird' => __('Earlybird', ADAMLABS_GALLERY_TEXTDOMAIN),
			'gingham' => __('Gingham', ADAMLABS_GALLERY_TEXTDOMAIN),
			'hudson' => __('Hudson', ADAMLABS_GALLERY_TEXTDOMAIN),
			'inkwell' => __('Inkwell', ADAMLABS_GALLERY_TEXTDOMAIN),
			'lark' => __('Lark', ADAMLABS_GALLERY_TEXTDOMAIN),
			'lofi' => __('Lo-Fi', ADAMLABS_GALLERY_TEXTDOMAIN),
			'mayfair' => __('Mayfair', ADAMLABS_GALLERY_TEXTDOMAIN),
			'moon' => __('Moon', ADAMLABS_GALLERY_TEXTDOMAIN),
			'nashville' => __('Nashville', ADAMLABS_GALLERY_TEXTDOMAIN),
			'perpetua' => __('Perpetua', ADAMLABS_GALLERY_TEXTDOMAIN),
			'reyes' => __('Reyes', ADAMLABS_GALLERY_TEXTDOMAIN),
			'rise' => __('Rise', ADAMLABS_GALLERY_TEXTDOMAIN),
			'slumber' => __('Slumber', ADAMLABS_GALLERY_TEXTDOMAIN),
			'toaster' => __('Toaster', ADAMLABS_GALLERY_TEXTDOMAIN),
			'walden' => __('Walden', ADAMLABS_GALLERY_TEXTDOMAIN),
			'willow' => __('Willow', ADAMLABS_GALLERY_TEXTDOMAIN),
			'xpro2' => __('X-pro II', ADAMLABS_GALLERY_TEXTDOMAIN),
			'grayscale' => __('Grayscale', ADAMLABS_GALLERY_TEXTDOMAIN)
		);
		
		return apply_filters('adamlabsgallery_get_all_media_filters',$media_filter_sources);
	}



	/**
	 * convert date to the date format that the user chose.
	 */
	public static function convert_post_date($date){
		if(empty($date))
			return($date);
		$date = date_i18n(get_option('date_format'), strtotime($date));
		return apply_filters('adamlabsgallery_convert_post_date', $date);
	}


	/**
	 * Create Multilanguage for JavaScript
	 */
	protected static function get_javascript_multilanguage(){

		$lang = array(
			'aj_please_wait' => __('Please wait...', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aj_ajax_error'   => __('Ajax Error!!!', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aj_success_must'   => __('The \'success\' param is a must!', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aj_error_not_found'   => __('ajax error! action not found', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aj_empty_response'   => __('Empty ajax response!', ADAMLABS_GALLERY_TEXTDOMAIN),
			'aj_wrong_alias'   => __('wrong alias', ADAMLABS_GALLERY_TEXTDOMAIN),
			'delete_item_skin'   => __('Really delete choosen Item Template?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'delete_grid'   => __('Really delete the Grid?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'choose_image'   => __('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN),
			'select_choose'   => __('--- choose ---', ADAMLABS_GALLERY_TEXTDOMAIN),
			'new_element'   => __('New Element', ADAMLABS_GALLERY_TEXTDOMAIN),
			'new_element'   => __('New Element', ADAMLABS_GALLERY_TEXTDOMAIN),
			'bottom_on_hover'   => __('Bottom on Hover', ADAMLABS_GALLERY_TEXTDOMAIN),
			'top_on_hover'   => __('Top on Hover', ADAMLABS_GALLERY_TEXTDOMAIN),
			'hidden'   => __('Hidden', ADAMLABS_GALLERY_TEXTDOMAIN),
			'full_price'   => __('$99 $999', ADAMLABS_GALLERY_TEXTDOMAIN),
			'regular_price'   => __('$99', ADAMLABS_GALLERY_TEXTDOMAIN),
			'regular_price_no_cur'   => __('99', ADAMLABS_GALLERY_TEXTDOMAIN),
			'top'   => __('Top', ADAMLABS_GALLERY_TEXTDOMAIN),
			'right'   => __('Right', ADAMLABS_GALLERY_TEXTDOMAIN),
			'bottom'   => __('Bottom', ADAMLABS_GALLERY_TEXTDOMAIN),
			'left'   => __('Left', ADAMLABS_GALLERY_TEXTDOMAIN),
			'hide'   => __('Hide', ADAMLABS_GALLERY_TEXTDOMAIN),
			'single'   => __('Single', ADAMLABS_GALLERY_TEXTDOMAIN),
			'bulk'   => __('Bulk', ADAMLABS_GALLERY_TEXTDOMAIN),
			'choose_images'   => __('Choose Images', ADAMLABS_GALLERY_TEXTDOMAIN),
			'import_demo_post_heavy_loading'   => __('The following demo data will be imported: AdamLabs Gallery Posts, Custom Meta, AdamLabsFonts. This can take a while, please do not leave the site until the import is finished', ADAMLABS_GALLERY_TEXTDOMAIN),
			'import_demo_grids_210'   => __('The following demo data will be imported: Grids of the 2.1.0 update. This can take a while, please do not leave the site until the import is finished', ADAMLABS_GALLERY_TEXTDOMAIN),
			'save_settings'   => __('Save Settings', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_element'   => __('Add Element', ADAMLABS_GALLERY_TEXTDOMAIN),
			'edit_element'   => __('Edit Element', ADAMLABS_GALLERY_TEXTDOMAIN),
			'remove_this_element'   => __('Really remove this element?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'choose_skins'   => __('Choose Skins', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_selected'   => __('Add Selected', ADAMLABS_GALLERY_TEXTDOMAIN),
			'deleting_nav_skin_message'   => __('Deleting a Navigation Skin may result in missing Skins in other Grids. Proceed?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_meta'   => __('Add Meta', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_widget_area'   => __('Add Widget Area', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_font'   => __('Add Google Font', ADAMLABS_GALLERY_TEXTDOMAIN),
			'save_post_meta'   => __('Save Post Meta', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_change_widget_area_name'   => __('Are you sure the change the Widget Area name?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_delete_widget_area'   => __('Really delete this Widget Area? This can\'t be undone and if may affect existing Posts/Pages that use this Widget Area.', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_delete_meta'   => __('Really delete this meta? This can\'t be undone.', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_change_meta_effects'   => __('If you change this settings, it may affect current Posts that use this meta, proceed?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_change_font_effects'   => __('If you change this settings, it may affect current Posts that use this Font, proceed?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'handle_and_name_at_least_3'   => __('The handle and name has to be at least three characters long!', ADAMLABS_GALLERY_TEXTDOMAIN),
			'layout_settings'   => __('Layout Settings', ADAMLABS_GALLERY_TEXTDOMAIN),
			'close'   => __('Close', ADAMLABS_GALLERY_TEXTDOMAIN),
			'create_nav_skin'   => __('Save Navigation Skin', ADAMLABS_GALLERY_TEXTDOMAIN),
			'apply_changes'   => __('Save Changes', ADAMLABS_GALLERY_TEXTDOMAIN),
			'new_element_sanitize'   => __('new-element', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_delete_element_permanently'   => __('This will delete this element permanently, really proceed?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'element_name_exists_do_overwrite'   => __('Element with chosen name already exists. Really overwrite the Element?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'element_was_not_changed'   => __('Element was not created/changed', ADAMLABS_GALLERY_TEXTDOMAIN),
			'not_selected'   => __('Not Selected', ADAMLABS_GALLERY_TEXTDOMAIN),
			'class_name'   => __('Class:', ADAMLABS_GALLERY_TEXTDOMAIN),
			'class_name_short'   => __('Class', ADAMLABS_GALLERY_TEXTDOMAIN),
			'save_changes' => __('Save Changes', ADAMLABS_GALLERY_TEXTDOMAIN),
			'enter_position' => __('Enter a Position', ADAMLABS_GALLERY_TEXTDOMAIN),
			'leave_not_saved'   => __('By leaving now, all changes since the last saving will be lost. Really leave now?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'please_enter_unique_item_name' => __('Please enter a unique item name', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fontello_icons' => __('Choose Icon', ADAMLABS_GALLERY_TEXTDOMAIN),
			'please_enter_unique_element_name' => __('Please enter a unique element name', ADAMLABS_GALLERY_TEXTDOMAIN),
			'please_enter_unique_skin_name' => __('Please enter a unique Navigation Skin name', ADAMLABS_GALLERY_TEXTDOMAIN),
			'item_name_too_short' => __('Item name too short', ADAMLABS_GALLERY_TEXTDOMAIN),
			'skin_name_too_short' => __('Navigation Skin name too short', ADAMLABS_GALLERY_TEXTDOMAIN),
			'skin_name_already_registered' => __('Navigation Skin with choosen name already exists, please choose a different name', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withvimeo' => __('With Vimeo', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withyoutube' => __('With YouTube', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withwistia' => __('With Wistia', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withimage' => __('With Image', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withthtml5' => __('With HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withsoundcloud' => __('With SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN),
			'withoutmedia' => __('Without Media', ADAMLABS_GALLERY_TEXTDOMAIN),
			'selectyouritem' => __('Select Your Item', ADAMLABS_GALLERY_TEXTDOMAIN),
			'add_at_least_one_element' => __('Please add at least one element in Custom Grid mode', ADAMLABS_GALLERY_TEXTDOMAIN),
			'adamlabsgallery_shortcode_creator' => __('Portfolio Gallery Shortcode Creator', ADAMLABS_GALLERY_TEXTDOMAIN),
//			'shortcode_generator' => __('Shortcode Generator', ADAMLABS_GALLERY_TEXTDOMAIN),
			'shortcode_could_not_be_correctly_parsed' => __('Shortcode could not be parsed.', ADAMLABS_GALLERY_TEXTDOMAIN),
			'please_add_at_least_one_layer' => __('Please add at least one Layer.', ADAMLABS_GALLERY_TEXTDOMAIN),
			'shortcode_parsing_successfull' => __('Shortcode parsing successfull. Items can be found in step 3', ADAMLABS_GALLERY_TEXTDOMAIN),
			'script_will_try_to_load_last_working' => __('Portfolio Gallery will now try to go to the last working version of this grid', ADAMLABS_GALLERY_TEXTDOMAIN),
			'save_rules' => __('Save Rules', ADAMLABS_GALLERY_TEXTDOMAIN),
			'discard_changes' => __('Discard Changes', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_discard_changes' => __('Really discard changes?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'reset_fields' => __('Reset Fields', ADAMLABS_GALLERY_TEXTDOMAIN),
			'really_reset_fields' => __('Really reset fields?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'meta_val' => __('(Meta)', ADAMLABS_GALLERY_TEXTDOMAIN),
			'deleting_this_cant_be_undone' => __('Deleting this can\'t be undone, continue?', ADAMLABS_GALLERY_TEXTDOMAIN),
			'shortcode' => __('ShortCode', ADAMLABS_GALLERY_TEXTDOMAIN),
			'filter' => __('Filter', ADAMLABS_GALLERY_TEXTDOMAIN),
			'skin' => __('Skin', ADAMLABS_GALLERY_TEXTDOMAIN),
			'custom_filter' => __('--- Custom Filter ---', ADAMLABS_GALLERY_TEXTDOMAIN),
			'delete_this_element' => __('Are you sure you want to delete this element?', ADAMLABS_GALLERY_TEXTDOMAIN)
		);

		return apply_filters('adamlabsgallery_get_javascript_multilanguage', $lang);
	}

	
	/**
	 * get grid animations
	 */
	public static function get_grid_animations(){

		$animations = array(
			'fade' =>  __('Fade', ADAMLABS_GALLERY_TEXTDOMAIN),
			'scale' =>  __('Scale', ADAMLABS_GALLERY_TEXTDOMAIN),
			'rotatescale' =>  __('Rotate Scale', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fall' =>  __('Fall', ADAMLABS_GALLERY_TEXTDOMAIN),
			'rotatefall' =>  __('Rotate Fall', ADAMLABS_GALLERY_TEXTDOMAIN),
			'horizontal-slide' =>  __('Horizontal Slide', ADAMLABS_GALLERY_TEXTDOMAIN),
			'vertical-slide' =>  __('Vertical Slide', ADAMLABS_GALLERY_TEXTDOMAIN),
			'horizontal-flip' =>  __('Horizontal Flip', ADAMLABS_GALLERY_TEXTDOMAIN),
			'vertical-flip' =>  __('Vertical Flip', ADAMLABS_GALLERY_TEXTDOMAIN),
			'horizontal-flipbook' =>  __('Horizontal Flipbook', ADAMLABS_GALLERY_TEXTDOMAIN),
			'vertical-flipbook' =>  __('Vertical Flipbook', ADAMLABS_GALLERY_TEXTDOMAIN)
		);

		return apply_filters('adamlabsgallery_get_grid_animations', $animations);

	}
	
	/**
	 * get grid animations
	 */
	public static function get_start_animations(){

		$animations = array(
			'none' =>  __('None', ADAMLABS_GALLERY_TEXTDOMAIN),
			'reveal' =>  __('Reveal', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fade' =>  __('Fade', ADAMLABS_GALLERY_TEXTDOMAIN),
			'scale' =>  __('Scale', ADAMLABS_GALLERY_TEXTDOMAIN),
			'slideup' => __('Slide Up (short)', ADAMLABS_GALLERY_TEXTDOMAIN),
			'covergrowup' =>  __('Slide Up (long)', ADAMLABS_GALLERY_TEXTDOMAIN),
			'slideleft' => __('Slide Left', ADAMLABS_GALLERY_TEXTDOMAIN),
			'slidedown' => __('Slide Down', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipvertical' => __('Flip Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fliphorizontal' => __('Flip Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipup' => __('Flip Up', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipdown' => __('Flip Down', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipright' => __('Flip Right', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipleft' => __('Flip Left', ADAMLABS_GALLERY_TEXTDOMAIN),
			'skewleft' => __('Skew', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flipleft' => __('Flip Left', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomin' => __('Rotate Zoom', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flyleft' => __('Fly Left', ADAMLABS_GALLERY_TEXTDOMAIN),
			'flyright' => __('Fly Right', ADAMLABS_GALLERY_TEXTDOMAIN)
		);

		return apply_filters('adamlabsgallery_get_grid_start_animations', $animations);

	}
	
	/**
	 * get grid item animations,
	 */
	public static function get_grid_item_animations(){

		$animations = array(
			'none' =>  __('None', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomin' => __('Zoom In', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomout' => __('Zoom Out', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fade' => __('Fade Out', ADAMLABS_GALLERY_TEXTDOMAIN),
			'blur' => __('Blur', ADAMLABS_GALLERY_TEXTDOMAIN),
			'shift' => __('Shift', ADAMLABS_GALLERY_TEXTDOMAIN),
			'rotate' => __('Rotate', ADAMLABS_GALLERY_TEXTDOMAIN)
		);

		return apply_filters('adamlabsgallery_get_grid_item_animations', $animations);

	}


	/**
	 * get grid animations
	 */
	public static function get_hover_animations($inout = false){
		if(!$inout){
			$animations = array(
				'none' => __(' None', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fade' => __('Fade', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipvertical' => __('Flip Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fliphorizontal' => __('Flip Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipup' => __('Flip Up', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipdown' => __('Flip Down', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipright' => __('Flip Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipleft' => __('Flip Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'turn' => __('Turn', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slide' => __('Slide', ADAMLABS_GALLERY_TEXTDOMAIN),
				'scaleleft' => __('Scale Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'scaleright' => __('Scale Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideleft' => __('Slide Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideright' => __('Slide Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideup' => __('Slide Up', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slidedown' => __('Slide Down', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortleft' => __('Slide Short Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortright' => __('Slide Short Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortup' => __('Slide Short Up', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortdown' => __('Slide Short Down', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewleft' => __('Skew Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewright' => __('Skew Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollleft' => __('Roll Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollright' => __('Roll Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'falldown' => __('Fall Down', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rotatescale' => __('Rotate Scale', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoomback' => __('Zoom from Back', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoomfront' => __('Zoom from Front', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyleft' => __('Fly Left', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyright' => __('Fly Right', ADAMLABS_GALLERY_TEXTDOMAIN),
				'covergrowup' => __('Cover Grow', ADAMLABS_GALLERY_TEXTDOMAIN),
				'collapsevertical' => __('Collapse Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
				'collapsehorizontal' => __('Collapse Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linediagonal' => __('Line Diagonal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linehorizontal' => __('Line Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linevertical' => __('Line Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
				'spiralzoom' => __('Spiral Zoom', ADAMLABS_GALLERY_TEXTDOMAIN),
				'circlezoom' => __('Circle Zoom', ADAMLABS_GALLERY_TEXTDOMAIN)
			);
		}else{
			$animations = array(
				'none' => __(' None', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fade' => __('Fade In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fadeout' => __('Fade Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipvertical' => __('Flip Vertical In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipverticalout' => __('Flip Vertical Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fliphorizontal' => __('Flip Horizontal In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fliphorizontalout' => __('Flip Horizontal Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipup' => __('Flip Up In Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipupout' => __('Flip Up Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipdown' => __('Flip Down In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipdownout' => __('Flip Down Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipright' => __('Flip Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'fliprightout' => __('Flip Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipleft' => __('Flip Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flipleftout' => __('Flip Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'turn' => __('Turn In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'turnout' => __('Turn Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideleft' => __('Slide Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideleftout' => __('Slide Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideright' => __('Slide Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'sliderightout' => __('Slide Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideup' => __('Slide Up In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideupout' => __('Slide Up Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slidedown' => __('Slide Down In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slidedownout' => __('Slide Down Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortleft' => __('Slide Short Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortleftout' => __('Slide Short Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortright' => __('Slide Short Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortrightout' => __('Slide Short Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortup' => __('Slide Short Up In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortupout' => __('Slide Short Up Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortdown' => __('Slide Short Down In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'slideshortdownout' => __('Slide Short Down Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewleft' => __('Skew Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewleftout' => __('Skew Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewright' => __('Skew Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'skewrightout' => __('Skew Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollleft' => __('Roll Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollleftout' => __('Roll Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollright' => __('Roll Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rollrightout' => __('Roll Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'falldown' => __('Fall Down In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'falldownout' => __('Fall Down Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rotatescale' => __('Rotate Scale In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'rotatescaleout' => __('Rotate Scale Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoomback' => __('Zoom from Back In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoombackout' => __('Zoom from Back Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoomfront' => __('Zoom from Front In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'zoomfrontout' => __('Zoom from Front Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyleft' => __('Fly Left In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyleftout' => __('Fly Left Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyright' => __('Fly Right In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'flyrightout' => __('Fly Right Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'covergrowup' => __('Cover Grow In', ADAMLABS_GALLERY_TEXTDOMAIN),
				'covergrowupout' => __('Cover Grow Out', ADAMLABS_GALLERY_TEXTDOMAIN),
				'collapsevertical' => __('Collapse Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
				'collapsehorizontal' => __('Collapse Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linediagonal' => __('Line Diagonal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linehorizontal' => __('Line Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
				'linevertical' => __('Line Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
				'spiralzoom' => __('Spiral Zoom', ADAMLABS_GALLERY_TEXTDOMAIN),
				'circlezoom' => __('Circle Zoom', ADAMLABS_GALLERY_TEXTDOMAIN)
			);
		}

        asort($animations);

		return apply_filters('adamlabsgallery_get_hover_animations', $animations);
	}

	
    /**
	 * get media animations (only out animations!)
	 */
    public static function get_media_animations(){

        $media_anim = array(
            'none' => __(' None', ADAMLABS_GALLERY_TEXTDOMAIN),
            'flipverticalout' => __('Flip Vertical', ADAMLABS_GALLERY_TEXTDOMAIN),
            'fliphorizontalout' => __('Flip Horizontal', ADAMLABS_GALLERY_TEXTDOMAIN),
            'fliprightout' => __('Flip Right', ADAMLABS_GALLERY_TEXTDOMAIN),
            'flipleftout' => __('Flip Left', ADAMLABS_GALLERY_TEXTDOMAIN),
            'flipupout' => __('Flip Up', ADAMLABS_GALLERY_TEXTDOMAIN),
            'flipdownout' => __('Flip Down', ADAMLABS_GALLERY_TEXTDOMAIN),
            'shifttotop' => __('Shift To Top', ADAMLABS_GALLERY_TEXTDOMAIN),
            'turnout' => __('Turn', ADAMLABS_GALLERY_TEXTDOMAIN),
            '3dturnright' => __('3D Turn Right', ADAMLABS_GALLERY_TEXTDOMAIN),
            'pressback' => __('Press Back', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomouttocorner' => __('Zoom Out To Side', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomintocorner' => __('Zoom In To Side', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomtodefault' => __('Zoom To Default', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomdefaultblur' => __('Zoom Default Blur', ADAMLABS_GALLERY_TEXTDOMAIN),
			'mediazoom' => __('Zoom', ADAMLABS_GALLERY_TEXTDOMAIN),
			'blur' => __('Blur', ADAMLABS_GALLERY_TEXTDOMAIN),
			'fadeblur' => __('Fade Blur', ADAMLABS_GALLERY_TEXTDOMAIN),
			'grayscalein' => __('GrayScale In', ADAMLABS_GALLERY_TEXTDOMAIN),
			'grayscaleout' => __('GrayScale Out', ADAMLABS_GALLERY_TEXTDOMAIN),
			'zoomblur' => __('Zoom Blur', ADAMLABS_GALLERY_TEXTDOMAIN),
            'zoombackout' => __('Zoom to Back', ADAMLABS_GALLERY_TEXTDOMAIN),
            'zoomfrontout' => __('Zoom to Front', ADAMLABS_GALLERY_TEXTDOMAIN),
            'zoomandrotate' => __('Zoom And Rotate', ADAMLABS_GALLERY_TEXTDOMAIN)
        );

        //asort($media_anim);

        return apply_filters('adamlabsgallery_get_media_animations', $media_anim);
    }


	/**
	 * set basic columns if empty
	 */
	public static function set_basic_colums($columns){

		if(!isset($columns[0]) || intval($columns[0]) == 0) $columns[0] = 5;
		if(!isset($columns[1]) || intval($columns[1]) == 0) $columns[1] = 4;
		if(!isset($columns[2]) || intval($columns[2]) == 0) $columns[2] = 4;
		if(!isset($columns[3]) || intval($columns[3]) == 0) $columns[3] = 3;
		if(!isset($columns[4]) || intval($columns[4]) == 0) $columns[4] = 3;
		if(!isset($columns[5]) || intval($columns[5]) == 0) $columns[5] = 3;
		if(!isset($columns[6]) || intval($columns[6]) == 0) $columns[6] = 1;

		return apply_filters('adamlabsgallery_set_basic_colums', $columns);
	}


	/**
	 * set basic columns if empty
	 */
	public static function set_basic_colums_custom($columns){
		
		if(is_array($columns)) return self::set_basic_colums($columns);
		
		$new_columns = array();
		
		$columns = (intval($columns) > 0) ? $columns : 5;
		
		$new_columns[] = $columns;
		$new = $columns - ceil(($columns - 2) / 3) * 1;
		$new_columns[] = ($new < 2) ? 2 : $new;
		$new = $columns - ceil(($columns - 2) / 3) * 2;
		$new_columns[] = ($new < 2) ? 2 : $new;
		$new = $columns - ceil(($columns - 2) / 3) * 3;
		$new_columns[] = ($new < 2) ? 2 : $new;
		$new_columns[] = 2;
		$new_columns[] = 2;
		$new_columns[] = 1;
		
		return apply_filters('adamlabsgallery_set_basic_colums_custom', $new_columns);
	}

	/**
	 * set basic height of Masonry Content if Empty
	 */
	public static function set_basic_mascontent_height($mascontent_height){

		if(!isset($mascontent_height[0]) || intval($mascontent_height[0]) == 0) $mascontent_height[0] = 0;
		if(!isset($mascontent_height[1]) || intval($mascontent_height[1]) == 0) $mascontent_height[1] = 0;
		if(!isset($mascontent_height[2]) || intval($mascontent_height[2]) == 0) $mascontent_height[2] = 0;
		if(!isset($mascontent_height[3]) || intval($mascontent_height[3]) == 0) $mascontent_height[3] = 0;
		if(!isset($mascontent_height[4]) || intval($mascontent_height[4]) == 0) $mascontent_height[4] = 0;
		if(!isset($mascontent_height[5]) || intval($mascontent_height[5]) == 0) $mascontent_height[5] = 0;
		if(!isset($mascontent_height[6]) || intval($mascontent_height[6]) == 0) $mascontent_height[6] = 0;

		return apply_filters('adamlabsgallery_set_basic_mascontent_height', $mascontent_height);
	}

	/**
	 * set basic columns width if empty
	 */
	public static function set_basic_colums_width($columns_width){

		if(!isset($columns_width[0]) || intval($columns_width[0]) == 0) $columns_width[0] = 1400;
		if(!isset($columns_width[1]) || intval($columns_width[1]) == 0) $columns_width[1] = 1170;
		if(!isset($columns_width[2]) || intval($columns_width[2]) == 0) $columns_width[2] = 1024;
		if(!isset($columns_width[3]) || intval($columns_width[3]) == 0) $columns_width[3] = 960;
		if(!isset($columns_width[4]) || intval($columns_width[4]) == 0) $columns_width[4] = 778;
		if(!isset($columns_width[5]) || intval($columns_width[5]) == 0) $columns_width[5] = 640;
		if(!isset($columns_width[6]) || intval($columns_width[6]) == 0) $columns_width[6] = 480;

		return apply_filters('adamlabsgallery_set_basic_colums_width', $columns_width);
	}

	/**
	 * set basic columns width if empty
	 */
	public static function set_basic_masonry_content_height($mas_con_height){

		if(!isset($mas_con_height[0])) $mas_con_height[0] = 0;
		if(!isset($mas_con_height[1])) $mas_con_height[1] = 0;
		if(!isset($mas_con_height[2])) $mas_con_height[2] = 0;
		if(!isset($mas_con_height[3])) $mas_con_height[3] = 0;
		if(!isset($mas_con_height[4])) $mas_con_height[4] = 0;
		if(!isset($mas_con_height[5])) $mas_con_height[5] = 0;
		if(!isset($mas_con_height[6])) $mas_con_height[6] = 0;

		return apply_filters('adamlabsgallery_set_basic_masonry_content_height', $mas_con_height);
	}


	/**
	 * set basic columns height if empty
	 * @since: 2.0.4
	 */
	public static function set_basic_colums_height($columns_height){

		if(!isset($columns_height[0]) || intval($columns_height[0]) == 0) $columns_height[0] = 0;
		if(!isset($columns_height[1]) || intval($columns_height[1]) == 0) $columns_height[1] = 0;
		if(!isset($columns_height[2]) || intval($columns_height[2]) == 0) $columns_height[2] = 0;
		if(!isset($columns_height[3]) || intval($columns_height[3]) == 0) $columns_height[3] = 0;
		if(!isset($columns_height[4]) || intval($columns_height[4]) == 0) $columns_height[4] = 0;
		if(!isset($columns_height[5]) || intval($columns_height[5]) == 0) $columns_height[5] = 0;
		if(!isset($columns_height[6]) || intval($columns_height[6]) == 0) $columns_height[6] = 0;

		return apply_filters('adamlabsgallery_set_basic_colums_height', $columns_height);
	}


	/**
	 * encode array into json for client side
	 */
	public static function jsonEncodeForClientSide($arr){
		$json = "";
		if(!empty($arr)){
			$json = json_encode($arr);
			$json = addslashes($json);
		}

		$json = "'".$json."'";

		return apply_filters('adamlabsgallery_jsonEncodeForClientSide', $json, $arr);
	}


	/**
	 * Get url to secific view.
	 */
	public static function getFontsUrl(){

		$link = admin_url('admin.php?page=adamlabs-google-fonts');
		return apply_filters('adamlabsgallery_getFontsUrl', $link);
	}


	/**
	 * Get url to secific view.
	 */
	public static function getViewUrl($viewName="",$urlParams="",$slug=""){
		$params = "";

		$plugin = AdamLabsGallery::get_instance();
		if($slug == "") $slug = $plugin->get_plugin_slug();

		if($viewName != "") $params = "&view=".$viewName;
		$params .= (!empty($urlParams)) ? "&".$urlParams : "";

		$link = admin_url( "admin.php?page=".$slug.$params);
		return apply_filters('adamlabsgallery_getViewUrl', $link, $viewName, $urlParams, $slug);
	}


	/**
	 * Get url to secific view.
	 */
	public static function getSubViewUrl($viewName="",$urlParams="",$slug=""){
		$params = "";

		$plugin = AdamLabsGallery::get_instance();
		if($slug == "") $slug = $plugin->get_plugin_slug();

		if($viewName != "") $params = "-".$viewName;
		$params .= (!empty($urlParams)) ? "&".$urlParams : "";

		$link = admin_url( "admin.php?page=".$slug.$params);
		return apply_filters('adamlabsgallery_getSubViewUrl', $link, $viewName, $urlParams, $slug);
	}


	/**
	 * Get Post Types + Custom Post Types
	 */
	public static function getPostTypesAssoc($arrPutToTop = array()){
		$arrBuiltIn = array("post"=>"post", "page"=>"page");

		$arrCustomTypes = get_post_types(array('_builtin' => false));

		//top items validation - add only items that in the customtypes list
		$arrPutToTopUpdated = array();
		foreach($arrPutToTop as $topItem){
			if(in_array($topItem, $arrCustomTypes) == true){
				$arrPutToTopUpdated[$topItem] = $topItem;
				unset($arrCustomTypes[$topItem]);
			}
		}

		$arrPostTypes = array_merge($arrPutToTopUpdated,$arrBuiltIn,$arrCustomTypes);

		//update label
		foreach($arrPostTypes as $key=>$type){
			$objType = get_post_type_object($type);

			if(empty($objType)){
				$arrPostTypes[$key] = $type;
				continue;
			}

			// Remove NextGen Post Types from the list
			if( !strpos($objType->labels->singular_name, 'extGEN') ){
				$arrPostTypes[$key] = $objType->labels->singular_name;	
			}
			else{
				unset($arrPostTypes[$key]);	
			}
		}

		return apply_filters('adamlabsgallery_getPostTypesAssoc', $arrPostTypes, $arrPutToTop);
	}
	
	
	/**
	 * Translate the Categories depending on selected language (needed for backend)
	 */
	public function translate_base_categories_to_cur_lang($postTypes){
		global $sitepress;
		
		if(AdamLabsGallery_Wpml::is_wpml_exists()){
			if(is_array($postTypes)){
				foreach($postTypes as $key => $type){
					$tarr = explode('_', $type);
					$id = array_pop($tarr);
					$post_type = implode('_', $tarr);
					$id = icl_object_id(intval($id), $post_type, true, ICL_LANGUAGE_CODE);
					$postTypes[$key] = $post_type.'_'.$id;
				}
			}
		}
		
		return apply_filters('adamlabsgallery_translate_base_categories_to_cur_lang', $postTypes);
	}

	
	/**
	 * Get post types with categories.
	 */
	public static function getPostTypesWithCatsForClient(){
		global $sitepress;
		
		$arrPostTypes = self::getPostTypesWithCats(true);

		$globalCounter = 0;

		$arrOutput = array();

		foreach($arrPostTypes as $postType => $arrTaxWithCats){

			$arrCats = array();
			foreach($arrTaxWithCats as $tax){
				$taxName = $tax["name"];
				$taxTitle = $tax["title"];
				$globalCounter++;
				$arrCats["option_disabled_".$globalCounter] = "---- ".$taxTitle." ----";
				foreach($tax["cats"] as $catID=>$catTitle){
					if(AdamLabsGallery_Wpml::is_wpml_exists() && isset($sitepress)){
						$catID = icl_object_id($catID, $taxName, true, $sitepress->get_default_language());
					}
					$arrCats[$taxName."_".$catID] = $catTitle;
				}
			}//loop tax

			$arrOutput[$postType] = $arrCats;

		}//loop types
		
		return apply_filters('adamlabsgallery_getPostTypesWithCatsForClient', $arrOutput);
	}


	/**
	 * get array of post types with categories (the taxonomies is between).
	 * get only those taxomonies that have some categories in it.
	 */
	public static function getPostTypesWithCats(){
		$arrPostTypes = self::getPostTypesWithTaxomonies();

		$arrPostTypesOutput = array();
		foreach($arrPostTypes as $name=>$arrTax){

			$arrTaxOutput = array();
			foreach($arrTax as $taxName=>$taxTitle){
				$cats = self::getCategoriesAssoc($taxName);
				
				if(!empty($cats))
					$arrTaxOutput[] = array(
							 "name"=>$taxName,
							 "title"=>$taxTitle,
							 "cats"=>$cats);
			}

			$arrPostTypesOutput[$name] = $arrTaxOutput;

		}

		return apply_filters('adamlabsgallery_getPostTypesWithCats', $arrPostTypesOutput);
	}

	
	/**
	 * get current language code
	 */
	public static function get_current_lang_code(){
		$langTag = get_bloginfo('language');
		$data = explode('-', $langTag);
		$code = $data[0];
		return apply_filters('adamlabsgallery_get_current_lang_code', $code);
	}

	
	/**
	 * get post types array with taxomonies
	 */
	public static function getPostTypesWithTaxomonies(){
		$arrPostTypes = self::getPostTypesAssoc();

		foreach($arrPostTypes as $postType=>$title){
			$arrTaxomonies = self::getPostTypeTaxomonies($postType);
			$arrPostTypes[$postType] = $arrTaxomonies;
		}

		return apply_filters('adamlabsgallery_getPostTypesWithTaxomonies', $arrPostTypes);
	}


	/**
	 * get post categories list assoc - id / title
	 */
	public static function getCategoriesAssoc($taxonomy = "category"){

		if(strpos($taxonomy,",") !== false){
			$arrTax = explode(",", $taxonomy);
			$arrCats = array();
			foreach($arrTax as $tax){
				$cats = self::getCategoriesAssoc($tax);
				$arrCats = array_merge($arrCats,$cats);
			}

		}else{

			//$cats = get_terms("category");
			$args = array("taxonomy"=>$taxonomy);

			//AdamLabsGallery_Wpml::disable_language_filtering();

			$cats = get_categories($args);

			//AdamLabsGallery_Wpml::enable_language_filtering();

			$arrCats = array();
			foreach($cats as $cat){
				$numItems = $cat->count;
				$itemsName = "items";
				if($numItems == 1)
					$itemsName = "item";

				$title = $cat->name . " ($numItems $itemsName) [slug: ".$cat->slug."]"; //ADD SLUG HERE

				$id = $cat->cat_ID;
				$id = AdamLabsGallery_Wpml::get_id_from_lang_id($id,$cat->taxonomy);

				$arrCats[$id] = $title;
			}
		}
		
		return apply_filters('adamlabsgallery_getCategoriesAssoc', $arrCats, $taxonomy);
	}


	/**
	 * get post type taxomonies
	 */
	public static function getPostTypeTaxomonies($postType){
		$arrTaxonomies = get_object_taxonomies(array('post_type' => $postType), 'objects');
		
		$arrNames = array();
		foreach($arrTaxonomies as $key=>$objTax){
			$arrNames[$objTax->name] = $objTax->labels->name;
		}

		return apply_filters('adamlabsgallery_getPostTypeTaxomonies', $arrNames, $postType);
	}


	/**
	 * get first category from categories list
	 */
	private static function getFirstCategory($cats){
		$ret = '';
		
		foreach($cats as $key=>$value){
			if(strpos($key,"option_disabled") === false){
				$ret = $key;
				break;
			}
		}
		
		return apply_filters('adamlabsgallery_getFirstCategory', $ret, $cats);
	}


	/**
	 * set category by post type, with specific name (can be regular or woocommerce)
	 */
	public static function setCategoryByPostTypes($postTypes, $postTypesWithCats){

		//update the categories list by the post types
		if(strpos($postTypes, ",") !== false)
			$postTypes = explode(",",$postTypes);
		else
			$postTypes = array($postTypes);

		$arrCats = array();
		$isFirst = true;

		foreach($postTypes as $postType){
			$cats = array();
			foreach($postTypesWithCats as $postCats){
				if(array_key_exists($postType, $postCats)) $cats = $postCats;
			}
			if($isFirst == true){
				$firstValue = self::getFirstCategory($cats);
				$isFirst = false;
			}

			$arrCats = array_merge($arrCats,$cats);
		}

		return apply_filters('adamlabsgallery_setCategoryByPostTypes', $arrCats, $postTypes, $postTypesWithCats);
	}


	/**
	 * get posts by categorys/tags
	 */
	public static function getPostsByCategory($grid_id, $catID, $postTypes="any", $taxonomies="category", $pages = array(), $sortBy = 'ID', $direction = 'DESC', $numPosts=-1, $arrAddition = array(), $enable_caching = true, $relation = 'OR'){ //category
		global $sitepress;
		
		//get post types
		if(strpos($postTypes,",") !== false){
			$postTypes = explode(",", $postTypes);
			if(array_search("any", $postTypes) !== false)
				$postTypes = "any";
		}

		if(empty($postTypes))
			$postTypes = "any";

		if(strpos($catID,",") !== false)
			$catID = explode(",",$catID);
		else
			$catID = array($catID);

		$query = array(
			'order'=>$direction,
			'posts_per_page'=>$numPosts,
			'showposts'=>$numPosts,
			'post_status'=>'publish',
			'post_type'=>$postTypes,
			//'fields' => 'ids,post_type'
		);
		$enable_caching = false;
		
		if(strpos($sortBy, 'adamlabsgallery-') === 0){
			$meta = new AdamLabsGallery_Meta();
			$m = $meta->get_all_meta(false);
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgallery-'.$me['handle'] == $sortBy){
						$sortBy = (isset($me['sort-type']) && $me['sort-type'] == 'numeric') ? 'meta_num_'.$sortBy : 'meta_'.$sortBy;
						break;
					}
				}
			}
		}elseif(strpos($sortBy, 'adamlabsgalleryl-') === 0){ //change to meta_num_ or meta_ depending on setting
			$sortfound = false;
			$link_meta = new AdamLabsGallery_Meta_Linking();
			$m = $link_meta->get_all_link_meta();
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgalleryl-'.$me['handle'] == $sortBy){
						$sortBy = (isset($me['sort-type']) && $me['sort-type'] == 'numeric') ? 'meta_num_'.$me['original'] : 'meta_'.$me['original'];
						$sortfound = true;
						break;
					}
				}
			}
			if(!$sortfound){
				$sortBy = 'none';
			}
		}
		
		//add sort by (could be by meta)
		if(strpos($sortBy, "meta_num_") === 0){
			$metaKey = str_replace("meta_num_", "", $sortBy);
			$query["orderby"] = "meta_value_num";
			$query["meta_key"] = $metaKey;
		}else if(strpos($sortBy, "meta_") === 0){
			$metaKey = str_replace("meta_", "", $sortBy);
			$query["orderby"] = "meta_value";
			$query["meta_key"] = $metaKey;
		}else{
			$query["orderby"] = $sortBy;
		}

		if($query["orderby"] == "likespost"){
			$query["orderby"] = "meta_value";
			$query["meta_key"] = "adamlabsgallery_votes_count";
		}

		//get taxonomies array
		$arrTax = array();
		if(!empty($taxonomies)){
			$arrTax = explode(",", $taxonomies);
		}


		if(!empty($taxonomies)){

			$taxQuery = array();

			//add taxomonies to the query
			if(strpos($taxonomies,",") !== false){	//multiple taxomonies
				$taxonomies = explode(",",$taxonomies);
				foreach($taxonomies as $taxomony){
					$taxArray = array(
						'taxonomy' => $taxomony,
						'field' => 'id',
						'terms' => $catID
					);
					
					if($relation == 'AND') $taxArray['operator'] = 'AND';
					
					$taxQuery[] = $taxArray;
				}
			}else{		//single taxomony
				$taxArray = array(
					'taxonomy' => $taxonomies,
					'field' => 'id',
					'terms' => $catID
				);
				
				if($relation == 'AND') $taxArray['operator'] = 'AND';
			
				$taxQuery[] = $taxArray;
			}
			
			$taxQuery['relation'] = $relation;

			$query['tax_query'] = $taxQuery;
		} //if exists taxanomies
		
		$query['suppress_filters'] = false;
		
		if(!empty($arrAddition) && is_array($arrAddition)){
			foreach($arrAddition as $han => $val){
				if(strtolower(substr($val, 0, 5)) == 'array') {
					$val = explode(',', str_replace(array('(', ')'), '', substr($val, 5)));
					$arrAddition[$han] = $val;
				}
			}
			$query = array_merge($query, $arrAddition);
			if(isset($arrAddition['offset'])){
				if(isset($query['posts_per_page']) && ($query['posts_per_page'] == '-1' || $query['posts_per_page'] == -1)){
					$query['posts_per_page'] = '9999';
					$query['showposts'] = '9999';
				}
			}
		}
		
		if($query['orderby'] == 'none') $query['orderby'] = 'post__in';
		
		if(empty($grid_id)) $grid_id = time();
		
		//add wpml transient
		$lang_code = '';
		if(AdamLabsGallery_Wpml::is_wpml_exists()){
			$lang_code = AdamLabsGallery_Wpml::get_current_lang_code();
		}
		
		$objQuery = false;
		
		$query_type = get_option('adamlabsgallery_query_type', 'wp_query');
		
		if($objQuery === false){
		
			echo '<!-- CACHE CREATED FOR: '.$grid_id.' -->';

			$query = apply_filters( 'adamlabsgallery_get_posts', $query, $grid_id );
			
			if($query_type == 'wp_query'){
				$wp_query = new WP_Query();
				$wp_query->parse_query($query);
				
				$objQuery = $wp_query->get_posts();
				//$objQuery = new WP_Query($query);
			}else{
				$objQuery = get_posts($query);
			}

			//select again the pages
			if(is_array($postTypes) && in_array('page', $postTypes) && count($postTypes) > 1 || $postTypes == 'page'){ //Page is selected and also another custom category
				$query['post_type'] = 'page';
				unset($query['tax_query']); //delete category/tag filtering
			
				$query['post__in'] = $pages;	
				
				if($query_type == 'wp_query'){
					$wp_query = new WP_Query();
					$wp_query->parse_query($query);
					$objQueryPages = $wp_query->get_posts();
					//$objQueryPages = new WP_Query($query);
				}else{
					$objQueryPages = get_posts($query);
				}
				
				if($query_type == 'wp_query'){
					if(is_object($objQueryPages) && is_object($objQuery)){
						$objQuery->posts = array_merge($objQuery->posts, $objQueryPages->posts);
					}
					if(is_object($objQueryPages) && !is_object($objQuery)){
						$objQuery = $objQueryPages;
					}
				}else{
					if(is_array($objQueryPages) && is_array($objQuery)){
						$objQuery = array_merge($objQuery, $objQueryPages);
					}
					if(is_array($objQueryPages) && !is_array($objQuery)){
						$objQuery = $objQueryPages;
					}
				}
				
				//remove duplicated posts
				/*if($query_type == 'wp_query'){
					if(is_object($objQueryPages) && is_object($objQuery)){
						$objQuery->posts = array_merge($objQuery->posts, $objQueryPages->posts);
					}
					if(is_object($objQueryPages) && !is_object($objQuery)){
						$objQuery = $objQueryPages;
					}
				}else{*/
					if(is_array($objQueryPages) && is_array($objQuery)){
						$objQuery = array_merge($objQuery, $objQueryPages);
					}
					if(is_array($objQueryPages) && !is_array($objQuery)){
						$objQuery = $objQueryPages;
					}
				//}
				
				//remove duplicated posts
				/*if($query_type == 'wp_query'){
					if(!empty($objQuery->posts)){
						$fIDs = array();
						foreach($objQuery->posts as $objID => $objPost){
							if(isset($fIDs[$objPost->ID])){
								unset($objQuery->posts[$objID]);
								continue;
							}
							$fIDs[$objPost->ID] = true;
						}
					}
				}else{*/
					if(!empty($objQuery)){
						$fIDs = array();
						foreach($objQuery as $objID => $objPost){
							if(isset($fIDs[$objPost->ID])){
								unset($objQuery[$objID]);
								continue;
							}
							$fIDs[$objPost->ID] = true;
						}
					}
				//}
			}
			
			if($enable_caching){
				set_transient( 'adamlabsgallery_trans_query_'.$grid_id.$lang_code, $objQuery, 60*60*24 );
			}
			
		}else{
			echo '<!-- CACHE FOUND FOR: '.$grid_id.' -->';
		}
		
		/*if($query_type == 'wp_query'){
			$arrPosts = @$objQuery->posts;
		}else{*/
			$arrPosts = $objQuery;
		//}
		
		
		//check if we should rnd the posts
		if($sortBy == 'rand' && !empty($arrPosts)){
			shuffle($arrPosts);
		}
		
		if(!empty($arrPosts)){
			foreach($arrPosts as $key=>$post){

				if(method_exists($post, "to_array"))
					$arrPost = $post->to_array();
				else
					$arrPost = (array)$post;

				if($arrPost['post_type'] == 'page'){
					if(!empty($pages)){ //filter to pages if array is set
						$delete = true;
						foreach($pages as $page){
							if(!empty($page)){
								if($arrPost['ID'] == $page){
									$delete = false;
									break;
								}elseif(isset($sitepress)){ //WPML
									$current_main_id = icl_object_id( $arrPost['ID'], 'page', true, $sitepress->get_default_language() );
									if($current_main_id == $page){
										$delete = false;
										break;
									}
								}
							}
						}
						if($delete){ //if not wanted, go to next
							unset($arrPosts[$key]);
							continue;
						}
					}
				}
				/*
				$arrPostCats = self::getPostCategories($post, $arrTax);
				$arrPost["categories"] = $arrPostCats;
				*/
				$arrPosts[$key] = $arrPost;
			}
		}
		
		return apply_filters('adamlabsgallery_modify_posts', $arrPosts, $grid_id);
	}


	/**
	 * Get taxonomies by post ID
	 */
	public static function get_custom_taxonomies_by_post_id($post_id){
		
		// get post by post id
		$post = get_post( $post_id ); 

		// get post type by post
		$post_type = $post->post_type;

		// get post type taxonomies
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$terms = array();
		foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
			// get the terms related to post
			$c_terms = get_the_terms( $post->ID, $taxonomy_slug );
			
			if(!empty($c_terms)){
				$terms = array_merge($terms, $c_terms);
			}
		}
		
		
		return apply_filters('adamlabsgallery_get_custom_taxonomies_by_post_id', $terms, $post_id);
	}

	
	/**
	 * Receive all Posts by given IDs
	 */
	public static function get_posts_by_ids($ids, $sort_by = 'none', $sort_order = 'DESC'){
		
		$query = array(
		   'post__in'	=> $ids,
		   'post_type'	=> 'any',
		   'order'		=> $sort_order,
		   'numberposts'=> count($ids)
		);
		
		if(strpos($sort_by, 'adamlabsgallery-') === 0){
			$meta = new AdamLabsGallery_Meta();
			$m = $meta->get_all_meta(false);
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgallery-'.$me['handle'] == $sort_by){
						$sort_by = (isset($me['sort-type']) && $me['sort-type'] == 'numeric') ? 'meta_num_'.$sort_by : 'meta_'.$sort_by;
						break;
					}
				}
			}
		}elseif(strpos($sort_by, 'adamlabsgalleryl-') === 0){ //change to meta_num_ or meta_ depending on setting
			$sortfound = false;
			$link_meta = new AdamLabsGallery_Meta_Linking();
			$m = $link_meta->get_all_link_meta();
			if(!empty($m)){
				foreach($m as $me){
					if('adamlabsgalleryl-'.$me['handle'] == $sort_by){
						$sort_by = (isset($me['sort-type']) && $me['sort-type'] == 'numeric') ? 'meta_num_'.$me['original'] : 'meta_'.$me['original'];
						$sortfound = true;
						break;
					}
				}
			}
			if(!$sortfound){
				$sort_by = 'none';
			}
		}
		
		//add sort by (could be by meta)
		if(strpos($sort_by, "meta_num_") === 0){
			$metaKey = str_replace("meta_num_", "", $sort_by);
			$query["orderby"] = "meta_value_num";
			$query["meta_key"] = $metaKey;
		}else if(strpos($sort_by, "meta_") === 0){
			$metaKey = str_replace("meta_", "", $sort_by);
			$query["orderby"] = "meta_value";
			$query["meta_key"] = $metaKey;
		}else{
			$query["orderby"] = $sort_by;
		}
		
		if($query['orderby'] == 'none') $query['orderby'] = 'post__in';
		$query = apply_filters('adamlabsgallery_get_posts_by_ids_query', $query, $ids);
		
		$objQuery = get_posts($query);

		$arrPosts = $objQuery;

		foreach($arrPosts as $key=>$post){
			if(method_exists($post, "to_array"))
				$arrPost = $post->to_array();
			else
				$arrPost = (array)$post;

			$arrPosts[$key] = $arrPost;
		}

		return apply_filters('adamlabsgallery_get_posts_by_ids', $arrPosts);
	}


	/**
	 * Receive all Posts ordered by popularity
	 */
	public static function get_popular_posts($max_posts = 20){
		
		$post_id = get_the_ID();
		
		$my_posts = array();
		
		$args = array(
			'post_type' => 'any',
			'posts_per_page' => $max_posts,
			'suppress_filters' => 0,
			'meta_key'    => '_thumbnail_id',
			'orderby'     => 'comment_count',
			'order'       => 'DESC'
		);
		
		$args = apply_filters('adamlabsgallery_get_popular_posts_query', $args, $post_id);
		$posts = get_posts($args);
		
		foreach($posts as $post){
		
			if(method_exists($post, "to_array"))
				$my_posts[] = $post->to_array();
			else
				$my_posts[] = (array)$post;
		}
		
		return apply_filters('adamlabsgallery_get_popular_posts', $my_posts);
	}


	/**
	 * Receive all Posts ordered by popularity
	 */
	public static function get_latest_posts($max_posts = 20){
		
		$post_id = get_the_ID();
		
		$my_posts = array();
		
		$args = array(
			'post_type' => 'any',
			'posts_per_page' => $max_posts,
			'suppress_filters' => 0,
			'meta_key'    => '_thumbnail_id',
			'orderby'     => 'date',
			'order'       => 'DESC'
		);
		$args = apply_filters('adamlabsgallery_get_latest_posts_query', $args, $post_id);
		
		$posts = get_posts($args);
		
		foreach($posts as $post){
		
			if(method_exists($post, "to_array"))
				$my_posts[] = $post->to_array();
			else
				$my_posts[] = (array)$post;
		}
		
		return apply_filters('adamlabsgallery_get_latest_posts', $my_posts);
	}
	
	
	/**
	 * Receive all Posts that are related to the current post
	 */
	public static function get_related_posts($max_posts = 20){
		$my_posts = array();
		
		$post_id = get_the_ID();
		
		$tags_string = '';
		$post_tags = get_the_tags();
		if ($post_tags) {
			foreach ($post_tags as $post_tag) {
				$tags_string .= $post_tag->slug . ',';
			}
		}
		
		$query = array(
						'exclude' => $post_id,
						'numberposts' => $max_posts,
						'tag' => $tags_string
					  );
					  
		$get_relateds = apply_filters('adamlabsgallery_get_related_posts', $query, $post_id);
		$tag_related_posts = get_posts($get_relateds);		
		
		
		if(count($tag_related_posts) < $max_posts){
			$ignore = array();
			foreach($tag_related_posts as $tag_related_post){
				$ignore[] = $tag_related_post->ID;
			}
			$article_categories = get_the_category($post_id);
			$category_string = '';
			foreach($article_categories as $category) { 
				$category_string .= $category->cat_ID . ',';
			}
			$max = $max_posts - count($tag_related_posts);
			
			$excl = implode(',', $ignore);
			$query = array(
							'exclude' => $excl,
							'numberposts' => $max,
							'category' => $category_string
						  );
						  
			$get_relateds = apply_filters('adamlabsgallery_get_related_posts_query', $query, $post_id);
			$cat_related_posts = get_posts($get_relateds);
			
			$tag_related_posts = $tag_related_posts + $cat_related_posts;
		}
		
		foreach($tag_related_posts as $post){
		
			$the_post = array();
			
			if(method_exists($post, "to_array"))
				$the_post = $post->to_array();
			else
				$the_post = (array)$post;
			
			if($the_post['ID'] == $post_id) continue;
			
			$my_posts[] = $the_post;
		}
		
		return apply_filters('adamlabsgallery_get_related_posts', $my_posts);
	}
	

	/**
	 * get post categories by postID and taxonomies
	 * the postID can be post object or array too
	 */
	public static function getPostCategories($postID,$arrTax){

		if(!is_numeric($postID)){
			$postID = (array)$postID;
			$postID = $postID["ID"];
		}

		$arrCats = wp_get_post_terms( $postID, $arrTax);

		$arrCats = self::convertStdClassToArray($arrCats);
		
		return apply_filters('adamlabsgallery_getPostCategories', $arrCats, $postID, $arrTax);
	}


	/**
	 * Convert std class to array, with all sons
	 */
	public static function convertStdClassToArray($arr){
		$arr = (array)$arr;

		$arrNew = array();

		foreach($arr as $key=>$item){
			$item = (array)$item;
			$arrNew[$key] = $item;
		}

		return apply_filters('adamlabsgallery_convertStdClassToArray', $arrNew, $arr);
	}


	/**
	 * get cats and taxanomies data from the category id's
	 */
	public static function getCatAndTaxData($catIDs){

		if(is_string($catIDs)){
			$catIDs = trim($catIDs);
			if(empty($catIDs))
				return(array("tax"=>"","cats"=>""));

			$catIDs = explode(",", $catIDs);
		}

		$strCats = "";
		$arrTax = array();
		foreach($catIDs as $cat){
			if(strpos($cat,"option_disabled") === 0)
				continue;

			$pos = strrpos($cat,"_");

			$taxName = substr($cat,0,$pos);
			$catID = substr($cat,$pos+1,strlen($cat)-$pos-1);

			//translate catID to current language if wpml exists
			$catID = AdamLabsGallery_Wpml::change_cat_id_by_lang($catID, $taxName);


			$arrTax[$taxName] = $taxName;
			if(!empty($strCats))
				$strCats .= ",";

			$strCats .= $catID;
		}

		$strTax = "";
		foreach($arrTax as $taxName){
			if(!empty($strTax))
				$strTax .= ",";

			$strTax .= $taxName;
		}

		$output = array("tax"=>$strTax,"cats"=>$strCats);

		return apply_filters('adamlabsgallery_getCatAndTaxData', $output, $catIDs);
	}
	
	
	/**
	 * get categories list, copy the code from default wp functions
	 */
	public static function get_categories_html_list($catIDs, $do_type, $seperator = ',', $tax = false){
		global $wp_rewrite;

		$categories = self::get_categories_by_ids($catIDs, $tax);

		$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

		$thelist = '';

		if(!empty($categories)){
			foreach($categories as $key => $category){
				if($key > 0) $thelist .= $seperator;
				
				switch($do_type){
					case 'none':
						$thelist .= $category->name;
					break;
					case 'filter':
						$thelist .= '<a href="#" class="adamlabsgallery-triggerfilter" data-filter="filter-'.$category->slug.'">'.$category->name.'</a>';
					break;
					case 'link':
					default:
						$url = '';
						if($tax !== false){
							$url = get_term_link($category, $tax);
							if(is_wp_error($url)) $url = '';
							
						}else{
							$url = get_category_link( $category->term_id );
						}
						$thelist .= '<a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', ADAMLABS_GALLERY_TEXTDOMAIN), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
					break;
				}
			}
		}

		return apply_filters('adamlabsgallery_get_categories_html_list', $thelist, $catIDs, $do_type, $seperator, $tax);
	}
	
	
	/**
	 * get categories by post IDs
	 */
	public static function get_categories_by_posts($posts){
		$post_ids = array();
		
		$categories = array();
		
		if(!empty($posts)){
			foreach($posts as $post){
				$post_ids[] = $post['ID'];
			}
		}
		
		if(!empty($post_ids)){
			foreach($post_ids as $post_id){
				$cats = self::get_custom_taxonomies_by_post_id($post_id);
				$categories = array_merge($categories, $cats);
			}
		}
		
		return apply_filters('adamlabsgallery_get_categories_by_posts', $categories, $posts);
		
	}
	
	
	/**
	 * translate categories obj to string
	 */
	public static function translate_categories_to_string($cats){
		
		$categories = array();
		
		if(!empty($cats)){
			foreach($cats as $cat){
				$categories[] = $cat->term_id;
			}
		}
		
		$categories = implode(',', $categories);
		
		return apply_filters('adamlabsgallery_translate_categories_to_string', $categories, $cats);
	}

	
	/**
	 * get categories by id's
	 */
	public static function get_categories_by_ids($arrIDs, $tax = false){

		if(empty($arrIDs))
			return(array());

		$strIDs = implode(',', $arrIDs);

		$args['include'] = $strIDs;

		if($tax !== false)
			$args['taxonomy'] = $tax;

		$arrCats = get_categories( $args );
		
		return apply_filters('adamlabsgallery_get_categories_by_ids', $arrCats, $arrIDs, $tax);
		
	}


	/**
	 * get categories by id's
	 */
	public static function get_create_category_by_slug($cat_slug, $cat_name){

		$cat = term_exists( $cat_slug, $cat_name );

		if ($cat !== 0 && $cat !== null){
			if(is_array($cat))
				return $cat['term_id'];
			else
				return $cat;
		}

		//create category if possible
		$new_name = ucwords(str_replace('-', ' ', $cat_slug));
		$category_array = wp_insert_term(
			$new_name,
			$cat_name,
			array(
				'description' => '',
				'slug'   => $cat_slug
			)
		);

		$category_array = apply_filters('adamlabsgallery_get_create_category_by_slug', $category_array, $cat_slug, $cat_name);
		
		if(is_array($category_array) && !empty($category_array))
			return $category_array['term_id'];
		else
			return false;

		return false;
	}

	/**
	 * get post taxonomies html list
	 */
	public static function get_tax_html_list($postID, $taxonomy, $seperator = ',', $do_type = 'link', $taxmax = false){
		
		if(empty($seperator)) $seperator  ='&nbsp;';
		
		$terms = get_the_terms($postID , $taxonomy);

		$taxList = array();

		if(!empty($terms)) {

			foreach ($terms as $term) {
						$taxList[] = '<a href="'.get_term_link($term->term_id).'" style="display:inline">'.$term->name.'</a>';
					}
		
			if($taxmax) {
				$taxs = array_slice($taxList, 0, $taxmax, true);
				//$taxList = implode($seperator, $taxs);
			}
			
			switch($do_type){
				case 'none':
					$taxList = implode($seperator, $taxList);
					$taxList = strip_tags($taxList);
				break;
				case 'filter':
					$text = '';
					if(!empty($taxList)){
						foreach($taxList as $key => $tax){
							if($key > 0) $text .= $seperator;
							$tax = strip_tags($tax);
							$text .= '<a href="#" class="adamlabsgallery-triggerfilter" data-filter="filter-'.$tax.'">'.sanitize_title($tax).'</a>';
						}
					}
					$taxList = $text;
				break;
				case 'link':
					$taxList = implode($seperator, $taxList);
				break;
				
			}
			
		}

		return apply_filters('adamlabsgallery_get_tax_html_list', $taxList, $postID, $seperator, $do_type);
	}


	/**
	 * get post tags html list
	 */
	public static function get_tags_html_list($postID, $seperator = ',', $do_type = 'link', $tagmax = false){
		
		if(empty($seperator)) $seperator  ='&nbsp;';
		
		$tagList = get_the_tag_list("",$seperator,"",$postID);
		
		if(!empty($tagList)) {
		
			if($tagmax) {
				$tags = explode($seperator, $tagList);
				$tags = array_slice($tags, 0, $tagmax, true);
				$tagList = implode($seperator, $tags);
			}
			
			switch($do_type){
				case 'none':
					$tagList = strip_tags($tagList);
				break;
				case 'filter':
					$tags = strip_tags($tagList);
					$tags = explode($seperator, $tags);
					
					$text = '';
					if(!empty($tags)){
						foreach($tags as $key => $tag){
							if($key > 0) $text .= $seperator;
							$text .= '<a href="#" class="adamlabsgallery-triggerfilter" data-filter="filter-'.$tag.'">'.sanitize_title($tag).'</a>';
						}
					}
					$tagList = $text;
				break;
				case 'link':
					//return tagList as it is
				break;
				
			}
			
		}
		// var_dump($tagList);
		return apply_filters('adamlabsgallery_get_tags_html_list', $tagList, $postID, $seperator, $do_type);
	}


	/**
	 * check if text has a certain tag in it
	 */
	public function text_has_certain_tag($string, $tag){
		$r = apply_filters('adamlabsgallery_text_has_certain_tag', array('string' => $string, 'tag' => $tag));
		if(!is_array($r) || !isset($r['string']) || is_array($r['string'])) return "";
		return preg_match("/<" . $r['tag'] . "[^<]+>/", $r['string'], $m) != 0;
	}


	/**
	 * output the demo skin html
	 */
	public static function output_demo_skin_html($data){
		$data = apply_filters('adamlabsgallery_output_demo_skin_html_pre', $data);
		
		$grid = new AdamLabsGallery();
		$base = new AdamLabsGallery_Base();
		$item_skin = new AdamLabsGallery_Item_Skin();
		
		if(!isset($data['postparams']['source-type'])){ //something is wrong, print error
			return array('error' => __('Something is wrong, this may have to do with Server limitations', ADAMLABS_GALLERY_TEXTDOMAIN));
		}
		
		$html = '';
		$preview = '';

		$preview_type = ($data['postparams']['source-type'] == 'custom') ? 'custom' : 'preview';

		$grid_id = (isset($data['id']) && intval($data['id']) > 0) ? intval($data['id']) : '-1';
		
		ob_start();
		$grid->output_adamlabsgallery($grid_id, $data, $preview_type);
		$html = ob_get_contents();
		ob_clean();
		ob_end_clean();

		$skin = $base->getVar($data['params'], 'entry-skin', 0, 'i');
		if($skin > 0){
			ob_start();
			$item_skin->init_by_id($skin);
			$item_skin->output_item_skin('custom');
			$preview = ob_get_contents();
			ob_clean();
			ob_end_clean();
		}

		return apply_filters('adamlabsgallery_output_demo_skin_html_post', array('html' => $html, 'preview' => $preview));

	}


	/**
	 * return all custom element fields
	 */
	public function get_custom_elements_for_javascript(){
		$meta = new AdamLabsGallery_Meta();
		$item_elements = new AdamLabsGallery_Item_Element();

		$elements = array(
					array('name' => 'custom-soundcloud', 'type' => 'input'),
					array('name' => 'custom-vimeo', 'type' => 'input'),
					array('name' => 'custom-youtube', 'type' => 'input'),
					array('name' => 'custom-wistia', 'type' => 'input'),
					array('name' => 'custom-html5-mp4', 'type' => 'input'),
					array('name' => 'custom-html5-ogv', 'type' => 'input'),
					array('name' => 'custom-html5-webm', 'type' => 'input'),
					array('name' => 'custom-image', 'type' => 'image'),
					array('name' => 'custom-text', 'type' => 'textarea'),
					array('name' => 'custom-ratio', 'type' => 'select'),
					array('name' => 'post-link', 'type' => 'input'),
					array('name' => 'custom-filter', 'type' => 'input')
					);

		$custom_meta = $meta->get_all_meta(false);
		
		if(!empty($custom_meta)){
			foreach($custom_meta as $cmeta){
				if($cmeta['type'] == 'text') $cmeta['type'] = 'input';
				
				$elements[] = array('name' => 'adamlabsgallery-cm-'.$cmeta['handle'], 'type' => $cmeta['type'], 'default' => @$cmeta['default']);
			}
		}

		$def_ele = $item_elements->getElementsForDropdown();

		foreach($def_ele as $type => $element){
			foreach($element as $handle => $name){
				$elements[] = array('name' => $handle, 'type' => 'input');
			}
		}

		return apply_filters('adamlabsgallery_get_custom_elements_for_javascript', $elements);
	}


	/**
	 * return all media data of post that we may need
	 */
	public function get_post_media_source_data($post_id, $image_type){
		$ret = array();
		
		$c_post = get_post($post_id);
		
		$ptid = get_post_thumbnail_id($post_id);
		//$ret['featured-image'] = wp_get_attachment_url($ptid, $image_type);
		$feat_img = wp_get_attachment_image_src($ptid, $image_type);
		$feat_img_full = wp_get_attachment_image_src($ptid, 'full');
		$feat_img_alt_text = get_post_meta($ptid, '_wp_attachment_image_alt', true);
		$ret['featured-image'] = ($feat_img !== false) ? $feat_img['0'] : '';
		$ret['featured-image-full'] = ($feat_img_full !== false) ? $feat_img_full['0'] : '';
		$ret['featured-image-alt'] = ($feat_img_alt_text !== '') ? $feat_img_alt_text : '';
		$ret['featured-image-width'] = ($feat_img !== false) ? $feat_img['1'] : '';
		$ret['featured-image-full-width'] = ($feat_img_full !== false) ? $feat_img_full['1'] : '';
		$ret['featured-image-height'] = ($feat_img !== false) ? $feat_img['2'] : '';
		$ret['featured-image-full-height'] = ($feat_img_full !== false) ? $feat_img_full['2'] : '';
		$ret['content-image'] = $this->get_first_content_image(-1, $c_post);
		$ret['content-iframe'] = $this->get_first_content_iframe(-1, $c_post);
		
		$content_id = $this->get_image_id_by_url($ret['content-image']);
		$ret['content-image-alt'] = (!empty($content_id)) ? get_post_meta($content_id, '_wp_attachment_image_alt', true) : '';

		//get Post Metas
		$values = get_post_custom($post_id);

		$ret['youtube'] = isset($values['adamlabsgallery_sources_youtube']) ? esc_attr($values['adamlabsgallery_sources_youtube'][0]) : '';
		$ret['content-youtube'] = $this->get_first_content_youtube(-1, $c_post);
		$ret['vimeo'] = isset($values['adamlabsgallery_sources_vimeo']) ? esc_attr($values['adamlabsgallery_sources_vimeo'][0]) : '';
		$ret['content-vimeo'] = $this->get_first_content_vimeo(-1, $c_post);
		$ret['wistia'] = isset($values['adamlabsgallery_sources_wistia']) ? esc_attr($values['adamlabsgallery_sources_wistia'][0]) : '';
		$ret['content-wistia'] = $this->get_first_content_wistia(-1, $c_post);
		//$ret['alternate-image'] = isset($values['adamlabsgallery_sources_image']) ? wp_get_attachment_url(esc_attr($values['adamlabsgallery_sources_image'][0]), $image_type) : '';
		if(isset($values['adamlabsgallery_sources_image'])){
			$alt_img = wp_get_attachment_image_src(esc_attr($values['adamlabsgallery_sources_image'][0]), $image_type);
			$alt_img_full = wp_get_attachment_image_src(esc_attr($values['adamlabsgallery_sources_image'][0]), 'full');
			$alt_img_text = get_post_meta(esc_attr($values['adamlabsgallery_sources_image'][0]), '_wp_attachment_image_alt', true);
			$ret['alternate-image'] = ($alt_img !== false) ? $alt_img['0'] : '';
			$ret['alternate-image-full'] = ($alt_img_full !== false) ? $alt_img_full['0'] : '';
			$ret['alternate-image-alt'] = ($alt_img_text !== '') ? $alt_img_text : '';
			$ret['alternate-image-width'] = ($alt_img !== false) ? $alt_img['1'] : '';
			$ret['alternate-image-full-width'] = ($alt_img_full !== false) ? $alt_img_full['1'] : '';
			$ret['alternate-image-height'] = ($alt_img !== false) ? $alt_img['2'] : '';
			$ret['alternate-image-full-height'] = ($alt_img_full !== false) ? $alt_img_full['2'] : '';
		}else{
			$ret['alternate-image'] = '';
		}

		$ret['iframe'] = isset($values['adamlabsgallery_sources_iframe']) ? esc_attr($values['adamlabsgallery_sources_iframe'][0]) : '';

		$ret['soundcloud'] = isset($values['adamlabsgallery_sources_soundcloud']) ? esc_attr($values['adamlabsgallery_sources_soundcloud'][0]) : '';
		$ret['content-soundcloud'] = $this->get_first_content_soundcloud(-1, $c_post);
		
		$ret['html5']['mp4'] = isset($values['adamlabsgallery_sources_html5_mp4']) ? esc_attr($values['adamlabsgallery_sources_html5_mp4'][0]) : '';
		$ret['html5']['ogv'] = isset($values['adamlabsgallery_sources_html5_ogv']) ? esc_attr($values['adamlabsgallery_sources_html5_ogv'][0]) : '';
		$ret['html5']['webm'] = isset($values['adamlabsgallery_sources_html5_webm']) ? esc_attr($values['adamlabsgallery_sources_html5_webm'][0]) : '';
		
		$ret['image-fit'] = isset($values['adamlabsgallery_image_fit']) && $values['adamlabsgallery_image_fit'][0] != '-1' ? esc_attr($values['adamlabsgallery_image_fit'][0]) : '';
		$ret['image-repeat'] = isset($values['adamlabsgallery_image_repeat']) && $values['adamlabsgallery_image_repeat'][0] != '-1' ? esc_attr($values['adamlabsgallery_image_repeat'][0]) : '';
		$ret['image-align-horizontal'] = isset($values['adamlabsgallery_image_align_h']) && $values['adamlabsgallery_image_align_h'][0] != '-1' ? esc_attr($values['adamlabsgallery_image_align_h'][0]) : '';
		$ret['image-align-vertical'] = isset($values['adamlabsgallery_image_align_v']) && $values['adamlabsgallery_image_align_v'][0] != '-1' ? esc_attr($values['adamlabsgallery_image_align_v'][0]) : '';
		
		$content_video = $this->get_first_content_video(-1, $c_post);
		
		if($content_video !== false){
			$ret['content-html5']['mp4'] = @$content_video['mp4'];
			$ret['content-html5']['ogv'] = @$content_video['ogv'];
			$ret['content-html5']['webm'] = @$content_video['webm'];
		}else{
			$ret['content-html5']['mp4'] = '';
			$ret['content-html5']['ogv'] = '';
			$ret['content-html5']['webm'] = '';
		}
		
		$ret['revslider'] = isset($values['adamlabsgallery_sources_revslider']) ? esc_attr($values['adamlabsgallery_sources_revslider'][0]) : '';
		$ret['adamlabsgallery'] = isset($values['adamlabsgallery_sources_adamlabsgallery']) ? esc_attr($values['adamlabsgallery_sources_adamlabsgallery'][0]) : '';
		
		return apply_filters('adamlabsgallery_modify_media_sources', $ret, $post_id);

	}


	/**
	 * return all media data of custom element that we may need
	 */
	public function get_custom_media_source_data($values, $image_type){
		
		$ret = array();
		
		$ret['youtube'] = isset($values['custom-youtube']) ? esc_attr($values['custom-youtube']) : '';
		$ret['vimeo'] = isset($values['custom-vimeo']) ? esc_attr($values['custom-vimeo']) : '';
		$ret['wistia'] = isset($values['wistia']) ? esc_attr($values['wistia']) : '';
		
		if(isset($values['custom-image']) || isset($values['custom-image-url'])) {
			
			if(isset($values['custom-image']) && $values['custom-image'] !== ''){
				$alt_img = wp_get_attachment_image_src(esc_attr($values['custom-image']), $image_type);
				$alt_img_full = wp_get_attachment_image_src(esc_attr($values['custom-image']), 'full');
				$alt_text = get_post_meta(esc_attr($values['custom-image']), '_wp_attachment_image_alt', true);
				
			}
			else {
				$alt_img = $values['custom-image-url'];
				if(!empty($values['custom-image-url-full']))
					$alt_img_full =  $values['custom-image-url-full'];
				else
					$alt_img_full =  $values['custom-image-url'];
				$alt_text = '';
			}
			
			$ret['featured-image'] = ($alt_img !== false && isset($alt_img['0'])) ? $alt_img['0'] : '';
			$ret['featured-image-full'] = ($alt_img_full !== false && isset($alt_img_full['0'])) ? $alt_img_full['0'] : '';
			$ret['featured-image-alt'] = ($alt_text !== '') ? $alt_text : '';
			$ret['featured-image-width'] = ($alt_img !== false) ? @$alt_img['1'] : '';
			$ret['featured-image-full-width'] = ($alt_img_full !== false) ? @$alt_img_full['1'] : '';
			$ret['featured-image-height'] = ($alt_img !== false) ? @$alt_img['2'] : '';
			$ret['featured-image-full-height'] = ($alt_img_full !== false) ? @$alt_img_full['2'] : '';
			
			$ret['alternate-image-preload-url'] = (isset($values['custom-preload-image-url'])) ? $values['custom-preload-image-url'] : '';
		}
		
		if(isset($values['adamlabsgallery-alternate-image']) && $values['adamlabsgallery-alternate-image'] !== '') {
			
			$alt_img = wp_get_attachment_image_src(esc_attr($values['adamlabsgallery-alternate-image']), $image_type);
			$alt_img_full = wp_get_attachment_image_src(esc_attr($values['adamlabsgallery-alternate-image']), 'full');
			$alt_text = get_post_meta(esc_attr($values['adamlabsgallery-alternate-image']), '_wp_attachment_image_alt', true);
			
			$ret['alternate-image'] = ($alt_img !== false && isset($alt_img['0']) ) ? $alt_img['0'] : '';
			$ret['alternate-image-full'] = ($alt_img_full !== false && isset($alt_img_full['0']) ) ? $alt_img_full['0'] : '';
			$ret['alternate-image-alt'] = ($alt_text !== '') ? $alt_text : '';
			$ret['alternate-image-width'] = ($alt_img !== false) ? @$alt_img['1'] : '';
			$ret['alternate-image-full-width'] = ($alt_img_full !== false) ? @$alt_img_full['1'] : '';
			$ret['alternate-image-height'] = ($alt_img !== false) ? @$alt_img['2'] : '';
			$ret['alternate-image-full-height'] = ($alt_img_full !== false) ? @$alt_img_full['2'] : '';
			
		}
		
		$ret['image-fit'] = isset($values['image-fit']) && $values['image-fit'] != '-1' ? esc_attr($values['image-fit']) : '';
		$ret['image-repeat'] = isset($values['image-repeat']) && $values['image-repeat'] != '-1' ? esc_attr($values['image-repeat']) : '';
		$ret['image-align-horizontal'] = isset($values['image-align-horizontal']) && $values['image-align-horizontal'] != '-1' ? esc_attr($values['image-align-horizontal']) : '';
		$ret['image-align-vertical'] = isset($values['image-align-vertical']) && $values['image-align-vertical'] != '-1' ? esc_attr($values['image-align-vertical']) : '';

		$ret['soundcloud'] = isset($values['custom-soundcloud']) ? esc_attr($values['custom-soundcloud']) : '';

		$ret['html5']['mp4'] = isset($values['custom-html5-mp4']) ? esc_attr($values['custom-html5-mp4']) : '';
		$ret['html5']['ogv'] = isset($values['custom-html5-ogv']) ? esc_attr($values['custom-html5-ogv']) : '';
		$ret['html5']['webm'] = isset($values['custom-html5-webm']) ? esc_attr($values['custom-html5-webm']) : '';
		
		$ret['html5']['webm'] = isset($values['custom-html5-webm']) ? esc_attr($values['custom-html5-webm']) : '';
		$ret['html5']['webm'] = isset($values['custom-html5-webm']) ? esc_attr($values['custom-html5-webm']) : '';
		
		$ret['iframe'] = isset($values['iframe']) ? esc_attr($values['iframe']) : '';
		$ret['revslider'] = isset($values['revslider']) ? esc_attr($values['revslider']) : '';
		$ret['adamlabsgallery'] = isset($values['adamlabsgallery']) ? esc_attr($values['adamlabsgallery']) : '';
		
		return apply_filters('adamlabsgallery_get_custom_media_source_data', $ret);

	}


	/**
	 * set basic Order List for Main Media Source
	*/
	public static function get_media_source_order(){

		$media = array(	'featured-image' =>  array('name' => __('Featured Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'youtube' =>		 array('name' => __('YouTube Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'vimeo' =>			 array('name' => __('Vimeo Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'wistia' =>		 	 array('name' => __('Wistia Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'html5' =>			 array('name' => __('HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'soundcloud' =>		 array('name' => __('SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'play-circled'),
						'alternate-image' => array('name' => __('Alternate Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'iframe' =>			 array('name' => __('iFrame Markup', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'align-justify'),
						'content-image' =>	 array('name' => __('First Content Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'content-iframe' =>	 array('name' => __('First Content iFrame', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'align-justify'),
						'content-html5' =>	 array('name' => __('First Content HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'content-youtube' => array('name' => __('First Content YouTube Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'content-vimeo' =>	 array('name' => __('First Content Vimeo Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'content-wistia' =>  array('name' => __('First Content Wistia Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'content-soundcloud'=>array('name' => __('First Content SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'play-circled')
						);
						
		return apply_filters('adamlabsgallery_set_media_source_order', apply_filters('adamlabsgallery_get_media_source_order', $media));
		
	}


	/**
	 * set basic Order List for Lightbox Source
	 */
	public static function get_lb_source_order(){

		$media =  array('featured-image' =>    array('name' => __('Featured Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'youtube' =>		   array('name' => __('YouTube Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'vimeo' =>			   array('name' => __('Vimeo Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'wistia' =>		 	   array('name' => __('Wistia Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'html5' =>			   array('name' => __('HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'alternate-image' =>   array('name' => __('Alternate Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'content-image' =>	   array('name' => __('First Content Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'post-content' =>	   array('name' => __('Post Content', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'doc-inv'),
						'revslider' => array('name' => __('Slider Revolution', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'arrows-ccw'),
						'adamlabsgallery' => array('name' => __('Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'th-large'),
						'soundcloud' =>        array('name' => __('SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'soundcloud'),
						'iframe' =>            array('name' => __('iFrame', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'link')
						);
						
		return apply_filters('adamlabsgallery_set_lb_source_order', apply_filters('adamlabsgallery_get_lb_source_order', $media));
		
	}
	
	/**
	 * set basic Order List for Lightbox Source
	 */
	public static function get_lb_button_order(){

		$buttons =  array('share'      =>  array('name' => __('Social Share', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'forward'),
						'slideShow'  =>  array('name' => __('Play / Pause', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'play'),
						'thumbs'     =>	 array('name' => __('Thumbnails', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'th'),
						'zoom'       =>	 array('name' => __('Zoom/Pan', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'search'),
						'download'   =>	 array('name' => __('Download Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'download'),
						'arrowLeft'  =>  array('name' => __('Left Arrow', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'left'),
						'arrowRight' =>	 array('name' => __('Right Arrow', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'right'),
						'close'      =>	 array('name' => __('Close Button', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'cancel')
						);
						
		return apply_filters('adamlabsgallery_set_lb_button_order', apply_filters('adamlabsgallery_get_lb_button_order', $buttons));
		
	}


	/**
	 * set basic Order List for Ajax loading
	 */
	public static function get_aj_source_order(){

		$media =  array('post-content' =>  array('name' => __('Post Content', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'doc-text'),
						'youtube' =>		 array('name' => __('YouTube Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'vimeo' =>			 array('name' => __('Vimeo Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'wistia' =>		     array('name' => __('Wistia Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'html5' =>			 array('name' => __('HTML5 Video', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'soundcloud' =>		 array('name' => __('SoundCloud', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'video'),
						'featured-image' =>  array('name' => __('Featured Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'alternate-image' => array('name' => __('Alternate Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'content-image' =>	 array('name' => __('First Content Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture')
						);
						
		return apply_filters('adamlabsgallery_set_ajax_source_order', apply_filters('adamlabsgallery_get_ajax_source_order', $media));
		
	}


	/**
	 * set basic Order List for Poster Orders
	 */
	public static function get_poster_source_order(){

		$media = array(	'featured-image' =>  array('name' => __('Featured Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'alternate-image' => array('name' => __('Alternate Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'content-image' =>	 array('name' => __('First Content Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'youtube-image' =>	 array('name' => __('YouTube Thumbnail', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'vimeo-image' =>	 array('name' => __('Vimeo Thumbnail', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'default-youtube-image' => array('name' => __('YouTube Default Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'default-vimeo-image' => array('name' => __('Vimeo Default Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'default-html-image' => array('name' => __('HTML5 Default Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'picture'),
						'no-image' =>		 array('name' => __('No Image', ADAMLABS_GALLERY_TEXTDOMAIN), 'type' => 'align-justify')
						);
						
		return apply_filters('adamlabsgallery_set_poster_source_order', apply_filters('adamlabsgallery_get_poster_source_order', $media));
		
	}
	
	
	/**
	 * remove essential grid shortcode from text
	 */
	public function strip_adamlabsgallery_shortcode($content){
	
		if(has_shortcode($content, 'adamlabsgallery')){
			global $shortcode_tags;
			$stack = $shortcode_tags;
			$shortcode_tags = array('adamlabsgallery' => 1);
			$content = strip_shortcodes($content);
			
			$shortcode_tags = $stack;
		}
		
		return apply_filters('adamlabsgallery_strip_adamlabsgallery_shortcode', $content);
	}
	
	
	/**
	 * retrieve all content gallery images in post text
	 */
	public function get_all_gallery_images($content, $url = false){
		
		$ret = array();
		
		if($content !== null){ 
			if(has_shortcode($content, 'gallery')){
				
				preg_match('/\[gallery.*ids=.(.*).\]/', $content, $img_ids);
				
				if(isset($img_ids[1])){
					if($url == false){
						if($img_ids[1] !== '') $ret = explode(',', $img_ids[1]);
					}else{ //get URL instead of ID
						$images = array();
						$imgs = explode(',', $img_ids[1]);
						foreach($imgs as $img){
							$t_img = wp_get_attachment_image_src($img, 'full');
							if($t_img !== false){
								$images[] = $t_img[0];
							}
						}
						$ret = $images;
					}
				}
			}
			
		}
		
		return apply_filters('adamlabsgallery_get_all_gallery_images', $ret, $content, $url);
	}
	

	/**
	 * retrieve the first content image in post text
	 */
	public function get_first_content_image($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);
		
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);

		if(isset($matches[1][0]))
			$first_img = $matches[1][0];

		if(empty($first_img)){
			$first_img = '';
		}
		
		return apply_filters('adamlabsgallery_get_first_content_image', $first_img, $post_id, $post);
		
	}


	/**
	 * retrieve all content images in post text
	 */
	public function get_all_content_images($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);

		$images = array();
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img[^>]*src\s?=\s?([\'"])((?:(?!\1).)*)[^>]*>/i', $post->post_content, $matches);
		
		if(isset($matches[2][0]))
			$images = $matches[2];

		if(empty($images)){
			$images = array();
		}
		
		return apply_filters('adamlabsgallery_get_all_content_images', $images, $post_id, $post);
		
	}
	
	
	/**
	 * retrieve the first iframe in the post text
	 */
	public function get_first_content_iframe($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);
		
		$first_iframe = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<iframe.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		
		if(isset($matches[0][0]))
			$first_iframe = $matches[0][0];

		if(empty($first_iframe)){
			$first_iframe = '';
		}
		
		return apply_filters('adamlabsgallery_get_first_content_iframe', $first_iframe, $post_id, $post);
		
	}
	
	/**
	 * retrieve the first youtube video in the post text
	 */
	public function get_first_content_youtube($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);

		$first_yt = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/(http:|https:|:)?\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[?=&+%\w-]*/i', $post->post_content, $matches);
		
		if(isset($matches[2][0]))
			$first_yt = $matches[2][0];

		if(empty($first_yt)){
			$first_yt = '';
		}
		
		return apply_filters('adamlabsgallery_get_first_content_youtube', $first_yt, $post_id, $post);
	}
	
	
	/**
	 * retrieve the first vimeo video in the post text
	 */
	public function get_first_content_vimeo($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);

		$first_vim = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/(http:|https:|:)?\/\/?vimeo\.com\/([0-9]+)\??|player\.vimeo\.com\/video\/([0-9]+)\??/i', $post->post_content, $matches);
		
		if(isset($matches[2][0]) && !empty($matches[2][0]))
			$first_vim = $matches[2][0];
		if(isset($matches[3][0]) && !empty($matches[3][0]))
			$first_vim = $matches[3][0];

		if(empty($first_vim)){
			$first_vim = '';
		}
		
		return apply_filters('adamlabsgallery_get_first_content_vimeo', $first_vim, $post_id, $post);
	}
	
	
	/**
	 * retrieve the first wistia video in the post text
	 */
	public function get_first_content_wistia($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);

		$first_ws = '';
		ob_start();
		ob_end_clean();
		// /(http:|https:|:)?\/\/?wistia\.net\/([0-9a-z]+)\??|player\.wistia\.net\/video\/([0-9a-z]+)\??|(wistia.com|wi.st|wistia.net)\/(medias|embed)\/([0-9a-z]+)\.*/i
		$output = preg_match_all('/(http:|https:|:)?\/\/?wistia\.net\/([0-9]+)\??|player\.wistia\.net\/video\/([0-9]+)\??/i', $post->post_content, $matches);
		
		if(isset($matches[2][0]))
			$first_ws = $matches[2][0];

		if(empty($first_ws)){
			$output = preg_match_all("/wistia\.com\/(medias|embed)\/([0-9a-z]+)/i", $post->post_content, $matches);
			if(isset($matches[2][0]))
				$first_ws = $matches[2][0];
			
			if(empty($first_ws)){
				$first_ws = '';
			}
		}
		
		return apply_filters('adamlabsgallery_get_first_content_wistia', $first_ws, $post_id, $post);
	}
	
	
	/**
	 * retrieve the first video in the post text
	 */
	public function get_first_content_video($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);
		
		$video = false;
		ob_start();
		ob_end_clean();
		$output = preg_match_all("'<video>(.*?)</video>'si", $post->post_content, $matches);
		
		if(isset($matches[0][0])){
			$videos = preg_match_all('/<source.+src=[\'"]([^\'"]+)[\'"].*>/i', $matches[0][0], $video_match);
			if(isset($video_match[1]) && is_array($video_match[1])){
				foreach($video_match[1] as $video_source){
					$vid = explode('.', $video_source);
					switch(end($vid)){
						case 'ogv':
							$video['ogv'] = $video_source;
							break;
						case 'webm':
							$video['webm'] = $video_source;
							break;
						case 'mp4':
							$video['mp4'] = $video_source;
							break;
					}
				}
			}
		}

		if(empty($video)){
			$video = false;
		}
		
		return apply_filters('adamlabsgallery_get_first_content_video', $video, $post_id, $post);
		
	}
	
	
	/**
	 * retrieve the first soundcloud in the post text
	 */
	public function get_first_content_soundcloud($post_id, $post = false) {
		if($post_id != -1)
			$post = get_post($post_id);

		$first_sc = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/\/\/api.soundcloud.com\/tracks\/(.[0-9]*)/i', $post->post_content, $matches);
		
		if(isset($matches[1][0]))
			$first_sc = $matches[1][0];
			
		if(empty($first_sc)){
			$first_sc = '';
		}
		
		return apply_filters('adamlabsgallery_get_first_content_soundcloud', $first_sc, $post_id, $post);
	}
	
	
	/**
	 * retrieve the image id from the given image url
	 */
	public function get_image_id_by_url($image_url) {
		global $wpdb;
		$attachment_id = false;

		// If there is no url, return.
		if ( '' != $image_url ){

			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $image_url, $upload_dir_paths['baseurl'] ) ) {

				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$image_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_url );

				// Remove the upload path base directory from the attachment URL
				$image_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $image_url );

				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $image_url ) );

			}
			
		}
		
		return apply_filters('adamlabsgallery_get_image_id_by_url', $attachment_id, $image_url);
	}


	/**
	 * check if in the content exists a certain essential grid
	 */
	public function is_shortcode_with_handle_exist($grid_handle) {

		$content = get_the_content();
		$pattern = get_shortcode_regex();
        preg_match_all('/'.$pattern.'/s', $content, $matches);

		$found = false;

		if(is_array($matches[2]) && !empty($matches[2])){ //
			foreach($matches[2] as $key => $sc){
				if($sc == 'adamlabsgallery'){
					$attr = shortcode_parse_atts($matches[3][$key]);
					if(isset($attr['alias'])){
						if($grid_handle == $attr['alias']){
							$found = true;
							break;
						}
					}
				}
			}
		}

		return apply_filters('adamlabsgallery_get_image_id_by_url', $found, $grid_handle);
	}


	/**
	 * minimize CSS styles
	 */
	public function compress_css($buffer){
		$buffer = apply_filters('adamlabsgallery_compress_css_pre', $buffer);

		/* remove comments */
		$buffer = preg_replace("!/\*[^*]*\*+([^/][^*]*\*+)*/!", "", $buffer);
		/* remove tabs, spaces, newlines, etc. */
		$buffer = str_replace("	", " ", $buffer); //replace tab with space
		$arr = array("\r\n", "\r", "\n", "\t", "  ", "    ", "    ");
		$rep = array("", "", "", "", " ", " ", " ");
		$buffer = str_replace($arr, $rep, $buffer);
		/* remove whitespaces around {}:, */
		$buffer = preg_replace("/\s*([\{\}:,])\s*/", "$1", $buffer);
		/* remove last ; */
		$buffer = str_replace(';}', "}", $buffer);

		return apply_filters('adamlabsgallery_compress_css_post', $buffer);
	}
	
	/**
	 * shuffle by preserving the key
	 */
	public function shuffle_assoc($list){
		if (!is_array($list)) return $list; 

		$keys = array_keys($list); 
		shuffle($keys); 
		$random = array(); 
		foreach($keys as $key){ 
			$random[$key] = $list[$key]; 
		}
		
		return apply_filters('adamlabsgallery_shuffle_assoc', $random);
	}
	
	/**
	 * prints out debug text if constant ADAMLABS_DEBUG is defined and true
	 */
	public static function debug($value , $message, $where = "console"){
		if( defined('ADAMLABS_DEBUG') && ADAMLABS_DEBUG ){
			if($where=="console"){
				echo '<script>
					jQuery(document).ready(function(){
						if(window.console) {
							console.log("'.$message.'");
							console.log('.json_encode($value).');
						}
					});
				</script>
				';
			}
			else{
				var_dump($value);
			}
		}
		else {
			return false;
		}
	}

	/**
	 * prints out numbers in YouTube format
	 */
	public static function thousandsViewFormat($num) {
		if($num > 999){
			  $x = round($num);
			  $x_number_format = number_format($x);
			  $x_array = explode(',', $x_number_format);
			  $x_parts = array('K', 'M', 'B', 'T');
			  $x_count_parts = count($x_array) - 1;
			  $x_display = $x;
			  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			  $x_display .= $x_parts[$x_count_parts - 1];
		}
		else $x_display = $num;
	  	
	  	return $x_display;
	}

}