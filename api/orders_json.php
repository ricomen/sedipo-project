<?php
/**
 * @copyright 2022
 */

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



function order_items($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global  $IS_LMS;

    $users_list = [];
    $counterparty_id = 0;
    $money = 0;

    if($order_id >0 ) {
	    $stmt = $dbh->prepare('SELECT DISTINCT  `counterparty_id`  FROM `a_order`   WHERE `order_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                  $counterparty_id =  $row->counterparty_id; 
        }
        if($counterparty_id == 0){
             $result = ["role"=>$session_role, "action"=>"items",  "list"=>[], "consulting_list"=>[], "balance"=>0  ];
             echo json_encode($result, JSON_UNESCAPED_UNICODE);
             return;
        }

//AND (`certificate_num`>0 OR (`course_id`=0 AND `certificate_num`=0))  , `certificate_num`
        $stmt = $dbh->prepare('SELECT DISTINCT  `item_id`, `a_users`.`user_id`, `a_order_users`.`user_counterparty_id`,  `lastname`, `firstname`, `middlename`, `a_order_users`.`certificate_num`, `user_lock`,  `job_title_id`, `date_of_birth`, `snils`, `number_of_people`  FROM `a_order_users` LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users`  ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`     WHERE `order_id`=? AND `counterparty_id`=?  AND (`a_users`.`status`=0 || `a_users`.`user_id`=1)  ORDER BY `certificate_num`, `lastname` ');
        $stmt->execute([$order_id, $counterparty_id]);
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $job_title_name = '';
	        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_job_title`  WHERE `job_title_id`=? ');
                $stmt2->execute([$row->job_title_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		        $job_title_name = $row2->name;
	        }

	        $login = '';
	        $password = '';
                $email_lms = '';
                if( $IS_LMS == 1 ){
                   $stmt2 = $dbh->prepare('SELECT  `login`, `password`, `email`  FROM `a_users_passwd`  WHERE `order_id`=? AND `user_id`=? ');
                   $stmt2->execute([$order_id, $row->user_id]);
                   if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                       $login = $row2->login;
                       $password = $row2->password;
                       $email_lms = $row2->email;
                   }
                }

	        $course_list = [];
                 if($row->user_id >1 ){
                     //$stmt2 = $dbh->prepare('SELECT DISTINCT  `item_id`, `a_order_course`.`course_id`, `a_course`.`name`, `shortname`,  `certificate_name`, `certificate_a1_name`, `certificate_temlate`, `certificate_a1_temlate`, `is_rank_of_profession`, `main_module`, `modules`, `variation`, `a_course`.`hours`, `hours_add`, `hours_add2`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `user_id`=? AND `a_order_users`.`order_id`=?  AND `a_order_course`.`course_id`>0 AND `cohort_id`=0  ORDER BY  `modules`, `main_module` DESC, `shortname` ');
                     $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`course_id`, `a_course`.`name`, `shortname`,  `certificate1_name`, `certificate2_name`, `certificate1_template`, `certificate2_template`,  `main_module`, `modules`, `variation`, `a_course`.`hours`, `hours_add`, `hours_add2`, `type_of_education_id`, `subtype_of_education_id`, `blank_number`, `custom_cert_number`,  `group_number`   FROM  `a_order_course`   LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `item_id`=?   AND `course_id`>0   ORDER BY  `modules`, `main_module` DESC, `shortname` ');
                     $stmt2->execute([ $row->item_id ]);
                }
                else{
                     //$stmt2 = $dbh->prepare('SELECT DISTINCT  `item_id`, `a_order_course`.`course_id`, `a_course`.`name`, `shortname`,  `certificate_name`, `certificate_a1_name`, `certificate_temlate`, `certificate_a1_temlate`, `is_rank_of_profession`, `main_module`, `modules`, `variation`, `a_course`.`hours`, `hours_add`, `hours_add2`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `user_id`=? AND `a_order_users`.`order_id`=?  AND `a_order_course`.`course_id`>0 AND `cohort_id`=0  AND `parent_item_id`=?  ORDER BY  `modules`, `main_module` DESC, `shortname` ');
                     $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`course_id`, `a_course`.`name`, `shortname`,  `certificate1_name`, `certificate2_name`, `certificate1_template`, `certificate2_template`,  `main_module`, `modules`, `variation`, `a_course`.`hours`, `hours_add`, `hours_add2`, `type_of_education_id`, `subtype_of_education_id`, `blank_number`, `custom_cert_number`, `group_number`   FROM `a_order_course`  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `item_id`=?  AND `course_id`>0   ORDER BY  `modules`, `main_module` DESC, `shortname` ');
                     $stmt2->execute([ $row->item_id ]);
                }
	        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	            $shortname = $row2->shortname;
	            if($shortname == '')
	                    $shortname = $row2->name;

                    if($row2->variation == 2)
                             $hours = intval($row2->hours_add);
                    else if($row2->variation == 3)
                             $hours = intval($row2->hours_add2);
                    else
                             $hours = intval($row2->hours);

                    $is_blank = 'false';
                    $stmt3 = $dbh->prepare('SELECT  `is_blank`   FROM   `a_template`   WHERE `template_id`=? ');
                    $stmt3->execute([ $row2->certificate1_template ]);
                    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
	                $is_blank = $row3->is_blank;
	            }         


                    $rank_of_profession ='';
	            //$stmt3 = $dbh->prepare('SELECT  `rank_of_profession`   FROM  `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)  WHERE `order_id`=? AND `course_id`=? AND `rank_of_profession`>0 ');
    	            //$stmt3->execute([$order_id, $row2->course_id]);
	            //while($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
	            //    $rank_of_profession = $row3->rank_of_profession;
	            //}         
	                    

                    $course_list[] = [ "course_id"=>$row2->course_id, "name"=>$row2->name, "shortname"=>$shortname,  "certificate1_name"=>$row2->certificate1_name, "certificate2_name"=>$row2->certificate2_name, "certificate1_template"=>$row2->certificate1_template, "certificate1_template"=>$row2->certificate1_template,  "main_module"=>$row2->main_module, "modules"=>$row2->modules,  "hours"=>$hours, "variation"=>$row2->variation, "is_blank"=>$is_blank, "blank_number"=>$row2->blank_number, "custom_cert_number"=>$row2->custom_cert_number,  "group_number"=>$row2->group_number ];
	        }
                
                $check_sum = checkSnilsControlSum($row->snils);

	        $users_list[] =  ["item_id"=>$row->item_id,  "user_id"=>$row->user_id,  "user_counterparty_id"=>$row->user_counterparty_id,  "num"=>$row->certificate_num, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename,  "user_lock"=>$row->user_lock,  "job_title"=>$job_title_name,  "date_of_birth"=>$row->date_of_birth, "snils"=>$row->snils,  "snils_check_sum"=>$check_sum,  "number_of_people"=>$row->number_of_people, "login"=>$login,  "password"=>$password, "email_lms"=>$email_lms,  "course_list"=>$course_list ];
        }

        $consulting_list = [];
        //$stmt = $dbh->prepare('SELECT DISTINCT  `a_order_course`.`course_id`, `a_course`.`name`, `shortname`, `a_order_course`.`quantum`   FROM  `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `order_id`=?  AND `user_id`=0  ');
        $stmt = $dbh->prepare('SELECT DISTINCT  `a_order_course`.`course_id`, `a_course`.`name`, `shortname`, `quantum`   FROM  `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `order_id`=?  AND `a_order_users`.`user_counterparty_id`=0    ');
        $stmt->execute([$order_id]);
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
              $shortname = $row->shortname;
              if($shortname == '')
                    $shortname = $row->name;
                    $consulting_list[] = ["course_id"=>$row->course_id, "name"=>$row->name, "shortname"=>$shortname,  "quantum"=>$row->quantum ];
         }
         $money = order_balance($order_id);    

         $group_list = [];
         $stmt = $dbh->prepare('SELECT `course_id`, `group_id`, `group_number`, `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? ');
         $stmt->execute([$order_id]);
         while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                  $group_list[] = [ "course_id"=>$row->course_id,  "group_id"=>$row->group_id, "group_number"=>$row->group_number,  "custom_protocol_num"=>$row->custom_protocol_num];
         }
    }
    
    //$result = ["role"=>$session_role, "action"=>"users_list", "userId"=>$user_id_session, "result"=>$rc_list];
    $result = ["role"=>$session_role, "action"=>"items",  "list"=>$users_list, "consulting_list"=>$consulting_list, "group_list"=>$group_list,  "balance"=>$money  ];
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



