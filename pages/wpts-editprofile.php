<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		 $current_user_role=$current_user->roles[0];
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
    $teacher_table=$wpdb->prefix."wpts_teacher";
    $class_table=$wpdb->prefix."wpts_class";
    $users_table=$wpdb->prefix."users";
$sid = intval(get_current_user_id());
 $tinfo = $wpdb->get_row("select teacher.*,user.user_email from $teacher_table teacher LEFT JOIN $users_table user ON user.ID=teacher.wp_usr_id where teacher.wp_usr_id='".esc_sql($sid)."'");
if( !empty( $stinfo ) ) {
    $loc_avatar=get_user_meta($sid,'simple_local_avatar',true);
    $img_url= $loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
}
    $student_table = $wpdb->prefix."wpts_student";
    $class_table = $wpdb->prefix."wpts_class";
    $users_table = $wpdb->prefix."users";
 if($current_user_role == 'student'){
       $stinfo = $wpdb->get_row("select * from $student_table where wp_usr_id='".esc_sql($sid)."'");
       $loc_avatar=get_user_meta($sid,'simple_local_avatar',true);
       $img_url= $loc_avatar ? $loc_avatar['full'] : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
}
 if($current_user_role == 'parent'){
       $stinfo = $wpdb->get_row("select * from $student_table where parent_wp_usr_id='".esc_sql($sid)."'");
       $loc_avatar=get_user_meta($sid,'simple_local_avatar',true);
       $img_url= $loc_avatar ? $loc_avatar['full'] : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
}
?>
<div id="message_response"></div>
<?php if($current_user_role == 'student'){?>
    <form name="StudentEditForm" id="StudentEditForm" method="POST" enctype="multipart/form-data">

    <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php esc_html_e( 'Personal Details', 'wptopschool' )?></h3>
            </div>
            <div class="wpts-card-body">
                    <?php wp_nonce_field( 'StudentRegister', 'sregister_nonce', '', true ) ?>
                    <div class="wpts-row">
                        <?php  do_action('wpts_before_personal_detail_editprofile_fields'); ?>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label displaypicture"><?php esc_html_e( 'Profile Image', 'wptopschool' );?></label>
                                <div class="wpts-profileUp">
                                    <?php
                                    $loc_avatar =   get_user_meta($sid,'simple_local_avatar',true);
                                    $img_url    =   $loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
                                    ?>
                                    <img src="<?php echo esc_url($img_url);?>" id="img_preview" onchange="imagePreview(this);" height="150px" width="150px" class="wpts-upAvatar" />
                                    <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                        <input name="displaypicture" class="wpts-file-upload upload" id="displaypicture" type="file" accept="image/jpg, image/jpeg" />
                                    </div>
                                </div>
                                <p class="wpts-form-notes">* <?php esc_html_e( 'Only JPEG and JPG supported, * Max 3 MB Upload', 'wptopschool' );?> </p>
                                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php esc_html_e( 'Please Upload Profile Image', 'wptopschool' );?></label>
                                <p id="test" style="color:red" class="validation-error-displaypicture"></p>
                            </div>
                        </div>
                        <input type="hidden" id="wpts_locationginal" value="<?php echo admin_url();?>" />
                        <input type="hidden" id="studID" name="wp_usr_id" value="<?php echo esc_attr($sid);?>">
                        <input type="hidden" name="parentid" value="<?php echo esc_attr($stinfo->parent_wp_usr_id);?>">
                        <input type="hidden" id="" name="studenteditprofile" value="studenteditprofile">

                        <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-12 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="gender"><?php esc_html_e( 'Gender', 'wptopschool' );?></label>
                                <div class="wpts-radio-inline">
                                    <div class="wpts-radio">
                                        <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='male') echo "checked"?> value="Male" checked="checked">
                                        <label for="Male"><?php esc_html_e( 'Male', 'wptopschool' );?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='female') echo "checked"; ?> value="Female">
                                        <label for="Female"><?php esc_html_e( 'Female', 'wptopschool' );?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='other') echo "checked"; ?> value="other">
                                        <label for="other"><?php esc_html_e( 'Other', 'wptopschool' );?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix wpts-ipad-show"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="firstname"><?php esc_html_e( 'First Name', 'wptopschool' );?> <span class="wpts-required">*</span></label>
                                <input type="text" class="wpts-form-control" id="firstname" value="<?php echo !empty( $stinfo->s_fname ) ? esc_attr($stinfo->s_fname) : esc_attr($stinfo->s_fname); ?>" name="s_fname" placeholder="First Name">
                                <?php wp_nonce_field( 'StudentEdit', 'sedit_nonce', '', true ) ?>
                                    <input type="hidden" id="studID" name="wp_usr_id" value="<?php echo esc_attr($sid);?>">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="middlename"><?php esc_html_e( 'Middle Name', 'wptopschool' );?> </label>
                                <input type="text" class="wpts-form-control" value="<?php echo !empty( $stinfo->s_mname ) ? esc_attr($stinfo->s_mname) : esc_attr($stinfo->s_mname); ?>" id="middlename" name="s_mname" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="lastname"><?php esc_html_e( 'Last Name', 'wptopschool' );?> <span class="wpts-required">*</span></label>
                                <input type="text" class="wpts-form-control" id="lastname" value="<?php echo !empty( $stinfo->s_lname ) ? esc_attr($stinfo->s_lname) : esc_attr($stinfo->s_lname); ?>" name="s_lname" placeholder="Last Name" required="required">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="dateofbirth"><?php esc_html_e( 'Date of Birth', 'wptopschool' );?></label>
                                <input type="text" class="wpts-form-control select_date" value="<?php echo !empty( $stinfo->s_dob ) ? wpts_ViewDate(esc_attr($stinfo->s_dob)) : ''; ?>" id="Dob" name="s_dob" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="bloodgroup"><?php esc_html_e( 'Blood Group', 'wptopschool' );?></label>
                                <select class="wpts-form-control" id="Bloodgroup" name="s_bloodgrp">
                                    <option value=""><?php esc_html_e( 'Select Blood Group', 'wptopschool' );?></option>
                                   <option <?php if ($stinfo->s_bloodgrp == 'O+') echo esc_html("selected","wptopschool"); ?> value="O+"><?php echo esc_html("O +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'O-') echo esc_html("selected","wptopschool"); ?> value="O-"><?php echo esc_html("O -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'A+') echo esc_html("selected","wptopschool"); ?> value="A+"><?php echo esc_html("A +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'A-') echo esc_html("selected","wptopschool"); ?> value="A-"><?php echo esc_html("A -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'B+') echo esc_html("selected","wptopschool"); ?> value="B+"><?php echo esc_html("B +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'B-') echo esc_html("selected","wptopschool"); ?> value="B-"><?php echo esc_html("B -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'AB+') echo esc_html("selected","wptopschool"); ?> value="AB+"><?php echo esc_html("AB +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'AB-') echo esc_html("selected","wptopschool"); ?> value="AB-"><?php echo esc_html("AB -","wptopschool");?></option>
                                </select>
                            </div>
                               </div>
                            <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                                <div class="wpts-form-group">
                                        <label class="wpts-label" for="s_p_phone"><?php esc_html_e( 'Phone Number', 'wptopschool' );?></label>
                                        <input type="text" class="wpts-form-control" id="s_p_phone" name="s_p_phone" value="<?php echo $stinfo->p_phone;?>" placeholder="Phone Number" onkeypress='return event.keyCode == 8 || event.keyCode == 46
                                        || event.keyCode == 37 || event.keyCode == 39 || event.charCode >= 48 && event.charCode <= 57'>
                                        <small><?php esc_html_e( '(Please enter country code with mobile number)', 'wptopschool' );?></small>
                                        <input type="hidden" name="parentid" id="parentid" value="<?php echo esc_attr($stinfo->parent_wp_usr_id);?>"/>
                                    </div>

                        </div>
                        <div class="wpts-col-xs-12">
                            <hr />
                            <h4 class="card-title mt-5"><?php esc_html_e( 'Address', 'wptopschool' );?></h4>
                        </div>
                        <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Address"><?php esc_html_e( 'Current Address', 'wptopschool' );?> <span class="wpts-required">*</span></label>
                                <input type="text" name="s_address" class="wpts-form-control" rows="4" id="current_address" value="<?php echo esc_attr($stinfo->s_address); ?>" placeholder="Street Address" />
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group ">
                                <label class="wpts-label" for="CityName"><?php esc_html_e( 'City Name', 'wptopschool' );?></label>
                                <input type="text" class="wpts-form-control" id="current_city" value="<?php echo esc_attr($stinfo->s_city); ?>" name="s_city" placeholder="City Name">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Country"><?php esc_html_e( 'Country', 'wptopschool' );?></label>
                                <?php $countrylist = wpts_county_list(); ?>
                                    <select class="wpts-form-control" id="current_country" name="s_country">
                                        <option value="">Select Country</option>
                                        <?php foreach ($countrylist as $key => $value) { ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php echo selected($stinfo->s_country, $value); ?>>
                                                <?php echo esc_html($value); ?>
                                            </option>
                                            <?php
                                            }
                                            ?>
                                    </select>
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Pincode"><?php esc_html_e( 'Pin Code', 'wptopschool' );?><span class="wpts-required">*</span></label>
                                <input type="text" class="wpts-form-control" id="current_pincode" value="<?php echo esc_attr($stinfo->s_zipcode); ?>" name="s_zipcode" placeholder="Pin Code">
                            </div>
                        </div>
                        <?php  do_action('wpts_after_personal_detail_editprofile_fields'); ?>
						<div class="wpts-col-xs-12">
                            <button type="submit" class="wpts-btn wpts-btn-success" id="studentform"><?php esc_html_e( 'Update', 'wptopschool' );?></button>

                        </div>
                    </div>
            </div>
        </div>
    </div>

    </form>
<?php } else if($current_user_role == 'teacher'){?>
<form name="TeacherEditForm" id="TeacherEditForm" method="POST" enctype="multipart/form-data">
      <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php esc_html_e( 'Personal Details', 'wptopschool' )?></h3>
                <?php /*<h5 class="wpts-card-subtitle"><?php echo $tinfo->first_name.' '.$tinfo->middle_name.' '.$tinfo->last_name;?></h5> */?>
            </div>
            <div class="wpts-card-body">
                <div class="wpts-row">
                    <?php  do_action('wpts_before_teacher_personal_detail_editprofile_fields'); ?>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label"><?php esc_html_e( 'Profile Image', 'wptopschool' );?></label>
                                <div class="wpts-profileUp">
                                    <?php
                                    $loc_avatar =   get_user_meta($sid,'simple_local_avatar',true);
                                    $img_url    =   $loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
                                    ?>
                                    <img class="wpts-upAvatar" id="img_preview" src="<?php echo esc_url($img_url);?>">
                                    <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                        <input type="file" name="displaypicture" class="wpts-file-upload" id="displaypicture">

                                    </div>
                                </div>
                                <p class="wpts-form-notes">* <?php esc_html_e( 'Only JPEG and JPG supported, * Max 3 MB Upload', 'wptopschool' )?> </p>
                                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php esc_html_e( 'Please Upload Profile Image', 'wptopschool' )?></label>
                                <p id="test" style="color:red"></p>
                            </div>
                        </div>
                        <div class="wpts-col-lg-9 wpts-col-md-8 wpts-col-sm-12 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="gender"><?php esc_html_e( 'Gender', 'wptopschool' )?></label>
                                <div class="wpts-radio-inline">

                                        <div class="wpts-radio">
                                            <input type="radio" name="Gender" <?php if($tinfo->gender=='Male') echo "checked";?> value="Male">
                                            <label for="Male"><?php esc_html_e( 'Male', 'wptopschool' )?></label>
                                        </div>
                                        <div class="wpts-radio">
                                            <input type="radio" name="Gender" <?php if($tinfo->gender=='Female') echo "checked";?> value="Female">
                                            <label for="Female"><?php esc_html_e( 'Female', 'wptopschool' )?></label>
                                        </div>
                                        <div class="wpts-radio">
                                            <input type="radio" name="Gender" <?php if($tinfo->gender=='other') echo "checked";?> value="other">
                                            <label for="other"><?php esc_html_e( 'Others', 'wptopschool' )?></label>
                                        </div>
                                        <?php

                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php wp_nonce_field( 'TeacherRegister', 'tregister_nonce', '', true ) ?>
                        <div class="clearfix wpts-ipad-show"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="firstname"><?php esc_html_e( 'First Name', 'wptopschool' )?> <span class="wpts-required">*</span></label>
                                <input type="text" class="wpts-form-control" value="<?php echo esc_attr($tinfo->first_name);?>" id="firstname" name="firstname" placeholder="First Name">
                                <input type="hidden" id="wpts_locationginal" value="<?php echo admin_url();?>" />
                                <input type="hidden" id="UserID" name="UserID" value="<?php echo esc_attr($tinfo->wp_usr_id); ?>">
                                <input type="hidden" id="UserID" name="teachereditprofile" value="teachereditprofile">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="middlename"><?php esc_html_e( 'Middle Name', 'wptopschool' )?></label>
                                <input type="text" class="wpts-form-control" id="name" name="middlename" value="<?php echo esc_attr($tinfo->middle_name) ;?>" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="lastname"><?php esc_html_e( 'Last Name', 'wptopschool' )?>
                                    <span class="wpts-required">*</span>
                                            </span>
                                </label>
                                <input type="text" class="wpts-form-control" id="name" name="lastname" value="<?php echo esc_attr($tinfo->last_name); ?>" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="dateofbirth"><?php esc_html_e( 'Date of Birth', 'wptopschool' )?></label>

                                     <input type="text" class="wpts-form-control select_date datepicker" value="<?php if($tinfo->dob == "0000-00-00"){ } else { echo wpts_viewDate(esc_attr($tinfo->dob));} ?>" id="Dob" name="Dob" placeholder="Date of Birth">

                            </div>
                        </div>


                        <input type="hidden" class="wpts-form-control" id="Email" name="Email" value="<?php echo esc_attr($tinfo->user_email); ?>" placeholder="Teacher Email">

                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="address"><?php esc_html_e( 'Current Address', 'wptopschool' )?><span class="wpts-required"> *</label>

                                <textarea name="Address" class="wpts-form-control" rows="1"><?php echo esc_textarea($tinfo->address); ?></textarea>

                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="CityName"><?php esc_html_e( 'City Name', 'wptopschool' )?></label>

                                <input type="text" class="wpts-form-control" id="CityName" name="city" placeholder="City Name" value="<?php echo esc_attr($tinfo->city);?>">

                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Country"><?php esc_html_e( 'Country', 'wptopschool' )?></label>
                                <?php $countrylist = wpts_county_list(); ?>
                                <select class="wpts-form-control" id="Country" name="country">
                                    <option value=""><?php esc_html_e( 'Select Country', 'wptopschool' )?></option>
                                    <?php
                                        foreach( $countrylist as $key=>$value ) { ?>
                                    <option value="<?php echo esc_attr($value);?>" <?php echo selected( $tinfo->country, $value ); ?>><?php echo esc_html($value);?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Zip Code"><?php esc_html_e( 'Pin Code', 'wptopschool' )?><span class="wpts-required"> *</label></label>

                                <input type="text" name="zipcode" class="wpts-form-control" value="<?php echo esc_attr($tinfo->zipcode); ?>">


                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Phone"><?php esc_html_e( 'Phone Number', 'wptopschool' )?></label>

                                <input type="text" class="wpts-form-control" id="Phone" name="Phone" value="<?php echo esc_attr($tinfo->phone); ?>" placeholder="(XXX)-(XXX)-(XXXX)">

                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                            <label class="wpts-label" for="Blood"><?php esc_html_e( 'Blood Group', 'wptopschool' )?></label>


                            <select class="wpts-form-control" id="Bloodgroup" name="Bloodgroup">
                                    <option value=""><?php esc_html_e( 'Select Blood Group', 'wptopschool' )?></option>
                                    <option <?php if ($tinfo->bloodgrp == 'O+') echo esc_html("selected","wptopschool"); ?> value="O+"> <?php echo esc_html("O +","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'O-') echo esc_html("selected","wptopschool"); ?> value="O-"><?php echo esc_html("O -","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'A+') echo esc_html("selected","wptopschool"); ?> value="A+"><?php echo esc_html("A +","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'A-') echo esc_html("selected","wptopschool"); ?> value="A-"><?php echo esc_html("A -","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'B+') echo esc_html("selected","wptopschool"); ?> value="B+"><?php echo esc_html("B +","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'B-') echo esc_html("selected","wptopschool"); ?> value="B-"><?php echo esc_html("B -","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'AB+') echo esc_html("selected","wptopschool"); ?> value="AB+"><?php echo esc_html("AB +","wptopschool");?> </option>
                                    <option <?php if ($tinfo->bloodgrp == 'AB-') echo esc_html("selected","wptopschool"); ?> value="AB-"><?php echo esc_html("AB -","wptopschool");?> </option>
                                </select>

                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Qualification"><?php esc_html_e( 'Qualification', 'wptopschool' )?></label>

                                <input type="text" class="wpts-form-control" id="Qual" name="Qual" value="<?php echo esc_attr($tinfo->qualification); ?>" placeholder="Qualification">

                            </div>
                        </div>
                        <?php  do_action('wpts_after_teacher_personal_detail_editprofile_fields'); ?>
                        <div class="wpts-col-xs-12">
                            <button type="submit" id="u_teacher" class="wpts-btn wpts-btn-success"><?php esc_html_e( 'Update', 'wptopschool' )?></button>
                            <a href='<?php echo esc_url(wpts_admin_url()."sch-teacher");?>' class="wpts-btn wpts-dark-btn"><?php esc_html_e( 'Back', 'wptopschool' )?></a>
                            <!--  <a href='<?php echo esc_url(wpts_admin_url());?>sch-teacher' class="wpts-btn wpts-dark-btn">Back</a> -->


                        </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } else if($current_user_role == 'parent'){?>
        <form name="ParentEditForm" id="ParentEditForm" method="POST" enctype="multipart/form-data">

    <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php esc_html_e( 'Personal Details', 'wptopschool' )?></h3>
            </div>
            <div class="wpts-card-body">
                    <?php wp_nonce_field( 'StudentRegister', 'sregister_nonce', '', true ) ?>
                    <div class="wpts-row">
                        <?php  do_action('wpts_before_parent_personal_detail_editprofile_fields'); ?>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label displaypicture"><?php esc_html_e( 'Profile Image', 'wptopschool' )?></label>
                                <div class="wpts-profileUp">
                                    <?php
                                    $loc_avatar =   get_user_meta($sid,'simple_local_avatar',true);
                                    $img_url    =   $loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
                                    ?>
                                    <img src="<?php echo esc_url($img_url);?>" id="img_preview" onchange="imagePreview(this);" height="150px" width="150px" class="wpts-upAvatar" />
                                    <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                        <input name="displaypicture" class="wpts-file-upload upload" id="displaypicture" type="file" accept="image/jpg, image/jpeg" />
                                    </div>
                                </div>
                                <p class="wpts-form-notes">* <?php esc_html_e( 'Only JPEG and JPG supported, * Max 3 MB Upload', 'wptopschool' )?> </p>
                                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php esc_html_e( 'Please Upload Profile Image', 'wptopschool' )?></label>
                                <p id="test" style="color:red" class="validation-error-displaypicture"></p>
                            </div>
                        </div>
                        <input type="hidden" id="wpts_locationginal" value="<?php echo admin_url();?>" />
                        <input type="hidden" id="studID" name="wp_usr_id" value="<?php echo esc_attr($sid);?>">
                        <input type="hidden" name="parentid" value="<?php echo esc_attr($stinfo->parent_wp_usr_id);?>">
                         <input type="hidden" id="" name="s_fname" value="<?php echo esc_attr($stinfo->s_fname);?>">
                          <input type="hidden" id="" name="s_lname" value="<?php echo esc_attr($stinfo->s_lname);?>">
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_gender"><?php esc_html_e( 'Gender', 'wptopschool' );?></label>
                            <div class="wpts-radio-inline">
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'male') echo "checked" ?> value="Male" checked="checked">
                                    <label for="Male"><?php esc_html_e( 'Male', 'wptopschool' )?></label>
                                </div>
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'female') echo "checked"; ?> value="Female">
                                    <label for="Female"><?php esc_html_e( 'Female', 'wptopschool' )?></label>
                                </div>
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'other') echo "checked"; ?> value="other">
                                    <label for="other"><?php esc_html_e( 'Others', 'wptopschool' )?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="firstname"><?php esc_html_e( 'First Name', 'wptopschool' )?></label>
                            <input type="text" class="wpts-form-control" id="firstname" value="<?php echo !empty($stinfo->p_fname) ? esc_attr($stinfo->p_fname) : ''; ?>" name="p_fname" placeholder="Parent First Name">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="middlename"><?php esc_html_e( 'Middle Name', 'wptopschool' )?></label>
                            <input type="text" class="wpts-form-control" id="middlename" value="<?php echo !empty($stinfo->p_mname) ? esc_attr($stinfo->p_mname) : ''; ?>" name="p_mname" placeholder="Parent Middle Name" >
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="lastname"><?php esc_html_e( 'Last Name', 'wptopschool' )?> </label>
                            <input type="text" class="wpts-form-control" id="lastname" value="<?php echo !empty($stinfo->p_lname) ? esc_attr($stinfo->p_lname) : ''; ?>" name="p_lname" placeholder="Parent Last Name">
                        </div>
                    </div>

                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_edu"><?php esc_html_e( 'Education', 'wptopschool' )?></label>
                            <input type="text" class="wpts-form-control" value="<?php echo esc_attr($stinfo->p_edu); ?>" name="p_edu" placeholder="Parent Education">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_profession"><?php esc_html_e( 'Profession', 'wptopschool' )?></label>
                            <input type="text" class="wpts-form-control" name="p_profession" value="<?php echo esc_attr($stinfo->p_profession); ?>" placeholder="Parent Profession">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label for="phone"><?php esc_html_e( 'Phone', 'wptopschool' )?></label>
                            <input type="text" class="wpts-form-control" id="phone" value="<?php echo esc_attr($stinfo->s_phone); ?>" name="s_phone" placeholder="Phone Number" >
                        </div>
                    </div>

                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label for="bloodgroup"><?php esc_html_e( 'Blood Group (Optional)', 'wptopschool' )?></label>
                            <select class="wpts-form-control" id="Bloodgroup" name="p_bloodgrp">
                                <option value=""><?php esc_html_e( 'Select Blood Group', 'wptopschool' )?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'O+') echo esc_html("selected","wptopschool"); ?> value="O+"><?php echo __("O +","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'O-') echo esc_html("selected","wptopschool"); ?> value="O-"><?php echo __("O -","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'A+') echo esc_html("selected","wptopschool"); ?> value="A+"><?php echo __("A +","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'A-') echo esc_html("selected","wptopschool"); ?> value="A-"><?php echo __("A -","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'B+') echo esc_html("selected","wptopschool"); ?> value="B+"><?php echo __("B +","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'B-') echo esc_html("selected","wptopschool"); ?> value="B-"><?php echo __("B -","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'AB+') echo esc_html("selected","wptopschool"); ?> value="AB+"><?php echo __("AB +","wptopschool");?></option>
                                <option <?php if ($stinfo->p_bloodgrp == 'AB-') echo esc_html("selected","wptopschool"); ?> value="AB-"><?php echo __("AB -","wptopschool");?></option>
                            </select>
                        </div>
                    </div>
                    <?php  do_action('wpts_after_parent_personal_detail_editprofile_fields'); ?>
                    <div class="wpts-col-xs-12">
                        <button type="submit" class="wpts-btn wpts-btn-success" id="parentform"><?php echo esc_html("Update","wptopschool"); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>
<?php }?>
<div class="wpts-popupMain wpts-popVisible" id="SuccessModal" data-pop="SuccessModal" style="display:none;">
          <div class="wpts-overlayer"></div>
          <div class="wpts-popBody wpts-alert-body">
            <div class="wpts-popInner">
                <a href="javascript:;" class="wpts-closePopup"></a>
                <div class="wpts-popup-cont wpts-alertbox wpts-alert-success">
                    <div class="wpts-alert-icon-box">
                        <i class="icon wpts-icon-tick-mark"></i>
                    </div>
                    <div class="wpts-alert-data">
                        <input type="hidden" name="teacherid" id="teacherid">
                        <h4><?php esc_html_e( 'Success', 'wptopschool' )?></h4>
                        <p><?php esc_html_e( 'Data Saved Successfully.', 'wptopschool' )?></p>
                    </div>

                </div>
            </div>
          </div>
</div>
		<?php
			wpts_body_end();
			wpts_footer();
 } else {
		//Include Login Section
	include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
}
?>