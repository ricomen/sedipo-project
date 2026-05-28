<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_status';
 $db_fields = ['status_id', 'name', 'activity', 'action', 'new_status', 'learn'];
 $db_fields_list = ['status_id', 'name', 'activity'];
 $db_order = 'status_id';
 $db_index = 'status_id';
 $db_fields_search = ['name'];

 require_once('json_lib.php');



