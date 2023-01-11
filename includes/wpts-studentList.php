<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
 $proversion	=	wpts_check_pro_version();
	  $proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
	  $protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
	  $prodisable	=	!$proversion['status'] ? 'disabled="disabled"'	: '';
	  $studentFieldList =  array(	's_rollno'			=>	__('Roll Number', 'wptopschool'),
									's_fname'			=>	__('Student First Name', 'wptopschool'),
									's_mname'			=>	__('Student Middle Name', 'wptopschool'),
									's_lname'			=>	__('Student Last Name', 'wptopschool'),
									's_zipcode'			=>	__('Zip Code', 'wptopschool'),
									's_country'			=>	__('Country', 'wptopschool'),
									's_gender'			=>	__('Gender', 'wptopschool'),
									's_address'			=>	__('Current Address', 'wptopschool'),
									's_paddress'		=>	__('Permanent Address', 'wptopschool'),
									'p_fname '			=>	__('Parent First Name', 'wptopschool'),
									's_bloodgrp'		=>	__('Blood Group', 'wptopschool'),
									's_dob'				=>	__('Date Of Birth', 'wptopschool'),
									's_doj'				=>	__('Date Of Join', 'wptopschool'),
									's_phone'			=>	__('Phone Number', 'wptopschool'),
							);
	$teacherId	=	0;
	global $current_user;
	$role		=	 $current_user->roles;
	$cuserId	=	 $current_user->ID;
?>
<?php $proversion1    =    wpts_check_pro_version('wpts_addon_version');
  	  $prodisable1    =    !$proversion1['status'] ? 'notinstalled'    : 'installed';

  	   $propayment    =    wpts_check_pro_version('pay_WooCommerce');
      $propayment    =    !$propayment['status'] ? 'notinstalled'    : 'installed';

       $prohistory    =    wpts_check_pro_version('wpts_mc_version');
    $prodisablehistory    =    !$prohistory['status'] ? 'notinstalled'    : 'installed';
       ?>
