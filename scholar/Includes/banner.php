<?php
$banners = getBanners();
?>
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
                        <?php
                        $base_url = "//localhost/ukf_project/scholar/";
                        $image_url = $base_url . $banner['image_path'];
                        ?>
                        <div class="item <?php echo htmlspecialchars($banner['item_class']); ?>"
                             style="background-image: url('<?php echo htmlspecialchars($image_url); ?>');">
                            <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
                            <div class="banner-overlay"></div>
                            <div class="header-text"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
