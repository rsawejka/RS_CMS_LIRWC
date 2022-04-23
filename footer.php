<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<footer class="site-footer mt-auto pt-5" id="colophon">
<div class="bg-dark text-white py-4" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

            <div class="col-md-3">
                <h5>Test</h5>
                <a href="#" class="d-block">Test</a>
                <?php wp_nav_menu( array( 'theme_location' => 'footer-column-one' ) ); ?>
            </div>
            <div class="col-md-3">
                <h5>Test</h5>
                <a href="#" class="d-block">Test</a>
                <?php wp_nav_menu( array( 'theme_location' => 'footer-column-two' ) ); ?>
            </div>
            <div class="col-md-3">
                <h5>Test</h5>
                <a href="#" class="d-block">Test</a>
                <?php wp_nav_menu( array( 'theme_location' => 'footer-column-three' ) ); ?>
            </div>
            <div class="col-md-3">
                <h5>Test</h5>
                <a href="#" class="d-block">Test</a>
                <?php wp_nav_menu( array( 'theme_location' => 'footer-column-four' ) ); ?>
            </div>



					<div class="site-info">

						<?php understrap_site_info(); ?>

					</div><!-- .site-info -->

				</footer><!-- #colophon -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

