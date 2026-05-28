<html>
<head>
	<meta charset="UTF-8">
	<title>Предварительный просмотр</title>
	<style>
		.table-border td{border:none;}
		*{margin:0; padding:0;}
		body {background:#ccc;}
		.wrapper {width: 80vw; margin: 5rem auto; background: #fff; padding: 5rem 2rem}
		h2 {font-size: 20px}
		h1,h2 {margin-bottom: 2rem;}
		h3,h4,p, table {margin-top: 2rem;margin-bottom: 2rem;}
		table {border-collapse: collapse;}
		th {
  font-size: 13px;
  font-weight: normal;

  border-top: 4px solid #000;
  border-bottom: 1px solid #000;

  padding: 8px;
}
td {

  border-bottom: 1px solid #000;

  border-top: 1px solid #000;
  padding: 8px;
}
tr:hover td {
  background: #ccddff;
}
	</style>
	
</head>
<body>
	<div class="wrapper">

	
	<?php
/**
 * @copyright 2022
 */


require_once 'config.php';
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



$rprg_id = $_GET['id'];



    $rc =  ["Id"=>0,  "name"=>'', "form"=>'', "hours"=>'' ];
    $list = [];
    $num = 1;
    $sum_o =  0;
    $sum_z= 0;
    $sum_l = 0;
    $sum_p = 0;
    $sum_s = 0;
    $sum_a  = 0;
    $sum_sum = 0;
    if($rprg_id >0 ) {



	echo '<table width="100%" border="0" class="table-border"><tr><td>Согласовано:<br>директор:<br /> АО «______________»<br>_________________И.О.Ф.:<br /><br>
«____» __________________  2022г.</td><td>Утверждаю:<br>Директор <br/><br/><br/>_________________<br />«____» __________________  2022г.</td></tr></table>';

	echo '<p><br/></p> <h2 align="center">ДОПОЛНИТЕЛЬНАЯ ПРОФЕССИОНАЛЬНАЯ  ПРОГРАММА ПОВЫШЕНИЯ КВАЛИФИКАЦИИ</h2>';

	$stmt = $dbh->prepare('SELECT `id`,  `name`, `form`, `form1`, `form2`, `form3`, `att_i`, `hours`, `p1`, `p2`,  `hours_o`, `hours_z`, `hours_l`,  `hours_p`, `hours_s`, `hours_a`, `hours_k`, `hours_i`  FROM `p_rprg`  WHERE `id`=?');
	$stmt->execute([$rprg_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $hours_i = $row->hours_i;
	    $hours_k = $row->hours_k;

	    echo '<h2 align="center">«' . $row->name . '»</h2>';
	    echo '<p align="center">Уфа 20____</p>';


	    echo '<h2 align="center">Аннотация</h2>';

	    echo '<p> Дополнительная профессиональная программа повышения квалификации «'. $row->name  .'» разработана для повышения квалификации персонала АО «______________» и овладения слушателями новых компетенций, необходимых для повышения производительности труда подразделений.</p>';
	    echo '<p> Программа разработана учебно-методическим отделом Автономной некоммерческой организацией дополнительного профессионального образования «Центр профессиональной подготовки кадров». </p>';
	    echo '<p>Нормативный срок освоения программы '.$row->hours.' часов при очной/заочной форме подготовки.  </p>';
	    echo '<p>Рассмотрено и утверждено на заседании методической комиссии:<br />    Протокол №                       от                               20___ г.</p>';
	    echo '<table width="100%" border="0"><tr><td>Руководитель учебно-методического отдела</td><td>Аюпова Р.Р.</td></tr><tr><td>Председатель методической комиссии</td><td>Ахметжанова С.А.</td></tr><tr><td>Методист</td><td>Вазитдинова Г.М.</td></tr></table>';

	    echo '<h2 align="center">1. ПОЯСНИТЕЛЬНАЯ ЗАПИСКА</h2>';
	    echo '<p> Дополнительная профессиональная программа повышения квалификации «'. $row->name  .'» разработана в соответствии с нормами Федерального закона от 29 декабря 2012 г. № 273-ФЗ "Об образовании в Российской Федерации" и с учетом требований Порядка организации и осуществления образовательной деятельности по дополнительным профессиональным программам, утвержденного приказом Министерства образования и науки Российской Федерации от 1 июля 2013 г. № 499, </p>';
	    echo '<h3 >Цель реализации программы</h3>';
	    echo '<p>'. $row->p1  .'</p>';

	    echo '<h3 >Результаты освоения программы</h3>';
	    echo '<p>'. $row->p2  .'</p>';

	    echo '<h3 >Нормативный срок прохождения программы</h3>';
	    echo '<p>Нормативный срок освоения программы составляет '.$row->hours.' часов' .$row->hours_l. ' часов – теоретических занятий очных '.$row->hours_z.' . часов – теоретических занятий дистанционно; '.$row->hours_k.' часов - консультация и итоговая аттестация (защита междисциплинарного проекта), включая все виды аудиторной учебной работы слушателя.   </p>';

//	    $rc =  ["Id"=>$row->id,  "name"=>$row->name, "form"=>$row->form, "form1"=>$row->form1, "form2"=>$row->form2, "form3"=>$row->form3, "att_i"=>$row->att_i, "hours"=>$row->hours, "s_hours_o"=>$sum_o, "s_hours_z"=>$sum_z , "s_hours_l"=>$sum_l, "s_hours_p"=>$sum_p, "s_hours_s"=>$sum_s, "s_hours_a"=>$sum_a, "p1"=>$row->p1, "p2"=>$row->p2, "hours_o"=>$row->hours_o, "hours_z"=>$row->hours_z, "hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p, "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a, "hours_k"=>$row->hours_k, "hours_i"=>$row->hours_i ];
	}
		
		?>
		<h2 align="center">2. КАЛЕНДАРНЫЙ УЧЕБНЫЙ ГРАФИК</h2>
		<p>Календарный учебный график составляется в форме расписания занятий при наборе группы и прилагается к программе повышения квалификации.
</p>
		<p>1. Продолжительность учебного года<br>
Начало учебных занятий – по формированию учебной группы.<br>
Продолжительность учебного года совпадает с календарным.<br>
2. Регламент образовательного процесса:<br>
Продолжительность учебной недели –5 дней для очной формы обучения <br>
Не более 8 часов в день.<br>
3. Продолжительность занятий:<br>
Продолжительность занятий в группах:<br>
- 45 минут;<br>
- перерыв между занятиями составляет - 10 минут</p>
		
		<?php
		
		
		

	    echo '<h2  align="center">3.  УЧЕБНЫЙ ПЛАН</h2>';
	    echo '<p align="center">дополнительной профессиональной программы  повышения квалификации «'. $row->name  .'» </p>';
	    echo '<table width="100%" border="1">';
	    echo '<tr><td>№</td><td>Наименование <br />разделов</td><td>Всего <br />час.</td><td colspan="2">Очные занятия</td><td colspan="2">Заочные занятия</td><td>Стажировка / <br >Самоподготовка</td><td>Форма контроля</td></tr>';
	    echo '<tr><td></td><td> </td><td></td><td>Лекции</td><td>Практ. <br />занятия</td><td>Лекции</td><td>Практ. <br />занятия</td><td></td><td></td></tr>';
	    echo '<tr><td>1</td><td>Учебные предметы</td><td>' .$row->hours. '</td><td>' .$row->hours_l. '</td><td>' .$row->hours_p. '</td><td>' .$row->hours_z. '</td><td>' .$row->hours_o. '</td><td>' .$row->hours_s. '</td><td>' .$row->att_i. '</td></tr>';
	    echo '<tr><td>4</td><td>Консультация </td><td></td><td>' .$row->hours_k. '</td><td></td><td></td><td></td><td></td><td></td></tr>';
	    echo '<tr><td>5</td><td>Итоговая форма контроля</td><td></td><td></td><td></td><td></td><td></td><td></td><td>' .$row->att_i. '</td></tr>';
	    echo '</table>';


	    $s_sum_o = 0;
	    $s_sum_z = 0;
	    $s_sum_l = 0;
	    $s_sum_p = 0;
	    $s_sum_s = 0;
	    $s_sum_a = 0;
	    $s_sum_sum = 0;


	    echo '<h2  align="center">4. УЧЕБНО-ТЕМАТИЧЕСКИЙ ПЛАН</h2>';
	    echo '<p align="center">дополнительной профессиональной программы  повышения квалификации «'. $row->name  .'» </p>';
	echo '<table width="100%" border="1">';
	    echo '<tr><td>№</td><td>Наименование <br />разделов</td><td>Всего <br />час.</td><td colspan="2">Очные занятия</td><td colspan="2">Заочные занятия</td><td>Стажировка / <br >Самоподготовка</td><td  colspan="2">Форма контроля</td></tr>';
	    echo '<tr><td></td><td> </td><td></td><td>Лекции</td><td>Практ. <br />занятия</td><td>Лекции</td><td>Практ. <br />занятия</td><td></td><td></td><td>часы</td></tr>';
	$stmt = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`   FROM `p_item`  WHERE `id`=? AND `parent`=0  ORDER BY `num`  ');
	$stmt->execute([$rprg_id]);
    	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    //$list[] =  ["p_Id"=>$row->p_id, "num"=>$num,  "name"=>$row->name, "form"=>$row->form,  "hours_o"=>'',  "hours_z"=>'',"hours_l"=>'', "hours_p"=>'',  "hours_s"=>'', "hours_a"=>'',  "parent"=>$row->parent   ];
	    echo '<tr><td>'. $num .'</td><td  colspan="9">' . $row->name .'</td></tr>';
	    $num1 = 1;

	    $sum_o = 0;
	    $sum_z = 0;
	    $sum_l = 0;
	    $sum_p = 0;
	    $sum_s = 0;
	    $sum_a = 0;
	    $sum_sum = 0;

	    $stmt1 = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`   FROM `p_item`  WHERE `id`=? AND `parent`=?  ORDER BY `num`  ');
	    $stmt1->execute([$rprg_id, $row->p_id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
		$hours1 = $row1->hours_l + $row1->hours_p + $row1->hours_z + $row1->hours_o + $row1->hours_s + $row1->hours_a; 
		$sum_sum = $sum_sum + $hours1;
		//$list[] =  ["p_Id"=>$row1->p_id, "num"=>$num .'.'. $num1,  "name"=>$row1->name, "form"=>$row1->form, "hours_o"=>$row1->hours_o,  "hours_z"=>$row1->hours_z, "hours_l"=>$row1->hours_l, "hours_p"=>$row1->hours_p,  "hours_s"=>$row1->hours_s,  "hours_a"=>$row1->hours_a, "parent"=>$row1->parent   ];
		echo '<tr><td>'. $num .'.'. $num1 .'</td><td>'. $row1->name. '</td><td>' .$hours1. '</td><td>'. $row1->hours_l .'</td><td>'. $row1->hours_p .'</td><td>'. $row1->hours_z .'</td><td>'. $row1->hours_o .'</td><td>'. $row1->hours_s .'</td><td>'. $row1->form .'</td><td>'. $row1->hours_a .'</td></tr>';
		$num1 = $num1 +1;
	    }
	    $s_sum_o = $s_sum_o + $sum_o;
	    $s_sum_z = $s_sum_z + $sum_z ;
	    $s_sum_l = $s_sum_l + $sum_l;
	    $s_sum_p = $s_sum_p + $sum_p ;
	    $s_sum_s = $s_sum_s + $sum_s;
	    $s_sum_a = $s_sum_a + $sum_a;
	    $s_sum_sum = $s_sum_sum + $sum_sum;

	    //$list[] =  ["p_Id"=>$row->p_id, "num"=>0,  "name"=>'Итого: ', "form"=>$row->form,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,"hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a,  "parent"=>$row->parent  ];
	    echo '<tr><td></td><td>Итого: </td><td>' .$sum_sum. '</td><td>'. $sum_l .'</td><td>'. $sum_p .'</td><td>'. $sum_z .'</td><td>'. $sum_o .'</td><td>'. $sum_s .'</td><td></td><td>'. $sum_a .'</td></tr>';
	    $num = $num+1;
	}
	    echo '<tr><td></td><td>Консультации: </td><td>' .$hours_k. '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	    echo '<tr><td></td><td>Итоговая аттестация: </td><td>' .$hours_i. '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	    $s_sum_sum = $s_sum_sum + $hours_k + $hours_i;
	    echo '<tr><td></td><td>Всего: </td><td>' .$s_sum_sum. '</td><td>'. $s_sum_l .'</td><td>'. $s_sum_p .'</td><td>'. $s_sum_z .'</td><td>'. $s_sum_o .'</td><td>'. $s_sum_s .'</td><td></td><td>'. $s_sum_a .'</td></tr>';
	echo '</table>';

	echo '<h2  align="center">5. СОДЕРЖАНИЕ ПРОГРАММЫ</h2>';
	$num = 1;
	$stmt = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`     FROM `p_item`  WHERE `id`=? AND `parent`=0   ORDER BY `num` ');
	$stmt->execute([$rprg_id]);
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $num1 = 1;
	    echo '<p>'. $num.'. '. $row->name. '<br />'. $row->description .'<p>';
	    $stmt1 = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`    FROM `p_item`  WHERE `id`=? AND `parent`=?   ORDER BY `num` ');
	    $stmt1->execute([$rprg_id, $row->p_id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
	      echo '<p>'. $num .'.'. $num1 .'. '. $row1->name. '<br />'. $row1->description .'<p>';
		$num1 = $num1 +1;
	    }
	    $num = $num+1;
	} ?>

	<h2  align="center">6. ОРГАНИЗАЦИОННО-ПЕДАГОГИЧЕСКИЕ УСЛОВИЯ РЕАЛИЗАЦИИ ПРОГРАММЫ</h2>
	<h3  align="center"> Материально-технические условия реализации программы и образовательные  технологии</h3>

	<h2  align="center">7. ФОРМЫ АТТЕСТАЦИИ</h2>
	<p>&nbsp;</p>
	<p>Итоговая аттестация проходит в форме итогового тестирования.</p>
	<p><strong>Критерии оценивания итогового тестирования</strong>:</p>
	<p>Оценка за контроль ключевых компетенций обучающихся производится по пятибалльной системе. При выполнении заданий ставится отметка:</p>

	<p>«3» - за 50-70% правильно выполненных заданий,</p>
	<p>«4» - за 70-85%&nbsp; правильно выполненных заданий,</p>
	<p>«5» - за правильное выполнение более 85% заданий</p>
		
		
	<h2 align="center">8. ОЦЕНОЧНЫЕ МАТЕРИАЛЫ</h2>
<?php 
	


	echo '<h2  align="center">9. СПИСОК ЛИТЕРАТУРЫ</h2>';
	$num = 1;
	$stmt = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`     FROM `p_item`  WHERE `id`=? AND `parent`=0   ORDER BY `num` ');
	$stmt->execute([$rprg_id]);
	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    $num1 = 1;
	    echo '<p>'. $num.'. '. $row->name. '<br />'. $row->bib .'<p>';
	    $stmt1 = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`    FROM `p_item`  WHERE `id`=? AND `parent`=?   ORDER BY `num` ');
	    $stmt1->execute([$rprg_id, $row->p_id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
	      echo '<p>'. $num .'.'. $num1 .'. '. $row1->name. '<br />'. $row1->bib .'<p>';
		$num1 = $num1 +1;
	    }
	    $num = $num+1;
	}


	echo '<h2  align="center">10. СОСТАВИТЕЛИ ПРОГРАММЫ</h2>';


    }


?></div></body> </html>