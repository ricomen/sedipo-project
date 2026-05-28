<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//'certificate_prefix',

 $db_table = 'a_course_category';
 $db_fields_list = ['category_id', 'name',  'is_rank_of_profession', 'modules', 'consulting',  'type_of_education_id', 'type_of_program_id' ];
 $db_fields = ['category_id', 'moodle_category_id',  'name',  'parent_id', 'is_rank_of_profession',  'type_of_education_id', 'subtype_of_education_id', 'type_of_program_id',  'type_of_education', 'subtype_of_education', 'type_of_program',  'modules', 'consulting',    'need_snils', 'need_birth', 'need_address', 'need_diplom', 'need_photo', 'contract_id', 'contract2_id' ];
 $db_order = '`category_id`';
 //$db_order = '`type_of_education_id`, `type_of_program_id` DESC';
 $db_index = 'category_id';
 $db_fields_search = [ 'name'];
 $simple_search = 0;


 require_once('json_lib.php');


