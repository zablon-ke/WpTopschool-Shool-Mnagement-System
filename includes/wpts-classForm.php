<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');

$c_fee_type = '';
$ctable=$wpdb->prefix."wpts_class";
$teacher_table=	$wpdb->prefix."wpts_teacher";
$teacher_data = $wpdb->get_results("select wp_usr_id,CONCAT_WS(' ', first_name, middle_name, last_name ) AS full_name from $teacher_table order by tid");
$classname	= $classnumber	= $classcapacity = $classlocation = $classstartingdate = $classendingdate= $teacherid = '';
if( isset( $_GET['id']) ) {
	$classid =	intval($_GET['id']);
	$wpts_classes =$wpdb->get_results("select * from $ctable where cid='".esc_sql($classid)."'");

	foreach ($wpts_classes as $wpts_editclass) {
		$classname=$wpts_editclass->c_name;
		$classnumber=$wpts_editclass->c_numb;
		$classcapacity=$wpts_editclass->c_capacity;
		$classlocation=$wpts_editclass->c_loc;
		$classstartingdate1=$wpts_editclass->c_sdate;

		$classstartingdate = date("m/d/Y", strtotime($classstartingdate1));
		$classendingdate1=$wpts_editclass->c_edate;
		$classendingdate = date("m/d/Y", strtotime($classendingdate1));
		$teacherid=$wpts_editclass->teacher_id;
		if($wpts_editclass->c_fee_type != ''){
			$c_fee_type =$wpts_editclass->c_fee_type  ;
		}
	}
}
// $label	=	isset( $_GET['id'] ) ? apply_filters( 'wpts_class_main_heading_update', esc_html__( 'Update Class Information', 'wptopschool' )) : apply_filters( 'wpts_class_main_heading_add', esc_html__( 'Add Class Information', 'wptopschool' ));
$formname		=	isset( $_GET['id'] ) ? 'ClassEditForm' : 'ClassAddForm';
$buttonname	=	isset( $_GET['id'] ) ? 'Update' : 'Submit';
$propayment = wpts_check_pro_version('pay_WooCommerce');
$propayment = !$propayment['status'] ? 'notinstalled'    : 'installed';
?>
<!-- This form is used for Add/Update Class -->
<div id="formresponse"></div>
<form name="<?php echo esc_attr($formname);?>" id="<?php echo esc_attr($formname); ?>" method="post">
	<?php if( isset( $_GET['id']) ) { ?>
		<input type="hidden" name="cid" value="<?php echo esc_attr($classid);?>">
	<?php } ?>
	<div class="wpts-row">
	<div class="wpts-col-xs-12">
		<div class="wpts-card">
			<div class="wpts-card-head">
				<h3 class="wpts-card-title"><?php echo isset( $_GET['id'] ) ? apply_filters( 'wpts_class_main_heading_update', esc_html__( 'Update Class Information', 'wptopschool' )) : apply_filters( 'wpts_class_main_heading_add', esc_html__( 'Add Class Information', 'wptopschool' ));; ?></h3>
			</div>
			<div class="wpts-card-body">
				 <?php wp_nonce_field( 'ClassAction', 'caction_nonce', '', true ) ?>
				<div class="wpts-row">
					<?php  do_action('wpts_before_class_detail_fields');
					  $is_required_item = apply_filters('wpts_class_is_required',array());
					  $item =  apply_filters( 'wpts_class_title_item',esc_html("Class Name","wptopschool"));
					?>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group ">
							<label class="wpts-label" for="Name"><?php esc_html_e("Class Name","wptopschool");
								/*Check Required Field*/
								if(isset($is_required_item['Name'])){
									$is_required =  esc_html($is_required_item['Name'],"wptopschool");
								}else{
									$is_required = true;
								}
								?>
							<span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
							</label>
							<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control"  name="Name"  value="<?php echo esc_attr($classname); ?>">
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
					   <div class="wpts-form-group">
							<label class="wpts-label" for="Number"><?php esc_html_e("Class Number","wptopschool");
								/*Check Required Field*/
								if(isset($is_required_item['Number'])){
									$is_required =  esc_html($is_required_item['Number'],"wptopschool");
								}else{
									$is_required = true;
								}
								?>
							<span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<input data-is_required="<?php echo esc_attr($is_required); ?>" type="text" class="wpts-form-control"  name="Number"  value="<?php echo esc_attr($classnumber); ?>">
							<input type="hidden"  id="wpts_locationginal" value="<?php echo esc_url(admin_url());?>"/>
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group">
							<label class="wpts-label" for="Capacity"><?php esc_html_e("Class Capacity","wptopschool");
								/*Check Required Field*/
								if(isset($is_required_item['capacity'])){
									$is_required =  esc_html($is_required_item['capacity'],'wptopschool');
								}else{
									$is_required = true;
								}
								?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" pattern="[0-9]*" class="wpts-form-control numbers"  name="capacity" id="c_capacity" value="<?php echo esc_attr($classcapacity); ?>" min="0">
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group">
						   <label class="wpts-label" for="Selectteacher"><?php esc_html_e("Class Teacher","wptopschool")."<span> (Incharge)</span>";
 								/*Check Required Field*/
 								if(isset($is_required_item['ClassTeacherID'])){
 									$is_required =  esc_html($is_required_item['ClassTeacherID'],'wptopschool');
 								}else{
 									$is_required = false;
 								}
 								?>
								<span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<select data-is_required="<?php echo esc_attr($is_required); ?>" name="ClassTeacherID" class="wpts-form-control">
								<option value=""><?php echo esc_html("Select Teacher", "wptopschool");?></option>
								<?php
								if(!empty($teacher_data)){
								foreach ($teacher_data as $teacher_list) {
									$teacherlistid= $teacher_list->wp_usr_id;
									?>
								<option value="<?php echo esc_attr($teacherlistid);?>" <?php if($teacherlistid == $teacherid) echo esc_html("selected","wptopschool"); ?> ><?php echo esc_html($teacher_list->full_name);?></option>
								<?php }
								}?>
							</select>
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group">
							<label class="wpts-label" for="Starting"><?php esc_html_e("Class Starting on","wptopschool");
							 /*Check Required Field*/
							 if(isset($is_required_item['Sdate'])){
									 $is_required =  esc_html($is_required_item['Sdate'],"wptopschool");
							 }else{
									 $is_required = true;
							 }
							 ?>
							 <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control select_date wpts-start-date" name="Sdate" value="<?php echo esc_attr($classstartingdate); ?>" readonly>
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group">
							<label class="wpts-label" for="Ending"><?php esc_html_e("Class Ending on","wptopschool");
							 /*Check Required Field*/
							 if(isset($is_required_item['Edate'])){
									 $is_required =  esc_html($is_required_item['Edate'],"wptopschool");
							 }else{
									 $is_required = true;
							 }
							 ?>
							 <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control select_date wpts-end-date" name="Edate"  value="<?php echo esc_attr($classendingdate); ?>" readonly>
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
						<div class="wpts-form-group">
								<label class="wpts-label" for="Location"><?php esc_html_e("Class Location","wptopschool");
								 /*Check Required Field*/
								 if(isset($is_required_item['Location'])){
										 $is_required =  esc_html($is_required_item['Location'],"wptopschool");
								 }else{
										 $is_required = false;
								 }
								 ?>
								 <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
								<input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" name="Location"  value="<?php echo esc_attr($classlocation); ?>">
						</div>
					</div>
					<div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
					<div class="wpts-form-group">
							<label class="wpts-label" for="Location"><?php esc_html_e("Class Fee Type","wptopschool");
							 /*Check Required Field*/
							 if(isset($is_required_item['classfeetype'])){
								$is_required =  esc_html($is_required_item['classfeetype'],"wptopschool");
							 }else{
								$is_required = true;
							 }
							 ?>
							 <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
							<select data-is_required="<?php echo esc_attr($is_required); ?>" name="classfeetype" class="wpts-form-control">
								<option value="" selected disabled><?php echo esc_html("Select Class Fee Type", "wptopschool");?></option>
								 <?php if($propayment == "installed"){
								 	echo esc_html($c_fee_type);?>
								<option value="paid" <?php if($c_fee_type == "paid") echo esc_html("selected","wptopschool"); ?>><?php echo esc_html("Paid", "wptopschool");?></option>
							<?php } ?>
								<option value="free" <?php if($c_fee_type == "free") echo esc_html("selected","wptopschool"); ?>><?php echo esc_html("Free", "wptopschool");?></option>
                            </select>

						</div>
					</div>
					<?php  do_action('wpts_after_class_detail_fields'); ?>
					<div class="wpts-col-xs-12 wpts-btnsubmit-section">
						<button type="submit" class="wpts-btn wpts-btn-success" id="c_submit"><?php echo esc_html($buttonname); ?></button>
						<a href="<?php echo esc_url(wpts_admin_url().'sch-class')?>" class="wpts-btn wpts-dark-btn" ><?php echo esc_html("Back", "wptopschool");?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<!-- End of Add/Update New Class Form -->
