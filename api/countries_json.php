<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_config = 'helper';

 $db_table = 'a_countries';
 $db_fields_list = [ 'alpha3', 'name', 'code'];
 $db_fields = ['code', 'name', 'fullname', 'alpha2', 'alpha3'  ];
 $db_order = '`name`';
 $db_index = 'alpha3';
 $db_fields_search = ['alpha3', 'name' ];
 $db_search_limit = 20;
 $simple_search = 1;


 require_once('json_lib.php');




