<?php
require_once __DIR__ . '/../functions/helpers.php';

class GallerySection
{
    private array $events; // Pole na uloženie eventov čo sa stali

    // Konštruktor, ktorý môže prijať pole eventov alebo ich automaticky načíta z DB, ak sa nič neodošle
    public function __construct(array $events = [])
    {
        // Ak je pole eventov prázdne, načítame ich cez Helpers z databázy
        if (empty($events)) {
            $helpers = new Helpers();
            $this->events = $helpers->getEvents();
        } else {
            $this->events = $events; // Ak sú eventy odovzdané, použijeme ich priamo
        }
    }

    // Metóda na vykreslenie HTML galérie eventov
    public function render(): void
    {
        // Ak nie sú žiadne eventy, zobrazí sa oznam a vykresľovanie sa ukončí
        if (empty($this->events)) {
            echo '<p>Žiadne eventy na zobrazenie.</p>';
            return;
        }
        ?>
        <div class="section events" id="events">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="section-heading">
                            <h6>Galéria</h6>
                            <h2>Naše eventy</h2>
                        </div>
                    </div>

                    <?php foreach ($this->events as $event): ?> <!-- Pre každý event v poli -->
                        <div class="col-lg-12 col-md-6">
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="image">
                                            <!-- Obrázok eventu, alt text je názov eventu, zabezpečené htmlspecialchars -->
                                            <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <ul>
                                            <li>
                                                <!-- Kategória eventu -->
                                                <span class="category"><?php echo htmlspecialchars($event['category']); ?></span>
                                                <!-- Názov eventu -->
                                                <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                            </li>
                                            <li>
                                                <span>Dátum konania:</span>
                                                <h6><?php echo htmlspecialchars($event['date']); ?></h6>
                                            </li>
                                            <li>
                                                <span>Počet účastníkov:</span>
                                                <h6><?php echo htmlspecialchars($event['customers']); ?></h6>
                                            </li>
                                            <li>
                                                <span>Hodnotenie:</span>
                                                <h6><?php echo htmlspecialchars($event['rating']); ?></h6>
                                            </li>
                                        </ul>
                                        <!-- Odkaz s ikonou na viac info (aktuálne len placeholder #) -->
                                        <a href="#"><i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Link na Instagram s ikonou -->
                    <p class="portfolio-link">
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                            Viac na našom Instagrame
                            <img src="/ukf_project/scholar/assets/images/ig.png" alt="Instagram" class="insta-icon">
                        </a>
                    </p>

                </div>
            </div>
        </div>
        <?php
    }
}

$gallery = new GallerySection(); // Vytvorí inštanciu galérie, automaticky načíta eventy z DB
$gallery->render(); // Vykreslí sekciu galérie eventov
