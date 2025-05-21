<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(["message" => "Email et mot de passe requis."]);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
  echo json_encode([
    "message" => "Connexion réussie.",
    "user" => [
      "id" => $user['id'],
      "email" => $user['email'],
      "first_name" => $user['first_name']
    ]
  ]);
} else {
  http_response_code(401);
  echo json_encode(["message" => "Identifiants invalides."]);
}
