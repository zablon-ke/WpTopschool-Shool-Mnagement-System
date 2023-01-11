<?php


// Exit if accessed directly


if ( !defined( 'ABSPATH' ) ) exit;


/**


 * Initilize TopSchool


 *


 * Handle to initilize Settings of SchoolPress


 *


 * @package WPTopSchool


 * @since 1.0.0


 */


function wpts_get_setting() {


	global $wpts_settings_data, $wpdb;


	$wpts_settings_table	=	$wpdb->prefix."wpts_settings";


	$wpts_settings_edit		=	$wpdb->get_results("SELECT * FROM $wpts_settings_table" );


	foreach($wpts_settings_edit as $sdat) {


		$wpts_settings_data[$sdat->option_name]	=	$sdat->option_value;


	}


}


/*


* Send mail when new user register


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_send_user_register_mail( $userInfo = array(), $user_id ) {


	if( !empty( $user_id ) && $user_id > 0 ) {


		wp_new_user_notification( $user_id, '', 'user');


	}


}


/*


* Check current user is authorized or not


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_Authenticate() {


	global $current_user;


	if($current_user->roles[0]!='administrator' && $current_user->roles[0]!='teacher' ) {


		echo esc_html("Unauthorized Access!","wptopschool");


		exit;


	}


}


/*


* Check current user has update access or not


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_UpdateAccess($role,$id){


	global $current_user;


	$current_user_role=$current_user->roles[0];


	if( $current_user_role=='administrator' || ( $current_user_role==$role && $current_user->ID==$id ) || $current_user_role=='teacher'  ) {


		return true;


	} else {


		return false;


	}


}


/*


* Get role of current user


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_CurrentUserRole(){


	global $current_user;


	return isset( $current_user->roles[0] ) ? $current_user->roles[0] : '';


}


/*


* Get add as per given setting


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_ViewDate($date){





	global $wpdb, $wpts_settings_data;


	$date_format	=	isset( $wpts_settings_data['date_format'] ) ? $wpts_settings_data['date_format'] : '';


	$dformat		=	empty( $date_format ) ? 'm/d/Y' : $date_format;


	return ( !empty( $date ) && $date!='0000-00-00' ) ? date( $dformat,strtotime($date) ) : $date;


}


/*


* Store date as per given setting


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_StoreDate($date) {


	return ( !empty ( $date ) && $date!='0000-00-00' ) ? date('Y-m-d',strtotime($date)) : $date;


}


/*


* Check for username exists or not


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_CheckUsername($username='',$return=false){


	$username	=	empty( $username ) ? sanitize_user($_POST['username'] ) : $username ;


	if ( username_exists( $username ) ) {


        if ($return)


            return true;


        else{


            echo esc_html("true","wptopschool");


            wp_die();


        }


    } else {


        if ($return)


            return false;


        else {


            echo esc_html("false","wptopschool");


            wp_die();


        }


    }


}


/*


* Check for emailID exists or not


* @package WPTopSchool


* @since 1.0.0


*/


function wpts_CheckEmail(){

	$email=sanitize_email($_POST['email']);

	echo email_exists( $email ) ? esc_html("true","wptopschool") : esc_html("false","wptopschool");

	wp_die();

}

/*

* Create dynamic email id if not specified

* @package WPTopSchool

* @since 1.0.0

*/

function wpts_EmailGen($username){
	return $username."@wptopschool.com";
}


/* This function is used for send mail */


function wpts_send_mail( $to, $subject, $body, $attachment='' ) {

	global $wpts_settings_data;

	$email			=	$wpts_settings_data['sch_email'];

	$from			=	$wpts_settings_data['sch_name'];

	$admin_email	=	get_option( 'admin_email' );

	$email		=	!empty( $email ) ? wp_unslash($email) : wp_unslash($admin_email);

	$from		=	!empty( $from ) ? $from : get_option( 'blogname'  );

	$headers	=	 array();

	if( !empty( $email ) && !empty( $from ) ) {

		$headers[]	=	"From: $from <$email>";

		$headers[] 	=	'Content-Type: text/html; charset=UTF-8';

	}

	if( wp_mail( $to, $subject, $body, $headers, $attachment )) return true;

	else return false;

}


/* This function is return country list */

