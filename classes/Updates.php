<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

/**
 * Logs Class
 * 
 * Responsible for all function related to posts
 *
 * @package Social Auto Poster
 * @since 1.0.0
 */
class SAP_Updates{

	private $db;
	private $settings;
	private $dbvarsion;
	private $_plan_table;
	public $common;		
	public $_table_membership, $plan, $_table_payment_history;

	public function __construct() {

		global $sap_db_connect;
        
        // Set Database
        $this->db = $sap_db_connect;
		$this->_plan_table = 'sap_plans';
		$this->_table_membership = 'sap_membership';
		$this->_table_payment_history = 'sap_payment_history';
		$this->common = new Common();

        // Create settings object
		$this->settings = new SAP_Settings();

		// Get database version settings
		$dbvarsion 		= $this->settings->get_options( 'sap_set_db_version' );
		$sap_version 	= $this->settings->get_options( 'sap_version' );
		$sap_new_sass 	= $this->settings->get_options( 'sap_new_sass' );
		
		
		// Check if version is empty
		if( empty($dbvarsion) && ( empty( $sap_version ) || empty( $sap_new_sass ) ) ) {

			// Alter user table, add role column
			$query = "ALTER TABLE `sap_users`
						ADD role varchar(255) NOT NULL AFTER password,
						ADD plan bigint(20) NOT NULL AFTER role,
						ADD expiration varchar(255) NULL AFTER plan,
						ADD email_verification_tokan longtext NULL  AFTER plan,
						ADD status tinyint(2) NULL COMMENT ' 1 active / 0 inactive';";
			$this->db->query( $query );

			// Update table to update default roles of users
			$query = "UPDATE `sap_users` SET role = 'superadmin' WHERE role = '';";
			$this->db->query( $query );

			$query = "UPDATE `sap_users` SET status = '1' WHERE role = 'superadmin';";
			$this->db->query( $query );

			// Alter sap_logs table and add a user_id column
			$query = "ALTER TABLE `sap_logs`
						ADD user_id bigint(20) NOT NULL AFTER id;";
			$this->db->query( $query );

			$insert_email_sub = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('renewal_email_subject', 'Subscription Renewal', 'yes')";
			$this->db->query( $insert_email_sub );

			$payment_gateway = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('payment_gateway', 'manual', 'yes')";
			$this->db->query( $payment_gateway );

			$default_payment_method = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('default_payment_method', 'manual', 'yes')";
			$this->db->query( $default_payment_method );

			$insert_email_content = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('renewal_email_content', '<h3>Hello {user_name},</h3>
                      <p>
						Your current subscription {membership_level}  has been renewed successfully for the subscription id: {subscription_id}. Your {membership_level} plan will be expire on {expiration_date}
						</p>	


                      	<p>Thanks,
                        <br>The Team</p>', 'yes')";
			$this->db->query( $insert_email_content );


			$sql = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('cancelled_membership_email_subject', 'Your membership has been cancelled', 'yes')";
			$this->db->query( $sql );


			$sql = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('cancelled_membership_email_content', '<h3>Hello {user_name},</h3>
                      <p>
            Your current subscription {membership_level}  has been cancelled. You will retain access until {expiration_date}
            </p>
                        <p>Thanks</p>', 'yes')";
			$this->db->query( $sql );

			$sql = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('expired_membership_email_subject', 'Your membership has expired', 'yes')";
			$this->db->query( $sql );

			$sql = "INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('expired_membership_email_content', '<h3>Hello {user_name},</h3>

Your current subscription {membership_level} has expired. 

To renew or upgrade the membership login to your profile and follow the suggested actions. 

Thanks', 'yes')";
			$this->db->query( $sql );

			// Create user settings table
			$query = "CREATE TABLE IF NOT EXISTS `sap_user_settings` (
					`setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`user_id` bigint(20) NOT NULL,
					`setting_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
					`setting_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
					`autoload` varchar(255) NOT NULL DEFAULT 'yes',
					PRIMARY KEY  (`setting_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->db->query( $query );

			// Create plan table
			$query = "CREATE TABLE IF NOT EXISTS `sap_plans` (
					`id` bigint(20) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`stripe_subscription_id` varchar(255) DEFAULT '',
					`stripe_product_id` varchar(255) DEFAULT '',
					`status` tinyint(2) NULL COMMENT ' 1 active / 0 inactive',
					`subscription_expiration_days` int(21)  NULL,
					`description` longtext NULL,
					`price` DOUBLE NOT NULL,
					`networks` longtext NOT NULL,
					`created` datetime NOT NULL,
					`modified_date` datetime NOT NULL,
					PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->db->query( $query );			

			$query = "ALTER TABLE `sap_users` DROP `expiration`;";		
			$query = "ALTER TABLE `sap_users` DROP `plan`;";		
			$query = "ALTER TABLE `sap_users` DROP `picture`;";		
			$this->db->query( $query );

			$query = "ALTER TABLE `sap_users` DROP `plan`;";
			$this->db->query( $query );


			$query = "CREATE TABLE IF NOT EXISTS `sap_membership` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`plan_id` int(11) NOT NULL,
				`membership_duration_days` int(11) DEFAULT NULL,
				`customer_id` varchar(255) NOT NULL,
				`customer_name` varchar(255) NOT NULL,
				`membership_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT ' 0 pending / 1 active / 2 expired /3 cancelled',
				`recurring` tinyint(2) NOT NULL  DEFAULT '0' COMMENT '1 yes / 0 no',
				`gateway` varchar(255) DEFAULT NULL,
				`subscription_id` varchar(255) DEFAULT NULL,
				`expiration_date` varchar(255)  NULL,
				`renew_date` datetime DEFAULT NULL,
				`upgrade_date` datetime DEFAULT NULL,
				`cancellation_date` datetime DEFAULT NULL,
				`previous_plan` varchar(255) DEFAULT NULL,
				`membership_created_date` datetime DEFAULT NULL,
				`created_date` datetime NOT NULL,
				`modified_date` datetime NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->db->query( $query );


			$query = "ALTER TABLE `sap_membership` ADD INDEX( `user_id`); 
			ALTER TABLE `sap_membership` ADD INDEX( `customer_name`); 
			ALTER TABLE `sap_membership` ADD INDEX( `plan_id`);"; 
			$this->db->query( $query );

			$query = "CREATE TABLE IF NOT EXISTS `sap_payment_history` (
				`id` int(20) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`membership_id` int(11) NOT NULL,
				`plan_id` int(11) NOT NULL,
				`customer_id` varchar(255) DEFAULT NULL,
				`customer_name` varchar(255) DEFAULT NULL,
				`customer_email` varchar(255) DEFAULT NULL,
				`payment_date` datetime DEFAULT NULL,
				`amount` double DEFAULT NULL,
				`type` tinyint(2) NOT NULL  DEFAULT '0' COMMENT ' 0 new /  1 renew / 2 upgrade',
				`gateway` varchar(255) DEFAULT NULL COMMENT 'stripe / paypal  / manual',
				`payment_status` tinyint(2) NOT NULL DEFAULT '0'  COMMENT '0 Pending / 1 completed / 2 fail /3 Refunded',
				`transaction_id` varchar(255) DEFAULT NULL,
				`transaction_data` longtext DEFAULT NULL,
				`created_date` datetime NOT NULL,
				`modified_date` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->db->query( $query );


			$query = "ALTER TABLE `sap_payment_history` ADD INDEX( `membership_id`); 
			ALTER TABLE `sap_payment_history` ADD INDEX( `plan_id`);";
			$this->db->query( $query );

			$query = " ALTER TABLE zone RENAME TO sap_zone";
			$this->db->query( $query );
			
			// Update db version option
			$dbvarsion = '1.0.1';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );

		}

		// Check dbversion is 1.0.1
		if( '1.0.0' == $dbvarsion ) {
			//Sap quick posts edit
			$qp_query = "ALTER TABLE `sap_quick_posts` ADD `video` varchar(255)  CHARACTER SET  utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `image`";
			$this->db->query( $qp_query );

			$dbvarsion = '1.0.1';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );
		} elseif( empty( $dbvarsion ) ) {
			$dbvarsion = '1.0.1';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );
		}

		// Check dbversion is 1.0.1
		if( '1.0.1' == $dbvarsion ) {
			//create table for coupons and chnages in payment history table regarding coupons
			$qp_query = "CREATE TABLE IF NOT EXISTS `sap_coupons` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`coupon_code` VARCHAR(100) NOT NULL ,
				`coupon_type` ENUM('fixed_discount','percentage_discount') NOT NULL ,
				`coupon_amount` INT NOT NULL ,
				`coupon_description` TEXT NOT NULL ,
				`coupon_expiry_date` DATETIME NOT NULL ,
				`coupon_status` ENUM('draft','publish','used') NOT NULL ,
				`created_date` DATETIME NOT NULL ,
				`modified_date` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
			$this->db->query( $qp_query );
			  
			$qp_query = "ALTER TABLE `sap_coupons` CHANGE `modified_date` `modified_date` DATETIME NULL DEFAULT NULL";
			$this->db->query( $qp_query );
			$qp_query = "ALTER TABLE `sap_coupons` CHANGE `coupon_expiry_date` `coupon_expiry_date` DATETIME NULL DEFAULT NULL";
			$this->db->query( $qp_query );
			$qp_query = "ALTER TABLE `sap_coupons` CHANGE `coupon_description` `coupon_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";
			$this->db->query( $qp_query );
			$qp_query = "ALTER TABLE `sap_coupons` CHANGE `coupon_amount` `coupon_amount` DOUBLE NOT NULL";
			$this->db->query( $qp_query );
			
			$db_query = "SHOW COLUMNS from `sap_payment_history` LIKE 'coupon_id'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$qp_query = "ALTER TABLE `sap_payment_history` ADD `coupon_id` INT NULL AFTER `payment_date`";
				$this->db->query( $qp_query );
			}
			$db_query = "SHOW COLUMNS from `sap_payment_history` LIKE 'coupon_name'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$qp_query = "ALTER TABLE `sap_payment_history` ADD `coupon_name` VARCHAR(100) NULL AFTER `amount`, ADD `coupon_discount_amount` DOUBLE NULL AFTER `coupon_name`";
				$this->db->query( $qp_query );
			}

			$dbvarsion = '1.0.2';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );
		}
		
