<?php 

/* Check the absolute path to the Social Auto Poster directory. */
if ( !defined( 'SAP_APP_PATH' ) ) {
    // If SAP_APP_PATH constant is not defined, perform some action, show an error, or exit the script
    // Or exit the script if required
    exit();
}

global $sap_common;
$SAP_Mingle_Update = new SAP_Mingle_Update();
$license_data = $SAP_Mingle_Update->get_license_data();
if( !$sap_common->sap_is_license_activated() ){
	$redirection_url = '/mingle-update/';
	header('Location: ' . SAP_SITE_URL . $redirection_url );
	die();
}

include SAP_APP_PATH . 'header.php';

include SAP_APP_PATH . 'sidebar.php';

$router = $GLOBALS['router'];
// echo SAP_SITE_URL; exit;
// echo $router->generate('process_upgrade'); exit;
$settings_object      = new SAP_Settings();
?>
<style type="text/css">
    .wrap {
        background-color: #f1f1f1;
        height: 100%;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: sans-serif;
    }

    body {
        margin: 0 auto;
        width: 100%;
        height: 100%;
    }

    .wrap .bold {
        font-weight: bold;
        margin-top: 20px;
    }

    h4 {
        font-weight: normal;
    }

    .loader-1 {
        width: 48px;
        height: 48px;
        border: 5px solid #bdbdbd;
        border-bottom-color: #1958f4;
        border-radius: 50%;
        display: inline-block;
        -webkit-animation: rotation 1s linear infinite;
        animation: rotation 1s linear infinite;
    }

     @keyframes rotation {
        0% { transform: rotate(0deg) }
        100% { transform: rotate(360deg) }
      }

     .successfully {
            background-color: #1958f4;
            max-width: 400px;
            padding: 15px 30px;
            margin: 40px;
            color: #fff;
            border-radius: 10px;
            width: 100%;
            text-align: center;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 1px 1px 10px rgba(0,0,0,0.2);
            line-height: 26px;
            flex-direction: column;
    }

    .progress-note {
        text-align: center;
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    svg {
        width: 40px;
        display: block;
        margin-bottom:10px;
    }
    .successfully-note {
        display: block;
    }
    

    #success_tic  h3.modal-title {
        font-size: 25px;
        font-weight: 600;
        line-height: 30.26px; 
    }

    #success_tic .checkmark-circle {
        width: 100px;
        height: 100px;
        position: relative;
        display: inline-flex;
        vertical-align: middle;
        align-items: center;
        justify-content: center;
        background: rgb(0 151 24 / 10%);
        border-radius: 7px;
    }

    #success_tic .checkmark-circle svg {
        margin-bottom: 0;
}
    #success_tic .page-body .head h3 {
        font-family: Inter;
        font-size: 20px;
        line-height: 28px;
        text-align: left;
    }

    #success_tic .page-body {
        max-width: 100%;
        background-color: #FFFFFF;
        margin: 30px 0;
        display: flex;
        justify-content: space-between;
    }

    #success_tic .close {
        opacity: 1;
        position: absolute;
        right: 0px;
        font-size: 30px;
        padding: 3px 15px;
        margin-bottom: 0;
        top: 20px;
    }

    #success_tic .page-body .head h3 span {
        display: block;
        font-weight: 600;
        color: #009718;
    }

    .progress-note .bold {
        font-size: 22px;
        color: rgb(17 17 17 / 60%);
        font-weight: 600;
        margin: 20px 0 0px;
    }

    .progress-note h4 {
        font-family: Inter;
        font-size: 20px;
        font-weight: 500;
        line-height: 35px;
        text-align: center;
        max-width: 600px;
    }

    .progress-note .bold span {
        color: #1958f4;
    }

