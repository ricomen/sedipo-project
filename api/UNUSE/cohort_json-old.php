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



//session_start();



$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}


#$api_function = 'object';
#$api_arg = [];



function cohort_create($group_id, $name,  $course_id, $category, $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher  ) {
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
        $stmt2 = $dbh->prepare('INSERT INTO `a_cohort`(`group_id`, `name`,  `course_id`, `category`,  `date_begin`, `date_end`, `date_protocol`, `protocol_num`, `directive`, `chairman`, `teacher` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
	    $stmt2->execute([$group_id, $name,  $course_id, $category, $date_begin, $date_end, $date_protocol, $protocol_num, $chairman, $teacher  ]);
	    $cohort_id = $dbh->lastInsertId(); 
        $stmt2 = $dbh->query('UNLOCK TABLES');
	    
    }

    if($cohort_id>0)
	    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "result"=>$cohort_id ];
    else
        $result = ["status"=>"1", "error"=>'Ошибка записи в базу данных', "role"=>$session_role, "action"=>"cohort_save", "userId"=>$user_id_session, "result"=>0 ];

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function cohort_save($cohort_id, $name,  $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher ) {
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

        $stmt = $dbh->prepare('UPDATE `a_cohort` SET   `name`=?,   `date_begin`=?, `date_end`=?, `date_protocol`=?, `directive`=?, `chairman`=?, `teacher`=?   WHERE `cohort_id`=?');
        $stmt->execute([ $name, $date_begin, $date_end, $date_protocol, $directive, $chairman, $teacher,  $cohort_id ]);
        

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




function cohort_del($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($cohort_id >0 ) {
	$stmt = $dbh->prepare('DELETE FROM  `a_cohort`   WHERE `cohort_id`=?  ');
	$stmt->execute([$cohort_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"cohort_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function cohort_object($cohort_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc =  ["cohort_id"=>$cohort_id,  "name"=>'', "group_id"=>0, "moodle_cohort_id"=>0  ];
    if($cohort_id >0 ) {
	    $stmt = $dbh->prepare('SELECT   `a_cohort`.`name`, `a_cohort`.`course_id`, `category`, `date_begin`, `date_end`, `date_protocol`, `group_id`, `moodle_cohort_id`,  `a_cohort`.`directive`, `a_cohort`.`chairman`, `a_cohort`.`teacher`, `a_course`.`name` as `course_name`, `a_course`.`shortname` as `course_shortname`    FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`)  WHERE `cohort_id`=?');
	    $stmt->execute([$cohort_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	        $rc =  ["cohort_id"=>$cohort_id,  "name"=>$row->name,   "course_id"=>$row->course_id,  "course_name"=>$row->course_name,  "course_shortname"=>$row->course_shortname,  "category"=>$row->category, "date_begin"=>$row->date_begin, "date_end"=>$row->date_end, "date_protocol"=>$row->date_protocol, "group_id"=>$row->group_id, "moodle_cohort_id"=>$row->moodle_cohort_id, "directive"=>$row->directive,  "chairman"=>$row->chairman, "teacher"=>$row->teacher   ];
	    }
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"object",  "result"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}






if($api_function=='insert'){
    cohort_create(intval($api_arg["groupId"]), $api_arg["name"], intval($api_arg["courseId"]), intval($api_arg["category"]), $api_arg["date_begin"], $api_arg["date_end"],  $api_arg["date_protocol"], $api_arg["directive"],  $api_arg["chairman"], $api_arg["teacher"] );
}
else if($api_function=='update'){
    cohort_save(intval($api_arg["cohortId"]), $api_arg["name"],  $api_arg["date_begin"], $api_arg["date_end"], $api_arg["date_protocol"], $api_arg["directive"], $api_arg["chairman"], $api_arg["teacher"]  );
}
else if($api_function=='delete'){
    cohort_del(intval($api_arg["objectId"]) );
}
else if($api_function=='object'){
    cohort_object(intval($api_arg["objectId"]));
}

else {
    $result = array('JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>

