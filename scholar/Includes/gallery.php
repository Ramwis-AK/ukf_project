<?php
$events = getEvents();
?>

<div class="section events" id="events">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6>Galéria</h6>
                    <h2>Naše eventy</h2>
                </div>
            </div>
            <?php
            foreach ($events as $event) :
                ?>
                <div class="col-lg-12 col-md-6">
                    <div class="item">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="image">
                                    <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <ul>
                                    <li>
                                        <span class="category"><?php echo $event['category']; ?></span>
                                        <h4><?php echo $event['title']; ?></h4>
                                    </li>
                                    <li>
                                        <span>Dátum konania:</span>
                                        <h6><?php echo $event['date']; ?></h6>
                                    </li>
                                    <li>
                                        <span>Počet účastníkov:</span>
                                        <h6><?php echo $event['custumers']; ?></h6>
                                    </li>
                                    <li>
                                        <span>Hodnotenie:</span>
                                        <h6><?php echo $event['rating']; ?></h6>
                                    </li>
                                </ul>
                                <a href="#"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <p class="portfolio-link">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                    Viac na našom Instagrame
                    <img src="/ukf_project/scholar/assets/images/test.jpg" alt="Instagram" class="insta-icon">
                </a>
            </p>

        </div>
    </div>
</div>