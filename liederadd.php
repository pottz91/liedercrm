<?php include 'header.php'; ?>
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
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
            <h1>Lieder hinzufügen</h1>
            <p>Du bist auf der Lieder hinzufügen Seite</p>
            <?php
            include 'datenbank.php';

            echo '<form method="post" action="" enctype="multipart/form-data">
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
                    <input type="file" class="form-control" id="pdf_attachment" name="pdf_attachment" accept="application/pdf" required>
                        </div>
                            
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary">Lied hinzufügen</button>
                        </form>';

            // Überprüfen, ob das Formular abgeschickt wurde
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['action']) && $_POST['action'] == 'add') {
                    $name = isset($_POST["name"]) ? $_POST["name"] : "";
                    $autor = isset($_POST["autor"]) ? $_POST["autor"] : "";
                    $ton = isset($_POST["ton"]) ? $_POST["ton"] : "";
                    $pdf_filename = "";

                    if (isset($_FILES['pdf_attachment'])) {
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
                                $pdf_filename = basename($_FILES["pdf_attachment"]["name"]);
                            } else {
                                echo "Es gab einen Fehler beim Hochladen Ihrer Datei.";
                            }
                        }
                    } else {
                        echo "Keine Datei ausgewählt.";
                    }

                    // SQL-Statement zum Einfügen des neuen Lieds in die Datenbank
                    $sql = "INSERT INTO lieder (name, autor, ton, pdf_attachment) VALUES ('$name', '$autor', '$ton', '$pdf_filename')";

                    // Ausführen des SQL-Statements
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Lied wurde erfolgreich hinzugefügt.</p>";
                    } else {
                        echo "Fehler: " . $sql . "<br>" . $conn->error;
                    }
                } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
                    $id = isset($_POST["id"]) ? $_POST["id"] : "";

                    // SQL-Statement zum Löschen des Lieds aus der Datenbank
                    $sql = "DELETE FROM lieder WHERE id = $id";

                    // Ausführen des SQL-Statements
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Lied wurde erfolgreich gelöscht.</p>";
                    } else {
                        echo "Fehler: " . $sql . "<br>" . $conn->error;
                    }
                }
            }

            ?>
            <h2>Liederliste</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Autor</th>
                        <th>Tonart</th>
                        <th>Datei</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL-Statement zum Abrufen der Lieder aus der Datenbank
                    $sql = "SELECT * FROM lieder";
                    $result = $conn->query($sql);
                    // Ausgeben der Lieder in der Tabelle
                   if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $pdfPath = 'pdf/' . $row["pdf_attachment"];
                            $pdfButton = '';
                            if (!empty($row["pdf_attachment"])) {
                                $pdfButton = "<a href='$pdfPath' target='_blank' class='btn btn-primary'>PDF öffnen</a>";
                            }
                            echo "<tr><td>" . $row["name"] . "</td><td>" . $row["autor"] . "</td><td>" . $row["ton"] . "</td><td>$pdfButton</td><td><form method='post' action=''><input type='hidden' name='id' value='" . $row["id"] . "'><input type='hidden' name='action' value='delete'><button type='submit' class='btn btn-danger'>Löschen</button></form></td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Keine Lieder gefunden.</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
