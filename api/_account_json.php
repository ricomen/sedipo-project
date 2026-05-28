<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_account';
 $db_fields_list = ['account_id', 'login', 'fullname', 'role', 'role_id'];
 $db_fields = ['account_id', 'login', 'fullname', 'email', 'phone', 'role', 'role_id' ];
 $db_order = '`login`';
 $db_index = 'account_id';
 $db_fields_search = ['login', 'fullname'];
 $simple_search = 0;


 require_once('json_lib.php');



