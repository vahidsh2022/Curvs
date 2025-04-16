<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$botLog = $this->getBotLogById();
$bot = $this->getBotByBotId($botLog->bot_id);

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

global $sap_common;

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="d-flex flex-wrap align-items-center">
                             <span class="margin-r-5"><i class="fa fa-user-secret"></i></span>

                <?php eLang('bots_logs_title'); ?>
            </span>
            <!--            <div class='sap-delete'>-->
            <!--                <a href="--><?php //echo $router->generate('bots_add'); ?><!--"-->
            <!--                   class="btn btn-primary">--><?php //eLang('add_new'); ?><!-- </a>-->
            <!---->
            <!--            </div>-->
        </h1>
    </section>
    <!-- Main content -->
    <?php
    ////
    ?>

    <section class="content">
        <div class="row mobile-row">
            <div class="col-xs-12">
                <?php echo $this->flash->renderFlash(); ?>
                <div class="box bg-white" style="padding: 15px;font-size: 16px">
                    <div>
                        <span><?php eLang('bots_logs_bot_type') ?>: </span>
                        <strong><?php echo $bot->type ?></strong>
                    </div>
                    <div>
                        <span><?php eLang('bots_logs_identifier') ?>: </span>
                        <strong><?php echo $botLog->data_key ?></strong>
                    </div>
                    <div>
                        <span><?php eLang('bots_logs_created_at') ?>: </span>
                        <strong><?php echo $botLog->created_at ?></strong>
                    </div>
                    <?php foreach (json_decode($botLog->data_json,true) as $key => $value) { ?>
                            <div>
                                <span><?php echo $key ?>: </span>
                                <strong><?php echo is_array($value) ? json_encode($value) : $value ?></strong>
                            </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>


<script>
    const _pageLang = {
        delete_record_conform_msg: '<?php echo $sap_common->lang("delete_record_conform_msg") ?>',
    }
</script>
<?php include SAP_APP_PATH . 'footer.php'; ?>
