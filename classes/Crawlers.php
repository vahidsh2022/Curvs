<?php



class SAP_Crawlers
{
    private $common, $db, $flash, $table, $settings;
    private $_id;
    public function __construct()
    {
        $this->table = 'sap_crawlers';
        /** @property Sap_Database $db */
        $this->db = new Sap_Database();
        $this->common = new Common();
        $this->flash = new Flash();
        $this->settings = new SAP_Settings();
        global $match;
        $this->_id = $match['params']['id'] ?? null;
    }

    protected function getRoute()
    {
        global $router;
        if ($this->_id) {
            return $router->generate('crawlers_edit', ['id' => $this->_id]);
        }
        return $router->generate('crawlers_add');
    }

    public function index()
    {
        if (!sap_current_user_can('crawling')) {
            $this->common->redirect('login');
        }


        include_once($this->common->get_template_path('Crawlers' . DS . 'index.php'));
    }

    public function pendings()
    {
        if (sap_current_user_can('crawling')) {
            $this->common->redirect('login');
        }


        include_once($this->common->get_template_path('Crawlers' . DS . 'pending.php'));
    }

    public function list()
    {
        if (!sap_current_user_can('crawling')) {
            $this->common->redirect('login');
        }

        $userId = sap_get_current_user_id();
        $where = " where user_id = $userId ";
        $inputs = $this->db->escape($_GET);

        if (!empty($value = $inputs['search']['value'])) {
            $where .= "and (platform like '%$value%'";
            $where .= "or listening_channel like '%$value%'";
            $where .= "or status like '%$value%')";
        }

        if (!empty($inputs['searchByStatus']) && $inputs['searchByStatus'] != 'all') {
            $where .= " and status = '{$inputs['searchByStatus']}' ";
        }

        $crawlers = $this->db->get_results("select * from $this->table $where order by created_date desc", true);
        $count = $this->db->get_row("select count(*) as count from $this->table $where", true)->count;

        global $router;
        $data = [];
        foreach ($crawlers as $key => $crawler) {
            $channels = '';

            $item = explode(',', $crawler['listening_channel'])[0];
            $url = $item;
            if ($crawler['platform'] == 'telegram') {
                $url = 'https://t.me/' . $url;
            }
            $item = str_replace(['https://', 'http://', 'www.'], '', $item);
            $channels .= "<div><a href='$url' target='_blank'>$item</a></div>";

            $data[] = [
                $key + 1,
                $this->getIconByPlatform($crawler['platform']) . $crawler['platform'],
                $channels,
                $crawler['status'],
                $crawler['translation_language'],
                $crawler['created_date'],
                '<a href="' . $router->generate('crawlers_edit', ['id' => $crawler['id']]) . '" data-toggle="tooltip" title="Edit" data-placement="top"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>',
            ];
        }
        echo json_encode([
            'data' => $data,
            'draw' => $inputs['draw'] + 1,
            'recordsFiltered' => $count,
            'recordsTotal' => $count,
        ]);
    }
    public function pending_list()
    {
        if (sap_current_user_can('crawling')) {
            $this->common->redirect('login');
        }

        $where = " where status = 'pending' and platform = 'web'";
        $inputs = $this->db->escape($_GET);

        if (!empty($value = $inputs['search']['value'])) {
            $where .= "and (platform like '%$value%'";
            $where .= "or listening_channel like '%$value%'";
            $where .= "or status like '%$value%')";
        }

        $crawlers = $this->db->get_results("select * from $this->table $where order by created_date desc", true);
        $count = $this->db->get_row("select count(*) as count from $this->table $where", true)->count;

        global $router;
        $data = [];
        foreach ($crawlers as $key => $crawler) {
            $channels = '';
            foreach (explode(',', $crawler['listening_channel']) as $item) {
                $url = $item;
                if ($crawler['platform'] == 'telegram') {
                    $url = 'https://t.me/' . $url;
                }
                $item = str_replace(['https://', 'http://', 'www.'], '', $item);
                $channels .= "<div><a href='$url' target='_blank'>$item</a></div>";
            }

            $data[] = [
                $key + 1,
                $crawler['platform'] . $this->getIconByPlatform($crawler['platform']),
                $channels,
                $crawler['status'],
                $crawler['translation_language'],
                $crawler['created_date'],
                '<a href="' . $router->generate('crawlers_edit', ['id' => $crawler['id']]) . '" data-toggle="tooltip" title="Edit" data-placement="top"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><i class="fa fa-bolt active-this" data-id="' . $crawler['id'] . '" title="Active"></i>',
            ];
        }
        echo json_encode([
            'data' => $data,
            'draw' => $inputs['draw'] + 1,
            'recordsFiltered' => $count,
            'recordsTotal' => $count,
        ]);
    }
    public function add()
    {
        if (!sap_current_user_can('crawling')) { /// an Exception Situations
            $this->common->redirect('login');
        }

        $crawler = $this->getData();

        $crawler['status'] = $crawler['is_active'] ?? false ? 'active' : 'deactive';
        if ($crawler['platform'] == 'web') {
            $crawler['status'] = 'pending';
        }

        if (($_POST['new_crawler'] ?? false) && $this->save($crawler)) {
            if ($crawler['platform'] == 'telegram' && $crawler['status'] == 'active') {
                if (!$this->send2CrawlerServer($this->db->lastid(), 'telegram')) {
                    $this->flash->setFlash(lang('crwlr_cnt_snd2crwlr'), 'warning');
                }
            }
            $this->flash->setFlash(lang('crwlr_added_sucs'), 'success');
            $this->common->redirect('crawlers');
            return;
        }

        include_once($this->common->get_template_path('Crawlers' . DS . 'add.php'));
    }

