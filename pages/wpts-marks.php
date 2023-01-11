<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		if( $current_user_role=='administrator' || $current_user_role=='teacher' ) {
				wpts_topbar();
				wpts_sidebar();
				wpts_body_start();
				$settings_data = [];
				$class_id       =   $subject_id =   $exam_id=0;
				$proversion     =   wpts_check_pro_version();
				$proclass       =   !$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
				$protitle       =   !$proversion['status'] && isset( $proversion['message'] )? $proversion['message']   : '';
				$prodisable     =   !$proversion['status'] ? 'disabled="disabled"'  : '';
				if( isset(  $_POST['MarkAction']  ) ){
						$class_id   =   (isset($_POST['ClassID'])) ?  intval($_POST['ClassID']) : '';
						$subject_id =   (isset($_POST['SubjectID'])? intval($_POST['SubjectID']) : '');
						$exam_id    =   (isset($_POST['ExamID'])? intval($_POST['ExamID']) : '');
				}
				$ctname     =   $wpdb->prefix.'wpts_class';
				$classQuery =   "select `cid`,`c_name` from `$ctname`";
				$msg        =   'Please Add Class Before Adding Marks';
				if( $current_user_role=='teacher' ) {
						$cuserId    =   intval($current_user->ID);
						$classQuery =   "SELECT DISTINCT c.cid,c.c_name FROM wp_wpts_class c
														INNER JOIN wp_wpts_subject s ON s.class_id= c.cid
														WHERE s.sub_teach_id ='".esc_sql($cuserId)."'";
						$msg        =   'Please ask Principal to assign class and subject';
				}
				$clt    =   $wpdb->get_results( $classQuery );
				$wpts_settings_table    =   $wpdb->prefix."wpts_settings";
				$wpts_settings_edit     =   $wpdb->get_results("SELECT * FROM $wpts_settings_table" );

				foreach( $wpts_settings_edit as $sdat ) {
						$settings_data[$sdat->option_name]  =   $sdat->option_value;
				}
				?>
				<div class="wpts-card">
						<div class="wpts-card-head">
								<h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_marks_heading_item',esc_html("Students Marks","wptopschool")); ?></h3>
						</div>
						 <div class="wpts-card-body">
										<?php
										$item =  apply_filters( 'wpts_student_marks_title_item',esc_html("Class Name","wptopschool"));
										 if( empty( $clt ) ) {
												echo '<div class="wpts-text-red col-lg-2">'.esc_html($msg).'</div>';
										} else { ?>
										<form class="wpts-form-horizontal" id="MarkForm" action="" method="post" enctype="multipart/form-data">
												<div class="wpts-row">
												<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
														<div class="wpts-form-group">
													<label class="wpts-label"><?php
										                  esc_html_e("Class","wptopschool");
										              ?></label>
													<select name="ClassID"  id="ClassID" class="wpts-form-control" required>
														<option value=""><?php _e( 'Select Class', 'wptopschool' ); ?> </option>
														<?php foreach( $clt as $cnm ) { ?>
                                                            <option value="<?php echo esc_attr(intval($cnm->cid));?>" <?php if($cnm->cid==$class_id) echo esc_html("selected","wptopschool");?>><?php echo esc_html($cnm->c_name);?></option>
															<?php } ?>
													</select>
														</div>
												</div>
												<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
														<div class="wpts-form-group">
														<label class="wpts-label">
															<?php esc_html_e("Exam","wptopschool");?></label>
																<select name="ExamID" class="wpts-form-control" id="ExamID" required>
																		<?php
																		if( $exam_id > 0 ) {
																				$examtable  =   $wpdb->prefix.'wpts_exam';
																				$examlist   =   $wpdb->get_results("select eid,e_name from $examtable where classid='$class_id'");
																				foreach( $examlist as $exam ) { ?>
																						<option value="<?php echo esc_attr(intval($exam->eid));?>" <?php if($exam->eid==$exam_id) echo esc_html("selected","wptopschool");?>><?php echo esc_html($exam->e_name);?></option>
																				<?php }
																		} else { ?>
																				<option value=""><?php _e( 'Select Exam', 'wptopschool' ); ?> </option>
																		<?php } ?>
																</select>
														</div>
												</div>
												<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
														<div class="wpts-form-group">
														<label class="wpts-label"><?php esc_html_e("Subject","wptopschool");?></label>
																<?php
																$examtable  =   $wpdb->prefix.'wpts_exam';
																if( $exam_id!= '' ) {
                                                                    $exam_id = esc_sql($exam_id);
																		$subjectID =   $wpdb->get_var("select subject_id from $examtable where eid='$exam_id'");
																		$subjectlist    =   explode( ",", $subjectID );

																}

                                                                ?>
																		<select name="SubjectID"  id="SubjectID" class="wpts-form-control" required>
																		<?php if( $subject_id>0 ) {
                                                                            $sub_tbl    =   $wpdb->prefix."wpts_subject";
                                                                            $subInfo    =   $wpdb->get_results("select sub_name,id from $sub_tbl where class_id='".esc_sql($class_id)."'");

                                                                            foreach( $subInfo as $sub_list ) {
                                                                                    if( in_array( $sub_list->id, $subjectlist ) ) {
                                                                                    echo "<option value='".esc_attr($sub_list->id)."'". selected( $subject_id, $sub_list->id, false ).">".$sub_list->sub_name."</option>";
                                                                                    }
																				}
																		} else { ?>
																				<option value=""><?php _e( 'Select Subject', 'wptopschool' ); ?></option>
																		<?php } ?>
																		</select>
														</div>
												</div>
												<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12 <?php echo esc_attr($proclass);?>" title="" <?php echo esc_html($prodisable); ?>>
														<div class="wpts-form-group">
																<label class="wpts-label"><?php _e( 'Attach CSV', 'wptopschool'); ?></label>
																<span <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
																		<div class="wpts-btn wpts-btn-file" <?php echo esc_html($prodisable); ?>>
																		<span><i class="fa fa-file-text-o"></i> <?php esc_html_e( 'Attach CSV File', 'wptopschool' );?></span>
																		<input type="file" name="MarkCSV" class="<?php echo esc_attr($proclass);?> wpts-form-control" title="" <?php echo esc_html($prodisable); ?>>
																		</div>
																</span>
																<span class="text"></span>

														</div>
												</div>
												<div class="clearfix"></div>
												<div class="wpts-col-sm-8">
														<div class="wpts-form-group">
																<button type="submit" class="wpts-btn wpts-btn-success MarkAction update-btn" name="MarkAction"  value="Add Marks"><?php _e( 'Add/Update', 'wptopschool'); ?> </button>
																<span <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
																<button type="submit" name="MarkAction" class="wpts-btn wpts-dark-btn update-btn MarkAction <?php echo esc_attr($proclass);?>" <?php echo esc_html($prodisable); ?> value="ImportCSV"><?php _e( 'Upload CSV', 'wptopschool'); ?></button>
																</span>
																<button name="MarkAction" class="wpts-btn wpts-btn-primary update-btn" id="viewmarks" value="View Marks"><?php _e( 'View Marks', 'wptopschool'); ?> </button>
														</div>
												</div>
												</div>
										</form>
										<?php

												if(isset($_POST['MarkAction']) && sanitize_text_field($_POST['MarkAction'])=='Add Marks'){
														$mark_entered   =   '';
														//Get Extra Fields
														$extra_tbl      =   $wpdb->prefix."wpts_mark_fields";
														$extra_fields   =   $wpdb->get_results("select * from $extra_tbl where subject_id='".esc_sql($subject_id)."'");
														if( wpts_IsMarkEntered( $class_id,$subject_id,$exam_id ) ) {
																$wpts_marks     =   wpts_GetMarks($class_id,$subject_id,$exam_id);
																$mark_entered   =   1;
																$wpts_exmarks   =   wpts_GetExMarks($subject_id,$exam_id);
																foreach($wpts_exmarks as $exmark){
																		$extra_marks[$exmark->student_id][$exmark->field_id]=$exmark->mark;
																}
														}
												?>
												<div id="mark_entry" class="col-md-12 col-lg-12 col-sm-12">
														<?php if( $mark_entered ==1 ) { ?>
																<h3 class="wpts-card-title"><?php _e( 'Marks Already Entered update here!', 'wptopschool'); ?></h3><br/>
														<?php } else {  ?>
																<h3 class="wpts-card-title"><?php _e( 'Enter Marks', 'wptopschool'); ?></h3>
														<?php } ?>
														<div class="">
																<form class="form-horizontal group-border-dashed" id="AddMarkForm" action="" style="border-radius: 0px;" method="post">
                                                        <input class="form-control" type="hidden" value="<?php echo esc_attr($subject_id);?>" name="SubjectID">
                                                        <input class="form-control" type="hidden" value="<?php echo esc_attr(intval($class_id));?>" name="ClassID">
                                                        <input class="form-control" type="hidden" value="<?php echo esc_attr(intval($exam_id));?>" name="ExamID">
<table class="wpts-table" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr>
<th><?php _e( 'RollNo.', 'wptopschool'); ?></th>
<th><?php _e( 'Name', 'wptopschool' ); ?></th>
<!-- <th><?php _e( 'Mark', 'wptopschool' );?></th> -->
<?php  if((!isset($settings_data['markstype'])) || ($settings_data['markstype'] == "Number"))
																								{ ?>
<th><?php _e( 'Marks', 'wptopschool' );?></th>
																								<?php }
else {?>
						<th><?php _e( 'Grade', 'wptopschool' );?></th>
										<?php } ?>
<th><?php _e( 'Remarks', 'wptopschool');?></th>
<?php if(!empty($extra_fields)){
foreach($extra_fields as $extf){
																								?>
<th><?php echo esc_html($extf->field_text);?></th>
<?php } } ?>
</tr>
																				</thead>
																				<tbody>
<?php
														if($mark_entered==1)
																				{
$stable     =   $wpdb->prefix."wpts_student";
																						$sno        =   1;
																						$stl = [];
																						$studentlists   =   $wpdb->get_results("select class_id, sid from $stable");
																						echo "<br />";
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
							if (empty($stl)) {
							echo "<tr><td>".__( 'No Students to retrive', 'wptopschool')."</td></tr>";
							}else {
						foreach ($stl as $id ) {
                            $getslist  =   $wpdb->get_results("select * from $stable WHERE sid = $id order by CAST('s_rollno' as SIGNED)");
                            foreach ($getslist as $student ) {
                            $usid       =   intval($student->wp_usr_id);
						    $stroll     =   $student->s_rollno;
					$stfullname =   $student->s_fname.' '.$student->s_mname.' '.$student->s_lname;
                    $marktable  =   $wpdb->prefix."wpts_mark";
				$getmark    =   $wpdb->get_row("select * from $marktable WHERE class_id='$class_id' AND student_id='".esc_sql($usid)."' AND subject_id='".esc_sql($subject_id)."' AND exam_id='".esc_sql($exam_id)."' ");
				$getmarkid      =   isset( $getmark->mid ) ? $getmark->mid : '';
				if( empty($getmark) ) {
					$mark_data  =   array( 'subject_id'=>$subject_id,'class_id'=>$class_id,'student_id'=>$usid,'exam_id'=>$exam_id );
					$m_ins      =   $wpdb->insert($marktable,$mark_data);
					if( $wpdb->insert_id )
										$getmarkid = $wpdb->insert_id;
									}
						?>
																	<tr>
										<td class="number"><?php echo esc_html($stroll);?></td>
									<td class="number"><?php echo esc_html($stfullname);?></td>
													<td class="sch_mark">
											<?php  if((!isset($settings_data['markstype'])) || ( $settings_data['markstype'] == "Number"))
															{
									        $classvar = "class='numbers wpts-form-control'";
															}
													else
											{
												$classvar = "class='textboxvalue wpts-form-control'";
										}
										?>
									<input type="text" <?php  echo wp_kses_post($classvar);?> id="v_marks" value="<?php echo ((isset($getmark->mark)? esc_attr($getmark->mark) : ''));  ?>" name="marks[<?php echo esc_attr($getmarkid);?>][]">
																								</td>
										<td class="sch_remarks">
										<input type="text" class="textcls wpts-form-control"  value="<?php echo esc_attr($getmark->remarks);  ?>" name="remarks[<?php echo esc_attr($getmarkid);?>][]">
									</td>
									<?php if(!empty($extra_fields)){
									foreach($extra_fields as $extf){
													?>
								<td><input type="text" class="numbers wpts-form-control" id="v_exmark" min="0" name="exmarks[<?php echo $usid;?>][<?php echo esc_html($extf->field_id);?>]" value="<?php echo ((isset($extra_marks[$usid][$extf->field_id]) ? esc_attr($extra_marks[$usid][$extf->field_id]) : ''));?>"></td>
									<?php } } ?>
								</tr>
                                <?php
                                $sno++;
                                }
                                 }
                                echo "<input type='hidden' name='update' value='true'>";
                                }
								}else{
									$stable     =   $wpdb->prefix."wpts_student";
															$sno        =   1;
															$stl = [];
								$studentlists   =   $wpdb->get_results("select class_id, sid from $stable");
										echo "<br />";
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
							if (empty($stl)) {
								echo "<tr><td>".__( 'No Students to retrive3', 'wptopschool')."</td></tr>";
								}else {
									foreach ($stl as $id ) {
                                        $id = esc_sql($id);
					 $getslist  =   $wpdb->get_results("select * from $stable WHERE sid = '$id' order by CAST('s_rollno' as SIGNED)");
							foreach( $getslist as $slist ) {
												?>
									<tr>
				<!-- <td class="number"><?php //echo $sno; ?></td> -->
						<td class="number"><?php echo esc_html($slist->s_rollno);?></td>
				<td class="number"><?php echo esc_html($slist->s_fname.' '.$slist->s_mname.' '.$slist->s_lname);?></td>
						<td class="sch_mark">
				<?php  if((!isset($settings_data['markstype'])) || ( $settings_data['markstype'] == "Number"))
														{
                                                          $classvar = "class='numbers markbox wpts-form-control'";
														}
														else {
                                                            $classvar = "class='textboxvalue markbox wpts-form-control'";
														}
																?>
			<input type="text" <?php  echo  wp_kses_post($classvar);?> min="0" value="" name="marks[<?php echo esc_attr($slist->wp_usr_id);?>][]">
			</td>
				<td class="sch_remarks">
			<input type="text" class="textcls wpts-form-control"  value="" name="remarks[<?php echo esc_attr($slist->wp_usr_id);?>][]">
			<!--    <input type="text" class="textcls" id="v_remarks" value="<?php echo esc_attr($getmark->mark);  ?>" name="remarks[<?php echo esc_attr($getmarkid);?>][]"> -->
								</td>
			<?php if(!empty($extra_fields)){
						foreach($extra_fields as $extf){
							?>
									<td><input type="text" class="wpts-form-control" id="v_exmark1" min="0" name="exmarks[<?php echo esc_attr($slist->wp_usr_id);?>][<?php echo esc_html($extf->field_id);?>]"></td>
									</td>
										<?php } } ?>
									</tr>
								<?php
								$sno++;
								} }
							}
						?>
			<?php
				if(empty($stl) && $mark_entered=='0'){
					 echo "<tr><td>".__( 'No Students to retrive', 'wptopschool')."</td></tr>";
					}else { ?><?php }} ?></tbody></table>
					<div class="wpts-row">
						<div class="wpts-col-md-12">
							<input  type="submit" class="wpts-btn wpts-btn-success" id="AddMark_Submit" name="AddMark_Submit"  value="Save Marks">
						</div>
					</div>
					</form>
					</div>
												</div>
												<?php
														}
														else if(isset($_POST['MarkAction']) && sanitize_text_field($_POST['MarkAction'])=='View Marks')
														{
																include_once( WPTS_PLUGIN_PATH .'/includes/wpts-viewMark.php');
														}else{
																do_action( 'wpts_marks_actions' );
														}
												?>
										<?php } ?>
								</div>
						</div>
		<?php
				wpts_body_end();
				wpts_footer();
		}else if( $current_user_role=='parent' ) {
				wpts_topbar();
				wpts_sidebar();
				wpts_body_start();
						global $wpdb;
						$parent_id      =   intval($current_user->ID);
						$student_table  =   $wpdb->prefix."wpts_student";
						$class_table    =   $wpdb->prefix."wpts_class";$cidd = '';
                        // $cidd = sanitize_text_field(stripslashes($_GET['cid']));
						$class_id =   esc_sql(base64_decode(sanitize_text_field($_GET['cid'])));
						$students =   $wpdb->get_results("select st.wp_usr_id, st.class_id, st.sid, CONCAT_WS(' ', st.s_fname, st.s_mname, st.s_lname ) AS full_name,cl.c_name from $student_table st LEFT JOIN $class_table cl ON cl.cid=st.class_id where st.parent_wp_usr_id='".esc_sql($parent_id)."'");
						$child          =   array();
						foreach($students as $childinfo){
								$child[]=array( 'student_id'    =>  $childinfo->wp_usr_id,
																'name'          =>  $childinfo->full_name,
																'class_id'      =>  $childinfo->class_id,
																'class_name'    =>  $childinfo->c_name,
																'sid'   =>  $childinfo->sid );
						}
						?>

						<div class="wpts-card">
								<div class="wpts-card-head">
								<h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_marks_heading_item',esc_html("Students Marks","wptopschool")); ?></h3>
						</div>
						<div class="wpts-card-body">
								<div class="tabbable-line">
										<div class="tabSec wpts-nav-tabs-custom" id="verticalTab">
												<div class="tabList">
												<ul class="wpts-resp-tabs-list">
												<?php $child = sanitize_price_array($child); $i=0;
                                                foreach($child as $ch) {
														if(base64_decode(sanitize_text_field($_GET['sid'])) == $ch['sid']){?>
														<li class="wpts-tabing <?php echo ($i==0)?'active':''?>">
														<?php echo esc_html($ch['name']);?>
														</li>
														<?php } $i++; } ?>
												</ul>
												</div>
										<div class="wpts-tabBody wpts-resp-tabs-container">
												<?php
												$i=0;
												foreach( $child as $ch ) {
														$ch_class=$ch['class_id'];
												?>
												<div class="tab-pane wpts-tabMain <?php echo ($i==0)?'active':''?>" id="<?php echo 'student'.$i;?>">
														<?php
														$student_id =   sanitize_text_field($ch['student_id']);
														wpts_MarkReport( $student_id, $class_id );
														?>
												<?php
												$i++;
												}
												?>
										</div>
								</div>
						</div>
				</div>
				</div>
				</div>
				</div>
				<?php
				wpts_body_end();
				wpts_footer();
		}else if( $current_user_role=='student' ) {
				wpts_topbar();
				wpts_sidebar();
				wpts_body_start();
				$student_id=intval($current_user->ID);
                $ciid = sanitize_text_field(stripslashes($_GET['cid']));
				$class_id = intval(base64_decode($ciid));
				?>

				<div class="wpts-card">
						<div class="wpts-card-head">
						    <h3 class="wpts-card-title"><?php esc_html_e( 'Your Marks', 'wptopschool' );?></h3>
						</div>
						<div class="wpts-card-body">
								<div class="gap-top-bottom">
										<?php wpts_MarkReport($student_id, $class_id); ?>
								</div>
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
