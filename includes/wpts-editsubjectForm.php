<?php if (!defined( 'ABSPATH' ) )
exit('No Such File');
$subjectid=intval($_GET['id']);
$teacher_table=	$wpdb->prefix."wpts_teacher";
$classnumber =	$wpdb->prefix."wpts_class";
$teacher_data = $wpdb->get_results("select * from $teacher_table");
$subtable=$wpdb->prefix."wpts_subject";
$wpts_subjects =$wpdb->get_results("select * from $subtable where id='".esc_sql($subjectid)."'");
foreach ($wpts_subjects as $subject_data) {
	$subid = intval($subject_data->id);
	$classid = intval($subject_data->class_id);
	$subname = $subject_data->sub_name;
	$subcode = $subject_data->sub_code;
	$subteacherid = $subject_data->sub_teach_id;
	$subbookname = $subject_data->book_name;
}
?>
<!-- This form is used for Edit Subject Details -->
<div class="formresponse"></div>
<form action="" name="SubjectEditForm"  id="SEditForm" method="post">
	<div class="wpts-col-xs-12">
		<div class="wpts-card">
			<div class="wpts-card-head">
				<h3 class="wpts-card-title"><?php esc_html_e( 'Edit Subject Details', 'wptopschool' ); ?></h3>
			</div>
			<div class="wpts-card-body">
				<div class="wpts-col-md-12 line_box">
					<div class="wpts-row">
                    <?php $wpts_class =$wpdb->get_results("select c_name from $classnumber where cid='".esc_sql($classid)."'");
                    ?>
                <label class="wpts-labelMain" for="Name"><?php echo esc_html("Class Name","wptopschool");?>: <?php if(!empty($wpts_class)){ echo esc_html($wpts_class[0]->c_name); }else{
                    $wpts_class[0]->c_name = '';
                };?></label>
                </div></div></div>
			<input type="hidden" name="cid" value="<?php echo esc_attr($subid);?>">
			<div class="wpts-card-body">
				<div class="wpts-col-md-12 line_box">
					<div class="wpts-row">
					<?php  do_action('wpts_before_subject_detail_fields'); ?>
						<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
							<div class="wpts-form-group">
								<label class="wpts-label" for="Name"><?php esc_html_e("Subject","wptopschool");?> <span class="wpts-required"> *</span></label>
								<input type="text"   class="wpts-form-control" ID="EditSName" name="EditSName" placeholder="Subject Name" value="<?php echo esc_attr($subname);?>">
								<input type="hidden" class="wpts-form-control" value="<?php echo esc_attr($subid);?>" id="SRowID" name="SRowID">
								<input type="hidden" class="wpts-form-control" value="" id="ESClassID" name="ClassID">
								<input type="hidden" id="wpts_locationginal1" value="<?php echo esc_attr(admin_url());?>"/>
							</div>
						</div>
						<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
							<div class="wpts-form-group">
								<label class="wpts-label" for="Name"><?php esc_html_e("Subject Code","wptopschool");?></label>
								<input type="text" class="wpts-form-control" ID="EditSCode" name="EditSCode" placeholder="Subject Code" value="<?php echo esc_attr($subcode);?>"></div>
							</div>
							<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">							<div class="wpts-form-group">
								<label class="wpts-label" for="Name"><?php esc_html_e("Subject Teacher (Incharge)","wptopschool");?></label>
								<select name="EditSTeacherID" id="EditSTeacherID" class="wpts-form-control">
									<option value=""><?php echo esc_html("Select Teacher","wptopschool");?> </option>
									<?php foreach ($teacher_data as $teacher_list) {
                                        $teacherlistid= intval($teacher_list->wp_usr_id);?>
                                        <option value="<?php echo esc_attr($teacherlistid);?>"
										<?php if($teacherlistid == $subteacherid) echo esc_html("selected","wptopschool"); ?> >
										<?php echo esc_html($teacher_list->first_name ." ". $teacher_list->last_name);?>

										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                                <div class="wpts-form-group">
								<label class="wpts-label" for="BName"><?php esc_html_e("Book Name","wptopschool");?></label>
								<input type="text" class="wpts-form-control" name="EditBName" id="EditBName" placeholder="Book Name" value="<?php echo esc_attr($subbookname);?>">
							</div>
						</div>
						<?php  do_action('wpts_after_subject_detail_fields'); ?>
					</div>
				</div>
				<div class="wpts-col-md-12">
					<input type="submit" id="SEditSave" class="wpts-btn wpts-btn-success" value="Update">
                    <a href="<?php echo esc_url(wpts_admin_url().'sch-subject')?>" class="wpts-btn wpts-dark-btn" ><?php echo esc_html("Back","wptopschool");?></a>
				</div>
			</div>
		</div>
	</div>
</form><!-- End of Edit Subject Details Form -->