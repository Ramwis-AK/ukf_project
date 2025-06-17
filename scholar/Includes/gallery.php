<?php
require_once __DIR__ . '/../functions/helpers.php';

class GallerySection
{
    private array $events;

    public function __construct(array $events = [])
    {
        // Ak neodošleš eventy, načítajú sa automaticky z DB
        if (empty($events)) {
            $helpers = new Helpers();
            $this->events = $helpers->getEvents();
        } else {
            $this->events = $events;
        }
    }

    public function render(): void
    {
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

                    <?php foreach ($this->events as $event): ?>
                        <div class="col-lg-12 col-md-6">
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="image">
                                            <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <ul>
                                            <li>
                                                <span class="category"><?php echo htmlspecialchars($event['category']); ?></span>
                                                <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                            </li>
                                            <li>
                                                <span>Dátum konania:</span>
                                                <h6><?php echo htmlspecialchars($event['date']); ?></h6>
                                            </li>
                                            <li>
                                                <span>Počet účastníkov:</span>
                                                <h6><?php echo htmlspecialchars($event['customers'] ?? $event['custumers'] ?? ''); ?></h6>
                                            </li>
                                            <li>
                                                <span>Hodnotenie:</span>
                                                <h6><?php echo htmlspecialchars($event['rating']); ?></h6>
                                            </li>
                                        </ul>
                                        <a href="#"><i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

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

// Použitie (napríklad v tvojom view alebo kontroléri):
$gallery = new GallerySection();
$gallery->render();

