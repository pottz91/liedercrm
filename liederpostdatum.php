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
