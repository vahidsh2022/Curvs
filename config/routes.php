<?php
include(SAP_APP_PATH . 'Lib' . DS . 'Routes' . DS . 'Router.php');

$router = new Router();
$GLOBALS['router'] = $router;
if (SAP_BASEPATH != '/') {
    $router->setBasePath(SAP_BASEPATH);
} else {
    $router->setBasePath(''); // Fix blank page issue after installation
}

//Route 
$router->map('GET|POST', '/terms-and-conditions', 'Users#termsAndConditions', 'terms_and_conditions');
$router->map('GET', '/twitter-page', 'Users#twitterPage', 'twitter_page');
$router->map('GET', '/youtube-page', 'Users#youtubePage', 'youtube_page');
$router->map('GET|POST', '/', 'Users#login', 'login');
$router->map('POST', '/login_user/', 'Users#login_user', 'login_user');
$router->map('GET|POST', '/logout/', 'Users#logout', 'logout');
$router->map('GET|POST', '/my-account/', 'Users#my_account', 'my_account');
$router->map('GET|POST', '/my-account/update', 'Users#update_user', 'update_user');
$router->map('GET|POST', '/forgot-password/', 'Users#forgot_password', 'forgot_password');
$router->map('GET|POST', '/signup/', 'Users#user_signup', 'user_signup');
$router->map('GET|POST', '/check_coupon_code/', 'Users#check_coupon_code', 'check_coupon_code');
$router->map('GET|POST', '/get_stripe_data/', 'Users#get_stripe_data', 'get_stripe_data');
$router->map('GET|POST', '/save_user/', 'Users#save_user', 'save_user');
$router->map('GET|POST', '/thank-you/[i:id]', 'Users#thank_you', 'thank-you');
$router->map('GET|POST', '/upgrade-thank-you/[i:id]', 'Users#upgrade_thank_you', 'upgrade-thank-you');
$router->map('GET|POST', '/resend-email/[i:id]', 'Users#resend_verification_email', 'resend-verification-email');

$router->map('GET|POST', '/forgot-password-process/', 'Users#forgot_password_process', 'forgot_password_process');
$router->map('GET|POST', '/reset-password/', 'Users#reset_password', 'reset_password');
$router->map('GET|POST', '/reset-password-process/', 'Users#reset_password_process', 'reset_password_process');

$router->map('GET|POST', '/payment/', 'Payment#payment', 'payment');
$router->map('GET|POST', '/payment/upgrade/', 'Payment#payment', 'payment_upgrade');
$router->map('GET|POST', '/make_payment/', 'Payment#make_payment', 'make_payment');
$router->map('GET|POST', '/re-payment/', 'Payment#re_payment', 're-payment');
$router->map('GET|POST', '/plan_details/', 'Payment#plan_details', 'plan_details');
$router->map('GET|POST', '/subscription-stripe/', 'Payment#subscription_webhook', 'subscription_webhook');
$router->map('GET|POST', '/plan-proration-credit/', 'Payment#plan_proration_credit', 'plan-proration-credit');
$router->map('GET|POST', '/payment/subscription/', 'Payment#subscription', 'payment-subscription');
$router->map('GET|POST', '/payment-page/', 'Payment#payment', 'back-payment-page');
$router->map('GET|POST', '/expire-membership-cron/', 'Payment#cron_to_expire_membership', 'cron_to_expire_membership');

//Posts Routes
$router->map('GET', '/posts/', 'Posts#index', 'posts');
$router->map('GET|POST', '/add-new-post/', 'Posts#add_new_post', 'addpost');
$router->map('GET|POST', '/posts/view/[i:id]', 'Posts#view_post', 'viewpost');
$router->map('GET|POST', '/post/update/', 'Posts#update_post', 'updatepost');
$router->map('POST', '/post/delete/', 'Posts#delete_post', 'deletepost');
$router->map('POST', '/post/delete_multiple/', 'Posts#delete_multiple_post', 'deletemultiple');
$router->map('GET|POST', '/edit-post/', 'Posts#edit', 'editpost');
$router->map('POST', '/posts/save/', 'Posts#save_post', 'save_post');

//Settings Routes
$router->map('GET', '/settings/', 'Settings#view', 'settings');
$router->map('POST', '/settings/save/', 'Settings#save_all_settings', 'save_all_settings');

$router->map('POST', '/posts/reset_post_status/', 'Posts#reset_post_status', 'reset_post_status');


//Settings Routes
$router->map('GET', '/smtp-settings/', 'Settings#smtp_settings', 'smtp_settings');
$router->map('GET', '/general-settings/', 'Settings#general_settings', 'general_settings');
$router->map('GET|POST', '/save_stripe_settings/', 'Settings#save_stripe_settings', 'save_stripe_settings');

