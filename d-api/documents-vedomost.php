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



$vars_str = file_get_contents('documents/'.$_GET['vars']);
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

$category_id = 0; 
$course_name = [];
$course_date_begin = [];
$course_date_end = [];


$cohort = [];
$teacher = [];
$course = [];
$users = [];
$users_header = [];
$protocol = [];
$course_shortname = ''; 
$add_header = [];
$add_default  = [];


$stmt = $dbh->prepare("SELECT  `a_cohort`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `main_teacher`, `enterprise_manager`, `enterprise_manager2`, `course_id`   FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $course_id = $row['course_id'];
    $date_begin = $row['date_begin'];
    $cohort =  [ 'name'=>$row['name'],  'course_name'=>$row['course_name'],  'hours'=>$row['hours'],  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'],  'main_teacher'=>$row['main_teacher'], 'enterprise_manager'=>$row['enterprise_manager'], 'enterprise_manager2'=>$row['enterprise_manager2'] ];
}
$smarty->assign('group', $cohort);
//print_r($courses);
//echo $group_id;

$stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`   FROM  `a_order_users` LEFT JOIN `a_users` USING(`user_id`) LEFT JOIN `a_job_title` USING(`job_title_id`)     WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $users1[] = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'], 'subdivision'=>$row['subdivision'], 'position'=>$row['position'] ];
}
$smarty->assign('users1', $users1 );



$cohort = [];
$users = [];
$teacher = [];
$main_teacher = '';
$main_teacher_a = '';

//LEFT JOIN `a_counterparty` USING(counterparty_id)     , `a_counterparty`.`name` as 'counterparty'     , 'counterparty'=>$row2['counterparty'] 
$stmt = $dbh->prepare("SELECT  `a_cohort`.`name`,  `a_course`.`name` as `course_name`, `hours`, `date_begin`, `date_end`, `date_protocol`, `main_teacher`, `enterprise_manager`, `enterprise_manager2`, `course_id`   FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) WHERE `cohort_id`=?");
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
    $course_id = $row['course_id'];
    $date_begin = $row['date_begin'];
    $cohort =  [ 'name'=>$row['name'],  'course_name'=>$row['course_name'],  'hours'=>intval($row['hours']),  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'],  'main_teacher'=>$row['main_teacher'], 'enterprise_manager'=>$row['enterprise_manager'], 'enterprise_manager2'=>$row['enterprise_manager2'], 'main_teacher_id'=>$row['main_teacher'], 'main_teacher'=>$main_teacher, 'main_teacher_a'=>$main_teacher_a ];
}
$smarty->assign('group', $cohort);
//print_r($courses);
//echo $group_id;



$stmt = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`   FROM  `a_cohort` LEFT JOIN `a_teacher` ON `a_cohort`.`main_teacher`=`a_teacher`.`user_id`  WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $teacher = ['lastname'=>$row['lastname'], 'firstname'=>$row['firstname'], 'middlename'=>$row['middlename'] ];
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
    $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])+$i) );
//echo $scheduler[$i], ' ', $date_i, ' |';     
    while($scheduler[$i] != $date_i && $i<100){
        $i = $i+1;
        $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])+$i) );
//echo $scheduler[$i], ' ', $date_i, ' |'; 
    }    
    $calendar[] = ['date'=>$date_i, 'topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ];
}
$smarty->assign('calendar', $calendar );

$calendar2 = [];
$stmt = $dbh->prepare("SELECT `day`, `topic`, `name_topic`, `hours` FROM   `a_course_calendar`  WHERE `course_id`=? AND  `type`=2 ORDER BY `num` ");
$stmt->execute( [$course_id]  );
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $date_i = date('Y-m-d', strtotime($date_begin) + 86400*(intval($row['day'])) );
    if($scheduler[$i] != $date_i)
            continue;
    $calendar2[] = ['date'=>$date_i, 'topic'=>$row['topic'], 'name'=>$row['name_topic'], 'hours'=>$row['hours'] ];
    $i = $i+1;
}
$smarty->assign('calendar2', $calendar2 );



//=========================================================


