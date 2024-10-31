


<div id="adamlabsgallery-source" class="postbox adamlabsgallery-postbox">
    <h3>
        <span><?php _e('Manage Sources', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
        <div class="postbox-arrow"></div>
    </h3>
    <div class="inside" style="padding:0px !important;margin:0px !important;height:100%;position:relative;background:#e1e1e1">
        <div>
            <div class="">
                <form id="adamlabsgallery-form-create-posts">
                    <div class="adamlabsgallery-creative-settings">
                        <div class="adamlabsgallery-cs-tbc-left">
                            <h3><span><?php _e('Grid Item Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                        </div>
                        <div class="adamlabsgallery-cs-tbc adamlabsgallery-form-table-cell">
                            <p class="adamlabsgallery-form-table-cell">
                                <label for="shortcode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose source of grid items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Based on', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                            </p>
                            <p id="adamlabsgallery-source-choose-wrapper" class="adamlabsgallery-form-table-cell">
									<span class="adamlabsgallery-form-choose-field">
										<input type="radio" name="source-type" value="post" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'source-type', 'custom'), 'post'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items from Posts, Custom Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Post, Pages, Custom Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</span>
                                <span class="adamlabsgallery-form-choose-field">
										<input type="radio" name="source-type" value="custom" <?php echo checked($base->getVar($grid['postparams'], 'source-type', 'custom'), 'custom'); ?> > <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Items from the Media Gallery (Bulk Selection, Upload Possible)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Custom Grid (Editor Above)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</span>
                                <span class="adamlabsgallery-form-choose-field">
										<input type="radio" name="source-type" value="stream" <?php echo checked($base->getVar($grid['postparams'], 'source-type', 'custom'), 'stream'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Fetches dynamic streams from several sources ', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
									</span>
                                <?php if(array_key_exists('nggdb', $GLOBALS) ){ ?>
                                    <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="source-type" value="nextgen" <?php echo checked($base->getVar($grid['postparams'], 'source-type', 'custom'), 'nextgen'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Fetches NextGen Galleries and Albums ', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('NextGen Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                <?php } ?>
                                <?php if( function_exists('wp_rml_dropdown') ){ ?>
                                    <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="source-type" value="rml" <?php echo checked($base->getVar($grid['postparams'], 'source-type', 'post'), 'rml'); ?>> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Fetches Real Media Library Galleries and Folders', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Real Media Library', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                <?php } ?>
                                <?php do_action('adamlabsgallery_grid_source',$base,$grid); ?>
                            </p>
                        </div>

                    </div>

                    <div id="custom-sorting-wrap" style="display: none;">
                        <ul id="adamlabsgallery-custom-li-sorter" style="margin:0px">
                        </ul>
                    </div>
                    <div id="post-pages-wrap">
                        <div class="divider1"></div>
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Type and Category', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc">
                                <p>
                                    <label for="post_types" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select Post Types (multiple selection possible)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Post Types', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    <select name="post_types" size="5" multiple="multiple" >
                                        <?php
                                        $selectedPostTypes = array();
                                        $post_types = $base->getVar($grid['postparams'], 'post_types', 'post');
                                        if(!empty($post_types))
                                            $selectedPostTypes = explode(',',$post_types);
                                        else
                                            $selectedPostTypes = array('post');

                                        if(!empty($post_elements)){
                                            foreach($post_elements as $handle => $name){
                                                ?>
                                                <option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $selectedPostTypes), true); ?>><?php echo $name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>

                                <p id="adamlabsgallery-post-cat-wrap">
                                    <label for="source-code" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Select Categories and Tags (multiple selection possible)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Post Categories', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    <?php
                                    $postTypes = (strpos($postTypes, ",") !== false) ? explode(",",$postTypes) : $postTypes = array($postTypes);
                                    if(empty($postTypes)) $postTypes = array($postTypes);
                                    //change $postTypes to corresponding IDs depending on language
                                    //$postTypes = $base->translate_base_categories_to_cur_lang($postTypes);
                                    ?>
                                    <select name="post_category" size="7" multiple="multiple" >
                                        <?php
                                        if($grid !== false){ //set the values
                                            if(!empty($categories)){

                                                foreach($categories as $handle => $cat){
                                                    ?>
                                                    <option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $postTypes), true); ?><?php echo (strpos($handle, 'option_disabled_') !== false) ? ' disabled="disabled"' : ''; ?>><?php echo $cat; ?></option>
                                                    <?php
                                                }
                                            }
                                        }else{
                                            if(!empty($postTypesWithCats['post'])){

                                                foreach($postTypesWithCats['post'] as $handle => $cat){
                                                    ?>
                                                    <option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $postTypes), true); ?><?php echo (strpos($handle, 'option_disabled_') !== false) ? ' disabled="disabled"' : ''; ?>><?php echo $cat; ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                <div style="float: left"><label>&nbsp;</label></div><a class="button-primary adamlabsgallery-clear-taxonomies" href="javascript:void(0);"><?php _e('Clear Categories', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                </p>
                                <p>
                                <div style="float: left"><label for="category-relation"><?php _e('Category Relation', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></div>
                                <div style="float: left">
                                    <input type="radio" value="OR" name="category-relation" <?php checked($base->getVar($grid['postparams'], 'category-relation', 'OR'), 'OR'); ?> class="firstinput"> <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Post need to be in one of the selected categories/tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('OR', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    <input type="radio" value="AND" name="category-relation" <?php checked($base->getVar($grid['postparams'], 'category-relation', 'OR'), 'AND'); ?> > <span class="adamlabsgallery-tooltip-wrap" title="<?php _e('Post need to be in all categories/tags selected', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('AND', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                </div>
                                <div style="clear: both;"></div>
                                </p>
                                <div id="adamlabsgallery-additional-post">
                                    <div style="float: left"><label for="additional-query" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Please use it like \'year=2012&monthnum=12\'', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Additional Parameters', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label></div>
                                    <div style="float: left">
                                        <input type="text" name="additional-query" class="adamlabsgallery-additional-parameters" value="<?php echo $base->getVar($grid['postparams'], 'additional-query', ''); ?>" />
                                        <p><?php _e('Please use it like \'year=2012&monthnum=12\' or \'post__in=array(1,2,5)&post__not_in=array(25,10)\'', ADAMLABS_GALLERY_TEXTDOMAIN); ?>&nbsp;-&nbsp;
                                            <?php _e('For a full list of parameters, please visit <a href="https://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">this</a> link', ADAMLABS_GALLERY_TEXTDOMAIN); ?></p>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider1"></div>
                    <div id="set-pages-wrap">
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Pages', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc">
                                <p>
                                    <label for="pages" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Additional filtering on pages,Start to type a page title for pre selection', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Pages', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    <input type="text" id="pages" value="" name="search_pages"> <a class="button-secondary" id="button-add-pages" href="javascript:void(0);">+</a>
                                </p>
                                <div id="pages-wrap">
                                    <?php
                                    if(!empty($pages)){
                                        foreach($pages as $page){
                                            if(in_array($page->ID, $selected_pages)){
                                                ?>
                                                <div data-id="<?php echo $page->ID; ?>"><?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?> <i class="adamlabsgallery-icon-trash del-page-entry"></i></div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <select name="selected_pages" multiple="true" style="display: none;">
                                    <?php
                                    if(!empty($pages)){
                                        foreach($pages as $page){
                                            ?>
                                            <option value="<?php echo $page->ID; ?>"<?php selected(in_array($page->ID, $selected_pages), true); ?>><?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="divider1"></div>
                    </div>

                    <div id="aditional-pages-wrap">
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Options', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc">
                                <?php
                                $max_entries = intval($base->getVar($grid['postparams'], 'max_entries', '-1'));
                                ?>
                                <p>
                                    <label for="pages" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Defines a posts limit, use only numbers, -1 will disable this option, use only numbers', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Maximum Posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    <input type="number" value="<?php echo $max_entries; ?>" name="max_entries">
                                </p>
                                <?php
                                $max_entries_preview = intval($base->getVar($grid['postparams'], 'max_entries_preview', '20'));
                                ?>
                                <p>
                                    <label for="pages" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Defines a posts limit, use only numbers, -1 will disable this option, use only numbers', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Maximum Posts Preview', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    <input type="number" value="<?php echo $max_entries_preview; ?>" name="max_entries_preview">
                                </p>
                            </div>
                        </div>
                        <div class="divider1"></div>
                    </div>

                    <div id="all-stream-wrap">
                        <div id="external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Service', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc adamlabsgallery-form-table-cell">
                                    <p class="adamlabsgallery-form-table-cell">
                                        <label for="shortcode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose source of grid items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Provider', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p id="adamlabsgallery-source-choose-wrapper" class="adamlabsgallery-form-table-cell">
										<span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="youtube" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'youtube'); ?>><span class="inplabel"><?php _e('YouTube', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="vimeo" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'vimeo'); ?>><span class="inplabel"><?php _e('Vimeo', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="instagram" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'instagram'); ?>><span class="inplabel"><?php _e('Instagram', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="flickr" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'flickr'); ?>><span class="inplabel"><?php _e('Flickr', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="facebook" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'facebook'); ?>><span class="inplabel"><?php _e('Facebook', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="twitter" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'twitter'); ?>><span class="inplabel"><?php _e('Twitter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="behance" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'behance'); ?>><span class="inplabel"><?php _e('Behance', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span>
                                        <!--span class="adamlabsgallery-form-choose-field">
											<input type="radio" name="stream-source-type" value="dribbble" <?php checked($base->getVar($grid['postparams'], 'stream-source-type', 'instagram'), 'dribbble'); ?>><span class="inplabel"><?php _e('Dribbble', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
										</span-->
                                    </p>
                                    <p id="adamlabsgallery-source-youtube-message">
                                        <span class="description"><?php _e('The "YouTube Stream" content source is used to display a full stream of videos from a channel/playlist.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <p id="adamlabsgallery-source-vimeo-message">
                                        <span class="description"><?php _e('The "Vimeo Stream" content source is used to display a full stream of videos from a user/album/group/channel.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div>

                        <div id="youtube-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the YouTube API key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('API Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'youtube-api', ''); ?>" name="youtube-api" id="youtube-api">
                                    <div class="adamlabs-clearfix"></div>
                                    <span class="description"><?php _e('Find information about the YouTube API key <a target="_blank" href="https://developers.google.com/youtube/v3/getting-started#before-you-start">here</a>', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label  class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the ID of the YouTube channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Channel ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" style="margin-right:10px" value="<?php echo $base->getVar($grid['postparams'], 'youtube-channel-id', ''); ?>" name="youtube-channel-id" id="youtube-channel-id">
                                        <span class="description"><?php _e('See how to find the Youtube channel ID <a target="_blank" href="https://support.google.com/youtube/answer/3250431?hl=en">here</a>', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Display the channel videos or playlist', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="youtube-type-source" value="channel" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'youtube-type-source', 'channel'), 'channel'); ?>><span class="inplabel"><?php _e('Channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="youtube-type-source" value="playlist_overview" <?php checked($base->getVar($grid['postparams'], 'youtube-type-source', 'channel'), 'playlist_overview'); ?> > <span class="inplabel"><?php _e('Overview Playlists', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="youtube-type-source" value="playlist" <?php checked($base->getVar($grid['postparams'], 'youtube-type-source', 'channel'), 'playlist'); ?> > <span class="inplabel"><?php _e('Single Playlist', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <div id="adamlabsgallery-external-source-youtube-playlist-wrap">
                                        <p>
                                            <?php $youtube_playlist = $base->getVar($grid['postparams'], 'youtube-playlist', '');
                                            ?>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the playlist you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Playlist', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="hidden" name="youtube-playlist" value="<?php echo $youtube_playlist; ?>">
                                            <select name="youtube-playlist-select" id="youtube-playlist-select">
                                            </select>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="youtube-thumb-size">
                                            <option value='default' <?php selected( $base->getVar($grid['postparams'], 'youtube-thumb-size', 'default'), 'default');?>><?php _e('Default (120px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='medium' <?php selected( $base->getVar($grid['postparams'], 'youtube-thumb-size', 'default'), 'medium');?>><?php _e('Medium (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='high' <?php selected( $base->getVar($grid['postparams'], 'youtube-thumb-size', 'default'), 'high');?>><?php _e('High (480px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='standard' <?php selected( $base->getVar($grid['postparams'], 'youtube-thumb-size', 'default'), 'standard');?>><?php _e('Standard (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='maxres' <?php selected( $base->getVar($grid['postparams'], 'youtube-thumb-size', 'default'), 'maxres');?>><?php _e('Max. Res. (1280px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="youtube-full-size">
                                            <option value='default' <?php selected( $base->getVar($grid['postparams'], 'youtube-full-size', 'default'), 'default');?>><?php _e('Default (120px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='medium' <?php selected( $base->getVar($grid['postparams'], 'youtube-full-size', 'default'), 'medium');?>><?php _e('Medium (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='high' <?php selected( $base->getVar($grid['postparams'], 'youtube-full-size', 'default'), 'high');?>><?php _e('High (480px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='standard' <?php selected( $base->getVar($grid['postparams'], 'youtube-full-size', 'default'), 'standard');?>><?php _e('Standard (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='maxres' <?php selected( $base->getVar($grid['postparams'], 'youtube-full-size', 'default'), 'maxres');?>><?php _e('Max. Res. (1280px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Stream this number of videos', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'youtube-count', '12'); ?>" name="youtube-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input id="youtube-transient-sec" type="number" value="<?php echo $base->getVar($grid['postparams'], 'youtube-transient-sec', '86400'); ?>" name="youtube-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_youtube"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="youtube-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div> <!-- End YouTube Stream -->

                        <div id="vimeo-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Source of Vimeo videos', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Videos of', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="vimeo-type-source" value="user" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'vimeo-type-source', 'user'), 'user'); ?>><span class="inplabel"><?php _e('User', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="vimeo-type-source" value="album" <?php checked($base->getVar($grid['postparams'], 'vimeo-type-source', 'user'), 'album'); ?>><span class="inplabel"><?php _e('Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="vimeo-type-source" value="group" <?php checked($base->getVar($grid['postparams'], 'vimeo-type-source', 'user'), 'group'); ?>><span class="inplabel"><?php _e('Group', ADAMLABS_GALLERY_TEXTDOMAIN); ?>	</span>
                                        <input type="radio" name="vimeo-type-source" value="channel" <?php checked($base->getVar($grid['postparams'], 'vimeo-type-source', 'user'), 'channel'); ?>><span class="inplabel"><?php _e('Channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>

                                    <p>
                                    <div id="adamlabsgallery-external-source-vimeo-user-wrap" class="adamlabsgallery-external-source-vimeo">
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('ID of the user', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('User', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-username', ''); ?>" name="vimeo-username">
                                    </div>
                                    <div id="adamlabsgallery-external-source-vimeo-group-wrap" class="adamlabsgallery-external-source-vimeo">
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('ID of the group', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Group', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-groupname', ''); ?>" name="vimeo-groupname">
                                    </div>
                                    <div id="adamlabsgallery-external-source-vimeo-album-wrap" class="adamlabsgallery-external-source-vimeo">
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('The ID of the album', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Album ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-albumid', ''); ?>" name="vimeo-albumid">
                                    </div>
                                    <div id="adamlabsgallery-external-source-vimeo-channel-wrap" class="adamlabsgallery-external-source-vimeo">
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('ID of the channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-channelname', ''); ?>" name="vimeo-channelname">
                                    </div>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="vimeo-thumb-size">
                                            <option value='thumbnail_small' <?php selected( $base->getVar($grid['postparams'], 'vimeo-thumb-size', 'thumbnail_small'), 'thumbnail_small');?>><?php _e('Small (100px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='thumbnail_medium' <?php selected( $base->getVar($grid['postparams'], 'vimeo-thumb-size', 'thumbnail_small'), 'thumbnail_medium');?>><?php _e('Medium (200px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='thumbnail_large' <?php selected( $base->getVar($grid['postparams'], 'vimeo-thumb-size', 'thumbnail_small'), 'thumbnail_large');?>><?php _e('Large (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Display this number of videos', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-count', '12'); ?>" name="vimeo-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'vimeo-transient-sec', '86400'); ?>" name="vimeo-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_vimeo"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="vimeo-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div><!-- End Vimeo Stream -->

                        <div id="instagram-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings instagram_user">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Source of Instagram images', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Images/Videos from', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="checkbox" name="instagram-type-source-users" data-source="users" value="true" class="firstinput instagram-type-source" <?php checked($base->getVar($grid['postparams'], 'instagram-type-source-users', ''), 'true'); ?>><span class="inplabel"><?php _e('People', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="checkbox" name="instagram-type-source-tags" data-source="tags" class="instagram-type-source" value="true" <?php checked($base->getVar($grid['postparams'], 'instagram-type-source-tags', ''), 'true'); ?>><span class="inplabel"><?php _e('Tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="checkbox" name="instagram-type-source-places" data-source="places" class="instagram-type-source" value="true" <?php checked($base->getVar($grid['postparams'], 'instagram-type-source-places', ''), 'true'); ?>><span class="inplabel"><?php _e('Places', ADAMLABS_GALLERY_TEXTDOMAIN); ?>	</span>
                                    </p>
                                    <div class="instagram_users">
                                        <p>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Instagram Users', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Instagram User(s)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'instagram-user-id', ''); ?>" name="instagram-user-id">
                                            <span class="description"><?php _e('Separate multiple searched users by commas', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        </p>
                                    </div>
                                    <div class="instagram_tags">
                                        <p>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Instagram Tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Instagram Tag(s)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'instagram-tags', ''); ?>" name="instagram-tags">
                                            <span class="description"><?php _e('Separate multiple searched tags by commas', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        </p>
                                    </div>
                                    <div class="instagram_places">
                                        <p>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Instagram Places', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Instagram Place ID(s)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'instagram-places', ''); ?>" name="instagram-places">
                                            <span class="description"><?php _e('Separate multiple searched places by commas', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        </p>
                                        <span class="description"><?php _e('Get the ID from the URL (Example https://www.instagram.com/explore/locations/<strong><i style="font-size:14px">213121716</i></strong>/cologne-germany/)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="instagram-thumb-size">
                                            <option value='Thumbnail' <?php selected( $base->getVar($grid['postparams'], 'instagram-thumb-size', 'Low Resolution'), 'Thumbnail');?>><?php _e('Thumbnail (150px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Low Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-thumb-size', 'Low Resolution'), 'Low Resolution');?>><?php _e('Low Resolution (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Standard Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-thumb-size', 'Low Resolution'), 'Standard Resolution');?>><?php _e('Standard Resolution (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Original Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-thumb-size', 'Standard Resolution'), 'Original Resolution');?>><?php _e('Original Resolution (custom)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="instagram-full-size">
                                            <option value='Thumbnail' <?php selected( $base->getVar($grid['postparams'], 'instagram-full-size', 'Standard Resolution'), 'Thumbnail');?>><?php _e('Thumbnail (150px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Low Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-full-size', 'Standard Resolution'), 'Low Resolution');?>><?php _e('Low Resolution (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Standard Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-full-size', 'Standard Resolution'), 'Standard Resolution');?>><?php _e('Standard Resolution (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Original Resolution' <?php selected( $base->getVar($grid['postparams'], 'instagram-full-size', 'Standard Resolution'), 'Original Resolution');?>><?php _e('Original Resolution (custom)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Display this number of photos', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'instagram-count', '12'); ?>" name="instagram-count">
                                        <!--span class="description"><?php _e(' In <a href="https://www.instagram.com/developer/sandbox/" target="_blank">Sandbox mode</a> the max number is 20.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span-->
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'instagram-transient-sec', '86400'); ?>" name="instagram-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_instagram"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="instagram-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Please use no cache smaller than 1800 seconds or Instagram might ban your IP temporarily.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div><!-- End Instagram Stream -->

                        <div id="flickr-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in your Flickr API Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Flickr API Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input style="margin-right:10px" type="text" value="<?php echo $base->getVar($grid['postparams'], 'flickr-api-key', ''); ?>" name="flickr-api-key">
                                        <span class="description"><?php _e('Read <a target="_blank" href="http://weblizar.com/get-flickr-api-key/">here</a> how to get your Flickr API key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the flickr streaming source?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <span class="inplabel"><input type="radio" name="flickr-type" value="publicphotos" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'flickr-type', 'publicphotos'), 'publicphotos'); ?>> <?php _e('User Public Photos', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <span class="inplabel"><input type="radio" name="flickr-type" value="photosets" <?php checked($base->getVar($grid['postparams'], 'flickr-type', 'publicphotos'), 'photosets'); ?>> <?php _e('User Photoset', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <span class="inplabel"><input type="radio" name="flickr-type" value="gallery" <?php checked($base->getVar($grid['postparams'], 'flickr-type', 'publicphotos'), 'gallery'); ?>> <?php _e('Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <span class="inplabel"><input type="radio" name="flickr-type" value="group" <?php checked($base->getVar($grid['postparams'], 'flickr-type', 'publicphotos'), 'group'); ?>> <?php _e('Groups\' Photos', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <div id="adamlabsgallery-external-source-flickr-sources">
                                        <div id="adamlabsgallery-external-source-flickr-publicphotos-url-wrap">
                                            <p>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put the URL of the flickr User', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Flickr User Url', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'flickr-user-url'); ?>" name="flickr-user-url">
                                            </p>
                                        </div>
                                        <div id="adamlabsgallery-external-source-flickr-photosets-wrap">
                                            <p>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the photoset you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Photoset', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <input type="hidden" name="flickr-photoset" value="<?php echo $base->getVar($grid['postparams'], 'flickr-photoset', ''); ?>">
                                                <select name="flickr-photoset-select">
                                                </select>
                                            </p>
                                        </div>
                                        <div id="adamlabsgallery-external-source-flickr-gallery-url-wrap">
                                            <p>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put the URL of the flickr Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Flickr Gallery Url', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'flickr-gallery-url'); ?>" name="flickr-gallery-url">
                                            </p>
                                        </div>
                                        <div id="adamlabsgallery-external-source-flickr-group-url-wrap">
                                            <p>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put the URL of the flickr Group', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Flickr Group Url', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'flickr-group-url'); ?>" name="flickr-group-url">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="flickr-thumb-size">
                                            <option value='Square' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Square');?>><?php _e('Square (75px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Large Square' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Large Square');?>><?php _e('Large Square (150px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Thumbnail' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Thumbnail');?>><?php _e('Thumbnail (100px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Small' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Small');?>><?php _e('Small (240px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Small 320' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Small 320');?>><?php _e('Small (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Medium');?>><?php _e('Medium (500px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium 640' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Medium 640');?>><?php _e('Medium (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium 800' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Medium 800');?>><?php _e('Medium (800px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Large' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Large');?>><?php _e('Large (1024px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Original' <?php selected( $base->getVar($grid['postparams'], 'flickr-thumb-size', 'Small 320'), 'Original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="flickr-full-size">
                                            <option value='Square' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Square');?>><?php _e('Square (75px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Large Square' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Large Square');?>><?php _e('Large Square (150px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Thumbnail' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Thumbnail');?>><?php _e('Thumbnail (100px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Small' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Small');?>><?php _e('Small (240px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Small 320' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Small 320');?>><?php _e('Small (320px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Medium');?>><?php _e('Medium (500px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium 640' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Medium 640');?>><?php _e('Medium (640px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Medium 800' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Medium 800');?>><?php _e('Medium (800px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Large' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Large');?>><?php _e('Large (1024px)', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='Original' <?php selected( $base->getVar($grid['postparams'], 'flickr-full-size', 'Medium 800'), 'Original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Stream this number of photos', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'flickr-count', '12'); ?>" name="flickr-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'flickr-transient-sec', '86400'); ?>" name="flickr-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_flickr"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="flickr-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div><!-- End Flickr Stream -->

                        <div id="facebook-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Facebook app id', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('App ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'facebook-app-id', '') ?>" name="facebook-app-id">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Facebook app secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('App Secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" style="margin-right:10px" value="<?php echo $base->getVar($grid['postparams'], 'facebook-app-secret', '') ?>" name="facebook-app-secret">
                                        <span class="description"><?php _e('Please <a target="_blank" href="https://developers.facebook.com/docs/apps/register">register</a> your Website app with Facebook to get these values.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <?php $facebook_page_url = $base->getVar($grid['postparams'], 'facebook-page-url', ''); ?>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the URL/ID of the Facebook page', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Facebook Page', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input style="margin-right:10px"  type="text" value="<?php echo $facebook_page_url; ?>" name="facebook-page-url" id="adamlabsgallery-facebook-page-url">
                                        <span class="description"><?php _e('Please enter the Page Name of a public Facebook Page (no personal profile).', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Display a pages photo album or timeline', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="facebook-type-source" value="album" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'facebook-type-source', 'timeline'), 'album'); ?> > <span class="inplabel"><?php _e('Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="facebook-type-source" value="timeline" <?php checked($base->getVar($grid['postparams'], 'facebook-type-source', 'timeline'), 'timeline'); ?> > <span class="inplabel"><?php _e('Timeline', ADAMLABS_GALLERY_TEXTDOMAIN); ?>	</span>
                                    </p>
                                    <div id="adamlabsgallery-external-source-facebook-album-wrap">
                                        <p>
                                            <?php $facebook_album = $base->getVar($grid['postparams'], 'facebook-album', '');?>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the album you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="hidden" name="facebook-album" value="<?php echo $facebook_album; ?>">
                                            <select name="facebook-album-select">
                                            </select>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="divider1"></div>

                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Stream this number of posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'facebook-count', '12'); ?>" name="facebook-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" style="margin-right:10px" value="<?php echo $base->getVar($grid['postparams'], 'facebook-transient-sec', '86400'); ?>" name="facebook-transient-sec"><a style="margin-right:10px" id="clear_cache_facebook"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="facebook-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div><!-- End Facebook Stream -->

                        <div id="twitter-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in your Twitter Consumer Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Twitter Consumer Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'twitter-consumer-key', ''); ?>" name="twitter-consumer-key">
                                    </p>
                                    <p>
                                        <label  class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in your Twitter Consumer Secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Twitter Consumer Secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'twitter-consumer-secret', ''); ?>" name="twitter-consumer-secret">
                                    </p>
                                    <p>
                                        <label  class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in your Twitter Access Token', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Twitter Access Token', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'twitter-access-token', ''); ?>" name="twitter-access-token" >
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in your Twitter Access Secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Twitter Access Secret', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" style="margin-right:10px" value="<?php echo $base->getVar($grid['postparams'], 'twitter-access-secret', ''); ?>" name="twitter-access-secret">
                                        <span class="description"><?php _e('Please <a target="_blank" href="https://dev.twitter.com/apps">register</a> your application with Twitter to fill the API fields.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Twitter Account to stream from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Twitter Account Name @', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'twitter-user-id', ''); ?>" name="twitter-user-id">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Include or Exclude tweets with no tweetpic inside', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Text Tweets', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" class="firstinput" name="twitter-image-only" value="false" <?php checked($base->getVar($grid['postparams'], 'twitter-image-only', 'true'), 'false'); ?>> <span class="inplabel adamlabsgallery-tooltip-wrap" title="<?php _e('Include text only tweets in stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Include', ADAMLABS_GALLERY_TEXTDOMAIN); ?>	</span>
                                        <input type="radio" name="twitter-image-only" value="true" <?php checked($base->getVar($grid['postparams'], 'twitter-image-only', 'true'), 'true'); ?>> <span class='inplabel adamlabsgallery-tooltip-wrap' title="<?php _e('Exclude text only tweets from stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Exclude', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Exclude or Include retweets in stream?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Retweets', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="twitter-include-retweets" value="on" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'twitter-include-retweets', 'on'), 'on'); ?>> <span class="inplabel adamlabsgallery-tooltip-wrap" title="<?php _e('Include retweets in stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Include', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="twitter-include-retweets" value="off" <?php checked($base->getVar($grid['postparams'], 'twitter-include-retweets', 'on'), 'off'); ?>> <span class="inplabel adamlabsgallery-tooltip-wrap" title="<?php _e('Exclude retweets from stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Exclude', ADAMLABS_GALLERY_TEXTDOMAIN); ?>	</span>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Exclude or Include replies in stream?', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Replies', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="twitter-exclude-replies" value="off" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'twitter-exclude-replies', 'on'), 'off'); ?>> <span class="inplabel adamlabsgallery-tooltip-wrap" title="<?php _e('Include replies in stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Include', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="twitter-exclude-replies" value="on" <?php checked($base->getVar($grid['postparams'], 'twitter-exclude-replies', 'on'), 'on'); ?>> <span class="inplabel adamlabsgallery-tooltip-wrap" title="<?php _e('Exclude replies from stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Exclude', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Stream this number of posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'twitter-count', '12'); ?>" name="twitter-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'twitter-transient-sec', '86400'); ?>" name="twitter-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_twitter"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="twitter-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div><!-- End Twitter Stream -->

                        <div id="behance-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the Behance API key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('API Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'behance-api', ''); ?>" name="behance-api" id="behance-api">
                                    <div class="adamlabs-clearfix"></div>
                                    <span class="description"><?php _e('Register your app to receive an API key <a target="_blank" href="https://www.behance.net/dev">here</a>', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Stream', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label  class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the ID of the Behance channel', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Behance User ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" style="margin-right:10px" value="<?php echo $base->getVar($grid['postparams'], 'behance-user-id', ''); ?>" name="behance-user-id" id="behance-user-id">
                                        <span class="description"><?php _e('Find the Behance User ID in the URL of her/his projects page.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Source of Behance Images', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Show', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="radio" name="behance-type" value="projects" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'behance-type', 'projects'), 'projects'); ?>><span class="inplabel"><?php _e('Projects Overview', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        <input type="radio" name="behance-type" value="project" <?php checked($base->getVar($grid['postparams'], 'behance-type', 'overview'), 'project'); ?>><span class="inplabel"><?php _e('Single Project', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                    <div id="adamlabsgallery-external-source-behance-project-wrap">
                                        <p>
                                            <?php $behance_project = $base->getVar($grid['postparams'], 'behance-project', '');?>
                                            <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the project you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select project', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                            <input type="hidden" name="behance-project" value="<?php echo $behance_project; ?>">
                                            <select name="behance-project-select" id="behance-project-select">
                                            </select>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="divider1"></div>

                            <div id="adamlabsgallery-external-source-behance-projects-images-wrap" class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="behance-projects-thumb-size">
                                            <option value='115' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-thumb-size', '202'), '115');?>><?php _e('115px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='202' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-thumb-size', '202'), '202');?>><?php _e('202px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='230' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-thumb-size', '202'), '230');?>><?php _e('230px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='404' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-thumb-size', '202'), '404');?>><?php _e('404px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-thumb-size', '202'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="behance-projects-full-size">
                                            <option value='115' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-full-size', '202'), '115');?>><?php _e('115px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='202' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-full-size', '202'), '202');?>><?php _e('202px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='230' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-full-size', '202'), '230');?>><?php _e('230px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='404' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-full-size', '202'), '404');?>><?php _e('404px wide', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'behance-projects-full-size', '202'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div id="adamlabsgallery-external-source-behance-project-images-wrap" class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="behance-project-thumb-size">
                                            <option value='disp' <?php selected( $base->getVar($grid['postparams'], 'behance-project-thumb-size', 'max_1240'), 'disp');?>><?php _e('Disp', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='max_1200' <?php selected( $base->getVar($grid['postparams'], 'behance-project-thumb-size', 'max_1240'), 'max_1200');?>><?php _e('Max. 1200px', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='max_1240' <?php selected( $base->getVar($grid['postparams'], 'behance-project-thumb-size', 'max_1240'), 'max_1240');?>><?php _e('Max. 1240px', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'behance-project-thumb-size', 'max_1240'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="behance-project-full-size">
                                            <option value='disp' <?php selected( $base->getVar($grid['postparams'], 'behance-project-full-size', 'max_1240'), 'disp');?>><?php _e('Disp', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='max_1200' <?php selected( $base->getVar($grid['postparams'], 'behance-project-full-size', 'max_1240'), 'max_1200');?>><?php _e('Max. 1200px', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='max1240' <?php selected( $base->getVar($grid['postparams'], 'behance-project-full-size', 'max_1240'), 'max_1240');?>><?php _e('Max. 1240px', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'behance-project-full-size', 'max_1240'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>

                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Details', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Stream this number of posts', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Count', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'behance-count', '12'); ?>" name="behance-count">
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Keep stream result cached (recommended)', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Stream Cache (sec)', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p class="cachenumbercheck">
                                        <input type="number" value="<?php echo $base->getVar($grid['postparams'], 'behance-transient-sec', '86400'); ?>" name="behance-transient-sec">&nbsp;<a style="margin-right:10px" id="clear_cache_behance"  class="button-primary adamlabsgallery-clear-cache" href="javascript:void(0);" data-clear="behance-transient-sec">Clear Cache</a>
                                        <span style="margin-left:10px" class="importantlabel showonsmallcache description"><?php _e('Small cache intervals may influence the loading times negatively.', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div> <!-- End behance Stream -->

                        <div id="dribbble-external-stream-wrap">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('API', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Put in the dribbble API key', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('API Key', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        <input type="text" value="<?php echo $base->getVar($grid['postparams'], 'dribbble-api', ''); ?>" name="dribbble-api" id="dribbble-api">
                                    <div class="adamlabs-clearfix"></div>
                                    <span class="description"><?php _e('Find information about the dribbble API key <a target="_blank" href="https://developers.google.com/dribbble/v3/getting-started#before-you-start">here</a>', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div>

                    </div>
                    <?php
                    if(array_key_exists('nggdb', $GLOBALS) ){
                        $nextgen = new AdamLabsGallery_Nextgen(); ?>
                        <div id="all-nextgen-wrap">
                            <div id="nextgen-source-wrap">
                                <div class="adamlabsgallery-creative-settings">
                                    <div class="adamlabsgallery-cs-tbc-left">
                                        <h3><span><?php _e('NextGen', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                    </div>
                                    <div class="adamlabsgallery-cs-tbc">
                                        <p>
                                            <label for="shortcode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose source of grid items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        </p>
                                        <p id="adamlabsgallery-source-choose-wrapper">
                                            <input type="radio" name="nextgen-source-type" value="gallery" class="firstinput" <?php checked($base->getVar($grid['postparams'], 'nextgen-source-type', 'gallery'), 'gallery'); ?>><span class="inplabel"><?php _e('Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                            <input type="radio" name="nextgen-source-type" value="album" <?php checked($base->getVar($grid['postparams'], 'nextgen-source-type', 'gallery'), 'album'); ?>><span class="inplabel"><?php _e('Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                            <input type="radio" name="nextgen-source-type" value="tags" <?php checked($base->getVar($grid['postparams'], 'nextgen-source-type', 'gallery'), 'tags'); ?>><span class="inplabel"><?php _e('Tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="divider1"></div>
                            </div>

                            <div id="adamlabsgallery-nextgen-tags-wrap" class="nextgen-source">
                                <div id="nextgen-source-wrap">
                                    <div class="adamlabsgallery-creative-settings">
                                        <div class="adamlabsgallery-cs-tbc-left">
                                            <h3><span><?php _e('Tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                        </div>
                                        <div class="adamlabsgallery-cs-tbc">
                                            <p>
                                                <?php $nextgen_tags = $base->getVar($grid['postparams'], 'nextgen-tags', '');
                                                $nextgen_tags_list = $nextgen->get_tag_list($nextgen_tags);
                                                ?>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the tags you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Tags', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <select multiple name="nextgen-tags" id="nextgen-tags">
                                                    <?php echo implode("", $nextgen_tags_list); ?>
                                                </select>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="divider1"></div>
                                </div>
                            </div>
                            <div id="adamlabsgallery-nextgen-gallery-wrap" class="nextgen-source">
                                <div id="nextgen-source-wrap">
                                    <div class="adamlabsgallery-creative-settings">
                                        <div class="adamlabsgallery-cs-tbc-left">
                                            <h3><span><?php _e('Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                        </div>
                                        <div class="adamlabsgallery-cs-tbc">
                                            <p>
                                                <?php $nextgen_gallery = $base->getVar($grid['postparams'], 'nextgen-gallery', '');
                                                $nextgen_galleries = $nextgen->get_gallery_list($nextgen_gallery);
                                                ?>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the gallery you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <select name="nextgen-gallery" id="nextgen-gallery">
                                                    <?php echo implode("", $nextgen_galleries); ?>
                                                </select>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="divider1"></div>
                                </div>
                            </div>

                            <div id="adamlabsgallery-nextgen-album-wrap" class="nextgen-source">
                                <div id="nextgen-source-wrap">
                                    <div class="adamlabsgallery-creative-settings">
                                        <div class="adamlabsgallery-cs-tbc-left">
                                            <h3><span><?php _e('Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                        </div>
                                        <div class="adamlabsgallery-cs-tbc">
                                            <p>
                                                <?php $nextgen_album = $base->getVar($grid['postparams'], 'nextgen-album', '');
                                                $nextgen_albums = $nextgen->get_album_list($nextgen_album);
                                                ?>
                                                <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('Select the album you want to pull the data from', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Select Album', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                                <select name="nextgen-album" id="nextgen-album">
                                                    <?php echo implode("", $nextgen_albums); ?>
                                                </select>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="divider1"></div>
                                </div>
                            </div>

                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="nextgen-thumb-size">
                                            <option value='thumb' <?php selected( $base->getVar($grid['postparams'], 'nextgen-thumb-size', 'thumb'), 'thumb');?>><?php _e('Thumb', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'nextgen-thumb-size', 'thumb'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="nextgen-full-size">
                                            <option value='thumb' <?php selected( $base->getVar($grid['postparams'], 'nextgen-full-size', 'thumb'), 'thumb');?>><?php _e('Thumb', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                            <option value='original' <?php selected( $base->getVar($grid['postparams'], 'nextgen-full-size', 'thumb'), 'original');?>><?php _e('Original', ADAMLABS_GALLERY_TEXTDOMAIN);?></option>
                                        </select>
                                    </p>
                                </div>

                            </div>
                            <div class="divider1"></div>

                        </div>
                    <?php }

                    if( function_exists("wp_rml_dropdown") ){
                        $rml_items = wp_rml_dropdown($base->getVar($grid['postparams'], 'rml-source-type', '-1'),array(RML_TYPE_COLLECTION),true); ?>
                        <div id="all-rml-wrap">
                            <div id="rml-source-wrap">
                                <div class="adamlabsgallery-creative-settings">
                                    <div class="adamlabsgallery-cs-tbc-left">
                                        <h3><span><?php _e('Real Media Library', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                    </div>
                                    <div class="adamlabsgallery-cs-tbc">
                                        <p>
                                            <label for="shortcode" class="adamlabsgallery-tooltip-wrap" title="<?php _e('Choose source of grid items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                        </p>
                                        <p id="adamlabsgallery-source-choose-wrapper">
                                            <select id="rml-source-type" name="rml-source-type"><?php echo $rml_items; ?></select><span class="inplabel"> <?php _e('Select Folder or Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="divider1"></div>
                            </div>

                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Image Sizes', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc">
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the Grid Items', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Grid Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="rml-thumb-size">
                                            <?php echo AdamLabsGallery_Rml::option_list_image_sizes($base->getVar($grid['postparams'], 'rml-thumb-size', 'original')); ?>
                                        </select>
                                    </p>
                                    <p>
                                        <label class="adamlabsgallery-new-label adamlabsgallery-tooltip-wrap" title="<?php _e('For images that appear inside the lightbox, links, etc.', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Full Image Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                    </p>
                                    <p>
                                        <select name="rml-full-size">
                                            <?php echo AdamLabsGallery_Rml::option_list_image_sizes($base->getVar($grid['postparams'], 'rml-full-size', 'original')); ?>
                                        </select>
                                    </p>
                                </div>

                            </div>
                            <div class="divider1"></div>
                        </div>
                    <?php } ?>
                    <?php do_action('adamlabsgallery_grid_source_options',$base,$grid); ?>

                    <div id="media-source-order-wrap">
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Media Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc" style="padding:15px; min-width:310px; width:310px">
                                <div class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set default order of used media', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Item Media Source Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>

                                <div id="imso-list" class="adamlabsgallery-media-source-order-wrap" style="height:440px;margin-top:10px;">
                                    <?php
                                    if(!empty($media_source_order)){
                                        foreach($media_source_order as $media_handle){
                                            if(!isset($media_source_list[$media_handle])) continue;
                                            ?>
                                            <div id="imso-<?php echo $media_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
                                                <i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $media_source_list[$media_handle]['type']; ?>"></i>
                                                <span style="float:left"><?php echo $media_source_list[$media_handle]['name']; ?></span>
                                                <input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="media-source-order[]" checked="checked" value="<?php echo $media_handle; ?>" />
                                                <div style="clear:both"></div>
                                            </div>
                                            <?php
                                            unset($media_source_list[$media_handle]);
                                        }
                                    }

                                    if(!empty($media_source_list)){
                                        foreach($media_source_list as $media_handle => $media_set){
                                            ?>
                                            <div id="imso-<?php echo $media_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
                                                <i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $media_set['type']; ?>"></i>
                                                <span style="float:left"><?php echo $media_set['name']; ?></span>
                                                <input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="media-source-order[]" value="<?php echo $media_handle; ?>" />
                                                <div style="clear:both"></div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                            </div>

                            <div id="poster-media-source-container" class="adamlabsgallery-cs-tbc" style="padding:15px;min-width:310px; width:100%">
                                <div class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default order of Poster Image Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Optional Audio/Video Image Order', ADAMLABS_GALLERY_TEXTDOMAIN); ?></div>
                                <div id="pso-list" class="adamlabsgallery-media-source-order-wrap" style="height:440px;margin-top:10px;">
                                    <?php
                                    if(!empty($poster_source_order)){
                                        foreach($poster_source_order as $poster_handle){
                                            if(!isset($poster_source_list[$poster_handle])) continue;
                                            ?>
                                            <div id="pso-<?php echo $poster_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
                                                <i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $poster_source_list[$poster_handle]['type']; ?>"></i>
                                                <span style="float:left"><?php echo $poster_source_list[$poster_handle]['name']; ?></span>
                                                <input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="poster-source-order[]" checked="checked" value="<?php echo $poster_handle; ?>" />
                                                <div style="clear:both"></div>
                                            </div>
                                            <?php
                                            unset($poster_source_list[$poster_handle]);
                                        }
                                    }

                                    if(!empty($poster_source_list)){
                                        foreach($poster_source_list as $poster_handle => $poster_set){
                                            ?>
                                            <div id="pso-<?php echo $poster_handle; ?>" class="adamlabsgallery-media-source-order button-primary">
                                                <i style="float:left; margin-right:10px;" class="adamlabsgallery-icon-<?php echo $poster_set['type']; ?>"></i>
                                                <span style="float:left"><?php echo $poster_set['name']; ?></span>
                                                <input style="float:right;margin: 5px 4px 0 0;" class="adamlabsgallery-get-val" type="checkbox" name="poster-source-order[]" value="<?php echo $poster_handle; ?>" />
                                                <div style="clear:both"></div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div style="margin-left:150px; padding-left:15px; padding-bottom:15px;margin-top:0px;background:#fff">
                            <?php _e('First Media Source will be loaded as default. In case one source does not exist, next available media source in this order will be used', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
                        </div>

                        <div class="divider1"></div>
                    </div>



                    <div id="media-source-sizes">
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Source Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">

                                <div>
                                    <!-- DEFAULT IMAGE SOURCE -->
                                    <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Desktop Grid Image Source Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Desktop Image Source Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>

                                    <div style="margin-bottom: 10px;">
                                        <?php
                                        $image_source_type = $base->getVar($grid['postparams'], 'image-source-type', 'full');
                                        ?>
                                        <select name="image-source-type">
                                            <?php
                                            foreach($all_image_sizes as $handle => $name){
                                                ?>
                                                <option <?php selected($image_source_type, $handle); ?> value="<?php echo $handle; ?>"><?php echo $name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div>
                                    <!-- DEFAULT IMAGE SOURCE -->
                                    <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Mobile Grid Image Source Size', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Mobile Image Source Type', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>

                                    <div style="margin-bottom: 10px;">
                                        <?php
                                        /* 2.1.6 */
                                        $image_source_type = $base->getVar($grid['postparams'], 'image-source-type-mobile', $image_source_type);
                                        ?>
                                        <select name="image-source-type-mobile">
                                            <?php
                                            foreach($all_image_sizes as $handle => $name){
                                                ?>
                                                <option <?php selected($image_source_type, $handle); ?> value="<?php echo $handle; ?>"><?php echo $name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="divider1"></div>
                    </div>
                    <?php $enable_media_filter = get_option('adamlabsgallery_enable_media_filter', 'false');
                    if ($enable_media_filter!="false"){ ?>
                        <div id="media-source-filter">
                            <div class="adamlabsgallery-creative-settings">
                                <div class="adamlabsgallery-cs-tbc-left">
                                    <h3><span><?php _e('Media Filter', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                                </div>
                                <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">


                                    <div style="display:none; margin-bottom: 10px;">
                                        <?php
                                        $media_filter_type = $base->getVar($grid['postparams'], 'media-filter-type', 'none');
                                        ?>
                                        <select id="media-filter-type" name="media-filter-type">
                                            <?php
                                            foreach($all_media_filters as $handle => $name){
                                                ?>
                                                <option <?php selected($media_filter_type, $handle); ?> value="<?php echo $handle; ?>"><?php echo $name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="inst-filter-grid">
                                        <?php
                                        foreach($all_media_filters as $handle => $name){
                                            $selected = $media_filter_type === $handle ? "selected" : "";
                                            ?>
                                            <div data-type="<?php echo $handle; ?>" class="inst-filter-griditem <?php echo $selected; ?>"><div class="ifgname"><?php echo $name; ?></div><div class="inst-filter-griditem-img <?php echo $handle; ?>"></div><div class="inst-filter-griditem-img-noeff"></div></div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="divider1"></div>
                        </div>
                    <?php } ?>
                    <div id="media-source-default-templates">
                        <div class="adamlabsgallery-creative-settings">
                            <div class="adamlabsgallery-cs-tbc-left">
                                <h3><span><?php _e('Default Source', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                            </div>
                            <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
                                <div  style="float:left">
                                    <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Image will be used if no criteria are matching so a default image will be shown', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Default Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                                </div>
                                <div style="float:left; margin-bottom: 10px;">
                                    <div>
                                        <?php

                                        $default_img = $base->getVar($grid['postparams'], 'default-image', 0, 'i');

                                        /*
                                        if($default_img === 0) {
                                            $default_global_img = get_option('adamlabsgallery_global_default_img', '');
                                            $default_img = !empty($default_global_img) ? intval($default_global_img) : 0;
                                        }
                                        */

                                        $var_src = '';
                                        if($default_img > 0){
                                            $img = wp_get_attachment_image_src($default_img, 'full');
                                            if($img !== false){
                                                $var_src = $img[0];
                                            }
                                        }
                                        ?>
                                        <img id="adamlabsgallery-default-image-img" class="image-holder-wrap-div" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?> />
                                    </div>
                                    <a class="button-primary adamlabsgallery-default-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-default-image"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                    <a class="button-primary adamlabsgallery-default-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-default-image"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                    <input type="hidden" name="default-image" value="<?php echo $default_img; ?>" id="adamlabsgallery-default-image" />
                                </div>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                    </div>

                    <div class="adamlabsgallery-creative-settings default-posters notavailable" id="adamlabsgallery-youtube-default-poster">

                        <div class="divider1"></div>
                        <div class="adamlabsgallery-cs-tbc-left">
                            <h3 class="box-closed"><span><?php _e('YouTube Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                        </div>
                        <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
                            <div  style="float:left">
                                <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default posters for the different video sources', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Default Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                            </div>
                            <div style="float:left; margin-bottom: 10px;">
                                <div>
                                    <?php
                                    $youtube_default_img = $base->getVar($grid['postparams'], 'youtube-default-image', 0, 'i');
                                    $var_src = '';
                                    if($youtube_default_img > 0){
                                        $youtube_img = wp_get_attachment_image_src($youtube_default_img, 'full');
                                        if($youtube_img !== false){
                                            $var_src = $youtube_img[0];
                                        }
                                    }
                                    ?>
                                    <img id="adamlabsgallery-youtube-default-image-img" class="image-holder-wrap-div" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?> />
                                </div>
                                <a class="button-primary adamlabsgallery-youtube-default-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-youtube-default-image"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <a class="button-primary adamlabsgallery-youtube-default-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-youtube-default-image"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <input type="hidden" name="youtube-default-image" value="<?php echo $youtube_default_img; ?>" id="adamlabsgallery-youtube-default-image" />
                            </div>
                        </div>
                    </div>

                    <div class="adamlabsgallery-creative-settings default-posters notavailable" id="adamlabsgallery-vimeo-default-poster">
                        <div class="divider1"></div>
                        <div class="adamlabsgallery-cs-tbc-left">
                            <h3 class="box-closed"><span><?php _e('Vimeo Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                        </div>
                        <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
                            <div  style="float:left">
                                <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default posters for the different video sources', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Default Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                            </div>
                            <div style="float:left; margin-bottom: 10px;">
                                <div>
                                    <?php
                                    $vimeo_default_img = $base->getVar($grid['postparams'], 'vimeo-default-image', 0, 'i');
                                    $var_src = '';
                                    if($vimeo_default_img > 0){
                                        $vimeo_img = wp_get_attachment_image_src($vimeo_default_img, 'full');
                                        if($vimeo_img !== false){
                                            $var_src = $vimeo_img[0];
                                        }
                                    }
                                    ?>
                                    <img id="adamlabsgallery-vimeo-default-image-img" class="image-holder-wrap-div" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?> />
                                </div>
                                <a class="button-primary adamlabsgallery-vimeo-default-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-vimeo-default-image"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <a class="button-primary adamlabsgallery-vimeo-default-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-vimeo-default-image"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <input type="hidden" name="vimeo-default-image" value="<?php echo $vimeo_default_img; ?>" id="adamlabsgallery-vimeo-default-image" />
                            </div>
                            <div style="clear:both"></div>
                        </div>

                    </div>

                    <div class="adamlabsgallery-creative-settings default-posters notavailable" id="adamlabsgallery-html5-default-poster">

                        <div class="adamlabsgallery-cs-tbc-left">
                            <h3 class="box-closed"><span><?php _e('HTML5 Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></span></h3>
                        </div>
                        <div class="adamlabsgallery-cs-tbc" style="padding-top:15px">
                            <div  style="float:left">
                                <label class="adamlabsgallery-tooltip-wrap" title="<?php _e('Set the default posters for the different video sources', ADAMLABS_GALLERY_TEXTDOMAIN); ?>"><?php _e('Default Poster', ADAMLABS_GALLERY_TEXTDOMAIN); ?></label>
                            </div>
                            <div style="float:left; margin-bottom: 10px;">
                                <div>
                                    <?php
                                    $html_default_img = $base->getVar($grid['postparams'], 'html-default-image', 0, 'i');
                                    $var_src = '';
                                    if($html_default_img > 0){
                                        $html_img = wp_get_attachment_image_src($html_default_img, 'full');
                                        if($html_img !== false){
                                            $var_src = $html_img[0];
                                        }
                                    }
                                    ?>
                                    <img id="adamlabsgallery-html-default-image-img" class="image-holder-wrap-div" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?> />
                                </div>
                                <a class="button-primary adamlabsgallery-html-default-image-add" href="javascript:void(0);" data-setto="adamlabsgallery-html-default-image"><?php _e('Choose Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <a class="button-primary adamlabsgallery-html-default-image-clear" href="javascript:void(0);" data-setto="adamlabsgallery-html-default-image"><?php _e('Remove Image', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
                                <input type="hidden" name="html-default-image" value="<?php echo $html_default_img; ?>" id="adamlabsgallery-html-default-image" />
                            </div>
                            <div style="clear:both"></div>

                        </div>
                    </div>
                    <div id="gallery-wrap"></div>
                </form>
            </div>
        </div>
        <div class="divider1"></div>

    </div>
</div>