function wpts_county_list() {

	return array(

	'AF' => __( 'Afghanistan', 'wptopschool' ),


	'AX' => __( '&#197;land Islands', 'wptopschool' ),


	'AL' => __( 'Albania', 'wptopschool' ),


	'DZ' => __( 'Algeria', 'wptopschool' ),


	'AD' => __( 'Andorra', 'wptopschool' ),


	'AO' => __( 'Angola', 'wptopschool' ),


	'AI' => __( 'Anguilla', 'wptopschool' ),


	'AQ' => __( 'Antarctica', 'wptopschool' ),


	'AG' => __( 'Antigua and Barbuda', 'wptopschool' ),


	'AR' => __( 'Argentina', 'wptopschool' ),


	'AM' => __( 'Armenia', 'wptopschool' ),


	'AW' => __( 'Aruba', 'wptopschool' ),


	'AU' => __( 'Australia', 'wptopschool' ),


	'AT' => __( 'Austria', 'wptopschool' ),


	'AZ' => __( 'Azerbaijan', 'wptopschool' ),


	'BS' => __( 'Bahamas', 'wptopschool' ),


	'BH' => __( 'Bahrain', 'wptopschool' ),


	'BD' => __( 'Bangladesh', 'wptopschool' ),


	'BB' => __( 'Barbados', 'wptopschool' ),


	'BY' => __( 'Belarus', 'wptopschool' ),


	'BE' => __( 'Belgium', 'wptopschool' ),


	'PW' => __( 'Belau', 'wptopschool' ),


	'BZ' => __( 'Belize', 'wptopschool' ),


	'BJ' => __( 'Benin', 'wptopschool' ),


	'BM' => __( 'Bermuda', 'wptopschool' ),


	'BT' => __( 'Bhutan', 'wptopschool' ),


	'BO' => __( 'Bolivia', 'wptopschool' ),


	'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wptopschool' ),


	'BA' => __( 'Bosnia and Herzegovina', 'wptopschool' ),


	'BW' => __( 'Botswana', 'wptopschool' ),


	'BV' => __( 'Bouvet Island', 'wptopschool' ),


	'BR' => __( 'Brazil', 'wptopschool' ),


	'IO' => __( 'British Indian Ocean Territory', 'wptopschool' ),


	'VG' => __( 'British Virgin Islands', 'wptopschool' ),


	'BN' => __( 'Brunei', 'wptopschool' ),


	'BG' => __( 'Bulgaria', 'wptopschool' ),


	'BF' => __( 'Burkina Faso', 'wptopschool' ),


	'BI' => __( 'Burundi', 'wptopschool' ),


	'KH' => __( 'Cambodia', 'wptopschool' ),


	'CM' => __( 'Cameroon', 'wptopschool' ),


	'CA' => __( 'Canada', 'wptopschool' ),


	'CV' => __( 'Cape Verde', 'wptopschool' ),


	'KY' => __( 'Cayman Islands', 'wptopschool' ),


	'CF' => __( 'Central African Republic', 'wptopschool' ),


	'TD' => __( 'Chad', 'wptopschool' ),


	'CL' => __( 'Chile', 'wptopschool' ),


	'CN' => __( 'China', 'wptopschool' ),


	'CX' => __( 'Christmas Island', 'wptopschool' ),


	'CC' => __( 'Cocos (Keeling) Islands', 'wptopschool' ),


	'CO' => __( 'Colombia', 'wptopschool' ),


	'KM' => __( 'Comoros', 'wptopschool' ),


	'CG' => __( 'Congo (Brazzaville)', 'wptopschool' ),


	'CD' => __( 'Congo (Kinshasa)', 'wptopschool' ),


	'CK' => __( 'Cook Islands', 'wptopschool' ),


	'CR' => __( 'Costa Rica', 'wptopschool' ),


	'HR' => __( 'Croatia', 'wptopschool' ),


	'CU' => __( 'Cuba', 'wptopschool' ),


	'CW' => __( 'Cura&Ccedil;ao', 'wptopschool' ),


	'CY' => __( 'Cyprus', 'wptopschool' ),


	'CZ' => __( 'Czech Republic', 'wptopschool' ),


	'DK' => __( 'Denmark', 'wptopschool' ),


	'DJ' => __( 'Djibouti', 'wptopschool' ),


	'DM' => __( 'Dominica', 'wptopschool' ),


	'DO' => __( 'Dominican Republic', 'wptopschool' ),


	'EC' => __( 'Ecuador', 'wptopschool' ),


	'EG' => __( 'Egypt', 'wptopschool' ),


	'SV' => __( 'El Salvador', 'wptopschool' ),


	'GQ' => __( 'Equatorial Guinea', 'wptopschool' ),


	'ER' => __( 'Eritrea', 'wptopschool' ),


	'EE' => __( 'Estonia', 'wptopschool' ),


	'ET' => __( 'Ethiopia', 'wptopschool' ),


	'FK' => __( 'Falkland Islands', 'wptopschool' ),


	'FO' => __( 'Faroe Islands', 'wptopschool' ),


	'FJ' => __( 'Fiji', 'wptopschool' ),


	'FI' => __( 'Finland', 'wptopschool' ),


	'FR' => __( 'France', 'wptopschool' ),


	'GF' => __( 'French Guiana', 'wptopschool' ),


	'PF' => __( 'French Polynesia', 'wptopschool' ),


	'TF' => __( 'French Southern Territories', 'wptopschool' ),


	'GA' => __( 'Gabon', 'wptopschool' ),


	'GM' => __( 'Gambia', 'wptopschool' ),


	'GE' => __( 'Georgia', 'wptopschool' ),


	'DE' => __( 'Germany', 'wptopschool' ),


	'GH' => __( 'Ghana', 'wptopschool' ),


	'GI' => __( 'Gibraltar', 'wptopschool' ),


	'GR' => __( 'Greece', 'wptopschool' ),


	'GL' => __( 'Greenland', 'wptopschool' ),


	'GD' => __( 'Grenada', 'wptopschool' ),


	'GP' => __( 'Guadeloupe', 'wptopschool' ),


	'GT' => __( 'Guatemala', 'wptopschool' ),


	'GG' => __( 'Guernsey', 'wptopschool' ),


	'GN' => __( 'Guinea', 'wptopschool' ),


	'GW' => __( 'Guinea-Bissau', 'wptopschool' ),


	'GY' => __( 'Guyana', 'wptopschool' ),


	'HT' => __( 'Haiti', 'wptopschool' ),


	'HM' => __( 'Heard Island and McDonald Islands', 'wptopschool' ),


	'HN' => __( 'Honduras', 'wptopschool' ),


	'HK' => __( 'Hong Kong', 'wptopschool' ),


	'HU' => __( 'Hungary', 'wptopschool' ),


	'IS' => __( 'Iceland', 'wptopschool' ),


	'IN' => __( 'India', 'wptopschool' ),


	'ID' => __( 'Indonesia', 'wptopschool' ),


	'IR' => __( 'Iran', 'wptopschool' ),


	'IQ' => __( 'Iraq', 'wptopschool' ),


	'IE' => __( 'Republic of Ireland', 'wptopschool' ),


	'IM' => __( 'Isle of Man', 'wptopschool' ),


	'IL' => __( 'Israel', 'wptopschool' ),


	'IT' => __( 'Italy', 'wptopschool' ),


	'CI' => __( 'Ivory Coast', 'wptopschool' ),


	'JM' => __( 'Jamaica', 'wptopschool' ),


	'JP' => __( 'Japan', 'wptopschool' ),


	'JE' => __( 'Jersey', 'wptopschool' ),


	'JO' => __( 'Jordan', 'wptopschool' ),


	'KZ' => __( 'Kazakhstan', 'wptopschool' ),


	'KE' => __( 'Kenya', 'wptopschool' ),


	'KI' => __( 'Kiribati', 'wptopschool' ),


	'KW' => __( 'Kuwait', 'wptopschool' ),


	'KG' => __( 'Kyrgyzstan', 'wptopschool' ),


	'LA' => __( 'Laos', 'wptopschool' ),


	'LV' => __( 'Latvia', 'wptopschool' ),


	'LB' => __( 'Lebanon', 'wptopschool' ),


	'LS' => __( 'Lesotho', 'wptopschool' ),


	'LR' => __( 'Liberia', 'wptopschool' ),


	'LY' => __( 'Libya', 'wptopschool' ),


	'LI' => __( 'Liechtenstein', 'wptopschool' ),


	'LT' => __( 'Lithuania', 'wptopschool' ),


	'LU' => __( 'Luxembourg', 'wptopschool' ),


	'MO' => __( 'Macao S.A.R., China', 'wptopschool' ),


	'MK' => __( 'Macedonia', 'wptopschool' ),


	'MG' => __( 'Madagascar', 'wptopschool' ),


	'MW' => __( 'Malawi', 'wptopschool' ),


	'MY' => __( 'Malaysia', 'wptopschool' ),


	'MV' => __( 'Maldives', 'wptopschool' ),


	'ML' => __( 'Mali', 'wptopschool' ),


	'MT' => __( 'Malta', 'wptopschool' ),


	'MH' => __( 'Marshall Islands', 'wptopschool' ),


	'MQ' => __( 'Martinique', 'wptopschool' ),


	'MR' => __( 'Mauritania', 'wptopschool' ),


	'MU' => __( 'Mauritius', 'wptopschool' ),


	'YT' => __( 'Mayotte', 'wptopschool' ),


	'MX' => __( 'Mexico', 'wptopschool' ),


	'FM' => __( 'Micronesia', 'wptopschool' ),


	'MD' => __( 'Moldova', 'wptopschool' ),


	'MC' => __( 'Monaco', 'wptopschool' ),


	'MN' => __( 'Mongolia', 'wptopschool' ),


	'ME' => __( 'Montenegro', 'wptopschool' ),


	'MS' => __( 'Montserrat', 'wptopschool' ),


	'MA' => __( 'Morocco', 'wptopschool' ),


	'MZ' => __( 'Mozambique', 'wptopschool' ),


	'MM' => __( 'Myanmar', 'wptopschool' ),


	'NA' => __( 'Namibia', 'wptopschool' ),


	'NR' => __( 'Nauru', 'wptopschool' ),


	'NP' => __( 'Nepal', 'wptopschool' ),


	'NL' => __( 'Netherlands', 'wptopschool' ),


	'AN' => __( 'Netherlands Antilles', 'wptopschool' ),


	'NC' => __( 'New Caledonia', 'wptopschool' ),


	'NZ' => __( 'New Zealand', 'wptopschool' ),


	'NI' => __( 'Nicaragua', 'wptopschool' ),


	'NE' => __( 'Niger', 'wptopschool' ),


	'NG' => __( 'Nigeria', 'wptopschool' ),


	'NU' => __( 'Niue', 'wptopschool' ),


	'NF' => __( 'Norfolk Island', 'wptopschool' ),


	'KP' => __( 'North Korea', 'wptopschool' ),


	'NO' => __( 'Norway', 'wptopschool' ),


	'OM' => __( 'Oman', 'wptopschool' ),


	'PK' => __( 'Pakistan', 'wptopschool' ),


	'PS' => __( 'Palestinian Territory', 'wptopschool' ),


	'PA' => __( 'Panama', 'wptopschool' ),


	'PG' => __( 'Papua New Guinea', 'wptopschool' ),


	'PY' => __( 'Paraguay', 'wptopschool' ),


	'PE' => __( 'Peru', 'wptopschool' ),


	'PH' => __( 'Philippines', 'wptopschool' ),


	'PN' => __( 'Pitcairn', 'wptopschool' ),


	'PL' => __( 'Poland', 'wptopschool' ),


	'PT' => __( 'Portugal', 'wptopschool' ),


	'QA' => __( 'Qatar', 'wptopschool' ),


	'RE' => __( 'Reunion', 'wptopschool' ),


	'RO' => __( 'Romania', 'wptopschool' ),


	'RU' => __( 'Russia', 'wptopschool' ),


	'RW' => __( 'Rwanda', 'wptopschool' ),


	'BL' => __( 'Saint Barth&eacute;lemy', 'wptopschool' ),


	'SH' => __( 'Saint Helena', 'wptopschool' ),


	'KN' => __( 'Saint Kitts and Nevis', 'wptopschool' ),


	'LC' => __( 'Saint Lucia', 'wptopschool' ),


	'MF' => __( 'Saint Martin (French part)', 'wptopschool' ),


	'SX' => __( 'Saint Martin (Dutch part)', 'wptopschool' ),


	'PM' => __( 'Saint Pierre and Miquelon', 'wptopschool' ),


	'VC' => __( 'Saint Vincent and the Grenadines', 'wptopschool' ),


	'SM' => __( 'San Marino', 'wptopschool' ),


	'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wptopschool' ),


	'SA' => __( 'Saudi Arabia', 'wptopschool' ),


	'SN' => __( 'Senegal', 'wptopschool' ),


	'RS' => __( 'Serbia', 'wptopschool' ),


	'SC' => __( 'Seychelles', 'wptopschool' ),


	'SL' => __( 'Sierra Leone', 'wptopschool' ),


	'SG' => __( 'Singapore', 'wptopschool' ),


	'SK' => __( 'Slovakia', 'wptopschool' ),


	'SI' => __( 'Slovenia', 'wptopschool' ),


	'SB' => __( 'Solomon Islands', 'wptopschool' ),


	'SO' => __( 'Somalia', 'wptopschool' ),


	'ZA' => __( 'South Africa', 'wptopschool' ),


	'GS' => __( 'South Georgia/Sandwich Islands', 'wptopschool' ),


	'KR' => __( 'South Korea', 'wptopschool' ),


	'SS' => __( 'South Sudan', 'wptopschool' ),


	'ES' => __( 'Spain', 'wptopschool' ),


	'LK' => __( 'Sri Lanka', 'wptopschool' ),


	'SD' => __( 'Sudan', 'wptopschool' ),


	'SR' => __( 'Suriname', 'wptopschool' ),


	'SJ' => __( 'Svalbard and Jan Mayen', 'wptopschool' ),


	'SZ' => __( 'Swaziland', 'wptopschool' ),


	'SE' => __( 'Sweden', 'wptopschool' ),


	'CH' => __( 'Switzerland', 'wptopschool' ),


	'SY' => __( 'Syria', 'wptopschool' ),


	'TW' => __( 'Taiwan', 'wptopschool' ),


	'TJ' => __( 'Tajikistan', 'wptopschool' ),


	'TZ' => __( 'Tanzania', 'wptopschool' ),


	'TH' => __( 'Thailand', 'wptopschool' ),


	'TL' => __( 'Timor-Leste', 'wptopschool' ),


	'TG' => __( 'Togo', 'wptopschool' ),


	'TK' => __( 'Tokelau', 'wptopschool' ),


	'TO' => __( 'Tonga', 'wptopschool' ),


	'TT' => __( 'Trinidad and Tobago', 'wptopschool' ),


	'TN' => __( 'Tunisia', 'wptopschool' ),


	'TR' => __( 'Turkey', 'wptopschool' ),


	'TM' => __( 'Turkmenistan', 'wptopschool' ),


	'TC' => __( 'Turks and Caicos Islands', 'wptopschool' ),


	'TV' => __( 'Tuvalu', 'wptopschool' ),


	'UG' => __( 'Uganda', 'wptopschool' ),


	'UA' => __( 'Ukraine', 'wptopschool' ),


	'AE' => __( 'United Arab Emirates', 'wptopschool' ),


	'GB' => __( 'United Kingdom (UK)', 'wptopschool' ),


	'US' => __( 'United States (US)', 'wptopschool' ),


	'UY' => __( 'Uruguay', 'wptopschool' ),


	'UZ' => __( 'Uzbekistan', 'wptopschool' ),


	'VU' => __( 'Vanuatu', 'wptopschool' ),


	'VA' => __( 'Vatican', 'wptopschool' ),


	'VE' => __( 'Venezuela', 'wptopschool' ),


	'VN' => __( 'Vietnam', 'wptopschool' ),


	'WF' => __( 'Wallis and Futuna', 'wptopschool' ),


	'EH' => __( 'Western Sahara', 'wptopschool' ),


	'WS' => __( 'Western Samoa', 'wptopschool' ),


	'YE' => __( 'Yemen', 'wptopschool' ),


	'ZM' => __( 'Zambia', 'wptopschool' ),


	'ZW' => __( 'Zimbabwe', 'wptopschool' )


	);


}


/* This Function is Check Pro Version */


function wpts_check_pro_version( $class='wpts_pro_version' ) {





	$response = array();


	$response['status']	 =true;


	if( !empty( $class ) && !class_exists( $class ) ) {


		$response['status']		=	false;


		$response['class']		=	'upgrade-to-wpts-version';


		$response['message']	=	'Please Purchase This Add-on';


		return $response;


	}


	return $response;


}


?>