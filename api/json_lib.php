<?php
/**
 * @copyright 2019   
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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




require_once('../config.php');
require_once 'lib.php';

if( $db_config == 'helper')
{
    $dbhost = $cfg_helper->host;
    $dbuser = $cfg_helper->user;
    $dbpassword = $cfg_helper->password;
    $dbname = $cfg_helper->name;
}
else {
    $dbhost = $cfg->host;
    $dbuser = $cfg->user;
    $dbpassword = $cfg->password;
    $dbname = $cfg->name;
}

/*
$dbhost_session = $cfg->host;
$dbuser_session = $cfg->user;
$dbpassword_session = $cfg->password;
$dbname_session = $cfg->name;
*/

$session_role = '';

$userid = 0;    // User id.




try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword );  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


/*
if($db_config != '' && $db_config != 'main') {
    try {  
        $dbh_session = new PDO("mysql:host=$dbhost_session;dbname=$dbname_session;charset=utf8", $dbuser_session, $dbpassword_session );  
    }  
    catch(PDOException $e) {  
        echo $e->getMessage();  
    }
}
else {
        $dbh_session = $dbh;
}
*/

/*
$is_siteadmin = 0;
$customer = 0;
session_start();
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
*/

$_JSON =  json_decode(file_get_contents('php://input'), true);
foreach ($_JSON as $key => $value) {
    $api_function = $key;
    $api_arg = $value;
    break; 
}



function objects_list($where, $conditions, $search, $page) {
    global $api_arg;
    global $user_id_session, $session_role;
    //global $user_id_session, $is_siteadmin, $customer;
    global $dbh;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search, $db_search_limit, $dbh;
    global $page_size;
    global $simple_search;

    $offset=0;
    if( $page>1 ){
	    $offset = intval($page-1)*$page_size;
    }
    $num_pages = 0;

    $rc_list = [];
    //$rc_list[] = ["userId"=>0, "login"=>'', "name"=>'', "fullname"=>''];

    $fields = '';
    foreach($db_fields_list as $value){
      $fields = $fields . '`' . $value . '` ,';
    }
    $fields = trim($fields, " ,");

    $where_str = ''; 
    if($where != ''){
        $where_str = " WHERE ". $where ." ";
    }
    else if($conditions != ''){
        $where_str = " WHERE ". $conditions ." ";
    }

    
    if($where != '' && $search != ''){
        $where_str = $where_str . "AND (";
    }
    else if( $search != ''){
        $where_str = " WHERE (";
    }

    if(isset($simple_search) && intval($simple_search)>0)
        $serch_char1 = ''; 
    else
        $serch_char1 = '%'; 

    $limit_str ='';
    if( $search != ''){
	    foreach($db_fields_search as $value){
                $where_str = $where_str ."`". $value . "` LIKE '". $serch_char1 . addslashes($search) . "%' OR ";
	    }
	    $where_str  = trim($where_str, "OR ");
        $where_str = $where_str . ") ";

    }
    if( $page >0  ){
            $limit_str =  'LIMIT '.$page_size.' OFFSET ' . $offset;
    }
    else if( $db_search_limit >0 ){
            $limit_str =  ' LIMIT ' . intval($db_search_limit) . ' ';
    }
//file_put_contents("lst.txt", print_r($limit_str, true) );

    $stmt = $dbh->query('SELECT '.$fields.' FROM `'.$db_table.'` '.$where_str.' ORDER BY '.$db_order. $limit_str );
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
	$rc = [];
	foreach($db_fields_list as $value){
    	    $rc["$value"] = trim($row["$value"]) ;
	}
	    $rc_list[] =  $rc;
    }
    $status = 0;


    $num_pages = 0;
    if( $page > 0 ){
	    $stmt0 = $dbh->query('SELECT count(*)  as `count` FROM `'.$db_table.'` '.$where_str );
        if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	          $members = $row0->count;
        }
        $num_pages = intval(($members+0.5)/$page_size+1);
        if( $page > $num_pages ){
	          $page = $num_pages;
        }
    }

