<?php
 
if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Search {
	
	private $plugin_slug = '';
	private $settings = array();
	private $base;
	
	public function __construct($force = false) {
		$base = new AdamLabsGallery_Base();
		$this->base = $base;
		
		$plugin = AdamLabsGallery::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		$settings = get_option('adamlabsgallery-search-settings', array('settings' => array(), 'global' => array(), 'shortcode' => array()));
		
		if($force){ //change settings to force inclusion by setting search-enable to on
			$settings['settings']['search-enable'] = 'on';
		}
		
		$settings = AdamLabsGallery_Base::stripslashes_deep($settings);
		
		$this->settings = $settings;
		
		if(!is_admin()){ //only for frondend
			if($base->getVar($settings['settings'], 'search-enable', 'off') == 'on'){
				add_action( 'wp_footer', array( $this, 'enqueue_styles' ) ); //wp_enqueue_scripts
				add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) ); //wp_enqueue_scripts
			}
		}
		
		do_action('adamlabsgallery_search__construct', $this);
		
	}
	
	
	/** 
	 * add search shortcode functionality
	 */
	public static function register_shortcode_search($args, $mid_content = null){
		extract(shortcode_atts(array('handle' => ''), $args, 'adamlabsgallery_search'));
        
		if(trim($handle) === '') return false;
		
		$settings = get_option('adamlabsgallery-search-settings', array('settings' => array(), 'global' => array(), 'shortcode' => array()));
		
		$settings = AdamLabsGallery_Base::stripslashes_deep($settings);
		
		if(!isset($settings['shortcode']['sc-handle'])) return false;
		
		$use_key = false;
		
		foreach($settings['shortcode']['sc-handle'] as $key => $sc_handle){
			if($sc_handle === $handle){
				$use_key = $key;
			}
		}
		
		if($key === false) return false;
		
		//we have found it, now proceed if correct handle and a text was set it
		$class = 'adamlabsgallery-'.sanitize_html_class($settings['shortcode']['sc-handle'][$use_key]);
		if($class === '') return false;
		
		$text = trim($settings['shortcode']['sc-html'][$use_key]);
		if($text === '') return false;
		
		//modify text so that we add 1. the class to existing if there is a tag element in it (add only to first wrap). 2. the class as new if there is a tag element inside. 3. wrap text around it if there is not tag element
		
		
		$search = new AdamLabsGallery_Search(true); //true will enqueue scripts to page
		
		preg_match_all('/<(.*?)>/', $text, $matches);
		
		if(!empty($matches[0])){ //check if first tag has class, if not add it
			$string = $matches[0][0];
			if(strpos($string, 'class="') !== false){
				$new_text = str_replace('class="', 'class="'.$class.' ', $string);
			}elseif(strpos($string, "class='") !== false){
				$new_text = str_replace("class='", "class='".$class.' ', $string);
			}else{
				$use_string = $matches[1][0];
				$new_text = '<'.$use_string.' class="'.$class.'">';
			}
			$text = str_replace($string, $new_text, $text);
		}else{
			$text = '<a href="javascript:void(0);" class="'.$class.'">'.$text.'</a>';
		}
		
		return apply_filters('adamlabsgallery_register_shortcode_search', $text, $args);
		
	}
	
	
	/** 
	 * enqueue styles on startup
	 */
	public function enqueue_styles(){
		$style = apply_filters('adamlabsgallery_enqueue_styles_search', '<style type="text/css">
		
			/* LIGHT */
			#adamlabsgallery_search_wrapper {
				height: auto;
				left: 0;
				opacity: 0;
				position: absolute;
				top: 0;
				width: 100%;
				z-index: 20000;
			}
			#adamlabsgallery_search_wrapper .adamlabsgallery-filter-wrapper, #adamlabsgallery_search_wrapper .adamlabsgallery-pagination {
				margin-left: 0 !important;
			}
			#adamlabsgallery_big_search_wrapper {
				padding-top: 0;
				position: relative;
			}
			#adamlabsgallery_big_search_wrapper .bigsearchfield {
				-moz-border-bottom-colors: none !important;
				-moz-border-left-colors: none !important;
				-moz-border-right-colors: none !important;
				-moz-border-top-colors: none !important;
				background: none repeat scroll 0 0 rgba(255, 255, 255, 0) !important;
				border:none !important;
				border-bottom:2px solid #333 !important;
				color: #333 !important;
				font-size: 40px !important;
				font-weight: 700;
				line-height: 40px !important;
				padding: 0 !important;
				position: relative;
				width: 100%;
				z-index: 10;
				height:50px !important;		
				border-radius:0 !important;
				-webkit-border-radius:0 !important;
				-moz-border-radius:0 !important;								
			}
			
			#adamlabsgallery_big_search_wrapper::-ms-clear { display: none; }
			#adamlabsgallery_big_search_wrapper .bigsearchfield::-ms-clear { display: none; }
			
			#adamlabsgallery_big_search_fake_txt {
				background: none repeat scroll 0 0 rgba(255, 255,255, 0) !important;
				border: medium none !important;
				color: #333 !important;
				font-size: 40px !important;
				font-weight: 700;
				line-height: 40px !important;
				padding: 0 !important;
				position: absolute !important;
				top: 7px !important;
				width: 100%;
				z-index: 5;
				height:50px !important;
			}
			.adamlabsgallery_big_search_close {
				color: #333;
				cursor: pointer;
				font-size: 40px;
				font-weight: 400;
				position: absolute;
				right: 30px;
				top: 50px;
				z-index: 20;
			}
			.adamlabsgallery_searchresult_title {
				color: #333;
				font-size: 11px;
				font-weight: 700;
				letter-spacing: 1px;
				margin-top: 30px;
				text-transform: uppercase;
			}
			#adamlabsgallery_search_bg {
				background: none repeat scroll 0 0 rgba(255, 255, 255, 0.85);
				height: 100%;
				left: 0;
				position: fixed;
				top: 0;
				width: 100%;
				z-index: 19999;
			}
			.adamlabsgallery_searchcontainer {
				box-sizing: border-box;
				padding: 40px;
				position: relative;
			}
			.adamlabsgallery_searchresult {
				color: #333;
				font-size: 17px;
				font-weight: 600;
				line-height: 26px;
			}
			
			/* DARK */
			#adamlabsgallery_big_search_wrapper.dark .bigsearchfield {
				background: none repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
				border-bottom:2px solid #fff !important;
				color: #fff !important;
			}
			
			.dark #adamlabsgallery_big_search_fake_txt {
				background: none repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
				color: #fff !important;
			}
			.dark .adamlabsgallery_big_search_close {
				color: #fff;
			}
			.dark .adamlabsgallery_searchresult_title {
				color: #fff;
			}
			#adamlabsgallery_search_bg.dark {
				background: none repeat scroll 0 0 rgba(0, 0, 0, 0.85);
			}
			.dark .adamlabsgallery_searchresult {
				color: #fff;
			}
		</style>', (object)$this->settings);
		
		echo $style;
		
		add_action('adamlabsgallery_add_search_style', (object)$this->settings);
		
	}
	
	
	/** 
	 * enqueue scripts on startup
	 */
	public function enqueue_scripts(){
		
		wp_enqueue_script('adamlabs-tools');
		wp_enqueue_script('adamlabsgallery-adamlabsgallery-script');
		
		$globals = $this->base->getVar($this->settings, 'global', array());
		$shortcode = $this->base->getVar($this->settings, 'shortcode', array());
		
		$search_classes = $this->base->getVar($globals, 'search-class', array());
		$search_styles = $this->base->getVar($globals, 'search-style', array());
		$search_skins = $this->base->getVar($globals, 'search-grid-id', array());
		
		$sc_classes = $this->base->getVar($shortcode, 'sc-handle', array());
		$sc_styles = $this->base->getVar($shortcode, 'sc-style', array());
		$sc_skins = $this->base->getVar($shortcode, 'sc-grid-id', array());
		
		//add shortcodes also here
		if(!empty($sc_classes)){
			foreach($sc_classes as $key => $handle){
				$sc_classes[$key] = '.adamlabsgallery-'.sanitize_html_class($handle);
				if($sc_classes[$key] === '.adamlabsgallery-'){
					unset($sc_classes[$key]);
					unset($sc_styles[$key]);
					unset($sc_skins[$key]);
				}else{
					$search_classes[] = $sc_classes[$key];
					$search_styles[] = $sc_styles[$key];
					$search_skins[] = $sc_skins[$key];
				}
			}
		}
		
		$search_class = implode(', ', $search_classes);
		
		if(trim($search_class) === '') return true;
		
		?>
		<script type="text/javascript">
			jQuery('body').on('click', '<?php echo $search_class; ?>', function(e) {
				
				if(jQuery('#adamlabsgallery_search_bg').length > 0) return true; //only allow one instance at a time
				
				var identifier = 0;
				var overlay_skin = <?php echo json_encode($search_styles); ?>;
				var skins = <?php echo json_encode($search_skins); ?>;
				
				<?php
				foreach($search_classes as $k => $ident){
					if($k > 0) echo 'else ';
					echo 'if(jQuery(this).is(\''.$ident.'\')){'."\n";
					echo '				identifier = '.$k.';'."\n";
					echo '			}';
				}
				?>
				
				var counter = {val:jQuery(document).scrollTop()};
				
				adamlabsgallerygs.TweenLite.to(counter,0.5,{val:0,ease:adamlabsgallerygs.Power4.easeOut,
					onUpdate:function() {
						forcescrolled = true;
						adamlabsgallerygs.TweenLite.set(jQuery(window),{scrollTop:counter.val});
					},
					onComplete:function(){
						forcescrolled = false;
					}
				});

				forcescrolled = true;

				jQuery('body').append('<div id="adamlabsgallery_search_bg" class="'+overlay_skin[identifier]+'"></div><div id="adamlabsgallery_search_wrapper"></div>');
				var sw = jQuery('#adamlabsgallery_search_wrapper'),
					sb = jQuery('#adamlabsgallery_search_bg'),
					onfocus = "if(this.value == '<?php _e('Enter your search', ADAMLABS_GALLERY_TEXTDOMAIN); ?>') { this.value = ''; }",
					onblur = "if(this.value == '') { this.value = '<?php _e('Enter your search', ADAMLABS_GALLERY_TEXTDOMAIN); ?>'; }",
					ivalue = "<?php _e('Enter your search', ADAMLABS_GALLERY_TEXTDOMAIN); ?>";

				sw.append('<div class="adamlabsgallery_searchcontainer '+overlay_skin[identifier]+'" style="position:relative; width:100%; "></div>');
				var cont = sw.find('.adamlabsgallery_searchcontainer');
				cont.append('<div id="adamlabsgallery_big_search_wrapper" class="'+overlay_skin[identifier]+'"><div id="adamlabsgallery_big_search_fake_txt"><?php _e('Enter your search', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div><input class="bigsearchfield" name="bigsearchfield" type="text"></input></div><div class="adamlabsgallery_big_search_close"><i class="adamlabsgallery-icon-cancel"></i></div>');
				cont.append('<div class="adamlabsgallery_searchresult_title"></div>');

				var bsft = jQuery('#adamlabsgallery_big_search_fake_txt'),
					myst = new adamlabsgallerygs.SplitText(bsft,{type:"words,chars"}),
					mytl = new adamlabsgallerygs.TimelineLite();
				mytl.pause(0);

				mytl.add(adamlabsgallerygs.TweenLite.to(bsft,0.4,{x:30,ease:adamlabsgallerygs.Power2.easeOut}));
				jQuery.each(myst.chars,function(index,chars) {
					mytl.add(adamlabsgallerygs.TweenLite.to(chars,0.2,{autoAlpha:0,scale:0.8,ease:adamlabsgallerygs.Power2.easeOut}),(Math.random()*0.2));
				});


				var inp = cont.find('input');
				setTimeout(function() {
					inp.focus();
				},450);

				inp.on('keyup',function(e) {
					if (inp.val().length==0)
						mytl.reverse();
					else
						mytl.play();
				});
				inp.on('keypress',function(e) {
					if (inp.val().length==0)
						mytl.reverse();
					else
						mytl.play();

					if (e.keyCode==13) {
						cont.find('.adamlabsgallery_searchresult').remove();
						
						var objData = {
							action: 'adamlabsgallery_front_request_ajax',
							client_action: 'get_search_results',
							token: '<?php echo wp_create_nonce('AdamLabsGallery_Front'); ?>',
							data: {search: inp.val(), skin: skins[identifier] }
						};
						
						jQuery.ajax({
							type:'post',
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							dataType:'json',
							data:objData
						}).success(function(result,status,arg3) {
							if(typeof(result.data) !== 'undefined'){
								jQuery('#adamlabsgallery_search_wrapper .adamlabsgallery_searchcontainer').append("<div class='adamlabsgallery_searchresult'>"+result.data+"</div>");
							}
						}).error(function(arg1, arg2, arg3) {
							jQuery('#adamlabsgallery_search_wrapper .adamlabsgallery_searchcontainer').html("<div class='adamlabsgallery_searchresult'><p class='futyi'>FAILURE: "+arg2+"   "+arg3+"</p></div>");
						});

					}
				});

				adamlabsgallerygs.TweenLite.fromTo(sw,0.4,{x:0,y:0,scale:0.7,autoAlpha:0,transformOrigin:"50% 0%"},{scale:1,autoAlpha:1,x:0,ease:adamlabsgallerygs.Power3.easeOut,delay:0.1});
				adamlabsgallerygs.TweenLite.fromTo(sb,0.4,{x:0,y:0,scale:0.9,autoAlpha:0,transformOrigin:"50% 0%"},{scale:1,autoAlpha:1,x:0,ease:adamlabsgallerygs.Power3.easeOut});
				var bgs =  jQuery('.adamlabsgallery_big_search_close');
				bgs.hover(function() {
					adamlabsgallerygs.TweenLite.to(bgs,0.4,{rotation:180});
				},function() {
					adamlabsgallerygs.TweenLite.to(bgs,0.4,{rotation:0});
				})
				bgs.click(function() {
					adamlabsgallerygs.TweenLite.to(sw,0.4,{x:0,y:0,scale:0.8,autoAlpha:0,ease:adamlabsgallerygs.Power3.easeOut,onComplete:function(){
						sw.remove();
						//kill everything from essential !!!!
					}});
					adamlabsgallerygs.TweenLite.to(sb,0.4,{x:0,y:0,scale:0.9,delay:0.1,autoAlpha:0,ease:adamlabsgallerygs.Power3.easeOut,onComplete:function(){
						sb.remove();
					}});
				});
			});
		</script>
		<?php
		add_action('adamlabsgallery_add_search_script', (object)$this->settings);
	}
	
	
	/** 
	 * return search result HTML
	 */
	public function output_search_result($search, $skin_id = 0){
		$skin_id = intval($skin_id);
		
		if($search == '' || $skin_id === 0){
			return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		} 
		
		$post_types = get_post_types(array('public' => true, 'exclude_from_search' => false), 'objects');
		$searchable_types = array();
		if( $post_types ) {
			foreach( $post_types as $type) {
				$searchable_types[] = $type->name;
			}
		}
		$args = array(
			's'         => $search,
			'showposts' => -1,
			'post_type' => $searchable_types
		);
		
		$args = apply_filters('adamlabsgallery_modify_search_query', $args);
		
		$query_type = get_option('adamlabsgallery_query_type', 'wp_query');
		
		$wp_query = new WP_Query();
		$wp_query->parse_query($args);
		
		$adamlabs_allsearch = $wp_query->get_posts();
		//$adamlabs_allsearch = new WP_Query($args);
		
		if(empty($adamlabs_allsearch)){
			return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		}
		/*if(!$adamlabs_allsearch->post_count) {
			return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		}*/
		
		//global $post;
		
		$posts = array();
		foreach($adamlabs_allsearch as $search){
			$posts[] = $search->ID;
		}
		
		/*if($adamlabs_allsearch->have_posts()){
			while($adamlabs_allsearch->have_posts()){
				$adamlabs_allsearch->the_post();
				$posts[] = $post->ID;
			}
		}*/
		
		$alias = AdamLabsGallery::get_alias_by_id($skin_id);
		if($alias == ''){
			return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		}
		
		$content = do_shortcode(apply_filters('adamlabsgallery_output_search_result', '[adamlabsgallery alias="'.$alias.'" posts="'.implode(',', $posts).'"]'));
		wp_reset_query();
		
		return $content;
	}
	
	
	/** 
	 * return search result ID's
	 */
	public static function output_search_result_ids($search, $grid_id = 0){
		
		$s = apply_filters('output_search_result_ids_pre', array('search' => $search, 'grid_id' => $grid_id));
		
		$search = $s['search'];
		$grid_id = $s['grid_id'];
		
		$grid_id = intval($grid_id);
		
		if($search == '' || $grid_id === 0){
			return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		} 
		
		$grid = new AdamLabsGallery;
		if($grid->init_by_id($grid_id) === false) return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		
		$base = new AdamLabsGallery_Base();
		
		if($grid->is_custom_grid()){
			$ids = array();
			
			$custom_entries = $grid->get_layer_values();
			
			if($custom_entries !== false && !empty($custom_entries)){
				foreach($custom_entries as $key => $entry){
					$text_found = self::search_in_array($entry, $search, 'custom-');
					
					if($text_found === false && isset($entry['custom-image'])){ //search in image informations
						//$metas = get_post_meta(esc_attr($entry['custom-image']), '_wp_attachment_metadata'); //, '_wp_attachment_image_alt', true
						$title = get_the_title(esc_attr($entry['custom-image']));
						$title = strtolower($title);
						$val = strtolower($search);
						
						if(strpos($title, $val) !== false) $text_found = true;
					}
					
					if($text_found) $ids[] = $key;
				}
			}
			
			if (empty($ids)) return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
		}else{
			$post_category = $grid->get_postparam_by_handle('post_category');
			$post_types = $grid->get_postparam_by_handle('post_types');
			$page_ids = explode(',', $grid->get_postparam_by_handle('selected_pages', '-1'));
			$start_sortby = $grid->get_param_by_handle('sorting-order-by-start', 'none');
			$start_sortby_type = $grid->get_param_by_handle('sorting-order-type', 'ASC');
			$max_entries = $grid->get_maximum_entries($grid);
			$cat_tax = AdamLabsGallery_Base::getCatAndTaxData($post_category);
			$additional_query = $grid->get_postparam_by_handle('additional-query', '');
			if($additional_query !== ''){
				$additional_query .= '&s='.$search;
			}else{
				$additional_query .= 's='.$search;
			}
			$additional_query = wp_parse_args($additional_query);
			
			ob_start();
			$posts = AdamLabsGallery_Base::getPostsByCategory($grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, $max_entries, $additional_query, false);
			ob_clean();
			ob_end_clean();
		
			
			if(empty($posts) || count($posts) === 0){
				return __('Not found', ADAMLABS_GALLERY_TEXTDOMAIN);
			}
			
			$ids = array();
			
			foreach($posts as $post){
				$ids[] = $post['ID'];
			}
		}
		
		$ids = apply_filters('output_search_result_ids_post', $ids);
		
		return $ids;
	}
	
	
	/** 
	 * return if in array the search string can be found
	 */
	public static function search_in_array($array, $search, $ignore){
		if(!empty($array) && is_array($array)){
			foreach($array as $key => $val){
				if(strpos($key, $ignore) !== false) continue;
				$search = strtolower($search);
				$val = strtolower($val);
				
				if(strpos($val, $search) !== false) return true;
			}
		}
		
		return false;
	}
	
	
}

?>