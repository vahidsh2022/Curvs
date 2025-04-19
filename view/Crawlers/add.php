<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

$crawler = $crawler ?? $this->getData();

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

?>

<style>
    .error-message {
        color: red;
        font-weight: bold
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="d-flex flex-wrap align-items-center">
                                                       <span class="margin-r-5"><i class="fa fa-crosshairs"></i></span>

                <?php echo $sap_common->lang('crwlr_new'); ?>
            </span>
            <a href="<?php echo $router->generate('crawlers'); ?>"><button class="btn btn-primary back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 13 23" fill="none">
                        <path d="M11 20.6863L1.65685 11.3431L11 2" stroke="white" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg><?php echo $sap_common->lang('back'); ?></button></a>
        </h1>
    </section>
    <!-- Main content -->
    <?php
    ////
    ?>

    <section class="content">
        <div class="row mobile-row">
            <div class="col-xs-12">
                <?php echo $this->flash->renderFlash(); ?>
                <div class="box shadow-lg animated fadeIn">
                    <div class="box-body sap-custom-drop-down-wrap shadow">
                        <div class="row shadow-lg">
                            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 shadow-lg p-4">
                                <form action="<?php echo $this->getRoute(); ?>" method="post"
                                    enctype="multipart/form-data" id="addForm" class="form-horizontal">

                                    <!-- Platform -->
                                    <div class="form-group">
                                        <label for="platform" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('platform'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_platform_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <select
                                                style="color: black;z-index: 999; width:20%; border: 1px solid #21a6f3;padding: 7px 12px; box-shadow: 1px 1px 5px 1px rgb(0, 136, 255); border-radius: 5px; display: flex ; justify-content: center;align-items: center; overflow: visible !important ; height: 35px;"
                                                id="platform" name="crawler[platform]" onchange="updatePattern()">
                                                <option value="telegram" <?php echo $crawler['platform'] == 'telegram' ? 'selected' : ''; ?>>
                                                    <?php eLang('telegram'); ?>
                                                </option>
                                                <option value="web" <?php echo $crawler['platform'] == 'web' ? 'selected' : ''; ?>>
                                                    <?php eLang('web'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Listening Channel -->
                                    <div class="form-group">
                                        <label for="listening_channel" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('listening_channel'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_channel_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input required type="text" class="form-control animated-input"
                                                    id="listening_channel" name="crawler[listening_channel]"
                                                    value="<?php echo $crawler['listening_channel']; ?>"
                                                   placeholder="<?php eLang('crwlr_chnl_plchld'); ?>">
                                        </div>
                                    </div>

                                    <!-- Target Networks -->
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('crwlr_target_networks'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_networks_tooltip'); ?>"></i>
                                        </label>
                                        <span id="checkboxError"
                                            style="color: red; display: none;"><?php eLang('crwlr_target_networks_error'); ?></span>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="row">
                                                <?php foreach ($crawler['networks'] as $name => $channels) { ?>
                                                    <div class="col-md-3 col-sm-6 col-xs-12 network-card animated-card">
                                                        <?php echo $this->getIconByPlatform($name,3) ?>

                                                        <div class="checkbox-group">
                                                            <?php foreach ($channels as $channel => $checked) {
                                                                $checked = $checked ? 'checked' : '';
                                                                echo <<<html
                                                                    <label><input type="hidden" name="crawler[networks][$name][$channel]" value="0">
                                                                    <input class="crawler-networks" type="checkbox" name="crawler[networks][$name][$channel]" value="1" $checked> $channel</label>
                                                                    html;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Automatic -->
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('automatic'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_auto_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="toggle-switch">
                                                <input type="hidden" name="crawler[automatic]" value="0">
                                                <input type="checkbox" id="automatic" name="crawler[automatic]"
                                                    value="1" <?php echo $crawler['automatic'] ? 'checked' : ''; ?>>
                                                <label for="automatic" class="toggle-label"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Translation Language -->
                                    <div class="form-group">
                                        <label for="translation_language" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('translation_language'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_language_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <select required
                                                style="color: black;z-index: 999; width:20%; border: 1px solid #21a6f3;padding: 7px 12px; box-shadow: 1px 1px 5px 1px rgb(0, 136, 255); border-radius: 5px; display: flex ; justify-content: center;align-items: center; overflow: visible !important ; height: 35px;"
                                                id="translation_language" name="crawler[translation_language]">
                                                <option value="persian" <?php echo $crawler['translation_language'] == 'persian' ? 'selected' : ''; ?>>
                                                    <?php eLang('persian'); ?>
                                                </option>
                                                <option value="arabic" <?php echo $crawler['translation_language'] == 'arabic' ? 'selected' : ''; ?>>
                                                    <?php eLang('arabic'); ?>
                                                </option>
                                                <option value="english" <?php echo $crawler['translation_language'] == 'english' ? 'selected' : ''; ?>>
                                                    <?php eLang('english'); ?>
                                                </option>
                                                <option value="chines" <?php echo $crawler['translation_language'] == 'chines' ? 'selected' : ''; ?>>
                                                    <?php eLang('chines'); ?>
                                                </option>
                                                <option value="russian" <?php echo $crawler['translation_language'] == 'russian' ? 'selected' : ''; ?>>
                                                    <?php eLang('russian'); ?>
                                                </option>
                                                <option value="hindi" <?php echo $crawler['translation_language'] == 'hindi' ? 'selected' : ''; ?>>
                                                    <?php eLang('hindi'); ?>
                                                </option>
                                                <option value="kordish" <?php echo $crawler['translation_language'] == 'kordish' ? 'selected' : ''; ?>>
                                                    <?php eLang('kordish'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Replace Before -->
                                    <div class="form-group">
                                        <label for="replace_before" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('replace_before'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_rplc_bfr_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control animated-input" id="replace_before"
                                                pattern="([^=]+=[^,،]+([,،][^=]+=[^,،]+)*)"
                                                name="crawler[replace_before]"
                                                value="<?php echo $crawler['replace_before']; ?>"
                                                placeholder="<?php eLang('crwlr_rplc_plchld'); ?>">
                                        </div>
                                    </div>

                                    <!-- Replace After -->
                                    <div class="form-group">
                                        <label for="replace_after" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('replace_after'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_rplc_aftr_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control animated-input" id="replace_after"
                                                pattern="([^=]+=[^,،]+([,،][^=]+=[^,،]+)*)"
                                                name="crawler[replace_after]"
                                                value="<?php echo $crawler['replace_after']; ?>"
                                                placeholder="<?php eLang('crwlr_rplc_plchld'); ?>">
                                        </div>
                                    </div>

                                    <!-- Delete Before -->
                                    <div class="form-group">
                                        <label for="delete_before" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('delete_before'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_dlt_bfr_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control animated-input" id="delete_before"
                                                name="crawler[delete_before]" pattern="[^,،]+([,،][^,،]+)*"
                                                value="<?php echo $crawler['delete_before']; ?>"
                                                placeholder="<?php eLang('crwlr_dlt_plchld'); ?>">
                                        </div>
                                    </div>

                                    <!-- Delete After -->
                                    <div class="form-group">
                                        <label for="delete_after" class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('delete_after'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_dlt_afte_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control animated-input" id="delete_after"
                                                name="crawler[delete_after]"
                                                value="<?php echo $crawler['delete_after']; ?>"
                                                placeholder="<?php eLang('crwlr_dlt_plchld'); ?>">
                                        </div>
                                    </div>
                                    <!-- Create Image -->
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">
                                            <?php eLang('create_image'); ?>
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_crt_img_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="toggle-switch">
                                                <input type="hidden" name="crawler[create_image]" value="0">
                                                <input type="checkbox" id="create_image" name="crawler[create_image]"
                                                    value="1" <?php echo $crawler['create_image'] ? 'checked' : ''; ?>>
                                                <label for="create_image" class="toggle-label"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Create Image when don't have image -->
                                    <?php /*
                               <div class="form-group">
                                   <label class="col-sm-3 col-xs-12 control-label">
                                   <?php eLang('create_image_no'); ?>
                                       <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                           title="<?php eLang('crwlr_crt_img_no_tooltip'); ?>"></i>
                                   </label>
                                   <div class="col-sm-9 col-xs-12">
                                       <div class="toggle-switch">
                                       <input type="hidden" name="crawler[create_image_no]" value="0">
                                       <input type="checkbox" id="create_image_no" name="crawler[create_image_no]"
                                               value="1" <?php echo $crawler['create_image_no'] ? 'checked' : ''; ?>>
                                           <label for="create_image_no" class="toggle-label"></label>
                                       </div>
                                   </div>
                               </div> */ ?>

                                    <!-- Is Active -->
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">
                                            Is Active
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_is_actv_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="toggle-switch">
                                                <input type="hidden" name="crawler[is_active]" value="0">
                                                <input type="checkbox" id="is_active" name="crawler[is_active]"
                                                    value="1" <?php echo $crawler['is_active'] ? 'checked' : '' ?>>
                                                <label for="is_active" class="toggle-label"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Translate Text -->
                                    <div class="form-group">
                                        <label for="translate_text" class="col-sm-3 col-xs-12 control-label">
                                            Additional information (Prompt)
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_trns_txt_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <textarea class="form-control animated-input" id="translate_text" rows="3"
                                                name="crawler[translate_text]"
                                                placeholder="<?php eLang('crwlr_trns_txt_plchld'); ?>"><?php echo $crawler['translate_text']; ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Validation Text -->
                                    <div class="form-group">
                                        <label for="validation_text" class="col-sm-3 col-xs-12 control-label">
                                            Validation Text
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_vldtn_txt_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <textarea class="form-control animated-input" id="validation_text" rows="3"
                                                name="crawler[validation_text]"
                                                placeholder="<?php eLang('crwlr_vldtn_txt_plchld'); ?>"><?php echo $crawler['validation_text']; ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Hashtag Enabled -->
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 control-label">
                                            Hashtag Enabled
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_hshtg_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="toggle-switch">
                                                <input type="hidden" name="crawler[hashtag_enabled]" value="0">
                                                <input type="checkbox" id="hashtag_enabled" value="1"
                                                    name="crawler[hashtag_enabled]" <?php echo $crawler['hashtag_enabled'] ? 'checked' : ''; ?>>
                                                <label for="hashtag_enabled" class="toggle-label"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Watermark -->
                                    <div class="form-group">
                                        <label for="watermark" class="col-sm-3 col-xs-12 control-label">
                                            Watermark
                                            <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                title="<?php eLang('crwlr_wrtmrk_tooltip'); ?>"></i>
                                        </label>
                                        <div class="col-sm-9 col-xs-12">
                                            <div
                                                style="display: flex; flex-direction: column; justify-content: center; align-items: start;">
                                                <input type="hidden" name="crawler[watermark]" value="<?php echo $crawler['watermark'] ?>" id="watermarkValue">
                                                <input type="file" class="form-control animated-input" id="watermark"
                                                    name="watermark">
                                                <p style="margin-top: 10px;color: #E53935;">
                                                    <?php eLang('crwlr_wtrmrk_hint'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="WatermarkContiner"
                                        style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-direction: row-reverse;">
                                        <!-- Watermark Preview Box -->
                                        <div class="form-group">
                                            <div class="" style="width: 95%;">
                                                <div class="watermark-preview-wrapper">
                                                    <div class="watermark-preview-box mb-3">
                                                        <div class="watermark-preview-container">
                                                            <img id="watermarkPreview"
                                                                src="<?php echo (empty($crawler['watermark'])?'#': SAP_IMG_URL .$crawler['watermark']); ?>"
                                                                alt="Watermark Preview"
                                                                class="watermark-preview-image hidden">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Watermark Position -->
                                        <div class="form-group">
                                            <label for="watermark_pos" class="col-sm-3 col-xs-12 control-label">
                                                Watermark Position
                                                <i class="fa fa-question-circle text-muted" data-toggle="tooltip"
                                                    title="<?php eLang('crwlr_wtrmrk_ps_tooltip'); ?>"></i>
                                            </label>
                                            <div class="col-sm-9 col-xs-12">
                                                <div class="d-flex" style="align-items: center">
                                                    <select
                                                            style="color: black;z-index: 999; width:70%; border: 1px solid #21a6f3;padding: 7px 12px; box-shadow: 1px 1px 5px 1px rgb(0, 136, 255); border-radius: 5px; display: flex ; justify-content: center;align-items: center; overflow: visible !important ; height: 35px;"
                                                            id="watermark_pos" name="crawler[watermark_pos]">
                                                        <option value=""><?php eLang('choose'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'center' ? 'selected' : ''; ?>
                                                                value="center"><?php eLang('center'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'top-middle' ? 'selected' : ''; ?>
                                                                value="top-middle"><?php eLang('top-middle'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'bottom-middle' ? 'selected' : ''; ?>
                                                                value="bottom-middle"><?php eLang('bottom-middle'); ?>
                                                        </option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'middle-left' ? 'selected' : ''; ?>
                                                                value="middle-left"><?php eLang('middle-left'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'middle-right' ? 'selected' : ''; ?>
                                                                value="middle-right"><?php eLang('middle-right'); ?>
                                                        </option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'top-left' ? 'selected' : ''; ?>
                                                                value="top-left"><?php eLang('top-left'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'bottom-left' ? 'selected' : ''; ?>
                                                                value="bottom-left"><?php eLang('bottom-left'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'top-right' ? 'selected' : ''; ?>
                                                                value="top-right"><?php eLang('top-right'); ?></option>
                                                        <option <?php echo $crawler['watermark_pos'] == 'bottom-right' ? 'selected' : ''; ?>
                                                                value="bottom-right"><?php eLang('bottom-right'); ?>
                                                        </option>
                                                    </select>
                                                    <div class="fa fa-trash color-red" id="watermarkDelete" style="font-size: 32px; margin-left: 10px; cursor: pointer"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-9 col-sm-offset-3">
                                                <input type="submit" name="new_crawler" value="<?php eLang('save'); ?>"
                                                    class="btn btn-primary btn-lg btn-custom animated-button">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<?php include SAP_APP_PATH . 'footer.php'; ?>