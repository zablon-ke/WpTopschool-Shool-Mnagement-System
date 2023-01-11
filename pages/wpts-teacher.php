<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
	if( is_user_logged_in() ) {
		global $current_user, $wp_roles, $wpdb;
    $current_user_role=$current_user->roles[0];
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			?>
			<?php
				if(isset( $_GET['tab'] ) && sanitize_text_field($_GET['tab'])=='addteacher')
				{
					include_once( WPTS_PLUGIN_PATH .'/includes/wpts-teacherForm.php' );
				}
				else if(isset($_GET['id']) && is_numeric($_GET['id']))
				{
					include_once( WPTS_PLUGIN_PATH .'/includes/wpts-teacherProfile.php' );
				}
				else {
					include_once( WPTS_PLUGIN_PATH .'/includes/wpts-teacherList.php' );
				?>
				<?php do_action( 'wpts_teacher_import_html' ); ?>
			<?php
			}
			wpts_body_end();
			wpts_footer();
		}
		if($current_user_role=='parent' || $current_user_role=='student')
		{
			wpts_topbar();
			wpts_sidebar();
			wpts_body_start();
			$ID	=	intval($current_user->ID);
			$teacher_table	=	$wpdb->prefix."wpts_teacher";
			$class_table	=	$wpdb->prefix."wpts_class";
			$subjects_table = 	$wpdb->prefix."wpts_subject";
			$student_table  = 	$wpdb->prefix."wpts_student";
			$queryFiels		=	$current_user_role=='student' ? 'wp_usr_id' : 'parent_wp_usr_id';
			$classlist		=	array();
			$classquery		=	'';

            $ciid = sanitize_text_field(stripslashes($_GET['cid']));
			$classID = esc_sql(base64_decode($ciid));

			?>
	     <div class="wpts-card">
			<div class="wpts-card-head">
        <h3 class="wpts-card-title"><?php esc_html_e( 'Teachers Details', 'wptopschool' ); ?> </h3>
      </div>
			<div class="wpts-card-body">
					<table id="teacher_table" class="wpts-table">
						<thead>
						<tr>
							<th class="nosort">#</th>
							<th><?php _e( 'Full Name', 'wptopschool' ); ?></th>
							<th><?php _e( 'Incharge of Class', 'wptopschool' ); ?></th>
							<th><?php _e( 'Subjects Handling', 'wptopschool' ); ?></th>
							<th><?php _e( 'Phone', 'wptopschool' ); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						if( !empty( $classID ) ) {

							$classquery	=	'AND c.cid='.esc_sql($classID);

             $sub_han =	$wpdb->get_results("select sub_name, sub_teach_id, c.c_name from $subjects_table s, $class_table c where sub_teach_id > 0 AND c.cid = s.class_id $classquery order by c.cid");

              if(!empty($sub_han)){
                foreach($sub_han as $subhan) {
  					$sub_handling[$subhan->sub_teach_id][]=$subhan->sub_name.' ('.$subhan->c_name.')';
  								$teacher[]	=	$subhan->sub_teach_id;
				}
              }else{
                $teacher[]	=	'0';
              }

							$incharges=$wpdb->get_results("select c.c_name,c.teacher_id from $class_table c LEFT JOIN $teacher_table t ON t.wp_usr_id=c.teacher_id where c.teacher_id>0 $classquery");
							foreach($incharges as $incharge){
								$cincharge[$incharge->teacher_id][]=$incharge->c_name;
							}
							if( !empty( $teacher ) && !empty($classID) ) {
								$teacherQuery	=	' WHERE wp_usr_id IN ('.implode( ", " , $teacher ).') ';
							}
							$teachers = $wpdb->get_results("select * from $teacher_table $teacherQuery order by tid DESC");
							$sno		=	0;

							foreach($teachers as $tinfo)
							{
								$loc_avatar	=	get_user_meta($tinfo->wp_usr_id,'simple_local_avatar',true);
								$img_url	=	$loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL.'img/avatar.png';
								$sno	=	$sno+1;
							?>
							<tr>
								<td><?php echo esc_html($sno);?></td>
								<td><?php echo esc_html($tinfo->first_name." ". $tinfo->middle_name." ".$tinfo->last_name);?></td>
								<td><?php if(isset($cincharge[$tinfo->wp_usr_id])) { echo esc_html(implode( ", ", $cincharge[$tinfo->wp_usr_id])); } else { echo '-'; } ?></td>
								<td><?php if(isset($sub_handling[$tinfo->wp_usr_id])) { echo esc_html(implode( ", ", $sub_handling[$tinfo->wp_usr_id] )); } else { echo '-'; } ?></td>
								<td><?php echo esc_html($tinfo->phone);?></td>
							</tr>
							<?php }	?>
						<?php } ?>
						</tbody>
						<tfoot>
						<tr>
							<th>#</th>
							<th><?php _e( 'Full Name', 'wptopschool' ); ?></th>
							<th><?php _e( 'Incharge of Class', 'wptopschool' ); ?></th>
							<th><?php _e( 'Subjects Handling', 'wptopschool' ); ?></th>
							<th><?php _e( 'Phone', 'wptopschool' ); ?></th>
						</tr>
						</tfoot>
					</table>
			</div>
		</div>
		<?php
			wpts_body_end();
			wpts_footer();
		}?>
		<div class="wpts-popupMain" id="ViewModal">
		  <div class="wpts-overlayer"></div>
		  <div class="wpts-popBody">
		    <div class="wpts-popInner">
		    	<a href="javascript:;" class="wpts-closePopup"></a>
				<div id="ViewModalContent">
				</div>
		    </div>
		  </div>
		</div>
		<?php
	}
	else {
		include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
	}
	?>
