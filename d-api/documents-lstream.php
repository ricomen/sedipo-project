<?php

require_once '../config.php';
require_once 'd-api_lib.php';
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

require_once '../../config/config-auth.php';
$dbhost_a = $cfg_auth->host;
$dbuser_a = $cfg_auth->user;
$dbpassword_a = $cfg_auth->password;
$dbname_a = $cfg_auth->name;
try {  
    $dbh_a = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


session_start();
$sessionid = session_id();
$auth_session = 0;
$stmt = $dbh_a->prepare('SELECT `login`, `role_id`, `a_account`.`account_id`, `customer_id`   FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `session_id`=? ');
$stmt->execute([$sessionid]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $auth_session = 1;
}
if($auth_session == 0){
    exit();
}


require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;


require '../smarty/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->setTemplateDir('../documents/');
$smarty->setCompileDir('../tmp/');
$smarty->setConfigDir('../config/');
$smarty->setCacheDir('../cache/');


require_once  '../PhpWord/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;


$course_name = [];
$course_date_begin = [];
$course_date_end = [];

$lstream_id = intval($_GET['id']);
$is_attestation = intval($_GET['attestation']);
$lstream = [];
$users = [];
$teacher = [];
$main_teacher = '';
$main_teacher_a = '';
$form_of_study =  ['',  'очная', 'очная с применением дистанционных технологий',  'заочная',  'заочная с применением дистанционных технологий', 'очно-заочная',  'очно-заочная с применением дистанционных технологий' ];
$course_id = 0; 
$category_id = 0;
$order_date = date("Y-m-d"); 


$output_format = '';


$stmt = $dbh->prepare("SELECT   DISTINCT   `a_lstream`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `form_of_study`, `main_teacher`, `course_id`, `category_id`, qualification_work, qualification    FROM `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`) WHERE `lstream_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $course_id = $row['course_id'];
    $category_id = $row['category_id'];
    $date_begin = $row['date_begin'];
    $lstream =  [ 'form_of_study'=>$form_of_study [$row['form_of_study'] ], 'name'=>$row['name'], 'main_teacher'=>$row['main_teacher'], 'qualification'=>$row['qualification'], 'qualification_work'=>$row['qualification_work'], 'course_name'=>$row['course_name'],  'hours'=>intval($row['hours']),  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'] ];
    $order_date = $row['date_begin'];
}
$smarty->assign('lstream', $lstream);

//$smarty->assign('group', $lstream);



$row_count = 0;
$is_students = 1;
if($output_format=="word") {
      $stmt = $dbh->prepare("SELECT count(*) as `count`   FROM `a_cohort`  LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)    LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id` LEFT JOIN  `a_counterparty` ON `a_user_counterparty`.`counterparty_id`=`a_counterparty`.`counterparty_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)     WHERE `lstream_id`=? AND `a_users`.`user_id`!='1' " );
      $stmt->execute( [$order_id ] );
      if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
          $row_count = $row->count;
      }
      try  {
          $phpWord->cloneRow('students-number', $row_count);
      }
      catch(Throwable $s) {
         $is_students = 0;
      }
}

$row_number= 1;
$stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `job_title`, `a_counterparty`.`shortname` as `counterparty_shortname`   FROM `a_cohort`  LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)    LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id` LEFT JOIN  `a_counterparty` ON `a_user_counterparty`.`counterparty_id`=`a_counterparty`.`counterparty_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)     WHERE `lstream_id`=? AND `a_users`.`user_id`!='1'" );
$stmt->execute( [intval($_GET['id'])] );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $users[] = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'], 'subdivision'=>$row['subdivision'], 'position'=>$row['job_title'], 'counterparty_shortname'=>$row['counterparty_shortname'] ];

    if($output_format=="word" && $is_students) {
          $phpWord->setValue('students-number'.'#'.$row_number, $row_number);
          $phpWord->setValue('students-lastname'.'#'.$row_number, $row['lastname']);
          $phpWord->setValue('students-firstname'.'#'.$row_number, $row['firstname']);
          $phpWord->setValue('students-middlename'.'#'.$row_number, $row['middlename']);
          $phpWord->setValue('students-job_of_title'.'#'.$row_number, $row['job_of_title']);
          $phpWord->setValue('students-date_of_birth'.'#'.$row_number, $date_of_birtf);
          $phpWord->setValue('students-user_age'.'#'.$row_number, $user_age);
          $phpWord->setValue('students-snils'.'#'.$row_number, $row['snils']);
          $phpWord->setValue('students-address'.'#'.$row_number, $row['address']);
          $phpWord->setValue('students-pasport_series'.'#'.$row_number, $row['pasport_series']);
          $phpWord->setValue('students-pasport_number'.'#'.$row_number, $row['pasport_number']);
          $phpWord->setValue('students-pasport_unit'.'#'.$row_number, $row['pasport_unit']);
          $phpWord->setValue('students-pasport_unit_number'.'#'.$row_number, $row['pasport_unit_number']);
          $phpWord->setValue('students-email'.'#'.$row_number, $row['email']);
          $phpWord->setValue('students-phone'.'#'.$row_number, $row['phone']);
          $phpWord->setValue('students-fullname3'.'#'.$row_number, formatFio($row['lastname'].' '.$row['firstname'].' '.$row['middlename']) );
     }
     $row_number = $row_number +1;
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

    if($output_format=="word" && $main_teacher_id > 0) {
          $phpWord->setValue('main_teacher-lastname', $row['lastname']);
          $phpWord->setValue('main_teacher-firstname', $row['firstname']);
          $phpWord->setValue('main_teacher-middlename', $row['middlename']);
          $phpWord->setValue('main_teacher-job_of_title', $row['job_of_title']);
          $phpWord->setValue('main_teacher-date_of_birth', $date_of_birtf);
          $phpWord->setValue('main_teacher-user_age', $user_age);
          $phpWord->setValue('main_teacher-snils', $row['snils']);
          $phpWord->setValue('main_teacher-address'.'#'.$row_number, $row['address']);
          $phpWord->setValue('main_teacher-pasport_series', $row['pasport_series']);
          $phpWord->setValue('main_teacher-pasport_number', $row['pasport_number']);
          $phpWord->setValue('main_teacher-pasport_unit', $row['pasport_unit']);
          $phpWord->setValue('main_teacher-pasport_unit_number', $row['pasport_unit_number']);
          $phpWord->setValue('main_teacher-email', $row['email']);
          $phpWord->setValue('main_teacher-fullname3'.'#'.$row_number, formatFio($row['lastname'].' '.$row['firstname'].' '.$row['middlename']) );
    }

}
$smarty->assign('teacher', $teacher );


