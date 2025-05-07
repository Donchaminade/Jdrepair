<?php
session_start();
header('Content-Type: application/json');

require_once '../Admin/includes/conf.php';

// Lire les données JSON reçues
$data = json_decode(file_get_contents('php://input'), true);

// Vérification si les données sont valides
if (!$data || !isset($data['email'], $data['mot_de_passe'])) {
    echo json_encode(['success' => false, 'message' => 'Données invalides reçues.']);
    exit;
}

$email = htmlspecialchars(trim($data['email']));
$password = trim($data['mot_de_passe']); // pas besoin de htmlspecialchars ici

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

try {
    // Rechercher l'utilisateur par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        // Enregistrer les informations en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie',
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Identifiants invalides.']);
    }

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
}
