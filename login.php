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
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $db->prepare("SELECT id, password, role FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Benutzername oder Passwort falsch.";
        }
    } else {
        echo "Bitte f�llen Sie alle Felder aus.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <div class="container">
        <form method="post">
            <input type="text" name="username" placeholder="Benutzername" required><br>
            <input type="password" name="password" placeholder="Passwort" required><br>
           <button type="submit">Login</button>
           <a href="forgot_password.php">forgot password?</a>
        </form>
        
    </div>
    
</body>
</html>