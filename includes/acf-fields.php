<?php
/**
 * ACF Fields Configuration
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_register_acf_fields() {
    if(!function_exists('acf_add_local_field_group')) return;

    // Service Request Fields
    acf_add_local_field_group([
        'key' => 'group_service_request',
        'title' => 'Service Request Details',
        'fields' => [
            [
                'key' => 'field_equipment_type',
                'label' => 'Equipment Type',
                'name' => 'equipment_type',
                'type' => 'select',
                'choices' => [
                    'ac' => 'AC',
                    'refrigerator' => 'Refrigerator',
                    'water_purifier' => 'Water Purifier',
                    'microwave' => 'Microwave Oven',
                    'tv' => 'TV'
                ],
                'required' => true
            ],
            [
                'key' => 'field_warranty',
                'label' => 'Under Warranty',
                'name' => 'warranty',
                'type' => 'true_false',
                'ui' => true
            ],
            [
                'key' => 'field_purchase_date',
                'label' => 'Purchase Date',
                'name' => 'purchase_date',
                'type' => 'date_picker',
                'required' => true
            ],
            [
                'key' => 'field_brand',
                'label' => 'Brand',
                'name' => 'brand',
                'type' => 'text',
                'required' => true
            ],
            [
                'key' => 'field_status',
                'label' => 'Status',
                'name' => 'status',
                'type' => 'select',
                'choices' => [
                    'new' => 'New',
                    'assigned' => 'Assigned',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled'
                ],
                'default_value' => 'new'
            ],
            [
                'key' => 'field_assigned_technician',
                'label' => 'Assigned Technician',
                'name' => 'assigned_technician',
                'type' => 'relationship',
                'post_type' => ['technician'],
                'filters' => ['search'],
                'max' => 1,
                'return_format' => 'object'
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post_type', 
                    'operator' => '==', 
                    'value' => 'service_request'
                ]
            ]
        ]
    ]);

    // Technician Fields
    acf_add_local_field_group([
        'key' => 'group_technician_details',
        'title' => 'Technician Details',
        'fields' => [
            [
                'key' => 'field_tech_status',
                'label' => 'Status',
                'name' => 'technician_status',
                'type' => 'select',
                'choices' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ],
                'default_value' => 'active',
                'required' => 1
            ],
            [
                'key' => 'field_tech_phone',
                'label' => 'Phone Number',
                'name' => 'technician_phone',
                'type' => 'text',
                'required' => 1
            ],
            [
                'key' => 'field_tech_email',
                'label' => 'Email',
                'name' => 'technician_email',
                'type' => 'email',
                'required' => 1
            ],
            [
                'key' => 'field_tech_specialization',
                'label' => 'Specializations',
                'name' => 'technician_specialization',
                'type' => 'checkbox',
                'choices' => [
                    'ac' => 'AC Repair',
                    'refrigerator' => 'Refrigerator',
                    'water_purifier' => 'Water Purifier',
                    'microwave' => 'Microwave Oven',
                    'tv' => 'TV'
                ],
                'required' => 1
            ],
            [
                'key' => 'field_tech_experience',
                'label' => 'Years of Experience',
                'name' => 'technician_experience',
                'type' => 'number',
                'required' => 1
            ]
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'technician']]
        ]
    ]);

    // Feedback Fields
    acf_add_local_field_group([
        'key' => 'group_service_feedback',
        'title' => 'Service Feedback',
        'fields' => [
            [
                'key' => 'field_rating',
                'label' => 'Rating',
                'name' => 'service_rating',
                'type' => 'number',
                'min' => 1,
                'max' => 5,
                'required' => true
            ],
            [
                'key' => 'field_feedback',
                'label' => 'Feedback',
                'name' => 'service_feedback',
                'type' => 'textarea'
            ]
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'service_request']]
        ]
    ]);
}
add_action('acf/init', 'sms_register_acf_fields');