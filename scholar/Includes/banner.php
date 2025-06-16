<?php
// $mysqli = new mysqli('localhost', 'username', 'password', 'database');
//if ($mysqli->connect_error) {
//    die('Chyba pripojenia: ' . $mysqli->connect_error);
//}
//
//// Načítanie bannerov z DB
//$result = $mysqli->query("SELECT * FROM banners ORDER BY id ASC");
//
//$bannerSlides = [];
//while ($row = $result->fetch_assoc()) {
//    $bannerSlides[] = [
//        'item_class' => $row['item_class'],
//        'category' => $row['category'],
//        'title' => $row['title'],
//        'image_path' => $row['image_path'],
//    ];
//}
$bannerSlides = [
    [
        'item_class' => 'item-1',
        'category' => 'Our Courses',
        'title' => 'With Scholar Teachers, Everything Is Easier',
    ],
    [
        'item_class' => 'item-2',
        'category' => 'Best Result',
        'title' => 'Get the best result out of your effort',
    ],
    [
        'item_class' => 'item-3',
        'category' => 'Online Learning',
        'title' => 'Online Learning helps you save the time',
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
        min-height: 600px;
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
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
