<?php include 'db.php'; ?>

<!-- Main header section -->
<header>
    <!-- Header wrapper containing logo and navigation -->
    <div class="header-wrapper">
        <!-- Site logo linking to home page -->
        <a href="index.php" id="logo" class="uppercase">dodorave</a>

        <!-- Main navigation menu -->
        <nav>
            <a href="aboutus.php"">About us</a>
            <a href="help.php">Help</a>
        </nav>
    </div>

    <!-- User authentication section -->
    <div class="admin-user">
        <?php
            // Display admin button if user is logged in and is an admin
            if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1) {
                echo "<a href='admin.php' class='admin-btn'>Admin</a>";
            }
        ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Dashboard link for logged-in users -->
            <a href="dashboard.php" id="logIn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="32" height="32" stroke-width="2"> <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path> <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path> <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                </svg>
            </a>
        <?php else: ?>
            <!-- Login link for non-logged-in users -->
            <a href="login.php" id="logIn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="32" height="32" stroke-width="2"> <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path> <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path> <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path> 
                </svg> 
            </a>
        <?php endif; ?>
    </div>
</header>