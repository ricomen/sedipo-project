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



require '../smarty/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->setTemplateDir('../documents/');
$smarty->setCompileDir('../tmp/');
$smarty->setConfigDir('../config/');
$smarty->setCacheDir('../cache/');


$text = '';
$commission_id  = 0;
if(isset($_GET['id']) && intval($_GET['id'])>0){
    $stmt = $dbh->prepare("SELECT `protocol_html`, `a_course`.`commission_id`, `a_lstream`.`date_protocol`  FROM  `a_cohort` LEFT JOIN  `a_course` USING(`course_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)    WHERE `cohort_id`=?");
    $stmt->execute( [intval($_GET['id']) ] );
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
            $html_str = $row['protocol_html'];
            $commission_id  = $row['commission_id'];
            $date_protocol = $row['date_protocol'];
    }
}


//echo $commission_id ;
//echo $date_protocol;

$print_v = 0;
if(isset($_GET['print_v']) && $_GET['print_v']!='false')
   $print_v = 1;



$teachers_commission = [];
$teachers_commission_html2 = '<table border="0">';
$teachers_commission_html2_short = '<table border="0">';
$edition_id = 0;
$directive_num = '№'; 

if($commission_id>0){
    //$stmt = $dbh->prepare("SELECT `edition_id`  FROM `a_teachers_commission_edition` LEFT JOIN  `a_teachers_commission_teacher` USING(`edition_id`)  WHERE  `a_teachers_commission_edition`.`commission_id`=? AND `edition`<?  ORDER BY `edition` DESC, `n` LIMIT 1 ");  
    $stmt = $dbh->prepare("SELECT `edition_id`, `directive_num`  FROM `a_teachers_commission_edition`  WHERE  `commission_id`=? AND `edition`<=?  ORDER BY `edition` DESC LIMIT 1 ");  
    $stmt->execute( [$commission_id, $date_protocol] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
          $edition_id = intval($row->edition_id);
          $directive_num = $row->directive_num;
    }
    $stmt = $dbh->prepare("SELECT  `teacher_id`, `n`, `fullname`, `job_title`, `sign` FROM  `a_teachers_commission_teacher`  WHERE  `edition_id`=?  ORDER BY  `n`");  
    $stmt->execute( [ $edition_id  ] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        if($print_v == 0)
                 $img_sign = '<div style="position: relative; top: -40px; left: 0px;"><div style="position: absolute; top: 1px; left: 1px; "><img width="100px" src="'.$JsonApiURL.'documents/'.$row->sign.'"></div></div>';
        else
                 $img_sign = '';

        if($row->n==0){
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td valign="top">Председатель комиссии: </td><td>'. $img_sign. ' _________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /> </td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td valign="top">Председатель комиссии: </td><td>'. $img_sign. ' _________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /> </td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
        }
        else if($row->n==1){
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td>Члены комиссии: </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /></td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td>Члены комиссии: </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /></td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
        }
        else {
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td>  </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /></td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td>  </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span><br /></td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
        }
    }
}
$teachers_commission_html2 = $teachers_commission_html2 . '</table>';
if($directive_lstream != '')
       $directive_num = $directive_lstream;

$teachers_commission =  [ 'html1'=>'', 'html2'=>$teachers_commission_html2,  'html1_short'=>'', 'html2_short'=>$teachers_commission_html2_short, 'directive_num'=>$directive_num  ];
$smarty->assign('teachers_commission', $teachers_commission);
//print_r($teachers_commission);


if(isset($_GET['print_v']))
    $smarty->assign('print_v', $_GET['print_v']);
else
    $smarty->assign('print_v', 'false');


require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;



header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0



if($_GET['print_v'] && $_GET['print_v']=='edit')
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
/*else if($_GET['print_v'] && $_GET['print_v']=='layout')
{
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$header = file_get_contents('documents-layout_protocol.header.html');
$header = str_replace( "{Id}", intval($_GET['id']),  $header); 
$bottom = file_get_contents('documents-layout.bottom.html');
echo $header;
echo $html_str;
echo $bottom;
}*/

else {
	$dompdf = new Dompdf();
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

//file_put_contents("lst.txt", print_r($report_file, true) );

	$dompdf->loadHtml($html_str, 'UTF-8');
	$dompdf->render();
	$dompdf->stream();
        //$dompdf->stream(rus2translit($report_file). ".pdf", array("Attachment" => 0));
}



/*echo $header;
echo $html_str;
echo $bottom;
*/



?>