<?php
/**
 * The template for displaying archive pages
 * Template Name: lirwc-espresso_events
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

    <div class="bg-primary p-5">
        <div class="container">
            <h1 class="text-white"><?= the_title() ?></h1>
        </div>
    </div>

    <div class="wrapper" id="archive-wrapper">

        <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">


            <!-- Do the left sidebar check -->
            <?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

            <main class="site-main" id="main">

                <?php

                $result = '<div class="container py-5"><div class="row">';
                $args = array(

                    'post_type' => 'espresso_events',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'espresso_event_categories',
                            'field' => 'slug',
                            'terms'    => 'events',
                        ),
                    ),
                    'paged' => get_query_var('paged') ?: 1,
                    'orderby' => 'title',
                    'order' => 'asc',
                    'posts_per_page' => 2

                );

                $query = new WP_Query($args);
                if ($query->have_posts()) {

                    $result .= '<div class="row">';

                    while ($query->have_posts()) {
                        $query->the_post();

                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        $result .= '
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-body p-0">
           <a href=' . espresso_event_link_url(0, false) . '>    
<div class="lirwc-card_image" style="background-image: url(' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 200px;">
           </div>          
            </a>
            <div class="p-4">
               <a class="text-decoration-none" href=' . espresso_event_link_url(0, false) . '>    <h5 class="card-title text-truncate mb-1">' . get_the_title() . '</h5></a>
                <p class="pt-1"><i class="fas fa-solid fa-user me-2"></i> ' . get_post_meta(get_the_ID(), 'instructor', true) . '</p>
                <p><i class="fas fa-calendar-alt me-2"></i> ' . espresso_event_date(null, null, 0, false) . '</p>
                <p><i class="fas fa-map-pin me-2"></i> ' . (espresso_venue_name(0, null, false) ? espresso_venue_name(0, null, false) : __('No location', 'lirwc')) . '</p>
                <a href=' . espresso_event_link_url(0, false) . ' class="btn btn-primary w-100">' . __('View Event', 'lirwc') . ' <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>';

                        //https://eventespresso.com/wiki/ee4-themes-templates/
                        //"<div class='rounded-top' style='height:200px'>" . the_post_thumbnail() . "</div>";
                        /*the_title();
                        espresso_event_date();
                        $classLink = espresso_event_link_url(0, false);
                        echo "<a href='$classLink'>link</a>";*/


                    }
                } else {
                    get_template_part( 'loop-templates/content', 'none' );
                }
                $result .= "</div>";
                $result .= "</div>";

                echo $result;
                ?>

            </main><!-- #main -->

            <?php
            // Display the pagination component.
            understrap_pagination(['total' => $query->max_num_pages]);
            // Do the right sidebar check.
            get_template_part( 'global-templates/right-sidebar-check' );
            wp_reset_query();

            ?>


        </div><!-- #content -->

    </div><!-- #archive-wrapper -->

<?php
get_footer();
