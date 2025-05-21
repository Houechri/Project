<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
// Réponse au format JSON
header('Content-Type: application/json');

// Connexion à la base de données
$host = 'localhost';        // ou 127.0.0.1
$db   = 'auth_db';    // Nom de votre base de données
$user = 'root';             // Nom d'utilisateur MySQL
$pass = '';                 // Mot de passe MySQL
$charset = 'utf8mb4';

// DSN = Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options pour PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Connexion à la base
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Exécution de la requête
    $stmt = $pdo->query("SELECT id, first_name, last_name, email, role FROM utilisateurs");

    // Récupération des résultats
    $users = $stmt->fetchAll();

    // Réponse JSON
    echo json_encode($users);

} catch (\PDOException $e) {
    // En cas d'erreur, retourner une erreur JSON
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données : ' . $e->getMessage()]);
}
?>
