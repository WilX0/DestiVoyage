<?php
require "fonction.php";
$host = 'mysql-destivoyage.alwaysdata.net';
$dbname = 'destivoyage_projetdwa';
$username = '333374_kenzi';
$password = 'projetdwa';

$string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$con = new PDO($string,$username,$password);
// $query = $con->prepare('select login from user');
// $query->execute();

$currentDateTime = new DateTime();


$dateTime24HoursAgo = $currentDateTime->sub(new DateInterval('PT12H'));


$date24HoursAgo = $dateTime24HoursAgo->format('Y-m-d H:i:s');


$query = "DELETE FROM user WHERE email_verified = 0 AND date_inscription < :date24HoursAgo";
database_run($query, array('date24HoursAgo' => $date24HoursAgo));
?>
