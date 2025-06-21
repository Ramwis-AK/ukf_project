<?php
require_once __DIR__ . '/../functions/helpers.php';

class Courses
{
    // Premenné uchovávajúce kategórie kurzov a eventy
    private array $courseCategories;
    private array $courseItems;

    // Konstruktor triedy, ktorý očakáva kategórie a eventy
    public function __construct(array $courseCategories, array $courseItems)
    {
        $this->courseCategories = $courseCategories;
        $this->courseItems = $courseItems;
    }

    // Filter na bodky, aby sme z css vytvárali triedy v php
    private function getCourseClasses(string $filter): string
    {
        return ltrim($filter, '.');
    }

    // Hlavná metóda pre vykreslenie sekcie eventov v HTML
    public function render(): void
    {
        ?>
        <section class="section courses" id="courses">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="section-heading">
                            <h6>ponuka</h6>
                            <h2>aktuálna ponuka</h2>
                        </div>
                    </div>
                </div>

                <!-- Filter kategórií eventov -->
                <ul class="event_filter">
                    <?php foreach ($this->courseCategories as $category) : ?>
                        <li>
                            <!-- navigácia pre eventy -->
                            <a class="<?php echo $category['active'] ? 'is_active' : ''; ?>" href="#!" data-filter="<?php echo htmlspecialchars($category['filter']); ?>">
                                <?php echo htmlspecialchars($category['text']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Zobrazovanie eventov -->
                <div class="row event_box">
                    <?php foreach ($this->courseItems as $course) : ?>
                        <div class="col-lg-4 col-md-6 align-self-center mb-30 event_outer <?php echo $this->getCourseClasses($course['filter']); ?>">
                            <div class="events_item">
                                <div class="thumb">
                                    <a href="#"><img src="<?php echo htmlspecialchars($course['image']); ?>" alt=""></a>
                                </div>
                                <div class="down-content">
                                    <span class="author"><?php echo htmlspecialchars($course['author']); ?></span>
                                    <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

// Vytvoríme inštanciu pomocnej triedy na získanie dát
$helpers = new Helpers();

$courseCategories = $helpers->getCourseCategories();
$courseItems = $helpers->getCourses();

// Vytvoríme objekt triedy Courses s dátami a vykreslíme obsah
$courses = new Courses($courseCategories, $courseItems);
$courses->render();
