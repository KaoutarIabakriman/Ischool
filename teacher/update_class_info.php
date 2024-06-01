<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant que professeur
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si class_id est fourni dans l'URL
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Récupérer les informations de classe pour l'ID de classe spécifié
    $class_query = "SELECT * FROM classes WHERE id = ?";
    
    // Préparation de la requête
    $stmt = mysqli_prepare($conn, $class_query);
    if ($stmt) {
        // Liaison des paramètres et exécution de la requête
        mysqli_stmt_bind_param($stmt, "i", $class_id);
        if (mysqli_stmt_execute($stmt)) {
            $class_result = mysqli_stmt_get_result($stmt);
            if ($class_result && mysqli_num_rows($class_result) > 0) {
                $class_row = mysqli_fetch_assoc($class_result);
                $class_name = $class_row['class_name'];
                $description = $class_row['description'];
                $key_words = $class_row['key_words'];
                $pre_requirements = $class_row['pre_requirements'];

                // Vérifier si le formulaire est soumis pour mettre à jour les infos de la classe
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_name']) && isset($_POST['description']) && isset($_POST['key_words']) && isset($_POST['pre_requirements'])) {
                    $new_class_name = $_POST['class_name'];
                    $new_description = $_POST['description'];
                    $new_key_words = $_POST['key_words'];
                    $new_pre_requirements = $_POST['pre_requirements'];
                    // Mettre à jour les informations de classe dans la base de données
                    $update_query = "UPDATE classes SET class_name = ?, description = ?, key_words = ?, pre_requirements = ? WHERE id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_query);
                    if ($update_stmt) {
                        mysqli_stmt_bind_param($update_stmt, "ssssi", $new_class_name, $new_description, $new_key_words, $new_pre_requirements, $class_id);
                        if (mysqli_stmt_execute($update_stmt)) {
                            echo "Les informations du cours ont été mises à jour avec succès!";
                            // Rediriger vers la page de détails de la classe ou toute autre page si nécessaire
                            header("Location: class_details.php?class_id=" . $class_id);
                            exit();
                        } else {
                            echo "Erreur lors de la mise à jour des informations du cours: " . mysqli_error($conn);
                        }
                        mysqli_stmt_close($update_stmt); // Fermer la déclaration de mise à jour
                    } else {
                        echo "Erreur lors de la préparation de la requête de mise à jour : " . mysqli_error($conn);
                    }
                }

                // Afficher le formulaire pour mettre à jour les informations de la classe
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <?php
                    require_once "dashbord_head.html";
                    ?>
                    <title>Mettre à jour les informations du cours</title>
                    <style>
                        /* Styles CSS pour la mise en page */
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
                        require_once "dashbord_body.html";
                        ?>
                        <div class="main">
                            <div class="topbar">
                                <div class="toggle">
                                    <ion-icon name="menu-outline"></ion-icon>
                                </div>
                            </div>
                            <div class="card">
                                <h2>Mettre à jour les informations du cours</h2>
                                <form action="" method="post">
                                    <label for="class_name">Intitulé Du Cours:</label>
                                    <input type="text" id="class_name" name="class_name" value="<?php echo $class_name; ?>"
                                        required><br><br>

                                    <label for="description">Description:</label><br>
                                    <textarea id="description" name="description" rows="4" cols="50"
                                        required><?php echo $description; ?></textarea><br><br>

                                    <label for="key_words">Mots Clés:</label>
                                    <input type="text" id="key_words" name="key_words" value="<?php echo $key_words; ?>"
                                        required><br><br>

                                    <label for="pre_requirements">Les Prérequis:</label><br>
                                    <textarea id="pre_requirements" name="pre_requirements" rows="4" cols="50"
                                        required><?php echo $pre_requirements; ?></textarea><br><br>


                                    <input type="submit" value="Mettre à jour" class="submit">
                                </form>
                            </div>
                </body>
                <?php
                require_once "dashboard_script.html";
                ?>

                </html>
                <?php
            } else {
                echo "Cours Introuvable.";
            }
            mysqli_stmt_close($stmt); // Fermer la déclaration
        } else {
            echo "Erreur lors de l'exécution de la requête : " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de la préparation de la requête : " . mysqli_error($conn);
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
} else {
    echo "Requête invalide.";
}
?>