// Logs Routes
$router->map('GET', '/logs/', 'Logs#index', 'logs');
$router->map('POST', '/log/delete/', 'Logs#delete_log', 'deletelog');
$router->map('POST', '/log/log_view_details/', 'Logs#log_view_details', 'logviewdetails');
$router->map('POST', '/log/delete_multiple/', 'Logs#delete_multiple_logs', 'deletemultiplelogs');
$router->map('POST', '/log/sap_poster_logs_graph/', 'Logs#sap_poster_logs_graph', 'sap_poster_logs_graph');

$router->map('GET', '/report/', 'Logs#report', 'report');
$router->map('GET', '/debug/', 'Debug#debug', 'debug');
$router->map('POST', '/debug/clear/', 'Debug#clear', 'clear');
$router->map('POST', '/settings/sap_auto_poster_pinterest_add_accounts/', 'Settings#sap_auto_poster_pinterest_add_accounts', 'sapautoposterpinterestaddaccounts');


//Payment List
$router->map('GET', '/payments/', 'Payments#index', 'payments');
$router->map('GET', '/payments-ajax/', 'Payments#payments_datasource_response', 'payment_ajax_list');
$router->map('GET', '/user-payments-ajax/', 'Payments#user_payments_datasource_response', 'user_payment_ajax_list');
$router->map('GET|POST', '/payment-invoice/[i:id]', 'Payments#user_payments_payment_invoice', 'user_payments_payment_invoice');
$router->map('GET|POST', '/payments/payment_delete/', 'Payments#delete_payments', 'delete_payments');
$router->map('POST', '/payments/delete_multiple/', 'Payments#delete_multiple_payment', 'delete_multiple_payment');

$router->map('GET|POST', '/payments/add-payment/', 'Payments#add_payment', 'add-payment');
$router->map('GET|POST', '/payments/save/', 'Payments#save_payment', 'save-payment');
$router->map('GET|POST', '/payments/edit/[i:payment_id]', 'Payments#edit_payment', 'edit-payment');
$router->map('GET|POST', '/payments/update/', 'Payments#update_payment', 'update-payment');
$router->map('GET|POST', '/payments/get_user_membership_details/', 'Payments#get_user_membership_details', 'get_user_membership_details');


//Quick Posts Routes
$router->map('GET', '/quick-post/', 'Quick_Posts#index', 'quick_posts');
$router->map('GET', '/users-quick-post/', 'Quick_Posts#users', 'quick_posts_users');
$router->map('GET', '/quick-post/detail/[i:id]', 'Quick_Posts#detail', 'quick_posts_detail');
$router->map('GET','/quick-post/add/','Quick_Posts#add','quick_posts_add');
$router->map('GET','/quick-post/add_from_cpg/[i:cpg_id]','Quick_Posts#add_from_cpg','quick_posts_add_from_cpg');
$router->map('GET', '/quick-post/[i:id]', 'Quick_Posts#index', 'quick_posts_with_id');
$router->map('POST', '/quick-post/save/', 'Quick_Posts#save_post', 'quick_save_post');
$router->map('POST', '/quick-post/delete/', 'Quick_Posts#delete_post', 'quick_deletepost');
$router->map('POST', '/quick-post/delete_multiple/', 'Quick_Posts#delete_multiple_post', 'quick_delete_multiple');
$router->map('GET|POST', '/quick-post/view/[i:id]', 'Quick_Posts#view_post', 'quick_viewpost');
$router->map('GET|POST', '/quick-post/update/', 'Quick_Posts#update_post', 'quick_updatepost');
$router->map('GET|POST', '/quick-post/sap_generate_caption/', 'Quick_Posts#sap_generate_caption', 'sap_generate_caption');
$router->map('GET','/quick-post/json/','Quick_Posts#get_posts_by_status_json','quick_post_by_status_json');

//Quick Posts Routes
$router->map('GET', '/mingle-update/', 'Mingle_Update#index', 'mingle_update');
$router->map('POST', '/mingle-update/save_process/', 'Mingle_Update#save_process', 'save_process');
$router->map('POST', '/mingle-update/check_update/', 'Mingle_Update#check_update', 'check_update');
$router->map('POST', '/mingle-update/version_updating/', 'Mingle_Update#version_updating', 'version_updating');
$router->map('POST', '/mingle-update/version_compress/', 'Mingle_Update#version_compress', 'version_compress');

