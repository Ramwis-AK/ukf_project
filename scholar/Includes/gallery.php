<?php
/**
 * Events section template
 *
 * @package Scholar
 */
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
            // You can fetch events from database here
            $events = [
                [
                    'image' => 'assets/images/event-01.jpg',
                    'category' => 'Detské akcie',
                    'title' => 'ZOO Bojnice',
                    'date' => '16 Feb 2021',
                    'custumers' => '150+',
                    'rating' => '4,3'
                ],
                [
                    'image' => 'assets/images/event-01.jpg',
                    'category' => 'Školy',
                    'title' => 'Gymnázium, Bratislava',
                    'date' => '2. máj 2024',
                    'custumers' => '240+',
                    'rating' => '4,9'
                ],
                [
                    'image' => 'assets/images/event-01.jpg',
                    'category' => 'Firmy',
                    'title' => 'Business Centrum, Trenčín',
                    'date' => '16 Feb 2021',
                    'custumers' => '80+',
                    'rating' => '4,7'
                ]
            ];

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
            <p1><a href="dokumenty/podmienky.pdf" target="_blank" rel="noopener noreferrer">Celé Portfólio TU</a></p1>
        </div>
    </div>

</div>