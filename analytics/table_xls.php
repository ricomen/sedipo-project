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



//session_start();
//$session_id = session_id();
#$api_function = '';
#$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


if( is_null($_JSON))
{
    $jarg = explode( ',', $_GET['search']);    
    
    $date1 = $jarg[0];
    $date2 = $jarg[1];

    $sessionId = $jarg[2];
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

    $out = orders_list( $date1, $date2,  $out_format);

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Номер')
        ->setCellValue('B1', 'Дата заявки: ')
        ->setCellValue('C1', 'Номер счета')
        ->setCellValue('D1', 'Организация')
        ->setCellValue('E1', 'Сумма оплаты')
        ->setCellValue('F1', 'Оплачено')
        ->setCellValue('G1', 'Дата оплаты')
        ->setCellValue('H1', 'Дата акта выполненных работ / дата протокола')
        ->setCellValue('I1', 'Дата возврата акта выполненных работ')
        ->setCellValue('J1', 'Программа обучения')
        ->setCellValue('K1', 'Количество часов')
        ->setCellValue('L1', 'Количество слушателей')
        ->setCellValue('M1', 'Стоимость (руб.)')
        ->setCellValue('N1', 'Сумма (руб.)')
        ->setCellValue('O1', '№ протокола')
        ->setCellValue('P1', 'Прочие учебные центры')
        ->setCellValue('Q1', 'ФИО слушателя')
        ->setCellValue('R1', '№ удостоверения')
        ->setCellValue('S1', 'Тест')
        ->setCellValue('T1', '№ удостоверения по охране труда')
        ->setCellValue('U1', 'Примечание');

 
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('A')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('B')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('C')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('D')->setWidth(60);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('E')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('F')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('G')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('H')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('I')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('J')->setWidth(60);
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
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('P')->setWidth(8);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('Q')->setWidth(30);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('R')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('S')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('T')->setWidth(16);
      $spreadsheet->setActiveSheetIndex(0)
        ->getColumnDimension('U')->setWidth(16);

//$spreadsheet->getActiveSheet()->getStyle('A')->setBgColor('green');

    
    $i =2;
    foreach ($out as $row) {
        $timestamp = strtotime($row['date_order']);
        $payment_date = '';
        if($row['payment_receipt_date']!=null && $row['payment_receipt_date']!='' && $row['payment_receipt_date']!='0000-00-00' ){
            $timestamp_payment = strtotime($row['payment_receipt_date']);
            $payment_date = date("d.m.Y", $timestamp_payment);
        }
        $spreadsheet->setActiveSheetIndex(0)
	        ->setCellValue('A'.$i, $row['date_order'].'/'.$row['number'])
	        ->setCellValue('B'.$i, date("d.m.Y", $timestamp) )
	        ->setCellValue('C'.$i, $row['invoice'])
	        ->setCellValue('D'.$i, $row['counterparty_name'])
	        ->setCellValue('E'.$i, $row['price_total'])
	        ->setCellValue('F'.$i, $row['payment_receipt'])
	        ->setCellValue('G'.$i,  $payment_date)
	        ->setCellValue('H'.$i, '' )
	        ->setCellValue('I'.$i, '' );
            foreach ($row['courses'] as $row2) {
               $spreadsheet->setActiveSheetIndex(0)
                  ->setCellValue('J'.$i, $row2['name'])
                  ->setCellValue('K'.$i, $row2['hours'])
                  ->setCellValue('L'.$i, $row2['count'])
                  ->setCellValue('M'.$i, $row2['price_course'])
                  ->setCellValue('N'.$i, $row2['price_course_total'])
                  ->setCellValue('O'.$i, $row2['protocol_num']);

                foreach ($row2['users'] as $row3) {
                   $spreadsheet->setActiveSheetIndex(0)
                     ->setCellValue('Q'.$i, $row3['fullname'])
                     ->setCellValue('R'.$i, $row2['protocol_num'].'-'.sprintf('%02d', $row3["certificate_num"]))
                     ->setCellValue('T'.$i, $row3['eisot_cert_number']);

                   if($row2['performer_id']>0){
                   $spreadsheet->setActiveSheetIndex(0)
                     ->setCellValue('P'.$i, 'ᳵ' );
                   }
                   $i = $i+1;
                }
               $i = $i+1;
            }

            $i = $i+1;
//print_r( $row);
    }
    $spreadsheet->getActiveSheet()->setTitle('Таблица');
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="отчет.xlsx"');
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



function orders_list( $date1, $date2, $out_format) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;


    if($out_format=='xlsx' || $out_format=='docx') {
        $page_size_this = 10000;
        $offset=0;
    }

    $list = [];
    $addsearch = '';

    if($date1!=''){
        $addsearch = $addsearch . ' AND `date_order`>=' . $dbh->quote(strip_tags($date1)) . ' ';
    }
    if($date2!=''){
        $addsearch = $addsearch . ' AND `date_order`<=' . $dbh->quote(strip_tags($date2)) . ' ';
    }


    $stmt = $dbh->query('SELECT `order_id`, `counterparty_id`, `date_order`, `number`, `status_id`, `payment_receipt`, `payment_receipt_date`, `invoice`  FROM `a_order` WHERE `status_id`>10 AND `status_id`<16  '.$addsearch.' ORDER BY `order_id`  DESC LIMIT 10000');
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $invoice = $row->invoice;
        if($invoice == '')
            $invoice = 'CED-'.$row->order_id;

        $counterparty_name = '';
        $stmt2 = $dbh->prepare("SELECT  `name`,`longtime_contract`  FROM `a_counterparty`  WHERE  `counterparty_id`=? ");
	    $stmt2->execute([$row->counterparty_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $counterparty_name = $row2->name;
	    }
        $courses = [];
        $stmt1 = $dbh->prepare("SELECT  DISTINCT  `a_order_course`.`course_id`, `a_course`.`name`, `a_course`.`shortname`, `hours`, `a_order_course`.`cohort_id`, `performer_id`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN  `a_course` USING(`course_id`)  WHERE `a_order_course`.`cohort_id`>0 AND `a_order_users`.`order_id`=?  ");
        $stmt1->execute([ $row->order_id ]);
        while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {
            $k = 0;
            $price_course  = 0;
            $price_course_total = 0;
            $stmt2 = $dbh->prepare("SELECT  `price`  FROM  `a_order_price`   WHERE `order_id`=?  AND  `course_id`=?   ");
            $stmt2->execute([$row->order_id, $row1->course_id]);
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                 $price_course  =  $row2->price;
            }
            if($price_course  == 0 ){
                $stmt2 = $dbh->prepare("SELECT  `price`  FROM  `a_price`   WHERE `counterparty_id`=?  AND  `course_id`=?   ");
                $stmt2->execute([$row->counterparty, $row1->course_id]);
                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                      $price_course  =  $row2->price;
                }
            }
            if($price_course  == 0 ){
                $stmt2 = $dbh->prepare("SELECT  `price`  FROM  `a_course`   WHERE   `course_id`=?   ");
                $stmt2->execute([ $row1->course_id ]);
                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                      $price_course  =  $row2->price;
                }
            }

            $protocol_num = '';
            $date_protocol = ''; 
            $rowlstream_id = 0; 
            $stmt2 = $dbh->prepare('SELECT  `a_lstream`.`date_protocol`, `a_cohort`.`lstream_id`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_cohort`.`protocol_num`   FROM  `a_cohort` LEFT JOIN `a_lstream` USING(`lstream_id`) WHERE `cohort_id`=?');
            $stmt2->execute([ $row1->cohort_id ]);
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                 $date_protocol = $row2->date_protocol;
                 $lstream_id = $row2->lstream_id;
                 $protocol_num = sprintf('%02d', $row2->month_protocol).'/'.sprintf('%02d', $row2->day_protocol).'/'.sprintf('%d', $row2->protocol_num);
            }

            $users = [];
            $stmt3 = $dbh->prepare("SELECT DISTINCT `a_users`.`user_id`, `lastname`, `firstname`, `middlename`, `a_order_users`.`certificate_num`  FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)   WHERE `order_id`=?  AND  `course_id`=?");
            $stmt3->execute([$row->order_id, $row1->course_id]);
            while($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                $eisot_cert_number = '';  
                $stmt4 = $dbh->prepare("SELECT  `cert_number`  FROM `a_lstream_eisot`  WHERE `user_id`=?  AND  `lstream_id`=? ");
                $stmt4->execute( [ $row3->user_id, $lstream_id  ] );
                if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {
                     $eisot_cert_number = $row4->cert_number;
                }

                $users[] = [ 'fullname'=>$row3->lastname.' '.$row3->firstname.' '.$row3->middlename, 'certificate_num'=>$row3->certificate_num, 'eisot_cert_number'=>$eisot_cert_number ];
                $k = $k +1;
                $price_course_total  =  $price_course_total  + $price_course;
            }

            $courses[] = [ 'name'=>$row1->name, 'hours'=>intval($row1->hours).' ч.', 'count'=>$k, 'price_course'=>$price_course, 'price_course_total'=>$price_course_total, 'protocol_num'=>$protocol_num, 'performer_id'=>$row1->performer_id, 'users'=>$users  ];
        }




	    $order_count = 0;
	    $stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM  `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   WHERE `order_id`=?  ');
	    $stmt2->execute([$row->order_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $order_count = $row2->count;
	    }

    $secondary_order = 0;
    $stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM `a_order_cash`  WHERE `secondary_order_id`=?');
    $stmt2->execute([ $row->order_id ]);
    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
        $secondary_order = $row2->secondary_order_id;
    }

        $users_fio = '';
        if($row->counterparty_id == 1) {
            $users_fio = ':';
            $stmt2 = $dbh->prepare("SELECT DISTINCT `a_users`.`user_id`, `lastname`, `firstname`, `middlename`  FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` USING(`user_id`)   WHERE `order_id`=? ");
            $stmt2->execute([$row->order_id]);
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $users_fio =  $users_fio .' '. $row2->lastname .' '.  $row2->firstname .' '. $row2->middlename.',';
            }
            $users_fio = mb_substr($users_fio, 0, -1 );
        }

        $price_total = 0;
        $stmt2 = $dbh->prepare("SELECT `a_order_course`.`course_id`, `price`, `user_counterparty_id`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN  `a_order_price` USING(`course_id`)  WHERE `a_order_users`.`order_id`=?  AND  `a_order_price`.`order_id`=?   ");
        $stmt2->execute([$row->order_id, $row->order_id]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $price_total =  $price_total + intval($row2->price);
        }
