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
$username = "root";
$password = "";
$dbname = "gov";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Initialiser les variables pour afficher la modal
$showModal = false;
$message = "";

// Récupérer les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NomComplet = isset($_POST['NomComplet']) ? trim($_POST['NomComplet']) : null;
    $Poste = isset($_POST['Poste']) ? trim($_POST['Poste']) : null;
    $Email = isset($_POST['Email']) ? trim($_POST['Email']) : null;
    $MotPasse = isset($_POST['MotPasse']) ? trim($_POST['MotPasse']) : null;

    // Validation des données
    if ($NomComplet && $Poste && $Email && $MotPasse) {
        // Insérer les données dans la base de données
        $sqlInsert = "INSERT INTO administration (NomComplet, Poste, Email, MotPasse) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);

        if ($stmt) {
            $stmt->bind_param("ssss", $NomComplet, $Poste, $Email, $MotPasse);

            if ($stmt->execute()) {
                $showModal = true;
                $message = "Utilisateur ajouté avec succès.";
            } else {
                $showModal = true;
                $message = "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error;
            }

            $stmt->close();
        } else {
            $showModal = true;
            $message = "Erreur de préparation de la requête.";
        }
    } else {
        $showModal = true;
        $message = "Tous les champs doivent être remplis.";
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
    <title>Ajouter Utilisateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   
    <br>
<br>
    <center><img src="D.PNG" width="900"></center>

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

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#messageModal').modal('show');
                $('#redirectButton').on('click', function () {
                    window.location.href = "administration.PHP"; // Modifier si nécessaire
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>

