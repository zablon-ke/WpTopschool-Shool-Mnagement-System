<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
    wpts_header();
    if( is_user_logged_in() ) {
        global $current_user, $wp_roles, $wpdb;
            $current_user_role=$current_user->roles[0];
            if ($current_user_role == 'administrator' || $current_user_role == 'teacher') {
                wpts_topbar();
                wpts_sidebar();
                wpts_body_start();
                $class_table=$wpdb->prefix."wpts_class";
                $leave_table=$wpdb->prefix."wpts_leavedays";
                $current_user_id = get_current_user_id();
                if ($current_user_role == 'administrator') {
                $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table");
                }
                if ($current_user_role == 'teacher') {

                    $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table where teacher_id =  '".esc_sql($current_user_id)."'");
                }
                $classes=array();

                foreach($class_r as $classinfo){
                    $classes[$classinfo->cid]=array('c_name'=>$classinfo->c_name,'c_sdate'=>$classinfo->c_sdate,'c_edate'=>$classinfo->c_edate);
                }

                ?>
                <div class="wpts-card">
                    <div class="wpts-card-head">
                        <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_leaveclendar_heading_item',esc_html__( 'Generate Leave days for a class', 'wptopschool')); ?></h3>
                    </div>
                    <?php if ($current_user_role == 'administrator') { ?>
                    <div class="wpts-card-body" id="addLeaveDaysBody">
                        <div class="wpts-row">
                        <?php
                        if(isset($_POST['submit']) && sanitize_text_field($_POST['submit'])=='Save Dates'){
                            $class_id   =   sanitize_text_field($_POST['ClassID']);
                           if( $class_id!='' )
                                $check      =   $wpdb->get_row("select * from $leave_table where class_id='".esc_sql($class_id)."'");
                            if(!is_numeric($class_id)){
                               echo "<div class='wpts-col-md-12 wpts-text-red off-dates wpts-form-group'>Select class to generate weekly off dates</div>";
                            }else if(!empty($check)){
                                echo "<div class='wpts-col-md-12 wpts-text-red off-dates wpts-form-group'>Dates were already generated for <span class='wpts-text-blue'>".esc_html($classes[$class_id]['c_name'])."</span> please delete or use add option to add leave dates</div>";
                            }else if(sanitize_text_field($_POST['from'])=='' || sanitize_text_field($_POST['to'])=='') {
                                echo "<div class='wpts-col-md-12 wpts-text-red off-dates wpts-form-group'>Enter valid  <span class='wpts-text-blue'> school year</span> dates before trying!</div>";
                            }else{
                                $strDateFrom=date('Y-m-d',strtotime($_POST['from']));
                                $strDateTo=date('Y-m-d',strtotime($_POST['to']));
                                $check_class=$wpdb->get_row("select c_sdate,c_edate from $class_table where cid='$class_id'");
                                if($check_class->c_sdate =='' || $check_class->c_sdate ==NULL){
                                    $wpdb->update($class_table,array('c_sdate'=>$strDateFrom),array('cid'=>$class_id));
                                }
                                if($check_class->c_edate =='' || $check_class->c_edate ==NULL){
                                    $wpdb->update($class_table,array('c_edate'=>$strDateTo),array('cid'=>$class_id));
                                }
                                if(sanitize_text_field($_POST['weeklyoff'])=='0'){
                                    $weeklyoff=array('Saturday','Sunday');
                                }else{
                                    $weeklyoff=array(sanitize_text_field($_POST['weeklyoff']));
                                }
                                $leave_dates=wpts_leaveDates($strDateFrom,$strDateTo,$weeklyoff);
                                foreach($leave_dates as $ldate){
                                    $leave_table_data = array('class_id'=>intval($_POST['ClassID']),'leave_date'=>$ldate,'description'=>'Weekend');
                                    $dt_ins=$wpdb->insert($leave_table,$leave_table_data);
                                }
                            }
                        }
                        ?>
                        </div>
                        <div class="wpts-row">
                             <?php  if ($current_user_role == 'administrator'){?>
                        <div class="wpts-col-md-6">
                            <form action="" name="leaveDaysForm" id="leaveDaysForm" method="post">
                              <?php do_action("wpts_before_leavecalendar");
                                $item =  apply_filters( 'wpts_leaveclendar_title_item',array());
                                ?>
                                <div class="wpts-form-group">
                                    <label class="wpts-label"><?php esc_html_e("Class","wptopschool"); ?><span class="wpts-required">*</span></label>
                                    <select name="ClassID" id="ClassID" class="wpts-form-control">
                                        <option value=""><?php esc_html_e( 'Select Class', 'wptopschool' );?></option>
                                        <?php foreach($classes as $cid=>$cname){ ?>
                                           <option value="<?php echo esc_attr($cid);?>"><?php echo esc_html($cname['c_name']);?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="wpts-display-none">
                                    <div class="wpts-row">
                                            <div class="wpts-col-md-6">
                                                <div class="wpts-form-group">

                                                    <label class="wpts-label"><?php esc_html_e("Start Date","wptopschool"); ?><span class="wpts-required">*</span></label>
                                                    <input type="text" name="from" id="CSDate" class="wpts-form-control select_date">
                                                </div>
                                            </div>
                                            <div class="wpts-col-md-6">
                                                <div class="wpts-form-group">

                                                    <label class="wpts-label"><?php esc_html_e("End Date","wptopschool"); ?><span class="wpts-required">*</span></label>
                                                    <input type="text" name="to" id="CEDate" class="wpts-form-control select_date">
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="wpts-form-group">

                                    <label class="wpts-label"><?php esc_html_e("Weekly off ","wptopschool"); ?><span class="wpts-required">*</span></label>
                                    <select name="weeklyoff" class="wpts-form-control">
                                        <option value=""><?php esc_html_e( 'Select Day', 'wptopschool' );?></option>
                                        <option value="0"><?php esc_html_e( 'All Saturday and Sunday', 'wptopschool' );?></option>
                                        <option value="Monday"><?php esc_html_e( 'Monday', 'wptopschool' );?></option>
                                        <option value="Tuesday"><?php esc_html_e( 'Tuesday', 'wptopschool' );?></option>
                                        <option value="Wednesday"><?php esc_html_e( 'Wednesday', 'wptopschool' );?></option>
                                        <option value="Thursday"><?php esc_html_e( 'Thursday', 'wptopschool' );?></option>
                                        <option value="Friday"><?php esc_html_e( 'Friday', 'wptopschool' );?></option>
                                        <option value="Saturday"><?php esc_html_e( 'Saturday', 'wptopschool' );?></option>
                                        <option value="Sunday"><?php esc_html_e( 'Sunday', 'wptopschool' );?></option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="wpts-form-group MTTen">
                                    <input type="submit" name="submit" class="wpts-btn wpts-btn-primary" value="Save Dates">
                                </div>
                            </form>
                        </div>
                        <div class="wpts-col-md-6">
                            <ul class="wpts-ulli-list">
                                <li><?php esc_html_e( 'Use this form only once for a class to generate all weekly off dates', 'wptopschool' );?></li>
                                <li><?php esc_html_e( 'All these dates are excluded for attendance calculation', 'wptopschool' );?></li>
                                <li><?php esc_html_e( 'You can delete any date if you have a school on that date', 'wptopschool' );?></li>
                                <li><?php esc_html_e( 'This is for attendance calculation purpose only', 'wptopschool' );?></li>
                            </ul>
                        </div>
                         <?php  }?>
                    </div>
                </div>
                <?php
            }
                $leaves=$wpdb->get_results("select class_id,description,count(*) as numleaves from $leave_table group by class_id");
                ?>
                            <div class="wpts-card">
                                <div class="wpts-card-body">
                                    <table class="wpts-table" id="wpts_leave_days" cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th><?php esc_html_e( 'Class', 'wptopschool' );?></th>
                                                <th><?php esc_html_e( 'School Year', 'wptopschool' );?></th>
                                                <th><?php esc_html_e( 'Number of leave days', 'wptopschool' );?></th>
                                                 <?php  if ($current_user_role == 'administrator'){?>
                                                <th align="center" class="nosort"><?php esc_html_e( 'Action', 'wptopschool' );?></th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                    <tbody>
                                    <?php foreach($leaves as $leave){
                                        if($classes[$leave->class_id]['c_name'] == ""){ }else{ ?>
                                        <tr>
                                            <td><?php echo esc_html($classes[$leave->class_id]['c_name']);?></td>
                                            <td>
                                                <?php
                                                echo wpts_ViewDate(esc_html($classes[$leave->class_id]['c_sdate'])).' to '.wpts_ViewDate(esc_html($classes[$leave->class_id]['c_edate']));
                                                ?></td>
                                            <td><?php echo esc_html($leave->numleaves);?></td>
                                               <?php  if ($current_user_role == 'administrator'){?>
                                            <td align="center">
                                                <div class="wpts-action-col">
                                                <a href="javascript:;" class="leaveView wpts-popclick" data-pop="ViewModal" data-id="<?php echo esc_attr(intval($leave->class_id));?>">
                                                <i class="icon wpts-view wpts-view-icon"></i></a>
                                                <a href="javascript:;" class="leaveAdd wpts-popclick" data-pop="ViewModal" data-id="<?php echo esc_attr(intval($leave->class_id));?>">
                                                <i class="icon fa fa-plus-circle btn btn-primary gap-bottom-small"></i></a>
                                               <!--  <a href="javascript:;" class="leaveDelete" data-id="<?php echo intval($leave->class_id);?>">
                                                <i class="icon wpts-trash wpts-delete-icon"></i></a> -->
                                                <a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DeleteModal" title="Delete" data-id="<?php echo esc_attr($leave->class_id);?>" >
                                                    <i class="icon wpts-trash wpts-delete-icon" data-id="<?php echo esc_attr(intval($leave->class_id));?>"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <?php }?>
                                        </tr>
                                    <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><?php esc_html_e( 'Class', 'wptopschool' );?></th>
                                            <th><?php esc_html_e( 'School Year', 'wptopschool' );?></th>
                                            <th><?php esc_html_e( 'Number of leave days', 'wptopschool' );?></th>
                                              <?php  if ($current_user_role == 'administrator'){?><th class="nosort"><?php esc_html_e( 'Action', 'wptopschool' );?></th><?php }?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                                </div>
                            </div>
                    <div class="wpts-popupMain" id="ViewModal">
                      <div class="wpts-overlayer"></div>
                      <div class="wpts-popBody">
                        <div class="wpts-popInner">
                            <a href="javascript:;" class="wpts-closePopup"></a>
                            <div id="ViewModalContent" class="wpts-text-left"></div>
                        </div>
                      </div>
                    </div>
                <?php
                wpts_body_end();
                wpts_footer();
            }else if($current_user_role == 'parent' || $current_user_role == 'student'){
                wpts_topbar();
                wpts_sidebar();
                wpts_body_start();
                $current_user_id = get_current_user_id();
                                //echo 'Your User ID is: ' .$current_user_id;
                                $Student_table=$wpdb->prefix."wpts_student";
                                if($current_user_role == 'student'){
                                $examinfo=$wpdb->get_row("select class_id from $Student_table where wp_usr_id = '".esc_sql($current_user_id)."'");
                                     //$examinfo->class_id;
                                    // $ciid = sanitize_text_field(stripslashes($_GET['cid']));
                                    $class_id = base64_decode(sanitize_text_field($_GET['cid']));
                             } else {
                                $examinfo=$wpdb->get_row("select class_id from $Student_table where parent_wp_usr_id = '".esc_sql($current_user_id)."'");
                                 //$examinfo->class_id;
                                    // $ciid = sanitize_text_field(stripslashes($_GET['cid']));
                                    $class_id = base64_decode(sanitize_text_field($_GET['cid']));
                             }
                $class_table=$wpdb->prefix."wpts_class";
                $leave_table=$wpdb->prefix."wpts_leavedays";
               // $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table where cid = $examinfo->class_id");
                 $class_r=$wpdb->get_results("select cid,c_name,c_sdate,c_edate from $class_table where cid =  '".esc_sql($class_id)."'");
                $classes=array();
                foreach($class_r as $classinfo){
                    $classes[ $classinfo->cid]=array('c_name'=>$classinfo->c_name,'c_sdate'=>$classinfo->c_sdate,'c_edate'=>$classinfo->c_edate);
                }
                ?>
                            <?php
                            //$leaves=$wpdb->get_results("select class_id,description,count(*) as numleaves from $leave_table WHERE class_id = $examinfo->class_id  group by class_id");
                             $leaves=$wpdb->get_results("select class_id,description,count(*) as numleaves from $leave_table WHERE class_id = '".esc_sql($class_id)."' group by class_id");

                            ?>
                        <div class="wpts-card">
                            <div class="wpts-card-head" >
                                <h3 class="wpts-card-title"><?php esc_html_e( 'Leave Calender', 'wptopschool' );?></h3>
                            </div>
                          <div class="wpts-card-body">
                                <table class="wpts-table" id="wpts_leave_days">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e( 'Class', 'wptopschool' );?></th>
                                            <th><?php esc_html_e( 'School Yea', 'wptopschool' );?>r</th>
                                            <th><?php esc_html_e( 'Number of leave days', 'wptopschool' );?></th>
                                            <th align="center" class="nosort"><?php esc_html_e( 'Action', 'wptopschool' );?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($leaves as $leave){ ?>
                                        <tr>
                                            <td><?php echo esc_html($classes[$leave->class_id]['c_name']);?></td>
                                            <td>
                                                <?php
                                                echo wpts_ViewDate(esc_html($classes[$leave->class_id]['c_sdate'])).' to '.wpts_ViewDate(esc_html($classes[$leave->class_id]['c_edate']));
                                                ?></td>
                                            <td><?php echo esc_html($leave->numleaves-1);?></td>
                                            <td align="center">
                                                <div class="wpts-action-col minWidthAuto">
                                                <span><a href="javascript:;" class="leaveView wpts-popclick" data-pop="ViewModal" data-id="<?php echo esc_attr(intval($leave->class_id));?>"><i class="icon wpts-view wpts-view-icon"></i></a></span>
                                                </div>
                                            </td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
<!-- Modal popup of leave view -->
        <div class="wpts-popupMain" id="ViewModal">
          <div class="wpts-overlayer"></div>
          <div class="wpts-popBody">
            <div class="wpts-popInner">
                <a href="javascript:;" class="wpts-closePopup"></a>
                <div id="ViewModalContent">
                    <div class="wpts-panel-body">
                        <h3 id="leaveModalHeader" class="wpts-card-title">
                    </div>
                    <div id="leaveModalBody" class="modal-body">
                    </div>
                </div>
               <!-- <div class="wpts-col-md-12">
                    <button type="button" class="wpts-btn wpts-dark-btn wpts-popup-cancel" data-dismiss="modal">Cancel1</button>
                </div>-->
            </div>
            </div>
          </div>
        </div>
<!-- END Modal popup of leave view -->

                <div class="wpts-wrapper">
                <div class="wpts-container">
            <?php
                wpts_body_end();
                wpts_footer();?>
            </div></div>
            <?php }
    }else{
       include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
    }
?>
