<?php

class SAP_Bots
{
    private Sap_Database $db;
    private Common $common;
    private Flash $flash;
    private string $table;
    public $bot;

    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
        $this->flash = new Flash();
        $this->table = 'sap_bots';
    }

    public function index()
    {
        if(! sap_current_user_can('bots') && sap_get_current_user_role() !== 'superadmin') {
            $this->common->redirect('login');
            return false;
        }

        if(isset($_GET['userId']) && !empty($_GET['userId'])) {
            REST([
                'bots' => $this->getBots($_GET['userId']),
            ]);
            return false;
        }


        include_once $this->common->get_template_path('Bots' . DS . 'index.php');
    }

    public function getAllUsers()
    {
        return $this->db->get_results("SELECT * FROM sap_users WHERE status = 1 AND role = 'user'");
    }

    public function getBots($userId = null)
    {
        try {
            $userId = $userId ?? sap_get_current_user_id();
            $table = $this->table;

            return $this->db->get_results("SELECT * FROM " . $table . " WHERE `user_id` = " . $userId . " ORDER BY `created_at` DESC");
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function add()
    {
        if(! sap_current_user_can('bots')) {
            $this->common->redirect('add');
        }

        include_once $this->common->get_template_path('Bots' . DS . 'add.php');
    }

    public function store()
    {
        try {
            $multiData = $this->storeValidation();
        } catch (Exception $exception) {
            $errors = '';
            foreach ($exception->validator->errors()->all() as $error) {
                $errors .= $error . '<br>';
            }
            $this->flash->setFlash($errors,'error');
            $this->common->redirect('bots_add');
            return false;
        }

        try {
            foreach ($multiData as $data) {
                $type = ucfirst($data['type']);

                $data = $this->db->escape($data);
                if($this->db->insert($this->table, $data)) {
                    $this->flash->setFlash($type . ' ' . lang('bots_saved'),'success');
                } else {
                    $this->flash->setFlash(lang('bots_failed_to_save') . ' ' . $type,'error');
                }

                $botId = $this->db->lastid();
                $bot = $this->db->get_row("SELECT * FROM sap_bots WHERE id = " . $botId,true);
                $this->postBotDataToPython($bot);
            }
            $this->common->redirect(IS_IFRAME ? 'bots_add' : 'bots');
            return true;
        } catch (Exception $exception) {
            $this->flash->setFlash(lang('bots_failed_to_save'),'error');
            $this->common->redirect('bots_add');
            return false;
        }
    }

    public function storeValidation(): array
    {
        $options = $_POST['bots_options'];
        $data = [
            'bots_options' => $options,
        ];

        $validator = getValidator();
        $validator->validate($data,[
            'bots_options' => ['nullable','array'],
            'bots_options.*.likes' => ['nullable'],
            'bots_options.*.comments' => ['nullable'],
            'bots_options.*.follows' => ['nullable'],
            'bots_options.*.auto_answer' => ['nullable'],
            'bots_options.*.retweet' => ['nullable'],
            'bots_options.*.retweet_pages' => ['nullable'],
            'bots_options.*.target' => ['nullable'],
            'bots_options.*.pages' => ['nullable'],
            'bots_options.*.max_message_length' => ['nullable'],
            'bots_options.*.mute_in_minute' => ['nullable'],
            'bots_options.*.warning_message' => ['nullable'],
            'bots_options.*.bad_words' => ['nullable'],
            'bots_options.*.white_list_usernames' => ['nullable'],
        ]);

        $userId = sap_get_current_user_id();
        $createdAt = date('Y-m-d H:i:s');
        $finalData = [];
        foreach ($options as $type => $values) {
            $target = $values['target'];
            $pages = explode(',',$values['pages']);
            $pages = array_map(function($page){
                return trim($page);
            },$pages);
            $pages = array_filter($pages);
            if(empty($pages)) {
                $pages = null;
            }

            unset($values['target']);
            unset($values['pages']);

            if($type == 'twitter') {
                if(isset($values['retweet']) && $values['retweet'] == '1') {
                    $retweetPages = $values['retweet_pages'] ?? [];
                    $retweetPages = explode(',',$retweetPages);
                    $retweetPages = array_map(function($page){
                        return trim($page);
                    },$retweetPages);
                    $retweetPages = array_filter($retweetPages);
                    $values['retweet_pages'] = $retweetPages;
                } else {
                    $values['retweet_pages'] = [];
                }
            }

            if($type === 'telegram_police') {
                $target = 'Telegram Police Target';
                $badWords = explode(',',$values['bad_words']);
                $badWords = array_map(function($page){
                    return trim($page);
                },$badWords);
                $badWords = array_filter($badWords);
                $values['bad_words'] = $badWords;


                $whiteListUsernames = explode(',',$values['white_list_usernames']);
                $whiteListUsernames = array_map(function($page){
                    return trim($page);
                },$whiteListUsernames);
                $whiteListUsernames = array_filter($whiteListUsernames);
                $values['white_list_usernames'] = $whiteListUsernames;
            }

            $finalData[] = [
                'type' => $type,
                'user_id' => $userId,
                'target' => $target,
                'pages' => $pages ? json_encode($pages) : $pages,
                'options' => json_encode($values),
                'created_at' => $createdAt,
            ];
        }

        return array_filter($finalData,function($item){
            return ! empty(trim($item['target'] ?? '')) && ! empty(trim($item['pages'] ?? ''));
        });
    }

    public function edit()
    {
        try {
            if(! sap_current_user_can('bots')) {
                $this->common->redirect('login');
            }

            global $match;
            $id = $match['params']['id'];
            $this->bot = $this->getBotById($id);

            if($this->bot->user_id != sap_get_current_user_id()) {
                throw new Exception('This bot does not belongs to you.');
            }

            include_once $this->common->get_template_path('Bots' . DS . 'edit.php');
        } catch (Exception $exception) {
            $this->flash->setFlash('Something went wrong. please try again.','error');

            $this->common->redirect('bots');
        }
    }

    public function getBotById($id)
    {
        try {
            return $this->db->get_row("SELECT * FROM " . $this->table . " WHERE `id` = " . $id,true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function update()
    {
        try {
            $data = $this->updateValidation();

            if(empty($data)) {
                $this->flash->setFlash('Missing some inputs','error');
                $this->common->redirect('bots');
                return false;
            }
        } catch (Exception $exception) {
            $errors = '';
            foreach ($exception->validator->errors()->all() as $error) {
                $errors .= $error . '<br>';
            }
            $this->flash->setFlash($errors,'error');
            $this->common->redirect('bots');

            return false;
        }

        try {
            global $match;
            $id = $match['params']['id'];

            $type = ucfirst($data['type']);
            $data = $this->db->escape($data);

            if($this->db->update($this->table, $data,['id' => $id])) {
                $this->flash->setFlash($type . ' Bot successfully saved.','success');
            } else {
                $this->flash->setFlash('Unknown error failed to store bot ' . $type,'error');
            }

            $bot = $this->db->get_row("SELECT * FROM sap_bots WHERE id = " . $id,true);
            $this->postBotDataToPython($bot);

            $this->common->redirect('bots');
            return true;
        } catch (Exception $exception) {
            $this->flash->setFlash('Something went wrong. please try again.','error');
            $this->common->redirect('bots');
            return false;
        }
    }

    public function updateValidation(): array
    {
        $options = $_POST['bots_options'];
        $data = [
            'bots_options' => $options,
        ];

        $validator = getValidator();
        $validator->validate($data,[
            'bots_options' => ['required','array'],
            'bots_options.*.likes' => ['nullable'],
            'bots_options.*.comments' => ['nullable'],
            'bots_options.*.follows' => ['nullable'],
            'bots_options.*.auto_answer' => ['nullable'],
            'bots_options.*.retweet' => ['nullable'],
            'bots_options.*.target' => ['nullable'],
            'bots_options.*.pages' => ['required'],
            'bots_options.*.max_message_length' => ['nullable'],
            'bots_options.*.mute_in_minute' => ['nullable'],
            'bots_options.*.warning_message' => ['nullable'],
            'bots_options.*.bad_words' => ['nullable'],
            'bots_options.*.white_list_usernames' => ['nullable'],
        ],[
            'bots_options.*.target.required' => 'Target field is required.',
            'bots_options.*.pages.required' => 'Pages field is required.',
        ]);

        $finalData = [];

        foreach ($options as $type => $values) {
            $target = $values['target'];

            if(! $target && $type !== 'telegram_police') {
                $this->flash->setFlash("Must set target for $type",'error');
                continue;
            }

            $pages = explode(',',$values['pages']);
            $pages = array_map(function($page){
                return trim($page);
            },$pages);
            $pages = array_filter($pages);

            unset($values['target']);
            unset($values['pages']);

            if($type == 'twitter') {
                if(isset($values['retweet']) && $values['retweet'] == '1') {
                    $retweetPages = $values['retweet_pages'] ?? [];
                    $retweetPages = explode(',',$retweetPages);
                    $retweetPages = array_map(function($page){
                        return trim($page);
                    },$retweetPages);
                    $retweetPages = array_filter($retweetPages);
                    $values['retweet_pages'] = $retweetPages;
                } else {
                    $values['retweet_pages'] = [];
                }
            }

            if($type === 'telegram_police') {
                $target = 'Telegram Police Target';
                $badWords = explode(',',$values['bad_words']);
                $badWords = array_map(function($page){
                    return trim($page);
                },$badWords);
                $badWords = array_filter($badWords);
                $values['bad_words'] = $badWords;

                $whiteListUsernames = explode(',',$values['white_list_usernames']);
                $whiteListUsernames = array_map(function($page){
                    return trim($page);
                },$whiteListUsernames);
                $whiteListUsernames = array_filter($whiteListUsernames);
                $values['white_list_usernames'] = $whiteListUsernames;
            }

            $finalData = [
                'type' => $type,
                'target' => $target,
                'pages' => json_encode($pages),
                'options' => json_encode($values),
            ];
        }

        if(empty(trim($finalData['target'])) && empty(trim($finalData['pages']))) {
            $finalData = [];
        }
       return $finalData;
    }

    public function delete()
    {
        try {
            global $match;
            $id = $match['params']['id'];

            if($this->db->delete($this->table,['id' => $id])) {
                REST([]);
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function networkTypes(): array
    {
        return [
            'telegram',
            "telegram_police",
            'instagram',
            'twitter',
        ];
    }

    public function importExcelPage()
    {
        if(sap_get_current_user_role() !== 'superadmin') {
            $this->common->redirect('dashboard');
            return false;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_FILES['file']) && $_FILES['file']) {
                try {
                    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    if($extension !== 'xlsx') {
                        $this->flash->setFlash("Only file with xlsx format can be uploaded",'error');
                        $this->common->redirect('bots_import_excel');
                        return false;
                    }
                    [$total,$success,$failed] = $this->readAndStoreExcelData($_FILES['file']['tmp_name']);

                    $this->flash->setFlash('Total rows detected: '.$total,'info');
                    $this->flash->setFlash('Total rows successfully imported: '.$success,'success');
                    if($failed) {
                        $this->flash->setFlash('Total Rows failed to import: '.$failed,'error');
                    }
                } catch (Exception $exception) {
                    $this->flash->setFlash($exception->getMessage(),'error');
                }
            }

            $this->common->redirect('bots_import_excel');
            return false;
        }

        include_once $this->common->get_template_path('Bots' . DS . 'import_excel.php');
    }

    public function readAndStoreExcelData($file,$type = 'Xlsx'): array
    {
        $sheet = getPHPSpreadsSheet($file, $type);
        $worksheet = $sheet->getSheetByName('data');

        $dataArray = $worksheet->toArray();

        $headers = array_shift($dataArray);

        $allRows = [];

        foreach ($dataArray as $row) {
            $rowData = [];

            foreach ($headers as $index => $header) {
                $rowData[$header] = isset($row[$index]) ? $row[$index] : null;
            }
            $allRows[] = $rowData;
        }

        $total = count($allRows);
        $success = 0;
        $failed = 0;
        foreach ($allRows as $index => $row) {
            $rowNumber = $index + 1;

            $email = $row['user_email'];
            $user = $this->db->get_row("SELECT id from sap_users where email = '$email'" ,true);
            if(empty($user)) {
                $this->flash->setFlash("User not found with this email address {$email} at row {$rowNumber}",'error');
                $failed++;
                continue;
            }
            if(!$this->db->insert($this->table, [
                'user_id' => $user->id,
                'type' => $row['bot_type'],
                'target' => $row['bot_target'],
                'pages' => json_encode(explode(',',$row['bot_pages']) ?? []),
                'options' => json_encode([
                    'likes' => @$row['bot_likes'] == 'true',
                    'comments' => @$row['bot_comments'] == 'true',
                    'follows' => @$row['bot_follows'] == 'true',
                    'auto_answer' => @$row['bot_auto_answer'] == 'true',
                ]),
                'created_at' => date('Y-m-d H:i:s')
            ])) {
                $this->flash->setFlash("Bot at row {$rowNumber} failed to import",'error');

                $failed++;
                continue;
            };
            $lastBotId = $this->db->lastid();
            $accounts = json_decode($row['accounts'] ?? json_encode([]), true);
            $meta = json_decode($row['meta'] ?? json_encode([]), true);

            foreach ($accounts as $account) {
                 if(!$this->db->insert("sap_bots_profiles",[
                    "user_id" => $user->id,
                    "bot_id" => $lastBotId,
                    "name" => $row['profile_name'],
                    'username' => $account['username'],
                    "password" => $account['password'],
                    'email' => $account['email'],
                    'email_password' => $account['email_password'],
                    'type' => $account['type'],
                    'gender' => $row['profile_gender'],
                    'age' => $row['profile_age'],
                    'country' => $row['profile_country'],
                    'city' => $row['profile_city'],
                    "meta" => json_encode($meta),
                    "created_at" => date('Y-m-d H:i:s'),
                ])) {
                     $this->flash->setFlash("Bot profile at row {$rowNumber} failed to import",'error');

                     $failed++;
                     continue;
                 };
            }

            $success++;
        }

        return [$total,$success,$failed];
    }

    public function importExcelForUserAndBotPage()
    {
        if(sap_get_current_user_role() !== 'superadmin') {
            $this->common->redirect('dashboard');
            return false;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $botId = $_POST['bot_id'];
            $_SESSION['form_data'] = [
                'user_id' => $userId,
            ];
            if(empty($userId) || empty($botId)) {
                $this->flash->setFlash("Please select at least one user and bot",'error');
                $this->common->redirect('bots_import_excel_user_and_bot');
                return false;
            }

            if(isset($_FILES['file']) && $_FILES['file']) {
                try {
                    if(empty($_FILES['file']['name'])) {
                        $this->flash->setFlash("Please select your excel file",'error');
                        $this->common->redirect('bots_import_excel_user_and_bot');
                        return false;
                    }

                    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    if($extension !== 'xlsx') {
                        $this->flash->setFlash("Only file with xlsx format can be uploaded",'error');
                        $this->common->redirect('bots_import_excel_user_and_bot');
                        return false;
                    }
                    [$total,$success,$failed,$skip] = $this->readAndStoreExcelDataForUserAndBot($userId,$botId,$_FILES['file']['tmp_name']);

                    $this->flash->setFlash('Total rows detected: '.$total,'info');
                    $this->flash->setFlash('Total rows successfully imported: '.$success,'success');
                    if($skip) {
                        $this->flash->setFlash('Total rows skipped: ' . $skip,'warning');
                    }
                    if($failed) {
                        $this->flash->setFlash('Total Rows failed to import: '.$failed,'error');
                    }
                } catch (Exception $exception) {
                    $this->flash->setFlash($exception->getMessage(),'error');
                }
            }

            $this->common->redirect('bots_import_excel_user_and_bot');
            return false;
        }

        include_once $this->common->get_template_path('Bots' . DS . 'import_excel_user_and_bot.php');
    }

    public function readAndStoreExcelDataForUserAndBot($userId,$botId,$file,$type = 'Xlsx'): array
    {
        $errorReturn = [0,0,0];
        $user = $this->db->get_row("SELECT id from sap_users where id = '$userId'" ,true);
        if(empty($user)) {
            $this->flash->setFlash("User not found with this id {$userId}",'error');
            return $errorReturn;
        }

        $bot = $this->db->get_row("SELECT id,target,pages,type from sap_bots where id = '$botId' and user_id = '$userId'" ,true);
        if(empty($bot)) {
            $this->flash->setFlash("Bot not found with this id {$botId} for user with id '$userId'",'error');
            return $errorReturn;
        }

        $sheet = getPHPSpreadsSheet($file, $type);
        $worksheet = $sheet->getSheetByName('data');

        if(empty($worksheet)) {
            $this->flash->setFlash('Worksheet name must be "data"','error');
            return $errorReturn;
        }

        $dataArray = $worksheet->toArray();

        $headers = array_shift($dataArray);

        $allRows = [];

        foreach ($dataArray as $row) {
            $rowData = [];

            foreach ($headers as $index => $header) {
                $rowData[$header] = isset($row[$index]) ? $row[$index] : null;
            }
            $allRows[] = $rowData;
        }

        $total = count($allRows);
        $success = 0;
        $failed = 0;
        $skip = 0;

        $fileUpload = new FileUploader(array());

        foreach ($allRows as $index => $row) {
            $rowNumber = $index + 1;

            $hasError = false;
            if(empty($row['username'])) {
                $this->flash->setFlash("User name is empty at row {$rowNumber}",'error');
                if(! $hasError) {
                    $failed++;
                    $hasError = true;
                }
            }
            if(empty($row['password'])) {
                $this->flash->setFlash("Password is empty at row {$rowNumber}",'error');
                if(! $hasError) {
                    $failed++;
                    $hasError = true;
                }
            }
            if(empty($row['email'])) {
                $this->flash->setFlash("Email is empty at row {$rowNumber}",'error');
                if(! $hasError) {
                    $failed++;
                    $hasError = true;
                }
            }
            if(empty($row['email_password'])) {
                $this->flash->setFlash("Email password is empty at row {$rowNumber}",'error');
                if(! $hasError) {
                    $failed++;
                    $hasError = true;
                }
            }
            if(empty($row['type'])) {
                $this->flash->setFlash("Type is empty at row {$rowNumber}",'error');
                if(! $hasError) {
                    $failed++;
                    $hasError = true;
                }
            }

            if($hasError) {
                continue;
            }

            if($this->db->exists('sap_bots_profiles','user_id,username,password',[
                'user_id' => $userId,
                'username' => $row['username'],
                'password' => $row['password'],
            ])) {
                $this->flash->setFlash("Skip row {$rowNumber} because username and password already exists.",'warning');
                $skip++;
                continue;
            }
            if($this->db->exists('sap_bots_profiles','user_id,email,email_password',[
                'user_id' => $userId,
                'email' => $row['email'],
                'email_password' => $row['email_password'],
            ])) {
                $this->flash->setFlash("Skip row {$rowNumber} because email and email password already exists.",'warning');
                $skip++;
                continue;
            }

            $meta = json_decode($row['meta'] ?? json_encode([]), true);
            $meta = $this->addDataFromRowToMeta($row,$meta,['userid']);
            if(json_last_error() !== JSON_ERROR_NONE) {
                $this->flash->setFlash("Meta column was invalid format at row {$rowNumber}","error");
                return $errorReturn;
            }
            $image = null;
            if(! empty($row['profile_image'])) {
                $image = $fileUpload->uploadFileFromUrl($row['profile_image']);
            }

            $row = $this->rowGenerator($row);
            if(!$this->db->insert("sap_bots_profiles",[
                "user_id" => $user->id,
                "bot_id" => $botId,
                "name" => $row['profile_name'],
                'username' => $row['username'],
                "password" => $row['password'],
                'email' => $row['email'],
                'email_password' => $row['email_password'],
                'type' => $row['type'],
                'gender' => $row['profile_gender'],
                'age' => $row['profile_age'],
                'country' => $row['profile_country'],
                'city' => $row['profile_city'],
                'image' => $image,
                "meta" => $this->metaGenerator($meta),
                "created_at" => date('Y-m-d H:i:s'),
            ])) {
                $error = error_get_last();
                $this->flash->setFlash("Bot profile at row {$rowNumber} failed to import message: " . json_encode($error),'error');

                $failed++;
                continue;
            };

            $success++;
        }

        if(!$this->postBotProfilesDataToPython($bot)) {
            $failed++;
        }

        return [$total,$success,$failed,$skip];
    }

    private function rowGenerator(array $row)
    {
        if(! class_exists('SAP_BotsProfiles')) {
            require_once CLASS_PATH . '/BotsProfiles.php';
        }
        $botProfile = new SAP_BotsProfiles();

        $profileGender = $row['profile_gender'] ?? array_rand($botProfile->genders());
        $profileName = $row['profile_name'] ?? null;
        if(empty($profileName)) {
            switch ($profileGender) {
                case 'male':
                    $maleNames = ['Alex','Jack'];
                    $profileName = $maleNames[array_rand($maleNames)];
                    break;
                case 'female': {
                    $femaleNames = ['Emma','Mia'];
                    $profileName = $femaleNames[array_rand($femaleNames)];
                    break;
                }
                case 'neutral': {
                    $neutral = ['Kai','Noah'];
                    $profileName = $neutral[array_rand($neutral)];
                    break;
                }
                default: {
                    $profileName = null;
                }
            }
        }


        $row = array_merge([
            'profile_gender' => $profileGender,
            'profile_name' => $profileName,
            'profile_country' => 'iran',
            'profile_city' => 'tehran',
            'profile_age' => rand(20,30),
        ],$this->removeEmptyFieldFromRowOfExcel($row));

        return $row;
    }

    private function metaGenerator(array $meta)
    {
        if(! class_exists('SAP_BotsProfiles')) {
            require_once CLASS_PATH.'/BotsProfiles.php';
        }
        $botProfile = new SAP_BotsProfiles();

        $meta = array_merge([
            "openness" => rand(0,100),
            "conscientiousness" => rand(0,100),
            "extraversion" => rand(0,100),
            "agreeableness" => rand(0,100),
            "neuroticism" => rand(0,100),
            "formality" => array_rand($botProfile->formalities()),
            "tone" => array_rand($botProfile->tones()),
            "verbosity" => array_rand($botProfile->verbosities()),
            "vocabulary" => array_rand($botProfile->vocabularies()),
            "pacing" => array_rand($botProfile->pacings()),
            "useOfEmojis" => array_rand($botProfile->useOfEmojis()),
            "domainExpertise" => "",
            "knowledgeDepth" => array_rand($botProfile->knowledgeDepths()),
            "informationSource" => array_rand($botProfile->informationSources()),
            "proactiveness" => array_rand($botProfile->proactivenesses()),
            "helpfulness" => array_rand($botProfile->helpfulnesses()),
            "humor" => 100,
            "patience" => 100,
            "adaptability" => array_rand($botProfile->adaptabilities()),
        ],$this->removeEmptyFieldFromRowOfExcel($meta));

        return $this->db->escape(json_encode($meta));
    }

    private function removeEmptyFieldFromRowOfExcel(array $row): array
    {
        return array_filter($row, function($val) {
            return (!empty($val) || $val === 0);
        });
    }

    private function addDataFromRowToMeta(array &$row,array $meta,array $keys): array
    {
        foreach ($keys as $key) {
            $meta[$key] = $row[$key] ?? null;
            unset($row[$key]);
        }

        return $meta;
    }


    public function postBotDataToPython($bot): bool
    {
        $botId = $bot->id;
        $url = $this->getBotPythonUrlFromType($bot);
        $data = $this->getBotDataForPythonFromType($bot);

        if(empty($data)) {
            return false;
        }
        if(empty($url)) {
            $this->flash->setFlash("URL python not found for bot id $botId", 'error');
            return false;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        $response = curl_exec($ch);

        $logDir = SAP_LOG_DIR . "/callBot";
        if (!file_exists($logDir)) {
            // اگر پوشه وجود ندارد، آن را ایجاد می‌کنیم
            mkdir($logDir, 0777, true);
        }
        file_put_contents($logDir . "/$botId.log", print_r([
            'curl' => curl_error($ch),
            'info' => curl_getinfo($ch),
            'request' => json_encode($data),
            'response' => $response
        ], true));
        if(curl_errno($ch)) {
            $this->flash->setFlash(curl_error($ch), 'error');
            return false;
        }
        curl_close($ch);

        return true;
    }

    public function postBotProfilesDataToPython($bot): bool
    {
        $botId = $bot->id;
        $profiles = $this->db->get_results("SELECT * FROM sap_bots_profiles where bot_id = '$botId'");
        $url = $this->getBotProfilePythonUrlFromType($bot);
        $data = $this->getBotProfilesDataForPythonFromType($bot,$profiles);
        if(empty($data)) {
            return false;
        }
        if(empty($url)) {
            $this->flash->setFlash("URL python not found for bot id $botId", 'error');
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        $response = curl_exec($ch);

        $logDir = SAP_LOG_DIR . "/callBotProfiles";
        if (!file_exists($logDir)) {
            // اگر پوشه وجود ندارد، آن را ایجاد می‌کنیم
            mkdir($logDir, 0777, true);
        }
        file_put_contents($logDir . "/$botId.log", print_r([
            'curl' => curl_error($ch),
            'info' => curl_getinfo($ch),
            'request' => json_encode($data),
            'response' => $response
        ], true));
        if(curl_errno($ch)) {
            $this->flash->setFlash(curl_error($ch), 'error');
            return false;
        }
        curl_close($ch);

        return true;
    }

    public function getBotProfilesDataForPythonFromType($bot,$botProfiles): array
    {
        $pages = json_decode($bot->pages ?? json_encode([]),true);
        $data = [
            "group_name" => $bot->id,
            "active" => true,
            'pages' => $pages,
            'target' => $bot->target,
        ];
        if($bot->type === 'twitter') {
            $data['url'] = $bot->target;
        }
        $accounts = [];
        foreach ($botProfiles as $profile) {
            $accounts[] = $this->getBotProfilesAccountDataForPythonFromType($bot,$profile);
        }

        $data['accounts'] = $accounts;

        return $data;
    }

    private function getBotProfilesAccountDataForPythonFromType($bot,$botProfile): array
    {
        $botOptions = $bot->options ? json_decode($bot->options,true) : [];
        $meta = json_decode($botProfile->meta ?? json_encode([]),true);
        switch ($botProfile->type) {
            case 'telegram': {
                return [
                    'phone_number' => $botProfile->username,
                    'bot_personality' => array_merge($meta,[
                        'name' => $botProfile->name,
                        'password' => $botProfile->password,
                        'email' => $botProfile->email,
                        'email_password' => $botProfile->email_password,
                        'type' => $botProfile->type,
                        'gender' => $botProfile->gender,
                        'age' => $botProfile->age,
                        'country' => $botProfile->country,
                        'city' => $botProfile->city,
                        'image' => $botProfile->image ? SAP_IMG_URL . $botProfile->image : SAP_SITE_URL . '/assets/images/avatar.jpg',
                        'created_at' => $botProfile->created_at,
                    ]),
                ];
            }
            case 'twitter': {
                return [
                    'username' => $botProfile->username,
                    'likes' => @$botOptions['likes'],
                    'follows' => @$botOptions['follows'],
                    'comments' => @$botOptions['comments'],
                    'retweet' => @$botOptions['retweets'],
                    'retweet_pages' => @$botOptions['retweet_pages'],
                    'bot_personality' => array_merge($meta,[
                        'name' => $botProfile->name,
                        'password' => $botProfile->password,
                        'email' => $botProfile->email,
                        'email_password' => $botProfile->email_password,
                        'type' => $botProfile->type,
                        'gender' => $botProfile->gender,
                        'age' => $botProfile->age,
                        'country' => $botProfile->country,
                        'city' => $botProfile->city,
                        'image' => $botProfile->image ? SAP_IMG_URL . $botProfile->image : SAP_SITE_URL . '/assets/images/avatar.jpg',
                        'created_at' => $botProfile->created_at,
                    ]),
                ];
            }
            default: {
                return [];
            }
        }
    }

    private function getBotDataForPythonFromType($bot): array
    {
        $pages = json_decode($bot->pages ?? json_encode($bot->pages),true);
        $options = json_decode($bot->options ?? json_encode([]),true);
        switch ($bot->type) {
            case 'telegram_police': {
                return [
                    "group_name" => $bot->id,
                    "active" => true,
                    'pages' => $pages,
                    'target' => $bot->target,
                    "max_message_length" => @$options['max_message_length'],
                    "mute_in_minute" => @$options['mute_in_minute'],
                    "warning_message" => @$options['warning_message'],
                    "bad_words" => @$options['bad_words'],
                    "white_list_usernames" => @$options['white_list_usernames'],
                ];
            }
            default: {
                return [];
            }
        }
    }

    private function getBotPythonUrlFromType($bot): string
    {
        switch ($bot->type) {
            case 'telegram_police': {
                return 'http://65.21.168.172:8181/create-police/';
            }
            default: {
                return '';
            }
        }
    }

    private function getBotProfilePythonUrlFromType($bot): string
    {
        switch ($bot->type) {
            case 'telegram': {
                return 'http://65.21.168.172:8181/create-conversation/';
            }
            case 'twitter': {
                return 'http://65.21.168.172:8181/create-tweeter/';
            }
            default: {
                return '';
            }
        }
    }

}