//    $result = ["role"=>$session_role, "action"=>"list",  "list"=>$rc_list, "stmt"=>$stmt ];
    $result = ["role"=>$session_role, "action"=>"list",  "userId"=>$user_id_session, "status"=>$status,   "list"=>$rc_list, "numPages"=>$num_pages, "page"=>$page  ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function object($object_id, $where) {
    global $user_id_session, $session_role;
    global $api_arg;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    $where_str = ''; 
    if($where != ''){
        $where_str = " AND ". $where ." ";
    }

    $rc_list = [];
    foreach ($db_fields as $value) {
        $rc_list["$value"] = '';
    }
    $status = 1;
    if($object_id >= 0) {
        $fields_list = '';
        foreach ($db_fields as $value) {
            $fields_list = $fields_list . '`'.$value.'` ,';
        }
        $fields_list = substr($fields_list, 0, -1); 
//        if($is_siteadmin){ 
        $stmt = $dbh->prepare('SELECT '.$fields_list.' FROM `'.$db_table.'` WHERE `'.$db_index.'`=? '.$where_str.' LIMIT 1');
        $stmt->execute([$object_id]);
//    }
//    else { 
//        $stmt = $dbh->prepare('SELECT '.$fields_list.' FROM `'.$db_table.'` WHERE `'.$db_index.'`=? and `customer_id`=?');
//        $stmt->execute([$object_id, $customer]);
//    } 
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
	    $rc_list = $row;
            $status = 0;
    }
    }
    //$result = ["isSysAdmin"=>$is_siteadmin, "action"=>"object", "customerId"=>$customer, "userId"=>$user_id_session, "ststus"=>$ststus, "stmt"=>$stmt, "object_id"=>$object_id,   "result"=>$rc_list];
    $result = ["role"=>$session_role, "action"=>"object",  "userId"=>$user_id_session, "status"=>$status,   "result"=>$rc_list];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function object_update($object_id) {
    global $api_arg, $user_id_session, $is_siteadmin, $customer;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    $update_list = '';
    $update_arr = []; 
    foreach ($db_fields as $value) {
        if($value == $db_index)
	        continue;
	    if(isset($api_arg[$value])) {
    	    $update_list = $update_list .'`'. $value . '`=?, ';
    	    //if($api_arg[$value] == 'true' || $api_arg[$value] == 'false')
    	    $update_arr[] = trim(strip_tags($api_arg[$value], ['<br>', '<b>']));
	}
	//else
    	//    $update_arr[] = '';
    }
    $update_list = trim($update_list, " ,");
    //$update_list = substr($update_list, 0, -2); 
    $update_arr[] = $object_id;

    $stmt = $dbh->prepare('UPDATE `'.$db_table.'` SET '.$update_list.'  WHERE `'.$db_index.'`=? ');
    $stmt->execute( $update_arr );
    $status = "0";
    $error = "";
    if(!$stmt) {
            $status = "4";
            $error = 'error 2: '. $dbh->errorInfo()[2];
    }
//file_put_contents("lst2.txt", json_encode($update_arr, JSON_UNESCAPED_UNICODE));

/*

    if($is_siteadmin){ 
        $stmt = $dbh->prepare('UPDATE `users` SET `login`=?, `name`=?, `fullname`=?, `customer_id`=?  WHERE `user_id`=?');
        $stmt->execute([$login, $name, $fullname, $customer_id, $a_user_id ]);
        $p_user_id = $a_user_id; 
    }
    else { 
        $stmt = $dbh->prepare('UPDATE `users` SET  `fullname`=?   WHERE `user_id`=? and `customer_id`=?');
        $stmt->execute([ $fullname,  $a_user_id, $customer ]);
        $p_user_id = $user_id; 
    }
    }
    else if($is_siteadmin){
    $role = '';
    if($customer=='' || $customer==0) {
        $role = 'admin';
    }
    //$stmt = $dbh->prepare('REPLACE INTO `users`(`user_id`, `name`, `fullname`) VALUES ( ?, ?, ?)');
        //$stmt = $dbh->prepare('INSERT INTO `users`(`user_id`,`name`, `fullname`) VALUES ( ?, ?)  ON DUPLICATE KEY UPDATE `fullname` = VALUES(`fullname`), `name` = VALUES(`name`)');
        $stmt = $dbh->prepare('INSERT INTO `users`(`login`, `name`, `fullname`, `customer_id`, `role`) VALUES ( ?, ?, ?, ?, ?)');
    $stmt->execute([$login, $name, $fullname, $customer, $role]);
    $p_user_id = $dbh->lastInsertId(); 
    }
    $rc2=0;
    if($p_user_id>0 && $passwd1!='' && $passwd2!=''){
    $rc2=1;
    if($passwd1 == $passwd2){
        $stmt2 = $dbh->prepare('UPDATE `users` SET `password`=?  WHERE `user_id`=?');
        $stmt2->execute( [md5($passwd1), $p_user_id ]);
        if($stmt2) 
	$rc2=0;
    }
    }
    if(!$stmt) {
            $rc_list = [ "status"=>"4", "error"=>'error 2: '. $dbh->errorInfo()[2] ];
    }
    else if($rc2) {
            $rc_list = [ "status"=>"7", "error"=>'error Не совпадает Пароль и Пароль повторно ' ];
    }
    else {
            $rc_list = [ "status"=>"0", "error"=> '' ];
    }

*/
    $result = ["role"=>$session_role, "action"=>"object",  "action"=>"update", "status"=>$status, "error"=>$error,  "result"=>$object_id];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function object_insert($object_id) {
    global $api_arg, $user_id_session, $is_siteadmin, $customer;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    $insert_list = '';
    $val_list = '';
    $insert_arr = []; 
    foreach ($db_fields as $value) {
        if($value == $db_index)
               continue;

        $insert_list = $insert_list  .'`'.  $value . '`, ';
        $val_list = $val_list . '?, ';
        
	    if(isset($api_arg[$value]))
    	    $insert_arr[] = trim(strip_tags($api_arg[$value],  ['<br>', '<b>']));
	    else
    	    $insert_arr[] = '';
    }
    //$insert_list = substr($insert_list, 0, -2); 
    $insert_list = trim($insert_list, " ,");
    //$val_list = substr($val_list, 0, -2); 
    $val_list = trim($val_list, " ,");

    if($object_id >0 ){
        $insert_list = $insert_list  .', `'.  $db_index . '` ';
        $val_list = $val_list . ', ? ';
        $insert_arr[] = $object_id;
    }

//file_put_contents("lst.txt", print_r($insert_list, true) );
//file_put_contents("lst1.txt", print_r($val_list, true) );

    $stmt = $dbh->prepare('INSERT INTO `'.$db_table.'` ( '.$insert_list.') VALUES('.$val_list.')  ');
    $stmt->execute( $insert_arr );
    $object_rc = $dbh->lastInsertId(); 

    $status = "0";
    $error = "";
    if(!$stmt) {
            $status = "4";
            $error = 'error 2: '. $dbh->errorInfo()[2];
    }

    $result = ["role"=>$session_role,   "action"=>"insert", "status"=>$status, "error"=>$error,  "result"=>$object_rc];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function object_replace($object_id) {
    global $api_arg, $user_id_session, $is_siteadmin, $customer;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    if($object_id <=0 ){
        $result = ["role"=>$session_role,  "action"=>"replace", "status"=>1, "error"=>'not Id',  "result"=>''];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    $insert_list = '';
    $val_list = '';
    $insert_arr = []; 
    foreach ($db_fields as $value) {
        if($value == $db_index)
               continue;

        $insert_list = $insert_list  .'`'.  $value . '`, ';
        $val_list = $val_list . '?, ';
        
    	if(isset($api_arg[$value]))
    	    $insert_arr[] = trim(strip_tags($api_arg[$value],  ['<br>', '<b>']));
	    else
    	    $insert_arr[] = '';
    }
    //$insert_list = substr($insert_list, 0, -2); 
    $insert_list = trim($insert_list, " ,");
    //$val_list = substr($val_list, 0, -2); 
    $val_list = trim($val_list, " ,");

    $insert_list = $insert_list  .', `'.  $db_index . '` ';
    $val_list = $val_list . ', ? ';
    $insert_arr[] = $object_id;

    $stmt = $dbh->prepare('DELETE FROM `'.$db_table.'` WHERE `'.  $db_index .'`=?  ');
    $stmt->execute( [$object_id] );

    $stmt = $dbh->prepare('INSERT INTO `'.$db_table.'` ( '.$insert_list.') VALUES('.$val_list.')  ');
    $stmt->execute( $insert_arr );
    $object_rc = $dbh->lastInsertId(); 

    $status = "0";
    $error = "";
    if(!$stmt) {
            $status = "4";
            $error = 'error 2: '. $dbh->errorInfo()[2];
    }

    $result = ["role"=>$session_role, "action"=>"object",   "status"=>$status, "error"=>$error,  "result"=>$object_rc];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function object_delete($object_id, $where) {
    global $api_arg, $user_id_session, $is_siteadmin, $customer, $dbh, $session_role;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    $where_str = ''; 
    if($where != ''){
        $where_str = " AND ". $where ." ";
    }

    if( $object_id>0 ) {
	$stmt = $dbh->prepare('DELETE FROM `'.$db_table.'` WHERE `'.$db_index.'`=? '. $where_str );
	$stmt->execute([$object_id]);
    
	if(!$stmt) {
            $result = ["role"=>$session_role, "action"=>"object",  "status"=>"4", "error"=>'error 2: '. $dbh->errorInfo()[2],  "action"=>"user_delete", "result"=>$object_id  ];
	}
	else {
            $result = ["role"=>$session_role, "action"=>"object",  "status"=>"0", "error"=>'',  "action"=>"user_delete", "result"=>$object_id   ];
	}
    }
    /*else if( $where != '' ) {
        $where_str =  $where ." ";
	$stmt = $dbh->prepare('DELETE FROM `'.$db_table.'` WHERE '. $where_str );
	$stmt->execute([]);
    
	if(!$stmt) {
            $result = ["role"=>$session_role, "action"=>"object",  "status"=>"4", "error"=>'error 2: '. $dbh->errorInfo()[2],  "action"=>"user_delete", "result"=>$object_id  ];
	}
	else {
            $result = ["role"=>$session_role, "action"=>"object",  "status"=>"0", "error"=>'',  "action"=>"user_delete", "result"=>$object_id   ];
	}
    }*/
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function max_index_id(){
    global $api_arg, $user_id_session, $is_siteadmin, $customer, $dbh, $session_role;
    global $db_table, $db_fields, $db_fields_list, $db_index, $db_order, $db_fields_search,  $dbh;

    $error = '';
    $object_id = 0;
    $stmt = $dbh->query('SELECT MAX(`'.$db_index.'`) as `max_id` FROM `'.$db_table.'` ' );
//file_put_contents("lst.txt", print_r($stmt, true));
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
           $object_id = $row->max_id;
           $status = 0;
    }
    $result = ["role"=>$session_role,   "action"=>"max_index_id", "status"=>$status, "error"=>$error,  "result"=>$object_id];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


$session_counterparty_id = -1;
$session_account_id = -1;
$session_role = '';
$session_login = '';
$stmt = $dbh_a->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
//$stmt = $dbh->prepare('SELECT `account_id`, `counterparty_id`  FROM `a_session`  WHERE `token`=? ');
$stmt->execute([ $api_arg['sessionId'] ]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
     $session_counterparty_id = $row->counterparty_id;
     $session_account_id = $row->account_id;
     $session_role = $row->role;
     $session_login = $row->login;
}





api_journal($db_table, $api_function, $api_arg, $session_login);
if($api_function=='list'){
    if( isset($api_arg["where"]) &&  $api_arg["where"]!='' ) 
	$where = $api_arg["where"];
    else
	$where = '';
	
	$conditions = '';
	if( isset($api_arg["conditions"]) && $api_arg["conditions"]!=''  ){
	    $conditions = ' 1';
	    $conditions_a = $api_arg["conditions"];
	    foreach ($api_arg["conditions"] as $key => $value)
	        $conditions = $conditions . ' AND `'.$key.'`= '.intval($value);
	}
	
    if( isset($api_arg["search"]) &&  $api_arg["search"]!='' ) 
	$search = $api_arg["search"];
    else
	$search = '';
	
//file_put_contents("lst.txt", print_r($conditions, true) );
	objects_list($where, $conditions, $search, intval($api_arg["page"]));
}
else if($api_function=='object'    && intval($api_arg["objectId"])>=0 ){
    if( isset($api_arg["where"]) &&  $api_arg["where"]!='' ) 
	$where = $api_arg["where"];
    else
	$where = '';

    object(intval($api_arg["objectId"]), $where);
}
else if($api_function=='update'     && intval($api_arg["objectId"])>0 ){
    object_update(intval($api_arg["objectId"]) );
}
//strip_tags($text, '<br>');
else if($api_function=='insert'  ){
    if(intval($api_arg["objectId"])>0) 
    	object_insert(intval($api_arg["objectId"]));
    else
	    object_insert(0);
}
else if($api_function=='save'     && intval($api_arg["objectId"])>=0 ){
    if(intval($api_arg["objectId"])>0) 
	    object_update(intval($api_arg["objectId"]) );
    else
	    object_insert(0 );
}
else if($api_function=='replace'     && intval($api_arg["objectId"])>0 ){
    object_replace(intval($api_arg["objectId"]) );
}
else if($api_function=='delete'     && (intval($api_arg["objectId"])>0 || trim($api_arg["where"])!='') ){
    if( isset($api_arg["where"]) &&  trim($api_arg["where"])!='' ) 
	    $where = $api_arg["where"];
    else
	    $where = '';

    object_delete(intval($api_arg["objectId"]), $where);
}
else if($api_function=='max_index_id' ){
    max_index_id();
}

else {
    $result = array('$_JSON'=>$_JSON, 'POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

//htmlspecialchars("", ENT_QUOTES);
//strip_tags($text, ['<br>', '<b>']);