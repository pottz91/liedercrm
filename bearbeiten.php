<?php include 'header.php'; ?>
<?php include 'datenbank.php'; ?>
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

            <?php
            // Überprüfen, ob die ID des zu bearbeitenden Datensatzes übergeben wurde
            if (isset($_POST['id'])) {
                $id = $_POST['id'];

                // Überprüfen, ob das Formular zum Aktualisieren des Datensatzes gesendet wurde
                if (isset($_POST['action']) && $_POST['action'] === 'update') {
                    echo "Der Datensatz wurde erfolgreich aktualisiert.";
                    header("Location: lieder.php");
                    exit();
                    
                    // Die Werte aus dem Formular abrufen
                    $name = $_POST['name'];
                    $autor = $_POST['autor'];
                    $ton = $_POST['ton'];

                    // Code zum Aktualisieren des Datensatzes in der Datenbank
                    $updateSql = "UPDATE lieder SET name = '$name', autor = '$autor', ton = '$ton' WHERE id = '$id'";
                    if ($conn->query($updateSql) === TRUE) {
                        echo "Der Datensatz wurde erfolgreich aktualisiert.";
                    } else {
                        echo "Beim Aktualisieren des Datensatzes ist ein Fehler aufgetreten: " . $conn->error;
                    }
                }


                // Datenbankabfrage, um den zu bearbeitenden Datensatz abzurufen
                $sql = "SELECT * FROM lieder WHERE id = '$id'";
                $result = $conn->query($sql);

                // Überprüfen, ob ein Datensatz gefunden wurde
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Das Formular zum Bearbeiten des Datensatzes anzeigen
                    echo '<h2>Lied - Datensatz bearbeiten</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="' . $row["name"] . '" required>
                </div>
                <div class="mb-3">
                    <label for="autor" class="form-label">Autor:</label>
                    <input type="text" class="form-control" id="autor" name="autor" value="' . $row["autor"] . '" required>
                </div>
                <div class="mb-3">
                    <label for="ton" class="form-label">Tonart:</label>
                    <input type="text" class="form-control" id="ton" name="ton" value="' . $row["ton"] . '" required>
                </div>
                <div class="mb-3">
                    <label for="pdf_attachment" class="form-label">PDF-Anhang:</label>
                    <input type="file" class="form-control" id="pdf_attachment" name="pdf_attachment" accept="application/pdf">
                </div>
                <input type="hidden" name="id" value="' . $row["id"] . '">
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Aktualisieren</button>
                <form method="post" action="" onsubmit="return confirmDelete();" style="display: inline-block;">
                    <!-- Rest des Formulars -->
                    <input type="hidden" name="id" value="' . $row["id"] . '">
                    <button type="submit" name="delete" class="btn btn-danger">Löschen</button>
                </form>
                </form>
                <script>
                    function confirmDelete() {
                        return confirm("Willst Du dieses Lied endgültig aus der Datenbank löschen?");
                    }
                </script>';
        
                    if (isset($_POST['delete'])) {
                        // Bestätigung zum Löschen des Datensatzes
                        if (confirmDelete()) {
                            // Code zum Löschen des Datensatzes aus der Datenbank
                            $deleteId = $_POST['id'];
                            $deleteSql = "DELETE FROM lieder WHERE id = '$deleteId'";
                            if ($conn->query($deleteSql) === TRUE) {
                                echo "Der Datensatz wurde erfolgreich gelöscht.";
                                header("Location: lieder.php");
                                exit();
                            } else {
                                echo "Beim Löschen des Datensatzes ist ein Fehler aufgetreten: " . $conn->error;
                            }
                        } else {
                            echo "Das Löschen wurde abgebrochen.";
                        }
                    }
                } else {
                    echo "<p>Der Datensatz konnte nicht gefunden werden.</p>";
                }
            } else {
                echo "<p>Es wurde keine ID zum Bearbeiten übergeben.</p>";
            }

            ?>

        </div>
    </div>
</body>