<?php
/**
 * @copyright 2022
 */
$group_id = intval($_GET["groupId"]);
$group = "Группа_$group_id";
if($_GET["groupName"] != '')
      $group = str_replace(';', '', $_GET["groupName"]);

header("Content-Disposition: attachment; filename=$group.html");
header("Content-Type: application/vnd.ms-excel");

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

echo "<!doctype html>\n";
echo '<html lang="ru">';
echo '  <head>';
echo '    <meta charset="utf-8">';
echo '    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '    <title> АИС Компетенция </title>';


//lastname	firstname	email	username	password	idnumber 	address	institution	department	cohort1
    if($group_id>0) {
        echo '<table width="100%" border="1">';
	//echo  "lastname;firstname;organization;position;login;email;password;cohort1\n";
	echo  "<tr><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Место работы</th><th>Структурное подразделение</th><th>Должность</th><th>Логин</th><th>Пароль</th> <th>Группа</th></tr>\n";

	$stmt = $dbh->prepare('SELECT `a_users`.`user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `subdivision`,  `position_id`, `password`  FROM `a_users`  LEFT JOIN `a_groups_users` USING(`user_id`)  WHERE `group_id`=?   ORDER BY `lastname`');
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
	    echo  '<tr><td>',str_replace(';', '', $row->lastname).'</td><td>'.  str_replace(';', '', $row->firstname).'</td><td>'.str_replace(';', '', $row->middlename).'</td><td>'. str_replace(';', '', $organization_name).'</td><td>'. str_replace(';', '', $row->subdivision).'</td><td>'. str_replace(';', '', $position_name).'</td><td>'. str_replace(';', '', $row->login).'</td><td>'. str_replace(';', '', $row->password)."</td> <td>$group</td></tr>\n";
       }
       echo '</table>';

   }
echo '</body></html>';

?>
