<?php
session_start();

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Inclure la connexion à la base de données
  include_once "connection.php";

  // Récupérer les données du formulaire
  $username_email = $_POST["email"];
  $password = $_POST["password"];

  // Valider et filtrer les entrées (vous pouvez ajouter plus de validation ici)
  $username_email = filter_var($username_email, FILTER_SANITIZE_STRING);

  // Vérifier si le nom d'utilisateur/email et le mot de passe correspondent dans la base de données
  $query = "SELECT * FROM teachers WHERE email='$username_email' AND password='" . md5($password) . "'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);

  if ($count == 1) {
    // Récupérer l'ID de l'enseignant depuis la base de données
    $row = mysqli_fetch_assoc($result);
    $teacher_id = $row['id'];

    // Définir les variables de session
    $_SESSION["email"] = $username_email;
    $_SESSION["teacher_id"] = $teacher_id; // Ajouter l'ID de l'enseignant à la session

    // Rediriger vers la page de bienvenue après une connexion réussie
    header("Location: welcome.php");
    exit();
  } else {
    echo "<div class='message'>
    <span>E-mail ou mot de passe invalide</span>
  </div>";
  }

  // Fermer la connexion à la base de données
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Styles CSS pour les messages d'erreur -->
  <style>
    .message {
      width: 500px;
      text-align: center;
      position: absolute;
      top: 20px;
      margin: auto;
      background: #E2A5A5;
      padding: 15px;
      border: 3px solid #A30A0A;
      border-radius: 10px;
    }

    .message span {
      font-size: 19px;
      font-weight: 500;
      color: #421818;
    }
  </style>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Inclure les icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Inclure le fichier CSS personnalisé -->
  <link rel="stylesheet" href="css/style.css" />
  <title>Error</title>
</head>

<body>
  <!-- Conteneur principal -->
  <div class="container" id="container">
    <!-- Formulaire d'inscription -->
    <div class="form-container sign-up">
      <form action="singup.php" method="post">
        <h1>Créer un compte</h1>
        <!-- Champ pour le prénom -->
        <input type="text" placeholder="Prénom" name="firstname" required />
        <!-- Champ pour le nom -->
        <input type="text" placeholder="Nom" name="lastname" required />
        <!-- Champ pour l'email -->
        <input type="email" placeholder="Email" name="email" required />
        <!-- Champ pour le mot de passe -->
        <input type="password" placeholder="Mot de passe" name="password" required />
        <!-- Bouton pour s'inscrire -->
        <button>S'inscrire</button>
      </form>
    </div>
    <!-- Formulaire de connexion -->
    <div class="form-container sign-in">
      <form action="login.php" method="post">
        <h1>Se connecter</h1>
        <!-- Champ pour l'email -->
        <input type="email" placeholder="Email" name="email" required />
        <!-- Champ pour le mot de passe -->
        <input type="password" placeholder="Mot de passe" name="password" required />
        <!-- Bouton pour se connecter -->
        <button>Se connecter</button>
      </form>
    </div>
    <!-- Conteneur de bascule -->
    <div class="toggle-container">
      <div class="toggle">
        <!-- Bascule pour changer entre les formulaires d'inscription et de connexion -->
        <div class="toggle-panel toggle-left">
          <h1>Bienvenue</h1>
          <p>Entrez vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site</p>
          <!-- Bouton caché pour basculer vers le formulaire de connexion -->
          <button class="hidden" id="login">Se connecter</button>
        </div>
        <!-- Panneau de bascule vers la droite (inscription) -->
        <div class="toggle-panel toggle-right">
          <h1>Bonjour, ami !</h1>
          <p>
            Inscrivez-vous avec vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site
          </p>
          <!-- Bouton caché pour basculer vers le formulaire d'inscription -->
          <button class="hidden" id="register">S'inscrire</button>
        </div>
      </div>
    </div>
  </div>
</body>
<!-- Inclure le fichier JavaScript principal -->
<script src="main.js"></script>

</html>
