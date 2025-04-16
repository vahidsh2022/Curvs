<?php 

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if( !$sap_common->sap_is_license_activated() ){
	$redirection_url = '/mingle-update/';
	header('Location: ' . SAP_SITE_URL . $redirection_url );
	die();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

  $plan_id = $match['params']['id'];
  $plan_data = $this->get_plan( $plan_id, true );

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header d-flex justify-content-between">
		<h1><div class="plus-icon edit-icon"></div><p><?php echo $sap_common->lang('edit_membership_level'); ?><small></small></p></h1>
		<a href="<?php echo SAP_SITE_URL . '/plans/'; ?>"><button class="btn btn-primary back-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 13 23" fill="none">
				<path d="M11 20.6863L1.65685 11.3431L11 2" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			Back
		</button></a>
	</section>

	<section class="content">
		<?php
		echo $this->flash->renderFlash(); ?>
		<form class="edit-plan-form" id="edit-plan" method="POST" enctype="multipart/form-data" action="<?php echo SAP_SITE_URL . '/plan/update/'; ?>">
			<div class="box box-primary">
				<div class="box-header with-border">
					<div class="row d-flex flex-wrap align-items-center">
						<div class="col-md-6 form-group"><h3 class="box-title"><?php echo $sap_common->lang('membership_level_details'); ?></h3></div>
						<div class="col-md-6 form-group" bis_skin_checked="1">
							<div style="display: inline-block;float: right;">
								<div class="d-flex align-items-center justify-content-end status-text" bis_skin_checked="1">
									<label class="control-label"><?php echo $sap_common->lang('status'); ?>:</label>
									<div class="" bis_skin_checked="1">
										<input type="checkbox" class="tgl tgl-ios" name="status" id="status" <?php echo ($plan_data->status == '1') ? "checked='checked'" : ''; ?> value="1">
										<label class="tgl-btn float-right-cs-init" for="status"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
	            $plan_id = $match['params']['id'];
	            $plan_data = $this->get_plan( $plan_id, true );

				
	            if ( empty($plan_data) ) {
	                header("Location:" . SAP_SITE_URL . "/plans/");
	                exit;
	            } ?>

				<div class="box-body">
					<div class="row edit-plan-inner d-flex flex-wrap">
						<div class="col-md-6 col-xs-12">
							<div class=" form-group">
								<label class=""><?php echo $sap_common->lang('membership_level'); ?><span class="astric">*</span></label>
								<input type="text" class="form-control" name="sap_name" id="sap_name" value="<?php echo ( !empty($plan_data->name) ? $plan_data->name : '' ); ?>" placeholder="<?php echo $sap_common->lang('ph_membership_level'); ?>" />
								<p class="description"><?php echo $sap_common->lang('name_of_the_membership_level'); ?></p>
							</div>
							<div class=" form-group">
								<label><?php echo $sap_common->lang('price'); ?><span class="astric">*</span></label>
								<input type="number" class="form-control" name="sap_price" id="sap_price" value="<?php echo ( $plan_data->price !="" ? $plan_data->price : '' ); ?>" min="0" step="0.5" placeholder="<?php echo $sap_common->lang('ph_price'); ?>" />
								<p class="description"><?php echo $sap_common->lang('price_of_membership_msg'); ?> </p>
							</div>					
						
							<div class="form-group">
								<label><?php echo $sap_common->lang('duration'); ?></label>
								<!-- <div class="d-flex row"> -->
									<!-- <div class="col-md-12"> -->
										<input type="number"  min="0" step="1" class="form-control" name="subscription_expiration_days" id="subscription_expiration_days" value="<?php echo  $plan_data->subscription_expiration_days; ?>"  />
										<p class="description"><?php echo $sap_common->lang('Length_of_time'); ?></p>
									<!-- </div> -->
									<!-- <div class="col-md-2">
										<input readonly="readonly"  type="text" class="form-control expiration-day-label" name="" id="" value="Days" />
									</div> -->
								<!-- </div> -->
							</div>
							

						</div>
						<div class="col-md-6 col-xs-12">
							<div class="description-textarea">
								<div class="form-group">
									<label><?php echo $sap_common->lang('description'); ?></label>
									<textarea class="form-control" rows="7" name="sap_description" id="sap_description" placeholder="<?php echo $sap_common->lang('ph_description'); ?>"><?php echo ( !empty($plan_data->description) ? $plan_data->description : '' ); ?></textarea>
									<p class="description"><?php echo $sap_common->lang('membership_level_description'); ?></p>
								</div>
							</div>
						</div>
					</div>
					
					<div class=" border-top">
						<div class="row">
							<div class="col-md-12 form-group">
								<h3><strong><?php echo $sap_common->lang('networks'); ?></strong></h3>
								<?php
									$networks = !empty($plan_data->networks) ? unserialize($plan_data->networks) : array(); 
									$networks_count = !empty($plan_data->networks_count) ? unserialize($plan_data->networks_count) : array(); 
								?>
								<div class="d-flex  plan-network-wrap">
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_fb" class="sap_network_fb_lbl network-level <?php echo ( in_array('facebook', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_fb'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_fb_field_info" style="display:<?php echo ( in_array('facebook', $networks) )? '':'none'; ?>" >
												<input id="sap_network_fb_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[facebook]" value="<?php echo isset($networks_count['facebook'])?$networks_count['facebook']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"  style="display:<?php echo ( in_array('facebook', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_fb_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
												<input id="sap_network_fb" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="facebook" <?php if( in_array('facebook', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_fb');" />
												<label class="tgl-btn float-right-cs-init" for="sap_network_fb"></label>
											</div>
										</div>
										
									</div>
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_tw" class="sap_network_tw_lbl network-level <?php echo ( in_array('twitter', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_twitter'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_tw_field_info" style="display:<?php echo ( in_array('twitter', $networks) )? '':'none'; ?>" >
												<input id="sap_network_tw_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[twitter]" value="<?php echo isset($networks_count['twitter'])?$networks_count['twitter']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"  style="display:<?php echo ( in_array('twitter', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_tw_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
												<input id="sap_network_tw" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="twitter" <?php if( in_array('twitter', $networks) ) echo 'checked'; ?>  onchange="return hide_show_ac_field('sap_network_tw');"/>
												<label class="tgl-btn float-right-cs-init" for="sap_network_tw"></label>
											</div>
										</div>
										
									</div>
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_linkedin" class="sap_network_linkedin_lbl network-level <?php echo ( in_array('linkedin', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_li'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_linkedin_field_info" style="display:<?php echo ( in_array('linkedin', $networks) )? '':'none'; ?>" >
												<input id="sap_network_linkedin_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[linkedin]" value="<?php echo isset($networks_count['linkedin'])?$networks_count['linkedin']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"  style="display:<?php echo ( in_array('linkedin', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_linkedin_field_info" data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_linkedin" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="linkedin" <?php if( in_array('linkedin', $networks) ) echo 'checked'; ?>  onchange="return hide_show_ac_field('sap_network_linkedin');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_linkedin"></label>
											</div>
										</div>
										
									</div>
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_tumblr" class="sap_network_tumblr_lbl network-level <?php echo ( in_array('facebook', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_tumblr'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_tumblr_field_info" style="display:<?php echo ( in_array('tumblr', $networks) )? '':'none'; ?>" >
												<input id="sap_network_tumblr_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[tumblr]" value="<?php echo isset($networks_count['tumblr'])?$networks_count['tumblr']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"  style="display:<?php echo ( in_array('tumblr', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_tumblr_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_tumblr" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="tumblr" <?php if( in_array('tumblr', $networks) ) echo 'checked'; ?>  onchange="return hide_show_ac_field('sap_network_tumblr');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_tumblr"></label>
											</div>
										</div>
										
									</div>
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_pin" class="sap_network_pin_lbl network-level <?php echo ( in_array('pinterest', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_pinterest'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_pin_field_info" style="display:<?php echo ( in_array('pinterest', $networks) )? '':'none'; ?>" >
												<input id="sap_network_pin_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[pinterest]" value="<?php echo isset($networks_count['pinterest'])?$networks_count['pinterest']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('pinterest', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_pin_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_pin" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="pinterest" <?php if( in_array('pinterest', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_pin');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_pin"></label>
											</div>
										</div>
										
									</div>
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_gmb" class="sap_network_gmb_lbl network-level <?php echo ( in_array('gmb', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_gmb'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_gmb_field_info" style="display:<?php echo ( in_array('gmb', $networks) )? '':'none'; ?>" >
												<input id="sap_network_gmb_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[gmb]" value="<?php echo isset($networks_count['gmb'])?$networks_count['gmb']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"  style="display:<?php echo ( in_array('gmb', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_gmb_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
												<input id="sap_network_gmb" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="gmb" <?php if( in_array('gmb', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_gmb');"/>
												<label class="tgl-btn float-right-cs-init" for="sap_network_gmb"></label>
											</div>
										</div>
										
									</div>

									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_reddit" class="sap_network_reddit_lbl network-level <?php echo ( in_array('reddit', $networks) )? 'social-lbl-cls':''; ?>">
												<?php echo $sap_common->lang('network_label_reddit'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_reddit_field_info" style="display:<?php echo ( in_array('reddit', $networks) )? '':'none'; ?>" >
												<input id="sap_network_reddit_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[reddit]" value="<?php echo isset($networks_count['reddit'])?$networks_count['reddit']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"   style="display:<?php echo ( in_array('reddit', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_reddit_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_reddit" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="reddit" <?php if( in_array('reddit', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_reddit');"/>
											    <label class="tgl-btn float-right-cs-init" for="sap_network_reddit"></label>
			                                </div>
										</div>
										
									</div>
									
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_insta" class="sap_network_insta_lbl network-level <?php echo ( in_array('instagram', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_insta'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_insta_field_info" style="display:<?php echo ( in_array('instagram', $networks) )? '':'none'; ?>" >
												<input id="sap_network_insta_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[instagram]" value="<?php echo isset($networks_count['instagram'])?$networks_count['instagram']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>"   style="display:<?php echo ( in_array('instagram', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_insta_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_insta" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="instagram" <?php if( in_array('instagram', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_insta');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_insta"></label>
											</div>
										</div>
										
									</div>
									
									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="network_label_youtube" class="network_label_youtube_lbl network-level <?php echo ( in_array('youtube', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_youtube'); ?></label>
											<div class="d-flex align-items-center justify-content-around network_label_youtube_field_info" style="display:<?php echo ( in_array('youtube', $networks) )? '':'none'; ?>" >
												<input id="network_label_youtube_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[youtube]" value="<?php echo isset($networks_count['youtube'])?$networks_count['youtube']:''?>" placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('youtube', $networks) )? '':'none'; ?>"/>
												<div  class="tooltip-wrap network_label_youtube_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="network_label_youtube" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="youtube" <?php if( in_array('youtube', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('network_label_youtube');"/>
											   <label class="tgl-btn float-right-cs-init" for="network_label_youtube"></label>
											</div>
										</div>
										
									</div>

									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_blogger" class="sap_network_blogger_lbl network-level <?php echo ( in_array('blogger', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_blogger'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_blogger_field_info" style="display:<?php echo ( in_array('blogger', $networks) )? '':'none'; ?>" >
												<input id="sap_network_blogger_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[blogger]" value="<?php echo isset($networks_count['blogger'])?$networks_count['blogger']:''?>"  placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('blogger', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_blogger_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												  </div>
											</div>
											<div>
											   <input id="sap_network_blogger" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="blogger" <?php if( in_array('blogger', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_blogger');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_blogger"></label>
											</div>
										</div>
										
									</div>

									<div class="sap-plan-network sap-mb-1">
										<div class="d-flex align-items-center justify-content-between">
											<label for="sap_network_wordpress" class="sap_network_wordpress_lbl network-level <?php echo ( in_array('wordpress', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_wordpress'); ?></label>
											<div class="d-flex align-items-center justify-content-around sap_network_wordpress_field_info" style="display:<?php echo ( in_array('wordpress', $networks) )? '':'none'; ?>" >
												<input id="sap_network_wordpress_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[wordpress]" value="<?php echo isset($networks_count['wordpress'])?$networks_count['wordpress']:''?>"  placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('wordpress', $networks) )? '':'none'; ?>" />
												<div  class="tooltip-wrap sap_network_wordpress_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
														<i class="glyphicon  glyphicon-info-sign"></i>
												</div>
											</div>
											<div>
											   <input id="sap_network_wordpress" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="wordpress" <?php if( in_array('wordpress', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_wordpress');"/>
											   <label class="tgl-btn float-right-cs-init" for="sap_network_wordpress"></label>
											</div>
										</div>
										
									</div>
                                    <div class="sap-plan-network sap-mb-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="sap_network_telegram" class="sap_network_telegram_lbl network-level <?php echo ( in_array('telegram', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_telegram'); ?></label>
                                            <div class="d-flex align-items-center justify-content-around sap_network_telegram_field_info" style="display:<?php echo ( in_array('telegram', $networks) )? '':'none'; ?>" >
                                                <input id="sap_network_telegram_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[telegram]" value="<?php echo isset($networks_count['telegram'])?$networks_count['telegram']:''?>"  placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('telegram', $networks) )? '':'none'; ?>" />
                                                <div  class="tooltip-wrap sap_network_telegram_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
                                                    <i class="glyphicon  glyphicon-info-sign"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <input id="sap_network_telegram" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="telegram" <?php if( in_array('telegram', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_telegram');"/>
                                                <label class="tgl-btn float-right-cs-init" for="sap_network_telegram"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sap-plan-network sap-mb-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="sap_network_crod" class="sap_network_crod_lbl network-level <?php echo ( in_array('crod', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_crod'); ?></label>
                                            <div class="d-flex align-items-center justify-content-around sap_network_crod_field_info" style="display:<?php echo ( in_array('crod', $networks) )? '':'none'; ?>" >
                                                <input id="sap_network_crod_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[crod]" value="<?php echo isset($networks_count['crod'])?$networks_count['crod']:''?>"  placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('crod', $networks) )? '':'none'; ?>" />
                                                <div  class="tooltip-wrap sap_network_crod_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
                                                    <i class="glyphicon  glyphicon-info-sign"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <input id="sap_network_crod" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="crod" <?php if( in_array('crod', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_crod');"/>
                                                <label class="tgl-btn float-right-cs-init" for="sap_network_crod"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sap-plan-network sap-mb-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="sap_network_crud" class="sap_network_crud_lbl network-level <?php echo ( in_array('crud', $networks) )? 'social-lbl-cls':''; ?>"><?php echo $sap_common->lang('network_label_crud'); ?></label>
                                            <div class="d-flex align-items-center justify-content-around sap_network_crud_field_info" style="display:<?php echo ( in_array('crud', $networks) )? '':'none'; ?>" >
                                                <input id="sap_network_crud_count" type="number" min="0" step="1" class="form-control network-count-num" name="sap_network_count[crud]" value="<?php echo isset($networks_count['crud'])?$networks_count['crud']:''?>"  placeholder="<?php echo $sap_common->lang('ph_network_count'); ?>" style="display:<?php echo ( in_array('crud', $networks) )? '':'none'; ?>" />
                                                <div  class="tooltip-wrap sap_network_crud_field_info " data-toggle="tooltip" data-placement="top" title="<?php echo $sap_common->lang('limited_ac_info'); ?>">
                                                    <i class="glyphicon  glyphicon-info-sign"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <input id="sap_network_crud" type="checkbox" class="tgl tgl-ios" name="sap_network[]" value="crud" <?php if( in_array('crud', $networks) ) echo 'checked'; ?> onchange="return hide_show_ac_field('sap_network_crud');"/>
                                                <label class="tgl-btn float-right-cs-init" for="sap_network_crud"></label>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="sap-mt-1 col-md-12 form-group">
							<input type="hidden" name="form-updated" value="1" />
							<input type="hidden" value="<?php echo ( !empty($plan_id) ? $plan_id : 0 ); ?>" name="id" />
							<button type="submit" name="sap_update_plan_submit" class="btn btn-primary"><?php echo $sap_common->lang('updat_membership_level'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php
include'footer.php';
?>
<script type="text/javascript" class="init">
	'use strict';
		function hide_show_ac_field(field =""){
			if ($('#'+field).prop('checked')) {
				  $("#"+field+"_count").show();
				  $("."+field+"_field_info").show();
				  $("."+field+"_lbl").addClass('social-lbl-cls');
			}else{
				 $("#"+field+"_count").hide();
				 $("."+field+"_field_info").hide();
				 $("."+field+"_lbl").removeClass("social-lbl-cls");
			}
		
		}

</script> 