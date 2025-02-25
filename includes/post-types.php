<?php
/**
 * Register Custom Post Types
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_register_post_types() {
    // Service Requests CPT
    register_post_type('service_request', [
        'labels' => [
            'name' => 'Service Requests',
            'singular_name' => 'Service Request',
            'add_new' => 'Add New Request',
            'add_new_item' => 'Add New Request',
            'edit_item' => 'Edit Request',
            'view_item' => 'View Request',
            'search_items' => 'Search Requests',
            'not_found' => 'No requests found'
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_rest' => true
    ]);

    // Technicians CPT
    register_post_type('technician', [
        'labels' => [
            'name' => 'Technicians',
            'singular_name' => 'Technician',
            'add_new' => 'Add New Technician',
            'add_new_item' => 'Add New Technician',
            'edit_item' => 'Edit Technician',
            'view_item' => 'View Technician',
            'search_items' => 'Search Technicians',
            'not_found' => 'No technicians found',
            'menu_name' => 'Technicians'
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-admin-users',
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => true
    ]);
}
add_action('init', 'sms_register_post_types');