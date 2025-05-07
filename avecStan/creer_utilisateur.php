<?php
session_start();

// Optionnel : Tu peux sécuriser ici pour que seuls certains puissent créer des comptes
// if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
//     header("Location: login.php");
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ace3i_bd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    if (!empty($nom) && !empty($mot_de_passe)) {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, mot_de_passe) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $mot_de_passe_hash);

        if ($stmt->execute()) {
            $message = "Utilisateur créé avec succès 🎉.";
        } else {
            $message = "Erreur lors de la création : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un utilisateur</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        input, button { padding: 10px; margin: 10px; }
        .message { margin-top: 20px; color: green; }
    </style>
</head>
<body>
    <h1>Créer un nouvel utilisateur</h1>
    <form method="POST" action="">
        <input type="text" name="nom" placeholder="Nom d'utilisateur" required><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
        <button type="submit">Créer utilisateur</button>
    </form>
    <?php if (!empty($message)) { echo "<div class='message'>$message</div>"; } ?>
</body>
</html>
