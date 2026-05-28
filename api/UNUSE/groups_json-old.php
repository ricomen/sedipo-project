<?php
/**
 * @copyright 2022
 */


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



session_start();

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


function cohort_list($order_id, $make, $group_name,  $page) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $g_list = [];


    $addsearch = '';
    if($group_name!=''){
        $addsearch =  ' AND `name` LIKE ' . addslashes($group_name) . ' ';
    }

    $count = 0;
    if($order_id>0 && $make==1){
	    $stmt = $dbh->prepare('SELECT count(*) as `count`   FROM `a_order_groups`   WHERE `order_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $count = $row->count;
	    }
    }
    if($order_id>0 && $make==1 && $count==0){
        $group_status = 7; /*Комплектование групп*/ 	
	    $stmt = $dbh->prepare('SELECT `order_id`, `date_order`, `number`, `status_id`, `counterparty_id`, `date_begin`, `date_end`   FROM `a_order`   WHERE `order_id`=?');
	    $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $date_order=$row->date_order;
	        $number=$row->number;
	        $order_status_id=$row->status_id;
	        $order_counterparty_id=$row->counterparty_id;
	        $date_begin=$row->date_begin;
	        $date_end=$row->date_end;
	    }
        
        $i = 1;
	    $stmt = $dbh->prepare('SELECT DISTINCT `a_course`.`course_id`, `a_course`.`shortname`  FROM  `a_order_users` LEFT JOIN `a_course` USING(`course_id`)  WHERE `order_id`=? AND `a_order_users`.`course_id`>0 ');
	    $stmt->execute([$order_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $group_name = $date_order .'/'.  $number .'-'.  $i;
            //$stmt2 = $dbh->prepare('INSERT INTO `a_groups`( `name`, `status_id`,  `date_begin`, `date_end` ) VALUES ( ?, ?, ?, ? )');
            //$stmt2->execute([$group_name, $group_status,  $date_begin, $date_end ]);
            //$p_group_id = $dbh->lastInsertId(); 

            $directive = '';
            $chairman = '';
            $teacher = '';
            $stmt2 = $dbh->prepare('SELECT  `chairman`, `teacher`, `directive`  FROM  `a_course` LEFT JOIN `a_course_category` USING(`category_id`) WHERE  `a_course`.`course_id`=? ');
            $stmt2->execute([ $row->course_id]);
            if ($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $chairman = $row2->chairman;
                $teacher = $row2->teacher;
                $directive = $row2->directive;
            }

$p_group_id =0;
            $protocol_num = 1;
            $cohort_name = $date_order .'/'.  $number .'.'.  $i;
            $stmt2 = $dbh->query('LOCK TABLES   `a_cohort`  WRITE');
            $stmt2 = $dbh->query('SELECT max(`protocol_num`)  as `last_protocol_num`    FROM `a_cohort` WHERE YEAR(`date_protocol`) = YEAR(NOW()) AND MONTH(`date_protocol`) = MONTH(NOW()) ');
            if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                    $protocol_num = $row2->last_protocol_num + 1;
            }
            $stmt2 = $dbh->prepare("INSERT INTO `a_cohort`(`group_id`, `name`,  `course_id`,   `date_begin`, `date_end`, `date_protocol`, `protocol_num`, `directive`, `chairman`, `teacher`,  `finalexamination`, `certificate_grade`  ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )");
            $stmt2->execute([$p_group_id, $cohort_name,  $row->course_id,  $date_begin, $date_end, $date_end, $protocol_num, $directive, $chairman, $teacher, 'Сдано', '']);
            $p_cohort_id = $dbh->lastInsertId(); 
            $stmt2 = $dbh->query('UNLOCK TABLES');
            if($p_cohort_id >0 ) {
                $stmt2 = $dbh->prepare('INSERT INTO `a_order_groups`(`order_id`, `group_id`, `cohort_id`) VALUES ( ?, ?, ? )');
	            $stmt2->execute([$order_id, $p_group_id, $p_cohort_id ]);


//                $stmt2 = $dbh->prepare('INSERT INTO `a_cohort`(`group_id`, `name`,  `course_id`,   `date_begin`, `date_end`, `date_protocol`, `chairman`, `teacher` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )');
//	            $stmt2->execute([$p_group_id, $cohort_name,  $row->course_id,  $date_begin, $date_end, $date_end,  $chairman, $teacher]);
//	            $cohort_id = $dbh->lastInsertId(); 

                $certificate_num = 1;
                //$stmt2 = $dbh->query('LOCK TABLES   `a_order_users`  WRITE');
                $stmt2 = $dbh->query('SELECT max(`certificate_num`)  as `last_certificate_num`   FROM `a_order_users` LEFT JOIN `a_cohort` USING(`cohort_id`)  WHERE  YEAR(`date_protocol`) = YEAR(NOW()) AND MONTH(`date_protocol`) = MONTH(NOW()) ');
	            //$stmt2->execute([$order_id,  $row->course_id]);
                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                    $certificate_num = $row2->last_certificate_num + 1;
                }
	            $stmt2 = $dbh->prepare('SELECT   `user_id`  FROM  `a_order_users`   WHERE `order_id`=? AND `course_id`=? ');
	            $stmt2->execute([$order_id,  $row->course_id]);
	            while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	                $stmt3 = $dbh->prepare('INSERT INTO  `a_groups_users`(`user_id`, `group_id`, `cohort_id` ) VALUES( ?, ?, ? )');
    	            $stmt3->execute([$row2->user_id, $p_group_id, $p_cohort_id]);

	                $stmt3 = $dbh->prepare('UPDATE `a_order_users` SET  `cohort_id`=?, certificate_num=? WHERE `order_id`=? AND `user_id`=? AND  `course_id`=?');
    	            $stmt3->execute([$p_cohort_id, $certificate_num, $order_id, $row2->user_id, $row->course_id ]);
    	            $certificate_num = $certificate_num +1;
	            }
                //$stmt2 = $dbh->query('UNLOCK TABLES');
            }
	        $i = $i+1;
	    }
	    
	    
    }


    if($order_id>0) { 
    	//$stmt = $dbh->prepare('SELECT `a_groups`.`group_id`,  `name`, `status_id`   FROM `a_groups` LEFT JOIN `a_order_groups` USING(`group_id`)  WHERE `order_id`=?  '.$addsearch.' ORDER BY `group_id` DESC LIMIT 200');
    	$stmt = $dbh->prepare('SELECT `a_cohort`.`cohort_id`,  `name`, `status_id`   FROM `a_cohort` LEFT JOIN `a_order_users` USING(`cohort_id`)  WHERE `order_id`=?  '.$addsearch.' ORDER BY `cohort_id` DESC LIMIT 200');
	    $stmt->execute([$order_id]);
    }
    else {
	   $stmt = $dbh->query('SELECT `cohort_id`,  `name`, `status_id`   FROM `a_cohort` WHERE 1  '.$addsearch.' ORDER BY `cohort_id` DESC LIMIT 200');
    }
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $group_count = 0;
	    $stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM  `a_order_users`   WHERE `cohort_id`=? AND `user_id`>0 ');
	    $stmt2->execute([$row->cohort_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        $group_count = $row2->count;
	    }
        $cohorts_list = [];
	    $stmt2 = $dbh->prepare('SELECT  `cohort_id`, `a_course`.`name`,  `a_course`.`shortname`,  `a_course`.`course_id`, `protocol_temlate`   FROM  `a_cohort` LEFT JOIN `a_course` USING(`course_id`) LEFT JOIN `a_course_category` USING(`category_id`)   WHERE `cohort_id`=? ');
	    $stmt2->execute([$row->cohort_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	        //$cohorts_list[] = ["cohort_id"=>$row2->cohort_id, "course_id"=>$row2->course_id, "name"=>$row2->name,  "protocol_temlate"=>$row2->protocol_temlate  ];
	    }

	    $g_list[] =  ["groupId"=>$row->cohort_id,  "name"=>$row->name,  "status_id"=>$row->status_id,   "count"=>$group_count,  "cohort_id"=>$row2->cohort_id, "course_id"=>$row2->course_id,  "course_name"=>$row2->name,  "course_"=>$row2->shortname,  "protocol_temlate"=>$row2->protocol_temlate  ];
    }

    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$g_list,  "search"=>$is_search ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function cohort_object($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc =  ["cohort_id"=>$cohort_id,  "name"=>'', "group_id"=>0, "moodle_cohort_id"=>0  ];
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT   `a_cohort`.`name`, `a_cohort`.`course_id`, `category`, `date_begin`, `date_end`, `date_protocol`, `group_id`, `moodle_cohort_id`,  `a_cohort`.`directive`, `a_cohort`.`chairman`, `a_cohort`.`teacher`, `a_course`.`name` as `course_name`, `a_course`.`shortname` as `course_shortname`, `order_id`, `finalexamination`, `certificate_grade`    FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_order_users` USING(`cohort_id`)   WHERE `a_cohort`.`cohort_id`=?');
	    $stmt->execute([$cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $rc =  ["cohort_id"=>$cohort_id,  "name"=>$row->name,   "course_id"=>$row->course_id,  "course_name"=>$row->course_name,  "course_shortname"=>$row->course_shortname,  "category"=>$row->category, "date_begin"=>$row->date_begin, "date_end"=>$row->date_end, "date_protocol"=>$row->date_protocol, "order_id"=>$row->order_id, "moodle_cohort_id"=>$row->moodle_cohort_id, "directive"=>$row->directive,  "chairman"=>$row->chairman, "teacher"=>$row->teacher,  "finalexamination"=>$row->finalexamination, "certificate_grade"=>$row->certificate_grade,       "group_id"=>$row->group_id   ];
	    }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





function cohort_save($cohort_id, $name,  $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher, $finalexamination, $certificate_grade ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($date_begin == '')
         $date_begin = date('Y-m-d');

    if($date_end == '')
         $date_end = date('Y-m-d');


    if($cohort_id >0 && $name!='' ) {
        if($chairman == '' && $teacher == '') {
	        $stmt = $dbh->prepare('SELECT     `chairman`, `teacher`    FROM `a_course`   WHERE `course_id`=?');
	        $stmt->execute([$course_id]);
	        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	            $chairman = $row->chairman;
	            $teacher = $row->teacher;
	        }
        }     

        $protocol_num = 0;
        $order_id = 0;
        $course_id = 0;
        //$stmt2 = $dbh->query('LOCK TABLES   `a_cohort`  WRITE');
        $stmt = $dbh->prepare('SELECT `protocol_num`, `order_id`, `course_id`   FROM `a_cohort` LEFT JOIN `a_order_groups` USING(`cohort_id`)  WHERE `a_cohort`.`cohort_id`=?  AND  YEAR(`date_protocol`) = YEAR(?)  AND  MONTH(`date_protocol`) = MONTH(?) ');
        $stmt->execute([ $cohort_id, $date_protocol, $date_protocol ]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	            $protocol_num = $row->protocol_num;
	            $order_id  = $row->order_id;
	            $course_id = $row->course_id;
	    }
        if($protocol_num < 1 ) {
            $protocol_num = 1;
            $stmt2 = $dbh->query('LOCK TABLES   `a_cohort`  WRITE');
            $stmt2 = $dbh->prepare('SELECT max(`protocol_num`)  as `last_protocol_num`    FROM `a_cohort` WHERE YEAR(`date_protocol`) = YEAR(?)  AND  MONTH(`date_protocol`) = MONTH(?) ');
            $stmt2->execute([ $date_protocol, $date_protocol ]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $protocol_num = $row2->last_protocol_num + 1;
	        }
            $stmt2 = $dbh->prepare('UPDATE `a_cohort` SET   `protocol_num`=?  WHERE `cohort_id`=?');
            $stmt2->execute([ $protocol_num,   $cohort_id ]);
            $stmt2 = $dbh->query('UNLOCK TABLES');
            
	        $stmt2 = $dbh->prepare('SELECT   `user_id`  FROM  `a_order_users`   WHERE `cohort_id`=? ');
	        $stmt2->execute([ $cohort_id ]);
	        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $certificate_num = 0;
                $stmt3 = $dbh->prepare('UPDATE `a_order_users` SET  `certificate_num`=?  WHERE  `user_id`=? AND `order_id`=? AND  `course_id`=?');
	            $stmt3->execute([$certificate_num,  $row2->user_id, $order_id, $course_id ]);
            }
        } 

        $stmt = $dbh->prepare('UPDATE `a_cohort` SET   `name`=?,   `date_begin`=?, `date_end`=?, `date_protocol`=?, `directive`=?, `chairman`=?, `teacher`=? , `finalexamination`=?, `certificate_grade`=?   WHERE `cohort_id`=?');
        $stmt->execute([ $name, $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher, $finalexamination, $certificate_grade,  $cohort_id ]);
        

        $certificate_num = 1;
        //$stmt2 = $dbh->query('LOCK TABLES   `a_order_users`  WRITE');
        $stmt2 = $dbh->prepare('SELECT max(`certificate_num`)  as `last_certificate_num`   FROM `a_order_users` LEFT JOIN `a_cohort` USING(`cohort_id`)  WHERE  YEAR(`date_protocol`) = YEAR(?) AND MONTH(`date_protocol`) = MONTH(?) ');
        $stmt2->execute([ $date_protocol, $date_protocol ]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $certificate_num = $row2->last_certificate_num + 1;
        }
        $stmt2 = $dbh->prepare('SELECT   `user_id`  FROM  `a_order_users`   WHERE `order_id`=? AND `course_id`=? ');
        $stmt2->execute([$order_id,  $row->course_id]);
        while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
            $stmt3 = $dbh->prepare('UPDATE `a_order_users` SET   certificate_num=? WHERE `order_id`=? AND `user_id`=? AND  `course_id`=?');
            $stmt3->execute([$certificate_num, $order_id, $row2->user_id, $course_id ]);
            $certificate_num = $certificate_num +1;
        }
        //$stmt2 = $dbh->query('UNLOCK TABLES');
        
    }

//file_put_contents("/var/www/dekanat_bagsurb_ru/lst.txt", json_encode([ $name, $status, $organization_id, $date_begin, $date_end,  $group_id ], JSON_UNESCAPED_UNICODE));
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"update",  "result"=>$cohort_id ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function cohort_create( $name,  $course_id, $category, $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher, $finalexamination, $certificate_grade  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($date_begin == '')
         $date_begin = date('Y-m-d');

    if($date_end == '')
         $date_end = date('Y-m-d');

    if($group_id >0 ) {
        if($chairman == '' && $teacher == '') {
            $stmt = $dbh->prepare('SELECT `chairman`, `teacher`, `directive`  FROM `a_course` LEFT JOIN `a_course_category` USING(`category_id`) WHERE  `a_course`.`course_id`=? ');
	        $stmt->execute([$course_id]);
	        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	            $chairman = $row->chairman;
	            $teacher = $row->teacher;
	            $directive = $row->directive;
	        }
        }     

        $protocol_num = 1;
        $stmt2 = $dbh->query('LOCK TABLES   `a_cohort`  WRITE');
        $stmt2 = $dbh->query('SELECT max(`protocol_num`)  as `last_protocol_num`    FROM `a_cohort` WHERE YEAR(`date_protocol`) = YEAR(NOW())  AND MONTH(`date_protocol`) = MONTH(NOW()) ');
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $protocol_num = $row2->last_protocol_num + 1;
	    }
        $stmt2 = $dbh->prepare('INSERT INTO `a_cohort`( `name`,  `course_id`, `category`,  `date_begin`, `date_end`, `date_protocol`, `protocol_num`, `directive`, `chairman`, `teacher` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
	    $stmt2->execute([ $name,  $course_id, $category, $date_begin, $date_end, $date_protocol, $protocol_num, $chairman, $teacher  ]);
	    $cohort_id = $dbh->lastInsertId(); 
        $stmt2 = $dbh->query('UNLOCK TABLES');
	    
    }

    if($cohort_id>0)
	    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "result"=>$cohort_id ];
    else
        $result = ["status"=>"1", "error"=>'Ошибка записи в базу данных', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "result"=>0 ];

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function cohort_del($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('DELETE FROM  `a_cohort`   WHERE `cohort_id`=?  ');
	    $stmt->execute([$cohort_id]);

	    $stmt = $dbh->prepare('DELETE FROM  `a_order_groups`   WHERE `cohort_id`=?  ');
	    $stmt->execute([$cohort_id]);


        $group_id = 0;
	    $stmt = $dbh->prepare('SELECT  `group_id`  FROM `a_cohort`   WHERE `cohort_id`=?');
	    $stmt->execute([$cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
             $group_id = $row->group_id;
	    }     

	    $stmt = $dbh->prepare('DELETE FROM  `a_groups_users`   WHERE `group_id`=?  ');
    	$stmt->execute([$group_id]);


	    $stmt = $dbh->prepare('DELETE FROM  `a_groups`   WHERE `group_id`=?  ');
    	$stmt->execute([$group_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"group_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function cohort_items($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $users_list = [];
    $course_list = [];
    $organization_id = 0;
    //$rc_list[] = ["userId"=>0, "login"=>'', "name"=>'', "fullname"=>''];

    if($cohort_id >0 ) {
	    $stmt2 = $dbh->prepare('SELECT DISTINCT  `a_cohort`.`course_id`,   `a_course`.`name`, `a_course`.`shortname`, `certificate_name`, `certificate_a1_name`, `certificate_temlate`, `certificate_a1_temlate`   FROM `a_cohort`  LEFT JOIN `a_course` USING(`course_id`)  LEFT JOIN `a_course_category` USING(`category_id`)  LEFT JOIN `a_order_users` USING(`cohort_id`)  WHERE `a_cohort`.`cohort_id`=? ');
        $stmt2->execute([$cohort_id]);
	    while($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		     $course_list[] = [ "course_id"=>$row2->course_id, "name"=>$row2->name, "shortname"=>$row2->shortname, "certificate_name"=>$row2->certificate_name, "certificate_a1_name"=>$row2->certificate_a1_name, "certificate_temlate"=>$row2->certificate_temlate, "certificate_a1_temlate"=>$row2->certificate_a1_temlate ];
	    }

	    $stmt = $dbh->prepare('SELECT DISTINCT  `a_users`.`user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `counterparty_id`, `position_id`, `subdivision`, `date_of_birth`, `snils`  FROM `a_users`  LEFT JOIN `a_order_users` USING(`user_id`)  WHERE `cohort_id`=?   ORDER BY `lastname`');
	    $stmt->execute([$cohort_id]);
	    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $organization_name = '';
            $stmt2 = $dbh->prepare("SELECT  `name`  FROM `a_counterparty`  WHERE  `counterparty_id`=? ");
	        $stmt2->execute([$row->counterparty_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	            $organization_name = $row2->name;
	        }

	        $position_name = '';
	        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_positions`  WHERE `position_id`=? ');
    	    $stmt2->execute([$row->position_id]);
	        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		        $position_name = $row2->name;
	        }

	        $users_list[] =  ["userId"=>$row->user_id, "login"=>$row->login, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "counterparty_id"=>$row->counterparty_id,  "organization_name"=>$organization_name, "position"=>$position_name, "subdivision"=>$row->subdivision, "date_of_birth"=>$row->date_of_birth, "snils"=>$row->snils ];
       }
    }

    $result = ["role"=>$session_role, "action"=>"group_items",  "list"=>$users_list,  "course_list"=>$course_list, "search"=>$is_search ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function group_item_del($group_id, $user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($group_id >0 ) {
	$stmt = $dbh->prepare('DELETE FROM  `a_groups_users`   WHERE `group_id`=? AND `user_id`=? ');
	$stmt->execute([$group_id, $user_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"group_item_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function cohort_report($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course_id = 0;
    $rc = [];
    $list = [];
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT  `course_id`  FROM `a_cohort`   WHERE `cohort_id`=?');
	    $stmt->execute([$group_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $course_id = $row->course_id;
	    }
    }
    if($cohort_id >0 &&  $course_id >0) { 
	    $stmt = $dbh->prepare('SELECT `user_id`  FROM `a_order_users` WHERE `cohort_id`=?');
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






if($api_function=='list'){
    if(isset($api_arg["name"]))
        $name = $api_arg["name"];
    else
        $name = '';

    cohort_list(intval($api_arg["order_id"]), intval($api_arg["make"]), $name, $api_arg["page"] );
}
else if($api_function=='object'){
    cohort_object(intval($api_arg["objectId"]));
}
else if($api_function=='insert'){
    cohort_create( $api_arg["name"], intval($api_arg["courseId"]), intval($api_arg["category"]), $api_arg["date_begin"], $api_arg["date_end"],  $api_arg["date_protocol"], $api_arg["directive"],  $api_arg["chairman"], $api_arg["teacher"],  $api_arg["finalexamination"], $api_arg["certificate_grade"] );
}
else if($api_function=='update'){
    cohort_save(intval($api_arg["objectId"]), $api_arg["name"],  $api_arg["date_begin"], $api_arg["date_end"], $api_arg["date_protocol"], $api_arg["directive"], $api_arg["chairman"], $api_arg["teacher"],  $api_arg["finalexamination"], $api_arg["certificate_grade"]  );
}
else if($api_function=='delete'){
    cohort_del(intval($api_arg["objectId"]) );
}
else if($api_function=='items'){
    cohort_items(intval($api_arg["objectId"]) );
}
else if($api_function=='item_del'){
    cohort_item_del(intval($api_arg["objectId"]), intval($api_arg["userId"]) );
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



?>

