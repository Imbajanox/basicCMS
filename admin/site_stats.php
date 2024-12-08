<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$totalUsers = $db->query("SELECT COUNT(*) AS count FROM users")->fetch()['count'];
$adminUsers = $db->query("SELECT COUNT(*) AS count FROM users WHERE role = 'admin'")->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Statistiken</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Seitenstatistiken</h1>
    <nav>
        <a href="manage_users.php">Benutzerverwaltung</a>
        <a href="site_stats.php">Statistiken</a>
        <a href="content_management.php">Inhaltsverwaltung</a>
    </nav>
    <p>Gesamte Benutzer: <?php echo $totalUsers; ?></p>
    <p>Administratoren: <?php echo $adminUsers; ?></p>
</body>
</html>