$stmt = $dbh->prepare("SELECT  `a_course`.`name`, `a_course`.`category_id`, `a_course`.`shortname`, `hours`, `date_begin`, `date_end`, `date_protocol`,  MONTH(`date_protocol`) as `month_protocol`,  DAYOFMONTH(`date_protocol`) as `day_protocol`,  `protocol_num`, `a_cohort`.`chairman`, `a_cohort`.`teachers_commission`, `a_cohort`.`directive`,  `finalexamination`, `certificate_grade`, `name_common`  FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) WHERE `cohort_id`=?");
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
     

    //$chairman_a =  preg_split("/[\s\t]+/",$row['chairman']);
    $chairman_a =  preg_split("/–[\s\t]+/",$row['chairman']);
    if(count($chairman_a)<2)
        $chairman_a =  preg_split("/-[\s\t]+/", $row['chairman']);
    $teachers_commission_str ='';
    $teachers_commission_a = explode("\n", trim($row['teachers_commission']));
    $teachers_commission1 = '';
    $teachers_commission1_title = '<p class="left">Члены комиссии: </p>';
    $teachers_commission1_signs = [];
    foreach ( $teachers_commission_a as $ti ){
        $t_a = preg_split("/–[\s\t]/", $ti);
        if( count($t_a)<2 )
            $t_a = preg_split("/-[\s\t]/", $ti);
        $t_fio = trim($t_a[0]); /*' '.$t_a[1].' '.$t_a[2].*/
        $teachers_commission_str = $teachers_commission_str .' _________________ /'. $t_fio.'/<br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br />'; 
        
        $ti = str_replace("–", ";", $ti); 
        $ti = str_replace("-", ";", $ti); 
        $ti_a = explode(";", $ti);
        $teachers_commission1 = $teachers_commission1 .  '<tr><td>'. $teachers_commission1_title .'</td><td><p class="left">' . $ti_a[0]. '</p></td><td><p class="left">– ' . $ti_a[1]. '</p></td></tr>';
        $teachers_commission1_title = '';
        $teachers_commission1_signs[] =  $ti_a[2];
    }

     
    $chairman = '<tr><td><p class="left">Председатель комиссии: </p></td><td><p class="left"> '. trim($row['chairman']) . '</p></td></tr>';
    $chairman = str_replace("–", '</p></td><td><p class="left">– ', $chairman);
    $chairman = str_replace("-", '</p></td><td><p class="left">– ', $chairman);
//    $teachers_commission = str_replace("\n", '<br />', trim($row['teachers_commission']));

    $teachers_commission = '<tr><td valign="top">Члены комиссии: </td><td><p class="left"> '. str_replace("\n", '</p></td></tr><tr><td></td><td><p class="left">', trim($row['teachers_commission']));
    $teachers_commission = str_replace("–", '</p></td><td><p class="left">– ', $teachers_commission);
    $teachers_commission = str_replace("-", '</p></td><td><p class="left">– ', $teachers_commission);
    
    if( $row['name_common']!='')
          $course_name = $row['name_common'].' '.$row['name'];
    else
          $course_name = $row['name'];
    $protocol_date = $row['date_protocol'];
    $protocol_num = sprintf('%02d', $row['month_protocol']).'/'.sprintf('%02d', $row['day_protocol']).'/'.sprintf('%d', $row['protocol_num']);
    $course =  [ 'name'=>$course_name,  'hours'=>$row['hours'],  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'], 'protocol_num'=>$protocol_num, 'chairman'=>$chairman, 'teachers_commission'=>$teachers_commission1, 'directive'=>$row['directive'], 'chairman2'=>'/'.trim($chairman_a[0])./*' '.$chairman_a[1].' '.$chairman_a[2].*/'/', 'teachers_commission2'=>$teachers_commission_str, 'teachers_commission_signs'=>$teachers_commission1_signs ];
    $category_id = $row['category_id'];
    $course_shortname = $row['shortname'];
    $finalexamination = $row['finalexamination'];
    if(trim($finalexamination)=='')
         $finalexamination = 'Сдано';
    $certificate_grade = $row['certificate_grade'];
    if(trim($certificate_grade)!='' )
          $certificate_grade = $certificate_grade.' <br />';
    
}
$smarty->assign('course', $course);

//print_r($course);

