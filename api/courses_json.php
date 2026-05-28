<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_course';
 $db_fields_list =  ['course_id', 'name', 'shortname',   'price', 'category_id', 'main_module', 'rank_of_profession', 'hours',  'hours_add', 'hours_add2', 'category_id'];
 $db_fields = ['course_id', 'category_id', 'performer_id', 'name', 'shortname', 'moodle_course_id',   'profession', 'rank_of_profession', 'hours', 'hours_l', 'hours_p', 'hours_i', 'hours_c', 'price_multiplier', 'hours_add', 'hours_l_add', 'hours_p_add', 'hours_i_add', 'hours_c_add', 'price_multiplier2', 'hours_add2', 'hours_l_add2', 'hours_p_add2', 'hours_i_add2', 'hours_c_add2',  'form_of_study', 'form_of_study_2',  'main_module', 'price', 'description', 'delta2', 'competence', 'qualification_work', 'name_common', 'certificate1_template', 'certificate1_name', 'certificate2_template', 'certificate2_name', 'protocol_template',  'commission_id', 'qualification', 'opd',  'area',  'okpdtr_code', 'eisot_id' ];
 $db_order = '`main_module` DESC, `shortname`';
 $db_index = 'course_id';
 $db_fields_search = [ 'name', 'shortname' ];
 $simple_search = 0;
 $db_search_limit = 50;


 require_once('json_lib.php');


