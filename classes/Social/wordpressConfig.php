<?php

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

/**
 * Twitter posting
 *
 * Handles all the functions to tweet on twitter
 * 
 * @package Social auto poster
 * @since 1.0.0
 */
class SAP_Wordpress_Config {

    private $db, $common, $flash, $twitter, $settings, $user_id, $logs, $quick_posts, $sap_common;

    public function __construct($from_user_id='') {
        global $sap_common,$sap_db_connect;
        //Check Settings class not exit then call class
        if (!class_exists('SAP_Settings')) {
            include_once( CLASS_PATH . 'Settings.php' );
        }

        //Check Settings class not exit then call class
        if (!class_exists('SAP_Posts')) {
            include_once( CLASS_PATH . 'Posts.php' );
        }

        if (!class_exists('SAP_Quick_Posts')) {
            require_once( CLASS_PATH . 'Quick_Posts.php' );
        }

        //Set Database
        $this->db = $sap_db_connect;
        $this->settings = new SAP_Settings();
        $this->flash = new Flash();
        $this->posts = new SAP_Posts();
        $this->common = new Common();
        $this->logs = new SAP_Logs();
        $this->quick_posts = new SAP_Quick_Posts();
        $this->sap_common = $sap_common;
        $this->user_id = $from_user_id;
    }
     /**
     * Fetch Wordpress site details
     *  
     * Handles to wordpress site details
     * 
     * @package Mingle
     * @since 1.0.0
     */

