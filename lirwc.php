<?php

/*
Plugin Name: LIRWC
Plugin URI: https://wctc.edu
Description: Show up coming events with a short code
Version: 0.1
Author: AR Ventures
Author URI: https://wctc.edu
Text Domain: lirwc
*/

namespace lirwcShortCode;

use WP_Query;

class upcomingCourses
{
    //static attribute to hold the single instance
    /**
     * @var static $instance The instance of the page
     */
    private static $instance;

    // make constructor private so objects cannot be created outside of file

    /**
     * LIRWC shortcode constructor.
     */
    private function __construct()
    {

        add_action('wp_head', array($this, 'lirwc_dequeue_styles'), 7);
        add_action('wp_footer', array($this, 'lirwc_dequeue_styles'), 7);

        // Change the default Event Espresso slug
        add_filter('FHEE__EE_Register_CPTs__register_CPT__rewrite', [$this, 'ee_custom_venues_slug'], 10, 2);

        // Register the LIRWC shortcodes
        add_shortcode("lirwc_upcomming_events", array($this, 'upcomingEvents'));
        add_shortcode("lirwc_locations", array($this, 'lirwcLocations'));

        // Flatten 'posts_join' filters so that all come before priority 10,
        // because the arm one fails at default priority of 11
        add_filter('posts_where', function ($where) {
            global $wp_filter;
            $wp_filter['posts_join']->callbacks = array_values($wp_filter['posts_join']->callbacks);
            return $where;
        }, 99);

        add_action('init', array($this, 'addRewriteRules'));


        add_filter('post_type_link', array($this, 'rewriteEventPermalink'), 1, 4);

        add_action('init', [$this, 'lirwc_register_menus']);

    }

