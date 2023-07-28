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
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 font-weight-bold text-green-800 ml-2 mb-1">Statistik</h1>
                    </div>
            <div class="col-xl-12 col-md-12 mb-12">
                    <div class="card border-left-success h-50 py-1">
                        <div class="card-header shadow mb-1">
                            <div class="text-x font-weight-bold text-success text-uppercase mb-1">Wie oft wurde das Lied gespielt:</div>
                        <!--</div>-->
                <form action="" method="get">
                    <label for="zeitraum" class="custom-label">Zeitraum:</label>
                    <select name="zeitraum" id="zeitraum" onchange="this.form.submit()">
                        <option value="alle" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == 'alle' ? 'selected' : ''; ?>>Alle</option>
                        <option value="14tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '14tage' ? 'selected' : ''; ?>>Letzte 150 Tage</option>
                        <option value="30tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '30tage' ? 'selected' : ''; ?>>Letzte 365 Tage</option>
                        <option value="60tage" <?php echo isset($_GET['zeitraum']) && $_GET['zeitraum'] == '60tage' ? 'selected' : ''; ?>>Letzte 730 Tage</option>
                    </select>

                    <label for="liedname" class="custom-label flex">Liedname:</label>
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
                </div>
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

                // Pagination-Parameter
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = 10; // Anzahl der Datensätze pro Seite
                $offset = ($page - 1) * $perPage;
                $sql .= " LIMIT $perPage OFFSET $offset";

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

               <div class="card card-body mb-0" id="chart"></div>
            </div>
            <div class="mb-4"></div> <!--Leerzeile-->

            <div id="content-wrapper" class="d-flex flex-column">
    <div id="content" class="container" style="margin-bottom: 200px">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 font-weight-bold text-green-800 ml-2 mb-1">Test</h1>
        </div>
        <div class="col-xl-12 col-md-12 mb-12">
            <div class="card border-left-success shadow h-100 py-1">
                <div class="card-header shadow mb-1">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Diese Lieder könntet ihr wieder spielen:</div>
                    <form></form>
                </div>
                <div class="col">
                    <div class="scrollable-list">
                        <table class="table table-striped table-borderless">
                            <?php
                            // Ausgabe der Liedvorschläge in Form von Cards
                            foreach ($liedVorschlaege as $lied) {
                                echo "<tr><td class='card-list card-body pb-3 mb-1'>$lied</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                .scrollable-list {
                    max-height: 400px;
                    overflow-y: auto;
                }

                .sticky-header {
                    position: sticky;
                    top: -10;
                    background-color: #fff;
                    z-index: 1;
                }

                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #ffffff !important;
                }

                .custom-label {
                    margin-left: 20px;
                }
            </style>

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
<?php
            include 'footer.php';
            ?>
    </body>

</html>
