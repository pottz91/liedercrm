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

<body style="background-color: #171c45;">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Account erstellen</h1>
                            </div>
                            <form class="user" method="POST" action="">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="benutzername"
                                        placeholder="Benutzername">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="passwort"
                                        placeholder="Passwort">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="passwort2"
                                        placeholder="Passwort wiederholen">
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Account registrieren
                                </button>

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="password.php">Passwort vergessen?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="index.php">Bereits einen Account? Einloggen!</a>
                            </div>
                            <div class="pt-2 text-center" style="font-size:12px">
                                <i>Siehe, ich komme bald und mein Lohn mit mir, zu geben einem jeglichen, wie seine
                                    Werke
                                    sein werden. Ich bin das A und das O, der Erste und der Letzte, der Anfang und das
                                    Ende.
                                    Offenbarung 22,12-13:</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>