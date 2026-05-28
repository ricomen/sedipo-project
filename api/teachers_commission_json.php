<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_teachers_commission';
 $db_fields_list = ['commission_id', 'name'];
 $db_fields = ['commission_id',  'name', 'order_id'   ];
 $db_order = '`name`';
 $db_index = 'commission_id';
 $db_fields_search = [ 'name' ];


 require_once('json_lib.php');


