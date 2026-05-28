<?php
      #########################################################
      # Функция склоняет фамилию, имя и отчество в  дательный падеж
      # Поддержка http://www.softtime.ru/forum/
      #
      #   Вводимые параметры:
      #   $FirstName      - фамилия
      #   $SecondName     - имя
      #   $Patronymic     - отчество
      #########################################################

      function DativeCase($FirstName, $SecondName, $Patronymic)
      {
        $FirstName = trim($FirstName);
        $SecondName = trim($SecondName);
        $Patronymic = trim($Patronymic);
        
        if (!empty($FirstName) && !empty($SecondName) && !empty($Patronymic)) {
          # Получаем пол человека:
          if (mb_substr($Patronymic, -1) == 'ч') {
            # Склонение фамилии мужчины:
            switch (mb_substr($FirstName, -2))   {
              case 'ха':
                  $FirstName = mb_substr($FirstName, 0, -2).'хи';
                  break;
                  
              default:
                  switch (mb_substr($FirstName, -1))  {
                      case 'е': case 'о': case 'и': case 'я': case 'а':
                          break;
                          
                      case 'й':
                          $FirstName = mb_substr($FirstName, 0, -2).'ому';
                          break;
                          
                      case 'ь':
                          $FirstName = mb_substr($FirstName, 0, -1).'ю';
                          break;
                          
                      default:
                          $FirstName = $FirstName.'у';
                          break;
                  }
                  break;
            }

            # Склонение мужского имени:
            switch (mb_substr($SecondName, -1))  {
               case 'л':
                   $SecondName = mb_substr($SecondName, 0, -2).'лу';
                   break;
                   
               case 'а': case 'я':
                   If (mb_substr($SecondName, -2, 1) == 'и') {
                       $SecondName = mb_substr($SecondName, 0, -1).'и';
                   }
                   else {
                       $SecondName = mb_substr($SecondName, 0, -1).'е';
                   }
                   break;
                           
               case 'й': case 'ь':
                   $SecondName = mb_substr($SecondName, 0, -1).'ю';
                   break;
                            
               default:
                   $SecondName = $SecondName.'у';
                   break;
            }
            
            # Склонение отчества
            $Patronymic = $Patronymic.'у';
            
        }  
        else  {
            # Склоенение женской фамилии
            switch (mb_substr($FirstName, -1))  {
                case 'о': case 'и': case 'б': case 'в': case 'г': 
                case 'д': case 'ж': case 'з': case 'к': case 'л': 
                case 'м': case 'н': case 'п': case 'р': case 'с': 
                case 'т': case 'ф': case 'х': case 'ц': case 'ч': 
                case 'ш': case 'щ': case 'ь':
                    break;
                    
                case 'я':
                    $FirstName = mb_substr($FirstName, 0, -2).'ой';
                    
                default:
                    $FirstName = mb_substr($FirstName, 0, -1).'ой';
                    break;
            }
            
            # Склонение женского имени:
            switch (mb_substr($SecondName, -1))  {
               case 'а': case 'я':
                   If (mb_substr($SecondName, -2, 1) == 'и') {
                       $SecondName = mb_substr($SecondName, 0, -1).'и';
                   }
                   else  {
                       $SecondName = mb_substr($SecondName, 0, -1).'е';
                   }
                   break;
                           
               case 'ь':
                   $SecondName = mb_substr($SecondName, 0, -1).'и';
                   break;
            }
            
            # Склонение женского отчества
            $Patronymic = mb_substr($Patronymic, 0, -1).'е';

          }
      
          return [$FirstName, $SecondName, $Patronymic];
        }
      }
     
?>