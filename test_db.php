<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
echo "Erfolgreich verbunden";
?>
