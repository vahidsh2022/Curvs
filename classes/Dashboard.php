<?php

class SAP_Dashboard
{
    private Common $common;
    private SAP_Settings $settings;
    private SAP_Crawlers $crawlers;
    private SAP_Bots $bots;
    private SAP_Quick_Posts $quickPosts;
    private SAP_CPG $CPG;

    private $db;
    private $flash;

    public function __construct()
    {
        global $sap_db_connect;
		$this->flash = new Flash();

        $this->db = $sap_db_connect;
        $this->common = new Common();
        if (!class_exists('SAP_Crawlers')) {
            require_once(CLASS_PATH . 'Settings.php');
        }
        $this->settings = new SAP_Settings();

        if (!class_exists('SAP_Crawlers')) {
            require_once(CLASS_PATH . 'Crawlers.php');
        }
        $this->crawlers = new SAP_Crawlers();

        if (!class_exists('SAP_Bots')) {
            require_once(CLASS_PATH . 'Bots.php');
        }
        $this->bots = new SAP_Bots();


        if (!class_exists('SAP_Quick_Posts')) {
            require_once(CLASS_PATH . 'Quick_Posts.php');
        }
        $this->quickPosts = new SAP_Quick_Posts();

        if (!class_exists('SAP_CPG')) {
            require_once(CLASS_PATH . 'CPG.php');
        }
        $this->CPG = new SAP_CPG();
    }

    public function index()
    {
        if (!sap_current_user_can('dashboard')) {
            $this->common->redirect('login');
            return false;
        }

        include_once $this->common->get_template_path('Dashboard' . DS . 'index.php');
    }
    public function admin()
    {
        if (sap_current_user_can('admin')) {
            $this->common->redirect('login');
            return false;
        }

        include_once $this->common->get_template_path('Dashboard' . DS . 'admin.php');
    }

    protected function getAdminDashboardUserData()
    {
        return $this->db->get_results("SELECT 
                COUNT(*) AS total,
                COUNT(CASE WHEN created >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01') 
                    AND created < DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') THEN 1 END) AS last_month,
                COUNT(CASE WHEN DATE(created) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday
            FROM sap_users
            WHERE role = 'user';
            ")[0];
    }

    protected function getAdminDashboardPostsData($status)
    {
        $counts = [
            'total' => 0,
            'last_month' => 0,
            'yesterday' => 0,
        ];

        // Total
        $q = "SELECT COUNT(*) as total FROM sap_quick_posts WHERE status = $status";
        $res = $this->db->get_results($q, true);
        $counts['total'] = $res[0]['total'];

        // Last month
        $q = "SELECT COUNT(*) as total FROM sap_quick_posts WHERE status = $status AND created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        $res = $this->db->get_results($q, true);
        $counts['last_month'] = $res[0]['total'];

        // Yesterday
        $q = "SELECT COUNT(*) as total FROM sap_quick_posts WHERE status = $status AND DATE(created_date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        $res = $this->db->get_results($q, true);
        $counts['yesterday'] = $res[0]['total'];

        return $counts;
    }

    protected function getAdminDashboardCrawlersData()
    {
        return $this->db->get_results("SELECT 
                COUNT(*) AS total,
                COUNT(CASE WHEN created_date >= CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday,
                COUNT(CASE WHEN created_date >= CURDATE() - INTERVAL 1 MONTH THEN 1 END) AS last_month
            FROM 
                sap_crawlers
            ")[0];
    }
    protected function getAdminDashboardCpgData()
    {
        return $this->db->get_results("SELECT 
                COUNT(*) AS total,
                COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 1 MONTH THEN 1 END) AS last_month,
                COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday
            FROM 
                sap_crawled_posts
            ")[0];
    }


    public function socialAccounts()
    {
        $accounts = [];
        foreach ($this->networkTypes() as $networkType) {
            $accounts[$networkType] = $this->settings->get_user_setting("sap_{$networkType}_options");
        }

        return $accounts;
    }

    public function networkTypes()
    {
        return [
            'telegram',
            'twitter',
        ];
    }

    public function socialIconByType(string $type): string
    {
        $socialIconPath = SAP_SITE_URL . '/assets/images/social-icon';
        switch ($type) {
            case 'twitter':
                return $socialIconPath . '/twitter.png';
            case 'telegram':
                return $socialIconPath . '/telegram.svg';
            case 'instagram':
                return $socialIconPath . '/instagram.svg';
            default: {
                return 'notfound';
            }
        }
    }

    public function getCrawlers()
    {
        $crawlers = [];
        foreach ($this->crawlers->getCrawlers() as $crawler) {
            $crawlers[$crawler->platform][] = $crawler;
        }

        return $crawlers;
    }

    public function getCpgs()
    {
        return $this->CPG->getCPGsBelongsToUser();
    }

    public function socialIconByPlatform(string $platform): string
    {
        $socialIconPath = SAP_SITE_URL . '/assets/images/social-icon';
        switch ($platform) {
            case 'web':
                return $socialIconPath . '/web.png';
            case 'telegram':
                return $socialIconPath . '/telegram.svg';
            default: {
                return 'notfound';
            }
        }
    }

    public function getBots()
    {
        $bots = [];

        foreach ($this->bots->getBots() as $bot) {
            $bots[$bot->type][] = $bot;
        }

        return $bots;
    }

    public function getQuickPosts()
    {
        $posts = $this->quickPosts->get_posts_by_status(2);
        if (count($posts) < 10) {
            foreach ($this->quickPosts->get_posts_by_status(1) as $publishedPost) {
                $posts[] = $publishedPost;
            }
        }

        return $posts;
    }



    public function imageLoader($source = '')
    {
        return is_string($source) ? $source : '';
    }

    public function messageLoader(string $message = '')
    {
        if (strlen($message) > 100) {
            return substr($message, 0, 100) . '....';
        }

        return $message;
    }

    public function limitPosts(array $data, int $limit = 10)
    {
        return array_slice($data, 0, $limit);
    }
}