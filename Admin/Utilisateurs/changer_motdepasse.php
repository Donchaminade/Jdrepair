<?php
session_start();
require_once '../includes/conf.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ancien = $_POST['ancien_mdp'];
    $nouveau = $_POST['nouveau_mdp'];

    $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$_SESSION['utilisateur_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($ancien, $user['mot_de_passe'])) {
        $nouveau_hash = password_hash($nouveau, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nouveau_hash, $_SESSION['utilisateur_id']]);
        $message = "Mot de passe mis à jour.";
    } else {
        $message = "Mot de passe actuel incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer mot de passe</title>
</head>
<body>
<h2>Changer votre mot de passe</h2>
<?php if ($message): ?><p><?= $message ?></p><?php endif; ?>
<form method="post">
    <input type="password" name="ancien_mdp" placeholder="Ancien mot de passe" required><br>
    <input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe" required><br>
    <button type="submit">Mettre à jour</button>
</form>
</body>
</html>
