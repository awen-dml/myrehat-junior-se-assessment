<?php
/**
 * MyRehat Test child theme functions.
 */

add_action( 'wp_enqueue_scripts', 'myrehat_test_assets' );

function myrehat_test_assets() {
    wp_enqueue_style(
        'myrehat-test-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'myrehat-test-trails',
        get_stylesheet_directory_uri() . '/trails.js',
        array(),
        '1.0.0',
        true
    );
}

/**
 * Return the translated booking form title.
 */
function myrehat_test_booking_title() {
    return __( 'Book a guided hike', 'myrehat-test' );
}

/**
 * Process booking POST data securely.
 */
add_action( 'template_redirect', 'myrehat_test_booking_handler' );

function myrehat_test_booking_handler() {
    if (
        'POST' !== $_SERVER['REQUEST_METHOD'] ||
        ! isset( $_POST['trails_booking'] )
    ) {
        return;
    }

    if (
        ! isset( $_POST['trails_booking_nonce'] ) ||
        ! is_string( $_POST['trails_booking_nonce'] )
    ) {
        wp_die( 'Invalid booking request.', 'Booking error', array( 'response' => 403 ) );
    }

    $nonce = sanitize_text_field(
        wp_unslash( $_POST['trails_booking_nonce'] )
    );

    if ( ! wp_verify_nonce( $nonce, 'myrehat_booking_action' ) ) {
        wp_die( 'Invalid booking request.', 'Booking error', array( 'response' => 403 ) );
    }

    if (
        ! isset( $_POST['date'] ) ||
        ! is_string( $_POST['date'] )
    ) {
        wp_die( 'A valid booking date is required.', 'Booking error', array( 'response' => 400 ) );
    }

    $date = sanitize_text_field( wp_unslash( $_POST['date'] ) );

    if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
        wp_die( 'Invalid date format.', 'Booking error', array( 'response' => 400 ) );
    }

    $year  = (int) substr( $date, 0, 4 );
    $month = (int) substr( $date, 5, 2 );
    $day   = (int) substr( $date, 8, 2 );

    if ( ! checkdate( $month, $day, $year ) ) {
        wp_die( 'Invalid calendar date.', 'Booking error', array( 'response' => 400 ) );
    }

    // Store only validated data; this is not a permanent booking record.
    set_transient( 'trails_last_booking', $date, HOUR_IN_SECONDS );
}

/**
 * Increment a trail view counter.
 * The hook supplies both the trail ID and the increment amount.
 */
add_action( 'myrehat_trail_viewed', 'myrehat_test_count_view', 10, 2 );

function myrehat_test_count_view( $trail_id, $count = 1 ) {
    if ( ! is_scalar( $trail_id ) || ! is_numeric( $trail_id ) ) {
        return;
    }

    $trail_id = absint( $trail_id );

    if ( 0 === $trail_id ) {
        return;
    }

    $count = is_numeric( $count ) ? (int) $count : 1;

    if ( $count < 1 ) {
        return;
    }

    $key   = 'trail_views_' . $trail_id;
    $total = (int) get_option( $key, 0 );

    update_option( $key, $total + $count );
}
