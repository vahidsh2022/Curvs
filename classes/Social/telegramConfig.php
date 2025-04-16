<?php


class SAP_Telegram
{

    private $db, $common, $flash, $telegram, $settings, $user_id, $logs, $sap_common;

    public function __construct($from_user_id = '')
    {
        global $sap_common, $sap_db_connect;
        //Check Settings class not exit then call class
        if (!class_exists('SAP_Settings')) {
            include_once(CLASS_PATH . 'Settings.php');
        }

        //Check Settings class not exit then call class
        if (!class_exists('SAP_Posts')) {
            include_once(CLASS_PATH . 'Posts.php');
        }

        if (!class_exists('SAP_Quick_Posts')) {
            require_once(CLASS_PATH . 'Quick_Posts.php');
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

    public function sap_load_telegram($consumer_key, $consumer_secret, $oauth_token, $oauth_secret)
    {
        dd(__CLASS__ . "->" . __METHOD__, func_get_args());
    }

    public function sap_get_user_data($channel_id)
    {
        return $channel_id;
    }

    public function sap_post_to_telegram($post_id)
    {
        dd(__CLASS__ . "->" . __METHOD__, func_get_args());
    }

    public function sap_quick_post_to_telegram($post_id)
    {
        $quick_post = $this->quick_posts->get_post($post_id, true);
        $networks = $this->quick_posts->get_post_meta($post_id, 'sap_networks');

        $user_id = $quick_post->user_id;
        $this->common->sap_script_logs(print_r($networks, true), $user_id);

        $selectedAccounts = $this->getSelectedAccounts($networks,$user_id);

        $image = $quick_post->image;
        $video = $quick_post->video;
        $media = $quick_post->media ? json_decode($quick_post->media, true) : array();

        $message = str_replace('\n', "\n", $quick_post->message);

        $data = [
            'parse_mode' => 'markdown',
        ];

        if(!empty($media)) {
            $data['media'] = [];
            foreach ($media as $index => $file) {
                $mediaPhotoKey = "media_photo$index";
                $data[$mediaPhotoKey] = new CURLFile(SAP_APP_PATH . '/uploads/' . $file['src']);

                $data['media'][] = [
                    'type' => mediaIsImage($file['src']) ? 'photo' : 'video',
                    'media' => "attach://$mediaPhotoKey",
                    'caption' => $index == array_key_first($media) ? $message : '',
                ];
            }

            $data['media'] = json_encode($data['media']);
        } else if(!empty($video)) {
            $data['video'] = new CURLFile(SAP_APP_PATH . '/uploads/' . $quick_post->video);
        } else if (!empty($image)) {
            $data['photo'] = new CURLFile(SAP_APP_PATH . '/uploads/' . $quick_post->image);
        }

        $responses = [];
        foreach ($selectedAccounts as $account) {
            $data['chat_id'] = $account['name'];
            if(!empty($data['media'])) {
                $response = $this->sendMediaGroup($post_id,$user_id,$data);
            } else if (!empty($data['video'])) {
                $data['caption'] = $message;
                $response = $this->sendVideo($post_id, $user_id, $data);
            } else if (!empty($data['photo'])) {
                $data['caption'] = $message;
                $response = $this->sendPhoto($post_id, $user_id, $data);
            } else {
                $data['text'] = $message;
                $response = $this->sendMessage($post_id, $user_id, $data);
            }

            $responses[] = $response;
        }

        return $responses;
    }

    private function sendMessage($post_id, $user_id, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot6903075389:AAHwPqY1ILKN0KIRAdzyBonff5iLTGJCz8Q/sendMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $log = $this->logs->add_log('telegram', [
            'message' => $data['text'],
            'http_code' => $info['http_code'],
            'channel'=> $data['chat_id'],
        ], '', $user_id);
        $this->sap_common->sap_script_logs("telegram sent. log_id: $log", $user_id);
        file_put_contents(SAP_LOG_DIR . "/callTelegram/$post_id.log", print_r([
            'curl' => curl_error($curl),
            'info' => $info,
            'request' => $data,
            'response' => $response,
            'log_id' => $log,
        ], true));

        curl_close($curl);

        return $response;
    }

    private function sendPhoto($post_id, $user_id, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot6903075389:AAHwPqY1ILKN0KIRAdzyBonff5iLTGJCz8Q/sendPhoto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data'
            ),
        ));

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $log = $this->logs->add_log('telegram', [
            'message' => $data['caption'],
            'http_code' => $info['http_code'],
            'channel'=> $data['chat_id'],
        ], '', $user_id);
        $this->sap_common->sap_script_logs("telegram sent. log_id: $log", $user_id);
        file_put_contents(SAP_LOG_DIR . "/callTelegram/$post_id.log", print_r([
            'curl' => curl_error($curl),
            'info' => $info,
            'request' => $data,
            'response' => $response,
            'log_id' => $log,
        ], true));

        curl_close($curl);

        return $response;
    }

    public function sendVideo($post_id, $user_id, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot6903075389:AAHwPqY1ILKN0KIRAdzyBonff5iLTGJCz8Q/sendVideo',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data'
            ),
        ));

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $log = $this->logs->add_log('telegram', [
            'message' => $data['caption'],
            'http_code' => $info['http_code'],
            'channel'=> $data['chat_id'],
        ], '', $user_id);
        $this->sap_common->sap_script_logs("telegram sent. log_id: $log", $user_id);
        file_put_contents(SAP_LOG_DIR . "/callTelegram/$post_id.log", print_r([
            'curl' => curl_error($curl),
            'info' => $info,
            'request' => $data,
            'response' => $response,
            'log_id' => $log,
        ], true));

        curl_close($curl);

        return $response;
    }


    private function sendMediaGroup($post_id, $user_id, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot6903075389:AAHwPqY1ILKN0KIRAdzyBonff5iLTGJCz8Q/sendMediaGroup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data'
            ),
        ));
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $log = $this->logs->add_log('telegram', [
            'message' => $data['caption'] ?? '',
            'http_code' => $info['http_code'],
            'channel'=> $data['chat_id'],
        ], '', $user_id);
        $this->sap_common->sap_script_logs("telegram sent. log_id: $log", $user_id);
        file_put_contents(SAP_LOG_DIR . "/callTelegram/$post_id.log", print_r([
            'curl' => curl_error($curl),
            'info' => $info,
            'request' => $data,
            'response' => $response,
            'log_id' => $log,
        ], true));

        curl_close($curl);

        return $response;
    }


    public function getSelectedAccounts($networks,$user_id)
    {
        if(empty($user_id)) {
            $user_id = sap_get_current_user_id();
        }
        $accounts = $this->settings->get_user_setting('sap_telegram_accounts_details', $user_id);
        $selectedAccountsIndex = $networks['tg_accounts'] ?? [];

        $selectedAccounts = [];
        foreach ($selectedAccountsIndex as $index) {
            $selectedAccounts[] = $accounts[$index] ?? [];
        }
        $selectedAccounts = array_filter($selectedAccounts);
        if (empty($selectedAccounts)) {
            $selectedAccounts = [
                [
                    'name' => $networks['telegram'] ?? '',
                ],
            ];
            $selectedAccounts = array_filter($selectedAccounts);
        }

        return $selectedAccounts;
    }
}