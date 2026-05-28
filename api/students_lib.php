<?php
/**
 * @copyright 2025
 */




function user_link_lib($order_id, $user_counterparty_id, $user_id,  $item_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $AccountPrefix, $EmailDomain;


    $number_of_people = 1;
    $post_link = 0;
    if($item_id > 0) {
         $stmt = $dbh->prepare('SELECT  `number_of_people`  FROM   `a_order_users`  LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)  WHERE `item_id`=? AND `user_id`=1  ');
         $stmt->execute([ $item_id ]);
         if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $post_link = 1;
	        $number_of_people =  intval($row->number_of_people);
         }

    }

    if($user_counterparty_id>0 &&  $order_id>0 && $number_of_people>0 ) {
        //$stmt = $dbh->prepare('DELETE FROM `a_order_users` WHERE `order_id`=?  AND  `user_id`=? ');
        //$stmt->execute([$order_id,  $user_id ]);

        $user_id_0 = 0;
        $stmt = $dbh->prepare("SELECT  `user_id`  FROM   `a_user_counterparty`   WHERE  `user_counterparty_id`= ? ");
        $stmt->execute([ $user_counterparty_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $user_id_0 =  $row->user_id;
        }

        $item_0 = 0;
        $stmt = $dbh->prepare("SELECT  `item_id`  FROM  `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)  WHERE  `order_id`=?  AND `user_counterparty_id`= ? ");
        $stmt->execute([$order_id, $user_counterparty_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $item_0 =  $row->item_id;
        }
        if( $item_0 > 0 &&  $item_id==0){
                 $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"link", "result"=>0   ];
                 return $result;
        }

        $num = 1;
    	//$stmt = $dbh->prepare("SELECT  count(*) as `count` FROM  `a_order_users`  WHERE  `order_id`=?  AND `cohort_id`=0  AND `course_id`=0  ");
    	$stmt = $dbh->prepare("SELECT  MAX(`a_order_users`.`certificate_num`) as `num` FROM  `a_order_users` LEFT JOIN `a_order_course` USING(`item_id`)   WHERE  `order_id`=?  ");
        $stmt->execute([$order_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        //$num =  intval($row->count) + 1;
	        $num =  intval($row->num) + 1;
        }
        if($item_id > 0 && $post_link>0 ) 
                 $num = $num - $number_of_people;// -1; 

        $user_count = 0;
        $stmt = $dbh->prepare("SELECT  count(*) as `count`  FROM `a_users_passwd`  LEFT JOIN `a_user_counterparty` USING(`user_id`)   WHERE  `order_id`=?  AND   `user_counterparty_id`= ? ");
        $stmt->execute([$order_id, $user_counterparty_id] );
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {
	        $user_count =  $row->count;
        }
//file_put_contents("lst.txt", print_r([$order_id, $user_id_0, $user_count  ], true) );
        if($user_count == 0 && $user_id_0 > 1 ) {
	    $passwd_chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
	    $login = $AccountPrefix . str_pad("$user_id_0-$order_id", 9, '0', STR_PAD_LEFT); 
	    $email_lms = $login . $EmailDomain;
	    $shfl = str_shuffle($passwd_chars);
	    $password = substr($shfl,0,8);
            $stmt2 = $dbh->prepare('INSERT INTO `a_users_passwd`(`order_id`, `user_id`,  `email`, `login`, `password`)  VALUES( ?,  ?,  ?,  ?, ?) ');
            $stmt2->execute([$order_id, $user_id_0,  $email_lms, $login, $password ]);
        }

        if($item_0 == 0) {
            $stmt = $dbh->prepare('INSERT INTO `a_order_users`(`order_id`,   `user_counterparty_id`,  `certificate_num`, `parent_id`) VALUES ( ?, ?, ?, ? )');
            //$stmt->execute([$order_id,  $user_id, $user_counterparty_id, $num, $item_id]);
            $stmt->execute([$order_id,  $user_counterparty_id, $num, $item_id]);
            $last_id = $dbh->lastInsertId();
        }
        else {
            $last_id = $item_0;
        }

        if($item_id > 0 && $post_link>0 ) {
            $stmt = $dbh->prepare('SELECT `course_id`, `rank_of_profession`, `variation`, `form_of_study`   FROM  `a_order_course`  WHERE `item_id`=? ');
            $stmt->execute([ $item_id ]);
            while($row = $stmt->fetch(PDO::FETCH_OBJ)) {

                $stmt2 = $dbh->prepare('INSERT  INTO  `a_order_course`(`item_id`,  `course_id`, `rank_of_profession`, `variation`, `form_of_study` ) VALUES ( ?, ?, ?, ?, ? )');
                $stmt2->execute([$last_id , $row->course_id, $row->rank_of_profession, $row->variation, $row->form_of_study ]);
            }
            $stmt2 = $dbh->prepare('UPDATE  `a_order_users` SET `number_of_people`=`number_of_people`-1   WHERE `item_id`=? AND `number_of_people`>0 ');
            $stmt2->execute([ $item_id ]);
        }
    }

    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"link",  "result"=>$last_id ];
    return $result;
}

?>