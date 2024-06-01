<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
  header("Location: my_classes.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
  exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si l'ID de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
  $class_id = $_GET['class_id'];

  // Récupérer les détails de la classe depuis la base de données
  $class_query = "SELECT * FROM classes WHERE id = '$class_id' AND teacher_id = '" . $_SESSION['teacher_id'] . "'";
  $class_result = mysqli_query($conn, $class_query);

  if ($class_result && mysqli_num_rows($class_result) > 0) {
    $class_row = mysqli_fetch_assoc($class_result);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
      <?php
      require_once "dashbord_head.html";
      ?>
      <title><?php echo $class_row['class_name']; ?></title>
      <style>
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
        }

        .card h2 {
          font-size: 35px;
          text-align: center;
          font-weight: 600;
          margin: 30px auto;
          color: #480000;
          text-transform: uppercase;
          text-shadow: 0 2px white, 0 3px #777;
        }

        .card .links {
          display: flex;
          align-items: center;
          padding: 20px;
          justify-content: center;
        }

        .card .links a {
          text-decoration: none;
          display: flex;
          align-items: center;
          gap: 20px;
          margin: 8px auto;
          padding: 17px 40px;
          border-radius: 50px;
          cursor: pointer;
          border: 0;
          color: #B70000;
          background-color: white;
          box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
          letter-spacing: 1.5px;
          text-transform: uppercase;
          font-size: 15px;
          transition: all 0.5s ease;
        }

        .card .links a i {
          font-size: 20px;
        }

        .card a:hover {
          letter-spacing: 3px;
          background-color: #B70000;
          color: hsl(0, 0%, 100%);
          box-shadow: #B70000 0px 7px 29px 0;
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
            <h2><?php echo $class_row['class_name']; ?></h2>

            <!-- Lien vers la boîte de réception et les messages envoyés -->
            <div class="links">
              <a href='inbox.php?class_id=<?php echo $class_row['id']; ?>'><i class="fa-solid fa-inbox"></i>Boîte de
                réception</a>
              <a href='sent_messages.php?class_id=<?php echo $class_row['id']; ?>'><i class="fa-solid fa-message"></i>Messages
                envoyés</a>
            </div>
          </div>
    </body>
    <?php
    require_once "dashboard_script.html";
    ?>

    </html>
    <?php
  } else {
    echo "Cours introuvable ou vous n'êtes pas autorisé à afficher ce cours.";
  }
} else {
  echo "Requête invalide.";
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
