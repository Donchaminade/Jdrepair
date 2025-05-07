<?php
// Démarrage de la session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['IdAdmin'])) {
    header("Location: Log.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gov";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérifiez si l'IdAdmin est transmis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['IdAdmin'])) {
    $IdAdmin = intval($_POST['IdAdmin']);

    // Requête SQL pour supprimer l'utilisateur
    $sql = "DELETE FROM Administration WHERE IdAdmin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $IdAdmin);

    if ($stmt->execute()) {
        // Redirection avec un message de succès
        header("Location: administration.php?message=Utilisateur supprimé avec succès");
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
