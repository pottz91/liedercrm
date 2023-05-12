<?php include 'header.php'; ?>

<?php
// Datenbankverbindung herstellen
include('datenbank.php');

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Benutzereingaben validieren
    $benutzername = filter_input(INPUT_POST, 'benutzername', FILTER_SANITIZE_STRING);
    $passwort = filter_input(INPUT_POST, 'passwort', FILTER_SANITIZE_STRING);
    $passwort2 = filter_input(INPUT_POST, 'passwort2', FILTER_SANITIZE_STRING);

    // Überprüfen, ob beide Passwörter übereinstimmen
    if ($passwort !== $passwort2) {
        die('Die Passwörter stimmen nicht überein.');
    }

    // Passwort hashen
    $hash = password_hash($passwort, PASSWORD_DEFAULT);

    // SQL-Abfrage zum Überprüfen, ob der Benutzer bereits existiert
    $sql = "SELECT * FROM benutzer WHERE benutzername = '$benutzername'";

    $result = $conn->query($sql);

    // Überprüfen, ob die Abfrage erfolgreich war
    if ($result === false) {
        die("Abfrage fehlgeschlagen: " . $conn->error);
    }

    // Überprüfen, ob ein Ergebnis zurückgegeben wurde
    if ($result->num_rows > 0) {
        die("Der Benutzername ist bereits vergeben.");
    }

    // Benutzer in die Datenbank eintragen
    $sql = "INSERT INTO benutzer (benutzername, passwort) VALUES ('$benutzername', '$hash')";

    if ($conn->query($sql) === false) {
        die("Eintrag in die Datenbank fehlgeschlagen: " . $conn->error);
    }

    // Erfolgsmeldung
    echo "Registrierung erfolgreich abgeschlossen!";
}

// Verbindung zur Datenbank schließen
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
</head>

<body>
    <div class="container">
        <h1>Registrierung</h1>
        <form method="post" action="registrierung.php">
            <div class="mb-3">
                <label for="benutzername" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="benutzername" name="benutzername">
            </div>
            <div class="mb-3">
                <label for="passwort" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="passwort" name="passwort">
            </div>
            <div class="mb-3">
                <label for="passwort2" class="form-label">Passwort wiederholen</label>
                <input type="password" class="form-control" id="passwort2" name="passwort2">
            </div>
            <button type="submit" class="btn btn-primary">Registrieren</button>
        </form>
    </div>
</body>

</html>