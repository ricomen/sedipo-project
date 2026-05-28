<?php
header('Content-Type: text/xml, application/xml');
header('Content-disposition: filename="export.xml"');

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


if($rprg_id>0){

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
	$stmt = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`   FROM `p_item`  WHERE `id`=? AND `parent`=0  ORDER BY `num`  ');
	$stmt->execute([$rprg_id]);
    	while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
	    //$list[] =  ["p_Id"=>$row->p_id, "num"=>$num,  "name"=>$row->name, "form"=>$row->form,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,"hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a,  "parent"=>$row->parent, "description"=>$row->description  ];
	    $list[] =  ["модуль_Id"=>$row->p_id, "N_пп"=>$num,  "Наименование_учебных_модулей"=>$row->name, "Контроль_знаний_форма"=>$row->form, "Заочная_форма_Пр_занятий"=>$row->hours_o,  "Заочная_форма_Лекций"=>$row->hours_z, "Очная_форма_Лекций"=>$row->hours_l, "Очная_форма_Пр_занятий"=>$row->hours_p,  "Самоподготовка"=>$row->hours_s,  "Контроль_знаний_часов"=>$row->hours_a,   "Содержание"=>$row->description, "Нормативно_правовая_информация"=>$row->np, "Литература"=>$row->bib,   "parent"=>$row->parent];
	    $num1 = 1;

	    $stmt1 = $dbh->prepare('SELECT `p_id`,  `name`, `form`,  `hours_o`,  `hours_z`, `hours_l`,  `hours_p`, `hours_s`,  `hours_a`,  `parent`, `description`, `np`, `bib`    FROM `p_item`  WHERE `id`=? AND `parent`=?  ORDER BY `num`  ');
	    $stmt1->execute([$rprg_id, $row->p_id]);
    	    while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
		$sum_o = $sum_o + intval($row1->hours_o);
		$sum_z = $sum_z + intval($row1->hours_z);
		$sum_l = $sum_l + intval($row1->hours_l);
		$sum_p = $sum_p + intval($row1->hours_p);
		$sum_s = $sum_s + intval($row1->hours_s);
		$sum_a = $sum_a + intval($row1->hours_a);
		$list[] =  ["модуль_Id"=>$row1->p_id, "N_пп"=>$num .'.'. $num1,  "Наименование_учебных_модулей"=>$row1->name, "Контроль_знаний_форма"=>$row1->form, "Заочная_форма_Пр_занятий"=>$row1->hours_o,  "Заочная_форма_Лекций"=>$row1->hours_z, "Очная_форма_Лекций"=>$row1->hours_l, "Очная_форма_Пр_занятий"=>$row1->hours_p,  "Самоподготовка"=>$row1->hours_s,  "Контроль_знаний_часов"=>$row1->hours_a,   "Содержание"=>$row1->description, "Нормативно_правовая_информация"=>$row1->np, "Литература"=>$row1->bib,   "parent"=>$row1->parent];
		$num1 = $num1 +1;
	    }
	    //$list[] =  ["p_Id"=>$row->p_id, "num"=>0,  "name"=>'Итого: ', "form"=>$row->form,  "hours_o"=>$row->hours_o,  "hours_z"=>$row->hours_z,"hours_l"=>$row->hours_l, "hours_p"=>$row->hours_p,  "hours_s"=>$row->hours_s, "hours_a"=>$row->hours_a,  "parent"=>$row->parent  ];
	    $num = $num+1;
	}

	$stmt = $dbh->prepare('SELECT `id`,  `name`, `form`, `form1`, `form2`, `form3`, `att_i`, `hours`, `p0`, `p1`, `p2`, `p3`, `p4`, `p5`, `p6`,  `hours_o`, `hours_z`, `hours_l`,  `hours_p`, `hours_s`, `hours_a`, `hours_k`, `hours_i`, `s_fl`  FROM `p_rprg`  WHERE `id`=?');
	$stmt->execute([$rprg_id]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  

	    //$rc =  ["Id"=>$row->id,  "Название_программы"=>$row->name,  "Очная"=>$row->form1, "Заочная"=>$row->form2, "Очно-заочная"=>$row->form3, "Итоговая_аттестация_форма"=>$row->att_i, "Нормативный_срок_освоения_программы"=>$row->hours,   "Аннотация"=>$row->p0, "Цель_реализации_программы"=>$row->p1, "Планируемые_результаты_обучения"=>$row->p2, "Оценочные_материалы"=>$row->p3, "Составители_программы"=>$row->p4, "Заочная_форма_Пр_занятий"=>$row->hours_o, "Заочная_форма_Лекций"=>$row->hours_z, "Очная_форма_Лекций"=>$row->hours_l, "Очная_форма_Пр_занятий"=>$row->hours_p, "Самоподготовка"=>$row->hours_s, "Контроль_знаний_часов"=>$row->hours_a, "Консультации"=>$row->hours_k, "Итоговая_аттестация_часов"=>$row->hours_i, "Стажировки_Самоподготовка"=>$row->s_fl, "модули"=>$list ];
	    $rc =  ["Id"=>$row->id,  "Название_программы"=>$row->name,  "Очная"=>$row->form1, "Заочная"=>$row->form2, "Очно-заочная"=>$row->form3, "Итоговая_аттестация_форма"=>$row->att_i, "Нормативный_срок_освоения_программы"=>$row->hours,   "Аннотация"=>$row->p0, "Цель_реализации_программы"=>$row->p1, "Планируемые_результаты_обучения"=>$row->p2, "Оценочные_материалы"=>$row->p3, "Составители_программы"=>$row->p4, "Материально-технические условия реализации программы и образовательные технологии"=>$row->p5, "Формеы аттестации"=>$row->p6, "Заочная_форма_Пр_занятий"=>$row->hours_o, "Заочная_форма_Лекций"=>$row->hours_z, "Очная_форма_Лекций"=>$row->hours_l, "Очная_форма_Пр_занятий"=>$row->hours_p, "Самоподготовка"=>$row->hours_s, "Контроль_знаний_часов"=>$row->hours_a, "Консультации"=>$row->hours_k, "Итоговая_аттестация_часов"=>$row->hours_i, "Стажировки_Самоподготовка"=>$row->s_fl, "Норма_часов"=>8,  "модули"=>$list ];
	}


    }
    //$result = [ "rprg"=>$rc, "item"=>$list ];
//print_r($result);
    //echo json_encode($result, JSON_UNESCAPED_UNICODE);

    $xml = array_to_xml($rc, new SimpleXMLElement('<root/>'))->asXML();
    echo "$xml\n";
}



function array_to_xml(array $arr, SimpleXMLElement $xml) {
        foreach ($arr as $k => $v) {

            $attrArr = array();
            $kArray = explode(' ',$k);
            $tag = array_shift($kArray);

            if (count($kArray) > 0) {
                foreach($kArray as $attrValue) {
                    $attrArr[] = explode('=',$attrValue);                   
                }
            }

            if (is_array($v)) {
                if (is_numeric($k)) {
                    array_to_xml($v, $xml);
                } else {
                    $child = $xml->addChild($tag);
                    if (isset($attrArr)) {
                        foreach($attrArr as $attrArrV) {
                            $child->addAttribute($attrArrV[0],$attrArrV[1]);
                        }
                    }                   
                    array_to_xml($v, $child);
                }
            } else {
                $child = $xml->addChild($tag, $v);
                if (isset($attrArr)) {
                    foreach($attrArr as $attrArrV) {
                        $child->addAttribute($attrArrV[0],$attrArrV[1]);
                    }
                }
            }               
        }

        return $xml;
    }