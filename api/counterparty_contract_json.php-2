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



function contract_list($counterparty_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $list = [];
	//$stmt = $dbh->prepare("SELECT  `contract_id`, `date_contract`, `order_id`, `legacy`, `name`  FROM `a_contract` INNER JOIN `a_counterparty_contract` USING(`contract_id`)   WHERE `counterparty_id`=? AND `type`=0 AND `longtime_contract`=1 ORDER BY `contract_id`  ");
    $stmt = $dbh->prepare("SELECT  `longtime_contract`   FROM `a_counterparty`  WHERE  `counterparty_id`=?  ");
    $stmt->execute([$counterparty_id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        if($row->longtime_contract !='true'){
            $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list   ];
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            return;    
        }
    }

    $stmt = $dbh->prepare("SELECT  `contract_id`, `name`, `prefix`   FROM `a_contract`  WHERE  `type`=0 AND `longtime_contract`=1 ORDER BY `contract_id`  ");
    $stmt->execute([]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $date_contract = '';
        $legacy = '';
        $upload_file = '';
        $upload_dir =  '';
        $enterprise_manager = '';
        $enterprise_manager2 = '';
        $enterprise_manager_signs = '';
        $contract_html_length = 0;
        $stmt2 = $dbh->prepare("SELECT `date_contract`,  `legacy`, `upload_file`, `upload_dir`, LENGTH(`contract_html`) as `contract_html_length`  FROM  `a_counterparty_contract`   WHERE `order_id`=0 AND  `counterparty_id`=? AND `contract_id`=?  AND `addition`=-1   ");
        $stmt2->execute([$counterparty_id, $row->contract_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $date_contract = $row2->date_contract;
            $legacy = $row2->legacy;
            $upload_file = $row2->upload_file;
            $upload_dir = $row2->upload_dir;
            $contract_html_length = $row2->contract_html_length;
        }
        $list[] =  ["contract_id"=>$row->contract_id, "name"=>$row->name, "prefix"=>$row->prefix, "date_contract"=>$date_contract, "legacy"=>$legacy, "upload_file"=>$upload_file, "upload_dir"=>$upload_dir, "contract_html_length"=>$contract_html_length ];
    }

    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list   ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function contract_object($contract_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $rc =  ["contract_id"=>0,  "date_contract"=>''  ];
    if($contract_id >0 ) {

	        $rc =  ["contract_id"=>$row->contract_id,    ];
   }
    
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function contract_save($counterparty_id,  $date_contract, $legacy, $a_contract_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($counterparty_id >0 ) {
        for($i=0; $i<count($a_contract_id); $i++) {
            if(intval($a_contract_id[$i])<=0 || $date_contract[$i]=='' || $date_contract[$i]=='1000-01-01' )
                    continue;

            $contract_count = 0;
            $stmt = $dbh->prepare("SELECT count(*) as `count`  FROM  `a_counterparty_contract`   WHERE `counterparty_id`=? AND `contract_id`=? AND `addition`=-1  ");
            $stmt->execute([ $counterparty_id, $a_contract_id[$i] ]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $contract_count = $row->count;
            }
            
            if($contract_count > 0){
                $stmt = $dbh->prepare("DELETE FROM `a_counterparty_contract`   WHERE  `addition`=-1  AND `order_id`>0  AND  `counterparty_id`=? AND `contract_id`=?");
                $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);

                $stmt = $dbh->prepare('UPDATE  `a_counterparty_contract` SET `date_contract`=?, `legacy`=?  WHERE  `addition`= -1  AND `order_id`=0  AND   `counterparty_id`=? AND  `contract_id`=? ');
                $stmt->execute([$date_contract[$i], $legacy[$i],  $counterparty_id, $a_contract_id[$i] ]);

                if($date_contract[$i]== '' || $date_contract[$i]== '1000-01-01' || $date_contract[$i]== NULL ){
	               $stmt = $dbh->prepare('SELECT `upload_file`, `upload_dir`  FROM `a_counterparty_contract`  WHERE `counterparty_id`=? AND `contract_id`=?');
		       $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);
                       if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                            if($row->upload_file != '')
                                unlink($path . $row->upload_dir . $row->upload_file);
                    }
        	    $stmt = $dbh->prepare("DELETE FROM `a_counterparty_contract`   WHERE  `addition`= -1   AND  `counterparty_id`=? AND `contract_id`=?");
        	    $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);
                }
            }
            else if($date_contract[$i] != '1000-01-01' && $date_contract[$i]!=''){
                $stmt = $dbh->prepare('INSERT INTO `a_counterparty_contract`(`counterparty_id`, `contract_id`, `date_contract`, `legacy`,  `contract_html`,  `addition`  ) VALUES( ?, ?, ?, ?, ?, -1 ) ');
                $stmt->execute([ $counterparty_id, $a_contract_id[$i], $date_contract[$i], $legacy[$i], ''  ]);
            }
        }
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$i ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function order_contract_save($order_id, $counterparty_id,  $date_contract, $legacy, $a_contract_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($counterparty_id >0 ) {
        $stmt = $dbh->prepare("SELECT  `longtime_contract`   FROM `a_counterparty`  WHERE  `counterparty_id`=?  ");
        $stmt->execute([$counterparty_id]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                if($row->longtime_contract =='true'){
                    $counterparty_longtime_contract = 1;
                }
        }

        for($i=0; $i<count($a_contract_id); $i++) {
            if(intval($a_contract_id[$i])<=0 || $date_contract[$i]==''  || $date_contract[$i]=='1000-01-01' )
                    continue;

            $longtime_contract = 0;
            $contract_order_id = $order_id;
            $stmt = $dbh->prepare("SELECT  `longtime_contract`   FROM `a_counterparty`  WHERE  `counterparty_id`=?  ");
            $stmt->execute([$counterparty_id]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                if($row->longtime_contract =='true'){
                    $counterparty_longtime_contract = 1;
                     $stmt2 = $dbh->prepare("SELECT `prefix`, `name`, `longtime_contract` FROM `a_contract` WHERE `contract_id`=? AND  `type`=0 ");
                     $stmt2->execute( [ intval($v_contract) ] );
                     if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                          if( intval($row2->longtime_contract) >0 ) {
                                $longtime_contract = 1;
                                $contract_order_id = 0;
                          }
                     }
                }
            }

            $contract_count = 0;
            if( $longtime_contract == 1){
                $stmt = $dbh->prepare("SELECT count(*) as `count`  FROM  `a_counterparty_contract`   WHERE `counterparty_id`=? AND `contract_id`=? AND `addition`=-1 AND `order_id`=0 ");
                $stmt->execute([ $counterparty_id, $a_contract_id[$i] ]);
            }
            else{
                $stmt = $dbh->prepare("SELECT count(*) as `count`  FROM  `a_counterparty_contract`   WHERE `counterparty_id`=? AND `contract_id`=? AND `addition`=-1 AND `order_id`=? ");
                $stmt->execute([ $counterparty_id, $a_contract_id[$i], $order_id ]);
            }
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $contract_count = $row->count;
            }
            
            if($contract_count > 0){
                if( $longtime_contract == 1){
                      $stmt = $dbh->prepare("DELETE FROM `a_counterparty_contract`   WHERE  `addition`=-1  AND `order_id`=?  AND  `counterparty_id`=? AND `contract_id`=?");
                      $stmt->execute([ $order_id, $counterparty_id, $a_contract_id[$i] ]);
                }
                else {
                      $stmt = $dbh->prepare("DELETE FROM `a_counterparty_contract`   WHERE  `addition`=-1  AND `order_id`=0  AND  `counterparty_id`=? AND `contract_id`=?");
                      $stmt->execute([ $counterparty_id, $a_contract_id[$i] ]);
                }

//file_put_contents("lst1.txt", print_r([$date_contract[$i], $legacy[$i],  $counterparty_id, $a_contract_id[$i]], true) );

                $stmt = $dbh->prepare('UPDATE  `a_counterparty_contract` SET `date_contract`=?, `legacy`=?  WHERE  `addition`= -1  AND `order_id`=?  AND   `counterparty_id`=? AND  `contract_id`=? ');
                $stmt->execute([$date_contract[$i], $legacy[$i], $contract_order_id,  $counterparty_id, $a_contract_id[$i] ]);

                if($date_contract[$i]== '' || $date_contract[$i]== '1000-01-01' || $date_contract[$i]== NULL ){
	               $stmt = $dbh->prepare('SELECT `upload_file`, `upload_dir`  FROM `a_counterparty_contract`  WHERE `counterparty_id`=? AND `contract_id`=?');
		       $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);
                       if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                            if($row->upload_file != '')
                                unlink($path . $row->upload_dir . $row->upload_file);
                    }
        	    $stmt = $dbh->prepare("DELETE FROM `a_counterparty_contract`   WHERE  `addition`= -1   AND  `counterparty_id`=? AND `contract_id`=?  AND `order_id`=? ");
        	    $stmt->execute([$counterparty_id, $a_contract_id[$i], $contract_order_id ]);
//file_put_contents("lst.txt", print_r($stmt,  true) );
                }
                
            }
            else if($date_contract[$i] != '1000-01-01' && $date_contract[$i]!=''){
//file_put_contents("lst1.txt", print_r([$date_contract[$i], $legacy[$i],  $counterparty_id, $a_contract_id[$i]], true) );
                $stmt = $dbh->prepare('INSERT INTO `a_counterparty_contract`(`counterparty_id`, `contract_id`, `date_contract`, `legacy`, `order_id`,  `contract_html`,  `addition`  ) VALUES( ?, ?, ?, ?, ?, ?,  -1 ) ');
                $stmt->execute([ $counterparty_id, $a_contract_id[$i], $date_contract[$i], $legacy[$i], $contract_order_id, ''  ]);
            }
        }
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$i ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





function contract_create( $counterparty_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $session_account_id;

    $status = 1; // Новая заявка
    $contract_id = 0;
    
   

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"insert",  "result"=>$contract_id ];
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


if($api_function=='list'){

    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;

    contract_list($counterparty_id );
}
else if($api_function=='object'){
    contract_object(intval($api_arg["objectId"]));
}
else if($api_function=='update'){
    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;

    contract_save($counterparty_id,  $api_arg["date_contract"], $api_arg["legacy_contract"], $api_arg["a_contract_id"] );
}
else if($api_function=='update_order_contract'){
    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;

    order_contract_save(intval($api_arg["order_id"]), $counterparty_id,  $api_arg["date_contract"], $api_arg["legacy_contract"], $api_arg["a_contract_id"] );
}
else if($api_function=='insert'){
    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;
        
    contract_create($counterparty_id, $api_arg["date_order"],  $api_arg["date_begin"], $api_arg["date_end"], intval($api_arg["payer_id"]), $api_arg["balance_pay"] );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

