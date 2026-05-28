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



function teacher_items($user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $users_list = [];
    $counterparty_id = 0;
    //$rc_list[] = ["userId"=>0, "login"=>'', "name"=>'', "fullname"=>''];

    if($user_id >0 ) {
	    $course_list = [];
	    $stmt = $dbh->prepare('SELECT DISTINCT `course_id`, `category_id`, `priority`, `name` FROM `a_teacher_course` LEFT JOIN `a_course_category` USING(`category_id`)   WHERE `user_id`=?  ORDER BY `priority` ');
    	$stmt->execute([$user_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	      $shortname = '';
	      if($row->course_id > 0) {
	        $stmt2 = $dbh->prepare('SELECT DISTINCT  `name`, `shortname`,    `main_module`  FROM  `a_course`   WHERE `course_id`=?  ');
    	    $stmt2->execute([$row->course_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	            $shortname = $row2->shortname;
	            if($shortname == '')
	                    $shortname = $row2->name;
	                    
	        }                 
          }
		  $course_list[] = [ "course_id"=>$row->course_id, "category_name"=>$row->name, "category_id"=>$row->category_id, "shortname"=>$shortname, "priority"=>$row->priority  ];
	    }
    }

    $result = ["role"=>$session_role, "action"=>"items",  "list"=>$course_list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function teacher_search($course_id, $search_str ){
   global $api_arg, $user_id_session, $session_role,  $dbh;

    $teacher_list = [];
    //$stmt = $dbh->prepare('SELECT DISTINCT `a_teacher_course`.`user_id`, `a_teacher_course`.`priority`, `lastname`, `firstname`, `middlename`   FROM `a_teacher_course` LEFT JOIN `a_course` USING(`category_id`) LEFT JOIN `a_teacher` USING(`user_id`)   WHERE (`a_teacher_course`.`course_id`=?  OR `a_course`.`course_id`=?) AND `lastname` LIKE ?  ORDER BY `a_teacher_course`.`priority` ');
    $stmt = $dbh->prepare('SELECT DISTINCT `a_teacher_course`.`user_id`, `a_teacher_course`.`priority`, `lastname`, `firstname`, `middlename`   FROM `a_teacher_course` LEFT JOIN `a_course` USING(`category_id`) LEFT JOIN `a_teacher` USING(`user_id`)   WHERE (`a_teacher_course`.`course_id`=?  OR `a_course`.`course_id`=?) AND `lastname` LIKE ?  ORDER BY `a_teacher_course`.`priority` LIMIT 25 ');
    $stmt->execute([ $course_id, $course_id, sqlspecialchars($search_str)."%" ]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $teacher_list[] = [ "user_id"=>$row->user_id,  "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename ];
    }
    $result = ["role"=>$session_role, "action"=>"search",  "list"=>$teacher_list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function  teacher_course($user_id, $category_id, $course_id, $priority) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $user_check_arr = explode(',', $user_check); 
    $check_id_arr = explode(',', $check_id); 
    $k = 0;

    if($user_id >0 && $category_id>0 ) {

        $stmt = $dbh->prepare('DELETE FROM  `a_teacher_course` WHERE  `user_id`=? AND `category_id`=? AND `course_id`=? ');
        $stmt->execute([$user_id, $category_id, $course_id ]);

        $stmt = $dbh->prepare('INSERT  INTO  `a_teacher_course`(`user_id`, `category_id`, `course_id`, `priority`) VALUES ( ?, ?, ?, ? )');
        $stmt->execute([$user_id, $category_id, $course_id, $priority ]);
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course",  "result"=>$k ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function  teacher_course_del($user_id, $category_id, $course_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($user_id >0 && $category_id>0 ) {

        $stmt = $dbh->prepare('DELETE FROM  `a_teacher_course` WHERE  `user_id`=? AND `category_id`=? AND `course_id`=? ');
        $stmt->execute([$user_id, $category_id, $course_id ]);

    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course_del",  "result"=>$order_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function  sqlspecialchars($str){
   return  str_replace( '\'"\\%', '', $str);

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

if($api_function=='items' && intval($api_arg["objectId"])>0 ){
    teacher_items(intval($api_arg["objectId"]) );
}
else if($api_function=='search' && intval($api_arg["course_id"])>0 ){
    teacher_search(intval($api_arg["course_id"]), htmlspecialchars($api_arg["search"], ENT_QUOTES) );
}
else if($api_function=='user_course'  && intval($api_arg["objectId"])>0 && intval($api_arg["category_id"])>0   ){
    teacher_course(intval($api_arg["objectId"]), intval($api_arg["category_id"]), intval($api_arg["course_id"]), intval($api_arg["priority"]) );
}
else if($api_function=='user_course_del'  && intval($api_arg["objectId"])>0  && intval($api_arg["category_id"])>0  ){
    teacher_course_del(intval($api_arg["objectId"]), intval($api_arg["category_id"]), intval($api_arg["course_id"]) );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

