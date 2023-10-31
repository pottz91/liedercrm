<?php
include 'datenbank.php';
include 'auth.php';
session_start();
?>

<?php
include 'datenbank.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Europe/Berlin');
$heute = date("Y-m-d");

$datumVor30Tagen = date("Y-m-d", strtotime("-30 days")); // Datum vor 30 Tagen im Format 'Y-m-d'

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
        $class = (strtotime($row["datum"]) >= strtotime($heute)) ? "text-success" : "";
        $listItems .= "<li class='py-2 {$class}'>" . $row["name"] . " - " . $datum . "</li>";
        $listItems .= "<hr class='my-1'>";
    }
} else {
    $listItems = "<li>Keine Daten vorhanden.</li>";
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
        include 'menu.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <?php
            include 'topbar.php'; ?>
            <!-- Main Content -->
            <div id="content">

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 font-weight-bold text-green-800 mb-1">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-1">
                                <div class="card-header shadow mb-1">
                                    <div class="text-x font-weight-bold text-success text-uppercase mb-1">Anstehende Lieder</div>
                                </div>
                                <div class="col mr-2">
                                    <div class="scrollable-list">
                                        <ul class="list-unstyled">
                                            <?php echo $listItems; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- Area Chart -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-1">
                                <div class="card-header shadow mb-1">
                                    <div class="text-x font-weight-bold text-success text-uppercase mb-1">Letzte angelegte Lieder</div>
                                        <label for="autor-select">Autor</label>
                                            <select id="autor-select"
                                                onchange="toggleColumnVisibility('autor-select', 'autor-column')">
                                                <option value="visible">Einblenden</option>
                                                <option value="hidden" selected>Ausblenden</option>
                                            </select>
                                        <label for="benutzer-select">Benutzer</label>
                                            <select id="benutzer-select"
                                                onchange="toggleColumnVisibility('benutzer-select', 'benutzer-column')">
                                                <option value="visible">Anzeigen</option>
                                                <option value="hidden" selected>Ausblenden</option>
                                            </select>
                                    </div>

                                    <?php
                                    include 'datenbank.php';

                                    // SQL-Abfrage ausführen, um die Liederdaten zu erhalten
                                    $sql = "SELECT name, autor, DATE_FORMAT(hinzugefuegt_am, '%d.%m.%Y') AS hinzugefuegt_am_formatted FROM lieder ORDER BY hinzugefuegt_am DESC";
                                    $result = $conn->query($sql);

                                    // Überprüfen, ob die Abfrage erfolgreich war
                                    if ($result === false) {
                                        die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
                                    }
                                    ?>

                                    <div class="table table-borderless">
                                        <div class="scrollable-list">
                                            <table class="table table-striped">
                                                <thead style="z-index 1; background-color: white"  class="sticky-header">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th class="optional-column autor-column">Autor</th>
                                                        <th>Hinzugefügt</th>
                                                        <th class="optional-column benutzer-column">Benutzer</th>
                                                    </tr>
                                                </thead>
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
                                            </style>
                                                <tbody>
                                                    <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            // Benutzername anhand der Benutzer-ID abrufen
                                                            $benutzer_id = getBenutzerId($row["autor"]);
                                                            $benutzername = getBenutzername($benutzer_id);

                                                        echo "<tr>
                                                            <td>" . $row["name"] . "</td>
                                                            <td class='optional-column autor-column'>" . $row["autor"] . "</td>
                                                            <td>" . $row["hinzugefuegt_am_formatted"] . "</td>
                                                            <td class='optional-column benutzer-column'>" . $benutzername . "</td>
                                                        </tr>
                                                        ";
                                                            }
                                                        } else {
                                                            echo "<tr>
                                                            <td colspan='4'>Keine Daten vorhanden.</td>
                                                            </tr>";
                                                        }
                                                    ?>

                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <script>
                                function toggleColumnVisibility(selectId, columnClass) {
                                    var select = document.getElementById(selectId);
                                    var optionValue = select.value;
                                    var columns = document.getElementsByClassName(columnClass);

                                    for (var i = 0; i < columns.length; i++) {
                                        if (optionValue === 'hidden') {
                                            columns[i].style.display = 'none';
                                        } else {
                                            columns[i].style.display = '';
                                        }
                                    }
                                }

                                // Initialer Aufruf der toggleColumnVisibility Funktion
                                window.addEventListener('DOMContentLoaded', function () {
                                    toggleColumnVisibility('autor-select', 'autor-column');
                                    toggleColumnVisibility('benutzer-select', 'benutzer-column');
                                });
                            </script>
                            <!--</div>-->
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-1">
                                <div class="card-header shadow mb-1">
                                    <div class="text-x font-weight-bold text-success text-uppercase mb-1">Lieder (Gesamt)</div>
                                </div>
                                <div class="col mr-2">
                                    <div id="chart"></div>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Funktion zum Abrufen des Benutzernamens basierend auf der Autor-ID
                        function getBenutzerId($autor)
                        {
                            global $conn;

                            $sql = "SELECT benutzer_id FROM lieder WHERE autor = '$autor'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                return $row["benutzer_id"];
                            } else {
                                return "";
                            }
                        }

                        function getBenutzername($benutzer_id)
                        {
                            global $conn;

                            $sql = "SELECT benutzername FROM benutzer WHERE id = '$benutzer_id'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                return $row["benutzername"];
                            } else {
                                return "";
                            }
                        }

                        ?>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-1">
                                <div class="card-header shadow mb-1">
                                    <div class="text-x font-weight-bold text-success text-uppercase mb-1">Gesamtzahl der Abspielungen</div>
                                </div>
                                <div class="col mr-2">
                                            <div class="scrollable-list">
                                                <ul class="list-unstyled">
                                                    <?php
                                                    // SQL-Abfrage ausführen, um die Gesamtzahl der Abspielungen der Lieder zu erhalten
                                                    $sql = "SELECT lieder.name, SUM(abspielungen.gesamt_abspielungen) AS gesamt_abspielungen
                                    FROM lieder
                                    LEFT JOIN abspielungen ON lieder.id = abspielungen.lieder_id
                                    GROUP BY lieder.name
                                    ORDER BY gesamt_abspielungen DESC";
                                                    $result = $conn->query($sql);

                                                    if ($result === false) {
                                                        die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
                                                    }

                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<li class='py-2'>" . $row["name"] . " - " . $row["gesamt_abspielungen"] . "</li>";
                                                        echo "<hr class='my-1'>";
                                                    }
                                                    ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>



                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php
            include 'footer.php';
            ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>

    <script>
        <?php
        // SQL-Abfrage ausführen, um die Liederdaten zu erhalten
        $sql = "SELECT DATE_FORMAT(hinzugefuegt_am, '%Y-%m-%d') AS datum, COUNT(*) AS anzahl FROM lieder GROUP BY datum";
        $result = $conn->query($sql);

        // Überprüfen, ob die Abfrage erfolgreich war
        if ($result === false) {
            die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
        }

        // Daten für das Diagramm erstellen
        $chartData = array();
        while ($row = $result->fetch_assoc()) {
            $chartData[] = array(
                "x" => $row["datum"],
                "y" => $row["anzahl"]
            );
        }

  

        // PHP-Daten an JavaScript übergeben
        echo "const chartData = " . json_encode($chartData) . ";";
        ?>

        // ApexCharts initialisieren
        document.addEventListener("DOMContentLoaded", function () {
            if (chartData.length > 0) {
                const options = {
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Anzahl der Lieder',
                        data: chartData
                    }],
                    xaxis: {
                        type: 'category', // Achsentyp auf 'category' ändern
                        labels: {
                            format: 'dd.MM.yyyy'
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        colors: ['#576bcc'] // Hier wird die Farbe der Linie auf Blau gesetzt
                    },
                    fill: {
                        colors: ['#576bcc'] // Hier wird die Farbe des Bereichs unter der Linie auf Blau gesetzt
                    },
                };

                const chart = new ApexCharts(document.querySelector('#chart'), options);
                chart.render();
            } else {
                console.error("Keine Daten für das Diagramm vorhanden.");
            }
        });
        console.log(chartData);
    </script>
