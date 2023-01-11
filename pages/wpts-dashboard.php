<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
if( is_user_logged_in() ) {
	global $current_user, $wp_roles, $wpdb;
	//get_currentuserinfo();
	$propayment = wpts_check_pro_version('pay_WooCommerce');
	$propayment = !$propayment['status'] ? 'notinstalled'    : 'installed';

	$current_user_role=$current_user->roles[0];
	if( $current_user_role == 'administrator' || $current_user_role == 'teacher' || $current_user_role == 'parent' || $current_user_role == 'student' ) {
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();

		global $wpdb;
		$user_table		=	$wpdb->prefix."users";
		$student_table		=	$wpdb->prefix."wpts_student";
		$teacher_table		=	$wpdb->prefix."wpts_teacher";
		$class_table		=	$wpdb->prefix."wpts_class";
		$attendance_table	=	$wpdb->prefix."wpts_attendance";
		$usercount = $wpdb->get_row("SELECT count(sid) as countstudent FROM $student_table JOIN $user_table ON $user_table.ID = $student_table.wp_usr_id");
		$teachercount = $wpdb->get_row("SELECT count(tid) as countteacher FROM $teacher_table JOIN $user_table ON $user_table.ID = $teacher_table.wp_usr_id");
		$parentscount = $wpdb->get_row("SELECT count(sid) as countparents FROM $student_table JOIN $user_table ON $user_table.ID = $student_table.parent_wp_usr_id");

		$users_count		=	$wpdb->get_row("SELECT(SELECT COUNT(*)FROM $student_table )AS stcount,(SELECT COUNT(*)FROM $teacher_table) AS tcount,(SELECT COUNT(DISTINCT parent_wp_usr_id) FROM $student_table where `parent_wp_usr_id`!='') AS pcount,(SELECT COUNT(*) FROM $class_table) AS clcount");


		?>

		<?php

		if( $current_user_role == 'parent') {
			$paymenconfirmationid = 0;?>

			<?php $stable = $wpdb->prefix."wpts_student";
			$ctlass = $wpdb->prefix."wpts_class";
            $cteacher	=	$wpdb->prefix."wpts_teacher";
            $ftlass = $wpdb->prefix."wpts_fees";
			$wpts_classes =$wpdb->get_results("SELECT * FROM $stable where parent_wp_usr_id = '$current_user->ID'");

			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,

            );
            $siid = array();
            $results = $wpdb->get_results("SELECT s.wp_usr_id,f.student_id,f.order_id FROM $stable AS s INNER JOIN $ftlass AS f ON f.student_id =  s.wp_usr_id");
            foreach($results as $res){
                $siid[] = $res->wp_usr_id;

            }
			$productloop = new WP_Query( $args );
			foreach ($wpts_classes as $sclas) {

				if (is_numeric($sclas->class_id)){
					$classIDArray[] = $sclas->class_id;
				}else{
					$classIDArray = unserialize($sclas->class_id);
				}
                $classIDArray = array_map('intval', $classIDArray);
				foreach ($classIDArray as $idsvalue){
                    $idsvalue = sanitize_text_field($idsvalue);
                    $clasname = $wpdb->get_row("SELECT * FROM $ctlass where cid='".esc_sql($idsvalue)."'");

					if($clasname->c_fee_type == 'free'){
	 					$nonemenu = 'nopayment-none';
					}else {
						$wpts_tech_data =$wpdb->get_results("SELECT * FROM  $cteacher t INNER JOIN $ctlass c ON t.wp_usr_id=c.teacher_id where c.cid = '".$idsvalue."' and c.c_fee_type = 'paid'");

				$courses1 = get_user_meta($current_user->ID, '_pay_woocommerce_enrolled_class_access_counter');
				$courses2 = get_user_meta($sclas->wp_usr_id, '_pay_woocommerce_enrolled_class_access_counter' );
			   		$courses = array_merge($courses1,$courses2);


				if ( ! empty( $courses ) ) {
					$courses = maybe_unserialize( $courses );
				} else {
					$courses = array();
				}

				$courses_check=array();
				$courses_value=array();

				foreach($courses as $key => $value)
				{
					foreach($value as $key1 => $value1)
					{
						if(!empty($value1))
												{
													$courses_check[] = $key1;
													$courses_value[] = $value1;
												}
					}
				}
				if(in_array($sclas->wp_usr_id, $siid))
				{
                    echo"";
                    //  echo $paymenconfirmationid;
				}
				else
				{
                    echo "";
                    $paymenconfirmationid++;
                    // echo $paymenconfirmationid;

				}


}
}}
$nocount = 0;
// echo $paymenconfirmationid;
if($paymenconfirmationid > 0)
						{
							$nonemenu = ' nopayment-none1';
						}
						else
						{
							$nonemenu = ' nopayment-none';
						}
						$args = array(
									'post_type'      => 'product',
									'posts_per_page' => -1
								);
					$obituary_query = new WP_Query($args);

					while ($obituary_query->have_posts()) : $obituary_query->the_post();
					global $product;

						$product_title= get_the_title();
						if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
						$currency = get_woocommerce_currency_symbol();
					}
				$price = get_post_meta( get_the_ID(), '_regular_price', true);
					$courses1 = get_post_meta( get_the_ID(), '_related_class', true );
					if ( ! empty( $courses1 ) ) {
						$courses1 = maybe_unserialize( $courses1 );
					} else {
						$courses1 = array();
					}




					foreach($courses1 as $key => $value){

							if(in_array($value, $classIDArray)){

                                   $nocount++;
							}

						}
							endwhile;
				wp_reset_query();
                            //  echo $nocount;
				// if($nocount > 0)
				// 		{
				// 					$nonemenu = ' nopayment-none1';
				// 		}
				// 		else
				// 		{
				// 					$nonemenu = ' nopayment-none';
				// 		}
		?>
<style type="text/css">
	.nopayment-none10 {display:block !important;}
</style>
<?php if($propayment == "installed"){?>
<table class="wpts-table <?php echo $nonemenu;?>" id="status-table" cellspacing="0" width="100%" style="width:100%;margin-bottom: 30px !important;">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Student Name', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Roll No', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Class Name', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Class Teacher Name', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Class Fee Status', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Fees For', 'wptopschool' ); ?></th>
                    <th><?php esc_html_e( 'Pay Now', 'wptopschool' ); ?></th>
				</tr>
			</thead>
			<tbody>

<?php 	  }
 $siid = array();
 $results = $wpdb->get_results("SELECT s.wp_usr_id,f.student_id,f.order_id FROM $stable AS s INNER JOIN $ftlass AS f ON f.student_id =  s.wp_usr_id");
 foreach($results as $res){
    $siid[] = $res->wp_usr_id;
    // echo "<pre>";print_r($siid);
 }

			foreach ($wpts_classes as $sclas) {
				if (is_numeric($sclas->class_id)){
					$classIDArray[] = $sclas->class_id;
				}else{
					$classIDArray = unserialize($sclas->class_id);
				}
                $classIDArray = array_map('intval', $classIDArray);
				foreach ($classIDArray as $idsvalue){
                    $idsvalue = sanitize_text_field($idsvalue);
					$clasname = $wpdb->get_row("SELECT * FROM  $ctlass where cid='".esc_sql($idsvalue)."'");
					if($clasname->c_fee_type == 'free'){
	 					$nonemenu = 'nopayment-none';

					}else {

						$wpts_tech_data =$wpdb->get_results("SELECT * FROM  $cteacher t INNER JOIN $ctlass c ON t.wp_usr_id=c.teacher_id where c.cid = '".$idsvalue."' and c.c_fee_type = 'paid'");

				$courses1 = get_user_meta($current_user->ID, '_pay_woocommerce_enrolled_class_access_counter');
				$courses2 = get_user_meta($sclas->wp_usr_id, '_pay_woocommerce_enrolled_class_access_counter' );
				$courses = array_merge($courses1,$courses2);


				if ( ! empty( $courses ) ) {
					$courses = maybe_unserialize( $courses );
				} else {
					$courses = array();
				}

				$courses_check=array();
				$courses_value=array();

				foreach($courses as $key => $value)
				{
					foreach($value as $key1 => $value1)
					{
												if(!empty($value1))
												{
													$courses_check[] = $key1;
													$courses_value[] = $value1;
												}
					}
				}
				if(in_array($idsvalue, $courses_check))
				{

				}
				else
				{

					$paymenconfirmationid++;

				}
						if($paymenconfirmationid > 0)
						{
									$nonemenu = ' nopayment-none';
						}
						else
						{
									$nonemenu = ' nopayment-none1';
						}
							$courses_valu_final = array();
						foreach($courses_value as $key => $value)
						{
								foreach($value as $key1 => $value1)
								{
									$courses_valu_final[] = $value1;
								}
						}
				$product_id=array();
				foreach($courses_valu_final as $key => $value )
				{
						$order = wc_get_order($value);
						$items = $order->get_items();
						foreach ( $items as $item )
						{
    						$product_id[] = $item['product_id'];
						}
				}
				if ( $productloop->have_posts() ) :
						  while ( $productloop->have_posts() ) : $productloop->the_post();
						  	global $product;
							$product_title= get_the_title();
						if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
						$currency = get_woocommerce_currency_symbol();
					}
							$price = get_post_meta( get_the_ID(), '_sale_price', true);
                            $courses1 = get_post_meta( get_the_ID(), '_related_class', true );
                            //  echo "<pre>";print_r($courses_check);
                            // echo "<pre>";print_r($siid);


							if ( ! empty( $courses1 ) ) {
								$courses1 = maybe_unserialize( $courses1 );
							} else {
								$courses1 = array();
							}
							foreach($courses1 as $key => $value){
								if(!in_array($sclas->wp_usr_id, $siid)){

									if(in_array($value, $classIDArray)){

										$stable=$wpdb->prefix."wpts_student";
										$ctlass=$wpdb->prefix."wpts_class";
										$cteacher =$wpdb->prefix."wpts_teacher";

										 $wpts_cls_data =$wpdb->get_results("SELECT * FROM   $ctlass where cid = '".$value."' and c_fee_type = 'paid'");

										if($propayment == "installed"){
											?>
											<tbody>
												<tr>
													<td><?php echo  esc_html($sclas->s_fname.' '.$sclas->s_lname);?></td>
													<td><?php echo  esc_html($sclas->s_rollno);?></td>
													<td><?php echo  esc_html($wpts_cls_data[0]->c_name);?></td>
													<td><?php echo  esc_html($wpts_tech_data[0]->first_name.' '.$wpts_tech_data[0]->last_name);?></td>
													<td><?php echo esc_html('Not Paid','wptopschool')?> </td>
													<td><?php echo esc_html($product_title);?></td>
													<td><a class="wpts-btn" href="<?php echo get_permalink();?>?sid=<?php echo esc_attr($sclas->wp_usr_id);?>&cid=<?php echo esc_attr($value);?>" target="_blank"> <?php echo esc_html($currency."".$price) ;?> <?php echo esc_html('Pay Now','wptopschool');?></a></td>
												</tr>

											</tbody>
											<?php
										}

									}
								}
							}
endwhile;
						break;

					wp_reset_query();
				endif;
			}
		}
	}
	?>
	</tbody>
