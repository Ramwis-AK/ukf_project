<?php

class About
{
    private array $accordionItems = [
        [
            'id' => '1',
            'title' => 'Ako vieme zabezpečiť, že váš event bude presne podľa vašich predstáv?',
            'content' => 'Na začiatku si detailne vypočujeme vaše želania a očakávania. Neponúkame žiadne šablóny, každý event tvoríme na mieru s dôrazom na detaily, ktoré pre vás majú význam. Sme vaši partneri, nie len dodávatelia.',
            'show' => true
        ],
        [
            'id' => '2',
            'title' => 'Čím sa odlišujeme od konkurencie, ktorá tiež sľubuje kvalitu a rýchlosť?',
            'content' => 'Nezakladáme sa len na prázdnych frázach. Naša kvalita vychádza z neustáleho vzdelávania a nasadenia, a rýchlosť z dobre nastavených procesov, ktoré eliminujú zbytočné prieťahy. Nemusíte čakať, aby ste dostali výnimočné.',
            'show' => false
        ],
        [
            'id' => '3',
            'title' => 'Ako zvládame neočakávané situácie, ktoré môžu zničiť akýkoľvek event?',
            'content' => 'Vďaka skúsenostiam a flexibilite sme pripravení rýchlo reagovať na zmeny. Pre nás je stres zákazníka zakázaný pojem – riešenia nájdeme vždy, aj keď sa niečo pokazí.',
            'show' => false
        ],
        [
            'id' => '4',
            'title' => 'Prečo by ste mali investovať práve do nás, keď je na trhu toľko firiem?',
            'content' => 'Lebo nám naozaj záleží – nie len na výsledku, ale aj na vás ako človeku za projektom. Nepredávame prázdne sľuby, ale spoľahlivosť, osobný prístup a výsledok, ktorý vás posunie ďalej. S nami nejde len o event, ale o zážitok.',
            'show' => false
        ],
    ];

    public function render(): void
    {
        ?>
        <!-- About Us Section -->
        <div class="section about-us">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-1">
                        <div class="accordion" id="accordionExample">
                            <?php foreach ($this->accordionItems as $item): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo htmlspecialchars($item['id']); ?>">
                                        <button class="accordion-button <?php echo $item['show'] ? '' : 'collapsed'; ?>"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?php echo htmlspecialchars($item['id']); ?>"
                                                aria-expanded="<?php echo $item['show'] ? 'true' : 'false'; ?>"
                                                aria-controls="collapse<?php echo htmlspecialchars($item['id']); ?>">
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo htmlspecialchars($item['id']); ?>"
                                         class="accordion-collapse collapse <?php echo $item['show'] ? 'show' : ''; ?>"
                                         aria-labelledby="heading<?php echo htmlspecialchars($item['id']); ?>"
                                         data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php echo $item['content']; // obsah môže byť HTML ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-5 align-self-center">
                        <div class="section-heading">
                            <h6>O nás</h6>
                            <h2>Ste pripravený zažiť rozdiel?</h2>
                            <p>Každý má svoj príbeh. My nie sme len eventová agentúra – sme tím, ktorý verí v silu emócií a radosti.
                                Rýchla reakcia, pohotové riešenia a tvorivý prístup? Pre nás samozrejmosť.</p>
                            <div class="main-button">
                                <li class="scroll-to-section">
                                    <a href="#contact">Zisti viac</a>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Použitie:
$about = new About();
$about->render();
