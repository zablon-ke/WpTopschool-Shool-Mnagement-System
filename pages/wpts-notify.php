<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb,$wpts_settings_data;
		$notify_table	=	$wpdb->prefix . "wpts_notification";
		$status = $ins = 0;
		$current_user_role=$current_user->roles[0];
	if($current_user_role=='teacher') {
		$receiverTypeList = array( 'all'  => __( 'All Users', 'wptopschool' ),
			 						'allt' => __( 'All Teachers', 'wptopschool' ),
								'alls' => __( 'All Students', 'wptopschool'),
							    'allp' => __( 'All Parents', 'wptopschool')
							   );
} else {
	$receiverTypeList = array( 'all'  => __( 'All Users', 'wptopschool' ),
								'alls' => __( 'All Students', 'wptopschool'),
							    'allp' => __( 'All Parents', 'wptopschool'),
							    'allt' => __( 'All Teachers', 'wptopschool' ) );
}
		$notifyTypeList	=	array( 0 	=>	__( 'All', 'wptopschool') ,
							   1 	=>	__( 'Email', 'wptopschool'),
							   2	=>	__( 'SMS', 'wptopschool'),
							   3	=> 	__( 'Web Notification', 'wptopschool'),
							   4	=>	__( 'Push Notification (Android & IOS)', 'wptopschool') );
		//to send notifications
			$student_table	=	$wpdb->prefix.'wpts_student';
					$teacher_table	=	$wpdb->prefix.'wpts_teacher';
					$users_table	=	$wpdb->prefix.'users ';
					$whereQuery1	= 'where ut.ID = st.parent_wp_usr_id AND ut.user_email!=""';
