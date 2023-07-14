<?php
include 'datenbank.php'; // Datenbankverbindung herstellen


// Funktion zum Protokollieren einer Aktivität
function logAktivitaet($benutzerID, $aktivitaet)
{
    global $conn;

    $zeitpunkt = date('Y-m-d H:i:s');
    $sql = "INSERT INTO aktivitaetsprotokoll (benutzer_id, aktivitaet, zeitpunkt) VALUES ('$benutzerID', '$aktivitaet', '$zeitpunkt')";

    if ($conn->query($sql) === TRUE) {
        echo "Aktivität erfolgreich protokolliert.";
    } else {
        echo "Fehler beim Protokollieren der Aktivität: " . $conn->error;
    }
}

// Funktion zum Abrufen des Aktivitätsprotokolls
function getAktivitaetsprotokoll()
{
    global $conn;

    // Anzahl der Einträge pro Seite
    $eintraegeProSeite = 5;

    // Aktuelle Seite aus der URL lesen
    $seite = isset($_GET['seite']) ? $_GET['seite'] : 1;

    // Offset berechnen
    $offset = ($seite - 1) * $eintraegeProSeite;

    $sql = "SELECT a.*, b.benutzername FROM aktivitaetsprotokoll a INNER JOIN benutzer b ON a.benutzer_id = b.id ORDER BY a.zeitpunkt DESC LIMIT $offset, $eintraegeProSeite";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $benutzerID = $row['benutzer_id'];
            $benutzername = $row['benutzername'];
            $aktivitaet = $row['aktivitaet'];
            $zeitpunkt = date('d.m.Y H:i:s', strtotime($row['zeitpunkt']));

            echo '<div class="card mb-2">';
            echo '<div class="card-body">';
            echo '<h6 class="card-title">' . $zeitpunkt . '</h6>';
            echo '<p class="card-text">Benutzer ID: ' . $benutzerID . ' - Benutzername: ' . $benutzername . '</p>';

            // Hier wird das Genre nur für Benutzer mit ID 1 angezeigt
            echo '<p class="card-text">Aktivität: ' . $aktivitaet . '</p>';

            echo '</div>';
            echo '</div>';
        }

        // Pagination erstellen
        $sql = "SELECT COUNT(*) as total FROM aktivitaetsprotokoll";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $gesamtEintraege = $row['total'];
        $gesamtSeiten = ceil($gesamtEintraege / $eintraegeProSeite);

        echo '<ul class="pagination">';
        for ($i = 1; $i <= $gesamtSeiten; $i++) {
            echo '<li class="page-item' . ($i == $seite ? ' active' : '') . '"><a class="page-link" href="?seite=' . $i . '">' . $i . '</a></li>';
        }
        echo '</ul>';
    } else {
    }
}
?>