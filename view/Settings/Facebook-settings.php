<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

?>
<div class="tab-pane <?php echo ( $active_tab == "facebook") ? "active" : "" ?>" id="facebook">
    <form id="facebook-settings" class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data"> 
        <?php
        global $sap_common;
        // if FB app id is not empty reset session data
        if (isset($_GET['fb_reset_user']) && $_GET['fb_reset_user'] == '1' && !empty($_GET['sap_fb_userid'])) {
            $facebook->sap_fb_reset_session();
        }

        //Url shortner options
        $shortner_options = $common->sap_get_all_url_shortners();

        //Get SAP options which stored
        $sap_facebook_options = $this->get_user_setting('sap_facebook_options');

        // Getting facebook Rest all accounts
        $fb_rest_accounts = $this->sap_get_fb_rest_accounts();

        //getting facebook App Method account
        $fb_app_accounts = $this->sap_get_fb_app_accounts();

        $fb_app_version = !empty($sap_facebook_options['fb_app_version']) ? $sap_facebook_options['fb_app_version'] : '';

        $facebook_auth_options = !empty($sap_facebook_options['facebook_auth_options']) ? $sap_facebook_options['facebook_auth_options'] : 'appmethod';

        $facebook_proxy_options = !empty($sap_facebook_options['enable_proxy']) ? $sap_facebook_options['enable_proxy'] : '';
        $networks_count = sap_get_users_networks_count();

        $graph_style = "";
        $proxy_style =  "display:none";
        $app_style = "";
        if ($facebook_auth_options == 'graph') {
            $app_style = "display:none";
        } else if ($facebook_auth_options == 'appmethod') {
            $graph_style = "display:none";
        }

        if($facebook_proxy_options == 1) {
            $proxy_style = "display:block";
        }

        $facebook_count = is_numeric($networks_count['facebook'] ?? '') ? $networks_count['facebook'] : 999;

        // Getting facebook app grant data
        $sap_fb_sess_data = $this->get_user_setting('sap_fb_sess_data');
        ?>
        <div class="box box-primary box-inner-div border-b" style="display: none">
            <input type="hidden" name="facebook_count" id="facebook_count" value="<?php echo $facebook_count?>" />
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('facebook_general_title'); ?></div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('facebook_autoposting'); ?></label>
                        <div class="tg-list-item col-sm-9">
                            <input class="tgl tgl-ios" name="sap_facebook_options[enable_facebook]" id="enable_facebook" <?php echo!empty($sap_facebook_options['enable_facebook']) ? 'checked="checked"' : 'checked="checked"'; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="enable_facebook"></label>
                            <span class=""><?php echo $sap_common->lang('facebook_autoposting_help'); ?></span>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_facebook_submit" class="btn btn-primary sap-facebbok-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>
        <div class="box box-primary border-b">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('facebook_proxy_title'); ?></div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('facebook_proxy_enable'); ?></label>
                        <div class="tg-list-item col-sm-9">
                            <input class="tgl tgl-ios" name="sap_facebook_options[enable_proxy]" id="enable_proxy" <?php echo!empty($sap_facebook_options['enable_proxy']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="enable_proxy"></label>
                            <span class=""><?php echo $sap_common->lang('facebook_proxy_title_help'); ?></span>
                        </div>
                    </div>
                    <div id="facebook-proxy" style="<?php print $proxy_style; ?>">
                        <div class="form-group">
                            <label for="facebook_proxy_url" class="col-sm-3 control-label padding-top-0"><?php echo $sap_common->lang('facebook_proxy_url'); ?></label>
                             <div class="col-sm-6">
                                <input type="url" class="form-control bitly-token" name="sap_facebook_options[proxy_url]" value="<?php echo!empty($sap_facebook_options['proxy_url']) ? $sap_facebook_options['proxy_url'] : ''; ?>" >
                                <span class=""><?php echo $sap_common->lang('facebook_proxy_url_help'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="facebook_proxy_username" class="col-sm-3 control-label padding-top-0"><?php echo $sap_common->lang('facebook_proxy_username'); ?></label>
                             <div class="col-sm-6">
                                <input type="text" class="form-control bitly-token" name="sap_facebook_options[proxy_username]" value="<?php echo!empty($sap_facebook_options['proxy_username']) ? $sap_facebook_options['proxy_username'] : ''; ?>" >
                                <span class=""><?php echo $sap_common->lang('facebook_proxy_username_help'); ?></span>
                            </div>  
                        </div>
                        <div class="form-group">
                            <label for="facebook_proxy_password" class="col-sm-3 control-label padding-top-0"><?php echo $sap_common->lang('facebook_proxy_password'); ?></label>
                             <div class="col-sm-6">
                                <input type="text" class="form-control bitly-token" name="sap_facebook_options[proxy_password]" value="<?php echo!empty($sap_facebook_options['proxy_password']) ? $sap_facebook_options['proxy_password'] : ''; ?>" >
                                <span class=""><?php echo $sap_common->lang('facebook_proxy_password_help'); ?></span>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_facebook_submit" class="btn btn-primary sap-facebbok-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>

        <div class="box box-primary border-b">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('facebook_api_setting'); ?></div>
            <?php
            	if(  $facebook_count > 0) {
                    $limit_note = '';
                    if($facebook_count < 2) {

                        $limit_note = sprintf($sap_common->lang('single_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$facebook_count);
                    } else if($facebook_count > 1) {
                        $limit_note = sprintf($sap_common->lang('max_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$facebook_count);
                    }
					?>
						<div class="sap-alert-error-box linkedin-multi-post-note count-limit-msg gmb-count-msg-limit"><?php echo $limit_note ?></div> 
					<?php
				}
			
			?>
            <div class="box-body">
                <div class="sap-box-inner sap-api-facebook-settings ">
                    <div class="form-group">
                        <label for="app-setting" class="col-sm-3 control-label padding-top-0"><?php echo $sap_common->lang('facebook_authentication'); ?></label>
                        <div class="col-sm-3">
                            <input id="app_api" type="radio" name="sap_facebook_options[facebook_auth_options]" <?php echo($facebook_auth_options == 'appmethod') ? 'checked="checked"' : ''; ?> value="appmethod">
                            <label class="auth-option" for="app_api"><?php echo $sap_common->lang('facebook_app_method'); ?></label>
                        </div>
                        <div class="col-sm-3">
                            <input id="graph_api" type="radio" name="sap_facebook_options[facebook_auth_options]" <?php echo($facebook_auth_options == 'graph') ? 'checked="checked"' : ''; ?> value="graph">
                            <label class="auth-option" for="graph_api"><?php echo $sap_common->lang('facebook_graph_api'); ?></label>
                        </div>
                    </div>
                    
                    <div id="facebook-app-method" style="<?php print $app_style; ?>" class="app-method-wrap">
                        <?php

                        
                        //$fb_app_accounts = [1];
                        //$facebook_count = 1;
                        if( count($fb_app_accounts) >= $facebook_count && $facebook_count > 0){
                            $limit_alert = '';
                            if($facebook_count < 2) {

                                $limit_alert = sprintf($sap_common->lang('single_account_limit_alert'),'<span class="limit-note">','</span>',$facebook_count);
                            } else if($facebook_count > 1) {
                                $limit_alert = sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$facebook_count);
                            }
                            ?>
                                <div class="sap-alert-error-box limit_reached"><?php echo $limit_alert; ?></div>
                            <?php
                           
                         }else{
                        
                                if (!empty($fb_app_accounts)) {
                                    echo '<div class="fb-btn">';
                                }
                            
                                echo '<p><a class="sap-grant-fb-android btn btn-primary sap-api-btn " href="' . $facebook->sap_auto_poster_get_fb_app_method_login_url() . '">  ' .$sap_common->lang("facebook_add_account").'</a></p>';
                                if (!empty($fb_app_accounts)) {
                                    echo '</div>';
                                }
                         }
                                               
                        if (!empty($fb_app_accounts) && $facebook_auth_options == 'appmethod') {
                            ?>

                            <div class="form-group form-head">
                                <label class="col-md-3 "><?php echo $sap_common->lang('user_id'); ?></label>
                                <label class="col-md-3 "><?php echo $sap_common->lang('account_name'); ?></label>
                                <label class="col-md-3 "><?php echo $sap_common->lang('action'); ?></label>
                            </div>  
                            <?php
                            $i = 0;
                            foreach ($fb_app_accounts as $facebook_app_key => $facebook_app_value) {
                                if (is_array($facebook_app_value)) {
                                    $fb_user_data = $facebook_app_value;
                                    $app_reset_url = '?fb_reset_user=1&sap_fb_userid=' . $facebook_app_key;
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

                <div id="facebook-graph-api" style="<?php print $graph_style; ?>">

                    <div class="form-group">
                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('facebook_application'); ?></label>
                        <div class="col-sm-9 documentation-text">
                            <?php echo sprintf($sap_common->lang('facebook_graph_api_hlp_text'),'<span>','<a href="https://docs.wpwebelite.com/social-network-integration/facebook/" target="_blank">','</a>','</span>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="app-permission-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('allowing_permissinons'); ?></label>
                        <div class="col-sm-9">
                            <span><?php echo $sap_common->lang('allowing_permissinons_hlp_text'); ?></span>
                        </div>
                    </div>


                    <div class="form-group display_desktop">
                        <label class="col-sm-3"><?php echo $sap_common->lang('facebook_app_id_key'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('facebook_app_secret'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('validd_oath_uris'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('allowing_permissinons'); ?></label>
                    </div>

                    <?php
                    $sap_facebook_keys = empty($sap_facebook_options['facebook_keys']) ? array(0 => array('app_id' => '', 'app_secret' => '')) : $sap_facebook_options['facebook_keys'];
                    if (!empty($sap_facebook_keys)) {
                        $i = 0;
                        foreach ($sap_facebook_keys as $key => $value) {
                            ?>
                            <div class="form-group display_mobile sap-facebook-account-details" data-row-id="<?php echo $key; ?>">
                                <div class="col-sm-3">
                                    <label class="heading-label"><?php echo $sap_common->lang('facebook_app_id_key'); ?></label>
                                    <input class="form-control sap-facebook-app-id" name="sap_facebook_options[facebook_keys][<?php echo $key; ?>][app_id]" value="<?php echo $value['app_id']; ?>" placeholder="<?php echo $sap_common->lang('facebook_app_id_key_plh_text'); ?>" type="text">
                                </div>
                                <div class="col-sm-3">
                                    <label class="heading-label"><?php echo $sap_common->lang('facebook_app_secret'); ?></label>
                                    <input class="form-control sap-facebook-app-secret" name="sap_facebook_options[facebook_keys][<?php echo $key; ?>][app_secret]" value="<?php echo $value['app_secret']; ?>" placeholder="<?php echo $sap_common->lang('facebook_app_secret_plh_text'); ?>" type="text">
                                </div>

                                <?php
                                if (!empty($value['app_id'])) {
                                    $valid_auto_redirect_url = SAP_SITE_URL.'/settings/' . '?grant_fb=true&fb_app_id=' . $value['app_id'];
                                    ?>
                                    <div class="col-sm-3">
                                        <label class="heading-label"><?php echo $sap_common->lang('valid_redirect_url'); ?></label>
                                        <input class="form-control fb-oauth-url" id="fb-oauth-url-<?php print $value['app_id']; ?>" type="text" value="<?php echo $valid_auto_redirect_url; ?>" size="30" readonly/>
                                        <button type="button" data-inputID="#fb-oauth-url-" data-appid="<?php print $value['app_id']; ?>" class="btn btn-primary copy-clipboard"><?php echo $sap_common->lang('copy'); ?></button>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-3">
                                    <label class="heading-label"><?php echo $sap_common->lang('allowing_permissinons'); ?></label>
                                    <div class="sap-grant-reset-data">
                                        <?php
                                        if (!empty($value['app_id']) && !empty($value['app_secret']) && !empty($sap_fb_sess_data[$value['app_id']])) {
                                            echo '<p  class="sap-grant-msg">'.$sap_common->lang("allowing_permissinons_help_text").'</p>';
                                            ?>
                                            <a href="?fb_reset_user=1&sap_fb_userid=<?php echo $value['app_id']; ?>"><?php echo $sap_common->lang('reset_user_session'); ?></a>
                                            <?php
                                        } elseif (!empty($value['app_id']) && !empty($value['app_secret'])) {
                                            echo '<p><a href="' . $facebook->sap_get_fb_login_url($value['app_id']) . '">'.$sap_common->lang("grant_permission").'</a></p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-12 remove-icon-facebook">
                                    <div class="pull-right">
                                        <a href="javascript:void(0)" class="sap-facebook-remove remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                    }
                    ?>

                    <div class="form-group">
                        <div class="pull-right add-more">
                            <button type="button" class="btn btn-primary sap-fb-more-account"><i class="fa fa-plus"></i> <?php echo $sap_common->lang('add_more'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_facebook_submit" class="btn btn-primary sap-facebbok-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('autopost_to_fb'); ?> </div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group fb-selector">

                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('autopost_to_fb_users'); ?></label>


                        <?php
                        if (isset($_SESSION['sap_fb_user_accounts']) && !empty($_SESSION['sap_fb_user_accounts'])) {
                            $sap_fb_user = $facebook->sap_get_fb_user_data();
                        } else {
                            $sap_fb_user = '';
                        }

                        if (!isset($sap_fb_user) && empty($sap_fb_user)) {
                            $sap_fb_user['id'] = 0;
                        }
                        ?>
                        <div class="col-sm-6">
                            <?php
                            // Getting facebook all accounts
                            $fb_accounts = $facebook->sap_get_fb_accounts('all_app_users_with_name');
                            ?>

                            <div class="tg-list-item">
                                <select class="sap_select" multiple="multiple" name="sap_facebook_options[fb_type_post_user][]">
                                    <?php
                                    if (!empty($fb_accounts) && is_array($fb_accounts)) {
                                        $facebook_count =  $networks_count['facebook'];
                                        $fb_count =1;
                                        $fb_type_post_user = (!empty($sap_facebook_options['fb_type_post_user'])) ? $sap_facebook_options['fb_type_post_user'] : array();
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
                            </div>
                            <span><?php echo $sap_common->lang('autopost_to_fb_users_help'); ?></span>
                            <div class="button-Select sap-mt-1">
                                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all m-r-10" data-parent="fb-selector"> Select All</button>
                                <button type="button" class="btn btn-light deselect_all" data-parent="fb-selector">Select None</button>
                            </div>
                        </div>
                        <!-- <div class="col-sm-3">
                            <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="fb-selector"> Select All</button>
                            <button type="button" class="btn btn-light deselect_all" data-parent="fb-selector">Select None</button>
                        </div> -->
                    </div>
                    
                    <div class="form-group"> 

                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('share_posting_type'); ?></label>

                        <div class='col-sm-6'>
                            <div class="tg-list-item">
                                <?php
                                $share_posting_type = array(
                                    "link_posting" => 'Link posting',
                                    "image_posting" => "Image posting",
                                    "reel_posting" => "Reel posting",
                                );
                                ?>
                                <select class="sap_select sap_share_posting_type_fb" id="sap_share_posting_type"  name="sap_facebook_options[share_posting_type]">          
                                    <?php
                                    $selected_share_posting_type = !empty($sap_facebook_options['share_posting_type']) ? ($sap_facebook_options['share_posting_type']) : 'link_posting';
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
                    <div class="form-group show-fb-image-post">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('facebook_post_img'); ?></label>
                        <div class="col-sm-6 sap-facebook-img-wrap <?php echo (!empty($sap_facebook_options['fb_image'])) ? 'custom-hide-uploader' : '';?>">
                            <?php 
                            if( !empty( $sap_facebook_options['fb_image'] ) ) {
                                ?>
                                <div class="facebook-img-preview sap-img-preview">
                                    <img src="<?php echo SAP_IMG_URL.$sap_facebook_options['fb_image']; ?>">
                                    <div class="cross-arrow">
                                        <a href="javascript:void(0)" data-upload_img=".sap-facebook-img-wrap .file-input" data-preview=".facebook-img-preview" title="Remove Facebook Image" class="sap-setting-remove-img remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div> 
                                </div>
                                <?php 
                            } ?>
                            <input id="sap_tweet_img" name="fb_image" type="file" class="file file-loading <?php echo !empty($sap_facebook_options['fb_image']) ? 'sap-hide' : ''; ?>" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="15">
                            <input type="hidden" class="uploaded_img" name="sap_facebook_options[fb_image]" value="<?php echo !empty($sap_facebook_options['fb_image']) ? $sap_facebook_options['fb_image'] : ''; ?>" >
                        </div>
                    </div>
                    <div class="form-group show-fb-reel-post">
                        <label for="" class="col-sm-3 control-label"> <?php echo $sap_common->lang('fb_post_video'); ?></label>
                            <div class="col-sm-6 <?php echo (!empty($sap_facebook_options['sap_fb_reel'])) ? 'fb-hide-uploader' : '';?>">
                            <?php
                            if( !empty( $sap_facebook_options['sap_fb_reel'] ) ) { 
                            ?>
                                <div class="fb-video-preview">
                                    <div class="cross-arrow">
                                        <a href="javascript:void(0)" data-upload_img=".file-input" data-preview=".fb-video-preview" title="Remove FB Reel" class="sap-setting-remove-reel-fb remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div> 									
                                    <div class="sap-quick-post-privew-video">
                                        <video width="auto" height="100%" controls>
                                            <source src="<?php echo SAP_IMG_URL.$sap_facebook_options['sap_fb_reel']; ?>" type="video/mp4">
                                        </video>
                                    </div>	

                                </div>
                            <?php 
                            } ?>
                                <?php 
                                $preview_name = !empty($sap_facebook_options['sap_fb_reel']) ? $sap_facebook_options['sap_fb_reel'] : '';
                                $preview_video = !empty($sap_facebook_options['sap_fb_reel']) ? SAP_SITE_URL.'/uploads/'. $preview_name : '';
                                ?>
                                <input id="sap_fb_reel" tabindex="3" name="sap_fb_reel" value="<?php echo $preview_video; ?>" type="file" class="file file-loading fb-reel-input" data-show-upload="false" data-show-caption="true" data-max-file-size="<?php echo MINGLE_MAX_FILE_UPLOAD_SIZE; ?>" />
                                <input type="hidden" id="uploaded_video" class="fb-reel-input-hidden" name="sap_facebook_options[sap_fb_reel]" value="<?php echo !empty($sap_facebook_options['sap_fb_reel']) ? $sap_facebook_options['sap_fb_reel'] : ''; ?>" >
                                <h6><b>Please check allowed video formats and standards <a target="_blank" href="https://docs.wpwebelite.com/mingle-saas/social-network-configuration/#Quickshare-video">here.</a></b></h6>
                        </div>
                    </div>
                    <div class="form-group">   
                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('url_shortener'); ?></label>
                        <div class="col-sm-6">
                            <select class="sap_select sap-url-shortener-select"  name="sap_facebook_options[fb_type_shortner_opt]">
                                <?php
                                $selected_url_type = !empty($sap_facebook_options['fb_type_shortner_opt']) ? $sap_facebook_options['fb_type_shortner_opt'] : '';
                                foreach ($shortner_options as $key => $value) {

                                    $selected = "";
                                    if (!empty($selected_url_type) && $selected_url_type == $key) {
                                        $selected = ' selected="selected"';
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>   
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bit_access_token'); ?></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control bitly-token" name="sap_facebook_options[fb_bitly_access_token]" value="<?php echo!empty($sap_facebook_options['fb_bitly_access_token']) ? $sap_facebook_options['fb_bitly_access_token'] : ''; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('shorte_api_token'); ?></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control shorte-token" name="sap_facebook_options[fb_shortest_api_token]" value="<?php echo!empty($sap_facebook_options['fb_shortest_api_token']) ? $sap_facebook_options['fb_shortest_api_token'] : ''; ?>" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_facebook_submit" class="btn btn-primary sap-facebbok-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>


        <input type="hidden" name="limit_facebook_count" id="limit_facebook_count" value="<?php echo $facebook_count ?>" />
        <input type="hidden" name="created_facebook_count" id="created_facebook_count" value="<?php echo count($fb_app_accounts ?? []) ?: 1;?>" />

        <?php
        //Get SAP options which stored
        $sap_options = $this->get_user_setting('sap_facebook_options');
        $sap_options_count = isset($networks_count['facebook']) ? $networks_count['facebook'] : "";
        $sap_options_network = $network;
        $sap_options_network_docs_url = 'https://developers.facebook.com/docs/';
        include(SAP_APP_PATH . 'view/Settings/_channel_settings.php');
        ?>



    </form>
</div>