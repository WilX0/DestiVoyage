<?php
// Incluez la configuration de votre base de données
try{
    $host = 'mysql-destivoyage.alwaysdata.net';
    $dbname = 'destivoyage_projetdwa';
    $username = '333374_kenzi';
    $password = 'projetdwa';

    $string = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($string,$username,$password);
    $query = $con->prepare('select login from user');
    $query->execute();

    $logins = $query->fetchAll(PDO::FETCH_ASSOC);
    // echo $result;    

    header("Content-Type: application/json");
    echo json_encode($logins);
    
}catch(PDOException $e){
    echo ''. $e->getMessage();
}

?>
