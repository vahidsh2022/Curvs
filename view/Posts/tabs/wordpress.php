<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
// Getting wordpress all accounts

$networks_count = sap_get_users_networks_count();
$wordpress_post_status = array('Unpublished', 'Published', 'Scheduled');
$sap_wordpress_options = $this->settings->get_user_setting('sap_wordpress_options');
$sap_wordpress_grant_data = $this->settings->get_user_setting('sap_wordpress_sess_data'); // Getting facebook app grant data

$sap_wordpress_post_accounts =array();
if (!empty($post_id)) {

    $status = $this->get_post_meta($post_id, '_sap_wordpress_status');
    $sap_wordpress_post_custom_title = $this->get_post_meta($post_id, '_sap_wordpress_post_custom_title');
    $sap_wordpress_post_accounts = $this->get_post_meta($post_id, '_sap_wordpress_post_accounts');
    $sap_wordpress_post_message = $this->get_post_meta($post_id, '_sap_wordpress_post_message');
    $sap_schedule_time_wordpress = $this->get_post_meta($post_id, 'sap_schedule_time_wordpress');
    $sap_wordpress_post_img = $this->get_post_meta($post_id, '_sap_wordpress_post_image');

    $sap_wordpress_post_accounts =!empty($sap_wordpress_post_accounts)?$sap_wordpress_post_accounts:array();
}


    
    ?>
    <div class="row">
        <div class="col-sm-12 margin-bottom">
            <div class="form-group">
                <label class="col-sm-4 col-xs-5"><?php echo $sap_common->lang('status'); ?>
                    <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Status of Facebook post i.e. published/unpublished/scheduled."></i>
                </label>
                <div class="col-sm-8 col-xs-7">
                    <?php

                    if (isset($status) && array_key_exists($status, $wordpress_post_status)) {
                     echo '<label class="_sap_fb_status_lbl status-text">'.$wordpress_post_status[$status].'</label>';
                     echo '<button class="btn btn-primary reset_post_status btn-xs" aria-data-id="'.$post_id.'" aria-type="facebook" aria-label="_sap_fb_status" type="button" ><i class="fa fa-refresh" aria-hidden="true"></i> Reset Status</button>';
                 }else{
                    echo '<label class="_sap_fb_status_lbl status-Unpublished">Unpublished</label>';
                }?>
            </div>
        </div>
        
    </div>
    <div class="col-sm-12 margin-bottom">
        <div class="form-group wp-selector">
            <label for="sap_fb_user_id" class="col-sm-4 control-label"><?php echo $sap_common->lang('post_to_wordpress_account'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Select an account to which you want to publish a post. This setting overrides the general settings. Leave it empty to use the general default settings."></i>
            </label>
            <div class="col-sm-5">
                <?php
                    $wordpress_urls = $wordpress_config->sap_get_wordpress_urls();
                ?>
                <select class="form-control sap_select" tabindex="6" name="sap_wordpress[accounts][]" multiple="multiple" id="sap_wordpress_user_id" data-placeholder="Select User">
                <?php
                    
                    if ( !empty( $wordpress_urls ) && is_array( $wordpress_urls ) ) {
                        $wordpress_cnt =1;
                        $wordpress_count =  $networks_count['wordpress'];
                        foreach ( $wordpress_urls as $uid => $uname ) {
                            if( $wordpress_cnt > $wordpress_count && $wordpress_count >0){
                                break;
                            }
                            $wordpress_cnt++;
                            ?>
                            <option value="<?php echo $uname;?>" <?php echo ( in_array($uname,$sap_wordpress_post_accounts))?"selected":"";?>><?php echo $uname;?></option>
                        <?php } ?>
                    <?php } // End of foreach ?>
                </select>
                <div class="button-Select sap-mt-1">
                    <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="wp-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                    <button type="button" class="btn btn-light deselect_all" data-parent="wp-selector"><?php echo $sap_common->lang('select_none'); ?></button>
                </div>
            </div>
            <!-- <div class="col-sm-3">
                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="wp-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                <button type="button" class="btn btn-light deselect_all" data-parent="wp-selector"><?php echo $sap_common->lang('select_none'); ?></button>
            </div> -->
        </div>
    </div>

    <div class="col-sm-12 margin-bottom hide-wordpress-custom-link">
        <div class="form-group">
            <label for="sap_linkedin_custom_link" class="col-sm-4 control-label"><?php echo $sap_common->lang('post_title'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Here you can enter the custom link which will be used for the wall post. The link must start with http://"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" tabindex="21" class="form-control " name="sap_wordpress[custom_title]" id="sap_wordpress_custom_link" value="<?php echo (!empty($sap_wordpress_post_custom_title) ? $sap_wordpress_post_custom_title :'');?>" placeholder="<?php echo $sap_common->lang('post_title'); ?>" />
            </div>
        </div>
    </div>
    <div class="col-sm-12 margin-bottom show-wordpress-image-post">
        <div class="form-group">
            <label for="sap_fb_post_img" class="col-sm-4 control-label">
                <?php echo $sap_common->lang('post_image'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Here you can upload a image which will be used for the Facebook wall post. Leave it empty to use the default image from the settings page.<br><br><strong>Note: </strong>This option only work if your facebook app version is below 2.9. If you're using latest facebook app, it wont work. <a href='https://developers.facebook.com/blog/post/2017/06/27/API-Change-Log-Modifying-Link-Previews/' target='_blank'>Learn More.</a>" data-html="true"></i>
            </label>
            <div class="col-sm-8">
                <?php if(!empty($sap_wordpress_post_img)) { ?>
                    <input id="sap_wordpress_post_img" name="sap_wordpress_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8" data-initial-preview="<img src='<?php echo SAP_IMG_URL.$sap_wordpress_post_img;?>'/>">
                <?php } else { ?>
                    <input id="sap_wordpress_post_img" name="sap_wordpress_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8">
                <?php } ?>
                <input type="hidden" name="sap_wordpress_post_img" class="sap-default-img" value="<?php echo !empty( $sap_wordpress_post_img )? $sap_wordpress_post_img :'';  ?>">
            </div>
        </div>
    </div>


    <div class="col-sm-12 margin-bottom">
        <div class="form-group">
            <label for="sap_fb_post_custom_message" class="col-sm-4 control-label"><?php echo $sap_common->lang('custom_message'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Here you can enter a custom content which will be used for the Facebook post. Leave it empty to use content of the current post." data-html="true"></i>
            </label>
            <div class="col-sm-8">                
                <textarea class="form-control" name="sap_wordpress[message]" id="sap_wordpress_post_custom_message" tabindex="5"><?php echo (!empty($sap_wordpress_post_message) ? $sap_wordpress_post_message : '');?></textarea>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="sap-schedule-time-wordpress" class="col-sm-4 control-label">
                <?php echo $sap_common->lang('individual_schedule'); ?>&nbsp;
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="This setting modifies the schedule global setting and overrides scheduled time. Keep it blank to use the global schedule settings."></i>
            </label>
            <div class="col-sm-4">
                <input type="text" name="sap-schedule-time-wordpress" id="sap-schedule-time-wordpress" placeholder="YYYY-MM-DD hh:mm" <?php echo !empty($sap_schedule_time_wordpress) ? 'value="' . date('Y-m-d H:i', $sap_schedule_time_wordpress) . '"' : ''; ?> readonly="" class="form-control sap-datetime wordpress-schedule-input">
            </div>
        </div>
    </div>
    <input type="hidden" name="networks[wordpress]" id="enable_wordpress" value="1">
</div>