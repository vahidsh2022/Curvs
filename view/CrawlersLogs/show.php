<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';


$crawlerLogId = $match['params']['id'];

$crawlerLog = $this->getCrawlerLogById($crawlerLogId);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between">
        <h1>
            <span class="margin-r-5"><i class="fa fa-crosshairs"></i></span>

            <p><?php echo $sap_common->lang('edit_crawler_log'); ?><small></small></p></h1>
        <a href="<?php echo $router->generate('crawler_logs'); ?>">
            <button class="btn btn-primary back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 13 23" fill="none">
                    <path d="M11 20.6863L1.65685 11.3431L11 2" stroke="white" stroke-width="3" stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>
                Back
            </button>
        </a>
    </section>

    <section class="content" style=" padding-top: 0;">
        <?php
        echo $this->flash->renderFlash(); ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $sap_common->lang('edit_crawler_detail'); ?>
                    : <?php echo $crawlerLogId ?></h3>
            </div>

            <?php
            if (empty($crawlerLog)) { ?>
                <div class="box-body">
                    <p><b><?php echo $sap_common->lang('data_no_exists_msg'); ?> </b></p>
                </div>
            <?php } else { ?>
                <div class="box-body">
                    <div class="row ">
                        <div class="col-md-6 form-group">
                            <label><?php echo $sap_common->lang('crawler_type'); ?>
                                : </label><strong> <?php echo $crawlerLog->crawler_type ?></strong>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><?php echo $sap_common->lang('created_at'); ?>
                                : </label><strong> <?php echo $crawlerLog->created_at ?></strong>
                        </div>
                        <div class="col-md-12 form-group">
                            <label><?php echo $sap_common->lang('user_message'); ?>: </label>
                            <div>
                                <?php echo htmlspecialchars($crawlerLog->user_message) ?>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label><?php echo $sap_common->lang('crawler_message'); ?>: </label>
                            <div>
                                <?php echo htmlspecialchars($crawlerLog->crawler_message) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
    </section>


</div></div>


<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js' ?>" type="text/javascript"></script>
<script src="<?php echo SAP_SITE_URL . '/assets/js/custom.js'; ?>"></script>
<?php
include 'footer.php';
?>
