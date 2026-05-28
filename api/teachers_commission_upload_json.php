<?php
/**
 * @copyright 2022
 */


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



function signs_upload( $commission_id,  $edition_id,  $teacher_id, $file_name  ) {
    global $api_arg, $user_id_session,   $dbh;
    
//file_put_contents("lst.txt", json_encode($file_name, JSON_UNESCAPED_UNICODE));

    $rc=0;
    if(!empty($_FILES['upload1']['tmp_name']) )
    {
        $path = "../documents/signs/";
        //$file_name = $counterparty_id. '_' .$contract_id;
        $upload_dir = "signs/";
        
        if(move_uploaded_file($_FILES['upload1']['tmp_name'], $path. $file_name )) {
            $rc=1;

            $stmt = $dbh->prepare('UPDATE `a_teachers_commission_teacher`  SET `sign`=?  WHERE `commission_id`=? AND `edition_id`=? AND `teacher_id`=?' );
            $stmt->execute([$upload_dir . $file_name, $commission_id,  $edition_id,  $teacher_id ]);
        }
    }    
    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc, "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if(intval($_POST["commission_id"])!=0 && intval($_POST["edition_id"])!=0  && intval($_POST["teacher_id"])!=0 &&  $_FILES){
//file_put_contents( 'lst.txt', json_encode($_POST["file1_name"], JSON_UNESCAPED_UNICODE));        
     signs_upload( intval($_POST["commission_id"]),  intval($_POST["edition_id"]),   intval($_POST["teacher_id"]), basename($_POST["file1_name"])  ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
