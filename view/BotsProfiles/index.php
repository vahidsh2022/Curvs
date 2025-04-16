<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$groupedBotsProfiles = $this->getBotsProfiles(true);

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

                <?php eLang('bots_profiles_title'); ?>
            </span>
        </h1>
    </section>
    <!-- Main content -->
    <?php
    echo $this->flash->renderFlash();
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
                                <input type="text" id="searchInputBotsProfiles" class="custom-search-input"
                                       placeholder="Type to search">
                            </div>
                        </div>
                        <table id="bots-profiles-table" class="display table table-bordered table-striped compact member-list">
                            <thead>
                            <tr>
                                <th data-sortable="false" data-width="5px"><?php eLang('number'); ?></th>
                                <th data-sortable="false" data-width="5px"><?php eLang('image'); ?></th>
                                <th data-sortable="true"><?php eLang('types'); ?></th>
                                <th data-sortable="false"><?php eLang('created_at'); ?></th>
                                <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $index = 0;
                            foreach ($groupedBotsProfiles as $botId => $profiles) {
                                $index++
                                ?>
                                <tr>
                                    <td data-sortable="false" data-width="5px"><?php echo $index; ?></td>
                                    <td data-sortable="true">
                                        <img src="<?php echo $profiles[0]->image ? SAP_IMG_URL . $profiles[0]->image : SAP_SITE_URL . '/assets/images/avatar.jpg'; ?>" class="img-thumbnail" style="border-radius: 50%" width="100" />
                                        <?php echo $profiles[0]->name; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($profiles as $profile) { ?>
                                            <img src="<?php echo $this->socialIconByType($profile->type) ?>" alt="<?php echo $profile->type ?>">
                                        <?php } ?>
                                    </td>
                                    <td data-sortable="false"><?php echo $profiles[0]->created_at; ?></td>
                                    <td class="action_icons">
                                        <a href="<?php echo $router->generate('bots_profiles_add_or_edit', ['bot_id' => $botId]); ?>"
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

<script>
    const _pageLang = {
        "no_data": '<?php echo $sap_common->lang('no_data_available_in_table') ?>'
    }
</script>
<?php include SAP_APP_PATH . 'footer.php'; ?>


