<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
/**
 * Admin Class
 *
 * Handles generic Admin functionality.
 *
 * @package WPTopSchool
 * @since 1.0.0
 */
class Wpts_Admin
{
	public
	function __construct()
	{
	}
	/*
	* Add menu for manage license code
	* @package WPTopSchool
	* @since 1.0.0
	*/
function wpts_admin_menu()
	{
		global $current_user;
		$current_user_role=$current_user->roles[0];
		$proversion1    =    wpts_check_pro_version('wpts_addon_version');
  		$prodisable1    =    !$proversion1['status'] ? 'notinstalled'    : 'installed';
  		 $propayment    =    wpts_check_pro_version('pay_WooCommerce');
   		$propayment    =    !$propayment['status'] ? 'notinstalled'    : 'installed';

		$promessage2    =    wpts_check_pro_version('wpts_message_version');
    	$prodisablemessage2    =    !$promessage2['status'] ? 'notinstalled'    : 'installed';

		add_menu_page(__('WPTopSchool', 'wptopschool') , __('WPTopSchool', 'wptopschool') , 'manage_options', 'wptopschool', array(
			$this,
			'wpts_admin_details'
		) , WPTS_PLUGIN_URL . 'img/favicon.png');
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-dashboard icon"></i>&nbsp; Dashboard', 'edit_posts', 'sch-dashboard', array(
			$this,
			'wpts_callback_dashboard'
		));

			add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-inbox"></i>&nbsp; Messages', 'edit_posts', 'sch-messages', array(
				$this,
				'wpts_callback_messages'
			));

		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-id icon"></i>&nbsp; Students', 'edit_posts', 'sch-student', array(
			$this,
			'wpts_callback_students'
		));
		if($proversion1['status']){
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-welcome-write-blog icon"></i>&nbsp; Registration Request', 'edit_posts', 'sch-request', array(
			$this,
			'wpts_callback_request'
		));
		}
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-groups icon"></i>&nbsp; Teachers', 'edit_posts', 'sch-teacher', array(
			$this,
			'wpts_callback_teachers'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-businessman icon"></i>&nbsp; Parents', 'edit_posts', 'sch-parent', array(
			$this,
			'wpts_callback_parents'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-welcome-widgets-menus icon"></i>&nbsp; Classes', 'edit_posts', 'sch-class', array(
			$this,
			'wpts_callback_classes'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-clipboard icon"></i>&nbsp; Attendance', 'edit_posts', 'sch-attendance', array(
			$this,
			'wpts_callback_attendance'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-book"></i>&nbsp; Subjects', 'edit_posts', 'sch-subject', array(
			$this,
			'wpts_callback_subject'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-edit"></i>&nbsp; Exams', 'edit_posts', 'sch-exams', array(
			$this,
			'wpts_callback_exams'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-check-square-o"></i>&nbsp; Marks', 'edit_posts', 'sch-marks', array(
			$this,
			'wpts_callback_marks'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="dashicons dashicons-calendar-alt"></i>&nbsp; Events', 'edit_posts', 'sch-events', array(
			$this,
			'wpts_callback_events'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-clock-o"></i>&nbsp; Time Table', 'edit_posts', 'sch-timetable', array(
			$this,
			'wpts_callback_timetable'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-upload"></i>&nbsp; Import History', 'edit_posts', 'sch-importhistory', array(
			$this,
			'wpts_callback_importhistory'
		));
		if( $current_user_role=='administrator' || $current_user_role=='teacher')
		{
			add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-bullhorn"></i>&nbsp; Notify', 'edit_posts', 'sch-notify', array(
				$this,
				'wpts_callback_notify'
			));
		}
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-road"></i>&nbsp; Transport', 'edit_posts', 'sch-transport', array(
			$this,
			'wpts_callback_transport'
		));
		if($current_user_role=='administrator' || $current_user_role=='teacher'){
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-signal"></i>&nbsp; Teacher Attendance', 'edit_posts', 'sch-teacherattendance', array(
			$this,
			'wpts_callback_teacherattendance'
		));
	}
	if($current_user_role=='administrator' || $current_user_role=='teacher'){
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-cog"></i>&nbsp; General Settings', 'edit_posts', 'sch-settings', array(
			$this,
			'wpts_callback_settings'
		));
	}
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-check-square-o"></i>&nbsp; Subject Mark Fields', 'edit_posts', 'sch-settings&sc=subField', array(
			$this,
			'wpts_callback_subfield'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-clock-o"></i>&nbsp; Working Hours', 'edit_posts', 'sch-settings&sc=WrkHours', array(
			$this,
			'wpts_callback_wrkhours'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-key fa-fw"></i>&nbsp; Change Password', 'edit_posts', 'sch-changepassword', array(
			$this,
			'wpts_callback_changepassword'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-strikethrough"></i>&nbsp; Leave Calendar', 'edit_posts', 'sch-leavecalendar', array(
			$this,
			'wpts_callback_leavecalendar'
		));
		add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-key fa-fw"></i>&nbsp; Edit Profile', 'edit_posts', 'sch-editprofile', array(
			$this,
			'wpts_callback_editprofile'
		));
		add_submenu_page('wptopschool', 'wptopschool',  '<i class="fa fa-key fa-fw"></i>&nbsp; History', 'edit_posts', 'sch-history', array(
			$this,
			'wpts_callback_schhistory'
		));
		if($propayment == "installed"){
			add_submenu_page('wptopschool', 'wptopschool', '<i class="fa fa-strikethrough"></i>&nbsp; Payment', 'edit_posts', 'sch-payment', array(
			$this,
			'wpts_callback_payment'
			));
		}
		add_submenu_page( 'wptopschool', 'wptopschool', '<i class="dashicons dashicons-welcome-add-page"></i>&nbsp; Addons',
    'manage_options', 'addons',
		array(
			$this,
			'wpts_addons'
		));
	}
	/*
	* Call html of purchase code validation and contact
	* @package WPTopSchool
	* @since 1.0.0
	*/
	function wpts_admin_details()
	{
		require_once (WPTS_PLUGIN_PATH . 'lib/wpts-admin-options.php');
	}
	function wpts_addons()
	{
		require_once (WPTS_PLUGIN_PATH . 'lib/wpts-admin-addons.php');
	}
	function wpts_callback_dashboard()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-dashboard.php');
	}
	function wpts_callback_payment()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-payment.php');
	}
	function wpts_callback_editprofile()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-editprofile.php');
	}
	function wpts_callback_students()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-student.php');
	}
	function wpts_callback_request()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-registration-request.php');
	}
	function wpts_callback_teachers()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-teacher.php');
	}
	function wpts_callback_messages()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-messages.php');
	}
	function wpts_callback_parents()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-parent.php');
	}
	function wpts_callback_classes()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-class.php');
	}
	function wpts_callback_attendance()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-attendance.php');
	}
	function wpts_callback_subject()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-subject.php');
	}
	function wpts_callback_marks()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-marks.php');
	}
	function wpts_callback_exams()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-exams.php');
	}
	function wpts_callback_events()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-events.php');
	}
	function wpts_callback_timetable()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-timetable.php');
	}
	function wpts_callback_importhistory()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-importhistory.php');
	}
	function wpts_callback_notify()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-notify.php');
	}
	function wpts_callback_transport()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-transport.php');
	}
	function wpts_callback_teacherattendance()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-teacher-attendance.php');
	}
	function wpts_callback_settings()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-settings.php');
	}
	function wpts_callback_changepassword()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-changepassword.php');
	}
	function wpts_callback_leavecalendar()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-leavecalendar.php');
	}
	function wpts_callback_schhistory()
	{
		require_once (WPTS_PLUGIN_PATH . 'pages/wpts-history.php');
	}
	/*
	* Add required css and js for purchase code validation page
	* @package WPTopSchool
	* @since 1.0.0
	*/
	function wpts_add_admin_scripts($hook)
	{

		$prohistory    =    wpts_check_pro_version('wpts_mc_version');
    	$prodisablehistory    =    !$prohistory['status'] ? 'notinstalled'    : 'installed';

		wp_register_style('wpts_wp_admin_font_awesome', WPTS_PLUGIN_URL . 'css/font-awesome.min.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_font_awesome');
		wp_register_style('wpts_wp_admin_ionicons', WPTS_PLUGIN_URL . 'css/ionicons.min.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_ionicons');
		wp_register_style('wpts_wp_admin_wpts-grid', WPTS_PLUGIN_URL . 'css/wpts-grid.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-grid');
		// wp_register_style('wpts_wp_admin_pnotify', WPTS_PLUGIN_URL . 'css/pnotify.min.css', false, '1.0.0');
		// wp_enqueue_style('wpts_wp_admin_pnotify');
		if (is_user_logged_in())
		{
			wp_register_style('wpts_wp_admin_dataTablesresp', WPTS_PLUGIN_URL . 'css/datepicker.min.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_dataTablesresp');
			wp_register_style('wpts_wp_admin_dataTablesbootresp2', WPTS_PLUGIN_URL . 'plugins/datatables/responsive.bootstrap.min.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_dataTablesbootresp2');
		}
		if ($hook == 'wptopschool_page_sch-student')
		{
			// wp_register_style('wpts_wp_admin_blueimp-gallery', WPTS_PLUGIN_URL . 'plugins/gallery/blueimp-gallery.min.css', false, '1.0.0');
			// wp_enqueue_style('wpts_wp_admin_blueimp-gallery');
		}
		if ($hook == 'wptopschool_page_sch-dashboard')
		{
			wp_register_style('wpts_wp_admin_fullcalendar', WPTS_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_fullcalendar');
		}
		if ($hook == 'wptopschool_page_sch-events')
		{
			wp_register_style('wpts_wp_admin_fullcalendar', WPTS_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_fullcalendar');
			wp_register_style('wpts_wp_admin_timepicker', WPTS_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_timepicker');
		}

		if ($hook == 'wptopschool_page_sch-settings' || $hook == 'wptopschool_page_sch-teacher')
		{
			wp_register_style('wpts_wp_admin_boottimepicker', WPTS_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.css', false, '1.0.0');
			wp_enqueue_style('wpts_wp_admin_boottimepicker');
		}
		wp_register_style('wpts_wp_admin_wpts-icons', WPTS_PLUGIN_URL . 'css/wpts-icons.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-icons');
		wp_register_style('wpts_wp_admin_wpts-widget', WPTS_PLUGIN_URL . 'css/wpts-widget.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-widget');
		wp_register_style('wpts_wp_admin_wpts-style', WPTS_PLUGIN_URL . 'css/wpts-style.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-style');
		wp_register_style('wpts_wp_admin_wpts-style-resposive', WPTS_PLUGIN_URL . 'css/wpts-resposive.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-style-resposive');
		wp_register_style('wpts_wp_admin_wpts-style-select', WPTS_PLUGIN_URL . 'css/bootstrap-select.min.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-style-select');
		wp_register_style('wpts_wp_admin_wpts-style-multiselect', WPTS_PLUGIN_URL . 'css/bootstrap-multiselect.css', false, '1.0.0');
		wp_enqueue_style('wpts_wp_admin_wpts-style-multiselect');
		// wp_enqueue_script('wpts_wp_admin_jquery302', WPTS_PLUGIN_URL . 'js/bootstrap-select.min.js', array(
		// 	'jquery'
		// ) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery303', WPTS_PLUGIN_URL . 'js/bootstrap-multiselect.js', array(
			'jquery'
		) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery1', WPTS_PLUGIN_URL . 'plugins/otherjs/wpts.validate.min.js', array(
			'jquery'
		) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery90', WPTS_PLUGIN_URL . 'js/wpts-wp-admin.js', array(
				'jquery'
		) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery2', WPTS_PLUGIN_URL . 'js/lib/bootstrap.min.js', array(
			'jquery'
		) , '1.0.0', true);
		// wp_enqueue_script('wpts_wp_admin_jquery3', WPTS_PLUGIN_URL . 'plugins/fastclick/fastclick.min.js', array(
		// 	'jquery'
		// ) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery4', WPTS_PLUGIN_URL . 'js/lib/app.js', array(
			'jquery'
		) , '1.0.0', true);
		// wp_enqueue_script('wpts_wp_admin_jquery5', WPTS_PLUGIN_URL . 'js/lib/pnotify.min.js', array(
		// 	'jquery'
		// ) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery6', WPTS_PLUGIN_URL . 'plugins/slimScroll/jquery.slimscroll.min.js', array(
			'jquery'
		) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery7', WPTS_PLUGIN_URL . 'js/bootstrap-datepicker.min.js', array(
			'jquery'
		) , '1.0.0', true);
		wp_enqueue_script('wpts_wp_admin_jquery100', WPTS_PLUGIN_URL . 'js/wpts-settingtab.js', array(
				'jquery'
			) , '1.0.0', true);
		if ($hook == 'toplevel_page_WPTopSchool'){
			wp_enqueue_script('wpts_wp_admin_jquery90', WPTS_PLUGIN_URL . 'js/wpts-wp-admin.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if (is_user_logged_in())
		{
			wp_enqueue_script('wpts_wp_admin_jquery8', WPTS_PLUGIN_URL . 'plugins/datatables/jquery.datatables.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery999', WPTS_PLUGIN_URL . 'js/wpts-custome.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if($hook == 'wptopschool_page_sch-request')
				{
				wp_enqueue_script('wpts_wp_admin_jquery1011', WPTS_PLUGIN_URL . 'js/wpts-sch-request.js', array(
				'jquery'
			) , '1.0.0', true);
			}
		if ($hook == 'wptopschool_page_sch-dashboard')
		{
			wp_enqueue_script('wpts_wp_admin_jquery10', WPTS_PLUGIN_URL . 'plugins/fullcalendar/moment.min.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery11', WPTS_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery12', WPTS_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery13', WPTS_PLUGIN_URL . 'js/wpts-dashboard.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-student' )
		{
			// wp_enqueue_script('wpts_wp_admin_jquery15', WPTS_PLUGIN_URL . 'plugins/fileupload/jquery.iframe-transport.js', array(
			// 	'jquery'
			// ) , '1.0.0', true);
			// wp_enqueue_script('wpts_wp_admin_jquery16', WPTS_PLUGIN_URL . 'plugins/gallery/jquery.blueimp-gallery.min.js', array(
			// 	'jquery'
			// ) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery171', WPTS_PLUGIN_URL . 'js/wpts-student.js', array(
				'jquery'
			) , '1.0.0', true);

			if($prodisablehistory == 'installed'){
			 	wp_enqueue_script('wpts_wp_admin_jquery007', WPTS_PLUGIN_URL . 'js/wpts-custom-multicls.js', array(
			 	'jquery'
			 ) , '1.0.0', true);


			}
		}
		if ($hook == 'wptopschool_page_sch-editprofile' )
		{
			wp_enqueue_script('wpts_wp_admin_jquery17', WPTS_PLUGIN_URL . 'js/wpts-editprofile.js', array(
				'jquery'
			) ,'1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-teacher')
		{
			wp_enqueue_script('wpts_wp_admin_jquery18', WPTS_PLUGIN_URL . 'js/wpts-teacher.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-parent')
		{
			wp_enqueue_script('wpts_wp_admin_jquery19', WPTS_PLUGIN_URL . 'js/wpts-parent.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-class')
		{
			// wp_deregister_script('jquery-ui-datepicker');
			wp_enqueue_script('wpts_wp_admin_jquery20', WPTS_PLUGIN_URL . 'js/wpts-class.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-attendance')
		{
			wp_enqueue_script('wpts_wp_admin_jquery21', WPTS_PLUGIN_URL . 'js/wpts-attendance.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-subject')
		{
			wp_enqueue_script('wpts_wp_admin_jquery22', WPTS_PLUGIN_URL . 'js/wpts-subject.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-exams')
		{
			wp_enqueue_script('wpts_wp_admin_jquery23', WPTS_PLUGIN_URL . 'js/wpts-exam.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-marks')
		{
			wp_enqueue_script('wpts_wp_admin_jquery24', WPTS_PLUGIN_URL . 'js/wpts-mark.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-timetable')
		{
			wp_deregister_script( 'jquery-ui-core' );
			wp_enqueue_script('wpts_wp_admin_jquery25', WPTS_PLUGIN_URL . 'plugins/otherui/wpts_easyui.min.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery201', WPTS_PLUGIN_URL . 'js/lib/jquery.draggable.js', array(
			'jquery'
		) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery26', WPTS_PLUGIN_URL . 'js/wpts_timetable.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-settings')
		{
			wp_enqueue_script('wpts_wp_admin_jquery27', WPTS_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery28', WPTS_PLUGIN_URL . 'js/wpts-settings.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-importhistory')
		{
			wp_enqueue_script('wpts_wp_admin_jquery29', WPTS_PLUGIN_URL . 'js/wpts-importhistory.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-transport')
		{
			wp_enqueue_script('wpts_wp_admin_jquery30', WPTS_PLUGIN_URL . 'js/wpts-transport.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-teacherattendance')
		{
			wp_enqueue_script('wpts_wp_admin_jquery31', WPTS_PLUGIN_URL . 'js/wpts-teacherattendance.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-notify')
		{
			wp_enqueue_script('wpts_wp_admin_jquery32', WPTS_PLUGIN_URL . 'js/wpts-notify.js', array(
				'jquery'
			) , '1.0.0', true);

		}
		if ($hook == 'wptopschool_page_sch-events')
		{
			wp_enqueue_script('wpts_wp_admin_jquery33', WPTS_PLUGIN_URL . 'plugins/fullcalendar/moment.min.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery34', WPTS_PLUGIN_URL . 'plugins/fullcalendar/fullcalendar.min.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery35', WPTS_PLUGIN_URL . 'plugins/timepicker/bootstrap-timepicker.js', array(
				'jquery'
			) , '1.0.0', true);
			wp_enqueue_script('wpts_wp_admin_jquery36', WPTS_PLUGIN_URL . 'js/wpts-events.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-notify')
		{
			wp_enqueue_script('wpts_wp_admin_jquery37', WPTS_PLUGIN_URL . 'js/wpts-leavecalendar.js', array(
				'jquery'
			) , '1.0.0', true);

		}
		if ($hook == 'wptopschool_page_sch-messages' || $hook == 'wptopschool_page_sch-parent')
		{
			 wp_register_style('wpts_wp_admin_multiselect', WPTS_PLUGIN_URL . 'plugins/multiselect/jquery.multiselect.css', false, '1.0.0');
			 wp_enqueue_style('wpts_wp_admin_multiselect');
			wp_enqueue_script('wpts_wp_admin_jquery39', WPTS_PLUGIN_URL . 'js/wpts-messages.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-changepassword')
		{
			wp_enqueue_script('wpts_wp_admin_jquery40', WPTS_PLUGIN_URL . 'js/wpts-changepassword.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-payment')
		{
			wp_enqueue_script('wpts_wp_admin_jquery41', WPTS_PLUGIN_URL . 'plugins_url("plugins/multiselect/jquery.multiselect.js', array(
				'jquery'
			) , '1.0.0', true);
		}
		if ($hook == 'wptopschool_page_sch-leavecalendar')
		{
			wp_enqueue_script('wpts_wp_admin_jquery42', WPTS_PLUGIN_URL . 'js/wpts-leavecalendar.js', array(
				'jquery'
			) , '1.0.0', true);
		}
	}
	/*
	* Add pages in menu default
	* @package WPTopSchool
	* @since 1.0.0
	*/
	function wpts_add_adminbar()
	{
		global $wp_admin_bar;
		$wpts_wpschooldashboard_url = site_url() . '/wp-admin/admin.php?page=sch-dashboard';
		$wpts_wpschoolstudent_url = site_url() . '/wp-admin/admin.php?page=sch-student';
		$wpts_wpschoolteacher_url = site_url() . '/wp-admin/admin.php?page=sch-teacher';
		$wpts_wpschoolclass_url = site_url() . '/wp-admin/admin.php?page=sch-class';
		$wpts_wpschoolparent_url = site_url() . '/wp-admin/admin.php?page=sch-parent';
		$wp_admin_bar->add_menu(array(
			'parent' => false,
			'id' => 'dashboard',
			'title' => _('WPTopSchool Dashboard') ,
			'href' => $wpts_wpschooldashboard_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'teacher',
			'title' => _('Teacher') ,
			'href' => $wpts_wpschoolteacher_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'student',
			'title' => _('Student') ,
			'href' => $wpts_wpschoolstudent_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'class',
			'title' => _('Class') ,
			'href' => $wpts_wpschoolclass_url
		));
		$wp_admin_bar->add_menu(array(
			'parent' => 'dashboard',
			'id' => 'parent',
			'title' => _('Parent') ,
			'href' => $wpts_wpschoolparent_url
		));
	}
	function add_hooks()
	{
		function wpts_custom_loginlogo()
		{
            global $current_user, $wpdb, $wpts_settings_data,$post,$current_user_name;
            $url = plugin_dir_url( __FILE__ ).'img/wptopschoollogo.jpg';
            $imglogo  = isset( $wpts_settings_data['sch_logo'] ) ? sanitize_text_field($wpts_settings_data['sch_logo']) : $url;
            echo '<style type="text/css">
                        .login h1 a {background-image: url(' . $imglogo . ') !important; }
				  </style>';
		}
		// Add menu page for purchase code validation
		add_action('admin_menu', array(
			$this,
			'wpts_admin_menu'
		));
		add_action('login_enqueue_scripts', 'wpts_custom_loginlogo');
		// Add css and js
		add_action('admin_enqueue_scripts', array(
			$this,
			'wpts_add_admin_scripts'
		));
		// Add pages into admin menu
		add_action('wp_before_admin_bar_render', array(
			$this,
			'wpts_add_adminbar'
		));
	}
}
