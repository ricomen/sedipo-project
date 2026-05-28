<?php
/**
 * @copyright 2022
 */


require_once('phpspreadsheet/vendor/autoload.php');
//require_once('../PhpSpreadsheet/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$spreadsheet = new Spreadsheet();
// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');



$group_id = intval($_GET["groupId"]);
$group = "Группа_$group_id";
if($_GET["groupName"] != '')
      $group = str_replace(';', '', $_GET["groupName"]);



require_once 'config.php';
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

  $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A2', '')
    ->setCellValue('B2', 'Фамилия')
    ->setCellValue('C2', 'Имя')
    ->setCellValue('D2', 'Отчество')
    ->setCellValue('E2', "Место работы")
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



//lastname	firstname	email	username	password	idnumber 	address	institution	department	cohort1
       $i = 1;
	$stmt = $dbh->prepare('SELECT `a_users`.`user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `subdivision`,  `position_id`, `password`  FROM `a_users`  LEFT JOIN `a_groups_users` USING(`user_id`)  WHERE `group_id`=?   ORDER BY `lastname`');
	$stmt->execute([$group_id]);
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $organization_name = '';
	    $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_organizations`  WHERE `organization_id`=? ');
    	    $stmt2->execute([$row->organization_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$organization_name = $row2->name;
	    }
	    $position_name = '';
	    $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_positions`  WHERE `position_id`=? ');
    	    $stmt2->execute([$row->position_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$position_name = $row2->name;
	    }

/*	    $courses = '';
	    $stmt2 = $dbh->prepare('SELECT  `a_cache_course`.`shortname`  FROM `a_groups_users`  LEFT JOIN `a_groups` USING(`group_id`) LEFT JOIN `a_cache_course` USING(`course_id`)  WHERE `a_groups`.`date`=? AND `a_groups_users`.`user_id`=?');
	    $stmt2->execute([$date_list, $row->user_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$courses = $courses . $row2->shortname . ', ';
            }
	    $courses = mb_substr($courses, 0, -2);
*/

	    $ii = $i+2;
	    $spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A'.$ii, "$i")
	    ->setCellValue('B'.$ii, $row->lastname)
	    ->setCellValue('C'.$ii, $row->firstname)
	    ->setCellValue('D'.$ii, $row->middlename)
	    ->setCellValue('E'.$ii, $organization_name)
	    ->setCellValue('F'.$ii, $position_name)
	    ->setCellValue('G'.$ii, $row->login)
	    ->setCellValue('H'.$ii, $row->password);
	    //$html_str = $html_str .  '<tr><td>'. str_replace(';', '', $row->lastname).'</td><td>'.  str_replace(';', '', $row->firstname).'</td><td>'.str_replace(';', '', $row->middlename).'</td><td>'. str_replace(';', '', $organization_name).'</td><td>'. str_replace(';', '', $position_name).'</td><td>'. str_replace(';', '', $row->login).'</td><td>'. str_replace(';', '', $row->password)."</td> <td>".$courses."</td></tr>\n";

    	    $i = $i + 1;
       }

$spreadsheet->getActiveSheet()->setTitle('Список');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Список.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

//header('Content-disposition: filename="report.xlsx"');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
//$writer->save('../output.xlsx');
exit;

?>

