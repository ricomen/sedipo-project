<?php
/**
 * @copyright 2022
 */


//$session_id = session_id();

require_once '../config/config-auth.php';
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

//file_put_contents("lst.txt", print_r([$login], true) );


function login_info( ) {
    global $api_arg,   $dbh;
    global $php_sessionid;

        $stmt = $dbh->prepare('SELECT `login`,  `a_account`.`account_id`, `fullname`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `role_name`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  LEFT JOIN  `a_role` USING(`role_id`)  LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `session_id`=? ');
        $stmt->execute([$php_sessionid]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  

            $rc = [ "role"=>$row->role_name,  "login"=>$row->login, "account_id"=>$row->account_id, "fullname"=>$row->fullname,  "customer_id"=>intval($row->customer_id), "customer_name"=>$row->customer_name   ];
            echo json_encode($rc, JSON_UNESCAPED_UNICODE);
	    return;
	}

	$result = [ "role"=>'',  "login"=>'', "account_id"=>0, "fullname"=>'',  "customer_id"=>0, "customer_name"=>''   ];;
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

$php_sessionid = str_replace("'\'\\\n\r ", '', $_COOKIE['PHPSESSID'] );
login_info( );

?>
