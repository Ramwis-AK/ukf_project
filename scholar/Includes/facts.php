<?php
$factsItems = getFacts();
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