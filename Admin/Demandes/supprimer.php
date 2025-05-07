<?php
include_once '../includes/conf.php';

$id = $_GET['id'];
$conn->query("DELETE FROM demande_reparation WHERE id_demande = $id");

header("Location: index.php");
exit();
?>
