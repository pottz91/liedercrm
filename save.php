<?php
include 'datenbank.php';

// Daten aus dem Formular erhalten
$lied = $_POST['lied'];
$datum = $_POST['datum'];

// SQL-Query zum Einfügen der Daten in die Datenbank
$sql = "INSERT INTO lieder_datum (lied_id, datum) SELECT id, '$datum' FROM lieder WHERE name = '$lied'";

if ($conn->query($sql) === TRUE) {
    echo "Daten erfolgreich gespeichert.";
} else {
    echo "Fehler beim Speichern der Daten: " . $conn->error;
}

// Verbindung zur Datenbank schließen
$conn->close();
?>

<!-- JavaScript zum Neuladen der Seite -->
<script>
    // Seite neu laden
    window.location.reload();
</script>