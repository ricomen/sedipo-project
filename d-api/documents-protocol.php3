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
$stmt = $dbh_a->prepare('SELECT `login`, `role_name`, `a_account`.`account_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`) LEFT JOIN `a_role` USING(`role_id`)   WHERE `session_id`=? ');
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

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once  '../PhpWord/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Html;

$print_v = 0;
if(isset($_GET['print_v']) && $_GET['print_v']!='false')
   $print_v = 1;


$groupid = intval($_GET['groupid']);
//if($groupid==0)
//      $groupid = intval($_GET['id']);

$output_format = '';
$page_size = '';
$phpWord = null;


function report_protocol($groupid, $order_id  ){
 global  $dbh, $smarty, $JsonApiURL, $is_short_certificate_num, $IS_SECR;
//global  $template_name, $template_file, $template_footer_file,
 global   $report_file, $print_v;
 global $output_format, $page_size;
 global $phpWord;

 $category_id = 0; 
 $template_id = 0;
 $course_name = [];
 $course_date_begin = [];
 $course_date_end = [];

 $course = [];
 $users = [];
 $users_header = [];
 $protocol = [];
 $course_shortname = ''; 
 $add_header = [];
 $add_default  = [];
 $commission_id = 0;
 $date_protocol = '0000-00-00';


//if($groupid>0 ){
 $date_protocol = '1000-01-01';
 $directive_lstream =  '';
 $is_rank_of_profession =='false';
 $lstream_id = 0;
 $stmt = $dbh->prepare("SELECT  `a_course`.`course_id`, `a_cohort`.`group_number`, `a_course`.`name`, `a_course`.`category_id`, `a_course`.`shortname`, `hours`, `competence`, `qualification`, `area`, `date_begin`, `date_end`, `date_protocol`, `a_cohort`.`date_num`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`,  `a_cohort`.`protocol_num`,  `a_lstream`.`commission_id`, `a_lstream`.`directive_num`, `name_common`, `protocol_template`, `a_cohort`.`lstream_id`, `list_documents_num`  FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_lstream` USING(`lstream_id`)  WHERE `cohort_id`=?");
 $stmt->execute( [$groupid] );
 if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $category_id = $row['category_id'];
    $category_name = '';
    $lstream_id = $row['lstream_id'];
    $course_shortname = $row['shortname'];
    $date_protocol = $row['date_protocol'];
    $directive_lstream = $row['directive_num'];

    $template_id = $row['protocol_template'];
    $template_name = '';
    $template_file = '';
    $report_file = '';
    $template_file_php = '';
    $template_footer_file = '';
    $output_format = '';
    $page_size = '';

    $is_blank = 0; 
    if(intval($_GET['add_template'])>0 ){
        $template_id = intval($_GET['add_template']);
    }
    $stmt2 = $dbh->prepare('SELECT  `name`, `file`, `footer_file`, `file_php`, `num_of_page`, `type`, `is_blank`, `size_page`, `output_format`   FROM  `a_template`  WHERE `template_id`=? ');
    $stmt2->execute([ $template_id ]);
    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
        $num_of_page = $row2->num_of_page;
        $template_name = $row2->name;
        $template_file = $row2->file;
        $template_footer_file = $row2->footer_file;
        $template_file_php = $row2->file_php;
        $is_blank = $row2->is_blank;
        $output_format = $row2->output_format;
        $page_size = $row2->size_page;

    }
    if($template_file_php != '')
         include '../documents/'.$template_file_php;