    public function edit()
    {
        $isSuperAdmin = sap_get_current_user_role() === 'superadmin';

        if (!sap_current_user_can('crawling') && !$isSuperAdmin) {
            $this->common->redirect('login');
        }
        $crawler = $this->getData($this->_id);
        $crawler['status'] = $crawler['is_active'] ?? false ? 'active' : 'deactive';
        if ($crawler['platform'] == 'web') {
            $crawler['status'] = 'pending';
        }
        if (($_POST['new_crawler'] ?? false) && $this->update($crawler)) {
            if ($crawler['platform'] == 'telegram' && $crawler['status'] == 'active') {
                if (!$this->send2CrawlerServer($this->_id, 'telegram')) {
                    $this->flash->setFlash(lang('crwlr_cnt_snd2crwlr'), 'warning');
                }
            }
            $this->flash->setFlash(lang('crwlr_added_sucs'), 'success');
            if ($isSuperAdmin) {
                $this->common->redirect('crawlers_pendings');
            } else {
                $this->common->redirect('crawlers');
            }
            return;
        }


        include_once($this->common->get_template_path('Crawlers' . DS . 'add.php'));
    }

    public function pendings_active()
    {
//        if (sap_get_current_user_role() !== 'superadmin') {
//            $this->common->redirect('login');
//        }
        $crawler = $this->getData($this->_id);

        if ($this->send2CrawlerServer($this->_id, $crawler['platform'])) {
            $this->flash->setFlash(lang('crwlr_actv_sucs'), 'success');
        } else {
            $this->flash->setFlash(lang('crwlr_cnt_snd2crwlr'), 'warning');
        }
        die('done');
    }

