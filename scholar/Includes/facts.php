<?php
// Načítanie pomocných funkcií a dát z externého súboru
require_once __DIR__ . '/../functions/helpers.php';

// Vytvorenie inštancie triedy Helpers
$helpers = new Helpers();

// Získanie dát pre fun facts (fakty) z pomocnej triedy
$factsItems = $helpers->getFacts();

// Trieda na vykreslenie sekcie so zaujímavými faktami
class FunFacts
{
    // Uchovávanie položiek faktov
    private array $factsItems;

    // Konstruktor triedy prijíma pole faktov
    public function __construct(array $factsItems)
    {
        $this->factsItems = $factsItems;
    }

    // Metóda na vykreslenie HTML pre sekciu faktov
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
                                <?php
                                // Prechádzame každý fakt a zobrazíme ho vo vlastnom stĺpci
                                foreach ($this->factsItems as $fact) : ?>
                                    <div class="col-lg-3 col-md-6">
                                        <!--
                                            Trieda CSS z dát, napr. na špecifické štýly alebo animácie
                                            Používame htmlspecialchars pre bezpečnosť proti XSS
                                        -->
                                        <div class="counter <?php echo htmlspecialchars($fact['class']); ?>">
                                            <!--
                                                H2 obsahuje animované číslo
                                                data-to = cieľová hodnota animácie
                                                data-speed = rýchlosť animácie v ms
                                            -->
                                            <h2 class="timer count-title count-number"
                                                data-to="<?php echo htmlspecialchars($fact['number']); ?>"
                                                data-speed="<?php echo htmlspecialchars($fact['speed']); ?>">
                                            </h2>
                                            <!-- Textový popis faktu -->
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

// Vytvorenie objektu triedy FunFacts s dátami a vykreslenie sekcie
$funFacts = new FunFacts($factsItems);
$funFacts->render();
