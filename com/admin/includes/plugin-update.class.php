<?php
/**
 * Plugin Update Class For Portfolio Gallery
 * Enables automatic updates on the Plugin
 */
 
if( !defined( 'ABSPATH') ) exit();

class AdamLabsGallery_Plugin_Update {
	
	private $version;
	
	public function __construct($version) {
		
		$this->set_version($version);
		
	}
	
	
	/**
	 * update the version
	 */
	public function update_version($new_version){
	
		update_option("adamlabsgallery_grids_version", $new_version);
		
	}
	
	
	/**
	 * set the version in class
	 */
	public function set_version($new_version){
	
		$this->version = $new_version;
		
	}
	
	/**
	 * update routine, do updates depending on what version we currently are
	 */
	public function do_update_process(){
		
		// $this->update_version('0.5.0');
		// $this->set_version('0.5.0');

		if(version_compare($this->version, '0.1.0', '<=')){
			$this->update_to_010();
		}

		if(version_compare($this->version, '0.2.0', '<')){
			$this->update_to_02();
		}

		if(version_compare($this->version, '0.2.1', '<')){
			$this->update_to_021();
		}

		if(version_compare($this->version, '0.3.0', '<')){
			$this->update_to_030();
		}

		/* 2.1.5 */
		if(version_compare($this->version, '0.4.0', '<')){
			$this->update_to_040();
		}

		/* 0.5.0 */
		if(version_compare($this->version, '0.5.0', '<')){
			$this->update_to_050();
		}

		/* 0.6.0 */
		if(version_compare($this->version, '0.6.0', '<')){
			$this->update_to_060();
		}

		/* 0.7.0 */
		if(version_compare($this->version, '0.7.0', '<')){
			$this->update_to_070();
		}

		do_action('adamlabsgallery_do_update_process', $this->version);
	}


