<?php
/**
 * Services component
 */

// Define service items
$serviceItems = [
    [
        'icon' => 'assets/images/service-01.png',
        'title' => 'Online Degrees',
        'description' => 'Whenever you need free templates in HTML CSS, you just remember TemplateMo website.',
        'button_url' => '#',
        'button_text' => 'Read More'
    ],
    [
        'icon' => 'assets/images/service-02.png',
        'title' => 'Short Courses',
        'description' => 'You can browse free templates based on different tags such as digital marketing, etc.',
        'button_url' => '#',
        'button_text' => 'Read More'
    ],
    [
        'icon' => 'assets/images/service-03.png',
        'title' => 'Web Experts',
        'description' => 'You can start learning HTML CSS by modifying free templates from our website too.',
        'button_url' => '#',
        'button_text' => 'Read More'
    ]
];
?>

<!-- Services Section -->
<div class="services section" id="services">
    <div class="container">
        <div class="row">

            <?php foreach ($serviceItems as $service) : ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="icon">
                            <img src="<?php echo $service['icon']; ?>" alt="<?php echo $service['title']; ?>">
                        </div>
                        <div class="main-content">
                            <h4><?php echo $service['title']; ?></h4>
                            <p><?php echo $service['description']; ?></p>
                            <div class="main-button">
                                <a href="<?php echo $service['button_url']; ?>"><?php echo $service['button_text']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>