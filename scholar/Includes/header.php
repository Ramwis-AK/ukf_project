<?php
/**
 * Header component
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define navigation items
$navItems = [
    ['url' => '#top', 'text' => 'Domov', 'active' => true],
    ['url' => '#services', 'text' => 'O nás', 'active' => false],
    ['url' => '#courses', 'text' => 'Ponuka', 'active' => false],
    ['url' => '#team', 'text' => 'Tím', 'active' => false],
    ['url' => '#events', 'text' => 'Galéria', 'active' => false],
    ['url' => '#contact', 'text' => 'Kontakt', 'active' => false],
];
?>

<!-- Header Area -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- Logo Start -->
                    <a href="index.php" class="logo">
                        <h1>Gorm</h1>
                    </a>
                    <!-- Logo End -->

                    <!-- Auth Area Start -->
                    <div class="auth-area">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <!-- User is logged in -->
                            <div class="logged-in-user">
                                <span>Vitaj, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <a href="logout.php" class="logout-btn">Odhlásiť sa</a>
                            </div>
                        <?php else: ?>
                            <!-- User is not logged in -->
                            <div class="login-register">
                                <a href="login.php" class="login-btn">Prihlásiť sa / Registrovať</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Auth Area End -->

                    <!-- Menu Start -->
                    <ul class="nav">
                        <?php foreach ($navItems as $item) : ?>
                            <li class="scroll-to-section">
                                <a href="<?php echo $item['url']; ?>" <?php echo $item['active'] ? 'class="active"' : ''; ?>>
                                    <?php echo $item['text']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="menu-trigger">
                        <span>Menu</span>
                    </a>
                    <!-- Menu End -->
                </nav>
            </div>
        </div>
    </div>
</header>