//      require '../documents/'.$template_file_php;
//print_r($protocol_template);

    if($output_format=="word") {
         $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('../documents/'.$template_file);
    }
    else {
        $phpWord = null;
    }


    $stmt2 = $dbh->prepare("SELECT *   FROM `a_course_category`  WHERE `category_id`=?");
    $stmt2->execute( [$category_id] );
    if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  
         $is_rank_of_profession = $row2['is_rank_of_profession'];
         $category_name = $row2['name']; 
    }

    $custom_protocol_num = ''; 
    $stmt2 = $dbh->prepare('SELECT  `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? AND `group_number`=? ');
    $stmt2->execute([$order_id, $row['course_id'], $row['group_number'] ]);
    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
          $custom_protocol_num = $row2->custom_protocol_num;
    }

    $commission_id  = $row['commission_id'];
    if( $row['name_common']!='')
          $course_name = $row['name_common'].' '.$row['name'];
    else
          $course_name = $row['name'];

    if($is_short_certificate_num)
         $protocol_num = sprintf('%02d', $row['month_protocol']).'/'.sprintf('%02d', $row['day_protocol']).'/'.sprintf('%d', $row['protocol_num']);
    else
         $protocol_num = substr(sprintf('%04d', $row['year_protocol']), 2, 2).'/'.sprintf('%02d', $row['month_protocol']).'/'.sprintf('%02d', $row['day_protocol']).'/'.sprintf('%d', $row['protocol_num']);

    if( $custom_protocol_num != '')
        $protocol_number = $custom_protocol_num;
    else
        $protocol_number = $protocol_num;


    $list_documents_num = '...';
    $stmt3 = $dbh->prepare("SELECT  YEAR(`date_protocol`) as `year_list`,  MONTH(`date_protocol`) as `month_list`,  DAYOFMONTH(`date_protocol`) as `day_list`   FROM `a_lstream`  WHERE `lstream_id`=?");
    $stmt3->execute( [ $row['lstream_id'] ] );
    if($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {  
        if($is_short_certificate_num)
             $list_documents_num = sprintf('%02d', $row3['month_list']).'/'.sprintf('%02d', $row3['day_list']).'/'.sprintf('%d', $row['list_documents_num']);
        else
             $list_documents_num = substr(sprintf('%04d', $row3['year_list']), 2, 2).'/'.sprintf('%02d', $row3['month_list']).'/'.sprintf('%02d', $row3['day_list']).'/'.sprintf('%d', $row['list_documents_num']);
    }

    $competence =  preg_replace( '#^(&nbsp;|&nbsp||s|&#160;)+#is' , '' , $row['competence'] );
    $qualification =  preg_replace( '#^(&nbsp;|&nbsp||s|&#160;)+#is' , '' , $row['qualification'] );

    $course =  [ 'name'=>$course_name,  'hours'=>$row['hours'],  'date_begin'=>$row['date_begin'], 'date_end'=>$row['date_end'], 'date_protocol'=>$row['date_protocol'], 'date_protocol_rus'=>formatRussianDate($row['date_protocol']), 'protocol_num'=>$protocol_number, 'category_name'=>$category_name, 'competence'=>$competence, 'qualification'=>$qualification, 'area'=>$row['area'], 'list_documents_num'=>$list_documents_num ];


//file_put_contents("lst.txt", print_r($row['competence'], true) );
//file_put_contents("lst1.txt", print_r($row['qualification'], true) );
    $date_beginf = '1000-01-01';
    $date_endf = '1000-01-01';
    $date_protocolf = '1000-01-01';


    $date_beginf = date('d.m.Y',strtotime($row['date_begin']));
    $date_endf = date('d.m.Y',strtotime($row['date_end']));
    $date_protocolf = date('d.m.Y',strtotime($row['date_protocol']));

    if($output_format=="word") {
          $phpWord->setValue('courses-name', $course_name);
          $phpWord->setValue('courses-shortname', $row['shortname']);
          $phpWord->setValue('courses-price', $price);
          $phpWord->setValue('courses-count', $count);
          $phpWord->setValue('courses-hours', $row['hours']);
          $phpWord->setValue('courses-form_of_study', $form_of_study[ $row['form_of_study'] ]);
          $phpWord->setValue('courses-category_id'.'#'.$row_number, intval($row['category_id']));
          $phpWord->setValue('courses-category_name', $category_name);
          $phpWord->setValue('courses-type_of_education', $row['type_of_education']);
          $phpWord->setValue('courses-subtype_of_education', $row['subtype_of_education']);
          $phpWord->setValue('courses-type_of_program', $row['type_of_program']);
          $phpWord->setValue('courses-certificate_name', $row['certificate1_name']);
          $phpWord->setValue('courses-competence', $row['competence']);
          $phpWord->setValue('courses-qualification', $row['qualification']);
          $phpWord->setValue('courses-area', $row['area']);
          $phpWord->setValue('courses-list_documents_num', $list_documents_num );
          $phpWord->setValue('courses-date_begin', $date_beginf);
          $phpWord->setValue('courses-date_end', $date_endf);
          $phpWord->setValue('courses-date_protocol', $date_protocolf);
          $phpWord->setValue('courses-date_protocol_rus', formatRussianDate($row['date_protocol']));
          $phpWord->setValue('courses-protocol_num', $protocol_number);
    }
    else {
       $smarty->assign('course', $course);
    }
 }
