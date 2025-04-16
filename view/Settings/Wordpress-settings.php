<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}
require_once( LIB_PATH . '/wordpress-xmlrpc/WordpressClient.php' );
$wordpress_count = isset($networks_count['wordpress'])?$networks_count['wordpress']:"";


?>
<div class="tab-pane <?php echo ( $active_tab == "wordpress") ? "active" : '' ?>" id="wordpress">
	<form id="wordpress-settings" class="form-horizontal" method="POST" action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data"> 

        <?php 
        global $sap_common;
        $sap_wordpress_options = $this->get_user_setting('sap_wordpress_options');
		?>

        <div class="box box-primary border-b">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('wordpress_general_title'); ?></div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group mb-0">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('wordpress_autoposting'); ?></label>
                        <div class="tg-list-item col-sm-6">
                            <input class="tgl tgl-ios" name="sap_wordpress_options[enable_wordpress]" id="enable_wordpress" <?php echo!empty($sap_wordpress_options['enable_wordpress']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                            <label class="tgl-btn float-right-cs-init" for="enable_wordpress"></label>
							<span><?php echo $sap_common->lang('wordpress_autoposting_help'); ?></span>
                        </div>
                        <div class="col-sm-12 pt-40">
                            <button type="submit" name="sap_wordpress_submit" class="btn btn-primary sap-wordpress-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
        <div class="box box-primary border-b">
			<div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('wordpress_api_setting'); ?> </div>
			
						<?php 
						 if(  $wordpress_count > 0) {
							$limit_note = '';
                             if($wordpress_count < 2) {

                                    $limit_note = sprintf($sap_common->lang('single_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$wordpress_count);
                                } else if($wordpress_count > 1) {
                                    $limit_note = sprintf($sap_common->lang('max_account_limit_note'),'<span class="limit-note"><strong>','</strong></span>',$wordpress_count);
                                }
                    
								?>
                                <div class="alert alert-info linkedin-multi-post-note count-limit-msg gmb-count-msg-limit"><?php echo $limit_note ?></div> 
                            <?php
                            }
                        ?>
			
			<?php
				// Check if xmlrpc_encode_request function exists
				$disabled ="";
				if (!function_exists('xmlrpc_encode_request') 	) {

					$disabled ="disabled";
					?>
					   <div class="wordpress-xml-rpc-error">
						<?php
					   $xml_rpc_note = sprintf($sap_common->lang('xml_rpc_notice'),'<span>','</span>');
					   echo $xml_rpc_note;
					   ?>
						</div>
					<?php
				}
			?>

			<div class="box-body">
				<div class="sap-box-inner sap-api-twitter-settings">
					<?php
					$sap_wordpress_data = !empty($sap_wordpress_options['wordpress_keys']) ?  $sap_wordpress_options['wordpress_keys']:array();
					$sap_wordpress_keys =array(0 => array('website_name' => '', 'website_url' => '', 'website_usernm' => '', 'website_pwd' => '')) ;

					if (!empty($sap_wordpress_data) ) {
						?>
					   <div id="facebook-app-method" style="" class="app-method-wrap">
						<div class="form-group form-head">
							<label class="col-md-3 "><?php echo $sap_common->lang('website_name'); ?></label>
							<label class="col-md-3 "><?php echo $sap_common->lang('website_url'); ?></label>
							<label class="col-md-3 "><?php echo $sap_common->lang('action'); ?></label>
						</div>  
						<?php
						$i = 0;
						foreach ($sap_wordpress_data as $wordpress_app_key => $wordpress_app_value) {
							if (is_array($wordpress_app_value)) {
								$fb_user_data = $facebook_app_value;
								$app_reset_url = '?fb_reset_user=1&sap_fb_userid=' . $wordpress_app_key;
								?>
								<div class="form-group form-deta">
									<div class="col-md-3 "><?php print $wordpress_app_value['website_name']; ?></div>
									<div class="col-md-3 "><?php print $wordpress_app_value['website_url']; ?></div>
									<div class="col-md-3 delete-account">
										<a href="javascript:;" onclick="return delete_wordpress_site(<?php echo $wordpress_app_key;?>);"><?php echo $sap_common->lang('delete_account'); ?></a>
									</div>

									<input type="hidden" name="sap_wordpress_options[wordpress_keys][<?php echo $wordpress_app_key?>][website_name]" value="<?php print $wordpress_app_value['website_name']; ?>" />
									<input type="hidden" name="sap_wordpress_options[wordpress_keys][<?php echo $wordpress_app_key?>][website_url]" value="<?php print $wordpress_app_value['website_url']; ?>" />
									<input type="hidden" name="sap_wordpress_options[wordpress_keys][<?php echo $wordpress_app_key?>][website_unm]" value="<?php print $wordpress_app_value['website_unm']; ?>" />
									<input type="hidden" name="sap_wordpress_options[wordpress_keys][<?php echo $wordpress_app_key?>][website_pwd]" value="<?php print $wordpress_app_value['website_pwd']; ?>" />
								</div>
								<?php
							}
						}
						echo "</div>";
					}
					//if (!empty($sap_wordpress_keys) && count($sap_wordpress_data) < $wordpress_count && $wordpress_count > 0 ) {
						if (!empty($sap_wordpress_keys) ) {
					
					
					
						$i = 0;
						foreach ($sap_wordpress_keys as $key => $value) {
							?>
							<div class="form-group sap-twitter-account-details mt-20" >
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('website_name'); ?></label>
									<input class="form-control website_name" name="sap_wordpress_options1[wordpress_keys][<?php echo $key; ?>][website_name]" value="<?php echo $value['website_name']; ?>" placeholder="<?php echo $sap_common->lang('website_name_plh_text'); ?>" type="text">
									<span class="lbl-error color-red error-web-name" style="display:none"><?php echo $sap_common->lang('website_name_plh_text'); ?></span>
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('website_url'); ?></label>
									<input class="form-control website_url" name="sap_wordpress_options1[wordpress_keys][<?php echo $key; ?>][website_url]" value="<?php echo $value['website_url']; ?>" placeholder="<?php echo $sap_common->lang('website_url_plh_text'); ?>" type="text">
									<span class="lbl-error color-red error-web-url" style="display:none"><?php echo $sap_common->lang('website_url_plh_text'); ?></span>
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('website_unm'); ?></label>
									<input class="form-control website_unm" name="sap_wordpress_options1[wordpress_keys][<?php echo $key; ?>][website_unm]" value="<?php echo $value['website_usernm']; ?>" placeholder="<?php echo $sap_common->lang('website_unm_plh_text'); ?>" type="text">
									<span class="lbl-error color-red error-web-username" style="display:none"><?php echo $sap_common->lang('website_unm_plh_text'); ?></span>
								</div>
								<div class="col-sm-3 d-flex align-items-center twitter-accunt-det">
									<label class="heading-label"><?php echo $sap_common->lang('website_pwd'); ?></label>
									<input class="form-control website_pwd" name="sap_wordpress_options1[wordpress_keys][<?php echo $key; ?>][website_pwd]" value="<?php echo $value['website_pwd']; ?>" placeholder="<?php echo $sap_common->lang('website_pwd_plh_text'); ?>" type="password">
									<span class="lbl-error color-red error-web-pwd" style="display:none"><?php echo $sap_common->lang('website_pwd_plh_text'); ?></span>
								</div>
                            </div>
							<?php
							$i++;
						}
					}  ?>
					<?php
						// $sap_wordpress_data = [1,2,3];
						// $wordpress_count = 2;
						if( count($sap_wordpress_data) >= $wordpress_count && $wordpress_count > 0){ 
							$limit_alert = '';
	                        if($wordpress_count < 2) {

	                            $limit_alert = sprintf($sap_common->lang('single_account_limit_alert'),'<span class="limit-note">','</span>',$wordpress_count);
	                        } else if($wordpress_count > 1) {
	                            $limit_alert = sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$wordpress_count);
	                        }
	                        ?>
	                            <div class="sap-alert-error-box limit_reached"><?php echo $limit_alert; ?></div>
	                        <?php
						}else{ ?>
							<div class="">
								<div class="pull-right add-more">
									<button type="button" class="btn btn-primary "  <?php echo $disabled;?> onclick="return add_wordpress_site();"><i class="fa fa-plus"></i> <?php echo $sap_common->lang('add_website'); ?></button>
								</div>
							</div>
						<?php }	?>
				</div>
			</div>
		</div>
		<div class="box box-primary">
            <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('autopost_to_wodpress'); ?> </div>
            <div class="box-body">
                <div class="sap-box-inner">
                    <div class="form-group">
					    <label for="app-setting" class="col-sm-3 control-label"><?php echo $sap_common->lang('map_wordpress_post_types'); ?></label>
					    <div class="col-sm-6">
							<div class="tg-list-item">
								<?php
									$auto_post_save_data = !empty( $sap_wordpress_options['auto_post_save_data'] ) ? $sap_wordpress_options['auto_post_save_data'] : array();
								?>
								<select class="sap_select sap_select_wordpress" multiple="multiple" name="sap_wordpress_options[auto_post_save_data][]">
                                    <?php
									if (!empty($auto_post_save_data) && is_array($sap_wordpress_data)) {
                                        foreach ($auto_post_save_data as $autopost_app_key => $autopost_app_value) {
										?>
											<option value="<?php echo $autopost_app_value;?>" <?php echo (in_array($autopost_app_value, $auto_post_save_data)) ? "selected" : ""; ?>><?php echo $autopost_app_value;?></option>
										<?php
                                            }
                                        } // End of foreach
                                    ?>
                                </select>
								<button type="button" class="map-post-types-cls" onclick="return map_post_types();" ><?php echo $sap_common->lang('map_post_types'); ?> </button>
						    </div>
                        </div>
				    </div>
					<div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('post_title'); ?></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="sap_wordpress_options[wordpress_global_title]" value="<?php echo!empty($sap_wordpress_options['wordpress_global_title']) ? $sap_wordpress_options['wordpress_global_title'] : ''; ?>" >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="" class="col-sm-3 control-label"> <?php echo $sap_common->lang('wordpress_post_img'); ?></label>
                        <div class="col-sm-6 sap-wordpress-img-wrap <?php echo ( ! empty( $sap_wordpress_options['wordpress_image'] ) ) ? 'tw-hide-uploader' : '';?>">
                            <?php if( !empty( $sap_wordpress_options['wordpress_image'] ) ) { ?>
                                <div class="wordpress-img-preview sap-img-preview">
                                    <img src="<?php echo SAP_IMG_URL.$sap_wordpress_options['wordpress_image']; ?>">
                                    <div class="cross-arrow">
                                        <a href="javascript:void(0)" data-upload_img=".sap-wordpress-img-wrap .file-input" data-preview=".wordpress-img-preview" title="<?php echo $sap_common->lang('wordpress_post_img_remove'); ?>" class="sap-setting-remove-img remove-tx-init"><i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
							<input id="sap_wordpress_img" name="wordpress_image" type="file" class="file file-loading <?php echo !empty( $sap_wordpress_options['wordpress_image'] )? 'sap-hide' : ''; ?>" data-show-upload="false" data-show-caption="true" data-allowed-file-extensions='["png", "jpg","jpeg", "gif"]' tabindex="15">
						    <input type="hidden" class="uploaded_img" name="sap_wordpress_options[wordpress_image]" value="<?php echo !empty( $sap_wordpress_options['wordpress_image'] )? $sap_wordpress_options['wordpress_image'] :''; ?>" >
                        </div>
                    </div>
			    </div>
            </div>
            <div class="box-footer">
                <div class="">
                    <button type="submit" name="sap_wordpress_submit" class="btn btn-primary sap-wordpress-submit"><i class="fa fa-inbox"></i> <?php echo $sap_common->lang('save'); ?></button>
                </div>
            </div>
        </div>
	</form>
	<!-- Start : Add Post Type Popup  -->
	<div class="mingle-popup" style="display: none;">
		<div class="wpw-mingle-header">
			<div class="wpw-mingle-header-title"><?php echo $sap_common->lang('map_post_types'); ?></div>
			<div class="wpw-mingle-popup-close"><a href="javascript:void(0);" class="wpw-mingle-close-button" onclick="return close_map_post_types();">×</a></div>
		</div>
		<div class="wpw-mingle-popup">
		  <input type="hidden" name="site_count" id="site_count" value="<?php echo count($sap_wordpress_data); ?>" /> 
			<div class="wp-map-pt-row table-header">
				<div class="wpmptr-name"><strong><?php echo $sap_common->lang('website_name'); ?></strong></div>
				<div class="wpmptr-post-types"><strong><?php echo $sap_common->lang('post_types'); ?></strong></div>
			 </div>
				<?php
			        if (!empty($sap_wordpress_data) && is_array($sap_wordpress_data)) {
						$wordpress_cnt =1;
						foreach ($sap_wordpress_data as $wordpress_app_key => $wordpress_app_value) {
							if( $wordpress_cnt > $wordpress_count && $wordpress_count >0){
								break;
							}
							$wordpress_cnt++;

								$endpoint =  $wordpress_app_value['website_url']  . '/xmlrpc.php';
								$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient( $endpoint, $wordpress_app_value['website_unm'], $wordpress_app_value['website_pwd'] );
								$postTypes = $wpClient->getPostTypes( array('public' => true) );	
								
						 ?>
					<div class="wp-map-pt-row">
						<div class="wpmptr-name">
							<span><?php echo $wordpress_app_value['website_name'];?></span><br>
							<code><?php echo $wordpress_app_value['website_url'];?></code>
							<input type="hidden" name="site_info_<?php echo $wordpress_app_key;?>" id="site_info_<?php echo $wordpress_app_key;?>" value="<?php echo $wordpress_app_value['website_name'];?>"/>
						</div>
						<div class="wpmptr-post-types">
							<select name="post-types website_post_info_<?php echo $wordpress_app_key;?>" id="website_post_info_<?php echo $wordpress_app_key;?>" onchange="return website_info_change();" >
								<option value="" >-- Please select post types --</option>
								<?php if(!empty( $postTypes) && !$postTypes['faultString']){
										foreach( $postTypes as $key_posts => $val_post){
										?>
											<option value="<?php echo $val_post['name'];?>"><?php echo $val_post['label'];?></option>
										<?php
									}
								}
								?>
						  </select>
						</div>
					</div>
					<?php }
					} ?>
			<div class="wp-map-submit">
				<button type="button" class="button wp-map-submit-btn" onclick="return save_map_post_types();" disabled="">Save Changes</button>
			</div>
		</div>
	</div>
	<div class="wpw-mingle-popup-overlay" style="display: none;"></div>
	<!-- End : Add Post Type Popup  -->
</div>
<script type="text/javascript">
    function add_wordpress_site(){
        var website_name =$(".website_name").val();
        var website_url =$(".website_url").val();
        var website_unm =$(".website_unm").val();
        var website_pwd =$(".website_pwd").val();
		var error =0;
		$(".error-web-name").hide();$(".error-web-url").hide();$(".error-web-username").hide();$(".error-web-pwd").hide();
		if( website_name == ""){
			$(".error-web-name").show();
			error =1;
		}
		if( website_url == ""){
			$(".error-web-url").show();
			error =1;
		}
		if( website_unm == ""){
			$(".error-web-username").show();
			error =1;
		}
		if( website_pwd == ""){
			$(".error-web-pwd").show();
			error =1;
		}
		if( error == 1){
			return false;
		}
		$.ajax({

            type: 'POST',
            url: '../wordpress-add-site/',
            data: {website_name: website_name,website_url:website_url,website_unm:website_unm,website_pwd:website_pwd},
            success: function (result) {
				var result = jQuery.parseJSON(result);
				if( result.status =="success"){
					window.location.reload();
				}
				else{
					alert(result.errorString);
					return false;
				}
            }
        });
	}
	function delete_wordpress_site(site_index =''){
		if ( confirm("<?php echo $sap_common->lang('delete_record_conform_msg'); ?>") ) {
			$.ajax({

				type: 'POST',
				url: '../wordpress-delete-site/',
				data: {site_id: site_index},
				success: function (result) {
					var result = $.parseJSON(result);
					if ( result.status ) {
						window.location.reload();
					}
				
				}
			});
		}
	}
	function map_post_types(){
		$(".mingle-popup").show();
		$(".wpw-mingle-popup-overlay").show();
	}
	function close_map_post_types(){
		$(".mingle-popup").hide();
		$(".wpw-mingle-popup-overlay").hide();
	}

	function save_map_post_types(){
		$(".mingle-popup").hide();
		$(".wpw-mingle-popup-overlay").hide();
		var site_count =$("#site_count").val();
		var option="";
		for( var i=0; i<site_count; i++){
			var site_name =$("#site_info_"+i).val();
			var post_name =$("#website_post_info_"+i).val();
			var post_option =site_name+"-"+post_name;
			option +="<option value='"+post_option+"'>"+post_option+"</option>";
		}
		$(".sap_select_wordpress").html(option);
		let  selOption = [];
		for( var i=0; i<site_count; i++){
			var site_name =$("#site_info_"+i).val();
			var post_name =$("#website_post_info_"+i).val();
			var post_option =site_name+"-"+post_name;
			selOption.push(post_option);
	    }
		$(".sap_select_wordpress").val(selOption).trigger('change');
	}
	function website_info_change(){
		$('.wp-map-submit-btn').removeAttr('disabled');
	}
</script>