<?php
session_start();
if(!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit;
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Willkommen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <nav>
    <?php 
    if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
        echo "<a href='admin/dashboard.php'>Admin Dashboard</a>";
    }
    ?>
    <a href="logout.php">Logout</a>
    </nav>
</body>
</html>