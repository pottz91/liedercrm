<?php
session_start();

if (isset($_GET["page"]) && $_GET["page"] === "log") {
    $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
    $passwort = filter_input(INPUT_POST, 'passwort', FILTER_SANITIZE_STRING);

    // SQL-Abfrage zum Überprüfen, ob der Benutzername existiert
    $sql = "SELECT * FROM benutzer WHERE benutzername = '$user'";
    $result = $conn->query($sql);

    // Überprüfen, ob die Abfrage erfolgreich war
    if ($result === false) {
        die("Abfrage fehlgeschlagen: " . $conn->error);
    }

    // Überprüfen, ob ein Ergebnis zurückgegeben wurde
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Überprüfen, ob das Passwort korrekt ist
        if (password_verify($passwort, $row['passwort'])) {
            $_SESSION["username"] = $user;
            echo '<script>window.location.href = "seite2.php";</script>';
            exit();
        } else {
            echo "Das eingegebene Passwort ist falsch!";
        }

    } else {
        echo "Der Benutzername wurde nicht gefunden!";
    }
}
?>