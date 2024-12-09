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
$db->query("CREATE TABLE IF NOT EXISTS posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$stmt = $db->query("SELECT id, title, created_at FROM posts");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inhaltsverwaltung</title>
</head>

<body>
    <h1>Inhaltsverwaltung</h1>
    <a href="add_post.php">Neuen Beitrag hinzufügen</a>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <?php echo htmlspecialchars($post['title']); ?> - <?php echo $post['created_at']; ?>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>">Bearbeiten</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>"
                    onclick="return confirm('Sind Sie sicher?')">Löschen</a>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>