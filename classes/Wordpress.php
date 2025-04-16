<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

/**
 *  Wordpress Class function
 * 
 * A class contains common function to be used to throughout the System
 *
 * @package Mingle
 * @since 2.0.0
 */
class SAP_Wordpress{
	
	
	private $table_name;
	private $db;
	public $flash;
	public $common;	
	public $membership;
	public $sap_common, $users_table, $membership_table;

	public function __construct() {
		
		global $sap_common;
		$this->db = new Sap_Database();
		$this->table_name = 'sap_plans';
		$this->users_table = 'sap_users';
		$this->membership_table = 'sap_membership';
		$this->flash = new Flash();
		$this->common = new Common();
		$this->sap_common = $sap_common;
		
		
	}

	/**
	 * Add wordpress site using ajax function
	 * 
	 * @package Mingle
	 * @since 2.0.0
	 */
	public function wordpress_add_site(){
		$website_name =isset($_POST['website_name'])?$_POST['website_name']:"";
		$website_url =isset($_POST['website_url'])?$_POST['website_url']:"";
		$website_unm =isset($_POST['website_unm'])?$_POST['website_unm']:"";
		$website_pwd =isset($_POST['website_pwd'])?$_POST['website_pwd']:"";

		require_once( LIB_PATH . 'wordpress-xmlrpc/WordpressClient.php' );
		require_once ( CLASS_PATH . 'Settings.php');

		
		$SAP_Settings = new SAP_Settings();
		
		$endpoint =$website_url . '/xmlrpc.php';
	

		// Create client instance
		$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient( $endpoint, $website_unm,$website_pwd );
		try{

			$user = $wpClient->getProfile();

			
			$sap_wordpress_options = $SAP_Settings->get_user_setting('sap_wordpress_options');
			if( is_array($user) && xmlrpc_is_fault($user) ) {
				

				$json_data = array(
					'status' => 'fail',
					'errorString' => 'The login details you have entered is incorrect.',
				);
			}
			else{
				$wordpress_keys = array();
				if( !empty($sap_wordpress_options)){
					$wordpress_keys = isset($sap_wordpress_options['wordpress_keys'])?$sap_wordpress_options['wordpress_keys']:array();
				}
				$error =0;
				if( !empty($wordpress_keys)){
					foreach($wordpress_keys as $key_site => $val_site){
							if( $val_site['website_url']  == $website_url)
							{
								$error =1;
							}
					}
				}
				if( $error == 1){
					$json_data = array(
					'status' => '4',
					'errorString' => 'Site is already added please try another site',
					);
				}else{

						$wordpress_site_count =count($wordpress_keys);
						$wordpress_keys[$wordpress_site_count]['website_name'] =$website_name;
						$wordpress_keys[$wordpress_site_count]['website_url'] =$website_url;
						$wordpress_keys[$wordpress_site_count]['website_unm'] =$website_unm;
						$wordpress_keys[$wordpress_site_count]['website_pwd'] =$website_pwd;
						if( empty($sap_wordpress_options)){
							$sap_wordpress_options =array();
						}
						
						$sap_wordpress_options['wordpress_keys'] =$wordpress_keys;

						$update_setting = $SAP_Settings->update_user_setting( 'sap_wordpress_options', $sap_wordpress_options );
						$_SESSION['sap_active_tab'] = 'wordpress';
						$json_data = array(
							'status' => 'success',
							
						);
					}
				
			}
			echo json_encode($json_data);
			exit;
		}
		catch (Exception $e) {
			// Handle the exception
			echo 'An error occurred: ' . $e->getMessage();
		}
	
	 }
	 /**
	 * Delete wordpress site using ajax function
	 * 
	 * @package Mingle
	 * @since 2.0.0
	 */
	 function wordpress_delete_site(){
		$site_id =isset($_POST['site_id'])?$_POST['site_id']:"";
		
		require_once ( CLASS_PATH . 'Settings.php');
		$SAP_Settings = new SAP_Settings();
		$sap_wordpress_options = $SAP_Settings->get_user_setting('sap_wordpress_options');
		$sap_wordpress_site_info =isset($sap_wordpress_options['auto_post_save_data'])? $sap_wordpress_options['auto_post_save_data'] :array();
		//auto_post_save_data
		$wordpress_site_data = isset($sap_wordpress_options['wordpress_keys'])? $sap_wordpress_options['wordpress_keys'] :array();
		if(!empty($wordpress_site_data)){
			$delete_site_info = $wordpress_site_data[$site_id];
			if(!empty($sap_wordpress_site_info)){
				foreach($sap_wordpress_site_info as $key_site_info => $val_siteinfo) {
					$site_name= $val_siteinfo;
					$site_arr = explode("-",$val_siteinfo);
					if( isset($site_arr[0]) && $site_arr[0] ==$delete_site_info['website_name']){
						unset($sap_wordpress_site_info[$key_site_info]);
					}
				}
			}
		}
		$sap_wordpress_site_info = array_values($sap_wordpress_site_info);
		$sap_wordpress_options['auto_post_save_data'] = $sap_wordpress_site_info;
		unset($wordpress_site_data[$site_id]);
		$wordpress_site_data =array_values($wordpress_site_data);
		$sap_wordpress_options['wordpress_keys'] = $wordpress_site_data;

		$update_setting = $SAP_Settings->update_user_setting( 'sap_wordpress_options', $sap_wordpress_options );
		$_SESSION['sap_active_tab'] = 'wordpress';
		$json_data = array(
			'status' => 'success',
		);
		echo json_encode($json_data);
		exit;

	 }

	 /**
	 * Get site post types using xmlrpc 
	 *
	 * @package Mingle
 	 * @since 3.5.1
	 */
	public function wpw_auto_poster_get_site_post_types( $data ) {

		require_once( LIB_PATH . '/wordpress-xmlrpc/WordpressClient.php' );

		
		// Create endpoint
		$endpoint = esc_url( $data['url'] ) . '/xmlrpc.php';

		// Create client instance
		$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient( $endpoint, $data['username'], $data['password'] );

		$postTypes = $wpClient->getPostTypes( array('public' => true) );

		if( is_array($postTypes) && xmlrpc_is_fault($postTypes) ) {
			return false;
		}

		return $postTypes;
	}

}