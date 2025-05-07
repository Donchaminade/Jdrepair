<?php
// Démarrage de la session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['IdAdmin'])) {
    header("Location: Log.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "gov"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Initialiser une variable pour afficher la modal
$showModal = false;
$message = "";

// Récupérer les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomDmd = htmlspecialchars($_POST['NomDmd']);
    $numeroTel = htmlspecialchars($_POST['NumeroTel']);
    $concerneAnnee = (int) $_POST['ConcerneAnnee'];
    $typeDmd = isset($_POST['TypeDmd']) ? implode(", ", $_POST['TypeDmd']) : '';
    $dateRdv = $_POST['DateRdv'];
    $idAdmin = (int) $_SESSION['IdAdmin'];

    // Préparer la requête SQL d'insertion
    $sql = "INSERT INTO demandeur (DateHeureDmd, NomDmd, NumeroTel, ConcerneAnnee, TypeDmd, DateRdv, IdAdmin)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Lier les paramètres
        $stmt->bind_param("ssissi", $nomDmd, $numeroTel, $concerneAnnee, $typeDmd, $dateRdv, $idAdmin);

        // Exécuter la requête
        if ($stmt->execute()) {
            $showModal = true;
            $message = "Demande (Dépôt) enregistrée avec succès.";
        } else {
            $showModal = true;
            $message = "Erreur lors de l'enregistrement de la demande : " . $stmt->error;
        }

        // Fermer la requête
        $stmt->close();
    } else {
        $showModal = true;
        $message = "Erreur lors de la préparation de la requête : " . $conn->error;
    }
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement de la Demande</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
    </style>
</head>
<br>
<br>
<body> <center><img src="D.PNG" width="900"></center>
    
    <?php if ($showModal): ?>
        <!-- Modal Bootstrap -->
        <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= htmlspecialchars($message); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="redirectButton">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script pour afficher la modal et rediriger -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Afficher la modal automatiquement
            $(document).ready(function () {
                $('#messageModal').modal('show');

                // Redirection après fermeture
                $('#redirectButton').on('click', function () {
                    window.location.href = "afficher_demandes.php"; // Remplacez par la page de redirection
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>
