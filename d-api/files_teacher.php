<?php

$dirPath = "/files/documents1/";

$file = 't'+$_GET['teacher_id']

$files = scandir($dirPath);
foreach ($files as $file) {
    $filePath = $dirPath  . $file;
    if (is_file($filePath)) {
        echo '<p><a href="'.$filePath.'" target="_blank">'. $file . '</a></p>';
    }
}
?>