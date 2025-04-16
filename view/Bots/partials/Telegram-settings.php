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
<!--            <div class="sap-box-inner">-->
<!--                <div class="form-group">-->
<!--                    <label for="" class="col-sm-3 control-label">--><?php //echo $sap_common->lang('auto_answer'); ?><!--</label>-->
<!--                    <div class="tg-list-item col-sm-9">-->
<!--                        <input class="tgl tgl-ios" name="bots_options[--><?php //echo $network; ?><!--][auto_answer]" id="--><?php //echo $network; ?><!--_auto_answer" --><?php //echo @$options['auto_answer'] == '1' ? 'checked="checked"' : ''; ?><!-- type="checkbox" value="1">-->
<!--                        <label class="tgl-btn float-right-cs-init" for="--><?php //echo $network; ?><!--_auto_answer"></label>-->
<!--                        <span class="">--><?php //echo $sap_common->lang('bots_auto_answer_help');?><!--</span><strong>--><?php //echo ucfirst($network) ?><!--</strong>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>