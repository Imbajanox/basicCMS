<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Benutzer-ID aus der URL holen
if (!isset($_GET['id'])) {
    echo "Benutzer-ID fehlt.";
    exit;
}

$userId = $_GET['id'];

// Benutzer aus der Datenbank löschen
$stmt = $db->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);

echo "Benutzer gelöscht!";
header("Location: ../manage_users.php"); // Zurück zur Benutzerverwaltung
exit;
?>