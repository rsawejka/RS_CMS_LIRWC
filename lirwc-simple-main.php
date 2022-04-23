<?php
/**
 * The template for displaying LIRWC Header
 *  * Template Name: lirwc-banner

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
    <div class="wrapper" id="single-wrapper">

        <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

            <div class="row">

                <!-- Do the left sidebar check -->
                <?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

                <main class="site-main mt-4" id="main">

                    <?php
                    while ( have_posts() ) {
                        the_post();

                        the_content();

                    }

                    ?>

                </main><!-- #main -->

                <!-- Do the right sidebar check -->

            </div><!-- .row -->

        </div><!-- #content -->

    </div><!-- #single-wrapper -->

<?php
get_footer();
