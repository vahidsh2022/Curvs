<?php


class SAP_CPG
{
    private $db;
    //Set table name
    private $table;
    private $post_meta_table_name;
    private $settings;
    public $flash;
    public $common, $sap_common;
    private $_id;

    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
        $this->table = 'sap_crawled_posts';
        $this->flash = new Flash();
        global $match;
        $this->_id = $match['params']['id'] ?? null;
    }

    public function index()
    {
        if (!sap_current_user_can('cpg')) {
            $this->common->redirect('login');
        }

        include_once($this->common->get_template_path('CPG' . DS . 'index.php'));
    }

    private function getData()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $factory = getValidator();
        return $factory->validate($data, [
            'bot_id' => ['bail', 'required', 'integer'],
            'original_subject' => ['string', 'nullable'],
            'new_subject' => ['string', 'nullable'],
            'original_message' => ['required', 'string', 'max:65000'],
            'new_message' => ['required', 'string', 'max:65000'],
            'orginal_image' => ['string', 'nullable'], //['url:http,https', 'between:10,255'],
            'new_image' => ['string', 'nullable'], //['required_with:orginal_image', 'url:http,https', 'between:10,255'],
            'media' => ['nullable','array'],
            'media.*.src' => ['nullable', 'string'],
            'media.*.caption' => ['nullable', 'string'],
            'link' => ['string', 'nullable'], //['url', 'between:10,255'],
            'network' => ['array'],
            'send_at' => ['date_format:Y-m-d H:i:s'],
            'token_count' => ['required', 'integer'],
            'tags' => ['nullable', 'array'],
            'video_link' => ['nullable', 'string'],
            'validation' => ['nullable', 'boolean'],
            'additional_notes' => ['nullable', 'string'],
        ], [
            'bot_id' => lang('cpg_bot_id_vldtn'),
            'original_subject' => lang('cpg_original_subject_vldtn'),
            'new_subject' => lang('cpg_new_subject_vldtn'),
            'original_message' => lang('cpg_original_message_vldtn'),
            'new_message' => lang('cpg_new_message_vldtn'),
            'orginal_image' => lang('cpg_orginal_image_vldtn'),
            'new_image' => lang('cpg_new_image_vldtn'),
            'media' => lang('cpg_media_vldtn'),
            'link' => ['url' => lang('cpg_link_url_vldtn'), 'between' => lang('cpg_link_btwn_vldtn')],
            'network' => lang('cpg_network_vldtn'),
            'send_at' => lang('cpg_send_at_vldtn'),
            'token_count' => lang('cpg_token_count_vldtn'),
            'tags' => lang('cpg_tags_vldtn'),
            'video_link' => lang('cpg_video_link_vldtn'),
            'validation' => lang('cpg_validation_vldtn'),
            'additional_notes' => lang('cpg_additional_notes_vldtn'),
        ]);
    }

    public function getCPGs()
    {
        try {
            $user_id = sap_get_current_user_id();
            return $this->db->get_results("SELECT * FROM " . $this->table . " where crawler_id in (select id from sap_crawlers where user_id = " . $user_id . ") ORDER BY created_at DESC");
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getCPGsBelongsToUser()
    {
        try {
            $userId = sap_get_current_user_id();
            return $this->db->get_results("SELECT cp.* FROM sap_crawlers c JOIN " . $this->table . " cp ON c.id = cp.crawler_id WHERE c.user_id = " . $userId);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    protected function save($data)
    {
        $escapeColumns = [
            'original_subject',

        ];

        foreach ($escapeColumns as $column) {
            if (isset($data[$column])) {
                $data[$column] = $this->db->escape($data);
            }
        }
        return $this->db->insert($this->table, $data);
    }

    public function add()
    {
        $logDir = SAP_LOG_DIR;
        file_put_contents($logDir . "manage_errors.log", print_r([
            'cpg added called'
        ], true),FILE_APPEND);
        $user = $this->getUserByToken();
        file_put_contents($logDir . "manage_errors.log", print_r([
            'get user'
        ], true),FILE_APPEND);
        if (empty($user)) {
            file_put_contents($logDir . "manage_errors.log", print_r([
                '401 error'
            ], true),FILE_APPEND);
            REST([], 401);
        }
        file_put_contents($logDir . "manage_errors.log", print_r([
            'before get cpg'
        ], true),FILE_APPEND);
        try {
            $cpg = $this->getData();
            file_put_contents($logDir . "manage_errors.log", print_r([
                'after get cpg'
            ], true),FILE_APPEND);
        } catch (\Throwable $exception) {
            file_put_contents($logDir . "manage_errors.log", print_r([
                'validator errors'
            ], true),FILE_APPEND);
            REST($exception->validator->errors()->toArray(), 400);
        }

        file_put_contents($logDir . "manage_errors.log", print_r([
            'after validator work'
        ], true),FILE_APPEND);
        $cpg['crawler_id'] = $cpg['bot_id'];
        unset($cpg['bot_id']);
        $networks = $cpg['network'];
        $cpg['network'] = serialize($networks);
        $cpg['created_at'] = date('Y-m-d H:i:s');
        if (isset($cpg['tags'])) {
            $cpg['tags'] = json_encode($cpg['tags'] ?? []);
        }


        if (!empty($cpg['video_link'])) {
            $fileUpload = new FileUploader(array());
            $uploadPath = $fileUpload->uploadFileFromUrl($cpg['video_link']);
            $cpg['video_link'] = $uploadPath;
        }
        if (!empty($cpg['new_image'])) {
            $fileUpload = new FileUploader(array());
            $uploadPath = $fileUpload->uploadFileFromUrl($cpg['new_image']);
            $cpg['new_image'] = $uploadPath;
        }
        if (!empty($cpg['media'])) {
            $fileUpload = new FileUploader(array());
            $uploadPath = [];
            foreach ($cpg['media'] as $media) {
                $uploadPath[] = [
                    'src' => $fileUpload->uploadFileFromUrl($media['src']),
                    'caption' => $media['caption'],
                ];
            }
            $cpg['media'] = json_encode($uploadPath);
        }

        $storedNewMessage = $cpg['new_message'];
        $storedOriginalMessage = $cpg['original_message'];

        $cpg['new_message'] = $this->db->escape($cpg['new_message']);
        $cpg['original_message'] = $this->db->escape($cpg['original_message']);

        $validation = $cpg['validation'] ?? true;
        unset($cpg['validation']);

        $additional_notes = $cpg['additional_notes'] ?? '';
        unset($cpg['additional_notes']);
        $cpg['data_json'] = [
            'additional_notes' => $additional_notes,
        ];
        $cpg['data_json'] = json_encode($cpg['data_json']);

        file_put_contents($logDir . "manage_errors.log", print_r([
            'before save cpg'
        ], true),FILE_APPEND);
        if ($this->save($cpg) === false) {
            file_put_contents($logDir . "manage_errors.log", print_r([
                'save error cpg'
            ], true),FILE_APPEND);
            REST([], 500);
        }

        $cpg['id'] = $this->db->lastid();
        $cpg['new_message'] = $storedNewMessage;
        $cpg['original_message'] = $storedOriginalMessage;
        $cpg['validation'] = $validation;

        file_put_contents(
            SAP_LOG_DIR . "crawledPost/{$cpg['crawler_id']}-" . date('ymd-His') . ".log",
            print_r($cpg, true)
        );

        file_put_contents($logDir . "manage_errors.log", print_r([
            'additional notes'
        ], true),FILE_APPEND);
        if(! empty($additional_notes)) {
            file_put_contents($logDir . "manage_errors.log", print_r([
                'save additional notes'
            ], true),FILE_APPEND);
            REST($cpg);
            return;
        }

        file_put_contents($logDir . "manage_errors.log", print_r([
            'before loop networks'
        ], true),FILE_APPEND);
        foreach ($networks as $network) {
            foreach ($network['channels'] as $channel) {
                $method = 'send2schedulerBy' . ucfirst($network['name']);
                if (
                    !method_exists($this, $method)
                    || !$this->$method($cpg, $channel)
                ) {
                    file_put_contents($logDir . "manage_errors.log", print_r([
                        'method exists not work'
                    ], true),FILE_APPEND);
                    $this->common->sap_script_logs(sprintf(
                        "crawled post (%s) do not send to %s -> %s to %s",
                        $cpg['id'],
                        $network['name'],
                        $channel['channel_id'],
                        $channel['send_timestamp']
                    ));
                }
            }
        }
        file_put_contents($logDir . "manage_errors.log", print_r([
            'after loop networks'
        ], true),FILE_APPEND);
        REST($cpg);
    }

    public function edit()
    {
        if (!sap_current_user_can('cpg')) {
            $this->common->redirect('login');
            return false;
        }

        try {
            global $match;
            $id = $match['params']['id'];
            $this->cpg = $this->getCPGById($id);

            if ($this->cpg->crawler_user_id != sap_get_current_user_id()) {
                throw new Exception('This cpg does not belongs to you.');
            }
            include_once $this->common->get_template_path('CPG' . DS . 'edit.php');

        } catch (Exception $exception) {
            $this->flash->setFlash('Something went wrong. please try again.', 'error');

            $this->common->redirect('CPG');
        }
    }

    public function getCPGById($id)
    {
        $user_id = sap_get_current_user_id();
        try {
            return $this->db->get_row("SELECT cp.*,c.user_id as crawler_user_id FROM sap_crawlers c JOIN " . $this->table . " cp ON c.user_id = $user_id and c.id = cp.crawler_id WHERE cp.id = $id", true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function detail()
    {
        if (!sap_current_user_can('cpg')) {
            $this->common->redirect('login');
        }

        $post = $this->getCPGById($this->_id);


		die(json_encode([
			'original_subject' => ['title' => lang('original_subject'), 'value' => ($post->original_subject ?? '-')],
			'new_subject' => ['title' => lang('new_subject'), 'value' => ($post->new_subject ?? '-')],
			'original_message' => ['title' => lang('original_message'), 'value' => ($post->original_message ?? '-')],
			'new_message' => ['title' => lang('new_message'), 'value' => ($post->new_message ?? '-')],
			'orginal_image' => ['title' => lang('orginal_image'), 'value' => empty($post->orginal_image) ? '/assets/images/no-imag.png' : '../uploads/' . $post->orginal_image],
			'new_image' => ['title' => lang('new_image'), 'value' => empty($post->new_image) ? '/assets/images/no-imag.png' : '../uploads/' . $post->new_image],
			'link' => ['title' => lang('link'), 'value' => $post->link],
			'send_at' => ['title' => lang('send_at'), 'value' => $post->send_at],
			'token_count' => ['title' => lang('token_count'), 'value' => $post->token_count],
			'network' => ['title' => lang('network'), 'value' => '-'],
			'created_at' => ['title' => lang('created_at'), 'value' => $post->created_at],
		]));
    }

    protected function getUserByToken()
    {
        $token = $_SERVER['HTTP_X_USER_KEY'] ?? 'nOTtoKenSEtiNthISrEQuesT';

        $usersByTokens = [
            1 => '847ae912-cf51-4acd-b4c7-cf00fed66770',
            2 => '79e4bfb6-3c1d-471f-abdd-731109f82e5b',
            3 => '5aa53c74-b2b0-48e2-8d21-dcc19ab8dcbc',
            4 => 'a284baae-682a-4d8d-9b8b-b3a81ec2c940',
            5 => '5cce06a9-c29f-4e70-9181-6032c628acac',
        ];

        $id = array_search($token, $usersByTokens);
        if (empty($id)) {
            return null;
        }

        $result = $this->db->get_results("SELECT * FROM sap_users WHERE `id` = {$id}");
        if (empty($result)) {
            return null;
        }

        return $result[0];
    }

    protected function send2schedulerByTwitter($crawledPost, $channel)
    {
        $crawler = $this->db->get_results('select user_id,translation_language from sap_crawlers where id=' . $crawledPost['crawler_id'])[0];
        $user_id = $crawler->user_id;

        $keys = unserialize($this->db->get_results("select setting_value from sap_user_settings where user_id = $user_id and setting_name='sap_twitter_options' ")[0]->setting_value);
        if (empty($keys['twitter_keys'])) {
            return false;
        }

        $footer = "";
        // $footer = "\n\n🔗 \u{200F}[لینک مقاله]({URL})";
        foreach ($keys['twitter_keys'] as $key => $configs) {
            if ($configs['channel_id'] == $channel['channel_id']) {
                $footer = "\n\n" . $configs['footer'];
            }
        }

        $rtl = "\u{200F}";
        $ltr = "\u{200E}";
        $defaultDir = "\u{200F}";
        if (in_array($crawler->translation_language, ['english', 'hines', 'russian', 'hindi'])) {
            $defaultDir = '';
        }
        $footer = str_replace('{URL}', $crawledPost['link'], $footer);
        $footer = str_replace('{ENTER}', "\n", $footer);
        $footer = str_replace('{RTL}', $rtl, $footer);
        $footer = str_replace('{LTR}', $ltr, $footer);

        $message = "$defaultDir{$crawledPost['new_message']} " . (empty($footer) ? '' : $footer);

        $image = '';
        if (!empty($crawledPost['new_image'])) {
            $image = $crawledPost['new_image'];
        }

        $prepare_data = array(
            'message' => $message,
            'user_id' => $user_id,
            'image' => $image ?? '',
            'video' => '',
            'ip_address' => '',
            'status' => 2,
            'created_date' => date('Y-m-d H:i:s'),
            'share_link' => '',
        );

        if (!$this->db->insert('sap_quick_posts', $prepare_data)) {
            return false;
        }
        $post_id = $this->db->lastid();

        $metas = [
            'crawler_id' => $crawledPost['crawler_id'],
            'crawler_post_id' => $crawledPost['id'],
            'sap_schedule_time' => toTimestamp('2025-03-20 07:44:55'),
            'sap_networks' => ['twitter' => 1],
            '_sap_tg_status' => 2,
        ];
        foreach ($metas as $key => $value) {
            $this->save_meta_post($post_id, $key, $value);
        }

        return true;
    }

    protected function send2schedulerByTelegram($crawledPost, $channel)
    {
        $logDir = SAP_LOG_DIR;
        file_put_contents($logDir . "manage_errors.log", print_r([
            'send2schedulerByTelegram'
        ], true),FILE_APPEND);
        $crawler = $this->db->get_results('select user_id,translation_language from sap_crawlers where id=' . $crawledPost['crawler_id'])[0];
        $user_id = $crawler->user_id;

        $keys = unserialize($this->db->get_results("select setting_value from sap_user_settings where user_id = $user_id and setting_name='sap_telegram_options' ")[0]->setting_value);

        file_put_contents($logDir . "manage_errors.log", print_r([
            'before check empty telegram keys'
        ], true),FILE_APPEND);
        if (empty($keys['telegram_keys'])) {
            return false;
        }

        file_put_contents($logDir . "manage_errors.log", print_r([
            'after check empty telegram keys'
        ], true),FILE_APPEND);
        $crawledPostTags = $crawledPost ? json_decode($crawledPost['tags'] ?? [], true) : [];

        file_put_contents($logDir . "manage_errors.log", print_r([
            'wth message footer'
        ], true),FILE_APPEND);
        $message = $this->messageWithFooter($crawledPost['new_message'], $channel['channel_id'], $crawledPostTags, $keys['telegram_keys'], $crawledPost['link']);
        // $footer = "\n\n🔗 \u{200F}[لینک مقاله]({URL})";
//        foreach ($keys['telegram_keys'] as $key => $configs) {
//            if ($configs['channel_id'] == $channel['channel_id']) {
//                $footer = "\n\n" . $configs['footer'];
//            }
//        }
//
//        $rtl="\u{200F}";
//        $ltr = "\u{200E}";
//        $defaultDir="\u{200F}";
//        if(in_array($crawler->translation_language,['english','hines','russian','hindi'])){
//            $defaultDir='';
//        }
//        $footer = str_replace('{URL}', $crawledPost['link'], $footer);
//        $footer = str_replace('{ENTER}', "\n", $footer);
//        $footer = str_replace('{RTL}', $rtl, $footer);
//        $footer = str_replace('{LTR}', $ltr, $footer);
//
//        $message = "$defaultDir{$crawledPost['new_message']} " . (empty($footer) ? '' : $footer);
        $image = '';
        $video = '';
        $media = json_encode([]);

        file_put_contents($logDir . "manage_errors.log", print_r([
            'before media'
        ], true),FILE_APPEND);
        if (!empty($crawledPost['video_link'])) {
            $video = $crawledPost['video_link'];
        }
        if (!empty($crawledPost['new_image'])) {
            $image = $crawledPost['new_image'];
        }
        if(!empty($crawledPost['media'])) {
            $media = $crawledPost['media'];
        }

        $status = $crawledPost['validation'] ? 2 : 4; // 2 pending to send , 4 reject
        $prepare_data = array(
            'message' => $this->db->escape($message),
            // 'message' =>"\u{200F}*{$crawledPost['new_subject']}*\n\n\u{200F}{$crawledPost['new_message']}\n\n🔗 \u{200F}[لینک مقاله]({URL})\n{telegram_link}",
            'user_id' => $user_id,
            'image' => $image,
            'video' => $video,
            'media' => $media,
            'ip_address' => '',
            'status' => $status,
            'created_date' => date('Y-m-d H:i:s'),
            'share_link' => '',
        );

        file_put_contents($logDir . "manage_errors.log", print_r([
            'before insert quick post'
        ], true),FILE_APPEND);
        if (!$this->db->insert('sap_quick_posts', $prepare_data)) {
            return false;
        }
        file_put_contents($logDir . "manage_errors.log", print_r([
            'after insert quick post'
        ], true),FILE_APPEND);
        $post_id = $this->db->lastid();
        $metas = [
            'crawler_id' => $crawledPost['crawler_id'],
            'crawler_post_id' => $crawledPost['id'],
            'sap_schedule_time' => (new DateTime($channel['send_timestamp']))->getTimestamp() + 300,
            'sap_networks' => ['telegram' => $channel['channel_id']],
            '_sap_tg_status' => $status,
        ];
        file_put_contents($logDir . "manage_errors.log", print_r([
            'before loop meta'
        ], true),FILE_APPEND);
        foreach ($metas as $key => $value) {
            $this->save_meta_post($post_id, $key, $value);
        }
        file_put_contents($logDir . "manage_errors.log", print_r([
            'after loop meta saved'
        ], true),FILE_APPEND);
        return true;
    }

    protected function save_meta_post($id, $key, $value)
    {
        return $this->db->insert('sap_quick_postmeta', [
            'post_id' => $id,
            'meta_key' => $key,
            'meta_value' => is_array($value) ? serialize($value) : $this->db->filter($value),
        ]);
    }

    public function messageWithFooter(string $message, string $chat_id, mixed $crawledPostTags, array $keys, $url)
    {
        if (empty($keys)) {
            return $message . "\n" . $this->tagsTransformer($crawledPostTags);
        }

        // $footer = "\n\n🔗 \u{200F}[لینک مقاله]({URL})";
        $footer = '';

        foreach ($keys as $key => $configs) {
            if ($configs['channel_id'] == $chat_id) {
                $footer = $configs['footer'];
            }
        }
        $rtl = "\u{200F}";
        $ltr = "\u{200E}";
        $footer = str_replace('{ENTER}', "\n", $footer);
        $footer = str_replace('{RTL}', $rtl, $footer);
        $footer = str_replace('{LTR}', $ltr, $footer);
        if ($url) {
            $footer = str_replace('{URL}', "[لینک مقاله]($url)", $footer);
        } else {
            $footer = str_replace('{URL}', '', $footer);
        }

        return $message . "\n" . $this->tagsTransformer($crawledPostTags) . "\n" . $footer;
    }

    private function tagsTransformer(mixed $tags): string
    {
        switch (true) {
            case empty($tags): {
                return '';
            }
            case is_string($tags): {
                return $tags;
            }
            case is_array($tags): {
                return implode(' ', $tags);
            }
            default: {
                return json_encode($tags);
            }
        }
    }
}
