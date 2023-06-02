<?php
include 'header.php';
include 'datenbank.php';
include 'auth.php';
?>

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




                <div class="main">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h2 mb-0 text-gray-800">Lieder</h1>

                    </div>
                    <div class="content">
                        <!-- Page Heading -->

                        <p>Auf dieser Seite hast du einen Überblick über deine <b>Lieder</b></p>

                        <form method="post" action="suche.php">
                            <label for="search">Suche:</label>
                            <input type="text" id="search" name="search">
                            <button type="submit">Suchen</button>
                        </form>
                        <?php
                        include 'datenbank.php';

                        $sql = "SELECT * FROM lieder ORDER BY name ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo "<table class='table'>";
                            echo "<thead><tr><th>Name</th><th>Autor</th><th>Tonart</th><th>Datei</th></tr></thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["autor"] . "</td><td>" . $row["ton"] . "</td><td>";
                                if (!empty($row["pdf_attachment"])) {
                                    $pdfPath = $row["pdf_attachment"];
                                    echo "<a href='$pdfPath' target='_blank' class='btn btn-primary'>PDF öffnen</a>";
                                } else {
                                    echo "-";
                                }
                                echo "</td></tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "Keine Lieder gefunden";
                        }
                        $conn->close();
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
        include 'footer.php';
        ?>