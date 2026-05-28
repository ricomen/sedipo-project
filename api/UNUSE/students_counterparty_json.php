<?php
/**
 * @copyright 2022
 */


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

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


require_once 'students_lib.php';




function user_c_save($user_id,   $counterparty_id, $job_title_id_arg, $job_title_name, $job_title_category,  $subdivision,  ) {

    global $api_arg, $user_id_session, $session_role, $EmailDomain, $AccountPrefix, $dbh;


    if($user_id==1){
	$result = ["status"=>1, "error"=>'Эту запись нельзя редактировать',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>0]];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
        return;
    }
    
    $job_title_name = trim($job_title_name);
    
    $job_title_id = 0;
    if(trim($job_title_name) != ''){
	$count_p = 0;
	$stmt = $dbh->prepare("select `job_title_id` FROM  `a_job_title`  WHERE `name`=?   ");
        $stmt->execute([trim($job_title_name)]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $count_p = 1;
	    $job_title_id = intval($row->job_title_id);
	}
	if($count_p == 0 && trim($job_title_name) != '') {
	    $stmt = $dbh->prepare("INSERT INTO `a_job_title`(`name`) VALUES(?)");
    	    $stmt->execute([trim($job_title_name)]);
	    $job_title_id = intval($dbh->lastInsertId() ); 
	}

    }

    $c_user_id = 0;
    $stmt = $dbh->prepare('SELECT count(*) as `count`  FROM `a_user_counterparty` WHERE `user_id`=? AND `counterparty_id`=? AND `job_title_id`=? ');
    $stmt->execute([$user_id, $counterparty_id, $job_title_id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
          $c_user_id = $row->count;
    }
    if($c_user_id>0 && $job_title_id>0){
        $stmt = $dbh->prepare('UPDATE `a_user_counterparty` SET  `counterparty_id`=?,  `subdivision`=?, `job_title_id`=?, `job_title_category`=?   WHERE `user_id`=? ');
        $stmt->execute([ $counterparty_id, trim($subdivision), $job_title_id, $job_title_category,  $user_id ]);

        $p_user_id = $user_id; 
    }
    else if($job_title_id>0) {
            $stmt = $dbh->prepare('INSERT INTO `a_user_counterparty`( `user_id`,  `counterparty_id`,  `job_title_id`, `subdivision` ) VALUES ( ?, ?, ?, ?  )');
	    $stmt->execute([$user_id,  $counterparty_id, $job_title_id, $subdivision]);
	    $p_user_id = $dbh->lastInsertId(); 
	    if($p_user_id<=0){
	        $result = ["status"=>2, "error"=>'Ошибка DB',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>0]];
	        echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;
           }

    }

    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "result"=>["user_id"=>$p_user_id] ];
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



if($api_function=='save' ){
    user_c_save(intval($api_arg["objectId"]),  intval($api_arg["counterparty_id"]), intval($api_arg["job_title_id"]), htmlspecialchars($api_arg["job_title_name"], ENT_QUOTES), intval($api_arg["job_title_category"]), htmlspecialchars($api_arg["subdivision"], ENT_QUOTES),  );

}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