function order_item_del( $item_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($item_id >0 ) {
        $order_id = 0;
        $stmt = $dbh->prepare('SELECT `order_id`   FROM  `a_order_users`  WHERE   `item_id`=?   ');
        $stmt->execute( [ $item_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $order_id = $row->order_id;
        }

        $stmt = $dbh->prepare('DELETE FROM  `a_order_course`   WHERE `item_id`=? ');
        $stmt->execute([ $item_id ]);

        $stmt = $dbh->prepare('DELETE FROM  `a_order_users`   WHERE `item_id`=? ');
        $stmt->execute([ $item_id ]);

        $i=1;
        $stmt = $dbh->prepare('SELECT  `item_id`, `certificate_num`   FROM  `a_order_users`   WHERE `order_id`=?   ORDER BY `certificate_num` ');
        $stmt->execute( [$order_id, ] );
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            if($row->certificate_num != $i){
                 $stmt2 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE  `item_id`=?  ');
                 $stmt2->execute([$i,  $row->item_id]);
            }
            $i = $i + 1;
        }
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"order_item_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function user_sort($order1_id, $item_id, $cmd) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if( $item_id>0) {
        $order_id = 0;
        $stmt = $dbh->prepare('SELECT `order_id`   FROM  `a_order_users`  WHERE   `item_id`=?   ');
        $stmt->execute( [ $item_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $order_id = $row->order_id;
        }

        $num = 1;
        $stmt = $dbh->prepare('SELECT  `certificate_num`   FROM  `a_order_users`   WHERE `order_id`=? AND `item_id`=?   ');
        $stmt->execute( [$order_id, $item_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
              if($row->certificate_num>0)
                    $num = $row->certificate_num;
        }
        if($cmd == 'down'){
            $num2 = $num;
            $item_id2 = 0;
            $stmt2 = $dbh->prepare('SELECT  `item_id`, `certificate_num`   FROM  `a_order_users`   WHERE `order_id`=? AND `certificate_num`>?   ORDER BY `certificate_num` ');
            $stmt2->execute( [$order_id, $num] );
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                $num2 = $row2->certificate_num;
                $item_id2 = $row2->item_id;
            }
            if($num2 <= $num)
                  $num2 = $num+1;
            if($item_id2 >0 ){
	            $stmt3 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE `order_id`=? AND `item_id`=?  ');
	            $stmt3->execute([$num2, $order_id, $item_id]);

	            $stmt3 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE `order_id`=? AND `item_id`=?  ');
	            $stmt3->execute([$num, $order_id, $item_id2]);
            }
        }
        if($cmd == 'up'){
            $num2 = $num;
            $item_id2 = 0;
            $stmt2 = $dbh->prepare('SELECT  `item_id`, `certificate_num`   FROM  `a_order_users`   WHERE `order_id`=? AND `certificate_num`<?   ORDER BY `certificate_num` DESC ');
            $stmt2->execute( [$order_id, $num] );
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                $num2 = $row2->certificate_num;
                $item_id2 = $row2->item_id;
            }
            if($num2 >= $num)
                  $num2 = $num-1;
            if($item_id2 >0 ){
	            $stmt3 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE `order_id`=? AND `item_id`=?  ');
	            $stmt3->execute([$num2, $order_id, $item_id]);

	            $stmt3 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE `order_id`=? AND `item_id`=?  ');
	            $stmt3->execute([$num, $order_id, $item_id2]);
            }
        }
        
        $i=1;
        $stmt = $dbh->prepare('SELECT  `item_id`, `certificate_num`   FROM  `a_order_users`   WHERE `order_id`=?   ORDER BY `certificate_num` ');
        $stmt->execute( [$order_id, ] );
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            if($row->certificate_num != $i){
                 $stmt2 = $dbh->prepare('UPDATE  `a_order_users` SET `certificate_num`=?  WHERE  `item_id`=?  ');
                 $stmt2->execute([$i,  $row->item_id]);
            }
            $i = $i + 1;
        }

    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"user_up" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function order_item_lock($order_id, $user_id, $user_lock) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 ) {
        $stmt = $dbh->prepare('SELECT DISTINCT  `a_order_users`.`item_id`   FROM  `a_order_users`   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)    WHERE `order_id`=?  AND  `user_id`=?  ');
        $stmt->execute([$order_id, $user_id]);
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $stm2 = $dbh->prepare('UPDATE  `a_order_users` SET `user_lock`=?  WHERE `item_id`=? ');
            $stm2->execute([ $user_lock,  $row->item_id]);
        }
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"lock" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function order_course($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_list = [];
    $counterparty_id = 0;

    if($order_id >0 ) {
        $counterparty_id = 0;
        $stmt = $dbh->prepare('SELECT `counterparty_id`   FROM `a_order`   WHERE `order_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	            $counterparty_id =  $row->counterparty_id;
	    }

//AND (`certificate_num`>0 OR (`course_id`=0 AND `certificate_num`=0))  , `certificate_num`
	    $stmt = $dbh->prepare('SELECT DISTINCT  `a_order_course`.`course_id`, `a_course`.`name`, `shortname`,   `main_module`, `modules`, `variation`, `a_course`.`hours`, `hours_add`, `hours_add2`, `price`  FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `a_order_users`.`order_id`=?    ');
	    $stmt->execute([$order_id ]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $shortname = $row->shortname;
	        if($shortname == '')
	              $shortname = $row->name;

            if($row->variation == 2)
                   $hours = intval($row->hours_add);
            else if($row->variation == 3)
                    $hours = intval($row->hours_add2);
            else
                    $hours = intval($row->hours);
            
            $price = $row->price;
            $stmt2 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
            $stmt2->execute([$counterparty_id, $row->course_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                if(intval($row2->price) >0 )      
                      $price =  intval($row2->price);
	        }

            $price2 = '';
            $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_discounts` WHERE `order_id`=?  AND `course_id`=? ");
            $stmt3->execute([$order_id, $row->course_id]);
	        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                if(intval($row3->price) >0 )      
                      $price2 =  intval($row3->price);
	        }


		    $course_list[] = [ "course_id"=>$row->course_id, "name"=>$row->name, "shortname"=>$shortname,  "main_module"=>$row2->main_module, "modules"=>$row2->modules,  "hours"=>$hours, "variation"=>$row2->variation, "price"=>$price, "price2"=>$price2 ] ;
       }
    }

    //$result = ["role"=>$session_role, "action"=>"users_list", "userId"=>$user_id_session, "result"=>$rc_list];
    $result = ["role"=>$session_role, "action"=>"order_course",  "list"=>$course_list   ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function order_discounts_save($order_id, $a_course_id, $price  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    if($order_id >0 ) {
        $stmt = $dbh->prepare('DELETE FROM `a_order_discounts` WHERE `order_id`= ? ');
        $stmt->execute([ $order_id ]);
        for($i=0; $i<count($a_course_id); $i++) {
            if(intval($price[$i]) >0 ) {
                $stmt = $dbh->prepare('INSERT INTO `a_order_discounts`(`order_id`, `course_id`, `price`) VALUES( ?, ?, ?) ');
                $stmt->execute([ $order_id, $a_course_id[$i], intval($price[$i]) ]);
            }
//file_put_contents("lst.txt", print_r($contract_count, true) );
        }
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"discounts_save",  "result"=>$i ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}







function order_del($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 ) {
        $stmt = $dbh->prepare('SELECT  `item_id`   FROM  `a_order_users`   WHERE `order_id`=?');
        $stmt->execute( [$order_id] );
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {

                $stmt2 = $dbh->prepare('SELECT  `cohort_id`   FROM  `a_order_course`   WHERE `item_id`=?');
                $stmt2->execute( [ $row->item_id ] );
                while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                    $stmt3 = $dbh->prepare('DELETE FROM  `a_cohort_scheduler`  WHERE `cohort_id`=?');
                    $stmt3->execute([ $row2->cohort_id   ]);

                    $stmt3 = $dbh->prepare('DELETE FROM  `a_cohort`  WHERE `cohort_id`=? ');
                    $stmt3->execute([ $row2->cohort_id ]);

                    $lstream_id_i = 0;
                    $stmt3 = $dbh->prepare('SELECT  `lstream_id`  FROM `a_cohort`   WHERE `cohort_id`=? ');
	            $stmt3->execute([ $row2->cohort_id ]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                       $lstream_id_i = $row3->lstream_id;
                    }
                    if($lstream_id_i >0 ){
                        $stmt3 = $dbh->prepare('SELECT count(*) as `count`  FROM `a_cohort`  WHERE  `lstream_id`=? ');
                        $stmt3->execute([$lstream_id_i]);
                        if($row3 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                            if($row3->count == 0){
                                 $stmt4 = $dbh->prepare('DELETE FROM   `a_lstream`   WHERE  `lstream_id`=?  ');
                                 $stmt4->execute([$lstream_id_i]);
                            }
	                }
	            }

                }

                $stmt3 = $dbh->prepare('DELETE FROM  `a_order_course`   WHERE `item_id`=? ');
                $stmt3->execute([ $row->item_id ]);

	        $stmt3 = $dbh->prepare('DELETE FROM  `a_order_users`   WHERE `item_id`=? ');
	        $stmt3->execute([ $row->item_id ]);
            }

	    $stmt = $dbh->prepare('DELETE FROM  `a_order_discounts`   WHERE `order_id`=?  ');
	    $stmt->execute([$order_id]);

	    $stmt = $dbh->prepare('DELETE FROM  `a_order_price`   WHERE `order_id`=?  ');
	    $stmt->execute([$order_id]);

	    $stmt = $dbh->prepare('DELETE FROM  `a_order_users`   WHERE `order_id`=?  ');
	    $stmt->execute([$order_id]);

	    $stmt = $dbh->prepare('DELETE FROM  `a_order`   WHERE `order_id`=?  ');
	    $stmt->execute([$order_id]);

	    $stmt = $dbh->prepare('DELETE FROM  `a_order_cash`   WHERE `order_id`=? OR `secondary_order_id`=?  ');
	    $stmt->execute([$order_id, $order_id]);
	
	    $stmt = $dbh->prepare('DELETE FROM  `a_order_group`   WHERE `order_id`=?  ');
	    $stmt->execute([$order_id]);

    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"delete" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function orders_list($counterparty_id, $order_id,  $date1, $date2, $order_name, $course, $status, $page, $sort) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $page_size;

    $offset=0;
    if( $page>1 ){
	    $offset = intval($page-1)*$page_size;
    }
    $num_pages = 0;
    
    $order_str = ' `date_order`  DESC, `number`  DESC ';
    if($sort > 0 )
         $order_str = ' `date_ct`  DESC ';

    $list = [];
    $addsearch = '';
    if($counterparty_id>0){
        $addsearch =  ' AND `counterparty_id`=' . $counterparty_id . ' ';
    }
    if($order_id>0){
        $addsearch =  ' AND `a_order`.`order_id`=' . $order_id. ' ';
    }
    if($date1!=''){
        $addsearch = $addsearch . ' AND `date_order`>=' . $dbh->quote(strip_tags($date1)) . ' ';
    }
    if($date2!=''){
        $addsearch = $addsearch . ' AND `date_order`<=' . $dbh->quote(strip_tags($date2)) . ' ';
    }
    if($order_name!=''){
        $addsearch = $addsearch . ' AND `a_order`.`order_name` LIKE \'%' . addslashes(strip_tags(trim($order_name))) . '%\' ';
    }
    if($course!=''){
        $addsearch = $addsearch . ' AND `a_course`.`shortname` LIKE \'%' . addslashes(strip_tags(trim($course))) . '%\' ';
    }
    if($status>0){
        $addsearch = $addsearch . ' AND `status_id`=' . $status . ' ';
    }
    else {
        $addsearch = $addsearch . ' AND `status_id`<=15  ';
    }
//file_put_contents("lst.txt", print_r($addsearch, true) );

    if($course!='')
	    $stmt = $dbh->query('SELECT DISTINCT `order_id`, `counterparty_id`, `date_order`, `number`, `date_completed`, `status_id`, `payment_receipt`, `payment_receipt_date`, `invoice`, `postpaid`, `upload_file`   FROM `a_order`  LEFT JOIN `a_order_users` USING(`order_id`)  LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_course` USING(`course_id`)  WHERE 1  '.$addsearch.' ORDER BY '.$order_str.'  LIMIT '.$page_size.'  OFFSET  ' . "$offset");
    else
	    $stmt = $dbh->query('SELECT `order_id`, `counterparty_id`, `date_order`, `number`, `date_completed`, `status_id`, `payment_receipt`, `payment_receipt_date`, `invoice`, `postpaid`, `upload_file`  FROM `a_order` WHERE 1  '.$addsearch.' ORDER BY '.$order_str.' LIMIT '.$page_size.'  OFFSET  ' . "$offset");

    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        //$organization_name = '';
        $longtime_contract_c =  '';
        $counterparty_name = '';
        if($row->counterparty_id > 1){
            $stmt2 = $dbh->prepare("SELECT  `name`,`longtime_contract`  FROM `a_counterparty`  WHERE  `counterparty_id`=? ");
            $stmt2->execute([$row->counterparty_id]);
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $counterparty_name = $row2->name;
	            $longtime_contract_c =  $row2->longtime_contract;
            }
        }
        else{
            $longtime_contract_c =  '';
            $stmt2 = $dbh->prepare("SELECT  `lastname`, `firstname`, `middlename`  FROM  `a_order` LEFT JOIN `a_order_users` USING(`order_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  WHERE  `a_order`.`order_id`=? ");
            $stmt2->execute([$row->order_id]);
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $counterparty_name = 'Частное лицо - ' . $row2->lastname. ' ' . $row2->firstname. ' ' . $row2->middlename;
	            $longtime_contract_c = 'false';
            }
        }
        $status_name = '';
        $activity = 0;
        $activity2 = 0;
        $action = '';
        $new_status = 0;
        $action2 = '';
        $new_status2 = 0;
        $stmt2 = $dbh->prepare("SELECT  `name`, `activity`, `activity2`, `action`, `new_status`, `action2`, `new_status2`, `action_postpaid`, `new_status_postpaid`   FROM `a_status`  WHERE  `status_id`=? ");
        $stmt2->execute([$row->status_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $status_name = $row2->name;
	        $activity  = $row2->activity;
	        $activity2 = $row2->activity2;
	        $action2 = $row2->action2;
	        $new_status2 = $row2->new_status2;

                if( $row->postpaid >0 && $row2->new_status_postpaid >0 ){
                        $new_status = $row2->new_status_postpaid;
                        $action = $row2->action_postpaid;
                } 
                else {
                        $new_status = $row2->new_status;
                        $action = $row2->action;
                }

        }
        $order_count = 0;
        $stmt2 = $dbh->prepare('SELECT count(DISTINCT `user_id`) as `count` FROM  `a_order_users` LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)     WHERE `order_id`=?  ');
        $stmt2->execute([$row->order_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $order_count = $row2->count;
        }

        $check_sum = true;
        $stmt2 = $dbh->prepare("SELECT  `snils`  FROM  `a_order` LEFT JOIN `a_order_users` USING(`order_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  WHERE  `a_order`.`order_id`=? ");
        $stmt2->execute([$row->order_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            if( checkSnilsControlSum($row2->snils)==false )
                  $check_sum = false;
        }

        $groups_list = [];
        $groups_count = 0;
        $stmt2 = $dbh->prepare("SELECT count(*) as `count`   FROM `a_order_users`  LEFT JOIN `a_order_course`  USING(`item_id`)   WHERE  `order_id`=? AND `cohort_id`>0"); 
        $stmt2->execute([$row->order_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $groups_count = $row2->count;
        }

        $lstream_count = 0;
        $stmt2 = $dbh->prepare("SELECT count(*) as `count`   FROM `a_order_users`  LEFT JOIN `a_order_course`  USING(`item_id`)   LEFT JOIN `a_cohort`  USING(`cohort_id`)   WHERE  `order_id`=? AND `a_order_course`.`cohort_id`>0 AND `a_cohort`.`lstream_id`>0 "); 
        $stmt2->execute([$row->order_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $lstream_count = $row2->count;
        }

        $contract_list = [];
        $contract_id = 0;
        if($row->counterparty_id > 1){
          $stmt2 = $dbh->prepare('SELECT DISTINCT  `performer_id`, `contract_id`, `contract2_id`  FROM `a_order_users` LEFT JOIN `a_order_course`  USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE (`contract_id`>0 OR (`contract2_id`>0 AND `performer_id`>0))  AND `a_order_users`.`order_id`=? ORDER BY `contract_id`, `contract2_id` ');
          $stmt2->execute([ $row->order_id ]);
//file_put_contents("lst.txt", print_r($row->counterparty_id, true) );
          while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            if($row2->performer_id >0 )
                $contract_id = $row2->contract2_id;
            else
                $contract_id = $row2->contract_id;

            $additions_count = -1;
            $stmt4 = $dbh->prepare('SELECT DISTINCT   `a_contract`.`name`, `template_id`, `template1_id`, `template2_id`,`template3_id`, `longtime_contract`  FROM   `a_contract`   WHERE `contract_id`=?   ');
            $stmt4->execute([ $contract_id ]);
            if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {  


    	            if($row4->template3_id > 0 )
	                    $additions_count = 3;
    	            else if($row4->template2_id > 0 )
	                    $additions_count = 2;
	            else if($row4->template1_id > 0 )
	                    $additions_count = 1;
	            else if($row4->template_id > 0 )
	                    $additions_count = 0;
    	            else
	                    $additions_count = -1;

                    $date_contract = '';
                    $upload_file = '';
                    $upload_dir = '';
                    $longtime_contract = 0;
                    $longtime_contract_is_signed = 0;
	            if( $longtime_contract_c =='true' && $row4->longtime_contract>0  )
                              $longtime_contract = 1;

                    if( $longtime_contract == 1 ){
	                 $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_contract`, `legacy`, `upload_file`, `upload_dir`  FROM  `a_counterparty_contract` WHERE `contract_id`=? AND `counterparty_id`=? AND `addition`<0 AND `order_id`=0 ');
	                 $stmt3->execute([$contract_id, $row->counterparty_id ]);
                    }
                    else{
	                 $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_contract`, `legacy`, `upload_file`, `upload_dir`  FROM  `a_counterparty_contract` WHERE `contract_id`=? AND `counterparty_id`=? AND `addition`<0 AND `order_id`=? ');
	                 $stmt3->execute([$contract_id, $row->counterparty_id, $row->order_id ]);
                    }
                    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
	                    if($row3->date_contract!='' &&  $row3->date_contract!='1000-01-01' &&  $row3->date_contract!='0000-00-00' && $row3->upload_file!='' && $row3->upload_dir!='' )
	                            $longtime_contract_is_signed = 1;

                            $date_contract = $row3->date_contract;
                            $legacy_contract = $row3->legacy;
                            $upload_file = $row3->upload_file;
                            $upload_dir = $row3->upload_dir;
                    }
                    $additions_list = [];
                    for($i=0; $i<4; $i++) {
                        $a_item = [ "addition"=> -1, "date_contract"=>'',  "upload_file"=>'', "upload_dir"=>'' ];
	                $stmt5 = $dbh->prepare('SELECT DISTINCT  `addition`, `date_contract`, `legacy`, `upload_file`, `upload_dir`  FROM  `a_counterparty_contract`  WHERE `order_id`=? AND  `contract_id`=? AND `counterparty_id`=? AND `addition`=?  ORDER BY `addition` ');
	                $stmt5->execute([$row->order_id, $contract_id, $row->counterparty_id, $i ]);
                        if($row5 = $stmt5->fetch(PDO::FETCH_OBJ)) {  
                             if($row5->date_contract!='' &&  $row5->date_contract!='0000-00-00')
                                    $a_item = [ "addition"=>$row5->addition, "date_contract"=>$row5->date_contract,  "upload_file"=>$row5->upload_file, "upload_dir"=>$row5->upload_dir ];
                        }
                        $additions_list[] = $a_item;
                    }
                    $contract_list[] = ["contract_id"=>$contract_id, "name"=>$row4->name, "additions_count"=>$additions_count, "longtime_contract_is_signed"=>$longtime_contract_is_signed, "longtime_contract"=>$longtime_contract, "date_contract"=>$date_contract, "legacy_contract"=>$legacy_contract,  "upload_file"=>$upload_file, "upload_dir"=>$upload_dir, "additions_list"=>$additions_list ];
            }
          }

          $completed_a = [];
          $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_contract`, `upload_file`, `upload_dir`  FROM  `a_counterparty_contract` WHERE `contract_id`=0 AND `counterparty_id`=?  ');
          $stmt3->execute([$row->counterparty_id ]);
          if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                $date_contract = $row3->date_contract;
                $upload_file = $row3->upload_file;
                $upload_dir = $row3->upload_dir;
                $completed_a = ["contract_id"=>0, "name"=>'Акт выполненных работ', "additions_count"=>0, "longtime_contract_is_signed"=>1, "longtime_contract"=>0, "date_contract"=>$date_contract,  "upload_file"=>$upload_file, "upload_dir"=>$upload_dir, "additions_list"=>[] ];
          }
        }
        else {
            $completed_a = [];
    	    $stmt2 = $dbh->prepare('SELECT DISTINCT  `performer_id`  FROM `a_order_users` LEFT JOIN `a_order_course`  USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE `a_order_course`.`course_id`>0 AND  `a_order_users`.`order_id`=?  ');
            $stmt2->execute([ $row->order_id ]);
    	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
    	        $performer = 0;
    	        if($row2->performer_id >0 )
            	        $performer = 1;

                $date_contract = '';
                $legacy_contract = '';
                $upload_file = '';
                $upload_dir = '';
                $contract_id = 0;
                $name = '';

                $stmt4 = $dbh->prepare('SELECT DISTINCT  `contract_id`,  `name`, `template_id`  FROM   `a_contract`   WHERE `type`=1 AND `performer`=?   ');
                $stmt4->execute([ $performer ]);
    	        if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {  
                    $contract_id = $row4->contract_id;
                    $name = $row4->name;
                }

                $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_contract`, `legacy`, `upload_file`, `upload_dir`  FROM  `a_counterparty_contract` WHERE `contract_id`=? AND `counterparty_id`=1 AND `addition`<0 AND `order_id`=? ');
                $stmt3->execute([$contract_id,  $row->order_id ]);
                if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                        $date_contract = $row3->date_contract;
                        $legacy_contract = $row3->legacy;
                        $upload_file = $row3->upload_file;
                        $upload_dir = $row3->upload_dir;
                }
                $contract_list[] = ["contract_id"=>$contract_id, "name"=>$name, "additions_count"=>0,  "longtime_contract"=>0, "longtime_contract_is_signed"=>0, "date_contract"=>$date_contract, "legacy_contract"=>$legacy_contract,  "upload_file"=>$upload_file, "upload_dir"=>$upload_dir,  "additions_list"=>[[], [], []]  ];
	    }
            
        }

        if($course!='')
	        $stmt0 = $dbh->query('SELECT count(DISTINCT `order_id` )  as `count`   FROM `a_order`  LEFT JOIN `a_order_users` USING(`order_id`)  LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`)  WHERE 1  '.$addsearch);
	    else
	        $stmt0 = $dbh->query('SELECT count(DISTINCT `order_id` )  as `count`   FROM `a_order` WHERE 1  '.$addsearch);
        if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	        $members = $row0->count;
        }
        $num_pages = intval(($members+0.5)/$page_size+1);
        if( $page > $num_pages ){
	        $page = $num_pages;
        }

        $list[] =  ["order_id"=>$row->order_id,  "date_order"=>$row->date_order,  "number"=>$row->number, "name_order"=>substr($row->date_order, 2).'/'.$row->number,  "date_completed"=>$row->date_completed, "status_id"=>$row->status_id, "activity"=>$activity, "activity2"=>$activity2, "status_name"=>$status_name, "action"=>$action, "new_status"=>$new_status, "action2"=>$action2, "new_status2"=>$new_status2, "counterparty_id"=>$row->counterparty_id, "counterparty_name"=>$counterparty_name,  "count"=>$order_count,  "payment_receipt"=>$row->payment_receipt, "payment_receipt_date"=>$row->payment_receipt_date, "invoice"=>$row->invoice, "upload_file"=>$row->upload_file, "postpaid"=>$row->postpaid,   "groups_count"=>$groups_count, "lstream_count"=>$lstream_count,  "contract_list"=>$contract_list, "completed_a"=>$completed_a,  "snils_check_sum"=>$check_sum  ];
    }

    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$list,  "counterparty_id"=>$counterparty_id, "numPages"=>$num_pages, "page"=>$page  ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function order_object($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh, $dbh_a;


    $rc =  ["order_id"=>0,  "date_begin"=>''  ];
    if($order_id >0 ) {
	    $stmt = $dbh->prepare('SELECT `order_id`, `date_order`, `number`, `status_id`, `counterparty_id`, `date_begin`, `date_end`, `date_completed`,  `payer_id`, `refund_check`, `refund`, `account_id`, `payment_receipt`, `payment_receipt_date`, `invoice`, `upload_file`   FROM `a_order`   WHERE `order_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    	    //$stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM  `a_order_users`   WHERE `order_id`=? AND `course_id`=0 ');
                $stmt2 = $dbh->prepare('SELECT count(DISTINCT `user_counterparty_id`) as `count` FROM  `a_order_users`   WHERE `order_id`=?  ');
	        $stmt2->execute([$row->order_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $items_count = $row2->count;
	        }
	        
	        $secondary_order = [];
	        $secondary_order_id = 0;
	        if($row->order_id>0){
        	    $stmt3 = $dbh->prepare('SELECT `secondary_order_id`, `a_order`.`date_order`, `a_order`.`number`  FROM  `a_order_cash` LEFT JOIN `a_order` ON  `secondary_order_id`=`a_order`.`order_id`   WHERE `a_order_cash`.`order_id`=? ');
	            $stmt3->execute([$row->order_id]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
	                $secondary_order_id = $row3->secondary_order_id;
	                $secondary_order = ["date_order"=>$row3->date_order,  "number"=>$row3->number ];
	            }
	        }

	        $account_name = '';
	        if($row->account_id>0){
        	    $stmt4 = $dbh_a->prepare("SELECT  `fullname`, `login`, `email` FROM `a_account`  WHERE `account_id`=? ");
	            $stmt4->execute([$row->account_id]);
                    if($row4 = $stmt4->fetch(PDO::FETCH_OBJ)) {  
                        $account_name = $row4->fullname . '(' .$row4->email. ')' ; 
                    }    
	        }
                $money = counterparty_balance($row->counterparty_id, $row->order_id);    
	        //$rc =  ["order_id"=>$row->order_id,  "date_order"=>$row->date_order,  "number"=>$row->number,   "status_id"=>$row->status_id, "counterparty_id"=>$row->counterparty_id, "date_begin"=>$row->date_begin, "date_end"=>$row->date_end, "date_completed"=>$row->date_completed,    "payer_id"=>$row->payer_id, "payment_receipt"=>$row->payment_receipt, "payment_receipt_date"=>$row->payment_receipt_date, "refund_check"=>$row->refund_check, "refund"=>$row->refund,  "account_name"=>$account_name, "balance"=>$money, "items_count"=>$items_count, "secondary_order_id"=>$secondary_order_id, "secondary_order"=>$secondary_order, "invoice"=>$row->invoice, "upload_file"=>$row->upload_file   ];
	        $rc =  ["order_id"=>$row->order_id,  "date_order"=>$row->date_order,  "number"=>$row->number,   "status_id"=>$row->status_id, "counterparty_id"=>$row->counterparty_id, "date_begin"=>$row->date_begin, "date_end"=>$row->date_end, "date_completed"=>$row->date_completed,    "payer_id"=>$row->payer_id, "payment_receipt"=>$row->payment_receipt, "payment_receipt_date"=>$row->payment_receipt_date, "refund_check"=>$row->refund_check, "refund"=>$row->refund,   "balance"=>$money, "items_count"=>$items_count, "secondary_order_id"=>$secondary_order_id, "secondary_order"=>$secondary_order, "invoice"=>$row->invoice, "upload_file"=>$row->upload_file,  "account_name"=>$account_name,   ];
	    }
    }
    
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  user_course($order_id, $user_check, $check_id, $check_item_id, $course_id,  $variation, $group_number) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $user_check_arr = explode(',', $user_check); 
    $check_id_arr = explode(',', $check_id); 
    $check_item_id_arr = explode(',', $check_item_id); 
    $k = 0;
    $form_of_study = 0;

    if( $group_number == 1 )
             $group_number = 0;

    if($order_id >0 && $course_id>0 ) {
        $main_module = 0; 
        $stmt = $dbh->prepare('SELECT `a_course`.`category_id`, `modules`, `form_of_study`   FROM `a_course` LEFT JOIN  `a_course_category` USING(`category_id`)   WHERE `course_id`=?');
        $stmt->execute([$course_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $form_of_study = $row->form_of_study;
	        if( $row->modules > 0) {
	            $stmt2 = $dbh->prepare("SELECT `course_id` FROM `a_course`  WHERE `category_id`=? AND `main_module`='true'");
	            $stmt2->execute([$row->category_id]);
	            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	               $main_module = $row2->course_id;
	            }
	        }
        }

        $counterparty_id = 0;
        $stmt = $dbh->prepare('SELECT `counterparty_id`   FROM `a_order`   WHERE `order_id`=?');
        $stmt->execute([$order_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
             $counterparty_id =  $row->counterparty_id;
        }

        for($i=0; $i<count($check_item_id_arr);  $i++) {
            if($user_check_arr[$i] == 'true' ) {
                $user_lock = 1;
                $stmt0 = $dbh->prepare('SELECT `user_lock`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   WHERE `order_id`=? AND  `item_id`=? ');
                $stmt0->execute([$order_id, intval($check_item_id_arr[$i]) ]);
                if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	                 $user_lock = $row0->user_lock;
                }
                if($user_lock>0)
	                    continue;

                if($main_module >0){
                     if( intval($check_item_id_arr[$i]) > 0 ){
                            $stmt = $dbh->prepare('DELETE FROM  `a_order_course` WHERE `item_id`=? AND `course_id`=? ');
                            $stmt->execute([ intval($check_item_id_arr[$i]), $main_module ]);
                     }
                     //$stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`, `rank_of_profession`, `variation`, `group_number`,  `form_of_study` ) VALUES ( ?, ?, ?, ?, ?, ? )');
                     //$stmt->execute([intval($check_item_id_arr[$i]), $main_module, $rank_of_profession, $variation, $group_number, $form_of_study  ]);
                     $stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`,  `variation`, `group_number`,  `form_of_study` ) VALUES ( ?, ?, ?, ?, ?  )');
                     $stmt->execute([intval($check_item_id_arr[$i]), $main_module,  $variation, $group_number, $form_of_study  ]);
                }
                if( intval($check_item_id_arr[$i]) > 0 ){
                       $stmt = $dbh->prepare('DELETE FROM  `a_order_course` WHERE `item_id`=? AND `course_id`=? ');
                       $stmt->execute([ intval($check_item_id_arr[$i]), $course_id ]);
                }
                //$stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`, `rank_of_profession`, `variation`, `group_number`, `form_of_study` ) VALUES ( ?, ?, ?, ?, ?, ? )');
                //$stmt->execute([intval($check_item_id_arr[$i]) , $course_id, $rank_of_profession, $variation, $group_number, $form_of_study ]);
                $stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`,  `variation`, `group_number`, `form_of_study` ) VALUES ( ?, ?, ?, ?, ?  )');
                $stmt->execute([intval($check_item_id_arr[$i]), $course_id,  $variation, $group_number, $form_of_study ]);

                $group_id = 0;
                $stmt = $dbh->prepare('SELECT `group_id`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? AND `group_number`=? ');
                $stmt->execute([$order_id, $course_id, $group_number]);
                if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                      $group_id =  $row->group_id;
                }
                if($group_id==0){
                     $stmt = $dbh->prepare('INSERT  INTO  `a_order_group`(`order_id`,  `course_id`, `group_number` )  VALUES ( ?, ?, ?  )');
                     $stmt->execute([$order_id, $course_id, $group_number ]);
                }

                $k++;
            }
        }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course",  "result"=>$k ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function  user_course_del($item_id,  $course_id, $group_number ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    /*if($order_id >0 && $user_id>0 && $course_id>0 ) {
        $stmt = $dbh->prepare('DELETE FROM  `a_order_users`  WHERE `order_id`=? AND `user_id`=? AND `course_id`=? ');
        $stmt->execute([$order_id, $user_id, $course_id ]);
    }*/

    $stmt = $dbh->prepare('SELECT `order_id` FROM  `a_order_users`   WHERE `item_id`=? ');
    $stmt->execute([$item_id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
          $order_id =  $row->order_id;
    }

    if($item_id>0 && $course_id>0 ) {
        $stmt = $dbh->prepare('DELETE FROM  `a_order_course` WHERE `item_id`=? AND `course_id`=?  AND `group_number`=? ');
        $stmt->execute([ $item_id, $course_id, $group_number ]);
    }

    $group_count = 0;
    $stmt = $dbh->prepare('SELECT count(*) as `count`   FROM `a_order_course` LEFT JOIN  `a_order_users` USING(`item_id`)   WHERE `order_id`=? AND `course_id`=? AND `group_number`=? ');
    $stmt->execute([$order_id, $course_id, $group_number]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
          $group_count =  $row->count;
    }
    if($group_count==0 && $order_id>0 && $course_id>0 ){
         $stmt = $dbh->prepare('DELETE FROM  `a_order_group` WHERE `order_id`=? AND  `course_id`=? AND `group_number`=?  ');
         $stmt->execute([$order_id, $course_id, $group_number ]);
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course_del",  "result"=>$order_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





function order_save($order_id,  $date_begin, $date_end, $date_completed,    $payer_id, $balance_pay, $refund_check,  $refund, $payment_receipt, $payment_receipt_date, $invoice ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($date_begin == '')
         $date_begin = date('Y-m-d');

    if($date_end == '')
         $date_end = '1000-01-01';


    if($order_id >0 ) {
        $stmt = $dbh->prepare('UPDATE `a_order`  SET `date_begin`=?, `date_end`=?, `date_completed`=?,   `payer_id`=?,   `payment_receipt`=?, `payment_receipt_date`=?, `invoice`=?  WHERE `order_id`=?');
        $stmt->execute([ $date_begin, $date_end, $date_completed,   $payer_id, $payment_receipt, $payment_receipt_date, $invoice,   $order_id ]);
        
	    /*$stmt2 = $dbh->prepare('SELECT  `a_cohort`.`cohort_id` FROM `a_order_group` LEFT JOIN `a_cohort` USING(`cohort_id`)   WHERE `order_id`=? ');
	    $stmt2->execute([$order_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $stmt3 = $dbh->prepare('UPDATE `a_cohort`  SET  `status_id`=?  WHERE `cohort_id`=?');
            $stmt3->execute([ $status, $row2->cohort_id ]);
	    } */       
	    if($balance_pay == 'true') {
        	    $stmt2 = $dbh->prepare('SELECT counterparty_id  FROM `a_order`  WHERE `order_id`=? ' );
	            $stmt2->execute([ $order_id ]);
	            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	                $counterparty_id = $row2->counterparty_id;
	            }
	            use_balance($order_id, $counterparty_id);
	    }
	    if($refund_check == 'true' || `refund_check`== 'false') {
                 $stmt2 = $dbh->prepare('UPDATE `a_order`  SET `refund_check`=?, `refund`=? WHERE `order_id`=?');
                 $stmt2->execute([ $refund_check,  $refund, $order_id ]);
	    }     
            $stmt2 = $dbh->prepare('UPDATE `a_order`  SET `date_ct`=now()  WHERE `order_id`=?');
            $stmt2->execute([ $order_id ]);
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$order_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function order_update_status($order_id, $status) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 ) {
        $new_status = 0;
        $is_certificate = 0;
        $date_completed = '';
        $old_status = 0;
        $stmt = $dbh->prepare('SELECT `new_status`, `new_status2`,  `new_status_postpaid` , `a_status`.`certificate`, `date_completed`, `a_order`.`status_id`, `a_order`.`postpaid`  FROM `a_order` LEFT JOIN `a_status` USING(`status_id`)  WHERE `order_id`=?');
        $stmt->execute([ $order_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($status < 0)
                $new_status = $row->new_status2;
            else if($status == 0){
                if( $row->postpaid >0 && $row->new_status_postpaid >0 ){
                        $new_status = $row->new_status_postpaid;
                } 
                else {
                        $new_status = $row->new_status;
                }
            }
            else 
                $new_status = $status;

            $is_certificate = $row->certificate;
            $date_completed = $row->date_completed;
            $old_status = $row->status_id;
        }
        
        $secondary_order = 0;
        $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_order_cash`  WHERE `secondary_order_id`=?');
        $stmt->execute([ $order_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $secondary_order = $row->secondary_order_id;
        }
        if( $secondary_order>0 && $new_status <= 1 )
                $new_status = 2;
	    
        if($new_status > 0){
            $stmt = $dbh->prepare('UPDATE `a_order`  SET  `status_id`=?  WHERE `order_id`=?');
            $stmt->execute([$new_status, $order_id ]);

            $stmt2 = $dbh->prepare('SELECT DISTINCT `cohort_id` FROM `a_order_users` LEFT JOIN `a_order_course`  USING(`item_id`)   WHERE `order_id`=? AND `cohort_id`>0 ');
            $stmt2->execute([$order_id]);
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                $stmt3 = $dbh->prepare('UPDATE `a_cohort`  SET  `status_id`=?  WHERE `cohort_id`=?');
                $stmt3->execute([ $new_status, $row2->cohort_id ]);
           }
        }
        if($new_status == 1 || $new_status == 2){
               $stmt = $dbh->prepare('SELECT  `item_id`   FROM  `a_order_users`   WHERE `order_id`=?');
               $stmt->execute( [$order_id] );
               while($row = $stmt->fetch(PDO::FETCH_OBJ)) {

                   $stmt2 = $dbh->prepare('SELECT  `cohort_id`   FROM  `a_order_course`   WHERE `item_id`=?');
                   $stmt2->execute( [ $row->item_id ] );
                   while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                       $lstream_id_i = 0;
                       $stmt3 = $dbh->prepare('SELECT  `lstream_id`  FROM `a_cohort`   WHERE `cohort_id`=? ');
	               $stmt3->execute([$row_cohort_id]);
	               if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                           $lstream_id_i = $row3->cohort_id;
                       }
                       if($lstream_id_i >0 ){

                            $stmt3 = $dbh->prepare('SELECT count(*) as `count`  FROM `a_cohort`  WHERE  `lstream_id`=? ');
                            $stmt3->execute([$lstream_id_i]);
                            if($row3 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                                if($row3->count <= 1){
                                     $stmt4 = $dbh->prepare('DELETE FROM   `a_lstream`   WHERE  `lstream_id`=?  ');
                                     $stmt4->execute([$lstream_id_i]);
                                }
	                    }
	                }
                        $stmt3 = $dbh->prepare('UPDATE `a_cohort` SET `lstream_id`=0   WHERE `cohort_id`=? ');
                        $stmt3->execute([ $row2->cohort_id ]);

                        $stmt3 = $dbh->prepare('DELETE FROM  `a_cohort_scheduler`  WHERE `cohort_id`=?');
                        $stmt3->execute([ $row2->cohort_id   ]);

                        //$stmt3 = $dbh->prepare('UPDATE  `a_order_course` SET `cohort_id`=0  WHERE `item_id`=?');
                        //$stmt3->execute([  $row->item_id   ]);

                        //$stmt3 = $dbh->prepare('DELETE FROM  `a_cohort`  WHERE `cohort_id`=? ');
                        //$stmt3->execute([ $row2->cohort_id ]);
                   }
                }
        }


        if($new_status == 1){
	       $stmt2 = $dbh->prepare('DELETE FROM  `a_order_price` WHERE `order_id`=?  ');
	       $stmt2->execute([$order_id ]);
        }    

        if($new_status == 3 ){
            order_to_1c($order_id);
        }
        
        if($new_status == 3 && intval($secondary_order)==0 ){
            $stmt = $dbh->prepare('SELECT DISTINCT  `a_order_course`.`course_id`,  `counterparty_id`,  `a_course`.`price`, `a_course`.`main_module`    FROM `a_order`  LEFT JOIN `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)  USING(`order_id`) LEFT JOIN `a_course` USING(`course_id`)  WHERE `order_id`=? AND `a_order_course`.`course_id`>0  ');
	        $stmt->execute([$order_id]);
	        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $price = 0;
                    $main_module =  $row2->main_module;

                    $stmt2 = $dbh->prepare("SELECT  `price` FROM `a_order_discounts` WHERE `order_id`=?   AND `course_id`=? ");
                    $stmt2->execute([$order_id, $row->course_id]);
	                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                          $price =  $row2->price;
                    }
                    
                    if($price == 0) {
                        $stmt2 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
                        $stmt2->execute([$row->counterparty_id, $row->course_id]);
	                    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                              $price =  $row2->price;
	                    }
                     }
	        
                     if($price == 0)
	                    $price = $row->price;


                     $stmt2 = $dbh->prepare('DELETE FROM  `a_order_price` WHERE `order_id`=?  AND `course_id`=? ');
                     $stmt2->execute([$order_id,  $row->course_id ]);
//file_put_contents("lst.txt", print_r([$order_id,  $row->course_id, $price ], true) );

                     $stmt2 = $dbh->prepare('INSERT  INTO  `a_order_price`(`order_id`,  `course_id`, `price`) VALUES ( ?, ?, ? )');
                     $stmt2->execute([$order_id,  $row->course_id,  $price ]);
	   
	        }
        }

        if($new_status == 4){
            $stmt = $dbh->prepare('UPDATE `a_order`  SET  `postpaid`=1  WHERE `order_id`=?');
            $stmt->execute([ $order_id ]);
        }
        if($new_status == 1 || $new_status == 2 || $new_status == 3){
            $stmt = $dbh->prepare('UPDATE `a_order`  SET  `postpaid`=0  WHERE `order_id`=?');
            $stmt->execute([ $order_id ]);
        }

        if($new_status == 7){
            $date_end='1000-01-01'; 
            $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`cohort_id`, `lstream_id` FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_cohort` USING(`cohort_id`)   WHERE `order_id`=? ');
            $stmt2->execute([$order_id]);
            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                if($row2->lstream_id>0){
                     $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_begin`, `date_end`, `date_protocol` FROM `a_lstream`  WHERE `lstream_id`=? ');
                     $stmt3->execute([ $row2->lstream_id ]);
                     if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                           if( strcmp($date_end, $row3->date_end)<0)
                                  $date_end = $row3->date_end;
                     }
                }
            }
