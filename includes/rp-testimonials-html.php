<div class="wrap">

</div>
<?php
  
$args = array(
        'meta_query' => array(
            array(
                'key' => 'review_rating',
                'value' => 5,
                'compare' => '<='
            )
        ),
        'orderby' => 'user_registered',
        'order' => 'DESC'
    );

$user_query = new WP_User_Query( $args );

$users = $user_query->get_results();
?>
<div class="row">
<?php

$users = array_slice( $users, 0, 6 );

foreach( $users as $user ):
    $user_meta = get_user_meta( $user->ID );

    ?>

    <div class="col-md-6 my-3">
        <div class="card" style="height: 320px">
            <div class="card-header bg-dark text-white">
                <span class="user-full-name"><?= $user_meta['first_name'][1] . " " . $user_meta['last_name'][1] ?></span>
                <div class="user-ratings float-right" data-ratings="<?= $user_meta['review_rating'][0] ?>"></div>
            </div>
            <div class="card-body">
                <strong><em>"<?= $user->review_content ?>"</em></strong>
            </div>
        </div>
    </div>

<?php
    endforeach;
?>
</div>