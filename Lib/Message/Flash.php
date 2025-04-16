<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Flash
 *
 * @author PC10
 */
class Flash {

    private $_messageStack;
    public $message;
    private $db;
    private $settings;
    public $sap_common;
    public $_plan_table;

    public function __construct() {
        global $sap_common,$sap_db_connect;
        
        // Set Database
        $this->db = $sap_db_connect;
        $this->sap_common = $sap_common;
        $this->_plan_table = 'sap_plans';
        
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = array('messageStack' => array());
        }

    }

    public function setFlash($message, $class, $icon = '',$unique = false) {
        $this->_messageStack = array('message' => $message, 'class' => $class, 'icon' => $icon);
        if(isset($unique) && $unique == true){
            $this->_messageStack['unique'] = '1';
        }
        $_SESSION['flash']['messageStack'][] = $this->_messageStack;
    }

    public function renderFlash() {
		$render = '';

       $uploads_folder      =  SAP_APP_PATH.'uploads/';
       $is_uploads_writable =  is_writable($uploads_folder);
       
        if( !$is_uploads_writable && $_SESSION['user_details']['role'] == 'superadmin' ) {
            $write_permission_msg = sprintf($this->sap_common->lang('flash_message_write_permission'),$uploads_folder);
            $this->setFlash($write_permission_msg,'error change-uploads-permission');
        }

        $this->settings = new SAP_Settings();
        $current_version = $this->settings->get_options( 'sap_set_manual_upgrade_version');
        if(version_compare($current_version,'1.0.1','lt') || !$current_version) {

            $old_plan = $this->db->get_row( "SELECT COUNT(*) FROM " . $this->_plan_table . " WHERE status = '1' AND networks_count = ''" );
            
            if($old_plan[0] && $_SESSION['user_details']['role'] == 'superadmin') {
                $db_update_msg = sprintf($this->sap_common->lang('flash_message_db_update'),'<br><br><a href="'. SAP_SITE_URL .'/upgrade-process" class="btn btn-primary">Update Mingle Database</a>');
                $this->setFlash($db_update_msg, 'error remove-install-folder'); 
            }
        }
        

        if ( file_exists( SAP_APP_PATH . 'install' ) ) {
            if (isset($_SESSION['user_details']) && !empty($_SESSION['user_details']) && $_SESSION['user_details']['role'] == 'superadmin' ) {
                $this->setFlash($this->sap_common->lang('flash_message_remove_install_folder'), 'error remove-install-folder'); 
            }              
        }
        global $sap_global_settings;
        if (isset($_SESSION['user_details']) && !empty($_SESSION['user_details']) && $_SESSION['user_details']['role'] == 'superadmin' ) {
            
            $license_data = $sap_global_settings->get_options( 'sap_license_data' );

            if ( ! empty( $license_data['license_key'] ) && empty( $license_data['access_token'] ) ) {
                $this->setFlash($this->sap_common->lang('flash_message_update_access_token'), 'error');
            }
        }

        if (isset($_SESSION['user_details']) && !empty($_SESSION['user_details']) && $_SESSION['user_details']['role'] == 'user' ) {
            $user_id = sap_get_current_user_id();
            
            $db_version = $sap_global_settings->get_options( 'sap_set_db_version' );
            if($db_version == '1.0.3'){
                $qp_query = "SELECT * FROM `sap_user_settings` WHERE user_id = ".$user_id." AND setting_name = 'sap_fb_sess_data' AND setting_value = ''";
                $result = $this->db->get_results( $qp_query );
			    if(!empty($result)) {
                    $this->setFlash($this->sap_common->lang('flash_message_fb_graphapi_update'), 'error');
                }
            }
        }

        if (!empty($_SESSION['flash']['messageStack'])) {

        	foreach ( $_SESSION['flash']['messageStack'] as $key => $value ) {

	            $render .= '<div class="alert alert-' . $_SESSION['flash']['messageStack'][$key]['class'] . ' alert-dismissible" role="alert">
	  							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  							' . $_SESSION['flash']['messageStack'][$key]['message'] . '
	  						</div>';
        	}
        	unset($_SESSION['flash']['messageStack']);
        } else {
            $render = "";
        }
        
        return $render;
    }

}
