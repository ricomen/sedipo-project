<?php
/**
 * @copyright 2025
 */

require_once '../config.php';
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


$group_id = intval($_POST["lstream_id"]);


if($group_id>0) {
    $stmt = $dbh->prepare('update  `a_lstream` SET `moodle_cohort_id`=?, `moodle_group_id`=?,  `moodle_enrol_id`=?  WHERE `lstream_id`=?');
    $stmt->execute([$_POST["moodle_cohort_id"], $_POST["moodle_group_id"], $_POST["moodle_enrol_id"],  $group_id]);
}
?>

