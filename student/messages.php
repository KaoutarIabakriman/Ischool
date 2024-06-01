<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
  header("Location: my_classes.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
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

    .card h3 {
      font-size: 22px;
      font-weight: 600;
      text-transform: capitalize;
      margin: 0px auto 30px auto;
    }

    .card form {
      display: flex;
      align-items: center;
      flex-direction: column;
      width: 50%;
      margin: auto;
    }

    .card form textarea {
      width: 100%;
      min-height: 150px;
      resize: none;
      padding: 15px;
      border-radius: 12px;
      outline: none;
      border: 2px solid #E1E1E1;
      background: #E1E1E1;
      color: #000036;
      font-size: 16px;
      text-align: center;
      box-shadow: inset 2px 2px 8px rgba(0, 0, 0, 0.3);
      transition: .4s ease;
    }

    .card form textarea:focus {
      transform: scale(1.05);
      background: #fff;
      border: 2px solid #010d24;
    }

    .card form textarea::placeholder {
      font-size: 17px;
      font-weight: 500;
      color: #000036;
    }

    .card form .submit {
      padding: 17px 45px;
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
      margin: 0px auto 10px auto;
    }

    .card form .submit:hover {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    .card .links {
      display: flex;
      width: 80%;
      margin: 20px auto;
      align-items: center;
      justify-content: center;
    }

    .card .links a {
      text-decoration: none;
      margin: auto;
      --glow-color: rgb(217, 176, 255);
      --glow-spread-color: rgba(191, 123, 255, 0.781);
      --enhanced-glow-color: rgb(231, 206, 255);
      --btn-color: rgb(100, 61, 136);
      border: .25em solid var(--glow-color);
      padding: 1em 3em;
      color: var(--glow-color);
      font-size: 15px;
      font-weight: bold;
      background-color: var(--btn-color);
      border-radius: 1em;
      outline: none;
      box-shadow: 0 0 1em .25em var(--glow-color),
        0 0 4em 1em var(--glow-spread-color),
        inset 0 0 .75em .25em var(--glow-color);
      text-shadow: 0 0 .5em var(--glow-color);
      position: relative;
      transition: all 0.3s;
    }

    .card .links a::after {
      pointer-events: none;
      content: "";
      position: absolute;
      top: 120%;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: var(--glow-spread-color);
      filter: blur(2em);
      opacity: .7;
      transform: perspective(1.5em) rotateX(35deg) scale(1, .6);
    }

    .card .links a:hover {
      color: var(--btn-color);
      background-color: var(--glow-color);
      box-shadow: 0 0 1em .25em var(--glow-color),
        0 0 4em 2em var(--glow-spread-color),
        inset 0 0 .75em .25em var(--glow-color);
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html"; // Inclut le code HTML de l'en-tête
  ?>
  <title>Messages</title>
</head>

<body>
  <div class="container">
    <?php
    require_once "dashbord_body.html"; // Inclut le code HTML du corps de la page
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

        // Vérifie si l'identifiant de classe est fourni dans l'URL
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
          $class_id = $_GET['class_id'];

          // Requête pour obtenir les détails de la classe depuis la base de données
          $class_query = "SELECT * FROM classes WHERE id = '$class_id'";
          $class_result = mysqli_query($conn, $class_query);

          if ($class_result && mysqli_num_rows($class_result) > 0) {
            $class_row = mysqli_fetch_assoc($class_result);
            echo "<h2>" . $class_row['class_name'] . "</h2>";

            // Formulaire pour envoyer des messages
            echo "<h3>Envoyer un Message à l'Enseignant</h3>";
            echo "<form action='send_message.php' method='post'>";
            echo "<input type='hidden' name='class_id' value='" . $class_row['id'] . "'>";
            echo "<textarea name='message' placeholder='Tapez votre message ici' required></textarea><br>";
            echo "<input class='submit' type='submit' value='Envoyer le Message'>";
            echo "</form>";

            // Liens vers les messages envoyés et la boîte de réception
            echo "<div class='links'>";
            echo "<a href='sent_messages.php?class_id=" . $class_row['id'] . "'>Messages Envoyés</a><br>";
            echo "<a href='inbox.php?class_id=" . $class_row['id'] . "'>Boîte de Réception</a><br>";
            echo "</div>";

          } else {
            echo "Cours non trouvé ou vous n'avez pas la permission de voir ce cours.";
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
require_once "dashboard_script.html"; // Inclut le code JavaScript
?>

</html>
