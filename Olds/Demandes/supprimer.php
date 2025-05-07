<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM Clients WHERE id_client = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: index.php?message=Client supprimé avec succès !");
    exit();
}
?>
