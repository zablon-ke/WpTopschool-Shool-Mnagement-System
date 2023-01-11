<?php if (!defined('ABSPATH')) exit('No Such File');
$teacher_table = $wpdb->prefix . "wpts_teacher";
$class_table = $wpdb->prefix . "wpts_class";
$users_table = $wpdb->prefix . "users";
$tid = intval($_GET['id']);
$msg = '';
if (isset($_GET['edit']) && sanitize_text_field($_GET['edit']) == 'true')
{
    if ($current_user_role == 'administrator' || ($current_user_role == 'teacher' && sanitize_text_field($current_user->ID) == $tid))
    {
        $edit = true;
    }
    else
    {
        $edit = false;
    }
    if (isset($_POST['tedit_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['tedit_nonce']) , 'TeacherEdit'))
    {
        ob_start();
        wpts_UpdateTeacher();
        $msg = ob_get_clean();
    }
}
else
{
    $edit = false;
}
$tinfo = $wpdb->get_row("select teacher.*,user.user_email from $teacher_table teacher LEFT JOIN $users_table user ON user.ID=teacher.wp_usr_id where teacher.wp_usr_id='".esc_sql($tid)."'");
if (!empty($tinfo))
{ ?> <div id="formresponse"> <?php echo esc_html($msg); ?> </div>
<div class="wpts-row"> <?php if ($edit)
    { ?> <form name="TeacherEditForm" id="TeacherEditForm" method="POST" enctype="multipart/form-data"> <?php
    } ?> <div class="wpts-col-xs-12">
      <div class="wpts-card">
        <div class="wpts-card-head">
          <h3 class="wpts-card-title"><?php esc_html_e( 'Personal Details', 'wptopschool' ); ?></h3>
            <?php /*
			<h5 class="wpts-card-subtitle"><?php echo esc_html($tinfo->first_name.' '.$tinfo->middle_name.' '.$tinfo->last_name);?> </h5> */ ?>
        </div>
        <div class="wpts-card-body">
          <div class="wpts-row">
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label"><?php esc_html_e( 'Profile Image', 'wptopschool' ); ?></label>
                <div class="wpts-profileUp">
                <?php $loc_avatar = get_user_meta($tid, 'simple_local_avatar', true);
                $img_url = $loc_avatar ? $loc_avatar['full'] : wpts_PLUGIN_URL . 'img/default_avtar.jpg'; ?>
                <img class="wpts-upAvatar" id="img_preview_teacher" src="<?php echo esc_url($img_url); ?>">
                <div class="wpts-upload-button"> <?php if ($edit){ ?> <i class="fa fa-camera"></i>
                <input type="file" name="displaypicture" class="wpts-file-upload" id="displaypicture"> <?php } ?>
                  </div>
                </div>
                <p class="wpts-form-notes">*<?php esc_html_e( 'Only JPEG and JPG supported, * Max 3 MB Upload', 'wptopschool' ); ?></p>
                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php esc_html_e( 'Please Upload Profile Image', 'wptopschool' ); ?></label>
                <p id="test" style="color:red"></p>
              </div>
            </div>
            <div class="wpts-col-lg-9 wpts-col-md-8 wpts-col-sm-12 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="gender"><?php esc_html_e( 'Gender', 'wptopschool' ); ?></label>
                <div class="wpts-radio-inline"> <?php if ($edit){ ?> <div class="wpts-radio">
                    <input type="radio" name="Gender" <?php if ($tinfo->gender == 'Male') echo "checked"; ?> value="Male">
                    <label for="Male"><?php esc_html_e( 'Male', 'wptopschool' ); ?></label>
                  </div>
                  <div class="wpts-radio">
                    <input type="radio" name="Gender" <?php if ($tinfo->gender == 'Female') echo "checked"; ?> value="Female">
                    <label for="Female"><?php esc_html_e( 'Female', 'wptopschool' ); ?></label>
                  </div>
                  <div class="wpts-radio">
                    <input type="radio" name="Gender" <?php if ($tinfo->gender == 'other') echo "checked"; ?> value="other">
                    <label for="other"><?php esc_html_e( 'Other', 'wptopschool' ); ?></label>
                  </div> <?php } else { echo esc_html($tinfo->gender); } ?>
                </div>
              </div>
            </div> <?php wp_nonce_field('TeacherRegister', 'tregister_nonce', '', true) ?> <div class="clearfix wpts-ipad-show"></div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="firstname"><?php esc_html_e( 'First Name', 'wptopschool' ); ?>
                <span class="wpts-required">*</span>
                </label>
                <input type="text" class="wpts-form-control" value="<?php echo esc_attr($tinfo->first_name); ?>" id="firstname" name="firstname" placeholder="First Name">
                <input type="hidden" id="wpts_locationginal" value="<?php echo esc_url(admin_url()); ?>" />
                <input type="hidden" id="UserID" name="UserID" value="<?php echo esc_attr($tinfo->wp_usr_id); ?>">
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="middlename"><?php esc_html_e( 'Middle Name', 'wptopschool' ); ?></label>
                <input type="text" class="wpts-form-control" id="name" name="middlename" value="<?php echo esc_attr($tinfo->middle_name); ?>" placeholder="Middle Name">
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="lastname"><?php esc_html_e( 'Last Name', 'wptopschool' ); ?>
                <?php if ($edit){ ?> <span class="wpts-required">*</span> <?php } ?> </span>
                </label>
                <input type="text" class="wpts-form-control" id="name" name="lastname" value="<?php echo esc_attr($tinfo->last_name); ?>" placeholder="Last Name">
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="dateofbirth"><?php esc_html_e( 'Date of Birth', 'wptopschool' ); ?></label> <?php if ($edit) { ?>
                <input type="text" class="wpts-form-control select_date datepicker" value="<?php if ($tinfo->dob == "0000-00-00"){ }else{ echo wpts_viewDate(esc_attr($tinfo->dob));} ?>" id="Dob" name="Dob" placeholder="Date of Birth"> <?php } else { echo wpts_viewDate(esc_html($tinfo->dob)); } ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Email"><?php esc_html_e( 'Email Address', 'wptopschool' ); ?>
                <span class="wpts-required"> *</span>
                </label> <?php if ($edit) { ?>
                <input type="email" class="wpts-form-control" id="Email" name="Email" value="<?php echo esc_attr($tinfo->user_email); ?>" placeholder="Teacher Email">
                <?php } else echo esc_html($tinfo->user_email); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="address"><?php esc_html_e( 'Current Address', 'wptopschool' ); ?>
                <span class="wpts-required"> * </label>
                <?php if ($edit){ ?> <textarea name="Address" class="wpts-form-control" rows="1"><?php echo esc_textarea($tinfo->address); ?></textarea>
                <?php } else echo esc_html($tinfo->address); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
            <div class="wpts-form-group">
            <label class="wpts-label" for="CityName"><?php esc_html_e( 'City Name', 'wptopschool' ); ?></label>
            <?php if ($edit) { ?>
            <input type="text" class="wpts-form-control" id="CityName" name="city" placeholder="City Name" value="<?php echo esc_attr($tinfo->city); ?>"> <?php } else echo esc_html($tinfo->city); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Country"><?php esc_html_e( 'Country', 'wptopschool' ); ?></label>
                <?php if ($edit){ $countrylist = wpts_county_list(); ?>
                  <select class="wpts-form-control" id="Country" name="country">
                  <option value=""><?php esc_html_e( 'Select Country', 'wptopschool' ); ?></option>
                  <?php foreach ($countrylist as $key => $value) { ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php echo selected($tinfo->country, $value); ?>>
                    <?php echo esc_html($value); ?> </option> <?php } ?>
                </select> <?php } else echo esc_html($stinfo->country); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Zip Code"><?php esc_html_e( 'Pin Code', 'wptopschool' ); ?>
                <span class="wpts-required"> * </label>
                </label> <?php if ($edit){ ?>
                <input type="text" name="zipcode" class="wpts-form-control" value="<?php echo esc_attr($tinfo->zipcode); ?>"> <?php } else echo esc_html($stinfo->zipcode); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Phone"><?php esc_html_e( 'Phone Number', 'wptopschool' ); ?></label>
                <?php if ($edit) { ?>
                 <input type="text" class="wpts-form-control" id="Phone" name="Phone" value="<?php echo esc_attr($tinfo->phone); ?>" placeholder="(XXX)-(XXX)-(XXXX)"> <?php } else echo esc_html($tinfo->phone); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Blood"><?php esc_html_e( 'Blood Group', 'wptopschool' ); ?></label>
                <?php if ($edit) { ?>
                <select class="wpts-form-control" id="Bloodgroup" name="Bloodgroup">
                  <option value=""><?php esc_html_e( 'Select Blood Group', 'wptopschool' ); ?></option>
                  <option <?php if ($tinfo->bloodgrp == 'O+') echo esc_html("selected","wptopschool"); ?> value="O+"> <?php echo __("O +","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'O-') echo esc_html("selected","wptopschool"); ?> value="O-"><?php echo __("O -","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'A+') echo esc_html("selected","wptopschool"); ?> value="A+"><?php echo __("A +","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'A-') echo esc_html("selected","wptopschool"); ?> value="A-"><?php echo __("A -","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'B+') echo esc_html("selected","wptopschool"); ?> value="B+"><?php echo __("B +","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'B-') echo esc_html("selected","wptopschool"); ?> value="B-"><?php echo __("B -","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'AB+') echo esc_html("selected","wptopschool"); ?> value="AB+"><?php echo __("AB +","wptopschool");?> </option>
                  <option <?php if ($tinfo->bloodgrp == 'AB-') echo esc_html("selected","wptopschool"); ?> value="AB-"><?php echo __("AB -","wptopschool");?> </option>
                </select> <?php } else echo esc_html($tinfo->bloodgrp); ?>
              </div>
            </div>
            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
            <label class="wpts-label" for="Qualification"><?php esc_html_e( 'Qualification', 'wptopschool' ); ?></label>
            <?php if ($edit){ ?>
            <input type="text" class="wpts-form-control" id="Qual" name="Qual" value="<?php echo esc_attr($tinfo->qualification); ?>" placeholder="Qualification"> <?php } else echo esc_html($tinfo->qualification); ?>
              </div>
            </div>
            <div class="wpts-col-xs-12"> <?php if ($edit) { ?> <button type="submit" id="u_teacher" class="wpts-btn wpts-btn-success"><?php esc_html_e( 'Next', 'wptopschool' ); ?></button>
              <!--  <a href='
            <?php echo wpts_admin_url(); ?>sch-teacher' class="wpts-btn wpts-dark-btn">Back</a> --> <?php } else{ ?>
            <a href="?id=<?php echo esc_attr($tinfo->wp_usr_id); ?>&edit=true" type="button" class="wpts-btn wpts-btn-sm wpts-btn-warning"><i class="icon dashicons dashicons-edit wpts-edit-icon"></i></a>
            <a data-original-title="Remove this user" type="button" class="wpts-btn wpts-btn-sm wpts-btn-danger"> <i class="icon dashicons dashicons-trash wpts-delete-icon"></i></a> <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="wpts-col-xs-12">
      <div class="wpts-card">
        <div class="wpts-card-head">
          <h3 class="wpts-card-title"><?php esc_html_e( 'School Details', 'wptopschool' ); ?></h3>
        </div>
        <div class="wpts-card-body">
          <div class="wpts-row">
            <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Join"><?php esc_html_e( 'Joining Date (mm/dd/yyyy)', 'wptopschool' ); ?></label>
                <?php if ($edit) { ?>
                    <input type="text" class="wpts-form-control select_date" value="<?php if (wpts_viewDate($tinfo->doj) == "0000-00-00"){}else{ echo wpts_viewDate(esc_attr($tinfo->doj));} ?>" id="Doj" name="Doj" placeholder="Date of Join"> <?php } else echo wpts_viewDate(esc_html($tinfo->doj)); ?>
              </div>
            </div>
            <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Releaving"><?php esc_html_e( 'Leaving Date (mm/dd/yyyy)', 'wptopschool' ); ?></label>
                <?php if ($edit){ ?>
                <input type="text" class="wpts-form-control select_date" value="<?php if (wpts_viewDate($tinfo->dol) == "0000-00-00"){ }else{ echo wpts_viewDate(esc_attr($tinfo->dol)); } ?>" id="Dol" name="dol" placeholder="Date of Leave"> <?php } else echo wpts_viewDate(esc_html($tinfo->dol)); ?>
              </div>
            </div>
            <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Working"><?php esc_html_e( 'Working Hours', 'wptopschool' ); ?></label>
                <?php if ($edit){ ?>
                <input type="text" name="whours" class="wpts-form-control" value="<?php echo esc_attr($tinfo->whours); ?>"> <?php } else echo esc_html($tinfo->whours); ?>
              </div>
            </div>
            <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
                <label class="wpts-label" for="Position"><?php esc_html_e( 'Current Position', 'wptopschool' ); ?></label> <?php if ($edit) { ?>
                <input type="text" class="wpts-form-control" id="Position" name="Position" value="<?php echo esc_attr($tinfo->position); ?>" placeholder="Position"><?php } else echo esc_html($tinfo->position); ?>
              </div>
            </div>
            <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
              <div class="wpts-form-group">
            <label class="wpts-label" for="Employee"><?php esc_html_e( 'Employee Code', 'wptopschool' ); ?></label>
            <?php if ($edit){ if ($current_user_role == 'administrator'){ ?>
            <input type="text" class="wpts-form-control" id="Empcode" name="Empcode" value="<?php echo esc_attr($tinfo->empcode); ?>" placeholder="Empcode"> <?php } } else { echo esc_html($tinfo->empcode); } ?>
              </div>
            </div>
            <div class="wpts-col-xs-12">
            <?php if ($edit) { ?>
            <button type="submit" id="u_teacher" class="wpts-btn wpts-btn-success"><?php esc_html_e( 'Update', 'wptopschool' ); ?></button>
            <a href='<?php echo esc_url(wpts_admin_url()."sch-teacher")?>' class="wpts-btn wpts-dark-btn"><?php esc_html_e( 'Back', 'wptopschool' ); ?></a> <?php
            }
            else
            { ?>
            <a href="?id=<?php echo esc_attr($tinfo->wp_usr_id); ?>&edit=true" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
            <a data-original-title="Remove this user" type="button" class="btn btn-sm btn-danger">
            <i class="glyphicon glyphicon-remove"></i></a> <?php
            } ?>
  </form>
</div>
</div>
</div>
</div>
</div>
</div> <?php
}
else
{
    echo esc_html("Sorry! No Data Retriverd","wptopschool");
} ?>
<!--<a href="javascript:;" id="sucess_teacher" class="wpts-popclick" data-pop="SuccessModal" title="Delete" style="display:none;">a</a> -->
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
          <h4><?php echo esc_html("Success","wptopschool");?></h4>
          <p><?php echo esc_html("Data Saved Successfully.","wptopschool");?></p>
        </div>
      </div>
    </div>
  </div>
</div>