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
$userid = 0;    // User id.

/*if (!$user = $DB->get_record('user', array('id' => $userid))) {
    print_error('invaliduserid');
}




if(is_siteadmin() )
    $is_admin = "true";
else    
    $is_admin = "";




if (!isset($_SESSION['user_id']) or $_SESSION['user_id']==0 or $_SESSION['user_id']=='') {
    $user_id_session = 0;
    $customer_id  = 0;

    $rc_list = [ "status"=>"1", "error"=>'No auth' ];
    $result = ["isSysAdmin"=>0, "customerId"=>0, "userId"=>0, "action"=>"action", "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    return 0;
}

$user_id_session = $_SESSION['user_id'];
$stmt = $dbh->prepare('SELECT `customer_id`,`role` FROM `users` WHERE `user_id`=? ');
$stmt->execute([$user_id_session]); 
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
    $customer = $row->customer_id;
    if($row->role == 'admin')
    $is_siteadmin = 1;
}

#echo $user_id; 
*/


//$api_function = '';
//$api_arg = [];

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}

//$api_function='users_list';

/* USERS */

//function users_list($is_search, $lastname, $firstname, $middlename, $facultet_id, $form_id, $group_id, $organization, $page) {
function users_list($is_search, $lastname, $firstname, $middlename,  $organization, $page) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $offset=0;
    if( $page>1 ){
	$offset = intval($page)*100;
    }
    else {
	 $page=1;
    }

    $users_list = [];
    $members = 0;
    $addsearch = '';

    if($is_search >0){
/*        if($facultet_id>0){
            $addsearch = $addsearch . ' AND `facultet_id`=' . $facultet_id . ' ';
        }
        if($form_id>0){
            $addsearch = $addsearch . ' AND `form_id`=' . $form_id . ' ';
        }
        if($organization>0){
            $addsearch = $addsearch . ' AND `organization_id`=' . $organization . ' ';
        }
*/
	if($organization>0){
	    $stmt = $dbh->prepare('SELECT `user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`  FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? AND `organization_id`=?  ORDER BY `lastname` LIMIT 100  OFFSET ' . "$offset");
    	    $stmt->execute(["$lastname%", "$firstname%", "$middlename%", $organization,]);
	}
	else {
	    $stmt = $dbh->prepare('SELECT `user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`  FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? '. $addsearch .'  ORDER BY `lastname` LIMIT 100  OFFSET  ' . "$offset");
    	    $stmt->execute(["$lastname%", "$firstname%", "$middlename%"]);
	}
    }
    else {
	$stmt = $dbh->query('SELECT `user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`  FROM `a_users`   ORDER BY `lastname`  LIMIT 100  OFFSET  ' . "$offset");
    }
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
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

	    $users_list[] =  ["userId"=>$row->user_id, "login"=>$row->login, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "organization"=>$organization_name, "position"=>$position_name ];
    }


    if($is_search >0){
	if($organization>0){
	    $stmt0 = $dbh->prepare('SELECT SELECT count(*) as `count`    FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? AND `organization_id`=?');
    	    $stmt0->execute(["$lastname%", "$firstname%", "$middlename%", $organization]);
	}
	else {
	    $stmt0 = $dbh->prepare('SELECT count(*) as `count`  FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? ');
    	    $stmt0->execute(["$lastname%", "$firstname%", "$middlename%"]);
	}
    }
    else {
	$stmt0 = $dbh->query('SELECT count(*) as `count`   FROM `a_users`   ');
    }
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	$members = $row0->count;
    }
    $num_pages = intval(($members)/100);
    if( $page > $num_pages ){
	    $page = $num_pages;
    }

    //$result = ["role"=>$session_role, "action"=>"users_list", "userId"=>$user_id_session, "result"=>$rc_list, "numPages"=>$members];
    $result = ["role"=>$session_role, "action"=>"users_list",  "list"=>$users_list,  "search"=>$is_search, "numPages"=>$num_pages, "page"=>$page];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function user_object($user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    /*if($user_id == 0)
        $a_user_id = $user_id_session;
    else
        $a_user_id = $user_id;
    */

    $rc =  ["user_id"=>0,  "lastname"=>'', "firstname"=>'', "middlename"=>'', "login"=>'',  "password"=>'', "organization_id"=>0, "position_id"=>0, "organization"=>'', "position"=>'', "subdivision"=>'', "date_of_birth"=>'', "cart_id"=>'' ];
    if($user_id >0 ) {
	//$stmt = $dbh->prepare('SELECT  `user_id`,  `lastname`, `firstname`, `middlename`, `email`, `login`, `password`,  `facultet_id`, `form_id`,   `organization_id`, `position_id`, `subdivision`, `date_of_birth`, `cart_id`  FROM `a_users`   WHERE `user_id`=?');
	$stmt = $dbh->prepare('SELECT  `user_id`,  `lastname`, `firstname`, `middlename`, `email`, `login`, `password`,     `organization_id`, `position_id`, `subdivision`, `date_of_birth`, `cart_id`  FROM `a_users`   WHERE `user_id`=?');
	$stmt->execute([$user_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            $group_id = 0;
	    $stmt2 = $dbh->prepare('SELECT  `group_id`   FROM `a_groups_users`   WHERE `user_id`=?');
	    $stmt2->execute([$user_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $group_id = $row2->group_id;
            }
            $group_name = '';
	    $stmt2 = $dbh->prepare('SELECT  `name`   FROM `a_groups`   WHERE `group_id`=?');
	    $stmt2->execute([$group_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $group_name = $row2->name;
            }
/*            $facultet_name =  '';
	    $stmt2 = $dbh->prepare('SELECT  `name`   FROM `a_facultet`   WHERE `facultet_id`=?');
	    $stmt2->execute([$row->facultet_id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                $facultet_name = $row2->name;
            }*/
	    $organization_name = '';
            if( $row->organization_id >0 ){
	      $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_organizations`  WHERE `organization_id`=? ');
    	      $stmt2->execute([$row->organization_id]);
	      if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	  	  $organization_name = $row2->name;
	      }
            }
	    $position_name = '';
            if($row->position_id >0 ){
	      $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_positions`  WHERE `position_id`=? ');
    	      $stmt2->execute([$row->position_id]);
	      if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
	  	  $position_name = $row2->name;
	      }
            }

	    //$rc =  ["user_id"=>$row->user_id, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "email_lms"=>$row->email, "login"=>$row->login,  "password"=>$row->password, "group_id"=>$group_id, "group_name"=>$group_name, "facultet_id"=>$row->facultet_id, "form_id"=>$row->form_id, "facultet_name"=>$facultet_name,  "organization_id"=>$row->organization_id, "position_id"=>$row->position_id, "organization"=>$organization_name, "position"=>$position_name, "subdivision"=>$row->subdivision, "date_of_birth"=>$row->date_of_birth, "cart_id"=>$row->cart_id ];
	    $rc =  ["user_id"=>$row->user_id, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "email_lms"=>$row->email, "login"=>$row->login,  "password"=>$row->password, "group_id"=>$group_id, "group_name"=>$group_name,  "organization_id"=>$row->organization_id, "position_id"=>$row->position_id, "organization"=>$organization_name, "position"=>$position_name, "subdivision"=>$row->subdivision, "date_of_birth"=>$row->date_of_birth, "cart_id"=>$row->cart_id ];
	}
    }
    $result = ["role"=>$session_role, "action"=>"user", "userId"=>$user_id_session, "userInfo"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

/*
function user_view($cart_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($cart_id >0 ) {
	$stmt = $dbh->prepare('SELECT  `user_id`   FROM `a_users`   WHERE `cart_id`=?');
	$stmt->execute([$cart_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    return user_detalies($row->user_id);
	}
    }
    $rc =  ["user_id"=>0,  "lastname"=>'', "firstname"=>'', "middlename"=>'', "login"=>'',  "password"=>'', "organization_id"=>0, "position_id"=>0, "organization"=>'', "position"=>'', "subdivision"=>'', "date_of_birth"=>'' ];
    $result = ["role"=>$session_role, "action"=>"user", "userId"=>$user_id_session, "userInfo"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
*/


function user_search( $group_id,  $lastname, $firstname, $middlename, $organization_id,  $subdivision, $position, $date_of_birth,  $email, $login) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    //if($lastname=='' &&  $email=='' &&  $login=='' ){
    if($lastname=='' ){
	$result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"user_serch",   "userInfo"=>["userId"=>0]];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
        return;
    }

    $rc = ["userId"=>0];
    if($organization_id > 0) {
	$stmt = $dbh->prepare('SELECT  `a_users`.`user_id`,  `lastname`, `firstname`, `middlename`, `email`, `login`, `password`,  `organization_id`, `position_id`, `subdivision`, `date_of_birth`  FROM `a_users`    LEFT JOIN `a_groups_users` USING(`user_id`) LEFT JOIN `a_positions` USING(`position_id`)  WHERE (`a_groups_users`.`group_id` IS NULL or `a_groups_users`.`group_id` != ?) and `lastname` LIKE ? and `firstname` LIKE ? and `middlename` LIKE ?   and `organization_id`=?  and  `subdivision` LIKE ?  and `a_positions`.`name` LIKE ?  and `date_of_birth`  LIKE ?    LIMIT 2');
	$stmt->execute([$group_id, "$lastname%", "$firstname%", "$middlename%", $organization_id, "$subdivision%", "$position%",  "$date_of_birth%" ]);
	//$stmt = $dbh->prepare('SELECT  `a_users`.`user_id`,  `lastname`, `firstname`, `middlename`, `email`, `login`, `password`,  `organization_id`, `position_id`, `subdivision`, `date_of_birth`  FROM `a_users`    LEFT JOIN `a_groups_users` USING(`user_id`)   WHERE (`a_groups_users`.`group_id` IS NULL or `a_groups_users`.`group_id` != ?) and `lastname` LIKE ? and `firstname` LIKE ? and `middlename` LIKE ?   and `organization_id`=?  and  `subdivision` LIKE ? and `date_of_birth`  LIKE ?    LIMIT 2');
	//$stmt->execute([$group_id, "$lastname%", "$firstname%", "$middlename%", $organization_id, "$subdivision%",  "$date_of_birth%" ]);
    }
    else {
	$stmt = $dbh->prepare('SELECT  `a_users`.`user_id`,  `lastname`, `firstname`, `middlename`, `email`, `login`, `password`,  `organization_id`, `position_id`, `subdivision`, `date_of_birth`  FROM `a_users`    LEFT JOIN `a_groups_users` USING(`user_id`)   WHERE (`a_groups_users`.`group_id` IS NULL or `a_groups_users`.`group_id` != ?) and `lastname` LIKE ? and `firstname` LIKE ? and `middlename`  LIKE ?   and `date_of_birth` LIKE ? LIMIT 2');
	$stmt->execute([$group_id, "$lastname%", "$firstname%", "$middlename%",  "$date_of_birth%" ]);
    }
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
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

	    $rc =  ["userId"=>$row->user_id, "lastname"=>$row->lastname, "firstname"=>$row->firstname, "middlename"=>$row->middlename, "email_lms"=>$row->email, "login"=>$row->login,  "password"=>$row->password, "organization_id"=>$row->organization_id, "position_id"=>$row->position_id, "organization"=>$organization_name, "position"=>$position_name, "subdivision"=>$row->subdivision, "date_of_birth"=>$row->date_of_birth ];
    }
    $is_single = 1;
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	if($row->user_id > 0)
	    $is_single = 0;
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"user_serch", "userId"=>$user_id_session, "userInfo"=>$rc, "userSingle"=>$is_single ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function user_report($user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc =  [];
    if($user_id >0 ) {
	$stmt = $dbh->prepare('SELECT `num`, `course`, `course_id`, `date`, `result`  FROM `a_reports`   WHERE `user_id`=?');
	$stmt->execute([$user_id]);
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $rc[] =  ["num"=>$row->num, "course"=>$row->course, "course_id"=>$row->course_id, "date"=>$row->date, "result"=>$row->result ];
	}
    }
    $result = ["role"=>$session_role, "action"=>"user_report", "userId"=>$user_id_session, "userReport"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}






//function user_save($user_id, $email_lms, $login, $lastname, $firstname, $middlename,  $password, $organization_id, $position_id_arg, $position_name, $subdivision, $date_of_birth, $facultet_id, $form_id,  $group_id, $cart_id ) {
function user_save($user_id, $email_lms, $login, $lastname, $firstname, $middlename,  $password, $organization_id, $position_id_arg, $position_name, $subdivision, $date_of_birth, $cart_id ) {
    global $api_arg, $user_id_session, $session_role, $EmailDomain, $AccountPrefix, $dbh;

    if($lastname=='' ||  $firstname==''){
	$result = ["status"=>1, "error"=>'Не все обязательные поля заполнены',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>0]];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
        return;
    }
    
    $position_id = 0;
    if(trim($position_name) != ''){
	$count_p = 0;
	$stmt = $dbh->prepare("select `position_id` FROM  `a_positions`  WHERE `name`=?   ");
        $stmt->execute([trim($position_name)]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $count_p = 1;
	    $position_id = $row->position_id;
	}
	if($count_p == 0 && trim($position_name) != '') {
	    $stmt = $dbh->prepare("INSERT INTO `a_positions`(`name`) VALUES(?)");
    	    $stmt->execute([trim($position_name)]);
	    $position_id = $dbh->lastInsertId(); 
	}

    }

    if($user_id>0){
        //$stmt = $dbh->prepare('UPDATE `a_users` SET `lastname`=?, `firstname`=?,  `middlename`=?,  `facultet_id`=?,  `form_id`=?,  `organization_id`=?,  `subdivision`=?,  `position_id`=?, `date_of_birth`=?   WHERE `user_id`=?');
        //$stmt->execute([$lastname, $firstname, $middlename, $facultet_id, $form_id, $organization_id, $subdivision, $position_id, $date_of_birth,     $user_id ]);
        $stmt = $dbh->prepare('UPDATE `a_users` SET `lastname`=?, `firstname`=?,  `middlename`=?,    `organization_id`=?,  `subdivision`=?,  `position_id`=?, `date_of_birth`=?   WHERE `user_id`=?');
        $stmt->execute([$lastname, $firstname, $middlename,  $organization_id, $subdivision, $position_id, $date_of_birth,     $user_id ]);
	if( $password !=' ' ){
    	    $stmt = $dbh->prepare('UPDATE `a_users` SET `password`=?  WHERE `user_id`=?');
    	    $stmt->execute([$password,     $user_id ]);
	}
        $p_user_id = $user_id; 

/*        if($group_id >0) {
    	    $stmt = $dbh->prepare('DELETE FROM `a_groups_users` WHERE `user_id` = ? ');
	    $stmt->execute([$p_user_id]);

    	    $stmt = $dbh->prepare('INSERT INTO `a_groups_users`(`group_id`,  `user_id`) VALUES ( ?, ?)');
	    $stmt->execute([$group_id,  $p_user_id]);
	}*/
    }
    else {
	$exist_user_id = 0;
	if($organization_id > 0) {
	    $stmt = $dbh->prepare('SELECT  `user_id`   FROM `a_users` WHERE  `lastname` LIKE ? and `firstname` LIKE ? and  `middlename` LIKE ?   and `organization_id`=? and `position_id`=? and `subdivision` LIKE ? and `cart_id` LIKE ?  LIMIT 2');
	    $stmt->execute(["$lastname%", "$firstname%", "$middlename%", $organization_id, $position_id, $subdivision, $cart_id]);
	}
	else {
	    $stmt = $dbh->prepare('SELECT  `user_id`  FROM `a_users` WHERE  `lastname` LIKE ? and `firstname` LIKE ? and  `middlename` LIKE ? and `cart_id` LIKE ?  LIMIT 2');
	    $stmt->execute(["$lastname%", "$firstname%", "$middlename%", $cart_id]);
	}
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $exist_user_id = $row->user_id;
	}
	if( $exist_user_id > 0 ){
	    $result = ["status"=>1, "error"=>'Учетная запись уже существует',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>$exist_user_id ] ];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
	    return;
	} 

        //$stmt = $dbh->prepare('INSERT INTO `a_users`(`lastname`, `firstname`,  `middlename`, `facultet_id`,  `form_id`, `organization_id`,  `position_id`, `subdivision`, `date_of_birth`, `cart_id`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	//$stmt->execute([$lastname, $firstname, $middlename, $facultet_id, $form_id, $organization_id, $position_id, $subdivision, $date_of_birth, $cart_id]);
        $stmt = $dbh->prepare('INSERT INTO `a_users`(`lastname`, `firstname`,  `middlename`,  `organization_id`,  `position_id`, `subdivision`, `date_of_birth`, `cart_id`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$stmt->execute([$lastname, $firstname, $middlename,  $organization_id, $position_id, $subdivision, $date_of_birth, $cart_id]);
	$p_user_id = $dbh->lastInsertId(); 
	if($p_user_id<=0){
	    $result = ["status"=>2, "error"=>'Ошибка DB',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>0]];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	    return;
	}

	$passwd_chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
	$login = $AccountPrefix . str_pad("$p_user_id", 5, '0', STR_PAD_LEFT); 
	$email_lms = $login . $EmailDomain;
	$shfl = str_shuffle($passwd_chars);
	$password = substr($shfl,0,8);
 
        $stmt2 = $dbh->prepare('UPDATE `a_users` SET `email`=?, `login`=?, `password`=?  WHERE `user_id`=?');
        $stmt2->execute([$email_lms, $login, $password,  $p_user_id ]);

        /*if($group_id >0) {
    	    $stmt = $dbh->prepare('INSERT INTO `a_groups_users`(`group_id`,  `user_id`) VALUES ( ?, ?)');
	    $stmt->execute([$group_id,  $p_user_id]);
	}*/
    }

    //$stmt = $dbh->prepare('REPLACE INTO `users`(`user_id`, `name`, `fullname`) VALUES ( ?, ?, ?)');
        //$stmt = $dbh->prepare('INSERT INTO `users`(`user_id`,`name`, `fullname`) VALUES ( ?, ?)  ON DUPLICATE KEY UPDATE `fullname` = VALUES(`fullname`), `name` = VALUES(`name`)');



    
    /* if($p_user_id>0 && $passwd1!='' && $passwd2!=''){
    $rc2=1;
    if($passwd1 == $passwd2){
        $stmt2 = $dbh->prepare('UPDATE `users` SET `password`=?  WHERE `user_id`=?');
        $stmt2->execute( [md5($passwd1), $p_user_id ]);
        if($stmt2) 
	$rc2=0;
    else if($rc2) {
            $rc_list = [ "status"=>"7", "error"=>'error Не совпадает Пароль и Пароль повторно ' ];
    }
    }*/ 


    /*if(!$stmt) {
            $rc_list = [ "status"=>"4", "error"=>'error 2: '. $dbh->errorInfo()[2] ];
    }
    }
    else {
            $rc_list = [ "status"=>"0", "error"=> '' ];
    }*/

    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "userInfo"=>["userId"=>$p_user_id] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function user_link($group_id,  $user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    if($user_id>0 && $group_id>0) {
        $stmt = $dbh->prepare('DELETE FROM `a_groups_users` WHERE `group_id`=?  AND  `user_id`=? ');
	$stmt->execute([$group_id,  $user_id ]);

        $stmt = $dbh->prepare('INSERT INTO `a_groups_users`(`group_id`,  `user_id`) VALUES ( ?, ?)');
	$stmt->execute([$group_id,  $user_id ]);
    }


    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"user_link",  "userId"=>$user_id_session ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function user_del($user_id) {
    global $api_arg, $user_id_session, $session_role, $customer, $dbh;

    $status = "0";
    $error = '';

    if($user_id >0) {
        $stmt = $dbh->prepare('DELETE FROM `a_groups_users` WHERE  `user_id`=? ');
	$stmt->execute([$user_id ]);

	$stmt = $dbh->prepare('DELETE FROM `a_users`  WHERE `user_id`=?');
	$stmt->execute([$user_id]);

	if(!$stmt) {
            $status = "4";
	    $error = 'error 2: '. $dbh->errorInfo()[2];
	}
    }

    $result = ["status"=>$status, "error"=>$error, "role"=>$session_role, "action"=>"user_del", "customerId"=>$customer, "userId"=>$user_id_session];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





if($api_function=='list'){
//    users_list($api_arg["search"], $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"], intval($api_arg["facultetId"]),  intval($api_arg["formId"]),  intval($api_arg["groupId"]), intval($api_arg["organization"]), $api_arg["page"] );
    users_list($api_arg["search"], $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"],  intval($api_arg["organization"]), $api_arg["page"] );
}
else if($api_function=='object'  ){
    user_object(intval($api_arg["userId"]));
}
else if($api_function=='user_view'  ){
    user_detalies(intval($api_arg["cartId"]));
}
else if($api_function=='save' ){
//    user_save(intval($api_arg["userId"]), $api_arg["email_lms"],  $api_arg["login"], $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"],  $api_arg["password"], $api_arg["organization_id"], $api_arg["position_id"], $api_arg["position_name"], $api_arg["subdivision"], $api_arg["date_of_birth"], intval($api_arg["facultetId"]), intval($api_arg["formId"]), intval($api_arg["groupId"]), $api_arg["cartId"] );
    user_save(intval($api_arg["userId"]), $api_arg["email_lms"],  $api_arg["login"], $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"],  $api_arg["password"], $api_arg["organization_id"], $api_arg["position_id"], $api_arg["position_name"], $api_arg["subdivision"], $api_arg["date_of_birth"],  $api_arg["cartId"] );
}
else if($api_function=='delete' ){
    user_del( intval($api_arg["userId"]) );
}
else if($api_function=='search' ){
//    user_search( intval($api_arg["groupId"]),  $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"],  intval($api_arg["organizationId"]),  $api_arg["subdivision"], $api_arg["$position"], $api_arg["date_of_birth"],  $api_arg["email"],  $api_arg["login"] );
    user_search( intval($api_arg["groupId"]),  $api_arg["lastname"], $api_arg["firstname"], $api_arg["middlename"],  intval($api_arg["organizationId"]),  $api_arg["subdivision"], $api_arg["$position"], $api_arg["date_of_birth"],  $api_arg["email"],  $api_arg["login"] );
}
else if($api_function=='user_link' ){
    user_link(intval($api_arg["groupId"]), intval($api_arg["userId"]));
}
else if($api_function=='user_report'){
    user_report( intval($api_arg["userId"]) );
}


else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
