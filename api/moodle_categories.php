
$c_list = [];

function categories_list_go($c_parent, $level) {
    global $api_arg, $user_id_session, $session_role,  $dbh;
    global $c_list;

    $rc = [];
    $prefix = str_repeat('- ', $level);

//    $stmt = $dbh->prepare('SELECT `id`, `name`,  `parent`  FROM `md_course_categories` WHERE  `parent`= ?  AND `visible`=1    ORDER BY `name`  LIMIT 500');
    $stmt = $dbh->prepare('SELECT `id`, `name`,  `parent`, `visible`  FROM `md_course_categories` WHERE  `parent`= ?   ORDER BY `name`  LIMIT 500');
    $stmt->execute([$c_parent] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        if($row->visible==0 && $level==0)
              continue;
        $name = $prefix . $row->name;
	$c_list[] =  ["category_id"=>$row->id,  "name"=>$name,  "path"=>$row->path  ];
	$rc = array_merge( $c_list, categories_list_go($row->id, $level+1) );
    }
    return  $rc;
}



function categories_list() {
    global $api_arg, $user_id_session, $session_role,  $dbh;

    $rc = categories_list_go(0, 0);

    $result = ["role"=>$session_role, "action"=>"categories_list",   "list"=>$rc];
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}


