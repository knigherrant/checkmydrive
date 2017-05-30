-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2017 at 12:52 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `checkmydrive`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`, `userid`) VALUES
('1eb421frq8ohg0pqpamhqhsru2a4qj89', '0', '', 1496136510, '', 81);

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `configs` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `configs`) VALUES
(1, '{"admin_email":"81","logo":"images\\/logos.png","company":"Aloud Media Limited","contact":"Marc Nixon","address":"45 Dawson Street","city":"Dublin","phone":"35316877157","default_terms":"Thank you for your business. We do expect payment within {due_date}, so please process this invoice within that time.","date_format":"d\\/m\\/Y","subscription":"10","sitename":"Checkmydrive","mailfrom":"email@clientrolapp.com","fromname":"Clientol","allow_registration":"1","activation":"0","login_by_email":"1","forum":"https:\\/\\/www.google.com.vn","isMail":"smtp","smtp_secure":"ssl","smtp_host":"smtp.gmail.com","smtp_user":"dev.joomlavi@gmail.com","smtp_pass":"joomlavi123","smtp_port":"465","public_key":"6LegdSkTAAAAADYA7FN9VPfJoNhyGYYZU9FDafS5","private_key":"6LegdSkTAAAAABzbDQKdPLTkYlQ1iEPqj3kjaWaB","paypal_sandbox":"1","paypal_account":"accounts@aloud.ie","paypal_lang_code":"US"}');

-- --------------------------------------------------------

--
-- Table structure for table `lang_codes`
--

CREATE TABLE `lang_codes` (
  `id` int(11) NOT NULL,
  `country` varchar(255) CHARACTER SET latin1 NOT NULL,
  `code` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lang_codes`
--

