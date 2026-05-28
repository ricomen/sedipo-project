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
require_once 'lib.php';
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



session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}



function lstream_list($order_id,  $counterparty_id, $lstream_id,  $date1, $date2, $course, $lstream, $status, $page) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;
    global $HoursPerDay;

    $offset=0;
    if( $page>1 ){
	    $offset = intval($page-1)*$page_size;
    }
    $num_pages = 0;

    $l_list = [];

    if($lstream_id==0){
        $addsearch = '';
        if($counterparty_id>0){
            $addsearch =  ' AND `counterparty_id`=' . $counterparty_id . ' ';
        }
        if($date1!=''){
            $addsearch = $addsearch . ' AND `a_lstream`.`date_begin`>=' . $dbh->quote($date1) . ' ';
        }
        if($date2!=''){
            $addsearch = $addsearch . ' AND `a_lstream`.`date_end`<=' . $dbh->quote($date2) . ' ';
        }
        if($course!=''){
            $addsearch = $addsearch . ' AND `a_course`.`shortname` LIKE \'%' . addslashes(trim($course)) . '%\' ';
        }
        if($lstream!=''){
            $addsearch = $addsearch . ' AND `a_lstream`.`name` LIKE \'%' . addslashes(trim($lstream)) . '%\' ';
        }
        //if($status>0){
        //    $addsearch = $addsearch . ' AND `a_lstream`.`status_id`=' . $status . ' ';
        //}
    }
    else {
        $addsearch = 'AND `a_lstream`.`lstream_id`=' . $lstream_id . ' ';
        //$order_id = 0;
    }

    $status_id = 1;
    $date_order = '';

    $count = 0;
    if($order_id>0) { 
        $stmt = $dbh->prepare('SELECT DISTINCT `lstream_id`, `a_lstream`.`name`,  `a_lstream`.`date_begin`, `a_lstream`.`date_end`, `a_lstream`.`date_protocol`, `a_course`.`shortname` as `course_name`, `a_course`.`course_id`, `a_course`.`hours`, `a_order_course`.`course_id`, `a_course`.`category_id`, `type_of_education_id`, `subtype_of_education_id`, `type_of_program_id`, `contract_id`, `contract2_id`, `main_teacher`, `certificate1_template`, `certificate2_template`, `certificate1_name`, `certificate2_name`    FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) LEFT JOIN `a_order` USING(`order_id`) LEFT JOIN `a_course_category` USING(`category_id`) WHERE `a_cohort`.`lstream_id`>0 AND  `order_id`=?  '.$addsearch.' ORDER BY `a_lstream`.`name` DESC  LIMIT '.$page_size.'  OFFSET  ' . "$offset");
        $stmt->execute([$order_id]);
    }
    else {
        $stmt = $dbh->prepare( 'SELECT DISTINCT `lstream_id`, `a_lstream`.`name`,  `a_lstream`.`date_begin`, `a_lstream`.`date_end`, `a_lstream`.`date_protocol`, `a_course`.`shortname` as `course_name`, `a_course`.`course_id`, `a_course`.`hours`, `a_order_course`.`course_id`, `a_course`.`category_id`, `type_of_education_id`, `subtype_of_education_id`, `type_of_program_id`, `contract_id`, `contract2_id`, `main_teacher`, `certificate1_template`, `certificate2_template`, `certificate1_name`, `certificate2_name`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) LEFT JOIN `a_order` USING(`order_id`) LEFT JOIN `a_course_category` USING(`category_id`) WHERE `a_cohort`.`lstream_id`>0 AND  `order_id`>0  '.$addsearch.' ORDER BY `a_lstream`.`name` DESC  LIMIT '.$page_size.'  OFFSET  ' . "$offset");
	$stmt->execute([]);
    }
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $group_count = 0;
	    $days =  intval(intval($row->hours) / $HoursPerDay + 0.9) + intval(intval(intval($row->hours) / $HoursPerDay + 0.9) / 5 )*2;
	    $date2 = date('Y-m-d', strtotime($row->date_end) - $days * (60*60*24));

	    //$stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM  `a_order_users` LEFT JOIN  `a_order_course` USING(`item_id`)  WHERE `cohort_id`=? AND `user_id`>0 ');
	    $stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM  `a_cohort`   WHERE `lstream_id`=?  ');
	    $stmt2->execute([$row->lstream_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $group_count = $row2->count;
	    }

	    $stmt2 = $dbh->prepare('SELECT   count(*) as `count`  FROM `a_cohort_scheduler`   WHERE `cohort_id`=?');
	    //$stmt2->execute([$row->cohort_id]);
	    $stmt2->execute([$row->lstream_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $scheduler_count = $row2->count;
	    }


            /*$counterparty_shortname = '';
	    $stmt2 = $dbh->prepare('SELECT `name`, `shortname` FROM  `a_order`  LEFT JOIN `a_counterparty`  USING(`counterparty_id`)  WHERE `order_id`=? ');
	    //$stmt2->execute([$row->order_id]);
	    $stmt2->execute([$row->lstream_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $counterparty_shortname = $counterparty_shortname .' '. $row2->shortname;
	    }*/

            $contract_name_l = '';
            $template_id = 0;
            $template1_id = 0;
            $template2_id = 0;
            $template3_id = 0;
            $template_id2 = 0;
            $template1_id2 = 0;
            $template2_id2 = 0;
            $template3_id2 = 0;
	    $stmt2 = $dbh->prepare('SELECT `name`, `template_id`, `template1_id`, `template2_id`, `template3_id`   FROM  `a_contract`   WHERE `contract_id`=? ');
	    $stmt2->execute([$row->contract_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $contract_name_l =  $row2->name;
                $template_id = $row2-> template_id;
                $template1_id = $row2-> template1_id;
                $template2_id = $row2-> template2_id;
                $template3_id = $row2-> template3_id;
	    }
            $contract2_name_l = '';
	    $stmt2 = $dbh->prepare('SELECT `name`, `template_id`, `template1_id`, `template2_id`, `template3_id` FROM  `a_contract`   WHERE `contract_id`=? ');
	    $stmt2->execute([$row->contract2_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $contract2_name_l =  $row2->name;
                $template_id2 = $row2-> template_id;
                $template1_id2 = $row2-> template1_id;
                $template2_id2 = $row2-> template2_id;
                $template3_id2 = $row2-> template3_id;
	    }

            $check_sum_total = true;
            $counterparty_list = [];
	    $stmt2 = $dbh->prepare('SELECT DISTINCT  `a_counterparty`.`name`, `a_counterparty`.`shortname`, `a_order_users`.`order_id`, `a_order`.`counterparty_id`, `a_order_course`.`cohort_id`,   LENGTH(`protocol_html`) as `protocol_html_length`, `upload_certificate`, `modify_certificate1`, `modify_certificate2`, `upload_protocol`, `modify_protocol`,   `order_name`, `a_order_course`.`course_id`    FROM   `a_order_course` LEFT JOIN `a_cohort`  USING(`cohort_id`) LEFT JOIN `a_order_users` USING(`item_id`)   LEFT JOIN `a_order` USING(`order_id`)  LEFT JOIN `a_counterparty` ON `a_order`.`counterparty_id`=`a_counterparty`.`counterparty_id`  WHERE `lstream_id`=? ');
	    $stmt2->execute([$row->lstream_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                if($row2->counterparty_id==1){
                         $contract_id = $row->contract2_id;
                         $contract_name = $contract2_name_l;
                }
                else {
                         $contract_id = $row->contract_id;
                         $contract_name = $contract_name_l;
                }
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
                $additions_list = [['upload_dir'=>'', 'upload_file'=>''], ['upload_dir'=>'', 'upload_file'=>''], ['upload_dir'=>'', 'upload_file'=>''], ['upload_dir'=>'', 'upload_file'=>''] ];
                $stmt3 = $dbh->prepare("SELECT  `upload_dir`, `upload_file`, `addition`, LENGTH(`contract_html`) as `contract_html_length`  FROM  `a_counterparty_contract`   WHERE `counterparty_id`=? AND `contract_id`=? AND `addition`=0   ");
                $stmt3->execute([ $row2->counterparty_id, $contract_id]);
                if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                           $upload_file = $row3->upload_file;
                           $upload_dir = $row3->upload_dir;
                           $addition = "";
                           $additions_list[0] = ['upload_dir'=>$upload_dir, 'upload_file'=>$upload_file,  'addition'=> $addition];
                }
                $stmt3 = $dbh->prepare("SELECT  `upload_dir`, `upload_file`, `addition`, LENGTH(`contract_html`) as `contract_html_length`  FROM  `a_counterparty_contract`   WHERE `order_id`=?  AND `counterparty_id`=? AND `contract_id`=?   ORDER BY `addition`  ");
                $stmt3->execute([$row2->order_id, $row2->counterparty_id, $contract_id]);
                while($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                           $upload_file = $row3->upload_file;
                           $upload_dir = $row3->upload_dir;
                           $addition = $row3->addition;
                           $additions_list[$addition] = ['upload_dir'=>$upload_dir, 'upload_file'=>$upload_file,  'addition'=> $addition];
                }
                //$list[] =  ["contract_id"=>$row->contract_id, "name"=>$row->name, "prefix"=>$row->prefix, "date_contract"=>$date_contract, "legacy"=>$legacy, "upload_file"=>$upload_file, "upload_dir"=>$upload_dir, "contract_html_length"=>$contract_html_length, "additions_list"=>$additions_list ];

                 $check_sum = true;
                 $stmt3 = $dbh->prepare("SELECT  `snils`   FROM `a_order_course` LEFT JOIN  `a_order_users`  USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  WHERE `a_order_course`.`course_id`=? AND  `a_order_users`.`order_id`=? AND `a_users`.`user_id`>1 AND `a_users`.`status`=0   ");
                 $stmt3->execute([ $row2->course_id, $row2->order_id ]);
                 while($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                    if( checkSnilsControlSum($row3->snils)==false ){
                          $check_sum = false;
                          break;
                    }
                 }
                 if( $check_sum==false )
                      $check_sum_total = false;

	        //$counterparty_list[] = [ "shortname"=>$row2->shortname, "name"=>$row2->name, "order_id"=>$row2->order_id ];
	        $counterparty_list[] = [ "shortname"=>$shortname,  "order_id"=>$row2->order_id,  "order_name"=>$row2->order_name,  "counterparty_id"=>$row2->counterparty_id, "cohort_id"=>$row2->cohort_id,  "protocol_html_length"=>intval($row2->protocol_html_length),  "upload_certificate"=>$row2->upload_certificate,  "modify_certificate1"=>$row2->modify_certificate1, "modify_certificate2"=>$row2->modify_certificate2, "upload_protocol"=>$row2->upload_protocol, "modify_protocol"=>$row2->modify_protocol, "contract_id"=>$contract_id, "contract_name"=>$contract_name, "template_id"=>$template_id, "template1_id"=>$template1_id, "template2_id"=>$template2_id, "template3_id"=>$template3_id, "template_id2"=>$template_id2, "template1_id2"=>$template1_id2, "template2_id2"=>$template2_id2, "template3_id2"=>$template3_id2, "additions_list"=>$additions_list,  "snils_check_sum"=>$check_sum ];
	    }

/*	    $stmt2 = $dbh->prepare('SELECT   `protocol_temlate`, `certificate1_temlate`, `certificate2_temlate`, `certificate1_name`, `certificate2_name`, `main_teacher`, `a_course`.`category_id`, `a_course_category`.`consulting`    FROM  `a_lstream` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)   WHERE `lstream_id`=? ');
	    $stmt2->execute([$row->lstream_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  

	        $l_list[] =  [ "name"=>$row->name,  "status_id"=>$row->status_id,   "count"=>$group_count,  "scheduler_count"=>$scheduler_count, "cohort_id"=>$row->cohort_id, "lstream_id"=>$row->lstream_id, "course_id"=>$row->course_id,  "course_name"=>$row->course_name,  "category_id"=>$row2->category_id, "consulting"=>$row2->consulting,  "date_begin"=>$row->date_begin, "date_end"=>$row->date_end,  "hours"=>intval($row->hours), "days"=>$days, "date_begin2"=>$date2, "date_protocol"=>$row->date_protocol,  "counterparty_list"=>$counterparty_list  ]; 
	        
	    }*/
            $l_list[] =  [ "name"=>$row->name,  "status_id"=>$row->status_id,   "count"=>$group_count,  "scheduler_count"=>$scheduler_count, "cohort_id"=>$row->cohort_id, "lstream_id"=>$row->lstream_id, "course_id"=>$row->course_id,  "course_name"=>$row->course_name,  "category_id"=>$row->category_id, "consulting"=>$row2->consulting,  "date_begin"=>$row->date_begin, "date_end"=>$row->date_end,  "hours"=>intval($row->hours), "days"=>$days, "date_begin2"=>$date2, "date_protocol"=>$row->date_protocol, "type_of_education_id"=>$row->type_of_education_id,  "subtype_of_education_id"=>$row->subtype_of_education_id,  "type_of_program_id"=>$row->type_of_program_id, "contract_id"=>$row->contract_id, "contract2_id"=>$row->contract2_id, "teacher_id"=>$row->main_teacher,  "certificate1_template"=>$row->certificate1_template, "certificate2_template"=>$row->certificate2_template, "certificate1_name"=>$row->certificate1_name, "certificate2_name"=>$row->certificate2_name,  "snils_check_sum"=>$check_sum_total,   "counterparty_list"=>$counterparty_list ]; 

    }
    if($order_id>0) { 
        $stmt0 = $dbh->prepare('SELECT count(DISTINCT `a_lstream`.`lstream_id` ) as `count`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) LEFT JOIN `a_order` USING(`order_id`) LEFT JOIN `a_course_category` USING(`category_id`) WHERE `a_cohort`.`lstream_id`>0 AND  `order_id`=?  '.$addsearch );
        //$stmt0 = $dbh->prepare('SELECT count(DISTINCT `a_lstream`.`lstream_id` ) as `count`  FROM   `a_lstream` LEFT JOIN  `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_order_course`  USING(`course_id`) LEFT JOIN `a_order_users`  USING(`item_id`)   LEFT JOIN `a_order` USING(`order_id`)    WHERE `order_id`=?  '.$addsearch);
        $stmt0->execute([$order_id]);
    }
    else {
        $stmt0 = $dbh->prepare( 'SELECT count(DISTINCT `a_lstream`.`lstream_id` ) as `count`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_cohort` USING(`cohort_id`) LEFT JOIN `a_lstream` USING(`lstream_id`) LEFT JOIN `a_order` USING(`order_id`) LEFT JOIN `a_course_category` USING(`category_id`) WHERE `a_cohort`.`lstream_id`>0 AND  `order_id`>0  '.$addsearch );
        //$stmt0 = $dbh->query('SELECT   count(DISTINCT `a_lstream`.`lstream_id` ) as `count`  FROM   `a_lstream` LEFT JOIN  `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_order_course`  USING(`course_id`) LEFT JOIN `a_order_users`  USING(`item_id`)   LEFT JOIN `a_order` USING(`order_id`)   WHERE 1  '.$addsearch  );
        $stmt0->execute([]);
    }
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	$members = $row0->count;
    }
    $num_pages = intval(($members+0.5)/$page_size+1);
    if( $page > $num_pages ){
	    $page = $num_pages;
    }


    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$l_list,  "search"=>$addsearch, "numPages"=>$num_pages, "page"=>$page ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  checkSnilsControlSum($snils) {
      $numbers = str_split(str_replace(' ', '', str_replace('-', '',  trim($snils))), 1);
      $controlSum = substr(str_replace(' ', '', str_replace('-', '',  trim($snils))), -2);

      // Расчет контрольной суммы
      $calculatedSum = 0;
      for ( $i = 0; $i < 10; $i++) {
        $calculatedSum += intval($numbers[$i]) * (9 - $i);
      }

      // Проверка особых случаев
      if ($calculatedSum > 101) {
        $calculatedSum %= 101;
      }
      if ($calculatedSum == 100 || $calculatedSum == 101) {
        $calculatedSum = 0;
      }

      // Форматирование в двузначную строку
      $formattedCalculatedSum = sprintf('%02d', $calculatedSum);

      return $formattedCalculatedSum == $controlSum;
}





function lstream_object($lstream_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $HoursPerDay;

    $rc =  [  ];
    $days = 0;
    if($lstream_id >0 ) {
	    $stmt = $dbh->prepare('SELECT   `a_lstream`.`name`, `date_begin`, `date_end`, `date_protocol`,  `main_teacher`, `commission_id`, `directive_num`,   `moodle_cohort_id`   FROM `a_lstream`    WHERE `a_lstream`.`lstream_id`=?');
	    $stmt->execute([ $lstream_id ]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                 $consulting = 0;
                 

//  rank_of_profession  ,  `variation`
                 $stmt2 = $dbh->prepare('SELECT   `a_cohort`.`course_id`, `a_course`.`category_id`,  `a_course`.`name` as `course_name`, `a_course`.`shortname` as `course_shortname`, `consulting`, `a_course`.`commission_id`   FROM  `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `lstream_id`=?');
                 $stmt2->execute([ $lstream_id ]);
                 if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                    $consulting = $row2->consulting ;

                    $main_teacher_name = '';
                    $stmt3 = $dbh->prepare('SELECT  `lastname`, `firstname`, `middlename`   FROM  `a_teacher`     WHERE `user_id`=?');
                    $stmt3->execute([$row->main_teacher]);
                    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
	                  $main_teacher_name = $row3->lastname. ' ' . $row3->firstname. ' ' .$row3->middlename ;
                }

               /* if($chairman == '' && $teacher == '') {
                       $stmt3 = $dbh->prepare('SELECT `chairman`, `teachers_commission`, `directive_num` FROM `a_course` LEFT JOIN `a_teachers_commission` USING(`commission_id`)  WHERE `course_id`=?');
                       $stmt3->execute([ $row2->course_id ]);
                       if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                             $chairman = $row3->chairman;
                             $teachers_commission = $row3->teachers_commission;
                             $directive_num = $row3->directive_num;
	                }
                }*/
                $rc =  [  "name"=>$row->name,   "course_id"=>$row2->course_id,  "course_name"=>$row2->course_name,  "course_shortname"=>$row2->course_shortname,  "category"=>$row2->category_id, "consulting"=>$consulting,  "date_begin"=>$row->date_begin, "date_end"=>$row->date_end, "date_protocol"=>$row->date_protocol, "main_teacher"=>$main_teacher_name, "main_teacher_id"=>$row->main_teacher, "moodle_cohort_id"=>$row->moodle_cohort_id,  "commission_id"=>$row->commission_id, "directive_num"=>$row->directive_num  ];
            }
         }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function lstream_save($lstream_id,  $main_teacher_id,  $commission_id, $directive_num  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($lstream_id >0 && intval($commission_id)>0 ) {
        $stmt = $dbh->prepare('UPDATE `a_lstream` SET   `commission_id`=?   WHERE `lstream_id`=?');
        $stmt->execute([  $commission_id,   $lstream_id ]);
    }
    if($lstream_id >0 && intval($main_teacher_id)>0  ) {
        $stmt = $dbh->prepare('UPDATE `a_lstream` SET   `main_teacher`=?  WHERE `lstream_id`=?');
        $stmt->execute([ $main_teacher_id,   $lstream_id ]);
    }

    $stmt = $dbh->prepare('UPDATE `a_lstream` SET    `directive_num`=?   WHERE `lstream_id`=?');
    $stmt->execute([  $directive_num,   $lstream_id ]);

//file_put_contents("lst.txt", json_encode([$commission_id, $main_teacher_id, $directive_num, $lstream_id ], JSON_UNESCAPED_UNICODE));
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$lstream_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


/*
function lstream_unlink($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if( $cohort_id>0) {
        $lstream_id = 0;
        $stmt = $dbh->prepare('SELECT `lstream_id`  FROM  `a_cohort`   WHERE `cohort_id`=?');
        $stmt->execute([ $cohort_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
              $lstream_id = $row->lstream_id;
        }

        if($lstream_id >0) {
            $stmt = $dbh->prepare('UPDATE `a_cohort` SET   `lstream_id`=0   WHERE `cohort_id`=?');
            $stmt->execute([ $cohort_id ]);

            $stmt = $dbh->prepare('SELECT count(*) as `count` FROM  `a_cohort`   WHERE `lstream_id`=?');
            $stmt->execute([ $lstream_id ]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
                if($row->count == 0) {
                     $stmt2 = $dbh->prepare('DELETE  FROM  `a_cohort_scheduler`   WHERE `cohort_id`=?');
                     $stmt2->execute([ $cohort_id ]);

                     $stmt2 = $dbh->prepare('DELETE FROM  `a_lstream`   WHERE `lstream_id`=? ');
                     $stmt2->execute([$lstream_id]);
                }
            }
        }
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"lstream_unlink" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
*/


function lstream_del($lstream_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($lstream_id >0 ) {
        $stmt = $dbh->prepare('DELETE FROM  `a_lstream`   WHERE `lstream_id`=? ');
        $stmt->execute([$lstream_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"lstream_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}







function lstream_report($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_id = 0;
    $rc = [];
    $list = [];
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT  `course_id`  FROM `a_lstream`   WHERE `lstream_id`=?');
	    $stmt->execute([$group_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $course_id = $row->course_id;
	    }
    }
    if($cohort_id >0 &&  $course_id >0) { 
	    $stmt = $dbh->prepare('SELECT `user_id`  FROM `a_order_users` WHERE `lstream_id`=?');
	    $stmt->execute([$cohort_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $rc1 = [];
	        $user_id = $row->user_id;  
	        $stmt1 = $dbh->prepare('SELECT  `course`, `date`, `certificate`  FROM `a_reports`   WHERE `user_id`=? AND `course_id`=?' );
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




/*
function lstream_scheduler_object($cohort_id){
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $HoursPerDay;

    $rc =  [];
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT   `date`, `work`  FROM `a_cohort_scheduler`   WHERE `lstream_id`=?');
	    $stmt->execute([$cohort_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $rc[] = [$row->date, $row->work];
	    }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"cohort_scheduler_object", "cohort_id"=>$cohort_id, "list"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function lstream_scheduler_save($cohort_id,  $date_begin, $date_end, $date_protocol, $scheduler, $rebuild) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $HoursPerDay;

    //$days = 0;
    $lstream_id = 0;
    if($cohort_id >0 ) {
            $stmt = $dbh->prepare('DELETE FROM  `a_cohort_scheduler`  WHERE `lstream_id`=?');
            $stmt->execute([ $cohort_id  ]);

            foreach ($scheduler as $value) {
                $stmt = $dbh->prepare('INSERT INTO `a_cohort_scheduler`(`lstream_id`, `date`, `work` ) VALUES ( ?, ?, ? )');
                $stmt->execute([ $cohort_id,  $value["date"],  $value["work"] ]);
                //$lstream_id = $dbh->lastInsertId(); 
            }
            
            //$stmt2 = $dbh->prepare('UPDATE `a_lstream` SET `date_begin`=?, `date_end`=?, `date_protocol`=?, `date_num`=? WHERE `lstream_id`=?');
            //$stmt2->execute([$date_begin, $date_end, $date_protocol, $date_protocol, $cohort_id ]);
            $stmt = $dbh->prepare('UPDATE `a_lstream` SET `date_begin`=?, `date_end`=?, `date_protocol`=?  WHERE `lstream_id`=?');
            $stmt->execute([$date_begin, $date_end, $date_protocol,  $cohort_id ]);

            $i_cohort_id = $cohort_id;
            $date_end2 = $date_end;
            while($i_cohort_id > 0 && $rebuild>0){ 
	            $stmt = $dbh->prepare('SELECT  `lstream_id`, `date_begin`, `date_end`, `date_protocol`   FROM `a_lstream`   WHERE `prev_id`=?');
	            $stmt->execute([$i_cohort_id]);
	            $i_cohort_id = 0;
	            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	                    $i_cohort_id = $row->cohort_id;
	                    if($i_cohort_id > 0){
	                        $days2_timestamp = strtotime($row->date_end) - strtotime($row->date_begin);
                            $date_begin2 = date('Y-m-d', strtotime($date_end2) + (60*60*24));
                	        $date_end2 = date('Y-m-d', strtotime($date_begin2) + $days2_timestamp );
                	        $date_protocol2 = $date_end2; 

                            $stmt2 = $dbh->prepare('UPDATE `a_lstream` SET `date_begin`=?, `date_end`=?, `date_protocol`=?  WHERE `lstream_id`=?');
                            $stmt2->execute([$date_begin2, $date_end2, $date_protocol2,  $i_cohort_id ]);
	                    }
	            }         
	        }     

    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"lstream_create",  "result"=>$lstream_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
//file_put_contents("lst.txt", print_r($value, true) );
*/                


function lstream_teacher_unlink($lstream_id,  $date, $topic_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($lstream_id >0 ) {
        $stmt = $dbh->prepare('DELETE FROM  `a_lstream_teacher`   WHERE `lstream_id`=? AND `date`=? AND `topic_id`=? ');
        $stmt->execute([$lstream_id,  $date, $topic_id]);

    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"lstream_teacher_unlink",  "result"=>$lstream_id ];
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
     $session_login = $row->login;
}



api_journal('order', $api_function, $api_arg, $session_login);
if($api_function=='list'){
    if(isset($api_arg["date1"]))
	    $date1 = $api_arg["date1"];
    else
	    $date1 = "";

    if(isset($api_arg["date2"]))
	    $date2 = $api_arg["date2"];
    else
	    $date2 = "";

    if(isset($api_arg["course"]))
        $course = $api_arg["course"];
    else
        $course = '';

    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;
        
    lstream_list(intval($api_arg["order_id"]), $counterparty_id, intval($api_arg["lstream_id"]), $date1,  $date2, $course, $api_arg["lstream"], intval($api_arg["status"]), $api_arg["page"] );
}
else if($api_function=='object'){
    lstream_object(intval($api_arg["objectId"]));
}
//else if($api_function=='insert'){
//    lstream_create( $api_arg["name"], intval($api_arg["courseId"]), intval($api_arg["category"]), $api_arg["date_begin"], $api_arg["date_end"],  $api_arg["date_protocol"], $api_arg["date_order"],  $api_arg["main_teacher"], $api_arg["directive"],  intval($api_arg["commission_id"]),  $api_arg["finalexamination"], $api_arg["certificate_grade"], $api_arg["enterprise_manager"], $api_arg["enterprise_manager2"] );
//}
else if($api_function=='update'){
    lstream_save(intval($api_arg["objectId"]),   $api_arg["main_teacher"],  intval($api_arg["commission_id"]),  $api_arg["directive_num"] );
}
else if($api_function=='delete'){
    lstream_del(intval($api_arg["objectId"]) );
}
else if($api_function=='items'){
    lstream_items(intval($api_arg["objectId"]) );
}
else if($api_function=='item_del'){
    lstream_item_del(intval($api_arg["objectId"]), intval($api_arg["userId"]) );
}
//else if($api_function=='lstream_create'){
//    lstream_create(intval($api_arg["objectId"]),  $api_arg["date_begin"], $api_arg["date_end"], $api_arg["date_protocol"] );
//}

//else if($api_function=='lstream_unlink'){
//    lstream_unlink( intval($api_arg["cohort_id"]) );
//}
//else if($api_function=='cohort_scheduler_object'){
//    lstream_scheduler_object(intval($api_arg["objectId"]) );
//}
//else if($api_function=='cohort_scheduler_save'){
//    lstream_scheduler_save(intval($api_arg["objectId"]),  $api_arg["date_begin"], $api_arg["date_end"], $api_arg["date_protocol"], $api_arg["scheduler"], intval($api_arg["rebuild"]) );
//}
else if($api_function=='lstream_teacher_unlink'){
    //lstream_teacher_unlink( intval($api_arg["lstream_id"]),  strip_tags($api_arg["date"], ''), intval($api_arg["topic_id"]) );
    lstream_teacher_unlink( intval($api_arg["lstream_id"]),  $api_arg["date"], intval($api_arg["topic_id"]) );
}



else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
//    lstream_save(intval($api_arg["objectId"]), $api_arg["name"],  $api_arg["date_begin"], $api_arg["date_end"], $api_arg["date_protocol"], $api_arg["main_teacher"],  $api_arg["directive"], $api_arg["chairman"], $api_arg["teachers_commission"],  $api_arg["finalexamination"], $api_arg["certificate_grade"], $api_arg["enterprise_manager"], $api_arg["enterprise_manager2"] );

?>

