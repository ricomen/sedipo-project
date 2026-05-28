<?php
/**
 * @copyright 2025
 */
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="export.xml"');
header('Cache-Control: max-age=0');
header("Pragma: no-cache");

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1



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
$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
echo "<RegistrySet xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n";

try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}

session_start();
$eisot_id_arr = [
        1=>'Оказание первой помощи пострадавшим',
        2=>'Использование (применение) средств индивидуальной защиты',
        3=>'Общие вопросы охраны труда и функционирования системы управления охраной труда',
        4=>'Безопасные методы и приемы выполнения работ при воздействии вредных и (или) опасных производственных факторов, источников опасности, идентифицированных в рамках специальной оценки условий труда и оценки профессиональных рисков',
        //5=>'Безопасные методы и приемы выполнения работ повышенной опасности, к которым предъявляются дополнительные требования в соответствии с нормативными правовыми актами, содержащими государственные нормативные требования охраны труда',
        6=>'Безопасные методы и приемы выполнения земляных работ',
        7=>'Безопасные методы и приемы выполнения ремонтных, монтажных и демонтажных работ зданий и сооружений',
        8=>'Безопасные методы и приемы выполнения работ при размещении, монтаже, техническом обслуживании и ремонте технологического оборудования (включая технологическое оборудование)',
        9=>'Безопасные методы и приемы выполнения работ на высоте',
        10=>'Безопасные методы и приемы выполнения пожароопасных работ',
        11=>'Безопасные методы и приемы выполнения работ в ограниченных и замкнутых пространствах (ОЗП)',
        12=>'Безопасные методы и приемы выполнения строительных работ, в том числе: - окрасочные работы - электросварочные и газосварочные работы',
        13=>'Безопасные методы и приемы выполнения работ, связанных с опасностью воздействия сильнодействующих и ядовитых веществ',
        14=>'Безопасные методы и приемы выполнения газоопасных работ',
        15=>'Безопасные методы и приемы выполнения огневых работ',
        16=>'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией подъемных сооружений',
        17=>'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией тепловых энергоустановок',
        18=>'Безопасные методы и приемы выполнения работ в электроустановках',
        19=>'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией сосудов, работающих под избыточным давлением',
        20=>'Безопасные методы и приемы обращения с животными',
        21=>'Безопасные методы и приемы при выполнении водолазных работ',
        22=>'Безопасные методы и приемы работ по поиску, идентификации, обезвреживанию и уничтожению взрывоопасных предметов',
        23=>'Безопасные методы и приемы работ в непосредственной близости от полотна или проезжей части эксплуатируемых автомобильных и железных дорог',
        24=>'Безопасные методы и приемы работ, на участках с патогенным заражением почвы',
        25=>'Безопасные методы и приемы работ по валке леса в особо опасных условиях',
        26=>'Безопасные методы и приемы работ по перемещению тяжеловесных и крупногабаритных грузов при отсутствии машин соответствующей грузоподъемности и разборке покосившихся и опасных (неправильно уложенных) штабелей круглых лесоматериалов',
        27=>'Безопасные методы и приемы работ с радиоактивными веществами и источниками ионизирующих излучений',
        28=>'Безопасные методы и приемы работ с ручным инструментом, в том числе с пиротехническим',
        29=>'Безопасные методы и приемы работ в театрах'
     ];


    $course_id = 0;
    $course_name = '';
    $e_id = 1;
    $e_course_name = '';
    $stmt = $dbh->prepare("SELECT   DISTINCT   `a_lstream`.`name`, `a_course`.`course_id`,  `a_course`.`name` as `course_name`, `hours`, `a_lstream`.`date_begin`, `a_lstream`.`date_end`, `a_lstream`.`date_protocol`,   `form_of_study`,  `a_cohort`.`course_id`, `category_id`, `a_course`.`eisot_id`  FROM `a_lstream` LEFT JOIN `a_cohort` USING(`lstream_id`)   LEFT JOIN `a_course` USING(`course_id`) WHERE `lstream_id`=?");
    $stmt->execute( [intval($_GET['id'])] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
        $date_begin = $row->date_begin;
        $date_end = $row->date_end;
        $date_protocol = $row->date_protocol;

        $course_id = $row->course_id;
        $course_name = $row->course_name;
        $hours = intval($row->hours);
        $form_of_study = $form_of_study_a[intval($row->form_of_study)];
        $e_id = $row->eisot_id;
        if($e_id<=0){
             $e_id =  eisot_id($course_name);
        }
        $e_course_name = eisot_name($e_id);
    }


    $stmt = $dbh->prepare("SELECT  `name`, `inn`   FROM `a_self`  WHERE `edition`<=?  ORDER BY `edition` DESC  LIMIT 1  ");
    $stmt->execute( [$date_protocol] );
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
          $self_name =  $row->name;
          $self_inn =  $row->inn;
    }


    $i = 1;
    $stmt = $dbh->prepare("SELECT  DISTINCT `lastname`,`firstname`,`middlename`,`subdivision`,`a_job_title`.`name` as `position`, `sex`, `date_of_birth`, `snils`, `inn`, `a_user_counterparty`.`counterparty_id`,  `a_cohort`.`date_num`, `a_cohort`.`protocol_num`,  YEAR(`a_cohort`.`date_num`) as `year_protocol`, MONTH(`a_cohort`.`date_num`) as `month_protocol`,  DAYOFMONTH(`a_cohort`.`date_num`) as `day_protocol`, `a_users`.`user_id`, `order_id`, `a_order_course`.`group_number`     FROM `a_lstream`  LEFT JOIN  `a_cohort` USING(`lstream_id`)   LEFT JOIN  `a_order_course` USING(`cohort_id`)  LEFT JOIN  `a_order_users` USING(`item_id`)   LEFT JOIN `a_user_counterparty` USING(`user_counterparty_id`)   LEFT JOIN `a_users` ON `a_user_counterparty`.`user_id`=`a_users`.`user_id`  LEFT JOIN `a_job_title` USING(`job_title_id`)    WHERE `a_lstream`.`lstream_id`=?  AND `a_order_users`.`user_lock`=0  ");
    $stmt->execute( [intval($_GET['id'])] );
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
         $stmt1 = $dbh->prepare("SELECT count(*) as `count` FROM  `a_lstream_eisot` WHERE `user_id`=?  AND `lstream_id`=? ");
         $stmt1->execute( [$row->user_id, intval($_GET['id']) ] );
         if($row1 = $stmt1->fetch(PDO::FETCH_OBJ)) {  
                if( $row1->count > 0 )
                        continue;
         }
         
         $custom_protocol_number = '';
         $stmt2 = $dbh->prepare('SELECT `group_id`, `custom_protocol_num`   FROM `a_order_group`   WHERE `order_id`=? AND `course_id`=? AND `group_number`=?  ');
         $stmt2->execute([$row->order_id, $course_id, $row->group_number ]);
         if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                  $custom_protocol_number = $row2->custom_protocol_num;
         }
         if( $custom_protocol_number!='' )
               $protocol_num = $custom_protocol_number;
         else if($is_short_certificate_num)
               $protocol_num = sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);
         else
               $protocol_num = substr(sprintf('%04d', $row->year_protocol), 2, 2).'/'.sprintf('%02d', $row->month_protocol).'/'.sprintf('%02d', $row->day_protocol).'/'.sprintf('%d', $row->protocol_num);

         $stmt2 = $dbh->prepare("SELECT DISTINCT  `name`, `inn` FROM `a_counterparty`  WHERE `counterparty_id`=?");
         $stmt2->execute( [ $row->counterparty_id ] );
         if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  

         }

        $snils = mb_str_split( str_replace(['-', ' '], ['', ''], $row->snils), 3);
        echo "    <RegistryRecord>\n";
        echo "        <Worker>\n";
        echo "            <LastName>$row->lastname</LastName>\n";
        echo "            <FirstName>$row->firstname</FirstName>\n";
        echo "            <MiddleName>$row->middlename</MiddleName>\n";
        echo "            <Snils>$snils[0]-$snils[1]-$snils[2] $snils[3]</Snils>\n";
        echo "            <Position>$row->position</Position>\n";
        echo "            <EmployerInn>$row2->inn</EmployerInn>\n";
        echo "            <EmployerTitle>$row2->name</EmployerTitle>\n";
        echo "        </Worker>\n";
        echo "        <Organization>\n";
        echo "            <Inn>$self_inn</Inn>\n";
        echo "            <Title>$self_name</Title>\n";
        echo "        </Organization>\n";
        echo "        <Test isPassed=\"true\" learnProgramId=\"$e_id\">\n";
        echo "            <Date>$date_protocol</Date>\n";
        echo "            <ProtocolNumber>$protocol_num</ProtocolNumber>\n";
        echo "            <LearnProgramTitle>$e_course_name</LearnProgramTitle>\n";
        echo "        </Test>\n";
        echo "    </RegistryRecord>\n";

        $i = $i+1;
    }
