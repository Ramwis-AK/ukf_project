<?php
require_once __DIR__ . '/../functions/helpers.php';
class TestimonialsSection
{
    private array $testimonials;

    public function __construct(array $testimonials)
    {
        $this->testimonials = $testimonials;
    }

    public function render(): void
    {
        ?>
        <div class="section testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="owl-carousel owl-testimonials">
                            <?php foreach ($this->testimonials as $testimonial): ?>
                                <div class="item">
                                    <p><?php echo htmlspecialchars($testimonial['quote']); ?></p>
                                    <div class="author">
                                        <img src="<?php echo htmlspecialchars($testimonial['image']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>">
                                        <span class="category"><?php echo htmlspecialchars($testimonial['category']); ?></span>
                                        <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-5 align-self-center">
                        <div class="section-heading">
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

$helpers = new Helpers();
$testimonials = $helpers->getTestimonials();

$section = new TestimonialsSection($testimonials);
$section->render(); // <-- NAJHLAVNEJŠIE!
