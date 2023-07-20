<?php
include 'auth.php';
include 'datenbank.php';

// Überprüfen, ob das Formular zum Hinzufügen oder Bearbeiten abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        // Hinzufügen eines neuen Lieds
        if ($_POST["action"] == "add") {
            $name = $_POST["name"];
            $tags = $_POST["tags"];
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

                // Datum aus dem Formular erfassen
                $datum = $_POST["hinzugefuegt_am"];
                // Das erfasste Datum in das richtige Format konvertieren (von d.m.Y zu Y-m-d)
                $hinzugefuegt_am = date("Y-m-d", strtotime($datum));

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

            if (isset($_SESSION['benutzername'])) {
                $benutzername = $_SESSION['benutzername'];

                // Überprüfen, ob der Benutzer in der Datenbank vorhanden ist und die entsprechende benutzer_id abrufen
                $benutzerQuery = "SELECT id FROM benutzer WHERE benutzername = '$benutzername'";
                $benutzerResult = $conn->query($benutzerQuery);

                if ($benutzerResult->num_rows > 0) {
                    $benutzerRow = $benutzerResult->fetch_assoc();
                    $benutzer_id = $benutzerRow['id'];
                } else {
                    // handle the case when the user does not exist in the database
                    echo "Benutzer existiert nicht.";
                    exit();
                }
            } else {
                // handle the case when 'benutzername' does not exist in the session
                echo "Benutzer ist nicht angemeldet.";
                exit();
            }

            // Abspielung des Liedes zählen und in die Tabelle "abspielungen" einfügen
            $abspielungenQuery = "INSERT INTO abspielungen (lieder_id, gesamt_abspielungen)
VALUES ('$id', 1)
ON DUPLICATE KEY UPDATE gesamt_abspielungen = gesamt_abspielungen + 1";

            $conn->query($abspielungenQuery);



            $sql = "INSERT INTO lieder (name, autor, ton, pdf_attachment, hinzugefuegt_am, benutzer_id, tags)
            VALUES ('$name', '$autor', '$ton', '$pdf_attachment', '$hinzugefuegt_am', '$benutzer_id', '$tags')";

            if ($conn->query($sql) === TRUE) {
                $id = $conn->insert_id; // Abrufen der ID des gerade hinzugefügten Liedes

                // Überprüfen, ob die ID erfolgreich abgerufen wurde
                if ($id) {
                    echo "<p>Lied wurde erfolgreich hinzugefügt.</p>";

                    // Abspielung des Liedes zählen und in die Tabelle "abspielungen" einfügen
                    $abspielungenQuery = "INSERT INTO abspielungen (lieder_id, gesamt_abspielungen)
                                          VALUES ('$id', 1)
                                          ON DUPLICATE KEY UPDATE gesamt_abspielungen = gesamt_abspielungen + 1";

                    $conn->query($abspielungenQuery);
                } else {
                    echo "Fehler beim Abrufen der ID des hinzugefügten Liedes.";
                }
            }



        }
        // Bearbeiten eines vorhandenen Lieds
        elseif ($_POST["action"] == "edit") {
            $id = $_POST["editId"];
            $name = $_POST["editName"];
            $autor = $_POST["editAutor"];
            $ton = $_POST["editTon"];
            $tags = $_POST["tags"];
            $datum = $_POST["editHinzugefuegt_am"];

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
            $sql = "UPDATE lieder SET name='$name', autor='$autor', tags='$tags', ton='$ton', pdf_attachment='$pdf_attachment', hinzugefuegt_am='$datum' WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Lied wurde erfolgreich aktualisiert.</p>";
            } else {
                echo "Fehler: " . $sql . "<br>" . $conn->error;
            }
        }
    }
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

    if (isset($_POST["lied"]) && isset($_POST["datum"])) {
        // Code zum Einfügen des Liedes in die lieder_datum-Tabelle

        // Abrufen der Lied-ID
        $liedId = $_POST["lied"];

        // Aktualisieren der Abspielungen in der Tabelle "abspielungen"
        $abspielungenQuery = "INSERT INTO abspielungen (lieder_id, gesamt_abspielungen)
                              VALUES ('$liedId', 1)
                              ON DUPLICATE KEY UPDATE gesamt_abspielungen = gesamt_abspielungen + 1";

        $conn->query($abspielungenQuery);
    }

}



