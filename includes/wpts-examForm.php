<?php if(!defined('ABSPATH')) exit;
$extable = $wpdb->prefix."wpts_exam";
$examname = $examsdate = $examedate = $classid = $examid = '';
$subjectid = array();
if(isset($_GET['id'])){
	$examid = intval($_GET['id']);
	$wpts_exams = $wpdb->get_results( "select * from $extable where eid='".esc_sql($examid)."'");
	foreach($wpts_exams as $examdata){
	 $classid = $examdata->classid;
	$examname = $examdata->e_name;
	$examsdate = $examdata->e_s_date;
	$examedate = $examdata->e_e_date;
	$subjectid = explode( ",",$examdata->subject_id);
	}
}
$label = isset($_GET['id']) ? apply_filters( 'wpts_exam_update_heading_item', esc_html__( 'Update Exam Information' , 'wptopschool' )): apply_filters( 'wpts_exam_add_heading_item', esc_html__('Add Exam Information' , 'wptopschool' ));
$formname = isset($_GET['id']) ? 'ExamEditForm' : 'ExamEntryForm';
$buttonname = isset($_GET['id']) ? apply_filters( 'wpts_exam_update_button_text', esc_html__( 'Update' , 'wptopschool' )) : apply_filters( 'wpts_exam_submit_button_text', esc_html__('Submit' , 'wptopschool' ));
?>
<!-- This form is used for Add/Update New Exam Information -->
<div id="formresponse"></div>
<form name="<?php echo esc_attr($formname);?>" action="#"
	id="<?php echo esc_attr($formname);?>" method="post">
	<div class="wpts-row">
	<div class="wpts-col-xs-12">
		<div class="wpts-card">
			<div class="wpts-card-head">
				<h3 class="wpts-card-title">
					<?php echo $label; ?>
				</h3>
			</div>
			<div class="wpts-card-body">
				<div class="wpts-row">
					<?php  do_action('wpts_before_exam_fields');
            $is_required_item = apply_filters('wpts_exam_fields_is_required',array());
           ?>
					<div class="wpts-col-lg-4 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
						<div class="wpts-form-group">
							<input type="hidden"  id="wpts_locationginal" value="<?php echo esc_url(admin_url());?>"/>
                            <?php
                            /*Check Required Field*/
                            if(isset($is_required_item['class_name'])){
                                $is_required =  esc_html($is_required_item['class_name'],"wptopschool");
                            }else{
                                $is_required = true;
                            }
                            ?>
								<label class="wpts-label" for="Name"><?php esc_html_e("Class Name","wptopschool"); ?>
									<span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
									<?php if($current_user_role=='teacher') {} else {?>
								</label>
							<?php }?>
								<?php
								$classQuery	=	"select cid,c_name from $ctable";
								if($current_user_role=='teacher') {
								$cuserId		=	intval($current_user->ID);
								$classQuery		=	"select cid,c_name from $ctable where teacher_id='".esc_sql($cuserId)."'";
								}
								$wpts_classes 	=	$wpdb->get_results( $classQuery );

								if($current_user_role=='teacher') {
								echo ' : '.esc_html($wpts_classes[0]->c_name);
								echo '<input type="hidden" name="class_name" id="class_name" value="'.esc_attr($wpts_classes[0]->cid).'">';
								echo '</label>';
										}
								else {	?>
									<select name="class_name" data-is_required="<?php echo esc_attr($is_required); ?>" id="class_name" class="wpts-form-control">
										<option value=""><?php echo esc_html("Select Class","wptopschool");?></option>
										<?php	foreach($wpts_classes as $value) {
											$classlistid = intval($value->cid);?>
										<option value="<?php echo esc_attr(intval($value->cid));?>"
											<?php if($classlistid == $classid) echo esc_html("selected","wptopschool"); ?>>
											<?php echo esc_html($value->c_name);?>
										</option>
										<?php }	?>
									</select>
									<?php } ?>
								</div>
							</div>
							<div class="wpts-col-lg-4 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
								<div class="wpts-form-group">
                  <?php
                   /*Check Required Field*/
                   if(isset($is_required_item['ExName'])){
                       $is_required =  esc_html($is_required_item['ExName'],"wptopschool");
                   }else{
                       $is_required = true;
                   }
                   ?>
                    <label class="wpts-label" for="Name"><?php esc_html_e("Exam Name","wptopschool"); ?>
                            <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
                    </label>
                    <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" ID="ExName" name="ExName" value="<?php echo esc_attr($examname); ?>">
                    </div>
                </div>
				<div class="wpts-col-lg-4 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
				<div class="wpts-form-group">
                    <?php
                     /*Check Required Field*/
                     if(isset($is_required_item['ExStart'])){
                         $is_required =  esc_html($is_required_item['ExStart'],"wptopschool");
                     }else{
                         $is_required = true;
                     }
                     ?>
                        <label class="wpts-label" for="Name"><?php esc_html_e("Exam Start Date","wptopschool"); ?>
                            <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
                        </label>
                        <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control hasDatepicker" ID="ExStart" name="ExStart" value="<?php echo esc_attr($examsdate); ?>">
                        </div>
                    </div>
                    <div class="wpts-col-lg-4 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                      <?php
                       /*Check Required Field*/
                       if(isset($is_required_item['ExEnd'])){
                           $is_required =  esc_html($is_required_item['ExEnd'],"wptopschool");
                       }else{
                           $is_required = true;
                       }
                       ?>
                            <label class="wpts-label" for="Name"><?php esc_html_e("Exam End date","wptopschool"); ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
                            </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control ExEnd hasDatepicker" ID="ExEnd" name="ExEnd" value="<?php echo esc_attr($examedate); ?>">
                            </div>
                        </div>
                        <div class="wpts-col-lg-8 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                            <div class="wpts-form-group exam-subject-list">
                        <?php
                         /*Check Required Field*/
                         if(isset($is_required_item['subjectall'])){
                             $is_required =  esc_html($is_required_item['subjectall'],"wptopschool");
                         }else{
                             $is_required = false;
                         }
                         ?>
						<label class="wpts-label" for="Name"><?php esc_html_e("Subject Name","wptopschool"); ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></label>
                            <input type="checkbox" data-is_required="<?php echo esc_attr($is_required); ?>" name="subjectall" value="All" class="exam-all-subjects wpts-checkbox" id="all">
                                <label for="all" class="wpts-checkbox-label">All</label>
                                <div class="exam-class-list">
                                    <?php $sub_table = $wpdb->prefix."wpts_subject";
                                    if($current_user_role=='teacher') {
                                        $classid = esc_sql($wpts_classes[0]->cid);
                                    }
                                    if(!empty($classid)){
                                        $subjectlist	=	$wpdb->get_results("select sub_name,id from $sub_table where class_id='$classid'");
                                        foreach($subjectlist as $svalue){ ?>
                                    <input type="checkbox" name="subjectid[]" value="<?php echo esc_attr($svalue->id); ?>" class="exam-subjects wpts-checkbox" id="subject-<?php echo esc_attr($svalue->id);?>"
                                        <?php if(in_array($svalue->id, $subjectid)){ ?> checked
                                        <?php } ?> >
                                        <label for="subject-<?php echo esc_attr($svalue->id);?>" class="wpts-checkbox-label">
                                            <?php echo esc_html($svalue->sub_name);?>
                                        </label>
                                        <?php } } ?>
                                    </div>
									</div>
								</div>
									<?php  do_action('wpts_after_exam_fields'); ?>
									</div>
											<?php if(!empty($examid)){ ?>
											<input type="hidden" ID="ExamID" name="ExamID" value="<?php echo esc_attr($examid); ?>">
											<?php } ?>
												<div class="wpts-row">
													<div class="wpts-col-xs-12">
														<button type="submit" class="wpts-btn wpts-btn-success" id="e_submit">
															<?php echo esc_html($buttonname); ?>
														</button>
														<a href="<?php echo esc_url(wpts_admin_url().'sch-exams')?>" class="wpts-btn wpts-dark-btn" ><?php echo apply_filters( 'wpts_exam_back_button_text', esc_html__( 'Back' , 'wptopschool' )); ?>
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								</form>
								<!-- End of Add/Update Exam Form -->
