<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Récupère les informations sur les enseignants depuis la base de données
$query = "SELECT * FROM teachers";
$result = mysqli_query($conn, $query);

// Ferme la connexion à la base de données
mysqli_close($conn);
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
  <title>Voir les Enseignants</title>
  <style>
    /* Styles CSS */
    .card {
      display: flex;
      flex-direction: column;
      width: 1000px;
      margin: 50px auto;
      padding: 20px 50px;
      border-radius: 20px;
      background: #A196FF;
      box-shadow: 4px 4px 13px #8678cf,
        -4px -4px 13px #c6b0ff;
    }

    .card h2 {
      font-size: 35px;
      text-align: start;
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
      color: #162155;
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
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    .card .teacher-list {
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
        <h2>Voir les Enseignants</h2>

        <div class="teacher-list">
          <?php
          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<a href='teacher_info.php?id=" . $row['id'] . "' class='teacher-link'><i class='fa-solid fa-user-tie'></i>" . $row['firstName'] . " " . $row['lastName'] . "</a>";
            }
          } else {
            echo "<p class='message'>Aucun enseignant trouvé.</p>";
          }
          ?>
        </div>

      </div>
    </div>
  </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
