<?php

include 'datenbank.php';
session_start();

// Überprüfen, ob das Anmeldeformular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];
    $password = $_POST['passwort'];
    // SQL-Statement zum Abrufen des Benutzers aus der Datenbank
    $sql = "SELECT passwort FROM benutzer WHERE benutzername = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['passwort'];

        // Überprüfen des Passworts
        if (password_verify($password, $hashedPassword)) {
            // Anmeldeinformationen sind korrekt, Sitzung erstellen
            $_SESSION['loggedin'] = true;
            $_SESSION['benutzername'] = $username;
            // Leite zur Startseite weiter
            header('Location: seite2.php');
            exit;
        }
    }

    // Anmeldeinformationen sind ungültig, Fehlermeldung anzeigen
    echo "Ungültige Anmeldeinformationen.";
}
?>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="stylesheet" href="./ressource/css/style.css">
    <link rel="stylesheet" href="./ressource/css/sb-admin-2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="./ressource/js/sb-admin-2.js"></script>
    <script src="./ressource/js/jquery.easing.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:200,300,400,500,600,700,800,900|nunito-sans:400"
        rel="stylesheet" />
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
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
                                    <form class="user" method="post" action="index.php">
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
