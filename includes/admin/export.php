
<?php
/**
 * Export Functionality
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function sms_export_reports() {
    if(!isset($_POST['export_reports']) || !current_user_can('manage_options')) {
        return;
    }

    $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : '';
    $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : '';
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    $technician = isset($_POST['technician']) ? intval($_POST['technician']) : '';

    $service_requests = sms_get_filtered_service_requests($date_from, $date_to, $status, $technician);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="service-reports-' . date('Y-m-d') . '.csv"');

    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['Request ID', 'Date', 'Customer', 'Service Type', 'Technician', 'Status']);

    foreach($service_requests as $request) {
        $user = get_user_by('id', $request->post_author);
        $tech = get_field('assigned_technician', $request->ID);
        
        fputcsv($fp, [
            $request->ID,
            get_the_date('Y-m-d', $request->ID),
            $user ? $user->display_name : 'N/A',
            get_field('equipment_type', $request->ID),
            $tech ? $tech->post_title : 'Not Assigned',
            get_field('status', $request->ID)
        ]);
    }

    fclose($fp);
    exit;
}
add_action('admin_init', 'sms_export_reports');