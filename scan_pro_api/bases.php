<?php
require 'secure.php';
header('Content-Type: application/json');

$pdo = new PDO("mysql:host=192.168.1.16;dbname=scan_produits_pro;port=3307;charset=utf8mb4", "pvesaphong", "Ves@ph0ng1949", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$bases = $pdo->query("SELECT * FROM bases")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($bases);
?>