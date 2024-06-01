<?php
session_start();

// Supprime toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Redirige vers la page de connexion après la déconnexion
header("Location: index.html");
exit();
?>
