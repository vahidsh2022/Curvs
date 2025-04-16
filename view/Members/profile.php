<?php

/* Check the absolute path to the Social Auto Poster directory. */
if (!defined('SAP_APP_PATH')) {
	// If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
	// Or exit the script if required
	exit();
}

global $sap_common, $router;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if (!$sap_common->sap_is_license_activated()) {
	$redirection_url = '/mingle-update/';
	header('Location: ' . SAP_SITE_URL . $redirection_url);
	die();
}
include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';


$member_id = $match['params']['id'];

$membership_data = $this->user->get_user_subscription_details($member_id);
$payment_data = $this->payments->user_payments_history($member_id);


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header d-flex justify-content-between">
		<h1>
			<div class="plus-icon edit-icon"></div>
			<p><?php elang('customer_profile'); ?><small></small></p>
		</h1>
		<a href="<?php echo $router->generate('member_list'); ?>"><button class="btn btn-primary back-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 13 23" fill="none">
					<path d="M11 20.6863L1.65685 11.3431L11 2" stroke="white" stroke-width="3" stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
				Back
			</button></a>
	</section>

	<section class="content" style=" padding-top: 0;">
		<?php
		echo $this->flash->renderFlash(); ?>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php elang('customer_details'); ?></h3>
			</div>

			<?php

			$member_data = $this->get_member($member_id, true);
			if (empty($member_data)) {
				die('<div class="box-body"><p><b>' . lang('user_no_exist_msg') . '</b></p></div>');
			} ?>

			<div class="box-body">
				<div class="row">
					<div class="col-md-6 form-group">
						<label><?php eLang('first_name'); ?><span class="astric">*</span></label>
						<p class="form-control-static">
							<?php echo !empty($member_data->first_name) ? htmlspecialchars($member_data->first_name) : '-'; ?>
						</p>
					</div>
					<div class="col-md-6 form-group">
						<label><?php eLang('last_name'); ?></label>
						<p class="form-control-static">
							<?php echo !empty($member_data->last_name) ? htmlspecialchars($member_data->last_name) : '-'; ?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label><?php eLang('email'); ?><span class="astric">*</span></label>
						<p class="form-control-static">
							<?php echo !empty($member_data->email) ? htmlspecialchars($member_data->email) : '-'; ?>
						</p>
					</div>

					<div class="col-md-6 form-group">
						<label><?php eLang('role'); ?></label>
						<p class="form-control-static">
							<?php
							$role = isset($member_data->role) ? $member_data->role : '';
							if ($role == 'superadmin') {
								eLang('admin');
							} elseif ($role == 'user') {
								eLang('user');
							} else {
								echo '-';
							}
							?>
						</p>
					</div>
				</div>


				<div class="row">
					<div class="col-md-6 form-group">
						<label><?php eLang('password'); ?></label>
						<p class="form-control-static">
							••••••••
							<a href="<?php echo $router->generate('edit_member', ['id' => $member_data->id]); ?>"
								class="btn btn-xs btn-default" style="margin-left: 10px;">
								<?php eLang('change_password'); ?>
							</a>
						</p>
					</div>
				</div>


				<div class="row sap_plan">
					<div class="col-md-6 form-group d-flex">
						<label style="margin: 0 50px 0 0"><?php elang('status'); ?></label>
						<div class="">
							<?php
							if ($member_data->status == '1') {
								echo '<div class="plan-active">' . lang('active') . '</div>';
							} else {
								echo '<div class="plan-inactive">' . lang('in-active') . '</div>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="content" style=" padding-top: 0;">
		<div class="box box-primary membership_details-table">
			<div class="box-header ">
				<h3 class="box-title"><?php elang('membership_details'); ?></h3>
			</div>
			<div class="box-body">

				<?php
				if (!empty($membership_data)) {
					?>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th><?php elang('membership_level'); ?></th>
								<th><?php elang('allowed_network'); ?></th>
								<th><?php elang('membership_status'); ?></th>
								<th><?php elang('recurring'); ?></th>
								<th><?php elang('expiration_date'); ?></th>
							</tr>
						</thead>
						<tbody>
							<td><?php echo $membership_data->name ?></td>
							<td>
								<?php

								$li_content = '';
								$networks = unserialize($membership_data->networks);
								$networks_count = unserialize($membership_data->networks_count);

								if (!empty($networks)) {
									foreach ($networks as $key => $network) {

										// Convert to lowercase
										$lowercaseString = strtolower($network);
										$network_data_count = isset($networks_count[$network]) ? $networks_count[$network] : "";

										// Replace spaces with hyphens
										$finalkey = str_replace(' ', '-', $lowercaseString);
										if (!empty($network_data_count)) {
											$li_content .= '<div class="' . $finalkey . ' finalnetwork" data-toggle="tooltip" data-placement="top" title="' . sap_get_networks_label($network) . '"> <span class="edit-member-soc-count ' . $network . '-edit-cnt">' . $network_data_count . '</span></div>';
										} else {
											$li_content .= '<div class="' . $finalkey . ' finalnetwork" data-toggle="tooltip" data-placement="top" title="' . sap_get_networks_label($network) . '"> </div>';
										}

									}
									echo rtrim($li_content);
								}
								?>
							</td>
							<td>
								<?php
								$planstatus = get_membership_status_label($membership_data->membership_status);
								if ($membership_data->membership_status == '1') {
									echo '<div class="plan-active">' . lang('active') . '</div>';
								} else {
									echo '<div class="plan-inactive">' . lang('in-active') . '</div>';
								}
								?>
							</td>
							<td><?php echo get_recuring_status_label($membership_data->recurring) ?></td>
							<td><?php echo sap_get_membership_expiration_date($membership_data->expiration_date) ?></td>

						</tbody>

						<tfoot>
							<tr>
								<th><?php elang('membership_level'); ?></th>
								<th><?php elang('allowed_network'); ?></th>
								<th><?php elang('membership_status'); ?></th>
								<th><?php elang('recurring'); ?></th>
								<th><?php elang('expiration_date'); ?></th>
							</tr>
						</tfoot>
					</table>
				<?php } else {
					echo '<p><b>' . lang('customer_membership_purchased') . '</b></p>';
				} ?>
			</div>
		</div>




		<div class="box box-primary membership_details-table">

			<div class="box-header ">
				<h3 class="box-title"><?php elang('recent_payments'); ?></h3>
			</div>
			<div class="box-body">
				<?php if (!empty($payment_data)) { ?>
					<table id="user_payment_histrory" class="display table table-bordered table-striped member-list"
						width="100%">
						<thead>
							<tr>
								<th><?php elang('number'); ?></th>
								<th><?php elang('membership_level'); ?></th>
								<th><?php elang('payment_gateway'); ?></th>
								<th><?php elang('transaction_id'); ?></th>
								<th><?php elang('payment_status'); ?></th>
								<th><?php elang('coupon_name'); ?></th>
								<th><?php elang('amount'); ?></th>
								<th><?php elang('discount_amount'); ?></th>
								<th><?php elang('total_amount'); ?></th>
								<th><?php elang('payment_date'); ?></th>
								<th><?php elang('invoice'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$number = 1;
							foreach ($payment_data as $key => $payment) {

								$test_mode = $this->settings->get_options('stripe_test_mode');
								$stripe_endpoint = ($test_mode == 'yes') ? 'https://dashboard.stripe.com/test/' : 'https://dashboard.stripe.com/';

								$transaction_id = $payment->transaction_id;
								$currency_symbol = $sap_common->get_currency_symbol($payment->currency);

								if (strpos($transaction_id, 'sub_') !== false) {
									$transaction_id = '<a target="__blank" href="' . $stripe_endpoint . 'subscriptions/' . $payment->transaction_id . '">' . $payment->transaction_id . '</a>';
								} elseif (strpos($transaction_id, 'ch_') !== false) {
									$transaction_id = '<a href="' . $stripe_endpoint . 'payments/' . $payment->transaction_id . '">' . $payment->transaction_id . '</a>';
								}
								?>
								<tr>
									<td><?php echo $number ?></td>
									<td><?php echo $payment->plan_name ?></td>
									<td><?php echo ucfirst($payment->gateway) ?></td>
									<td><?php echo $transaction_id ?></td>
									<td><?php echo get_payment_status_label($payment->payment_status) ?></td>
									<td><?php echo isset($payment->coupon_name) != '' || isset($payment->coupon_name) != null ? $payment->coupon_name : ""; ?>
									</td>
									<td><?php echo $currency_symbol . round($payment->amount, 2) ?></td>
									<td><?php echo isset($payment->coupon_discount_amount) != '' || isset($payment->coupon_discount_amount) != null ? $currency_symbol . round($payment->coupon_discount_amount, 2) : $currency_symbol . "0"; ?>
									</td>
									<td><?php echo round($payment->amount, 2) > round($payment->coupon_discount_amount, 2) ? $currency_symbol . round($payment->amount, 2) - round($payment->coupon_discount_amount, 2) : $currency_symbol . "0"; ?>
									</td>
									<td><?php echo sap_format_date($payment->payment_date, true) ?></td>
									<td>
										<div>
											<?php
											echo '<a target="_blank"  class="view-Status" href="' . SAP_SITE_URL . '/payment-invoice/' . $payment->id . '">View</a>';
											?>
										</div>
									</td>
								</tr>
								<?php
								$number++;
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th><?php elang('number'); ?></th>
								<th><?php elang('membership_level'); ?></th>
								<th><?php elang('payment_gateway'); ?></th>
								<th><?php elang('transaction_id'); ?></th>
								<th><?php elang('payment_status'); ?></th>
								<th><?php elang('coupon_name'); ?></th>
								<th><?php elang('amount'); ?></th>
								<th><?php elang('discount_amount'); ?></th>
								<th><?php elang('total_amount'); ?></th>
								<th><?php elang('payment_date'); ?></th>
								<th><?php elang('invoice'); ?></th>
							</tr>
						</tfoot>
						<tbody></tbody>
					</table>
				<?php } else {
					echo '<p><b>' . lang('customer_not_made_payment') . '</b></p>';
				} ?>
			</div>
		</div>
	</section>

</div>
</div>


<script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js' ?>" type="text/javascript"></script>
<script src="<?php echo SAP_SITE_URL . '/assets/js/custom.js'; ?>"></script>
<?php
include 'footer.php';
?>