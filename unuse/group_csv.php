<?php
/**
 * @copyright 2022
 */
$group_id = intval($_GET["groupId"]);
$group = "Группа_$group_id";
if($_GET["groupName"] != '')
      $group = str_replace(';', '', $_GET["groupName"]);

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$group.csv");

require_once 'config.php';
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

//lastname	firstname	email	username	password	idnumber 	address	institution	department	cohort1
    if($group_id>0) {
	//echo  "lastname;firstname;organization;position;login;email;password;cohort1\n";
	echo  "lastname;firstname;institution;username;email;password;cohort1\n";

	$stmt = $dbh->prepare('SELECT `a_users`.`user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`, `password`  FROM `a_users`  LEFT JOIN `a_groups_users` USING(`user_id`)  WHERE `group_id`=?   ORDER BY `lastname`');
	$stmt->execute([$group_id]);
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

	    //$users_list[] =  ["userId"=>$row->user_id,
	    echo  str_replace(';', '', $row->lastname).';'.  str_replace(';', '', $row->firstname).' '.str_replace(';', '', $row->middlename).';'. str_replace(';', '', $organization_name).';'. str_replace(';', '', $row->login).';'. str_replace(';', '', $row->email).';'. str_replace(';', '', $row->password).';'. str_replace(';', '', $group)."\n";
       }



   }

?>