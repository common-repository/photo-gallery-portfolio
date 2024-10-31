<?php

if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Library {
	private $library_list		 = 'adamlabsgallery/get-list.php';
	private $library_dl			 = 'adamlabsgallery/download.php';
	private $library_server_path = '/adamlabsgallery/images/';
	private $library_path		 = '/adamlabsgallery/templates/';
	private $library_path_plugin = 'com/admin/assets/imports/';
	private $curl_check			 = null;
	
	const SHOP_VERSION			 = '1.0.0';
	
	
	/**
	 * Check if Curl can be used
	 */
	public function check_curl_connection(){
		
		if($this->curl_check !== null) return $this->curl_check;
		
		$curl = new WP_Http_Curl();
		
		$this->curl_check = $curl->test();
		
		return $this->curl_check;
	}

	// read the json import file
    public function import_custom_images($json,$path){
		//search for the layers part
		$grids = $json["grids"];
		$new_grids = array();
		foreach($grids as $grid){
			$layers = json_decode($grid["layers"]);
			//find the image ids
			$new_layers = array();
			foreach ($layers as $layer){
				$layer = json_decode($layer);
					if( isset($layer->{'custom-type'}) &&  $layer->{'custom-type'}=="image"){
						$custom_image = $path.$layer->{'custom-image'}.".jpg";
						//import the image and replace the id
						$layer->{'custom-image'} = "".$this->create_image($custom_image);
						if(!empty($layer->{'adamlabsgallery-alternate-image'})){
							$alternate_image = $path.$layer->{'adamlabsgallery-alternate-image'}.".jpg";
							//import the image and replace the id
							$layer->{'adamlabsgallery-alternate-image'} = "".$this->create_image($alternate_image);
						}
					}
				$new_layers[] = json_encode($layer);
			}
			$grid["layers"] = json_encode($new_layers) ;
			$new_grids[] = $grid; 
		}
		
		$json["grids"] = $new_grids;
		
        return $json;
    }

    public function create_image($file){
        if(empty($file)) return false;
        $upload_dir = wp_upload_dir();
        $image_url = $file;
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
		
        $attach_id = wp_insert_attachment( $attachment, $file );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
	   
		return $attach_id;
    }
}
?>