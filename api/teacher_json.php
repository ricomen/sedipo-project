<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_teacher';
 $db_fields_list = ['user_id', 'lastname', 'firstname', 'middlename', 'on_staff'];
 $db_fields = ['user_id', 'lastname', 'firstname', 'middlename', 'on_staff', 'login', 'email', 'password', 'status', 'date_of_birth', 'inn','snils', 'pasport', 'pasport2', 'phone', 'address', 'education', 'diplom' ];
 $db_order = '`lastname`, `firstname`';
 $db_index = 'user_id';
 $db_fields_search = [ 'lastname'];
 $simple_search = 0;


 require_once('json_lib.php');


