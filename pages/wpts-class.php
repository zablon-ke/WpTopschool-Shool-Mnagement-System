<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		if( $current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			$filename	=	'';
			$header	=	'Classes';
			if( isset($_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'addclass' ) {
				$header	=	$label	=	__( 'Add New Class', 'wptopschool');
				$filename	=	WPTS_PLUGIN_PATH .'includes/wpts-classForm.php';
			}elseif((isset($_GET['id']) && is_numeric($_GET['id'])))  {
				$header	=	$label	=	__( 'Update Class', 'wptopschool');
				$filename	=	WPTS_PLUGIN_PATH .'includes/wpts-classForm.php';
			}
		?>
		<?php
		if( !empty( $filename) ) {
			include_once ( $filename );
		} else {
		?>
		<div class="wpts-card">
			<div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php esc_html_e( 'Class List', 'wptopschool' )?></h3>
				<?php  if( $current_user_role=='administrator' ) { ?>

				<?php } ?>
            </div>
			<div class="wpts-card-body">
				<table class="wpts-table" id="class_table" cellspacing="0" width="100%" style="width:100%">
					<thead>
					<tr>
						<th class="nosort">#</th>
						<th><?php esc_html_e( 'Class Number', 'wptopschool' ); ?></th>
						<th><?php esc_html_e( 'Class Name', 'wptopschool' ); ?></th>
						<th><?php esc_html_e( 'Teacher Incharge', 'wptopschool' ); ?></th>
						<th><?php esc_html_e( 'Number of Students', 'wptopschool' ); ?></th>
						<th><?php esc_html_e( 'Capacity', 'wptopschool' ); ?></th>
						<th><?php esc_html_e( 'Location', 'wptopschool' ); ?></th>
						<?php  if( $current_user_role=='administrator' ) { ?> <th class="nosort" align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th> <?php } ?>
					</tr>
					</thead>
					<tbody>
									<?php
									$ctable=$wpdb->prefix."wpts_class";
									$stable=$wpdb->prefix."wpts_student";
									$wpts_classes =$wpdb->get_results("select * from $ctable order by cid DESC");
									$sno=1;
									$teacher_table=	$wpdb->prefix."wpts_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}

									foreach ($wpts_classes as $wpts_class) {
										$cid=intval($wpts_class->cid);


										$studentlists	=	$wpdb->get_results("select class_id, sid from $stable");
										$stl = [];
										foreach ($studentlists as $stu) {
											if(is_numeric($stu->class_id) ){
												if($stu->class_id == $cid){
												 $stl[] = $stu->sid;
											 }
											}
											else{
												 $class_id_array = unserialize( $stu->class_id );
												// print_r($class_id_array);
												 if(!empty($class_id_array)){
												 if(in_array($cid, $class_id_array)){
													 $stl[] = $stu->sid;
												 }
												}
											}
										}
										$class_students_count = count($stl);

										$teach_id= intval($wpts_class->teacher_id);
										$teachername	=	'';
									?>
										<tr id="<?php echo esc_attr($wpts_class->cid);?>" class="pointer">
											<td><?php echo esc_html($sno);?><td><?php echo esc_html($wpts_class->c_numb);?> </td>
											<td><?php echo esc_html($wpts_class->c_name);?></td>
											<td><?php echo isset( $teacherlist[$teach_id] ) ? esc_html($teacherlist[$teach_id]) : '';?></td>
											<td><?php echo esc_html($class_students_count);?></td>
											<td><?php echo esc_html($wpts_class->c_capacity);?></td>
											<td><?php echo esc_html($wpts_class->c_loc);?></td>
											<?php  if( $current_user_role=='administrator' ) { ?>
												<td align="center">
													<div class="wpts-action-col">
													<a href="<?php echo esc_url(wpts_admin_url().'sch-class&id='.esc_attr($wpts_class->cid)."&edit=true");?>" title="Edit">
													<i class="icon dashicons dashicons-edit wpts-edit-icon"></i></a>

													<a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr($wpts_class->cid);?>" >
	                                				<i class="icon dashicons dashicons-trash wpts-delete-icon" data-id="<?php echo esc_attr($wpts_class->cid);?>"></i>
	                                				</a>
	                                				</div>

												</td>
											<?php } ?>
										</tr>
									<?php
										$sno++;
									}
									?>
								</tbody>
				</table>
			</div>
		</div>

		<?php  } if( $current_user_role=='administrator' ) { ?>

		<?php  } ?>
		<?php
			//include_once ( $filename );
			wpts_body_end();
			wpts_footer();
		}
		else if($current_user_role=='parent' || $current_user_role='student')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			?>

				<div class="wpts-row">
					<div class="wpts-col-md-12">
						<div class="wpts-card">
						<div class="wpts-card-head ui-sortable-handle">
                                    <h3 class="wpts-card-title"><?php esc_html_e( 'Classe Details', 'wptopschool' )?> </h3>
                                </div>
							<div class="wpts-card-body">

								<table id="class_table" class="wpts-table wpts-table-bordered wpts-table-striped" cellspacing="0" width="100%" style="width:100%">
									<thead>
									<tr>
										<th class="nosort">#</th>
										<th><?php esc_html_e( 'Class Number', 'wptopschool' ); ?></th>
										<th><?php esc_html_e( 'Class Name', 'wptopschool' ); ?></th>
										<th><?php esc_html_e( 'Teacher Incharge', 'wptopschool' ); ?></th>
										<th><?php esc_html_e( 'Number of Students', 'wptopschool' ); ?></th>
										<th><?php esc_html_e( 'Location', 'wptopschool' ); ?></th>
									</tr>
									</thead>
									<tbody>
									<?php
									$ctable=$wpdb->prefix."wpts_class";
									$stable=$wpdb->prefix."wpts_student";
									$teacher_table=	$wpdb->prefix."wpts_teacher";
									$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
									$teacherlist	=	array();
									if( !empty( $teacher_data ) ) {
										foreach( $teacher_data  as $value )
											$teacherlist[$value->wp_usr_id] = $value->full_name;
									}
									if( $current_user_role=='student' ) {
										$wpts_classes =$wpdb->get_results("SELECT cls.* FROM $ctable cls, $stable st where st.wp_usr_id = '$current_user->ID' AND st.class_id=cls.cid");
									} else {
										$wpts_classes =$wpdb->get_results("SELECT DISTINCT cls.* FROM $ctable cls, $stable st where st.parent_wp_usr_id = '$current_user->ID' AND st.class_id=cls.cid");
									}
									$sno=1;
									foreach ($wpts_classes as $wpts_class)
									{
										$cid = intval($wpts_class->cid);
										$class_students_count = $wpdb->get_var( "SELECT COUNT(`wp_usr_id`) FROM $stable WHERE class_id = '".esc_sql($cid)."'" );
										$teach_id= intval($wpts_class->teacher_id);
										$teacher=get_userdata($teach_id);
										?>
										<tr id="<?php echo  esc_attr($wpts_class->cid);?>" class="pointer">
											<td><?php echo esc_html($sno);?><td><?php echo  esc_html($wpts_class->c_numb);?> </td>
											<td><?php echo esc_html($wpts_class->c_name);?></td>
										    <td><?php echo isset( $teacherlist[$teach_id] ) ? esc_html($teacherlist[$teach_id]) : '';?></td>
											<td><?php echo esc_html($class_students_count);?></td>
											<td><?php echo esc_html($wpts_class->c_loc);?></td>
										</tr>
										<?php
										$sno++;
									}
									?>
									</tbody>
								</table>
								</div>
							</div>
						</div>
					</div>

			<?php
			wpts_body_end();
			wpts_footer();
		}
	}
	else{

		include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
	}
?>
