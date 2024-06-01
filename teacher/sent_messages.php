<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant que professeur
if (!isset($_SESSION['teacher_id'])) {
    header("Location: my_classes.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Vérifie si l'identifiant de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Récupère les détails de la classe depuis la base de données
    $class_query = "SELECT * FROM classes WHERE id = '$class_id' AND teacher_id = '" . $_SESSION['teacher_id'] . "'";
    $class_result = mysqli_query($conn, $class_query);

    if ($class_result && mysqli_num_rows($class_result) > 0) {
        $class_row = mysqli_fetch_assoc($class_result);
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <?php
            require_once "dashbord_head.html";
            ?>
            <title>Les Messages Envoyés</title>
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

                .card .sent-message {
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

                .card .sent-message .date {
                    background: #5B0000;
                    color: #fff;
                    padding: 13px;
                    letter-spacing: 1px;
                    font-size: 15px;
                    font-weight: 600;
                    border-radius: 20px;
                    margin-bottom: 10px;
                    margin-left: 20px;
                    box-shadow: 2px 2px 9px rgba(0, 0, 0, 0.4);
                }

                .card .sent-message .date strong {
                    margin-right: 25px;
                }

                .card .sent-message .text {
                    padding: 10px 20px;
                    margin: 10px 40px;
                    font-size: 17px;
                    font-weight: 600;
                    letter-spacing: 1px;
                    columns: #480000;
                }

                .card .sent-message .head {
                    font-size: 18px;
                    font-weight: 600;
                    letter-spacing: 1px;
                    color: #fff;
                    background: #910000;
                    padding: 15px;
                    border-radius: 15px;
                    margin: 10px 40px;
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
                        <h2>Les Messages Envoyés</h2>

                        <?php
                        // Récupère les messages et les réponses pour cette classe
                        $messages_query = "SELECT m.m_id AS message_id, m.sent_at, m.message_content, s.firstname, s.lastname, r.reply_message, r.replied_at
            FROM messages m
            INNER JOIN students s ON s.id = m.sender_id
            LEFT JOIN replies r ON r.message_id = m.m_id
            WHERE m.class_id = '$class_id' AND r.teacher_id = '" . $_SESSION['teacher_id'] . "'";
                        $messages_result = mysqli_query($conn, $messages_query);

                        if ($messages_result && mysqli_num_rows($messages_result) > 0) {
                            while ($message_row = mysqli_fetch_assoc($messages_result)) {
                                ?>
                                <div class="sent-message">
                                    <p class="date"><strong>De:</strong> <?php echo $message_row['firstname'] . " " . $message_row['lastname']; ?>
                                    </p>
                                    <p class="date"><strong>Envoyé le:</strong> <?php echo $message_row['sent_at']; ?></p>
                                    <strong class="head">Message:</strong>
                                    <p class="text"><?php echo $message_row['message_content']; ?></p>
                                    <?php if ($message_row['reply_message']) { ?>
                                        <strong class="head">Réponse du Professeur:</strong>
                                        <p class="text"><?php echo $message_row['reply_message']; ?></p>
                                        <p class="date"><strong>Envoyé le:</strong> <?php echo $message_row['replied_at']; ?></p>
                                    <?php } else { ?>
                                        <p>Pas encore de réponse.</p>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p class='message'>Aucun message avec réponse trouvé.</p>";
                        }
                        ?>
        </body>
        <?php
        require_once "dashboard_script.html";
        ?>

        </html>
        <?php
    } else {
        echo "Classe introuvable ou vous n'êtes pas autorisé à voir les élèves de cette classe.";
    }
} else {
    echo "Requête invalide.";
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>