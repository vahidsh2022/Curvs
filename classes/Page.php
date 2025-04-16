<?php



class SAP_Page
{
    public $common, $db, $flash;
    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
        $this->flash = new Flash();
    }

    public function index()
    {
        ///> check permission in orginal code style.
        /// superadmin
        // if (sap_current_user_can('page')) {
        //     $this->common->redirect('login');
        // }
        ///> user
        if (!sap_current_user_can('page')) {
            $this->common->redirect('login');
        }

        $this->flash->setFlash($this->common->lang('simple_page_welcome'), 'success');


        include_once($this->common->get_template_path('Page' . DS . 'index.php'));
    }
}