<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['téléphone']);
    $adresse = trim($_POST['adresse']);

    // Validation des champs
    
    $errors = [];
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est requis et doit être valide.";
    }
    if (empty($telephone)) {
        $errors[] = "Le téléphone est requis.";
    }
    if (empty($errors)) {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO Clients (nom, prenom, email, téléphone, adresse) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $telephone, $adresse]);

        // Rediriger vers la page d'index avec un message de succès
        header("Location: index.php?message=Client ajouté avec succès !");
        exit();
    } else {
        // Si des erreurs existent, afficher le message d'erreur
        $errorMessages = implode('<br>', $errors);
        echo "<div class='alert alert-danger'>$errorMessages</div>";
    }
}
?>

<!-- Formulaire d'ajout dans une modale -->
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
        <div class="modal-content animate__animated animate__zoomIn">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="ajouterClientModalLabel">Ajouter un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" placeholder="Nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="prenom" placeholder="Prénom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="téléphone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="téléphone" placeholder="Téléphone">
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" name="adresse" placeholder="Adresse"></textarea>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Le code pour afficher la modale sur le clic du bouton -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>

<script>
    // Si l'URL contient "message", afficher la modale
    <?php if (isset($_GET['message'])): ?>
        var myModal = new bootstrap.Modal(document.getElementById('ajouterClientModal'));
        myModal.show();
    <?php endif; ?>
</script>
