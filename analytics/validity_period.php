<?php
/**
 * @copyright 2024
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


try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;


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

    $counterparty_id = $jarg[0];
    $month = 1;
    $month_stop = 1;

    $date1 = date('d.m.Y');

    $rc = users_validity_period($counterparty_id, $month, $month_stop);

    $stmt = $dbh->prepare('SELECT DISTINCT   `a_counterparty`.`shortname`, `a_counterparty`.`name`  FROM  `a_counterparty` WHERE   `counterparty_id`=? '  );
    $stmt->execute([ $counterparty_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
              $counterparty_title = $row->name;
    }

    $thml_txt  =  <<<EOT
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 210.01mm 297mm; margin: 5mm }
		p { font-family: "DejaVu Serif", serif; font-size: 10pt;  margin-top:  0.23mm;  margin-bottom: 0.23mm; background: transparent }
	        td, th { text-align: center; font-family: "Roboto", sans-serif; font-size: 10pt; padding: 3px; }

            table, td, th  { border: 1px solid #000; border-collapse: collapse; }
	</style>
</head>
<body> 
    <p><b> Даты окончания действия документов сотрудников по состоянию на  $date1 </b></p>
    <p style="text-align: right; padding-left: 200px;"> $counterparty_title </p><br />
    <table v-if="counterparty_id>0"  width="100%">
      <thead>
        <tr  align="left">
          <th scope="col"> </th>
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <!--<th  v-if="IS_SUBDIVISION"  scope="col"> Подразделение </th>-->
          <th  scope="col"> Должность </th>
          <th scope="col"> Курс</th>
          <th scope="col"> № документа</th>
          <th scope="col"> Дата </th>
          <th scope="col"> Дата окончания действия документа </th>
        </tr>
      </thead>
     
     <tbody>   

EOT;

    $i = 1;
    foreach ( $rc as $item ) {
//print_r($item);
      $thml_txt = $thml_txt . '<tr v-for="item in info.list"  align="left" style="float: none;" >';  
      $thml_txt = $thml_txt . '<td> ' . $i  . '. </td>';
      $thml_txt = $thml_txt . '<td>' .  $item["lastname"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["firstname"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["middlename"] . '</td>';
      //if(IS_SUBDIVISION) $thml_txt = $thml_txt . '<td>' .  $item["subdivizion"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["job_title"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["course"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["certificate"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["date_convert"] . '</td>';
      $thml_txt = $thml_txt . '<td>' .  $item["date_finish_convert"] . '</td>';
      $thml_txt = $thml_txt . '</tr>';
      $i = $i +1;
    }
    $thml_txt = $thml_txt . '</tbody>';
    $thml_txt = $thml_txt . '</table>';
    $thml_txt = $thml_txt . '</body>';

//echo $thml_txt;

    $dompdf = new Dompdf();
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->set_option('defaultFont', 'DejaVu Sans');
    $dompdf->set_option('isRemoteEnabled', true);

    $dompdf->loadHtml($thml_txt, 'UTF-8');
    $dompdf->render();
    //$dompdf->stream(rus2translit($report_file). ".pdf", array("Attachment" => 0));
    $dompdf->stream("validity_period.pdf", array("Attachment" => 0));

}









function users_validity_period($counterparty_id, $month, $month_stop) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if(intval($month) <= 0 )
         $month = 1;

    $rc =  [];
    if($counterparty_id > 0 ) {
	    $stmt = $dbh->prepare('SELECT  `a_reports`.`user_id`,  `lastname`, `firstname`, `middlename`, `job_title_id`, `a_job_title`.`name` as `job_title_name` , `subdivision`,   `course`, `course_id`, `certificate`,  `a_date`, `date_finish`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_user_counterparty` ON `a_users`.`user_id`=`a_user_counterparty`.`user_id`   LEFT JOIN `a_job_title` USING(`job_title_id`)  WHERE  `a_user_counterparty`.`counterparty_id`=? AND `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH) AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\'    ORDER BY `date_finish` DESC LIMIT 1000 '  );
	    $stmt->execute([ $counterparty_id ]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $rc[] =  ["user_id"=>$row->user_id, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "job_title_id"=>$row->job_title_id,  "job_title"=>$row->job_title_name, "subdivision"=>$row->subdivision, "course"=>$row->course, "course_id"=>$row->course_id, "certificate"=>$row->certificate, "date"=>$row->a_date, "date_finish"=>$row->date_finish, "date_convert"=>formatDate($row->a_date), "date_finish_convert"=>formatDate($row->date_finish)   ];
	    }
    }
    else {
	    $stmt = $dbh->prepare('SELECT DISTINCT  `a_user_counterparty`.`counterparty_id`, `a_counterparty`.`shortname`, `a_counterparty`.`name`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_user_counterparty` ON `a_users`.`user_id`=`a_user_counterparty`.`user_id`   LEFT JOIN `a_counterparty` ON `a_user_counterparty`.`counterparty_id`=`a_counterparty`.`counterparty_id`  WHERE  `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH)  AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\' AND `a_user_counterparty`.`counterparty_id`>1  ORDER BY `a_counterparty`.`shortname`  LIMIT 500 '  );
	    $stmt->execute([  ]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $count = 0;
	        $stmt2 = $dbh->prepare('SELECT  count(`a_user_counterparty`.`user_id`) as `count`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_user_counterparty` ON `a_users`.`user_id`=`a_user_counterparty`.`user_id`    WHERE  `a_user_counterparty`.`counterparty_id`=? AND `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH) AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\' '  );
	        $stmt2->execute([ $row->counterparty_id ]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                      $count = $row2->count;
                }
	        $rc[] =  ["counterparty_id"=>$row->counterparty_id, "counterparty_shortname"=>$row->shortname, "counterparty_title"=>$row->name, "count"=>$count   ];
	    }
    }
    //$result = ["role"=>$session_role, "action"=>"report",  "list"=>$rc ];
    //echo json_encode($result, JSON_UNESCAPED_UNICODE);
    return $rc;
}

function formatDate($date_point) {
    $datetemp = explode("-",$date_point);
    $yearmy = $datetemp[0];
    $mounthmy = $datetemp[1];
    $daymy = $datetemp[2];
    
    return "$daymy.$mounthmy.$yearmy";
}


function hidden($counterparty_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = 0;
    if($counterparty_id > 0 ) {
	    //$stmt = $dbh->prepare('SELECT  `a_reports`.`user_id`,  `lastname`, `firstname`, `middlename`, `job_title_id`, `a_job_title`.`name` as `job_title_name` , `subdivision`,   `course`, `course_id`, `certificate`,  `a_date`, `date_finish`  FROM `a_reports` LEFT JOIN `a_users` USING(`user_id`)  LEFT JOIN `a_job_title` USING(`job_title_id`)  WHERE  `a_users`.`counterparty_id`=? AND `date_finish`<=  DATE_SUB(CURDATE(), INTERVAL '.intval($month).' MONTH) AND `a_date`<>`date_finish` AND `date_finish`>\'1000-01-01\'    ORDER BY `date_finish` DESC LIMIT 1000 '  );
	    //$stmt->execute([ $counterparty_id ]);

            $rc = 1;
    }
    //$result = ["role"=>$session_role, "action"=>"report",  "list"=>$rc ];
    //echo json_encode($result, JSON_UNESCAPED_UNICODE);
    return $rc;
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



if($api_function=='validity_period'){

        $rc = users_validity_period( intval($api_arg["counterparty_id"]), intval($api_arg["month"]) , intval($api_arg["month_stop"]) );

        $result = ["role"=>$session_role, "action"=>"validity_period",  "list"=>$rc    ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
else if($api_function=='hidden'){

        $rc = hidden( intval($api_arg["counterparty_id"]) );

        $result = ["role"=>$session_role, "action"=>"hidden",  "result"=>$rc    ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


?>
