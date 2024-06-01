<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que professeur
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_upload_form.html"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si le formulaire est soumis et que le fichier est téléchargé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["chapter_file"]) && isset($_POST["chapter_name"]) && isset($_POST["class_id"])) {
    $teacher_id = $_SESSION['teacher_id'];
    $class_id = $_POST['class_id'];
    $chapter_name = $_POST['chapter_name'];
    $file_name = $_FILES["chapter_file"]["name"];
    $file_temp = $_FILES["chapter_file"]["tmp_name"];

    // Vérifier si le fichier est un PDF
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    if (strtolower($file_ext) != "pdf") {
        echo "Erreur : seuls les fichiers PDF sont autorisés.";
        exit();
    }

    // Télécharger le fichier
    $upload_dir = "uploads/";
    $file_path = $upload_dir . $file_name;
    if (move_uploaded_file($file_temp, $file_path)) {
        // Insérer les informations du chapitre dans la base de données
        $insert_query = "INSERT INTO chapters (class_id, chapter_name, file_path) VALUES ('$class_id', '$chapter_name', '$file_path')";
        if (mysqli_query($conn, $insert_query)) {
            header("Location: preview_chapters.php?class_id=".$class_id);
            exit();
        } else {
            echo "Erreur lors de l'importation du chapitre : " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de l'importation.";
    }
} else {
    echo "Requête invalide.";
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
