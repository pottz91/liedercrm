<?php
include 'datenbank.php';
include 'auth.php';

// Code für bessere Fehlersuche
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Fügen Sie Ihre Meta-Tags und Stylesheets hier ein -->
</head>
<body>
    <div id="wrapper">
        <?php 
        include 'menu.php'; 
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <?php 
            include 'topbar.php'; 
            ?>
            <!-- Main Content -->
            <div id="content" class="container" style="margin-bottom: 50px">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 font-weight-bold text-green-800 ml-2 mb-1">Liedersuche</h1>
                </div>
                <div class="card border-left-success shadow h-flex py-1">
                    <div class="card-header shadow mb-1">
                        <!--<div class="text-x font-weight-bold text-success text-uppercase mb-1">Lieder Suche:
                        </div>-->
                        <!-- Fügen Sie hier Ihr Suchformular ein -->
                        <form method="GET" action="songsearch.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Liedtitel eingeben...">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">Suchen</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col mr-2">
                        <div class="scrollable-list">
                            <ul class="list-unstyled">
                                <?php
                                // SQL-Statement zum Abrufen der Lieder aus der Datenbank mit deutschem Datums- und Uhrzeitformat
                                $sql = "SELECT lieder.name, lieder.pdf_attachment FROM lieder";
                                $result = mysqli_query($conn, $sql);

                                if ($result === false) {
                                    die("Datenbankabfrage fehlgeschlagen: " . mysqli_error($conn));
                                }

                                // Überprüfen, ob Zeilen in der Abfrageergebnismenge vorhanden sind
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $pdfPath = 'pdf/' . $row["pdf_attachment"];
                                        $pdfButton = '';

                                        // Überprüfen, ob ein PDF-Anhang vorhanden ist
                                        if (!empty($row["pdf_attachment"])) {
                                            $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-sm btn-primary'>PDF</a>";
                                        }
                                        // Hier sollten Sie die Ergebnisse Ihrer Liedersuche ausgeben
                                        echo "<div class='d-flex justify-content-between align-items-center mr-3'>";
                                        echo "<div>" . $row["name"] . "</div>";
                                        echo $pdfButton;
                                        echo "</div>";
                                        echo "<hr class='my-1'>"; // Trennlinie hinzufügen
                                    }
                                } else {
                                    echo "<div>Keine Lieder gefunden.</div>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
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

<div id="searchResults"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Echtzeitsuche bei Eingabe im Textfeld
        $('#search').on('input', function () {
            var search = $(this).val();
            if (search.length > 2) {
                $.ajax({
                    type: "POST",

                    <?php
include 'datenbank.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT name, pdf_attachment FROM lieder WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        die("Datenbankabfrage fehlgeschlagen: " . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdfPath = 'pdf/' . $row["pdf_attachment"];
            $pdfButton = '';

            if (!empty($row["pdf_attachment"])) {
                $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-sm btn-primary'>PDF</a>";
            }

            echo "<li>" . $row["name"] . $pdfButton . "</li>";
        }
    } else {
        echo "<li>Keine Lieder gefunden.</li>";
    }
}
?>



                    //url: "search.php", // Erstellen Sie eine PHP-Datei für die Abfrage der Suchergebnisse
                    data: {search: search},
                    success: function (response) {
                        $('#searchResults').html(response);
                    }
                });
            } else {
                $('#searchResults').empty();
            }
        });
    });
</script>

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
    <?php
    include 'footer.php';
    ?>
</body>
</html>
