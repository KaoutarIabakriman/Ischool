<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que professeur
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si le formulaire est soumis et si les données nécessaires sont définies
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['visibility']) && isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];
    $visibility = $_POST['visibility']; // Tableau contenant les identifiants des chapitres et leur statut de visibilité

    // Parcourir le tableau de visibilité et mettre à jour la visibilité de chaque chapitre
    foreach ($visibility as $chapter_id => $status) {
        $update_query = "UPDATE chapters SET hidden = '$status' WHERE id = '$chapter_id' AND class_id = '$class_id'";
        mysqli_query($conn, $update_query);
    }

    // Rediriger vers la page de prévisualisation des chapitres après la mise à jour
    header("Location: preview_chapters.php?class_id=".$class_id);
    exit();
} else {
    echo "Requête invalide.";
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
