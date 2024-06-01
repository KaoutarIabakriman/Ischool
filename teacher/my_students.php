<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
  header("Location: class_details.php"); // Rediriger vers la page de connexion si non connecté
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
    <html lang="fr">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
      <?php
      require_once "dashbord_head.html";
      ?>
      <title>Étudiants inscrits</title>
      <style>
      .card {
      display: flex;
      flex-direction: column;
      width: 1000px;
      margin: 50px auto;
      padding: 20px 50px;
      border-radius: 20px;
      background: #FFB3B3;
      box-shadow: 4px 4px 13px #8678cf,
        -4px -4px 13px #c6b0ff;
    }

    .card h2 {
      font-size: 35px;
      text-align: center;
      font-weight: 600;
      margin: 35px auto 10px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card a {
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

    .card a i {
      font-size: 20px;
    }

    .card a:hover {
      letter-spacing: 3px;
      background-color: #B70000;
      color: hsl(0, 0%, 100%);
      box-shadow: #B70000 0px 7px 29px 0px;
    }

    .card .student-list {
      display: flex;
      align-items: center;
      width: 70%;
      flex-wrap: wrap;
      padding: 20px;
      gap: 20px;
      justify-content: center;
      margin: 30px auto;
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
            <h2>Les étudiants inscrits à ce cours <?php echo $class_row['class_name']; ?></h2>

            <?php
            // Récupérer les étudiants inscrits au cours depuis la base de données
            $enrolled_query = "SELECT students.id, students.firstName, students.lastName FROM enrollment INNER JOIN students ON enrollment.student_id = students.id WHERE enrollment.class_id = '$class_id'";
            $enrolled_result = mysqli_query($conn, $enrolled_query);

            // Afficher les étudiants inscrits sous forme de liens
            echo "<div class='student-list'>";
            if (mysqli_num_rows($enrolled_result) > 0) {
              while ($enrolled_row = mysqli_fetch_assoc($enrolled_result)) {
                echo "<a href=''>" . "<i
                    class='fa-solid fa-user'></i>" . $enrolled_row['firstName'] . " " . $enrolled_row['lastName'] . "</a>";
              }
              echo "<div/>";
            } else {
              echo "<p class='message'>Aucun élève inscrit dans ce cours</p>";
            }
            ?>
          </div>
    </body>
    <?php
    require_once "dashboard_script.html";
    ?>

    </html>
    <?php
  } else {
    echo "Classe introuvable ou vous n'êtes pas autorisé à voir les élèves de cette classe.";
  }
} else {
  echo "Requête invalide.";
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
