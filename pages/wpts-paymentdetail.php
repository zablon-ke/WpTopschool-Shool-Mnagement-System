<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header();
    if( is_user_logged_in() ) {
        wpts_topbar();
        wpts_sidebar();
        wpts_body_start();
        $proversion     =   wpts_check_pro_version( 'wpts_payment_version' );
        $proclass       =   !$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
        $protitle       =   !$proversion['status'] && isset( $proversion['message'] )? $proversion['message']   : '';

        ?>
        <section class="content-header">
            <h1><?php esc_html_e( 'Payment', 'wptopschool' ); ?></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo esc_url(site_url('sch-dashboard')); ?> "><i class="fa fa-dashboard"></i> <?php esc_html_e( 'Dashboard', 'wptopschool' ); ?></a></li>
                <li><a href="<?php echo esc_url(site_url('sch-payment')); ?>"><?php esc_html_e( 'Payment', 'wptopschool' ); ?></a></li>
            </ol>
        </section>


        <?php
            if( !empty( $protitle ) ){
                echo '<h2>To use this feature upgrade to pro version</h2>';
            } else {
                do_action( 'wpts_payment_service' );
            }
        wpts_body_end();
        wpts_footer();
    }
    else{
        //Include Login Section
        include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
    }
?>
