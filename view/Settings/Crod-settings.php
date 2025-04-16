<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}


?>

<div class="tab-pane <?php echo ($active_tab == "crud") ? "active" : "" ?>" id="crud">
    <iframe src="<?php echo SAP_SITE_URL . '/bots/add/' ?>?iframe=1" frameborder="0" width="100%" height="1000" scrolling="no" class="auto-height">

    </iframe>
</div>
