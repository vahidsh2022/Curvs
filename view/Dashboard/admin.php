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
    <section class="content-header content-header-quick-post d-flex justify-content-between">
        <h1>
            <span class="margin-r-5"><i class="fa fa-tachometer"></i></span>
            <?php echo $sap_common->lang('dashboard') ?>
        </h1>
    </section>

    <section id="dashboard_section" class="content sap-quick-post">
        <div class="container">
            <div class="light-background">
                <?php echo $this->flash->renderFlash(); ?>
                <!-- Users Overview Section (with eLang for localization) -->
                <div class="row">
                    <?php $users = $this->getAdminDashboardUserData(); ?>
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php eLang('total_customers'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $users->total; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading"><?php eLang('customers_last_month'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $users->last_month; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <div class="panel-heading"><?php eLang('customers_yesterday'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $users->yesterday; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Posts Overview Section -->
                <!-- Posts Overview Section -->
                <div class="row">

                    <!-- Published Posts -->
                    <?php $posts = $this->getAdminDashboardPostsData(1); ?>
                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading"><?php eLang('published_posts'); ?></div>
                            <div class="panel-body">
                                <p><?php eLang('total'); ?>: <strong><?php echo $posts['total']; ?></strong>
                                </p>
                                <p><?php eLang('last_month'); ?>:
                                    <strong><?php echo $posts['last_month']; ?></strong>
                                </p>
                                <p><?php eLang('yesterday'); ?>:
                                    <strong><?php echo $posts['yesterday']; ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduled Posts -->
                    <?php $posts = $this->getAdminDashboardPostsData(2); ?>
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading"><?php eLang('scheduled_posts'); ?></div>
                            <div class="panel-body">
                                <p><?php eLang('total'); ?>: <strong><?php echo $posts['total']; ?></strong>
                                </p>
                                <p><?php eLang('last_month'); ?>:
                                    <strong><?php echo $posts['last_month']; ?></strong>
                                </p>
                                <p><?php eLang('yesterday'); ?>:
                                    <strong><?php echo $posts['yesterday']; ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Dashboard Section: Crawlers Overview -->
                <?php $crawlers = $this->getAdminDashboardCrawlersData(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php eLang('total_crawlers'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $crawlers->total; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading"><?php eLang('crawlers_last_month'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $crawlers->last_month; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <div class="panel-heading"><?php eLang('crawlers_yesterday'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $crawlers->yesterday; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Section: CPG Overview -->
                <?php $cpg = $this->getAdminDashboardCPGData(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php eLang('total_cpg'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $cpg->total; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading"><?php eLang('cpg_last_month'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $cpg->last_month; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <div class="panel-heading"><?php eLang('cpg_yesterday'); ?></div>
                            <div class="panel-body text-center">
                                <h3><?php echo $cpg->yesterday; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>


</div>
<?php
include SAP_APP_PATH . 'footer.php';