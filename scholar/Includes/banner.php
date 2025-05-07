<?php
/**
 * Banner component
 */

// Define banner slides
$bannerSlides = [
    [
        'item_class' => 'item-1',
        'category' => 'Our Courses',
        'title' => 'With Scholar Teachers, Everything Is Easier',
        'description' => 'Scholar is free CSS template designed by TemplateMo for online educational related websites. This layout is based on the famous Bootstrap v5.3.0 framework.',
        'buttons' => [
            ['class' => 'main-button', 'url' => '#', 'text' => 'Request Demo'],
            ['class' => 'icon-button', 'url' => '#', 'icon' => 'fa fa-play', 'text' => 'What\'s Scholar?']
        ]
    ],
    [
        'item_class' => 'item-2',
        'category' => 'Best Result',
        'title' => 'Get the best result out of your effort',
        'description' => 'You are allowed to use this template for any educational or commercial purpose. You are not allowed to re-distribute the template ZIP file on any other website.',
        'buttons' => [
            ['class' => 'main-button', 'url' => '#', 'text' => 'Request Demo'],
            ['class' => 'icon-button', 'url' => '#', 'icon' => 'fa fa-play', 'text' => 'What\'s the best result?']
        ]
    ],
    [
        'item_class' => 'item-3',
        'category' => 'Online Learning',
        'title' => 'Online Learning helps you save the time',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temporious incididunt ut labore et dolore magna aliqua suspendisse.',
        'buttons' => [
            ['class' => 'main-button', 'url' => '#', 'text' => 'Request Demo'],
            ['class' => 'icon-button', 'url' => '#', 'icon' => 'fa fa-play', 'text' => 'What\'s Online Course?']
        ]
    ]
];
?>

<!-- Main Banner Area -->
<div class="main-banner" id="top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-banner">

                    <?php foreach ($bannerSlides as $slide) : ?>
                        <div class="item <?php echo $slide['item_class']; ?>">
                            <div class="header-text">
                                <span class="category"><?php echo $slide['category']; ?></span>
                                <h2><?php echo $slide['title']; ?></h2>
                                <p><?php echo $slide['description']; ?></p>
                                <div class="buttons">
                                    <?php foreach ($slide['buttons'] as $button) : ?>
                                        <div class="<?php echo $button['class']; ?>">
                                            <a href="<?php echo $button['url']; ?>">
                                                <?php if (isset($button['icon'])) : ?>
                                                    <i class="<?php echo $button['icon']; ?>"></i>
                                                <?php endif; ?>
                                                <?php echo $button['text']; ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>