<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_teachers_commission_edition';
 $db_fields_list = ['commission_id', 'edition_id', 'edition' ];
 $db_fields = ['commission_id', 'edition_id', 'edition', 'directive_num', 'comment'  ];
 $db_order = '`commission_id`, `edition_id` DESC ';
 $db_index = 'edition_id';
 $db_fields_search = [ 'commission_id' ];


 require_once('json_lib.php');


