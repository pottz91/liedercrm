<?php

//Zeitzone festlegen
date_default_timezone_set('Europe/Berlin');

//Datum auf Heute im Format Y-M-D festlegen
$heute = date("Y-m-d");

//Datum vor 7 Tagen im Format 'Y-m-d'
$vor7Tagen = date("Y-m-d", strtotime("-7 days")); // Datum vor 7 Tagen im Format 'Y-m-d'

// Datum vor 30 Tagen im Format 'Y-m-d'
$datumVor30Tagen = date("Y-m-d", strtotime("-30 days")); 

$sql = "SELECT lieder.name, lieder_datum.datum
        FROM lieder
        LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id
        WHERE (lieder_datum.datum >= '$heute' OR lieder_datum.datum >= '$datumVor30Tagen')
        ORDER BY lieder_datum.datum DESC";
$result = $conn->query($sql);

// Überprüfen, ob die Abfrage erfolgreich war
if ($result === false) {
    die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
}

// Daten für die Liste erstellen
$listItems = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datum = date("d.m.Y", strtotime($row["datum"]));
        $class = "";
        
        if (strtotime($row["datum"]) >= strtotime($heute)) {
            $class = "text-success"; // Hervorhebung für das aktuelle Datum
        } elseif (strtotime($row["datum"]) >= strtotime($vor7Tagen)) {
            $class = "text-warning"; // Hervorhebung für die letzten 7 Tage (gelb)
        }
    
        $listItems .= "<li class='py-2 {$class}'>" . $row["name"] . " - " . $datum . "</li>";
        $listItems .= "<hr class='my-1'>";
    }
} else {
    $listItems = "<li>Keine Daten vorhanden.</li>";
}

?>
