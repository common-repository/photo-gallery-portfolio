<?php

/**
 * Social Gallery WordPress Photo Viewer Plugin Extension
 **/
 
if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Social_Gallery {
	
	/**
	 * Check if Social Gallery is activated and at least version x.x is installed
	 **/
	public static function sg_exists(){
		
		if(!apply_filters( 'socialgallery-installed', false)){
			$exists = false;
		}else{
			$exists = true;
		}
		
		return apply_filters('adamlabsgallery_sg_exists', $exists);
	}
	
	
	/**
	 * Check if Social Gallery is active in the Portfolio Gallery Global options
	 **/
	public static function is_active(){
		$active = false;
		
		if(self::sg_exists()){
			$opt = get_option('adamlabsgallery_use_lightbox', 'false');
			if($opt === 'sg') $active = true;
		}
		
		return apply_filters('adamlabsgallery_is_active', $active);
	}
	
}
	
?>