// Überprüfen, ob ein POST-Request gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Die ausgewählten Checkbox-Werte erhalten
    $checkboxName = isset($_POST["checkboxName"]) ? 1 : 0;
    $checkboxAutor = isset($_POST["checkboxAutor"]) ? 1 : 0;
    $checkboxTonart = isset($_POST["checkboxTonart"]) ? 1 : 0;
    $checkboxDatei = isset($_POST["checkboxDatei"]) ? 1 : 0;
    $checkboxBenutzername = isset($_POST["checkboxBenutzername"]) ? 1 : 0;
    $checkboxAktionen = isset($_POST["checkboxAktionen"]) ? 1 : 0;
    $checkboxDatum = isset($_POST["checkboxDatum"]) ? 1 : 0;
    $checkboxGesamtAbspielungen = isset($_POST["checkboxGesamtAbspielungen"]) ? 1 : 0;
    $checkboxTags = isset($_POST["checkboxTags"]) ? 1 : 0;

    // Tabellenname und Primärschlüsselwert (Beispiel)
    $tableName = "Benutzereinstellungen";
    $primaryKeyValue = 1;

    // SQL-Update-Abfrage erstellen
    $sql = "UPDATE $tableName SET Wert = CONCAT('$checkboxName', ',', '$checkboxAutor', ',', '$checkboxTonart', ',', '$checkboxDatei', ',', '$checkboxBenutzername', ',', '$checkboxAktionen', ',', '$checkboxDatum', ',', '$checkboxGesamtAbspielungen', ',', '$checkboxTags') WHERE EinstellungsID = $primaryKeyValue";

    // Update-Abfrage ausführen
    if ($conn->query($sql) === TRUE) {
        echo "Die Werte wurden erfolgreich in der Datenbank aktualisiert.";
    } else {
        echo "Fehler beim Aktualisieren der Werte in der Datenbank: " . $conn->error;
    }
}


?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

<div id="wrapper">
    <?php include 'menu.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <?php include 'topbar.php'; ?>
        <!-- Main Content -->
        <div id="content ">
            <!-- Begin Page Content -->
            <div class="container-fluid p-4 mb-5">
                <div class="row">
                    <div class="p-2 col-md-3 col-lg-3 col-xl-3 card card-body">


                                <h3>Lieder zu einem Datum hinzufügen</h3>

 

                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="lied" class="form-label">Lied auswählen:</label>
                                <input class="form-control" type="text" id="lied" name="lied" list="liederList"
                                    required>
                                <datalist id="liederList">
                                    <?php
                                    // Lieder aus der Datenbank abrufen und Duplikate entfernen
                                    $sql = "SELECT DISTINCT name FROM lieder";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["name"] . "'>";
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="datum" class="form-label">Datum:</label>
                                <input type="date" class="form-control" id="datum" name="datum" required>
                            </div>
                            <button type="submit" class="btn btn-primary" onclick="location.reload()">Zu Datum
                                hinzufügen</button>
                        </form>
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
                            <div class="mb-3">
                                <label for="hinzugefuegt_am" class="form-label">Hinzugefügt am:</label>
                                <input type="date" class="form-control" id="hinzugefuegt_am" name="hinzugefuegt_am"
                                    value="<?php echo date('Y-m-d'); ?>" required>

                            </div>
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags:</label>
                                <input type="text" class="form-control" name="tags" id="editTags">
                                <small>Gib Tags durch Kommas getrennt ein (z.B. Kategorie1, Kategorie2).</small>
                            </div>
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn btn-primary">Lied hinzufügen</button>
                        </form>



                    
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["lied"]) && isset($_POST["datum"])) {
                                $liedId = $_POST["lied"];
                                $datum = $_POST["datum"];

                                $sql = "INSERT INTO lieder_datum (lied_id, datum) VALUES ('$liedId', '$datum')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "<p>Lied wurde erfolgreich zu Datum hinzugefügt.</p>";
                                } else {
                                    echo "Fehler: " . $sql . "<br>" . $conn->error;
                                }
                            }
                        }
                        ?>

                    </div>
                    <script>
                        $(document).ready(function () {
                            $('#liederTable').DataTable({
                                "paging": true,
                                "searching": true,
                                "responsive": true
                            });

                        });
                    </script>

                    <div class="col-md-9 col-lg-9 col-xl-9 card card-body"">
                        <div class=" content">



                        <h2>Liederliste</h2>
                  <div class="dropdown pt-2 pb-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="columnDropdown"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Spalten auswählen
    </button>
