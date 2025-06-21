<?php
/**
 * Scholar - Online School PHP Template
 * Main index file in OOP style
 */

define('DB_ACCESS_ALLOWED', true); // Konštanta, ktorá môže byť použitá v databázovej konfigurácii na overenie, že prístup je povolený (napr. aby sa zabránilo priamemu prístupu k súboru)
session_start(); // Spustenie PHP session - umožňuje pracovať so session premennými ako $_SESSION['role']

// Hlavná trieda reprezentujúca aplikáciu
class ScholarApp
{
    // Názov stránky zobrazovaný v <title>
    private string $pageTitle = "Scholar - Online School PHP Template";

    // Zoznam CSS súborov, ktoré sa majú načítať (niektoré sa ešte duplicituju ďalej v kóde)
    private array $stylesheets = [
        'global', 'preloader', 'header', 'banner', 'service', 'about',
        'courses', 'facts', 'team', 'testimonials', 'gallery', 'contact', 'footer'
    ];

    // Konštruktor – vykoná sa automaticky pri vytvorení objektu
    public function __construct()
    {
        // Načítanie konfiguračných a pomocných súborov
        $this->includeConfig();
        $this->includeHelpers();
    }

    // Načítanie databázovej konfigurácie
    private function includeConfig(): void
    {
        include_once('config/db_config.php');
    }

    // Načítanie pomocných funkcií
    private function includeHelpers(): void
    {
        include_once('functions/helpers.php');
    }

    // Ak je používateľ prihlásený ako admin, presmeruje ho do administračného rozhrania
    private function checkAdminRedirect(): void
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header("Location: ../admin.php"); // Presmerovanie
            exit; // Ukončí ďalšie spracovanie skriptu
        }
    }

    // Dynamické vypisovanie CSS súborov zo zoznamu $stylesheets
    private function outputStylesheets(): void
    {
        foreach ($this->stylesheets as $css) {
            echo "<link rel='stylesheet' href='assets/css/{$css}.css'>\n";
        }
    }

    // Hlavná metóda aplikácie, ktorá ju spustí
    public function run(): void
    {
        $this->checkAdminRedirect(); // Skontroluje rolu
        $this->renderPage(); // Vykreslí stránku
    }

    // Vykreslenie celej HTML stránky
    private function renderPage(): void
    {
        // HTML a PHP kombinované pre výstup hlavičky, obsahu a skriptov stránky
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <!-- Základné meta informácie -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Import Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

            <!-- Dynamický titulok stránky -->
            <title><?php echo htmlspecialchars($this->pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

            <!-- Bootstrap CSS framework -->
            <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

            <!-- Ďalšie štýly -->
            <link rel="stylesheet" href="assets/css/fontawesome.css">
            <link rel="stylesheet" href="assets/css/templatemo-scholar.css">
            <link rel="stylesheet" href="assets/css/owl.css">
            <link rel="stylesheet" href="assets/css/animate.css">

            <!-- Hardcoded štýly – duplicita voči dynamickému zoznamu -->
            <link rel="stylesheet" href="assets/css/global.css">
            <link rel="stylesheet" href="assets/css/preloader.css">
            <link rel="stylesheet" href="assets/css/header.css">
            <link rel="stylesheet" href="assets/css/auth.css"> <!-- Tento nie je v $stylesheets -->
            <link rel="stylesheet" href="assets/css/banner.css">
            <link rel="stylesheet" type="text/css" href="assets/css/service.css?v=<?php echo time(); ?>"> <!-- cache busting cez timestamp -->
            <link rel="stylesheet" href="assets/css/about.css">
            <link rel="stylesheet" href="assets/css/courses.css">
            <link rel="stylesheet" href="assets/css/facts.css">
            <link rel="stylesheet" href="assets/css/team.css">
            <link rel="stylesheet" href="assets/css/testimonials.css">
            <link rel="stylesheet" href="assets/css/gallery.css">
            <link rel="stylesheet" href="assets/css/contact.css">
            <link rel="stylesheet" href="assets/css/footer.css">

            <!-- Swiper slider CSS knižnica -->
            <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

            <!-- Dynamické štýly podľa zoznamu -->
            <?php $this->outputStylesheets(); ?>
        </head>

        <body>
        <!-- Prednačítavací animovaný blok -->
        <?php include_once('includes/preloader.php'); ?>

        <!-- Hlavička stránky -->
        <?php include_once('includes/header.php'); ?>

        <!-- Hlavný banner -->
        <?php include_once('includes/banner.php'); ?>

        <!-- Sekcia služieb -->
        <?php include_once('includes/services.php'); ?>

        <!-- Sekcia o nás -->
        <?php include_once('includes/about.php'); ?>

        <!-- Sekcia kurzov -->
        <?php include_once('includes/courses.php'); ?>

        <!-- Sekcia so štatistikami alebo zaujímavosťami -->
        <?php include_once('includes/facts.php'); ?>

        <!-- Sekcia tímu -->
        <?php include_once('includes/team.php'); ?>

        <!-- Referencie alebo hodnotenia -->
        <?php include_once('includes/testimonials.php'); ?>

        <!-- Galéria / podujatia -->
        <?php include_once('includes/gallery.php'); ?>

        <!-- Kontakt -->
        <?php include_once('includes/contact.php'); ?>

        <!-- Pätička -->
        <?php include_once('includes/footer.php'); ?>

        <!-- Načítanie JavaScriptov -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/owl-carousel.js"></script>
        <script src="assets/js/counter.js"></script>
        <script src="assets/js/custom.js"></script>
        </body>
        </html>
        <?php
    }
}

// Vytvorenie inštancie aplikácie a jej spustenie
$app = new ScholarApp();
$app->run();
