<?php
 
if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Global_Css {
	
	/**
	 * Get global CSS
	 */
	public static function get_global_css_styles(){
	
		$custom_css = stripslashes(get_option('adamlabsgallery_custom_css', ''));
		
		return apply_filters('adamlabsgallery_get_global_css_styles', $custom_css);
		
	}
	
	
	/**
	 * Update global CSS
	 */
	public static function set_global_css_styles($css_styles){
	
		$custom_css = update_option('adamlabsgallery_custom_css', apply_filters('adamlabsgallery_set_global_css_styles', stripslashes($css_styles)));
		
	}
	
	
	/**
	 * echo global CSS with wrapper
	 */
	public static function output_global_css_styles_wrapped(){
		$base = new AdamLabsGallery_Base();
		
		$styles = '<style type="text/css">';
		$styles .= $base->compress_css(self::get_global_css_styles());
		$styles .= '</style>'."\n";
		
		echo apply_filters('adamlabsgallery_output_global_css_styles_wrapped', $styles);
		
	}
	
	
	/**
	 * insert default CSS into option
	 */
	public static function propagate_default_global_css($networkwide = false){
		
		$css = self::get_default_global_css();
		
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
			global $wpdb;
			
			// 2.2.5
			// $old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
				
				switch_to_blog($blog_id);
				
				if(get_option('adamlabsgallery_custom_css_imported', 'false') == 'true') continue;
				
				update_option('adamlabsgallery_custom_css_imported', 'true');
				
				self::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css_multisite', $css, $blog_id));
				
				// 2.2.5
				restore_current_blog();
				
            }
			
			// 2.2.5
            // switch_to_blog($old_blog); //go back to correct blog
			
		}else{
			if(get_option('adamlabsgallery_custom_css_imported', 'false') == 'false'){
			
				update_option('adamlabsgallery_custom_css_imported', 'true');
				
				self::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css', $css));
				
			}
		}
		
	}
	
	
	/**
	 * get default global CSS
	 */
	public static function get_default_global_css(){
		
		$global_css = "/* HENRY HARRISON */

a.adamlabsgallery-henryharrison-element-1,
a.adamlabsgallery-henryharrison-element-2 {
  -webkit-transition: all .4s linear;  
    -moz-transition: all .4s linear;  
    -o-transition: all .4s linear;  
    -ms-transition: all .4s linear;  
    transition: all .4s linear; 
}

/* JIMMY CARTER */
.adamlabsgallery-jimmy-carter-element-11 i:before { margin-left:0px; margin-right:0px;}


/* HARDING */
.adamlabsgallery-harding-element-17 { letter-spacing:1px}
.adamlabsgallery-harding-wrapper .adamlabsgallery-entry-media { overflow:hidden; 
            box-sizing:border-box;
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            padding:30px 30px 0px 30px;
}

.adamlabsgallery-harding-wrapper .adamlabsgallery-media-poster { overflow:hidden; 
            border-radius:50%;
            -webkit-border-radius:50%;
            -moz-border-radius:50%;
}

/*ULYSSES S GRANT */
.adamlabsgallery-ulysses-s-grant-wrapper .adamlabsgallery-entry-media { overflow:hidden; 
            box-sizing:border-box;
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            padding:30px 30px 0px 30px;
}

.adamlabsgallery-ulysses-s-grant-wrapper .adamlabsgallery-media-poster { overflow:hidden; 
            border-radius:50%;
            -webkit-border-radius:50%;
            -moz-border-radius:50%;
}

/*RICHARD NIXON */
.adamlabsgallery-richard-nixon-wrapper .adamlabsgallery-entry-media { overflow:hidden; 
            box-sizing:border-box;
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            padding:30px 30px 0px 30px;
}

.adamlabsgallery-richard-nixon-wrapper .adamlabsgallery-media-poster { overflow:hidden; 
            border-radius:50%;
            -webkit-border-radius:50%;
            -moz-border-radius:50%;
}


/* HERBERT HOOVER */
.adamlabsgallery-herbert-hoover-wrapper .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale\"); /* Firefox 10+, Firefox on Android */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
}

.adamlabsgallery-herbert-hoover-wrapper:hover .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0'/></filter></svg>#grayscale\");
    -webkit-filter: grayscale(0%);}


/* JOOHNSON */
.adamlabsgallery-lyndon-johnson-wrapper .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale\"); /* Firefox 10+, Firefox on Android */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
}