</table>
<?php
}



if( $current_user_role == 'student') {

	$stable=$wpdb->prefix."wpts_student";
	$ctlass=$wpdb->prefix."wpts_class";
	$user_id = $current_user->ID;
	$wpts_stud_data = $wpdb->get_results("SELECT s.class_id,s.parent_wp_usr_id FROM  $stable s INNER JOIN $ctlass c where s.wp_usr_id = '".$user_id."' and c.c_fee_type = 'paid'");


	$cid = [];
	if(!empty($wpts_stud_data)){
		if(is_numeric($wpts_stud_data[0]->class_id) ){
			$cid[] = $wpts_stud_data[0]->class_id;
		}else{
			$class_id_array = unserialize( $wpts_stud_data[0]->class_id );
			foreach($class_id_array as $id){
				$cid[] = $id;
			}
		}
	}
	if($propayment == "installed"){
		$paymenconfirmationid = 0;
        $class_id_array = array_map('intval', $class_id_array);
		foreach ($class_id_array as $id ){
            $id = esc_sql($id);
			$clasname = $wpdb->get_row("SELECT * FROM $ctlass where cid='".$id."'");
			if($clasname->c_fee_type == 'free'){
				$nonemenu = 'nopayment-none';
			} else {
				$wpts_tech_data =$wpdb->get_results("SELECT * FROM  $cteacher t INNER JOIN $ctlass c ON t.wp_usr_id=c.teacher_id where c.cid = '".$id."' and c.c_fee_type = 'paid'");
				$courses1 = get_user_meta($wpts_stud_data[0]->parent_wp_usr_id, '_pay_woocommerce_enrolled_class_access_counter' );
				$courses2 = get_user_meta( $user_id, '_pay_woocommerce_enrolled_class_access_counter' );
				$courses = array_merge($courses1,$courses2);
				if ( ! empty( $courses ) ) {
					$courses = maybe_unserialize( $courses );
				} else {
					$courses = array();
				}
				$courses_check=array();
				foreach($courses as $key => $value)
				{
					foreach($value as $key1 => $value1)
					{
						if(!empty($value1))
						{
							$courses_check[] = $key1;
						}
					}
				}
				if(in_array($id, $courses_check))
				{
				}
				else
				{
					 $paymenconfirmationid++;
				}
			}
		}
if($paymenconfirmationid > 0){
					$nonemenu = ' nopayment1-none1';
				}else {
					$nonemenu = ' nopayment-none';
				}
				$args = array(
									'post_type'      => 'product',
									'posts_per_page' => -1
								);
					$obituary_query = new WP_Query($args);
					while ($obituary_query->have_posts()) : $obituary_query->the_post();
					global $product;
						$product_title= get_the_title();
						if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
						$currency = get_woocommerce_currency_symbol();
					}
				$price = get_post_meta( get_the_ID(), '_regular_price', true);
					$courses1 = get_post_meta( get_the_ID(), '_related_class', true );
					if ( ! empty( $courses1 ) ) {
						$courses1 = maybe_unserialize( $courses1 );
					} else {
						$courses1 = array();
					}

			foreach($courses1 as $key => $value)
						{
							if(in_array($value, $cid[0]))
							{
								$nocount++;
							}
						}
				endwhile;
				wp_reset_query();
				if($nocount > 0)
						{
									$nonemenu = ' nopayment-none1';
						}
						else
						{
									$nonemenu = ' nopayment-none';
						}
		?>

						<table class="wpts-table <?php echo $nonemenu; ?>" id="status-table" cellspacing="0" width="100%" style="width:100%;margin-bottom: 30px !important;">
							<thead>
								<tr>
                                <th><?php esc_html_e( 'Student Name', 'wptopschool' ); ?></th>
                                <th><?php esc_html_e( 'Roll No', 'wptopschool' ); ?></th>
                                <th><?php esc_html_e( 'Class Name', 'wptopschool' ); ?></th>
                                <th><?php esc_html_e( 'Class Teacher Name', 'wptopschool' ); ?></th>
                                <th><?php esc_html_e( 'Class Fee Status', 'wptopschool' ); ?></th>
                                <th><?php esc_html_e( 'Fees For', 'wptopschool' ); ?></th>

                                <th><?php esc_html_e( 'Pay Now', 'wptopschool' );?></th>
								</tr>
							</thead>
							<?php
						}
						if($propayment == "installed"){
						$wpts_tech_data =$wpdb->get_results("SELECT * FROM  $cteacher t INNER JOIN $ctlass c ON t.wp_usr_id=c.teacher_id where c.cid = '".$cid."' and c.c_fee_type = 'paid'");
						if ( ! empty( $courses ) ) {
							$courses = maybe_unserialize( $courses );
						} else {
							$courses = array();
						}
						$courses_check=array();
						$courses_value=array();
						foreach($courses as $key => $value){
									foreach($value as $key1 => $value1){
									if(!empty($value1)){
												$courses_check[] = $key1;
												$courses_value[] = $value1;
										}
										}

										}
							$courses_valu_final = array();
							foreach($courses_value as $key => $value)
							{
								foreach($value as $key1 => $value1)
								{
									$courses_valu_final[] = $value1;
								}
							}
					$product_id=array();
					foreach($courses_valu_final as $key => $value )
							{
									$order = wc_get_order($value);
									$items = $order->get_items();
									foreach($items as $item )
										{
									    $product_id[] = $item['product_id'];
										}
							}


					if(!empty($cid[0])){
						foreach($cid[0] as $cid1){
							if(in_array($cid1, $courses_check)){

								$nonemenu = ' nopayment1-none1';
							} else {

								$nonemenu = ' nopayment-none';

							}
						}
					}
					$args = array('post_type'      => 'product',
								  'posts_per_page' => -1);
					$obituary_query = new WP_Query($args);
					while ($obituary_query->have_posts()) : $obituary_query->the_post();
					global $product;
						$product_title= get_the_title();
						if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
						$currency = get_woocommerce_currency_symbol();
					}
				$price = get_post_meta( get_the_ID(), '_regular_price', true);
					$courses1 = get_post_meta( get_the_ID(), '_related_class', true );
					if ( ! empty( $courses1 ) ) {
						$courses1 = maybe_unserialize( $courses1 );
					} else {
						$courses1 = array();
					}
					foreach($courses1 as $key => $value)
					{
						if(!in_array(get_the_ID(), $product_id))
						{
							if(in_array($value, $cid[0]))
							{
								$stable=$wpdb->prefix."wpts_student";
								$ctlass=$wpdb->prefix."wpts_class";
								$cteacher =$wpdb->prefix."wpts_teacher";
								$wpts_stud_data =$wpdb->get_results("SELECT * FROM  $stable s INNER JOIN $ctlass c where s.wp_usr_id = '".$user_id."' and c.c_fee_type = 'paid' and c.cid = ".$value);
								$wpts_tech_data =$wpdb->get_results("SELECT * FROM  $cteacher t INNER JOIN $ctlass c ON t.wp_usr_id=c.teacher_id where c.cid = '".$value."' and c.c_fee_type = 'paid'");
								if($propayment == "installed"){	?>
									<tbody>
										<tr>
										<td><?php echo esc_html($wpts_stud_data[0]->s_fname.' '.$wpts_stud_data[0]->s_lname);?></td>
										<td><?php echo  esc_html($wpts_stud_data[0]->s_rollno);?></td>
										<td> <?php echo  esc_html($wpts_stud_data[0]->c_name);?></td>
										<td> <?php echo  esc_html($wpts_tech_data[0]->first_name.' '.$wpts_tech_data[0]->last_name);?></td>
										<td><?php esc_html_e( 'Not Paid', 'wptopschool' ); ?></td>
										<td> <?php echo esc_html($product_title);?></td>
										<td><a class="wpts-btn" href="<?php echo get_permalink();?>" target="_blank"><?php echo esc_html($currency."".$price) ;?> <?php esc_html_e( 'Pay Now', 'wptopschool' ); ?></a></td>
										</tr>
									</tbody>
									<?php
								}
								$nonemenu = ' nopayment-none';
							}
						}
					}
				endwhile;
				wp_reset_query();
			}}
			if($propayment == "installed"){
				if( $current_user_role == 'student') {
				if($nocount > 0){?>
</table>

				<?php } else {


					?>
				</table>

<?php
 }}
}
if($propayment != "installed"){
	$nonemenu='';
}
if( $current_user_role=='administrator' || $current_user_role == 'parent'){?> <div class="wpts-row "><?php } else { ?>
	<div class="wpts-row">
	<?php  } ?>
	<div class="wpts-col-sm-3 wpts-col-xs-6">
		<a class="wpts-colorBox" <?php if($current_user_role == 'parent' || $current_user_role == 'student'){ }else {?> href="<?php echo wpts_admin_url();?>sch-student" <?php } ?> >
			<span class="wpts-colorBox-title"><?php echo apply_filters('wpts_sidebar_student_title_menu',esc_html__('Students','wptopschool')); ?></span>
			<h4 class="wpts-colorBox-head"><?php echo isset( $usercount->countstudent ) ?  intval($usercount->countstudent) : 0; ?><sup>+</sup></h4>
		</a>
	</div>
	<div class="wpts-col-sm-3 wpts-col-xs-6">
		<a class="wpts-colorBox wpts-orangebox wpts-teacherInfo" <?php if($current_user_role == 'parent'  || $current_user_role == 'student'){ }else {?> href="<?php echo wpts_admin_url();?>sch-teacher" <?php } ?>>
			<span class="wpts-colorBox-title"><?php echo apply_filters('wpts_sidebar_teacher_title_menu',esc_html__('Teachers','wptopschool')); ?></span>
			<h4 class="wpts-colorBox-head"><?php echo isset($teachercount->countteacher)  ?  intval($teachercount->countteacher) : 0; ?><sup>+</sup></h4>
		</a>
	</div>
	<div class="wpts-col-sm-3 wpts-col-xs-6">
		<a class="wpts-colorBox wpts-yellowbox wpts-parentsInfo"<?php if($current_user_role == 'parent'  || $current_user_role == 'student'){ }else {?>  href="<?php echo wpts_admin_url();?>sch-parent" <?php } ?>>
			<span class="wpts-colorBox-title"><?php echo apply_filters('wpts_sidebar_parent_title_menu',esc_html__('Parents','wptopschool')); ?></span>
			<h4 class="wpts-colorBox-head"><?php echo isset($parentscount->countparents) ?  intval($parentscount->countparents) : 0; ?><sup>+</sup></h4>
		</a>
	</div>

	<div class="wpts-col-sm-3 wpts-col-xs-6">
		<a class="wpts-colorBox wpts-greenbox wpts-classInfo" <?php if($current_user_role == 'parent'  || $current_user_role == 'student'){ }else {?> href="<?php echo wpts_admin_url();?>sch-class" <?php } ?>>
			<span class="wpts-colorBox-title"><?php echo apply_filters('wpts_sidebar_classes_title_menu',esc_html__('Classes','wptopschool'));?></span>
			<h4 class="wpts-colorBox-head"><?php echo isset(  $users_count->clcount ) ?  intval($users_count->clcount) : 0; ?><sup>+</sup></h4>
		</a>
	</div>

	</div>
	<!-- Info boxes -->

	<?php if( $current_user_role=='administrator' || $current_user_role == 'parent' ){?> <div class="wpts-row "><?php } else { ?>
	<div class="wpts-row">
		<?php  } ?>
		<!-- Left col -->
		<div class="wpts-col-lg-8  wpts-col-xs-12">
			<div class="wpts-card">
				<div class="wpts-card-head">
					<div class="wpts-left">
						<h3 class="wpts-card-title"><?php esc_html_e( 'Activities Calender', 'wptopschool' )?></h3>
					</div>
					<ul class="wpts-cards-indicators wpts-right">
						<li><span class="wpts-indic wpts-blue-indic"></span> <?php esc_html_e( 'Events', 'wptopschool' ); ?></li>
						<li><span class="wpts-indic wpts-red-indic"></span> <?php esc_html_e( 'Exams', 'wptopschool' ); ?></li>
						<li><span class="wpts-indic wpts-green-indic"></span> <?php esc_html_e( 'Holidays', 'wptopschool' );?></li>
					</ul>
				</div>

				<div class="wpts-card-body">
					<div id="multiple-events"></div>
				</div>
			</div>
		</div>
		<div class="wpts-col-lg-4  wpts-col-xs-12">
			<div class="wpts-card">
				<div class="wpts-card-head">
					<h3 class="wpts-card-title"><?php esc_html_e( 'Exam', 'wptopschool' )?></h3>
				</div>
				<div class="wpts-card-body">

                      <?php if($current_user_role == 'student' ) {
						$current_user_id = get_current_user_id();
                        $Student_table=$wpdb->prefix."wpts_student";

                    $examinfo=$wpdb->get_row("select class_id from $Student_table where wp_usr_id = '$current_user_id'");
                        $examclassid =   unserialize($examinfo->class_id);

                        $exam_table=$wpdb->prefix."wpts_exam";
                        foreach($examclassid as $class_id ) {
                        $examinfo  = $wpdb->get_results("select * from $exam_table where classid = '$class_id' order by e_s_date ASC");
						}
						if(!empty($examinfo)){
						?>
					<table class="wpts-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Date', 'wptopschool' );?></th>
								<th><?php esc_html_e( 'Exam', 'wptopschool' );?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($examinfo as $exam) { ?>
								<tr>
									<td><?php echo wpts_ViewDate(esc_html($exam->e_s_date))." TO ".wpts_ViewDate(esc_html($exam->e_e_date));?></td>
									<td><?php echo esc_html($exam->e_name); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } } elseif($current_user_role == 'parent')
					{
						$parentexam = array();
						$clasid = array();
						$current_user_id = get_current_user_id();
                        $Student_table=$wpdb->prefix."wpts_student";

                    $examinfo=$wpdb->get_results("select * from $Student_table where parent_wp_usr_id = '$current_user_id'");

                     foreach($examinfo as $class_id_f ) {
                     	$parentexam[] = $class_id_f->class_id;
                     }

                    foreach($parentexam as $parray){

                        $examclassid =   unserialize($parray);

                        $exam_table=$wpdb->prefix."wpts_exam";
                        foreach($examclassid as $class_id ) {

                        	$clasid[] = $class_id;

						}
					}

					  $parentchildcls = esc_sql(implode(',',$clasid));
					  $examinfo  = $wpdb->get_results("select * from $exam_table where classid IN (".$parentchildcls.") order by e_s_date ASC");

					if(!empty($examinfo)){
						?>
					<table class="wpts-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Date', 'wptopschool' );?></th>
								<th><?php esc_html_e( 'Exam', 'wptopschool' );?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($examinfo as $exam) { ?>
								<tr>
									<td><?php echo wpts_ViewDate(esc_html($exam->e_s_date))." TO ".wpts_ViewDate(esc_html($exam->e_e_date));?></td>
									<td><?php echo esc_html($exam->e_name); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php }}else {
						$exam_table=$wpdb->prefix."wpts_exam";
						$class_table=$wpdb->prefix."wpts_class";

						$examinfo=$wpdb->get_results("SELECT u.*, c.*
							FROM $exam_table u
							INNER JOIN $class_table c ON u.classid= c.cid
							order by u. e_s_date ASC");
							if(!empty($examinfo)){?>
							<table class="wpts-table">
								<thead>
									<tr>
										<th><?php esc_html_e( 'Date', 'wptopschool' );?></th>
										<th><?php esc_html_e( 'Exam', 'wptopschool' );?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($examinfo as $exam) { ?>
										<tr>
											<td><?php echo wpts_ViewDate(esc_html($exam->e_s_date))." TO ".wpts_ViewDate(esc_html($exam->e_e_date));?></td>
											<td><?php echo esc_html($exam->e_name."(".$exam->c_name.")"); ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						<?php } } ?>
						</div>
					</div>
				</div>
		</div>
		<div id="eventContent" title="Event Details" style="display:none;">
			<div class="modal-content">
			<span class="close">&times;</span>
			<h4><?php esc_html_e( 'Event Name', 'wptopschool' )?> :  <span id="viewEventTitle"></span></h4>
    	<?php esc_html_e( 'Start', 'wptopschool' );?>: <span id="eventStart"></span><br>
    	<?php esc_html_e( 'End', 'wptopschool' );?>: <span id="eventEnd"></span><br>


	</div>
</div>
	<?php
		wpts_body_end();
		wpts_footer();
	} else {
		echo WPTS_PERMISSION_MSG;
	}
 } else {
    include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
	}
?>