<div class="dropdown-menu" aria-labelledby="columnDropdown">
    <form method="POST" action="liederadd.php">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxName" id="checkboxName" data-column="0"
                checked>
            <label class="form-check-label" for="checkboxName">
                Name
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxAutor" id="checkboxAutor" data-column="1"
                checked>
            <label class="form-check-label" for="checkboxAutor">
                Autor
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxTonart" id="checkboxTonart" data-column="2"
                checked>
            <label class="form-check-label" for="checkboxTonart">
                Tonart
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxDatei" id="checkboxDatei" data-column="3"
                checked>
            <label class="form-check-label" for="checkboxDatei">
                Datei
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxBenutzername" id="checkboxBenutzername"
                data-column="4" checked>
            <label class="form-check-label" for="checkboxBenutzername">
                Benutzername
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxKategorie" id="checkboxKategorie"
                data-column="5" checked>
            <label class="form-check-label" for="checkboxKategorie">
                Kategorie
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxAktionen" id="checkboxAktionen"
                data-column="6" checked>
            <label class="form-check-label" for="checkboxAktionen">
                Aktionen
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxDatum" id="checkboxDatum" data-column="7"
                checked>
            <label class="form-check-label" for="checkboxDatum">
                Datum
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkboxGesamtAbspielungen" id="checkboxGesamtAbspielungen"
                data-column="8" checked>
            <label class="form-check-label" for="checkboxGesamtAbspielungen">
                Gesamt Abspielungen
            </label>
        </div>
    </form>
</div>
</div>


                        <div class="table-responsive">

                        <table id="liederTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Autor</th>
                                    <th>Tonart</th>
                                    <th>Datei</th>
                                    <th>Benutzername</th>
                                    <th>Kategorien</th>
                                    <th>Aktionen</th>
                                    <th>Datum</th>
                                    <th>Gesamt Abspielungen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // SQL-Statement zum Abrufen der Lieder aus der Datenbank mit deutschem Datums- und Uhrzeitformat
                                $sql = "SELECT lieder.id, lieder.name, lieder.autor, lieder.ton, lieder.pdf_attachment, benutzer.benutzername, lieder.tags, COALESCE(DATE_FORMAT(lieder_datum.datum, '%d.%m.%Y'), DATE_FORMAT(lieder.hinzugefuegt_am, '%d.%m.%Y')) AS datum, COALESCE(abspielungen.gesamt_abspielungen, 0) AS gesamt_abspielungen
        FROM lieder
        LEFT JOIN benutzer ON lieder.benutzer_id = benutzer.id
        LEFT JOIN lieder_datum ON lieder.id = lieder_datum.lied_id
        LEFT JOIN (SELECT lieder_id, COUNT(*) AS gesamt_abspielungen FROM abspielungen GROUP BY lieder_id) AS abspielungen ON lieder.id = abspielungen.lieder_id";

                                $result = $conn->query($sql);

                                // Überprüfen, ob Zeilen in der Abfrageergebnismenge vorhanden sind
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $pdfPath = 'pdf/' . $row["pdf_attachment"];
                                        $pdfButton = '';

                                        // Überprüfen, ob ein PDF-Anhang vorhanden ist
                                        if (!empty($row["pdf_attachment"])) {
                                            $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-sm btn-primary'>PDF</a>";
                                        }

                                        // Tags als Badges generieren
                                        $tags = explode(",", $row["tags"]);
                                        $tagBadges = "";
                                        foreach ($tags as $tag) {
                                            $tagBadges .= "<span class='badge bg-secondary'>$tag</span> ";
                                        }

                                        echo "<tr>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["autor"] . "</td>
                    <td>" . $row["ton"] . "</td>
                    <td>$pdfButton</td>
                    <td>" . $row["benutzername"] . "</td>
                    <td>" . $tagBadges . "</td>
                    <td>
                    <button type='button' class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row["id"] . "' data-name='" . $row["name"] . "' data-autor='" . $row["autor"] . "' data-ton='" . $row["ton"] . "' data-pdf='" . $row["pdf_attachment"] . "' data-tags='" . $row["tags"] . "'>Bearbeiten</button>
                    </td>
                    <td>" . $row["datum"] . "</td>
                    <td>" . $row["gesamt_abspielungen"] . "</td>
                </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>Keine Lieder gefunden.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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
                                                <label for="editTags" class="form-label">Tags</label>
                                                <input type="text" class="form-control" id="editTagsModal" name="tags"
                                                    value="">
                                            </div>

                                            <div class="mb-3">
                                                <label for="editPdfAttachment" class="form-label">PDF-Anhang:</label>
                                                <input type="file" class="form-control" id="editPdfAttachment"
                                                    name="editPdfAttachment" accept="application/pdf">
                                                <input type="hidden" id="editPdfAttachmentExisting"
                                                    name="editPdfAttachmentExisting">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editHinzugefuegt_am" class="form-label">Datum:</label>
                                                <input type="date" class="form-control" id="editHinzugefuegt_am"
                                                    name="editHinzugefuegt_am" required>
                                            </div>
                                            <input type="hidden" id="editId" name="editId">
                                            <input type="hidden" name="action" value="edit">
                                            <button type="submit" class="btn btn-primary">Lied aktualisieren</button>
                                        </form>
                                        <form id="deleteForm" method="post" action="liederadd.php"
                                            onsubmit='return confirm("Möchtest du dieses Lied wirklich löschen?")'>
                                            <input type="hidden" name="lied_id" id="deleteLiedId">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" style="font-size:12px"
                                                class="btn btn-danger">Löschen</button>
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
            // JavaScript-Code, um die Lied-ID im Modal zu setzen und die Seite neu zu laden
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var liedId = button.data('id');
                $('#deleteLiedId').val(liedId);
            });

            // JavaScript-Code, um die Seite nach dem Löschen neu zu laden
            $('#deleteForm').submit(function () {
                // Fügen Sie hier Code hinzu, um die Lied-ID zu löschen und die Seite neu zu laden
                location.reload();
            });
        </script>

        <script>
       $(document).ready(function () {
    var table = $('#liederTable').DataTable();

    // Funktion zum Speichern der Checkbox-Zustände im Local Storage
    function saveColumnSelection() {
        var selectedColumns = [];
        $('.dropdown-menu input[type="checkbox"]').each(function () {
            selectedColumns.push({
                id: $(this).attr('id'),
                checked: $(this).is(':checked')
            });
        });
        localStorage.setItem('selectedColumns', JSON.stringify(selectedColumns));
    }

    // Funktion zum Laden der Checkbox-Zustände aus dem Local Storage
    function loadColumnSelection() {
        var selectedColumns = JSON.parse(localStorage.getItem('selectedColumns'));
        if (selectedColumns) {
            selectedColumns.forEach(function (column) {
                $('#' + column.id).prop('checked', column.checked);
                var columnIndex = $('#' + column.id).attr('data-column');
                var column = table.column(columnIndex);
                column.visible(column.checked);
            });
        }
    }

    $('.dropdown-menu input[type="checkbox"]').on('change', function () {
        var column = table.column($(this).attr('data-column'));
        column.visible($(this).is(':checked'));
        saveColumnSelection();
    });

    // Checkbox-Zustände beim Laden der Seite wiederherstellen
    loadColumnSelection();
});

        </script>

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
                var datum = button.getAttribute('data-datum')

                // Liedinformationen im Modal setzen
                var editForm = document.getElementById('editForm');
                editForm.setAttribute('action', 'liederadd.php');
                document.getElementById('editId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editAutor').value = autor;
                document.getElementById('editTon').value = ton;
                document.getElementById('editTagsModal').value = button.getAttribute('data-tags');



                document.getElementById('editPdfAttachmentExisting').value = pdf;
                document.getElementById('editHinzugefuegt_am').value = datum;

            });
        </script>
    </div>