echo "</RegistrySet>\n";


function eisot_id($course_name) {
    global $eisot_id_arr;

     return array_search($course_name, $eisot_id_arr);
}


function eisot_name($eisotid) {
    global $eisot_id_arr;

     return  $eisot_id_arr[$eisotid];
}



/*
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<RegistrySet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <RegistryRecord>
        <Worker>
            <LastName>Иванов</LastName>
            <FirstName>Иван</FirstName>
            <MiddleName>Иванович</MiddleName>
            <Snils>123-456-789 00</Snils>
            <Position>Водитель</Position>
            <EmployerInn>1234567890</EmployerInn>
            <EmployerTitle>ООО «СпецТех»</EmployerTitle>
        </Worker>
        <Organization>
            <Inn>1234567890</Inn>
            <Title>ООО УЧЕБНЫЙ КОМБИНАТ</Title>
        </Organization>
        <Test isPassed="true" learnProgramId="1">
            <Date>2024-01-31</Date>
            <ProtocolNumber>19</ProtocolNumber>
            <LearnProgramTitle>Оказание первой помощи пострадавшим </LearnProgramTitle>
        </Test>
    </RegistryRecord>
</RegistrySet>



 Коды и названия программ:
ID      Название программы в реестре
1       Оказание первой помощи пострадавшим
2       Использование (применение) средств индивидуальной защиты
3       Общие вопросы охраны труда и функционирования системы управления охраной труда
4       Безопасные методы и приемы выполнения работ при воздействии вредных и (или) опасных производственных факторов, источников опасности, идентифицированных в рамках специальной оценки условий труда и оценки профессиональных рисков
5       Безопасные методы и приемы выполнения работ повышенной опасности, к которым предъявляются дополнительные требования в соответствии с нормативными правовыми актами, содержащими государственные нормативные требования охраны труда
6       Безопасные методы и приемы выполнения земляных работ
7       Безопасные методы и приемы выполнения ремонтных, монтажных и демонтажных работ зданий и сооружений
8       Безопасные методы и приемы выполнения работ при размещении, монтаже, техническом обслуживании и ремонте технологического оборудования (включая технологическое оборудование)
9       Безопасные методы и приемы выполнения работ на высоте
10      Безопасные методы и приемы выполнения пожароопасных работ
11      Безопасные методы и приемы выполнения работ в ограниченных и замкнутых пространствах (ОЗП)
12      Безопасные методы и приемы выполнения строительных работ, в том числе: - окрасочные работы - электросварочные и газосварочные работы
13      Безопасные методы и приемы выполнения работ, связанных с опасностью воздействия сильнодействующих и ядовитых веществ
14      Безопасные методы и приемы выполнения газоопасных работ
15      Безопасные методы и приемы выполнения огневых работ
16      Безопасные методы и приемы выполнения работ, связанные с эксплуатацией подъемных сооружений
17      Безопасные методы и приемы выполнения работ, связанные с эксплуатацией тепловых энергоустановок
18      Безопасные методы и приемы выполнения работ в электроустановках
19      Безопасные методы и приемы выполнения работ, связанные с эксплуатацией сосудов, работающих под избыточным давлением
20      Безопасные методы и приемы обращения с животными
21      Безопасные методы и приемы при выполнении водолазных работ
22      Безопасные методы и приемы работ по поиску, идентификации, обезвреживанию и уничтожению взрывоопасных предметов
23      Безопасные методы и приемы работ в непосредственной близости от полотна или проезжей части эксплуатируемых автомобильных и железных дорог
24      Безопасные методы и приемы работ, на участках с патогенным заражением почвы
25      Безопасные методы и приемы работ по валке леса в особо опасных условиях
26      Безопасные методы и приемы работ по перемещению тяжеловесных и крупногабаритных грузов при отсутствии машин соответствующей грузоподъемности и разборке покосившихся и опасных (неправильно уложенных) штабелей круглых лесоматериалов
27      Безопасные методы и приемы работ с радиоактивными веществами и источниками ионизирующих излучений
28      Безопасные методы и приемы работ с ручным инструментом, в том числе с пиротехническим
29      Безопасные методы и приемы работ в театрах 

*/

