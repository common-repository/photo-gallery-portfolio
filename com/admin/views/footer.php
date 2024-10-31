<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 */

if( !defined( 'ABSPATH') ) exit();

$tooltips = get_option('adamlabsgallery_tooltips', 'true');

?>

</div>

<script type="text/javascript">
	var token = '<?php echo wp_create_nonce("AdamLabsGallery_actions"); ?>';
	var es_do_tooltipser = <?php echo $tooltips; ?>;
	
	jQuery(document).ready(function() {
		
		AdminEssentials.initAccordion();
		
		<?php
		if($tooltips == 'true'){
		?>
        AdminEssentials.initToolTipser();
		<?php
		}
		?>
	});
</script>

<div id="waitaminute">
	<div class="waitaminute-message"><i class="adamlabsgallery-icon-coffee"></i><br><?php _e("Please Wait...", ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
</div>

<div id="adamlabsgallery-error-box">
	
</div>
