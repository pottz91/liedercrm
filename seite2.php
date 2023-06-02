<?php
include 'header.php';
include_once 'datenbank.php';
include 'auth.php';
?>

<body>
    <div class="menubar">
        <h1>Liedersammlung</h1>
        <div class="myname">
            <div class="avatar">P</div>ProJeCt
        </div>
    </div>
    <div class="main">
        <div class="menu">
            <a href="seite2.php"><img src="img/home.svg"> Start</a>
            <a href="lieder.php"><img src="img/book.svg"> Lieder</a>
            <a href="liederadd.php"><img src="img/add.svg"> Lieder hinzufügen</a>
            <a href="index.php?page=legal"><img src="img/legal.svg"> Impressum</a>
            <a href="logout.php"><i style="font-size: 22px" class="ri-logout-box-r-line">&nbsp;</i>Logout</a>
        </div>
        <div class="content">
            <h1>Herzlich willkommen</h1>
            <p>Du bist auf der Startseite!</p>


            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Anzahl der Lieder</h5>
                                <div id="chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Chart 2</h5>
                                <div id="chart2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Chart 3</h5>
                                <div id="chart3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <script>
            <?php
            // SQL-Abfrage ausführen, um die Liederdaten zu erhalten
            $sql = "SELECT COUNT(*) as count FROM lieder";
            $result = $conn->query($sql);

            // Überprüfen, ob die Abfrage erfolgreich war
            if ($result === false) {
                die("Datenbankabfrage fehlgeschlagen: " . $conn->error);
            }

            // Daten für das Diagramm erstellen
            $chartData = array();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $chartData[] = array(
                    "x" => "Gesamt",
                    "y" => $row["count"]
                );
            }

            // Datenbankverbindung schließen
            $conn->close();

            // PHP-Daten an JavaScript übergeben
            echo "const chartData = " . json_encode($chartData) . ";";
            ?>

            // ApexCharts initialisieren
            const options = {
                chart: {
                    type: 'bar'
                },
                series: [{
                    name: 'Anzahl der Lieder',
                    data: chartData
                }],
                xaxis: {
                    categories: ['Gesamt']
                }
            };

            const chart = new ApexCharts(document.querySelector('#chart'), options);
            chart.render();
        </script>
    </div>
    <div class="footer">
        (C) 2023 Visio Software
    </div>
</body>