<div class="adamlabsgallery-grid-main-settings">
    <?php if($grid !== false){ ?>
        <input type="hidden" name="adamlabsgallery-id" value="<?php echo $grid['id']; ?>" />
    <?php } ?>

    <label for="name" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Name of the grid', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
        <?php _e('Title', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
        <input type="text" name="name"  value="<?php echo $base->getVar($grid, 'name', '', 's'); ?>" /> *
    </label>
    <label for="handle" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Technical alias without special chars and white spaces', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
        <?php _e('Alias', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
        <input type="text" name="handle"  value="<?php echo $base->getVar($grid, 'handle', '', 's'); ?>" /> *
    </label>
    <label for="shortcode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Copy this shortcode to paste it to your pages or posts content', ADAMLABS_GALLERY_TEXTDOMAIN); ?>" >
        <?php _e('Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
        <input type="text" name="shortcode" value="" readonly="readonly" />
    </label>
    <label for="id" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Add a unique ID to be able to add CSS to certain Grids', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
        <?php _e('CSS ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
        <input type="text" name="css-id" id="adamlabsgallery-id-value" value="<?php echo $base->getVar($grid['params'], 'css-id', '', 's'); ?>" />
    </label>
</div>