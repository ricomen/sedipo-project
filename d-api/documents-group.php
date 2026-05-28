<?php

require_once '../config.php';
$dbhost_a = $cfg->host;
$dbuser_a = $cfg->user;
$dbpassword_a = $cfg->password;
$dbname_a = $cfg->name;
try {  
    $dbh = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}



session_start();
$sessionid = session_id();
$auth_session = 0;
$stmt = $dbh->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
$stmt->execute([$sessionid]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $auth_session = 1;
}
if($auth_session == 0){
    exit();
}



//$html_str = file_get_contents('documents/'.$_GET['template']);



$vars_str = file_get_contents('../documents/'.$_GET['vars']);
$vars_arr = explode(",", str_replace(" ", "", $vars_str) );


//require_once 'smarty/vendor/autoload.php';
//use Smarty\Smarty;

require '../smarty/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->setTemplateDir('../documents/');
$smarty->setCompileDir('../tmp/');
$smarty->setConfigDir('../config/');
$smarty->setCacheDir('../cache/');


//$sql_str = file_get_contents('documents/'.$_GET['sql']);
//$lines = explode("\n", $content);


$course_name = [];
$course_date_begin = [];
$course_date_end = [];


$cohort = [];
$users = [];
$teacher = [];
$main_teacher = '';
$main_teacher_a = '';
$form_of_study =  ['',  'Очная', 'Очная с применением дистанционных технологий',  'Заочная',  'Заочная с применением дистанционных технологий', 'Очно-заочная',  'Очно-заочная с применением дистанционных технологий' ];
$order_id = 0;
$counterparty_name = '';
$course_id = 0; 
$category_id = 0;

$stmt = $dbh->prepare("SELECT  `a_order_users`.`order_id`, `a_order`.`counterparty_id`, `a_counterparty`.`name` as 'counterparty_name'   FROM `a_cohort`  LEFT JOIN `a_order_users` USING(`cohort_id`) LEFT JOIN `a_order` USING(`order_id`) LEFT JOIN `a_counterparty` USING(`counterparty_id`)    WHERE `a_cohort`.`cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
      $counterparty_name = $row['counterparty_name'];
      $order_id = $row['order_id'];
}

$stmt = $dbh->prepare("SELECT  `a_cohort`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `main_teacher`, `form_of_study`, `enterprise_manager`, `enterprise_manager2`, `course_id`, `category_id`    FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $stmt2 = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`  FROM `a_teacher` WHERE `user_id`=?");
    $stmt2->execute( [$row['main_teacher']] );
    if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
        $main_teacher = $row2['lastname'].' '.$row2['firstname'].' '.$row2['middlename'];
        if( mb_substr($row2['middlename'], -1)=='ч')
            $main_teacher_a = $row2['lastname'].'а '.$row2['firstname'].'а '.$row2['middlename'].'а ';
        else
            $main_teacher_a = $row2['lastname'].'у '.$row2['firstname'].'у '.mb_substr($row2['middlename'], 0 -1).'у ';
    }
    $enterprise_manager3 = $row['enterprise_manager'];
    //$enterprise_manager3 = $enterprise_manager[0]. ' ' .mb_substr($enterprise_manager[1], 0, 1). '.' .mb_substr($enterprise_manager[2], 0, 1). '.';
    $main_teacher_short = $row2['lastname']. ' ' .mb_substr($row2['firstname'], 0, 1). '.' .mb_substr($row2['middlename'], 0, 1). '.';
    $course_id = $row['course_id'];
    $category_id = $row['category_id'];
    $date_begin = $row['date_begin'];
    $cohort =  [ 'form_of_study'=>$form_of_study [$row['form_of_study'] ], 'name'=>$row['name'],  'course_name'=>$row['course_name'],  'hours'=>intval($row['hours']),  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'],  'counterparty_name'=>$counterparty_name, 'enterprise_manager'=>$row['enterprise_manager'], 'enterprise_manager2'=>$row['enterprise_manager2'], 'main_teacher_id'=>$row['main_teacher'], 'main_teacher'=>$main_teacher, 'main_teacher_a'=>$main_teacher_a, 'main_teacher_short'=>$main_teacher_short, 'enterprise_manager3'=>$row['enterprise_manager'] ];
}
$smarty->assign('group', $cohort);
//print_r($courses);
//echo $group_id;



