<?php

class SAP_Calender
{
    private Common $common;
    private Sap_Database $db;

    public function __construct()
    {
        $this->db = new Sap_Database();
        $this->common = new Common();
    }

    public function index()
    {
        if(! sap_current_user_can('calender')) {
            $this->common->redirect('login');
        }

        include_once $this->common->get_template_path('Calender' . DS . 'index.php');
    }

    public function getQuickPosts(bool $grouped = false)
    {
        try {
            $userID = sap_get_current_user_id();
            $now = date('Y-m-d');

            $data = $this->db->get_results(
                "SELECT 
    qp.post_id AS quick_post_id,
    qp.user_id,
    qp.message,
    qp.share_link,
    qp.image,
    qp.video,
    qp.status,
    qp.created_date,
    JSON_ARRAYAGG(
        JSON_OBJECT('key', qpm.meta_key, 'value', qpm.meta_value)
    ) AS meta_data
FROM sap_quick_posts qp
LEFT JOIN sap_quick_postmeta qpm ON qp.post_id = qpm.post_id
WHERE qp.user_id = $userID
AND DATE(qp.created_date) = '$now'
GROUP BY qp.post_id");

            if(! $grouped) {
                return $data;
            }

            $groupedData = [];
            foreach ($data as $item) {
                $item->meta_data = json_decode($item->meta_data, true);
                $metaDataScheduleTime = array_values(array_filter($item->meta_data, function($item) {
                    return $item['key'] === 'sap_schedule_time';
                }));
                if(empty($metaDataScheduleTime)) {
                    continue;
                }
                $item->day_name = date('l',$metaDataScheduleTime[0]['value']);
                $groupedData[] = $item;
            }

            return $groupedData;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function socialIconByType(string $type): string
    {
        $socialIconPath = SAP_SITE_URL . '/assets/images/social-icon';
        switch (true) {
            case strpos($type,'twitter') !== false:
                return $socialIconPath . '/twitter.svg';
            case strpos($type,'telegram') !== false:
                return $socialIconPath . '/telegram.svg';
            case strpos($type,'instagram') !== false:
                return $socialIconPath . '/instagram.svg';
            case strpos($type,'facebook') !== false:
                return $socialIconPath . '/facebook.png';
            default: {
                return '';
            }
        }
    }
}