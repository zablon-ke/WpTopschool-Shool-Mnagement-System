<?php

if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
    if( is_user_logged_in() ) {
        global $current_user, $wp_roles, $wpdb;
        $current_user_role=$current_user->roles[0];
        if($current_user_role=='administrator' || $current_user_role=='teacher') {
            wpts_topbar();
            wpts_sidebar();
            wpts_body_start();
            ?>

    <div class="wpts-card">
        <div class="wpts-card-head">
            <div class="subject-inner wpts-left wpts-class-filter">

                <form name="StudentClass" id="StudentClass" method="post" action="">
                <label class="wpts-labelMain"><?php _e( 'Select Role:', 'wptopschool' ); ?></label>
                <select name="ClassID" id="ClassID" class="wpts-form-control">
                    <?php
                    $sel_classid    =   isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : '';
                    $temp_table =   $wpdb->prefix."wpts_temp";
                    $sel_class      =   $wpdb->get_results("select t_type from $temp_table Order By t_id ASC");
                    $usertype = array();
                    ?>
                    <?php if($current_user_role=='administrator' ) { ?>
                    <option value="all" <?php if($sel_classid=='all') echo esc_html("selected","wptopschool"); ?>><?php _e( 'All', 'wptopschool' ); ?></option>
                    <?php } foreach( $sel_class as $classes ) {
                        if (in_array($classes->t_type, $usertype)) {} else { ?>
                        <option value="<?php echo esc_attr($classes->t_type);?>" <?php if($sel_classid==$classes->t_type) echo esc_html("selected","wptopschool"); ?>><?php echo ucfirst($classes->t_type);?></option>
                    <?php } array_push($usertype, $classes->t_type); }  ?>
                </select>
                </form>
            </div>
        </div>
    <div class="wpts-card-body">
                <div class="subject-head">
                    <div class="wpts-bulkaction">
                        <select name="bulkaction" class="wpts-form-control" id="bulkactionreqest">
                            <option value=""><?php esc_html_e( 'Select Action', 'wptopschool' ); ?></option>
                            <option value="bulkUsersApprove"><?php esc_html_e( 'Approve', 'wptopschool' ); ?></option>
                            <option value="bulkUsersDisapprove"><?php esc_html_e( 'Disapprove', 'wptopschool' ); ?></option>
                        </select>
                    </div>
                <table id="request_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
                <thead>
                    <tr>
                        <th class="nosort">
                        <input type="checkbox" id="selectall" name="selectall" class="ccheckbox">
                        </th>
                        <th><?php esc_html_e( 'Full Name', 'wptopschool' ); ?></th>
                        <th class="sort"><?php esc_html_e( 'Email', 'wptopschool' ); ?> </th>
                        <th><?php esc_html_e( 'User Role', 'wptopschool' ); ?> </th>
                        <th><?php esc_html_e( 'Date Of Registration', 'wptopschool' ); ?></th>
                        <th  align="center" class="nosort"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $student_table  =   $wpdb->prefix."wpts_temp";
                            $class_id='';
                            if( isset($_POST['ClassID'] ) ) {
                                $class_id= sanitize_text_field($_POST['ClassID']);
                            }else if( !empty( $sel_class ) ) {
                                $class_id = 'all';
                            }
                            $classquery =   " where t_type='".esc_sql($class_id)."' ";
                            if($class_id=='NULL'){
                                $classquery =   "";
                            }elseif($class_id=='all'){
                                $classquery="";
                            }
                    $students   =   $wpdb->get_results("select * from $student_table $classquery Order By t_id DESC");
                    foreach($students as $stinfo)
                    {   ?>
                        <tr <?php if($stinfo->t_active == 0) {echo "style='background-color:#fcdddd'";}?>>
                            <td>
                            <input type="checkbox" class="ccheckbox strowselect" name="UID[]" value="<?php echo esc_attr($stinfo->t_id);?>">
                            </td>
                            <td><?php echo esc_html($stinfo->t_name);?></td>
                            <td><?php echo esc_html($stinfo->t_email);?></td>
                            <td><?php echo esc_html(ucfirst($stinfo->t_type));?></td>
                            <td><?php echo esc_html($stinfo->t_date);?></td>
                            <td align="center">
                                <div class="wpts-action-col">
                                    <a href="javascript:;"  id="approved_is" data-pop="ViewModal" data-id="<?php echo esc_attr($stinfo->t_id);?>" title="Approve"><?php esc_html_e( 'Approve', 'wptopschool' ); ?> |</a>
                                    <a href="javascript:;" id="d_teacher" class="wpts-popclick" data-pop="DisapproveModal" title="Disapprove" data-id="<?php echo esc_attr($stinfo->t_id);?>" ><?php esc_html_e( 'Disapprove', 'wptopschool' ); ?></a>
                                </div>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?php esc_html_e( 'Full Name', 'wptopschool' ); ?></th>
                        <th><?php esc_html_e( 'Email', 'wptopschool' ); ?> </th>
                        <th><?php esc_html_e( 'User Role', 'wptopschool' ); ?> </th>
                        <th><?php esc_html_e( 'Date Of Registration', 'wptopschool' ); ?></th>
                    <th  align="center"><?php esc_html_e( 'Action', 'wptopschool' ); ?></th>
                  </tr>
                </tfoot>
              </table>
              </div>
            </div><!-- /.box-body -->
        </div>

            <?php wpts_body_end();
            wpts_footer();  } ?>
    <div class="wpts-popupMain" id="ViewModal">
      <div class="wpts-overlayer"></div>
      <div class="wpts-popBody">
        <div class="wpts-popInner">
            <a href="javascript:;" class="wpts-closePopup"></a>
            <div id="ViewModalContent"></div>
        </div>
      </div>
    </div>
<div class="wpts-popupMain wpts-popVisible" id="DisapproveModal" data-pop="DisapproveModal" style="display:none;">
  <div class="wpts-overlayer"></div>
  <div class="wpts-popBody wpts-alert-body">
    <div class="wpts-popInner">
        <a href="javascript:;" class="wpts-closePopup"></a>
        <div class="wpts-popup-cont wpts-alertbox wpts-alert-danger">
            <div class="wpts-alert-icon-box">
                <i class="icon wpts-icon-question-mark"></i>
            </div>
            <div class="wpts-alert-data">
                <h4><?php esc_html_e( 'Confirmation Needed', 'wptopschool' ); ?></h4>
                <p><?php esc_html_e( 'Are you sure want to disapprove?', 'wptopschool' ); ?></p>
            </div>
            <div class="wpts-alert-btn">
                <input type="hidden" name="teacherid" id="teacherid">
                <a class="wpts-btn wpts-btn-danger ClassDeleteBt">Ok</a>
                <a href="javascript:;" class="wpts-btn wpts-dark-btn wpts-popup-cancel"><?php esc_html_e( 'Cancel', 'wptopschool' ); ?></a>
            </div>
        </div>
    </div>
  </div>
</div>
    <?php
    }
    else {
        include_once( WPTS_PLUGIN_PATH.'/includes/wpts-login.php');//Include login
    }
?>
