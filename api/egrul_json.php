<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_config = 'helper';

 $db_table = 'a_egrul';
 $db_fields_list = ['name', 'inn'];
 $db_fields = ['name', 'inn', 'kpp', 'addres1', 'enterprise_manager', 'phone', 'email',  'okpo',  'ogrn'  ];
 $db_order = '`name`';
 $db_index = 'inn';
 $db_fields_search = ['inn', 'name' ];
 $db_search_limit = 20;
 $simple_search = 1;


 require_once('json_lib.php');



