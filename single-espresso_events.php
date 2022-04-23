<?php
/**
 *   * Template Name: single-espresso_events
 * The template for displaying single event.
 *
 * @package Event Espresso
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get table prefix
global $wpdb;

get_header();
$container = get_theme_mod('understrap_container_type');

// Get array of event dates
$dates_array = espresso_list_of_event_dates(0, null, null, false, true, false, false);

// Get array of attendees
$sql = "SELECT CONCAT(ATT_fname, ' ', ATT_lname) name, ATT_email email ";
$sql .= "FROM {$wpdb->prefix}esp_attendee_meta ";
$sql .= "INNER JOIN {$wpdb->prefix}esp_registration ";
$sql .= "USING (ATT_ID) ";
$sql .= "WHERE {$wpdb->prefix}esp_registration.EVT_ID = %d";
$attendees = $wpdb->get_results($wpdb->prepare($sql, get_the_id()));

// Define categories array
$categories = explode(', ', espresso_event_categories(get_the_ID(), true, false));
// Remove "Classes" from array
array_splice($categories, array_search('Classes', $categories), 1);

$category_list = implode(' ', $categories);

$course_num = !empty(get_post_meta(get_the_ID(), 'Course #', true)) ? '#' . get_post_meta(get_the_ID(), 'Course #', true) : '';

foreach ($dates_array as $datetime) {
    if ($datetime instanceof EE_Datetime) {

        $event_obj = $post->EE_Event;
        if ($post->EE_Event instanceof EE_Event) {
            $spaces_taken = EEM_Event::instance()->count_related($event_obj, 'Registration') . "\n";
        }

        $spaces_initial = $datetime->sum_tickets_initially_available();
        $spaces_left = $spaces_initial - $spaces_taken;
        break;
    }
}

?>

    <style>
        .ticket-selector-submit-btn-wrap {
            display: block;
        }

        .ticket-selector-submit-btn, .ticket-selector-submit-btn-wrap {
            float: right;
        }

        .tkt-slctr-tbl {
            display: none;
        }

        .tkt-slctr-tbl + p {
            display: none;
        }
    </style>

<?php EE_Registry::instance()->load_helper('People_View'); ?>

    <div class="bg-primary text-white py-5">
        <div class="container px-3 px-md-5">
            <h1 class="mb-3">
                <?= get_the_title() ?>
                <span style="font-weight:100" class="ms-2"><?= $course_num ?></span>
            </h1>
            <p class="mb-2 small d-inline-block me-3">
                <strong>Category: </strong> <?= $category_list ?>
            </p>
            <?php
            foreach ($dates_array as $datetime) {
                if ($datetime instanceof EE_Datetime) {
                    echo '<p class="mb-2 small d-inline-block me-3">
<strong>Start date:</strong> ' . $datetime->date_range() . ' ' . $datetime->time_range() . '</p>';
                    break;
                }
            }

            $end_date = "";
            foreach ($dates_array as $datetime) {
                if ($datetime instanceof EE_Datetime) {
                    $end_date = '<p class="mb-2 small d-inline-block"><strong>End date:</strong> ' . $datetime->date_range() . ' ' . $datetime->time_range() . '</p>';
                }
            }
            echo $end_date;

            ?>
            <p class="fw-bold mb-2">Instructor(s):</p>
            <?php
            $staff = EEH_People_View::get_people_for_event(get_the_id())['staff'] ?? [];
            if (!empty($staff)) { ?>
                <div class="d-flex">
                    <?php foreach ($staff as $person) { ?>
                        <div class="d-flex me-4">
                            <a href="<?= get_permalink($person->ID()) ?>">
                                <img src="<?= 'https://gravatar.com/avatar/' . md5($person->email()) ?>?s=30&d=mp"
                                     style="width:30px;height:30px"
                                     class="rounded me-2 d-inline-block" alt="">
                                <p class="d-inline-block text-white"><?= $person->full_name() ?></p>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p class="mb-0 small fst-italic ">No instructor(s) assigned</p>
            <?php } ?>
        </div>
    </div>


    <div class="wrapper" id="single-wrapper">

        <div class="<?php echo esc_attr($container); ?> px-3 px-md-5" id="content" tabindex="-1">

            <div class="row mt-5">

                <div class="col-md-8">

                    <div class="mb-3">
                        <div class="lirwc-card_image rounded border"
                             style="background-image: url('<?= has_post_thumbnail(0) ? get_the_post_thumbnail_url() : get_site_url() . "/placeholder.png" ?>'); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 400px;">
                        </div>
                        <?php // echo get_the_post_thumbnail(get_the_id(), 'large', ['class' => 'rounded w-100']); ?>
                    </div>

                    <hr>

                    <section class="my-4">
                        <h5 class="fw-bold">Description</h5>
                        <?php if (get_the_content() !== "") {
                            echo '<p>' . get_the_content() . '</p>';
                        } else {
                            echo '<p class="text-muted">No description provided</p>';
                        } ?>
                    </section>

                    <hr>

                    <section class="my-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-3">Attendees (<?= count($attendees) ?>)</h5>
                        </div>
                        <?php
                        // If user is logged in
                        if (is_user_logged_in()) {
                            // If there are attendees
                            if (count($attendees)) { ?>
                                <div class="row">
                                    <?php foreach ($attendees as $attendee) { ?>
                                        <div class="col-md-3">
                                            <div class="card mb-3 text-center">
                                                <div class="card-body">
                                                    <img src="<?= 'https://gravatar.com/avatar/' . md5($attendee->email) ?>?s=64&d=mp"
                                                         style="width:64px;height:64px"
                                                         class="rounded-circle mb-3" alt="">
                                                    <h5><?= $attendee->name ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="text-muted">No attendees, yet. Why not be the first?</p>
                            <?php }
                        } else { ?>
                            <p class="text-muted">You must be logged in to view attendees.</p>
                        <?php } ?>
                    </section>


                    <hr>

                    <h5 class="fw-bold">Location</h5>

                    <?php if (espresso_venue_has_address()) { ?>

                        <p><strong>Name:</strong> <?php espresso_venue_name() ?></p>
                        <p><strong>Address:</strong> <?php espresso_venue_raw_address('inline') ?>
                        </p>
                        <p><strong>Phone:</strong> <?php espresso_venue_phone() ?></p>
                        <p><strong>Website:</strong> <?php espresso_venue_website() ?></p>
                        <?php espresso_venue_gmap() ?>

                    <?php } else { ?>
                        <p class="text-muted">No specified location has been added.</p>
                    <?php } ?>

                    <hr>

                </div>

                <div class="col-md-4">
                    <div class="card border-dark">
                        <div class="card-header bg-primary text-white p-3">
                            <h5>Details</h5>
                        </div>
                        <div class="card-body">
                            <h4><?= array_shift($post->EE_Event->first_datetime()->tickets())->pretty_price(); ?></h4>

                            <div class="card mb-2 border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                        $onlyOneDate = count($dates_array) === 1 ? "col-md-12" : "col-md-6";
                                        foreach ($dates_array as $datetime) {
                                            if ($datetime instanceof EE_Datetime) {
                                                echo '<div class="' . $onlyOneDate . ' border-top pt-3 mb-3">
                                            <p class="mb-1 small"><i class="fas fa-calendar-alt me-2"></i>' . $datetime->date_range() . '</p>
                                            <p class="small"><i class="fas fa-clock me-2"></i>' . $datetime->time_range() . '</p>
                                        </div>';
                                            }
                                        }

                                        ?>
                                        <div class="border-bottom"></div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold text-center mb-4"><?= $spaces_left . ' of ' . $spaces_initial . ' spaces remaining' ?></h6>

                            <?php if (is_user_logged_in()) {
                                if (current_user_can('is_member')) {
                                    espresso_ticket_selector();
                                } else { ?>
                                    <a href="<?= site_url() ?>/become-member"
                                       class="btn btn-primary w-100">Become a
                                        member to register <i
                                                class="fas fa-arrow-right ms-2"></i></a>
                                <?php }
                            } else { ?>
                                <a href="<?= site_url() ?>/login" class="btn btn-primary w-100">Log
                                    in to register <i
                                            class="fas fa-arrow-right ms-2"></i></a>
                            <?php } ?>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

<?php
get_footer();
