<?php

global $EmailDomain, $ReportTitle, $AccountPrefix;  
global $cfg;
global $page_size;

$cfg = new stdClass();

$cfg->host    = 'localhost';
$cfg->name    = 'test';
$cfg->user    = 'sedipo';
$cfg->password    = 'Dfg05lgflpr';

$EmailDomain = 'sed@ipo5.ru';
$ReportTitle = 'Система сопровождения образования';
$AccountPrefix = 'd';


$AuthTitle = 'Институт Профессионального Образования';
$ShortTitle = 'ИПО';
$JsonApiURL = 'https://test.sedipo.ru/';
$MoodleApiURL = 'https://test.ru/moodle/';

$page_size=30;
$hours_per_day = 8;

$DPO = 1;
?>