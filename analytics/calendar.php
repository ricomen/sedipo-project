<?php
/**
 * @copyright 2024
 */


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

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once('calendar-inc.php');

session_start();
$session_id = session_id();
$api_function = '';
$api_arg = [];


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

    if($date1 == ""){
         $date1 = date('Y-m-d');
    }
    if($date2 == ""){
         $date2 = date('Y-m-d', strtotime('+1 month') );
    }

    calendar_calc(  $date1, $date2 );
    $rc = Calendar::get2Interval($date1, $date2,  $l_events, $events);

    $head1 =  <<<EOT
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 210.01mm 297mm; margin: 10mm }
		p { font-family: "DejaVu Serif", serif; font-size: 12pt;  margin-top:  0.23mm;  margin-bottom: 0.23mm; background: transparent }
	        td, th { text-align: center; font-family: "Roboto", sans-serif; font-size: 10pt; }

            table, td, th  { border: 1px solid #000; border-collapse: collapse; }
	</style>
</head>
<body lang="ru-RU" link="#000080" vlink="#800000" dir="ltr"> 
EOT;



    $dompdf = new Dompdf();
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->set_option('defaultFont', 'DejaVu Sans');
    $dompdf->set_option('isRemoteEnabled', true);

    $dompdf->loadHtml($head1 . $rc . '</body>', 'UTF-8');
    $dompdf->render();
    //$dompdf->stream(rus2translit($report_file). ".pdf", array("Attachment" => 0));
    $dompdf->stream("calendar.pdf", array("Attachment" => 0));


    //echo $rc;
}

$l_events = [];
$l_teachers = [];
function calendar_calc(  $date1, $date2 ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $l_events, $l_teachers;

    $l_teachers = [];
    $stmt = $dbh->prepare("SELECT  DISTINCT `a_cohort_scheduler`.`lstream_id`, `a_cohort_scheduler`.`date`, `a_lstream`.`name`, `main_teacher`  FROM `a_cohort_scheduler` LEFT JOIN `a_lstream` USING(`lstream_id`)   WHERE `a_cohort_scheduler`.`date`>=? AND `a_cohort_scheduler`.`date`<=? AND `work`=1  ");
    $stmt->execute([ $date1, $date2 ]);
    //$stmt->execute([ date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('+2 month')) ]);
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $stmt2 = $dbh->prepare("SELECT  DISTINCT  `a_course`.`shortname`, `a_status`.`name` as `status_name`  FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_status` USING(`status_id`)   WHERE `a_cohort`.`lstream_id`=?   ");
        $stmt2->execute([$row->lstream_id  ]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $status_name =  $row2->status_name;
            $course_name =  $row2->shortname;
        }
        else {
            $status_name =  '';
            $course_name = '';
        }

        $counterparty_list = [];
        $stmt2 = $dbh->prepare('SELECT DISTINCT  `a_counterparty`.`name`, `a_counterparty`.`shortname`, `a_order_users`.`order_id`, `a_order`.`counterparty_id`, `a_order_course`.`cohort_id`,   LENGTH(`protocol_html`) as `protocol_html_length`, `order_name`, `a_order_course`.`course_id`    FROM   `a_order_course` LEFT JOIN `a_cohort`  USING(`cohort_id`) LEFT JOIN `a_order_users` USING(`item_id`)   LEFT JOIN `a_order` USING(`order_id`)  LEFT JOIN `a_counterparty` ON `a_order`.`counterparty_id`=`a_counterparty`.`counterparty_id`  WHERE `lstream_id`=? ');
        $stmt2->execute([$row->lstream_id]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            if( $row2->counterparty_id==1 ){
                  $stmt3 = $dbh->prepare("SELECT  `lastname`, `firstname`, `middlename`   FROM  `a_order_users`  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`     WHERE `a_order_users`.`order_id`=?   ");
                  $stmt3->execute([ $row2->order_id]);
                  if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                       $shortname = 'Частное лицо - '. $row3->lastname .' '. $row3->firstname .' '. $row3->middlename;
                  }
            }
            else {
                  $shortname = $row2->shortname;
            }
            $counterparty_list[] = [ "shortname"=>$shortname,  "order_id"=>$row2->order_id,  "order_name"=>$row2->order_name,  "counterparty_id"=>$row2->counterparty_id, "cohort_id"=>$row2->cohort_id,   ];
        }


        $stmt2 = $dbh->prepare("SELECT  DISTINCT  `user_id`  FROM `a_lstream_teacher`  WHERE `lstream_id`=? AND `date`=?   ");
        $stmt2->execute([$row->lstream_id, $row->date]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $teacher_id =  $row2->user_id;
        }
        else
            $teacher_id = $row->main_teacher;

        $stmt2 = $dbh->prepare("SELECT  `lastname`, `firstname`, `middlename`   FROM `a_teacher`   WHERE `user_id`=?   ");
        $stmt2->execute([ $teacher_id ]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $teacher =  $row2->lastname .' '. mb_substr($row2->firstname, 0, 1) .'. '. mb_substr($row2->middlename, 0, 1). '.';
        }
        else
           $teacher = '';

        if (  key_exists( $row->lstream_id, $l_teachers )  ){
            $fl = true;
            foreach ( $l_teachers[$row->lstream_id] as $item_t) {
                if( $item_t[0] == $teacher_id) {
                    $fl = false;
                } 
            }
            if($fl){
               array_push( $l_teachers[$row->lstream_id], [$teacher_id, $teacher]);
            }
        }
        else {
                $l_teachers[$row->lstream_id] = [ [$teacher_id, $teacher] ];
        }
        $l_events[] = [ $row->date, $row->name, $course_name, $row->lstream_id, $teacher,  $counterparty_list, $status_name ];
    }
//file_put_contents("lst.txt", print_r(  $l_events , true) );
}

 
$events = [ ];

 
// echo Calendar::getMonth(date('n'), date('Y'),  $l_events, $events );

/*function calendar_g($counterparty_id, $order_id,  $date1, $date2, $course, $status, $page, $out_format) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
 
        $rc = Calendar::get2Interval(date('Y-m'), date('Y-m', strtotime('+1 month')),  $l_events, $events);

        $result = ["role"=>$session_role, "action"=>"calendar",  "result"=>$rc    ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
}*/



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



if($api_function=='calendar'){
    if(isset($api_arg["date1"]))
	    $date1 = $api_arg["date1"];
    else
	    $date1 = "";

    if(isset($api_arg["date2"]))
	    $date2 = $api_arg["date2"];
    else
	    $date2 = "";

        if($date1 == ""){
             $date1 = date('Y-m-d');
        }
        if($date2 == ""){
             //$timestamp1 = strtotime($date1);
             $date2 = date('Y-m-d', strtotime('+1 month') );
             //$date2 = date('Y-m-d', $timestamp1 +  strtotime('+1 month'));
        }

        $l_events = [];
        $l_teachers = [];
        calendar_calc(  $date1, $date2 );
        $rc = Calendar::get2Interval($date1, $date2,  $l_events, $events);

        $result = ["role"=>$session_role, "action"=>"calendar",  "result"=>$rc    ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

/*else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}*/
 
?>
