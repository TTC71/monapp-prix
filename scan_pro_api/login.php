<?php
session_start();
file_put_contents("debug_login.txt", file_get_contents("php://input"));
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO("mysql:host=192.168.1.16;dbname=scan_produits_pro;port=3307;charset=utf8mb4", "pvesaphong", "Ves@ph0ng1949", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST)) {
        // Envoi classique via formulaire HTML
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
    } else {
        // Envoi via fetch JSON
        $data = json_decode(file_get_contents("php://input"));
        $username = $data->username ?? '';
        $password = $data->password ?? '';
        $remember = $data->remember ?? false;
    }

    // Vérification que username ET password sont bien là
    if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Champs manquants"]);
        exit;
    }

    // REQUETE SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $token = bin2hex(random_bytes(16));
        $pdo->prepare("UPDATE users SET token = ? WHERE id = ?")->execute([$token, $user['id']]);

        $_SESSION['token'] = $token;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        echo json_encode(["token" => $token, "role" => $user['role']]);
    } else {
        echo json_encode(["error" => "Identifiants invalides"]);
    }
    exit;
} else {
    echo json_encode(["error" => "Méthode invalide."]);
    exit;
}
