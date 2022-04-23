<?php
/**
 * Template Name: lirwc-user-registrations
 **/

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get user attendee ID
$att_id = get_user_option('EE_Attendee_ID', get_current_user_id());
$contact = EEM_Attendee::instance()->get_one_by_ID(get_user_option('EE_Attendee_ID', get_current_user_id()));

get_header();
?>

    <div class="bg-primary text-center text-white">
        <div class="container py-5">
            <h1>Your Registrations</h1>
        </div>
    </div>

    <div class="container my-4 px-5">

        <?php
        // If user is logged in
        if (is_user_logged_in()) {

            // If current user is a member
            if (current_user_can('is_member')) {

                // If registrations found
                if (isset($contact)) { ?>
                    <div class="row">
                        <?php foreach ($contact->get_many_related('Registration') as $registration) { ?>
                            <div class="col-md-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <a href="<?= get_the_permalink($registration->event_ID()) ?>">
                                                    <div class="lirwc-card_image rounded" style="background-image: url('<?= (has_post_thumbnail($registration->event_ID()) ? get_the_post_thumbnail_url($registration->event_ID()) : get_site_url() . "/placeholder.png") ?>'); background-clip: content-box; background-position: center; background-size: cover; width: 100%; height: 120px;">
                                            </div>

                                                </a>
                                            </div>
                                            <div class="col mt-2">
                                                <a href="<?= get_the_permalink($registration->event_ID()) ?>"
                                                   class="text-decoration-none">
                                                    <h5 class="text-truncate"><?= $registration->event_name() ?></h5>
                                                </a>
                                                <p class="d-none d-md-block">
                                                    <strong>Price:</strong>
                                                    <?= EEH_Template::format_currency($registration->final_price()) ?>
                                                    /
                                                    <strong>Reg code:</strong>
                                                    <?= $registration->reg_code() ?> /
                                                    <strong>TXD ID:</strong>
                                                    <?= $registration->transaction_ID() ?>
                                                </p>
                                                <div class="d-block d-md-none">
                                                    <p class="mb-2">
                                                        <strong>Price:</strong>:
                                                        <?= EEH_Template::format_currency($registration->final_price()) ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong>Reg code:</strong>
                                                        <?= $registration->reg_code() ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong>TXD ID:</strong>
                                                        <?= $registration->transaction_ID() ?>
                                                    </p>
                                                </div>
                                                <a href="<?= $registration->edit_attendee_information_url() ?>"
                                                   class="btn btn-primary btn-sm"><i
                                                            class="fas fa-edit me-2"></i> Edit
                                                    registration</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else {
                    echo 'No registrations found!';
                }

            } else {
                echo 'Become a member first!';
            }


        } else {
            echo 'Please login first!';
        }
        ?>

    </div>

<?php
get_footer();