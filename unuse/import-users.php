<!DOCTYPE html>
<html>
        <head>
                <meta charset="utf-8">
                <title>Импорт списка</title>
        </head>
        <body>

<?php
/*
require_once '../config.php';
$host = $CFG->dbhost;
$user = $CFG->dbuser;
$password = $CFG->dbpass;
$dbname = $CFG->dbname;
*/

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


    if($_FILES){
    echo $_FILES['giftfile']['name'], '<br>';

    $handle = @fopen($_FILES['giftfile']['tmp_name'], "r");
    if($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer= trim($buffer);
	$rec = explode(';', $buffer);

//print_r($rec);
//echo '<br>';
//echo $buffer;

	$count_p = 0;
	$position_id = 0;
	$stmt = $dbh->prepare("select `position_id` FROM  `a_positions`  WHERE `name`=?   ");
        $stmt->execute([$rec[3]]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $count_p = 1;
	    $position_id = $row->position_id;
	}
	if($count_p == 0) {
	    $stmt = $dbh->prepare("INSERT INTO `a_positions`(`name`) VALUES(?)");
    	    $stmt->execute([$rec[3]]);
	    $position_id = $dbh->lastInsertId(); 
	}

	$count_o = 0;
	$organization_id = 0;
	$stmt = $dbh->prepare("select `organization_id` FROM  `a_organizations`  WHERE `name`=?   ");
        $stmt->execute([$rec[4]]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $count_o = 1;
	    $organization_id = $row->organization_id;
	}
	if($count_o == 0) {
	    $stmt = $dbh->prepare("INSERT INTO `a_organizations`(`name`) VALUES(?)");
    	    $stmt->execute([$rec[4]]);
	    $organization_id = $dbh->lastInsertId(); 
	}

	$count_u = 0;
	$stmt = $dbh->prepare("select `user_id`  FROM  `a_users`  WHERE `lastname`=? AND  `firstname`=? AND  `middlename`=?  ");
        $stmt->execute([$rec[0],$rec[1],$rec[2]]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $id_user = $row->user_id; 
	    $count_u =  $count_u +1;
	}

	if($count_u == 0) {
	    $stmt = $dbh->prepare("INSERT INTO `a_users`(`lastname`, `firstname`, `middlename`, `email`, `login`, `password`, `organization_id`, `position_id`) VALUES(?,?,?,?,?,?,?,?)");
    	    $stmt->execute([$rec[0],$rec[1],$rec[2], '', '', '', $organization_id, $position_id]);
	    $id_user = $dbh->lastInsertId(); 
	}


	if($count_u == 0 && $id_user>0) {
	    $passwd_chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
	    $login = 'p' . str_pad("$id_user", 5, '0', STR_PAD_LEFT); 
	    $email = $login . '@mng.cppk.ru';
	    $shfl = str_shuffle($passwd_chars);
	    $password = substr($shfl,0,8);
 
    	    $stmt2 = $dbh->prepare('UPDATE `a_users` SET `email`=?, `login`=?, `password`=?  WHERE `user_id`=?');
    	    $stmt2->execute([$email, $login, $password,  $id_user ]);
	}


	$stmt2 = $dbh->prepare("INSERT INTO `a_reports`(`user_id`, `num`, `course`, `course_id`, `date`, `result`) VALUES(?,?,?,?,?,?)");
    	$stmt2->execute([$id_user, $rec[7], $rec[5], 0, $rec[6], $rec[8] ]);


        }
        fclose($handle);
    }

    }

?>
<form method="POST"  enctype="multipart/form-data" >


<input name="giftfile" type="file" />

    <p><input type="submit" value="ok"></p>
</form>



</body>
</html>

