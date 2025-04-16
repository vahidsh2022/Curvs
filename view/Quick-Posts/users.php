lang
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
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header content-header-quick-post d-flex justify-content-between">
        <?php if ($sap_common->sap_is_license_activated()) { ?>
            <h1>
                <span class="margin-r-5"><i class="fa fa-area-chart"></i></span>
                <?php eLang('users_quick_posts'); ?>
            </h1>
        <?php } ?>
    </section>

    <section class="content sap-quick-post">
        <div class="row">
            <?php echo $this->flash->renderFlash(); ?>

            <?php $all_posts = $this->get_all_posts(); ?>
            <div class="d-flex flex-wrap row">
                
                <div class="col-md-4">
                    <!-- DataTables Search Filter outside DataTables Wrapper -->
                    <div id="customSearch" class="customSearch">
                        <input type="text" id="searchInputquickpost" class="custom-search-input"
                            placeholder="Type to search">
                    </div>
                </div>
            </div>
            <table id="list-post" class="display table table-bordered table-striped">
                <thead>
                    <tr>
                        <th data-sortable="true"><?php eLang('ID'); ?></th>
                        <th data-sortable="true"><?php eLang('message'); ?></th>
                        <th data-sortable="true"><?php eLang('networks'); ?></th>
                        <th data-sortable="true"><?php eLang('image_video'); ?></th>
                        <th data-sortable="true"><?php eLang('status'); ?></th>
                        <th data-sortable="true"><?php eLang('user'); ?></th>
                        <th data-sortable="true"><?php eLang('date'); ?></th>
                        <th data-sortable="false" class="quick-post-th-action">
                            <?php eLang('action'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once CLASS_PATH . DS . 'Crawlers.php';
                    $crawlers = new SAP_Crawlers();
                    foreach ($all_posts as $post) { ?>
                        <tr id="quick_post_<?php echo $post->post_id; ?>">
                            <td><?php echo $post->post_id; ?></td>
                            <td>
                                <a class="post-detail" aria-data-id="<?php echo $post->post_id; ?>" class="edit_quick_post">
                                    <?php echo !empty($post->message) ? $this->common->sap_content_excerpt($post->message, 65) : ''; ?>
                                </a>
                            </td>
                            <td data-sortable="true"><?php
                            foreach (unserialize($post->networks) as $network => $space) {
                                if (is_array($space))
                                    $space = implode($space);
                                echo $crawlers->getIconByPlatform($network) . $space;
                            }
                            ?></td>
                            <td data-sortable="true"><?php echo (!empty($post->image) || !empty($post->video))?'<i class="fa fa-check-square-o green"></i>':''; ?></td>
                            <td data-sortable="true"><?php echo ($post->status == 1 ? 'published' : 'scheduled'); ?></td>
                            <td data-sortable="true">
                                <span class="documentation-text"><a
                                        href="<?php echo $router->generate('profile_member', ['id' => $post->user_id]); ?>"><?php echo $post->first_name . ' ' . $post->last_name; ?>
                                    </a></span>
                            </td>
                            <td class="quick-status">
                                <span <?php echo !empty($post->shedule) && $post->status == 2 ? 'data-toggle="tooltip" title="' . date('Y-m-d H:i', $post->shedule) . '" ' : '' ?>
                                    data-placement="left"><?php echo date("M j, Y g:i a", strtotime($post->created_date)); ?></span>
                            </td>
                            <td class="action_icons">
                                <a class="delete_quick_post" data-toggle="tooltip" title="Delete" data-placement="top"
                                    aria-data-id="<?php echo $post->post_id; ?>"><i class="fa fa-trash"
                                        aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th data-sortable="true"><?php eLang('ID'); ?></th>
                        <th data-sortable="true"><?php eLang('message'); ?></th>
                        <th data-sortable="true"><?php eLang('networks'); ?></th>
                        <th data-sortable="true"><?php eLang('image_video'); ?></th>
                        <th data-sortable="true"><?php eLang('status'); ?></th>
                        <th data-sortable="true"><?php eLang('user'); ?></th>
                        <th data-sortable="true"><?php eLang('date'); ?></th>
                        <th data-sortable="false"><?php eLang('action'); ?></th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </section>

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

<?php include SAP_APP_PATH . 'footer.php'; ?>

<script type="text/javascript" class="init">
    'use strict';

    $(document).ready(function () {
        $('#list-post').DataTable({
            "oLanguage": {
                "sEmptyTable": "No post found."
            },
            "aLengthMenu": [[15, 25, 50, 100], [15, 25, 50, 100]],
            "pageLength": 15,
            "pagingType": "full",
            "dom": 'lrtip',
            "order": [],
            "columnDefs": [
                {
                    'targets': [0, 3],
                    'orderable': false,
                },
                // { width: '220px', targets: 1 },
                // { width: '80px', targets: 3 },
            ]
        });

        // Attach DataTables search to custom input
        $('#searchInputquickpost').on('keyup', function () {
            $('#list-post').DataTable().search(this.value).draw();
        });

        $(document).on('click', '.delete_quick_post', function () {
            var obj = $(this);
            var post_id = $(this).attr('aria-data-id');
            if (confirm("<?php eLang('delete_record_conform_msg'); ?>")) {
                $.ajax({
                    type: 'POST',
                    url: SAP_SITE_URL + '/quick-post/delete/',
                    data: { post_id: post_id },
                    success: function (result) {
                        window.location.reload();
                    }
                });
            }

        });

        $(document).on('change', '.searchByGender_div', function () {
            var selected_val = $(this).find('option:selected').val();
            if (selected_val == 'delete') {
                var id = [];
                $("input[name='post_id[]']:checked").each(function (i) {
                    id[i] = $(this).val();
                });

                //tell you if the array is empty
                if (id.length === 0) {
                    alert("<?php eLang('select_checkbox_alert'); ?>");

                } else if (confirm("<?php eLang('delete_selected_records_conform_msg'); ?>")) {

                    $.ajax({
                        url: SAP_SITE_URL + '/quick-post/delete_multiple/',
                        method: 'POST',
                        data: { id: id },
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                } else {
                    return false;
                }
            }
        });

        $(document).on('click', '.post-detail', function () {
            var obj = $(this);
            var log_id = $(this).attr('aria-data-id');
            $.ajax({
                type: 'GET',
                url: '../quick-post/detail/' + log_id,
                success: function (result) {
                    var result = jQuery.parseJSON(result);

                    if (result) {
                        let $tbody = $('#myModal').find('.modal-body table tbody');
                        $tbody.empty();
                        for (let key in result) {
                            let title = result[key]['title'];
                            let value = result[key]['value'];
                            if (key == 'image') {
                                $tbody.append(`<tr><th>${title}</th><td><img class="media-preview" src="${value}"></td></tr>`);
                                continue
                            }
                            if (key == 'video') {
                                $tbody.append(value ?
                                    `<tr><th>${title}</th><td><video class="media-preview" controls src="${value}"></video></td></tr>` :
                                    `<tr><th>${title}</th><td>no-video</td></tr>`);
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