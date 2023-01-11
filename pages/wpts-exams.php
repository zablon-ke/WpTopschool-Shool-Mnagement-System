<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
    global $current_user, $wpdb;
		$current_user_role	=	$current_user->roles[0];
		$current_user_Id	=	intval($current_user->ID);
		$subject_table		=	$wpdb->prefix."wpts_subject";
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			$filename	=	'';
			$header ='Exams';
			if( isset($_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'addexam' ) {
				$header	=	$label	=	__( 'Add New Exam', 'wptopschool');
				$filename	=	WPTS_PLUGIN_PATH .'includes/wpts-examForm.php';
			}elseif(( isset($_GET['id']) && intval($_GET['id'])))  {
				$header	=	$label	=	__( 'Update Exam', 'wptopschool');
				$filename	=	WPTS_PLUGIN_PATH .'includes/wpts-examForm.php';
			}
			$extable	=	$wpdb->prefix."wpts_exam";
			$ctable		=	$wpdb->prefix."wpts_class";
			$wpts_exams =	$wpdb->get_results( "select * from $extable");
			$class_ID	=	0;
			if( $current_user_role=='teacher' ) {
				$cuserId	=	intval($current_user->ID);
				$class_ID	=	$wpdb->get_results("SELECT DISTINCT c.cid,c.c_name,s.id FROM wp_wpts_class c
								INNER JOIN wp_wpts_subject s ON s.class_id= c.cid
								WHERE s.sub_teach_id ='".esc_sql($cuserId)."' || c.teacher_id = '".esc_sql($cuserId)."'");
			$j=0;
			foreach( $class_ID as $class_IDa ) {
				$clsid[] = $class_IDa->cid;
				$subid[] = $class_IDa->id;
			}
			$msg		=	'Please Ask Principal To Assign Class';
			if( !empty( intval($class_ID )) ) {
				$wpts_exams =	$wpdb->get_results( "select * from $extable where classid IN (".implode(',',$clsid).") and subject_id IN (".implode(',',$subid).")");
			}
		}
		if( !empty( $filename) ) {
			include_once ( $filename );
		} else {?>
		<div class="wpts-card">
      <div class="wpts-card-body">
				<div class="subject-head">
          <?php if( $current_user_role=='teacher' && empty( $class_ID ) ) {
            echo '<div class="alert alert-danger col-lg-2">'.esc_html($msg).'</div>';
          } ?>
				</div>
				<table id="exam_class_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
					<thead>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Exam Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Class Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Subject Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Start Date', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'End Date', 'wptopschool' );?></th>
						<?php	if($current_user_role=='administrator'){?>
							<th class="nosort"><?php esc_html_e( 'Action', 'wptopschool' );?></th>
						<?php }?>
						</tr>
					</thead>
					<tbody>
						<?php
						$sno=1;
						foreach( $wpts_exams as $wpts_exam ) {
							$classname = $sublist	=	'';
							$classid	=	isset($wpts_exam->classid)  ? intval($wpts_exam->classid) : '';
							if( !empty( intval($classid )) ) {
								$classname = $wpdb->get_var( "SELECT c_name FROM $ctable WHERE cid = '".esc_sql($classid)."'" );
							}
							$sublist	=	'-';
							if( !empty($wpts_exam->subject_id) ) {
								$subject_list	=	array();
								$slist	=	str_replace( 'All,', '',$wpts_exam->subject_id);

								if( !empty( $slist ) ) {
									$subjectlist	=	$wpdb->get_results("SELECT sub_name FROM $subject_table WHERE id IN($slist) ", ARRAY_A );
									foreach( $subjectlist as $list ) {
										$subject_list[]	= $list['sub_name'];
									}
									$sublist	=	implode(", ",$subject_list);
								}

							}
						?>
							<tr id="<?php echo esc_attr($wpts_exam->eid);?>">
								<td><?php echo  esc_html($sno); ?>
								<td><?php echo  esc_html($wpts_exam->e_name);?></td>
								<td><?php echo  esc_html($classname); ?></td>
								<td><?php echo  esc_html($sublist); ?></td>
								<td><?php echo  wpts_ViewDate(esc_html($wpts_exam->e_s_date)); ?></td>
								<td><?php echo  wpts_ViewDate(esc_html($wpts_exam->e_e_date));?></td>
									<?php	if($current_user_role=='administrator'){?>
								<td align="center">
									<div class="wpts-action-col">
										<a href="<?php echo esc_url(wpts_admin_url().'sch-exams&id='.intval($wpts_exam->eid).'&edit=true');?>"><i class="icon dashicons dashicons-edit wpts-edit-icon"></i></a>
										<a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr($wpts_exam->eid);?>" >
                      <i class="icon dashicons dashicons-trash wpts-delete-icon" data-id="<?php echo esc_attr($wpts_exam->eid);?>"></i>
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
					<tfoot>
						<tr>
							<th class="nosort">#</th>
							<th><?php esc_html_e( 'Exam Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Class Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Subject Name', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'Start Date', 'wptopschool' );?></th>
							<th><?php esc_html_e( 'End Date', 'wptopschool' );?></th>
							<?php	if($current_user_role=='administrator'){?>
							<th class="nosort"><?php esc_html_e( 'Action', 'wptopschool' );?></th>
							<?php } ?>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	<?php } ?>
		<?php if($current_user_role=='administrator'){?>
		<!--Info Modal-->
		<div class="modal fade" id="InfoModal" tabindex="-1" role="dialog" aria-labelledby="InfoModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header">
								<p class="box-title" id="InfoModalTitle"></p>
							</div><!-- /.box-header -->
							<div id="InfoModalBody" class="box-body PTZero">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.modal -->
		<?php
	}
  wpts_body_end();
  wpts_footer();
}
else if( $current_user_role=='parent' || $current_user_role='student'){
  wpts_topbar();
  wpts_sidebar();
  wpts_body_start();
  ?>
  <div class="wpts-card">
    <div class="wpts-card-head">
      <h3 class="wpts-card-title"><?php esc_html_e( 'Time Table', 'wptopschool' )?> </h3>
    </div>
    <div class="wpts-card-body">
      <table id="exam_class_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
        <thead>
          <tr>
            <th class="nosort">#</th>
						<th><?php esc_html_e( 'Exam Name', 'wptopschool' );?></th>
						<th><?php esc_html_e( 'Class Name', 'wptopschool' );?></th>
						<th><?php esc_html_e( 'Subject Name', 'wptopschool' );?></th>
						<th><?php esc_html_e( 'Start Date', 'wptopschool' );?></th>
						<th><?php esc_html_e( 'End Date', 'wptopschool' );?></th>
					</tr>
				</thead>
        <tbody>
          <?php
        //   $id = sanitize_text_field(stripslashes($_GET['cid']));
          $class_id = base64_decode(sanitize_text_field($_GET['cid']));
          $extable	=	$wpdb->prefix."wpts_exam";
					$studtable	=	$wpdb->prefix."wpts_student";
					$classtable	=	$wpdb->prefix."wpts_class";
          $wpts_exams = [];
					if( $current_user_role=='parent' ) {
			  $wpts_exams =$wpdb->get_results( "SELECT DISTINCT e.*,c.c_name FROM $studtable st, $extable e, $classtable c where st.parent_wp_usr_id='".esc_sql($current_user_Id)."' AND e.classid='".esc_sql($class_id)."' AND c.cid=".esc_sql($class_id)."");
				} else {
              $wpts_exams =$wpdb->get_results( "SELECT DISTINCT e.*,c.c_name FROM $studtable st, $extable e, $classtable c where st.wp_usr_id='".esc_sql($current_user_Id)."' AND e.classid='".esc_sql($class_id)."' AND c.cid='".esc_sql($class_id)."'");
					}
          $sno=1;
          if(!empty($wpts_exams)){
  					foreach ($wpts_exams as $wpts_exam){
              $sublist	=	'';
              if( !empty($wpts_exam->subject_id) ) {
                $subject_list	=	array();
  							$subjectlist	=	$wpdb->get_results("SELECT sub_name FROM $subject_table WHERE id IN($wpts_exam->subject_id)", ARRAY_A );
  							foreach( $subjectlist as $list ) {
  								$subject_list[]	= $list['sub_name'];
  							}
  							$sublist	=	implode(", ",$subject_list);
  						}
  						?>
  						<tr id="<?php echo esc_attr($wpts_exam->eid);?>" class="pointer">
  							<td><?php echo esc_html($sno);?></td>
  							<td><?php echo  esc_html($wpts_exam->e_name);?></td>
  							<td><?php echo  esc_html($wpts_exam->c_name);?> </td>
  							<td style="width: 580px;"><?php echo esc_html($sublist); ?> </td>
  							<td><?php echo  wpts_ViewDate(esc_html($wpts_exam->e_s_date)); ?></td>
  							<td><?php echo  wpts_ViewDate(esc_html($wpts_exam->e_e_date));?></td>
  						</tr>
  						<?php
  						$sno++;
  					}
          }
          ?>
        </tbody>
      </table>
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
