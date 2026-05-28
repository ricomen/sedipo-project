<?php
/**
 * @copyright 2025
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


$group_id = intval($_GET["lstreamId"]);


$moodle_cohort_id = 0;
$moodle_group_id = 0;
$moodle_enrol_id = 0;
$group_name = '';
$group_date_end = 2147483647;
if($group_id>0) {

    $stmt0 = $dbh->prepare('SELECT   `name`,   `moodle_cohort_id`, `moodle_group_id`, `moodle_enrol_id`, UNIX_TIMESTAMP(`date_begin`), UNIX_TIMESTAMP(`date_end`) as `date_end`  FROM `a_lstream`   WHERE `lstream_id`=?');
    $stmt0->execute([$group_id]);
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
        $group_name = $row0->name;
        $moodle_cohort_id = $row0->moodle_cohort_id;
        $moodle_group_id = $row0->moodle_group_id;
        $moodle_enrol_id = $row0->moodle_enrol_id;
        $group_date_begin = intval($row0->date_begin);
        if(intval($row0->date_end)>0 ) 
              $group_date_end = intval($row0->date_end);
    }
    $moodle_course_idnumber = ''; 
    $stmt0 = $dbh->prepare('SELECT  `moodle_course_id`  FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`)   WHERE `lstream_id`=?');
    $stmt0->execute([$group_id]);
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
        $moodle_course_id = $row0->moodle_course_id;
    }


//file_put_contents("lst.txt", print_r($moodle_course_id, true) );

/*    $moodle_course = 0;
    $rc= $DB->get_record_sql("SELECT id FROM {couser} WHERE  idnumber = '$moodle_course_idnumber';");
    foreach ($rc as $record) {
        $moodle_course_id =  $record;
        break;
    }
*/


    echo '<p>Глобальная группа: '.$group_name.'</p>';
    echo '<table width="100%" border="1">';
    echo  "<tr><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Организация</th><th>Должность</th><th>Логин</th></tr>\n";


    $users = [];
    $stmt = $dbh->prepare('SELECT DISTINCT  `order_id`, `a_users`.`user_id`, `lastname`, `firstname`, `middlename`, `user_counterparty_id`,  `a_user_counterparty`.`counterparty_id`,  `job_title_id`, `job_title_category`, `subdivision`      FROM  `a_cohort` LEFT JOIN `a_order_course` USING(`cohort_id`)  LEFT JOIN   `a_order_users` USING(`item_id`)  LEFT JOIN `a_user_counterparty` USING (`user_counterparty_id`)   LEFT JOIN `a_users`  USING (`user_id`)  WHERE  `lstream_id`=?   ORDER BY `lastname`');
    $stmt->execute([$group_id]);
    $n =0;
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $login = '';
        $email_lms = '';
        $password = '';
        $stmt2 = $dbh->prepare('SELECT  `login`, `email`,  `password`  FROM  `a_users_passwd`  WHERE `order_id`=? AND `user_id`=?  ');
        $stmt2->execute([$row->order_id,  $row->user_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
              $login = $row2->login;
              $email_lms = $row2->email;
              $password = $row2->password;
        }

        $counterparty_name = '';
        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_counterparty`  WHERE `counterparty_id`=? ');
        $stmt2->execute([$row->counterparty_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
            $counterparty_name = $row2->name;
            //counterparty_name = str_replace("'", '&#39;',  str_replace('"', '&quot;', $row2->name));
        }
        $job_title_name = '';
        $stmt2 = $dbh->prepare('SELECT  `name`  FROM `a_job_title`  WHERE `job_title_id`=? ');
        $stmt2->execute([$row->job_title_id]);
        if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {
             $job_title_name =  $row2->name;
             //job_title_name = str_replace("'", '&#39;',  str_replace('"', '&quot;', $row2->name));
        }

        //echo  str_replace(';', '', $row->lastname).';'.  str_replace(';', '', $row->firstname).' '.str_replace(';', '', $row->middlename).';'. str_replace(';', '', $organization_name).';'. str_replace(';', '', $position_name).';'. str_replace(';', '', $row->login).';'. str_replace(';', '', $row->email).';'. str_replace(';', '', $row->password).';'. str_replace(';', '', $group)."\n";


        $users[] = ["lastname"=>$row->lastname, "firstname"=>$row->firstname,  "middlename"=>$row->middlename, "counterparty_name"=>$counterparty_name, "job_title_name"=>$job_title_name,  "email"=>$email_lms, "login"=>$login, "password"=>$password ];


        echo  '<tr><td>',str_replace(';', '', $row->lastname).'</td><td>'.  str_replace(';', '', $row->firstname).'</td><td>'.str_replace(';', '', $row->middlename).'</td><td>'. str_replace(';', '', $counterparty_name).'</td><td>'. str_replace(';', '', $job_title_name).'</td><td>'. str_replace(';', '', $login)."</td></tr>\n";
        $n=$n+1;
       }
       echo '</table>';

       $moodle_course_idnumber = ''; 
       $stmt0 = $dbh->prepare('SELECT  `moodle_course_id`  FROM `a_cohort` LEFT JOIN `a_course` USING(`course_id`)   WHERE `lstream_id`=?');
       $stmt0->execute([$group_id]);
       if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
           $moodle_course_id = $row0->moodle_course_id;


       /*$data = [ "moodle_course_id"=>$moodle_course_id, "group_id"=>$group_id,  "group_name"=>$group_name,  "moodle_cohort_id"=>$moodle_cohort_id, "moodle_group_id"=>$moodle_group_id,   "moodle_enrol_id"=>$moodle_enrol_id, "group_date_begin"=>$group_date_begin, "group_date_end"=>$group_date_end, "list"=>json_encode($users, JSON_UNESCAPED_UNICODE)  ];
       $headers = stream_context_create( [ 'http' => [
	    'method'  => 'POST',
	    'header'  => 'Content-Type: application/x-www-form-urlencoded',
	    'content' => http_build_query($data)
            ]   ] );
       $rc = file_get_contents($MoodleApiURL.'moodle_sync_plugin.php', false, $headers);*/

       echo '<form  action="'.$MoodleApiURL.'moodle_sync_plugin.php'.'" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" >';
       echo '<input type="hidden" name="moodle_course_id" value="'.$moodle_course_id.'" >';
       echo '<input type="hidden" name="group_id" value="'.$group_id.'" >';
       echo '<input type="hidden" name="group_name" value="'.$group_name.'" >';
       echo '<input type="hidden" name="moodle_cohort_id" value="'.$moodle_cohort_id.'" >';
       echo '<input type="hidden" name="moodle_group_id" value="'.$moodle_group_id.'" >';
       echo '<input type="hidden" name="moodle_enrol_id" value="'.$moodle_enrol_id.'" >';
       echo '<input type="hidden" name="group_date_begin" value="'.$group_date_begin.'" >';
       echo '<input type="hidden" name="group_date_end" value="'.$group_date_end.'" >';
       echo '<input type="hidden" name="JsonApiURL" value="'.$JsonApiURL.'" >';
       echo '<input type="hidden" name="list" value="'.base64_encode(json_encode($users, JSON_UNESCAPED_UNICODE)).'" >';
       echo '<p><input type="submit" value="Записать" ></p>';
       echo '</form>';
       


//<a  :href="moodle_url+'moodle_sync.php?cohortId='+item.lstream_id+'&groupId='+item.lstream_id+'&2Name='+item.name"  target="_blank"  type="button" class="btn btn-outline-secondary  btn-sm" title="Синхронизировать с LMS"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>

       
//print_r($rc);
    }


    //$stmt0 = $dbh->prepare('update  `a_lstream` SET `moodle_cohort_id`=?, `moodle_group_id`=?,  `moodle_enrol_id`=?  WHERE `lstream_id`=?');
    //$stmt0->execute([$moodle_cohort_id, $moodle_group_id, $moodle_enrol_id,  $group_id]);


       //echo '<p>Экспортировано в LMS ' . $n . ' записей</p>';
       //echo '<p><a href="students_xls.php?cohortId=' . $group_id . '&groupName=' . $group_name . '" ><button> Скачать список слушателей</button></a>';
       //echo ' <button onClick="window.close()">Закрыть</button></p>';
}
?>

