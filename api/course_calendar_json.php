<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_course_calendar';
// $db_fields_list = ['course_id', 'variation',  'topic_id',  'type', 'num', 'day', 'topic', 'name_topic', 'hours'];
// $db_fields = ['course_id', 'variation', 'topic_id',  'type', 'num', 'day', 'topic', 'name_topic', 'hours' ];
 $db_fields_list = ['course_id', 'variation',  'topic_id',  'type',  'topic', 'name_topic', 'hours', 'num'];
 $db_fields = ['course_id', 'variation', 'topic_id',  'type', 'topic', 'name_topic', 'hours', 'num' ];
// $db_order = '`num`,`topic`';
 $db_order = '`topic`';
 $db_index = 'topic_id';
 $db_fields_search = [ 'name_topic'];
 $simple_search = 0;


 require_once('json_lib.php');


