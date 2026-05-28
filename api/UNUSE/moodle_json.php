<?php
/**
 * @copyright 2022
 */


require_once 'config-moodle.php';
$dbhost = $CFG->dbhost;
$dbuser = $CFG->dbuser;
$password = $CFG->dbpass;
$dbname = $CFG->dbname;
try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $password);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}

require_once 'config.php';
$dbhost_a = $cfg->host;
$dbuser_a = $cfg->user;
$dbpassword_a = $cfg->password;
$dbname_a = $cfg->name;
try {  
    $dbh_a = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


session_start();
$userid = 0;    // User id.


//$api_function = '';
//$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}

//$api_function='courses_list';




function courses_list($is_search, $name) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $p_list = [];
    //$rc_list[] = ["userId"=>0, "login"=>'', "name"=>'', "fullname"=>''];

    //if($session_role) {
    if($is_search >0){
	$stmt = $dbh->prepare('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course` WHERE `fullname` LIKE ?  AND `id` > 1  ORDER BY `fullname`');
        $stmt->execute(["$name%"]);
    }
    else {
	$stmt = $dbh->query('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course`  WHERE  `id` > 1   ORDER BY `fullname`');
    }
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	$context_id = 0;
	$stmt2 = $dbh->prepare('SELECT `id` FROM `mdl_context` WHERE `contextlevel`=50 AND `instanceid`=? ');
        $stmt2->execute([$row->id]);
	if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	    $context_id = $row2->id;
	}
	$p_list[] =  ["courseId"=>$row->id,  "name"=>$row->fullname,  "shortname"=>$row->shortname, "contextId"=>$context_id  ];
    }
    //}
    $result = ["role"=>$session_role, "action"=>"course_list",  "list"=>$p_list,  "search"=>$is_search];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


/* Hi there! I'm just started in moodle and I'm curious to know what's the meaning of the values of contextlevel ? I know that contextlevel = 50 it's to indicates that is a course and I find others values defined to that same column inside the code.
CONTEXT_SYSTEM = 10
CONTEXT_PERSONAL = 20
CONTEXT_USER = 30
CONTEXT_COURSECAT = 40
CONTEXT_GROUP = 60
CONTEXT_MODULE = 70
CONTEXT_BLOCK = 80
*/

function course_info($course_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


	$context_id = 0;
	$stmt2 = $dbh->prepare('SELECT `id` FROM `mdl_context` WHERE `contextlevel`=50 AND `instanceid`=? ');
        $stmt2->execute([$course_id]);
	if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
	    $context_id = $row2->id;
	}
    
    $result = ["role"=>$session_role, "action"=>"course_info",  "contextId"=>$context_id];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





