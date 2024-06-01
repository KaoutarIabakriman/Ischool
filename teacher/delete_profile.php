<?php
session_start();
include_once "connection.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: index.html"); // Redirige vers la page de connexion si non connecté
    exit();
}

// Récupère l'email de l'étudiant
$email = $_SESSION['email'];

// Insère la demande de suppression dans la table delete_requests
$insertQuery = "INSERT INTO delete_requests (user_type, user_id) VALUES ('teacher', (SELECT id FROM teachers WHERE email = '$email'))";
$insertResult = mysqli_query($conn, $insertQuery);

if ($insertResult) {
    header("Location: index.html"); // Redirige vers la page d'accueil après avoir envoyé la demande de suppression
} else {
    echo "Erreur lors de l'envoi de la demande de suppression."; // Affiche un message d'erreur si l'insertion échoue
}

mysqli_close($conn);
?>
