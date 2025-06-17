<?php
$courseCategories = getCourseCategories();
$courseItems = getCourses();

function getCourseClasses($filter) {
    return ltrim($filter, '.');
}
?>

<section class="section courses" id="courses">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h6>ponuka</h6>
                    <h2>aktuálna ponuka</h2>
                </div>
            </div>
        </div>

        <!-- Course Filters -->
        <ul class="event_filter">
            <?php foreach ($courseCategories as $category) : ?>
                <li>
                    <a class="<?php echo $category['active'] ? 'is_active' : ''; ?>" href="#!" data-filter="<?php echo htmlspecialchars($category['filter']); ?>">
                        <?php echo htmlspecialchars($category['text']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Course Items -->
        <div class="row event_box">
            <?php foreach ($courseItems as $course) : ?>
                <div class="col-lg-4 col-md-6 align-self-center mb-30 event_outer <?php echo getCourseClasses($course['filter']); ?>">
                    <div class="events_item">
                        <div class="thumb">
                            <a href="#"><img src="<?php echo htmlspecialchars($course['image']); ?>" alt=""></a>
                        </div>
                        <div class="down-content">
                            <span class="author"><?php echo htmlspecialchars($course['author']); ?></span>
                            <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
