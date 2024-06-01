<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion de l'administrateur si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'identifiant de la demande de suppression est fourni
if (isset($_GET['request_id'])) {
  $request_id = $_GET['request_id'];

  // Supprime la demande de suppression de la table delete_requests
  $deleteQuery = "DELETE FROM delete_requests WHERE id = '$request_id'";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
        header("Location: delete_requests.php");
      } else {
    echo "Erreur lors de la suppression de la demande de suppression.";
  }
} else {
  echo "Requête invalide.";
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>
