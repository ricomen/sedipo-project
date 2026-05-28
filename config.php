<?php

global $EmailDomain, $ReportTitle, $AccountPrefix, $LogoImg, $LoginLogoImg;  
global $cfg, $cfg_helper;
global $page_size;

$cfg = new stdClass();
$cfg->host    = 'localhost';
$cfg->name    = 'sed20';
$cfg->user    = 'sed20';
$cfg->password    = 'gr5hfk';
$cfg->customer_id    = 2;

$cfg_helper = new stdClass();
$cfg_helper->host    = 'localhost';
$cfg_helper->name    = 'helper';
$cfg_helper->user    = 'sed20';
$cfg_helper->password    = 'gr5hfk';

$ReportTitle = 'Система сопровождения образования';
$AccountPrefix = 'd';
$EmailDomain = 'sed@ipo5.ru';


$AuthTitle = 'Sed 2.0';
$ShortTitle = 'Sed 2.0';
$JsonApiURL = 'https://sed20d.sedipo.ru/';
$MoodleApiURL = 'https://sdoold.sedipo.ru/sedipo/';

$LogoImg = '/uploads/images/logo.png';
$LoginLogoImg = '/uploads/images/login-logo.png';

$page_size=30;

$HoursPerDay = 8;
$UploadMax = 5; //GB
$ESM = 0;
$is_short_certificate_num = 1;
$IS_1C = 0;
$IS_LMS = 1;
$is_LITTLE = 0;
$is_NDS = 0.2;
$IS_SECR = 0;
$IS_DEVELOPMENT = 1;
$IS_DEPRECATED = 1;
?>