<?php
/**
 * @copyright 2022
 */



function api_journal( $api_module,  $api_function, $api_arg, $login  ) {
    global $api_arg, $user_id_session, $session_role,  $dbh;

/*
    if($api_module != '' &&  $login!= '' ) {
        $stmt = $dbh->prepare('INSERT INTO `a_journal`(`api_module`, `api_function`, `api_arg`, `login`, `remote_addr` ) VALUES( ?, ?, ?, ? , ? ) ');
        $stmt->execute([$api_module,  $api_function, mb_substr(json_encode($api_arg, JSON_UNESCAPED_UNICODE), 0, 200),  $login, $_SERVER['REMOTE_ADDR'] ]);
        if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
              return 0;
        }
        return 1;
    }
*/

}



function getDirectorySize(string $folderPath) {
  $files = scandir($folderPath);
  unset($files[0], $files[1]);
  $size = 0;
  foreach ($files as $file) {
    if (file_exists($folderPath . '/' . $file)) {
      $size += filesize($folderPath . '/' . $file);
      if (is_dir($folderPath . '/' . $file)) {
        $size += getDirectorySize($folderPath . '/' . $file);
      }
    }

  }
  return $size;
}


?>