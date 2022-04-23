<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="card my-5">
                <div class="card-body p-5">
                    <h1 class="h3">Page Not Found</h1>
                    <p>Sorry, the page you're looking for on this site could not be found. It may have been moved, deleted, or may have not existed.</p>

                    <?php get_search_form(); ?>

                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>


<?php
get_footer();
