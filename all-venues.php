<?php
/**
 * The template for displaying archive pages
 * Template Name: lirwc-espresso_venues
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
            <h1 class="text-white"><?= get_the_title() ?></h1>
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
                    'post_type' => 'espresso_venues',
                    'orderby' => 'title',
                    'order' => 'asc'

                );

                $query = new WP_Query($args);
                if ($query->have_posts()) {


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
            <a href="' . get_permalink(get_the_ID()) . '"></a>
           ' . espresso_venue_link(null, ' <img src="' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '" class="rounded-top" style="height:200px" alt="' . (get_post_meta(0, '_wp_attachment_image_alt', true) ? get_post_meta(0, '_wp_attachment_image_alt', true) : "Image of " . get_the_title()) . '">') . '
            <div class="p-4">
            <a  class="text-decoration-none" href="' . get_permalink(get_the_ID()) . '"><h5 class="card-title text-truncate mb-1">' . get_the_title() . '</h5></a>
                <p><i class="fas fa-solid fa-city"></i> ' . espresso_venue_name(null, null, 0) . '</p>
                
                <div class="d-flex flex-row"><div><i class="ms-1 fas fa-map-pin me-2"></i></div> ' . '<div class="ms-1">' . (espresso_venue_raw_address('inline', 0, false) ? espresso_venue_raw_address('inline', 0, false) : __('No address', 'lirwc')) . '</div></div>
                ' . espresso_venue_link(null, '<span class="mt-3 btn btn-primary w-100">' . __('View class', 'lirwc') . ' <i class=" fas fa-arrow-right ms-2"></i></span>') . '
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

                echo $result;
                ?>

            </main><!-- #main -->

            <?php
            // Display the pagination component.
            understrap_pagination();
            // Do the right sidebar check.
            get_template_part( 'global-templates/right-sidebar-check' );
            ?>


        </div><!-- #content -->

    </div><!-- #archive-wrapper -->

<?php
get_footer();