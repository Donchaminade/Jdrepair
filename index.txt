<?php
require_once '../includes/conf.php';

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM demande_reparation WHERE id_demande = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE demande_reparation SET nom_complet=?, numero=?, email=?, adresse=?, marque_telephone=?, probleme=?, type_reparation=? WHERE id_demande=?");
    $stmt->execute([
        $_POST['nom_complet'], $_POST['numero'], $_POST['email'], $_POST['adresse'],
        $_POST['marque_telephone'], $_POST['probleme'], $_POST['type_reparation'], $_POST['id_demande']
    ]);
    header("Location: index.php");
    exit;
}

// Traitement du traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['traiter'])) {
    $stmt = $pdo->prepare("INSERT INTO traitement (id_demande, commentaire, date_traitement) VALUES (?, ?, NOW())");
    $stmt->execute([$_POST['id_demande'], $_POST['commentaire']]);
    header("Location: index.php");
    exit;
}

// Récupération des données
$demandes = $pdo->query("SELECT d.*, t.id_traitement FROM demande_reparation d LEFT JOIN traitement t ON d.id_demande = t.id_demande ORDER BY date_demande DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Demandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #2c5364, #203a43, #0f2027);
            color: white;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .container {
            padding-top: 80px;
        }
        table {
            background: white;
            color: black;
        }
        .btn-custom {
            background-color: #17a2b8;
            color: white;
        }
        .modal-body label {
            font-weight: bold;
        }

                    .table-bordered th, .table-bordered td {
                border: 1px solid #dee2e6;
                vertical-align: middle;
            }

            .table-hover tbody tr:hover {
                background-color: #f1f1f1;
            }

            thead th {
                text-align: center;
                vertical-align: middle;
            }

    </style>
</head>
<body>
<div id="particles-js"></div>

<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-light"><i class="bi bi-house"></i> Accueil</a>
        <a href="ajouter.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Ajouter</a>
    </div>

    <h3 class="text-center mb-4">Liste des Demandes de Réparation</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Marque</th>
                <th>Problème</th>
                <th>Date</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($demandes as $demande): ?>
            <tr>
                <td><?= htmlspecialchars($demande['nom_complet']) ?></td>
                <td><?= htmlspecialchars($demande['numero']) ?></td>
                <td><?= htmlspecialchars($demande['email']) ?></td>
                <td><?= htmlspecialchars($demande['adresse']) ?></td>
                <td><?= htmlspecialchars($demande['marque_telephone']) ?></td>
                <td><?= htmlspecialchars($demande['probleme']) ?></td>
                <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                <td><?= htmlspecialchars($demande['type_reparation']) ?></td>
                <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalModifier<?= $demande['id_demande'] ?>"><i class="bi bi-pencil-square"></i></button>
                    
                    <a href="?delete=<?= $demande['id_demande'] ?>" onclick="return confirm('Confirmer la suppression ?')" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></a>
                    
                    <?php if ($demande['id_traitement']): ?>
                        <button class="btn btn-sm btn-secondary" disabled>En Cours</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTraiter<?= $demande['id_demande'] ?>">Traiter</button>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Modal Modifier -->
            <div class="modal fade" id="modalModifier<?= $demande['id_demande'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier la Demande</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                            <input type="hidden" name="update" value="1">
                            <div class="mb-2"><label>Nom complet</label><input class="form-control" name="nom_complet" value="<?= $demande['nom_complet'] ?>"></div>
                            <div class="mb-2"><label>Numéro</label><input class="form-control" name="numero" value="<?= $demande['numero'] ?>"></div>
                            <div class="mb-2"><label>Email</label><input class="form-control" name="email" value="<?= $demande['email'] ?>"></div>
                            <div class="mb-2"><label>Adresse</label><input class="form-control" name="adresse" value="<?= $demande['adresse'] ?>"></div>
                            <div class="mb-2"><label>Marque téléphone</label><input class="form-control" name="marque_telephone" value="<?= $demande['marque_telephone'] ?>"></div>
                            <div class="mb-2"><label>Problème</label><textarea class="form-control" name="probleme"><?= $demande['probleme'] ?></textarea></div>
                            <div class="mb-2"><label>Type</label><select class="form-control" name="type_reparation">
                                <option <?= $demande['type_reparation'] == 'standard' ? 'selected' : '' ?>>standard</option>
                                <option <?= $demande['type_reparation'] == 'urgent' ? 'selected' : '' ?>>urgent</option>
                            </select></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Traiter -->
            <div class="modal fade" id="modalTraiter<?= $demande['id_demande'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Traiter la Demande</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                            <input type="hidden" name="traiter" value="1">
                            <div class="mb-3">
                                <label for="commentaire">Commentaire</label>
                                <textarea name="commentaire" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit">Valider</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script>
particlesJS("particles-js", {
    particles: {
        number: { value: 50, density: { enable: true, value_area: 800 } },
        color: { value: "#ffffff" },
        shape: { type: "circle" },
        opacity: { value: 0.5 },
        size: { value: 3 },
        line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
        move: { enable: true, speed: 2 }
    },
    interactivity: {
        events: {
            onhover: { enable: true, mode: "repulse" }
        }
    },
    retina_detect: true
});
</script>
</body>
</html>
