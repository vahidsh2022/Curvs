<div class="box box-primary border-b">
    <div class="box-header sap-settings-box-header">Channel Settings</div>
    <div class="box-body">
        <div class="sap-box-inner sap-api-<?php echo $sap_options_network ?>-settings">
            <?php
            if ($sap_options_count > 0) {
                $limit_note = '';
                if ($sap_options_count < 2) {
                    $limit_note = sprintf($sap_common->lang('single_account_limit_note'), '<span class="limit-note"><strong>', '</strong></span>', $sap_options_count);
                } else if ($sap_options_count > 1) {
                    $limit_note = sprintf($sap_common->lang('max_account_limit_note'), '<span class="limit-note"><strong>', '</strong></span>', $sap_options_count);
                }
                ?>
                <div
                        class="sap-alert-error-box linkedin-multi-post-note count-limit-msg gmb-count-msg-limit">
                    <?php echo $limit_note ?>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="app-setting" class="control-label"><?php echo ucfirst($sap_options_network)  . ' ' . $sap_common->lang('settings_channel_setting_title') ?> </label>
                        <?php if($sap_options_network === 'telegram') { ?>
                        <div class="documentation-text">
                            Important note: Add the bot (<strong style="color: #0d6aad">
                                @ExemailBot
                            </strong>) to your Telegram channel or group and then admin it, then give it access permissions to publish posts, delete posts, and edit posts.
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
            $sap_options_channels = empty($sap_options["{$sap_options_network}_keys"]) ? [
                [
                    'channel_id' => '',
                    'footer' => '',
                    'limit_type' => 'daily',
                    'limit_value' => 2,
                ]
            ] : $sap_options["{$sap_options_network}_keys"];

            ?>

            <div id="section_channel_setting_<?php echo $sap_options_network ?>_container">


            <?php
            foreach ($sap_options_channels as $key => $sap_options_channel) {
                $prefixInpName = "sap_{$sap_options_network}_options[{$sap_options_network}_keys][$key]";
                ?>
<!--            Each Channel -->
                    <?php
                include(SAP_APP_PATH . 'view/Settings/_channel_settings_item.php');

                ?>
            <?php } ?>
            </div>


<!--            <input type="hidden" name="limit_--><?php //echo $sap_options_network ?><!--_count" id="limit_--><?php //echo $sap_options_network ?><!--_count" value="">-->
<!--            <input type="hidden" name="created_--><?php //echo $sap_options_network ?><!--_count" id="created_--><?php //echo $sap_options_network ?><!--_count" value="1">-->

            <?php

            if( $sap_options_count <= count($sap_options_channels)){
                ?>
                <div class="sap-alert-error-box limit_reached"><?php echo sprintf($sap_common->lang('max_account_limit_alert'),'<span class="limit-note">','</span>',$sap_options_count); ?></div>
                <?php
            }else{
                ?>
                <div class="pull-right add-more">
                    <button type="button" class="btn btn-primary sap-add-more-<?php echo $sap_options_network ?>-account"
                            data-network-section-id="section_channel_setting_<?php echo $sap_options_network ?>"
                            data-network-count="<?php echo count($sap_options_channels) - 1 ?>"

                    >
                        <i class="fa fa-plus"></i> Add more
                    </button>
                </div>
                <?php
            }
            ?>


        </div>
    </div>

    <div class="box-footer">
        <button type="submit" name="sap_<?php echo $sap_options_network ?>_submit" class="btn btn-primary sap-<?php echo $sap_options_network ?>-submit">
            <i class="fa fa-inbox"></i> Save
        </button>
    </div>
</div>
