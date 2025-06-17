<?php

class Services
{
    private array $serviceItems = [
        [
            'icon' => 'assets/images/service-01.png',
            'title' => 'Eventy na mieru',
            'description' => 'S každým zákaznikom si prejdeme jeho predstavu a očakávania. Pretože nám záleží.',
        ],
        [
            'icon' => 'assets/images/service-02.png',
            'title' => 'Rýchlosť',
            'description' => 'Veríme v kvalitnú prácu. Rýchlosť je len výhodou, ktorú ponúkame svojim zákaznikom. Pretože nám záleží.',
        ],
        [
            'icon' => 'assets/images/service-03.png',
            'title' => 'Kvalita',
            'description' => 'Pravdilne sa vzdelávame v oblasti, aby sme mohli priniesť ešte viac. Pretože nám záleží.',
        ]
    ];

    public function render(): void
    {
        ?>
        <!-- Services Section -->
        <div class="services section" id="services">
            <div class="container">
                <div class="row">

                    <?php foreach ($this->serviceItems as $service) : ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="service-item">
                                <div class="icon">
                                    <img src="<?php echo htmlspecialchars($service['icon']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                                </div>
                                <div class="main-content">
                                    <h4><?php echo htmlspecialchars($service['title']); ?></h4>
                                    <p><?php echo htmlspecialchars($service['description']); ?></p>
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
$services = new Services();
$services->render();
