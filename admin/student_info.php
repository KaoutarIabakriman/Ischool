<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION["username"])) {
  // Redirige vers la page de connexion de l'administrateur si non connecté
  header("Location: admin_login.php");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'identifiant de l'étudiant est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Récupère les détails de l'étudiant depuis la base de données
    $query_student = "SELECT * FROM students WHERE id = '$student_id'";
    $result_student = mysqli_query($conn, $query_student);

    if ($result_student && mysqli_num_rows($result_student) > 0) {
        $row_student = mysqli_fetch_assoc($result_student);
        $firstName = $row_student['firstname'];
        $lastName = $row_student['lastname'];
        $email = $row_student['email'];
        // Ajoutez plus de champs si nécessaire
    } else {
        echo "Étudiant non trouvé.";
        exit(); // Arrête l'exécution ultérieure
    }

    // Récupère les cours auxquels l'étudiant est inscrit depuis la base de données
    $query_classes = "SELECT c.class_name FROM enrollment e INNER JOIN classes c ON e.class_id = c.id WHERE e.student_id = '$student_id'";
    $result_classes = mysqli_query($conn, $query_classes);

    $classes = [];
    if ($result_classes && mysqli_num_rows($result_classes) > 0) {
        while ($row_classes = mysqli_fetch_assoc($result_classes)) {
            $classes[] = $row_classes['class_name'];
        }
    }
} else {
    echo "Requête invalide.";
    exit(); // Arrête l'exécution ultérieure
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    require_once "dashbord_head.html";
  ?>
  <title>Informations sur l'étudiant</title>
  <style>
    /* Styles CSS */
    .card {
      display: flex;
      flex-direction: column;
      width: 700px;
      margin: 10px auto;
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
      margin: 35px auto 40px auto;
      color: #010d24;
      text-transform: uppercase;
      text-shadow: 0 2px white, 0 3px #777;
    }

    .card p {
      font-size: 20px;
      font-weight: 500;
      margin: 10px 30px;
    }

    .card p strong {
      font-size: 22px;
      font-weight: 600;
      border-bottom: 4px solid #010d24;
      margin-right: 30px;
    }

    .enrolled-classes {
      margin-bottom: 20px;
    }

    .enrolled-classes p {
      margin-bottom: 10px;
    }

    .card ul {
      width: 50%;
      margin: 40px auto 30px auto;
    }

    .card ul li {
      list-style: none;
      background: #010d24;
      text-align: center;
      color: #fff;
      margin: 10px;
      font-size: 18px;
      font-weight: 500;
      border-radius: 15px;
      padding: 15px 35px;
      cursor: pointer;
      text-transform: capitalize;
      box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.5);
      transition: .5s ease;
    }

    .card ul li:hover {
      transform: scale(1.06);
    }

    .card button  {
      align-items: center;
      background-image: linear-gradient(144deg, #AF40FF, #5B42F3 50%, #00DDEB);
      border: 0;
      border-radius: 8px;
      box-shadow: rgba(151, 65, 252, 0.2) 0 15px 30px -5px;
      box-sizing: border-box;
      margin: 0 auto;
      color: #FFFFFF;
      display: flex;
      font-size: 18px;
      justify-content: center;
      line-height: 1em;
      width: 340px;
      padding: 3px;
      text-decoration: none;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
      white-space: nowrap;
      cursor: pointer;
      transition: all .3s;
    }

    .card button:active,
    .card button:hover {
      outline: 0;
    }

    .card button a {
      background-color: rgb(5, 6, 45);
      padding: 16px 24px;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      width: 100%;
      height: 100%;
      transition: 300ms;
    }

    .card button:hover a {
      background: none;
    }

    .card button:active {
      transform: scale(0.9);
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
        <h2>Informations sur l'étudiant</h2>

        <div class="student-details">
          <p><strong>ID:</strong> <?php echo $student_id; ?></p>
          <p><strong>Prénom:</strong> <?php echo $firstName; ?></p>
          <p><strong>Nom de famille:</strong> <?php echo $lastName; ?></p>
          <p><strong>Email:</strong> <?php echo $email; ?></p>
          <?php if (!empty($classes)): ?>
            <div class="enrolled-classes">
              <p><strong>Cours inscrits:</strong></p>
              <ul>
                <?php foreach ($classes as $class): ?>
                  <li><?php echo $class; ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <!-- Ajoutez plus de champs si nécessaire -->
        </div>
        <button>
          <a href="students.php">Retour à la liste des étudiants</a>
        </button>
      </div>
    </div>
</body>
<?php
  require_once "dashboard_script.html";
?>
</html>
