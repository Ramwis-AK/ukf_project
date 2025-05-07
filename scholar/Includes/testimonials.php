<?php
/**
 * Testimonials section template
 *
 * @package Scholar
 */
?>

<div class="section testimonials">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="owl-carousel owl-testimonials">
                    <?php
                    // You can fetch testimonials from database here
                    $testimonials = [
                        [
                            'quote' => '"Please tell your friends or collegues about TemplateMo website. Anyone can access the website to download free templates. Thank you for visiting."',
                            'image' => 'assets/images/testimonial-author.jpg',
                            'category' => 'Full Stack Master',
                            'name' => 'Claude David'
                        ],
                        [
                            'quote' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravid."',
                            'image' => 'assets/images/testimonial-author.jpg',
                            'category' => 'UI Expert',
                            'name' => 'Thomas Jefferson'
                        ],
                        [
                            'quote' => '"Scholar is free website template provided by TemplateMo for educational related websites. This CSS layout is based on Bootstrap v5.3.0 framework."',
                            'image' => 'assets/images/testimonial-author.jpg',
                            'category' => 'Digital Animator',
                            'name' => 'Stella Blair'
                        ]
                    ];

                    foreach ($testimonials as $testimonial) :
                        ?>
                        <div class="item">
                            <p><?php echo $testimonial['quote']; ?></p>
                            <div class="author">
                                <img src="<?php echo $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>">
                                <span class="category"><?php echo $testimonial['category']; ?></span>
                                <h4><?php echo $testimonial['name']; ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-5 align-self-center">
                <div class="section-heading">
                    <h6>TESTIMONIALS</h6>
                    <h2>What they say about us?</h2>
                    <p>You can search free CSS templates on Google using different keywords such as templatemo portfolio, templatemo gallery, templatemo blue color, etc.</p>
                </div>
            </div>
        </div>
    </div>
</div>