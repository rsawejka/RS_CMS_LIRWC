<?php
/**
 *  * Template Name: lirwc-login and edit template

 *
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

    <div class="wrapper" id="single-wrapper">
        <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

            <div class="row">
                <h4 class="mt-3"><a class="text-decoration-none" href='<?= get_home_url() ?>'><i class="fas fa-solid fa-arrow-left"></i><span class="ms-2">Back home</span></a></h4>



                <main class="site-main" id="main">

                    <?php
                    while ( have_posts() ) {
                        the_post();
                        the_content();


                    }
                    ?>

                </main><!-- #main -->



            </div><!-- .row -->

        </div><!-- #content -->

    </div><!-- #single-wrapper -->

<?php
get_footer();
