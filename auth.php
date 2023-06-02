<?php
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Benutzer ist nicht angemeldet, leite zur Login-Seite weiter
    header('Location: index.php');
    exit;
}
