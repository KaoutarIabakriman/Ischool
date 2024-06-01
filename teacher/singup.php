<?php
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Inclure la connexion à la base de données
  include_once "connection.php";

  // Récupérer les données du formulaire
  $first_name = $_POST["firstname"];
  $last_name = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Valider et filtrer les entrées (vous pouvez ajouter plus de validation ici)
  $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
  $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Hachage du mot de passe (pour une meilleure sécurité, utilisez la fonction password_hash())
  $hashed_password = md5($password); // Ceci est juste un exemple, pas recommandé pour la production

  // Insérer les données de l'utilisateur dans la base de données
  $query = "INSERT INTO teachers (firstName, lastName, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Rediriger vers la page de bienvenue après une inscription réussie
    header("Location: welcome.php");
    exit();
  } else {
    echo "Erreur: " . mysqli_error($conn);
  }

  // Fermer la connexion à la base de données
  mysqli_close($conn);
}
?>
