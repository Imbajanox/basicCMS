<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Admin-Dashboard</title>
</head>
<body>
    <h1>Admin-Dashboard</h1>
    <ul>
        <li><a href="manage_users.php">Benutzerverwaltung</a></li>
        <li><a href="site_stats.php">Statistiken</a></li>
        <li><a href="content_management.php">Inhaltsverwaltung</a></li>
    </ul>
</body>
</html>