<?php
 
if( !defined( 'ABSPATH') ) exit();

if (!isset($wp_rewrite))
	$wp_rewrite = new WP_Rewrite();

if(!class_exists('AdamLabsPost')) {
	class AdamLabsPost {
		
		// Variables for Post Data
		public $MP_title;
		public $MP_type;
		public $MP_content;
		public $MP_category;
		public $MP_taxonomy;
		public $MP_terms;
		public $MP_template;
		public $MP_slug;
		public $MP_date;
		public $MP_post_tags;
		public $MP_meta;
		public $MP_auth_id;
		public $MP_status = "publish";
		
		// Variables for Post Updating
		public $MP_current_post;
		public $MP_current_post_id;
		public $MP_current_post_permalink;
		
		// Error Array
		public $MP_errors;
		
		// Creation functions
		public function create() {
			$cat = apply_filters('AdamLabsGallery_category', 'adamlabsgallery_category');
			
			$error_obj = "";
			if(isset($this->MP_title) ) {
				if ($this->MP_type == 'page')
					$post = get_page_by_title( $this->MP_title, 'OBJECT', $this->MP_type );
				else 
					$post = get_page_by_title( $this->MP_title, 'OBJECT', $this->MP_type );
					
				$post_data = array(
					'post_title'    => wp_strip_all_tags($this->MP_title),
					'post_name'     => $this->MP_slug,
					'post_content'  => $this->MP_content,
					'post_status'   => $this->MP_status,
					'post_type'     => $this->MP_type,
					'post_author'   => $this->MP_auth_id,
					'post_category' => $this->MP_category,
					'page_template' => $this->MP_template,
					'post_date'		=> $this->MP_date
				);

				if(!isset($post)){
					
					$this->MP_current_post_id = wp_insert_post( $post_data, $error_obj );
					$this->MP_current_post = get_post((integer)$this->MP_current_post_id, 'OBJECT');
					$this->MP_current_post_permalink = get_permalink((integer)$this->MP_current_post_id);
					
					$terms = array();
					$terms_array = explode(',', $this->MP_terms);
					foreach($terms_array as $singleterm){
						$term = get_term_by('slug', $singleterm, $cat);	
						$terms[]=$term->term_id;
					}
					wp_set_post_terms( $this->MP_current_post_id, $terms, $cat);
					
					if(!empty($this->MP_post_tags)){
						wp_set_post_terms( $this->MP_current_post_id, $this->MP_post_tags,'post_tag');
					}
					
					foreach($this->MP_meta as $meta_key => $meta_value){
						if($meta_key == 'adamlabsgallery-clients-icon' && !empty($meta_value)){
							$attach_id = $this->create_image('demologowhite.png');
							$meta_value = $attach_id;
						}
						if($meta_key == 'adamlabsgallery-clients-icon-dark' && !empty($meta_value)){
							$attach_id = $this->create_image('demologo.png');
							$meta_value = $attach_id;
						}
						update_post_meta($this->MP_current_post_id, $meta_key, $meta_value);
					}
					
					global $imagenr;
					if($imagenr==4) $imagenr = 1;
					$attach_id = $this->create_image('demo_template_'.$imagenr++.'.jpg');
					set_post_thumbnail( $this->MP_current_post_id, $attach_id );
					
					
					return $this->MP_current_post_id;
				}
				else {
					//$this->update();
					$this->errors[] = 'That page already exists. Try updating instead. Control passed to the update() function.';
					return FALSE;
				}
			} 
			else {
				$this->errors[] = 'Title has not been set.';
				return FALSE;
			}
		}
		
		public function create_image($file){
			$image_url = ADAMLABS_GALLERY_PLUGIN_PATH . 'com/admin/assets/images/'.$file;
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents($image_url);
			$filename = basename($image_url);
			if(wp_mkdir_p($upload_dir['path']))
				$file = $upload_dir['path'] . '/' . $filename;
			else
				$file = $upload_dir['basedir'] . '/' . $filename;
			file_put_contents($file, $image_data);
			
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $file, $this->MP_current_post_id );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			return $attach_id;
		}
		
		// SET POST'S TITLE	
		public function set_title($name){
			$this->MP_title = $name;
			return $this->MP_title;
		}
		
		// SET POST'S TYPE	
		public function set_type($type){
			$this->MP_type = $type;
			return $this->MP_type;
		}
		
		// SET POST'S CONTENT	
		public function set_content($content){
			$this->MP_content = $content;
			return $this->MP_content;
		}
		
		// SET POST'S AUTHOR ID	
		public function set_author_id($auth_id){
			$this->MP_auth_id = $auth_id;
			return $this->MP_auth_id;
		}

		
		// SET POST'S STATE	
		public function set_post_state($content){
			$this->MP_status = $content;
			return $this->MP_status;
		}
		
		public function set_post_meta($option_array){
			$this->MP_meta = $option_array;
			return $this->MP_meta;
		}
		
		public function set_date($date){
			$this->MP_date = $date;
			return $this->MP_date;
		}
		
		// SET POST SLUG
		public function set_post_slug($slug){
			$args = array('name' => $slug);
			$posts_query = get_posts( $args );
			if( !get_posts( $args ) && !get_page_by_path( $this->MP_slug ) ) {
				$this->MP_slug = $slug;
				return $this->MP_slug;
			}
			else {
				$this->errors[] = 'Slug already in use.';
				return FALSE;
			}
		}
		
		// SET PAGE TEMPLATE
		public function set_page_template($content){
			if ($this->MP_type == "page") {
				$this->MP_template = $content;
				return $this->MP_template;
			}
			else {
				$this->errors[] = 'You can only use templates for pages.';
				return FALSE;
			}
		}
		
		// SET POST'S TAXONOMY	
		public function set_tax($tax){
			$this->MP_taxonomy = $tax;
			return $this->MP_taxonomy;
		}
		
		public function set_tax_terms($terms){
			$this->MP_terms = $terms;
			return $this->MP_terms;
		}
		
		public function set_post_tags($tags){
			$this->MP_post_tags = $tags;
			return $this->MP_post_tags;
		}
		
		public function import_taxonomies($terms){
			$cat = apply_filters('AdamLabsGallery_category', 'adamlabsgallery_category');
			
			$terms = json_decode($terms,true);	
			//print_r($terms);die;
			foreach($terms as $term){		
				if( !term_exists( $term['name'], $cat ) ){
					wp_insert_term( $term['name'], $cat, array( 'description'	=> $term['category_description'],'slug' => $term['slug'] ) );
				}
			}
		}
		
		// ADD CATEGORY IDs TO THE CATEGORIES ARRAY
		public function add_category($IDs){
			if(is_array($IDs)) {
				foreach ($IDs as $id) {
					if (is_int($id)) {
						$this->MP_category[] = $id;
					} else {
						$this->errors[] = '<b>' .$id . '</b> is not a valid integer input.';
						return FALSE;
					}
				}
			} else {
				$this->errors[] = 'Input specified is not a valid array.';
				return FALSE;
			}
		}
		
		public function prettyprint($content){
			echo "<pre>";
			print_r($content);
			echo "</pre>";
		}
		
		
	}

}

