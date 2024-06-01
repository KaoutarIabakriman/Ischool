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

// Vérifier si l'identifiant de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  $class_id = $_GET['id'];

  // Récupérer les détails de la classe depuis la base de données
  $query_class = "SELECT c.class_name, c.description, c.key_words, c.pre_requirements, t.firstName AS teacher_firstName, t.lastName AS teacher_lastName
                    FROM classes c
                    INNER JOIN teachers t ON c.teacher_id = t.id
                    WHERE c.id = '$class_id'";
  $result_class = mysqli_query($conn, $query_class);

  if ($result_class && mysqli_num_rows($result_class) > 0) {
    $row_class = mysqli_fetch_assoc($result_class);
    $class_name = $row_class['class_name'];
    $description = $row_class['description'];
    $key_words = $row_class['key_words'];
    $pre_requirements = $row_class['pre_requirements'];
    $teacher_name = $row_class['teacher_firstName'] . " " . $row_class['teacher_lastName'];
  } else {
    echo "Classe non trouvée.";
    exit(); // Arrêter l'exécution ultérieure
  }

  // Récupérer les étudiants inscrits à la classe
  $query_students = "SELECT s.firstName, s.lastName FROM enrollment e INNER JOIN students s ON e.student_id = s.id WHERE e.class_id = '$class_id'";
  $result_students = mysqli_query($conn, $query_students);

  $students = [];
  if ($result_students && mysqli_num_rows($result_students) > 0) {
    while ($row_students = mysqli_fetch_assoc($result_students)) {
      $students[] = $row_students['firstName'] . " " . $row_students['lastName'];
    }
  }

  // Récupérer les chapitres de la classe
  $query_chapters = "SELECT * FROM chapters WHERE class_id = '$class_id'";
  $result_chapters = mysqli_query($conn, $query_chapters);

  $chapters = [];
  if ($result_chapters && mysqli_num_rows($result_chapters) > 0) {
    while ($row_chapters = mysqli_fetch_assoc($result_chapters)) {
      $chapters[] = $row_chapters;
    }
  }
} else {
  echo "Requête invalide.";
  exit(); // Arrêter l'exécution ultérieure
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require_once "dashbord_head.html";
  ?>
  <title>Informations sur la classe</title>
  <style>
    /* Styles CSS */
    .card {
      display: flex;
      flex-direction: column;
      width: 900px;
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

    .card .class-details {
      background: #0B0454;
      margin: 20px auto;
      padding: 25px;
      width: 90%;
      border-radius: 12px;
      display: flex;
      align-items: center;
      flex-direction: column;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .card .class-details strong {
      font-size: 20px;
      font-weight: 600;
      color: #fff;
      margin: 10px auto;
      border-bottom: 5px solid #fff;
      border-top: 5px solid #fff;
      border-radius: 10px;
      padding: 10px 20px;
    }

    .card .class-details p {
      background: #fff;
      color: #000010;
      width: 90%;
      padding: 15px;
      font-size: 16px;
      font-weight: 500;
      margin: 10px auto;
      letter-spacing: 1px;
      cursor: pointer;
      border-radius: 12px;
      box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.5);
      transition: .4s ease;
    }

    .card .class-details p:hover {
      background-color: hsl(261deg 80% 48%);
      transform: scale(1.02);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
      color: #fff;
    }

    .card .enrolled-students {
      background: #0B0454;
      margin: 20px auto;
      padding: 25px;
      border-radius: 12px;
      width: 90%;
      display: flex;
      align-items: center;
      flex-direction: column;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .card .enrolled-students h3 {
      font-size: 20px;
      font-weight: 600;
      color: #fff;
      margin: 10px auto;
      border-bottom: 5px solid #fff;
      border-top: 5px solid #fff;
      border-radius: 10px;
      padding: 10px 20px;
    }

    .card .students {
      display: flex;
      align-items: center;
      width: 100%;
      flex-wrap: wrap;
      padding: 20px;
      gap: 15px;
      justify-content: center;
      margin: 0 auto;
    }

    .card .enrolled-students .students a {
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

    .card .enrolled-students .students a i {
      font-size: 20px;
    }

    .card .enrolled-students .students a:hover {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    .card .chapters-list {
      background: #0B0454;
      margin: 20px auto;
      padding: 25px;
      border-radius: 12px;
      width: 90%;
      display: flex;
      align-items: center;
      flex-direction: column;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .card .chapters-list h3 {
      font-size: 20px;
      font-weight: 600;
      color: #fff;
      margin: 10px auto;
      border-bottom: 5px solid #fff;
      border-top: 5px solid #fff;
      border-radius: 10px;
      padding: 10px 20px;
    }

    .card .chapters-list .chapter {
      background: #fff;
      padding: 20px;
      margin: 10px;
      border-radius: 12px;
      width: 90%;
      display: flex;
      align-items: center;
      flex-direction: column;
    }

    .card .chapters-list .chapter h4 {
      font-size: 20px;
      font-weight: 600;
      text-transform: capitalize;
      margin: 0 auto 20px auto;
      background: #162155;
      color: #fff;
      padding: 15px 30px;
      border-radius: 12px;
    }

    .card .chapters-list .chapter .content {
      display: flex;
      align-items: center;
      gap: 50px;
      background: #162155;
      padding: 20px;
      border-radius: 12px;
    }

    .chapter .content p {
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .chapter .content a {
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

    .chapter .content a:hover {
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    .chapter .content a i {
      font-size: 22px;
    }

    .card button {
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
        <h2>Informations sur la classe</h2>

        <div class="class-details">
          <strong>Intitulé du cours</strong>
          <p> <?php echo $class_name; ?></p>
          <strong>Enseignant</strong>
          <p> <?php echo $teacher_name; ?></p>
          <strong>Description</strong>
          <p> <?php echo $description; ?></p>
          <strong>Mots-clés</strong>
          <p> <?php echo $key_words; ?></p>
          <strong>Prérequis</strong>
          <p> <?php echo $pre_requirements; ?></p>
        </div>

        <div class="enrolled-students">
          <h3>Étudiants inscrits</h3>
          <div class="students">
            <?php foreach ($students as $student): ?>
              <a><i class="fa-solid fa-user"></i><?php echo $student; ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="chapters-list">
          <h3>Chapitres</h3>
          <?php foreach ($chapters as $chapter): ?>
            <div class="chapter">
              <h4><?php echo $chapter['chapter_name']; ?></h4>
              <div class="content">
                <p>Créé à: <?php echo $chapter['created_at']; ?></p>
                <a target="_blank" href="../teacher/<?php echo $chapter['file_path']; ?>"><i
                    class="fa-solid fa-file-pdf"></i>Voir PDF</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <button>
          <a href="classes.php">Retour à la liste des cours</a>
        </button>
      </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
