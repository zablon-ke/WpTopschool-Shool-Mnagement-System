<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
/**
 * Public Class
 *
 * Handles generic Public functionality.
 *
 * @package WPTopSchool
 * @since 1.0.0
 */
class wpts_Public
{
    public function __construct()
    {
    }
    /*
    * Redirect user to dashboard
    * @package WPTopSchool
    * @since 1.0.0
    */
    function wpts_login_redirect($redirect_to, $request, $user)
    {
        global $user;
        if (isset($user->roles) && is_array($user->roles))
        {
            if (in_array('administrator', $user->roles)) //check for admins
            {
                return $redirect_to; // redirect them to the default place
            }
            else
            {
                return site_url('/wp-admin/admin.php?page=sch-dashboard');
            }
        }
        return $redirect_to;
    }
    /*
    * redirect to specific page
    * @package WPTopSchool
    * @since 1.0.0
    */
    function wpts_page_template($page_template)
    {
        if (is_page('sch-dashboard'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-dashboard.php';
        }
        if (is_page('sch-student'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-student.php';
        }
        if (is_page('sch-transport'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-transport.php';
        }
        if (is_page('sch-parent'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-parent.php';
        }
        if (is_page('sch-class'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-class.php';
        }
        if (is_page('sch-teacher'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-teacher.php';
        }
        if (is_page('sch-messages'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-messages.php';
        }
        if (is_page('sch-profile'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-profile.php';
        }
        if (is_page('sch-exams'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-exams.php';
        }
        if (is_page('sch-marks'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-marks.php';
        }
        if (is_page('sch-attendance'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-attendance.php';
        }
        if (is_page('sch-timetable'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-timetable.php';
        }
        if (is_page('sch-reminder'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-reminder.php';
        }
        if (is_page('sch-events'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-events.php';
        }
        if (is_page('sch-subject'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-subject.php';
        }
        if (is_page('sch-settings'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-settings.php';
        }
        if (is_page('sch-calendar'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-calendar.php';
        }
        if (is_page('sch-teacherattendance'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-teacher-attendance.php';
        }
        if (is_page('sch-profile'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-profile.php';
        }
        if (is_page('sch-notify'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-notify.php';
        }
        if (is_page('sch-payment'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-paymentdetail.php';
        }
        if (is_page('sch-importhistory'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-importhistory.php';
        }
        if (is_page('sch-leavecalendar'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-leavecalendar.php';
        }
        if (is_page('sch-changepassword'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-changepassword.php';
        }
        if (is_page('sch-editprofile'))
        {
            $page_template = dirname(__FILE__) . '/pages/wpts-editprofile.php';
        }
        return $page_template;
    }
    function add_hooks()
    {
        // redirect user to dashboard page
        add_filter('login_redirect', array(
            $this,
            'wpts_login_redirect'
        ) , 10, 3);
        // set page template as
        add_filter('page_template', array(
            $this,
            'wpts_page_template'
        ));
    }
}
