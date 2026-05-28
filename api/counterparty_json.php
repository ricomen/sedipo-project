<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_config = 'main';

 $db_table = 'a_counterparty';
 $db_fields_list = ['counterparty_id',  'name', 'shortname', 'type', 'longtime_contract'];
 $db_fields = ['counterparty_id', 'account_id', 'name', 'shortname', 'type', 'entity', 'inn', 'kpp', 'ogrn', 'addres1', 'addres2', 'email',  'phone',  'position_head', 'enterprise_manager', 'position_head2', 'enterprise_manager2',  'bank', 'checking_account', 'correspondent_account', 'bik', 'longtime_contract', 'comment' ];
 $db_order = '`shortname`';
 $db_index = 'counterparty_id';
 $db_fields_search = ['shortname', 'name', 'inn' ];
 $db_search_limit = 25;
 $simple_search = 0;


 require_once('json_lib.php');