</style>
<div class="content-wrapper">
    <section class="content">
        <div class="progress-note">
            <span class="loader-1"> </span>
             <div class="bold">
                <?php echo $sap_common->lang('updated_records'); ?>:<span id="total_updated_records">0</span>
            </div>
            <h4><?php echo $sap_common->lang('db_update_inprogrss'); ?></h4>
           
        </div>
        <div class="successfully" style="display:none">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle> <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            <div class="successfully-note">
            <?php echo $sap_common->lang('db_updated_success'); ?>
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="success_tic">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> -->
                                        <!-- <i class="fa fa-times" aria-hidden="true"></i> -->
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                            <path d="M17 2L2 17M2.00002 2L17 17" stroke="#838383" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg> -->
                                    </button>
                                    <h3 class="modal-title">Mingle Database Update</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="page-body">
                                        <div class="head">  
                                            <h3 style="margin-top:5px;"><?php echo sprintf($sap_common->lang('flash_db_updated_success'),"<span>","</span>"); ?></h3>
                                            <!-- <h4>Lorem ipsum dolor sit amet</h4> -->
                                        </div> 
                                        <div class="checkmark-circle"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                                <path d="M25 0C28.8778 0 32.5476 0.883452 35.8265 2.45923C34.4844 3.52107 33.2399 4.54043 32.0804 5.52158C29.8717 4.71882 27.489 4.28135 25.0042 4.28135C19.283 4.28135 14.1013 6.60041 10.3551 10.3508C6.60466 14.1013 4.28559 19.2788 4.28559 25C4.28559 30.7212 6.60466 35.8987 10.3551 39.6492C14.1055 43.3996 19.283 45.7187 25.0042 45.7187C30.7255 45.7187 35.9072 43.3996 39.6534 39.6492C43.4038 35.8987 45.7229 30.7212 45.7229 25C45.7229 23.6408 45.5912 22.3072 45.3406 21.0202C46.4152 19.6186 47.5153 18.2212 48.6408 16.8366C49.5243 19.3935 50.0042 22.1415 50.0042 25C50.0042 31.902 47.2052 38.1541 42.6818 42.6775C38.1583 47.201 31.9062 50 25.0042 50C18.1023 50 11.8502 47.201 7.32671 42.6775C2.79901 38.1541 0 31.902 0 25C0 18.098 2.79901 11.8459 7.32246 7.32246C11.8459 2.79901 18.098 0 25 0ZM13.3537 20.8928L19.4529 20.8121L19.9074 20.931C21.1391 21.6403 22.2987 22.4516 23.3818 23.369C24.1633 24.0316 24.9108 24.7537 25.6201 25.5352C27.8075 22.0141 30.1393 18.7819 32.6028 15.8087C35.2999 12.551 38.1626 9.5948 41.1697 6.89772L41.7644 6.66837H48.42L47.0778 8.15919C42.9536 12.7421 39.2117 17.4779 35.8308 22.3624C32.4499 27.2511 29.4258 32.297 26.7372 37.4915L25.9004 39.1055L25.1317 37.4618C23.713 34.4164 22.0141 31.6216 19.9881 29.1242C17.9621 26.6267 15.6048 24.4096 12.8568 22.5238L13.3537 20.8928Z" fill="#01A601"/>
                                            </svg>
                                        </div> 
                                    </div>
                                </div>
                                <div class="modal-footer text-center">
                                    <a href="<?php echo SAP_SITE_URL ?>"><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $sap_common->lang('close'); ?></button></a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


<?php
include'footer.php';
?>
<!-- jQuery 3 -->
<!-- <script src="<?php echo SAP_SITE_URL . '/assets/js/jquery.min.js'; ?>"></script> -->
<script type="text/javascript" class="init">
    'use strict';
    $(document).ready(function () {     

        $('#success_tic').on('hidden.bs.modal', function () {
            document.location.href = '<?php echo SAP_SITE_URL ?>'; // Redirect to the welcome page
        })

        var page = 1;
        // jQuery('.progress-note').show();
        // jQuery('.successfully').hide();
        
        function process_start(result) {

            if(result != 'completed') {

                $.ajax({
                    url: '<?php echo SAP_SITE_URL ?>/process-upgrade/',
                    type: 'POST',
                    data: {
                        page: page
                    },
                    error: function(e) {
                        console.log(e);
                    },
                    dataType: 'text',
                    success: function(data) {
                        if(data == 'completed') {
                            // console.log(data);
                            // jQuery('.progress-note').hide();
                            // jQuery('.successfully').show();
                            $('#success_tic').modal('show');
                        } else {

                            page = (page*1) + 1
                        }
                        jQuery('#total_updated_records').text((jQuery('#total_updated_records').text()*1) + 10);

                        process_start(data);
                    },
                    
                });
            }
        }

        process_start('');
    });
</script>
