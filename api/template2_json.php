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



function template_html($template_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $html = '';
    $stmt = $dbh->prepare('SELECT  `file`  FROM `a_template`    WHERE `template_id`=?   ');
    $stmt->execute([ $template_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      if($row->file !='' ) {
            $html = file_get_contents('../documents/' . $row->file);
        }                 
    }

    $result = ["role"=>$session_role, "action"=>"template_html",  "result"=>$html ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function html_save($template_id, $html ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $stmt = $dbh->prepare('SELECT  `file`, `wysiwyg`  FROM `a_template`    WHERE `template_id`=?   ');
    $stmt->execute([ $template_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      if($row->file !='' ) {
            file_put_contents( '../documents/' . $row->file, $html );
// file_put_contents('lst.txt', '../documents/' . $row->file );
// file_put_contents('lst2.txt', $html );
        }                 
    }

    $result = ["role"=>$session_role, "action"=>"html_save",  "result"=>'' ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function footer_html($template_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $html = '';
    $stmt = $dbh->prepare('SELECT  `footer_file`  FROM `a_template`    WHERE `template_id`=?   ');
    $stmt->execute([ $template_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      if($row->footer_file !='' ) {
            $html = file_get_contents('../documents/' . $row->footer_file);
        }
    }

    $result = ["role"=>$session_role, "action"=>"template_html",  "result"=>$html ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function footer_save($template_id, $html ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $stmt = $dbh->prepare('SELECT    `file` , `footer_file`  FROM `a_template`    WHERE `template_id`=?   ');
    $stmt->execute([ $template_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      if($row->file !='' ) {
            if($row->footer_file  =='' ||  $row->footer_file  == $row->file) {
                 $filename_a = explode('.', $row->file, 2);
                 $footer_file = $filename_a[0] . '_footer_'. '.' . $filename_a[1];

                 $stmt2 = $dbh->prepare('UPDATE `a_template` SET  `footer_file`=?     WHERE `template_id`=?   ');
                 $stmt2->execute([$footer_file,  $template_id ]);
            }
            else {
                $footer_file = $row->footer_file;
            }
            file_put_contents( '../documents/' . $footer_file, $html );
        }                 
    }

    $result = ["role"=>$session_role, "action"=>"html_save",  "result"=>'' ];
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
if($api_function=='template_html' && intval($api_arg["objectId"])>0 ){
    template_html(intval($api_arg["objectId"]) );
}
else if($api_function=='html_save' && intval($api_arg["objectId"])>0 ){
    html_save(intval($api_arg["objectId"]), $api_arg["template_html"] );
}
else if($api_function=='footer_html' && intval($api_arg["objectId"])>0 ){
    footer_html(intval($api_arg["objectId"]) );
}
else if($api_function=='footer_save' && intval($api_arg["objectId"])>0 ){
    footer_save(intval($api_arg["objectId"]), $api_arg["template_html"] );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

