<?php
include 'datenbank.php';
include 'auth.php';

//Code für bessere Fehlersuche
error_reporting(E_ALL);
ini_set('display_errors', '1');

$message = ""; // Variable für die Meldung

if (isset($_POST['submit'])) {
    if (!empty($_POST['password'])) {
        $neuesPasswort = $_POST['password'];
        $hashPasswort = password_hash($neuesPasswort, PASSWORD_DEFAULT);

        session_start();
        $benutzername = $_SESSION['benutzername'];

        $sql = "UPDATE benutzer SET passwort = '$hashPasswort' WHERE benutzername = '$benutzername'";

        if ($conn->query($sql) === TRUE) {
            $message = "Passwort wurde erfolgreich aktualisiert.";
        } else {
            $message = "Fehler beim Aktualisieren des Passworts: " . $conn->error;
        }
    } else {
        $message = "Bitte geben Sie ein neues Passwort ein.";
    }
}

// Rollenabfrage
//session_start();
$benutzername = $_SESSION['benutzername'];

// Überprüfen, ob der aktuelle Benutzer die ID 1 hat
$sql = "SELECT id FROM benutzer WHERE benutzername = '$benutzername'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $benutzerID = $row['id'];

    if ($benutzerID == 1) {
        // SQL-Abfrage zum Abrufen aller anderen registrierten Mitglieder
        $sqlAndereMitglieder = "SELECT * FROM benutzer WHERE id <> 1";
        $resultAndereMitglieder = $conn->query($sqlAndereMitglieder);

        // Array zum Speichern der anderen Mitglieder
        $andereMitglieder = array();

        // Schleife zum Durchlaufen der Abfrageergebnisse
        while ($rowAndereMitglieder = $resultAndereMitglieder->fetch_assoc()) {
            $andereMitglieder[] = $rowAndereMitglieder['benutzername'];
        }
    }
}

// Bild-Upload-Funktion
if (isset($_POST['upload'])) {
    $benutzername = $_SESSION['benutzername'];

    // Überprüfen, ob eine Datei ausgewählt wurde
    if (!empty($_FILES['bild']['name'])) {
        $uploadOrdner = 'uploads/'; // Verzeichnis zum Speichern der hochgeladenen Bilder
        $dateiName = $_FILES['bild']['name'];
        $dateiTemp = $_FILES['bild']['tmp_name'];
        $dateiZiel = $uploadOrdner . $dateiName;

        // Verschieben der hochgeladenen Datei in das Zielverzeichnis
        if (move_uploaded_file($dateiTemp, $dateiZiel)) {
            // Bildpfad in die Datenbank speichern
            $sql = "UPDATE benutzer SET bild = '$dateiZiel' WHERE benutzername = '$benutzername'";

            if ($conn->query($sql) === TRUE) {
                $message = "Bild erfolgreich hochgeladen.";
                header("Location: profil.php"); // Seite neu laden
                exit(); // Beenden Sie das Skript, um die Weiterleitung sicherzustellen
            } else {
                $message = "Fehler beim Hochladen des Bildes: " . $conn->error;
            }
        } else {
            $message = "Fehler beim Verschieben der hochgeladenen Datei.";
        }
    } else {
        $message = "Bitte wählen Sie eine Datei aus.";
    }
}

// Einloggen als anderer Benutzer
if (isset($_GET['username'])) {
    $benutzername = $_GET['username'];

    // Überprüfen der Authentifizierung für den Benutzernamen
    // ...

    // Einloggen als Benutzer
    session_start();
    $_SESSION['benutzername'] = $benutzername;
    // Weitere Informationen oder Berechtigungen in der Sitzung speichern
    // ...

    // Weiterleitung zur Profilseite des eingeloggten Benutzers
    header("Location: profil.php");
    exit();
}

?>

<div id="wrapper">
    <?php include 'menu.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <?php include 'topbar.php'; ?>

        <div id="content" style="margin-bottom: 200px">
            <div class="container">
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="account-settings">
                                    <div class="user-profile">
                                        <h5 class="user-name">
                                            <?php echo "<p>" . $_SESSION['benutzername'] . "</p>"; ?>
                                        </h5>

                                        <?php

                                        $benutzername = $_SESSION['benutzername'];

                                        // Bild des Benutzers aus der Datenbank abrufen
                                        $sql = "SELECT bild FROM benutzer WHERE benutzername = '$benutzername'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $bild = $row['bild'];
                                            if (!empty($bild)) {
                                                echo '<img style="border-radius: 5px" src="' . $bild . '" alt="Profilbild" width="200">';
                                            } else {
                                                echo '<img style="border-radius: 5px" src="https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png" alt="Profilbild" width="200">';
                                            }
                                        } else {
                                            echo '<img style="border-radius: 5px" src="https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png" alt="Profilbild" width="200">';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-<?php echo $message == "Passwort wurde erfolgreich aktualisiert." ? "success" : "danger"; ?>"
                                        role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <h6 class="mb-2 text-primary">Passwort ändern</h6>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <form method="POST" action="profil.php">
                                            <div class="form-group">
                                                <label for="password">Neues Passwort</label>
                                                <input type="password" class="form-control" id="password"
                                                    placeholder="Neues Passwort eingeben" name="password">
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" id="submit" name="submit"
                                                    class="btn btn-primary">Aktualisieren</button>
                                            </div>
                                        </form>
                                        <form method="POST" action="profil.php" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="bild">Profilbild hochladen</label>
                                                <input type="file" class="form-control-file" id="bild" name="bild">
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" id="upload" name="upload"
                                                    class="btn btn-primary">Hochladen</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <hr>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($benutzerID) && $benutzerID == 1 && !empty($andereMitglieder)): ?>
                <div class="container">
                    <div class="card card-body m-0 mt-2  row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 text-primary">Andere registrierte Mitglieder:</h6>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <ul>
                                <?php foreach ($andereMitglieder as $mitglied): ?>
                                    <li>
                                        <?php echo $mitglied; ?>
                                        <a href="profil.php?username=<?php echo $mitglied; ?>">Einloggen</a>
                                        <form method="POST" action="profil.php" style="display:inline-block;">
                                            <input type="hidden" name="editBenutzerID" value="<?php echo $mitglied; ?>">
                                            <button type="submit" name="edit" class="btn btn-link">Bearbeiten</button>
                                        </form>
                                        <form method="POST" action="profil.php" style="display:inline-block;">
                                            <input type="hidden" name="deleteBenutzerID" value="<?php echo $mitglied; ?>">
                                            <button type="submit" name="delete" class="btn btn-link">Löschen</button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div id="content" style="margin-bottom: 200px">
                <!-- Rest des Inhalts hier -->
                <div class="pt-2 container">
                    <?php
                    include 'log.php';
                    $benutzerID = $_SESSION['benutzerID'] = 1; // Annahme: Die Benutzer-ID wird in der Sitzungsvariable 'benutzerID' gespeichert
                    if ($benutzerID == 1) {
                        getAktivitaetsprotokoll();
                    }
                    ?>
                </div>

            </div>

        </div>
    </div>
</div>
