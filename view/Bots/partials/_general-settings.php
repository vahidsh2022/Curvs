<?php
$bot = $bot ?? $this->bot;
$options = [];
if($bot) {
    $options = json_decode($bot->options, true);
}
?>

<?php if($network !== 'telegram' && $network !== 'telegram_police') { ?>
<div class="sap-box-inner">
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('likes'); ?></label>
        <div class="tg-list-item col-sm-9">
            <input class="tgl tgl-ios" name="bots_options[<?php echo $network; ?>][likes]" id="<?php echo $network; ?>_likes" <?php echo @$options['likes'] == '1' ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
            <label class="tgl-btn float-right-cs-init" for="<?php echo $network; ?>_likes"></label>
            <span class=""><?php echo $sap_common->lang('bots_likes_help');?></span><strong><?php echo ucfirst($network) ?></strong>
        </div>
    </div>
</div>
<div class="sap-box-inner">
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('follows'); ?></label>
        <div class="tg-list-item col-sm-9">
            <input class="tgl tgl-ios" name="bots_options[<?php echo $network; ?>][follows]" id="<?php echo $network; ?>_follows" <?php echo @$options['follows'] == '1' ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
            <label class="tgl-btn float-right-cs-init" for="<?php echo $network; ?>_follows"></label>
            <span class=""><?php echo $sap_common->lang('bots_follows_help');?></span><strong><?php echo ucfirst($network) ?></strong>
        </div>
    </div>
</div>
<div class="sap-box-inner">
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('comments'); ?></label>
        <div class="tg-list-item col-sm-9">
            <input class="tgl tgl-ios" name="bots_options[<?php echo $network; ?>][comments]" id="<?php echo $network; ?>_comments" <?php echo @$options['comments'] == '1' ? 'checked="checked"' : ''; ?> type="checkbox" value="1">
            <label class="tgl-btn float-right-cs-init" for="<?php echo $network; ?>_comments"></label>
            <span class=""><?php echo $sap_common->lang('bots_comments_help');?></span><strong><?php echo ucfirst($network) ?></strong>
        </div>
    </div>
</div>
<?php } ?>
<?php if($network !== 'telegram_police') { ?>
<div class="sap-box-inner">
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bots_target'); ?></label>
        <div class="tg-list-item col-sm-9">
            <textarea class="form-control" name="bots_options[<?php echo $network; ?>][target]" cols="30" rows="5"><?php echo$bot ? $bot->target : '' ?></textarea>
        </div>
    </div>
</div>
<?php } ?>
<div class="sap-box-inner">
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bots_pages'); ?></label>
        <div class="tg-list-item col-sm-9">
            <textarea class="form-control" name="bots_options[<?php echo $network; ?>][pages]" cols="30" rows="1"><?php echo$bot ? implode(', ',json_decode($bot->pages,true)) : '' ?></textarea>
        </div>
    </div>
</div>
<?php if($network === 'telegram_police') { ?>
    <div class="sap-box-inner">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('max_message_length'); ?></label>
            <div class="tg-list-item col-sm-9">
                <textarea class="form-control" name="bots_options[<?php echo $network; ?>][max_message_length]" cols="30" rows="1"><?php echo @$options['max_message_length'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="sap-box-inner">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('mute_in_minute'); ?></label>
            <div class="tg-list-item col-sm-9">
                <textarea class="form-control" name="bots_options[<?php echo $network; ?>][mute_in_minute]" cols="30" rows="1"><?php echo @$options['mute_in_minute'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="sap-box-inner">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('warning_message'); ?></label>
            <div class="tg-list-item col-sm-9">
                <textarea class="form-control" name="bots_options[<?php echo $network; ?>][warning_message]" cols="30" rows="1"><?php echo @$options['warning_message'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="sap-box-inner">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('bad_words'); ?></label>
            <div class="tg-list-item col-sm-9">
                <textarea class="form-control" name="bots_options[<?php echo $network; ?>][bad_words]" cols="30" rows="1"><?php echo @$options['bad_words'] ? implode(', ',@$options['bad_words']) : '' ?></textarea>
            </div>
        </div>
    </div>
    <div class="sap-box-inner">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label"><?php echo $sap_common->lang('white_list_usernames'); ?></label>
            <div class="tg-list-item col-sm-9">
                <textarea class="form-control" name="bots_options[<?php echo $network; ?>][white_list_usernames]" cols="30" rows="1"><?php echo@$options['white_list_usernames'] ? implode(', ',@$options['white_list_usernames']) : '' ?></textarea>
            </div>
        </div>
    </div>
<?php } ?>

