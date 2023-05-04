<?php include 'header.php';?>
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
        </div>
        <div class="content">
            <h1>Lieder hinzufügen</h1>
    <p>Du bist auf der Lieder hinzufügen Seite</p>
    <form method="post" action="">
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
        <button type="submit" class="btn btn-primary">Lied hinzufügen</button>
    </form>
    <br>


            <?php
            // Verbindung zur Datenbank herstellen
            include 'datenbank.php';

            // Überprüfen, ob das Formular abgeschickt wurde
            if (isset($_POST['name']) && isset($_POST['autor']) && isset($_POST['ton'])) {
                $name = $_POST['name'];
                $autor = $_POST['autor'];
                $key = $_POST['ton'];

                // SQL-Statement zum Einfügen des Lieds in die Datenbank
                $sql = "INSERT INTO lieder (name, autor, ton) VALUES ('$name', '$autor', '$ton')";

                // Ausführen des SQL-Statements
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Lied wurde erfolgreich hinzugefügt.</p>";
                } else {
                    echo "Fehler: " . $sql . "<br>" . $conn->error;
                }
            }

            // Überprüfen, ob das Löschen-Formular abgeschickt wurde
            if (isset($_POST['id'])) {
                $id = $_POST['id'];

                // SQL-Statement zum Löschen des Lieds aus der Datenbank
                $sql = "DELETE FROM lieder WHERE id=$id";

                // Ausführen des SQL-Statements
                if ($conn->query($sql) === TRUE) {
                } else {
                    echo "Fehler: " . $sql . "<br>" . $conn->error;
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
        echo "<tr><td>" . $row["name"] . "</td><td>" . $row["autor"] . "</td><td>" . $row["ton"] . "</td><td><form method='post' action=''><input type='hidden' name='id' value='" . $row["id"] . "'><button type='submit' class='btn btn-danger'>Löschen</button></form></td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>Keine Lieder gefunden.</td></tr>";
}

// Überprüfen, ob ein Lied gelöscht werden soll
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // SQL-Statement zum Löschen des Lieds aus der Datenbank
    $sql = "DELETE FROM lieder WHERE id = $id";

    // Ausführen des SQL-Statements
    if ($conn->query($sql) === TRUE) {
        echo "<p>Lied wurde erfolgreich gelöscht.</p>";
    } else {
        echo "Fehler: " . $sql . "<br>" . $conn->error;
    }
}
