<?php
/**
 * The template for displaying archive pages
 * Template Name: lirwc-espresso_classes
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$container = get_theme_mod('understrap_container_type');

$category = $_GET['category'] ?? 'courses';
$direction = $_GET['direction'] ?? "asc";
$location = $_GET['location'] ?? "";

?>

    <div class="bg-primary p-5 px-2 px-md-5">
        <div class="container">
            <h1 class="text-white"><?= the_title() ?></h1>
        </div>
    </div>

    <div class="wrapper" id="archive-wrapper">

        <div class="bg-dark p-4">

            <div class="container">
                <form method="get">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <h5 class="text-white">Category</h5>
                            <select name="category" class="form-select w-100">
                                <option value="courses">All Categories</option>
                                <?php
                                $cat = "";
                                $categories = get_term_children(7, "espresso_event_categories");
                                foreach ($categories as $cat) {
                                    $catLink = get_category_link($cat);
                                    $is_selected = $category === get_the_category_by_ID($cat) ? "selected" : "";
                                    echo "<option value='" . get_the_category_by_ID($cat) . "'" . $is_selected . ">";
                                    echo get_the_category_by_ID($cat);
                                    echo "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <h5 class="text-white">Direction</h5>
                            <select name="direction" class="form-select w-100">
                                <option value="desc" <?= $direction === "desc" ? 'selected' : '' ?>>
                                    Name
                                    DESC
                                </option>
                                <option value="asc" <?= $direction === "asc" ? 'selected' : '' ?>>
                                    Name
                                    ASC
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <h5 class="text-white">Location</h5>
                            <select name="location" class="form-select w-100">
                                <option value="">All Locations</option>
                                <?php
                                $query1 = new WP_Query(['post_type' => 'espresso_venues', 'posts_per_page' => 200]);
                                if ($query1->have_posts()) {
                                    while ($query1->have_posts()) {
                                        $query1->the_post();
                                        $is_selected = $location == get_the_ID() ? "selected" : "";
                                        echo '<option value="' . get_the_ID() . '"' . $is_selected . '>' . get_the_title() . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <h5>_</h5>
                            <button type="submit" class="btn btn-light col-12"><i
                                        class="fas fa-sliders me-2"></i> Apply filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">


            <!-- Do the left sidebar check -->
            <?php get_template_part('global-templates/left-sidebar-check'); ?>

            <main class="site-main" id="main">

                <?php
                $result = '<div class="container py-5"><div class="row">';
                $args = [

                    'post_type' => 'espresso_events',
                    'tax_query' => [
                        'relation' => 'AND',
                        [
                            'taxonomy' => 'espresso_event_categories',
                            'field' => 'slug',
                            'terms' => 'courses',
                        ],
                        [
                            'taxonomy' => 'espresso_event_categories',
                            'field' => 'slug',
                            'terms' => $category,
                        ],
                    ],
                    //orderby clauses: espresso_date, title
                    'paged' => get_query_var('paged') ?: 1,
                    'orderby' => 'title',
                    'venue' => 'a',
                    'order' => $direction,
                    'posts_per_page' => 2
                ];

                $query = new WP_Query($args);
                if ($query->have_posts()) {


                    $result .= '<div class="row">';

                    while ($query->have_posts()) {
                        $query->the_post();

                        $venue = espresso_venue_id();

                        if ($location != "") {
                            if ($venue != $location) {
                                continue;
                            }
                        }

                        // Define categories array
                        $categories = explode(', ', espresso_event_categories(get_the_ID(), true, false));
                        // Remove "Courses" from array
                        array_splice($categories, array_search('Courses', $categories), 1);

                        $evt_url = espresso_event_link_url(0, false);
                        $evt_instructor = get_post_meta(get_the_ID(), 'instructor', true);
                        $evt_begins = espresso_event_date(null, null, 0, false);
                        $evt_location = espresso_venue_name(0, null, false) ?? __('No location', 'lirwc');

                        $result .= '
    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-body p-0">' . implode(', ', $categories) . '
            
           <a href=' . $evt_url . '>   
<div class="lirwc-card_image" style="background-image: url(' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 200px;">
           </div>            
           </a>
            <div class="p-4">
            
               <a class="text-decoration-none" href=' . $evt_url . '>
                    <h5 class="card-title text-truncate mb-1">' . get_the_title() . '</h5>
               </a>
               
                <p class="pt-1 small mb-2"><i class="fas fa-solid fa-user me-2"></i> ' . $evt_instructor . '</p>
                
                <p class="small mb-2"><i class="fas fa-calendar-alt me-2"></i> ' . $evt_begins . '</p>
                
                <p class="text-truncate small"><i class="fas fa-map-pin me-2"></i> ' . $evt_location . '</p>
                
                <a href=' . $evt_url . ' class="btn btn-primary w-100">' . __('View class', 'lirwc') . ' <i class="fas fa-arrow-right ms-2"></i></a>
                
                </div>
            </div>
        </div>
    </div>
      ';

                        //https://eventespresso.com/wiki/ee4-themes-templates/
                        //"<div class='rounded-top' style='height:200px'>" . the_post_thumbnail() . "</div>";
                        /*the_title();
                        espresso_event_date();
                        $classLink = espresso_event_link_url(0, false);
                        echo "<a href='$classLink'>link</a>";*/


                        //                        $allTeachers = array();
                        //                        $getTeachers = get_post_meta(get_the_ID(), 'instructor', true);
                        //                        array_push($allTeachers, $getTeachers);
                        //                        print_r($allTeachers);
                        //  echo get_post_meta(get_the_ID(), 'instructor', true);
                    }

                } else {
                    get_template_part('loop-templates/content', 'none');
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
            get_template_part('global-templates/right-sidebar-check');

            wp_reset_query();
            ?>


        </div><!-- #content -->

    </div><!-- #archive-wrapper -->

<?php
get_footer();
