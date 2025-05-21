<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$firstName = $data['first_name'] ?? '';
$lastName = $data['last_name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$firstName || !$lastName || !$email || !$password) {
  http_response_code(400);
  echo json_encode(["message" => "Tous les champs sont requis."]);
  exit;
}

$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
  http_response_code(409);
  echo json_encode(["message" => "Email déjà utilisé."]);
  exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO utilisateurs (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
$stmt->execute([$firstName, $lastName, $email, $hashedPassword]);

echo json_encode(["message" => "Inscription réussie."]);
