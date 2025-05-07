<?php
session_start();

// On détruit toutes les variables de session
$_SESSION = [];

// On détruit la session
session_destroy();

// Redirection vers la page de connexion
header("Location: ../../index.html"); // ou index.php selon ton projet
exit;
?>
