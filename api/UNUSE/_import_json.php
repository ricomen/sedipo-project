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

function user_import( $order_id ) {
    global $api_arg, $user_id_session, $AccountPrefix, $EmailDomain,  $dbh;
    
    $i_email = 0;
    $i_position = 0;
    $i_date_of_birth = 0;
    $i_snils = 0;
    $i_subdivision = 0;

    if($_FILES["csvfile"]['type'] == 'application/vnd.ms-excel'){
	    $rnd_file = md5(time());
	    move_uploaded_file($_FILES['csvfile']['tmp_name'], '/tmp/' . $rnd_file . '.xls');
	    $cmd  =   __DIR__ .'/conv.sh /tmp/' . $rnd_file . '.xls  /tmp/' . $rnd_file . '.csv';
	    system( $cmd  );
	    $file = '/tmp/' . $rnd_file . '.csv';
    }
    else {
	    $file = $_FILES["csvfile"]['tmp_name'];
    }


    $handle = @fopen($file, "r");
    $rows_count = 0;
    if($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
	    $rows_count = $rows_count +1;
	    $buffer= trim($buffer);
	    $rec = explode(';', $buffer);
	    if($rows_count == 1) {
	        $i = 0;
	        foreach ($rec as  $value){
		        if($value == 'email')
		            $i_email = $i;

		        if($value == 'Должность')
		            $i_position = $i;

		        if($value == 'Подразделение')
		            $i_subdivision = $i;

		        if($value == 'День месяц рождения')
		            $i_date_of_birth = $i;

		        if($value == 'Дата рождения')
		            $i_date_of_birth = $i;

		        if($value == 'СНИЛС')
		            $i_snils = $i;

		        $i = $i+1;
	        }
	        continue;
	    }
	    $job_title_id = 0;


        if($i_position > 0 && trim($rec[$i_position])!=''){
	        $count_p = 0;
	        $stmt = $dbh->prepare("select `job_title_id` FROM  `a_job_title`  WHERE `name`=?   ");
            $stmt->execute([trim($rec[$i_position])]);
	        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	            $count_p = 1;
	            $job_title_id = $row->job_title_id;
	        }
	        if($count_p == 0 && trim($rec[$i_position]) != '') {
	            $stmt = $dbh->prepare("INSERT INTO `a_job_title`(`name`) VALUES(?)");
    	        $stmt->execute([trim($rec[$i_position])]);
	            $job_title_id = $dbh->lastInsertId(); 
	        }
        }

	    $counterparty_id = 0;
	    $stmt = $dbh->prepare("select `counterparty_id` FROM  `a_order`  WHERE `order_id`=?   ");
        $stmt->execute([$order_id]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $counterparty_id = $row->counterparty_id;
	    }


	   $email = '';
	   if($i_email>0)
	         $email = trim($rec[$i_email]);
	        
	   $subdivision = '';
	   if($i_subdivision>0)
	         $subdivision = trim($rec[$i_subdivision]);

	   $date_of_birth = '';
	   if($i_date_of_birth>0)
	         $date_of_birth = trim($rec[$i_date_of_birth]);
	        
	   $snils = '';
	   if($i_snils>0)
	         $snils = trim($rec[$i_snils]);
	        

	    $user_id_exist = 0;
	    $stmt = $dbh->prepare("SELECT `user_id` FROM  `a_users`  WHERE `lastname`=? AND  `firstname`=? AND  `middlename`=? AND `counterparty_id`=? AND `job_title_id`=?  AND `date_of_birth`=?  AND `snils`=? LIMIT 1 ");
        $stmt->execute([trim($rec[0]), trim($rec[1]) ,trim($rec[2]), $counterparty_id, $job_title_id, $date_of_birth, $snils  ]);
	    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $user_id_exist = $row->user_id;
	    }

	    if($user_id_exist == 0 ) {
	        $stmt = $dbh->prepare("INSERT INTO `a_users`(`counterparty_id`, `lastname`, `firstname`, `middlename`,   `job_title_id`, `subdivision`, `date_of_birth`, `snils`) VALUES(?,?,?,?,?,?,?,?)");
    	    $stmt->execute([$counterparty_id, trim($rec[0]), trim($rec[1]), trim($rec[2]),  $job_title_id, $subdivision, $date_of_birth, $snils ]);
	        $id_user = $dbh->lastInsertId(); 

	        if($id_user>0) {
		        $passwd_chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
		        $login = $AccountPrefix . str_pad("$id_user", 5, '0', STR_PAD_LEFT); 
		        $shfl = str_shuffle($passwd_chars);
    		    $password = substr($shfl,0,8);
    		    $stmt2 = $dbh->prepare('UPDATE `a_users` SET  `login`=?, `password`=?  WHERE `user_id`=?');
    		    $stmt2->execute([$login, $password,  $id_user ]);

                if(trim($rec[$i_email])==''){
		            $email = $login . $EmailDomain;
    		        $stmt2 = $dbh->prepare('UPDATE `a_users` SET `email`=?  WHERE `user_id`=?');
    		        $stmt2->execute([$email, $login, $password,  $id_user ]);
                }
 
	        }

	    }
	    else {
	           $id_user = $user_id_exist;
	    }

    	if($order_id >0 && $id_user>0) {
    		$stmt = $dbh->prepare('DELETE FROM `a_order_users`  WHERE `order_id`=? AND  `user_id`=? AND `course_id`=0 ');
		    $stmt->execute([$order_id,  $id_user]);

    		$stmt = $dbh->prepare('INSERT INTO `a_order_users`(`order_id`,  `user_id`, `course_id`) VALUES ( ?, ?, 0)');
		    $stmt->execute([$order_id,  $id_user]);
	    }

    }
        fclose($handle);
    }
    if($_FILES["csvfile"]['type'] == 'application/vnd.ms-excel' && $file!=''){
        system( 'rm -f ' . $file  );
    }

    $result = ["status"=>0, "error"=>'',   "action"=>"user_import",  "result"=>["total"=>$rows_count,  "file"=>$_FILES["csvfile"]["name"],  "type"=>$_FILES["csvfile"]["type"]] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





if($_POST["order_id"]!='' &&  $_FILES){
     user_import( intval($_POST["order_id"]) ); 
}

else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'FILES'=>$_FILES, 'SERVER'=>$_SERVER);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
