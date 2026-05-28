<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_lstream';
 $db_fields_list = ['lstream_id', 'name', 'status_id', 'date_begin', 'date_end'];
// $db_fields = ['stream_id', 'name', 'status_id', 'date_begin', 'date_end', 'date_protocol', 'num', 'moodle_cohort_id', 'moodle_group_id', 'moodle_enrol_id', 'directive', 'chairman', 'teacher', 'finalexamination', 'certificate_grade' ];
 $db_fields = ['lstream_id', 'name', 'status_id', 'date_begin', 'date_end', 'date_protocol', 'num',  'directive', 'chairman', 'teacher', 'finalexamination', 'certificate_grade' ];
 $db_order = 'name';
 $db_index = 'lstream_id';
 $db_fields_search = [ 'name'];


 require_once('json_lib.php');