$calendar1 =[];
$scheduler = [];
//$stmt = $dbh->prepare("SELECT  `date`  FROM `a_cohort_scheduler` WHERE `cohort_id`=? AND `work`=1  ORDER BY `date` ");
$stmt = $dbh->prepare("SELECT DISTINCT `date`  FROM `a_cohort_scheduler` WHERE `lstream_id`=? AND `work`=1  ORDER BY `date` ");
$stmt->execute( [intval($_GET['id'])]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $scheduler[] = $row['date'];
    $schedulerarr[] = ['date'=>$row['date']];
}
$calendarcount = count($scheduler);

$i = 0;
$calendar = [];
$hours1 = $HoursPerDay;
$day = 0;
$teorcount = 0;
$shdel = 0;
if($is_attestation>0)
   $type_str = ' OR `type`=3';
else 
   $type_str = '';

$stmt = $dbh->prepare("SELECT DISTINCT `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND `variation`=1  AND (`type`=1 OR `type`=2 $type_str)  ORDER BY `topic` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $calendar1[] = ['topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ]; 
    $teorcount+=intval($row['hours']);
}
$calendar=createSchedule($scheduler,$calendar1,$HoursPerDay);
$teorcountsum = $teorcount/$HoursPerDay;
$teorcountsum;
//$shedullercount = count($calendar);

$smarty->assign('calendar', $calendar );
$smarty->assign('shedullercount', $calendarcount  );
$smarty->assign('scheduler', $schedulerarr  );

$calendar2 = [];
$calendar3 = [];
$schedulerpr = [];
$schedulerprf = [];
$schedulerprl = [];
$stmt = $dbh->prepare("SELECT `day`, `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND  `type`=4 ORDER BY `num` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
/*    $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])) );
    if($scheduler[$i] != $date_i)
            continue;
*/            
    $schedulerpr = array_slice($scheduler,$teorcountsum);
    $calendar3[] = ['topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ];
    $calendar2=createSchedule($schedulerpr,$calendar3,$HoursPerDay);
    $i = $i+1;
}
$schedulerprf = $schedulerpr[0];
$schedulerprl = end($schedulerpr);
$smarty->assign('calendar2', $calendar2 );
$smarty->assign('teorcountsum', $teorcountsum );
$smarty->assign('teorcount', $teorcount );
$smarty->assign('schedulerprf', $schedulerprf );
$smarty->assign('schedulerprl', $schedulerprl );




$stmt2 = $dbh->prepare("SELECT  *  FROM `a_self` WHERE `edition`<?  ORDER BY `edition` DESC  LIMIT 1   ");
$stmt2->execute( [ $order_date ] );
if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
    $enterprise_manager_a = explode(" ", $row2['enterprise_manager']);
    $enterprise_manager3 = $enterprise_manager_a[0]. ' ' .mb_substr($enterprise_manager_a[1], 0, 1). '.' .mb_substr($enterprise_manager_a[2], 0, 1). '.';

    $self_data =  [  'name'=>$row2['name'], 'shortname'=>$row2['shortname'],    'inn'=>$row2['inn'], 'kpp'=>$row2['kpp'], 'ogrn'=>$row2['ogrn'], 'addres1'=>$row2['addres1'],  'addres2'=>$row2['addres2'], 'phone'=>$row2['phone'],
      'position_head'=>$row2['position_head'], 'enterprise_manager'=>$row2['enterprise_manager'], 'position_head2'=>$row2['position_head2'], 'enterprise_manager2'=>$row2['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3,  'enterprise_manager_signs'=>$row2['enterprise_manager_signs'], 
      'bank'=>$row2['bank'], 'checking_account'=>$row2['checking_account'], 'correspondent_account'=>$row2['correspondent_account'], 'bik'=>$row2['bik'], 'license'=>$row2['license'], 'accreditation'=>$row2['accreditation'], 'city'=>$row2['city'] ];

}
$smarty->assign('self_data', $self_data);
//print_r($self_data);






if(isset($_GET['print_v']))
    $smarty->assign('print_v', $_GET['print_v']);
else
    $smarty->assign('print_v', 'false');



//echo $course_id;
//print_r($scheduler);


	$html_str = $smarty->fetch('../documents/'.$_GET['template']);

//echo $html_str;

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






?>