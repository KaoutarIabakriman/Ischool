<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'enseignant
if (!isset($_SESSION['teacher_id'])) {
    header("Location: my_classes.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Inclure la connexion à la base de données
include_once "connection.php";

// Vérifier si l'ID de la classe est fourni dans l'URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Récupérer les détails de la classe depuis la base de données
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
            <title>Boîte de réception</title>
            <style>
                /* CSS inclus */
                .card {
                    display: flex;
                    flex-direction: column;
                    width: 800px;
                    margin: 10px auto;
                    padding: 20px;
                    height: auto;
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

                .card .inbox-msg {
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

                .card .inbox-msg .date {
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

                .card .inbox-msg .date strong {
                    margin-right: 25px;
                }

                .card .inbox-msg .text {
                    padding: 10px 20px;
                    margin: 10px 40px;
                    font-size: 16px;
                    font-weight: 500;
                    letter-spacing: 1px;
                    columns: #480000;
                }

                .card .inbox-msg form {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 30px;
                    width: 90%;
                    margin: 10px auto;
                }

                .card .inbox-msg form textarea {
                    width: 90%;
                    min-height: 50px;
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

                .card .inbox {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .card .inbox-msg form textarea::placeholder {
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
                    border: 3px solid #B70000;
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
                        <h2>Boîte de réception</h2>

                        <?php
                        // Récupérer les messages des étudiants pour cette classe
                        $messages_query = "SELECT * FROM messages m
                        INNER JOIN students s ON s.id = m.sender_id
                        WHERE class_id = '$class_id'";
                        $messages_result = mysqli_query($conn, $messages_query);

                        if ($messages_result && mysqli_num_rows($messages_result) > 0) {
                            while ($message_row = mysqli_fetch_assoc($messages_result)) {
                                ?>
                                <div class="inbox-msg">
                                    <p class="date"><strong>De:</strong>
                                        <?php echo $message_row['firstname'] . " " . $message_row['lastname']; ?>
                                    </p>
                                    <p class="date"><strong>Envoyé le:</strong> <?php echo $message_row['sent_at']; ?></p>
                                    <strong class="date">Message:</strong>
                                    <p class="text"> <?php echo $message_row['message_content']; ?></p>
                                    <form action='reply_messages.php' method='post'>
                                        <input type='hidden' name='class_id' value='<?php echo $class_id; ?>'>
                                        <input type='hidden' name='message_id' value='<?php echo $message_row['m_id']; ?>'>
                                        <input type='hidden' name='student_id' value='<?php echo $message_row['sender_id']; ?>'>
                                        <textarea name='reply_message' placeholder="Répondre à l'étudiant" required></textarea>
                                        <input type='submit' value='Envoyer la réponse' class="submit">
                                    </form>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p class='message'>Aucun message dans la boîte de réception.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </body>
        <?php
        require_once "dashboard_script.html";
        ?>

        </html>
        <?php
    } else {
        echo "Cours introuvable ou vous n'êtes pas autorisé à afficher ce cours.";
    }
} else {
    echo "Requête invalide.";
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
