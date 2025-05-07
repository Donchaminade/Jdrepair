<?php
// Démarrage de la session
session_start();

// Vérifie si l'utilisateur est connecté
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

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer toutes les demandes
$sql = "SELECT * FROM demandeur where Etat = 0 ORDER BY DateHeureDmd ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>print</title>
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

        @media print {
            .no-print {
                display: none;
            }
        }

        .print-button {
            margin: 20px 0;
            text-align: center;
        }

        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
<header>
        <!-- <a id="homeButton4" href="index.php" class="button no-print">
            <i class="fas fa-home"></i> Accueil
        </a> -->

        <!-- <a id="homeButton1" href="print_retrait.php" class="button no-print" target="_blank">
            <i class="fas fa-print"></i> PDF
        </a> -->
      
    </header>
    <h1>Demandes restants en cours</h1>

    <!-- fonction de windows permettant d'imprimer la liste -->
    <div class="print-button no-print">
        <button onclick="window.print()">Imprimer</button>
        <!-- <button onclick="window.open('print.php', '_blank');">Imprimer</button> -->

    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Num. Téléphone</th>
                <th>Année Concernée</th>
                <th>Type de Demande</th>
                <th>Date RDV</th>
                <th>Date/Heure Demande</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Affichage des demandes
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['NomDmd']) . "</td>
                            <td>" . htmlspecialchars($row['NumeroTel']) . "</td>
                            <td>" . htmlspecialchars($row['ConcerneAnnee']) . "</td>
                            <td>" . htmlspecialchars($row['TypeDmd']) . "</td>
                            <td>" . htmlspecialchars($row['DateRdv']) . "</td>
                            <td>" . htmlspecialchars($row['DateHeureDmd']) . "</td>
                            
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='no-data'>Aucune demande trouvée</td></tr>";
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
