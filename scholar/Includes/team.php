<?php
$team_members = getTeamMembers();
?>

<div class="team section" id="team">
    <div class="container">
        <div class="row">
            <?php


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