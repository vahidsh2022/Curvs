<?php
    $networkCanHasChannelSettings = $sap_options_network === 'telegram' || $sap_options_network === 'instagram' || $sap_options_network === 'facebook';

    $channelAccountWrapperClass = 'panel panel-default';
    if($networkCanHasChannelSettings) {
        $channelAccountWrapperClass .= " sap-$sap_options_network-account-details";
    }
?>

<div class="<?php echo $channelAccountWrapperClass ?>" data-row-id="<?php echo $key; ?>">
    <div class="panel-body">
        <h3 class="text-primary" style="margin-top: 15px;">Channel Settings</h3>
        <div id="section_channel_setting_<?php echo $sap_options_network ?>">
            <?php if ($networkCanHasChannelSettings) { ?>
            <div class="col-md-12  <?php echo ( $key == 0 ) ? 'sap-' . $sap_options_network . '-main' : ''; ?>" >
                <div class=" pull-right">
                    <a href="javascript:void(0)" class="sap-<?php echo $sap_options_network ?>-remove remove-tx-init"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-6">
                    <label class="heading-label">
                        <?php echo $sap_options_network === 'telegram' ? 'Channel ID' : 'Page Name' ?>
                    </label>
                    <input class="form-control sap_<?php echo $sap_options_network ?>_channel_id" name="<?php echo $prefixInpName ?>[channel_id]"
                           value="<?php echo @$sap_options_channel['channel_id'] ?>"
                           placeholder="Enter Page Name." type="text">
                </div>
                <div class="col-sm-3">
                    <label>Limit Type</label>
                    <select name="<?php echo $prefixInpName ?>[limit_type]" class="form-control sap_<?php echo $sap_options_network ?>_limit_type">
                        <option value="daily" <?php echo @$sap_options_channel['limit_type'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="hourly" <?php echo @$sap_options_channel['limit_type'] === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>Limit Value</label>
                    <input class="form-control sap_<?php echo $sap_options_network ?>_limit_value" name="<?php echo $prefixInpName ?>[limit_value]"
                           value="<?php echo @$sap_options_channel['limit_value'] ?>"
                           placeholder="Limit value" type="text">
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-sm-6">
                    <label>Sleep Time From</label>
                    <input type="time" name="<?php echo $prefixInpName ?>[sleep_from]" value="<?php echo @$sap_options_channel['sleep_from'] ?>"
                           class="form-control sap_<?php echo $sap_options_network ?>_sleep_from">
                </div>
                <div class="col-sm-6">
                    <label>Sleep Time To</label>
                    <input type="time" name="<?php echo $prefixInpName ?>[sleep_to]" class="form-control sap_<?php echo $sap_options_network ?>_sleep_to"
                           value="<?php echo @$sap_options_channel['sleep_to'] ?>">
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-sm-6">
                    <label>Post Footer</label>
                    <textarea name="<?php echo $prefixInpName ?>[footer]" class="form-control  sap_<?php echo $sap_options_network ?>_footer" rows="4"
                              placeholder="Enter post footer."><?php echo @$sap_options_channel['footer'] ?></textarea>
                    <div class="channel_settings_footer_help">
                        <p><?php eLang('channel_setting_item_footer_help_title'); ?></p>
                        <ul>
                            <li><?php eLang('channel_setting_item_footer_help_list_item_URL') ?></li>
                            <li><?php eLang('channel_setting_item_footer_help_list_item_ENTER') ?></li>
                            <li><?php eLang('channel_setting_item_footer_help_list_item_RTL') ?></li>
                            <li><?php eLang('channel_setting_item_footer_help_list_item_LTR') ?></li>
                        </ul>

                        <h4><?php eLang('channel_setting_item_footer_for_example') ?></h4>
                        <div class="channel-setting-item-help-box">
                            <h6><?php eLang('channel_setting_item_footer_example_content') ?></h6>
                        </div>
                        <h4><?php eLang('channel_setting_item_footer_renderd') ?></h4>
                        <div class="channel-setting-item-help-box">
                            <div>
                                <?php eLang('channel_setting_item_footer_example_html') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
