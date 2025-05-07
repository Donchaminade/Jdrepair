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
    $IdDmd = isset($_POST['IdDmd']) ? $_POST['IdDmd'] : null;
    $NomRecept = isset($_POST['NomRecept']) ? $_POST['NomRecept'] : null;
    $Telephone = isset($_POST['Telephone']) ? $_POST['Telephone'] : null;

    // Valider les données
    if ($IdDmd && $NomRecept && $Telephone) {
        // Démarrer une transaction pour garantir la cohérence
        $conn->begin_transaction();

        try {
            // Insérer dans la table retrait
            $sqlInsert = "INSERT INTO retrait (DateRetrait, NomRecept, Telephone, IdDmd) VALUES (NOW(), ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ssi", $NomRecept, $Telephone, $IdDmd);

            if (!$stmtInsert->execute()) {
                throw new Exception("Erreur lors de l'insertion dans la table retrait : " . $stmtInsert->error);
            }

            // Mettre à jour l'état dans la table demandeur
            $sqlUpdate = "UPDATE demandeur SET Etat = 1 WHERE IdDmd = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $IdDmd);

            if (!$stmtUpdate->execute()) {
                throw new Exception("Erreur lors de la mise à jour de l'état dans la table demandeur : " . $stmtUpdate->error);
            }

            // Valider la transaction si tout est réussi
            $conn->commit();

            // Préparer le message de succès
            $showModal = true;
            $message = "Retrait effectué avec succès.";
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $conn->rollback();
            $showModal = true;
            $message = "Erreur : " . $e->getMessage();
        } finally {
            // Fermer les requêtes préparées
            if (isset($stmtInsert)) $stmtInsert->close();
            if (isset($stmtUpdate)) $stmtUpdate->close();
        }
    } else {
        $showModal = true;
        $message = "Veuillez remplir tous les champs.";
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
    <title>Retrait</title>
    <!-- Bootstrap CSS -->
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

        <!-- Scripts pour afficher la modal et rediriger -->
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
