

<?php if (isset($quickPost) && ! empty($quickPost)) { ?>
<div class="bg-<?php echo $quickPost->status == '1' ? 'green' : 'yellow' ?>-100 text-<?php echo $quickPost->status == '1' ? 'green' : 'yellow' ?>-600 text-xs p-1 rounded-md">
    <?php echo strlen($quickPost->message) > 50 ? substr($quickPost->message,0,50) . ' ...' : $quickPost->message ?>

    <?php
    $networkMeta = array_values(array_filter($quickPost->meta_data, function($item) {
        return $item['key'] === 'sap_networks';
    }))[0];

    $iconSrc = $this->socialIconByType($networkMeta['value']);
    ?>
    <img src="<?php echo $iconSrc ?>" alt="Social Icon">
</div>
<?php } ?>

