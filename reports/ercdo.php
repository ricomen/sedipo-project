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

    ercdo_report();
}




function ercdo_report() {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    global $form_of_study_a, $level_of_education_a, $is_short_certificate_num;


    //$worksheet = $spreadsheet->setActiveSheetIndex(0);
    //$highestRow = $worksheet->getHighestRow();
    //$highestCol = $worksheet->getHighestColumn();
    //$info = $worksheet->rangeToArray("A1:$highestCol$highestRow", null, true, false, false);


    $course_name = '';
    $lstream_name =  '';
    $rank_of_profession =  '';
    $stmt = $dbh->prepare("SELECT   DISTINCT   `a_lstream`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `form_of_study`,  `course_id`, `a_course`.`category_id`, `form_of_study`, `type_of_education_id`, `subtype_of_education_id`, `type_of_program_id`, `rank_of_profession`    FROM `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_course_category`  USING(`category_id`)   WHERE `lstream_id`=?");
    $stmt->execute( [intval($_GET['id'])] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $course_id = $row->course_id;
        $category_id = $row->category_id;
        $date_begin = $row->date_begin;
        $date_end = $row->date_end;
        $date_protocol = $row->date_protocol;
        $course_name = $row->course_name;
        $hours = intval($row->hours);
        $form_of_study = $form_of_study_a[intval($row->form_of_study)];
        $type_of_education_id = $row->type_of_education_id;
        $subtype_of_education_id = $row->subtype_of_education_id;
        $type_of_program_id = $row->type_of_program_id;
        $lstream_name = $row->name;
        $rank_of_profession =  intval($row->rank_of_profession);
    }


    $stmt2 = $dbh->prepare("SELECT  *  FROM `a_self` WHERE `edition`<?  ORDER BY `edition` DESC  LIMIT 1   ");
    $stmt2->execute( [ $date_protocol ] );
    if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
         $enterprise_manager = $row2['enterprise_manager'];
         $enterprise_manager_a = explode(" ", $row2['enterprise_manager']);
         $enterprise_manager3 = $enterprise_manager_a[0]. ' ' .mb_substr($enterprise_manager_a[1], 0, 1). '.' .mb_substr($enterprise_manager_a[2], 0, 1). '.';

         //$self_data =  [  'name'=>$row2['name'], 'shortname'=>$row2['shortname'],    'inn'=>$row2['inn'], 'kpp'=>$row2['kpp'], 'ogrn'=>$row2['ogrn'], 'addres1'=>$row2['addres1'],  'addres2'=>$row2['addres2'], 'phone'=>$row2['phone'],
         //'position_head'=>$row2['position_head'], 'enterprise_manager'=>$row2['enterprise_manager'], 'position_head2'=>$row2['position_head2'], 'enterprise_manager2'=>$row2['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3,  'enterprise_manager_signs'=>$row2['enterprise_manager_signs'], 
         //'bank'=>$row2['bank'], 'checking_account'=>$row2['checking_account'], 'correspondent_account'=>$row2['correspondent_account'], 'bik'=>$row2['bik'], 'license'=>$row2['license'], 'accreditation'=>$row2['accreditation'], 'city'=>$row2['city'] ];

}




    if( $type_of_program_id==1 ){
        $ercdo_temtlate = "ercdo1.xlsx";
        $ai = 'Удостоверение о повышении квалификации';
        $ji = 'Повышение квалификации';
    }
    else if( $type_of_program_id==2 ){
        $ercdo_temtlate = "ercdo2.xlsx";
        $ai = 'Диплом о профессиональной переподготовке';
        $ji = 'Программа переподготовки рабочих, служащих';
    }
    else if( $type_of_program_id==3 ){
        $ercdo_temtlate = "ercdo3.xlsx";
        $ai = 'Свидетельство о профессии рабочего, должности служащего';
        $ji = 'Программа профессиональной подготовки по профессии рабочего, должности служащего';
    }
    else
         return;

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($ercdo_temtlate);

    $order_id =  0;
    $i = 2;
    $stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`, `sex`, `email`,  `date_of_birth`, `snils`, `level_of_education`, `dplom_series`, `diplom_number`, `diplom_lastname`, `citizenship`,   `a_cohort`.`date_num`, `a_cohort`.`protocol_num`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`,  `a_order_users`.`certificate_num`, `a_order_course`.`course_id`, `variation`,  `blank_number`, `a_order_course`.`group_number`,  `custom_cert_number`, `order_id`  FROM `a_cohort`  LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)    WHERE `lstream_id`=? AND `user_lock`=0 ");
    $stmt->execute( [intval($_GET['id'])] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $citizenship = $row->citizenship;
        if( $citizenship=='' || $citizenship=='0' || $citizenship=='643' )
               $citizenship = 'RUS';
        if(  $citizenship=='860' )
               $citizenship = 'UZB';

        if( $row->snils=='' || $row->snils=='-- ')
               $egpu = 'Нет';
        else
               $egpu = 'Да';

        $order_id = $row->order_id;
        $topic_t = 0;
        $name_topic = '';
        $hours_topic = '';
        $course_calendar = [];
        if( $type_of_program_id==2 || $type_of_program_id==3 ){
            $stmt2 = $dbh->prepare("SELECT   `topic_id`,  `type`,  `topic`, `name_topic`, `hours`  FROM  `a_course_calendar`    WHERE `course_id`=? AND `variation`=? ORDER BY `topic` ");
            $stmt2->execute( [$row->course_id, $row->variation] );
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $topic_a = explode('.', $row2->topic);
                if( intval($topic_a[1])==0 &&  ($row2->type==1 || $row2->type==2 || $row2->type==7) ){
                      $topic_t =  intval($topic_a[0]);
                      $name_topic = $row2->name_topic;
                      $hours_topic = $row2->hours;
                }
                else if( intval($topic_a[0])==$topic_t &&  ($row2->type==3 || $row2->type==5 || $row2->type==7) && $name_topic!='' ){
                      $course_calendar[] = [$name_topic, $hours_topic];
                      $name_topic = '';
                }
            }
        }
