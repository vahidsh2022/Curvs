<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$botsLogs = $this->getBotsLogs();


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
        <div class="row  mobile-row">
            <div class="col-xs-12">
                <?php echo $this->flash->renderFlash(); ?>
                <div class="box">
                    <div class="box-body sap-custom-drop-down-wrap">
                        <div class="filter-wrap">
                            <!-- DataTables Search Filter outside DataTables Wrapper -->
                            <div id="customSearch" class="customSearch">
                                <input type="text" id="searchInputBots" class="custom-search-input"
                                       placeholder="Type to search">
                            </div>
                        </div>
                        <table id="bots-table" class="display table table-bordered table-striped compact member-list">
                            <thead>
                            <tr>
                                <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                <th data-sortable="false" data-width="5px"><?php eLang('bot_type'); ?></th>
                                <th data-sortable="false" data-width="5px"><?php eLang('key'); ?></th>
                                <th data-sortable="false" data-width="5px"><?php eLang('values'); ?></th>
                                <th data-sortable="false"><?php eLang('created_at'); ?></th>
                                <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($botsLogs as $index => $botLog) {
                                $index++
                                ?>
                                <tr id="bot_row_<?php echo$botLog->id ?>">
                                    <td data-sortable="false" data-width="5px"><?php echo $index; ?></td>
                                    <td data-sortable="false" data-width="5px"><?php echo $botLog->bot_type; ?></td>
                                    <td data-sortable="false" data-width="5px"><?php echo $botLog->data_key; ?></td>
                                    <td data-sortable="true"><?php echo substr($botLog->data_json,0,120) ?></td>
                                    <td data-sortable="false"><?php echo $botLog->created_at; ?></td>
                                    <td class="action_icons">
                                        <a href="<?php echo $router->generate('bots_logs_show', ['id' => $botLog->id]); ?>"
                                           data-toggle="tooltip" title="<?php echo $sap_common->lang('show') ?>"
                                           data-placement="top">
                                            <i class="fa fa-eye" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>
                    </div>
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
