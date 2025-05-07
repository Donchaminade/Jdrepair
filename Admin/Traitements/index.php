<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'reparationbd';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Traitement du formulaire d'ajout de statut
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajouter_statut'])) {
    $id_demande = $_POST['id_demande'];
    $id_traitement = $_POST['id_traitement'];
    $date_reparation = $_POST['date_reparation'];
    $statut = $_POST['statut'];
    $montant_total = $_POST['montant_total'];
    $montant_paye = $_POST['montant_paye'];
    $reste_a_payer = $montant_total - $montant_paye;

    $sql = "INSERT INTO reparation (id_demande, id_traitement, date_reparation, statut, montant_total, montant_paye, reste_a_payer)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissddd", $id_demande, $id_traitement, $date_reparation, $statut, $montant_total, $montant_paye, $reste_a_payer);

    if ($stmt->execute()) {
        echo "<script>alert('Statut ajouté avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout du statut');</script>";
    }
    $stmt->close();
}

// Récupération des traitements
$sql = "
    SELECT 
        t.id_traitement,
        t.id_demande,
        t.date_reception,
        t.montant_total,
        t.montant_paye,
        (t.montant_total - t.montant_paye) AS reste_a_payer,
        t.type_reparation,
        d.nom_complet,
        d.numero,
        d.marque_telephone,
        d.probleme
    FROM traitement t
    JOIN demande_reparation d ON t.id_demande = d.id_demande
    ORDER BY t.date_reception DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des traitements</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #444; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #eee; }
        .btn-modal {
            padding: 5px 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 99;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 20px;
            width: 50%;
            border-radius: 6px;
        }
        .close { float: right; cursor: pointer; font-size: 24px; }
    </style>
</head>
<body>

<h2>Liste des traitements</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Nom du client</th>
                <th>Numéro</th>
                <th>Marque</th>
                <th>Problème</th>
                <th>Date réception</th>
                <th>Montant total</th>
                <th>Montant payé</th>
                <th>Reste</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nom_complet'] ?></td>
                    <td><?= $row['numero'] ?></td>
                    <td><?= $row['marque_telephone'] ?></td>
                    <td><?= $row['probleme'] ?></td>
                    <td><?= $row['date_reception'] ?></td>
                    <td><?= number_format($row['montant_total'], 2) ?> €</td>
                    <td><?= number_format($row['montant_paye'], 2) ?> €</td>
                    <td><?= number_format($row['reste_a_payer'], 2) ?> €</td>
                    <td>
                        <button class="btn-modal"
                            onclick="openModal(
                                <?= $row['id_demande'] ?>,
                                <?= $row['id_traitement'] ?>,
                                <?= $row['montant_total'] ?>,
                                <?= $row['montant_paye'] ?>
                            )">Ajouter statut</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun traitement trouvé.</p>
<?php endif; ?>

<?php $conn->close(); ?>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Ajouter un statut de réparation</h3>
        <form method="POST">
            <input type="hidden" name="id_demande" id="id_demande">
            <input type="hidden" name="id_traitement" id="id_traitement">
            <input type="hidden" name="montant_total" id="montant_total">
            <input type="hidden" name="montant_paye" id="montant_paye">

            <label>Date de réparation :</label><br>
            <input type="date" name="date_reparation" required><br><br>

            <label>Statut :</label><br>
            <select name="statut" required>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
                <option value="échec">Échec</option>
            </select><br><br>

            <button type="submit" name="ajouter_statut">Soumettre</button>
        </form>
    </div>
</div>

<script>
function openModal(id_demande, id_traitement, montant_total, montant_paye) {
    document.getElementById('id_demande').value = id_demande;
    document.getElementById('id_traitement').value = id_traitement;
    document.getElementById('montant_total').value = montant_total;
    document.getElementById('montant_paye').value = montant_paye;
    document.getElementById('modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}
</script>

</body>
</html>
