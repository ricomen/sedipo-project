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

try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
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
    // exit();
}


require_once('../PhpSpreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$form_of_study_a = [ '', 'Очная', 'Очная',  'Заочная', 'Заочная', 'Очно-заочная (вечерняя)', 'Очно-заочная (вечерняя)' ];
$level_of_education_a =['', 'Среднее профессиональное образование', 'Высшее образование' ];

if(intval($_GET['id']>0 )){

    frdo_report();
}




function frdo_report() {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    global $form_of_study_a, $level_of_education_a, $is_short_certificate_num;


    //$worksheet = $spreadsheet->setActiveSheetIndex(0);
    //$highestRow = $worksheet->getHighestRow();
    //$highestCol = $worksheet->getHighestColumn();
    //$info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);


    $course_name = ''; 
    $stmt = $dbh->prepare("SELECT   DISTINCT   `a_lstream`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `form_of_study`,  `course_id`, `a_course`.`category_id`, `form_of_study`, `type_of_education_id`, `subtype_of_education_id`, `type_of_program_id`    FROM `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_course_category`  USING(`category_id`)   WHERE `lstream_id`=?");
    $stmt->execute( [intval($_GET['id'])] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        //$course_id = $row['course_id'];
        //$category_id = $row['category_id'];
        $date_begin = $row->date_begin;
        $date_end = $row->date_end;
        $date_protocol = $row->date_protocol;
        $course_name = $row->course_name;
        $hours = intval($row->hours);
        $form_of_study = $form_of_study_a[intval($row->form_of_study)];
        $type_of_education_id = $row->type_of_education_id;
        $subtype_of_education_id = $row->subtype_of_education_id;
        $type_of_program_id = $row->type_of_program_id;
    }
   if($type_of_education_id==1 ){
          $frdo_temtlate = "fis_frdo_po.xlsx";


          if( $type_of_program_id==1 ){
               $ai = 'Свидетельство о профессии рабочего, должности служащего';
               $ji = 'Программа повышения квалификации рабочих, служащих';
          }
          else if( $type_of_program_id==2 ){
              $ai = 'Свидетельство о профессии рабочего, должности служащего';
              $ji = 'Программа переподготовки рабочих, служащих';
          }
          else if( $type_of_program_id==3 ){
              $ai = 'Свидетельство о профессии рабочего, должности служащего';
              $ji = 'Программа профессиональной подготовки по профессии рабочего, должности служащего';
          }
    }
     else if($type_of_education_id==2 && $subtype_of_education_id==1 ){
          $frdo_temtlate = "fis_frdo_dpo.xlsx";

          if( $type_of_program_id==1 ){
               $ai = 'Удостоверение о повышении квалификации';
               $ji = 'Повышение квалификации';
          }
          else if( $type_of_program_id==2 ){
              $ai = 'Диплом о профессиональной переподготовке';
              $ji = 'Профессиональная переподготовка';
          }
          else if( $type_of_program_id==3 ){
              $ai = 'Диплом о профессиональной подготовке';
              $ji = 'Профессиональная подготовка';
          }

    }
    else   
         return;

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($frdo_temtlate);

    $i = 2;
    $stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`, `sex`, `date_of_birth`, `snils`, `level_of_education`, `dplom_series`, `diplom_number`, `diplom_lastname`, `citizenship`,   `a_cohort`.`date_num`, `a_cohort`.`protocol_num`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`,  `a_order_users`.`certificate_num`, `a_order_course`.`course_id`, `blank_number`, `a_order_course`.`group_number`, `custom_cert_number`  FROM `a_cohort`  LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)    WHERE `lstream_id`=? AND `user_lock`=0 ");
    $stmt->execute( [intval($_GET['id'])] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  

        $rank_of_profession =  '';
        $stmt2 = $dbh->prepare("SELECT  `rank_of_profession`  FROM  `a_course`    WHERE `course_id`=?");
        $stmt2->execute( [$row->course_id] );
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
             if( intval($row2->rank_of_profession)>0 )
                  $rank_of_profession =  intval($row2->rank_of_profession);
        }

        $citizenship = $row->citizenship;
        if( $citizenship=='' || $citizenship=='RUS' || $citizenship=='0' )
               $citizenship = '643';
        if( $citizenship=='UZB'  )
               $citizenship = '860';


        if($row->custom_cert_number!='')
              $cert_num = $row->custom_cert_number;
        else if($is_short_certificate_num)
              $cert_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);
        else
              $cert_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);

        //$cert_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);

        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A$i", $ai)
        ->setCellValue("B$i", 'Оригинал')
        ->setCellValue("C$i", 'Нет')
        ->setCellValue("D$i", 'Нет')
        ->setCellValue("E$i", 'Нет')
        ->setCellValue("H$i", date2rus($date_protocol) )
        ->setCellValue("I$i", $cert_num)
        ->setCellValue("J$i", $ji)
        ->setCellValue("K$i", $course_name )
        ->setCellValue("M$i", $rank_of_profession);


        if($type_of_education_id==1) {
            $spreadsheet->setActiveSheetIndex(0)
           ->setCellValue("L$i", $course_name)
           ->setCellValue("N$i", substr($date_begin, 0, 4 ))
           ->setCellValue("O$i", substr($date_end, 0, 4 ))
           ->setCellValue("P$i", $hours )
           ->setCellValue("Q$i", $row->lastname )
           ->setCellValue("R$i", $row->firstname )
           ->setCellValue("S$i", $row->middlename )
           ->setCellValue("T$i", date2rus($row->date_of_birth)  )
           ->setCellValue("U$i", $row->sex )
           ->setCellValue("V$i", $row->snils )
           ->setCellValue("W$i", $citizenship )
           ->setCellValue("X$i", $form_of_study )
           ->setCellValue("Y$i", 'Платное обучение' )
           ->setCellValue("Z$i", 'в образовательной организации' );
           
        }

