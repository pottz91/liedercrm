<?php

// Das ist unsere Datenbank Connection

// Verbindung zur Datenbank herstellen
$servername = "db";
$username = "db";
$password = "db";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen, ob die Verbindung erfolgreich war. falls nicht gibt es einen fehler
if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Hier können weitere Abfragen an die Datenbank gesendet werden

?>