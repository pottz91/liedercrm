<?php include 'header.php';?>
<?php include 'datenbank.php';?>
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
            <h1>Lieder</h1>
            <p>Auf dieser Seite hast du einen Überblick über deine <b>Lieder</b></p>
            <?php
        include 'datenbank.php';

        $sql = "SELECT * FROM lieder ORDER by name ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr><th>Name</th><th>Autor</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["autor"] . "</td></tr>";
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
