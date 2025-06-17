<?php
require_once __DIR__ . '/../config/db_config.php';

class Banner
{
    private string $baseUrl;
    private array $banners = [];
    private Database $db;

    public function __construct(string $baseUrl = "//localhost/ukf_project/scholar/")
    {
        $this->baseUrl = $baseUrl;
        $this->db = Database::getInstance();  // Singleton DB inštancia
        $this->loadBanners();
    }

    private function loadBanners(): void
    {
        // SELECT všetkých bannerov z DB (napr. z tabuľky banners)
        $result = $this->db->select("SELECT * FROM banners ORDER BY id ASC");

        if ($result !== false) {
            // Predpokladám, že v tabuľke banners máš stĺpce:
            // image_path, title, item_class
            $this->banners = $result;
        } else {
            // Ak DB dotaz zlyhá, môžeš mať fallback, alebo prázdne pole
            $this->banners = [];
        }
    }

    public function render(): void
    {
        ?>
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

        <div class="main-banner" id="top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl-carousel owl-banner">
                            <?php foreach ($this->banners as $banner) :
                                $imageUrl = $this->baseUrl . $banner['image_path'];
                                ?>
                                <div class="item <?php echo htmlspecialchars($banner['item_class']); ?>"
                                     style="background-image: url('<?php echo htmlspecialchars($imageUrl); ?>');">
                                    <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
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

// Použitie
$banner = new Banner();
$banner->render();
