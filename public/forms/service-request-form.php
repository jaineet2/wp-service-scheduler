<?php
/**
 * Service Request Form Handler
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
function sms_handle_service_request_submission() {
    if (!isset($_POST['submit_service_request'])) return;

    // Verify nonce
    if (!isset($_POST['service_request_nonce']) || 
        !wp_verify_nonce($_POST['service_request_nonce'], 'submit_service_request')) {
        wp_die('Security check failed');
    }

    // Validate required fields
    if (empty($_POST['equipment_type']) || empty($_POST['purchase_date']) || 
        empty($_POST['brand']) || empty($_POST['description'])) {
        wp_die('Please fill all required fields');
    }

    // Create service request
    $post_id = wp_insert_post([
        'post_title'    => 'Service Request - ' . $_POST['equipment_type'] . ' - ' . current_time('Y-m-d H:i:s'),
        'post_content'  => sanitize_textarea_field($_POST['description']),
        'post_status'   => 'publish',
        'post_type'     => 'service_request',
        'post_author'   => get_current_user_id()
    ]);

    if (is_wp_error($post_id)) {
        wp_die('Error creating service request');
    }

    // Update ACF fields
    update_field('equipment_type', sanitize_text_field($_POST['equipment_type']), $post_id);
    update_field('warranty', isset($_POST['warranty']) ? 1 : 0, $post_id);
    update_field('purchase_date', sanitize_text_field($_POST['purchase_date']), $post_id);
    update_field('brand', sanitize_text_field($_POST['brand']), $post_id);
    update_field('status', 'new', $post_id);

    // Redirect with success message
    wp_redirect(add_query_arg('submission', 'success', wp_get_referer()));
    exit;
}
add_action('init', 'sms_handle_service_request_submission');