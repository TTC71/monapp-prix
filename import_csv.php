<?php
// Activer l'affichage et le log des erreurs
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_debug.log');
error_reporting(E_ALL);

// Gestion globale des erreurs (même erreurs fatales)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile on line $errline");
    http_response_code(500);
    exit;
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL) {
        $info = "[SHUTDOWN] {$error['message']} in {$error['file']} on line {$error['line']}";
        error_log($info);
        http_response_code(500);
    }
});

// Paramètres de la base de données
$host = '192.168.1.16';
$db = 'mon_app_github';
$user = 'pvesaphong';
$pass = 'Ves@ph0ng1949';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=3307;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Emplacement de ton CSV
    $csvFile = __DIR__ . '/BaseProduits.csv';

    if (!file_exists($csvFile)) {
        throw new Exception("Fichier CSV introuvable !");
    }

    // Ouvre le fichier
    $file = fopen($csvFile, 'r');
    if (!$file) {
        throw new Exception("Impossible d'ouvrir le fichier CSV !");
    }

    // Lire la première ligne (en-tête)
    fgetcsv($file);

    // Parcourir le fichier
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        if (count($data) < 7) {
            continue; // Ligne vide ou incomplète
        }

        list($code1, $code2, $code3, $produit, $quantite, $prix, $prix_ajoute) = $data;

        $stmt = $pdo->prepare("INSERT IGNORE INTO produits (code1, code2, code3, produit, quantite, prix, prix_ajoute)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$code1, $code2, $code3, $produit, $quantite, $prix, $prix_ajoute]);
    }

    fclose($file);

    echo "Import terminé !";

} catch (Exception $e) {
    error_log("Exception : " . $e->getMessage());
    echo "Erreur : " . $e->getMessage();
}
?>
