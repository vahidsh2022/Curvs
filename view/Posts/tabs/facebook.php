<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
    // Getting facebook all accounts
$fb_accounts = $facebook->sap_get_fb_accounts('all_app_users_with_name');
$networks_count = sap_get_users_networks_count();

if (!empty($post_id)) {

    $status = $this->get_post_meta($post_id, '_sap_fb_status');
    $sap_fb_custom_msg = $this->get_post_meta($post_id, '_sap_fb_post_msg');
    $sap_fb_custom_img = $this->get_post_meta($post_id, '_sap_fb_post_image');
    $sap_fb_post_accounts = $this->get_post_meta($post_id, '_sap_fb_post_accounts');
    $sap_fb_posting_type = $this->get_post_meta($post_id, '_sap_fb_post_type');
    $sap_schedule_time_fb = $this->get_post_meta($post_id, 'sap_schedule_time_fb');
    $sap_fb_post_reel = $this->get_post_meta($post_id, '_sap_fb_post_reel');
    $sap_facebook_custom_link = $this->get_post_meta($post_id, '_sap_fb_post_custom_link');
}
$fb_post_status = array('Unpublished', 'Published', 'Scheduled');
$sap_facebook_options = $this->settings->get_user_setting('sap_facebook_options');
    $sap_facebook_grant_data = $this->settings->get_user_setting('sap_fb_sess_data'); // Getting facebook app grant data
    
    ?>
    <div class="row">
        <div class="col-sm-12 margin-bottom">
            <?php if( empty( $sap_facebook_grant_data ) ) {?>
                <div class="col-sm-12">
                    <div class="alert alert-danger sap-warning">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <?php echo $sap_common->lang('quick_post_facebook_help_text'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="col-sm-4 col-xs-5"><?php echo $sap_common->lang('status'); ?>
                    <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Status of Facebook post i.e. published/unpublished/scheduled."></i>
                </label>
                <div class="col-sm-8 col-xs-7">
                    <?php

                    if (isset($status) && array_key_exists($status, $fb_post_status)) {
                     echo '<label class="_sap_fb_status_lbl status-text">'.$fb_post_status[$status].'</label>';
                     echo '<button class="btn btn-primary reset_post_status btn-xs" aria-data-id="'.$post_id.'" aria-type="facebook" aria-label="_sap_fb_status" type="button" ><i class="fa fa-refresh" aria-hidden="true"></i> Reset Status</button>';
                 }else{
                    echo '<label class="_sap_fb_status_lbl status-Unpublished">Unpublished</label>';
                }?>
            </div>
        </div>
        
    </div>
    <div class="col-sm-12 margin-bottom">
        <div class="form-group fb-selector">
            <label for="sap_fb_user_id" class="col-sm-4 control-label"><?php echo $sap_common->lang('post_to_facebook_account'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Select an account to which you want to publish a post. This setting overrides the general settings. Leave it empty to use the general default settings."></i>
            </label>
            <div class="col-sm-5">
                <select class="form-control sap_select" tabindex="6" name="sap_facebook[accounts][]" multiple="multiple" id="sap_fb_user_id" data-placeholder="Select User">
                    <?php
                    if (!empty($fb_accounts) && is_array($fb_accounts)) {
                        $fb_type_post_user = (!empty($sap_fb_post_accounts)) ? $sap_fb_post_accounts : array();
                        $facebook_count =  $networks_count['facebook'];
                        $fb_count =1;
                        foreach ($fb_accounts as $aid => $aval) {

                            if( $fb_count > $facebook_count && $facebook_count >0){
                                break;
                            }
                            $fb_count++;
                            if (is_array($aval)) {
                                $fb_app_data = isset($sap_fb_sess_data[$aid]) ? $sap_fb_sess_data[$aid] : array();
                                $fb_user_data = isset($fb_app_data['sap_fb_user_cache']) ? $fb_app_data['sap_fb_user_cache'] : array();
                                $fb_opt_label = !empty($fb_user_data['name']) ? $fb_user_data['name'] . ' - ' : '';
                                $fb_opt_label = $fb_opt_label . $aid;
                                foreach ($aval as $aval_key => $aval_data) { ?>
                                    <option <?php echo in_array($aval_key, $fb_type_post_user) ? 'selected="selected"' : ''; ?> value="<?php echo $aval_key; ?>" ><?php echo $aval_data; ?></option>
                                <?php } ?>
                            <?php } 
                            else { ?>
                                <option <?php echo in_array($aid, $fb_type_post_user) ? 'selected="selected"' : ''; ?> value="<?php echo $aid; ?>" ><?php echo $aval; ?></option>
                                <?php
                            }
                        } // End of foreach
                    } // End of main if
                    ?>
                </select>
                <div class="button-Select sap-mt-1">
                    <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="fb-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                    <button type="button" class="btn btn-light deselect_all" data-parent="fb-selector"><?php echo $sap_common->lang('select_none'); ?></button>
                </div>
            </div>
            <!-- <div class="col-sm-3">
                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="fb-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                <button type="button" class="btn btn-light deselect_all" data-parent="fb-selector"><?php echo $sap_common->lang('select_none'); ?></button>
            </div> -->
        </div>
    </div>

    <div class="col-sm-12 margin-bottom">
        <div class="form-group"> 
            <label for="app-setting" class="col-sm-4 control-label"><?php echo $sap_common->lang('share_posting_type'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Select a posting type to which you want to publish a post. This setting overrides the general settings. Leave it empty to use the general default settings."></i>
            </label>
            <div class='col-sm-8'>
                <div class="tg-list-item">
                    <?php
                    $share_posting_type = array(
                        "link_posting" => 'Link posting',
                        "image_posting" => "Image posting",
                        "reel_posting" => "Reel posting",
                    );
                    ?>
                    <select class="sap_select sap_share_posting_type_fb_meta" id="sap_share_posting_type"  name="sap_facebook[type]">          
                        <?php
                        $selected_share_posting_type = !empty($sap_fb_posting_type) ? $sap_fb_posting_type : $sap_facebook_options['share_posting_type'];

                        if (!empty($share_posting_type)) {
                            foreach ($share_posting_type as $type => $share_posting_type) {
                                ?>
                                <option value="<?php echo $type ?>" <?php
                                if ($type == $selected_share_posting_type) {
                                    echo 'selected=selected';
                                } else {
                                    echo '';
                                }
                                ?>><?php echo $share_posting_type ?></option> 
                                <?php
                            }
                        }
                        ?>    

                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 margin-bottom hide-fb-custom-link">
        <div class="form-group">
            <label for="sap_linkedin_custom_link" class="col-sm-4 control-label"><?php echo $sap_common->lang('custom_link'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Here you can enter the custom link which will be used for the wall post. The link must start with http://"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" tabindex="21" class="form-control sap-valid-url" name="sap_facebook_custom_link" id="sap_facebook_custom_link" value="<?php echo (!empty($sap_facebook_custom_link) ? $sap_facebook_custom_link :'');?>" placeholder="<?php echo $sap_common->lang('custom_link'); ?>" />
            </div>
        </div>
    </div>

    <div class="col-sm-12 margin-bottom show-fb-image-post">
        <div class="form-group">
            <label for="sap_fb_post_img" class="col-sm-4 control-label">
                <?php echo $sap_common->lang('post_image'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Here you can upload a image which will be used for the Facebook wall post. Leave it empty to use the default image from the settings page.<br><br><strong>Note: </strong>This option only work if your facebook app version is below 2.9. If you're using latest facebook app, it wont work. <a href='https://developers.facebook.com/blog/post/2017/06/27/API-Change-Log-Modifying-Link-Previews/' target='_blank'>Learn More.</a>" data-html="true"></i>
            </label>
            <div class="col-sm-8">
                <?php if(!empty($sap_fb_custom_img)) { ?>
                    <input id="sap_fb_post_img" name="sap_facebbok_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8" data-initial-preview="<img src='<?php echo SAP_IMG_URL.$sap_fb_custom_img;?>'/>">
                <?php } else { ?>
                    <input id="sap_fb_post_img" name="sap_facebbok_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8">
                <?php } ?>
                <input type="hidden" name="sap_facebbok_post_img" class="sap-default-img" value="<?php echo !empty( $sap_fb_custom_img )? $sap_fb_custom_img :'';  ?>">
            </div>
        </div>
    </div>

    <div class="col-sm-12 margin-bottom show-fb-reel-post">
        <label for="" class="col-sm-4 control-label"> <?php echo $sap_common->lang('fb_post_video'); ?></label>
            <div class="col-sm-6 <?php echo (!empty($sap_fb_post_reel)) ? 'fb-hide-uploader' : '';?>">
            <?php
            if( !empty( $sap_fb_post_reel ) ) { 
            ?>
                <div class="fb-video-preview">									
                    <div class="cross-arrow">
                        <a href="javascript:void(0)" data-upload_img=".file-input" data-preview=".fb-video-preview" title="Remove FB Reel" class="sap-setting-remove-reel-fb remove-tx-init"><i class="fa fa-close"></i></a>
                    </div>
                    <div class="sap-quick-post-privew-video">
                        <video width="auto" height="100%" controls>
                            <source src="<?php echo SAP_IMG_URL.$sap_fb_post_reel; ?>" type="video/mp4">
                        </video>
                    </div>	 
                </div>
            <?php 
            } ?>
                <?php 
                $preview_name = !empty($sap_fb_post_reel) ? $sap_fb_post_reel : '';
                $preview_video = !empty($sap_fb_post_reel) ? SAP_SITE_URL.'/uploads/'. $preview_name : '';
                ?>
                <input id="fb_post_reel" tabindex="3" name="fb_post_reel" value="<?php echo $preview_video; ?>" type="file" class="file file-loading fb-reel-input" data-show-upload="false" data-show-caption="true" data-max-file-size="<?php echo MINGLE_MAX_FILE_UPLOAD_SIZE; ?>" data-allowed-file-extensions='["mp4","mov","mkv"]'/>
                <input type="hidden" id="uploaded_video" class="fb-reel-input-hidden" name="sap_fb_post_reel" value="<?php echo !empty($sap_fb_post_reel) ? $sap_fb_post_reel : ''; ?>" >
                <h6><b>Please check allowed video formats and standards <a target="_blank" href="https://docs.wpwebelite.com/mingle-saas/social-network-configuration/#Quickshare-video">here.</a></b></h6>
        </div>
    </div>

    <div class="col-sm-12 margin-bottom">
        <div class="form-group">
            <label for="sap_fb_post_custom_message" class="col-sm-4 control-label"><?php echo $sap_common->lang('custom_message'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Here you can enter a custom content which will be used for the Facebook post. Leave it empty to use content of the current post." data-html="true"></i>
            </label>
            <div class="col-sm-8">                
                <textarea class="form-control" name="sap_facebook[message]" id="sap_fb_post_custom_message" tabindex="5"><?php echo (!empty($sap_fb_custom_msg) ? $sap_fb_custom_msg : '');?></textarea>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="sap-schedule-time-fb" class="col-sm-4 control-label">
                <?php echo $sap_common->lang('individual_schedule'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="This setting modifies the schedule global setting and overrides scheduled time. Keep it blank to use the global schedule settings."></i>
            </label>
            <div class="col-sm-4">
                <input type="text" name="sap-schedule-time-fb" id="sap-schedule-time-fb" placeholder="YYYY-MM-DD hh:mm" <?php echo !empty($sap_schedule_time_fb) ? 'value="' . date('Y-m-d H:i', $sap_schedule_time_fb) . '"' : ''; ?> readonly="" class="form-control sap-datetime fb-schedule-input">
            </div>
        </div>
    </div>
    <input type="hidden" name="networks[facebook]" id="enable_facebook" value="1">
</div>