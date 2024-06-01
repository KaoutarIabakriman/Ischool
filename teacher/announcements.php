<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: my_classes.php"); // Redirige vers la page de mes classes si non connecté
    exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'ID de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Récupère les détails de la classe depuis la base de données pour afficher le nom de la classe
    $class_query = "SELECT class_name FROM classes WHERE id = '$class_id' AND teacher_id = '" . $_SESSION['teacher_id'] . "'";
    $class_result = mysqli_query($conn, $class_query);
    $class_name = mysqli_fetch_assoc($class_result)['class_name'];

    if ($class_result && mysqli_num_rows($class_result) > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <?php
            require_once "dashbord_head.html"; // Inclut les balises d'en-tête communes
            ?>
            <title>Annonces</title>
            <style>
                /* CSS pour la mise en page de la page */
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

                .message {
                    font-size: 20px;
                    font-weight: 500;
                    padding: 25px;
                    width: 90%;
                    text-align: center;
                    margin: auto;
                    border-bottom: 2px solid #141D47;
                    border-radius: 25px;
                    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
                }

                .card form {
                    display: flex;
                    align-items: center;
                    flex-direction: column;
                    padding: 20px;
                    gap: 20px;
                }

                .card form textarea {
                    width: 70%;
                    min-height: 100px;
                    margin: 0 auto;
                    padding: 30px;
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

                .card form textarea::placeholder {
                    font-size: 16px;
                    font-weight: 600;
                    color: #181a21;
                }

                .card form textarea:focus {
                    background: #B70000;
                    color: #fff;
                }

                .card form .submit {
                    text-decoration: none;
                    margin: 0 auto;
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

                .card h3 {
                    font-size: 25px;
                    text-align: center;
                    font-weight: 600;
                    margin: 30px auto;
                    color: #480000;
                    text-transform: uppercase;
                    text-shadow: 0 2px white, 0 3px #777;
                }

                .card .announcements {
                    display: flex;
                    flex-direction: column;
                }

                .card .announcement {
                    display: flex;
                    width: 90%;
                    flex-direction: column;
                    align-items: start;
                    background: #fff;
                    padding: 20px;
                    margin: 10px auto;
                    border-radius: 20px;
                    box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.5);
                }

                .announcement .date {
                    background: #5B0000;
                    color: #fff;
                    padding: 13px;
                    font-size: 18px;
                    font-weight: 600;
                    border-radius: 20px;
                    margin-bottom: 20px;
                    box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
                }

                .announcement .date strong {
                    margin-right: 20px;
                }

                .announcement .date span {
                    font-weight: 400;
                    font-size: 15px;
                }

                .announcement .text {
                    width: 93%;
                    margin: auto 20px;
                    font-size: 18px;
                    font-weight: 500;
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

                        <h2>Annonces du cours: <?php echo $class_name; ?></h2> <!-- Titre affichant le nom du cours -->

                        <!-- Formulaire pour ajouter une nouvelle annonce -->
                        <form method="POST" action="add_announcement.php">
                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>"> <!-- Champ caché pour l'ID de la classe -->
                            <textarea name="announcement" placeholder="Écrivez votre annonce ici..." required></textarea> <!-- Zone de texte pour l'annonce -->
                            <input type="submit" value="Ajouter l'annonce" class="submit"> <!-- Bouton pour ajouter l'annonce -->
                        </form>

                        <?php
                        // Vérifie si le formulaire est soumis pour ajouter une nouvelle annonce
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['announcement'])) {
                            $announcement = mysqli_real_escape_string($conn, $_POST['announcement']);

                            // Insère une nouvelle annonce dans la base de données
                            $insert_query = "INSERT INTO announcements (class_id, announcement) VALUES ('$class_id', '$announcement')";
                            if (mysqli_query($conn, $insert_query)) {
                                echo "<p>Annonce ajoutée avec succès!</p>"; // Affiche un message de succès
                            } else {
                                echo "<p>Erreur lors de l'ajout de l'annonce: " . mysqli_error($conn) . "</p>"; // Affiche un message d'erreur avec les détails de l'erreur MySQL
                            }
                        }

                        // Récupère les annonces de cette classe depuis la base de données
                        $announcements_query = "SELECT * FROM announcements WHERE class_id = '$class_id' ORDER BY created_at DESC";
                        $announcements_result = mysqli_query($conn, $announcements_query);
                        // Affiche les annonces existantes
                        if ($announcements_result && mysqli_num_rows($announcements_result) > 0) {
                            echo "<h3>Annonces existantes</h3>"; // Titre pour les annonces existantes
                            echo "<div class='announcements'>";
                            while ($announcement_row = mysqli_fetch_assoc($announcements_result)) {
                                echo "<div class='announcement'>";
                                echo "<p class='date'><strong>Posté le:</strong> " . $announcement_row['created_at'] . "</p>"; // Affiche la date de publication de l'annonce
                                echo "<p class='text'>" . $announcement_row['announcement'] . "</p>"; // Affiche le texte de l'annonce
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p class='message'>Aucune annonce pour l'instant.</p>"; // Affiche un message si aucune annonce n'existe
                        }
                        ?>
                    </div>
        </body>
        <?php
        require_once "dashboard_script.html"; // Inclut les scripts communs pour le tableau de bord
        ?>

        </html>
        <?php
    } else {
        echo "Cours introuvable ou vous n'êtes pas autorisé à afficher les annonces de ce cours."; // Message si la classe n'est pas trouvée ou si l'utilisateur n'est pas autorisé à afficher les annonces de cette classe
    }
} else {
    echo "Requête invalide."; // Message si la requête est invalide
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>
