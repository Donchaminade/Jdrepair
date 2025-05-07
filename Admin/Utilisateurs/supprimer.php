<?php
session_start();
require_once '../includes/conf.php';

if (!isset($_SESSION["admin_confirmed"]) || $_SESSION["admin_confirmed"] !== true) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}

header("Location: index.php");
exit();
