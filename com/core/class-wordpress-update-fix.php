<?php

if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_WordPress_Fix {
	
	public function __construct(){
		
		//Since WP 4.2, terms may split and receive new IDs if you update them, if they have the same handle on two or more Custom Post Types or tag/categorie
		add_action( 'split_shared_term', array('AdamLabsGallery_WordPress_Fix', 'split_terms_fix'), 10, 4 );
		
	}
	
	/**
	 * Search all Grids and change the term IDs set in the selected terms if needed
	 **/
	static function split_terms_fix( $old_term_id, $new_term_id, $term_taxonomy_id, $taxonomy ){
		
		$r = apply_filters('adamlabsgallery_split_terms_fix', array('old_term_id' => $old_term_id, 'new_term_id' => $new_term_id, 'term_taxonomy_id' => $term_taxonomy_id, 'taxonomy' => $taxonomy));
		
		$base = new AdamLabsGallery_Base();
		
		$lang = array();
		
		if(AdamLabsGallery_Wpml::is_wpml_exists()){
			$lang = icl_get_languages();
		}
		
		$grids = AdamLabsGallery::get_adamlabsgallery_grids();
		if(!empty($grids)){
			foreach($grids as $grid){
				
				$selected = json_decode($grid->postparams, true);
				$post_category = $base->getVar($selected, 'post_category');
				
				$cat_tax = $base->getCatAndTaxData($post_category);
				
				$cats = array();
				if(!empty($cat_tax['cats']))
					$cats = explode(',', $cat_tax['cats']);
					
				$taxes = array('post_tag');
				if(!empty($cat_tax['tax']))
					$taxes = explode(',', $cat_tax['tax']);
				
				$cont = false;
				if(!empty($cats)){
					foreach($cats as $cat){
						
						if($r['old_term_id'] == $cat && in_array($r['taxonomy'], $taxes)){ //ID needs to be changed
						
							foreach($taxes as $t){ //replace all occuring old term id with the new term id and then Save the Grid
								$post_category = str_replace($t.'_'.$r['old_term_id'], $t.'_'.$r['new_term_id'], $post_category);
							}
							
							$selected['post_category'] = $post_category;
							$grid->postparams = $selected;
							$grid->params = json_decode($grid->params, true);
							$grid->layers = json_decode($grid->layers, true);
							
							$new_grid = (array) $grid; //cast to array as update_create_grid expects an array
							
							AdamLabsGallery_Admin::update_create_grid($new_grid);
							
							//now delete cache of the Grid so that changes take effect immediately
							if(!empty($lang)){
								foreach($lang as $code => $val){
									delete_transient( 'adamlabsgallery_trans_query_'.$grid->id.$val['language_code'] );
									delete_transient( 'adamlabsgallery_trans_full_grid_'.$grid->id.$val['language_code'] );
								}
							}else{
								delete_transient( 'adamlabsgallery_trans_query_'.$grid->id );
								delete_transient( 'adamlabsgallery_trans_full_grid_'.$grid->id );
							}
							$cont = true;
						}
						
						if($cont == true) break;
					}
				}					
			}
		}
		
	}
	
}

$adamlabsgallery_wp_fix = new AdamLabsGallery_WordPress_Fix();

?>