<?php

class SAP_CrawlerLogs
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
        $this->table = 'sap_crawler_logs';
    }

    public function index()
    {
        if(! sap_current_user_can('crawlers_logs')) {
            $this->common->redirect('login');
        }

        include_once($this->common->get_template_path('CrawlersLogs' . DS . 'index.php'));
    }

    public function getCrawlersLogs()
    {
        try {
            $user_id = sap_get_current_user_id();

            return $this->db->get_results("SELECT * FROM " . $this->table . " WHERE `user_id` = " . $user_id . " ORDER BY `created_at` DESC");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show()
    {
        if(! sap_current_user_can('crawlers_logs')) {
            $this->common->redirect('login');
        }

        include_once ($this->common->get_template_path('CrawlersLogs' . DS . 'show.php'));
    }

    public function getCrawlerLogById($id)
    {
        try {
            return $this->db->get_row("SELECT * FROM " . $this->table . " WHERE `id` = " . $id, true);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function add()
    {
        $headers = getallheaders();
        if(@$headers['TOKEN'] !== $this->token()) {
            REST([], 401);
        }

        try {
            $data = $this->getData();
        } catch (Throwable $exception) {
            REST($exception->validator->errors()->toArray(), 400);
        }

        if(! $this->save($data)) {
            REST([],500);
        }

        REST($data);
    }

    public function types(): array
    {
        return [
            'police' => $this->common->lang('crawler_type_police'),
            'signal' => $this->common->lang('crawler_type_signal'),
        ];
    }

    private function getData()
    {
        $crawlerId = @$_POST['crawler_id'];
        $data = [
            'crawler_type' => @$_POST['crawler_type'],
            'crawler_id' => $crawlerId,
            'user_message' => @$_POST['user_message'] ? json_encode($_POST['user_message']) : null,
            'crawler_message' => @$_POST['crawler_message'] ? json_encode($_POST['crawler_message']) : null,
        ];

        $factory = getValidator();
        $validatedData =  $factory->validate($data, [
            'crawler_id' => ['bail', 'required', 'integer'],
            'crawler_type' => ['required', 'string', 'in:police,signal' . implode(',', array_keys($this->types()))],
            'user_message' => ['required', 'string', 'max:65000'],
            'crawler_message' => ['required', 'string', 'max:65000'],
        ]);

        $userId = $this->db->get_row("SELECT * FROM " . "sap_crawlers" . " WHERE `id` = " . $crawlerId,true)->user_id;
        $validatedData['user_id'] = $userId;
        $validatedData['created_at'] = date('Y-m-d H:i:s');

        return $validatedData;
    }

    private function save(array $data)
    {
        try {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->db->insert($this->table,$data);
        } catch (Exception $exception) {
            REST([
                'error' => $exception->getMessage(),
            ],400);
        }
    }

    private function token(): string
    {
        return '1a673dfb-378e-415d-a35e-8699c3bea79f';
    }

}