    protected function getData($id = null)
    {
        if (is_array($_POST['crawler'] ?? null)) {
            return $_POST['crawler'];
        }
        if (!empty($id)) {
            $query = "select * from $this->table where id = $id ";
            if (sap_get_current_user_role() == 'user') {
                $query .= 'and user_id = ' . sap_get_current_user_id();
            }
            $crawler = $this->db->get_results("$query limit 1", true);
            if (empty($crawler) || empty($crawler[0])) {
                $this->common->redirect('crawlers' . (sap_get_current_user_role() == 'user' ? '' : '_pendings'));
            }
            $crawler = $crawler[0];
            $crawler['networks'] = unserialize($crawler['networks']);
            return $crawler;
        }
        return [
            'platform' => '',
            'listening_channel' => '',
            'networks' => $this->getNetworksAndSpaces(),// ['twitter' => ['page1' => 0, 'page2' => 0], 'facebook' => ['page1' => 0, 'page2' => 0, 'page3' => 0], 'instagram' => ['channel2' => 0], 'telegram' => ['channel1' => 0, 'channel2' => 0]],
            'automatic' => '',
            'translation_language' => '',
            'replace_before' => '',
            'replace_after' => '',
            'delete_before' => '',
            'delete_after' => '',
            'create_image' => '',
            'create_image_no' => '',
            'is_active' => '',
            'translate_text' => '',
            'validation_text' => '',
            'hashtag_enabled' => '',
            'watermark' => '',
            'watermark_pos' => '',
        ];
    }

    protected function getNetworksAndSpaces()
    {
        $nets = (array) sap_get_users_networks();
        $nets[] = 'telegram';

        $nets = array_flip($nets);
        foreach ($nets as $net => $value) {
            $value = $this->settings->get_user_setting("sap_{$net}_accounts_details");
            if (empty($value)) {
                unset($nets[$net]);
                continue;
            }
            $value = array_column($value, 'name');
            $value = is_array($value) ? array_flip($value) : '';
            $nets[$net] = array_map(fn() => 0, $value);
        }
        return $nets;
    }

    protected function save(array $data = [])
    {
        $errors = $this->validation($data);
        if (!empty($errors)) {
            $this->flash->setFlash($errors, 'error');
            return false;
        }

        $data['user_id'] = sap_get_current_user_id();
        $data['networks'] = serialize($data['networks']);
        $data['listening_channel'] = explode(',',$data['listening_channel'])[0];
        $data['created_date'] = date('Y-m-d H:i:s');
        if (!empty($_FILES['watermark']['name'])) {
            $file = (new FileUploader([]))->uploadFile('watermark');
            $data['watermark'] = uniqid('wm4crwl_') . '.' . pathinfo($file, PATHINFO_EXTENSION);
            rename(SAP_APP_PATH . '/uploads/' . $file, SAP_APP_PATH . '/uploads/' . $data['watermark']);
        }

        $data = $this->db->escape($data);
        if (!$this->db->insert($this->table, $data)) {
            $this->flash->setFlash('error happen, try agin later', 'error');
            return false;
        }
        return true;
    }

    protected function update($data)
    {
        $errors = $this->validation($data);
        if (!empty($errors)) {
            $this->flash->setFlash($errors, 'error');
            return false;
        }

        $data['user_id'] = sap_get_current_user_id();
        $data['networks'] = serialize($data['networks']);
        $data['listening_channel'] = explode(',',$data['listening_channel'])[0];
        $data['created_date'] = date('Y-m-d H:i:s');
        if (empty($data['watermark'])) {
            $data['watermark'] = null;
        }
        if (!empty($_FILES['watermark']['name'])) {
            $file = (new FileUploader([]))->uploadFile('watermark');
            $data['watermark'] = uniqid('wm4crwl_') . '.' . pathinfo($file, PATHINFO_EXTENSION);
            rename(SAP_APP_PATH . '/uploads/' . $file, SAP_APP_PATH . '/uploads/' . $data['watermark']);
        }

        $data = $this->db->escape($data);
        if (!$this->db->update($this->table, $data, ['id' => $this->_id])) {
            $this->flash->setFlash('error happen, try agin later', 'error');
            return false;
        }
        return true;
    }

