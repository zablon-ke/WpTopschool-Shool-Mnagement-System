<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
$subjectclassid =	intval($_GET['classid']);
$teacher_table=	$wpdb->prefix."wpts_teacher";
$teacher_data = $wpdb->get_results("select * from $teacher_table");
$class_table	=	$wpdb->prefix."wpts_class";
$classQuery		=	$wpdb->get_results("select * from $class_table where cid='".esc_sql($subjectclassid)."'");
foreach($classQuery as $classdata){
	$cid= intval($classdata->cid);
}
?>
<!-- This form is used for Add New Subject Form -->
<div class="formresponse"></div>
<form name="SubjectEntryForm" action="#" id="SubjectEntryForm" method="post">
		<div class="wpts-card">
				<div class="wpts-card-head">
					<div class="wpts-row">
						<div class="wpts-col-xs-12">
						 <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_subject_heading_item', esc_html__( 'New Subject Entry', 'wptopschool' )); ?></h3>
						</div>
					</div>
				</div>

				<input type="hidden"  id="wpts_locationginal1" value="<?php echo esc_url(admin_url());?>"/>
				<div class="wpts-card-body">
					<div class="wpts-row">
					<div class="wpts-col-md-12 line_box">
						<?php wp_nonce_field( 'SubjectRegister', 'subregister_nonce', '', true ); ?>
						<div class="wpts-row">
							<?php
                  do_action('wpts_before_subject_detail_fields');
                  /*Required field Hook*/
                  $is_required_item = apply_filters('wpts_subject_fields_is_required',array());
              ?>
						<div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
							<div class="wpts-form-group">
								<label class="wpts-label" for="Name">
                                <?php
                                    esc_html_e("Class","wptopschool");
                  /*Check Required Field*/
                  if(isset($is_required_item['SCID'])){
                      $is_required =  esc_html($is_required_item['SCID'],"wptopschool");
                  }else{
                      $is_required = true;
                  }
                  ?>
                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
								</label>
								<select name="SCID" data-is_required="<?php echo esc_attr($is_required); ?>" id="SCID" class="wpts-form-control" required>
								<option value="" ><?php echo esc_html("Please Select Class","wptopschool");?></option>
								<?php
								foreach($sel_class as $classes) { $sel_classid = ''; ?>
									<option value="<?php echo esc_attr(intval($classes->cid));?>" <?php if($sel_classid==$classes->cid) echo esc_html("selected","wptopschool"); ?>><?php echo esc_html($classes->c_name);?></option>
								<?php } ?>

							</select>
							<!-- <?php foreach($classQuery as $classdata){
								$cid= $classdata->cid; ?>
								<label class="wpts-labelMain" for="Name">Class Name : <?php if($cid == $subjectclassid) echo esc_attr($classdata->c_name);?></label>
									<input type="hidden" class="wpts-form-control" id="SCID" name="SCID" value="<?php if($cid == $subjectclassid) echo esc_attr($classdata->cid);?>">
								<?php } ?> -->
							</div>
						</div>
						</div>
						<?php for($i=1;$i<=5;$i++){?>
						<div class="wpts-row">
								<div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
									<div class="wpts-form-group">
						<?php
	                    /*Check Required Field*/
	                    if(isset($is_required_item['SNames'])){
	                        $is_required =  esc_html($is_required_item['SNames'],"wptopschool");
	                    }else{
	                        $is_required = true;
	                    }
	                    ?>
									<label class="wpts-label" for="Name"><?php echo esc_html_e("Subject Name","wptopschool")." ".intval($i);?><?php if($i=='1') { ?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
									<?php } ?></label>
									<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" name="SNames[]">
									</div>
								</div>

								<div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
									<div class="wpts-form-group">
										<label class="wpts-label" for="Name"><?php
                      esc_html_e("Subject Code","wptopschool");
                      /*Check Required Field*/
                      if(isset($is_required_item['SCodes'])){
                          $is_required =  esc_html($is_required_item['SCodes'],"wptopschool");
                      }else{
                          $is_required = false;
                      }
                      ?>
                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
                    </label>
					<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" name="SCodes[]">
					</div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                        <label class="wpts-label" for="Name">
                    <?php
                          esc_html_e("Subject Teacher","wptopschool")."<span> (Incharge)</span>";
                      /*Check Required Field*/
                      if(isset($is_required_item['STeacherID'])){
                          $is_required =  esc_html($is_required_item['STeacherID'],"wptopschool");
                      }else{
                          $is_required = false;
                      }
                      ?>
                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?>
                    </label>
                                <select name="STeacherID[]"  data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control">
                                    <option value=""><?php echo esc_html_e("Please Select Teacher","wptopschool");?></option>
                                        <?php
                                        foreach ($teacher_data as $teacher_list) {
                                            $teacherlistid= $teacher_list->wp_usr_id;?>
                                            <option value="<?php echo esc_attr($teacherlistid);?>" ><?php echo esc_html($teacher_list->first_name ." ". $teacher_list->last_name);?></option>
                                            <?php
                                        }
                                        ?>
                                </select>
									</div>
								</div>
								<div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
									<div class="wpts-form-group">
										<label class="wpts-label" for="BName">  <?php
                                        esc_html_e("Book Name","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_item['BNames'])){
                                        $is_required =  esc_html($is_required_item['BNames'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></label>
						<input type="text" class="wpts-form-control" name="BNames[]" placeholder="Book Name">
									</div>
								</div>
								<?php if($i!='5') { ?>
								<hr style="border-top:1px solid #5C779E"/>
								<?php }?>

						</div>
						<?php } ?>
						<?php  do_action('wpts_after_subject_detail_fields'); ?>
					</div>
					<div class="wpts-col-md-12">
						<button type="submit" class="wpts-btn wpts-btn-success" id="s_submit"><?php echo apply_filters( 'wpts_subject_button_submit_label',esc_html("Submit","wptopschool"));?></button>
						 <a href="<?php echo esc_url(wpts_admin_url().'sch-subject')?>" class="wpts-btn wpts-dark-btn" ><?php echo apply_filters( 'wpts_subject_button_back_label',esc_html("Back","wptopschool"));?></a>
					</div>
				</div>
			</div>
		</div>
</form>
<!-- End of Add Subject Form -->
