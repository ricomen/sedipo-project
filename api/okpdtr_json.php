<?php
/**
 * @copyright 2022
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



require_once '../config.php';
require_once 'lib.php';
$dbhost = $cfg_helper->host;
$dbuser = $cfg_helper->user;
$dbpassword = $cfg_helper->password;
$dbname = $cfg_helper->name;

/*
$dbhost_session = $cfg->host;
$dbuser_session = $cfg->user;
$dbpassword_session = $cfg->password;
$dbname_session = $cfg->name;
*/



try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


/*
try {  
        $dbh_session = new PDO("mysql:host=$dbhost_session;dbname=$dbname_session;charset=utf8", $dbuser_session, $dbpassword_session );  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}
*/


session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}




function o_list1() {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $list=[];
    $stmt = $dbh->prepare('SELECT DISTINCT  `area`   FROM `a_okpdtr`');
    $stmt->execute([]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $list[] = $row->area ;
    }

    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list  ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function o_list($okpdtr_area) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $list=[];
    $stmt = $dbh->prepare('SELECT  `id`, `name`, `code`, `range1`, `range2`   FROM `a_okpdtr`   WHERE  `area` LIKE ? LIMIT 500 ');
    $stmt->execute(["$okpdtr_area%"]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $list[] = ["id"=>$row->id, "name"=>$row->name,  "code"=>$row->code,  "range1"=>$row->range1,  "range2"=>$row->range2 ];
    }

    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list  ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function object($okpdtr_code) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $rc = ["id"=>0, "name"=>'', "range1"=>0, "range2"=>0, "okpdtr_area"=>''];
    $stmt = $dbh->prepare('SELECT  `id`, `name`, `range1`, `range2`, `area`   FROM `a_okpdtr`   WHERE  `code` =?  ');
    $stmt->execute([$okpdtr_code]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $rc = ["id"=>$row->id, "name"=>$row->name, "range1"=>$row->range1, "range2"=>$row->range2, "okpdtr_area"=>$row->area];
            $status = 0;
    }

    $result = ["role"=>$session_role, "action"=>"object",   "status"=>$status,   "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt_session = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt_session = $dbh_session->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt_session->execute([ $api_arg['sessionId'] ]);
if($row = $stmt_session->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
     $session_login = $row->login;
}



api_journal('okpdr', $api_function, $api_arg, $session_login);
if($api_function=='list'){
    //o_list(strip_tags($api_arg["okpdtr_area"], []) );
    o_list($api_arg["okpdtr_area"] );
}
else if($api_function=='list1'){
    o_list1( );
}
else if($api_function=='object'){
    object(intval($api_arg["objectId"]) );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>

