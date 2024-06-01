<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
  header("Location: my_classes.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <style>
    /* Styles CSS */
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

    .card .inbox {
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

    .card .inbox .date {
      background: #000036;
      color: #fff;
      padding: 13px;
      font-size: 15px;
      font-weight: 600;
      border-radius: 20px;
      margin-bottom: 10px;
      margin-left: 20px;
      box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
    }

    .inbox .date span {
      margin-right: 20px;
    }

    .inbox strong {
      font-size: 20px;
      font-weight: 600;
      color: #010d24;
      text-transform: capitalize;
      margin: 10px 0;
    }

    .inbox p {
      font-size: 16px;
      font-weight: 500;
      letter-spacing: 1px;
      margin: 10px 30px;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html"; // Inclure l'en-tête de la page
  ?>
  <title>Profil</title>
</head>

<body>
  <div class="container">
    <?php
    require_once "dashbord_body.html"; // Inclure le corps de la page
    ?>
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon> <!-- Icône pour basculer le menu -->
        </div>
      </div>
      <div class="card">
        <?php
        include_once "connection.php"; // Inclure la connexion à la base de données

        // Vérifier si l'ID de la classe est fourni dans l'URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
          $class_id = $_GET['class_id'];

          // Récupérer les messages et les réponses de la base de données
          $messages_query = "SELECT messages.*, replies.reply_message, replies.replied_at
                                FROM messages
                                INNER JOIN replies ON messages.m_id = replies.message_id
                                WHERE messages.class_id = '$class_id' AND messages.sender_id = '{$_SESSION['student_id']}'";
          $messages_result = mysqli_query($conn, $messages_query);

          if ($messages_result && mysqli_num_rows($messages_result) > 0) {
            // Afficher les messages et les réponses
            while ($row = mysqli_fetch_assoc($messages_result)) {
              echo "<div class='inbox'>";
              echo "<strong>Votre message :</strong>";
              echo "<p>" . $row['message_content'] . "</p>";
              echo "<strong class='date'><span>Envoyé à :</span>" . $row['sent_at'] . "</strong>";
              echo "<strong>Réponse du professeur :</strong>";
              echo "<p>" . $row['reply_message'] . "</p>";
              echo "<strong class='date'><span>Répondu à</span> : " . $row['replied_at'] . "</strong>";
              echo "</div>";
            }
          } else {
            echo "<p class='message'>Aucun message ou réponse trouvé.</p>";
          }
        } else {
          echo "Requête invalide.";
        }

        // Fermer la connexion à la base de données
        mysqli_close($conn);
        ?>
      </div>
    </div>
  </div>
</body>
<?php
require_once "dashboard_script.html"; // Inclure les scripts de la page
?>

</html>
