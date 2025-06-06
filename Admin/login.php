<?php
session_start();
require_once '../Admin/includes/conf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['mot_de_passe']);

    if (empty($email) || empty($password)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                header("Location: ../Admin/Utilisateurs/index.php");
                exit;
            } else {
                $erreur = "Identifiants invalides.";
            }
        } catch (PDOException $e) {
            $erreur = "Erreur serveur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .login-container {
            width: 680px;
            padding: 50px;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .form-control {
            font-size: 1.2rem;
            padding: 12px;
        }

        .btn {
            font-size: 1.2rem;
            padding: 12px;
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

<div class="login-container">
    <h2 class="mb-4">Connexion</h2>
    <form method="POST" action="">
        <div class="mb-4">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="mb-4 position-relative">
            <input type="password" class="form-control" name="mot_de_passe" id="password" placeholder="Mot de passe" required>
            <button type="button" id="togglePassword">👁️</button>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        <a href="../Admin/Utilisateurs/resetpass.php">Mot de passe oublié ?</a>
    </form>
</div>

<?php if (isset($erreur)): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '<?= $erreur ?>'
    });
</script>
<?php endif; ?>

<script>
    // Configuration inline pour particles.js
    particlesJS("particles-js", {
        "particles": {
            "number": { "value": 60 },
            "color": { "value": "#ffffff" },
            "shape": { "type": "circle" },
            "opacity": { "value": 0.5 },
            "size": { "value": 3 },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 4,
                "direction": "none",
                "out_mode": "bounce"
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": { "enable": true, "mode": "repulse" },
                "onclick": { "enable": true, "mode": "push" }
            },
            "modes": {
                "repulse": { "distance": 100 },
                "push": { "particles_nb": 4 }
            }
        },
        "retina_detect": true
    });

    // Afficher/Masquer mot de passe
    document.getElementById('togglePassword').addEventListener('click', function () {
        const field = document.getElementById('password');
        field.type = (field.type === 'password') ? 'text' : 'password';
        this.textContent = (field.type === 'password') ? '👁️' : '🙈';
    });
</script>

</body>
</html>
