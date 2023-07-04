<?php
include 'header.php';
include 'datenbank.php';
include 'auth.php';
?>

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
        <div id="content">

            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="main">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h2 mb-0 text-gray-800">Lieder</h1>
                    </div>
                    <div class="content">
                        <!-- Page Heading -->
                        <p>Auf dieser Seite hast du einen Überblick über deine <b>Lieder</b></p>
                        <?php
                        include 'datenbank.php';
                        if (isset($_POST["lied_id"])) {
                            $liedId = $_POST["lied_id"];

                            // SQL-Statement zum Löschen des Eintrags in der Tabelle "abspielungen"
                            $deleteAbspielungenSQL = "DELETE FROM abspielungen WHERE lieder_id = $liedId";

                            // Führe das SQL-Statement aus, um den Eintrag zu löschen
                            if ($conn->query($deleteAbspielungenSQL) === TRUE) {
                                // Der Eintrag wurde erfolgreich gelöscht, jetzt kannst du den Datensatz in der Tabelle "lieder" löschen
                                $deleteLiedSQL = "DELETE FROM lieder WHERE id = $liedId";

                                if ($conn->query($deleteLiedSQL) === TRUE) {
                                    echo "<p>Lied wurde erfolgreich gelöscht.</p>";
                                } else {
                                    echo "Fehler: " . $deleteLiedSQL . "<br>" . $conn->error;
                                }
                            } else {
                                echo "Fehler: " . $deleteAbspielungenSQL . "<br>" . $conn->error;
                            }
                        }



                        $sql = "SELECT id, name, autor, ton, pdf_attachment, DATE_FORMAT(hinzugefuegt_am, '%d.%m.%Y') AS hinzugefuegt_am FROM lieder ORDER BY name ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo "<table id='lieder-table' class='table-responsive'>";
                            echo "<thead><tr><th>Name</th><th>Autor</th><th>Tonart</th><th>Datei</th><th>Aktionen</th><th>Datum</th></tr></thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["autor"] . "</td>";
                                echo "<td>" . $row["ton"] . "</td>";
                                echo "<td>";
                                if (!empty($row["pdf_attachment"])) {
                                    $pdfPath = "pdf/" . $row["pdf_attachment"];
                                    echo "<a style='font-size: 12px' href='$pdfPath' target='_blank' class='btn btn-sm btn-primary'>PDF öffnen</a>";
                                } else {
                                    echo "-";
                                }
                                echo "</td>";
                                echo "<td>
                <form method='post' action='lieder.php' onsubmit='return confirm(\"Möchtest du dieses Lied wirklich löschen?\")'>
                    <input type='hidden' name='lied_id' value='" . $row["id"] . "'>
                    <button type='submit' style='font-site:12px' class='btn btn-sm btn-danger'>Löschen</button>
                </form>
            </td>";
                                echo "<td>" . $row["hinzugefuegt_am"] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "Keine Lieder gefunden.";
                        }
                        $conn->close();
                        ?>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#lieder-table').DataTable();
                        });
                    </script>
                </div>

            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
</div>