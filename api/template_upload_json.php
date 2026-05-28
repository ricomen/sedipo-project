<?php
/**
 * @copyright 2026
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



function document_upload( $template_id, $filename ) {
    global $api_arg, $user_id_session,   $dbh;

    $rc1=0;
    if(!empty($_FILES['upload1']['tmp_name']) )
    {
        $path = "../documents/";
        unlink($path. $filename);
        if(move_uploaded_file($_FILES['upload1']['tmp_name'], $path. $filename )) {
            $rc1=1;

            //$stmt = $dbh->prepare('UPDATE `a_order`  SET `upload_file`=?  WHERE `order_id`=? ' );
            //$stmt->execute([$file_name, $order_id ]);
        }
    }    

    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc1, "uploadorder"=>$_FILES["upload1"]["name"],  "uploadordertype"=>$_FILES["upload1"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if(intval($_POST["template_id"])!=0 &&   $_FILES){
     document_upload( intval($_POST["template_id"]), htmlspecialchars($_POST["filename"], ENT_QUOTES) ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
