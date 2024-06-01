<?php
session_start();

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Inclusion de la connexion à la base de données
  include_once "connection.php";

  // Récupère les données du formulaire
  $username_email = $_POST["email"];
  $password = $_POST["password"];

  // Valide et assainit les entrées (vous pouvez ajouter plus de validation ici)
  $username_email = filter_var($username_email, FILTER_SANITIZE_STRING);

  // Vérifie si le nom d'utilisateur/email et le mot de passe correspondent dans la base de données
  $query = "SELECT * FROM students WHERE email='$username_email' AND password='".md5($password)."'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);

  if ($count == 1) {
    $row = mysqli_fetch_array($result);
    $student_id = $row['id'];
    // Définit les variables de session
    $_SESSION["email"] = $username_email;
    $_SESSION["student_id"] = $student_id;

    // Redirige vers la page de bienvenue après une connexion réussie
    header("Location: welcome.php");
    exit();
  } else {
    echo "<div class='message'>
      <span>Nom d'utilisateur ou mot de passe incorrect</span>
    </div>";
  }

  // Ferme la connexion à la base de données
  mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Erreur</title>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="singup.php" method="post">
                <h1>Créer un compte</h1>
                <input type="text" placeholder="Prénom" name="firstname" required/>
                <input type="text" placeholder="Nom" name="lastname" required/>
                <input type="email" placeholder="Email" name="email" required />
                <input type="password" placeholder="Mot de passe" name="password" required/>
                <button>S'inscrire</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1>Se connecter</h1>
                <input type="email" placeholder="Email" name="email" required/>
                <input type="password" placeholder="Mot de passe" name="password" required/>
                <button>Se connecter</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bienvenue</h1>
                    <p>Entrez vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour, ami !</h1>
                    <p>
                        Inscrivez-vous avec vos coordonnées personnelles pour utiliser toutes les fonctionnalités du site
                    </p>
                    <button class="hidden" id="register">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="main.js"></script>

</html>
