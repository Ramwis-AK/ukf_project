<?php
/**
 * Fun Facts component
 */

// Define facts items
$factsItems = [
    [
        'number' => 150,
        'speed' => 1000,
        'text' => 'Happy Students',
        'class' => ''
    ],
    [
        'number' => 804,
        'speed' => 1000,
        'text' => 'Course Hours',
        'class' => ''
    ],
    [
        'number' => 50,
        'speed' => 1000,
        'text' => 'Employed Students',
        'class' => ''
    ],
    [
        'number' => 15,
        'speed' => 1000,
        'text' => 'Years Experience',
        'class' => 'end'
    ]
];
?>

<!-- Fun Facts Section -->
<div class="section fun-facts">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper">
                    <div class="row">

                        <?php foreach ($factsItems as $fact) : ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="counter <?php echo $fact['class']; ?>">
                                    <h2 class="timer count-title count-number"
                                        data-to="<?php echo $fact['number']; ?>"
                                        data-speed="<?php echo $fact['speed']; ?>">
                                    </h2>
                                    <p class="count-text"><?php echo $fact['text']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>