<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['student_id'])) {
    header("Location: welcome.php"); // Redirige vers la page d'accueil si l'utilisateur n'est pas connecté
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Styles CSS pour la mise en forme -->
  <style>
    .card {
      width: 800px;
      margin: 50px auto;
      padding: 20px;
      border: 2px solid #c4c4c4;
      border-radius: 20px;
      background: #D5D9EE;
      box-shadow:  9px 9px 30px #b0b0b0,
                  -9px -9px 30px #ffffff;
    }

    .card h2 {
      font-size: 45px;
      text-align: center;
      font-weight: 600;
      margin: 20px auto;
      color: #010d24;
      text-transform: uppercase;
    }

    .card .cours {
      display: flex;
      gap: 25px;
      margin: 60px auto;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      width: 80%;
    }

    .card .cour {
      min-width: 150px;
      padding: 15px 25px;
      display: flex;
      align-items: center;
      background: #F0F0F0;
      box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2);
      border-radius: 9px;
      border-top: 5px solid #010d24;
      border-bottom: 5px solid #010d24;
      transition: .4s ease;
    }

    .card .cour:hover {
      transform: scale(1.1);
    }

    .card .cour i {
      font-size: 35px;
      color: #222;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 25px;
    }

    .card .cour a {
      text-decoration: none;
      font-size: 19px;
      font-weight: 500;
      color: #333;
      text-transform: uppercase;
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Inclusion de la bibliothèque Font Awesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Inclusion des ressources du tableau de bord -->
  <?php
    require_once "dashbord_head.html";
  ?>
  <title>Mes Cours</title>
</head>
<body>
  <div class="container">
    <!-- Inclusion du contenu du tableau de bord -->
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
        <?php
            // Inclusion de la connexion à la base de données
            include_once "connection.php";

            // Récupération de l'identifiant de l'étudiant depuis la session
            $student_id = $_SESSION['student_id'];

            // Récupération des cours auxquels l'étudiant est inscrit depuis la base de données
            $enrolled_query = "SELECT classes.id, classes.class_name FROM enrollment INNER JOIN classes ON enrollment.class_id = classes.id WHERE enrollment.student_id = '$student_id'";
            $enrolled_result = mysqli_query($conn, $enrolled_query);

            // Affichage des cours auxquels l'étudiant est inscrit sous forme de liens
            if (mysqli_num_rows($enrolled_result) > 0) {
                echo "<h2>Vos Cours</h2>";
                echo "<div class='cours'>";
                while ($enrolled_row = mysqli_fetch_assoc($enrolled_result)) {
                    echo "<div class='cour'>";
                    echo "<i class='fa-solid fa-book'></i>";
                    echo "<a href='class_details.php?class_id=" . $enrolled_row['id'] . "'>" . $enrolled_row['class_name'] . "</a><br>";
                    echo "</div>";
                  }
                echo "<div/>";
            } else {
                echo "<p class='message'>Vous n'êtes inscrits à aucun cours</p>";
            }

            // Fermeture de la connexion à la base de données
            mysqli_close($conn);
            ?>
      </div>
    </div>
  </div>
</body>
<!-- Inclusion des scripts JavaScript pour le tableau de bord -->
<?php
  require_once "dashboard_script.html";
?>
</html>
