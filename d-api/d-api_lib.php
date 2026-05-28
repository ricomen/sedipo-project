<?php
// TRANSLITERATION
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

// RUSSIAN MOUNTH
function getRussianMonth($date, $case = 'genitive') {
    $months = [
        'genitive' => [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ],
        'nominative' => [
            1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 
            5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август', 
            9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь'
        ]
    ];

    try {
        $dateTime = new DateTime($date);
        $monthNumber = (int)$dateTime->format('n');
        $case = in_array($case, ['genitive', 'nominative']) ? $case : 'genitive';
        return $months[$case][$monthNumber] ?? '';
    } catch (Exception $e) {
        return '';
    }
}


function formatRussianDate($date, $mod='', $case = 'genitive') {
    $day = date('j', strtotime($date));
    $month = getRussianMonth($date, $case);
    $year = date('Y', strtotime($date));
    if ($mod=='d')
    return $day;
    elseif ($mod=='m')
    return $month;
    elseif ($mod=='y')
    return $year;
    else
    return "$day $month $year";
}

//FAMILIYA IMYA OTCHESTVO --> FAMILIYA.I.O.
function formatFio($fio, $backward=false) {
    $fiotemp = explode(" ",$fio);
    $firstname = $fiotemp[0];
    $lastname = mb_substr($fiotemp[1], 0, 1);
    $middlename = mb_substr($fiotemp[2], 0, 1);
    if($middlename!='')
    {
    $return_value = match ($backward) {
        false => "$firstname $lastname.$middlename.",
        true => "$lastname.$middlename. $firstname",
    };
    return $return_value;
    }
 
    else
       return $fio;
}




function createSchedule(array $dates, array $topics, $HoursPerDay) {
    $schedule = [];
    $dateIndex = 0;
    
    // Проверяем, что у нас есть даты и темы
    if (empty($dates) || empty($topics)) {
        return $schedule;
    }
    
    $currentDate = $dates[$dateIndex];
    $remainingHoursInDay = $HoursPerDay;
    $topicIndex = 0;
    
    while ($topicIndex < count($topics)) {
        $topic = $topics[$topicIndex];
        $topicName = $topic['name'];
        $topicTopic = $topic['topic'];
        $topicHours = $topic['hours'];
        
        // Определяем сколько часов мы можем выделить в текущий день
        $hoursToAllocate = min($topicHours, $remainingHoursInDay);
        
        // Добавляем запись в расписание
        if ($hoursToAllocate > 0) {
            $schedule[] = [
                'date' => $currentDate,
                'name' => $topicName,
                'topic' => $topicTopic,
                'hours' => $hoursToAllocate
            ];
            
            // Уменьшаем оставшиеся часы в теме и в дне
            $topics[$topicIndex]['hours'] -= $hoursToAllocate;
            $remainingHoursInDay -= $hoursToAllocate;
        }
        
        // Если тема полностью распределена, переходим к следующей
        if ($topics[$topicIndex]['hours'] == 0) {
            $topicIndex++;
        }
        
        // Если день заполнен, переходим к следующему дню
        if ($remainingHoursInDay == 0) {
            $dateIndex++;
            if ($dateIndex < count($dates)) {
                $currentDate = $dates[$dateIndex];
                $remainingHoursInDay = $HoursPerDay;
            } else {
                // Если даты закончились, но темы остались - выбрасываем исключение
                if ($topicIndex < count($topics)) {
                    //throw new Exception("Не хватает дат для распределения всех тем");
                    $schedule[] = [
                        'date' => 'Не хватает дат для распределения всех тем'.$HoursPerDay.'!!',
                        'name' => 'Не хватает дат для распределения всех тем'.$HoursPerDay.'!!',
                        'topic' => 'Не хватает дат для распределения всех тем'.$HoursPerDay.'!!',
                        'hours' => 'Не хватает дат для распределения всех тем'.$HoursPerDay.'!!'
                    ];
                    return $schedule;
                }
                break;
            }
        }
    }
    
    return $schedule;
}



?>