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
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer les retraits et relier les demandes
$sql = "SELECT R.IdRt, R.DateRetrait, R.NomRecept, R.Telephone, R.IdDmd, D.NomDmd
        FROM Retrait R
        JOIN demandeur D ON R.IdDmd = D.IdDmd
        ORDER BY R.DateRetrait ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression des Retraits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('Ar.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 50%;
            opacity: 0.1;
            z-index: -1;
        }

        

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        @media print {
            button {
                display: none;
            }
        }

        .print-button {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }

        .print-button button {
            padding: 10px 15px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .print-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
<header>
        <!-- <a id="homeButton3" href="index.php" class="button no-print">
            <i class="fas fa-home"></i> Accueil
        </a> -->

        <!-- <a id="homeButton1" href="print_retrait.php" class="button no-print" target="_blank">
            <i class="fas fa-print"></i> PDF
        </a> -->
      
    </header>
    <h1>Impression des Retraits</h1>

    <div class="print-button">
        <button onclick="window.print()">Imprimer</button>
    </div>

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

    <?php
    // Fermer la connexion
    $conn->close();
    ?>

</body>

</html>
