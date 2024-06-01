<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
  // Redirige vers la page de connexion si non connecté
  header("Location: index.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    require_once "dashbord_head.html"; // Inclut le contenu du head
  ?>
  <style>
    /* Styles pour l'en-tête */
    .heading {
      width: 70%;
      margin: 200px auto;
    }

    /* Styles pour le titre */
    .heading h1 {
      text-align: center;
      font-size: 70px;
      font-weight: 700;
      color: #222;
      letter-spacing: 1px;
      text-transform: uppercase;
      display: grid;
      grid-template-columns: 1fr max-content 1fr;
      grid-template-rows: 27px 0;
      grid-gap: 20px;
      align-items: center;
    }

    /* Styles pour les lignes horizontales */
    .heading h1:after,
    .heading h1:before {
      content: " ";
      display: block;
      border-bottom: 3px solid rgb(33, 33, 212);
      border-top: 3px solid rgb(33, 33, 212);
      height: 20px;
      background-color: #f8f8f8;
    }
  </style>
  <title>Bienvenue</title>
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
      <div class="heading">
        <h1>Bienvenue</h1>
      </div>
    </div>
  </div>
</body>
<?php
  require_once "dashboard_script.html"; // Inclut le script JavaScript
?>
</html>
