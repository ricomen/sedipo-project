<?php
/**
 * @copyright 2025
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_self';
 $db_fields_list = ['edition_id',  'edition' ];
 $db_fields = [ 'edition_id',  'edition', 'name', 'shortname',  'entity', 'inn', 'kpp', 'ogrn', 'addres1', 'addres2', 'email',  'phone',  'position_head', 'enterprise_manager', 'position_head2', 'enterprise_manager2',  'bank', 'checking_account', 'correspondent_account', 'bik',  'enterprise_manager_signs',  'company_signs',  'comment',  'license',  'accreditation', 'city' ];
 $db_order = '`edition_id`';
 $db_index = 'edition_id';
 $db_fields_search = ['edition' ];


 require_once('json_lib.php');



