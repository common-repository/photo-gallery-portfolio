<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 */

if( !defined( 'ABSPATH') ) exit();

$order = false;
//order=asc&orderby=name
$selected = array('shortcode' => false, 'last_modified' => false, 'favorite' => false, 'name' => false);

$saved_sorting = get_option('adamlabsgallery-current-sorting', array());

if(isset($_GET['orderby']) && isset($_GET['order'])){
	$saved_sorting['orderby'] = esc_attr($_GET['orderby']);
	$saved_sorting['order'] = esc_attr($_GET['order']);
}

if(isset($_GET['limit'])) $saved_sorting['limit'] = esc_attr($_GET['limit']);
if(isset($_GET['pagenum'])) $saved_sorting['pagenum'] = esc_attr($_GET['pagenum']);

update_option('adamlabsgallery-current-sorting', $saved_sorting);


if(isset($saved_sorting['orderby']) && isset($saved_sorting['order'])){
	$order = array();
	switch($saved_sorting['orderby']){
		case 'shortcode':
			$order['handle'] = ($saved_sorting['order'] == 'asc') ? 'ASC' : 'DESC';
			$selected['shortcode'] = ($saved_sorting['order'] == 'desc') ? 'asc' : 'desc';
		break;
		case 'last_modified':
			$order['last_modified'] = ($saved_sorting['order'] == 'asc') ? 'ASC' : 'DESC';
			$selected['last_modified'] = ($saved_sorting['order'] == 'desc') ? 'asc' : 'desc';
		break;
		case 'favorite':
			$order['favorite'] = ($saved_sorting['order'] == 'asc') ? 'ASC' : 'DESC';
			$selected['favorite'] = ($saved_sorting['order'] == 'desc') ? 'asc' : 'desc';
		break;
		case 'name':
		default:
			$order['name'] = ($saved_sorting['order'] == 'asc') ? 'ASC' : 'DESC';
			$selected['name'] = ($saved_sorting['order'] == 'desc') ? 'asc' : 'desc';
		break;
	}
}

$grids = AdamLabsGallery::get_adamlabsgallery_grids($order);

$limit = (@intval($saved_sorting['limit']) > 0) ? @intval($saved_sorting['limit']) : 10;
$otype = 'reg';
$total = 0;

$pagenum = isset( $saved_sorting['pagenum'] ) ? absint( $saved_sorting['pagenum'] ) : 1;
$offset = ( $pagenum - 1 ) * $limit;

$cur_offset = 0;