//if($contract_order_id > 0 && $contract_enterprise_manager != ''){
//    $stmt = $dbh->prepare("SELECT  	`date_contract` as `date_order`, `enterprise_manager`, `enterprise_manager2`, `enterprise_manager_signs`  FROM `a_counterparty_contract`  WHERE `contract_id`=? AND  `counterparty_id`=?  ");
//    $stmt->execute( [ intval($_GET['template_id']),  intval($_GET['counterparty_id'])   ] );
//}
//else 
if($order_id >0 ){
    $stmt = $dbh->prepare("SELECT `date_order`  FROM `a_order`  WHERE `order_id`=?   ");
    $stmt->execute( [$order_id  ] );
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
       if( $date_contract == '' )
            $date_contract = $row['date_order'];
       }
}
if( $date_contract == '' ){
     $stmt = $dbh->prepare("SELECT CURDATE() as `date_order`, `enterprise_manager`, `enterprise_manager2`, `enterprise_manager_signs`  FROM `a_self`  WHERE `edition`<=?  ORDER BY `edition` DESC  LIMIT 1   ");
     $stmt->execute( [$date_contract] );
}
else {
    $stmt = $dbh->prepare("SELECT CURDATE() as `date_order`, `enterprise_manager`, `enterprise_manager2`, `enterprise_manager_signs`  FROM `a_self`   ORDER BY `edition` DESC  LIMIT 1   ");
    $stmt->execute( [] );
}
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    if($contract_order_id == 0 && $longtime_contract0 > 0 && $order_id >0 ){
        $stmt2 = $dbh->prepare("UPDATE `a_counterparty_contract` SET  `enterprise_manager`=?, `enterprise_manager2`=?, `enterprise_manager_signs`=?, `order_id`=?    WHERE `contract_id`=? AND  `counterparty_id`=? ");
        $stmt2->execute( [$row['enterprise_manager'],  $row['enterprise_manager2'], $row['enterprise_manager_signs'], $order_id,   intval($_GET['template_id']),  intval($_GET['counterparty_id']) ] );
    }

    if( $date_contract == '' )
            $date_contract = $row['date_order'];
    
    $date_contract_finish = explode('-', $date_contract)[0] . '-12-31';

    $enterprise_manager_a = explode(" ", $row['enterprise_manager']);
    $enterprise_manager3 = $enterprise_manager_a[0]. ' ' .mb_substr($enterprise_manager_a[1], 0, 1). '.' .mb_substr($enterprise_manager_a[2], 0, 1). '.';
    $data2 =  ['enterprise_manager'=>$row['enterprise_manager'],  'enterprise_manager2'=>$row['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3, 'enterprise_manager_signs'=>$row['enterprise_manager_signs']  ];
    $data3 =  ['contract_number'=>$contract_number_s, 'date_contract'=>$date_contract,  'date_contract_finish'=>$date_contract_finish ];
}
$smarty->assign('self_data', $data2);


$stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`   FROM  `a_order_users` LEFT JOIN `a_users` USING(`user_id`) LEFT JOIN `a_positions` USING(`job_title_id`)     WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $users[] = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'], 'subdivision'=>$row['subdivision'], 'position'=>$row['position'] ];
}
$smarty->assign('users', $users );


$main_teacher_id = 0;
$teacher = ['lastname'=>'', 'firstname'=>'', 'middlename'=>'' ];
$stmt = $dbh->prepare("SELECT  `main_teacher`  FROM  `a_cohort`  WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
    $main_teacher_id = $row->main_teacher;
}
if($main_teacher_id == 0 ){
    $stmt = $dbh->prepare("SELECT  `user_id`  FROM  `a_teacher_course`  WHERE `course_id`=?  ORDER BY `priority` ");
    $stmt->execute( [ $course_id ] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $main_teacher_id = $row->user_id;
    }
}
if($main_teacher_id == 0 ){
    $stmt = $dbh->prepare("SELECT  `user_id`  FROM  `a_teacher_course`  WHERE `category_id`=?  ORDER BY `priority` ");
    $stmt->execute( [ $course_id ] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $main_teacher_id = $row->user_id;
    }
}
if($main_teacher_id > 0   ){
    $stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`   FROM   `a_teacher`   WHERE `user_id`=?");
    $stmt->execute( [ $main_teacher_id ] );
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
        $teacher = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'] ];
    }
}
$smarty->assign('teacher', $teacher );


$scheduler = [];
$stmt = $dbh->prepare("SELECT  `date`  FROM `a_cohort_scheduler` WHERE `cohort_id`=? AND `work`=1  ORDER BY `date` ");
$stmt->execute( [intval($_GET['id'])]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $scheduler[] = $row['date'];
}

$i = 0;
$calendar = [];
$stmt = $dbh->prepare("SELECT `day`, `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND  `type`=1 ORDER BY `num` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
/*    $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])+$i) );
    while($scheduler[$i] != $date_i && $i<100){
        $i = $i+1;
        $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])+$i) );
    }*/
    $date_i = $scheduler[intval($row['day']-1)];
    $calendar[] = ['date'=>$date_i, 'topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ];
    $i = $i+1;
    $calendarcount = $i;
}
$smarty->assign('calendar', $calendar );
$smarty->assign('calendarcount', $calendarcount );

$calendar2 = [];
$stmt = $dbh->prepare("SELECT `day`, `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND  `type`=2 ORDER BY `num` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
/*    $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])) );
    if($scheduler[$i] != $date_i)
            continue;
*/            
    $date_i = $scheduler[intval($row['day']-1)];
    $calendar2[] = ['date'=>$date_i, 'topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ];
    $i = $i+1;
}
$smarty->assign('calendar2', $calendar2 );


if(isset($_GET['print_v']))
    $smarty->assign('print_v', $_GET['print_v']);
else
    $smarty->assign('print_v', 'false');



//echo $course_id;
//print_r($scheduler);


	$html_str = $smarty->fetch('../documents/'.$_GET['template']);

//echo $html_str;

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if($_GET['print_v'] && $_GET['print_v']=='office')
{
//header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
header('Content-Type: application/vnd.oasis.opendocument.text');
header('Content-Disposition: attachment; filename="'.rus2translit($_GET['file']).'.odt"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

/*$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');*/
echo $html_str;
}


else if($_GET['print_v'] && $_GET['print_v']=='edit')
{

header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$header = file_get_contents('documents-editor.header.html');
$bottom = file_get_contents('documents-editor.bottom.html');
echo $header;
echo $html_str;
echo $bottom;
}
else if($_GET['print_v'] && $_GET['print_v']=='layout')
{
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$header = file_get_contents('documents-layout.header.html');
$header = str_replace( "{Id}", $groupid, $header); 
$bottom = file_get_contents('documents-layout.bottom.html');
echo $header;
echo $html_str;
echo $bottom;
}


else {

	$dompdf = new Dompdf();
	$dompdf->setPaper('A4', $paper);
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

	$dompdf->loadHtml($html_str, 'UTF-8');
	$dompdf->render();
	//$dompdf->stream();
    $dompdf->stream(rus2translit($_GET['file']) . ".pdf", array("Attachment" => 0));
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