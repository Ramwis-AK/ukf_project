<?php
/**
 * Scholar - Online School PHP Template
 * Main index file
 */

// Include configuration
include_once('config/db.php');
include_once('functions/helpers.php');

// Page title
$pageTitle = "Scholar - Online School PHP Template";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title><?php echo $pageTitle; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-scholar.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <!-- separate css -->
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/banner.css">
    <link rel="stylesheet" href="assets/css/service.css">
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="assets/css/courses.css">
    <link rel="stylesheet" href="assets/css/facts.css">
    <link rel="stylesheet" href="assets/css/team.css">
    <link rel="stylesheet" href="assets/css/testimonials.css">
    <link rel="stylesheet" href="assets/css/gallery.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
<!-- Preloader Start -->
<?php include_once('includes/preloader.php'); ?>
<!-- Preloader End -->

<!-- Header Area Start -->
<?php include_once('includes/header.php'); ?>
<!-- Header Area End -->

<!-- Main Banner Start -->
<?php include_once('includes/banner.php'); ?>
<!-- Main Banner End -->

<!-- Services Section Start -->
<?php include_once('includes/services.php'); ?>
<!-- Services Section End -->

<!-- About Us Section Start -->
<?php include_once('includes/about.php'); ?>
<!-- About Us Section End -->

<!-- Courses Section Start -->
<?php include_once('includes/courses.php'); ?>
<!-- Courses Section End -->

<!-- Fun Facts Section Start -->
<?php include_once('includes/facts.php'); ?>
<!-- Fun Facts Section End -->

<!-- Team Section Start -->
<?php include_once('includes/team.php'); ?>
<!-- Team Section End -->

<!-- Testimonials Section Start -->
<?php include_once('includes/testimonials.php'); ?>
<!-- Testimonials Section End -->

<!-- Events Section Start -->
<?php include_once('includes/gallery.php'); ?>
<!-- Events Section End -->

<!-- Contact Us Section Start -->
<?php include_once('includes/contact.php'); ?>
<!-- Contact Us Section End -->

<!-- Footer Start -->
<?php include_once('includes/footer.php'); ?>
<!-- Footer End -->

<!-- Scripts -->
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/counter.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>