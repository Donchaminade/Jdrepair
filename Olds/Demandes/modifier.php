<?php
require_once '../includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_client = $_POST["id_client"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $telephone = $_POST["téléphone"];
    $adresse = $_POST["adresse"];

    if (!empty($id_client) && !empty($nom) && !empty($email)) {
        try {
            $sql = "UPDATE Clients SET nom = :nom, prenom = :prenom, email = :email, téléphone = :telephone, adresse = :adresse WHERE id_client = :id_client";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':id_client' => $id_client
            ]);

            $_SESSION['message'] = "Le client a été modifié avec succès.";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur lors de la modification : " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Veuillez remplir tous les champs requis.";
    }
}

header("Location: index.php");
exit();
