<?php
require_once '../includes/conf.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = trim($_POST['nom_complet']);
    $email = trim($_POST['email']);
    $nouveau_mdp = trim($_POST['nouveau_mdp']);

    if (empty($nom_complet) || empty($email) || empty($nouveau_mdp)) {
        $message = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_complet = ? AND email = ?");
        $stmt->execute([$nom_complet, $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?");
            $update->execute([$hash, $user['id_utilisateur']]);
            $message = '✅ Mot de passe réinitialisé avec succès.';
            $success = true;
        } else {
            $message = '❌ Nom complet ou adresse email incorrect.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #373B44, #4286f4);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="card col-md-6">
    <h3 class="mb-4 text-center">🔒 Réinitialiser le mot de passe</h3>

    <?php if ($message): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nom complet</label>
            <input type="text" name="nom_complet" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="nouveau_mdp" class="form-control" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Réinitialiser</button>
            <a href="../index.html" class="btn btn-secondary ms-2">Annuler</a>
        </div>
    </form>
</div>
</body>
</html>
