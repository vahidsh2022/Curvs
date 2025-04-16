<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
global $router;

?>

<div class="tab-pane <?php echo ( $active_tab == $network) ? "active" : "" ?>" id="<?php echo $network ?>">
    <div class="box box-primary box-inner-div border-b">
        <div class="box-header sap-settings-box-header"><?php echo $sap_common->lang('facebook_general_title'); ?></div>
        <div class="box-body">
            <?php include( SAP_APP_PATH . 'view/Bots/partials/_general-settings.php'); ?>
            <div class="sap-box-inner">
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('retweet'); ?></label>
                    <div class="tg-list-item col-sm-9">
                        <input class="tgl tgl-ios" name="bots_options[<?php echo $network; ?>][retweet]" id="<?php echo $network; ?>_retweet" <?php echo @$options['retweet'] == '1' ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
                        <label class="tgl-btn float-right-cs-init" for="<?php echo $network; ?>_retweet"></label>
                        <span class=""><?php echo $sap_common->lang('bots_retweet_help');?></span><strong><?php echo ucfirst($network) ?></strong>
                    </div>
                </div>
            </div>
            <div id="twitter_retweet_pages_wrapper" class="sap-box-inner" style="display: none">
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('twitter_retweet_pages'); ?></label>
                    <div class="tg-list-item col-sm-9">
                        <textarea class="form-control" name="bots_options[<?php echo $network; ?>][retweet_pages]" cols="30" rows="1"><?php echo implode(', ',@$options['retweet_pages'] ?? []) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

