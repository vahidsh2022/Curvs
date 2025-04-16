<?php
/**
 * Define some constants
 */


if ( ! defined('SAP_REDDIT_CLIENT_ID') ){ 
	 define('SAP_REDDIT_CLIENT_ID', 'IS9gj7xgMtDCWPA9PDGriQ' ); 
	
}

if ( ! defined('SAP_REDDIT_SECRET_KEY') ){
	define('SAP_REDDIT_SECRET_KEY', 'kQHHqYKnT1s2bmFVbF70kq2WyTm8rw' ); 
}

if (! defined('SAP_REDDIT_REDIRECT_URL')) {
    define('SAP_REDDIT_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/reddit/index.php'); 
}

if (! defined('SAP_REDDIT_APP_SCOPE')) {
    $scopes = array( 'save', 'modposts', 'identity', 'edit', 'flair', 'history', 'modconfig', 'modflair', 'modlog', 'modposts', 'modwiki', 'mysubreddits', 'privatemessages', 'read', 'report', 'submit', 'subscribe', 'vote', 'wikiedit', 'wikiread' );

    $scopes = implode(",", $scopes);

    define('SAP_REDDIT_APP_SCOPE', $scopes); //Reddit scopes
}

if ( ! defined('SAP_NEW_LI_APP_METHOD_ID') ){
	define('SAP_NEW_LI_APP_METHOD_ID', '78atjnjdyfgvd8' ); // LINKEDIN APP ID
}
if ( ! defined('SAP_NEW_LI_APP_METHOD_SECRET') ) {
	define('SAP_NEW_LI_APP_METHOD_SECRET', 'DSgb8XJB6jHpgXWq' ); // LINKEDIN APP SECRET
}
if ( ! defined('SAP_NEW_LI_APP_REDIRECT_URL') ) {
	define('SAP_NEW_LI_APP_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/li/index.php' ); // LI app redirect url
}

if ( ! defined('SAP_NEW_FB_APP_METHOD_ID') ){
	define('SAP_NEW_FB_APP_METHOD_ID', '4071144333111775' ); // FACEBOOK APP ID
}
if ( ! defined('SAP_NEW_FB_APP_METHOD_SECRET') ) {
	define('SAP_NEW_FB_APP_METHOD_SECRET', 'ebf17438e572c9dbd1d51a18091114bf' ); // FACEBOOK APP SECRET
}
if ( ! defined('SAP_NEW_FB_APP_REDIRECT_URL') ) {
	define('SAP_NEW_FB_APP_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/fb/index.php' ); // FB app redirect url
}

if ( ! defined('SAP_NEW_FB_APP_METHOD_ID_FOR_INSTA') ){
	define('SAP_NEW_FB_APP_METHOD_ID_FOR_INSTA', '4071144333111775' );
	
}
if ( ! defined('SAP_NEW_FB_APP_METHOD_SECRET_FOR_INSTA') ) {
	define('SAP_NEW_FB_APP_METHOD_SECRET_FOR_INSTA', 'ebf17438e572c9dbd1d51a18091114bf' );

}
if ( ! defined('SAP_NEW_FB_APP_REDIRECT_URL_FOR_INSTA') ) {
	define('SAP_NEW_FB_APP_REDIRECT_URL_FOR_INSTA', 'https://updater.wpwebelite.com/codebase/SAP-PHP/fb/index-instagram.php' ); // FB app redirect url
}

if ( ! defined('SAP_NEW_TR_APP_METHOD_KEY') ){
	define('SAP_NEW_TR_APP_METHOD_KEY', 'FknIg4UwDenGJcVpUC3qasyFmQefJz3W2SN8CR02IkJBm00qC2' ); // TUMBLR APP ID
}
if ( ! defined('SAP_NEW_TR_APP_METHOD_SECRET') ) {
	define('SAP_NEW_TR_APP_METHOD_SECRET', 'vbRZ3j0J2UM5XG4GnioI5bLsjyXGA5RuGMSkVttgCR1lLUhRsJ' ); // TUMBLR APP SECRET
}
if ( ! defined('SAP_NEW_TR_APP_REDIRECT_URL') ) {
	define('SAP_NEW_TR_APP_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/tb/index.php' ); // FB app redirect url
}


