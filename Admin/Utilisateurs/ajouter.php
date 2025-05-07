<?php
session_start();
require_once '../Admin/includes/conf.php'; // Connexion à la base de données

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom_complet = htmlspecialchars(trim($_POST['nom_complet']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mot_de_passe = htmlspecialchars(trim($_POST['mot_de_passe']));
    $role = htmlspecialchars(trim($_POST['role']));

    // Hachage du mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Insertion dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom_complet, $email, $hashed_password, $role]);

        // Retourner un message de succès en JSON
        echo json_encode(['success' => true, 'message' => 'Utilisateur ajouté avec succès!']);
    } catch (PDOException $e) {
        // Si une erreur survient, retourner un message d'erreur
        echo json_encode(['success' => false, 'message' => 'Erreur d\'insertion dans la base de données.']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <style>
        body {
            background-color: #222;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .form-container {
            width: 650px;
            padding: 50px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            animation: slideIn 0.8s ease-out;
        }

        .form-control {
            font-size: 1.1rem;
            padding: 12px;
            margin-bottom: 20px;
        }

        .btn {
            font-size: 1.2rem;
            padding: 12px;
            width: 100%;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .position-relative button {
            border: none;
            background: none;
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.5rem;
            color: white;
        }

        a {
            color: #aaa;
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }

        a:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="form-container">
    <h2 class="mb-4">Ajouter un Utilisateur</h2>
    <form id="addUserForm" method="POST">
        <div class="mb-4">
            <input type="text" class="form-control" id="nom_complet" name="nom_complet" placeholder="Nom complet" required>
        </div>
        <div class="mb-4">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="mb-4 position-relative">
            <input type="password" class="form-control" id="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="button" id="togglePassword">👁️</button>
        </div>
        <div class="mb-4">
            <select class="form-control" name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="technicien">Technicien</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Utilisateur</button>
    </form>
</div>

<script>
    // Chargement des particules
    particlesJS.load('particles-js', 'particles.json', function() {
        console.log('Particles.js chargé avec succès!');
    });

    // Afficher/Masquer le mot de passe
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.innerText = '🙈';
        } else {
            passwordField.type = 'password';
            this.innerText = '👁️';
        }
    });

    // Soumission du formulaire avec AJAX
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const nom_complet = document.getElementById('nom_complet').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;

        // Envoyer les données en AJAX
        fetch('ajouter.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nom_complet=${nom_complet}&email=${email}&mot_de_passe=${password}&role=${role}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: "Succès",
                    text: data.message,
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "index.php"; // Redirige vers la page des utilisateurs après succès
                });
            } else {
                Swal.fire({
                    title: "Erreur",
                    text: data.message,
                    icon: "error"
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: "Erreur",
                text: "Une erreur est survenue lors de la requête.",
                icon: "error"
            });
            console.error(error);
        });
    });
</script>

</body>
</html>