// Members Routes
$router->map('GET', '/members/', 'Members#index', 'member_list');
$router->map('GET', '/members-ajax/', 'Members#members_datasource_response', 'member_ajax_list');
$router->map('GET|POST', '/member/add/', 'Members#add_new_member', 'add_member');
$router->map('GET|POST', '/member/save/', 'Members#save_member', 'save_member');
$router->map('GET|POST', '/member/edit/[i:id]', 'Members#edit_member', 'edit_member');
$router->map('GET|POST', '/member/update/', 'Members#update_member', 'update_member');
$router->map('GET|POST', '/member/delete/', 'Members#delete_member', 'delete_member');
$router->map('POST', '/member/delete_multiple/', 'Members#delete_multiple_member', 'delete_multiple_member');
$router->map('POST', '/member/searchByStatus/', 'Members#searchByStatus', 'searchByStatus');
$router->map('GET|POST', '/email/[*]', 'Members#member_email_verification', 'member-email-verification');

$router->map('GET|POST', '/member/profile/[i:id]', 'Members#profile_member', 'profile_member');


//Membership Routes
$router->map('GET', '/membership/', 'Membership#index', 'membership_list');
$router->map('GET', '/membership-ajax/', 'Membership#membership_datasource_response', 'membership_ajax_list');
$router->map('GET|POST', '/membership/add/', 'Membership#add_new_membership', 'add_membership');
$router->map('GET|POST', '/membership/save/', 'Membership#save_membership', 'save_membership');
$router->map('GET|POST', '/membership/edit/[i:id]', 'Membership#edit_membership', 'edit_membership');
$router->map('GET|POST', '/membership/update/', 'Membership#update_membership', 'update_membership');
$router->map('GET|POST', '/cancel-user-membership/[i:user_id]', 'Membership#cancle_user_membership', 'cancle-user-membership');
$router->map('GET|POST', '/expire-user-membership/[i:user_id]', 'Membership#expire_user_membership', 'expire-user-membership');



// Your subscription routes
$router->map('GET|POST', '/subscription/', 'Members#your_subscription', 'your-subscription');

// Plans Routes
$router->map('GET', '/plans/', 'Plans#index', 'plan_list');
$router->map('GET', '/plan-ajax/', 'Plans#plan_datasource_response', 'plan_ajax_list');
$router->map('GET|POST', '/plan/add/', 'Plans#add_new_plan', 'add_plan');
$router->map('GET|POST', '/plan/edit/[i:id]', 'Plans#edit_plan', 'edit_plan');
$router->map('GET|POST', '/plan/save/', 'Plans#save_plan', 'save_plan');
$router->map('GET|POST', '/plan/update/', 'Plans#update_plan', 'update_plan');
$router->map('GET|POST', '/plan/delete/', 'Plans#delete_plan', 'delete_plan');
$router->map('POST', '/plan/delete_multiple/', 'Plans#delete_multiple_plan', 'delete_multiple_plan');
$router->map('POST', '/plan/plan-expiry-date/', 'Plans#get_plan_expiry_date', 'get_plan_expiry_date');

//Coupons Routes
$router->map('GET', '/coupons/', 'Coupons#index', 'coupons');
$router->map('GET', '/coupons-ajax/', 'Coupons#coupons_datasource_response', 'coupon_ajax_list');
$router->map('GET|POST', '/coupons/coupon_delete/', 'Coupons#delete_coupons', 'delete_coupons');
$router->map('POST', '/coupons/delete_multiple/', 'Coupons#delete_multiple_coupon', 'delete_multiple_coupon');

$router->map('GET|POST', '/coupons/add-coupon/', 'Coupons#add_coupon', 'add-coupon');
$router->map('GET|POST', '/coupons/save/', 'Coupons#save_coupon', 'save-coupon');
$router->map('GET|POST', '/coupons/edit/[i:coupon_id]', 'Coupons#edit_coupon', 'edit-coupon');
$router->map('GET|POST', '/coupons/update/', 'Coupons#update_coupon', 'update-coupon');

$router->map('POST', '/set_payment_intent/', 'Payment#set_payment_intent', 'set_payment_intent');
$router->map('POST', '/set_payment_intent_user/', 'Payment#set_payment_intent_user', 'set_payment_intent_user');

$router->map('POST', '/wordpress-add-site/', 'Wordpress#wordpress_add_site', 'wordpress_add_site');
$router->map('POST', '/wordpress-delete-site/', 'Wordpress#wordpress_delete_site', 'wordpress_delete_site');

$router->map('POST', '/fetch-reddit-flair/', 'Reddits#fetch_reddit_flair', 'fetch_reddit_flair');

