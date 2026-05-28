<?php
/**
 * @copyright 2019
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$_JSON =  json_decode(file_get_contents('php://input'), true);
//$table = $_JSON['table'];
$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break;
}

 $db_table = $table;
 $db_fields_list = ['b_id', 'title', 'text', 'date_from', 'img_ads'];
 $db_fields = ['b_id',  'title', 'text', 'date_add', 'date_update', 'date_from', 'date_to', 'img_ads', 'img_main'];
 $db_order = '`date_from`';
 $db_index = 'b_id';
 $db_fields_search = [ 'title' ];
 $db_search_limit = 3;
 $db_fields2 = ['id', 'login', 'ced', 'world'];
 $db_index2 = 'login';
 $db_fields_search2 =  '`login`' ;

require_once('../config.php');

$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;

$dbhost_helper = $cfg_helper->host;
$dbuser_helper = $cfg_helper->user;
$dbpassword_helper = $cfg_helper->password;
$dbname_helper = $cfg_helper->name;

try {
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword );
}
catch(PDOException $e) {
    echo $e->getMessage();
}
try {
    $dbh2 = new PDO("mysql:host=$dbhost_helper;dbname=$dbname_helper;charset=utf8", $dbuser_helper, $dbpassword_helper);
}
catch(PDOException $e) {
    echo $e->getMessage();
}



function news_list($table_name) {
  global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search, $db_search_limit, $dbh2;

  $fields_list = '';
  foreach ($db_fields_list as $value) {
    $fields_list = $fields_list . '`'.$value.'` ,';
  }
  $fields_list = substr($fields_list, 0, -1);

  $stmt = $dbh2->prepare('SELECT '.$fields_list.' FROM `'.$db_table.'` ORDER BY '.$db_order.' DESC LIMIT '. $db_search_limit);
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

function news_isRead($db_table, $db_login){
  global $dbh, $db_fields_search2;
//$db_fields_search =  'login' ;
//echo json_encode($db_login, JSON_UNESCAPED_UNICODE);
//  $stmt = $dbh->prepare('SELECT * FROM `'.$db_table.'` WHERE `login` =?');
  $stmt = $dbh->prepare('SELECT * FROM `'.$db_table.'` WHERE '.$db_fields_search2.' = ?');
  $stmt->execute([$db_login]);

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

//  echo json_encode('loginlogin', JSON_UNESCAPED_UNICODE);

}


function update_isRead($db_login){
  global $dbh, $db_table, $news_type, $news_isRead;

  $stmt = $dbh->prepare('UPDATE `'.$db_table.'` SET '.$news_type.'='.$news_isRead.'  WHERE `login`=? ');
  $stmt->execute( [$db_login] );

  $result = ["role"=>$db_login, "action"=>"update_isRead", "news_type"=>$news_type, "news_isRead"=>$news_isRead, strval($news_type)=>$news_isRead ];
  echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if($api_function == 'list'){
  $db_table = $api_arg["table"];
  news_list($db_table);
} else if($api_function == 'news_isRead'){
  $db_table = $api_arg["table"];
  $db_login = $api_arg["login"];
  news_isRead($db_table, $db_login);
} else if($api_function == 'news_isRead_update') {
  $db_table = $api_arg["table"];
  $db_login = $api_arg["login"];
  $news_type = $api_arg["news_type"];
  $news_isRead = $api_arg["news_isRead"];
  update_isRead($db_login);
}