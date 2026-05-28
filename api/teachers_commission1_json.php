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



function teachers_commission_object($commission_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $list = [];

    if($user_id >0 ) {
	    $course_list = [];
	    $stmt = $dbh->prepare('SELECT `course_id`, `category_id`, `priority`, `name` FROM `teachers_commission_teacher`   WHERE `commission_id`=?  ORDER BY `n` ');
            $stmt->execute([$commission_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	                    $shortname = $row2->name;
	                    
		  $course_list[] = [ "course_id"=>$row->course_id, "category_name"=>$row->name, "category_id"=>$row->category_id, "shortname"=>$shortname, "priority"=>$row->priority  ];
	    }
    }

    $result = ["role"=>$session_role, "action"=>"items",  "list"=>$course_list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function  _del($user_id, $category_id, $course_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($user_id >0 && $category_id>0 ) {

        $stmt = $dbh->prepare('DELETE FROM  `a_teacher_course` WHERE  `user_id`=? AND `category_id`=? AND `course_id`=? ');
        $stmt->execute([$user_id, $category_id, $course_id ]);

    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course_del",  "result"=>$order_id ];
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

api_journal('order', $api_function, $api_arg, $session_login);
if($api_function=='object' && intval($api_arg["objectId"])>0 ){
    teachers_commission_object(intval($api_arg["objectId"]) );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

