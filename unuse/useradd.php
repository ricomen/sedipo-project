<?php


require_once('../config.php');
require_once($CFG->libdir.'/gdlib.php');


/*
global $DB;    
$user             = new StdClass();
$user->email      = strtolower('test@m.ru'); //MOODLE requires lowercase
$user->username   = strtolower('atest');//MOODLE requires lowercase
$user->password   = hash_internal_user_password('123');
$user->lastname   = 'test';
$user->firstname  = 'test';
// These values are required. 
// Default values are stored in moodle config files but... this is easier.
$user->auth       = 'manual';
$user->confirmed  = 1;
$user->mnethostid = 1;
$user->country    = 'RU'; //Or another country
$user->lang       = 'ru'; //Or another country
$user->timecreated = time();
$user->maildisplay= 0;

$user->id = $DB->insert_record('user', $user); // returns new userid
*/

/*
$lastid = $DB->insert_record('user', $user);
$user2 = get_complete_user_data('id', $lastid);
*/

/*
  `idnumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `institution` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `city` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
 `timezone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '99',    	Asia/Yekaterinburg 	
*/



//36
/*
$cohort             = new StdClass();
$cohort->contextid      = 1;
$cohort->name     	= 'test';
$cohort->idnumber      = 'test';
$cohort->description   = '';
$cohort->descriptionformat   = 1;
$cohort->visible   	= 1;
$cohort->component   = '';
$cohort->timecreated = time();
$cohort->timemodified = time();
$cohort->theme   = '';

$cohort->id = $DB->insert_record('cohort', $cohort); 
*/
//18



$cohort_members             = new StdClass();
$cohort_members->cohortid	= 18;
$cohort_members->userid		= 36;
$cohort_members->timeadded	= time();

$cohort_members->id = $DB->insert_record('cohort_members', $cohort_members); 


/*
CREATE TABLE `mdl_cohort` (
  `id` bigint NOT NULL,
  `contextid` bigint NOT NULL,
  `name` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `idnumber` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `descriptionformat` tinyint NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `component` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `timecreated` bigint NOT NULL,
  `timemodified` bigint NOT NULL,
  `theme` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Each record represents one cohort (aka site-wide group).' ROW_FORMAT=COMPRESSED;


INSERT INTO `mdl_cohort` (`id`, `contextid`, `name`, `idnumber`, `description`, `descriptionformat`, `visible`, `component`, `timecreated`, `timemodified`, `theme`) VALUES
(1, 1, 'Глобальная группа', 'Глобальная группа', '', 1, 1, '', 1644843214, 1644843214, ''),
(2, 1, 'Тестовая группа 1', 'Тестовая группа 1', '', 1, 1, '', 1645160362, 1645160362, ''),
(3, 1, 'Тестовая группа 2', 'Тестовая группа 2', '', 1, 1, '', 1645160362, 1645160362, ''),



CREATE TABLE `mdl_cohort_members` (
  `id` bigint NOT NULL,
  `cohortid` bigint NOT NULL DEFAULT '0',
  `userid` bigint NOT NULL DEFAULT '0',
  `timeadded` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Link a user to a cohort.' ROW_FORMAT=COMPRESSED;


*/

?>