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
    <link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/twitter-page.css'; ?>">
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
<div class="w-[1400px] mx-auto shadow-2xl rounded-lg p-6 my-12 bg-white">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Creating a YouTube Application</h1>
    <p class="text-center text-gray-600 mb-6">
        To get a YouTube Application detail, go and visit:
        <a class="text-blue-500 hover:underline" href="https://console.developers.google.com/project">Google Developers Console</a>
    </p>

    <div class="space-y-8 ">
        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                You need to be logged in to your YouTube account to get the YouTube Application ID and channel secret. First, log in to your YouTube account and follow the link above to access the developer page. Click on the "Create Project" button.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/google-step1.webp" alt="Step 1">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                After clicking "Create Project", the project screen opens. Enter the Project Name, and the Project ID will be auto-generated. Click on the "Create" button to create the project.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/1-2.webp" alt="Step 2">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                After creating the project, you will be redirected to the project listing page. Click on the Project name.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/google-step3.webp" alt="Step 3">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Now, enable the YouTube Data API V3. Click on APIs & Services, then find and click on Library.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/2-2.webp" alt="Step 4">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                In the Library, enter "YouTube" in the search bar. You will find YouTube Data API v3 in the suggestion box. Click on it.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/3-2.webp" alt="Step 5">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Click on the YouTube Data API v3, and then click on the "Enable" button.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/4-3.webp" alt="Step 6">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Click on the "Credentials" menu and select the "OAuth consent screen" tab. Choose the user type and click on "Create".
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/google-step7.webp" alt="Step 7">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                After saving the "OAuth consent screen", you will be redirected to the scope. Click on Save and continue, then add test users.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/google-step8.webp" alt="Step 8">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Now enter the application type. Based on the Application type, fill in the data and click on "Create". You will find the Client ID and Secret Key in a pop-up.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/Client-Id-and-Sceret-screen-for-Youtube.webp" alt="Step 9">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Copy the Client ID and client secret to the YouTube settings within the Plugin settings page. Once you save the settings, it will display a valid OAuth redirect URI.
            </p>
            <div class="md:w-1/2">
                <img class="rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/Youtbe-10-1.webp" alt="Step 10">
                <img class="rounded-lg mt-2" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/Youtube-9-1.webp" alt="Step 11">
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                Go to the developer dashboard > credentials (in the side menu bar) > select the app from the dropdown next to the Google Cloud logo. Click on the project name.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/youtube-redirect-URL.webp" alt="Step 12">
        </div>

        <div class="flex flex-col md:flex-row items-center p-4 border border-blue-500 rounded-lg bg-blue-50">
            <p class="md:w-1/2 text-lg text-gray-700 leading-7 md:mr-4 mb-4 md:mb-0">
                After entering the redirect URI, click on the "Grant Extended Permission" button within the plugin’s settings page and save.
            </p>
            <img class="md:w-1/2 rounded-lg" src="<?php echo SAP_SITE_URL ?>/assets/images/youtube-page/Youtube-12-3.webp" alt="Step 13">
        </div>
    </div>
</div>
</body>
<!-- jQuery 3 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap.min.js'; ?>"></script>
</body>
</html>