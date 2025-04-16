<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $router, $match, $sap_common;
$settings_object      = new SAP_Settings();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo empty( $settings_object->get_options('mingle_site_name') ) ? SAP_NAME : $settings_object->get_options('mingle_site_name'); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php if(!empty($settings_object->get_options('mingle_favicon') )) {?>

        <link rel="icon" href="<?php echo SAP_IMG_URL . $settings_object->get_options('mingle_favicon'); ?>" type="image/png" sizes="32x32">
    <?php }else{?>
        <link rel="icon" href="<?php echo SAP_SITE_URL . '/assets/images/favicon.png'; ?>" type="image/png" sizes="32x32">
    <?php } ?>

    <!-- Terms And Conditions Page CSS -->
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/terms-and-conditions.css'; ?>">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Custom Stylesheet Start -->
    <style>
        <?php echo $settings_object->get_options('css_content'); ?>
    </style>
    <!-- Custom Stylesheet End -->
</head>
<body class="hold-transition login-page">
<!-- login -->
<div class="container">
    <header>
        <h1>Terms and Conditions of Use for Curvs.ai</h1>
        <p><strong>Effective Date:</strong> March 2025</p>
    </header>

    <section>
        <p>
            These Terms of Use constitute a legal agreement between you ("User") and Curvs.ai ("we," "us," or "our"), governing your access to and use of our services. Please read these terms carefully. By using Curvs.ai, you agree to comply with these terms.
        </p>
    </section>

    <section>
        <h2>1. Definitions and General Terms</h2>
        <ul>
            <li><strong>User Account:</strong> An account created by you to access our services.</li>
            <li><strong>Authorized Users:</strong> Individuals authorized by you to use your account on our platform.</li>
            <li><strong>Content:</strong> Any information, data, files, text, images, videos, audio, or any other data submitted by users on the platform.</li>
            <li><strong>Services:</strong> All services provided by Curvs.ai, including AI-powered content creation and optimization.</li>
            <li><strong>Subscriptions:</strong> Paid plans required to access certain premium services.</li>
        </ul>
    </section>

    <section>
        <h2>2. Registration and Access to Services</h2>
        <ul>
            <li>You must create an account and provide accurate information to use our services.</li>
            <li>You are responsible for maintaining the security of your account and must not share login credentials with others.</li>
            <li>We reserve the right to suspend or delete accounts that are suspicious or violate our policies.</li>
        </ul>
    </section>

    <section>
        <h2>3. Fees and Subscriptions</h2>
        <ul>
            <li>Some services require payment on a monthly or yearly basis.</li>
            <li>All payments are non-refundable.</li>
            <li>Failure to make timely payments may result in account suspension or restrictions.</li>
        </ul>
    </section>

    <section>
        <h2>4. User Rights and Responsibilities</h2>
        <ul>
            <li>Users must comply with all applicable laws and regulations.</li>
            <li>Publishing prohibited content, including offensive, hateful, or rights-infringing materials, is not allowed.</li>
            <li>Any attempt to misuse our services or breach our systems is strictly forbidden.</li>
        </ul>
    </section>

    <section>
        <h2>5. Intellectual Property and User Content</h2>
        <ul>
            <li>All intellectual property, including software, design, trademarks, and other proprietary elements of Curvs.ai, belong to us.</li>
            <li>Users retain ownership of their submitted content but grant Curvs.ai permission to use it for service improvement.</li>
            <li>We reserve the right to remove any content that violates our policies.</li>
        </ul>
    </section>

    <section>
        <h2>6. Security and Privacy</h2>
        <ul>
            <li>User data is protected in accordance with our Privacy Policy.</li>
            <li>We do not share personal data with third parties without user consent.</li>
        </ul>
    </section>

    <section>
        <h2>7. Limitation of Liability</h2>
        <ul>
            <li>Curvs.ai is not responsible for any direct or indirect damages resulting from the use of our services.</li>
            <li>Services are provided "as is," with no guarantees of uninterrupted or error-free performance.</li>
        </ul>
    </section>

    <section>
        <h2>8. Changes to the Terms</h2>
        <ul>
            <li>We reserve the right to update these terms at any time.</li>
            <li>Continued use of the services after updates constitutes acceptance of the new terms.</li>
        </ul>
    </section>

    <section>
        <h2>9. Contact Us</h2>
        <p>For any inquiries, please contact us at <a href="mailto:support@curvs.ai">support@curvs.ai</a></p>
    </section>

    <footer>
        <p>© 2025 Curvs.ai. All rights reserved.</p>
    </footer>
</div>
</body>
<!-- jQuery 3 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap.min.js'; ?>"></script>
</body>
</html>