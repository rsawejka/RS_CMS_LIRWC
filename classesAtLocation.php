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


$id = $_GET['id'] ?? 0;
$thePost = get_post($id);
$theLocation = $thePost->post_title;
?>
    <div class="bg-primary p-5">
        <div class="container">
            <h1 class="text-white">Classes At <?= $theLocation ?></h1>
        </div>
    </div>
    <div class="wrapper" id="single-wrapper">

        <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">


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

                    if ($venue == $theLocation) {
                        $classes .= '
    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-body p-0">' . espresso_event_categories(get_the_ID(), true, false) . '
            
           <a href=' . espresso_event_link_url(0, false) . '>   
<div class="lirwc-card_image" style="background-image: url(' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 200px;">
           </div>            
           </a>
            <div class="p-4">
               <a class="text-decoration-none" href=' . espresso_event_link_url(0, false) . '>    <h5 class="card-title text-truncate mb-1">' . get_the_title() . '</h5></a>
                <p class="pt-1"><i class="fas fa-solid fa-user me-2"></i> ' . get_post_meta(get_the_ID(), 'instructor', true) . '</p>
                <p><i class="fas fa-calendar-alt me-2"></i> ' . espresso_event_date(null, null, 0, false) . '</p>
                <p class="text-truncate"><i class="fas fa-map-pin me-2"></i> ' . (espresso_venue_name(0, null, false) ? espresso_venue_name(0, null, false) : __('No location', 'lirwc')) . '</p>
                <a href=' . espresso_event_link_url(0, false) . ' class="btn btn-primary w-100">' . __('View class', 'lirwc') . ' <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>';
                    } else {
                        $classes .= "";

                    }


                }

            }
            echo $classes;
            ?>
        </div>

<?php
get_footer();
