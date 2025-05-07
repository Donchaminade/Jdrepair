<?php
 // Assure-toi que ce chemin est correct
require_once '../includes/conf.php';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom_complet = $_POST['nom_complet'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $marque_telephone = $_POST['marque_telephone'];
    $probleme = $_POST['probleme'];
    $type_reparation = $_POST['type_reparation'];

    try {
        $stmt = $pdo->prepare("INSERT INTO demande_reparation (nom_complet, numero, email, adresse, marque_telephone, probleme, type_reparation, date_demande) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nom_complet, $numero, $email, $adresse, $marque_telephone, $probleme, $type_reparation]);

        echo '<script>alert("Demande ajoutée avec succès."); window.location.href="index.php";</script>';
    } catch (PDOException $e) {
        echo '<script>alert("Erreur lors de l\'ajout de la demande : ' . $e->getMessage() . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une demande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #000;
            color: #fff;
            position: relative;
            z-index: 1;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .form-container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100 form-container">
        <!-- En-tête avec boutons -->
        <div class="w-100 d-flex justify-content-between align-items-center mb-4">
            <a href="../dash/tbord.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left-circle"></i> Accueil
            </a>
            <h2 class="text-center flex-grow-1">Ajouter une demande</h2>
            <a href="../Demandes/index.php" class="btn btn-outline-light">
                <i class="bi bi-list"></i> Liste des demandes
            </a>
        </div>

        <!-- Formulaire -->
        <div class="w-100" style="max-width: 600px;">
            <form method="POST" action="" class="bg-dark p-4 rounded shadow">
                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="nom_complet" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Numéro</label>
                    <input type="tel" name="numero" class="form-control" 
                        pattern="^\+[0-9]{8,15}$" 
                        inputmode="tel" 
                        placeholder="+22890123456" 
                        required 
                        title="Le numéro doit commencer par + et contenir entre 8 et 15 chiffres.">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Marque du téléphone</label>
                    <input type="text" name="marque_telephone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Problème</label>
                    <textarea name="probleme" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type de réparation</label>
                    <select name="type_reparation" class="form-select" required>
                        <option value="standard">Standard</option>
                        <option value="express">Express</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Soumettre</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle"
                },
                "opacity": {
                    "value": 0.5
                },
                "size": {
                    "value": 3
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6
                }
            },
            "interactivity": {
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    }
                }
            },
            "retina_detect": true
        });
    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#repairForm').on('submit', function(event) {
            event.preventDefault(); // Empêche le formulaire de se soumettre normalement

            var formData = $(this).serialize(); // Récupère toutes les données du formulaire

            $.ajax({
                url: '', // L'URL de ton fichier PHP ou API
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Si la réponse est un succès
                    if (response === 'success') {
                        $('#responseMessage').html('<div class="alert alert-success">Demande ajoutée avec succès!</div>');
                        $('#repairForm')[0].reset(); // Réinitialise le formulaire
                    } else {
                        $('#responseMessage').html('<div class="alert alert-danger">Erreur lors de l\'ajout de la demande.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#responseMessage').html('<div class="alert alert-danger">Erreur serveur: ' + error + '</div>');
                }
            });
        });
    });
</script>

</body>
</html>
