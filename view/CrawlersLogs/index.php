<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$crawlersLogs = $this->getCrawlersLogs();

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

global $sap_common;


?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
            <span class="d-flex flex-wrap align-items-center">
                <span class="margin-r-5"><i class="fa fa-crosshairs"></i></span>

                <?php eLang('crawler_logs_title'); ?>
            </span>
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
                                    <input type="text" id="searchInputCrawlerLogs" class="custom-search-input"
                                           placeholder="Type to search">
                                </div>
                            </div>
                            <table id="crawler-logs-table" class="display table table-bordered table-striped compact member-list">
                                <thead>
                                <tr>
                                    <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                    <th data-sortable="true"><?php eLang('crawler'); ?></th>
                                    <th data-sortable="true"><?php eLang('crawler_type'); ?></th>
                                    <th data-sortable="true"><?php eLang('user_message'); ?></th>
                                    <th data-sortable="true"><?php eLang('crawler_message'); ?></th>
                                    <th data-sortable="false"><?php eLang('created_at'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($crawlersLogs as $crawlersLog) { ?>
                                    <tr>
                                        <td data-sortable="false" data-width="5px"><?php echo $crawlersLog->id; ?></td>
                                        <td data-sortable="true"><?php echo $crawlersLog->crawler ?? ''; ?></td>
                                        <td data-sortable="true"><?php echo $crawlersLog->crawler_type; ?></td>
                                        <td data-sortable="true"><?php echo substr($crawlersLog->user_message,0,30) . ' ...'; ?></td>
                                        <td data-sortable="true"><?php echo substr($crawlersLog->crawler_message,0,30) . ' ...'; ?></td>
                                        <td data-sortable="false"><?php echo $crawlersLog->created_at; ?></td>
                                        <td class="action_icons">
                                            <a href="<?php echo $router->generate('crawler_logs_show', ['id' => $crawlersLog->id]); ?>"
                                               data-toggle="tooltip" title="<?php echo $sap_common->lang('show') ?>" data-placement="top">
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
        "crawler_type": '<?php echo $sap_common->lang('crawler_type') ?>',
        "no_data": '<?php echo $sap_common->lang('no_data_available_in_table') ?>'
    }
</script>
<?php include SAP_APP_PATH . 'footer.php'; ?>


