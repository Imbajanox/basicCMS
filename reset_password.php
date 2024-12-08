<?php
session_start();

$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

if (!isset($_GET['token'])) {
    echo "Ungültiger Link.";
    exit;
}

$token = $_GET['token'];

// Überprüfen, ob der Token gültig ist
$stmt = $db->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()");
$stmt->execute([':token' => $token]);
$user = $stmt->fetch();

if (!$user) {
    echo "Ungültiger oder abgelaufener Token.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Passwort aktualisieren und Token zurücksetzen
    $stmt = $db->prepare("UPDATE users SET password = :password, reset_token = NULL WHERE reset_token = :token");
    $stmt->execute([
        ':password' => $password,
        ':token' => $token
    ]);

    echo "Passwort erfolgreich zurückgesetzt!";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Passwort zurücksetzen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Neues Passwort eingeben</h1>
    <form method="POST">
        <label>Neues Passwort:</label>
        <input type="password" name="password" required>
        <button type="submit">Passwort zurücksetzen</button>
    </form>
</body>
</html>
