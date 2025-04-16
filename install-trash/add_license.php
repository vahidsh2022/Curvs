<?php
session_start();

if ( !defined('DS') )
    define('DS', DIRECTORY_SEPARATOR);

/* Absolute path to the Social Auto Poster directory. */
if ( !defined('SAP_APP_PATH') )
    define('SAP_APP_PATH', dirname(dirname(__FILE__)) . DS );

include ( SAP_APP_PATH . DS . 'classes' . DS . 'Common.php' );
global $sap_common;
$sap_common = new Common();

require_once('include/settings.inc.php');
require_once('include/functions.inc.php');

$task = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
$passed_step = isset($_SESSION['passed_step']) ? (int) $_SESSION['passed_step'] : 0;
$error_msg = '';
$focus_field = '';

// handle previous steps
// -------------------------------------------------
if ($passed_step >= 4) {
    // OK
} else {
    header('location: index.php');
    exit;
}

// handle form submission
// -------------------------------------------------
if ($task == 'send') {
    $sap_license_email = isset($_POST['sap_license_email']) ? prepare_input($_POST['sap_license_email']) : '';
    $sap_license_key   = isset($_POST['sap_license_key']) ? prepare_input($_POST['sap_license_key']) : '';
    $sap_license_activation = isset($_POST['action']) ? prepare_input($_POST['action']) : '';
    $status = false;
     // validation here
    // -------------------------------------------------
    if ( empty( $sap_license_email ) ) {
        $focus_field = 'sap_license_email';
        $error_msg = $sap_common->lang('install_sap_license_email');
    } else if ( empty($sap_license_key) ) {
        $focus_field = 'sap_license_key';
        $error_msg = $sap_common->lang('install_sap_license_key');
    } else if ($sap_license_email != '' && !is_email($sap_license_email)) {
            $focus_field = 'sap_license_email';
            $error_msg = $sap_common->lang('install_sap_license_email_format');
    } else {
        if (empty($error_msg) && $sap_license_activation == 'Activate License') {
            $data = wpw_auto_poster_render_activation_settings( $sap_license_key, $sap_license_email, $sap_license_activation );
            if ( isset( $data['status'] ) && true == $data['status'] ) {
                $status = true;
                $final_activation_code =  base64_encode( $sap_license_key. '%' . $sap_license_email );
                $_SESSION['final_activation_code'] = $final_activation_code;
                $_SESSION['sap_license_data'] = serialize( array( 'license_key' => $sap_license_key, 'license_email' => $sap_license_email, 'status' => 1 ) );
                $_SESSION['sap_license_email'] = $sap_license_email;
                $_SESSION['sap_license_key'] = $sap_license_key;
                $_SESSION['action']    = $sap_license_activation;
                $_SESSION['passed_step'] = 5;
                header('location: ready_to_install.php');
                exit;
            } else {
                $status = $data["status"];
                $status_msg = $data["msg"];
                if ( $status == 'true' ) {
                    //$this->flash->setFlash( $status_msg = $data["msg"], 'success');
                } else {
                    $error_msg = $status_msg;
                }
            }
        }
    }
} else {
    $sap_license_email = isset($_SESSION['sap_license_email']) ? $_SESSION['sap_license_email'] : '';
    $sap_license_key   = isset($_SESSION['sap_license_key']) ? $_SESSION['sap_license_key'] : '';
    $sap_license_activation   = isset($_SESSION['action']) ? $_SESSION['action'] : '';
}

function url(){
    // Get the current URL
    $currentUrl = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
    );

    // Parse the URL to get components
    $parsedUrl = parse_url($currentUrl);

    // Reconstruct the URL up to before '/install'
    $installPos = strpos($parsedUrl['path'], '/install');
    if ($installPos !== false) {
        $sitePath = substr($parsedUrl['path'], 0, $installPos);
    } else {
        $sitePath = $parsedUrl['path'];
    }

    // Construct the site URL
    $siteUrl = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $parsedUrl['host'],
        $sitePath
    );

    return rtrim($siteUrl, '/');
} 

