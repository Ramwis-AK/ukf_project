<?php

class About
{
    private array $accordionItems = [
        [
            'id' => '1',
            'title' => 'Where shall we begin?',
            'content' => 'Dolor <strong>almesit amet</strong>, consectetur adipiscing elit, sed doesn\'t eiusmod tempor incididunt ut labore consectetur <code>adipiscing</code> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.',
            'show' => true
        ],
        [
            'id' => '2',
            'title' => 'How do we work together?',
            'content' => 'Dolor <strong>almesit amet</strong>, consectetur adipiscing elit, sed doesn\'t eiusmod tempor incididunt ut labore consectetur <code>adipiscing</code> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.',
            'show' => false
        ],
        [
            'id' => '3',
            'title' => 'Why SCHOLAR is the best?',
            'content' => 'There are more than one hundred responsive HTML templates to choose from <strong>Template</strong>Mo website. You can browse by different tags or categories.',
            'show' => false
        ],
        [
            'id' => '4',
            'title' => 'Do we get the best support?',
            'content' => 'You can also search on Google with specific keywords such as <code>templatemo business templates, templatemo gallery templates, admin dashboard templatemo, 3-column templatemo, etc.</code>',
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
