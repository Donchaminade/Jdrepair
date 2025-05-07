<?php
require_once '/config.php';
// require_once '../includes/header.php';
// require_once '../includes/footer.php';

// Démarrer la session pour gérer les messages
session_start();

// Vérification de l'existence d'un message dans la session et affichage
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Suppression du message après affichage
}

$stmt = $pdo->query("SELECT * FROM Clients ORDER BY idcli DESC");
$clients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a href="../dash/index.html" class="btn btn-danger me-auto animate__animated animate__pulse animate__infinite">
            <i class="bi bi-house-door-fill fs-5"></i> Accueil
        </a>
        <!-- <a class="navbar-brand ms-auto" href="../clients/index.php">Gestion Clients</a> -->
    </div>
</nav>

<!-- Fond animé -->
<div id="particles-js"></div>

<div class="container-fluid mt-4">
    <!-- Barre de recherche et bouton "Ajouter" alignés -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text"><i class="bi bi-search fs-5"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
        </div>

        <button type="button" class="btn btn-success animate__animated animate__pulse animate__infinite" data-bs-toggle="modal" data-bs-target="#ajouterClientModal">
            <i class="bi bi-person-plus-fill fs-5"></i> Ajouter un Client
        </button>
    </div>

    <h2 class="text-center text-light">Liste des Clients</h2>

    <!-- Affichage du message si défini -->
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- TABLE RESPONSIVE -->
    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nom & Prenom</th>
                    <!-- <th>Prénom</th> -->
                    <!-- <th>Email</th> -->
                    <th>Téléphone</th>
                    <th>Date Reception</th>
                    <!-- <th>Adresse</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="clientTable">
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['nom_complet']) ?></td>
                        <td><?= htmlspecialchars($client['contact']) ?></td>
                        <td><?= htmlspecialchars($client['date_arrivee']) ?></td>
                        <td class="text-center">
                            <!-- Bouton Modifier -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modifierClientModal"
                                    onclick="remplirFormulaire(<?= htmlspecialchars(json_encode($client)) ?>)">
                                <i class="bi bi-pencil fs-6"> Modifier</i>
                            </button>

                            <a href="supprimer.php?id=<?= $client['id_client'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer ce client ?');">
                                <i class="bi bi-trash fs-6"> Supprimer</i>
                            </a>
                            <!-- Bouton Contacter -->
                            <button type="button" class="btn btn-info btn-sm" onclick="contactClient('<?= htmlspecialchars($client['téléphone']) ?>')">
                                <i class="bi bi-whatsapp fs-6"> Contacter</i> 
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
    
<!-- Modal pour Ajouter un Client -->
<div class="modal fade" id="ajouterClientModal" tabindex="-1" aria-labelledby="ajouterClientModalLabel" aria-hidden="true">
    <style>
                    /* --- MODAL STYLÉ --- */
        .modal-content {
            background: linear-gradient(135deg, #2C3E50, #4CA1AF);
            color: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- TITRE DU MODAL --- */
        .modal-header {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-title {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- FORMULAIRE --- */
        .form-label {
            font-weight: bold;
            color: #fff;
        }

        .form-control {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #4CA1AF;
            box-shadow: 0 0 10px rgba(76, 161, 175, 0.5);
            background: rgba(255, 255, 255, 0.3);
        }

        /* --- BOUTONS --- */
        .btn-primary {
            background: #27ae60;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #2ecc71;
            transform: scale(1.05);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            transition: 0.3s;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }


    </style>


    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajouterClientModalLabel">Ajouter un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="ajouter.php" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom & Prenom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom">
                    </div> -->
                    <!-- <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div> -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label">contact (whatsapp)</label>
                        <input type="text" class="form-control" id="telephone" name="téléphone">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse"></textarea>
                    </div> -->
                    <div class="mb-3">
                        <label for="daterec" class="form-label">Email</label>
                        <input type="datetime" class="form-control" id="daterec" name="daterec" required>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Client -->
<div class="modal fade" id="modifierClientModal" tabindex="-1" aria-labelledby="modifierClientModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifierClientModalLabel">Modifier Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="modifier.php" method="POST">
                    <input type="hidden" id="idcli" name="id_client">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom & Prenom</label>
                        <input type="text" class="form-control" id="nom_modif" name="nom" required>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom_modif" name="prenom">
                    </div> -->
                    <div class="mb-3">
                        <label for="daterec" class="form-label">Date Reception</label>
                        <input type="datetime" class="form-control" id="daterec_modif" name="daterec" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">contact (whatsapp)</label>
                        <input type="text" class="form-control" id="telephone_modif" name="téléphone">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse_modif" name="adresse"></textarea>
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function remplirFormulaire(client) {
    document.getElementById("idcli").value = client.id_client;
    document.getElementById("nom_modif").value = client.nom;
    // document.getElementById("prenom_modif").value = client.prenom;
    document.getElementById("daterec_modif").value = client.email;
    // document.getElementById("telephone_modif").value = client.téléphone;
    // document.getElementById("adresse_modif").value = client.adresse;
}

</script>

<?php require_once '../includes/footer.php'; ?>

<!-- Styles -->
<style>
    /* Fond animé */
    #particles-js {
        position: fixed;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        z-index: -1;
        background: linear-gradient(45deg, #110E0EFF, #1B0E02FF, #2C2A16FF, #140374FF, #150070FF, #0C1D47FF, #311C3FFF);
        background-size: 400% 400%;
        animation: gradientBG 10s ease infinite;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Table stylée */
    .table-responsive {
        background: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
        color: white;
    }

    .table-dark {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Animation des icônes */
    .btn i {
        transition: transform 0.3s ease-in-out;
    }

    .btn:hover i {
        transform: scale(1.3);
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {"value": 100, "density": {"enable": true, "value_area": 800}},
            "color": {"value": ["#ff0000", "#ff7300", "#ffea00", "#47ff00", "#00ffee", "#0048ff", "#9b00ff"]},
            "shape": {"type": "circle"},
            "opacity": {"value": 0.7, "random": true},
            "size": {"value": 5, "random": true},
            "line_linked": {"enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.5, "width": 1},
            "move": {"enable": true, "speed": 2, "direction": "none", "out_mode": "out"}
        }
    });

    // Recherche dynamique dans le tableau
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#clientTable tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    // Fonction pour ouvrir WhatsApp avec un message prérempli
    function contactClient(phoneNumber) {
        const message = encodeURIComponent("Bonjour, c'est l'etablissement Ami du monde. Nous vous ecrivons Concernant votre demande de repartion de votre telephone");
        const url = `https://wa.me/${phoneNumber}?text=${message}`;
        window.open(url, '_blank');
    }
</script>