if ( ! defined('SAP_NEW_FB_APP_VERSION') ) {
	define('SAP_NEW_FB_APP_VERSION', 'v3.0' ); // FB Version
}

// GMB
if ( ! defined('SAP_NEW_GMB_CLIENT_ID') ) {
	define('SAP_NEW_GMB_CLIENT_ID', '804943316894-vksk1aj1mpkec9k57ocp8pttmno62hvk.apps.googleusercontent.com' );
}
if ( ! defined('SAP_NEW_GMB_CLIENT_SECRET') ) {
	define('SAP_NEW_GMB_CLIENT_SECRET', 'ns7XgpZEiAp3KalQFkcgH12Z' );
}
if ( ! defined('SAP_NEW_GMB_REDIRECT_URL') ) {
	define('SAP_NEW_GMB_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/gmb/success.php' );
}
if ( ! defined('SAP_NEW_GMB_SCOPE') ) {
	define('SAP_NEW_GMB_SCOPE', 'https://www.googleapis.com/auth/plus.business.manage' ); // GMB Scope
}


// Blogger
if ( ! defined( 'SAP_BLOGGER_CLIENT_ID' ) ){
	define( 'SAP_BLOGGER_CLIENT_ID', '804943316894-vksk1aj1mpkec9k57ocp8pttmno62hvk.apps.googleusercontent.com' ); // BLOGGER APP ID
}
if ( ! defined( 'SAP_BLOGGER_CLIENT_SECRET' ) ) {
	define( 'SAP_BLOGGER_CLIENT_SECRET', 'ns7XgpZEiAp3KalQFkcgH12Z' ); // BLOGGER APP SECRET
}
if ( ! defined( 'SAP_BLOGGER_REDIRECT_URL' ) ) {
	define( 'SAP_BLOGGER_REDIRECT_URL', 'https://updater.wpwebelite.com/codebase/SAP-PHP/blogger/index.php' ); // BLOGGER REDIRECT URL
}
if ( ! defined( 'SAP_BLOGGER_AUTH_URL' ) ) {
	define( 'SAP_BLOGGER_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth?' ); // BLOGGER AUTH URL
}
if (! defined( 'SAP_BLOGGER_ACCESS_TOKEN_URL' ) ) {
    define( 'SAP_BLOGGER_ACCESS_TOKEN_URL', 'https://oauth2.googleapis.com/token' ); //BLOGGER SCOPE
}
if (! defined( 'SAP_BLOGGER_USERINFO_URL' ) ) {
    define( 'SAP_BLOGGER_USERINFO_URL', 'https://www.googleapis.com/oauth2/v1/userinfo' ); //BLOGGER USERINFO
}


if (! defined( 'SAP_BLOGGER_SCOPE' ) ) {
	$scopes = array( 'https://www.googleapis.com/auth/blogger', 'https://www.googleapis.com/auth/userinfo.profile' );
	//$scopes = 'https://www.googleapis.com/auth/blogger';
    $scopes = implode(" ", $scopes);

    define( 'SAP_BLOGGER_SCOPE', $scopes ); //BLOGGER SCOPE
}

if ( !defined('SAP_UPDATER_LICENSE_URL') ){
    define('SAP_UPDATER_LICENSE_URL', 'https://updater.wpwebelite.com/Updates/validator.php');
}

/* Max file upload size validation for Mingle Media Upload Default 1MB. */
if ( !defined('MINGLE_MAX_FILE_UPLOAD_SIZE') )
	define('MINGLE_MAX_FILE_UPLOAD_SIZE', '10240000' );

/* Max video upload size validation for Mingle Media Upload Default 10MB. */
if ( !defined('MINGLE_MAX_VIDEO_UPLOAD_SIZE') )
    define('MINGLE_MAX_VIDEO_UPLOAD_SIZE', '10240000' );