/*        $stmt2 = $dbh->prepare("SELECT  `price` FROM `a_order_discounts` WHERE `order_id`=?   AND `course_id`=? ");
        $stmt2->execute([$order_id, $row->course_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
              $price =  $row2->price;
	    }
  */                  
        if($price_total == 0) {
            $stmt2 = $dbh->prepare("SELECT `a_order_course`.`course_id`, `price` FROM `a_order_users` LEFT JOIN  `a_order_course` USING(`item_id`)  LEFT JOIN  `a_order_discounts` USING(`course_id`)  WHERE `a_order_users`.`order_id`=?  AND  `a_order_discounts`.`order_id`=? ");
            $stmt2->execute([$row->order_id, $row->order_id]);
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $price_total =  $price_total + intval($row2->price);     
            }
        }

        if($price_total == 0) {
            $stmt2 = $dbh->prepare("SELECT DISTINCT `a_order_course`.`course_id`, `a_price`.`price` FROM `a_order_users` LEFT JOIN  `a_order_course` USING(`item_id`)   LEFT JOIN `a_order` USING(`order_id`)  LEFT JOIN `a_price` USING(`counterparty_id`)  WHERE `a_order_users`.`order_id`=?   AND `a_order_course`.`course_id`>0  AND `cohort_id`=0  AND `a_price`.`price`>0");
            $stmt2->execute( [$row->order_id  ] );
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) { 
                $price_total =  $price_total + intval($row2->price);     
            }
        }

        if($price_total == 0) {
            $stmt2 = $dbh->prepare("SELECT `a_order_course`.`course_id`, `price` FROM `a_order_users` LEFT JOIN  `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`)  WHERE `a_order_users`.`order_id`=?   AND `course_id`>0  AND `cohort_id`=0");
            $stmt2->execute( [$row->order_id  ] );
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) { 
                $price_total =  $price_total + intval($row2->price);     
            }
        }




                
          
