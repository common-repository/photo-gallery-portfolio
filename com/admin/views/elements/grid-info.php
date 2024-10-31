<?php
 
if( !defined( 'ABSPATH') ) exit();

$dir = plugin_dir_path(__FILE__).'../../../';

$validated = get_option('adamlabsgallery_valid', 'false');
$code = get_option('adamlabsgallery_code', '');
$latest_version = get_option('adamlabsgallery_latest-version', AdamLabsGallery::VERSION);
if(version_compare($latest_version, AdamLabsGallery::VERSION, '>')){
	//new version exists
}else{
	//up to date
}
?>

<!-- 
THE INFO ABOUT EMBEDING OF THE SLIDER 			
-->