    /**
     * @return mixed make sure output is new instance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance == new self();
        }

        return self::$instance;
    }

    public function rewriteEventPermalink($post_link, $post, $leave_name = false, $sample = false)
    {
        $ee_slug = get_option('ee_config')->core->event_cpt_slug;
        if (is_object_in_term($post->ID, 'espresso_event_categories', 'courses')) {
            $post_link = str_replace('/' . $ee_slug . '/', '/courses/', $post_link);
        } else if (is_object_in_term($post->ID, 'espresso_event_categories', 'events')) {
            $post_link = str_replace('/' . $ee_slug . '/', '/events/', $post_link);
        }
        return $post_link;

    }

    public function lirwc_register_menus()
    {
        register_nav_menus(
            array(
                'footer-column-one' => __('Footer Column 1', 'lirwc'),
                'footer-column-two' => __('Footer Column 2', 'lirwc'),
                'footer-column-three' => __('Footer Column 3', 'lirwc'),
                'footer-column-four' => __('Footer Column 4', 'lirwc'),
            )
        );
    }

    public function addRewriteRules()
    {
        add_rewrite_rule('courses/(?!page)([^/]+)?/?$', 'index.php?espresso_events=$matches[1]', 'top');
        add_rewrite_rule('events/ß(?!page)([^/]+)?/?$', 'index.php?espresso_events=$matches[1]', 'top');
    }

    public function ee_custom_venues_slug($slug, $post_type)
    {
        if ($post_type == 'espresso_venues') {
            return ['slug' => 'locations'];
        }
        return $slug;
    }

    public function lirwc_dequeue_styles()
    {
        wp_dequeue_style('arm_bootstrap_all_css');
        wp_deregister_style('arm_bootstrap_all_css');
        wp_dequeue_style('font-awesome-official-v4shim');
        wp_deregister_style('font-awesome-official-v4shim');
        wp_dequeue_style('areoi-style-index');
        wp_deregister_style('areoi-style-index');
    }

    function upcomingEvents()
    {

        ///show only upcoming events
        $args = array(

            'post_type' => 'espresso_events',
            'tax_query' => array(
                array(
                    'taxonomy' => 'espresso_event_categories',
                    'field' => 'slug',
                    'terms' => 'courses',
                ),
            ),
            'orderby' => 'date',
            'order' => 'asc',
            'posts_per_page' => '3',

        );

        $query = new WP_Query($args);

        $result = '<div class="container py-5">';
        $result .= '
        <div class="d-flex justify-content-between">
            <h2 class="mb-4">Upcoming Courses</h2>
            <a href="' . site_url('courses') . '" class="btn btn-link text-decoration-none">View all courses <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
';
//what used to be used to pull the images
// echo "<img src="' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '" class="rounded-top" style="height:200px" alt="' . (get_post_meta(0, '_wp_attachment_image_alt', true) ? get_post_meta(0, '_wp_attachment_image_alt', true) : "Image of " . get_the_title()) . '">"
        if ($query->have_posts()) {

            $result .= '<div class="row">';

            while ($query->have_posts()) {
                $query->the_post();

                $result .= '
    <div class="col-md-3">
        <div class="card">
            <div class="card-body p-0">
          <a href=' . espresso_event_link_url(0, false) . '>
          <div class="lirwc-card_image" style="background-image: url(' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 200px;">
           </div>
               
          </a>
            <div class="p-4">
                <h5 class="card-title text-truncate mb-1"> <a class="text-decoration-none" href=' . espresso_event_link_url(0, false) . '>' . get_the_title() . '</a></h5>
                <p><i class="fas fa-calendar-alt me-2"></i> ' . espresso_event_date(null, null, 0, false) . '</p>
                <p class="text-truncate"><i class="fas fa-map-pin me-2"></i> ' . (espresso_venue_name(0, null, false) ? espresso_venue_name(0, null, false) : __('No location', 'lirwc')) . '</p>
                <a href=' . espresso_event_link_url(0, false) . ' class="btn btn-primary w-100">' . __('View class', 'lirwc') . ' <i class="fas fa-arrow-right ms-2"></i></a>
               
                </div>
            </div>
        </div>
    </div>';

            }

            $result .= '</div>';

            wp_reset_postdata();

        } else {
            $result .= '<p>' . __('No upcoming courses - check back soon!', 'lirwc') . '</p>';
        }

        $result .= '</div>';

        return $result;

    }


    function lirwcLocations()
    {
        ///show only upcoming events
        $args1 = array(

            'post_type' => 'espresso_venues',
            'orderby' => 'date',
            'order' => 'asc',
            'tax_query' => array(
                array(
                    'taxonomy' => 'espresso_venue_categories',
                    'field' => 'slug',
                    'terms' => 'lirwc-locations',
                ),
            ),

        );

        $query1 = new WP_Query($args1);

        $result1 = '<div class="container py-5">';
        $result1 .= '
        <div class="d-flex justify-content-between">
            <h2 class="mb-4">Locations</h2>
            <a href="' . site_url('locations') . '" class="btn btn-link text-decoration-none">View all Locations <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
';

        if ($query1->have_posts()) {

            $result1 .= '<div class="row">';

            while ($query1->have_posts()) {
                $query1->the_post();

                $result1 .= '
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body p-0">
           <a href="' . strip_tags(espresso_venue_link()) . '">
<div class="lirwc-card_image" style="background-image: url(' . (has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png") . '); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 200px;">
           </div>         
            </a>
            <div class="p-4">
                <h5 class="card-title text-truncate mb-1"> <a class="text-decoration-none" href=' . espresso_venue_link(0, false) . get_the_title() . '</a></h5>';

                $result1 .= '

                <a href="' . strip_tags(espresso_venue_link()) . '" class="btn btn-primary w-100">
                ' . __('View location', 'lirwc') . ' <i class="fas fa-arrow-right ms-2"></i>
                </a>
               
                </div>
            </div>
        </div>
    </div>';

            }

            $result1 .= '</div>';

            wp_reset_postdata();

        } else {
            $result1 .= '<p>' . __('No locations - check back soon!', 'lirwc') . '</p>';
        }

        $result1 .= '</div>';

        return $result1;

    }

    private function __clone()
    {
    }

}

//instantiate our plug in(create the class object and run the constructor)
upcomingCourses::getInstance();