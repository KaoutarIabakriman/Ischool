<?php
// Include database connection
include_once "connection.php";

// Check if class_id is provided in the URL
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Fetch chapters for the specified class ID
    $chapters_query = "SELECT * FROM chapters WHERE class_id = '$class_id'";
    $chapters_result = mysqli_query($conn, $chapters_query);

    // Display chapters

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <?php
        require_once "dashbord_head.html";
        ?>
        <title>Chapitres</title>
        <style>
            .card {
                display: flex;
                flex-direction: column;
                width: 1000px;
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
                text-align: start;
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

            .card .chapters {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: 20px;
                padding: 25px;
                width: 90%;
                margin: auto;
            }

            .card .chapters .chapter {
                width: 350px;
                background: #ADADAD;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 15px;
                border-radius: 20px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
            }

            .card .chapters .chapter form {
                display: flex;
                flex-direction: column;
                width: 100%;
                align-items: center;
            }

            .chapter .head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 30px;
                margin: 10px auto;
            }

            .chapter .head i {
                font-size: 28px;
                color: #141D47;
                background: #fff;
                border-radius: 20px;
                padding: 10px;
            }

            .chapter .head h3 {
                font-size: 22px;
                font-weight: 600;
                color: #141D47;
            }

            .chapter select {
                padding: 10px 20px;
                border: none;
                outline: none;
                border-top-left-radius: 12px;
                border-bottom-left-radius: 12px;
                font-size: 15px;
                font-weight: 500;
            }

            .chapter .submit {
                padding: 10px 20px;
                cursor: pointer;
                border-top-right-radius: 12px;
                border-bottom-right-radius: 12px;
                border: 0;
                color: #FF1919;
                background-color: white;
                box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
                text-transform: uppercase;
                font-size: 15px;
                transition: all 0.5s ease;
            }

            .chapter .submit:hover {
                background-color: #B70000;
                color: hsl(0, 0%, 100%);
                box-shadow: #FF1919 0px 7px 29px 0px;
            }

            .chapter .view {
                text-decoration: none;
                margin: 10px auto;
                padding: 11px 20px;
                border-radius: 50px;
                width: 80%;
                cursor: pointer;
                text-align: center;
                border: 0;
                color: #FF1919;
                background-color: white;
                box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                font-size: 15px;
                transition: all 0.5s ease;
            }

            .chapter .view:hover {
                background-color: #B70000;
                color: hsl(0, 0%, 100%);
                box-shadow: #FF1919 0px 7px 29px 0px;
            }

            .chapter .links {
                display: flex;
                width: 100%;
                align-items: center;
                justify-content: center;
                padding: 0 10px;
                margin: 0 auto;
            }

            .chapter .links a{
                padding: 11px 25px;
                text-decoration: none;
                border-radius: 50px;
                cursor: pointer;
                text-align: center;
                border: 0;
                margin: 0 30px;
                color: #FF1919;
                background-color: white;
                box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
                font-size: 13px;
                font-weight: 500;
                letter-spacing: 1px;
                transition: all 0.5s ease;
                text-align: center;
            }

            .chapter .links a:hover {
                background-color: #B70000;
                color: hsl(0, 0%, 100%);
                box-shadow: #FF1919 0px 7px 29px 0px;
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
                    <h2>Chapitres</h2>
                    <div class="chapters">
                        <?php
                        if (mysqli_num_rows($chapters_result) > 0) {
                            while ($chapter_row = mysqli_fetch_assoc($chapters_result)) {
                                echo "<div class='chapter'>";
                                echo "<form action='update_chapter_visibility.php' method='post'>";
                                echo "<div class='head'>";
                                echo "<i class='fa-solid fa-print'></i>";
                                echo "<h3>" . $chapter_row['chapter_name'] . ($chapter_row['hidden'] ? " (Invisible)" : "") . "</h3>";
                                echo "</div>";
                                echo "<div class='select'>";
                                echo "<select name='visibility[" . $chapter_row['id'] . "]'>";
                                echo "<option value='0'>Rendre Visible</option>"; // Default to show
                                echo "<option value='1'>Rendre Invisible</option>";
                                echo "</select>";
                                echo "<input type='hidden' name='class_id' value='" . $class_id . "'>"; // Hidden input for class ID
                                echo "<input type='submit' value='Appliquer' class='submit'>";
                                echo "</div>";
                                echo "<a href='" . $chapter_row['file_path'] . "' target='_blank' class='view'>Afficher le PDF</a>"; // Link to view PDF
                                echo "<div class='links'>";
                                echo "<a href='delete_chapter.php?chapter_id=" . $chapter_row['id'] . "&class_id=" . $class_id . "'>Supprimer</a><br>";
                                echo "<a href='update_chapter.php?chapter_id=" . $chapter_row['id'] . "&class_id=" . $class_id . "'>Modifier</a><br>";
                                echo "</div>";
                                echo "</form>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <?php
                        } else {
                            echo "<p class='message'>Aucun chapitre disponible.</p>";
                        }
                        ?>
                </div>
    </body>
    <?php
    require_once "dashboard_script.html";
    ?>

    </html>
    <?php


    // Close database connection
    mysqli_close($conn);
} else {
    echo "Requête invalide.";
}
?>