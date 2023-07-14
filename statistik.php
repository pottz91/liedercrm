<?php
include 'datenbank.php';
include 'auth.php';

// Funktion, um Liedvorschläge zu erhalten
function getLiedVorschlaege($tage)
{
    global $conn; // Zugriff auf die Datenbankverbindung

    // SQL-Abfrage, um Lieder zu erhalten, die seit $tage Tagen nicht gespielt wurden
    $sql = "SELECT name FROM lieder 
            LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id 
            WHERE lieder_datum.datum <= DATE_SUB(CURDATE(), INTERVAL $tage DAY) 
            GROUP BY lieder.id";

    $result = $conn->query($sql);

    if ($result === false) {
        die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
    }

    $liedVorschlaege = array();
    while ($row = $result->fetch_assoc()) {
        $liedVorschlaege[] = $row['name'];
    }

    return $liedVorschlaege;
}

// Aufruf der Funktion für Liedvorschläge, die nicht zwischen 30+ Tagen gespielt wurden
$liedVorschlaege = getLiedVorschlaege(30);

?>

<!DOCTYPE html>
<html lang="de">

<body>
    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <?php include 'topbar.php'; ?>
            <!-- Main Content -->
            <div id="content" class="container" style="margin-bottom: 200px">
                <form action="" method="get">
                    <label for="zeitraum">Zeitraum:</label>
                    <select name="zeitraum" id="zeitraum" onchange="this.form.submit()">
                        <option value="alle" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == 'alle' ? 'selected' : ''; ?>>Alle</option>
                        <option value="14tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '14tage' ? 'selected' : ''; ?>>Letzte 14 Tage</option>
                        <option value="30tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '30tage' ? 'selected' : ''; ?>>Letzte 30 Tage</option>
                        <option value="60tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '60tage' ? 'selected' : ''; ?>>Letzte 60 Tage</option>
                    </select>

                    <label for="liedname">Liedname:</label>
                    <select name="liedname" id="liedname">
                        <option value="">Alle</option>
                        <?php
                        // SQL-Abfrage, um die verfügbaren Liednamen zu erhalten
                        $sql = "SELECT DISTINCT name FROM lieder";
                        $result = $conn->query($sql);

                        // Überprüfen, ob die Abfrage erfolgreich war
                        if ($result === false) {
                            die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
                        }

                        // Liednamen in das Select-Feld einfügen
                        while ($row = $result->fetch_assoc()) {
                            $selected = isset($_GET['liedname']) && $_GET['liedname'] == $row['name'] ? 'selected' : '';
                            echo "<option value='" . $row['name'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>

                    <button class="btn btn-primary btn-sm" type="submit">Filtern</button>
                </form>

                <?php
                // Filter für den Zeitraum
                $zeitraum = isset($_GET['zeitraum']) ? $_GET['zeitraum'] : 'alle';

                // Filter für den Liednamen
                $liedname = isset($_GET['liedname']) ? $_GET['liedname'] : '';

                // SQL-Abfrage basierend auf dem gewählten Zeitraum und Liednamen
                $sql = "";
                if ($zeitraum == '14tage') {
                    $sql = "SELECT lieder.name, COUNT(lieder_datum.id) AS abspielungen 
                            FROM lieder 
                            LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id 
                            WHERE lieder_datum.datum >= DATE_SUB(CURDATE(), INTERVAL 150 DAY) 
                            AND (lieder.name LIKE '%$liedname%' OR '$liedname' = '')
                            GROUP BY lieder.id 
                            ORDER BY abspielungen DESC";
                } elseif ($zeitraum == '30tage') {
                    $sql = "SELECT lieder.name, COUNT(lieder_datum.id) AS abspielungen 
                            FROM lieder 
                            LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id 
                            WHERE lieder_datum.datum >= DATE_SUB(CURDATE(), INTERVAL 365 DAY) 
                            AND (lieder.name LIKE '%$liedname%' OR '$liedname' = '')
                            GROUP BY lieder.id 
                            ORDER BY abspielungen DESC";
                } elseif ($zeitraum == '60tage') {
                    $sql = "SELECT lieder.name, COUNT(lieder_datum.id) AS abspielungen 
                            FROM lieder 
                            LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id 
                            WHERE lieder_datum.datum >= DATE_SUB(CURDATE(), INTERVAL 730 DAY) 
                            AND (lieder.name LIKE '%$liedname%' OR '$liedname' = '')
                            GROUP BY lieder.id 
                            ORDER BY abspielungen DESC";
                } else {
                    $sql = "SELECT lieder.name, COUNT(lieder_datum.id) AS abspielungen 
                            FROM lieder 
                            LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id 
                            WHERE lieder.name LIKE '%$liedname%' OR '$liedname' = ''
                            GROUP BY lieder.id 
                            ORDER BY abspielungen DESC";
                }

                $result = $conn->query($sql);

                if ($result === false) {
                    die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
                }

                $chartData = array();
                while ($row = $result->fetch_assoc()) {
                    $chartData[] = array(
                        "x" => $row["name"],
                        "y" => $row["abspielungen"]
                    );
                }
                ?>

                <div class="card card-body mb-4" id="chart"></div>

                <div class="row">
                    <div class="col">
                        <h3>Diese Lieder könntet ihr wieder spielen:</h3>
                        <?php
                        // Ausgabe der Liedvorschläge in Form von Cards
                        foreach ($liedVorschlaege as $lied) {
                            echo "<div class='card card-body pb-3 mb-1'>$lied</div>";
                        }
                        ?>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const options = {
                            chart: {
                                type: 'bar',
                                height: 350
                            },
                            series: [{
                                name: 'Abspielungen',
                                data: <?php echo json_encode($chartData); ?>
                            }],
                            xaxis: {
                                type: 'category',
                            },
                        };

                        const chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>