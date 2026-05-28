<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $db_table = 'a_template';
 $db_fields_list = ['template_id', 'name', 'type', 'application',  'file', 'output_format', 'hidden'];
 $db_fields = ['template_id', 'name', 'type', 'application', 'file', 'size_page', 'num_of_page', 'is_blank', 'output_format', 'wysiwyg', 'hidden', 'footer_file', 'file_php' ];
 $db_order = '`name`';
 $db_index = 'template_id';
 $db_fields_search = [ 'name'];
 $simple_search = 0;


 require_once('json_lib.php');


