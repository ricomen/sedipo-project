<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_teachers_commission_teacher';
 $db_fields_list = ['teacher_id', 'commission_id', 'edition_id', 'n', 'fullname', 'job_title', 'sign'];
 $db_fields = ['teacher_id', 'commission_id', 'edition_id', 'n', 'fullname', 'job_title', 'sign'  ];
 $db_order = '`n`';
 $db_index = 'teacher_id';
 $db_fields_search = [ 'commission_id' ];


 require_once('json_lib.php');