	/**
	 * update to 0.1.0
	 * @since: 0.1.0
	 * @does: adds navigation skins to support dropdowns
	 */
	public function update_to_010(){

		//update navigation skins to support dropdowns
		$nav = new AdamLabsGallery_Navigation();

		$navigation_skins = array(
			array('handle' => 'flat-light','css' => '/* FLAT LIGHT SKIN DROP DOWN 1.1.0 */
.flat-light .adamlabsgallery-filterbutton 								{ 	color:#000;color:rgba(0,0,0,0.5);}

.flat-light	.adamlabsgallery-selected-filterbutton						{	background:#fff; padding:10px 20px 10px 30px; color:#000; border-radius: 4px;font-weight:700;}

.flat-light .adamlabsgallery-cartbutton,
.flat-light .adamlabsgallery-cartbutton a,
.flat-light .adamlabsgallery-cartbutton a:visited,
.flat-light .adamlabsgallery-cartbutton a:hover,
.flat-light .adamlabsgallery-cartbutton i,
.flat-light .adamlabsgallery-cartbutton i.before								{	font-weight:700; color:#000; }
.flat-light .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open	{	 margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top;}

.flat-light .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.flat-light .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open	{	 color:rgba(0,0,0,1); }

.flat-light .adamlabsgallery-dropdown-wrapper							{	border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.flat-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:700; text-align: left}
.flat-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.flat-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'flat-dark','css' => '/* FLAT DARK SKIN DROP DOWN 1.1.0 */
.flat-dark .adamlabsgallery-filterbutton 								{ 	color:#fff !important}

.flat-dark .adamlabsgallery-selected-filterbutton						{	background: #3A3A3A; background: rgba(0, 0, 0, 0.2); padding:10px 20px 10px 30px; color:#fff; border-radius: 4px;font-weight:600; }

.flat-dark .adamlabsgallery-cartbutton,
.flat-dark .adamlabsgallery-cartbutton a,
.flat-dark .adamlabsgallery-cartbutton a:visited,
.flat-dark .adamlabsgallery-cartbutton a:hover,
.flat-dark .adamlabsgallery-cartbutton i,
.flat-dark .adamlabsgallery-cartbutton i.before						{	font-weight:600; color:#fff; }
.flat-dark .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top;}

.flat-dark .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.flat-dark .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open		{	 color:rgba(255,255,255,1); }
.flat-dark .adamlabsgallery-cartbutton:hover,
.flat-dark .adamlabsgallery-selected-filterbutton:hover, 
.flat-dark .adamlabsgallery-selected-filterbutton.hoveredfilter		{	background: rgba(0, 0, 0, 0.5); }

.flat-dark .adamlabsgallery-dropdown-wrapper							{	background:#222; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.flat-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#fff; color:rgba(255,255,255,0.5) !important;}
.flat-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.flat-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	background:transparent !important; color:#fff; color:rgba(255,255,255,1) !important;}
.flat-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.flat-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'minimal-dark','css' => '/* MINIMAL DARK SKIN DROP DOWN 1.1.0 */
.minimal-dark .adamlabsgallery-filterbutton 								{ 	color:#fff !important}

.minimal-dark .adamlabsgallery-selected-filterbutton						{	background: transparent; border: 1px solid rgba(255, 255, 255, 0.1);background: rgba(0, 0, 0, 0); padding:10px 20px 10px 30px; color:#fff; border-radius: 4px;font-weight:600;}

.minimal-dark .adamlabsgallery-cartbutton									{	border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius:5px !important; -moz-border-radius:5px !important;-webkit-border-radius:5px !important;}
.minimal-dark .adamlabsgallery-cartbutton,
.minimal-dark .adamlabsgallery-cartbutton a,
.minimal-dark .adamlabsgallery-cartbutton a:visited,
.minimal-dark .adamlabsgallery-cartbutton a:hover,
.minimal-dark .adamlabsgallery-cartbutton i,
.minimal-dark .adamlabsgallery-cartbutton i.before						{	font-weight:600; color:#fff; }
.minimal-dark .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top; color:#fff;}

.minimal-dark .adamlabsgallery-cartbutton:hover,
.minimal-dark .adamlabsgallery-selected-filterbutton:hover, 
.minimal-dark .adamlabsgallery-selected-filterbutton.hoveredfilter		{	border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); }

.minimal-dark .adamlabsgallery-dropdown-wrapper								{	background:#333; background:rgba(0,0,0,0.95);border: 1px solid rgba(255, 255, 255, 0.1);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.minimal-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	border:none !important; box-shadow:none !important; background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#fff; color:rgba(255,255,255,0.5) !important;}
.minimal-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.minimal-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	background:transparent !important; color:#fff; color:rgba(255,255,255,1) !important; }
.minimal-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid rgba(255, 255, 255, 0.2)}
.minimal-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'minimal-light','css' => '/* MINIMAL LIGHT SKIN DROP DOWN 1.1.0 */
.minimal-light .adamlabsgallery-filterbutton 								{ 	color:#999 !important}

.minimal-light .adamlabsgallery-selected-filterbutton						{	 border: 1px solid #E5E5E5;background: #fff; padding:10px 20px 10px 30px; color:#999; border-radius: 4px;font-weight:700;  }

.minimal-light .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top; color:#999;}

.minimal-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton span i 			{ color: #fff !important;  }
.minimal-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span, 
.minimal-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span		{ color: #000 !important;  }
.minimal-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span i, 
.minimal-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span i		{ color: #fff !important;  }

.minimal-light .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.minimal-light .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open		{	 color:rgba(0,0,0,1) !important; }
.minimal-light .adamlabsgallery-cartbutton:hover, 							
.minimal-light .adamlabsgallery-selected-filterbutton:hover, 
.minimal-light .adamlabsgallery-selected-filterbutton.hoveredfilter		{	border-color: #bbb; color: #333; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.minimal-light .adamlabsgallery-dropdown-wrapper							{	background:#fff; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13);}
.minimal-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	border:none !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:700; text-align: left; color:#999; }
.minimal-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.minimal-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	background:transparent !important; color:#000 !important; box-shadow: none !important}
.minimal-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.minimal-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'simple-light','css' => '/* SIMPLE LIGHT SKIN DROP DOWN 1.1.0 */
.simple-light .adamlabsgallery-filterbutton 								{ 	color:#999 !important}

.simple-light .adamlabsgallery-selected-filterbutton						{	 border: 1px solid #E5E5E5;background: #eee; padding:5px 5px 5px 10px; color:#000; font-weight:400;}

.simple-light .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color:#000;}

.simple-light .adamlabsgallery-cartbutton:hover,
.simple-light .adamlabsgallery-selected-filterbutton:hover, 
.simple-light .adamlabsgallery-selected-filterbutton.hoveredfilter		{	background-color: #fff; border-color: #bbb; color: #333; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.simple-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton span		{ color: #000;  }
.simple-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span, 
.simple-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span		{ color: #000 !important;  }
.simple-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span i, 
.simple-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span i		{ color: #fff !important;  }

.simple-light .adamlabsgallery-dropdown-wrapper								{	background:#fff; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13);}
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:400; text-align: left; }
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton span { color:#777; }
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	color:#000 !important; box-shadow: none !important}
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'simple-dark','css' => '/* SIMPLE DARK SKIN DROP DOWN */
.simple-dark .adamlabsgallery-filterbutton 									{ 	color:#fff !important}

.simple-dark .adamlabsgallery-selected-filterbutton							{	 border: 1px solid rgba(255, 255, 255, 0.15);background:rgba(255, 255, 255, 0.08);padding:5px 5px 5px 10px; color:#fff; font-weight:600;}

.simple-dark .adamlabsgallery-cartbutton									{	border: 1px solid rgba(255, 255, 255, 0.1) !important; }
.simple-dark .adamlabsgallery-cartbutton,
.simple-dark .adamlabsgallery-cartbutton a,
.simple-dark .adamlabsgallery-cartbutton a:visited,
.simple-dark .adamlabsgallery-cartbutton i,
.simple-dark .adamlabsgallery-cartbutton i.before						{	font-weight:600; color:#fff; }

.simple-dark .adamlabsgallery-cartbutton:hover a, 
.simple-dark .adamlabsgallery-cartbutton:hover i 							{ color: #000; }

.simple-dark .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.simple-dark .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open		{	 color:#000; }
.simple-dark .adamlabsgallery-cartbutton:hover, 							
.simple-dark .adamlabsgallery-selected-filterbutton:hover, 
.simple-dark .adamlabsgallery-selected-filterbutton.hoveredfilter			{	border-color: #fff; color: #000; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); background: #fff; }

.simple-dark .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color:#fff;}

.simple-dark .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span, 
.simple-dark .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span		{ color: #000 !important;  }

.simple-dark .adamlabsgallery-dropdown-wrapper								{	background:#fff; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.simple-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton			{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#777 !important; }
.simple-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton span { color:#777; }
.simple-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.simple-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	color:#000 !important; box-shadow: none !important; font-weight: 600;}
.simple-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #444;}
.simple-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span		{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'text-dark','css' => '/* TEXT DARK SKIN DROP DOWN 1.1.0 */
.text-dark .adamlabsgallery-filterbutton 									{ 	color: #FFF;color: rgba(255, 255, 255, 0.4) !important}
	
.text-dark .adamlabsgallery-selected-filterbutton							{	padding:5px 5px 5px 10px; color: #FFF;color: rgba(255, 255, 255, 0.4);  font-weight:600;}

.text-dark .adamlabsgallery-cartbutton										{	 }
.text-dark .adamlabsgallery-cartbutton,
.text-dark .adamlabsgallery-cartbutton a,
.text-dark .adamlabsgallery-cartbutton a:visited,
.text-dark .adamlabsgallery-cartbutton a:hover,
.text-dark .adamlabsgallery-cartbutton i,
.text-dark .adamlabsgallery-cartbutton i.before							{	font-weight:600; color: #FFF; color: rgba(255, 255, 255, 0.4); }

.text-dark .adamlabsgallery-cartbutton:hover a, 
.text-dark .adamlabsgallery-cartbutton:hover i 								{ color: rgba(255, 255, 255, 1); }

.text-dark .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.text-dark .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open		{	 color: rgba(255, 255, 255, 1); }
.text-dark .adamlabsgallery-cartbutton:hover, 							
.text-dark .adamlabsgallery-selected-filterbutton:hover, 
.text-dark .adamlabsgallery-selected-filterbutton.hoveredfilter				{	color: rgba(255, 255, 255, 1); }

.text-dark .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color: #FFF;color: rgba(255, 255, 255, 0.4); }

.text-dark .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span, 
.text-dark .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span	{ color: rgba(255, 255, 255, 1);  }

.text-dark .adamlabsgallery-dropdown-wrapper								{	border: 1px solid rgba(0, 0, 0, 0.15); background:#000; background:rgba(0, 0, 0, 0.95); }
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton				{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#999 !important; }
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton span  		{   text-decoration: none !important; }
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected		{	color:#fff !important; box-shadow: none !important; }
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #444;}
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected .adamlabsgallery-filter-checked,
.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover .adamlabsgallery-filter-checked	{	color:#fff;}

.text-dark .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span		{	vertical-align: middle; line-height:20px; color:#000;}'),
			array('handle' => 'text-light','css' => '/* TEXT LIGHT SKIN DROP DOWN 1.1.0 */
.text-light .adamlabsgallery-filterbutton 									{ 	color: #999}

.text-light .adamlabsgallery-selected-filterbutton							{	padding:5px 5px 5px 10px; color: #999; font-weight:600;}

.text-light .adamlabsgallery-cartbutton										{	 }
.text-light .adamlabsgallery-cartbutton,
.text-light .adamlabsgallery-cartbutton a,
.text-light .adamlabsgallery-cartbutton a:visited,
.text-light .adamlabsgallery-cartbutton a:hover,
.text-light .adamlabsgallery-cartbutton i,
.text-light .adamlabsgallery-cartbutton i.before							{	font-weight:600; color: #999; }

.text-light .adamlabsgallery-cartbutton:hover a, 
.text-light .adamlabsgallery-cartbutton:hover i 							{ color: #444; }

.text-light .adamlabsgallery-selected-filterbutton:hover .adamlabsgallery-icon-down-open,
.text-light .adamlabsgallery-selected-filterbutton.hoveredfilter .adamlabsgallery-icon-down-open		{	 color: #444; }
.text-light .adamlabsgallery-cartbutton:hover, 							
.text-light .adamlabsgallery-selected-filterbutton:hover, 
.text-light .adamlabsgallery-selected-filterbutton.hoveredfilter			{	color: #444; }

.text-light .adamlabsgallery-selected-filterbutton .adamlabsgallery-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color: #999; }

.text-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton:hover span, 
.text-light .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected span	{ text-decoration: none !important; }

.text-light .adamlabsgallery-dropdown-wrapper								{	border: 1px solid rgba(255, 255, 255, 0.15); background:#fff; background:rgba(255, 255, 255, 0.95); }
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton				{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#999 !important; }
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton span  		{   text-decoration: none !important; }
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover,
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected	{	color:#000 !important; box-shadow: none !important; }
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #ddd;}
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton.selected .adamlabsgallery-filter-checked,
.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filterbutton:hover .adamlabsgallery-filter-checked	{	color:#000;}

.text-light .adamlabsgallery-dropdown-wrapper .adamlabsgallery-filter-checked span		{	vertical-align: middle; line-height:20px; color:#000;}')
		);

		foreach($navigation_skins as $skin){
			$old_skin = $nav->get_adamlabsgallery_navigation_skin_by_handle($skin['handle']);

			if($old_skin !== false){
				$old_skin['css'] .= "\n\n\n".$skin['css'];

				//modify variables to meet requirement for update function
				$old_skin['skin_css'] = $old_skin['css'];
				$old_skin['sid'] = $old_skin['id'];
				unset($old_skin['name']);
				unset($old_skin['css']);
				unset($old_skin['id']);

				$nav->update_create_navigation_skin_css($old_skin);

			}

		}

		$this->update_version('0.1.0');
		$this->set_version('0.1.0');

	}


	/**
	 * update to 0.2
	 * @since: 0.2
	 * @does: adds navigation skins to support search
	 */
	public function update_to_02(){

		//update navigation skins to support search
		$nav = new AdamLabsGallery_Navigation();

		$navigation_skins = array(
			array('handle' => 'flat-light','css' => '/* FLAT LIGHT SEARCH 2.0 */
.flat-light input.adamlabsgallery-search-input[type="text"]{	background: #FFF !important;padding: 0px 15px !important;
												color: #000 !important;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;line-height: 40px !important;border: none !important;box-shadow: none !important;
												font-size: 12px !important;text-transform: uppercase;font-weight: 700;
											}
.flat-light input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#000 !important}
.flat-light input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#000 !important}
.flat-light input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#000 !important}
.flat-light input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder	{ color:#000 !important}
.flat-light .adamlabsgallery-search-submit,
.flat-light .adamlabsgallery-search-clean  { background:#fff; color:#999; width:40px;height:40px; text-align: center; vertical-align: top; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:5px;}
.flat-light .adamlabsgallery-search-submit:hover,
.flat-light .adamlabsgallery-search-clean:hover { color:#000;}'),
			array('handle' => 'flat-dark','css' => '/* FLAT DARK SEARCH 2.0 */
.flat-dark input.adamlabsgallery-search-input[type="text"]{	background: #3A3A3A !important; background: rgba(0, 0, 0, 0.2) !important;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;line-height: 40px !important;border: none !important;box-shadow: none !important;
												font-size: 12px !important;text-transform: uppercase;
												padding: 0px 15px !important;color: #fff !important;
											}
.flat-dark input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#fff !important}
.flat-dark input.adamlabsgallery-search-input[type="text"]:-moz-placeholder {	color:#fff !important}
.flat-dark input.adamlabsgallery-search-input[type="text"]::-moz-placeholder {	color:#fff !important}
.flat-dark input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder {	color:#fff !important}

.flat-dark input.adamlabsgallery-search-input[type="text"]:hover,
.flat-dark input.adamlabsgallery-search-input[type="text"]:focus { background: #4A4A4A !important;background: rgba(0, 0, 0, 0.5) !important;}
.flat-dark .adamlabsgallery-search-submit,
.flat-dark .adamlabsgallery-search-clean	{	background: #3A3A3A !important; background: rgba(0, 0, 0, 0.2) !important;
								color:#fff; width:40px;height:40px; text-align: center; vertical-align: top; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:5px;
							}
.flat-dark .adamlabsgallery-search-submit:hover,
.flat-dark .adamlabsgallery-search-clean:hover { background: #4A4A4A !important;background: rgba(0, 0, 0, 0.5) !important;color:#fff;}'),
			array('handle' => 'minimal-dark','css' => '/* MINIMAL DARK SEARCH 2.0 */
.minimal-dark input.adamlabsgallery-search-input[type="text"] { background: transparent !important; background: rgba(0, 0, 0, 0) !important;
													padding: 0px 15px !important;color: #fff !important;line-height: 38px !important;
													border-radius: 5px 0px 0px 5px;-moz-border-radius: 5px 0px 0px 5px;-webkit-border-radius: 5px 0px 0px 5px;														
													border:1px solid #fff !important;border:1px solid rgba(255,255,255,0.1) !important;
													border-right: none !important;box-shadow: none !important;
													font-size: 12px !important;font-weight: 600;
												}
												
.minimal-dark input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#fff !important}
.minimal-dark input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#fff !important}
.minimal-dark input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#fff !important}
.minimal-dark input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#fff !important}

.minimal-dark input.adamlabsgallery-search-input[type="text"]:hover,
.minimal-dark input.adamlabsgallery-search-input[type="text"]:focus { background: transparent !important;background: rgba(255, 255, 255, 0.1) !important;border-color: rgba(255, 255, 255, 0.2) !important;box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.13) !important;}
.minimal-dark .adamlabsgallery-search-submit,
.minimal-dark .adamlabsgallery-search-clean { background: transparent !important; background: rgba(0, 0, 0, 0) !important;color:#fff; width:40px;height:40px; text-align: center; vertical-align: top; 
								border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:0px;
								border:1px solid #fff !important;border:1px solid rgba(255,255,255,0.1) !important;
							}
.minimal-dark .adamlabsgallery-search-submit { border-left:none !important; border-right:none !important; border-radius:0;-webkit-border-radius:0;-moz-border-radius:0;}
.minimal-dark .adamlabsgallery-search-clean { border-left:none !important;  border-radius:0px 5px 5px 0px; -webkit-border-radius:0px 5px 5px 0px; -moz-border-radius:0px 5px 5px 0px}
.minimal-dark .adamlabsgallery-search-submit:hover,
.minimal-dark .adamlabsgallery-search-clean:hover { background: transparent !important;background: rgba(255, 255, 255, 0.1) !important;border-color: rgba(255, 255, 255, 0.2) !important;box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.13) !important;}'),
			array('handle' => 'minimal-light','css' => '/* MINIMAL LIGHT SEARCH 2.0 */
.minimal-light input.adamlabsgallery-search-input[type="text"] {	background: #fff !important;
													padding: 0px 15px !important;color: #999 !important;line-height: 38px !important;
													border-radius: 5px 0px 0px 5px;-moz-border-radius: 5px 0px 0px 5px;-webkit-border-radius: 5px 0px 0px 5px;
													border:1px solid #E5E5E5 !important;
													border-right: none !important;box-shadow: none !important;
													font-size: 12px !important;font-weight: 600;
												}
												
.minimal-light input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#999 !important}
.minimal-light input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#999 !important}
.minimal-light input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#999 !important}
.minimal-light input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#999 !important}

.minimal-light input.adamlabsgallery-search-input[type="text"]:hover,
.minimal-light input.adamlabsgallery-search-input[type="text"]:focus { background: #fff !important;border-color: #bbb !important;box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.13) !important;}
.minimal-light .adamlabsgallery-search-submit,
.minimal-light .adamlabsgallery-search-clean { background:#fff !important;color:#999; width:40px;height:40px; text-align: center; vertical-align: top; 
									border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px;margin-left:0px;
									border:1px solid #E5E5E5 !important;
								}
.minimal-light .adamlabsgallery-search-submit { border-right:none !important; border-radius:0; -webkit-border-radius:0; -moz-border-radius:0;}
.minimal-light .adamlabsgallery-search-clean { border-radius:0px 5px 5px 0px; -webkit-border-radius:0px 5px 5px 0px; -moz-border-radius:0px 5px 5px 0px}
.minimal-light .adamlabsgallery-search-submit:hover,
.minimal-light .adamlabsgallery-search-clean:hover { background: #fff !important; border-color: #bbb !important; box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.13) !important;}'),
			array('handle' => 'simple-light','css' => '/* SIMPLE LIGHT SEARCH 2.0 */
.simple-light .adamlabsgallery-search-wrapper { line-height: 30px !important}
.simple-light input.adamlabsgallery-search-input[type="text"] { background: #eee !important; padding: 0px 15px !important;
												border: 1px solid #E5E5E5 !important;
												color: #000 !important; line-height: 30px !important; box-shadow: none !important;
												font-size: 12px !important; text-transform: uppercase; font-weight: 400;
												}
.simple-light input.adamlabsgallery-search-input[type="text"]:hover,
.simple-light input.adamlabsgallery-search-input[type="text"]:focus { background-color: #fff !important}
.simple-light input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#000 !important}
.simple-light input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#000 !important}
.simple-light input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#000 !important}
.simple-light input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#000 !important}
.simple-light .adamlabsgallery-search-submit,
.simple-light .adamlabsgallery-search-clean { border: 1px solid #E5E5E5 !important; background:#eee; color:#000; width:32px; height:32px; text-align: center; font-size:14px; 
								vertical-align: top; margin-left:5px;
							  }
.simple-light .adamlabsgallery-search-submit:hover,
.simple-light .adamlabsgallery-search-clean:hover { color:#000; background:#fff !important}'),
			array('handle' => 'simple-dark','css' => '/* SIMPLE DARK SEARCH 2.0 */
.simple-dark .adamlabsgallery-search-wrapper { line-height: 30px !important}
.simple-dark input.adamlabsgallery-search-input[type="text"] { background: rgba(255, 255, 255, 0.08) !important; padding: 0px 15px !important;
												border:1px solid rgba(255, 255, 255, 0.15) !important;
												color: #fff !important; line-height: 30px !important; box-shadow: none !important;
												font-size: 12px !important; font-weight: 600;
											  }
.simple-dark input.adamlabsgallery-search-input[type="text"]:hover,
.simple-dark input.adamlabsgallery-search-input[type="text"]:focus { background-color: #fff !important; color:#000 !important;}
.simple-dark input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#fff !important}
.simple-dark input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#fff !important}
.simple-dark input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#fff !important}
.simple-dark input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#fff !important}
.simple-dark input:hover.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#000 !important}
.simple-dark input:hover.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#000 !important}
.simple-dark input:hover.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#000 !important}
.simple-dark input:hover.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#000 !important}

.simple-dark .adamlabsgallery-search-submit,
.simple-dark .adamlabsgallery-search-clean { border: 1px solid rgba(255, 255, 255, 0.15) !important; background: rgba(255, 255, 255, 0.08); 
								color:#fff; width:32px; height:32px; text-align: center; font-size:12px; 
								vertical-align: top;margin-left:5px;
							 }
.simple-dark .adamlabsgallery-search-submit:hover,
.simple-dark .adamlabsgallery-search-clean:hover{ color:#000; background:#fff !important}'),
			array('handle' => 'text-dark','css' => '/* TEXT DARK SEARCH 2.0 */
.text-dark .adamlabsgallery-search-wrapper {	line-height: 32px !important; vertical-align: middle !important}
.text-dark input.adamlabsgallery-search-input[type="text"] { background: transparent !important; padding: 0px 15px !important;
												border:none !important; margin-bottom:0px !important;
												color: #fff !important; color: rgba(255, 255, 255, 0.4) !important; line-height: 20px !important; box-shadow: none !important;
												font-size: 12px !important; font-weight: 600;
											}
.text-dark input.adamlabsgallery-search-input[type="text"]:hover,
.text-dark input.adamlabsgallery-search-input[type="text"]:focus {	 color:#fff !important;}
.text-dark input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#fff !important;color: rgba(255, 255, 255, 0.4) !important;}
.text-dark input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#fff !important; color: rgba(255, 255, 255, 0.4) !important;}
.text-dark input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#fff !important; color: rgba(255, 255, 255, 0.4) !important;}
.text-dark input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#fff !important; color: rgba(255, 255, 255, 0.4) !important;}
.text-dark input:hover.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#fff !important}
.text-dark input:hover.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#fff !important}
.text-dark input:hover.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#fff !important}
.text-dark input:hover.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#fff !important}


.text-dark .adamlabsgallery-search-submit,
.text-dark .adamlabsgallery-search-clean { border: none !important; background: transparent; line-height:20px;vertical-align: middle;
								color:#fff;color: rgba(255, 255, 255, 0.4) !important;height:20px; text-align: center; font-size:12px; 
								margin-left:10px; padding-left:10px; border-left:1px solid #fff !important; border-left:1px solid rgba(255, 255, 255, 0.2) !important;
							}
.text-dark .adamlabsgallery-search-submit:hover,
.text-dark .adamlabsgallery-search-clean:hover{ color:#fff !important;}'),
			array('handle' => 'text-light','css' => '/* TEXT LIGHT SEARCH 2.0 */
.text-light .adamlabsgallery-search-wrapper { line-height: 32px !important; vertical-align: middle !important}
.text-light input.adamlabsgallery-search-input[type="text"] { background: transparent !important; padding: 0px 15px !important;
												border:none !important; margin-bottom:0px !important;
												color: #999 !important; line-height: 20px !important; box-shadow: none !important;
												font-size: 12px !important;font-weight: 600;
											}
.text-light input.adamlabsgallery-search-input[type="text"]:hover,
.text-light input.adamlabsgallery-search-input[type="text"]:focus	{ color:#444 !important;}
.text-light input.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder { color:#999 !important;}
.text-light input.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#999 !important;}
.text-light input.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#999 !important;}
.text-light input.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#999 !important;}
.text-light input:hover.adamlabsgallery-search-input[type="text"]::-webkit-input-placeholder {	color:#444 !important}
.text-light input:hover.adamlabsgallery-search-input[type="text"]:-moz-placeholder { color:#444 !important}
.text-light input:hover.adamlabsgallery-search-input[type="text"]::-moz-placeholder { color:#444 !important}
.text-light input:hover.adamlabsgallery-search-input[type="text"]:-ms-input-placeholder { color:#444 !important}

.text-light .adamlabsgallery-search-submit,
.text-light .adamlabsgallery-search-clean { border: none !important; background: transparent; line-height:20px; vertical-align: middle;
								color:#999;height:20px; text-align: center; font-size:12px; 
								margin-left:10px; padding-left:10px; border-left:1px solid #e5e5e5 !important; 
							}
.text-light .adamlabsgallery-search-submit:hover,
.text-light .adamlabsgallery-search-clean:hover { color:#444 !important; }')
		);

		foreach($navigation_skins as $skin){
			$old_skin = $nav->get_adamlabsgallery_navigation_skin_by_handle($skin['handle']);

			if($old_skin !== false){
				$old_skin['css'] .= "\n\n\n".$skin['css'];

				//modify variables to meet requirement for update function
				$old_skin['skin_css'] = $old_skin['css'];
				$old_skin['sid'] = $old_skin['id'];
				unset($old_skin['name']);
				unset($old_skin['css']);
				unset($old_skin['id']);

				$nav->update_create_navigation_skin_css($old_skin);

			}

		}

		$this->update_version('0.2');
		$this->set_version('0.2');

	}


	/**
	 * update to 0.2.1
	 * @since: 0.2.1
	 * @does: adds navigation skins to support search further, fixing some missing styles
	 */
	public function update_to_021(){
		//update navigation skins to support search
		$nav = new AdamLabsGallery_Navigation();

		$navigation_skins = array(
			array('handle' => 'simple-light','css' => '/* SIMPLE LIGHT SEARCH 2.0.1 */
.simple-light input.adamlabsgallery-search-input[type="text"] {
	border-radius: 0px !important;
	height: 32px;
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}

.simple-light .adamlabsgallery-search-submit, .simple-light .adamlabsgallery-search-clean {
	width:32px;height:32px;
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}'),
			array('handle' => 'minimal-dark','css' => '/* MINIMAL DARK SEARCH 2.0.1 */
.minimal-dark input.adamlabsgallery-search-input[type="text"] {
	height: 40px;
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}
.minimal-dark .adamlabsgallery-search-submit, .minimal-dark .adamlabsgallery-search-clean {
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}'),
			array('handle' => 'minimal-light','css' => '/* MINIMAL LIGHT SEARCH 2.0.1 */
.minimal-light .adamlabsgallery-search-submit, .minimal-light .adamlabsgallery-search-clean {
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}'),
			array('handle' => 'simple-dark','css' => '/* SIMPLE DARK SEARCH 2.0.1 */
.simple-dark input.adamlabsgallery-search-input[type="text"] { box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	height: 34px;
	border-radius: 0px !important;
}'));

		foreach($navigation_skins as $skin){
			$old_skin = $nav->get_adamlabsgallery_navigation_skin_by_handle($skin['handle']);

			if($old_skin !== false){
				$old_skin['css'] .= "\n\n\n".$skin['css'];

				//modify variables to meet requirement for update function
				$old_skin['skin_css'] = $old_skin['css'];
				$old_skin['sid'] = $old_skin['id'];
				unset($old_skin['name']);
				unset($old_skin['css']);
				unset($old_skin['id']);

				$nav->update_create_navigation_skin_css($old_skin);

			}

		}

		$this->update_version('0.2.1');
		$this->set_version('0.2.1');
	}


	/**
	 * update to 0.3.0
	 * @since: 0.3.0
	 * @does: adds new Item Skins and Global Styles
	 */
	public function update_to_030(){

		$skins = array(
			array('name' => 'FlickrStream', 'handle' => 'flickrstream', 'params' => '{"adamlabsgallery-item-skin-element-last-id":"19","choose-layout":"even","show-content":"bottom","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","cover-type":"full","container-background-color":"rgba(0,0,0,0.50)","0":"Default","cover-always-visible-desktop":"","cover-always-visible-mobile":"","element-container-background-color-opacity":"100","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#222222","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"transparent","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#222222","content-padding":["0","0","0","0"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"none","all-shadow-used":"none","content-shadow-color":"rgba(0,0,0,1)","content-shadow-alpha":"100","content-box-shadow":["0","0","0","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"fade","media-animation":"none","media-animation-delay":"0","link-set-to":"cover","link-link-type":"lightbox","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}', 'layers' => '[{"id":"1","order":0,"container":"br","settings":{"0":"Default","source":"icon","enable-hover":"","font-size":"16","line-height":"22","color":"#ffffff","font-family":"","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"none","display":"inline-block","text-align":"center","float":"right","clear":"none","margin":["0","15","10","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["60","60","60","60"],"border-radius-unit":"px","border-color":"#ffffff","border-style":"solid","font-size-hover":"16","line-height-hover":"22","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"400","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"rgba(0,0,0,0.50)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["60","60","60","60"],"border-radius-unit-hover":"px","border-color-hover":"#ffffff","border-style-hover":"solid","hideunder":"0","transition":"fade","delay":"0","link-type":"post","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","css":"","css-hover":"","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","hide-on-video":"","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","show-on-lightbox-video":"","source-icon":"adamlabsgallery-icon-link"}},null,{"id":"15","order":1,"container":"br","settings":{"0":"Default","source":"post","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"13","line-height":"19","color":"#ffffff","font-family":"","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"left","clear":"none","margin":["0","0","15","20"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"14","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(255,255,255,0.15)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"none","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","source-post":"title"}},null,{"id":"17","order":2,"container":"br","settings":{"0":"Default","source":"text","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"13","line-height":"19","color":"#ffffff","font-family":"","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"left","clear":"none","margin":["0","0","15","20"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"14","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(255,255,255,0.15)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"none","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","show-on-lightbox-video":"","source-text":"<i class=\"adamlabsgallery-icon-star-empty\"><\/i> %favorites%"}},{"id":"19","order":3,"container":"br","settings":{"0":"Default","source":"text","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-function":"link","limit-type":"none","limit-num":"10","source-text-style-disable":"","enable-hover":"","font-size":"13","line-height":"19","color":"rgba(255,255,255,0.5)","font-family":"","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"left","clear":"none","margin":["0","0","15","20"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"14","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(255,255,255,0.15)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hideunderheight":"0","hidetype":"visibility","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"none","url-link":"","meta-link":"","javascript-link":"","link-target":"_self","tag-type":"div","force-important":"true","facebook-sharing-link":"site","facebook-link-url":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","show-on-lightbox-video":"","source-text":"by %author_name%"}}]', 'settings' => '{"favorite":false}'),
			array('name' => 'FacebookStream', 'handle' => 'facebookstream', 'params' => '{"adamlabsgallery-item-skin-element-last-id":"33","choose-layout":"masonry","show-content":"top","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","cover-type":"full","container-background-color":"rgba(54,88,153,0.65)","0":"Default","cover-always-visible-desktop":"","cover-always-visible-mobile":"","element-container-background-color-opacity":"100","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#ffffff","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"#e5e5e5","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#ffffff","content-padding":["30","30","26","30"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"double","all-shadow-used":"none","content-shadow-color":"rgba(0,0,0,1)","content-shadow-alpha":"100","content-box-shadow":["0","1","10","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"none","media-animation-delay":"0","link-set-to":"none","link-link-type":"none","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}', 'layers' => '[{"id":"0","order":"0","container":"m","settings":{"0":"","source":"post","enable-hover":"","font-size":"14","line-height":"22","color":"#363839","font-family":"Arial, Helvetica, sans-serif","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"none","display":"block","text-align":"left","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"20","line-height-hover":"25","color-hover":"#13c0df","font-family-hover":"\"Raleway\"","font-weight-hover":"800","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"none","delay":"30","link-type":"post","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","limit-type":"none","limit-num":"10","tag-type":"div","force-important":"","align":"t_l","absolute-unit":"px","hide-on-video":"","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","show-on-lightbox-video":"","source-post":"title"}},{"id":"25","order":"0","container":"c","settings":{"0":"","source":"text","enable-hover":"on","font-size":"13","line-height":"20","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["5","10","3","10"],"background-color":"rgba(0,0,0,0.15)","bg-alpha":"100","background-size":"cover","background-size-x":"100","background-size-y":"100","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"13","line-height-hover":"20","color-hover":"#ffffff","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(0,0,0,0.50)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"fade","delay":"0","link-type":"lightbox","url-link":"","javascript-link":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","transition-type":"","hide-on-video":"true","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","show-on-lightbox-video":"","source-text":"SHOW IMAGE"}},{"id":"28","order":"1","container":"c","settings":{"0":"Default","source":"text","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","enable-hover":"on","font-size":"13","line-height":"20","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"uppercase","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","5"],"padding":["5","10","3","10"],"background-color":"rgba(0,0,0,0.15)","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"20","color-hover":"#ffffff","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(0,0,0,0.50)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","transition":"fade","transition-type":"","delay":"0","link-type":"lightbox","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","show-on-lightbox-video":"true","source-text":"PLAY VIDEO"}},{"id":"3","order":"1","container":"m","settings":{"0":"","source":"text","enable-hover":"","font-size":"14","line-height":"22","color":"#365899","font-family":"Arial, Helvetica, sans-serif","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"capitalize","display":"inline-block","text-align":"center","float":"none","clear":"none","margin":["10","15","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#e5e5e5","border-style":"none","css":"","font-size-hover":"14","line-height-hover":"14","color-hover":"#000000","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"400","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"#e5e5e5","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"none","delay":"34","link-type":"none","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":", ","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","limit-type":"none","limit-num":"10","hide-on-video":"","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","show-on-sale":"","show-if-featured":"","show-on-lightbox-video":"","source-text":"<i class=\"adamlabsgallery-icon-thumbs-up-alt\" style=\"background:#365899;color:#fff;float:left;width:23px;height:23px;font-size:12px;text-align:center;border-radius:14px;margin-right:5px;\"><\/i> %likes_short%"}},{"id":"33","order":"2","container":"m","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"14","line-height":"22","color":"#90949c","font-family":"Arial, Helvetica, sans-serif","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["10","0","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"14","line-height-hover":"22","color-hover":"#7f7f7f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"900","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"skewleft","transition-type":"","delay":"10","link-type":"none","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","show-on-lightbox-video":"","source-text":"<i class=\"adamlabsgallery-icon-calendar-empty\" style=\"background:#90949c;color:#fff;float:left;width:23px;height:23px;font-size:12px;text-align:center;border-radius:14px;margin-right:5px;\"><\/i> %date%"}}]', 'settings' => '{"favorite":false}'),
			array('name' => 'YoutubeStream', 'handle' => 'youtubestream', 'params' => '{"adamlabsgallery-item-skin-element-last-id":"35","choose-layout":"masonry","show-content":"bottom","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","cover-type":"full","container-background-color":"rgba(0,0,0,0.65)","0":"Default","cover-always-visible-desktop":"","cover-always-visible-mobile":"","element-container-background-color-opacity":"100","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#ffffff","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"#e5e5e5","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#ffffff","content-padding":["20","20","20","20"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"double","all-shadow-used":"none","content-shadow-color":"rgba(0,0,0,1)","content-shadow-alpha":"100","content-box-shadow":["0","1","10","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"none","media-animation-delay":"0","link-set-to":"cover","link-link-type":"lightbox","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}', 'layers' => '[{"id":"0","order":"0","container":"m","settings":{"0":"","source":"post","enable-hover":"on","font-size":"14","line-height":"19","color":"#167ac6","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"capitalize","display":"block","text-align":"left","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"14","line-height-hover":"19","color-hover":"#167ac6","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"800","text-decoration-hover":"underline","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"none","delay":"30","link-type":"post","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","limit-type":"none","limit-num":"10","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","hide-on-video":"","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","show-on-lightbox-video":"","source-post":"title"}},{"id":"25","order":"0","container":"c","settings":{"0":"","source":"icon","enable-hover":"","font-size":"60","line-height":"60","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","background-size":"cover","background-size-x":"100","background-size-y":"100","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"60","line-height-hover":"60","color-hover":"rgba(255,255,255,0.85)","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"transparent","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"zoomback","delay":"0","link-type":"lightbox","url-link":"","javascript-link":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","transition-type":"","hide-on-video":"true","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","source-icon":"adamlabsgallery-icon-play"}},{"id":"33","order":"1","container":"m","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"chars","limit-num":"100","enable-hover":"","font-size":"14","line-height":"22","color":"#90949c","font-family":"Arial, Helvetica, sans-serif","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","10","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"14","line-height-hover":"22","color-hover":"#7f7f7f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"900","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"skewleft","transition-type":"","delay":"10","link-type":"post","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","show-on-lightbox-video":"","source-text":"%views_short% views"}},{"id":"34","order":"2","container":"m","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","source-function":"link","limit-type":"none","limit-num":"10","source-text-style-disable":"","enable-hover":"","font-size":"12","line-height":"12","color":"#90949c","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"capitalize","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"none","margin":["0","5","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#e5e5e5","border-style":"none","font-size-hover":"13","line-height-hover":"22","color-hover":"#e81c4f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"#e5e5e5","border-style-hover":"none","hideunder":"0","hideunderheight":"0","hidetype":"visibility","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"none","transition-type":"","delay":"34","link-type":"post","url-link":"","meta-link":"","javascript-link":"","link-target":"_blank","tag-type":"div","force-important":"true","facebook-sharing-link":"","facebook-link-url":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","show-on-lightbox-video":"","source-text":"<i class=\"adamlabsgallery-icon-thumbs-up-1\"><\/i>%likes_short%"}},{"id":"35","order":"3","container":"m","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","source-function":"link","limit-type":"none","limit-num":"10","source-text-style-disable":"","enable-hover":"","font-size":"12","line-height":"12","color":"#90949c","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"capitalize","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"none","margin":["0","5","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#e5e5e5","border-style":"none","font-size-hover":"13","line-height-hover":"22","color-hover":"#e81c4f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"#e5e5e5","border-style-hover":"none","hideunder":"0","hideunderheight":"0","hidetype":"visibility","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"none","transition-type":"","delay":"34","link-type":"post","url-link":"","meta-link":"","javascript-link":"","link-target":"_blank","tag-type":"div","force-important":"true","facebook-sharing-link":"site","facebook-link-url":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","show-on-lightbox-video":"","source-text":"<i class=\"adamlabsgallery-icon-thumbs-down\"><\/i>%dislikes_short%"}}]', 'settings' => '{"favorite":false}'),
			array('name' => 'TwitterStream', 'handle' => 'twitterstream', 'params' => '{"adamlabsgallery-item-skin-element-last-id":"38","choose-layout":"masonry","show-content":"top","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","cover-type":"full","container-background-color":"rgba(41,47,51,0.20)","0":"Default","cover-always-visible-desktop":"","cover-always-visible-mobile":"","element-container-background-color-opacity":"100","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#ffffff","full-padding":["30","30","30","30"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"#e5e5e5","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#ffffff","content-padding":["0","0","20","0"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"double","all-shadow-used":"none","content-shadow-color":"rgba(0,0,0,1)","content-shadow-alpha":"100","content-box-shadow":["0","1","10","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"zoomtodefault","media-animation-delay":"0","link-set-to":"none","link-link-type":"none","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}', 'layers' => '[{"id":"25","order":"0","container":"c","settings":{"0":"","source":"icon","enable-hover":"on","font-size":"30","line-height":"30","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","background-size":"cover","background-size-x":"100","background-size-y":"100","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"30","line-height-hover":"30","color-hover":"rgba(255,255,255,0.85)","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"fade","delay":"0","link-type":"lightbox","url-link":"","javascript-link":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","transition-type":"","hide-on-video":"true","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","show-on-lightbox-video":"","source-icon":"adamlabsgallery-icon-search"}},{"id":"37","order":"0","container":"br","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-function":"link","limit-type":"none","limit-num":"10","source-text-style-disable":"","enable-hover":"","font-size":"13","line-height":"22","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"capitalize","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"left","clear":"none","margin":["0","0","10","15"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#e5e5e5","border-style":"none","font-size-hover":"13","line-height-hover":"22","color-hover":"#e81c4f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"#e5e5e5","border-style-hover":"none","hideunder":"0","hideunderheight":"0","hidetype":"visibility","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"slideshortup","transition-type":"","delay":"0","link-type":"post","url-link":"","meta-link":"","javascript-link":"","link-target":"_blank","tag-type":"div","force-important":"true","facebook-sharing-link":"","facebook-link-url":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","show-on-lightbox-video":"","source-text":"@%author_name%"}},{"id":"35","order":"0","container":"m","settings":{"0":"Default","source":"post","source-separate":",","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","limit-type":"none","limit-num":"40","enable-hover":"on","font-size":"26","line-height":"32","color":"#292f33","font-family":"Arial, Helvetica, sans-serif","font-weight":"300","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"block","text-align":"left","float":"none","clear":"none","margin":["0","0","10","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"26","line-height-hover":"32","color-hover":"#0084b4","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"300","text-decoration-hover":"underline","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"none","transition-type":"","delay":"30","link-type":"post","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-lightbox-video":"","source-post":"title"}},{"settings":null},{"id":"33","order":"1","container":"m","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","enable-hover":"on","font-size":"13","line-height":"22","color":"#aab8c2","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","15","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"22","color-hover":"#19cf68","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"skewleft","transition-type":"","delay":"10","link-type":"sharetwitter","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","source-text":"<i class=\"adamlabsgallery-icon-shuffle-1\"><\/i> %retweets%"}},{"id":"36","order":"1","container":"c","settings":{"0":"Default","source":"icon","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-function":"link","limit-type":"none","limit-num":"10","source-text-style-disable":"","enable-hover":"on","font-size":"30","line-height":"30","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"30","line-height-hover":"30","color-hover":"rgba(255,255,255,0.85)","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hideunderheight":"0","hidetype":"visibility","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"lightbox","url-link":"","meta-link":"","javascript-link":"","link-target":"_self","tag-type":"div","force-important":"true","facebook-sharing-link":"","facebook-link-url":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","show-on-lightbox-video":"true","source-icon":"adamlabsgallery-icon-play"}},{"id":"3","order":"2","container":"m","settings":{"0":"","source":"text","enable-hover":"on","font-size":"13","line-height":"22","color":"#aab8c2","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"capitalize","display":"inline-block","text-align":"center","float":"none","clear":"none","margin":["0","15","0","0"],"padding":["0","0","0","0"],"background-color":"rgba(255,255,255,1)","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#e5e5e5","border-style":"none","css":"","font-size-hover":"13","line-height-hover":"22","color-hover":"#e81c4f","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"rgba(255,255,255,1)","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"#e5e5e5","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"none","delay":"34","link-type":"post","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":", ","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true","limit-type":"none","limit-num":"10","hide-on-video":"","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","show-on-sale":"","show-if-featured":"","source-text":"<i class=\"adamlabsgallery-icon-heart\"><\/i> %likes%"}}]', 'settings' => '{"favorite":false}'),
			array('name' => 'VimeoStream', 'handle' => 'vimeostream', 'params' => '{"adamlabsgallery-item-skin-element-last-id":"34","choose-layout":"even","show-content":"none","content-align":"left","element-x-ratio":"4","element-y-ratio":"3","cover-type":"full","container-background-color":"rgba(0,0,0,0.65)","0":"Default","cover-always-visible-desktop":"","cover-always-visible-mobile":"","element-container-background-color-opacity":"100","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#ffffff","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"#e5e5e5","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#ffffff","content-padding":["20","20","20","20"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"double","all-shadow-used":"none","content-shadow-color":"rgba(0,0,0,1)","content-shadow-alpha":"100","content-box-shadow":["0","1","10","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"none","media-animation-delay":"0","link-set-to":"none","link-link-type":"lightbox","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}', 'layers' => '[{"id":"0","order":"0","container":"c","settings":{"0":"","source":"post","enable-hover":"","font-size":"24","line-height":"28","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"capitalize","display":"block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","10","0","10"],"background-color":"transparent","bg-alpha":"100","background-size":"cover","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"24","line-height-hover":"28","color-hover":"#ffffff","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"900","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"transparent","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"fade","delay":"0","link-type":"post","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","hide-on-video":"","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","source-post":"title"}},{"id":"34","order":"0","container":"br","settings":{"0":"Default","source":"text","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"3","enable-hover":"","font-size":"12","line-height":"12","color":"rgba(255,255,255,0.5)","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"uppercase","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"block","text-align":"center","float":"none","clear":"both","margin":["0","0","10","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"13","line-height-hover":"14","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(255,255,255,0.15)","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"slideshortup","transition-type":"","delay":"30","link-type":"post","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","source-text":"%duration%"}},{"id":"33","order":"1","container":"c","settings":{"0":"Default","source":"text","source-separate":", ","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"chars","limit-num":"100","enable-hover":"","font-size":"18","line-height":"22","color":"#99aabc","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"block","text-align":"center","float":"none","clear":"both","margin":["5","0","0","0"],"padding":["0","10","0","10"],"background-color":"transparent","bg-alpha":"100","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"18","line-height-hover":"22","color-hover":"#99aabc","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"none","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_blank","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","source-text":"by %author_name%"}},{"id":"25","order":"2","container":"c","settings":{"0":"","source":"text","enable-hover":"on","font-size":"14","line-height":"40","color":"#44bbff","font-family":"Arial, Helvetica, sans-serif","font-weight":"700","text-decoration":"none","font-style":"","text-transform":"none","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["10","0","0","0"],"padding":["0","30","0","30"],"background-color":"transparent","bg-alpha":"100","background-size":"cover","background-size-x":"100","background-size-y":"100","background-repeat":"no-repeat","shadow-color":"rgba(0,0,0,1)","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["2","2","2","2"],"border-radius":["4","4","4","4"],"border-radius-unit":"px","border-color":"#44bbff","border-style":"solid","css":"","font-size-hover":"14","line-height-hover":"40","color-hover":"#ffffff","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"rgba(0,0,0,1)","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["2","2","2","2"],"border-radius-hover":["4","4","4","4"],"border-radius-unit-hover":"px","border-color-hover":"#ffffff","border-style-hover":"solid","css-hover":"","hideunder":"0","transition":"fade","delay":"0","link-type":"lightbox","url-link":"","javascript-link":"","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","limit-type":"none","limit-num":"10","transition-type":"","hide-on-video":"true","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","source-text":"Play Video"}}]', 'settings' => '{"favorite":false}'),
			array('name' => 'InstagramStream','handle' => 'instagramstream','params' => '{"adamlabsgallery-item-skin-element-last-id":"37","choose-layout":"even","show-content":"none","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","splitted-item":"none","cover-type":"full","container-background-color":"rgba(0,0,0,0.50)","cover-always-visible-desktop":"false","cover-always-visible-mobile":"false","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"#ffffff","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"#e5e5e5","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"#ffffff","content-padding":["20","20","20","20"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"double","all-shadow-used":"none","content-shadow-color":"#000000","content-box-shadow":["0","1","10","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"fade","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"none","media-animation-delay":"0","element-hover-image":"false","hover-image-animation":"fade","hover-image-animation-delay":"0","link-set-to":"none","link-link-type":"lightbox","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}','layers' => '[{"id":"0","order":"0","container":"c","settings":{"0":"","source":"text","enable-hover":"","font-size":"16","line-height":"16","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"uppercase","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","5","0","5"],"background-color":"transparent","background-size":"cover","background-repeat":"no-repeat","shadow-color":"#000000","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","css":"","font-size-hover":"24","line-height-hover":"28","color-hover":"#ffffff","font-family-hover":"Arial, Helvetica, sans-serif","font-weight-hover":"900","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"transparent","background-size-hover":"cover","background-size-x-hover":"100","background-size-y-hover":"100","background-repeat-hover":"no-repeat","shadow-color-hover":"#000000","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","css-hover":"","hideunder":"0","transition":"fade","delay":"0","link-type":"lightbox","url-link":"","javascript-link":"","margin-unit":"px","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","transition-type":"","position":"relative","top-bottom":"0","left-right":"0","source-separate":",","limit-type":"none","limit-num":"10","tag-type":"div","force-important":"true","align":"t_l","absolute-unit":"px","hide-on-video":"false","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","border-unit-hover":"px","box-shadow-unit-hover":"px","show-on-sale":"","show-if-featured":"","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","show-on-lightbox-video":"hide","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-text":"<i class=\"adamlabsgallery-icon-heart\"><\/i> %likes_short%"}},{"id":"35","order":"0","container":"tl","settings":{"0":"Default","source":"icon","source-separate":",","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"24","line-height":"22","color":"#ffffff","font-family":"","font-weight":"400","text-decoration":"none","font-style":"","text-transform":"none","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"right","clear":"none","margin":["10","7","0","0"],"padding":["0","0","0","0"],"background-color":"transparent","bg-alpha":"100","shadow-color":"#000000","shadow-alpha":"100","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"#ffffff","border-style":"solid","font-size-hover":"24","line-height-hover":"22","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"400","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"none","background-color-hover":"transparent","bg-alpha-hover":"100","shadow-color-hover":"#000000","shadow-alpha-hover":"100","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["60","60","60","60"],"border-radius-unit-hover":"px","border-color-hover":"#ffffff","border-style-hover":"solid","hideunder":"0","hide-on-video":"false","show-on-sale":"","show-if-featured":"","transition":"none","transition-type":"","delay":"0","link-type":"lightbox","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","border-hover-unit":"px","border-radius-hover-unit":"px","box-shadow-hover-unit":"px","adv-rules":{"ar-show":"show","ar-type":["off","off","off","off","off","off","off","off","off"],"ar-meta":["","","","","","","","",""],"ar-operator":["isset","isset","isset","isset","isset","isset","isset","isset","isset"],"ar-value":["","","","","","","","",""],"ar-value-2":["","","","","","","","",""],"ar-logic":["and","and","and","and","and","and"],"ar-logic-glob":["and","and"]},"show-on-lightbox-video":"true","source-icon":"adamlabsgallery-icon-videocam","source-catmax":"-1","always-visible-desktop":"true","always-visible-mobile":"true"}},{"id":"37","order":"1","container":"c","settings":{"0":"Default","source":"text","source-separate":",","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"16","line-height":"16","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"uppercase","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","5","0","5"],"background-color":"transparent","shadow-color":"#000000","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"20","line-height-hover":"25","color-hover":"#ffffff","font-family-hover":"\"Raleway\"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"capitalize","background-color-hover":"transparent","shadow-color-hover":"#000000","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"false","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"lightbox","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","show-on-lightbox-video":"true","link-target":"_self","source-text-style-disable":"","margin-unit":"px","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-text":"<i class=\"adamlabsgallery-icon-play\"><\/i> %likes_short%"}},{"id":"36","order":"2","container":"c","settings":{"0":"Default","source":"text","source-separate":",","limit-type":"none","limit-num":"10","enable-hover":"","font-size":"16","line-height":"16","color":"#ffffff","font-family":"Arial, Helvetica, sans-serif","font-weight":"900","text-decoration":"none","font-style":"","text-transform":"uppercase","position":"relative","align":"t_l","absolute-unit":"px","top-bottom":"0","left-right":"0","display":"inline-block","text-align":"center","float":"none","clear":"both","margin":["0","0","0","0"],"padding":["0","5","0","5"],"background-color":"transparent","shadow-color":"#000000","box-shadow":["0","0","0","0"],"border":["0","0","0","0"],"border-radius":["0","0","0","0"],"border-radius-unit":"px","border-color":"transparent","border-style":"none","font-size-hover":"17","line-height-hover":"14","color-hover":"#ffffff","font-family-hover":"","font-weight-hover":"700","text-decoration-hover":"none","font-style-hover":"","text-transform-hover":"uppercase","background-color-hover":"rgba(255,255,255,0.15)","shadow-color-hover":"#000000","box-shadow-hover":["0","0","0","0"],"border-hover":["0","0","0","0"],"border-radius-hover":["0","0","0","0"],"border-radius-unit-hover":"px","border-color-hover":"transparent","border-style-hover":"none","hideunder":"0","hide-on-video":"false","show-on-sale":"","show-if-featured":"","transition":"fade","transition-type":"","delay":"0","link-type":"lightbox","url-link":"","meta-link":"","javascript-link":"","tag-type":"div","force-important":"true","padding-unit":"px","border-unit":"px","box-shadow-unit":"px","source-function":"link","hideunderheight":"0","hidetype":"visibility","link-target":"_self","source-text-style-disable":"","margin-unit":"px","show-on-lightbox-video":"false","source-catmax":"-1","always-visible-desktop":"","always-visible-mobile":"","source-text":"<i class=\"adamlabsgallery-icon-align-left\"><\/i> %num_comments%"}}]','settings' => '{"favorite":false}'),
		);

		//Item Skins
		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site
			global $wpdb;

			// $old_blog = $wpdb->blogid;

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){
				switch_to_blog($blog_id);

				$skins = apply_filters('adamlabsgallery_propagate_default_item_skins_multisite_update_to_210', $skins, $blog_id);

				AdamLabsGallery_Item_Skin::insert_default_item_skins($skins);

				restore_current_blog();
			}

			// switch_to_blog($old_blog); //go back to correct blog

		}else{

			$skins = apply_filters('adamlabsgallery_propagate_default_item_skins_update_to_210', $skins);

			AdamLabsGallery_Item_Skin::insert_default_item_skins($skins);
		}


		$new_css = '
		
/*TWITTER STREAM*/
.adamlabsgallery-content.adamlabsgallery-twitterstream-element-33-a { display: inline-block; }
.adamlabsgallery-twitterstream-element-35 { word-break: break-all; } 
.adamlabsgallery-overlay.adamlabsgallery-twitterstream-container {background: -moz-linear-gradient(top, rgba(0,0,0,0) 50%, rgba(0,0,0,0.83) 99%, rgba(0,0,0,0.85) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(99%,rgba(0,0,0,0.83)), color-stop(100%,rgba(0,0,0,0.85))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#00000000\', endColorstr=\'#d9000000\',GradientType=0 ); /* IE6-9 */}

/*FACEBOOK STREAM*/
.adamlabsgallery-content.adamlabsgallery-facebookstream-element-33-a { display: inline-block; }
.adamlabsgallery-facebookstream-element-0 { word-break: break-all; } 

/*FLICKR STREAM*/
.adamlabsgallery-overlay.adamlabsgallery-flickrstream-container {background: -moz-linear-gradient(top, rgba(0,0,0,0) 50%, rgba(0,0,0,0.83) 99%, rgba(0,0,0,0.85) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,rgba(0,0,0,0)), color-stop(99%,rgba(0,0,0,0.83)), color-stop(100%,rgba(0,0,0,0.85))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.83) 99%,rgba(0,0,0,0.85) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#00000000\', endColorstr=\'#d9000000\',GradientType=0 ); /* IE6-9 */}
';

		//Global Styles
		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site
			global $wpdb;

			// $old_blog = $wpdb->blogid;

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){
				switch_to_blog($blog_id);

				$css = AdamLabsGallery_Global_Css::get_global_css_styles();

				AdamLabsGallery_Global_Css::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css_multisite_update_to_210', $css.$new_css, $blog_id));

				// 0.6.0.5
				restore_current_blog();

			}

			// 0.6.0.5
			// switch_to_blog($old_blog); //go back to correct blog

		}else{
			$css = AdamLabsGallery_Global_Css::get_global_css_styles();
			AdamLabsGallery_Global_Css::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css_update_to_210', $css.$new_css));
		}

		$this->update_version('0.3.0');
		$this->set_version('0.3.0');
	}

	/**
	 * update process
	 * @since: 0.4.0
	 * @does: adds new param(s) to all previous Item Skins
	 */
	private function addDefaultSkinParam($skins, $params) {

		if(!empty($skins)) {

			foreach($skins as $skin) {

				if(isset($skin['layers']) && !empty($skin['layers'])) {

					$layers = $skin['layers'];
					foreach($layers as $prop => $layer) {

						if(isset($layer['settings']) && !empty($layer['settings'])) {

							$settings = $layer['settings'];
							foreach($params as $key => $val) $settings[$key] = $val;

							$layer['settings'] = $settings;
							$layers[$prop] = $layer;

						}
					}

					$skin['layers'] = json_encode($layers);
					AdamLabsGallery_Item_Skin::update_save_item_skin($skin);

				}

			}
		}
	}

	/**
	 * update to 0.4.0
	 * @since: 0.4.0
	 * @does: adds new layer param(s) to all previous Item Skins via the "addDefaultSkinParam" function above
	 */
	public function update_to_040(){

		// new item skin layer params
		$paramsToPush = array('source-catmax' => '-1');

		//Item Skins
		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site
			global $wpdb;

			// $old_blog = $wpdb->blogid;

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){

				switch_to_blog($blog_id);
				$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
				$this->addDefaultSkinParam($skins, $paramsToPush);

				// 0.6.0.5
				restore_current_blog();

			}

			// switch_to_blog($old_blog); //go back to correct blog

		}else{

			$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
			$this->addDefaultSkinParam($skins, $paramsToPush);

		}

		$this->update_version('0.4.0');
		$this->set_version('0.4.0');

	}

	/* 0.5.0 */
	/* covering all the bases */
	private static function checkFalsey($val) {
		return !empty($val) && $val !== 'NULL' && $val !== 'false' && $val !== 'undefined';
	}

	/* 0.5.0 */
	/* upgrades previous and imported skins with new options */
	private static function process_skin_050($skin, $canConvert, $toConvert, $fromImport = false) {

		if(isset($skin['params']) && isset($skin['layers']) && !empty($skin['params'])) {

			$params = $skin['params'];
			$layers = $skin['layers'];

			// bail if params are missing
			if(empty($params)) {
				if($fromImport) return $skin;
				else return;
			}

			// decode if not already decoded
			if(is_string($params)) $params = json_decode($params, true);
			if(is_string($layers) && !empty($layers)) $layers = json_decode($layers, true);

			// one more check for params, as they are required for any given skin
			if(empty($params)) {
				if($fromImport) return $skin;
				else return;
			}

			$paramsChanged = false;
			$layersChanged = false;

			/* join color and opacity for params */
			if($canConvert) {

				$colors = $toConvert['settings'];
				foreach($colors as $colorSet) {

					$colorProp = $colorSet['color'];
					$opacityProp = $colorSet['opacity'];

					if(isset($params[$colorProp]) && isset($params[$opacityProp])) {

						$color = $params[$colorProp];
						$opacity = $params[$opacityProp];

						if(static::checkFalsey($color) && is_numeric($opacity) && $opacity != '100') {

							$converted = AdamLabsColorpicker::convert($color, $opacity);
							if(!empty($converted)) {
								$params[$colorProp] = $converted;
								$params[$opacityProp] = '100';
								$paramsChanged = true;
							}
						}
					}
				}
			}

			if(!empty($layers)) {

				$colors = $toConvert['layers'];
				$invisible = isset($params['cover-group-animation']) ? $params['cover-group-animation'] === 'none' : false;

				foreach($layers as $key => $layer) {

					if(isset($layer['settings']) && !empty($layer['settings'])) {

						$layerSets = $layer['settings'];
						$toUpdate = false;

						/* set new visibility options */
						if(isset($layerSets['transition']) && !empty($layerSets['transition']) && !isset($layerSets['always-visible-desktop']) && !isset($layerSets['always-visible-mobile'])) {

							$hidden = $layerSets['transition'] === 'none' && $invisible ? 'true' : '';
							$layer['settings']['always-visible-desktop'] = $hidden;
							$layer['settings']['always-visible-mobile'] = $hidden;
							$toUpdate = true;

						}

						/* join color and opacity for layers */
						if($canConvert) {

							foreach($colors as $colorSet) {

								$colorProp = $colorSet['color'];
								$opacityProp = $colorSet['opacity'];

								if(isset($layerSets[$colorProp]) && isset($layerSets[$opacityProp])) {

									$color = $layerSets[$colorProp];
									$opacity = $layerSets[$opacityProp];
									if(static::checkFalsey($color) && is_numeric($opacity) && $opacity != '100') {

										$converted = AdamLabsColorpicker::convert($color, $opacity);
										if(!empty($converted)) {

											$layer['settings'][$colorProp] = $converted;
											$layer['settings'][$opacityProp] = '100';
											$toUpdate = true;
										}
									}
								}
							}
						}
						if($toUpdate) {
							$layers[$key] = $layer;
							$layersChanged = true;
						}

					}
				}
			}

			if(!$fromImport) {

				if($paramsChanged || $layersChanged) {
					if($paramsChanged) $skin['params'] = $params;
					if($layersChanged) $skin['layers'] = json_encode($layers);
					AdamLabsGallery_Item_Skin::update_save_item_skin($skin);
				}
			}
			else {
				$skin['params'] = json_encode($params);
				$skin['layers'] = json_encode($layers);
				return $skin;
			}

		}
		else if($fromImport) {
			return $skin;
		}

	}

	/*
	  0.5.0
	  Determining if a Layer was officially set to "always visible" and setting the new "showWithoutHover" options accordingly
	  Also merges all colors+opacity where applicable
	*/
	public static function process_update_050($skins, $fromImport = false) {

		if(!empty($skins)) {

			// vars defined here so they are only created once
			$canConvert = class_exists('AdamLabsColorpicker');
			$toConvert = array(

				'settings' => array(
					array('color' => 'container-background-color', 'opacity' => 'element-container-background-color-opacity'),
					array('color' => 'content-shadow-color',       'opacity' => 'content-shadow-alpha')
				),

				'layers' => array(
					array('color' => 'background-color',       'opacity' => 'bg-alpha'),
					array('color' => 'background-color-hover', 'opacity' => 'bg-alpha-hover'),
					array('color' => 'shadow-color',           'opacity' => 'shadow-alpha'),
					array('color' => 'shadow-color-hover',     'opacity' => 'shadow-alpha-hover')
				)
			);

			// update cycle
			if(!$fromImport) {
				foreach($skins as $skin) {
					static::process_skin_050($skin, $canConvert, $toConvert);
				}
			}
			// import cycle
			else {
				return static::process_skin_050($skins, $canConvert, $toConvert, true);
			}
		}
		else if($fromImport) {

			return $skins;
		}

	}

	/**
	 * update to 0.5.0
	 * @since: 0.5.0
	 * @does: adds new "showWithoutHover" options and upgrade to new Color Picker
	 */
	public function update_to_050(){

		//Item Skins
		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site
			global $wpdb;

			// $old_blog = $wpdb->blogid;

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){

				switch_to_blog($blog_id);
				$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
				$this->process_update_050($skins);

				// 0.6.0.5
				restore_current_blog();

			}

			// 0.6.0.5
			// switch_to_blog($old_blog); //go back to correct blog

		}else{

			$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
			$this->process_update_050($skins);

		}

		$this->update_version('0.5.0');
		$this->set_version('0.5.0');

	}

	/**
	 * update to 2.1.7
	 * @since: 2.1.7
	 * @does: adds new "post likes votes" options
	 */
	public function update_to_060(){

		foreach( get_posts() as $post ) {
			if(!is_numeric(get_post_meta( $post->ID, 'adamlabsgallery_votes_count', $single = true ))){
				update_post_meta($post->ID, 'adamlabsgallery_votes_count',0);
			}
		}

		// Global Styles
		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site
			global $wpdb;

			// $old_blog = $wpdb->blogid;

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){
				switch_to_blog($blog_id);

				$css = AdamLabsGallery_Global_Css::get_global_css_styles();

				// new
				$css = str_replace('.adamlabsgallery-entry-media img', '.adamlabsgallery-media-poster', $css);
				AdamLabsGallery_Global_Css::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css_multisite_update_to_0600', $css, $blog_id));

				// 0.6.0.5
				restore_current_blog();

			}

			// 0.6.0.5
			// switch_to_blog($old_blog); //go back to correct blog

		}else{
			$css = AdamLabsGallery_Global_Css::get_global_css_styles();

			// new
			$css = str_replace('.adamlabsgallery-entry-media img', '.adamlabsgallery-media-poster', $css);
			AdamLabsGallery_Global_Css::set_global_css_styles(apply_filters('adamlabsgallery_propagate_default_global_css_update_to_0600', $css));
		}

		$this->update_version('0.6.0');
		$this->set_version('0.6.0');
	}

	/**
	 * update to 0.7.0
	 * @since: 0.7.0
	 * @does: adds a new skin to the exisiting installation
	 */
	 public function insert_skin($skin) {

		$skins = AdamLabsGallery_Item_Skin::get_adamlabsgallery_item_skins();
		if(!empty($skins)) {
			foreach($skins as $skn) {
				if(isset($skn['handle']) && $skn['handle'] === 'adamlabsgalleryblankskin') return;
			}
		}

		global $wpdb;
		$table_name = $wpdb->prefix . AdamLabsGallery::TABLE_ITEM_SKIN;
		$wpdb->insert($table_name, array('name' => $skin['name'], 'handle' => $skin['handle'], 'params' => $skin['params'], 'layers' => $skin['layers']));

	 }

	/**
	 * update to 0.7.0
	 * @since: 0.7.0
	 * @does: adds new blank skin for custom grid blank items
	 */
	public function update_to_070(){

		global $wpdb;

		$blank_skin = array('name' => 'AdamLabsGalleryBlankSkin','handle' => 'adamlabsgalleryblankskin','params'=>'{"adamlabsgallery-item-skin-element-last-id":"0","choose-layout":"even","show-content":"none","content-align":"left","image-repeat":"no-repeat","image-fit":"cover","image-align-horizontal":"center","image-align-vertical":"center","element-x-ratio":"4","element-y-ratio":"3","splitted-item":"none","cover-type":"full","container-background-color":"rgba(0, 0, 0, 0)","cover-always-visible-desktop":"false","cover-always-visible-mobile":"false","cover-background-size":"cover","cover-background-repeat":"no-repeat","cover-background-image":"0","cover-background-image-url":"","full-bg-color":"rgba(255, 255, 255, 0)","full-padding":["0","0","0","0"],"full-border":["0","0","0","0"],"full-border-radius":["0","0","0","0"],"full-border-color":"transparent","full-border-style":"none","full-overflow-hidden":"false","content-bg-color":"rgba(255, 255, 255, 0)","content-padding":["0","0","0","0"],"content-border":["0","0","0","0"],"content-border-radius":["0","0","0","0"],"content-border-color":"transparent","content-border-style":"none","all-shadow-used":"none","content-shadow-color":"#000000","content-box-shadow":["0","0","0","0"],"cover-animation-top-type":"","cover-animation-delay-top":"0","cover-animation-top":"fade","cover-animation-center-type":"","cover-animation-delay-center":"0","cover-animation-center":"none","cover-animation-bottom-type":"","cover-animation-delay-bottom":"0","cover-animation-bottom":"fade","cover-group-animation":"none","media-animation":"none","media-animation-delay":"0","element-hover-image":"false","hover-image-animation":"fade","hover-image-animation-delay":"0","link-set-to":"none","link-link-type":"none","link-url-link":"","link-meta-link":"","link-javascript-link":"","link-target":"_self"}','layers'=>"[]",'settings'=>null);
		$new_skin = array('name' => $blank_skin['name'], 'handle' => $blank_skin['handle'], 'params' => $blank_skin['params'], 'layers' => $blank_skin['layers']);

		if(function_exists('is_multisite') && is_multisite()){ //do for each existing site

			// Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);

			foreach($blogids as $blog_id){

				switch_to_blog($blog_id);
				$this->insert_skin($new_skin);
				restore_current_blog();

			}

		}else{

			$this->insert_skin($new_skin);

		}

		$this->update_version('0.7.0');
		$this->set_version('0.7.0');
	
	}
	
}