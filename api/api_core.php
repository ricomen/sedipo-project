<?php


function core_course_report2($course_id , $date1, $date2, $completed  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $course = '';
    $rc =  [];
    if($course_id >0 ) {
	//$stmt = $dbh->prepare('SELECT `course`   FROM `a_reports`  WHERE `course_id`=?   LIMIT 1 ');
	$stmt = $dbh->prepare('SELECT `name`   FROM `a_cache_course`  WHERE `course_id`=? ');
	$stmt->execute([$course_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $course_name =  $row->name;
	}

	$date_filter = '';
        if( $date1!='' ||  $date2!='' )
           $date_filter = " AND (( `a_date`>='" .$date1. "' ";
        else if( $date1!='' )
           $date_filter = " AND  `a_date`>='" .$date1. "' ";
        if( $date2!='' )
           $date_filter = $date_filter. " AND `a_date`<='" .$date2. "' ";
        if( $date1!='' ||  $date2!='' )
    	    $date_filter = $date_filter. ' ) OR `a_date` IS NULL ) ';

//file_put_contents("/var/www/aisbsk/moodle/lst.txt", $date_filter);
        
        $user_id_0 = 0;
	$stmt = $dbh->prepare('SELECT  DISTINCT  `a_users`.`user_id`, `num`,   `a_reports`.`date`, `result`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`, `subdivision`   FROM  `a_users`  LEFT JOIN `a_reports`  USING(`user_id`) LEFT JOIN  `a_groups_users` USING(`user_id`)  LEFT JOIN  `a_groups` USING(`group_id`)  WHERE  `a_groups`.`course_id`=? '.$date_filter.' ORDER BY `lastname`  LIMIT 500 ');
	$stmt->execute([$course_id]);
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $progress = '';
            if($row->user_id == $user_id_0)
                continue;
            $user_id_0 = $row->user_id;
	    $organization_name = '';
	    $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_organizations`  WHERE `organization_id`=? ');
    	    $stmt2->execute([$row->organization_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$organization_name = $row2->name;
	    }
	    $position_name = '';
	    $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_positions`  WHERE `position_id`=? ');
    	    $stmt2->execute([$row->position_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		$position_name = $row2->name;
	    }

            if($row->result == null && $completed=='true' )
                  continue;

            if($row->result == null){
                $progress = '0';
		$stmt2 = $dbh->prepare('SELECT  `grade`, `modules`, `grade_test`, `modules_test`   FROM `a_progress`  WHERE `user_id`=? AND `course_id`=? ');
                $stmt2->execute([$row->user_id, $course_id]);
		if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
		    if($row2->modules>0)
			$progress = intval($row2->grade / $row2->modules * 100);
                    if($progress > 100)
			$progress = 100;

		    if($row2->modules_test>0)
			$progress_test = intval($row2->grade_test / $row2->modules_test * 100);
                    if($progress_test > 100)
			$progress_test = 100;
		}
            }

	    $rc[] =  ["userId"=>$row->user_id, "num"=>$row->num, "date"=>$row->date, "result"=>$row->result, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "organization"=>$organization_name, "position"=>$position_name, "progress"=>$progress, "progress_test"=>$progress_test ];
	}
    }
    $result = ["status"=>"0", "error"=>'', "role"=>$session_role, "action"=>"course_report", "userId"=>$user_id_session,  "course_id"=>$course_id, "course"=>$course_name, "userReport"=>$rc ];
    return( $result );
}




?>