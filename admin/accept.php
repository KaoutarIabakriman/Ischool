<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion administrateur si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'ID de la demande de suppression est fourni
if (isset($_GET['request_id']) && isset($_GET['user_type'])) {
  $request_id = $_GET['request_id'];
  $user_type = $_GET['user_type'] . "s"; // Ajoute un "s" pour obtenir le nom de la table correspondante

  // Supprime le compte de l'utilisateur en fonction de l'ID de demande de suppression
  $deleteQuery = "DELETE FROM $user_type WHERE id = (SELECT user_id FROM delete_requests WHERE id = '$request_id')";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
    // Supprime la demande de suppression de la table delete_requests
    $deleteRequestQuery = "DELETE FROM delete_requests WHERE id = '$request_id'";
    $deleteRequestResult = mysqli_query($conn, $deleteRequestQuery);

    if ($deleteRequestResult) {
      header("Location: delete_requests.php");
    } else {
      echo "Erreur lors de la suppression de la demande de suppression.";
    }
  } else {
    echo "Erreur lors de la suppression du compte.";
  }
} else {
  echo "Requête invalide.";
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>
