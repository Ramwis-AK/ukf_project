<?php
/**
 * Team section template
 *
 * @package Scholar
 */
?>

<div class="team section" id="team">
    <div class="container">
        <div class="row">
            <?php
            // You can fetch team members from database here
            $team_members = [
                [
                    'image' => 'assets/images/member-01.jpg',
                    'category' => 'UX Teacher',
                    'name' => 'Sophia Rose',
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#'
                ],
                [
                    'image' => 'assets/images/member-02.jpg',
                    'category' => 'Graphic Teacher',
                    'name' => 'Cindy Walker',
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#'
                ],
                [
                    'image' => 'assets/images/member-03.jpg',
                    'category' => 'Full Stack Master',
                    'name' => 'David Hutson',
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#'
                ],
                [
                    'image' => 'assets/images/member-04.jpg',
                    'category' => 'Digital Animator',
                    'name' => 'Stella Blair',
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#'
                ]
            ];

            foreach ($team_members as $member) :
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="team-member">
                        <div class="main-content">
                            <img src="<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                            <span class="category"><?php echo $member['category']; ?></span>
                            <h4><?php echo $member['name']; ?></h4>
                            <ul class="social-icons">
                                <li><a href="<?php echo $member['facebook']; ?>"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="<?php echo $member['twitter']; ?>"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="<?php echo $member['linkedin']; ?>"><i class="fab fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>