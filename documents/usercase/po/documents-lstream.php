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



require '../smarty/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->setTemplateDir('../documents/');
$smarty->setCompileDir('../tmp/');
$smarty->setConfigDir('../config/');
$smarty->setCacheDir('../cache/');


$course_name = [];
$course_date_begin = [];
$course_date_end = [];


$lstream = [];
$users = [];
$teacher = [];
$main_teacher = '';
$main_teacher_a = '';
$form_of_study =  ['',  'Очная', 'Очная с применением дистанционных технологий',  'Заочная',  'Заочная с применением дистанционных технологий', 'Очно-заочная',  'Очно-заочная с применением дистанционных технологий' ];
$course_id = 0; 
$category_id = 0;
$order_date = date("Y-m-d"); 


$stmt = $dbh->prepare("SELECT   DISTINCT   `a_lstream`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `form_of_study`,  `course_id`, `category_id`    FROM `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`) WHERE `lstream_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $course_id = $row['course_id'];
    $category_id = $row['category_id'];
    $date_begin = $row['date_begin'];
    $lstream =  [ 'form_of_study'=>$form_of_study [$row['form_of_study'] ], 'name'=>$row['name'],  'course_name'=>$row['course_name'],  'hours'=>intval($row['hours']),  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'] ];
    $order_date = $row['date_begin'];
}
$smarty->assign('lstream', $lstream);

//$smarty->assign('group', $lstream);




$stmt2 = $dbh->prepare("SELECT  *  FROM `a_self` WHERE `edition`<?  ORDER BY `edition` DESC  LIMIT 1   ");
$stmt2->execute( [ $order_date ] );
if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
    $enterprise_manager_a = explode(" ", $row2['enterprise_manager']);
    $enterprise_manager3 = $enterprise_manager_a[0]. ' ' .mb_substr($enterprise_manager_a[1], 0, 1). '.' .mb_substr($enterprise_manager_a[2], 0, 1). '.';

    $self_data =  [  'name'=>$row2['name'], 'shortname'=>$row2['shortname'],    'inn'=>$row2['inn'], 'kpp'=>$row2['kpp'], 'ogrn'=>$row2['ogrn'], 'addres1'=>$row2['addres1'],  'addres2'=>$row2['addres2'], 'phone'=>$row2['phone'],
      'position_head'=>$row2['position_head'], 'enterprise_manager'=>$row2['enterprise_manager'], 'position_head2'=>$row2['position_head2'], 'enterprise_manager2'=>$row2['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3,  'enterprise_manager_signs'=>$row2['enterprise_manager_signs'], 
      'bank'=>$row2['bank'], 'checking_account'=>$row2['checking_account'], 'correspondent_account'=>$row2['correspondent_account'], 'bik'=>$row2['bik'] ];

    //$data2 =  ['enterprise_manager'=>$row['enterprise_manager'],  'enterprise_manager2'=>$row['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3, 'enterprise_manager_signs'=>$row['enterprise_manager_signs']  ];
}
$smarty->assign('self_data', $self_data);
//print_r($self_data);





$stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `job_title`   FROM `a_cohort`  LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)  LEFT JOIN `a_users` USING(`user_id`) LEFT JOIN `a_job_title` USING(`job_title_id`)     WHERE `lstream_id`=?");
$stmt->execute( [intval($_GET['id'])] );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $users[] = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'], 'subdivision'=>$row['subdivision'], 'position'=>$row['job_title'] ];
}
$smarty->assign('users', $users );


$main_teacher_id = 0;
$teacher = ['lastname'=>'', 'firstname'=>'', 'middlename'=>'' ];
$stmt = $dbh->prepare("SELECT  `main_teacher`  FROM  `a_lstream`  WHERE `lstream_id`=?");
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
    $stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`, `date_of_birth`, `inn`, `snils`, `pasport`, `pasport2`, `phone`, `address`, `education`, `diplom`  FROM `a_teacher` WHERE `user_id`=?");
    $stmt->execute( [ $main_teacher_id ] );
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
        $main_teacher_fio = $row['lastname'].' '.$row['firstname'].' '.$row['middlename'];
        if( mb_substr($row2['middlename'], -1)=='ч')
            $main_teacher_a = $row['lastname'].'а '.$row['firstname'].'а '.$row['middlename'].'а ';
        else
            $main_teacher_a = $row['lastname'].'у '.$row['firstname'].'у '.mb_substr($row['middlename'], 0 -1).'у ';

        $main_teacher_initials = $row2['lastname']. ' ' .mb_substr($row2['firstname'], 0, 1). '.' .mb_substr($row2['middlename'], 0, 1). '.';
    }

    $teacher = [ 'lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'], 'fullname'=>$main_teacher_fio, 'fullname_a'=>$main_teacher_a, 'fullname_initials'=>$main_teacher_initials,   'date_of_birth'=>$row['date_of_birth'], 'inn'=>$row['inn'], 'snils'=>$row['snils'], 'pasport'=>$row['pasport'],  'phone'=>$row['phone'], 'address'=>$row['address'], 'education'=>$row['education'], 'diplom'=>$row['diplom'] ];
}
$smarty->assign('teacher', $teacher );



$scheduler = [];
//$stmt = $dbh->prepare("SELECT  `date`  FROM `a_cohort_scheduler` WHERE `cohort_id`=? AND `work`=1  ORDER BY `date` ");
$stmt = $dbh->prepare("SELECT  `date`  FROM `a_cohort_scheduler` WHERE `lstream_id`=? AND `work`=1  ORDER BY `date` ");
$stmt->execute( [intval($_GET['id'])]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $scheduler[] = $row['date'];
}

$i = 0;
$calendar = [];
$hours1 = 8;
$day = 1;
$stmt = $dbh->prepare("SELECT  `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND `variation`=1  AND (`type`=1 OR `type`=2)  ORDER BY `topic` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hours =  $row['hours'];
    while($hours > 0){
        if($hours >= $hours1) {
             $hours = $hours - $hours1;
             $day = $day +1;
             $h = $hours1;
             $hours1 = 8;
        }
        else {
             $h = $hours;
             $hours1 = $hours1 - $hours;
        }
        $date_i = $scheduler[$day];
        $calendar[] = ['date'=>$date_i, 'topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$h ];
        $i = $i+1;
        if($i>1000) 
            break;
     }
     $hours1 = $hours1 - $h;
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