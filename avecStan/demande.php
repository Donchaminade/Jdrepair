<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = ''; // À adapter selon ta configuration
$dbname = 'reparationbd';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Traitement du formulaire
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom_complet = $_POST['nom_complet'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $marque = $_POST['marque'];
    $probleme = $_POST['probleme'];
    $date_demande = $_POST['date_demande'];
    $type_reparation = $_POST['type_reparation'];

    $sql = "INSERT INTO demande_reparation (nom_complet, numero, email, adresse, marque_telephone, probleme, date_demande, type_reparation)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssss", $nom_complet, $numero, $email, $adresse, $marque, $probleme, $date_demande, $type_reparation);
        if ($stmt->execute()) {
            $success = "Demande enregistrée avec succès.";
        } else {
            $error = "Erreur lors de l'enregistrement.";
        }
        $stmt->close();
    } else {
        $error = "Erreur de requête préparée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Faire une demande de réparation</title>
</head>
<body>
    <h2>Formulaire de demande de réparation</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Nom complet:</label><br>
        <input type="text" name="nom_complet" required><br><br>

        <label>Numéro:</label><br>
        <input type="text" name="numero" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br><br>

        <label>Adresse:</label><br>
        <input type="text" name="adresse"><br><br>

        <label>Marque du téléphone:</label><br>
        <input type="text" name="marque" required><br><br>

        <label>Problème du téléphone:</label><br>
        <textarea name="probleme" rows="4" required></textarea><br><br>

        <label>Date de demande:</label><br>
        <input type="date" name="date_demande" required><br><br>

        <label>Type de réparation:</label><br>
        <select name="type_reparation">
            <option value="standard" selected>Standard</option>
            <option value="express">Express</option>
        </select><br><br>

        <button type="submit">Soumettre</button>
    </form>
</body>
</html>
