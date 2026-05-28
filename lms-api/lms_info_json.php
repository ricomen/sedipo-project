<?php
/**
 * @copyright 2025
 */

require_once '../../config/config-auth.php';
$dbhost = $cfg_auth->host;
$dbuser = $cfg_auth->user;
$dbpassword = $cfg_auth->password;
$dbname = $cfg_auth->name;


try {  
    $dbh_a = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}




require_once('../config.php');
global $DB;    

//categories_list
//courses_list

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


function courses_list($category_id){
       global  $MoodleApiURL;

       $data = ['category_id'=>$category_id ];
       $headers = stream_context_create( [ 'http' => [
	    'method'  => 'POST',
	    'header'  => 'Content-Type: application/x-www-form-urlencoded',
	    'content' => http_build_query($data)
            ]   ] );
       $rc = file_get_contents($MoodleApiURL.'moodle_courses_plugin.php', false, $headers);

       echo $rc;
}


function categories_list(){
       global  $MoodleApiURL;

       $data = [   ];
       $headers = stream_context_create( [ 'http' => [
	    'method'  => 'POST',
	    'header'  => 'Content-Type: application/x-www-form-urlencoded',
	    'content' => http_build_query($data)
            ]   ] );
       $rc = file_get_contents($MoodleApiURL.'moodle_categories_plugin.php', false, $headers);

       echo $rc;
}



/*
$session_account_id = -1;
$session_role = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `role_id`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}
$php_sessionid = str_replace("'\'\\\n\r ", '', $_COOKIE['PHPSESSID'] );
$sessionId = $api_arg["sessionId"];
*/

if($api_function=='categories_list'){
    categories_list();
}
else if($api_function=='courses_list'){
    courses_list( intval($api_arg["category_id"]) );
}

else {
  echo "{$api_function}";
}

?>

