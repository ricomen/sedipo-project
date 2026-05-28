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
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}



require_once '../config.php';
require_once 'lib.php';
$cfg_customer_id = $cfg->customer_id;


//$api_function = '';
//$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}

//$api_function='users_list';






function accounts_list() {
    global $api_arg,   $dbh, $cfg_customer_id;

    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;


    $p_list = [];
    $stmt = $dbh->prepare('SELECT `account_id`, `login`, `email`, `role_id`, `role_name`, `fullname`  FROM `a_account`  LEFT JOIN  `a_role` USING(`role_id`) WHERE 1 '.$cfg_customer_filter.'  ORDER BY `login`');
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $p_list[] =  ["account_id"=>$row->account_id,  "login"=>$row->login, "email"=>$row->email,   "role_id"=>$row->role_id,  "role"=>$row->role_name,  "fullname"=>$row->fullname  ];
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"list",  "list"=>$p_list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function account_object($arg_id) {
    global $api_arg,  $dbh;
    global $php_sessionid, $sessionId, $session_account_id;


    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;



    $rc =  ["account_id"=>0, "login"=>'', "email"=>'',  "fullname"=>'',  "role"=>''  ];
    if($arg_id >0 ) {
        $account_id = $arg_id;
    }
    else {
        $account_id = $session_account_id;
    }

    $stmt = $dbh->prepare('SELECT  `account_id`,  `email`, `login`, `role_id`, `fullname`, `a_account`.`customer_id`, `prefix`  FROM `a_account` LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `account_id`=?  '.$cfg_customer_filter );
    $stmt->execute([ $account_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $rc =  ["account_id"=>$row->account_id, "login"=>$row->login, "email"=>$row->email,  "fullname"=>$row->fullname,  "role_id"=>$row->role_id, "customer_id"=>$row->customer_id, "prefix"=>$row->prefix  ];
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function account_save($account_id, $fullname, $login,  $email, $password0, $password1, $password2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $session_privileges;


    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;


    $rc =  ["account_id"=>0  ];
    if($account_id >0 ) {
        $stmt = $dbh->prepare('UPDATE `a_account` SET  `fullname`=?, `login`=?,  `email`=?  WHERE `account_id`=?  '.$cfg_customer_filter );
        $stmt->execute([$fullname, $login,  $email, $account_id ]);
        $p_arg_id = $account_id; 
    }

    if($account_id>0 &&   $password1!='' &&  $password2!='' && $password1 ==  $password2 ) {
	$old_password = '';
	$stmt = $dbh->prepare('SELECT  `account_id`, `password`  FROM `a_account`   WHERE `account_id`=?');
	$stmt->execute([$account_id ]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $old_password = $row->password;
	}
	if( ($old_password == $password0) || ($session_privileges.accountedit == 2) ){
    	    $stmt2 = $dbh->prepare('UPDATE `a_account` SET  `password`=?  WHERE `account_id`=? ' .$cfg_customer_filter);
    	    $stmt2->execute([$account_id ]);
	}
    }

    $rc =  ["account_id"=>$p_arg_id  ];
    $rc2 =  ["objectId"=>$p_arg_id  ];
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"organization_save", "userId"=>$user_id_session, "accountInfo"=>$rc, "result"=>$rc2 ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function account_insert($account_id, $fullname, $login,  $email,  $password1, $password2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;


     $rc =  ["account_id"=>0  ];
     $account_id = 0;
     $stmt = $dbh->prepare('INSERT INTO `a_account`(`fullname`, `login`,  `email`, `password`, `role_id`, `customer_id` ) VALUES ( ?, ?, ?, ?, ?, ?)');
     $stmt->execute([$fullname, $login,  $email, $password1, 1, $cfg_customer_id]);
     $account_id = $dbh->lastInsertId(); 

     if($account_id>0 &&  $password1!='' &&  $password2!='' && $password1 ==  $password2 ) {
	$old_password = '';
	$stmt = $dbh->prepare('SELECT  `account_id`, `password`  FROM `a_account`   WHERE `account_id`=?');
	$stmt->execute([$account_id ]);

        $stmt2 = $dbh->prepare('UPDATE `a_account` SET  `password`=?  WHERE `account_id`=? ' .$cfg_customer_filter);
        $stmt2->execute([$account_id ]);
    }

    $rc =  ["account_id"=>$p_arg_id  ];
    $rc2 =  ["objectId"=>$p_arg_id  ];
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"organization_save", "userId"=>$user_id_session, "accountInfo"=>$rc, "result"=>$rc2 ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function account_del($object_id) {
    global $api_arg, $user_id_session, $session_role, $customer, $dbh;

    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;


    $status = "0";
    $error = '';

    if($arg_id >0) {
	$stmt = $dbh->prepare('DELETE FROM `a_account`   WHERE `account_id`=? ' .$cfg_customer_filter);
	$stmt->execute([$object_id]);

	if(!$stmt) {
            $status = "4";
	    $error = 'error 2: '. $dbh->errorInfo()[2];
	}
    }

    $result = ["status"=>$status, "error"=>$error, "role"=>$session_role, "action"=>"organization_del", "customerId"=>$customer, "userId"=>$user_id_session];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function update_password($account_id,  $password0, $password1, $password2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = ' AND `customer_id`=' . $cfg_customer_id;


    //if($account_id>0 && $password0!='' &&  $password1!='' &&  $password2!='' && $password1 ==  $password2 ) {
    if($account_id>0 &&   $password1!='' &&  $password2!='' && $password1 ==  $password2 ) {
	$old_password = '';
	$stmt = $dbh->prepare('SELECT  `account_id`, `password`  FROM `a_account`   WHERE `account_id`=? '  .$cfg_customer_filter);
	$stmt->execute([$account_id ]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $old_password = $row->password;
	}
	if( $old_password == md5($password0) || $old_password == '' ){
    	    $stmt2 = $dbh->prepare('UPDATE `a_account` SET  `password`=?  WHERE `account_id`=?');
    	    $stmt2->execute([ md5($password1), $account_id ]);
	}
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update_password",  "result"=>$account_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



$session_account_id = -1;
$session_role = '';
$session_role_id = 0;
$stmt = $dbh->prepare('SELECT `login`, `role_name`, `a_account`.`role_id`, `a_account`.`account_id`, `counterparty_id`,     `accountedit`, `self_list`, `rolelist`, `accountslist`, `set_template_contract`, `validity_period_counterparty_list`, `course_category_list`, `courses_list`, `teachers_commission_list`, `teacher_list`, `template_list`, `counterparty_list`, `students_list`, `orders_analytics`, `orders_table`, `stat_report`, `groups_list`, `lstream_list`, `calendar`, `eisot_import`, `orders_list`, `orders_list_buh`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)   LEFT JOIN  `a_role` USING(`role_id`)   WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role_name;
     $session_role_id = $row->role_id;

     $session_privileges = [
          "accountedit"=>$row->accountedit,
          "self_list"=>$row->self_list,
          "rolelist"=>$row->rolelist,
          "accountslist"=>$row->accountslist,
          "set_template_contract"=>$row->set_template_contract,
          "validity_period_counterparty_list"=>$row->validity_period_counterparty_list,
          "course_category_list"=>$row->course_category_list,
          "courses_list"=>$row->courses_list,
          "teachers_commission_list"=>$row->teachers_commission_list,
          "teacher_list"=>$row->teacher_list,
          "template_list"=>$row->template_list,
          "counterparty_list"=>$row->counterparty_list,
          "students_list"=>$row->students_list,
          "orders_analytics"=>$row->orders_analytics,
          "orders_table"=>$row->orders_table,
          "stat_report"=>$row->stat_report,
          "groups_list"=>$row->groups_list,
          "lstream_list"=>$row->lstream_list,
          "calendar"=>$row->calendar,
          "eisot_import"=>$row->eisot_import,
          "orders_list"=>$row->orders_list,
          "orders_list_buh"=>$row->orders_list_buh
         ];
}
$php_sessionid = str_replace("'\'\\\n\r ", '', $_COOKIE['PHPSESSID'] );
$sessionId = $api_arg["sessionId"];



if($api_function=='list'){
    accounts_list();
}
else if($api_function=='object'){
    account_object(intval($api_arg["objectId"]));
}
else if($api_function=='update'){
    account_save(intval($api_arg["objectId"]), $api_arg["fullname"], $api_arg["login"],  $api_arg["email"],  $api_arg["password0"],  $api_arg["password1"],  $api_arg["password2"]);
}
else if($api_function=='insert'){
    account_insert( $api_arg["fullname"], $api_arg["login"],  $api_arg["email"],   $api_arg["password1"],  $api_arg["password2"]);
}
else if($api_function=='delete'){
    account_del(intval($api_arg["objectId"]));
}
else if($api_function=='update_password'){
    update_password(intval($api_arg["objectId"]),   strip_tags($api_arg["password0"]),  strip_tags($api_arg["password1"]),  strip_tags($api_arg["password2"]) );
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
