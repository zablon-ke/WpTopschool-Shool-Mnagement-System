<?php if (!defined( 'ABSPATH' ) )exit('No Such File');
?>
<!-- This form is used for Add New Student -->
<div id="formresponse"></div>
<form name="StudentEntryForm" id="StudentEntryForm" method="POST" enctype="multipart/form-data"><div class="wpts-col-xs-12">
    <div class="wpts-row">
    <div class="wpts-card">
                <div class="wpts-card-head">
                    <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_personal_detail', esc_html__( 'Personal Details', 'wptopschool' )); ?></h3>
                </div>
                <div class="wpts-card-body">
                     <?php wp_nonce_field('StudentRegister', 'sregister_nonce', '', true) ?>
                    <div class="wpts-row">

                        <?php
                          do_action('wpts_before_student_personal_detail_fields');
                          /*Required field Hook*/
                          $is_required_item = apply_filters('wpts_student_personal_is_required',array());
                        ?>

                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                                <div class="wpts-form-group">
                                    <label class="wpts-label displaypicture">
                                      <?php esc_html_e("Profile Image","wptopschool");?>
                                    </label>
                                    <div class="wpts-profileUp">
                                        <img class="wpts-upAvatar" id="img_preview1"  src="<?php echo esc_url(plugins_url( 'img/default_avtar.jpg', dirname(__FILE__) ))?>">
                                        <div class="wpts-upload-button"><i class="fa fa-camera"></i>

                                        <input name="displaypicture"  class="wpts-file-upload" id="displaypicture"  type="file" accept="image/jpg, image/jpeg" />
                                        </div>
                                    </div>
                                    <p class="wpts-form-notes">* <?php echo esc_html("Only JPEG and JPG supported, * Max 3 MB Upload","wptopschool");?> </p>
                                    <!-- <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;">Please Upload Profile Image</label> -->
                                    <p id="test" style="color:red"></p>
                                </div>
                        </div>
                        <div class="wpts-col-lg-9 wpts-col-md-8 wpts-col-sm-12 wpts-col-xs-12">
                                <div class="wpts-form-group">
                                    <label class="wpts-label" for="gender">
                                      <?php esc_html_e("Gender","wptopschool");?>
                                    </label>
                                    <div class="wpts-radio-inline">
                                        <div class="wpts-radio">
                                            <input type="radio" name="s_gender" value="Male" checked="checked" id="Male">
                                            <label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
                                        </div>
                                        <div class="wpts-radio">
                                            <input type="radio" name="s_gender" value="Female" id="Female">
                                            <label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
                                        </div>
                                        <div class="wpts-radio">
                                            <input type="radio" name="s_gender" value="other" id="other">
                                            <label for="other"><?php echo esc_html_e("Other","wptopschool");?></label>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="clearfix wpts-ipad-show"></div>
                        <input type="hidden"  id="wpts_locationginal1" value="<?php echo esc_url(admin_url());?>"/>
                        <div class="clearfix wpts-ipad-show"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="firstname">
                                  <?php esc_html_e("First Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_fname'])){
                                      $is_required =  esc_html($is_required_item['s_fname'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="firstname" name="s_fname" >
                                <input type="hidden"  id="wpts_locationginal" value="<?php echo admin_url();?>"/>
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="middlename">
                                  <?php esc_html_e("Middle Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['middlename'])){
                                      $is_required =  esc_html($is_required_item['middlename'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="middlename" name="middlename">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="lastname">
                                  <?php esc_html_e("Last Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_lname'])){
                                      $is_required =  esc_html($is_required_item['s_lname'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                                <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="lastname" name="s_lname">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="dateofbirth">
                                  <?php esc_html_e("Date of Birth","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_dob'])){
                                      $is_required =  esc_html($is_required_item['s_dob'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" class="wpts-form-control select_date" data-is_required="<?php echo esc_attr($is_required); ?>"  id="Dob" name="s_dob" placeholder="mm/dd/yyyy" readonly>
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
                                    <option value=""><?php echo esc_html("Select Blood Group","wptopschool");?></option>
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
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                        <label class="wpts-label"  for="s_p_phone">
                                          <?php esc_html_e("Phone Number","wptopschool");
                                          /*Check Required Field*/
                                          if(isset($is_required_item['s_p_phone'])){
                                              $is_required =  esc_html($is_required_item['s_p_phone'],"wptopschool");
                                          }else{
                                              $is_required = false;
                                          }

                                          ?>
                                          <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                        </label>
                                        <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="s_p_phone" name="s_p_phone" onkeypress='return event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.charCode >= 48 && event.charCode <= 57'>
                                        <small><?php echo esc_html("(Please enter country code with mobile number)","wptopschool");?></small>
                                    </div>
                                </div>
                        <div class="wpts-col-xs-12">
                            <hr />
                            <h4 class="card-title mt-5"><?php echo esc_html("Address","wptopschool");?></h4>
                        </div>
                        <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Address">
                                  <?php esc_html_e("Current Address","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_address'])){
                                      $is_required =  esc_html($is_required_item['s_address'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" name="s_address" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" rows="4" id="current_address" />
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                           <div class="wpts-form-group ">
                                <label class="wpts-label" for="CityName">
                                  <?php esc_html_e("City Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_city'])){
                                      $is_required =  esc_html($is_required_item['s_city'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="current_city" name="s_city">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Country">
                                  <?php esc_html_e("Country","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_country'])){
                                      $is_required =  esc_html($is_required_item['s_country'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <?php $countrylist = wpts_county_list();?>
                                <select class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="current_country" name="s_country" >
                                    <option value=""><?php echo esc_html("Select Country","wptopschool");?></option>
                                    <?php
                                        foreach( $countrylist as $key=>$value ) { ?>
                                    <option value="<?php echo esc_attr($value);?>"><?php echo esc_html($value);?></option>
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
                                <input type="text" class="wpts-form-control" id="current_pincode" name="s_zipcode" data-is_required="<?php echo esc_attr($is_required); ?>">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <input type="checkbox"  id="sameas" value="1" class="wpts-checkbox"> <label for="sameas"> <?php echo esc_html("Same as Above","wptopschool");?> </label>
                            </div>
                        </div>
                        <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <div class="wpts-form-group">
                                  <?php
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_paddress'])){
                                      $is_required =  esc_html($is_required_item['s_paddress'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                    <label for="Address"><?php esc_html_e("Permanent Address","wptopschool");?><span class="wpts-required"><?php echo ($is_required)?"*":""; ?></span></label>
                                    <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" rows="5" id="permanent_address" name="s_paddress">
                                </div>
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Zipcode">
                                  <?php esc_html_e("City Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_pcity'])){
                                      $is_required =  esc_html($is_required_item['s_pcity'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text " class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="permanent_city" name="s_pcity">
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Zipcode">
                                  <?php esc_html_e("Country","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_pcountry'])){
                                      $is_required =  esc_html($is_required_item['s_pcountry'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <select class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="permanent_country"  name="s_pcountry">
                                    <option value=""><?php echo esc_html("Select Country","wptopschool");?></option>
                                    <?php foreach ($countrylist as $key => $value) { ?>
                                        <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Zipcode">
                                  <?php esc_html_e("Pin Code","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['s_pzipcode'])){
                                      $is_required =  esc_html($is_required_item['s_pzipcode'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }

                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                 </label>
                                   <input type="text" class="wpts-form-control" id="permanent_pincode" name="s_pzipcode"  data-isrequired="<?php echo esc_attr($is_required); ?>">
                            </div>
                        </div>

                          <?php

                              do_action( 'wpts_after_student_personal_detail_fields' );
                          ?>

                        <div class="wpts-col-xs-12 wpts-hidden-xs">
                            <button type="submit" class="wpts-btn wpts-btn-success" id="studentform1"><?php echo esc_html("Next","wptopschool");?></button>&nbsp;&nbsp;
                           <!--  <a href="<?php echo esc_url(wpts_admin_url());?>sch-student" class="wpts-btn wpts-dark-btn">Back</a> -->
                        </div>
                    </div>
                </div>
        </div>
      <div class="wpts-row">
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
                                <label class="customUpload btnUpload  wpts-label">
                                  <?php esc_html_e("Profile Image","wptopschool");?>
                                  </label>
                                <div class="wpts-profileUp">
                                    <img class="wpts-upAvatar" id="img_preview1"  src="<?php echo esc_url(plugins_url( 'img/default_avtar.jpg', dirname(__FILE__) ))?>">

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
                                <label class="wpts-label" for="p_gender">
                                  <?php esc_html_e("Gender","wptopschool"); ?>
                                  </label>
                                <div class="wpts-radio-inline">
                                    <div class="wpts-radio">
                                        <input type="radio" name="p_gender" value="Male" checked="checked" id="p_Male">
                                        <label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="p_gender" value="Female" id="p_Female">
                                        <label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="p_gender" value="other" id="p_other">
                                        <label for="other"><?php echo esc_html_e("Other","wptopschool");?></label>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="clearfix wpts-ipad-show"></div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="firstname">
                              <?php esc_html_e("First Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['p_fname'])){
                                  $is_required =  esc_html($is_required_parent['p_fname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="p_firstname" name="p_fname">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="middlename"><?php esc_html_e("Middle Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['p_mname'])){
                                  $is_required =  esc_html($is_required_parent['p_mname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" class="wpts-form-control" <?php echo esc_attr($is_required); ?> id="p_middlename" name="p_mname">
                        </div>
                    </div>
                    <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="lastname"><?php esc_html_e("Last Name","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['p_mname'])){
                                  $is_required =  esc_html($is_required_parent['p_mname'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                            </label>
                            <input type="text" class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="p_lastname" name="p_lname">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="Username"><?php esc_html_e("Username","wptopschool");
                              /*Check Required Field*/
                              if(isset($is_required_parent['pUsername'])){
                                  $is_required =  esc_html($is_required_parent['pUsername'],"wptopschool");
                              }else{
                                  $is_required = false;
                              }
                              ?>
                              <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                            </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control chk-username" id="p_username" name="pUsername">
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="Password">
                              <?php esc_html_e("Password","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['pPassword'])){
                                    $is_required =  esc_html($is_required_parent['pPassword'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="p_password" name="pPassword">
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="ConfirmPassword">
                              <?php esc_html_e("Confirm Password","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['pConfirmPassword'])){
                                    $is_required =  esc_html($is_required_parent['pConfirmPassword'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="p_confirmpassword" name="pConfirmPassword" >
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="pbloodgroup"><?php esc_html_e("Blood Group","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_parent['p_bloodgroup'])){
                                        $is_required =  esc_html($is_required_parent['p_bloodgroup'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                    </label>
                                <select class="wpts-form-control" data-is_required="<?php echo esc_attr($is_required); ?>" id="p_bloodgroup" name="p_bloodgroup">
                                    <option value=""><?php echo esc_html("Select Blood Group","wptopschool");?></option>
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
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="pEmail">
                              <?php esc_html_e("Email Address","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['pEmail'])){
                                    $is_required =  esc_html($is_required_parent['pEmail'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input  data-required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control chk-email" id="pEmail" name="pEmail" type="email">
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="phone">
                                  <?php esc_html_e("Phone","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_parent['s_phone'])){
                                        $is_required =  esc_html($is_required_parent['s_phone'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="s_phone" name="s_phone">
                            </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_edu">
                              <?php esc_html_e("Education","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['p_edu'])){
                                    $is_required =  esc_html($is_required_parent['p_edu'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" name="p_edu" id="p_edu">
                        </div>
                    </div>
                    <div class="wpts-col-md-3 wpts-col-sm-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="p_profession">
                              <?php esc_html_e("Profession","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_parent['p_profession'])){
                                    $is_required =  esc_html($is_required_parent['p_profession'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                              </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" name="p_profession" id="p_profession">
                        </div>
                    </div>

                        <?php
                            do_action('wpts_after_student_parent_detail_fields');
                        ?>

                    <div class="wpts-col-xs-12 wpts-hidden-xs">
                        <button type="submit" class="wpts-btn wpts-btn-success" id="studentform2">Next</button>&nbsp;&nbsp;
                        <!-- <a href="<?php echo esc_url(wpts_admin_url());?>sch-student" class="wpts-btn wpts-dark-btn">Back</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
      <div class="wpts-row">
    <div class="wpts-col-lg-6 wpts-col-md-6  wpts-col-sm-6 wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_account_information', esc_html__( 'Account Information', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
              <div class="wpts-form-group">
                <?php
                    do_action('wpts_before_student_account_detail_fields');
                    /*Required field Hook*/
                    $is_required_account = apply_filters('wpts_student_account_is_required',array());
                ?>
              </div>
               <div class="wpts-form-group">
                    <label class="wpts-label" for="Email"><?php esc_html_e("Email Address","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_account['Email'])){
                            $is_required =  esc_html($is_required_account['Email'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                      </label>
                    <input type="email" data-is_required="<?php echo esc_attr($is_required); ?>" class="chk-email wpts-form-control" id="Email" name="Email">
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Username">
                      <?php esc_html_e("Username","wptopschool");
                          /*Check Required Field*/
                          if(isset($is_required_account['section']) && $is_required_account['section'] == "account" && isset($is_required_account['Username'])){
                              $is_required =  esc_html($is_required_account['Username'],"wptopschool");
                          }else{
                              $is_required = true;
                          }
                          ?>
                          <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                        </label>
                    <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control chk-username" id="Username" name="Username">
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Password"><?php esc_html_e("Password","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_account['section']) && $is_required_account['section'] == "account" && isset($is_required_account['Password'])){
                            $is_required =  esc_html($is_required_account['Password'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                      </label>
                    <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Password" name="Password" >
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="ConfirmPassword"><?php esc_html_e("Confirm Password","wptopschool");

                        /*Check Required Field*/
                        if(isset($is_required_account['section']) && $is_required_account['section'] == "account" && isset($is_required_account['ConfirmPassword'])){
                            $is_required =  esc_html($is_required_account['ConfirmPassword'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                      </label>
                    <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="ConfirmPassword" name="ConfirmPassword" >
                </div>
                <div class="wpts-form-group">
                  <?php
                      do_action('wpts_after_student_account_detail_fields');
                  ?>
                </div>
                <div class="wpts-hidden-xs">
                    <button type="submit" class="wpts-btn wpts-btn-success" id="studentform3"><?php echo esc_html('Next','wptopschool');?></button>&nbsp;&nbsp;
                    <!-- <a href="<?php echo esc_url(wpts_admin_url());?>sch-student" class="wpts-btn wpts-dark-btn">Back</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="wpts-col-lg-6 wpts-col-md-6  wpts-col-sm-6 wpts-col-xs-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_student_title_school_detail', esc_html__( 'School Details', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
                  <?php
                          do_action('wpts_before_student_school_detail_fields');
                          /*Required field Hook*/
                          $is_required_school = apply_filters('wpts_student_school_is_required',array());
                      ?>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Doj"><?php esc_html_e("Joining Date","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_school['section']) && $is_required_school['section'] == "school" && isset($is_required_school['s_doj'])){
                            $is_required =  esc_html($is_required_school['s_doj'],"wptopschool");
                        }else{
                            $is_required = false;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                      </label>
                    <input type="text" class="wpts-form-control select_date Doj" id="Doj" name="s_doj" value="<?php echo date('m/d/Y'); ?>" placeholder="mm/dd/yyyy" readonly>
                </div>
                <div class="wpts-row">
                    <div class="wpts-col-md-12 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="empcode">
                              <?php esc_html_e("Class","wptopschool");
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
                              $class_table = $wpdb->prefix . "wpts_class";
                              $classes = $wpdb->get_results("select cid,c_name from $class_table");
                              $prohistory    =    wpts_check_pro_version('wpts_mc_version');
                              $prodisablehistory    =    !$prohistory['status'] ? 'notinstalled'    : 'installed';
                              if($prodisablehistory == 'installed'){
                                echo '<select class="selectpicker wpts-form-control data-is_required="'.esc_attr($is_required).'"multiselect" name="Class[]" data-icon-base="fa" data-tick-icon="fa-check" multiple data-live-search="true">';
                              }else{
                                echo '<select class="wpts-form-control" data-is_required="'.esc_attr($is_required).'"  name="Class[]">';
                                echo '<option value="" disabled selected>Select Class</option>';
                              }
                              foreach($classes as $class)
                              {
                             ?>
                              <option value="<?php echo esc_attr($class->cid); ?>"><?php echo esc_html($class->c_name); ?></option>
                          <?php
                            }
                           ?>
                          </select>
                           <div class="date-input-block">
                             <table class="table table-bordered" width="100%" cellspacing="0" cellpadding="5"></table>
                          </div>
                        </div>

                    </div>
                    <div class="wpts-col-md-12 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="dateofbirth">
                              <?php esc_html_e("Roll Number","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_school['section']) && $is_required_school['section'] == "school" && isset($is_required_school['s_rollno'])){
                                      $is_required =  esc_html($is_required_school['s_rollno'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                              ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                            <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Rollno" name="s_rollno">
                        </div>
                    </div>
                </div>
                <?php
                  do_action('wpts_after_student_school_detail_fields');
                ?>
                <div class="wpts-btnsubmit-section">
                  <button type="submit" class="wpts-btn wpts-btn-success" id="studentform4"><?php echo esc_html("Submit","wptopschool");?></button>&nbsp;&nbsp;
                  <a href="<?php echo esc_url(wpts_admin_url().'sch-student');?>" class="wpts-btn wpts-dark-btn"><?php echo esc_html("Back","wptopschool");?></a>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
<!-- End of Add New Student Form -->
