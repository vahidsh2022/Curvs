<?php




class SAP_Temp
{

    public function __construct()
    {

    }

    public function index()
    {
        // include_once CLASS_PATH.'Crawlers.php';
        // dd((new SAP_Crawlers())->send2CrawlerServer(2,'telegram'));



        // require_once(CLASS_PATH . 'Social' . DS . 'twitterConfig.php');
        // $tw = new SAP_Twitter(3);
        // $tw->sap_quick_post_to_twitter(88);


        // require_once(CLASS_PATH . 'Social' . DS . 'telegramConfig.php');
        // $tg = new SAP_Telegram(3);
        // $tg->sap_quick_post_to_telegram(88);


        // send posting logs
        // dd((new SAP_Logs())->add_log('telegram', [
        //     'message' => 'xani',
        //     'http_code' => 222,
        //     'joke'=>'yes',
        //     // 'key' => SCALAR_VALUE
        // ],1,5));
        

        
        
        
        // /> scheduling
        $s = new SAP_Shedule_Posts();
        $b = $s->get_sheduled_post_ids();
        $s->now = true;
        try {
            $s->handle_sheduled_posts();
        } catch (\Throwable $th) {
            dd($th);
        }
        REST(['b' => $b, 'ids' => $s->get_sheduled_post_ids()]);
    }
}