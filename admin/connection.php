<?php
// Paramètres de connexion à la base de données
$server_name = "localhost"; // Le serveur de la base de données, généralement localhost sur un environnement de développement
$username = "root";         // Le nom d'utilisateur pour se connecter à la base de données
$password = "";             // Le mot de passe associé à l'utilisateur de la base de données
$database = "project";      // Le nom de la base de données à laquelle se connecter
$port = 3308;               // Le numéro de port MySQL

// Tentative de connexion à la base de données MySQL avec le numéro de port spécifié
$conn = mysqli_connect($server_name, $username, $password, $database, $port);

// Vérification de la connexion
if (!$conn) {
    // La connexion a échoué, affiche un message d'erreur et arrête le script
    die("Connection failed: " . mysqli_connect_error());
}
?>
