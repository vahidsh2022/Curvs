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


include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';


// Get user's active networks
$networks = sap_get_users_networks();
$networks_count = sap_get_users_networks_count();


//Get Facebook options
$sap_facebook_options = array();
if (in_array('facebook', $networks)) {
    $sap_facebook_options = $this->settings->get_user_setting('sap_facebook_options');
    $sap_facebook_options = !empty($sap_facebook_options) ? $sap_facebook_options : array();
}

//Get Linkdin options
$sap_linkedin_options = array();
if (in_array('linkedin', $networks)) {
    $sap_linkedin_options = $this->settings->get_user_setting('sap_linkedin_options');
    $sap_linkedin_options = !empty($sap_linkedin_options) ? $sap_linkedin_options : array();
}

//Get Twitter options
$sap_twitter_options = array();
if (in_array('twitter', $networks)) {
    $sap_twitter_options = $this->settings->get_user_setting('sap_twitter_options');
    $sap_twitter_options = !empty($sap_twitter_options) ? $sap_twitter_options : array();
}

//Get Telegram options
$sap_telegram_options = [];
if (true || in_array('telegram', $networks)) {
    $sap_telegram_options = $this->settings->get_user_setting('sap_telegram_options');
    $sap_telegram_options = !empty($sap_telegram_options) ? $sap_telegram_options : [];
}

//Get Youtube options
$sap_youtube_options = array();
if (in_array('youtube', $networks)) {
    $sap_youtube_options = $this->settings->get_user_setting('sap_youtube_options');
    $sap_youtube_options = !empty($sap_youtube_options) ? $sap_youtube_options : array();
}

//Get Tumblr options
$sap_tumblr_options = array();
if (in_array('tumblr', $networks)) {
    $sap_tumblr_options = $this->settings->get_user_setting('sap_tumblr_options');
    $sap_tumblr_options = !empty($sap_tumblr_options) ? $sap_tumblr_options : array();
}

//Get Pinterest options
$sap_pinterest_options = array();
if (in_array('pinterest', $networks)) {
    $sap_pinterest_options = $this->settings->get_user_setting('sap_pinterest_options');
    $sap_pinterest_options = !empty($sap_pinterest_options) ? $sap_pinterest_options : array();
}

//Get GMB options
$sap_gmb_options = array();
if (in_array('gmb', $networks)) {
    $sap_gmb_options = $this->settings->get_user_setting('sap_google_business_options');
    $sap_gmb_options = !empty($sap_gmb_options) ? $sap_gmb_options : array();
}

//Get GMB options
$sap_instagram_options = array();
if (in_array('instagram', $networks)) {
    $sap_instagram_options = $this->settings->get_user_setting('sap_instagram_options');
    $sap_instagram_options = !empty($sap_instagram_options) ? $sap_instagram_options : array();
}

// Reddit
$sap_reddit_options = array();
if (in_array('reddit', $networks)) {
    $sap_reddit_options = $this->settings->get_user_setting('sap_reddit_options');
    $sap_reddit_options = !empty($sap_reddit_options) ? $sap_reddit_options : array();
}

// Blogger
$sap_blogger_options = array();
if (in_array('blogger', $networks)) {
    $sap_blogger_options = $this->settings->get_user_setting('sap_blogger_options');
    $sap_blogger_options = !empty($sap_blogger_options) ? $sap_blogger_options : array();
}

//Wordpress
$sap_wordpress_options = array();
if (in_array('wordpress', $networks)) {
    $sap_wordpress_options = $this->settings->get_user_setting('sap_wordpress_options');
    $sap_wordpress_options = !empty($sap_wordpress_options) ? $sap_wordpress_options : array();
}


if (!class_exists('SAP_Linkedin')) {
    include(CLASS_PATH . 'Social' . DS . 'liConfig.php');
}
$linkedin = new SAP_Linkedin();

if (!class_exists('SAP_Facebook')) {
    include(CLASS_PATH . 'Social' . DS . 'fbConfig.php');
}
$facebook = new SAP_Facebook();

if (!class_exists('SAP_Pinterest')) {
    include(CLASS_PATH . 'Social' . DS . 'pinConfig.php');
}
$pinterest = new SAP_Pinterest();