$is_rank_of_profession = 'false';
$stmt = $dbh->prepare("SELECT *   FROM `a_course_category`  WHERE `category_id`=?");
$stmt->execute( [$category_id] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $is_rank_of_profession = $row['is_rank_of_profession'];
    $protocol_temlate = $row['protocol_temlate'];
    if($row['add_header']!='') {
        $add_header = explode(';', $row['add_header']);
        for ($i = 0; $i < count($add_header); $i++) {
                $add_header[$i] = '<div style="font-size: 8pt;">'.$add_header[$i].'</div>' ;
        }
        $add_default  = explode(';', $row['add_default']);
        for ($i = 0; $i < count($add_default); $i++) {
              if($add_default[$i]=='')
                    $add_default[$i] =  '&#x2009;' ;
        }
    }
    //$protocol = [ 'protocol_p1'=>$row2['protocol_p1'], 'protocol_p2'=>$row2['protocol_p2'], 'protocol_p3'=>$row2['protocol_p3'], 'is_position'=>$row2['is_position'], 'is_organization'=>$row2['is_organization'], 'is_certificate'=>$row2['is_certificate'], 'certificate_header'=>$row2['certificate_header']  ];

    $user_row = [];
    $user_row[] =  '<div style="font-size: 8pt;">Ф.И.О. обучающегося
(полностью)
</div>';
    //$user_row[] =  'Фамилия';
    //$user_row[] =  'Имя';
    //$user_row[] =  'Отчество';
    //if($row['is_position']=='true')
    //    $user_row[] =  '<div style="font-size: 8pt;">Должность</div>';
    
    //if($row['is_organization']=='true')
    //    $user_row[] =  '<div style="font-size: 8pt;">Наименование организации</div>';

   if('true')
       $user_row[] =  '<div style="font-size: 8pt;">№ группы</div>' ;
        
   if('true')
       $user_row[] =  '<div style="font-size: 8pt;">Протокол (номер, дата)</div>' ;
        

    if($row['is_finalexamination']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Итоговая аттестация</div>';
        
    if($row['is_certificate']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Номер удостоверения о повышении квалификации</div>' ;
        
    if('true')
        $user_row[] =  '<div style="font-size: 8pt;">ФИО выдавшего документ</div>' ;
        
    if('true')
        $user_row[] =  '<div style="font-size: 8pt;">Подпись выдавшего документ</div>' ;
        
    if('true')
        $user_row[] =  '<div style="font-size: 8pt;">Подпись получившего документ</div>' ;
  
        
    $users_header = array_merge($user_row); 
    
    $protocol['protocol_h']   =  $row['protocol_h'];
    $protocol['directive']    =  $row['directive'];
    $protocol['protocol_p1']  =  $row['protocol_p1'];
    $protocol['protocol_p2']  =  $row['protocol_p2'];
    $protocol['protocol_p3']  =  $row['protocol_p3'];
    $protocol['is_hours_p']   =  $row['is_hours_p']; 

}
$smarty->assign('users_header', $users_header );
$smarty->assign('protocol', $protocol );


$rank_of_profession = 0;
$rank_of_profession_name =  ['','','2 (второй) разряд','3 (третий) разряд','4 (четвертый) разряд','5 (пятый) разряд','6 (шестой) разряд','7 (седьмой) разряд','8 (восьмой) разряд'];
$stmt2 = $dbh->prepare("SELECT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`, `a_counterparty`.`name` as 'counterparty', `a_counterparty`.`shortname` as 'counterparty_shortname', `certificate_num`, `order_id`, `user_id`, `course_id`   FROM `a_order_users` LEFT JOIN `a_users` USING(`user_id`) LEFT JOIN `a_job_title` USING(`job_title_id`)  LEFT JOIN `a_counterparty` USING(counterparty_id)    WHERE `cohort_id`=? ORDER BY `certificate_num`, `lastname` ");
$stmt2->execute( [ intval($_GET['id']) ] );
while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
    //$users[] = ['lastname'=>$row2['lastname'], 'firstname'=>$row2['firstname'], 'middlename'=>$row2['middlename'], 'subdivision'=>$row2['subdivision'], 'position'=>$row2['position'], 'counterparty'=>$row2['counterparty']  ];
    $user_row = [];
    //$user_row[] =  $row2['lastname'];
    //$user_row[] =  $row2['firstname'];
    //$user_row[] =  $row2['middlename'];
    $user_row[] =  '<div style="text-align: left;">'.$row2['lastname'] .' '. $row2['firstname'] .' '. $row2['middlename'].'</div>';
    
     $user_row[] = $cohort['name'];
     $user_row[] = '<div style="text-align: left;">№' .$protocol_num.' от '.$protocol_date.'</div>' ;
   // if($row['is_position']=='true')
   //     $user_row[] =  $row2['position'];
    
    //if($row['is_organization']=='true') {
        //$user_row[] =  $row2['counterparty'];
    //    $user_row[] =  $row2['counterparty_shortname'];
   // }    
        
    if($row['is_finalexamination']=='true')
        $user_row[] =  $finalexamination;
        //$user_row[] = $course_shortname . '<br />Сдано';

    if($row['is_certificate']=='true')
        $user_row[] =   $certificate_grade.'№ '.$protocol_num.'-'.sprintf('%02d', $row2['certificate_num']);
        
    if($is_rank_of_profession =='true') {
        $rank_of_profession = 0;
        $stmt3 = $dbh->prepare("SELECT  `rank_of_profession`  FROM `a_order_users`  WHERE `order_id`=? AND `user_id`=? AND `course_id`=? AND  `rank_of_profession`>0 ");
        $stmt3->execute( [ $row2['order_id'], $row2['user_id'], $row2['course_id'] ] );
        if($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {  
             $rank_of_profession =  $row3['rank_of_profession'];
        }
        $user_row[] =   $rank_of_profession_name[$rank_of_profession];
    }
    
    $users[] = array_merge($user_row, $add_default); 
}
$smarty->assign('users', $users );

if(isset($_GET['print_v']))
    $smarty->assign('print_v', $_GET['print_v']);
else
    $smarty->assign('print_v', 'false');


//print_r($users_header);
//print_r($users);
//print_r($protocol);

	$html_str = $smarty->fetch('../documents/'.$_GET['template']);
	//$html_str = $smarty->fetch('documents/usercase/kpk/vedomost.html');

//echo $html_str;


require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

/*require_once  'PhpWord/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;*/



if($_GET['print_v'] && $_GET['print_v']=='office')
{
/*$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
Html::addHtml($section, $html_str);

$targetFile = __DIR__ . "/1.docx";
$phpWord->save($targetFile, 'Word2007');*/

 	
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

$header = file_get_contents('documents-editor_protocol.header.html');
$header = str_replace( "{Id}", intval($_GET['id']), $header); 
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
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

	//$html_str = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style> body { font-family: DejaVu Sans; font-size:12px; }</style><body> hello world по русски </body></html>';

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