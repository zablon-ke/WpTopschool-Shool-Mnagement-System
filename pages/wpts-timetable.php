<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');

wpts_header();
if (is_user_logged_in()) {
	global $current_user, $wp_roles, $wpdb;
		$current_usr_rle=$current_user->roles[0];
    if( $current_usr_rle == 'administrator' || $current_usr_rle == 'teacher' ) {
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
		$wpts_teacher_table =	$wpdb->prefix . 'wpts_teacher';
		$class_table		=	$wpdb->prefix."wpts_class";
		$classQuery			=	"select cid,c_name from $class_table Order By cid ASC";
		$msg				=	'Please Add Class Before Adding Subjects';
		if( $current_usr_rle=='teacher' ) {
			$cuserId		=	intval($current_user->ID);
			$classQuery		=	"select cid,c_name from $class_table where teacher_id='".esc_sql($cuserId)."'";
			$msg			=	'Please Ask Principal To Assign Class';
		}
		$sel_class		=	$wpdb->get_results( $classQuery );

		$sel_classid	=	isset( $_POST['ClassID'] ) ? intval($_POST['ClassID']) : 'all';
		$wpts_class_name	=	isset( $_POST['wpts_class_name'] ) ? sanitize_text_field($_POST['wpts_class_name']) : '';
		$sel_classname	=	$ctablename = '';
		foreach( $sel_class as $key=>$value ) {
			if( $value->cid	==	$sel_classid ) {
				$sel_classname	=	$value->c_name;
			}
			if( $wpts_class_name	== $value->cid ){
				$ctablename	= ' For Class '.$value->c_name;
			}
		}
        ?>

		<?php
		if( isset($_GET['ac']) && sanitize_text_field($_GET['ac'])=='add' && !empty( $sel_classid ) ) { ?>
				<div class="wpts-row">
					<div class="wpts-col-md-12">
						<?php include_once( WPTS_PLUGIN_PATH.'/includes/wpts-createTimetable.php'); ?>
					</div>
				</div>
		<?php } else if( isset( $_GET['timetable'] )  && intval($_GET['timetable']) > 0 ) {
				include_once( WPTS_PLUGIN_PATH.'/includes/wpts-editTimetable.php');
		} else { ?>
		<?php if( !empty( $sel_classid ) ) {
			include_once( WPTS_PLUGIN_PATH .'/includes/wpts-viewTimetable.php');
			$response	=	wpts_ViewTimetable($sel_classid);
		?>
		<div class="wpts-card">
        <div class="wpts-card-body">
		<form name="TimetableClass" id="TimetableClass" method="post" action="" class="wpts-form-horizontal class-filter">
			<div class="wpts-row">
				<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-9">
					<div class="wpts-form-group">
						<label class="wpts-label"><?php echo esc_html("Select Class Name","wptopschool");?></label>
						<select name="ClassID" id="ClassID" class="wpts-form-control">
								<option value="0" <?php if($sel_classid == 'all') echo esc_html("selected","wptopschool"); ?>><?php echo "Select";?></option>
							<?php foreach($sel_class as $classes) {	?>
							<option value="<?php echo esc_attr(intval($classes->cid));?>" <?php if($sel_classid==$classes->cid) echo esc_html("selected","wptopschool"); ?>><?php echo esc_html($classes->c_name);?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<?php if( $current_usr_rle == 'administrator'){?>
				<div class="wpts-col-lg-3 wpts-col-md-4 wpts-col-sm-4 wpts-col-xs-3s">
					<div class="wpts-form-group">
						<?php if( isset( $response['status'] ) && $response['status']==2 ) { ?>
								<a href="?page=sch-timetable&timetable=<?php echo esc_attr($sel_classid); ?>" title="Delete" data-id="<?php echo esc_attr($sel_classid); ?>" class="gap-all wp-edit-timetable wpts-timetable-btn">
									<i class="icon wpts-edit wpts-edit-icon icn-gap"></i>
								</a>
								<a href="javascript:void(0);" title="Delete" data-id="<?php echo esc_attr($sel_classid); ?>" class="wp-delete-timetable wpts-timetable-btn">
									<i class="icon wpts-trash wpts-delete-icon  ClassDeleteBt icn-gap " data-id="<?php echo esc_attr($sel_classid); ?>"></i>
								</a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
			</div>
		</form>
		<?php
		//echo $response['msg'];
		if($sel_classid == 'all'){} else {echo isset( $response['msg'] ) ? $response['msg'] :'';
	}
		} else {
			echo '<div class="wpts-text-red col-lg-2">'.esc_html($msg).'</div>';
		}?>
			<?php
		}
		wpts_body_end();
		wpts_footer();
	} elseif ($current_usr_rle == 'student') {
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
			include_once(  WPTS_PLUGIN_PATH.'/includes/wpts-viewTimetable.php');
			$wpts_student_table = $wpdb->prefix . "wpts_student";

			$class_id = base64_decode(sanitize_text_field($_GET['cid']));

			$result = wpts_ViewTimetable($class_id);
			echo wpts_kses_filter_allowed_html($result['msg']);?>
		 <div class="wpts-wrapper">
                <div class="wpts-container">
            <?php wpts_body_end();
		wpts_footer();?>
		</div></div><?php
	} else if ($current_usr_rle == 'parent') {
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
			include_once( WPTS_PLUGIN_PATH.'/includes/wpts-viewTimetable.php');
			$wpts_student_table = $wpdb->prefix . "wpts_student";
			$child_info = $wpdb->get_results("select * from $wpts_student_table where parent_wp_usr_id='".esc_sql($current_user->ID)."'");
			?>
			<!-- <section class="wpts-container"> -->
				<div class="wpts-row">
					<div class="wpts-col-md-12">
						<div class="wpts-card">
							<div class="wpts-card-body">
								<div class="tabbable-line">
									<div class="tabSec wpts-nav-tabs-custom" id="verticalTab">
										<div class="tabList">
										<ul class="wpts-resp-tabs-list">
										<?php
										$i=0;
										foreach ($child_info as $child_inf) {
											if(base64_decode(sanitize_text_field($_GET['sid'])) == $child_inf->sid){
										?>
										<li class="wpts-tabing <?php echo ($i==0)?'active':''?>">
										<?php echo esc_html($child_inf->s_fname.' '. $child_inf->s_mname.' '. $child_inf->s_lname); ?>
										</li>
										<?php } $i++; } ?>

										</ul>
										</div>
									<div class="wpts-tabBody wpts-resp-tabs-container">
										<?php
										$i=0;
										foreach ($child_info as $child_inf) {
										?>
											<div class="tab-pane wpts-tabMain <?php echo ($i==0)?'active':''?>" id="<?php echo 'child-'.esc_attr($i); ?>">
												<?php
												if ($child_inf->class_id != '') {
														//$class_id = $child_inf->class_id;
														$class_id = base64_decode(sanitize_text_field($_GET['cid']));
														$result = wpts_ViewTimetable($class_id);
														echo wpts_kses_filter_allowed_html($result['msg']);
												} else {
													echo "<div class='wpts-col-md-12'><div class='wpts-text-red'>Class missing..</div></div>";
												}  ?>
											</div>
										<?php $i++; } ?>
									</div>
								</div>

		<?php
		wpts_body_end();
		wpts_footer();
		}
} else{
	include_once( WPTS_PLUGIN_PATH.'/includes/wpts-login.php');
}?>
