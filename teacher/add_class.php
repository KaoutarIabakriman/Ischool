<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_name = $_POST['class_name'];
    $teacher_id = $_SESSION['teacher_id']; // Supposant que l'identifiant de l'enseignant est défini dans la session lors de la connexion
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $pre_requirements = $_POST['pre_requirements'];

    // Utilisation de prepared statements pour éviter les injections SQL
    $insert_sql = "INSERT INTO classes (class_name, teacher_id, description, key_words, pre_requirements) VALUES (?, ?, ?, ?, ?)";
    
    // Préparation de la requête
    $stmt = mysqli_prepare($conn, $insert_sql);
    if ($stmt) {
        // Liaison des paramètres et exécution de la requête
        mysqli_stmt_bind_param($stmt, "sisss", $class_name, $teacher_id, $description, $keywords, $pre_requirements);
        if (mysqli_stmt_execute($stmt)) {
            echo "Cours ajouté avec succès!";
            header("Location: welcome.php"); // Redirige vers la page de bienvenue
            exit();
        } else {
            echo "Erreur lors de l'ajout du cours : " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de la préparation de la requête : " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt); // Ferme la déclaration
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
    require_once "dashbord_head.html"; // Inclut les balises d'en-tête communes
    ?>
    <title>Ajouter un nouveau cours</title>
    <style>
        /* Styles CSS pour la mise en page */
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
            text-align: center;
        }

        .card h2 {
            font-size: 35px;
            text-align: start;
            font-weight: 600;
            margin: 30px auto;
            color: #480000;
            text-transform: uppercase;
            text-shadow: 0 2px white, 0 3px #777;
        }

        .card form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card input,
        .card textarea {
            width: 70%;
            margin: 0 auto 30px auto;
            padding: 12px 20px;
            outline: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            background: #FF8C8C;
            color: #181a21;
            resize: none;
            border: none;
            border-radius: 8px;
            transition: .4s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .card input:focus,
        .card textarea:focus {
            background: #B70000;
            color: #fff;
        }

        .card label {
            font-size: 21px;
            font-weight: 600;
            color: #350000;
            margin-bottom: 5px;
        }

        .card form .submit {
            text-decoration: none;
            margin: 15px auto;
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

        .card form .submit:hover {
            letter-spacing: 3px;
            background-color: #B70000;
            color: hsl(0, 0%, 100%);
            box-shadow: #B70000 0px 7px 29px 0px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        require_once "dashbord_body.html"; // Inclut le contenu du tableau de bord
        ?>
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon> <!-- Icône de menu -->
                </div>
            </div>
            <div class="card">
                <h2>Ajouter Un Nouveau Cours</h2> <!-- Titre du formulaire -->
                <form method="post" action="add_class.php"> <!-- Formulaire pour ajouter un nouveau cours -->
                    <label for="class_name">Intitulé Du Cours:</label> <!-- Champ pour le nom du cours -->
                    <input type="text" id="class_name" name="class_name" required> <!-- Champ de saisie du nom du cours -->

                    <label for="description">Description:</label><br> <!-- Champ pour la description du cours -->
                    <textarea id="description" name="description" rows="4" cols="50" required></textarea> <!-- Zone de texte pour la description du cours -->

                    <label for="keywords">Mots Clés:</label> <!-- Champ pour les mots-clés du cours -->
                    <input type="text" id="keywords" name="keywords"> <!-- Champ de saisie pour les mots-clés du cours -->

                    <label for="pre_requirements">Les Prérequis:</label><br> <!-- Champ pour les prérequis du cours -->
                    <textarea id="pre_requirements" name="pre_requirements" rows="4" cols="50"></textarea> <!-- Zone de texte pour les prérequis du cours -->

                    <input type="submit" value="Add Class" class="submit"> <!-- Bouton pour ajouter le cours -->
                </form>
            </div>
</body>
<?php
require_once "dashboard_script.html"; // Inclut les scripts communs pour le tableau de bord
?>

</html>