//           ->setCellValue("L$i", 'Нет')
        if($type_of_education_id==2) {
            $blank_number =  explode(" ", $row->blank_number);
            if(count($blank_number)>1){
                $spreadsheet->setActiveSheetIndex(0)
               ->setCellValue("F$i", $blank_number[0] )
               ->setCellValue("G$i", $blank_number[1] );
            }
            else {
                $spreadsheet->setActiveSheetIndex(0)
               ->setCellValue("F$i", 'Нет')
               ->setCellValue("G$i", 'Нет');
            }


            
        
            $spreadsheet->setActiveSheetIndex(0)
           ->setCellValue("N$i", 'Нет')
           ->setCellValue("S$i", substr($date_begin, 0, 4 ))
           ->setCellValue("T$i", substr($date_end, 0, 4 ))
           ->setCellValue("U$i", $hours )
           ->setCellValue("V$i", $row->lastname )
           ->setCellValue("W$i", $row->firstname )
           ->setCellValue("X$i", $row->middlename )
           ->setCellValue("Y$i", date2rus($row->date_of_birth)  )
           ->setCellValue("Z$i", $row->sex )
           ->setCellValue("AA$i", $row->snils )
           ->setCellValue("AB$i", $form_of_study )
           ->setCellValue("AC$i", 'Платное обучение' )
           ->setCellValue("AD$i", 'в образовательной организации' )
           ->setCellValue("AE$i", $citizenship );

            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("O$i", $level_of_education_a[$row->level_of_education] )
            ->setCellValue("P$i", $row->diplom_lastname )
            ->setCellValue("Q$i", $row->dplom_series )
            ->setCellValue("R$i", $row->diplom_number );
        }

                    $blank_empty = $row->blank_number;
            if($blank_empty=='' || $blank_empty=='0'){
                $spreadsheet->setActiveSheetIndex(0)
               ->setCellValue("F$i", 'Нет')
               ->setCellValue("G$i", 'Нет');
            }
           // else{
           //      $spreadsheet->setActiveSheetIndex(0)
           //     ->setCellValue("F$i", $blank_empty)
           //    ->setCellValue("G$i", $blank_empty);
           // }
        $i = $i+1;


    }




    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //$writer->save("frdo_write.xlsx");
    //ob_end_clean();
    $writer->save('php://output');
    exit(); // !!!

//print_r( $info[0] );
//file_put_contents("lst2.txt", print_r( $info, true));
}

function date2rus($date_str) {
  if($date_str == ''  || $date_str == '1000-01-01')
         return('');
 
  $date_a = explode( '-',  $date_str );
   return($date_a[2] . '.' . $date_a[1] . '.' . $date_a[0]); 
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
