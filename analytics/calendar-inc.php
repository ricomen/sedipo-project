<?php


$l_items = [];
$l_items_name = [];
$l_items_status = [];
$l_items_id = [];
$l_items_teacher = [];
$l_items_counterparty_list = [];
$background_colors =['#CACFDE', '#c1e3ff', '#f8c390', '#c0b0df', '#54e38e', '#FD7585', '#48CFAD', '#4FC1E9', '#fd937f', '#5D9CEC', '#FC87C0', '#7AC0BD', '#d9d4c0', '#a0a9a8',  '#fff' ];
$background_p = ['', ' background: repeating-linear-gradient(-45deg, #777 0, #fff 1px, transparent 1px, transparent 4px); ',
     ' background: repeating-linear-gradient(-45deg, #777 0, #fff 1px, transparent 1px, transparent 4px), repeating-linear-gradient(45deg, #777 0, #fff 1px, transparent 1px, transparent 4px); '];

class Calendar {

  public static function getMonth($year, $month, $l_events, $events = []) { 
  global $l_items, $l_items_name, $l_items_status,  $l_items_id, $l_items_teacher, $l_items_counterparty_list, $background_colors, $background_p;

  $months = [
   1  => 'Январь',    2  => 'Февраль', 3  => 'Март',   4  => 'Апрель',
   5  => 'Май',       6  => 'Июнь',    7  => 'Июль',   8  => 'Август',
   9  => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
  ];
  $month = intval($month);
  $out = "\n".' <div >
  <p>' . $months[$month] . ' ' . $year . '</p>
  <table width="100%"  class="table table-bordered  border-primary" border="1" >
  <tr valign="top">
   <th width="14%" >Пн</th>
   <th width="14%" >Вт</th>
   <th width="14%" >Ср</th>
   <th width="14%" >Чт</th>
   <th width="14%" >Пт</th>
   <th width="14%" style="color: red;" >Сб</th>
   <th width="14%" style="color: red;" >Вс</th>
  </tr>'."\n";
  $day_week = date('N', mktime(0, 0, 0, $month, 1, $year)) - 1;
  $out.= '  <tr valign="top">'."\n";
  for ($x = 0; $x < $day_week; $x++) {
   $out.= '   <td>&nbsp;</td>'."\n";
  }
  $days_counter = 0;  
  $days_month = date('t', mktime(0, 0, 0, $month, 1, $year));
  for ($day = 1; $day <= $days_month; $day++) {
   $current_date = sprintf("%4d-%02d-%02d", $year, $month, $day);
   if (date('j.n.Y') == $day . '.' . $month . '.' . $year) $class = ' today';
   elseif (time() > strtotime($day . '.' . $month . '.' . $year)) $class = ' past';
   else $class = '';
   $event_show = false;
   $event_text = [];
   if (!empty($events)) {
    foreach ($events as $date => $text) {
     $date = explode('.', $date);
     if (count($date) == 3) {
      $y = explode(' ', $date[2]);
      if (count($y) == 2) $date[2] = $y[0];
      if ($day == intval($date[0]) && $month == intval($date[1]) && $year == $date[2]) {
       $event_show = true;
       $event_text[] = $text;
      }
     } 
     elseif (count($date) == 2) {
      if ($day == intval($date[0]) && $month == intval($date[1])) {
       $event_show = true;
       $event_text[] = $text;
      }
     } 
     elseif ($day == intval($date[0])) {
      $event_show = true;
      $event_text[] = $text;
     }    
    }
   }
   $l_event_show = false;
   $l_event_text = [];
   $color_count = count($background_colors)-1;
    foreach ($l_events as $date_text) {
         $date = explode('-', $date_text[0]);
         if ($day == intval($date[2]) && $month == intval($date[1]) && $year == intval($date[0]) ) {
               $l_event_show = true;
               $i = 0;
               $l_i =  -1 ;
               foreach ( $l_items as $item) {
                   $l_i =  -1 ;
                   if($item == $date_text[1]) {
                           $l_i = $i;
                           break;
                   }
                   $i = $i+1;
               }
               $l_ii = 0;
               if(intval($l_i)  < 0  ){
                 $l_i = $i;
                 $l_items[] = $date_text[1];
                 $l_items_name[] = $date_text[2];
                 $l_items_status[] = $date_text[6];
                 $l_items_id[] = $date_text[3];
                 $l_items_teacher[]  = $date_text[4];
                 $l_items_counterparty_list[]  = $date_text[5];
               }
               while($l_i >= $color_count){
                           $l_i =  $l_i - $color_count;
                           $l_ii = $l_ii+1;
               }
                 /*if( $l_i >= $color_count) {
                       $l_ii = intval($l_i/($color_count+1));
                       $l_i =  $l_i - $color_count*$l_ii;
                 }*/
               if($l_ii>3){
                       $l_i = $color_count;
                       $l_ii = 0;
                }
               //$l_ii = intval($color_count - $l_i);
               $l_event_text[] = [ $date_text[1], $date_text[2], $l_i, $date_text[3], $l_ii, $date_text[4], $date_text[5] ];
         }
   }
   if ($event_show) {
    $out.= '   <td class="calendar-day' . $class . ' event"><b>' . $day."</b>\n";
    if (!empty($event_text)) {
     $out.= '    <div class="calendar-popup">' . implode('<br>', $event_text) . '</div>'."\n";
    }
    $out.= '   </td>'."\n";
   } 
   else {
    $out.= '   <td class="calendar-day' . $class . '"><b>' . $day . '</b><br />';
    if ($l_event_show) {
        $i = 0;
        foreach ($l_event_text as $l_text) {
          $out.=   '<div  style="'. $background_p[$l_text[4]] .' background-color: ' . $background_colors[$l_text[2]] . ';  padding: 6px;  margin: 2px; font-size: smaller; " title="' . $l_text[1] . '" ><nobr><a href="/#/lstream_teacher/'. $l_text[3]. '/'. $current_date .'" class="nav-link" ><span style="font-weight: 600;">' . $l_text[0] . '</span><br /><span style="font-size: smaller;">' . $l_text[5] . '</span></a> </nobr> </div>';
        }
    }
    $out.=   "</td>\n";
   }
   if ($day_week == 6) {
    $out.= '  </tr>'."\n";
    if (($days_counter + 1) != $days_month) $out.= '  <tr valign="top">'."\n";
    $day_week = -1;
   }
   $day_week++; 
   $days_counter++;
  }
  while($day_week++ <7){
      $out .= '<td></td>';
  }
  $out .= "  </tr></table> </div>";
  return $out;
 }