//file_put_contents("lst2.txt", print_r( $course_calendar, true));
        $course_calendar_index2 = ['Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ'];
        $course_calendar_index3 = ['Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ'];


        $custom_protocol_num = '';
        $stmt3 = $dbh->prepare('SELECT  `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? AND `group_number`=?  ');
        $stmt3->execute([$order_id, $row->course_id, $row->group_number  ]);
        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
              $custom_protocol_num = $row3->custom_protocol_num;
        }

        if($is_short_certificate_num)
             $protocol_num =  sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);
        else
             $protocol_num =  substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);

        if($row->custom_cert_number!='')
              $cert_num = $row->custom_cert_number;
        else if($is_short_certificate_num)
              $cert_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);
        else
              $cert_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);

        //$cert_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num).'-'.sprintf('%02d', $row->certificate_num);

        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A$i", $row->lastname )
        ->setCellValue("B$i", $row->firstname )
        ->setCellValue("C$i", $row->middlename )
        ->setCellValue("D$i", date2rus($row->date_of_birth)  )
        ->setCellValue("H$i", $row->snils )
        ->setCellValue("J$i", $egpu )

        ->setCellValue("F$i", $course_name );



        if( $type_of_program_id==1 ){
           $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("L$i", $row->email )
            ->setCellValue("N$i", $citizenship )
            ->setCellValue("M$i", mb_substr($row->sex, 0, 1) )
            ->setCellValue("T$i", "В образовательной организации" )
            ->setCellValue("Y$i", $level_of_education_a[$row->level_of_education] )
            ->setCellValue("Z$i", $row->diplom_lastname )

            ->setCellValue("O$i", 'Протокол № ' . $protocol_num )
            ->setCellValue("P$i", 'Приказ № ' . $lstream_name )
            ->setCellValue("R$i", $hours )
            ->setCellValue("U$i", 'Платное обучение' )
            ->setCellValue("AA$i", $date_begin )
            ->setCellValue("AB$i", $date_end )

            ->setCellValue("AW$i", $enterprise_manager );
        }
        else if( $type_of_program_id==2 ){
           $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("L$i", $row->email )
            ->setCellValue("N$i", $citizenship )
            ->setCellValue("M$i", mb_substr($row->sex, 0, 1) )
            ->setCellValue("T$i", "В образовательной организации" )
            ->setCellValue("Y$i", $level_of_education_a[$row->level_of_education] )
            ->setCellValue("Z$i", $row->diplom_lastname )

            ->setCellValue("O$i", 'Протокол № ' . $protocol_num )
            ->setCellValue("P$i", 'Приказ № ' . $lstream_name )
            ->setCellValue("R$i", $hours )
            ->setCellValue("U$i", 'Платное обучение' )
            ->setCellValue("AA$i", $date_begin )
            ->setCellValue("AB$i", $date_end )
            ->setCellValue("AD$i", $date_protocol )

            ->setCellValue("AW$i", $enterprise_manager );

           for($j=0; $j<count($course_calendar); $j++){
               $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($course_calendar_index2[$j]."$i", $course_calendar[$j][0] .' | '. $course_calendar[$j][1].' ч. | хорошо' );
           }
        }
        else if( $type_of_program_id==3 ){
           $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("N$i", $row->email )
            ->setCellValue("P$i", $citizenship )
            ->setCellValue("O$i", mb_substr($row->sex, 0, 1) )
            ->setCellValue("T$i", "В образовательной организации" )
            ->setCellValue("Q$i", 'Протокол № ' . $protocol_num )
            ->setCellValue("R$i", 'Приказ № ' . $lstream_name )
            ->setCellValue("T$i", $hours )
            ->setCellValue("W$i", 'Платное обучение' )
            ->setCellValue("S$i", $date_begin )
            ->setCellValue("AW$i", $enterprise_manager );

           for($j=0; $j<count($course_calendar); $j++){
               $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($course_calendar_index3[$j]."$i", $course_calendar[$j][0] .' | '. $course_calendar[$j][1].' ч. | хорошо' );
           }
        }

/*
        ->setCellValue("A$i", $ai)
        ->setCellValue("B$i", 'Оригинал')
        ->setCellValue("C$i", 'Нет')
        ->setCellValue("D$i", 'Нет')
        ->setCellValue("E$i", 'Нет')
        ->setCellValue("H$i", date2rus($date_protocol) )
        ->setCellValue("I$i", $cert_num)
        ->setCellValue("J$i", $ji)
        ->setCellValue("M$i", $rank_of_profession);
*/

/*
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

*/

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
  if($date_str == '' || $date_str == '1000-01-01' )
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
