<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

// $crawlers = $this->getCrawlers();

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
            <div class='sap-delete'>

                <a href="<?php echo $router->generate('crawlers_add'); ?>"
                    class="btn btn-primary"><?php eLang('add_new'); ?></a>

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
                                        <option value='all'><?php eLang('all'); ?></option>
                                        <option value='active'><?php eLang('active'); ?></option>
                                        <option value="pending"><?php eLang('pending'); ?></option>
                                        <option value="deactive"><?php eLang('deactive'); ?></option>
                                    </select>
                                    <button class="apply_filters btn btn-primary"><?php eLang('filter'); ?></button>
                                </div>
                            </div>
                        </div>
                        <table id="list-crawlers"
                            class="display table table-bordered table-striped compact member-list">
                            <thead>
                                <tr>
                                    <!-- <th data-sortable="false" data-width="5px"><input type="checkbox"
                                            class="crawler-select-all" /></th> -->
                                    <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                    <th data-sortable="true"><?php eLang('platform'); ?></th>
                                    <th data-sortable="true"><?php eLang('listening_channel'); ?></th>
                                    <th data-sortable="true"><?php eLang('status'); ?></th>
                                    <th data-sortable="true"><?php eLang('translation_language'); ?></th>
                                    <th data-sortable="false"><?php eLang('dates'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                            </thead>
                            <?php /* ?>
        <tbody>
            <?php foreach ($crawlers as $crawler) { ?>
                <tr>
                    <td data-sortable="false" data-width="5px"><input type="checkbox"
                            class="crawler-select-all" /></td>
                    <td data-sortable="false" data-width="5px"><?php echo $crawler->id; ?></td>
                    <td data-sortable="true"><?php echo $crawler->platform; ?>
                        <span class="page-title-icon crawler_<?php echo $crawler->platform; ?>_icon">
                        </span>
                    </td>
                    <td data-sortable="true">
                        <?php
                        foreach (explode(',', $crawler->listening_channel) as $item) {
                            $url = $item;
                            if ($crawler->platform == 'telegram') {
                                $url = 'https://t.me/' . $url;
                            }
                            $item = str_replace(['https://', 'http://', 'www.'], '', $item);
                            echo "<div><a href='$url' target='_blank'>$item</a></div>";
                        }
                        ?>
                    </td>
                    <td data-sortable="true"><?php echo $crawler->status; ?></td>
                    <td data-sortable="true"><?php echo $crawler->translation_language; ?></td>
                    <td data-sortable="false"><?php echo $crawler->created_date; ?></td>
                    <td class="action_icons">
                        <a href="<?php echo $router->generate('crawlers_edit', ['id' => $crawler->id]); ?>"
                            data-toggle="tooltip" title="Edit" data-placement="top">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a class="delete_post" data-toggle="tooltip" title="Delete" data-placement="top"
                            aria-data-id="<?php echo $crawler->id; ?>"><i class="fa fa-trash"
                                aria-hidden="true"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <?php */ ?>
                            <tfoot>
                                <tr>
                                    <!-- <th data-sortable="false" data-width="5px"><input type="checkbox"
                                            class="crawler-select-all" /></th> -->
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
                {"targets": 0, "name": "number", 'searchable': false, 'orderable': false},
                {"targets": 1, "name": "platform", 'searchable': true, 'orderable': true},
                {"targets": 2, "name": "channels", 'searchable': true, 'orderable': false},
                {"targets": 3, "name": "status", 'searchable': false, 'orderable': false},
                {"targets": 4, "name": "translation_language", 'true': false, 'orderable': false},
                {"targets": 5, "name": "dates", 'searchable': false, 'orderable': false},
                {"targets": 6, "name": "action", 'searchable': false, 'orderable': false},
            ],

            'ajax': {
                'url': '../crawlers/list',
                'data': function (data) {
                    data.searchByStatus = $('#searchByStatus').val();
                }
            },

        });


        $('body').on('click', '.apply_filters', function () {
            // console.log(dtListUsers.draw());
            // dtListUsers.ajax.reload() 
            // dtListUsers.ajax.reload(null, false);  // `false` prevents page reset
            dtListUsers.ajax.reload(null, true);  // `true` forces cache refresh


        });
    });
</script>