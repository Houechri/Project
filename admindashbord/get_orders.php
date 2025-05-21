<?php
header('Content-Type: application/json');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'panier_db';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit;
}

// Vérifier si la table produits_commandes existe
try {
    $commandesStmt = $pdo->query("SELECT * FROM commandes ORDER BY id DESC");
    $commandes = $commandesStmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($commandes as $commande) {
        $commande_id = $commande['id'];

        $produitsStmt = $pdo->prepare("SELECT * FROM produits_commandes WHERE commande_id = ?");
        $produitsStmt->execute([$commande_id]);
        $produits = $produitsStmt->fetchAll(PDO::FETCH_ASSOC);

        $result[] = [
            'nom' => $commande['nom'],
            'telephone' => $commande['telephone'],
            'adresse' => $commande['adresse'],
            'total' => $commande['total'],
            'date' => $commande['date_commande'],
            'produits' => $produits
        ];
    }

    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
}
?>
