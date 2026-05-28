<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_contract';
 $db_fields_list = ['contract_id', 'type', 'name', 'longtime_contract', 'prefix', 'template_id', 'template1_id', 'template2_id', 'template3_id', 'performer'];
 $db_fields = ['contract_id', 'type', 'name', 'longtime_contract', 'prefix', 'template_id', 'template1_id', 'template2_id', 'template3_id', 'performer'  ];
 $db_order = '`contract_id`';
 $db_index = 'contract_id';
 $db_fields_search = ['name' ];
 $db_search_limit = 25;


 require_once('json_lib.php');



