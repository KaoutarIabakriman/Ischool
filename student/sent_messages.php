<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
  // Redirige vers la page des cours si l'utilisateur n'est pas connecté
  header("Location: my_classes.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <style>
    /* Styles pour la carte contenant les informations de cours et les messages */
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

    /* Styles pour l'en-tête principale dans la carte */
    .card h2 {
      font-size: 35px;
      text-align: start;
      font-weight: 600;
      margin: 30px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    /* Style pour les messages indiquant l'état ou les erreurs */
    .message {
      font-size: 20px;
      font-weight: 500;
      padding: 25px;
      text-align: center;
      border-bottom: 2px solid #141D47;
      border-radius: 25px;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    /* Styles pour les titres des sous-sections dans la carte */
    .card h3 {
      margin: 0 auto 20px auto;
      font-size: 23px;
      font-weight: 600;
      text-transform: capitalize;
    }

    /* Styles pour les blocs contenant les messages envoyés */
    .card .message-sent {
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

    /* Styles pour la date dans les messages envoyés */
    .message-sent .date {
      background: #000036;
      color: #fff;
      padding: 13px;
      font-size: 15px;
      font-weight: 600;
      border-radius: 20px;
      margin-bottom: 20px;
      box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
    }

    /* Styles pour l'accentuation dans les messages envoyés */
    .message-sent strong {
      background: #000036;
      color: #fff;
      padding: 13px;
      font-size: 15px;
      font-weight: 600;
      border-radius: 20px;
      margin-bottom: 20px;
      box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  // Inclut l'en-tête HTML du tableau de bord
  require_once "dashbord_head.html";
  ?>
  <title>Messages Envoyés</title>
</head>

<body>
  <div class="container">
    <?php
    // Inclut le corps HTML du tableau de bord
    require_once "dashbord_body.html";
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

        // Vérifie si l'identifiant de cours est fourni dans l'URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
          $class_id = mysqli_real_escape_string($conn, $_GET['class_id']);

          // Récupère les détails du cours depuis la base de données
          $class_query = "SELECT * FROM classes WHERE id = '$class_id'";
          $class_result = mysqli_query($conn, $class_query);

          if ($class_result && mysqli_num_rows($class_result) > 0) {
            $class_row = mysqli_fetch_assoc($class_result);
            echo "<h2>" . htmlspecialchars($class_row['class_name']) . "</h2>";

            // Récupère et affiche les messages envoyés par l'étudiant
            $sent_messages_query = "SELECT * FROM messages WHERE class_id = '$class_id' AND sender_id = '" . $_SESSION['student_id'] . "'";
            $sent_messages_result = mysqli_query($conn, $sent_messages_query);

            if ($sent_messages_result && mysqli_num_rows($sent_messages_result) > 0) {
              echo "<h3>Messages envoyés :</h3>";
              while ($sent_message_row = mysqli_fetch_assoc($sent_messages_result)) {
                echo "<div class='message-sent'>";
                echo "<p class='date'><strong>Envoyé :</strong> " . $sent_message_row['sent_at'] . "</p>";
                echo "<strong>Message :</strong>";
                echo "<p class='text'>" . htmlspecialchars($sent_message_row['message_content']) . "</p>";
                echo "</div>";
              }
            } else {
              echo "<p class='message'>Aucun message n'a encore été envoyé.</p>";
            }
          } else {
            echo "<p class='message'>Cours non trouvé ou vous n'avez pas l'autorisation de voir ce cours.</p>";
          }
        } else {
          echo "<p class='message'>Demande invalide.</p>";
        }

        // Ferme la connexion à la base de données
        mysqli_close($conn);
        ?>

      </div>
    </div>
  </div>
</body>
<?php
// Inclut les scripts du tableau de bord
require_once "dashboard_script.html";
?>
</html>
