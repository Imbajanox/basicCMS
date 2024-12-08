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
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Willkommen im Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <nav>
        <a href="manage_users.php">Benutzerverwaltung</a>
        <a href="site_stats.php">Statistiken</a>
        <a href="content_management.php">Inhaltsverwaltung</a>
    </nav>
</body>
</html>