<?php
/*
Plugin Name: 	WPTopSchool
Plugin URI: 	http://wptopschool.com
Description:    WPTopSchool is a school management system plugin that makes school activities transparent to parents It is a product made during internship. For more information please visit our website.
Version: 		1.0.0
Author: 		WPTopSchool Team
Author URI: 	wptopschool.com
Text Domain:	wptopschool
Domain Path:    languages
@package WPTopSchool
*/
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
/**
 * Basic plugin definitions
 *
 * @package WPTopSchool
 * @since 1.0.0
 */
if (!defined('WPTS_PLUGIN_URL'))
{
	define('WPTS_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('WPTS_PLUGIN_PATH'))
{
	define('WPTS_PLUGIN_PATH', plugin_dir_path(__FILE__));
}
if (!defined('WPTS_PLUGIN_VERSION'))
{
	define('WPTS_PLUGIN_VERSION', '1.0.0'); //Plugin version number
}
define('WPTS_PERMISSION_MSG', 'You don\'t have enough permission to access this page');
// Call the  required files when plugin activate
register_activation_hook(__FILE__, 'wpts_activation');
function wpts_activation()
{
	include_once (WPTS_PLUGIN_PATH . 'lib/wpts-activation.php');
}
register_deactivation_hook( __FILE__, 'wpts_deactivation');
function wpts_deactivation()
{
	include_once (WPTS_PLUGIN_PATH . 'lib/wpts-deactivation.php');
}
// add action to load plugin
add_action('plugins_loaded', 'wpts_plugins_loaded');

function wpts_plugins_loaded()
{
	$wpts_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
	load_plugin_textdomain('wptopschool', false, $wpts_lang_dir);
	// initialize settings of plugin Open required files for initialization
	require_once (WPTS_PLUGIN_PATH . 'lib/wpts-ajaxworks.php');
	require_once (WPTS_PLUGIN_PATH . 'lib/wpts-ajaxworks-student.php');
	require_once (WPTS_PLUGIN_PATH . 'lib/wpts-ajaxworks-teacher.php');
	require_once (WPTS_PLUGIN_PATH . 'wpts-layout.php');
	require_once (WPTS_PLUGIN_PATH . 'includes/wpts-misc.php');
	wpts_get_setting();

	global $wpts_settings_data;
	global $wpts_admin, $wpts_public, $paytmClass, $paypalClass;
	// admin class handles most of functionalities of plugin
	include_once (WPTS_PLUGIN_PATH . 'wpts-class-admin.php');
	$wpts_admin = new wpts_Admin();
	$wpts_admin->add_hooks();
	// public class handles most of functionalities of plugin
	include_once (WPTS_PLUGIN_PATH . 'wpts-class-public.php');
	$wpts_public = new wpts_Public();
	$wpts_public->add_hooks();
}



add_action('admin_init', 'ajax_actions');
function ajax_actions()
{
	add_action('wp_ajax_listdashboardschedule', 'wpts_listdashboardschedule');
	add_action('wp_ajax_StudentProfile', 'wpts_StudentProfile');
	add_action('wp_ajax_AddStudent', 'wpts_AddStudent');
	add_action('wp_ajax_UpdateStudent', 'wpts_UpdateStudent');
	add_action('wp_ajax_StudentPublicProfile', 'wpts_StudentPublicProfile');
	add_action('wp_ajax_ParentPublicProfile', 'wpts_ParentPublicProfile');
	add_action('wp_ajax_TeacherPublicProfile', 'wpts_TeacherPublicProfile');
	add_action('wp_ajax_bulkDelete', 'wpts_BulkDelete');
	add_action('wp_ajax_undoImport', 'wpts_UndoImport');
	add_action('wp_ajax_AddTeacher', 'wpts_AddTeacher');
	add_action('wp_ajax_AddParent', 'wpts_AddParent');
	add_action('wp_ajax_AddClass', 'wpts_AddClass');
	add_action('wp_ajax_UpdateClass', 'wpts_UpdateClass');
	add_action('wp_ajax_GetClass', 'wpts_GetClass');
	add_action('wp_ajax_DeleteClass', 'wpts_DeleteClass');
	add_action('wp_ajax_Updateregisterdeactive', 'wpts_Updateregisterdeactive');
	add_action('wp_ajax_Updateregisteractive', 'wpts_Updateregisteractive');
	add_action('wp_ajax_bulkaproverequest', 'wpts_bulkaproverequest');
	add_action('wp_ajax_bulkdisaproverequest', 'wpts_bulkdisaproverequest');
	add_action('wp_ajax_AddExam', 'wpts_AddExam');
	add_action('wp_ajax_UpdateExam', 'wpts_UpdateExam');
	add_action('wp_ajax_ExamInfo', 'wpts_ExamInfo');
	add_action('wp_ajax_DeleteExam', 'wpts_DeleteExam');
	add_action('wp_ajax_getStudentsList', 'wpts_getStudentsList');
	add_action('wp_ajax_AttendanceEntry', 'wpts_AttendanceEntry');
	add_action('wp_ajax_deleteAttendance', 'wpts_DeleteAttendance');
	add_action('wp_ajax_getStudentsAttendanceList', 'wpts_getStudentsAttendanceList');
	add_action('wp_ajax_getAbsentees', 'wpts_GetAbsentees');
	add_action('wp_ajax_getAbsentDates', 'wpts_GetAbsentDates');
	add_action('wp_ajax_getAttReport', 'wpts_GetAttReport');
	add_action('wp_ajax_AddSubject', 'wpts_AddSubject');
	add_action('wp_ajax_SubjectInfo', 'wpts_SubjectInfo');
	add_action('wp_ajax_UpdateSubject', 'wpts_UpdateSubject');
	add_action('wp_ajax_DeleteSubject', 'wpts_DeleteSubject');
	add_action('wp_ajax_subjectList', 'wpts_SubjectList');
	add_action('wp_ajax_save_timetable', 'wpts_SaveTimetable');
	add_action('wp_ajax_deletsloat', 'wpts_DeleteTimetablesloat');
	add_action('wp_ajax_deletTimetable', 'wpts_DeleteTimetable');
	add_action('wp_ajax_addMark', 'wpts_AddMark');
	add_action('wp_ajax_getMarksubject', 'wpts_getMarksubject');

	add_action('wp_ajax_GenSetting', 'wpts_GenSetting');
	add_action('wp_ajax_GenSettingsms', 'wpts_GenSettingsms');
	add_action('wp_ajax_GenSettingsocial', 'wpts_GenSettingsocial');
	add_action('wp_ajax_GenSettinglicensing', 'wpts_GenSettinglicensing');
	add_action('wp_ajax_addSubField', 'wpts_AddSubField');
	add_action('wp_ajax_updateSubField', 'wpts_UpdateSubField');
	add_action('wp_ajax_deleteSubField', 'wpts_DeleteSubField');
	add_action('wp_ajax_manageGrade', 'wpts_ManageGrade');
	add_action('wp_ajax_addEvent', 'wpts_AddEvent');
	add_action('wp_ajax_updateEvent', 'wpts_UpdateEvent');
	add_action('wp_ajax_deleteEvent', 'wpts_DeleteEvent');
	add_action('wp_ajax_listEvent', 'wpts_ListEvent');
	add_action('wp_ajax_deleteAllLeaves', 'wpts_DeleteLeave');
	add_action('wp_ajax_addLeaveDay', 'wpts_AddLeaveDay');
	add_action('wp_ajax_getLeaveDays', 'wpts_GetLeaveDays');
	add_action('wp_ajax_getClassYear', 'wpts_GetClassYear');
	add_action('wp_ajax_addTransport', 'wpts_AddTransport');
	add_action('wp_ajax_updateTransport', 'wpts_UpdateTransport');
	add_action('wp_ajax_viewTransport', 'wpts_ViewTransport');
	add_action('wp_ajax_deleteTransport', 'wpts_DeleteTransport');
	add_action('wp_ajax_sendMessage', 'wpts_SendMessage');
	add_action('wp_ajax_sendSubMessage', 'wpts_sendSubMessage');
	add_action('wp_ajax_viewMessage', 'wpts_ViewMessage');
	add_action('wp_ajax_deleteMessage', 'wpts_DeleteMessage');
	add_action('wp_ajax_photoUpload', 'wpts_UploadPhoto');
	add_action('wp_ajax_deletePhoto', 'wpts_DeletePhoto');
	add_action('wp_ajax_DeleteStudent', 'wpts_DeleteStudent');
	add_action('wp_ajax_DeleteTeacher', 'wpts_DeleteTeacher');
	// Teacher modules
	add_action('wp_ajax_getTeachersList', 'wpts_getTeachersList');
	add_action('wp_ajax_TeacherAttendanceEntry', 'wpts_TeacherAttendanceEntry');
	add_action('wp_ajax_TeacherAttendanceDelete', 'wpts_TeacherAttendanceDelete');
	add_action('wp_ajax_TeacherAttendanceView', 'wpts_TeacherAttendanceView');
	add_action('wp_ajax_UpdateTeacher', 'wpts_UpdateTeacher');
	// Notification modules
	add_action('wp_ajax_deleteNotify', 'wpts_deleteNotify');
	add_action('wp_ajax_getNotify', 'wpts_getNotifyInfo');
	// Change Password
	add_action('wp_ajax_changepassword', 'wpts_changepassword');
	// Import Dummy data
	add_action('wp_ajax_ImportContents', 'wpts_Import_Dummy_contents');
}



// Get error content and update
function wpts_save_error()
{
	update_option('plugin_error', ob_get_contents());
}
add_action('activated_plugin', 'wpts_save_error');
//Show Link Plugin Page
function wpts_add_plugin_links($links)
{
	$plugin_links = array(
		'<a href="'.esc_url('admin.php?page=WPTopSchool').'"><strong style="color: #11967A; display: inline;">' . __('Settings', 'WPTopSchool-123') . '</strong></a>'
	);
	return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'wpts_add_plugin_links', 20);
// Change login page logo url
function wp_wp_login_url() {  return home_url(); }
add_filter( 'login_headerurl', 'wp_wp_login_url' );

    //
    function wpts_std_role(){
    $role = get_role( 'student' );
    $role->add_cap( 'edit_posts', true );
    }
    add_action( 'init', 'wpts_std_role', 11 );
?>