//file_put_contents("lst.txt", print_r([$date_end ], true) );
            if($date_end == '')
                  $date_end = '1000-01-01';
            $stmt = $dbh->prepare('UPDATE `a_order`  SET  `date_end`=?  WHERE `order_id`=?');
            $stmt->execute([$date_end,  $order_id ]);
        }


        if($new_status == 11){
            //if($date_completed == '' || $date_completed=='0000-00-00'  || $date_completed=='1000-01-01'){
                $date_end='1000-01-01'; 
                $date_protocol='1000-01-01'; 
                $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`cohort_id`, `lstream_id` FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_cohort` USING(`cohort_id`)   WHERE `order_id`=? ');
                $stmt2->execute([$order_id]);
                while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                  if($row2->lstream_id>0){
                     $date_protocol_i = '1000-01-01';
                     $cohort_id_i = $row2->cohort_id;
                     $stmt3 = $dbh->prepare('SELECT DISTINCT  `date_begin`, `date_end`, `date_protocol` FROM `a_lstream`  WHERE `lstream_id`=? ');
                     $stmt3->execute([ $row2->lstream_id ]);
                     if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                           if( strcmp($date_protocol, $row3->date_protocol)<0)
                                  $date_protocol = $row3->date_protocol;

                           if( strcmp($date_end, $row3->date_end)<0)
                                  $date_end = $row3->date_end;

                           $date_protocol_i = $row3->date_protocol;
                     }
                  } 
                }
                if( strcmp($date_end, $date_protocol)<0)
                        $date_end = $date_protocol;

            //}
            /*if($date_end == '')
                  $date_end = '1000-01-01';
            if($date_protocol == '')
                  $date_protocol = '1000-01-01';*/
            $stmt = $dbh->prepare('UPDATE `a_order`  SET `date_end`=?,  `date_completed`=?  WHERE `order_id`=?');
            $stmt->execute([$date_end, $date_protocol, $order_id ]);
          
        }
        if($new_status == 12){
                $date_protocol='1000-01-01'; 
                $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`cohort_id`, `lstream_id`, `a_course`.`category_id` FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_cohort` USING(`cohort_id`)    WHERE `order_id`=? ');
                $stmt2->execute([$order_id]);
                while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                  if($row2->lstream_id>0){
                     $date_protocol_i = '1000-01-01';
                     $cohort_id_i = $row2->cohort_id;
                     $stmt3 = $dbh->prepare('SELECT DISTINCT   `date_protocol` FROM `a_lstream`  WHERE `lstream_id`=? ');
                     $stmt3->execute([ $row2->lstream_id ]);
                     if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                           $date_protocol_i = $row3->date_protocol;
                     }

                     $list_documents_num_i = 1;
                     $stmt3 = $dbh->prepare('SELECT   `list_documents_num`  FROM `a_cohort` LEFT JOIN `a_lstream`  USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`)   WHERE `list_documents_num`>0  AND  `a_course`.`category_id`= ?  AND `a_lstream`.`date_protocol`=? ORDER BY `list_documents_num` ');
                     $stmt3->execute([ $row2->category_id,  $date_protocol_i ]);
                     while($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
                         if($row3->list_documents_num > $list_documents_num_i)
                              break;

                         $list_documents_num_i = $list_documents_num_i+1;
                     }
                     $stmt3 = $dbh->prepare('UPDATE  `a_cohort` SET `list_documents_num`=?  WHERE `cohort_id`=? ');
                     $stmt3->execute([ $list_documents_num_i, $cohort_id_i ]);

                     lib_create_users_report($row2->cohort_id);

                  } 
                }

            /*if($date_completed == '' || $date_completed=='0000-00-00'   || $date_completed=='1000-01-01'){
                $stmt = $dbh->prepare('UPDATE `a_order`  SET  `date_completed`=?  WHERE `order_id`=?');
                $stmt->execute([$date_protocol, $order_id ]);
            }*/
            completed_to_1c($order_id);
        }
        if($old__status >= 12 && $new_status < 12){
                $date_protocol='1000-01-01'; 
                $stmt2 = $dbh->prepare('SELECT DISTINCT `a_order_course`.`cohort_id`, `lstream_id`   FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_cohort` USING(`cohort_id`)    WHERE `order_id`=? ');
                $stmt2->execute([$order_id]);
                while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
                  if($row2->lstream_id>0){
                     $cohort_id_i = $row2->cohort_id;

                     $stmt3 = $dbh->prepare('UPDATE  `a_cohort` SET `list_documents_num`=?  WHERE `cohort_id`=? ');
                     $stmt3->execute([ 0, $cohort_id_i ]);

                     //lib_create_users_report($row2->cohort_id);
                  } 
                }

        }

        if($new_status == 16){
            cancelled_to_1c($order_id);
        }


        /*if($new_status == 12 || $new_status == 8){
            $stmt = $dbh->prepare('SELECT count(*) as `count` FROM `a_order_users`  WHERE `order_id`=? AND  `user_lock`>0  ');
            $stmt->execute([$order_id ]);
	        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	            if($row->count == 0){
                    $stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM `a_order_cash` WHERE `order_id`=? ');
                    $stmt2->execute([$order_id ]);
	                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	                    if($row2->count == 0){
                            $stmt2 = $dbh->prepare('INSERT INTO `a_order_cash`(`order_id`, `date_cash`, `cancellation`) VALUES ([?, now(), 0)');
                            $stmt2->execute([$order_id ]);
	                    }
	                }
                }
	        }
        }*/
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update_status",  "result"=>$new_status ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  order_sync_1c($order_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0  ) {
        $stmt = $dbh->prepare('SELECT  `status_id` FROM `a_order`  WHERE `order_id`=?');
        $stmt->execute([ $order_id ]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            if($row->status_id > 1 && $row->status_id < 12  && $row->status_id != 4  )
                    order_to_1c($order_id);

            if($row->status_id == 4 || $row->status_id == 12 )
                    completed_to_1c($order_id);
	    }
    }
}


