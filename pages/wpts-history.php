<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
if( is_user_logged_in() ) {
  global $current_user, $wp_roles, $wpdb;
  $current_user_role=$current_user->roles[0];
  $student_id ='';
  if ($current_user_role == 'administrator' || $current_user_role == 'teacher') {
    // $sid = sanitize_text_field(stripslashes($_GET['id']));
    $student_id = base64_decode(sanitize_text_field($_GET['id']));
  }else if($current_user_role == 'parent' || $current_user_role == 'student'){
    $student_id = intval(get_current_user_id());
  }

  global $wpdb;
  $class_table = $wpdb->prefix."wpts_class";
  $student_table = $wpdb->prefix."wpts_student";
  $history_table = $wpdb->prefix."wpts_history";
  $teacher_table = $wpdb->prefix . "wpts_teacher";
  $stinfo  =  $wpdb->get_row("select CONCAT_WS(' ', s_fname, s_mname, s_lname ) AS full_name from $student_table where wp_usr_id='".esc_sql($student_id)."'");

    wpts_topbar();
    wpts_sidebar();
    wpts_body_start();

    ?>


    <div class="wpts-card">
      <div class="wpts-card-body">
        <?php
        if ($current_user_role == 'administrator' || $current_user_role == 'teacher') {
          echo "<h3 class='wpts-card-title'>".esc_html($stinfo->full_name)."'s  history</h3>";
        }
        ?>
        <table class="wpts-table" id="history_table" cellspacing="0" width="100%" style="width:100%">
          <thead>
            <tr>
              <th><?php esc_html_e( 'ID', 'wptopschool' );?></th>
              <th><?php esc_html_e( 'Class Name', 'wptopschool' );?></th>
              <th><?php esc_html_e( 'Teacher', 'wptopschool' );?></th>
              <th><?php esc_html_e( 'Start Date', 'wptopschool' );?></th>
              <th><?php esc_html_e( 'End Date', 'wptopschool' );?></th>
              <th><?php esc_html_e( 'Joining Date', 'wptopschool' );?></th>
            </tr>
          </thead>
          <tbody>

            <?php

              $historyData  =  $wpdb->get_results("SELECT ct.cid, ct.c_name, st.wp_usr_id, CONCAT_WS(' ', st.s_fname, st.s_mname, st.s_lname ) AS full_name, ht.* , CONCAT_WS(' ', tt.first_name, tt.middle_name, tt.last_name ) AS t_full_name FROM $history_table ht
                JOIN $class_table ct ON ct.cid = ht.c_id
                JOIN $teacher_table tt ON tt.wp_usr_id = ct.teacher_id
                JOIN $student_table st ON ht.s_id = st.wp_usr_id WHERE st.wp_usr_id = '".esc_sql($student_id)."'");
                if(empty($historyData)){
                  echo "<tr><td colspan='6' align='center'><strong>History Not Found!</strong></td></tr>";
                }else{
              foreach ($historyData as $history) {
                $student_count = $wpdb->get_results("SELECT class_id FROM $student_table WHERE wp_usr_id = '".esc_sql($student_id)."' ");
                $scount = 0;
                foreach ( $student_count as $count) {

                  if(is_numeric($count->class_id) ){
                    if($count->class_id == $history->cid){
                      $scount++;
                   }

                  }else{
                    $class_id_array = unserialize($count->class_id);
                    if(in_array($history->cid, $class_id_array)){
                      $scount++;
                    }
                  }

                }
                echo "<tr>
                <td>".esc_html($history->h_id)."</td>
                <td>".esc_html($history->c_name)."</td>
                <td>".esc_html($history->t_full_name)."</td>
                <td>".esc_html($history->start_date)."</td>
                <td>".esc_html($history->end_date)."</td>
                <td>".date('Y-m-d',esc_html(strtotime($history->enrollment_date)))."</td>
                </tr>";
              }
            }
              ?>

            </tbody>
            <tfoot>
              <tr>
                 <th><?php esc_html_e( 'ID', 'wptopschool' );?></th>
                 <th><?php esc_html_e( 'Class Name', 'wptopschool' );?></th>
                 <th><?php esc_html_e( 'Teacher', 'wptopschool' );?></th>
                 <th><?php esc_html_e( 'Start Date', 'wptopschool' );?></th>
                 <th><?php esc_html_e( 'End Date', 'wptopschool' );?></th>
                 <th><?php esc_html_e( 'Joining Date', 'wptopschool' );?></th>
               </tr>
             </tfoot>
           </table>
      </div>
    </div>
    <?php

    wpts_body_end();
    wpts_footer();

  }else{
    include_once( WPTS_PLUGIN_PATH .'/includes/wpsp-login.php');
  }
?>
