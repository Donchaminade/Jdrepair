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

// Récupérer les demandes de réparation
$sql = "SELECT * FROM demande_reparation ORDER BY date_demande DESC";
$result = $conn->query($sql);

// Récupérer les demandes déjà traitées
$traitements = [];
$res_traitements = $conn->query("SELECT id_demande FROM traitement");
if ($res_traitements) {
    while ($row_traitement = $res_traitements->fetch_assoc()) {
        $traitements[] = $row_traitement['id_demande'];
    }
}

// Traitement du formulaire modal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_demande'])) {
    $id_demande = $_POST['id_demande'];
    $date_reception = $_POST['date_reception'];
    $montant_total = $_POST['montant_total'];
    $montant_paye = $_POST['montant_paye'];

    // Calculer le reste à payer directement dans le PHP
    $reste_a_payer = $montant_total - $montant_paye;

    // Insertion dans la table traitement
    $sql_traitement = "INSERT INTO traitement (id_demande, date_reception, montant_total, montant_paye, type_reparation)
                       VALUES (?, ?, ?, ?, 'standard')";

    $stmt = $conn->prepare($sql_traitement);
    if ($stmt) {
        $stmt->bind_param("isdd", $id_demande, $date_reception, $montant_total, $montant_paye);
        if ($stmt->execute()) {
            echo '<script>alert("Traitement ajouté avec succès."); window.location.reload();</script>';
        } else {
            echo '<script>alert("Erreur lors de l\'ajout du traitement.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Erreur de requête préparée.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des demandes de réparation</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-modal {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
        }
        .btn-modal:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Liste des demandes de réparation</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>Numéro</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Marque du téléphone</th>
                    <th>Problème</th>
                    <th>Date de demande</th>
                    <th>Type de réparation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['nom_complet'] ?></td>
                        <td><?= $row['numero'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['adresse'] ?></td>
                        <td><?= $row['marque_telephone'] ?></td>
                        <td><?= $row['probleme'] ?></td>
                        <td><?= $row['date_demande'] ?></td>
                        <td><?= $row['type_reparation'] ?></td>
                        <td>
                            <?php if (in_array($row['id_demande'], $traitements)): ?>
                                <button class="btn-modal" disabled>Déjà traité</button>
                            <?php else: ?>
                                <button class="btn-modal" onclick="openModal(<?= $row['id_demande'] ?>)">Traitement</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune demande de réparation enregistrée.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Formulaire de traitement</h3>
            <form method="POST" action="">
                <input type="hidden" name="id_demande" id="id_demande">
                <label>Date de réception :</label><br>
                <input type="date" name="date_reception" required><br><br>

                <label>Montant total :</label><br>
                <input type="number" name="montant_total" required><br><br>

                <label>Montant payé :</label><br>
                <input type="number" name="montant_paye" required><br><br>

                <button type="submit">Soumettre</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id_demande) {
            document.getElementById("id_demande").value = id_demande;
            document.getElementById("modal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>
</body>
</html>
