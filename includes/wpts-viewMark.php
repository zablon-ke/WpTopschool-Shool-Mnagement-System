<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$class_id	=	sanitize_text_field($_POST['ClassID']);
$subject_id	=	intval($_POST['SubjectID']);
$exam_id	=	intval($_POST['ExamID']);
$error		=	'';

if( empty( $class_id ) ) {
	$error .='<li>ClassID Missing!</li>';
} if( empty( $subject_id ) ) {
	$error .='<li>SubjectID Missing!</li>';
} if( empty( $exam_id ) ) {
	$error .='<li>ExamID Missing!</li>';
}
if(empty( $error ) && (wpts_IsMarkEntered( $class_id, $subject_id, $exam_id ) ) ) {
	$extra_tbl		=	$wpdb->prefix."wpts_mark_fields";
	$student_table	=	$wpdb->prefix."wpts_student";
	$extra_fields	=	$wpdb->get_results("select * from $extra_tbl where subject_id='".esc_sql($subject_id)."'");
	$wpts_marks		=	wpts_GetMarks($class_id,$subject_id,$exam_id);
	$wpts_exmarks	=	wpts_GetExMarks($subject_id,$exam_id);
	// $class_students	=	$wpdb->get_results("select s_rollno,wp_usr_id,s_fname,s_mname,s_lname from $student_table where class_id='$class_id'",ARRAY_A);
	if( isset($_POST['ClassID'] )) {
		$class_id=sanitize_text_field($_POST['ClassID']);
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
				 if(!empty($class_id_array)){
					 if(in_array($class_id, $class_id_array)){
						 $stl[] = $stu->sid;
					 }
			 }
			}
		}
	}
	$students_list	=	$extra_marks	=	array();
	if (!empty($stl)) {
        $stl = array_map('intval', $stl);
		foreach ($stl as $id ) {
        $id = esc_sql($id);
		$class_students	=	$wpdb->get_results("select s_rollno,wp_usr_id,s_fname,s_mname,s_lname from $student_table where sid = '$id' ",ARRAY_A);
			foreach($class_students as $cstud){
				$students_list[$cstud['wp_usr_id']]=array('rollno'=>$cstud['s_rollno'],'name'=>$cstud['s_fname'].' '.$cstud['s_mname'].' '.$cstud['s_lname']);
			}
		}
	}
	foreach($wpts_exmarks as $exmark){
		$extra_marks[$exmark->student_id][$exmark->field_id]=$exmark->mark;
	}
?>
	<div class="wpts-col-md-12 wpts-col-lg-12 wpts-col-sm-12" id="marks-information">
		<?php
			$classTable	=	$wpdb->prefix.'wpts_class';
			$className	=	$wpdb->get_var( "SELECT c_name FROM `$classTable` where cid='".esc_sql($class_id)."'" );
			$subTable	=	$wpdb->prefix."wpts_subject";
			$subName	=	$wpdb->get_var( "SELECT sub_name FROM `$subTable` where id='".esc_sql($subject_id)."'" );
			$exTable	=	$wpdb->prefix.'wpts_exam';
			$examName	=	$wpdb->get_var( "SELECT e_name FROM `$exTable` where eid='".esc_sql($exam_id)."'" );
			echo '<div class="wp-mark-info" style="display:none;">';
			echo !empty( $className ) ? '<b>Class Name 		: </b>'.esc_html($className) : '';
			echo !empty( $subName ) ? '<br><b>Subject Name	: </b>'.esc_html($subName) : '';
			echo !empty( $examName ) ? '<br><b>Exam Name	: </b>'.esc_html($examName) : '';
			echo '</div>';
		?>
		<table class="wpts-table wpts-table-bordered table-striped table-responsive" id="wp-student-mark" style="width:100%">
			<thead>
				<tr>
					<th width="10%" class="nosort">#</th>
					<th><?php echo esc_html("RollNo.","wptopschool");?></th>
					<th><?php echo esc_html("Name","wptopschool");?></th>
					<th><?php echo esc_html("Mark","wptopschool");?></th>
					<?php
					if(!empty($extra_fields)){
							foreach($extra_fields as $extf){?>
								<th><?php echo esc_html($extf->field_text);?></th>
						<?php }
					} ?>
					<th><?php echo esc_html("Remark","wptopschool");?></th>
				</tr>
			</thead>
			<tbody>
				<?php $sno=1; foreach($wpts_marks as $mark){ ?>
				<tr>
					<td><?php echo esc_html($sno);?> </td>
					<td> <?php echo isset( $students_list[$mark->student_id]['rollno'] ) ? esc_html($students_list[$mark->student_id]['rollno']) : '';?> </td>
					<td> <?php echo isset( $students_list[$mark->student_id]['name'] ) ? esc_html($students_list[$mark->student_id]['name']) : '';?> </td>
					<td><?php echo esc_html($mark->mark); ?> </td>
					<?php if(!empty($extra_fields)){
							foreach($extra_fields as $extf){?>
							<td>
							<?php echo isset( $extra_marks[$mark->student_id][$extf->field_id] ) ? esc_html($extra_marks[$mark->student_id][$extf->field_id]) : ''; ?> </td>
						<?php }
					} ?>
					<td> <?php echo esc_html($mark->remarks); ?> </td>
				</tr>
				<?php $sno++; } ?>
			</tbody>
		</table>
	</div>
	<?php
		$proversion		=	wpts_check_pro_version();
		$proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
		$protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
		$prodisable		=	!$proversion['status'] ? 'disabled="disabled"'	: '';
	?>
	<div class="wpts-col-md-8 wpts-col-md-offset-4">
		<form id="ExportMarksForm" name="ExportMarks" method="POST">
			<input type="hidden" name="ClassID" value="<?php echo esc_attr($class_id);?>">
			<input type="hidden" name="SubjectID" value="<?php echo esc_attr($subject_id);?>">
			<input type="hidden" name="ExamID" value="<?php echo esc_attr($exam_id);?>">
			<input type="hidden" name="exportmarks" value="exportmarks">
			<button type="button" class="wpts-btn wpts-btn-primary <?php echo esc_attr($proclass);?>" id="Exportmarksbutton" title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable); ?>><i class="fa fa-download"></i> <?php echo esc_html("Export","wptopschool");?> </button>
			<button type="button" class="wpts-btn wpts-btn-primary <?php echo esc_attr($proclass);?>" title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable); ?> id="PrintMarks"><i class="fa fa-print"></i> <?php echo esc_html("Print","wptopschool");?> </button>
		</form>
	</div>
<?php
} else {
	$error .="<li>Marks not yet entered!</li>";
?>

<div class="wpts-popupMain wpts-popVisible" id="SuccessModal" style="display:block;">
 <div class="wpts-overlayer"></div>
 <div class="wpts-popBody wpts-alert-body">
	 <div class="wpts-popInner">
		 <a href="javascript:;" class="wpts-closePopup"></a>
		 <div class="wpts-popup-cont wpts-alertbox wpts-alert-danger">
			 <div class="wpts-alert-icon-box">
				 <!-- <i class="icon wpts-icon-tick-mark"></i> -->
				 <i class="icon wpts-icon-question-mark"></i>
			 </div>
			 <div class="wpts-alert-data">
				 <!-- <input type="hidden" name="teacherid" id="teacherid"> -->
				 <h4><?php echo esc_html( 'Error', 'wptopschool' ); ?></h4>
				 <p><?php echo wp_kses_post( $error); ?></p>
			 </div>
		 </div>
	 </div>
 </div>
</div>

<?php }?>
