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

            // SQL-Statement zum Hinzufügen des Lieds in die Datenbank
            $sql = "INSERT INTO lieder (name, autor, ton, pdf_attachment, hinzugefuegt_am) VALUES ('$name', '$autor', '$ton', '$pdf_attachment', NOW())";

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
                    <h1>Lieder hinzufügen</h1>
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
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary">Lied hinzufügen</button>
                    </form>

                    <h2>Liederliste</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Autor</th>
                                <th>Tonart</th>
                                <th>Datei</th>
                                <th>Aktionen</th>
                                <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // SQL-Statement zum Abrufen der Lieder aus der Datenbank
                            $sql = "SELECT * FROM lieder";
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

                                    echo "<tr>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["autor"] . "</td>
                                    <td>" . $row["ton"] . "</td>
                                    <td>$pdfButton</td>
                                    <td>
                                        <button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row["id"] . "' data-name='" . $row["name"] . "' data-autor='" . $row["autor"] . "' data-ton='" . $row["ton"] . "' data-pdf='" . $row["pdf_attachment"] . "'>Bearbeiten</button>
                                    </td>
                                    <td>" . $row["hinzugefuegt_am"] . "</td>
                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Keine Lieder gefunden.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Modal für die Bearbeitung -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Lied bearbeiten</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editForm" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="editName" class="form-label">Name:</label>
                                            <input type="text" class="form-control" id="editName" name="editName"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editAutor" class="form-label">Autor:</label>
                                            <input type="text" class="form-control" id="editAutor" name="editAutor"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editTon" class="form-label">Tonart:</label>
                                            <input type="text" class="form-control" id="editTon" name="editTon"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editPdfAttachment" class="form-label">PDF-Anhang:</label>
                                            <input type="file" class="form-control" id="editPdfAttachment"
                                                name="editPdfAttachment" accept="application/pdf">
                                            <input type="hidden" id="editPdfAttachmentExisting"
                                                name="editPdfAttachmentExisting">
                                        </div>
                                        <input type="hidden" id="editId" name="editId">
                                        <input type="hidden" name="action" value="edit">
                                        <button type="submit" class="btn btn-primary">Lied aktualisieren</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal öffnen und Liedinformationen setzen
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            // Auslösendes Element abrufen
            var button = event.relatedTarget;

            // Liedinformationen aus den Datenattributen des Elements abrufen
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var autor = button.getAttribute('data-autor');
            var ton = button.getAttribute('data-ton');
            var pdf = button.getAttribute('data-pdf');

            // Liedinformationen im Modal setzen
            var editForm = document.getElementById('editForm');
            editForm.setAttribute('action', 'liederadd.php');
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editAutor').value = autor;
            document.getElementById('editTon').value = ton;
            document.getElementById('editPdfAttachmentExisting').value = pdf;
        });
    </script>
    </body>