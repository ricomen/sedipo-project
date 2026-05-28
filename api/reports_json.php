<?php
/**
 * @copyright 2024
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



//session_start();
//$session_id = session_id();
#$api_function = '';
#$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}




function  c_validity_period( $index, $counterparty_id, $month, $month_stop ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $count = 0;
    if($counterparty_id>0 ) {
        $stmt = $dbh->prepare('SELECT  count(`a_users`.`user_id`) as `count`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_user_counterparty` ON `a_users`.`user_id`=`a_user_counterparty`.`user_id`    WHERE  `a_user_counterparty`.`counterparty_id`=? AND `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH) AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\' '  );
        $stmt->execute([ $counterparty_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                  $count = $row->count;
                  $rc = [ "index"=>$index, "counterparty_id"=>$counterparty_id,  "count"=>$count];
        }

    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"validity_period",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





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

if($api_function=='counterparty_validity_period' ){
    c_validity_period( intval($api_arg["index"]),  intval($api_arg["counterparty_id"]), intval($api_arg["month"]) , intval($api_arg["month_stop"]) );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

