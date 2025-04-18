<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if (!$sap_common->sap_is_license_activated()) {
    $redirection_url = '/mingle-update/';
    header('Location: ' . SAP_SITE_URL . $redirection_url);
    die();
}

global $router, $match, $sap_common;

$payment_gateway = array();
$payment_gateway = $this->setting->get_options('payment_gateway');
$stripe_label = $this->setting->get_options('stripe_label');
$default_payment_method = $this->setting->get_options('default_payment_method');
$stripe_test_mode = $this->setting->get_options('stripe_test_mode');
$enable_billing_details = $this->setting->get_options('enable_billing_details');

$plans = $this->get_plans();

$register_data = isset($_SESSION['register_data']) ? $_SESSION['register_data'] : array();
$settings_object = new SAP_Settings();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo empty($settings_object->get_options('mingle_site_name')) ? SAP_NAME : $settings_object->get_options('mingle_site_name'); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php if (!empty($settings_object->get_options('mingle_favicon'))) { ?>

        <link rel="icon" href="<?php echo SAP_IMG_URL . $settings_object->get_options('mingle_favicon'); ?>"
              type="image/png" sizes="32x32">

    <?php } else { ?>

        <link rel="icon" href="<?php echo SAP_SITE_URL . '/assets/images/favicon.png'; ?>" type="image/png"
              sizes="32x32">

    <?php } ?>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/font-awesome.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/curvs-social-auto-poster.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/_all-skins.min.css'; ?>">
    <!-- Login Page CSS -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/curvs-login.css?id=1'; ?>">

    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/style.css'; ?>">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .alert-padding {
            padding: 4px !important;
        }

        .alert-width {
            width: 64% !important;
        }
    </style>
    <!-- Custom Stylesheet Start -->
    <style>
        <?php echo $settings_object->get_options('css_content'); ?>
    </style>
    <!-- Custom Stylesheet End -->
    <script>
        var SAP_SITE_URL = "<?php echo SAP_SITE_URL; ?>";
    </script>
    <?php
    $payment_gateway_arr = !is_array($payment_gateway) ? (array)$payment_gateway : $payment_gateway;
    $implode_payment_gateway_arr = implode(",", $payment_gateway_arr);
    $explode_payment_gateway_arr = explode(",", $implode_payment_gateway_arr);

    if (!empty($explode_payment_gateway_arr) && in_array('stripe', $explode_payment_gateway_arr)) { ?>
        <script src="https://js.stripe.com/v3/"></script>
    <?php } ?>


    <style>
        .plan-cards-container {
            display: flex;
            width: 100%;
            justify-content: center; /* Centers the cards horizontally */
            padding: 10px;
            flex-wrap: wrap; /* Allows wrapping on smaller screens */
        }

        .plan-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px); /* 1/3 width with margin adjustment */
            margin: 10px; /* Adds spacing between cards */
            padding: 20px;
            transition: all 0.3s ease;
            box-sizing: border-box; /* Ensures padding and border are included in width calculation */
            text-align: center; /* Centers content inside the card */
            display: flex;
            flex-direction: column; /* Stacks children vertically */
            justify-content: space-between; /* Ensures spacing between sections */
            height: 100%; /* Ensures cards stretch to the same height */
        }

        .card-body {
            flex: 1; /* Allows the body to grow and fill available space */
            padding: 15px;
            overflow: hidden; /* Prevents content from overflowing */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Aligns content evenly */
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .card-description {
            font-size: 0.9rem;
            color: #666;
            flex: 1; /* Allows the description to take up remaining space */
            overflow: hidden; /* Prevents overflow */
            text-overflow: ellipsis; /* Adds ellipsis for long text */
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limits to 3 lines */
            -webkit-box-orient: vertical;
        }

        .social-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            justify-content: center; /* Centers social icons */
        }

        .social-item img {
            width: 20px;
            margin-right: 8px;
        }

        .plan-card-footer {
            background-color: #f9f9f9;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }

        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
            margin-top: 10px; /* Adds spacing above the price */
        }

        .price::after {
            content: " per month"; /* Adds "per month" after the price */
            font-size: 0.8rem;
            font-weight: normal;
            color: #666;
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .plan-card {
                width: calc(50% - 20px); /* Adjust for smaller screens (2 cards per row) */
            }
        }

        @media (max-width: 480px) {
            .plan-card {
                width: calc(100% - 20px); /* Full width for very small screens (1 card per row) */
            }
        }

        /* مخفی کردن input radio */
        .plan-card input[type="radio"] {
            display: none;
        }

        /* استایل پیش‌فرض برای کارت‌ها */
        .plan-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            cursor: pointer; /* تغییر نشانگر ماوس به pointer */
        }

        /* استایل زمانی که کارت انتخاب شده است */
        .plan-card.selected {
            border-color: #007bff; /* تغییر رنگ border */
            background-color: #f0f8ff; /* تغییر رنگ background */
        }
    </style>
