<?php
/**
 *   * Template Name: single-espresso_venue
 * The template for displaying single event.
 *
 * @package Event Espresso
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');



$theLocation = get_the_title();



$query_params = array(
    'order_by' => 'Datetime.DTT_EVT_start',
    'order' => 'ASC',
    array(
        'status' => 'publish',
        'Datetime.DTT_EVT_start' => array(
            '>',
            date( current_time( 'mysql' ) ),
            'Datetime.DTT_EVT_end' => array(
                '<',
                date( current_time( 'mysql' ) )
            )
        )
    )
);
$events = EEH_Venue_View::get_venue( $post->ID )->events( $query_params );

// start the output
echo '<h3>Upcoming events at this venue</h3><ul>';
// the loop
foreach ( $events as $event ) {
    echo '<li>';
    echo '<a href="' . get_permalink( $event->get( 'EVT_ID' ) ) . '">' . $event->get( 'EVT_name' ) . '</a>';
    echo '</li>';
}
echo '</ul>';


?>
    <div class="wrapper" id="single-wrapper">

        <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

            <div class="row mt-5">

                <div class="col-md-8">
                    <div class="card border-dark">
                        <div class="card-body">
                            <div class="lirwc-card_image" style="background-image: url('<?= has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png" ?>'); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 400px;">
                            </div>
                            <h1><?php the_title() ?></h1>
                            <p><strong>Phone Number:</strong> <?php espresso_venue_phone() ?></p>
                            <?php $gMapLink = get_post_meta( get_the_ID(), 'gMapLink', true ); ?>
                            <p class='venueAddress'><strong>Address:</strong> <?php echo "<span>" . espresso_venue_raw_address('inline') . "</span>" ?><span><a target="_blank" rel="noopener noreferrer" href="<?= $gMapLink ?>"><strong class="ms-3">Get Directions</strong></a></span></p>





                            <p><?php
                                $id = get_the_ID();
                                espresso_venue_gmap($id) ?></p>
                            <?php
                            //wp-query of all classes then show it if the venue matches the page venue

                            $classes = "";
                            $args = array(
                                'post_type' => 'espresso_events',

                            );
                            //echo $theLocation . '<br>';
                            //echo'current location' . strlen($theLocation) . ' ' . $theLocation;
                            //echo '<br>';
?>
                            <h4>Classes at the <?= $theLocation ?> Location</h4>
                            <?php
                            $query = new WP_Query($args);
                            if ($query->have_posts()) {

                                while ($query->have_posts()) {
                                    $query->the_post();
                                    $venue = espresso_venue_name(0, "", false);
                                    $venue = strip_tags($venue);

                                    $className = get_the_title();




                                    if ($venue == $theLocation){
                                        $classes .= '<div>


 <a  class="text-decoration-none" href="' . get_permalink(get_the_ID()) . '">
    <div>
        <div class="card">
           <div class="p-4">
           <h5 class="card-title text-truncate mb-1 "><div class="d-flex flex-row justify-content-between"><div class="col-10 text-truncate">' . get_the_title() . '</div><div><i class="fas fa-solid fa-arrow-right"></i></div></div></h5> 

            </div>
        </div>
    </div>
    </a></div>';
                                    }
                                    else{
                                        $classes .= "";

                                    }


                                }

                            }
                            echo $classes;
                            ?>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">
                    <div class="card border-dark">
                        <div class="card-header bg-primary text-white p-3">
                            <h5>Other Locations</h5>
                        </div>
<?php
$args1 = array(
    'post_type' => 'espresso_venues',

);
$result = "";
$query1 = new WP_Query($args1);
if ($query1->have_posts()) {



    while ($query1->have_posts()) {
        $query1->the_post();
        $newTitle = get_the_title();
      //  echo $newTitle;
      //  echo $theLocation;
        if ($theLocation === $newTitle){
            $result .= " ";
        }else{
            $result .= '<div>

 <a  class="text-decoration-none" href="' . get_permalink(get_the_ID()) . '">
    <div>
        <div class="card">
           <div class="p-4">
           <h5 class="card-title text-truncate mb-1 "><div class="d-flex flex-row justify-content-between"><div>' . get_the_title() . '</div><div><i class="fas fa-solid fa-arrow-right"></i></div></div></h5> 

            </div>
        </div>
    </div>
    </a></div>';
        }




    }
}

?>

                            <?= $result; ?>

                        </div>

                </div>

            </div>


        </div>







<?php
get_footer();
