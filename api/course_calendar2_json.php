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





function save($topic_id, $topic, $course_id, $variation,  $type, $name_topic, $hours, $cohort_id, $num  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    //$stmt = $dbh->prepare('DELETE FROM `a_course_calendar` WHERE  `course_id`=?  ');
    //$stmt->execute([ $course_id, $variation, $topic_id`, $type, $topic, $name_topic, $hours, $cohort_id, $num ]);


    if($topic_id>0){
        $stmt = $dbh->prepare('DELETE FROM `a_course_calendar` WHERE  `topic_id`=?  ');
        $stmt->execute([ $topic_id ]);

        $stmt = $dbh->prepare('INSERT INTO `a_course_calendar`(`course_id`, `variation`, `topic_id`, `type`,  `topic`, `name_topic`, `hours`, `cohort_id`, `num`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ? )  ');
        $stmt->execute([ $course_id, $variation, $topic_id, $type, $topic, $name_topic, $hours, $cohort_id, $num ]);
    }
    else{
        $num = 1;
//file_put_contents("lst.txt", print_r( [$course_id, $variation,  $type, $topic, $name_topic, $hours, $cohort_id, $num], true) );
        $stmt = $dbh->prepare('INSERT INTO `a_course_calendar`(`course_id`, `variation`, `type`,  `topic`, `name_topic`, `hours`, `cohort_id`, `num` ) VALUES( ?, ?, ?, ?, ?, ?, ?, ? )  ');
        $stmt->execute([ $course_id, $variation,  $type, $topic, $name_topic, $hours, $cohort_id, $num ]);
    }
    $result = ["role"=>$session_role, "action"=>"calendar_save",  "result"=>'' ];
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

if($api_function=='save2'  ){
    save( intval($api_arg["objectId"]), $api_arg["topic"], intval($api_arg["course_id"]),  intval($api_arg["variation"]), intval($api_arg["type"]), htmlspecialchars($api_arg["name_topic"], ENT_QUOTES), intval($api_arg["hours"]), intval($api_arg["cohort_id"]), intval($api_arg["num"]) );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

