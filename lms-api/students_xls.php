<?php
/**
 * @copyright 2022
 */


require_once('../PhpSpreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();

$csv_file = 0;
$group_id = intval($_GET["lstreamId"]);
//$group = "Группа_$group_id";
if($_GET["format"] == 'csv')
      $csv_file = 1;



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

if(!$csv_file){
  $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A2', '')
    ->setCellValue('B2', 'Фамилия')
    ->setCellValue('C2', 'Имя')
    ->setCellValue('D2', 'Отчество')
    ->setCellValue('E2', "Организация")
    ->setCellValue('F2', 'Должность')
    ->setCellValue('G2', "Логин")
    ->setCellValue('H2', "Пароль");


  $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', '')
    ->setCellValue('B1', 'Группа: ')
    ->setCellValue('C1', $group);


  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('A')->setWidth(5);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('B')->setWidth(20);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('C')->setWidth(20);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('D')->setWidth(20);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('E')->setWidth(20);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('F')->setWidth(50);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('G')->setWidth(20);
  $spreadsheet->setActiveSheetIndex(0)
    ->getColumnDimension('H')->setWidth(20);
}
else {
	echo  "lastname;firstname;institution;username;email;password;cohort1\n";
}

//lastname	firstname	email	username	password	idnumber 	address	institution	department	cohort1
$group_name = '';
$group_date_end = 2147483647;
if($group_id>0) {
    $stmt0 = $dbh->prepare('SELECT   `name`,   `moodle_cohort_id`, `moodle_group_id`, `moodle_enrol_id`, UNIX_TIMESTAMP(`date_begin`), UNIX_TIMESTAMP(`date_end`) as `date_end`  FROM `a_lstream`   WHERE `lstream_id`=?');
    $stmt0->execute([$group_id]);
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
        $group_name = $row0->name;
        $moodle_cohort_id = $row0->moodle_cohort_id;
        $moodle_group_id = $row0->moodle_group_id;
        $moodle_enrol_id = $row0->moodle_enrol_id;
        if(intval($row0->date_end)>0 ) 
              $group_date_end = intval($row0->date_end);
    }
    $stmt0 = $dbh->prepare('SELECT  `course_id`  FROM `a_cohort`   WHERE `lstream_id`=?');
    $stmt0->execute([$group_id]);
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
        $course_id = $row0->course_id;
    }



/*	    $courses = '';
	    $stmt2 = $dbh->prepare('SELECT  `a_cache_course`.`shortname`  FROM `a_groups_users`  LEFT JOIN `a_groups` USING(`group_id`) LEFT JOIN `a_cache_course` USING(`course_id`)  WHERE `a_groups`.`date`=? AND `a_groups_users`.`user_id`=?');
	    $stmt2->execute([$date_list, $row->user_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$courses = $courses . $row2->shortname . ', ';
            }
	    $courses = mb_substr($courses, 0, -2);
*/


    $i = 1;
    $stmt = $dbh->prepare('SELECT DISTINCT  `order_id`, `a_users`.`user_id`, `lastname`, `firstname`, `middlename`, `user_counterparty_id`,  `a_user_counterparty`.`counterparty_id`,  `job_title_id`, `job_title_category`, `subdivision`      FROM  `a_cohort` LEFT JOIN `a_order_course` USING(`cohort_id`)  LEFT JOIN   `a_order_users` USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING (`user_counterparty_id`)   LEFT JOIN `a_users`  USING (`user_id`)  WHERE  `lstream_id`=?   ORDER BY `lastname`');
    $stmt->execute([$group_id]);
    $n =0;
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $login = 'a';
        $email_lms = '';
        $password = '';
        $stmt2 = $dbh->prepare('SELECT  `login`, `email`,  `password`  FROM  `a_users_passwd`  WHERE `order_id`=? AND `user_id`=?  ');
        $stmt2->execute([$row->order_id,  $row->user_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
              $login = $row2->login;
              $email_lms = $row2->email;
              $password = $row2->password;
        }

        $counterparty_name = '';
        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_counterparty`  WHERE `counterparty_id`=? ');
        $stmt2->execute([$row->counterparty_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $counterparty_name = $row2->name;
            //counterparty_name = str_replace("'", '&#39;',  str_replace('"', '&quot;', $row2->name));
        }
        $job_title_name = '';
        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_job_title`  WHERE `job_title_id`=? ');
        $stmt2->execute([$row->job_title_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
             $job_title_name =  $row2->name;
             //job_title_name = str_replace("'", '&#39;',  str_replace('"', '&quot;', $row2->name));
        }


	    $ii = $i+2;
          if(!$csv_file){
	    $spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A'.$ii, "$i")
	    ->setCellValue('B'.$ii, $row->lastname)
	    ->setCellValue('C'.$ii, $row->firstname)
	    ->setCellValue('D'.$ii, $row->middlename)
	    ->setCellValue('E'.$ii, $counterparty_name)
	    ->setCellValue('F'.$ii, $job_title_name)
	    ->setCellValue('G'.$ii, $login)
	    ->setCellValue('H'.$ii, $password);
	    //$html_str = $html_str .  '<tr><td>'. str_replace(';', '', $row->lastname).'</td><td>'.  str_replace(';', '', $row->firstname).'</td><td>'.str_replace(';', '', $row->middlename).'</td><td>'. str_replace(';', '', $organization_name).'</td><td>'. str_replace(';', '', $position_name).'</td><td>'. str_replace(';', '', $row->login).'</td><td>'. str_replace(';', '', $row->password)."</td> <td>".$courses."</td></tr>\n";
          }
          else{
	    echo  str_replace(';', '', $row->lastname).';'.  str_replace(';', '', $row->firstname).' '.str_replace(';', '', $row->middlename).';'. str_replace(';', '', $counterparty_name).';'. str_replace(';', '', $login).';'. str_replace(';', '', $email_lms).';'. str_replace(';', '', $password).';'. str_replace(';', '', $group_name)."\n";
          }
          $i = $i + 1;
       }
if(!$csv_file){
    $spreadsheet->getActiveSheet()->setTitle('Список');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Список-'.$group_name.'.xlsx"');
    header('Cache-Control: max-age=0');

    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //$writer->save("frdo_write.xlsx");
    $writer->save('php://output');
}
else {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=$group_name.csv");
    header('Cache-Control: max-age=0');
}
    exit;
}
?>

