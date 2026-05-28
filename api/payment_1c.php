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



$api_function = '';
$api_arg = [];

session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
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



function payment_1c() {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    
    $dir_1c = glob("../../1c/1C/payment_*.{json}", GLOB_BRACE);
    foreach ($dir_1c as $filename) {
        $json_data = file_get_contents($filename); 
        if ($json_data === false)
                continue;

        $json_array = json_decode( mb_substr($json_data, 1), true); 
        $order_id = intval($json_array['id']);
        if($order_id == 0)
                continue;
        
        $payment_date = explode("T", $json_array['paymentDate'])[0];
        $payment_receipt = $json_array['paymentАmount'];
//print_r($json_array);    

        $stmt = $dbh->prepare('UPDATE `a_order` SET `payment_receipt`=?, `payment_receipt_date`=?  WHERE `order_id`=?');
        $stmt->execute([ $payment_receipt, $payment_date, $order_id ]);

        $stmt = $dbh->prepare('UPDATE `a_order` SET `status_id`=?  WHERE `order_id`=? AND (`status_id`=2  OR `status_id`=3)');
        $stmt->execute([ 6, $order_id ]);
        
        //echo("../../1c/1C/" . basename($filename));
        unlink("../../1c/1C/" . basename($filename));
    }        
}



payment_1c();

$result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"1c",  "result"=>'' ];
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
