<?php
session_start();

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: welcome.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Récupérer l'ID de l'enseignant à partir de la session
$teacher_id = $_SESSION['teacher_id'];

// Récupérer les cours créés par l'enseignant depuis la base de données
$classes_query = "SELECT * FROM classes WHERE teacher_id = '$teacher_id'";
$classes_result = mysqli_query($conn, $classes_query);

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
    <title>Vos cours</title>
    <style>
        .card {
            width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 2px solid #c4c4c4;
            border-radius: 20px;
            background: #FFB3B3;
            box-shadow: 9px 9px 30px #b0b0b0,
                -9px -9px 30px #ffffff;
        }

        .card h2 {
            font-size: 45px;
            text-align: center;
            font-weight: 600;
            margin: 20px auto;
            color: #480000;
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
            border-top: 5px solid #5B0000;
            border-bottom: 5px solid #5B0000;
            transition: .4s ease;
        }

        .card .cour:hover {
            transform: scale(1.1);
        }

        .card .cour i {
            font-size: 35px;
            color: #5B0000;
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
            color: #5B0000;
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
                <h2>Vos cours</h2>
                <div class="cours">
                    <?php
                    if (mysqli_num_rows($classes_result) > 0) {
                        while ($class_row = mysqli_fetch_assoc($classes_result)) {
                            echo "<div class='cour'>";
                            echo "<i class='fa-solid fa-book'></i>";
                            echo "<a href='class_details.php?class_id=" . $class_row['id'] . "'>" . $class_row['class_name'] . "</a><br>";
                            echo "</div>";
                        } ?>

                    </div>
                    <?php
                    } else {
                        echo "<p class='message'>Vous n'avez pas encore créé de cours.</p>";
                    }
                    mysqli_close($conn);
                    ?>
            </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
