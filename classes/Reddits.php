<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

/**
 *  Reddit Class function
 * 
 * A class contains common function to be used to throughout the System
 *
 * @package Mingle
 * @since 2.0.0
 */
class SAP_Reddits{
	
	
	private $table_name;
	private $db;
	public $flash;
	public $common;	
	public $membership;
	private $settings;
	public $sap_common, $users_table, $membership_table;

	public function __construct() {
		
		global $sap_common;
		$this->db = new Sap_Database();
		$this->table_name = 'sap_plans';
		$this->users_table = 'sap_users';
		$this->membership_table = 'sap_membership';
		$this->flash = new Flash();
		$this->common = new Common();
		$this->settings = new SAP_Settings();
		$this->sap_common = $sap_common;

		

	}

	/**
	 * Add wordpress site using ajax function
	 * 
	 * @package Mingle
	 * @since 2.0.0
	 */

	public function fetch_reddit_flair(){ 
		
		$login_user_id = sap_get_current_user_id();
		if (!class_exists('SAP_Reddit')) {
			include ( CLASS_PATH . 'Social' . DS . 'redditConfig.php' );
		}
		$reddit = new SAP_Reddit();

		$subreddits_accounts = $reddit->sap_auto_poster_get_reddit_accounts_with_subreddits( $login_user_id );   

		$sap_reddit_options     = $this->settings->get_user_setting('sap_reddit_options');
        $sap_reddit_sess_data   = $this->settings->get_user_setting('sap_reddit_sess_data');
		
        ?>
        <table>
            <tbody>			
            <?php 
            
            if(!empty($subreddits_accounts) && is_array($subreddits_accounts)) {
                
                $user_id = (isset($_POST['user_id']) && !empty($_POST['user_id'])) ? $_POST['user_id'] : '';
                if($user_id){
                   
                    $aval_data = array_key_exists( $user_id , $subreddits_accounts ) ? $subreddits_accounts[$user_id] : array();
                   //foreach($subreddits_accounts as $aval_key => $aval_data) {
                        $main_account_details = explode('|', $aval_data['main-account']);
                        $main_account_name = !empty( $main_account_details[1] ) ? 
                        $main_account_details[1] : '';	
                        $access_token = '';
                        echo "<tr><td colspan='2' style='text-align: center;border: 1px solid;'><strong>".$main_account_details[1]."</strong></td></tr>";
                        $sub_reddit_flair = ( !empty($sap_reddit_options) && isset( $sap_reddit_options['sub_reddit_flair'][$user_id] )) ? $sap_reddit_options['sub_reddit_flair'][$user_id] : '';
                        if($sap_reddit_sess_data && !empty($sap_reddit_sess_data)){
                            $refresh_token = $sap_reddit_sess_data[$main_account_details[0]]['token_details']['refresh_token'];
                            $newTokenData = $reddit->get_exchange_token($refresh_token);
                        	$access_token = $newTokenData['access_token'];
                        }
                        
                        if (!empty($aval_data['subreddits']) && is_array($aval_data['subreddits'])) { 
                            
                            
                            foreach($aval_data['subreddits'] as $sr_key => $sr_data) { 
                                $sub_reddit_flair_val = isset($sub_reddit_flair[$sr_data]) ? explode(' || ',$sub_reddit_flair[$sr_data]) : '';	
                                $flair_id = (isset($sub_reddit_flair_val[1]) && !empty($sub_reddit_flair_val)) ? $sub_reddit_flair_val[0] : '';																
                                $flair_text = (isset($sub_reddit_flair_val[1]) && !empty($sub_reddit_flair_val)) ? $sub_reddit_flair_val[1] : '';		
																			
                                $flairs = $reddit->getFlair( $sr_data, $access_token );
								
                                if( !empty($flairs)  && !isset($flairs->error) ){
								
                                echo "<tr>";
                                    echo "<td>". $sr_data ."</td>";
                                ?>
                                <td>
                                    <select class="post-types" name='sap_reddit_options[sub_reddit_flair][<?php echo $user_id; ?>][<?php echo $sr_data; ?>]'>
                                        <option value="">-- <?php echo $this->sap_common->lang('select_flair');  ?> --</option>					
                                        <?php  
                                        if( is_array($flairs) && !empty($flairs)){
                                            foreach($flairs as $flair){
												
												if(!empty($flair)){
													$selected = ( $flair->text == $flair_text ) ? 'selected' : '';
                                                	echo '<option value="'.$flair->id.' || '.$flair->text.'" '.$selected.'>'.$flair->text.'</option>';
												}
                                            }
                                        }
                                        
                                        ?>
                                    </select>
                                </td>
                        <?php
                                echo "</tr>"; 
                                }
                            }
                        }
                    ?>
                        
                <?php
                    //}
                }    	
            } 
                ?>
                
            </tbody>
            
        </table>
        <?php 
        exit;
	} 

}