<?php



class SAP_Rest
{
    public $common, $db;
    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
    }

    public function index()
    {
        if (sap_current_user_can('plans')) {
            $this->common->redirect('login');
        }
        echo json_encode(['a' => 10]);
    }
}