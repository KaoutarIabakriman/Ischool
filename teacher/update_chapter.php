<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que professeur
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chapter_id']) && isset($_POST['class_id'])) {
    $chapter_id = $_POST['chapter_id'];
    $new_chapter_name = $_POST['new_chapter_name'];
    $file_name = $_FILES["new_chapter_file"]["name"];
    $file_temp = $_FILES["new_chapter_file"]["tmp_name"];
    $class_id = $_POST['class_id'];

    // Vérifier si le fichier est un PDF
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    if (strtolower($file_ext) != "pdf") {
        echo "Erreur : seuls les fichiers PDF sont autorisés.";
        exit();
    }

    // Télécharger le nouveau fichier
    $upload_dir = "uploads/";
    $file_path = $upload_dir . $file_name;
    if (move_uploaded_file($file_temp, $file_path)) {
        // Mettre à jour les informations du chapitre dans la base de données
        $update_query = "UPDATE chapters SET chapter_name = '$new_chapter_name', file_path = '$file_path' WHERE id = '$chapter_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "Chapitre importé avec succès!";
            header("Location: preview_chapters.php?class_id=" . $class_id);
        } else {
            echo "Erreur lors de l'importation du chapitre: " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de l'importation.";
    }
} else {
    // Inclure le formulaire HTML s'il n'y a pas de soumission de formulaire
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        require_once "dashbord_head.html";
        ?>
        <title>Importer Un Chapitre</title>
        <style>
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
            }

            .card h2 {
                font-size: 35px;
                text-align: center;
                font-weight: 600;
                margin: 30px auto;
                color: #480000;
                text-transform: uppercase;
                text-shadow: 0 2px white, 0 3px #777;
            }

            .card form {
                display: flex;
                flex-direction: column;
                gap: 30px;
                padding: 30px;
            }

            .card label {
                font-size: 18px;
                margin-right: 30px;
                font-weight: 600;
                color: #fff;
                background: #480000;
                padding: 15px;
                border-radius: 12px;
            }

            .card form .input {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .card form .input input {
                flex: 1;
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

            .card form .input input:focus {
                background: #B70000;
                color: #fff;
            }

            .card form .submit {
                margin: 15px auto;
                padding: 17px 40px;
                border-radius: 50px;
                cursor: pointer;
                border: 0;
                color: #B70000;
                width: 80%;
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
            require_once "dashbord_body.html";
            ?>
            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <h2>Importer Un Chapitre</h2>
                    <form action="update_chapter.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="chapter_id" value="<?php echo $_GET['chapter_id']; ?>">
                        <input type="hidden" name="class_id" value="<?php echo $_GET['class_id']; ?>">
                        <!-- Obtenir l'ID du chapitre depuis l'URL -->
                        <div class="input">
                            <label for="new_chapter_name">Intitulé Du Chapitre:</label>
                            <input type="text" id="new_chapter_name" name="new_chapter_name" required>
                        </div>

                        <div class="input">
                            <label for="new_chapter_file">Importer Un PDF:</label>
                            <input type="file" id="new_chapter_file" name="new_chapter_file" accept=".pdf" required>
                        </div>

                        <input type="submit" value="Importer" class="submit">
                    </form>
                </div>
    </body>
    <?php
    require_once "dashboard_script.html";
    ?>

    </html>
    <?php
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
