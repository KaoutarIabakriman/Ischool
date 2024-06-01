<?php
session_start();

// Efface toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Redirige vers la page de connexion après la déconnexion
header("Location: admin_login.php");
exit();
?>
