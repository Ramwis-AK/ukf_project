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
                            <?php
                            $base_url = "//localhost/ukf_project/scholar/";
                            $image_url = $base_url . $banner['image_path'];
                            ?>
                            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Banner Image">
                            <div class="banner-overlay"></div>
                            <div class="header-text"></div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
