<?php

require_once 'config.php';
$dbhost_a = $cfg->host;
$dbuser_a = $cfg->user;
$dbpassword_a = $cfg->password;
$dbname_a = $cfg->name;
try {  
    $dbh = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}



session_start();
$sessionid = session_id();
$auth_session = 0;
$stmt = $dbh->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
$stmt->execute([$sessionid]);
if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $auth_session = 1;
}
if($auth_session == 0){
    exit();
}



//$html_str = file_get_contents('documents/'.$_GET['template']);



$vars_str = file_get_contents('documents/'.$_GET['vars']);
$vars_arr = explode(",", str_replace(" ", "", $vars_str) );


//require_once 'smarty/vendor/autoload.php';
//use Smarty\Smarty;

require 'smarty/libs/Smarty.class.php';
$smarty = new Smarty();
$smarty->setTemplateDir('documents/');
$smarty->setCompileDir('tmp/');
$smarty->setConfigDir('config/');
$smarty->setCacheDir('cache/');


$sql_str = file_get_contents('documents/'.$_GET['sql']);
//$lines = explode("\n", $content);
$stmt = $dbh->prepare($sql_str);
$stmt->execute( [intval($_GET['id'])] );
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    foreach ($vars_arr as $value) {
        if($row[$value] != '') {
	    $smarty->assign($value, $row[$value]);
//echo $value, $row[$value], '<br>';
	}
    }
}

	$html_str = $smarty->fetch('documents/'.$_GET['template']);

//echo $html_str;

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

	$dompdf = new Dompdf();
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

	//$html_str = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style> body { font-family: DejaVu Sans; font-size:12px; }</style><body> hello world по русски </body></html>';

	$dompdf->loadHtml($html_str, 'UTF-8');
	$dompdf->render();
	$dompdf->stream();
	
?>