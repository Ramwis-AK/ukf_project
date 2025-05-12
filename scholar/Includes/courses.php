<?php
/**
 * Courses component
 */

// Define course filter categories
$courseCategories = [
    ['filter' => '*', 'text' => 'Všetko', 'active' => true],
    ['filter' => '.little', 'text' => 'Detské akcie', 'active' => false],
    ['filter' => '.mid', 'text' => 'Školy', 'active' => false],
    ['filter' => '.big', 'text' => 'Firmy', 'active' => false],
    ['filter' => '.else', 'text' => 'Iné', 'active' => false]
];

// Define course items
$courseItems = [
    [
        'image' => 'assets/images/course-01.jpg',
        'categories' => ['little'],
        'author' => 'Stella Blair',
        'title' => 'Learn Web Design'
    ],
    [
        'image' => 'assets/images/course-02.jpg',
        'categories' => ['little'],
        'author' => 'Cindy Walker',
        'title' => 'Web Development Tips'
    ],
    [
        'image' => 'assets/images/course-03.jpg',
        'categories' => ['mid'],
        'author' => 'David Hutson',
        'title' => 'Latest Web Trends'
    ],
    [
        'image' => 'assets/images/course-04.jpg',
        'categories' => ['mid'],
        'author' => 'Stella Blair',
        'title' => 'Online Learning Steps'
    ],
    [
        'image' => 'assets/images/course-05.jpg',
        'categories' => ['big'],
        'author' => 'Sophia Rose',
        'title' => 'Be a WordPress Master'
    ],
    [
        'image' => 'assets/images/course-06.jpg',
        'categories' => ['big'],
        'author' => 'David Hutson',
        'title' => 'Full Stack Developer'
    ],
    [
        'image' => 'assets/images/course-05.jpg',
        'categories' => ['else'],
        'author' => 'Sophia Rose',
        'title' => 'Be a WordPress Master'
    ],
    [
        'image' => 'assets/images/course-05.jpg',
        'categories' => ['else'],
        'author' => 'Sophia Rose',
        'title' => 'Be a WordPress Master'
    ]
];

// Helper function to get CSS classes for course items
function getCourseClasses($categories) {
    return implode(' ', $categories);
}
?>

<!-- Courses Section -->
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
                    <a class="<?php echo $category['active'] ? 'is_active' : ''; ?>" href="#!" data-filter="<?php echo $category['filter']; ?>"><?php echo $category['text']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Course Items -->
        <div class="row event_box">
            <?php foreach ($courseItems as $course) : ?>
                <div class="col-lg-4 col-md-6 align-self-center mb-30 event_outer <?php echo getCourseClasses($course['categories']); ?>">
                    <div class="events_item">
                        <div class="thumb">
                            <a href="#"><img src="<?php echo $course['image']; ?>" alt=""></a>
                        </div>
                        <div class="down-content">
                            <span class="author"><?php echo $course['author']; ?></span>
                            <h4><?php echo $course['title']; ?></h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>