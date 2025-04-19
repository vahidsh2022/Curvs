<!-- /.content-wrapper -->
<?php 

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$settings_object      = new SAP_Settings();
$footer_content = $settings_object->get_options('footer_content');
?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 
        <?php echo SAP_VERSION;  ?>
    </div>
    <?php 

    if(!empty($footer_content)){
        echo $footer_content;
    }else{
        echo sprintf($sap_common->lang('footer'),'<strong>','&copy',date('Y'),'<a href="https://www.wpwebelite.com" target="_blank">','</a>','</strong>');
    }?>
  
    
</footer>

<!-- Control Sidebar -->

</div>
<?php if ($match['name'] == 'viewpost' || $match['name'] == 'addpost' || $match['name'] == 'quick_posts' || $match['name'] == 'quick_posts_with_id' || $match['name'] == 'quick_viewpost' || $match['name'] == 'quick_posts_add' || $match['name'] == 'quick_posts_add_from_cpg') { ?>
    <script src="<?php echo SAP_SITE_URL . '/assets/js/moment.js'; ?>" defer onload=''></script>
    <script src="<?php echo SAP_SITE_URL . '/assets/js/moment-timezone-with-data.js'; ?>" defer onload=''></script>
    <?php }
?>
<!-- ./wrapper -->
<!-- jQuery 3 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap.min.js'; ?>" defer onload=''></script>
<?php if ($match['name'] != 'report' || $match['name'] == 'quick_viewpost') { ?>
    <script src="<?php echo SAP_SITE_URL . '/assets/js/bootstrap-datetimepicker.js'; ?>" defer onload=''></script>
    <?php }
?>
<!-- social-auto-poster -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/curvs-social-auto-poster.min.js'; ?>" defer onload=''></script>
<!-- jvectormap -->

<?php



//Js for Plans and Members
if ( $match['name'] == 'add_plan' || $match['name'] == 'edit_plan' || $match['name'] == 'add_member' || $match['name'] == 'edit_member' || $match['name'] == 'add_membership' ) {
    
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/jQuery-validate/jquery.validate.js"></script>';
}

//Js for Users
if ($match['name'] == 'my_account') {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/jQuery-validate/jquery.validate.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/users.js"></script>';
}

if ($match['name'] == 'addpost' || $match['name'] == 'viewpost' || $match['name'] == 'general_settings' || $match['name'] == 'settings' || $match['name'] == 'quick_posts' || $match['name'] == 'quick_posts_with_id' || $match['name'] == 'quick_viewpost' || $match['name'] == 'quick_posts_add' || $match['name'] == 'quick_posts_add_from_cpg') {
    echo '<script src="' . SAP_SITE_URL . '/assets/js/select2.js"></script>';
    echo '<script src="' . SAP_SITE_URL . '/assets/js/icheck.min.js"></script>';
}
?>

<!-- search -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.search.min.js'; ?>" defer onload=''></script>
<!-- custom -->
<script src="<?php echo SAP_SITE_URL . '/assets/js/custom.js'; ?>" defer onload=''></script>
<!-- dataTable Start -->
<script type="text/javascript" src="<?php echo SAP_SITE_URL . '/assets/dataTables/js/jquery.dataTables.min.js'; ?>" defer onload=''></script>
<script type="text/javascript" src="<?php echo SAP_SITE_URL . '/assets/dataTables/js/dataTables.bootstrap.min.js'; ?>" defer onload=''></script>
<script type="text/javascript" src="<?php echo SAP_SITE_URL . '/assets/dataTables/js/dataTable-init.js'; ?>" defer onload=''></script>
<!-- dataTable End -->
<?php
if ($match['name'] == 'addpost' || $match['name'] == 'settings' || $match['name'] == 'general_settings' || $match['name'] == 'viewpost' || $match['name'] == 'quick_posts' || $match['name'] == 'quick_posts_with_id' || $match['name'] == 'quick_viewpost' || $match['name'] == 'quick_posts_add' || $match['name'] == 'quick_posts_add_from_cpg') {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/fileinput.js?id=1" defer onload=""></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/posts/add-posts.js" defer onload=""></script>';
}

//Js for settings
if ($match['name'] == 'settings') {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/jQuery-validate/jquery.validate.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/settings.js?id=2"></script>';
}

//Js for update 
if ($match['name'] == 'mingle_update') {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/jQuery-validate/jquery.validate.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/curvs-update.js"></script>';
}

//Js for simple page
if ($match['name'] == 'page') {
    echo '<script>console.log("scripts ....")</script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/simple-page.js"></script>';
}

if(strpos($match['name'],'crawlers_') !== false) {
    echo '<script src="' . SAP_SITE_URL . '/assets/js/select2.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/crawlers.js?id=1"></script>';
}

if(strpos($match['name'],'CPG') !== false) {
    echo '<script src="' . SAP_SITE_URL . '/assets/js/select2.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/cpg.js"></script>';
}


if(strpos($match['name'],'crawler_logs') !== false) {
    echo '<script src="' . SAP_SITE_URL . '/assets/js/select2.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/cpg.js"></script>';
}

if((strpos($match['name'],'bots') !== false || strpos($match['name'],'bots_edit')) && strpos($match['name'],'bots_profiles') === false) {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/bots.js?id=4"></script>';
}

if(strpos($match['name'],'bots_profiles') !== false) {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/bots_profiles.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/slider.js"></script>';
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/clone.js"></script>';
}

if(strpos($match['name'],'settings') !== false) {
    echo '<script type="text/javascript" src="' . SAP_SITE_URL . '/assets/js/channel_settings.js?id=2"></script>';
}



?>
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>

<script src="<?php echo SAP_SITE_URL . '/assets/js/iframe-handler.js' ?>"></script>

<script>
    const __SAP_SITE_URL = "<?php echo SAP_SITE_URL ?>";
</script>
<script src="<?php echo SAP_SITE_URL . '/assets/js/helpers.js' ?>"></script>
</body>
</html>