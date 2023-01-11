<?php

if (!defined('ABSPATH')) exit('No Such File');

/* This function is used for Add Teacher */

function wpts_AddTeacher()
{

	global $wpdb;
	if (!isset($_POST['tregister_nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['tregister_nonce']) , 'TeacherRegister'))
	{

		echo esc_html("Unauthorized Submission!!", "wptopschool");
		exit;

	}

	wpts_Authenticate();

	if (wpts_CheckUsername(sanitize_user($_POST['Username']) , true) === true)
	{

		echo esc_html("Given User Name Already Exists!!", "wptopschool");
		exit;

	}

	if (email_exists(sanitize_email($_POST['Email'])))
	{

		echo esc_html("Given Email ID Already registered!", "wptopschool");
		exit;

	}

	$wpts_teacher_table = $wpdb->prefix . "wpts_teacher";

	$firstname = sanitize_text_field($_POST['firstname']);

	$middlename = sanitize_text_field($_POST['middlename']);

	$lastname = sanitize_text_field($_POST['lastname']);

	$gender = sanitize_text_field($_POST['Gender']);

	$address = sanitize_text_field($_POST['Address']);

	$city = sanitize_text_field($_POST['city']);

	$phone = sanitize_text_field($_POST['Phone']);

	$qual = sanitize_text_field($_POST['Qual']);

	$position = sanitize_text_field($_POST['Position']);

	$bloodgroup = sanitize_text_field($_POST['Bloodgroup']);

	$empcode = sanitize_text_field($_POST['EmpCode']);

	$dob = !empty($_POST['Dob']) ? date('Y-m-d', strtotime(sanitize_text_field($_POST['Dob']))) : '';

	$doj = !empty($_POST['Doj']) ? date('Y-m-d', strtotime(sanitize_text_field($_POST['Doj']))) : '';

	$country = sanitize_text_field($_POST['country']);

	$zipcode = intval($_POST['zipcode']);

	if (!empty($empcode))

	{

		$result = $wpdb->get_row("SELECT *FROM $wpts_teacher_table WHERE empcode='$empcode'", ARRAY_A);

		if (count($result) > 0)

		{

			echo esc_html("You have already assign same Employee Code to another Employee", "wptopschool");

			exit;

		}

	}

	$userInfo = array(

		'user_login' => sanitize_user($_POST['Username']) ,

		'user_pass' => sanitize_text_field($_POST['Password']) ,

		'first_name' => sanitize_text_field($firstname) ,

		'user_email' => sanitize_email($_POST['Email']) ,

		'role' => 'teacher'

	);

	$user_id = wp_insert_user($userInfo);

	if (!is_wp_error($user_id))

	{

		// send registration mail

		$msg = 'Hello ' . esc_html($firstname);

		$msg.= '<br />Your are registered as teacher at <a href="' . esc_url(site_url()) . '">School</a><br /><br />';

		$msg.= 'Your Login details are below.<br />';

		$msg.= 'Your User Name is : ' . sanitize_user($_POST['Username']) . '<br />';

		$msg.= 'Your Password is : ' . sanitize_text_field($_POST['Password']) . '<br /><br />';

		$msg.= 'Please Login by clicking <a href="' . esc_url(site_url() . '/sch-dashboard').'">Here </a><br /><br />';

		$msg.= 'Thanks,<br />' . get_bloginfo('name');

		wpts_send_mail(sanitize_email($_POST['Email']) , 'User Registered', $msg);

		$teacher_data = array(

			'wp_usr_id' => $user_id,

			'first_name' => $firstname,

			'middle_name' => $middlename,

			'last_name' => $lastname,

			'address' => $address,

			'city' => $city,

			'country' => $country,

			'zipcode' => $zipcode,

			'empcode' => $empcode,

			'dob' => $dob,

			'doj' => $doj,

			'dol' => !empty($_POST['dol']) ? date('Y-m-d', strtotime(sanitize_text_field($_POST['dol']))) : '',

			'whours' => sanitize_text_field($_POST['whours']) ,

			'phone' => $phone,

			'qualification' => $qual,

			'gender' => $gender,

			'bloodgrp' => $bloodgroup,

			'position' => $position

		);

		$tch_ins = $wpdb->insert($wpts_teacher_table, $teacher_data);



		if ($tch_ins)

		{

			do_action('wpts_teacher_created', $user_id, $teacher_data);

		}

		if (!empty($_FILES['displaypicture']['name']))

		{

			if (!function_exists('wp_handle_upload')) require_once (ABSPATH . 'wp-admin/includes/file.php');

			$mimes = array(

				'jpg|jpeg|jpe' => 'image/jpeg',

				'png' => 'image/png'

			);

			$avatar = wp_handle_upload($_FILES['displaypicture'], array(

				'mimes' => $mimes,

				'test_form' => false

			));

			if (isset($avatar['error']))

			{

				$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>Please upload a valid image file for the avatar.</div></div>";

			}

			else

			if (empty($avatar['file']))

			{

				switch ($avatar['error'])

				{

				case 'File type does not meet security guidelines. Try another.':

					add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error",__("Please upload a valid image file for the avatar.","wpts_teacher_photo_edit"));'));

					$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>Please upload a valid image file for the avatar.</div></div>";

					break;

				default:

					add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error","<strong>".__("There was an error uploading the avatar:","wpts_teacher_photo_edit")."</strong> ' . esc_attr($avatar['error']) . '");'));

					$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>There was an error uploading the avatar</div></div>";

				}

				return;

			}

			else{

				update_user_meta($user_id, 'displaypicture', array(

					'full' => $avatar['url']

				));

				update_user_meta($user_id, 'simple_local_avatar', array(

					'full' => $avatar['url']

				));

			}

		}

		if ($tch_ins)

		{

			$msg = esc_html("success", "wptopschool");

		}

		else

		{

			$msg = esc_html("Oops! Something went wrong try again.", "wptopschool");

		}

	}

	else

	{

		if (is_wp_error($user_id))

		{

			$msg = $user_id->get_error_message();

		}

	}

	echo wp_kses_post($msg);

	wp_die();

}

/* This function is used for View Teacher Profile Information */

function wpts_TeacherPublicProfile()

{

	global $wpdb;

	$tid = sanitize_text_field($_POST['id']);

	$teacher_table = $wpdb->prefix . "wpts_teacher";

	$users_table = $wpdb->prefix . "users";

	$tinfo = $wpdb->get_row("select teacher.*,user.user_email from $teacher_table teacher LEFT JOIN $users_table user ON user.ID=teacher.wp_usr_id where teacher.wp_usr_id='".esc_sql($tid)."'");

	$loc_avatar = get_user_meta($tid, 'simple_local_avatar', true);

	$img_url = $loc_avatar ? sanitize_text_field($loc_avatar['full']) : WPTS_PLUGIN_URL . 'img/avatar.png';

	if (!empty($tinfo))

	{

		$profile = "<div class='wpts-panel-body'>

					<div class='wpts-userpic'>

						<img src='".esc_url($img_url)."' height='150px' width='150px' class='wpts-img-round'/>

					</div>

					<div class='wpts-userDetails'>

						<table class='wpts-table'>

							<tr>

								<td><strong>Full Name:</strong> ".esc_html($tinfo->first_name)." ".esc_html($tinfo->middle_name)." ".esc_html( $tinfo->last_name)."</td>

								<td><strong>Gender: </strong>".esc_html($tinfo->gender)."</td>

							</tr>

							<tr>

								<td><strong>Date of Birth: </strong>" . wpts_ViewDate(esc_html($tinfo->dob)) . "</td>

								<td><strong>Email: </strong> ".esc_html($tinfo->user_email)."</td>

							</tr>

							<tr>

								<td><strong>Phone Number: </strong> ".esc_html($tinfo->phone)."</td>

								<td><strong>Blood Group: </strong> ".esc_html($tinfo->bloodgrp)."</td>

							</tr>

							<tr>

								<td><strong>Position: </strong> ".esc_html($tinfo->position)."</td>

								<td><strong>Qualification: </strong> ".esc_html($tinfo->qualification)."</td>

							</tr>

							<tr>

								<td colspan='2'><strong>Address: </strong> ".esc_html($tinfo->address, $tinfo->city, $tinfo->country, $tinfo->zipcode)."</td>

							</tr>



							<tr>

								<td><strong>Joining Date: </strong> " . wpts_ViewDate(esc_html($tinfo->doj)) . "</td>

								<td><strong>Leaving Date: </strong> " . wpts_ViewDate(esc_html($tinfo->dol)) . "</td>

							</tr>

							<tr>

								<td><strong>Working Hours: </strong> ".esc_html($tinfo->whours)."</td>

								<td><strong>Employee Code : </strong> ".esc_html($tinfo->empcode)."</td>

							</tr>

						</table>

					</div>

					</div>

				</div>

			</div>

		</div>";

	}

	else

	{

		$profile = esc_html("No data retrived!..", "wptopschool");

	}

	echo apply_filters('wpts_teacher_profile', wp_kses_post($profile), intval($tid));

	wp_die();

}

/* This function is used for Update Teacher Information */

function wpts_UpdateTeacher()

{


	$user_id = intval($_POST['UserID']);

	global $wpdb;

	$wpts_teacher_table = $wpdb->prefix . "wpts_teacher";




	$errors = wpts_validation(array(

		sanitize_text_field($_POST['firstname']) => 'required',

		sanitize_text_field($_POST['Address']) => 'required',

		sanitize_text_field($_POST['lastname']) => 'required',

		sanitize_email($_POST['Email']) => 'required|email'

	));

	if (is_array($errors))

	{

		echo "<div class='col-md-12'><div class='alert alert-danger'>";

		foreach($errors as $error)

		{

			echo "<li>" . esc_html($error) . "</li>";

		}

		echo "</div></div>";

		return false;

	}

	$firstname = sanitize_text_field($_POST['firstname']);

	$middlename = sanitize_text_field($_POST['middlename']);

	$lastname = sanitize_text_field($_POST['lastname']);

	$email = sanitize_email($_POST['Email']);

	$gender = sanitize_text_field($_POST['Gender']);

	$address = sanitize_text_field($_POST['Address']);

	$phone = sanitize_text_field($_POST['Phone']);

	$qual = sanitize_text_field($_POST['Qual']);

	$bloodgroup = sanitize_text_field($_POST['Bloodgroup']);

	$country = sanitize_text_field($_POST['country']);

	$city = sanitize_text_field($_POST['city']);

	$zipcode = intval($_POST['zipcode']);

	$position = sanitize_text_field($_POST['Position']);

	$empcode = sanitize_text_field($_POST['Empcode']);

	$whours = sanitize_text_field($_POST['whours']);

	$dob = !empty($_POST['Dob']) ? wpts_StoreDate(sanitize_text_field($_POST['Dob'])) : '';

	$doj = !empty($_POST['Doj']) ? wpts_StoreDate(sanitize_text_field($_POST['Doj'])) : '';



	$teachereditprofile = sanitize_text_field($_POST['teachereditprofile']);



if ($teachereditprofile == 'teachereditprofile')
	{
		$teacher_data = array(

			'first_name' => $firstname,

			'middle_name' => $middlename,

			'last_name' => $lastname,

			'address' => $address,

			'country' => $country,

			'city' => $city,

			'zipcode' => $zipcode,

			'dob' => $dob,

			'phone' => $phone,

			'qualification' => $qual,

			'gender' => $gender,

			'bloodgrp' => $bloodgroup



		);

		$wpts_tch_upd = $wpdb->update($wpts_teacher_table, $teacher_data, array(

			'wp_usr_id' => $user_id

		));

	}

	else

	{

		$empcode = sanitize_text_field($_POST['Empcode']);

		$teacher_data = array(

			'first_name' => $firstname,

			'middle_name' => $middlename,

			'last_name' => $lastname,

			'address' => $address,

			'country' => $country,

			'city' => $city,

			'zipcode' => $zipcode,

			'empcode' => $empcode,

			'dob' => $dob,

			'doj' => $doj,

			'phone' => $phone,

			'qualification' => $qual,

			'gender' => $gender,

			'bloodgrp' => $bloodgroup,

			'position' => $position,

			'dol' => !empty($_POST['dol']) ? date('Y-m-d', strtotime(sanitize_text_field($_POST['dol']))) : '',

			'whours' => sanitize_text_field($_POST['whours'])

		);

		$wpts_tch_upd = $wpdb->update($wpts_teacher_table, $teacher_data, array(

			'wp_usr_id' => $user_id

		));

	}

	if ($email != '')
	{

		$tch_upd_email = wp_update_user(array(

			'ID' => $user_id,

			'user_email' => $email

		));

	}

	if ($wpts_tch_upd)
	{

		do_action('wpts_teacher_updated', intval($user_id) , $teacher_data);

	}
	if (!function_exists('wp_handle_upload'))
	{

		require_once (ABSPATH . 'wp-admin/includes/file.php');

	}

	if (!empty($_FILES['displaypicture']['name']))
	{

		$mimes = array(

			'jpg|jpeg|jpe' => 'image/jpeg',

			'png' => 'image/png'

		);

		$avatar = wp_handle_upload($_FILES['displaypicture'], array(

			'mimes' => $mimes,

			'test_form' => false

		));

		if (isset($avatar['error']))
		{

			$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>Please upload a valid image file for the avatar.</div></div>";
		}

		else if (empty($avatar['file']))
		{

			switch ($avatar['error'])
			{

			case 'File type does not meet security guidelines. Try another.':

				add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error",__("Please upload a valid image file for the avatar.","wpts_teacher_photo_edit"));'));

				$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>Please upload a valid image file for the avatar.</div></div>";

				break;

			default:

				add_action('user_profile_update_errors', create_function('$a', '$a->add("avatar_error","<strong>".__("There was an error uploading the avatar:","wpts_teacher_photo_edit")."</strong> ' . esc_attr($avatar['error']) . '");'));

				$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>There was an error uploading the avatar</div></div>";

			}
			return;
		}
		else
		{

			update_user_meta($user_id, 'displaypicture', array(

				'full' => $avatar['url']

			));

			update_user_meta($user_id, 'simple_local_avatar', array(

				'full' => $avatar['url']

			));

		}

	}

	if (!$wpts_tch_upd && is_wp_error($tch_upd_email))
	{

		$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-danger'>Oops! Something went wrong try again.</div></div>";

	}

	elseif (is_wp_error($tch_upd_email))
	{

		$msg = "<div class='col-md-12 col-lg-12'><div class='alert alert-warning'>" . $tch_upd_email->get_error_message() . "</div></div>";
	}

	if (is_wp_error($wpts_tch_upd))
	{

		$msg =  $stu_upd->get_error_message() ;

	}
	else
	{

		$msg = esc_html("success","wptopschool");

	}

	echo wp_kses_post($msg);

}

?>
