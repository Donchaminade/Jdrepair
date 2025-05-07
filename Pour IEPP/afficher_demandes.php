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
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "gov"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Initialisation des variables
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Requête SQL pour récupérer les demandes
$sql = "SELECT * FROM demandeur WHERE NomDmd LIKE ? OR NumeroTel LIKE ? ORDER BY DateHeureDmd ASC";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Liste des Demandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            position: relative; /* Permet d'utiliser ::before */
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 2; /* Assure que le contenu reste au-dessus */
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
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
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
            z-index: 2; /* Place le tableau au-dessus du filigrane */
            position: relative;
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
            margin-top: 20px;
        }

        .btn-retirer {
            padding: 5px 10px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-retirer:hover {
            background-color: #c0392b;
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


.btn-retirer:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}






    /* Fenêtre modale */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); /* Couleur semi-transparente */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        overflow: hidden; /* Empêche le débordement */
    }

    /* Contenu de la modale */
    .modal-content {
        background: white; /* Fond blanc */
        padding: 25px; /* Espacement interne */
        border-radius: 10px; /* Coins arrondis */
        width: 420px; /* Largeur fixe */
        max-width: 90%; /* Réduction sur écrans étroits */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Ombre pour donner du relief */
        position: relative;
        animation: fadeIn 0.3s ease-out; /* Animation d'apparition */
    }

    /* Bouton de fermeture */
    .close-button {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 20px;
        color: #888; /* Couleur adoucie */
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-button:hover {
        color: #e74c3c; /* Rouge au survol */
    }

    /* Titre de la modale */
    .modal-content h2 {
        font-size: 22px;
        font-weight: bold;
        color: #333;
        text-align: center; /* Centré */
        margin-top: 0; /* Suppression du margin-top */
    }

    /* Sections de formulaire */
    .modal-content div {
        margin-bottom: 20px; /* Espacement entre champs */
    }

    .modal-content label {
        display: block;
        font-size: 14px;
        font-weight: bold;
        color: #444;
        margin-bottom: 8px;
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"] {
        width: 100%; /* Champs de pleine largeur */
        padding: 12px; /* Espacement interne */
        border: 1px solid #ddd; /* Bordure légère */
        border-radius: 5px; /* Coins légèrement arrondis */
        font-size: 16px; /* Taille de police */
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* Focus sur les champs */
    .modal-content input[type="text"]:focus,
    .modal-content input[type="number"]:focus {
        border-color: #007bff; /* Couleur bleue */
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3); /* Lumière bleue */
        outline: none;
    }

    /* Bouton principal */
    .modal-content button {
        width: 100%;
        padding: 12px 0; /* Plus haut et centré */
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
    }

    .modal-content button:hover {
        background-color: #541A1C; /* Bleu plus foncé */
        transform: translateY(-2px); /* Légère montée */
    }

    .modal-content button:active {
        transform: translateY(0); /* Retour au clic */
    }

    /* Animation d'apparition */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

</style>

</style>

    </style>
</head>

<body>
    <!-- En-tête fixe avec les boutons -->
    <header>
        <a id="homeButton" href="index.php" class="button no-print">
            <i class="fas fa-home"></i> Home
        </a>

        <a id="homeButton2" href="print_dmd.php" class="button no-print m-auto" target="_blank">
            <i class="fas fa-print " ></i> PDF
        </a>
      
    </header>
    <br>
        <br>
        <br>
    <div class="container">
        <h1>Liste des Demandes</h1>
        <!-- Barre de recherche -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Rechercher par nom ou numéro" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <!-- Tableau des demandes -->
       
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Numéro de Téléphone</th>
                    <th>Année Concernée</th>
                    <th>Type de Demande</th>
                    <th>Date du Rendez-vous</th>
                    <th>Date & Heure de la Demande</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($result->num_rows > 0) {
                    // Affichage des demandes
                   while ($row = $result->fetch_assoc()) {
                    $isDisabled = $row['Etat'] == 1 ? 'disabled' : '';
                    echo "<tr>
                            <td>" . htmlspecialchars($row['NomDmd']) . "</td>
                            <td>" . htmlspecialchars($row['NumeroTel']) . "</td>
                            <td>" . htmlspecialchars($row['ConcerneAnnee']) . "</td>
                            <td>" . htmlspecialchars($row['TypeDmd']) . "</td>
                            <td>" . htmlspecialchars($row['DateRdv']) . "</td>
                            <td>" . htmlspecialchars($row['DateHeureDmd']) . "</td>
                            <td>
                                <form method='post' action='retirer_demande.php' style='display:inline;'>
                                    <input type='hidden' name='IdDmd' value='" . htmlspecialchars($row['IdDmd']) . "'>
                                    <button type='submit' class='btn-retirer' $isDisabled>Retirer</button>
                                </form>
                            </td>
                          </tr>";
}

                } else {
                    echo "<tr><td colspan='7' class='no-data'>Aucune demande trouvée</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Fenêtre modale -->
<div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Confirmation de Retrait</h2>
        <form method="post" action="inserer_retrait.php">
            <input type="hidden" name="IdDmd" id="IdDmd">
            <div>
                <label for="NomRecept">Nom du récepteur :</label>
                <input type="text" name="NomRecept" id="NomRecept" required>
            </div>
            <div>
                <label for="Telephone">Téléphone :</label>
                <input type="text" name="Telephone" id="Telephone" required>
            </div>
            <button type="submit">Confirmer</button>
        </form>
    </div>
</div>
<script>
   // Gestion de l'ouverture/fermeture du modal
document.querySelectorAll('.btn-retirer').forEach(button => {
    button.addEventListener('click', function (event) {
        if (this.disabled) return; // Ignorer si le bouton est désactivé

        event.preventDefault(); // Empêche la soumission du formulaire par défaut

        const IdDmd = this.parentElement.querySelector('input[name="IdDmd"]').value;

        // Ouvrir le modal
        const modal = document.getElementById('modal');
        modal.style.display = 'flex';

        // Remplir l'IdDmd dans le champ caché du modal
        document.getElementById('IdDmd').value = IdDmd;
    });
});

// Fermer le modal
document.querySelector('.close-button').addEventListener('click', function () {
    document.getElementById('modal').style.display = 'none';
});

// Fermer le modal en cliquant en dehors du contenu
window.addEventListener('click', function (event) {
    if (event.target.classList.contains('modal')) {
        document.getElementById('modal').style.display = 'none';
    }
});

</script>


</body>

</html>

<?php
// Fermer la connexion
$stmt->close();
$conn->close();
?>


























