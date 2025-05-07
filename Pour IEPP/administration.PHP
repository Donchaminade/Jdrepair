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

// Initialisation des variables
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Requête SQL pour récupérer les utilisateurs de la table administration
$sql = "SELECT IdAdmin, NomComplet, Poste, Email FROM administration
        WHERE NomComplet LIKE ? OR Email LIKE ?
        ORDER BY NomComplet ASC";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }


        /* Filigrane */
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('Ar.png'); /* Remplacez par le chemin vers votre image */
            background-repeat: no-repeat;
            background-position: center;
            background-size: 15%; /* Ajustez la taille */
            opacity: 0.2; /* Transparence du filigrane */
            z-index: 1; /* Derrière le contenu */
            pointer-events: none; /* Empêche toute interaction */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        .no-data {
            text-align: center;
            color: #888;
        }

        .add-button {
            display: block;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            width: 150px;
        }

        .add-button:hover {
            background-color: #218838;
        }

        /* Styles pour le modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-close:hover {
            color: red;
        }

        .modal-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle i {
            position: absolute;
            right: 10px;
            top: 12px;
            cursor: pointer;
        }

        .modal-form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-form button:hover {
            background-color: #0056b3;
        }


                .delete-button {
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-button:hover {
            background-color: #c82333;
        }





        
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
        }

        /* Style des boutons de l'en-tête */
        header .button {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            color: #fff;
            text-decoration: none;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        header .button i {
            margin-right: 8px;
        }

        header .button:hover {
            transform: scale(1.05);
        }

        #homeButton {
            background-color: #541A1C;
        }
        #homeButton:hover {
            background-color: #541A1C;
        }
    </style>
</head>

<body>
     <!-- En-tête fixe avec les boutons -->
     <header>
        <a id="homeButton" href="index.php" class="button no-print">
            <i class="fas fa-home"></i> Home
        </a>
      
    </header>
    <br>
    <br>
    <br>
    <div class="container">
        <h1>Liste des Utilisateurs</h1>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Rechercher par nom ou email">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <!-- Bouton ajouter un utilisateur -->
        <button class="add-button" id="addUserButton">Ajouter un utilisateur</button>

        <!-- Tableau des utilisateurs -->
        <table>
    <thead>
        <tr>
            <td>Nom Complet</td>
            <td>Poste</td>
            <td>Email</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['NomComplet']) . "</td>
                        <td>" . htmlspecialchars($row['Poste']) . "</td>
                        <td>" . htmlspecialchars($row['Email']) . "</td>
                        <td>
                            <form method='post' action='delete_user.php' style='display: inline;'>
                                <input type='hidden' name='IdAdmin' value='" . $row['IdAdmin'] . "'>
                                <button type='submit' class='delete-button'>Supprimer</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='no-data'>Aucun utilisateur trouvé</td></tr>";
        }
        ?>
    </tbody>
</table>

    </div>

    <!-- Modal -->
    <div class="modal" id="addUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter un utilisateur</h2>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <form class="modal-form" method="post" action="add_user.php">
                <input type="text" name="NomComplet" placeholder="Nom Complet" required>
                <input type="text" name="Poste" placeholder="Poste" required>
                <input type="email" name="Email" placeholder="Email" required>
                <div class="password-toggle">
                    <input type="password" name="MotPasse" id="passwordField" placeholder="Mot de passe" required>
                    <i id="togglePassword" class="fas fa-eye"></i>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>

    <script>
        const addUserButton = document.getElementById('addUserButton');
        const addUserModal = document.getElementById('addUserModal');
        const closeModal = document.getElementById('closeModal');
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('passwordField');

        addUserButton.addEventListener('click', () => {
            addUserModal.style.display = 'flex';
        });

        closeModal.addEventListener('click', () => {
            addUserModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === addUserModal) {
                addUserModal.style.display = 'none';
            }
        });

        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>

<?php
// Fermer la connexion
$stmt->close();
$conn->close();
?>
