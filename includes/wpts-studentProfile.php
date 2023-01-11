<?php if (!defined( 'ABSPATH' ) ) exit('No Such File');

$student_table = $wpdb->prefix."wpts_student";
$class_table = $wpdb->prefix."wpts_class";
$users_table = $wpdb->prefix."users";
$sid = intval($_GET['id']);
$edit = true;
$msg = '';
$student_index = '';
if( isset($_GET['edit']) && sanitize_text_field($_GET['edit'])=='true' && ($current_user_role=='administrator' || $current_user_role=='teacher' ) && (isset( $_POST['sedit_nonce'] ) && wp_verify_nonce( sanitize_text_field($_POST['sedit_nonce']), 'StudentEdit' ) ) )  {
    ob_start();
    wpts_UpdateStudent();
}
$stinfo  =  $wpdb->get_row("select * from $student_table where wp_usr_id='".esc_sql($sid)."'");
// print_r($stinfo);
if( !empty( $stinfo ) ) {
    $student_index = $stinfo->sid;
    $loc_avatar=get_user_meta($sid,'simple_local_avatar',true);
    $img_url= $loc_avatar ? $loc_avatar['full'] : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
     $parentid   =   intval($stinfo->parent_wp_usr_id);
    if( !empty( $parentid ) ) {
        $parentInfo =   get_user_by( 'id', $parentid );
        $parentEmail =  isset( $parentInfo->data->user_email ) ? sanitize_text_field($parentInfo->data->user_email) : '';
        //Update Parent Profile Picture
        $parent_loc_avatar=get_user_meta($parentid,'simple_local_avatar',true);
        $parent_img_url= $parent_loc_avatar ? sanitize_text_field($parent_loc_avatar['full']) : WPTS_PLUGIN_URL.'img/default_avtar.jpg';
    }
?>

<div id="formresponse"></div>

<form name="StudentEditForm" id="StudentEditForm" method="post" enctype="multipart/form-data" novalidate="novalidate">
    <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_personal_detail', esc_html__( 'Personal Details', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
                    <?php wp_nonce_field( 'StudentRegister', 'sregister_nonce', '', true ) ?>

                    <div class="wpts-row">
                        <?php
                          do_action('wpts_before_student_personal_detail_fields');
                          /*Required field Hook*/
                          $is_required_item = apply_filters('wpts_student_personal_is_required',array());
                        ?>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label displaypicture">
                                <?php esc_html_e("Profile Image","wptopschool");?></label>
                                <div class="wpts-profileUp">
                                    <img src="<?php echo esc_url($img_url);?>" id="img_preview" onchange="imagePreview(this);" height="150px" width="150px" class="wpts-upAvatar" />
                                    <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                        <input name="displaypicture" class="wpts-file-upload upload" id="displaypicture" type="file" accept="image/jpg, image/jpeg" />
                                    </div>
                                </div>
                                <p class="wpts-form-notes">* <?php echo esc_html("Only JPEG and JPG supported, * Max 3 MB Upload","wptopschool");?> </p>
                                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php echo esc_html("Please Upload Profile Image","wptopschool");?></label>
                                <p id="test" style="color:red" class="validation-error-displaypicture"></p>
                            </div>
                        </div>
                        <input type="hidden" id="studID" name="wp_usr_id" value="<?php echo esc_attr($sid);?>">
                        <input type="hidden" name="parentid" value="<?php echo esc_attr($stinfo->parent_wp_usr_id);?>">
                        <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-12 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="gender">
                                <?php esc_html_e("Gender","wptopschool");?></label>
                                <div class="wpts-radio-inline">
                                    <div class="wpts-radio">
                                        <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='male') echo "checked"?> value="Male" checked="checked">
                                        <label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='female') echo "checked"; ?> value="Female">
                                        <label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                       <input type="radio" name="s_gender" <?php if(strtolower($stinfo->s_gender)=='other') echo "checked"; ?> value="other">
                                       <label for="other"><?php echo esc_html_e("Other","wptopschool");?></label>
                                    </div>
                                </div>
                           </div>
                        </div>
                        <div class="clearfix wpts-ipad-show"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                          <div class="wpts-form-group">
                            <?php
                            /*Check Required Field*/
                            if(isset($is_required_item['s_fname'])){
                                $is_required =  esc_html($is_required_item['s_fname']);
                            }else{
                                $is_required = true;
                            }
                            ?>
                            <label class="wpts-label" for="firstname">
                            <?php esc_html_e("First Name","wptopschool"); ?>
                            <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="firstname" value="<?php echo !empty( $stinfo->s_fname ) ? esc_attr($stinfo->s_fname) : esc_attr($stinfo->s_fname); ?>" name="s_fname">
                            <?php wp_nonce_field( 'StudentEdit', 'sedit_nonce', '', true ) ?>
                            <input type="hidden" id="studID" name="wp_usr_id" value="<?php echo esc_attr($sid);?>">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                              <?php
                              /*Check Required Field*/
                              if(isset($is_required_item['middlename'])){
                                  $is_required =  esc_html($is_required_item['middlename'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <label class="wpts-label" for="middlename"><?php esc_html_e("Middle Name","wptopschool");?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" value="<?php echo !empty( $stinfo->s_mname ) ? esc_attr($stinfo->s_mname) : esc_attr($stinfo->s_mname); ?>" id="middlename" name="s_mname" >
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                              <?php
                              /*Check Required Field*/
                              if(isset($is_required_item['s_lname'])){
                                  $is_required =  esc_html($is_required_item['s_lname'],"wptopschool");
                              }else{
                                  $is_required = true;
                              }

                              ?>
                                <label class="wpts-label" for="lastname"><?php esc_html_e("Last Name","wptopschool");?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="lastname" value="<?php echo !empty( $stinfo->s_lname ) ? esc_attr($stinfo->s_lname) : esc_attr($stinfo->s_lname); ?>" name="s_lname"  required="required">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                              <?php
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_dob'])){
                                      $is_required =  esc_html($is_required_item['s_dob'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                <label class="wpts-label" for="dateofbirth"><?php esc_html_e("Date of Birth","wptopschool"); ?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control select_date" value="<?php echo !empty( $stinfo->s_dob ) ? wpts_ViewDate(esc_attr($stinfo->s_dob)) : ''; ?>" id="Dob" name="s_dob" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="bloodgroup">
                                  <?php esc_html_e("Blood Group","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_bloodgrp'])){
                                      $is_required =  esc_html($is_required_item['s_bloodgrp'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <select class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="Bloodgroup" name="s_bloodgrp">
                                    <option value=""><?php echo __("Select Blood Group","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'O+') echo esc_html("selected","wptopschool"); ?> value="O+"><?php echo __("O +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'O-') echo esc_html("selected","wptopschool"); ?> value="O-"><?php echo __("O -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'A+') echo esc_html("selected","wptopschool"); ?> value="A+"><?php echo __("A +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'A-') echo esc_html("selected","wptopschool"); ?> value="A-"><?php echo __("A -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'B+') echo esc_html("selected","wptopschool"); ?> value="B+"><?php echo __("B +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'B-') echo esc_html("selected","wptopschool"); ?> value="B-"><?php echo __("B -","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'AB+') echo esc_html("selected","wptopschool"); ?> value="AB+"><?php echo __("AB +","wptopschool");?></option>
                                    <option <?php if ($stinfo->s_bloodgrp == 'AB-') echo esc_html("selected","wptopschool"); ?> value="AB-"><?php echo __("AB -","wptopschool");?></option>
                                </select>
                            </div>
                               </div>
                            <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                                <div class="wpts-form-group">
                                        <label class="wpts-label" for="s_p_phone">
                                        <?php esc_html_e("Phone Number","wptopschool");
                                        /*Check Required Field*/
                                        if(isset($is_required_item['s_p_phone'])){
                                            $is_required =  esc_html($is_required_item['s_p_phone'],"wptopschool");
                                        }else{
                                            $is_required = false;
                                        }
                                        ?>
                                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                        <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="s_p_phone" name="s_p_phone" value="<?php echo esc_attr($stinfo->p_phone);?>"  onkeypress='return event.keyCode == 8 || event.keyCode == 46
                                        || event.keyCode == 37 || event.keyCode == 39 || event.charCode >= 48 && event.charCode <= 57'>
                                        <small><?php esc_html("(Please enter country code with mobile number)","wptopschool");?></small>
                                        <input type="hidden" name="parentid" id="parentid" value="<?php echo esc_attr($stinfo->parent_wp_usr_id);?>"/>
                                    </div>
                        </div>
                        <div class="wpts-col-xs-12">
                            <hr />
                            <h4 class="card-title mt-5"><?php echo esc_html("Address","wptopschool");?></h4>
                        </div>
                      <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                              <?php
                              /*Check Required Field*/
                              if(isset($is_required_item['s_address'])){
                                  $is_required =  esc_html($is_required_item['s_address'],"wptopschool");
                              }else{
                                  $is_required = true;
                              }
                              ?>
                                <label class="wpts-label" for="Address"><?php esc_html_e("Current Address","wptopschool"); ?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" name="s_address" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" rows="4" id="current_address" value="<?php echo esc_attr($stinfo->s_address); ?>"  />
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group ">
                                <?php
                                    /*Check Required Field*/
                                    if(isset($is_required_item['s_city'])){
                                        $is_required =  esc_html($is_required_item['s_city'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                <label class="wpts-label" for="CityName"><?php esc_html_e("City Name","wptopschool");?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="current_city" value="<?php echo esc_attr($stinfo->s_city); ?>" name="s_city">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Country"> <?php echo esc_html_e("Country","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_country'])){
                                      $is_required =  esc_html($is_required_item['s_country'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <?php $countrylist = wpts_county_list(); ?>
                                    <select class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="current_country" name="s_country">
                                        <option value=""><?php echo esc_html("Select Country","wptopschool");?></option>
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
                             <?php
                             /*Check Required Field*/
                             if(isset($is_required_item['s_zipcode'])){
                                 $is_required =  esc_html($is_required_item['s_zipcode'],"wptopschool");
                             }else{
                                 $is_required = false;
                             }
                             ?>
                                <label class="wpts-label" for="Zipcode"><?php esc_html_e("Pin Code","wptopschool"); ?><span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="current_pincode" name="s_zipcode"  value="<?php echo esc_attr($stinfo->s_zipcode); ?>" data-isrequired="<?php echo $data = apply_filters( 'wpts_student_isrequired_pincode',true); ?>">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                           <div class="wpts-form-group">
                                <input type="checkbox" id="sameas" value="1" onclick="sameAsAbove()">
                                <label for="sameas"> <?php echo esc_html("Same as Above","wptopschool");?> </label>
                            </div>
                        </div>
                        <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <div class="wpts-form-group">
                                    <label for="PermanentAddress"><?php esc_html_e("Permanent Address","wptopschool");
                                      /*Check Required Field*/
                                      if(isset($is_required_item['s_paddress'])){
                                          $is_required =  esc_html($is_required_item['s_paddress'],"wptopschool");
                                      }else{
                                          $is_required = false;
                                      }
                                      ?>
                                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                    </label>
                                    <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" rows="5"  id="permanent_address" value="<?php echo esc_attr($stinfo->s_paddress);?>" name="s_paddress">
                                </div>
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label for="City"><?php esc_html_e("City Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_pcity'])){
                                      $is_required =  esc_html($is_required_item['s_pcity'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="permanent_city" value="<?php echo esc_attr($stinfo ->s_pcity); ?>" name="s_pcity"
                                >
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label for="Country"><?php esc_html_e("Country","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_item['s_pcountry'])){
                                        $is_required =  esc_html($is_required_item['s_pcountry'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <?php $countrylist = wpts_county_list(); ?>
                                    <select data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="permanent_country" name="s_pcountry">
                                        <option value=""><?php echo esc_html("Select Country","wptopschool");?></option>
                                        <?php foreach ($countrylist as $key => $value) { ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php echo selected($stinfo->s_pcountry, $value); ?>>
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
                                   <label class="wpts-label" for="Zipcode"><?php esc_html_e("Pin Code","wptopschool");
                                   /*Check Required Field*/
                                   if(isset($is_required_item['s_pzipcode'])){
                                       $is_required =  esc_html($is_required_item['s_pzipcode'],"wptopschool");
                                   }else{
                                       $is_required = false;
                                   }
                                   ?>
                                   <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                 </label>
                                   <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="permanent_pincode" name="s_pzipcode" value="<?php echo esc_attr($stinfo->s_pzipcode);?>"   data-isrequired="<?php echo $data = apply_filters( 'wpts_student_isrequired_pincode',true); ?>">

                            </div>
                        </div>
                          <?php
                              do_action( 'wpts_after_student_personal_detail_fields' );
                          ?>
                        <div class="wpts-col-xs-12">
                            <button type="submit" class="wpts-btn wpts-btn-success" id="studentform"><?php echo esc_html("Next","wptopschool");?></button>&nbsp;&nbsp;
                        </div>
                    </div>
            </div>
        </div>
    </div>


    <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_parent_detail', esc_html__( 'Parent Detail', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
                <div class="wpts-row">
                      <?php
                            do_action('wpts_before_student_parent_detail_fields');
                            /*Required field Hook*/
                            $is_required_parent = apply_filters('wpts_student_parent_is_required',array());
                        ?>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <input type="hidden" id="wpts_locationginal" value="<?php echo esc_url(admin_url());?>" />

                            <div class="wpts-profileUp">
                                <?php if($parent_img_url == ""){?>
                                     <img class="wpts-upAvatar" id="img_preview1"  onchange="imagePreview(this);" src="<?php echo esc_url(plugins_url( 'img/default_avtar.jpg', dirname(__FILE__) ));?>">
                               <!--  <img class="wpts-upAvatar" id="img_preview1" onchange="imagePreview(this);" src="<?php echo esc_url($parent_img_url);?>"> -->
                            <?php } else {?>
                                <img class="wpts-upAvatar" id="img_preview1" onchange="imagePreview(this);" src="<?php echo esc_url($parent_img_url);?>">
                            <?php }?>
                                <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                    <input name="p_displaypicture" class="wpts-file-upload" id="p_displaypicture" type="file" accept="image/jpg, image/jpeg" />
                                </div>
                            </div>
                            <p class="wpts-form-notes">* <?php echo esc_html("Only JPEG and JPG supported, * Max 3 MB Upload","wptopschool");?> </p>
                            <label id="pdisplaypicture-error" class="error" for="pdisplaypicture" style="display: none;">
                                <?php echo esc_html("Please Upload Profile Image","wptopschool");?></label>
                            <p id="test" style="color:red"></p>
                        </div>
                    </div>
                    <div class="wpts-col-lg-9 wpts-col-md-8 wpts-col-sm-12 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_gender"><?php esc_html_e("Gender","wptopschool");?></label>
                            <div class="wpts-radio-inline">
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'male') echo esc_html("checked","wptopschool"); ?> value="Male" checked="checked">
                                    <label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
                                </div>
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'female') echo esc_html("checked","wptopschool"); ?> value="Female">
                                    <label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
                                </div>
                                <div class="wpts-radio">
                                    <input type="radio" name="p_gender" <?php if (strtolower($stinfo->p_gender) == 'other') echo esc_html("checked","wptopschool"); ?> value="other">
                                    <label for="other"><?php echo esc_html_e("Other","wptopschool");?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix wpts-ipad-show"></div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="firstname"><?php esc_html_e("First Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_fname'])){
                                  $is_required =  esc_html($is_required_parent['p_fname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="firstname" value="<?php echo !empty($stinfo->p_fname) ? esc_attr($stinfo->p_fname) : ''; ?>" name="p_fname">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="middlename"><?php esc_html_e("Middle Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_mname'])){
                                  $is_required =  esc_html($is_required_parent['p_mname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="middlename" value="<?php echo !empty($stinfo->p_mname) ? esc_attr($stinfo->p_mname) : ''; ?>" name="p_mname"  >
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="lastname"><?php esc_html_e("Last Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_lname'])){
                                  $is_required =  esc_html($is_required_parent['p_lname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                            <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="lastname" value="<?php echo !empty($stinfo->p_lname) ? esc_attr($stinfo->p_lname) : ''; ?>" name="p_lname" >
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <?php // If parent not created then add this fields?>
                    <?php if($stinfo->parent_wp_usr_id == 0){?>
                        <input type="hidden"  name="studentprofileparentnew" value="studentprofileparentnew">
                        <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="pEmail"><?php esc_html_e("Email Address","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['pEmail'])){
                                      $is_required =  esc_html($is_required_parent['pEmail'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control chk-email" id="pEmail" name="pEmail" type="email">
                            </div>
                        </div>

                        <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Username"><?php esc_html_e("Username","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['pUsername'])){
                                      $is_required =  esc_html($is_required_parent['pUsername'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control chk-username" id="p_username" name="pUsername">
                            </div>
                        </div>

                        <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Password"> <?php esc_html_e("Password","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['pPassword'])){
                                        $is_required =  esc_html($is_required_parent['pPassword'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="password" data-is_required="<?php echo esc_attr($is_required);?>" class="wpts-form-control" id="p_password" name="pPassword">
                            </div>
                        </div>

                        <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="ConfirmPassword"><?php esc_html_e("Confirm Password","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['pConfirmPassword'])){
                                        $is_required =  esc_html($is_required_parent['pConfirmPassword'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="p_confirmpassword" name="pConfirmPassword" >
                            </div>
                        </div>
                    <?php } ?>

                    <div class="wpts-col-md-3 wpts-col-sm-3 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_edu"><?php esc_html_e("Education","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_edu'])){
                                  $is_required =  esc_html($is_required_parent['p_edu'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" value="<?php echo esc_attr($stinfo->p_edu); ?>" name="p_edu">
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-3 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_profession"><?php esc_html_e("Profession","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_profession'])){
                                  $is_required =  esc_html($is_required_parent['p_profession'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" name="p_profession" value="<?php echo esc_attr($stinfo->p_profession); ?>">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-3 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label for="phone"><?php esc_html_e("Phone","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['s_phone'])){
                                  $is_required =  esc_html($is_required_parent['s_phone'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="phone" value="<?php echo esc_attr($stinfo->s_phone); ?>" name="s_phone" >
                        </div>
                    </div>

                    <div class="wpts-col-lg-3 wpts-col-md-3 wpts-col-sm-3 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label for="bloodgroup"><?php esc_html_e("Blood Group","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['section']) && $is_required_parent['section'] == "parent" && isset($is_required_parent['p_bloodgroup'])){
                                    $is_required =  esc_html($is_required_parent['p_bloodgroup'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <select data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Bloodgroup" name="p_bloodgrp">
                                <option value=""><?php echo esc_html("Select Blood Group","wptopschool");?></option>
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
                     <?php
                            do_action('wpts_after_student_parent_detail_fields');
                        ?>
                    <div class="wpts-col-xs-12">
                        <button type="submit" class="wpts-btn wpts-btn-success" id="studentform"><?php echo esc_html("Next","wptopschool");?></button>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_school_detail', esc_html__( 'School Details', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
                <div class="wpts-row">
                     <?php
                          do_action('wpts_before_student_school_detail_fields');
                          /*Required field Hook*/
                          $is_required_school = apply_filters('wpts_student_school_is_required',array());
                      ?>
                    <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="dateofbirth"><?php esc_html_e("Joining Date","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_school['section']) && $is_required_school['section'] == "school" && isset($is_required_school['s_doj'])){
                                    $is_required =  esc_html($is_required_school['s_doj'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control select_date" id="Doj" value="<?php echo !empty( $stinfo->s_doj ) ? wpts_ViewDate(esc_attr($stinfo->s_doj)) : '' ; ?>" name="s_doj">
                        </div>
                    </div>
                    <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                      <?php
                      $classes = [];
                      $classIDArray = [];
                      $class_table = $wpdb->prefix . "wpts_class";
                      $class_mapping_table = $wpdb->prefix . "wpts_class_mapping";
                      $classes = $wpdb->get_results("select cid,c_name from $class_table");
                      ?>
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="Class"><?php esc_html_e("Class","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_school['section']) && $is_required_school['section'] == "school" && isset($is_required_school['Class'])){
                                    $is_required =  esc_html($is_required_school['Class'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <?php

                            if (is_numeric($stinfo->class_id)){
                              $classIDArray[] = $stinfo->class_id;
                            }else{
                              $classIDArray = unserialize($stinfo->class_id);
                            }
                            $prohistory    =    wpts_check_pro_version('wpts_mc_version');
                            $prodisablehistory    =    ! $prohistory['status'] ? 'notinstalled' : 'installed';

                            if( $prodisablehistory == 'installed'){
                              echo '<select data-is_required="'.esc_attr($is_required).'" class="selectpicker wpts-form-control multiselect student-profile" multiple="multiple" name="Class[]" data-icon-base="fa" data-tick-icon="fa-check" multiple data-live-search="true">';
                            }else{
                              echo '<select data-is_required="'.esc_attr($is_required).'" class="wpts-form-control" name="Class[]">';
                              echo '<option value="" disabled selected>Select Class</option>';
                            }

                            foreach ($classes as $class) {
                            ?>
                              <option value="<?php echo esc_attr($class->cid); ?>" <?php if(!empty($classIDArray)){ if (in_array( $class->cid , $classIDArray )) echo esc_html('selected','wptopschool'); }?>>
                                <?php echo esc_html($class->c_name); ?>
                              </option>
                            <?php }?>
                          </select>

                          <input type="hidden" name="prev_select_class" value="<?php echo esc_attr($stinfo->class_id);?>">
                        </div>
                        <div class="date-input-block">
                          <table class="table table-bordered" width="100%" cellspacing="0" cellpadding="5">
                          <?php

                          foreach ($classes as $class) {
                            $class_data = $wpdb->get_results("select * from $class_mapping_table where sid='".esc_sql($student_index)."' AND cid = '".esc_sql($class->cid)."'");
                            if(!empty($classIDArray)){
                            if (in_array( $class->cid , $classIDArray )){
                            ?>
                            <tr>
                              <td style="border:1px solid;padding:5px;">
                                <strong><?php echo esc_html($class->c_name); ?></strong>
                              </td>
                              <td style="border:1px solid;padding:5px;">
                              <input type='text' class='someclass datepicker' name='Classdata[]' placeholder='yyyy-mm-dd' <?php echo (((isset($class_data[0]->date) && ($class_data[0]->date != ''))) ? 'value="'.esc_attr($class_data[0]->date).'"' : ''); ?>>
                            </td>
                            </tr>
                          <?php } }
                           }?>
                            </table>
                        </div>
                    </div>
                    <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="dateofbirth"><?php esc_html_e("Roll Number","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_school['section']) && $is_required_school['section'] == "school" && isset($is_required_school['s_rollno'])){
                                      $is_required =  esc_html($is_required_school['s_rollno'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Rollno" value="<?php echo esc_attr($stinfo->s_rollno); ?>" name="s_rollno">
                        </div>
                    </div>
                     <?php
                      do_action('wpts_after_student_school_detail_fields');
                  ?>
                </div>
                <div class="">
                    <button type="submit" class="wpts-btn wpts-btn-success" id="studentform"><?php echo esc_html("Update","wptopschool");?></button>&nbsp;&nbsp;
                    <a href="<?php echo esc_url(wpts_admin_url().'sch-student')?>" class="wpts-btn wpts-dark-btn"><?php echo esc_html("Back","wptopschool");?></a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php } else { echo esc_html("Soory! No Data retrived!!","wptopschool");}?>
