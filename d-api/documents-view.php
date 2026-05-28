<?php

require_once 'd-api_lib.php';

require_once '../../config/config-auth.php';
$dbhost_a = $cfg_auth->host;
$dbuser_a = $cfg_auth->user;
$dbpassword_a = $cfg_auth->password;
$dbname_a = $cfg_auth->name;
try {  
    $dbh_a = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}

session_start();
$sessionid = session_id();
$auth_session = 0;
$stmt = $dbh_a->prepare('SELECT `login`, `role_id`, `a_account`.`account_id`, `customer_id`   FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `session_id`=? ');
$stmt->execute([$sessionid]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $auth_session = 1;
}
if($auth_session == 0){
    echo 'error 1';
    exit();
}

if($_GET['file'] == ''){
    echo 'error 2';
    exit();
}

$filename = htmlspecialchars($_GET['file'], ENT_QUOTES);
$ext = mb_substr($filename, mb_strrpos($filename, '.')+1);
$arg_file =str_replace('/uploads/', '', $filename);
$file = '../uploads/'. $arg_file;


if (file_exists($file)) {
header('Cache-Control: max-age=0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

if($ext == 'pdf')
     header('Content-Type: application/pdf');

if($ext == 'docx')
     header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

header('Content-Disposition: attachment; filename="'.rus2translit($arg_file).'"');

readfile($file);
}
echo 'error 3';

?>