<?php
/**
 * Main index file
 */

define('DB_ACCESS_ALLOWED', true); //  overenie, či je prístup k databázovej konfigurácii povolený
session_start(); // Spustenie PHP session - práca s premennými ako $_SESSION['role']

// Hlavná trieda reprezentujúca aplikáciu
class ScholarApp
{
    private string $pageTitle = "Scholar - Online School PHP Template";

    // Zoznam CSS súborov, ktoré sa majú načítať
    private array $stylesheets = [
        'global', 'preloader', 'header', 'auth', 'banner', 'service', 'about',
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

    // Používateľ prihlásený ako admin, presmeruje ho do administračného rozhrania
    private function checkAdminRedirect(): void
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { //pritomnost a kontrola role
            header("Location: ../admin.php");
            exit;
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
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Import Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

            <!-- Dynamický titulok stránky|konvertuje špeciálne znaky do bezpečných HTML entít -->
            <title><?php echo htmlspecialchars($this->pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

            <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

            <link rel="stylesheet" href="assets/css/fontawesome.css">
            <link rel="stylesheet" href="assets/css/templatemo-scholar.css">
            <link rel="stylesheet" href="assets/css/owl.css">
            <link rel="stylesheet" href="assets/css/animate.css">

            <!-- cache busting cez timestamp -->
            <link rel="stylesheet" type="text/css" href="assets/css/service.css?v=<?php echo time(); ?>">

            <!-- JS knižnica na úpravu posuvníka -->
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

        <!-- Sekcia so zaujímavosťami -->
        <?php include_once('includes/facts.php'); ?>

        <!-- Sekcia tímu -->
        <?php include_once('includes/team.php'); ?>

        <!-- Referencie -->
        <?php include_once('includes/testimonials.php'); ?>

        <!-- Galéria / podujatia -->
        <?php include_once('includes/gallery.php'); ?>

        <!-- Kontakt -->
        <?php include_once('includes/contact.php'); ?>

        <!-- Pätička -->
        <?php include_once('includes/footer.php'); ?>

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
