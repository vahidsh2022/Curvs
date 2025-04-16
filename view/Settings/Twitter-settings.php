<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}
$networks_count = sap_get_users_networks_count();
$twitter_count = is_numeric($networks_count['twitter'] ?? '') ? $networks_count['twitter'] : 999;

?>
<!-- End Tab 2 /.tab-pane -->
<div class="tab-pane <?php echo ( $active_tab == "twitter") ? "active" : '' ?>" id="twitter">
	<form id="twiiter-settings" class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data"> 
		<?php
		global $sap_common;
		//Get SAP options which stored
		$sap_twitter_options 		  = $this->get_user_setting('sap_twitter_options');
		$sap_twitter_accounts_details = $this->get_user_setting('sap_twitter_accounts_details');
		//Url shortner options
		$shortner_options = $common->sap_get_all_url_shortners();
		?>
		<div class="box box-primary border-b" style="display: none">
			<div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('twi_general_settings'); ?> </div>
			<div class="box-body">
				<div class="sap-box-inner">
					<div class="form-group mb-0">
						<label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('en_autopost_twi'); ?></label>
						<div class="tg-list-item col-sm-6">
							<input class="tgl tgl-ios" name="sap_twitter_options[enable_twitter]" id="enable_twitter" <?php echo!empty($sap_twitter_options['enable_twitter']) ? 'checked="checked"' : 'checked="checked"'; ?> type="checkbox" value="1">
							<label class="tgl-btn float-right-cs-init" for="enable_twitter"></label>
							<span><?php echo $sap_common->lang('en_autopost_twi_help'); ?></span>
						</div>
						<div class="col-sm-12 pt-40">	
							<button type="submit" name="sap_twitter_submit" class="btn btn-primary sap-twitter-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="box-footer">
				<div class="pull-right">
					<button type="submit" name="sap_twitter_submit" class="btn btn-primary sap-twitter-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
				</div>
			</div> -->
		</div>

		<div class="box box-primary border-b">
			<div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('twi_api_settings'); ?> </div>
			<div class="box-body">
				<div class="sap-box-inner sap-api-twitter-settings">
					<div class="form-group">
						<div class="col-sm-12  ">
						<?php 
						 if(  $twitter_count > 0) {
							$limit_note = '';
                             if($twitter_count < 2) {

                                    $limit_note = sprintf($sap_common->lang('single_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$twitter_count);
                                } else if($twitter_count > 1) {
                                    $limit_note = sprintf($sap_common->lang('max_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$twitter_count);
                                }
								?>
                                <div class="sap-alert-error-box linkedin-multi-post-note count-limit-msg gmb-count-msg-limit"><?php echo $limit_note ?></div> 
                            <?php
                            }
                        ?>
                       </div>
                     </div>
                     <div class="form-group">
						<label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('twi_application'); ?></label>
						<div class="col-sm-12  documentation-text ">
							<?php echo sprintf($sap_common->lang('twi_application_help_text'),'<span>','<a href="'.$router->generate('twitter_page').'" target="_blank">','</a>','</span>'); ?>
						</div>
                    </div>
					

					<?php
					$sap_twitter_keys = empty($sap_twitter_options['twitter_keys']) ? array(0 => array('consumer_key' => '', 'consumer_secret' => '', 'oauth_token' => '', 'oauth_secret' => '')) : $sap_twitter_options['twitter_keys'];

					if (!empty($sap_twitter_keys)) {
						$i = 0;
						foreach ($sap_twitter_keys as $key => $value) {
							?>
							<div class="form-group sap-twitter-account-details " data-row-id="<?php echo $key; ?>">
								<div class="col-md-12  <?php echo ( $i == 0 ) ? 'sap-twitter-main' : ''; ?>">
									<div class=" pull-right">
										<a href="javascript:void(0)" class="sap-twitter-remove remove-tx-init"><i class="fa fa-close"></i></a>
									</div>    
								</div>   
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('api_key'); ?></label>
									<input class="form-control sap-twitter-consumer-key" name="sap_twitter_options[twitter_keys][<?php echo $key; ?>][consumer_key]" value="<?php echo $value['consumer_key']; ?>" placeholder="<?php echo $sap_common->lang('api_key_plh_text'); ?>Enter Twitter API Key." type="text">
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('api_secret'); ?></label>
									<input class="form-control sap-twitter-consumer-secret" name="sap_twitter_options[twitter_keys][<?php echo $key; ?>][consumer_secret]" value="<?php echo $value['consumer_secret']; ?>" placeholder="<?php echo $sap_common->lang('api_secret_plh_text'); ?>" type="text">
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('access_token'); ?></label>
									<input class="form-control sap-twitter-oauth-token" name="sap_twitter_options[twitter_keys][<?php echo $key; ?>][oauth_token]" value="<?php echo $value['oauth_token']; ?>" placeholder="<?php echo $sap_common->lang('access_token_plh_text'); ?>" type="text">
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('access_token_secret'); ?></label>
									<input class="form-control sap-twitter-oauth-secret" name="sap_twitter_options[twitter_keys][<?php echo $key; ?>][oauth_secret]" value="<?php echo $value['oauth_secret']; ?>" placeholder="<?php echo $sap_common->lang('access_token_secret_plh_text'); ?>" type="text">
								</div>
                                <?php
                                //Get SAP options which stored
                                $sap_options = $this->get_user_setting('sap_twitter_options');
                                $sap_options_count = isset($networks_count['twitter']) ? $networks_count['twitter'] : "";
                                $sap_options_network = $network;
                                $sap_options_network_docs_url = 'https://apitracker.io/a/twitter';
                                $sap_options_channel = $value;
                                $prefixInpName = "sap_{$sap_options_network}_options[{$sap_options_network}_keys][$key]";
                                include(SAP_APP_PATH . 'view/Settings/_channel_settings_item.php');
                                ?>
							</div>
							<?php
							$i++;
						}
					}  ?>
					<input type="hidden" name="limit_twitter_count" id="limit_twitter_count" value="<?php echo $twitter_count;?>" />
					<input type="hidden" name="created_twitter_count" id="created_twitter_count" value="<?php echo count($sap_twitter_keys ?? [])  ?: 1;?>" />

					<?php
					
						if( count($sap_twitter_keys) >= $twitter_count && $twitter_count > 0 && !empty($sap_twitter_options['twitter_keys'])){
							$limit_alert = '';
                            if($twitter_count < 2) {

                                $limit_alert = sprintf($sap_common->lang('single_account_limit_alert'),'<span class="limit-note">','</span>',$twitter_count);
                            } else if($twitter_count > 1) {
                                $limit_alert = sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$twitter_count);
                            }
                            ?>
                                <div class="sap-alert-error-box limit_reached"><?php echo $limit_alert; ?></div>
                            <?php
						}else{
							?>
								<div class="">
									<div class="pull-right add-more">
										<button type="button" class="btn btn-primary sap-add-more-twitter-account" style="display:<?php echo $twitter_display ;?>"><i class="fa fa-plus"></i> <?php echo $sap_common->lang('add_more'); ?></button>
									</div>
								</div>
							<?php
						}
					?>
				</div>
			</div>
			<div class="box-footer">
				<div class="">
					<button type="submit" name="sap_twitter_submit" class="btn btn-primary sap-twitter-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
				</div>
			</div>

		</div>

		<div class="box box-primary ">
			<div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('autopost_to_twi'); ?></div>
			<div class="box-body">

				<div class="sap-box-inner sap-api-twitter-autopost">
					<div class="form-group tw-selector">
						<label for="tw-post-users" class="col-sm-3 control-label"><?php echo $sap_common->lang('autopost_to_twi_users'); ?></label>
						<div class="col-sm-6">
							<select class="form-control sap_select" multiple="multiple" name="sap_twitter_options[posts_users][]">
							<?php
							$accounts_details = !empty( $sap_twitter_options['posts_users'] )? $sap_twitter_options['posts_users'] : array();

							if (!empty($sap_twitter_accounts_details)) {
								$twit_count =1;
								foreach ( $sap_twitter_accounts_details as $key => $value ){
									if( $twit_count > $twitter_count && $twitter_count >0){
										break;
									}
									$twit_count++;

									echo '<option '.( in_array( $key, $accounts_details )? 'selected="selected"' : '' ).' value="'.$key.'">'.$value['name'].'</option>';
								}
							} ?>
							</select>
							<span><?php echo $sap_common->lang('autopost_to_twi_users_help'); ?></span>
							<div class="button-Select sap-mt-1">
	                            <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all  m-r-10" data-parent="tw-selector"> Select All</button>
	                            <button type="button" class="btn btn-light deselect_all" data-parent="tw-selector">Select None</button>
	                        </div>
						</div>
						<!-- <div class="col-sm-3">
                            <button type="button" name="sap_facebook_submit" class="btn btn-primary select_all" data-parent="tw-selector"> Select All</button>
                            <button type="button" class="btn btn-light deselect_all" data-parent="tw-selector">Select None</button>
                        </div> -->
					</div>
					<div class="form-group">
						<label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('dis_img_posting'); ?></label>
						<div class="col-sm-6 inline-switches">
							<input class="tgl tgl-ios" name="sap_twitter_options[disable_image_tweet]" id="disable-image-tweet" <?php echo !empty($sap_twitter_options['disable_image_tweet']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
							<label class="tgl-btn float-right-cs-init" for="disable-image-tweet"></label>
							<span><?php echo $sap_common->lang('dis_img_posting_help'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-3 control-label" style="display: none !important;"> <?php echo $sap_common->lang('twi_post_img'); ?></label>
							<div class="col-sm-6 sap-tweet-img-wrap <?php echo (!empty($sap_twitter_options['tweet_image'])) ? 'tw-hide-uploader' : '';?>"  style="display: none !important;">
							<?php
							if( !empty( $sap_twitter_options['tweet_image'] ) ) {
							?>
								<div class="tweet-img-preview sap-img-preview">
									<img src="<?php echo SAP_IMG_URL.$sap_twitter_options['tweet_image']; ?>">
									<div class="cross-arrow">
										<a href="javascript:void(0)" data-upload_img=".sap-tweet-img-wrap .file-input" data-preview=".tweet-img-preview" title="Remove Tweet Image" class="sap-setting-remove-img remove-tx-init"><i class="fa fa-close"></i></a>
									</div>
								</div>
						<?php
							} ?>
								<input id="sap_tweet_img" name="tweet_image" type="file" class="file file-loading <?php echo !empty( $sap_twitter_options['tweet_image'] )? 'sap-hide' : ''; ?>" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="15">
								<input type="hidden" class="uploaded_img" name="sap_twitter_options[tweet_image]" value="<?php echo !empty( $sap_twitter_options['tweet_image'] )? $sap_twitter_options['tweet_image'] : ''; ?>" >
							</div>
						</div>
				  </div>
				  <div class="form-group" style="display: none">
					  <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('url_shortener'); ?></label>
					  <div class="col-sm-6">
							 <select class="sap_select sap-url-shortener-select" name="sap_twitter_options[tw_type_shortner_opt]">
										<?php
											$selected_url_type = !empty($sap_twitter_options['tw_type_shortner_opt']) ? $sap_twitter_options['tw_type_shortner_opt'] : '';
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
					  <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bit_access_token'); ?></label>                      
					  <div class="col-sm-6">
						  <input type="text" class="form-control bitly-token" name="sap_twitter_options[tw_bitly_access_token]" value="<?php echo!empty($sap_twitter_options['tw_bitly_access_token']) ? $sap_twitter_options['tw_bitly_access_token'] : ''; ?>" >     
					  </div>
					</div>
					<div class="form-group">
					  <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('shorte_api_token'); ?></label>                      
					  <div class="col-sm-6">
						  <input type="text" class="form-control shorte-token" name="sap_twitter_options[tw_shortest_api_token]" value="<?php echo!empty($sap_twitter_options['tw_shortest_api_token']) ? $sap_twitter_options['tw_shortest_api_token'] : ''; ?>" >     
					  </div>
					</div>
			</div>
			<div class="box-footer">
				<div class="">
					<button type="submit" name="sap_twitter_submit" class="btn btn-primary sap-twitter-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
				</div>
			</div>
		</div>

	</form>
</div>