		// Check dbversion is 1.0.2
		if( '1.0.2' == $dbvarsion ) {
			$qp_query = "UPDATE `sap_user_settings` set setting_value = '' WHERE setting_name = 'sap_fb_sess_data'";
			$this->db->query( $qp_query );		

			$dbvarsion = '1.0.3';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );		
		}

		if( '1.0.3' == $dbvarsion ) {
			$db_query = "SHOW COLUMNS from `sap_plans` LIKE 'networks_count'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$qp_query = "ALTER TABLE `sap_plans` ADD `networks_count` TEXT NOT NULL AFTER `networks`";
				$this->db->query( $qp_query );
			}
			$dbvarsion = '1.0.4';
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );	
		}
		if( '1.0.4' == $dbvarsion ) {
			$db_query = "SHOW COLUMNS from `sap_membership` LIKE 'networks'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$qp_query = "ALTER TABLE `sap_membership` ADD `networks` TEXT NOT NULL AFTER `previous_plan`,ADD `networks_count` TEXT NOT NULL AFTER `networks`";
				$this->db->query( $qp_query );
			}
			$dbvarsion = '1.0.5';		
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );			
		}
		if( '1.0.5' == $dbvarsion ) {
			$db_query = "SHOW COLUMNS from `sap_payment_history` LIKE 'networks'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$is_currencies_exist = $this->settings->get_options('sap_currencies');

				$currencies = [
					'USD' => '$',
					'AED' => 'د.إ',
					'ALL' => 'Lek',
					'AMD' => '֏',
					'ANG' => 'ƒ',
					'AUD' => '$',
					'AWG' => 'ƒ',
					'AZN' => 'ман',
					'BAM' => 'KM',
					'BBD' => '$',
					'BDT' => '৳',
					'BGN' => 'лв',
					'BMD' => '$',
					'BND' => '$',
					'BSD' => '$',
					'BWP' => 'P',
					'BYN' => 'руб.',
					'BZD' => 'BZ$',
					'CAD' => '$',
					'CDF' => 'FrCD',
					'CHF' => 'CHF',
					'CNY' => '¥',
					'CZK' => 'Kč',
					'DKK' => 'kr',
					'DOP' => 'RD$',
					'DZD' => 'DA',
					'EGP' => '£',
					'ETB' => 'Br',
					'EUR' => '€',
					'FJD' => '$',
					'GBP' => '£',
					'GEL' => 'ლ',
					'GIP' => '£',
					'GMD' => 'D',
					'GYD' => '$',
					'HKD' => '$',
					'HTG' => 'HTG',
					'HUF' => 'Ft',
					'IDR' => 'Rp',
					'ILS' => '₪',
					'INR' => '₹',
					'ISK' => 'kr',
					'JMD' => 'J$',
					'KES' => 'Ksh',
					'KGS' => 'лв',
					'KHR' => '៛',
					'KYD' => '$',
					'KZT' => 'лв',
					'LBP' => '£',
					'LKR' => '₨',
					'LRD' => '$',
					'LSL' => 'LSL',
					'MAD' => 'MAD',
					'MDL' => 'L',
					'MKD' => 'ден',
					'MMK' => 'K',
					'MNT' => '₮',
					'MOP' => 'MOP$',
					'MVR' => 'Rf',
					'MWK' => 'MK',
					'MXN' => '$',
					'MYR' => 'RM',
					'MZN' => 'MT',
					'NAD' => '$',
					'NGN' => '₦',
					'NOK' => 'kr',
					'NPR' => '₨',
					'NZD' => '$',
					'PGK' => 'K',
					'PHP' => '₱',
					'PKR' => '₨',
					'PLN' => 'zł',
					'QAR' => '﷼',
					'RON' => 'lei',
					'RSD' => 'Дин',
					'RUB' => 'руб',
					'SAR' => '﷼',
					'SBD' => '$',
					'SCR' => '₨',
					'SEK' => 'kr',
					'SGD' => 'S$',
					'SLE' => 'SLE',
					'SOS' => 'S',
					'SZL' => 'L',
					'THB' => '฿',
					'TJS' => 'ЅM',
					'TOP' => 'T$',
					'TRY' => '₺',
					'TTD' => 'TT$',
					'TWD' => 'NT$',
					'TZS' => 'TSh',
					'UAH' => '₴',
					'UZS' => 'лв',
					'WST' => 'WS$',
					'XCD' => '$',
					'YER' => '﷼',
					'ZAR' => 'R',
					'ZMW' => 'ZK'
					];

				if(!$is_currencies_exist) {
					$this->settings->add_options( 'sap_currencies',addslashes(json_encode($currencies)));
					$this->settings->add_options( 'sap_selected_currency','USD');
				}

				$qp_query = "ALTER TABLE `sap_payment_history` ADD `networks` TEXT NOT NULL AFTER `customer_email`,ADD `networks_count` TEXT NULL DEFAULT NULL AFTER `networks`,ADD `expiration_date` varchar(255) NULL AFTER `networks_count`,ADD `currency` varchar(255) NOT NULL DEFAULT 'USD' AFTER `amount`";
				$this->db->query( $qp_query );
			}
			$dbvarsion = '1.0.6';		
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );			
		}
		if( '1.0.6' == $dbvarsion ) {
			$db_query = "SHOW COLUMNS from `sap_membership` LIKE 'networks'";
			$result = $this->db->get_results( $db_query );
			if(empty($result)) {
				$qp_query = "ALTER TABLE `sap_membership` ADD `networks` TEXT NOT NULL AFTER `previous_plan`,ADD `networks_count` TEXT NOT NULL AFTER `networks`";
				$this->db->query( $qp_query );
			}
			$dbvarsion = '1.0.7';	
			$this->settings->update_options( 'sap_set_db_version', $dbvarsion );
		}

		if( '1.0.7' == $dbvarsion ) {
				
			$dbvarsion = '1.0.8';	
		}
		
	}
	
	public function upgrade_database() {

		$template_path = $this->common->get_template_path('Update' . DS . 'upgrade.php' );
		include_once( $template_path );			
		
	}
	
	public function process_upgrade() {
		
		$current_version = $this->settings->get_options( 'sap_set_manual_upgrade_version');
		if(!$current_version) {
			$this->settings->add_options( 'sap_set_manual_upgrade_version', '1.0.0');
		}

		if(version_compare($current_version,'1.0.1','lt')) {
			
			$old_plan = $this->db->get_row( "SELECT COUNT(*) FROM " . $this->_plan_table . " WHERE status = '1' AND networks_count = ''" );
			
			if($old_plan[0]) {
				$plans = [];
				$plans = $this->db->get_results( "SELECT * FROM " . $this->_plan_table . " WHERE status = '1' ORDER BY convert(`price`, decimal) ASC" );
		
				foreach($plans as $this_plan) {
					
					if(!$this_plan->networks_count) {
		
						$this_networks_count = array();
						$this_networks = unserialize($this_plan->networks);
						foreach($this_networks as $this_network) {
							
							$this_networks_count[$this_network] = 0;
						}
						
						$data = array(
							'networks_count' => serialize($this_networks_count),
						);
			
						try {
			
							$data   = $this->db->escape($data);
							$conditions = array(
								'id' => $this_plan->id
							);
							$this->db->update($this->_plan_table, $data, $conditions);
							
						}
						catch (Exception $e) {
							return $e->getMessage();
						}
					}
				}
			}
	
			$page = (isset($_REQUEST['page']) && $_REQUEST['page']) ? $_REQUEST['page'] : 1;
			$limit = 10;
			$offset = ($page - 1) * $limit;
			$all_membership_data = $this->db->get_results("SELECT * FROM " . $this->_table_membership . " WHERE (`networks_count`='' OR networks_count IS NULL OR `networks`='' OR networks IS NULL) LIMIT $offset,$limit");
	
			if($all_membership_data) {
	
				$this->plan = new SAP_Plans();
	
				foreach($all_membership_data as $membership_data) {
			
					$all_payment_data = $this->db->get_results("SELECT * FROM " . $this->_table_payment_history . " WHERE user_id=$membership_data->user_id AND (`networks_count`='' OR networks_count IS NULL OR `networks`='' OR networks IS NULL) ORDER BY id DESC");
			
					if($all_payment_data) {

						foreach($all_payment_data as $key => $payment_data) {
							
							// Get active networks
							$plan_data  = $this->plan->get_plan( $payment_data->plan_id, true );
							$networks = !empty($plan_data->networks)? $plan_data->networks :"";
							$networks_count = !empty($plan_data->networks_count)? $plan_data->networks_count :"";
				
							$data = array(
								'networks' => $networks,
								'networks_count' => $networks_count,
								'expiration_date' => (!$key && $membership_data->id == $payment_data->membership_id) ? $membership_data->expiration_date : ''
							);
				
							try {
				
								$data   = $this->db->escape($data);
								$conditions = array(
									'id' => $payment_data->id
								);
								$this->db->update($this->_table_payment_history, $data, $conditions);
								
							}
							catch (Exception $e) {
								echo $e->getMessage();
								// exit;
							}
						}
					}

					if(!$membership_data->networks || !$membership_data->networks_count){
						
						// Get active networks
						$plan_data  = $this->plan->get_plan( $membership_data->plan_id, true );
						$networks = !empty($plan_data->networks)? $plan_data->networks :"";
						$networks_count = !empty($plan_data->networks_count)? $plan_data->networks_count :"";
			
						$data = array(
							'networks' => $networks,
							'networks_count' => $networks_count,
						);
			
						try {
			
							$data   = $this->db->escape($data);
							$conditions = array(
								'id' => $membership_data->id
							);
							$this->db->update($this->_table_membership, $data, $conditions);
							
						}
						catch (Exception $e) {
							echo $e->getMessage();
							// exit;
						}
					}
				}
				echo "processing";
			} else {
				$this->settings->update_options( 'sap_set_manual_upgrade_version', '1.0.1');
				echo "completed";
			}
		}
		exit;
	}
}

return new SAP_Updates();