</div>
<style>
    @media only screen and (max-width: 850px) {
        div.dataTables_wrapper div.dataTables_filter input {
            width: 100px !important;
        }
</style>

  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
        <script>
            $(function () {
                // Autocomplete für Lied
                $("#lied").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "autocomplete.php",
                            type: "GET",
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        var selectedLied = ui.item.value;
                        $("#lied").val(selectedLied);
                        return false; // Prevent default selection behavior
                    }
                });

                // Submit-Handler für das Formular
                $("form").submit(function (event) {
                    event.preventDefault(); // Prevent form submission

                    // Lied und Datum auslesen
                    var selectedLied = $("#lied").val();
                    var selectedDatum = $("#datum").val();

                    // Debug-Ausgabe (kann entfernt werden)
                    console.log("Ausgewähltes Lied: " + selectedLied);
                    console.log("Ausgewähltes Datum: " + selectedDatum);

                    // Hier wird der Code zum Speichern der Daten in der Datenbank eingefügt
                    $.ajax({
                        url: "save.php", // Pfad zur PHP-Datei für das Speichern der Daten
                        type: "POST",
                        data: {
                            lied: selectedLied,
                            datum: selectedDatum
                        },
                        success: function (response) {
                            // Erfolgsbehandlung
                            console.log("Daten erfolgreich gespeichert.");
                            // Hier kannst du weitere Aktionen ausführen oder eine Bestätigungsmeldung anzeigen
                        },
                        error: function (xhr, status, error) {
                            // Fehlerbehandlung
                            console.log("Fehler beim Speichern der Daten.");
                            // Hier kannst du entsprechend reagieren und eine Fehlermeldung anzeigen
                        }
                    });

                    // Optional: Formular zurücksetzen
                    $("#lied").val("");
                    $("#datum").val("");
                });
            });
        </script>

</body>

</html>
