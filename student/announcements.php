<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
  header("Location: welcome.php"); // Redirige vers la page d'accueil si non connecté
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <style>
    .card {
      display: flex;
      flex-direction: column;
      width: 800px;
      margin: 10px auto;
      padding: 20px;
      border: 2px solid #c4c4c4;
      border-radius: 20px;
      background: #D5D9EE;
      box-shadow: 9px 9px 30px #b0b0b0,
        -9px -9px 30px #ffffff;
    }

    .card h2 {
      font-size: 35px;
      text-align: start;
      font-weight: 600;
      margin: 30px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .message {
      font-size: 20px;
      font-weight: 500;
      padding: 25px;
      text-align: center;
      border-bottom: 2px solid #141D47;
      border-radius: 25px;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .card .announcement {
      display: flex;
      width: 90%;
      flex-direction: column;
      align-items: start;
      background: #fff;
      padding: 20px;
      margin: 10px auto;
      border-radius: 20px;
      box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.5);
    }

    .announcement .date {
      background: #000036;
      color: #fff;
      padding: 13px;
      font-size: 18px;
      font-weight: 600;
      border-radius: 20px;
      margin-bottom: 20px;
      box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
    }

    .announcement .date strong {
      margin-right: 20px;
    }

    .announcement .date span {
      font-weight: 400;
      font-size: 15px;
    }

    .announcement .text {
      width: 93%;
      margin: auto 20px;
      font-size: 18px;
      font-weight: 500;
    }

  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html";// Inclut le code du head
  ?>
  <title>Annonce</title>
</head>

<body>
  <div class="container">
    <?php
    require_once "dashbord_body.html";// Inclut le code du body
    ?>
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
      </div>
      <div class="card">
        <?php
        // Inclut la connexion à la base de données
        include_once "connection.php";

        // Vérifie si l'ID du cours est fourni dans l'URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
          $class_id = $_GET['class_id'];

          // Récupère les détails de la classe depuis la base de données
          $class_query = "SELECT * FROM classes WHERE id = '$class_id'";
          $class_result = mysqli_query($conn, $class_query);

          if ($class_result && mysqli_num_rows($class_result) > 0) {
            $class_row = mysqli_fetch_assoc($class_result);
            echo "<body>";
            echo "<h2>Annonces de " . $class_row['class_name'] . "</h2>";

            // Récupère les annonces pour l'ID du cours spécifié
            $announcements_query = "SELECT * FROM announcements WHERE class_id = '$class_id'";
            $announcements_result = mysqli_query($conn, $announcements_query);

            //afficher les annonces
            if ($announcements_result && mysqli_num_rows($announcements_result) > 0) {
              while ($announcement_row = mysqli_fetch_assoc($announcements_result)) {
                echo "<div class='announcement'>";
                echo "<p class='date'><strong>Publié :</strong> <span class='posted'>" . $announcement_row['created_at'] . "</span></p>";
                echo "<p class='text'>" . $announcement_row['announcement'] . "</p>";
                echo "</div>";
              }
            } else {
              echo "<p class='message'>Aucune annonce pour le moment.</p>";
            }

            echo "</body>";
            echo "</html>";
          } else {
            echo "Cours non trouvé ou vous n'avez pas la permission de voir les annonces pour ce cours.";
          }
        } else {
          echo "Requête invalide.";
        }

        // Ferme la connexion à la base de données
        mysqli_close($conn);
        ?>

      </div>
    </div>
  </div>
</body>
<?php
require_once "dashboard_script.html";// Inclut le script JavaScript
?>

</html>