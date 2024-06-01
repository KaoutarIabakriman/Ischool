<?php
// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclut la connexion à la base de données
    include_once "connection.php";

    // Récupère les données du formulaire
    $first_name = $_POST["firstname"];
    $last_name = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Valide et filtre les entrées
    $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Hash le mot de passe
    $hashed_password = md5($password);
    // Insère les données utilisateur dans la base de données
    $query = "INSERT INTO students (firstName, lastName, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirige vers la page d'accueil après l'inscription réussie
        header("Location: index.html");
        exit();
    } else {
        // Gère les erreurs
        echo "Erreur : Impossible de s'inscrire. Veuillez réessayer ultérieurement.";
        // Journalise les informations d'erreur détaillées (facultatif)
        error_log("Erreur de base de données : " . mysqli_error($conn));
    }

    // Ferme la connexion à la base de données
    mysqli_close($conn);
}
?>