if(!class_exists('AdamLabsPort')) {
	class AdamLabsPort {
		public $MP_pages;
		public $MP_posts;
		public $MP_posts_categories;
		public $MP_tags;
		public $MP_thumbnail;
		public $MP_post_ID;
		public $MP_import_posts;
				
		// Error Array
		public $MP_errors;
		
		public function set_adamlabs_import_posts($json){
			$this->MP_import_posts = $json;
		}
		
		public function import_custom_posts(){
			$cat = apply_filters('AdamLabsGallery_category', 'adamlabsgallery_category');
			$type = apply_filters('AdamLabsGallery_custom_post_type', 'AdamLabsGallery');
			
			$posts_json = $this->MP_import_posts;
			$posts_array = json_decode($this->MP_import_posts,true);
			foreach ($posts_array as $post){
				$newPost = new AdamLabsPost;
				//Standards
					$newPost->set_title( $post["post_title"] );
					$newPost->set_type( $type );
					$newPost->set_content( $post["post_content"] );
					$newPost->set_post_state( "publish" );
				//Categories	
					$newPost->set_tax($cat);
					$newPost->set_tax_terms($post['post_categories']);
				//Tags
					if(!empty($post['post_tags'])) $newPost->set_post_tags($post['post_tags']);	
				//Meta
					$newPost->set_post_meta( $post["post_options"]);
				
					
				$post_id = $newPost->create();
				
			}
		}
		
		
		// Creation functions
		public function export_pages() {
			$pages = get_pages(); 
			foreach ($pages as $page_data) { 
				$this->MP_pages_array[$page_data->ID]['post_title'] = $page_data->post_title;
				$this->MP_pages_array[$page_data->ID]['post_author'] = $page_data->post_author;
				$this->MP_pages_array[$page_data->ID]['post_date'] = $page_data->post_date;
				$this->MP_pages_array[$page_data->ID]['post_excerpt'] = $page_data->post_excerpt;
				$this->MP_pages_array[$page_data->ID]['post_status'] = $page_data->post_status;
				$this->MP_pages_array[$page_data->ID]['post_parent'] = $page_data->post_parent;
				$this->MP_pages_array[$page_data->ID]['post_content'] = apply_filters('the_content', $page_data->post_content);
			}
			$this->MP_pages = json_encode($this->MP_pages_array);
		}
		
		public function export_post_categories(){
			$categories = get_categories();
			foreach($categories as $category) { 
				$this->MP_categories_array[$category->term_id]['name'] = $category->name;
				$this->MP_categories_array[$category->term_id]['slug'] = $category->slug;
				$this->MP_categories_array[$category->term_id]['term_group'] = $category->term_group;
				$this->MP_categories_array[$category->term_id]['term_taxonomy_id'] = $category->term_taxonomy_id;
				$this->MP_categories_array[$category->term_id]['taxonomy'] = $category->taxonomy;
				$this->MP_categories_array[$category->term_id]['description'] = $category->description;
				$this->MP_categories_array[$category->term_id]['parent'] = $category->parent;
				$this->MP_categories_array[$category->term_id]['count'] = $category->count;
				$this->MP_categories_array[$category->term_id]['cat_ID'] = $category->cat_ID;
				$this->MP_categories_array[$category->term_id]['category_count'] = $category->category_count;
				$this->MP_categories_array[$category->term_id]['category_description'] = $category->category_description;
				$this->MP_categories_array[$category->term_id]['cat_name'] = $category->cat_name;
				$this->MP_categories_array[$category->term_id]['category_nicename'] = $category->category_nicename;
				$this->MP_categories_array[$category->term_id]['category_parent'] = $category->category_parent;
			} 
			$this->MP_posts_categories = json_encode($this->MP_categories_array);
		}
		
		public function export_tags(){
			$tags = get_tags();
			foreach($tags as $tag) { 
				$this->MP_tags_array[$tag->term_id]['name'] = $tag->name;
				$this->MP_tags_array[$tag->term_id]['slug'] = $tag->slug;
				$this->MP_tags_array[$tag->term_id]['term_group'] = $tag->term_group;
				$this->MP_tags_array[$tag->term_id]['term_taxonomy_id'] = $tag->term_taxonomy_id;
				$this->MP_tags_array[$tag->term_id]['taxonomy'] = $tag->taxonomy;
				$this->MP_tags_array[$tag->term_id]['description'] = $tag->description;
				$this->MP_tags_array[$tag->term_id]['parent'] = $tag->parent;
			}
			$this->MP_tags = json_encode($this->MP_tags_array);
		}
		
		public function export_custom_posts($custom_post_type){
			$args=array(
				'post_type' => $custom_post_type,
				'posts_per_page' => 99999,
				'suppress_filters' => 0
			);
			$list = get_posts($args);
			foreach ($list as $post_data) :
				$this->MP_posts_array[$post_data->ID]['post_title'] = $post_data->post_title;
				$this->MP_posts_array[$post_data->ID]['post_author'] = $post_data->post_author;
				$this->MP_posts_array[$post_data->ID]['post_date'] = $post_data->post_date;
				$this->MP_posts_array[$post_data->ID]['post_excerpt'] = $post_data->post_excerpt;
				$this->MP_posts_array[$post_data->ID]['post_status'] = $post_data->post_status;
				$this->MP_posts_array[$post_data->ID]['post_parent'] = $post_data->post_parent;
				$this->MP_posts_array[$post_data->ID]['post_content'] = apply_filters('the_content', $post_data->post_content);
				$this->MP_posts_array[$post_data->ID]['post_options'] = $this->all_get_options($post_data->ID);
			endforeach;
			$this->MP_posts = json_encode($this->MP_posts_array);
			//$this->MP_posts = $this->array_to_xml($this->MP_posts_array);
		}
		
		public function all_get_options($id = 0){
			if ($id == 0) :
				global $wp_query;
				$content_array = $wp_query->get_queried_object();
				if(isset($content_array->ID)){
					$id = $content_array->ID;
				}
			endif;   
		
			$first_array = get_post_custom_keys($id);
		
			if(isset($first_array)){
				foreach ($first_array as $key => $value) :
					   $second_array[$value] =  get_post_meta($id, $value, FALSE);
						foreach($second_array as $second_key => $second_value) :
								   $result[$second_key] = $second_value[0];
						endforeach;
				 endforeach;
			 }
			
			if(isset($result)){
				return $result;
			}
		}
		
		public function export_posts() {
			$args=array(
				'posts_per_page' => 99999,
				'suppress_filters' => 0
			);
			$posts = get_posts($args); 
			$counter=1;
			foreach ($posts as $post_data) { 
				if($counter++>30){
					$this->MP_posts_array[$post_data->ID]['post_title'] = $post_data->post_title;
					$this->MP_posts_array[$post_data->ID]['post_author'] = $post_data->post_author;
					$this->MP_posts_array[$post_data->ID]['post_date'] = $post_data->post_date;
					$this->MP_posts_array[$post_data->ID]['post_excerpt'] = $post_data->post_excerpt;
					$this->MP_posts_array[$post_data->ID]['post_status'] = $post_data->post_status;
					$this->MP_posts_array[$post_data->ID]['post_parent'] = $post_data->post_parent;
					$this->MP_posts_array[$post_data->ID]['post_content'] = apply_filters('the_content', $post_data->post_content);
					//Categories
						$categories = get_the_category($post_data->ID);
						$separator = ',';
						$output = '';
						if($categories){
							foreach($categories as $category) {
								$output .= $category->slug . $separator;
							}
							$this->MP_posts_array[$post_data->ID]['post_categories'] = trim($output, $separator);
						}
					//Tags
						$posttags = get_the_tags($post_data->ID);
						$count=0;
						$output = '';
						if ($posttags) {
						  foreach($posttags as $tag) {
							  $output .= $tag->slug . $separator;
						  }
						  $this->MP_posts_array[$post_data->ID]['post_tags'] = trim($output, $separator);
						}
					//Options
						$this->MP_posts_array[$post_data->ID]['post_options'] = $this->all_get_options($post_data->ID);
				}
			}
			$this->MP_posts = json_encode($this->MP_posts_array);
		}
		
		public function save_export(){
			
		}
		
		public function pretty_print($content){
			echo "<pre><code>";
			print_r($content);
			echo "</code></pre>";
			echo "<hr>";
		}
		
		
	}
}
?>