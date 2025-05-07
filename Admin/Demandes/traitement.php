<?php
require_once '../includes/conf.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID requis");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO traitement (id_demande, date_reception, montant_total, montant_paye, type_reparation)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $id, $_POST['date_reception'], $_POST['montant_total'],
        $_POST['montant_paye'], $_POST['type_reparation']
    ]);
    header("Location: index.php");
    exit;
}
?>

<!-- Formulaire Bootstrap -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement de la demande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Traitement de la demande</h3>
    <form method="POST">
        <label>Date de réception</label>
        <input type="date" name="date_reception" class="form-control mb-2" required>
        <label>Montant total</label>
        <input type="number" name="montant_total" class="form-control mb-2" required>
        <label>Montant payé</label>
        <input type="number" name="montant_paye" class="form-control mb-2" required>
        <label>Type de réparation</label>
        <select name="type_reparation" class="form-control mb-3">
            <option value="standard">Standard</option>
            <option value="complexe">Complexe</option>
        </select>
        <button class="btn btn-primary">Valider</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