function  report_sync() {
    global $api_arg, $user_id_session, $session_role,  $dbh,  $dbh_a;


	$list = [];
	$stmt = $dbh->query('SELECT `userid`, `customcertid`, `code`, `timecreated`  FROM  `mdl_customcert_issues` ');
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $course_name = '';
	    $username = '';
	    $lastname = ''; 
	    $code = $row->code;
	    $course_id = '';
	    $date_cert = date('d.m.Y', $row->timecreated);
	    $a_date = date('Y-m-d', $row->timecreated);

	    $stmt2 = $dbh->prepare('SELECT  `course` FROM `mdl_customcert` WHERE `id`=?  ');
    	    $stmt2->execute([$row->customcertid]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$course_id = $row2->course;
		$stmt3 = $dbh->prepare('SELECT  `fullname`,  `shortname`  FROM `mdl_course` WHERE `id`=?  ');
    		$stmt3->execute([$row2->course]);
		if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
		    $course_name =  $row3->fullname;
		}
	    }

	    $stmt2 = $dbh->prepare('SELECT  `username`, `lastname` FROM `mdl_user` WHERE `id`=?  ');
    	    $stmt2->execute([$row->userid]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$username = $row2->username;
		$lastname = $row2->lastname;
	    }
	    
	    $user_id = 0;
	    $stmt_a = $dbh_a->prepare('SELECT  `user_id`,  `lastname`   FROM `a_users`   WHERE `login`=?');
	    $stmt_a->execute([$username]);
	    if($row_a = $stmt_a->fetch(PDO::FETCH_OBJ)) {
		$user_id  = $row_a->user_id;
	    }
	    
	    if($user_id>0){
		$stmt_a2 = $dbh_a->prepare('SELECT count(*) as `count`   FROM `a_reports`   WHERE `user_id`=? AND  `course_id`=? ');
		$stmt_a2->execute([$user_id, $course_id]);
		if($row_a2 = $stmt_a2->fetch(PDO::FETCH_OBJ)) {
		    $count  = $row_a2->count;
		}
		
		if($count==0){
		    $stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_reports`(`user_id`, `course_id`, `course`, `num`, `date`, `result`, `a_date`)  VALUES(?,?,?,?,?,?,?) ');
		    $stmt_a2->execute([$user_id, $course_id, $course_name, $code, $date_cert, 'Пройден', $a_date]);

		    /*$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_cache_course`(`course_id`, `name`)  VALUES(?,?) ');
		    $stmt_a2->execute([$course_id, $course_name]);*/
		}

		//$list[] = ["userId"=>$user_id, "username"=>$username, "course"=>$course_name, "code"=>$code, "courseId"=>$course_id, "date"=>$date_cert, "sync"=>$count ];
	    }
	}

	$list2 = [];
	$course_id_0 = 0;
	$modules_test_id = 0;
	$stmt = $dbh->query("SELECT `id`  FROM `mdl_modules` WHERE `name`='quiz' ");
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
		$modules_test_id =  $row->id;
	}
 
	//$stmt = $dbh->query('SELECT DISTINCT `courseid`, `userid` FROM `mdl_grade_grades` LEFT JOIN `mdl_grade_items` ON `mdl_grade_grades`.`itemid`=`mdl_grade_items`.`id` ORDER BY  `courseid`, `userid` ');
	$stmt = $dbh->query('SELECT DISTINCT  `course`, `userid` FROM  `mdl_course_modules` LEFT JOIN `mdl_course_modules_completion` ON  `mdl_course_modules_completion`.`coursemoduleid`= `mdl_course_modules`.`id`  WHERE `completionstate`>0  ORDER BY  `course`, `userid` ');
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $username = '';
	    $lastname = ''; 
	    $a_date = date('Y-m-d');
	    $course_id = $row->course;
	    $moodle_userid = $row->userid;
            $grade = 0;
            $grade_test = 0;
            $time_completion = 0;

	    $stmt2 = $dbh->prepare('SELECT DISTINCT  count(*) as `count`  FROM  `mdl_course_modules` LEFT JOIN `mdl_course_modules_completion` ON  `mdl_course_modules_completion`.`coursemoduleid`= `mdl_course_modules`.`id`  WHERE `completionstate`>0 AND  `course`=? AND `userid`=? ');
    	    $stmt2->execute([$course_id, $moodle_userid]);
    	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$grade = $row2->count;
            }

	    $stmt2 = $dbh->prepare('SELECT DISTINCT  count(*) as `count`  FROM  `mdl_course_modules` LEFT JOIN `mdl_course_modules_completion` ON  `mdl_course_modules_completion`.`coursemoduleid`= `mdl_course_modules`.`id`  WHERE `completionstate`>0 AND `mdl_course_modules`.`module`=?  AND  `course`=? AND `userid`=? ');
    	    $stmt2->execute([$modules_test_id, $course_id, $moodle_userid]);
    	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$grade_test = $row2->count;
            }




