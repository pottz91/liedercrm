<nav style="background-color: #171c45; !important;"
    class="navbar navbar-expand navbar-light  topbar mb-4 static-top shadow">
    <div class="sidebar-brand-icon">
        <img class="p-1 d-md-none img-profile " width="95px" src="./img/logo.jpg">
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <?php
            include 'datenbank.php'; // Stellen Sie sicher, dass die Datenbankverbindung in dieser Datei enthalten ist
            
            if ($conn->connect_error) {
                die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
            }

            $benutzername = $_SESSION['benutzername'];

            // Bild des Benutzers aus der Datenbank abrufen
            $sql = "SELECT bild FROM benutzer WHERE benutzername = '$benutzername'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $bild = $row['bild'];
            }
            ?>

            <a class="nav-link dropdown-toggle" href="profil.php" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 mt-3 d-lg-inline text-gray-600 small">
                    <?php echo "<p>" . $_SESSION['benutzername'] . "</p>"; ?>
                </span>
                <?php if (!empty($bild)): ?>
                    <img class="img-profile rounded-circle" src="<?php echo $bild; ?>" alt="Profilbild">
                <?php else: ?>
                    <img class="img-profile rounded-circle"
                        src="https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png"
                        alt="Standardprofilbild">
                <?php endif; ?>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Einstellungen
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Aktivitätsprotokoll
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Abmelden
                </a>
            </div>
        </li>
    </ul>
</nav>