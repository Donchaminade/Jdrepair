<?php
include_once '../includes/conf.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM demande_reparation WHERE id_demande = $id");
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("UPDATE demande_reparation SET nom_complet=?, numero=?, email=?, adresse=?, marque_telephone=?, probleme=?, type_reparation=? WHERE id_demande=?");
    $stmt->bind_param("sssssssi", $_POST['nom_complet'], $_POST['numero'], $_POST['email'], $_POST['adresse'], $_POST['marque_telephone'], $_POST['probleme'], $_POST['type_reparation'], $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la demande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h3>Modifier la demande</h3>
    <form method="POST">
        <div class="mb-3"><input type="text" name="nom_complet" value="<?= $data['nom_complet'] ?>" class="form-control" required></div>
        <div class="mb-3"><input type="text" name="numero" value="<?= $data['numero'] ?>" class="form-control" required></div>
        <div class="mb-3"><input type="email" name="email" value="<?= $data['email'] ?>" class="form-control" required></div>
        <div class="mb-3"><input type="text" name="adresse" value="<?= $data['adresse'] ?>" class="form-control" required></div>
        <div class="mb-3"><input type="text" name="marque_telephone" value="<?= $data['marque_telephone'] ?>" class="form-control" required></div>
        <div class="mb-3"><textarea name="probleme" class="form-control" required><?= $data['probleme'] ?></textarea></div>
        <div class="mb-3">
            <select name="type_reparation" class="form-control" required>
                <option value="standard" <?= $data['type_reparation'] == 'standard' ? 'selected' : '' ?>>Standard</option>
                <option value="urgente" <?= $data['type_reparation'] == 'urgente' ? 'selected' : '' ?>>Urgente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    </form>
</body>
</html>
