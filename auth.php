<?php
session_start();

// Die Datenbankverbindung einbinden
include 'datenbank.php';

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 1) {
    // Benutzer ist nicht angemeldet, leite zur Login-Seite weiter
    header('Location: index.php');
    exit;
}
echo "<p>Angemeldet als: " . $_SESSION['benutzername'] . "</p>";

?>