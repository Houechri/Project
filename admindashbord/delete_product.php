<?php
header("Content-Type: application/json");
$pdo = new PDO("mysql:host=localhost;dbname=panier_db;charset=utf8", "root", "");

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);

$stmt = $pdo->prepare("DELETE FROM produits_commandes WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["status" => "deleted"]);
?>
