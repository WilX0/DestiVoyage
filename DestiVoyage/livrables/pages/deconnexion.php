<?php
session_start();
// Unset all session variables
$_SESSION = array();
// Destroy the session
session_destroy();

// redirectin apres la connexion
header("Location: ../index.php");
exit();
?>