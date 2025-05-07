<?php
// db.php - Connexion à la base de données

$host = '127.0.0.1';       // Hôte de la base de données (généralement localhost)
$dbname = 'repa_db';          // Nom de la base de données
$username = 'root';        // Nom d'utilisateur de la base de données
$password = '';            // Mot de passe (par défaut pour XAMPP, c'est vide)

try {
    // Créer une instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Définir le mode d'erreur pour PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, afficher un message d'erreur
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    exit();
}
?>
