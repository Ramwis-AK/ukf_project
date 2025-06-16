<?php
/**
 * About Us component
 */
$sql = "SELECT * FROM questions WHERE is_visible = 1 ORDER BY display_order ASC";
$result = $mysqli->query($sql);

$accordionItems = [];
while ($row = $result->fetch_assoc()) {
    $accordionItems[] = [
        'id' => 'item-' . $row['id'],  // môžeš si vytvoriť podľa ID
        'title' => $row['title'],
        'content' => $row['content'],
        'show' => (bool)$row['is_visible']
    ];
}

?>

<!-- About Us Section -->
<div class="section about-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-1">
                <div class="accordion" id="accordionExample">

                    <?php foreach ($accordionItems as $item) : ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $item['id']; ?>">
                                <button class="accordion-button <?php echo $item['show'] ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $item['id']; ?>" aria-expanded="<?php echo $item['show'] ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $item['id']; ?>">
                                    <?php echo $item['title']; ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $item['id']; ?>" class="accordion-collapse collapse <?php echo $item['show'] ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $item['id']; ?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php echo $item['content']; ?>
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