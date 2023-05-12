<?php
include 'header.php';
include 'datenbank.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Überprüfen, ob eine Lied-ID übergeben wurde
if (isset($_GET["id"])) {
    $lied_id = $_GET["id"];

    // SQL-Statement zum Abrufen des vorhandenen Lieds aus der Datenbank
    $sql = "SELECT * FROM lieder WHERE id = $lied_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $autor = $row["autor"];
        $ton = $row["ton"];
        $pdf_attachment = $row["pdf_attachment"];
    }
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
        </div>
        <div class="content">
            <h1>Lied bearbeiten</h1>
            <?php
            include 'datenbank.php';
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["action"]) && $_POST["action"] == "edit") {
                    // Abrufen der aktualisierten Liedinformationen aus dem Formular
                    $name = $_POST["name"];
                    $autor = $_POST["autor"];
                    $ton = $_POST["ton"];
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
                                $pdf_attachment = basename($_FILES["pdf_attachment"]["name"]);
                            } else {
                                echo "Es gab einen Fehler beim Hochladen Ihrer Datei.";
                            }
                        }
                    } // Ende der Überprüfung, ob eine neue PDF-Datei hochgeladen wurde
            

                    // SQL-Statement zum Aktualisieren des Lieds in der Datenbank
                    $sql = "UPDATE lieder SET name='$name', autor='$autor', ton='$ton', pdf_attachment='$pdf_attachment' WHERE id=$id";

                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Lied wurde erfolgreich aktualisiert.</p>";
                    } else {
                        echo "Fehler: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
            // Ende der if-Bedingung für das Aktualisieren des Lieds
            ?>


            <form method="post" action="" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="autor" class="form-label">Autor:</label>
                    <input type="text" class="form-control" id="autor" name="autor" value="<?php echo $autor; ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label for="ton" class="form-label">Tonart:</label>
                    <input type="text" class="form-control" id="ton" name="ton" value="<?php echo $ton; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="pdf_attachment" class="form-label">PDF-Anhang:</label>
                    <input type="file" class="form-control" id="pdf_attachment" name="pdf_attachment"
                        accept="application/pdf">
                </div>
                <input type="hidden" name="action" value="edit">
                <button type="submit" class="btn btn-primary">Lied aktualisieren</button>
            </form>