/*	    $stmt2 = $dbh->prepare('SELECT DISTINCT  count(*) as `count`  FROM  `mdl_course_modules` LEFT JOIN `mdl_course_modules_completion` ON  `mdl_course_modules_completion`.`coursemoduleid`= `mdl_course_modules`.`id`  WHERE `completionstate`=0 AND  `course`=? AND `userid`=? ');
    	    $stmt2->execute([$course_id, $moodle_userid]);
    	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$begin_u = $row2->count;
            }
*/

            if( $grade >0 ) {
		$stmt2 = $dbh->prepare('SELECT  `username`, `lastname` FROM `mdl_user` WHERE `id`=?  ');
    		$stmt2->execute([$moodle_userid]);
		if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
			$username = $row2->username;
			$lastname = $row2->lastname;
		}

		$modules = 0;
		$stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM `mdl_course_modules` WHERE `course`=?  ');
    		$stmt2->execute([$course_id]);
		if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
			$modules = $row2->count;
		}

		$modules_test = 0;
		$stmt2 = $dbh->prepare('SELECT count(*) as `count` FROM `mdl_course_modules` WHERE `module`=? AND `course`=?  ');
    		$stmt2->execute([$modules_test_id, $course_id]);
		if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
			$modules_test = $row2->count;
		}

		$stmt2 = $dbh->prepare('SELECT DISTINCT  MAX(`timemodified`) as `time_completion`  FROM  `mdl_course_modules` LEFT JOIN `mdl_course_modules_completion` ON  `mdl_course_modules_completion`.`coursemoduleid`= `mdl_course_modules`.`id`  WHERE  `completionstate`>0  AND  `course`=? AND `userid`=? ');
    		$stmt2->execute([$course_id, $moodle_userid]);
    		if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		    $time_completion = $row2->time_completion;
        	}


		$user_id = 0;
		$stmt_a = $dbh_a->prepare('SELECT  `user_id`,  `lastname`   FROM `a_users`   WHERE `login`=?');
		$stmt_a->execute([$username]);
		if($row_a = $stmt_a->fetch(PDO::FETCH_OBJ)) {
			$user_id  = $row_a->user_id;
		}
	    
		if($user_id>0){
		    if($grade >= $modules){
			$date_cert = date('d.m.Y', $time_completion );
		        $c_date = date('Y-m-d', $time_completion );
			$course_name = '';

			$count = 0;
			$stmt_a2 = $dbh_a->prepare('SELECT count(*) as `count`   FROM `a_reports`   WHERE `user_id`=? AND  `course_id`=? ');
			$stmt_a2->execute([$user_id, $course_id]);
			if($row_a2 = $stmt_a2->fetch(PDO::FETCH_OBJ)) {
			    $count  = $row_a2->count;
			}
			if($count==0){
			    $stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_reports`(`user_id`, `course_id`, `course`, `num`, `date`, `result`, `a_date`)  VALUES(?,?,?,?,?,?,?) ');
			    $stmt_a2->execute([$user_id, $course_id, $course_name, '', $date_cert, 'Пройден', $c_date]);
			}
		    }

			$stmt_a2 = $dbh_a->prepare('DELETE  FROM `a_progress`   WHERE `user_id`=? AND  `course_id`=? ');
			$stmt_a2->execute([$user_id, $course_id]);
		
			$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_progress`(`user_id`, `course_id`,  `a_date`, `grade`, `modules`, `grade_test`, `modules_test` )  VALUES(?,?,?,?,?,?,?) ');
			$stmt_a2->execute([$user_id, $course_id,  $a_date, $grade, $modules, $grade_test, $modules_test ]);


			//$list2[] = ["userId"=>$user_id, "username"=>$username, "course"=>$course_name,  "courseId"=>$course_id, "date"=>$a_date, "grade"=>$grade];
        	}
	    }
	    /*if($course_id != $course_id_0 && $course_id>0) {
	        $course_name = '';
	        $stmt3 = $dbh->prepare('SELECT  `fullname`,  `shortname`  FROM `mdl_course` WHERE `id`=?  ');
		$stmt3->execute([$course_id ]);
		if($row3 = $stmt3->fetch(PDO::FETCH_OBJ)) {
			$course_name =  $row3->fullname;
		}

		$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_cache_course`(`course_id`, `name`)  VALUES(?,?) ');
		$stmt_a2->execute([$course_id, $course_name]);
		$course_id_0 = $course_id;
	    }*/
        }

	$stmt = $dbh->query('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course`  ');
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
		$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_cache_course`(`course_id`, `name`)  VALUES(?,?) ');
		$stmt_a2->execute([$row->id, $row->fullname]);
	}
    
    $result = ["role"=>$session_role, "action"=>"report_sync",  "list"=>$list2 ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




if($api_function=='courses_list'){
    courses_list($api_arg["search"], $api_arg["name"]);
}
else if($api_function=='course_info'){
    course_info($api_arg["course_id"]);
}
else if($api_function=='report_sync'){
    report_sync();
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
