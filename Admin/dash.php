<?php
// Démarrage de la session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['iduser'])) {
    header("Location: login.html");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "repa_db"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}


// Requête pour récupérer les dernieres reparation récentes triées par date
// $sqlRecentDemandes = "SELECT NomDmd, NumeroTel, TypeDmd, DateRdv, Etat FROM demandeur ORDER BY DateRdv DESC LIMIT 25";
// $result = $conn->query($sqlRecentDemandes);

// Vérifier les résultats
// if (!$result) {
//     die("Erreur dans l'exécution de la requête : " . $conn->error);
// }


// Requête pour compter le nombre total de demandes
// $sqlTotalDemandes = "SELECT COUNT(*) as totalDemandes FROM demandeur";
// $resultDemandes = $conn->query($sqlTotalDemandes);
// $totalDemandes = $resultDemandes->fetch_assoc()['totalDemandes'] ?? 0;




// Requête pour compter le nombre de demandes non retirées (état = 0)
// $sqlDemandesNonRetirees = "SELECT COUNT(*) as demandesNonRetirees FROM demandeur WHERE etat = 0";
// $resultDemandesNonRetirees = $conn->query($sqlDemandesNonRetirees);

// Vérification de la validité du résultat
// Valeur par défaut si la requête échoue
// $demandesNonRetirees = 0; 
// if ($resultDemandesNonRetirees) {
//     $row = $resultDemandesNonRetirees->fetch_assoc();
//     $demandesNonRetirees = $row['demandesNonRetirees'] ?? 0;
// }


// Requête pour compter le nombre de demandes retirées (état = 1)
// $sqlRetraits = "SELECT COUNT(*) as totalRetraits FROM demandeur WHERE etat = 1";
// $resultRetraits = $conn->query($sqlRetraits);

// Vérification de la validité du résultat
// Valeur par défaut si la requête échoue
// $totalRetraits = 0; 
// if ($resultRetraits) {
//     $row = $resultRetraits->fetch_assoc();
//     $totalRetraits = $row['totalRetraits'] ?? 0;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JD Reparation</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" type="text/css" href="d.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Vos styles ici */
         /* Styles du fichier */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        nav {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-container {
            margin: 0 auto;
            padding: 20px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
        }

        .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }

        .form-group button {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #555;
        }

        .btn {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #555;
        }



                .session-info {
            background-color: #f8f9fa; /* Fond léger */
            padding: 10px 20px; /* Espacement interne */
            border-radius: 8px; /* Coins arrondis */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Ombre légère */
            display: flex;
            align-items: center; /* Centrage vertical */
            justify-content: space-between; /* Espacement entre le texte et le bouton */
            margin: 10px 0; /* Marges autour du bloc */
        }

        .session-info span {
            font-size: 16px; /* Taille du texte */
            color: #333; /* Couleur du texte */
            font-weight: 600; /* Gras pour l'accent */
        }

        .session-info span:first-child {
            font-size: 14px;
            color: #6c757d; /* Couleur secondaire (invité) */
        }

        .session-info span strong {
            font-weight: bold;
            color: #007bff; /* Accent bleu pour le nom */
        }



        .status.disponible {
            color: green;
            font-weight: bold;
        }

        .status.retirer {
            color: red;
            font-weight: bold;
        }




    </style>
</head>

<body>
    <!-- Votre contenu HTML ici -->
    <div class="container">
        <!-- Navigation et contenu principal -->
         <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title">JDRepair</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Tableau de Bord</span>
                    </a>
                </li>

              <li>
                <a href="#" id="openModal">
                    <span class="icon">
                        <!-- Icône de dossier pour le dépôt -->
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </span>
                    <span class="title">Faire un dépôt</span>
                </a>
            </li>

                <li>
                    <a href="afficher_demandes.php">
                        <span class="icon">
                            <ion-icon name="list-outline"></ion-icon>
                        </span>
                        <span class="title">Liste des demandes</span>
                    </a>
                </li>

                <li>
                    <a href="afficher_retrait.php">
                        <span class="icon">
                            <ion-icon name="swap-horizontal-outline"></ion-icon>
                        </span>
                        <span class="title">Liste des retraits</span>
                    </a>
                </li>
                <li>
                    <a href="reste.php"  target="_blank">
                        <span class="icon ">
                            <ion-icon name="swap-vertical-outline"></ion-icon>
                        </span>
                        <span class="title">Reste a Traiter</span>
                    </a>
                </li>
              <!-- Affichez l'élément seulement si le poste de l'utilisateur est Inspecteur -->
    <?php if ($_SESSION['role'] === 'jdadmin'): ?>
        <li>
            <a href="users.php">
                <span class="icon">
                    <ion-icon name="person-outline"></ion-icon>
                </span>
                <span class="title">Utilisateurs</span>
            </a>
        </li>
    <?php endif; ?>

                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Déconnecté</span>
                    </a>
                </li>
                <br>
                <br><br>
                <br>
                <br>
                <br>

                <!-- <a href="codecrack.netlify.com" class = "text-white">Copyright @2025 CodeCrack</a> -->
            </ul>
            
        </div>
        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

               
                <div class="user">
                    <img src="assets/imgs/customer02.png" alt="">
                </div>
            </div>

            <!-- =================== Session Info =================== -->
              <!-- =================== Session Info =================== -->
<div class="session-info">
    <?php
    // Assurez-vous que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifiez si les informations nécessaires existent dans la session
    if (isset($_SESSION['nom_complet']) && isset($_SESSION['role'])) {
        echo "<span>Ravi de vous revoir, " . htmlspecialchars($_SESSION['nom_complet']) . " (" . htmlspecialchars($_SESSION['role']) . ")</span>";
    } else {
        echo "<span>Bienvenue, invité</span>";
    }
    ?>
