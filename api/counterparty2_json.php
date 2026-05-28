<?php
/**
 * @copyright 2024
 */

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



function counterparty_list(  $page ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $offset=0;
    if( $page>1 ){
	    $offset = intval($page-1)*$page_size;
    }
    $num_pages = 0;

    $counterparty_list = [];
    $stmt = $dbh->prepare('SELECT  `counterparty_id`, `name`, `shortname`, `type`  FROM `a_users` WHERE 1  ORDER BY `lastname` LIMIT '.$page_size.'  OFFSET  ' . "$offset");
    $stmt->execute([ ]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $counterparty_list[] =  ["userId"=>$row->user_id,  "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "counterparty_id"=>$row->counterparty_id, "organization_name"=>$organization_name, "subdivision"=>$row->subdivision,  "job_title"=>$job_title_name ];
    }


    $stmt0 = $dbh->prepare('SELECT  count(DISTINCT `user_id` )  as `count`  FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? AND `counterparty_id`=?');
    $stmt0->execute([]);
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	    $members = $row0->count;
    }
    $num_pages = intval(($members+0.5)/$page_size+1);
    if( $page > $num_pages ){
	    $page = $num_pages;
    }

    //$result = ["role"=>$session_role, "action"=>"users_list", "userId"=>$user_id_session, "result"=>$rc_list, "numPages"=>$members];
    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$counterparty_list,  "numPages"=>$num_pages, "page"=>$page];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function  c_validity_period( $counterparty_id, $month, $month_stop ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $count = 0;
    if($counterparty_id>0 ) {
        $stmt = $dbh->prepare('SELECT  count(`user_id`) as `count`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)    WHERE  `a_users`.`counterparty_id`=? AND `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH) AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\' '  );
        $stmt->execute([ $counterparty_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                  $count = $row->count;
                  $rc = [ "counterparty_id"=>$counterparty_id,  "count"=>$count];
        }

    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"validity_period",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt = $dbh->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}

if($api_function=='list' ){
    counterparty_list(  intval($api_arg["$page"] );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

