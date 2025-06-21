<?php
require_once __DIR__ . '/../functions/helpers.php';

class TeamSection
{
    private array $teamMembers; // Uchováva pole členov tímu

    // Konštruktor triedy, ktorý očakáva pole členov tímu
    public function __construct(array $teamMembers)
    {
        $this->teamMembers = $teamMembers; // Uloží prichádzajúce členy do vlastnej premennej triedy
    }

    // Metóda na vykreslenie HTML sekcie s členmi tímu
    public function render(): void
    {
        ?>
        <div class="team section" id="team">
            <div class="container">
                <div class="row">
                    <?php foreach ($this->teamMembers as $member): ?> <!-- Prejde každý tímový objekt v poli -->
                        <div class="col-lg-3 col-md-6">
                            <div class="team-member">
                                <div class="main-content">
                                    <img src="<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                                    <span class="category"><?php echo htmlspecialchars($member['category']); ?></span>
                                    <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                                    <ul class="social-icons"> <!-- Ikony sociálnych sietí -->
                                        <li><a href="<?php echo htmlspecialchars($member['facebook']); ?>"><i class="fab fa-facebook"></i></a></li>
                                        <li><a href="<?php echo htmlspecialchars($member['twitter']); ?>"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="<?php echo htmlspecialchars($member['linkedin']); ?>"><i class="fab fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?> <!-- Koniec cyklu pre všetkých členov -->
                </div>
            </div>
        </div>
        <?php
    }
}


$helpers = new Helpers();
$teamMembers = $helpers->getTeamMembers();

$teamSection = new TeamSection($teamMembers); // Vytvorí inštanciu TeamSection s načítanými členmi
$teamSection->render(); // Vykreslí HTML sekciu tímu
