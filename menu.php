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
            <span class="menu-font">Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <li class="nav-item">
        <a class="nav-link " href="lieder.php" aria-expanded="true">
            <i class="fas fa-fw fa-music"></i>
            <span class="menu-font">Lieder</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link " href="liederadd.php" aria-expanded="true">
            <i class="fas fa-fw fa-music"></i>
            <span class="menu-font">Lieder hinzufügen</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link " href="logout.php" aria-expanded="true">
            <i class="fas fa-fw fa-sign-out"></i>
            <span class="menu-font">Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <footer class="navbar navbar-dark bg-primary fixed-bottom d-md-none">
    <a class="navbar-brand ml-auto order-2" href="#" onclick="toggleMobileMenu()">
        <i class="fas fa-bars" style="display: inline-block;"></i>
    </a>
    <ul id="mobileMenuList" class="list-unstyled" style="display: none;">
    
    <li class="nav-item active">
        <a class="nav-link" href="seite2.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="menu-font">Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>


        <li class="nav-item">
        <a class="nav-link " href="lieder.php" aria-expanded="true">
            <i class="fas fa-fw fa-music"></i>
            <span class="menu-font">Lieder</span>
        </a>
    </li>

        <li class="nav-item">
        <a class="nav-link " href="liederadd.php" aria-expanded="true">
            <i class="fas fa-fw fa-music"></i>
            <span class="menu-font">Lieder hinzufügen</span>
        </a>
    </li>

        <li class="nav-item">
        <a class="nav-link " href="logout.php" aria-expanded="true">
            <i class="fas fa-fw fa-sign-out"></i>
            <span class="menu-font">Logout</span>
        </a>
    </li>
    </ul>
</footer>

</ul>

<script>
    function toggleMobileMenu() {
        var mobileMenuList = document.getElementById('mobileMenuList');
        
        if (mobileMenuList.style.display === 'none') {
            mobileMenuList.style.display = 'block';
        } else {
            mobileMenuList.style.display = 'none';
        }
    }
</script>

<style>
    .menu-font {
        font-size: 16px !important;
        color: #fff !important;
    }

    @media (max-width: 767px) {
        .sidebar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }
    }
</style>
