<?php
session_start();
require_once '../includes/conf.php';

// Redirection si non connecté
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location: ../../login.php");
    exit();
}

// Récupération de l'utilisateur connecté depuis la base
$utilisateur = null;
try {
    $stmt = $pdo->prepare("SELECT id_utilisateur, nom_complet, role FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$_SESSION["utilisateur_id"]]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        session_destroy();
        header("Location: ../../login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Récupérer la liste des utilisateurs
$liste = [];
$message = "";
try {
    $stmt = $pdo->query("SELECT id_utilisateur, nom_complet, email, role FROM utilisateurs");
    $liste = $stmt->fetchAll();
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Utilisateurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #0f2027;
            color: #fff;
        }
        .card {
            background-color: #fff;
            color: #000;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body>
<div id="particles-js"></div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des utilisateurs</h2>
        <div>
            <a href="../../index.php" class="btn btn-outline-light">Accueil</a>
            <button class="btn btn-success" onclick="confirmerAjout()">Ajouter</button>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-lg rounded-4">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <?php if ($utilisateur["role"] === "admin"): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($liste as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u["nom_complet"]) ?></td>
                        <td><?= htmlspecialchars($u["email"]) ?></td>
                        <td><?= htmlspecialchars($u["role"]) ?></td>
                        <?php if ($utilisateur["role"] === "admin"): ?>
                            <td>
                                <a href="modifier.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                                <a href="supprimer.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL DE CONFIRMATION MOT DE PASSE -->
<div class="modal fade" id="modalAdmin" tabindex="-1" aria-labelledby="modalAdminLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="verifier_admin.php" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p>Veuillez entrer votre mot de passe pour confirmer :</p>
        <input type="password" name="mot_de_passe_admin" class="form-control" required>
        <input type="hidden" name="action" value="ajouter">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Valider</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/particles.min.js"></script>
<script>
particlesJS.load('particles-js', '../../assets/js/particles.json');

function confirmerAjout() {
    const role = "<?= $utilisateur['role'] ?>";
    if (role === "admin") {
        const modal = new bootstrap.Modal(document.getElementById('modalAdmin'));
        modal.show();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Action non autorisée',
            text: 'Seuls les administrateurs peuvent ajouter un utilisateur.',
            confirmButtonText: 'OK'
        });
    }
}
</script>
</body>
</html>