$whereQuery	= 'where ut.ID = st.wp_usr_id AND ut.user_email!=""';
$student_ids1	=	$wpdb->get_results( "select * from $student_table st, $users_table ut $whereQuery",ARRAY_A );
$teacher_ids1	=	$wpdb->get_results( "select * from $teacher_table st, $users_table ut $whereQuery", ARRAY_A );
$parent_ids1	=	$wpdb->get_results( "select * from $student_table st, $users_table ut $whereQuery1", ARRAY_A );
$usersList1	=	array_merge( $student_ids1,$teacher_ids1);
		if( isset( $_POST['notifySubmit']) && sanitize_text_field($_POST['notifySubmit']) == 'Notify' ) {
			if(isset( $_POST['type'] )  &&
				isset( $_POST['subject'])  && !empty( sanitize_text_field($_POST['subject']) ) && isset( $_POST['description'] ) && !empty( sanitize_text_field($_POST['description']) ) ) {
					$student_table	=	$wpdb->prefix.'wpts_student';
					$parents_table	=	$wpdb->prefix.'wpts_parent';
					$teacher_table	=	$wpdb->prefix.'wpts_teacher';
					$users_table	=	$wpdb->prefix.'users ';
					$receiverType	=	sanitize_price_array($_POST['receiver']);
					$notifyType		=	intval($_POST['type']);
					$subject 		=	sanitize_text_field($_POST['subject']);
					$description 	=	sanitize_textarea_field($_POST['description']);
					$usersList		=	$student_ids	=	$parent_ids	=	$teacher_ids	=	array();
					$whereQuery	= 'where ut.ID = st.wp_usr_id';
					 $whereQuery1    = 'where ut.ID = st.wp_usr_id';
					if ( $notifyType ==1 || $notifyType ==0 ) {
						$whereQuery	.=	' AND ut.user_email!=""';
					}
					if ( $notifyType ==2 || $notifyType ==0 ) {
					$whereQuery	.=	' AND st.s_phone!=""';
					}
					if ( $notifyType ==2 || $notifyType ==0 ) {
					$whereQuery1	.=	' AND st.phone!=""';
					}
					if ( $notifyType ==1 || $notifyType ==0 ) {
						$whereQuery1	.=	' AND ut.user_email!=""';
					}
					foreach($receiverType as $receivers)
					{
					if( $receivers  == 'alls' || $receivers == 'all')	{
						$student_ids	=	$wpdb->get_results( "select * from $student_table st, $users_table ut $whereQuery",ARRAY_A );
					}
					else if( $receivers == 'allp' || $receivers == 'all' ) {
						$parent_ids		=	$wpdb->get_results( "select * from $student_table st ,$users_table ut where ut.ID=st.parent_wp_usr_id AND ut.user_email!=''", ARRAY_A );
					}
					else if( $receivers == 'allt' || $receivers == 'all' ) {
						$teacher_ids	=	$wpdb->get_results( "select * from $teacher_table st, $users_table ut $whereQuery", ARRAY_A );
					}
					else {
						 	$sqlvar =  'select * from '.$users_table.' where ID = '.esc_sql($receivers).' AND user_email!=""';
						$student_ids	=	$wpdb->get_results( $sqlvar,ARRAY_A );
					}
					}
					$usersList	=	array_merge( $student_ids,$parent_ids,$teacher_ids );
					if ( $notifyType ==1 || $notifyType ==0 ) { //If notification is mail/All
						$wpts_settings_table=$wpdb->prefix."wpts_settings";
						$wpts_settings_edit=$wpdb->get_results( "SELECT * FROM $wpts_settings_table" );
						foreach($wpts_settings_edit as $sdat) {
							$settings_data[$sdat->option_name]=$sdat->option_value;
						}
						add_filter( 'wp_mail_from', 'wpts_new_mail_from' );
						add_filter( 'wp_mail_from_name', 'wpts_new_mail_from_name' );
						function wpts_new_mail_from($old) {
						   global $settings_data;
						  return isset( $settings_data['sch_email'] ) && !empty($settings_data['sch_email']) ? $settings_data['sch_email'] : $old;
						}
						function wpts_new_mail_from_name($old) {
							global $settings_data;
							return isset( $settings_data['sch_name'] ) && !empty( $settings_data['sch_name'] ) ? $settings_data['sch_name'] : $old;
						}
						$body = nl2br( $description );
						$headers = array('Content-Type: text/html; charset=UTF-8');
						foreach( $usersList as $key =>$value ) {
							$to = $value['user_email'];
							if( !empty( $to ) ) {
								if( wpts_send_mail( $to, $subject, $body ) ) $status = 1;
							}
						}
					}
					if( isset( $wpts_settings_data['notification_sms_alert'] ) && $wpts_settings_data['notification_sms_alert'] == 1 ) { //if notification enable from setting page
						if ( $notifyType ==2 || $notifyType ==0 ) { //If notification is sms/All
							foreach( $usersList as $key =>$value ) {
								$to = $value['s_phone'];
								if( !empty( $to ) ) {
								if($wpts_settings_data['sch_sms_slaneuser']!= ""){
                                    $notify_msg_response = apply_filters('wpts_send_notification_msg', false, $to, $description );
                                    }
                                    else {
                                        $notify_msg_response = apply_filters('wpts_send_notification_msg_twilio', false, $to, $description );
                                    }
									if( $notify_msg_response ) $status = 1;
								}
							}
						}
					}
					$currentDate	=	wpts_StoreDate( esc_attr( date('Y-m-d h:i:s') ) );
					$description	=	strlen( $description ) > 255 ? substr( $description, 0, 254 ) : $description;
					//insert into db
					$notify_table_data = array(
											'name' => $subject,
											'description' => $description,
											'receiver' => $receiverType,
											'type' => $notifyType,
											'status' => $status,
											'date'	=> $currentDate
										);
									$ins = $wpdb->insert( $notify_table,$notify_table_data);
				}
		}
		$current_user_role=$current_user->roles[0];
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
		$addUrl = add_query_arg( 'ac', 'add', get_permalink());
		if($current_user_role=='administrator' || $current_user_role=='teacher') { 	?>
		<?php
		if($ins) {  ?>
		<div class="wpts-popupMain wpts-popVisible" id="SuccessModal" style="display:block;">
			<div class="wpts-overlayer"></div>
			<div class="wpts-popBody wpts-alert-body">
				<div class="wpts-popInner">
					<a href="javascript:;" class="wpts-closePopup"></a>
					<div class="wpts-popup-cont wpts-alertbox wpts-alert-success">
						<div class="wpts-alert-icon-box">
							<i class="icon dashicons dashicons-yes"></i>
						</div>
						<div class="wpts-alert-data">
							<input type="hidden" name="teacherid" id="teacherid">
							<h4><?php esc_html_e( 'Success', 'wptopschool' );?></h4>
							<p><?php esc_html_e( 'Notification Successfully Send!', 'wptopschool' );?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php  } ?>
		<?php if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add' ) { ?>
		<div class="wpts-card">
			<div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters('wpts_add_notify_heading_item',esc_html("Notification :","wptopschool")); ?> </h3>
            </div>
                 <div class="wpts-card-body">
							<form  method="post" class="form-horizontal" id="NotifyEntryForm">
								<input type="hidden"  id="wpts_locationginal" value="<?php echo admin_url();?>"/>
								<div class="wpts-row">
										<?php  do_action('wpts_before_notification');
										$item =  apply_filters('wpts_add_event_popup_title_item',array());
										?>
									<div class="wpts-col-md-4">
										<div class="wpts-form-group">

											<label class="wpts-label"><?php esc_html_e("Name","wptopschool");
											?><span class="wpts-required"> *</span></label>
											<input type="text" name="subject" class="wpts-form-control">
										</div>
									</div>
									<div class="wpts-col-md-4">
										<div class="wpts-form-group">
											<label class="wpts-label"><?php esc_html_e("Receiver","wptopschool");
											?></label>
										<select class="selectpicker wpts-form-control" data-icon-base="fa" data-tick-icon="fa-check" id="" name="receiver[]" multiple data-live-search="true">
										 <option value="all"><?php esc_html_e( 'All Users', 'wptopschool' );?></option>
										 <option value="alls"><?php esc_html_e( 'All Students', 'wptopschool' );?></option>
										 <option value="<?php echo 'allp'?>"><?php esc_html_e( 'All Parents', 'wptopschool' );?></option>
										 <option value="<?php echo 'allt'?>"><?php esc_html_e( 'All Teachers', 'wptopschool' );?></option>
										 <?php foreach($usersList1 as $usersList1details)
											{?>
											<option value="<?php echo esc_attr($usersList1details['wp_usr_id']);?>"><?php if(!empty($usersList1details['s_fname']) && !empty($usersList1details['s_lname'])){echo esc_html($usersList1details['s_fname']." ".$usersList1details['s_lname']);} if(!empty($usersList1details['first_name']) && !empty($usersList1details['last_name'])){
											echo esc_html($usersList1details['first_name']." ".$usersList1details['last_name']);}?></option>
											<?php } if(!empty($parent_ids1))
											{
												foreach($parent_ids1 as $parent_idsdata)
											{
												?>
												 <option value="<?php echo esc_attr($parent_idsdata['parent_wp_usr_id']);?>"><?php echo esc_html($parent_idsdata['p_fname']." ".$parent_idsdata['p_lname']);
												?></option>
											<?php } }
											?>

                            		</select>
											<!-- <select name="receiver" class="wpts-form-control">
												<option value=""><?php _e( 'Whom to notify?', 'wptopschool'); ?></option>
												<?php
													foreach( $receiverTypeList as $key => $value ) {
														echo '<option value="'.esc_attr($key).'">'.esc_html($value).'</option>';
													}
												?>
											</select> -->
										</div>
									</div>
									<div class="wpts-col-md-4">
										<div class="wpts-form-group">

											<label class="wpts-label"><?php
												    esc_html_e("Notify Type","wptopschool");
											?><span class="wpts-required"> *</span></label>
												<?php $proversion = wpts_check_pro_version('wpts_sms_version');
													$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
													$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
													$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';
												?>
												<select name="type" class="wpts-form-control">
													<option value=""><?php _e( 'How to notify?', 'wptopschool'); ?></option>
													<option value="1"><?php _e( 'Email', 'wptopschool'); ?></option>
													<option value="2" title="<?php echo esc_attr($protitle); ?>" class="<?php echo esc_attr($proclass); ?>"
														<?php if( !empty( $prodisable ) ) { ?> disabled <?php  } ?>>
														<?php _e( 'SMS', 'wptopschool'); ?>
													</option>
													<option value="0"><?php _e( 'All', 'wptopschool'); ?></option>
												</select>
												<?php
												if( !isset( $wpts_settings_data['notification_sms_alert'] ) || ( isset( $wpts_settings_data['notification_sms_alert'] ) && $wpts_settings_data['notification_sms_alert'] != 1 ) ) {
													echo '<p style="margin-top:6px;">
													<img src="'.esc_url(plugins_url( '/img/svg/info-icon.svg', dirname(__FILE__) )).'" width="12" height="12" /> Enable SMS Notification Option from setting page to send SMS</p>';
												}
												?>
											</div>
										</div>
								</div>
								<div class="wpts-row">
									<div class="wpts-col-md-12">
										<div class="wpts-form-group">
											<label class="wpts-label"><?php
													esc_html_e("Description","wptopschool");
											?><span class="wpts-required"> *</span></label>
											<textarea class="wpts-form-control" name="description" required minlength="15"></textarea>
										</div>
									</div>
								</div>
									<?php  do_action('wpts_after_notification'); ?>
									<div class="wpts-form-group">
										<div class="wpts-row">
											<div class="wpts-col-md-12">
												<input type="submit" class="wpts-btn wpts-btn-success" name="notifySubmit" value="Notify" id="notifySubmit">
												<a href="<?php echo esc_url(wpts_admin_url().'sch-notify');?>" class="wpts-btn wpts-dark-btn"><?php esc_html_e( 'Back', 'wptopschool' ); ?></a>
											</div>
										</div>
									</div>
							</form>
						</div>
					</div>
			<?php } else { ?>
			<div class="wpts-card">
                 <div class="wpts-card-body">
							<table id="notify_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<tr>
										<th class="nosort">#</th>
										<th><?php _e( 'Name', 'wptopschool' ); ?></th>
										<th><?php _e( 'Description', 'wptopschool' );?></th>
										<!-- <th><?php _e( 'Receiver', 'wptopschool' ); ?></th> -->
										<th><?php _e( 'Type', 'wptopschool' ); ?></th>
										<th><?php _e( 'Date', 'wptopschool');  ?></th>
										<th class="nosort" align="center"><?php _e( 'Action', 'wptopschool'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										//Last added will me shown first
										$notifyInfo = $wpdb->get_results("Select * from $notify_table order by nid desc");
										foreach( $notifyInfo as $key=>$value ) {
											$receiver	=	isset( $receiverTypeList[$value->receiver] ) ? $receiverTypeList[$value->receiver] : $value->receiver;
											$type		=	isset( $notifyTypeList[$value->type] ) ? $notifyTypeList[$value->type] : $value->type;
												echo '<tr>
													<td>'.esc_html($key+1).'</td>
													<td>'.esc_html($value->name).'</td>
													<td>'.esc_html(substr( $value->description, 0, 20)).'</td>
													<td>'.esc_html($type).'</td>
													<td>'.wpts_ViewDate( esc_html($value->date) ).'</td>
													<td align="center">
														<div class="wpts-action-col">
														<a href="javascript:;" class="wpts-popclick notify-view"  data-id="'.esc_attr(intval($value->nid)).'"  data-pop="ViewModal"><i class="icon wpts-view wpts-view-icon"></i></a>
															<a href="javascript:;" class="wpts-popclick notify-Delete"  data-id="'.esc_attr(intval($value->nid)).'" >
															<i class="icon wpts-trash wpts-delete-icon notify-Delete" ></i>
															</a>
														</div>
													</td>
												</tr>';
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th class="nosort">#</th>
										<th><?php _e( 'Name', 'wptopschool' ); ?></th>
										<th><?php _e( 'Description', 'wptopschool' );?></th>
										<!-- <th><?php _e( 'Receiver', 'wptopschool' ); ?></th> -->
										<th><?php _e( 'Type', 'wptopschool' ); ?></th>
										<th><?php _e( 'Date', 'wptopschool');  ?></th>
										<th class="nosort"><?php _e( 'Action', 'wptopschool'); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
		<div class="wpts-popupMain" id="ViewModal">
			<div class="wpts-overlayer"></div>
			<div class="wpts-popBody">
				<div class="wpts-popInner">
					<a href="javascript:;" class="wpts-closePopup"></a>
					<div id="ViewModalContent" class="wpts-text-left"></div>
				</div>
			</div>
		</div>
		<?php }
		}
		else if($current_user_role=='parent' || $current_user_role='student')
		{
		}
		wpts_body_end();
		wpts_footer();
	}
	else{
		include_once( WPTS_PLUGIN_PATH.'/includes/wpts-login.php');
	}
?>
