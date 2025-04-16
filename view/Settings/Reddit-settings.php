<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}
$reddit_count = isset($networks_count['reddit'])?$networks_count['reddit']:"";

?>
<div class="tab-pane <?php echo ( $active_tab == "reddit") ? "active" : '' ?>" id="reddit">
    <form id="reddit-settings" class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data"> 
        <?php
        global $sap_common;

        if (isset($_GET['reddit_reset_user']) && $_GET['reddit_reset_user'] == '1' && !empty($_GET['sap_reddit_userid'])) {
            $reddit->sap_reddit_reset_session();
        }
        //Get SAP options which stored
        $sap_reddit_options     = $this->get_user_setting('sap_reddit_options');
        $sap_reddit_sess_data   = $this->get_user_setting('sap_reddit_sess_data');
       // $sap_reddit_custom_accounts = $reddit->sap_get_reddit_accounts();
      
        //getting reddit App Method account
        $reddit_app_accounts = $reddit->sap_get_reddit_accounts();
     
        // Url shortner options
        $shortner_options = $common->sap_get_all_url_shortners();
        
        ?>
        <div class="box box-primary border-b">
            <div class="box-header sap-settings-box-header">
                <?php echo $sap_common->lang('reddit_general_settings'); ?> </div>

            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group mb-0">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('en_autopost_reddit'); ?></label>
                        <div class="tg-list-item col-sm-6">
                            <input class="tgl tgl-ios" name="sap_reddit_options[enable_reddit]" id="enable_reddit" <?php echo!empty($sap_reddit_options['enable_reddit']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="enable_reddit"></label>
                            <span><?php echo $sap_common->lang('en_autopost_reddit_help'); ?></span>
                        </div>
                        <div class="col-md-12 pt-40">
                            <button type="submit" name="sap_reddit_submit" class="btn btn-primary sap-reddit-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" name="sap_reddit_submit" class="btn btn-primary sap-reddit-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div> -->
        </div>
        
           <div class="box box-primary border-b">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('reddit_api_settings'); ?></div>
            <div class="box-body">
                <div class="sap-box-inner sap-api-reddit-settings">
                    <div class="form-group">
                        <div class="alert alert-info linkedin-multi-post-note documentation-text tooltip-wrap">
                            <!-- <i> -->
                            <i class="glyphicon  glyphicon-info-sign"></i><?php echo sprintf($sap_common->lang('reddit_application_flair_map_help_text'),'<strong>','</strong>'); ?>
                            <!-- </i> -->
                            <!-- <br/> -->
                            
                        </div>
						
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12  ">
                             <?php
                             if(  $reddit_count > 0) {
                                $limit_note = '';
                                     if($reddit_count < 2) {

                                            $limit_note = sprintf($sap_common->lang('single_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$reddit_count);
                                        } else if($reddit_count > 1) {
                                            $limit_note = sprintf($sap_common->lang('max_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$reddit_count);
                                        }
                            
                                        ?>
                                        <div class="alert alert-info linkedin-multi-post-note count-limit-msg gmb-count-msg-limit"><?php echo $limit_note ?></div> 
                                    <?php
                                    }
                                ?>
                        </div>
                    </div>
                     <div class="row">
                    <!-- <div class="col-lg-4">
                        <div class="form-groups">
                            <label for="app-setting" class="control-label padding-top-0"><?php echo $sap_common->lang('reddit_authentication'); ?></label>
                           
                        </div>
                    </div> -->
                    <div class="col-lg-12">
                        <div id="reddit-app-method">
                            <?php
                            // $reddit_app_accounts = [1,2,3];
                            // $reddit_count =4;
                             if( count($reddit_app_accounts) >= $reddit_count && $reddit_count > 0 ){
                                $limit_alert = '';
                                if($reddit_count < 2) {

                                    $limit_alert = sprintf($sap_common->lang('single_account_limit_alert'),'<span class="limit-note">','</span>',$reddit_count);
                                } else if($reddit_count > 1) {
                                    $limit_alert = sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$reddit_count);
                                }
                                ?>
                                    <div class="sap-alert-error-box limit_reached"><?php echo $limit_alert; ?></div>
                                <?php    
                             }else{
                                if (!empty($reddit_app_accounts)) {
                                    echo '<div class="fb-btn">';
                                 }

                                echo '<p><a class="sap-grant-fb-android btn btn-primary sap-api-btn"  href="' . $reddit->sap_auto_poster_get_reddit_login_url() . '"> '.$sap_common->lang("reddit_add_account").' </a></p>';
                                if (!empty($reddit_app_accounts)) {
                                    echo '</div>';
                                }
                             }
                             
                           
                            if (!empty($reddit_app_accounts) ) {
                                ?>

                                <div class="form-group form-head">
                                    <label class="col-md-3 "><?php echo $sap_common->lang('user_id'); ?></label>
                                    <label class="col-md-3 "><?php echo $sap_common->lang('account_name'); ?></label>
                                    <label class="col-md-3 "><?php echo $sap_common->lang('flair'); ?></label>
                                    <label class="col-md-3 "><?php echo $sap_common->lang('action'); ?></label>
                                </div>  
                                <?php
                                  
                                $i = 0;
                                $active_ac_count = 0;
                                
                                $sub_reddit_flair = ( !empty($sap_reddit_options) && isset( $sap_reddit_options['sub_reddit_flair'] )) ? $sap_reddit_options['sub_reddit_flair'] : '';

                                foreach ($reddit_app_accounts as $reddit_user_id => $reddit_user_name) {
                                    
                                    // if (is_array($value) &&  $active_ac_count < $reddit_count) {
                                        $active_ac_count ++;
                                        
                                    if( !empty($reddit_user_id) ) {
                                        $reddit_user_data = $reddit_user_name;
                                        $app_reset_url = '?reddit_reset_user=1&sap_reddit_userid=' . $reddit_user_id;
                                        ?>
                                        <div class="form-group form-deta">
                                            <div class="col-md-3 "><?php echo $reddit_user_id; ?></div>
                                            <div class="col-md-3 "><?php echo $reddit_user_name; ?></div>
                                            <div class="col-md-3 flair">
                                                <div class="reddit-flair-map-wrap">
                                                    <button type="button" class="map-flair-cls" data-user_id="<?php echo $reddit_user_id; ?>" data-user_account="<?php echo $reddit_user_name; ?>"><?php echo $sap_common->lang('map_flair'); ?></button>
                                                    <!-- Start : Add Post Type Popup  -->
                                                    <div class="mingle-popup wp-map-reddit-account-flair" style="display: none;">
                                                        <div class="wpw-mingle-header">
                                                            <div class="wpw-mingle-header-title"><?php echo $sap_common->lang('map_your_flair'); ?></div>
                                                            <div class="wpw-mingle-popup-close"><a href="javascript:void(0);" class="wpw-mingle-close-button" onclick="return close_map_post_types();">×</a></div>
                                                        </div>
                                                        <div class="wpw-mingle-popup">
                                                            <div class="update_loader"><img src="<?php echo SAP_SITE_URL.'/assets/images/ajax-loader.gif'; ?>" alt="Update"></div>
                                                            <div class="flair_value" id="flair_value_<?php echo $aid; ?>">
                                                            <?php 
                                                            
                                                            if(isset($sub_reddit_flair[$reddit_user_id])){
                                                                foreach($sub_reddit_flair[$reddit_user_id] as $comm_key => $comm_val){
                                                                    ?>
                                                                    <input type="hidden" name="sap_reddit_options[sub_reddit_flair][<?php echo $reddit_user_id; ?>][<?php echo $comm_key ?>]" value="<?php echo $comm_val  ?>"> 
                                                                    <?php 
                                                                }
                                                            }
                                                            ?>
                                                            </div>
                                                            <div class="wp-map-pt-row table-header">
                                                                <div class="wpmptr-name"><strong><?php echo $sap_common->lang('reddit_communities'); ?></strong></div>
                                                                <div class="wpmptr-post-types"><strong><?php echo $sap_common->lang('select_flair'); ?></strong></div>
                                                            </div>
                                                                
                                                            <div class="wp-map-pt-row map-data-list">

                                                            </div>
                                                                    
                                                            
                                                            
                                                        </div>
                                                        <div class="wp-map-submit reddit-popup-save-btn">
                                                            <button type="submit" name="sap_reddit_submit" id="wpw_auto_poster_set_submit_reddit_flair" class="btn btn-primary sap-facebbok-submit wpw_auto_poster_set_submit_reddit_flair"><i class="fa fa-inbox"></i> Save</button>
                                                        </div>
                                                    </div>
                                                    <div class="wpw-mingle-popup-overlay" style="display: none;"></div>
                                                    <!-- End : Add Post Type Popup  -->
                                                </div>
                                            </div>
                                            <div class="col-md-3 delete-account">
                                                <a href="<?php print $app_reset_url; ?>"><?php echo $sap_common->lang('delete_account'); ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                //  }
                                }
                            }
                            ?>

                        </div>
                    </div>
                    </div>
                </div>

                <div id="reddit-graph-api" style="<?php print $graph_style; ?>">

                    <div class="form-group">
                        <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('reddit_application'); ?></label>
                        <div class="col-sm-9">
                            <?php echo sprintf($sap_common->lang('reddit_graph_api_hlp_text'),'<span>','<a href="https://docs.wpwebelite.com/social-network-integration/reddit/" target="_blank">','</a>','</span>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="app-permission-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('allowing_permissinons'); ?></label>
                        <div class="col-sm-9">
                            <span><?php echo $sap_common->lang('allowing_permissinons_hlp_text'); ?></span>
                        </div>
                    </div>


                    <div class="form-group display_desktop">
                        <label class="col-sm-3"><?php echo $sap_common->lang('reddit_app_id_key'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('reddit_app_secret'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('validd_oath_uris'); ?></label>
                        <label class="col-sm-3"><?php echo $sap_common->lang('allowing_permissinons'); ?></label>
                    </div>


                    <div class="form-group">
                        <div class="pull-right add-more">
                            <button type="button" class="btn btn-primary sap-fb-more-account"><i class="fa fa-plus"></i> <?php echo $sap_common->lang('add_more'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_reddit_submit" class="btn btn-primary sap-facebbok-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>


        <div class="box box-primary">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('autopost_to_reddit'); ?></div>
            <div class="box-body">
            <?php error_reporting( E_ALL ); ?>

                <div class="sap-box-inner sap-api-reddit-autopost">
                    <div class="form-group rd-selector">
                        <label for="tw-post-users" class="col-sm-3 control-label"><?php echo $sap_common->lang('autopost_to_reddit_users'); ?></label>
                        <div class="col-sm-6">
                            <select class="form-control sap_select kkkkk" multiple="multiple" name="sap_reddit_options[posts_users][]">
                            <?php
                            $accounts_details = !empty( $sap_reddit_options['posts_users'] )? $sap_reddit_options['posts_users'] : array();
                                
                            if (!empty($reddit_app_accounts)) {
                                $redit_cnt =1;
                                //foreach ( $reddit_app_accounts as $uid => $uname ){
                                   /*  if( $redit_cnt > $reddit_count && $reddit_count >0){
                                        break;
                                    }
                                    $redit_cnt++; */

                                    //echo '<option '.( in_array( $uid, $accounts_details )? 'selected="selected"' : '' ).' value="'.$uid.'">'.$uname.'</option>';
                                    $subreddits_accounts = $reddit->sap_auto_poster_get_reddit_accounts_with_subreddits( $user_id ); 
                                   
                                    if(!empty($subreddits_accounts) && is_array($subreddits_accounts)) {
                                        foreach($subreddits_accounts as $aval_key => $aval_data) {
                                               
                                            $main_account_details = explode('|', $aval_data['main-account']);
                                            $main_account_name = !empty( $main_account_details[1] ) ? $main_account_details[1] : '';	
                                               
                                            ?>											
                                            <optgroup label="<?php echo $main_account_name; ?>" >
                                                <option value="<?php echo $aval_data['main-account']; ?>" <?php echo ( in_array( $aval_data['main-account'], $accounts_details )? 'selected="selected"' : '' ) ?> ><?php echo $main_account_name; ?></option>
                                                <?php 
                                                if (!empty($aval_data['subreddits']) && is_array($aval_data['subreddits'])){ 
                                                    foreach($aval_data['subreddits'] as $sr_key => $sr_data) { ?>
                                                        <option value="<?php echo $sr_key; ?>" <?php echo ( in_array( $sr_key, $accounts_details )? 'selected="selected"' : '' ) ?> ><?php echo $sr_data; ?></option>
                                                <?php }
                                                } 
                                                ?>	
                                            </optgroup>
                                            <?php
                                        }	
                                    } 
                                    ?>
                                    
                                    <?php 
                                //}
                            } ?>
                            
                            </select>
                            <span><?php echo $sap_common->lang('autopost_to_reddit_users_help'); ?></span>
                             <div class="button-Select sap-mt-1">
                                <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all m-r-10" data-parent="rd-selector"> Select All</button>
                                <button type="button" class="btn btn-light deselect_all" data-parent="rd-selector">Select None</button>
                            </div>
                        </div>
                        <!-- <div class="col-sm-3">
                            <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="rd-selector"> Select All</button>
                            <button type="button" class="btn btn-light deselect_all" data-parent="rd-selector">Select None</button>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('dis_img_posting'); ?></label>
                        <div class="tg-list-item col-sm-6">
                            <input class="tgl tgl-ios" name="sap_reddit_options[disable_image_reddit]" id="disable-image-reddit" <?php echo !empty($sap_reddit_options['disable_image_reddit']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="disable-image-reddit"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"> <?php echo $sap_common->lang('reddit_post_img'); ?></label>
                            <div class="col-sm-6 sap-reddit-img-wrap <?php echo (!empty($sap_reddit_options['reddit_image'])) ? 'tw-hide-uploader' : '';?>">
                            <?php 
                            if( !empty( $sap_reddit_options['reddit_image'] ) ) {
                            ?>
                                <div class="reddit-img-preview sap-img-preview">
                                    <img src="<?php echo SAP_IMG_URL.$sap_reddit_options['reddit_image']; ?>">
                                    <div class="cross-arrow">
                                        <a href="javascript:void(0)" data-upload_img=".sap-reddit-img-wrap .file-input" data-preview=".reddit-img-preview" title="Remove Reddit Image" class="sap-setting-remove-img remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div> 
                                </div>
                        <?php 
                            } ?>
                                <input id="sap_reddit_img" name="reddit_image" type="file" class="file file-loading <?php echo !empty( $sap_reddit_options['reddit_image'] )? 'sap-hide' : ''; ?>" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="15">

                                <input type="hidden" class="uploaded_img" name="sap_reddit_options[reddit_image]" value="<?php echo !empty( $sap_reddit_options['reddit_image'] )? $sap_reddit_options['reddit_image'] :''; ?>" >
                            </div>
                        </div>
                  </div>

          
              
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('url_shortener'); ?></label>  
                      <div class="col-sm-6">
                             <select class="sap_select sap-url-shortener-select" name="sap_reddit_options[reddit_type_shortner_opt]">
                                        <?php 
                                            $selected_url_type = !empty($sap_reddit_options['reddit_type_shortner_opt']) ? $sap_reddit_options['reddit_type_shortner_opt'] : '';  
                                           foreach($shortner_options as $key => $value) { 
                                            $selected = "";
                                            if (!empty($selected_url_type) && $selected_url_type == $key) {
                                                $selected = ' selected="selected"';
                                            }
                                        ?>
                                            <option value="<?php echo $key;  ?>"<?php echo $selected; ?>><?php echo $value;  ?></option>
                                        <?php } ?>
                            </select>
                      </div>   
                  </div>
                
                    <div class="form-group">
                      <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('shorte_api_token'); ?></label>                      
                      <div class="col-sm-6">
                          <input type="text" class="form-control shorte-token" name="sap_reddit_options[reddit_shortest_api_token]" value="<?php echo!empty($sap_reddit_options['reddit_shortest_api_token']) ? $sap_reddit_options['reddit_shortest_api_token'] : ''; ?>" >     
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bit_access_token'); ?></label>                      
                      <div class="col-sm-6">
                          <input type="text" class="form-control bitly-token" name="sap_reddit_options[reddit_bitly_access_token]" value="<?php echo!empty($sap_reddit_options['reddit_bitly_access_token']) ? $sap_reddit_options['reddit_bitly_access_token'] : ''; ?>" >     
                      </div>
                  </div>

            </div>
            
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_reddit_submit" class="btn btn-primary sap-reddit-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>

        </div>
    </form>
</div>