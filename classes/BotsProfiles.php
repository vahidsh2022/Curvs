<?php

class SAP_BotsProfiles
{
    private Sap_Database $db;
    private Common $common;
    private Flash $flash;
    private string $table;
    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
        $this->flash = new Flash();
        $this->table = 'sap_bots_profiles';
    }

    public function getBot()
    {
        global $match;
        return $this->db->get_row('SELECT * FROM ' . 'sap_bots' . ' WHERE `id` = ' . $match['params']['bot_id'] . ' AND `user_id` = ' . sap_get_current_user_id(),true);
    }

    public function index()
    {
        if(! sap_current_user_can('bots')) {
            $this->common->redirect('login');
        }

        include_once ($this->common->get_template_path('BotsProfiles' . DS . 'index.php'));
    }

    public function getBotsProfiles(bool $grouped = false)
    {
        try {
            $userId = sap_get_current_user_id();

            $data = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE `user_id` = " . $userId . " ORDER BY created_at DESC");
            if(! $grouped) {
                global $match;
                $botId = $match['params']['bot_id'];

                return array_values(array_filter($data, function($row) use ($botId) {
                    return $row->bot_id == $botId;
                }));
            }

            $groupedData = [];

            foreach ($data as $row) {
                $groupedData[$row->bot_id][] = $row;
            }

            return $groupedData;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getBotProfileId($id)
    {
        try {

            $query = "SELECT * FROM " . $this->table . " WHERE `id` = " . $id;

            $headers = getallheaders();
            if($headers['TOKEN'] !== $this->token()) {
                $userId = sap_get_current_user_id();
                $query .= " AND `user_id` = " . $userId;
            }
            return $this->db->get_row($query,true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addOrEdit()
    {
        if(! sap_current_user_can('bots')) {
            $this->common->redirect('login');
        }

        if($this->getBot()->user_id != sap_get_current_user_id()) {
            $this->flash->setFlash(lang('unknown_error'),'error');
            $this->common->redirect('bots');
        }

        include_once ($this->common->get_template_path('BotsProfiles' . DS . 'add_or_edit.php'));
    }

    public function storeOrUpdate()
    {
        try {
            $multiData = $this->storeValidation();
        } catch (Exception $exception) {
            $errors = '';
            foreach ($exception->validator->errors()->all() as $error) {
                $errors .= $error . '<br>';
            }
            $this->flash->setFlash($errors,'error');
            $this->common->redirect('bots');
            return false;
        }

        if(empty($multiData)) {
            $this->flash->setFlash(lang("fill_at_least_one_social_account"),'error');
            $this->common->redirect('bots');
        }

        try {
            foreach ($multiData as $data) {

                $type = $data['type'];
                $profileId = @$data['profile_id'];
                unset($data['profile_id']);

                if($profileId) {

                    $botProfile = $this->getBotProfileId($profileId);
                    $newMeta = json_decode($data['meta'], true);

                    $oldMeta = json_decode($botProfile->meta, true);
                    $data['meta'] = array_merge($oldMeta,$newMeta);
                    $data['meta'] = json_encode($data['meta']);

                    $data = $this->db->escape($data);
                    if($this->db->update($this->table, $data,['id' => $profileId])) {
                        $this->flash->setFlash(lang('bots_profiles_updated').  ' ' . $type,'success');
                    } else {
                        $this->flash->setFlash(lang('bots_profiles_failed_social_account') . ' ' . $type,'error');
                    }
                } else {
                    $data = $this->db->escape($data);
                    if($this->db->insert($this->table, $data)) {
                        $this->flash->setFlash(lang('bots_profiles_stored').  ' ' . $type,'success');
                    } else {
                        $this->flash->setFlash(lang('bots_profiles_failed_social_account') . ' ' . $type,'error');
                    }
                }
            }

            $this->common->redirect('bots_profiles');
            return true;
        } catch (Exception $exception) {
            $this->flash->setFlash($exception->getMessage(),'error');
            $this->flash->setFlash(lang('unknown_error'),'error');
            $this->common->redirect('bots');
            return false;
        }
    }

    public function storeValidation(): array
    {
        $validator = getValidator();

        $data = [
            'name' => $_POST['name'],
            'gender' => $_POST['gender'],
            'age' => $_POST['age'] ?? 0,
            'country' => $_POST['country'],
            'city' => $_POST['city'],
            'meta' => $_POST['meta'],
        ];

         $validator->validate($data,[
            'name' => ['required'],
            'gender' => ['nullable'],
            'age' => ['nullable'],
            'country' => ['nullable'],
            'city' => ['nullable'],
            'meta' => ['nullable','array'],
            'meta.*' => ['nullable','string'],
            'new_data.*' => ['nullable','array'],
            'new_data.*.type' => ['required','string'],
            'new_data.*.username' => ['required','string'],
            'new_data.*.password' => ['required','string'],
            'new_data.*.email' => ['required','string'],
            'new_data.*.email_password' => ['required','string'],
            'exists_data.*' => ['nullable','array'],
            'exists_data.*.type' => ['required','string'],
            'exists_data.*.username' => ['required','string'],
            'exists_data.*.password' => ['required','string'],
            'exists_data.*.email' => ['required','string'],
            'exists_data.*.email_password' => ['required','string'],
        ],[
            'name.required' => 'Name is required',
         ]);

        $data['meta'] = json_encode($data['meta'] ?? []);
        $data['user_id'] = sap_get_current_user_id();
        $data['bot_id'] = $this->getBot()->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        if (!empty($_FILES['image']['name'])) {
            $fileUpload = new FileUploader(array());
            $uploadPath = $fileUpload->uploadFile('image');
            $data['image'] = $uploadPath;
        }

        // credentials data
        $newData = $_POST['new_data'] ?? [];
        $newDataItems = [];
        foreach ($newData as $index => $values) {
           $newDataItems[] = array_merge([
               'username' => $values['username'],
               'password' => $values['password'],
               'email' => $values['email'],
               'email_password' => $values['email_password'],
               'type' => $values['type']
           ],$data);
        }

        // credentials data which already exists
        $existsData = $_POST['exists_data'] ?? [];
        $existsDataItems = [];
        foreach ($existsData as $profileId => $values) {
            $existsDataItems[] = array_merge([
                'profile_id' => $profileId,
                'username' => $values['username'],
                'password' => $values['password'],
                'email' => $values['email'],
                'email_password' => $values['email_password'],
                'type' => $values['type']
            ],$data);
        }

        $finalData = array_merge($existsDataItems,$newDataItems);

        return array_filter($finalData,fn($item) => !empty($item['username']) && !empty($item['password']) && !empty($item['email']) && !empty($item['email_password']) && !empty($item['type']));
    }


    public function toJson()
    {
       try {
           global $match;
           $id =  $match['params']['id'];

           $botProfile = $this->getBotProfileId($id);
           REST([
               'user_id' => $botProfile->user_id,
               'bot_id' => $botProfile->bot_id,
               'username' => $botProfile->username,
               'name' => $botProfile->name,
               'gender' => $botProfile->gender,
               'age' => $botProfile->age,
               'country' => $botProfile->country,
               'city' => $botProfile->city,
               'image' => SAP_IMG_URL . $botProfile->image,
               'meta' => json_decode($botProfile->meta,true),
           ]);
       } catch (Exception $exception) {
           REST($exception->getMessage(),400);
       }
    }

    public function token(): string
    {
        return '7689c9a0-5567-4e77-829a-17fed7336852';
    }

    public function socialIconByType(string $type): string
    {
        $socialIconPath = SAP_SITE_URL . '/assets/images/social-icon';
        switch ($type) {
            case 'twitter':
                return $socialIconPath . '/twitter.svg';
            case 'telegram':
                return $socialIconPath . '/telegram.svg';
            case 'instagram':
                return $socialIconPath . '/instagram.svg';
            default: {
                return 'something';
            }
        }
    }

    public function genders(): array
    {
        return [
            'neutral' => 'Neutral',
            'male' => 'Male',
            'female' => 'Female',
        ];
    }

    public function formalities(): array
    {
        return [
            'formal' => 'Formal',
            'informal' => 'Informal',
        ];
    }

    public function tones(): array
    {
        return [
            'friendly' => 'Friendly',
            'humorous' => 'Humorous',
            'serious' => 'Serious',
            'sarcastic' => 'Sarcastic',
            'enthusiastic' => 'Enthusiastic',
            'empathetic' => 'Empathetic',
        ];
    }

    public function verbosities(): array
    {
        return [
            'concise' => 'Concise',
            'verbose' => 'Verbose',
        ];
    }

    public function vocabularies(): array
    {
        return [
            'simple' => 'Simple',
            'complex' => 'Complex',
        ];
    }

    public function pacings(): array
    {
        return [
            'fast' => 'Fast',
            'slow' => 'Slow',
        ];
    }

    public function useOfEmojis(): array
    {
        return [
            'none' => 'None',
            'minimal' => 'Minimal',
            'moderate' => 'Moderate',
            'frequent' => 'Frequent'
        ];
    }

    public function knowledgeDepths(): array
    {
        return [
            'superficial' => 'Superficial',
            'in_depth' => 'in-depth',
        ];
    }

    public function informationSources(): array
    {
        return [
            'internal' => 'Internal',
            'internet' => 'Internet',
            'user' => 'User',

        ];
    }

    public function proactivenesses(): array
    {
        return [
            'true' => 'True',
            'false' => 'False',
        ];
    }

    public function helpfulnesses(): array
    {
        return [
            'true' => 'True',
            'false' => 'False',
        ];
    }

    public function adaptabilities(): array
    {
        return [
            'true' => 'True',
            'false' => 'False',
        ];
    }

    public function networkTypes(): array
    {
        return [
            'telegram' => 'Telegram',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter',
        ];
    }
}