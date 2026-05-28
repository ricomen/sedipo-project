<!DOCTYPE html>
<html>
        <head>
                <meta charset="utf-8">
                <title>Импорт - Администратор</title>
        </head>
        <body>

<?php
require_once 'configuration.php';

$jconfig = new JConfig();
$host = $jconfig->host;
$user = $jconfig->user;
$password = $jconfig->password;
$dbname = $jconfig->db;

try {  
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}




#print_r($_FILES);

    if($_FILES){
    echo $_FILES['giftfile']['name'], '<br>';
    $stmt = $dbh->query("INSERT INTO `eo_competence`(`competence`) VALUES('".$_POST["competence"]."')");
    $id_competence = $dbh->lastInsertId(); 



    $handle = @fopen($_FILES['giftfile']['tmp_name'], "r");
    if($handle) {
        $parse_s=0;
        $txt_q='';
        $score_count = 0;
        $num_q=1;
        while (($buffer = fgets($handle, 4096)) !== false) {
	$buffer= trim($buffer);
#echo mb_substr($buffer, 0,  2);
	    #if(mb_substr($buffer, 0,  2) == '//')
	    if(substr($buffer, 0, 2) == '//')
	        continue;

	    if($buffer == ''){
	    $parse_s=0;
	    $txt_q='';
	    $score_count = 0;
	        continue;
	}

	    if($parse_s==0 && substr($buffer, 0, 2) == '::'){
	    $parse_s=1;
	    #echo substr($buffer, 2 ), '<br>';
	    $sep = stripos($buffer, '::',  2 );
	    $name_q = substr($buffer, 2, $sep-2 );
	    $buffer = substr($buffer, $sep+2 );
	}
	    if($parse_s==1){
	    $sep2 = stripos($buffer, '{',  1 );
	    $percent = 0;
	    if($sep2>0){
	    $txt_q = $txt_q . substr($buffer,0 ,$sep2);
	    $parse_s=2;
		$stmt = $dbh->query("INSERT INTO `eo_question`(`question`, `num_q`, `id_competence`) VALUES('".$txt_q."', ". $num_q. ", " . $id_competence .  ")");
	    $id_question = $dbh->lastInsertId(); 
#echo 'id_question: ', $id_question, '<br>';
		continue;
	    }
	    else {
	    $txt_q = $txt_q . $buffer;
	    }
	}
	    if($parse_s==2){
	    $a_type =  substr($buffer, 0, 1 );
	    $txt_a =  substr($buffer, 1 );
	    $score = 0;
	    $percent = 0;
	    if($a_type == '='){
	    if(substr($buffer, 1, 1 )=='%'){
	        $sep3 = stripos($buffer, '%',  2 );
	        $percent = intval(substr($buffer, 2, $sep3-2 ));
	        $txt_a =  substr($buffer, $sep3+1 );
	    }
	    $score = 1;
	    $score_count = $score_count+1;
	    }
	    if($a_type == '~'){
	    if(substr($buffer, 1, 1 )=='%'){
	        $sep3 = stripos($buffer, '%',  2 );
	        $percent = intval(substr($buffer, 2, $sep3-2 ));
	        $txt_a =  substr($buffer, $sep3+1 );
	        #$buffer = substr($buffer, $sep+1 );
	        #$percent = 1;
	    }
	    }
	    if($a_type == '=' or $a_type == '~'){
	    $stmt = $dbh->query("INSERT INTO `eo_question_answers`(`id_question`, `answer`, `score`, `percent`) VALUES(".$id_question.", '".$txt_a."', " .$score.', ' .$percent.")");
	    }
	    if($a_type == '}'){
	    $parse_s=0;
	    $txt_q='';
		$stmt = $dbh->query("UPDATE `eo_question` SET `score_type`=" . $score_count . " WHERE `id_question`=" .$id_question);
	    $num_q = $num_q+1;
		continue;
	    }
	}
#    		echo $buffer, '<br>';
        }
        if (!feof($handle)) {
	    echo "Ошибка: fgets()\n";
        }
        fclose($handle);
    }

    }


?>
<form method="POST"  enctype="multipart/form-data" >

<p> Компитенция <input type="text" name="competence"></p>


<input name="giftfile" type="file" />

    <p><input type="submit" value="ok"></p>
</form>



</body>
</html>

