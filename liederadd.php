<?php
include 'header.php';
include 'datenbank.php';
include 'auth.php';

// Überprüfen, ob das Formular zum Hinzufügen oder Bearbeiten abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        // Hinzufügen eines neuen Lieds
        if ($_POST["action"] == "add") {
            $name = $_POST["name"];
            $autor = $_POST["autor"];
            $ton = $_POST["ton"];
            $pdf_attachment = '';

            // Überprüfen, ob ein PDF-Anhang hochgeladen wurde
            if (isset($_FILES['pdf_attachment']) && $_FILES['pdf_attachment']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "pdf/";
                $target_file = $target_dir . basename($_FILES["pdf_attachment"]["name"]);
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Überprüfen, ob die Datei ein echtes PDF ist
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['pdf_attachment']['tmp_name']);
                if ($mime != 'application/pdf') {
                    echo "Die Datei ist kein PDF.";
                    $uploadOk = 0;
                }

                // Überprüfen, ob $uploadOk aufgrund eines Fehlers auf 0 gesetzt ist
                if ($uploadOk == 0) {
                    echo "Die Datei wurde nicht hochgeladen.";
                } else {
                    if (move_uploaded_file($_FILES["pdf_attachment"]["tmp_name"], $target_file)) {
                        echo "Die Datei " . htmlspecialchars(basename($_FILES["pdf_attachment"]["name"])) . " wurde erfolgreich hochgeladen.";
                        $pdf_attachment = basename($_FILES["pdf_attachment"]["name"]);
                    } else {
                        echo "Es gab einen Fehler beim Hochladen Ihrer Datei.";
                    }
                }
            }

            $hinzugefuegt_am = isset($_POST["hinzugefuegt_am"]) ? $_POST["hinzugefuegt_am"] : date("Y-m-d H:i:s");

            // SQL-Statement zum Hinzufügen des Lieds in die Datenbank
            $sql = "INSERT INTO lieder (name, autor, ton, pdf_attachment, hinzugefuegt_am) VALUES ('$name', '$autor', '$ton', '$pdf_attachment', '$hinzugefuegt_am')";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Lied wurde erfolgreich hinzugefügt.</p>";
            } else {
                echo "Fehler: " . $sql . "<br>" . $conn->error;
            }
        }
        // Bearbeiten eines vorhandenen Lieds
        elseif ($_POST["action"] == "edit") {
            $id = $_POST["editId"];
            $name = $_POST["editName"];
            $autor = $_POST["editAutor"];
            $ton = $_POST["editTon"];

            // Überprüfen, ob ein PDF-Anhang hochgeladen wurde
            if (isset($_FILES['editPdfAttachment']) && $_FILES['editPdfAttachment']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "pdf/";
                $target_file = $target_dir . basename($_FILES["editPdfAttachment"]["name"]);
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Überprüfen, ob die Datei ein echtes PDF ist
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['editPdfAttachment']['tmp_name']);
                if ($mime != 'application/pdf') {
                    echo "Die Datei ist kein PDF.";
                    $uploadOk = 0;
                }

                // Überprüfen, ob $uploadOk aufgrund eines Fehlers auf 0 gesetzt ist
                if ($uploadOk == 0) {
                    echo "Die Datei wurde nicht hochgeladen.";
                } else {
                    if (move_uploaded_file($_FILES["editPdfAttachment"]["tmp_name"], $target_file)) {
                        echo "Die Datei " . htmlspecialchars(basename($_FILES["editPdfAttachment"]["name"])) . " wurde erfolgreich hochgeladen.";
                        $pdf_attachment = basename($_FILES["editPdfAttachment"]["name"]);
                    } else {
                        echo "Es gab einen Fehler beim Hochladen Ihrer Datei.";
                    }
                }
            } else {
                $pdf_attachment = $_POST["editPdfAttachmentExisting"];
            }

            // SQL-Statement zum Aktualisieren des Lieds in der Datenbank
            $sql = "UPDATE lieder SET name='$name', autor='$autor', ton='$ton', pdf_attachment='$pdf_attachment' WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Lied wurde erfolgreich aktualisiert.</p>";
            } else {
                echo "Fehler: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

include 'header.php';
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
                <div class="content">
                    <h1 class="h2 mb-0 text-gray-800">Lieder hinzufügen</h1>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor:</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                        </div>
                        <div class="mb-3">
                            <label for="ton" class="form-label">Tonart:</label>
                            <input type="text" class="form-control" id="ton" name="ton" required>
                        </div>
                        <div class="mb-3">
                            <label for="pdf_attachment" class="form-label">PDF-Anhang:</label>
                            <input type="file" class="form-control" id="pdf_attachment" name="pdf_attachment"
                                accept="application/pdf" required>
                        </div>
                        <div class="mb-3">
                            <label for="hinzugefuegt_am" class="form-label">Hinzugefügt am:</label>
                            <input type="datetime-local" class="form-control" id="hinzugefuegt_am"
                                name="hinzugefuegt_am">
                        </div>
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary">Lied hinzufügen</button>
                    </form>

                    <h2>Liederliste</h2>
                    <?php
                    // SQL-Statement zum Abrufen der Lieder aus der Datenbank mit deutschem Datums- und Uhrzeitformat
                    $sql = "SELECT id, name, autor, ton, pdf_attachment, DATE_FORMAT(STR_TO_DATE(hinzugefuegt_am, '%Y-%m-%d %H:%i:%s'), '%d.%m.%Y') AS hinzugefuegt_am_deutsch FROM lieder ORDER BY hinzugefuegt_am DESC";
                    $result = $conn->query($sql);

                    // Überprüfen, ob Zeilen in der Abfrageergebnismenge vorhanden sind
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $pdfPath = 'pdf/' . $row["pdf_attachment"];
                            $pdfButton = '';

                            // Überprüfen, ob ein PDF-Anhang vorhanden ist
                            if (!empty($row["pdf_attachment"])) {
                                $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-primary'>PDF öffnen</a>";
                            }

                            $currentDate = date("d.m.Y");
                            $liedClass = ($row["hinzugefuegt_am_deutsch"] == $currentDate) ? "current-lied" : "";

                            echo "<div class='lied-container $liedClass'>
                                    <h3>{$row["name"]}</h3>
                                    <p><strong>Autor:</strong> {$row["autor"]}</p>
                                    <p><strong>Tonart:</strong> {$row["ton"]}</p>
                                    <p>$pdfButton</p>
                                    <p><strong>Hinzugefügt am:</strong> {$row["hinzugefuegt_am_deutsch"]}</p>
                                  </div>";
                            echo "<hr class='lied-separator'>";
                        }
                    } else {
                        echo "<p>Keine Lieder gefunden.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .current-lied {
        background-color: #c8e6c9;
        padding: 10px;
        border-radius: 5px;
    }

    .lied-separator {
        border-top: 1px solid #ddd;
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>