    protected function validation(array $data = [])
    {
        $factory = getValidator([
            'platform.required' => 'The platform field is required.',
            'platform.in' => 'The platform must be either "telegram" or "web".',
            'listening_channel.required' => 'The listening channel field is required.',
            'translation_language.required' => 'The translation language field is required.',
            'translate_text.required' => 'The translate text field is required.',
        ]);

        // Step 4: Define validation rules
        $rules = [
            'platform' => 'required|in:telegram,web', // Must be either "telegram" or "web"
            'listening_channel' => 'regex:' . ($data['platform'] == 'web' ?
                '/^\s*(https?:\/\/[^\s,]+)\s*(,\s*https?:\/\/[^\s,]+\s*)*$/' :
                '/^\s*(@?[a-zA-Z0-9_]{5,})\s*(,\s*@?[a-zA-Z0-9_]{5,}\s*)*$/'), // telegram 
            'networks' => 'required|array|min:1',
            'automatic' => 'boolean', // Must be a boolean (true/false)
            'translation_language' => 'required|string|max:50', // Required, string, max 50 characters
            'replace_before' => 'nullable|string|max:255|regex:/([^=]+=[^,،]+)(,[^=]+=[^,،]+)*/', // Optional, string, max 255 characters
            'replace_after' => 'nullable|string|max:255', // Optional, string, max 255 characters
            'delete_before' => 'nullable|string|max:255', // Optional, string, max 255 characters
            'delete_after' => 'nullable|string|max:255', // Optional, string, max 255 characters
            'create_image' => 'boolean', // Must be a boolean (true/false)
            'create_image_no' => 'boolean', // Must be a boolean (true/false)
            'is_active' => 'boolean', // Must be a boolean (true/false)
            'translate_text' => 'string', // Required, string
            'validation_text' => 'string', // Required, string
            'hashtag_enabled' => 'boolean', // Must be a boolean (true/false)
            'watermark' => 'max:5120',
            'watermark_pos' => 'string|in:center,top-middle,bottom-middle,middle-left,middle-right,top-left,bottom-left,top-right,bottom-right,clear|required_with:watermark',
        ];

        // Step 5: Validate the data
        $validator = $factory->make($data, $rules);

        // Step 6: Check if validation fails
        if ($validator->fails()) {
            // Get errors
            $errors = $validator->errors();

            $messages = '';
            // Display errors
            foreach ($errors->toArray() as $key => $error) {
                $messages .= "$key: " . implode($error) . "<br>";
            }
            return $messages;
        } else {
            return;
        }

    }

    public function getCrawlers()
    {
        $result = [];
        try {
            $user_id = sap_get_current_user_id();

            $result = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE `user_id` = {$user_id} ORDER BY `created_date` DESC");
        } catch (Exception $e) {
            return $e->getMessage();
        }

        //Return result
        return $result;
    }

    public function send2CrawlerServer(int $id, $platform)
    {
        $request = $this->getRequest($id);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://65.21.168.172:8282/create-$platform/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ]);

        $response = curl_exec($curl);
        file_put_contents(SAP_LOG_DIR . "/callCrawler/$id-$platform.log", print_r([
            'curl' => curl_error($curl),
            'info' => curl_getinfo($curl),
            'request' => $request,
            'response' => $response
        ], true));
        curl_close($curl);

        $updates = ['sent_date' => date('Y-m-d H:i:s')];
        $isSuccess = $response == '{"message":"success"}';
        if ($isSuccess) {
            $updates['status'] = 'active';
        }

        return $isSuccess && $this->db->update($this->table, $updates, ['id' => $id]);
    }

    private function replacingFormater($string)
    {
        $string = explode(',', $string);
        $result = [];
        foreach ($string as $item) {
            list($find_what, $replace_with) = explode('=', $item, 2);
            if (empty($find_what) || empty($replace_with)) {
                continue;
            }
            $result[] = compact('find_what', 'replace_with');
        }
        return json_encode($result);
    }

