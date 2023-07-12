<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion toggled" style="background-color: #171c45" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/seite2.php">
        <div class="sidebar-brand-icon">
            <img class="img-profile"  width="105px" src="./img/logo.jpg">
        </div>
        <div class="sidebar-brand-text mx-3">LiederCRM <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="seite2.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="menu-font">Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <li class="nav-item">
        <a class="nav-link" href="lieder.php" aria-expanded="true">
            <i class="fas fa-fw fa-music"></i>
            <span class="menu-font">Lieder</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="liederadd.php" aria-expanded="true">
            <i class="fas fa-fw fa-edit"></i>
            <span class="menu-font">Lieder hinzufügen</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="logout.php" aria-expanded="true">
            <i class="fas fa-fw fa-sign-out"></i>
            <span class="menu-font">Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <nav  style="background-color: #171c45" class=" mobile-nav">
        <a href="seite2.php" class="bloc-icon">
            <i class="fas fa-home"></i>
            <span class="menunavcolor">Dashboard</span>
        </a>
        <a href="lieder.php" class="bloc-icon">
            <i class="fas fa-music"></i>
            <span class="menunavcolor">Lieder</span>
        </a>
        <a href="liederadd.php" class="bloc-icon">
            <i class="fas fa-edit"></i>
            <span class="menunavcolor">Bearbeiten</span>
        </a>
        <a href="logout.php" class="bloc-icon">
            <i class="fas fa-sign-out"></i>
            <span class="menunavcolor">Logout</span>
        </a>
    </nav>


</ul>

<style>
    .mobile-nav {
        background: #F1F1F1;
        position: fixed;
        bottom: 0;
        height: 65px;
        width: 100%;
        display: flex;
        justify-content: space-around;
        z-index: 999;
        /* Fügen Sie einen hohen Z-Index hinzu, um sicherzustellen, dass die Navbar über anderen Elementen liegt */
    }

    .bloc-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .bloc-icon i {
        font-size: 20px;
        color: white;
    }

    .bloc-icon span {
        font-size: 12px;
        margin-top: 5px;

    }

    .menunavcolor {
        color: white;
    }

    @media screen and (min-width: 600px) {
        .mobile-nav {
            display: none;
        }
    }

    .menu-font {
        font-size: 16px !important;
        color: #fff !important;
    }

    @media (max-width: 767px) {
        ...sidebar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background-color: #0056b3;
            background-image: linear-gradient(45deg, #0056b3, #00a9e0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
    }
</style>
