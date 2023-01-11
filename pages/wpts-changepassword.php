<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
?>
<div id="message_response"></div>
<form class="form-horizontal group-border-dashed" action="" id="changepassword">
	<div class="wpts-card">
		<div class="wpts-card-head">
			<h3 class="wpts-card-title"><?php esc_html_e( 'Change Password', 'wptopschool' )?></h3>
		</div>

		<div class="wpts-card-body">
				<div class="wpts-row">
					<div class="wpts-col-md-3">
						<div class="wpts-form-group">
							<label class="wpts-label"><?php _e( 'Current Password', 'wptopschool' ); ?></label>
							<input class="wpts-form-control" name="oldpw" id="oldpw" type="password" required>
						</div>
					</div>
				</div>

				<div class="wpts-row">
					<div class="wpts-col-md-3">
						<div class="wpts-form-group">
							<label class="wpts-label"><?php _e( 'New Password', 'wptopschool' ); ?></label>
							<input class="wpts-form-control" name="newpw" id="newpw" type="password" required>
						</div>
					</div>
				</div>

				<div class="wpts-row">
					<div class="wpts-col-md-3">
						<div class="wpts-form-group">
							<label class="wpts-label"><?php _e( 'Confirm  New Password', 'wptopschool' ); ?></label>
							<input class="wpts-form-control" name="newrpw" id="newrpw" type="password" required>
						</div>
					</div>
				</div>

				<div class="wpts-row">
					<div class="wpts-col-md-12">
						<div class="wpts-form-group">
							<input class="wpts-btn wpts-btn-primary" name="Change" id="Change" value="Change" type="submit">
						</div>
					</div>
				</div>

		</div>
	</div>
</form>
		<?php
			wpts_body_end();
			wpts_footer();
} else {
		//Include Login Section
	include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
}
?>