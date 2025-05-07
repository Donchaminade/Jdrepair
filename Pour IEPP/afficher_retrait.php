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

// Requête SQL pour récupérer les retraits et relier les demandes
$sql = "SELECT R.IdRt, R.DateRetrait, R.NomRecept, R.Telephone, R.IdDmd, D.NomDmd
        FROM Retrait R
        JOIN demandeur D ON R.IdDmd = D.IdDmd
        WHERE D.NomDmd LIKE ? OR D.NumeroTel LIKE ?
        ORDER BY R.DateRetrait ASC";

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
    <title>Liste des Retraits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
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

        <a id="homeButton2" href="print_retrait.php" class="button no-print m-auto" target="_blank">
            <i class="fas fa-print " ></i> PDF
        </a>
      
    </header>
    <br>
        <br>
        <br>
    <div class="container">
        <h1>Liste des Retraits</h1>
        <!-- Barre de recherche -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Rechercher par nom ou numéro" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <!-- Tableau des retraits -->
        <table>
            <thead>
                <tr>
                    <th>Nom du Réceptionnaire</th>
                    <th>Numéro de Téléphone</th>
                    <th>Nom du Demandeur</th>
                    <th>Date de Retrait</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Affichage des retraits
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['NomRecept']) . "</td>
                                <td>" . htmlspecialchars($row['Telephone']) . "</td>
                                <td>" . htmlspecialchars($row['NomDmd']) . "</td>
                                <td>" . htmlspecialchars($row['DateRetrait']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-data'>Aucun retrait trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Fermer la connexion

$stmt->close();
$conn->close();
?>
