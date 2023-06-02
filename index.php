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





<body class="bg-gradient-primary">

    <div class="container">

        <!-- Äußere Reihe -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Verschachtelte Reihe innerhalb des Kartenkörpers -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Willkommen zurück!</h1>
                                    </div>
                                    <form class="user" method="post" action="index.php?page=log">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="user" id="exampleInputEmail1"
                                                aria-describedby="emailHelp" placeholder="Dein Benutzername...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="passwort" class="form-control"
                                                id="exampleInputPassword1" placeholder="Dein Passwort">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Einloggen
                                        </button>
                                        <hr>

                                    </form>
                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Passwort vergessen?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Ein Konto erstellen!</a>
                                    </div>
                                    <hr>
                                    <div class="text-center" style="font-size:12px">
                                        <i>Denn wenn du mit deinem Munde bekennst, dass Jesus der Herr ist, und glaubst
                                            in
                                            deinem Herzen, dass ihn Gott von den Toten auferweckt hat, so wirst du
                                            gerettet.
                                            Römer 10:9</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

<style>
    .bg-login-image {
        background: url(https://images.unsplash.com/photo-1525201548942-d8732f6617a0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80);
        background-position: center;
        background-size: cover;
    }
</style>