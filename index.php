<?php
include 'header.php';
include 'datenbank.php';
include 'auth.php';
?>

<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
    <div class="container">
        Bitte logge dich ein:<br />
        <form method="post" action="index.php?page=log">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" class="form-control" name="user" id="exampleInputEmail1"
                    aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Passwort</label>
                <input type="password" name="passwort" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Einloggen</button>
        </form>
    </div>
</body>