<?php
include 'datenbank.php';
include 'auth.php';
?>


<?php
include 'datenbank.php';

$heute = date("Y-m-d"); // aktuelles Datum im Format 'Y-m-d'

$sql = "SELECT * FROM lieder_datum 
        JOIN lieder ON lieder_datum.lied_id = lieder.id 
        WHERE datum >= '$heute' 
        ORDER BY datum ASC";

$result = $conn->query($sql);

// Überprüfen, ob die Abfrage erfolgreich war
if ($result === false) {
    die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
}

// Daten für die Liste erstellen
$listItems = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datum = date("d.m.Y", strtotime($row["datum"])); // Hier die Änderung
        $class = ($datum == date("d.m.Y")) ? "text-success" : ""; // Hervorhebung für das aktuelle Datum
        $listItems .= "<li class='py-2 {$class}'>" . $row["name"] . " - " . $datum . "</li>";
        $listItems .= "<hr class='my-1'>"; // Trennstrich
    }
} else {
    $listItems = "<li>Keine Daten vorhanden.</li>";
}

// Datenbankverbindung schließen
$conn->close();
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Anstehende Lieder</div>
                                            <div class="scrollable-list">
                                                <ul class="list-unstyled">
                                                    <?php echo $listItems; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Area Chart -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <!--<div class="card shadow mb-4">-->
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Letzte angelegte Lieder</div>
                                            <!-- Card Header - Dropdown -->
                                            <!--<div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Letzte angelegte Lieder</h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>-->
                                        </div>
                                    </div>
                                </div>

                                <div class="scrollable-list">
                                    <style>
                                        .scrollable-list {
                                            max-height: 400px;
                                            overflow-y: auto;
                                        }
                                    </style>
                                    <!-- Card Body -->

                                    <?php
                                    include 'datenbank.php';

                                    // SQL-Abfrage ausführen, um die Liederdaten zu erhalten
                                    $sql = "SELECT name, autor, hinzugefuegt_am FROM lieder ORDER BY hinzugefuegt_am DESC";
                                    $result = $conn->query($sql);

                                    // Überprüfen, ob die Abfrage erfolgreich war
                                    if ($result === false) {
                                        die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
                                    }
                                    ?>
                                    <div class="table-container">
                                        <div class="mobile-dropdowns">
                                            <label for="autor-select">Autor</label>
                                            <select id="autor-select"
                                                onchange="toggleColumnVisibility('autor-select', 'autor-column')">
                                                <option value="visible">Anzeigen</option>
                                                <option value="hidden" selected>Ausblenden</option>
                                            </select>
                                            <label for="benutzer-select">Benutzer</label>
                                            <select id="benutzer-select"
                                                onchange="toggleColumnVisibility('benutzer-select', 'benutzer-column')">
                                                <option value="visible">Anzeigen</option>
                                                <option value="hidden" selected>Ausblenden</option>
                                            </select>
                                        </div>
                                        <div class="table table-borderless">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th class="optional-column autor-column">Autor</th>
                                                        <th>Hinzugefügt</th>
                                                        <th class="optional-column benutzer-column">Benutzer</th>
                                                    </tr>
                                                </thead>
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
                <td>" . $row["hinzugefuegt_am"] . "</td>
                <td class='optional-column benutzer-column'>" . $benutzername . "</td>
            </tr>
            <tr>
                <td colspan='4'><hr class='my-1'></td>
            </tr>";
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
                                </div>
                            </div>
                        </div>







                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Lieder (Gesamt)</div>
                                            <div id="chart"></div>
                                        </div>

                                    </div>
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
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Gesamtzahl der Abspielungen</div>
                                            <div class="scrollable-list">
                                                <ul class="list-unstyled">
                                                    <?php
                                                    // SQL-Abfrage ausführen, um die Gesamtzahl der Abspielungen der Lieder zu erhalten
                                                    $sql = "SELECT lieder.name, COALESCE(abspielungen.gesamt_abspielungen, 0) AS gesamt_abspielungen
            FROM lieder
            LEFT JOIN abspielungen ON lieder.id = abspielungen.lieder_id";
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

        // Datenbankverbindung schließen
        $conn->close();

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