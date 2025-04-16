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
<div class="max-w-screen-lg mx-auto my-12 px-6 py-12 shadow-2xl rounded-lg bg-white">
    <!-- Title Section -->
    <header class="text-center border-b border-blue-500 pb-4">
        <h1 class="text-2xl font-bold">Creating a Twitter Application</h1>
    </header>

    <!-- Introduction -->
    <section class="mt-6">
        <p class="text-lg text-center">
            To create a Twitter App, please visit:
            <a class="text-blue-600 underline" href="https://developer.twitter.com/" aria-label="Twitter Developer Website">https://developer.twitter.com/</a>.
            You need to log in to the developer site using your Twitter account.
        </p>
    </section>

    <!-- Main Content -->
    <main class="mt-12 space-y-8">
        <!-- Step 1 -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Step 1: Register Your Developer Account</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    After logging in, your Twitter developer account will be registered, and you will be redirected to the Twitter Developer Dashboard.
                    Here, click on the Default App settings to update your Twitter developer app settings.
                </p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/Twitter-Developers.webp" alt="Twitter Developer Dashboard" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>

        <!-- Step 2 -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Step 2: Configure Authentication Settings</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    This setting allows you to configure your Twitter developer application's authentication settings.
                    Click on the "Set Up" button (as shown in the screenshot below) to proceed with the configuration.
                </p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/Twitter-Developers-Dashboard.webp" alt="Twitter Developer Authentication Settings" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>

        <!-- Step 3 -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Step 3: Set App Permissions</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    For the app permissions, select the appropriate settings based on your use case:
                </p><ul class="list-disc list-inside mt-2 space-y-1">
                    <li><strong>Social Auto Poster:</strong> Select "Read &amp; Write" permission for posting with the Twitter API.</li>
                    <li><strong>Social Login:</strong> Select "Read" permission and enable the option to request the user's email.</li>
                    <li>For the app type, select "Native" if using Social Auto Poster.</li>
                    <li>Input your website URL in both the Callback URL and Website URL fields for Social Auto Poster.</li>
                    <li>For Social Login, insert the redirect URL in the Callback URL field.</li>
                </ul>
                <p></p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/Twitter-Developer-APP-Oauth.webp" alt="Twitter App Permissions" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>

        <!-- Step 4 -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Step 4: Generate API Keys</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    To generate API keys for your app, go back to the app dashboard and click on "Keys and Tokens."
                    This will allow you to set up API keys and secrets for your developer app.
                </p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/Twitter-API-Setup.webp" alt="Twitter API Setup" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>

        <!-- Step 5 -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Step 5: Save API Keys</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    In the "Keys and Tokens" section, you can create an API key, Bearer Token, and Access Tokens.
                    Depending on your use case:
                </p><ul class="list-disc list-inside mt-2 space-y-1">
                    <li><strong>Social Auto Poster:</strong> Use the API Key, Secret, Access Tokens, and Secret.</li>
                    <li><strong>Social Login:</strong> Use the API Key and Secret.</li>
                </ul>
                Click "Generate" to create the tokens and save them in the appropriate fields for Social Auto Poster or Social Login.
                <p></p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/Twitter-API-Key.webp" alt="Twitter API Keys" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>

        <!-- Final Step -->
        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Final Step: Insert Keys</h2>
            <div class="p-6 border rounded-lg shadow-md">
                <p class="text-lg leading-relaxed">
                    Once you have generated and saved the keys and secrets, insert them into the appropriate fields in Social Auto Poster or Social Login.
                </p>
                <img src="<?php echo SAP_SITE_URL ?>/assets/images/twitter-page/social-network-integration.webp" alt="Social Network Integration" class="w-full h-auto mt-4 rounded-lg">
            </div>
        </section>
    </main>
</div>
</body>
<!-- jQuery 3 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap.min.js'; ?>"></script>
</body>
</html>