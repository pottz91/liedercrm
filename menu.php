<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/seite2.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-music"></i>
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

    <footer class="navbar navbar-dark bg-primary fixed-bottom d-md-none">
        <div id="mobileMenuList" class="container">
            <div class="row">
            <div class="d-flex flex-row bd-highlight mb-3">...
                <div class="p-2 bd-highlight"></div>
                    <a class="nav-link" href="seite2.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span class="menu-font">Dashboard</span>
                    </a>
                <div class="p-2 bd-highlight"></div>
                    <a class="nav-link" href="lieder.php">
                        <i class="fas fa-fw fa-music"></i>
                        <span class="menu-font">Lieder</span>
                    </a>
                <div class="p-2 bd-highlight"></div>
                    <a class="nav-link" href="liederadd.php">
                        <i class="fas fa-fw fa-edit"></i>
                        <span class="menu-font">Bearbeiten</span>
                    </a>
                <div class="p-2 bd-highlight"></div>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-fw fa-sign-out"></i>
                        <span class="menu-font">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</ul>

<style>
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