    protected function resolveSpaces(array $networks, int $userId)
    {
        foreach ($networks as $network => $items) {
            foreach ($items as $item => $value) {
                if ($value == '0') {
                    unset($networks[$network][$item]);
                }
            }
            if (empty($networks[$network])) {
                unset($networks[$network]);
            }
        }

        $final = [];
        $counter = 0;
        foreach ($networks as $network => $items) {
            $final[$counter] = [
                'name' => $network,
                'channels' => [],
            ];
            $configs = $this->settings->get_user_setting("sap_{$network}_options", $userId);
            dd('first cg',$configs,$network,$userId);
            $configs = array_column($configs["{$network}_keys"], null, 'channel_id');

            foreach ($items as $name => $value) {
                $limit = empty($configs[$name]['limit_value']) ? 1 : (int) $configs[$name]['limit_value'];
                $interval = ($configs[$name]['limit_type'] == 'daily' ? 86400 : 3600) / $limit;
                $sleepFrom = empty($configs[$name]['sleep_from']) ? null : (int) $configs[$name]['sleep_from'];
                $sleepTo = empty($configs[$name]['sleep_to']) ? null : (int) $configs[$name]['sleep_to'];

                $value = [
                    "channel_id" => $name,
                    "send_interval" => $interval,
                    'sleep_from' => $sleepFrom,
                    'sleep_to' => $sleepTo,
                    "send_timestamp" => null,
                ];
                $final[$counter]['channels'][] = $value;
            }
        }

        return $final;
    }

    protected function split($string)
    {
        if (empty($string)) {
            return '[]';
        }

        if (strstr($string, ',')) {
            return json_encode(explode(',', $string));
        }

        return "[\"$string\"]";
    }

    protected function getRequest($id)
    {
        $crawler = $this->db->get_results("select * from {$this->table} where id = $id")[0];
        try {
            $channels = json_encode($this->resolveSpaces(unserialize($crawler->networks), $crawler->user_id));
            dd('cch',$channels);
        } catch (Exception $exception ) {
            dd($exception->getMessage());
        }


        $deleteBefore = $this->split($crawler->delete_before);
        $deleteAfter = $this->split($crawler->delete_after);

        $replaceBefore = $this->replacingFormater($crawler->replace_before);
        $replaceAfter = $this->replacingFormater($crawler->replace_after);
        $isActive = $crawler->is_active ? 'true' : 'false';
        $watermark = empty($crawler->watermark) ? 'null' : sprintf("\"%s%s\"", SAP_IMG_URL, $crawler->watermark);
        $pos = empty($crawler->watermark_pos) || $watermark == 'null' ? 'null' : "\"{$crawler->watermark_pos}\"";
        $valid = empty($crawler->validation_text) ? 'null' : "\"{$crawler->validation_text}\"";
        $trans = empty($crawler->translate_text) ? 'null' : "\"{$crawler->translate_text}\"";
        return <<<json
{
    "bot_id": $id,
    "is_active": $isActive,
    "listening_channel": "$crawler->listening_channel",
    "data":{
        "network": $channels,
        "translation_prompt": "Translate the text into $crawler->translation_language.",
        "additional_prompt": $trans,
        "validation_prompt": $valid,
        "text_replacements": $replaceBefore,
        "text_deletions": $deleteBefore,
        "translation_replacements": $replaceAfter,
        "translation_deletions": $deleteAfter,
        "generate_tags": $crawler->hashtag_enabled,
        "generate_image": $crawler->create_image,
        "image_watermark_url": $watermark,
        "image_watermark_position": $pos
    }
}
json;
    }

    public function getIconByPlatform(string $platform = '', int $size = 1): string
    {
        switch ($platform) {
            case 'telegram':
                $icon = "<span class='margin-r-5'> <i class='fa fa-telegram fa-{$size}x'></i> </span>";
                break;
            case 'web':
                $icon = "<span class='margin-r-5'> <i class='fa fa-internet-explorer'></i> </span>";
                break;
            default:
                $icon = "<span class='page-title-icon crawler_{$platform}_icon'> </span>";
        }

        return $icon;
    }
}