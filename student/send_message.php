<?php
// Démarrage de la session
session_start();

// Vérification de l'authentification de l'utilisateur en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: my_classes.php");
    exit(); // Arrêt du script
}

// Inclusion du fichier de connexion à la base de données
include_once "connection.php";

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id']) && isset($_POST['message'])) {
    // Récupération des données du formulaire
    $class_id = $_POST['class_id'];
    $message = $_POST['message'];
    $student_id = $_SESSION['student_id'];

    // Insertion du message dans la base de données
    $insert_query = "INSERT INTO messages (sender_id, class_id, message_content) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query); // Préparation de la requête SQL
    if ($stmt) {
        // Liaison des valeurs aux paramètres de la requête
        mysqli_stmt_bind_param($stmt, "iis", $_SESSION['student_id'], $class_id, $message);
        // Exécution de la requête
        if (mysqli_stmt_execute($stmt)) {
            // Redirection vers la page des messages pour la classe spécifiée
            header("Location: messages.php?class_id=" . $class_id);
        } else {
            // Affichage d'un message d'erreur en cas d'échec de l'exécution de la requête
            echo "Erreur lors de l'envoi du message : " . mysqli_stmt_error($stmt);
        }
        // Fermeture de la requête préparée
        mysqli_stmt_close($stmt);
    } else {
        // Affichage d'un message d'erreur en cas d'échec de la préparation de la requête
        echo "Erreur de préparation de la requête : " . mysqli_error($conn);
    }
} else {
    // Affichage d'un message d'erreur en cas de soumission de formulaire invalide
    echo "Requête invalide.";
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
