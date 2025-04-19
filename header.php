<?php
/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $router, $match;
$user_id = sap_get_current_user_id();
if( !empty( $user_id ) && isset( $this->settings ) && !empty( $this->settings ) ){
    $user_options = $this->settings->get_user_setting('sap_general_options', $user_id);



    $timezone = (!empty($user_options['timezone']) ) ? $user_options['timezone'] : ''; // user timezone

    //Update time zone based on user setting
    if (!empty($timezone)) { // set default timezone
        date_default_timezone_set($timezone);
    }
}
$settings_object      = new SAP_Settings();
$header_content = $settings_object->get_options('header_content');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if( !empty( $settings_object->get_options('mingle_meta_title') ) ) { ?>
        <meta name="title" content="<?php echo $settings_object->get_options('mingle_meta_title'); ?>">
    <?php } ?>
    <?php if( !empty( $settings_object->get_options('mingle_meta_description') ) ) { ?>
        <meta name="description" content="<?php echo $settings_object->get_options('mingle_meta_description'); ?>">
    <?php } ?>
    <title>
        <?php
        $htmlDocTitle = str_replace('_', ' ', $match['name'] ?? '');
        $htmlDocTitle = $htmlDocTitle == 'CPG' ? 'Crawlers History' : $htmlDocTitle;
        $htmlDocTitle = ucwords($htmlDocTitle);

        ?>
        <?php echo $settings_object->get_options('mingle_site_name')." - ".$htmlDocTitle; ?>
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
    if(!empty($settings_object->get_options('mingle_favicon') )) {?>

        <link rel="stylesheet" href="assets/css/header.css">
        <link rel="icon" href="<?php echo SAP_IMG_URL . $settings_object->get_options('mingle_favicon'); ?>" type="image/png" sizes="32x32">
    <?php }else{?>
        <link rel="icon" href="<?php echo SAP_SITE_URL . '/assets/images/favicon.png'; ?>" type="image/png" sizes="32x32">
    <?php } ?>


    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/bootstrap.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/bootstrap-datetimepicker.css'; ?>">
    <?php
    if ($match['name'] == 'report' ) {?>
        <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/bootstrap-datepicker.min.css'; ?>">
        <?php
    } ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/font-awesome.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/curvs-social-auto-poster.min.css'; ?>">

    <?php

    if ($match['name'] == 'addpost' || $match['name'] == 'viewpost' ||  $match['name'] == 'settings' || $match['name'] == 'quick_posts' || $match['name'] == 'quick_posts_with_id'  || $match['name'] == 'quick_viewpost' || $match['name'] == 'general_settings'  || $match['name'] == 'quick_posts_add'  || $match['name'] == 'quick_posts_add_from_cpg') {
        echo '<link rel="stylesheet" href="'.SAP_SITE_URL.'/assets/css/select2.min.css">';
        echo '<link rel="stylesheet" href="'.SAP_SITE_URL.'/assets/css/fileinput.css">';
    } ?>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/_all-skins.min.css'; ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/flat/blue.css'; ?>">
    <!-- bootstrap wysihtml5 - text editor -->

    <!-- style -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/style.css?id=6'; ?>">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- dataTable Start -->
    <link rel="stylesheet" type="text/css" href="<?php echo SAP_SITE_URL . '/assets/dataTables/css/dataTables.bootstrap.min.css'; ?>">
    <!-- dataTable End -->

    <!-- Custom Stylesheet Start -->
    <style>
        <?php echo $settings_object->get_options('css_content'); ?>
    </style>
    <!-- Custom Stylesheet End -->
    <?php

    if (strpos($match['name'],'crawlers') !== false) {
        echo '<link rel="stylesheet" href="'.SAP_SITE_URL.'/assets/css/crawler.css">';
    }

    echo $header_content;

    $sap_general_options = $settings_object->get_options('sap_general_options');
    if( !empty($sap_general_options['timezone']) ){
        echo '<script> var SapTimeZone = "'.$sap_general_options['timezone'].'"; </script>';
    }else {
        echo '<script> var SapTimeZone = "'.date_default_timezone_get().'"; </script>';
    } ?>

    <script>
        var SAP_SITE_URL = "<?php echo SAP_SITE_URL; ?>";
    </script>
