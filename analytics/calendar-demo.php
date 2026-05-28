<!DOCTYPE html>
<html lang="ru">
 <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
  <title>Event Calendar</title>
  <link rel="stylesheet" type="text/css" href="calendar.css">

    <link href="/css/bootstrap-lumen.min.css" rel="stylesheet" >
    <script src="/css/bootstrap.bundle.min.js" ></script>

 </head>
 <body>


<?php
 require_once('calendar-inc.php'); //Подключить класс календар
 
 $events = [ 
  '23.02' => 'День защитника Отечества',
  '08.03' => 'Международный женский день',
  '21.09.2022' => 'test22',
  '01.01' => 'Новый год', //выведется каждый год
  '4'    => 'test'
 ];
 
 $l_events = [ 
   ['2025-06-13', '25-05-27.3', 'Использование (применение) СИЗ'],
   ['2025-06-13', '25-04-14.1', 'Специалист по пожарной профилактике'], 
   ['2025-06-13', '25-04-14.3', '2Специалист по пожарной профилактике'], 
   ['2025-06-16', '25-04-14.2', 'Б.1.10. Проектирование, строительство, реконструкция, техническое перевооружение, капитальный ремонт, консервация и ликвидация опасных производственных объектов нефтегазоперерабатывающих и нефтехимических производств'] 
 ];

 //На текущий год:
 //echo Calendar::getInterval(date('01.Y'), date('12.Y'), $events);
 
 //Можно вместо этого раскоммментировать другие варианты вызова:
 
 //На фиксированный интервал январь - октябрь 2022:
 //echo Calendar::getInterval(date('01.2022'), date('10.2022'), $events);
 
 //Только на текущий месяц и без событий:
 echo Calendar::getMonth(date('n'), date('Y'),  $l_events, $events );
 
 //На год вперёд от текущего месяца:
 //echo Calendar::getInterval(date('n.Y'), date('n.Y', strtotime('+11 month')), $events);
 
 //На 10 лет вперёд от января текущего года:
 //echo Calendar::getInterval(date('01.Y'), date('01.Y', strtotime('+10 year')), $events);
?>

 </body>
</html>
