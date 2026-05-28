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



function contract_upload( $counterparty_id, $contract_id, $addition ) {
    global $api_arg, $user_id_session,   $dbh;
    
//file_put_contents("lst1.txt", json_encode($_FILES, JSON_UNESCAPED_UNICODE));

    $rc=0;
    if(!empty($_FILES['upload1']['tmp_name']) )
    {
        $longtime_contract_is_signed = 0;
        $longtime_contract = 0;
        $longtime_contract_c = '';
        $new_contract_id = $contract_id;

        $stmt = $dbh->prepare("SELECT  `name`,`longtime_contract`  FROM `a_counterparty`  WHERE  `counterparty_id`=? ");
        $stmt->execute([$row->counterparty_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $longtime_contract_c =  $row->longtime_contract;
        }

        $stmt = $dbh->prepare('SELECT DISTINCT   `a_contract`.`name`, `template_id`, `template1_id`, `template2_id`,`template3_id`, `longtime_contract`  FROM   `a_contract`   WHERE `contract_id`=?   ');
        $stmt->execute([ $contract_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
    	            if($row4->template3_id > 0 )
	                    $additions_count = 3;
    	            else if($row4->template2_id > 0 )
	                    $additions_count = 2;
	            else if($row4->template1_id > 0 )
	                    $additions_count = 1;
	            else if($row4->template_id > 0 )
	                    $additions_count = 0;
    	            else
	                    $additions_count = -1;

	            if( $longtime_contract_c =='true' && $row->longtime_contract>0  ){
                              $longtime_contract = 1;
                              $new_contract_id = 0;
                    }
        }

        $path = "../uploads/contracts/";
        $file_name = $counterparty_id. '_' .$contract_id;
        $upload_dir = "/uploads/contracts/";
        
        $stmt = $dbh->prepare('SELECT `upload_file`, `upload_dir`, `date_contract`  FROM `a_counterparty_contract`  WHERE `counterparty_id`=?  AND `contract_id`=?');
        $stmt->execute([$counterparty_id, $contract_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                if($row->date_contract!='' &&  $row->date_contract!='1000-01-01' &&  $row->date_contract!='0000-00-00' && $row->upload_file!='' && $row->upload_dir!='' )
                    $longtime_contract_is_signed = 1;

        	if($row->upload_file != '')
                     unlink($path . $row->upload_dir . $row->upload_file);
        }
//file_put_contents( 'lst.txt', json_encode($path. $file_name, JSON_UNESCAPED_UNICODE));        
        if(move_uploaded_file($_FILES['upload1']['tmp_name'], $path. $file_name .'.pdf')) {
            $rc=1;
        }
        $stmt = $dbh->prepare('UPDATE `a_counterparty_contract` SET `upload_file`=?, `upload_dir`=?  WHERE `counterparty_id`=? AND `contract_id`=?');
        $stmt->execute([$file_name .'.pdf', $upload_dir,  $counterparty_id, $contract_id ]);
            //$stmt2 = $dbh->prepare("INSERT INTO `a_counterparty_contract`( `counterparty_id`, `contract_id`, `addition`, `date_contract`, `order_id`, `upload_file`, `upload_dir`, `legacy`, `enterprise_manager`, `enterprise_manager2`, `enterprise_manager_signs`) VALUES(?, ?, ?, ?, ?, ?, ?, '', ?, ?, ?)   ");
            //$stmt2->execute([$counterparty_id, $contract_id, $addition, $date_contract, $order_id, $file_name.'.pdf', $upload_dir, $enterprise_manager, $enterprise_manager2, $enterprise_manager_signs  ]);
    }    
    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc, "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


if(intval($_POST["counterparty_id"])!=0 && intval($_POST["contract_id"])!=0 && intval($_POST["addition"])!=0 &&  $_FILES){
     contract_upload( intval($_POST["counterparty_id"]), intval($_POST["contract_id"]), intval($_POST["addition"])  ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


?>
