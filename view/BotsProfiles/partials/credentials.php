<?php
$prefixInpName = $profile ? "exists_data[" . $profile->id . "]" : "new_data[0]";
$suffixId = "_" . ($profile ? $profile->id : '0');
?>

<div class="new-field">
    <div class="form-group">
        <label for="networkType"><?php echo $sap_common->lang('bots_profiles_network_type') ?></label>
        <select class="form-control" name="<?php echo $prefixInpName ?>[type]">
            <?php foreach ($this->networkTypes() as $value => $label) { ?>
                <option value="<?php echo $value; ?>" <?php echo $value == ($profile ? $profile->type : '') ? 'selected' : ''; ?>><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="<?php echo 'username_' . $suffixId ?>"><?php echo $sap_common->lang('bots_profiles_username') ?></label>
        <input type="text" class="form-control" id="<?php echo 'username_' . $suffixId ?>" name="<?php echo $prefixInpName ?>[username]" value="<?php echo $profile ? $profile->username : '' ?>">
    </div>

    <div class="form-group">
        <label for="<?php echo 'password_' . $suffixId ?>"><?php echo $sap_common->lang('bots_profiles_password') ?></label>
        <input type="password" class="form-control" id="<?php echo 'password_' . $suffixId ?>" name="<?php echo $prefixInpName ?>[password]" value="<?php echo $profile ? $profile->password : '' ?>" >
    </div>
    <div class="form-group">
        <label for="<?php echo 'email_' . $suffixId ?>"><?php echo $sap_common->lang('bots_profiles_email') ?></label>
        <input type="text" class="form-control" id="<?php echo 'email_' . $suffixId ?>" name="<?php echo $prefixInpName ?>[email]" value="<?php echo $profile ? $profile->email : '' ?>">
    </div>
    <div class="form-group">
        <label for="<?php echo 'email_password_' . $suffixId ?>"><?php echo $sap_common->lang('bots_profiles_email_password') ?></label>
        <input type="password" class="form-control" id="<?php echo 'email_password_' . $suffixId ?>" name="<?php echo $prefixInpName ?>[email_password]" value="<?php echo $profile ? $profile->email_password : '' ?>" >
    </div>
</div>

