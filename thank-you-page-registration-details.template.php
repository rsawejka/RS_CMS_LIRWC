<?php

/**
 * @type EE_Transaction $transaction
 * @type boolean        $is_primary
 * @type string         $reg_url_link
 * @type string         $SPCO_attendee_information_url
 */

?>
        <h3><?php esc_html_e('Registration Details', 'event_espresso'); ?></h3>

<?php do_action('AHEE__thank_you_page_registration_details_template__after_heading'); ?>

<div class="ee-registration-details-dv">
    <?php
    $registrations = $transaction->registrations();
    $registrations = is_array($registrations) ? $registrations : [];
    $reg_count     = count($registrations);
    $reg_cntr      = 0;
    $event_name    = '';
    $wait_list     = false;

    foreach ($registrations as $registration) {
        if (! $registration instanceof EE_Registration) {
            continue;
        }

        // Get event dates
        $dates = espresso_list_of_event_dates($registration->event_id(), null, null, false, true, false, false);


        $reg_cntr++;
        if ($event_name != $registration->event_name()) {
            ?>

            <div class="card border-dark p-3">
                <div class="card-body">

            <div class="col-md-12 mb-3">
                <div class="card border-0">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="<?= get_the_permalink($registration->event_ID()) ?>">
                                    <img src="<?= (has_post_thumbnail($registration->event_ID()) ? get_the_post_thumbnail_url($registration->event_ID()) : get_site_url() . "/placeholder.png") ?>"
                                         alt="" class="rounded img-fluid">
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
                                    <strong>Status:</strong>
                                    <?php $registration->e_pretty_status(true) ?>
                                </p>
                                <div class="d-block d-md-none">
                                    <p class="mb-2">
                                        <strong>Price:</strong>:
                                        <?= EEH_Template::format_currency($registration->final_price()) ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Reg code:</strong>
                                        <?= $registration->e('REG_code') ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        <?php $registration->e_pretty_status(true) ?>
                                    </p>
                                </div>
                                <a href="<?= esc_url_raw($registration->edit_attendee_information_url()) ?>"
                                   class="btn btn-primary btn-sm"><i class="fas fa-edit me-2"></i> Edit
                                    registration</a>
                                <a class="btn btn-light btn-sm border"
                                   href="<?= esc_url_raw(
                                       add_query_arg(['token' => $registration->reg_url_link(), 'resend_reg_confirmation' => 'true'], EE_Registry::instance()->CFG->core->thank_you_page_url())
                                   ) ?>">
                                    <i class="fas fa-envelope me-2"></i>
                                    Resend confirmation
                                </a>
                            </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if ($registration->attendee() instanceof EE_Attendee) { ?>
                <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 py-2">Registrant Info</h5>
                </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>First Name:</strong>
                            <?= $registration->attendee()->fname() ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Last Name:</strong>
                            <?= $registration->attendee()->lname() ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong>
                            <?= $registration->attendee()->email() ?>
                        </li>
                    </ul>
            </div>
                </div>
        <?php } ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0 py-2">Occurrences</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach($dates as $date) { ?>
                            <li class="list-group-item">
                                    <i class="fas fa-calendar me-2"></i>
                                    <?= $date->date_range() . ' ' . $date->time_range() ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } if ($is_primary || (! $is_primary && $reg_url_link == $registration->reg_url_link())) { ?>
                    <?php if ($registration->status_ID() === EEM_Registration::status_id_wait_list) { ?>
                        <div class="alert alert-warning mt-4">
                            <h5>You're on a wait list</h5>
                            <p>If any spaces open up, an administrator will reach out to you in order to secure a spot. Transactions listed below include all registrations, even those that you're currently on a wait list for.</p>
                        </div>
                    <?php } ?>

            <?php do_action(
                'AHEE__thank_you_page_registration_details_template__after_registration_table_row',
                $registration
            );
        }
    }
    ?>
                </div>
            </div>
    <?php if ($is_primary && $SPCO_attendee_information_url) { ?>
        <p>
            <a href='<?php echo esc_url_raw($SPCO_attendee_information_url) ?>'>
                <?php esc_html_e("Click here to edit All Attendee Information", 'event_espresso'); ?>
            </a>
        </p>
    <?php } ?>
    <?php
    do_action('AHEE__thank_you_page_registration_details_template__after_registration_details');
    ?>

</div>
<!-- end of .registration-details -->