//print_r($course);
//}
/*else {
    $directive_lstream = '';
    $course =  [ 'name'=>'Курс',  'hours'=>0,  'date_begin'=>'дд.мм.гггг', 'date_end'=>'дд.мм.гггг', 'date_protocol'=>'дд.мм.гггг', 'protocol_num'=>1 ];

    $template_id = intval($_GET['template']);
    $date_protocol = date("Y-m-d", time() );
}*/


 $teachers_commission = [];
 $teachers_commission_html1 = '<table border="0" >';
 $teachers_commission_html2 = '<table border="0"  >';
 $teachers_commission_html1_short = '<table border="0"  >';
 $teachers_commission_html2_short = '<table border="0"  >';
 $teachers_commission_html1_shortb = '<table border="0"  >';
 $teachers_commission_html2_shortb = '<table border="0"  >';
 $edition_id = 0;
 $directive_num = '№'; 


    //$stmt = $dbh->prepare("SELECT `edition_id`  FROM `a_teachers_commission_edition` LEFT JOIN  `a_teachers_commission_teacher` USING(`edition_id`)  WHERE  `a_teachers_commission_edition`.`commission_id`=? AND `edition`<?  ORDER BY `edition` DESC, `n` LIMIT 1 ");  
    $stmt = $dbh->prepare("SELECT `edition_id`, `directive_num`  FROM `a_teachers_commission_edition`  WHERE  `commission_id`=? AND `edition`<=?  ORDER BY `edition` DESC LIMIT 1 ");  
    $stmt->execute( [$commission_id, $date_protocol] );



    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
          $edition_id = intval($row->edition_id);
          $directive_num = $row->directive_num;
    }
    if($output_format=="word") {

        $phpWord->setValue('directive_num', $directive_num);

    }

     if($commission_id>0){
     $row_number = 1;
     $is_teachers_commission = 1;
     $is_teachers_commission_signs = 1;
     $row_count = 0;
     if($output_format=="word") {
          $stmt = $dbh->prepare("SELECT count(*) as `count`   FROM `a_teachers_commission_teacher`  WHERE  `commission_id`=? AND `edition_id`=?  " );
          $stmt->execute( [  $commission_id, $edition_id  ] );
          if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
              $row_count = $row->count;
          }
          try  {
              $phpWord->cloneRow('teachers_commission-title', $row_count);
          }
          catch(Throwable $s) {
             $is_teachers_commission = 0;
          }

          try  {
              $phpWord->cloneRow('teachers_commission_signs-title', $row_count);
          }
          catch(Throwable $s) {
             $is_teachers_commission_signs = 0;
          }
     }



    $stmt = $dbh->prepare("SELECT  `teacher_id`, `n`, `fullname`, `job_title`, `sign` FROM  `a_teachers_commission_teacher`  WHERE  `edition_id`=?  ORDER BY  `n`");  
    $stmt->execute( [ $edition_id  ] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        if($print_v == 0)
                 $img_sign = '<div style="position: relative; top: -40px; left: 0px;"><div style="position: absolute; top: 1px; left: 1px; "><img width="100px" src="'.$JsonApiURL.'documents/'.$row->sign.'"></div></div>';
        else
                 $img_sign = '';

        if($row->n==0){
             $teachers_commission_html1 = $teachers_commission_html1 . '<tr><td valign="top">Председатель комиссии: </td><td class="left">' . $row->fullname .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td valign="top">Председатель комиссии: </td><td>'. $img_sign. ' _________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div> </td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html1_short = $teachers_commission_html1_short . '<tr><td valign="top">Председатель комиссии: </td><td class="left">' . formatFio($row->fullname) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td valign="top">Председатель комиссии: </td><td>'. $img_sign. ' _________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div> </td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
             $teachers_commission_html1_shortb = $teachers_commission_html1_shortb . '<tr><td valign="top">Председатель комиссии: </td><td class="left">' . formatFio($row->fullname,true) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_shortb = $teachers_commission_html2_shortb . '<tr><td valign="top">Председатель комиссии: </td><td>'. $img_sign. ' _________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div></td><td valign="top" class="left">/' . formatFio($row->fullname,true) .  '/</td></tr>';

        }
        else if($row->n==1){
             $teachers_commission_html1 = $teachers_commission_html1 . '<tr><td valign="top">Члены комиссии: </td><td class="left">' . $row->fullname .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td valign="top">Члены комиссии: </td><td>'. $img_sign. '_________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div></td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html1_short = $teachers_commission_html1_short . '<tr><td valign="top">Члены комиссии: </td><td class="left">' . formatFio($row->fullname) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td valign="top">Члены комиссии: </td><td>'. $img_sign. '_________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div></td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
             $teachers_commission_html1_shortb = $teachers_commission_html1_shortb . '<tr><td valign="top">Члены комиссии: </td><td class="left">' . formatFio($row->fullname,true) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_shortb = $teachers_commission_html2_shortb . '<tr><td valign="top">Члены комиссии: </td><td>'. $img_sign. '_________________ <br /> <div style="font-size: 8px; padding-left: 30px;">(подпись)</div></td><td valign="top" class="left">/' . formatFio($row->fullname,true) .  '/</td></tr>';

        }
        else {
         if($row->job_title=="Секретарь"||$row->job_title=="Секретарь учебной части"&& $IS_SECR == 1){
             $teachers_commission_html1 = $teachers_commission_html1 . '<tr><td valign="top">Секретарь учебной части:</td><td class="left">' . $row->fullname .  '</td><td> </td><td class="left"> </td></tr>';
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td valign="top">Секретарь:</td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html1_short = $teachers_commission_html1_short . '<tr><td valign="top">Секретарь учебной части:</td><td class="left">' . formatFio($row->fullname) .  '</td><td> </td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td valign="top">Секретарь:</td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
             $teachers_commission_html1_shortb = $teachers_commission_html1_shortb . '<tr><td valign="top"></td>Секретарь учебной части:<td class="left">' . formatFio($row->fullname,true) .  '</td><td> </td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_shortb = $teachers_commission_html2_shortb . '<tr><td valign="top">Секретарь:</td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . formatFio($row->fullname,true) .  '/</td></tr>';
             } 
             else{
             $teachers_commission_html1 = $teachers_commission_html1 . '<tr><td valign="top"></td><td class="left">' . $row->fullname .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2 = $teachers_commission_html2 . '<tr><td valign="top">  </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . $row->fullname .  '/</td></tr>';
             $teachers_commission_html1_short = $teachers_commission_html1_short . '<tr><td valign="top"></td><td class="left">' . formatFio($row->fullname) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_short = $teachers_commission_html2_short . '<tr><td valign="top">  </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . formatFio($row->fullname) .  '/</td></tr>';
             $teachers_commission_html1_shortb = $teachers_commission_html1_shortb . '<tr><td valign="top"></td><td class="left">' . formatFio($row->fullname,true) .  '</td><td> –</td><td class="left">' .$row->job_title .'</td></tr>';
             $teachers_commission_html2_shortb = $teachers_commission_html2_shortb . '<tr><td valign="top">  </td><td>'. $img_sign. '_________________ <br /> <span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td><td valign="top" class="left">/' . formatFio($row->fullname,true) .  '/</td></tr>';
             }
        }

        if($output_format=="word"  && $is_teachers_commission) {
                   if($row->n==0){
                        $phpWord->setValue('teachers_commission-title'.'#'.$row_number, 'Председатель комиссии:' );
                   }
                   else if($row->n==1) {
                        $phpWord->setValue('teachers_commission-title'.'#'.$row_number, 'Члены комиссии:' );
                   }
                   else {

                        if($row->job_title=="Секретарь"||$row->job_title=="Секретарь учебной части"&& $IS_SECR == 1){
                            $phpWord->setValue('teachers_commission-title'.'#'.$row_number, 'Секретарь учебной части' );
                        }
                        else{
                            $phpWord->setValue('teachers_commission-title'.'#'.$row_number, '' );
                        }

                        
                   }
                   $phpWord->setValue('teachers_commission-fullname'.'#'.$row_number, $row->fullname );
                   $phpWord->setValue('teachers_commission-fio'.'#'.$row_number, formatFio($row->fullname) );
                   if($row->job_title=="Секретарь"||$row->job_title=="Секретарь учебной части"&& $IS_SECR == 1){
                            $phpWord->setValue('teachers_commission-job_title'.'#'.$row_number, '' );
                    }
                    else{
                            $phpWord->setValue('teachers_commission-job_title'.'#'.$row_number, $row->job_title );
                    }
                   
                   $phpWord->setValue('teachers_commission-number'.'#'.$row_number, $row_number );



         }


            $imageSignStyle = [
                'path' => '../documents/'.$row->sign,
                'width' => 50,
                'height' => 50,
                'wrap' => 'inFront' // Основной параметр: square, tight, topAndBottom, behind, inFront
            ];


        if($output_format=="word"  && $is_teachers_commission_signs) {
                   if($row->n==0){
                        $phpWord->setValue('teachers_commission_signs-title'.'#'.$row_number, 'Председатель комиссии:' );
                   }
                   else if($row->n==1) {
                        $phpWord->setValue('teachers_commission_signs-title'.'#'.$row_number, 'Члены комиссии:' );
                   }
                   else {

                        if($row->job_title=="Секретарь"||$row->job_title=="Секретарь учебной части"&& $IS_SECR == 1){
                            $phpWord->setValue('teachers_commission_signs-title'.'#'.$row_number, 'Секретарь' );
                        }
                        else{
                            $phpWord->setValue('teachers_commission_signs-title'.'#'.$row_number, '' );
                        }
                        
                   }
                   $phpWord->setValue('teachers_commission_signs-fullname'.'#'.$row_number, $row->fullname );
                   $phpWord->setValue('teachers_commission_signs-fio'.'#'.$row_number, formatFio($row->fullname) );
                   $phpWord->setValue('teachers_commission_signs-job_title'.'#'.$row_number, $row->job_title );
                   $phpWord->setValue('teachers_commission_signs-number'.'#'.$row_number, $row_number );
                   
                   
                   if(isset($_GET['print_v']) && $_GET['print_v']=='false' ) {
                   $phpWord->setImageValue('teachers_commission_signs-sign'.'#'.$row_number, $imageSignStyle);
                   }
                   else{
                    $phpWord->setValue('teachers_commission_signs-sign'.'#'.$row_number, ''); 
                   }
                   
                   
         }
         $row_number = $row_number +1;

    }
 }
 $teachers_commission_html1 = $teachers_commission_html1 . '</table>';
 $teachers_commission_html2 = $teachers_commission_html2 . '</table>';
 $teachers_commission_html1_short = $teachers_commission_html1_short . '</table>';
 $teachers_commission_html2_short = $teachers_commission_html2_short . '</table>';
 $teachers_commission_html1_shortb = $teachers_commission_html1_shortb . '</table>';
 $teachers_commission_html2_shortb = $teachers_commission_html2_shortb . '</table>';
 if($directive_lstream != '')
       $directive_num = $directive_lstream;

 $teachers_commission =  [ 'html1'=>$teachers_commission_html1, 'html2'=>$teachers_commission_html2,  'html1_short'=>$teachers_commission_html1_short, 'html1_shortb'=>$teachers_commission_html1_shortb, 'html2_short'=>$teachers_commission_html2_short, 'html2_shortb'=>$teachers_commission_html2_shortb, 'directive_num'=>$directive_num  ];
 //$teachers_commission =  [ $teachers_commission_html1, $teachers_commission_html2 ];

 if($output_format=="") {
    $smarty->assign('teachers_commission', $teachers_commission);
 }
 //print_r($teachers_commission);




