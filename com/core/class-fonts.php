<?php
 
if( !defined( 'ABSPATH') ) exit();

if(!class_exists('AdamLabs_Fonts')) {
	 
	class AdamLabs_Fonts {

		/**
		 * Add a new Font 
		 */
		public function add_new_font($new_font){
			
			if(!isset($new_font['url']) || strlen($new_font['url']) < 3) return __('Wrong parameter received', ADAMLABS_GALLERY_TEXTDOMAIN);
			if(!isset($new_font['handle']) || strlen($new_font['handle']) < 3) return __('Wrong handle received', ADAMLABS_GALLERY_TEXTDOMAIN);
			
			$fonts = $this->get_all_fonts();
			
			if(!empty($fonts)){
				foreach($fonts as $font){
					if($font['handle'] == $new_font['handle']) return __('Font with handle already exist, choose a different handle', ADAMLABS_GALLERY_TEXTDOMAIN);
				}
			}
			
			$new = array('url' => $new_font['url'], 'handle' => $new_font['handle']);
			
			$fonts[] = $new;
			
			$do = update_option('adamlabs-google-fonts', $fonts);
			
			return true;
		}
		
		
		/**
		 * change font by handle
		 */
		public function edit_font_by_handle($edit_font){
			
			if(!isset($edit_font['handle']) || strlen($edit_font['handle']) < 3) return __('Wrong Handle received', ADAMLABS_GALLERY_TEXTDOMAIN);
			if(!isset($edit_font['url']) || strlen($edit_font['url']) < 3) return __('Wrong Params received', ADAMLABS_GALLERY_TEXTDOMAIN);
			
			$fonts = $this->get_all_fonts();
			
			if(!empty($fonts)){
				foreach($fonts as $key => $font){
					if($font['handle'] == $edit_font['handle']){
						$fonts[$key]['handle'] = $edit_font['handle'];
						$fonts[$key]['url'] = $edit_font['url'];
						
						$do = update_option('adamlabs-google-fonts', $fonts);
						return true;
					}
				}
			}
			
			return false;
		}
		
		
		/**
		 * Remove Font
		 */
		public function remove_font_by_handle($handle){
			
			$fonts = $this->get_all_fonts();
			
			if(!empty($fonts)){
				foreach($fonts as $key => $font){
					if($font['handle'] == $handle){
						unset($fonts[$key]);
						$do = update_option('adamlabs-google-fonts', $fonts);
						return true;
					}
				}
			}
			
			return __('Font not found! Wrong handle given.', ADAMLABS_GALLERY_TEXTDOMAIN);
		}
		
		
		/**
		 * get all fonts
		 */
		public function get_all_fonts(){
		
			$fonts = get_option('adamlabs-google-fonts', array());
			
			return $fonts;
		}
		
		
		/**
		 * get all handle of fonts 
		 */
		public function get_all_fonts_handle(){
			$fonts = array();
			
			$font = get_option('adamlabs-google-fonts', array());
			
			if(!empty($font)){
				foreach($font as $f){
					$fonts[] = $f['handle'];
				}
			}
			
			return $fonts;
		}
		
		
		/**
		 * register all fonts
		 */
		public function register_fonts(){
		
			$fonts = $this->get_all_fonts();
			
			$http = (is_ssl()) ? 'https' : 'http';
			
			$font_url = $http.'://fonts.googleapis.com/css?family=';
			$font_url = apply_filters('adamlabsfonts_modify_url', $font_url);
			
			if(!empty($fonts)){
				foreach($fonts as $font){
					if($font !== ''){
						$font = apply_filters('adamlabsfonts_modify_font', $font);
						wp_register_style('adamlabs-'.sanitize_title($font['handle']), $font_url.strip_tags($font['url']));
						wp_enqueue_style('adamlabs-'.sanitize_title($font['handle']));
					}
				}
			}
			
		}

		/**
		 * register all fonts
		 */
		public function register_icon_fonts($focus){
			$enable_fontello = get_option('adamlabsgallery_global_enable_fontello', 'backfront');
			$enable_font_awesome = get_option('adamlabsgallery_global_enable_font_awesome', 'false');
			$enable_pe7 = get_option('adamlabsgallery_global_enable_pe7', 'false');
			
			if($focus=="admin"){
				if($enable_fontello!="false") wp_enqueue_style('adamlabs-fontello', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/fontello/css/fontello.css', array(), AdamLabsGallery::VERSION );
				if($enable_pe7!="false") wp_enqueue_style('adamlabs-stroke-7', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), AdamLabsGallery::VERSION );
				if($enable_font_awesome!="false") wp_enqueue_style('adamlabs-font-awesome', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/font-awesome/css/font-awesome.css', array(), AdamLabsGallery::VERSION );
			}
			else{
				if($enable_fontello=="backfront") wp_enqueue_style('adamlabs-fontello', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/fontello/css/fontello.css', array(), AdamLabsGallery::VERSION );
				if($enable_font_awesome=="backfront") wp_enqueue_style('adamlabs-font-awesome', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/font-awesome/css/font-awesome.css', array(), AdamLabsGallery::VERSION );
				if($enable_pe7=="backfront") wp_enqueue_style('adamlabs-stroke-7', ADAMLABS_GALLERY_PLUGIN_URL . 'com/public/assets/font/pe-icon-7-stroke/css/pe-icon-7-stroke.css', array(), AdamLabsGallery::VERSION );
			}
			
		}
		
		
		/**
		 * register all fonts
		 */
		public static function propagate_default_fonts($networkwide = false){
			
			$default = array (
					array('url' => 'Open+Sans:300,400,600,700,800', 'handle' => 'open-sans'),
					array('url' => 'Raleway:100,200,300,400,500,600,700,800,900', 'handle' => 'raleway'),
					array('url' => 'Droid+Serif:400,700', 'handle' => 'droid-serif' )
				); 
			
			$default = apply_filters('adamlabsgallery_add_default_fonts', $default); //will be obsolete soon, use tp_add_default_fonts instead
			$default = apply_filters('adamlabs_add_default_fonts', $default);
			
			if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
				global $wpdb;
				
				// 2.2.5
				// $old_blog = $wpdb->blogid;
				
				// Get all blog ids and create tables
				$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
				
				foreach($blogids as $blog_id){
					switch_to_blog($blog_id);
					self::_propagate_default_fonts($default);
					
					// 2.2.5
					restore_current_blog();
				}
				
				// 2.2.5
				// switch_to_blog($old_blog); //go back to correct blog
				
			}else{
			
				self::_propagate_default_fonts($default);
				
			}
			
		}
		
		/**
		 * register all fonts modified for multisite
		 */
		public static function _propagate_default_fonts($default){
			
			$fonts = get_option('adamlabs-google-fonts', array());
			
			if(!empty($fonts)){ // do nothing
				/*foreach($default as $d_key => $d_font){
					$found = false;
					foreach($fonts as $font){
						if($font['handle'] == $d_font['handle']){
							$found = true;
							break;
						}
					}
					
					if($found == false)
						$fonts[] = $default[$d_key];
				}
				
				update_option('adamlabs-google-fonts', $fonts);
				*/
			}else{
				
				update_option('adamlabs-google-fonts', $default);
				
			}
			
		}
		
	}
}
?>