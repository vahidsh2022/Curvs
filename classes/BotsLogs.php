<?php

class SAP_BotsLogs
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
        $this->table = 'sap_bots_logs';
    }

    public function index()
    {
        if(! sap_current_user_can('bots_logs')) {
            $this->common->redirect('login');
            return false;
        }
        include_once ($this->common->get_template_path('BotsLogs' . DS . 'index.php'));
    }

    public function getBotsLogs($userId = null)
    {
        try {
            $userId = $userId ?? sap_get_current_user_id();

            $bots = $this->db->get_results("SELECT id,type FROM " . 'sap_bots' . " WHERE `user_id` = " . $userId . " ORDER BY `created_at` DESC");
            $botsIds = array_map(function ($bot) {
                return $bot->id;
            },$bots);
            $botLogs = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE `bot_id` IN (" . implode(',', $botsIds) . ") ORDER BY `created_at` DESC");
            foreach ($botLogs as $botLog) {
                $currentBotIndex = array_search($botLog->bot_id, $botsIds);
                $currentBot = $bots[$currentBotIndex];
                $botLog->bot_type = $currentBot->type;
            }

            return $botLogs;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function add()
    {
        try {
            $validatedData = $this->getData();
        } catch (\Throwable $exception) {
            if(isset($exception->validator)) {
                REST($exception->validator->errors()->toArray(), 400);
            } else {
                REST($exception->getMessage(), 400);
            }
            return false;
        }

        $data = $validatedData;
        $data['data_key'] = $data['identifier'];
        unset($data['identifier']);

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['data_json'] = [];

        $valuesKey = [
            'message',
            'group',
            'like',
            'follow',
            'retweet',
            'comment',
            'page',
            'reply', // is array and contains some keys ( check getData() )
        ];

        foreach ($valuesKey as $valueKey) {
            if(isset($data[$valueKey])) {
                $data['data_json'][$valueKey] = $data[$valueKey] ?: null;

                unset($data[$valueKey]);
            }
        }

        if(! $this->db->exists('sap_bots','id',[
            'id' => $data['bot_id'],
        ])) {
//            $botId = $data['bot_id'];
//            $bot = $this->db->get_row("SELECT * FROM sap_bots WHERE JSON_CONTAINS(pages, '\"$botId\"')",true);
//            if(empty($bot)) {
                REST([
                    'error' => "Bot not found",
                ]);
//            }
//            $data['bot_id'] = $bot->id;
        }
        $data['data_json'] = array_filter($data['data_json']);
        $data['data_json'] = json_encode($data['data_json']);

        if(! $this->save($data)) {
            REST([],500);
        }

        $logDir = SAP_LOG_DIR . "/callBotLog";
        if (!file_exists($logDir)) {
            // اگر پوشه وجود ندارد، آن را ایجاد می‌کنیم
            mkdir($logDir, 0777, true);
        }
        $botLogId = $this->db->lastid();
        file_put_contents($logDir . "/$botLogId.log", print_r([
            'validated_data' => json_encode($validatedData),
            'request' => file_get_contents('php://input'),
            'response' => json_encode($data)
        ], true));

        $botLog = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE `id` = " . $botLogId,true);
        REST($botLog);
    }

    private function getData()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(! is_array($data)) {
            $data = $_POST;
        }
        if(! is_array($data)) {
            $data = [];
        }
        $factory = getValidator();
        return $factory->validate($data, [
            'bot_id' => ['bail', 'required'],
            'identifier' => ['required','string'],
            'message' => ['nullable','string'],
            'group' => ['nullable','string'],
            'like' => ['nullable','boolean'],
            'follow' => ['nullable','boolean'],
            'retweet' => ['nullable','boolean'],
            'comment' => ['nullable','boolean'],
            'page' => ['nullable','string'],
            'reply' => ['nullable', 'array'],
            'reply.identifier' => ['nullable', 'string'],
            'reply.message' => ['nullable', 'string'],
        ], [
            'bot_id' => lang('bots_logs_bot_id_vldtn'),
            'identifier' => lang('bots_logs_identifier_vldtn'),
            'message' => lang('bots_logs_message_vldtn'),
            'group' => lang('bots_logs_group_vldtn'),
            'like' => lang('bots_logs_like_vldtn'),
            'follow' => lang('bots_logs_follow_vldtn'),
            'retweet' => lang('bots_logs_retweet_vldtn'),
            'comment' => lang('bots_logs_comment_vldtn'),
            'reply' => lang('bots_logs_reply_vldtn'),
            'reply.identifier' => lang('bots_logs_reply_identifier_vldtn'),
            'reply.message' => lang('bots_logs_reply_message_vldtn'),
        ]);
    }

    public function save($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function show()
    {
        if(! sap_current_user_can('bots_logs')) {
            $this->common->redirect('login');
            return false;
        }
        include_once ($this->common->get_template_path('BotsLogs' . DS . 'show.php'));
    }

    public function getBotLogById($id = null)
    {
        global $match;
        $botLogId = $match['params']['id'];

        $id = $id ?? $botLogId;

        return $this->db->get_row("SELECT * FROM " . $this->table . " WHERE `id` = " . $id,true);
    }

    public function getBotByBotId($botId)
    {
        return $this->db->get_row("SELECT * FROM " . 'sap_bots' . " WHERE `id` = " . $botId,true);
    }
}