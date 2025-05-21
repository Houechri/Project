<?php
header("Content-Type: application/json");
$pdo = new PDO("mysql:host=localhost;dbname=panier_db;charset=utf8", "root", "");

$data = json_decode(file_get_contents("php://input"), true);

// Insertion de la commande
$stmt = $pdo->prepare("INSERT INTO commandes (nom, telephone, adresse, total) VALUES (?, ?, ?, ?)");
$stmt->execute([$data['name'], $data['phone'], $data['location'], $data['total']]);
$commande_id = $pdo->lastInsertId();

// Insertion des produits
foreach ($data['products'] as $index => $product) {
  $stmt = $pdo->prepare("INSERT INTO produits_commandes (commande_id, nom_produit, prix, image) VALUES (?, ?, ?, ?)");
  $stmt->execute([$commande_id, $product['name'], $product['price'], $product['image'] ?? '']);

  // Enregistrer l'ID du produit pour suppression plus tard (optionnel si retour à JS)
}

echo json_encode(["status" => "success"]);
?>
