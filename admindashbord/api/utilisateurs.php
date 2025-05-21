<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Connexion à la base de données
$host = 'localhost';
$db   = 'auth_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit;
}

// Vérifier que la méthode est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Méthode non autorisée']);
    exit;
}

// Lire les données JSON
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si les données sont valides
if (!isset($data['nom'], $data['email'], $data['password'], $data['role'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Champs manquants']);
    exit;
}

$nomComplet = explode(' ', $data['nom']);
$first_name = $nomComplet[0];
$last_name = isset($nomComplet[1]) ? $nomComplet[1] : '';

$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Sécurité
$role = $data['role'];

try {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $password, $role]);

    echo json_encode(['message' => 'Utilisateur ajouté avec succès']);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erreur lors de l\'insertion : ' . $e->getMessage()]);
}
?>
