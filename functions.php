<?php

function lirwc_dequeue_styles(){
    wp_dequeue_style('arm_bootstrap_all_css');
    wp_deregister_style('arm_bootstrap_all_css');
}
add_action( 'wp_print_styles', 'lirwc_dequeue_styles', PHP_INT_MAX);