INSERT INTO `lang_codes` (`id`, `country`, `code`) VALUES
(1, 'ÅLAND ISLANDS', 'AX'),
(2, 'ALBANIA', 'AL'),
(3, 'ALGERIA', 'DZ'),
(4, 'AMERICAN SAMOA', 'AS'),
(5, 'ANDORRA', 'AD'),
(6, 'ANGUILLA', 'AI'),
(7, 'ANTARCTICA', 'AQ'),
(8, 'ANTIGUA AND BARBUDA', 'AG'),
(9, 'ARGENTINA', 'AR'),
(10, 'ARMENIA', 'AM'),
(11, 'ARUBA', 'AW'),
(12, 'AUSTRALIA', 'AU'),
(13, 'AUSTRIA', 'AT'),
(14, 'AZERBAIJAN', 'AZ'),
(15, 'BAHAMAS', 'BS'),
(16, 'BAHRAIN', 'BH'),
(17, 'BANGLADESH', 'BD'),
(18, 'BARBADOS', 'BB'),
(19, 'BELGIUM', 'BE'),
(20, 'BELIZE', 'BZ'),
(21, 'BENIN', 'BJ'),
(22, 'BERMUDA', 'BM'),
(23, 'BHUTAN', 'BT'),
(24, 'BOSNIA-HERZEGOVINA', 'BA'),
(25, 'BOTSWANA', 'BW'),
(26, 'BOUVET ISLAND', 'BV'),
(27, 'BRAZIL', 'BR'),
(28, 'BRITISH INDIAN OCEAN TERRITORY', 'IO'),
(29, 'BRUNEI DARUSSALAM', 'BN'),
(30, 'BULGARIA', 'BG'),
(31, 'BURKINA FASO', 'BF'),
(32, 'CANADA', 'CA'),
(33, 'CAPE VERDE', 'CV'),
(34, 'CAYMAN ISLANDS', 'KY'),
(35, 'CENTRAL AFRICAN REPUBLIC', 'CF'),
(36, 'CHILE', 'CL'),
(37, 'CHINA', 'CN'),
(38, 'CHRISTMAS ISLAND', 'CX'),
(39, 'COCOS (KEELING) ISLANDS', 'CC'),
(40, 'COLOMBIA', 'CO'),
(41, 'COOK ISLANDS', 'CK'),
(42, 'CYPRUS', 'CY'),
(43, 'CZECH REPUBLIC', 'CZ'),
(44, 'DENMARK', 'DK'),
(45, 'DJIBOUTI', 'DJ'),
(46, 'DOMINICA', 'DM'),
(47, 'DOMINICAN REPUBLIC', 'DO'),
(48, 'ECUADOR', 'EC'),
(49, 'COSTA RICA', 'CR'),
(50, 'EGYPT', 'EG'),
(51, 'EL SALVADOR', 'SV'),
(52, 'ESTONIA', 'EE'),
(53, 'FALKLAND ISLANDS (MALVINAS)', 'FK'),
(54, 'FAROE ISLANDS', 'FO'),
(55, 'FIJI', 'FJ'),
(56, 'FINLAND', 'FI'),
(57, 'FRANCE', 'FR'),
(58, 'FRENCH GUIANA', 'GF'),
(59, 'FRENCH POLYNESIA', 'PF'),
(60, 'FRENCH SOUTHERN TERRITORIES', 'TF'),
(61, 'GABON', 'GA'),
(62, 'GAMBIA', 'GM'),
(63, 'GEORGIA', 'GE'),
(64, 'GERMANY', 'DE'),
(65, 'GHANA', 'GH'),
(66, 'GIBRALTAR', 'GI'),
(67, 'GREECE', 'GR'),
(68, 'GREENLAND', 'GL'),
(69, 'GRENADA', 'GD'),
(70, 'GUADELOUPE', 'GP'),
(71, 'GUAM', 'GU'),
(72, 'GUERNSEY', 'GG'),
(73, 'GUYANA', 'GY'),
(74, 'HEARD ISLAND AND MCDONALD ISLANDS', 'HM'),
(75, 'HOLY SEE (VATICAN CITY STATE)', 'VA'),
(76, 'HONDURAS', 'HN'),
(77, 'HONG KONG', 'HK'),
(78, 'HUNGARY', 'HU'),
(79, 'ICELAND', 'IS'),
(80, 'INDIA', 'IN'),
(81, 'INDONESIA', 'ID'),
(82, 'IRELAND', 'IE'),
(83, 'ISLE OF MAN', 'IM'),
(84, 'ISRAEL', 'IL'),
(85, 'ITALY', 'IT'),
(86, 'JAMAICA', 'JM'),
(87, 'JAPAN', 'JP'),
(88, 'JERSEY', 'JE'),
(89, 'JORDAN', 'JO'),
(90, 'KAZAKHSTAN', 'KZ'),
(91, 'KIRIBATI', 'KI'),
(92, 'KOREA, REPUBLIC OF', 'KR'),
(93, 'KUWAIT', 'KW'),
(94, 'KYRGYZSTAN', 'KG'),
(95, 'LATVIA', 'LV'),
(96, 'LESOTHO', 'LS'),
(97, 'LIECHTENSTEIN', 'LI'),
(98, 'LITHUANIA', 'LT'),
(99, 'LUXEMBOURG', 'LU'),
(100, 'MACAO', 'MO'),
(101, 'MACEDONIA', 'MK'),
(102, 'MADAGASCAR', 'MG'),
(103, 'MALAWI', 'MW'),
(104, 'MALAYSIA', 'MY'),
(105, 'MALTA', 'MT'),
(106, 'MARSHALL ISLANDS', 'MH'),
(107, 'MARTINIQUE', 'MQ'),
(108, 'MAURITANIA', 'MR'),
(109, 'MAURITIUS', 'MU'),
(110, 'MAYOTTE', 'YT'),
(111, 'MEXICO', 'MX'),
(112, 'MICRONESIA, FEDERATED STATES OF', 'FM'),
(113, 'MOLDOVA, REPUBLIC OF', 'MD'),
(114, 'MONACO', 'MC'),
(115, 'MONGOLIA', 'MN'),
(116, 'MONTENEGRO', 'ME'),
(117, 'MONTSERRAT', 'MS'),
(118, 'MOROCCO', 'MA'),
(119, 'MOZAMBIQUE', 'MZ'),
(120, 'NAMIBIA', 'NA'),
(121, 'NAURU', 'NR'),
(122, 'NEPAL', 'NP'),
(123, 'NETHERLANDS', 'NL'),
(124, 'NETHERLANDS ANTILLES', 'AN'),
(125, 'NEW CALEDONIA', 'NC'),
(126, 'NEW ZEALAND', 'NZ'),
(127, 'NICARAGUA', 'NI'),
(128, 'NIGER', 'NE'),
(129, 'NIUE', 'NU'),
(130, 'NORFOLK ISLAND', 'NF'),
(131, 'NORTHERN MARIANA ISLANDS', 'MP'),
(132, 'NORWAY', 'NO'),
(133, 'OMAN', 'OM'),
(134, 'PALAU', 'PW'),
(135, 'PALESTINE', 'PS'),
(136, 'PANAMA', 'PA'),
(137, 'PARAGUAY', 'PY'),
(138, 'PERU', 'PE'),
(139, 'PHILIPPINES', 'PH'),
(140, 'PITCAIRN', 'PN'),
(141, 'POLAND', 'PL'),
(142, 'PORTUGAL', 'PT'),
(143, 'PUERTO RICO', 'PR'),
(144, 'QATAR', 'QA'),
(145, 'REUNION', 'RE'),
(146, 'ROMANIA', 'RO'),
(147, 'RUSSIAN FEDERATION', 'RU'),
(148, 'RWANDA', 'RW'),
(149, 'SAINT HELENA', 'SH'),
(150, 'SAINT KITTS AND NEVIS', 'KN'),
(151, 'SAINT LUCIA', 'LC'),
(152, 'SAINT PIERRE AND MIQUELON', 'PM'),
(153, 'SAINT VINCENT AND THE GRENADINES', 'VC'),
(154, 'SAMOA', 'WS'),
(155, 'SAN MARINO', 'SM'),
(156, 'SAO TOME AND PRINCIPE', 'ST'),
(157, 'SAUDI ARABIA', 'SA'),
(158, 'SENEGAL', 'SN'),
(159, 'SERBIA', 'RS'),
(160, 'SEYCHELLES', 'SC'),
(161, 'SINGAPORE', 'SG'),
(162, 'SLOVAKIA', 'SK'),
(163, 'SLOVENIA', 'SI'),
(164, 'SOLOMON ISLANDS', 'SB'),
(165, 'SOUTH AFRICA', 'ZA'),
(166, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GS'),
(167, 'SPAIN', 'ES'),
(168, 'SURINAME', 'SR'),
(169, 'SVALBARD AND JAN MAYEN', 'SJ'),
(170, 'SWAZILAND', 'SZ'),
(171, 'SWEDEN', 'SE'),
(172, 'SWITZERLAND', 'CH'),
(173, 'TAIWAN, PROVINCE OF CHINA', 'TW'),
(174, 'TANZANIA, UNITED REPUBLIC OF', 'TZ'),
(175, 'THAILAND', 'TH'),
(176, 'TIMOR-LESTE', 'TL'),
(177, 'TOGO', 'TG'),
(178, 'TOKELAU', 'TK'),
(179, 'TONGA', 'TO'),
(180, 'TRINIDAD AND TOBAGO', 'TT'),
(181, 'TUNISIA', 'TN'),
(182, 'TURKEY', 'TR'),
(183, 'TURKMENISTAN', 'TM'),
(184, 'TURKS AND CAICOS ISLANDS', 'TC'),
(185, 'TUVALU', 'TV'),
(186, 'UGANDA', 'UG'),
(187, 'UKRAINE', 'UA'),
(188, 'UNITED ARAB EMIRATES', 'AE'),
(189, 'UNITED KINGDOM', 'GB'),
(190, 'UNITED STATES', 'US'),
(191, 'UNITED STATES MINOR OUTLYING ISLANDS', 'UM'),
(192, 'URUGUAY', 'UY'),
(193, 'UZBEKISTAN', 'UZ'),
(194, 'VANUATU', 'VU'),
(195, 'VENEZUELA', 'VE'),
(196, 'VIET NAM', 'VN'),
(197, 'VIRGIN ISLANDS, BRITISH', 'VG'),
(198, 'VIRGIN ISLANDS, U.S.', 'VI'),
(199, 'WALLIS AND FUTUNA', 'WF'),
(200, 'WESTERN SAHARA', 'EH'),
(201, 'ZAMBIA', 'ZM');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `templateId` int(255) NOT NULL,
  `current_tem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`templateId`, `current_tem`) VALUES
(2, 'checkmydrive');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userFileId` int(255) NOT NULL,
  `user_level` tinyint(2) NOT NULL DEFAULT '1',
  `subscriber_start` datetime NOT NULL,
  `subscriber_end` datetime NOT NULL,
  `subscription` tinyint(1) UNSIGNED NOT NULL,
  `params` text CHARACTER SET utf8 NOT NULL,
  `company` varchar(255) COLLATE utf8_bin NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `created_by`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`, `userFileId`, `user_level`, `subscriber_start`, `subscriber_end`, `subscription`, `params`, `company`, `address`) VALUES
(81, 'Super User', 'dev@gmail.com', '$2a$08$e8BS1bBvHE4Zl.XY6lGf0u4x9zcbNxJsxdtJQFolp/WglyEREFTDC', 'dev@gmail.com', 81, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2017-05-30 05:28:30', '2014-03-10 10:08:49', '2017-05-30 09:28:30', 0, 3, '0000-00-00 00:00:00', '2017-06-28 16:00:04', 1, '', '', ''),
(88, 'Son Nguyen', 'jdev@gmail.com', '$2a$08$e8a8/.DDjMMFPNE/Ek2piex4/iMfurgL/KSDkhDYMtWbztMwT2vNi', 'jdev@gmail.com', 81, 1, 0, '', NULL, NULL, NULL, '98db7fb1f76d889649ef7fe8d24b44ce', '::1', '2017-05-30 05:11:23', '2016-09-07 03:09:00', '2017-05-30 09:11:23', 0, 2, '2016-09-07 03:09:00', '2017-09-07 03:09:00', 1, '{"transaction_subject":"","txn_type":"web_accept","payment_date":"20:29:57 Nov 07, 2016 PST","last_name":"Joomlavi","residence_country":"US","pending_reason":"unilateral","item_name":"12-Month Control Professional Subscription","payment_gross":"108.00","mc_currency":"USD","payment_type":"instant","protection_eligibility":"Ineligible","payer_status":"verified","verify_sign":"AFcWxV21C7fd0v3bYYYRCpSSRl31AItRnZ7O7unrEfDyl1doUmo7G.Gi","tax":"0.00","test_ipn":"1","payer_email":"buyer.joomlavi@gmail.com","txn_id":"7BP315748V0121943","quantity":"1","receiver_email":"accounts@aloud.ie","first_name":"Dev","payer_id":"TMAJ735CWGXGL","item_number":"1","handling_amount":"0.00","payment_status":"Pending","shipping":"0.00","mc_gross":"108.00","custom":"88","charset":"utf-8","notify_version":"3.8","merchant_return_link":"click here","auth":"AwO3.Cof05pxjGfp1JbXM0HMzX5J8eENQ16YTMAShN0MI0ifJve2n.pGczB9sEhkGB6t.aNyXBVYD.p82DE-Ivw","google":{"token":{"access_token":"ya29.GltOBFNm8zebjE_8Uf3diu7OCJKfSwOy-eXAAtraO3Jo9LtSxEclOFainshAn3yQ62OAkN7ydXgAy-qsgGgF2pi_S80sKf0_aKDnST6gZVR3VjlntFGyV9jOl9tM","token_type":"Bearer","expires_in":3600,"refresh_token":"1\\/9lWs0MDekyawUQaaBwR8PZlo0Ws2_CeY_lP3rQ76b60","id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6IjQyMTcxMDE1MzMwZjEwN2FhN2QxYjg1NGJmY2Y1NGE0ZjVhNTA3MDUifQ.eyJhenAiOiI2NjA5NTM1MzY4Nzgtc2RpZDM2NHZncWFsOW5mNW81bjFzdm5odTdnNnFxNW4uYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2NjA5NTM1MzY4Nzgtc2RpZDM2NHZncWFsOW5mNW81bjFzdm5odTdnNnFxNW4uYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDMyODIwNTYzMjQ4OTQ4MzY1MDUiLCJlbWFpbCI6ImtuaWdoZXJyYW50QGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiSDJva2VicXdNM05fQ3RURi1DYTVNUSIsImlzcyI6Imh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbSIsImlhdCI6MTQ5NTEzMzQ1MSwiZXhwIjoxNDk1MTM3MDUxLCJuYW1lIjoiOuGDpjogU29u4oSiIDrhg6Y6IE4iLCJwaWN0dXJlIjoiaHR0cHM6Ly9saDMuZ29vZ2xldXNlcmNvbnRlbnQuY29tLy1seWp5RmlUamZGUS9BQUFBQUFBQUFBSS9BQUFBQUFBQUFXMC93X1hYb3lkd01EVS9zOTYtYy9waG90by5qcGciLCJnaXZlbl9uYW1lIjoiOuGDpjogU29u4oSiIDrhg6Y6IiwiZmFtaWx5X25hbWUiOiJOIiwibG9jYWxlIjoidmkifQ.Q5teR6dtXu_JJUxnKzdKl8844EC4k74Ccr6yFALjbDnfLjjxtDqxX0HwVxh8YaynauIYYMk19d67RpdnJRsSovhdzVP8aGr-_-cdCeLCyjf-QwElhZd3Xv3umbV8l7AcYhGb7P0nwZtRpDugfgddfj4ciVMW36o2UBEE_UZc2N-JLTOmc2pvV6GikiLnh9Aq5zr5yc9zk1OZnY_iXwsPv8UuDAF6-JRM2AWxSaKb7EDYLSH8EEqmA5LxhvijMtPMJJlAIBVpK4v_i2g_Yn27erD31lbjx6msjr7wMxk6thShvytyen_FN-TpQd_dtZZFZ0RvtWYzV92BxEnr3O6gVw","created":1495133435},"refresh_token":"1\\/9lWs0MDekyawUQaaBwR8PZlo0Ws2_CeY_lP3rQ76b60","folder":"0B2WXVDiHKHIJMmI2a2FOamdjUmM","info":{"name":":\\u10e6: Son\\u2122 :\\u10e6: N","email":"knigherrant@gmail.com","avatar":"https:\\/\\/lh3.googleusercontent.com\\/-lyjyFiTjfFQ\\/AAAAAAAAAAI\\/AAAAAAAAAW0\\/w_XXoydwMDU\\/photo.jpg"}},"use":"google"}', '', ''),
(103, 'Son 432432', 'joomlavi.son@gmail.com', '$2a$08$ROiZjIUfQihoIiVAIcV0ZeRuQOcWoN5oeb.7E8uzYDQR33kptESJS', 'joomlavi.son@gmail.com', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2017-05-27 05:13:53', '2017-05-27 05:13:15', '2017-05-27 09:13:53', 0, 1, '2017-05-27 05:13:15', '2017-06-27 05:13:15', 0, '', '', ''),
(104, '42342 4234324', '423432423rantxx@gmail.com', '$2a$08$9g2TBzpgZsCDXvHCup2BCOsRG8UBCpr8IV1VQh7t2OhJW5NqxuMHe', '423432423rantxx@gmail.com', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '0000-00-00 00:00:00', '2017-05-27 05:17:04', '2017-05-27 09:17:04', 0, 1, '2017-05-27 05:17:04', '2017-06-27 05:17:04', 0, '', '', ''),
(105, '42342 4234324', '42432ntxx@gmail.com', '$2a$08$4d9xLoPWsF/9ZP1kr2d/Ueuv3wzSPgrjTdp97BbyShnQXJV56M1Eq', '42432ntxx@gmail.com', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '0000-00-00 00:00:00', '2017-05-27 05:34:10', '2017-05-27 09:34:10', 0, 1, '2017-05-27 05:34:10', '2017-06-27 05:34:10', 0, '', '', ''),
(106, '42342 4234324', '543345434ntxx@gmail.com', '$2a$08$d3hwops13/FNCOIeMyxTEO3Jyqmcp2.r331nMYGEAekCr1mKOmQWC', '543345434ntxx@gmail.com', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '0000-00-00 00:00:00', '2017-05-27 05:36:36', '2017-05-27 09:36:36', 0, 1, '2017-05-27 05:36:36', '2017-06-27 05:36:36', 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `country`, `website`) VALUES
(1, 83, NULL, NULL),
(2, 84, NULL, NULL),
(3, 85, NULL, NULL),
(4, 86, NULL, NULL),
(5, 88, NULL, NULL),
(6, 89, NULL, NULL),
(7, 93, NULL, NULL),
(8, 98, NULL, NULL),
(9, 102, NULL, NULL),
(10, 103, NULL, NULL),
(11, 104, NULL, NULL),
(12, 105, NULL, NULL),
(13, 106, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lang_codes`
--
ALTER TABLE `lang_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`templateId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_autologin`
--
ALTER TABLE `user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lang_codes`
--
ALTER TABLE `lang_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `templateId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
