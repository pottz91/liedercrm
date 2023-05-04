<form method="post" action="suche.php">
  <label for="search">Suche:</label>
  <input type="text" id="search" name="search">
  <button type="submit">Suchen</button>
</form>
PHP-Code in suche.php:

$search_term = $_POST["search"];

$sql = "SELECT * FROM lieder WHERE name LIKE '%$search_term%' OR autor LIKE '%$search_term%' OR ton LIKE '%$search_term%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
// Zeige Abfrageergebnisse wie in Ihrem ursprünglichen Code an
} else {
echo "Keine Lieder gefunden";
}
