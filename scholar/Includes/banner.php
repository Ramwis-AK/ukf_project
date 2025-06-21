<?php
// Načítanie konfigurácie databázy a pripojenia
require_once __DIR__ . '/../config/db_config.php';
class Banner
{
    private string $baseUrl;       // Základná cesta ku obrazkom
    private array $banners = [];   // Pole bannerov získané z databázy
    private Database $db;          // DB inštancia (očakáva singleton triedu Database)

    // Konstruktor nastaví základnú URL a natiahne bannery z DB
    public function __construct(string $baseUrl = "//localhost/ukf_project/scholar/")
    {
        $this->baseUrl = $baseUrl;
        $this->db = Database::getInstance();  // Použitie singletonu na pripojenie k databáze
        $this->loadBanners();                 // Načítanie bannerov pri inicializácii
    }

    // Načítava bannery z databázy
    private function loadBanners(): void
    {
        $result = $this->db->select("SELECT * FROM banners ORDER BY id ASC");

        if ($result !== false) {
            $this->banners = $result;
        } else {
            $this->banners = []; //ak nastane chyba ulož prázdne pole
        }
    }

    // Renderovacia metóda vypíše HTML pre banner s karusel
    public function render(): void
    {
        ?>
        <!-- extra úprava css -->
        <style>
            .main-banner {
                min-height: 300px;
                display: flex;
                align-items: center;
            }
            .main-banner .item {
                min-height: 600px;
                display: flex;
                align-items: center;
            }
        </style>

        <!-- Banner sekcia s karusel  -->
        <div class="main-banner" id="top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl-carousel owl-banner">
                            <!-- Prejde banners a vytvorí úplnú URL  -->
                            <?php foreach ($this->banners as $banner) :
                                $imageUrl = $this->baseUrl . $banner['image_path'];
                                ?>
                                <!-- Každý banner ako samostatná karusel položka -->
                                <div class="item <?php echo htmlspecialchars($banner['item_class']); ?>"
                                     style="background-image: url('<?php echo htmlspecialchars($imageUrl); ?>');">
                                    <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
                                    <!-- Voliteľné vrstvy: overlay, text, animácie -->
                                    <div class="banner-overlay"></div>
                                    <div class="header-text"></div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Spustenie komponentu: vytvorí inštanciu a renderuje
$banner = new Banner();
$banner->render();
