<?php
// db.php
$host = 'localhost';        // Database host
$db   = 'webtech_2025A_deborah_maxime'; // Database name
$user = 'deborah.maxime';             // Database user
$pass = 'Rab13963';                 // Database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
