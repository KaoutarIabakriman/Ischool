<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
    header("Location: classes.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Styles CSS -->
  <style>
    .card {
      display: flex;
      flex-direction: column;
      width: 800px;
      margin: 40px auto;
      padding: 20px;
      border: 2px solid #c4c4c4;
      border-radius: 20px;
      background: #D5D9EE;
      box-shadow:  9px 9px 30px #b0b0b0,
                  -9px -9px 30px #ffffff;
    }

    .card h2 {
      font-size: 35px;
      text-align: start;
      font-weight: 600;
      margin: 30px auto 0 auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card span {
      font-size: 20px;
      font-weight: 600;
      text-transform: uppercase;
      margin: 20px auto 0 auto;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card p {
      width: 450px;
      background: #FCFCFC;
      text-align: center;
      font-size: 20px;
      font-weight: 600;
      color: #162155;
      margin: 10px auto 10px auto;
      padding: 15px;
      border-radius: 12px;
      border-radius: 20px;
      box-shadow:  9px 9px 30px #b0b0b0;
      line-height: 30px;
      letter-spacing: 1px;
      transition: .5s ease;
    }

    .card p:hover {
      transform: scale(1.05);
    }

    .card form {
      margin: 30px auto;
      width: 100%;
      text-align: center;
    }

    .card .submit {
      text-decoration: none;
      width: 70%;
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


    .card .submit:hover {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Inclure l'en-tête du tableau de bord -->
  <?php 
    require_once "dashbord_head.html";
  ?>
  <title>Inscription au cours</title>
</head>
<body>
  <div class="container">
    <?php 
      // Inclure le corps du tableau de bord
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
            // Inclure la connexion à la base de données
            include_once "connection.php";

            // Récupérer les cours disponibles depuis la base de données
            $class_query = "SELECT * FROM classes";
            $class_result = mysqli_query($conn, $class_query);

            // Afficher les cours disponibles avec des informations supplémentaires pour l'inscription
            if (mysqli_num_rows($class_result) > 0) {

                // Vérifier si l'ID de la classe est fourni dans l'URL
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
                    $clicked_class_id = $_GET['class_id'];

                    // Récupérer les informations détaillées pour la classe cliquée
                    $detail_query = "SELECT * FROM classes WHERE id = '$clicked_class_id'";
                    $detail_result = mysqli_query($conn, $detail_query);

                    if ($detail_result && mysqli_num_rows($detail_result) > 0) {
                        $class_row = mysqli_fetch_assoc($detail_result);
                        echo "<h2>" . $class_row['class_name'] . "</h2>";
                        echo "<span>Enseignant :</span> ";
                        echo "<p>". getTeacherName($class_row['teacher_id'], $conn) . "</p>";
                        echo "<span>Description :</span>";
                        echo "<p>" . $class_row['description'] . "</p>";
                        echo "<span>Prérequis :</span>";
                        echo "<p>". $class_row['pre_requirements'] . "</p>";
                        echo "<form method='post' action='enroll.php'>";
                        echo "<input type='hidden' name='class_id' value='" . $class_row['id'] . "'>";
                        echo "<input class='submit' type='submit' value=\"S'inscrire\">";
                        echo "</form>";
                    } else {
                        echo "Cours introuvable.";
                    }
                } else {
                    echo "Requête invalide.";
                }
            } else {
                echo "Aucun cours disponible pour l'inscription.";
            }

            // Fonction pour obtenir le nom de l'enseignant
            function getTeacherName($teacher_id, $conn) {
                $teacher_id = mysqli_real_escape_string($conn, $teacher_id);
                $query = "SELECT firstName, lastName FROM teachers WHERE id = '$teacher_id'";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['firstName'] . " " . $row['lastName'];
                } else {
                    return "Enseignant inconnu";
                }
            }

            // Fermer la connexion à la base de données
            mysqli_close($conn);
        ?>

      </div>
    </div>
  </div>
</body>
<!-- Inclure les scripts du tableau de bord -->
<?php 
  require_once "dashboard_script.html";
?>
</html>