    public function sap_get_wordpress_urls($user_id=''){
        // Taking some defaults
        $wordpress_data = array();

        $sap_wordpress_options = $this->settings->get_user_setting('sap_wordpress_options', $user_id);
                
        if ( isset($sap_wordpress_options) && $sap_wordpress_options && is_array( $sap_wordpress_options['auto_post_save_data'] ) && !empty( $sap_wordpress_options['auto_post_save_data'] ) ) {

            $wordpress_data = $sap_wordpress_options['auto_post_save_data'];
        }

        return $wordpress_data;
    }
    /**
     * Quick Post To Wordpress
     * 
     * Handles to Quick Post on wodrpress site
     * 
     * @package Mingle
     * @since 1.0.0
     */
    public function sap_quick_post_on_wordpress_post($post_id){

             //Getting wordpress options
            $sap_wordpress_options = $this->settings->get_user_setting('sap_wordpress_options', $user_id);

            //Getting stored wordpress app data
            $sap_wordpress_sess_data = $this->settings->get_user_setting('sap_wordpress_sess_data', $user_id);

            // General setting
            $sap_general_options = $this->settings->get_user_setting('sap_general_options',$user_id);

            $link_timestamp = isset($sap_general_options['timestamp_link']) ? "?".time() : '';

            $sap_networks_meta = $this->quick_posts->get_post_meta($post_id, 'sap_networks');
           
            $wpAllSites = !empty($sap_networks_meta['wordpress_accounts']) ? $sap_networks_meta['wordpress_accounts'] : array();
           
            $all_website_info = isset($sap_wordpress_options['wordpress_keys'])?$sap_wordpress_options['wordpress_keys']:array();
            $quick_post     = $this->quick_posts->get_post($post_id, true);

            $post_img = !empty($quick_post->image) ? $quick_post->image :$sap_wordpress_options['wordpress_image'] ;
            if (!empty($post_img)) {
                $post_img = SAP_IMG_URL . $post_img;
            }

            //Initilize wordpress posting
            $wp_posting = array();

           // Check chats are stored or not
            if( !empty($wpAllSites) ) {

                // NEW Data.
                $custom_content = $quick_post->message;
                $custom_title = !empty($sap_networks_meta['wordpress_title']) ? $sap_networks_meta['wordpress_title'] : $sap_wordpress_options['wordpress_global_title'];
              
                $wp_post_sites = !empty($sap_networks_meta['wordpress_accounts']) ? $sap_networks_meta['wordpress_accounts'] : array();
                $all_website_info = isset($sap_wordpress_options['wordpress_keys'])?$sap_wordpress_options['wordpress_keys']:array();
                require_once( LIB_PATH . '/wordpress-xmlrpc/WordpressClient.php' );
               
                try {

                    if( !empty($wp_post_sites) ) {
                        foreach( $wp_post_sites as $wp_post_site ) {
                            $wp_site = array();
    
                            $wp_post_site_arr = explode( '-', $wp_post_site );
                            $wp_site_id = isset( $wp_post_site_arr[0] ) ? $wp_post_site_arr[0] : '';
                            $wp_post_type = isset( $wp_post_site_arr[1] ) ? $wp_post_site_arr[1] : $post_type;
                           
                            if( !empty($all_website_info)){
                                foreach($all_website_info as $key_siteinfo => $val_siteinfo){
                                    if($val_siteinfo['website_name'] == $wp_site_id){
                                        $wp_site =$val_siteinfo;
                                        break;
                                    } 
                                }
                            }
                            
                            if( empty($wp_site['website_url']) || empty($wp_site['website_unm']) || empty($wp_site['website_pwd']) ) {
                                $wp_posting['fail'] = 1;
                                continue;
                            }
    
                            // Create endpoint
                           $endpoint =  $wp_site['website_url']  . '/xmlrpc.php';
                         
                            // Create client instance
                            $wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient( $endpoint, $wp_site['website_unm'], $wp_site['website_pwd'] );
                            
                            $args = array(
                                'post_type' 	 => $wp_post_type,
                                'post_status' 	 =>'publish',
                                'post_title' 	 => $custom_title,
                                'post_content' 	 => $custom_content,
                            );

                            // Creating post
                            $added_post = $wpClient->newPost( $custom_title, $custom_content, $args );
                            
                            $post_args = array(
                                'post_type' 	 => 'quickshare',
                                'post_status' 	 =>'publish',
                                'title' 	 => $custom_title,
                                'description' 	 => $custom_content,
                            );


                           if( !isset($added_post['faultString']) ) {

                            $WpPost = $wpClient->getPost( $added_post );
                            $post_link = isset( $WpPost['link'] ) ? $WpPost['link'] : '';
    
                            $wp_posting['success'] = 1;
                            $addPostImg = '';
                                if( !empty($post_img) ) {
                                    $post_args['submitted-image-url'] =$post_img;

                                    $post_result = $wpClient->getPost( $added_post );

                                    if( empty($post_result['post_id']) ) {
                                        return false;
                                    }

                                    $image 		= file_get_contents($post_img);
                                    $name 		= basename($post_img);
                                    $mime 		= 'image/jpg';
                                    $bits 		= $image;
                                    $overwrite 	= true;
                                    $postId 	= $post_result['post_id'];

                                    $attachment_result = $wpClient->uploadFile($name, $mime, $bits, $overwrite, $postId);

                                    if( !empty($attachment_result['id']) ) {
                                        $edit_args['post_thumbnail'] = $attachment_result['id'];
                                        $edit_args['custom_fields'] = array(
                                            'key' => '_thumbnail_id',
                                            'value' => $attachment_result['id']
                                        );
                            
                                        // Edit posts to set featured image and category
                                        $imageAdded =  $wpClient->editPost($post_result['post_id'], $edit_args);
                                    }

                                }
    
                                if( empty($addPostImg) && 'null' == strtolower($addPostImg) ) {
                                    $addPostImg == '';
                                }

                               
    
                                // Log the response
                                $postingLog = array(
                                    'id' => $added_post,
                                    'posted_posttype' => $wp_post_type,
                                    'posted_site' => $wp_site['url'],
                                    'message' =>$custom_content,
                                    'display_name'=>$custom_title,
                                );
                                


                                $post_args['submitted-url'] =$wp_site['website_url']."?post_type=quickshare&p=".$post_id;

                                // record logs for wordpress data
                                $requesting_data = array(
                                        'id' =>$added_post ,
                                        'posted_posttype' =>$wp_post_type,
                                        'posted_site'=>$wp_site['website_url'],

                                        
                                );
                                if( !empty($post_img) ) {
                                    $requesting_data['attach-img']= $post_img;
                                }

                                $this->sap_common->sap_script_logs('Wordpress : Post sucessfully posted on - ' . $wp_site['website_name'], $user_id);
                                $this->sap_common->sap_script_logs('WordPress post data : ' . var_export($post_args, true), $user_id);
                                $this->sap_common->sap_script_logs('Wordpress posting request on:  ' . $wp_site['website_name'] ." : ". var_export($requesting_data, true), $user_id);
                                $this->flash->setFlash('Wordpress : Post sucessfully posted on - ' .$wp_site['website_name'], 'success','',true);
                                if (!empty($custom_image)) {
                                    $postingLog['attach-img'] =$addPostImg;
                                }
                               
                            } else {
                                
                                $this->sap_common->sap_script_logs('Wordpress error : ' .$added_post['faultString'], $user_id);
                            }
                        }
    
                       
                        $this->sap_common->sap_script_logs( 'WordPress posting completed successfully.', $user_id );
                    }
                } catch ( Exception $e ) {
                    
                    //record logs exception generated
                    $this->sap_common->sap_script_logs('Wordpress error : ' .$e->__toString(), $user_id);
                    // display error notice on post page
                   
                }

            } 
    
            return $wp_posting;
       
    }
    /**
     * Multi Post To Wordpress
     * 
     * Handles to Multi Post on wodrpress site
     * 
     * @package Mingle
     * @since 1.0.0
     */
    public function sap_wordpress_post_to_userwall($post_id){
        
          //Getting Wordpress options
          $sap_wordpress_options = $this->settings->get_user_setting('sap_wordpress_options', $user_id);
          $custom_title = $this->posts->get_post_meta($post_id, '_sap_wordpress_post_custom_title');
          $wpAllSites = $this->posts->get_post_meta($post_id, '_sap_wordpress_post_accounts');
          $sap_wordpress_custom_title = $this->posts->get_post_meta($post_id, '_sap_wordpress_post_custom_title');
          $custom_content= $this->posts->get_post_meta($post_id, '_sap_wordpress_post_message');
          $custom_image= $this->posts->get_post_meta($post_id, '_sap_wordpress_post_image');
        
          $post = $this->posts->get_post($post_id, true);
          $custom_image = !empty($post->img)?$post->img: $custom_image;
          if( empty($custom_image)){
            $custom_image =$sap_wordpress_options['wordpress_image'];
          }

          $custom_title = !empty($custom_title) ? $custom_title : $sap_wordpress_options['wordpress_global_title'];
          
          $custom_content = isset($_POST['body'])?$_POST['body']:$custom_content;

          //Getting stored wordpress app data
          $sap_wordpress_sess_data = $this->settings->get_user_setting('sap_wordpress_sess_data', $user_id);

          $global_share_post_type = (!empty($sap_wordpress_options['share_posting_type']) ) ? $sap_wordpress_options['share_posting_type'] : 'link_posting';

          // General setting
          $sap_general_options = $this->settings->get_user_setting('sap_general_options',$user_id);

          $link_timestamp = isset($sap_general_options['timestamp_link']) ? "?".time() : '';

          $sap_networks_meta = $this->quick_posts->get_post_meta($post_id, 'sap_networks');
         
          $all_website_info = isset($sap_wordpress_options['wordpress_keys'])?$sap_wordpress_options['wordpress_keys']:array();
          $global_site_info= isset($sap_wordpress_options['auto_post_save_data'])?$sap_wordpress_options['auto_post_save_data']:array();

          if( empty($wpAllSites)){
              $wpAllSites = $global_site_info ;
          }
          
          //Initilize wordpress posting
          $wp_posting = array();

          if (empty($wpAllSites)) {
            $this->flash->setFlash('Wordpress user not selected', 'error' ,'',true);
            $this->sap_common->sap_script_logs('Wordpress error: User not selected for posting.', $user_id);
            //return false
            return false;
        }

          
         // Check chats are stored or not
          if( !empty($wpAllSites) ) {

              $wp_post_sites = $this->posts->get_post_meta($post_id, '_sap_wordpress_post_accounts');
              $all_website_info = isset($sap_wordpress_options['wordpress_keys'])?$sap_wordpress_options['wordpress_keys']:array();
              require_once( LIB_PATH . '/wordpress-xmlrpc/WordpressClient.php' );
              if( empty($wp_post_sites)){
                  $wp_post_sites = $global_site_info ;
                }
             
              try {
                
                  if( !empty($wp_post_sites) ) {
                      foreach( $wp_post_sites as $wp_post_site ) {
                          $wp_site = array();
  
                          $wp_post_site_arr = explode( '-', $wp_post_site );
                          $wp_site_id = isset( $wp_post_site_arr[0] ) ? $wp_post_site_arr[0] : '';
                          $wp_post_type = isset( $wp_post_site_arr[1] ) ? $wp_post_site_arr[1] : $post_type;
                         
                          if( !empty($all_website_info)){
                              foreach($all_website_info as $key_siteinfo => $val_siteinfo){
                                  if($val_siteinfo['website_name'] == $wp_site_id){
                                      $wp_site =$val_siteinfo;
                                      break;
                                  } 
                              }
                          }
                          
                          if( empty($wp_site['website_url']) || empty($wp_site['website_unm']) || empty($wp_site['website_pwd']) ) {
                              $wp_posting['fail'] = 1;
                              continue;
                          }
  
                          // Create endpoint
                         $endpoint =  $wp_site['website_url']  . '/xmlrpc.php';
                       
                          // Create client instance
                          $wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient( $endpoint, $wp_site['website_unm'], $wp_site['website_pwd'] );
                          
                          $args = array(
                              'post_type' 	 => $wp_post_type,
                              'post_status' 	 =>'publish',
                              'post_title' 	 => $custom_title,
                              'post_content' 	 => $custom_content,
                          );

                          // Creating post
                          $added_post = $wpClient->newPost( $custom_title, $custom_content, $args );

                         

                         if( !isset($added_post['faultString']) ) {

                            $post_args = array(
                                'post_type' 	 => 'multishare',
                                'post_status' 	 =>'publish',
                                'title' 	 => $custom_title,
                                'description' 	 => $custom_content,
                            );


                          $WpPost = $wpClient->getPost( $added_post );
                          $post_link = isset( $WpPost['link'] ) ? $WpPost['link'] : '';
  
                          $wp_posting['success'] = 1;
                          $addPostImg = '';
                            if (!empty($custom_image)) {
                                $custom_image = SAP_IMG_URL . $custom_image;
                            }

                        
                            if( !empty($custom_image) ) {
                                $post_args['submitted-image-url']  = $custom_image;

                                    $post_result = $wpClient->getPost( $added_post );

                                    if( empty($post_result['post_id']) ) {
                                        return false;
                                    }

                                    $image 		= file_get_contents($custom_image);
                                    $name 		= basename($custom_image);
                                    $mime 		= 'image/jpg';
                                    $bits 		= $image;
                                    $overwrite 	= true;
                                    $postId 	= $post_result['post_id'];

                                    $attachment_result = $wpClient->uploadFile($name, $mime, $bits, $overwrite, $postId);

                                    if( !empty($attachment_result['id']) ) {
                                        $edit_args['post_thumbnail'] = $attachment_result['id'];
                                        $edit_args['custom_fields'] = array(
                                            'key' => '_thumbnail_id',
                                            'value' => $attachment_result['id']
                                        );
                            
                                        // Edit posts to set featured image and category
                                        $imageAdded =  $wpClient->editPost($post_result['post_id'], $edit_args);
                                    }

                            }

                              // Log the response
                              $postingLog = array(
                                  'id' => $added_post,
                                  'posted_posttype' => $wp_post_type,
                                  'posted_site' => $wp_site['url'],
                                  'message' =>$custom_content,
                                  'display_name'=>$custom_title,
                              );

                              if (!empty($custom_image)) {
                                $postingLog['attach-img'] =$custom_image;
                              }
                              $post_args['submitted-url'] =$wp_site['website_url']."?post_type=multishare&p=".$post_id;

                              $requesting_data = array(
                                'id' =>$added_post ,
                                'posted_posttype' =>$wp_post_type,
                                'posted_site'=>$wp_site['website_url'],
                                        
                                );
                                if( !empty($custom_image) ) {
                                    $requesting_data['attach-img']= $custom_image;
                                }

  
                              // record logs for wordpress data
                              $this->sap_common->sap_script_logs('Wordpress : Post sucessfully posted on - ' . $wp_site['website_name'], $user_id);
                              $this->sap_common->sap_script_logs('Wordpress post data : ' . var_export($post_args, true), $user_id);
                              $this->sap_common->sap_script_logs('Wordpress posting request on:  ' . $wp_site['website_name'] ." : ". var_export($requesting_data, true), $user_id);
                              $this->logs->add_log('wordpress', $postingLog, '', $user_id);
                              $this->flash->setFlash('Wordpress : Post sucessfully posted on - ' .$wp_site['website_name'], 'success','',true);
                              
                          } else {

                              $this->sap_common->sap_script_logs('Wordpress error : ' .$added_post['faultString'], $user_id);
                          }
                      }
                      // record logs for posting done on wordpress
                      $this->sap_common->sap_script_logs( 'WordPress posting completed successfully.' );
                  }
                  
              } catch ( Exception $e ) {
                  
                  //record logs exception generated
                  $this->sap_common->sap_script_logs('Wordpress error : ' .$e->__toString(), $user_id);
                  // display error notice on post page
                  return false;
              }

          } 
          return $wp_posting;
    }
}
