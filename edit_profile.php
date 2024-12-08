<?php
session_start();

// Datenbankverbindung
$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT first_name, last_name, phone_number FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Benutzer nicht gefunden.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);

    if (empty($first_name) || empty($last_name)) {
        $error = "Vorname und Nachname dürfen nicht leer sein.";
    } else {

        $stmt = $db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number WHERE id = :id");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':phone_number' => $phone_number,
            ':id' => $user_id
        ]);

        $success = "Profil erfolgreich aktualisiert!";

        $user['first_name'] = $first_name;
        $user['last_name'] = $last_name;
        $user['phone_number'] = $phone_number;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil bearbeiten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Profil bearbeiten</h2>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        
        
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="first_name">Vorname:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>

            <label for="last_name">Nachname:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>

            <label for="phone_number">Telefonnummer:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">

            <button type="submit">Änderungen speichern</button>
        </form>
    </div>
</body>
</html>