<?php
$banners = getBanners();
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

                    <?php foreach ($banners as $banner) : ?>
                        <div class="item <?php echo htmlspecialchars($banner['item_class']); ?>">
                            <div class="header-text">
                                <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
                                <?php if (!empty($banner['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($banner['image_path']); ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
