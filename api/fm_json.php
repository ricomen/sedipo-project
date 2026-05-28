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



session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}



function  fm_mkdir($parent_directory, $filemanager_directory) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;
    
    $fm_dir = $_SERVER['DOCUMENT_ROOT'].'/files/'.$parent_directory.'/'.$filemanager_directory;
    if(! is_dir($fm_dir) )
            mkdir($fm_dir);
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



if($api_function=='mkdir'){
    fm_mkdir($api_arg["parent_directory"], $api_arg["filemanager_directory"] );
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>



