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
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $this->flash->renderFlash();

                //Active tab check
                $active_tab = !empty($_SESSION['sap_active_tab']) ? $_SESSION['sap_active_tab'] : $networks[0];

                if (!in_array($active_tab, $networks)) {
                    $active_tab = '';
                } ?>
                <form class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/bots/store/' . (IS_IFRAME ? '?iframe=1' : '');?>">

                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom settings--tabs-custom">
                        <ul class="nav nav-tabs">
                            <?php

                            foreach ($networks as $key => $network) {
                                switch ($network) {
                                    case 'facebook':
                                        $label = $sap_common->lang('network_label_fb');
                                        break;
                                    case 'telegram':
                                        $label = $sap_common->lang('network_label_tg');
                                        break;
                                    case "telegram_police":
                                        $label = $sap_common->lang('network_label_tg_police');
                                        break;
                                    case 'twitter':
                                        $label = $sap_common->lang('network_label_twitter');
                                        break;
                                    case 'linkedin':
                                        $label = $sap_common->lang('network_label_li');
                                        break;
                                    case 'tumblr':
                                        $label = $sap_common->lang('network_label_tumblr');
                                        break;
                                    case 'pinterest':
                                        $label = $sap_common->lang('network_label_pinterest');
                                        break;
                                    case 'gmb':
                                        $label = $sap_common->lang('network_label_gmb');
                                        break;
                                    case 'reddit':
                                        $label = $sap_common->lang('network_label_reddit');
                                        break;
                                    case 'blogger':
                                        $label = $sap_common->lang('network_label_blogger');
                                        break;
                                    case 'youtube':
                                        $label = $sap_common->lang('network_label_youtube');
                                        break;
                                    case 'instagram':
                                        $label = $sap_common->lang('network_label_insta');
                                        break;
                                    case 'wordpress':
                                        $label = $sap_common->lang('network_label_wordpress');
                                        break;
                                }

                                $class = ($active_tab == $network || $key === 0) ? "active" : "";
                                echo '<li class="' . $class . '"><a href="#' . $network . '" data-toggle="tab">' . $label . '</a></li>';
                            } ?>

                        </ul>

                        <div class="tab-content tab-content-settings">
                            <?php

                            foreach ($networks as $key => $network) {
                                include_once(SAP_APP_PATH . 'view/Bots/partials/' . ucwords($network) . '-settings.php');
                            } ?>

                            <div class="box-footer">
                                <div class="">
                                    <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?>
                                    </button>
                                </div>
                            </div>


                            <!-- /.tab-pane -->
                            <span class="sap-loader">
							<div class="sap-loader-sub">
								<div class="sap-loader-img"></div>
							</div>
						</span>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </form>
                <!-- nav-tabs-custom -->
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>

<?php
unset($_SESSION['sap_active_tab']);
include 'footer.php';
?>
