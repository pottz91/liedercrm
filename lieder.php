<?php
include 'header.php';
include 'datenbank.php';
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
            <a href="legal.php"><img src="img/legal.svg"> Impressum</a>
            <a href="logout.php"><i style="font-size: 22px" class="ri-logout-box-r-line">&nbsp;</i>Logout</a>
        </div>
        <div class="content">
            <h1>Lieder</h1>
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
    <div class="footer">
        (C) 2023 Visio Software
    </div>
</body>
