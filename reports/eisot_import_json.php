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

require_once('../PhpSpreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


function eisot_import( $lstream_id ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    global $is_short_certificate_num;
    
    if($_FILES['upload1']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
         $result = ["status"=>1, "error"=>'Ндопустимый тип файла: '. $_FILES["upload1"]["type"],  "action"=>"eisot_import",   "result"=>["total"=>0,  "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ]];
         echo json_encode($result, JSON_UNESCAPED_UNICODE);
         return;
    }

    $reader = new Xlsx();
    $spreadsheet = $reader->load($_FILES['upload1']['tmp_name']); 
    $worksheet = $spreadsheet->setActiveSheetIndex(0);
    $highestRow = $worksheet->getHighestRow();
    $highestCol = $worksheet->getHighestColumn();
    $info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);
//file_put_contents("lst.txt", print_r([ $info ], true) );
    $i = 0;
    $k = 0;
    $stmt = $dbh->prepare('DELETE FROM `a_lstream_eisot` WHERE  `lstream_id`=? ');
    $stmt->execute([ $lstream_id ]);

    foreach ($info as $listRrow) {
       if( $i>0 && trim($listRrow[0])!='') {
          if($lstream_id>0){
               //$stmt = $dbh->prepare("SELECT `a_users`.`user_id`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`, MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_cohort`.`protocol_num`   FROM `a_cohort`  LEFT JOIN `a_order_course` using(`cohort_id`)  LEFT JOIN `a_order_users` USING(`item_id`)  LEFT JOIN `a_users` USING(`user_id`)  WHERE `a_cohort`.`lstream_id`=? AND  `lastname`=? AND  `firstname`=? AND  `middlename`=? AND `snils`=?  ");
               //$stmt->execute([trim($lstream_id),   trim($listRrow[3]), trim($listRrow[4]), trim($listRrow[5]), trim($listRrow[6]) ]);
               $stmt = $dbh->prepare("SELECT `a_users`.`user_id` , MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_cohort`.`protocol_num`, `order_id`, `a_order_course`.`course_id`   FROM `a_cohort`  LEFT JOIN `a_order_course` using(`cohort_id`)  LEFT JOIN `a_order_users` USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  WHERE `a_cohort`.`lstream_id`=? AND  `lastname` LIKE ? AND  `firstname` LIKE ? AND  `middlename`LIKE ? AND `snils`=?  ");
               $stmt->execute([trim($lstream_id),   trim($listRrow[3]).'%', trim($listRrow[4]).'%', trim($listRrow[5]).'%', trim($listRrow[6]) ]);
               if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                     $custom_protocol_number = '';
                     $stmt2 = $dbh->prepare('SELECT `group_id`, `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? ');
                     $stmt2->execute([$row->order_id, $row->course_id]);
                     if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                             $custom_protocol_number = $row2->custom_protocol_num;
                     }
                     if( $custom_protocol_number!='' )
                           $protocol_num = $custom_protocol_number;
                     else if($is_short_certificate_num)
                           $protocol_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);
                     else
                           $protocol_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);

                   //$stmt2 = $dbh->prepare('UPDATE `a_lstream_eisot` SET `cert_number`=?, `pack_id `=?   WHERE `user_id`=?');
                   //$stmt2->execute([trim($listRrow[0]), trim($listRrow[1]), $row->id_user ]);
                   if(intval($row->user_id) > 1  && trim($listRrow[13]) == $protocol_num ){
                        $stmt2 = $dbh->prepare('DELETE FROM `a_lstream_eisot` WHERE `lstream_id`=? AND `user_id`=? ');
                        $stmt2->execute([ $lstream_id, $row->user_id ]);
                        
                        $stmt2 = $dbh->prepare('INSERT INTO `a_lstream_eisot`( `lstream_id`, `user_id`, `protocol_num`,  `cert_number`, `pack_id`) VALUES(?, ?, ?, ?, ? )');
                        $stmt2->execute([ $lstream_id, $row->user_id, $protocol_num,  trim($listRrow[0]), trim($listRrow[1]) ]);
                        $k = $k+1;
                   }
               }
           }
           else {
               $lstream_id_i = 0;
               //$stmt = $dbh->prepare("SELECT  `a_cohort`.`lstream_id`, `a_users`.`user_id`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`, MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_cohort`.`protocol_num`   FROM `a_cohort`  LEFT JOIN `a_order_course` using(`cohort_id`)  LEFT JOIN `a_order_users` USING(`item_id`)  LEFT JOIN `a_users` USING(`user_id`)  WHERE  `lastname`=? AND  `firstname`=? AND  `middlename`=? AND `snils`=?  ");
               //$stmt->execute([  trim($listRrow[3]), trim($listRrow[4]), trim($listRrow[5]), trim($listRrow[6]) ]);
               $stmt = $dbh->prepare("SELECT `a_cohort`.`lstream_id`, `a_users`.`user_id` , MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_cohort`.`protocol_num`, `order_id`, `a_order_course`.`course_id`   FROM `a_cohort`  LEFT JOIN `a_order_course` using(`cohort_id`)  LEFT JOIN `a_order_users` USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)  WHERE  `lastname` LIKE ? AND  `firstname` LIKE ? AND  `middlename`LIKE ? AND `snils`=?  ");
               $stmt->execute([ trim($listRrow[3]).'%', trim($listRrow[4]).'%', trim($listRrow[5]).'%', trim($listRrow[6]) ]);
               while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                     $custom_protocol_number = '';
                     $stmt2 = $dbh->prepare('SELECT `group_id`, `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? ');
                     $stmt2->execute([$row->order_id, $row->course_id]);
                     if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                             $custom_protocol_number = $row2->custom_protocol_num;
                     }
                     if( $custom_protocol_number!='' )
                           $protocol_num = $custom_protocol_number;
                     else if($is_short_certificate_num)
                           $protocol_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);
                     else
                           $protocol_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);

                     $lstream_id_i = $row->lstream_id;

                     if(intval($row->user_id) > 1  && trim($listRrow[13]) == $protocol_num && $lstream_id_i > 0  ){
                        $stmt2 = $dbh->prepare('DELETE FROM `a_lstream_eisot` WHERE `lstream_id`=? AND `user_id`=? ');
                        $stmt2->execute([ $lstream_id_i, $row->user_id ]);

                        $stmt2 = $dbh->prepare('INSERT INTO `a_lstream_eisot`( `lstream_id`, `user_id`,  `protocol`,  `cert_number`, `pack_id`) VALUES(?, ?, ?, ?, ? )');
                        $stmt2->execute([ $lstream_id_i, $row->user_id, $protocol_num,  trim($listRrow[0]), trim($listRrow[1]) ]);
                        $k = $k+1;
                   }
               }
           }
       }
       $i = $i +1;
    }

    $result = ["status"=>0, "error"=>'',  "action"=>"eisot_import",   "result"=>["total"=>$k,  "file"=>$_FILES["upload1"]["name"],  "type"=>$_FILES["upload1"]["type"]  ]];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


//file_put_contents("lst.txt", print_r([$_FILES, $_POST ], true) );
if($_POST["lstream_id"]!='' &&  $_FILES){
     eisot_import( intval($_POST["lstream_id"]) ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>
