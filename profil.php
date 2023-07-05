<?php
include 'datenbank.php';
include 'auth.php';

// Funktion zum Aktualisieren des Passworts
function passwortAktualisieren($neuesPasswort)
{
    // Stelle sicher, dass der Benutzer eingeloggt ist
    session_start();
    if (!isset($_SESSION['benutzername'])) {
        die("Benutzer ist nicht eingeloggt.");
    }

    // Aktualisiere das Passwort in der Datenbank
    $benutzername = $_SESSION['benutzername'];
    $passwortHash = password_hash($neuesPasswort, PASSWORD_DEFAULT);
    $sql = "UPDATE benutzer SET passwort = '$passwortHash' WHERE benutzername = '$benutzername'";

    if ($conn->query($sql) === TRUE) {
        echo "Passwort erfolgreich aktualisiert.";
    } else {
        echo "Fehler beim Aktualisieren des Passworts: " . $conn->error;
    }
}

// Überprüfe, ob das Formular zum Aktualisieren des Passworts gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Überprüfe, ob das neue Passwort ausgefüllt wurde
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $neuesPasswort = $_POST['password'];

        // Passwort aktualisieren
        passwortAktualisieren($neuesPasswort);
    } else {
        echo "Bitte geben Sie ein neues Passwort ein.";
    }
}
?>

<div id="wrapper">
    <?php
    include 'menu.php';
    ?>


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <?php
        include 'topbar.php';
        ?>
        <!-- Main Content -->
        <div id="content" style="margin-bottom: 200px">
            <div class="container">
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="account-settings">
                                    <div class="user-profile">
                                        <h5 class="user-name">
                                            <?php
                                            echo "<p>" . $_SESSION['benutzername'] . "</p>";
                                            ?>
                                        </h5>
                                        <h6 class="user-email">test</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <h6 class="mb-2 text-primary">Passwort ändern</h6>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="password">Neues Passwort</label>
                                            <input type="password" class="form-control" id="password"
                                                placeholder="Neues Passwort eingeben" name="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-right">
                                            <button type="submit" id="submit" name="submit"
                                                class="btn btn-primary">Aktualisieren</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>