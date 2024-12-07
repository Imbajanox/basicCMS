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

$stmt = $db->query("SELECT id, username, email, role FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Benutzerverwaltung</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Benutzerverwaltung</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Benutzername</th>
                <th>E-Mail</th>
                <th>Rolle</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="user_management/edit_user.php?id=<?php echo $user['id']; ?>">Bearbeiten</a>
                        <a href="user_management/delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Sind Sie sicher?')">Löschen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>