<?php
session_start();

// Datenbankverbindung mit PDO
$dsn = "mysql:host=localhost;dbname=test_db;charset=utf8mb4";
$db = new PDO($dsn, "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $password_hash
            ]);
            $_SESSION['success'] = "Registrierung erfolgreich. Bitte loggen Sie sich ein.";
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Doppelte Eintr�ge verhindern
                echo "Benutzername oder E-Mail bereits vergeben.";
            } else {
                echo "Fehler: " . $e->getMessage();
            }
        }
    } else {
        echo "Bitte f�llen Sie alle Felder aus.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Registrierung</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Registrierung</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Benutzername" required><br>
        <input type="email" name="email" placeholder="E-Mail" required><br>
        <input type="password" name="password" placeholder="Passwort" required><br>
        <button type="submit">Registrieren</button>
    </form>
</body>
</html>