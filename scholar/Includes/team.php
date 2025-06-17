<?php
require_once __DIR__ . '/../functions/helpers.php';
class TeamSection
{
    private array $teamMembers;

    public function __construct(array $teamMembers)
    {
        $this->teamMembers = $teamMembers;
    }

    public function render(): void
    {
        ?>
        <div class="team section" id="team">
            <div class="container">
                <div class="row">
                    <?php foreach ($this->teamMembers as $member): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="team-member">
                                <div class="main-content">
                                    <img src="<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                                    <span class="category"><?php echo htmlspecialchars($member['category']); ?></span>
                                    <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                                    <ul class="social-icons">
                                        <li><a href="<?php echo htmlspecialchars($member['facebook']); ?>"><i class="fab fa-facebook" style="position: relative; top: 12px;"></i></a></li>
                                        <li><a href="<?php echo htmlspecialchars($member['twitter']); ?>"><i class="fab fa-twitter" style="position: relative; top: 12px;"></i></a></li>
                                        <li><a href="<?php echo htmlspecialchars($member['linkedin']); ?>"><i class="fab fa-linkedin" style="position: relative; top: 12px;"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}

// Použitie:
$helpers = new Helpers();
$teamMembers = $helpers->getTeamMembers();

$teamSection = new TeamSection($teamMembers);
$teamSection->render();
