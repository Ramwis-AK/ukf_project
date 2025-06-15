<?php
// Define banner slides (buttons removed)
$bannerSlides = [
    [
        'item_class' => 'item-1',
        'category' => 'Our Courses',
        'title' => 'With Scholar Teachers, Everything Is Easier',
        'description' => 'Scholar is free CSS template designed by TemplateMo for online educational related websites. This layout is based on the famous Bootstrap v5.3.0 framework.',
    ],
    [
        'item_class' => 'item-2',
        'category' => 'Best Result',
        'title' => 'Get the best result out of your effort',
        'description' => 'You are allowed to use this template for any educational or commercial purpose. You are not allowed to re-distribute the template ZIP file on any other website.',
    ],
    [
        'item_class' => 'item-3',
        'category' => 'Online Learning',
        'title' => 'Online Learning helps you save the time',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temporious incididunt ut labore et dolore magna aliqua suspendisse.',
    ]
];
?>

<!-- Main Banner Area with taller height -->
<style>
    .main-banner {
        min-height: 300px;
        display: flex;
        align-items: center;
    }
    .main-banner .item {
        min-height: 550px;
        display: flex;
        align-items: center;
    }
</style>

<div class="main-banner" id="top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-banner">

                    <?php foreach ($bannerSlides as $slide) : ?>
                        <div class="item <?php echo $slide['item_class']; ?>">
                            <div class="header-text">
                                <h2><?php echo $slide['title']; ?></h2>
                                <p><?php echo $slide['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