if (!class_exists('SAP_Gmb')) {
    include(CLASS_PATH . 'Social' . DS . 'gmbConfig.php');
}
$google_buisness = new SAP_Gmb();

if (!class_exists('SAP_Instagram')) {
    include(CLASS_PATH . 'Social' . DS . 'instaConfig.php');
}
$instagram = new SAP_Instagram();

if (!class_exists('SAP_Reddit')) {
    include(CLASS_PATH . 'Social' . DS . 'redditConfig.php');
}
$reddit = new SAP_Reddit();

if (!class_exists('SAP_Youtube')) {
    include(CLASS_PATH . 'Social' . DS . 'youtubeConfig.php');
}
$youtube = new SAP_Youtube();

if (!class_exists('SAP_Blogger')) {
    include(CLASS_PATH . 'Social' . DS . 'bloggerConfig.php');
}

$blogger = new SAP_Blogger();

if (!class_exists('SAP_Tumblr')) {
    include(CLASS_PATH . 'Social' . DS . 'tumblrConfig.php');
}

$tumblr = new SAP_Tumblr();


if (!class_exists('SAP_Wordpress_Config')) {
    include(CLASS_PATH . 'Social' . DS . 'wordpressConfig.php');
}

$wordpress = new SAP_Wordpress_Config();

$to_get_social_posting_error = array();
$status_meta = array();
$link_to_post = array();
$all_status = array();
$sap_schedule_time = '';

//Get Post data
if (!empty($match['params']['id'])) {
    $post_id = $match['params']['id'];
    $post_data = $this->get_post($post_id, true);

    $sap_networks = $this->get_post_meta($post_id, 'sap_networks');

    if (isset($post_data->status) && $post_data->status == 2) {
        $sap_schedule_time = $this->get_post_meta($post_id, 'sap_schedule_time');
    }

    if (empty($sap_schedule_time)) {
        $sap_schedule_time = $this->get_post_meta($post_id, 'sap_schedule_time');

    }

    $post_meta = array();
    if (!empty($sap_networks)) {
        foreach ($sap_networks as $key => $sap_network) {
            if ($key == 'fb_accounts') {
                $post_meta['facebook_accounts'] = $sap_network;
            } else {
                $post_meta[$key] = $sap_network;
            }
        }
    }


    if (!empty($post_meta)) {

        foreach ($post_meta as $post_meta_key => $post_meta_value) {
            if (strpos($post_meta_key, "_accounts")) {

                $to_get_social_posting_error[] = str_replace("_accounts", "", $post_meta_key);
                $status_key = str_replace("_accounts", "", $post_meta_key);

                $get_post_status = '';
                if ($status_key === 'facebook') {
                    $get_post_status = $this->get_post_meta($post_id, 'fb_status');


                } elseif ($status_key === 'instagram') {


                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $status_key . '_status');


                    if (empty($get_post_status)) {
                        $get_post_status = $this->get_post_meta($post_id, $status_key . '_status');
                    }
                } elseif ($status_key == 'reddit') {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $status_key . '_status');
                } elseif ($status_key == 'youtube') {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_yt_status');
                } elseif ($status_key == 'blogger') {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $status_key . '_status');
                    if (empty($get_post_status)) {
                        $get_post_status = $this->get_post_meta($post_id, $status_key . '_status');
                    }
                } else {

                    $get_post_status = $this->get_post_meta($post_id, $status_key . '_status');
                    if (empty($get_post_status)) {
                        $get_post_status = $this->get_post_meta($post_id, '_sap_' . $status_key . '_status');

                    }
                }

                $all_status[$status_key] = $get_post_status;
                $link_to_post[] = $this->get_post_meta($post_id, 'sap_' . $status_key . '_link_to_post');
            } elseif (strpos($post_meta_key, "_locations")) {

                $to_get_social_posting_error[] = str_replace("_locations", "", $post_meta_key);
                $status_key = str_replace("_locations", "", $post_meta_key);
                $get_post_status = $this->get_post_meta($post_id, $status_key . '_status');
                if (empty($get_post_status)) {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $status_key . '_status');
                }
                $all_status[$status_key] = $get_post_status;
                $link_to_post[] = $this->get_post_meta($post_id, 'sap_' . $status_key . '_link_to_post');
            } elseif (in_array($post_meta_key, $networks)) {

                $to_get_social_posting_error[] = $post_meta_key;

                if ($post_meta_key === 'facebook') {
                    $get_post_status = $this->get_post_meta($post_id, 'fb_status');
                } elseif ($post_meta_key === 'instagram') {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $post_meta_key . '_status');
                    if (empty($get_post_status)) {
                        $get_post_status = $this->get_post_meta($post_id, $post_meta_key . '_status');
                    }
                } else {
                    $get_post_status = $this->get_post_meta($post_id, '_sap_' . $post_meta_key . '_status');
                }

                $all_status[$post_meta_key] = $get_post_status;

            }
        }

    }


    if (!empty($to_get_social_posting_error)) {

        foreach ($to_get_social_posting_error as $to_get_social_posting_error_key => $to_get_social_posting_error_value) {
            if ($to_get_social_posting_error_value == 'facebook') {
                $acc = 'fb';
            } else {
                $acc = $to_get_social_posting_error_value;
            }

            $status_meta[$to_get_social_posting_error_value] = $this->get_post_meta($post_id, 'sap_' . $acc . '_posting_error');
        }
    }
}