function  order_balance($order_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    //$user_check_arr = explode(',', $user_check); 
    //$check_id_arr = explode(',', $check_id); 
    //$k = 0;
    $money = 0;
        
    if($order_id >0  ) {
        $stmt = $dbh->prepare('SELECT `course_id`, `user_counterparty_id`, `cohort_id`, `a_course`.`price`   FROM `a_order`  LEFT JOIN `a_order_users` USING(`order_id`)  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`)  WHERE `order_id`=? AND `user_lock`=1 AND `cohort_id`>0 ');
	    $stmt->execute([$order_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $price = 0;
            $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_price` WHERE `order_id`=?  AND `course_id`=? ");
            $stmt3->execute([$order_id, $row2->course_id]);
	        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $price =  $row3->price;
    	    }
            if($price == 0) {
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
                $stmt3->execute([$counterparty_id, $row2->course_id]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  $row3->price;
                }
            }
            if($price == 0) {
                  $price = $row->price;
            }
            $money = $money + intval($price);
	    }
    }
    return( $money );
}


function  counterparty_balance($counterparty_id , $order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $money = 0;
    if($counterparty_id >0  ) {
        $stmt = $dbh->prepare('SELECT DISTINCT  `course_id`, `user_counterparty_id`, `cohort_id`, `a_course`.`price`   FROM `a_order`  LEFT JOIN `a_order_users` USING(`order_id`) LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN `a_course` USING(`course_id`)  WHERE `counterparty_id`=? AND `user_lock`=1 AND `a_course`.`price`>0 AND `cohort_id`=0 AND (`refund_check`<>1 OR `a_order`.`order_id`=?) ');
        $stmt->execute([$counterparty_id, $order_id]);
        while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $price = 0;
            $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_price` WHERE `order_id`=?  AND `course_id`=? ");
            $stmt3->execute([$order_id, $row->course_id]);
	        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $price =  $row3->price;
    	    }
            if($price == 0) {
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
                $stmt3->execute([$counterparty_id, $row2->course_id]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  $row3->price;
	            }
            }
            if($price == 0) {
                $price = $row->price;
            }    
            $money = $money + intval($price);
	    }
    }
    return( $money );
}