</div>
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo htmlspecialchars//($totalDemandes); ?></div>
                        <div class="cardName">Reparation Totale</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="documents-outline"></ion-icon>
                    </div>
                </div>
                <!-- Affichage de la carte avec le nombre de retraits -->
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo htmlspecialchars//($totalRetraits); ?></div>
                        <div class="cardName">Mes clients</div>
                    </div>
                    <div class="iconBx">
                        <!-- Icône de dossier ouvert pour le retrait de dossier -->
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                <div>
                    <div class="numbers"><?php echo //$demandesNonRetirees; ?></div>
                    <div class="cardName">Appareils Recus</div>
                </div>
                <div class="iconBx">
                <ion-icon name="swap-horizontal-outline"></ion-icon>
                </div>
            </div>

                <div class="card">
                    <div>
                    <div class="numbers">
            <?php 
            // Calcul du pourcentage de traitement
            // if ($totalDemandes > 0) {
            //     $pourcentageTraitement = ($totalRetraits / $totalDemandes) * 100;
            //     // Affichage du pourcentage avec 2 décimales
            //     echo round($pourcentageTraitement, 2) . '%'; 
            // } else {
            //     echo '0%'; // Si pas de demande, afficher 0%
            // }
            ?>
        </div>
                        <div class="cardName">Moyenne de traitement</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="bar-chart-outline"></ion-icon>
                    </div>
                </div>
            </div>


            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Liste des dépôts</h2>
                    </div>

    <table>
        <thead>
            <tr>
                <td>Nom du Demandeur</td>
                <td>Numéro</td>
                <td>Type de Demande</td>
                <td>Date du Rendez-vous</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                // Affichage des données récupérées
                while ($row = $result->fetch_assoc()) {
                    $etatText = ($row['Etat'] == 0) ? 'Disponible' : 'Retirer'; // Conversion du statut
                    $statusClass = ($row['Etat'] == 0) ? 'disponible' : 'retirer'; // Classe CSS dynamique
                    echo "<tr>
                            <td>" . htmlspecialchars($row['NomDmd']) . "</td>
                            <td>" . htmlspecialchars($row['NumeroTel']) . "</td>
                            <td>" . htmlspecialchars($row['TypeDmd']) . "</td>
                            <td>" . htmlspecialchars($row['DateRdv']) . "</td>
                            <td><span class='status " . $statusClass . "'>" . htmlspecialchars($etatText) . "</span></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-data'>Aucune demande trouvée</td></tr>";
            }
            ?>
        </tbody>
    </table>
                </div>

                <div class="recentCustomers">
    <div class="cardHeader">
        <h2>Notifications</h2><br>
    </div>
    <h4>  Rendez-vous prévu dans la semaine .</h4>
    <table>
        <?php
        // Requête pour récupérer les demandes dans la semaine à venir
        $sqlUpcomingAppointments = "SELECT NomDmd, DateRdv FROM demandeur WHERE DateRdv BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        $resultUpcomingAppointments = $conn->query($sqlUpcomingAppointments);

        if ($resultUpcomingAppointments && $resultUpcomingAppointments->num_rows > 0) {
            // Afficher chaque demande comme une ligne dans le tableau
            while ($row = $resultUpcomingAppointments->fetch_assoc()) {
                $nomDmd = htmlspecialchars($row['NomDmd']);
                $dateRdv = htmlspecialchars($row['DateRdv']);
                ?>
                <tr>
                    <td width="60px">
                        <!-- Image d'illustration de la demandeur, vous pouvez la personnaliser -->
                        <div class="imgBx">
                            <img src="assets/imgs/customer02.PNG" alt="Customer">
                        </div>
                    </td>
                    <td>
                        <h4><?php echo $nomDmd; ?><br><span><?php echo $dateRdv; ?></span></h4>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='2'>Aucun rendez-vous imminent.</td></tr>";
        }
        ?>
    </table>
</div>




    
            <!-- Modal -->
            <div id="modalDepot" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="form-container">
                        <h2>Formulaire de Demande (Depôt)</h2>
                        <form action="traitement.php" method="post">
                            <div class="form-group">
                                <label for="nomDmd">Nom</label>
                                <input type="text" id="nomDmd" name="NomDmd" required>
                            </div>

                            <div class="form-group">
                                <label for="numeroTel">Numéro de Téléphone</label>
                                <input type="text" id="numeroTel" name="NumeroTel" required>
                            </div>

                            <div class="form-group">
                                <label for="concerneAnnee">Année Concernée</label>
                                <input type="number" id="concerneAnnee" name="ConcerneAnnee" min="1900" max="2099" required>
                            </div>

                            <div class="form-group">
                                <label>Type de Demande</label>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="typeAttestation" name="TypeDmd[]" value="Attestation de Nationalité">
                                    <label for="typeAttestation">Attestation de CEPD</label>

                                    <input type="checkbox" id="typeBac" name="TypeDmd[]" value="BAC">
                                    <label for="typeBac">Attestation de BAC</label>

                                    <input type="checkbox" id="typeActeNaissance" name="TypeDmd[]" value="Acte de Naissance">
                                    <label for="typeActeNaissance">Duplicata</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dateRdv">Date du Rendez-vous</label>
                                <input type="date" id="dateRdv" name="DateRdv" required>
                            </div>

                            <input type="hidden" id="idAdmin" name="IdAdmin" value="<?php echo $_SESSION['IdAdmin']; ?>">

                            <button type="submit" class="btn">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"> </script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </div>
</body>

</html>

