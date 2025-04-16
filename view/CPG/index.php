<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$CPGs = $this->getCPGs();

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

                <?php eLang('cpg_title_full'); ?>
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
                                <input type="text" id="searchInputCpgs" class="custom-search-input"
                                       placeholder="Type to search">
                            </div>
                        </div>
                        <table id="cpgs-table" class="display table table-bordered table-striped compact member-list">
                            <thead>
                            <tr>
                                <th data-sortable="false" data-width="5px"><?php eLang('crawler_id'); ?></th>
                                <th data-sortable="true"><?php eLang('original_message'); ?></th>
                                <th data-sortable="true"><?php eLang('new_message'); ?></th>
                                <th data-sortable="true"><?php eLang('original_image'); ?></th>
                                <th data-sortable="true"><?php eLang('new_image'); ?></th>
                                <th data-sortable="true"><?php eLang('link'); ?></th>
                                <th data-sortable="false"><?php eLang('created_at'); ?></th>
                                <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($CPGs as $CPG) { ?>
                                <tr>
                                    <td data-sortable="false" data-width="5px"><?php echo $CPG->id; ?></td>
                                    <td data-sortable="true"><a class="post-detail" aria-data-id="<?php echo $CPG->id; ?>">
                                        <?php echo substr($CPG->original_message,0,30) . ' ...'; ?></a></td>
                                    <td data-sortable="true"><?php echo substr($CPG->new_message,0,30) . ' ...'; ?></td>
                                    <td data-sortable="false">
                                        <img src="<?php echo empty($CPG->orginal_image) ? '/assets/images/no-imag.png' : $CPG->orginal_image; ?>" class="img-thumbnail" width="100" />
                                    </td>
                                    <td data-sortable="false">
                                        <?php
                                            $newImageUrl = $CPG->new_image;
                                            if(empty($newImageUrl)) {
                                                $newImageUrl = '/assets/images/no-imag.png';
                                            } else if(! (str_contains($newImageUrl,'http') || str_contains($newImageUrl,'https') )) {
                                                $newImageUrl = str_replace(SAP_IMG_URL, '', $newImageUrl);
                                                $newImageUrl = SAP_IMG_URL . $newImageUrl;
                                            }
                                        ?>
                                        <a href="<?php echo $newImageUrl ?>" target="_blank">
                                            <img src="<?php echo $newImageUrl ?>" class="img-thumbnail" width="100" />
                                        </a>
                                    </td>
                                    <td data-sortable="true">
                                        <a href="<?php echo $CPG->link ?>" target="_blank">
                                            <?php echo $CPG->link ?>
                                        </a>
                                    </td>
                                    <td data-sortable="true"><?php echo $CPG->created_at ?></td>
                                    <td class="action_icons">
                                        <a href="<?php echo $router->generate('quick_posts_add_from_cpg', ['cpg_id' => $CPG->id]); ?>"
                                           data-toggle="tooltip" title="<?php echo $sap_common->lang('edit') ?>" data-placement="top">
                                            <i class="fa fa-pencil" aria-hidden="true"></i></a>
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

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
                <h3 class="modal-title"><?php eLang('post_detail'); ?></h3>
            </div>
            <div class="modal-body">
                <div class="social_logs_view"></div>
                <table class="table table-striped" id="tblGrid">
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal"><?php eLang('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    const _pageLang = {
        "no_data": '<?php echo $sap_common->lang('no_data_available_in_table') ?>'
    }
</script>
<?php include SAP_APP_PATH . 'footer.php'; ?>

<script>
$(document).ready(function () {
    $(document).on('click', '.post-detail', function () {
        var obj = $(this);
        var log_id = $(this).attr('aria-data-id');
        $.ajax({
            type: 'GET',
            url: '../cpg/detail/' + log_id,
            success: function (result) {
                var result = jQuery.parseJSON(result);
                if (result) {
                    let $tbody = $('#myModal').find('.modal-body table tbody');
                    $tbody.empty();
                    for (let key in result) {
                        let title = result[key]['title'];
                        let value = result[key]['value'];
                        if (key == 'orginal_image' || key == 'new_image') {
                            $tbody.append(`<tr><th>${title}</th><td><img class="media-preview" src="${value}"></td></tr>`);
                            continue
                        }
                        if (key == 'link') {
                            $tbody.append(value ?
                                `<tr><th>${title}</th><td><a href="${value}" target="_blank">click Me</a></td></tr>` :
                                `<tr><th>${title}</th><td>---</td></tr>`);
                            continue
                        }
                        $tbody.append(`<tr><th>${title}</th><td>${value}</td></tr>`);
                    }
                }
            }
        });
        $('#myModal').modal('show');
    });
});
</script>