//        if($secondary_order > 0 &&  intval($_GET['enable_lock']==0))          
//                $price = 0;
            


//        $groups_list = [];
//        $contract_list = [];
/*
//	  *  $stmt2 = $dbh->prepare('SELECT  `a_cohort`.`cohort_id`, `a_cohort`.`name`,  `a_course`.`course_id`, `a_course`.`name` as `course_name`, `protocol_temlate`, `performer_id`  FROM `a_order_groups` LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN  `a_course_category` USING(`category_id`)  WHERE `order_id`=? ');
	    $stmt2 = $dbh->prepare("SELECT DISTINCT  `a_order_users`.`cohort_id`, `a_cohort`.`name`,  `a_course`.`course_id`, `a_course`.`shortname` as `course_name`, `protocol_temlate`, `performer_id`, `main_module`, `modules`  FROM `a_order_users` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`)  LEFT JOIN  `a_course_category` USING(`category_id`)  WHERE `a_order_users`.`order_id`=? AND  `a_order_users`.`cohort_id`>0 AND (`a_course`.`main_module`='true' OR `a_course_category`.`modules`=0)");
	    $stmt2->execute([$row->order_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $groups_list[] = ["cohort_id"=>$row2->cohort_id, "name"=>$row2->name, "course_id"=>$row2->course_id, "course_name"=>$row2->course_name, "protocol_temlate"=>$row2->protocol_temlate, "main_module"=>$row2->main_module, "modules"=>$row2->modules, "performer_id"=>$row2->performer_id ];
	    }


        $contract_id = 0;
        if( $row->counterparty_id > 1){
    	    $stmt2 = $dbh->prepare('SELECT DISTINCT  `performer_id`, `contract_id`, `contract2_id`  FROM `a_order_users` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE (`contract_id`>0 OR (`contract2_id`>0 AND `performer_id`>0))  AND `a_order_users`.`order_id`=?  ');
	        $stmt2->execute([ $row->order_id ]);
	        //$stmt2 = $dbh->prepare('SELECT DISTINCT  `contract_id`, `name`, `template`, `template1`, `template2`,`template3`, `longtime_contract`  FROM `a_order_users` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)   LEFT JOIN `a_contract`  USING(`contract_id`)  WHERE `contract_id`>0 AND `a_order_users`.`order_id`=?  ');
	        //$stmt2->execute([ $row->order_id ]);
    	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            if($row2->performer_id >0 )
	                $contract_id = $row2->contract2_id;
	            else
	                $contract_id = $row2->contract_id;

    	        $stmt4 = $dbh->prepare('SELECT DISTINCT   `a_contract`.`name`, `template`, `template1`, `template2`,`template3`, `longtime_contract`  FROM   `a_contract`   WHERE `contract_id`=?   ');
	            $stmt4->execute([ $contract_id ]);
    	        if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {  

    	            if($row4->template3 != '')
	                    $addition = 3;
    	            else if($row4->template2 != '')
	                    $addition = 2;
	                else if($row4->template1 != '')
	                    $addition = 1;
	                else if($row4->template != '')
	                    $addition = 0;
    	            else
	                    $addition = -1;
	            
                    $longtime_contract = 0;
                    $longtime_contract_is_signed = 0;
	                if( $longtime_contract_c =='true' && $row4->longtime_contract>0  ){
                        $longtime_contract = 1;
	                    $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_contract` FROM  `a_counterparty_contract` WHERE `contract_id`=? AND `counterparty_id`=? ');
	                    $stmt3->execute([$contract_id, $row->counterparty_id ]);
                	    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
	                        if($row3->date_contract!='' &&  $row3->date_contract!='0000-00-00')
	                                $longtime_contract_is_signed = 1;
                	    }
	                }    
    	            $contract_list[] = ["contract_id"=>$row2->contract_id, "name"=>$row4->name, "addition"=>$addition, "longtime_contract_is_signed"=>$longtime_contract_is_signed, "longtime_contract"=>$longtime_contract ];
        	    }
	        }
        }
        else {
    	    $stmt2 = $dbh->prepare('SELECT DISTINCT  `performer_id`  FROM `a_order_users` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE `a_order_users`.`course_id`>0 AND  `a_order_users`.`order_id`=?  ');
	        $stmt2->execute([ $row->order_id ]);
    	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
    	        $performer = 0;
    	        if($row2->performer_id >0 )
            	        $performer = 1;
    	        $stmt4 = $dbh->prepare('SELECT DISTINCT  `contract_id`,  `name`, `template`  FROM   `a_contract`   WHERE `type`=1 AND `performer`=?   ');
	            $stmt4->execute([ $performer ]);
    	        if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {  
    	            $contract_list[] = ["contract_id"=>$row4->contract_id, "name"=>$row4->name, "addition"=>'', "longtime_contract"=>'', "longtime_contract_is_signed"=>'' ];
        	    }
	        }
            
        }
*/
        $members = 0;
        if($course!='')
	        $stmt0 = $dbh->query('SELECT count(DISTINCT `order_id` )  as `count`   FROM `a_order`  LEFT JOIN `a_order_users` USING(`order_id`) LEFT JOIN `a_course` USING(`course_id`)  WHERE 1  '.$addsearch);
	    else
	        $stmt0 = $dbh->query('SELECT count(DISTINCT `order_id` )  as `count`   FROM `a_order` WHERE 1  '.$addsearch);
        if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	        $members = $row0->count;
        }

	    $list[] =  ["order_id"=>$row->order_id,  "date_order"=>$row->date_order,  "number"=>$row->number,  "invoice"=>$invoice,   "activity"=>$activity,   "counterparty_id"=>$row->counterparty_id, "counterparty_name"=>$counterparty_name . $users_fio,  "count"=>$order_count, "price_total"=>$price_total,  "payment_receipt"=>$row->payment_receipt, "payment_receipt_date"=>$row->payment_receipt_date,   "courses"=>$courses ];
    }
    
    if($out_format=='xlsx' || $out_format=='docx') {
        return($list);
    }
    else {
        $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list,  "search"=>$addsearch1,  "total"=>$members  ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}


