<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$selected = '';
$instagram_accounts = $instagram->sap_get_fb_instagram_accounts('all_app_users_with_name');
if (!empty($post_id)) {

    $status = $this->get_post_meta($post_id, '_sap_instagram_status');
    $sap_instagram_custom_msg = $this->get_post_meta($post_id, '_sap_instagram_post_msg');
    $sap_instagram_custom_img = $this->get_post_meta($post_id, '_sap_instagram_post_image');
    $sap_instagram_post_accounts = $this->get_post_meta($post_id, '_sap_instagram_post_accounts');
    $sap_schedule_time_instagram = $this->get_post_meta($post_id, 'sap_schedule_time_instagram');
    $sap_inst_posting_type = $this->get_post_meta($post_id, '_sap_instagram_post_type');
    $sap_inst_video = $this->get_post_meta($post_id, '_sap_instagram_post_video');
}
// echo "sasadsa<pre>";print_r($this->get_post_meta($post_id, 'sap_inst_video'));echo "</pre>";exit;
$sap_instagram_post_status = array('Unpublished', 'Published', 'Scheduled');
$sap_instagram_options  = $this->settings->get_user_setting('sap_instagram_options');
$sap_instagram_sess_data = $this->settings->get_user_setting('sap_fb_sess_data_for_insta'); // Getting gmb session data
?>
<div class="row">
    <div class="col-sm-12 margin-bottom">
        <?php if(empty($sap_instagram_sess_data)) { ?>
            <div class="col-sm-12">
                <div class="alert alert-danger sap-warning">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <?php echo $sap_common->lang('instagram_quick_post_facebook_help_text'); ?>
                </div>
            </div> 
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-4 col-xs-5"><?php echo $sap_common->lang('status'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Status of Instagram post i.e. published/unpublished/scheduled."></i>
            </label>
            <div class="col-sm-8 col-xs-7">
                <?php
                if (isset($status) && array_key_exists($status, $sap_instagram_post_status)) {
                   echo '<label class="_sap_instagram_status_lbl status-text">'.$sap_instagram_post_status[$status].'</label>';
                   echo '<button class="btn btn-primary reset_post_status btn-xs" aria-data-id="'.$post_id.'" aria-type="gmb" aria-label="_sap_instagram_status" type="button"><i class="fa fa-refresh" aria-hidden="true"></i>'.$sap_common->lang('reset_status').' </button>';
               } else {
                echo '<label class="_sap_instagram_status_lbl status-Unpublished">'.$sap_common->lang('unpublished').'</label>';
            }
            ?>
        </div>
        </div>
    </div>


    <div class="col-sm-12 margin-bottom">
        <div class="form-group in-selector">
            <label for="sap_instagram_location_id" class="col-sm-4 control-label"><?php echo $sap_common->lang('post_to_instagram'); ?>
                <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Select accounts to which you want to publish a post. This setting overrides the default settings. Leave it empty to use the general default settings."></i>
            </label>
            <div class="col-sm-5">
                <select class="form-control sap_select" tabindex="6" name="sap_instagram[accounts][]" multiple="multiple" id="sap_instagram_user_id" data-placeholder="Select Locations">
                <?php
                    if (!empty($instagram_accounts) && is_array($instagram_accounts)) {
                        $fb_type_post_user = (!empty($sap_instagram_post_accounts)) ? $sap_instagram_post_accounts : array();
                        $instagram_count =  $networks_count['instagram'];
                        $insta_count =1;
                        foreach ($instagram_accounts as $aid => $aval) {
                            if( $insta_count > $instagram_count && $instagram_count >0){
                                break;
                            }
                            $insta_count++;

                            if (is_array($aval)) {
                                $fb_app_data = isset($sap_instagram_sess_data[$aid]) ? $sap_instagram_sess_data[$aid] : array();
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
                    <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="in-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                    <button type="button" class="btn btn-light deselect_all" data-parent="in-selector"><?php echo $sap_common->lang('select_none'); ?></button>
                </div>
            </div>
            <!-- <div class="col-sm-3">
                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="in-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                <button type="button" class="btn btn-light deselect_all" data-parent="in-selector"><?php echo $sap_common->lang('select_none'); ?></button>
            </div> -->
        </div>
    </div>
    <div class="col-sm-12 margin-bottom">
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
                    "image_posting" => "Image posting",
                    "reel_posting" => "Reel posting",
                );
                ?>
                <select class="sap_select sap_share_posting_type_inst_meta" id="sap_share_posting_type"  name="sap_instagram[type]">          
                    <?php
                    $selected_share_posting_type = !empty($sap_inst_posting_type) ? $sap_inst_posting_type : $sap_instagram_options['share_posting_type'];

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
<div class="col-sm-12 margin-bottom show-inst-image-post">
    <div class="form-group">
        <label for="sap_instagram_post_img" class="col-sm-4 control-label">
            <?php echo $sap_common->lang('post_image'); ?>
            <i class="fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Here you can upload an image which will be used for the Instagram posting. Leave it empty to use the general default settings." data-html="true"></i>
        </label>
        <div class="col-sm-8">
            <?php if(!empty($sap_instagram_custom_img)) { ?>
                <input id="sap_instagram_post_img" name="sap_instagram_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8" data-initial-preview="<img src='<?php echo SAP_IMG_URL.$sap_instagram_custom_img;?>'/>">
            <?php } else { ?>
                <input id="sap_instagram_post_img" name="sap_instagram_post_img" type="file" class="file file-loading" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="8">
            <?php } ?>
            <input type="hidden" name="sap_instagram_post_img" class="sap-default-img" value="<?php echo !empty( $sap_instagram_custom_img )? $sap_instagram_custom_img :'';  ?>">
        </div>
    </div>
</div>

<div class="col-sm-12 margin-bottom show-inst-reel-post">
    <label for="" class="col-sm-4 control-label"> <?php echo $sap_common->lang('insta_post_video'); ?></label>
        <div class="col-sm-8 <?php echo (!empty($sap_inst_video)) ? 'inst-hide-uploader' : '';?>">
        <?php
        if( !empty( $sap_inst_video ) ) { 
        ?>
            <div class="inst-video-preview">									
                <div class="cross-arrow">
                    <a href="javascript:void(0)" data-upload_img=".file-input" data-preview=".inst-video-preview" title="Remove Insta Reel" class="sap-setting-remove-reel-inst remove-tx-init"><i class="fa fa-close"></i></a>
                </div> 
                <div class="sap-quick-post-privew-video">
                    <video width="auto" height="100%" controls>
                        <source src="<?php echo SAP_IMG_URL.$sap_inst_video; ?>" type="video/mp4">
                    </video>
                </div>	
            </div>
        <?php 
        } ?>
            <?php 
            $preview_name = !empty($sap_inst_video) ? $sap_inst_video : '';
            $preview_video = !empty($sap_inst_video) ? SAP_SITE_URL.'/uploads/'. $preview_name : '';
            ?>
            <input id="sap_inst_video" tabindex="3" name="sap_inst_video" value="<?php echo $preview_video; ?>" type="file" class="file file-loading fb-reel-input" data-show-upload="false" data-show-caption="true" data-max-file-size="<?php echo MINGLE_MAX_FILE_UPLOAD_SIZE; ?>" data-allowed-file-extensions='["mp4","mov","mkv"]'/>
            <input type="hidden" id="uploaded_video" class="inst-reel-input-hidden" name="sap_inst_video" value="<?php echo !empty($sap_inst_video) ? $sap_inst_video : ''; ?>" >
            <h6><b>Please check allowed video formats and standards <a target="_blank" href="https://docs.wpwebelite.com/mingle-saas/social-network-configuration/#Quickshare-video">here.</a></b></h6>
    </div>
</div>

<div class="col-sm-12 margin-bottom">
    <div class="form-group">
        <label for="sap_instagram_post_custom_message" class="col-sm-4 control-label"><?php echo $sap_common->lang('custom_message'); ?>
            <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Here you can enter a custom content which will be used for the Instagram post. Leave it empty to use the general default settings." data-html="true"></i>
        </label>
        <div class="col-sm-8">                
            <textarea class="form-control" name="sap_instagram[message]" id="sap_instagram_post_custom_message" tabindex="5"><?php echo (!empty($sap_instagram_custom_msg) ? $sap_instagram_custom_msg : '');?></textarea>
        </div>
    </div>
</div>
<div class="col-sm-12">
        <div class="form-group">
            <label for="sap-schedule-time-instagram" class="col-sm-4 control-label">
                <?php echo $sap_common->lang('individual_schedule'); ?>
                <i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="This setting modifies the schedule global setting and overrides scheduled time. Keep it blank to use the global schedule settings."></i>
            </label>
            <div class="col-sm-2">
                <input type="text" name="sap-schedule-time-instagram" id="sap-schedule-time-instagram" placeholder="YYYY-MM-DD hh:mm" <?php echo !empty($sap_schedule_time_instagram) ? 'value="' . date('Y-m-d H:i', $sap_schedule_time_instagram) . '"' : ''; ?> readonly="" class="form-control sap-datetime fb-schedule-input">
            </div>
        </div>
    </div>
    <input type="hidden" name="networks[instagram]" id="enable_instagram" value="1">
</div>