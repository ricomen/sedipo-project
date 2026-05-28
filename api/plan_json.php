<?php
/**
 * @copyright 2024
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



require_once '../config.php';
require_once 'lib.php';
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



function rprg_list( $search, $page) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $is_search = '';
    $offset=0;
    if( $page>1 ){
	$offset = intval($page)*100;
    }
    else {
	 $page=1;
    }

    $users_list = [];
    $members = 0;

    if($is_search >0){
	    $stmt = $dbh->prepare('SELECT `user_id`, `login`, `lastname`, `firstname`, `middlename`, `email`, `organization_id`, `position_id`  FROM `a_users` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ?  ORDER BY `lastname` LIMIT 100  OFFSET  ' . "$offset");
    	    $stmt->execute(["$lastname%", "$firstname%", "$middlename%"]);
    }
    else {
	$stmt = $dbh->query('SELECT `id`,  `name`, `form`, `form1`, `form2`, `form3`, `hours`  FROM `p_rprg`   ORDER BY `name`  LIMIT 100  OFFSET  ' . "$offset");
    }
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  

	    $rprg_list[] =  ["Id"=>$row->id,  "name"=>$row->name, "form"=>$row->form, "hours"=>$row->hours ];
    }


    if($is_search >0){
	    $stmt0 = $dbh->prepare('SELECT count(*) as `count`  FROM `p_rprg` WHERE `lastname` LIKE ? AND `firstname` LIKE ? AND  `middlename` LIKE ? ');
    	    $stmt0->execute(["$lastname%", "$firstname%", "$middlename%"]);
    }
    else {
	$stmt0 = $dbh->query('SELECT count(*) as `count`   FROM `p_rprg`   ');
    }
    if($row0 = $stmt0->fetch(PDO::FETCH_OBJ)) {  
	$members = $row0->count;
    }
    $num_pages = intval(($members)/100);
    if( $page > $num_pages ){
	    $page = $num_pages;
    }

    $result = ["role"=>$session_role, "action"=>"rprg_list",  "list"=>$rprg_list,  "search"=>$is_search, "numPages"=>$num_pages, "page"=>$page];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function rprg_detalies($rprg_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc =  ["Id"=>0,  "name"=>'', "form"=>'', "hours"=>'' ];
    $list = [];
    $num = 1;
    $sum_o =  0;
    $sum_z= 0;
    $sum_l = 0;
    $sum_p = 0;
    $sum_s = 0;
    $sum_a  = 0;
    if($rprg_id >0 ) {
	$stmt = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`   FROM `p_item`  WHERE `id`=? AND `parent`=0  ORDER BY `num` ');
	$stmt->execute([$rprg_id]);
    	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    //$list[] =  ["p_Id"=>$row->p_id, "num"=>$num,  "name"=>$row->name, "form"=>$row->form,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,"hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a,  "parent"=>$row->parent, "description"=>$row->description  ];
	    $list[] =  ["p_Id"=>$row->p_id, "num"=>$num,  "name"=>$row->name, "form"=>$row->form,  "hours_o"=>'',  "hours_z"=>'',"hours_l"=>'', "hours_p"=>'',  "hours_s"=>'', "hours_a"=>'',  "parent"=>$row->parent   ];
	    $num1 = 1;

	    $stmt1 = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`   FROM `p_item`  WHERE `id`=? AND `parent`=?   ORDER BY `num`');
	    $stmt1->execute([$rprg_id, $row->p_id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
		$list[] =  ["p_Id"=>$row1->p_id, "num"=>$num .'.'. $num1,  "name"=>$row1->name, "form"=>$row1->form, "hours_o"=>$row1->hours_o,  "hours_z"=>$row1->hours_z, "hours_l"=>$row1->hours_l, "hours_p"=>$row1->hours_p,  "hours_s"=>$row1->hours_s,  "hours_a"=>$row1->hours_a, "parent"=>$row1->parent   ];
		$num1 = $num1 +1;
	    }
	    $list[] =  ["p_Id"=>$row->p_id, "num"=>0,  "name"=>'Итого: ', "form"=>$row->form,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,"hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a,  "parent"=>$row->parent  ];
	    $num = $num+1;
	}

	$stmt = $dbh->prepare('SELECT `id`,  `name`, `form`, `form1`, `form2`, `form3`, `att_i`, `hours`, `p0`, `p1`, `p2`, `p3`, `p4`, `p5`, `p6`,  `hours_o`, `hours_z`, `hours_l`,  `hours_p`, `hours_s`, `hours_a`, `hours_k`, `hours_i`, `s_fl`, `norm_hours`  FROM `p_rprg`  WHERE `id`=?');
	$stmt->execute([$rprg_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  

	    $rc =  ["Id"=>$row->id,  "name"=>$row->name, "form"=>$row->form, "form1"=>$row->form1, "form2"=>$row->form2, "form3"=>$row->form3, "att_i"=>$row->att_i, "hours"=>$row->hours, "s_hours_o"=>$sum_o, "s_hours_z"=>$sum_z , "s_hours_l"=>$sum_l, "s_hours_p"=>$sum_p, "s_hours_s"=>$sum_s, "s_hours_a"=>$sum_a, "p0"=>$row->p0, "p1"=>$row->p1, "p2"=>$row->p2, "p3"=>$row->p3, "p4"=>$row->p4, "p5"=>$row->p5, "p6"=>$row->p6, "hours_o"=>$row->hours_o, "hours_z"=>$row->hours_z, "hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p, "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a, "hours_k"=>$row->hours_k, "hours_i"=>$row->hours_i, "s_fl"=>$row->s_fl, "norm_hours"=>$row->norm_hours ];
	}


    }
    $result = ["role"=>$session_role, "action"=>"rprg", "userId"=>$user_id_session, "rprg"=>$rc, "item"=>$list ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}




function rprg_save($id, $name, $hours, $form, $form1, $form2, $form3, $att_i, $p0, $p1, $p2, $p3, $p4, $p5, $p6, $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a, $hours_k, $hours_i, $s_fl, $norm_hours) {
    global $api_arg, $user_id_session, $session_role, $EmailDomain, $AccountPrefix, $dbh;

    $t0= "Дополнительная профессиональная программа повышения квалификации __________________________________разработана в соответствии с нормами Федерального закона от 29 декабря 2012 г. № 273-ФЗ \"Об образовании в Российской Федерации\" и с учетом требований Порядка организации и осуществления образовательной деятельности по дополнительным профессиональным программам, утвержденного приказом Министерства образования и науки Российской Федерации от 1 июля 2013 г. № 499,";
    $t1= "Целью реализации программы может быть совершенствование и (или) получение новой компетенции, необходимой для профессиональной деятельности, и (или) повышение профессионального уровня в рамках имеющейся квалификации.\n Пример: Целью реализации программы является повышение профессионального уровня ППС отделения «наименование отделения» в области «наименование области изучения/темы» в рамках имеющейся квалификации для непрерывного развития и совершенствования профессиональных компетенций, внедрения современных инновационных подходов в обучении студентов.";
    $t2= "В результате освоения программы совершенствуются следующие компетенции:\n    1. …\n     2. …\n Слушатель должен приобрести следующие знания и умения, необходимые для качественного изменения компетенций:\n Слушатель должен знать: - …\n - Слушатель должен уметь: - … -";
    $t6= "Итоговая аттестация проходит в форме итогового тестирования.\n Критерии оценивания итогового тестирования:\n Оценка за контроль ключевых компетенций обучающихся производится по пятибалльной системе. При выполнении заданий ставится отметка:\n «3» - за 50-70% правильно выполненных заданий,\n «4» - за 70-85%  правильно выполненных заданий,\n «5» - за правильное выполнение более 85% заданий.";

    if($name=='' ){
	$result = ["status"=>1, "error"=>'Не все обязательные поля заполнены',  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>0]];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
        return;
    }
    if($form1==1)
	$form1 = 'true';
    if($form2==1)
	$form2 = 'true';
    if($form3==1)
	$form3 = 'true';

    if($id>0){
        $stmt = $dbh->prepare('UPDATE `p_rprg` SET `name`=?, `hours`=?,  `form`=?,  `form1`=?,  `form2`=?,  `form3`=?, `att_i`=?, `p0`=?, `p1`=?,  `p2`=?, `p3`=?,  `p4`=?,  `p5`=?,  `p6`=?, `s_fl`=?        WHERE `id`=?');
        $stmt->execute([$name, $hours, $form, $form1, $form2, $form3, $att_i,  $p0, $p1, $p2, $p3, $p4, $p5, $p6, $s_fl,  $id ]);
        $stmt = $dbh->prepare('UPDATE `p_rprg` SET   `hours_o`=?,  `hours_z`=?,   `hours_l`=?,  `hours_p`=?,  `hours_s`=?, `hours_a`=?,   `hours_k`=?, `hours_i`=?, `norm_hours`=?   WHERE `id`=?');
        $stmt->execute([ $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a, $hours_k, $hours_i, $norm_hours,   $id ]);
        $p_id = $id; 
    }
    else {
        $stmt = $dbh->prepare('INSERT INTO `p_rprg`(`name`, `hours`,    `p0`,  `p1`,  `p2`,  `p3`,  `p4`,  `p5`,  `p6`, `s_fl`) VALUES ( ?, ?,  ?, ?, ?, ?, ?, ?, ?,  ?)');
	$stmt->execute([$name, $hours,  $t0, $t1, $t2, '', '', '', $t6, 'Самоподготовка']);
	$p_id = $dbh->lastInsertId(); 
	if($p_id<=0){
	    $result = ["status"=>2, "error"=>'Ошибка DB'. $dbh->errorInfo()[2],  "role"=>$session_role, "action"=>"user_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>0]];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	    return;
	}

    }


    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"rprg_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>$p_id] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function rprg_delete($id) {
    global $api_arg, $user_id_session, $session_role, $customer, $dbh;

    $status = "0";
    $error = '';

    if($id >0) {

	$stmt = $dbh->prepare('DELETE FROM `p__items`  WHERE `id`=?');
	$stmt->execute([$id]);

	$stmt = $dbh->prepare('DELETE FROM `p_rprg`  WHERE `id`=?');
	$stmt->execute([$id]);

	if(!$stmt) {
            $status = "4";
	    $error = 'error 2: '. $dbh->errorInfo()[2];
	}
    }

    $result = ["status"=>$status, "error"=>$error, "role"=>$session_role, "action"=>"rprg_delete", "customerId"=>$customer, "userId"=>$user_id_session];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}





function p_item_save($id, $p_id, $parent,  $name, $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a,  $form, $description, $np, $bib) {
    global $api_arg, $user_id_session, $session_role, $EmailDomain, $AccountPrefix, $dbh;

    if($name=='' ){
	$result = ["status"=>1, "error"=>'Не все обязательные поля заполнены',  "role"=>$session_role, "action"=>"item_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>0, "p_Id"=>0]];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
        return;
    }
    

    if($p_id > 0){
    	$stmt = $dbh->prepare('UPDATE `p_item` SET `name`=?,   `form`=?, `description`=?, `np`=?, `bib`=?  WHERE `p_id`=?');
    	$stmt->execute([$name,  $form, $description, $np, $bib,     $p_id ]);

	if($parent>0 ){
    	    $stmt = $dbh->prepare('UPDATE `p_item` SET    `hours_o`=?,  `hours_z`=?,   `hours_l`=?,  `hours_p`=?,  `hours_s`=?,  `hours_a`=?   WHERE `p_id`=?');
    	    $stmt->execute([$hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a,      $p_id ]);
	}


	if($parent == 0){
	    $p_id_0 = $p_id;
	    $parent_0 = $p_id;
	}
	else {
	    $p_id_0 = $parent;
	    $parent_0 = $parent;
        }
	    
	$sum_o =  0;
	$sum_z= 0;
	$sum_l = 0;
	$sum_p = 0;
	$sum_s = 0;
	$sum_a  = 0;
	$num = 0; 

	$stmt1 = $dbh->prepare('SELECT `p_id`,   `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`   FROM `p_item`  WHERE `id`=? AND `parent`=?');
	$stmt1->execute([$id, $parent_0]);
    	while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
	}
    	$stmt = $dbh->prepare('UPDATE `p_item` SET   `hours_o`=?,  `hours_z`=?,   `hours_l`=?,  `hours_p`=?,  `hours_s`=?,  `hours_a`=?   WHERE `p_id`=?');
    	$stmt->execute([$sum_o, $sum_z, $sum_l, $sum_p, $sum_s, $sum_a,   $p_id_0 ]);

        $i_id = $p_id; 
    }
    else {
	if($parent == 0){
	    $stmt1 = $dbh->prepare('SELECT max(`num`) as `num`  FROM `p_item` WHERE `parent`=0 ');
	    $stmt1->execute([]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {
		$num = $row1->num;
	    }

    	    $stmt = $dbh->prepare('INSERT INTO `p_item`( `id`, `parent`, `num`, `name`, `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`, `form`, `description`, `np`, `bib`   ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	    $stmt->execute([$id, 0, $num+1,  $name, $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a, $form, $description, $np, $bib]);
	    $i_id = $dbh->lastInsertId(); 

    	    $stmt1 = $dbh->prepare('INSERT INTO `p_item`( `id`, `parent`, `num`, `name`, `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`, `form`, `description`, `np`, `bib`   ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	    $stmt1->execute([$id, $i_id, 1,  'Тема ', $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a, $form, '', '', '']);
	}
	else {
	    $stmt1 = $dbh->prepare('SELECT max(`num`) as `num`  FROM `p_item` WHERE `parent`=? ');
	    $stmt1->execute([$parent]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {
		$num = $row1->num;
	    }

    	    $stmt = $dbh->prepare('INSERT INTO `p_item`( `id`, `parent`, `num`, `name`, `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`, `form`, `description`, `np`, `bib`   ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	    $stmt->execute([$id, $parent, $num+1, $name, $hours_o, $hours_z, $hours_l, $hours_p, $hours_s, $hours_a, $form, $description, $np, $bib]);
	    $i_id = $dbh->lastInsertId(); 

	    $stmt1 = $dbh->prepare('SELECT `p_id`,   `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`   FROM `p_item`  WHERE `id`=? AND `parent`=?');
	    $stmt1->execute([$id, $parent]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
	    }
    	    $stmt = $dbh->prepare('UPDATE `p_item` SET   `hours_o`=?,  `hours_z`=?,   `hours_l`=?,  `hours_p`=?,  `hours_s`=?,  `hours_a`=?   WHERE `p_id`=?');
    	    $stmt->execute([$sum_o, $sum_z, $sum_l, $sum_p, $sum_s, $sum_a,   $parent ]);

	}
     }
    if($i_id == 0){
	    $result = ["status"=>2, "error"=>'Ошибка DB'. $dbh->errorInfo()[2],  "role"=>$session_role, "action"=>"item_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>0, "p_Id"=>0]];
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	    return;
    }

    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"item_save",  "userId"=>$user_id_session, "rprg"=>["Id"=>$id,  "p_Id"=>$i_id] ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function p_calc($id) {
    global $api_arg, $user_id_session, $session_role, $EmailDomain, $AccountPrefix, $dbh;

    $n = 0;
    $stmt = $dbh->prepare('SELECT count(*) as `count`   FROM `p_item`  WHERE `id`=? AND `parent`>0');
    $stmt->execute([$id]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $n =  $row->count;
    }

    if($n>0){
	$stmt = $dbh->prepare('SELECT    `hours`,   `hours_o`, `hours_z`, `hours_l`,  `hours_p`, `hours_s`, `hours_a`, `hours_k`, `hours_i`  FROM `p_rprg`  WHERE `id`=?');
	$stmt->execute([$id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	     $hours = $row->hours;
	     $hours_l = $row->hours_l / $n;
	     $hours_p = $row->hours_p / $n;
	     $hours_z = $row->hours_z / $n;
	     $hours_o = $row->hours_o / $n;
	     $hours_s = $row->hours_s / $n;
	     $hours_a = $row->hours_a / $n;
	}
	$num = 1;
	$stmt = $dbh->prepare('SELECT `p_id`   FROM `p_item`  WHERE `id`=? AND `parent`>0');
	$stmt->execute([$id]);
    	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    	    $stmt2 = $dbh->prepare('UPDATE `p_item` SET   `hours_l`=?,  `hours_p`=?,    `hours_z`=?, `hours_o`=?,   `hours_s`=?,  `hours_a`=?   WHERE `p_id`=?');
    	    $stmt2->execute([ $hours_l, $hours_p, $hours_z, $hours_o,  $hours_s, $hours_a,   $row->p_id ]);
	    $num = $num + 1;
	}


	$stmt = $dbh->prepare('SELECT `p_id`   FROM `p_item`  WHERE `id`=? AND `parent`=0');
	$stmt->execute([$id]);
    	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	    $i_id =  $row->p_id; 
	    $sum_o =  0;
	    $sum_z= 0;
	    $sum_l = 0;
	    $sum_p = 0;
	    $sum_s = 0;
	    $sum_a  = 0;

	    $stmt1 = $dbh->prepare('SELECT `p_id`,   `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`   FROM `p_item`  WHERE  `parent`=? AND  `id`=?');
	    $stmt1->execute([ $i_id,  $id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
	    }
    	    $stmt2 = $dbh->prepare('UPDATE `p_item` SET   `hours_o`=?,  `hours_z`=?,   `hours_l`=?,  `hours_p`=?,  `hours_s`=?,  `hours_a`=?   WHERE `p_id`=?');
    	    $stmt2->execute([$sum_o, $sum_z, $sum_l, $sum_p, $sum_s, $sum_a,   $i_id  ]);
	}

    }
}



function p_item_detalies($p_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    $rc =  ["id"=>0, "parent"=>'', "name"=>''  ];
    $s_fl = 'Самоподготовка';
    if($p_id >0 ) {
	$stmt = $dbh->prepare('SELECT `id`, `parent`, `name`, `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`, `form`, `description`, `np`, `bib`  FROM `p_item`   WHERE `p_id`=?');
	$stmt->execute([$p_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $stmt2 = $dbh->prepare('SELECT `s_fl`  FROM `p_rprg`  WHERE `id`=?');
	    $stmt2->execute([$row->id]);
	    if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
		$s_fl = $row2->s_fl;
	    }
	    $rc =  ["rprg_id"=>$row->id, "parent"=>$row->parent, "name"=>$row->name,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,  "hours_l"=>$row->hours_l,  "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s,  "hours_a"=>$row->hours_a, "form"=>$row->form, "description"=>$row->description, "np"=>$row->np, "bib"=>$row->bib, "s_fl"=>$s_fl ];
	}
    }
    $result = ["role"=>$session_role, "action"=>"p_detalies", "userId"=>$user_id_session, "p"=>$rc ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


function p_item_up($p_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($p_id >0 ) {
	$stmt = $dbh->prepare('SELECT `id`, `parent`, `num`  FROM `p_item`   WHERE `p_id`=?');
	$stmt->execute([$p_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $num = $row->num - 1;
	    if($row->num > 1){
		$stmt1 = $dbh->prepare('SELECT `p_id`  FROM `p_item`   WHERE `parent`=? AND `num`=? ' );
		$stmt1->execute([$parent, $num]);
		if($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		    $stmt2 = $dbh->prepare('UPDATE  `p_item` SET `num`=?   WHERE `p_id`=?  ');
		    $stmt2->execute([$row->num, $row1->p_id]);
		}
		$stmt2 = $dbh->prepare('UPDATE  `p_item` SET `num`=?   WHERE `p_id`=?  ');
		$stmt2->execute([$num, $p_id]);
	    }
	}
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"p_item_up" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function p_item_down($p_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($p_id >0 ) {
	$stmt = $dbh->prepare('SELECT `id`, `parent`, `num`  FROM `p_item`   WHERE `p_id`=?');
	$stmt->execute([$p_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $num = $row->num + 1;
	    if($row->num > 0){
		$stmt1 = $dbh->prepare('SELECT `p_id`  FROM `p_item`   WHERE `parent`=? AND `num`=? ' );
		$stmt1->execute([$parent, $num]);
		if($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		    $stmt2 = $dbh->prepare('UPDATE  `p_item` SET `num`=?   WHERE `p_id`=?  ');
		    $stmt2->execute([$row->num, $row1->p_id]);
		}
	    }
	
	    $stmt2 = $dbh->prepare('UPDATE  `p_item` SET `num`=?   WHERE `p_id`=?  ');
	    $stmt2->execute([$num, $p_id]);
	}
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"p_item_up" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



function p_item_del($p_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    if($p_id >0 ) {
	$stmt = $dbh->prepare('DELETE FROM  `p_item`   WHERE `p_id`=?  ');
	$stmt->execute([$p_id]);
    }

    $result = ["status"=>0, "error"=>'', "role"=>$session_role, "action"=>"p_item_del" ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}











api_journal('order', $api_function, $api_arg, $session_login);
if($api_function=='rprg_list'){
    rprg_list($api_arg["search"],  $api_arg["page"] );
}
else if($api_function=='rprg_detalies'  ){
    rprg_detalies(intval($api_arg["Id"]));
}
else if($api_function=='rprg_save' ){
    rprg_save( intval($api_arg["Id"]), $api_arg["name"],  intval($api_arg["hours"]), $api_arg["form"], $api_arg["form1"], $api_arg["form2"], $api_arg["form3"], $api_arg["att_i"], $api_arg["p0"], $api_arg["p1"], $api_arg["p2"], $api_arg["p3"], $api_arg["p4"], $api_arg["p5"], $api_arg["p6"], intval($api_arg["hours_o"]), intval($api_arg["hours_z"]), intval($api_arg["hours_l"]), intval($api_arg["hours_p"]), intval($api_arg["hours_s"]), intval($api_arg["hours_a"]), intval($api_arg["hours_k"]), intval($api_arg["hours_i"]), $api_arg["s_fl"], $api_arg["norm_hours"] );
}
else if($api_function=='rprg_delete' ){
    rprg_delete( intval($api_arg["Id"]) );
}
else if($api_function=='p_item_detalies' ){
    p_item_detalies( intval($api_arg["p_Id"]) );
}


else if($api_function=='p_items'){
    p_items(intval($api_arg["groupId"]) );
}
else if($api_function=='p_item_save' ){
    p_item_save( intval($api_arg["Id"]), intval($api_arg["p_Id"]), intval($api_arg["parent"]),  $api_arg["name"], intval($api_arg["hours_o"]), intval($api_arg["hours_z"]), intval($api_arg["hours_l"]), intval($api_arg["hours_p"]), intval($api_arg["hours_s"]), intval($api_arg["hours_a"]), $api_arg["form"], $api_arg["description"], $api_arg["np"], $api_arg["bib"] );
}
else if($api_function=='p_item_del')
{
    p_item_del(intval($api_arg["p_id"]) );
}
else if($api_function=='p_item_up')
{
    p_item_up(intval($api_arg["p_id"]) );
}
else if($api_function=='p_item_down')
{
    p_item_down(intval($api_arg["p_id"]) );
}
else if($api_function=='p_calc')
{
    p_calc(intval($api_arg["Id"]) );
}


else {
    $result = array('POST'=>$_POST, 'GET'=>$_GET, 'SERVER'=>$_SERVER, 'JSON'=>$_JSON);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>
