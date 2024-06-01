<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
  // Redirige vers la page de connexion si non connecté
  header("Location: index.html");
  exit();
}

// Inclut la connexion à la base de données
include_once "connection.php";

// Récupère les informations de l'utilisateur actuel
$email = $_SESSION["email"];
$query = "SELECT id, firstname, lastname, email FROM teachers WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $id = $row['id'];
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $email = $row['email'];
} else {
  echo "Erreur lors de la récupération des informations utilisateur.";
}

// Met à jour les informations de l'utilisateur si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $newFirstname = $_POST['firstname'];
  $newLastname = $_POST['lastname'];
  $newEmail = $_POST['email'];
  $newPassword = $_POST['password'];

  // Valide et met à jour la base de données
  // Remarque : Vous devriez ajouter une validation adéquate et le hachage pour le mot de passe
  $hashed_password = md5($newPassword);
  $updateQuery = "UPDATE teachers SET firstname = '$newFirstname', lastname = '$newLastname', email = '$newEmail', password = '$hashed_password' WHERE id = '$id'";
  $updateResult = mysqli_query($conn, $updateQuery);

  if ($updateResult) {
    header("Location: profile.php");
    // Met à jour l'email de session si l'email est changé
    $_SESSION["email"] = $newEmail;
  } else {
    echo "Erreur lors de la mise à jour du profil.";
  }
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
  require_once "dashbord_head.html";
  ?>
  <title>Modifier Le Profil</title>
  <style>
    .card {
      width: 800px;
      margin: 10px auto;
      padding: 20px;
      border: 2px solid #c4c4c4;
      border-radius: 8px;
      background: #FFB3B3;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
    }
    .card h2 {
      font-size: 45px;
      text-align: center;
      font-weight: 600;
      margin: 20px auto;
      color: #350000;
      text-transform: uppercase;
    }
    .card form {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }

    .card form .inputs {
      display: flex;
      width: 70%;
      flex-direction: column;
      text-align: center;
      text-align: start;
      justify-content: center;
    }

    .card form .inputs label {
      font-size: 19px;
      font-weight: 500;
      color: #350000;
      margin-bottom: 8px;
    }

    .card form .inputs input {
      width: 100%;
      margin: auto;
      padding: 12px 20px;
      outline: none;
      font-size: 16px;
      font-weight: 600;
      background: #FF8C8C;
      color: #181a21;
      border: none;
      border-radius: 8px;
      transition: .4s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .card form .inputs input:focus {
      background: #B70000;
      color: #fff;
    }
    .card form .submit {
      text-decoration: none;
      margin: 8px auto;
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
        <h2>Modifier Le Profil</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="inputs">
            <label for="firstname">Prénom:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required><br>

            <label for="lastname">Nom:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required><br>
          </div>

          <input type="submit" value="Modifier Le Profil" class="submit">
        </form>
      </div>
</body>
<?php
require_once "dashboard_script.html";
?>

</html>