if (isset($post_data) && !empty($post_data)) {
    $preview_date = (isset($post_data->created_date) && !empty($post_data->created_date)) ? $post_data->created_date : "";
    $preview_image = (isset($post_data->image) && !empty($post_data->image)) ? $post_data->image : "";
    if (!empty($preview_image)) {
        $preview_image = SAP_IMG_URL . str_replace(SAP_IMG_URL, "", $preview_image);
    }
    $preview_link = (isset($post_data->share_link) && !empty($post_data->share_link)) ? $post_data->share_link : "";
    $preview_message = (isset($post_data->message) && !empty($post_data->message)) ? $post_data->message : "";
    $preview_video = $post_data->video;

    $extension = '';

    if (!empty($preview_video)) {

        $preview_video_url = SAP_SITE_URL . '/uploads/' . $preview_video;
        $file_data = pathinfo($preview_video_url);
        $extension = $file_data['extension'];
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content sap-quick-post">
        <div class="row">
            <?php if ($sap_common->sap_is_license_activated()) { ?>
            <?php echo $this->flash->renderFlash(); ?>

            <?php

            if (isset($match['params']['id']) && !empty($match['params']['id'])) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
                    <?php if (!empty($status_meta)) { ?>
                        <div class="sap-quick-post-privew-wrap">
                            <?php // Check if 'twitter' key exists in the array and remove it if it does
                            if (array_key_exists('twitter', $status_meta)) {
                                unset($status_meta['twitter']);
                            }

                            if (array_key_exists('telegram', $status_meta)) {
                                unset($status_meta['telegram']);
                            }

                            // Check if 'linkedin' key exists in the array and remove it if it does
                            if (array_key_exists('linkedin', $status_meta)) {
                                unset($status_meta['linkedin']);
                            }

                            // Check if 'pinterest' key exists in the array and remove it if it does
                            if (array_key_exists('pinterest', $status_meta)) {
                                unset($status_meta['pinterest']);
                            }

                            foreach ($status_meta as $status_meta_key_ => $status_meta_value) {


                                $scheduled_date = '';
                                $social_class = "sap-quick-post-privew-" . $status_meta_key_;
                                $get_all_networks = $this->sap_get_supported_networks();

                                $get_status = (isset($all_status[$status_meta_key_])) ? $all_status[$status_meta_key_] : '';
                                $social_link_to_post = '';

                                if ($status_meta_key_ == 'facebook') {
                                    $status_meta_key = 'fb';
                                } else {
                                    $status_meta_key = $status_meta_key_;
                                }

                                if ($get_status === '1' || $get_status === 1) {
                                    $status = "Published";
                                    $social_link_to_post = $this->get_post_meta($post_id, 'sap_' . $status_meta_key . '_link_to_post');
                                } elseif ($get_status === '2' || $get_status === 2) {
                                    $status = "Scheduled";
                                    $individual_time = $this->get_post_meta($post_id, 'sap_schedule_time_' . $status_meta_key);
                                    $scheduled_date = date("F j, Y g:i a", !empty($individual_time) ? $individual_time : $sap_schedule_time);
                                } else {
                                    $status = "Unpublished";
                                }

                                $preview_social_title = (isset($get_all_networks[$status_meta_key])) ? $get_all_networks[$status_meta_key] : '';

                                ?>
                                <div class="sap-quick-post-privew <?php echo $social_class; ?>">
                                    <div class="sap-quick-post-privew-header">
                                        <div class="sap-quick-post-privew-header-h2">
                                            <h2><?php if (!empty($social_link_to_post)) {
                                                    echo '<a href="' . $social_link_to_post . '" target="_blank">';
                                                } ?><?php echo $preview_social_title;
                                                if (!empty($social_link_to_post)) {
                                                    echo '</a>';
                                                } ?></h2>
                                            <p class="sap-quick-post-privew-date"><?php echo date("F j Y g:i a", strtotime($preview_date)); ?></p>
                                        </div>
                                        <div class="sap-quick-post-privew-header-p">
                                            <p class="<?php echo lcfirst($status); ?>"><?php echo $status; ?></p>
                                            <?php if (!empty($scheduled_date)) { ?>
                                                <p class="scheduled_date"><?php echo $scheduled_date ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="sap-quick-post-privew-content">
                                        <?php if (!empty($preview_image)) { ?>
                                            <div class="sap-quick-post-privew-image">
                                                <?php if (!empty($social_link_to_post)) {
                                                    echo '<a href="' . $social_link_to_post . '" target="_blank">';
                                                } ?>
                                                <img src="<?php echo $preview_image; ?>">
                                                <?php if (!empty($social_link_to_post)) {
                                                    echo '</a>';
                                                } ?>
                                            </div>

                                        <?php } ?>

                                        <?php if (!empty($preview_video_url)) { ?>

                                            <?php if ($extension == 'mkv' || $extension == 'mov') { ?>
                                                <div class="sap-quick-post-privew-video">
                                                    <h4>Video preview is not supported for ( .mkv and .mov ) file
                                                        formats.</h4>
                                                    <h4>Video URL :
                                                        <a href="<?php echo $preview_video_url; ?>"
                                                           target="_blank"><?php echo $preview_video_url; ?></a>
                                                    </h4>
                                                </div>
                                            <?php } else { ?>
                                                <div class="sap-quick-post-privew-video">
                                                    <video width="100%" height="100%" controls>
                                                        <source src="<?php echo $preview_video_url; ?>"
                                                                type="video/<?php echo $extension; ?>">
                                                    </video>
                                                </div>
                                            <?php } ?>


                                        <?php } ?>

                                        <div class="sap-quick-post-new">
                                            <div class="sap-quick-post-privew-message">
                                                <a href="<?php echo $social_link_to_post; ?>">
                                                    <p><?php echo $preview_message; ?></p></a>
                                            </div>
                                            <?php if (is_array($status_meta_value) && !empty($status_meta_value)) { ?>

                                                <div class="sap-quick-post-privew-users-wpar">
                                                    <?php
                                                    foreach ($status_meta_value as $status_meta_user_key => $status_meta_user_value) {


                                                        if (is_array($status_meta_user_value) && isset($status_meta_user_value['status'])) {
                                                            $preview_user_message = '';
                                                            $preview_user_status = $status_meta_user_value['status'];

                                                            if (is_numeric($status_meta_user_key)) {
                                                                if (isset($status_meta_user_value['message'])) {
                                                                    $preview_user_message = "Posted On have an error " . $status_meta_user_value['message'];
                                                                } else {
                                                                    $preview_user_message = "Posted On have an error ";
                                                                }
                                                            } else {

                                                                if ($preview_user_status === 'success') {
                                                                    $preview_user_message = "Posted On " . $status_meta_user_key . " Successfully.";
                                                                } else {
                                                                    if (isset($status_meta_user_value['message'])) {
                                                                        $preview_user_message = "Posted On " . $status_meta_user_key . " have an error " . $status_meta_user_value['message'];
                                                                    } else {
                                                                        $preview_user_message = "Posted On " . $status_meta_user_key . " have an error ";
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        $message_class = $status_meta_user_value['status'];
                                                        ?>
                                                        <p class="sap-quick-post-privew-user-status <?php echo $message_class; ?>"><?php echo $preview_user_message; ?></p>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($preview_link)) { ?>
                                            <div class="sap-quick-post-privew-link">
                                                <a href="<?php echo $preview_link; ?>"><?php echo $preview_link; ?></a>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php
            $schedule_tab_active = $publish_tab_active = $schedule_content_active = $publish_content_active = "";
            if (isset($sap_schedule_time) && !empty($sap_schedule_time)) {
                $schedule_li_active = "sap-tab-li-active";
                $schedule_tab_active = "sap-tab-nav-active";
                $schedule_content_active = "sap-tab-content-active";
            } else {
                $publish_li_active = "sap-tab-li-active";
                $publish_tab_active = "sap-tab-nav-active";
                $publish_content_active = "sap-tab-content-active";
            }
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right sap-tab-wrap">
                <div class="sap-tab-nav-wrap">
                    <ul class="nav nav-tabs">
                        <li class="sap-right-tab-li <?php echo $publish_li_active; ?>">
                            <a href="#" class="sap-tab-nav <?php echo $publish_tab_active; ?>"
                               id="published"><?php echo $sap_common->lang('published'); ?></a>
                        </li>
                        <li class="sap-right-tab-li <?php echo $schedule_li_active; ?>">
                            <a href="#" class="sap-tab-nav <?php echo $schedule_tab_active; ?>"
                               id="scheduled"><?php echo $sap_common->lang('scheduled'); ?></a>
                        </li>
                    </ul>
                </div>
                <!--                --><?php //$all_posts = $this->get_posts_by_status(1); ?>
                <div class="sap-tab-content-wrap 456">
                    <div class="sap-tab-content sap-tab-content-published <?php echo $publish_content_active; ?>"
                         id="published">
                        <div class="box-body sap-custom-drop-down-wrap 789">
                            <div class="d-flex flex-wrap row">
                                <div class="col-md-4">
                                    <select id='searchByGender' class="searchByGender_div" style="width: 100%;">
                                        <option value=''><?php echo $sap_common->lang('bulk_action'); ?></option>
                                        <option value='delete'><?php echo $sap_common->lang('delete'); ?></option>
                                    </select>
                                </div>
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
                                    <th data-sortable="false" data-width="10px"><input type="checkbox"
                                                                                       class="quickpost-select-all"/>
                                    </th>
                                    <th>ID</th>
                                    <th><?php echo $sap_common->lang('message') ?> </th>
                                    <th><?php echo $sap_common->lang('networks') ?></th>
                                    <th><?php echo $sap_common->lang('image_video') ?></th>
                                    <th data-sortable="false"
                                        class="quick-post-th-action"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <?php $all_posts = $this->get_posts_by_status(2); ?>
                    <div class="sap-tab-content sap-tab-content-scheduled <?php echo $schedule_content_active; ?>"
                         id="scheduled">
                        <div class="box-body sap-custom-drop-down-wrap">
                            <div class="scheduled-top-wrap d-flex flex-wrap row">
                                <div class="col-md-4">
                                    <select id='searchByGender' class="searchByGender_div">
                                        <option value=''><?php echo $sap_common->lang('bulk_action'); ?></option>
                                        <option value='delete'><?php echo $sap_common->lang('delete'); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <!-- DataTables Search Filter outside DataTables Wrapper -->
                                    <div id="customSearch" class="customSearch">
                                        <input type="text" id="searchInputscheduleds" class="custom-search-input"
                                               placeholder="Type to search">
                                    </div>
                                </div>
                            </div>

                            <table id="list-post-scheduled" class="display table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th data-sortable="false" data-width="10px"><input type="checkbox"
                                                                                       class="quickpost-select-all"/>
                                    </th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('message'); ?></th>
                                    <th data-sortable="true"><?php eLang('networks'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('image_video'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('date'); ?></th>
                                    <th data-sortable="false"
                                        class="quick-post-th-action"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th data-sortable="false" data-width="10px"><input type="checkbox"
                                                                                       class="quickpost-select-all"/>
                                    </th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('message'); ?></th>
                                    <th data-sortable="true"><?php eLang('networks'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('image_video'); ?></th>
                                    <th data-sortable="true"><?php echo $sap_common->lang('date'); ?></th>
                                    <th data-sortable="false"><?php echo $sap_common->lang('action'); ?></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                <?php
                                $page_query = $_GET['params'];
                                $page_data = explode("/", $page_query);
                                $selected_id = !empty($page_data[1]) ? $page_data[1] : '';


                                if (count($all_posts) > 0) {
                                    foreach ($all_posts as $quick_post) {
                                        ?>
                                        <tr id="quick_post_<?php echo $quick_post->post_id; ?>">
                                            <td><input type="checkbox" name="post_id[]"
                                                       value="<?php echo $quick_post->post_id; ?>" <?php echo($quick_post->post_id == $selected_id ? 'checked' : ''); ?>/>
                                            </td>
                                            <td>
                                                <a aria-data-id="<?php echo $quick_post->post_id; ?>"
                                                   class="edit_quick_post post-detail">
                                                    <?php echo !empty($quick_post->message) ? $this->common->sap_content_excerpt($quick_post->message, 65) : ''; ?>
                                                </a>
                                            </td>
                                            <td><?php
                                                // foreach (unserialize($quick_post->networks) as $network => $space) {
                                                //     if (is_array($space))
                                                //         $space = implode($space);
                                                //     echo $crawlers->getIconByPlatform($network) . $space;
                                                // }
                                                ?></td>
                                            <td>
                                                <?php
                                                $uploadsDirURl = SAP_IMG_URL;
                                                $assetsDirURL = SAP_SITE_URL . '/assets';
                                                if (!empty($quick_post->media)) {
                                                    echo "<div class='flex gap-2'>";
                                                    foreach (json_decode($quick_post->media, true) as $media) {
                                                        $mediaSrc = $uploadsDirURl . $media['src'];
                                                        if (mediaIsImage($mediaSrc)) {
                                                            echo "<div><a href='$mediaSrc' target='_blank'><img class='media-thumbnail' src='$mediaSrc'></a></div>";
                                                        } else if (mediaIsVideo($mediaSrc)) {
                                                            echo "<div><a href='$mediaSrc' target='_blank'><i class='fa fa-file-video-o' aria-hidden='true'></i></video></a><div>";
                                                        }
                                                    }
                                                    echo "</div>";
                                                } else if (!empty($quick_post->video)) {
                                                    $mediaSrc = $uploadsDirURl . $quick_post->video;
                                                    echo "<a href='$mediaSrc' target='_blank'><i class='fa fa-file-video-o' aria-hidden='true'></i></video></a>";
                                                } elseif (!empty($quick_post->image)) {
                                                    $mediaSrc = $uploadsDirURl . $quick_post->image;
                                                    echo "<img class='media-thumbnail' src='$mediaSrc'>";
                                                } else {
                                                    echo "<img class='media-thumbnail' src='$assetsDirURL/images/no-imag.png'>";
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            $shedule = $this->get_post_meta($quick_post->post_id, 'sap_schedule_time');
                                            ?>
                                            <td class="quick-status">
                                                <span <?php echo !empty($shedule) && $quick_post->status == 2 ? 'data-toggle="tooltip" title="' . date('Y-m-d H:i', $shedule) . '" ' : '' ?> data-placement="left"><?php echo date("M j, Y", strtotime($quick_post->created_date)); ?></span>
                                            </td>
                                            <td class="action_icons">

                                                <!--                                                <a href="-->
                                                <?php //echo SAP_SITE_URL . '/quick-post/view/' . $quick_post->post_id; ?><!--"-->
                                                <!--                                                   class="edit_quick_post" data-toggle="tooltip" title="Edit"-->
                                                <!--                                                   data-placement="top"-->
                                                <!--                                                   aria-data-id="-->
                                                <?php //echo $quick_post->post_id; ?><!--"><i-->
                                                <!--                                                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>-->

                                                <a class="delete_quick_post" data-toggle="tooltip" title="Delete"
                                                   data-placement="top"
                                                   aria-data-id="<?php echo $quick_post->post_id; ?>"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                <a title="Edit"
                                                   href="<?php echo $router->generate('quick_viewpost', ['id' => $quick_post->post_id]); ?>"><i
                                                            class="fa fa-pencil" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } else {
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left"><div class="alert alert-error">License Not registered</div></div>';
                } ?>
            </div>

        </div>
    </section>

</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"
                                                                                               aria-hidden="true"></i>
                </button>
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

        $(document).on('click', ".sap-tab-nav", function (e) {
            e.preventDefault();

            var id = $(this).attr("id");
            $("a.sap-tab-nav").each(function (index, element) {
                $(this).removeClass("sap-tab-nav-active");
                $(this).closest('ul').find('li').removeClass("sap-tab-li-active");
            });
            $(".sap-tab-content").each(function (index, element) {
                $(this).removeClass("sap-tab-content-active");
            });
            $(this).addClass("sap-tab-nav-active");
            $(this).closest('li').addClass("sap-tab-li-active");
            $(".sap-tab-content#" + id).addClass("sap-tab-content-active");
            $('#list-post-scheduled').removeAttr('style');
        });

        const listPostDT = $('#list-post').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            searching: false,
            lengthChange: false,
            ajax: {
                url: "<?php echo $router->generate('quick_post_by_status_json') ?>?status=2",
                data: function(d) {
                    d.searchValue = $('#searchInputquickpost').val();
                },
                type: "GET",
                dataSrc: "data" // مسیر آرایه‌ای که پست‌ها درش هستن
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<input type="checkbox" name="post_id[]" class="row-select" value="${row.post_id}">`;
                    }
                },
                {
                    data: null, orderable: false,
                    searchable: false, render: function (data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart
                    }
                },
                {
                    data: "message", render: function (data, type, row) {
                        return String(data).substring(0, 100) + ' ...';
                    }
                },
                {
                    data: null, orderable: false,
                    searchable: false, render: function (data, type, row) {
                        return '';
                        // let output = '';
                        // for (let network in data) {
                        //     let space = data[network];
                        //     if (Array.isArray(space)) {
                        //         space = space.join(", ");
                        //     }
                        //     output += `<span class="network">${network}: ${space}</span><br>`;
                        // }
                        // console.log({data,output,type,row})
                        // return output;
                    },
                },
                {
                    data: null, orderable: false,
                    searchable: false, render: function (data, type, row) {
                        const uploadsDirURL = "<?= SAP_IMG_URL ?>";
                        const assetsDirURL = "<?= SAP_SITE_URL ?>/assets";
                        let output = "";

                        if (row.media) {
                            try {
                                const mediaArray = JSON.parse(row.media);
                                output += `<div class='flex gap-2'>`;
                                mediaArray.forEach(media => {
                                    const src = uploadsDirURL + media.src;
                                    if (isImage(src)) {
                                        output += `<div><a href="${src}" target="_blank"><img class="media-thumbnail" src="${src}" /></a></div>`;
                                    } else if (isVideo(src)) {
                                        output += `<div><a href="${src}" target="_blank"><i class='fa fa-file-video-o' aria-hidden='true'></i></a></div>`;
                                    }
                                });
                                output += `</div>`;
                            } catch (e) {
                                output = "خطا در پردازش مدیا";
                            }
                        } else if (row.video) {
                            const src = uploadsDirURL + row.video;
                            output = `<a href="${src}" target="_blank"><i class='fa fa-file-video-o' aria-hidden='true'></i></a>`;
                        } else if (row.image && row.image !== "0") {
                            const src = uploadsDirURL + row.image;
                            output = `<img class="media-thumbnail" src="${src}" />`;
                        } else {
                            output = `<img class="media-thumbnail" src="${assetsDirURL}/images/no-imag.png" />`;
                        }

                        return output;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        const editUrl = `<?= $router->generate('quick_viewpost', ['id' => '__POST_ID__']) ?>`.replace('__POST_ID__', row.post_id);
                        const editBtn = `<a title="Edit" href="${editUrl}"><i class="fa fa-pencil" aria-hidden="true"></i></a>`;

                        const deleteBtn = `<a class="delete_quick_post" data-toggle="tooltip" title="Delete" data-placement="top" aria-data-id="${row.post_id}"><i class="fa fa-trash" aria-hidden="true"></i></a>`;
                        return `<td class="action_icons">${deleteBtn} ${editBtn}</td>`;
                    }
                }
            ],
            createdRow: function(row,data,dataIndex) {
              $(row).attr('id','quick_post_' + data.post_id);
            },
            drawCallback: function() {
                $(document).on('click', '.delete_quick_post', function () {
                    var obj = $(this);
                    var post_id = $(this).attr('aria-data-id');
                    console.log({post_id})
                    if (confirm("<?php echo $sap_common->lang('delete_record_conform_msg'); ?>")) {
                        $.ajax({
                            type: 'POST',
                            url: SAP_SITE_URL + '/quick-post/delete/',
                            data: {post_id: post_id},
                            success: function (result) {
                                var result = jQuery.parseJSON(result);
                                if (result.status) {
                                    $('#quick_post_' + post_id).remove();
                                    if ($("#list-post tbody tr").length == 0) {
                                        $("#list-post").find('tbody').append('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr>');
                                    }
                                }
                            }
                        });
                    }
                });

                $(document).on('change', '.searchByGender_div', function () {
                    var selected_val = $(this).find('option:selected').val();
                    console.log('change',{selected_val})
                    if (selected_val == 'delete') {
                        var id = [];
                        $("input[name='post_id[]']:checked").each(function (i) {
                            id[i] = $(this).val();
                        });

                        //tell you if the array is empty
                        if (id.length === 0) {
                            alert("<?php echo $sap_common->lang('select_checkbox_alert'); ?>");

                        } else if (confirm("<?php echo $sap_common->lang('delete_selected_records_conform_msg'); ?>")) {

                            $.ajax({
                                url: SAP_SITE_URL + '/quick-post/delete_multiple/',
                                method: 'POST',
                                data: {id: id},
                                success: function (result) {
                                    // window.location.reload();
                                    console.log({result})
                                    // var result = jQuery.parseJSON(result);
                                    // if (result.status) {
                                    //     window.location.replace(result.redirect_url);
                                    // }
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                });
            }
        });

        // Attach DataTables search to custom input
        let searchListPostDTTimeout;
        $('#searchInputquickpost').on('keyup', function () {
            if (searchListPostDTTimeout) {
                clearTimeout(searchListPostDTTimeout);
            }

            searchListPostDTTimeout = setTimeout(() => {
                listPostDT.ajax.reload();
            }, 500);
        });

        $('#list-post-scheduled').DataTable({
            "oLanguage": {
                "sEmptyTable": "No post found."
            },
            "aLengthMenu": [[15, 25, 50, 100], [15, 25, 50, 100]],
            "pageLength": 15,
            "pagingType": "full",
            "dom": 'lrtip',
            "order": [],
            "autoWidth": false,
            "columnDefs": [
                {"width": "17px", "targets": 0},
                {width: '220px', targets: 1},
                {width: '80px', targets: 3},
                {
                    'targets': [0, 3],
                    'orderable': false
                }
            ],
            "columns": [
                {data: "post_id"},

            ],
        });

        // Attach DataTables search to custom input
        $('#searchInputscheduleds').on('keyup', function () {
            $('#list-post-scheduled').DataTable().search(this.value).draw();
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

        $(document).on("error", "#list-post img", function () {
            $(this).attr("src", "<?php echo SAP_SITE_URL . '/assets/images/no-imag.png' ?>");
        });
        $(document).on("error", "#list-post-scheduled img", function () {
            $(this).attr("src", "<?php echo SAP_SITE_URL . '/assets/images/no-imag.png' ?>");
        });
    });
</script>
