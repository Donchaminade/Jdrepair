<?php
require_once '../includes/conf.php';
session_start();

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    echo "⛔ Accès refusé.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "⚠️ Utilisateur non spécifié.";
    exit();
}

$id = $_GET['id'];
$message = "";

// Récupération des infos actuelles
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo "⚠️ Utilisateur introuvable.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom_complet"]);
    $email = trim($_POST["email"]);
    $role = trim($_POST["role"]);

    if (!empty($nom) && !empty($email) && in_array($role, ['admin', 'technicien'])) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom_complet = ?, email = ?, role = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $email, $role, $id]);

        $message = "✅ Informations mises à jour avec succès.";
        // Actualiser les données récupérées
        $utilisateur['nom_complet'] = $nom;
        $utilisateur['email'] = $email;
        $utilisateur['role'] = $role;
    } else {
        $message = "⚠️ Tous les champs sont requis et valides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
</head>
<body>
    <h2>Modifier l'utilisateur</h2>
    <form method="POST">
        <input type="text" name="nom_complet" value="<?= htmlspecialchars($utilisateur['nom_complet']) ?>" required><br>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required><br>
        <select name="role" required>
            <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="technicien" <?= $utilisateur['role'] === 'technicien' ? 'selected' : '' ?>>Technicien</option>
        </select><br>
        <button type="submit">Enregistrer les modifications</button>
    </form>
    <div><?= $message ?></div>
    <a href="index.php">⬅ Retour</a>
</body>
</html>
