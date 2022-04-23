<?php
/** @var EE_Transaction $transaction */
/** @var boolean $revisit */
/** @var string $order_conf_desc */

do_action('AHEE__thank_you_page_overview_template__top', $transaction);

?>

<div class="bg-primary text-center text-white">
    <div class="container py-5">
        <h1>Registration Confirmation</h1>
    </div>
</div>

<div id="espresso-thank-you-page-overview-dv" class="container mt-4">

<?php //if (! $revisit) { ?>
        <div class="alert alert-success p-5">
                <h3>
                    <i class="fas fa-check me-2"></i> You're signed up!
                </h3>
                <p class="mb-3">
                    Your registration has been successfully processed. You'll receive a confirmation email shortly.
                </p>
                <?php if (! empty($TXN_receipt_url)) { ?>
                        <a class="btn btn-primary mt-0 me-1" href="<?= esc_url_raw($TXN_receipt_url); ?>" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i> View order receipt
                        </a>
                <?php } ?>
                <a class="btn btn-light" href="<?= site_url() . '/classes' ?>">
                    Explore other classes
                </a>
        </div>
<?php //} ?>

    <?php do_action('AHEE__thank_you_page_overview_template__content', $transaction); ?>

</div>
<!-- end of espresso-thank-you-page-overview-dv -->

<?php do_action('AHEE__thank_you_page_overview_template__bottom', $transaction); ?>