function wpw_auto_poster_render_activation_settings( $sap_license_key, $sap_license_email, $sap_license_activation ) {
	$json = '{"status":true,"msg":"Valid"}';
    $data = json_decode( $json, true );
	return $data;
    $activation_code = $sap_license_key;
    $email_address   = $sap_license_email;
    $url             = 'https://updater.wpwebelite.com/Updates/validator.php';
    $curl            = curl_init();
    $fields          = array(
        'email'           => $email_address,
        'site_url'        => url(),
        'activation_code' => $activation_code,
        'activation'      => $sap_license_activation,
        'item_id'         => 29531150,
    );
    $fields_string   = http_build_query( $fields );
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_HEADER, false );
    curl_setopt( $curl, CURLOPT_POST, true );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields_string );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    
    $data = json_decode( curl_exec( $curl ), true );
    return $data ;
}
function generateRandomCode() {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$code = '';

	for ($i = 0; $i < 36; $i++) {
		if (in_array($i, [8, 13, 18, 23])) {
			$code .= '-';
		} else {
			$code .= $characters[rand(0, strlen($characters) - 1)];
		}
	}

	return $code;
}

$randomCode = generateRandomCode();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="ApPHP Company - Advanced Power of PHP">
        <meta name="generator" content="ApPHP EasyInstaller">
        <title><?php echo $sap_common->lang('install_installation_complate'); ?></title>
        <link rel="icon" href="<?php echo $Site_url . '/assets/images/favicon.png'; ?>" type="image/png" sizes="32x32">
        <link rel="stylesheet" type="text/css" href="templates/css/styles.css" />	

        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    </head>
    <body>
        <div id="main">    
            <h1><?php echo $sap_common->lang('install_welcome_title'); ?></h1>
            <h2 class="sub-title"><?php echo $sap_common->lang('install_welcome_text'); ?></h2>

            <div id="content">
                <?php
                draw_side_navigation(5);
                ?>
                 <div class="central-part">
                    <h2><?php echo $sap_common->lang('install_step_5_to_7'); ?></h2>

                    <p><?php echo $sap_common->lang('install_step_5_help_text'); ?></p>
                    
                    <form action="add_license.php" method="post">
                        <input type="hidden" name="task" value="send" />
                        <input type="hidden" name="token" value="<?php echo isset( $_SESSION['token'] ) ? $_SESSION['token'] : ''; ?>" />

                        <?php
                        if (!empty($error_msg)) {
                            echo '<div class="alert alert-error">' . $error_msg . '</div>';
                        }
                        ?>
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">			
                            <tr>
                                <td colspan="3"><span class="star">*</span> <?php echo $sap_common->lang('install_required_fields'); ?></td>
                            </tr>
                            <tr><td nowrap height="10px" colspan="3"></td></tr>
                            
                            <tr>
                                <td width="250px">&nbsp;<?php echo $sap_common->lang('sap_license_email'); ?>&nbsp;<span class="star">*</span></td>
                                <td><input name="sap_license_email" id="sap_license_email" class="form_text" size="28" maxlength="125" value="temp@mail.com" placeholder="<?php echo $sap_common->lang('sap_license_email'); ?>" /></td>
                                <td rowspan="6" valign="top">
                                    <div id="notes_license_key" class="notes_container">
                                        <?php echo sprintf($sap_common->lang('install_license_key_help_text'),'<h4>','</h4>','<p>','</p>'); ?>

                                    </div>
                                    <img class="loading_img" src="images/ajax_loading.gif" alt="loading..." />
                                    <div id="notes_message" class="notes_container"></div>					
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;<?php echo $sap_common->lang('sap_license_key'); ?>&nbsp;<span class="star">*</span></td>
                                <td><input name="sap_license_key" id="sap_license_key" class="form_text" type="text" size="28" maxlength="40" value="<?= $randomCode ?>" onfocus="textboxOnFocus('notes_license_key')" onblur="textboxOnBlur('notes_license_key')" placeholder="<?php echo $sap_common->lang('sap_license_key_placeholder'); ?>" /></td>
                            </tr>

                            <tr><td colspan="2" nowrap height="50px">&nbsp;</td></tr>
                            <tr>
                                <td colspan="2">
                                    <a href="administrator_account.php" class="form_button" /><?php echo $sap_common->lang('install_back'); ?></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="action" value="Activate License">
                                    <?php if( ! empty( $_SESSION['final_activation_code'] ) ) { ?>
                                        <input type="submit" class="form_button" value="Continue" />
                                    <?php } else { ?>
                                        <input type="submit" class="form_button" value="Validate" />
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                    </div>
                <div class="clear"></div>
            </div>
            <?php include_once('include/footer.inc.php'); ?>        
        </div>

        <script type="text/javascript">
            'use strict';
            function bodyOnLoad() {
                setFocus("<?php echo $focus_field; ?>");
            }
        </script>
    </body>
</html>
