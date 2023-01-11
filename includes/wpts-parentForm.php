<?php if (!defined( 'ABSPATH' ) )exit('No Such File');
	global $current_user, $wpdb;
	$current_user_role=$current_user->roles[0];
?>
<!-- This form is used for Add New Parent -->
<div id="formresponse"></div>
<form name="ParentEntryForm" id="ParentEntryForm" method="post">
	<div class="wpts-card">
		<div class="wpts-card-head">
			<h3 class="wpts-card-title"><?php esc_html_e("New Parent entry","wptopschool");?></h3>
		</div>
		<div class="wpts-card-body">
		<div class="wpts-col-md-6">
			<?php wp_nonce_field( 'ParentRegister', 'pregister_nonce', '', true ) ?>
			<div class="wpts-row">
					<div class="wpts-col-md-4">
						<div class="wpts-form-group">
							<label class="wpts-label" for="firstname"><?php echo esc_html_e("Firstname","wptopschool");?> <span class="wpts-required">*</span></label>
							<input type="text" class="wpts-form-control" id="firstname" name="firstname" placeholder="First Name">
						</div>
					</div>
					<div class="wpts-col-md-4">
						<div class="wpts-form-group">
							<label class="wpts-label" for="middlename"><?php echo esc_html_e("Middlename","wptopschool");?> <span class="wpts-required">*</span></label>
							<input type="text" class="wpts-form-control" id="middlename" name="middlename" placeholder="Middle Name">
						</div>
					</div>
					<div class="wpts-col-md-4">
						<div class="wpts-form-group">
							<label class="wpts-label" for="lastname"><?php echo esc_html_e("Lastname","wptopschool");?> <span class="wpts-required">*</span></label>
							<input type="text" class="wpts-form-control" id="lastname" name="lastname" placeholder="Last Name">
						</div>
					</div>
			</div>

			<div class="wpts-form-group">
				<label class="wpts-label" for="Username"><?php echo esc_html_e("Username","wptopschool");?> <span class="wpts-required">*</span></label>
				<input type="text" class="wpts-form-control" id="Username" name="Username" placeholder="Parent Username">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="Email"><?php echo esc_html_e("Email address","wptopschool");?> <span class="wpts-required">*</span></label>
				<input type="email" class="wpts-form-control" id="Email" name="Email" placeholder="parent Email">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="Password"><?php echo esc_html_e("Password","wptopschool");?> <span class="wpts-required">*</span></label>
				<input type="password" class="wpts-form-control" id="Password" name="Password" placeholder="Password">
			</div>
			<div class="wpts-form-group">
				<label for="ConfirmPassword"><?php echo esc_html_e("Confirm Password","wptopschool");?> <span class="wpts-required">*</span></label>
				<input type="password" class="wpts-form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="educ"><?php echo esc_html_e("Education","wptopschool");?></label>
				<input type="text" class="wpts-form-control" id="Qual" name="Qual" placeholder="Highest Education Degree">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="dateofbirth"><?php echo esc_html_e("Date of Birth","wptopschool");?></label>
				<input type="text" class="wpts-form-control select_date" id="Dob" name="Dob" placeholder="Date of Birth">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="displaypicture"><?php echo esc_html_e("Profile Image","wptopschool");?></label>
				<input type="file" name="displaypicture" id="displaypicture">
				<p id="test" style="color:red"></p>
			</div>
		</div>
		<div class="wpts-col-md-6">
			<div class="wpts-form-group parent-student-list">
				<label class="wpts-label" for="position"><?php echo esc_html_e("Select Student","wptopschool");?></label>
				<?php
				$class_table	=	$wpdb->prefix."wpts_class";
				$classQuery		=	"select cid,c_name from $class_table";
				if( $current_user_role=='teacher' ) {
					$cuserId	=	intval($current_user->ID);
					$classQuery	=	"select cid,c_name from $class_table where teacher_id='".esc_sql($cuserId)."'";
				}
				$classList		=	$wpdb->get_results( $classQuery );
				?>
				 <select name="child_list[]" id="child_list" data-icon-base="fa" data-tick-icon="fa-check" multiple data-live-search="true" class="selectpicker wpts-form-control">
					<?php foreach( $classList as $classkey=>$classvalue ) { ?>
						<optgroup label="Class Name:<?php echo esc_attr($classvalue->c_name); ?>">
							<?php
								$student_table		=	$wpdb->prefix."wpts_student";
								$studentList		=	$wpdb->get_results("select wp_usr_id,s_fname from $student_table where class_id='$classvalue->cid'");
								foreach( $studentList as $studentkey=> $studentvalue ) {
							?>
							<option value="<?php echo esc_attr(intval($studentvalue->wp_usr_id)); ?>"><?php echo esc_html($studentvalue->s_fname); ?></option>
								<?php } ?>
						</optgroup>
					<?php } ?>
				</select>
			</div>
			<div class="wpts-form-group wpts-gender-field">
				<label class="wpts-label" for="Class"><?php echo esc_html_e("Gender","wptopschool");?></label> <br/>
				<div class="radio">
					<input type="radio" name="Gender" value="Male" checked="checked">
					<label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
				</div>
				<div class="radio">
					<input type="radio" name="Gender" value="Female">
					<label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
				</div>
				<div class="radio">
					<input type="radio" name="Gender" value="Other">
					<label for="Female"><?php echo esc_html_e("Other","wptopschool");?></label>
				</div>
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="position"><?php echo esc_html_e("Profession","wptopschool");?></label>
				<input type="text" class="wpts-form-control" id="profession" name="Profession" placeholder="profession">
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="Address" ><?php echo esc_html_e("Current Address","wptopschool");?></label>
				<textarea name="Address" class="wpts-form-control" rows="4"></textarea>
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="Address" ><?php echo esc_html_e("Permanent Address","wptopschool");?></label>
				<textarea name="pAddress" class="wpts-form-control" rows="5"></textarea>
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="Country"><?php echo esc_html_e("Country","wptopschool");?></label>
				<?php $countrylist = wpts_county_list();?>
				<select class="wpts-form-control" id="Country" name="country">
					<option value=""><?php echo esc_html("Country","wptopschool");?></option>
					<?php
					foreach( $countrylist as $key=>$value ) { ?>
						<option value="<?php echo esc_attr($value);?>"><?php echo esc_html_e($value);?></option>
					<?php
					}
					?>
				</select>
			</div>

			<div class="wpts-row">
				<div class="wpts-col-md-6">
					<div class="wpts-form-group">
						<label class="wpts-label" for="Zipcode"><?php echo esc_html_e("Zipcode","wptopschool");?></label>
						<input type="text" class="wpts-form-control" id="Zipcode" name="zipcode" placeholder="Zipcode">
					</div>
				</div>
				<div class="wpts-col-md-6">
					<div class="wpts-form-group">
						<label class="wpts-label" for="phone"><?php echo esc_html_e("Phone","wptopschool");?></label>
						<input type="text" class="wpts-form-control" id="phone" name="Phone" placeholder="Phone Number">
					</div>
				</div>
			</div>
			<div class="wpts-form-group">
				<label class="wpts-label" for="bloodgroup"><?php echo esc_html_e("Blood Group","wptopschool");?></label>
				<select class="wpts-form-control" id="Bloodgroup" name="Bloodgroup">
					<option value=""><?php echo esc_html("Blood Group","wptopschool");?></option>
					<option value="O+"><?php echo __("O +","wptopschool");?></option>
                    <option value="O-"><?php echo __("O -","wptopschool");?></option>
                    <option value="A+"><?php echo __("A +","wptopschool");?></option>
                    <option value="A-"><?php echo __("A -","wptopschool");?></option>
                    <option value="B+"><?php echo __("B +","wptopschool");?></option>
                    <option value="B-"><?php echo __("B -","wptopschool");?></option>
                    <option value="AB+"><?php echo __("AB +","wptopschool");?></option>
                    <option value="AB-"><?php echo __("AB -","wptopschool");?></option>
				</select>
			</div>
		</div>
		<div class="wpts-col-md-12">
			<button type="submit" class="wpts-btn wpts-btn-primary" id="parentform"><?php echo esc_html("Submit","wptopschool");?></button>
		</div>
	</div>
	</div>
</form>
<!-- End of Add New Parent Form -->