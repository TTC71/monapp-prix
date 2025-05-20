<?php
require 'secure.php';
$data = json_decode(file_get_contents("php://input"));

$pdo = new PDO("mysql:host=192.168.1.16;dbname=scan_produits_pro;port=3307;charset=utf8mb4", "pvesaphong", "Ves@ph0ng1949", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$check = $pdo->prepare("SELECT * FROM produits WHERE code1 = ?");
$check->execute([$data->code1]);
$product = $check->fetch();

if ($product) {
    $pdo->prepare("UPDATE produits SET prix_ajoute = ?, source = ? WHERE code1 = ?")
        ->execute([$data->prix_ajoute, $data->source, $data->code1]);
} else {
    $pdo->prepare("INSERT INTO produits (code1, produit, quantite, prix_ajoute, source) VALUES (?, ?, ?, ?, ?)")
        ->execute([$data->code1, $data->produit, $data->quantite, $data->prix_ajoute, $data->source]);
}

$pdo->prepare("INSERT INTO historique (user_id, action) VALUES (?, ?)")
    ->execute([$_SESSION['user_id'], "Ajout ou maj prix pour code: " . $data->code1]);

echo json_encode(["success" => true]);
?>