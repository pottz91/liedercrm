<?php
include 'datenbank.php';

$searchTerm = $_GET['search']; // Suchbegriff aus der AJAX-Anfrage erhalten

// SQL-Abfrage, um die passenden Liednamen zu erhalten
$sql = "SELECT name FROM lieder WHERE name LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

if ($result === false) {
    die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
}

$liednamen = array();
while ($row = $result->fetch_assoc()) {
    $liednamen[] = $row['name'];
}

// JSON-Antwort mit den passenden Liednamen senden
header('Content-Type: application/json');
echo json_encode($liednamen);
?>