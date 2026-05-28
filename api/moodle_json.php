<?php
/**
 * @copyright 2022
 */


require_once '../config-moodle.php';
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

require_once '../config.php';
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


//session_start();

//$api_function = '';
//$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}

//$api_function='courses_list';




function courses_list($is_search, $name,  $c_parent) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $p_list = [];
    $c_list = [];
    //$rc_list[] = ["userId"=>0, "login"=>'', "name"=>'', "fullname"=>''];

    //if($session_role) {
    if($is_search >0 && $c_parent>0){
	$stmt = $dbh->prepare('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course` WHERE `fullname` LIKE ?  AND `id` > 1 AND `category`=?  ORDER BY `fullname` LIMIT 500');
        $stmt->execute(["$name%", $c_parent]);
    }
    else if( $c_parent>0) {
	$stmt = $dbh->prepare('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course`  WHERE  `id` > 1 AND `category`=?  ORDER BY `fullname` LIMIT 500');
        $stmt->execute([ $c_parent]);
    }
    else if($is_search >0){
	$stmt = $dbh->prepare('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course` WHERE `fullname` LIKE ?  AND `id` > 1  ORDER BY `fullname` LIMIT 500');
        $stmt->execute(["$name%"]);
    }
    else {
	$stmt = $dbh->query('SELECT `id`, `fullname`,  `shortname`  FROM `mdl_course`  WHERE  `id` > 1   ORDER BY `fullname` LIMIT 500');
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

/*    $stmt = $dbh->prepare('SELECT `id`, `name`,  `parent`  FROM `mdl_course_categories` WHERE  `parent`= ?     ORDER BY `name`  LIMIT 100');
    $stmt->execute([ $c_parent] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	$c_list[] =  ["categoryId"=>$row->id,  "name"=>$row->name,  "path"=>$row->path  ];
    }*/

    $categories_txt ='';
    $r_parent =  0;
    $stmt = $dbh->prepare('SELECT  `name`, `parent`,  `path`  FROM `mdl_course_categories` WHERE  `id`= ?       LIMIT 1');
    $stmt->execute([ $c_parent] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	  $categories_txt = $row->name;
          $r_parent =  $row->parent;
    }


    //$result = ["role"=>$session_role, "action"=>"course_list",  "list"=>$p_list, "categories"=>$c_list,  "search"=>$is_search, "categories_txt"=>$categories_txt, "parent"=>$r_parent];
    $result = ["role"=>$session_role, "action"=>"course_list",  "list"=>$p_list,   "search"=>$is_search, "categories_txt"=>$categories_txt, "parent"=>$r_parent];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function categories_list_go($c_parent, $level) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $c_list = [];
    $rc = [];
    $prefix = str_repeat('- ', $level);

    $stmt = $dbh->prepare('SELECT `id`, `name`,  `parent`,  `path`  FROM `mdl_course_categories` WHERE  `parent`= ?     ORDER BY `name`  LIMIT 150');
    $stmt->execute([$c_parent] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $name = $prefix . $row->name;
	$c_list[] =  ["categoryId"=>$row->id,  "name"=>$name,  "path"=>$row->path  ];
	$rc = array_merge( $c_list, categories_list_go($row->id, $level+1) );
    }
    return  $rc;
}

function categories_list() {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = categories_list_go(0, 0);

    $result = ["role"=>$session_role, "action"=>"categories_list",   "categories"=>$rc];
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
    
    $result = ["role"=>$session_role, "action"=>"course_list",  "contextId"=>$context_id];
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

	$stmt = $dbh->query('SELECT `id`, `fullname`,  `shortname`, `category`  FROM `mdl_course`  ');
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
		$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_cache_course`(`course_id`, `name`)  VALUES(?,?) ');
		$stmt_a2->execute([$row->id, $row->fullname]);

		$category_id = 0;
		$stmt_a2 = $dbh_a->prepare('SELECT `category_id`   FROM `a_course_category`   WHERE `moodle_category_id`=?  ');
		$stmt_a2->execute([$row->category]);
		if($row_a2 = $stmt_a2->fetch(PDO::FETCH_OBJ)) {
		    $category_id = $row_a2->category_id;
		}

		if($category_id == 0){
		    $stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_course_category`( `moodle_category_id`, `name`, `parent`)  VALUES(?,?,?) ');
		    $stmt_a2->execute([$row->id, $row->fullname, 0]);
		    $category_id = $dbh_a->lastInsertId();
		}

		$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_course`(`moodle_course_id`, `name`, `category_id`)  VALUES(?,?,?) ');
		$stmt_a2->execute([$row->id, $row->fullname, $category_id]);
	}
    
    $result = ["role"=>$session_role, "action"=>"report_sync",  "list"=>'' ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function  report_sync2() {
    global $api_arg, $user_id_session, $session_role,  $dbh,  $dbh_a;


	$stmt = $dbh->query('SELECT `id`, `fullname`,  `shortname`, `category`  FROM `mdl_course`  ');
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
		if($row->id <= 1)
		    continue;

		//$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_cache_course`(`course_id`, `name`)  VALUES(?,?) ');
		//$stmt_a2->execute([$row->id, $row->fullname]);

		$stmt_a2 = $dbh_a->prepare('SELECT `course_id`, `name`, `shortname`   FROM `a_course`   WHERE `moodle_course_id`=?  ');
		$stmt_a2->execute([$row->id]);
		if($row_a2 = $stmt_a2->fetch(PDO::FETCH_OBJ)) {
        	    if($row_a2->name != $row->fullname) {
			$stmt_a3 = $dbh_a->prepare('UPDATE `a_course` SET  `name`=?   WHERE  `course_id`= ?  ');
			$stmt_a3->execute([$row->fullname, $row_a2->course_id] );
            	    }
		    continue;
		}

		$category_id = 0;
		$stmt_a2 = $dbh_a->prepare('SELECT `category_id`   FROM `a_course_category`   WHERE `moodle_category_id`=?  ');
		$stmt_a2->execute([$row->category]);
		if($row_a2 = $stmt_a2->fetch(PDO::FETCH_OBJ)) {
		    $category_id = $row_a2->category_id;
		}

		if($category_id == 0){
		    $category_name = $row->category; 
		    $parent_id = 0;
		    $stmt2 = $dbh->prepare('SELECT  `name`,  `parent`  FROM `mdl_course_categories` WHERE  `id`= ?  ');
		    $stmt2->execute([$row->category] );
		    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
			$category_name = $row2->name;
			$parent_id = $row2->parent;
		    }

		    $stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_course_category`( `moodle_category_id`, `parent_id`, `name`)  VALUES(?,?,?) ');
		    $stmt_a2->execute([$row->category, $parent_id, $category_name]);
		    $category_id = $dbh_a->lastInsertId();
		}

		$stmt_a2 = $dbh_a->prepare('INSERT INTO  `a_course`(`moodle_course_id`, `category_id`, `name`, `shortname` )  VALUES(?,?,?,?) ');
		$stmt_a2->execute([$row->id, $category_id, $row->fullname, $row->shortname ]);
	}


    $result = ["role"=>$session_role, "action"=>"moodle_sync",  "list"=>'' ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$stmt_a = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt_a->execute([ $api_arg['sessionId'] ]);
if($row = $stmt_a->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
}




if($api_function=='courses_list'){
    courses_list($api_arg["search"], $api_arg["name"], intval($api_arg["parent"]));
}
else if($api_function=='course_info'){
    course_info($api_arg["course_id"]);
}
else if($api_function=='categories_list'){
    categories_list();
}
else if($api_function=='report_sync'){
    report_sync2();
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
