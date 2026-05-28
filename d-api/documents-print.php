<?php

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
    exit();
}



#$IS_DEPRECATED=0;
$IS_DEPRECATED = 1;

if(isset($_POST['protocol_save']) && $IS_DEPRECATED==1 ){
    if(isset($_POST['cohort_id']) && $_POST['cohort_id']>0){
        $stmt = $dbh->prepare("update `a_cohort` SET `protocol_html`=?  WHERE `cohort_id`=?");
        $stmt->execute( [$_POST['editor'], intval($_POST['cohort_id']) ] );
    }
    echo '<a href="#" onclick="window.close(); return false;">Закрыть</a>';
    echo '<script> window.onload = function (){ window.close(); } </script> ';
    exit();
}

/*
if(isset($_POST['contract_save'])){
    if(isset($_POST['template_id']) && isset($_POST['id']) && $_POST['id']>0 && $_POST['template_id']>0){
        $stmt = $dbh->prepare("update `a_counterparty_contract`  SET `contract_html`=?  WHERE `counterparty_id`=? AND  `cohort_id`=?");
        $stmt->execute( [$_POST['editor'], intval($_POST['id']) ] );
    }
    echo '<a href="#" onclick="window.close(); return false;">Закрыть</a>';
    echo '<script> window.onload = function (){ window.close(); } </script> ';
    exit();
}
*/

$text = $_POST['editor'];
$text = preg_replace("~<p>~", "", $text, 2);
$text = preg_replace("~</p>~", "", $text, 2);
$text = preg_replace("~<br>~", "", $text, 2);
$text = preg_replace("~<p><br></p>~", "", $text, 1000);
$text = preg_replace('~<p><meta http-equiv="content-type" content="text/html; charset=utf-8">~', '<meta http-equiv="content-type" content="text/html; charset=utf-8">', $text, 1000);
$text = preg_replace('~<p style="page-break-before: always;"><br></p>~', '<p style="page-break-before: always;" ></p>',  $text, 1000);
$text = preg_replace('~<br></p><style type="text/css">~', '<style type="text/css">',  $text, 1000);
$text = preg_replace('~<div style="page-break-before: always;"> <br></div>~', '<p style="page-break-before: always;" ></p>',  $text, 1000);
#echo $text;

require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;



	$dompdf = new Dompdf();
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->set_option('defaultFont', 'DejaVu Sans');
	$dompdf->set_option('isRemoteEnabled', true);

	//$html_str = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style> body { font-family: DejaVu Sans; font-size:12px; }</style><body> hello world по русски </body></html>';

	$dompdf->loadHtml($text, 'UTF-8');
	$dompdf->render();
        if(isset($_POST['protocol_save']) && $IS_DEPRECATED==0 ){
             $cohort_id = intval($_POST['cohort_id']);
             $path = "../uploads/groups/protocol/";
             $file_name = 'protocol_' .intval($cohort_id) . '.pdf';
             $output = $dompdf->output();
             file_put_contents($path. $file_name, $output);

             $stmt = $dbh->prepare('UPDATE `a_cohort`  SET `upload_protocol`=?  WHERE `cohort_id`=? ' );
             $stmt->execute([$file_name, $cohort_id ]);

             echo '<a href="#" onclick="window.close(); return false;">Закрыть</a>';
             echo '<script> window.onload = function (){ window.close(); } </script> ';
             exit();
        }
        if(isset($_POST['protocol_save'])  ){
             echo '<a href="#" onclick="window.close(); return false;">Закрыть</a>';
             echo '<script> window.onload = function (){ window.close(); } </script> ';
             exit();
        }
        else {
             $dompdf->stream("print.pdf", array("Attachment" => 0));
        }


function rus2translit($str) {
	mb_regex_encoding('UTF-8');

        $str2 = str_replace(
            array(" ", "(", ")", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            //array(" ", "(", ")", ".", ",", ":", ";", "+", "/", "\\", "'",  "\"", "-", "`"),
            //array("_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"),
            $str
        );
        
        $str3 = str_replace(
            array("а", "б", "в", "г", "д", "е", "з", "и", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ц", "ъ", "ы", "ь"),
            array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", ""),
            $str2
        );
               
        $str4 = str_replace(
            array("А", "Б", "В", "Г", "Д", "Е", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Ц", "Ъ", "Ы", "Ь"),
            array("A", "B", "V", "G", "D", "E", "Z", "I", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "С", "", "Y", ""),
            $str3
        );
        
        $str5 = str_replace(
            array("э", "х", "й", "ё", "ж", "ч", "ш", "щ", "ю", "я", "Э", "Х", "Й", "Ё", "Ж", "Ч", "Ш", "Щ", "Ю", "Я"),
            array("eh", "kh", "jj", "jo", "zh", "ch", "sh", "shh", "ju", "ja", "EH", "KH", "JJ", "JO", "ZH", "CH", "SH", "SHH", "JU", "JA"),
            $str4
        );
   
        return $str5;
}

?>