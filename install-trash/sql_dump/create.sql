
DROP TABLE IF EXISTS `sap_logs`;
CREATE TABLE `sap_logs` (
  `id` int(11) NOT NULL,
  `user_id` bigint(11) NOT NULL,
  `social_source` longtext NOT NULL,
  `social_type` varchar(255) NOT NULL,
  `posting_type` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_options`;
CREATE TABLE `sap_options` (
  `option_id` bigint(20) NOT NULL,
  `option_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `option_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_plans`;
CREATE TABLE `sap_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT '',
  `stripe_product_id` varchar(255) DEFAULT '',
  `status` tinyint(20) DEFAULT NULL COMMENT ' 1 active / 0 inactive',
  `subscription_expiration_days` int(21) DEFAULT NULL,
  `networks` text NOT NULL,
  `networks_count` text NOT NULL,
  `created` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sap_postmeta`;
CREATE TABLE `sap_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_posts`;
CREATE TABLE `sap_posts` (
  `post_id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT '1',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `share_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` text COLLATE utf8_unicode_ci,
  `sent_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `parent` bigint(20) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `sap_posts` ADD INDEX( `title`);


DROP TABLE IF EXISTS `sap_quick_posts`;

CREATE TABLE `sap_quick_posts` (
  `post_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `share_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_quick_postmeta`;
CREATE TABLE `sap_quick_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_users`;
CREATE TABLE `sap_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `status` tinyint(2) NULL COMMENT ' 1 active / 0 inactive',
  `email_verification_tokan` longtext NULL ,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `forgot_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sap_user_settings`;
CREATE TABLE `sap_user_settings` (
  `setting_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `setting_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `autoload` varchar(255) NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `sap_zone`;
CREATE TABLE `sap_zone` (
  `zone_id` int(10) NOT NULL,
  `country_code` char(2) COLLATE utf8_bin NOT NULL,
  `zone_name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `sap_zone` (`zone_id`, `country_code`, `zone_name`) VALUES
(1, 'AD', 'Europe/Andorra'),
(2, 'AE', 'Asia/Dubai'),
(3, 'AF', 'Asia/Kabul'),
(4, 'AG', 'America/Antigua'),
(5, 'AI', 'America/Anguilla'),
(6, 'AL', 'Europe/Tirane'),
(7, 'AM', 'Asia/Yerevan'),
(8, 'AO', 'Africa/Luanda'),
(9, 'AQ', 'Antarctica/McMurdo'),
(10, 'AQ', 'Antarctica/Casey'),
(11, 'AQ', 'Antarctica/Davis'),
(12, 'AQ', 'Antarctica/DumontDUrville'),
(13, 'AQ', 'Antarctica/Mawson'),
(14, 'AQ', 'Antarctica/Palmer'),
(15, 'AQ', 'Antarctica/Rothera'),
(16, 'AQ', 'Antarctica/Syowa'),
(17, 'AQ', 'Antarctica/Troll'),
(18, 'AQ', 'Antarctica/Vostok'),
(19, 'AR', 'America/Argentina/Buenos_Aires'),
(20, 'AR', 'America/Argentina/Cordoba'),
(21, 'AR', 'America/Argentina/Salta'),
(22, 'AR', 'America/Argentina/Jujuy'),
(23, 'AR', 'America/Argentina/Tucuman'),
(24, 'AR', 'America/Argentina/Catamarca'),
(25, 'AR', 'America/Argentina/La_Rioja'),
(26, 'AR', 'America/Argentina/San_Juan'),
(27, 'AR', 'America/Argentina/Mendoza'),
(28, 'AR', 'America/Argentina/San_Luis'),
(29, 'AR', 'America/Argentina/Rio_Gallegos'),
(30, 'AR', 'America/Argentina/Ushuaia'),
(31, 'AS', 'Pacific/Pago_Pago'),
(32, 'AT', 'Europe/Vienna'),
(33, 'AU', 'Australia/Lord_Howe'),
(34, 'AU', 'Antarctica/Macquarie'),
(35, 'AU', 'Australia/Hobart'),
(36, 'AU', 'Australia/Currie'),
(37, 'AU', 'Australia/Melbourne'),
(38, 'AU', 'Australia/Sydney'),
(39, 'AU', 'Australia/Broken_Hill'),
(40, 'AU', 'Australia/Brisbane'),
(41, 'AU', 'Australia/Lindeman'),
(42, 'AU', 'Australia/Adelaide'),
(43, 'AU', 'Australia/Darwin'),
(44, 'AU', 'Australia/Perth'),
(45, 'AU', 'Australia/Eucla'),
(46, 'AW', 'America/Aruba'),
(47, 'AX', 'Europe/Mariehamn'),
(48, 'AZ', 'Asia/Baku'),
(49, 'BA', 'Europe/Sarajevo'),
(50, 'BB', 'America/Barbados'),
(51, 'BD', 'Asia/Dhaka'),
(52, 'BE', 'Europe/Brussels'),
(53, 'BF', 'Africa/Ouagadougou'),
(54, 'BG', 'Europe/Sofia'),
(55, 'BH', 'Asia/Bahrain'),
(56, 'BI', 'Africa/Bujumbura'),
(57, 'BJ', 'Africa/Porto-Novo'),
(58, 'BL', 'America/St_Barthelemy'),
(59, 'BM', 'Atlantic/Bermuda'),
(60, 'BN', 'Asia/Brunei'),
(61, 'BO', 'America/La_Paz'),
(62, 'BQ', 'America/Kralendijk'),
(63, 'BR', 'America/Noronha'),
(64, 'BR', 'America/Belem'),
(65, 'BR', 'America/Fortaleza'),
(66, 'BR', 'America/Recife'),
(67, 'BR', 'America/Araguaina'),
(68, 'BR', 'America/Maceio'),
(69, 'BR', 'America/Bahia'),
(70, 'BR', 'America/Sao_Paulo'),
(71, 'BR', 'America/Campo_Grande'),
(72, 'BR', 'America/Cuiaba'),
(73, 'BR', 'America/Santarem'),
(74, 'BR', 'America/Porto_Velho'),
(75, 'BR', 'America/Boa_Vista'),
(76, 'BR', 'America/Manaus'),
(77, 'BR', 'America/Eirunepe'),
(78, 'BR', 'America/Rio_Branco'),
(79, 'BS', 'America/Nassau'),
(80, 'BT', 'Asia/Thimphu'),
(81, 'BW', 'Africa/Gaborone'),
(82, 'BY', 'Europe/Minsk'),
(83, 'BZ', 'America/Belize'),
(84, 'CA', 'America/St_Johns'),
(85, 'CA', 'America/Halifax'),
(86, 'CA', 'America/Glace_Bay'),
(87, 'CA', 'America/Moncton'),
(88, 'CA', 'America/Goose_Bay'),
(89, 'CA', 'America/Blanc-Sablon'),
(90, 'CA', 'America/Toronto'),
(91, 'CA', 'America/Nipigon'),
(92, 'CA', 'America/Thunder_Bay'),
(93, 'CA', 'America/Iqaluit'),
(94, 'CA', 'America/Pangnirtung'),
(95, 'CA', 'America/Atikokan'),
(96, 'CA', 'America/Winnipeg'),
(97, 'CA', 'America/Rainy_River'),
(98, 'CA', 'America/Resolute'),
(99, 'CA', 'America/Rankin_Inlet'),
(100, 'CA', 'America/Regina'),
(101, 'CA', 'America/Swift_Current'),
(102, 'CA', 'America/Edmonton'),
(103, 'CA', 'America/Cambridge_Bay'),
(104, 'CA', 'America/Yellowknife'),
(105, 'CA', 'America/Inuvik'),
(106, 'CA', 'America/Creston'),
(107, 'CA', 'America/Dawson_Creek'),
(108, 'CA', 'America/Fort_Nelson'),
(109, 'CA', 'America/Vancouver'),
(110, 'CA', 'America/Whitehorse'),
(111, 'CA', 'America/Dawson'),
(112, 'CC', 'Indian/Cocos'),
(113, 'CD', 'Africa/Kinshasa'),
(114, 'CD', 'Africa/Lubumbashi'),
(115, 'CF', 'Africa/Bangui'),
(116, 'CG', 'Africa/Brazzaville'),
(117, 'CH', 'Europe/Zurich'),
(118, 'CI', 'Africa/Abidjan'),
(119, 'CK', 'Pacific/Rarotonga'),
(120, 'CL', 'America/Santiago'),
(121, 'CL', 'America/Punta_Arenas'),
(122, 'CL', 'Pacific/Easter'),
(123, 'CM', 'Africa/Douala'),
(124, 'CN', 'Asia/Shanghai'),
(125, 'CN', 'Asia/Urumqi'),
(126, 'CO', 'America/Bogota'),
(127, 'CR', 'America/Costa_Rica'),
(128, 'CU', 'America/Havana'),
(129, 'CV', 'Atlantic/Cape_Verde'),
(130, 'CW', 'America/Curacao'),
(131, 'CX', 'Indian/Christmas'),
(132, 'CY', 'Asia/Nicosia'),
(133, 'CY', 'Asia/Famagusta'),
(134, 'CZ', 'Europe/Prague'),
(135, 'DE', 'Europe/Berlin'),
(136, 'DE', 'Europe/Busingen'),
(137, 'DJ', 'Africa/Djibouti'),
(138, 'DK', 'Europe/Copenhagen'),
(139, 'DM', 'America/Dominica'),
(140, 'DO', 'America/Santo_Domingo'),
(141, 'DZ', 'Africa/Algiers'),
(142, 'EC', 'America/Guayaquil'),
(143, 'EC', 'Pacific/Galapagos'),
(144, 'EE', 'Europe/Tallinn'),
(145, 'EG', 'Africa/Cairo'),
(146, 'EH', 'Africa/El_Aaiun'),
(147, 'ER', 'Africa/Asmara'),
(148, 'ES', 'Europe/Madrid'),
(149, 'ES', 'Africa/Ceuta'),
(150, 'ES', 'Atlantic/Canary'),
(151, 'ET', 'Africa/Addis_Ababa'),
(152, 'FI', 'Europe/Helsinki'),
(153, 'FJ', 'Pacific/Fiji'),
(154, 'FK', 'Atlantic/Stanley'),
(155, 'FM', 'Pacific/Chuuk'),
(156, 'FM', 'Pacific/Pohnpei'),
(157, 'FM', 'Pacific/Kosrae'),
(158, 'FO', 'Atlantic/Faroe'),
(159, 'FR', 'Europe/Paris'),
(160, 'GA', 'Africa/Libreville'),
(161, 'GB', 'Europe/London'),
(162, 'GD', 'America/Grenada'),
(163, 'GE', 'Asia/Tbilisi'),
(164, 'GF', 'America/Cayenne'),
(165, 'GG', 'Europe/Guernsey'),
(166, 'GH', 'Africa/Accra'),
(167, 'GI', 'Europe/Gibraltar'),
(168, 'GL', 'America/Godthab'),
(169, 'GL', 'America/Danmarkshavn'),
(170, 'GL', 'America/Scoresbysund'),
(171, 'GL', 'America/Thule'),
(172, 'GM', 'Africa/Banjul'),
(173, 'GN', 'Africa/Conakry'),
(174, 'GP', 'America/Guadeloupe'),
(175, 'GQ', 'Africa/Malabo'),
(176, 'GR', 'Europe/Athens'),
(177, 'GS', 'Atlantic/South_Georgia'),
(178, 'GT', 'America/Guatemala'),
(179, 'GU', 'Pacific/Guam'),
(180, 'GW', 'Africa/Bissau'),
(181, 'GY', 'America/Guyana'),
(182, 'HK', 'Asia/Hong_Kong'),
(183, 'HN', 'America/Tegucigalpa'),
(184, 'HR', 'Europe/Zagreb'),
(185, 'HT', 'America/Port-au-Prince'),
(186, 'HU', 'Europe/Budapest'),
(187, 'ID', 'Asia/Jakarta'),
(188, 'ID', 'Asia/Pontianak'),
(189, 'ID', 'Asia/Makassar'),
(190, 'ID', 'Asia/Jayapura'),
(191, 'IE', 'Europe/Dublin'),
(192, 'IL', 'Asia/Jerusalem'),
(193, 'IM', 'Europe/Isle_of_Man'),
(194, 'IN', 'Asia/Kolkata'),
(195, 'IO', 'Indian/Chagos'),
(196, 'IQ', 'Asia/Baghdad'),
(197, 'IR', 'Asia/Tehran'),
(198, 'IS', 'Atlantic/Reykjavik'),
(199, 'IT', 'Europe/Rome'),
(200, 'JE', 'Europe/Jersey'),
(201, 'JM', 'America/Jamaica'),
(202, 'JO', 'Asia/Amman'),
(203, 'JP', 'Asia/Tokyo'),
(204, 'KE', 'Africa/Nairobi'),
(205, 'KG', 'Asia/Bishkek'),
(206, 'KH', 'Asia/Phnom_Penh'),
(207, 'KI', 'Pacific/Tarawa'),
(208, 'KI', 'Pacific/Enderbury'),
(209, 'KI', 'Pacific/Kiritimati'),
(210, 'KM', 'Indian/Comoro'),
(211, 'KN', 'America/St_Kitts'),
(212, 'KP', 'Asia/Pyongyang'),
(213, 'KR', 'Asia/Seoul'),
(214, 'KW', 'Asia/Kuwait'),
(215, 'KY', 'America/Cayman'),
(216, 'KZ', 'Asia/Almaty'),
(217, 'KZ', 'Asia/Qyzylorda'),
(218, 'KZ', 'Asia/Aqtobe'),
(219, 'KZ', 'Asia/Aqtau'),
(220, 'KZ', 'Asia/Atyrau'),
(221, 'KZ', 'Asia/Oral'),
(222, 'LA', 'Asia/Vientiane'),
(223, 'LB', 'Asia/Beirut'),
(224, 'LC', 'America/St_Lucia'),
(225, 'LI', 'Europe/Vaduz'),
(226, 'LK', 'Asia/Colombo'),
(227, 'LR', 'Africa/Monrovia'),
(228, 'LS', 'Africa/Maseru'),
(229, 'LT', 'Europe/Vilnius'),
(230, 'LU', 'Europe/Luxembourg'),
(231, 'LV', 'Europe/Riga'),
(232, 'LY', 'Africa/Tripoli'),
(233, 'MA', 'Africa/Casablanca'),
(234, 'MC', 'Europe/Monaco'),
(235, 'MD', 'Europe/Chisinau'),
(236, 'ME', 'Europe/Podgorica'),
(237, 'MF', 'America/Marigot'),
(238, 'MG', 'Indian/Antananarivo'),
(239, 'MH', 'Pacific/Majuro'),
(240, 'MH', 'Pacific/Kwajalein'),
(241, 'MK', 'Europe/Skopje'),
(242, 'ML', 'Africa/Bamako'),
(243, 'MM', 'Asia/Yangon'),
(244, 'MN', 'Asia/Ulaanbaatar'),
(245, 'MN', 'Asia/Hovd'),
(246, 'MN', 'Asia/Choibalsan'),
(247, 'MO', 'Asia/Macau'),
(248, 'MP', 'Pacific/Saipan'),
(249, 'MQ', 'America/Martinique'),
(250, 'MR', 'Africa/Nouakchott'),
(251, 'MS', 'America/Montserrat'),
(252, 'MT', 'Europe/Malta'),
(253, 'MU', 'Indian/Mauritius'),
(254, 'MV', 'Indian/Maldives'),
(255, 'MW', 'Africa/Blantyre'),
(256, 'MX', 'America/Mexico_City'),
(257, 'MX', 'America/Cancun'),
(258, 'MX', 'America/Merida'),
(259, 'MX', 'America/Monterrey'),
(260, 'MX', 'America/Matamoros'),
(261, 'MX', 'America/Mazatlan'),
(262, 'MX', 'America/Chihuahua'),
(263, 'MX', 'America/Ojinaga'),
(264, 'MX', 'America/Hermosillo'),
(265, 'MX', 'America/Tijuana'),
(266, 'MX', 'America/Bahia_Banderas'),
(267, 'MY', 'Asia/Kuala_Lumpur'),
(268, 'MY', 'Asia/Kuching'),
(269, 'MZ', 'Africa/Maputo'),
(270, 'NA', 'Africa/Windhoek'),
(271, 'NC', 'Pacific/Noumea'),
(272, 'NE', 'Africa/Niamey'),
(273, 'NF', 'Pacific/Norfolk'),
(274, 'NG', 'Africa/Lagos'),
(275, 'NI', 'America/Managua'),
(276, 'NL', 'Europe/Amsterdam'),
(277, 'NO', 'Europe/Oslo'),
(278, 'NP', 'Asia/Kathmandu'),
(279, 'NR', 'Pacific/Nauru'),
(280, 'NU', 'Pacific/Niue'),
(281, 'NZ', 'Pacific/Auckland'),
(282, 'NZ', 'Pacific/Chatham'),
(283, 'OM', 'Asia/Muscat'),
(284, 'PA', 'America/Panama'),
(285, 'PE', 'America/Lima'),
(286, 'PF', 'Pacific/Tahiti'),
(287, 'PF', 'Pacific/Marquesas'),
(288, 'PF', 'Pacific/Gambier'),
(289, 'PG', 'Pacific/Port_Moresby'),
(290, 'PG', 'Pacific/Bougainville'),
(291, 'PH', 'Asia/Manila'),
(292, 'PK', 'Asia/Karachi'),
(293, 'PL', 'Europe/Warsaw'),
(294, 'PM', 'America/Miquelon'),
(295, 'PN', 'Pacific/Pitcairn'),
(296, 'PR', 'America/Puerto_Rico'),
(297, 'PS', 'Asia/Gaza'),
(298, 'PS', 'Asia/Hebron'),
(299, 'PT', 'Europe/Lisbon'),
(300, 'PT', 'Atlantic/Madeira'),
(301, 'PT', 'Atlantic/Azores'),
(302, 'PW', 'Pacific/Palau'),
(303, 'PY', 'America/Asuncion'),
(304, 'QA', 'Asia/Qatar'),
(305, 'RE', 'Indian/Reunion'),
(306, 'RO', 'Europe/Bucharest'),
(307, 'RS', 'Europe/Belgrade'),
(308, 'RU', 'Europe/Kaliningrad'),
(309, 'RU', 'Europe/Moscow'),
(310, 'RU', 'Europe/Simferopol'),
(311, 'RU', 'Europe/Volgograd'),
(312, 'RU', 'Europe/Kirov'),
(313, 'RU', 'Europe/Astrakhan'),
(314, 'RU', 'Europe/Saratov'),
(315, 'RU', 'Europe/Ulyanovsk'),
(316, 'RU', 'Europe/Samara'),
(317, 'RU', 'Asia/Yekaterinburg'),
(318, 'RU', 'Asia/Omsk'),
(319, 'RU', 'Asia/Novosibirsk'),
(320, 'RU', 'Asia/Barnaul'),
(321, 'RU', 'Asia/Tomsk'),
(322, 'RU', 'Asia/Novokuznetsk'),
(323, 'RU', 'Asia/Krasnoyarsk'),
(324, 'RU', 'Asia/Irkutsk'),
(325, 'RU', 'Asia/Chita'),
(326, 'RU', 'Asia/Yakutsk'),
(327, 'RU', 'Asia/Khandyga'),
(328, 'RU', 'Asia/Vladivostok'),
(329, 'RU', 'Asia/Ust-Nera'),
(330, 'RU', 'Asia/Magadan'),
(331, 'RU', 'Asia/Sakhalin'),
(332, 'RU', 'Asia/Srednekolymsk'),
(333, 'RU', 'Asia/Kamchatka'),
(334, 'RU', 'Asia/Anadyr'),
(335, 'RW', 'Africa/Kigali'),
(336, 'SA', 'Asia/Riyadh'),
(337, 'SB', 'Pacific/Guadalcanal'),
(338, 'SC', 'Indian/Mahe'),
(339, 'SD', 'Africa/Khartoum'),
(340, 'SE', 'Europe/Stockholm'),
(341, 'SG', 'Asia/Singapore'),
(342, 'SH', 'Atlantic/St_Helena'),
(343, 'SI', 'Europe/Ljubljana'),
(344, 'SJ', 'Arctic/Longyearbyen'),
(345, 'SK', 'Europe/Bratislava'),
(346, 'SL', 'Africa/Freetown'),
(347, 'SM', 'Europe/San_Marino'),
(348, 'SN', 'Africa/Dakar'),
(349, 'SO', 'Africa/Mogadishu'),
(350, 'SR', 'America/Paramaribo'),
(351, 'SS', 'Africa/Juba'),
(352, 'ST', 'Africa/Sao_Tome'),
(353, 'SV', 'America/El_Salvador'),
(354, 'SX', 'America/Lower_Princes'),
(355, 'SY', 'Asia/Damascus'),
(356, 'SZ', 'Africa/Mbabane'),
(357, 'TC', 'America/Grand_Turk'),
(358, 'TD', 'Africa/Ndjamena'),
(359, 'TF', 'Indian/Kerguelen'),
(360, 'TG', 'Africa/Lome'),
(361, 'TH', 'Asia/Bangkok'),
(362, 'TJ', 'Asia/Dushanbe'),
(363, 'TK', 'Pacific/Fakaofo'),
(364, 'TL', 'Asia/Dili'),
(365, 'TM', 'Asia/Ashgabat'),
(366, 'TN', 'Africa/Tunis'),
(367, 'TO', 'Pacific/Tongatapu'),
(368, 'TR', 'Europe/Istanbul'),
(369, 'TT', 'America/Port_of_Spain'),
(370, 'TV', 'Pacific/Funafuti'),
(371, 'TW', 'Asia/Taipei'),
(372, 'TZ', 'Africa/Dar_es_Salaam'),
(373, 'UA', 'Europe/Kiev'),
(374, 'UA', 'Europe/Uzhgorod'),
(375, 'UA', 'Europe/Zaporozhye'),
(376, 'UG', 'Africa/Kampala'),
(377, 'UM', 'Pacific/Midway'),
(378, 'UM', 'Pacific/Wake'),
(379, 'US', 'America/New_York'),
(380, 'US', 'America/Detroit'),
(381, 'US', 'America/Kentucky/Louisville'),
(382, 'US', 'America/Kentucky/Monticello'),
(383, 'US', 'America/Indiana/Indianapolis'),
(384, 'US', 'America/Indiana/Vincennes'),
(385, 'US', 'America/Indiana/Winamac'),
(386, 'US', 'America/Indiana/Marengo'),
(387, 'US', 'America/Indiana/Petersburg'),
(388, 'US', 'America/Indiana/Vevay'),
(389, 'US', 'America/Chicago'),
(390, 'US', 'America/Indiana/Tell_City'),
(391, 'US', 'America/Indiana/Knox'),
(392, 'US', 'America/Menominee'),
(393, 'US', 'America/North_Dakota/Center'),
(394, 'US', 'America/North_Dakota/New_Salem'),
(395, 'US', 'America/North_Dakota/Beulah'),
(396, 'US', 'America/Denver'),
(397, 'US', 'America/Boise'),
(398, 'US', 'America/Phoenix'),
(399, 'US', 'America/Los_Angeles'),
(400, 'US', 'America/Anchorage'),
(401, 'US', 'America/Juneau'),
(402, 'US', 'America/Sitka'),
(403, 'US', 'America/Metlakatla'),
(404, 'US', 'America/Yakutat'),
(405, 'US', 'America/Nome'),
(406, 'US', 'America/Adak'),
(407, 'US', 'Pacific/Honolulu'),
(408, 'UY', 'America/Montevideo'),
(409, 'UZ', 'Asia/Samarkand'),
(410, 'UZ', 'Asia/Tashkent'),
(411, 'VA', 'Europe/Vatican'),
(412, 'VC', 'America/St_Vincent'),
(413, 'VE', 'America/Caracas'),
(414, 'VG', 'America/Tortola'),
(415, 'VI', 'America/St_Thomas'),
(416, 'VN', 'Asia/Ho_Chi_Minh'),
(417, 'VU', 'Pacific/Efate'),
(418, 'WF', 'Pacific/Wallis'),
(419, 'WS', 'Pacific/Apia'),
(420, 'YE', 'Asia/Aden'),
(421, 'YT', 'Indian/Mayotte'),
(422, 'ZA', 'Africa/Johannesburg'),
(423, 'ZM', 'Africa/Lusaka'),
(424, 'ZW', 'Africa/Harare');

--
-- Indexes for table `sap_logs`
--
ALTER TABLE `sap_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sap_options`
--
ALTER TABLE `sap_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `sap_postmeta`
--
ALTER TABLE `sap_postmeta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `sap_user_settings`
--
ALTER TABLE `sap_user_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- Indexes for table `sap_posts`
--
ALTER TABLE `sap_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `sap_quick_postmeta`
--
ALTER TABLE `sap_quick_postmeta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `sap_quick_posts`
--
ALTER TABLE `sap_quick_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `sap_users`
--
ALTER TABLE `sap_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sap_pans`
--
ALTER TABLE `sap_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zone`
--
ALTER TABLE `sap_zone`
  ADD PRIMARY KEY (`zone_id`),
  ADD KEY `idx_country_code` (`country_code`),
  ADD KEY `idx_zone_name` (`zone_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sap_logs`
--
ALTER TABLE `sap_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;
--
-- AUTO_INCREMENT for table `sap_options`
--
ALTER TABLE `sap_options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `sap_postmeta`
--
ALTER TABLE `sap_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1726;
--
-- AUTO_INCREMENT for table `sap_posts`
--
ALTER TABLE `sap_posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `sap_quick_postmeta`
--
ALTER TABLE `sap_quick_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT for table `sap_quick_posts`
--
ALTER TABLE `sap_quick_posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `sap_users`
--
ALTER TABLE `sap_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sap_plans`
--
ALTER TABLE `sap_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `sap_zone`
  MODIFY `zone_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;COMMIT;
--
-- Change Collation for table `sap_user_settings`
--
ALTER TABLE `sap_user_settings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

--
-- AUTO_INCREMENT for table `sap_user_settings`
--
ALTER TABLE `sap_user_settings`
  MODIFY `setting_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;
  
INSERT INTO `sap_users` (`first_name`, `last_name`, `email`, `password`,`role`,   `status`, `created`, `modified`, `token`, `forgot_time`) 
          VALUES('<USER_FIRST_NAME>', '<USER_LAST_NAME>', '<USER_EMAIL>', '<PASSWORD>','superadmin',   '1', '<CREATED>', '<MODIFIED>', '0e0f0decf1902d3ff3547a8a1a6d1aa0', '');

INSERT INTO `sap_options` (`option_name`, `option_value`) 
          VALUES( '<SAP_LICENSE_ACTIVATED>', '<FINAL_ACTIVATION_CODE>');

INSERT INTO `sap_options` (`option_name`, `option_value`) 
          VALUES( '<SAP_LICENSE_DATA>', '<LICENSE_DATA>');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('sap_version', '5.5.2', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('sap_new_sass', '2.0.0', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('renewal_email_subject', 'Subscription Renewal', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('renewal_email_content', '<h3>Hello {user_name},</h3>
                      <p>
            Your current subscription {membership_level}  has been renewed successfully for the subscription id: {subscription_id}. Your {plan_name} plan will be expire on {expiration_date}
            </p>  


                        <p>Thanks,
                        <br>The {sap_name} Team</p>', 'yes');


INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('cancelled_membership_email_subject', 'Your membership has been cancelled', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('cancelled_membership_email_content', '<h3>Hello {user_name},</h3>
                      <p>
            Your current subscription {membership_level}  has been cancelled. You will retain access until {expiration_date}
            </p>
                        <p>Thanks</p>', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('expired_membership_email_subject', 'Your membership has expired', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('expired_membership_email_content', '<h3>Hello {user_name},</h3>

Your current subscription {membership_level} has expired. 

To renew or upgrade the membership login to your profile and follow the suggested actions. 

Thanks', 'yes');


DROP TABLE IF EXISTS `sap_membership`;
CREATE TABLE `sap_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `membership_duration_days` int(11) DEFAULT NULL,
  `customer_id` varchar(255) NOT NULL,
  `gateway` varchar(255) DEFAULT NULL,
  `subscription_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,  
  `membership_status` tinyint(2) DEFAULT '0' COMMENT ' 0 pending / 1 active / 2 expired / 3 cancelled',
  `recurring` tinyint(2) NOT NULL  DEFAULT '0' COMMENT '1 yes / 0 no',
  `expiration_date` varchar(255) DEFAULT NULL,
  `renew_date` datetime DEFAULT NULL,
  `upgrade_date` datetime DEFAULT NULL,
  `cancellation_date` datetime DEFAULT NULL,
  `previous_plan` varchar(255) DEFAULT NULL,
  `networks`    TEXT  NOT NULL,
  `networks_count`    TEXT  NOT NULL,
  `membership_created_date` datetime DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `sap_membership` ADD INDEX( `user_id`); 
ALTER TABLE `sap_membership` ADD INDEX( `customer_name`);


DROP TABLE IF EXISTS `sap_payment_history`;
CREATE TABLE `sap_payment_history` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(25) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `networks`    TEXT  NOT NULL,
  `networks_count`    TEXT  NOT NULL,
  `expiration_date` varchar(255) DEFAULT NULL,
  `payment_date` datetime NOT NULL,
  `amount` double DEFAULT NULL,  
  `type` tinyint(2) NOT NULL  DEFAULT '0'  COMMENT '0 new / 1 renew/ 2 upgrade ',
  `gateway` varchar(255) DEFAULT NULL COMMENT 'stripe / paypal  / manual',  
  `payment_status` tinyint(2) NOT NULL DEFAULT '0'  COMMENT '0 Pending / 1 completed / 2 fail /3 Refunded',
  `transaction_id` varchar(255) DEFAULT NULL,
  `transaction_data` longtext DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sap_payment_history` ADD INDEX( `membership_id`); 
ALTER TABLE `sap_payment_history` ADD INDEX( `plan_id`);


INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('payment_gateway', 'manual', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('default_payment_method', 'manual', 'yes');
          
DROP TABLE IF EXISTS `sap_coupons`;
CREATE TABLE `sap_coupons` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `coupon_code` VARCHAR(100) NOT NULL ,
  `coupon_type` ENUM('fixed_discount','percentage_discount') NOT NULL ,
  `coupon_amount` INT NOT NULL ,
  `coupon_description` TEXT NOT NULL ,
  `coupon_expiry_date` DATETIME NOT NULL ,
  `coupon_status` ENUM('draft','publish','used') NOT NULL ,
  `created_date` DATETIME NOT NULL ,
  `modified_date` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `sap_coupons` CHANGE `modified_date` `modified_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `sap_coupons` CHANGE `coupon_expiry_date` `coupon_expiry_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `sap_coupons` CHANGE `coupon_description` `coupon_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `sap_coupons` CHANGE `coupon_amount` `coupon_amount` DOUBLE NOT NULL;

ALTER TABLE `sap_payment_history` ADD `coupon_id` INT NULL AFTER `payment_date`;
ALTER TABLE `sap_payment_history` ADD `coupon_name` VARCHAR(100) NULL AFTER `amount`, ADD `coupon_discount_amount` DOUBLE NULL AFTER `coupon_name`;
ALTER TABLE `sap_payment_history` ADD `currency` varchar(255) NOT NULL DEFAULT 'USD' AFTER `amount`;

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('sap_set_manual_upgrade_version', '1.0.0', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('sap_currencies', '{"USD":"$","AED":"\\u062f.\\u0625","ALL":"Lek","AMD":"\\u058f","ANG":"\\u0192","AUD":"$","AWG":"\\u0192","AZN":"\\u043c\\u0430\\u043d","BAM":"KM","BBD":"$","BDT":"\\u09f3","BGN":"\\u043b\\u0432","BMD":"$","BND":"$","BSD":"$","BWP":"P","BYN":"\\u0440\\u0443\\u0431.","BZD":"BZ$","CAD":"$","CDF":"FrCD","CHF":"CHF","CNY":"\\u00a5","CZK":"K\\u010d","DKK":"kr","DOP":"RD$","DZD":"DA","EGP":"\\u00a3","ETB":"Br","EUR":"\\u20ac","FJD":"$","GBP":"\\u00a3","GEL":"\\u10da","GIP":"\\u00a3","GMD":"D","GYD":"$","HKD":"$","HTG":"HTG","HUF":"Ft","IDR":"Rp","ILS":"\\u20aa","INR":"\\u20b9","ISK":"kr","JMD":"J$","KES":"Ksh","KGS":"\\u043b\\u0432","KHR":"\\u17db","KYD":"$","KZT":"\\u043b\\u0432","LBP":"\\u00a3","LKR":"\\u20a8","LRD":"$","LSL":"LSL","MAD":"MAD","MDL":"L","MKD":"\\u0434\\u0435\\u043d","MMK":"K","MNT":"\\u20ae","MOP":"MOP$","MVR":"Rf","MWK":"MK","MXN":"$","MYR":"RM","MZN":"MT","NAD":"$","NGN":"\\u20a6","NOK":"kr","NPR":"\\u20a8","NZD":"$","PGK":"K","PHP":"\\u20b1","PKR":"\\u20a8","PLN":"z\\u0142","QAR":"\\ufdfc","RON":"lei","RSD":"\\u0414\\u0438\\u043d","RUB":"\\u0440\\u0443\\u0431","SAR":"\\ufdfc","SBD":"$","SCR":"\\u20a8","SEK":"kr","SGD":"S$","SLE":"SLE","SOS":"S","SZL":"L","THB":"\\u0e3f","TJS":"\\u0405M","TOP":"T$","TRY":"\\u20ba","TTD":"TT$","TWD":"NT$","TZS":"TSh","UAH":"\\u20b4","UZS":"\\u043b\\u0432","WST":"WS$","XCD":"$","YER":"\\ufdfc","ZAR":"R","ZMW":"ZK"}', 'yes');

INSERT INTO `sap_options` (`option_name`, `option_value`, `autoload`) 
          VALUES('sap_selected_currency', 'INR', 'yes');



/* crawlers table */
DROP TABLE IF EXISTS `sap_crawlers`;
CREATE TABLE `sap_crawlers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `platform` varchar(100) NOT NULL,
  `listening_channel` varchar(255) NOT NULL,
  `automatic` tinyint(4) NOT NULL DEFAULT 0,
  `translation_language` varchar(50) NOT NULL,
  `replace_before` tinytext DEFAULT NULL,
  `replace_after` tinytext DEFAULT NULL,
  `delete_before` tinytext DEFAULT NULL,
  `delete_after` tinytext DEFAULT NULL,
  `create_image` tinyint(3) NOT NULL DEFAULT 0,
  `is_active` tinyint(3) NOT NULL DEFAULT 0,
  `translate_text` tinytext DEFAULT NULL,
  `hashtag_enabled` tinyint(3) NOT NULL DEFAULT 0,
  `status` enum('pending','sent') NOT NULL DEFAULT 'pending',
  `created_date` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sap_crawlers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sap_users` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ;


/* crawled_posts */
DROP TABLE IF EXISTS `sap_crawled_posts`;
CREATE TABLE `sap_crawled_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crawler_id` int(11) unsigned NOT NULL,
  `original_message` text NOT NULL,
  `new_message` text NOT NULL,
  `orginal_image` varchar(255) DEFAULT NULL,
  `new_image` varchar(255) DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  `token_count` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `crawler_id` (`crawler_id`),
  CONSTRAINT `sap_crawled_posts_ibfk_1` FOREIGN KEY (`crawler_id`) REFERENCES `sap_crawlers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* sap_crawler_logs */
DROP TABLE IF EXISTS `sap_crawler_logs`;
CREATE TABLE `sap_crawler_logs` (
                                     `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                     `user_id` int(11) NOT NULL,
                                     `crawler_id` int(11) UNSIGNED NOT NULL,
                                     `crawler_type` varchar(255) NOT NULL,
                                     `user_message` JSON NOT NULL,
                                     `crawler_message` JSON NOT NULL,
                                     `created_at` datetime NULL DEFAULT null ON UPDATE current_timestamp(),
                                     PRIMARY KEY (`id`),
                                     KEY `crawler_id` (`crawler_id`),
                                     CONSTRAINT `sap_crawler_logs_crawler_fk` FOREIGN KEY (`crawler_id`) REFERENCES `sap_crawlers` (`id`),
                                     CONSTRAINT `sap_crawler_logs_user_fk` FOREIGN KEY (`user_id`) REFERENCES `sap_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* sap_bots */
DROP TABLE IF EXISTS `sap_bots`;
CREATE TABLE `sap_bots` (
                                          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                          `user_id` int(11) NOT NULL,
                                          `type` varchar(255) NOT NULL,
                                          `target` varchar(255) NOT NULL,
                                          `pages` JSON NOT NULL,
                                          `options` JSON NULL,
                                          `created_at` datetime NULL DEFAULT null ON UPDATE current_timestamp(),
                                          PRIMARY KEY (`id`),
                                          KEY `user_id` (`user_id`),
                                          CONSTRAINT `sap_bots_user_fk` FOREIGN KEY (`user_id`) REFERENCES `sap_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/* sap_bots_profile */
DROP TABLE IF EXISTS `sap_bots_profiles`;
CREATE TABLE `sap_bots_profiles` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `bot_id` int(11) UNSIGNED NOT NULL,
    `name` varchar(255) NOT NULL,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `email_password` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `gender` varchar(50) NOT NULL,
    `age` int(11) NULL,
    `country` varchar(255) NULL,
    `city` varchar(255) NULL,
    `image` varchar(255) NULL,
    `meta` JSON NULL,
    `created_at` datetime NULL DEFAULT null ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `bot_id` (`bot_id`),
    KEY `username` (`username`),
    KEY `email` (`email`),
    CONSTRAINT `sap_bots_profiles_bot_fk` FOREIGN KEY (`bot_id`) REFERENCES `sap_bots` (`id`),
    CONSTRAINT `sap_bots_profiles_user_fk` FOREIGN KEY (`user_id`) REFERENCES `sap_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* EX/Crawlers */
ALTER TABLE sap_crawlers
ADD COLUMN `networks` TEXT DEFAULT NULL AFTER `listening_channel`,
ADD COLUMN `validation_text` TINYTEXT DEFAULT NULL AFTER `translate_text`,
ADD COLUMN `watermark` TINYTEXT DEFAULT NULL AFTER `hashtag_enabled`,
ADD COLUMN `watermark_pos` ENUM('top', 'left', 'right', 'bottom') NOT NULL DEFAULT 'top' AFTER `watermark`;

ALTER TABLE `sap_crawlers`
CHANGE `watermark_pos` `watermark_pos` varchar(20) COLLATE 'utf8mb4_general_ci' NULL DEFAULT '' AFTER `watermark`;

/* Ex/cpg */
ALTER TABLE `sap_crawled_posts`
ADD `original_subject` varchar(255) NULL AFTER `crawler_id`,
ADD `new_subject` varchar(255) NULL AFTER `original_subject`,
ADD `link` varchar(1024) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `new_image`,
ADD `network` mediumtext NOT NULL AFTER `send_at`,
CHANGE `created_at` `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP AFTER `token_count`;
ALTER TABLE `sap_crawled_posts`
CHANGE `orginal_image` `orginal_image` varchar(1024) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `new_message`,
CHANGE `new_image` `new_image` varchar(1024) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `orginal_image`,
CHANGE `created_at` `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP AFTER `token_count`;


/* EX/crawler */
ALTER TABLE `sap_crawlers`
ADD `create_image_no` tinyint(3) NOT NULL DEFAULT '0' AFTER `create_image`;

/* EX/crawler */
ALTER TABLE `sap_crawlers`
CHANGE `status` `status` varchar(50) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT 'pending' AFTER `watermark_pos`;

UPDATE `sap_crawlers` SET `status` = 'active' WHERE `status` = 'sent';

/* FT/telegram */
ALTER TABLE `sap_logs`
CHANGE `social_source` `social_source` longtext COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `user_id`,
CHANGE `social_type` `social_type` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `social_source`,
CHANGE `created` `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `status`,
COLLATE 'utf8mb4_general_ci';

/* FT/tags,video_link */
ALTER TABLE `sap_crawled_posts`
    ADD `video_link` varchar(255) NULL AFTER `link`,
    ADD `tags` JSON NULL AFTER `video_link`;

/* sap_bots_logs */
DROP TABLE IF EXISTS `sap_bots_logs`;
CREATE TABLE `sap_bots_logs` (
                            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `bot_id` int(11) UNSIGNED NOT NULL,
                            `data_key` varchar(255) NOT NULL,
                            `data_json` JSON NOT NULL,
                            `created_at` datetime NULL DEFAULT null ON UPDATE current_timestamp(),
                            PRIMARY KEY (`id`),
                            KEY `bot_id` (`bot_id`),
                            CONSTRAINT `sap_bots_logs_bot_id_fk` FOREIGN KEY (`bot_id`) REFERENCES `sap_bots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* sap_crawled_posts */
ALTER TABLE `sap_crawled_posts`
    ADD COLUMN media JSON DEFAULT NULL AFTER new_image;

/* sap_quick_posts */
ALTER TABLE `sap_quick_posts`
    ADD COLUMN media JSON DEFAULT NULL AFTER video;

/* sap_quick_posts */
ALTER TABLE `sap_crawled_posts`
    ADD COLUMN data_json JSON DEFAULT NULL AFTER token_count;

/* sap_crawlers */
ALTER TABLE `sap_crawlers`
    MODIFY `translate_text` TEXT;

ALTER TABLE `sap_crawled_posts`
    MODIFY `new_subject` TEXT;