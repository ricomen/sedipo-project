<?php
/**
 * @copyright 2022
 */


//$session_id = session_id();

require_once '../../config/config-auth.php';
$dbhost_a = $cfg_auth->host;
$dbuser_a = $cfg_auth->user;
$dbpassword_a = $cfg_auth->password;
$dbname_a = $cfg_auth->name;


try {  
    $dbh_a = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
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


function auth( $login, $password,  $arg) {
    global $api_arg,  $dbh_a, $cfg_customer_id;
    global $php_sessionid, $sessionId;

    $token  = sha1($php_sessionid, false);
    $stmt = $dbh_a->query('DELETE  FROM `a_session`  WHERE `time_session`<now() - INTERVAL 1 DAY ');

    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = " AND `redirect`<>'' AND customer_id=" . $cfg_customer_id;

    if(trim($arg)!=''){
          $stmt = $dbh_a->prepare('SELECT  `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `redirect`, `billing`, `token`   FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)   LEFT JOIN `a_customer` USING(`customer_id`)   WHERE  `token`=?' . $cfg_customer_filter);
          $stmt->execute([$arg]);
          if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
              $stmt2 = $dbh_a->prepare('DELETE  FROM `a_session`  WHERE  `session_id`=? OR  `token`=? ');
              $stmt2->execute( [$php_sessionid, $arg] );
//file_put_contents("lst.txt", print_r([$php_sessionid,  $token,  $row->redirect,   intval($row->customer_id), intval($row->account_id) ], true) );

              $stmt2 = $dbh_a->prepare('INSERT INTO `a_session`(`session_id`, `token`, `account_id`  ) VALUES ( ?, ?, ? )');
              $stmt2->execute( [$php_sessionid, $token, intval($row->account_id) ] );

                  $rc = [  "customer_id"=>intval($row->customer_id), "customer_name"=>$row->customer_name, "billing"=>$row->billing, "prefix"=>$row->prefix, "redirect"=>$row->redirect, "token"=>$row->token ];
                  $result = ["status"=>0, "error"=>'',   "action"=>"auth",  "role"=>'auth', "result"=>$rc  ];
	          echo json_encode($result, JSON_UNESCAPED_UNICODE);
                  return;
          }
          $result = ["status"=>2, "error"=>'no valid key',  "role"=>'auth', "action"=>"auth", "result"=>["token"=>'']   ];
          echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    if($login!=''  && $password!='' ){
        if( filter_var($login, FILTER_VALIDATE_EMAIL) )  {
              $stmt = $dbh_a->prepare("SELECT  `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `redirect`, `billing`, `token`    FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)   LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `email`=? AND `password`=? AND `a_session`.`session_id`=? " . $cfg_customer_filter);
              $stmt->execute([$login,  md5($password), $php_sessionid ]);
        }
        else  {
              $stmt = $dbh_a->prepare("SELECT  `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `redirect`, `billing`, `token`    FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `login`=? AND `password`=? AND `a_session`.`session_id`=? " . $cfg_customer_filter);
              $stmt->execute([$login,  md5($password), $php_sessionid ]);
        }
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->token!=''  && intval($row->customer_id)>0  && intval($row->account_id)>0 ) {
                  $rc = [  "customer_id"=>intval($row->customer_id), "customer_name"=>$row->customer_name, "billing"=>$row->billing, "prefix"=>$row->prefix, "redirect"=>$row->redirect, "token"=>$row->token ];
                  $result = ["status"=>0, "error"=>'',   "action"=>"auth",  "role"=>'auth', "result"=>$rc  ];
	          echo json_encode($result, JSON_UNESCAPED_UNICODE);
                  return;
            }
	}


        if( filter_var($login, FILTER_VALIDATE_EMAIL) )  {
	     $stmt = $dbh_a->prepare('SELECT `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `redirect`, `billing`, `role_id`   FROM `a_account`   LEFT JOIN `a_customer` USING(`customer_id`) WHERE `email`=? AND `password`=? ' . $cfg_customer_filter);
             $stmt->execute([$login,  md5($password)]);
        }
        else {
	     $stmt = $dbh_a->prepare('SELECT `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `redirect`, `billing`, `role_id`   FROM `a_account`   LEFT JOIN `a_customer` USING(`customer_id`) WHERE `login`=? AND `password`=? ' . $cfg_customer_filter );
             $stmt->execute([$login,  md5($password)]);
        }
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            if( intval($row->customer_id)>0 && intval($row->account_id)>0 ) {
                $stmt2 = $dbh_a->prepare('DELETE  FROM `a_session`  WHERE  `session_id`=?  ');
                $stmt2->execute( [$php_sessionid ] );

                $stmt2 = $dbh_a->prepare('INSERT INTO `a_session`(`session_id`, `token`, `account_id`  ) VALUES ( ?, ?, ? )');
                $stmt2->execute( [$php_sessionid, $token, intval($row->account_id) ] );

	        $rc = [ "role_id"=>intval($row->role_id), "customer_id"=>intval($row->customer_id), "customer_name"=>$row->customer_name, "billing"=>$row->billing, "prefix"=>$row->prefix, "redirect"=>$row->redirect, "token"=>$token  ];
	        $result = ["status"=>0, "error"=>'',   "action"=>"auth",  "role"=>'auth', "result"=>$rc  ];
	        echo json_encode($result, JSON_UNESCAPED_UNICODE);
	        return;
            }
	}
     }
     $result = ["status"=>1, "error"=>'not found',  "role"=>'auth', "action"=>"auth", "result"=>["token"=>'']   ];
     echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function is_auth( $arg ) {
    global $api_arg,   $dbh_a, $cfg_customer_id;
    global $php_sessionid, $sessionId;

    $cfg_customer_filter = '';
    if($cfg_customer_id>0)
          $cfg_customer_filter = " AND `redirect`<>''  AND customer_id=" . $cfg_customer_id;

	$stmt = $dbh_a->query('DELETE  FROM `a_session`  WHERE `time_session`<now() - INTERVAL 1 DAY ');

        $stmt = $dbh_a->prepare('SELECT `login`, `a_account`.`account_id`,  `a_customer`.`customer_id`, `a_customer`.`name`  as `customer_name`, `prefix`, `billing`, `session_id`, `token`, `role_id`, `fullname`    FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)    LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `token`=? ' . $cfg_customer_filter);
        $stmt->execute([$arg]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $stmt2 = $dbh_a->prepare('UPDATE `a_session` SET  `time_session`=now() WHERE `token`=? ');
            $stmt2->execute([$row->token]);

            $rc = [ "role"=>$row->role_name, "role_id"=>$row->role_id, "login"=>$row->login, "account_id"=>$row->account_id, "customer_id"=>intval($row->customer_id), "customer_name"=>$row->customer_name, "billing"=>$row->billing, "prefix"=>$row->prefix, "token"=>$row->token,  "fullname"=>$row->fullname  ];
            $result = ["status"=>0, "error"=>'',   "action"=>"auth",  "role"=>'auth', "result"=>$rc  ];
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
	    return;
	}

	$result = ["role"=>$session_role,   "status"=>1, "error"=>'not found',   "action"=>"auth", "result"=>[]  ];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function logout( $arg ) {
    global $api_arg, $user_id_session, $session_role,  $dbh_a;
    global $php_sessionid, $sessionId;

	$stmt = $dbh_a->query('DELETE  FROM `a_session`  WHERE `time_session`<now() - INTERVAL 1 DAY ');

	$stmt = $dbh_a->prepare('DELETE  FROM `a_session`   WHERE `token`=? ');
        $stmt->execute([$arg]);

	$stmt = $dbh_a->prepare('DELETE  FROM `a_session`   WHERE `session_id`=? ');
        $stmt->execute([$php_sessionid]);

        session_regenerate_id();

	$result = ["status"=>0, "error"=>'',   "action"=>"logout", "sessionId"=>'', "role"=>'', "role_id"=>0, "login"=>'' ];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function update_mascotSession( $arg ) {
    global $api_arg, $dbh;
        $stmt = $dbh->prepare('UPDATE `a_session` SET  `mascotHome`=1 WHERE `token`=? ');
        $stmt->execute([$arg]);
        return;
}



$session_account_id = -1;
$session_role = '';
/*
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `role_id`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}
*/
$php_sessionid = str_replace("'\'\\\n\r ", '', $_COOKIE['PHPSESSID'] );
$sessionId = $api_arg["sessionId"];

if($api_function=='auth'){
    auth(strip_tags($api_arg["login"]), strip_tags($api_arg["password"]), str_replace("'\'\\\n\r ", '', $api_arg["sessionId"]));
}
else if($api_function=='is_auth'){
    is_auth(str_replace("'\'\\\n\r ", '', $api_arg["sessionId"]) );
}
else if($api_function=='logout'){
    logout($api_arg["sessionId"] );
}

//else if($api_function=='info'){
//    info();
//}





else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
