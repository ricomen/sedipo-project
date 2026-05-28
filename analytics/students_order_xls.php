<?php
/**
 * @copyright 2022
 */


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

require_once('../PhpSpreadsheet/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


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

$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt->execute([ $session_id ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


if( is_null($_JSON)){ 
    $order_id =  $_GET['id'];    
    $out_format='xlsx';

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
        ->setCreator('SED-1.0')
        ->setLastModifiedBy('SED-1.0')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory(' result file');


    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Фамилия')
        ->setCellValue('B1', 'Имя')
        ->setCellValue('C1', 'Отчество')
        ->setCellValue('D1', 'Дата рождения')
        ->setCellValue('E1', 'СНИЛС')
        ->setCellValue('F1', 'Пол (Муж. / Жен.)')
        ->setCellValue('G1', 'email')
        ->setCellValue('H1', 'Должность')
        ->setCellValue('I1', 'Подразделение')
        ->setCellValue('J1', 'Телефон')
        ->setCellValue('K1', 'Паспорт серия')
        ->setCellValue('L1', 'Паспорт номер')
        ->setCellValue('M1', 'Выдан')
        ->setCellValue('N1', 'Код подразделения')
        ->setCellValue('O1', 'Адрес');

      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('A')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('B')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('C')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('D')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('E')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('F')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('G')->setWidth(6);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('H')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('I')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('J')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('K')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('L')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('M')->setWidth(16); 
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('N')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('O')->setWidth(16); 

    $out = students_list( $order_id,  $out_format);

//file_put_contents("lst1.txt", print_r($out['users'], true) );

    $i =2;
    foreach ($out['users'] as $row) {
        $spreadsheet->setActiveSheetIndex(0)
	        ->setCellValue('A'.$i, $row['lastname'])
	        ->setCellValue('B'.$i, $row['firstname'])
	        ->setCellValue('C'.$i, $row['middlename'])
          ->setCellValue('D'.$i, $row['date_of_birth'])
	        ->setCellValue('E'.$i, $row['snils'])
	        ->setCellValue('F'.$i, $row['sex'])
	        ->setCellValue('G'.$i, $row['email'])
	        ->setCellValue('H'.$i, $row['job_title'])
	        ->setCellValue('I'.$i, $row['subdivision'])
	        ->setCellValue('J'.$i, $row['phone'])
	        ->setCellValue('K'.$i, $row['pasport_series'])
	        ->setCellValue('L'.$i, $row['pasport_number'])
          ->setCellValue('M'.$i, $row['pasport_unit'])
          ->setCellValue('N'.$i, $row['pasport_unit_number'])
          ->setCellValue('O'.$i, $row['address']);
          

            $i = $i+1;
    }

    $spreadsheet->getActiveSheet()->setTitle('Список слушателей');
    $spreadsheet->setActiveSheetIndex(0);
    $spreadsheet->getActiveSheet()->getStyle('D')
    ->getNumberFormat()
    ->setFormatCode('dd.mm.yyyy');
    //->setFormatCode(
    //    \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME, 'yyyy-mm-dd'
    //);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="список_сотрудников_эксп.xlsx"');
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
    exit;    
}


function students_list( $order_id, $out_format) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;


    if($out_format=='xlsx' || $out_format=='docx') {
        $page_size_this = 10000;
        $offset=0;
    }

    $list = [];
    $addsearch = '';

    //$stmt = $dbh->prepare('SELECT  `counterparty_id`, `date_order`, `number`, `status_id`, `payment_receipt`, `payment_receipt_date`, `invoice`  FROM `a_order` WHERE  `order_id`=? ORDER BY `date_order` ');
    $stmt = $dbh->prepare('SELECT  `counterparty_id`, `date_order`, `number`, `status_id`, `payment_receipt`, `payment_receipt_date`, `invoice`  FROM `a_order` WHERE  `order_id`=? ');
    $stmt->execute([$order_id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $invoice = $row->invoice;
        if($invoice == '')
            $invoice = 'CED-'.$row->order_id;

        $counterparty_name = '';
        $stmt2 = $dbh->prepare("SELECT  `name`,`longtime_contract`  FROM `a_counterparty`  WHERE  `counterparty_id`=? ");
        $stmt2->execute([$row->counterparty_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
             $counterparty_name = $row2->name;
        }

        $users = [];
        $stmt2 = $dbh->prepare("SELECT DISTINCT `a_user_counterparty`.`user_id`, `lastname`, `firstname`, `middlename`, `date_of_birth`, `snils`, `email`, `sex`, `job_title_id`, `subdivision`, `pasport_series`, `pasport_number`, `pasport_unit`, `pasport_unit_number`, `address`, `a_order_users`.`certificate_num`  FROM `a_order_users`  LEFT JOIN  `a_user_counterparty` USING(`user_counterparty_id`)    LEFT JOIN `a_users`  USING(`user_id`)   WHERE `order_id`=? ");
        $stmt2->execute([ $order_id ]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
          $dateofb = $row2->date_of_birth;
      $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $dateofb );
	    $job_title_name = '';
	    $stmt3 = $dbh->prepare('SELECT  `name`  FROM `a_job_title`  WHERE `job_title_id`=? ');
    	    $stmt3->execute([$row2->job_title_id]);
	    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
		$job_title_name = $row3->name;
	    }

	    $login = '';
	    $password = '';
            $email_lms = '';
	    $stmt3 = $dbh->prepare('SELECT  `login`, `password`, `email`  FROM `a_users_passwd`  WHERE `order_id`=? AND `user_id`=? ');
            $stmt3->execute([$order_id, $row2->user_id]);
	    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
	        $login = $row3->login;
	        $password = $row3->password;
	        $email_lms = $row3->email;
	    }


            //$users[] = [ 'fullname'=>$row3->lastname.' '.$row3->firstname.' '.$row3->middlename, 'certificate_num'=>$row3->certificate_num ];
            $users[] = [ 'lastname'=>$row2->lastname, 'firstname'=>$row2->firstname, 'middlename'=>$row2->middlename, 'date_of_birth'=>$excelDateValue, 'snils'=>$row2->snils,  'sex'=>$row2->sex, 'email'=>$row2->email,  'job_title'=>$job_title_name, 'subdivision'=>$row2->subdivision,  'certificate_num'=>$row2->certificate_num, 'address'=>$address, 'pasport_series'=>$pasport_series, 'pasport_number'=>$pasport_number, 'pasport_unit'=>$pasport_unit, 'pasport_unit_number'=>$pasport_unit_number, 'login'=>$login, 'password'=>$password ];
        }
        $list =  ["order_id"=>$order_id,  "date_order"=>$row->date_order,  "number"=>$row->number,  "invoice"=>$invoice,   "activity"=>$activity,   "counterparty_id"=>$row->counterparty_id, "counterparty_name"=>$counterparty_name . $users_fio,  "count"=>$order_count, "price_total"=>$price_total,  "payment_receipt"=>$row->payment_receipt, "payment_receipt_date"=>$row->payment_receipt_date,   "users"=>$users ];
    }
    
    if($out_format=='xlsx' || $out_format=='docx') {
        return($list);
    }
    else {
        $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list,  "search"=>$addsearch1,  "total"=>$members  ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}





if($api_function=='count'){

    //orders_count(  $date1, $date2 );
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

