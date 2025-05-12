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
                    'category' => 'Web Design',
                    'title' => 'UI Best Practices',
                    'date' => '16 Feb 2036',
                    'duration' => '22 Hours',
                    'price' => '$120'
                ],
                [
                    'image' => 'assets/images/event-02.jpg',
                    'category' => 'Front End',
                    'title' => 'New Design Trend',
                    'date' => '24 Feb 2036',
                    'duration' => '30 Hours',
                    'price' => '$320'
                ],
                [
                    'image' => 'assets/images/event-03.jpg',
                    'category' => 'Full Stack',
                    'title' => 'Web Programming',
                    'date' => '12 Mar 2036',
                    'duration' => '48 Hours',
                    'price' => '$440'
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
                                        <span>Duration:</span>
                                        <h6><?php echo $event['duration']; ?></h6>
                                    </li>
                                    <li>
                                        <span>Cena:</span>
                                        <h6><?php echo $event['price']; ?></h6>
                                    </li>
                                </ul>
                                <a href="#"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>