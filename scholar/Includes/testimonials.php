<?php
require_once __DIR__ . '/../functions/helpers.php'; // Načítame externý súbor s pomocnými funkciami

class TestimonialsSection
{
    private array $testimonials; // Pole na uloženie referencií / svedectiev

    // Konštruktor prijíma pole referencií a ukladá ho do vlastnej premennej
    public function __construct(array $testimonials)
    {
        $this->testimonials = $testimonials;
    }

    // Metóda, ktorá vykreslí HTML časť s referenciami
    public function render(): void
    {
        ?>
        <div class="section testimonials"> <!-- Hlavný wrapper sekcie referencií -->
            <div class="container"> <!-- Bootstrap kontajner -->
                <div class="row"> <!-- Bootstrap riadok -->
                    <div class="col-lg-7"> <!-- Šírka 7 stĺpcov na veľkých obrazovkách -->
                        <div class="owl-carousel owl-testimonials"> <!-- Carousel (posuvník) pre referencie, používa knižnicu Owl Carousel -->
                            <?php foreach ($this->testimonials as $testimonial): ?> <!-- Pre každý testimonial v poli -->
                                <div class="item"> <!-- Jeden posuvný element -->
                                    <!-- Citát / text svedectva, htmlspecialchars chráni pred XSS -->
                                    <p><?php echo htmlspecialchars($testimonial['quote']); ?></p>
                                    <div class="author"> <!-- Informácie o autorovi -->
                                        <!-- Obrázok autora -->
                                        <img src="<?php echo htmlspecialchars($testimonial['image']); ?>">
                                        <!-- Kategória / pozícia autora -->
                                        <span class="category"><?php echo htmlspecialchars($testimonial['category']); ?></span>
                                        <!-- Meno autora -->
                                        <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    </div>
                                </div>
                            <?php endforeach; ?> <!-- Koniec cyklu pre všetky referencie -->
                        </div>
                    </div>
                    <div class="col-lg-5 align-self-center"> <!-- Vedľajší stĺpec, zarovnaný vertikálne na stred -->
                        <div class="section-heading"> <!-- Nadpis a úvodný text sekcie -->
                            <h6>TESTIMONIALS</h6>
                            <h2>What they say about us?</h2>
                            <p>You can search free CSS templates on Google using different keywords such as templatemo portfolio, templatemo gallery, templatemo blue color, etc.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Použitie:
$helpers = new Helpers(); // Vytvorenie inštancie pomocnej triedy Helpers
$testimonials = $helpers->getTestimonials(); // Získanie pola referencií pomocou metódy getTestimonials()

$section = new TestimonialsSection($testimonials); // Vytvorenie inštancie TestimonialsSection s načítanými dátami
$section->render(); // <-- NAJHLAVNEJŠIE! Vykreslí sekciu referencií do HTML
