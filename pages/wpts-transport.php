<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
		?>
		<div class="wpts-card">
			<div class="wpts-card-body">
				<table id="transport_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
					<thead>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Vehicle Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Number', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Phone', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Route Fees', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Route', 'wptopschool' ); ?> </th>
							<?php if($current_user_role=='administrator'){?>
							<th class="nosort" align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
							<?php }?>
						</tr>
					</thead>
					<tbody>
					<?php
					$trans_table=$wpdb->prefix."wpts_transport";
					$wpts_trans =$wpdb->get_results("select * from $trans_table");
					$sno=1;
					foreach ($wpts_trans as $wpts_tran){ ?>
						<tr>
							<td><?php echo  esc_html($sno);?></td>
							<td><?php echo  esc_html($wpts_tran->bus_name); ?></td>
							<td><?php echo  esc_html($wpts_tran->bus_no);?> </td>
							<td><?php echo  esc_html($wpts_tran->driver_name); ?></td>
							<td><?php echo  esc_html($wpts_tran->phone_no);?></td>
							<td><?php echo  esc_html($wpts_tran->route_fees);?></td>
							<td><?php echo  esc_html($wpts_tran->bus_route);?></td>
							<?php if($current_user_role=='administrator'){?>
							<td align="center">
								<div class="wpts-action-col">
									<a href="javascript:;" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" class="ViewTrans  wpts-popclick" data-pop="ViewModal" title="View">
									<i class="icon wpts-view wpts-view-icon"></i></a>
									<a href="javascript:;" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" class="EditTrans wpts-popclick" data-pop="ViewModal" title="Edit">
									<i class="icon wpts-edit wpts-edit-icon"></i></a>
									<a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" >
	                                <i class="icon wpts-trash wpts-delete-icon" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>"></i></a>
								</div>
							</td>
							<?php }?>
						</tr>
					<?php
						$sno++;
					}
					?>
					</tbody>
					<tfoot>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Vehicle Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Number', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Phone', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Route Fees', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Route', 'wptopschool' ); ?> </th>
							<?php if($current_user_role=='administrator'){?>
							<th class="nosort" align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
							<?php }?>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<!--Info Modal-->
		<div class="wpts-popupMain" id="ViewModal">
			<div class="wpts-overlayer"></div>
			<div class="wpts-popBody">
				<div class="wpts-popInner">
					<a href="javascript:;" class="wpts-closePopup"></a>
					<div id="ViewModalContent" class="wpts-text-left"></div>
				</div>
			</div>
		</div>
		<?php
			wpts_body_end();
			wpts_footer();
		}
		else if($current_user_role=='parent' || $current_user_role=='student' )
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			?>
			<div class="wpts-card">
			<div class="wpts-card-body">
				<table id="transport_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
					<thead>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Vehicle Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Number', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Phone', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Route Fees', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Route', 'wptopschool' ); ?> </th>
							<?php if($current_user_role=='administrator'){?>
							<th class="nosort" align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
							<?php }?>
						</tr>
					</thead>
					<tbody>
					<?php
					$trans_table=$wpdb->prefix."wpts_transport";
					$wpts_trans =$wpdb->get_results("select * from $trans_table");
					$sno=1;
					foreach ($wpts_trans as $wpts_tran){ ?>
						<tr>
							<td><?php echo  esc_html($sno);?></td>
							<td><?php echo  esc_html($wpts_tran->bus_name); ?></td>
							<td><?php echo  esc_html($wpts_tran->bus_no);?> </td>
							<td><?php echo  esc_html($wpts_tran->driver_name); ?></td>
							<td><?php echo  esc_html($wpts_tran->phone_no);?></td>
							<td><?php echo  esc_html($wpts_tran->route_fees);?></td>
							<td><?php echo  esc_html($wpts_tran->bus_route);?></td>
							<?php if($current_user_role=='administrator'){?>
							<td align="center">
								<div class="wpts-action-col">
									<a href="javascript:;" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" class="ViewTrans  wpts-popclick" data-pop="ViewModal" title="View">
									<i class="icon wpts-view wpts-view-icon"></i></a>
									<a href="javascript:;" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" class="EditTrans wpts-popclick" data-pop="ViewModal" title="Edit">
									<i class="icon wpts-edit wpts-edit-icon"></i></a>
									<a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>" >
	                                <i class="icon wpts-trash wpts-delete-icon" data-id="<?php echo esc_attr(intval($wpts_tran->id));?>"></i></a>
								</div>
							</td>
							<?php }?>
						</tr>
					<?php
						$sno++;
					}
					?>
					</tbody>
					<tfoot>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Vehicle Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Number', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Name', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Driver Phone', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Route Fees', 'wptopschool' ); ?></th>
							<th><?php esc_html_e( 'Vehicle Route', 'wptopschool' ); ?> </th>
							<?php if($current_user_role=='administrator'){?>
							<th class="nosort" align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
							<?php }?>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<!--Info Modal-->
		<div class="wpts-popupMain" id="ViewModal">
			<div class="wpts-overlayer"></div>
			<div class="wpts-popBody">
				<div class="wpts-popInner">
					<a href="javascript:;" class="wpts-closePopup"></a>
					<div id="ViewModalContent" class="wpts-text-left"></div>
				</div>
			</div>
		</div>
			<?php
			wpts_body_end();
			wpts_footer();
		}
	}
	else{
		//Include Login Section
		include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
	}
?>
