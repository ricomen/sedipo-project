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

require_once('../PhpSpreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


function calendar_import( $course_id, $cohort_id, $variation  ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    
//file_put_contents("lst1.txt", json_encode($_FILES, JSON_UNESCAPED_UNICODE));

    $reader = new Xlsx();
    $spreadsheet = $reader->load($_FILES['upload1']['tmp_name']); 
    $worksheet = $spreadsheet->setActiveSheetIndex(0);
    $highestRow = $worksheet->getHighestRow();
    $highestCol = $worksheet->getHighestColumn();
    $info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);

//file_put_contents("lst2.txt", json_encode($info, JSON_UNESCAPED_UNICODE));

    $stmt = $dbh->prepare("DELETE FROM `a_course_calendar`  WHERE `course_id`=? AND  `variation`=? AND `cohort_id`=0");
    $stmt->execute([$course_id, $variation ]);

    calendar_import_i( $course_id, $cohort_id, $info, $variation );


    //$result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file1"=>$_FILES["upload1"]["name"],  "type1"=>$_FILES["upload1"]["type"],  "file2"=>$_FILES["upload2"]["name"],  "type2"=>$_FILES["upload2"]["type"],  "type3"=>$_FILES["upload3"]["type"] ]];
    $result = ["status"=>0, "error"=>'',  "action"=>"user_import",   "result"=>["total"=>[$rows_count, $rows_count2],  "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ]];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function calendar_import_i( $course_id, $cohort_id, $info, $variation ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;

    $hours_l = 0;
    $hours_p = 0;
    $hours_i = 0;
    $hours_c = 0;
    $rows_count = 0;
	$day = 1;    
    foreach ($info as $listRrow) {
        //$myDate = date('Y-m-d h:i',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($tableRow[2]));
	    if($rows_count == 0) {
	        $i = 0;
	        foreach ($listRrow as  $value){
		        $i = $i+1;
	        }
	       //$stmt = $dbh->prepare("DELETE FROM `a_course_calendar`  WHERE `course_id`=? AND `cohort_id`=?  ");
    	       //$stmt->execute([$course_id, $cohort_id]);
	    }
	    else if(trim($listRrow[0])!='') {
               $topic_a = explode('.', trim($listRrow[0]));
               //$topic = sprintf('%2d.%2d', intval($topic_a[0]),  intval($topic_a[1])); 
               $topic = '';
               foreach ($topic_a as $value){ 
                        $topic = $topic . sprintf('%2d.', intval($value) );
               }
               $hours = floatval($listRrow[2]);
//file_put_contents("lst.txt", print_r([$course_id, $variation, $topic,  strip_tags($listRrow[1]), $hours, intval($listRrow[3]), $cohort_id ] , true) );

               if(intval($listRrow[3])<4){   
                   $stmt = $dbh->prepare("INSERT INTO `a_course_calendar`(`course_id`, `variation`, `topic`, `name_topic`, `hours`, `type`, `cohort_id`) VALUES(?,?,?,?,?,?,?)");
                   $stmt->execute([$course_id, $variation, $topic,  strip_tags($listRrow[1]), $hours, intval($listRrow[3]), $cohort_id ]);
               }
               if(intval($listRrow[3]) == 1 )
                   $hours_l = $hours_l +$hours;
               else if(intval($listRrow[3]) == 2 )
                   $hours_p = $hours_p +$hours;
               else if(intval($listRrow[3]) == 5 || intval($listRrow[3]) == 6 )
                   $hours_p = $hours_p +$hours;
               else if(intval($listRrow[3]) == 3 )
                   $hours_i = $hours_i +$hours;
               else if(intval($listRrow[3]) == 4 )
                   $hours_c = $hours_c +$hours;

               if($variation == 1){
                    $stmt = $dbh->prepare("UPDATE `a_course` SET hours_l=?, hours_p=?, hours_i=?, hours_c=?   WHERE `course_id`=? ");
                    $stmt->execute([ $hours_l, $hours_p, $hours_i, $hours_c,  $course_id ]);
               }
               else if($variation == 2){
                    $stmt = $dbh->prepare("UPDATE `a_course` SET hours_l_add=?, hours_p_add=?, hours_i_add=?, hours_c_add=?   WHERE `course_id`=? ");
                    $stmt->execute([ $hours_l, $hours_p, $hours_i, $hours_c,  $course_id ]);
               }
               else if($variation == 3){
                    $stmt = $dbh->prepare("UPDATE `a_course` SET hours_l_add2=?, hours_p_add2=?, hours_i_add2=?, hours_c_add2=?   WHERE `course_id`=? ");
                    $stmt->execute([ $hours_l, $hours_p, $hours_i, $hours_c,  $course_id ]);
               }
	    }
	    $rows_count = $rows_count +1;
    }

}




if(isset($_POST["cohort_id"]))
    $cohort_id = intval($_POST["cohort_id"]);
else
    $cohort_id = 0;

if($_POST["course_id"]!='' &&  $_FILES){
     calendar_import( intval($_POST["course_id"]), $cohort_id, intval($_POST["variation"])  ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
