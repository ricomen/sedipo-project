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



function document_upload( $cohort_id ) {
    global $api_arg, $user_id_session,   $dbh;

    $rc1=0;
    if(!empty($_FILES['upload1ce']['tmp_name']) )
    {
        if($_POST["file1ce_type"] == "application/pdf" )
            $ex = '.pdf';
        if($_POST["file1ce_type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
            $ex = '.docx';

        $path = "../uploads/groups/certificate/";
        $file_name = 'modify_certificate_' .intval($cohort_id) .$ex;

        if(move_uploaded_file($_FILES['upload1ce']['tmp_name'], $path. $file_name )) {
            $rc1=1;

            $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `modify_certificate1`=?  WHERE `cohort_id`=? ' );
            $stmt->execute([$file_name, $cohort_id ]);
        }
    }    

    $rc2=0;
    if(!empty($_FILES['upload2ce']['tmp_name']) )
    {
        if($_POST["file2ce_type"] == "application/pdf" )
            $ex = '.pdf';
        if($_POST["file2ce_type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
            $ex = '.docx';

        $path = "../uploads/groups/certificate/";
        $file_name = 'modify_certificate2_' .intval($cohort_id) .$ex;

        if(move_uploaded_file($_FILES['upload2ce']['tmp_name'], $path. $file_name )) {
            $rc2=1;

            $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `modify_certificate2`=?  WHERE `cohort_id`=? ' );
            $stmt->execute([$file_name, $cohort_id ]);
        }
    }


    $rc2=0;
    if(!empty($_FILES['upload_c']['tmp_name']) )
    {
        if($_POST["file_c_type"] == "application/pdf" )
            $ex = '.pdf';
        if($_POST["file_c_type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
            $ex = '.docx';

        $path = "../uploads/groups/certificate/";
        $file_name = 'certificate_' .intval($cohort_id) .$ex;

        if(move_uploaded_file($_FILES['upload_c']['tmp_name'], $path. $file_name )) {
            $rc2=1;

            $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `upload_certificate`=?  WHERE `cohort_id`=? ' );
            $stmt->execute([$file_name, $cohort_id ]);
        }
    }


    $rc0=0;
    if(!empty($_FILES['upload1p']['tmp_name']) )
    {
        if($_POST["file1p_type"] == "application/pdf" )
            $ex = '.pdf';
        if($_POST["file1p_type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
            $ex = '.docx';

        $path = "../uploads/groups/protocol/";
        $file_name = 'protocol_' .intval($cohort_id) .$ex;

        if(move_uploaded_file($_FILES['upload1p']['tmp_name'], $path. $file_name )) {
            $rc1=1;

            $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `upload_protocol`=?  WHERE `cohort_id`=? ' );
            $stmt->execute([$file_name, $cohort_id ]);
        }
    }

    if(!empty($_FILES['upload1pe']['tmp_name']) )
    {
        if($_POST["file1pe_type"] == "application/pdf" )
            $ex = '.pdf';
        if($_POST["file1pe_type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
            $ex = '.docx';

        $path = "../uploads/groups/protocol/";
        $file_name = 'modify_protocol_' .intval($cohort_id) .$ex;

        if(move_uploaded_file($_FILES['upload1pe']['tmp_name'], $path. $file_name )) {
            $rc1=1;

            $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `modify_protocol`=?  WHERE `cohort_id`=? ' );
            $stmt->execute([$file_name, $cohort_id ]);
        }
    }

    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc1, "file1c"=>$_FILES["upload1c"]["name"],  "type1c"=>$_FILES["upload1c"]["type"], "file2c"=>$_FILES["upload2c"]["name"],  "type2c"=>$_FILES["upload2c"]["type"], "file1p"=>$_FILES["upload1p"]["name"],  "type1c"=>$_FILES["upload1p"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if(intval($_POST["cohort_id"])!=0 &&   $_FILES){
//file_put_contents( 'lst.txt', json_encode($_POST["file1_name"], JSON_UNESCAPED_UNICODE));        
     document_upload( intval($_POST["cohort_id"])  ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
