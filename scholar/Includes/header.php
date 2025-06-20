<?php

// navigácia + login/register + logo
class Header
{
    // Definícia položiek navigácie
    private array $navItems = [
        ['url' => '#top', 'text' => 'Domov', 'active' => true],
        ['url' => '#services', 'text' => 'O nás', 'active' => false],
        ['url' => '#courses', 'text' => 'Ponuka', 'active' => false],
        ['url' => '#team', 'text' => 'Tím', 'active' => false],
        ['url' => '#events', 'text' => 'Galéria', 'active' => false],
        ['url' => '#contact', 'text' => 'Kontakt', 'active' => false],
    ];

    public function __construct()
    {
        // Pri vytvorení inštancie zabezpečí, že session je spustená
        $this->startSession();
    }

    // ochrana proti duplicitnému štartu
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Kontrola, či je používateľ prihlásený
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    // Vráti meno zo session ak je prihlásení
    private function getUsername(): string
    {
        return $this->isLoggedIn() ? $_SESSION['username'] : '';
    }

    // Hlavná metóda: generuje HTML kód pre hlavičku vrátane navigácie a login stavu
    public function render(): void
    {
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
                                <!-- Ak je používateľ prihlásený, zobrazí jeho meno a odhlasovacie tlačidlo -->
                                <?php if ($this->isLoggedIn()) : ?>
                                    <div class="logged-in-user">
                                        <!-- ochrana proti XSS(nebezpečné znaky)-->
                                        <span>Vitaj, <?php echo htmlspecialchars($this->getUsername(), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <a href="/ukf_project/scholar/includes/logout.php" class="logout-btn">Odhlásiť sa</a>
                                    </div>
                                <?php else : ?>
                                    <!-- Ak nie je prihlásený, zobrazí sa možnosť prihlásiť/registrovať -->
                                    <div class="login-register">
                                        <a href="Includes/login.php" class="login-btn">Prihlásiť sa  Registrovať</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Auth Area End -->

                            <!-- Menu Start -->
                            <ul class="nav">
                                <?php foreach ($this->navItems as $item) : ?>
                                    <li class="scroll-to-section">
                                        <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>"
                                            <?php echo $item['active'] ? 'class="active"' : ''; ?>>
                                            <?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- Mobilné menu toggle button -->
                            <a class="menu-trigger">
                                <span>Menu</span>
                            </a>
                            <!-- Menu End -->
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <?php
    }
}

// Použitie: vytvorí sa inštancia a zavolá sa metóda render() na výpis hlavičky stránky
$header = new Header();
$header->render();
