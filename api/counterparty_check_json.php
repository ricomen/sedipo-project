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
$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;


try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}



session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}



function check_unique( $is_create, $objectId, $account_id, $inn, $name, $shortname,  $email ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $error = '';
    $count = 0;
    if($inn != '') {
        $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_counterparty` WHERE `inn`=? ');
        $stmt->execute([$inn ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->count >0) {
                $error = $error . '<br /> Контрагент с таким ИНН уже существует';
                $count = $count +1;
            }
         }
    }

    if($name != '') {
        $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_counterparty` WHERE `name`=? ');
        $stmt->execute([$name ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->count >0) {
                $error = $error . "\n Контрагент с таким названием уже существует";
                $count = $count +1;
            }
         }
    }
    
    if($shortname != '') {
        $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_counterparty` WHERE  `shortname`=? ');
        $stmt->execute([  $shortname ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->count >0) {
                $error = $error . "\n Контрагент с таким кратким названием уже существует";
                $count = $count +1;
            }
        }
    }
    
    if($email != '' && $email != ' ') {
        $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_counterparty` WHERE  `email`=?');
        $stmt->execute([ $email ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->count >0) {
                $error = $error . "\n Контрагент с таким email уже существует";
                $count = $count +1;
            }
        }
    }

    $result = ["status"=>$count, "error"=>$error, "role"=>$session_role, "action"=>"check_unique",  "result"=>$count ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
//file_put_contents("lst.txt", print_r($value, true) );
                




$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}



if($api_function=='check_unique'){

/*    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;
*/

    check_unique(intval($api_arg["is_create"]), intval($api_arg["objectId"]), intval($api_arg["account_id"]), strip_tags($api_arg["inn"]), strip_tags($api_arg["name"]), strip_tags($api_arg["shortname"]),  strip_tags($api_arg["email"])  );
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>

