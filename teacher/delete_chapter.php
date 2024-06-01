<?php
// Inclure la connexion à la base de données
include_once "connection.php";

// Afficher le contenu du tableau $_GET à des fins de débogage
echo var_dump($_GET);

// Vérifier si chapter_id et class_id sont fournis dans l'URL
if (isset($_GET['chapter_id']) && isset($_GET["class_id"])) {
    $chapter_id = $_GET['chapter_id'];
    $class_id = $_GET['class_id'];

    // Supprimer le chapitre de la base de données
    $delete_query = "DELETE FROM chapters WHERE id = '$chapter_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: preview_chapters.php?class_id=".$class_id); // Rediriger vers la page d'aperçu des chapitres pour la classe
    } else {
        echo "Erreur lors de la suppression du chapitre: " . mysqli_error($conn); // Afficher un message d'erreur si la suppression échoue
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
} else {
    echo "Requête invalide."; // Affichage si chapter_id ou class_id n'est pas fourni dans l'URL
}
?>