</head>

<style>
    .nav-item {
        .tooltip-text {
            display: none;
        }
    }
    @media screen and (max-width: 767px){
        .nav-item {
            span {
                display: none;
            }
            i {
                font-size: 24px;
            }
        }
        .nav-item:hover {
            .tooltip-text {
                padding: 0 10px;
                display: block;
                font-size: 16px;
                background-color: #3f3737;
                color: #fff;
                text-align: center;
                border-radius: 6px;
                position: absolute;
                top: 50px;
                z-index: 1;
            }
        }
    }
</style>

<?php $user_details = isset( $_SESSION['user_details'] ) ? $_SESSION['user_details'] : array();
$user_class = ($user_details['role'] == 'user') ? 'mingle-user' : 'mingle-admin';
?>

<body class="hold-transition skin-blue sidebar-mini <?php echo $user_class; ?>">
<div class="wrapper">
    <?php sap_check_user_payment_status() ?>
    <header class="main-header">
        <div class="pull-left image logo-class">
            <img id="logo-img" src="http://localhost/Curvs/assets/images/Curvs12.png">
        </div>
        <!-- Logo -->
        <!-- <a href="<?php echo SAP_SITE_URL; ?>/posts/" class="logo sap-logo-text">
            <span class="logo-mini"><b>SAP</b></span>
            <span class="logo-lg"><?php echo empty( $settings_object->get_options('mingle_site_name') ) ? SAP_NAME : $settings_object->get_options('mingle_site_name'); ?></span>
        </a> -->
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- My Account Link -->
                    <li class="nav-item">
                        <a href="<?php echo SAP_SITE_URL . '/my-account/'; ?>" class="nav-link">
                            <i class="fa fa-user-circle"></i>
                            <span>
                                    My Account
                                </span>
                            <div class="tooltip-text" style="left: -10px">
                                My Account
                            </div>
                        </a>
                    </li>
                    <!-- Upgrade Button -->
                    <li class="nav-item">
                        <a href="<?php echo $router->generate('your-subscription'); ?>" class="nav-link">
                            <i class="fa fa-diamond"></i>
                            <span>
                                    Subscription
                                </span>
                            <div class="tooltip-text" style="left: -15px">
                                subscription
                            </div>
                        </a>
                    </li>
                    <!-- Signout Link -->
                    <li class="nav-item">
                        <a href="<?php echo SAP_SITE_URL . '/logout/'; ?>" class="nav-link">
                            <i class="fa fa-sign-out"></i>
                            <span>
                                    Signout
                                </span>
                            <div class="tooltip-text" style="left: -30px">
                                Signout
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>


        <!--            <nav class="navbar navbar-static-top">-->
        <!--            <!-- Sidebar toggle button-->
        <!--            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">-->
        <!--                <span class="sr-only">Toggle navigation</span>-->
        <!--            </a>-->
        <!---->
        <!--            <div class="navbar-custom-menu">-->
        <!--                <ul class="nav navbar-nav">-->
        <!--                    <li class="dropdown user user-menu">-->
        <!--                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown" >-->
        <!--                            <i class="fa fa-user-circle"></i>-->
        <!--                            <span class="hidden-xs">--><?php //echo $user_details['first_name'] . ' ' . $user_details['last_name']; ?><!--</span>-->
        <!--                            <i class="fa fa-angle-down m-l-1"></i>-->
        <!--                        </a>-->
        <!--                        <ul class="dropdown-menu dropdown-menu-right">-->
        <!--                          <li class="dropdown-item"><a href="--><?php //echo SAP_SITE_URL . '/my-account/'; ?><!--"><i class="fa fa-users"></i>My Account</a></li>-->
        <!--                          <li class="dropdown-item"><a href="--><?php //echo SAP_SITE_URL . '/logout/'; ?><!--"><i class="fa fa-sign-out"></i>Signout</a></li>-->
        <!--                        </ul>-->
        <!--                    </li>-->
        <!--                </ul>-->
        <!--            </div>-->
        <!--        </nav>-->
    </header>
</div>
</body>
</html>