/*
function lib_create_users_report($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_id = 0;
    $course = '';
    $date_protocol = '';
    $protocol_num = '';
    $delta = 0;
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT  `a_cohort`.`course_id`, `a_course`.`name`,  `delta`   FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE `cohort_id`=?');
	    $stmt->execute([$cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $course_id = $row->course_id;
	        $course = $row->name;
	        $delta = $row->delta;
	    }
	    $stmt = $dbh->prepare('SELECT   `date_protocol`,  MONTH(`date_protocol`) as `month_protocol`, `date_protocol` + INTERVAL ?  YEAR as `date_finish`, `protocol_num`  FROM `a_cohort`   WHERE `cohort_id`=?');
	    $stmt->execute([$delta, $cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $date_protocol = $row->date_protocol;
	        $date_finish = $row->date_finish;
	        $protocol_num = $row->month_protocol .'/'. $row->protocol_num;
	    }
    }
    if($cohort_id >0 &&  $course_id >0) { 
	    $stmt2 = $dbh->prepare('SELECT `user_id`, `certificate_num`   FROM `a_order_users` WHERE `cohort_id`=?');
	    $stmt2->execute([$cohort_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	        $user_id = $row2->user_id;  
	        $certificate_num = $row2->certificate_num;  
	        $stmt3 = $dbh->prepare('SELECT count(*) as `count`  FROM `a_reports`   WHERE `course_id`=? AND `user_id`=? AND `a_date`=?  ' );
	        $stmt3->execute([$course_id, $user_id, $date_protocol ]);
	        if ($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
		          $count =  $row3->count;
	        }
	        if($count ==0) {
	            $stmt3 = $dbh->prepare('INSERT INTO `a_reports`(`user_id`, `course`, `course_id`, `certificate`, `protocol_num`, `a_date`, `date_finish`)  VALUES(?, ?, ?, ?, ?, ?, ?)  ' );
	            $stmt3->execute([$user_id, $course, $course_id, $certificate_num, $protocol_num,  $date_protocol, $date_finish ]);
	       } 
	    }
    }

}



function order_report($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_id = 0;
    $rc = [];
    $list = [];
    if($group_id >0 ) {
	    $stmt = $dbh->prepare('SELECT  `course_id`  FROM `a_cohort`   WHERE `cohort_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $course_id = $row->course_id;
	    }
    }
    if($group_id >0 &&  $course_id >0) { 
	    $stmt = $dbh->prepare('SELECT `user_id`  FROM `a_order_users`  WHERE `cohort_id`=?');
	    $stmt->execute([$group_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $rc1 = [];
	        $user_id = $row->user_id;  
	        $stmt1 = $dbh->prepare('SELECT `num`, `course`, `date`, `result`  FROM `a_reports`   WHERE `user_id`=? AND `course_id`=?' );
	        $stmt1->execute([$user_id, $course_id]);
	        while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		        $rc1[] =  ["num"=>$row1->num,  "date"=>$row1->date, "result"=>$row1->result, "course"=>$row1->course ];
	        }
	        $list[]  =  ["userId"=>$user_id,  "result"=>$rc1 ];
	    //array_push($list,  [$user_id=>$rc1]) ;
	    }
    }
    $rc =  ["group_id"=>$group_id,  "course_id"=>$course_id,  "list"=>$list ];
    $result = ["role"=>$session_role, "action"=>"group_report", "userId"=>$user_id_session, "groupReport"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
*/


function orders_count( $date1, $date2 ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $addsearch = '';

    if($date1!=''){
        $addsearch = $addsearch . ' AND `date_order`>=' . $dbh->quote(strip_tags($date1)) . ' ';
    }
    if($date2!=''){
        $addsearch = $addsearch . ' AND `date_order`<=' . $dbh->quote(strip_tags($date2)) . ' ';
    }

    $stmt = $dbh->query('SELECT count(*) as `count`  FROM `a_order` WHERE `status_id`>10 AND `status_id`<16 '.$addsearch );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $count =  $row->count;
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"count",  "result"=>$count ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}


if($api_function=='count'){
    if(isset($api_arg["date1"]))
	    $date1 = $api_arg["date1"];
    else
	    $date1 = "";

    if(isset($api_arg["date2"]))
	    $date2 = $api_arg["date2"];
    else
	    $date2 = "";


    orders_count(  $date1, $date2 );
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

