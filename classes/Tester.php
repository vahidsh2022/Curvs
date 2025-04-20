<?php

class SAP_Tester
{

    public function send_quick_post_to_telegram()
    {
        global $match;
        if(! class_exists('SAP_Quick_Posts')) {
            require_once ( CLASS_PATH . 'Quick_Posts.php');
        }
        $quickPost = new SAP_Quick_Posts();

        if(! class_exists('SAP_Telegram')) {
            require_once ( CLASS_PATH . 'Social' . DS . 'telegramConfig.php');
        }
        $telegramConfig = new SAP_Telegram();

        $res = $telegramConfig->sap_quick_post_to_telegram($match['params']['id']);
        REST($res);
    }

    public function get_request_crawler_format()
    {
        global $match;
        if(! class_exists('SAP_Crawlers')) {
            require_once ( CLASS_PATH . 'Crawlers.php');
        }
        $crawlers = new SAP_Crawlers();

        dd($crawlers->getRequest($match['params']['id']));

    }

}