<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
global $router;

?>

<div class="tab-pane <?php echo ( $active_tab == $network) ? "active" : "" ?>" id="<?php echo $network ?>">
    <div class="box box-primary box-inner-div border-b">
        <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('facebook_general_title'); ?></div>
        <div class="box-body">
            <?php include( SAP_APP_PATH . 'view/Bots/partials/_general-settings.php'); ?>
        </div>
    </div>
</div>