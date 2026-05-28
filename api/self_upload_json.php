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



function self_upload( $edition_id ) {
    global $api_arg, $user_id_session,   $dbh;

    $rc=0;
    if(!empty($_FILES['upload1']) )
    {
        $logo_path = "../uploads/images/";
        $file_name  = 'login-logo.png';
        $file2_name = 'logo.png';
        $upload_dir = "uploads/images/";
        
        if(move_uploaded_file($_FILES['upload1']['tmp_name'], $logo_path . $file_name )) {
            copy($logo_path . $file_name, $logo_path . $file2_name);
            $rc=1;

        }
    }    

    if(!empty($_FILES['upload3']) )
    {
        $path = "../documents/self/manager";
        $file_name = $edition_id . 'manager_sign.png';
        $upload_dir = "self/manager/";
        
        if(move_uploaded_file($_FILES['upload3']['tmp_name'], $path. $file_name )) {
            $rc=1;

    		$stmt = $dbh->prepare('UPDATE `a_self`  SET `enterprise_manager_signs`=?  WHERE  `edition_id`=? ' );
    		$stmt->execute([$upload_dir . $file_name, $edition_id ]);
        }
    }    

    if(!empty($_FILES['upload2']) )
    {
        $path = "../documents/self/company";
        $file_name = $edition_id . 'company_sign.png';
        $upload_dir = "self/company/";
        
        if(move_uploaded_file($_FILES['upload2']['tmp_name'], $path. $file_name )) {
            $rc=1;

    		$stmt = $dbh->prepare('UPDATE `a_self`  SET `company_signs`=?  WHERE  `edition_id`=? ' );
    		$stmt->execute([$upload_dir . $file_name, $edition_id ]);
        }
    }    


    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc, "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if( intval($_POST["edition_id"])!=0   &&  $_FILES){
//file_put_contents( 'lst.txt', json_encode($_POST["file1_name"], JSON_UNESCAPED_UNICODE));        
     self_upload( intval($_POST["edition_id"]) ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
