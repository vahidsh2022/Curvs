<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}
$instagram_count = is_numeric($networks_count['instagram'] ?? '') ? $networks_count['instagram'] : 999;

?>
<div class="tab-pane <?php echo ( $active_tab == "instagram") ? "active" : "" ?>" id="instagram">
    <form id="instagram-settings" class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data"> 
        
        <?php 
        global $sap_common;
        // if FB app id is not empty reset session data
        if (isset($_GET['insta_reset_user']) && $_GET['insta_reset_user'] == '1' && !empty($_GET['sap_insta_userid'])) {
            $instagram->sap_fb_reset_session_for_insta();
        }

        //getting facebook App Method account
        $inta_fb_app_accounts = $this->sap_get_insta_fb_app_accounts();
        
        $sap_instagram_options  = $this->get_user_setting('sap_instagram_options');
          
        // Getting facebook app grant data
        $sap_fb_sess_data = $this->get_user_setting('sap_fb_sess_data_for_insta');

        ?>


        <div class="box box-primary border-b" style="display: none">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('instagram_general_title'); ?></div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group mb-0">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('instagram_autoposting'); ?></label>
                        <div class="tg-list-item col-sm-6">
                            <input class="tgl tgl-ios" name="sap_instagram_options[enable_instagram]" id="enable_instagram" <?php echo!empty($sap_instagram_options['enable_instagram']) ? 'checked="checked"' : 'checked="checked"'; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="enable_instagram"></label>
                            <span><?php echo $sap_common->lang('instagram_autoposting_help'); ?></span>
                        </div>
                        <div class="col-sm-12 pt-40">
                            <button type="submit" name="sap_instagram_submit" class="btn btn-primary sap-instagram-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" name="sap_instagram_submit" class="btn btn-primary sap-instagram-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div> -->
        </div>


        <div class="box box-primary">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('instagram_api_setting'); ?></div>

            <div id="facebook-app-method" class="sap-box-inner">
                <input type="hidden" name="limit_instagram_count" id="limit_instagram_count" value="<?php echo $instagram_count ?>" />
                <input type="hidden" name="created_instagram_count" id="created_instagram_count" value="<?php echo count($sap_instagram_options['instagram_keys'] ?? []) ?: 1;?>" />
            
               <?php
                 if(  $instagram_count > 0) {
                    $limit_note = '';
                    
                    if($instagram_count < 2) {

                        $limit_note = sprintf($sap_common->lang('single_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$instagram_count);
                    } else if($instagram_count > 1) {
                        $limit_note = sprintf($sap_common->lang('max_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$instagram_count);
                    }
                    ?>
                    <div class="alert alert-info linkedin-multi-post-note count-limit-msg gmb-count-msg-limit"><?php echo $limit_note ?></div> <br>
              <?php
             }
             ?>
             
             <?php
               
            //    $inta_fb_app_accounts = [1,2,3];
            //    $instagram_count = 2;
                if( count($inta_fb_app_accounts) >= $instagram_count && $instagram_count > 0 ){
                   $limit_alert = '';
                    if($instagram_count < 2) {

                        $limit_alert = sprintf($sap_common->lang('single_account_limit_alert'),'<span class="limit-note">','</span>',$instagram_count);
                    } else if($instagram_count > 1) {
                        $limit_alert = sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$instagram_count);
                    }
                    ?>
                        <div class="sap-alert-error-box limit_reached"><?php echo $limit_alert; ?></div>
                    <?php
                }else{
                    if (!empty($inta_fb_app_accounts)) {
                        echo '<div class="fb-btn">';
                    }

                    echo '<p><a class="sap-grant-fb-android btn btn-primary sap-api-btn"  href="' . $instagram->sap_auto_poster_get_fb_app_method_login_url() . '"> '.$sap_common->lang("facebook_add_account").' </a></p>';
                    if (!empty($inta_fb_app_accounts)) {
                        echo '</div>';
                    }
                }

               
                if ( !empty($inta_fb_app_accounts) ) {
                    ?>

                    <div class="form-group form-head">
                        <label class="col-md-3 "><?php echo $sap_common->lang('user_id'); ?></label>
                        <label class="col-md-3 "><?php echo $sap_common->lang('account_name'); ?></label>
                        <label class="col-md-3 "><?php echo $sap_common->lang('action'); ?></label>
                    </div>  
                    <?php
                    $i = 0;
                    foreach ($inta_fb_app_accounts as $facebook_app_key => $facebook_app_value) {
                        if (is_array($facebook_app_value)) {
                            $fb_user_data = $facebook_app_value;
                            $app_reset_url = '?insta_reset_user=1&sap_insta_userid=' . $facebook_app_key;
                            ?>
                            <div class="form-group form-deta">
                                <div class="col-md-3 "><?php print $facebook_app_key; ?></div>
                                <div class="col-md-3 "><?php print $fb_user_data['name']; ?></div>
                                <div class="col-md-3 delete-account">
                                    <a href="<?php print $app_reset_url; ?>"><?php echo $sap_common->lang('delete_account'); ?></a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }    
                
                ?>

            </div>
        </div>    
        <div class="box box-primary" style="margin-top: 30px;">
			<div class="box-header sap-settings-box-header">
                <?php echo $sap_common->lang('autopost_to_instagram'); ?>
            </div>
			<div class="box-body">
				<div class="sap-box-inner sap-api-instagram-autopost">
					<div class="form-group in-selector">
						<label for="insta-post-users" class="col-sm-3 control-label"><?php echo $sap_common->lang('autopost_to_insta_users'); ?></label>
						<div class="col-sm-6">
                            <?php $fb_accounts = $instagram->sap_get_fb_instagram_accounts('all_app_users_with_name'); ?>
							<select class="form-control sap_select" multiple="multiple" name="sap_instagram_options[posts_users][]">
							    <?php
                                    
                                    if (!empty($fb_accounts) && is_array($fb_accounts)) {
                                        $fb_type_post_user = (!empty($sap_instagram_options['posts_users'])) ? $sap_instagram_options['posts_users'] : array();
                                        $insta_count =1;
                                        foreach ($fb_accounts as $aid => $aval) {
                                            if( $insta_count > $instagram_count && $instagram_count >0){
                                                break;
                                            }
                                            $insta_count++;


                                            if (is_array($aval)) {

                                                $fb_app_data = isset($sap_fb_sess_data[$aid]) ? $sap_fb_sess_data[$aid] : array();
                                                $fb_user_data = isset($fb_app_data['sap_insta_user_cache']) ? $fb_app_data['sap_insta_user_cache'] : array();
                                                $fb_opt_label = !empty($fb_user_data['name']) ? $fb_user_data['name'] . ' - ' : '';
                                                $fb_opt_label = $fb_opt_label . $aid;
                                                ?>
                                                <optgroup label="<?php echo $fb_opt_label; ?>">

                                                    <?php foreach ($aval as $aval_key => $aval_data) { ?>
                                                        <option <?php echo in_array($aval_key, $fb_type_post_user) ? 'selected="selected"' : ''; ?> value="<?php echo $aval_key; ?>" ><?php echo $aval_data; ?></option>
                                                    <?php } ?>

                                                </optgroup>

                                            <?php } else { ?>
                                                <option <?php echo in_array($aid, $fb_type_post_user) ? 'selected="selected"' : ''; ?> value="<?php echo $aid; ?>" ><?php echo $aval; ?></option>
                                                <?php
                                            }
                                        } // End of foreach
                                    } // End of main if
                                    ?>
							</select>
                            <span><?php echo $sap_common->lang('autopost_to_insta_users_help'); ?></span>
                            <div class="button-Select sap-mt-1">
                                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all m-r-10 " data-parent="in-selector"> <?php echo $sap_common->lang('select_all'); ?></button>
                                <button type="button" class="btn btn-light deselect_all" data-parent="in-selector"><?php echo $sap_common->lang('select_none'); ?></button>
                            </div>
						</div>
                       <!--  <div class="col-sm-3 213">
                            <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="in-selector"> Select All</button>
                            <button type="button" class="btn btn-light deselect_all" data-parent="in-selector">Select None</button>
                        </div> -->
					</div>
                    
                    <div class="form-group">
                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('share_posting_type'); ?></label>

                            <div class='col-sm-6'>
                                <div class="tg-list-item">
                                    <?php
                                    $share_posting_type = array(
                                        "image_posting" => "Image posting",
                                        "reel_posting" => "Reel posting",
                                    );
                                    ?>
                                    <select class="sap_select sap_share_posting_type_inst" id="sap_share_posting_type"  name="sap_instagram_options[share_posting_type]">          
                                        <?php
                                        $selected_share_posting_type = !empty($sap_instagram_options['share_posting_type']) ? ($sap_instagram_options['share_posting_type']) : 'link_posting';
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

					<div class="form-group mb-0 show-inst-image-post">
						<label for="" class="col-sm-3 control-label"> <?php echo $sap_common->lang('inst_post_img'); ?></label>
						<div class="col-sm-6 sap-insta-img-wrap <?php echo (!empty($sap_instagram_options['insta_image'])) ? 'tw-hide-uploader' : '';?>">
							<?php if( !empty( $sap_instagram_options['insta_image'] ) ) { ?>
								<div class="insta-img-preview sap-img-preview">
									<img src="<?php echo SAP_IMG_URL.$sap_instagram_options['insta_image']; ?>">
									<div class="cross-arrow">
										<a href="javascript:void(0)" data-upload_img=".sap-insta-img-wrap .file-input" data-preview=".insta-img-preview" title="Remove Insta Image" class="sap-setting-remove-img remove-tx-init"><i class="fa fa-close"></i></a>
									</div> 
								</div>
						    <?php } ?>
							<input id="sap_insta_img" name="insta_image" type="file" class="file file-loading <?php echo !empty( $sap_instagram_options['insta_image'] )? 'sap-hide' : ''; ?>" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="15">
							<input type="hidden" class="uploaded_img" name="sap_instagram_options[insta_image]" value="<?php echo !empty( $sap_instagram_options['insta_image'] )? $sap_instagram_options['insta_image'] : ''; ?>" >
						</div>
					</div>
			    

                    <div class="form-group show-inst-reel-post">
                        <label for="" class="col-sm-3 control-label"> <?php echo $sap_common->lang('insta_post_video'); ?></label>
                            <div class="col-sm-6 <?php echo (!empty($sap_instagram_options['sap_inst_video'])) ? 'inst-hide-uploader' : '';?>">
                            <?php
                            if( !empty( $sap_instagram_options['sap_inst_video'] ) ) { 
                            ?>
                                <div class="inst-video-preview">									
                                <div class="cross-arrow">
                                        <a href="javascript:void(0)" data-upload_img=".file-input" data-preview=".inst-video-preview" title="Remove Insta Reel" class="sap-setting-remove-reel-inst remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div>
                                    <div class="sap-quick-post-privew-video">
                                        <video width="auto" height="100%" controls>
                                            <source src="<?php echo SAP_IMG_URL.$sap_instagram_options['sap_inst_video']; ?>" type="video/mp4">
                                        </video>
                                    </div>	 
                                </div>
                            <?php 
                            } ?>
                                <?php 
                                $preview_name = !empty($sap_instagram_options['sap_inst_video']) ? $sap_instagram_options['sap_inst_video'] : '';
                                $preview_video = !empty($sap_instagram_options['sap_inst_video']) ? SAP_SITE_URL.'/uploads/'. $preview_name : '';
                                ?>
                                <input id="sap_inst_video" tabindex="3" name="sap_inst_video" value="<?php echo $preview_video; ?>" type="file" class="file file-loading inst-reel-input" data-show-upload="false" data-show-caption="true" data-max-file-size="<?php echo MINGLE_MAX_FILE_UPLOAD_SIZE; ?>" />
                                <input type="hidden" id="uploaded_video" class="inst-reel-input-hidden" name="sap_instagram_options[sap_inst_video]" value="<?php echo !empty($sap_instagram_options['sap_inst_video']) ? $sap_instagram_options['sap_inst_video'] : ''; ?>" >
                                <h6><b>Please check allowed video formats and standards <a target="_blank" href="https://docs.wpwebelite.com/mingle-saas/social-network-configuration/#Quickshare-video">here.</a></b></h6>
                        </div>
                    </div>
                </div>

			<div class="box-footer">
				<div class="">
					<button type="submit" name="sap_instagram_submit" class="btn btn-primary sap-insta-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
				</div>
			</div>
	    </div>


        <?php
        //Get SAP options which stored
        $sap_options = $this->get_user_setting('sap_instagram_options');
        $sap_options_count = isset($networks_count['instagram']) ? $networks_count['instagram'] : "";
        $sap_options_network = $network;
        $sap_options_network_docs_url = 'https://developers.facebook.com/products/instagram/apis/';
        include(SAP_APP_PATH . 'view/Settings/_channel_settings.php');
        ?>
    </form>
</div>