.adamlabsgallery-lyndon-johnson-wrapper:hover .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0'/></filter></svg>#grayscale\");
    -webkit-filter: grayscale(0%);}


/*RONALD REAGAN*/
.adamlabsgallery-overlay.adamlabsgallery-ronald-reagan-container {background: -moz-linear-gradient(top, rgba(0,0,0,0) 50%, rgba(0,0,0,0.83) 99%, rgba(0,0,0,0.85) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(99%,rgba(0,0,0,0.83)), color-stop(100%,rgba(0,0,0,0.85))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#d9000000',GradientType=0 ); /* IE6-9 */}

/*GEORGE BUSH*/
.adamlabsgallery-georgebush-wrapper .adamlabsgallery-entry-cover {background: -moz-linear-gradient(top, rgba(0,0,0,0) 50%, rgba(0,0,0,0.83) 99%, rgba(0,0,0,0.85) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(99%,rgba(0,0,0,0.83)), color-stop(100%,rgba(0,0,0,0.85))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#d9000000',GradientType=0 ); /* IE6-9 */}

/*GEORGE BUSH
.adamlabsgallery-georgebush-wrapper .adamlabsgallery-entry-cover { background: rgba(0,0,0,0.5);}*/

/*JEFFERSON*/
.adamlabsgallery-jefferson-wrapper { -webkit-border-radius: 5px !important; -moz-border-radius: 5px !important; border-radius: 5px !important; -webkit-mask-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA5JREFUeNpiYGBgAAgwAAAEAAGbA+oJAAAAAElFTkSuQmCC) !important; }

/*MONROE*/
.adamlabsgallery-monroe-element-1 { text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1); }

/*LYNDON JOHNSON*/
.adamlabsgallery-lyndon-johnson-wrapper .adamlabsgallery-entry-cover { background: -moz-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%, rgba(18,18,18,0) 96%, rgba(19,19,19,0) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(0,0,0,0.35)), color-stop(96%,rgba(18,18,18,0)), color-stop(100%,rgba(19,19,19,0))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* IE10+ */
background: radial-gradient(ellipse at center,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#59000000', endColorstr='#00131313',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
 }

/*WILBERT*/
.adamlabsgallery-wilbert-wrapper .adamlabsgallery-entry-cover { background: -moz-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%, rgba(18,18,18,0) 96%, rgba(19,19,19,0) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(0,0,0,0.35)), color-stop(96%,rgba(18,18,18,0)), color-stop(100%,rgba(19,19,19,0))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* IE10+ */
background: radial-gradient(ellipse at center,  rgba(0,0,0,0.35) 0%,rgba(18,18,18,0) 96%,rgba(19,19,19,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#59000000', endColorstr='#00131313',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
 }
.adamlabsgallery-wilbert-wrapper .adamlabsgallery-media-poster{
  -webkit-transition: 0.4s ease-in-out;
    -moz-transition:  0.4s ease-in-out;
    -o-transition:  0.4s ease-in-out;
    transition:  0.4s ease-in-out;
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale\"); /* Firefox 10+, Firefox on Android */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
}

.adamlabsgallery-wilbert-wrapper:hover .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0'/></filter></svg>#grayscale\");
    -webkit-filter: grayscale(0%);}

/*PHILLIE*/
.adamlabsgallery-phillie-element-3:after { 
content:\" \";
width: 0px;
height: 0px;
border-style: solid;
border-width: 5px 5px 0 5px;
border-color: #000 transparent transparent transparent;
left:50%;
margin-left:-5px; bottom:-5px; position:absolute; }

/*HOWARD TAFT*/
.adamlabsgallery-howardtaft-wrapper .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0'/></filter></svg>#grayscale\");
    -webkit-filter: grayscale(0%);
}

.adamlabsgallery-howardtaft-wrapper:hover .adamlabsgallery-media-poster{
    filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale\"); /* Firefox 10+, Firefox on Android */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
}

/* WOOCOMMERCE */
.myportfolio-container .added_to_cart.wc-forward { font-family: \"Open Sans\"; font-size: 13px; color: #fff; margin-top: 10px; }

/* LIGHTBOX */
.adamlabsgallerybox-title.adamlabsgallerybox-title-outside-wrap { font-size: 15px; font-weight: 700; text-align: center; }
.adamlabsgallerybox-title.adamlabsgallerybox-title-inside-wrap { padding-bottom: 10px; font-size: 15px; font-weight: 700; text-align: center; }";
		
		return apply_filters('adamlabsgallery_get_default_global_css', $global_css);
	}
	
	
}

?>