<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id']) && isset($_POST['announcement'])) {
    $class_id = $_POST['class_id'];
    $announcement = $_POST['announcement'];

    // Insère l'annonce dans la base de données
    $insert_query = "INSERT INTO announcements (class_id, announcement) VALUES ('$class_id', '$announcement')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: announcements.php?class_id=$class_id"); // Redirige vers la page des annonces
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'annonce : " . mysqli_error($conn);
    }
} else {
    echo "Requête invalide.";
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>