</head>
<body class="hold-transition login-page signup-page">
<!-- login -->
<div class="signup-flex">
    <div class="d-lg-block col-lg-4  bg-plum-plate">
        <div class="login-logo-inner">
            <div class="login-logo">
                    <img src="<?php echo SAP_SITE_URL . '/assets/images/Mingle-Logo.svg'; ?>" class="mingle-logo"/>
            </div>
        </div>
    </div>
    <div class=" d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8  login-box-wrap">
        <div class="signup-box">


            <!-- /.login-logo -->
            <div class="signup-box-body">
                <?php if (!empty($plans)) { ?>
                    <form class="add-member-form" name="new-member" id="add-member" method="POST"
                          enctype="multipart/form-data" action="<?php echo SAP_SITE_URL . '/save_user/'; ?>"
                          novalidate="novalidate">
                        <div class=" box-primary">

                            <div class="signup-error">
                                <?php echo $this->flash->renderFlash(); ?>
                            </div>

                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $sap_common->lang('sign_up'); ?></h3>
                            </div>

                            <input type="hidden" name="sap_role" value="user">
                            <input type="hidden" name="sap_notify" value="yes">

                            <div class="box-body">
                                <div class="row sap-mt-1_5">
                                    <div class="col-md-4 form-group">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('first_name'); ?>
                                                <span class="astric">*</span></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="text" class="form-control" name="sap_firstname"
                                                       value="<?php echo isset($register_data['sap_firstname']) ? $register_data['sap_firstname'] : '' ?>"
                                                       id="sap_firstname"
                                                       placeholder="<?php echo $sap_common->lang('first_name'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('last_name'); ?></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="text" class="form-control" name="sap_lastname"
                                                       id="sap_lastname"
                                                       value="<?php echo isset($register_data['sap_lastname']) ? $register_data['sap_lastname'] : '' ?>"
                                                       placeholder="<?php echo $sap_common->lang('last_name'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('email'); ?>
                                                <span class="astric">*</span></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="text" class="form-control" name="sap_email" id="sap_email"
                                                       value="<?php echo isset($register_data['sap_email']) ? $register_data['sap_email'] : '' ?>"
                                                       placeholder="<?php echo $sap_common->lang('email'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('password'); ?>
                                                <span class="astric">*</span></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="password" class="form-control" name="sap_password"
                                                       id="sap_password" value=""
                                                       placeholder="<?php echo $sap_common->lang('password'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('re_password'); ?>
                                                <span class="astric">*</span></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="password" class="form-control" name="sap_repassword"
                                                       id="sap_repassword" value=""
                                                       placeholder="<?php echo $sap_common->lang('re_password_plh'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($plans)) {

                                    ?>

                                    <div class="plan-cards-container">
                                        <?php
                                        $currency_symbol = $sap_common->get_default_currency_symbol();
                                        foreach ($plans as $key => $plan) {
                                            $unlimited_class = '';
                                            if ($plan->subscription_expiration_days == '' || $plan->subscription_expiration_days == '0') {
                                                $unlimited_class = 'unlimited_plan';
                                            }

                                            if ($plan->price == 0 || $plan->price == '') {
                                                $class = 'price_zero_cls';
                                            } else {
                                                $class = 'price_not_zero_cls';
                                            }

                                            ?>
                                            <div class="plan-card">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="min-height: 50px"><?php echo $plan->name ?></h5>
                                                    <p class="card-description" style="font-size: 0.9rem; color: #666;min-height: 150px">
                                                        <?php foreach (explode('br',$plan->description) as  $desc) { ?>
                                                            <?php echo $desc ?> <br />
                                                        <?php } ?>

                                                    </p>
                                                    <p class="card-text">
                                                    <ul style="list-style: none; padding: 0; display: flex; gap: 15px; justify-content: center;flex-wrap: wrap;min-height: 150px">
                                                        <?php foreach ($this->socialIconsByType($plan->networks) as $socialIconPath) { ?>
                                                            <li class="social-item"
                                                                style="display: flex; align-items: center;">
                                                                <img src="<?php echo $socialIconPath ?>"
                                                                     alt="Social Icon" style="width: 20px;">
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                    </p>
                                                    <input type="radio" class="form-check-input <?php echo $class . ' ' . $unlimited_class ?>" name="sap_plan"
                                                           id="<?php echo $plan->id ?>" value="<?php echo $plan->id ?>"
                                                           style="transform: scale(1.5);">
                                                </div>
                                                <div class="plan-card-footer">
                                                    <span class="price"><?php echo empty($plan->price) ? '0' : $currency_symbol . $plan->price ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>

                                    <div class="row" id="plan_result">
                                    </div>

                                    <div class="row apply_coupon_section">
                                        <div class="col-md-12 form-group">
                                            <div class="row">
                                                <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('apply_coupon'); ?></label>
                                                <div class="col-sm-8 col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input type="text" id="apply_coupon_text"
                                                                   name="apply_coupon" class="form-control"/>
                                                            <input type="hidden" id="apply_coupon_amount"
                                                                   name="apply_coupon_amount" class="form-control"/>
                                                            <input type="hidden" id="coupon_id" name="coupon_id"
                                                                   class="form-control"/>
                                                            <input type="hidden" id="applied_coupon_amount"
                                                                   name="applied_coupon_amount" class="form-control"/>
                                                        </div>
                                                        <div class="col-md-8 ">
                                                            <input type="button"
                                                                   class="btn btn-primary apply_coupon_button"
                                                                   value="Apply Coupon"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 coupon_message pt-6">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row payment_method_cls" style="visibility: hidden;position: relative">
                                        <div class="col-md-12 form-group" style="position: absolute">
                                            <div class="row">
                                                <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('choose_payment'); ?></label>
                                                <div class="col-sm-8 col-md-9 gateway_checkbox">
                                                    <?php

                                                    $stripe_label = !empty($stripe_label) ? $stripe_label : 'Stripe';
                                                    $live_publishable_key = $this->setting->get_options('live_publishable_key');
                                                    $live_secret_key = $this->setting->get_options('live_secret_key');
                                                    $test_publishable_key = $this->setting->get_options('test_publishable_key');
                                                    $test_secret_key = $this->setting->get_options('test_secret_key');

                                                    if ($stripe_test_mode == 'yes') {
                                                        // echo 34; exit;
                                                        $stripe_keys_exist = ($test_publishable_key && $test_secret_key) ? true : false;
                                                    } else {
                                                        $stripe_keys_exist = ($live_publishable_key && $live_secret_key) ? true : false;
                                                    }

                                                    if (!empty($payment_gateway)) {

                                                        $payment_gateway = explode(',', $payment_gateway);

                                                        foreach ($payment_gateway as $data) {
                                                            if ($data != 'stripe' || ($data == 'stripe' && $stripe_keys_exist)) {
                                                                ?>
                                                                <div class="form-check">
                                                                    <input class="form-check-input payment-gateway"
                                                                           type="radio" name="gateway_type"
                                                                           value="<?php echo $data ?>"
                                                                           id="payment_<?php echo $data ?>" <?php if ($data == $default_payment_method) {
                                                                        echo 'checked';
                                                                    } ?>>
                                                                    <label class="form-check-label"
                                                                           for="payment_<?php echo $data ?>">
                                                                        <?php if ($data == 'stripe') {
                                                                            echo $stripe_label;
                                                                        } else {
                                                                            echo ucfirst($data);
                                                                        } ?>
                                                                    </label>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    } else {
                                                        ?>
                                                        <div class="alert alert-danger" role="alert">
                                                            <?php echo $sap_common->lang('signup_payment_help_text'); ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        #terms_and_conditions-error {
                                            color: red !important;
                                        }
                                    </style>
                                    <div class="terms-and-conditions sap-mt-2">
                                        <div class="form-check">
                                            <input style="transform: scale(1.5);margin-right: 10px" class="form-check-input payment-gateway"
                                                   type="checkbox" name="terms_and_conditions"
                                                   value="1"
                                                   id="terms_and_conditions">
                                            <label class="form-check-label"
                                                   for="terms_and_conditions">
                                                <a class="" style="font-size: x-large" target="_blank" href="<?php echo $router->generate('terms_and_conditions') ?>"><?php echo $sap_common->lang('terms_and_conditions'); ?></a>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $payment_gateway_array = !empty($payment_gateway) ? $payment_gateway : array();


                                    if (in_array('stripe', $payment_gateway_array)) {


                                        if ($stripe_test_mode == 'yes' && $stripe_keys_exist) {
                                            ?>

                                            <div class="stripe-payment-fields"
                                                 style="display:<?php if ('stripe' == $default_payment_method) {
                                                     echo 'block';
                                                 } else {
                                                     echo 'none';
                                                 } ?>;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <?php echo sprintf($sap_common->lang('signup_test_help_text'), '<h3 class="panel-title">', '</h3>', '<span>', '</span>'); ?>
                                                            </div>

                                                            <div class="panel-body">
                                                                <?php echo sprintf($sap_common->lang('signup_card_details'), '<p>', '<b>', '</b>', '</p>', '<p>', '<b>', '</b>', '</p>', '<p>', '<b>', '</b>', '</p>', '<p>', '<a href="https://stripe.com/docs/testing#cards" target="_blank">', '</a>', '</p>'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($stripe_keys_exist) { ?>
                                            <div class="stripe-payment-fields">
                                                <input type="hidden" name="action" value="stripe">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h3 class="panel-title"><?php echo $sap_common->lang('signup_payment_details'); ?></h3>
                                                            </div>

                                                            <div class="panel-body">

                                                                <div class="form-group">
                                                                    <div id="stripe-card-element"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div id="response-message" class="col-md-12"></div>

                                        <?php if (!empty($enable_billing_details)) { ?>
                                            <div class="stripe-payment-fields stripe-billing-details"
                                                 style="display:<?php if ('stripe' == $default_payment_method) {
                                                     echo 'block';
                                                 } else {
                                                     echo 'none';
                                                 } ?>;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h3 class="panel-title"><?php echo $sap_common->lang('signup_address_details'); ?></h3>
                                                            </div>
                                                            <ul>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3"><?php echo $sap_common->lang('line1'); ?></label>
                                                                    <input type="text" class="form-control" name="line1"
                                                                           id="line1"
                                                                           placeholder="<?php echo $sap_common->lang('line1'); ?>">
                                                                </li>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3 "><?php echo $sap_common->lang('line2'); ?></label>
                                                                    <input type="text" class="form-control" name="line2"
                                                                           id="line2"
                                                                           placeholder="<?php echo $sap_common->lang('line2'); ?>">
                                                                </li>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3 "><?php echo $sap_common->lang('city'); ?></label>
                                                                    <input type="text" class="form-control" name="city"
                                                                           id="city"
                                                                           placeholder="<?php echo $sap_common->lang('city'); ?>">
                                                                </li>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3 "><?php echo $sap_common->lang('postal_code'); ?></label>
                                                                    <input type="text" class="form-control"
                                                                           name="postal_code" id="postal_code"
                                                                           placeholder="<?php echo $sap_common->lang('postal_code'); ?>">
                                                                </li>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3 "><?php echo $sap_common->lang('state'); ?></label>
                                                                    <input type="text" class="form-control" name="state"
                                                                           id="state"
                                                                           placeholder="<?php echo $sap_common->lang('state'); ?>">
                                                                </li>
                                                                <li>
                                                                    <label class="col-sm-4 col-md-3 "><?php echo $sap_common->lang('country'); ?></label>
                                                                    <input type="text" class="form-control"
                                                                           name="country" id="country"
                                                                           placeholder="<?php echo $sap_common->lang('country'); ?>">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>


                                        <div class="row auto-renew-opt"
                                             style="display:<?php if ('stripe' == $default_payment_method) {
                                                 echo 'block';
                                             } else {
                                                 echo 'none';
                                             } ?>;">
                                            <label class="col-sm-2 col-md-2"><?php echo $sap_common->lang('signup_auto_renew'); ?></label>
                                            <div class="col-sm-8 col-md-9">
                                                <input type="checkbox" class="tgl tgl-ios" name="auto_renew"
                                                       id="auto_renew" value="1">
                                                <label class="tgl-btn float-right-cs-init" for="auto_renew"></label>
                                            </div>

                                        </div>
                                    <?php }
                                } ?>

                                <?php
                                if (!empty($payment_gateway)){ ?>
                                <div class="col-md-12 form-group sap-mt-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="row">
                                                <input type="hidden" name="form-submitted" value="1">
                                                <button type="submit" name="sap_add_member_submit"
                                                        class="btn btn-primary"><?php echo $sap_common->lang('signup_register_btn'); ?></button>
                                            </div>
                                        </div>
                                        <div class="sign_up-right log-in col-md-6">Already have an account? <a
                                                    class="text-center login-link backtologin"
                                                    href="<?php echo SAP_SITE_URL ?>"><?php echo $sap_common->lang('back_to_login_text'); ?></a>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php } ?>
                        </div>
                    </form>
                <?php } else {
                    ?>
                    <form class="add-member-form">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="signup-error">No Membership plans available for sign up.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>

            <?php
            unset($_SESSION['register_data']);
            unset($register_data);
            ?>
            <!-- /.signup-box-body -->
        </div>
    </div>
</div>
</body>
<!-- jQuery 3 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap.min.js'; ?>"></script>
<script src="<?php echo SAP_SITE_URL . '/assets/js/jQuery-validate/jquery.validate.js' ?>"></script>
<script src="<?php echo SAP_SITE_URL . '/assets/js/curvs-login.js?id=1'; ?>"></script>
<?php
$stripe_test_mode = $this->setting->get_options('stripe_test_mode');
// IF send box enabled
if ($stripe_test_mode == 'yes') {
    $publish_key = $this->setting->get_options('test_publishable_key');
} else {
    $publish_key = $this->setting->get_options('live_publishable_key');
}
?>

<script type="text/javascript">
    var stripe_publishable_key = "<?php echo $publish_key;?>";
    var disc_amount = "<?php echo $sap_common->lang('discount_amount'); ?>";
    var payable_amount = "<?php echo $sap_common->lang('payable_amount'); ?>";
    var coupon_error_message = "<?php echo $sap_common->lang('enter_coupon_code'); ?>";

</script>
<?php
$implode_payment_gateway_arr = implode(",", $payment_gateway_arr);
$explode_payment_gateway_arr = explode(",", $implode_payment_gateway_arr);
if (!empty($explode_payment_gateway_arr) && in_array('stripe', $explode_payment_gateway_arr)) { ?>
    <script type="text/javascript" src="<?php echo SAP_SITE_URL . '/assets/js/stripe-processing.js' ?>"></script>
<?php } ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const planCards = document.querySelectorAll('.plan-card');

        planCards.forEach(card => {
            card.addEventListener('click', function() {
                // حذف کلاس selected از همه کارت‌ها
                planCards.forEach(c => {
                    c.classList.remove('selected');
                });

                // اضافه کردن کلاس selected به کارت کلیک‌شده
                this.classList.add('selected');

                // انتخاب radio input مربوط به این کارت
                const radioInput = this.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                }
            });
        });
    });
</script>
</body>
</html>