function  use_balance($order_id, $counterparty_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

        
    if($order_id >0  && $counterparty_id>0) {
        $stmt2 = $dbh->prepare('DELETE FROM  `a_order_users` WHERE `order_id`=?  ');
        $stmt2->execute([ $order_id ]);

        $stmt = $dbh->prepare('SELECT  `user_counterparty_id`, `course_id`,  `order_id`  FROM  `a_order`  LEFT JOIN `a_order_users` USING(`order_id`)  WHERE `counterparty_id`=?  AND `user_lock`=1 AND `cohort_id`=0 ');
	    $stmt->execute([$counterparty_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
//file_put_contents("lst1.txt", json_encode([$order_id,  $row->user_id, $row->course_id,  $row->rank_of_profession ], JSON_UNESCAPED_UNICODE));
            $stmt2 = $dbh->prepare('INSERT  INTO  `a_order_users`(`order_id`, `user_id`, `course_id` ) VALUES ( ?, ?, ? )');
            $stmt2->execute([$order_id,  $row->user_id, $row->course_id ]);

            $stmt2 = $dbh->prepare('SELECT  `price` FROM `a_order_price` WHERE  `order_id`=?  AND  `course_id`=? ');
            $stmt2->execute([$row->order_id, $row->course_id ]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $stmt3 = $dbh->prepare('INSERT  INTO `a_order_price`(`order_id`, `course_id`, `price` ) VALUES ( ?, ?, ? )');
                $stmt3->execute([ $order_id, $row->course_id, $row2->price ]);
	        }
	    }
        $stmt = $dbh->prepare('UPDATE `a_order`  SET  `status_id`=?  WHERE `order_id`=?');
        $stmt->execute([2, $order_id ]);

        $stmt = $dbh->prepare('SELECT DISTINCT `order_id`  FROM  `a_order`  LEFT JOIN `a_order_users` USING(`order_id`)  WHERE `counterparty_id`=?  AND `user_lock`=1 AND `cohort_id`=0 ');
	    $stmt->execute([$counterparty_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        if( $row->order_id >0 ) {
                $stmt2 = $dbh->prepare('INSERT  INTO `a_order_cash`(`order_id`,  `secondary_order_id` ) VALUES ( ?, ? )');
                $stmt2->execute([ $row->order_id, $order_id ]);
	        }
	    }
    }
}



function  user_course_check( $user_check, $check_id, $check_item_id, $course_id,  $variation) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $user_check_arr = explode(',', $user_check); 
    $check_id_arr = explode(',', $check_id); 
    $check_item_id_arr = explode(',', $check_item_id); 
    $k = 0;
    $form_of_study = 0;

    if($course_id>0 ) {
        $main_module = 0; 
        $stmt = $dbh->prepare('SELECT `a_course`.`category_id`, `modules`, `form_of_study`   FROM `a_course` LEFT JOIN  `a_course_category` USING(`category_id`)   WHERE `course_id`=?');
        $stmt->execute([$course_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $form_of_study = $row->form_of_study;
	        if( $row->modules > 0) {
	            $stmt2 = $dbh->prepare("SELECT `course_id` FROM `a_course`  WHERE `category_id`=? AND `main_module`='true'");
	            $stmt2->execute([$row->category_id]);
	            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	               $main_module = $row2->course_id;
	            }
	        }
        }

        $counterparty_id = 0;
        $stmt = $dbh->prepare('SELECT `counterparty_id`   FROM `a_order`   WHERE `order_id`=?');
        $stmt->execute([$order_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
             $counterparty_id =  $row->counterparty_id;
        }


//file_put_contents("lst1.txt", json_encode($check_item_id_arr, JSON_UNESCAPED_UNICODE));
//file_put_contents("lst2.txt", json_encode($user_check_arr, JSON_UNESCAPED_UNICODE));

        $rc = [];
        for($i=0; $i<count($check_item_id_arr);  $i++) {
            $rc[$i] = 0; 
            if($user_check_arr[$i] == 'true' ) {
                $user_id = 0;
                $user_lock = 1;
                //$stmt0 = $dbh->prepare('SELECT `user_id`,  `user_lock`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   WHERE `order_id`=? AND  `item_id`=? ');
                $stmt0 = $dbh->prepare('SELECT `a_user_counterparty`.`user_id`,  `user_lock`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  WHERE  `a_order_users`.`item_id`=? ');
                $stmt0->execute([intval($check_item_id_arr[$i]) ]);
                if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	                 $user_id = $row0->user_id;
	                 $user_lock = $row0->user_lock;
                }
                if($user_lock>0 || $user_id==0 )
	                    continue;

//file_put_contents("lst3.txt", json_encode([$user_id], JSON_UNESCAPED_UNICODE));
                if($main_module >0){
                     //$stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`,  `variation`, `group_number`,  `form_of_study` ) VALUES ( ?, ?, ?, ?, ?  )');
                     //$stmt->execute([intval($check_item_id_arr[$i]), $main_module,  $variation, $group_number, $form_of_study  ]);
                }
                //$stmt = $dbh->prepare('SELECT `item_id` FROM  `a_order_course` WHERE  `course_id`=?  `variation`  `form_of_study` ');
                //$stmt = $dbh->prepare('SELECT count(*) as `count`  FROM  `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`) WHERE  `course_id`=?   ');

                $rc[$i] = 0; 
                $stmt = $dbh->prepare('SELECT `order_id` FROM  `a_order_users` LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`) LEFT JOIN `a_order_course` USING(`item_id`)  WHERE  `course_id`=?  AND `a_user_counterparty`.`user_id`=?  ');
                $stmt->execute([$course_id, $user_id ]);
                if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                    $rc[$i] = $row->order_id; 
                }
                $k++;
            }
        }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"user_course_check",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}







function lib_create_users_report($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $is_short_certificate_num;

    $course_id = 0;
    $course = '';
    $date_protocol = '';
    $protocol_num = '';
    $delta = 0;
    if($cohort_id >0  ) {
	    $stmt = $dbh->prepare('SELECT DISTINCT   `a_cohort`.`course_id`, `a_course`.`name`, `a_cohort`.`lstream_id`, delta2, `protocol_num`, `a_cohort`.`date_num`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`,  MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`   FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)    WHERE `cohort_id`=?');
	    $stmt->execute([$cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $lstream_id = $row->lstream_id;
	        $course_id = $row->course_id;
	        $course = $row->name;
	        $delta = $row->delta2;
                if($is_short_certificate_num)
                          $protocol_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);
                else
                          $protocol_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);

	        //$protocol_num =  $row->protocol_num;
	    }
	    $stmt = $dbh->prepare('SELECT   `date_protocol`,  `date_protocol` + INTERVAL ?  YEAR as `date_finish`   FROM `a_lstream`  WHERE `lstream_id` =?');
	    $stmt->execute([$delta, $lstream_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $date_protocol = $row->date_protocol;
	        $date_finish = $row->date_finish;
	    }
    }
    if($cohort_id >0 &&  $course_id >0) { 
	    $stmt2 = $dbh->prepare('SELECT `user_id`, `a_order_users`.`certificate_num`, `order_id`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN  `a_user_counterparty`  USING(`user_counterparty_id`)   WHERE `cohort_id`=?');
	    $stmt2->execute([$cohort_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	        $user_id = $row2->user_id;
	        $order_id = $row2->order_id;
	        $certificate_num = $protocol_num .'-'. $row2->certificate_num;  
                $count = 0;
	        $stmt3 = $dbh->prepare('SELECT count(*) as `count`  FROM `a_reports`   WHERE `course_id`=? AND `user_id`=? AND `a_date`=?  ' );
	        $stmt3->execute([$course_id, $user_id, $date_protocol ]);
	        if ($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
		          $count =  $row3->count;
	        }
	        if($count == 0) {
	            $stmt3 = $dbh->prepare('INSERT INTO `a_reports`(`user_id`, `course`, `course_id`, `cohort_id`, `order_id`,  `certificate`, `protocol_num`, `a_date`, `date_finish`)  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)  ' );
	            $stmt3->execute([$user_id, $course, $course_id, $cohort_id, $order_id,  $certificate_num, $protocol_num,  $date_protocol, $date_finish ]);
	       } 
	    }
    }

}




function order_create( $counterparty_id, $date_order,  $date_begin, $date_end, $payer_id, $balance_pay, $invoice ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $session_account_id;

    $rc_status = 0; 
    $order_id = 0;
    $error = '';
    
    if($date_begin == '')
         $date_begin = date('Y-m-d');

    if($date_end == '')
         $date_end = '1000-01-01';
    
    $number = 1;
	//$stmt = $dbh->prepare('SELECT `number`  FROM `a_order`   WHERE `counterparty_id`=? ORDER BY `number` DESC' );
	$stmt = $dbh->prepare('SELECT `number`  FROM `a_order`   WHERE `date_order`=? ORDER BY `number` DESC' );
	$stmt->execute([$date_order]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $number = intval($row->number) + 1;
	}

    $order_name = substr($date_order, -8) . '/' . $number;
    $order_id = 0;
    $stmt = $dbh->prepare('INSERT INTO `a_order`( `counterparty_id`, `date_order`, `number`, `order_name`, `date_begin`, `date_end`, `status_id`,  `payer_id`, `invoice`, `account_id` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$counterparty_id, $date_order, $number, $order_name, $date_begin, $date_end, 1,   $payer_id, $invoice, $session_account_id ]);
    $order_id = intval($dbh->lastInsertId());

    if($order_id == 0) {
        $error = 'Невозможно создать заявку';
        $rc_status = 1;
    }

    if($balance_pay == 'true' && $order_id>0 )
            use_balance($order_id, $counterparty_id );

    $result = ["status"=>$rc_status, "error"=>$error, "role"=>$session_role, "action"=>"insert",  "result"=>$order_id  ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function  consulting_add($order_id,  $course_id, $quantum ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 && $course_id>0 && $quantum>0) {
            $counterparty_id = 0;
            $stmt = $dbh->prepare('SELECT `counterparty_id`   FROM `a_order`   WHERE `order_id`=?');
            $stmt->execute([$order_id]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	                $counterparty_id =  $row->counterparty_id;
            }

            $item_id = 0;
            $stmt = $dbh->prepare('SELECT `item_id`  FROM  `a_order_users`  WHERE `order_id`=? AND  `user_counterparty_id`=0');
            $stmt->execute([$order_id]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	                $item_id =  $row->item_id;
            }
            if($item_id == 0){
	        $stmt = $dbh->prepare('INSERT  INTO  `a_order_users`(`order_id`, `user_counterparty_id` ) VALUES ( ?, 0 )');
	        $stmt->execute([$order_id  ]);
                $item_id = $dbh->lastInsertId(); 
            }
        
            $stmt = $dbh->prepare('DELETE FROM  `a_order_course`  WHERE `item_id`=? AND `course_id`=?  ');
            $stmt->execute([ $item_id, $course_id ]);

            $stmt = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`, `quantum`) VALUES ( ?, ?, ? )');
            $stmt->execute([ $item_id,   $course_id, $quantum ]);
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"consulting_add",  "result"=>$quantum ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  consulting_del($order_id,  $course_id ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 && $course_id>0) {
        $item_id = 0;
        $stmt = $dbh->prepare('SELECT `item_id`  FROM  `a_order_users`  WHERE `order_id`=? AND  `user_counterparty_id`=0');
        $stmt->execute([$order_id]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $item_id =  $row->item_id;
        }
        if($item_id > 0){
            $stmt2 = $dbh->prepare('DELETE FROM  `a_order_course`  WHERE `item_id`=? AND `course_id`=?  ');
            $stmt2->execute([ $item_id, $course_id ]);

            $stmt2 = $dbh->prepare('DELETE FROM  `a_order_users`  WHERE `item_id`=? AND  `order_id`=?  AND  `user_counterparty_id`=0 ');
            $stmt2->execute([$item_id, $order_id]);
        }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"consulting_del",  "result"=>1 ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



/*
function cohort_save($group_id, $name,  $course_id, $category, $date_g ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc =  [];
    if($group_id >0 ) {
        $stmt = $dbh->prepare('INSERT INTO `a_cohort`(`group_id`, `name`,  `course_id`, `category`, `date_g` ) VALUES ( ?, ?, ?, ?, ? )');
	$stmt->execute([$group_id, $name,  $course_id, $category, $date_g ]);
	$cohort_id = $dbh->lastInsertId(); 
        $rc[] =  ["cohort_id"=>$cohort_id  ];
    }


    if($cohort_id>0)
	$result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "cohortInfo"=>$rc ];
    else
        $result = ["status"=>"1", "error"=>'Ошибка записи в базу данных', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "groupInfo"=>$rc ];

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

}


function cohort_del($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($cohort_id >0 ) {
	$stmt = $dbh->prepare('DELETE FROM  `a_cohort`   WHERE `cohort_id`=?  ');
	$stmt->execute([$cohort_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"cohort_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

*/



function contracts($counterparty_id, $order_id, $longtime_contract_c) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $a_contract_id = [];
    $stmt = $dbh->prepare("SELECT DISTINCT `performer_id`, `contract_id`, `contract2_id`  FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)   LEFT JOIN  `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)  WHERE `order_id`=?  AND `a_course`.`course_id`>0 AND `user_lock`=0 ");
    $stmt->execute( [ $order_id ] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        if($row->performer_id == 0 && $row->contract_id !='' && $row->contract_id != NULL  && array_search(intval($row->contract_id), $a_contract_id) === false  ) {
            $a_contract_id[] =  intval($row->contract_id);
        }
        if($row->performer_id > 0 && $row->contract2_id!='' && $row->contract2_id!= NULL  && array_search(intval($row->contract2_id), $a_contract_id) === false ) {
            $a_contract_id[] = intval($row->contract2_id);
        }
    }

    $contract_number = '';
    $legacy_count  = 0;
    foreach($a_contract_id as $v_contract) {
        $stmt2 = $dbh->prepare("SELECT `prefix`, `name`, `longtime_contract` FROM `a_contract` WHERE `contract_id`=? AND  `type`=0 ");
        $stmt2->execute( [ intval($v_contract) ] );
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            if( intval($row2->longtime_contract) >0 && $longtime_contract_c == 'true') {
                $stmt3 = $dbh->prepare("SELECT `legacy`, DATE_FORMAT(`date_contract`, '%d.%m.%Y') as `date_contract`  FROM `a_counterparty_contract`  WHERE `contract_id`=? AND  `counterparty_id`=? ");
                $stmt3->execute( [ intval($v_contract), $counterparty_id ] );
                if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) { 
                    if($row3->legacy != '') {
                        if($legacy_count == 0)
                                $contract_number = $contract_number  . $row3->legacy . ' от ' . $row3->date_contract . ', ';  
                        $legacy_count  = 1;
                    }
                    else {
                        $contract_number = $contract_number  . $row2->prefix . $row->counterparty_id . ' от ' . $row3->date_contract . ', ';
                    }    
                } 
                else
                    $contract_number = $contract_number  .  $row2->prefix . $row->counterparty_id . ', ';
            }
            else
                $contract_number = $contract_number  .  $row2->prefix . $row->counterparty_id. '-' . $row->order_id . ' от ' . $row->date_order_ru . ', ';
        }    
    }
    $contract_number = mb_substr($contract_number, 0, -2);
    return $contract_number;
}




/*function contract_save($counterparty_id,  $date_contract, $a_contract_id  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($counterparty_id >0 ) {
        for($i=0; $i<count($a_contract_id); $i++) {
            $contract_count = 0;
            $stmt = $dbh->prepare("SELECT count(*) as `count`  FROM  `a_counterparty_contract`   WHERE `counterparty_id`=? AND `contract_id`=?   ");
            $stmt->execute([ $counterparty_id, $a_contract_id[$i] ]);
            if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                $contract_count = $row->count;
            }

            if($contract_count > 0){
                if($date_contract[$i]!= '' && $date_contract[$i]!='0000-00-00'){
                        $stmt = $dbh->prepare('UPDATE  `a_counterparty_contract` SET `date_contract`=?,   WHERE `counterparty_id`=? AND  `contract_id`=? ');
                        $stmt->execute([$date_contract[$i],   $counterparty_id, $a_contract_id[$i] ]);
                }

                if($date_contract[$i]== '' || $date_contract[$i]== '0000-00-00'){
	            $stmt = $dbh->prepare('SELECT `upload_file`, `upload_dir`  FROM `a_counterparty_contract`  WHERE `counterparty_id`=? AND `contract_id`=?');
		    $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);
                    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
                        if($row->upload_file != '')
                             unlink($path . $row->upload_dir . $row->upload_file);
                        }
        	        $stmt = $dbh->prepare("UPDATE `a_counterparty_contract` SET `upload_file`='', `upload_dir`=''  WHERE `counterparty_id`=? AND `contract_id`=?");
        	        $stmt->execute([$counterparty_id, $a_contract_id[$i] ]);
                }
                
            }
            else if($date_contract[$i] != '0000-00-00' && $date_contract[$i]!=''){
                $stmt = $dbh->prepare('INSERT INTO `a_counterparty_contract`(`counterparty_id`, `contract_id`, `date_contract`  ) VALUES( ?, ?, ? ) ');
                $stmt->execute([ $counterparty_id, $a_contract_id[$i], $date_contract[$i] ]);
            }
        }
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$i ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}*/


function  protocol_number_save($order_id, $course_id, $group_number,  $custom_protocol_number ){
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($group_number == 1)
          $group_number = 0;

    $group_id = 0;
    $stmt = $dbh->prepare('SELECT `group_id`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? AND  `group_number`=? ');
    $stmt->execute([$order_id, $course_id, $group_number]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
          $group_id =  $row->group_id;
    }
    if($group_id > 0){
         $stmt = $dbh->prepare('UPDATE `a_order_group` SET `custom_protocol_num`=?    WHERE `order_id`=? AND `course_id`=? AND `group_number`=? ');
         $stmt->execute([ $custom_protocol_number,  $order_id, $course_id, $group_number ]);
    }
    else{
         $stmt = $dbh->prepare('INSERT  INTO  `a_order_group`(`order_id`,  `course_id`, `group_number`,  `custom_protocol_num`)  VALUES ( ?, ?, ?, ?  )');
         $stmt->execute([$order_id, $course_id, $group_number, $custom_protocol_number ]);
         $group_id = intval($dbh->lastInsertId());
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"protocol_number_save",  "result"=>$group_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  cert_number_save($item_id, $course_id, $blank_number, $custom_cert_number ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $stmt = $dbh->prepare("UPDATE  `a_order_course`  SET `blank_number`=?, `custom_cert_number`=?  WHERE `item_id`=? AND `course_id`=? ");
    $stmt->execute([$blank_number, $custom_cert_number,  $item_id, $course_id ]);

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"cert_number_save",  "result"=>$item_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function payment_receipt($order_id, $payment_receipt,  $payment_receipt_date ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($order_id >0 && $payment_receipt>0 && $payment_receipt_date!=null) {
        $stmt = $dbh->prepare("UPDATE `a_order`  SET `payment_receipt`=?, `payment_receipt_date`=?  WHERE `order_id`=? AND `payment_receipt`=0");
        $stmt->execute([$payment_receipt,  $payment_receipt_date, $order_id ]);
    }

    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"payment_receipt",  "result"=>$order_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function reserve_seats($order_id, $number_of_people ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


        $counterparty_id = 0;
        $stmt = $dbh->prepare('SELECT DISTINCT  `counterparty_id`   FROM `a_order`   WHERE `order_id`=?  ');
        $stmt->execute([ $order_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $counterparty_id = $row->counterparty_id; 
        }
        if($counterparty_id == 0){
              return;
        }
        $user_counterparty_id = 0;
        $stmt = $dbh->prepare('SELECT DISTINCT  `user_counterparty_id`   FROM   `a_user_counterparty`   WHERE `counterparty_id`=?  AND  `user_id`=1 AND `job_title_id`<=1 ');
        $stmt->execute([ $counterparty_id ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $user_counterparty_id = $row->user_counterparty_id; 
        }
        if($user_counterparty_id == 0){
             $stmt = $dbh->prepare('INSERT INTO `a_user_counterparty`( `user_id`, `counterparty_id`, `job_title_id`) VALUES ( 1, ?, 0 )');
             $stmt->execute([ $counterparty_id ]);
             $user_counterparty_id  = intval($dbh->lastInsertId());
        }
        if($user_counterparty_id == 0){
              return;
        }

        $num = 1;
        $stmt = $dbh->prepare("SELECT  MAX(`certificate_num`) as `num` FROM  `a_order_users`  WHERE  `order_id`=?  ");
        $stmt->execute([$order_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $num =  intval($row->num);  //+1
        }

        $stmt = $dbh->prepare('INSERT INTO `a_order_users`(`order_id`,  `user_counterparty_id`, `number_of_people`,  `certificate_num`) VALUES ( ?, ?, ?, ?)');
        $stmt->execute([$order_id,  $user_counterparty_id, $number_of_people,   $num+$number_of_people ]);


    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"payment_receipt",  "result"=>$order_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function order_to_1c($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    
    $order = [];
    $counterparty_id = 0;
    $price_total = 0;
	$stmt = $dbh->prepare('SELECT DISTINCT  `a_order`.`counterparty_id`, `date_order`, `inn`, `name`, `shortname`, `longtime_contract`, `payer_id`, `payment_receipt`, `payment_receipt_date`   FROM `a_order` LEFT JOIN `a_counterparty` USING(`counterparty_id`)  WHERE `order_id`=?');
	$stmt->execute([$order_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $counterparty_id =  $row->counterparty_id;
        if($counterparty_id <=1 )    
                return;
                
        if( $row->payment_receipt_date!='' && intval($row->payment_receipt)>0 ) 
                return;
    
        $longtime_contract  = $row->longtime_contract; 
        $performer_id = 0;
        $self_id = 0;
        $list = [];
        $stmt2 = $dbh->prepare("SELECT DISTINCT  `a_course`.`course_id`, `a_course`.`name` as `course_name`,  `a_course`.`price`, `performer_id`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  LEFT JOIN `a_course` USING(`course_id`)   WHERE `a_order_users`.`order_id`=?   ORDER BY `a_course`.`course_id` ");
        $stmt2->execute([$order_id]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $price = 0;
            $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_price` WHERE `order_id`=?  AND `course_id`=? ");
            $stmt3->execute([$order_id, $row2->course_id]);
	        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $price =  intval($row3->price);
    	    }
            
            if($price == 0) {
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_discounts` WHERE `order_id`=?   AND `course_id`=? ");
                $stmt3->execute([$order_id, $row2->course_id]);
                if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  intval($row3->price);
                }
            }
            
            if($price == 0) {
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
                $stmt3->execute([$counterparty_id, $row2->course_id]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  intval($row3->price);
	            }
            }
            if($price == 0) {
                $price = $row2->price;
            }

        
            $count = 0;
            $stmt3 = $dbh->prepare("SELECT  count(*) as `count`  FROM `a_order_users`  LEFT JOIN `a_order_course` USING(`item_id`)  WHERE `order_id`=?  AND `course_id`=? AND `cohort_id`=0 ");
            $stmt3->execute([$order_id, $row2->course_id]);
    	    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $count =  $row3->count;
                  $price_total = $price_total + $price*$count;
    	    }
	        if($row2->performer_id > 0 )
                    $performer_id = $row2->performer_id;
    	    if($row2->performer_id == 0 )
                    $self_id = 1;

	        $list[] = ["course_id"=>$row2->course_id, "course_name"=>$row2->course_name, "price"=>$price, "count"=>$count ];
        }

        $contract_name = '';
        $contract_name = contracts($counterparty_id, $order_id, $longtime_contract);
    
        $current_date = date('c', time() );
        //$order = ["id"=>$order_id, "date"=>$current_date,  "date_order"=>$row->date_order, "price"=>$price_total, "inn"=>$row->inn, "name"=>$row->name, "shortname"=>$row->shortname, "counterparty_id"=>$row->counterparty_id, "list"=>$list ];
        $order = ["id"=>$order_id, "date"=>$row->date_order,  "date_order"=>$row->date_order, "price"=>$price_total, "inn"=>$row->inn, "name"=>$row->name, "shortname"=>$row->shortname, "counterparty_id"=>$row->counterparty_id, "contract_name"=>$contract_name, "payer_id"=>$row->payer_id, "list"=>$list ];
        file_put_contents( "../../1c/CED/order".$order_id.".json", json_encode($order, JSON_UNESCAPED_UNICODE));
        chmod("../../1c/CED/order".$order_id.".json", 0666);
file_put_contents( "../../1c/tmp/order".$order_id.".json", json_encode($order, JSON_UNESCAPED_UNICODE));
	}
	
    $course = [];
    $stmt2 = $dbh->query("SELECT `course_id`,  `name`, `shortname`  FROM `a_course`  ");
    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
        $course[] = ["course_id"=>$row2->course_id, "name"=>$row2->name];
    }    
    file_put_contents( "../../1c/CED/courses.json", json_encode($course, JSON_UNESCAPED_UNICODE));
    
    $counterparty = [];
    $stmt2 = $dbh->query("SELECT `counterparty_id`, `inn`, `name`, `shortname`,  `kpp`, `ogrn`, `addres1`, `addres2`, `email`, `phone`, `position_head`, `enterprise_manager`,  `bank`, `checking_account`, `correspondent_account`, `bik`  FROM `a_counterparty` WHERE `counterparty_id`>1 AND `type`=0 ");
    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
        $counterparty[] = ["inn"=>$row2->inn, "name"=>$row2->name, "shortname"=>$row2->shortname,  "counterparty_id"=>$row2->counterparty_id,    "bank"=>$row2->bank, "checking_account"=>$row2->checking_account, "correspondent_account"=>$row2->correspondent_account, "bik"=>$row2->bik, "kpp"=>$row2->kpp, "ogrn"=>$row2->ogrn , "addres1"=>$row2->addres1, "addres2"=>$row2->addres2 ];
    }    
    file_put_contents( "../../1c/CED/counterparty.json", json_encode($counterparty, JSON_UNESCAPED_UNICODE));
    
}


function completed_to_1c($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

return;

    $counterparty_id =0;
	$stmt = $dbh->prepare('SELECT DISTINCT  `counterparty_id`   FROM `a_order`   WHERE `order_id`=?');
	$stmt->execute([$order_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
          $counterparty_id =  $row->counterparty_id;
	}
    if($counterparty_id <=1 )    
        return;

    $is_lock = 0;
    $stmt2 = $dbh->prepare("SELECT count(*) as `count`   FROM `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   WHERE `order_id`=?  AND `course_id`>0  AND  `user_lock`>0  ");
    $stmt2->execute([$order_id]);
    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
        $is_lock = $row2->count;
    }
    $list = [];
    
    if($is_lock > 0){
        $stmt2 = $dbh->prepare("SELECT DISTINCT  `a_course`.`course_id`, `a_course`.`name` as `course_name`,  `a_course`.`price`,  	`performer_id`   FROM `a_order_users` LEFT JOIN `a_course` USING(`course_id`)   WHERE `a_order_users`.`order_id`=? AND  `a_order_users`.`cohort_id`=0 AND `a_course`.`course_id`>0   AND  `user_lock`=0   ORDER BY `a_course`.`course_id` ");
        $stmt2->execute([$order_id]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $price = 0;
            $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_price` WHERE `order_id`=?  AND `course_id`=? ");
            $stmt3->execute([$order_id, $row2->course_id]);
	        if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $price =  intval($row3->price);
    	    }
            
            if($price == 0) {
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_order_discounts` WHERE `order_id`=?   AND `course_id`=? ");
                $stmt3->execute([$order_id, $row2->course_id]);
                if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  intval($row3->price);
                }
            }
            
            if($price == 0) {
                $price = $row2->price;
                $stmt3 = $dbh->prepare("SELECT  `price` FROM `a_price` WHERE `counterparty_id`=?  AND `course_id`=? ");
                $stmt3->execute([$counterparty_id, $row2->course_id]);
	            if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                      $price =  $row3->price;
	            }
            }
            if($price == 0) {
                $price = $row2->price;
            }
            
        
            $count = 0;
            $stmt3 = $dbh->prepare("SELECT  count(*) as `count`  FROM `a_order_users` WHERE `order_id`=?  AND `course_id`=? AND `cohort_id`=0   AND  `user_lock`=0 ");
            $stmt3->execute([$order_id, $row2->course_id]);
    	    if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {  
                  $count =  $row3->count;
                  $price_total = $price_total + $price*$count;
    	    }
    	    if($row2->performer_id > 0 )
                    $performer_id = $row2->performer_id;
	        if($row2->performer_id == 0 )
                    $self_id = 1;

    	    $list[] = ["course_id"=>$row2->course_id, "course_name"=>$row2->course_name, "price"=>$price, "count"=>$count ];
        }
    }


    $date_completed = '';
	//$stmt = $dbh->prepare('SELECT DISTINCT `a_cohort`.`date_protocol`  FROM `a_order_users` LEFT JOIN `a_cohort`  USING(`cohort_id`)  WHERE `order_id`=? AND `a_order_users`.`cohort_id`>0 ');
	$stmt = $dbh->prepare('SELECT `date_completed`  FROM `a_order`  WHERE `order_id`=?  ');
	$stmt->execute([$order_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
          //if( strcmp($row->date_protocol, $date_protocol) > 0 )
            //      $date_protocol =  $row->date_protocol;
          $date_completed =  $row->date_completed;
    }
    $completed = ["id"=>$order_id,  "date"=>$date_completed, "list"=>$list ]; 

    file_put_contents( "../../1c/CED/completed".$order_id.".json", json_encode($completed, JSON_UNESCAPED_UNICODE));
    chmod("../../1c/CED/completed".$order_id.".json",  0666);

file_put_contents( "../../1c/tmp/completed".$order_id.".json", json_encode($completed, JSON_UNESCAPED_UNICODE));
}



function cancelled_to_1c($order_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $date_cancelled = date('Y-m-d');
    $cancelled = ["id"=>$order_id,  "date"=>$date_cancelled ]; 

    file_put_contents( "../../1c/CED/cancelled".$order_id.".json", json_encode($cancelled, JSON_UNESCAPED_UNICODE));
    chmod("../../1c/CED/cancelled".$order_id.".json",  0666);

file_put_contents( "../../1c/tmp/cancelled".$order_id.".json", json_encode($cancelled, JSON_UNESCAPED_UNICODE));
}




$php_sessionid = str_replace("'\'\\\n\r ", '', $_COOKIE['PHPSESSID'] );
$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role_name`, `a_account`.`account_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`) LEFT JOIN `a_role` USING(`role_id`)  WHERE `session_id`=? ');
$stmt->execute([ $php_sessionid ]);
//$stmt = $dbh_a->prepare('SELECT `login`, `role_name`, `a_account`.`account_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`) LEFT JOIN `a_role` USING(`role_id`)  WHERE `token`=? ');
//$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role_name;
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

    $counterparty_id = intval($api_arg["counterparty_id"]);
    if($counterparty_id == 0)
        $counterparty_id = $session_counterparty_id;

    orders_list($counterparty_id, intval($api_arg["orderid"]),  $date1, $date2, $api_arg["order_name"], $api_arg["course"], intval($api_arg["status"]), intval($api_arg["page"]), intval($api_arg["sort"]) );
}
else if($api_function=='items' && intval($api_arg["objectId"])>0 ){
    order_items(intval($api_arg["objectId"]) );
}
else if($api_function=='item_del'  && intval($api_arg["item_id"])>0   ){
    order_item_del( intval($api_arg["item_id"])  );
}
else if($api_function=='user_course'  && intval($api_arg["objectId"])>0  && intval($api_arg["course_id"])>0  ){
    user_course(intval($api_arg["objectId"]), $api_arg["user_check"], $api_arg["check_id"], $api_arg["check_item_id"], intval($api_arg["course_id"]),  intval($api_arg["variation"]), intval($api_arg["group_number"]) );
}
else if($api_function=='item_lock'  && intval($api_arg["objectId"])>0  && intval($api_arg["user_id"])>0 ){
    order_item_lock(intval($api_arg["objectId"]), intval($api_arg["user_id"]), intval($api_arg["user_lock"]) );
}
else if($api_function=='user_course_del'  && intval($api_arg["item_id"])>0  && intval($api_arg["course_id"])>0  ){
    user_course_del( intval($api_arg["item_id"]),  intval($api_arg["course_id"]),  intval($api_arg["group_number"]) );
}
else if($api_function=='delete'){
    order_del(intval($api_arg["objectId"]) );
}
else if($api_function=='object'){
    order_object(intval($api_arg["objectId"]));
}
else if($api_function=='update'){
    order_save(intval($api_arg["objectId"]),  $api_arg["date_begin"], $api_arg["date_end"],  $api_arg["date_completed"],   intval($api_arg["payer_id"]), $api_arg["balance_pay"],  $api_arg["refund_check"],  $api_arg["refund"],  $api_arg["payment_receipt"],  $api_arg["payment_receipt_date"], $api_arg["invoice"]  );
}
else if($api_function=='insert' && intval($api_arg["counterparty_id"])>0){
    $counterparty_id = intval($api_arg["counterparty_id"]);
    //if($counterparty_id <= 1)
    //    $counterparty_id = $session_counterparty_id;
        
    order_create($counterparty_id, $api_arg["date_order"],  $api_arg["date_begin"], $api_arg["date_end"], intval($api_arg["payer_id"]), $api_arg["balance_pay"], $api_arg["invoice"] );
}
else if($api_function=='update_status'){
    order_update_status(intval($api_arg["objectId"]), intval($api_arg["status"]) );
}
else if($api_function=='sync'){
    order_sync_1c(intval($api_arg["objectId"]) );
}
//else if($api_function=='report'){
//    order_report( intval($api_arg["objectId"]) );
//}

else if($api_function=='user_order'  && intval($api_arg["objectId"])>0  && intval($api_arg["item_id"])>0 ){
    user_sort(intval($api_arg["objectId"]), intval($api_arg["item_id"]), $api_arg["cmd"] );
}
else if($api_function=='consulting_add'  && intval($api_arg["objectId"])>0  && intval($api_arg["course_id"])>0 ){
    consulting_add(intval($api_arg["objectId"]), intval($api_arg["course_id"]), intval($api_arg["quantum"]) );
}
else if($api_function=='consulting_del'  && intval($api_arg["objectId"])>0  && intval($api_arg["course_id"])>0 ){
    consulting_del(intval($api_arg["objectId"]), intval($api_arg["course_id"]) );
}
else if($api_function=='order_course' && intval($api_arg["objectId"])>0 ){
    order_course(intval($api_arg["objectId"]) );
}
else if($api_function=='discounts_save' && intval($api_arg["objectId"])>0 ){
    order_discounts_save(intval($api_arg["objectId"]), $api_arg["course_id"], $api_arg["price"] );
}
//else if($api_function=='contract_update'){
//    $counterparty_id = intval($api_arg["counterparty_id"]);
//    if($counterparty_id == 0)
//        $counterparty_id = $session_counterparty_id;
//
//    contract_save($counterparty_id,  $api_arg["date_contract"],  $api_arg["a_contract_id"] );
//}
else if($api_function=='payment_receipt' && intval($api_arg["objectId"])>0 ){
    payment_receipt(intval($api_arg["objectId"]), intval($api_arg["payment_receipt"]), $api_arg["payment_receipt_date"] );
}
else if($api_function=='reserve_seats' && intval($api_arg["objectId"])>0 ){
    reserve_seats(intval($api_arg["objectId"]), intval($api_arg["number_of_people"]) );
}
else if($api_function=='protocol_number_save' && intval($api_arg["course_id"])>0 && intval($api_arg["objectId"])>0 ){
    protocol_number_save(intval($api_arg["objectId"]), intval($api_arg["course_id"]), intval($api_arg["group_number"]),   strip_tags($api_arg["custom_protocol_number"]) );
}
else if($api_function=='cert_number_save' && intval($api_arg["item_id"])>0 ){
    cert_number_save(intval($api_arg["item_id"]), intval($api_arg["course_id"]), strip_tags($api_arg["blank_number"]),  strip_tags($api_arg["custom_cert_number"]) );
}
else if($api_function=='user_course_check'  ){
    user_course_check( $api_arg["user_course_check"], $api_arg["check_id"], $api_arg["check_item_id"], intval($api_arg["course_id"]),  intval($api_arg["variation"])  );
}


else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