  public static function get2Interval($start, $end,  $l_events, $events = []) { 
  global $l_items, $l_items_name, $l_items_status, $l_items_id, $l_items_teacher, $l_teachers, $l_items_counterparty_list,  $background_colors, $background_p;

  $color_count = count($background_colors)-1;
  $curr = explode('-', $start);
  $curr[1] = intval($curr[1]);
  $end = explode('-', $end);
  $end[1] = intval($end[1]);
  $out =  '<div class="row"><div class="col">';
  while(1) {
   $out .= self::getMonth($curr[0], $curr[1], $l_events, $events);
   if ($curr[1] == $end[1] && $curr[0] == $end[0])
          break;

   $curr[1]++;
   if ($curr[1] == 13) {
         $curr[1] = 1; 
         $curr[0]++;
   }
   $out .= '</div><div class="col">';
  }; 
  $out .= '</div></div>';

  $out .= '<div align="left"><p style="page-break-before: always;">Информация о потоках: </p><table class="table table-bordered" width="100%"><tr><td>Поток</td><td>Курс</td><td>Преподаватели</td><td width="16%"></td></tr> ';
  $i=0;
  foreach ( $l_items as $item) {
     $l_i = $i;
     if( $l_i >= $color_count) {
            $l_ii = intval($l_i/$color_count);
            $l_i =  $l_i - $color_count*$l_ii;
     }
     if($l_ii>3){
            $l_i = $color_count;
            $l_ii = 0;
     }

     $teacher = '';
     if (  key_exists( $l_items_id[$i], $l_teachers )  ){
           foreach ( $l_teachers[ $l_items_id[$i] ] as $item_t) {
               $teacher = $teacher . '<nobr>' .  $item_t[1] . '</nobr><br />';
           }
     }

     $out .= '<tr> <td  style="padding: 3px; text-align: center;" valign="middle"><nobr><div style="'. $background_p[$l_ii] .'padding-top: 2px; padding-bottom: 2px; padding-right: 5px;  padding-left: 5px; background-color: ' . $background_colors[$l_i] . ';"><a href="/#/lstream_list/0/0/'. $l_items_id[$i] .'" class="nav-link" title="Поток"> ' . $item . '</a></div></nobr></td><td style="text-align: left; padding-left: 5px;" >  '. $l_items_name[$i].  '</td><td>'. $teacher  .'</td><td align="center"><small>'. $l_items_status[$i]  .'</small></td></tr>';
//file_put_contents("lst2.txt", print_r(  $l_items_counterparty_list , true) );

     foreach ( $l_items_counterparty_list[$i] as $item2) {
         $out .= '<tr> <td colspan="2" style="padding: 3px; text-align: left;" valign="middle"><div style="padding-top: 2px; padding-bottom: 2px; padding-right: 5px;  padding-left: 20px; font-size: small; ">'. $item2['shortname'] . ' - заявка: '. $item2['order_name']  .'</div></td><td> </td><td></td></tr>';
     }

     $i = $i +1;
  }
  $out .= '</table>';

  return $out;
 }
 
 public static function getInterval($start, $end, $l_events, $events = []) { 
  $curr = explode('-', $start);
  $curr[1] = intval($curr[1]);
  $end = explode('-', $end);
  $end[1] = intval($end[1]);
  $begin = true;
  //$out =  file_get_contents("/analytics/calendar-inc.css");
  $out =  '';
  do {
   $out .= self::getMonth($curr[0], $curr[1], $l_events, $events);
   if ($curr[1] == $end[1] && $curr[0] == $end[0])
          $begin = false;
   $curr[1]++;
   if ($curr[1] == 13) {
         $curr[1] = 1; 
         $curr[0]++;
   }
  } while ($begin == true); 
  $out .= "\n".'</div>'."\n";
  return $out;
 }
}

?>