/*
earnProgramId - порядковый номер обучения
LearnProgramTitle - наименование обучения

1 - Оказание первой помощи пострадавшим
2 - Использование (применение) средств индивидуальной защиты
3 - Общие вопросы охраны труда и функционирования системы управления охраной труда
4 - Безопасные методы и приемы выполнения работ при воздействии вредных и (или) опасных производственных факторов, источников опасности, идентифицированных в рамках специальной оценки условий труда и оценки профессиональных рисков
6 - Безопасные методы и приемы выполнения земляных работ
7 - Безопасные методы и приемы выполнения ремонтных, монтажных и демонтажных работ зданий и сооружений
8 - Безопасные методы и приемы выполнения работ при размещении, монтаже, техническом обслуживании и ремонте технологического оборудования (включая технологическое оборудование)
9 - Безопасные методы и приемы выполнения работ на высоте
10 -Безопасные методы и приемы выполнения пожароопасных работ
11 - Безопасные методы и приемы выполнения работ в ограниченных и замкнутых пространствах (ОЗП)
12 - Безопасные методы и приемы выполнения строительных работ, в том числе: - окрасочные работы - электросварочные и газосварочные работы
13 - Безопасные методы и приемы выполнения работ, связанных с опасностью воздействия сильнодействующих и ядовитых веществ
14 - Безопасные методы и приемы выполнения газоопасных работ
15 - Безопасные методы и приемы выполнения огневых работ
16 - Безопасные методы и приемы выполнения работ, связанные с эксплуатацией подъемных сооружений
17 - Безопасные методы и приемы выполнения работ, связанные с эксплуатацией тепловых энергоустановок
18 - Безопасные методы и приемы выполнения работ в электроустановках
19 - Безопасные методы и приемы выполнения работ, связанные с эксплуатацией сосудов, работающих под избыточным давлением
20 - Безопасные методы и приемы обращения с животными
21 - Безопасные методы и приемы при выполнении водолазных работ
22 - Безопасные методы и приемы работ по поиску, идентификации, обезвреживанию и уничтожению взрывоопасных предметов
23 - Безопасные методы и приемы работ в непосредственной близости от полотна или проезжей части эксплуатируемых автомобильных и железных дорог
24 - Безопасные методы и приемы работ, на участках с патогенным заражением почвы
25 - Безопасные методы и приемы работ по валке леса в особо опасных условиях
26 - Безопасные методы и приемы работ по перемещению тяжеловесных и крупногабаритных грузов при отсутствии машин соответствующей грузоподъемности и разборке покосившихся и опасных (неправильно уложенных) штабелей круглых лесоматериалов
27 - Безопасные методы и приемы работ с радиоактивными веществами и источниками ионизирующих излучений
28 - Безопасные методы и приемы работ с ручным инструментом, в том числе с пиротехническим
29 - Безопасные методы и приемы работ в театрах
*/

?>