?>
	<div id="adamlabsgallery-global-settings-wrap" style="float: right; margin-bottom: 10px;">
		<input type="text" id="adamlabsgallery-search-grids" style=" width: 300px; height: 30px;" placeholder="<?php _e('Search Listed Grids', ADAMLABS_GALLERY_TEXTDOMAIN); ?>">
		<div style="clear: both"></div>
	</div>

    <a class='button-primary' href='<?php echo $this->getViewUrl(AdamLabsGallery_Admin::VIEW_GRID_CREATE, 'create=true'); ?>'><?php _e('Create New Portfolio Gallery', ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>

    <div id="adamlabsgallery-grid-overview-wrapper">

		<?php
		
		if(!empty($grids) && is_array($grids)){
			?>
			<table class='wp-list-table widefat fixed adamlabsgallery-postbox'>
				<thead>
					<tr>
						<th width="35px">
							<div class="adamlabsgallery-mini-sort-wrapper">
								<a href="?page=adamlabsgallery&orderby=favorite&order=<?php echo ($selected['favorite'] !== false) ? $selected['favorite'] : 'asc'; ?>" class=" ">
									<i class="adamlabsgallery-icon-down-dir adamlabsgallery-mini-sort-down<?php echo ($selected['favorite'] !== false && $selected['favorite'] == 'desc') ? ' selected' : ''; ?>"></i>
									<i class="adamlabsgallery-icon-up-dir adamlabsgallery-mini-sort-up<?php echo ($selected['favorite'] !== false && $selected['favorite'] == 'asc') ? ' selected' : ''; ?>"></i>
								</a>
							</div>
						</th>
						<th width="20px"><?php _e('ID', ADAMLABS_GALLERY_TEXTDOMAIN); ?></th>
						<th width="20%">
							<?php _e('Name', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<div class="adamlabsgallery-mini-sort-wrapper">
								<a href="?page=adamlabsgallery&orderby=name&order=<?php echo ($selected['name'] !== false) ? $selected['name'] : 'asc'; ?>" class=" ">
									<i class="adamlabsgallery-icon-down-dir adamlabsgallery-mini-sort-down<?php echo ($selected['name'] !== false && $selected['name'] == 'desc') ? ' selected' : ''; ?>"></i>
									<i class="adamlabsgallery-icon-up-dir adamlabsgallery-mini-sort-up<?php echo ($selected['name'] !== false && $selected['name'] == 'asc') ? ' selected' : ''; ?>"></i>
								</a>
							</div>
						</th>
						<th width="22%">
							<?php _e('Shortcode', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<div class="adamlabsgallery-mini-sort-wrapper">
								<a href="?page=adamlabsgallery&orderby=shortcode&order=<?php echo ($selected['shortcode'] !== false) ? $selected['shortcode'] : 'asc'; ?>" class=" ">
									<i class="adamlabsgallery-icon-down-dir adamlabsgallery-mini-sort-down<?php echo ($selected['shortcode'] !== false && $selected['shortcode'] == 'desc') ? ' selected' : ''; ?>"></i>
									<i class="adamlabsgallery-icon-up-dir adamlabsgallery-mini-sort-up<?php echo ($selected['shortcode'] !== false && $selected['shortcode'] == 'asc') ? ' selected' : ''; ?>"></i>
								</a>
							</div>
						</th>
						<th width="30%"><?php _e('Actions', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </th>
						<th width="20%"><?php _e('Settings', ADAMLABS_GALLERY_TEXTDOMAIN); ?> </th>
						<th width="12%">
							<?php _e('Modified', ADAMLABS_GALLERY_TEXTDOMAIN); ?>
							<div class="adamlabsgallery-mini-sort-wrapper">
								<a href="?page=adamlabsgallery&orderby=last_modified&order=<?php echo ($selected['last_modified'] !== false) ? $selected['last_modified'] : 'asc'; ?>" class=" ">
									<i class="adamlabsgallery-icon-down-dir adamlabsgallery-mini-sort-down<?php echo ($selected['last_modified'] !== false && $selected['last_modified'] == 'desc') ? ' selected' : ''; ?>"></i>
									<i class="adamlabsgallery-icon-up-dir adamlabsgallery-mini-sort-up<?php echo ($selected['last_modified'] !== false && $selected['last_modified'] == 'asc') ? ' selected' : ''; ?>"></i>
								</a>
							</div>
						</th>
					</tr>
				</thead>
				<tbody id="adamlabsgallery-grid-list">
				<?php
				foreach($grids as $grid){
					$total++;
					$cur_offset++;
					if($cur_offset <= $offset) continue; //if we are lower then the offset, continue;
					if($cur_offset > $limit + $offset) continue; // if we are higher then the limit + offset, continue
					
					$cur_grid = AdamLabsGallery::get_adamlabsgallery_by_id($grid->id);
					$skin_id = @$cur_grid['params']['entry-skin'];
					?>
					<tr>
						<td><a href="javascript:void(0);" class="adamlabsgallery-toggle-favorite" id="adamlabsgallery-star-id-<?php echo $grid->id; ?>"><i class="adamlabsgallery-icon-star<?php
							echo (isset($cur_grid['settings']['favorite']) && $cur_grid['settings']['favorite'] == 'true') ? '' : '-empty';
						?>"></i></a></td>
						<td><?php echo $grid->id; ?></td>
						<td><?php echo $grid->name; ?></td>
						<td>[adamlabsgallery alias="<?php echo $grid->handle; ?>"]</td>
						<td>
							<div class="btn-wrap-overview-<?php echo $grid->id; ?>">
								<a class="button-primary" href="<?php echo AdamLabsGallery_Base::getViewUrl(AdamLabsGallery_Admin::VIEW_GRID_CREATE, 'create='.$grid->id); ?>"><i class="adamlabsgallery-icon-cog"></i><?php _e("Edit", ADAMLABS_GALLERY_TEXTDOMAIN); ?></a>
								<a class="button-primary" <?php if($GLOBALS['adamlabsgallery_validated'] === 'false'){ ?>onclick="alert('this is a pro feature');return false;" <?php } ?> href="<?php echo ($GLOBALS['adamlabsgallery_validated'] === 'false' ? '' : AdamLabsGallery_Base::getViewUrl(AdamLabsGallery_Admin::VIEW_ITEM_SKIN_EDITOR, 'create='.$skin_id)); ?>" target="_blank"><i class="adamlabsgallery-icon-palette"></i><?php _e("Edit Template", ADAMLABS_GALLERY_TEXTDOMAIN); echo ($GLOBALS['adamlabsgallery_validated'] === 'false' ? '<span style="color:red">(Pro)</span>' : '') ?></a>
								<a class="button adamlabsgallery-btn-delete-grid" id="adamlabsgallery-delete-<?php echo $grid->id; ?>" href="javascript:void(0)"><i class="adamlabsgallery-icon-trash"></i></a>
								<a class="button adamlabsgallery-btn-duplicate-grid" id="adamlabsgallery-duplicate-<?php echo $grid->id; ?>" href="javascript:void(0)"><i class="adamlabsgallery-icon-picture"></i></a>
							</div>
						</td>
						<td>
						<?php
						echo ucfirst($cur_grid['params']['layout']);
						if($cur_grid['params']['layout'] == 'even')
							echo ', '.$cur_grid['params']['x-ratio'].':'.$cur_grid['params']['y-ratio'];
						
						if(isset($cur_grid['postparams']['source-type']))
							echo ', '.ucfirst($cur_grid['postparams']['source-type']);
							
						if(isset($cur_grid['params']['layout-sizing']))
							echo ', '.ucfirst($cur_grid['params']['layout-sizing']);
						?>
						</td>
						<td>
							<?php echo @$cur_grid['last_modified']; ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}else{
			_e('No Galleries found!', ADAMLABS_GALLERY_TEXTDOMAIN);
		}
		?>
	</div>

	<?php
	$num_of_pages = ceil( $total / $limit );
	
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%', '' ),
		'format' => '',
		'add_args' => array('limit' => $limit),
		'prev_text' => __( '&laquo;', ADAMLABS_GALLERY_TEXTDOMAIN ),
		'next_text' => __( '&raquo;', ADAMLABS_GALLERY_TEXTDOMAIN ),
		'total' => $num_of_pages,
		'current' => $pagenum
	) );

	if ( $page_links ) {
		echo '<div class="ess-pagination-wrap"><div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div></div>';
	}
	?>
	<form id="ess-pagination-form" action="?page=adamlabsgallery&pagenum=1" method="GET">
		<input type="hidden" name="page" value="adamlabsgallery" />
		<input type="hidden" name="pagenum" value="1" />
		<select name="limit" onchange="this.form.submit()">
			<option <?php echo ($limit == 10) ? 'selected="selected"' : ''; ?> value="10">10</option>
			<option <?php echo ($limit == 25) ? 'selected="selected"' : ''; ?> value="25">25</option>
			<option <?php echo ($limit == 50) ? 'selected="selected"' : ''; ?> value="50">50</option>
			<option <?php echo ($limit == 9999) ? 'selected="selected"' : ''; ?> value="9999"><?php _e('All', ADAMLABS_GALLERY_TEXTDOMAIN); ?></option>
		</select>
	</form>
	<?php
	require_once('elements/grid-info.php');
	?>
	
	<script type="text/javascript">
		AdminEssentials.initOverviewGrid();
	</script>