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

include 'header.php';

include 'sidebar.php';


// Get user's active networks
$networks = $this->networkTypes();

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			<span class="d-flex flex-wrap align-items-center">
				                             <span class="margin-r-5"><i class="fa fa-user-secret"></i></span>

			                <?php eLang('bots_title'); ?>
			</span>
        </h1>
    </section>
    <!-- Main content -->
    <div class="content">
        <!-- Info boxes -->
        <div class="box shadow-lg animated fadeIn">
            <?php echo $this->flash->renderFlash(); ?>

            <div class="box-body sap-custom-drop-down-wrap shadow">
                <form class="import-bots-form"  name="import_bots" id="import_bots" method="POST" enctype="multipart/form-data" action="<?php echo SAP_SITE_URL . '/bots/import/excel/'; ?>">
                    <label for="file">Import Excel File</label>
                    <input type="file" name="file" id="file" accept=".xlsx">
                    <div class="sap-mt-3"></div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>

<?php
unset($_SESSION['sap_active_tab']);
include 'footer.php';
?>
