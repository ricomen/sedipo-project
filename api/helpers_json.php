<?php
/**
 * @copyright 2019
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$_JSON =  json_decode(file_get_contents('php://input'), true);
$table = $_JSON['table'];

 $db_table = $table;
 $db_fields_list = ['name', 'description', 'normative'];
 $db_fields = ['helper_id',  'name', 'description', 'normative'];
 $db_order = '`name`';
 $db_index = 'helper_id';
 $db_fields_search = [ 'name' ];


require_once('../config.php');

$dbhost = $cfg_helper->host;
$dbuser = $cfg_helper->user;
$dbpassword = $cfg_helper->password;
$dbname = $cfg_helper->name;

try {
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword );
}
catch(PDOException $e) {
    echo $e->getMessage();
}



function helpers_list($table_name) {
  global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

  $fields_list = '';
  foreach ($db_fields_list as $value) {
    $fields_list = $fields_list . '`'.$value.'` ,';
  }
  $fields_list = substr($fields_list, 0, -1);

  $stmt = $dbh->prepare('SELECT '.$fields_list.' FROM `'.$db_table.'`');
  $stmt->execute();

  if($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
    $rc_list = $row;
    $status = 0;
  }


  $object = new stdClass();
  foreach ($rc_list as $key => $value)
  {
    $object->$key = $value;
  }

  $result = $object;
  echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



helpers_list($db_table);