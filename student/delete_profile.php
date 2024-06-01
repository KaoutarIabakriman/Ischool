<?php
session_start();
include_once "connection.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: index.html"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Obtient l'e-mail de l'étudiant
$email = $_SESSION['email'];

// Insère la demande de suppression dans la table delete_requests
$insertQuery = "INSERT INTO delete_requests (user_type, user_id) VALUES ('student', (SELECT id FROM students WHERE email = '$email'))";
$insertResult = mysqli_query($conn, $insertQuery);

if ($insertResult) {
    header("Location: index.html");
} else {
    echo "Erreur lors de l'envoi de la demande de suppression."; // Message d'erreur en cas d'échec de l'envoi de la demande de suppression
}

mysqli_close($conn);
?>
