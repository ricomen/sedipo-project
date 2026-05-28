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

function upload( $filename ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    

    if($_FILES["upload"]['type'] == 'image/png') {
	    move_uploaded_file($_FILES['upload']['tmp_name'], 'upload/' . $filename . '.png');
	    $file = 'upload/' . $filename . '.png';
    }
    else if($_FILES["upload"]['type'] == 'application/pdf') {
	    move_uploaded_file($_FILES['upload']['tmp_name'], 'upload/' . $filename . '.pdf');
	    $file = 'upload/' . $filename . '.pdf';
    }
    else if($_FILES["upload"]['type'] == 'image/jpeg') {
	    move_uploaded_file($_FILES['upload']['tmp_name'], 'upload/' . $filename . '.jpg');
	    $file = 'upload/' . $filename . '.jpg';
    }
    

    $result = ["status"=>0, "error"=>'',   "action"=>"upload",  "result"=>["file"=>$file,  "upload"=>$_FILES["upload"]["name"],  "type"=>$_FILES["upload"]["type"]] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function upload2( $filename1, $filename2 ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    
    if($filename1 != ''){
        if($_FILES["upload1"]['type'] == 'image/png') {
	        move_uploaded_file($_FILES['upload1']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename1 . '.png');
	        $file1 = 'upload/' . $filename . '.png';
        }
        else if($_FILES["upload1"]['type'] == 'application/pdf') {
	        move_uploaded_file($_FILES['upload1']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename1 . '.pdf');
	        $file1 = 'upload/' . $filename . '.pdf';
        }
        else if($_FILES["upload1"]['type'] == 'image/jpeg') {
	        move_uploaded_file($_FILES['upload1']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename1 . '.jpg');
	        $file1 = 'upload/' . $filename . '.jpg';
        }
    }

//file_put_contents("lst.txt", json_encode($_FILES['upload1']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename1 . '.png', JSON_UNESCAPED_UNICODE));

    if($filename2 != ''){
        if($_FILES["upload2"]['type'] == 'image/png') {
	        move_uploaded_file($_FILES['upload2']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename2 . '.png');
	        $file1 = 'upload/' . $filename . '.png';
        }
        else if($_FILES["upload2"]['type'] == 'application/pdf') {
	        move_uploaded_file($_FILES['upload2']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename2 . '.pdf');
	        $file1 = 'upload/' . $filename . '.pdf';
        }
        else if($_FILES["upload2"]['type'] == 'image/jpeg') {
	        move_uploaded_file($_FILES['upload2']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/upload/' . $filename2 . '.lpg');
	        $file1 = 'upload/' . $filename . '.jpg';
        }
    }

    $result = ["status"=>0, "error"=>'',   "action"=>"upload",  "result"=>["file1"=>$file1, "file2"=>$file2,   "type1"=>$_FILES["uploa1"]["type"],  "type2"=>$_FILES["uploa2"]["type"] ]];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




if( isset($_FILES) ){
     upload2( $_POST["name1"], $_POST["name2"] ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
