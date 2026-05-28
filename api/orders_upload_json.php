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



function document_upload( $order_id ) {
    global $api_arg, $user_id_session,   $dbh;

    $rc1=0;
    if(!empty($_FILES['uploadorder']['tmp_name']) )
    {
        $path = "../uploads/orders/";
        $file_name = 'order_' .intval($order_id) .'_'. date('Y-m-d').'.pdf';

        if(move_uploaded_file($_FILES['uploadorder']['tmp_name'], $path. $file_name )) {
            $rc1=1;

            $stmt = $dbh->prepare('UPDATE `a_order`  SET `upload_file`=?  WHERE `order_id`=? ' );
            $stmt->execute([$file_name, $order_id ]);
        }
    }    

    // $rc2=0;
    // if(!empty($_FILES['upload2c']['tmp_name']) )
    // {
    //     $path = "../uploads/groups/certificate/";
    //     $file_name = 'certificate2_' .intval($cohort_id) .'_'. date('Y-m-d').'.pdf';

    //     if(move_uploaded_file($_FILES['upload2c']['tmp_name'], $path. $file_name )) {
    //         $rc2=1;

    //         $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `upload_certificate2`=?  WHERE `cohort_id`=? ' );
    //         $stmt->execute([$file_name, $cohort_id ]);
    //     }
    // }

    // $rc0=0;
    // if(!empty($_FILES['upload1p']['tmp_name']) )
    // {
    //     $path = "../uploads/groups/protocol/";
    //     $file_name = 'protocol_' .intval($cohort_id) .'_'. date('Y-m-d').'.pdf';

    //     if(move_uploaded_file($_FILES['upload1c']['tmp_name'], $path. $file_name )) {
    //         $rc1=1;

    //         $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `upload_protocol`=?  WHERE `cohort_id`=? ' );
    //         $stmt->execute([$file_name, $cohort_id ]);
    //     }
    // }

    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"upload",   "result"=>["upload"=>$rc1, "uploadorder"=>$_FILES["uploadorder"]["name"],  "uploadordertype"=>$_FILES["uploadorder"]["type"]  ] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



if(intval($_POST["order_id"])!=0 &&   $_FILES){
//file_put_contents( 'lst.txt', json_encode($_POST["file1_name"], JSON_UNESCAPED_UNICODE));        
     document_upload( intval($_POST["order_id"])  ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
