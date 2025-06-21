<?php
require_once __DIR__ . '/../functions/helpers.php';

class TestimonialsSection
{
    private array $testimonials; // Pole na uloženie referencií

    // Konštruktor prijíma pole referencií a ukladá ho do vlastnej premennej
    public function __construct(array $testimonials)
    {
        $this->testimonials = $testimonials;
    }

    // Metóda, ktorá vykreslí HTML časť s referenciami
    public function render(): void
    {
        ?>
        <div class="section testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="owl-carousel owl-testimonials">
                            <?php foreach ($this->testimonials as $testimonial): ?> <!-- Pre každý testimonial v poli -->
                                <div class="item"> <!-- Posuvný element -->
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
                    <div class="col-lg-5 align-self-center">
                        <div class="section-heading">
                            <h6>TESTIMONIALS</h6>
                            <h2>Čo o nás hovoria ľudia?</h2>
                            <p>Nechajte sa presvedčiť kvalitou našich služieb prostredníctvom skúseností a zážitkov našich klientov. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

$helpers = new Helpers();
$testimonials = $helpers->getTestimonials();

$section = new TestimonialsSection($testimonials); // Vytvorenie inštancie TestimonialsSection s načítanými dátami
$section->render(); // <-- NAJHLAVNEJŠIE! Vykreslí sekciu referencií do HTML
