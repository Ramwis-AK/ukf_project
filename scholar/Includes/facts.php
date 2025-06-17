<?php
require_once __DIR__ . '/../functions/helpers.php';

$helpers = new Helpers();
$factsItems = $helpers->getFacts();

class FunFacts
{
    private array $factsItems;

    public function __construct(array $factsItems)
    {
        $this->factsItems = $factsItems;
    }

    public function render(): void
    {
        ?>
        <!-- Fun Facts Section -->
        <div class="section fun-facts">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wrapper">
                            <div class="row">
                                <?php foreach ($this->factsItems as $fact) : ?>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="counter <?php echo htmlspecialchars($fact['class']); ?>">
                                            <h2 class="timer count-title count-number"
                                                data-to="<?php echo htmlspecialchars($fact['number']); ?>"
                                                data-speed="<?php echo htmlspecialchars($fact['speed']); ?>">
                                            </h2>
                                            <p class="count-text"><?php echo htmlspecialchars($fact['text']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

$funFacts = new FunFacts($factsItems);
$funFacts->render();
