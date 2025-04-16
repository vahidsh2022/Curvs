<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

?>
<div class="tab-pane <?php echo ($active_tab == "telegram") ? "active" : "" ?>" id="telegram">
    <form id="telegram-settings" class="form-horizontal" method="POST"
        action="<?php echo SAP_SITE_URL . '/settings/save/'; ?>" enctype="multipart/form-data">

        <?php

        //Get SAP options which stored
        $sap_telegram_options = $this->get_user_setting('sap_telegram_options');


        $telegram_count = is_numeric($networks_count['telegram'] ?? '') ? $networks_count['telegram'] : 999;

        ?>

        <!-- General Settings -->
        <div class="box box-primary" style="display:none;">
            <div class="box box-primary box-inner-div border-b">
                <input type="hidden" name="limit_telegram_count" id="limit_telegram_count" value="<?php echo $telegram_count ?>" />
                <input type="hidden" name="created_telegram_count" id="created_telegram_count" value="<?php echo count($sap_telegram_options['telegram_keys'] ?? [])  ?: 1;?>" />

                <div class="box-header sap-settings-box-header"><?php eLang('tg_general_settings'); ?>
                </div>
                <div class="box-body">
                    <div class="sap-box-inner">
                        <div class="form-group">
                            <label for=""
                                class="col-sm-3 control-label"><?php eLang('tg_autoposting'); ?></label>
                            <div class="tg-list-item col-sm-9">
                                <input class="tgl tgl-ios" name="sap_telegram_options[enable_telegram]"
                                    id="enable_telegram" <?php echo !empty($sap_telegram_options['enable_telegram']) ? 'checked="checked"' : 'checked="checked"'; ?> type="checkbox" value="1">
                                <label class="tgl-btn float-right-cs-init" for="enable_telegram"></label>
                                <span class=""><?php eLang('tg_autoposting_help'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="">
                        <button type="submit" name="sap_telegram_submit" class="btn btn-primary sap-facebbok-submit"><i
                                class="fa fa-inbox"></i> <?php eLang('save'); ?></button>
                    </div>
                </div>
            </div>

        </div>
        <!-- /General Settings -->

        <!-- AutoPost Settings -->
        <div class="box box-primary">
            <div class="box box-primary box-inner-div border-b">
                <div class="box-header sap-settings-box-header"><?php eLang('tg_autpos_settings'); ?>
                </div>
                <div class="box-body">
                    <div class="sap-box-inner">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php eLang('tg_disable_img'); ?></label>
                            <div class="tg-list-item col-sm-9">
                                <input class="tgl tgl-ios" name="sap_telegram_options[disable_image]" id="disable_image"
                                    <?php echo !empty($sap_telegram_options['disable_image']) ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                                <label class="tgl-btn float-right-cs-init" for="disable_image"></label>
                                <span class=""><?php eLang('tg_disable_img_help'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="">
                        <button type="submit" name="sap_telegram_submit" class="btn btn-primary sap-telegram-submit"><i
                                class="fa fa-inbox"></i> <?php eLang('save'); ?></button>
                    </div>
                </div>
            </div>

        </div>
        <!-- /AutoPost Settings -->

        <?php
        //Get SAP options which stored
        $sap_options = $this->get_user_setting('sap_telegram_options');
        $sap_options_count = $telegram_count;
        $sap_options_network = $network;
        $sap_options_network_docs_url = 'https://core.telegram.org/constructor/document';
        include(SAP_APP_PATH . 'view/Settings/_channel_settings.php');
        ?>
    </form>
</div>