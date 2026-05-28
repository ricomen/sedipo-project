<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


 $db_table = 'a_lstream_teacher';
 $db_fields_list = ['l_id', 'lstream_id', 'date', 'topic_id',  'user_id'];
 $db_fields = ['l_id', 'lstream_id', 'date', 'topic_id',  'user_id' ];
 $db_order = '`l_id`';
 $db_index = 'l_id';
 $db_fields_search = [ 'date'];


 require_once('json_lib.php');


