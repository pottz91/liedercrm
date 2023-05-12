<?php
include 'header.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Document</title>
</head>

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
            <h1>Herzlich willkommen</h1>
            <p>Du bist auf der Startseite!</p>
        </div>
    </div>
    <div class="footer">
        (C) 2023 Visio Software
    </div>
</body>

</html>