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

session_start();



function contract_upload( $counterparty_id, $contract_id, $date_contract, $legacy ) {
    global $api_arg, $user_id_session,   $dbh;
    
//file_put_contents("lst1.txt", json_encode($_FILES, JSON_UNESCAPED_UNICODE));

    $rc=0;
    if(!empty($_FILES['upload1']['tmp_name']) )
    {
        $path = "../uploads/contracts/";
        $file_name = $counterparty_id. '_' .$contract_id;
        //$upload_dir = "/uploads/contracts/";
        $upload_dir = "contracts/";
        
        $stmt = $dbh->prepare('SELECT `upload_file`, `upload_dir`  FROM `a_counterparty_contract`  WHERE `counterparty_id`=? AND `contract_id`=?  AND `addition`=-1');
        $stmt->execute([$counterparty_id, $contract_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        	if($row->upload_file != '')
                     unlink($path . $row->upload_dir . $row->upload_file);
        }
        else {
                $stmt = $dbh->prepare('INSERT INTO `a_counterparty_contract`(`counterparty_id`, `contract_id`, `date_contract`,  `legacy`, `contract_html`, `addition` ) VALUES( ?, ?, ?, ?, ?, -1 ) ');
                $stmt->execute([ $counterparty_id, $contract_id, $date_contract, $legacy,  ''  ]);
        }
//file_put_contents( 'lst.txt', json_encode($path. $file_name, JSON_UNESCAPED_UNICODE));        
        if(move_uploaded_file($_FILES['upload1']['tmp_name'], $path. $file_name .'.pdf')) {
            $rc=1;
        }
        $stmt = $dbh->prepare('UPDATE `a_counterparty_contract` SET `upload_file`=?, `upload_dir`=?  WHERE `counterparty_id`=? AND `contract_id`=?  AND `addition`=-1');
        $stmt->execute([$file_name .'.pdf', $upload_dir,  $counterparty_id, $contract_id ]);
            //$stmt2 = $dbh->prepare("INSERT INTO `a_counterparty_contract`( `counterparty_id`, `contract_id`, `addition`, `date_contract`, `order_id`, `upload_file`, `upload_dir`, `legacy`, `enterprise_manager`, `enterprise_manager2`, `enterprise_manager_signs`) VALUES(?, ?, ?, ?, ?, ?, ?, '', ?, ?, ?)   ");
            //$stmt2->execute([$counterparty_id, $contract_id, $addition, $date_contract, $order_id, $file_name.'.pdf', $upload_dir, $enterprise_manager, $enterprise_manager2, $enterprise_manager_signs  ]);
    }    
    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc, "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if(intval($_POST["counterparty_id"])!=0 && intval($_POST["contract_id"])!=0 &&  $_FILES){
     contract_upload( intval($_POST["counterparty_id"]), intval($_POST["contract_id"]), $_POST["date_contract"],  $_POST["legacy"] ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


?>
