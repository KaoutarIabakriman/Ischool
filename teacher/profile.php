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

// Récupère les informations de l'enseignant depuis la base de données
$email = $_SESSION["email"];
$query = "SELECT * FROM teachers WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $firstname = $row['firstName'];
  $lastname = $row['lastName'];
  $email = $row['email'];

  // Vérifie s'il existe déjà une demande de suppression pour cet enseignant
  $query1 = "SELECT * FROM delete_requests WHERE user_id = {$row['id']} AND user_type = 'teacher'";
  $result1 = mysqli_query($conn, $query1);

  // Ferme la connexion à la base de données
  mysqli_close($conn);
} else {
  echo "Erreur lors de la récupération des informations utilisateur.";
  exit(); // Arrête l'exécution ultérieure en cas d'erreur
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html";
  ?>
  <title>Profil</title>
  <style>
    .card {
      max-width: 900px;
      margin: 40px auto;
      padding: 30px 80px;
      border: 2px solid #c4c4c4;
      border-radius: 20px;
      background: #FFB3B3;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);

    }

    .card h2 {
      font-size: 50px;
      font-weight: 600;
      text-transform: uppercase;
      color: #010d24;
    }

    .card .profile_card {
      display: flex;
      justify-content: end;
      flex-direction: column;
      align-items: center;
      text-align: start;
      gap: 20px;
      padding: 25px;
    }

    .profile_card p {
      margin-top: 10px;
      font-weight: 500;
      font-size: 23px;
      color: #0b0c21;
    }

    .profile_card .links {
      padding: 30px 20px;
      display: flex;
      gap: 20px;
      flex-direction: column;
    }

    .profile_card strong {
      text-transform: capitalize;
      margin-right: 8px;
      color: #010d24;
    }

    .profile_card a {
      text-decoration: none;
      margin: 8px auto;
      padding: 17px 40px;
      border-radius: 50px;
      cursor: pointer;
      border: 0;
      color: #FF1919;
      background-color: white;
      box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-size: 15px;
      transition: all 0.5s ease;
    }


    .profile_card a:hover {
      background-color: #B70000;
      transform: scale(1.1);
      color: hsl(0, 0%, 100%);
      box-shadow: #FF1919 0px 7px 29px 0px;
    }

  </style>
</head>

<body>
  <div class="container">
    <?php
    require_once "dashbord_body.html";
    ?>
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
      </div>
      <div class="card">
        <h2>Informations Du Profil</h2>
        <div class="profile_card">
          <p><strong>Prénom:</strong> <?php echo $firstname; ?></p>
          <p><strong>Nom:</strong> <?php echo $lastname; ?></p>
          <p><strong>Email:</strong> <?php echo $email; ?></p>
          <div class="links">
            <a href="edit_profile.php">Modifier le Profil</a>
            <?php
            if ($result1 && mysqli_num_rows($result1) > 0) {
              echo "<p>Demande déjà envoyée</p>";
            } else {
              echo "<a href='delete_profile.php'>Supprimer le profil</a>";
            }
            ?>
          </div>
        </div>


      </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
