<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
  header("Location: my_classes.php"); // Redirige vers la page de mes classes si non connecté
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'ID de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
  $class_id = $_GET['class_id'];

  // Récupère les détails de la classe depuis la base de données
  $class_query = "SELECT * FROM classes WHERE id = '$class_id' AND teacher_id = '" . $_SESSION['teacher_id'] . "'";
  $class_result = mysqli_query($conn, $class_query);

  if ($class_result && mysqli_num_rows($class_result) > 0) {
    $class_row = mysqli_fetch_assoc($class_result);
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php
      require_once "dashbord_head.html"; // Inclut les balises d'en-tête communes
      ?>
      <title>Détails du cours</title>
      <style>
        /* Styles CSS pour la mise en page */
        .card {
          display: flex;
          flex-direction: column;
          width: 800px;
          margin: 10px auto;
          padding: 20px;
          border: 2px solid #c4c4c4;
          border-radius: 20px;
          background: #FFB3B3;
          box-shadow: 9px 9px 30px #b0b0b0,
            -9px -9px 30px #ffffff;
          text-align: center;
        }

        .card h2 {
          font-size: 35px;
          text-align: start;
          font-weight: 600;
          margin: 30px auto;
          color: #480000;
          text-transform: uppercase;
          text-shadow: 0 2px white, 0 3px #777;
        }

        .card strong {
          font-size: 20px;
          font-weight: 600;
          color: #5B0000;
          margin: 10px auto;
          border-bottom: 5px solid #5B0000;
          border-top: 5px solid #5B0000;
          border-radius: 10px;
          padding: 10px 20px;
        }

        .card p {
          background: #fff;
          color: #5B0000;
          width: 90%;
          padding: 15px;
          font-size: 16px;
          font-weight: 500;
          margin: 10px auto;
          letter-spacing: 1px;
          cursor: pointer;
          border-radius: 12px;
          box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.5);
          transition: .4s ease;
        }

        .card p:hover {
          background-color: #B70000;
          transform: scale(1.02);
          box-shadow: #B70000 0px 7px 29px 0px;
          color: #fff;
        }

        .card .links {
          margin: 60px auto 10px auto;
          width: 70%;
          display: flex;
          flex-direction: column;
        }

        .card .links a {
          text-decoration: none;
          margin: 8px auto;
          padding: 17px 40px;
          border-radius: 50px;
          width: 100%;
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

        .card .links a:hover {
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
        require_once "dashbord_body.html"; // Inclut le contenu du tableau de bord
        ?>
        <div class="main">
          <div class="topbar">
            <div class="toggle">
              <ion-icon name="menu-outline"></ion-icon>
            </div>
          </div>
          <div class="card">
            <h2><?php echo $class_row['class_name']; ?></h2>
            <strong>Description:</strong>
            <p> <?php echo $class_row['description']; ?></p>
            <strong>Mots Clés:</strong>
            <p><?php echo $class_row['key_words']; ?></p>
            <strong>Les Prérequis:</strong>
            <p><?php echo $class_row['pre_requirements']; ?></p>

            <div class="links">
              <a href='my_students.php?class_id=<?php echo $class_row['id']; ?>'>Mes étudiants</a><br>
              <a href='teacher_upload_form.php?class_id=<?php echo $class_row['id']; ?>'>Importer un nouveau
                chapitre</a><br>
              <a href='preview_chapters.php?class_id=<?php echo $class_row['id']; ?>'>Chapitres</a><br>
              <a href='update_class_info.php?class_id=<?php echo $class_row['id']; ?>'>Modifier les informations du
                cours</a><br>
              <a href='announcements.php?class_id=<?php echo $class_row['id']; ?>'>Annonces</a><br>
              <a href='messages.php?class_id=<?php echo $class_row['id']; ?>'>Messages</a><br>
            </div>
          </div>
    </body>
    <?php
    require_once "dashboard_script.html"; // Inclut les scripts communs pour le tableau de bord
    ?>

    </html>
    <?php
  } else {
    echo "Cours introuvable ou vous n'êtes pas autorisé à afficher ce cours.";
  }
} else {
  echo "Requête invalide.";
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>
