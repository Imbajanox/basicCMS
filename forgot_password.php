<?php
session_start();

$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Prüfen, ob die E-Mail existiert
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generiere einen Reset-Token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+2 hour')); // 1 Stunde gültig
        $stmt = $db->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->execute([
            ':token' => $token,
            ':expires' => $expires,
            ':email' => $email
        ]);

        // Sende den Link per E-Mail (hier simuliert)
        $resetLink = "reset_password.php?token=$token";
        echo "Ein Passwort-Reset-Link wurde gesendet: <a href='$resetLink'>$resetLink</a>";
    } else {
        echo "E-Mail-Adresse wurde nicht gefunden.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Passwort zurücksetzen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Passwort zurücksetzen</h1>
    <form method="POST">
        <label>E-Mail:</label>
        <input type="email" name="email" required>
        <button type="submit">Reset-Link senden</button>
    </form>
</body>
</html>