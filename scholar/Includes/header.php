<?php
/**
 * Header component
 */

// Define navigation items
$navItems = [
    ['url' => '#top', 'text' => 'Home', 'active' => true],
    ['url' => '#services', 'text' => 'Services', 'active' => false],
    ['url' => '#courses', 'text' => 'Courses', 'active' => false],
    ['url' => '#team', 'text' => 'Team', 'active' => false],
    ['url' => '#events', 'text' => 'Events', 'active' => false],
    ['url' => '#contact', 'text' => 'Register Now!', 'active' => false],
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
                        <h1>Scholar</h1>
                    </a>
                    <!-- Logo End -->

                    <!-- Search Start -->
                    <div class="search-input">
                        <form id="search" action="#" method="get">
                            <input type="text" placeholder="Type Something" id="searchText" name="searchKeyword" />
                            <i class="fa fa-search"></i>
                        </form>
                    </div>
                    <!-- Search End -->

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