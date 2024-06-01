<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
  // Redirige vers la page de connexion si non connecté
  header("Location: index.html");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Récupère les informations de l'étudiant depuis la base de données
$email = $_SESSION["email"];
$query = "SELECT * FROM students WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $email = $row['email'];

  // Vérifie s'il y a déjà une demande de suppression pour cet étudiant
  $query1 = "SELECT * FROM delete_requests WHERE user_id = {$row['id']} AND user_type = 'student'";
  $result1 = mysqli_query($conn, $query1);

  // Ferme la connexion à la base de données
  mysqli_close($conn);
} else {
  echo "Erreur lors de la récupération des informations de l'utilisateur.";
  exit(); // Arrête l'exécution ultérieure en cas d'erreur
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <style>
    /* Styles pour la carte */
    .card {
      max-width: 900px;
      margin: 40px auto;
      padding: 30px 80px;
      border: 2px solid #c4c4c4;
      border-radius: 20px;
      background: #d8dfed;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);

    }

    /* Styles pour le titre */
    .card h1 {
      font-size: 50px;
      font-weight: 600;
      text-transform: uppercase;
      color: #010d24;
    }

    /* Styles pour la partie du profil */
    .card .profile_card {
      display: flex;
      justify-content: end;
      flex-direction: column;
      align-items: center;
      text-align: start;
      gap: 20px;
      padding: 25px;
    }

    /* Styles pour les paragraphes dans la partie du profil */
    .profile_card p {
      margin-top: 10px;
      font-weight: 500;
      font-size: 23px;
      color: #0b0c21;
    }

    /* Styles pour les liens dans la partie du profil */
    .profile_card .links {
      padding: 30px 20px;
      display: flex;
      gap: 20px;
      flex-direction: column;
    }

    /* Styles pour les éléments forts dans les paragraphes */
    .profile_card strong {
      text-transform: capitalize;
      margin-right: 8px;
      color: #010d24;
    }

    /* Styles pour les liens */
    .profile_card a {
      text-decoration: none;
      margin: 8px auto;
      padding: 17px 40px;
      border-radius: 50px;
      cursor: pointer;
      border: 0;
      color: #162155;
      background-color: white;
      box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-size: 15px;
      transition: all 0.5s ease;
    }

    /* Styles au survol des liens */
    .profile_card a:hover {
      background-color: hsl(261deg 80% 48%);
      transform: scale(1.1);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    require_once "dashbord_head.html"; // Inclut le contenu du head
  ?>
  <title>Profil</title>
</head>
<body>
  <div class="container">
    <?php
      require_once "dashbord_body.html"; // Inclut le contenu du body
    ?>
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
      </div>
      <div class="card">
      <h1>Informations de Profil</h1>
      <div class="profile_card">
      <p><strong>Prénom :</strong> <?php echo $firstname; ?></p>
      <p><strong>Nom :</strong> <?php echo $lastname; ?></p>
      <p><strong>Email :</strong> <?php echo $email; ?></p>
      <div class="links">
    <a href="edit_profile.php">Modifier le Profil</a>
    <?php
      if ($result1 && mysqli_num_rows($result1) > 0) {
        echo "<p>Demande déjà envoyée</p>";
      } else {
        echo "<a href='delete_profile.php'>Supprimer le Profil</a>";
      }
    ?>
      </div>
      </div>
      </div>
    </div>
  </div>
</body>
<?php
  require_once "dashboard_script.html"; // Inclut le script JavaScript
?>
</html>
