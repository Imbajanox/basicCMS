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

// Benutzer aus der Datenbank laden
$stmt = $db->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "Benutzer nicht gefunden.";
    exit;
}

// Wenn das Formular abgeschickt wurde, Daten aktualisieren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $updateStmt = $db->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
    $updateStmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':id' => $userId
    ]);

    echo "Benutzer aktualisiert!";
    header("Location: ../manage_users.php"); // Zurück zur Benutzerverwaltung
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Benutzer bearbeiten</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_users.php">Benutzerverwaltung</a>
        <a href="site_stats.php">Statistiken</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <div class="container">
    <h1>Benutzer bearbeiten</h1>
    <form method="POST">
        <label>Benutzername:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label>E-Mail:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label>Rolle:</label>
        <select name="role" required>
            <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>Benutzer</option>
            <option value="editor" <?php if ($user['role'] === 'editor') echo 'selected'; ?>>Moderator</option>
            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Administrator</option>
        </select><br>

        <button type="submit">Speichern</button>
        <a href="../manage_users.php">Abbrechen</a>
    </form>
</div>
    
</body>
</html>