<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

global $sap_common;


$socialAccounts = $this->socialAccounts();
$groupedCrawlers = $this->getCrawlers();
$groupedBots = $this->getBots();
$posts = $this->getQuickPosts();
$cpgs = $this->getCpgs();

?>

<link rel="stylesheet" href="<?php echo SAP_SITE_URL . '/assets/css/dashboard.css?id=2' ?>">

<div class="content-wrapper">
    <section class="content-header content-header-quick-post d-flex justify-content-between">
        <h1>
            <span class="margin-r-5"><i class="fa fa-tachometer"></i></span>
            <?php echo $sap_common->lang('dashboard') ?>
        </h1>
    </section>

    <section id="dashboard_section" class="content sap-quick-post">
        <div class="container">
            <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php eLang('shortcuts'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <a href="<?php echo $router->generate('settings'); ?>" class="btn btn-primary"><?php eLang('new_account'); ?></a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <a href="<?php echo $router->generate('quick_posts_add'); ?>" class="btn btn-primary"><?php eLang('new_post'); ?></a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <a href="<?php echo $router->generate('crawlers_add'); ?>" class="btn btn-primary"><?php eLang('new_crawler'); ?></a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <a href="<?php echo $router->generate('bots_add'); ?>" class="btn btn-primary"><?php eLang('new_crowd'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="light-background">
                <div class="row">
                    <?php echo $this->flash->renderFlash(); ?>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="panel custom-panel-blue">
                            <div class="panel-heading"><?php echo $sap_common->lang('social_management') ?></div>
                            <div class="panel-body">
                                <div class="white-box">
                                    <div class="sales-info">
                                        <?php foreach ($socialAccounts as $network => $socialAccount) { ?>
                                            <div class="info-row">
                                            <span class="info-label fw-bold">
                                            <img src="<?php echo $this->socialIconByType($network) ?>"
                                                 alt="<?php echo $network ?>">
                                                <?php echo ucfirst($network) ?>
                                            </span>
                                                <span class="info-value">( <?php echo count($socialAccount["{$network}_keys"] ?? []) ?> )</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-wrapper-one">
                            <?php foreach ($this->limitPosts($posts) as $post) { ?>
                                <div class="post-item">
                                    <img src="<?php echo $this->imageLoader(SAP_IMG_URL . $post->image) ?>"
                                         alt="Post Image">
                                    <div class="post-content">
                                        <p class="fw-bold">
                                            <a href='<?php echo $router->generate('quick_viewpost',['id'=>$post->post_id]); ?>'><?php echo $post->message ?></a>
                                        </p>
                                        <div class="date-time">
                                            <?php echo $post->created_date ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="panel custom-panel-red">
                            <div class="panel-heading"><?php echo $sap_common->lang('crawler_services') ?></div>
                            <div class="panel-body">
                                <div class="white-box">
                                    <div class="sales-info">
                                        <?php foreach ($groupedCrawlers as $platform => $crawlers) { ?>
                                            <div class="info-row">
                                            <span class="info-label fw-bold">
                                            <img src="<?php echo $this->socialIconByPlatform($platform) ?>"
                                                 alt="<?php echo $platform ?>">
                                                                                      <?php echo ucfirst($platform) ?>

                                            </span>
                                                <span class="info-value">( <?php echo count($crawlers) ?> )</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-wrapper-one">
                            <?php foreach ($this->limitPosts($cpgs) as $cpg) { ?>
                                <div class="post-item">
                                    <img src="<?php echo($this->imageLoader(($cpg->new_image ?: $cpg->orginal_image))) ?>"
                                         alt="Post Image">
                                    <div class="post-content">
                                        <p class="fw-bold">
                                        <a href='<?php echo $router->generate('quick_posts_add_from_cpg',['cpg_id'=>$cpg->id]); ?>'><?php echo $post->message ?></a>
                                        </p>
                                        <div class="date-time">
                                            <?php echo $cpg->created_at ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="panel custom-panel-green">
                            <div class="panel-heading"><?php echo $sap_common->lang('bots_crowd') ?></div>
                            <div class="panel-body">
                                <div class="white-box">
                                    <div class="sales-info">
                                        <?php foreach ($groupedBots as $type => $bots) { ?>
                                            <div class="info-row">
                                            <span class="info-label fw-bold">
                                            <img src="<?php echo $this->socialIconByType($type) ?>"
                                                 alt="<?php echo $type ?>">
                                                                                      <?php echo ucfirst($type) ?>
                                            </span>
                                                <span class="info-value">
                                                ( <?php echo count($bots ?? []) ?> )
                                            </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div class="post-item">-->
                        <!--                            <img src="-->
                        <?php //$this->messageLoader() ?><!--" alt="Test Image">-->
                        <!--                            <div class="post-content">-->
                        <!--                                <h4>Title Post</h4>-->
                        <!--                                <p>-->
                        <!--                                    --><?php //echo $this->messageLoader('test') ?>
                        <!--                                </p>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const images = document.querySelectorAll("#dashboard_section img");

        images.forEach(image => {
            image.addEventListener("error", function() {
                // اگر عکس وجود نداشت، این تابع اجرا می‌شود
                this.src = "<?php echo SAP_SITE_URL . '/assets/images/no-imag.png' ?>";
            });
        });
    });
</script>