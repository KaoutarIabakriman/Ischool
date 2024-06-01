<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["username"])) {
  // Redirect to the login page if not logged in
  header("Location: admin_login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html"; // Inclut les éléments de l'en-tête du tableau de bord
  ?>
  <title>Bienvenue Admin</title>
  <style>
    /* Styles CSS */
    .dashboard-links {
      display: flex;
      align-items: center;
      margin: 20px auto;
      gap: 20px;
    }

    .dashboard-links a {
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

    .dashboard-links a:hover {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    .logout {
      text-align: center;
      margin: 20px auto;
    }

    .logout button {
      align-items: center;
      background-image: linear-gradient(144deg, #AF40FF, #5B42F3 50%, #00DDEB);
      border: 0;
      border-radius: 8px;
      box-shadow: rgba(151, 65, 252, 0.2) 0 15px 30px -5px;
      box-sizing: border-box;
      color: #FFFFFF;
      display: flex;
      font-family: Phantomsans, sans-serif;
      font-size: 18px;
      justify-content: center;
      line-height: 1em;
      max-width: 100%;
      min-width: 140px;
      padding: 3px;
      text-decoration: none;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
      white-space: nowrap;
      cursor: pointer;
      transition: all .3s;
    }

    .logout button:active,
    .logout button:hover {
      outline: 0;
    }

    .logout button a {
      background-color: rgb(5, 6, 45);
      padding: 16px 24px;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      width: 100%;
      height: 100%;
      transition: 300ms;
    }

    .logout button:hover a {
      background: none;
    }

    .logout button:active {
      transform: scale(0.9);
    }

    .card {
      display: flex;
      flex-direction: column;
      width: 1000px;
      margin: 50px auto;
      padding: 20px 50px;
      border-radius: 20px;
      background: #A196FF;
      box-shadow: 4px 4px 13px #8678cf,
        -4px -4px 13px #c6b0ff;
    }

    .card h2 {
      font-size: 35px;
      text-align: start;
      font-weight: 600;
      margin: 35px auto 10px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card p {
      margin: 20px auto;
      font-size: 20px;
      font-weight: 500;
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php
    require_once "dashbord_body.html"; // Inclut le corps du tableau de bord
    ?>
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon> <!-- Icône pour le menu déroulant -->
        </div>
      </div>
      <div class="card">
        <h2>Bienvenue Admin</h2> <!-- Titre de bienvenue -->
        <p>Voici les demandes de suppression envoyés par:</p> <!-- Message de connexion -->

        <div class="dashboard-links">
          <a href="teacher_delete_requests.php">Enseignants</a> <!-- Lien pour afficher les demandes de suppression des enseignants -->
          <a href="student_delete_requests.php">Etudiants</a> <!-- Lien pour afficher les demandes de suppression des étudiants -->
        </div>

        <div class="logout">
          <button>
            <a href="welcome.php">Retour au tableau de bord</a> <!-- Bouton pour revenir au tableau de bord -->
          </button>
        </div>
      </div>
    </div>
  </div>
</body>
<?php
require_once "dashboard_script.html"; // Inclut les scripts du tableau de bord
?>

</html>