<div class="wpts-card">
		<div class="wpts-card-head">
	<?php /*<h3 class="wpts-card-title">Student List by class </h3>
			*/



		?>
		<div class="subject-inner wpts-left wpts-class-filter">
			<form name="StudentClass" id="StudentClass" method="post" action="">
				<label class="wpts-labelMain"><?php _e( 'Select Class Name', 'wptopschool' ); ?></label>
				<select name="ClassID" id="ClassID" class="wpts-form-control">
					<?php
					$sel_classid	=	isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : '';
					$class_table	=	$wpdb->prefix."wpts_class";
					$sel_class		=	$wpdb->get_results("select cid,c_name from $class_table Order By cid ASC");
					?>
					<?php if($current_user_role=='administrator' ) { ?>
					<option value="all" <?php if($sel_classid=='all') echo esc_html("selected","wptopschool"); ?>><?php _e( 'All', 'wptopschool' ); ?></option>
					<?php } foreach( $sel_class as $classes ) {
					?>
						<option value="<?php echo esc_attr($classes->cid);?>" <?php if($sel_classid==$classes->cid) echo esc_html("selected","wptopschool"); ?>><?php echo esc_html($classes->c_name);?></option>
					<?php } ?>
				</select>
			</form>
		</div>
		<div class="wpts-right wpts-import-export">
			<div class="wpts-btn-lists" <?php echo esc_html($prodisable);?> title="<?php echo esc_attr($protitle);?>">

				<div class="wpts-btn-list" <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>

					<div class="wpts-button-group wpts-dropdownmain wpts-left">
						<button type="button" class="wpts-btn wpts-btn-success print" id="PrintStudent" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>">
							<i class="fa fa-print" ></i> <?php _e( 'Print', 'wptopschool'); ?>
						</button>
						<button type="button" class="wpts-btn wpts-btn-success wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>">
						<!-- <span class="sr-only"><?php _e( 'Toggle Dropdown', 'wptopschool' );?></span> -->
					</button>
					<div class="wpts-dropdown wpts-dropdown-md">
					<ul>
						<li class="wpts-drop-title wpts-checkList"><?php _e( 'Select Columns to Print', 'wptopschool' );?> </li>
						<form id="StudentColumnForm" name="StudentColumnForm" method="POST">
							<?php foreach( $studentFieldList as $key=>$value ) { ?>
								<li class="wpts-checkList" >
									<input type="checkbox" name="StudentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
									<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
								</li>
							<?php } ?>
							<?php $currentSelectClass =	isset($_POST['ClassID']) ? intval($_POST['ClassID']) : '0'; ?>
							<input type="hidden" name="ClassID" value="<?php  echo esc_attr($currentSelectClass); ?>">
							<input type="hidden" name="exportstudent" value="exportstudent">
						</form>
					</ul>
					</div>
				</div>

			</div>
            <?php if ( in_array( 'administrator', $role ) ) {?>
			<div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
				<button id="ImportStudent" class="wpts-btn wpts-dark-btn wpts-popclick impt" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>" data-pop="ImportModal"><i class="fa fa-upload"></i> <?php echo esc_html("Import","wptopschool");?> </button></div>
					<?php }?>
			<div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
				<div class="wpts-dropdownmain wpts-button-group">
					<button type="button" class="wpts-btn print" id="ExportStudents" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>"><i class="fa fa-download"></i> <?php _e( 'Export', 'wptopschool'); ?> </button>
					<button type="button" class="wpts-btn wpts-btn-blue wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>">
						<!-- <span class="sr-only"><?php _e( 'Toggle Dropdown', 'wptopschool' );?></span> -->
					</button>
					 <div id="exportcontainer" style="display:none;"></div>
					<div class="wpts-dropdown wpts-dropdown-md wpts-dropdown-right">
						<ul>
							<li class="wpts-drop-title wpts-checkList"><?php _e( 'Select Columns to Export', 'wptopschool' );?> </li>
							<form id="ExportColumnForm" name="ExportStudentColumn" method="POST">
								<?php foreach( $studentFieldList as $key=>$value ) {?>
								<li class="wpts-checkList">
									<input type="checkbox" name="StudentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
									<label class="wpts-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
								</li>
								<?php }?>
								<input type="hidden" name="ClassID" value="<?php echo esc_html($currentSelectClass); ?>">
								<input type="hidden" name="exportstudent" value="exportstudent">
							</form>
						</ul>
					</div>
				</div>
			</div>

		</div>
		</div>
	</div>
	<div class="wpts-card-body">
						<div class="subject-head">
					<?php if ( in_array( 'administrator', $role ) ) { ?>
							<div class="wpts-bulkaction">
								<select name="bulkaction" class="wpts-form-control" id="bulkaction">
									<option value=""><?php echo esc_html("Select Action","wptopschool");?></option>
									<option value="bulkUsersDelete"><?php echo esc_html("Delete","wptopschool");?></option>
								</select>
							</div>
						<?php } ?>
						<table id="student_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
						<thead>
							<tr>
								<th class="nosort">
								<?php if ( in_array( 'administrator', $role ) ) { ?><input type="checkbox" id="selectall" name="selectall" class="ccheckbox"><?php } else echo esc_html('Sr. No.','wptopschool'); ?>
								</th>
								<th><?php echo apply_filters( 'wpts_student_table_rollno_heading',esc_html__('Roll No.','wptopschool'));?></th>
								<th><?php echo apply_filters( 'wpts_student_table_fullname_heading',esc_html__('Full Name','wptopschool'));?></th>
								<th><?php echo apply_filters( 'wpts_student_table_parent_heading',esc_html__('Parent','wptopschool'));?></th>
								<th><?php echo apply_filters( 'wpts_student_table_streetaddress_heading',esc_html__('Street Address','wptopschool'));?></th>
								<?php  if($propayment =='installed'){?>
								<th><?php echo apply_filters( 'wpts_student_table_paymentstatus_heading',esc_html__('Payment Status','wptopschool'));?></th>
							<?php } ?>
								<?php  if($proversion1['status']){?>
										 <th><?php echo apply_filters( 'wpts_student_table_class_status_heading',esc_html__('Class Status','wptopschool'));?></th>
									<?php } ?>
								<th><?php echo apply_filters( 'wpts_student_table_phone_heading',esc_html__('Phone','wptopschool'));?></th>
								<th align="center" class="nosort"><?php echo apply_filters( 'wpts_student_table_action_heading',esc_html__('Action','wptopschool'));?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$student_table	=	$wpdb->prefix."wpts_student";
							$users_table	=	$wpdb->prefix."users";
							$class_id='';
							if( isset($_POST['ClassID'] ) && $_POST['ClassID'] != 'all' ) {
								$class_id=intval($_POST['ClassID']);
								$stl = [];
								$studentlists	=	$wpdb->get_results("select class_id, sid from $student_table");
									foreach ($studentlists as $stu) {
										if(is_numeric($stu->class_id) ){
											if($stu->class_id == $class_id){
											 $stl[] = $stu->sid;
										 }
										}
										else{
											 $class_id_array = unserialize( $stu->class_id );
											 if(in_array($class_id, $class_id_array)){
												 $stl[] = $stu->sid;
											 }
										}
									}

								}
								else if(!isset($_POST['ClassID']) || $_POST['ClassID'] == 'all' ){
									$studentlists	=	$wpdb->get_results("select sid from $student_table");
									foreach ($studentlists as $stu) {
										 $stl[] = $stu->sid;
									}

								}

								if (!empty($stl)) {
									$key =0;
								foreach ($stl as $id ) {
                                    $id = esc_sql($id);
							$students	=	$wpdb->get_results("select * from $student_table s, $users_table u where u.ID=s.wp_usr_id AND sid = '$id' and user_login != 'student' order by sid desc");
							$plugins_url=plugins_url();
							$teacherId = '';
							if( $currentSelectClass != 'all' )
								$teacherId	=	$wpdb->get_var("select teacher_id from $class_table WHERE cid=$currentSelectClass");

							$pendingcount =0;
							$cid = array();
							foreach($students as $stinfo)
							{
								//echo $stinfo->wp_usr_id;

								if(is_numeric($stinfo->class_id) ){
									 $cid[] = $stinfo->class_id;
								}
								else{
									 $class_id_array = unserialize( $stinfo->class_id );
										 $cid[] = $class_id_array;
								}
								$courses = get_user_meta( $stinfo->parent_wp_usr_id, '_pay_woocommerce_enrolled_class_access_counter', true );
								$results = $wpdb->get_results("SELECT s.wp_usr_id,f.student_id,f.order_id FROM wp_wpts_student AS s INNER JOIN wp_wpts_fees AS f ON f.student_id =  s.wp_usr_id");
                                //  echo"<pre>";print_r($results);
								foreach($results as $res){
                                    $siid[] = $res->wp_usr_id;
                                    // echo "<pre>";print_r($siid);
                                }


								if(empty($siid))
								{
									$paid = esc_html("Pending","wptopschool");
								}
								else {
                                    if (in_array($stinfo->wp_usr_id,$siid)){
                                        $paid = esc_html("Paid","wptopschool");
                                    }else{
                                        $paid = esc_html("Pending","wptopschool");
                                    }
                                }
								$key++;
								?>

									<?php  if($proversion1['status']){?>
									<tr <?php if($stinfo->s_lname == "") {echo "style='background-color:#fcdddd'";} else {
										echo "style='background-color:#c9f7c9'";}?>>
									<?php }else {?>
									<tr>
								  	<?php } ?>

									<td>
									<?php if ( in_array( 'administrator', $role ) ) { ?>
										<input type="checkbox" class="ccheckbox strowselect" name="UID[]" value="<?php echo esc_attr($stinfo->wp_usr_id);?>">
									<?php }else echo esc_html($key); ?>
									</td>
									<td><?php echo esc_html($stinfo->s_rollno);?></td>
									<td><?php
										$mname = $stinfo->s_mname;
							            $lname = $stinfo->s_lname;
									echo esc_html($stinfo->s_fname .' '. $mname .' '.  $lname);?></td>
									<td><?php  echo esc_html($stinfo->p_fname." ".$stinfo->p_lname); ?>
									</td>
									<td><?php
										$country = !empty( $stinfo->s_country ) ? ", ".$stinfo->s_country : '';
										$city    = !empty( $stinfo->s_city ) ? ", ".$stinfo->s_city : '';
										$zipcode    = !empty( $stinfo->s_zipcode ) ? ", ".$stinfo->s_zipcode : '';
										echo esc_html($stinfo->s_address.' '.$city. ' ' . $country.' '.$zipcode);
									?></td>
									<?php  if($propayment == 'installed'){?>
									<td><?php echo esc_html($paid);
									?>
										<a href="<?php echo esc_url(wpts_admin_url().'sch-payment&id='.base64_encode(intval($stinfo->wp_usr_id)));?>" class="wpts-popclick1" title="View"><i class="icon dashicons dashicons-visibility wpts-view-icon"></i></a>

									</td>
								<?php } ?>
									<?php  if($proversion1['status']){?>
									<td>
                    <?php
                  $stl = array();
                  if($stinfo->class_id != ''){
                    if(is_numeric($stinfo->class_id) ){
                       $stl[] = $stinfo->class_id;
                    }else{
                       $class_id_array = unserialize($stinfo->class_id);
                       foreach ($class_id_array as $id) {
                         $stl[] = $id;
                       }
                    }
                  }else{
                    $stl[] = 0;
                  }

                  if($stl[0] == 0 ){ echo esc_html("Unassigned","wptopschool"); } else { echo esc_html("Assigned","wptopschool"); }
                  ?>
                  </td>
									<?php } ?>
									<td><?php echo esc_html($stinfo->s_phone);?></td>
									<td align="center">
										<div class="wpts-action-col">
											<a href="javascript:;" class="ViewStudent wpts-popclick" data-pop="ViewModal" data-id="<?php echo esc_attr($stinfo->wp_usr_id);?>" title="View"><i class="icon dashicons dashicons-visibility wpts-view-icon"></i></a>

											<a href="<?php echo "?id=".esc_attr($stinfo->wp_usr_id);?>javascript:;" data-id="<?php echo esc_attr($stinfo->wp_usr_id);?>"  data-pop="ViewModal" class="viewAttendance wpts-popclick" title="Attendance">
												<i class="icon dashicons dashicons-admin-users wpts-attendance-icon"></i>
											</a>
												<a href="<?php echo esc_url(wpts_admin_url().'sch-student&id='. esc_attr($stinfo->wp_usr_id).'&edit=true');?>" title="Edit"><i class="icon dashicons dashicons-edit wpts-edit-icon"></i>
												</a>
												<?php if ( in_array( 'administrator', $role ) || ( !empty( $teacherId ) && $teacherId==$cuserId ) ) { ?>
											<a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr($stinfo->sid);?>" >
	                                				<i class="icon dashicons dashicons-trash wpts-delete-icon" data-id="<?php echo esc_attr($stinfo->sid);?>"></i>
	                                				</a>
											<?php }

											  if($prodisablehistory == "installed"){?>
												<a href="<?php echo esc_url(wpts_admin_url().'sch-history&id='.base64_encode($stinfo->wp_usr_id));?>" title="History">
	                                				<i class="icon dashicons dashicons-image-rotate wpts-view-icon" data-id="<?php echo esc_attr($stinfo->sid);?>"></i>
	                                				</a>

	                                			<?php } ?>

										</div>
									</td>
								</tr>
							<?php
							}}}
							?>
						</tbody>
						<tfoot>
						  <tr>
							<th><?php if ( in_array( 'administrator', $role ) ) { }
								else esc_html_e("Sr. No","wptopschool"); ?></th>
							<th><?php esc_html_e("Roll No.","wptopschool");?></th>
							<th><?php esc_html_e("Full Name","wptopschool");?></th>
							<th><?php esc_html_e("Parent","wptopschool");?></th>
							<th><?php esc_html_e("Street Address","wptopschool");?></th>
							<?php  if($propayment =='installed'){?>
								<th><?php esc_html_e("Payment Status","wptopschool");?></th>
							<?php } ?>
							<?php  if($proversion1['status']){?>
								 <th><?php esc_html_e("Class Status","wptopschool");?></th>
							<?php } ?>
							<th><?php esc_html_e("Phone","wptopschool");?></th>
							<th align="center"><?php esc_html_e("Action","wptopschool");?></th>
						  </tr>
						</tfoot>
					  </table>
					  </div>
					</div><!-- /.box-body -->
				</div>