/*
* template_file_php
*/
    //$p_temlate = $row['protocol_temlate'];
    if($protocol_template['add_header']!='') {
        $add_header = explode(';', $protocol_template['add_header']);
        for ($i = 0; $i < count($add_header); $i++) {
                $add_header[$i] = '<div style="font-size: 8pt;">'.$add_header[$i].'</div>' ;
        }
        $add_default  = explode(';', $protocol_template['add_default']);
        for ($i = 0; $i < count($add_default); $i++) {
              if($add_default[$i]=='')
                    $add_default[$i] =  '&#x2009;' ;
        }
    }
    //$protocol = [ 'protocol_p1'=>$row2['protocol_p1'], 'protocol_p2'=>$row2['protocol_p2'], 'protocol_p3'=>$row2['protocol_p3'], 'is_position'=>$row2['is_position'], 'is_organization'=>$row2['is_organization'], 'is_certificate'=>$row2['is_certificate'], 'certificate_header'=>$row2['certificate_header']  ];

    $user_row = [];
    $user_row[] =  '<div style="font-size: 8pt;">Фамилия, имя, отчество</div>';
    //$user_row[] =  'Фамилия';
    //$user_row[] =  'Имя';
    //$user_row[] =  'Отчество';
    if($protocol_template['is_position']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Должность</div>';
    
    if($protocol_template['is_organization']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Наименование организации</div>';
    
    if($protocol_template['is_program']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Программа обучения</div>';



    if($protocol_template['is_finalexamination']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Итоговая аттестация</div>';
        
    if($protocol_template['is_certificate']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">'.$protocol_template['certificate_header'].'</div>' ;

    if($protocol_template['is_dateprotocol']=='true')
        $user_row[] =  '<div style="font-size: 8pt;">Дата, номер протокола аттестационной комиссии</div>';        
    
    if($is_rank_of_profession =='true')
        $user_row[] =  '<div style="font-size: 8pt;">Квалификационный разряд</div>' ;

    if($protocol_template['is_eisot']=='true')
        $user_row[] = '<div style="font-size: 8pt;">Регистрационный <br />номер записи в реестре <br />обученных по охране <br />труда лиц</div>' ;

    if($protocol_template['is_blank_number']=='true')
        $user_row[] = '<div style="font-size: 8pt;">'.$protocol_template['header_blank_number'].'</div>' ;


    $users_header = array_merge($user_row,  $add_header); 
    
/*
    $protocol['protocol_h']   =  $row['protocol_h'];
    $protocol['directive']    =  $row['directive_num'];
    $protocol['protocol_p1']  =  $row['protocol_p1'];
    $protocol['protocol_p2']  =  $row['protocol_p2'];
    $protocol['protocol_p3']  =  $row['protocol_p3'];
    $protocol['is_hours_p']   =  $row['is_hours_p']; 

}*/



 $smarty->assign('users_header', $users_header );
 //$smarty->assign('protocol', $protocol );
 $smarty->assign('protocol', $protocol_template );




 if( $order_id >0 ) {
    $stmt = $dbh->prepare("SELECT *, DATE_FORMAT(`date_order`, '%d.%m.%Y') as `date_order_ru`, `invoice`   FROM `a_order`    LEFT JOIN `a_counterparty` USING(`counterparty_id`) WHERE `order_id`=?");
    $stmt->execute( [$order_id ] );
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($row['invoice']=='')
             $invoice = 'CED-' . $row['order_id'];
        else
             $invoice = $row['invoice'];

        $data =  [ 'order_num'=>$row['order_id'], 'name'=>$row['name'], 'shortname'=>$row['shortname'], 'counterparty_id'=>$row['counterparty_id'],   'inn'=>$row['inn'], 'kpp'=>$row['kpp'], 'ogrn'=>$row['ogrn'],  'addres1'=>$row['addres1'],  'addres2'=>$row['addres2'], 'phone'=>$row['phone'],
      'position_head'=>$row['position_head'], 'enterprise_manager'=>$row['enterprise_manager'], 'position_head2'=>$row['position_head2'], 'enterprise_manager2'=>$row['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3, 
      'bank'=>$row['bank'], 'checking_account'=>$row['checking_account'], 'correspondent_account'=>$row['correspondent_account'], 'bik'=>$row['bik'], 
      'date_order'=>$order_date_order, 'number'=>$row['number'], 'date_begin'=>$order_date_begin, 'date_end'=>$order_date_end,  'date_completed'=>$date_completed,  
      'price_sum'=>$price_sum,  'price_sum_str'=>$price_sum_str, 'contract_number'=>$contract_number, 'date_order_finish'=>$date_order_finish, 'longtime_contract'=>$row['longtime_contract'], 'payer_id'=>$row['payer_id'], 'invoice'=>$invoice ];
    }

    $date_orderf = '1000-01-01';
    $date_beginf = '1000-01-01';
    $date_endf = '1000-01-01';
    $date_completedf = '1000-01-01';
    $date_order_finishf = '1000-01-01';

    $date_orderf = date('d.m.Y',strtotime($row['date_order_ru']));
    $date_beginf = date('d.m.Y',strtotime($order_date_begin));
    $date_endf = date('d.m.Y',strtotime($order_date_end));
    $date_completedf = date('d.m.Y',strtotime($date_completed));
    $date_order_finishf = date('d.m.Y',strtotime($date_order_finish));

    if($output_format=="word") {
      $phpWord->setValue('order_num', $row['order_id']);
      $phpWord->setValue('name', $row['name']);
      $phpWord->setValue('shortname', $row['shortname']);
      $phpWord->setValue('counterparty_id', $row['counterparty_id']);
      $phpWord->setValue('inn', $row['inn']);
      $phpWord->setValue('kpp', $row['kpp']);
      $phpWord->setValue('ogrn', $row['ogrn']);
      $phpWord->setValue('addres1', $row['addres1']);
      $phpWord->setValue('addres2', $row['addres2']);
      $phpWord->setValue('phone', $row['phone']);
      $phpWord->setValue('position_head', $row['position_head']);
      $phpWord->setValue('position_head2', $row['position_head2']);
      $phpWord->setValue('enterprise_manager', $enterprise_manager1);
      $phpWord->setValue('enterprise_manager2', $enterprise_manager2);
      $phpWord->setValue('enterprise_manager3', $enterprise_manager3); 
      $phpWord->setValue('bank'.'#'.$block_number, $row['bank']);
      $phpWord->setValue('checking_account', $row['checking_account']);
      $phpWord->setValue('correspondent_account', $row['correspondent_account']);
      $phpWord->setValue('bik', $row['bik']);
      $phpWord->setValue('date_order', $date_orderf);
      $phpWord->setValue('number', $row['number']);
      $phpWord->setValue('date_begin', $date_beginf);
      $phpWord->setValue('date_end', $date_endf);
      $phpWord->setValue('date_completed',  $date_completedf);
      $phpWord->setValue('price_sum',  $price_sum);
      $phpWord->setValue('price_sum_str', $price_sum_str);
      $phpWord->setValue('price_nds', $price_nds);
      $phpWord->setValue('price_nds_str', $price_nds_str);
      $phpWord->setValue('price_sum_nds',  $price_sum_nds);
      $phpWord->setValue('price_sum_nds_str',  $price_sum_nds_str);
      $phpWord->setValue('contract_number', $contract_number);
      $phpWord->setValue('date_order_finish', $date_order_finishf);
      $phpWord->setValue('longtime_contract', $row['longtime_contract']);
      $phpWord->setValue('payer_id', $row['payer_id']);
      $phpWord->setValue('invoice', $invoice );
    }
 }
 else {
    $invoice = 'CED-' . $order_id;
    /*    $data =  [ 'order_num'=>0, 'name'=>$row2['name'], 'shortname'=>$row2['shortname'], 'counterparty_id'=>$row2['counterparty_id'],   'inn'=>$row2['inn'], 'kpp'=>$row2['kpp'], 'ogrn'=>$row2['ogrn'],  'addres1'=>$row2['addres1'],  'addres2'=>$row2['addres2'], 'phone'=>$row2['phone'],
      'position_head'=>$row2['position_head'], 'enterprise_manager'=>$row2['enterprise_manager'], 'position_head2'=>$row2['position_head2'], 'enterprise_manager2'=>$row2['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3, 
      'bank'=>$row2['bank'], 'checking_account'=>$row2['checking_account'], 'correspondent_account'=>$row2['correspondent_account'], 'bik'=>$row2['bik'],
      'date_order'=>$date_order, 'number'=>'', 'date_begin'=>'', 'date_end'=>'', 'date_protocol'=>'', 'date_completed'=>'',
      'price_sum'=>$price_sum,  'price_sum_str'=>$price_sum_str, 'contract_number'=>$contract_number, 'date_order_finish'=>$date_order_finish, 'longtime_contract'=>$row2['longtime_contract'], 'payer_id'=>$row2['payer_id'], 'invoice'=>$invoice  ];*/

    if($output_format=="word") {
      $phpWord->setValue('invoice', $invoice );
    }

     $data =  [  'invoice'=>$invoice  ];
 }
 if($output_format=="") {
        $smarty->assign('order_data', $data);
 }
//print_r($data);







 $is_students = 1;
 $row_count = 0;
 if($output_format=="word") {
      $stmt = $dbh->prepare("SELECT count(*) as `count`   FROM `a_order_course` LEFT JOIN `a_order_users`  USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)  LEFT JOIN `a_counterparty` USING(counterparty_id)    WHERE `a_order_course`.`cohort_id`=?  AND `a_order_users`.`user_lock`=0 AND `a_user_counterparty`.`user_id`>1 " );
      $stmt->execute( [ $groupid ] );
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



 $users_data=[];
 //$order_id = 0;
 $rank_of_profession = 0;
 $blank_number = '';
 $row_number = 1;
 $rank_of_profession_name =  ['','','2 (второй) разряд','3 (третий) разряд','4 (четвертый) разряд','5 (пятый) разряд','6 (шестой) разряд','7 (седьмой) разряд','8 (восьмой) разряд'];
 //$stmt2 = $dbh->prepare("SELECT `a_order_users`.`user_id`, `lastname`,`firstname`,`middlename`,`date_of_birth`,`address`,`subdivision`,`a_job_title`.`name` as `job_title`, `a_counterparty`.`name` as 'counterparty', `a_counterparty`.`shortname` as 'counterparty_shortname', `a_counterparty`.`counterparty_id`,  `a_order_users`.`certificate_num`, `order_id`, `user_id`, `course_id`, `blank_number`   FROM `a_order_course` LEFT JOIN `a_order_users`  USING(`item_id`)   LEFT JOIN `a_users` USING(`user_id`) LEFT JOIN `a_job_title` USING(`job_title_id`)  LEFT JOIN `a_counterparty` USING(counterparty_id)    WHERE `a_order_course`.`cohort_id`=?  AND `a_order_users`.`user_lock`=0 AND `a_order_users`.`user_id`>1  ORDER BY `certificate_num` ");
 $stmt2 = $dbh->prepare("SELECT `a_user_counterparty`.`user_id`, `lastname`,`firstname`,`middlename`,`date_of_birth`,`address`,`subdivision`,`a_job_title`.`name` as `job_title`, `a_counterparty`.`name` as 'counterparty', `a_counterparty`.`shortname` as 'counterparty_shortname', `a_counterparty`.`counterparty_id`,  `a_order_users`.`certificate_num`, `order_id`, `course_id`, `blank_number`, `custom_cert_number`   FROM `a_order_course` LEFT JOIN `a_order_users`  USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)  LEFT JOIN `a_counterparty` USING(counterparty_id)    WHERE `a_order_course`.`cohort_id`=?  AND `a_order_users`.`user_lock`=0 AND `a_user_counterparty`.`user_id`>1  ORDER BY `certificate_num` ");
 $stmt2->execute( [ $groupid ] );
 while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    //$order_id = $row2['order_id'];
    $eisot_cert_number = '';
    if($is_blank>0)
            $blank_number =  $row2['blank_number'];

    $stmt3 = $dbh->prepare("SELECT  `cert_number`  FROM `a_lstream_eisot`  WHERE `user_id`=?  AND  `lstream_id`=? ");
    $stmt3->execute( [ $row2['user_id'], $lstream_id  ] );
    if($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
          $eisot_cert_number = $row3['cert_number'];
    }
    if($row2['custom_cert_number'] != '' )
        $cert_num = $row2['custom_cert_number'];
    else
        $cert_num = $protocol_template['certificate_grade'].'№ '.$protocol_num.'-'.sprintf('%02d', $row2['certificate_num']);

    $users_data[] = ['lastname'=>$row2['lastname'], 'firstname'=>$row2['firstname'], 'middlename'=>$row2['middlename'], 'subdivision'=>$row2['subdivision'], 'job_title'=>$row2['job_title'], 'date_of_birth'=>$row2['date_of_birth'],    'counterparty'=>$row2['counterparty'], 'counterparty_shortname'=>$row2['counterparty_shortname'], 'address'=>$row2['address'], 'eisot_cert_number'=>$eisot_cert_number, 'certificate_num'=>$row2['certificate_num'], 'cert_num'=>$cert_num,  'blank_number'=>$blank_number  ];
    

    $user_row = [];
    //$user_row[] =  $row2['lastname'];
    //$user_row[] =  $row2['firstname'];
    //$user_row[] =  $row2['middlename'];
    $user_row[] =  '<div style="text-align: left;">'.$row2['lastname'] .' '. $row2['firstname'] .' '. $row2['middlename'].'</div>';

    if($protocol_template['is_position']=='true')
        $user_row[] =  $row2['job_title'];
    

    if($protocol_template['is_organization']=='true') {
        //$user_row[] =  $row2['counterparty'];
      //if( $row2['counterparty_id']>1 )
        $user_row[] =  $row2['counterparty_shortname'];
      //else
        //$user_row[] =  '';
    }    
        
    if($protocol_template['is_program']=='true')
    $user_row[] =  $course['name'];


    if($protocol_template['is_finalexamination']=='true')
        $user_row[] =  $protocol_template['finalexamination'];
        //$user_row[] = $course_shortname . '<br />Сдано';

    if($protocol_template['is_certificate']=='true')
        $user_row[] =   $cert_num ;

    if($protocol_template['is_dateprotocol']=='true')
        $user_row[] =  date("d.m.Y", strtotime($date_protocol)).'<br /> № '.$protocol_num;
        
    if($is_rank_of_profession =='true') {
        $rank_of_profession = 0;
        $stmt3 = $dbh->prepare("SELECT  `rank_of_profession`  FROM `a_order_users`  WHERE `order_id`=? AND `user_id`=? AND `course_id`=? AND  `rank_of_profession`>0  AND `user_lock`=0 ");
        $stmt3->execute( [ $row2['order_id'], $row2['user_id'], $row2['course_id'] ] );
        if($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {  
             $rank_of_profession =  $row3['rank_of_profession'];
        }
        $user_row[] =   $rank_of_profession_name[$rank_of_profession];
    }

    if($protocol_template['is_eisot']=='true')
        $user_row[] = $eisot_cert_number ;

    if($protocol_template['is_blank_number']=='true')
        $user_row[] = $blank_number ;


    $users[] = array_merge($user_row, $add_default); 

    $date_of_birthf = '1000-01-01';

    $date_of_birthf = date('d.m.Y',strtotime($row2['date_of_birth']));

    if($output_format=="word" && $is_students) {
          $phpWord->setValue('students-number'.'#'.$row_number, $row_number);
          $phpWord->setValue('students-lastname'.'#'.$row_number, $row2['lastname']);
          $phpWord->setValue('students-firstname'.'#'.$row_number, $row2['firstname']);
          $phpWord->setValue('students-middlename'.'#'.$row_number, $row2['middlename']);
          $phpWord->setValue('students-job_title'.'#'.$row_number, $row2['job_title']);
          $phpWord->setValue('students-date_of_birth'.'#'.$row_number, $date_of_birthf);
          $phpWord->setValue('students-user_age'.'#'.$row_number, $user_age);
          $phpWord->setValue('students-snils'.'#'.$row_number, $row2['snils']);
          $phpWord->setValue('students-address'.'#'.$row_number, $row2['address']);
          $phpWord->setValue('students-pasport_series'.'#'.$row_number, $row2['pasport_series']);
          $phpWord->setValue('students-pasport_number'.'#'.$row_number, $row2['pasport_number']);
          $phpWord->setValue('students-pasport_unit'.'#'.$row_number, $row2['pasport_unit']);
          $phpWord->setValue('students-pasport_unit_number'.'#'.$row_number, $row2['pasport_unit_number']);
          $phpWord->setValue('students-email'.'#'.$row_number, $row2['email']);
          $phpWord->setValue('students-phone'.'#'.$row_number, $row2['phone']);
          $phpWord->setValue('students-fullname3'.'#'.$row_number, formatFio($row2['lastname'].' '.$row2['firstname'].' '.$row2['middlename']) );
          $phpWord->setValue('students-certificate_num'.'#'.$row_number, $cert_num  );
          $phpWord->setValue('students-blank_num'.'#'.$row_number, $blank_number  );
          $phpWord->setValue('students-counterparty_name'.'#'.$row_number, $row2['counterparty'] );
          $phpWord->setValue('students-counterparty_shortname'.'#'.$row_number, $row2['counterparty_shortname'] );
     }
     $row_number = $row_number +1;
  }
  if($output_format=="") {
       $smarty->assign('users', $users );
       $smarty->assign('users_data', $users_data );
  }

//print_r($users);
//print_r($users_data);


/*else {
    $users_header= [];
    $user_row[] =  '<div style="font-size: 8pt;">Фамилия, имя, отчество</div>';
    $users[] = $user_row;
    $smarty->assign('users', $users );
    $smarty->assign('users_header', $users_header );

    $user= [];
    $user_row[] =  '<div style="text-align: left;"> Фамилия Имя Отчество </div>';
    $users[] = $user_row;
    $smarty->assign('users', $users );
}*/



 $stmt2 = $dbh->prepare("SELECT *  FROM `a_self`  WHERE `edition`<=?  ORDER BY `edition` DESC  LIMIT 1  ");
 $stmt2->execute( [$date_protocol] );
 if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {  

    $enterprise_manager_a = explode(" ", $row2['enterprise_manager']);
    $enterprise_manager3 = $enterprise_manager_a[0]. ' ' .mb_substr($enterprise_manager_a[1], 0, 1). '.' .mb_substr($enterprise_manager_a[2], 0, 1). '.';

    $self_data =  [  'name'=>$row2['name'], 'shortname'=>$row2['shortname'],    'inn'=>$row2['inn'], 'kpp'=>$row2['kpp'], 'ogrn'=>$row2['ogrn'], 'addres1'=>$row2['addres1'],  'addres2'=>$row2['addres2'], 'phone'=>$row2['phone'],
      'position_head'=>$row2['position_head'], 'enterprise_manager'=>$row2['enterprise_manager'], 'position_head2'=>$row2['position_head2'], 'enterprise_manager2'=>$row2['enterprise_manager2'], 'enterprise_manager3'=>$enterprise_manager3,  'enterprise_manager_signs'=>$row2['enterprise_manager_signs'], 
      'bank'=>$row2['bank'], 'checking_account'=>$row2['checking_account'], 'correspondent_account'=>$row2['correspondent_account'], 'bik'=>$row2['bik'], 'license'=>$row2['license'], 'accreditation'=>$row2['accreditation'], 'city'=>$row2['city'] ];

    if($output_format=="word") {
      $phpWord->setValue('self-name', $row2['name']);
      $phpWord->setValue('self-shortname', $row2['shortname']);
      $phpWord->setValue('self-inn', $row2['inn']);
      $phpWord->setValue('self-kpp', $row2['kpp']);
      $phpWord->setValue('self-ogrn', $row2['ogrn']);
      $phpWord->setValue('self-addres1', $row2['addres1']);
      $phpWord->setValue('self-addres2', $row2['addres2']);
      $phpWord->setValue('self-city', $row2['city']);
      $phpWord->setValue('self-email', $row2['email']);
      $phpWord->setValue('self-phone', $row2['phone']);
      $phpWord->setValue('self-position_head', $row2['position_head']);
      $phpWord->setValue('self-enterprise_manager', $row2['enterprise_manager']);
      $phpWord->setValue('self-position_head2', $row2['position_head2']);
      $phpWord->setValue('self-enterprise_manager2', $row2['enterprise_manager2']);
      $phpWord->setValue('self-enterprise_manager3', $enterprise_manager3);
      $phpWord->setValue('self-enterprise_manager_signs', $row2['enterprise_manager_signs']);
      $phpWord->setValue('self-bank', $row2['bank']);
      $phpWord->setValue('self-checking_account', $row2['checking_account']);
      $phpWord->setValue('self-correspondent_account', $row2['correspondent_account']);
      $phpWord->setValue('self-bik', $row2['bik']);
      $phpWord->setValue('self-license', $row2['license']);
      $phpWord->setValue('self-accreditation', $row2['accreditation']);

            $imageStampStyle = [
                'path' => '../documents/signs/Stamp.png',
                'width' => 200,
                'height' => 200,
                'positioning'   => 'absolute',
                'wrap' => 'inFront' // Основной параметр: square, tight, topAndBottom, behind, inFront
                
            ];


        if(isset($_GET['print_v']) && $_GET['print_v']=='false') {
              $phpWord->setImageValue('self-stamp', $imageStampStyle);
      }
      else {
                $phpWord->setValue('self-stamp', '');
      }


    }
    else {
       $smarty->assign('self_data', $self_data);
    }
}
//print_r($self_data);


if(isset($_GET['print_v']))
    $smarty->assign('print_v', $_GET['print_v']);
else
    $smarty->assign('print_v', 'false');


//print_r($template_file);
if($output_format=="" && $template_file!='')
      $html_s = $smarty->fetch('../documents/'.$template_file);
else
      $html_s = '';

if($output_format=="" &&  $template_footer_file != '')
      $footer_html_s = $smarty->fetch('../documents/'.$template_footer_file);
else
      $footer_html_s = '';


return $html_s . $footer_html_s;
}







$html_str = "";
//$order_id = 0; 
if($groupid > 0){
    $stmt0 = $dbh->prepare("SELECT DISTINCT   `order_id`  FROM `a_order_course`  LEFT JOIN  `a_order_users` USING(`item_id`)     WHERE `cohort_id`=? ");
    $stmt0->execute( [ $groupid ] );
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {
      $order_id = $row0->order_id;
      $html_str = report_protocol($groupid, $order_id );
      $report_file = 'Protocol'.$groupid;
    }
}
else if( intval($_GET['id'])>0 ){
    $order_id = intval($_GET['id']);
    $j = 0;
    //$stmt0 = $dbh->prepare("SELECT DISTINCT   `a_order_course`.`cohort_id`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`  FROM `a_order_course`  LEFT JOIN  `a_order_users` USING(`item_id`) LEFT JOIN  `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`)    WHERE `order_id`=? ORDER BY  `cohort_id` ");
    $stmt0 = $dbh->prepare("SELECT DISTINCT   `a_order_course`.`cohort_id`  FROM `a_order_course`  LEFT JOIN  `a_order_users` USING(`item_id`)     WHERE `order_id`=? ORDER BY  `cohort_id` ");
    $stmt0->execute( [ intval($_GET['id']) ] );
    while($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {
      if($j>0){
          if($output_format=='mpdf')
              $html_str = $html_str . '<pagebreak  sheet-size="'. $page_size .'"  />';
          else
              $html_str = $html_str . '<div style="page-break-before: always;"></div>';
      }
      $html_str = $html_str . report_protocol($row0->cohort_id, $order_id ) ;
      $j++;
    }
    $report_file = 'Protocol_order'.$order_id;
}

//echo $html_str;
//echo $footer_html_str;



/*if($_GET['print_v'] && $_GET['print_v']=='word')
{
/*
$phpWord = new PhpWord();
$section = $phpWord->addSection();
Html::addHtml($section, $html_str, true);
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml');
//header('Content-Type: application/vnd.oasis.opendocument.text');
header('Content-Disposition: attachment; filename="'.rus2translit($report_file).'.docx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter->save('php://output');
//echo $html_str;
}*/

if($_GET['print_v'] && $_GET['print_v']=='edit' && $output_format=="")
{
header('Cache-Control: max-age=0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$header = file_get_contents('documents-editor_protocol.header.html');
$header = str_replace( "{Id}", $groupid, $header); 
$bottom = file_get_contents('documents-editor.bottom.html');
echo $header;
echo $html_str;
echo $bottom;
}
else if($_GET['print_v'] && $_GET['print_v']!='edit' && $output_format=="word" && $_GET['print_switch']!='false' && 0 )
{
header('Cache-Control: max-age=0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
header('Content-Disposition: attachment; filename="'.rus2translit($report_filename).'.pdf"');

PhpOffice\PhpWord\Settings::setPdfRendererPath('vendor/Dompdf/Dompdf');
PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

$location = '../tmp/' . $sessionid .'-cert.docx';
$phpWord->saveAs($location);
$phpWord_raw = IOFactory::load($location);
$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord_raw , 'PDF');
$xmlWriter->save('php://output');  
}

else if($output_format=="word") {
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//header('Content-Type: application/vnd.oasis.opendocument.text');
header('Content-Disposition: attachment; filename="'.rus2translit($report_file).'.docx"');
header('Cache-Control: max-age=0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$phpWord->saveAs('php://output');
}
else {
	$dompdf = new Dompdf();
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

	//$html_str = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style> body { font-family: DejaVu Sans; font-size:12px; }</style><body> hello world по русски </body></html>';

	$dompdf->loadHtml($html_str . $footer_html_str , 'UTF-8');
	$dompdf->render();
	//$dompdf->stream();
        $dompdf->stream(rus2translit($report_file) . ".pdf", array("Attachment" => 0));
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
$header = str_replace( "{Id}", $groupid, $header); 
$bottom = file_get_contents('documents-layout.bottom.html');
echo $header;
echo $html_str;
echo $bottom;
}*/



?>