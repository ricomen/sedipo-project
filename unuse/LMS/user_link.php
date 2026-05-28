
function user_link($group_id,  $user_id) {
    global $api_arg, $user_id_session, $session_role,  $dbh;


    if($user_id>0 && $group_id>0) {
	$passwd_chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
	$login = $AccountPrefix . str_pad("$p_user_id", 4, '0', STR_PAD_LEFT) . str_pad("group_id", 3, '0', STR_PAD_LEFT); 
	$email_lms = $login . $EmailDomain;
	$shfl = str_shuffle($passwd_chars);
	$password = substr($shfl,0,8);


        $stmt = $dbh->prepare('DELETE FROM `a_groups_users` WHERE `group_id`=?  AND  `user_id`=? ');
	$stmt->execute([$group_id,  $user_id ]);

    	$stmt = $dbh->prepare('INSERT INTO `a_groups_users`(`group_id`, `user_id`,  `email`, `login`, `password`) VALUES ( ?, ?, ?, ?, ?)');
	$stmt->execute([$group_id,  $p_user_id,  $email_lms, $login, $password]);
    }


    $result = ["status"=>0, "error"=>'',  "role"=>$session_role, "action"=>"user_link",  "userId"=>$user_id_session ];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
