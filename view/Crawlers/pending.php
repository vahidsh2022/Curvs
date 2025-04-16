<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="d-flex flex-wrap align-items-center">
                <span class="margin-r-5"><i class="fa fa-crosshairs"></i></span>
                <?php eLang('crwlr_title'); ?>
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
                            <div class="d-flex">
                                <!-- Filter and Search Area -->

                            </div>
                        </div>
                        <table id="list-crawlers"
                            class="display table table-bordered table-striped compact member-list">
                            <thead>
                                <tr>
                                    <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                    <th data-sortable="true"><?php eLang('platform'); ?></th>
                                    <th data-sortable="true"><?php eLang('listening_channel'); ?></th>
                                    <th data-sortable="true"><?php eLang('status'); ?></th>
                                    <th data-sortable="true"><?php eLang('translation_language'); ?></th>
                                    <th data-sortable="false"><?php eLang('dates'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                    <th data-sortable="true"><?php eLang('platform'); ?></th>
                                    <th data-sortable="true"><?php eLang('listening_channel'); ?></th>
                                    <th data-sortable="true"><?php eLang('status'); ?></th>
                                    <th data-sortable="true"><?php eLang('translation_language'); ?></th>
                                    <th data-sortable="false"><?php eLang('dates'); ?></th>
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
<script type="text/javascript" class="init">
    'use strict';

    $(document).ready(function () {

        var dtListUsers = $('#list-crawlers').DataTable({
            "oLanguage": {
                "sEmptyTable": "No membership found."
            },
            "aLengthMenu": [[15, 25, 50, 100], [15, 25, 50, 100]],
            "pageLength": 15,
            "bLengthChange": false,
            "order": [[5, "asc"]],
            "responsive": true,
            "processing": true,
            "serverSide": true,
            // "dom": 'lrtip',
            "columnDefs": [
                { "targets": 0, "name": "number", 'searchable': false, 'orderable': false },
                { "targets": 1, "name": "platform", 'searchable': true, 'orderable': true },
                { "targets": 2, "name": "channels", 'searchable': true, 'orderable': false },
                { "targets": 3, "name": "status", 'searchable': false, 'orderable': false },
                { "targets": 4, "name": "translation_language", 'true': false, 'orderable': false },
                { "targets": 5, "name": "dates", 'searchable': false, 'orderable': false },
                { "targets": 6, "name": "action", 'searchable': false, 'orderable': false },
            ],

            'ajax': {
                'url': '../pendings/list/',
                'data': function (data) {
                    data.searchByStatus = $('#searchByStatus').val();
                }
            },

        });


        $('body').on('click', '.active-this', function () {
            if (confirm('do you active this?')) {
                jQuery.ajax('../pendings/active/' + $(this).data('id'));
                location.href = location.href;
            }
        });
    });
</script>