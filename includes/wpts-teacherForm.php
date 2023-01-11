<?php if (!defined( 'ABSPATH' ) )exit('No Such File');?>
<!-- This form is used for Add New Teacher -->
<div id="formresponse"></div>
<form name="TeacherEntryForm" id="TeacherEntryForm" method="post">
   <div class="wpts-row">
        <div class="wpts-col-sm-12">
            <div class="wpts-card">
                 <div class="wpts-card-head">
                    <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_teacher_title_personal_detail', esc_html__( 'Personal Details', 'wptopschool' )); ?></h3>
                </div>
                <div class="wpts-card-body">
                    <?php wp_nonce_field( 'TeacherRegister', 'tregister_nonce', '', true ) ?>
                    <div class="wpts-row">
                    <?php
                      do_action('wpts_before_teacher_personal_detail_fields');
                      $is_required_item = apply_filters('wpts_teacher_personal_is_required',array());
                     ?>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label">
                                  <?php esc_html_e("Profile Image","wptopschool"); ?>
                                </label>
                                <div class="wpts-profileUp"><?php $turl = WPTS_PLUGIN_URL . 'img/default_avtar.jpg';?>
                                    <img class="wpts-upAvatar" id="img_preview_teacher" src="<?php echo esc_url($turl);?>">
                                    <div class="wpts-upload-button"><i class="fa fa-camera"></i><input name="displaypicture" class="wpts-file-upload" id="displaypicture" type="file" accept="image/jpg, image/jpeg" /></div>
                                </div>
                                <p class="wpts-form-notes">* <?php echo esc_html("Only JPEG and JPG supported, * Max 3 MB Upload ","wptopschool");?></p>
                                <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php echo esc_html("Please Upload Profile Image","wptopschool");?></label>
                                <p id="test" style="color:red"></p></div>
                        </div>
                        <div class="wpts-col-lg-9 wpts-col-md-8 wpts-col-sm-12 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="gender">
                                  <?php esc_html_e("Gender","wptopschool");?></label>
                                <div class="wpts-radio-inline">
                                    <div class="wpts-radio">
                                        <input type="radio" name="Gender" value="Male" checked="checked" id="Male">
                                        <label for="Male"><?php echo esc_html_e("Male","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="Gender" value="Female" id="Female">
                                        <label for="Female"><?php echo esc_html_e("Female","wptopschool");?></label>
                                    </div>
                                    <div class="wpts-radio">
                                        <input type="radio" name="Gender" value="other" id="other">
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
                                  if(isset($is_required_item['firstname'])){
                                      $is_required =  esc_html($is_required_item['firstname'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="firstname" name="firstname">
                                <input type="hidden"  id="wpts_locationginal" value="<?php echo esc_url(admin_url());?>"/>
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="middlename">
                                  <?php esc_html_e("Middle Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['firstname'])){
                                      $is_required =  esc_html($is_required_item['middlename'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="middlename" name="middlename">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="lastname">
                                  <?php esc_html_e("Last Name","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['lastname'])){
                                      $is_required =  esc_html($is_required_item['lastname'],"wptopschool");
                                  }else{
                                      $is_required = true;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="lastname" name="lastname">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="dateofbirth"><?php esc_html_e("Date of Birth","wptopschool");
                                  /*Check Required Field*/
                                  if(isset($is_required_item['Dob'])){
                                      $is_required =  esc_html($is_required_item['Dob'],"wptopschool");
                                  }else{
                                      $is_required = false;
                                  }
                                  ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <input type="text" class="wpts-form-control select_date" data-is_required="<?php echo esc_attr($is_required); ?>" id="Dob" name="Dob" placeholder="mm/dd/yyyy" readonly>
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="bloodgroup">
                                  <?php esc_html_e("Blood Group","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_item['Bloodgroup'])){
                                        $is_required =  esc_html($is_required_item['Bloodgroup'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                <select data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Bloodgroup" name="Bloodgroup">
                                    <option value=""><?php echo __("Select Blood Group","wptopschool");?></option>
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
                                <label class="wpts-label" for="phone"><?php esc_html_e("Phone","wptopschool");
                                    /*Check Required Field*/
                                    if(isset($is_required_item['Phone'])){
                                        $is_required =  esc_html($is_required_item['Phone'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="phone" name="Phone" placeholder="(XXX)-(XXX)-(XXXX)">
                            </div>
                        </div>
                        <div class="wpts-col-lg-3 wpts-col-md-8 wpts-col-sm-8 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="educ"><?php esc_html_e("Qualification","wptopschool");

                                    /*Check Required Field*/
                                    if(isset($is_required_item['Qual'])){
                                        $is_required =  esc_html($is_required_item['Qual'],"wptopschool");
                                    }else{
                                        $is_required = false;
                                    }
                                    ?>
                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Qual" name="Qual">
                            </div>
                        </div>
                        <div class="wpts-col-xs-12">
                            <hr />
                            <h4 class="card-title mt-5"><?php echo esc_html("Address","wptopschool");?></h4>
                        </div>
                        <div class="wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Address">
                                  <?php esc_html_e("Street Address ","wptopschool");
                                      /*Check Required Field*/
                                      if(isset($is_required_item['Address'])){
                                          $is_required =  esc_html($is_required_item['Address'],"wptopschool");
                                      }else{
                                          $is_required = true;
                                      }
                                      ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" name="Address" class="wpts-form-control" />
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                           <div class="wpts-form-group ">
                                <label class="wpts-label" for="CityName">
                                  <?php esc_html_e("City Name","wptopschool");
                                      /*Check Required Field*/
                                      if(isset($is_required_item['city'])){
                                          $is_required =  esc_html($is_required_item['city'],"wptopschool");
                                      }else{
                                          $is_required = false;
                                      }
                                      ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="CityName" name="city"  >
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Country"><?php esc_html_e("Country","wptopschool");
                                      /*Check Required Field*/
                                      if(isset($is_required_item['country'])){
                                          $is_required =  esc_html($is_required_item['country'],"wptopschool");
                                      }else{
                                          $is_required = false;
                                      }
                                      ?>
                                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                  </label>
                                    <?php $countrylist = wpts_county_list();?>
                                <select data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Country" name="country">
                                    <option value=""><?php echo esc_html("Select Country","wptopschool");?></option>
                                    <?php foreach( $countrylist as $key=>$value ) { ?>
                                    <option value="<?php echo esc_attr($value);?>"><?php echo esc_html($value);?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                            <div class="wpts-form-group">
                                <label class="wpts-label" for="Zipcode">
                                    <?php  esc_html_e("Pin Code","wptopschool");
                                        /*Check Required Field*/
                                        if(isset($is_required_item['zipcode'])){
                                            $is_required =  esc_html($is_required_item['zipcode'],"wptopschool");
                                        }else{
                                            $is_required = false;
                                        }
                                        ?>
                                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                    </label>
                                <input type="text" class="wpts-form-control" id="Zipcode" name="zipcode">
                            </div>
                        </div>
                        <?php  do_action('wpts_after_teacher_personal_detail_fields'); ?>
                        <div class="wpts-col-xs-12 wpts-hidden-xs">
                            <button type="submit" class="wpts-btn wpts-btn-success" id="teacherform"><?php echo esc_html("Next","wptopschool");?></button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="wpts-col-md-6 wpts-col-sm-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_teacher_title_account_detail', esc_html__( 'Account Information', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
              <?php  do_action('wpts_before_teacher_account_detail_fields');
              /*Required field Hook*/
              $is_required_parent = apply_filters('wpts_teacher_account_is_required',array());
              ?>
               <div class="wpts-form-group">
                    <label class="wpts-label" for="Email"><?php esc_html_e("Email Address","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_parent['Email'])){
                            $is_required =  esc_html($is_required_parent['Email'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="email" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Email" name="Email">
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Username"><?php esc_html_e("Username","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_parent['Username'])){
                            $is_required =  esc_html($is_required_parent['Username'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Username" name="Username">
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Password"><?php esc_html_e("Password","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_parent['Password'])){
                            $is_required =  esc_html($is_required_parent['Password'],"wptopschool");
                        }else{
                            $is_required = true;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="password" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Password" name="Password">
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="ConfirmPassword">
                      <?php esc_html_e("Confirm Password","wptopschool");
                          /*Check Required Field*/
                          if(isset($is_required_parent['ConfirmPassword'])){
                              $is_required =  esc_html($is_required_parent['ConfirmPassword'],"wptopschool");
                          }else{
                              $is_required = true;
                          }
                          ?>
                      <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="password" class="wpts-form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password">
                </div>
                   <?php  do_action('wpts_after_teacher_account_detail_fields'); ?>
                <div class="wpts-hidden-xs">
                    <button type="submit" class="wpts-btn wpts-btn-success" id="teacherform"><?php echo esc_html("Next","wptopschool");?></button>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="wpts-col-md-6 wpts-col-sm-12">
        <div class="wpts-card">
            <div class="wpts-card-head">
                <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_teacher_title_school_detail', esc_html__( 'School Details', 'wptopschool' )); ?></h3>
            </div>
            <div class="wpts-card-body">
                   <?php  do_action('wpts_before_teacher_school_detail_fields');
                   /*Required field Hook*/
                   $is_required_school = apply_filters('wpts_teacher_school_is_required',array());
                   ?>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Doj"><?php esc_html_e("Joining Date","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_school['Doj'])){
                            $is_required =  esc_html($is_required_school['Doj'],"wptopschool");
                        }else{
                            $is_required = false;
                        }
                        ?>
                    <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control select_date Doj" id="Doj" name="Doj" value="" placeholder="mm/dd/yyyy" readonly>
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="Dol"><?php esc_html_e("Leaving Date","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_school['dol'])){
                            $is_required =  esc_html($is_required_school['dol'],"wptopschool");
                        }else{
                            $is_required = false;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="text" class="wpts-form-control select_date Dol" id="Dol" name="dol" value="" placeholder="mm/dd/yyyy" readonly>
                </div>
                <div class="wpts-form-group">
                    <label class="wpts-label" for="position"><?php esc_html_e("Current Position","wptopschool");
                        /*Check Required Field*/
                        if(isset($is_required_school['Position'])){
                            $is_required =  esc_html($is_required_school['Position'],"wptopschool");
                        }else{
                            $is_required = false;
                        }
                        ?>
                        <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                    <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" class="wpts-form-control" id="Position" name="Position">
                </div>
                <div class="wpts-row">
                    <div class="wpts-col-md-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="empcode"><?php esc_html_e("Employee Code","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_school['EmpCode'])){
                                    $is_required =  esc_html($is_required_school['EmpCode'],"wptopschool");
                                }else{
                                    $is_required = false;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" class="wpts-form-control" id="EmpCode" name="EmpCode">
                        </div>
                    </div>
                    <div class="wpts-col-md-6 wpts-col-xs-12">
                        <div class="wpts-form-group">
                            <label class="wpts-label" for="whours"><?php esc_html_e("Working Hours","wptopschool");
                                /*Check Required Field*/
                                if(isset($is_required_school['whours'])){
                                    $is_required =  esc_html($is_required_school['whours'],"wptopschool");
                                }else{
                                    $is_required = true;
                                }
                                ?>
                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                            <input type="text" class="wpts-form-control" id="whours" name="whours">
                        </div>
                    </div>

                </div>
                   <?php  do_action('wpts_after_teacher_school_detail_fields'); ?>
                <div class="wpts-btnsubmit-section">
                    <button type="submit" class="wpts-btn wpts-btn-success" id="teacherform"><?php echo esc_html("Submit","wptopschool");?></button>&nbsp;&nbsp;
                    <a href="<?php echo esc_url(wpts_admin_url().'sch-teacher')?>" class="wpts-btn wpts-dark-btn"><?php echo esc_html("Back","wptopschool");?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<!-- End of Add New Teacher Form -->
