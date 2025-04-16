<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if (!$sap_common->sap_is_license_activated()) {
    $redirection_url = '/mingle-update/';
    header('Location: ' . SAP_SITE_URL . $redirection_url);
    die();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="d-flex flex-wrap align-items-center">
                <div class="page-title-icon simple_page_icon"></div>
                <?php echo $sap_common->lang('simple_page'); ?>
            </span>
            <div class='sap-delete'>

                <a href="<?php echo $router->generate('page'); ?>"
                    class="btn btn-primary"><?php echo $sap_common->lang('add_new'); ?> </a>

            </div>
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
                            <div class="d-flex">
                                <!-- Filter and Search Area -->
                                <div class="filter-dropdown">
                                    <select id='searchByStatus' name="searchByStatus">
                                        <option value=''><?php echo $sap_common->lang('select_status'); ?></option>
                                        <option value="1"><?php echo $sap_common->lang('active'); ?></option>
                                        <option value="0"><?php echo $sap_common->lang('in-active'); ?></option>
                                    </select>
                                    <button
                                        class="apply_filters btn btn-primary"><?php echo $sap_common->lang('filter'); ?></button>
                                </div>
                            </div>
                            <!-- DataTables Search Filter outside DataTables Wrapper -->
                            <div id="customSearch" class="customSearch">
                                <input type="text" id="searchInputplans" class="custom-search-input"
                                    placeholder="Type to search">
                            </div>
                        </div>
                        <table id="list-plans" class="display table table-bordered table-striped compact member-list">
                            <thead>
                                <tr>
                                    <th data-sortable="false" data-width="5px"><input type="checkbox"
                                            class="multipost-select-all" /></th>
                                    <th data-sortable="false" data-width="5px">
                                        <?php echo $sap_common->lang('number'); ?>
                                    </th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('name'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('price'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('networks'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('status'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th data-sortable="false" data-width="5px"><input type="checkbox"
                                            class="multipost-select-all" /></th>
                                    <th data-sortable="false" data-width="5px">
                                        <?php echo $sap_common->lang('number'); ?>
                                    </th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('name'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('price'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('networks'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('status'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                            </tfoot>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<?php include SAP_APP_PATH . 'footer.php'; ?>