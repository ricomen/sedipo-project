<?php
/**
 * @copyright 2019
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$_JSON =  json_decode(file_get_contents('php://input'), true);
//$table = $_JSON['table'];

// $db_table = $table;
 $db_fields_list = ['b_id', 'title', 'text', 'date_from', 'img_ads'];
 $db_fields = ['b_id',  'title', 'text', 'date_add', 'date_update', 'date_from', 'date_to', 'img_ads', 'img_main'];
 $db_order = '`date_update`';
 $db_index = 'b_id';
 $db_fields_search = [ 'title' ];
 //$db_search_limit = 3;


require_once('../config.php');
$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;

try {
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword );
}
catch(PDOException $e) {
    echo $e->getMessage();
}

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break;
}

function orders_count($date1) {
  global $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search, $dbh;
//  $stmt = $dbh->prepare('SELECT name as status, COUNT(`status_id`)as count FROM `a_order` LEFT JOIN `a_status` USING(`status_id`) WHERE date_order=? GROUP BY `status_id`');
//  $stmt->execute([$date1]);
  $stmt = $dbh->prepare('SELECT name as status, COUNT(`status_id`)as count FROM `a_order` LEFT JOIN `a_status` USING(`status_id`) WHERE `status_id` < 15 GROUP BY `status_id`');
  $stmt->execute();

  if($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
    $rc_list = $row;
    $status = 0;
  }

  $result = $rc_list;
  echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


if($api_function=='count'){
   orders_count($api_arg["date1"]);
}