$router->map('GET|POST', '/upgrade-process', 'Updates#upgrade_database', 'upgrade_database');
$router->map('GET|POST', '/process-upgrade/', 'Updates#process_upgrade', 'process_upgrade');

///> sample of routes
$router->map('GET|POST', '/simple/rest/', 'Rest#index', 'rest');
$router->map('GET|POST', '/simple/page/', 'Page#index', 'page');


///> sample of routes
$router->map('GET', '/crawlers/', 'Crawlers#index', 'crawlers');
$router->map('GET', '/crawlers/list', 'Crawlers#list', 'crawlers_list');
$router->map('GET|POST', '/crawlers/add', 'Crawlers#add', 'crawlers_add');
$router->map('GET|POST', '/crawlers/edit/[i:id]', 'Crawlers#edit', 'crawlers_edit');
$router->map('GET', '/crawlers/pendings/', 'Crawlers#pendings', 'crawlers_pendings');
$router->map('GET', '/crawlers/pendings/list/', 'Crawlers#pending_list', 'crawlers_pending_list');
$router->map('GET', '/crawlers/pendings/active/[i:id]', 'Crawlers#pendings_active', 'crawlers_pendings_active');

///>
$router->map('GET', '/cpg/', 'CPG#index', 'CPG');
$router->map('POST', '/cpg/add/', 'CPG#add', 'CPG_add');
$router->map('GET', '/cpg/[i:id]/show/', 'CPG#show', 'CPG_show');
$router->map('GET', '/cpg/detail/[i:id]', 'CPG#detail', 'CPG_detail');

// Crawlers logs
$router->map('GET', '/crawler_logs/', 'CrawlerLogs#index', 'crawler_logs');
$router->map('POST', '/crawler_logs/add/', 'CrawlerLogs#add', 'crawler_logs_add');
$router->map('GET', '/crawler_logs/[i:id]/show/', 'CrawlerLogs#show', 'crawler_logs_show');

// Bots
$router->map('GET','/bots/','Bots#index','bots');
$router->map('GET','/bots/add/',"Bots#add",'bots_add');
$router->map('POST','/bots/store/',"Bots#store",'bots_store');
$router->map('GET','/bots/edit/[i:id]',"Bots#edit",'bots_edit');
$router->map('POST','/bots/update/[i:id]',"Bots#update",'bots_update');
$router->map('POST','/bots/delete/[i:id]',"Bots#delete",'bots_delete');
$router->map('GET|POST','/bots/import/excel/','Bots#importExcelPage','bots_import_excel');
$router->map('GET|POST','/bots/import/excel/user_and_bot/',"Bots#importExcelForUserAndBotPage","bots_import_excel_user_and_bot");

// Bots profiles
$router->map('GET','/bots_profiles/','BotsProfiles#index','bots_profiles');
$router->map('GET','/bots_profiles/add_or_edit/[i:bot_id]/','BotsProfiles#addOrEdit','bots_profiles_add_or_edit');
$router->map('POST','/bots_profiles/store_or_update/[i:bot_id]/','BotsProfiles#storeOrUpdate','bots_profiles_store_or_update');
$router->map('GET','/bots_profiles/[i:id]/json/','BotsProfiles#toJson','bots_profiles_json');

// Bots Logs
$router->map('GET','/bots_logs/','BotsLogs#index','bots_logs');
$router->map('GET','/bots_logs/[i:id]/','BotsLogs#show','bots_logs_show');
$router->map('POST','/bots_logs/add/','BotsLogs#add','bots_logs_add');

// Calender
$router->map('GET','/calender/','Calender#index','calender');

// Dashboard
$router->map('GET','/dashboard/','Dashboard#index','dashboard');
$router->map('GET','/admin-dashboard/','Dashboard#admin','admin');

///> a REST API for developing purposes
$router->map('POST', '/temp/', 'Temp#index', 'temp');
$router->map('GET','/tester/send_quick_post_to_telegram/[i:id]/','Tester#send_quick_post_to_telegram', 'tester_send_quick_post');
$router->map('GET','/tester/get_request_crawler_format/[i:id]/','Tester#get_request_crawler_format', 'tester_get_request_crawler_format');

//Add route END
$match = $router->match();
// var_dump($match);
if ($match) {
    $GLOBALS['match'] = $match;
    //Get Class file and action file
    list($controller, $action) = explode('#', $match['target']);
    include_once CLASS_PATH . $controller . '.php';
    $controller = CLASS_PREFIX . $controller;
    $controller = new $controller;
    call_user_func_array(array($controller, $action), array($match['params']));
} else {
    http_response_code(404);
    die('404');
}
