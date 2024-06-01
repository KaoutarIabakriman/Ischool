<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion de l'administrateur si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Récupère les demandes de suppression de la base de données
$query = "SELECT d.*, firstname, lastname FROM delete_requests d INNER JOIN students s ON s.id = d.user_id";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html";
  ?>
  <title>Demandes de suppression</title>
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
      margin: 35px auto 50px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card thead th {
      text-transform: uppercase;
      font-size: 19px;
      color: #000055;
      font-weight: 600;
    }

    .message {
      font-size: 20px;
      font-weight: 500;
      padding: 35px;
      text-align: center;
    }

    .card tbody tr td {
      padding: 30px 0;
      text-align: center;
      border-bottom: 2px solid #010d24;
      font-size: 18px;
      font-weight: 500;
      color: #000011;
    }

    .card tbody tr:last-child td {
      border-bottom: none;
    }

    .card tbody tr td a {
      text-decoration: none;
      padding: 17px 20px;
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

    .card tbody tr td a:hover {
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
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
        <h2>Demandes de suppression</h2>
        <table>
          <thead>
            <tr>
              <th>Nom complet</th>
              <th>Type d'utilisateur</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
                echo "<td>" . $row['user_type'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td><a href='accept.php?request_id=" . $row['id'] . "&user_type=" . $row['user_type'] . "'>Accepter</a></td>";
                echo "<td><a href='reject.php?request_id=" . $row['id'] . "&user_type=" . $row['user_type'] . "'>Rejeter</a></td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='message'>Aucune demande de suppression</td></tr>";
            }
            ?>
          </tbody>
        </table>

      </div>

    </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
