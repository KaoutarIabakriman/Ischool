<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Rediriger vers la page de connexion si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Récupérer la liste des cours depuis la base de données
$query = "SELECT id, class_name FROM classes";
$result = mysqli_query($conn, $query);

$cours = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cours[] = $row;
    }
}
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
  <title>Liste des Cours</title>
  <style>
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

    .card .class-list {
      display: flex;
      align-items: center;
      width: 70%;
      flex-wrap: wrap;
      padding: 20px;
      gap: 20px;
      justify-content: center;
      margin: 30px auto;
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
        <h2>Liste des Cours</h2>

    <div class="class-list">
      <?php foreach ($cours as $cours): ?>
        <a href="class_info.php?id=<?php echo $cours['id']; ?>"><i class="fa-solid fa-book"></i><?php echo $cours['class_name']; ?></a>
      <?php endforeach; ?>
    </div>

      </div>
    </div>
    
  </div>
</body>
<?php
  require_once "dashboard_script.html";
?>

</html>
