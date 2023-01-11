<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
    wpts_header();
?>
<style>
.mes-dedeactivate-block{
    position: relative;
}
.mes-dedeactivate-block #message-license-deactivate{
    position: absolute;
    top: 29px;
    right: 0;
}
.mes-dedeactivate-block #multi-license-deactivate{
    position: absolute;
    top: 29px;
    right: 0;
}
.mes-dedeactivate-block #onlinepay-license-deactivate{
    position: absolute;
    top: 29px;
    right: 0;
}
</style>
<?php
    if( is_user_logged_in() ) {
        global $current_user, $wp_roles, $wpdb;
        $current_user_role=$current_user->roles[0];
        wpts_topbar();
        wpts_sidebar();
        wpts_body_start();
        //$proversion   =   wpts_check_pro_version();
        $proversion     =   wpts_check_pro_version('wpts_sms_version');
        $proclass       =   !$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
        $protitle       =   !$proversion['status'] && isset( $proversion['message'] )? $proversion['message']   : '';
        $prodisable     =   !$proversion['status'] ? 'disabled="disabled"'  : '';
        $promessage    =    wpts_check_pro_version('wpts_message_version');
        $prodisablemessage    =    !$promessage['status'] ? 'notinstalled'    : 'installed';
        $prohistory    =    wpts_check_pro_version('wpts_mc_version');
        $prodisablehistory    =    !$prohistory['status'] ? 'notinstalled'    : 'installed';
        $propayment    =    wpts_check_pro_version('pay_WooCommerce');
        $propayment    =    !$propayment['status'] ? 'notinstalled'    : 'installed';

        if($current_user_role=='administrator') {
            $ex_field_tbl   =   $wpdb->prefix."wpts_mark_fields";
            $subject_tbl    =   $wpdb->prefix."wpts_subject";
            $class_tbl      =   $wpdb->prefix."wpts_class";
        ?>
        <div class="wpts-card">
                            <?php
                            if(isset($_GET['sc'])&& sanitize_text_field($_GET['sc'])=='subField') {
                                //Fields Edit Section
                                if( isset( $_GET['sid'] ) && intval($_GET['sid'])>0 ) {
                                    $subject_id =   intval($_GET['sid']);
                                    $fields     =   $wpdb->get_results("select f.*,s.sub_name,c.c_name from $ex_field_tbl f LEFT JOIN $subject_tbl s ON s.id=f.subject_id LEFT JOIN $class_tbl c ON c.cid=s.class_id where f.subject_id='".esc_sql($subject_id)."'");
                                    ?>
                                    <div class="wpts-card-body">
                                <div class="wpts-row">
                                    <div class="wpts-col-md-12 line_box wpts-col-lg-12">
                                        <div class="wpts-form-group">
                                        <div class="wpts-row">
                                        <div class="wpts-col-md-3">
                                            <label class="wpts-labelMain"><?php _e( 'Class:', 'wptopschool'); ?></label> <?php echo esc_html($fields[0]->c_name);?>
                                        </div>
                                        <div class="wpts-col-md-3">
                                            <label class="wpts-labelMain"><?php _e( 'Subject:', 'wptopschool'); ?></label> <?php echo esc_html($fields[0]->sub_name);?>
                                        </div>
                                        </div>
                                        <input type="hidden"  id="wpts_locationginal" value="<?php echo admin_url();?>"/>
                                        </div>
                                        <div class="wpts-row">
                                        <?php
                                            if(count($fields)>0){
                                                $sno=1;
                                                foreach($fields as $field){ ?>
                                                        <div class="wpts-col-sm-6 wpts-col-md-4">
                                                            <div class="wpts-form-group smf-inline-form">
                                                                <input type="text" id="<?php echo intval($field->field_id);?>SF" value="<?php echo esc_attr($field->field_text);?>" class="wpts-form-control">
                                                                <button id="sf_update" class="wpts-btn wpts-btn-success  SFUpdate" data-id="<?php echo esc_attr(intval($field->field_id));?>"><span class="dashicons dashicons-yes"></span></button>
                                                                <button id="d_teacher" class="wpts-btn wpts-btn-danger  popclick" data-pop="DeleteModal" data-id="<?php echo esc_attr(intval($field->field_id));?>"><i class="icon wpts-trash"></i></button>
                                                          </div>
                                                        </div>
                                                <?php $sno++; }
                                            }else{
                                                echo "<div class='wpts-col-md-8 wpts-col-md-offset-4'>".__( 'No data retrived!', 'wptopschool')."</div>";
                                            }
                                        ?>
                                        </div>
                                        <a href="<?php echo esc_url(wpts_admin_url().'sch-settings&sc=subField');?>" class="wpts-btn wpts-dark-btn"><?php _e( 'Back', 'wptopschool'); ?></a>
                                    </div>
                                    </div>
                                    </div>
                                    <style>
                                    #AddFieldsButton{display:none}
                                    </style>
                                <?php }else{
                                //Subject Mark Extract fields
                                $all_fields =   $wpdb->get_results("select mfields.subject_id, GROUP_CONCAT(mfields.field_text) AS fields,class.c_name,subject.sub_name from $ex_field_tbl mfields LEFT JOIN $subject_tbl subject ON subject.id=mfields.subject_id LEFT JOIN $class_tbl class ON class.cid=subject.class_id group by mfields.subject_id");
                            ?>

                            <div class="wpts-card-body">
                                <div class="wpts-row">
                                <div class="wpts-col-md-12 wpts-table-responsive">
                                <table id="wpts_sub_division_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="nosort">#</th>
                                        <th><?php _e( 'Class', 'wptopschool'); ?></th>
                                        <th><?php _e( 'Subject', 'wptopschool'); ?></th>
                                        <th><?php _e( 'Fields', 'wptopschool'); ?></th>
                                        <th><?php _e( 'Action', 'wptopschool'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno=1;
                                    foreach($all_fields as $exfield){ ?>
                                        <tr>
                                            <td><?php echo esc_html($sno); ?></td><td><?php echo esc_html($exfield->c_name);?></td><td><?php echo esc_html($exfield->sub_name);?></td><td><?php echo esc_html($exfield->fields);?></td>
                                            <td>
                                                <div class="wpts-action-col">
                                                <a href="<?php echo esc_url(wpts_admin_url().'sch-settings&sc=subField&ac=edit&sid='.esc_attr($exfield->subject_id));?>" title="Edit"><i class="icon wpts-edit wpts-edit-icon"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $sno++; } ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th>#</th>
                                    <th><?php _e( 'Class', 'wptopschool'); ?></th>
                                    <th><?php _e( 'Subject', 'wptopschool'); ?></th>
                                    <th><?php _e( 'Fields', 'wptopschool'); ?></th>
                                    <th><?php _e( 'Action', 'wptopschool'); ?></th>
                                  </tr>
                                </tfoot>
                              </table></div>
                              </div>
                            <!--- Add Field Popup -->
                            <div class="wpts-popupMain" id="addFieldModal" >
                                <div class="wpts-overlayer"></div>
                                <div class="wpts-popBody">
                                    <div class="wpts-popInner">
                                        <a href="javascript:;" class="wpts-closePopup"></a>
                                        <div class="wpts-panel-heading">
                                            <h3 class="wpts-panel-title"><?php echo apply_filters( 'wpts_subject_mark_field_heading_item',esc_html("Add Subject Mark Fields","wptopschool")); ?></h3>
                                            </div>
                                            <div class="wpts-panel-body">
                                                        <div class="wpts-row">
                                                <form action="#" method="POST" name="SubFieldsForm" id="SubFieldsForm">
                                                                <div class="wpts-col-md-12 line_box">
                                                                    <div class="wpts-row">
                                                                      <?php
                                                                        $item =  apply_filters( 'wpts_subject_mark_field_title_item',esc_html("Class Name","wptopschool"));
                                                                        $is_required_item = apply_filters('wpts_subject_mark_field_is_required',array());
                                                                      ?>
                                                                        <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                                                            <div class="wpts-form-group">
                                                                                <?php wp_nonce_field( 'SubjectFields', 'subfields_nonce', '', true ) ?>
                                                                                <label class="wpts-label" for="Class"><?php
                                                                                      esc_html_e("Class","wptopschool");
                                                                                  /*Check Required Field*/
                                                                                  if(isset($is_required_item['ClassID'])){
                                                                                      $is_required =  esc_html($is_required_item['ClassID'],"wptopschool");
                                                                                  }else{
                                                                                      $is_required = true;
                                                                                  }
                                                                                  ?>
                                                                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                                                                <select name="ClassID" data-is_required="<?php echo esc_attr($is_required); ?>" id="SubFieldsClass" class="wpts-form-control">
                                                                                    <option value=""><?php esc_html_e( 'Select Class', 'wptopschool' ); ?></option>
                                                                                    <?php $classes=$wpdb->get_results("select cid,c_name from $class_tbl");
                                                                                        foreach($classes as $class){
                                                                                    ?>
                                                                                        <option value="<?php echo esc_attr(intval($class->cid));?>"><?php echo esc_html($class->c_name);?></option>
                                                                                        <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                                                            <div class="wpts-form-group">
                                                                                <label class="wpts-label" for="Subject"><?php
                                                                                      esc_html_e("Subject","wptopschool");
                                                                                  /*Check Required Field*/
                                                                                  if(isset($is_required_item['SubjectID'])){
                                                                                      $is_required =  esc_html($is_required_item['SubjectID'],"wptopschool");
                                                                                  }else{
                                                                                      $is_required = true;
                                                                                  }
                                                                                  ?>
                                                                                <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span></label>
                                                                                <select name="SubjectID" data-is_required="<?php echo esc_attr($is_required); ?>" id="SubFieldSubject" class="wpts-form-control">
                                                                                    <option value=""><?php esc_html_e( 'Select Subject', 'wptopschool' ); ?></option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                                                            <div class="wpts-form-group">
                                                                                <label class="wpts-label" for="Field"><?php
                                                                                      esc_html_e("Field","wptopschool");
                                                                                  /*Check Required Field*/
                                                                                  if(isset($is_required_item['FieldName'])){
                                                                                      $is_required =  esc_html($is_required_item['FieldName'],"wptopschool");
                                                                                  }else{
                                                                                      $is_required = true;
                                                                                  }
                                                                                  ?>
                                                                                  <span class="wpts-required"><?php echo esc_html(($is_required))?"*":""; ?></span>
                                                                                </label>
                                                                                <input type="text" data-is_required="<?php echo esc_attr($is_required); ?>" name="FieldName" class="wpts-form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="wpts-col-md-12">
                                                                            <button type="submit" class="wpts-btn wpts-btn-success"><?php echo apply_filters( 'wpts_subject_mark_field_button_submit_text',esc_html("Submit","wptopschool")); ?></button>
                                                                            <button type="button" class="wpts-btn wpts-dark-btn" data-dismiss="modal"><?php echo apply_filters( 'wpts_subject_mark_field_button_cancel_text',esc_html("Cancel","wptopschool")); ?></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div>
                                        <!-- End popup -->
                            </div>
                            <?php
                                }
                        } else if(isset($_GET['sc'])&& sanitize_text_field($_GET['sc'])=='WrkHours') {
                                //Class Hours
                                if(isset($_POST['AddHours'])){
                                    $workinghour_table  =   $wpdb->prefix."wpts_workinghours";
                                    if( empty( $_POST['hname'] ) || empty( $_POST['hstart'] ) || empty( $_POST['hend'])  || sanitize_text_field($_POST['htype'])=='' ) {
                                        echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Please fill all values.', 'wptopschool')."</div></div>";
                                    } elseif( strtotime( $_POST['hend'] ) <= strtotime( $_POST['hstart'] ) ) {
                                        echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Invalid Class Time.', 'wptopschool')."</div></div>";
                                    } else {
                                        $workinghour_namelist = $wpdb->get_var( $wpdb->prepare( "SELECT count( * ) AS total_hour FROM $workinghour_table WHERE HOUR = %s", $_POST['hname'] ) );
                                        if( $workinghour_namelist > 0 ) {
                                            echo "<div class='col-md-12'><div class='alert alert-danger'>".__( 'Class Hour Name Already exists.', 'wptopschool')."</div></div>";
                                        } else {
                                                    $workinghour_table_data = array('hour' =>  sanitize_text_field($_POST['hname']),
                                                    'begintime' =>  sanitize_text_field( $_POST['hstart'] ),
                                                    'endtime'   =>  sanitize_text_field( $_POST['hend'] ),
                                                    'type'      =>  sanitize_text_field( $_POST['htype'] )
                                                    );
                                            $ins=$wpdb->insert( $workinghour_table,$workinghour_table_data);
                                        }
                                    }
                                }
                                if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='DeleteHours' ) {
                                    $workinghour_table=$wpdb->prefix."wpts_workinghours";
                                    $hid=intval($_GET['hid']);
                                    $del=$wpdb->delete($workinghour_table,array('id'=>$hid));
                                }
                                //Save hours
                            ?>
                            <div class="wpts-card-body">
                            <form name="working_hour" method="post" action="">
                                <div class="wpts-form-group">
                                            <h3 class="wpts-card-title"><?php echo apply_filters('wpts_workinghours_heading_item',esc_html__( 'Class hours', 'wptopschool'));?></h3>
                                        </div>
                                            <div class="wpts-row">
                                              <?php
                                                  do_action("wpts_workinghours_before");
                                                  $item =  apply_filters( 'wpts_setting_workinghours_title_item',array());
                                              ?>
                                                <div class="wpts-col-md-4">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label"><?php
                                                              esc_html_e("Class Hour Name","wptopschool");
                                                      ?></label>
                                                        <input type="text" name="hname" class="wpts-form-control">
                                                     </div>
                                                </div>
                                                <div class="wpts-col-md-2 wpts-col-sm-6">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label"><?php
                                                              esc_html_e("From","wptopschool");
                                                      ?></label>
                                                        <input type="text" name="hstart" class="wpts-form-control"  id="timepicker1">
                                                     </div>
                                                </div>
                                                <div class="wpts-col-md-2 wpts-col-sm-6">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label"><?php
                                                              esc_html_e("To","wptopschool");
                                                      ?></label>
                                                        <input type="text" name="hend" class="wpts-form-control"  id="wp-end-time" data-provide="timepicker">
                                                     </div>
                                                </div>
                                                <div class="wpts-col-md-4">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label"><?php
                                                              esc_html_e("Type","wptopschool");
                                                      ?></label>
                                                        <select name="htype" class="wpts-form-control">
                                                            <option value="1"><?php esc_html_e( 'Teaching', 'wptopschool' ); ?></option>
                                                            <option value="0"><?php esc_html_e( 'Break', 'wptopschool' ); ?></option>
                                                        </select>
                                                     </div>
                                                </div>
                                                <div class="wpts-col-md-12">
                                                    <div class="wpts-form-group">
                                                        <button type="submit" class="wpts-btn wpts-btn-success" name="AddHours" value="AddHours"><i class="fa fa-plus"></i>&nbsp; <?php esc_html_e( 'Add Hour', 'wptopschool' ); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php do_action("wpts_workinghours_after"); ?>
                                </form>
                                    <table class="wpts-table" id="wpts_class_hours" cellspacing="0" width="100%" style="width:100%">
                                        <thead><tr>
                                            <th><?php esc_html_e( 'Class Hour', 'wptopschool' ); ?></th>
                                            <th><?php esc_html_e( 'Begin Time', 'wptopschool' ); ?></th>
                                            <th><?php esc_html_e( 'End Time', 'wptopschool' ); ?></th>
                                            <th><?php esc_html_e( 'Type', 'wptopschool' ); ?></th>
                                            <th class="nosort"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
                                        </tr> </thead>
                                        <tbody>
                                            <?php
                                                $htypes=array('Break','Teaching');
                                                $workinghour_table=$wpdb->prefix."wpts_workinghours";
                                                $workinghour_list =$wpdb->get_results("SELECT * FROM $workinghour_table") ;
                                                    foreach ($workinghour_list as $single_workinghour) {
                                                        $hourtype=$htypes[$single_workinghour->type]; ?>
                                                    <tr> <td><?php echo esc_html(stripslashes( $single_workinghour->hour) ) ?></td>
                                                            <td><?php echo esc_html($single_workinghour->begintime) ?></td>
                                                            <td><?php echo esc_html($single_workinghour->endtime) ?></td>
                                                            <td><?php echo esc_html($hourtype) ?></td>
                                                            <td>
                                                                <div class="wpts-action-col">
                                                                    <a href="<?php echo esc_url(wpts_admin_url().'sch-settings&sc=WrkHours&ac=DeleteHours&hid='.esc_attr(intval($single_workinghour->id))); ?>" class="delete"><i class="icon wpts-trash wpts-delete-icon"></i></a>
                                                                </div>
                                                            </td>
                                                            </tr>
                                                <?php   }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php esc_html_e( 'Class Hour', 'wptopschool' ); ?> </th>
                                                <th><?php esc_html_e( 'Begin Time', 'wptopschool' ); ?></th>
                                                <th><?php esc_html_e( 'End Time', 'wptopschool' ); ?></th>
                                                <th><?php esc_html_e( 'Type', 'wptopschool' ); ?></th>
                                                <th class="nosort"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
                                            </tr>
                                        </tfoot>
                                </table>
                                </div>
                                <?php
                            }else{
                                //General Settings
                                $wpts_settings_table    =   $wpdb->prefix."wpts_settings";
                                $wpts_settings_edit     =   $wpdb->get_results("SELECT * FROM $wpts_settings_table" );
                                foreach( $wpts_settings_edit as $sdat ) {
                                    $settings_data[$sdat->option_name]  =   $sdat->option_value;
                                }
                            ?>
                            <div class="wpts-card-body">
                            <div class="tabSec wpts-nav-tabs-custom" id="verticalTab">
                            <div class="tabList">
                                <ul class="wpts-resp-tabs-list">
                                    <li class="wpts-tabing" title="Info"><?php echo apply_filters( 'wpts_settings_tab_info_heading', esc_html__( 'Info', 'wptopschool' )); ?></li>
                                    <li class="wpts-tabing <?php echo esc_attr($proclass); ?>" title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable); ?> title="An overdose in each drop"><?php echo apply_filters( 'wpts_settings_tab_sms_heading', esc_html__( 'SMS', 'wptopschool' )); ?></li>
                                    <li class="wpts-tabing"  title="Licensing"><?php echo apply_filters( 'wpts_settings_tab_info_heading', esc_html__( 'Licensing', 'wptopschool' )); ?></li>
                                </ul>
                            </div>
                                <div class="wpts-tabBody wpts-resp-tabs-container">
                                    <div class="wpts-tabMain">
                                        <form name="schinfo_form" id="SettingsInfoForm" class="wpts-form-horizontal" method="post">
                                          <?php do_action('wpts_before_setting_info');
                                            $item =  apply_filters( 'wpts_setting_info_title_item',array());
                                          ?>
                                            <div  class="wpts-row">
                                            <div  class="wpts-form-group">
                                                <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">

                                                      <label class="wpts-label"><?php
                                                              esc_html_e("School Logo","wptopschool");
                                                      ?></label>
                                                        <div class="wpts-profileUp">
                                                          <?php
                                                          $url = plugins_url( 'img/wptopschoollogo.jpg', dirname(__FILE__) );
                                                          ?>
                                                            <img src="<?php  echo isset( $settings_data['sch_logo'] ) ? esc_url($settings_data['sch_logo']) : esc_url($url);?>" id="img_preview" onchange="imagePreview(this);" height="150px" width="150px" class="wpts-upAvatar" />
                                                            <div class="wpts-upload-button"><i class="fa fa-camera"></i>
                                                            <input type="hidden" name="old_img" id="old_image" value="<?php  echo isset( $settings_data['sch_logo'] ) ? esc_attr($settings_data['sch_logo']) : esc_attr($url);?>">
                                                            <input name="displaypicture" class="wpts-file-upload" id="displaypicture" type="file" accept="image/jpg, image/jpeg, image/png" >
                                                            </div>
                                                        </div>
                                                        <p class="wpts-form-notes">* <?php esc_html_e( 'Only JPEG, JPG and PNG supported, * Max 3 MB Upload', 'wptopschool' ); ?> </p>
                                                        <label id="displaypicture-error" class="error" for="displaypicture" style="display: none;"><?php esc_html_e( 'Please Upload Profile Image', 'wptopschool' ); ?></label>
                                                        <p id="test" style="color:red"></p>
                                                    </div>
                                                </div>
                                                <div class="wpts-col-lg-3 wpts-col-md-8 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label"><?php
                                                              esc_html_e("School Name","wptopschool");
                                                      ?></label>
                                                        <input type="text" name="sch_name" class="wpts-form-control" value="<?php echo stripslashes(isset( $settings_data['sch_name'] ) ? esc_attr($settings_data['sch_name']) : '');?>">
                                                    </div>
                                                </div>
                                                <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label" for="phone"><?php
                                                              esc_html_e("Phone","wptopschool");
                                                      ?></label>
                                                        <input type="text" class="wpts-form-control" id="phone" name="Phone" placeholder="(XXX)-(XXX)-(XXXX)"  value="<?php echo isset( $settings_data['sch_pno'] ) ? esc_attr($settings_data['sch_pno']) : '';?>">
                                                    </div>
                                                </div>
                                                <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                        <label class="wpts-label" for="phone"><?php
                                                              esc_html_e("Email Address","wptopschool");
                                                      ?></label>
                                                        <input type="text" class="wpts-form-control" id="email" name="email" placeholder="Email" value="<?php echo isset( $settings_data['sch_email'] ) ? esc_attr($settings_data['sch_email']) : '';?>">
                                                    </div>
                                                </div>
                                                <div class="wpts-col-lg-9 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                                    <div class="wpts-form-group">

                                                        <label class="wpts-label" for="Address"><?php
                                                            esc_html_e("Address","wptopschool");
                                                      ?><!-- <span class="wpts-required">*</span> --></label>
                                                        <textarea rows="2" cols="45" name="sch_addr" class="wpts-form-control"><?php echo isset( $settings_data['sch_addr'] ) ? esc_attr($settings_data['sch_addr']) : '';?></textarea>
                                                    </div>
                                                </div>
                                             <div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php
                                                            esc_html_e("City","wptopschool");
                                                      ?></label>
                                                    <input type="text" name="sch_city"  class="wpts-form-control" value="<?php echo isset( $settings_data['sch_city'] ) ? esc_attr($settings_data['sch_city']) : '';?>">
                                                </div>
                                            </div>
                                             <div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">

                                                    <label class="wpts-label"><?php
                                                            esc_html_e("State","wptopschool");
                                                      ?></label>
                                                    <input type="text" name="sch_state" class="wpts-form-control" value="<?php echo isset( $settings_data['sch_state'] ) ? esc_attr($settings_data['sch_state']) : '';?>">
                                                </div>
                                            </div>
                                              <div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                                                <div class="wpts-form-group">
                                                <label class="wpts-label" for="Country"><?php
                                                            esc_html_e("Country","wptopschool");
                                                      ?></label>
                                                 <select class="wpts-form-control" id="Country" name="country">
                                                    <option value=""><?php esc_html_e( 'Select Country', 'wptopschool' ); ?></option>
                                                <?php $country = wpts_county_list();
                                                //print_r($country);
                                                foreach ($country as $key => $value) {?>
                                                        <option value=<?php echo esc_attr($key);?><?php  if($key == $settings_data['sch_country']){ echo ' selected';}?>><?php echo esc_html($value); ?></option>
                                                <?php }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                             <div class="wpts-col-lg-3 wpts-col-md-6 wpts-col-sm-6 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php
                                                              esc_html_e("Fax","wptopschool");
                                                      ?></label>
                                                    <input type="text" name="sch_fax"  class="wpts-form-control" value="<?php echo isset( $settings_data['sch_fax'] ) ? esc_attr($settings_data['sch_fax']) :'';?>">
                                                </div>
                                            </div>
                                              <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                                                    <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php
                                                              esc_html_e("Website","wptopschool");
                                                      ?></label>
                                                    <input type="text" name="sch_website"   class="wpts-form-control" value="<?php echo isset( $settings_data['sch_website'] ) ? esc_attr($settings_data['sch_website']) : '';?>">
                                                    <input type="hidden" name="type"  value="info">
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                                                    <div class="wpts-form-group">

                                                    <label class="wpts-label"><?php
                                                              esc_html_e("Date Format","wptopschool");
                                                      ?></label>
                                                    <select name="date_format"  class="wpts-form-control">
                                                        <option value="m/d/Y" <?php echo  isset( $settings_data['date_format'] ) && ( $settings_data['date_format']=='m/d/Y')?'selected':''?>><?php esc_html_e( 'mm/dd/yyyy', 'wptopschool' ); ?></option>
                                                        <option value="Y-m-d" <?php echo  isset( $settings_data['date_format'] ) && ($settings_data['date_format']=='Y-m-d')?'selected':''?> ><?php esc_html_e( 'yyyy-mm-dd', 'wptopschool' ); ?></option>
                                                        <option value="d-m-Y" <?php echo  isset( $settings_data['date_format'] ) && ($settings_data['date_format']=='d-m-Y')?'selected':''?>><?php esc_html_e( 'dd-mm-yyyy', 'wptopschool' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-12">
                                                   <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php
                                                             esc_html_e("Marks Type","wptopschool");
                                                     ?></label>
                                                    <select name="markstype" class="wpts-form-control">
                                                        <option value="Number" <?php echo  isset( $settings_data['markstype'] ) && ( $settings_data['markstype']=='Number')?'selected':''?>><?php esc_html_e( 'Number', 'wptopschool' ); ?> </option>
                                                        <option value="Grade" <?php echo  isset( $settings_data['markstype'] ) && ($settings_data['markstype']=='Grade')?'selected':''?>><?php esc_html_e( 'Grade', 'wptopschool' ); ?> </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                            <div class="wpts-form-group <?php echo esc_attr($proclass); ?>" title="<?php echo esc_attr($protitle);?>" <?php echo esc_attr($prodisable); ?>>
                                                    <label class="wpts-label"><?php _e( 'SMS Setting' ,'wptopschool'); ?></label>
                                                    <input id="absent_sms_alert" type="checkbox" class="wpts-checkbox ccheckbox <?php echo esc_attr($proclass); ?> " title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable); ?> <?php if(isset($settings_data['absent_sms_alert']) && $settings_data['absent_sms_alert']==1) echo "checked"; ?> name="absent_sms_alert" value="1" >
                                                    <label for="absent_sms_alert" class="wpts-checkbox-label"> <?php _e( 'Send SMS to parent when student absent','wptopschool');?></label>
                                                    <input id="notification_sms_alert" type="checkbox" class="wpts-checkbox ccheckbox <?php echo esc_attr($proclass); ?>" title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable); ?> <?php if(isset($settings_data['notification_sms_alert']) && $settings_data['notification_sms_alert']==1) echo "checked"; ?> name="notification_sms_alert" value="1" >
                                                    <label for="notification_sms_alert" class="wpts-checkbox-label"> <?php _e( 'Enable SMS Notification','wptopschool');?></label>
                                                </div>
                                            </div>
                                            <div class="wpts-col-md-12 wpts-col-sm-12 wpts-col-xs-12">
                                                <div class="wpts-form-group">
                                                    <button type="submit" class="wpts-btn wpts-btn-success" id="setting_submit" name="submit" style="margin-top: 20px;!important" > <?php esc_html_e( 'Save', 'wptopschool' ); ?></button>
                                                </div>
                                            </div>
                                    </div>
                                    </div>
                                      <?php
                                        do_action('wpts_after_setting_info');
                                      ?>
                                        </form>
                                    </div>

                                    <div class="wpts-tabMain">
                                            <?php
                                        if( isset( $proversion['status'] ) && $proversion['status'] ) {
                                            do_action( 'wpts_sms_setting_html', $settings_data );
                                        } else {
                                            _e( 'Please Purchase This <a href="https://wptopschool.com/downloads/sms-add-on-wptopschool/" target="_blank">Add-on</a>', 'wptopschool' );
                                        }
                                        ?>
                                    </div>
                                    <div class="wpts-tabMain">
                                            <form name="Settingslicensing" id="Settingslicensing" class="wpts-form-horizontal" method="post">
                                            <div class="wpts-row">
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php  do_action('wpts_before_license');
                                                //    $item =  apply_filters( 'wpts_setting_licensing_title_item',array());

                                                    esc_html_e("Import Export","wptopschool");
                                                ?></label>
                                                    <input type="text" name="importexport"  class="wpts-form-control" value="<?php echo isset( $settings_data['importexport'] ) ? esc_attr($settings_data['importexport']) : '';?>">
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php
                                                          esc_html_e("SMS Addons: ","wptopschool");?></label>
                                                    <input type="text" name="smsaddons"  class="wpts-form-control" value="<?php echo isset( $settings_data['smsaddons'] ) ? esc_attr($settings_data['smsaddons']) : '';?>">
                                                </div>
                                            </div>
                                                <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group">
                                                    <label class="wpts-label"><?php esc_html_e("Front End Registration Addons: ","wptopschool");?></label>
                                                    <input type="text" name="feraddons"  class="wpts-form-control" value="<?php echo isset( $settings_data['feraddons'] ) ? esc_attr($settings_data['feraddons']) : '';?>">
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group mes-dedeactivate-block">

                                                    <label class="wpts-label"><?php esc_html_e("Dashboard to Dashboard Message Addons : ","wptopschool");?></label>
                                                    <input type="text" name="ddma"  class="wpts-form-control" value="<?php echo isset( $settings_data['ddma'] ) ? esc_attr($settings_data['ddma']) : '';?>">
                                                    <?php if($prodisablemessage == 'installed'){ ?>
                                                    <button type="button" id="message-license-deactivate" class="wpts-btn wpts-btn-denger"><?php echo esc_html("Deactivate","wptopschool");?></button>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group mes-dedeactivate-block">
                                                <label class="wpts-label"><?php esc_html_e("Multi-class Add-on :","wptopschool");?></label>
                                                    <input type="text" name="mcaon"  class="wpts-form-control" value="<?php echo isset( $settings_data['mcaon'] ) ? esc_attr($settings_data['mcaon']) : '';?>">
                                                    <?php if($prodisablehistory == 'installed'){ ?>
                                                    <button type="button" id="multi-license-deactivate" class="wpts-btn wpts-btn-denger"><?php echo esc_html("Deactivate","wptopschool");?></button>
                                                <?php } ?>
                                                </div>
                                            </div>
                                             <!-- Add Payment Lisenece button -->
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-4 wpts-col-xs-12">
                                                <div class="wpts-form-group mes-dedeactivate-block">
                                                    <label class="wpts-label"><?php esc_html_e("Online Fee Payment Addons :","wptopschool");?></label>
                                                    <input type="text" name="onlinepay"  class="wpts-form-control" value="<?php echo isset( $settings_data['onlinepay'] ) ? esc_attr($settings_data['onlinepay']) : '';?>">

                                                    <?php if($propayment == 'installed'){ ?>

                                                    <!-- <button type="button" id="onlinepay-license-deactivate" class="wpts-btn wpts-btn-denger"></?php echo esc_html("Deactivate","wptopschool");?></button> -->

                                                <?php } ?>

                                                </div>

                                            </div>
                                            <?php  do_action('wpts_after_license'); ?>
                                            <div class="wpts-col-lg-12 wpts-col-md-12 wpts-col-sm-12">
                                                <div class="wpts-form-group">
                                                    <button type="submit" id="s_save" class="wpts-btn wpts-btn-success" name="submit"><?php echo apply_filters( 'wpts_setting_licensig_button_submit_text',esc_html__('Save','wptopschool'));?></button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--    </div> -->
                            <?php } ?>
                        </div>
            <?php } else if($current_user_role=='parent' || $current_user_role=='student') {
                }
        wpts_body_end();
        wpts_footer(); ?>
    <?php
    }else {
        include_once( WPTS_PLUGIN_PATH.'/includes/wpts-login.php');
    }
?>
