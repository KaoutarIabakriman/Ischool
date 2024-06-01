<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        /* Styles de base pour le conteneur de carte */
        .card {
            display: flex;
            flex-direction: column;
            width: 1000px;
            margin: 10px auto;
            padding: 20px 50px;
            border: 2px solid #c4c4c4;
            border-radius: 20px;
            background: #D5D9EE;
            box-shadow: 9px 9px 30px #b0b0b0,
                -9px -9px 30px #ffffff;
        }

        /* Styles pour l'en-tête à l'intérieur de la carte */
        .card h2 {
            font-size: 35px;
            text-align: start;
            font-weight: 600;
            margin: 35px auto 10px auto;
            color: #010d24;
            text-transform: uppercase;
            text-shadow: 0 2px white, 0 3px #777;
        }

        /* Styles pour le conteneur de classes à l'intérieur de la carte */
        .card .classes {
            width: 90%;
            margin: 0 auto;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        /* Styles pour chaque élément de classe */
        .card .classes .cour {
            min-width: 150px;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            background: #F0F0F0;
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2);
            border-radius: 9px;
            border-top: 5px solid #000046;
            border-bottom: 5px solid #000046;
            transition: .4s ease;
        }

        /* Effet de survol pour les éléments de classe */
        .card .classes .cour:hover {
            transform: scale(1.1);
        }

        /* Styles pour les icônes dans les éléments de classe */
        .card .classes .cour i {
            font-size: 35px;
            color: #000046;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
        }

        /* Styles pour les liens dans les éléments de classe */
        .card .classes .cour a {
            text-decoration: none;
            font-size: 19px;
            font-weight: 500;
            color: #333;
            text-transform: uppercase;
        }

        /* Styles pour le message affiché en cas de résultat vide ou de terme de recherche non fourni */
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
    <meta charset="UTF-8">
    <!-- Inclure FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Inclure l'en-tête HTML du tableau de bord
    require_once "dashbord_head.html";
    ?>
    <title>Recherche de cours</title>
</head>
<body>
    <div class="container">
        <?php
        // Inclure le corps HTML du tableau de bord
        require_once "dashbord_body.html";
        ?>
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
            </div>
            <div class="card">
                <?php
                // Inclure les paramètres de connexion à la base de données
                include_once "connection.php";

                // Vérifier si un terme de recherche est fourni
                if (isset($_GET['search'])) {
                    // Sanitiser l'entrée pour éviter les injections SQL
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);

                    // Requête SQL pour récupérer les cours correspondant au terme de recherche
                    $search_query = "SELECT * FROM classes WHERE class_name LIKE '%$searchTerm%' OR key_words LIKE '%$searchTerm%'";
                    $search_result = mysqli_query($conn, $search_query);

                    // Vérifier si des résultats ont été retournés
                    if ($search_result && mysqli_num_rows($search_result) > 0) {
                        echo "<h2>Résultats de la recherche</h2>";
                        echo "<div class='classes'>";
                        // Afficher chaque cours sous forme de lien
                        while ($row = mysqli_fetch_assoc($search_result)) {
                            echo "<div class='cour'>";
                            echo "<i class='fa-solid fa-book'></i>";
                            echo "<a href='enroll_page.php?class_id=" . $row['id'] . "'>" . htmlspecialchars($row['class_name']) . "</a><br>";
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p class='message'>Aucun résultat trouvé.</p>";
                    }
                } else {
                    echo "<p class='message'>Aucun terme de recherche fourni.</p>";
                }

                // Fermer la connexion à la base de données
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>
<?php
// Inclure les scripts du tableau de bord
require_once "dashboard_script.html";
?>
</html>