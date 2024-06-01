<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: my_classes.php"); // Rediriger vers la page appropriée si non connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'], $_POST['reply_message'], $_POST['message_id'])) {
    // Récupérer les données du formulaire
    $class_id = $_POST['class_id'];
    $reply_message = $_POST['reply_message'];
    $message_id = $_POST['message_id'];
    $teacher_id = $_SESSION['teacher_id'];

    // Insérer la réponse dans la table des réponses
    $insert_query = "INSERT INTO replies (message_id, teacher_id, reply_message) VALUES ('$message_id', '$teacher_id', '$reply_message')";
    if (mysqli_query($conn, $insert_query)) {
        // Rediriger vers la page des messages après l'insertion de la réponse
        header("Location: messages.php?class_id=".$class_id);
    } else {
        // Afficher une erreur en cas d'échec de l'insertion
        echo "Erreur lors de l'envoi de la réponse : " . mysqli_error($conn);
    }
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
