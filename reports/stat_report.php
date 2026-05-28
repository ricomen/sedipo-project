<?php
/**
 * @copyright 2025
 */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="отчет.xlsx"');
header('Cache-Control: max-age=0');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1


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
$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;

$dbhost_helper = $cfg_helper->host;
$dbuser_helper = $cfg_helper->user;
$dbpassword_helper = $cfg_helper->password;
$dbname_helper = $cfg_helper->name;



try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


try {  
    $dbh_helper = new PDO("mysql:host=$dbhost_helper;dbname=$dbname_helper;charset=utf8", $dbuser_helper, $dbpassword_helper);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


session_start();
$sessionid = session_id();
$auth_session = 0;
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
$stmt->execute([$sessionid]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $auth_session = 1;
}
if($auth_session == 0){
    exit();
}


require_once('../PhpSpreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$form_of_study_a = [ '', 'Очная', 'Очная',  'Заочная', 'Заочная', 'Очно-заочная (вечерняя)', 'Очно-заочная (вечерняя)' ];
$level_of_education_a =['', 'Среднее профессиональное образование', 'Высшее образование' ];

if(intval($_GET['report_template']==2 )){
    $stat_template = 'pk11_stat.xlsx';
    stat_report_pk();
}
else if(intval($_GET['report_template']==1 )){
    $stat_template = 'po1_stat.xlsx';
    stat_report_po();
}




function stat_report_pk() {
    global $stat_template,  $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;

    $date1 = $_GET['year'] . '-01-01';
    $date2 = $_GET['year'] . '-12-31';

    //$worksheet = $spreadsheet->setActiveSheetIndex(0);
    //$highestRow = $worksheet->getHighestRow();
    //$highestCol = $worksheet->getHighestColumn();
    //$info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);


    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($stat_template);

    $tab13 = report13($date1, $date2);
    $spreadsheet->setActiveSheetIndex(3)
        ->setCellValue("P21", $tab13[0])
        ->setCellValue("P22", $tab13[1])
        ->setCellValue("R21", $tab13[2])
        ->setCellValue("R22", $tab13[3]);

    $tab21 = report21($date1, $date2);
    $spreadsheet->setActiveSheetIndex(6)
        ->setCellValue("P21", $tab13[2]+$tab13[3])
        ->setCellValue("Q21", $tab13[2])
        ->setCellValue("R21", $tab13[3])
        ->setCellValue("S21", $tab21[0])
        ->setCellValue("W21", $tab21[1]);


    $spreadsheet->setActiveSheetIndex(7)
        ->setCellValue("P21", $tab13[2])
        ->setCellValue("X21", $tab13[3]);


    $tab24 = report24($date1, $date2);
    $spreadsheet->setActiveSheetIndex(10)
        ->setCellValue("P21", $tab13[2]+$tab13[3])
        ->setCellValue("P22", $tab21[1])
        ->setCellValue("P23", $tab13[2])
        ->setCellValue("P24", $tab23[0])
        ->setCellValue("P25", $tab13[3])
        ->setCellValue("P26", $tab24[1])

        ->setCellValue("Q21", $tab24[2]+$tab24[3])
        ->setCellValue("Q22", $tab24[4]+$tab24[5])
        ->setCellValue("Q23", $tab24[2])
        ->setCellValue("Q25", $tab24[3])
        ->setCellValue("Q24", $tab24[4])
        ->setCellValue("Q26", $tab24[5])

        ->setCellValue("R21", $tab24[6]+$tab24[7])
        ->setCellValue("R22", $tab24[8]+$tab24[9])
        ->setCellValue("R23", $tab24[6])
        ->setCellValue("R25", $tab24[7])
        ->setCellValue("R24", $tab24[8])
        ->setCellValue("R26", $tab24[9])

        ->setCellValue("S21", $tab24[10]+$tab24[11])
        ->setCellValue("S22", $tab24[12]+$tab24[13])
        ->setCellValue("S23", $tab24[10])
        ->setCellValue("S25", $tab24[11])
        ->setCellValue("S24", $tab24[12])
        ->setCellValue("S26", $tab24[13])

        ->setCellValue("T21", $tab24[14]+$tab24[15])
        ->setCellValue("T22", $tab24[16]+$tab24[17])
        ->setCellValue("T23", $tab24[14])
        ->setCellValue("T25", $tab24[15])
        ->setCellValue("T24", $tab24[16])
        ->setCellValue("T26", $tab24[17])

        ->setCellValue("U21", $tab24[18]+$tab24[19])
        ->setCellValue("U22", $tab24[20]+$tab24[21])
        ->setCellValue("U23", $tab24[18])
        ->setCellValue("U25", $tab24[19])
        ->setCellValue("U24", $tab24[20])
        ->setCellValue("U26", $tab24[21])

        ->setCellValue("V21", $tab24[22]+$tab24[23])
        ->setCellValue("V22", $tab24[24]+$tab24[25])
        ->setCellValue("V23", $tab24[22])
        ->setCellValue("V25", $tab24[23])
        ->setCellValue("V24", $tab24[24])
        ->setCellValue("V26", $tab24[25])

        ->setCellValue("W21", $tab24[26]+$tab24[27])
        ->setCellValue("W22", $tab24[28]+$tab24[29])
        ->setCellValue("W23", $tab24[26])
        ->setCellValue("W25", $tab24[27])
        ->setCellValue("W24", $tab24[28])
        ->setCellValue("W26", $tab24[29])

        ->setCellValue("X21", $tab24[30]+$tab24[31])
        ->setCellValue("X22", $tab24[32]+$tab24[33])
        ->setCellValue("X23", $tab24[30])
        ->setCellValue("X25", $tab24[31])
        ->setCellValue("X24", $tab24[32])
        ->setCellValue("X26", $tab24[33])

        ->setCellValue("Y21", $tab24[34]+$tab24[35])
        ->setCellValue("Y22", $tab24[36]+$tab24[37])
        ->setCellValue("Y23", $tab24[34])
        ->setCellValue("Y25", $tab24[35])
        ->setCellValue("Y24", $tab24[36])
        ->setCellValue("Y26", $tab24[37])

        ->setCellValue("Z21", $tab24[38]+$tab24[39])
        ->setCellValue("Z22", $tab24[40]+$tab24[41])
        ->setCellValue("Z23", $tab24[38])
        ->setCellValue("Z25", $tab24[39])
        ->setCellValue("Z24", $tab24[40])
        ->setCellValue("Z26", $tab24[41]);

   
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //$writer->save("frdo_write.xlsx");
    $writer->save('php://output');

//print_r( $info[0] );
//file_put_contents("lst2.txt", print_r( $info, true));
}



function stat_report_po() {
    global $stat_template,  $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;

    $date1 = $_GET['year'] . '-01-01';
    $date2 = $_GET['year'] . '-12-31';

    //$worksheet = $spreadsheet->setActiveSheetIndex(0);
    //$highestRow = $worksheet->getHighestRow();
    //$highestCol = $worksheet->getHighestColumn();
    //$info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);


    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($stat_template);

    $tab13 = report13po($date1, $date2);
    $spreadsheet->setActiveSheetIndex(3)
        ->setCellValue("P21", $tab13[0])
        ->setCellValue("P22", $tab13[1])
        ->setCellValue("P23", $tab13[2])
        ->setCellValue("R21", $tab13[3])
        ->setCellValue("R22", $tab13[4])
        ->setCellValue("R23", $tab13[5]);

    $tab21 = report21po($date1, $date2, 0 );

    $spreadsheet->setActiveSheetIndex(5)
        ->setCellValue("R21", $tab21[0]+$tab21[1]+$tab21[2])
        ->setCellValue("Z21", $tab21[0])
        ->setCellValue("AB21", $tab21[1])
        ->setCellValue("AA21", $tab21[2])
        ->setCellValue("AC21", $tab21[9]);

    $spreadsheet->setActiveSheetIndex(6)
        ->setCellValue("R21", $tab21[3]+$tab21[4]+$tab21[5])
        ->setCellValue("Z21", $tab21[3])
        ->setCellValue("AB21", $tab21[4])
        ->setCellValue("AA21", $tab21[5])
        ->setCellValue("AC21", $tab21[10]);

    $spreadsheet->setActiveSheetIndex(7)
        ->setCellValue("R21", $tab21[6]+$tab21[7]+$tab21[8])
        ->setCellValue("Z21", $tab21[6])
        ->setCellValue("AB21", $tab21[7])
        ->setCellValue("AA21", $tab21[8])
        ->setCellValue("AC21", $tab21[11]);

    $i = 22;
    $j = 22;
    $k = 22;
    $stmt = $dbh->prepare("SELECT DISTINCT  `a_course`.`course_id`, `okpdtr_code`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1   " );
    $stmt->execute([$date1, $date2]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
       //$okpdtr_name = ''; 
       $okpdtr_name = '-'. $row->course_id; 
       if($row->okpdtr_code!=''){
            $stmt_helper = $dbh_helper->prepare("SELECT  `name`,   FROM `a_order_users`  WHERE `okpdtr_code`=? " );
            $stmt_helper->execute([$row->okpdtr_code]);
            if($row_helper = $stmt_helper->fetch(PDO::FETCH_OBJ)) { 
                  $okpdtr_name = $row_helper->name;
            }
       }

       $tab21 = report21po($date1, $date2, $row->course_id );
       if($tab21[0]+$tab21[1]+$tab21[2]>0){
           $spreadsheet->setActiveSheetIndex(5)
            ->setCellValue("A".$i, $okpdtr_name)
            ->setCellValue("N".$i, $i-20)
            ->setCellValue("Q".$i, $row->okpdtr_code);

           $spreadsheet->setActiveSheetIndex(5)
            ->setCellValue("R".$i, $tab21[0]+$tab21[1]+$tab21[2])
            ->setCellValue("Z".$i, $tab21[0])
            ->setCellValue("AB".$i, $tab21[1])
            ->setCellValue("AA".$i, $tab21[2])
            ->setCellValue("AC".$i, $tab21[9]);
           $i = $i +1;
       }

       if($tab21[3]+$tab21[4]+$tab21[5]>0){
           $spreadsheet->setActiveSheetIndex(6)
            ->setCellValue("A".$j, $okpdtr_name)
            ->setCellValue("N".$j, $j-20)
            ->setCellValue("Q".$j, $row->okpdtr_code);

           $spreadsheet->setActiveSheetIndex(6)
            ->setCellValue("R".$j, $tab21[3]+$tab21[4]+$tab21[5])
            ->setCellValue("Z".$j, $tab21[3])
            ->setCellValue("AB".$j, $tab21[4])
            ->setCellValue("AA".$j, $tab21[5])
            ->setCellValue("AC".$j, $tab21[10]);
           $j = $j +1;
       }

       if($tab21[6]+$tab21[7]+$tab21[8]>0){
           $spreadsheet->setActiveSheetIndex(7)
            ->setCellValue("A".$k, $okpdtr_name)
            ->setCellValue("N".$k, $k-20)
            ->setCellValue("Q".$k, $row->okpdtr_code);

          $spreadsheet->setActiveSheetIndex(7)
            ->setCellValue("R".$k, $tab21[6]+$tab21[7]+$tab21[8])
            ->setCellValue("Z".$k, $tab21[6])
            ->setCellValue("AB".$k, $tab21[7])
            ->setCellValue("AA".$k, $tab21[8])
            ->setCellValue("AC".$k, $tab21[11]);

           $k = $k +1;
       }
    }



    //$tab24 = report24($date1, $date2);
    $spreadsheet->setActiveSheetIndex(10)
        ->setCellValue("P21", $tab13[3])
        ->setCellValue("Q21", $tab13[4])
        ->setCellValue("R21", $tab13[5]);
   
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //$writer->save("frdo_write.xlsx");
    $writer->save('php://output');

//print_r( $info[0] );
//file_put_contents("lst2.txt", print_r( $info, true));
}




function report13($date1, $date2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = [];
    $stmt = $dbh->prepare('SELECT count(`a_order_course`.`course_id`) as `count`  FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND  type_of_education_id=2 AND type_of_program_id=1  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare('SELECT count(`a_order_course`.`course_id`) as `count`  FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    $stmt = $dbh->prepare('SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)   LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare('SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    return($rc);
}

function report13po($date1, $date2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = [];
    $stmt = $dbh->prepare('SELECT count(`a_order_course`.`course_id`) as `count`  FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND  type_of_education_id=1 AND type_of_program_id=3  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare('SELECT count(`a_order_course`.`course_id`) as `count`  FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=1 AND type_of_program_id=2  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }

    $stmt = $dbh->prepare('SELECT count(`a_order_course`.`course_id`) as `count`  FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=1 AND type_of_program_id=1  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }


    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=3  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare('SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=2  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare('SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=1  ' );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }

    return($rc);
}


function report21($date1, $date2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = [];
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=2 AND `type_of_program_id`=2 AND `qualification`<>'' " );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 


    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=2 AND (`type_of_program_id`=1 OR `type_of_program_id`=2) AND `sex`='Жен' " );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    return($rc);
}

function report21po($date1, $date2, $course_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_filter = '';
    if($course_id > 0){
           $course_filter = ' AND `a_course`.`course_id`='.$course_id;
    }
    $rc = [];
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=3  AND (`a_course`.`form_of_study`=1 OR `a_course`.`form_of_study`=2 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    }
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=3  AND (`a_course`.`form_of_study`=3 OR `a_course`.`form_of_study`=4 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=3  AND (`a_course`.`form_of_study`=5 OR `a_course`.`form_of_study`=6 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }

    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=2  AND (`a_course`.`form_of_study`=1 OR `a_course`.`form_of_study`=2 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=2  AND (`a_course`.`form_of_study`=3 OR `a_course`.`form_of_study`=4 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=2  AND (`a_course`.`form_of_study`=5 OR `a_course`.`form_of_study`=6 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=1  AND (`a_course`.`form_of_study`=1 OR `a_course`.`form_of_study`=2 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=1  AND (`a_course`.`form_of_study`=3 OR `a_course`.`form_of_study`=4 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_users` USING(`user_id`)     LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1  AND `type_of_program_id`=1  AND (`a_course`.`form_of_study`=5 OR `a_course`.`form_of_study`=6 ) ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }


    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=3  AND `sex`='Жен' ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=2  AND `sex`='Жен' ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }
    $stmt = $dbh->prepare("SELECT count(`a_users`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   `type_of_education_id`=1 AND `type_of_program_id`=1  AND `sex`='Жен' ". $course_filter  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    else {
        $rc[] = 0;
    }


    return($rc);
}


function report24($date1, $date2) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = [];
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1  AND `sex`='Жен' " );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  AND `sex`='Жен' " );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 


    $year_i = intval($_GET['year'])+1;

    $date_compare1 = "'" . $year_i -25 . "-01-01'";
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1 AND `date_of_birth`>'. $date_compare1 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2 AND `date_of_birth`>'. $date_compare1 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1  AND `sex`='Жен' AND `date_of_birth`>". $date_compare1 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  AND `sex`='Жен' AND `date_of_birth`>". $date_compare1 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    
    $age_a = [25, 30, 35, 40, 45, 50, 55, 60, 65 ];
    for($i=0; $i<8; $i++){
    $date_compare1 = "'" . $year_i -$age_a[$i+1] . "-01-01'";
    $date_compare2 = "'" . $year_i -$age_a[$i] . "-01-01'";
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1 AND `date_of_birth`>'. $date_compare1 . ' AND `date_of_birth`<='. $date_compare2 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2 AND `date_of_birth`>'. $date_compare1 . ' AND `date_of_birth`<='. $date_compare2  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1  AND `sex`='Жен' AND `date_of_birth`>". $date_compare1 . ' AND `date_of_birth`<='. $date_compare2  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  AND `sex`='Жен' AND `date_of_birth`>". $date_compare1 . ' AND `date_of_birth`<='. $date_compare2  );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 

    }

    $date_compare2 = "'" . $year_i -65 . "-01-01'";
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1 AND `date_of_birth`<='. $date_compare2 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare('SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)    LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2 AND `date_of_birth`<='. $date_compare2 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=1  AND `sex`='Жен' AND `date_of_birth`<=". $date_compare2 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 
    $stmt = $dbh->prepare("SELECT count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `a_lstream`.`date_protocol`>=? AND `a_lstream`.`date_protocol`<=? AND   type_of_education_id=2 AND type_of_program_id=2  AND `sex`='Жен' AND `date_of_birth`<=". $date_compare2 );
    $stmt->execute([$date1, $date2]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $rc[] = intval($row->count);
    } 


    return($rc);
}





function rus2translit($str) {
	mb_regex_encoding('UTF-8');

        $str2 = str_replace(
            array(" ", "(", ")", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            //array(" ", "(", ")", ".", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            //array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            $str
        );
        
        $str3 = str_replace(
            array("а", "б", "в", "г", "д", "е", "з", "и", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ц", "ъ", "ы", "ь"),
            array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", ""),
            $str2
        );
               
        $str4 = str_replace(
            array("А", "Б", "В", "Г", "Д", "Е", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Ц", "Ъ", "Ы", "Ь"),
            array("A", "B", "V", "G", "D", "E", "Z", "I", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "С", "", "Y", ""),
            $str3
        );
        
        $str5 = str_replace(
            array("э", "х", "й", "ё", "ж", "ч", "ш", "щ", "ю", "я", "Э", "Х", "Й", "Ё", "Ж", "Ч", "Ш", "Щ", "Ю", "Я"),
            array("eh", "kh", "jj", "jo", "zh", "ch", "sh", "shh", "ju", "ja", "EH", "KH", "JJ", "JO", "ZH", "CH", "SH", "SHH", "JU", "JA"),
            $str4
        );
   
        return $str5;
}
?>
