<?php
/**
 * @copyright 2022
 */


require_once 'config.php';
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




//$api_function = '';
//$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}

//$api_function='users_list';





function auth( $login,  $password ) {
    global $api_arg,  $dbh;


	$stmt = $dbh->prepare('SELECT `token`, `role` FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `login`=? AND `password`=? ');
        $stmt->execute([$login,  md5($password)]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $result = ["status"=>0, "error"=>'',  "action"=>"auth", "sessionId"=>$row->token, "role"=>$row->role ];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
	    return;
	}

	$stmt = $dbh->prepare('SELECT `account_id`, `role` FROM `a_account`  WHERE `login`=? AND `password`=? ');
        $stmt->execute([$login,  md5($password)]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $token  = md5("$row->account_id" . time() ); 
    	    $stmt2 = $dbh->prepare('INSERT INTO `a_session`(`token`, `account_id`) VALUES ( ?, ? )');
	    $stmt2->execute([$token, $row->account_id]);

	    $result = ["status"=>0, "error"=>'',   "action"=>"auth", "sessionId"=>$row->token, "role"=>$row->role ];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
	    return;
	}

	$result = ["status"=>1, "error"=>'not found',  "role"=>$session_role, "action"=>"auth", "sessionId"=>'', "role"=>'' ];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function is_auth( $arg ) {
    global $api_arg,   $dbh;


	$stmt = $dbh->prepare('SELECT `login`, `role`, `a_account`.`account_id` FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
        $stmt->execute([$arg]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $result = ["status"=>0, "error"=>'',  "action"=>"auth", "sessionId"=>$arg, "role"=>$row->role, "login"=>$row->login, "accountId"=>$row->account_id ];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
	    return;
	}

	$result = ["status"=>1, "error"=>'not found',   "action"=>"auth", "sessionId"=>'', "role"=>'', "login"=>'' ];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function logout( $arg ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


	$stmt = $dbh->prepare('DELETE  FROM `a_session`   WHERE `token`=? ');
        $stmt->execute([$arg]);
	$result = ["status"=>0, "error"=>'',   "action"=>"logout", "sessionId"=>'', "role"=>'', "login"=>'' ];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function accounts_list() {
    global $api_arg,   $dbh;


    $p_list = [];
    $stmt = $dbh->prepare('SELECT `account_id`, `login`, `role`, `fullname`  FROM `a_account`  ORDER BY `login`');
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $p_list[] =  ["accountId"=>$row->account_id,  "login"=>$row->login,  "role"=>$row->role,  "fullname"=>$row->fullname  ];
    }
    $result = ["role"=>$session_role, "action"=>"accounts_list",  "list"=>$p_list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function account_detalies($arg_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $rc =  ["account_id"=>0, "login"=>'', "email"=>'',  "fullname"=>'',  "role"=>''  ];
    if($arg_id >0 ) {
	$stmt = $dbh->prepare('SELECT  `account_id`,  `email`, `login`, `role`, `fullname`  FROM `a_account`   WHERE `account_id`=?');
	$stmt->execute([$arg_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $rc =  ["account_id"=>$row->account_id, "login"=>$row->login, "email"=>$row->email,  "fullname"=>$row->fullname,  "role"=>$row->role  ];
	}
    }
    $result = ["role"=>$session_role, "action"=>"organization_detalies", "userId"=>$user_id_session, "accountInfo"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function account_save($account_id, $fullname, $login,  $email, $password0, $password1, $password2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $rc =  ["account_id"=>0  ];
    if($account_id >0 ) {
        $stmt = $dbh->prepare('UPDATE `a_account` SET  `fullname`=?, `login`=?,  `email`=?  WHERE `account_id`=?');
        $stmt->execute([$fullname, $login,  $email, $account_id ]);
        $p_arg_id = $account_id; 
    }
    else {
        $stmt = $dbh->prepare('INSERT INTO `a_account`(`fullname`, `login`,  `email`, `password`, `role`) VALUES ( ?, ?, ?, ?, ?)');
	$stmt->execute([$fullname, $login,  $email, $password1, '']);
	$p_arg_id = $dbh->lastInsertId(); 
    }

    if($account_id>0 && $password0!='' &&  $password1!='' &&  $password2!='' && $password1 ==  $password2 ) {
	$old_password = '';
	$stmt = $dbh->prepare('SELECT  `account_idpassword`  FROM `a_account`   WHERE `account_id`=?');
	$stmt->execute([$account_id ]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $old_password = $row->password;
	}
	if( $old_password == $password0 ){
    	    $stmt2 = $dbh->prepare('UPDATE `a_account` SET  `password`=?  WHERE `account_id`=?');
    	    $stmt2->execute([$account_id ]);
	}
    }

    $rc =  ["account_id"=>$p_arg_id  ];
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"organization_save", "userId"=>$user_id_session, "accountInfo"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function account_del($arg_id) {
    global $api_arg, $user_id_session, $session_role, $customer, $dbh;

    $status = "0";
    $error = '';

    if($arg_id >0) {
	$stmt = $dbh->prepare('DELETE FROM `a_organizations`  WHERE `organization_id`=?');
	$stmt->execute([$arg_id]);

	if(!$stmt) {
            $status = "4";
	    $error = 'error 2: '. $dbh->errorInfo()[2];
	}
    }

    $result = ["status"=>$status, "error"=>$error, "role"=>$session_role, "action"=>"organization_del", "customerId"=>$customer, "userId"=>$user_id_session];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




if($api_function=='auth'){
    auth($api_arg["login"], $api_arg["password"] );
}
else if($api_function=='is_auth'){
    is_auth($api_arg["sessionId"] );
}
else if($api_function=='logout'){
    logout($api_arg["sessionId"] );
}
else if($api_function=='accounts_list'){
    accounts_list();
}
else if($api_function=='account_detalies'){
    account_detalies(intval($api_arg["accountId"]));
}
else if($api_function=='account_save'){
    account_save(intval($api_arg["accountId"]), $api_arg["fullname"], $api_arg["login"],  $api_arg["email"],  $api_arg["password0"],  $api_arg["password1"],  $api_arg["password2"]);
}
else if($api_function=='account_del'){
    account_del(intval($api_arg["accountId"]));
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
