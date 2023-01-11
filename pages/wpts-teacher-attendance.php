<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
		if($current_user_role=='administrator' || $current_user_role=='teacher') {	?>
		<div class="wpts-card">
			<div class="wpts-row">
				<div class="wpts-col-md-12">
						<div class="wpts-card-head">
							<h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_teacher_attendance_heading_item',esc_html("Teacher Attendance","wptopschool")); ?></h3>
						</div>
						<div class="wpts-card-body">
							<div class="wpts-row">
								<div class="wpts-col-md-3" id="AttendanceEnterForm">
										<div class="wpts-form-group">
											<label class="control-label"><?php _e( 'Date', 'wptopschool' ); ?> </label>
											<input type="text" class="wpts-form-control select_date" id="AttendanceDate" value="<?php echo isset($_POST['entry_date'])? esc_attr($_POST['entry_date']) : date('m/d/Y'); ?>" name="entry_date">
										</div>
										<div class="wpts-form-group">
											<?php if($current_user_role=='administrator'){?>
										<button id="AttendanceEnter" name="attendance" class="wpts-btn wpts-btn-success"><?php _e( 'Add', 'wptopschool'); ?></button>
									<?php }?>
										<button id="AttendanceView" name="attendanceview" class="wpts-btn wpts-btn-primary"><?php _e( 'View', 'wptopschool'); ?></button>
									</div>
								</div>
							</div>
							<div class="wpts-row">
								<div class="wpts-col-lg-12 wpts-col-md-12 Attendance-Overview MTTen">
									<div class="AttendanceContent">
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
		<?php if($current_user_role=='administrator'){?>
		<div class="modal modal-wide" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="AddModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="AddModalContent">
				</div>
			</div>
		</div><!-- /.modal -->
		<?php	}
		} else {
			echo esc_html("No access to this page","wptopschool");
		}
		wpts_body_end();
		wpts_footer();
	} else{
		include_once( WPTS_PLUGIN_PATH.'/includes/wpts-login.php');
	}
?>
